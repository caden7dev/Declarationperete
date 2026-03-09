<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>e-Déclaration 🇹🇬</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Nunito', sans-serif;
            background-color: #f0f4f8;
            color: #333;
            position: relative;
        }

        /* Arrière-plan avec image fixe - NETTE - 100% VISIBLE */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: url('{{ asset("images/image3.jpeg") }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
            z-index: -2;
            image-rendering: -webkit-optimize-contrast;
            image-rendering: crisp-edges;
            transform: translateZ(0);
            backface-visibility: hidden;
        }

        /* Header */
        header {
            background: linear-gradient(135deg, #1e3a5f 0%, #2d5a8c 100%);
            padding: 1.2rem 5%;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .header-content {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .logo h1 {
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
        }

        .logo .flag {
            background-color: #e74c3c;
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            font-size: 0.9rem;
        }

        nav {
            display: flex;
            align-items: center;
            gap: 2rem;
        }

        nav a {
            color: white;
            text-decoration: none;
            font-weight: 600;
            transition: opacity 0.3s;
        }

        nav a:hover {
            opacity: 0.8;
        }

        .btn-connect {
            background-color: #27ae60;
            color: white;
            padding: 0.6rem 1.5rem;
            border-radius: 6px;
            font-weight: 600;
            transition: background-color 0.3s, transform 0.2s;
        }

        .btn-connect:hover {
            background-color: #229954;
            transform: translateY(-2px);
        }

        /* Hero Section */
        .hero {
            position: relative;
            max-width: 1400px;
            margin: 3rem auto;
            padding: 0 5%;
        }

        .hero-container {
            position: relative;
            background: linear-gradient(135deg, #87ceeb 0%, #4a9fd8 100%);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            min-height: 500px;
            display: flex;
            align-items: center;
        }

        .hero-image {
            position: absolute;
            right: 0;
            top: 0;
            width: 60%;
            height: 100%;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 800 600"><rect fill="%234a9fd8" width="800" height="600"/><rect fill="%23fff" x="200" y="100" width="400" height="400" rx="10"/><rect fill="%23f39c12" x="220" y="120" width="360" height="60"/><rect fill="%2327ae60" x="220" y="200" width="360" height="40"/><rect fill="%233498db" x="220" y="260" width="360" height="40"/><rect fill="%23e74c3c" x="220" y="320" width="360" height="40"/><circle fill="%23fff" cx="400" cy="450" r="30"/></svg>') center/cover;
            opacity: 0.3;
        }

        .hero-content {
            position: relative;
            z-index: 2;
            background-color: rgba(255, 255, 255, 0.98);
            padding: 3rem;
            margin: 2rem;
            border-radius: 15px;
            max-width: 550px;
            box-shadow: 0 5px 30px rgba(0,0,0,0.3);
            backdrop-filter: blur(10px);
        }

        .hero-content h2 {
            font-size: 2.5rem;
            color: #2d5a8c;
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        .hero-content p {
            font-size: 1.1rem;
            color: #555;
            margin-bottom: 2rem;
            line-height: 1.6;
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .btn-primary {
            background-color: #27ae60;
            color: white;
            padding: 0.9rem 2rem;
            border-radius: 8px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(39, 174, 96, 0.3);
        }

        .btn-primary:hover {
            background-color: #229954;
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(39, 174, 96, 0.4);
        }

        .btn-secondary {
            background-color: transparent;
            color: #2d5a8c;
            padding: 0.9rem 2rem;
            border-radius: 8px;
            font-weight: 700;
            text-decoration: none;
            border: 2px solid #2d5a8c;
            transition: all 0.3s;
        }

        .btn-secondary:hover {
            background-color: #2d5a8c;
            color: white;
        }

        /* Bouton "J'ai trouvé un document" - NOUVEAU */
        .btn-found {
            background: linear-gradient(135deg, #f39c12, #e67e22);
            color: white;
            padding: 0.9rem 2rem;
            border-radius: 8px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(243, 156, 18, 0.3);
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .btn-found:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(243, 156, 18, 0.4);
        }

        /* Features Section */
        .features {
            max-width: 1400px;
            margin: 4rem auto;
            padding: 0 5%;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2.5rem;
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.95);
            padding: 2.5rem;
            border-radius: 15px;
            text-align: center;
            transition: all 0.3s;
            box-shadow: 0 5px 20px rgba(0,0,0,0.2);
            backdrop-filter: blur(10px);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
        }

        .icon-wrapper {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            background: linear-gradient(135deg, #27ae60 0%, #229954 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .icon-wrapper svg {
            width: 40px;
            height: 40px;
            color: white;
        }

        .feature-card h3 {
            font-size: 1.4rem;
            color: #2d5a8c;
            margin-bottom: 1rem;
            font-weight: 700;
        }

        .feature-card p {
            color: #666;
            line-height: 1.6;
            font-size: 1rem;
        }

        /* Footer */
        footer {
            background: linear-gradient(135deg, #1e3a5f 0%, #2d5a8c 100%);
            color: white;
            padding: 3rem 5%;
            margin-top: 5rem;
        }

        .footer-content {
            max-width: 1400px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 3rem;
        }

        .footer-section h4 {
            font-size: 1.2rem;
            margin-bottom: 1rem;
            color: #27ae60;
        }

        .footer-section ul {
            list-style: none;
        }

        .footer-section ul li {
            margin-bottom: 0.5rem;
        }

        .footer-section a {
            color: rgba(255,255,255,0.8);
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-section a:hover {
            color: white;
        }

        .footer-bottom {
            text-align: center;
            margin-top: 3rem;
            padding-top: 2rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            color: rgba(255,255,255,0.7);
        }

        /* Responsive */
        @media (max-width: 768px) {
            .header-content {
                flex-direction: column;
                gap: 1rem;
            }

            nav {
                flex-direction: column;
                gap: 1rem;
            }

            .hero-content h2 {
                font-size: 1.8rem;
            }

            .hero-buttons {
                flex-direction: column;
            }

            .features-grid {
                grid-template-columns: 1fr;
            }
        }

        html {
            scroll-behavior: smooth;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header>
        <div class="header-content">
            <div class="logo">
                <h1>e-Déclaration</h1>
                <span class="flag">🇹🇬</span>
            </div>
            <nav>
                <a href="#accueil">Accueil</a>
                <a href="#declarations">Mes Déclarations</a>
                <a href="{{ route('help.public') }}">Aide</a>
                <a href="{{ route('login') }}" class="btn-connect">Se Connecter</a>
            </nav>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero" id="accueil">
        <div class="hero-container">
            <div class="hero-image"></div>
            <div class="hero-content">
                <h2>Simplifiez vos Déclarations en Ligne</h2>
                <p>Déclarations en ligne, Suivi et impression des attestations</p>
                <div class="hero-buttons">
                    <a href="{{ route('register') }}" class="btn-primary">Commencer sa déclaration</a>
                    <a href="{{ route('documents-trouves.create') }}" class="btn-found">
                        📦 J'ai trouvé un document
                    </a>
                    <a href="#declarations" class="btn-secondary">En savoir plus</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section class="features" id="declarations">
        <div class="features-grid">
            <div class="feature-card">
                <div class="icon-wrapper">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <h3>Déclaration de Perte</h3>
                <p>Effectuez vos déclarations de perte de documents en quelques clics</p>
            </div>

            <div class="feature-card">
                <div class="icon-wrapper">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <h3>Suivi des Démarches</h3>
                <p>Suivez en temps réel l'évolution de vos dossiers administratifs</p>
            </div>

            <div class="feature-card">
                <div class="icon-wrapper">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <h3>Attestation Numérique</h3>
                <p>Téléchargez vos attestations certifiées au format numérique</p>
            </div>

            <!-- NOUVELLE CARD -->
            <div class="feature-card" style="background: linear-gradient(135deg, rgba(243, 156, 18, 0.1), rgba(230, 126, 34, 0.05));">
                <div class="icon-wrapper" style="background: linear-gradient(135deg, #f39c12, #e67e22);">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <h3>Document Trouvé ?</h3>
                <p>Aidez quelqu'un à retrouver son document perdu en le déclarant ici</p>
                <a href="{{ route('documents-trouves.create') }}" style="display: inline-block; margin-top: 1rem; color: #f39c12; font-weight: 700; text-decoration: none;">
                    Déclarer un document trouvé →
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h4>e-Déclaration</h4>
                <p>Plateforme officielle de déclaration en ligne du Togo</p>
            </div>
            <div class="footer-section">
                <h4>Liens Rapides</h4>
                <ul>
                    <li><a href="#accueil">Accueil</a></li>
                    <li><a href="#declarations">Mes Déclarations</a></li>
                    <li><a href="{{ route('help.public') }}">Aide</a></li>
                    <li><a href="{{ route('register') }}">Inscription</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Services</h4>
                <ul>
                    <li><a href="{{ route('register') }}">Déclaration de Perte</a></li>
                    <li><a href="{{ route('documents-trouves.create') }}">Document Trouvé</a></li>
                    <li><a href="{{ route('login') }}">Suivi des Démarches</a></li>
                    <li><a href="{{ route('help.public') }}">FAQ</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h4>Contact</h4>
                <ul>
                    <li>📧 contact@edeclaration.tg</li>
                    <li>📞 +228 XX XX XX XX</li>
                    <li>📍 Lomé, Togo</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 e-Déclaration. Tous droits réservés.</p>
        </div>
    </footer>

    <script>
        console.log('✨ e-Déclaration TG - Avec fonctionnalité documents trouvés !');
    </script>
</body>
</html>