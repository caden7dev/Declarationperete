<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Gestion des Types de Pièces - Administration</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Bootstrap CSS pour les modales -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
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
        /* ===== COULEURS TOGO AVEC DOMINANTE VERTE POUR L'ADMIN ===== */
        :root {
            --primary: #1a7a3a;
            --primary-dark: #0f5c2a;
            --primary-light: #4caf50;
            --accent: #f39c12;
            --accent-dark: #e67e22;
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
            --red-togo: #d21034;
        }

        /* ===== STYLES GLOBAUX ===== */
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            background: #f5f7fa;
            transition: background 0.2s ease;
        }

        body.dark-mode {
            background: #0f172a;
        }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 280px;
            background: rgba(255,255,255,0.98);
            backdrop-filter: blur(10px);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 100;
            border-right: 1px solid rgba(26,122,58,0.15);
            box-shadow: 2px 0 20px rgba(0,0,0,0.05);
            transition: background 0.2s, border-color 0.2s;
            border-top: 4px solid var(--red-togo);
        }

        body.dark-mode .sidebar {
            background: rgba(20,20,30,0.98);
            border-right-color: rgba(26,122,58,0.3);
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

        .admin-badge {
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
            border: 1px solid var(--accent);
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
            position: relative;
        }

        body.dark-mode .sidebar-nav a {
            color: #9ca3af;
        }

        .sidebar-nav a i {
            width: 20px;
            font-size: 1.1rem;
        }

        .sidebar-nav a:hover {
            background: rgba(26,122,58,0.08);
            color: var(--primary);
        }

        body.dark-mode .sidebar-nav a:hover {
            background: rgba(26,122,58,0.2);
        }

        .sidebar-nav a.active {
            background: linear-gradient(135deg, rgba(26,122,58,0.12), rgba(76,175,80,0.08));
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

        .nav-badge.orange {
            background: var(--accent);
        }
        .nav-badge.green {
            background: var(--success);
        }
        .nav-badge.blue {
            background: var(--info);
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
            border-left: 6px solid var(--red-togo);
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
            background: rgba(26,122,58,0.08);
        }

        .icon-btn:hover svg {
            stroke: var(--primary);
        }

        /* ===== PROFIL DROPDOWN ===== */
        .profile-dropdown {
            position: relative;
            cursor: pointer;
        }

        .profile-trigger {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 0.5rem 1rem;
            background: #f8f9fa;
            border-radius: 50px;
            transition: all 0.3s;
            border: 1px solid #e9ecef;
        }

        body.dark-mode .profile-trigger {
            background: #334155;
            border-color: #4b5563;
        }

        .profile-trigger:hover {
            background: #f1f5f9;
            border-color: var(--primary);
        }

        body.dark-mode .profile-trigger:hover {
            background: #475569;
        }

        .profile-avatar {
            width: 45px;
            height: 45px;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 700;
            font-size: 1.2rem;
        }

        .profile-info {
            text-align: right;
        }

        .profile-name {
            font-weight: 700;
            color: #2c3e50;
            font-size: 0.95rem;
        }

        body.dark-mode .profile-name {
            color: #e5e7eb;
        }

        .profile-role {
            font-size: 0.75rem;
            color: #6c757d;
        }

        body.dark-mode .profile-role {
            color: #94a3b8;
        }

        .dropdown-icon {
            color: #6c757d;
            transition: transform 0.3s;
        }

        .profile-dropdown.active .dropdown-icon {
            transform: rotate(180deg);
        }

        .dropdown-menu-custom {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            width: 280px;
            background: white;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.15);
            opacity: 0;
            visibility: hidden;
            transform: translateY(-10px);
            transition: all 0.3s;
            z-index: 1000;
        }

        body.dark-mode .dropdown-menu-custom {
            background: #1e293b;
        }

        .profile-dropdown.active .dropdown-menu-custom {
            opacity: 1;
            visibility: visible;
            transform: translateY(0);
        }

        .dropdown-header {
            padding: 1.5rem;
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            border-radius: 16px 16px 0 0;
            color: white;
            text-align: center;
        }

        .dropdown-header .avatar-large {
            width: 60px;
            height: 60px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 0.75rem;
            font-size: 1.8rem;
            font-weight: 700;
        }

        .dropdown-header .user-email {
            font-size: 0.8rem;
            opacity: 0.9;
        }

        .dropdown-divider {
            height: 1px;
            background: #e9ecef;
            margin: 0.5rem 0;
        }

        body.dark-mode .dropdown-divider {
            background: #334155;
        }

        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1.5rem;
            color: #2c3e50;
            text-decoration: none;
            transition: all 0.2s;
            font-size: 0.9rem;
            background: none;
            border: none;
            width: 100%;
            cursor: pointer;
        }

        body.dark-mode .dropdown-item {
            color: #e5e7eb;
        }

        .dropdown-item:hover {
            background: #f8f9fa;
        }

        body.dark-mode .dropdown-item:hover {
            background: #334155;
        }

        .dropdown-item.text-danger:hover {
            background: #fee2e2;
        }

        body.dark-mode .dropdown-item.text-danger:hover {
            background: #3f1e1e;
        }

        /* ===== STATISTIQUES ===== */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: white;
            border-radius: 16px;
            padding: 1.2rem;
            border: 1px solid var(--gray-200);
            transition: all 0.2s;
            border-left: 4px solid var(--gray-200);
        }

        body.dark-mode .stat-card {
            background: #1e293b;
            border-color: #334155;
        }

        .stat-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        }

        .stat-card .stat-label {
            font-size: 0.75rem;
            color: var(--gray-600);
            text-transform: uppercase;
            font-weight: 600;
            letter-spacing: 0.5px;
            margin-bottom: 0.3rem;
        }

        body.dark-mode .stat-card .stat-label {
            color: #94a3b8;
        }

        .stat-card .stat-value {
            font-size: 2rem;
            font-weight: 800;
            color: var(--dark);
        }

        body.dark-mode .stat-card .stat-value {
            color: #f1f5f9;
        }

        .stat-card.total { border-left-color: var(--primary); }
        .stat-card.active { border-left-color: var(--success); }
        .stat-card.inactive { border-left-color: var(--warning); }
        .stat-card.categories { border-left-color: var(--info); }

        /* ===== FILTRES ===== */
        .filter-card {
            background: white;
            border: 1px solid var(--gray-200);
            border-radius: 16px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            transition: background 0.2s;
        }

        body.dark-mode .filter-card {
            background: #1e293b;
            border-color: #334155;
        }

        .filter-card .form-label {
            font-weight: 600;
            color: var(--dark);
        }

        body.dark-mode .filter-card .form-label {
            color: #e5e7eb;
        }

        .filter-card .form-control,
        .filter-card .form-select {
            border: 2px solid var(--gray-200);
            border-radius: 12px;
            transition: all 0.2s;
            background: var(--gray-100);
        }

        body.dark-mode .filter-card .form-control,
        body.dark-mode .filter-card .form-select {
            background: #334155;
            border-color: #4b5563;
            color: #e5e7eb;
        }

        .filter-card .form-control:focus,
        .filter-card .form-select:focus {
            border-color: var(--primary);
            background: white;
            box-shadow: 0 0 0 3px rgba(26,122,58,0.1);
        }

        body.dark-mode .filter-card .form-control:focus,
        body.dark-mode .filter-card .form-select:focus {
            background: #1e293b;
            border-color: var(--primary-light);
        }

        /* ===== CARTES TYPES ===== */
        .type-card {
            background: white;
            border: 1px solid var(--gray-200);
            border-radius: 16px;
            margin-bottom: 1.5rem;
            overflow: hidden;
            transition: all 0.2s;
        }

        body.dark-mode .type-card {
            background: #1e293b;
            border-color: #334155;
        }

        .type-card:hover {
            border-color: var(--primary);
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            transform: translateY(-2px);
        }

        .type-card-header {
            padding: 1rem 1.5rem;
            background: var(--gray-100);
            border-bottom: 1px solid var(--gray-200);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        body.dark-mode .type-card-header {
            background: #334155;
            border-bottom-color: #4b5563;
        }

        .type-id-badge {
            background: var(--primary);
            color: white;
            padding: 0.25rem 0.8rem;
            border-radius: 50px;
            font-weight: 700;
            font-size: 0.8rem;
        }

        .type-name-header {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--dark);
        }

        body.dark-mode .type-name-header {
            color: #e5e7eb;
        }

        .type-status-badge {
            padding: 0.25rem 0.8rem;
            border-radius: 50px;
            font-weight: 600;
            font-size: 0.75rem;
        }

        .type-status-badge.active {
            background: var(--success);
            color: white;
        }
        .type-status-badge.inactive {
            background: var(--warning);
            color: #2c3e50;
        }

        .type-card-body {
            padding: 1.5rem;
        }

        .type-details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 0.75rem 1.5rem;
        }

        .type-detail-item {
            display: flex;
            border-bottom: 1px solid var(--gray-200);
            padding-bottom: 0.5rem;
        }

        body.dark-mode .type-detail-item {
            border-bottom-color: #4b5563;
        }

        .type-detail-label {
            font-weight: 600;
            color: var(--dark);
            width: 140px;
            flex-shrink: 0;
        }

        body.dark-mode .type-detail-label {
            color: #e5e7eb;
        }

        .type-detail-value {
            color: var(--gray-600);
            flex: 1;
        }

        body.dark-mode .type-detail-value {
            color: #94a3b8;
        }

        .type-actions {
            display: flex;
            gap: 0.5rem;
        }

        .btn-sm {
            padding: 0.3rem 0.8rem;
            font-size: 0.8rem;
            border-radius: 8px;
            font-weight: 600;
            border: none;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
        }

        .btn-primary {
            background: var(--primary);
            color: white;
        }
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(26,122,58,0.3);
        }
        .btn-secondary {
            background: var(--gray-200);
            color: var(--gray-600);
        }
        .btn-secondary:hover {
            background: var(--gray-600);
            color: white;
        }
        .btn-success {
            background: var(--success);
            color: white;
        }
        .btn-success:hover {
            background: #219653;
            transform: translateY(-2px);
        }
        .btn-danger {
            background: var(--danger);
            color: white;
        }
        .btn-danger:hover {
            background: #c0392b;
            transform: translateY(-2px);
        }
        .btn-warning {
            background: var(--accent);
            color: white;
        }
        .btn-warning:hover {
            background: var(--accent-dark);
            transform: translateY(-2px);
        }

        .btn-create {
            background: linear-gradient(135deg, var(--primary), var(--primary-dark));
            color: white;
            padding: 0.7rem 1.5rem;
            border-radius: 50px;
            font-weight: 700;
            border: none;
            transition: all 0.2s;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
        }
        .btn-create:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(26,122,58,0.3);
            color: white;
        }

        /* ===== ALERTES ===== */
        .alert-success {
            background: #d1fae5;
            color: #065f46;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            border-left: 4px solid var(--success);
        }

        body.dark-mode .alert-success {
            background: #0a3b2a;
            color: #34d399;
        }

        .alert-danger {
            background: #fee2e2;
            color: #991b1b;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            border-left: 4px solid var(--danger);
        }

        body.dark-mode .alert-danger {
            background: #3f1e1e;
            color: #f87171;
        }

        .alert-info {
            background: #dbeafe;
            color: #1e40af;
            padding: 1rem 1.5rem;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            border-left: 4px solid var(--info);
        }

        body.dark-mode .alert-info {
            background: #1e3a5f;
            color: #60a5fa;
        }

        /* ===== MODALES ===== */
        .modal-content {
            border-radius: 16px;
            border: none;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
        }
        body.dark-mode .modal-content {
            background: #1e293b;
            color: #e5e7eb;
        }
        .modal-header {
            border-radius: 16px 16px 0 0;
        }
        body.dark-mode .modal-header {
            border-bottom-color: #334155;
        }
        .modal-footer {
            border-radius: 0 0 16px 16px;
        }
        body.dark-mode .modal-footer {
            border-top-color: #334155;
        }
        body.dark-mode .form-control,
        body.dark-mode .form-select {
            background: #334155;
            border-color: #4b5563;
            color: #e5e7eb;
        }
        body.dark-mode .form-control:focus,
        body.dark-mode .form-select:focus {
            background: #1e293b;
            border-color: var(--primary-light);
            box-shadow: 0 0 0 3px rgba(26,122,58,0.2);
        }
        body.dark-mode .form-label {
            color: #e5e7eb;
        }
        body.dark-mode .text-muted {
            color: #94a3b8 !important;
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width:1200px) {
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width:1024px) {
            .sidebar { width: 100%; position: relative; height: auto; }
            .main { margin-left: 0; }
            .stats-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width:640px) {
            .stats-grid { grid-template-columns: 1fr; }
            .top-bar { flex-direction: column; align-items: stretch; text-align: center; }
            .top-bar-right { justify-content: center; }
            .type-details-grid { grid-template-columns: 1fr; }
            .type-detail-item { flex-wrap: wrap; }
            .type-detail-label { width: 100%; }
            .type-card-header { flex-direction: column; align-items: stretch; }
        }
    </style>
</head>
<body>

@php
    $user = auth()->user();
    $statsPertes = \App\Models\Perte::count();
    $statsDocuments = \App\Models\DocumentTrouve::count();
    $totalUsers = \App\Models\User::count();
    $totalTypes = $typesPieces->count();
    $activeTypes = $typesPieces->where('is_active', true)->count();
    $inactiveTypes = $typesPieces->where('is_active', false)->count();
    $categoriesCount = $typesPieces->unique('categorie')->count();
@endphp

<!-- ===== SIDEBAR ===== -->
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
        <div class="admin-badge">
            <i class="bi bi-shield-lock"></i> ADMIN
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section">PRINCIPAL</div>
        <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i> Dashboard
        </a>
        <a href="{{ route('admin.users.index') }}" class="{{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i> Utilisateurs
            <span class="nav-badge">{{ $totalUsers }}</span>
        </a>
        <a href="{{ route('admin.types-pieces.index') }}" class="{{ request()->routeIs('admin.types-pieces.*') ? 'active' : '' }}">
            <i class="bi bi-upc-scan"></i> Types de pièces
            <span class="nav-badge">{{ $totalTypes }}</span>
        </a>
        <a href="{{ route('admin.roles.index') }}" class="{{ request()->routeIs('admin.roles.*') ? 'active' : '' }}">
            <i class="bi bi-shield-check"></i> Rôles & droits
        </a>

        <div class="nav-section">DÉCLARATIONS</div>
        <a href="{{ route('admin.dashboard') }}">
            <i class="bi bi-files"></i> Toutes les pertes
            <span class="nav-badge">{{ $statsPertes }}</span>
        </a>
        <a href="#">
            <i class="bi bi-search-heart"></i> Documents trouvés
            <span class="nav-badge orange">{{ $statsDocuments }}</span>
        </a>

        <div class="nav-section">ANALYTIQUES</div>
        <a href="#">
            <i class="bi bi-graph-up"></i> Statistiques
        </a>
        <a href="#">
            <i class="bi bi-file-text"></i> Rapports
        </a>

        <div class="nav-section">PARAMÈTRES</div>
        <a href="{{ route('admin.profile') }}">
            <i class="bi bi-person-gear"></i> Mon profil
        </a>
    </nav>

    <div class="sidebar-footer">
        <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Voulez-vous vraiment vous déconnecter ?')">
            @csrf
            <button type="submit" class="logout-link">
                <svg fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
                Déconnecter
            </button>
        </form>
    </div>
</div>

<!-- ===== MAIN CONTENT ===== -->
<div class="main">
    <!-- TOP BAR -->
    <div class="top-bar">
        <div class="top-bar-left">
            <h1><i class="bi bi-upc-scan me-2" style="color: var(--primary);"></i>Gestion des Types de Pièces</h1>
            <p>Créez et gérez les types de documents déclarables</p>
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

            <!-- PROFIL DROPDOWN -->
            <div class="profile-dropdown" id="profileDropdown">
                <div class="profile-trigger" onclick="toggleDropdown()">
                    <div class="profile-avatar">
                        {{ strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                    <div class="profile-info">
                        <div class="profile-name">{{ $user->name }}</div>
                        <div class="profile-role">Administrateur</div>
                    </div>
                    <svg class="dropdown-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </div>

                <div class="dropdown-menu-custom">
                    <div class="dropdown-header">
                        <div class="avatar-large">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div class="fw-bold">{{ $user->name }}</div>
                        <div class="user-email">{{ $user->email }}</div>
                    </div>

                    <div class="dropdown-divider"></div>

                    <a href="{{ route('admin.profile') }}" class="dropdown-item">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/>
                            <circle cx="12" cy="7" r="4"/>
                        </svg>
                        Mon profil
                    </a>

                    <div class="dropdown-divider"></div>

                    <form method="POST" action="{{ route('logout') }}" onsubmit="return confirm('Voulez-vous vraiment vous déconnecter ?')" style="margin:0;">
                        @csrf
                        <button type="submit" class="dropdown-item text-danger" style="width:100%; text-align:left; background:none; border:none;">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                                <polyline points="16 17 21 12 16 7"/>
                                <line x1="21" y1="12" x2="9" y2="12"/>
                            </svg>
                            Se déconnecter
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- ALERTES -->
    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert-danger">{{ session('error') }}</div>
    @endif

    <!-- STATISTIQUES -->
    <div class="stats-grid">
        <div class="stat-card total">
            <div class="stat-label">Total Types</div>
            <div class="stat-value">{{ $totalTypes }}</div>
        </div>
        <div class="stat-card active">
            <div class="stat-label">Types Actifs</div>
            <div class="stat-value">{{ $activeTypes }}</div>
        </div>
        <div class="stat-card inactive">
            <div class="stat-label">Types Inactifs</div>
            <div class="stat-value">{{ $inactiveTypes }}</div>
        </div>
        <div class="stat-card categories">
            <div class="stat-label">Catégories</div>
            <div class="stat-value">{{ $categoriesCount }}</div>
        </div>
    </div>

    <!-- FILTRES -->
    <div class="filter-card">
        <form method="GET" action="{{ route('admin.types-pieces.index') }}" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Recherche</label>
                <input type="text" name="search" class="form-control" placeholder="Nom ou code..." value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Statut</label>
                <select name="status" class="form-select">
                    <option value="">Tous</option>
                    <option value="active" {{ request('status')=='active'?'selected':'' }}>Actifs</option>
                    <option value="inactive" {{ request('status')=='inactive'?'selected':'' }}>Inactifs</option>
                </select>
            </div>
            <div class="col-md-5">
                <button type="submit" class="btn btn-primary me-2"><i class="bi bi-funnel"></i> Filtrer</button>
                <a href="{{ route('admin.types-pieces.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-counterclockwise"></i> Réinitialiser</a>
            </div>
        </form>
    </div>

    <!-- BOUTON AJOUTER -->
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.5rem;">
        <h5 class="fw-bold mb-0"><i class="bi bi-plus-circle me-2" style="color:var(--primary);"></i>Liste des types de pièces</h5>
        <button class="btn-create" data-bs-toggle="modal" data-bs-target="#addTypePieceModal">
            <i class="bi bi-plus-lg"></i> Ajouter un type
        </button>
    </div>

    <!-- LISTE DES TYPES -->
    @forelse($typesPieces as $type)
    <div class="type-card">
        <div class="type-card-header">
            <div class="d-flex align-items-center gap-3">
                <span class="type-id-badge">#{{ $type->id }}</span>
                <div>
                    <div class="type-name-header">{{ $type->nom }}</div>
                    <small class="text-muted">{{ $type->categorie ?? 'Sans catégorie' }}</small>
                </div>
            </div>
            <div class="d-flex align-items-center gap-3">
                <span class="type-status-badge {{ $type->is_active ? 'active' : 'inactive' }}">
                    @if($type->is_active) <i class="bi bi-check-circle"></i> Actif @else <i class="bi bi-pause-circle"></i> Inactif @endif
                </span>
                <div class="type-actions">
                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editTypePieceModal{{ $type->id }}">
                        <i class="bi bi-pencil"></i> Modifier
                    </button>
                    <button class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteTypePieceModal{{ $type->id }}">
                        <i class="bi bi-trash3"></i> Supprimer
                    </button>
                </div>
            </div>
        </div>
        <div class="type-card-body">
            <div class="type-details-grid">
                <div class="type-detail-item">
                    <span class="type-detail-label">Nom</span>
                    <span class="type-detail-value">{{ $type->nom }}</span>
                </div>
                <div class="type-detail-item">
                    <span class="type-detail-label">Code</span>
                    <span class="type-detail-value">{{ $type->code ?? 'N/A' }}</span>
                </div>
                <div class="type-detail-item">
                    <span class="type-detail-label">Catégorie</span>
                    <span class="type-detail-value">{{ $type->categorie ?? 'N/A' }}</span>
                </div>
                <div class="type-detail-item">
                    <span class="type-detail-label">Statut</span>
                    <span class="type-detail-value">
                        @if($type->is_active)
                            <span class="badge bg-success">Actif</span>
                        @else
                            <span class="badge bg-warning text-dark">Inactif</span>
                        @endif
                    </span>
                </div>
                <div class="type-detail-item">
                    <span class="type-detail-label">Délai (jours)</span>
                    <span class="type-detail-value">{{ $type->delai_traitement ?? 'N/A' }}</span>
                </div>
                <div class="type-detail-item">
                    <span class="type-detail-label">Prix (FCFA)</span>
                    <span class="type-detail-value">{{ $type->prix ? number_format($type->prix,0,',',' ') : 'Gratuit' }}</span>
                </div>
                <div class="type-detail-item">
                    <span class="type-detail-label">Documents requis</span>
                    <span class="type-detail-value">{{ $type->documents_requis ?? 'Aucun' }}</span>
                </div>
                <div class="type-detail-item">
                    <span class="type-detail-label">Créé le</span>
                    <span class="type-detail-value">{{ $type->created_at->format('d/m/Y à H:i') }}</span>
                </div>
                @if($type->description)
                <div class="type-detail-item" style="grid-column:1/-1;">
                    <span class="type-detail-label">Description</span>
                    <span class="type-detail-value">{{ $type->description }}</span>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- MODALE ÉDITION -->
    <div class="modal fade" id="editTypePieceModal{{ $type->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.types-pieces.update', $type->id) }}">
                    @csrf @method('PUT')
                    <div class="modal-header bg-warning text-white">
                        <h5 class="modal-title"><i class="bi bi-pencil me-2"></i>Modifier le type de pièce</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Nom du type *</label>
                                <input type="text" name="nom" class="form-control" value="{{ $type->nom }}" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Code</label>
                                <input type="text" name="code" class="form-control" value="{{ $type->code }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Catégorie</label>
                                <select name="categorie" class="form-select">
                                    <option value="">Sélectionner...</option>
                                    <option value="Identité" {{ $type->categorie=='Identité'?'selected':'' }}>Identité</option>
                                    <option value="Véhicule" {{ $type->categorie=='Véhicule'?'selected':'' }}>Véhicule</option>
                                    <option value="Académique" {{ $type->categorie=='Académique'?'selected':'' }}>Académique</option>
                                    <option value="Professionnel" {{ $type->categorie=='Professionnel'?'selected':'' }}>Professionnel</option>
                                    <option value="Autre" {{ $type->categorie=='Autre'?'selected':'' }}>Autre</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Statut</label>
                                <select name="is_active" class="form-select">
                                    <option value="1" {{ $type->is_active?'selected':'' }}>Actif</option>
                                    <option value="0" {{ !$type->is_active?'selected':'' }}>Inactif</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Délai de traitement (jours)</label>
                                <input type="number" name="delai_traitement" class="form-control" value="{{ $type->delai_traitement }}">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold">Prix (FCFA)</label>
                                <input type="number" name="prix" class="form-control" value="{{ $type->prix }}">
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label fw-semibold">Documents requis</label>
                                <textarea name="documents_requis" class="form-control" rows="2">{{ $type->documents_requis }}</textarea>
                                <small class="text-muted">Séparez les documents par des virgules</small>
                            </div>
                            <div class="col-12 mb-3">
                                <label class="form-label fw-semibold">Description</label>
                                <textarea name="description" class="form-control" rows="3">{{ $type->description }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Enregistrer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- MODALE SUPPRESSION -->
    <div class="modal fade" id="deleteTypePieceModal{{ $type->id }}" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form method="POST" action="{{ route('admin.types-pieces.destroy', $type->id) }}">
                    @csrf @method('DELETE')
                    <div class="modal-header bg-danger text-white">
                        <h5 class="modal-title"><i class="bi bi-exclamation-triangle me-2"></i>Confirmer la suppression</h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <p>Êtes-vous sûr de vouloir supprimer <strong>{{ $type->nom }}</strong> ?</p>
                        <p class="text-danger"><small><i class="bi bi-exclamation-circle"></i> Cette action est irréversible !</small></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-danger"><i class="bi bi-trash3"></i> Supprimer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="alert-info">
        <i class="bi bi-info-circle me-2"></i> Aucun type de pièce trouvé. Commencez par en ajouter un !
    </div>
    @endforelse
</div>

<!-- ===== MODALE AJOUTER (en dehors de la boucle) ===== -->
<div class="modal fade" id="addTypePieceModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <form method="POST" action="{{ route('admin.types-pieces.store') }}">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title"><i class="bi bi-plus-circle me-2"></i>Ajouter un type de pièce</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Nom du type *</label>
                            <input type="text" name="nom" class="form-control" placeholder="Ex: Carte d'identité nationale" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Code</label>
                            <input type="text" name="code" class="form-control" placeholder="Ex: CIN">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Catégorie</label>
                            <select name="categorie" class="form-select">
                                <option value="">Sélectionner...</option>
                                <option value="Identité">Identité</option>
                                <option value="Véhicule">Véhicule</option>
                                <option value="Académique">Académique</option>
                                <option value="Professionnel">Professionnel</option>
                                <option value="Autre">Autre</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Statut</label>
                            <select name="is_active" class="form-select">
                                <option value="1" selected>Actif</option>
                                <option value="0">Inactif</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Délai de traitement (jours)</label>
                            <input type="number" name="delai_traitement" class="form-control" placeholder="Ex: 7">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">Prix (FCFA)</label>
                            <input type="number" name="prix" class="form-control" placeholder="Ex: 5000">
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label fw-semibold">Documents requis</label>
                            <textarea name="documents_requis" class="form-control" rows="2" placeholder="Acte de naissance, Photo d'identité..."></textarea>
                            <small class="text-muted">Séparez les documents par des virgules</small>
                        </div>
                        <div class="col-12 mb-3">
                            <label class="form-label fw-semibold">Description</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Description du type de pièce..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <button type="submit" class="btn btn-success"><i class="bi bi-check-lg"></i> Créer</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- ===== SCRIPTS ===== -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Horloge
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

    function toggleTheme() {
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

    document.addEventListener('DOMContentLoaded', function() {
        loadTheme();
        const btn = document.getElementById('themeToggleBtn');
        if (btn) btn.addEventListener('click', toggleTheme);
    });

    // Dropdown profil
    function toggleDropdown() {
        document.getElementById('profileDropdown').classList.toggle('active');
    }
    document.addEventListener('click', function(e) {
        const d = document.getElementById('profileDropdown');
        if (d && !d.contains(e.target)) {
            d.classList.remove('active');
        }
    });
</script>
</body>
</html>