<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Dossier Perte {{ $perte->numero_declaration ?? '' }} | Agent e-Déclaration TG</title>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Fraunces:wght@700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        :root {
            --c-navy:  #0c1a35;
            --c-navy2: #162544;
            --c-navy3: #1e3358;
            --c-green: #00d68f;
            --c-green2:#00b876;
            --c-blue:  #2563ff;
            --c-blue2: #1a4fd6;
            --c-amber: #f4a523;
            --c-red:   #f44336;
            --c-bg:    #f4f7fc;
            --c-white: #ffffff;
            --c-g1:    #f8fafd;
            --c-g2:    #eaeff8;
            --c-g3:    #c4cfe0;
            --c-g4:    #7a8ba8;
            --c-g5:    #3d5275;
        }

        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
        html { scroll-behavior: smooth; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: var(--c-bg);
            color: var(--c-navy);
            min-height: 100vh;
            display: flex;
        }

        @keyframes fadeUp  { from{opacity:0;transform:translateY(16px)} to{opacity:1;transform:translateY(0)} }
        @keyframes pulse   { 0%,100%{opacity:1} 50%{opacity:.25} }
        @keyframes scaleIn { from{opacity:0;transform:scale(.93) translateY(12px)} to{opacity:1;transform:scale(1) translateY(0)} }
        @keyframes fadeIn  { from{opacity:0} to{opacity:1} }

        .a1{animation:fadeUp .45s ease both}
        .a2{animation:fadeUp .45s .07s ease both}
        .a3{animation:fadeUp .45s .14s ease both}
        .a4{animation:fadeUp .45s .21s ease both}
        .a5{animation:fadeUp .45s .28s ease both}

        /* ═══ SIDEBAR ═══ */
        .sidebar {
            width:264px; min-width:264px;
            background:var(--c-navy);
            height:100vh; position:fixed; top:0; left:0;
            display:flex; flex-direction:column; z-index:200; overflow:hidden;
        }
        .sidebar::before {
            content:''; position:absolute; top:-80px; right:-80px;
            width:240px; height:240px;
            background:radial-gradient(circle,rgba(0,214,143,.15) 0%,transparent 70%);
            pointer-events:none;
        }
        .sb-head { padding:1.75rem 1.4rem 1.3rem; border-bottom:1px solid rgba(255,255,255,.07); position:relative; }
        .sb-brand { display:flex; align-items:center; gap:.8rem; margin-bottom:.9rem; }
        .sb-mark {
            width:42px; height:42px;
            background:linear-gradient(135deg,var(--c-green),var(--c-green2));
            border-radius:11px; display:flex; align-items:center; justify-content:center;
            font-size:1.3rem; flex-shrink:0;
            box-shadow:0 4px 14px rgba(0,214,143,.35);
        }
        .sb-name { font-family:'Fraunces',serif; font-weight:800; font-size:1rem; color:#fff; line-height:1.15; }
        .sb-sub  { font-family:'JetBrains Mono',monospace; font-size:.58rem; color:rgba(255,255,255,.3); letter-spacing:2px; text-transform:uppercase; }
        .sb-pill {
            display:inline-flex; align-items:center; gap:.4rem;
            background:rgba(0,214,143,.1); border:1px solid rgba(0,214,143,.2);
            color:var(--c-green); font-size:.62rem; font-weight:700;
            padding:.22rem .75rem; border-radius:50px; text-transform:uppercase; letter-spacing:.8px;
        }
        .live { width:6px; height:6px; background:var(--c-green); border-radius:50%; animation:pulse 1.8s infinite; }
        .sb-nav { flex:1; padding:1rem .9rem; overflow-y:auto; }
        .sb-sec  { font-family:'JetBrains Mono',monospace; font-size:.58rem; color:rgba(255,255,255,.22); letter-spacing:2.5px; text-transform:uppercase; padding:.85rem .5rem .35rem; }
        .sb-a {
            display:flex; align-items:center; gap:.65rem; padding:.62rem .8rem;
            border-radius:9px; text-decoration:none; color:rgba(255,255,255,.45);
            font-size:.835rem; font-weight:500; transition:all .18s; margin-bottom:.1rem; position:relative;
        }
        .sb-a:hover { background:rgba(255,255,255,.06); color:rgba(255,255,255,.8); }
        .sb-a.active { background:rgba(0,214,143,.1); color:var(--c-green); font-weight:700; }
        .sb-a.active::before { content:''; position:absolute; left:-1px; top:22%; bottom:22%; width:3px; background:var(--c-green); border-radius:0 3px 3px 0; }
        .sb-a svg { width:15px; height:15px; flex-shrink:0; }
        .sb-badge { margin-left:auto; background:var(--c-red); color:#fff; font-size:.58rem; font-weight:800; min-width:18px; height:18px; border-radius:9px; display:flex; align-items:center; justify-content:center; padding:0 4px; }
        .sb-foot { padding:.9rem; border-top:1px solid rgba(255,255,255,.07); }
        .sb-user { display:flex; align-items:center; gap:.6rem; padding:.5rem .6rem; border-radius:10px; background:rgba(255,255,255,.04); border:1px solid rgba(255,255,255,.07); margin-bottom:.7rem; }
        .sb-av { width:32px; height:32px; background:linear-gradient(135deg,var(--c-blue),var(--c-blue2)); border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:.72rem; font-weight:800; color:#fff; flex-shrink:0; }
        .sb-uname { font-size:.78rem; font-weight:700; color:rgba(255,255,255,.75); }
        .sb-urole { font-size:.62rem; color:rgba(255,255,255,.3); }
        .sb-out { width:100%; background:rgba(244,67,54,.1); color:#f87171; border:1px solid rgba(244,67,54,.2); padding:.6rem; border-radius:9px; font-size:.78rem; font-weight:700; cursor:pointer; display:flex; align-items:center; justify-content:center; gap:.45rem; transition:all .18s; font-family:'Plus Jakarta Sans',sans-serif; }
        .sb-out:hover { background:rgba(244,67,54,.18); color:#fca5a5; }

        /* ═══ MAIN ═══ */
        .main { margin-left:264px; flex:1; display:flex; flex-direction:column; min-height:100vh; }

        /* ═══ TOPBAR ═══ */
        .topbar { height:58px; background:var(--c-white); border-bottom:1px solid var(--c-g2); display:flex; align-items:center; justify-content:space-between; padding:0 2rem; position:sticky; top:0; z-index:100; }
        .bc { display:flex; align-items:center; gap:.4rem; font-size:.78rem; color:var(--c-g4); }
        .bc a { color:var(--c-g4); text-decoration:none; transition:color .15s; }
        .bc a:hover { color:var(--c-navy); }
        .bc-sep { opacity:.4; }
        .bc-cur { color:var(--c-navy); font-weight:700; }
        .tb-right { display:flex; align-items:center; gap:.6rem; }
        .tb-btn { width:36px; height:36px; background:var(--c-g1); border:1px solid var(--c-g2); border-radius:9px; display:flex; align-items:center; justify-content:center; cursor:pointer; transition:all .18s; position:relative; }
        .tb-btn:hover { background:var(--c-g2); transform:translateY(-1px); }
        .tb-btn svg { width:16px; height:16px; stroke:var(--c-g5); }
        .tb-dot { position:absolute; top:-2px; right:-2px; width:8px; height:8px; background:var(--c-red); border-radius:50%; border:2px solid var(--c-white); animation:pulse 2s infinite; }
        .tb-chip { display:flex; align-items:center; gap:.5rem; background:var(--c-g1); border:1px solid var(--c-g2); border-radius:50px; padding:.25rem .75rem .25rem .3rem; }
        .tb-chip-av { width:28px; height:28px; background:linear-gradient(135deg,var(--c-navy),var(--c-navy3)); border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:.65rem; font-weight:800; color:#fff; }
        .tb-chip-name { font-size:.75rem; font-weight:700; color:var(--c-navy); }

        /* ═══ PAGE ═══ */
        .page { padding:1.75rem 2rem 3rem; flex:1; max-width:1200px; margin:0 auto; width:100%; }

        /* ═══ HERO ═══ */
        .hero {
            background:var(--c-white); border:1px solid var(--c-g2); border-radius:20px;
            padding:1.75rem 2rem; margin-bottom:1.5rem; position:relative; overflow:hidden;
            box-shadow:0 4px 16px rgba(12,26,53,.09);
        }
        .hero::before { content:''; position:absolute; top:0; left:0; right:0; height:3px; background:linear-gradient(90deg,var(--c-navy),var(--c-green),var(--c-blue)); }
        .hero-inner { display:flex; align-items:flex-start; justify-content:space-between; gap:1.5rem; flex-wrap:wrap; }
        .hero-ref { font-family:'JetBrains Mono',monospace; font-size:.65rem; color:var(--c-g4); letter-spacing:2.5px; text-transform:uppercase; margin-bottom:.35rem; }
        .hero-title { font-family:'Fraunces',serif; font-size:1.75rem; font-weight:800; color:var(--c-navy); line-height:1.2; margin-bottom:.3rem; }
        .hero-sub { font-size:.88rem; color:var(--c-g4); margin-bottom:.85rem; }
        .hero-icon { width:76px; height:76px; background:linear-gradient(135deg,var(--c-navy),var(--c-navy3)); border-radius:18px; display:flex; align-items:center; justify-content:center; font-size:2.4rem; box-shadow:0 8px 28px rgba(12,26,53,.28); flex-shrink:0; }
        .hero-meta { display:flex; gap:2rem; padding-top:1.3rem; margin-top:1.3rem; border-top:1px solid var(--c-g2); flex-wrap:wrap; }
        .hm { display:flex; align-items:center; gap:.5rem; }
        .hm-ico { width:30px; height:30px; background:var(--c-g1); border:1px solid var(--c-g2); border-radius:7px; display:flex; align-items:center; justify-content:center; font-size:.8rem; flex-shrink:0; }
        .hm-l { font-size:.68rem; color:var(--c-g4); font-weight:500; }
        .hm-v { font-size:.84rem; color:var(--c-navy); font-weight:700; font-family:'JetBrains Mono',monospace; }

        /* statut */
        .sp { display:inline-flex; align-items:center; gap:.38rem; padding:.3rem .85rem; border-radius:50px; font-size:.72rem; font-weight:700; }
        .sp-dot { width:6px; height:6px; border-radius:50%; background:currentColor; }
        .sp-attente  { background:#fff7ed; color:#c2570c; border:1px solid #fed7aa; }
        .sp-validee  { background:#f0fdf4; color:#15803d; border:1px solid #bbf7d0; }
        .sp-rejetee  { background:#fff1f2; color:#be123c; border:1px solid #fecdd3; }

        /* ═══ FLASH ═══ */
        .flash { display:flex; align-items:center; gap:.75rem; padding:.85rem 1.25rem; border-radius:10px; margin-bottom:1.5rem; font-size:.84rem; font-weight:600; animation:fadeUp .3s ease; }
        .flash-ok  { background:#f0fdf4; border:1.5px solid #86efac; color:#15803d; }
        .flash-err { background:#fff1f2; border:1.5px solid #fca5a5; color:#be123c; }

        /* ═══ GRID ═══ */
        .grid { display:grid; grid-template-columns:1fr 320px; gap:1.4rem; align-items:start; }

        /* ═══ CARDS ═══ */
        .card { background:var(--c-white); border:1px solid var(--c-g2); border-radius:14px; overflow:hidden; box-shadow:0 1px 4px rgba(12,26,53,.06); margin-bottom:1.25rem; transition:box-shadow .22s, transform .22s; }
        .card:last-child { margin-bottom:0; }
        .card:hover { box-shadow:0 4px 16px rgba(12,26,53,.09); transform:translateY(-1px); }
        .card-head { display:flex; align-items:center; gap:.7rem; padding:1rem 1.3rem; border-bottom:1px solid var(--c-g2); background:var(--c-g1); }
        .chi { width:32px; height:32px; border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:.9rem; flex-shrink:0; }
        .chi-n { background:linear-gradient(135deg,#0c1a35,#1e3358); }
        .chi-g { background:linear-gradient(135deg,#00d68f,#00b876); }
        .chi-b { background:linear-gradient(135deg,#2563ff,#1a4fd6); }
        .chi-a { background:linear-gradient(135deg,#f4a523,#e08e10); }
        .chi-p { background:linear-gradient(135deg,#8b5cf6,#7c3aed); }
        .chi-r { background:linear-gradient(135deg,#f44336,#d32f2f); }
        .chi-s { background:linear-gradient(135deg,#64748b,#475569); }
        .ch-t { font-weight:700; font-size:.86rem; color:var(--c-navy); }
        .ch-s { font-size:.7rem; color:var(--c-g4); margin-top:.05rem; }
        .card-body { padding:1.2rem 1.3rem; }

        /* Info rows */
        .ir { display:flex; justify-content:space-between; align-items:center; padding:.5rem 0; border-bottom:1px solid var(--c-g2); gap:1rem; }
        .ir:last-child { border:none; padding-bottom:0; }
        .ir-l { font-size:.72rem; color:var(--c-g4); font-weight:500; flex-shrink:0; }
        .ir-v { font-size:.83rem; color:var(--c-navy); font-weight:700; text-align:right; }
        .ir-empty { color:var(--c-g3); font-style:italic; font-weight:400; }
        .ir-link { color:var(--c-blue); text-decoration:none; }
        .ir-link:hover { text-decoration:underline; }

        .two-col { display:grid; grid-template-columns:1fr 1fr; gap:0 1.5rem; }

        /* File chips */
        .file-chip {
            display:inline-flex; align-items:center; gap:.5rem;
            background:var(--c-g1); border:1.5px solid var(--c-g2);
            border-radius:9px; padding:.5rem .9rem;
            font-size:.78rem; font-weight:600; text-decoration:none;
            color:var(--c-blue); transition:all .18s; margin:.25rem .25rem 0 0;
        }
        .file-chip:hover { background:#eff6ff; border-color:var(--c-blue); }
        .file-chip svg { width:14px; height:14px; flex-shrink:0; }

        /* Timeline */
        .tl { display:flex; flex-direction:column; }
        .tl-item { display:flex; gap:.85rem; padding-bottom:1.1rem; position:relative; }
        .tl-item:last-child { padding-bottom:0; }
        .tl-track { position:absolute; left:14px; top:30px; bottom:0; width:1px; background:var(--c-g2); }
        .tl-item:last-child .tl-track { display:none; }
        .tl-bub { width:30px; height:30px; border-radius:50%; display:flex; align-items:center; justify-content:center; font-size:.8rem; flex-shrink:0; z-index:1; }
        .tl-done    { background:#dcfce7; border:2px solid #86efac; }
        .tl-current { background:#dbeafe; border:2px solid #93c5fd; }
        .tl-pending { background:var(--c-g1); border:2px dashed var(--c-g3); opacity:.55; }
        .tl-label { font-size:.82rem; font-weight:700; color:var(--c-navy); }
        .tl-date  { font-size:.68rem; color:var(--c-g4); margin-top:.08rem; font-family:'JetBrains Mono',monospace; }

        /* ═══ ACTIONS PANEL ═══ */
        .actions-panel {
            background:var(--c-navy); border-radius:16px; overflow:hidden;
            margin-bottom:1.25rem; box-shadow:0 12px 40px rgba(12,26,53,.3); position:relative;
        }
        .actions-panel::before { content:''; position:absolute; bottom:-60px; right:-60px; width:200px; height:200px; background:radial-gradient(circle,rgba(37,99,255,.15) 0%,transparent 70%); pointer-events:none; }
        .ap-head { padding:1.1rem 1.4rem .85rem; border-bottom:1px solid rgba(255,255,255,.07); display:flex; align-items:center; justify-content:space-between; }
        .ap-label { font-family:'JetBrains Mono',monospace; font-size:.6rem; font-weight:500; color:rgba(255,255,255,.3); text-transform:uppercase; letter-spacing:2px; }
        .ap-st { font-family:'JetBrains Mono',monospace; font-size:.6rem; color:rgba(255,255,255,.25); }
        .ap-body { padding:1.1rem; }
        .ap-btn { display:flex; align-items:center; gap:.75rem; width:100%; padding:.88rem 1rem; border-radius:11px; border:none; font-size:.82rem; font-weight:700; cursor:pointer; transition:all .2s; text-decoration:none; text-align:left; margin-bottom:.6rem; font-family:'Plus Jakarta Sans',sans-serif; }
        .ap-btn:last-child { margin-bottom:0; }
        .ap-btn svg { width:16px; height:16px; flex-shrink:0; }
        .apb-green  { background:linear-gradient(135deg,var(--c-green),var(--c-green2)); color:#fff; box-shadow:0 4px 16px rgba(0,214,143,.35); }
        .apb-green:hover  { transform:translateY(-2px); box-shadow:0 8px 24px rgba(0,214,143,.45); }
        .apb-red    { background:linear-gradient(135deg,#ef4444,#dc2626); color:#fff; box-shadow:0 4px 16px rgba(239,68,68,.3); }
        .apb-red:hover    { transform:translateY(-2px); box-shadow:0 8px 24px rgba(239,68,68,.4); }
        .apb-ghost  { background:rgba(255,255,255,.06); color:rgba(255,255,255,.5); border:1px solid rgba(255,255,255,.09); }
        .apb-ghost:hover  { background:rgba(255,255,255,.1); color:rgba(255,255,255,.75); }
        .apb-off    { background:rgba(255,255,255,.03); color:rgba(255,255,255,.2); border:1px dashed rgba(255,255,255,.08); cursor:not-allowed; }
        .apb-txt { flex:1; }
        .apb-sub { display:block; font-size:.65rem; font-weight:400; opacity:.7; margin-top:.06rem; }
        .ap-sep  { border:none; border-top:1px solid rgba(255,255,255,.07); margin:.65rem 0; }

        /* Reject modal */
        .overlay { display:none; position:fixed; inset:0; background:rgba(8,18,38,.75); backdrop-filter:blur(8px); z-index:900; align-items:center; justify-content:center; padding:1rem; }
        .overlay.open { display:flex; }
        .modal { background:var(--c-white); border-radius:20px; width:100%; max-width:480px; box-shadow:0 40px 100px rgba(8,18,38,.5); animation:scaleIn .3s cubic-bezier(.34,1.2,.64,1); overflow:hidden; }
        .modal-head { padding:1.4rem 1.5rem 1.1rem; border-bottom:1px solid var(--c-g2); display:flex; align-items:flex-start; justify-content:space-between; gap:1rem; }
        .mh-ico { width:40px; height:40px; background:linear-gradient(135deg,#ef4444,#dc2626); border-radius:10px; display:flex; align-items:center; justify-content:center; font-size:1.1rem; flex-shrink:0; }
        .mh-title { font-family:'Fraunces',serif; font-size:1.1rem; font-weight:800; color:var(--c-navy); }
        .mh-sub   { font-size:.76rem; color:var(--c-g4); margin-top:.15rem; line-height:1.5; }
        .modal-x  { width:30px; height:30px; background:var(--c-g1); border:1px solid var(--c-g2); border-radius:7px; cursor:pointer; display:flex; align-items:center; justify-content:center; color:var(--c-g5); font-size:.9rem; flex-shrink:0; transition:all .15s; }
        .modal-x:hover { background:var(--c-g2); }
        .modal-body { padding:1.3rem 1.5rem; }
        .field-label { font-size:.74rem; font-weight:700; color:var(--c-g5); margin-bottom:.4rem; }
        .field-textarea { width:100%; padding:.8rem .9rem; border:1.5px solid var(--c-g2); border-radius:9px; font-size:.84rem; font-family:'Plus Jakarta Sans',sans-serif; color:var(--c-navy); resize:vertical; min-height:110px; transition:border-color .15s; line-height:1.6; }
        .field-textarea:focus { outline:none; border-color:var(--c-red); }
        .field-hint { font-size:.68rem; color:var(--c-g4); margin-top:.3rem; }
        .char-count { font-size:.68rem; color:var(--c-g4); text-align:right; margin-top:.25rem; font-family:'JetBrains Mono',monospace; }
        .modal-foot { padding:1rem 1.5rem; border-top:1px solid var(--c-g2); background:var(--c-g1); display:flex; gap:.7rem; }
        .btn-cancel { padding:.72rem 1.3rem; background:var(--c-white); color:var(--c-g5); border:1.5px solid var(--c-g2); border-radius:9px; font-weight:700; font-size:.82rem; cursor:pointer; transition:all .15s; font-family:'Plus Jakarta Sans',sans-serif; }
        .btn-cancel:hover { background:var(--c-g2); }
        .btn-reject { flex:1; background:linear-gradient(135deg,#ef4444,#dc2626); color:#fff; border:none; border-radius:9px; font-weight:800; font-size:.84rem; cursor:pointer; transition:all .2s; display:flex; align-items:center; justify-content:center; gap:.5rem; font-family:'Plus Jakarta Sans',sans-serif; }
        .btn-reject:hover { transform:translateY(-1px); box-shadow:0 6px 18px rgba(239,68,68,.35); }

        /* Right panel sticky */
        .right-col { position:sticky; top:74px; }

        /* ═══ DARK MODE ═══ */
        body.dark-mode { background:#0a1220; color:#dce8f5; }
        body.dark-mode .sidebar { background:#0c1a35; }
        body.dark-mode .topbar { background:#101e36; border-color:#1c2d4a; }
        body.dark-mode .tb-btn { background:#141f35; border-color:#1c2d4a; }
        body.dark-mode .tb-btn svg { stroke:#8ba5c8; }
        body.dark-mode .tb-chip { background:#141f35; border-color:#1c2d4a; }
        body.dark-mode .tb-chip-name { color:#dce8f5; }
        body.dark-mode .hero { background:#101e36; border-color:#1c2d4a; }
        body.dark-mode .hero-title { color:#dce8f5; }
        body.dark-mode .hero-meta { border-color:#1c2d4a; }
        body.dark-mode .hm-ico { background:#1c2d4a; border-color:#2d4166; }
        body.dark-mode .hm-v { color:#dce8f5; }
        body.dark-mode .card { background:#101e36; border-color:#1c2d4a; }
        body.dark-mode .card-head { background:#141f35; border-color:#1c2d4a; }
        body.dark-mode .ch-t { color:#dce8f5; }
        body.dark-mode .ir { border-color:#1c2d4a; }
        body.dark-mode .ir-v { color:#dce8f5; }
        body.dark-mode .file-chip { background:#141f35; border-color:#1c2d4a; color:#93c5fd; }
        body.dark-mode .file-chip:hover { background:#1c2d4a; }
        body.dark-mode .tl-track { background:#1c2d4a; }
        body.dark-mode .tl-label { color:#dce8f5; }
        body.dark-mode .modal { background:#101e36; }
        body.dark-mode .modal-head { border-color:#1c2d4a; }
        body.dark-mode .mh-title { color:#dce8f5; }
        body.dark-mode .modal-foot { background:#141f35; border-color:#1c2d4a; }
        body.dark-mode .btn-cancel { background:#101e36; border-color:#1c2d4a; color:#8ba5c8; }
        body.dark-mode .field-textarea { background:#141f35; border-color:#1c2d4a; color:#dce8f5; }

        @media(max-width:1200px) { .grid { grid-template-columns:1fr 290px; } }
        @media(max-width:1024px) { .sidebar{transform:translateX(-100%)} .main{margin-left:0} .grid{grid-template-columns:1fr} .right-col{position:static} }
        @media(max-width:640px)  { .page{padding:1rem} .topbar{padding:0 1rem} .two-col{grid-template-columns:1fr} }
    </style>
</head>
<body>

@php
    $pendingCount = \App\Models\Perte::where('statut','en_attente')->count();
    $statut = $perte->statut;
    $sMap = [
        'en_attente' => ['label'=>'En attente', 'class'=>'sp-attente', 'icon'=>'⏳', 'pulse'=>true],
        'validee'    => ['label'=>'Validée',    'class'=>'sp-validee',  'icon'=>'✅', 'pulse'=>false],
        'rejetee'    => ['label'=>'Rejetée',    'class'=>'sp-rejetee',  'icon'=>'❌', 'pulse'=>false],
    ];
    $s = $sMap[$statut] ?? $sMap['en_attente'];
    $docIcons = ['Passeport'=>'🛂',"Carte d'identité (CNI)"=>'🪪','Permis de conduire'=>'🚗',"Carte d'électeur"=>'🗳️','Acte de naissance'=>'📋','Certificat de nationalité'=>'📜'];
    $docIcon = $docIcons[$perte->type_piece] ?? '📄';
    $ref = $perte->numero_declaration ?? 'DL-'.str_pad($perte->id, 5, '0', STR_PAD_LEFT);
    $initials = strtoupper(substr(auth()->user()->first_name ?? auth()->user()->name ?? 'A', 0,1) . substr(auth()->user()->last_name ?? '', 0,1));
@endphp

<!-- SIDEBAR -->
<aside class="sidebar">
    <div class="sb-head">
        <div class="sb-brand">
            <div class="sb-mark">🇹🇬</div>
            <div>
                <div class="sb-name">e-Déclaration TG</div>
                <div class="sb-sub">Espace Agent</div>
            </div>
        </div>
        <div class="sb-pill"><div class="live"></div>Agent Vérificateur</div>
    </div>

    <nav class="sb-nav">
        <div class="sb-sec">Dashboard</div>
        <a href="{{ route('agent.dashboard') }}" class="sb-a active">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zm10 0a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
            Vue d'ensemble
        </a>

        <div class="sb-sec">Déclarations</div>
        <a href="{{ route('agent.dashboard') }}" class="sb-a">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
            Pertes déclarées
            @if($pendingCount > 0)<span class="sb-badge">{{ $pendingCount }}</span>@endif
        </a>
        <a href="{{ route('agent.documents-trouves.index') }}" class="sb-a">
            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            Documents trouvés
        </a>
    </nav>

    <div class="sb-foot">
        <div class="sb-user">
            <div class="sb-av">{{ $initials }}</div>
            <div>
                <div class="sb-uname">{{ auth()->user()->first_name ?? auth()->user()->name }}</div>
                <div class="sb-urole">Agent vérificateur</div>
            </div>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="sb-out">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="width:13px;height:13px;"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                Déconnexion
            </button>
        </form>
    </div>
</aside>

<!-- MAIN -->
<div class="main">
    <header class="topbar">
        <nav class="bc">
            <a href="{{ route('agent.dashboard') }}">Accueil</a>
            <span class="bc-sep">/</span>
            <a href="{{ route('agent.dashboard') }}">Déclarations</a>
            <span class="bc-sep">/</span>
            <span class="bc-cur">{{ $ref }}</span>
        </nav>
        <div class="tb-right">
            <button class="tb-btn" onclick="toggleGlobalDarkMode()" title="Thème">
                <svg id="themeIcon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
            </button>
            <button class="tb-btn" onclick="window.location.href='{{ route('agent.dashboard') }}'" style="position:relative;">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                @if($pendingCount > 0)<span class="tb-dot"></span>@endif
            </button>
            <div class="tb-chip">
                <div class="tb-chip-av">{{ $initials }}</div>
                <span class="tb-chip-name">{{ auth()->user()->first_name ?? auth()->user()->name }}</span>
            </div>
        </div>
    </header>

    <div class="page">

        @if(session('success'))
        <div class="flash flash-ok a1">
            <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="width:17px;height:17px;flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('success') }}
            <button onclick="this.parentElement.remove()" style="margin-left:auto;background:none;border:none;cursor:pointer;font-size:1rem;color:inherit;">✕</button>
        </div>
        @endif
        @if(session('error'))
        <div class="flash flash-err a1">
            <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="width:17px;height:17px;flex-shrink:0;"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            {{ session('error') }}
            <button onclick="this.parentElement.remove()" style="margin-left:auto;background:none;border:none;cursor:pointer;font-size:1rem;color:inherit;">✕</button>
        </div>
        @endif

        {{-- HERO --}}
        <div class="hero a2">
            <div class="hero-inner">
                <div>
                    <div class="hero-ref">Déclaration de perte · {{ $ref }}</div>
                    <div class="hero-title">{{ $perte->type_piece }}</div>
                    <div class="hero-sub">{{ $perte->first_name }} {{ $perte->last_name }}</div>
                    <span class="sp {{ $s['class'] }}">
                        <span class="sp-dot" style="{{ $s['pulse'] ? 'animation:pulse 1.8s infinite' : '' }}"></span>
                        {{ $s['icon'] }} {{ $s['label'] }}
                    </span>
                </div>
                <div class="hero-icon">{{ $docIcon }}</div>
            </div>
            <div class="hero-meta">
                <div class="hm"><div class="hm-ico">📅</div><div><div class="hm-l">Date de perte</div><div class="hm-v">{{ \Carbon\Carbon::parse($perte->date_perte)->format('d/m/Y') }}</div></div></div>
                <div class="hm"><div class="hm-ico">📍</div><div><div class="hm-l">Lieu</div><div class="hm-v" style="font-family:'Plus Jakarta Sans',sans-serif;font-size:.78rem;">{{ $perte->lieu_perte }}</div></div></div>
                <div class="hm"><div class="hm-ico">⏰</div><div><div class="hm-l">Déclaré le</div><div class="hm-v">{{ \Carbon\Carbon::parse($perte->date_declaration ?? $perte->created_at)->format('d/m/Y H:i') }}</div></div></div>
                @if($perte->numero_piece)
                <div class="hm"><div class="hm-ico">🔢</div><div><div class="hm-l">N° de pièce</div><div class="hm-v">{{ $perte->numero_piece }}</div></div></div>
                @endif
            </div>
        </div>

        {{-- GRID --}}
        <div class="grid">

            {{-- LEFT --}}
            <div>

                {{-- Déclarant --}}
                <div class="card a3">
                    <div class="card-head">
                        <div class="chi chi-g">👤</div>
                        <div><div class="ch-t">Identité du déclarant</div><div class="ch-s">Informations personnelles du citoyen</div></div>
                    </div>
                    <div class="card-body">
                        <div class="two-col">
                            <div>
                                <div class="ir"><span class="ir-l">Nom</span><span class="ir-v">{{ $perte->last_name }}</span></div>
                                <div class="ir"><span class="ir-l">Prénom</span><span class="ir-v">{{ $perte->first_name }}</span></div>
                                <div class="ir"><span class="ir-l">Contact</span><span class="ir-v"><a href="tel:{{ $perte->contact }}" class="ir-link">{{ $perte->contact }}</a></span></div>
                            </div>
                            <div>
                                <div class="ir"><span class="ir-l">Adresse email</span><span class="ir-v"><a href="mailto:{{ $perte->email }}" class="ir-link" style="font-size:.75rem;">{{ $perte->email }}</a></span></div>
                                @if($perte->user)
                                <div class="ir"><span class="ir-l">Compte lié</span><span class="ir-v" style="color:var(--c-green2);">✅ Citoyen enregistré</span></div>
                                <div class="ir"><span class="ir-l">ID citoyen</span><span class="ir-v" style="font-family:'JetBrains Mono',monospace;font-size:.72rem;">#{{ $perte->user_id }}</span></div>
                                @else
                                <div class="ir"><span class="ir-l">Compte</span><span class="ir-v ir-empty">Non enregistré</span></div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Document --}}
                <div class="card a3">
                    <div class="card-head">
                        <div class="chi chi-b">📄</div>
                        <div><div class="ch-t">Informations du document perdu</div><div class="ch-s">Données de la pièce déclarée perdue</div></div>
                    </div>
                    <div class="card-body">
                        <div class="two-col">
                            <div>
                                <div class="ir"><span class="ir-l">Type de pièce</span><span class="ir-v">{{ $perte->type_piece }}</span></div>
                                <div class="ir"><span class="ir-l">Numéro de pièce</span><span class="ir-v {{ !$perte->numero_piece ? 'ir-empty' : '' }}">{{ $perte->numero_piece ?? 'Non renseigné' }}</span></div>
                                <div class="ir"><span class="ir-l">Date de délivrance</span><span class="ir-v {{ !$perte->date_delivrance ? 'ir-empty' : '' }}">{{ $perte->date_delivrance ? \Carbon\Carbon::parse($perte->date_delivrance)->format('d/m/Y') : 'Non renseignée' }}</span></div>
                            </div>
                            <div>
                                <div class="ir"><span class="ir-l">Autorité délivrance</span><span class="ir-v {{ !$perte->autorite_delivrance ? 'ir-empty' : '' }}">{{ $perte->autorite_delivrance ?? 'Non renseignée' }}</span></div>
                                <div class="ir"><span class="ir-l">Date de perte</span><span class="ir-v">{{ \Carbon\Carbon::parse($perte->date_perte)->format('d/m/Y') }}</span></div>
                                <div class="ir"><span class="ir-l">Lieu de perte</span><span class="ir-v">{{ $perte->lieu_perte }}</span></div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Circonstances --}}
                @if($perte->circonstances)
                <div class="card a4">
                    <div class="card-head">
                        <div class="chi chi-a">📝</div>
                        <div><div class="ch-t">Circonstances de la perte</div></div>
                    </div>
                    <div class="card-body">
                        <p style="font-size:.85rem;color:var(--c-g5);line-height:1.8;">{{ $perte->circonstances }}</p>
                    </div>
                </div>
                @endif

                {{-- Pièces jointes --}}
                @if($perte->copie_piece || $perte->declaration_vol || $perte->document_complementaire)
                <div class="card a4">
                    <div class="card-head">
                        <div class="chi chi-p">📎</div>
                        <div><div class="ch-t">Pièces jointes</div><div class="ch-s">Documents soumis par le citoyen</div></div>
                    </div>
                    <div class="card-body">
                        <div>
                            @if($perte->copie_piece)
                                @php $ext = strtolower(pathinfo($perte->copie_piece, PATHINFO_EXTENSION)); @endphp
                                @if(in_array($ext, ['jpg','jpeg','png','gif','webp']))
                                    <div style="margin-bottom:1rem;">
                                        <div style="font-family:'JetBrains Mono',monospace;font-size:.62rem;font-weight:500;color:var(--c-g4);text-transform:uppercase;letter-spacing:1.5px;margin-bottom:.4rem;">Copie de la pièce</div>
                                        <img src="{{ Storage::url($perte->copie_piece) }}" style="max-width:100%;max-height:200px;border-radius:8px;border:1.5px solid var(--c-g2);object-fit:contain;" alt="Copie pièce">
                                        <div style="margin-top:.4rem;"><a href="{{ Storage::url($perte->copie_piece) }}" target="_blank" style="font-size:.75rem;color:var(--c-blue);font-weight:600;text-decoration:none;">🔍 Voir en taille réelle</a></div>
                                    </div>
                                @endif
                            @endif
                            <div style="display:flex;flex-wrap:wrap;gap:.5rem;margin-top:.25rem;">
                                @if($perte->copie_piece)
                                <a href="{{ Storage::url($perte->copie_piece) }}" target="_blank" class="file-chip">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    Copie de la pièce
                                </a>
                                @endif
                                @if($perte->declaration_vol)
                                <a href="{{ Storage::url($perte->declaration_vol) }}" target="_blank" class="file-chip">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    Déclaration de vol
                                </a>
                                @endif
                                @if($perte->document_complementaire)
                                <a href="{{ Storage::url($perte->document_complementaire) }}" target="_blank" class="file-chip">
                                    <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                    Doc. complémentaire
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Traitement --}}
                @if($statut !== 'en_attente')
                <div class="card a4">
                    <div class="card-head">
                        <div class="chi {{ $statut === 'validee' ? 'chi-g' : 'chi-r' }}">{{ $statut === 'validee' ? '✅' : '❌' }}</div>
                        <div><div class="ch-t">Traitement de la déclaration</div><div class="ch-s">Décision prise par l'agent</div></div>
                    </div>
                    <div class="card-body">
                        <div class="ir"><span class="ir-l">Décision</span><span class="ir-v" style="color:{{ $statut === 'validee' ? 'var(--c-green2)' : 'var(--c-red)' }}">{{ $statut === 'validee' ? '✅ Validée' : '❌ Rejetée' }}</span></div>
                        @if($perte->validator)
                        <div class="ir"><span class="ir-l">Agent traitant</span><span class="ir-v">{{ $perte->validator->first_name ?? $perte->validator->name }} {{ $perte->validator->last_name ?? '' }}</span></div>
                        @endif
                        @if($perte->validated_at)
                        <div class="ir"><span class="ir-l">Date de traitement</span><span class="ir-v" style="font-family:'JetBrains Mono',monospace;font-size:.75rem;">{{ \Carbon\Carbon::parse($perte->validated_at)->format('d/m/Y à H:i') }}</span></div>
                        @endif
                        @if($perte->motif_rejet && $statut === 'rejetee')
                        <div style="margin-top:.8rem;padding-top:.8rem;border-top:1px solid var(--c-g2);">
                            <div style="font-family:'JetBrains Mono',monospace;font-size:.62rem;font-weight:500;color:var(--c-g4);text-transform:uppercase;letter-spacing:1.5px;margin-bottom:.4rem;">Motif du rejet</div>
                            <div style="background:#fff1f2;border:1px solid #fecdd3;border-radius:9px;padding:.75rem 1rem;font-size:.83rem;color:#be123c;line-height:1.65;">{{ $perte->motif_rejet }}</div>
                        </div>
                        @endif
                    </div>
                </div>
                @endif

            </div>{{-- /left --}}

            {{-- RIGHT --}}
            <div class="right-col">

                <!-- Actions -->
                <div class="actions-panel a3">
                    <div class="ap-head">
                        <div style="display:flex;align-items:center;gap:.5rem;">
                            <div style="width:8px;height:8px;background:var(--c-green);border-radius:50%;animation:pulse 1.8s infinite;"></div>
                            <div class="ap-label">Actions Agent</div>
                        </div>
                        <div class="ap-st">{{ strtoupper($statut) }}</div>
                    </div>
                    <div class="ap-body">

                        @if($statut === 'en_attente')
                            {{-- VALIDER --}}
                            <form method="POST" action="{{ route('agent.perte.valider', $perte->id) }}">
                                @csrf
                                <button type="submit" class="ap-btn apb-green"
                                        onclick="return confirm('Confirmer la validation de cette déclaration ?')">
                                    <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    <div class="apb-txt">
                                        Valider la déclaration
                                        <span class="apb-sub">Notifie le citoyen · génère un N° officiel</span>
                                    </div>
                                </button>
                            </form>

                            <hr class="ap-sep">

                            {{-- REJETER --}}
                            <button type="button" class="ap-btn apb-red" onclick="openRejectModal()">
                                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <div class="apb-txt">
                                    Rejeter la déclaration
                                    <span class="apb-sub">Requiert un motif explicatif</span>
                                </div>
                            </button>

                        @elseif($statut === 'validee')
                            <div class="ap-btn apb-off">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <div class="apb-txt">Déclaration validée ✅ <span class="apb-sub">Aucune action requise</span></div>
                            </div>

                        @else
                            <div class="ap-btn apb-off">
                                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636"/></svg>
                                <div class="apb-txt">Déclaration rejetée ❌ <span class="apb-sub">Aucune action disponible</span></div>
                            </div>
                        @endif

                        <hr class="ap-sep">

                        <a href="{{ route('agent.dashboard') }}" class="ap-btn apb-ghost" style="text-decoration:none;">
                            <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            <div class="apb-txt">Retour à la liste <span class="apb-sub">Dashboard des déclarations</span></div>
                        </a>
                    </div>
                </div>

                <!-- Timeline -->
                <div class="card a4">
                    <div class="card-head">
                        <div class="chi chi-s">📋</div>
                        <div><div class="ch-t">Historique du dossier</div></div>
                    </div>
                    <div class="card-body">
                        <div class="tl">
                            <div class="tl-item">
                                <div class="tl-track"></div>
                                <div class="tl-bub tl-done">📋</div>
                                <div>
                                    <div class="tl-label">Déclaration soumise</div>
                                    <div class="tl-date">{{ \Carbon\Carbon::parse($perte->date_declaration ?? $perte->created_at)->format('d/m/Y · H:i') }}</div>
                                </div>
                            </div>

                            <div class="tl-item">
                                <div class="tl-track"></div>
                                <div class="tl-bub {{ in_array($statut,['validee','rejetee']) ? 'tl-done' : 'tl-current' }}">👁️</div>
                                <div>
                                    <div class="tl-label {{ $statut === 'en_attente' ? '' : '' }}">Examinée par un agent</div>
                                    <div class="tl-date">
                                        @if($perte->validated_at) {{ \Carbon\Carbon::parse($perte->validated_at)->format('d/m/Y · H:i') }}
                                        @elseif($statut === 'en_attente') En cours…
                                        @else N/A
                                        @endif
                                    </div>
                                </div>
                            </div>

                            <div class="tl-item">
                                <div class="tl-bub {{ $statut === 'validee' ? 'tl-done' : ($statut === 'rejetee' ? 'tl-done' : 'tl-pending') }}">
                                    {{ $statut === 'validee' ? '✅' : ($statut === 'rejetee' ? '❌' : '⏳') }}
                                </div>
                                <div>
                                    <div class="tl-label {{ $statut === 'en_attente' ? 'tl-pending' : '' }}">
                                        Décision finale
                                    </div>
                                    <div class="tl-date">
                                        @if($perte->validated_at) {{ \Carbon\Carbon::parse($perte->validated_at)->format('d/m/Y · H:i') }}
                                        @else En attente
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Numéro officiel --}}
                @if($perte->numero_declaration)
                <div class="card a5">
                    <div class="card-head">
                        <div class="chi chi-n">🔒</div>
                        <div><div class="ch-t">Référence officielle</div></div>
                    </div>
                    <div class="card-body" style="text-align:center;padding:1.5rem;">
                        <div style="font-family:'JetBrains Mono',monospace;font-size:1.2rem;font-weight:700;color:var(--c-navy);background:var(--c-g1);border:1.5px solid var(--c-g2);border-radius:10px;padding:.75rem 1rem;letter-spacing:2px;">
                            {{ $perte->numero_declaration }}
                        </div>
                        <div style="font-size:.72rem;color:var(--c-g4);margin-top:.5rem;">Numéro de déclaration officiel</div>
                    </div>
                </div>
                @endif

            </div>{{-- /right --}}
        </div>{{-- /grid --}}
    </div>{{-- /page --}}
</div>{{-- /main --}}


<!-- MODAL REJET -->
<div class="overlay" id="rejectOverlay">
    <div class="modal">
        <div class="modal-head">
            <div style="display:flex;align-items:flex-start;gap:.85rem;">
                <div class="mh-ico">❌</div>
                <div>
                    <div class="mh-title">Rejeter la déclaration</div>
                    <div class="mh-sub">Indiquez un motif clair. Le citoyen sera notifié automatiquement.</div>
                </div>
            </div>
            <button class="modal-x" onclick="closeRejectModal()">✕</button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('agent.perte.rejeter', $perte->id) }}" id="rejectForm">
                @csrf
                <div class="field-label">Motif du rejet <span style="color:var(--c-red)">*</span></div>
                <textarea name="motif_rejet" id="motifTextarea" class="field-textarea"
                          placeholder="Ex : Informations incomplètes, documents illisibles, doublon de déclaration…"
                          maxlength="500" oninput="updateCount()"></textarea>
                <div class="char-count"><span id="charCount">0</span> / 500</div>
                <div class="field-hint">Minimum 10 caractères · Maximum 500 caractères.</div>
            </form>
        </div>
        <div class="modal-foot">
            <button type="button" class="btn-cancel" onclick="closeRejectModal()">Annuler</button>
            <button type="button" class="btn-reject" onclick="submitReject()">
                <svg fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="width:15px;height:15px;"><path stroke-linecap="round" stroke-linejoin="round" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Confirmer le rejet
            </button>
        </div>
    </div>
</div>

<script>
    /* Modal rejet */
    function openRejectModal()  { document.getElementById('rejectOverlay').classList.add('open'); document.body.style.overflow='hidden'; document.getElementById('motifTextarea').focus(); }
    function closeRejectModal() { document.getElementById('rejectOverlay').classList.remove('open'); document.body.style.overflow=''; }
    document.getElementById('rejectOverlay').addEventListener('click', function(e) { if(e.target===this) closeRejectModal(); });
    document.addEventListener('keydown', function(e) { if(e.key==='Escape') closeRejectModal(); });

    function updateCount() {
        const v = document.getElementById('motifTextarea').value.length;
        document.getElementById('charCount').textContent = v;
        document.getElementById('charCount').style.color = v > 450 ? '#ef4444' : '';
    }

    function submitReject() {
        const v = document.getElementById('motifTextarea').value.trim();
        if (v.length < 10) { alert('⚠️ Le motif doit contenir au moins 10 caractères.'); return; }
        if (confirm('Confirmer le rejet de cette déclaration ? Une notification sera envoyée au citoyen.')) {
            document.getElementById('rejectForm').submit();
        }
    }

    /* Thème */
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
            method:'POST',
            headers:{'X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]')?.content,'Content-Type':'application/json','Accept':'application/json'},
            body:JSON.stringify({dark_mode:isDark})
        }).catch(()=>{});
    }
    document.addEventListener('DOMContentLoaded', function() {
        const srv = '{{ auth()->user()->theme ?? "light" }}';
        const loc = localStorage.getItem('darkMode');
        applyTheme((srv||loc||'light')==='dark');
    });
</script>
</body>
</html>