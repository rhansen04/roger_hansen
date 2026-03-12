<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/admin/planning" class="text-decoration-none">Planejamentos</a></li>
        <li class="breadcrumb-item active">Calendario</li>
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

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="/admin/planning" class="text-decoration-none text-muted mb-2 d-block"><i class="fas fa-arrow-left me-2"></i> Voltar para lista</a>
        <h2 class="text-primary fw-bold mb-0"><i class="fas fa-calendar-alt me-2"></i>CALENDARIO DE PLANEJAMENTOS</h2>
    </div>
    <a href="/admin/planning/create" class="btn btn-hansen text-white">
        <i class="fas fa-plus me-2"></i> Novo Planejamento
    </a>
</div>

<!-- Month/Year Selector + Filters -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form method="GET" action="/admin/planning/calendar" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-bold mb-1">Mes</label>
                <select name="month" class="form-select form-select-sm">
                    <?php
                    $monthNames = [
                        1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Marco', 4 => 'Abril',
                        5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
                        9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
                    ];
                    foreach ($monthNames as $num => $name): ?>
                        <option value="<?= $num ?>" <?= $month == $num ? 'selected' : '' ?>><?= $name ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold mb-1">Ano</label>
                <select name="year" class="form-select form-select-sm">
                    <?php for ($y = date('Y') - 1; $y <= date('Y') + 1; $y++): ?>
                        <option value="<?= $y ?>" <?= $year == $y ? 'selected' : '' ?>><?= $y ?></option>
                    <?php endfor; ?>
                </select>
            </div>
            <?php if ($userRole !== 'teacher'): ?>
            <div class="col-md-3">
                <label class="form-label small fw-bold mb-1">Turma</label>
                <select name="classroom_id" class="form-select form-select-sm">
                    <option value="">Todas</option>
                    <?php foreach ($classrooms as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= ($classroomId == $c['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($c['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <?php endif; ?>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-filter me-1"></i> Filtrar</button>
            </div>
        </form>
    </div>
</div>

<?php
$statusBadge = [
    'draft' => ['bg-warning text-dark', 'Rascunho'],
    'submitted' => ['bg-primary', 'Enviado'],
    'registered' => ['bg-success', 'Registrado'],
];

// Navigation
$prevMonth = $month - 1;
$prevYear = $year;
if ($prevMonth < 1) { $prevMonth = 12; $prevYear--; }
$nextMonth = $month + 1;
$nextYear = $year;
if ($nextMonth > 12) { $nextMonth = 1; $nextYear++; }
$classFilter = !empty($classroomId) ? "&classroom_id={$classroomId}" : '';
?>

<!-- Month navigation -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <a href="/admin/planning/calendar?year=<?= $prevYear ?>&month=<?= $prevMonth ?><?= $classFilter ?>" class="btn btn-outline-secondary btn-sm">
        <i class="fas fa-chevron-left me-1"></i> Anterior
    </a>
    <h4 class="mb-0 fw-bold text-primary"><?= $monthName ?> <?= $year ?></h4>
    <a href="/admin/planning/calendar?year=<?= $nextYear ?>&month=<?= $nextMonth ?><?= $classFilter ?>" class="btn btn-outline-secondary btn-sm">
        Proximo <i class="fas fa-chevron-right ms-1"></i>
    </a>
</div>

<!-- Weeks Table -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-bordered mb-0 align-middle">
                <thead class="bg-light">
                    <tr>
                        <th class="py-3 ps-3" style="width: 180px;">Semana</th>
                        <th class="py-3">Planejamentos</th>
                        <th class="py-3 text-center" style="width: 120px;">Acoes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($weeks)): ?>
                        <tr>
                            <td colspan="3" class="text-center py-5 text-muted">
                                <i class="fas fa-calendar fa-3x mb-3"></i><br>
                                Nenhuma semana neste periodo.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($weeks as $week): ?>
                        <tr class="<?= !$week['in_month'] ? 'table-light opacity-75' : '' ?>">
                            <td class="ps-3">
                                <i class="fas fa-calendar-week text-primary me-2"></i>
                                <strong><?= $week['start_fmt'] ?></strong> a <strong><?= $week['end_fmt'] ?></strong>
                            </td>
                            <td>
                                <?php if (empty($week['submissions'])): ?>
                                    <span class="text-muted fst-italic small">Nenhum planejamento</span>
                                <?php else: ?>
                                    <?php foreach ($week['submissions'] as $sub):
                                        $badge = $statusBadge[$sub['status']] ?? ['bg-secondary', $sub['status']];
                                    ?>
                                        <div class="d-inline-flex align-items-center me-3 mb-1">
                                            <a href="/admin/planning/<?= $sub['id'] ?>" class="text-decoration-none fw-bold me-2">
                                                #<?= $sub['id'] ?>
                                            </a>
                                            <span class="badge <?= $badge[0] ?> me-2"><?= $badge[1] ?></span>
                                            <small class="text-muted">
                                                <?= htmlspecialchars($sub['classroom_name'] ?? '') ?>
                                                <?php if ($userRole !== 'teacher'): ?>
                                                    &middot; <?= htmlspecialchars($sub['teacher_name'] ?? '') ?>
                                                <?php endif; ?>
                                            </small>
                                            <a href="/admin/planning/<?= $sub['id'] ?>/routine" class="btn btn-sm btn-outline-info ms-2" title="Rotina Semanal">
                                                <i class="fas fa-clock"></i>
                                            </a>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <a href="/admin/planning/create?period_start=<?= $week['start'] ?>&period_end=<?= $week['end'] ?>"
                                   class="btn btn-sm btn-outline-success" title="Criar planejamento para esta semana">
                                    <i class="fas fa-plus"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-3">
    <small class="text-muted">
        <i class="fas fa-info-circle me-1"></i>
        Clique no numero do planejamento para visualizar. Use o botao <i class="fas fa-clock"></i> para gerenciar a rotina diaria.
    </small>
</div>
