<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0" style="color:var(--primary-color)"><i class="fas fa-user-friends me-2"></i>Responsaveis / Pais</h2>
</div>

<?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($_SESSION['success_message']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['success_message']); ?>
<?php endif; ?>

<?php if (empty($parents)): ?>
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="fas fa-users fa-4x text-muted mb-3"></i>
            <h5 class="text-muted">Nenhum responsavel cadastrado.</h5>
            <p class="text-muted small">Crie usuarios com role "parent" para que aparecam aqui.</p>
            <a href="/admin/users/create" class="btn btn-hansen mt-2"><i class="fas fa-plus me-2"></i>Criar Usuario</a>
        </div>
    </div>
<?php else: ?>
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Filhos Vinculados</th>
                    <th>Cadastro</th>
                    <th>Acoes</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($parents as $p): ?>
            <tr>
                <td class="fw-bold small"><?= htmlspecialchars($p['name']) ?></td>
                <td class="small text-muted"><?= htmlspecialchars($p['email']) ?></td>
                <td class="small">
                    <?php if ($p['children_count'] > 0): ?>
                        <?= htmlspecialchars($p['children_names']) ?>
                        <span class="badge bg-success ms-1"><?= $p['children_count'] ?></span>
                    <?php else: ?>
                        <span class="text-muted">Nenhum</span>
                    <?php endif; ?>
                </td>
                <td class="small text-muted"><?= date('d/m/Y', strtotime($p['created_at'])) ?></td>
                <td>
                    <a href="/admin/parents/<?= $p['id'] ?>/link" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-link me-1"></i>Vincular Filhos
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>
