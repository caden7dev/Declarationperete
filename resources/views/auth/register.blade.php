<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Créer un compte - e-Déclaration TG</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
        }

        /* Arrière-plan avec image - visible sans overlay */
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
            filter: brightness(0.85);
            image-rendering: -webkit-optimize-contrast;
            image-rendering: crisp-edges;
            transform: translateZ(0);
            backface-visibility: hidden;
        }

        /* Header - avec devise à droite */
        .header {
            background: linear-gradient(135deg, #0f2b3d 0%, #1a4a6f 100%);
            padding: 1rem 5%;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-content {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
        }

        .flag-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0,0,0,0.25);
        }

        .flag-icon svg {
            width: 42px;
            height: 32px;
        }

        .logo h1 {
            color: white;
            font-size: 1.6rem;
            font-weight: 800;
        }

        /* Devise à droite */
        .header-devise {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
        }

        .header-devise .republic-name {
            font-size: 0.65rem;
            color: rgba(255,255,255,.5);
            font-weight: 500;
        }

        .header-devise .republic-devise {
            font-size: 0.55rem;
            color: rgba(255,255,255,.35);
            font-weight: 400;
            margin-top: 2px;
        }

        /* Main Content */
        .main-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
            z-index: 1;
        }

        /* Cadre du formulaire */
        .register-card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 24px;
            padding: 2.5rem;
            width: 100%;
            max-width: 650px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
            animation: slideUp 0.5s ease-out;
            border: 1px solid rgba(0,0,0,0.05);
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .register-card h2 {
            color: #0f172a;
            font-size: 1.8rem;
            font-weight: 800;
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1rem;
        }

        .form-group label {
            display: block;
            color: #475569;
            font-weight: 600;
            margin-bottom: 0.4rem;
            font-size: 0.85rem;
        }

        .form-input, .form-select {
            width: 100%;
            padding: 0.8rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.9rem;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s;
            background: white;
        }

        .form-input:focus, .form-select:focus {
            outline: none;
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }

        .form-input.error, .form-select.error {
            border-color: #ef4444;
        }

        .form-input::placeholder, .form-select::placeholder {
            color: #94a3b8;
            font-size: 0.85rem;
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
        }

        .gender-group {
            display: flex;
            gap: 1.5rem;
            margin-top: 0.5rem;
        }

        .gender-option {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .gender-option input[type="radio"] {
            width: 16px;
            height: 16px;
            cursor: pointer;
            accent-color: #10b981;
        }

        .gender-option label {
            color: #64748b;
            font-size: 0.85rem;
            cursor: pointer;
            margin: 0;
            font-weight: 400;
        }

        .nationality-row {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 1rem;
            align-items: center;
        }

        .nationality-label {
            color: #475569;
            font-weight: 600;
            font-size: 0.85rem;
            white-space: nowrap;
        }

        .phone-row {
            display: flex;
            gap: 0;
        }

        .phone-prefix {
            display: flex;
            align-items: center;
            gap: 0.3rem;
            padding: 0.8rem;
            background-color: #f8fafc;
            border: 2px solid #e2e8f0;
            border-right: none;
            border-radius: 12px 0 0 12px;
            color: #64748b;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .phone-prefix svg {
            width: 16px;
            height: 16px;
        }

        .phone-row .form-input {
            border-radius: 0 12px 12px 0;
        }

        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            padding: 0.9rem;
            border: none;
            border-radius: 12px;
            font-size: 1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
            margin-top: 0.5rem;
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .login-section {
            margin-top: 1.5rem;
            text-align: center;
            padding-top: 1rem;
            border-top: 1px solid #e2e8f0;
        }

        .login-link {
            color: #10b981;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.85rem;
            transition: all 0.3s;
        }

        .login-link:hover {
            color: #059669;
            text-decoration: underline;
        }

        .alert {
            padding: 0.8rem 1rem;
            border-radius: 12px;
            margin-bottom: 1.2rem;
            font-size: 0.85rem;
        }

        .alert-success {
            background-color: #ecfdf5;
            color: #065f46;
            border-left: 4px solid #10b981;
        }

        .alert-error {
            background-color: #fef2f2;
            color: #991b1b;
            border-left: 4px solid #ef4444;
        }

        .error-message {
            color: #ef4444;
            font-size: 0.75rem;
            margin-top: 0.3rem;
        }

        .alert ul {
            margin: 0;
            padding-left: 1.2rem;
        }

        @media (max-width: 768px) {
            .register-card {
                padding: 1.5rem;
                margin: 1rem;
            }

            .register-card h2 {
                font-size: 1.5rem;
            }

            .form-row {
                grid-template-columns: 1fr;
                gap: 0.8rem;
            }

            .nationality-row {
                grid-template-columns: 1fr;
            }

            .nationality-label {
                padding: 0;
            }

            .gender-group {
                flex-direction: column;
                gap: 0.5rem;
            }

            .phone-row {
                flex-direction: column;
            }

            .phone-prefix {
                border-radius: 12px 12px 0 0;
                border-right: 2px solid #e2e8f0;
                border-bottom: none;
            }

            .phone-row .form-input {
                border-radius: 0 0 12px 12px;
            }

            .header-content {
                flex-direction: column;
                text-align: center;
            }

            .header-devise {
                align-items: center;
            }
        }
    </style>
</head>
<body>
    <!-- Header avec devise à droite -->
    <header class="header">
        <div class="header-content">
            <a href="{{ route('home') }}" class="logo">
                <div class="flag-icon">
                    <svg viewBox="0 0 5 4" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                        <rect width="5" height=".8" y="0"   fill="#006A36"/>
                        <rect width="5" height=".8" y=".8"  fill="#FFCB00"/>
                        <rect width="5" height=".8" y="1.6" fill="#006A36"/>
                        <rect width="5" height=".8" y="2.4" fill="#FFCB00"/>
                        <rect width="5" height=".8" y="3.2" fill="#006A36"/>
                        <rect width="1.9" height="2.4" fill="#D21034"/>
                        <polygon points="0.95,0.38 1.07,0.76 1.47,0.76 1.16,0.99 1.28,1.37 0.95,1.14 0.62,1.37 0.74,0.99 0.43,0.76 0.83,0.76" fill="#FFFFFF"/>
                    </svg>
                </div>
                <h1>e-Déclaration</h1>
            </a>
            <div class="header-devise">
                <span class="republic-name">République Togolaise</span>
                <span class="republic-devise">Travail-Liberté-Patrie</span>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="register-card">
            <h2>Créer un compte</h2>

            @if (session('status'))
                <div class="alert alert-success">
                    <span>✅</span> {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-error">
                    <span>❌</span>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="form-group">
                    <label for="last_name">Nom de famille</label>
                    <input 
                        type="text" 
                        id="last_name" 
                        name="last_name" 
                        class="form-input @error('last_name') error @enderror" 
                        placeholder="Votre nom de famille"
                        value="{{ old('last_name') }}"
                        required
                        autofocus
                    >
                    @error('last_name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="first_name">Prénom(s)</label>
                    <input 
                        type="text" 
                        id="first_name" 
                        name="first_name" 
                        class="form-input @error('first_name') error @enderror" 
                        placeholder="Vos prénoms"
                        value="{{ old('first_name') }}"
                        required
                    >
                    @error('first_name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="birth_date">Date de naissance</label>
                        <input 
                            type="date" 
                            id="birth_date" 
                            name="birth_date" 
                            class="form-input @error('birth_date') error @enderror"
                            value="{{ old('birth_date') }}"
                            required
                        >
                        @error('birth_date')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Genre</label>
                        <div class="gender-group">
                            <div class="gender-option">
                                <input 
                                    type="radio" 
                                    id="male" 
                                    name="gender" 
                                    value="male"
                                    {{ old('gender') == 'male' ? 'checked' : '' }}
                                    required
                                >
                                <label for="male">Masculin</label>
                            </div>
                            <div class="gender-option">
                                <input 
                                    type="radio" 
                                    id="female" 
                                    name="gender" 
                                    value="female"
                                    {{ old('gender') == 'female' ? 'checked' : '' }}
                                >
                                <label for="female">Féminin</label>
                            </div>
                        </div>
                        @error('gender')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="form-group">
                    <div class="nationality-row">
                        <span class="nationality-label">Nationalité</span>
                        <select 
                            id="nationality" 
                            name="nationality" 
                            class="form-select @error('nationality') error @enderror"
                            required
                        >
                            <option value="">Sélectionner...</option>
                            <option value="togolaise" {{ old('nationality') == 'togolaise' ? 'selected' : '' }}>Togolaise</option>
                            <option value="autre" {{ old('nationality') == 'autre' ? 'selected' : '' }}>Autre</option>
                        </select>
                    </div>
                    @error('nationality')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Adresse e-mail</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        class="form-input @error('email') error @enderror" 
                        placeholder="exemple@gmail.com"
                        value="{{ old('email') }}"
                        required
                    >
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="phone">Téléphone</label>
                    <div class="phone-row">
                        <div class="phone-prefix">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            +228
                        </div>
                        <input 
                            type="tel" 
                            id="phone" 
                            name="phone" 
                            class="form-input @error('phone') error @enderror" 
                            placeholder="90 00 00 00"
                            value="{{ old('phone') }}"
                        >
                    </div>
                    @error('phone')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="address">Adresse de résidence (Ville/Quartier)</label>
                    <input 
                        type="text" 
                        id="address" 
                        name="address" 
                        class="form-input @error('address') error @enderror" 
                        placeholder="Lomé, Quartier..."
                        value="{{ old('address') }}"
                        required
                    >
                    @error('address')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-input @error('password') error @enderror" 
                        placeholder="Créer un mot de passe"
                        required
                    >
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Confirmer le mot de passe</label>
                    <input 
                        type="password" 
                        id="password_confirmation" 
                        name="password_confirmation" 
                        class="form-input" 
                        placeholder="Confirmer votre mot de passe"
                        required
                    >
                </div>

                <button type="submit" class="btn-submit">
                    S'inscrire
                </button>

                <div class="login-section">
                    <a href="{{ route('login') }}" class="login-link">Déjà un compte ? Se connecter →</a>
                </div>
            </form>
        </div>
    </main>

    <script>
        const inputs = document.querySelectorAll('.form-input, .form-select');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.style.borderColor = '#10b981';
                this.style.boxShadow = '0 0 0 3px rgba(16, 185, 129, 0.1)';
            });
            
            input.addEventListener('blur', function() {
                this.style.borderColor = '#e2e8f0';
                this.style.boxShadow = 'none';
            });
        });

        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const passwordConfirm = document.getElementById('password_confirmation').value;

            if (password !== passwordConfirm) {
                e.preventDefault();
                alert('Les mots de passe ne correspondent pas !');
            }
        });
    </script>
</body>
</html>