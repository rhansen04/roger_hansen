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
            padding: 12px 20px;
            text-decoration: none;
            display: block;
            transition: 0.3s;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.85rem;
        }
        .nav-link-admin:hover, .nav-link-admin.active {
            color: white;
            background-color: rgba(255,255,255,0.15);
        }
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
    <a href="/admin/dashboard" class="nav-link-admin active"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a>
    <a href="/admin/students" class="nav-link-admin"><i class="fas fa-user-graduate me-2"></i> Alunos</a>
    <a href="/admin/schools" class="nav-link-admin"><i class="fas fa-school me-2"></i> Escolas</a>
    <a href="/admin/users" class="nav-link-admin"><i class="fas fa-users me-2"></i> Usuários</a>
    <a href="/admin/courses" class="nav-link-admin"><i class="fas fa-book me-2"></i> Cursos</a>
    <a href="/admin/observations" class="nav-link-admin"><i class="fas fa-clipboard-list me-2"></i> Observações</a>
    <a href="/admin/contacts" class="nav-link-admin"><i class="fas fa-envelope me-2"></i> Contatos</a>
    <a href="/admin/video-dashboard" class="nav-link-admin"><i class="fas fa-chart-line me-2"></i> Videos / Tracking</a>
    <a href="/admin/enrollments" class="nav-link-admin"><i class="fas fa-user-check me-2"></i> Matrículas</a>
    <a href="/admin/reports" class="nav-link-admin"><i class="fas fa-chart-bar me-2"></i> Relatórios</a>
    <div style="position: absolute; bottom: 20px; width: 100%;">
        <hr class="bg-white opacity-25">
        <a href="/logout" class="nav-link-admin text-danger"><i class="fas fa-sign-out-alt me-2"></i> Sair do Sistema</a>
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
