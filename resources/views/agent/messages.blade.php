<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Messagerie Agent - e-Déclaration TG</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    
    <script>
        (function() {
            try {
                const savedTheme = localStorage.getItem('darkMode');
                const isDark = savedTheme === 'dark';
                if (isDark) {
                    document.documentElement.style.backgroundColor = '#0f172a';
                    document.body.style.backgroundColor = '#0f172a';
                } else {
                    document.documentElement.style.backgroundColor = '#f5f7fa';
                    document.body.style.backgroundColor = '#f5f7fa';
                }
            } catch(e) {}
        })();
    </script>
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        :root {
            --primary: #f39c12;
            --primary-dark: #e67e22;
            --gray-100: #f8fafc;
            --gray-200: #e2e8f0;
            --gray-600: #64748b;
            --dark: #0f172a;
        }
        body {
            font-family: 'Inter', sans-serif;
            background: #f5f7fa;
            transition: background 0.2s;
        }
        body.dark-mode {
            background: #0f172a;
        }
        .app-header {
            background: white;
            border-bottom: 1px solid var(--gray-200);
            padding: 0.8rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
            transition: background 0.2s, border-color 0.2s;
        }
        body.dark-mode .app-header {
            background: #1e293b;
            border-bottom-color: #334155;
        }
        .header-left {
            display: flex;
            align-items: center;
            gap: 1.2rem;
        }
        .back-btn {
            background: transparent;
            border: none;
            font-size: 1.4rem;
            cursor: pointer;
            color: var(--gray-600);
            transition: 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            border-radius: 50%;
        }
        .back-btn:hover {
            background: var(--gray-100);
            color: var(--primary);
        }
        body.dark-mode .back-btn:hover {
            background: #334155;
        }
        .brand-wrapper {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }
        .site-brand {
            display: flex;
            align-items: center;
            gap: 0.7rem;
        }
        .flag-icon {
            width: 40px;
            height: 32px;
            border-radius: 4px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
        }
        .flag-icon svg {
            width: 100%;
            height: 100%;
        }
        .site-name {
            font-weight: 800;
            font-size: 1.3rem;
            background: linear-gradient(135deg, #1f5a3a, #2c6e9e);
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
        }
        .republic-text {
            font-size: 0.65rem;
            font-weight: 500;
            color: var(--gray-600);
            letter-spacing: 0.5px;
            margin-top: 0.2rem;
            margin-left: 0.2rem;
        }
        body.dark-mode .republic-text {
            color: #94a3b8;
        }
        .header-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .date-time {
            font-size: 0.85rem;
            color: var(--gray-600);
            background: var(--gray-100);
            padding: 0.4rem 1rem;
            border-radius: 12px;
            font-weight: 500;
        }
        body.dark-mode .date-time {
            background: #334155;
            color: #94a3b8;
        }
        .icon-btn {
            background: white;
            border: 1px solid var(--gray-200);
            border-radius: 10px;
            padding: 0.45rem;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            width: 36px;
            height: 36px;
        }
        body.dark-mode .icon-btn {
            background: #1e293b;
            border-color: #4b5563;
        }
        .icon-btn svg {
            width: 18px;
            height: 18px;
            stroke: var(--gray-600);
        }
        body.dark-mode .icon-btn svg {
            stroke: #9ca3af;
        }
        .icon-btn:hover {
            border-color: var(--primary);
            background: rgba(243,156,18,0.08);
        }
        .messaging-wrapper {
            padding: 1.5rem;
            height: calc(100vh - 80px);
        }
        .messaging-container {
            background: white;
            border-radius: 24px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            height: 100%;
            border: 1px solid var(--gray-200);
            transition: background 0.2s;
        }
        body.dark-mode .messaging-container {
            background: #1e293b;
            border-color: #334155;
        }
        .main-layout {
            display: flex;
            flex: 1;
            min-height: 0;
        }
        .citizen-sidebar {
            width: 340px;
            background: #fbfdff;
            border-right: 1px solid #edf2f7;
            display: flex;
            flex-direction: column;
            flex-shrink: 0;
        }
        body.dark-mode .citizen-sidebar {
            background: #0f172a;
            border-right-color: #334155;
        }
        .sidebar-header {
            padding: 20px;
            border-bottom: 1px solid #f0f4fa;
        }
        body.dark-mode .sidebar-header {
            border-bottom-color: #334155;
        }
        .sidebar-header h2 {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 16px;
        }
        body.dark-mode .sidebar-header h2 {
            color: #e5e7eb;
        }
        .search-box {
            display: flex;
            align-items: center;
            background: #f2f5f9;
            border-radius: 44px;
            padding: 8px 16px;
            gap: 10px;
            border: 1px solid #e9edf2;
        }
        body.dark-mode .search-box {
            background: #1e293b;
            border-color: #334155;
        }
        .search-box input {
            background: transparent;
            border: none;
            outline: none;
            font-size: 0.9rem;
            width: 100%;
            font-family: 'Inter', sans-serif;
            color: var(--dark);
        }
        body.dark-mode .search-box input {
            color: #e5e7eb;
        }
        .citizen-list {
            flex: 1;
            overflow-y: auto;
            padding: 8px 12px;
        }
        .citizen-item {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 12px;
            margin: 4px 0;
            border-radius: 20px;
            cursor: pointer;
            transition: 0.2s;
        }
        .citizen-item:hover { background: #f0f5fe; }
        body.dark-mode .citizen-item:hover { background: #334155; }
        .citizen-item.active { background: #eef2ff; }
        body.dark-mode .citizen-item.active { background: #2d3748; }
        .avatar {
            width: 48px; height: 48px;
            background: linear-gradient(145deg, #eef2fa, #e3e9f2);
            border-radius: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1.1rem;
            color: #2c5a7a;
            flex-shrink: 0;
        }
        body.dark-mode .avatar {
            background: #4b5563;
            color: #f1f5f9;
        }
        .citizen-info { flex: 1; min-width: 0; }
        .citizen-name {
            font-weight: 600;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }
        .status-dot {
            width: 8px; height: 8px;
            border-radius: 10px;
            display: inline-block;
        }
        .online { background-color: #2ecc71; }
        .offline { background-color: #b0c4de; }
        .citizen-details {
            font-size: 0.7rem;
            color: #6c819a;
            margin-top: 4px;
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
        }
        body.dark-mode .citizen-details {
            color: #94a3b8;
        }
        .badge-case {
            background: #ecf7f0;
            color: #2a7f49;
            padding: 2px 8px;
            border-radius: 30px;
            font-size: 0.65rem;
            font-weight: 500;
        }
        body.dark-mode .badge-case {
            background: #0f3b2c;
            color: #4ade80;
        }
        .last-msg-preview {
            font-size: 0.7rem;
            color: #8da0b2;
            margin-top: 6px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        body.dark-mode .last-msg-preview {
            color: #94a3b8;
        }
        .chat-area {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: #ffffff;
            overflow: hidden;
        }
        body.dark-mode .chat-area {
            background: #0f172a;
        }
        .chat-header {
            padding: 18px 24px;
            border-bottom: 1px solid #eff3f9;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        body.dark-mode .chat-header {
            border-bottom-color: #334155;
        }
        .chat-contact h3 {
            font-weight: 650;
            font-size: 1.2rem;
            color: var(--dark);
        }
        body.dark-mode .chat-contact h3 {
            color: #e5e7eb;
        }
        .chat-contact p {
            font-size: 0.75rem;
            color: #5c7f9c;
            margin-top: 4px;
            display: flex;
            gap: 8px;
            align-items: center;
        }
        body.dark-mode .chat-contact p {
            color: #94a3b8;
        }
        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 18px;
            background: #fefefe;
        }
        body.dark-mode .chat-messages {
            background: #0f172a;
        }
        .message {
            display: flex;
            flex-direction: column;
            max-width: 75%;
            animation: fadeIn 0.2s ease;
        }
        .message.incoming { align-self: flex-start; align-items: flex-start; }
        .message.outgoing { align-self: flex-end; align-items: flex-end; }
        .bubble {
            padding: 12px 18px;
            border-radius: 24px;
            font-size: 0.9rem;
            line-height: 1.45;
            word-break: break-word;
            background: #f1f5fb;
            color: #1a2c3c;
        }
        body.dark-mode .bubble {
            background: #1e293b;
            color: #e5e7eb;
        }
        .outgoing .bubble {
            background: #d9e9ff;
            border-bottom-right-radius: 6px;
        }
        body.dark-mode .outgoing .bubble {
            background: #2d3748;
        }
        .incoming .bubble {
            background: #eff3f8;
            border-bottom-left-radius: 6px;
        }
        .message-meta {
            font-size: 0.65rem;
            color: #8e9fb1;
            margin-top: 6px;
            display: flex;
            gap: 8px;
            align-items: center;
        }
        body.dark-mode .message-meta {
            color: #94a3b8;
        }
        .chat-input-area {
            background: #ffffff;
            border-top: 1px solid #ecf2f9;
            padding: 18px 24px;
            display: flex;
            align-items: center;
            gap: 14px;
        }
        body.dark-mode .chat-input-area {
            background: #0f172a;
            border-top-color: #334155;
        }
        .input-wrapper {
            flex: 1;
            background: #f4f8fe;
            border-radius: 44px;
            padding: 8px 18px;
            display: flex;
            align-items: center;
            gap: 12px;
            border: 1px solid #e6edf4;
        }
        body.dark-mode .input-wrapper {
            background: #1e293b;
            border-color: #334155;
        }
        .input-wrapper input {
            flex: 1;
            background: transparent;
            border: none;
            outline: none;
            font-size: 0.9rem;
            font-family: 'Inter', sans-serif;
            color: var(--dark);
        }
        body.dark-mode .input-wrapper input {
            color: #e5e7eb;
        }
        button.send-btn {
            background: var(--primary);
            border: none;
            padding: 10px 22px;
            border-radius: 44px;
            color: white;
            font-weight: 600;
            font-size: 0.85rem;
            display: flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: 0.2s;
        }
        button.send-btn:hover {
            background: var(--primary-dark);
            transform: scale(0.97);
        }
        .loading, .placeholder {
            text-align: center;
            color: #8e9fb1;
            padding: 20px;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px);}
            to { opacity: 1; transform: translateY(0);}
        }
        @media (max-width: 780px) {
            .citizen-sidebar { width: 280px; }
            .message { max-width: 85%; }
        }
        @media (max-width: 640px) {
            .main-layout { flex-direction: column; }
            .citizen-sidebar { width: 100%; max-height: 40%; border-right: none; border-bottom: 1px solid #edf2f7; }
        }
    </style>
</head>
<body>
    <div class="app-header">
        <div class="header-left">
            <button class="back-btn" onclick="history.back()" title="Retour">
                <i class="bi bi-arrow-left"></i>
            </button>
            <div class="brand-wrapper">
                <div class="site-brand">
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
                    <span class="site-name">e-Déclaration TG</span>
                </div>
                <div class="republic-text">RÉPUBLIQUE TOGOLAISE</div>
            </div>
        </div>
        <div class="header-right">
            <div class="date-time" id="currentDateTime">
                {{ \Carbon\Carbon::now()->locale('fr')->isoFormat('dddd D MMMM YYYY - HH:mm') }}
            </div>
            <button class="icon-btn theme-toggle" id="themeToggleBtn" title="Changer le thème">
                <svg id="themeIcon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </button>
        </div>
    </div>

    <div class="messaging-wrapper">
        <div class="messaging-container">
            <div class="main-layout">
                <div class="citizen-sidebar">
                    <div class="sidebar-header">
                        <h2><i class="bi bi-people-fill"></i> Liste des citoyens</h2>
                        <div class="search-box">
                            <i class="bi bi-search"></i>
                            <input type="text" id="searchCitizen" placeholder="Rechercher par nom...">
                        </div>
                    </div>
                    <div class="citizen-list" id="citizenListContainer">
                        <div class="loading">Chargement des citoyens...</div>
                    </div>
                </div>
                <div class="chat-area">
                    <div class="chat-header">
                        <div class="chat-contact">
                            <h3 id="chatContactName">Sélectionnez un citoyen</h3>
                            <p id="chatContactMeta"></p>
                        </div>
                        <i class="bi bi-telephone-fill"></i>
                    </div>
                    <div class="chat-messages" id="chatMessagesContainer">
                        <div class="placeholder">Cliquez sur un citoyen pour voir la conversation</div>
                    </div>
                    <div class="chat-input-area">
                        <div class="input-wrapper">
                            <i class="bi bi-mic"></i>
                            <input type="text" id="messageInput" placeholder="Écrire un message..." disabled>
                            <i class="bi bi-emoji-smile"></i>
                        </div>
                        <button class="send-btn" id="sendMessageBtn" disabled><i class="bi bi-send-fill"></i> Envoyer</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        // Données injectées par Laravel
        let citizensData = @json($citizens);
        let currentUserId = null;

        console.log('🔍 Données citoyens reçues :', citizensData.length, citizensData);

        function escapeHtml(str) {
            if (!str) return '';
            return str.replace(/[&<>]/g, function(m) {
                if (m === '&') return '&amp;';
                if (m === '<') return '&lt;';
                if (m === '>') return '&gt;';
                return m;
            });
        }

        function getInitials(name) {
            let parts = name.trim().split(' ');
            let initials = '';
            for (let part of parts) {
                if (part.length > 0) initials += part[0].toUpperCase();
            }
            return initials.substring(0, 2);
        }

        function renderCitizenList(data) {
            let html = '';
            if (!data || data.length === 0) {
                html = '<div class="loading">Aucun citoyen trouvé</div>';
            } else {
                data.forEach(cit => {
                    let activeClass = (currentUserId == cit.id) ? 'active' : '';
                    let statusClass = cit.is_online ? 'online' : 'offline';
                    let joinedYear = cit.created_at ? new Date(cit.created_at).getFullYear() : '2024';
                    let casesInfo = cit.cases_count || 0;
                    let lastMsg = cit.last_message ? (cit.last_message.length > 42 ? cit.last_message.substring(0,42)+'…' : cit.last_message) : 'Aucun message';
                    let details = `<div class="citizen-details">
                        <span><i class="bi bi-calendar"></i> Membre ${joinedYear}</span>
                        <span class="badge-case"><i class="bi bi-folder"></i> ${casesInfo} dossier(s)</span>
                    </div>`;
                    html += `
                        <div class="citizen-item ${activeClass}" data-id="${cit.id}">
                            <div class="avatar">${escapeHtml(getInitials(cit.name))}</div>
                            <div class="citizen-info">
                                <div class="citizen-name">
                                    ${escapeHtml(cit.name)}
                                    <span class="status-dot ${statusClass}"></span>
                                </div>
                                ${details}
                                <div class="last-msg-preview">
                                    <i class="bi bi-chat-dots"></i> ${escapeHtml(lastMsg)}
                                </div>
                            </div>
                        </div>
                    `;
                });
            }
            $('#citizenListContainer').html(html);
            // Attacher les événements après rendu
            $('.citizen-item').click(function() {
                let id = $(this).data('id');
                console.log('🔔 Clic sur citoyen ID :', id);
                selectCitizen(id);
            });
        }

        function selectCitizen(userId) {
            currentUserId = userId;
            renderCitizenList(citizensData); // rafraîchit le surlignage
            loadMessages(userId);
            // Activer le champ de saisie et le bouton
            $('#messageInput, #sendMessageBtn').prop('disabled', false);
            let selected = citizensData.find(c => c.id == userId);
            if (selected) {
                $('#chatContactName').text(selected.name);
                $('#chatContactMeta').html('<span class="status-dot online"></span> En ligne · Membre actif');
            } else {
                $('#chatContactName').text('Citoyen');
                $('#chatContactMeta').html('<span class="status-dot online"></span> En ligne');
            }
        }

        function loadMessages(userId) {
            $.get('{{ route("agent.messages.history", "") }}/' + userId, function(messages) {
                let html = '';
                if (messages.length === 0) {
                    html = '<div class="placeholder">Aucun message. Commencez la conversation !</div>';
                } else {
                    messages.forEach(msg => {
                        let direction = msg.direction === 'outgoing' ? 'outgoing' : 'incoming';
                        html += `
                            <div class="message ${direction}">
                                <div class="bubble">${escapeHtml(msg.text)}</div>
                                <div class="message-meta">
                                    <i class="bi bi-check-circle-fill"></i> ${msg.statusText} · ${msg.timestamp}
                                </div>
                            </div>
                        `;
                    });
                }
                $('#chatMessagesContainer').html(html);
                $('#chatMessagesContainer').scrollTop($('#chatMessagesContainer')[0].scrollHeight);
            });
        }

        function sendMessage() {
            let message = $('#messageInput').val().trim();
            if (!message || !currentUserId) {
                console.log('⛔ Message vide ou aucun citoyen sélectionné');
                return;
            }
            $.post('{{ route("agent.messages.envoyer") }}', {
                _token: '{{ csrf_token() }}',
                user_id: currentUserId,
                message: message
            }, function(res) {
                if (res.success) {
                    let msgHtml = `
                        <div class="message outgoing">
                            <div class="bubble">${escapeHtml(res.data.text)}</div>
                            <div class="message-meta"><i class="bi bi-check-circle-fill"></i> ${res.data.statusText} · ${res.data.timestamp}</div>
                        </div>
                    `;
                    $('#chatMessagesContainer').append(msgHtml);
                    $('#chatMessagesContainer').scrollTop($('#chatMessagesContainer')[0].scrollHeight);
                    $('#messageInput').val('');
                    let cit = citizensData.find(c => c.id == currentUserId);
                    if (cit) {
                        cit.last_message = message;
                        renderCitizenList(citizensData);
                    }
                } else {
                    alert('Erreur lors de l\'envoi');
                }
            }).fail(function(xhr) {
                console.error('❌ Erreur réseau :', xhr.status, xhr.responseText);
                alert('Erreur réseau. Vérifiez la console.');
            });
        }

        // Recherche
        $('#searchCitizen').on('input', function() {
            let search = $(this).val().toLowerCase();
            let filtered = citizensData.filter(c => c.name.toLowerCase().includes(search));
            renderCitizenList(filtered);
        });

        $(document).ready(function() {
            renderCitizenList(citizensData);
            $('#sendMessageBtn').click(sendMessage);
            $('#messageInput').keypress(function(e) {
                if (e.which === 13) sendMessage();
            });
        });

        // Horloge et thème
        function updateDateTime() {
            const now = new Date();
            const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' };
            const formatted = now.toLocaleDateString('fr-FR', options).replace(',', ' -');
            const dateTimeEl = document.getElementById('currentDateTime');
            if (dateTimeEl) dateTimeEl.innerHTML = formatted;
        }
        updateDateTime();
        setInterval(updateDateTime, 60000);

        function applyTheme(isDark) {
            if (isDark) {
                document.body.classList.add('dark-mode');
                const icon = document.querySelector('#themeIcon');
                if (icon) icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>';
            } else {
                document.body.classList.remove('dark-mode');
                const icon = document.querySelector('#themeIcon');
                if (icon) icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>';
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
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ dark_mode: isDark })
            }).catch(() => console.log('Mode sombre sauvegardé localement'));
        }

        document.addEventListener('DOMContentLoaded', function() {
            loadTheme();
            const themeBtn = document.getElementById('themeToggleBtn');
            if (themeBtn) themeBtn.addEventListener('click', toggleGlobalDarkMode);
        });
    </script>
</body>
</html>