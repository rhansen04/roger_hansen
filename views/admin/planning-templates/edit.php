<?php $isNew = empty($template); ?>

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/admin/planning-templates" class="text-decoration-none">Templates</a></li>
        <li class="breadcrumb-item active"><?= $isNew ? 'Novo' : 'Editar' ?> Template</li>
    </ol>
</nav>

<?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-2"></i><?= $_SESSION['success_message'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['success_message']); ?>
<?php endif; ?>
<?php if (isset($_SESSION['error_message'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-triangle me-2"></i><?= $_SESSION['error_message'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['error_message']); ?>
<?php endif; ?>

<div class="mb-4">
    <a href="/admin/planning-templates" class="text-decoration-none text-muted"><i class="fas fa-arrow-left me-2"></i> Voltar</a>
    <h2 class="text-primary fw-bold mt-3"><?= $isNew ? 'NOVO' : 'EDITAR' ?> TEMPLATE DE PLANEJAMENTO</h2>
</div>

<!-- Template Info -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white fw-bold"><i class="fas fa-file-alt me-2"></i> Dados do Template</div>
    <div class="card-body">
        <form action="<?= $isNew ? '/admin/planning-templates' : "/admin/planning-templates/{$template['id']}/update" ?>" method="POST">
            <div class="row">
                <div class="col-md-5 mb-3">
                    <label class="form-label fw-bold">Título <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" required
                        value="<?= htmlspecialchars($template['title'] ?? '') ?>"
                        placeholder="Ex: Planejamento Quinzenal PFI">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Faixa Etária</label>
                    <select name="age_group" class="form-select">
                        <option value="all" <?= (($template['age_group'] ?? '') === 'all') ? 'selected' : '' ?>>Todas</option>
                        <option value="0-3" <?= (($template['age_group'] ?? '') === '0-3') ? 'selected' : '' ?>>0-3 anos (PFI)</option>
                        <option value="3-6" <?= (($template['age_group'] ?? '') === '3-6') ? 'selected' : '' ?>>3-6 anos (PFII)</option>
                    </select>
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label fw-bold">Ordem</label>
                    <input type="number" name="sort_order" class="form-control" value="<?= $template['sort_order'] ?? 0 ?>">
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label fw-bold">Ativo</label>
                    <select name="is_active" class="form-select">
                        <option value="1" <?= (($template['is_active'] ?? 1) == 1) ? 'selected' : '' ?>>Sim</option>
                        <option value="0" <?= (($template['is_active'] ?? 1) == 0) ? 'selected' : '' ?>>Não</option>
                    </select>
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label fw-bold">Descrição</label>
                    <textarea name="description" class="form-control" rows="2"><?= htmlspecialchars($template['description'] ?? '') ?></textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-hansen"><i class="fas fa-save me-2"></i> <?= $isNew ? 'Criar Template' : 'Salvar Alterações' ?></button>
        </form>
    </div>
</div>

<?php if (!$isNew): ?>
<!-- Sections & Fields -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
        <span><i class="fas fa-layer-group me-2"></i> Seções e Campos</span>
        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#addSectionForm">
            <i class="fas fa-plus me-1"></i> Adicionar Seção
        </button>
    </div>
    <div class="card-body">

        <!-- Add Section Form (collapsed) -->
        <div class="collapse mb-4" id="addSectionForm">
            <div class="card card-body bg-light">
                <form action="/admin/planning-templates/<?= $template['id'] ?>/sections" method="POST">
                    <div class="row g-2 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold">Título da Seção <span class="text-danger">*</span></label>
                            <input type="text" name="title" class="form-control form-control-sm" required placeholder="Ex: Identificação">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label small fw-bold">Descrição</label>
                            <input type="text" name="description" class="form-control form-control-sm" placeholder="Instrução para o professor">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label small fw-bold">Ordem</label>
                            <input type="number" name="sort_order" class="form-control form-control-sm" value="<?= count($sections) ?>">
                        </div>
                        <div class="col-md-1">
                            <div class="form-check mt-4">
                                <input type="checkbox" name="is_registration" value="1" class="form-check-input" id="isReg">
                                <label class="form-check-label small" for="isReg">Registro</label>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-sm btn-primary w-100"><i class="fas fa-plus me-1"></i> Adicionar</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sections Accordion -->
        <?php if (empty($sections)): ?>
            <p class="text-muted text-center py-3">Nenhuma seção cadastrada. Adicione seções para montar o formulário.</p>
        <?php else: ?>
            <div class="accordion" id="sectionsAccordion">
                <?php foreach ($sections as $si => $sec): ?>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sec<?= $sec['id'] ?>">
                            <span class="me-2 fw-bold"><?= $si + 1 ?>.</span>
                            <?= htmlspecialchars($sec['title']) ?>
                            <?php if ($sec['is_registration']): ?>
                                <span class="badge bg-warning text-dark ms-2">Registro Pós-Vivência</span>
                            <?php endif; ?>
                            <span class="badge bg-secondary ms-2"><?= count($sec['fields'] ?? []) ?> campos</span>
                        </button>
                    </h2>
                    <div id="sec<?= $sec['id'] ?>" class="accordion-collapse collapse" data-bs-parent="#sectionsAccordion">
                        <div class="accordion-body">

                            <!-- Section actions -->
                            <div class="d-flex justify-content-end mb-3">
                                <form action="/admin/planning-templates/sections/<?= $sec['id'] ?>/delete" method="POST" class="d-inline"
                                    onsubmit="return confirm('Excluir seção e todos os campos?')">
                                    <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash me-1"></i> Excluir Seção</button>
                                </form>
                            </div>

                            <!-- Existing fields - Visual Preview -->
                            <?php if (!empty($sec['fields'])):
                                $typeColors = [
                                    'text' => '#0d6efd', 'textarea' => '#0d6efd',
                                    'date' => '#fd7e14', 'select' => '#6f42c1',
                                    'radio' => '#20c997', 'checkbox' => '#e83e8c', 'checklist' => '#e83e8c'
                                ];
                                foreach ($sec['fields'] as $field):
                                    $color = $typeColors[$field['field_type']] ?? '#6c757d';
                                    $opts = $field['options_json'] ? json_decode($field['options_json'], true) : [];
                                    if (!is_array($opts)) $opts = [];
                            ?>
                                <div class="mb-3 field-preview-card" style="border-left: 3px solid <?= $color ?>; padding: 12px 16px; background: #e9ecef; border-radius: 0 8px 8px 0;">
                                    <div class="d-flex justify-content-between align-items-start mb-1">
                                        <div>
                                            <span class="text-muted small fw-bold me-2">#<?= $field['sort_order'] ?></span>
                                            <span class="badge" style="background-color: <?= $color ?>"><?= $field['field_type'] ?></span>
                                            <span class="fw-semibold ms-2"><?= htmlspecialchars($field['label']) ?></span>
                                            <?php if ($field['is_required']): ?><span class="text-danger">*</span><?php endif; ?>
                                            <?php if (!empty($field['depends_on_field_id'])): ?>
                                                <span class="badge bg-info text-dark ms-1" title="Campo condicional">
                                                    <i class="fas fa-link me-1"></i>Depende de #<?= $field['depends_on_field_id'] ?> = <?= htmlspecialchars($field['depends_on_value'] ?? '') ?>
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <form action="/admin/planning-templates/fields/<?= $field['id'] ?>/delete" method="POST" class="d-inline"
                                            onsubmit="return confirm('Excluir campo?')">
                                            <button class="btn btn-sm btn-outline-danger"><i class="fas fa-times"></i></button>
                                        </form>
                                    </div>
                                    <?php if (!empty($field['description'])): ?>
                                        <p class="text-muted small mb-2"><?= htmlspecialchars($field['description']) ?></p>
                                    <?php endif; ?>

                                    <div class="mt-2">
                                    <?php switch ($field['field_type']):
                                        case 'text': ?>
                                            <input type="text" class="form-control form-control-sm" disabled placeholder="<?= htmlspecialchars($field['label']) ?>">
                                        <?php break; case 'textarea': ?>
                                            <textarea class="form-control form-control-sm" rows="3" disabled placeholder="<?= htmlspecialchars($field['label']) ?>"></textarea>
                                        <?php break; case 'date': ?>
                                            <input type="date" class="form-control form-control-sm" disabled style="max-width: 220px;">
                                        <?php break; case 'select': ?>
                                            <select class="form-select form-select-sm" disabled style="max-width: 300px;">
                                                <option value="">Selecione...</option>
                                                <?php foreach ($opts as $opt): ?>
                                                    <option><?= htmlspecialchars(is_array($opt) ? ($opt['label'] ?? $opt['value'] ?? '') : $opt) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        <?php break; case 'radio': ?>
                                            <?php foreach ($opts as $opt):
                                                $label = htmlspecialchars(is_array($opt) ? ($opt['label'] ?? $opt['value'] ?? '') : $opt); ?>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="radio" disabled>
                                                    <label class="form-check-label"><?= $label ?></label>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php break; case 'checkbox': case 'checklist': ?>
                                            <?php foreach ($opts as $opt):
                                                $label = htmlspecialchars(is_array($opt) ? ($opt['label'] ?? $opt['value'] ?? '') : $opt); ?>
                                                <div class="form-check">
                                                    <input class="form-check-input" type="checkbox" disabled>
                                                    <label class="form-check-label"><?= $label ?></label>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php break; default: ?>
                                            <input type="text" class="form-control form-control-sm" disabled placeholder="<?= htmlspecialchars($field['label']) ?>">
                                        <?php break; endswitch; ?>
                                    </div>
                                </div>
                            <?php endforeach; endif; ?>

                            <!-- Add field form -->
                            <div class="card card-body bg-light">
                                <h6 class="fw-bold mb-2"><i class="fas fa-plus-circle me-1"></i> Adicionar Campo</h6>
                                <form action="/admin/planning-templates/sections/<?= $sec['id'] ?>/fields" method="POST">
                                    <div class="row g-2 align-items-end">
                                        <div class="col-md-3">
                                            <label class="form-label small">Rótulo <span class="text-danger">*</span></label>
                                            <input type="text" name="label" class="form-control form-control-sm" required>
                                        </div>
                                        <div class="col-md-2">
                                            <label class="form-label small">Tipo</label>
                                            <select name="field_type" class="form-select form-select-sm" onchange="toggleOptions(this)">
                                                <option value="text">Texto</option>
                                                <option value="textarea">Texto Longo</option>
                                                <option value="date">Data</option>
                                                <option value="select">Seleção</option>
                                                <option value="radio">Radio</option>
                                                <option value="checkbox">Checkbox</option>
                                                <option value="checklist_group">Checklist</option>
                                            </select>
                                        </div>
                                        <div class="col-md-1">
                                            <label class="form-label small">Ordem</label>
                                            <input type="number" name="sort_order" class="form-control form-control-sm" value="<?= count($sec['fields'] ?? []) ?>">
                                        </div>
                                        <div class="col-md-1">
                                            <div class="form-check mt-4">
                                                <input type="checkbox" name="is_required" value="1" class="form-check-input">
                                                <label class="form-check-label small">Req.</label>
                                            </div>
                                        </div>
                                        <div class="col-md-3 options-field" style="display:none">
                                            <label class="form-label small">Opções (1 por linha)</label>
                                            <textarea name="options_text" class="form-control form-control-sm" rows="3" placeholder="Opção 1&#10;Opção 2&#10;Opção 3"></textarea>
                                        </div>
                                        <div class="col-md-2">
                                            <button type="submit" class="btn btn-sm btn-primary w-100"><i class="fas fa-plus me-1"></i> Adicionar</button>
                                        </div>
                                    </div>
                                    <div class="row g-2 mt-1">
                                        <div class="col-md-4">
                                            <label class="form-label small text-muted"><i class="fas fa-link me-1"></i>Depende do campo (opcional)</label>
                                            <select name="depends_on_field_id" class="form-select form-select-sm">
                                                <option value="">Nenhuma dependência</option>
                                                <?php if (!empty($template['sections'])):
                                                    foreach ($template['sections'] as $depSec):
                                                        foreach ($depSec['fields'] ?? [] as $depField): ?>
                                                            <option value="<?= $depField['id'] ?>">#<?= $depField['id'] ?> — <?= htmlspecialchars($depField['label']) ?> (<?= $depField['field_type'] ?>)</option>
                                                        <?php endforeach;
                                                    endforeach;
                                                endif; ?>
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <label class="form-label small text-muted">Valor que habilita</label>
                                            <input type="text" name="depends_on_value" class="form-control form-control-sm" placeholder="Ex: Manual">
                                        </div>
                                    </div>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<style>
.field-preview-card,
.field-preview-card *:not(.badge):not(.btn):not(.btn *) {
    color: #212529 !important;
}
.field-preview-card .text-danger { color: #dc3545 !important; }
.field-preview-card .text-muted  { color: #6c757d !important; }
</style>

<script>
function toggleOptions(el) {
    const row = el.closest('.row');
    const optField = row.querySelector('.options-field');
    const needsOpts = ['select','radio','checkbox','checklist_group'].includes(el.value);
    optField.style.display = needsOpts ? '' : 'none';
}
</script>
