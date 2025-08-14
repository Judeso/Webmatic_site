import React, { useState, useEffect } from 'react';
import { BrowserRouter, Routes, Route, Link } from 'react-router-dom';
import './App.css';
import { Star, Phone, Mail, MapPin, Clock, Monitor, Wrench, Gamepad2, Smartphone, ExternalLink, Menu, X, CheckCircle, Users, Award, Zap, Shield, FileText, Eye } from 'lucide-react';
import { Helmet } from 'react-helmet';

const BACKEND_URL = process.env.REACT_APP_BACKEND_URL;

// Hook pour les animations au scroll
const useScrollAnimation = () => {
  const [visibleElements, setVisibleElements] = useState(new Set());

  useEffect(() => {
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          if (entry.isIntersecting) {
            setVisibleElements(prev => new Set(prev.add(entry.target.dataset.animate)));
          }
        });
      },
      { threshold: 0.1, rootMargin: '50px' }
    );

    document.querySelectorAll('[data-animate]').forEach(el => observer.observe(el));
    return () => observer.disconnect();
  }, []);

  return visibleElements;
};

// Page CGV
const CGV = () => (
  <div className="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 pt-20">
    <Helmet>
      <title>Conditions Générales de Vente - Webmatic</title>
      <meta name="description" content="Conditions générales de vente de Webmatic - Services informatiques et développement web." />
      <meta name="robots" content="noindex" />
    </Helmet>
    <div className="container mx-auto px-6 py-12">
      <div className="max-w-4xl mx-auto bg-white rounded-2xl shadow-xl p-8">
        <h1 className="text-4xl font-bold text-slate-800 mb-8">Conditions Générales de Vente</h1>
        
        <div className="space-y-8 text-slate-700">
          <section>
            <h2 className="text-2xl font-bold text-slate-800 mb-4">1. Objet</h2>
            <p>Les présentes conditions générales de vente régissent les relations entre Webmatic, auto-entrepreneur spécialisé dans les services informatiques et le développement web, et ses clients.</p>
          </section>

          <section>
            <h2 className="text-2xl font-bold text-slate-800 mb-4">2. Services proposés</h2>
            <ul className="list-disc pl-6 space-y-2">
              <li>Création et développement de sites web</li>
              <li>Maintenance informatique sur site</li>
              <li>Réparation de consoles de jeux et optimisation gaming</li>
              <li>Services mobiles : réparation smartphones et tablettes</li>
              <li>Formation et support technique</li>
            </ul>
          </section>

          <section>
            <h2 className="text-2xl font-bold text-slate-800 mb-4">3. Tarifs et modalités de paiement</h2>
            <ul className="list-disc pl-6 space-y-2">
              <li>Tous les devis sont gratuits et sans engagement</li>
              <li>Les prix sont indiqués TTC</li>
              <li>Paiement à la prestation ou selon échéancier convenu</li>
              <li>Moyens de paiement acceptés : espèces, chèque, virement</li>
            </ul>
          </section>

          <section>
            <h2 className="text-2xl font-bold text-slate-800 mb-4">4. Exécution des prestations</h2>
            <p>Les interventions sont réalisées dans les meilleurs délais, selon la disponibilité et la complexité de la demande. Un devis détaillé sera fourni avant toute intervention.</p>
          </section>

          <section>
            <h2 className="text-2xl font-bold text-slate-800 mb-4">5. Garanties</h2>
            <ul className="list-disc pl-6 space-y-2">
              <li>Garantie de 3 mois sur les réparations matérielles</li>
              <li>Garantie de 6 mois sur les développements web</li>
              <li>Support technique gratuit pendant 1 mois après livraison</li>
            </ul>
          </section>

          <section>
            <h2 className="text-2xl font-bold text-slate-800 mb-4">6. Responsabilité</h2>
            <p>Webmatic s'engage à apporter tout son savoir-faire dans l'exécution des prestations. La responsabilité est limitée au montant de la prestation réalisée.</p>
          </section>

          <section>
            <h2 className="text-2xl font-bold text-slate-800 mb-4">7. Droit de rétractation</h2>
            <p>Conformément à la législation en vigueur, le client dispose d'un délai de 14 jours pour exercer son droit de rétractation, sauf pour les prestations de services entièrement exécutées.</p>
          </section>
        </div>
        
        <div className="mt-12 pt-8 border-t border-slate-200">
          <Link to="/" className="inline-flex items-center gap-2 bg-blue-600 text-white px-6 py-3 rounded-full hover:bg-blue-700 transition-colors">
            ← Retour à l'accueil
          </Link>
        </div>
      </div>
    </div>
  </div>
);

// Page Mentions légales
const MentionsLegales = () => (
  <div className="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 pt-20">
    <Helmet>
      <title>Mentions Légales - Webmatic</title>
      <meta name="description" content="Mentions légales de Webmatic - Informations légales et données personnelles." />
      <meta name="robots" content="noindex" />
    </Helmet>
    <div className="container mx-auto px-6 py-12">
      <div className="max-w-4xl mx-auto bg-white rounded-2xl shadow-xl p-8">
        <h1 className="text-4xl font-bold text-slate-800 mb-8">Mentions Légales</h1>
        
        <div className="space-y-8 text-slate-700">
          <section>
            <h2 className="text-2xl font-bold text-slate-800 mb-4">Éditeur du site</h2>
            <ul className="space-y-2">
              <li><strong>Nom :</strong> Webmatic - Audric</li>
              <li><strong>Statut :</strong> Auto-entrepreneur</li>
              <li><strong>Adresse :</strong> Pommier, 69380, France</li>
              <li><strong>Téléphone :</strong> 07 56 91 30 61</li>
              <li><strong>Email :</strong> contact@webmatic.fr</li>
            </ul>
          </section>

          <section>
            <h2 className="text-2xl font-bold text-slate-800 mb-4">Hébergement</h2>
            <p>Ce site est hébergé par Emergent Agent Platform, service cloud sécurisé.</p>
          </section>

          <section>
            <h2 className="text-2xl font-bold text-slate-800 mb-4">Propriété intellectuelle</h2>
            <p>L'ensemble du contenu de ce site (textes, images, vidéos) est protégé par le droit d'auteur. Toute reproduction sans autorisation est interdite.</p>
          </section>

          <section>
            <h2 className="text-2xl font-bold text-slate-800 mb-4">Données personnelles</h2>
            <div className="space-y-4">
              <p>Conformément au RGPD et à la loi Informatique et Libertés :</p>
              <ul className="list-disc pl-6 space-y-2">
                <li>Les données collectées via le formulaire de contact sont utilisées uniquement pour répondre à vos demandes</li>
                <li>Aucune donnée n'est transmise à des tiers</li>
                <li>Vous disposez d'un droit d'accès, de rectification et de suppression de vos données</li>
                <li>Analytics : Nous utilisons Plausible Analytics (respect de la vie privée, pas de cookies)</li>
              </ul>
            </div>
          </section>

          <section>
            <h2 className="text-2xl font-bold text-slate-800 mb-4">Cookies</h2>
            <p>Ce site utilise uniquement des cookies techniques nécessaires au fonctionnement. Aucun cookie publicitaire ou de tracking n'est utilisé.</p>
          </section>

          <section>
            <h2 className="text-2xl font-bold text-slate-800 mb-4">Droit applicable</h2>
            <p>Le présent site est soumis au droit français. En cas de litige, les tribunaux français seront compétents.</p>
          </section>
        </div>
        
        <div className="mt-12 pt-8 border-t border-slate-200">
          <Link to="/" className="inline-flex items-center gap-2 bg-blue-600 text-white px-6 py-3 rounded-full hover:bg-blue-700 transition-colors">
            ← Retour à l'accueil
          </Link>
        </div>
      </div>
    </div>
  </div>
);

// Composant principal avec animations
const HomePage = () => {
  const [isMenuOpen, setIsMenuOpen] = useState(false);
  const visibleElements = useScrollAnimation();

  const scrollToSection = (sectionId) => {
    const element = document.getElementById(sectionId);
    if (element) {
      element.scrollIntoView({ behavior: 'smooth' });
    }
    setIsMenuOpen(false);
  };

  return (
    <>
      <Helmet>
        <title>Webmatic - L'informatique côté pratique | Développement Web & Services IT</title>
        <meta name="description" content="Webmatic : développeur web expérimenté et technicien informatique à Pommier (69). Solutions créatives pour votre présence en ligne et maintenance informatique complète." />
        <meta name="keywords" content="développeur web, technicien informatique, Pommier 69, création site web, maintenance informatique, réparation console, smartphone" />
        <meta name="author" content="Webmatic - Audric" />
        <meta name="geo.region" content="FR-69" />
        <meta name="geo.placename" content="Pommier" />
        <meta property="og:title" content="Webmatic - Services informatiques et développement web" />
        <meta property="og:description" content="Expert en développement web et maintenance informatique à Pommier (69). Devis gratuit." />
        <meta property="og:type" content="website" />
        <meta property="og:locale" content="fr_FR" />
        <script type="application/ld+json">
          {JSON.stringify({
            "@context": "https://schema.org",
            "@type": "LocalBusiness",
            "name": "Webmatic",
            "description": "Services informatiques et développement web",
            "address": {
              "@type": "PostalAddress",
              "addressLocality": "Pommier",
              "postalCode": "69380",
              "addressCountry": "FR"
            },
            "telephone": "07 56 91 30 61",
            "openingHours": [
              "Mo-Fr 08:00-20:00",
              "Sa-Su 08:00-18:00"
            ],
            "serviceArea": "Rhône-Alpes",
            "services": ["Développement web", "Maintenance informatique", "Réparation consoles", "Services mobile"]
          })}
        </script>
        {/* Plausible Analytics */}
        <script defer data-domain="webmatic.fr" src="https://plausible.io/js/script.js"></script>
      </Helmet>

      <div className="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
        {/* Navigation avec animation */}
        <nav className="fixed top-0 w-full bg-white/95 backdrop-blur-sm shadow-sm z-50 border-b border-slate-200 nav-slide-down">
          <div className="container mx-auto px-6 py-4">
            <div className="flex justify-between items-center">
              <div className="flex items-center space-x-2 logo-bounce">
                <div className="w-10 h-10 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center icon-float">
                  <Monitor className="text-white w-6 h-6" />
                </div>
                <div>
                  <h1 className="text-2xl font-bold text-slate-800">Webmatic</h1>
                  <p className="text-sm text-slate-600">L'informatique côté pratique</p>
                </div>
              </div>
              
              <div className="hidden md:flex items-center space-x-8">
                <button onClick={() => scrollToSection('services')} className="nav-item text-slate-700 hover:text-blue-600 transition-colors">Services</button>
                <button onClick={() => scrollToSection('realisations')} className="nav-item text-slate-700 hover:text-blue-600 transition-colors">Réalisations</button>
                <button onClick={() => scrollToSection('avis')} className="nav-item text-slate-700 hover:text-blue-600 transition-colors">Avis</button>
                <button onClick={() => scrollToSection('contact')} className="btn-pulse bg-blue-600 text-white px-6 py-2 rounded-full hover:bg-blue-700 transition-colors">Contact</button>
              </div>

              <button 
                className="md:hidden hover:scale-110 transition-transform"
                onClick={() => setIsMenuOpen(!isMenuOpen)}
              >
                {isMenuOpen ? <X className="w-6 h-6" /> : <Menu className="w-6 h-6" />}
              </button>
            </div>

            {isMenuOpen && (
              <div className="md:hidden mt-4 pb-4 space-y-4 mobile-menu-slide">
                <button onClick={() => scrollToSection('services')} className="block w-full text-left text-slate-700 hover:text-blue-600 transition-colors">Services</button>
                <button onClick={() => scrollToSection('realisations')} className="block w-full text-left text-slate-700 hover:text-blue-600 transition-colors">Réalisations</button>
                <button onClick={() => scrollToSection('avis')} className="block w-full text-left text-slate-700 hover:text-blue-600 transition-colors">Avis</button>
                <button onClick={() => scrollToSection('contact')} className="block w-full text-left bg-blue-600 text-white px-4 py-2 rounded-full hover:bg-blue-700 transition-colors">Contact</button>
              </div>
            )}
          </div>
        </nav>

        {/* Hero Section avec animations */}
        <section id="hero" className="pt-20 pb-16 px-6">
          <div className="container mx-auto">
            <div className="grid lg:grid-cols-2 gap-12 items-center min-h-[80vh]">
              <div 
                data-animate="hero-text" 
                className={`space-y-8 ${visibleElements.has('hero-text') ? 'fade-in-up' : 'opacity-0'}`}
              >
                <div className="space-y-6">
                  <h1 className="text-5xl lg:text-6xl font-bold text-slate-800 leading-tight text-reveal">
                    L'informatique 
                    <span className="text-shimmer bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600"> côté pratique</span>
                  </h1>
                  <p className="text-xl text-slate-600 leading-relaxed">
                    Développeur web expérimenté et technicien informatique passionné. 
                    Solutions créatives pour votre présence en ligne et maintenance complète de vos équipements.
                  </p>
                </div>

                <div className="flex flex-col sm:flex-row gap-4">
                  <button 
                    onClick={() => scrollToSection('services')}
                    className="btn-3d bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-8 py-4 rounded-full hover:shadow-lg hover:scale-105 transition-all duration-300 font-semibold flex items-center justify-center gap-2"
                  >
                    <Zap className="w-5 h-5 icon-spark" />
                    Découvrir mes services
                  </button>
                  <a 
                    href="tel:0756913061"
                    className="btn-outline border-2 border-blue-600 text-blue-600 px-8 py-4 rounded-full hover:bg-blue-600 hover:text-white transition-all duration-300 font-semibold flex items-center justify-center gap-2"
                  >
                    <Phone className="w-5 h-5 icon-ring" />
                    07 56 91 30 61
                  </a>
                </div>

                <div className="flex items-center gap-6 text-sm text-slate-600">
                  <div className="flex items-center gap-2 feature-item">
                    <CheckCircle className="w-5 h-5 text-green-500 icon-check" />
                    <span>Devis gratuit</span>
                  </div>
                  <div className="flex items-center gap-2 feature-item">
                    <Clock className="w-5 h-5 text-blue-500 icon-tick" />
                    <span>Intervention rapide</span>
                  </div>
                </div>
              </div>

              <div 
                data-animate="hero-image"
                className={`relative ${visibleElements.has('hero-image') ? 'scale-in' : 'opacity-0'}`}
              >
                <div className="relative z-10 image-hover">
                  <img 
                    src="https://images.unsplash.com/photo-1508361727343-ca787442dcd7" 
                    alt="Innovation technologique Webmatic" 
                    className="rounded-2xl shadow-2xl w-full h-auto object-cover"
                    loading="eager"
                  />
                </div>
                <div className="absolute inset-0 bg-gradient-to-r from-blue-600/20 to-indigo-600/20 rounded-2xl transform rotate-3 -z-10 floating-bg"></div>
              </div>
            </div>
          </div>
        </section>

        {/* Services avec animations */}
        <section id="services" className="py-20 bg-white">
          <div className="container mx-auto px-6">
            <div 
              data-animate="services-header"
              className={`text-center mb-16 ${visibleElements.has('services-header') ? 'fade-in-up' : 'opacity-0'}`}
            >
              <h2 className="text-4xl font-bold text-slate-800 mb-4">Mes Services</h2>
              <p className="text-xl text-slate-600 max-w-3xl mx-auto">
                Solutions complètes pour tous vos besoins informatiques et web
              </p>
            </div>

            <div className="grid md:grid-cols-2 lg:grid-cols-4 gap-8">
              {[
                {
                  icon: <Monitor className="w-12 h-12 text-blue-600" />,
                  title: "Création de Sites Web",
                  description: "Sites sur mesure qui reflètent votre identité et vos objectifs",
                  features: ["Design responsive moderne", "Optimisation SEO incluse", "CMS facile à utiliser", "Hébergement et maintenance"],
                  delay: "0s"
                },
                {
                  icon: <Wrench className="w-12 h-12 text-blue-600" />,
                  title: "Maintenance Informatique",
                  description: "Installation et réparation de matériels informatiques complets",
                  features: ["Diagnostic complet", "Réparation sur site", "Installation matériel", "Formation utilisateur"],
                  delay: "0.1s"
                },
                {
                  icon: <Gamepad2 className="w-12 h-12 text-blue-600" />,
                  title: "Consoles & Gaming",
                  description: "Services spécialisés pour consoles de jeux et optimisation gaming",
                  features: ["Réparation consoles", "Configuration gaming", "Accessoires compatibles", "Optimisation performances"],
                  delay: "0.2s"
                },
                {
                  icon: <Smartphone className="w-12 h-12 text-blue-600" />,
                  title: "Téléphones & Mobile",
                  description: "Réparation et maintenance de smartphones et appareils mobiles",
                  features: ["Réparation écrans", "Changement batteries", "Récupération données", "Configuration appareils"],
                  delay: "0.3s"
                }
              ].map((service, index) => (
                <div 
                  key={index}
                  data-animate={`service-${index}`}
                  className={`service-card group bg-gradient-to-br from-slate-50 to-blue-50 p-8 rounded-2xl hover:shadow-xl transition-all duration-300 hover:-translate-y-2 ${visibleElements.has(`service-${index}`) ? 'fade-in-up' : 'opacity-0'}`}
                  style={{ animationDelay: service.delay }}
                >
                  <div className="mb-6 group-hover:scale-110 transition-transform duration-300 icon-pulse">
                    {service.icon}
                  </div>
                  <h3 className="text-xl font-bold text-slate-800 mb-3">{service.title}</h3>
                  <p className="text-slate-600 mb-6">{service.description}</p>
                  <ul className="space-y-2">
                    {service.features.map((feature, i) => (
                      <li key={i} className="flex items-center gap-2 text-sm text-slate-600 feature-appear" style={{ animationDelay: `${0.5 + i * 0.1}s` }}>
                        <CheckCircle className="w-4 h-4 text-green-500" />
                        {feature}
                      </li>
                    ))}
                  </ul>
                </div>
              ))}
            </div>
          </div>
        </section>

        {/* Réalisations avec animations */}
        <section id="realisations" className="py-20 bg-gradient-to-br from-slate-50 to-blue-50">
          <div className="container mx-auto px-6">
            <div 
              data-animate="realisations-header"
              className={`text-center mb-16 ${visibleElements.has('realisations-header') ? 'fade-in-up' : 'opacity-0'}`}
            >
              <h2 className="text-4xl font-bold text-slate-800 mb-4">Mes Réalisations</h2>
              <p className="text-xl text-slate-600">Découvrez quelques projets que j'ai eu le plaisir de réaliser</p>
            </div>

            <div className="grid lg:grid-cols-2 gap-12">
              {[
                {
                  title: "Sakura Massage",
                  description: "Site vitrine pour un institut de massage. Design élégant et apaisant avec système de réservation en ligne.",
                  image: "https://images.unsplash.com/photo-1743865319071-929ac8a27bcd",
                  url: "sakuramassage.fr",
                  tags: ["Site Vitrine", "Responsive", "Réservation"],
                  animate: "project-1"
                },
                {
                  title: "Hôtel Plaisance",
                  description: "Webmastering complet pour un hôtel. Gestion du contenu, optimisation SEO et maintenance technique.",
                  image: "https://images.unsplash.com/photo-1612999105465-d970b00015a8",
                  url: "hotel-plaisance.com",
                  tags: ["Webmastering", "SEO", "Maintenance"],
                  animate: "project-2"
                }
              ].map((project, index) => (
                <div 
                  key={index}
                  data-animate={project.animate}
                  className={`project-card bg-white rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition-all duration-500 hover:scale-105 ${visibleElements.has(project.animate) ? 'slide-in-up' : 'opacity-0'}`}
                >
                  <div className="relative h-64 overflow-hidden">
                    <img 
                      src={project.image} 
                      alt={project.title} 
                      className="w-full h-full object-cover hover:scale-110 transition-transform duration-500" 
                      loading="lazy"
                    />
                    <div className="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                  </div>
                  <div className="p-8">
                    <h3 className="text-2xl font-bold text-slate-800 mb-3">{project.title}</h3>
                    <p className="text-slate-600 mb-6">{project.description}</p>
                    <div className="flex flex-wrap gap-2 mb-6">
                      {project.tags.map((tag, i) => (
                        <span key={i} className="tag-bounce bg-blue-100 text-blue-600 px-3 py-1 rounded-full text-sm font-medium">
                          {tag}
                        </span>
                      ))}
                    </div>
                    <a 
                      href={`https://${project.url}`} 
                      target="_blank" 
                      rel="noopener noreferrer"
                      className="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-semibold hover:underline transition-all"
                    >
                      Visiter le site <ExternalLink className="w-4 h-4 icon-bounce" />
                    </a>
                  </div>
                </div>
              ))}
            </div>
          </div>
        </section>

        {/* Avis avec animations */}
        <section id="avis" className="py-20 bg-white">
          <div className="container mx-auto px-6">
            <div 
              data-animate="avis-header"
              className={`text-center mb-16 ${visibleElements.has('avis-header') ? 'fade-in-up' : 'opacity-0'}`}
            >
              <h2 className="text-4xl font-bold text-slate-800 mb-4">Avis Clients</h2>
              <p className="text-xl text-slate-600">Ce que disent mes clients sur Google</p>
            </div>

            <div className="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
              {[
                {
                  name: "Thierry Gray",
                  role: "Local Guide · 35 avis",
                  time: "il y a 3 mois",
                  rating: 5,
                  review: "Audric a ressuscité mon vieux PC à priori endommagé par un virus ! Excellent technicien, et en plus très sympathique et réactif. Je vous le recommande particulièrement.",
                  visit: "Visité en avril",
                  animate: "avis-1"
                },
                {
                  name: "Doryan HD",
                  role: "9 avis",
                  time: "il y a 2 ans", 
                  rating: 5,
                  review: "Personne très compétente et à l'écoute du projet. Enfin un professionnel qui n'est pas condescendant dans ce métier. Merci pour votre travail et votre compréhension 🙏 mon activité à vraiment été boosté",
                  visit: "Visité en mars 2023",
                  animate: "avis-2"
                },
                {
                  name: "Annick Feltz",
                  role: "6 avis",
                  time: "il y a 2 ans",
                  rating: 5,
                  review: "Audric m'a fait mon site sur mesure rapidement et continu le suivi pour de nouvelles modifications, très professionnel je recommande !",
                  visit: "Visité en décembre 2022",
                  animate: "avis-3"
                },
                {
                  name: "François Haym", 
                  role: "10 avis",
                  time: "il y a 2 ans",
                  rating: 5,
                  review: "Merci de t'être occupé de reprendre mon site en main ! N'hésitez pas à prendre contact!",
                  visit: "Visité en juillet 2022",
                  animate: "avis-4"
                },
                {
                  name: "Loris Ducrot",
                  role: "1 avis",
                  time: "il y a 2 ans",
                  rating: 5,
                  review: "Service excellent et professionnel. Très satisfait du travail réalisé.",
                  visit: "Visité en juin 2023",
                  animate: "avis-5"
                }
              ].map((review, index) => (
                <div 
                  key={index}
                  data-animate={review.animate}
                  className={`review-card bg-gradient-to-br from-slate-50 to-blue-50 p-6 rounded-2xl hover:shadow-lg transition-all duration-300 hover:scale-105 ${visibleElements.has(review.animate) ? 'fade-in-up' : 'opacity-0'}`}
                  style={{ animationDelay: `${index * 0.1}s` }}
                >
                  <div className="flex items-start gap-4 mb-4">
                    <div className="w-12 h-12 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full flex items-center justify-center avatar-bounce">
                      <span className="text-white font-bold text-lg">{review.name.charAt(0)}</span>
                    </div>
                    <div className="flex-1">
                      <h4 className="font-bold text-slate-800">{review.name}</h4>
                      <p className="text-sm text-slate-600">{review.role}</p>
                      <p className="text-sm text-slate-500">{review.time}</p>
                    </div>
                    <div className="flex gap-1">
                      {[...Array(review.rating)].map((_, i) => (
                        <Star key={i} className="w-4 h-4 fill-yellow-400 text-yellow-400 star-twinkle" style={{ animationDelay: `${i * 0.1}s` }} />
                      ))}
                    </div>
                  </div>
                  <p className="text-slate-700 mb-3 leading-relaxed">{review.review}</p>
                  <p className="text-sm text-slate-500">{review.visit}</p>
                </div>
              ))}
            </div>
          </div>
        </section>

        {/* Contact avec animations */}
        <section id="contact" className="py-20 bg-gradient-to-br from-slate-800 to-blue-900 text-white relative overflow-hidden">
          <div className="absolute inset-0 opacity-10">
            <div className="absolute top-20 left-20 w-40 h-40 bg-blue-500 rounded-full blur-3xl animate-pulse"></div>
            <div className="absolute bottom-20 right-20 w-60 h-60 bg-indigo-500 rounded-full blur-3xl animate-pulse" style={{ animationDelay: '1s' }}></div>
          </div>
          
          <div className="container mx-auto px-6 relative z-10">
            <div className="grid lg:grid-cols-2 gap-16">
              <div 
                data-animate="contact-info"
                className={`${visibleElements.has('contact-info') ? 'slide-in-left' : 'opacity-0'}`}
              >
                <h2 className="text-4xl font-bold mb-8">Contactez-moi</h2>
                <p className="text-xl text-slate-300 mb-8">
                  N'hésitez pas à me contacter pour discuter de votre projet
                </p>

                <div className="space-y-6">
                  <div className="flex items-center gap-4 contact-item">
                    <div className="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center icon-float">
                      <Phone className="w-6 h-6" />
                    </div>
                    <div>
                      <p className="font-semibold">Téléphone</p>
                      <a href="tel:0756913061" className="text-blue-300 hover:text-blue-200 hover:underline transition-all">07 56 91 30 61</a>
                    </div>
                  </div>

                  <div className="flex items-center gap-4 contact-item">
                    <div className="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center icon-float">
                      <MapPin className="w-6 h-6" />
                    </div>
                    <div>
                      <p className="font-semibold">Zone d'intervention</p>
                      <p className="text-slate-300">Pommier (69) et région Rhône-Alpes</p>
                    </div>
                  </div>
                </div>
              </div>

              <div 
                data-animate="contact-hours"
                className={`${visibleElements.has('contact-hours') ? 'slide-in-right' : 'opacity-0'}`}
              >
                <h3 className="text-2xl font-bold mb-8">Horaires d'ouverture</h3>
                <div className="bg-white/10 rounded-2xl p-8 backdrop-blur-sm border border-white/20 schedule-card">
                  <div className="space-y-4">
                    {[
                      { day: "Lundi", hours: "08:00 – 20:00" },
                      { day: "Mardi", hours: "08:00 – 20:00" },
                      { day: "Mercredi", hours: "08:00 – 20:00" },
                      { day: "Jeudi", hours: "08:00 – 20:00" },
                      { day: "Vendredi", hours: "08:00 – 20:00" },
                      { day: "Samedi", hours: "08:00 – 18:00" },
                      { day: "Dimanche", hours: "08:00 – 18:00" }
                    ].map((schedule, index) => (
                      <div key={index} className="flex justify-between items-center py-2 border-b border-white/20 last:border-b-0 schedule-item" style={{ animationDelay: `${index * 0.1}s` }}>
                        <span className="font-medium">{schedule.day}</span>
                        <span className="text-blue-300">{schedule.hours}</span>
                      </div>
                    ))}
                  </div>
                  <div className="mt-6 p-4 bg-green-500/20 rounded-lg guarantee-badge">
                    <p className="text-green-300 font-semibold flex items-center gap-2">
                      <CheckCircle className="w-5 h-5" />
                      Devis gratuit et sans engagement
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </section>

        {/* Footer avec liens légaux */}
        <footer className="bg-slate-900 text-white py-12">
          <div className="container mx-auto px-6">
            <div className="flex flex-col md:flex-row justify-between items-center mb-8">
              <div className="flex items-center space-x-2 mb-4 md:mb-0">
                <div className="w-10 h-10 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center">
                  <Monitor className="text-white w-6 h-6" />
                </div>
                <div>
                  <h3 className="text-xl font-bold">Webmatic</h3>
                  <p className="text-sm text-slate-400">L'informatique côté pratique</p>
                </div>
              </div>
              <div className="text-center md:text-right text-slate-400">
                <p>&copy; 2025 Webmatic. Tous droits réservés.</p>
                <p className="text-sm">Développé avec passion par Audric</p>
              </div>
            </div>
            
            {/* Liens légaux */}
            <div className="border-t border-slate-700 pt-8">
              <div className="flex flex-col md:flex-row justify-between items-center gap-4">
                <div className="flex flex-wrap gap-6 text-sm">
                  <Link to="/mentions-legales" className="text-slate-400 hover:text-white transition-colors hover:underline">
                    Mentions légales
                  </Link>
                  <Link to="/cgv" className="text-slate-400 hover:text-white transition-colors hover:underline">
                    CGV
                  </Link>
                  <span className="text-slate-400">
                    <Shield className="w-4 h-4 inline mr-1" />
                    Site sécurisé
                  </span>
                  <span className="text-slate-400">
                    <Eye className="w-4 h-4 inline mr-1" />
                    Analytics respectueux
                  </span>
                </div>
                <p className="text-sm text-slate-400">SIRET: [À compléter]</p>
              </div>
            </div>
          </div>
        </footer>
      </div>
    </>
  );
};

function App() {
  return (
    <BrowserRouter>
      <Routes>
        <Route path="/" element={<HomePage />} />
        <Route path="/mentions-legales" element={<MentionsLegales />} />
        <Route path="/cgv" element={<CGV />} />
      </Routes>
    </BrowserRouter>
  );
}

export default App;