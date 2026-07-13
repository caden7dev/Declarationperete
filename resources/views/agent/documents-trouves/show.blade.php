<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dossier Document Trouvé — Agent | e-Déclaration TG</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <!-- ⚡ ANTI-FLASH BLANC -->
    <script>
        (function() {
            try {
                const savedTheme = localStorage.getItem('darkMode');
                const isDark = savedTheme === 'dark';
                if (isDark) {
                    document.documentElement.style.backgroundColor = '#0f172a';
                    document.body.style.backgroundColor = '#0f172a';
                    document.documentElement.classList.add('dark-mode');
                } else {
                    document.documentElement.style.backgroundColor = '#f5f7fa';
                    document.body.style.backgroundColor = '#f5f7fa';
                }
            } catch(e) {}
        })();
    </script>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --primary: #f39c12;
            --primary-dark: #e67e22;
            --primary-light: #f1c40f;
            --success: #27ae60;
            --danger: #e74c3c;
            --info: #3498db;
            --dark: #0f172a;
            --gray-100: #f8fafc;
            --gray-200: #e2e8f0;
            --gray-600: #64748b;
            --gray-800: #1e293b;
        }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            position: relative;
            background: #f5f7fa;
            transition: background 0.2s ease;
        }

        body.dark-mode {
            background: #0f172a;
        }

        /* ===== SIDEBAR ===== (identique au dashboard agent) */
        .sidebar {
            width: 280px;
            background: rgba(255, 255, 255, 0.98);
            backdrop-filter: blur(10px);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 100;
            border-right: 1px solid rgba(243, 156, 18, 0.15);
            box-shadow: 2px 0 20px rgba(0,0,0,0.05);
            transition: background 0.2s, border-color 0.2s;
        }

        body.dark-mode .sidebar {
            background: rgba(20, 20, 30, 0.98);
            border-right-color: rgba(243, 156, 18, 0.3);
        }

        .sidebar-header {
            padding: 2rem 1.5rem 1rem 1.5rem;
            border-bottom: 1px solid var(--gray-200);
        }

        body.dark-mode .sidebar-header {
            border-bottom-color: #334155;
        }

        .sidebar-header h2 {
            font-size: 1.3rem;
            font-weight: 800;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        body.dark-mode .sidebar-header h2 {
            color: #e5e7eb;
        }

        .flag-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 35px;
            height: 28px;
            border-radius: 4px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.2);
            flex-shrink: 0;
        }

        .flag-icon svg {
            width: 100%;
            height: 100%;
        }

        .republic-text {
            font-size: 0.65rem;
            color: var(--gray-600);
            font-weight: 500;
            letter-spacing: 0.5px;
            margin-top: 0.3rem;
            margin-left: 0.5rem;
        }

        body.dark-mode .republic-text {
            color: #94a3b8;
        }

        .agent-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            font-size: 0.7rem;
            font-weight: 700;
            padding: 0.3rem 0.8rem;
            border-radius: 50px;
            margin-top: 0.5rem;
            text-transform: uppercase;
        }

        .nav-section {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--gray-600);
            padding: 1rem 1.5rem 0.5rem 1.5rem;
        }

        body.dark-mode .nav-section {
            color: #64748b;
        }

        .sidebar-nav {
            flex: 1;
            padding: 0.5rem 0;
            display: flex;
            flex-direction: column;
            overflow-y: auto;
        }

        .sidebar-nav a {
            text-decoration: none;
            color: var(--gray-600);
            font-weight: 500;
            padding: 0.7rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            transition: all 0.2s;
            font-size: 0.9rem;
            border-radius: 0 12px 12px 0;
        }

        body.dark-mode .sidebar-nav a {
            color: #9ca3af;
        }

        .sidebar-nav a i {
            width: 20px;
            font-size: 1.1rem;
        }

        .sidebar-nav a:hover {
            background: rgba(243, 156, 18, 0.08);
            color: var(--primary);
        }

        body.dark-mode .sidebar-nav a:hover {
            background: rgba(243, 156, 18, 0.2);
        }

        .sidebar-nav a.active {
            background: linear-gradient(135deg, rgba(243, 156, 18, 0.12), rgba(241, 196, 15, 0.08));
            color: var(--primary);
            font-weight: 600;
            border-right: 3px solid var(--primary);
        }

        .nav-badge {
            margin-left: auto;
            background: var(--danger);
            color: white;
            font-size: 0.65rem;
            font-weight: 700;
            min-width: 22px;
            height: 22px;
            border-radius: 11px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 6px;
        }

        .sidebar-footer {
            padding: 0.8rem 1rem;
            border-top: 1px solid var(--gray-200);
        }

        body.dark-mode .sidebar-footer {
            border-top-color: #334155;
        }

        .logout-link {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            text-decoration: none;
            color: var(--danger);
            font-weight: 950;
            background: none;
            border: none;
            width: 100%;
            cursor: pointer;
            padding: 0.4rem 0;
        }

        .logout-link svg {
            width: 16px;
            height: 16px;
        }

        .logout-link:hover {
            opacity: 0.8;
            transform: translateX(3px);
        }

        /* ===== MAIN CONTENT ===== */
        .main {
            margin-left: 280px;
            flex: 1;
            padding: 2rem;
            overflow-y: auto;
        }

        /* Top bar */
        .top-bar {
            background: white;
            border-radius: 20px;
            padding: 1.2rem 2rem;
            margin-bottom: 2rem;
            border: 1px solid var(--gray-200);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            transition: background 0.2s, border-color 0.2s;
        }

        body.dark-mode .top-bar {
            background: #1e293b;
            border-color: #334155;
        }

        .top-bar-left h1 {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 0.2rem;
        }

        body.dark-mode .top-bar-left h1 {
            color: #f1f5f9;
        }

        .top-bar-left p {
            color: var(--gray-600);
            font-size: 0.85rem;
        }

        body.dark-mode .top-bar-left p {
            color: #94a3b8;
        }

        .top-bar-right {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .date-time {
            font-size: 0.85rem;
            color: var(--gray-600);
            background: var(--gray-100);
            padding: 0.5rem 1rem;
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
            transition: all 0.2s;
            width: 38px;
            height: 38px;
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
            background: rgba(243, 156, 18, 0.08);
        }

        .icon-btn:hover svg {
            stroke: var(--primary);
        }

        .notification-btn {
            position: relative;
        }

        .notification-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background: var(--danger);
            color: white;
            font-size: 0.6rem;
            font-weight: 700;
            min-width: 16px;
            height: 16px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 4px;
        }

        /* Alertes */
        .alert {
            padding: 1rem 1.2rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            background: white;
            border-left: 4px solid var(--success);
            transition: background 0.2s;
        }

        body.dark-mode .alert {
            background: #1e293b;
            color: #e5e7eb;
        }

        .alert-success { border-left-color: var(--success); }
        .alert-error { border-left-color: var(--danger); }

        /* ============================================================
        📬 BOUTON "MARQUER COMME TRAITÉ" - PRISE EN CHARGE
        ============================================================ */
        .action-banner {
            background: #F0F7F3;
            border: 1px solid #006A36;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
        }

        body.dark-mode .action-banner {
            background: #0a3b2a;
            border-color: #059669;
        }

        .action-banner .ab-title {
            color: #006A36;
            font-weight: 700;
            margin: 0;
            font-size: 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        body.dark-mode .action-banner .ab-title {
            color: #86efac;
        }

        .action-banner .ab-text {
            color: #5A6478;
            font-size: 0.85rem;
            margin-top: 3px;
        }

        body.dark-mode .action-banner .ab-text {
            color: #94a3b8;
        }

        .btn-accept {
            background: #006A36;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0.65rem 1.4rem;
            font-weight: 600;
            cursor: pointer;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: background 0.2s;
            text-decoration: none;
        }

        .btn-accept:hover {
            background: #004d27;
        }

        body.dark-mode .btn-accept {
            background: #059669;
        }

        body.dark-mode .btn-accept:hover {
            background: #047857;
        }

        .btn-restituer-banner {
            background: #D21034;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0.65rem 1.4rem;
            font-weight: 600;
            cursor: pointer;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: background 0.2s;
            text-decoration: none;
        }

        .btn-restituer-banner:hover {
            background: #b00d2a;
        }

        body.dark-mode .btn-restituer-banner {
            background: #dc2626;
        }

        body.dark-mode .btn-restituer-banner:hover {
            background: #b91c1c;
        }

        /* ============================================================
        📬 NOTIFICATION - MARQUER COMME LU (existant)
        ============================================================ */
        .notification-banner {
            background: #F0F7F3;
            border: 1px solid #006A36;
            border-radius: 10px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
        }

        body.dark-mode .notification-banner {
            background: #0a3b2a;
            border-color: #059669;
        }

        .notification-banner .nb-title {
            color: #006A36;
            font-weight: 700;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        body.dark-mode .notification-banner .nb-title {
            color: #86efac;
        }

        .notification-banner .nb-text {
            color: #5A6478;
            font-size: 0.85rem;
            margin-top: 3px;
        }

        body.dark-mode .notification-banner .nb-text {
            color: #94a3b8;
        }

        .btn-mark-read {
            background: #006A36;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 0.6rem 1.2rem;
            font-weight: 600;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            transition: background 0.2s;
            flex-shrink: 0;
        }

        .btn-mark-read:hover {
            background: #004d27;
        }

        body.dark-mode .btn-mark-read {
            background: #059669;
        }

        body.dark-mode .btn-mark-read:hover {
            background: #047857;
        }

        /* Page Header */
        .page-header {
            background: white;
            border-radius: 20px;
            padding: 1.5rem 2rem;
            margin-bottom: 2rem;
            border: 1px solid var(--gray-200);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        body.dark-mode .page-header {
            background: #1e293b;
            border-color: #334155;
        }

        .page-header h1 {
            font-size: 1.5rem;
            font-weight: 800;
            color: var(--dark);
            display: flex;
            align-items: center;
            gap: 0.8rem;
            flex-wrap: wrap;
        }

        body.dark-mode .page-header h1 {
            color: #f1f5f9;
        }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--gray-100);
            color: var(--gray-600);
            padding: 0.7rem 1.5rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s;
        }

        body.dark-mode .back-btn {
            background: #334155;
            color: #94a3b8;
        }

        .back-btn:hover {
            background: var(--gray-200);
            color: var(--primary);
        }

        /* Statut pills */
        .statut-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.3rem 1rem;
            border-radius: 50px;
            font-size: 0.8rem;
            font-weight: 600;
            border: 1px solid transparent;
        }

        .sp-dot { width: 7px; height: 7px; border-radius: 50%; background: currentColor; }
        .sp-dot.pulse { animation: pulse 1.8s infinite; }

        @keyframes pulse {
            0%,100%{opacity:1} 50%{opacity:0.3}
        }

        .sp-attente  { background: #fef3c7; color: #b45309; border-color: #fde68a; }
        .sp-matche   { background: #dbeafe; color: #1d4ed8; border-color: #bfdbfe; }
        .sp-restitue { background: #d1fae5; color: #065f46; border-color: #a7f3d0; }
        .sp-archive  { background: #f1f5f9; color: #475569; border-color: #e2e8f0; }

        body.dark-mode .sp-attente  { background: #422d0b; color: #fbbf24; border-color: #713f12; }
        body.dark-mode .sp-matche   { background: #1e3a5f; color: #60a5fa; border-color: #1e40af; }
        body.dark-mode .sp-restitue { background: #0a3b2a; color: #34d399; border-color: #065f46; }
        body.dark-mode .sp-archive  { background: #1e293b; color: #94a3b8; border-color: #334155; }

        /* Matched banner */
        .matched-banner {
            background: linear-gradient(135deg, #ecfdf5, #d1fae5);
            border: 1px solid #6ee7b7;
            border-radius: 16px;
            padding: 1rem 1.5rem;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        body.dark-mode .matched-banner {
            background: linear-gradient(135deg, #0a3b2a, #064e3b);
            border-color: #059669;
        }

        .mb-icon { font-size: 1.8rem; flex-shrink: 0; }
        .mb-title { font-weight: 700; color: #065f46; font-size: 0.9rem; }
        body.dark-mode .mb-title { color: #a7f3d0; }
        .mb-text { font-size: 0.8rem; color: #047857; margin-top: 0.2rem; line-height: 1.5; }
        body.dark-mode .mb-text { color: #86efac; }

        /* Stats row */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 1rem 1.2rem;
            border: 1px solid var(--gray-200);
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        body.dark-mode .stat-card {
            background: #1e293b;
            border-color: #334155;
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .stat-label { font-size: 0.65rem; text-transform: uppercase; font-weight: 600; color: var(--gray-600); }
        .stat-value { font-size: 1rem; font-weight: 800; color: var(--dark); margin-top: 0.2rem; }
        body.dark-mode .stat-value { color: #f1f5f9; }

        /* Main grid */
        .main-grid {
            display: grid;
            grid-template-columns: 2fr 340px;
            gap: 1.5rem;
            align-items: start;
        }

        /* Cards */
        .card {
            background: white;
            border-radius: 20px;
            border: 1px solid var(--gray-200);
            margin-bottom: 1.5rem;
            transition: background 0.2s;
        }

        body.dark-mode .card {
            background: #1e293b;
            border-color: #334155;
        }

        .card-head {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 1rem 1.4rem;
            border-bottom: 1px solid var(--gray-200);
        }

        body.dark-mode .card-head {
            border-bottom-color: #334155;
        }

        .card-head-icon {
            width: 38px;
            height: 38px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            font-size: 1rem;
        }

        .card-title { font-weight: 700; color: var(--dark); font-size: 0.95rem; }
        .card-subtitle { font-size: 0.7rem; color: var(--gray-600); margin-top: 0.2rem; }
        body.dark-mode .card-title { color: #e5e7eb; }
        body.dark-mode .card-subtitle { color: #94a3b8; }

        .card-body { padding: 1.2rem 1.4rem; }

        /* Info rows */
        .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 1.2rem; }
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid var(--gray-200);
            gap: 1rem;
        }
        .info-row:last-child { border-bottom: none; padding-bottom: 0; }
        .ir-label { font-size: 0.7rem; font-weight: 600; color: var(--gray-600); text-transform: uppercase; }
        .ir-value { font-size: 0.85rem; font-weight: 600; color: var(--dark); text-align: right; }
        .ir-empty { color: #cbd5e1; font-weight: 400; }
        .ir-link { color: var(--primary); text-decoration: none; }
        .ir-link:hover { text-decoration: underline; }
        body.dark-mode .ir-value { color: #cbd5e1; }
        body.dark-mode .ir-empty { color: #64748b; }

        /* Photo */
        .photo-wrap { text-align: center; padding: 0.5rem; }
        .photo-wrap img {
            max-width: 100%;
            max-height: 260px;
            border-radius: 12px;
            border: 2px solid var(--gray-200);
        }
        .photo-empty {
            background: var(--gray-100);
            border: 2px dashed var(--gray-200);
            border-radius: 12px;
            padding: 2rem;
            text-align: center;
            color: var(--gray-600);
        }
        body.dark-mode .photo-empty {
            background: #1e293b;
            border-color: #334155;
        }

        /* Correspondances */
        .corr-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.8rem 1rem;
            border: 1.5px solid var(--gray-200);
            border-radius: 12px;
            margin-bottom: 0.7rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        body.dark-mode .corr-item { border-color: #334155; }
        .corr-item:hover { border-color: var(--primary); background: rgba(243,156,18,0.05); }
        .corr-name { font-weight: 700; color: var(--dark); font-size: 0.85rem; }
        .corr-detail { font-size: 0.7rem; color: var(--gray-600); margin-top: 0.2rem; }
        .corr-tag {
            background: #fef3c7;
            color: #d97706;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 0.2rem 0.6rem;
            border-radius: 50px;
            white-space: nowrap;
        }
        body.dark-mode .corr-tag { background: #422d0b; color: #fbbf24; }

        /* Actions card */
        .actions-card {
            background: linear-gradient(135deg, var(--dark), #1e293b);
            border-radius: 20px;
            overflow: hidden;
            margin-bottom: 1.5rem;
            box-shadow: 0 8px 20px rgba(0,0,0,0.2);
        }
        body.dark-mode .actions-card { background: linear-gradient(135deg, #0f172a, #1e293b); }

        .ac-head {
            padding: 1rem 1.4rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }
        .ac-head-dot {
            width: 8px; height: 8px;
            border-radius: 50%;
            background: var(--primary);
            animation: pulse 1.8s infinite;
        }
        .ac-head-label { font-size: 0.65rem; font-weight: 700; text-transform: uppercase; color: rgba(255,255,255,0.4); letter-spacing: 1px; }
        .ac-body { padding: 1.2rem; }

        .ac-btn {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            width: 100%;
            padding: 0.8rem 1rem;
            border-radius: 12px;
            border: none;
            font-size: 0.85rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            text-align: left;
            margin-bottom: 0.7rem;
            text-decoration: none;
        }
        .ac-btn:last-child { margin-bottom: 0; }
        .ac-btn svg { width: 18px; height: 18px; flex-shrink: 0; }

        .ac-btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            box-shadow: 0 4px 12px rgba(243,156,18,0.3);
        }
        .ac-btn-primary:hover { transform: translateY(-2px); box-shadow: 0 6px 16px rgba(243,156,18,0.4); }

        .ac-btn-success {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
        }
        .ac-btn-success:hover { transform: translateY(-2px); }

        .ac-btn-ghost {
            background: rgba(255,255,255,0.08);
            color: rgba(255,255,255,0.7);
            border: 1px solid rgba(255,255,255,0.12);
        }
        .ac-btn-ghost:hover { background: rgba(255,255,255,0.12); color: white; }

        .ac-btn-disabled {
            background: rgba(255,255,255,0.03);
            color: rgba(255,255,255,0.25);
            cursor: not-allowed;
            border: 1px dashed rgba(255,255,255,0.1);
        }

        .ac-btn-label { flex: 1; }
        .ac-btn-desc { font-size: 0.65rem; display: block; opacity: 0.7; margin-top: 0.1rem; }

        .ac-divider { border-top: 1px solid rgba(255,255,255,0.08); margin: 0.8rem 0; }

        .ac-corr-hint { font-size: 0.7rem; text-align: center; color: rgba(255,255,255,0.4); margin-top: 0.8rem; }

        /* Timeline */
        .tl { display: flex; flex-direction: column; gap: 1rem; }
        .tl-item {
            display: flex;
            gap: 1rem;
            position: relative;
        }
        .tl-dot {
            width: 30px; height: 30px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            background: var(--gray-100);
        }
        .tl-done { background: #d1fae5; color: #065f46; }
        .tl-pending { background: #e2e8f0; color: #64748b; opacity: 0.6; }
        body.dark-mode .tl-done { background: #0a3b2a; color: #34d399; }
        body.dark-mode .tl-pending { background: #334155; color: #94a3b8; }
        .tl-label { font-weight: 700; color: var(--dark); font-size: 0.85rem; }
        .tl-date { font-size: 0.7rem; color: var(--gray-600); margin-top: 0.2rem; }
        body.dark-mode .tl-label { color: #e5e7eb; }

        /* Modal */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(5px);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }
        .modal-overlay.open { display: flex; }
        .modal {
            background: white;
            border-radius: 20px;
            width: 100%;
            max-width: 560px;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
            animation: modalIn 0.2s;
        }
        body.dark-mode .modal { background: #1e293b; }
        @keyframes modalIn {
            from { opacity: 0; transform: scale(0.95) translateY(20px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }
        .modal-top {
            padding: 1.2rem 1.5rem;
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 1rem;
            position: sticky;
            top: 0;
            background: white;
            z-index: 2;
        }
        body.dark-mode .modal-top { background: #1e293b; border-bottom-color: #334155; }
        .modal-top-icon {
            width: 44px; height: 44px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: white;
            flex-shrink: 0;
        }
        .modal-title { font-size: 1.1rem; font-weight: 800; color: var(--dark); }
        .modal-subtitle { font-size: 0.75rem; color: var(--gray-600); margin-top: 0.2rem; line-height: 1.5; }
        .modal-close-btn {
            width: 32px; height: 32px;
            background: var(--gray-100);
            border-radius: 8px;
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--gray-600);
        }
        body.dark-mode .modal-close-btn { background: #334155; color: #94a3b8; }
        .modal-body { padding: 1.2rem 1.5rem; }
        .notif-preview {
            background: #eff6ff;
            border: 1px solid #bfdbfe;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1.2rem;
        }
        body.dark-mode .notif-preview { background: #1e3a5f; border-color: #3b82f6; }
        .np-header { display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.6rem; }
        .np-header-label { font-size: 0.65rem; font-weight: 700; color: #3b82f6; text-transform: uppercase; }
        .np-title { font-weight: 700; color: var(--dark); font-size: 0.85rem; }
        .np-text { font-size: 0.75rem; color: #475569; line-height: 1.5; margin-top: 0.3rem; }
        body.dark-mode .np-title { color: #e5e7eb; }
        body.dark-mode .np-text { color: #94a3b8; }

        .perte-section-label { font-size: 0.7rem; font-weight: 700; color: var(--gray-600); text-transform: uppercase; margin: 0.5rem 0 0.5rem; }
        .perte-option {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 0.8rem 1rem;
            border: 2px solid var(--gray-200);
            border-radius: 12px;
            margin-bottom: 0.5rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        body.dark-mode .perte-option { border-color: #334155; }
        .perte-option:hover, .perte-option.checked { border-color: var(--primary); background: rgba(243,156,18,0.05); }
        .perte-option input[type="radio"] { accent-color: var(--primary); width: 16px; height: 16px; flex-shrink: 0; }
        .po-name { font-weight: 700; color: var(--dark); font-size: 0.85rem; }
        .po-detail { font-size: 0.7rem; color: var(--gray-600); margin-top: 0.1rem; }

        .manual-input-wrap { margin-top: 1rem; padding-top: 1rem; border-top: 1px solid var(--gray-200); }
        .manual-label { font-size: 0.75rem; font-weight: 600; color: var(--gray-800); margin-bottom: 0.3rem; }
        .manual-input { width: 100%; padding: 0.6rem 0.8rem; border: 2px solid var(--gray-200); border-radius: 10px; background: white; }
        body.dark-mode .manual-input { background: #334155; border-color: #4b5563; color: #e5e7eb; }
        .manual-hint { font-size: 0.65rem; color: var(--gray-600); margin-top: 0.2rem; }

        .modal-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid var(--gray-200);
            display: flex;
            gap: 0.8rem;
            background: var(--gray-100);
            border-radius: 0 0 20px 20px;
        }
        body.dark-mode .modal-footer { background: #334155; border-top-color: #4b5563; }
        .btn-confirm {
            flex: 1;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            border: none;
            padding: 0.7rem;
            border-radius: 10px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        .btn-cancel {
            padding: 0.7rem 1.2rem;
            background: white;
            border: 1px solid var(--gray-200);
            border-radius: 10px;
            color: var(--gray-600);
            font-weight: 600;
            cursor: pointer;
        }
        body.dark-mode .btn-cancel { background: #1e293b; border-color: #4b5563; color: #94a3b8; }

        /* Empty state */
        .empty-state { text-align: center; padding: 2rem; color: var(--gray-600); }
        .es-icon { font-size: 2rem; margin-bottom: 0.5rem; }

        /* Responsive */
        @media (max-width: 1024px) {
            .main-grid { grid-template-columns: 1fr; }
            .stats-row { grid-template-columns: 1fr; }
            .two-col { grid-template-columns: 1fr; }
        }
        @media (max-width: 768px) {
            .sidebar { width: 100%; position: relative; height: auto; }
            .main { margin-left: 0; }
            .top-bar { flex-direction: column; align-items: flex-start; }
        }
    </style>
</head>
<body>

@php
    use App\Models\Perte;
    use App\Models\DocumentTrouve;
    use App\Models\Notification;
    
    // ✅ Compter les documents trouvés en attente (pour la sidebar)
    $pendingCount = DocumentTrouve::where('statut', 'en_attente')->count();
    
    // ✅ Compter les notifications non lues pour les documents trouvés (pour le badge)
    $unreadNotificationsCount = Notification::where('user_id', auth()->id())
        ->where('is_read', false)
        ->where('type', 'document_trouve')
        ->count();
    
    $statut = $documentTrouve->statut ?? 'en_attente';
    $sMap = [
        'en_attente' => ['label'=>'En attente',          'class'=>'sp-attente',  'icon'=>'⏳', 'pulse'=>true],
        'matche'     => ['label'=>'Propriétaire trouvé', 'class'=>'sp-matche',   'icon'=>'🔗', 'pulse'=>false],
        'restitue'   => ['label'=>'Restitué',             'class'=>'sp-restitue', 'icon'=>'✅', 'pulse'=>false],
        'archive'    => ['label'=>'Archivé',              'class'=>'sp-archive',  'icon'=>'📦', 'pulse'=>false],
    ];
    $s = $sMap[$statut] ?? $sMap['en_attente'];
    $docIcons = ['Passeport'=>'🛂',"Carte d'identité (CNI)"=>'🪪','Permis de conduire'=>'🚗',"Carte d'électeur"=>'🗳️','Acte de naissance'=>'📋','Certificat de nationalité'=>'📜'];
    $docIcon = $docIcons[$documentTrouve->type_document] ?? '📄';
    $correspCount = $pertesPotentielles->count();
@endphp

<!-- Sidebar -->
<div class="sidebar">
    <div class="sidebar-header">
        <h2>
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
            e-Déclaration TG
        </h2>
        <div class="republic-text">RÉPUBLIQUE TOGOLAISE</div>
        <div class="agent-badge"><i class="bi bi-shield-check"></i> AGENT</div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">PRINCIPAL</div>
        <a href="{{ route('agent.dashboard') }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a href="{{ route('agent.dashboard', ['statut' => 'en_attente']) }}">
            <i class="bi bi-hourglass-split"></i> En attente
            @if($pendingCount > 0)
                <span class="nav-badge">{{ $pendingCount }}</span>
            @endif
        </a>
        <a href="{{ route('agent.dashboard') }}">
            <i class="bi bi-files"></i> Toutes les pertes
        </a>

        <div class="nav-section">DOCUMENTS</div>
        <a href="{{ route('agent.documents-trouves.index') }}" class="active">
            <i class="bi bi-search-heart"></i> Documents trouvés
            @if($unreadNotificationsCount > 0)
                <span class="nav-badge" id="documentsTrouvesBadge">{{ $unreadNotificationsCount }}</span>
            @endif
        </a>

        <div class="nav-section">PARAMÈTRES</div>
        <a href="{{ route('agent.profile') }}">
            <i class="bi bi-person-gear"></i> Paramètres
        </a>
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

<!-- Main Content -->
<div class="main">
    <!-- Top Bar -->
    <div class="top-bar">
        <div class="top-bar-left">
            <h1><i class="bi bi-box-seam me-2" style="color: var(--primary);"></i>Document trouvé</h1>
            <p>{{ $documentTrouve->numero_declaration ?? 'DT-'.str_pad($documentTrouve->id,5,'0',STR_PAD_LEFT) }}</p>
        </div>
        <div class="top-bar-right">
            <div class="date-time" id="currentDateTime">
                {{ \Carbon\Carbon::now()->locale('fr')->isoFormat('dddd D MMMM YYYY - HH:mm') }}
            </div>
            <button class="icon-btn theme-toggle" id="themeToggleBtn" title="Changer le thème">
                <svg id="themeIcon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </button>
            <div class="icon-btn notification-btn" onclick="window.location.href='{{ route('agent.dashboard', ['statut' => 'en_attente']) }}'" title="Notifications">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                @if($pendingCount > 0)
                    <span class="notification-badge">{{ $pendingCount > 9 ? '9+' : $pendingCount }}</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Alertes -->
    @if(session('success'))
        <div class="alert alert-success"><i class="bi bi-check-circle-fill"></i> {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error"><i class="bi bi-exclamation-triangle-fill"></i> {{ session('error') }}</div>
    @endif

    <!-- ============================================================
    ✅ BOUTON "MARQUER COMME TRAITÉ" - PRISE EN CHARGE
    ============================================================ -->
    <div class="action-banner">
        <div>
            <p class="ab-title">
                <i class="bi bi-check2-circle" style="color: #006A36;"></i>
                📬 Document trouvé — Prise en charge
            </p>
            <p class="ab-text">
                Cliquez sur "Accepter" pour confirmer que vous avez pris connaissance de ce document et faire disparaître la notification.
            </p>
        </div>
        <div style="display:flex;gap:0.75rem;flex-wrap:wrap;">

            {{-- ✅ Bouton Accepter / Marquer comme lu --}}
            @if(isset($notification) && $notification && !$notification->is_read)
                <form method="POST"
                      action="{{ route('notifications.read', $notification->id) }}"
                      style="display:inline;"
                      onsubmit="return confirm('Confirmer la prise en charge de ce document trouvé ?')">
                    @csrf
                    <button type="submit" class="btn-accept" onclick="setTimeout(window.updateNotificationBadge, 500)">
                        <i class="bi bi-check2-circle"></i> Accepter & Marquer comme lu
                    </button>
                </form>
            @else
                <button class="btn-accept" style="opacity:0.5;cursor:not-allowed;" disabled>
                    <i class="bi bi-check2-circle"></i> Aucune notification non lue
                </button>
            @endif

            {{-- Bouton Restituer si déjà matché --}}
            @if($documentTrouve->statut === 'matche' || $documentTrouve->statut === 'en_attente_restitution')
            <form method="POST"
                  action="{{ route('agent.documents-trouves.restituer', $documentTrouve->id) }}"
                  style="display:inline;"
                  onsubmit="return confirm('Confirmer la restitution du document au propriétaire ?')">
                @csrf
                <button type="submit" class="btn-restituer-banner">
                    <i class="bi bi-gift"></i> 🎉 Confirmer la restitution
                </button>
            </form>
            @endif

        </div>
    </div>

    <!-- ============================================================
    📬 NOTIFICATION - MARQUER COMME LU (EXISTANT)
    ============================================================ -->
    @if(isset($notification) && $notification && !$notification->is_read)
    <div class="notification-banner">
        <div>
            <p class="nb-title">
                <i class="bi bi-bell-fill" style="color: #006A36;"></i>
                📬 Vous avez une notification non lue pour ce document
            </p>
            <p class="nb-text">
                Marquez-la comme lue une fois que vous avez pris connaissance.
            </p>
        </div>
        <form method="POST" action="{{ route('notifications.read', $notification->id) }}" style="flex-shrink: 0;">
            @csrf
            <button type="submit" class="btn-mark-read" onclick="setTimeout(window.updateNotificationBadge, 500)">
                <i class="bi bi-check2-circle"></i> Marquer comme lu
            </button>
        </form>
    </div>
    @endif

    <!-- Banner matché -->
    @if($statut === 'matche' && $documentTrouve->perteMatchee)
    <div class="matched-banner">
        <div class="mb-icon">🎉</div>
        <div>
            <div class="mb-title">Document associé — en attente de restitution</div>
            <div class="mb-text">
                Rapproché de la déclaration de <strong>{{ $documentTrouve->perteMatchee->first_name }} {{ $documentTrouve->perteMatchee->last_name }}</strong>
                ({{ $documentTrouve->perteMatchee->type_piece }}, perdu le {{ \Carbon\Carbon::parse($documentTrouve->perteMatchee->date_perte)->format('d/m/Y') }}).
                Le propriétaire a été notifié.
            </div>
        </div>
    </div>
    @endif

    <!-- Page Header -->
    <div class="page-header">
        <h1>
            {{ $docIcon }} {{ $documentTrouve->type_document }}
            @if($documentTrouve->nom_sur_document)
                <small style="font-size:0.85rem; color:var(--gray-600);">au nom de {{ $documentTrouve->nom_sur_document }} {{ $documentTrouve->prenom_sur_document }}</small>
            @endif
        </h1>
        <a href="{{ route('agent.documents-trouves.index') }}" class="back-btn">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>

    <!-- Statut -->
    <div style="margin-bottom: 1.5rem;">
        <span class="statut-pill {{ $s['class'] }}">
            <span class="sp-dot {{ $s['pulse'] ? 'pulse' : '' }}"></span>
            {{ $s['icon'] }} {{ $s['label'] }}
        </span>
    </div>

    <!-- Statistiques rapides -->
    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg,#1e3a5f,#162544); color:white;"><i class="bi bi-calendar"></i></div>
            <div>
                <div class="stat-label">Date découverte</div>
                <div class="stat-value">{{ \Carbon\Carbon::parse($documentTrouve->date_decouverte)->format('d/m/Y') }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg,#f59e0b,#d97706); color:white;"><i class="bi bi-geo-alt"></i></div>
            <div>
                <div class="stat-label">Lieu découverte</div>
                <div class="stat-value" style="font-size:0.85rem;">{{ Str::limit($documentTrouve->lieu_decouverte, 25) }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon" style="background: linear-gradient(135deg,{{ $correspCount > 0 ? '#10b981,#059669' : '#94a3b8,#64748b' }}); color:white;"><i class="bi bi-link"></i></div>
            <div>
                <div class="stat-label">Correspondances</div>
                <div class="stat-value" style="color:{{ $correspCount > 0 ? '#10b981' : '#64748b' }};">{{ $correspCount }}</div>
            </div>
        </div>
    </div>

    <!-- Grid principal -->
    <div class="main-grid">

        <!-- Colonne gauche -->
        <div>

            <!-- Informations document -->
            <div class="card">
                <div class="card-head">
                    <div class="card-head-icon" style="background: linear-gradient(135deg, var(--primary), var(--primary-dark)); color:white;"><i class="bi bi-card-id"></i></div>
                    <div>
                        <div class="card-title">Informations du document</div>
                        <div class="card-subtitle">Données extraites du document trouvé</div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="two-col">
                        <div>
                            <div class="info-row"><span class="ir-label">Type</span><span class="ir-value">{{ $documentTrouve->type_document }}</span></div>
                            <div class="info-row"><span class="ir-label">Numéro</span><span class="ir-value {{ !$documentTrouve->numero_document ? 'ir-empty' : '' }}">{{ $documentTrouve->numero_document ?? 'Non renseigné' }}</span></div>
                        </div>
                        <div>
                            <div class="info-row"><span class="ir-label">Nom sur doc.</span><span class="ir-value {{ !$documentTrouve->nom_sur_document ? 'ir-empty' : '' }}">{{ $documentTrouve->nom_sur_document ?? 'Non lisible' }}</span></div>
                            <div class="info-row"><span class="ir-label">Prénom sur doc.</span><span class="ir-value {{ !$documentTrouve->prenom_sur_document ? 'ir-empty' : '' }}">{{ $documentTrouve->prenom_sur_document ?? 'Non lisible' }}</span></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Déclarant -->
            <div class="card">
                <div class="card-head">
                    <div class="card-head-icon" style="background: linear-gradient(135deg,#10b981,#059669); color:white;"><i class="bi bi-person"></i></div>
                    <div>
                        <div class="card-title">Déclarant (personne qui a trouvé)</div>
                        <div class="card-subtitle">Coordonnées confidentielles</div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="two-col">
                        <div>
                            <div class="info-row"><span class="ir-label">Nom</span><span class="ir-value">{{ $documentTrouve->nom_declarant }}</span></div>
                            <div class="info-row"><span class="ir-label">Prénom</span><span class="ir-value">{{ $documentTrouve->prenom_declarant }}</span></div>
                            <div class="info-row"><span class="ir-label">Compte</span><span class="ir-value">{{ $documentTrouve->user_id ? '✅ Enregistré' : 'Anonyme' }}</span></div>
                        </div>
                        <div>
                            <div class="info-row"><span class="ir-label">Email</span><span class="ir-value"><a href="mailto:{{ $documentTrouve->email_declarant }}" class="ir-link">{{ $documentTrouve->email_declarant }}</a></span></div>
                            <div class="info-row"><span class="ir-label">Téléphone</span><span class="ir-value"><a href="tel:{{ $documentTrouve->telephone_declarant }}" class="ir-link">{{ $documentTrouve->telephone_declarant }}</a></span></div>
                            <div class="info-row"><span class="ir-label">Déclaré le</span><span class="ir-value">{{ \Carbon\Carbon::parse($documentTrouve->created_at)->format('d/m/Y H:i') }}</span></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Circonstances -->
            @if($documentTrouve->description || $documentTrouve->circonstances)
            <div class="card">
                <div class="card-head">
                    <div class="card-head-icon" style="background: linear-gradient(135deg,#f59e0b,#d97706); color:white;"><i class="bi bi-journal-text"></i></div>
                    <div>
                        <div class="card-title">Description & Circonstances</div>
                    </div>
                </div>
                <div class="card-body">
                    @if($documentTrouve->description)
                        <div class="info-row full-width" style="flex-direction:column; align-items:flex-start; gap:0.3rem;">
                            <span class="ir-label">État du document</span>
                            <div class="ir-value" style="font-weight:400; text-align:left;">{{ $documentTrouve->description }}</div>
                        </div>
                    @endif
                    @if($documentTrouve->circonstances)
                        <div class="info-row full-width" style="flex-direction:column; align-items:flex-start; gap:0.3rem;">
                            <span class="ir-label">Circonstances de la découverte</span>
                            <div class="ir-value" style="font-weight:400; text-align:left;">{{ $documentTrouve->circonstances }}</div>
                        </div>
                    @endif
                </div>
            </div>
            @endif

            <!-- Photo -->
            <div class="card">
                <div class="card-head">
                    <div class="card-head-icon" style="background: linear-gradient(135deg,#8b5cf6,#7c3aed); color:white;"><i class="bi bi-image"></i></div>
                    <div>
                        <div class="card-title">Photo du document</div>
                        <div class="card-subtitle">Téléchargée par le déclarant</div>
                    </div>
                </div>
                <div class="card-body">
                    @if($documentTrouve->photo_document)
                        @php $ext = strtolower(pathinfo($documentTrouve->photo_document, PATHINFO_EXTENSION)); @endphp
                        @if(in_array($ext, ['jpg','jpeg','png','gif','webp']))
                            <div class="photo-wrap">
                                <img src="{{ Storage::url($documentTrouve->photo_document) }}" alt="Photo du document">
                            </div>
                            <div style="text-align:center; margin-top:0.8rem;">
                                <a href="{{ Storage::url($documentTrouve->photo_document) }}" target="_blank" class="ir-link"><i class="bi bi-zoom-in"></i> Voir en taille réelle</a>
                            </div>
                        @else
                            <div class="photo-empty">
                                <i class="bi bi-file-pdf" style="font-size:2rem;"></i>
                                <div><a href="{{ Storage::url($documentTrouve->photo_document) }}" target="_blank" class="ir-link">Ouvrir le PDF</a></div>
                            </div>
                        @endif
                    @else
                        <div class="photo-empty">
                            <i class="bi bi-camera-off" style="font-size:2rem;"></i>
                            <div>Aucune photo fournie</div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Correspondances -->
            <div class="card">
                <div class="card-head">
                    <div class="card-head-icon" style="background: linear-gradient(135deg,#ef4444,#dc2626); color:white;"><i class="bi bi-search-heart"></i></div>
                    <div>
                        <div class="card-title">Correspondances potentielles</div>
                        <div class="card-subtitle">Déclarations de perte similaires</div>
                    </div>
                </div>
                <div class="card-body">
                    @if($correspCount > 0)
                        @foreach($pertesPotentielles as $perte)
                        <div class="corr-item" id="corr-{{ $perte->id }}" onclick="preselectPerte({{ $perte->id }}, '{{ addslashes($perte->first_name) }} {{ addslashes($perte->last_name) }}')">
                            <div>
                                <div class="corr-name">{{ $perte->first_name }} {{ $perte->last_name }}</div>
                                <div class="corr-detail">
                                    {{ $perte->type_piece }} • Perdu le {{ \Carbon\Carbon::parse($perte->date_perte)->format('d/m/Y') }}
                                    @if($perte->lieu_perte) • {{ $perte->lieu_perte }} @endif
                                    @if($perte->numero_piece) • N° {{ $perte->numero_piece }} @endif
                                </div>
                            </div>
                            <span class="corr-tag">Cliquer</span>
                        </div>
                        @endforeach
                        <p class="mt-2" style="font-size:0.7rem; color:var(--gray-600); margin-top:0.8rem;">Cliquez sur une ligne pour l'associer directement.</p>
                    @else
                        <div class="empty-state">
                            <div class="es-icon"><i class="bi bi-search"></i></div>
                            <div>Aucune déclaration de perte correspondante trouvée.</div>
                            <div style="font-size:0.75rem;">Vous pouvez tout de même associer manuellement via l'ID de déclaration.</div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Colonne droite - Actions & Timeline -->
        <div>

            <!-- Actions Agent -->
            <div class="actions-card">
                <div class="ac-head">
                    <div class="ac-head-dot"></div>
                    <div class="ac-head-label">Actions Agent</div>
                </div>
                <div class="ac-body">
                    @if($statut === 'en_attente')
                        <button class="ac-btn ac-btn-primary" onclick="openMatchModal()">
                            <i class="bi bi-link-45deg"></i>
                            <div class="ac-btn-label">
                                Valider & Associer
                                <span class="ac-btn-desc">Envoie une notification au propriétaire</span>
                            </div>
                        </button>
                    @elseif($statut === 'matche')
                        <div class="ac-btn ac-btn-disabled">
                            <i class="bi bi-check2-circle"></i>
                            <div class="ac-btn-label">Déjà associé <span class="ac-btn-desc">Propriétaire notifié</span></div>
                        </div>
                    @else
                        <div class="ac-btn ac-btn-disabled">
                            <i class="bi bi-x-circle"></i>
                            <div class="ac-btn-label">Association indisponible <span class="ac-btn-desc">Statut {{ $statut }}</span></div>
                        </div>
                    @endif

                    <hr class="ac-divider">

                    @if($statut === 'matche')
                        <form method="POST" action="{{ route('agent.documents-trouves.restituer', $documentTrouve->id) }}" onsubmit="return confirmRestitution(event)">
                            @csrf
                            <button type="submit" class="ac-btn ac-btn-success">
                                <i class="bi bi-check2-all"></i>
                                <div class="ac-btn-label">Marquer comme restitué <span class="ac-btn-desc">Clôture le dossier</span></div>
                            </button>
                        </form>
                    @elseif($statut === 'restitue')
                        <div class="ac-btn ac-btn-disabled">
                            <i class="bi bi-check2-all"></i>
                            <div class="ac-btn-label">Dossier clôturé <span class="ac-btn-desc">Document restitué</span></div>
                        </div>
                    @else
                        <div class="ac-btn ac-btn-disabled">
                            <i class="bi bi-check2-all"></i>
                            <div class="ac-btn-label">Restituer <span class="ac-btn-desc">Associer d'abord</span></div>
                        </div>
                    @endif

                    <hr class="ac-divider">

                    @if($statut === 'en_attente')
                        <form method="POST" action="{{ route('agent.documents-trouves.destroy', $documentTrouve->id) }}" onsubmit="return confirm('Supprimer ce dossier ? Action irréversible.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="ac-btn ac-btn-ghost">
                                <i class="bi bi-trash3"></i>
                                <div class="ac-btn-label">Supprimer le dossier <span class="ac-btn-desc">Retirer des listes actives</span></div>
                            </button>
                        </form>
                    @endif

                    @if($correspCount > 0)
                        <div class="ac-corr-hint"><i class="bi bi-info-circle"></i> {{ $correspCount }} correspondance(s) détectée(s)</div>
                    @endif
                </div>
            </div>

            <!-- Timeline -->
            <div class="card">
                <div class="card-head">
                    <div class="card-head-icon" style="background: linear-gradient(135deg,#64748b,#475569); color:white;"><i class="bi bi-clock-history"></i></div>
                    <div class="card-title">Historique du dossier</div>
                </div>
                <div class="card-body">
                    <div class="tl">
                        <div class="tl-item">
                            <div class="tl-dot tl-done"><i class="bi bi-box-seam"></i></div>
                            <div>
                                <div class="tl-label">Document déclaré trouvé</div>
                                <div class="tl-date">{{ \Carbon\Carbon::parse($documentTrouve->created_at)->format('d/m/Y H:i') }}</div>
                            </div>
                        </div>
                        <div class="tl-item">
                            <div class="tl-dot {{ in_array($statut,['matche','restitue']) ? 'tl-done' : 'tl-pending' }}"><i class="bi bi-link-45deg"></i></div>
                            <div>
                                <div class="tl-label">Propriétaire identifié & notifié</div>
                                <div class="tl-date">
                                    @if(in_array($statut,['matche','restitue']))
                                        {{ \Carbon\Carbon::parse($documentTrouve->updated_at)->format('d/m/Y H:i') }}
                                    @else
                                        En attente
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="tl-item">
                            <div class="tl-dot {{ $statut === 'restitue' ? 'tl-done' : 'tl-pending' }}"><i class="bi bi-check2-circle"></i></div>
                            <div>
                                <div class="tl-label">Document restitué</div>
                                <div class="tl-date">
                                    @if($statut === 'restitue' && $documentTrouve->date_restitution)
                                        {{ \Carbon\Carbon::parse($documentTrouve->date_restitution)->format('d/m/Y H:i') }}
                                    @else
                                        En attente
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<!-- Modal Association -->
<div class="modal-overlay" id="matchModal">
    <div class="modal">
        <div class="modal-top">
            <div style="display:flex; align-items:flex-start; gap:0.8rem;">
                <div class="modal-top-icon"><i class="bi bi-link-45deg"></i></div>
                <div>
                    <div class="modal-title">Valider l'association</div>
                    <div class="modal-subtitle">Sélectionnez le propriétaire. Une notification automatique lui sera envoyée.</div>
                </div>
            </div>
            <button class="modal-close-btn" onclick="closeMatchModal()"><i class="bi bi-x-lg"></i></button>
        </div>
        <div class="modal-body">
            <div class="notif-preview">
                <div class="np-header">
                    <i class="bi bi-bell np-bell"></i>
                    <span class="np-header-label">Aperçu notification</span>
                </div>
                <div class="np-title">🎉 Votre document a peut-être été trouvé !</div>
                <div class="np-text">
                    Un <strong>{{ $documentTrouve->type_document }}</strong> correspondant à votre déclaration a été trouvé
                    le {{ \Carbon\Carbon::parse($documentTrouve->date_decouverte)->format('d/m/Y') }}
                    à {{ $documentTrouve->lieu_decouverte }}. Un agent va vérifier et vous contactera.
                </div>
            </div>

            <form id="matchForm" method="POST" action="{{ route('agent.documents-trouves.matcher', $documentTrouve->id) }}">
                @csrf
                <input type="hidden" name="confirmation" value="1">

                @if($correspCount > 0)
                    <div class="perte-section-label">Correspondances automatiques</div>
                    @foreach($pertesPotentielles as $perte)
                    <label class="perte-option" id="modal-opt-{{ $perte->id }}">
                        <input type="radio" name="perte_id" value="{{ $perte->id }}" onchange="highlightOption({{ $perte->id }})">
                        <div>
                            <div class="po-name">{{ $perte->first_name }} {{ $perte->last_name }}</div>
                            <div class="po-detail">
                                {{ $perte->type_piece }} • Perte le {{ \Carbon\Carbon::parse($perte->date_perte)->format('d/m/Y') }}
                                @if($perte->lieu_perte) • {{ $perte->lieu_perte }} @endif
                                @if($perte->numero_piece) • N° {{ $perte->numero_piece }} @endif
                            </div>
                        </div>
                    </label>
                    @endforeach
                @else
                    <div class="empty-state" style="padding:0.5rem 0;"><i class="bi bi-info-circle"></i> Aucune correspondance automatique</div>
                @endif

                <div class="manual-input-wrap">
                    <div class="manual-label">Ou saisir manuellement l'ID de la déclaration de perte :</div>
                    <input type="number" id="manualPerteId" placeholder="Ex: 42" class="manual-input" oninput="if(this.value) document.querySelectorAll('#matchForm input[name=perte_id]').forEach(r=>r.checked=false)">
                    <div class="manual-hint">Utilisé si aucune option n'est sélectionnée.</div>
                </div>
            </form>
        </div>
        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeMatchModal()">Annuler</button>
            <button class="btn-confirm" onclick="submitMatch()"><i class="bi bi-send"></i> Confirmer & Notifier</button>
        </div>
    </div>
</div>

<script>
    // Horloge temps réel
    function updateDateTime() {
        const now = new Date();
        const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' };
        const formatted = now.toLocaleDateString('fr-FR', options).replace(',', ' -');
        const el = document.getElementById('currentDateTime');
        if (el) el.innerHTML = formatted;
    }
    updateDateTime();
    setInterval(updateDateTime, 60000);

    // Mode sombre
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
        const saved = localStorage.getItem('darkMode');
        applyTheme(saved === 'dark');
    }

    function toggleGlobalDarkMode() {
        const isDark = !document.body.classList.contains('dark-mode');
        applyTheme(isDark);
        fetch('{{ route("profile.toggle-dark-mode") }}', {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content, 'Content-Type': 'application/json' },
            body: JSON.stringify({ dark_mode: isDark })
        }).catch(() => {});
    }

    document.addEventListener('DOMContentLoaded', () => {
        loadTheme();
        const themeBtn = document.getElementById('themeToggleBtn');
        if (themeBtn) themeBtn.addEventListener('click', toggleGlobalDarkMode);
    });

    // ============================================================
    // ✅ MISE À JOUR DU BADGE DES NOTIFICATIONS
    // ============================================================
    function updateNotificationBadge() {
        fetch('{{ route("notifications.unread-count") }}', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            const badge = document.getElementById('documentsTrouvesBadge');
            if (badge) {
                if (data.count > 0) {
                    badge.textContent = data.count;
                    badge.style.display = 'flex';
                } else {
                    badge.style.display = 'none';
                }
            }
        })
        .catch(() => {});
    }

    // Exposer la fonction globalement
    window.updateNotificationBadge = updateNotificationBadge;

    // Modal
    function openMatchModal() { document.getElementById('matchModal').classList.add('open'); document.body.style.overflow = 'hidden'; }
    function closeMatchModal() { document.getElementById('matchModal').classList.remove('open'); document.body.style.overflow = ''; }
    document.getElementById('matchModal')?.addEventListener('click', e => { if (e.target === document.getElementById('matchModal')) closeMatchModal(); });

    function preselectPerte(id, name) {
        openMatchModal();
        setTimeout(() => {
            const radio = document.querySelector(`#matchForm input[name="perte_id"][value="${id}"]`);
            if (radio) {
                radio.checked = true;
                highlightOption(id);
                radio.closest('.perte-option')?.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        }, 120);
    }

    function highlightOption(id) {
        document.querySelectorAll('#matchForm .perte-option').forEach(el => el.classList.remove('checked'));
        const opt = document.getElementById(`modal-opt-${id}`);
        if (opt) opt.classList.add('checked');
    }

    function submitMatch() {
        const radio = document.querySelector('#matchForm input[name="perte_id"]:checked');
        const manual = document.getElementById('manualPerteId').value.trim();
        if (!radio && !manual) { alert('Sélectionnez une correspondance ou saisissez un ID.'); return; }
        if (manual && !radio) {
            let hidden = document.querySelector('#matchForm input[name="perte_id"][type="hidden"]');
            if (hidden) hidden.remove();
            hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'perte_id';
            hidden.value = manual;
            document.getElementById('matchForm').appendChild(hidden);
        }
        let name = radio ? radio.closest('.perte-option')?.querySelector('.po-name')?.innerText : `ID ${manual}`;
        if (confirm(`Confirmer l'association et envoyer la notification à : ${name} ?`)) {
            document.getElementById('matchForm').submit();
        }
    }

    function confirmRestitution(e) {
        e.preventDefault();
        if (confirm('Confirmer la restitution physique du document ? Le dossier sera clôturé.')) {
            e.target.submit();
        }
        return false;
    }
</script>
</body>
</html>