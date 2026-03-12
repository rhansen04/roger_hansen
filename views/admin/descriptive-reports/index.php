<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Pareceres Descritivos</li>
    </ol>
</nav>

<!-- Mensagens de Sucesso/Erro -->
<?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i><?php echo $_SESSION['success_message']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['success_message']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error_message'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i><?php echo $_SESSION['error_message']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['error_message']); ?>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="text-primary fw-bold mb-0">PARECERES DESCRITIVOS</h2>
        <p class="text-muted mb-0 small">Gerencie os pareceres descritivos dos alunos</p>
    </div>
    <a href="/admin/descriptive-reports/create" class="btn btn-hansen text-white text-decoration-none">
        <i class="fas fa-plus me-2"></i> Novo Parecer
    </a>
</div>

<!-- Contadores -->
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card card-stat border-0 shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width:48px;height:48px;background:rgba(108,117,125,0.1)">
                    <i class="fas fa-file-alt text-secondary fa-lg"></i>
                </div>
                <div>
                    <div class="fw-bold fs-4"><?php echo $counts['draft'] ?? 0; ?></div>
                    <div class="text-muted small">Rascunhos</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-stat border-0 shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width:48px;height:48px;background:rgba(25,135,84,0.1)">
                    <i class="fas fa-check-circle text-success fa-lg"></i>
                </div>
                <div>
                    <div class="fw-bold fs-4"><?php echo $counts['finalized'] ?? 0; ?></div>
                    <div class="text-muted small">Finalizados</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-stat border-0 shadow-sm">
            <div class="card-body d-flex align-items-center">
                <div class="rounded-circle d-flex align-items-center justify-content-center me-3" style="width:48px;height:48px;background:rgba(255,193,7,0.1)">
                    <i class="fas fa-exclamation-circle text-warning fa-lg"></i>
                </div>
                <div>
                    <div class="fw-bold fs-4"><?php echo $counts['revision_requested'] ?? 0; ?></div>
                    <div class="text-muted small">Em Revisao</div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filtros -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form method="GET" action="/admin/descriptive-reports" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-bold mb-1">Turma</label>
                <select name="classroom_id" class="form-select form-select-sm">
                    <option value="">Todas as turmas</option>
                    <?php foreach ($classrooms as $cr): ?>
                        <option value="<?php echo $cr['id']; ?>" <?php echo ($filters['classroom_id'] == $cr['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($cr['name']); ?> (<?php echo $cr['school_year']; ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold mb-1">Semestre</label>
                <select name="semester" class="form-select form-select-sm">
                    <option value="">Todos</option>
                    <option value="1" <?php echo ($filters['semester'] == '1') ? 'selected' : ''; ?>>1o Semestre</option>
                    <option value="2" <?php echo ($filters['semester'] == '2') ? 'selected' : ''; ?>>2o Semestre</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold mb-1">Ano</label>
                <input type="number" name="year" class="form-control form-control-sm" placeholder="<?php echo date('Y'); ?>" value="<?php echo $filters['year'] ?? ''; ?>" min="2020" max="2030">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold mb-1">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">Todos</option>
                    <option value="draft" <?php echo ($filters['status'] == 'draft') ? 'selected' : ''; ?>>Rascunho</option>
                    <option value="finalized" <?php echo ($filters['status'] == 'finalized') ? 'selected' : ''; ?>>Finalizado</option>
                    <option value="revision_requested" <?php echo ($filters['status'] == 'revision_requested') ? 'selected' : ''; ?>>Revisao Solicitada</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary btn-sm me-2">
                    <i class="fas fa-filter me-1"></i> Filtrar
                </button>
                <a href="/admin/descriptive-reports" class="btn btn-light btn-sm">
                    <i class="fas fa-times me-1"></i> Limpar
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Cards dos Pareceres -->
<?php if (empty($reports)): ?>
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5 text-muted">
            <i class="fas fa-file-alt fa-3x mb-3"></i><br>
            Nenhum parecer descritivo encontrado.
            <br><br>
            <a href="/admin/descriptive-reports/create" class="btn btn-hansen text-white">
                <i class="fas fa-plus me-2"></i> Criar Primeiro Parecer
            </a>
        </div>
    </div>
<?php else: ?>
    <div class="row g-3">
        <?php foreach ($reports as $report): ?>
            <?php
                $statusBadge = match($report['status']) {
                    'draft' => '<span class="badge bg-secondary">Rascunho</span>',
                    'finalized' => '<span class="badge bg-success">Finalizado</span>',
                    'revision_requested' => '<span class="badge bg-warning text-dark">Revisao Solicitada</span>',
                    default => '<span class="badge bg-secondary">Rascunho</span>'
                };
            ?>
            <div class="col-md-6 col-lg-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <?php if (!empty($report['student_photo'])): ?>
                                <img src="<?php echo htmlspecialchars($report['student_photo']); ?>" class="rounded-circle me-3" width="50" height="50" style="object-fit:cover;" alt="Foto">
                            <?php else: ?>
                                <div class="rounded-circle me-3 d-flex align-items-center justify-content-center" style="width:50px;height:50px;background:rgba(0,126,102,0.1)">
                                    <i class="fas fa-user text-primary"></i>
                                </div>
                            <?php endif; ?>
                            <div class="flex-grow-1">
                                <h6 class="mb-0 fw-bold"><?php echo htmlspecialchars($report['student_name']); ?></h6>
                                <small class="text-muted">
                                    <?php echo $report['semester']; ?>o Sem / <?php echo $report['year']; ?>
                                    <?php if (!empty($report['classroom_name'])): ?>
                                        &middot; <?php echo htmlspecialchars($report['classroom_name']); ?>
                                    <?php endif; ?>
                                </small>
                            </div>
                            <?php echo $statusBadge; ?>
                        </div>

                        <?php if ($report['status'] === 'revision_requested' && !empty($report['revision_notes'])): ?>
                            <div class="alert alert-warning py-1 px-2 small mb-2">
                                <i class="fas fa-exclamation-triangle me-1"></i>
                                <?php echo htmlspecialchars(mb_substr($report['revision_notes'], 0, 80)); ?>
                                <?php if (mb_strlen($report['revision_notes']) > 80) echo '...'; ?>
                            </div>
                        <?php endif; ?>

                        <div class="d-flex gap-2 mt-auto">
                            <a href="/admin/descriptive-reports/<?php echo $report['id']; ?>" class="btn btn-sm btn-outline-primary" title="Visualizar">
                                <i class="fas fa-eye me-1"></i> Ver
                            </a>
                            <?php if ($report['status'] !== 'finalized'): ?>
                                <a href="/admin/descriptive-reports/<?php echo $report['id']; ?>/edit" class="btn btn-sm btn-outline-secondary" title="Editar">
                                    <i class="fas fa-edit me-1"></i> Editar
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
