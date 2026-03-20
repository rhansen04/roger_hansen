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
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">
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
                    <?php if (!empty($selectedStudentId)):
                        // Encontrar nome do aluno selecionado
                        $selectedName = '';
                        foreach ($students as $s) {
                            if ($s['id'] == $selectedStudentId) { $selectedName = $s['name']; break; }
                        }
                    ?>
                        <input type="hidden" name="student_id" id="student_id" value="<?php echo $selectedStudentId; ?>">
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($selectedName); ?>" readonly>
                    <?php else: ?>
                        <select name="student_id" id="student_id" class="form-select" required>
                            <option value="">Selecione um aluno</option>
                            <?php foreach ($students as $student): ?>
                                <option value="<?php echo $student['id']; ?>">
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

    <?php include __DIR__ . '/_questions.php'; ?>

    <!-- Eixos Pedagogicos com Tabs -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0 fw-bold" style="color: var(--primary-color, #007e66);">
                <i class="fas fa-layer-group me-2"></i>Eixos Pedagogicos
            </h5>
        </div>
        <div class="card-body p-4">
            <ul class="nav nav-tabs flex-wrap" id="axesTabs" role="tablist">
                <?php $first = true; foreach ($axisQuestions as $axisKey => $axisData): ?>
                <li class="nav-item" role="presentation">
                    <button class="nav-link <?= $first ? 'active' : '' ?>" id="<?= $axisData['tab_btn'] ?>"
                            data-bs-toggle="tab" data-bs-target="#<?= $axisData['tab_id'] ?>" type="button" role="tab">
                        <i class="<?= $axisData['icon'] ?> me-1"></i> <?= $axisData['name'] ?>
                    </button>
                </li>
                <?php $first = false; endforeach; ?>
            </ul>

            <div class="tab-content pt-4" id="axesTabContent">
                <?php $first = true; foreach ($axisQuestions as $axisKey => $axisData): ?>
                <div class="tab-pane fade <?= $first ? 'show active' : '' ?>" id="<?= $axisData['tab_id'] ?>" role="tabpanel"
                     data-axis-field="<?= $axisData['field'] ?>">
                    <h6 class="fw-bold mb-3" style="color: var(--primary-color, #007e66);"><?= $axisData['name'] ?></h6>
                    <?php foreach ($axisData['questions'] as $qIdx => $question): ?>
                    <div class="mb-4 p-3 bg-light rounded border-start border-3 border-primary">
                        <label class="form-label fw-bold mb-2">
                            <span class="badge bg-primary rounded-pill me-2"><?= $qIdx + 1 ?></span>
                            <?= htmlspecialchars($question) ?> <span class="text-danger">*</span>
                        </label>
                        <textarea name="<?= $axisData['field'] ?>[<?= $qIdx ?>]"
                                  class="form-control axis-question-field"
                                  rows="2"
                                  data-axis="<?= $axisData['field'] ?>"
                                  placeholder="Sua resposta..."
                                  required></textarea>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php $first = false; endforeach; ?>
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
(function() {
    var form = document.getElementById('observationForm');
    var studentEl = document.getElementById('student_id');
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

    function syncRequiredFields() {
        var textareas = document.querySelectorAll('.axis-question-field');
        textareas.forEach(function(textarea) {
            if (!focusedAxis) {
                textarea.required = true;
                return;
            }
            textarea.required = (textarea.getAttribute('data-axis') === focusedAxis);
        });
    }

    syncRequiredFields();

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
        if (!studentEl || !studentEl.value) {
            e.preventDefault();
            alert('Por favor, selecione um aluno.');
            if (studentEl.tagName === 'SELECT') studentEl.focus();
            return false;
        }
        syncRequiredFields();
    });
})();

// Select2 para busca de alunos (se disponivel e nao for hidden)
var studentSelect = document.querySelector('select#student_id');
if (studentSelect && typeof jQuery !== 'undefined' && jQuery.fn.select2) {
    jQuery(studentSelect).select2({
        placeholder: 'Digite para buscar um aluno...',
        allowClear: true
    });
}

// Ativar tab via parametro ?focus= da URL
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
            if (tabEl) {
                var tab = new bootstrap.Tab(tabEl);
                tab.show();
            }
        }
    }
})();
</script>
