<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document Trouvé — Agent | e-Déclaration TG</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
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
                    document.documentElement.style.backgroundColor = '#f0f4f8';
                    document.body.style.backgroundColor = '#f0f4f8';
                }
            } catch(e) {}
        })();
    </script>
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Poppins', sans-serif; }

        body {
            background: #f0f4f8;
            min-height: 100vh;
            display: flex;
            transition: background 0.2s ease;
        }
        
        body.dark-mode {
            background: #0f172a;
        }

        /* ===== SIDEBAR AGENT ===== */
        .sidebar {
            width: 260px;
            background: linear-gradient(180deg, #1e3a5f 0%, #0f2744 100%);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 100;
            box-shadow: 4px 0 20px rgba(0,0,0,0.15);
            transition: background 0.2s;
        }
        
        body.dark-mode .sidebar {
            background: linear-gradient(180deg, #0f172a 0%, #0a0f1a 100%);
        }

        .sidebar-header {
            padding: 2rem 1.5rem 1.5rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }

        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            margin-bottom: 0.5rem;
        }

        .sidebar-logo-icon {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, #27ae60, #2ecc71);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.3rem;
        }

        .sidebar-logo-text {
            color: white;
            font-size: 1rem;
            font-weight: 800;
            line-height: 1.2;
        }

        .sidebar-logo-text span {
            display: block;
            font-size: 0.7rem;
            font-weight: 500;
            color: rgba(255,255,255,0.5);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .agent-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            background: rgba(39,174,96,0.2);
            border: 1px solid rgba(39,174,96,0.4);
            color: #2ecc71;
            font-size: 0.72rem;
            font-weight: 700;
            padding: 0.25rem 0.7rem;
            border-radius: 50px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 0.5rem;
        }

        .sidebar-nav {
            flex: 1;
            padding: 1.2rem 0.8rem;
            overflow-y: auto;
        }

        .nav-section-label {
            color: rgba(255,255,255,0.3);
            font-size: 0.68rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 0.5rem 0.8rem;
            margin-top: 0.5rem;
        }

        .sidebar-nav a {
            text-decoration: none;
            color: rgba(255,255,255,0.6);
            font-weight: 500;
            padding: 0.75rem 1rem;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            transition: all 0.2s;
            font-size: 0.88rem;
            margin-bottom: 0.1rem;
        }

        .sidebar-nav a:hover {
            background: rgba(255,255,255,0.08);
            color: white;
        }

        .sidebar-nav a.active {
            background: linear-gradient(135deg, rgba(39,174,96,0.3), rgba(46,204,113,0.2));
            color: #2ecc71;
            font-weight: 700;
            border: 1px solid rgba(39,174,96,0.3);
        }

        .sidebar-nav a svg { width: 18px; height: 18px; flex-shrink: 0; }

        .nav-badge {
            margin-left: auto;
            background: #ef4444;
            color: white;
            font-size: 0.65rem;
            font-weight: 700;
            min-width: 18px;
            height: 18px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 4px;
        }

        .sidebar-footer {
            padding: 1rem 0.8rem;
            border-top: 1px solid rgba(255,255,255,0.1);
        }

        .btn-logout {
            width: 100%;
            background: rgba(231,76,60,0.15);
            color: #e74c3c;
            padding: 0.8rem;
            border: 1px solid rgba(231,76,60,0.3);
            border-radius: 10px;
            font-size: 0.85rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-logout:hover { background: rgba(231,76,60,0.25); }

        /* ===== MAIN ===== */
        .main-content {
            margin-left: 260px;
            flex: 1;
            padding: 2rem;
            min-height: 100vh;
        }
        
        body.dark-mode .main-content {
            background: transparent;
        }

        .page-wrapper { max-width: 1100px; margin: 0 auto; }

        /* ===== TOP BAR ===== */
        .top-bar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .top-bar-left { display: flex; align-items: center; gap: 1rem; }

        .back-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: white;
            color: #475569;
            text-decoration: none;
            padding: 0.65rem 1.2rem;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.85rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            transition: all 0.2s;
        }
        
        body.dark-mode .back-btn {
            background: #1e293b;
            color: #94a3b8;
        }

        .back-btn:hover {
            background: #f8fafc;
            color: #1e3a5f;
            transform: translateX(-2px);
        }
        
        body.dark-mode .back-btn:hover {
            background: #334155;
            color: #e5e7eb;
        }

        .page-title {
            font-size: 1.4rem;
            font-weight: 800;
            color: #1e3a5f;
        }
        
        body.dark-mode .page-title {
            color: #e5e7eb;
        }

        .page-title span { color: #27ae60; }

        .top-bar-right { display: flex; align-items: center; gap: 0.8rem; }

        .icon-btn {
            background: white;
            border: none;
            width: 42px;
            height: 42px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0,0,0,0.08);
            position: relative;
            transition: all 0.2s;
        }
        
        body.dark-mode .icon-btn {
            background: #1e293b;
        }

        .icon-btn:hover { transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0,0,0,0.12); }
        .icon-btn svg { width: 20px; height: 20px; stroke: #475569; }
        
        body.dark-mode .icon-btn svg {
            stroke: #94a3b8;
        }

        .notif-dot {
            position: absolute;
            top: -3px; right: -3px;
            background: #ef4444;
            color: white;
            font-size: 0.6rem;
            font-weight: 700;
            min-width: 16px;
            height: 16px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 3px;
        }

        /* ===== FLASH MESSAGES ===== */
        .flash {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            font-weight: 600;
            font-size: 0.9rem;
        }

        .flash-success { background: #d1fae5; border: 1.5px solid #10b981; color: #065f46; }
        .flash-error   { background: #fee2e2; border: 1.5px solid #ef4444; color: #991b1b; }
        
        body.dark-mode .flash-success { background: #0a3b2a; color: #a7f3d0; }
        body.dark-mode .flash-error { background: #3f1e1e; color: #fecaca; }

        /* ===== HERO CARD ===== */
        .hero-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.07);
            position: relative;
            overflow: hidden;
            transition: background 0.2s;
        }
        
        body.dark-mode .hero-card {
            background: #1e293b;
        }

        .hero-card::before {
            content: '';
            position: absolute;
            top: 0; left: 0;
            width: 100%;
            height: 4px;
            background: linear-gradient(90deg, #1e3a5f, #27ae60, #3b82f6);
        }

        .hero-inner {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .hero-left { flex: 1; min-width: 200px; }

        .hero-ref {
            font-size: 0.75rem;
            color: #94a3b8;
            font-weight: 600;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 0.4rem;
        }

        .hero-title {
            font-size: 1.8rem;
            font-weight: 800;
            color: #1e3a5f;
            line-height: 1.2;
            margin-bottom: 0.8rem;
        }
        
        body.dark-mode .hero-title {
            color: #e5e7eb;
        }

        .hero-title small {
            display: block;
            font-size: 1rem;
            color: #64748b;
            font-weight: 500;
            margin-top: 0.3rem;
        }
        
        body.dark-mode .hero-title small {
            color: #94a3b8;
        }

        .statut-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.45rem 1rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.82rem;
        }

        .statut-dot { width: 7px; height: 7px; border-radius: 50%; background: currentColor; }
        .pulse { animation: pulse 2s infinite; }
        @keyframes pulse { 0%,100%{opacity:1} 50%{opacity:0.3} }

        .s-attente  { background: #fef3c7; color: #d97706; }
        .s-matche   { background: #dbeafe; color: #2563eb; }
        .s-restitue { background: #d1fae5; color: #059669; }
        .s-archive  { background: #f1f5f9; color: #64748b; }
        
        body.dark-mode .s-attente  { background: #422d0b; color: #fbbf24; }
        body.dark-mode .s-matche   { background: #1e3a5f; color: #60a5fa; }
        body.dark-mode .s-restitue { background: #0a3b2a; color: #34d399; }
        body.dark-mode .s-archive  { background: #1e293b; color: #94a3b8; }

        .hero-doc-icon {
            width: 80px;
            height: 80px;
            border-radius: 18px;
            background: linear-gradient(135deg, #1e3a5f, #2d5a8e);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.5rem;
            box-shadow: 0 8px 25px rgba(30,58,95,0.25);
            flex-shrink: 0;
        }

        .hero-meta-row {
            display: flex;
            gap: 2rem;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #f1f5f9;
            flex-wrap: wrap;
        }
        
        body.dark-mode .hero-meta-row {
            border-top-color: #334155;
        }

        .meta-item { display: flex; align-items: center; gap: 0.5rem; }
        .meta-item svg { width: 15px; height: 15px; stroke: #27ae60; flex-shrink: 0; }
        .meta-label { font-size: 0.75rem; color: #94a3b8; font-weight: 500; }
        .meta-value { font-size: 0.9rem; color: #1e293b; font-weight: 700; }
        
        body.dark-mode .meta-value {
            color: #cbd5e1;
        }

        /* ===== LAYOUT 3 COLONNES ===== */
        .layout-grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .layout-grid-2 {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 1.5rem;
            margin-bottom: 1.5rem;
        }

        /* ===== CARDS ===== */
        .card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 16px rgba(0,0,0,0.07);
            transition: background 0.2s;
        }
        
        body.dark-mode .card {
            background: #1e293b;
        }

        .card-full {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 16px rgba(0,0,0,0.07);
            margin-bottom: 1.5rem;
        }

        .card-header {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            margin-bottom: 1.2rem;
            padding-bottom: 0.8rem;
            border-bottom: 2px solid #f8fafc;
        }
        
        body.dark-mode .card-header {
            border-bottom-color: #334155;
        }

        .card-icon {
            width: 36px;
            height: 36px;
            border-radius: 9px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1rem;
            flex-shrink: 0;
        }

        .ci-blue   { background: #dbeafe; }
        .ci-green  { background: #d1fae5; }
        .ci-orange { background: #ffedd5; }
        .ci-red    { background: #fee2e2; }
        .ci-purple { background: #ede9fe; }
        .ci-yellow { background: #fef9c3; }
        
        body.dark-mode .ci-blue   { background: #1e3a5f; }
        body.dark-mode .ci-green  { background: #0a3b2a; }
        body.dark-mode .ci-orange { background: #422d0b; }
        body.dark-mode .ci-red    { background: #3f1e1e; }
        body.dark-mode .ci-purple { background: #2e1a3f; }
        body.dark-mode .ci-yellow { background: #3d3a0a; }

        .card-title { font-weight: 700; color: #1e3a5f; font-size: 0.95rem; }
        
        body.dark-mode .card-title {
            color: #e5e7eb;
        }

        .info-list { display: flex; flex-direction: column; gap: 0; }

        .info-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.55rem 0;
            border-bottom: 1px solid #f8fafc;
            gap: 1rem;
        }
        
        body.dark-mode .info-item {
            border-bottom-color: #334155;
        }

        .info-item:last-child { border-bottom: none; padding-bottom: 0; }

        .ii-label { font-size: 0.78rem; color: #94a3b8; font-weight: 500; flex-shrink: 0; }
        .ii-value { font-size: 0.87rem; color: #1e293b; font-weight: 600; text-align: right; }
        
        body.dark-mode .ii-value {
            color: #cbd5e1;
        }
        
        .ii-empty { color: #cbd5e1; font-style: italic; font-weight: 400; }

        /* ===== ACTIONS PANEL ===== */
        .actions-panel {
            background: linear-gradient(135deg, #1e3a5f, #0f2744);
            border-radius: 16px;
            padding: 1.5rem;
            box-shadow: 0 4px 16px rgba(0,0,0,0.15);
        }
        
        body.dark-mode .actions-panel {
            background: linear-gradient(135deg, #0f172a, #0a0f1a);
        }

        .actions-title {
            color: rgba(255,255,255,0.5);
            font-size: 0.7rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            margin-bottom: 1rem;
        }

        .action-btn {
            display: flex;
            align-items: center;
            gap: 0.8rem;
            width: 100%;
            padding: 0.9rem 1.2rem;
            border-radius: 10px;
            border: none;
            font-weight: 700;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            margin-bottom: 0.7rem;
        }

        .action-btn:last-child { margin-bottom: 0; }

        .action-btn svg { width: 18px; height: 18px; flex-shrink: 0; }

        .btn-match {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            box-shadow: 0 4px 12px rgba(59,130,246,0.3);
        }

        .btn-match:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(59,130,246,0.4); }

        .btn-restitue {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            box-shadow: 0 4px 12px rgba(16,185,129,0.3);
        }

        .btn-restitue:hover { transform: translateY(-2px); box-shadow: 0 6px 18px rgba(16,185,129,0.4); }

        .btn-archive {
            background: rgba(255,255,255,0.08);
            color: rgba(255,255,255,0.6);
            border: 1px solid rgba(255,255,255,0.1);
        }

        .btn-archive:hover { background: rgba(255,255,255,0.12); color: white; }

        .btn-disabled {
            background: rgba(255,255,255,0.05);
            color: rgba(255,255,255,0.25);
            cursor: not-allowed;
            border: 1px dashed rgba(255,255,255,0.1);
        }

        /* ===== MODAL MATCH ===== */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.6);
            backdrop-filter: blur(4px);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }

        .modal-overlay.open { display: flex; }

        .modal {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            max-width: 600px;
            width: 100%;
            box-shadow: 0 30px 80px rgba(0,0,0,0.3);
            animation: slideUp 0.3s ease;
            max-height: 90vh;
            overflow-y: auto;
        }
        
        body.dark-mode .modal {
            background: #1e293b;
        }

        @keyframes slideUp { from { transform: translateY(20px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }

        .modal-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #f1f5f9;
        }
        
        body.dark-mode .modal-header {
            border-bottom-color: #334155;
        }

        .modal-title { font-size: 1.2rem; font-weight: 800; color: #1e3a5f; }
        
        body.dark-mode .modal-title {
            color: #e5e7eb;
        }

        .modal-close {
            background: #f1f5f9;
            border: none;
            width: 34px;
            height: 34px;
            border-radius: 8px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.1rem;
            color: #64748b;
            transition: all 0.2s;
        }
        
        body.dark-mode .modal-close {
            background: #334155;
            color: #94a3b8;
        }

        .modal-close:hover { background: #e2e8f0; color: #1e3a5f; }
        
        body.dark-mode .modal-close:hover {
            background: #475569;
            color: #e5e7eb;
        }

        .modal-subtitle { color: #64748b; font-size: 0.85rem; margin-bottom: 1.5rem; }
        
        body.dark-mode .modal-subtitle {
            color: #94a3b8;
        }

        .perte-option {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            margin-bottom: 0.8rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        body.dark-mode .perte-option {
            border-color: #334155;
        }

        .perte-option:hover { border-color: #3b82f6; background: #eff6ff; }
        
        body.dark-mode .perte-option:hover {
            background: #1e3a5f;
            border-color: #60a5fa;
        }
        
        .perte-option input[type="radio"] { width: 18px; height: 18px; accent-color: #3b82f6; flex-shrink: 0; }
        .perte-option-info { flex: 1; }
        .perte-option-name { font-weight: 700; color: #1e293b; font-size: 0.9rem; }
        
        body.dark-mode .perte-option-name {
            color: #e5e7eb;
        }
        
        .perte-option-detail { font-size: 0.78rem; color: #64748b; margin-top: 0.2rem; }
        
        body.dark-mode .perte-option-detail {
            color: #94a3b8;
        }

        .modal-empty { text-align: center; padding: 2rem; color: #94a3b8; }
        .modal-empty-icon { font-size: 2rem; margin-bottom: 0.5rem; }

        .modal-footer { margin-top: 1.5rem; display: flex; gap: 0.8rem; }

        .btn-modal-confirm {
            flex: 1;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            padding: 0.9rem;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.9rem;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-modal-confirm:hover { transform: translateY(-1px); box-shadow: 0 6px 15px rgba(59,130,246,0.3); }

        .btn-modal-cancel {
            padding: 0.9rem 1.5rem;
            background: #f1f5f9;
            color: #64748b;
            border: none;
            border-radius: 10px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        body.dark-mode .btn-modal-cancel {
            background: #334155;
            color: #94a3b8;
        }

        .btn-modal-cancel:hover { background: #e2e8f0; }
        
        body.dark-mode .btn-modal-cancel:hover {
            background: #475569;
            color: #e5e7eb;
        }

        /* ===== CORRESPONDANCES ===== */
        .corr-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.2rem;
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            margin-bottom: 0.8rem;
            transition: all 0.2s;
            gap: 1rem;
            cursor: pointer;
        }
        
        body.dark-mode .corr-item {
            border-color: #334155;
        }

        .corr-item:hover { border-color: #3b82f6; background: #f8faff; }
        
        body.dark-mode .corr-item:hover {
            background: #1e3a5f;
            border-color: #60a5fa;
        }
        
        .corr-item:last-child { margin-bottom: 0; }
        .corr-name { font-weight: 700; color: #1e293b; font-size: 0.9rem; }
        
        body.dark-mode .corr-name {
            color: #e5e7eb;
        }
        
        .corr-detail { font-size: 0.78rem; color: #64748b; margin-top: 0.2rem; }
        
        body.dark-mode .corr-detail {
            color: #94a3b8;
        }
        
        .corr-tag { background: #fef3c7; color: #d97706; font-size: 0.72rem; font-weight: 700; padding: 0.25rem 0.7rem; border-radius: 50px; flex-shrink: 0; }
        
        body.dark-mode .corr-tag {
            background: #422d0b;
            color: #fbbf24;
        }

        /* ===== PHOTO ===== */
        .photo-wrap {
            text-align: center;
            padding: 0.5rem;
        }

        .photo-wrap img {
            max-width: 100%;
            max-height: 280px;
            border-radius: 10px;
            border: 2px solid #e2e8f0;
            object-fit: contain;
        }
        
        body.dark-mode .photo-wrap img {
            border-color: #334155;
        }

        .photo-none {
            background: #f8fafc;
            border: 2px dashed #cbd5e1;
            border-radius: 10px;
            padding: 2rem;
            text-align: center;
            color: #94a3b8;
            font-size: 0.85rem;
        }
        
        body.dark-mode .photo-none {
            background: #1e293b;
            border-color: #334155;
            color: #64748b;
        }

        /* ===== MATCHED BANNER ===== */
        .matched-banner {
            background: linear-gradient(135deg, #d1fae5, #a7f3d0);
            border: 2px solid #10b981;
            border-radius: 14px;
            padding: 1.2rem 1.5rem;
            display: flex;
            align-items: flex-start;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        body.dark-mode .matched-banner {
            background: linear-gradient(135deg, #0a3b2a, #065f46);
            border-color: #34d399;
        }

        .matched-icon { font-size: 1.8rem; flex-shrink: 0; }
        .matched-title { font-weight: 800; color: #065f46; font-size: 0.95rem; margin-bottom: 0.2rem; }
        .matched-text { color: #047857; font-size: 0.85rem; line-height: 1.5; }
        
        body.dark-mode .matched-title {
            color: #a7f3d0;
        }
        
        body.dark-mode .matched-text {
            color: #86efac;
        }

        /* ===== TIMELINE ===== */
        .timeline { display: flex; flex-direction: column; gap: 0; }

        .tl-item {
            display: flex;
            gap: 1rem;
            padding-bottom: 1.2rem;
            position: relative;
        }

        .tl-item:last-child { padding-bottom: 0; }

        .tl-item::before {
            content: '';
            position: absolute;
            left: 15px;
            top: 32px;
            bottom: 0;
            width: 2px;
            background: #f1f5f9;
        }
        
        body.dark-mode .tl-item::before {
            background: #334155;
        }

        .tl-item:last-child::before { display: none; }

        .tl-dot {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.9rem;
            flex-shrink: 0;
            z-index: 1;
        }

        .tl-dot-green  { background: #d1fae5; }
        .tl-dot-blue   { background: #dbeafe; }
        .tl-dot-gray   { background: #f1f5f9; }
        
        body.dark-mode .tl-dot-green  { background: #0a3b2a; }
        body.dark-mode .tl-dot-blue   { background: #1e3a5f; }
        body.dark-mode .tl-dot-gray   { background: #1e293b; }

        .tl-content { flex: 1; }
        .tl-label { font-weight: 700; color: #1e293b; font-size: 0.85rem; }
        
        body.dark-mode .tl-label {
            color: #cbd5e1;
        }
        
        .tl-date  { font-size: 0.75rem; color: #94a3b8; margin-top: 0.1rem; }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1200px) {
            .layout-grid { grid-template-columns: 1fr 1fr; }
        }

        @media (max-width: 1024px) {
            .sidebar { width: 100%; position: relative; height: auto; flex-direction: row; flex-wrap: wrap; }
            .main-content { margin-left: 0; }
            .layout-grid, .layout-grid-2 { grid-template-columns: 1fr; }
        }

        @media (max-width: 640px) {
            .main-content { padding: 1rem; }
            .hero-title { font-size: 1.3rem; }
        }
    </style>
</head>
<body>

@php
    $pendingCount = \App\Models\Perte::where('statut', 'en_attente')->count();
    $statut = $documentTrouve->statut ?? 'en_attente';
    $statutMap = [
        'en_attente' => ['label' => 'En attente', 'class' => 's-attente', 'icon' => '⏳', 'pulse' => true],
        'matche'     => ['label' => 'Propriétaire trouvé', 'class' => 's-matche', 'icon' => '🔗', 'pulse' => false],
        'restitue'   => ['label' => 'Restitué', 'class' => 's-restitue', 'icon' => '✅', 'pulse' => false],
        'archive'    => ['label' => 'Archivé', 'class' => 's-archive', 'icon' => '📦', 'pulse' => false],
    ];
    $s = $statutMap[$statut] ?? $statutMap['en_attente'];
    $docIcons = [
        'Passeport' => '🛂', "Carte d'identité (CNI)" => '🪪',
        'Permis de conduire' => '🚗', "Carte d'électeur" => '🗳️',
        'Acte de naissance' => '📋', 'Certificat de nationalité' => '📜',
    ];
    $docIcon = $docIcons[$documentTrouve->type_document] ?? '📄';
@endphp

<!-- ===== SIDEBAR AGENT ===== -->
<div class="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <div class="sidebar-logo-icon">🇹🇬</div>
            <div class="sidebar-logo-text">
                e-Déclaration TG
                <span>Espace Agent</span>
            </div>
        </div>
        <div class="agent-badge">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:12px;height:12px;">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            Agent Vérificateur
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-label">Navigation</div>
        <a href="{{ route('agent.dashboard') }}" class="{{ request()->routeIs('agent.dashboard') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Tableau de bord
        </a>

        <div class="nav-section-label">Déclarations</div>
        <a href="{{ route('agent.dashboard') }}" class="{{ request()->routeIs('agent.perte.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Déclarations de perte
            @if($pendingCount > 0)
                <span class="nav-badge">{{ $pendingCount }}</span>
            @endif
        </a>

        <a href="{{ route('agent.documents-trouves.index') }}" class="{{ request()->routeIs('agent.documents-trouves.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            Documents trouvés
        </a>
    </nav>

    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="btn-logout">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:16px;height:16px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Se déconnecter
            </button>
        </form>
    </div>
</div>

<!-- ===== MAIN CONTENT ===== -->
<div class="main-content">
    <div class="page-wrapper">

        <!-- Top Bar -->
        <div class="top-bar">
            <div class="top-bar-left">
                <a href="{{ route('agent.documents-trouves.index') }}" class="back-btn">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:16px;height:16px;">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    Documents trouvés
                </a>
                <div class="page-title">Dossier <span>#{{ $documentTrouve->numero_declaration ?? 'DT-'.str_pad($documentTrouve->id,5,'0',STR_PAD_LEFT) }}</span></div>
            </div>
            <div class="top-bar-right">
                <button class="icon-btn" id="themeToggleBtn" title="Thème">
                    <svg id="themeIcon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </button>
                <button class="icon-btn" onclick="window.location.href='{{ route('agent.dashboard') }}'" title="Notifications" style="position:relative;">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                    </svg>
                    @if($pendingCount > 0)
                        <span class="notif-dot">{{ $pendingCount }}</span>
                    @endif
                </button>
            </div>
        </div>

        {{-- Flash messages --}}
        @if(session('success'))
            <div class="flash flash-success"><span style="font-size:1.3rem;">✅</span> {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="flash flash-error"><span style="font-size:1.3rem;">❌</span> {{ session('error') }}</div>
        @endif

        {{-- Banner si matché --}}
        @if($statut === 'matche' && $documentTrouve->perteMatchee)
        <div class="matched-banner">
            <div class="matched-icon">🎉</div>
            <div>
                <div class="matched-title">Document associé à un propriétaire</div>
                <div class="matched-text">
                    Ce document a été rapproché de la déclaration de
                    <strong>{{ $documentTrouve->perteMatchee->first_name }} {{ $documentTrouve->perteMatchee->last_name }}</strong>
                    ({{ $documentTrouve->perteMatchee->type_piece }}, perdu le {{ \Carbon\Carbon::parse($documentTrouve->perteMatchee->date_perte)->format('d/m/Y') }}).
                    En attente de restitution physique.
                </div>
            </div>
        </div>
        @endif

        {{-- ===== HERO ===== --}}
        <div class="hero-card">
            <div class="hero-inner">
                <div class="hero-left">
                    <div class="hero-ref">Document Trouvé • {{ $documentTrouve->numero_declaration ?? 'DT-'.str_pad($documentTrouve->id,5,'0',STR_PAD_LEFT) }}</div>
                    <div class="hero-title">
                        {{ $documentTrouve->type_document }}
                        @if($documentTrouve->nom_sur_document)
                        <small>{{ $documentTrouve->nom_sur_document }} {{ $documentTrouve->prenom_sur_document }}</small>
                        @endif
                    </div>
                    <span class="statut-badge {{ $s['class'] }}">
                        <span class="statut-dot {{ $s['pulse'] ? 'pulse' : '' }}"></span>
                        {{ $s['icon'] }} {{ $s['label'] }}
                    </span>
                </div>
                <div class="hero-doc-icon">{{ $docIcon }}</div>
            </div>
            <div class="hero-meta-row">
                <div class="meta-item">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <div>
                        <div class="meta-label">Découvert le</div>
                        <div class="meta-value">{{ \Carbon\Carbon::parse($documentTrouve->date_decouverte)->format('d/m/Y') }}</div>
                    </div>
                </div>
                <div class="meta-item">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                    <div>
                        <div class="meta-label">Lieu</div>
                        <div class="meta-value">{{ $documentTrouve->lieu_decouverte }}</div>
                    </div>
                </div>
                <div class="meta-item">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div>
                        <div class="meta-label">Déclaré le</div>
                        <div class="meta-value">{{ \Carbon\Carbon::parse($documentTrouve->created_at)->format('d/m/Y à H:i') }}</div>
                    </div>
                </div>
                @if($documentTrouve->numero_document)
                <div class="meta-item">
                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/>
                    </svg>
                    <div>
                        <div class="meta-label">N° document</div>
                        <div class="meta-value">{{ $documentTrouve->numero_document }}</div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        {{-- ===== LAYOUT PRINCIPAL ===== --}}
        <div class="layout-grid-2">

            {{-- COLONNE GAUCHE --}}
            <div>

                {{-- Infos document --}}
                <div class="card" style="margin-bottom:1.5rem;">
                    <div class="card-header">
                        <div class="card-icon ci-blue">📄</div>
                        <div class="card-title">Informations du document</div>
                    </div>
                    <div class="info-list">
                        <div class="info-item">
                            <span class="ii-label">Type</span>
                            <span class="ii-value">{{ $documentTrouve->type_document }}</span>
                        </div>
                        <div class="info-item">
                            <span class="ii-label">Numéro</span>
                            <span class="ii-value {{ !$documentTrouve->numero_document ? 'ii-empty' : '' }}">
                                {{ $documentTrouve->numero_document ?? 'Non renseigné' }}
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="ii-label">Nom sur doc.</span>
                            <span class="ii-value {{ !$documentTrouve->nom_sur_document ? 'ii-empty' : '' }}">
                                {{ $documentTrouve->nom_sur_document ?? 'Non lisible' }}
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="ii-label">Prénom sur doc.</span>
                            <span class="ii-value {{ !$documentTrouve->prenom_sur_document ? 'ii-empty' : '' }}">
                                {{ $documentTrouve->prenom_sur_document ?? 'Non lisible' }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Infos déclarant --}}
                <div class="card" style="margin-bottom:1.5rem;">
                    <div class="card-header">
                        <div class="card-icon ci-green">👤</div>
                        <div class="card-title">Déclarant (a trouvé le document)</div>
                    </div>
                    <div class="info-list">
                        <div class="info-item">
                            <span class="ii-label">Nom complet</span>
                            <span class="ii-value">{{ $documentTrouve->nom_declarant }} {{ $documentTrouve->prenom_declarant }}</span>
                        </div>
                        <div class="info-item">
                            <span class="ii-label">Email</span>
                            <span class="ii-value" style="font-size:0.8rem;">
                                <a href="mailto:{{ $documentTrouve->email_declarant }}" style="color:#3b82f6;text-decoration:none;">
                                    {{ $documentTrouve->email_declarant }}
                                </a>
                            </span>
                        </div>
                        <div class="info-item">
                            <span class="ii-label">Téléphone</span>
                            <span class="ii-value">
                                <a href="tel:{{ $documentTrouve->telephone_declarant }}" style="color:#27ae60;text-decoration:none;">
                                    {{ $documentTrouve->telephone_declarant }}
                                </a>
                            </span>
                        </div>
                        @if($documentTrouve->user_id)
                        <div class="info-item">
                            <span class="ii-label">Compte</span>
                            <span class="ii-value" style="color:#27ae60;">✅ Utilisateur enregistré</span>
                        </div>
                        @else
                        <div class="info-item">
                            <span class="ii-label">Compte</span>
                            <span class="ii-value ii-empty">Déclarant anonyme</span>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- Circonstances --}}
                @if($documentTrouve->description || $documentTrouve->circonstances)
                <div class="card" style="margin-bottom:1.5rem;">
                    <div class="card-header">
                        <div class="card-icon ci-orange">📝</div>
                        <div class="card-title">Description & Circonstances</div>
                    </div>
                    @if($documentTrouve->description)
                    <div style="margin-bottom:1rem;">
                        <div style="font-size:0.72rem;color:#94a3b8;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:0.4rem;">État du document</div>
                        <p style="color:#374151;line-height:1.7;font-size:0.87rem;">{{ $documentTrouve->description }}</p>
                    </div>
                    @endif
                    @if($documentTrouve->circonstances)
                    <div>
                        <div style="font-size:0.72rem;color:#94a3b8;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:0.4rem;">Circonstances de la découverte</div>
                        <p style="color:#374151;line-height:1.7;font-size:0.87rem;">{{ $documentTrouve->circonstances }}</p>
                    </div>
                    @endif
                </div>
                @endif

                {{-- Photo --}}
                <div class="card">
                    <div class="card-header">
                        <div class="card-icon ci-purple">📷</div>
                        <div class="card-title">Photo du document</div>
                    </div>
                    @if($documentTrouve->photo_document)
                        @php $ext = strtolower(pathinfo($documentTrouve->photo_document, PATHINFO_EXTENSION)); @endphp
                        @if(in_array($ext, ['jpg','jpeg','png','gif','webp']))
                            <div class="photo-wrap">
                                <img src="{{ Storage::url($documentTrouve->photo_document) }}" alt="Photo">
                            </div>
                            <div style="text-align:center;margin-top:0.8rem;">
                                <a href="{{ Storage::url($documentTrouve->photo_document) }}" target="_blank"
                                   style="font-size:0.8rem;color:#3b82f6;font-weight:600;text-decoration:none;">
                                    🔍 Voir en plein écran
                                </a>
                            </div>
                        @else
                            <div style="text-align:center;padding:1rem;">
                                <a href="{{ Storage::url($documentTrouve->photo_document) }}" target="_blank"
                                   style="display:inline-flex;align-items:center;gap:0.5rem;background:#dbeafe;padding:0.8rem 1.5rem;border-radius:10px;color:#2563eb;font-weight:700;text-decoration:none;">
                                    📎 Ouvrir le PDF
                                </a>
                            </div>
                        @endif
                    @else
                        <div class="photo-none">
                            <div style="font-size:2rem;margin-bottom:0.5rem;">📷</div>
                            Aucune photo fournie
                        </div>
                    @endif
                </div>

            </div>{{-- fin colonne gauche --}}

            {{-- COLONNE DROITE --}}
            <div>

                {{-- ===== PANNEAU ACTIONS AGENT ===== --}}
                <div class="actions-panel" style="margin-bottom:1.5rem;">
                    <div class="actions-title">⚡ Actions Agent</div>

                    {{-- Matcher --}}
                    @if($statut === 'en_attente')
                        <button class="action-btn btn-match" onclick="openMatchModal()">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                            </svg>
                            Associer à un propriétaire
                        </button>
                    @elseif($statut === 'matche')
                        <button class="action-btn btn-disabled" disabled>
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                            </svg>
                            Déjà associé
                        </button>
                    @else
                        <button class="action-btn btn-disabled" disabled>
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101"/>
                            </svg>
                            Associer — Non disponible
                        </button>
                    @endif

                    {{-- Restituer --}}
                    @if($statut === 'matche')
                        <form method="POST" action="{{ route('agent.documents-trouves.restituer', $documentTrouve->id) }}"
                              onsubmit="return confirm('Confirmer la restitution du document ?')">
                            @csrf
                            <button type="submit" class="action-btn btn-restitue">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Marquer comme restitué
                            </button>
                        </form>
                    @else
                        <button class="action-btn btn-disabled" disabled>
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Restituer — {{ $statut === 'restitue' ? 'Déjà restitué' : 'Associer d\'abord' }}
                        </button>
                    @endif

                    {{-- Archiver --}}
                    @if($statut !== 'archive' && $statut !== 'restitue')
                        <form method="POST" action="{{ route('agent.documents-trouves.destroy', $documentTrouve->id) }}"
                              onsubmit="return confirm('Archiver ce document trouvé ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="action-btn btn-archive">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                </svg>
                                Archiver le dossier
                            </button>
                        </form>
                    @endif
                </div>

                {{-- ===== TIMELINE ===== --}}
                <div class="card" style="margin-bottom:1.5rem;">
                    <div class="card-header">
                        <div class="card-icon ci-yellow">📅</div>
                        <div class="card-title">Historique du dossier</div>
                    </div>
                    <div class="timeline">
                        <div class="tl-item">
                            <div class="tl-dot tl-dot-green">📦</div>
                            <div class="tl-content">
                                <div class="tl-label">Document déclaré trouvé</div>
                                <div class="tl-date">{{ \Carbon\Carbon::parse($documentTrouve->created_at)->format('d/m/Y à H:i') }}</div>
                            </div>
                        </div>
                        @if($statut !== 'en_attente')
                        <div class="tl-item">
                            <div class="tl-dot tl-dot-blue">🔗</div>
                            <div class="tl-content">
                                <div class="tl-label">Propriétaire potentiel identifié</div>
                                <div class="tl-date">
                                    @if($documentTrouve->updated_at)
                                        {{ \Carbon\Carbon::parse($documentTrouve->updated_at)->format('d/m/Y à H:i') }}
                                    @else
                                        Date inconnue
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
                        @if($statut === 'restitue')
                        <div class="tl-item">
                            <div class="tl-dot tl-dot-green">✅</div>
                            <div class="tl-content">
                                <div class="tl-label">Document restitué au propriétaire</div>
                                <div class="tl-date">
                                    {{ $documentTrouve->date_restitution
                                        ? \Carbon\Carbon::parse($documentTrouve->date_restitution)->format('d/m/Y à H:i')
                                        : 'Date enregistrée' }}
                                </div>
                            </div>
                        </div>
                        @else
                        <div class="tl-item">
                            <div class="tl-dot tl-dot-gray" style="opacity:0.4;">⏳</div>
                            <div class="tl-content">
                                <div class="tl-label" style="color:#94a3b8;">Restitution (en attente)</div>
                                <div class="tl-date">—</div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>

                {{-- ===== CORRESPONDANCES ===== --}}
                <div class="card">
                    <div class="card-header">
                        <div class="card-icon ci-red">🔍</div>
                        <div class="card-title">
                            Correspondances
                            @if($correspondances->count() > 0)
                                <span style="background:#fee2e2;color:#dc2626;font-size:0.7rem;font-weight:700;padding:0.2rem 0.6rem;border-radius:50px;margin-left:0.5rem;">
                                    {{ $correspondances->count() }}
                                </span>
                            @endif
                        </div>
                    </div>

                    @if($correspondances->count() > 0)
                        @foreach($correspondances as $perte)
                        <div class="corr-item" onclick="selectPerte({{ $perte->id }}, '{{ $perte->first_name }} {{ $perte->last_name }}')">
                            <div>
                                <div class="corr-name">{{ $perte->first_name }} {{ $perte->last_name }}</div>
                                <div class="corr-detail">
                                    {{ $perte->type_piece }} •
                                    Perdu le {{ \Carbon\Carbon::parse($perte->date_perte)->format('d/m/Y') }}
                                    @if($perte->lieu_perte) • {{ $perte->lieu_perte }} @endif
                                    @if($perte->numero_piece) • N° {{ $perte->numero_piece }} @endif
                                </div>
                            </div>
                            <span class="corr-tag">Possible</span>
                        </div>
                        @endforeach
                        <p style="font-size:0.75rem;color:#94a3b8;margin-top:0.8rem;padding-top:0.8rem;border-top:1px solid #f1f5f9;">
                            Cliquez sur une correspondance pour pré-sélectionner et ouvrir le formulaire de matching.
                        </p>
                    @else
                        <div style="text-align:center;padding:1.5rem;color:#94a3b8;">
                            <div style="font-size:2rem;margin-bottom:0.4rem;">🔎</div>
                            <div style="font-size:0.82rem;">Aucune déclaration de perte correspondante trouvée.</div>
                        </div>
                    @endif
                </div>

            </div>{{-- fin colonne droite --}}
        </div>

    </div>{{-- /page-wrapper --}}
</div>{{-- /main-content --}}

{{-- ===== MODAL MATCHING ===== --}}
<div class="modal-overlay" id="matchModal">
    <div class="modal">
        <div class="modal-header">
            <div class="modal-title">🔗 Associer à un propriétaire</div>
            <button class="modal-close" onclick="closeMatchModal()">✕</button>
        </div>

        <p class="modal-subtitle">
            Sélectionnez la déclaration de perte qui correspond à ce document trouvé.
            Le propriétaire sera automatiquement notifié avec vos coordonnées.
        </p>

        <form method="POST" action="{{ route('agent.documents-trouves.matcher', $documentTrouve->id) }}">
            @csrf

            @if($correspondances->count() > 0)
                <div id="perteOptions">
                    @foreach($correspondances as $perte)
                    <label class="perte-option" id="opt-{{ $perte->id }}">
                        <input type="radio" name="perte_id" value="{{ $perte->id }}" required>
                        <div class="perte-option-info">
                            <div class="perte-option-name">{{ $perte->first_name }} {{ $perte->last_name }}</div>
                            <div class="perte-option-detail">
                                {{ $perte->type_piece }} •
                                Perte le {{ \Carbon\Carbon::parse($perte->date_perte)->format('d/m/Y') }}
                                @if($perte->lieu_perte) • {{ $perte->lieu_perte }} @endif
                                @if($perte->numero_piece) • N° {{ $perte->numero_piece }} @endif
                            </div>
                        </div>
                    </label>
                    @endforeach
                </div>
            @else
                <div class="modal-empty">
                    <div class="modal-empty-icon">🔎</div>
                    <div>Aucune déclaration de perte correspondante.</div>
                    <div style="font-size:0.8rem;margin-top:0.5rem;color:#cbd5e1;">
                        Vous pouvez entrer manuellement un identifiant de perte.
                    </div>
                </div>
            @endif

            {{-- Saisie manuelle --}}
            <div style="margin-top:1rem;padding-top:1rem;border-top:1px solid #f1f5f9;">
                <div style="font-size:0.78rem;color:#64748b;font-weight:600;margin-bottom:0.5rem;">
                    Ou saisir manuellement l'ID de la déclaration de perte :
                </div>
                <input type="number" name="perte_id_manual" id="perteIdManual"
                       placeholder="Ex: 42"
                       style="width:100%;padding:0.7rem;border:2px solid #e2e8f0;border-radius:8px;font-size:0.9rem;font-family:'Poppins',sans-serif;"
                       onfocus="document.querySelectorAll('input[name=perte_id]').forEach(r=>r.checked=false)">
                <div style="font-size:0.73rem;color:#94a3b8;margin-top:0.3rem;">
                    Cette valeur sera utilisée si aucune option ci-dessus n'est sélectionnée.
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" class="btn-modal-cancel" onclick="closeMatchModal()">Annuler</button>
                <button type="submit" class="btn-modal-confirm">✅ Confirmer l'association</button>
            </div>
        </form>
    </div>
</div>

<script>
    // Ouvrir/fermer modal
    function openMatchModal() {
        document.getElementById('matchModal').classList.add('open');
    }

    function closeMatchModal() {
        document.getElementById('matchModal').classList.remove('open');
    }

    // Clic sur une correspondance → pré-sélectionne dans le modal
    function selectPerte(id, nom) {
        openMatchModal();
        setTimeout(() => {
            const radio = document.querySelector(`input[name="perte_id"][value="${id}"]`);
            if (radio) {
                radio.checked = true;
                radio.closest('.perte-option').style.borderColor = '#3b82f6';
                radio.closest('.perte-option').style.background = '#eff6ff';
                radio.closest('.perte-option').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        }, 100);
    }

    // Fermer modal si clic outside
    document.getElementById('matchModal').addEventListener('click', function(e) {
        if (e.target === this) closeMatchModal();
    });

    // Gestion perte_id : si manual rempli → écrase la sélection radio
    const modalForm = document.querySelector('#matchModal form');
    if (modalForm) {
        modalForm.addEventListener('submit', function(e) {
            const manual = document.getElementById('perteIdManual')?.value;
            if (manual) {
                document.querySelectorAll('input[name="perte_id"]').forEach(r => r.removeAttribute('required'));
                const hidden = document.createElement('input');
                hidden.type = 'hidden';
                hidden.name = 'perte_id';
                hidden.value = manual;
                this.appendChild(hidden);
            }
        });
    }

    // ===================== GESTION DU THÈME =====================
    function applyTheme(isDark) {
        if (isDark) {
            document.body.classList.add('dark-mode');
            const icon = document.getElementById('themeIcon');
            if (icon) {
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>';
            }
        } else {
            document.body.classList.remove('dark-mode');
            const icon = document.getElementById('themeIcon');
            if (icon) {
                icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>';
            }
        }
        localStorage.setItem('darkMode', isDark ? 'dark' : 'light');
    }

    function loadTheme() {
        const serverTheme = '{{ auth()->user()->theme ?? "light" }}';
        const localTheme = localStorage.getItem('darkMode');
        const isDark = (serverTheme === 'dark') || (localTheme === 'dark');
        applyTheme(isDark);
    }

    function toggleGlobalDarkMode() {
        const isDark = !document.body.classList.contains('dark-mode');
        applyTheme(isDark);
        fetch('{{ route("profile.toggle-dark-mode") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ dark_mode: isDark })
        }).catch(e => console.log('Erreur serveur:', e));
    }

    document.addEventListener('DOMContentLoaded', function() {
        loadTheme();
        
        const themeBtn = document.getElementById('themeToggleBtn');
        if (themeBtn) {
            themeBtn.addEventListener('click', toggleGlobalDarkMode);
        }
    });
</script>
</body>
</html>