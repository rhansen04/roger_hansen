<link rel="stylesheet" href="/assets/css/help-center.css">

<!-- Hero Section -->
<div class="help-hero">
    <div class="help-hero-content">
        <h1><i class="fas fa-life-ring me-3"></i>Como podemos ajudar?</h1>
        <p class="lead mb-4">Encontre guias, tutoriais e respostas para tirar o máximo do sistema Hansen.</p>
        <div class="help-search-wrapper">
            <i class="fas fa-search help-search-icon"></i>
            <input type="text" id="helpSearch" class="help-search-input" placeholder="Buscar artigos, tutoriais, dúvidas..." autocomplete="off">
        </div>
    </div>
</div>

<!-- Quick Start Strip -->
<div class="container-fluid px-0 mt-4">
    <h5 class="fw-bold mb-3"><i class="fas fa-bolt text-warning me-2"></i>Início Rápido</h5>
    <div class="row g-3 mb-4">
        <?php
        $quickStart = $categories['primeiros-passos']['articles'];
        $qsIcons = ['visao-geral' => 'fa-th-large', 'primeiro-acesso' => 'fa-key', 'navegacao' => 'fa-compass'];
        foreach ($quickStart as $slug => $art): ?>
        <div class="col-md-4 help-searchable" data-search="<?= strtolower($art['title'] . ' ' . $art['summary']) ?>">
            <a href="/admin/help/primeiros-passos/<?= $slug ?>" class="quick-start-card">
                <div class="quick-start-icon"><i class="fas <?= $qsIcons[$slug] ?? 'fa-file-alt' ?>"></i></div>
                <div>
                    <strong><?= $art['title'] ?></strong>
                    <small class="d-block text-muted"><?= $art['time'] ?> min de leitura</small>
                </div>
                <i class="fas fa-chevron-right ms-auto text-muted"></i>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Category Cards Grid -->
<div class="container-fluid px-0">
    <h5 class="fw-bold mb-3"><i class="fas fa-folder-open me-2" style="color:var(--primary-color)"></i>Categorias</h5>
    <div class="row g-4 mb-5" id="categoryGrid">
        <?php foreach ($categories as $slug => $cat): ?>
        <div class="col-md-6 col-lg-4 help-searchable" data-search="<?= strtolower($cat['title'] . ' ' . $cat['description'] . ' ' . implode(' ', array_column($cat['articles'], 'title'))) ?>">
            <a href="/admin/help/<?= $slug ?>" class="category-card">
                <div class="category-card-icon" style="background:<?= $cat['color'] ?>15; color:<?= $cat['color'] ?>">
                    <i class="<?= $cat['icon'] ?>"></i>
                </div>
                <h6 class="category-card-title"><?= $cat['title'] ?></h6>
                <p class="category-card-desc"><?= $cat['description'] ?></p>
                <span class="category-card-count"><?= count($cat['articles']) ?> artigos</span>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- FAQ Section -->
<div class="container-fluid px-0 mb-5">
    <h5 class="fw-bold mb-3"><i class="fas fa-question-circle me-2" style="color:var(--primary-color)"></i>Perguntas Frequentes</h5>
    <div class="accordion" id="faqAccordion">
        <?php foreach ($faq as $i => $item): ?>
        <div class="accordion-item help-searchable" data-search="<?= strtolower($item['question'] . ' ' . strip_tags($item['answer'])) ?>">
            <h2 class="accordion-header">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#faq<?= $i ?>">
                    <?= $item['question'] ?>
                </button>
            </h2>
            <div id="faq<?= $i ?>" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                <div class="accordion-body"><?= $item['answer'] ?></div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- No results message -->
<div id="noResults" class="text-center py-5 d-none">
    <i class="fas fa-search fa-3x text-muted mb-3 d-block"></i>
    <h5 class="text-muted">Nenhum resultado encontrado</h5>
    <p class="text-muted">Tente usar palavras-chave diferentes ou navegue pelas categorias acima.</p>
</div>

<script>
// Live search filter
document.getElementById('helpSearch').addEventListener('input', function() {
    var query = this.value.toLowerCase().trim();
    var items = document.querySelectorAll('.help-searchable');
    var visible = 0;

    items.forEach(function(item) {
        var match = !query || item.getAttribute('data-search').indexOf(query) !== -1;
        item.style.display = match ? '' : 'none';
        if (match) visible++;
    });

    document.getElementById('noResults').classList.toggle('d-none', visible > 0 || !query);
});
</script>
