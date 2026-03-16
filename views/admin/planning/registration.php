<?php
$statusBadge = ['draft' => 'bg-warning text-dark', 'submitted' => 'bg-primary', 'registered' => 'bg-success'];
$statusLabel = ['draft' => 'Rascunho', 'submitted' => 'Enviado', 'registered' => 'Registrado'];
?>

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/admin/planning" class="text-decoration-none">Planejamentos</a></li>
        <li class="breadcrumb-item"><a href="/admin/planning/<?= $submission['id'] ?>/days" class="text-decoration-none">Dias</a></li>
        <li class="breadcrumb-item active">Registro Pos-Vivencia</li>
    </ol>
</nav>

<?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i><?= $_SESSION['success_message'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['success_message']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error_message'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i><?= $_SESSION['error_message'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['error_message']); ?>
<?php endif; ?>

<!-- Info bar -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3">
        <div class="row text-center align-items-center">
            <div class="col-md-3">
                <small class="text-muted">Template:</small><br>
                <strong><?= htmlspecialchars($submission['template_title'] ?? '') ?></strong>
            </div>
            <div class="col-md-3">
                <small class="text-muted">Turma:</small><br>
                <strong><?= htmlspecialchars($submission['classroom_name'] ?? '') ?></strong>
            </div>
            <div class="col-md-3">
                <small class="text-muted">Periodo:</small><br>
                <strong><?= date('d/m', strtotime($submission['period_start'])) ?> - <?= date('d/m/Y', strtotime($submission['period_end'])) ?></strong>
            </div>
            <div class="col-md-3">
                <small class="text-muted">Status:</small><br>
                <span class="badge <?= $statusBadge[$submission['status']] ?? 'bg-secondary' ?>">
                    <?= $statusLabel[$submission['status']] ?? $submission['status'] ?>
                </span>
            </div>
        </div>
    </div>
</div>

<div class="mb-4">
    <a href="/admin/planning/<?= $submission['id'] ?>/days" class="text-decoration-none text-muted">
        <i class="fas fa-arrow-left me-2"></i> Voltar para dias
    </a>
    <h2 class="text-primary fw-bold mt-3">
        <i class="fas fa-clipboard-check me-2"></i>REGISTRO POS-VIVENCIA
    </h2>
    <p class="text-muted">Preencha os campos de registro apos a execucao do planejamento.</p>
</div>

<form action="/admin/planning/<?= $submission['id'] ?>/registration" method="POST" id="registrationForm">

    <?php if (!empty($sections)):
        foreach ($sections as $si => $section):
    ?>
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white fw-bold d-flex justify-content-between">
            <span>
                <i class="fas fa-clipboard-check me-2"></i>
                <?= ($si + 1) ?>. <?= htmlspecialchars($section['title']) ?>
            </span>
            <span class="badge bg-warning text-dark">Registro Pos-Vivencia</span>
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
                    $disabled = ($submission['status'] === 'registered');
            ?>
                <div class="mb-3">
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
                                    class="form-check-input" id="rf<?= $field['id'] ?>_<?= $oi ?>"
                                    <?= ($answerText === $opt) ? 'checked' : '' ?> <?= $disabled ? 'disabled' : '' ?>>
                                <label class="form-check-label" for="rf<?= $field['id'] ?>_<?= $oi ?>"><?= htmlspecialchars($opt) ?></label>
                            </div>
                        <?php endforeach;
                        break;
                        case 'checkbox':
                        case 'checklist_group':
                            $options = json_decode($field['options_json'] ?? '[]', true) ?: [];
                            foreach ($options as $oi => $opt): ?>
                            <div class="form-check">
                                <input type="checkbox" name="<?= $fieldName ?>[]" value="<?= $oi ?>"
                                    class="form-check-input" id="rf<?= $field['id'] ?>_<?= $oi ?>"
                                    <?= in_array($oi, $selectedIndices) ? 'checked' : '' ?> <?= $disabled ? 'disabled' : '' ?>>
                                <label class="form-check-label" for="rf<?= $field['id'] ?>_<?= $oi ?>"><?= htmlspecialchars($opt) ?></label>
                            </div>
                        <?php endforeach;
                        break;
                    endswitch; ?>
                </div>
            <?php endforeach;
            else: ?>
                <p class="text-muted small">Nenhum campo nesta secao.</p>
            <?php endif; ?>
        </div>
    </div>
    <?php endforeach;
    else: ?>
        <div class="alert alert-info">
            <i class="fas fa-info-circle me-2"></i>
            Nenhuma secao de registro encontrada no template. Verifique com o administrador.
        </div>
    <?php endif; ?>

    <!-- Action buttons -->
    <?php if ($submission['status'] !== 'registered'): ?>
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body d-flex justify-content-between">
            <a href="/admin/planning/<?= $submission['id'] ?>/days" class="btn btn-light">
                <i class="fas fa-arrow-left me-1"></i> Voltar
            </a>
            <div>
                <button type="submit" name="_action" value="save" class="btn btn-secondary me-2">
                    <i class="fas fa-save me-2"></i> Salvar Rascunho
                </button>
                <button type="submit" name="_action" value="register" class="btn btn-success"
                    onclick="return confirm('Deseja finalizar o registro pos-vivencia? Apos finalizar, os campos nao poderao mais ser editados.')">
                    <i class="fas fa-clipboard-check me-2"></i> Finalizar Registro
                </button>
            </div>
        </div>
    </div>
    <?php else: ?>
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body d-flex justify-content-between align-items-center">
            <a href="/admin/planning/<?= $submission['id'] ?>/days" class="btn btn-light">
                <i class="fas fa-arrow-left me-1"></i> Voltar
            </a>
            <span class="badge bg-success fs-6 py-2 px-3">
                <i class="fas fa-check-double me-1"></i> Registro Concluido
            </span>
        </div>
    </div>
    <?php endif; ?>
</form>
