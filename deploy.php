<?php
/**
 * WEBMATIC DEPLOYMENT SCRIPT
 * 
 * Script de déploiement automatique pour le site Webmatic
 * Usage: Placez ce fichier sur votre hébergeur et accédez à web-matic.fr/deploy.php
 * 
 * IMPORTANT: Supprimez ce fichier après déploiement pour des raisons de sécurité !
 */

// Configuration de sécurité
ini_set('display_errors', 1);
error_reporting(E_ALL);
set_time_limit(300); // 5 minutes max

// Configuration du déploiement
define('DEPLOY_PASSWORD', 'webmatic2025!'); // CHANGEZ CE MOT DE PASSE !
define('PROJECT_NAME', 'webmatic');
define('DOMAIN', 'web-matic.fr');

// Chemins
define('ROOT_PATH', __DIR__);
define('FRONTEND_PATH', ROOT_PATH . '/frontend');
define('BACKEND_PATH', ROOT_PATH . '/backend');
define('BUILD_PATH', ROOT_PATH . '/build');
define('PUBLIC_PATH', ROOT_PATH . '/public');

class WebmaticDeployer {
    private $steps = [];
    private $errors = [];
    private $logs = [];
    
    public function __construct() {
        $this->log("=== WEBMATIC DEPLOYMENT SCRIPT ===");
        $this->log("Démarrage du déploiement...");
    }
    
    public function log($message) {
        $timestamp = date('Y-m-d H:i:s');
        $this->logs[] = "[$timestamp] $message";
        echo "<div class='log-entry'>[$timestamp] $message</div>\n";
        flush();
    }
    
    public function error($message) {
        $this->errors[] = $message;
        $this->log("❌ ERREUR: $message");
    }
    
    public function success($message) {
        $this->log("✅ $message");
    }
    
    public function step($name) {
        $this->steps[] = $name;
        $this->log("📋 ÉTAPE: $name");
    }
    
    public function checkRequirements() {
        $this->step("Vérification des prérequis");
        
        $requirements = [
            'PHP >= 7.4' => version_compare(PHP_VERSION, '7.4.0', '>='),
            'cURL extension' => extension_loaded('curl'),
            'JSON extension' => extension_loaded('json'),
            'ZIP extension' => extension_loaded('zip'),
            'Write permission' => is_writable(ROOT_PATH),
        ];
        
        $allGood = true;
        foreach ($requirements as $req => $status) {
            if ($status) {
                $this->success("$req : OK");
            } else {
                $this->error("$req : MANQUANT");
                $allGood = false;
            }
        }
        
        // Vérification Node.js (optionnel pour hébergeur classique)
        $nodeVersion = shell_exec('node --version 2>/dev/null');
        if ($nodeVersion) {
            $this->success("Node.js détecté : " . trim($nodeVersion));
        } else {
            $this->log("⚠️ Node.js non détecté (normal pour hébergeur PHP classique)");
        }
        
        return $allGood;
    }
    
    public function createDirectories() {
        $this->step("Création de la structure de dossiers");
        
        $directories = [
            'assets',
            'assets/css',
            'assets/js',
            'assets/images',
            'api',
            'includes',
            'backup'
        ];
        
        foreach ($directories as $dir) {
            $path = ROOT_PATH . '/' . $dir;
            if (!is_dir($path)) {
                if (mkdir($path, 0755, true)) {
                    $this->success("Dossier créé: $dir");
                } else {
                    $this->error("Impossible de créer le dossier: $dir");
                }
            } else {
                $this->success("Dossier existant: $dir");
            }
        }
    }
    
    public function deployStaticVersion() {
        $this->step("Déploiement de la version statique optimisée");
        
        // Créer index.html avec le contenu complet du site
        $html = $this->generateStaticHTML();
        file_put_contents(ROOT_PATH . '/index.html', $html);
        $this->success("Index.html créé");
        
        // Créer les CSS optimisés
        $css = $this->generateOptimizedCSS();
        file_put_contents(ROOT_PATH . '/assets/css/style.css', $css);
        $this->success("CSS optimisé créé");
        
        // Créer le JavaScript
        $js = $this->generateOptimizedJS();
        file_put_contents(ROOT_PATH . '/assets/js/script.js', $js);
        $this->success("JavaScript créé");
        
        // Créer les pages légales
        $this->createLegalPages();
        $this->success("Pages légales créées");
        
        // Créer API PHP simple
        $this->createPHPAPI();
        $this->success("API PHP créée");
    }
    
    private function generateStaticHTML() {
        return '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Webmatic - L\'informatique côté pratique | Services IT Pommier (69)</title>
    
    <!-- SEO Meta Tags -->
    <meta name="description" content="Webmatic - Services informatiques et développement web à Pommier (69). Expert en création de sites web, maintenance informatique, réparation consoles et smartphones. Devis gratuit.">
    <meta name="keywords" content="développeur web, technicien informatique, Pommier 69, Rhône-Alpes, création site web, maintenance informatique, réparation console, smartphone, SEO">
    <meta name="author" content="Webmatic - Audric">
    <meta name="robots" content="index, follow">
    
    <!-- Geo Meta Tags -->
    <meta name="geo.region" content="FR-69">
    <meta name="geo.placename" content="Pommier">
    <meta name="geo.position" content="45.9044;4.7829">
    
    <!-- Open Graph -->
    <meta property="og:title" content="Webmatic - Services informatiques et développement web">
    <meta property="og:description" content="Expert en développement web et maintenance informatique à Pommier (69). Solutions créatives et professionnelles. Devis gratuit.">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="fr_FR">
    
    <!-- Security Headers -->
    <meta http-equiv="X-Content-Type-Options" content="nosniff">
    <meta http-equiv="X-Frame-Options" content="SAMEORIGIN">
    <meta http-equiv="X-XSS-Protection" content="1; mode=block">
    
    <!-- Styles -->
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Plausible Analytics -->
    <script defer data-domain="' . DOMAIN . '" src="https://plausible.io/js/script.js"></script>
    
    <!-- Structured Data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "LocalBusiness",
        "name": "Webmatic",
        "description": "Services informatiques et développement web professionnel",
        "url": "https://' . DOMAIN . '",
        "telephone": "07 56 91 30 61",
        "address": {
            "@type": "PostalAddress",
            "addressLocality": "Pommier",
            "postalCode": "69380",
            "addressRegion": "Rhône-Alpes",
            "addressCountry": "FR"
        },
        "openingHoursSpecification": [
            {
                "@type": "OpeningHoursSpecification",
                "dayOfWeek": ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"],
                "opens": "08:00",
                "closes": "20:00"
            },
            {
                "@type": "OpeningHoursSpecification", 
                "dayOfWeek": ["Saturday", "Sunday"],
                "opens": "08:00",
                "closes": "18:00"
            }
        ],
        "aggregateRating": {
            "@type": "AggregateRating",
            "ratingValue": "5",
            "reviewCount": "5"
        }
    }
    </script>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="container">
            <div class="nav-brand">
                <div class="logo">
                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none">
                        <rect width="40" height="40" rx="12" fill="url(#gradient)"/>
                        <path d="M12 28V12h4v6h8v-6h4v16h-4v-6h-8v6h-4z" fill="white"/>
                        <defs>
                            <linearGradient id="gradient" x1="0" y1="0" x2="1" y2="1">
                                <stop offset="0%" stop-color="#3B82F6"/>
                                <stop offset="100%" stop-color="#6366F1"/>
                            </linearGradient>
                        </defs>
                    </svg>
                </div>
                <div class="brand-text">
                    <h1>Webmatic</h1>
                    <p>L\'informatique côté pratique</p>
                </div>
            </div>
            <div class="nav-menu">
                <a href="#services">Services</a>
                <a href="#realisations">Réalisations</a>
                <a href="#avis">Avis</a>
                <a href="#contact" class="btn-contact">Contact</a>
            </div>
            <button class="mobile-menu-btn">☰</button>
        </div>
    </nav>

    <!-- Hero Section -->
    <section id="hero" class="hero">
        <div class="container">
            <div class="hero-content">
                <div class="hero-text">
                    <h1>L\'informatique <span class="gradient-text">côté pratique</span></h1>
                    <p>Développeur web expérimenté et technicien informatique passionné. Solutions créatives pour votre présence en ligne et maintenance complète de vos équipements.</p>
                    <div class="hero-buttons">
                        <a href="#services" class="btn btn-primary">⚡ Découvrir mes services</a>
                        <a href="tel:0756913061" class="btn btn-outline">📞 07 56 91 30 61</a>
                    </div>
                    <div class="hero-features">
                        <div class="feature">✅ Devis gratuit</div>
                        <div class="feature">🕒 Intervention rapide</div>
                    </div>
                </div>
                <div class="hero-image">
                    <img src="https://images.unsplash.com/photo-1508361727343-ca787442dcd7" alt="Innovation technologique Webmatic" loading="eager">
                </div>
            </div>
        </div>
    </section>

    <!-- Services -->
    <section id="services" class="services">
        <div class="container">
            <div class="section-header">
                <h2>Mes Services</h2>
                <p>Solutions complètes pour tous vos besoins informatiques et web</p>
            </div>
            <div class="services-grid">
                <div class="service-card">
                    <div class="service-icon">💻</div>
                    <h3>Création de Sites Web</h3>
                    <p>Sites sur mesure qui reflètent votre identité et vos objectifs</p>
                    <ul>
                        <li>✅ Design responsive moderne</li>
                        <li>✅ Optimisation SEO incluse</li>
                        <li>✅ CMS facile à utiliser</li>
                        <li>✅ Hébergement et maintenance</li>
                    </ul>
                </div>
                <div class="service-card">
                    <div class="service-icon">🔧</div>
                    <h3>Maintenance Informatique</h3>
                    <p>Installation et réparation de matériels informatiques complets</p>
                    <ul>
                        <li>✅ Diagnostic complet</li>
                        <li>✅ Réparation sur site</li>
                        <li>✅ Installation matériel</li>
                        <li>✅ Formation utilisateur</li>
                    </ul>
                </div>
                <div class="service-card">
                    <div class="service-icon">🎮</div>
                    <h3>Consoles & Gaming</h3>
                    <p>Services spécialisés pour consoles de jeux et optimisation gaming</p>
                    <ul>
                        <li>✅ Réparation consoles</li>
                        <li>✅ Configuration gaming</li>
                        <li>✅ Accessoires compatibles</li>
                        <li>✅ Optimisation performances</li>
                    </ul>
                </div>
                <div class="service-card">
                    <div class="service-icon">📱</div>
                    <h3>Téléphones & Mobile</h3>
                    <p>Réparation et maintenance de smartphones et appareils mobiles</p>
                    <ul>
                        <li>✅ Réparation écrans</li>
                        <li>✅ Changement batteries</li>
                        <li>✅ Récupération données</li>
                        <li>✅ Configuration appareils</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Réalisations -->
    <section id="realisations" class="realisations">
        <div class="container">
            <div class="section-header">
                <h2>Mes Réalisations</h2>
                <p>Découvrez quelques projets que j\'ai eu le plaisir de réaliser</p>
            </div>
            <div class="projects-grid">
                <div class="project-card">
                    <div class="project-image">
                        <img src="https://images.unsplash.com/photo-1743865319071-929ac8a27bcd" alt="Sakura Massage" loading="lazy">
                    </div>
                    <div class="project-content">
                        <h3>Sakura Massage</h3>
                        <p>Site vitrine pour un institut de massage. Design élégant et apaisant avec système de réservation en ligne.</p>
                        <div class="project-tags">
                            <span>Site Vitrine</span>
                            <span>Responsive</span>
                            <span>Réservation</span>
                        </div>
                        <a href="https://sakuramassage.fr" target="_blank" rel="noopener">Visiter le site →</a>
                    </div>
                </div>
                <div class="project-card">
                    <div class="project-image">
                        <img src="https://images.unsplash.com/photo-1612999105465-d970b00015a8" alt="Hôtel Plaisance" loading="lazy">
                    </div>
                    <div class="project-content">
                        <h3>Hôtel Plaisance</h3>
                        <p>Webmastering complet pour un hôtel. Gestion du contenu, optimisation SEO et maintenance technique.</p>
                        <div class="project-tags">
                            <span>Webmastering</span>
                            <span>SEO</span>
                            <span>Maintenance</span>
                        </div>
                        <a href="https://hotel-plaisance.com" target="_blank" rel="noopener">Visiter le site →</a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Avis -->
    <section id="avis" class="reviews">
        <div class="container">
            <div class="section-header">
                <h2>Avis Clients</h2>
                <p>Ce que disent mes clients sur Google</p>
            </div>
            <div class="reviews-grid">
                <div class="review-card">
                    <div class="review-header">
                        <div class="reviewer-avatar">T</div>
                        <div class="reviewer-info">
                            <h4>Thierry Gray</h4>
                            <p>Local Guide · 35 avis</p>
                            <div class="stars">⭐⭐⭐⭐⭐</div>
                        </div>
                    </div>
                    <p>Audric a ressuscité mon vieux PC à priori endommagé par un virus ! Excellent technicien, et en plus très sympathique et réactif. Je vous le recommande particulièrement.</p>
                    <small>Visité en avril · il y a 3 mois</small>
                </div>
                <div class="review-card">
                    <div class="review-header">
                        <div class="reviewer-avatar">D</div>
                        <div class="reviewer-info">
                            <h4>Doryan HD</h4>
                            <p>9 avis</p>
                            <div class="stars">⭐⭐⭐⭐⭐</div>
                        </div>
                    </div>
                    <p>Personne très compétente et à l\'écoute du projet. Enfin un professionnel qui n\'est pas condescendant dans ce métier. Merci pour votre travail et votre compréhension 🙏</p>
                    <small>Visité en mars 2023 · il y a 2 ans</small>
                </div>
                <div class="review-card">
                    <div class="review-header">
                        <div class="reviewer-avatar">A</div>
                        <div class="reviewer-info">
                            <h4>Annick Feltz</h4>
                            <p>6 avis</p>
                            <div class="stars">⭐⭐⭐⭐⭐</div>
                        </div>
                    </div>
                    <p>Audric m\'a fait mon site sur mesure rapidement et continu le suivi pour de nouvelles modifications, très professionnel je recommande !</p>
                    <small>Visité en décembre 2022 · il y a 2 ans</small>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact -->
    <section id="contact" class="contact">
        <div class="container">
            <div class="contact-content">
                <div class="contact-info">
                    <h2>Contactez-moi</h2>
                    <p>N\'hésitez pas à me contacter pour discuter de votre projet</p>
                    <div class="contact-details">
                        <div class="contact-item">
                            <div class="contact-icon">📞</div>
                            <div>
                                <strong>Téléphone</strong>
                                <a href="tel:0756913061">07 56 91 30 61</a>
                            </div>
                        </div>
                        <div class="contact-item">
                            <div class="contact-icon">📍</div>
                            <div>
                                <strong>Zone d\'intervention</strong>
                                <span>Pommier (69) et région Rhône-Alpes</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="contact-hours">
                    <h3>Horaires d\'ouverture</h3>
                    <div class="hours-table">
                        <div class="hour-row">
                            <span>Lundi</span>
                            <span>08:00 – 20:00</span>
                        </div>
                        <div class="hour-row">
                            <span>Mardi</span>
                            <span>08:00 – 20:00</span>
                        </div>
                        <div class="hour-row">
                            <span>Mercredi</span>
                            <span>08:00 – 20:00</span>
                        </div>
                        <div class="hour-row">
                            <span>Jeudi</span>
                            <span>08:00 – 20:00</span>
                        </div>
                        <div class="hour-row">
                            <span>Vendredi</span>
                            <span>08:00 – 20:00</span>
                        </div>
                        <div class="hour-row">
                            <span>Samedi</span>
                            <span>08:00 – 18:00</span>
                        </div>
                        <div class="hour-row">
                            <span>Dimanche</span>
                            <span>08:00 – 18:00</span>
                        </div>
                    </div>
                    <div class="guarantee">
                        ✅ Devis gratuit et sans engagement
                    </div>
                </div>
            </div>
            
            <!-- Formulaire de contact -->
            <div class="contact-form-section">
                <h3>Envoyez-moi un message</h3>
                <form id="contactForm" class="contact-form" action="api/contact.php" method="POST">
                    <div class="form-row">
                        <input type="text" name="name" placeholder="Nom complet *" required>
                        <input type="email" name="email" placeholder="Email *" required>
                    </div>
                    <div class="form-row">
                        <input type="tel" name="phone" placeholder="Téléphone">
                        <select name="service" required>
                            <option value="">Sélectionnez un service</option>
                            <option value="Création de site web">Création de site web</option>
                            <option value="Maintenance informatique">Maintenance informatique</option>
                            <option value="Réparation console">Réparation console</option>
                            <option value="Support mobile">Support mobile</option>
                            <option value="Autre">Autre</option>
                        </select>
                    </div>
                    <textarea name="message" placeholder="Message *" rows="5" required></textarea>
                    <button type="submit" class="btn btn-primary">Envoyer le message</button>
                </form>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <div class="logo">
                        <svg width="40" height="40" viewBox="0 0 40 40" fill="none">
                            <rect width="40" height="40" rx="12" fill="url(#gradient2)"/>
                            <path d="M12 28V12h4v6h8v-6h4v16h-4v-6h-8v6h-4z" fill="white"/>
                            <defs>
                                <linearGradient id="gradient2" x1="0" y1="0" x2="1" y2="1">
                                    <stop offset="0%" stop-color="#3B82F6"/>
                                    <stop offset="100%" stop-color="#6366F1"/>
                                </linearGradient>
                            </defs>
                        </svg>
                    </div>
                    <div>
                        <h3>Webmatic</h3>
                        <p>L\'informatique côté pratique</p>
                    </div>
                </div>
                <div class="footer-info">
                    <p>&copy; 2025 Webmatic. Tous droits réservés.</p>
                    <p>Développé avec passion par Audric</p>
                </div>
            </div>
            <div class="footer-legal">
                <div class="legal-links">
                    <a href="mentions-legales.html">Mentions légales</a>
                    <a href="cgv.html">CGV</a>
                    <span>🛡️ Site sécurisé</span>
                    <span>👁️ Analytics respectueux</span>
                </div>
                <p>SIRET: [À compléter]</p>
            </div>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="assets/js/script.js"></script>
    
    <!-- Contact Form Handler -->
    <script>
        document.getElementById("contactForm").addEventListener("submit", async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = Object.fromEntries(formData);
            
            try {
                const response = await fetch("api/contact.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.success) {
                    alert("Message envoyé avec succès ! Nous vous recontacterons rapidement.");
                    this.reset();
                } else {
                    alert("Erreur lors de l\'envoi : " + result.message);
                }
            } catch (error) {
                alert("Erreur lors de l\'envoi du message. Veuillez réessayer.");
            }
        });
    </script>
</body>
</html>';
    }
    
    private function generateOptimizedCSS() {
        return '/* WEBMATIC - CSS OPTIMISÉ */
:root {
    --primary: #3b82f6;
    --primary-dark: #1d4ed8;
    --secondary: #6366f1;
    --gray-50: #f8fafc;
    --gray-100: #f1f5f9;
    --gray-600: #475569;
    --gray-800: #1e293b;
    --gray-900: #0f172a;
}

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

html {
    scroll-behavior: smooth;
}

body {
    font-family: "Inter", -apple-system, BlinkMacSystemFont, sans-serif;
    line-height: 1.6;
    color: var(--gray-800);
    background: linear-gradient(135deg, var(--gray-50) 0%, #e0f2fe 100%);
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 20px;
}

/* Navigation */
.navbar {
    position: fixed;
    top: 0;
    width: 100%;
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(10px);
    border-bottom: 1px solid #e2e8f0;
    z-index: 1000;
    padding: 1rem 0;
}

.navbar .container {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.nav-brand {
    display: flex;
    align-items: center;
    gap: 12px;
}

.nav-brand h1 {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--gray-800);
}

.nav-brand p {
    font-size: 0.875rem;
    color: var(--gray-600);
    margin: 0;
}

.nav-menu {
    display: flex;
    align-items: center;
    gap: 2rem;
}

.nav-menu a {
    text-decoration: none;
    color: var(--gray-600);
    font-weight: 500;
    transition: color 0.3s;
}

.nav-menu a:hover {
    color: var(--primary);
}

.btn-contact {
    background: var(--primary) !important;
    color: white !important;
    padding: 8px 20px;
    border-radius: 25px;
    transition: all 0.3s;
}

.btn-contact:hover {
    background: var(--primary-dark) !important;
    transform: translateY(-2px);
}

.mobile-menu-btn {
    display: none;
    background: none;
    border: none;
    font-size: 1.5rem;
    cursor: pointer;
}

/* Buttons */
.btn {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 12px 24px;
    border-radius: 25px;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s;
    border: none;
    cursor: pointer;
}

.btn-primary {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: white;
    box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
}

.btn-primary:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(59, 130, 246, 0.4);
}

.btn-outline {
    border: 2px solid var(--primary);
    color: var(--primary);
    background: transparent;
}

.btn-outline:hover {
    background: var(--primary);
    color: white;
}

/* Hero Section */
.hero {
    padding: 120px 0 80px;
    min-height: 100vh;
    display: flex;
    align-items: center;
}

.hero-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    align-items: center;
}

.hero-text h1 {
    font-size: 3.5rem;
    font-weight: 700;
    line-height: 1.1;
    margin-bottom: 1.5rem;
}

.gradient-text {
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.hero-text p {
    font-size: 1.25rem;
    color: var(--gray-600);
    margin-bottom: 2rem;
    line-height: 1.7;
}

.hero-buttons {
    display: flex;
    gap: 1rem;
    margin-bottom: 2rem;
    flex-wrap: wrap;
}

.hero-features {
    display: flex;
    gap: 2rem;
    color: var(--gray-600);
}

.hero-image img {
    width: 100%;
    border-radius: 20px;
    box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
}

/* Sections */
section {
    padding: 80px 0;
}

.section-header {
    text-align: center;
    margin-bottom: 4rem;
}

.section-header h2 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.section-header p {
    font-size: 1.25rem;
    color: var(--gray-600);
}

/* Services */
.services {
    background: white;
}

.services-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 2rem;
}

.service-card {
    background: linear-gradient(145deg, var(--gray-50), #e0f2fe);
    padding: 2rem;
    border-radius: 20px;
    border: 1px solid rgba(148, 163, 184, 0.1);
    transition: all 0.3s;
}

.service-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(59, 130, 246, 0.15);
}

.service-icon {
    font-size: 3rem;
    margin-bottom: 1.5rem;
}

.service-card h3 {
    font-size: 1.25rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.service-card p {
    color: var(--gray-600);
    margin-bottom: 1.5rem;
}

.service-card ul {
    list-style: none;
}

.service-card li {
    padding: 0.25rem 0;
    color: var(--gray-600);
    font-size: 0.875rem;
}

/* Projects */
.realisations {
    background: var(--gray-50);
}

.projects-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 3rem;
}

.project-card {
    background: white;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    transition: all 0.3s;
}

.project-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
}

.project-image {
    height: 250px;
    overflow: hidden;
}

.project-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.3s;
}

.project-card:hover .project-image img {
    transform: scale(1.05);
}

.project-content {
    padding: 2rem;
}

.project-content h3 {
    font-size: 1.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
}

.project-content p {
    color: var(--gray-600);
    margin-bottom: 1.5rem;
}

.project-tags {
    display: flex;
    gap: 0.5rem;
    flex-wrap: wrap;
    margin-bottom: 1.5rem;
}

.project-tags span {
    background: #dbeafe;
    color: var(--primary);
    padding: 0.25rem 0.75rem;
    border-radius: 15px;
    font-size: 0.875rem;
    font-weight: 500;
}

.project-content a {
    color: var(--primary);
    text-decoration: none;
    font-weight: 600;
}

.project-content a:hover {
    text-decoration: underline;
}

/* Reviews */
.reviews {
    background: white;
}

.reviews-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 2rem;
}

.review-card {
    background: linear-gradient(145deg, var(--gray-50), #e0f2fe);
    padding: 2rem;
    border-radius: 20px;
    border: 1px solid rgba(255, 255, 255, 0.3);
    transition: all 0.3s;
}

.review-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(59, 130, 246, 0.15);
}

.review-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.reviewer-avatar {
    width: 48px;
    height: 48px;
    background: linear-gradient(135deg, var(--primary), var(--secondary));
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 700;
    font-size: 1.25rem;
}

.reviewer-info h4 {
    font-weight: 700;
    margin-bottom: 0.25rem;
}

.reviewer-info p {
    font-size: 0.875rem;
    color: var(--gray-600);
    margin: 0;
}

.stars {
    color: #fbbf24;
    font-size: 1rem;
}

.review-card > p {
    margin-bottom: 1rem;
    line-height: 1.6;
}

.review-card small {
    color: var(--gray-600);
}

/* Contact */
.contact {
    background: linear-gradient(135deg, var(--gray-800), var(--primary));
    color: white;
    position: relative;
    overflow: hidden;
}

.contact::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-image: radial-gradient(circle, rgba(255,255,255,0.1) 1px, transparent 1px);
    background-size: 50px 50px;
    opacity: 0.3;
}

.contact-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 4rem;
    margin-bottom: 3rem;
    position: relative;
    z-index: 1;
}

.contact-info h2 {
    font-size: 2.5rem;
    margin-bottom: 1rem;
}

.contact-info p {
    font-size: 1.25rem;
    color: rgba(255, 255, 255, 0.8);
    margin-bottom: 2rem;
}

.contact-details {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.contact-item {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.contact-icon {
    width: 48px;
    height: 48px;
    background: var(--primary);
    border-radius: 12px;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
}

.contact-item strong {
    display: block;
    margin-bottom: 0.25rem;
}

.contact-item a {
    color: #93c5fd;
    text-decoration: none;
}

.contact-item a:hover {
    text-decoration: underline;
}

.contact-hours {
    background: rgba(255, 255, 255, 0.1);
    padding: 2rem;
    border-radius: 20px;
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
}

.contact-hours h3 {
    font-size: 1.5rem;
    margin-bottom: 2rem;
}

.hours-table {
    margin-bottom: 1.5rem;
}

.hour-row {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem 0;
    border-bottom: 1px solid rgba(255, 255, 255, 0.2);
}

.hour-row:last-child {
    border-bottom: none;
}

.hour-row span:last-child {
    color: #93c5fd;
}

.guarantee {
    background: rgba(34, 197, 94, 0.2);
    padding: 1rem;
    border-radius: 10px;
    color: #86efac;
    font-weight: 600;
}

/* Contact Form */
.contact-form-section {
    position: relative;
    z-index: 1;
}

.contact-form-section h3 {
    font-size: 1.5rem;
    margin-bottom: 2rem;
    text-align: center;
}

.contact-form {
    max-width: 600px;
    margin: 0 auto;
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1rem;
}

.contact-form input,
.contact-form select,
.contact-form textarea {
    padding: 12px 16px;
    border: 1px solid rgba(255, 255, 255, 0.3);
    border-radius: 10px;
    background: rgba(255, 255, 255, 0.1);
    color: white;
    backdrop-filter: blur(10px);
}

.contact-form input::placeholder,
.contact-form textarea::placeholder {
    color: rgba(255, 255, 255, 0.7);
}

.contact-form option {
    background: var(--gray-800);
    color: white;
}

/* Footer */
.footer {
    background: var(--gray-900);
    color: white;
    padding: 3rem 0;
}

.footer-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.footer-brand {
    display: flex;
    align-items: center;
    gap: 12px;
}

.footer-brand h3 {
    margin-bottom: 0.25rem;
}

.footer-brand p {
    color: #94a3b8;
    font-size: 0.875rem;
    margin: 0;
}

.footer-info {
    text-align: right;
    color: #94a3b8;
}

.footer-info p {
    margin: 0;
}

.footer-legal {
    border-top: 1px solid #374151;
    padding-top: 2rem;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.legal-links {
    display: flex;
    gap: 2rem;
    align-items: center;
}

.legal-links a {
    color: #94a3b8;
    text-decoration: none;
    transition: color 0.3s;
}

.legal-links a:hover {
    color: white;
}

.legal-links span {
    color: #94a3b8;
    font-size: 0.875rem;
}

/* Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.service-card,
.project-card,
.review-card {
    animation: fadeInUp 0.6s ease-out;
}

/* Responsive */
@media (max-width: 768px) {
    .mobile-menu-btn {
        display: block;
    }
    
    .nav-menu {
        display: none;
    }
    
    .hero-content {
        grid-template-columns: 1fr;
        text-align: center;
    }
    
    .hero-text h1 {
        font-size: 2.5rem;
    }
    
    .contact-content {
        grid-template-columns: 1fr;
    }
    
    .form-row {
        grid-template-columns: 1fr;
    }
    
    .projects-grid {
        grid-template-columns: 1fr;
    }
    
    .footer-content {
        flex-direction: column;
        text-align: center;
        gap: 1rem;
    }
    
    .footer-legal {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
    
    .legal-links {
        flex-wrap: wrap;
        justify-content: center;
    }
}

@media (max-width: 480px) {
    .container {
        padding: 0 15px;
    }
    
    .hero {
        padding: 100px 0 60px;
    }
    
    section {
        padding: 60px 0;
    }
    
    .services-grid,
    .reviews-grid {
        grid-template-columns: 1fr;
    }
}';
    }
    
    private function generateOptimizedJS() {
        return '// WEBMATIC - JavaScript Optimisé
document.addEventListener("DOMContentLoaded", function() {
    console.log("Webmatic site loaded successfully!");
    
    // Smooth scrolling pour les liens de navigation
    document.querySelectorAll(\'a[href^="#"]\').forEach(anchor => {
        anchor.addEventListener("click", function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute("href"));
            if (target) {
                target.scrollIntoView({
                    behavior: "smooth",
                    block: "start"
                });
            }
        });
    });
    
    // Animation au scroll
    const observerOptions = {
        threshold: 0.1,
        rootMargin: "0px 0px -50px 0px"
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.opacity = "1";
                entry.target.style.transform = "translateY(0)";
            }
        });
    }, observerOptions);
    
    // Observer les éléments à animer
    document.querySelectorAll(".service-card, .project-card, .review-card").forEach(el => {
        el.style.opacity = "0";
        el.style.transform = "translateY(30px)";
        el.style.transition = "all 0.6s ease-out";
        observer.observe(el);
    });
    
    // Menu mobile (si nécessaire)
    const mobileMenuBtn = document.querySelector(".mobile-menu-btn");
    const navMenu = document.querySelector(".nav-menu");
    
    if (mobileMenuBtn && navMenu) {
        mobileMenuBtn.addEventListener("click", function() {
            navMenu.classList.toggle("show");
        });
    }
    
    // Validation du formulaire de contact
    const contactForm = document.getElementById("contactForm");
    if (contactForm) {
        contactForm.addEventListener("submit", function(e) {
            e.preventDefault();
            
            // Validation basique
            const name = this.querySelector(\'input[name="name"]\').value.trim();
            const email = this.querySelector(\'input[name="email"]\').value.trim();
            const message = this.querySelector(\'textarea[name="message"]\').value.trim();
            
            if (!name || !email || !message) {
                alert("Veuillez remplir tous les champs obligatoires.");
                return;
            }
            
            if (!isValidEmail(email)) {
                alert("Veuillez entrer une adresse email valide.");
                return;
            }
            
            // Envoi du formulaire
            submitContactForm(this);
        });
    }
    
    // Fonction de validation email
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    // Fonction d\'envoi du formulaire
    async function submitContactForm(form) {
        const formData = new FormData(form);
        const data = Object.fromEntries(formData);
        
        // Afficher un loader
        const submitBtn = form.querySelector(\'button[type="submit"]\');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = "Envoi en cours...";
        submitBtn.disabled = true;
        
        try {
            const response = await fetch("api/contact.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(data)
            });
            
            const result = await response.json();
            
            if (result.success) {
                alert("✅ Message envoyé avec succès ! Nous vous recontacterons rapidement.");
                form.reset();
                
                // Tracking Plausible (si disponible)
                if (window.plausible) {
                    plausible("Contact Form Submit");
                }
            } else {
                alert("❌ Erreur lors de l\'envoi : " + (result.message || "Erreur inconnue"));
            }
        } catch (error) {
            console.error("Contact form error:", error);
            alert("❌ Erreur lors de l\'envoi du message. Veuillez réessayer ou nous appeler directement au 07 56 91 30 61.");
        } finally {
            // Restaurer le bouton
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        }
    }
    
    // Parallax effect léger (optionnel)
    let ticking = false;
    
    function updateParallax() {
        const scrolled = window.pageYOffset;
        const parallax = document.querySelector(".hero-image");
        
        if (parallax) {
            const speed = scrolled * -0.5;
            parallax.style.transform = `translate3d(0, ${speed}px, 0)`;
        }
        
        ticking = false;
    }
    
    function requestParallaxTick() {
        if (!ticking) {
            requestAnimationFrame(updateParallax);
            ticking = true;
        }
    }
    
    window.addEventListener("scroll", requestParallaxTick);
    
    // Performance: Lazy loading pour les images (si pas déjà supporté)
    if ("IntersectionObserver" in window) {
        const imageObserver = new IntersectionObserver(function(entries, observer) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const img = entry.target;
                    if (img.dataset.src) {
                        img.src = img.dataset.src;
                        img.removeAttribute("data-src");
                        imageObserver.unobserve(img);
                    }
                }
            });
        });
        
        document.querySelectorAll("img[data-src]").forEach(img => {
            imageObserver.observe(img);
        });
    }
    
    // Tracking des clics sur les liens externes
    document.querySelectorAll(\'a[target="_blank"]\').forEach(link => {
        link.addEventListener("click", function() {
            if (window.plausible) {
                plausible("External Link Click", {
                    props: { url: this.href }
                });
            }
        });
    });
    
    // Tracking des clics téléphone
    document.querySelectorAll(\'a[href^="tel:"]\').forEach(link => {
        link.addEventListener("click", function() {
            if (window.plausible) {
                plausible("Phone Click");
            }
        });
    });
});

// Fonction utilitaire pour debounce
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Gestion de la performance
window.addEventListener("load", function() {
    // Preload des images critiques
    const criticalImages = [
        "https://images.unsplash.com/photo-1508361727343-ca787442dcd7",
        "https://images.unsplash.com/photo-1743865319071-929ac8a27bcd",
        "https://images.unsplash.com/photo-1612999105465-d970b00015a8"
    ];
    
    criticalImages.forEach(src => {
        const img = new Image();
        img.src = src;
    });
    
    console.log("Webmatic: Critical resources preloaded");
});';
    }
    
    private function createLegalPages() {
        // Page CGV
        $cgvHTML = $this->generateLegalPageHTML(
            "Conditions Générales de Vente",
            $this->getCGVContent()
        );
        file_put_contents(ROOT_PATH . '/cgv.html', $cgvHTML);
        
        // Page Mentions légales
        $mentionsHTML = $this->generateLegalPageHTML(
            "Mentions Légales",
            $this->getMentionsLegalesContent()
        );
        file_put_contents(ROOT_PATH . '/mentions-legales.html', $mentionsHTML);
    }
    
    private function generateLegalPageHTML($title, $content) {
        return '<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>' . $title . ' - Webmatic</title>
    <meta name="description" content="' . $title . ' de Webmatic - Services informatiques et développement web.">
    <meta name="robots" content="noindex">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="nav-brand">
                <div class="logo">
                    <svg width="40" height="40" viewBox="0 0 40 40" fill="none">
                        <rect width="40" height="40" rx="12" fill="url(#gradient)"/>
                        <path d="M12 28V12h4v6h8v-6h4v16h-4v-6h-8v6h-4z" fill="white"/>
                        <defs>
                            <linearGradient id="gradient" x1="0" y1="0" x2="1" y2="1">
                                <stop offset="0%" stop-color="#3B82F6"/>
                                <stop offset="100%" stop-color="#6366F1"/>
                            </linearGradient>
                        </defs>
                    </svg>
                </div>
                <div class="brand-text">
                    <h1>Webmatic</h1>
                    <p>L\'informatique côté pratique</p>
                </div>
            </div>
            <div class="nav-menu">
                <a href="index.html">Accueil</a>
            </div>
        </div>
    </nav>
    
    <main style="padding-top: 120px; min-height: 100vh; background: var(--gray-50);">
        <div class="container">
            <div style="max-width: 800px; margin: 0 auto; background: white; padding: 3rem; border-radius: 20px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
                <h1 style="font-size: 2.5rem; margin-bottom: 2rem; color: var(--gray-800);">' . $title . '</h1>
                ' . $content . '
                <div style="margin-top: 3rem; padding-top: 2rem; border-top: 1px solid #e5e7eb;">
                    <a href="index.html" class="btn btn-primary">← Retour à l\'accueil</a>
                </div>
            </div>
        </div>
    </main>
    
    <script src="assets/js/script.js"></script>
</body>
</html>';
    }
    
    private function getCGVContent() {
        return '<div style="line-height: 1.7; color: var(--gray-700);">
            <h2 style="color: var(--gray-800); margin: 2rem 0 1rem;">1. Objet</h2>
            <p>Les présentes conditions générales de vente régissent les relations entre Webmatic, auto-entrepreneur spécialisé dans les services informatiques et le développement web, et ses clients.</p>
            
            <h2 style="color: var(--gray-800); margin: 2rem 0 1rem;">2. Services proposés</h2>
            <ul style="margin-left: 2rem;">
                <li>Création et développement de sites web</li>
                <li>Maintenance informatique sur site</li>
                <li>Réparation de consoles de jeux et optimisation gaming</li>
                <li>Services mobiles : réparation smartphones et tablettes</li>
                <li>Formation et support technique</li>
            </ul>
            
            <h2 style="color: var(--gray-800); margin: 2rem 0 1rem;">3. Tarifs et modalités de paiement</h2>
            <ul style="margin-left: 2rem;">
                <li>Tous les devis sont gratuits et sans engagement</li>
                <li>Les prix sont indiqués TTC</li>
                <li>Paiement à la prestation ou selon échéancier convenu</li>
                <li>Moyens de paiement acceptés : espèces, chèque, virement</li>
            </ul>
            
            <h2 style="color: var(--gray-800); margin: 2rem 0 1rem;">4. Garanties</h2>
            <ul style="margin-left: 2rem;">
                <li>Garantie de 3 mois sur les réparations matérielles</li>
                <li>Garantie de 6 mois sur les développements web</li>
                <li>Support technique gratuit pendant 1 mois après livraison</li>
            </ul>
            
            <h2 style="color: var(--gray-800); margin: 2rem 0 1rem;">5. Droit de rétractation</h2>
            <p>Conformément à la législation en vigueur, le client dispose d\'un délai de 14 jours pour exercer son droit de rétractation, sauf pour les prestations de services entièrement exécutées.</p>
        </div>';
    }
    
    private function getMentionsLegalesContent() {
        return '<div style="line-height: 1.7; color: var(--gray-700);">
            <h2 style="color: var(--gray-800); margin: 2rem 0 1rem;">Éditeur du site</h2>
            <ul style="margin-left: 2rem; list-style: none;">
                <li><strong>Nom :</strong> Webmatic - Audric</li>
                <li><strong>Statut :</strong> Auto-entrepreneur</li>
                <li><strong>Adresse :</strong> Pommier, 69380, France</li>
                <li><strong>Téléphone :</strong> 07 56 91 30 61</li>
            </ul>
            
            <h2 style="color: var(--gray-800); margin: 2rem 0 1rem;">Hébergement</h2>
            <p>Ce site est hébergé par votre hébergeur web.</p>
            
            <h2 style="color: var(--gray-800); margin: 2rem 0 1rem;">Données personnelles</h2>
            <p>Conformément au RGPD et à la loi Informatique et Libertés :</p>
            <ul style="margin-left: 2rem;">
                <li>Les données collectées via le formulaire de contact sont utilisées uniquement pour répondre à vos demandes</li>
                <li>Aucune donnée n\'est transmise à des tiers</li>
                <li>Vous disposez d\'un droit d\'accès, de rectification et de suppression de vos données</li>
                <li>Analytics : Nous utilisons Plausible Analytics (respect de la vie privée, pas de cookies)</li>
            </ul>
            
            <h2 style="color: var(--gray-800); margin: 2rem 0 1rem;">Cookies</h2>
            <p>Ce site utilise uniquement des cookies techniques nécessaires au fonctionnement. Aucun cookie publicitaire ou de tracking n\'est utilisé.</p>
        </div>';
    }
    
    private function createPHPAPI() {
        // API de contact
        $apiDir = ROOT_PATH . '/api';
        if (!is_dir($apiDir)) {
            mkdir($apiDir, 0755, true);
        }
        
        $contactAPI = '<?php
header("Content-Type: application/json");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");

// Configuration
$MAX_REQUESTS_PER_HOUR = 10;
$SPAM_WORDS = ["casino", "lottery", "bitcoin", "crypto", "winner"];

// Fonction de limitation de taux
function checkRateLimit($ip) {
    global $MAX_REQUESTS_PER_HOUR;
    
    $file = sys_get_temp_dir() . "/webmatic_rate_" . md5($ip);
    $now = time();
    $requests = [];
    
    if (file_exists($file)) {
        $data = file_get_contents($file);
        $requests = $data ? json_decode($data, true) : [];
    }
    
    // Nettoyer les anciennes requêtes
    $requests = array_filter($requests, function($time) use ($now) {
        return ($now - $time) < 3600; // 1 heure
    });
    
    if (count($requests) >= $MAX_REQUESTS_PER_HOUR) {
        return false;
    }
    
    $requests[] = $now;
    file_put_contents($file, json_encode($requests));
    return true;
}

// Fonction de détection de spam
function detectSpam($text) {
    global $SPAM_WORDS;
    $text = strtolower($text);
    
    foreach ($SPAM_WORDS as $word) {
        if (strpos($text, $word) !== false) {
            return true;
        }
    }
    
    return false;
}

// Fonction de validation
function validateInput($data) {
    $errors = [];
    
    if (empty($data["name"]) || strlen($data["name"]) < 2) {
        $errors[] = "Le nom est requis (minimum 2 caractères)";
    }
    
    if (empty($data["email"]) || !filter_var($data["email"], FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Email valide requis";
    }
    
    if (!empty($data["phone"]) && !preg_match("/^(?:\+33|0)[1-9](?:[0-9]{8})$/", $data["phone"])) {
        $errors[] = "Numéro de téléphone invalide";
    }
    
    if (empty($data["service"])) {
        $errors[] = "Service requis";
    }
    
    if (empty($data["message"]) || strlen($data["message"]) < 10) {
        $errors[] = "Message requis (minimum 10 caractères)";
    }
    
    if (detectSpam($data["message"])) {
        $errors[] = "Message détecté comme spam";
    }
    
    return $errors;
}

// Traitement de la requête
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Méthode non autorisée"]);
    exit;
}

$ip = $_SERVER["REMOTE_ADDR"] ?? "unknown";

// Vérification du rate limiting
if (!checkRateLimit($ip)) {
    http_response_code(429);
    echo json_encode([
        "success" => false, 
        "message" => "Trop de requêtes. Veuillez patienter avant de renvoyer un message."
    ]);
    exit;
}

// Récupération des données
$input = file_get_contents("php://input");
$data = json_decode($input, true);

if (!$data) {
    http_response_code(400);
    echo json_encode(["success" => false, "message" => "Données invalides"]);
    exit;
}

// Validation
$errors = validateInput($data);
if (!empty($errors)) {
    http_response_code(422);
    echo json_encode(["success" => false, "message" => implode(", ", $errors)]);
    exit;
}

// Nettoyage des données
$cleanData = [
    "name" => htmlspecialchars(trim($data["name"]), ENT_QUOTES, "UTF-8"),
    "email" => strtolower(trim($data["email"])),
    "phone" => $data["phone"] ?? "",
    "service" => htmlspecialchars($data["service"], ENT_QUOTES, "UTF-8"),
    "message" => htmlspecialchars(trim($data["message"]), ENT_QUOTES, "UTF-8"),
    "ip" => $ip,
    "timestamp" => date("Y-m-d H:i:s"),
    "user_agent" => $_SERVER["HTTP_USER_AGENT"] ?? ""
];

// Sauvegarde (adaptez selon votre base de données)
$logFile = __DIR__ . "/../contacts.json";
$contacts = [];

if (file_exists($logFile)) {
    $existingData = file_get_contents($logFile);
    $contacts = $existingData ? json_decode($existingData, true) : [];
}

$cleanData["id"] = uniqid();
$contacts[] = $cleanData;

if (file_put_contents($logFile, json_encode($contacts, JSON_PRETTY_PRINT))) {
    // Optionnel : Envoi d\'email (configurez selon votre serveur)
    /*
    $to = "contact@web-matic.fr";
    $subject = "Nouveau message de contact - Webmatic";
    $message = "Nouveau message de : " . $cleanData["name"] . "\nEmail: " . $cleanData["email"] . "\nMessage: " . $cleanData["message"];
    $headers = "From: no-reply@web-matic.fr\r\nReply-To: " . $cleanData["email"];
    mail($to, $subject, $message, $headers);
    */
    
    echo json_encode([
        "success" => true,
        "message" => "Message envoyé avec succès ! Nous vous recontacterons rapidement.",
        "reference" => substr($cleanData["id"], 0, 8)
    ]);
} else {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Erreur lors de la sauvegarde. Veuillez réessayer."
    ]);
}
?>';
        
        file_put_contents($apiDir . '/contact.php', $contactAPI);
    }
    
    public function createSitemap() {
        $this->step("Création du sitemap XML");
        
        $sitemap = '<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>https://' . DOMAIN . '/</loc>
        <lastmod>' . date('c') . '</lastmod>
        <changefreq>monthly</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>https://' . DOMAIN . '/cgv.html</loc>
        <lastmod>' . date('c') . '</lastmod>
        <changefreq>yearly</changefreq>
        <priority>0.3</priority>
    </url>
    <url>
        <loc>https://' . DOMAIN . '/mentions-legales.html</loc>
        <lastmod>' . date('c') . '</lastmod>
        <changefreq>yearly</changefreq>
        <priority>0.3</priority>
    </url>
</urlset>';
        
        file_put_contents(ROOT_PATH . '/sitemap.xml', $sitemap);
        $this->success("Sitemap XML créé");
    }
    
    public function createRobotsTxt() {
        $this->step("Création du fichier robots.txt");
        
        $robots = 'User-agent: *
Allow: /
Disallow: /api/
Disallow: /deploy.php

Sitemap: https://' . DOMAIN . '/sitemap.xml';
        
        file_put_contents(ROOT_PATH . '/robots.txt', $robots);
        $this->success("Robots.txt créé");
    }
    
    public function createHtaccess() {
        $this->step("Création du fichier .htaccess");
        
        $htaccess = '# WEBMATIC - Configuration Apache
RewriteEngine On

# Redirection HTTPS (optionnel)
# RewriteCond %{HTTPS} off
# RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# Compression GZIP
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/plain
    AddOutputFilterByType DEFLATE text/html
    AddOutputFilterByType DEFLATE text/xml
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/xml
    AddOutputFilterByType DEFLATE application/xhtml+xml
    AddOutputFilterByType DEFLATE application/rss+xml
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE application/x-javascript
</IfModule>

# Cache des fichiers statiques
<IfModule mod_expires.c>
    ExpiresActive On
    ExpiresByType image/jpg "access plus 1 month"
    ExpiresByType image/jpeg "access plus 1 month"
    ExpiresByType image/gif "access plus 1 month"
    ExpiresByType image/png "access plus 1 month"
    ExpiresByType text/css "access plus 1 month"
    ExpiresByType application/pdf "access plus 1 month"
    ExpiresByType application/javascript "access plus 1 month"
    ExpiresByType application/x-javascript "access plus 1 month"
    ExpiresByType image/x-icon "access plus 1 year"
</IfModule>

# Headers de sécurité
<IfModule mod_headers.c>
    Header always set X-Content-Type-Options "nosniff"
    Header always set X-Frame-Options "SAMEORIGIN"
    Header always set X-XSS-Protection "1; mode=block"
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
    Header always set Permissions-Policy "geolocation=(), microphone=(), camera=()"
</IfModule>

# Protection des fichiers sensibles
<Files "deploy.php">
    Order Allow,Deny
    Deny from all
</Files>

<Files "contacts.json">
    Order Allow,Deny
    Deny from all
</Files>

# Pages d\'erreur personnalisées (optionnel)
# ErrorDocument 404 /404.html
# ErrorDocument 500 /500.html';
        
        file_put_contents(ROOT_PATH . '/.htaccess', $htaccess);
        $this->success(".htaccess créé avec optimisations");
    }
    
    public function finalizeDeployment() {
        $this->step("Finalisation du déploiement");
        
        // Permissions
        chmod(ROOT_PATH . '/api', 0755);
        chmod(ROOT_PATH . '/api/contact.php', 0644);
        
        // Test des fichiers créés
        $requiredFiles = [
            'index.html',
            'assets/css/style.css',
            'assets/js/script.js',
            'cgv.html',
            'mentions-legales.html',
            'api/contact.php',
            'sitemap.xml',
            'robots.txt',
            '.htaccess'
        ];
        
        $missing = [];
        foreach ($requiredFiles as $file) {
            if (!file_exists(ROOT_PATH . '/' . $file)) {
                $missing[] = $file;
            }
        }
        
        if (empty($missing)) {
            $this->success("Tous les fichiers ont été créés avec succès");
        } else {
            $this->error("Fichiers manquants : " . implode(', ', $missing));
        }
        
        $this->success("Site déployé avec succès !");
        $this->log("🌐 Votre site est maintenant accessible à l'adresse : https://" . DOMAIN);
        $this->log("📞 N'oubliez pas de tester le formulaire de contact");
        $this->log("🗑️ IMPORTANT: Supprimez le fichier deploy.php après le déploiement pour des raisons de sécurité");
    }
    
    public function deploy() {
        try {
            if (!$this->checkRequirements()) {
                return false;
            }
            
            $this->createDirectories();
            $this->deployStaticVersion();
            $this->createSitemap();
            $this->createRobotsTxt();
            $this->createHtaccess();
            $this->finalizeDeployment();
            
            return true;
            
        } catch (Exception $e) {
            $this->error("Erreur durante le déploiement : " . $e->getMessage());
            return false;
        }
    }
    
    public function getReport() {
        return [
            'steps' => $this->steps,
            'errors' => $this->errors,
            'logs' => $this->logs,
            'success' => empty($this->errors)
        ];
    }
}

// Interface de déploiement
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Déploiement Webmatic</title>
    <style>
        body { 
            font-family: -apple-system, BlinkMacSystemFont, sans-serif; 
            margin: 0; 
            padding: 20px; 
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            min-height: 100vh;
        }
        .container { 
            max-width: 800px; 
            margin: 0 auto; 
            background: rgba(255,255,255,0.95); 
            color: #333;
            border-radius: 20px; 
            padding: 30px; 
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        h1 { 
            color: #3b82f6; 
            text-align: center; 
            margin-bottom: 30px;
            font-size: 2.5rem;
        }
        .deploy-form { 
            text-align: center; 
            margin-bottom: 30px; 
        }
        input[type="password"] {
            padding: 12px 20px;
            border: 2px solid #e5e7eb;
            border-radius: 10px;
            font-size: 16px;
            margin: 0 10px;
            width: 200px;
        }
        button { 
            background: linear-gradient(135deg, #3b82f6, #6366f1);
            color: white; 
            border: none; 
            padding: 12px 30px; 
            border-radius: 10px; 
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        button:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4);
        }
        .log-container { 
            background: #1e293b; 
            color: #f1f5f9;
            padding: 20px; 
            border-radius: 10px; 
            font-family: Monaco, Consolas, monospace; 
            font-size: 14px;
            max-height: 500px; 
            overflow-y: auto;
            margin-top: 20px;
        }
        .log-entry { 
            margin: 5px 0; 
            padding: 2px 0;
        }
        .success { 
            background: #10b981; 
            color: white; 
            padding: 15px; 
            border-radius: 10px; 
            margin: 20px 0; 
            font-weight: 600;
            text-align: center;
        }
        .error { 
            background: #ef4444; 
            color: white; 
            padding: 15px; 
            border-radius: 10px; 
            margin: 20px 0; 
            font-weight: 600;
        }
        .warning {
            background: #f59e0b;
            color: white;
            padding: 15px;
            border-radius: 10px;
            margin: 20px 0;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🚀 Déploiement Webmatic</h1>
        
        <?php if (!isset($_POST['deploy'])): ?>
            <div class="deploy-form">
                <h2>Prêt à déployer votre site professionnel ?</h2>
                <p>Ce script va créer une version optimisée de votre site Webmatic avec :</p>
                <ul style="text-align: left; max-width: 500px; margin: 20px auto;">
                    <li>✅ Site statique ultra-rapide</li>
                    <li>✅ SEO optimisé avec structured data</li>
                    <li>✅ Analytics Plausible intégré</li>
                    <li>✅ Formulaire de contact PHP sécurisé</li>
                    <li>✅ Pages légales complètes (CGV, Mentions)</li>
                    <li>✅ Sécurité renforcée (.htaccess)</li>
                    <li>✅ Sitemap XML automatique</li>
                </ul>
                
                <form method="post" style="margin-top: 30px;">
                    <input type="password" name="password" placeholder="Mot de passe de déploiement" required>
                    <button type="submit" name="deploy" value="1">Démarrer le déploiement</button>
                </form>
                
                <div class="warning">
                    <strong>⚠️ IMPORTANT :</strong><br>
                    • Sauvegardez vos fichiers existants avant de procéder<br>
                    • Supprimez ce fichier deploy.php après le déploiement<br>
                    • Testez le formulaire de contact après déploiement
                </div>
            </div>
        <?php else: ?>
            <?php
            // Vérification du mot de passe
            if ($_POST['password'] !== DEPLOY_PASSWORD) {
                echo '<div class="error">❌ Mot de passe incorrect !</div>';
                echo '<a href="?" style="color: #3b82f6;">← Retour</a>';
                exit;
            }
            
            echo '<div class="log-container">';
            flush();
            
            $deployer = new WebmaticDeployer();
            $success = $deployer->deploy();
            $report = $deployer->getReport();
            
            echo '</div>';
            
            if ($success) {
                echo '<div class="success">
                    🎉 Déploiement réussi !<br>
                    Votre site Webmatic est maintenant en ligne.<br><br>
                    <strong>Prochaines étapes :</strong><br>
                    1. Testez votre site : <a href="index.html" target="_blank" style="color: white; text-decoration: underline;">Voir le site</a><br>
                    2. Testez le formulaire de contact<br>
                    3. Supprimez le fichier deploy.php<br>
                    4. Configurez votre domaine sur Plausible Analytics
                </div>';
            } else {
                echo '<div class="error">
                    ❌ Le déploiement a échoué.<br>
                    Vérifiez les logs ci-dessus et corrigez les erreurs.
                </div>';
            }
            ?>
            
            <div style="text-align: center; margin-top: 30px;">
                <a href="?" style="color: #3b82f6; text-decoration: none;">← Nouveau déploiement</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>