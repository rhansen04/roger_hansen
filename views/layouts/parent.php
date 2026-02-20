<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Area dos Pais | Hansen Educacional</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/darkmode.css">
    <script src="/assets/js/darkmode.js"></script>
    <style>
        :root { --primary-color: #007e66; --secondary-color: #ffb606; --dark-teal: #04574A; --bg-light: #e8e6e3; }
        body { font-family: 'Open Sans', sans-serif; background-color: var(--bg-light); }
        h1,h2,h3,h4,h5 { font-family: 'Montserrat', sans-serif; font-weight: 700; }
        .parent-topbar { background-color: var(--dark-teal); padding: 15px 0; border-bottom: 4px solid var(--secondary-color); }
        .parent-topbar a { color: rgba(255,255,255,0.85); text-decoration: none; font-weight: 600; text-transform: uppercase; font-size: 0.85rem; margin: 0 15px; transition: 0.3s; }
        .parent-topbar a:hover { color: var(--secondary-color); }
        .btn-hansen { background-color: var(--primary-color); color: white; border-radius: 5px; font-weight: 600; border: none; padding: 8px 20px; }
        .btn-hansen:hover { background-color: var(--dark-teal); color: white; }
    </style>
</head>
<body>
<div class="parent-topbar">
    <div class="container d-flex justify-content-between align-items-center">
        <div>
            <span class="text-white fw-bold" style="font-size:1.2rem;">HANSEN <span style="color:var(--secondary-color);">EDUCACIONAL</span></span>
            <span class="badge ms-2" style="background:var(--secondary-color);color:#000;font-size:0.7rem;">AREA DOS PAIS</span>
        </div>
        <div>
            <a href="/minha-area"><i class="fas fa-home me-1"></i> Inicio</a>
            <a href="/minha-area/perfil"><i class="fas fa-user me-1"></i> Meu Perfil</a>
            <a href="/" target="_blank"><i class="fas fa-globe me-1"></i> Site</a>
            <button class="dark-mode-toggle me-2" onclick="toggleDarkMode()" style="background:none;border:1px solid rgba(255,255,255,0.3);color:white;border-radius:4px;padding:4px 8px;cursor:pointer;"><i class="fas fa-moon"></i></button>
            <a href="/logout" style="color:#ffb3b3;"><i class="fas fa-sign-out-alt me-1"></i> Sair</a>
        </div>
    </div>
</div>
<div class="container py-4">
    <div class="mb-3">
        <span class="text-muted">Ola, <strong><?= htmlspecialchars($_SESSION['user_name'] ?? 'Responsavel') ?></strong></span>
    </div>
    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <i class="fas fa-check-circle me-2"></i><?= $_SESSION['success_message'] ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['success_message']); ?>
    <?php endif; ?>
    <?= $content ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
