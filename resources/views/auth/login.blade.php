<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Se connecter - e-Déclaration TG</title>
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
        .login-card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 24px;
            padding: 2.5rem;
            width: 100%;
            max-width: 450px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.15);
            animation: slideUp 0.5s ease-out;
            border: 1px solid rgba(0,0,0,0.05);
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .login-card h2 {
            color: #0f172a;
            font-size: 1.8rem;
            font-weight: 800;
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .form-group {
            margin-bottom: 1.2rem;
        }

        .form-group label {
            display: block;
            color: #475569;
            font-weight: 600;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }

        .form-input {
            width: 100%;
            padding: 0.9rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            font-size: 0.95rem;
            font-family: 'Inter', sans-serif;
            transition: all 0.3s;
            background: white;
        }

        .form-input:focus {
            outline: none;
            border-color: #10b981;
            box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
        }

        .form-input::placeholder {
            color: #94a3b8;
            font-size: 0.9rem;
        }

        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .remember-me input[type="checkbox"] {
            width: 16px;
            height: 16px;
            cursor: pointer;
            accent-color: #10b981;
        }

        .remember-me label {
            color: #64748b;
            font-size: 0.85rem;
            cursor: pointer;
            margin: 0;
        }

        .forgot-link {
            color: #64748b;
            text-decoration: none;
            font-size: 0.85rem;
            transition: color 0.3s;
        }

        .forgot-link:hover {
            color: #10b981;
            text-decoration: underline;
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
        }

        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .register-section {
            margin-top: 1.5rem;
            text-align: center;
            padding-top: 1.2rem;
            border-top: 1px solid #e2e8f0;
        }

        .register-section p {
            color: #64748b;
            font-size: 0.85rem;
            margin-bottom: 0.5rem;
        }

        .register-link {
            color: #10b981;
            text-decoration: none;
            font-weight: 700;
            font-size: 0.9rem;
            transition: all 0.3s;
        }

        .register-link:hover {
            color: #059669;
            text-decoration: underline;
        }

        .alert {
            padding: 0.9rem 1rem;
            border-radius: 12px;
            margin-bottom: 1.2rem;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
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

        .alert ul {
            margin: 0;
            padding-left: 1.2rem;
        }

        @media (max-width: 768px) {
            .login-card {
                padding: 2rem 1.5rem;
                margin: 1rem;
            }

            .login-card h2 {
                font-size: 1.5rem;
            }

            .form-options {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
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
        <div class="login-card">
            <h2>Se connecter</h2>

            @if(session('success'))
                <div class="alert alert-success">
                    <span>✅</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-error">
                    <span>❌</span>
                    <div>
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <div class="form-group">
                    <label for="email">Email</label> 
                    <input 
                        type="text" 
                        id="email" 
                        name="email" 
                        class="form-input" 
                        placeholder="exemple@email.com"
                        value="{{ old('email') }}"
                        required 
                        autofocus
                    >
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-input" 
                        placeholder="••••••••"
                        required
                    >
                </div>

                <div class="form-options">
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember">Se souvenir de moi</label>
                    </div>
                    <a href="{{ route('password.request') }}" class="forgot-link">Mot de passe oublié ?</a>
                </div>

                <button type="submit" class="btn-submit">
                    Se connecter
                </button>

                <div class="register-section">
                    <p>Vous n'avez pas encore de compte ?</p>
                    <a href="{{ route('register') }}" class="register-link">Créer un compte →</a>
                </div>
            </form>
        </div>
    </main>

    <script>
        const inputs = document.querySelectorAll('.form-input');
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
    </script>
</body>
</html>