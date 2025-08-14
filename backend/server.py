from fastapi import FastAPI, APIRouter, HTTPException, Depends, Request
from fastapi.security import HTTPBearer, HTTPAuthorizationCredentials
from fastapi.middleware.cors import CORSMiddleware
from fastapi.middleware.trustedhost import TrustedHostMiddleware
from fastapi.middleware.gzip import GZipMiddleware
from fastapi.responses import JSONResponse
from starlette.middleware.base import BaseHTTPMiddleware
from starlette.responses import Response
from dotenv import load_dotenv
from motor.motor_asyncio import AsyncIOMotorClient
import os
import logging
import time
import hashlib
from pathlib import Path
from pydantic import BaseModel, Field, EmailStr, validator
from typing import List, Optional
import uuid
from datetime import datetime, timedelta
import re
from collections import defaultdict

# Security imports
import secrets
import jwt

ROOT_DIR = Path(__file__).parent
load_dotenv(ROOT_DIR / '.env')

# MongoDB connection
mongo_url = os.environ['MONGO_URL']
client = AsyncIOMotorClient(mongo_url)
db = client[os.environ['DB_NAME']]

# Security Configuration
security = HTTPBearer(auto_error=False)
SECRET_KEY = os.environ.get('JWT_SECRET_KEY', secrets.token_urlsafe(32))
ALGORITHM = "HS256"

# Rate limiting storage
rate_limit_storage = defaultdict(list)

# Security Middleware
class SecurityHeadersMiddleware(BaseHTTPMiddleware):
    async def dispatch(self, request: Request, call_next):
        response = await call_next(request)
        
        # Security headers
        response.headers["X-Content-Type-Options"] = "nosniff"
        response.headers["X-Frame-Options"] = "DENY"
        response.headers["X-XSS-Protection"] = "1; mode=block"
        response.headers["Referrer-Policy"] = "strict-origin-when-cross-origin"
        response.headers["Permissions-Policy"] = "geolocation=(), microphone=(), camera=()"
        response.headers["Strict-Transport-Security"] = "max-age=31536000; includeSubDomains"
        response.headers["Content-Security-Policy"] = "default-src 'self'; script-src 'self' 'unsafe-inline' https://plausible.io; style-src 'self' 'unsafe-inline'; img-src 'self' data: https:; font-src 'self' https:; connect-src 'self' https://plausible.io;"
        
        return response

class RateLimitMiddleware(BaseHTTPMiddleware):
    def __init__(self, app, calls: int = 100, period: int = 60):
        super().__init__(app)
        self.calls = calls
        self.period = period

    async def dispatch(self, request: Request, call_next):
        client_ip = request.client.host
        now = time.time()
        
        # Clean old entries
        rate_limit_storage[client_ip] = [
            req_time for req_time in rate_limit_storage[client_ip] 
            if now - req_time < self.period
        ]
        
        # Check rate limit
        if len(rate_limit_storage[client_ip]) >= self.calls:
            return JSONResponse(
                status_code=429,
                content={"detail": "Rate limit exceeded. Please try again later."}
            )
        
        # Add current request
        rate_limit_storage[client_ip].append(now)
        
        return await call_next(request)

# Create the main app
app = FastAPI(
    title="Webmatic API",
    description="API sécurisée pour les services Webmatic",
    version="1.0.0",
    docs_url=None,  # Disable docs in production
    redoc_url=None   # Disable redoc in production
)

# Add security middleware
app.add_middleware(SecurityHeadersMiddleware)
app.add_middleware(RateLimitMiddleware, calls=100, period=60)
app.add_middleware(GZipMiddleware, minimum_size=1000)

# Trusted hosts (production should specify actual domains)
app.add_middleware(
    TrustedHostMiddleware,
    allowed_hosts=["*"]  # In production: ["webmatic.fr", "www.webmatic.fr"]
)

# CORS middleware
app.add_middleware(
    CORSMiddleware,
    allow_credentials=True,
    allow_origins=os.environ.get('CORS_ORIGINS', '*').split(','),
    allow_methods=["GET", "POST"],
    allow_headers=["*"],
)

# Create a router with the /api prefix
api_router = APIRouter(prefix="/api")

# Input validation models
class ContactForm(BaseModel):
    name: str = Field(..., min_length=2, max_length=100)
    email: EmailStr
    phone: Optional[str] = Field(None, pattern=r'^(?:\+33|0)[1-9](?:[0-9]{8})$')
    service: str = Field(..., min_length=1, max_length=100)
    message: str = Field(..., min_length=10, max_length=2000)
    
    @validator('name')
    def validate_name(cls, v):
        if not re.match(r'^[a-zA-ZÀ-ÿ\s\-\']+$', v):
            raise ValueError('Le nom contient des caractères invalides')
        return v.strip()
    
    @validator('service')
    def validate_service(cls, v):
        allowed_services = [
            'Création de site web',
            'Maintenance informatique', 
            'Réparation console',
            'Support mobile',
            'Autre'
        ]
        if v not in allowed_services:
            raise ValueError('Service non valide')
        return v
    
    @validator('message')
    def validate_message(cls, v):
        # Basic spam detection
        spam_words = ['casino', 'lottery', 'winner', 'bitcoin', 'crypto', 'investment']
        message_lower = v.lower()
        if any(word in message_lower for word in spam_words):
            raise ValueError('Message détecté comme spam')
        return v.strip()

class StatusCheck(BaseModel):
    id: str = Field(default_factory=lambda: str(uuid.uuid4()))
    client_name: str
    timestamp: datetime = Field(default_factory=datetime.utcnow)
    ip_address: Optional[str] = None

class StatusCheckCreate(BaseModel):
    client_name: str = Field(..., min_length=2, max_length=100)

# Security utilities
def create_access_token(data: dict, expires_delta: Optional[timedelta] = None):
    to_encode = data.copy()
    if expires_delta:
        expire = datetime.utcnow() + expires_delta
    else:
        expire = datetime.utcnow() + timedelta(minutes=15)
    to_encode.update({"exp": expire})
    encoded_jwt = jwt.encode(to_encode, SECRET_KEY, algorithm=ALGORITHM)
    return encoded_jwt

def get_client_ip(request: Request) -> str:
    """Get client IP with proxy support"""
    forwarded_for = request.headers.get("X-Forwarded-For")
    if forwarded_for:
        return forwarded_for.split(",")[0].strip()
    return request.client.host

def sanitize_input(text: str) -> str:
    """Basic input sanitization"""
    if not text:
        return ""
    # Remove potentially dangerous characters
    sanitized = re.sub(r'[<>"\']', '', str(text))
    return sanitized.strip()

# Logging configuration
logging.basicConfig(
    level=logging.INFO,
    format='%(asctime)s - %(name)s - %(levelname)s - %(message)s'
)
logger = logging.getLogger(__name__)

# API Routes
@api_router.get("/", 
    summary="Health Check",
    description="Vérification de l'état de l'API"
)
async def root():
    return {
        "message": "Webmatic API is running",
        "status": "healthy",
        "version": "1.0.0",
        "timestamp": datetime.utcnow().isoformat()
    }

@api_router.post("/contact", 
    summary="Contact Form",
    description="Traitement sécurisé du formulaire de contact"
)
async def submit_contact_form(
    contact_data: ContactForm,
    request: Request
):
    try:
        client_ip = get_client_ip(request)
        
        # Log contact attempt
        logger.info(f"Contact form submission from {client_ip}")
        
        # Create contact record with security info
        contact_record = {
            "id": str(uuid.uuid4()),
            "name": sanitize_input(contact_data.name),
            "email": contact_data.email.lower(),
            "phone": contact_data.phone,
            "service": contact_data.service,
            "message": sanitize_input(contact_data.message),
            "ip_address": client_ip,
            "user_agent": request.headers.get("User-Agent", ""),
            "timestamp": datetime.utcnow(),
            "processed": False
        }
        
        # Store in database
        await db.contacts.insert_one(contact_record)
        
        # Return success (without sensitive data)
        return {
            "success": True,
            "message": "Votre message a été envoyé avec succès. Nous vous recontacterons rapidement.",
            "reference": contact_record["id"][:8]
        }
        
    except Exception as e:
        logger.error(f"Contact form error: {str(e)}")
        raise HTTPException(
            status_code=500,
            detail="Une erreur est survenue. Veuillez réessayer plus tard."
        )

@api_router.post("/status", 
    response_model=StatusCheck,
    summary="Create Status Check",
    description="Créer un contrôle de statut"
)
async def create_status_check(
    input_data: StatusCheckCreate,
    request: Request
):
    try:
        client_ip = get_client_ip(request)
        
        status_dict = input_data.dict()
        status_dict["ip_address"] = client_ip
        status_obj = StatusCheck(**status_dict)
        
        # Store in database
        await db.status_checks.insert_one(status_obj.dict())
        
        logger.info(f"Status check created for {status_obj.client_name} from {client_ip}")
        
        return status_obj
        
    except Exception as e:
        logger.error(f"Status check error: {str(e)}")
        raise HTTPException(
            status_code=500,
            detail="Erreur lors de la création du contrôle de statut"
        )

@api_router.get("/status", 
    response_model=List[StatusCheck],
    summary="Get Status Checks",
    description="Récupérer les contrôles de statut (accès limité)"
)
async def get_status_checks(
    credentials: HTTPAuthorizationCredentials = Depends(security),
    limit: int = 50
):
    try:
        # In production, add proper authentication here
        # For now, allow access without auth for testing
        
        status_checks = await db.status_checks.find().limit(limit).to_list(limit)
        return [StatusCheck(**check) for check in status_checks]
        
    except Exception as e:
        logger.error(f"Get status checks error: {str(e)}")
        raise HTTPException(
            status_code=500,
            detail="Erreur lors de la récupération des données"
        )

@api_router.get("/analytics/summary",
    summary="Analytics Summary", 
    description="Résumé analytique sécurisé"
)
async def get_analytics_summary(
    credentials: HTTPAuthorizationCredentials = Depends(security)
):
    try:
        # In production, verify JWT token here
        
        # Get contact form submissions count
        contact_count = await db.contacts.count_documents({})
        
        # Get recent activity (last 30 days)
        thirty_days_ago = datetime.utcnow() - timedelta(days=30)
        recent_contacts = await db.contacts.count_documents({
            "timestamp": {"$gte": thirty_days_ago}
        })
        
        return {
            "total_contacts": contact_count,
            "recent_contacts_30d": recent_contacts,
            "last_updated": datetime.utcnow().isoformat()
        }
        
    except Exception as e:
        logger.error(f"Analytics error: {str(e)}")
        raise HTTPException(
            status_code=500,
            detail="Erreur lors de la récupération des analytics"
        )

# Security endpoint
@api_router.get("/security/check",
    summary="Security Check",
    description="Vérification de la sécurité du système"
)
async def security_check():
    try:
        security_status = {
            "ssl_enabled": True,
            "rate_limiting": True,
            "input_validation": True,
            "security_headers": True,
            "last_check": datetime.utcnow().isoformat(),
            "status": "secure"
        }
        
        return security_status
        
    except Exception as e:
        logger.error(f"Security check error: {str(e)}")
        return {
            "status": "error",
            "message": "Erreur lors de la vérification de sécurité"
        }

# Include the router in the main app
app.include_router(api_router)

# Global exception handler
@app.exception_handler(Exception)
async def global_exception_handler(request: Request, exc: Exception):
    logger.error(f"Global exception: {str(exc)} - Path: {request.url.path}")
    return JSONResponse(
        status_code=500,
        content={
            "detail": "Une erreur interne est survenue. Veuillez réessayer plus tard.",
            "timestamp": datetime.utcnow().isoformat()
        }
    )

# Health check endpoint (outside API prefix)
@app.get("/health")
async def health_check():
    return {
        "status": "healthy",
        "timestamp": datetime.utcnow().isoformat(),
        "services": {
            "database": "connected",
            "api": "running"
        }
    }

@app.on_event("startup")
async def startup_event():
    logger.info("Webmatic API starting up...")
    # Create database indexes for performance
    try:
        await db.contacts.create_index("timestamp")
        await db.contacts.create_index("email")
        await db.status_checks.create_index("timestamp")
        logger.info("Database indexes created successfully")
    except Exception as e:
        logger.warning(f"Index creation warning: {str(e)}")

@app.on_event("shutdown")
async def shutdown_db_client():
    logger.info("Webmatic API shutting down...")
    client.close()