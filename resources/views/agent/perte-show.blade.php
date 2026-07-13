<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dossier Perte {{ $perte->numero_declaration ?? '' }} | Agent e-Déclaration TG</title>
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
        /* ===== TOUS LES STYLES EXISTANTS ===== */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        :root {
            --primary: #f39c12;
            --primary-dark: #e67e22;
            --primary-light: #f1c40f;
            --secondary: #3498db;
            --success: #27ae60;
            --danger: #e74c3c;
            --warning: #f39c12;
            --info: #3b82f6;
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

        /* ===== SIDEBAR ===== */
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
            margin-bottom: 0.2rem;
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
            letter-spacing: 0.5px;
        }

        .nav-section {
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            color: var(--gray-600);
            padding: 1rem 1.5rem 0.5rem 1.5rem;
            margin-top: 0.5rem;
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
            position: relative;
        }

        body.dark-mode .sidebar-nav a {
            color: #9ca3af;
        }

        .sidebar-nav a i {
            width: 20px;
            height: 20px;
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
            font-size: 1rem;
            transition: all 0.2s;
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

        /* Top Bar */
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
            transition: background 0.2s, color 0.2s;
        }

        body.dark-mode .alert {
            background: #1e293b;
            color: #e5e7eb;
        }

        .alert-success { border-left-color: var(--success); }
        .alert-error { border-left-color: var(--danger); }
        .alert-warning { border-left-color: var(--warning); }
        .alert-info { border-left-color: var(--info); }

        /* Page Header */
        .page-header {
            background: white;
            padding: 1.5rem 2rem;
            border-radius: 20px;
            margin-bottom: 2rem;
            border: 1px solid var(--gray-200);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
            transition: background 0.2s;
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
            font-size: 0.9rem;
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

        /* Status Banner */
        .status-banner {
            padding: 1.5rem 2rem;
            border-radius: 20px;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            font-weight: 600;
            border: 1px solid transparent;
        }

        .status-banner.pending {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            color: #92400e;
            border-color: #f59e0b;
        }

        .status-banner.pending-verification {
            background: linear-gradient(135deg, #fef3c7, #fbbf24);
            color: #78350f;
            border-color: #d97706;
        }

        .status-banner.in-progress {
            background: linear-gradient(135deg, #dbeafe, #bfdbfe);
            color: #1e3a8a;
            border-color: #3b82f6;
        }

        .status-banner.matched {
            background: linear-gradient(135deg, #c7d2fe, #a5b4fc);
            color: #3730a3;
            border-color: #8b5cf6;
        }

        .status-banner.returned {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            color: #065f46;
            border-color: #10b981;
        }

        .status-banner.not-found {
            background: linear-gradient(135deg, #e5e7eb, #d1d5db);
            color: #374151;
            border-color: #6b7280;
        }

        .status-banner.approved {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            color: #065f46;
            border-color: #10b981;
        }

        .status-banner.rejected {
            background: linear-gradient(135deg, #fee2e2, #fecaca);
            color: #991b1b;
            border-color: #ef4444;
        }

        body.dark-mode .status-banner.pending {
            background: #422d0b;
            color: #fbbf24;
        }
        body.dark-mode .status-banner.pending-verification {
            background: #4a3000;
            color: #fcd34d;
        }
        body.dark-mode .status-banner.in-progress {
            background: #1e3a5f;
            color: #60a5fa;
        }
        body.dark-mode .status-banner.matched {
            background: #2e2b5c;
            color: #a78bfa;
        }
        body.dark-mode .status-banner.returned,
        body.dark-mode .status-banner.approved {
            background: #0a3b2a;
            color: #34d399;
        }
        body.dark-mode .status-banner.not-found {
            background: #1f2937;
            color: #9ca3af;
        }
        body.dark-mode .status-banner.rejected {
            background: #3f1e1e;
            color: #f87171;
        }

        .status-icon {
            font-size: 2rem;
        }

        /* ✅ NOUVEAU : Alertes de vérification */
        .verification-alert {
            background: #fef3c7;
            border-left: 4px solid #f59e0b;
            border-radius: 12px;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
        }

        body.dark-mode .verification-alert {
            background: #422d0b;
        }

        .verification-alert .icon {
            font-size: 1.5rem;
            flex-shrink: 0;
        }

        .verification-alert .content {
            flex: 1;
        }

        .verification-alert .title {
            font-weight: 700;
            color: #92400e;
            margin-bottom: 0.3rem;
        }

        body.dark-mode .verification-alert .title {
            color: #fbbf24;
        }

        .verification-alert .text {
            color: #78350f;
            font-size: 0.9rem;
        }

        body.dark-mode .verification-alert .text {
            color: #fcd34d;
        }

        /* Content Grid */
        .content-grid {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 2rem;
            align-items: start;
        }

        /* Card */
        .card {
            background: white;
            border-radius: 20px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            border: 1px solid var(--gray-200);
            transition: background 0.2s, border-color 0.2s;
        }

        body.dark-mode .card {
            background: #1e293b;
            border-color: #334155;
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.2rem;
            padding-bottom: 0.8rem;
            border-bottom: 2px solid var(--gray-200);
        }

        body.dark-mode .card-header {
            border-bottom-color: #334155;
        }

        .card-icon {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
            color: white;
        }

        .card-title {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--dark);
        }

        body.dark-mode .card-title {
            color: #e5e7eb;
        }

        .info-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1.2rem;
        }

        .info-item {
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
        }

        .info-item.full-width {
            grid-column: 1 / -1;
        }

        .info-label {
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            color: var(--gray-600);
            letter-spacing: 0.5px;
        }

        body.dark-mode .info-label {
            color: #94a3b8;
        }

        .info-value {
            font-size: 0.9rem;
            font-weight: 700;
            color: var(--dark);
            word-break: break-word;
        }

        body.dark-mode .info-value {
            color: #e5e7eb;
        }

        .info-value .badge {
            display: inline-block;
            padding: 0.2rem 0.6rem;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 600;
        }

        .badge-success { background: #d1fae5; color: #065f46; }
        .badge-warning { background: #fef3c7; color: #92400e; }
        .badge-danger { background: #fee2e2; color: #991b1b; }
        .badge-info { background: #dbeafe; color: #1e3a8a; }

        /* Citizen Card */
        .citizen-card {
            background: linear-gradient(135deg, #667eea, #764ba2);
            border-radius: 20px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            color: white;
        }

        body.dark-mode .citizen-card {
            background: linear-gradient(135deg, #4c51bf, #6b46c1);
        }

        .citizen-avatar {
            width: 70px;
            height: 70px;
            border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.8rem;
            font-weight: 800;
            margin: 0 auto 1rem;
            border: 3px solid rgba(255,255,255,0.3);
        }

        .citizen-name {
            font-size: 1.2rem;
            font-weight: 800;
            text-align: center;
            margin-bottom: 0.3rem;
        }

        .citizen-email {
            text-align: center;
            opacity: 0.9;
            font-size: 0.85rem;
        }

        .citizen-info {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid rgba(255,255,255,0.2);
        }

        .citizen-info-item {
            display: flex;
            justify-content: space-between;
            padding: 0.6rem 0;
            font-size: 0.85rem;
        }

        /* ============================================================
        ACTION SECTION AVEC VERROUILLAGE
        ============================================================ */
        .action-section {
            background: var(--gray-100);
            border-radius: 20px;
            padding: 1.5rem;
            transition: background 0.2s;
        }

        body.dark-mode .action-section {
            background: #334155;
        }

        .action-title {
            font-size: 1rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 1.2rem;
        }

        body.dark-mode .action-title {
            color: #e5e7eb;
        }

        /* Bloc de verrouillage - DOSSIER PRIS PAR UN AUTRE AGENT */
        .locked-block {
            background: #FEF2F2;
            border: 2px solid #D21034;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1.2rem;
        }

        body.dark-mode .locked-block {
            background: #3f1e1e;
            border-color: #ef4444;
        }

        .locked-block .locked-title {
            color: #D21034;
            font-weight: 700;
            font-size: 0.95rem;
            margin: 0;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        body.dark-mode .locked-block .locked-title {
            color: #f87171;
        }

        .locked-block .locked-text {
            color: #7F1D1D;
            font-size: 0.85rem;
            margin-top: 0.3rem;
        }

        body.dark-mode .locked-block .locked-text {
            color: #fca5a5;
        }

        .btn {
            width: 100%;
            padding: 0.9rem 1.2rem;
            border-radius: 12px;
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            border: none;
            margin-bottom: 0.8rem;
            text-decoration: none;
        }

        .btn-approve {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            color: white;
            box-shadow: 0 4px 12px rgba(39,174,96,0.3);
        }

        .btn-approve:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(39,174,96,0.4);
        }

        .btn-reject {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            box-shadow: 0 4px 12px rgba(231,76,60,0.3);
        }

        .btn-reject:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(231,76,60,0.4);
        }

        .btn-notfound {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            box-shadow: 0 4px 12px rgba(245,158,11,0.3);
        }

        .btn-notfound:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(245,158,11,0.4);
        }

        .btn-verify {
            background: linear-gradient(135deg, #3b82f6, #2563eb);
            color: white;
            box-shadow: 0 4px 12px rgba(59,130,246,0.3);
        }

        .btn-verify:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(59,130,246,0.4);
        }

        .btn-secondary {
            background: white;
            color: var(--gray-600);
            border: 1px solid var(--gray-200);
            box-shadow: none;
        }

        body.dark-mode .btn-secondary {
            background: #1e293b;
            border-color: #4b5563;
            color: #94a3b8;
        }

        .btn-secondary:hover {
            background: var(--gray-100);
            border-color: var(--primary);
            color: var(--primary);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(243,156,18,0.4);
        }

        .btn-disabled {
            background: #e5e7eb;
            color: #6b7280;
            cursor: not-allowed;
            opacity: 0.7;
            pointer-events: none;
        }

        body.dark-mode .btn-disabled {
            background: #374151;
            color: #6b7280;
        }

        /* Rejection box */
        .rejection-box {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 12px;
            padding: 1rem;
            margin-top: 0.5rem;
        }

        body.dark-mode .rejection-box {
            background: #422d0b;
            border-color: #fbbf24;
        }

        .rejection-box p {
            color: #92400e;
            font-size: 0.9rem;
            line-height: 1.6;
        }

        body.dark-mode .rejection-box p {
            color: #fbbf24;
        }

        /* Timeline */
        .timeline {
            position: relative;
            padding-left: 1.8rem;
        }

        .timeline::before {
            content: '';
            position: absolute;
            left: 0.4rem;
            top: 0;
            bottom: 0;
            width: 2px;
            background: var(--gray-200);
        }

        body.dark-mode .timeline::before {
            background: #334155;
        }

        .timeline-item {
            position: relative;
            margin-bottom: 1.2rem;
        }

        .timeline-item::before {
            content: '';
            position: absolute;
            left: -1.5rem;
            top: 0.2rem;
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: var(--primary);
            border: 2px solid white;
            box-shadow: 0 0 0 2px var(--primary);
        }

        .timeline-date {
            font-size: 0.75rem;
            color: var(--gray-600);
            margin-bottom: 0.2rem;
        }

        .timeline-text {
            font-weight: 700;
            color: var(--dark);
            font-size: 0.85rem;
        }

        body.dark-mode .timeline-text {
            color: #e5e7eb;
        }

        /* File chips */
        .file-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--gray-100);
            border: 1px solid var(--gray-200);
            border-radius: 8px;
            padding: 0.4rem 0.9rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-decoration: none;
            color: var(--primary);
            transition: all 0.2s;
            margin: 0.2rem;
        }

        body.dark-mode .file-chip {
            background: #334155;
            border-color: #4b5563;
            color: #fbbf24;
        }

        .file-chip:hover {
            background: var(--gray-200);
            transform: translateY(-2px);
        }

        /* ✅ NOUVEAU : Formulaire date d'expiration */
        .expiration-form {
            background: #f0f9ff;
            border: 1px solid #bae6fd;
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1rem;
        }

        body.dark-mode .expiration-form {
            background: #1e3a5f;
            border-color: #0c4a6e;
        }

        .expiration-form label {
            font-weight: 600;
            color: var(--dark);
            display: block;
            margin-bottom: 0.5rem;
        }

        body.dark-mode .expiration-form label {
            color: #e5e7eb;
        }

        .expiration-form input[type="date"] {
            width: 100%;
            padding: 0.7rem 1rem;
            border: 2px solid var(--gray-200);
            border-radius: 10px;
            font-size: 0.9rem;
            background: white;
            color: var(--dark);
            transition: border-color 0.2s;
        }

        body.dark-mode .expiration-form input[type="date"] {
            background: #334155;
            border-color: #4b5563;
            color: #e5e7eb;
        }

        .expiration-form input[type="date"]:focus {
            outline: none;
            border-color: var(--primary);
        }

        .expiration-form .btn {
            margin-top: 0.5rem;
            margin-bottom: 0;
        }

        /* Modal */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(4px);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .modal.active {
            display: flex;
            animation: fadeIn 0.2s;
        }

        .modal-content {
            background: white;
            border-radius: 24px;
            padding: 2rem;
            max-width: 500px;
            width: 100%;
            animation: slideUp 0.3s;
        }

        body.dark-mode .modal-content {
            background: #1e293b;
        }

        @keyframes slideUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .modal-title {
            font-size: 1.3rem;
            font-weight: 800;
            color: var(--dark);
        }

        body.dark-mode .modal-title {
            color: #e5e7eb;
        }

        .modal-close {
            background: var(--gray-100);
            border: none;
            width: 34px;
            height: 34px;
            border-radius: 8px;
            font-size: 1.2rem;
            cursor: pointer;
            color: var(--gray-600);
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        body.dark-mode .modal-close {
            background: #334155;
            color: #94a3b8;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 0.5rem;
        }

        body.dark-mode .form-label {
            color: #cbd5e1;
        }

        .form-textarea {
            width: 100%;
            padding: 0.9rem;
            border: 2px solid var(--gray-200);
            border-radius: 12px;
            font-size: 0.9rem;
            font-family: inherit;
            resize: vertical;
            background: white;
            color: var(--dark);
        }

        body.dark-mode .form-textarea {
            background: #334155;
            border-color: #4b5563;
            color: #e5e7eb;
        }

        .form-textarea:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(243,156,18,0.1);
        }

        .modal-actions {
            display: flex;
            gap: 1rem;
            margin-top: 1.5rem;
        }

        .btn-modal {
            flex: 1;
            padding: 0.8rem;
            border-radius: 12px;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            border: none;
        }

        .btn-modal-danger {
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
        }

        .btn-modal-success {
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            color: white;
        }

        .btn-modal-secondary {
            background: var(--gray-100);
            color: var(--gray-600);
        }

        body.dark-mode .btn-modal-secondary {
            background: #334155;
            color: #94a3b8;
        }

        /* Non-retrouvé banner (info pour l'agent) */
        .info-banner {
            background: var(--gray-100);
            border-left: 4px solid var(--warning);
            border-radius: 12px;
            padding: 1rem 1.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        body.dark-mode .info-banner {
            background: #1e293b;
        }

        .action-help {
            background: #f0f9ff;
            border-radius: 8px;
            padding: 0.8rem;
            font-size: 0.8rem;
            color: #1e3a5f;
            margin-top: 0.5rem;
            border: 1px solid #bae6fd;
        }

        body.dark-mode .action-help {
            background: #1e3a5f;
            color: #bae6fd;
            border-color: #0c4a6e;
        }

        /* Responsive */
        @media (max-width: 1024px) {
            .content-grid {
                grid-template-columns: 1fr;
            }
            .info-row {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                overflow: hidden;
            }
            .main {
                margin-left: 0;
                padding: 1rem;
            }
            .top-bar {
                flex-direction: column;
                align-items: stretch;
            }
            .top-bar-right {
                flex-wrap: wrap;
            }
            .page-header {
                flex-direction: column;
                align-items: stretch;
                text-align: center;
            }
        }
    </style>
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">

    <script>
        // Force rechargement si on revient avec le bouton "Retour"
        window.addEventListener('pageshow', function(event) {
            if (event.persisted) {
                window.location.reload();
            }
        });
    </script>
</head>

<body>

@php
    use App\Models\Perte;
    $pendingCount = Perte::where('statut','en_attente')->count();
    $pendingVerifCount = Perte::where('statut','en_attente_verification')->count();
    $statut = $perte->statut;
    
    // Mapping des statuts pour l'affichage
    $statusMap = [
        'en_attente'             => ['label'=>'En attente', 'class'=>'pending', 'icon'=>'⏳'],
        'en_attente_verification' => ['label'=>'En attente de vérification', 'class'=>'pending-verification', 'icon'=>'🔍'],
        'en_cours'               => ['label'=>'En cours', 'class'=>'in-progress', 'icon'=>'🔍'],
        'correspondance_trouvee' => ['label'=>'Correspondance trouvée', 'class'=>'matched', 'icon'=>'🔗'],
        'restitue'               => ['label'=>'Restitué', 'class'=>'returned', 'icon'=>'✅'],
        'non_retrouve'           => ['label'=>'Non retrouvé', 'class'=>'not-found', 'icon'=>'❓'],
        'validee'                => ['label'=>'Validée', 'class'=>'approved', 'icon'=>'✅'],
        'rejetee'                => ['label'=>'Rejetée', 'class'=>'rejected', 'icon'=>'❌'],
    ];
    $s = $statusMap[$statut] ?? ['label'=>ucfirst($statut), 'class'=>'pending', 'icon'=>'📄'];
    
    $docIcons = ['Passeport'=>'🛂',"Carte d'identité (CNI)"=>'🪪','Permis de conduire'=>'🚗',"Carte d'électeur"=>'🗳️','Acte de naissance'=>'📋','Certificat de nationalité'=>'📜'];
    $docIcon = $docIcons[$perte->type_piece] ?? '📄';
    $ref = $perte->numero_declaration ?? 'DL-'.str_pad($perte->id, 5, '0', STR_PAD_LEFT);
    
    // Variables pour le verrouillage
    $isLocked = $perte->is_locked ?? false;
    $assignedTo = $perte->assigned_to ?? null;
    $isAssignedToMe = $assignedTo == auth()->id();
    $assignedAgentName = $assignedTo ? ($perte->assignedAgent->name ?? 'un autre agent') : null;
    
    // Vérifier si le bouton "Prendre en charge" doit être affiché
    $showTakeButton = in_array($perte->statut, ['en_attente', 'en_attente_verification']) && !$isLocked;
    
    // ✅ NOUVEAU : Vérifier si la date d'expiration est manquante
    $hasExpiration = !is_null($perte->date_expiration);
    $needsManualVerification = $perte->statut === 'en_attente_verification' || $perte->statut_verification === 'manuelle';
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
        <div class="agent-badge">
            <i class="bi bi-shield-check"></i> AGENT
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">PRINCIPAL</div>
        <a href="{{ route('agent.dashboard') }}" class="{{ request()->routeIs('agent.dashboard') && !request('statut') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a href="{{ route('agent.dashboard', ['statut' => 'en_attente']) }}" class="{{ request('statut') == 'en_attente' ? 'active' : '' }}">
            <i class="bi bi-hourglass-split"></i> En attente
            @if($pendingCount > 0)
                <span class="nav-badge">{{ $pendingCount }}</span>
            @endif
        </a>
        <a href="{{ route('agent.dashboard', ['statut' => 'en_attente_verification']) }}" class="{{ request('statut') == 'en_attente_verification' ? 'active' : '' }}">
            <i class="bi bi-search"></i> À vérifier
            @if($pendingVerifCount > 0)
                <span class="nav-badge" style="background: #f59e0b;">{{ $pendingVerifCount }}</span>
            @endif
        </a>
        <a href="{{ route('agent.dashboard') }}">
            <i class="bi bi-files"></i> Toutes les pertes
        </a>

        <div class="nav-section">DOCUMENTS</div>
        <a href="{{ route('agent.documents-trouves.index') }}">
            <i class="bi bi-search-heart"></i> Documents trouvés
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
            <h1><i class="bi bi-file-text me-2" style="color: var(--primary);"></i>Détails de la déclaration</h1>
            <p>{{ $ref }} • {{ $perte->type_piece }}</p>
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
                @php $totalPending = $pendingCount + $pendingVerifCount; @endphp
                @if($totalPending > 0)
                    <span class="notification-badge">{{ $totalPending > 9 ? '9+' : $totalPending }}</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Alertes -->
    @if(session('success'))
        <div class="alert alert-success">
            <i class="bi bi-check-circle-fill"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">
            <i class="bi bi-exclamation-triangle-fill"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif
    @if(session('info'))
        <div class="alert alert-info">
            <i class="bi bi-info-circle-fill"></i>
            <span>{{ session('info') }}</span>
        </div>
    @endif

    <!-- Page Header -->
    <div class="page-header">
        <h1>{{ $docIcon }} {{ $perte->type_piece }}</h1>
        <a href="{{ route('agent.dashboard') }}" class="back-btn">
            <i class="bi bi-arrow-left"></i> Retour
        </a>
    </div>

    <!-- Status Banner -->
    <div class="status-banner {{ $s['class'] }}">
        <div class="status-icon">{{ $s['icon'] }}</div>
        <div>
            <div style="font-size: 1.1rem; font-weight: 800;">Statut : {{ $s['label'] }}</div>
            @if($perte->numero_declaration)
                <div style="font-size: 0.85rem; opacity: 0.8;">N° {{ $perte->numero_declaration }}</div>
            @endif
            @if(!$hasExpiration)
                <div style="font-size: 0.8rem; opacity: 0.7; margin-top: 0.3rem;">
                    ⚠️ Date d'expiration non renseignée
                </div>
            @endif
        </div>
    </div>

    <!-- ✅ NOUVEAU : Alerte de vérification manuelle -->
    @if($needsManualVerification && $statut !== 'rejetee')
        <div class="verification-alert">
            <div class="icon">🔍</div>
            <div class="content">
                <div class="title">Vérification manuelle requise</div>
                <div class="text">
                    Le citoyen n'a pas fourni de date d'expiration. 
                    @if($isAssignedToMe || $showTakeButton)
                        Veuillez vérifier manuellement le document ou ajouter la date d'expiration.
                    @else
                        Un agent doit prendre en charge ce dossier pour procéder à la vérification.
                    @endif
                </div>
            </div>
        </div>
    @endif

    <!-- Info supplémentaire pour non_retrouve (message pour l'agent) -->
    @if($perte->statut === 'non_retrouve')
        <div class="info-banner">
            <i class="bi bi-info-circle-fill" style="color: var(--warning); font-size: 1.5rem;"></i>
            <div>
                <strong>Document non retrouvé</strong><br>
                Le citoyen a été informé et invité à refaire une déclaration. Aucune action supplémentaire nécessaire de votre part.
            </div>
        </div>
    @endif

    <!-- Content Grid -->
    <div class="content-grid">

        <!-- Left Column -->
        <div>

            <!-- Déclarant -->
            <div class="card">
                <div class="card-header">
                    <div class="card-icon"><i class="bi bi-person"></i></div>
                    <div class="card-title">Informations du déclarant</div>
                </div>
                <div class="info-row">
                    <div class="info-item">
                        <div class="info-label">Nom</div>
                        <div class="info-value">{{ $perte->last_name }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Prénom</div>
                        <div class="info-value">{{ $perte->first_name }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Téléphone</div>
                        <div class="info-value">{{ $perte->contact }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Email</div>
                        <div class="info-value">{{ $perte->email }}</div>
                    </div>
                </div>
            </div>

            <!-- Document perdu -->
            <div class="card">
                <div class="card-header">
                    <div class="card-icon"><i class="bi bi-card-id"></i></div>
                    <div class="card-title">Informations du document perdu</div>
                </div>
                <div class="info-row">
                    <div class="info-item">
                        <div class="info-label">Type de pièce</div>
                        <div class="info-value">{{ $perte->type_piece }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Numéro de pièce</div>
                        <div class="info-value">{{ $perte->numero_piece ?? 'Non renseigné' }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Date de perte</div>
                        <div class="info-value">{{ \Carbon\Carbon::parse($perte->date_perte)->format('d/m/Y') }}</div>
                    </div>
                    <div class="info-item">
                        <div class="info-label">Lieu de perte</div>
                        <div class="info-value">{{ $perte->lieu_perte }}</div>
                    </div>
                    @if($perte->date_delivrance)
                    <div class="info-item">
                        <div class="info-label">Date de délivrance</div>
                        <div class="info-value">{{ \Carbon\Carbon::parse($perte->date_delivrance)->format('d/m/Y') }}</div>
                    </div>
                    @endif
                    <!-- ✅ NOUVEAU : Date d'expiration -->
                    <div class="info-item">
                        <div class="info-label">Date d'expiration</div>
                        <div class="info-value">
                            @if($hasExpiration)
                                {{ \Carbon\Carbon::parse($perte->date_expiration)->format('d/m/Y') }}
                                @if($perte->isExpired())
                                    <span class="badge badge-danger">Expiré</span>
                                @else
                                    <span class="badge badge-success">Valide</span>
                                @endif
                            @else
                                <span style="color: #f59e0b;">
                                    <i class="bi bi-exclamation-triangle"></i> Non renseignée
                                </span>
                                <span class="badge badge-warning">Vérification requise</span>
                            @endif
                        </div>
                    </div>
                    @if($perte->autorite_delivrance)
                    <div class="info-item">
                        <div class="info-label">Autorité de délivrance</div>
                        <div class="info-value">{{ $perte->autorite_delivrance }}</div>
                    </div>
                    @endif
                    <!-- ✅ NOUVEAU : Statut de vérification -->
                    <div class="info-item full-width">
                        <div class="info-label">Mode de vérification</div>
                        <div class="info-value">
                            @if($perte->statut_verification === 'auto')
                                <span class="badge badge-success">✅ Automatique</span>
                                <span style="font-size: 0.8rem; color: var(--gray-600); margin-left: 0.5rem;">
                                    (Date d'expiration fournie)
                                </span>
                            @else
                                <span class="badge badge-warning">🔍 Manuelle</span>
                                <span style="font-size: 0.8rem; color: var(--gray-600); margin-left: 0.5rem;">
                                    @if($perte->verified_at)
                                        (Vérifiée le {{ $perte->verified_at->format('d/m/Y') }})
                                    @else
                                        (En attente de vérification)
                                    @endif
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Circonstances -->
            @if($perte->circonstances)
            <div class="card">
                <div class="card-header">
                    <div class="card-icon"><i class="bi bi-journal-text"></i></div>
                    <div class="card-title">Circonstances de la perte</div>
                </div>
                <div class="info-item full-width">
                    <div class="info-value" style="line-height: 1.6;">{{ $perte->circonstances }}</div>
                </div>
            </div>
            @endif

            <!-- Pièces jointes -->
            @if($perte->copie_piece || $perte->declaration_vol || $perte->document_complementaire)
            <div class="card">
                <div class="card-header">
                    <div class="card-icon"><i class="bi bi-paperclip"></i></div>
                    <div class="card-title">Pièces jointes</div>
                </div>
                <div class="info-item full-width">
                    <div style="display:flex;flex-wrap:wrap;gap:.5rem;">
                        @if($perte->copie_piece)
                        <a href="{{ Storage::url($perte->copie_piece) }}" target="_blank" class="file-chip">
                            <i class="bi bi-file-earmark-pdf"></i> Copie de la pièce
                        </a>
                        @endif
                        @if($perte->declaration_vol)
                        <a href="{{ Storage::url($perte->declaration_vol) }}" target="_blank" class="file-chip">
                            <i class="bi bi-file-text"></i> Déclaration de vol
                        </a>
                        @endif
                        @if($perte->document_complementaire)
                        <a href="{{ Storage::url($perte->document_complementaire) }}" target="_blank" class="file-chip">
                            <i class="bi bi-file-earmark"></i> Doc. complémentaire
                        </a>
                        @endif
                    </div>
                </div>
            </div>
            @endif

            <!-- Rejection Reason -->
            @if($perte->statut === 'rejetee' && $perte->motif_rejet)
                <div class="card">
                    <div class="card-header">
                        <div class="card-icon"><i class="bi bi-x-octagon"></i></div>
                        <div class="card-title">Motif du rejet</div>
                    </div>
                    <div class="rejection-box">
                        <p>{{ $perte->motif_rejet }}</p>
                    </div>
                </div>
            @endif

            <!-- Timeline enrichie -->
            <div class="card">
                <div class="card-header">
                    <div class="card-icon"><i class="bi bi-clock-history"></i></div>
                    <div class="card-title">Chronologie</div>
                </div>
                <div class="timeline">
                    <!-- Étape 1 : Soumission -->
                    <div class="timeline-item">
                        <div class="timeline-date">{{ \Carbon\Carbon::parse($perte->created_at)->format('d/m/Y H:i') }}</div>
                        <div class="timeline-text">Déclaration soumise par le citoyen</div>
                    </div>

                    <!-- ✅ Étape 1.5 : Vérification manuelle (si applicable) -->
                    @if($needsManualVerification)
                    <div class="timeline-item">
                        <div class="timeline-date">
                            @if($perte->verified_at)
                                {{ $perte->verified_at->format('d/m/Y H:i') }}
                            @else
                                En attente
                            @endif
                        </div>
                        <div class="timeline-text">
                            @if($perte->verified_at)
                                ✅ Vérification manuelle effectuée
                                @if($perte->verifier)
                                    par {{ $perte->verifier->name }}
                                @endif
                            @else
                                ⏳ En attente de vérification manuelle (date d'expiration manquante)
                            @endif
                        </div>
                    </div>
                    @endif

                    <!-- Étape 2 : Prise en charge -->
                    @php
                        $taken = in_array($perte->statut, ['en_cours', 'correspondance_trouvee', 'restitue', 'non_retrouve', 'validee', 'rejetee']);
                    @endphp
                    <div class="timeline-item">
                        <div class="timeline-date">
                            @if($taken && $perte->validated_at)
                                {{ $perte->validated_at->format('d/m/Y H:i') }}
                            @else
                                —
                            @endif
                        </div>
                        <div class="timeline-text">
                            Prise en charge par un agent
                            @if($perte->validator)
                                ({{ $perte->validator->name }})
                            @endif
                        </div>
                    </div>

                    <!-- Étape 3 : Correspondance trouvée -->
                    @php
                        $found = in_array($perte->statut, ['correspondance_trouvee', 'restitue']);
                    @endphp
                    <div class="timeline-item">
                        <div class="timeline-date">
                            @if($found && $perte->updated_at)
                                {{ $perte->updated_at->format('d/m/Y H:i') }}
                            @else
                                —
                            @endif
                        </div>
                        <div class="timeline-text">
                            @if($found)
                                Correspondance trouvée
                            @else
                                Recherche de correspondance en cours
                            @endif
                        </div>
                    </div>

                    <!-- Étape 4 : Restitution / Non retrouvé -->
                    <div class="timeline-item">
                        <div class="timeline-date">
                            @if($perte->statut === 'restitue' && $perte->date_restitution)
                                {{ $perte->date_restitution->format('d/m/Y H:i') }}
                            @elseif($perte->statut === 'non_retrouve' && $perte->date_passage_non_retrouve)
                                {{ $perte->date_passage_non_retrouve->format('d/m/Y H:i') }}
                            @else
                                —
                            @endif
                        </div>
                        <div class="timeline-text">
                            @if($perte->statut === 'restitue')
                                ✅ Document restitué
                            @elseif($perte->statut === 'non_retrouve')
                                ❌ Document non retrouvé – dossier clos
                            @else
                                En attente de finalisation
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column -->
        <div>
            <!-- Citizen Info -->
            <div class="citizen-card">
                <div class="citizen-avatar">
                    {{ strtoupper(substr($perte->first_name ?? $perte->user->name, 0, 1)) }}
                </div>
                <div class="citizen-name">{{ $perte->first_name }} {{ $perte->last_name }}</div>
                <div class="citizen-email">{{ $perte->email }}</div>
                <div class="citizen-info">
                    <div class="citizen-info-item">
                        <span><i class="bi bi-telephone"></i> Téléphone</span>
                        <strong>{{ $perte->contact }}</strong>
                    </div>
                    @if($perte->user && $perte->user->address)
                    <div class="citizen-info-item">
                        <span><i class="bi bi-geo-alt"></i> Adresse</span>
                        <strong>{{ $perte->user->address }}</strong>
                    </div>
                    @endif
                </div>
            </div>

            <!-- ============================================================
            ACTIONS AVEC VERROUILLAGE
            ============================================================ -->
            <div class="action-section">
                <div class="action-title"><i class="bi bi-lightning-charge"></i> Actions disponibles</div>

                <!-- ============================================================
                ⛔ BLOC DE VERROUILLAGE - DOSSIER PRIS PAR UN AUTRE AGENT
                ============================================================ -->
                @if($isLocked && !$isAssignedToMe)
                    <div class="locked-block">
                        <p class="locked-title">
                            <i class="bi bi-lock-fill"></i> Dossier verrouillé
                        </p>
                        <p class="locked-text">
                            Ce dossier est en cours de traitement par 
                            <strong>{{ $assignedAgentName ?? 'un autre agent' }}</strong>.
                            Vous ne pouvez pas le modifier.
                        </p>
                    </div>
                @endif

                <!-- ============================================================
                ✅ NOUVEAU : FORMULAIRE AJOUT DATE D'EXPIRATION
                (pour les dossiers en attente de vérification)
                ============================================================ -->
                @if(($perte->statut === 'en_attente_verification' && $isAssignedToMe) || 
                    ($perte->statut === 'en_attente_verification' && $showTakeButton))
                    <div class="expiration-form">
                        <label for="date_expiration">
                            <i class="bi bi-calendar3"></i> Ajouter la date d'expiration
                        </label>
                        <form method="POST" action="{{ route('agent.perte.update-date-expiration', $perte->id) }}">
                            @csrf
                            <input type="date" name="date_expiration" id="date_expiration" 
                                   min="{{ date('Y-m-d') }}" required
                                   @if($perte->date_delivrance)
                                   min="{{ \Carbon\Carbon::parse($perte->date_delivrance)->addDay()->format('Y-m-d') }}"
                                   @endif
                                   >
                            <button type="submit" class="btn btn-primary" style="margin-top: 0.5rem;">
                                <i class="bi bi-check2-circle"></i> Valider la date d'expiration
                            </button>
                            <div class="action-help" style="margin-top: 0.5rem;">
                                <i class="bi bi-info-circle"></i> 
                                @if($perte->date_delivrance)
                                    La date doit être postérieure au {{ \Carbon\Carbon::parse($perte->date_delivrance)->format('d/m/Y') }}
                                @else
                                    La date doit être dans le futur
                                @endif
                            </div>
                        </form>
                        
                        <hr style="margin: 0.8rem 0; border-color: var(--gray-200);">
                        
                        <!-- Ou valider sans date -->
                        <form method="POST" action="{{ route('agent.perte.valider-verification-manuelle', $perte->id) }}" 
                              onsubmit="return confirm('✅ Valider cette déclaration sans date d\'expiration ?\n\nLe citoyen a été vérifié manuellement et le document est valide.\n\nConfirmez-vous ?')">
                            @csrf
                            <button type="submit" class="btn btn-verify">
                                <i class="bi bi-check2-circle"></i> Valider sans date d'expiration
                            </button>
                            <div class="action-help">
                                <i class="bi bi-info-circle"></i> 
                                Utilisez cette option si vous avez vérifié manuellement que le document est valide, 
                                même sans date d'expiration.
                            </div>
                        </form>

                        <!-- Ou rejeter -->
                        <button class="btn btn-reject" onclick="openRejectModal()" style="margin-top: 0.5rem;">
                            <i class="bi bi-x-lg"></i> Rejeter la déclaration
                        </button>
                        <div class="action-help">
                            <i class="bi bi-exclamation-triangle"></i> 
                            "Rejeter" si la déclaration est frauduleuse, incomplète ou invalide.
                        </div>
                    </div>
                @endif

                <!-- ============================================================
                BOUTON PRENDRE EN CHARGE (conditionné par $showTakeButton)
                ============================================================ -->
                @if($showTakeButton && $perte->statut !== 'en_attente_verification')
                    <form method="POST" 
                          action="{{ route('agent.perte.prendre', $perte->id) }}"
                          style="display:inline; width:100%;"
                          onsubmit="return confirm('Prendre en charge cette déclaration ?')">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-play-circle"></i> Prendre en charge
                        </button>
                    </form>
                @endif

                <!-- ============================================================
                AUTRES BOUTONS (selon statut)
                ============================================================ -->
                @if($perte->statut === 'en_cours' && $isAssignedToMe)
                    <a href="{{ route('agent.perte.recherche', $perte->id) }}" class="btn btn-primary">
                        <i class="bi bi-search-heart"></i> Rechercher des correspondances
                    </a>

                    <!-- Bouton "Non retrouvé" -->
                    <form method="POST" action="{{ route('agent.perte.non-retrouve', $perte->id) }}" style="width:100%;" onsubmit="return confirm('⚠️ Déclarer ce document comme NON RETROUVÉ ?\n\n🔍 Cette action signifie :\n- Le document n\'a pas été retrouvé après recherche.\n- Un récépissé PDF sera généré automatiquement.\n- Le citoyen recevra une notification avec lien de téléchargement.\n- Le citoyen pourra refaire son titre auprès du service compétent.\n\n✅ Confirmez-vous cette action ?')">
                        @csrf
                        <button type="submit" class="btn btn-notfound">
                            <i class="bi bi-emoji-frown"></i> Non retrouvé (lancer le renouvellement)
                        </button>
                    </form>
                    <div class="action-help">
                        <i class="bi bi-info-circle"></i> Utilisez "Non retrouvé" quand le document est perdu et doit être refait. Cela génère un récépissé PDF pour le citoyen.
                    </div>

                    <button class="btn btn-reject" onclick="openRejectModal()">
                        <i class="bi bi-x-lg"></i> Rejeter la déclaration
                    </button>
                    <div class="action-help">
                        <i class="bi bi-exclamation-triangle"></i> "Rejeter" est réservé aux déclarations frauduleuses, incomplètes ou invalides. Un motif est obligatoire.
                    </div>

                @elseif($perte->statut === 'correspondance_trouvee' && $isAssignedToMe)
                    <form method="POST" action="{{ route('agent.perte.restitution', $perte->id) }}" style="width:100%;">
                        @csrf
                        <button type="submit" class="btn btn-approve" onclick="return confirm('Confirmer la restitution physique du document ?')">
                            <i class="bi bi-check2-circle"></i> Marquer comme restitué
                        </button>
                    </form>

                    <!-- Bouton "Non retrouvé" si finalement le document n'est pas restitué -->
                    <form method="POST" action="{{ route('agent.perte.non-retrouve', $perte->id) }}" style="width:100%;" onsubmit="return confirm('⚠️ Déclarer ce document comme NON RETROUVÉ ?\n\nMême si une correspondance a été trouvée, vous pouvez décider de le déclarer non retrouvé si finalement le document ne peut être restitué.\n\n✅ Confirmez-vous ?')">
                        @csrf
                        <button type="submit" class="btn btn-notfound">
                            <i class="bi bi-emoji-frown"></i> Non retrouvé (annuler la correspondance)
                        </button>
                    </form>
                    <div class="action-help">
                        <i class="bi bi-info-circle"></i> Si malgré la correspondance, le document ne peut pas être restitué, utilisez "Non retrouvé".
                    </div>

                    <button class="btn btn-reject" onclick="openRejectModal()">
                        <i class="bi bi-x-lg"></i> Rejeter la déclaration
                    </button>
                    <div class="action-help">
                        <i class="bi bi-exclamation-triangle"></i> "Rejeter" est réservé aux déclarations frauduleuses, incomplètes ou invalides. Un motif est obligatoire.
                    </div>

                @elseif($perte->statut === 'pret_recuperation' && $isAssignedToMe)
                    <!-- ✅ BOUTON RESTITUER POUR PRET_RECUPERATION -->
                    <form method="POST" action="{{ route('agent.perte.restitution', $perte->id) }}" style="width:100%;">
                        @csrf
                        <button type="submit" class="btn btn-approve" onclick="return confirm('Confirmer la restitution physique du document ?')">
                            <i class="bi bi-check2-circle"></i> Restituer
                        </button>
                    </form>
                    <div class="action-help">
                        <i class="bi bi-info-circle"></i> Le citoyen a signalé avoir récupéré son document. Validez la restitution pour finaliser le dossier.
                    </div>

                @elseif($perte->statut === 'restitue')
                    <div class="alert alert-success" style="margin-bottom: 0;">
                        <i class="bi bi-check-circle-fill"></i> Dossier finalisé – Document restitué le {{ $perte->date_restitution ? $perte->date_restitution->format('d/m/Y') : '—' }}
                    </div>

                @elseif($perte->statut === 'non_retrouve')
                    <div class="alert alert-warning" style="margin-bottom: 0; background: #fef3c7; color: #92400e;">
                        <i class="bi bi-emoji-frown"></i> Document non retrouvé – Aucune action supplémentaire.
                    </div>
                    <!-- Bouton de simulation (pour tester) -->
                    <form method="POST" action="{{ route('agent.perte.simuler-pret', $perte->id) }}" style="margin-top: 1rem;" onsubmit="return confirm('Simuler la préparation du document ? Le citoyen sera notifié.')">
                        @csrf
                        <div style="display: flex; gap: 0.5rem; align-items: center; flex-wrap: wrap;">
                            <input type="text" name="lieu_recuperation" value="Commissariat de Lomé – Bureau 5 (SIMULATION)" class="form-control" style="flex:1; padding: 0.6rem 1rem; border: 1px solid var(--gray-200); border-radius: 8px; background: white; color: var(--dark);" required>
                            <button type="submit" class="btn btn-primary" style="width: auto; background: #f59e0b; color: white; border: none; padding: 0.6rem 1.2rem; border-radius: 8px; font-weight: 600;">
                                <i class="bi bi-clock-history"></i> Simuler préparation
                            </button>
                        </div>
                        <div class="action-help" style="margin-top: 0.5rem;">
                            <i class="bi bi-info-circle"></i> Utilisez ce bouton en test pour simuler la préparation du document (passage en "Prêt à récupérer").
                        </div>
                    </form>

                @elseif($perte->statut === 'validee')
                    <div class="alert alert-success" style="margin-bottom: 0;">
                        <i class="bi bi-check-circle-fill"></i> Déclaration validée
                    </div>

                @elseif($perte->statut === 'rejetee')
                    <div class="alert alert-danger" style="margin-bottom: 0;">
                        <i class="bi bi-x-octagon"></i> Déclaration rejetée
                    </div>
                @endif

                <hr style="margin: 1rem 0; border-color: var(--gray-200);">
                <a href="{{ route('agent.dashboard') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left"></i> Retour à la liste
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div class="modal" id="rejectModal">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-title"><i class="bi bi-x-octagon"></i> Rejeter la déclaration</div>
            <button class="modal-close" onclick="closeRejectModal()"><i class="bi bi-x-lg"></i></button>
        </div>
        <form method="POST" action="{{ route('agent.perte.rejeter', $perte->id) }}" id="rejectForm">
            @csrf
            <div class="form-group">
                <label class="form-label">Motif du rejet *</label>
                <textarea class="form-textarea" name="motif_rejet" required placeholder="Expliquez clairement la raison du rejet pour que le citoyen puisse comprendre et corriger si nécessaire (minimum 10 caractères)..."></textarea>
            </div>
            <div class="modal-actions">
                <button type="submit" class="btn-modal btn-modal-danger">Confirmer le rejet</button>
                <button type="button" class="btn-modal btn-modal-secondary" onclick="closeRejectModal()">Annuler</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Horloge temps réel
    function updateDateTime() {
        const now = new Date();
        const options = { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit' };
        const formatted = now.toLocaleDateString('fr-FR', options).replace(',', ' -');
        const dateTimeEl = document.getElementById('currentDateTime');
        if (dateTimeEl) dateTimeEl.innerHTML = formatted;
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

    // Reject Modal
    function openRejectModal() {
        document.getElementById('rejectModal').classList.add('active');
    }
    function closeRejectModal() {
        document.getElementById('rejectModal').classList.remove('active');
    }
    document.getElementById('rejectModal')?.addEventListener('click', (e) => {
        if (e.target.id === 'rejectModal') closeRejectModal();
    });

    // Auto-hide alerts
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
            alert.style.transition = 'opacity 0.3s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        });
    }, 5000);

    // Initialisation
    document.addEventListener('DOMContentLoaded', function() {
        loadTheme();
        const themeBtn = document.getElementById('themeToggleBtn');
        if (themeBtn) themeBtn.addEventListener('click', toggleGlobalDarkMode);
    });
</script>
</body>
</html>