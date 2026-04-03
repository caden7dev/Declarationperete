<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dossier Document Trouvé — Agent | e-Déclaration TG</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { 
            box-sizing: border-box; 
            margin: 0; 
            padding: 0; 
            font-family: 'Nunito', sans-serif;
        }

        body { 
            display: flex; 
            min-height: 100vh; 
            background: #f5f7fa;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: white;
            box-shadow: 2px 0 15px rgba(0,0,0,0.08);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 10;
        }

        .sidebar-header {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid #e8eef5;
        }

        .sidebar-header h2 { 
            font-size: 1.3rem;
            font-weight: 800;
            display: flex; 
            align-items: center; 
            gap: 0.8rem;
            color: #1e3a5f;
        }

        .agent-badge {
            background: linear-gradient(135deg, #f39c12, #f1c40f);
            color: white;
            padding: 0.3rem 0.8rem;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 700;
            margin-top: 0.5rem;
            display: inline-block;
        }

        .sidebar-nav {
            flex: 1;
            padding: 1.5rem 1rem;
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
            overflow-y: auto;
        }

        .sidebar-nav a {
            text-decoration: none;
            color: #64748b;
            font-weight: 600;
            padding: 0.9rem 1.2rem;
            border-radius: 10px;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            transition: all 0.2s;
            font-size: 0.95rem;
            position: relative;
        }

        .sidebar-nav a:hover {
            background: #f1f5f9;
            color: #f39c12;
        }

        .sidebar-nav a.active {
            background: linear-gradient(135deg, rgba(243, 156, 18, 0.1), rgba(241, 196, 15, 0.05));
            color: #f39c12;
            font-weight: 700;
            border: 2px solid #f39c12;
        }

        .sidebar-nav a svg {
            width: 20px;
            height: 20px;
        }

        /* Badge de notification dans sidebar */
        .nav-badge {
            margin-left: auto;
            background: #e74c3c;
            color: white;
            padding: 0.2rem 0.6rem;
            border-radius: 10px;
            font-size: 0.75rem;
            font-weight: 700;
            animation: pulse 2s infinite;
        }

        .nav-badge.orange {
            background: #f39c12;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }

        .sidebar-footer {
            padding: 1.5rem 1rem;
            border-top: 1px solid #e8eef5;
        }

        .btn-logout {
            width: 100%;
            background: #fff1f0;
            color: #e74c3c;
            padding: 0.9rem;
            border: none;
            border-radius: 10px;
            font-size: 0.95rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .btn-logout:hover {
            background: #ffe8e6;
        }

        /* Main */
        .main {
            margin-left: 280px;
            flex: 1;
            background: #f5f7fa;
            min-height: 100vh;
        }

        /* Top Bar */
        .top-bar {
            background: white;
            padding: 1.5rem 2.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 5;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .top-bar-left h1 {
            font-size: 1.75rem;
            font-weight: 800;
            color: #1e3a5f;
            margin-bottom: 0.3rem;
        }

        .top-bar-left p {
            color: #64748b;
            font-size: 0.95rem;
        }

        .top-bar-right {
            display: flex;
            gap: 1rem;
            align-items: center;
        }

        .notification-badge {
            position: relative;
            background: #f8f9fa;
            width: 45px;
            height: 45px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }

        .notification-badge:hover {
            background: #e9ecef;
            transform: scale(1.05);
        }

        .notification-count {
            position: absolute;
            top: -5px;
            right: -5px;
            background: #e74c3c;
            color: white;
            font-size: 0.7rem;
            padding: 0.2rem 0.5rem;
            border-radius: 10px;
            font-weight: 700;
            animation: pulse 2s infinite;
        }

        /* Content */
        .content {
            padding: 2.5rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        /* Alert */
        .alert {
            padding: 1rem 1.5rem;
            border-radius: 12px;
            margin-bottom: 2rem;
            display: flex;
            align-items: center;
            gap: 0.8rem;
            animation: slideDown 0.3s;
        }

        .alert-success {
            background: #d4edda;
            color: #155724;
            border-left: 4px solid #27ae60;
        }

        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border-left: 4px solid #e74c3c;
        }

        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Page Header */
        .page-header {
            background: white;
            padding: 2rem;
            border-radius: 16px;
            margin-bottom: 2rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .page-header h1 {
            font-size: 1.8rem;
            font-weight: 800;
            color: #1e3a5f;
            display: flex;
            align-items: center;
            gap: 0.8rem;
        }

        .back-btn {
            background: #f1f5f9;
            color: #64748b;
            padding: 0.8rem 1.5rem;
            border-radius: 10px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .back-btn:hover {
            background: #e2e8f0;
        }

        /* Statut pills */
        .statut-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.35rem 0.9rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .sp-dot { width: 6px; height: 6px; border-radius: 50%; background: currentColor; }
        .sp-dot.pulse { animation: pulse 1.8s infinite; }

        .sp-attente  { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
        .sp-matche   { background: #dbeafe; color: #1d4ed8; border: 1px solid #bfdbfe; }
        .sp-restitue { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .sp-archive  { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }

        /* Matched banner */
        .matched-banner {
            background: linear-gradient(135deg, #ecfdf5, #d1fae5);
            border: 1.5px solid #6ee7b7;
            border-radius: 14px;
            padding: 1.1rem 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.75rem;
            position: relative;
            overflow: hidden;
        }

        .matched-banner::before {
            content: '';
            position: absolute;
            left: 0; top: 0; bottom: 0;
            width: 4px;
            background: #27ae60;
        }

        .mb-icon { font-size: 1.6rem; flex-shrink: 0; }
        .mb-title { font-weight: 700; color: #065f46; font-size: 0.9rem; }
        .mb-text { font-size: 0.8rem; color: #047857; margin-top: 0.15rem; line-height: 1.5; }

        /* Stats Row */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 1rem 1.2rem;
            display: flex;
            align-items: center;
            gap: 0.85rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }

        .stat-icon {
            width: 40px;
            height: 40px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .stat-label { font-size: 0.7rem; color: #64748b; font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px; }
        .stat-value { font-size: 1.2rem; font-weight: 800; color: #1e3a5f; line-height: 1; margin-top: 0.15rem; }

        /* Main Grid */
        .main-grid {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 1.5rem;
            align-items: start;
        }

        /* Cards */
        .card {
            background: white;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            margin-bottom: 1.25rem;
            transition: all 0.2s;
        }

        .card:last-child { margin-bottom: 0; }
        .card:hover { box-shadow: 0 8px 20px rgba(0,0,0,0.1); }

        .card-head {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1.1rem 1.4rem;
            border-bottom: 1px solid #e2e8f0;
            background: #f8fafc;
        }

        .card-head-icon {
            width: 34px; height: 34px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.95rem;
            flex-shrink: 0;
        }

        .chi-blue   { background: linear-gradient(135deg,#3b82f6,#1d4ed8); color: white; }
        .chi-green  { background: linear-gradient(135deg,#10b981,#059669); color: white; }
        .chi-amber  { background: linear-gradient(135deg,#f59e0b,#d97706); color: white; }
        .chi-purple { background: linear-gradient(135deg,#8b5cf6,#7c3aed); color: white; }
        .chi-red    { background: linear-gradient(135deg,#ef4444,#dc2626); color: white; }
        .chi-gray   { background: linear-gradient(135deg,#64748b,#475569); color: white; }

        .card-title { font-weight: 700; font-size: 0.88rem; color: #1e3a5f; }
        .card-subtitle { font-size: 0.75rem; color: #64748b; margin-top: 0.1rem; }

        .card-body { padding: 1.25rem 1.4rem; }

        /* Info rows */
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.55rem 0;
            border-bottom: 1px solid #e2e8f0;
            gap: 1rem;
        }

        .info-row:last-child { border-bottom: none; padding-bottom: 0; }

        .ir-label { font-size: 0.76rem; color: #64748b; font-weight: 500; flex-shrink: 0; }
        .ir-value { font-size: 0.85rem; color: #1e3a5f; font-weight: 600; text-align: right; }
        .ir-empty { color: #cbd5e1; font-style: italic; font-weight: 400; }

        .ir-link { color: #3b82f6; text-decoration: none; font-weight: 600; }
        .ir-link:hover { text-decoration: underline; }

        /* 2-col grid inside cards */
        .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }

        /* Photo */
        .photo-wrap { text-align: center; padding: 0.5rem; }

        .photo-wrap img {
            max-width: 100%;
            max-height: 260px;
            border-radius: 10px;
            border: 2px solid #e2e8f0;
            object-fit: contain;
        }

        .photo-empty {
            background: #f8fafc;
            border: 2px dashed #e2e8f0;
            border-radius: 10px;
            padding: 2rem;
            text-align: center;
            color: #64748b;
        }

        /* Correspondances */
        .corr-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.9rem 1rem;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            margin-bottom: 0.7rem;
            cursor: pointer;
            transition: all 0.18s;
            gap: 0.75rem;
        }

        .corr-item:last-child { margin-bottom: 0; }

        .corr-item:hover {
            border-color: #3b82f6;
            background: #f0f5ff;
            transform: translateX(2px);
        }

        .corr-item.selected {
            border-color: #3b82f6;
            background: #eff6ff;
        }

        .corr-name { font-weight: 700; color: #1e3a5f; font-size: 0.85rem; }
        .corr-detail { font-size: 0.74rem; color: #64748b; margin-top: 0.15rem; line-height: 1.4; }

        .corr-tag {
            background: #fef3c7;
            color: #b45309;
            font-size: 0.65rem;
            font-weight: 700;
            padding: 0.2rem 0.55rem;
            border-radius: 50px;
            white-space: nowrap;
            flex-shrink: 0;
        }

        /* Timeline */
        .tl { display: flex; flex-direction: column; }

        .tl-item {
            display: flex;
            gap: 0.85rem;
            position: relative;
            padding-bottom: 1.1rem;
        }

        .tl-item:last-child { padding-bottom: 0; }

        .tl-line {
            position: absolute;
            left: 14px;
            top: 30px;
            bottom: 0;
            width: 1.5px;
            background: #e2e8f0;
        }

        .tl-item:last-child .tl-line { display: none; }

        .tl-dot {
            width: 30px; height: 30px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.8rem;
            flex-shrink: 0;
            z-index: 1;
            border: 2px solid white;
        }

        .tl-done { background: #d1fae5; }
        .tl-current { background: #dbeafe; }
        .tl-pending { background: #e2e8f0; opacity: 0.5; }

        .tl-label { font-size: 0.83rem; font-weight: 600; color: #1e3a5f; }
        .tl-date  { font-size: 0.72rem; color: #64748b; margin-top: 0.1rem; }

        /* Actions card */
        .actions-card {
            background: #1e3a5f;
            border-radius: 16px;
            overflow: hidden;
            margin-bottom: 1.25rem;
            box-shadow: 0 8px 32px rgba(15,31,61,0.25);
        }

        .ac-head {
            padding: 1.1rem 1.4rem 0.9rem;
            border-bottom: 1px solid rgba(255,255,255,0.08);
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }

        .ac-head-label {
            font-size: 0.65rem;
            font-weight: 600;
            color: rgba(255,255,255,0.35);
            text-transform: uppercase;
            letter-spacing: 2px;
        }

        .ac-head-dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: #10b981;
            animation: pulse 1.8s infinite;
        }

        .ac-body { padding: 1.1rem; }

        .ac-btn {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            width: 100%;
            padding: 0.85rem 1rem;
            border-radius: 10px;
            border: none;
            font-size: 0.84rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            text-align: left;
            margin-bottom: 0.6rem;
        }

        .ac-btn:last-child { margin-bottom: 0; }
        .ac-btn svg { width: 17px; height: 17px; flex-shrink: 0; }

        .ac-btn-primary {
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            box-shadow: 0 4px 14px rgba(59,130,246,0.35);
        }

        .ac-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 7px 20px rgba(59,130,246,0.45);
        }

        .ac-btn-success {
            background: linear-gradient(135deg, #10b981, #059669);
            color: white;
            box-shadow: 0 4px 14px rgba(16,185,129,0.3);
        }

        .ac-btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 7px 20px rgba(16,185,129,0.4);
        }

        .ac-btn-ghost {
            background: rgba(255,255,255,0.06);
            color: rgba(255,255,255,0.5);
            border: 1px solid rgba(255,255,255,0.08);
        }

        .ac-btn-ghost:hover {
            background: rgba(255,255,255,0.1);
            color: rgba(255,255,255,0.75);
        }

        .ac-btn-disabled {
            background: rgba(255,255,255,0.03);
            color: rgba(255,255,255,0.2);
            cursor: not-allowed;
            border: 1px dashed rgba(255,255,255,0.08);
        }

        .ac-btn-label { flex: 1; }
        .ac-btn-desc { font-size: 0.67rem; font-weight: 400; opacity: 0.7; display: block; margin-top: 0.05rem; }

        .ac-divider {
            border: none;
            border-top: 1px solid rgba(255,255,255,0.07);
            margin: 0.75rem 0;
        }

        .ac-corr-hint {
            font-size: 0.72rem;
            color: rgba(255,255,255,0.3);
            padding: 0.5rem;
            text-align: center;
        }

        /* Right panel sticky */
        .right-panel { position: sticky; top: 78px; }

        /* Modal */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(5px);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .modal-overlay.open { display: flex; }

        .modal {
            background: white;
            border-radius: 18px;
            width: 100%;
            max-width: 560px;
            max-height: 88vh;
            overflow-y: auto;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            animation: modalIn 0.3s;
        }

        @keyframes modalIn {
            from { opacity: 0; transform: scale(0.95) translateY(20px); }
            to { opacity: 1; transform: scale(1) translateY(0); }
        }

        .modal-top {
            padding: 1.5rem 1.5rem 1.25rem;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            position: sticky;
            top: 0;
            background: white;
            z-index: 2;
        }

        .modal-top-icon {
            width: 44px; height: 44px;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            border-radius: 11px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
            color: white;
            flex-shrink: 0;
        }

        .modal-title { font-size: 1.15rem; font-weight: 800; color: #1e3a5f; }
        .modal-subtitle { font-size: 0.78rem; color: #64748b; margin-top: 0.2rem; line-height: 1.5; }

        .modal-close-btn {
            width: 32px; height: 32px;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            color: #64748b;
            font-size: 1rem;
            flex-shrink: 0;
            transition: all 0.15s;
        }

        .modal-close-btn:hover { background: #e2e8f0; }

        .modal-body { padding: 1.4rem 1.5rem; }

        /* Notification preview */
        .notif-preview {
            background: #f0f5ff;
            border: 1.5px solid #bfdbfe;
            border-radius: 12px;
            padding: 1rem 1.2rem;
            margin-bottom: 1.25rem;
        }

        .np-header {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.6rem;
        }

        .np-header-label {
            font-size: 0.7rem;
            font-weight: 700;
            color: #3b82f6;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .np-bell { font-size: 1rem; }

        .np-title { font-weight: 700; color: #1e3a5f; font-size: 0.85rem; margin-bottom: 0.3rem; }
        .np-text { font-size: 0.78rem; color: #475569; line-height: 1.55; }

        /* Perte options */
        .perte-section-label {
            font-size: 0.72rem;
            font-weight: 700;
            color: #64748b;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.6rem;
        }

        .perte-option {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.9rem 1rem;
            border: 2px solid #e2e8f0;
            border-radius: 11px;
            margin-bottom: 0.6rem;
            cursor: pointer;
            transition: all 0.18s;
        }

        .perte-option:hover { border-color: #3b82f6; background: #f0f5ff; }
        .perte-option.checked { border-color: #3b82f6; background: #eff6ff; }

        .perte-option input[type="radio"] {
            width: 16px; height: 16px;
            accent-color: #3b82f6;
            flex-shrink: 0;
        }

        .po-name { font-weight: 700; color: #1e3a5f; font-size: 0.85rem; }
        .po-detail { font-size: 0.74rem; color: #64748b; margin-top: 0.15rem; line-height: 1.4; }

        /* Manual input */
        .manual-input-wrap {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #e2e8f0;
        }

        .manual-label {
            font-size: 0.74rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 0.5rem;
        }

        .manual-input {
            width: 100%;
            padding: 0.7rem 0.9rem;
            border: 2px solid #e2e8f0;
            border-radius: 9px;
            font-size: 0.88rem;
            transition: border-color 0.15s;
            color: #1e3a5f;
        }

        .manual-input:focus {
            outline: none;
            border-color: #3b82f6;
        }

        .manual-hint { font-size: 0.7rem; color: #64748b; margin-top: 0.35rem; }

        /* Modal footer */
        .modal-footer {
            padding: 1.1rem 1.5rem;
            border-top: 1px solid #e2e8f0;
            display: flex;
            gap: 0.75rem;
            background: #f8fafc;
            border-radius: 0 0 18px 18px;
        }

        .btn-confirm {
            flex: 1;
            background: linear-gradient(135deg, #3b82f6, #1d4ed8);
            color: white;
            padding: 0.8rem;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.88rem;
            cursor: pointer;
            transition: all 0.2s;
            display: flex; align-items: center; justify-content: center; gap: 0.5rem;
        }

        .btn-confirm:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(59,130,246,0.35);
        }

        .btn-cancel {
            padding: 0.8rem 1.4rem;
            background: white;
            color: #64748b;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.15s;
        }

        .btn-cancel:hover { background: #f1f5f9; }

        /* Empty states */
        .empty-state {
            text-align: center;
            padding: 2rem 1rem;
            color: #64748b;
        }

        .es-icon { font-size: 2rem; margin-bottom: 0.5rem; }
        .es-text { font-size: 0.82rem; line-height: 1.6; }

        /* Responsive */
        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); }
            .main { margin-left: 0; }
            .main-grid { grid-template-columns: 1fr; }
            .right-panel { position: static; }
            .stats-row { grid-template-columns: 1fr 1fr; }
            .content { padding: 1rem; }
        }

        @media (max-width: 640px) {
            .top-bar { padding: 1rem; }
            .stats-row { grid-template-columns: 1fr; }
            .two-col { grid-template-columns: 1fr; }
        }

        /* Dark mode */
        body.dark-mode {
            background: #0a1220;
            color: #e2e8f0;
        }

        body.dark-mode .sidebar { background: #101e36; border-color: #1c2d4a; }
        body.dark-mode .sidebar-header { border-color: #1c2d4a; }
        body.dark-mode .sidebar-header h2 { color: #e2e8f0; }
        body.dark-mode .sidebar-nav a { color: #94a3b8; }
        body.dark-mode .sidebar-nav a:hover { background: #1c2d4a; color: #f39c12; }
        body.dark-mode .sidebar-nav a.active { background: rgba(243,156,18,0.1); color: #f39c12; }
        body.dark-mode .sidebar-footer { border-color: #1c2d4a; }
        body.dark-mode .btn-logout { background: rgba(239,68,68,0.1); color: #f87171; }

        body.dark-mode .main { background: #0a1220; }
        body.dark-mode .top-bar { background: #101e36; border-color: #1c2d4a; }
        body.dark-mode .top-bar-left h1 { color: #e2e8f0; }
        body.dark-mode .notification-badge { background: #1c2d4a; }
        body.dark-mode .tb-agent-info { background: #1c2d4a; border-color: #2d4166; }
        body.dark-mode .tb-agent-name { color: #e2e8f0; }

        body.dark-mode .card { background: #101e36; border-color: #1c2d4a; }
        body.dark-mode .card-head { background: #162038; border-color: #1c2d4a; }
        body.dark-mode .card-title { color: #e2e8f0; }
        body.dark-mode .info-row { border-color: #1c2d4a; }
        body.dark-mode .ir-value { color: #e2e8f0; }
        body.dark-mode .stat-card { background: #101e36; border-color: #1c2d4a; }
        body.dark-mode .stat-value { color: #e2e8f0; }
        body.dark-mode .stat-label { color: #94a3b8; }

        body.dark-mode .photo-empty { background: #162038; border-color: #1c2d4a; }
        body.dark-mode .corr-item { border-color: #1c2d4a; background: #101e36; }
        body.dark-mode .corr-item:hover { border-color: #3b82f6; background: #162038; }
        body.dark-mode .tl-line { background: #1c2d4a; }
        body.dark-mode .modal { background: #101e36; }
        body.dark-mode .modal-top { background: #101e36; border-color: #1c2d4a; }
        body.dark-mode .modal-title { color: #e2e8f0; }
        body.dark-mode .modal-footer { background: #162038; border-color: #1c2d4a; }
        body.dark-mode .btn-cancel { background: #101e36; border-color: #1c2d4a; color: #94a3b8; }
        body.dark-mode .perte-option { border-color: #1c2d4a; background: #101e36; }
        body.dark-mode .perte-option:hover { background: #162038; }
        body.dark-mode .po-name { color: #e2e8f0; }
        body.dark-mode .manual-input { background: #162038; border-color: #1c2d4a; color: #e2e8f0; }
    </style>
</head>
<body>

@php
    $pendingCount  = \App\Models\Perte::where('statut','en_attente')->count();
    $statut        = $documentTrouve->statut ?? 'en_attente';
    $sMap = [
        'en_attente' => ['label'=>'En attente',          'class'=>'sp-attente',  'icon'=>'⏳', 'pulse'=>true],
        'matche'     => ['label'=>'Propriétaire trouvé', 'class'=>'sp-matche',   'icon'=>'🔗', 'pulse'=>false],
        'restitue'   => ['label'=>'Restitué',             'class'=>'sp-restitue', 'icon'=>'✅', 'pulse'=>false],
        'archive'    => ['label'=>'Archivé',              'class'=>'sp-archive',  'icon'=>'📦', 'pulse'=>false],
    ];
    $s = $sMap[$statut] ?? $sMap['en_attente'];
    $docIcons = ['Passeport'=>'🛂',"Carte d'identité (CNI)"=>'🪪','Permis de conduire'=>'🚗',"Carte d'électeur"=>'🗳️','Acte de naissance'=>'📋','Certificat de nationalité'=>'📜'];
    $docIcon = $docIcons[$documentTrouve->type_document] ?? '📄';
    $agentInitials = strtoupper(substr(auth()->user()->first_name ?? auth()->user()->name, 0, 1) . substr(auth()->user()->last_name ?? '', 0, 1));
    $correspCount  = $correspondances->count();
@endphp

<!-- SIDEBAR -->
<div class="sidebar">
    <div class="sidebar-header">
        <h2>
            <span>🇹🇬</span> 
            e-Déclaration TG
        </h2>
        <div class="agent-badge">👮 AGENT</div>
    </div>

    <nav class="sidebar-nav">
        <a href="{{ route('agent.dashboard') }}" class="{{ request()->routeIs('agent.dashboard') && !request('statut') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Dashboard
        </a>

        <a href="{{ route('agent.dashboard', ['statut' => 'en_attente']) }}" class="{{ request('statut') == 'en_attente' ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Déclarations Perte
            @if($pendingCount > 0)
                <span class="nav-badge">{{ $pendingCount }}</span>
            @endif
        </a>

        <a href="{{ route('agent.documents-trouves.index') }}" class="active">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            Documents Trouvés
        </a>

        <a href="{{ route('agent.dashboard', ['statut' => 'validee']) }}" class="{{ request('statut') == 'validee' ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Validées
        </a>

        <a href="{{ route('agent.dashboard', ['statut' => 'rejetee']) }}" class="{{ request('statut') == 'rejetee' ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            Rejetées
        </a>
    </nav>

    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Êtes-vous sûr de vouloir vous déconnecter ?')">
            @csrf
            <button type="submit" class="btn-logout">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:18px;height:18px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Se déconnecter
            </button>
        </form>
    </div>
</div>

<!-- MAIN -->
<div class="main">
    <header class="top-bar">
        <div class="top-bar-left">
            <h1>📦 Document trouvé</h1>
            <p>{{ $documentTrouve->numero_declaration ?? 'DT-'.str_pad($documentTrouve->id,5,'0',STR_PAD_LEFT) }}</p>
        </div>
        <div class="top-bar-right">
            <div class="notification-badge" onclick="window.location.href='{{ route('agent.dashboard', ['statut' => 'en_attente']) }}'">
                🔔
                @php
                    $totalNotif = $pendingCount;
                @endphp
                @if($totalNotif > 0)
                    <span class="notification-count">{{ $totalNotif }}</span>
                @endif
            </div>
        </div>
    </header>

    <div class="content">

        {{-- Flash --}}
        @if(session('success'))
            <div class="alert alert-success">
                <span>✅</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">
                <span>❌</span>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- Matched banner --}}
        @if($statut === 'matche' && $documentTrouve->perteMatchee)
        <div class="matched-banner">
            <div class="mb-icon">🎉</div>
            <div>
                <div class="mb-title">Document associé — en attente de restitution physique</div>
                <div class="mb-text">
                    Rapproché de la déclaration de <strong>{{ $documentTrouve->perteMatchee->first_name }} {{ $documentTrouve->perteMatchee->last_name }}</strong>
                    ({{ $documentTrouve->perteMatchee->type_piece }}, perdu le {{ \Carbon\Carbon::parse($documentTrouve->perteMatchee->date_perte)->format('d/m/Y') }}).
                    Le propriétaire a été notifié. Procédez à la restitution physique puis cliquez "Marquer comme restitué".
                </div>
            </div>
        </div>
        @endif

        {{-- Page header --}}
        <div class="page-header">
            <h1>
                {{ $docIcon }} {{ $documentTrouve->type_document }}
                @if($documentTrouve->nom_sur_document)
                    <small style="font-size:0.9rem; color:#64748b;">au nom de {{ $documentTrouve->nom_sur_document }} {{ $documentTrouve->prenom_sur_document }}</small>
                @endif
            </h1>
            <a href="{{ route('agent.documents-trouves.index') }}" class="back-btn">
                ← Retour
            </a>
        </div>

        {{-- Statut --}}
        <div style="margin-bottom: 1.5rem;">
            <span class="statut-pill {{ $s['class'] }}">
                <span class="sp-dot {{ $s['pulse'] ? 'pulse' : '' }}"></span>
                {{ $s['icon'] }} {{ $s['label'] }}
            </span>
        </div>

        {{-- Stats row --}}
        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg,#1e3a5f,#162544); color: white;">📅</div>
                <div>
                    <div class="stat-label">Date découverte</div>
                    <div class="stat-value" style="font-size:0.95rem;">{{ \Carbon\Carbon::parse($documentTrouve->date_decouverte)->format('d/m/Y') }}</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg,#f59e0b,#d97706); color: white;">📍</div>
                <div>
                    <div class="stat-label">Lieu découverte</div>
                    <div class="stat-value" style="font-size:0.82rem;">{{ Str::limit($documentTrouve->lieu_decouverte, 22) }}</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background: linear-gradient(135deg,{{ $correspCount > 0 ? '#10b981,#059669' : '#94a3b8,#64748b' }}); color: white;">🔍</div>
                <div>
                    <div class="stat-label">Correspondances</div>
                    <div class="stat-value" style="color:{{ $correspCount > 0 ? '#10b981' : '#64748b' }};">{{ $correspCount }}</div>
                </div>
            </div>
        </div>

        {{-- Main grid --}}
        <div class="main-grid">

            {{-- LEFT COLUMN --}}
            <div>

                {{-- Document --}}
                <div class="card">
                    <div class="card-head">
                        <div class="card-head-icon chi-blue">📄</div>
                        <div>
                            <div class="card-title">Informations du document</div>
                            <div class="card-subtitle">Données du document trouvé</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="two-col">
                            <div>
                                <div class="info-row">
                                    <span class="ir-label">Type</span>
                                    <span class="ir-value">{{ $documentTrouve->type_document }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="ir-label">Numéro</span>
                                    <span class="ir-value {{ !$documentTrouve->numero_document ? 'ir-empty' : '' }}">
                                        {{ $documentTrouve->numero_document ?? 'Non renseigné' }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <div class="info-row">
                                    <span class="ir-label">Nom sur doc.</span>
                                    <span class="ir-value {{ !$documentTrouve->nom_sur_document ? 'ir-empty' : '' }}">
                                        {{ $documentTrouve->nom_sur_document ?? 'Non lisible' }}
                                    </span>
                                </div>
                                <div class="info-row">
                                    <span class="ir-label">Prénom sur doc.</span>
                                    <span class="ir-value {{ !$documentTrouve->prenom_sur_document ? 'ir-empty' : '' }}">
                                        {{ $documentTrouve->prenom_sur_document ?? 'Non lisible' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Déclarant --}}
                <div class="card">
                    <div class="card-head">
                        <div class="card-head-icon chi-green">👤</div>
                        <div>
                            <div class="card-title">Déclarant — personne qui a trouvé</div>
                            <div class="card-subtitle">Coordonnées complètes (visibles agents uniquement)</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="two-col">
                            <div>
                                <div class="info-row">
                                    <span class="ir-label">Nom</span>
                                    <span class="ir-value">{{ $documentTrouve->nom_declarant }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="ir-label">Prénom</span>
                                    <span class="ir-value">{{ $documentTrouve->prenom_declarant }}</span>
                                </div>
                                <div class="info-row">
                                    <span class="ir-label">Compte</span>
                                    <span class="ir-value" style="{{ $documentTrouve->user_id ? 'color:#10b981' : '' }}">
                                        {{ $documentTrouve->user_id ? '✅ Enregistré' : 'Anonyme' }}
                                    </span>
                                </div>
                            </div>
                            <div>
                                <div class="info-row">
                                    <span class="ir-label">Email</span>
                                    <span class="ir-value">
                                        <a href="mailto:{{ $documentTrouve->email_declarant }}" class="ir-link" style="font-size:0.78rem;">
                                            {{ $documentTrouve->email_declarant }}
                                        </a>
                                    </span>
                                </div>
                                <div class="info-row">
                                    <span class="ir-label">Téléphone</span>
                                    <span class="ir-value">
                                        <a href="tel:{{ $documentTrouve->telephone_declarant }}" class="ir-link">
                                            {{ $documentTrouve->telephone_declarant }}
                                        </a>
                                    </span>
                                </div>
                                <div class="info-row">
                                    <span class="ir-label">Déclaré le</span>
                                    <span class="ir-value" style="font-size:0.75rem;">
                                        {{ \Carbon\Carbon::parse($documentTrouve->created_at)->format('d/m/Y H:i') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Circonstances --}}
                @if($documentTrouve->description || $documentTrouve->circonstances)
                <div class="card">
                    <div class="card-head">
                        <div class="card-head-icon chi-amber">📝</div>
                        <div>
                            <div class="card-title">Description & Circonstances</div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($documentTrouve->description)
                        <div style="margin-bottom:{{ $documentTrouve->circonstances ? '1.1rem' : '0' }};">
                            <div style="font-size:0.68rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:1px;margin-bottom:0.4rem;">État du document</div>
                            <p style="font-size:0.85rem;color:#475569;line-height:1.7;">{{ $documentTrouve->description }}</p>
                        </div>
                        @endif
                        @if($documentTrouve->circonstances)
                        <div>
                            <div style="font-size:0.68rem;font-weight:700;color:#64748b;text-transform:uppercase;letter-spacing:1px;margin-bottom:0.4rem;">Circonstances de la découverte</div>
                            <p style="font-size:0.85rem;color:#475569;line-height:1.7;">{{ $documentTrouve->circonstances }}</p>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

                {{-- Photo --}}
                <div class="card">
                    <div class="card-head">
                        <div class="card-head-icon chi-purple">📷</div>
                        <div>
                            <div class="card-title">Photo du document</div>
                            <div class="card-subtitle">Pièce jointe par le déclarant</div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($documentTrouve->photo_document)
                            @php $ext = strtolower(pathinfo($documentTrouve->photo_document, PATHINFO_EXTENSION)); @endphp
                            @if(in_array($ext, ['jpg','jpeg','png','gif','webp']))
                                <div class="photo-wrap">
                                    <img src="{{ Storage::url($documentTrouve->photo_document) }}" alt="Photo du document">
                                </div>
                                <div style="text-align:center;margin-top:0.75rem;">
                                    <a href="{{ Storage::url($documentTrouve->photo_document) }}" target="_blank"
                                       style="font-size:0.78rem;color:#3b82f6;font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:0.3rem;">
                                        🔍 Voir en taille réelle
                                    </a>
                                </div>
                            @else
                                <div style="text-align:center;padding:1rem;">
                                    <a href="{{ Storage::url($documentTrouve->photo_document) }}" target="_blank"
                                       style="display:inline-flex;align-items:center;gap:0.5rem;background:#f8fafc;padding:0.75rem 1.5rem;border-radius:9px;color:#3b82f6;font-weight:700;text-decoration:none;font-size:0.85rem;border:1.5px solid #e2e8f0;">
                                        📎 Ouvrir le document PDF
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="photo-empty">
                                <div style="font-size:2rem;margin-bottom:0.4rem;">📷</div>
                                <div style="font-size:0.82rem;">Aucune photo fournie par le déclarant</div>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Correspondances --}}
                <div class="card">
                    <div class="card-head">
                        <div class="card-head-icon chi-red">🔍</div>
                        <div>
                            <div class="card-title">
                                Correspondances potentielles
                                @if($correspCount > 0)
                                    <span style="background:#fee2e2;color:#dc2626;font-size:0.65rem;font-weight:700;padding:0.15rem 0.5rem;border-radius:50px;margin-left:0.4rem;vertical-align:middle;">{{ $correspCount }}</span>
                                @endif
                            </div>
                            <div class="card-subtitle">Déclarations de perte qui pourraient correspondre</div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($correspCount > 0)
                            @foreach($correspondances as $perte)
                            <div class="corr-item" id="corr-{{ $perte->id }}" onclick="preselectPerte({{ $perte->id }}, '{{ addslashes($perte->first_name) }} {{ addslashes($perte->last_name) }}')">
                                <div style="flex:1;">
                                    <div class="corr-name">{{ $perte->first_name }} {{ $perte->last_name }}</div>
                                    <div class="corr-detail">
                                        {{ $perte->type_piece }}
                                        • Perdu le {{ \Carbon\Carbon::parse($perte->date_perte)->format('d/m/Y') }}
                                        @if($perte->lieu_perte) • {{ $perte->lieu_perte }} @endif
                                        @if($perte->numero_piece) • N° {{ $perte->numero_piece }} @endif
                                    </div>
                                </div>
                                <span class="corr-tag">Cliquer pour sélectionner</span>
                            </div>
                            @endforeach
                            <p style="font-size:0.72rem;color:#64748b;margin-top:0.85rem;padding-top:0.85rem;border-top:1px solid #e2e8f0;line-height:1.5;">
                                💡 Cliquez sur une correspondance pour ouvrir le formulaire de validation et envoyer la notification au propriétaire.
                            </p>
                        @else
                            <div class="empty-state">
                                <div class="es-icon">🔎</div>
                                <div class="es-text">Aucune déclaration de perte correspondante trouvée pour le moment.<br>Vous pouvez tout de même faire un rapprochement manuel via le panneau Actions.</div>
                            </div>
                        @endif
                    </div>
                </div>

            </div>{{-- fin col gauche --}}

            {{-- RIGHT PANEL --}}
            <div class="right-panel">

                {{-- Actions --}}
                <div class="actions-card">
                    <div class="ac-head">
                        <div class="ac-head-dot"></div>
                        <div class="ac-head-label">Actions Agent</div>
                    </div>
                    <div class="ac-body">

                        {{-- MATCHER --}}
                        @if($statut === 'en_attente')
                            <button class="ac-btn ac-btn-primary" onclick="openMatchModal()">
                                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                                </svg>
                                <div class="ac-btn-label">
                                    Valider & Associer
                                    <span class="ac-btn-desc">Envoie une notification au propriétaire</span>
                                </div>
                            </button>
                        @elseif($statut === 'matche')
                            <div class="ac-btn ac-btn-disabled">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4"/>
                                </svg>
                                <div class="ac-btn-label">
                                    Déjà associé
                                    <span class="ac-btn-desc">Propriétaire notifié</span>
                                </div>
                            </div>
                        @else
                            <div class="ac-btn ac-btn-disabled">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                </svg>
                                <div class="ac-btn-label">
                                    Association — N/A
                                    <span class="ac-btn-desc">Statut {{ $statut }}</span>
                                </div>
                            </div>
                        @endif

                        <hr class="ac-divider">

                        {{-- RESTITUER --}}
                        @if($statut === 'matche')
                            <form method="POST" action="{{ route('agent.documents-trouves.restituer', $documentTrouve->id) }}"
                                  onsubmit="return confirmRestitution(event)">
                                @csrf
                                <button type="submit" class="ac-btn ac-btn-success">
                                    <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <div class="ac-btn-label">
                                        Marquer comme restitué
                                        <span class="ac-btn-desc">Clôture le dossier définitivement</span>
                                    </div>
                                </button>
                            </form>
                        @elseif($statut === 'restitue')
                            <div class="ac-btn ac-btn-disabled">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div class="ac-btn-label">
                                    Dossier clôturé ✅
                                    <span class="ac-btn-desc">Document restitué avec succès</span>
                                </div>
                            </div>
                        @else
                            <div class="ac-btn ac-btn-disabled">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div class="ac-btn-label">
                                    Restituer
                                    <span class="ac-btn-desc">Associer d'abord à un propriétaire</span>
                                </div>
                            </div>
                        @endif

                        <hr class="ac-divider">

                        {{-- ARCHIVER --}}
                        @if(!in_array($statut, ['archive','restitue']))
                            <form method="POST" action="{{ route('agent.documents-trouves.destroy', $documentTrouve->id) }}"
                                  onsubmit="return confirm('Archiver ce dossier ? Cette action est irréversible.')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="ac-btn ac-btn-ghost">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                    </svg>
                                    <div class="ac-btn-label">
                                        Archiver le dossier
                                        <span class="ac-btn-desc">Retirer des cas actifs</span>
                                    </div>
                                </button>
                            </form>
                        @endif

                        @if($correspCount > 0)
                            <div class="ac-corr-hint">🔍 {{ $correspCount }} correspondance{{ $correspCount > 1 ? 's' : '' }} détectée{{ $correspCount > 1 ? 's' : '' }}</div>
                        @endif
                    </div>
                </div>

                {{-- Timeline --}}
                <div class="card">
                    <div class="card-head">
                        <div class="card-head-icon chi-gray">📅</div>
                        <div>
                            <div class="card-title">Historique du dossier</div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="tl">
                            <div class="tl-item">
                                <div class="tl-line"></div>
                                <div class="tl-dot tl-done">📦</div>
                                <div>
                                    <div class="tl-label">Document déclaré trouvé</div>
                                    <div class="tl-date">{{ \Carbon\Carbon::parse($documentTrouve->created_at)->format('d/m/Y à H:i') }}</div>
                                </div>
                            </div>

                            <div class="tl-item">
                                <div class="tl-line"></div>
                                <div class="tl-dot {{ in_array($statut,['matche','restitue']) ? 'tl-done' : 'tl-pending' }}">🔗</div>
                                <div>
                                    <div class="tl-label" style="{{ !in_array($statut,['matche','restitue']) ? 'color:#64748b' : '' }}">
                                        Propriétaire identifié & notifié
                                    </div>
                                    <div class="tl-date">
                                        @if(in_array($statut,['matche','restitue']))
                                            {{ \Carbon\Carbon::parse($documentTrouve->updated_at)->format('d/m/Y à H:i') }}
                                        @else
                                            En attente
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="tl-item">
                                <div class="tl-dot {{ $statut === 'restitue' ? 'tl-done' : 'tl-pending' }}">✅</div>
                                <div>
                                    <div class="tl-label" style="{{ $statut !== 'restitue' ? 'color:#64748b' : '' }}">
                                        Document restitué
                                    </div>
                                    <div class="tl-date">
                                        @if($statut === 'restitue' && $documentTrouve->date_restitution)
                                            {{ \Carbon\Carbon::parse($documentTrouve->date_restitution)->format('d/m/Y à H:i') }}
                                        @else
                                            En attente
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>{{-- fin right panel --}}

        </div>{{-- fin main-grid --}}

    </div>{{-- fin content --}}
</div>{{-- fin main --}}


{{-- MODAL VALIDATION --}}
<div class="modal-overlay" id="matchModal">
    <div class="modal">
        <div class="modal-top">
            <div style="display:flex;align-items:flex-start;gap:0.9rem;">
                <div class="modal-top-icon">🔗</div>
                <div>
                    <div class="modal-title">Valider l'association</div>
                    <div class="modal-subtitle">
                        Sélectionnez le propriétaire du document.<br>
                        Une <strong>notification automatique</strong> lui sera envoyée avec vos coordonnées.
                    </div>
                </div>
            </div>
            <button class="modal-close-btn" onclick="closeMatchModal()">✕</button>
        </div>

        <div class="modal-body">

            {{-- Aperçu notification --}}
            <div class="notif-preview">
                <div class="np-header">
                    <span class="np-bell">🔔</span>
                    <span class="np-header-label">Aperçu de la notification envoyée</span>
                </div>
                <div class="np-title">🎉 Votre document a peut-être été trouvé !</div>
                <div class="np-text">
                    Un <strong>{{ $documentTrouve->type_document }}</strong> correspondant à votre déclaration a été trouvé
                    le {{ \Carbon\Carbon::parse($documentTrouve->date_decouverte)->format('d/m/Y') }}
                    à {{ $documentTrouve->lieu_decouverte }}.
                    Un agent va vérifier et vous contactera si c'est bien votre document.
                </div>
            </div>

            <form id="matchForm" method="POST" action="{{ route('agent.documents-trouves.matcher', $documentTrouve->id) }}">
                @csrf

                {{-- Options --}}
                @if($correspCount > 0)
                    <div class="perte-section-label">Correspondances détectées automatiquement</div>
                    @foreach($correspondances as $perte)
                    <label class="perte-option" id="modal-opt-{{ $perte->id }}">
                        <input type="radio" name="perte_id" value="{{ $perte->id }}"
                               onchange="highlightOption({{ $perte->id }})">
                        <div style="flex:1;">
                            <div class="po-name">{{ $perte->first_name }} {{ $perte->last_name }}</div>
                            <div class="po-detail">
                                {{ $perte->type_piece }}
                                • Perte le {{ \Carbon\Carbon::parse($perte->date_perte)->format('d/m/Y') }}
                                @if($perte->lieu_perte) • {{ $perte->lieu_perte }} @endif
                                @if($perte->numero_piece) • N° {{ $perte->numero_piece }} @endif
                            </div>
                        </div>
                    </label>
                    @endforeach
                @else
                    <div style="text-align:center;padding:1rem 0 0.5rem;color:#64748b;font-size:0.82rem;">
                        Aucune correspondance automatique. Saisir l'ID manuellement ci-dessous.
                    </div>
                @endif

                {{-- Saisie manuelle --}}
                <div class="manual-input-wrap">
                    <div class="manual-label">Ou saisir manuellement l'ID de la déclaration de perte :</div>
                    <input type="number" id="manualPerteId" placeholder="Ex : 42" class="manual-input"
                           oninput="if(this.value) document.querySelectorAll('input[name=perte_id]').forEach(r=>r.checked=false)">
                    <div class="manual-hint">Utilisé uniquement si aucune option ci-dessus n'est sélectionnée.</div>
                </div>

            </form>
        </div>

        <div class="modal-footer">
            <button class="btn-cancel" onclick="closeMatchModal()">Annuler</button>
            <button class="btn-confirm" onclick="submitMatch()">
                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="width:17px;height:17px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                </svg>
                Confirmer & Notifier le propriétaire
            </button>
        </div>
    </div>
</div>


<script>
    /* ========== MODAL ========== */
    function openMatchModal() {
        document.getElementById('matchModal').classList.add('open');
        document.body.style.overflow = 'hidden';
    }

    function closeMatchModal() {
        document.getElementById('matchModal').classList.remove('open');
        document.body.style.overflow = '';
    }

    document.getElementById('matchModal').addEventListener('click', function(e) {
        if (e.target === this) closeMatchModal();
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') closeMatchModal();
    });

    /* ========== PRESÉLECTION depuis les correspondances ========== */
    function preselectPerte(id, nom) {
        openMatchModal();
        setTimeout(() => {
            const radio = document.querySelector(`#matchForm input[name="perte_id"][value="${id}"]`);
            if (radio) {
                radio.checked = true;
                highlightOption(id);
                radio.closest('.perte-option').scrollIntoView({ behavior: 'smooth', block: 'nearest' });
            }
        }, 120);
    }

    function highlightOption(id) {
        document.querySelectorAll('#matchForm .perte-option').forEach(el => el.classList.remove('checked'));
        const selected = document.querySelector(`#modal-opt-${id}`);
        if (selected) selected.classList.add('checked');
    }

    /* ========== SUBMIT avec validation ========== */
    function submitMatch() {
        const radioSelected = document.querySelector('#matchForm input[name="perte_id"]:checked');
        const manualId = document.getElementById('manualPerteId').value.trim();

        if (!radioSelected && !manualId) {
            alert('⚠️ Veuillez sélectionner une correspondance ou saisir un ID manuellement.');
            return;
        }

        if (manualId && !radioSelected) {
            // Ajouter le champ caché pour l'ID manuel
            const existing = document.querySelector('#matchForm input[name="perte_id"][type="hidden"]');
            if (existing) existing.remove();
            const hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'perte_id';
            hidden.value = manualId;
            document.getElementById('matchForm').appendChild(hidden);
        }

        // Confirmation finale
        const name = radioSelected
            ? radioSelected.closest('.perte-option').querySelector('.po-name').textContent
            : `ID ${manualId}`;

        if (!confirm(`Confirmer l'association et envoyer la notification à : ${name} ?`)) return;

        document.getElementById('matchForm').submit();
    }

    /* ========== CONFIRMATION RESTITUTION ========== */
    function confirmRestitution(e) {
        e.preventDefault();
        if (confirm('Confirmer la restitution physique du document ? Le dossier sera définitivement clôturé et le propriétaire notifié.')) {
            e.target.submit();
        }
        return false;
    }
</script>
</body>
</html>