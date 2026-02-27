<link rel="stylesheet" href="/assets/css/help-center.css">

<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/help" class="text-decoration-none" style="color:var(--primary-color)"><i class="fas fa-life-ring me-1"></i>Central de Ajuda</a></li>
        <li class="breadcrumb-item active"><?= htmlspecialchars($category['title']) ?></li>
    </ol>
</nav>

<!-- Category Header -->
<div class="category-header mb-4" style="border-left: 4px solid <?= $category['color'] ?>">
    <div class="d-flex align-items-center gap-3">
        <div class="category-header-icon" style="background:<?= $category['color'] ?>15; color:<?= $category['color'] ?>">
            <i class="<?= $category['icon'] ?> fa-lg"></i>
        </div>
        <div>
            <h3 class="fw-bold mb-1"><?= htmlspecialchars($category['title']) ?></h3>
            <p class="text-muted mb-0"><?= htmlspecialchars($category['description']) ?></p>
        </div>
    </div>
</div>

<!-- Articles List -->
<div class="list-group">
    <?php foreach ($category['articles'] as $slug => $article): ?>
    <a href="/admin/help/<?= $category['slug'] ?>/<?= $slug ?>" class="list-group-item list-group-item-action article-list-item">
        <div class="d-flex justify-content-between align-items-start">
            <div class="flex-grow-1">
                <h6 class="mb-1 fw-bold"><?= htmlspecialchars($article['title']) ?></h6>
                <p class="mb-1 text-muted small"><?= htmlspecialchars($article['summary']) ?></p>
            </div>
            <div class="text-end ms-3 flex-shrink-0">
                <span class="badge bg-light text-muted"><i class="far fa-clock me-1"></i><?= $article['time'] ?> min</span>
                <i class="fas fa-chevron-right text-muted ms-2"></i>
            </div>
        </div>
    </a>
    <?php endforeach; ?>
</div>

<!-- Back button -->
<div class="mt-4">
    <a href="/admin/help" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Voltar à Central de Ajuda</a>
</div>
