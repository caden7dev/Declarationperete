<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Créer un compte - e-Déclaration TG</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Nunito', sans-serif;
            background: linear-gradient(135deg, #5a8dc4 0%, #7ba8d1 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

         /* Arrière-plan avec image - même que welcome.blade.php */
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
            /* Amélioration de la netteté */
            image-rendering: -webkit-optimize-contrast;
            image-rendering: crisp-edges;
            transform: translateZ(0);
            backface-visibility: hidden;
        }

        /* Pas d'overlay - Image 100% visible */


        .header {
            background-color: #1e3a5f;
            padding: 1.2rem 5%;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }

        .header-content {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo-img {
            width: 50px;
            height: 50px;
            background: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
        }

        .header h1 {
            color: white;
            font-size: 1.5rem;
            font-weight: 700;
        }

        .main-content {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .register-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            width: 100%;
            max-width: 600px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: slideUp 0.5s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .register-card h2 {
            color: #1e3a5f;
            font-size: 2rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 2rem;
        }

        .form-group {
            margin-bottom: 1.2rem;
        }

        .form-group label {
            display: block;
            color: #555;
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 1.5rem;
        }

        .form-input, .form-select {
            width: 100%;
            padding: 0.9rem;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1.4rem;
            font-family: 'Nunito', sans-serif;
            transition: all 0.3s;
        }

        .form-input:focus, .form-select:focus {
            outline: none;
            border-color: #27ae60;
            box-shadow: 0 0 0 3px rgba(39, 174, 96, 0.1);
        }

        .form-input.error, .form-select.error {
            border-color: #e74c3c;
        }

        .form-input::placeholder {
            color: #999;
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
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .gender-option label {
            color: #666;
            font-size: 0.9rem;
            cursor: pointer;
            margin: 0;
            font-weight: 400;
        }

        .nationality-row {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 1rem;
            align-items: end;
        }

        .nationality-label {
            padding: 0.9rem;
            color: #555;
            font-weight: 600;
            font-size: 0.9rem;
            white-space: nowrap;
        }

        .contact-row {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 0;
        }

        .phone-prefix {
            display: flex;
            align-items: center;
            padding: 0.9rem;
            background-color: #f5f5f5;
            border: 2px solid #e0e0e0;
            border-right: none;
            border-radius: 8px 0 0 8px;
            color: #666;
            font-size: 0.95rem;
        }

        .phone-prefix svg {
            width: 18px;
            height: 18px;
            margin-right: 0.3rem;
        }

        .contact-row .form-input {
            border-radius: 0 8px 8px 0;
        }

        .btn-submit {
            width: 100%;
            background-color: #27ae60;
            color: white;
            padding: 1rem;
            border: none;
            border-radius: 10px;
            font-size: 1.3rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(39, 174, 96, 0.3);
            margin-top: 1rem;
        }

        .btn-submit:hover {
            background-color: #229954;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(39, 174, 96, 0.4);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .login-section {
            margin-top: 1.5rem;
            text-align: center;
            padding-top: 1.5rem;
            border-top: 1px solid #e0e0e0;
        }

        .login-link {
            color: #2d5a8c;
            text-decoration: none;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .login-link:hover {
            color: #27ae60;
            text-decoration: underline;
        }

        .alert {
            padding: 1rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            font-size: 0.9rem;
        }

        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .alert-error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }

        .error-message {
            color: #e74c3c;
            font-size: 0.8rem;
            margin-top: 0.3rem;
        }

        @media (max-width: 768px) {
            .register-card {
                padding: 2rem 1.5rem;
                margin: 1rem;
            }

            .register-card h2 {
                font-size: 1.6rem;
            }

            .form-row {
                grid-template-columns: 1fr;
            }

            .nationality-row {
                grid-template-columns: 1fr;
            }

            .nationality-label {
                padding: 0.5rem 0;
            }

            .gender-group {
                flex-direction: column;
                gap: 0.8rem;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <div class="logo-img">🇹🇬</div>
            <h1>e-Déclaration TG</h1>
        </div>
    </header>

    <main class="main-content">
        <div class="register-card">
            <h2>Créer un compte</h2>

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-error">
                    Veuillez corriger les erreurs dans le formulaire.
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Nom de famille -->
                <div class="form-group">
                    <label for="last_name">Nom de famile</label>
                    <input 
                        type="text" 
                        id="last_name" 
                        name="last_name" 
                        class="form-input @error('last_name') error @enderror" 
                        placeholder="Nom de famile"
                        value="{{ old('last_name') }}"
                        required
                        autofocus
                    >
                    @error('last_name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Prénom(s) -->
                <div class="form-group">
                    <label for="first_name">Prénom(s)</label>
                    <input 
                        type="text" 
                        id="first_name" 
                        name="first_name" 
                        class="form-input @error('first_name') error @enderror" 
                        placeholder="Prénom(s)"
                        value="{{ old('first_name') }}"
                        required
                    >
                    @error('first_name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Date de naissance et Genre -->
                <div class="form-row">
                    <div class="form-group">
                        <label for="birth_date">Date de nacisance</label>
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

                <!-- Nationalité -->
                <div class="form-group">
                    <div class="nationality-row">
                        <div class="nationality-label">Nationalité</div>
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

               <!-- Adresse e-mail / Téléphone -->
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
            02
        </div>
        <input 
            type="text" 
            id="phone" 
            name="phone" 
            class="form-input @error('phone') error @enderror" 
            placeholder="0087683088"
            value="{{ old('phone') }}"
        >
    </div>
    @error('phone')
        <div class="error-message">{{ $message }}</div>
    @enderror
</div>


                <!-- Adresse de résidence -->
                <div class="form-group">
                    <label for="address">Adresse de résidence (Ville/Quartier)</label>
                    <input 
                        type="text" 
                        id="address" 
                        name="address" 
                        class="form-input @error('address') error @enderror" 
                        placeholder="Adresse de résidence (Ville/Quartier)"
                        value="{{ old('address') }}"
                        required
                    >
                    @error('address')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Mot de passe -->
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

                <!-- Confirmation mot de passe -->
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
                @if ($errors->any())
    <div class="alert alert-error">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif


                <button type="submit" class="btn-submit">
                    S'inscrire
                </button>

                <div class="login-section">
                    <a href="{{ route('login') }}" class="login-link">Déjà un compte? Se connecter</a>
                </div>
            </form>
        </div>
    </main>

    <script>
        const inputs = document.querySelectorAll('.form-input, .form-select');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.01)';
                this.parentElement.style.transition = 'transform 0.3s';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });

        // Validation côté client
        const form = document.querySelector('form');
        form.addEventListener('submit', function(e) {
            const password = document.getElementById('password').value;
            const passwordConfirm = document.getElementById('password_confirmation').value;

            if (password !== passwordConfirm) {
                e.preventDefault();
                alert('Les mots de passe ne correspondent pas!');
            }
        });
    </script>
</body>
</html>