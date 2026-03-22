<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/admin/descriptive-reports" class="text-decoration-none">Pareceres Descritivos</a></li>
        <li class="breadcrumb-item active" aria-current="page">Novo Parecer</li>
    </ol>
</nav>

<!-- Mensagens -->
<?php if (isset($_SESSION['error_message'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i><?php echo $_SESSION['error_message']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['error_message']); ?>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="/admin/descriptive-reports" class="text-decoration-none text-muted mb-2 d-block">
            <i class="fas fa-arrow-left me-2"></i> Voltar para pareceres
        </a>
        <h2 class="text-primary fw-bold mb-0">GERAR NOVO PARECER DESCRITIVO</h2>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-md-8 col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <form method="POST" action="/admin/descriptive-reports" id="createForm">
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
                    <div class="mb-4">
                        <label for="student_id" class="form-label fw-bold">
                            <i class="fas fa-user-graduate me-1 text-primary"></i> Aluno <span class="text-danger">*</span>
                        </label>
                        <select name="student_id" id="student_id" class="form-select" required onchange="loadObservations()">
                            <option value="">Selecione o aluno...</option>
                            <?php foreach ($students as $s): ?>
                                <option value="<?php echo $s['id']; ?>" <?php echo ($selectedStudentId == $s['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($s['name']); ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="semester" class="form-label fw-bold">
                                <i class="fas fa-calendar-alt me-1 text-primary"></i> Semestre <span class="text-danger">*</span>
                            </label>
                            <select name="semester" id="semester" class="form-select" required onchange="loadObservations()">
                                <option value="1" <?php echo ((int) ($selectedSemester ?? $defaultSemester ?? 1) === 1) ? 'selected' : ''; ?>>1o Semestre</option>
                                <option value="2" <?php echo ((int) ($selectedSemester ?? $defaultSemester ?? 1) === 2) ? 'selected' : ''; ?>>2o Semestre</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="year" class="form-label fw-bold">
                                <i class="fas fa-calendar me-1 text-primary"></i> Ano <span class="text-danger">*</span>
                            </label>
                            <input type="number" name="year" id="year" class="form-control" value="<?php echo (int) ($selectedYear ?? date('Y')); ?>" min="2020" max="2030" required onchange="loadObservations()">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label for="classroom_id" class="form-label fw-bold">
                            <i class="fas fa-chalkboard me-1 text-primary"></i> Turma <span class="text-muted fw-normal">(opcional)</span>
                        </label>
                        <select name="classroom_id" id="classroom_id" class="form-select">
                            <option value="">Detectar automaticamente</option>
                            <?php foreach ($classrooms as $cr): ?>
                                <option value="<?php echo $cr['id']; ?>">
                                    <?php echo htmlspecialchars($cr['name']); ?> (<?php echo $cr['school_year']; ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <div class="form-text">Se nao selecionada, sera detectada automaticamente.</div>
                    </div>

                    <?php if (!empty($selectedStudentId) && !empty($observations)): ?>
                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <i class="fas fa-clipboard-list me-1 text-primary"></i> Observacoes que serao compiladas
                        </label>
                        <div class="small text-muted mb-2">
                            O parecer sera gerado a partir de <strong>todas</strong> as observacoes encontradas para este aluno no semestre/ano selecionado, em ordem cronologica.
                        </div>
                        <?php foreach ($observations as $obs): ?>
                            <div class="border rounded p-3 mb-2 bg-light">
                                <strong><?php echo htmlspecialchars($obs['title'] ?? 'Observacao #' . $obs['id']); ?></strong>
                                <br><small class="text-muted">
                                    Criada em <?php echo date('d/m/Y H:i', strtotime($obs['created_at'])); ?>
                                    <?php if (!empty($obs['semester'])): ?>
                                        &middot; <?php echo $obs['semester']; ?>o Sem/<?php echo $obs['year']; ?>
                                    <?php endif; ?>
                                    <?php if (($obs['status'] ?? '') === 'finalized'): ?>
                                        <span class="badge bg-success ms-1">Finalizada</span>
                                    <?php endif; ?>
                                </small>
                            </div>
                        <?php endforeach; ?>
                        <div class="form-text">Total encontrado: <?php echo count($observations); ?> observacao(oes).</div>
                    </div>
                    <?php elseif (!empty($selectedStudentId) && empty($observations)): ?>
                    <div class="alert alert-warning mb-4">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Nenhuma observacao encontrada</strong> para este aluno.
                        <br><small>Voce precisa criar uma observacao antes de gerar o parecer descritivo.</small>
                        <div class="mt-2">
                            <a href="/admin/observations/create?student_id=<?php echo $selectedStudentId; ?>&semester=<?php echo (int) $selectedSemester; ?>&year=<?php echo (int) $selectedYear; ?>" class="btn btn-sm btn-warning">
                                <i class="fas fa-plus me-1"></i> Criar Observacao
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="alert alert-info small">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Como funciona:</strong> O sistema ira compilar automaticamente os textos de todas as observacoes do aluno no semestre/ano selecionado, gerando o texto base do parecer descritivo. Voce podera editar o texto na tela seguinte.
                    </div>

                    <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                        <a href="/admin/descriptive-reports" class="btn btn-light me-md-2">Cancelar</a>
                        <button type="submit" class="btn btn-hansen text-white">
                            <i class="fas fa-magic me-2"></i> Gerar Parecer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function loadObservations() {
    const studentId = document.getElementById('student_id').value;
    const semester = document.getElementById('semester').value;
    const year = document.getElementById('year').value;

    if (!studentId || !semester || !year) return;

    const url = new URL(window.location.href);
    url.searchParams.set('student_id', studentId);
    if (semester) url.searchParams.set('semester', semester);
    if (year) url.searchParams.set('year', year);
    window.location.href = url.toString();
}
</script>
