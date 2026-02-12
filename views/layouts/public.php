<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $title ?? 'Hansen Educacional'; ?></title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/assets/css/darkmode.css">
    <script src="/assets/js/darkmode.js"></script>
    <style>
        :root {
            /* Cores Oficiais do Site Atual hanseneducacional.com.br */
            --primary-color: #007e66;      /* Teal/Verde Escuro - Cor Principal */
            --secondary-color: #ffb606;    /* Amarelo Dourado - Accent */
            --dark-teal: #04574A;          /* Teal Escuro - Secundária */
            --medium-teal: #256c5d;        /* Teal Médio */
            --text-dark: #181a1b;          /* Texto Escuro */
            --text-light: #ffffff;         /* Texto Claro */
            --bg-light: #e8e6e3;           /* Background Bege Claro */
            --bg-dark: #181a1b;            /* Background Escuro (Dark Mode) */
            --pale-mint: #d9ede9;          /* Pale Mint - Highlights */
        }
        body { font-family: 'Open Sans', sans-serif; color: var(--text-dark); background-color: var(--bg-light); }
        h1, h2, h3, h4, h5, .navbar-brand { font-family: 'Montserrat', sans-serif; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; }
        
        .navbar { padding: 15px 0; border-bottom: 4px solid var(--primary-color); background-color: white !important; }
        .navbar-brand { line-height: 1; }
        .nav-link { color: var(--primary-color) !important; text-transform: uppercase; font-size: 0.85rem; font-weight: 700; margin: 0 12px; transition: 0.3s; }
        .nav-link:hover { color: var(--dark-teal) !important; }
        
        /* Botões usam o Verde Oficial com hover em Teal Escuro */
        .btn-hansen { background-color: var(--primary-color); color: white; border-radius: 50px; padding: 12px 35px; border: none; font-weight: 700; text-transform: uppercase; transition: 0.3s; box-shadow: 0 4px 15px rgba(0, 126, 102, 0.3); }
        .btn-hansen:hover { background-color: var(--dark-teal); color: white; transform: translateY(-2px); box-shadow: 0 6px 20px rgba(4, 87, 74, 0.3); }
        
        .text-yellow { color: var(--secondary-color) !important; }
        .text-orange { color: var(--secondary-color) !important; } /* Mantido para compatibilidade */
        .text-green { color: var(--primary-color) !important; }
        .text-teal { color: var(--dark-teal) !important; }
        .bg-primary-hansen { background-color: var(--primary-color); }
        .bg-pale-mint { background-color: var(--pale-mint); }
        
        .footer { background-color: var(--primary-color); color: white; padding: 80px 0 20px; border-top: 8px solid var(--dark-teal); }
        .footer h5 { color: white; border-left: 4px solid var(--secondary-color); padding-left: 15px; margin-bottom: 25px; font-size: 1.1rem; }
        .footer a { color: rgba(255,255,255,0.8); text-decoration: none; transition: 0.3s; font-size: 0.9rem; }
        .footer a:hover { color: var(--secondary-color); }
        .footer-bottom { border-top: 1px solid rgba(255,255,255,0.1); padding-top: 20px; margin-top: 40px; font-size: 0.8rem; opacity: 0.6; }
        
        /* Ajustes de seções */
        section { padding: 80px 0; }
        .bg-light { background-color: var(--bg-light) !important; }
        .bg-white { background-color: #ffffff !important; }
    </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="/">
            <div class="fw-bold" style="line-height: 1;">
                <span style="color: var(--primary-color); font-size: 1.8rem; display: block;">HANSEN</span>
                <span style="color: var(--secondary-color); font-size: 0.8rem; letter-spacing: 3px;">EDUCACIONAL</span>
            </div>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link" href="/">Início</a></li>
                <li class="nav-item"><a class="nav-link" href="/programas">Programas</a></li>
                <li class="nav-item"><a class="nav-link" href="/palestras">Palestras</a></li>
                <li class="nav-item"><a class="nav-link" href="/cursos">Cursos</a></li>
                <li class="nav-item"><a class="nav-link" href="/livros">Livros</a></li>
                <li class="nav-item"><a class="nav-link" href="/contato">Contato</a></li>
                <li class="nav-item ms-lg-2"><button class="dark-mode-toggle" onclick="toggleDarkMode()"><i class="fas fa-moon"></i></button></li>
                <li class="nav-item ms-lg-2"><a class="btn btn-hansen py-2" href="/login">Área do Aluno</a></li>
            </ul>
        </div>
    </div>
</nav>

<main>
    <?php echo $content; ?>
</main>

<footer class="footer mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4 mb-md-0">
                <div class="fw-bold mb-4" style="line-height: 1;">
                    <span style="color: white; font-size: 1.8rem; display: block;">HANSEN</span>
                    <span style="color: var(--secondary-color); font-size: 0.8rem; letter-spacing: 3px;">EDUCACIONAL</span>
                </div>
                <p class="small opacity-75">Transformando a experiência educacional na Primeira Infância através da ciência e do humanismo.</p>
                <div class="mt-4">
                    <a href="https://instagram.com/hansen.educacional" target="_blank" rel="noopener noreferrer" class="me-3 fs-4 text-white" title="Instagram Hansen Educacional"><i class="fab fa-instagram"></i></a>
                    <a href="https://facebook.com/hanseneducacional" target="_blank" rel="noopener noreferrer" class="me-3 fs-4 text-white" title="Facebook Hansen Educacional"><i class="fab fa-facebook"></i></a>
                    <a href="https://youtube.com/@hanseneducacional" target="_blank" rel="noopener noreferrer" class="fs-4 text-white" title="YouTube Hansen Educacional"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            <div class="col-md-2 mb-4 mb-md-0">
                <h5>INSTITUCIONAL</h5>
                <ul class="list-unstyled">
                    <li><a href="/">Início</a></li>
                    <li><a href="/#roger">Roger Hansen</a></li>
                    <li><a href="/contato">Contato</a></li>
                    <li><a href="/login">Área do Aluno</a></li>
                </ul>
            </div>
            <div class="col-md-2 mb-4 mb-md-0">
                <h5>CONTEÚDO</h5>
                <ul class="list-unstyled">
                    <li><a href="/programas">Programas</a></li>
                    <li><a href="/palestras">Palestras</a></li>
                    <li><a href="/cursos">Cursos</a></li>
                    <li><a href="/livros">Livros</a></li>
                </ul>
            </div>
            <div class="col-md-2 mb-4 mb-md-0">
                <h5>LEGAL</h5>
                <ul class="list-unstyled">
                    <li><a href="/termos-de-uso">Termos de Uso</a></li>
                    <li><a href="/politica-privacidade">Política de Privacidade</a></li>
                </ul>
            </div>
            <div class="col-md-2">
                <h5>FALE CONOSCO</h5>
                <p class="small mb-2 text-white"><i class="fab fa-whatsapp me-2 text-orange"></i> (48) 99142-7836</p>
                <p class="small mb-0 text-white"><i class="fas fa-envelope me-2 text-orange"></i> contato@hanseneducacional.com.br</p>
            </div>
        </div>
        <div class="footer-bottom text-center">
            <p class="mb-0">&copy; 2026 Hansen Educacional. Todos os direitos reservados. Desenvolvido por MGSWEB (Clone para Migração).</p>
        </div>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>