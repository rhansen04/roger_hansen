<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/admin/planning" class="text-decoration-none">Planejamentos</a></li>
        <li class="breadcrumb-item active">Visualizar #<?= $submission['id'] ?></li>
    </ol>
</nav>

<?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-2"></i><?= $_SESSION['success_message'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['success_message']); ?>
<?php endif; ?>

<?php
$statusBadge = ['draft' => 'bg-warning text-dark', 'submitted' => 'bg-primary', 'registered' => 'bg-success'];
$statusLabel = ['draft' => 'Rascunho', 'submitted' => 'Enviado', 'registered' => 'Registrado'];
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="/admin/planning" class="text-decoration-none text-muted mb-2 d-block"><i class="fas fa-arrow-left me-2"></i> Voltar</a>
        <h2 class="text-primary fw-bold mb-0">PLANEJAMENTO #<?= $submission['id'] ?></h2>
    </div>
    <div>
        <?php if ($submission['status'] !== 'registered'): ?>
            <a href="/admin/planning/<?= $submission['id'] ?>/edit" class="btn btn-hansen">
                <i class="fas fa-edit me-2"></i> Editar
            </a>
        <?php endif; ?>
        <button onclick="window.print()" class="btn btn-outline-secondary ms-2">
            <i class="fas fa-print me-2"></i> Imprimir
        </button>
    </div>
</div>

<!-- Info card -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="row">
            <div class="col-md-3">
                <small class="text-muted">Template</small>
                <p class="fw-bold mb-0"><?= htmlspecialchars($submission['template_title'] ?? '-') ?></p>
            </div>
            <div class="col-md-2">
                <small class="text-muted">Professor</small>
                <p class="fw-bold mb-0"><?= htmlspecialchars($submission['teacher_name'] ?? '-') ?></p>
            </div>
            <div class="col-md-2">
                <small class="text-muted">Turma</small>
                <p class="fw-bold mb-0"><?= htmlspecialchars($submission['classroom_name'] ?? '-') ?></p>
            </div>
            <div class="col-md-2">
                <small class="text-muted">Quinzena</small>
                <p class="fw-bold mb-0"><?= date('d/m', strtotime($submission['period_start'])) ?> - <?= date('d/m/Y', strtotime($submission['period_end'])) ?></p>
            </div>
            <div class="col-md-1">
                <small class="text-muted">Status</small><br>
                <span class="badge <?= $statusBadge[$submission['status']] ?? 'bg-secondary' ?>"><?= $statusLabel[$submission['status']] ?? $submission['status'] ?></span>
            </div>
            <div class="col-md-2">
                <small class="text-muted">Criado em</small>
                <p class="fw-bold mb-0"><?= date('d/m/Y H:i', strtotime($submission['created_at'])) ?></p>
            </div>
        </div>
    </div>
</div>

<!-- Sections with answers -->
<?php if ($template && !empty($template['sections'])):
    foreach ($template['sections'] as $si => $section):
        $isRegSection = !empty($section['is_registration']);
?>
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white fw-bold">
        <i class="fas fa-<?= $isRegSection ? 'clipboard-check' : 'list-alt' ?> me-2"></i>
        <?= ($si + 1) ?>. <?= htmlspecialchars($section['title']) ?>
        <?php if ($isRegSection): ?>
            <span class="badge bg-warning text-dark ms-2">Registro Pós-Vivência</span>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <?php if (!empty($section['fields'])):
            foreach ($section['fields'] as $field):
                $answer = $answers[$field['id']] ?? null;
                $answerText = $answer['answer_text'] ?? '';
                $answerJson = $answer['answer_json'] ?? null;
        ?>
            <div class="mb-3 pb-2 border-bottom">
                <label class="fw-bold text-muted small"><?= htmlspecialchars($field['label']) ?></label>

                <?php if (in_array($field['field_type'], ['checkbox', 'checklist_group']) && $answerJson):
                    $decoded = json_decode($answerJson, true);
                    $selectedIndices = $decoded['selected'] ?? [];
                    $options = json_decode($field['options_json'] ?? '[]', true) ?: [];
                ?>
                    <div class="mt-1">
                        <?php foreach ($options as $oi => $opt): ?>
                            <div>
                                <?php if (in_array($oi, $selectedIndices)): ?>
                                    <i class="fas fa-check-square text-success me-1"></i>
                                <?php else: ?>
                                    <i class="far fa-square text-muted me-1"></i>
                                <?php endif; ?>
                                <?= htmlspecialchars($opt) ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php elseif (!empty($answerText)): ?>
                    <p class="mb-0 mt-1"><?= nl2br(htmlspecialchars($answerText)) ?></p>
                <?php else: ?>
                    <p class="mb-0 mt-1 text-muted fst-italic">Não preenchido</p>
                <?php endif; ?>
            </div>
        <?php endforeach;
        else: ?>
            <p class="text-muted small">Nenhum campo nesta seção.</p>
        <?php endif; ?>
    </div>
</div>
<?php endforeach;
endif; ?>

<style>
@media print {
    #sidebar, .navbar, .breadcrumb, .btn { display: none !important; }
    #content { margin-left: 0 !important; }
    .card { break-inside: avoid; }
}
</style>
