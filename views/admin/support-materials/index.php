<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Material de Apoio</li>
    </ol>
</nav>

<?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i><?= $_SESSION['success_message'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['success_message']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error_message'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i><?= $_SESSION['error_message'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['error_message']); ?>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-primary fw-bold mb-0"><i class="fas fa-folder-open me-2"></i>MATERIAL DE APOIO</h2>
</div>

<p class="text-muted mb-4">Navegue pelas pastas para acessar os materiais de apoio pedagogico.</p>

<?php if (empty($tree)): ?>
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="fas fa-folder-open fa-3x text-muted mb-3"></i>
            <p class="text-muted mb-0">Nenhuma pasta de material criada. Execute o script de seed para criar a estrutura inicial.</p>
        </div>
    </div>
<?php else: ?>
    <div class="card border-0 shadow-sm">
        <div class="card-body p-4">
            <?php
            function renderTree($items, $level = 0) {
                foreach ($items as $item) {
                    $hasChildren = !empty($item['children']);
                    $padding = $level * 24;
                    $icon = $hasChildren ? 'fa-folder' : 'fa-folder';
                    $iconColor = $level === 0 ? '#ffb606' : '#007e66';
                    ?>
                    <div class="d-flex align-items-center py-2 border-bottom" style="padding-left: <?= $padding ?>px;">
                        <i class="fas <?= $icon ?> me-3" style="color:<?= $iconColor ?>;font-size:1.2rem;"></i>
                        <a href="/admin/support-materials/folder/<?= $item['id'] ?>" class="text-decoration-none fw-semibold text-dark flex-grow-1">
                            <?= htmlspecialchars($item['name']) ?>
                        </a>
                        <i class="fas fa-chevron-right text-muted"></i>
                    </div>
                    <?php
                    if ($hasChildren) {
                        renderTree($item['children'], $level + 1);
                    }
                }
            }
            renderTree($tree);
            ?>
        </div>
    </div>
<?php endif; ?>

<style>
.card-body .d-flex.border-bottom:hover {
    background-color: rgba(0,126,102,0.05);
    border-radius: 4px;
}
.card-body .d-flex.border-bottom:last-child {
    border-bottom: none !important;
}
</style>
