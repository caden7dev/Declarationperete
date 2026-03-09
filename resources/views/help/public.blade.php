<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aide & FAQ - e-Déclaration TG</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            color: #1e293b;
            min-height: 100vh;
        }

        /* Header */
        header {
            background: linear-gradient(135deg, #1e3a5f 0%, #2d5a8c 100%);
            padding: 1.2rem 5%;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
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
            gap: 0.8rem;
        }

        .logo h1 {
            color: white;
            font-size: 1.6rem;
            font-weight: 800;
            letter-spacing: -0.02em;
        }

        .logo .flag {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            padding: 0.3rem 0.7rem;
            border-radius: 6px;
            font-size: 1rem;
            box-shadow: 0 2px 8px rgba(231, 76, 60, 0.3);
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

        .btn-back {
            background: linear-gradient(135deg, #27ae60, #229954);
            color: white;
            padding: 0.7rem 1.8rem;
            border-radius: 8px;
            font-weight: 700;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(39, 174, 96, 0.3);
            text-decoration: none;
        }

        .btn-back:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(39, 174, 96, 0.4);
        }

        /* Main Content */
        .main-content {
            max-width: 1200px;
            margin: 3rem auto;
            padding: 0 5%;
        }

        .page-header {
            text-align: center;
            margin-bottom: 4rem;
        }

        .page-header h1 {
            font-size: 3rem;
            color: #1e3a5f;
            margin-bottom: 1rem;
            font-weight: 900;
        }

        .page-header p {
            font-size: 1.2rem;
            color: #64748b;
            font-weight: 500;
        }

        /* Search Box */
        .search-box {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            margin-bottom: 3rem;
        }

        .search-input {
            width: 100%;
            padding: 1rem 1.5rem;
            font-size: 1.1rem;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            transition: all 0.3s;
            font-family: 'Inter', sans-serif;
        }

        .search-input:focus {
            outline: none;
            border-color: #27ae60;
            box-shadow: 0 0 0 3px rgba(39, 174, 96, 0.1);
        }

        /* FAQ Section */
        .faq-section {
            background: white;
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            margin-bottom: 2rem;
        }

        .faq-section h2 {
            font-size: 2rem;
            color: #1e3a5f;
            margin-bottom: 2rem;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .faq-item {
            border-bottom: 1px solid #e2e8f0;
            padding: 1.5rem 0;
        }

        .faq-item:last-child {
            border-bottom: none;
        }

        .faq-question {
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer;
            font-size: 1.1rem;
            font-weight: 700;
            color: #1e293b;
            transition: color 0.3s;
        }

        .faq-question:hover {
            color: #27ae60;
        }

        .faq-question .icon {
            font-size: 1.5rem;
            transition: transform 0.3s;
        }

        .faq-item.active .icon {
            transform: rotate(45deg);
        }

        .faq-answer {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease;
            color: #64748b;
            line-height: 1.7;
            font-size: 1rem;
        }

        .faq-item.active .faq-answer {
            max-height: 500px;
            margin-top: 1rem;
        }

        /* Quick Links */
        .quick-links {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            margin-bottom: 3rem;
        }

        .quick-link-card {
            background: white;
            padding: 2rem;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            text-align: center;
            transition: all 0.3s;
            cursor: pointer;
        }

        .quick-link-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 30px rgba(0,0,0,0.12);
        }

        .quick-link-icon {
            font-size: 3rem;
            margin-bottom: 1rem;
        }

        .quick-link-card h3 {
            font-size: 1.3rem;
            color: #1e3a5f;
            margin-bottom: 0.5rem;
            font-weight: 700;
        }

        .quick-link-card p {
            color: #64748b;
            font-size: 0.95rem;
        }

        /* Contact Section */
        .contact-section {
            background: linear-gradient(135deg, #1e3a5f 0%, #2d5a8c 100%);
            color: white;
            padding: 3rem;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
        }

        .contact-section h2 {
            font-size: 2rem;
            margin-bottom: 1rem;
            font-weight: 800;
        }

        .contact-section p {
            font-size: 1.1rem;
            margin-bottom: 2rem;
            opacity: 0.9;
        }

        .contact-info {
            display: flex;
            justify-content: center;
            gap: 3rem;
            flex-wrap: wrap;
        }

        .contact-item {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            font-size: 1.1rem;
        }

        .btn-login {
            background: #27ae60;
            color: white;
            padding: 1rem 2.5rem;
            border-radius: 10px;
            font-weight: 700;
            text-decoration: none;
            display: inline-block;
            margin-top: 2rem;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(39, 174, 96, 0.3);
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(39, 174, 96, 0.4);
        }

        /* Footer */
        footer {
            background: linear-gradient(135deg, #1e3a5f 0%, #2d5a8c 100%);
            color: white;
            padding: 2rem 5%;
            margin-top: 5rem;
            text-align: center;
        }

        footer p {
            opacity: 0.8;
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

            .page-header h1 {
                font-size: 2rem;
            }

            .quick-links {
                grid-template-columns: 1fr;
            }

            .contact-info {
                flex-direction: column;
                gap: 1rem;
            }
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
                <a href="{{ route('home') }}">Accueil</a>
                <a href="{{ route('help.public') }}" style="color: #27ae60;">Aide</a>
                <a href="{{ route('login') }}" class="btn-back">Se Connecter</a>
            </nav>
        </div>
    </header>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Page Header -->
        <div class="page-header">
            <h1>❓ Centre d'Aide</h1>
            <p>Trouvez rapidement les réponses à vos questions</p>
        </div>

        <!-- Search Box -->
        <div class="search-box">
            <input type="text" class="search-input" id="searchInput" placeholder="🔍 Rechercher une question...">
        </div>

        <!-- Quick Links -->
        <div class="quick-links">
            <div class="quick-link-card" onclick="scrollToSection('faq-general')">
                <div class="quick-link-icon">📋</div>
                <h3>Questions Générales</h3>
                <p>Informations de base sur la plateforme</p>
            </div>
            <div class="quick-link-card" onclick="scrollToSection('faq-declaration')">
                <div class="quick-link-icon">📝</div>
                <h3>Faire une Déclaration</h3>
                <p>Comment déclarer une perte de document</p>
            </div>
            <div class="quick-link-card" onclick="scrollToSection('faq-compte')">
                <div class="quick-link-icon">👤</div>
                <h3>Mon Compte</h3>
                <p>Gestion de votre compte utilisateur</p>
            </div>
            <div class="quick-link-card" onclick="scrollToSection('faq-attestation')">
                <div class="quick-link-icon">📄</div>
                <h3>Attestations</h3>
                <p>Télécharger et vérifier vos attestations</p>
            </div>
        </div>

        <!-- FAQ General -->
        <div class="faq-section" id="faq-general">
            <h2>📋 Questions Générales</h2>
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    <span>Qu'est-ce que e-Déclaration TG ?</span>
                    <span class="icon">+</span>
                </div>
                <div class="faq-answer">
                    e-Déclaration TG est la plateforme officielle du gouvernement togolais permettant aux citoyens de déclarer en ligne la perte de leurs documents officiels (CNI, passeport, permis de conduire, etc.) et d'obtenir des attestations de déclaration de perte.
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    <span>Qui peut utiliser cette plateforme ?</span>
                    <span class="icon">+</span>
                </div>
                <div class="faq-answer">
                    Tous les citoyens togolais et résidents au Togo peuvent utiliser cette plateforme pour déclarer la perte de leurs documents officiels. Il suffit de créer un compte avec une adresse email valide.
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    <span>Est-ce que le service est gratuit ?</span>
                    <span class="icon">+</span>
                </div>
                <div class="faq-answer">
                    Oui, la déclaration en ligne est entièrement gratuite. Vous pouvez créer votre compte, faire vos déclarations et télécharger vos attestations sans frais.
                </div>
            </div>
        </div>

        <!-- FAQ Declaration -->
        <div class="faq-section" id="faq-declaration">
            <h2>📝 Faire une Déclaration</h2>
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    <span>Comment faire une déclaration de perte ?</span>
                    <span class="icon">+</span>
                </div>
                <div class="faq-answer">
                    1. Connectez-vous à votre compte<br>
                    2. Cliquez sur "Nouvelle Déclaration"<br>
                    3. Remplissez le formulaire avec les informations demandées<br>
                    4. Vérifiez vos informations<br>
                    5. Soumettez votre déclaration<br>
                    Vous recevrez une notification une fois votre déclaration validée par nos services.
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    <span>Quels documents puis-je déclarer ?</span>
                    <span class="icon">+</span>
                </div>
                <div class="faq-answer">
                    Vous pouvez déclarer la perte des documents suivants : Carte Nationale d'Identité (CNI), Passeport, Permis de Conduire, Acte de Naissance, Carte d'Électeur, et autres documents officiels.
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    <span>Combien de temps prend le traitement ?</span>
                    <span class="icon">+</span>
                </div>
                <div class="faq-answer">
                    Le traitement d'une déclaration prend généralement entre 24 et 48 heures ouvrables. Vous recevrez une notification par email dès que votre déclaration sera validée.
                </div>
            </div>
        </div>

        <!-- FAQ Compte -->
        <div class="faq-section" id="faq-compte">
            <h2>👤 Mon Compte</h2>
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    <span>Comment créer un compte ?</span>
                    <span class="icon">+</span>
                </div>
                <div class="faq-answer">
                    Cliquez sur "S'inscrire" en haut de la page, remplissez le formulaire avec vos informations (nom, prénom, email, mot de passe) et validez votre compte via l'email de confirmation.
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    <span>J'ai oublié mon mot de passe, que faire ?</span>
                    <span class="icon">+</span>
                </div>
                <div class="faq-answer">
                    Sur la page de connexion, cliquez sur "Mot de passe oublié ?". Entrez votre adresse email et suivez les instructions envoyées par email pour réinitialiser votre mot de passe.
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    <span>Comment modifier mes informations personnelles ?</span>
                    <span class="icon">+</span>
                </div>
                <div class="faq-answer">
                    Une fois connecté, allez dans "Paramètres" puis "Modifier le profil". Vous pourrez y modifier vos informations personnelles (nom, prénom, email, téléphone, adresse).
                </div>
            </div>
        </div>

        <!-- FAQ Attestation -->
        <div class="faq-section" id="faq-attestation">
            <h2>📄 Attestations</h2>
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    <span>Comment télécharger mon attestation ?</span>
                    <span class="icon">+</span>
                </div>
                <div class="faq-answer">
                    Une fois votre déclaration validée, allez dans "Mes Déclarations", cliquez sur la déclaration concernée, puis sur le bouton "Télécharger l'attestation PDF". L'attestation comprend un QR code de vérification.
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    <span>L'attestation est-elle valable légalement ?</span>
                    <span class="icon">+</span>
                </div>
                <div class="faq-answer">
                    Oui, l'attestation de déclaration de perte délivrée par notre plateforme est un document officiel reconnu par les autorités togolaises. Elle peut être utilisée pour vos démarches administratives.
                </div>
            </div>
            <div class="faq-item">
                <div class="faq-question" onclick="toggleFaq(this)">
                    <span>Puis-je imprimer mon attestation ?</span>
                    <span class="icon">+</span>
                </div>
                <div class="faq-answer">
                    Oui, vous pouvez télécharger l'attestation au format PDF et l'imprimer. L'attestation comporte un QR code qui permet de vérifier son authenticité en ligne.
                </div>
            </div>
        </div>

        <!-- Contact Section -->
        <div class="contact-section">
            <h2>📞 Besoin d'une aide supplémentaire ?</h2>
            <p>Notre équipe est disponible pour vous accompagner</p>
            <div class="contact-info">
                <div class="contact-item">
                    <span>📧</span>
                    <span>contact@edeclaration.tg</span>
                </div>
                <div class="contact-item">
                    <span>📞</span>
                    <span>+228 XX XX XX XX</span>
                </div>
                <div class="contact-item">
                    <span>🕒</span>
                    <span>Lun-Ven: 8h-17h</span>
                </div>
            </div>
            <a href="{{ route('login') }}" class="btn-login">Se connecter pour déclarer</a>
        </div>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2026 e-Déclaration TG. Tous droits réservés. | Gouvernement de la République Togolaise</p>
    </footer>

    <script>
        // Toggle FAQ
        function toggleFaq(element) {
            const faqItem = element.parentElement;
            const allFaqItems = document.querySelectorAll('.faq-item');
            
            // Close all other items
            allFaqItems.forEach(item => {
                if (item !== faqItem) {
                    item.classList.remove('active');
                }
            });
            
            // Toggle current item
            faqItem.classList.toggle('active');
        }

        // Scroll to section
        function scrollToSection(sectionId) {
            const section = document.getElementById(sectionId);
            if (section) {
                section.scrollIntoView({ behavior: 'smooth', block: 'start' });
            }
        }

        // Search functionality
        const searchInput = document.getElementById('searchInput');
        searchInput.addEventListener('input', function(e) {
            const searchTerm = e.target.value.toLowerCase();
            const faqItems = document.querySelectorAll('.faq-item');
            
            faqItems.forEach(item => {
                const question = item.querySelector('.faq-question span').textContent.toLowerCase();
                const answer = item.querySelector('.faq-answer').textContent.toLowerCase();
                
                if (question.includes(searchTerm) || answer.includes(searchTerm)) {
                    item.style.display = 'block';
                    if (searchTerm.length > 2) {
                        item.classList.add('active');
                    }
                } else {
                    item.style.display = 'none';
                }
            });

            // Show all if search is empty
            if (searchTerm === '') {
                faqItems.forEach(item => {
                    item.style.display = 'block';
                    item.classList.remove('active');
                });
            }
        });

        console.log('✨ Page d\'aide publique chargée !');
    </script>
</body>
</html>