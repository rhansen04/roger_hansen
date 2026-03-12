<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/admin/observations" class="text-decoration-none">Observacoes</a></li>
        <li class="breadcrumb-item active" aria-current="page">Nova Observacao</li>
    </ol>
</nav>

<div class="mb-4">
    <a href="/admin/observations" class="text-decoration-none text-muted"><i class="fas fa-arrow-left me-2"></i> Voltar para listagem</a>
    <h2 class="fw-bold mt-3" style="color: var(--primary-color, #007e66);">
        <i class="fas fa-plus-circle me-2"></i>NOVA OBSERVACAO PEDAGOGICA
    </h2>
</div>

<form action="/admin/observations" method="POST" id="observationForm">
    <!-- Dados basicos -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0 fw-bold" style="color: var(--primary-color, #007e66);">
                <i class="fas fa-info-circle me-2"></i>Dados Basicos
            </h5>
        </div>
        <div class="card-body p-4">
            <div class="row">
                <div class="col-md-5 mb-3">
                    <label class="form-label fw-bold">Aluno <span class="text-danger">*</span></label>
                    <select name="student_id" id="student_id" class="form-select" required>
                        <option value="">Selecione um aluno</option>
                        <?php foreach ($students as $student): ?>
                            <option value="<?php echo $student['id']; ?>"
                                <?php echo ($selectedStudentId == $student['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($student['name']); ?>
                                <?php if (!empty($student['school_name'])): ?>
                                    - <?php echo htmlspecialchars($student['school_name']); ?>
                                <?php endif; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Semestre <span class="text-danger">*</span></label>
                    <select name="semester" class="form-select" required>
                        <option value="1" <?php echo ($defaultSemester == 1) ? 'selected' : ''; ?>>1o Semestre</option>
                        <option value="2" <?php echo ($defaultSemester == 2) ? 'selected' : ''; ?>>2o Semestre</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Ano Letivo <span class="text-danger">*</span></label>
                    <select name="year" class="form-select" required>
                        <?php foreach ($years as $y): ?>
                            <option value="<?php echo $y; ?>" <?php echo ($y == $currentYear) ? 'selected' : ''; ?>>
                                <?php echo $y; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <!-- Eixos Pedagogicos com Tabs -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0 fw-bold" style="color: var(--primary-color, #007e66);">
                <i class="fas fa-layer-group me-2"></i>Eixos Pedagogicos
            </h5>
        </div>
        <div class="card-body p-4">
            <ul class="nav nav-tabs" id="axesTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="tab-general" data-bs-toggle="tab" data-bs-target="#panel-general" type="button" role="tab">
                        <i class="fas fa-file-alt me-1"></i> Observacao Geral
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-movement" data-bs-toggle="tab" data-bs-target="#panel-movement" type="button" role="tab">
                        <i class="fas fa-running me-1"></i> Movimento
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-manual" data-bs-toggle="tab" data-bs-target="#panel-manual" type="button" role="tab">
                        <i class="fas fa-hands me-1"></i> Manual
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-music" data-bs-toggle="tab" data-bs-target="#panel-music" type="button" role="tab">
                        <i class="fas fa-music me-1"></i> Musical
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-stories" data-bs-toggle="tab" data-bs-target="#panel-stories" type="button" role="tab">
                        <i class="fas fa-book-open me-1"></i> Contos
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="tab-pca" data-bs-toggle="tab" data-bs-target="#panel-pca" type="button" role="tab">
                        <i class="fas fa-comments me-1"></i> Comunicacao Ativa
                    </button>
                </li>
            </ul>

            <div class="tab-content pt-4" id="axesTabContent">
                <div class="tab-pane fade show active" id="panel-general" role="tabpanel">
                    <label class="form-label fw-bold">Observacao Geral</label>
                    <textarea name="observation_general" class="form-control" rows="5"
                        placeholder="Registre aqui observacoes gerais sobre o desenvolvimento do aluno neste periodo..."></textarea>
                </div>
                <div class="tab-pane fade" id="panel-movement" role="tabpanel">
                    <label class="form-label fw-bold">Eixo Atividade de Movimento</label>
                    <textarea name="axis_movement" class="form-control" rows="5"
                        placeholder="Descreva o desenvolvimento do aluno nas atividades de movimento..."></textarea>
                </div>
                <div class="tab-pane fade" id="panel-manual" role="tabpanel">
                    <label class="form-label fw-bold">Eixo Atividade Manual</label>
                    <textarea name="axis_manual" class="form-control" rows="5"
                        placeholder="Descreva o desenvolvimento do aluno nas atividades manuais..."></textarea>
                </div>
                <div class="tab-pane fade" id="panel-music" role="tabpanel">
                    <label class="form-label fw-bold">Eixo Atividade Musical</label>
                    <textarea name="axis_music" class="form-control" rows="5"
                        placeholder="Descreva o desenvolvimento do aluno nas atividades musicais..."></textarea>
                </div>
                <div class="tab-pane fade" id="panel-stories" role="tabpanel">
                    <label class="form-label fw-bold">Eixo Atividade de Contos</label>
                    <textarea name="axis_stories" class="form-control" rows="5"
                        placeholder="Descreva o desenvolvimento do aluno nas atividades de contos..."></textarea>
                </div>
                <div class="tab-pane fade" id="panel-pca" role="tabpanel">
                    <label class="form-label fw-bold">Eixo Programa Comunicacao Ativa</label>
                    <textarea name="axis_pca" class="form-control" rows="5"
                        placeholder="Descreva o desenvolvimento do aluno no Programa Comunicacao Ativa..."></textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- Botoes -->
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <span class="text-danger">*</span>
            <small class="text-muted">Campos obrigatorios</small>
        </div>
        <div>
            <a href="/admin/observations" class="btn btn-light me-2">Cancelar</a>
            <button type="submit" class="btn btn-hansen px-5">
                <i class="fas fa-save me-2"></i> CRIAR OBSERVACAO
            </button>
        </div>
    </div>
</form>

<script>
// Validacao do formulario
document.getElementById('observationForm').addEventListener('submit', function(e) {
    const studentId = document.getElementById('student_id').value;
    if (!studentId) {
        e.preventDefault();
        alert('Por favor, selecione um aluno.');
        document.getElementById('student_id').focus();
        return false;
    }
});

// Select2 para busca de alunos (se disponivel)
if (typeof jQuery !== 'undefined' && jQuery.fn.select2) {
    jQuery('#student_id').select2({
        placeholder: 'Digite para buscar um aluno...',
        allowClear: true
    });
}
</script>
