<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo | Hansen Educacional</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/darkmode.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/driver.js@1.3.1/dist/driver.css">
    <link rel="stylesheet" href="/assets/css/help-center.css">
    <script src="/assets/js/darkmode.js"></script>
    <style>
        :root {
            --sidebar-width: 250px;
            --sidebar-collapsed: 0px;
            --primary-color: #007e66;
            --secondary-color: #ffb606;
            --dark-teal: #04574A;
            --text-dark: #181a1b;
            --bg-light: #f0eeeb;
        }

        *, *::before, *::after { box-sizing: border-box; }
        body { background-color: var(--bg-light); font-family: 'Open Sans', sans-serif; margin: 0; }

        /* ── Sidebar Shell: flex column, no absolute positioning ── */
        #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0; top: 0;
            display: flex;
            flex-direction: column;
            background: linear-gradient(175deg, #00896e 0%, var(--primary-color) 40%, var(--dark-teal) 100%);
            color: white;
            z-index: 1040;
            transition: transform .3s cubic-bezier(.4,0,.2,1);
            border-right: none;
            box-shadow: 2px 0 20px rgba(0,0,0,.12);
        }

        /* ── Header (fixed height, never scrolls) ── */
        .sidebar-header {
            padding: 20px 16px 12px;
            text-align: center;
            flex-shrink: 0;
            border-bottom: 1px solid rgba(255,255,255,.1);
        }
        .sidebar-header h5 {
            font-size: .95rem;
            letter-spacing: 2.5px;
            margin: 0;
            font-weight: 800;
        }

        /* ── Nav (scrollable middle) ── */
        .sidebar-nav {
            flex: 1 1 auto;
            overflow-y: auto;
            overflow-x: hidden;
            padding: 8px 0;
            scrollbar-width: thin;
            scrollbar-color: rgba(255,255,255,.15) transparent;
        }
        .sidebar-nav::-webkit-scrollbar { width: 4px; }
        .sidebar-nav::-webkit-scrollbar-track { background: transparent; }
        .sidebar-nav::-webkit-scrollbar-thumb { background: rgba(255,255,255,.18); border-radius: 4px; }

        /* ── Links ── */
        .nav-link-admin {
            color: rgba(255,255,255,.78);
            padding: 9px 18px;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 10px;
            transition: all .2s ease;
            font-weight: 600;
            text-transform: uppercase;
            font-size: .78rem;
            letter-spacing: .3px;
            border-left: 3px solid transparent;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .nav-link-admin i { width: 18px; text-align: center; font-size: .85rem; flex-shrink: 0; }
        .nav-link-admin:hover {
            color: #fff;
            background: rgba(255,255,255,.1);
            border-left-color: var(--secondary-color);
        }
        .nav-link-admin.active {
            color: #fff;
            background: rgba(255,255,255,.14);
            border-left-color: var(--secondary-color);
            box-shadow: inset 0 0 20px rgba(255,182,6,.06);
        }
        .nav-link-admin.sub-item {
            padding-left: 46px;
            font-size: .74rem;
        }

        /* ── Section headers ── */
        .sidebar-section {
            padding: 14px 18px 5px;
            font-size: .62rem;
            text-transform: uppercase;
            letter-spacing: 1.8px;
            color: rgba(255,255,255,.38);
            font-weight: 700;
        }

        /* ── Footer (fixed height, never scrolls) ── */
        .sidebar-footer {
            flex-shrink: 0;
            border-top: 1px solid rgba(255,255,255,.1);
            background: rgba(0,0,0,.12);
        }
        .sidebar-footer .nav-link-admin {
            padding: 12px 18px;
            color: rgba(255,160,160,.88);
            font-size: .78rem;
        }
        .sidebar-footer .nav-link-admin:hover {
            color: #ff6b6b;
            background: rgba(255,0,0,.08);
            border-left-color: #ff6b6b;
        }

        /* ── Content area ── */
        #content {
            margin-left: var(--sidebar-width);
            padding: 20px;
            min-height: 100vh;
            transition: margin-left .3s cubic-bezier(.4,0,.2,1);
        }

        /* ── Mobile hamburger ── */
        .sidebar-toggle {
            display: none;
            position: fixed;
            top: 12px; left: 12px;
            z-index: 1050;
            background: var(--primary-color);
            color: #fff;
            border: none;
            border-radius: 8px;
            width: 40px; height: 40px;
            font-size: 1.1rem;
            cursor: pointer;
            box-shadow: 0 2px 10px rgba(0,0,0,.2);
            transition: transform .2s;
        }
        .sidebar-toggle:hover { transform: scale(1.08); }

        /* ── Overlay for mobile ── */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,.45);
            z-index: 1035;
            backdrop-filter: blur(2px);
        }

        /* ── Responsive: tablets ── */
        @media (max-width: 991.98px) {
            .sidebar-toggle { display: flex; align-items: center; justify-content: center; }
            #sidebar { transform: translateX(-100%); }
            #sidebar.open { transform: translateX(0); }
            .sidebar-overlay.open { display: block; }
            #content { margin-left: 0; padding-top: 60px; }
        }

        /* ── Responsive: small phones ── */
        @media (max-width: 575.98px) {
            #sidebar { width: 260px; }
            #content { padding: 60px 12px 12px; }
        }

        .card-stat { border-radius: 10px; border: none; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .btn-hansen { background-color: var(--primary-color); color: white; border-radius: 5px; font-weight: 600; border: none; padding: 8px 20px; }
        .btn-hansen:hover { background-color: var(--dark-teal); color: white; }
    </style>
</head>
<body>

<!-- Mobile toggle -->
<button class="sidebar-toggle" id="sidebarToggle" aria-label="Abrir menu">
    <i class="fas fa-bars"></i>
</button>
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<div id="sidebar">
    <!-- Header -->
    <div class="sidebar-header">
        <h5 class="text-white">HANSEN <span style="color: var(--secondary-color)">ADMIN</span></h5>
    </div>

    <!-- Scrollable nav -->
    <nav class="sidebar-nav">
        <?php
            $uri = strtok($_SERVER['REQUEST_URI'] ?? '', '?');
            $isActive = function($path) use ($uri) { return str_starts_with($uri, $path) ? 'active' : ''; };
        ?>
        <a href="/admin/dashboard" class="nav-link-admin <?= $isActive('/admin/dashboard') ?>"><i class="fas fa-tachometer-alt"></i> Dashboard</a>

        <div class="sidebar-section">Cadastros</div>
        <a href="/admin/students" class="nav-link-admin <?= $isActive('/admin/students') ?>"><i class="fas fa-user-graduate"></i> Alunos</a>
        <a href="/admin/schools" class="nav-link-admin <?= $isActive('/admin/schools') ?>"><i class="fas fa-school"></i> Escolas</a>
        <a href="/admin/users" class="nav-link-admin <?= $isActive('/admin/users') ?>"><i class="fas fa-users"></i> Usuários</a>
        <a href="/admin/parents" class="nav-link-admin <?= $isActive('/admin/parents') ?>"><i class="fas fa-user-friends"></i> Responsáveis</a>

        <div class="sidebar-section">Ensino</div>
        <?php $coursesActive = $isActive('/admin/courses') || $isActive('/admin/sections') || $isActive('/admin/lessons'); ?>
        <a href="/admin/courses" class="nav-link-admin <?= $coursesActive ? 'active' : '' ?>"><i class="fas fa-book"></i> Cursos</a>
        <a href="/admin/enrollments" class="nav-link-admin <?= $isActive('/admin/enrollments') ?>"><i class="fas fa-user-check"></i> Matrículas</a>
        <a href="/admin/observations" class="nav-link-admin <?= $isActive('/admin/observations') ?>"><i class="fas fa-clipboard-list"></i> Observações</a>
        <a href="/admin/classrooms" class="nav-link-admin <?= $isActive('/admin/classrooms') ?>"><i class="fas fa-chalkboard"></i> Turmas</a>
        <a href="/admin/planning-templates" class="nav-link-admin <?= $isActive('/admin/planning-templates') ?>"><i class="fas fa-file-alt"></i> Templates Planej.</a>
        <?php $planningActive = str_starts_with($uri, '/admin/planning') && !str_starts_with($uri, '/admin/planning-templates'); ?>
        <a href="/admin/planning" class="nav-link-admin <?= $planningActive ? 'active' : '' ?>"><i class="fas fa-calendar-alt"></i> Planejamentos</a>

        <div class="sidebar-section">Comunicação</div>
        <a href="/admin/contacts" class="nav-link-admin <?= $isActive('/admin/contacts') ?>"><i class="fas fa-envelope"></i> Contatos</a>
        <a href="/admin/messages" class="nav-link-admin <?= $isActive('/admin/messages') ?>"><i class="fas fa-comments"></i> Perguntas</a>

        <div class="sidebar-section">Análises</div>
        <a href="/admin/video-dashboard" class="nav-link-admin <?= $isActive('/admin/video-dashboard') ?>"><i class="fas fa-play-circle"></i> Vídeos / Tracking</a>
        <a href="/admin/reports" class="nav-link-admin <?= $isActive('/admin/reports') ?>"><i class="fas fa-chart-bar"></i> Relatórios</a>
        <a href="/admin/reports/low-scores" class="nav-link-admin sub-item <?= $isActive('/admin/reports/low-scores') ?>"><i class="fas fa-exclamation-triangle" style="color:rgba(255,180,180,.8)"></i> Notas Baixas</a>

        <div class="sidebar-section">Ajuda</div>
        <a href="javascript:void(0)" onclick="HelpTours.start()" class="nav-link-admin"><i class="fas fa-route"></i> Tour desta Página</a>
        <a href="/admin/help" class="nav-link-admin <?= $isActive('/admin/help') ?>"><i class="fas fa-life-ring"></i> Central de Ajuda</a>
    </nav>

    <!-- Footer (always visible, never overlaps) -->
    <div class="sidebar-footer">
        <a href="/logout" class="nav-link-admin"><i class="fas fa-sign-out-alt"></i> Sair do Sistema</a>
    </div>
</div>

<div id="content">
    <nav class="navbar navbar-light bg-white mb-4 shadow-sm rounded p-3">
        <div class="container-fluid">
            <div class="d-flex align-items-center">
                <?php if (!empty($pageTitle)): ?>
                    <h5 class="mb-0 fw-bold text-primary me-3"><?= htmlspecialchars($pageTitle) ?></h5>
                    <div class="vr me-3"></div>
                <?php endif; ?>
                <span class="navbar-text mb-0">
                    <i class="fas fa-user-circle me-2 text-primary"></i>
                    Logado como: <strong><?php echo $_SESSION['user_name'] ?? 'Usuário'; ?></strong>
                    <span class="badge bg-secondary ms-2 text-uppercase"><?php echo $_SESSION['user_role'] ?? 'admin'; ?></span>
                </span>
            </div>
            <div class="d-flex">
                <button class="btn btn-help-premium me-2" onclick="HelpTours.start()" title="Tour desta página"><i class="fas fa-question-circle"></i><span class="d-none d-md-inline">Ajuda</span></button>
                <button class="dark-mode-toggle me-2" onclick="toggleDarkMode()"><i class="fas fa-moon"></i></button>
                <a href="/" class="btn btn-outline-primary btn-sm" target="_blank">Ver Site Público</a>
            </div>
        </div>
    </nav>

    <?php echo $content; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/driver.js@1.3.1/dist/driver.js.iife.js"></script>
<script src="/assets/js/help-tours.js"></script>
<script>
(function() {
    const toggle = document.getElementById('sidebarToggle');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebarOverlay');
    function openSidebar() { sidebar.classList.add('open'); overlay.classList.add('open'); }
    function closeSidebar() { sidebar.classList.remove('open'); overlay.classList.remove('open'); }
    toggle.addEventListener('click', function() { sidebar.classList.contains('open') ? closeSidebar() : openSidebar(); });
    overlay.addEventListener('click', closeSidebar);
})();
</script>
</body>
</html>
