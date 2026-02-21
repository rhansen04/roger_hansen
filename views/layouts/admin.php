<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel Administrativo | Hansen Educacional</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="/assets/css/darkmode.css">
    <script src="/assets/js/darkmode.js"></script>
    <style>
        :root {
            --sidebar-width: 250px;
            /* Cores Oficiais do Site Atual hanseneducacional.com.br */
            --primary-color: #007e66;      /* Teal/Verde Escuro - Cor Principal */
            --secondary-color: #ffb606;    /* Amarelo Dourado - Accent */
            --dark-teal: #04574A;          /* Teal Escuro - Secundária */
            --text-dark: #181a1b;
            --bg-light: #e8e6e3;
        }
        body { background-color: var(--bg-light); font-family: 'Open Sans', sans-serif; }
        #sidebar {
            width: var(--sidebar-width);
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            background-color: var(--primary-color);
            color: white;
            padding-top: 20px;
            border-right: 4px solid var(--dark-teal);
        }
        #content {
            margin-left: var(--sidebar-width);
            padding: 20px;
        }
        .nav-link-admin {
            color: rgba(255,255,255,0.8);
            padding: 10px 20px;
            text-decoration: none;
            display: block;
            transition: 0.3s;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.82rem;
            border-left: 3px solid transparent;
        }
        .nav-link-admin:hover {
            color: white;
            background-color: rgba(255,255,255,0.1);
            border-left-color: var(--secondary-color);
        }
        .nav-link-admin.active {
            color: white;
            background-color: rgba(255,255,255,0.15);
            border-left-color: var(--secondary-color);
        }
        .sidebar-section {
            padding: 8px 20px 4px;
            font-size: 0.65rem;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            color: rgba(255,255,255,0.4);
            font-weight: 700;
            margin-top: 8px;
        }
        .sidebar-divider {
            border-top: 1px solid rgba(255,255,255,0.12);
            margin: 6px 16px;
        }
        .sidebar-bottom {
            position: absolute;
            bottom: 0;
            width: 100%;
            background: rgba(0,0,0,0.15);
            padding: 12px 0;
        }
        .sidebar-bottom .nav-link-admin {
            padding: 8px 20px;
            color: rgba(255,150,150,0.9);
        }
        .sidebar-bottom .nav-link-admin:hover {
            color: #ff6b6b;
            background-color: rgba(255,0,0,0.1);
            border-left-color: #ff6b6b;
        }
        #sidebar { overflow-y: auto; padding-bottom: 70px; }
        .card-stat { border-radius: 10px; border: none; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .btn-hansen { background-color: var(--primary-color); color: white; border-radius: 5px; font-weight: 600; border: none; padding: 8px 20px; }
        .btn-hansen:hover { background-color: var(--dark-teal); color: white; }
    </style>
</head>
<body>

<div id="sidebar">
    <div class="px-3 mb-4 text-center">
        <h5 class="fw-bold text-white">HANSEN <span style="color: var(--secondary-color)">ADMIN</span></h5>
        <hr class="bg-white opacity-25">
    </div>
    <?php
        $uri = strtok($_SERVER['REQUEST_URI'] ?? '', '?');
        $isActive = function($path) use ($uri) { return str_starts_with($uri, $path) ? 'active' : ''; };
    ?>
    <a href="/admin/dashboard" class="nav-link-admin <?= $isActive('/admin/dashboard') ?>"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>

    <div class="sidebar-section">Cadastros</div>
    <a href="/admin/students" class="nav-link-admin <?= $isActive('/admin/students') ?>"><i class="fas fa-user-graduate me-2"></i> Alunos</a>
    <a href="/admin/schools" class="nav-link-admin <?= $isActive('/admin/schools') ?>"><i class="fas fa-school me-2"></i> Escolas</a>
    <a href="/admin/users" class="nav-link-admin <?= $isActive('/admin/users') ?>"><i class="fas fa-users me-2"></i> Usuários</a>
    <a href="/admin/parents" class="nav-link-admin <?= $isActive('/admin/parents') ?>"><i class="fas fa-user-friends me-2"></i> Responsáveis</a>

    <div class="sidebar-section">Ensino</div>
    <a href="/admin/courses" class="nav-link-admin <?= $isActive('/admin/courses') ?>"><i class="fas fa-book me-2"></i> Cursos</a>
    <a href="/admin/enrollments" class="nav-link-admin <?= $isActive('/admin/enrollments') ?>"><i class="fas fa-user-check me-2"></i> Matrículas</a>
    <a href="/admin/observations" class="nav-link-admin <?= $isActive('/admin/observations') ?>"><i class="fas fa-clipboard-list me-2"></i> Observações</a>

    <div class="sidebar-section">Comunicação</div>
    <a href="/admin/contacts" class="nav-link-admin <?= $isActive('/admin/contacts') ?>"><i class="fas fa-envelope me-2"></i> Contatos</a>
    <a href="/admin/messages" class="nav-link-admin <?= $isActive('/admin/messages') ?>"><i class="fas fa-comments me-2"></i> Perguntas</a>

    <div class="sidebar-section">Análises</div>
    <a href="/admin/video-dashboard" class="nav-link-admin <?= $isActive('/admin/video-dashboard') ?>"><i class="fas fa-play-circle me-2"></i> Vídeos / Tracking</a>
    <a href="/admin/reports" class="nav-link-admin <?= $isActive('/admin/reports') ?>"><i class="fas fa-chart-bar me-2"></i> Relatórios</a>
    <a href="/admin/reports/low-scores" class="nav-link-admin <?= $isActive('/admin/reports/low-scores') ?>" style="font-size:0.78rem; padding-left:40px;"><i class="fas fa-exclamation-triangle me-2" style="color:rgba(255,180,180,0.8)"></i> Notas Baixas</a>

    <div class="sidebar-bottom">
        <a href="/logout" class="nav-link-admin"><i class="fas fa-sign-out-alt me-2"></i> Sair do Sistema</a>
    </div>
</div>

<div id="content">
    <nav class="navbar navbar-light bg-white mb-4 shadow-sm rounded p-3">
        <div class="container-fluid">
            <span class="navbar-text">
                <i class="fas fa-user-circle me-2 text-primary"></i> 
                Logado como: <strong><?php echo $_SESSION['user_name'] ?? 'Usuário'; ?></strong> 
                <span class="badge bg-secondary ms-2 text-uppercase"><?php echo $_SESSION['user_role'] ?? 'admin'; ?></span>
            </span>
            <div class="d-flex">
                <button class="dark-mode-toggle me-2" onclick="toggleDarkMode()"><i class="fas fa-moon"></i></button>
                <a href="/" class="btn btn-outline-primary btn-sm" target="_blank">Ver Site Público</a>
            </div>
        </div>
    </nav>
    
    <?php echo $content; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
