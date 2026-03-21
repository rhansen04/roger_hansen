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

<form action="/admin/observations/<?php echo $observation['id']; ?>/update" method="POST" id="observationForm" novalidate>
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
    <div id="validationSummary" class="alert alert-danger d-none" role="alert"></div>

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
                                data-pca-enabled="<?php echo !empty($pcaEnabledByStudent[$student['id']]) ? '1' : '0'; ?>"
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

    <?php include __DIR__ . '/_questions.php'; ?>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold" style="color: var(--primary-color, #007e66);">
                <i class="fas fa-history me-2"></i>Historico do Semestre
            </h5>
            <span class="badge bg-light text-dark"><?php echo count($observationHistory); ?> / <?php echo (int) $maxObservationsPerSemester; ?> registros</span>
        </div>
        <div class="card-body p-4">
            <p class="text-muted mb-3">
                Este registro faz parte de um conjunto cumulativo de observacoes do aluno neste semestre.
            </p>

            <?php if (empty($observationHistory)): ?>
                <div class="alert alert-light border mb-0">
                    Nenhum outro registro encontrado para este aluno neste semestre.
                </div>
            <?php else: ?>
                <div class="d-grid gap-3">
                    <?php foreach ($observationHistory as $history): ?>
                        <?php
                            $generalAnswers = parseAxisAnswers($history['observation_general'] ?? '', 8);
                            $preview = '';
                            foreach ($generalAnswers as $answer) {
                                if (trim($answer) !== '') {
                                    $preview = trim($answer);
                                    break;
                                }
                            }
                            $isCurrentHistory = ((int) $history['id'] === (int) $observation['id']);
                        ?>
                        <div class="border rounded-3 p-3 <?php echo $isCurrentHistory ? 'border-primary bg-primary-subtle' : 'bg-light-subtle'; ?>">
                            <div class="d-flex justify-content-between align-items-start gap-3">
                                <div>
                                    <div class="fw-semibold">
                                        Observacao #<?php echo (int) $history['id']; ?>
                                        <?php if ($isCurrentHistory): ?><span class="badge bg-primary ms-2">Registro atual</span><?php endif; ?>
                                    </div>
                                    <div class="small text-muted">
                                        Criada em <?php echo date('d/m/Y H:i', strtotime($history['created_at'])); ?>
                                        por <?php echo htmlspecialchars($history['teacher_name'] ?? 'Usuario'); ?>
                                    </div>
                                </div>
                                <span class="badge <?php echo (($history['status'] ?? '') === 'finalized') ? 'bg-success' : 'bg-warning text-dark'; ?>">
                                    <?php echo (($history['status'] ?? '') === 'finalized') ? 'Finalizada' : 'Em andamento'; ?>
                                </span>
                            </div>
                            <p class="mb-0 mt-3 text-muted">
                                <?php echo htmlspecialchars($preview !== '' ? mb_strimwidth($preview, 0, 220, '...') : 'Sem texto resumido na observacao geral.'); ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0 fw-bold" style="color: var(--primary-color, #007e66);">
                <i class="fas fa-layer-group me-2"></i>Eixos Pedagogicos
            </h5>
        </div>
        <div class="card-body p-4">
            <div id="pcaDisabledAlert" class="alert alert-warning d-none">
                O eixo <strong>Programa Comunicacao Ativa (PCA)</strong> esta desabilitado para a escola deste aluno e nao sera exigido neste registro.
            </div>

            <ul class="nav nav-tabs flex-wrap" id="axesTabs" role="tablist">
                <?php $first = true; foreach ($axisQuestions as $axisKey => $axisData): ?>
                <li class="nav-item <?php echo ($axisData['field'] === 'axis_pca') ? 'axis-pca-nav' : ''; ?>" role="presentation">
                    <button class="nav-link <?= $first ? 'active' : '' ?>" id="<?= $axisData['tab_btn'] ?>"
                            data-bs-toggle="tab" data-bs-target="#<?= $axisData['tab_id'] ?>" type="button" role="tab"
                            <?php echo ($axisData['field'] === 'axis_pca') ? 'data-pca-tab="1"' : ''; ?>>
                        <i class="<?= $axisData['icon'] ?> me-1"></i> <?= $axisData['name'] ?>
                    </button>
                </li>
                <?php $first = false; endforeach; ?>
            </ul>

            <div class="tab-content pt-4" id="axesTabContent">
                <?php $first = true; foreach ($axisQuestions as $axisKey => $axisData):
                    $savedAnswers = parseAxisAnswers($observation[$axisData['field']] ?? '', count($axisData['questions']));
                ?>
                <div class="tab-pane fade <?= $first ? 'show active' : '' ?>" id="<?= $axisData['tab_id'] ?>" role="tabpanel"
                     data-axis-field="<?= $axisData['field'] ?>"
                     <?php echo ($axisData['field'] === 'axis_pca') ? 'data-pca-pane="1"' : ''; ?>>
                    <h6 class="fw-bold mb-3" style="color: var(--primary-color, #007e66);"><?= $axisData['name'] ?></h6>
                    <?php foreach ($axisData['questions'] as $qIdx => $question): ?>
                    <div class="mb-4 p-3 bg-light rounded border-start border-3 border-primary">
                        <label class="form-label fw-bold mb-2">
                            <span class="badge bg-primary rounded-pill me-2"><?= $qIdx + 1 ?></span>
                            <?= htmlspecialchars($question) ?>
                            <?php if ($axisData['field'] !== 'axis_pca'): ?><span class="text-danger">*</span><?php endif; ?>
                        </label>
                        <textarea name="<?= $axisData['field'] ?>[<?= $qIdx ?>]"
                                  class="form-control axis-question-field"
                                  rows="2"
                                  data-axis="<?= $axisData['field'] ?>"
                                  data-axis-label="<?= htmlspecialchars($axisData['name'], ENT_QUOTES) ?>"
                                  data-question="<?= htmlspecialchars($question, ENT_QUOTES) ?>"
                                  placeholder="Sua resposta..."
                                  <?php echo $readonlyAttr; ?>
                                  <?php echo (!$isFinalized && $axisData['field'] !== 'axis_pca') ? 'required' : ''; ?>><?= htmlspecialchars($savedAnswers[$qIdx] ?? '') ?></textarea>
                        <?php if (!$isFinalized): ?>
                            <div class="invalid-feedback">Preencha esta pergunta antes de salvar.</div>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php $first = false; endforeach; ?>
            </div>
        </div>
    </div>

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
                    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
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
    const summaryEl = document.getElementById('validationSummary');
    const studentEl = document.getElementById('student_id');
    const pcaDisabledAlert = document.getElementById('pcaDisabledAlert');

    function getSelectedPcaEnabled() {
        if (!studentEl) return false;
        const selectedOption = studentEl.options[studentEl.selectedIndex];
        return !!(selectedOption && selectedOption.getAttribute('data-pca-enabled') === '1');
    }

    function syncPcaVisibility() {
        const pcaEnabled = getSelectedPcaEnabled();
        const pcaNav = document.querySelector('.axis-pca-nav');
        const pcaPane = document.querySelector('[data-pca-pane="1"]');
        const pcaTab = document.querySelector('[data-pca-tab="1"]');
        const pcaFields = document.querySelectorAll('textarea[data-axis="axis_pca"]');

        if (pcaDisabledAlert) {
            pcaDisabledAlert.classList.toggle('d-none', pcaEnabled);
        }

        if (pcaNav) pcaNav.classList.toggle('d-none', !pcaEnabled);
        if (pcaPane) pcaPane.classList.toggle('d-none', !pcaEnabled);
        if (pcaTab) pcaTab.disabled = !pcaEnabled;

        pcaFields.forEach(function(field) {
            field.disabled = !pcaEnabled || isFinalized;
            field.required = false;
            if (!pcaEnabled) {
                field.classList.remove('is-invalid');
            }
        });

        if (!pcaEnabled && pcaPane && pcaPane.classList.contains('show')) {
            const firstVisibleTab = document.querySelector('#axesTabs .nav-link:not([disabled])');
            if (firstVisibleTab) {
                new bootstrap.Tab(firstVisibleTab).show();
            }
        }
    }

    if (studentEl) {
        studentEl.addEventListener('change', syncPcaVisibility);
    }

    syncPcaVisibility();

    if (isFinalized) return;

    let hasUnsavedChanges = false;
    let saveTimeouts = {};

    function getAxisValues(axisField) {
        const textareas = document.querySelectorAll('textarea[data-axis="' + axisField + '"]');
        return Array.from(textareas)
            .filter(function(t) { return !t.disabled; })
            .map(function(t) { return t.value.trim(); });
    }

    function clearValidationState() {
        if (summaryEl) {
            summaryEl.classList.add('d-none');
            summaryEl.innerHTML = '';
        }

        document.querySelectorAll('.is-invalid').forEach(function(field) {
            field.classList.remove('is-invalid');
        });
    }

    function activateTabForField(field) {
        const pane = field.closest('.tab-pane');
        if (!pane) return;

        const tabButton = document.querySelector('[data-bs-target="#' + pane.id + '"]');
        if (tabButton) {
            new bootstrap.Tab(tabButton).show();
        }
    }

    function validateForm() {
        clearValidationState();
        syncPcaVisibility();

        const missing = [];

        if (studentEl && !studentEl.disabled && !studentEl.value) {
            studentEl.classList.add('is-invalid');
            missing.push({
                field: studentEl,
                label: 'Selecione um aluno.'
            });
        }

        document.querySelectorAll('.axis-question-field[required]').forEach(function(textarea) {
            if (textarea.disabled || textarea.value.trim()) return;

            textarea.classList.add('is-invalid');
            missing.push({
                field: textarea,
                label: textarea.getAttribute('data-axis-label') + ': ' + textarea.getAttribute('data-question')
            });
        });

        if (!missing.length) {
            return true;
        }

        if (missing[0].field !== studentEl) {
            activateTabForField(missing[0].field);
        }

        if (summaryEl) {
            const intro = missing.length === 1
                ? 'Falta responder 1 campo obrigatório:'
                : 'Faltam responder ' + missing.length + ' campos obrigatórios:';
            const items = missing.slice(0, 6).map(function(item) {
                return '<li>' + item.label + '</li>';
            }).join('');
            const extra = missing.length > 6
                ? '<p class="mb-0 mt-2 small">Há mais campos pendentes além dos listados acima.</p>'
                : '';

            summaryEl.innerHTML = '<strong>' + intro + '</strong><ul class="mb-0 mt-2">' + items + '</ul>' + extra;
            summaryEl.classList.remove('d-none');
            summaryEl.scrollIntoView({ behavior: 'smooth', block: 'start' });
        }

        setTimeout(function() {
            missing[0].field.focus();
        }, 150);

        return false;
    }

    function autoSaveAxis(axisField) {
        indicator.style.display = 'inline';
        indicatorText.textContent = 'Salvando...';

        fetch('/admin/observations/' + observationId + '/auto-save', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-Token': '<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>'
            },
            body: JSON.stringify({
                field: axisField,
                value: JSON.stringify(getAxisValues(axisField)),
                csrf_token: '<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>'
            })
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.success) {
                indicatorText.textContent = 'Salvo automaticamente as ' + data.saved_at;
                hasUnsavedChanges = false;
            } else {
                indicatorText.textContent = 'Erro ao salvar: ' + data.message;
            }
        })
        .catch(function() {
            indicatorText.textContent = 'Erro de conexao ao salvar';
        });
    }

    document.querySelectorAll('.axis-question-field').forEach(function(field) {
        field.addEventListener('input', function() {
            hasUnsavedChanges = true;
            field.classList.remove('is-invalid');
            const axis = this.getAttribute('data-axis');
            if (saveTimeouts[axis]) clearTimeout(saveTimeouts[axis]);
            saveTimeouts[axis] = setTimeout(function() { autoSaveAxis(axis); }, 2000);
        });

        field.addEventListener('blur', function() {
            if (this.disabled) return;
            const axis = this.getAttribute('data-axis');
            if (saveTimeouts[axis]) clearTimeout(saveTimeouts[axis]);
            autoSaveAxis(axis);
        });
    });

    window.addEventListener('beforeunload', function(e) {
        if (hasUnsavedChanges) {
            e.preventDefault();
            e.returnValue = '';
            return '';
        }
    });

    const form = document.getElementById('observationForm');
    if (form) {
        form.addEventListener('submit', function(e) {
            if (!validateForm()) {
                e.preventDefault();
                return false;
            }
            hasUnsavedChanges = false;
        });
    }
})();
</script>
