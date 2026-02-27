<link rel="stylesheet" href="/assets/css/help-center.css">

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/help" class="text-decoration-none" style="color:var(--primary-color)"><i class="fas fa-life-ring me-1"></i>Central de Ajuda</a></li>
        <li class="breadcrumb-item"><a href="/admin/help/<?= $catSlug ?>" class="text-decoration-none" style="color:var(--primary-color)"><?= htmlspecialchars($category['title']) ?></a></li>
        <li class="breadcrumb-item active"><?= htmlspecialchars($article['title']) ?></li>
    </ol>
</nav>

<!-- Article Header -->
<div class="article-header mb-4">
    <span class="badge mb-2" style="background:<?= $category['color'] ?>15; color:<?= $category['color'] ?>">
        <i class="<?= $category['icon'] ?> me-1"></i><?= $category['title'] ?>
    </span>
    <h2 class="fw-bold mb-2"><?= htmlspecialchars($article['title']) ?></h2>
    <div class="text-muted small">
        <i class="far fa-clock me-1"></i><?= $article['time'] ?> min de leitura
    </div>
</div>

<!-- Article Content (partial) -->
<div class="article-body">
    <?php
    $partialFile = __DIR__ . "/articles/{$catSlug}--{$artSlug}.php";
    if (file_exists($partialFile)) {
        include $partialFile;
    } else {
        // Generic content based on article data
        echo '<div class="article-content-placeholder">';
        echo '<p class="lead">' . htmlspecialchars($article['summary']) . '</p>';
        echo '<div class="help-tip"><i class="fas fa-lightbulb me-2"></i><strong>Dica:</strong> Use o tour interativo desta página clicando no botão <strong>?</strong> na barra superior para ver cada elemento em ação.</div>';

        // Generic step-by-step
        echo '<div class="article-steps">';
        echo '<div class="article-step"><div class="step-number">1</div><div class="step-content"><h6>Acesse a seção correspondente</h6><p>No menu lateral, navegue até a seção relacionada a este tópico.</p></div></div>';
        echo '<div class="article-step"><div class="step-number">2</div><div class="step-content"><h6>Explore as opções disponíveis</h6><p>Familiarize-se com os botões e funcionalidades da página.</p></div></div>';
        echo '<div class="article-step"><div class="step-number">3</div><div class="step-content"><h6>Execute a ação desejada</h6><p>Siga os formulários e confirmações na tela para completar a operação.</p></div></div>';
        echo '</div>';

        echo '<figure class="article-screenshot"><div class="screenshot-placeholder"><i class="fas fa-image fa-3x text-muted"></i><p class="text-muted mt-2">Screenshot será adicionado em breve</p></div><figcaption>Exemplo da interface — ' . htmlspecialchars($article['title']) . '</figcaption></figure>';
        echo '</div>';
    }
    ?>
</div>

<!-- Related Articles -->
<?php if (!empty($related)): ?>
<div class="related-articles mt-5">
    <h5 class="fw-bold mb-3"><i class="fas fa-link me-2" style="color:var(--primary-color)"></i>Artigos Relacionados</h5>
    <div class="row g-3">
        <?php foreach ($related as $slug => $rel): ?>
        <div class="col-md-6">
            <a href="/admin/help/<?= $catSlug ?>/<?= $slug ?>" class="related-article-card">
                <i class="<?= $category['icon'] ?> me-2" style="color:<?= $category['color'] ?>"></i>
                <div>
                    <strong><?= htmlspecialchars($rel['title']) ?></strong>
                    <small class="d-block text-muted"><?= $rel['time'] ?> min</small>
                </div>
                <i class="fas fa-arrow-right ms-auto text-muted"></i>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>

<!-- Navigation -->
<div class="mt-4 d-flex gap-2">
    <a href="/admin/help/<?= $catSlug ?>" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Voltar à categoria</a>
    <a href="/admin/help" class="btn btn-outline-secondary"><i class="fas fa-home me-2"></i>Central de Ajuda</a>
</div>
