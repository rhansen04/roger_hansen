<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0" style="color:var(--primary-color)"><i class="fas fa-link me-2"></i>Vincular Filhos</h2>
        <small class="text-muted">Responsavel: <strong><?= htmlspecialchars($parent['name']) ?></strong> &middot; <?= htmlspecialchars($parent['email']) ?></small>
    </div>
    <a href="/admin/parents" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i>Voltar</a>
</div>

<?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($_SESSION['success_message']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['success_message']); ?>
<?php endif; ?>
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($_SESSION['error']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<div class="row g-4">
    <div class="col-md-5">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold"><i class="fas fa-plus-circle me-2 text-success"></i>Vincular novo aluno</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="/admin/parents/<?= $parent['id'] ?>/link">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Aluno</label>
                        <select name="student_id" class="form-select" required>
                            <option value="">Selecione o aluno...</option>
                            <?php foreach ($students as $s): ?>
                                <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['name']) ?> (<?= htmlspecialchars($s['email']) ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Parentesco</label>
                        <select name="relationship" class="form-select">
                            <option value="pai">Pai</option>
                            <option value="mae">Mae</option>
                            <option value="responsavel">Responsavel</option>
                        </select>
                    </div>
                    <button type="submit" class="btn w-100" style="background:var(--primary-color);color:white">
                        <i class="fas fa-link me-2"></i>Vincular
                    </button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-7">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold"><i class="fas fa-users me-2"></i>Alunos vinculados <span class="badge bg-secondary ms-1"><?= count($linked) ?></span></h6>
            </div>
            <div class="card-body p-0">
                <?php if (empty($linked)): ?>
                    <p class="text-muted text-center py-4">Nenhum aluno vinculado ainda.</p>
                <?php else: ?>
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>Aluno</th><th>Parentesco</th><th>Vinculado em</th><th></th></tr>
                    </thead>
                    <tbody>
                    <?php foreach ($linked as $lk): ?>
                    <tr>
                        <td class="small fw-bold"><?= htmlspecialchars($lk['student_name']) ?></td>
                        <td class="small text-muted"><?= htmlspecialchars($lk['relationship']) ?></td>
                        <td class="small text-muted"><?= date('d/m/Y', strtotime($lk['created_at'])) ?></td>
                        <td>
                            <form method="POST" action="/admin/parents/unlink/<?= $lk['id'] ?>" onsubmit="return confirm('Remover vinculo com este aluno?')">
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Remover vinculo">
                                    <i class="fas fa-unlink"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
