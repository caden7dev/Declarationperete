<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Se connecter - e-Déclaration TG</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Nunito', sans-serif;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
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

        /* Header */
        .header {
            background-color: rgba(30, 58, 95, 0.95);
            padding: 1.2rem 5%;
            box-shadow: 0 2px 20px rgba(0,0,0,0.3);
            backdrop-filter: blur(10px);
            position: relative;
            z-index: 10;
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

        /* ========================================
           🎯 CADRE DU FORMULAIRE - MODIFIEZ ICI
           ======================================== */
        .login-card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 20px;
            
            /* 📏 ESPACEMENT INTÉRIEUR (padding) 
               Avant: 5rem | Maintenant: 2.5rem
               Plus petit = cadre plus compact
               Augmentez pour agrandir, diminuez pour réduire */
            padding: 2.5rem;
            
            width: 100%;
            
            /* 📐 LARGEUR MAXIMALE du cadre
               Avant: 550px | Maintenant: 450px
               Plus petit = cadre plus étroit
               Exemples: 400px (très petit), 500px (moyen), 600px (large) */
            max-width: 450px;
            
            box-shadow: 0 20px 60px rgba(0,0,0,0.4);
            animation: slideUp 0.5s ease-out;
            backdrop-filter: blur(15px);
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* 📝 TITRE "Se connecter"
           Taille réduite de 2.5rem à 2rem */
        .login-card h2 {
            color: #1e3a5f;
            font-size: 2rem;
            font-weight: 700;
            text-align: center;
            margin-bottom: 1.5rem;
        }

        /* Form Styles */
        .form-group {
            /* 📏 ESPACEMENT entre les champs
               Réduit de 1.5rem à 1.2rem */
            margin-bottom: 1.2rem;
        }

        .form-group label {
            display: block;
            color: #555;
            font-weight: 600;
            margin-bottom: 0.5rem;
            /* 📝 Taille des labels réduite */
            font-size: 1rem;
        }

        .form-input {
            width: 100%;
            /* 📏 Hauteur des champs de saisie
               Réduit de 1rem à 0.9rem */
            padding: 0.9rem;
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            /* 📝 Taille du texte dans les champs */
            font-size: 1rem;
            font-family: 'Nunito', sans-serif;
            transition: all 0.3s;
            background: white;
        }

        .form-input:focus {
            outline: none;
            border-color: #27ae60;
            box-shadow: 0 0 0 3px rgba(39, 174, 96, 0.1);
        }

        .form-input::placeholder {
            color: #999;
            font-size: 0.95rem;
        }

        /* Remember & Forgot */
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.2rem;
        }

        .remember-me {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .remember-me input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        .remember-me label {
            color: #666;
            font-size: 0.9rem;
            cursor: pointer;
            margin: 0;
        }

        .forgot-link {
            color: #666;
            text-decoration: none;
            font-size: 0.9rem;
            transition: color 0.3s;
        }

        .forgot-link:hover {
            color: #27ae60;
            text-decoration: underline;
        }

        /* Submit Button */
        .btn-submit {
            width: 100%;
            background-color: #27ae60;
            color: white;
            /* 📏 Hauteur du bouton réduite */
            padding: 0.9rem;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(39, 174, 96, 0.3);
        }

        .btn-submit:hover {
            background-color: #229954;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(39, 174, 96, 0.4);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        /* Register Link */
        .register-section {
            margin-top: 1.5rem;
            text-align: center;
            padding-top: 1.2rem;
            border-top: 1px solid #e0e0e0;
        }

        .register-section p {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }

        .register-link {
            color: #27ae60;
            text-decoration: none;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.3s;
        }

        .register-link:hover {
            color: #229954;
            text-decoration: underline;
        }

        /* Alert Messages */
        .alert {
            padding: 0.9rem;
            border-radius: 10px;
            margin-bottom: 1.2rem;
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

        /* Responsive */
        @media (max-width: 768px) {
            .login-card {
                padding: 2rem 1.5rem;
                margin: 1rem;
            }

            .login-card h2 {
                font-size: 1.6rem;
            }

            .form-options {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }
        }
    </style>
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="header-content">
            <div class="logo-img">🇹🇬</div>
            <h1>e-Déclaration TG</h1>
        </div>
    </header>

    <!-- Main Content -->
    <main class="main-content">
        <div class="login-card">
            <h2>Se connecter</h2>

            <!-- Session Status (Success Message) -->
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Validation Errors -->
            @if($errors->any())
                <div class="alert alert-error">
                    <ul style="margin:0; padding-left: 1.2rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                <!-- Email or Phone -->
                <div class="form-group">
                    <label for="email">Email</label> 
                    <input 
                        type="text" 
                        id="email" 
                        name="email" 
                        class="form-input" 
                        placeholder="Entrez votre email"
                        value="{{ old('email') }}"
                        required 
                        autofocus
                    >
                </div>

                <!-- Password -->
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        class="form-input" 
                        placeholder="Entrez votre mot de passe"
                        required
                    >
                </div>

                <!-- Remember Me & Forgot Password -->
                <div class="form-options">
                    <div class="remember-me">
                        <input type="checkbox" id="remember" name="remember" {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember">Se souvenir de moi</label>
                    </div>
                    <a href="{{ route('password.request') }}" class="forgot-link">Mot de passe oublié?</a>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn-submit">
                    Se connecter
                </button>

                <!-- Register Link -->
                <div class="register-section">
                    <p>Vous n'avez pas encore de compte ?</p>
                    <a href="{{ route('register') }}" class="register-link">Créer un compte →</a>
                </div>
            </form>
        </div>
    </main>

    <script>
        // Add smooth animations to inputs
        const inputs = document.querySelectorAll('.form-input');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'scale(1.02)';
                this.parentElement.style.transition = 'transform 0.3s';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'scale(1)';
            });
        });
    </script>
</body>
</html>