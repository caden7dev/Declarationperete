<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dossier Document Trouvé — Agent | e-Déclaration TG</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@300;400;500;600;700&family=DM+Mono:wght@400;500&family=Syne:wght@700;800&display=swap" rel="stylesheet">
    <style>
        :root {
            --navy:    #0f1f3d;
            --navy2:   #1a3358;
            --green:   #00c875;
            --green2:  #00a85a;
            --blue:    #3b6ef8;
            --blue2:   #1d4ed8;
            --amber:   #f59e0b;
            --red:     #ef4444;
            --bg:      #f0f3f8;
            --white:   #ffffff;
            --gray1:   #f8fafc;
            --gray2:   #e9eef5;
            --gray3:   #94a3b8;
            --gray4:   #475569;
            --text:    #0f1f3d;
            --card-shadow: 0 2px 12px rgba(15,31,61,0.08);
            --card-shadow-hover: 0 8px 32px rgba(15,31,61,0.14);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DM Sans', sans-serif;
            background: var(--bg);
            color: var(--text);
            min-height: 100vh;
            display: flex;
        }

        /* ==================== SIDEBAR ==================== */
        .sidebar {
            width: 256px;
            background: var(--navy);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 100;
            transition: transform 0.3s;
        }

        .sb-head {
            padding: 1.75rem 1.25rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,0.07);
        }

        .sb-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .sb-brand-icon {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, var(--green), var(--green2));
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .sb-brand-name {
            font-family: 'Syne', sans-serif;
            font-weight: 800;
            font-size: 1rem;
            color: white;
            line-height: 1.1;
        }

        .sb-brand-sub {
            font-size: 0.65rem;
            color: rgba(255,255,255,0.35);
            font-weight: 400;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            font-family: 'DM Mono', monospace;
        }

        .sb-role-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            background: rgba(0,200,117,0.12);
            border: 1px solid rgba(0,200,117,0.25);
            color: var(--green);
            font-size: 0.65rem;
            font-weight: 600;
            padding: 0.2rem 0.65rem;
            border-radius: 50px;
            margin-top: 0.75rem;
            letter-spacing: 0.5px;
            text-transform: uppercase;
        }

        .sb-role-dot {
            width: 5px; height: 5px;
            background: var(--green);
            border-radius: 50%;
            animation: glow 2s infinite;
        }

        @keyframes glow { 0%,100%{opacity:1} 50%{opacity:0.3} }

        .sb-nav {
            flex: 1;
            padding: 1rem 0.75rem;
            overflow-y: auto;
        }

        .sb-section {
            font-size: 0.62rem;
            font-weight: 600;
            color: rgba(255,255,255,0.25);
            letter-spacing: 2px;
            text-transform: uppercase;
            padding: 0.9rem 0.6rem 0.4rem;
            font-family: 'DM Mono', monospace;
        }

        .sb-link {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            padding: 0.65rem 0.75rem;
            border-radius: 9px;
            text-decoration: none;
            color: rgba(255,255,255,0.5);
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.18s;
            margin-bottom: 0.15rem;
            position: relative;
        }

        .sb-link:hover { background: rgba(255,255,255,0.06); color: rgba(255,255,255,0.85); }

        .sb-link.active {
            background: rgba(0,200,117,0.12);
            color: var(--green);
            font-weight: 600;
        }

        .sb-link.active::before {
            content: '';
            position: absolute;
            left: 0; top: 20%; bottom: 20%;
            width: 3px;
            background: var(--green);
            border-radius: 0 3px 3px 0;
        }

        .sb-link svg { width: 16px; height: 16px; flex-shrink: 0; }

        .sb-badge {
            margin-left: auto;
            background: var(--red);
            color: white;
            font-size: 0.6rem;
            font-weight: 700;
            min-width: 17px; height: 17px;
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            padding: 0 4px;
        }

        .sb-foot {
            padding: 0.75rem;
            border-top: 1px solid rgba(255,255,255,0.07);
        }

        .sb-logout {
            width: 100%;
            background: rgba(239,68,68,0.1);
            color: #f87171;
            border: 1px solid rgba(239,68,68,0.2);
            padding: 0.65rem;
            border-radius: 9px;
            font-size: 0.82rem;
            font-weight: 600;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 0.5rem;
            transition: all 0.18s;
            font-family: 'DM Sans', sans-serif;
        }

        .sb-logout:hover { background: rgba(239,68,68,0.18); color: #fca5a5; }

        /* ==================== MAIN ==================== */
        .main {
            margin-left: 256px;
            flex: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ==================== TOPBAR ==================== */
        .topbar {
            background: var(--white);
            border-bottom: 1px solid var(--gray2);
            padding: 0 2rem;
            height: 60px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 50;
        }

        .topbar-left {
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }

        .breadcrumb {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.82rem;
            color: var(--gray3);
        }

        .breadcrumb a {
            color: var(--gray3);
            text-decoration: none;
            transition: color 0.15s;
        }

        .breadcrumb a:hover { color: var(--navy); }

        .breadcrumb-sep { opacity: 0.5; }

        .breadcrumb-current {
            color: var(--navy);
            font-weight: 600;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .tb-icon-btn {
            width: 36px; height: 36px;
            border-radius: 8px;
            background: var(--gray1);
            border: 1px solid var(--gray2);
            display: flex; align-items: center; justify-content: center;
            cursor: pointer;
            transition: all 0.18s;
            position: relative;
        }

        .tb-icon-btn:hover { background: var(--gray2); }
        .tb-icon-btn svg { width: 17px; height: 17px; stroke: var(--gray4); }

        .tb-notif-dot {
            position: absolute;
            top: -2px; right: -2px;
            width: 8px; height: 8px;
            background: var(--red);
            border-radius: 50%;
            border: 1.5px solid white;
        }

        .tb-agent-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.3rem 0.75rem 0.3rem 0.3rem;
            background: var(--gray1);
            border: 1px solid var(--gray2);
            border-radius: 50px;
            cursor: default;
        }

        .tb-avatar {
            width: 28px; height: 28px;
            background: linear-gradient(135deg, var(--navy), var(--navy2));
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.7rem;
            font-weight: 700;
            color: white;
        }

        .tb-agent-name {
            font-size: 0.78rem;
            font-weight: 600;
            color: var(--navy);
        }

        /* ==================== PAGE CONTENT ==================== */
        .page-content {
            padding: 1.75rem 2rem;
            flex: 1;
        }

        /* ==================== PAGE HEADER ==================== */
        .page-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            margin-bottom: 1.75rem;
            gap: 1.5rem;
            flex-wrap: wrap;
        }

        .ph-left {}

        .ph-ref {
            font-family: 'DM Mono', monospace;
            font-size: 0.7rem;
            color: var(--gray3);
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 0.35rem;
        }

        .ph-title {
            font-family: 'Syne', sans-serif;
            font-size: 1.6rem;
            font-weight: 800;
            color: var(--navy);
            line-height: 1.2;
            margin-bottom: 0.6rem;
        }

        .ph-title small {
            display: block;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.9rem;
            font-weight: 400;
            color: var(--gray3);
            margin-top: 0.25rem;
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
        .sp-dot.pulse { animation: glow 1.8s infinite; }

        .sp-attente  { background: #fef3c7; color: #b45309; border: 1px solid #fde68a; }
        .sp-matche   { background: #dbeafe; color: #1d4ed8; border: 1px solid #bfdbfe; }
        .sp-restitue { background: #d1fae5; color: #065f46; border: 1px solid #a7f3d0; }
        .sp-archive  { background: #f1f5f9; color: #475569; border: 1px solid #e2e8f0; }

        /* ==================== FLASH ==================== */
        .flash {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.9rem 1.25rem;
            border-radius: 10px;
            margin-bottom: 1.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            animation: slideDown 0.3s ease;
        }

        @keyframes slideDown { from{transform:translateY(-8px);opacity:0} to{transform:translateY(0);opacity:1} }

        .flash-success { background: #d1fae5; border: 1.5px solid #6ee7b7; color: #065f46; }
        .flash-error   { background: #fee2e2; border: 1.5px solid #fca5a5; color: #991b1b; }
        .flash-icon { font-size: 1.1rem; flex-shrink: 0; }

        /* ==================== MATCHED BANNER ==================== */
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
            background: var(--green);
        }

        .mb-icon { font-size: 1.6rem; flex-shrink: 0; }
        .mb-title { font-weight: 700; color: #065f46; font-size: 0.9rem; }
        .mb-text { font-size: 0.8rem; color: #047857; margin-top: 0.15rem; line-height: 1.5; }

        /* ==================== MAIN GRID ==================== */
        .main-grid {
            display: grid;
            grid-template-columns: 1fr 340px;
            gap: 1.5rem;
            align-items: start;
        }

        /* ==================== CARDS ==================== */
        .card {
            background: var(--white);
            border-radius: 14px;
            border: 1px solid var(--gray2);
            overflow: hidden;
            box-shadow: var(--card-shadow);
            margin-bottom: 1.25rem;
            transition: box-shadow 0.2s;
        }

        .card:last-child { margin-bottom: 0; }
        .card:hover { box-shadow: var(--card-shadow-hover); }

        .card-head {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1.1rem 1.4rem;
            border-bottom: 1px solid var(--gray2);
            background: var(--gray1);
        }

        .card-head-icon {
            width: 34px; height: 34px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.95rem;
            flex-shrink: 0;
        }

        .chi-navy   { background: linear-gradient(135deg,#0f1f3d,#1a3358); }
        .chi-green  { background: linear-gradient(135deg,#00c875,#00a85a); }
        .chi-blue   { background: linear-gradient(135deg,#3b6ef8,#1d4ed8); }
        .chi-amber  { background: linear-gradient(135deg,#f59e0b,#d97706); }
        .chi-purple { background: linear-gradient(135deg,#8b5cf6,#7c3aed); }
        .chi-red    { background: linear-gradient(135deg,#ef4444,#dc2626); }

        .card-title { font-weight: 700; font-size: 0.88rem; color: var(--navy); }
        .card-subtitle { font-size: 0.75rem; color: var(--gray3); margin-top: 0.1rem; }

        .card-body { padding: 1.25rem 1.4rem; }

        /* Info rows */
        .info-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.55rem 0;
            border-bottom: 1px solid var(--gray2);
            gap: 1rem;
        }

        .info-row:last-child { border-bottom: none; padding-bottom: 0; }

        .ir-label { font-size: 0.76rem; color: var(--gray3); font-weight: 500; flex-shrink: 0; }
        .ir-value { font-size: 0.85rem; color: var(--navy); font-weight: 600; text-align: right; }
        .ir-empty { color: #cbd5e1; font-style: italic; font-weight: 400; }

        .ir-link { color: var(--blue); text-decoration: none; font-weight: 600; }
        .ir-link:hover { text-decoration: underline; }

        /* 2-col grid inside cards */
        .two-col { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }

        /* ==================== STATS ROW ==================== */
        .stats-row {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .stat-card {
            background: var(--white);
            border: 1px solid var(--gray2);
            border-radius: 12px;
            padding: 1rem 1.2rem;
            display: flex;
            align-items: center;
            gap: 0.85rem;
            box-shadow: var(--card-shadow);
        }

        .stat-icon {
            width: 40px; height: 40px;
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem;
            flex-shrink: 0;
        }

        .stat-label { font-size: 0.7rem; color: var(--gray3); font-weight: 500; text-transform: uppercase; letter-spacing: 0.5px; }
        .stat-value { font-size: 1.2rem; font-weight: 800; color: var(--navy); font-family: 'Syne', sans-serif; line-height: 1; margin-top: 0.15rem; }

        /* ==================== PHOTO ==================== */
        .photo-wrap { text-align: center; padding: 0.5rem; }

        .photo-wrap img {
            max-width: 100%;
            max-height: 260px;
            border-radius: 10px;
            border: 2px solid var(--gray2);
            object-fit: contain;
        }

        .photo-empty {
            background: var(--gray1);
            border: 2px dashed var(--gray2);
            border-radius: 10px;
            padding: 2rem;
            text-align: center;
            color: var(--gray3);
        }

        /* ==================== CORRESPONDANCES ==================== */
        .corr-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0.9rem 1rem;
            border: 1.5px solid var(--gray2);
            border-radius: 10px;
            margin-bottom: 0.7rem;
            cursor: pointer;
            transition: all 0.18s;
            gap: 0.75rem;
        }

        .corr-item:last-child { margin-bottom: 0; }

        .corr-item:hover {
            border-color: var(--blue);
            background: #f0f5ff;
            transform: translateX(2px);
        }

        .corr-item.selected {
            border-color: var(--blue);
            background: #eff6ff;
        }

        .corr-name { font-weight: 700; color: var(--navy); font-size: 0.85rem; }
        .corr-detail { font-size: 0.74rem; color: var(--gray3); margin-top: 0.15rem; line-height: 1.4; }

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

        /* ==================== TIMELINE ==================== */
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
            background: var(--gray2);
        }

        .tl-item:last-child .tl-line { display: none; }

        .tl-dot {
            width: 30px; height: 30px;
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 0.8rem;
            flex-shrink: 0;
            z-index: 1;
            border: 2px solid var(--white);
        }

        .tl-done { background: #d1fae5; }
        .tl-current { background: #dbeafe; }
        .tl-pending { background: var(--gray2); opacity: 0.5; }

        .tl-label { font-size: 0.83rem; font-weight: 600; color: var(--navy); }
        .tl-date  { font-size: 0.72rem; color: var(--gray3); margin-top: 0.1rem; font-family: 'DM Mono', monospace; }

        /* ==================== RIGHT PANEL ==================== */
        .right-panel { position: sticky; top: 78px; }

        /* Actions card */
        .actions-card {
            background: var(--navy);
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
            font-family: 'DM Mono', monospace;
        }

        .ac-head-dot {
            width: 6px; height: 6px;
            border-radius: 50%;
            background: var(--green);
            animation: glow 1.8s infinite;
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
            font-family: 'DM Sans', sans-serif;
        }

        .ac-btn:last-child { margin-bottom: 0; }
        .ac-btn svg { width: 17px; height: 17px; flex-shrink: 0; }

        .ac-btn-primary {
            background: linear-gradient(135deg, var(--blue), var(--blue2));
            color: white;
            box-shadow: 0 4px 14px rgba(59,110,248,0.35);
        }

        .ac-btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 7px 20px rgba(59,110,248,0.45);
        }

        .ac-btn-success {
            background: linear-gradient(135deg, var(--green), var(--green2));
            color: white;
            box-shadow: 0 4px 14px rgba(0,200,117,0.3);
        }

        .ac-btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 7px 20px rgba(0,200,117,0.4);
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

        /* Correspondances count in actions */
        .ac-corr-hint {
            font-size: 0.72rem;
            color: rgba(255,255,255,0.3);
            padding: 0.5rem;
            text-align: center;
            font-family: 'DM Mono', monospace;
        }

        /* ==================== MODAL ==================== */
        .modal-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(10,20,40,0.7);
            backdrop-filter: blur(6px);
            z-index: 1000;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .modal-overlay.open { display: flex; }

        .modal {
            background: var(--white);
            border-radius: 18px;
            width: 100%;
            max-width: 560px;
            max-height: 88vh;
            overflow-y: auto;
            box-shadow: 0 40px 100px rgba(10,20,40,0.4);
            animation: modalIn 0.28s cubic-bezier(0.34,1.2,0.64,1);
        }

        @keyframes modalIn {
            from { transform: scale(0.92) translateY(16px); opacity: 0; }
            to   { transform: scale(1) translateY(0); opacity: 1; }
        }

        .modal-top {
            padding: 1.5rem 1.5rem 1.25rem;
            border-bottom: 1px solid var(--gray2);
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
            background: linear-gradient(135deg, var(--blue), var(--blue2));
            border-radius: 11px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.2rem;
            flex-shrink: 0;
        }

        .modal-title { font-family: 'Syne', sans-serif; font-size: 1.15rem; font-weight: 800; color: var(--navy); }
        .modal-subtitle { font-size: 0.78rem; color: var(--gray3); margin-top: 0.2rem; line-height: 1.5; }

        .modal-close-btn {
            width: 32px; height: 32px;
            background: var(--gray1);
            border: 1px solid var(--gray2);
            border-radius: 8px;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center;
            color: var(--gray4);
            font-size: 1rem;
            flex-shrink: 0;
            transition: all 0.15s;
        }

        .modal-close-btn:hover { background: var(--gray2); }

        .modal-body { padding: 1.4rem 1.5rem; }

        /* Notification preview box */
        .notif-preview {
            background: linear-gradient(135deg, #0f1f3d08, #3b6ef808);
            border: 1.5px solid #3b6ef820;
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
            color: var(--blue);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .np-bell { font-size: 1rem; }

        .np-title { font-weight: 700; color: var(--navy); font-size: 0.85rem; margin-bottom: 0.3rem; }
        .np-text { font-size: 0.78rem; color: var(--gray4); line-height: 1.55; }

        /* Perte options */
        .perte-section-label {
            font-size: 0.72rem;
            font-weight: 700;
            color: var(--gray3);
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 0.6rem;
        }

        .perte-option {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.9rem 1rem;
            border: 2px solid var(--gray2);
            border-radius: 11px;
            margin-bottom: 0.6rem;
            cursor: pointer;
            transition: all 0.18s;
        }

        .perte-option:hover { border-color: var(--blue); background: #f5f8ff; }

        .perte-option.checked { border-color: var(--blue); background: #eff6ff; }

        .perte-option input[type="radio"] {
            width: 16px; height: 16px;
            accent-color: var(--blue);
            flex-shrink: 0;
        }

        .po-name { font-weight: 700; color: var(--navy); font-size: 0.85rem; }
        .po-detail { font-size: 0.74rem; color: var(--gray3); margin-top: 0.15rem; line-height: 1.4; }

        /* Manual input */
        .manual-input-wrap {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid var(--gray2);
        }

        .manual-label {
            font-size: 0.74rem;
            font-weight: 600;
            color: var(--gray4);
            margin-bottom: 0.5rem;
        }

        .manual-input {
            width: 100%;
            padding: 0.7rem 0.9rem;
            border: 2px solid var(--gray2);
            border-radius: 9px;
            font-size: 0.88rem;
            font-family: 'DM Mono', monospace;
            transition: border-color 0.15s;
            color: var(--navy);
        }

        .manual-input:focus {
            outline: none;
            border-color: var(--blue);
        }

        .manual-hint { font-size: 0.7rem; color: var(--gray3); margin-top: 0.35rem; }

        /* Modal footer */
        .modal-footer {
            padding: 1.1rem 1.5rem;
            border-top: 1px solid var(--gray2);
            display: flex;
            gap: 0.75rem;
            background: var(--gray1);
            border-radius: 0 0 18px 18px;
        }

        .btn-confirm {
            flex: 1;
            background: linear-gradient(135deg, var(--blue), var(--blue2));
            color: white;
            padding: 0.8rem;
            border: none;
            border-radius: 10px;
            font-weight: 700;
            font-size: 0.88rem;
            cursor: pointer;
            transition: all 0.2s;
            display: flex; align-items: center; justify-content: center; gap: 0.5rem;
            font-family: 'DM Sans', sans-serif;
        }

        .btn-confirm:hover {
            transform: translateY(-1px);
            box-shadow: 0 6px 18px rgba(59,110,248,0.35);
        }

        .btn-cancel {
            padding: 0.8rem 1.4rem;
            background: var(--white);
            color: var(--gray4);
            border: 1.5px solid var(--gray2);
            border-radius: 10px;
            font-weight: 600;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.15s;
            font-family: 'DM Sans', sans-serif;
        }

        .btn-cancel:hover { background: var(--gray2); }

        /* ==================== EMPTY STATES ==================== */
        .empty-state {
            text-align: center;
            padding: 2rem 1rem;
            color: var(--gray3);
        }

        .es-icon { font-size: 2rem; margin-bottom: 0.5rem; }
        .es-text { font-size: 0.82rem; line-height: 1.6; }

        /* ==================== RESPONSIVE ==================== */
        @media (max-width: 1280px) {
            .main-grid { grid-template-columns: 1fr 300px; }
        }

        @media (max-width: 1024px) {
            .sidebar { transform: translateX(-100%); }
            .main { margin-left: 0; }
            .main-grid { grid-template-columns: 1fr; }
            .right-panel { position: static; }
            .stats-row { grid-template-columns: 1fr 1fr; }
        }

        @media (max-width: 640px) {
            .page-content { padding: 1rem; }
            .topbar { padding: 0 1rem; }
            .two-col { grid-template-columns: 1fr; }
            .stats-row { grid-template-columns: 1fr; }
        }

        /* ==================== DARK MODE ==================== */
        body.dark-mode {
            --bg: #0d1626;
            --white: #111d33;
            --gray1: #162038;
            --gray2: #1e2d47;
            --gray3: #64748b;
            --gray4: #94a3b8;
            --text: #e2e8f0;
            --card-shadow: 0 2px 12px rgba(0,0,0,0.3);
        }

        body.dark-mode .topbar { background: #111d33; border-color: #1e2d47; }
        body.dark-mode .tb-icon-btn { background: #162038; border-color: #1e2d47; }
        body.dark-mode .tb-icon-btn svg { stroke: #94a3b8; }
        body.dark-mode .tb-agent-info { background: #162038; border-color: #1e2d47; }
        body.dark-mode .breadcrumb a { color: #64748b; }
        body.dark-mode .breadcrumb-current { color: #e2e8f0; }
        body.dark-mode .ph-title { color: #e2e8f0; }
        body.dark-mode .card { background: #111d33; border-color: #1e2d47; }
        body.dark-mode .card-head { background: #162038; border-color: #1e2d47; }
        body.dark-mode .card-title { color: #e2e8f0; }
        body.dark-mode .info-row { border-color: #1e2d47; }
        body.dark-mode .ir-value { color: #e2e8f0; }
        body.dark-mode .corr-item { border-color: #1e2d47; background: #111d33; }
        body.dark-mode .corr-item:hover { border-color: var(--blue); background: #162038; }
        body.dark-mode .stat-card { background: #111d33; border-color: #1e2d47; }
        body.dark-mode .stat-value { color: #e2e8f0; }
        body.dark-mode .modal { background: #111d33; }
        body.dark-mode .modal-top { background: #111d33; border-color: #1e2d47; }
        body.dark-mode .modal-title { color: #e2e8f0; }
        body.dark-mode .modal-footer { background: #162038; border-color: #1e2d47; }
        body.dark-mode .perte-option { border-color: #1e2d47; background: #111d33; }
        body.dark-mode .perte-option:hover,.perte-option.checked { background: #162038; }
        body.dark-mode .po-name { color: #e2e8f0; }
        body.dark-mode .manual-input { background: #162038; border-color: #1e2d47; color: #e2e8f0; }
        body.dark-mode .btn-cancel { background: #162038; border-color: #1e2d47; color: #94a3b8; }
        body.dark-mode .btn-cancel:hover { background: #1e2d47; }
        body.dark-mode .photo-empty { background: #162038; border-color: #1e2d47; }
        body.dark-mode .tl-line { background: #1e2d47; }
        body.dark-mode .notif-preview { background: rgba(59,110,248,0.06); border-color: rgba(59,110,248,0.15); }
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

<!-- ==================== SIDEBAR ==================== -->
<aside class="sidebar">
    <div class="sb-head">
        <div class="sb-brand">
            <div class="sb-brand-icon">🇹🇬</div>
            <div>
                <div class="sb-brand-name">e-Déclaration TG</div>
                <div class="sb-brand-sub">Espace Agent</div>
            </div>
        </div>
        <div class="sb-role-pill">
            <div class="sb-role-dot"></div>
            Agent Vérificateur
        </div>
    </div>

    <nav class="sb-nav">
        <div class="sb-section">Navigation</div>
        <a href="{{ route('agent.dashboard') }}" class="sb-link {{ request()->routeIs('agent.dashboard') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
            </svg>
            Tableau de bord
        </a>

        <div class="sb-section">Déclarations</div>
        <a href="{{ route('agent.dashboard') }}" class="sb-link {{ request()->routeIs('agent.perte.*') ? 'active' : '' }}">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Pertes déclarées
            @if($pendingCount > 0)
                <span class="sb-badge">{{ $pendingCount }}</span>
            @endif
        </a>

        <a href="{{ route('agent.documents-trouves.index') }}" class="sb-link active">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            Documents trouvés
        </a>
    </nav>

    <div class="sb-foot">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sb-logout">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:14px;height:14px;">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Se déconnecter
            </button>
        </form>
    </div>
</aside>

<!-- ==================== MAIN ==================== -->
<div class="main">

    <!-- Topbar -->
    <header class="topbar">
        <div class="topbar-left">
            <nav class="breadcrumb">
                <a href="{{ route('agent.dashboard') }}">Accueil</a>
                <span class="breadcrumb-sep">/</span>
                <a href="{{ route('agent.documents-trouves.index') }}">Documents trouvés</a>
                <span class="breadcrumb-sep">/</span>
                <span class="breadcrumb-current">{{ $documentTrouve->numero_declaration ?? 'DT-'.str_pad($documentTrouve->id,5,'0',STR_PAD_LEFT) }}</span>
            </nav>
        </div>
        <div class="topbar-right">
            <button class="tb-icon-btn" onclick="toggleGlobalDarkMode()" title="Changer le thème">
                <svg id="themeIcon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </button>
            <button class="tb-icon-btn" onclick="window.location.href='{{ route('agent.dashboard') }}'" style="position:relative;">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                @if($pendingCount > 0)
                    <span class="tb-notif-dot"></span>
                @endif
            </button>
            <div class="tb-agent-info">
                <div class="tb-avatar">{{ $agentInitials }}</div>
                <span class="tb-agent-name">{{ auth()->user()->first_name ?? auth()->user()->name }}</span>
            </div>
        </div>
    </header>

    <!-- Page Content -->
    <div class="page-content">

        {{-- Flash --}}
        @if(session('success'))
            <div class="flash flash-success">
                <span class="flash-icon">✅</span>
                {{ session('success') }}
                <button onclick="this.parentElement.remove()" style="margin-left:auto;background:none;border:none;cursor:pointer;color:inherit;font-size:1rem;">✕</button>
            </div>
        @endif
        @if(session('error'))
            <div class="flash flash-error">
                <span class="flash-icon">❌</span>
                {{ session('error') }}
                <button onclick="this.parentElement.remove()" style="margin-left:auto;background:none;border:none;cursor:pointer;color:inherit;font-size:1rem;">✕</button>
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
            <div class="ph-left">
                <div class="ph-ref">{{ $documentTrouve->numero_declaration ?? 'DT-'.str_pad($documentTrouve->id,5,'0',STR_PAD_LEFT) }}</div>
                <div class="ph-title">
                    {{ $docIcon }} {{ $documentTrouve->type_document }}
                    @if($documentTrouve->nom_sur_document)
                        <small>au nom de {{ $documentTrouve->nom_sur_document }} {{ $documentTrouve->prenom_sur_document }}</small>
                    @endif
                </div>
                <span class="statut-pill {{ $s['class'] }}">
                    <span class="sp-dot {{ $s['pulse'] ? 'pulse' : '' }}"></span>
                    {{ $s['icon'] }} {{ $s['label'] }}
                </span>
            </div>
        </div>

        {{-- Stats row --}}
        <div class="stats-row">
            <div class="stat-card">
                <div class="stat-icon chi-navy" style="background:linear-gradient(135deg,#0f1f3d,#1a3358);">📅</div>
                <div>
                    <div class="stat-label">Date découverte</div>
                    <div class="stat-value" style="font-size:0.95rem;font-family:'DM Mono',monospace;">{{ \Carbon\Carbon::parse($documentTrouve->date_decouverte)->format('d/m/Y') }}</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background:linear-gradient(135deg,#f59e0b,#d97706);">📍</div>
                <div>
                    <div class="stat-label">Lieu découverte</div>
                    <div class="stat-value" style="font-size:0.82rem;font-weight:700;">{{ Str::limit($documentTrouve->lieu_decouverte, 22) }}</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon" style="background:linear-gradient(135deg,{{ $correspCount > 0 ? '#00c875,#00a85a' : '#94a3b8,#64748b' }});">🔍</div>
                <div>
                    <div class="stat-label">Correspondances</div>
                    <div class="stat-value" style="color:{{ $correspCount > 0 ? 'var(--green)' : 'var(--gray3)' }};">{{ $correspCount }}</div>
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
                                    <span class="ir-value" style="{{ $documentTrouve->user_id ? 'color:var(--green)' : '' }}">
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
                                    <span class="ir-value" style="font-family:'DM Mono',monospace;font-size:0.75rem;">
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
                            <div style="font-size:0.68rem;font-weight:700;color:var(--gray3);text-transform:uppercase;letter-spacing:1px;margin-bottom:0.4rem;font-family:'DM Mono',monospace;">État du document</div>
                            <p style="font-size:0.85rem;color:var(--gray4);line-height:1.7;">{{ $documentTrouve->description }}</p>
                        </div>
                        @endif
                        @if($documentTrouve->circonstances)
                        <div>
                            <div style="font-size:0.68rem;font-weight:700;color:var(--gray3);text-transform:uppercase;letter-spacing:1px;margin-bottom:0.4rem;font-family:'DM Mono',monospace;">Circonstances de la découverte</div>
                            <p style="font-size:0.85rem;color:var(--gray4);line-height:1.7;">{{ $documentTrouve->circonstances }}</p>
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
                                       style="font-size:0.78rem;color:var(--blue);font-weight:600;text-decoration:none;display:inline-flex;align-items:center;gap:0.3rem;">
                                        🔍 Voir en taille réelle
                                    </a>
                                </div>
                            @else
                                <div style="text-align:center;padding:1rem;">
                                    <a href="{{ Storage::url($documentTrouve->photo_document) }}" target="_blank"
                                       style="display:inline-flex;align-items:center;gap:0.5rem;background:var(--gray1);padding:0.75rem 1.5rem;border-radius:9px;color:var(--blue);font-weight:700;text-decoration:none;font-size:0.85rem;border:1.5px solid var(--gray2);">
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
                            <p style="font-size:0.72rem;color:var(--gray3);margin-top:0.85rem;padding-top:0.85rem;border-top:1px solid var(--gray2);line-height:1.5;">
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
                        <div class="card-head-icon" style="background:linear-gradient(135deg,#64748b,#475569);">📅</div>
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
                                    <div class="tl-label" style="{{ !in_array($statut,['matche','restitue']) ? 'color:var(--gray3)' : '' }}">
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
                                    <div class="tl-label" style="{{ $statut !== 'restitue' ? 'color:var(--gray3)' : '' }}">
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

    </div>{{-- fin page-content --}}
</div>{{-- fin main --}}


{{-- ==================== MODAL VALIDATION ==================== --}}
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
                    <div style="text-align:center;padding:1rem 0 0.5rem;color:var(--gray3);font-size:0.82rem;">
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

    /* ========== THEME ========== */
    function applyTheme(isDark) {
        document.body.classList.toggle('dark-mode', isDark);
        const icon = document.getElementById('themeIcon');
        if (icon) {
            icon.innerHTML = isDark
                ? '<path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>'
                : '<path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>';
        }
        localStorage.setItem('darkMode', isDark ? 'dark' : 'light');
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
        }).catch(() => {});
    }

    document.addEventListener('DOMContentLoaded', function() {
        const serverTheme = '{{ auth()->user()->theme ?? "light" }}';
        const localTheme = localStorage.getItem('darkMode');
        applyTheme((serverTheme || localTheme || 'light') === 'dark');
    });
</script>
</body>
</html>