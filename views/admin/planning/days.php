<?php
$statusBadge = ['draft' => 'bg-warning text-dark', 'submitted' => 'bg-primary', 'registered' => 'bg-success'];
$statusLabel = ['draft' => 'Rascunho', 'submitted' => 'Enviado', 'registered' => 'Registrado'];
?>

<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/admin/planning" class="text-decoration-none">Planejamentos</a></li>
        <li class="breadcrumb-item active">Dias do Planejamento</li>
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

<!-- Acoes rapidas -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="/admin/planning" class="text-decoration-none text-muted"><i class="fas fa-arrow-left me-2"></i> Voltar</a>
        <h2 class="text-primary fw-bold mt-2 mb-0">
            <i class="fas fa-calendar-day me-2"></i>DIAS DO PLANEJAMENTO
        </h2>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <a href="/admin/planning/<?= $submission['id'] ?>" class="btn btn-outline-primary btn-sm">
            <i class="fas fa-eye me-1"></i> Ver Completo
        </a>
        <a href="/admin/planning/<?= $submission['id'] ?>/routine" class="btn btn-outline-secondary btn-sm">
            <i class="fas fa-clock me-1"></i> Rotina Semanal
        </a>
        <?php if ($submission['status'] === 'submitted'): ?>
        <a href="/admin/planning/<?= $submission['id'] ?>/registration" class="btn btn-success btn-sm">
            <i class="fas fa-clipboard-check me-1"></i> Registro Pos-Vivencia
        </a>
        <?php elseif ($submission['status'] === 'registered'): ?>
        <a href="/admin/planning/<?= $submission['id'] ?>/registration" class="btn btn-outline-success btn-sm">
            <i class="fas fa-check-double me-1"></i> Ver Registro
        </a>
        <?php endif; ?>
    </div>
</div>

<!-- Grid de dias -->
<div class="row g-3">
    <?php
    $currentWeek = null;
    foreach ($weekdays as $date):
        $dt = new DateTime($date);
        $dow = (int)$dt->format('N');
        $weekNum = $dt->format('W');
        $isToday = ($date === date('Y-m-d'));
        $entry = $entriesByDate[$date] ?? null;
        $dayStatus = $entry['status'] ?? 'empty';

        // Separador de semana
        if ($currentWeek !== null && $currentWeek !== $weekNum):
    ?>
    <div class="col-12"><hr class="my-2"></div>
    <?php
        endif;
        $currentWeek = $weekNum;

        // Cores e icones por status
        $cardClass = 'border-warning bg-warning bg-opacity-10';
        $iconClass = 'fas fa-hourglass-half text-warning';
        $statusText = 'Pendente';
        $statusBadgeClass = 'bg-warning text-dark';
        if ($dayStatus === 'draft') {
            $cardClass = 'border-warning bg-warning bg-opacity-10';
            $iconClass = 'fas fa-edit text-warning';
            $statusText = 'Rascunho';
            $statusBadgeClass = 'bg-warning text-dark';
        } elseif ($dayStatus === 'filled') {
            $cardClass = 'border-success bg-success bg-opacity-10';
            $iconClass = 'fas fa-check-circle text-success';
            $statusText = 'Concluído';
            $statusBadgeClass = 'bg-success';
        }
        if ($isToday) $cardClass .= ' shadow';
    ?>
    <div class="col-6 col-md-3 col-lg-2">
        <a href="/admin/planning/<?= $submission['id'] ?>/day/<?= $date ?>" class="card border-2 <?= $cardClass ?> text-decoration-none <?= ($submission['status'] === 'registered') ? 'pe-none opacity-75' : '' ?>">
            <div class="card-body text-center py-2 px-2">
                <?php if ($isToday): ?>
                    <span class="badge bg-primary mb-1" style="font-size:0.65em">HOJE</span><br>
                <?php endif; ?>
                <div class="text-muted fw-bold" style="font-size:0.7em"><?= $dayNames[$dow] ?? '' ?></div>
                <div class="fw-bold text-dark" style="font-size:1.4em;line-height:1.2"><?= $dt->format('d') ?></div>
                <div class="text-muted" style="font-size:0.65em"><?= $dt->format('m/Y') ?></div>
                <div class="mt-1">
                    <span class="badge <?= $statusBadgeClass ?>" style="font-size:0.6em">
                        <i class="<?= $iconClass ?> me-1" style="font-size:0.8em"></i><?= $statusText ?>
                    </span>
                </div>
            </div>
        </a>
    </div>
    <?php endforeach; ?>
</div>

<!-- Botoes de acao -->
<div class="card border-0 shadow-sm mt-4">
    <div class="card-body d-flex justify-content-between align-items-center">
        <a href="/admin/planning" class="btn btn-light">
            <i class="fas fa-arrow-left me-1"></i> Voltar para Listagem
        </a>
        <div>
            <?php if ($submission['status'] === 'draft'): ?>
                <form method="POST" action="/admin/planning/<?= $submission['id'] ?>/finalize" class="d-inline">
                    <button type="submit" class="btn btn-hansen"
                        onclick="return confirm('Deseja finalizar e enviar o planejamento para revisao? Apos o envio, os dias nao poderao mais ser editados.')">
                        <i class="fas fa-paper-plane me-2"></i> Finalizar Planejamento
                    </button>
                </form>
            <?php elseif ($submission['status'] === 'submitted'): ?>
                <a href="/admin/planning/<?= $submission['id'] ?>/registration" class="btn btn-success">
                    <i class="fas fa-clipboard-check me-2"></i> Registro Pos-Vivencia
                </a>
            <?php elseif ($submission['status'] === 'registered'): ?>
                <span class="badge bg-success fs-6 py-2 px-3">
                    <i class="fas fa-check-double me-1"></i> Planejamento Concluido
                </span>
            <?php endif; ?>
        </div>
    </div>
</div>
