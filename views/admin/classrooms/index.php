<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item active">Turmas</li>
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
    <h2 class="text-primary fw-bold mb-0">TURMAS</h2>
    <a href="/admin/classrooms/create" class="btn btn-hansen text-white">
        <i class="fas fa-plus me-2"></i> Nova Turma
    </a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">ID</th>
                        <th class="py-3">Nome</th>
                        <th class="py-3">Alunos</th>
                        <th class="py-3">Professor</th>
                        <th class="py-3">Faixa Etária</th>
                        <th class="py-3">Período</th>
                        <th class="py-3">Ano</th>
                        <th class="py-3">Status</th>
                        <th class="py-3 text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($classrooms)): ?>
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="fas fa-chalkboard fa-3x mb-3"></i><br>
                                Nenhuma turma cadastrada.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php
                        $periodLabels = ['morning' => 'Manhã', 'afternoon' => 'Tarde', 'full' => 'Integral'];
                        foreach ($classrooms as $c): ?>
                        <tr>
                            <td class="ps-4"><?= $c['id'] ?></td>
                            <td class="fw-bold">
                                <a href="/admin/classrooms/<?= $c['id'] ?>" class="text-decoration-none text-dark">
                                    <?= htmlspecialchars($c['name']) ?>
                                </a>
                            </td>
                            <td>
                                <span class="badge bg-primary rounded-pill">
                                    <i class="fas fa-users me-1"></i><?= $studentCounts[$c['id']] ?? 0 ?>
                                </span>
                            </td>
                            <td><?= htmlspecialchars($c['teacher_name'] ?? '-') ?></td>
                            <td><span class="badge bg-info"><?= $c['age_group'] ?> anos</span></td>
                            <td><?= $periodLabels[$c['period']] ?? $c['period'] ?></td>
                            <td><?= $c['school_year'] ?></td>
                            <td>
                                <?php if ($c['status'] === 'active'): ?>
                                    <span class="badge bg-success">Ativa</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inativa</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="/admin/classrooms/<?= $c['id'] ?>/edit" class="btn btn-sm btn-outline-secondary" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <?php if ($c['status'] === 'active'): ?>
                                        <button onclick="toggleStatus(<?= $c['id'] ?>, '<?= htmlspecialchars($c['name']) ?>', 'desativar')" class="btn btn-sm btn-outline-warning" title="Desativar">
                                            <i class="fas fa-toggle-off"></i>
                                        </button>
                                    <?php else: ?>
                                        <button onclick="toggleStatus(<?= $c['id'] ?>, '<?= htmlspecialchars($c['name']) ?>', 'ativar')" class="btn btn-sm btn-outline-success" title="Ativar">
                                            <i class="fas fa-toggle-on"></i>
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

<form id="toggleStatusForm" method="POST" style="display: none;"></form>

<script>
function toggleStatus(id, name, action) {
    const msg = action === 'desativar'
        ? `Desativar turma "${name}"?\n\nA turma ficará inativa, mas todo o histórico será preservado.`
        : `Ativar turma "${name}"?\n\nA turma voltará a ficar disponível.`;
    if (confirm(msg)) {
        const form = document.getElementById('toggleStatusForm');
        form.action = `/admin/classrooms/${id}/toggle-status`;
        form.submit();
    }
}
</script>
