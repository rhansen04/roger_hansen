<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha Conta | Hansen Educacional</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/darkmode.css">
    <script src="/assets/js/darkmode.js"></script>
    <style>
        :root {
            --primary-color: #007e66;
            --secondary-color: #ffb606;
            --dark-teal: #04574A;
            --bg-light: #e8e6e3;
        }
        body { font-family: 'Open Sans', sans-serif; background-color: var(--bg-light); }
        h1, h2, h3, h4, h5 { font-family: 'Montserrat', sans-serif; font-weight: 700; }
        .student-topbar {
            background-color: var(--primary-color);
            padding: 15px 0;
            border-bottom: 4px solid var(--dark-teal);
        }
        .student-topbar a { color: rgba(255,255,255,0.8); text-decoration: none; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; margin: 0 15px; transition: 0.3s; }
        .student-topbar a:hover, .student-topbar a.active { color: white; }
        .btn-hansen { background-color: var(--primary-color); color: white; border-radius: 5px; font-weight: 600; border: none; padding: 8px 20px; }
        .btn-hansen:hover { background-color: var(--dark-teal); color: white; }
    </style>
</head>
<body>

<div class="student-topbar">
    <div class="container d-flex justify-content-between align-items-center">
        <div>
            <span class="text-white fw-bold" style="font-size: 1.2rem;">HANSEN <span style="color: var(--secondary-color);">EDUCACIONAL</span></span>
        </div>
        <div>
            <a href="/minha-conta"><i class="fas fa-book me-1"></i> Meus Cursos</a>
            <a href="/minha-conta/perfil"><i class="fas fa-user me-1"></i> Meu Perfil</a>
            <a href="/" target="_blank"><i class="fas fa-globe me-1"></i> Site</a>
            <button class="dark-mode-toggle me-2" onclick="toggleDarkMode()"><i class="fas fa-moon"></i></button>
            <a href="/logout" class="text-danger"><i class="fas fa-sign-out-alt me-1"></i> Sair</a>
        </div>
    </div>
</div>

<div class="container py-4">
    <div class="mb-3">
        <span class="text-muted">Olá, <strong><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Aluno'); ?></strong></span>
    </div>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?php echo $_SESSION['success_message']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>

    <?php echo $content; ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
