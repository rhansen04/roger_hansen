<?php
$isEdit = ($mode === 'edit');
$submission = $submission ?? null;
$template = $template ?? null;
?>

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/admin/planning" class="text-decoration-none">Planejamentos</a></li>
        <li class="breadcrumb-item active"><?= $isEdit ? 'Editar' : 'Novo' ?> Planejamento</li>
    </ol>
</nav>

<div class="mb-4">
    <a href="/admin/planning" class="text-decoration-none text-muted"><i class="fas fa-arrow-left me-2"></i> Voltar</a>
    <h2 class="text-primary fw-bold mt-3"><?= $isEdit ? 'EDITAR' : 'NOVO' ?> PLANEJAMENTO</h2>
</div>

<?php if (!$isEdit): ?>
<!-- Step 1: Select template, classroom, period -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white fw-bold"><i class="fas fa-cog me-2"></i> Configuração</div>
    <div class="card-body">
        <form action="/admin/planning" method="POST" id="planningForm">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Template <span class="text-danger">*</span></label>
                    <select name="template_id" class="form-select" required id="templateSelect">
                        <option value="">Selecione...</option>
                        <?php foreach ($templates as $t): ?>
                            <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['title']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Turma <span class="text-danger">*</span></label>
                    <select name="classroom_id" class="form-select" required>
                        <option value="">Selecione...</option>
                        <?php foreach ($classrooms as $c): ?>
                            <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?> (<?= $c['school_name'] ?? '' ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label fw-bold">Início <span class="text-danger">*</span></label>
                    <input type="date" name="period_start" class="form-control" required>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label fw-bold">Fim <span class="text-danger">*</span></label>
                    <input type="date" name="period_end" class="form-control" required>
                </div>
            </div>

            <div class="alert alert-info small">
                <i class="fas fa-info-circle me-2"></i>
                Após criar o planejamento, você poderá preencher as seções do formulário. Salve como rascunho ou envie quando estiver pronto.
            </div>

            <hr>
            <div class="d-flex justify-content-end">
                <a href="/admin/planning" class="btn btn-light me-2">Cancelar</a>
                <button type="submit" name="_action" value="save" class="btn btn-hansen">
                    <i class="fas fa-save me-2"></i> Criar Planejamento
                </button>
            </div>
        </form>
    </div>
</div>

<?php else: ?>
<!-- Edit mode: show template fields -->
<form action="/admin/planning/<?= $submission['id'] ?>/update" method="POST" id="planningForm">

    <!-- Info bar -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body py-2">
            <div class="row text-center">
                <div class="col-md-3"><small class="text-muted">Template:</small><br><strong><?= htmlspecialchars($submission['template_title'] ?? '') ?></strong></div>
                <div class="col-md-3"><small class="text-muted">Turma:</small><br><strong><?= htmlspecialchars($submission['classroom_name'] ?? '') ?></strong></div>
                <div class="col-md-3"><small class="text-muted">Quinzena:</small><br><strong><?= date('d/m', strtotime($submission['period_start'])) ?> - <?= date('d/m/Y', strtotime($submission['period_end'])) ?></strong></div>
                <div class="col-md-3">
                    <small class="text-muted">Status:</small><br>
                    <?php
                    $statusBadge = ['draft' => 'bg-warning text-dark', 'submitted' => 'bg-primary', 'registered' => 'bg-success'];
                    $statusLabel = ['draft' => 'Rascunho', 'submitted' => 'Enviado', 'registered' => 'Registrado'];
                    ?>
                    <span class="badge <?= $statusBadge[$submission['status']] ?? 'bg-secondary' ?>"><?= $statusLabel[$submission['status']] ?? $submission['status'] ?></span>
                </div>
            </div>
        </div>
    </div>

    <!-- Dynamic sections -->
    <?php if ($template && !empty($template['sections'])):
        foreach ($template['sections'] as $si => $section):
            // If submitted, only show registration sections for editing
            $isRegSection = !empty($section['is_registration']);
            $disabled = false;
            if ($submission['status'] === 'submitted' && !$isRegSection) {
                $disabled = true;
            }
    ?>
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white fw-bold d-flex justify-content-between">
            <span>
                <i class="fas fa-<?= $isRegSection ? 'clipboard-check' : 'list-alt' ?> me-2"></i>
                <?= ($si + 1) ?>. <?= htmlspecialchars($section['title']) ?>
            </span>
            <?php if ($isRegSection): ?>
                <span class="badge bg-warning text-dark">Registro Pós-Vivência</span>
            <?php endif; ?>
        </div>
        <div class="card-body">
            <?php if (!empty($section['description'])): ?>
                <p class="text-muted small mb-3"><?= htmlspecialchars($section['description']) ?></p>
            <?php endif; ?>

            <?php if (!empty($section['fields'])):
                foreach ($section['fields'] as $field):
                    $fieldName = "answers[{$field['id']}]";
                    $answer = $answers[$field['id']] ?? null;
                    $answerText = $answer['answer_text'] ?? '';
                    $answerJson = $answer['answer_json'] ?? null;
                    $selectedIndices = [];
                    if ($answerJson) {
                        $decoded = json_decode($answerJson, true);
                        $selectedIndices = $decoded['selected'] ?? [];
                    }
            ?>
                <div class="mb-3"<?php if (!empty($field['depends_on_field_id'])): ?> data-depends-on="<?= $field['depends_on_field_id'] ?>" data-depends-value="<?= htmlspecialchars($field['depends_on_value'] ?? '') ?>" style="display:none"<?php endif; ?>>
                    <input type="hidden" name="answer_sections[<?= $field['id'] ?>]" value="<?= $section['id'] ?>">
                    <label class="form-label fw-bold">
                        <?= htmlspecialchars($field['label']) ?>
                        <?php if ($field['is_required']): ?><span class="text-danger">*</span><?php endif; ?>
                    </label>
                    <?php if (!empty($field['description'])): ?>
                        <small class="form-text text-muted d-block mb-1"><?= htmlspecialchars($field['description']) ?></small>
                    <?php endif; ?>

                    <?php switch ($field['field_type']):
                        case 'text': ?>
                            <input type="text" name="<?= $fieldName ?>" class="form-control"
                                value="<?= htmlspecialchars($answerText) ?>"
                                <?= $field['is_required'] ? 'required' : '' ?> <?= $disabled ? 'disabled' : '' ?>>
                        <?php break;
                        case 'textarea': ?>
                            <textarea name="<?= $fieldName ?>" class="form-control" rows="4"
                                <?= $field['is_required'] ? 'required' : '' ?> <?= $disabled ? 'disabled' : '' ?>><?= htmlspecialchars($answerText) ?></textarea>
                        <?php break;
                        case 'date': ?>
                            <input type="date" name="<?= $fieldName ?>" class="form-control"
                                value="<?= htmlspecialchars($answerText) ?>"
                                <?= $field['is_required'] ? 'required' : '' ?> <?= $disabled ? 'disabled' : '' ?>>
                        <?php break;
                        case 'select':
                            $options = json_decode($field['options_json'] ?? '[]', true) ?: []; ?>
                            <select name="<?= $fieldName ?>" class="form-select"
                                <?= $field['is_required'] ? 'required' : '' ?> <?= $disabled ? 'disabled' : '' ?>>
                                <option value="">Selecione...</option>
                                <?php foreach ($options as $oi => $opt): ?>
                                    <option value="<?= htmlspecialchars($opt) ?>" <?= ($answerText === $opt) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($opt) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        <?php break;
                        case 'radio':
                            $options = json_decode($field['options_json'] ?? '[]', true) ?: [];
                            foreach ($options as $oi => $opt): ?>
                            <div class="form-check">
                                <input type="radio" name="<?= $fieldName ?>" value="<?= htmlspecialchars($opt) ?>"
                                    class="form-check-input" id="f<?= $field['id'] ?>_<?= $oi ?>"
                                    <?= ($answerText === $opt) ? 'checked' : '' ?> <?= $disabled ? 'disabled' : '' ?>>
                                <label class="form-check-label" for="f<?= $field['id'] ?>_<?= $oi ?>"><?= htmlspecialchars($opt) ?></label>
                            </div>
                        <?php endforeach;
                        break;
                        case 'checkbox':
                        case 'checklist_group':
                            $options = json_decode($field['options_json'] ?? '[]', true) ?: [];
                            foreach ($options as $oi => $opt): ?>
                            <div class="form-check">
                                <input type="checkbox" name="<?= $fieldName ?>[]" value="<?= $oi ?>"
                                    class="form-check-input" id="f<?= $field['id'] ?>_<?= $oi ?>"
                                    <?= in_array($oi, $selectedIndices) ? 'checked' : '' ?> <?= $disabled ? 'disabled' : '' ?>>
                                <label class="form-check-label" for="f<?= $field['id'] ?>_<?= $oi ?>"><?= htmlspecialchars($opt) ?></label>
                            </div>
                        <?php endforeach;
                        break;
                    endswitch; ?>
                </div>
            <?php endforeach;
            else: ?>
                <p class="text-muted small">Nenhum campo nesta seção.</p>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach;
    endif; ?>

    <!-- Action buttons -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body d-flex justify-content-between">
            <a href="/admin/planning" class="btn btn-light">Cancelar</a>
            <div>
                <?php if ($submission['status'] === 'draft'): ?>
                    <button type="submit" name="_action" value="save" class="btn btn-secondary me-2">
                        <i class="fas fa-save me-2"></i> Salvar Rascunho
                    </button>
                    <button type="submit" name="_action" value="submit" class="btn btn-hansen"
                        onclick="return confirm('Deseja enviar o planejamento? Após envio, as seções principais não poderão mais ser editadas.')">
                        <i class="fas fa-paper-plane me-2"></i> Enviar Planejamento
                    </button>
                <?php elseif ($submission['status'] === 'submitted'): ?>
                    <button type="submit" name="_action" value="save" class="btn btn-secondary me-2">
                        <i class="fas fa-save me-2"></i> Salvar Registro
                    </button>
                    <button type="submit" name="_action" value="register" class="btn btn-success"
                        onclick="return confirm('Deseja finalizar o registro pós-vivência?')">
                        <i class="fas fa-clipboard-check me-2"></i> Finalizar Registro
                    </button>
                <?php endif; ?>
            </div>
        </div>
    </div>
</form>

<script>
// Field dependency: show/hide fields based on controlling field value
document.querySelectorAll('[data-depends-on]').forEach(function(wrapper) {
    var controlId = wrapper.dataset.dependsOn;
    var requiredVal = wrapper.dataset.dependsValue;
    var inputs = document.querySelectorAll('[name="answers[' + controlId + ']"]');
    function check() {
        var val = '';
        inputs.forEach(function(i) {
            if (i.type === 'radio' && i.checked) val = i.value;
            else if (i.tagName === 'SELECT') val = i.value;
        });
        var show = (val === requiredVal);
        wrapper.style.display = show ? '' : 'none';
        wrapper.querySelectorAll('input,textarea,select').forEach(function(el) {
            el.disabled = !show;
        });
    }
    inputs.forEach(function(i) { i.addEventListener('change', check); });
    check();
});
</script>
<?php endif; ?>
