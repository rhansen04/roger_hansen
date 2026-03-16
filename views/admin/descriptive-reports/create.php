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
                            <?php $selSemester = $_GET['semester'] ?? ''; ?>
                            <select name="semester" id="semester" class="form-select" required onchange="loadObservations()">
                                <option value="">Selecione...</option>
                                <option value="1" <?php echo ($selSemester == '1') ? 'selected' : ''; ?>>1o Semestre</option>
                                <option value="2" <?php echo ($selSemester == '2') ? 'selected' : ''; ?>>2o Semestre</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label for="year" class="form-label fw-bold">
                                <i class="fas fa-calendar me-1 text-primary"></i> Ano <span class="text-danger">*</span>
                            </label>
                            <input type="number" name="year" id="year" class="form-control" value="<?php echo date('Y'); ?>" min="2020" max="2030" required>
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

                    <!-- Observacoes disponiveis -->
                    <div class="mb-4" id="observationsSection" style="display:none;">
                        <label class="form-label fw-bold">
                            <i class="fas fa-clipboard-list me-1 text-primary"></i> Observacao Vinculada
                        </label>
                        <div id="observationsList">
                            <!-- Preenchido dinamicamente -->
                        </div>
                        <input type="hidden" name="observation_id" id="observation_id" value="<?php echo $selectedObservationId ?? ''; ?>">
                    </div>

                    <?php if (!empty($selectedStudentId) && !empty($observations)): ?>
                    <!-- Mostrar observacoes pre-carregadas (se veio com student_id na URL) -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">
                            <i class="fas fa-clipboard-list me-1 text-primary"></i> Observacoes Disponiveis
                        </label>
                        <?php foreach ($observations as $obs): ?>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="observation_id" value="<?php echo $obs['id']; ?>" id="obs_<?php echo $obs['id']; ?>"
                                    <?php echo ($selectedObservationId == $obs['id']) ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="obs_<?php echo $obs['id']; ?>">
                                    <strong><?php echo htmlspecialchars($obs['title'] ?? 'Observacao #' . $obs['id']); ?></strong>
                                    <br><small class="text-muted">
                                        <?php if (!empty($obs['observation_date'])): ?>
                                            <?php echo date('d/m/Y', strtotime($obs['observation_date'])); ?> &middot;
                                        <?php endif; ?>
                                        <?php echo htmlspecialchars($obs['category'] ?? 'Geral'); ?>
                                        <?php if (!empty($obs['semester'])): ?>
                                            &middot; <?php echo $obs['semester']; ?>o Sem/<?php echo $obs['year']; ?>
                                        <?php endif; ?>
                                        <?php if (($obs['status'] ?? '') === 'finalized'): ?>
                                            <span class="badge bg-success ms-1">Finalizada</span>
                                        <?php endif; ?>
                                    </small>
                                </label>
                            </div>
                        <?php endforeach; ?>
                        <div class="form-text">Selecione a observacao cujos textos serao compilados no parecer.</div>
                    </div>
                    <?php elseif (!empty($selectedStudentId) && empty($observations)): ?>
                    <div class="alert alert-warning mb-4">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Nenhuma observacao encontrada</strong> para este aluno.
                        <br><small>Voce precisa criar uma observacao antes de gerar o parecer descritivo.</small>
                        <div class="mt-2">
                            <a href="/admin/observations/create?student_id=<?php echo $selectedStudentId; ?>" class="btn btn-sm btn-warning">
                                <i class="fas fa-plus me-1"></i> Criar Observacao
                            </a>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="alert alert-info small">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Como funciona:</strong> O sistema ira compilar automaticamente os textos dos eixos pedagogicos da observacao selecionada, gerando o texto base do parecer descritivo. Voce podera editar o texto na tela seguinte.
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

    if (!studentId) return;

    // Redirecionar para a mesma pagina com o student_id para carregar observacoes
    const url = new URL(window.location.href);
    url.searchParams.set('student_id', studentId);
    if (semester) url.searchParams.set('semester', semester);
    window.location.href = url.toString();
}
</script>
