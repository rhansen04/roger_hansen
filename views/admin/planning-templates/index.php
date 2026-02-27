<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item active">Templates de Planejamento</li>
    </ol>
</nav>

<?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-2"></i><?= $_SESSION['success_message'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['success_message']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error_message'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-triangle me-2"></i><?= $_SESSION['error_message'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['error_message']); ?>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-primary fw-bold mb-0">TEMPLATES DE PLANEJAMENTO</h2>
    <a href="/admin/planning-templates/create" class="btn btn-hansen text-white">
        <i class="fas fa-plus me-2"></i> Novo Template
    </a>
</div>

<div class="row">
    <?php if (empty($templates)): ?>
        <div class="col-12 text-center py-5 text-muted">
            <i class="fas fa-file-alt fa-3x mb-3"></i><br>
            Nenhum template cadastrado.
        </div>
    <?php else: ?>
        <?php
        $ageLabels = ['0-3' => 'PFI (0-3 anos)', '3-6' => 'PFII (3-6 anos)', 'all' => 'Todas as idades'];
        foreach ($templates as $t): ?>
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="card-title fw-bold mb-0"><?= htmlspecialchars($t['title']) ?></h5>
                        <?php if ($t['is_active']): ?>
                            <span class="badge bg-success">Ativo</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Inativo</span>
                        <?php endif; ?>
                    </div>
                    <p class="text-muted small mb-2"><?= htmlspecialchars($t['description'] ?? '') ?></p>
                    <span class="badge bg-info"><?= $ageLabels[$t['age_group']] ?? $t['age_group'] ?></span>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <div class="btn-group w-100">
                        <a href="/admin/planning-templates/<?= $t['id'] ?>/edit" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-edit me-1"></i> Editar
                        </a>
                        <button onclick="confirmDelete(<?= $t['id'] ?>, '<?= htmlspecialchars($t['title']) ?>')" class="btn btn-sm btn-outline-danger">
                            <i class="fas fa-trash me-1"></i> Excluir
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<form id="deleteForm" method="POST" style="display: none;"></form>

<script>
function confirmDelete(id, name) {
    if (confirm(`Excluir template "${name}"?\n\nTodos os planejamentos vinculados serão afetados.`)) {
        const form = document.getElementById('deleteForm');
        form.action = `/admin/planning-templates/${id}/delete`;
        form.submit();
    }
}
</script>
