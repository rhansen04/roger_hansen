<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item active">Planejamentos</li>
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
    <h2 class="text-primary fw-bold mb-0">PLANEJAMENTOS PEDAGÓGICOS</h2>
    <a href="/admin/planning/create" class="btn btn-hansen text-white">
        <i class="fas fa-plus me-2"></i> Novo Planejamento
    </a>
</div>

<!-- Filters -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form method="GET" action="/admin/planning" class="row g-3 align-items-end">
            <?php if ($userRole !== 'teacher'): ?>
            <div class="col-md-3">
                <label class="form-label small fw-bold mb-1">Professor</label>
                <input type="text" name="teacher_id" class="form-control form-control-sm"
                    value="<?= $filters['teacher_id'] ?? '' ?>" placeholder="ID do professor">
            </div>
            <?php endif; ?>
            <div class="col-md-3">
                <label class="form-label small fw-bold mb-1">Turma</label>
                <select name="classroom_id" class="form-select form-select-sm">
                    <option value="">Todas</option>
                    <?php foreach ($classrooms as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= (($filters['classroom_id'] ?? '') == $c['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold mb-1">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">Todos</option>
                    <option value="draft" <?= (($filters['status'] ?? '') === 'draft') ? 'selected' : '' ?>>Rascunho</option>
                    <option value="submitted" <?= (($filters['status'] ?? '') === 'submitted') ? 'selected' : '' ?>>Enviado</option>
                    <option value="registered" <?= (($filters['status'] ?? '') === 'registered') ? 'selected' : '' ?>>Registrado</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-sm me-2"><i class="fas fa-filter me-1"></i> Filtrar</button>
                <a href="/admin/planning" class="btn btn-light btn-sm"><i class="fas fa-times me-1"></i> Limpar</a>
            </div>
        </form>
    </div>
</div>

<?php
$statusBadge = [
    'draft' => ['bg-warning text-dark', 'Rascunho'],
    'submitted' => ['bg-primary', 'Enviado'],
    'registered' => ['bg-success', 'Registrado']
];
?>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">ID</th>
                        <th class="py-3">Template</th>
                        <th class="py-3">Professor</th>
                        <th class="py-3">Turma</th>
                        <th class="py-3">Quinzena</th>
                        <th class="py-3">Status</th>
                        <th class="py-3">Criado em</th>
                        <th class="py-3 text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($submissions)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="fas fa-clipboard fa-3x mb-3"></i><br>
                                Nenhum planejamento encontrado.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($submissions as $sub): ?>
                        <tr>
                            <td class="ps-4"><?= $sub['id'] ?></td>
                            <td><?= htmlspecialchars($sub['template_title'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($sub['teacher_name'] ?? '-') ?></td>
                            <td><?= htmlspecialchars($sub['classroom_name'] ?? '-') ?></td>
                            <td>
                                <?= date('d/m', strtotime($sub['period_start'])) ?> - <?= date('d/m/Y', strtotime($sub['period_end'])) ?>
                            </td>
                            <td>
                                <?php $badge = $statusBadge[$sub['status']] ?? ['bg-secondary', $sub['status']]; ?>
                                <span class="badge <?= $badge[0] ?>"><?= $badge[1] ?></span>
                            </td>
                            <td class="small"><?= date('d/m/Y', strtotime($sub['created_at'])) ?></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="/admin/planning/<?= $sub['id'] ?>" class="btn btn-sm btn-outline-primary" title="Ver">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <?php if ($sub['status'] !== 'registered'): ?>
                                    <a href="/admin/planning/<?= $sub['id'] ?>/edit" class="btn btn-sm btn-outline-secondary" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php endif; ?>
                                    <?php if ($sub['status'] === 'draft'): ?>
                                    <button onclick="confirmDelete(<?= $sub['id'] ?>)" class="btn btn-sm btn-outline-danger" title="Excluir">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<form id="deleteForm" method="POST" style="display: none;"></form>

<script>
function confirmDelete(id) {
    if (confirm('Excluir este planejamento rascunho?')) {
        const form = document.getElementById('deleteForm');
        form.action = `/admin/planning/${id}/delete`;
        form.submit();
    }
}
</script>
