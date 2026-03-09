<style>
    /* Variables CSS pour les thèmes */
    :root {
        --bg-color: #f5f7fa;
        --text-color: #1e3a5f;
        --card-bg: #ffffff;
        --sidebar-bg: #ffffff;
        --border-color: #e8eef5;
        --hover-color: #f1f5f9;
        --input-bg: #f8fafc;
        --input-border: #e2e8f0;
    }

    body.dark-mode {
        --bg-color: #1a1a1a;
        --text-color: #ffffff;
        --card-bg: #2d2d2d;
        --sidebar-bg: #2d2d2d;
        --border-color: #404040;
        --hover-color: #404040;
        --input-bg: #404040;
        --input-border: #4b5563;
    }

    /* Application globale */
    body {
        background: var(--bg-color);
        color: var(--text-color);
        transition: all 0.3s ease;
    }

    /* Sidebar */
    .sidebar {
        background: var(--sidebar-bg);
        border-right: 1px solid var(--border-color);
    }

    .sidebar-nav a {
        color: var(--text-color);
        opacity: 0.8;
    }

    .sidebar-nav a:hover {
        background: var(--hover-color);
        color: #27ae60;
    }

    .sidebar-nav a.active {
        background: rgba(39, 174, 96, 0.1);
        color: #27ae60;
    }

    .sidebar-header {
        border-bottom-color: var(--border-color);
    }

    .sidebar-footer {
        border-top-color: var(--border-color);
    }

    /* Main content */
    .main {
        background: var(--bg-color);
    }

    .top-bar {
        background: var(--card-bg);
        border-bottom: 1px solid var(--border-color);
    }

    .top-bar h1 {
        color: var(--text-color);
    }

    /* Cards */
    .settings-card,
    .stat-mini,
    .dashboard-card,
    .table-card,
    .filter-card {
        background: var(--card-bg);
        border-color: var(--border-color);
    }

    /* Tableaux */
    .table {
        background: var(--card-bg);
        color: var(--text-color);
    }

    .table thead th {
        background: var(--hover-color);
        color: var(--text-color);
    }

    .table tbody td {
        border-bottom-color: var(--border-color);
    }

    .table tbody tr:hover {
        background: var(--hover-color);
    }

    /* Formulaires */
    .form-input,
    .form-select,
    .filter-select,
    .filter-input {
        background: var(--input-bg);
        border-color: var(--input-border);
        color: var(--text-color);
    }

    .form-input:focus,
    .form-select:focus {
        background: var(--card-bg);
        border-color: #27ae60;
    }

    .form-label {
        color: var(--text-color);
        opacity: 0.8;
    }

    /* Settings items */
    .setting-item {
        background: var(--hover-color);
    }

    .setting-item:hover {
        background: var(--card-bg);
        border-color: var(--border-color);
    }

    .setting-icon {
        background: var(--card-bg);
    }

    /* Stats */
    .stat-mini {
        background: var(--card-bg);
        border-color: var(--border-color);
    }

    .stat-mini-value {
        color: var(--text-color);
    }

    /* Modals */
    .modal-content {
        background: var(--card-bg);
        color: var(--text-color);
    }

    .modal-title {
        color: var(--text-color);
    }

    .modal-close {
        background: var(--hover-color);
        color: var(--text-color);
    }

    /* Alerts */
    .alert-success {
        background: rgba(39, 174, 96, 0.2);
        color: #27ae60;
    }

    .alert-error {
        background: rgba(231, 76, 60, 0.2);
        color: #e74c3c;
    }

    /* Boutons */
    .btn-secondary {
        background: var(--hover-color);
        color: var(--text-color);
    }

    .btn-secondary:hover {
        background: var(--border-color);
    }

    /* Pagination */
    .pagination a,
    .pagination span {
        background: var(--card-bg);
        color: var(--text-color);
        border-color: var(--border-color);
    }

    .pagination a:hover {
        background: var(--hover-color);
    }

    /* Filtres */
    .filter-card {
        background: var(--card-bg);
    }

    .filter-btn {
        background: var(--hover-color);
        color: var(--text-color);
    }

    .filter-btn.active {
        background: #27ae60;
        color: white;
    }

    /* Breadcrumb */
    .breadcrumb-custom {
        background: var(--hover-color);
    }

    .breadcrumb-custom a {
        color: var(--text-color);
    }

    /* Empty state */
    .empty-state {
        color: var(--text-color);
        opacity: 0.7;
    }

    /* Transitions */
    * {
        transition: background-color 0.3s ease, 
                    border-color 0.3s ease, 
                    color 0.3s ease,
                    box-shadow 0.3s ease;
    }
</style>