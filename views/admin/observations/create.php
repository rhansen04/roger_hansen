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

<form action="/admin/observations" method="POST" id="observationForm" novalidate>
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
                    <label class="form-label fw-bold">Aluno <span class="text-danger">*</span></label>
                    <?php if (!empty($selectedStudentId)):
                        $selectedName = '';
                        $selectedPcaEnabled = false;
                        foreach ($students as $s) {
                            if ($s['id'] == $selectedStudentId) {
                                $selectedName = $s['name'];
                                $selectedPcaEnabled = !empty($pcaEnabledByStudent[$s['id']]);
                                break;
                            }
                        }
                    ?>
                        <input type="hidden" name="student_id" id="student_id" value="<?php echo $selectedStudentId; ?>">
                        <input type="hidden" id="selected_student_pca_enabled" value="<?php echo $selectedPcaEnabled ? '1' : '0'; ?>">
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($selectedName); ?>" readonly>
                    <?php else: ?>
                        <select name="student_id" id="student_id" class="form-select" required>
                            <option value="">Selecione um aluno</option>
                            <?php foreach ($students as $student): ?>
                                <option value="<?php echo $student['id']; ?>" data-pca-enabled="<?php echo !empty($pcaEnabledByStudent[$student['id']]) ? '1' : '0'; ?>">
                                    <?php echo htmlspecialchars($student['name']); ?>
                                    <?php if (!empty($student['school_name'])): ?>
                                        - <?php echo htmlspecialchars($student['school_name']); ?>
                                    <?php endif; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Semestre <span class="text-danger">*</span></label>
                    <select name="semester" class="form-select" required>
                        <option value="1" <?php echo ($selectedSemester == 1) ? 'selected' : ''; ?>>1o Semestre</option>
                        <option value="2" <?php echo ($selectedSemester == 2) ? 'selected' : ''; ?>>2o Semestre</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Ano Letivo <span class="text-danger">*</span></label>
                    <select name="year" class="form-select" required>
                        <?php foreach ($years as $y): ?>
                            <option value="<?php echo $y; ?>" <?php echo ($y == $selectedYear) ? 'selected' : ''; ?>>
                                <?php echo $y; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
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
                Este aluno pode receber ate <?php echo (int) $maxObservationsPerSemester; ?> observacoes por semestre. Os registros anteriores aparecem aqui para apoiar o preenchimento cumulativo.
            </p>

            <?php if (empty($selectedStudentId)): ?>
                <div class="alert alert-light border mb-0">
                    Selecione aluno, semestre e ano para carregar o historico existente deste periodo.
                </div>
            <?php elseif (empty($observationHistory)): ?>
                <div class="alert alert-light border mb-0">
                    Nenhuma observacao cadastrada ainda para este aluno no <?php echo (int) $selectedSemester; ?>o semestre de <?php echo (int) $selectedYear; ?>.
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
                        ?>
                        <div class="border rounded-3 p-3 bg-light-subtle">
                            <div class="d-flex justify-content-between align-items-start gap-3">
                                <div>
                                    <div class="fw-semibold">Observacao #<?php echo (int) $history['id']; ?></div>
                                    <div class="small text-muted">
                                        Criada em <?php echo date('d/m/Y H:i', strtotime($history['created_at'])); ?>
                                        por <?php echo htmlspecialchars($history['teacher_name'] ?? 'Usuario'); ?>
                                    </div>
                                </div>
                                <span class="badge <?php echo (($history['status'] ?? '') === 'finalized') ? 'bg-success' : 'bg-warning text-dark'; ?>">
                                    <?php echo (($history['status'] ?? '') === 'finalized') ? 'Finalizada' : 'Em andamento'; ?>
                                </span>
                            </div>
                            <p class="mb-2 mt-3 text-muted">
                                <?php echo htmlspecialchars($preview !== '' ? mb_strimwidth($preview, 0, 220, '...') : 'Sem texto resumido na observacao geral.'); ?>
                            </p>
                            <a href="/admin/observations/<?php echo (int) $history['id']; ?>" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye me-1"></i> Ver registro
                            </a>
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
                O eixo <strong>Programa Comunicacao Ativa (PCA)</strong> esta desabilitado para a escola deste aluno e nao sera exigido nesta observacao.
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
                <?php $first = true; foreach ($axisQuestions as $axisKey => $axisData): ?>
                <div class="tab-pane fade <?= $first ? 'show active' : '' ?>" id="<?= $axisData['tab_id'] ?>" role="tabpanel"
                     data-axis-field="<?= $axisData['field'] ?>"
                     <?php echo ($axisData['field'] === 'axis_pca') ? 'data-pca-pane="1"' : ''; ?>>
                    <h6 class="fw-bold mb-3" style="color: var(--primary-color, #007e66);"><?= $axisData['name'] ?></h6>
                    <?php foreach ($axisData['questions'] as $qIdx => $question): ?>
                    <div class="mb-4 p-3 bg-light rounded border-start border-3 border-primary">
                        <label class="form-label fw-bold mb-2">
                            <span class="badge bg-primary rounded-pill me-2"><?= $qIdx + 1 ?></span>
                            <?= htmlspecialchars($question) ?>
                        </label>
                        <textarea name="<?= $axisData['field'] ?>[<?= $qIdx ?>]"
                                  class="form-control axis-question-field"
                                  rows="2"
                                  data-axis="<?= $axisData['field'] ?>"
                                  data-axis-label="<?= htmlspecialchars($axisData['name'], ENT_QUOTES) ?>"
                                  data-question="<?= htmlspecialchars($question, ENT_QUOTES) ?>"
                                  placeholder="Sua resposta..."
                                  ></textarea>
                        <div class="invalid-feedback">Preencha esta pergunta antes de salvar.</div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php $first = false; endforeach; ?>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center">
        <div></div>
        <div>
            <a href="/admin/observations" class="btn btn-light me-2">Cancelar</a>
            <button type="submit" class="btn btn-hansen px-5">
                <i class="fas fa-save me-2"></i> SALVAR OBSERVACAO
            </button>
        </div>
    </div>
</form>

<script>
(function() {
    var form = document.getElementById('observationForm');
    var studentEl = document.getElementById('student_id');
    var semesterEl = form.querySelector('select[name="semester"]');
    var yearEl = form.querySelector('select[name="year"]');
    var summaryEl = document.getElementById('validationSummary');
    var pcaDisabledAlert = document.getElementById('pcaDisabledAlert');
    var params = new URLSearchParams(window.location.search);
    var focus = params.get('focus');
    var focusMap = {
        'general': 'observation_general',
        'movement': 'axis_movement',
        'manual': 'axis_manual',
        'music': 'axis_music',
        'stories': 'axis_stories',
        'pca': 'axis_pca'
    };
    var focusedAxis = focusMap[focus] || null;

    function getSelectedPcaEnabled() {
        if (!studentEl) return false;

        if (studentEl.tagName === 'SELECT') {
            var selectedOption = studentEl.options[studentEl.selectedIndex];
            return !!(selectedOption && selectedOption.getAttribute('data-pca-enabled') === '1');
        }

        var hiddenPcaInput = document.getElementById('selected_student_pca_enabled');
        return !!(hiddenPcaInput && hiddenPcaInput.value === '1');
    }

    function isFieldRequired(fieldName) {
        return false;
    }

    function syncPcaVisibility() {
        var pcaEnabled = getSelectedPcaEnabled();
        var pcaNav = document.querySelector('.axis-pca-nav');
        var pcaPane = document.querySelector('[data-pca-pane="1"]');
        var pcaTab = document.querySelector('[data-pca-tab="1"]');
        var pcaFields = document.querySelectorAll('textarea[data-axis="axis_pca"]');

        if (pcaDisabledAlert) {
            pcaDisabledAlert.classList.toggle('d-none', pcaEnabled);
        }

        if (pcaNav) pcaNav.classList.toggle('d-none', !pcaEnabled);
        if (pcaPane) pcaPane.classList.toggle('d-none', !pcaEnabled);
        if (pcaTab) pcaTab.disabled = !pcaEnabled;

        pcaFields.forEach(function(field) {
            field.disabled = !pcaEnabled;
            field.required = false;

            if (!pcaEnabled) {
                field.value = '';
                field.classList.remove('is-invalid');
            }
        });

        if (!pcaEnabled && focusedAxis === 'axis_pca') {
            focusedAxis = null;
        }

        if (!pcaEnabled && pcaPane && pcaPane.classList.contains('show')) {
            var firstVisibleTab = document.querySelector('#axesTabs .nav-link:not([disabled])');
            if (firstVisibleTab) {
                new bootstrap.Tab(firstVisibleTab).show();
            }
        }
    }

    function syncRequiredFields() {
        document.querySelectorAll('.axis-question-field').forEach(function(textarea) {
            if (textarea.disabled) {
                textarea.required = false;
                return;
            }

            textarea.required = isFieldRequired(textarea.getAttribute('data-axis'));
        });
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
        var pane = field.closest('.tab-pane');
        if (!pane) return;

        var tabButton = document.querySelector('[data-bs-target="#' + pane.id + '"]');
        if (tabButton) {
            new bootstrap.Tab(tabButton).show();
        }
    }

    function reloadWithSelection() {
        if (!studentEl || !studentEl.value || studentEl.tagName !== 'SELECT') return;

        var url = new URL(window.location.href);
        url.searchParams.set('student_id', studentEl.value);
        if (semesterEl && semesterEl.value) url.searchParams.set('semester', semesterEl.value);
        if (yearEl && yearEl.value) url.searchParams.set('year', yearEl.value);
        window.location.href = url.toString();
    }

    function validateForm() {
        clearValidationState();
        syncPcaVisibility();
        syncRequiredFields();

        var missing = [];

        if (!studentEl || !studentEl.value) {
            missing.push({
                field: studentEl,
                label: 'Selecione um aluno.'
            });
        }

        document.querySelectorAll('.axis-question-field').forEach(function(textarea) {
            if (!textarea.required || textarea.disabled) return;
            if (textarea.value.trim()) return;

            textarea.classList.add('is-invalid');
            missing.push({
                field: textarea,
                label: textarea.getAttribute('data-axis-label') + ': ' + textarea.getAttribute('data-question')
            });
        });

        if (!missing.length) {
            return true;
        }

        if (missing[0].field === studentEl) {
            studentEl.classList.add('is-invalid');
        } else {
            activateTabForField(missing[0].field);
        }

        if (summaryEl) {
            var intro = missing.length === 1
                ? 'Falta responder 1 campo obrigatório:'
                : 'Faltam responder ' + missing.length + ' campos obrigatórios:';
            var items = missing.slice(0, 6).map(function(item) {
                return '<li>' + item.label + '</li>';
            }).join('');
            var extra = missing.length > 6
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

    syncPcaVisibility();
    syncRequiredFields();

    if (studentEl && studentEl.tagName === 'SELECT') {
        studentEl.addEventListener('change', reloadWithSelection);
    }
    if (semesterEl) semesterEl.addEventListener('change', reloadWithSelection);
    if (yearEl) yearEl.addEventListener('change', reloadWithSelection);

    document.querySelectorAll('[data-bs-toggle="tab"]').forEach(function(tabButton) {
        tabButton.addEventListener('shown.bs.tab', function(event) {
            if (!focusedAxis) return;
            var targetSelector = event.target.getAttribute('data-bs-target');
            var targetPane = targetSelector ? document.querySelector(targetSelector) : null;
            var axisField = targetPane ? targetPane.getAttribute('data-axis-field') : null;
            if (axisField) {
                focusedAxis = axisField;
                syncRequiredFields();
            }
        });
    });

    form.addEventListener('submit', function(e) {
        if (!validateForm()) {
            e.preventDefault();
            return false;
        }
    });

    document.querySelectorAll('select, textarea').forEach(function(field) {
        field.addEventListener('input', function() {
            if (field.value.trim()) {
                field.classList.remove('is-invalid');
            }
        });

        field.addEventListener('change', function() {
            if (!field.disabled && field.value.trim()) {
                field.classList.remove('is-invalid');
            }
        });
    });
})();

var studentSelect = document.querySelector('select#student_id');
if (studentSelect && typeof jQuery !== 'undefined' && jQuery.fn.select2) {
    jQuery(studentSelect).select2({
        placeholder: 'Digite para buscar um aluno...',
        allowClear: true
    });
}

(function() {
    var params = new URLSearchParams(window.location.search);
    var focus = params.get('focus');
    if (focus) {
        var tabMap = {
            'general': 'tab-general',
            'movement': 'tab-movement',
            'manual': 'tab-manual',
            'music': 'tab-music',
            'stories': 'tab-stories',
            'pca': 'tab-pca'
        };
        var tabId = tabMap[focus];
        if (tabId) {
            var tabEl = document.getElementById(tabId);
            if (tabEl && !tabEl.disabled) {
                var tab = new bootstrap.Tab(tabEl);
                tab.show();
            }
        }
    }
})();
</script>
