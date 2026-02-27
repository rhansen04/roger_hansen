<?php $isEdit = !empty($classroom); ?>

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/admin/classrooms" class="text-decoration-none">Turmas</a></li>
        <li class="breadcrumb-item active"><?= $isEdit ? 'Editar' : 'Nova' ?> Turma</li>
    </ol>
</nav>

<div class="mb-4">
    <a href="/admin/classrooms" class="text-decoration-none text-muted"><i class="fas fa-arrow-left me-2"></i> Voltar</a>
    <h2 class="text-primary fw-bold mt-3"><?= $isEdit ? 'EDITAR' : 'NOVA' ?> TURMA</h2>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="<?= $isEdit ? "/admin/classrooms/{$classroom['id']}/update" : '/admin/classrooms' ?>" method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Nome da Turma <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" required
                        value="<?= htmlspecialchars($classroom['name'] ?? '') ?>"
                        placeholder="Ex: Maternal II - Manhã">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Escola <span class="text-danger">*</span></label>
                    <select name="school_id" class="form-select" required>
                        <option value="">Selecione...</option>
                        <?php foreach ($schools as $s): ?>
                            <option value="<?= $s['id'] ?>" <?= (($classroom['school_id'] ?? '') == $s['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($s['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Professor <span class="text-danger">*</span></label>
                    <select name="teacher_id" class="form-select" required>
                        <option value="">Selecione...</option>
                        <?php foreach ($teachers as $t): ?>
                            <option value="<?= $t['id'] ?>" <?= (($classroom['teacher_id'] ?? '') == $t['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($t['name']) ?> (<?= $t['role'] ?? '' ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Faixa Etária <span class="text-danger">*</span></label>
                    <select name="age_group" class="form-select" required>
                        <option value="0-3" <?= (($classroom['age_group'] ?? '') === '0-3') ? 'selected' : '' ?>>0-3 anos (PFI)</option>
                        <option value="3-6" <?= (($classroom['age_group'] ?? '') === '3-6') ? 'selected' : '' ?>>3-6 anos (PFII)</option>
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Período</label>
                    <select name="period" class="form-select">
                        <option value="morning" <?= (($classroom['period'] ?? '') === 'morning') ? 'selected' : '' ?>>Manhã</option>
                        <option value="afternoon" <?= (($classroom['period'] ?? '') === 'afternoon') ? 'selected' : '' ?>>Tarde</option>
                        <option value="full" <?= (($classroom['period'] ?? '') === 'full') ? 'selected' : '' ?>>Integral</option>
                    </select>
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Ano Letivo</label>
                    <input type="number" name="school_year" class="form-control"
                        value="<?= $classroom['school_year'] ?? date('Y') ?>" min="2020" max="2030">
                </div>

                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Status</label>
                    <select name="status" class="form-select">
                        <option value="active" <?= (($classroom['status'] ?? 'active') === 'active') ? 'selected' : '' ?>>Ativa</option>
                        <option value="inactive" <?= (($classroom['status'] ?? '') === 'inactive') ? 'selected' : '' ?>>Inativa</option>
                    </select>
                </div>
            </div>

            <hr class="my-4">
            <div class="d-flex justify-content-between">
                <div><span class="text-danger">*</span> <small class="text-muted">Campos obrigatórios</small></div>
                <div>
                    <a href="/admin/classrooms" class="btn btn-light me-2">Cancelar</a>
                    <button type="submit" class="btn btn-hansen px-5">
                        <i class="fas fa-save me-2"></i> <?= $isEdit ? 'ATUALIZAR' : 'SALVAR' ?>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
