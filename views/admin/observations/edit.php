<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/admin/observations" class="text-decoration-none">Observacoes</a></li>
        <li class="breadcrumb-item active" aria-current="page">Editar Observacao</li>
    </ol>
</nav>

<?php
$status = $observation['status'] ?? 'in_progress';
$isFinalized = ($status === 'finalized');
$readonlyAttr = $isFinalized ? 'readonly' : '';
$disabledAttr = $isFinalized ? 'disabled' : '';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="/admin/observations/<?php echo $observation['id']; ?>" class="text-decoration-none text-muted">
            <i class="fas fa-arrow-left me-2"></i> Voltar para detalhes
        </a>
        <h2 class="fw-bold mt-3" style="color: var(--primary-color, #007e66);">
            <i class="fas fa-edit me-2"></i>EDITAR OBSERVACAO
        </h2>
    </div>
    <div class="d-flex align-items-center gap-3">
        <!-- Auto-save indicator -->
        <span id="autoSaveIndicator" class="text-muted small" style="display: none;">
            <i class="fas fa-clock me-1"></i>
            <span id="autoSaveText">Salvo automaticamente</span>
        </span>

        <?php if ($isFinalized): ?>
            <span class="badge bg-success fs-6 px-3 py-2">
                <i class="fas fa-check-circle me-1"></i> Finalizado
            </span>
        <?php else: ?>
            <span class="badge bg-warning text-dark fs-6 px-3 py-2">
                <i class="fas fa-edit me-1"></i> Em andamento
            </span>
        <?php endif; ?>
    </div>
</div>

<form action="/admin/observations/<?php echo $observation['id']; ?>/update" method="POST" id="observationForm">
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
                    <label class="form-label fw-bold">Aluno</label>
                    <select name="student_id" id="student_id" class="form-select" required <?php echo $disabledAttr; ?>>
                        <option value="">Selecione um aluno</option>
                        <?php foreach ($students as $student): ?>
                            <option value="<?php echo $student['id']; ?>"
                                <?php echo ($observation['student_id'] == $student['id']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($student['name']); ?>
                                <?php if (!empty($student['school_name'])): ?>
                                    - <?php echo htmlspecialchars($student['school_name']); ?>
                                <?php endif; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if ($isFinalized): ?>
                        <input type="hidden" name="student_id" value="<?php echo $observation['student_id']; ?>">
                    <?php endif; ?>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Semestre</label>
                    <select name="semester" class="form-select" required <?php echo $disabledAttr; ?>>
                        <option value="1" <?php echo ($observation['semester'] == 1) ? 'selected' : ''; ?>>1o Semestre</option>
                        <option value="2" <?php echo ($observation['semester'] == 2) ? 'selected' : ''; ?>>2o Semestre</option>
                    </select>
                    <?php if ($isFinalized): ?>
                        <input type="hidden" name="semester" value="<?php echo $observation['semester']; ?>">
                    <?php endif; ?>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Ano Letivo</label>
                    <select name="year" class="form-select" required <?php echo $disabledAttr; ?>>
                        <?php foreach ($years as $y): ?>
                            <option value="<?php echo $y; ?>" <?php echo ($observation['year'] == $y) ? 'selected' : ''; ?>>
                                <?php echo $y; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php if ($isFinalized): ?>
                        <input type="hidden" name="year" value="<?php echo $observation['year']; ?>">
                    <?php endif; ?>
                </div>
            </div>

            <!-- Info de criacao -->
            <div class="row">
                <div class="col-md-4">
                    <small class="text-muted">
                        <i class="fas fa-user-tie me-1"></i> Criado por: <strong><?php echo htmlspecialchars($observation['teacher_name']); ?></strong>
                    </small>
                </div>
                <div class="col-md-4">
                    <small class="text-muted">
                        <i class="fas fa-calendar me-1"></i> Criado em: <strong><?php echo date('d/m/Y H:i', strtotime($observation['created_at'])); ?></strong>
                    </small>
                </div>
                <div class="col-md-4">
                    <small class="text-muted">
                        <i class="fas fa-sync-alt me-1"></i> Atualizado: <strong><?php echo date('d/m/Y H:i', strtotime($observation['updated_at'])); ?></strong>
                    </small>
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
                    <textarea name="observation_general" class="form-control auto-save-field" rows="5"
                        data-field="observation_general" <?php echo $readonlyAttr; ?>
                        placeholder="Registre aqui observacoes gerais sobre o desenvolvimento do aluno neste periodo..."><?php echo htmlspecialchars($observation['observation_general'] ?? ''); ?></textarea>
                </div>
                <div class="tab-pane fade" id="panel-movement" role="tabpanel">
                    <label class="form-label fw-bold">Eixo Atividade de Movimento</label>
                    <textarea name="axis_movement" class="form-control auto-save-field" rows="5"
                        data-field="axis_movement" <?php echo $readonlyAttr; ?>
                        placeholder="Descreva o desenvolvimento do aluno nas atividades de movimento..."><?php echo htmlspecialchars($observation['axis_movement'] ?? ''); ?></textarea>
                </div>
                <div class="tab-pane fade" id="panel-manual" role="tabpanel">
                    <label class="form-label fw-bold">Eixo Atividade Manual</label>
                    <textarea name="axis_manual" class="form-control auto-save-field" rows="5"
                        data-field="axis_manual" <?php echo $readonlyAttr; ?>
                        placeholder="Descreva o desenvolvimento do aluno nas atividades manuais..."><?php echo htmlspecialchars($observation['axis_manual'] ?? ''); ?></textarea>
                </div>
                <div class="tab-pane fade" id="panel-music" role="tabpanel">
                    <label class="form-label fw-bold">Eixo Atividade Musical</label>
                    <textarea name="axis_music" class="form-control auto-save-field" rows="5"
                        data-field="axis_music" <?php echo $readonlyAttr; ?>
                        placeholder="Descreva o desenvolvimento do aluno nas atividades musicais..."><?php echo htmlspecialchars($observation['axis_music'] ?? ''); ?></textarea>
                </div>
                <div class="tab-pane fade" id="panel-stories" role="tabpanel">
                    <label class="form-label fw-bold">Eixo Atividade de Contos</label>
                    <textarea name="axis_stories" class="form-control auto-save-field" rows="5"
                        data-field="axis_stories" <?php echo $readonlyAttr; ?>
                        placeholder="Descreva o desenvolvimento do aluno nas atividades de contos..."><?php echo htmlspecialchars($observation['axis_stories'] ?? ''); ?></textarea>
                </div>
                <div class="tab-pane fade" id="panel-pca" role="tabpanel">
                    <label class="form-label fw-bold">Eixo Programa Comunicacao Ativa</label>
                    <textarea name="axis_pca" class="form-control auto-save-field" rows="5"
                        data-field="axis_pca" <?php echo $readonlyAttr; ?>
                        placeholder="Descreva o desenvolvimento do aluno no Programa Comunicacao Ativa..."><?php echo htmlspecialchars($observation['axis_pca'] ?? ''); ?></textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- Botoes -->
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <a href="/admin/observations/<?php echo $observation['id']; ?>" class="btn btn-light">
                <i class="fas fa-arrow-left me-1"></i> Voltar
            </a>
        </div>
        <?php if (!$isFinalized): ?>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-hansen px-4">
                    <i class="fas fa-save me-2"></i> SALVAR ALTERACOES
                </button>
                <button type="button" class="btn btn-success px-4" data-bs-toggle="modal" data-bs-target="#finalizeModal">
                    <i class="fas fa-check-circle me-2"></i> FINALIZAR REGISTRO
                </button>
            </div>
        <?php endif; ?>
    </div>
</form>

<?php if (!$isFinalized): ?>
<!-- Modal de Confirmacao de Finalizacao -->
<div class="modal fade" id="finalizeModal" tabindex="-1" aria-labelledby="finalizeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="finalizeModalLabel">
                    <i class="fas fa-check-circle me-2 text-success"></i>Finalizar Registro
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning mb-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Atencao:</strong> Apos finalizar, os campos ficarao somente leitura e nao poderao ser editados pelo professor.
                </div>
                <p>Deseja realmente finalizar esta observacao?</p>
                <p class="text-muted small mb-0">
                    Apenas a coordenacao podera reabrir o registro apos a finalizacao.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                <form action="/admin/observations/<?php echo $observation['id']; ?>/finalize" method="POST" style="display: inline;">
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-check-circle me-2"></i> Sim, Finalizar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
(function() {
    const observationId = <?php echo $observation['id']; ?>;
    const isFinalized = <?php echo $isFinalized ? 'true' : 'false'; ?>;
    const indicator = document.getElementById('autoSaveIndicator');
    const indicatorText = document.getElementById('autoSaveText');

    if (isFinalized) return;

    // Track original values for change detection
    const originalValues = {};
    const fields = document.querySelectorAll('.auto-save-field');

    fields.forEach(function(field) {
        originalValues[field.getAttribute('data-field')] = field.value;
    });

    let hasUnsavedChanges = false;
    let saveTimeout = null;

    // Auto-save on blur and debounced on input
    fields.forEach(function(field) {
        field.addEventListener('blur', function() {
            const fieldName = this.getAttribute('data-field');
            if (this.value !== originalValues[fieldName]) {
                autoSaveField(fieldName, this.value);
                originalValues[fieldName] = this.value;
            }
        });

        field.addEventListener('input', function() {
            hasUnsavedChanges = true;
            const fieldName = this.getAttribute('data-field');

            // Debounce: save after 2 seconds of inactivity
            if (saveTimeout) clearTimeout(saveTimeout);
            saveTimeout = setTimeout(function() {
                if (field.value !== originalValues[fieldName]) {
                    autoSaveField(fieldName, field.value);
                    originalValues[fieldName] = field.value;
                }
            }, 2000);
        });
    });

    function autoSaveField(fieldName, value) {
        // Show saving indicator
        indicator.style.display = 'inline';
        indicatorText.textContent = 'Salvando...';

        fetch('/admin/observations/' + observationId + '/auto-save', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                field: fieldName,
                value: value
            })
        })
        .then(function(response) { return response.json(); })
        .then(function(data) {
            if (data.success) {
                indicatorText.textContent = 'Salvo automaticamente as ' + data.saved_at;
                hasUnsavedChanges = false;
            } else {
                indicatorText.textContent = 'Erro ao salvar: ' + data.message;
            }
        })
        .catch(function(err) {
            indicatorText.textContent = 'Erro de conexao ao salvar';
            console.error('Auto-save error:', err);
        });
    }

    // Warn before leaving with unsaved changes
    window.addEventListener('beforeunload', function(e) {
        if (hasUnsavedChanges) {
            e.preventDefault();
            e.returnValue = '';
            return '';
        }
    });

    // Clear warning on form submit
    var form = document.getElementById('observationForm');
    if (form) {
        form.addEventListener('submit', function() {
            hasUnsavedChanges = false;
        });
    }
})();
</script>
