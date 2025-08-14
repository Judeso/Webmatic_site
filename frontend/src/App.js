import React, { useState, useEffect } from 'react';
import './App.css';
import { Star, Phone, Mail, MapPin, Clock, Monitor, Wrench, Gamepad2, Smartphone, ExternalLink, Menu, X, CheckCircle, Users, Award, Zap } from 'lucide-react';

const BACKEND_URL = process.env.REACT_APP_BACKEND_URL;

function App() {
  const [isMenuOpen, setIsMenuOpen] = useState(false);
  const [isVisible, setIsVisible] = useState({});

  // Animation on scroll
  useEffect(() => {
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry) => {
          setIsVisible(prev => ({
            ...prev,
            [entry.target.id]: entry.isIntersecting
          }));
        });
      },
      { threshold: 0.1 }
    );

    document.querySelectorAll('[id]').forEach((el) => {
      observer.observe(el);
    });

    return () => observer.disconnect();
  }, []);

  const scrollToSection = (sectionId) => {
    document.getElementById(sectionId)?.scrollIntoView({ behavior: 'smooth' });
    setIsMenuOpen(false);
  };

  return (
    <div className="min-h-screen bg-gradient-to-br from-slate-50 to-blue-50">
      {/* Navigation */}
      <nav className="fixed top-0 w-full bg-white/95 backdrop-blur-sm shadow-sm z-50 border-b border-slate-200">
        <div className="container mx-auto px-6 py-4">
          <div className="flex justify-between items-center">
            <div className="flex items-center space-x-2">
              <div className="w-10 h-10 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl flex items-center justify-center">
                <Monitor className="text-white w-6 h-6" />
              </div>
              <div>
                <h1 className="text-2xl font-bold text-slate-800">Webmatic</h1>
                <p className="text-sm text-slate-600">L'informatique côté pratique</p>
              </div>
            </div>
            
            <div className="hidden md:flex items-center space-x-8">
              <button onClick={() => scrollToSection('services')} className="text-slate-700 hover:text-blue-600 transition-colors">Services</button>
              <button onClick={() => scrollToSection('realisations')} className="text-slate-700 hover:text-blue-600 transition-colors">Réalisations</button>
              <button onClick={() => scrollToSection('avis')} className="text-slate-700 hover:text-blue-600 transition-colors">Avis</button>
              <button onClick={() => scrollToSection('contact')} className="bg-blue-600 text-white px-6 py-2 rounded-full hover:bg-blue-700 transition-colors">Contact</button>
            </div>

            <button 
              className="md:hidden"
              onClick={() => setIsMenuOpen(!isMenuOpen)}
            >
              {isMenuOpen ? <X className="w-6 h-6" /> : <Menu className="w-6 h-6" />}
            </button>
          </div>

          {/* Mobile Menu */}
          {isMenuOpen && (
            <div className="md:hidden mt-4 pb-4 space-y-4">
              <button onClick={() => scrollToSection('services')} className="block w-full text-left text-slate-700 hover:text-blue-600 transition-colors">Services</button>
              <button onClick={() => scrollToSection('realisations')} className="block w-full text-left text-slate-700 hover:text-blue-600 transition-colors">Réalisations</button>
              <button onClick={() => scrollToSection('avis')} className="block w-full text-left text-slate-700 hover:text-blue-600 transition-colors">Avis</button>
              <button onClick={() => scrollToSection('contact')} className="block w-full text-left bg-blue-600 text-white px-4 py-2 rounded-full hover:bg-blue-700 transition-colors">Contact</button>
            </div>
          )}
        </div>
      </nav>

      {/* Hero Section */}
      <section id="hero" className="pt-20 pb-16 px-6">
        <div className="container mx-auto">
          <div className="grid lg:grid-cols-2 gap-12 items-center min-h-[80vh]">
            <div className={`space-y-8 ${isVisible.hero ? 'animate-fadeInUp' : 'opacity-0'}`}>
              <div className="space-y-6">
                <h1 className="text-5xl lg:text-6xl font-bold text-slate-800 leading-tight">
                  L'informatique 
                  <span className="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600"> côté pratique</span>
                </h1>
                <p className="text-xl text-slate-600 leading-relaxed">
                  Développeur web expérimenté et technicien informatique passionné. 
                  Solutions créatives pour votre présence en ligne et maintenance complète de vos équipements.
                </p>
              </div>

              <div className="flex flex-col sm:flex-row gap-4">
                <button 
                  onClick={() => scrollToSection('services')}
                  className="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-8 py-4 rounded-full hover:shadow-lg hover:scale-105 transition-all duration-300 font-semibold flex items-center justify-center gap-2"
                >
                  <Zap className="w-5 h-5" />
                  Découvrir mes services
                </button>
                <a 
                  href="tel:0756913061"
                  className="border-2 border-blue-600 text-blue-600 px-8 py-4 rounded-full hover:bg-blue-600 hover:text-white transition-all duration-300 font-semibold flex items-center justify-center gap-2"
                >
                  <Phone className="w-5 h-5" />
                  07 56 91 30 61
                </a>
              </div>

              <div className="flex items-center gap-6 text-sm text-slate-600">
                <div className="flex items-center gap-2">
                  <CheckCircle className="w-5 h-5 text-green-500" />
                  <span>Devis gratuit</span>
                </div>
                <div className="flex items-center gap-2">
                  <Clock className="w-5 h-5 text-blue-500" />
                  <span>Intervention rapide</span>
                </div>
              </div>
            </div>

            <div className="relative">
              <div className="relative z-10">
                <img 
                  src="https://images.unsplash.com/photo-1508361727343-ca787442dcd7" 
                  alt="Innovation technologique" 
                  className="rounded-2xl shadow-2xl w-full h-auto object-cover"
                />
              </div>
              <div className="absolute inset-0 bg-gradient-to-r from-blue-600/20 to-indigo-600/20 rounded-2xl transform rotate-3 -z-10"></div>
            </div>
          </div>
        </div>
      </section>

      {/* Services */}
      <section id="services" className="py-20 bg-white">
        <div className="container mx-auto px-6">
          <div className="text-center mb-16">
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
                features: ["Design responsive moderne", "Optimisation SEO incluse", "CMS facile à utiliser", "Hébergement et maintenance"]
              },
              {
                icon: <Wrench className="w-12 h-12 text-blue-600" />,
                title: "Maintenance Informatique",
                description: "Installation et réparation de matériels informatiques complets",
                features: ["Diagnostic complet", "Réparation sur site", "Installation matériel", "Formation utilisateur"]
              },
              {
                icon: <Gamepad2 className="w-12 h-12 text-blue-600" />,
                title: "Consoles & Gaming",
                description: "Services spécialisés pour consoles de jeux et optimisation gaming",
                features: ["Réparation consoles", "Configuration gaming", "Accessoires compatibles", "Optimisation performances"]
              },
              {
                icon: <Smartphone className="w-12 h-12 text-blue-600" />,
                title: "Téléphones & Mobile",
                description: "Réparation et maintenance de smartphones et appareils mobiles",
                features: ["Réparation écrans", "Changement batteries", "Récupération données", "Configuration appareils"]
              }
            ].map((service, index) => (
              <div key={index} className="group bg-gradient-to-br from-slate-50 to-blue-50 p-8 rounded-2xl hover:shadow-xl transition-all duration-300 hover:-translate-y-2">
                <div className="mb-6 group-hover:scale-110 transition-transform duration-300">
                  {service.icon}
                </div>
                <h3 className="text-xl font-bold text-slate-800 mb-3">{service.title}</h3>
                <p className="text-slate-600 mb-6">{service.description}</p>
                <ul className="space-y-2">
                  {service.features.map((feature, i) => (
                    <li key={i} className="flex items-center gap-2 text-sm text-slate-600">
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

      {/* Réalisations */}
      <section id="realisations" className="py-20 bg-gradient-to-br from-slate-50 to-blue-50">
        <div className="container mx-auto px-6">
          <div className="text-center mb-16">
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
                tags: ["Site Vitrine", "Responsive", "Réservation"]
              },
              {
                title: "Hôtel Plaisance", 
                description: "Webmastering complet pour un hôtel. Gestion du contenu, optimisation SEO et maintenance technique.",
                image: "https://images.unsplash.com/photo-1612999105465-d970b00015a8",
                url: "hotel-plaisance.com",
                tags: ["Webmastering", "SEO", "Maintenance"]
              }
            ].map((project, index) => (
              <div key={index} className="bg-white rounded-2xl shadow-xl overflow-hidden hover:shadow-2xl transition-shadow duration-300">
                <div className="relative h-64">
                  <img src={project.image} alt={project.title} className="w-full h-full object-cover" />
                  <div className="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                </div>
                <div className="p-8">
                  <h3 className="text-2xl font-bold text-slate-800 mb-3">{project.title}</h3>
                  <p className="text-slate-600 mb-6">{project.description}</p>
                  <div className="flex flex-wrap gap-2 mb-6">
                    {project.tags.map((tag, i) => (
                      <span key={i} className="bg-blue-100 text-blue-600 px-3 py-1 rounded-full text-sm font-medium">
                        {tag}
                      </span>
                    ))}
                  </div>
                  <a 
                    href={`https://${project.url}`} 
                    target="_blank" 
                    rel="noopener noreferrer"
                    className="inline-flex items-center gap-2 text-blue-600 hover:text-blue-700 font-semibold"
                  >
                    Visiter le site <ExternalLink className="w-4 h-4" />
                  </a>
                </div>
              </div>
            ))}
          </div>
        </div>
      </section>

      {/* Avis Clients */}
      <section id="avis" className="py-20 bg-white">
        <div className="container mx-auto px-6">
          <div className="text-center mb-16">
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
                visit: "Visité en avril"
              },
              {
                name: "Doryan HD",
                role: "9 avis",
                time: "il y a 2 ans", 
                rating: 5,
                review: "Personne très compétente et à l'écoute du projet. Enfin un professionnel qui n'est pas condescendant dans ce métier. Merci pour votre travail et votre compréhension 🙏 mon activité à vraiment été boosté",
                visit: "Visité en mars 2023"
              },
              {
                name: "Annick Feltz",
                role: "6 avis",
                time: "il y a 2 ans",
                rating: 5,
                review: "Audric m'a fait mon site sur mesure rapidement et continu le suivi pour de nouvelles modifications, très professionnel je recommande !",
                visit: "Visité en décembre 2022"
              },
              {
                name: "François Haym", 
                role: "10 avis",
                time: "il y a 2 ans",
                rating: 5,
                review: "Merci de t'être occupé de reprendre mon site en main ! N'hésitez pas à prendre contact!",
                visit: "Visité en juillet 2022"
              },
              {
                name: "Loris Ducrot",
                role: "1 avis",
                time: "il y a 2 ans",
                rating: 5,
                review: "Service excellent et professionnel. Très satisfait du travail réalisé.",
                visit: "Visité en juin 2023"
              }
            ].slice(0, 6).map((review, index) => (
              <div key={index} className="bg-gradient-to-br from-slate-50 to-blue-50 p-6 rounded-2xl hover:shadow-lg transition-all duration-300">
                <div className="flex items-start gap-4 mb-4">
                  <div className="w-12 h-12 bg-gradient-to-r from-blue-600 to-indigo-600 rounded-full flex items-center justify-center">
                    <span className="text-white font-bold text-lg">{review.name.charAt(0)}</span>
                  </div>
                  <div className="flex-1">
                    <h4 className="font-bold text-slate-800">{review.name}</h4>
                    <p className="text-sm text-slate-600">{review.role}</p>
                    <p className="text-sm text-slate-500">{review.time}</p>
                  </div>
                  <div className="flex gap-1">
                    {[...Array(review.rating)].map((_, i) => (
                      <Star key={i} className="w-4 h-4 fill-yellow-400 text-yellow-400" />
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

      {/* Contact & Horaires */}
      <section id="contact" className="py-20 bg-gradient-to-br from-slate-800 to-blue-900 text-white">
        <div className="container mx-auto px-6">
          <div className="grid lg:grid-cols-2 gap-16">
            {/* Contact */}
            <div>
              <h2 className="text-4xl font-bold mb-8">Contactez-moi</h2>
              <p className="text-xl text-slate-300 mb-8">
                N'hésitez pas à me contacter pour discuter de votre projet
              </p>

              <div className="space-y-6">
                <div className="flex items-center gap-4">
                  <div className="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center">
                    <Phone className="w-6 h-6" />
                  </div>
                  <div>
                    <p className="font-semibold">Téléphone</p>
                    <a href="tel:0756913061" className="text-blue-300 hover:text-blue-200">07 56 91 30 61</a>
                  </div>
                </div>

                <div className="flex items-center gap-4">
                  <div className="w-12 h-12 bg-blue-600 rounded-xl flex items-center justify-center">
                    <MapPin className="w-6 h-6" />
                  </div>
                  <div>
                    <p className="font-semibold">Zone d'intervention</p>
                    <p className="text-slate-300">Pommier (69) et région Rhône-Alpes</p>
                  </div>
                </div>
              </div>
            </div>

            {/* Horaires */}
            <div>
              <h3 className="text-2xl font-bold mb-8">Horaires d'ouverture</h3>
              <div className="bg-white/10 rounded-2xl p-8 backdrop-blur-sm">
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
                    <div key={index} className="flex justify-between items-center py-2 border-b border-white/20 last:border-b-0">
                      <span className="font-medium">{schedule.day}</span>
                      <span className="text-blue-300">{schedule.hours}</span>
                    </div>
                  ))}
                </div>
                <div className="mt-6 p-4 bg-green-500/20 rounded-lg">
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

      {/* Footer */}
      <footer className="bg-slate-900 text-white py-12">
        <div className="container mx-auto px-6">
          <div className="flex flex-col md:flex-row justify-between items-center">
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
        </div>
      </footer>
    </div>
  );
}

export default App;