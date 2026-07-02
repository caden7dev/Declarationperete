<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Paramètres Agent - e-Déclaration TG</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <script>
        (function() {
            try {
                const savedTheme = localStorage.getItem('darkMode');
                const isDark = savedTheme === 'dark';
                if (isDark) {
                    document.documentElement.style.backgroundColor = '#0f172a';
                    document.body.style.backgroundColor = '#0f172a';
                }
            } catch(e) {}
        })();
    </script>
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        :root {
            --primary: #f39c12;
            --primary-dark: #e67e22;
            --success: #27ae60;
            --danger: #e74c3c;
            --dark: #0f172a;
            --gray-100: #f8fafc;
            --gray-200: #e2e8f0;
            --gray-600: #64748b;
        }
        body {
            font-family: 'Inter', sans-serif;
            background: #f5f7fa;
            transition: background 0.2s;
        }
        body.dark-mode { background: #0f172a; }
        
        /* Sidebar (même style que dashboard agent) */
        .sidebar {
            width: 280px;
            background: rgba(255,255,255,0.98);
            backdrop-filter: blur(10px);
            position: fixed;
            height: 100vh;
            border-right: 1px solid rgba(243,156,18,0.15);
            transition: background 0.2s;
        }
        body.dark-mode .sidebar { background: rgba(20,20,30,0.98); }
        
        .sidebar-header { padding: 2rem 1.5rem 1rem; border-bottom: 1px solid var(--gray-200); }
        .sidebar-header h2 { font-size: 1.3rem; font-weight: 800; display: flex; align-items: center; gap: 0.75rem; color: var(--dark); }
        body.dark-mode .sidebar-header h2 { color: #e5e7eb; }
        .flag-icon { width: 35px; height: 28px; border-radius: 4px; overflow: hidden; }
        .flag-icon svg { width: 100%; height: 100%; }
        .republic { font-size: 0.65rem; color: var(--gray-600); margin-top: 0.3rem; }
        
        .sidebar-nav { padding: 1rem 0; display: flex; flex-direction: column; }
        .sidebar-nav a {
            text-decoration: none; color: var(--gray-600); padding: 0.7rem 1.5rem;
            display: flex; align-items: center; gap: 0.8rem; border-radius: 0 12px 12px 0;
            transition: all 0.2s;
        }
        .sidebar-nav a:hover { background: rgba(243,156,18,0.08); color: var(--primary); }
        .sidebar-nav a.active { background: linear-gradient(135deg, rgba(243,156,18,0.12), rgba(241,196,15,0.08)); color: var(--primary); border-right: 3px solid var(--primary); }
        
        .sidebar-footer { padding: 0.8rem 1rem; border-top: 1px solid var(--gray-200); }
        .logout-link { color: var(--danger); display: flex; align-items: center; gap: 0.6rem; background: none; border: none; width: 100%; cursor: pointer; padding: 0.4rem 0; }
        
        .main { margin-left: 280px; padding: 2rem; }
        .top-bar { background: white; border-radius: 20px; padding: 1.2rem 2rem; margin-bottom: 2rem; display: flex; justify-content: space-between; align-items: center; border: 1px solid var(--gray-200); }
        body.dark-mode .top-bar { background: #1e293b; }
        .top-bar h1 { font-size: 1.5rem; font-weight: 800; color: var(--dark); }
        body.dark-mode .top-bar h1 { color: #f1f5f9; }
        
        .settings-card { background: white; border-radius: 20px; padding: 2rem; margin-bottom: 1.5rem; border: 1px solid var(--gray-200); }
        body.dark-mode .settings-card { background: #1e293b; }
        .settings-card-header { display: flex; align-items: center; gap: 1rem; margin-bottom: 1.5rem; padding-bottom: 1rem; border-bottom: 1px solid var(--gray-200); }
        .settings-card-icon { width: 45px; height: 45px; background: linear-gradient(135deg, var(--primary), var(--primary-dark)); border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; color: white; }
        .form-grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem; }
        .form-group { margin-bottom: 1rem; }
        .form-label { font-weight: 600; color: var(--gray-600); margin-bottom: 0.5rem; display: block; }
        .form-input { width: 100%; padding: 0.75rem; border: 2px solid var(--gray-200); border-radius: 12px; background: white; }
        body.dark-mode .form-input { background: #334155; border-color: #4b5563; color: #e5e7eb; }
        .btn { padding: 0.75rem 1.5rem; border-radius: 12px; font-weight: 700; cursor: pointer; border: none; }
        .btn-primary { background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color: white; }
        .btn-danger { background: #fee2e2; color: var(--danger); }
        .btn-group { display: flex; gap: 1rem; margin-top: 1.5rem; }
        .setting-item { padding: 1rem; background: var(--gray-100); border-radius: 12px; margin-bottom: 1rem; display: flex; justify-content: space-between; align-items: center; }
        body.dark-mode .setting-item { background: #334155; }
        .toggle-switch { position: relative; width: 52px; height: 28px; }
        .toggle-switch input { opacity: 0; }
        .toggle-slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #cbd5e1; border-radius: 28px; transition: 0.3s; }
        .toggle-slider:before { position: absolute; content: ""; height: 22px; width: 22px; left: 3px; bottom: 3px; background-color: white; border-radius: 50%; transition: 0.3s; }
        input:checked + .toggle-slider { background-color: var(--primary); }
        input:checked + .toggle-slider:before { transform: translateX(24px); }
        .alert { padding: 1rem; border-radius: 12px; margin-bottom: 1.5rem; }
        .alert-success { background: #d1fae5; color: #065f46; border-left: 4px solid var(--success); }
        .alert-error { background: #fee2e2; color: #991b1b; border-left: 4px solid var(--danger); }
        
        @media (max-width: 1024px) { .sidebar { width: 100%; position: relative; height: auto; } .main { margin-left: 0; } .form-grid { grid-template-columns: 1fr; } }
    </style>
</head>
<body>

@php
    $user = auth()->user();
    $unreadNotificationsCount = \App\Models\Notification::where('user_id', $user->id)->where('is_read', false)->count();
    $preferences = $user->preferences ?? [];
@endphp

<div class="sidebar">
    <div class="sidebar-header">
        <h2>
            <div class="flag-icon">
                <svg viewBox="0 0 5 4" xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="none">
                    <rect width="5" height=".8" y="0" fill="#006A36"/>
                    <rect width="5" height=".8" y=".8" fill="#FFCB00"/>
                    <rect width="5" height=".8" y="1.6" fill="#006A36"/>
                    <rect width="5" height=".8" y="2.4" fill="#FFCB00"/>
                    <rect width="5" height=".8" y="3.2" fill="#006A36"/>
                    <rect width="1.9" height="2.4" fill="#D21034"/>
                    <polygon points="0.95,0.38 1.07,0.76 1.47,0.76 1.16,0.99 1.28,1.37 0.95,1.14 0.62,1.37 0.74,0.99 0.43,0.76 0.83,0.76" fill="#FFFFFF"/>
                </svg>
            </div>
            e-Déclaration TG
        </h2>
        <div class="republic">RÉPUBLIQUE TOGOLAISE</div>
    </div>

    <nav class="sidebar-nav">
        <a href="{{ route('agent.dashboard') }}"><i class="bi bi-speedometer2"></i> Dashboard</a>
        <a href="{{ route('agent.dashboard', ['statut' => 'en_attente']) }}"><i class="bi bi-hourglass-split"></i> En attente</a>
        <a href="{{ route('agent.documents-trouves.index') }}"><i class="bi bi-search-heart"></i> Documents trouvés</a>
        <a href="{{ route('agent.profile') }}" class="active"><i class="bi bi-person-gear"></i> Paramètres</a>
    </nav>

    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Voulez-vous vraiment vous déconnecter ?')">
    @csrf
    <button type="submit" class="logout-link">
        Déconnecter
    </button>
</form>
    </div>
</div>

<div class="main">
    <div class="top-bar">
        <div>
            <h1><i class="bi bi-gear-fill me-2" style="color: var(--primary);"></i>Paramètres Agent</h1>
            <p style="color: var(--gray-600);">Gérez votre profil et vos préférences</p>
        </div>
        <div class="date-time" id="currentDateTime" style="font-size:0.85rem; color:var(--gray-600);">
            {{ \Carbon\Carbon::now()->locale('fr')->isoFormat('dddd D MMMM YYYY - HH:mm') }}
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success"><i class="bi bi-check-circle me-2"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error"><i class="bi bi-exclamation-triangle me-2"></i> {{ session('error') }}</div>
    @endif

    <!-- Informations personnelles -->
    <div class="settings-card">
        <div class="settings-card-header">
            <div class="settings-card-icon"><i class="bi bi-person"></i></div>
            <div><h3>Informations personnelles</h3><p style="color:var(--gray-600);">Modifiez vos informations de profil</p></div>
        </div>
        <form method="POST" action="{{ route('agent.profile.update') }}">
            @csrf
            @method('PUT')
            <div class="form-grid">
                <div class="form-group"><label class="form-label">Nom complet *</label><input type="text" name="name" class="form-input" value="{{ old('name', $user->name) }}" required></div>
                <div class="form-group"><label class="form-label">Email</label><input type="email" class="form-input" value="{{ $user->email }}" disabled></div>
                <div class="form-group"><label class="form-label">Téléphone</label><input type="tel" name="contact" class="form-input" value="{{ old('contact', $user->contact ?? '') }}"></div>
                <div class="form-group"><label class="form-label">Adresse</label><input type="text" name="address" class="form-input" value="{{ old('address', $user->address ?? '') }}"></div>
            </div>
            <div class="btn-group"><button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Enregistrer</button></div>
        </form>
    </div>

    <!-- Sécurité -->
    <div class="settings-card">
        <div class="settings-card-header">
            <div class="settings-card-icon"><i class="bi bi-shield-lock"></i></div>
            <div><h3>Sécurité</h3><p style="color:var(--gray-600);">Modifiez votre mot de passe</p></div>
        </div>
        <form method="POST" action="{{ route('agent.profile.password') }}">
            @csrf
            @method('PUT')
            <div class="form-grid">
                <div class="form-group"><label class="form-label">Mot de passe actuel *</label><input type="password" name="current_password" class="form-input" required></div>
                <div class="form-group"><label class="form-label">Nouveau mot de passe *</label><input type="password" name="password" class="form-input" required></div>
                <div class="form-group"><label class="form-label">Confirmer *</label><input type="password" name="password_confirmation" class="form-input" required></div>
            </div>
            <div class="btn-group"><button type="submit" class="btn btn-primary"><i class="bi bi-key"></i> Changer le mot de passe</button></div>
        </form>
    </div>

    <!-- Préférences -->
    <div class="settings-card">
        <div class="settings-card-header">
            <div class="settings-card-icon"><i class="bi bi-palette"></i></div>
            <div><h3>Préférences d'affichage</h3><p style="color:var(--gray-600);">Personnalisez votre expérience</p></div>
        </div>
        <div class="setting-item">
            <div><i class="bi bi-moon"></i> <strong>Mode sombre</strong><br><small style="color:var(--gray-600);">Activer le thème sombre</small></div>
            <label class="toggle-switch"><input type="checkbox" id="darkModeToggle" {{ ($preferences['dark_mode'] ?? false) ? 'checked' : '' }}><span class="toggle-slider"></span></label>
        </div>
        <div class="setting-item">
            <div><i class="bi bi-globe"></i> <strong>Langue</strong><br><small style="color:var(--gray-600);">Choisissez votre langue</small></div>
            <select id="languageSelect" style="padding:0.5rem; border-radius:8px; border:1px solid var(--gray-200);">
                <option value="fr" {{ (app()->getLocale() == 'fr') ? 'selected' : '' }}>🇫🇷 Français</option>
                <option value="en" {{ (app()->getLocale() == 'en') ? 'selected' : '' }}>🇬🇧 English</option>
            </select>
        </div>
    </div>
</div>

<script>
    function updateDateTime() {
        const now = new Date();
        const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' };
        const el = document.getElementById('currentDateTime');
        if (el) el.innerHTML = now.toLocaleDateString('fr-FR', options).replace(',', ' -');
    }
    updateDateTime();
    setInterval(updateDateTime, 60000);

    function applyTheme(isDark) {
        if (isDark) document.body.classList.add('dark-mode');
        else document.body.classList.remove('dark-mode');
        localStorage.setItem('darkMode', isDark ? 'dark' : 'light');
    }

    function loadTheme() {
        const saved = localStorage.getItem('darkMode');
        applyTheme(saved === 'dark');
        document.getElementById('darkModeToggle').checked = saved === 'dark';
    }

    document.getElementById('darkModeToggle')?.addEventListener('change', function(e) {
        applyTheme(e.target.checked);
        fetch('{{ route("agent.profile.preferences") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
            body: JSON.stringify({ dark_mode: e.target.checked })
        }).catch(() => {});
    });

    document.getElementById('languageSelect')?.addEventListener('change', function(e) {
        fetch('{{ route("agent.profile.preferences") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Content-Type': 'application/json' },
            body: JSON.stringify({ language: e.target.value })
        }).then(() => window.location.reload());
    });

    loadTheme();
</script>
</body>
</html>