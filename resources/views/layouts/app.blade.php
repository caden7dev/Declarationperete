<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', 'Laravel'))</title>

    {{-- Anti‑flash blanc – exécuté immédiatement --}}
    <script>
        (function() {
            try {
                const isDark = localStorage.getItem('darkMode') === 'true';
                if (isDark) {
                    document.documentElement.style.backgroundColor = '#0f172a';
                    document.body.style.backgroundColor = '#0f172a';
                }
            } catch(e) {}
        })();
    </script>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800,900" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Styles CSS personnalisés pour le mode sombre -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            --primary: #10b981;
            --primary-dark: #059669;
            --primary-light: #34d399;
            --secondary: #3b82f6;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark: #0f172a;
            --gray-100: #f8fafc;
            --gray-200: #e2e8f0;
            --gray-600: #64748b;
            --gray-800: #1e293b;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #f5f7fa;
            transition: background 0.3s, color 0.3s;
        }

        body.dark-mode {
            background: #0f172a;
            color: #e5e7eb;
        }

        /* Adaptations pour les composants Bootstrap */
        body.dark-mode .bg-white {
            background-color: #1e293b !important;
        }

        body.dark-mode .bg-gray-100 {
            background-color: #0f172a !important;
        }

        body.dark-mode .text-gray-500,
        body.dark-mode .text-gray-600,
        body.dark-mode .text-gray-700 {
            color: #94a3b8 !important;
        }

        body.dark-mode .border-gray-200,
        body.dark-mode .border-gray-300 {
            border-color: #334155 !important;
        }

        body.dark-mode .card,
        body.dark-mode .modal-content,
        body.dark-mode .dropdown-menu {
            background-color: #1e293b;
            border-color: #334155;
        }

        body.dark-mode .btn-close {
            filter: invert(1);
        }

        body.dark-mode .table {
            color: #e5e7eb;
        }

        body.dark-mode .table td,
        body.dark-mode .table th {
            border-color: #334155;
        }
    </style>

    <!-- Styles CSS personnalisés -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

    <!-- Styles supplémentaires -->
    @stack('styles')
</head>
<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100">

        <!-- Navigation (inclut déjà le bouton thème) -->
        @if(!request()->is('admin/*'))
            @include('layouts.navigation')
        @endif

        <!-- Page Heading (optionnel) -->
        @hasSection('header')
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    @yield('header')
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            @yield('content')
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Alpine.js (si nécessaire) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- Scripts globaux pour le thème -->
    <script>
        function applyTheme(isDark) {
            if (isDark) {
                document.body.classList.add('dark-mode');
                const icon = document.querySelector('#themeIcon');
                if (icon) {
                    icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>';
                }
            } else {
                document.body.classList.remove('dark-mode');
                const icon = document.querySelector('#themeIcon');
                if (icon) {
                    icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>';
                }
            }
            localStorage.setItem('darkMode', isDark ? 'dark' : 'light');
        }

        function loadTheme() {
            const savedTheme = localStorage.getItem('darkMode');
            const isDark = savedTheme === 'dark';
            applyTheme(isDark);
        }

        function toggleGlobalDarkMode() {
            const isDark = !document.body.classList.contains('dark-mode');
            applyTheme(isDark);
            fetch('{{ route("profile.toggle-dark-mode") }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ dark_mode: isDark })
            }).catch(console.error);
        }

        document.addEventListener('DOMContentLoaded', loadTheme);
    </script>

    <!-- Scripts supplémentaires -->
    @stack('scripts')
</body>
</html>