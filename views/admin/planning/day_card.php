<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/admin/planning" class="text-decoration-none">Planejamentos</a></li>
        <li class="breadcrumb-item"><a href="/admin/planning/<?= $submission['id'] ?>/days" class="text-decoration-none">Dias</a></li>
        <li class="breadcrumb-item active"><?= $dateFmt ?></li>
    </ol>
</nav>

<?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i><?= $_SESSION['success_message'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['success_message']); ?>
<?php endif; ?>

<div class="mb-4">
    <a href="/admin/planning/<?= $submission['id'] ?>/days" class="text-decoration-none text-muted">
        <i class="fas fa-arrow-left me-2"></i> Voltar para dias
    </a>
    <h2 class="text-primary fw-bold mt-3">
        <i class="fas fa-calendar-day me-2"></i><?= $dayName ?> - <?= $dateFmt ?>
    </h2>
    <p class="text-muted mb-0">
        <?= htmlspecialchars($submission['template_title'] ?? '') ?> &middot;
        <?= htmlspecialchars($submission['classroom_name'] ?? '') ?>
    </p>
</div>

<form action="/admin/planning/<?= $submission['id'] ?>/day/<?= $date ?>" method="POST" id="dayForm">

    <?php if (!empty($sections)):
        foreach ($sections as $si => $section):
            $sectionTitle = $section['title'];
            $sectionTitle = str_ireplace('Eixo de Vivências', 'Eixo de Atividades', $sectionTitle);
            $sectionTitle = str_ireplace('Eixo da Vivência', 'Eixo de Atividades', $sectionTitle);
            $sectionTitle = str_ireplace('Eixo de Vivencias', 'Eixo de Atividades', $sectionTitle);
            $sectionTitle = str_ireplace('Eixo da Vivencia', 'Eixo de Atividades', $sectionTitle);
    ?>
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white fw-bold">
            <i class="fas fa-list-alt me-2"></i>
            <?= ($si + 1) ?>. <?= htmlspecialchars($sectionTitle) ?>
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

                    $fieldLabel = $field['label'];
                    $fieldLabel = str_ireplace('Eixo de Vivências', 'Eixo de Atividades', $fieldLabel);
                    $fieldLabel = str_ireplace('Eixo da Vivência', 'Eixo de Atividades', $fieldLabel);
                    $fieldLabel = str_ireplace('Eixo de Vivencias', 'Eixo de Atividades', $fieldLabel);
                    $fieldLabel = str_ireplace('Eixo da Vivencia', 'Eixo de Atividades', $fieldLabel);
            ?>
                <div class="mb-3">
                    <input type="hidden" name="answer_sections[<?= $field['id'] ?>]" value="<?= $section['id'] ?>">
                    <label class="form-label fw-bold">
                        <?= htmlspecialchars($fieldLabel) ?>
                        <?php if ($field['is_required']): ?><span class="text-danger">*</span><?php endif; ?>
                    </label>
                    <?php if (!empty($field['description'])): ?>
                        <small class="form-text text-muted d-block mb-1"><?= htmlspecialchars($field['description']) ?></small>
                    <?php endif; ?>

                    <?php
                    $isEixoRadio = ($field['field_type'] === 'radio' &&
                        (stripos($fieldLabel, 'eixo') !== false || stripos($field['label'], 'eixo') !== false));

                    switch ($field['field_type']):
                        case 'text': ?>
                            <input type="text" name="<?= $fieldName ?>" class="form-control"
                                value="<?= htmlspecialchars($answerText) ?>"
                                <?= $field['is_required'] ? 'required' : '' ?>>
                        <?php break;
                        case 'textarea': ?>
                            <textarea name="<?= $fieldName ?>" class="form-control" rows="4"
                                <?= $field['is_required'] ? 'required' : '' ?>><?= htmlspecialchars($answerText) ?></textarea>
                        <?php break;
                        case 'date': ?>
                            <input type="date" name="<?= $fieldName ?>" class="form-control"
                                value="<?= htmlspecialchars($answerText) ?>"
                                <?= $field['is_required'] ? 'required' : '' ?>>
                        <?php break;
                        case 'select':
                            $options = json_decode($field['options_json'] ?? '[]', true) ?: []; ?>
                            <select name="<?= $fieldName ?>" class="form-select"
                                <?= $field['is_required'] ? 'required' : '' ?>>
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
                            if ($isEixoRadio): ?>
                            <div class="btn-group flex-wrap" role="group">
                                <?php foreach ($options as $oi => $opt):
                                    $optLabel = str_ireplace('Vivência', 'Atividade', $opt);
                                    $optLabel = str_ireplace('Vivencia', 'Atividade', $optLabel);
                                    $isChecked = ($answerText === $opt);
                                ?>
                                <input type="radio" class="btn-check" name="<?= $fieldName ?>"
                                    value="<?= htmlspecialchars($opt) ?>" id="f<?= $field['id'] ?>_<?= $oi ?>"
                                    autocomplete="off" <?= $isChecked ? 'checked' : '' ?>>
                                <label class="btn btn-outline-primary" for="f<?= $field['id'] ?>_<?= $oi ?>">
                                    <?= htmlspecialchars($optLabel) ?>
                                </label>
                                <?php endforeach; ?>
                            </div>
                            <?php else:
                                foreach ($options as $oi => $opt): ?>
                            <div class="form-check">
                                <input type="radio" name="<?= $fieldName ?>" value="<?= htmlspecialchars($opt) ?>"
                                    class="form-check-input" id="f<?= $field['id'] ?>_<?= $oi ?>"
                                    <?= ($answerText === $opt) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="f<?= $field['id'] ?>_<?= $oi ?>"><?= htmlspecialchars($opt) ?></label>
                            </div>
                            <?php endforeach;
                            endif;
                        break;
                        case 'checkbox':
                        case 'checklist_group':
                            $options = json_decode($field['options_json'] ?? '[]', true) ?: [];
                            foreach ($options as $oi => $opt): ?>
                            <div class="form-check">
                                <input type="checkbox" name="<?= $fieldName ?>[]" value="<?= $oi ?>"
                                    class="form-check-input" id="f<?= $field['id'] ?>_<?= $oi ?>"
                                    <?= in_array($oi, $selectedIndices) ? 'checked' : '' ?>>
                                <label class="form-check-label" for="f<?= $field['id'] ?>_<?= $oi ?>"><?= htmlspecialchars($opt) ?></label>
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
            Nenhuma secao disponivel para preenchimento diario. Verifique o template de planejamento.
        </div>
    <?php endif; ?>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body d-flex justify-content-between">
            <a href="/admin/planning/<?= $submission['id'] ?>/days" class="btn btn-light">
                <i class="fas fa-arrow-left me-1"></i> Voltar
            </a>
            <button type="submit" class="btn btn-hansen">
                <i class="fas fa-save me-2"></i> Salvar Dia
            </button>
        </div>
    </div>
</form>
