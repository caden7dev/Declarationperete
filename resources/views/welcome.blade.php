<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>e-Déclaration TG — Plateforme Nationale · République Togolaise</title>
    <meta name="description" content="Plateforme officielle de déclaration de perte de documents de la République Togolaise.">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        * { 
            box-sizing: border-box; 
            margin: 0; 
            padding: 0; 
            font-family: 'Inter', sans-serif;
        }

        :root {
            --primary: #10b981;
            --primary-dark: #059669;
            --secondary: #3b82f6;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark: #0f172a;
            --gray-100: #f8fafc;
            --gray-200: #e2e8f0;
            --gray-600: #64748b;
            --gray-800: #1e293b;
            --whatsapp: #25D366;
        }

        body { 
            min-height: 100vh; 
            position: relative;
            background: #f5f7fa;
            transition: background 0.2s ease;
        }

        body.dark-mode {
            background: #0f172a;
        }

        /* Image de fond */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url('{{ asset("images/image3.jpeg") }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            z-index: -2;
            filter: brightness(0.85);
        }

        /* Overlay */
        body::after {
            content: '';
            position: fixed;
            inset: 0;
            background: rgba(255, 255, 255, 0.85);
            z-index: -1;
            transition: background 0.2s ease;
        }
        body.dark-mode::after {
            background: rgba(15, 23, 42, 0.85);
        }

        /* ===== HEADER ===== */
        header {
            background: linear-gradient(135deg, #0f2b3d 0%, #1a4a6f 100%);
            padding: 1rem 5%;
            box-shadow: 0 4px 20px rgba(0,0,0,0.15);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .header-content {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .logo { 
            display: flex; 
            align-items: center; 
            gap: 0.75rem; 
            text-decoration: none;
        }
        
        .flag-icon {
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            overflow: hidden;
            box-shadow: 0 2px 6px rgba(0,0,0,0.25);
        }
        
        .flag-icon svg {
            width: 42px;
            height: 32px;
        }
        
        .logo h1 { 
            color: white; 
            font-size: 1.6rem; 
            font-weight: 800;
        }

        .logo-republic {
            display: flex;
            flex-direction: column;
            margin-left: 0.5rem;
        }
        .logo-republic .republic-name {
            font-size: 0.65rem;
            color: rgba(255,255,255,.5);
            font-weight: 500;
        }
        .logo-republic .republic-devise {
            font-size: 0.55rem;
            color: rgba(255,255,255,.35);
            font-weight: 400;
            margin-top: 2px;
        }

        /* ===== NAV ===== */
        nav { 
            display: flex; 
            align-items: center; 
            gap: 1.5rem; 
            flex-wrap: wrap; 
        }
        nav a { 
            color: rgba(255,255,255,0.9); 
            text-decoration: none; 
            font-weight: 600; 
            transition: all 0.3s;
            font-size: 0.95rem;
        }
        nav a:hover { color: #ffd700; transform: translateY(-2px); }

        .btn-connect {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 0.6rem 1.8rem;
            border-radius: 50px;
            font-weight: 700;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
            border: none;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn-connect:hover { 
            transform: translateY(-2px); 
            box-shadow: 0 6px 20px rgba(16, 185, 129, 0.4);
        }

        /* Boutons d'action (thème & langue) */
        .action-btn {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 50%;
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
            color: white;
            text-decoration: none;
            font-size: 1.1rem;
            padding: 0;
        }
        .action-btn:hover {
            background: rgba(255,255,255,0.2);
            border-color: rgba(255,255,255,0.4);
            transform: translateY(-2px);
        }
        .action-btn svg {
            width: 20px;
            height: 20px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
            stroke-linecap: round;
            stroke-linejoin: round;
        }

        /* Bouton langue unique */
        .lang-btn {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            border-radius: 50px;
            padding: 0.3rem 0.8rem;
            display: flex;
            align-items: center;
            gap: 0.3rem;
            cursor: pointer;
            transition: all 0.2s;
            color: white;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.8rem;
        }
        .lang-btn:hover {
            background: rgba(255,255,255,0.2);
            border-color: rgba(255,255,255,0.4);
            transform: translateY(-2px);
        }
        .lang-btn .flag-emoji {
            font-size: 1.2rem;
            line-height: 1;
        }
        .lang-btn .lang-label {
            font-size: 0.7rem;
            opacity: 0.9;
        }

        /* ===== HERO ===== */
        .hero {
            max-width: 1400px;
            margin: 3rem auto;
            padding: 0 5%;
        }

        .hero-container {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 3rem;
            background: white;
            border-radius: 24px;
            overflow: hidden;
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
            transition: background 0.2s;
        }
        body.dark-mode .hero-container {
            background: #1e293b;
        }

        .hero-content {
            padding: 3rem;
        }

        .badge-officiel {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(16, 185, 129, 0.1);
            border: 1px solid rgba(16, 185, 129, 0.2);
            color: var(--primary);
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.4rem 1rem;
            border-radius: 50px;
            margin-bottom: 1.5rem;
        }
        body.dark-mode .badge-officiel {
            background: rgba(16,185,129,0.2);
            border-color: rgba(16,185,129,0.3);
        }

        .badge-officiel .dot {
            width: 8px;
            height: 8px;
            background: var(--primary);
            border-radius: 50%;
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0%, 100% { opacity: 1; transform: scale(1); }
            50% { opacity: 0.5; transform: scale(1.2); }
        }

        .hero-content h1 {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--dark);
            line-height: 1.2;
            margin-bottom: 1rem;
            transition: color 0.2s;
        }
        body.dark-mode .hero-content h1 {
            color: #f1f5f9;
        }

        .hero-content h1 span {
            color: var(--primary);
        }

        .hero-content p {
            font-size: 1rem;
            color: var(--gray-600);
            line-height: 1.7;
            margin-bottom: 2rem;
        }
        body.dark-mode .hero-content p {
            color: #94a3b8;
        }

        .hero-buttons {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
            align-items: flex-start;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 0.9rem 2rem;
            border-radius: 12px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 4px 15px rgba(16, 185, 129, 0.3);
        }
        .btn-primary:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(16, 185, 129, 0.4);
        }

        .btn-outline {
            background: transparent;
            border: 2px solid var(--primary);
            color: var(--primary);
            padding: 0.9rem 2rem;
            border-radius: 12px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        .btn-outline:hover {
            background: var(--primary);
            color: white;
            transform: translateY(-3px);
        }

        .btn-found {
            background: linear-gradient(135deg, #f59e0b, #d97706);
            color: white;
            padding: 0.9rem 1.5rem;
            border-radius: 12px;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            box-shadow: 0 4px 15px rgba(245, 158, 11, 0.3);
        }
        .btn-found:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(245, 158, 11, 0.4);
        }

        .btn-hint {
            font-size: 0.7rem;
            color: #f59e0b;
            margin-top: 0.4rem;
            display: flex;
            align-items: center;
            gap: 0.2rem;
            white-space: nowrap;
        }
        @media (max-width: 768px) {
            .btn-hint { white-space: normal; text-align: center; }
        }

        .btn-wrapper {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        /* ===== ILLUSTRATION ===== */
        .hero-image {
            background: linear-gradient(135deg, #f0fdf4, #dcfce7);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
            position: relative;
        }
        body.dark-mode .hero-image {
            background: #1e293b;
        }

        .custom-illustration {
            position: relative;
            width: 100%;
            max-width: 480px;
            height: auto;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            background: #1a2a3a;
            overflow: hidden;
        }
        .desk {
            position: relative;
            background: linear-gradient(135deg, #2c3e50, #1a252f);
            padding: 1.5rem;
            border-radius: 16px;
        }
        .computer {
            background: #0f172a;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 8px 20px rgba(0,0,0,0.3);
            margin-bottom: 1rem;
        }
        .computer-header {
            background: #1e293b;
            padding: 0.6rem 1rem;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            border-bottom: 1px solid #334155;
        }
        .computer-dots {
            display: flex;
            gap: 0.3rem;
        }
        .computer-dots span {
            width: 10px;
            height: 10px;
            border-radius: 50%;
            background: #475569;
        }
        .computer-dots span:first-child { background: #ef4444; }
        .computer-dots span:nth-child(2) { background: #f59e0b; }
        .computer-dots span:last-child { background: #10b981; }
        .computer-title {
            font-size: 0.7rem;
            color: #94a3b8;
            margin-left: auto;
        }
        .computer-screen {
            padding: 1.2rem;
            background: #f8fafc;
        }
        body.dark-mode .computer-screen {
            background: #1e293b;
        }
        .form-preview {
            background: white;
            border-radius: 10px;
            padding: 1rem;
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
        }
        body.dark-mode .form-preview {
            background: #334155;
        }
        .form-preview h4 {
            font-size: 0.75rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0.8rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #10b981;
            display: inline-block;
        }
        body.dark-mode .form-preview h4 {
            color: #e5e7eb;
        }
        .preview-field {
            margin-bottom: 0.6rem;
        }
        .preview-field label {
            font-size: 0.6rem;
            font-weight: 600;
            color: #64748b;
            display: block;
            margin-bottom: 0.2rem;
        }
        body.dark-mode .preview-field label {
            color: #94a3b8;
        }
        .preview-field input, .preview-field select {
            width: 100%;
            padding: 0.4rem;
            border: 1px solid #e2e8f0;
            border-radius: 6px;
            font-size: 0.65rem;
            background: #f8fafc;
        }
        .preview-field input {
            background: white;
        }
        body.dark-mode .preview-field input, body.dark-mode .preview-field select {
            background: #1e293b;
            border-color: #4b5563;
            color: #e5e7eb;
        }
        .preview-submit {
            background: #10b981;
            color: white;
            padding: 0.4rem;
            border-radius: 6px;
            font-size: 0.65rem;
            font-weight: 600;
            text-align: center;
            margin-top: 0.6rem;
        }
        .person {
            position: absolute;
            bottom: 20px;
            right: -20px;
            width: 140px;
            height: auto;
            z-index: 2;
        }
        .person-body {
            background: #1a1a2e;
            width: 70px;
            height: 80px;
            border-radius: 20px 20px 10px 10px;
            position: relative;
            margin-left: auto;
            margin-right: 10px;
        }
        .person-head {
            width: 55px;
            height: 55px;
            background: #5c3d2e;
            border-radius: 50%;
            position: absolute;
            top: -45px;
            left: 18px;
        }
        .person-hair {
            width: 58px;
            height: 30px;
            background: #2c1810;
            border-radius: 30px 30px 15px 15px;
            position: absolute;
            top: -10px;
            left: -1.5px;
        }
        .person-face {
            position: relative;
            width: 100%;
            height: 100%;
        }
        .person-eyes {
            display: flex;
            gap: 10px;
            justify-content: center;
            padding-top: 18px;
        }
        .eye {
            width: 6px;
            height: 6px;
            background: white;
            border-radius: 50%;
        }
        .person-mouth {
            width: 12px;
            height: 4px;
            background: #c97a5e;
            border-radius: 0 0 5px 5px;
            margin: 8px auto 0;
        }
        .person-arm-left {
            width: 35px;
            height: 12px;
            background: #1a1a2e;
            position: absolute;
            top: 25px;
            left: -20px;
            border-radius: 10px;
            transform: rotate(-30deg);
        }
        .person-arm-right {
            width: 50px;
            height: 12px;
            background: #1a1a2e;
            position: absolute;
            top: 25px;
            right: -35px;
            border-radius: 10px;
            transform: rotate(20deg);
        }
        .keyboard {
            background: #334155;
            border-radius: 8px;
            padding: 0.5rem;
            margin-top: 0.5rem;
            display: flex;
            gap: 0.3rem;
            flex-wrap: wrap;
            justify-content: center;
        }
        .key {
            width: 20px;
            height: 20px;
            background: #475569;
            border-radius: 4px;
        }
        .key.space {
            width: 80px;
        }
        .floating-badge {
            position: absolute;
            bottom: 20px;
            left: 20px;
            background: white;
            border-radius: 20px;
            padding: 0.5rem 1rem;
            font-size: 0.7rem;
            font-weight: 600;
            color: var(--primary);
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            animation: float 3s ease-in-out infinite;
            z-index: 3;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-8px); }
        }

        /* ===== STATS ===== */
        .stats-section {
            max-width: 1400px;
            margin: 3rem auto;
            padding: 0 5%;
        }
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 1rem;
        }
        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 1.5rem;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            transition: all 0.3s;
            border: 1px solid var(--gray-200);
        }
        body.dark-mode .stat-card {
            background: #1e293b;
            border-color: #334155;
        }
        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.1);
        }
        .stat-number {
            font-size: 2rem;
            font-weight: 800;
            color: var(--dark);
        }
        body.dark-mode .stat-number {
            color: #f1f5f9;
        }
        .stat-label {
            font-size: 0.8rem;
            color: var(--gray-600);
            margin-top: 0.3rem;
        }
        body.dark-mode .stat-label {
            color: #94a3b8;
        }

        /* ===== DOCS STRIP ===== */
        .docs-strip {
            background: white;
            border-top: 1px solid var(--gray-200);
            border-bottom: 1px solid var(--gray-200);
            padding: 1rem 0;
        }
        body.dark-mode .docs-strip {
            background: #1e293b;
            border-color: #334155;
        }
        .docs-strip-inner {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 5%;
            display: flex;
            align-items: center;
            gap: 1rem;
            overflow-x: auto;
        }
        .docs-label {
            font-size: 0.7rem;
            font-weight: 700;
            color: var(--gray-600);
            text-transform: uppercase;
            letter-spacing: 1px;
            white-space: nowrap;
        }
        body.dark-mode .docs-label {
            color: #94a3b8;
        }
        .doc-pill {
            background: var(--gray-100);
            border: 1px solid var(--gray-200);
            border-radius: 50px;
            padding: 0.4rem 1rem;
            font-size: 0.8rem;
            font-weight: 500;
            color: var(--gray-800);
            text-decoration: none;
            white-space: nowrap;
            transition: all 0.2s;
        }
        body.dark-mode .doc-pill {
            background: #1e293b;
            border-color: #334155;
            color: #e5e7eb;
        }
        .doc-pill:hover {
            background: var(--primary);
            color: white;
            border-color: var(--primary);
            transform: translateY(-2px);
        }

        /* ===== WHY ===== */
        .why-section {
            max-width: 1400px;
            margin: 4rem auto;
            padding: 0 5%;
        }
        .why-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            margin-top: 3rem;
        }
        .why-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            transition: all 0.3s;
            border: 1px solid var(--gray-200);
        }
        body.dark-mode .why-card {
            background: #1e293b;
            border-color: #334155;
        }
        .why-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        .why-icon {
            width: 70px;
            height: 70px;
            background: rgba(16, 185, 129, 0.1);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 2rem;
        }
        .why-card h3 {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }
        body.dark-mode .why-card h3 {
            color: #f1f5f9;
        }
        .why-card p {
            font-size: 0.85rem;
            color: var(--gray-600);
            line-height: 1.6;
        }
        body.dark-mode .why-card p {
            color: #94a3b8;
        }

        /* ===== SERVICES ===== */
        .services-section {
            max-width: 1400px;
            margin: 4rem auto;
            padding: 0 5%;
        }
        .services-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
        }
        .service-card {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
            transition: all 0.3s;
            border: 1px solid var(--gray-200);
        }
        body.dark-mode .service-card {
            background: #1e293b;
            border-color: #334155;
        }
        .service-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 20px 40px rgba(0,0,0,0.1);
        }
        .service-icon {
            width: 70px;
            height: 70px;
            background: rgba(16, 185, 129, 0.1);
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 2rem;
        }
        .service-card h3 {
            font-size: 1.2rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }
        body.dark-mode .service-card h3 {
            color: #f1f5f9;
        }
        .service-card p {
            font-size: 0.85rem;
            color: var(--gray-600);
            line-height: 1.6;
            margin-bottom: 1rem;
        }
        body.dark-mode .service-card p {
            color: #94a3b8;
        }
        .service-link {
            color: var(--primary);
            text-decoration: none;
            font-weight: 600;
            font-size: 0.85rem;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            transition: gap 0.2s;
        }
        .service-link:hover {
            gap: 0.6rem;
        }

        /* ===== HOW IT WORKS ===== */
        .how-section {
            background: white;
            padding: 4rem 5%;
            margin: 4rem 0;
        }
        body.dark-mode .how-section {
            background: #1e293b;
        }
        .how-inner {
            max-width: 1400px;
            margin: 0 auto;
        }
        .steps-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1.5rem;
            margin-top: 3rem;
        }
        .step-card {
            text-align: center;
        }
        .step-number {
            width: 50px;
            height: 50px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            font-weight: 800;
            margin: 0 auto 1rem;
        }
        .step-card h3 {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }
        body.dark-mode .step-card h3 {
            color: #f1f5f9;
        }
        .step-card p {
            font-size: 0.85rem;
            color: var(--gray-600);
            line-height: 1.6;
        }
        body.dark-mode .step-card p {
            color: #94a3b8;
        }

        /* ===== FAQ ===== */
        .faq-section {
            max-width: 1400px;
            margin: 4rem auto;
            padding: 0 5%;
        }
        .faq-grid {
            max-width: 800px;
            margin: 2rem auto 0;
        }
        .faq-item {
            background: white;
            border-radius: 16px;
            margin-bottom: 1rem;
            border: 1px solid var(--gray-200);
            overflow: hidden;
            transition: all 0.3s;
        }
        body.dark-mode .faq-item {
            background: #1e293b;
            border-color: #334155;
        }
        .faq-question {
            padding: 1.2rem 1.5rem;
            font-weight: 700;
            color: var(--dark);
            cursor: pointer;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: background 0.2s;
        }
        body.dark-mode .faq-question {
            color: #f1f5f9;
        }
        .faq-question:hover {
            background: var(--gray-100);
        }
        body.dark-mode .faq-question:hover {
            background: #334155;
        }
        .faq-question span:first-child {
            font-size: 1rem;
        }
        .faq-icon {
            font-size: 1.2rem;
            transition: transform 0.3s;
            color: var(--primary);
        }
        .faq-item.active .faq-icon {
            transform: rotate(180deg);
        }
        .faq-answer {
            padding: 0 1.5rem;
            max-height: 0;
            overflow: hidden;
            transition: all 0.3s ease;
            color: var(--gray-600);
            line-height: 1.6;
            font-size: 0.9rem;
        }
        body.dark-mode .faq-answer {
            color: #94a3b8;
        }
        .faq-item.active .faq-answer {
            padding: 0 1.5rem 1.2rem;
            max-height: 200px;
        }

        /* ===== PARTNERS ===== */
        .partners-section {
            background: white;
            padding: 3rem 5%;
            margin: 2rem 0;
        }
        body.dark-mode .partners-section {
            background: #1e293b;
        }
        .partners-inner {
            max-width: 1400px;
            margin: 0 auto;
        }
        .partners-grid {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 3rem;
            flex-wrap: wrap;
            margin-top: 2rem;
        }
        .partner-item {
            text-align: center;
            opacity: 0.7;
            transition: all 0.3s;
        }
        .partner-item:hover {
            opacity: 1;
            transform: translateY(-3px);
        }
        .partner-icon {
            font-size: 3rem;
        }
        .partner-name {
            font-size: 0.75rem;
            color: var(--gray-600);
            margin-top: 0.3rem;
        }
        body.dark-mode .partner-name {
            color: #94a3b8;
        }

        /* ===== SECTION HEADER ===== */
        .section-header {
            text-align: center;
            margin-bottom: 3rem;
        }
        .section-header h2 {
            font-size: 2rem;
            font-weight: 800;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }
        body.dark-mode .section-header h2 {
            color: #f1f5f9;
        }
        .section-header p {
            font-size: 1rem;
            color: var(--gray-600);
        }
        body.dark-mode .section-header p {
            color: #94a3b8;
        }

        /* ===== CTA ===== */
        .cta-section {
            max-width: 1400px;
            margin: 4rem auto;
            padding: 0 5%;
        }
        .cta-box {
            background: linear-gradient(135deg, var(--dark), #1e293b);
            border-radius: 24px;
            padding: 3rem;
            text-align: center;
            color: white;
        }
        .cta-box h2 {
            font-size: 1.8rem;
            font-weight: 800;
            margin-bottom: 0.5rem;
        }
        .cta-box p {
            font-size: 1rem;
            color: rgba(255,255,255,0.7);
            margin-bottom: 2rem;
        }
        .cta-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        /* ===== WHATSAPP ===== */
        .whatsapp-float {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #25D366;
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            box-shadow: 0 4px 15px rgba(37, 211, 102, 0.4);
            transition: all 0.3s;
            z-index: 1000;
            animation: pulse-wa 2s infinite;
        }
        .whatsapp-float svg {
            width: 34px;
            height: 34px;
        }
        .whatsapp-float:hover {
            transform: scale(1.1);
            box-shadow: 0 8px 25px rgba(37, 211, 102, 0.5);
        }
        @keyframes pulse-wa {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        /* ===== FOOTER ===== */
        footer {
            background: var(--dark);
            color: white;
            padding: 3rem 5% 1.5rem;
            margin-top: 4rem;
        }
        .footer-inner {
            max-width: 1400px;
            margin: 0 auto;
        }
        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1.5fr;
            gap: 2rem;
            margin-bottom: 2rem;
        }
        .footer-col h4 {
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 1rem;
            color: rgba(255,255,255,0.5);
        }
        .footer-col ul {
            list-style: none;
        }
        .footer-col ul li {
            margin-bottom: 0.5rem;
        }
        .footer-col ul li a {
            color: rgba(255,255,255,0.4);
            text-decoration: none;
            font-size: 0.85rem;
            transition: color 0.2s;
        }
        .footer-col ul li a:hover {
            color: var(--primary);
        }
        .footer-bottom {
            text-align: center;
            padding-top: 1.5rem;
            border-top: 1px solid rgba(255,255,255,0.1);
            font-size: 0.8rem;
            color: rgba(255,255,255,0.3);
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 1024px) {
            .hero-container { grid-template-columns: 1fr; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
            .services-grid { grid-template-columns: repeat(2, 1fr); }
            .steps-grid { grid-template-columns: repeat(2, 1fr); }
            .footer-grid { grid-template-columns: repeat(2, 1fr); }
            .why-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 768px) {
            .header-content { flex-direction: column; text-align: center; }
            nav { justify-content: center; gap: 0.8rem; }
            .stats-grid { grid-template-columns: 1fr; }
            .services-grid { grid-template-columns: 1fr; }
            .steps-grid { grid-template-columns: 1fr; }
            .footer-grid { grid-template-columns: 1fr; }
            .hero-content { padding: 2rem; }
            .hero-content h1 { font-size: 1.8rem; }
            .hero-buttons { flex-direction: column; align-items: center; }
            .btn-wrapper { align-items: center; }
            .btn-hint { white-space: normal; text-align: center; }
            .cta-box { padding: 2rem; }
            .why-grid { grid-template-columns: 1fr; }
            .partners-grid { gap: 1.5rem; }
            .partner-item { flex: 0 0 calc(33.33% - 1rem); }
            .whatsapp-float { width: 50px; height: 50px; bottom: 20px; right: 20px; }
            .whatsapp-float svg { width: 28px; height: 28px; }
            .person { display: none; }
            .custom-illustration { max-width: 100%; }
            .action-btn { width: 32px; height: 32px; }
            .lang-btn { font-size: 0.7rem; padding: 0.2rem 0.6rem; }
        }
    </style>
</head>
<body>

<!-- ===== HEADER ===== -->
<header>
    <div class="header-content">
        <a href="{{ route('home') }}" style="display: flex; align-items: center; gap: 0.75rem; text-decoration: none;">
            <div class="logo">
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
                <h1>e-Déclaration</h1>
            </div>
            <div class="logo-republic">
                <span class="republic-name">République Togolaise</span>
                <span class="republic-devise">Travail-Liberté-Patrie</span>
            </div>
        </a>

        <!-- ✅ LE BLOC SUIVI A ÉTÉ SUPPRIMÉ -->

        <nav>
            <a href="#accueil">Accueil</a>
            <a href="#services">Services</a>
            <a href="#comment">Comment ça marche</a>
            <a href="{{ route('help.public') }}">Aide</a>

            <!-- ===== BOUTON CONNEXION ===== -->
            <a href="{{ route('login') }}" class="btn-connect">Se connecter</a>

            <!-- ===== BOUTON THÈME ===== -->
            <button class="action-btn" id="themeToggleBtn" title="Changer le thème">
                <svg id="themeIcon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </button>

            <!-- ===== BOUTON LANGUE ===== -->
            @php
                $currentLocale = app()->getLocale();
                $nextLocale = $currentLocale === 'fr' ? 'en' : 'fr';
                $flagEmoji = $currentLocale === 'fr' ? '🇫🇷' : '🇬🇧';
                $langLabel = strtoupper($currentLocale);
            @endphp
            <a href="{{ route('lang', ['locale' => $nextLocale]) }}" class="lang-btn" title="{{ $currentLocale === 'fr' ? 'Switch to English' : 'Passer en français' }}">
                <span class="flag-emoji">{{ $flagEmoji }}</span>
                <span class="lang-label">{{ $langLabel }}</span>
            </a>
        </nav>
    </div>
</header>

<!-- ===== HERO ===== -->
<section class="hero" id="accueil">
    <div class="hero-container">
        <div class="hero-content">
            <div class="badge-officiel">
                <span class="dot"></span>
                Service officiel de la République Togolaise
            </div>
            <h1>Plateforme Nationale de<br>Déclaration de <span>Documents Perdus</span></h1>
            <p>Déclarez la perte de vos documents administratifs en quelques minutes, suivez votre dossier en temps réel et téléchargez votre attestation numérique certifiée — sans vous déplacer.</p>
            <div class="hero-buttons">
                <div class="btn-wrapper">
                    <a href="{{ route('register') }}" class="btn-primary">
                        📄 Faire une déclaration
                    </a>
                </div>
                <div class="btn-wrapper">
                    <a href="{{ route('documents-trouves.create') }}" class="btn-found">
                        📦 Signaler
                    </a>
                    <div class="btn-hint">
                        📍 Vous avez trouvé un document ?
                    </div>
                </div>
                <div class="btn-wrapper">
                    <a href="#comment" class="btn-outline">
                        Comment ça marche →
                    </a>
                </div>
            </div>
        </div>
        <div class="hero-image">
            <div class="custom-illustration">
                <div class="desk">
                    <div class="computer">
                        <div class="computer-header">
                            <div class="computer-dots">
                                <span></span><span></span><span></span>
                            </div>
                            <span class="computer-title">e-Déclaration TG</span>
                        </div>
                        <div class="computer-screen">
                            <div class="form-preview">
                                <h4>📝 Nouvelle Déclaration de Perte</h4>
                                <div class="preview-field">
                                    <label>Type de pièce</label>
                                    <select>
                                        <option>Carte nationale d'identité</option>
                                        <option>Passeport</option>
                                        <option>Permis de conduire</option>
                                    </select>
                                </div>
                                <div class="preview-field">
                                    <label>Nom complet</label>
                                    <input type="text" placeholder="Kodjo Mensah" value="KATE emmanuel">
                                </div>
                                <div class="preview-field">
                                    <label>Numéro de pièce</label>
                                    <input type="text" placeholder="CNI-123456">
                                </div>
                                <div class="preview-field">
                                    <label>Date de perte</label>
                                    <input type="date">
                                </div>
                                <div class="preview-field">
                                    <label>Lieu de perte</label>
                                    <input type="text" placeholder="Lomé, Togo">
                                </div>
                                <div class="preview-submit">
                                    📤 Envoyer la déclaration
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="keyboard">
                        <div class="key"></div><div class="key"></div><div class="key"></div><div class="key"></div>
                        <div class="key"></div><div class="key"></div><div class="key"></div><div class="key"></div>
                        <div class="key space"></div>
                        <div class="key"></div><div class="key"></div><div class="key"></div>
                    </div>
                    <div class="person">
                        <div class="person-head">
                            <div class="person-hair"></div>
                            <div class="person-face">
                                <div class="person-eyes">
                                    <div class="eye"></div>
                                    <div class="eye"></div>
                                </div>
                                <div class="person-mouth"></div>
                            </div>
                        </div>
                        <div class="person-body"></div>
                        <div class="person-arm-left"></div>
                        <div class="person-arm-right"></div>
                    </div>
                </div>
                <div class="floating-badge">
                    ✨ +12 000 citoyens satisfaits
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ===== STATS ===== -->
<div class="stats-section">
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-number">12K+</div>
            <div class="stat-label">Déclarations traitées</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">98%</div>
            <div class="stat-label">Taux de satisfaction</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">&lt;48h</div>
            <div class="stat-label">Délai de traitement</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">24/7</div>
            <div class="stat-label">Disponibilité</div>
        </div>
        <div class="stat-card">
            <div class="stat-number">100%</div>
            <div class="stat-label">Gratuit</div>
        </div>
    </div>
</div>

<!-- ===== DOCUMENTS STRIP ===== -->
<div class="docs-strip">
    <div class="docs-strip-inner">
        <span class="docs-label">Documents acceptés :</span>
        <a href="{{ route('register') }}" class="doc-pill">🪪 Carte Nationale d'Identité</a>
        <a href="{{ route('register') }}" class="doc-pill">🛂 Passeport</a>
        <a href="{{ route('register') }}" class="doc-pill">🚗 Permis de conduire</a>
        <a href="{{ route('register') }}" class="doc-pill">📜 Acte de naissance</a>
        <a href="{{ route('register') }}" class="doc-pill">🎓 Diplôme & Attestation</a>
        <a href="{{ route('register') }}" class="doc-pill">📒 Carnet de santé</a>
        <a href="{{ route('register') }}" class="doc-pill">+ Autres</a>
    </div>
</div>

<!-- ===== WHY CHOOSE US ===== -->
<section class="why-section">
    <div class="section-header">
        <h2>Pourquoi <span style="color: var(--primary);">nous choisir ?</span></h2>
        <p>Une plateforme fiable, sécurisée et accessible à tous</p>
    </div>
    <div class="why-grid">
        <div class="why-card">
            <div class="why-icon">🔒</div>
            <h3>100% Sécurisé</h3>
            <p>Vos données sont chiffrées et protégées conformément aux normes internationales.</p>
        </div>
        <div class="why-card">
            <div class="why-icon">⚡</div>
            <h3>Rapide & Efficace</h3>
            <p>Déclaration en moins de 10 minutes. Traitement sous 48h maximum.</p>
        </div>
        <div class="why-card">
            <div class="why-icon">💰</div>
            <h3>Entièrement Gratuit</h3>
            <p>Service public 100% gratuit. Aucun frais caché.</p>
        </div>
    </div>
</section>

<!-- ===== SERVICES ===== -->
<section class="services-section" id="services">
    <div class="section-header">
        <h2>Nos services</h2>
        <p>Tout ce dont vous avez besoin en un seul endroit</p>
    </div>
    <div class="services-grid">
        <div class="service-card">
            <div class="service-icon">📄</div>
            <h3>Déclaration de Perte</h3>
            <p>Déclarez la perte de votre CNI, passeport, permis de conduire ou tout autre document administratif directement en ligne.</p>
            <a href="{{ route('register') }}" class="service-link">Commencer →</a>
        </div>
        <div class="service-card">
            <div class="service-icon">🔍</div>
            <h3>Suivi en Temps Réel</h3>
            <p>Consultez à tout moment l'état d'avancement de votre dossier. Recevez des notifications à chaque étape.</p>
            <a href="{{ route('login') }}" class="service-link">Suivre mon dossier →</a>
        </div>
        <div class="service-card">
            <div class="service-icon">🛡️</div>
            <h3>Attestation Numérique</h3>
            <p>Téléchargez votre attestation certifiée au format PDF, reconnue officiellement par toutes les administrations.</p>
            <a href="{{ route('login') }}" class="service-link">Voir mes attestations →</a>
        </div>
        <div class="service-card">
            <div class="service-icon">📦</div>
            <h3>J'ai Trouvé un Document</h3>
            <p>Vous avez trouvé un document ? Déclarez-le pour aider son propriétaire grâce au rapprochement automatique.</p>
            <a href="{{ route('documents-trouves.create') }}" class="service-link">Déclarer →</a>
        </div>
        <div class="service-card">
            <div class="service-icon">🔗</div>
            <h3>Rapprochement Automatique</h3>
            <p>Notre système compare automatiquement les déclarations de perte avec les documents trouvés.</p>
            <a href="{{ route('help.public') }}" class="service-link">En savoir plus →</a>
        </div>
        <div class="service-card">
            <div class="service-icon">🤝</div>
            <h3>Assistance & Support</h3>
            <p>Notre équipe est disponible pour vous accompagner dans vos démarches. Consultez la FAQ ou contactez-nous.</p>
            <a href="{{ route('help.public') }}" class="service-link">Centre d'aide →</a>
        </div>
    </div>
</section>

<!-- ===== HOW IT WORKS ===== -->
<section class="how-section" id="comment">
    <div class="how-inner">
        <div class="section-header">
            <h2>Comment ça fonctionne ?</h2>
            <p>Un processus simple en 4 étapes</p>
        </div>
        <div class="steps-grid">
            <div class="step-card">
                <div class="step-number">1</div>
                <h3>Créez votre compte</h3>
                <p>Inscrivez-vous avec votre numéro de téléphone ou adresse e-mail. Gratuit et rapide.</p>
            </div>
            <div class="step-card">
                <div class="step-number">2</div>
                <h3>Remplissez le formulaire</h3>
                <p>Indiquez le type de document, vos informations et les circonstances de la perte.</p>
            </div>
            <div class="step-card">
                <div class="step-number">3</div>
                <h3>Validation officielle</h3>
                <p>Un agent examine votre déclaration. Vous êtes notifié par SMS et e-mail.</p>
            </div>
            <div class="step-card">
                <div class="step-number">4</div>
                <h3>Téléchargez l'attestation</h3>
                <p>Votre attestation PDF certifiée est disponible immédiatement après validation.</p>
            </div>
        </div>
    </div>
</section>

<!-- ===== FAQ ===== -->
<section class="faq-section">
    <div class="section-header">
        <h2>Foire aux <span style="color: var(--primary);">questions</span></h2>
        <p>Les réponses aux questions les plus fréquentes</p>
    </div>
    <div class="faq-grid">
        <div class="faq-item">
            <div class="faq-question">
                <span>📌 Comment déclarer la perte de mon document ?</span>
                <span class="faq-icon">▼</span>
            </div>
            <div class="faq-answer">
                Il vous suffit de créer un compte, de remplir le formulaire en ligne avec les informations demandées (type de document, circonstances de la perte) et de soumettre votre déclaration. Un agent la traitera sous 48h.
            </div>
        </div>
        <div class="faq-item">
            <div class="faq-question">
                <span>📌 Combien de temps faut-il pour obtenir l'attestation ?</span>
                <span class="faq-icon">▼</span>
            </div>
            <div class="faq-answer">
                Le délai moyen de traitement est de 24 à 48 heures. Vous recevrez une notification dès que votre déclaration sera validée. L'attestation sera alors disponible en téléchargement.
            </div>
        </div>
        <div class="faq-item">
            <div class="faq-question">
                <span>📌 Est-ce que le service est vraiment gratuit ?</span>
                <span class="faq-icon">▼</span>
            </div>
            <div class="faq-answer">
                Oui, le service est 100% gratuit. C'est un service public mis à disposition des citoyens togolais. Aucun frais n'est requis pour la déclaration ou l'obtention de l'attestation.
            </div>
        </div>
    </div>
</section>

<!-- ===== PARTNERS ===== -->
<div class="partners-section">
    <div class="partners-inner">
        <div class="section-header">
            <h2>Nos <span style="color: var(--primary);">partenaires</span></h2>
            <p>Ils nous font confiance</p>
        </div>
        <div class="partners-grid">
            <div class="partner-item">
                <div class="partner-icon">🏛️</div>
                <div class="partner-name">Ministère de l'Intérieur</div>
            </div>
            <div class="partner-item">
                <div class="partner-icon">🆔</div>
                <div class="partner-name">ANPI Togo</div>
            </div>
            <div class="partner-item">
                <div class="partner-icon">🏢</div>
                <div class="partner-name">Mairie de Lomé</div>
            </div>
            <div class="partner-item">
                <div class="partner-icon">📞</div>
                <div class="partner-name">Togo Telecom</div>
            </div>
        </div>
    </div>
</div>

<!-- ===== CTA ===== -->
<section class="cta-section">
    <div class="cta-box">
        <h2>Prêt à effectuer votre déclaration ?</h2>
        <p>Rejoignez les 12 000 citoyens togolais qui ont déjà simplifié leurs démarches</p>
        <div class="cta-buttons">
            <a href="{{ route('register') }}" class="btn-primary">📄 Créer mon compte</a>
            <a href="{{ route('documents-trouves.create') }}" class="btn-outline" style="background: transparent; border-color: white; color: white;">📦 J'ai trouvé un document</a>
        </div>
    </div>
</section>

<!-- ===== FOOTER ===== -->
<footer>
    <div class="footer-inner">
        <div class="footer-grid">
            <div class="footer-col">
                <h4>e-Déclaration TG</h4>
                <p style="color: rgba(255,255,255,0.4); font-size: 0.85rem; line-height: 1.6;">Plateforme officielle du Togo pour les déclarations de perte de documents d'identité.</p>
            </div>
            <div class="footer-col">
                <h4>Liens rapides</h4>
                <ul>
                    <li><a href="#accueil">Accueil</a></li>
                    <li><a href="#services">Services</a></li>
                    <li><a href="#comment">Comment ça marche</a></li>
                    <li><a href="{{ route('help.public') }}">Aide</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Services</h4>
                <ul>
                    <li><a href="{{ route('register') }}">Déclaration de perte</a></li>
                    <li><a href="{{ route('documents-trouves.create') }}">Document trouvé</a></li>
                    <li><a href="{{ route('login') }}">Suivi de dossier</a></li>
                    <li><a href="{{ route('login') }}">Attestation numérique</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Contact</h4>
                <ul>
                    <li><a href="mailto:contact@edeclaration.tg">contact@edeclaration.tg</a></li>
                    <li><a href="tel:+22890000000">+228 90 00 00 00</a></li>
                    <li style="color: rgba(255,255,255,0.3); font-size: 0.75rem;">Lun - Ven: 8h - 17h</li>
                </ul>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2026 e-Déclaration TG - Ministère de l'Administration Territoriale du Togo</p>
        </div>
    </div>
</footer>

<!-- ===== WHATSAPP FLOATING BUTTON ===== -->
<a href="https://wa.me/22890000000?text=Bonjour%2C%20je%20souhaite%20avoir%20plus%20d%27informations%20sur%20la%20plateforme%20e-D%C3%A9claration%20TG" 
   class="whatsapp-float" 
   target="_blank"
   title="Discuter avec nous sur WhatsApp">
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
        <path fill="#25D366" d="M12 0C5.373 0 0 5.373 0 12c0 2.126.55 4.128 1.52 5.87L0 24l6.33-1.52A11.95 11.95 0 0 0 12 24c6.627 0 12-5.373 12-12S18.627 0 12 0z"/>
        <path fill="#FFF" d="M12 2c5.523 0 10 4.477 10 10s-4.477 10-10 10a9.96 9.96 0 0 1-5.33-1.56l-3.67.88.88-3.67A9.96 9.96 0 0 1 2 12c0-5.523 4.477-10 10-10z"/>
        <path fill="#25D366" d="M12 4.5C7.86 4.5 4.5 7.86 4.5 12c0 1.35.34 2.62.94 3.74l-.59 2.41 2.41-.59c1.12.6 2.39.94 3.74.94 4.14 0 7.5-3.36 7.5-7.5S16.14 4.5 12 4.5z"/>
        <path fill="#FFF" d="M16.5 14.8c-.2.4-.6.7-1.1.8-.4.1-.8.1-1.3-.2-.5-.3-1-.7-1.5-1.2-.4-.5-.8-1-1.1-1.5-.2-.3-.3-.6-.2-.9.1-.3.3-.5.6-.7.1-.1.2-.2.3-.3.1 0 .2 0 .2.1.1 0 .2.1.3.3.1.2.3.6.4.8.1.2.1.3 0 .4-.1.1-.2.2-.3.3-.1.1-.2.2-.2.3 0 .1 0 .2.1.3.2.3.5.6.8.9.3.3.6.5.9.6.2.1.4.1.5 0 .1-.1.2-.2.3-.3.1-.1.2-.1.3 0 .1.1.3.2.4.3.2.1.3.3.4.4.1.2.1.3 0 .5z"/>
        <path fill="#25D366" d="M11.5 10.5c.2-.2.3-.4.4-.6 0-.1 0-.2-.1-.3-.1-.2-.3-.6-.4-.8-.1-.2-.3-.3-.4-.3h-.3c-.2 0-.4.1-.6.2-.2.2-.4.4-.6.7-.2.3-.3.6-.2.9.1.3.2.6.3.9.4.7.8 1.3 1.3 1.8.5.5 1.1.9 1.8 1.2.3.1.6.2.9.2.3 0 .6-.1.9-.3.2-.1.4-.3.5-.5.1-.2.2-.4.1-.6-.1-.2-.2-.4-.4-.5-.1-.1-.3-.2-.4-.3-.2-.1-.3-.1-.5 0-.1.1-.2.2-.3.3-.1.1-.2.2-.3.2-.1 0-.2-.1-.3-.2-.2-.2-.5-.5-.7-.8-.2-.3-.3-.6-.2-.9.1-.3.2-.5.4-.7z"/>
    </svg>
</a>

<!-- ===== SCRIPTS ===== -->
<script>
    // FAQ Accordion
    document.querySelectorAll('.faq-question').forEach(question => {
        question.addEventListener('click', () => {
            const faqItem = question.parentElement;
            faqItem.classList.toggle('active');
        });
    });

    // ===== GESTION DU THÈME =====
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
        const saved = localStorage.getItem('darkMode');
        applyTheme(saved === 'dark');
    }

    function toggleTheme() {
        const isDark = !document.body.classList.contains('dark-mode');
        applyTheme(isDark);
        // Synchronisation avec le serveur (optionnel)
        fetch('{{ route("profile.toggle-dark-mode") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ dark_mode: isDark })
        }).catch(console.error);
    }

    document.addEventListener('DOMContentLoaded', function() {
        loadTheme();
        const btn = document.getElementById('themeToggleBtn');
        if (btn) btn.addEventListener('click', toggleTheme);
    });
</script>
</body>
</html>