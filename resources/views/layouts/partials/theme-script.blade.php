<script>
    // ============================================
    // MODE SOMBRE GLOBAL - Synchronisé sur toutes les pages
    // ============================================
    
    // Appliquer le thème
    function applyTheme(isDark) {
        if (isDark) {
            document.body.classList.add('dark-mode');
            // Changer l'icône en lune (si elle existe)
            const themeIcon = document.querySelector('#themeIcon');
            if (themeIcon) {
                themeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>';
            }
            // Mettre à jour le toggle dans les préférences (si présent)
            const darkModeToggle = document.querySelector('input[name="dark_mode"]');
            if (darkModeToggle) {
                darkModeToggle.checked = true;
            }
        } else {
            document.body.classList.remove('dark-mode');
            // Changer l'icône en soleil
            const themeIcon = document.querySelector('#themeIcon');
            if (themeIcon) {
                themeIcon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>';
            }
            const darkModeToggle = document.querySelector('input[name="dark_mode"]');
            if (darkModeToggle) {
                darkModeToggle.checked = false;
            }
        }
        
        // Sauvegarder la préférence dans localStorage
        localStorage.setItem('darkMode', isDark);
        
        // Déclencher un événement personnalisé
        window.dispatchEvent(new CustomEvent('themeChanged', { detail: { darkMode: isDark } }));
    }

    // Charger le thème depuis le localStorage d'abord
    function loadTheme() {
        const localDarkMode = localStorage.getItem('darkMode');
        
        @if(isset($preferences) && isset($preferences['dark_mode']))
            const sessionDarkMode = {{ $preferences['dark_mode'] ? 'true' : 'false' }};
        @else
            const sessionDarkMode = false;
        @endif
        
        let isDark = false;
        
        if (localDarkMode !== null) {
            isDark = localDarkMode === 'true';
        } else {
            isDark = sessionDarkMode;
            localStorage.setItem('darkMode', isDark);
        }
        
        applyTheme(isDark);
    }

    // Basculer le mode sombre
    function toggleGlobalDarkMode() {
        const isDark = !document.body.classList.contains('dark-mode');
        applyTheme(isDark);
        
        // Sauvegarder en session via AJAX
        fetch('{{ route("profile.toggle-dark-mode") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ dark_mode: isDark })
        }).catch(error => console.log('Synchronisation du thème:', error));
        
        // Afficher une notification
        showGlobalNotification('Mode ' + (isDark ? 'sombre' : 'clair') + ' activé');
    }

    // Notification globale
    function showGlobalNotification(message, type = 'success') {
        const notification = document.createElement('div');
        notification.className = `alert alert-${type}`;
        notification.innerHTML = `
            <span>${type === 'success' ? '✅' : 'ℹ️'}</span>
            <span>${message}</span>
        `;
        notification.style.position = 'fixed';
        notification.style.top = '20px';
        notification.style.right = '20px';
        notification.style.zIndex = '9999';
        notification.style.minWidth = '300px';
        notification.style.animation = 'slideDown 0.3s ease-out';
        notification.style.boxShadow = '0 10px 30px rgba(0,0,0,0.15)';
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 300);
        }, 3000);
    }

    // Écouter les changements de thème depuis d'autres onglets
    window.addEventListener('storage', function(e) {
        if (e.key === 'darkMode') {
            const isDark = e.newValue === 'true';
            applyTheme(isDark);
        }
    });

    // Écouter les événements personnalisés
    window.addEventListener('themeChanged', function(e) {
        const darkModeToggle = document.querySelector('input[name="dark_mode"]');
        if (darkModeToggle) {
            darkModeToggle.checked = e.detail.darkMode;
        }
    });

    // Charger le thème au démarrage
    document.addEventListener('DOMContentLoaded', loadTheme);
</script>

<style>
    .global-notification {
        animation: slideInRight 0.3s ease-out;
    }
    
    @keyframes slideInRight {
        from {
            opacity: 0;
            transform: translateX(30px);
        }
        to {
            opacity: 1;
            transform: translateX(0);
        }
    }
    
    /* Styles pour le bouton de thème */
    .theme-toggle svg {
        transition: transform 0.5s ease;
    }
    
    .theme-toggle:hover svg {
        transform: rotate(180deg);
    }
    
    /* Adaptation dark mode pour les boutons */
    body.dark-mode .icon-btn {
        background: #2d2d2d;
        border-color: #404040;
    }
    
    body.dark-mode .icon-btn svg {
        stroke: #9ca3af;
    }
    
    body.dark-mode .icon-btn:hover {
        border-color: var(--primary);
    }
    
    body.dark-mode .icon-btn:hover svg {
        stroke: var(--primary);
    }
</style>