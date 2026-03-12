<h2 class="fw-bold mb-4" style="color:var(--primary-color)">
    <i class="fas fa-chart-line me-2"></i>Painel da Coordenacao
</h2>

<!-- ROW 1: Cards principais -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid var(--primary-color) !important;">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small text-uppercase fw-bold">Turmas</div>
                    <div class="fs-2 fw-bold" style="color:var(--primary-color)"><?= $stats['total_classrooms'] ?></div>
                    <div class="text-muted small">ativas</div>
                </div>
                <i class="fas fa-chalkboard fa-2x opacity-25"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #28a745 !important;">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small text-uppercase fw-bold">Criancas</div>
                    <div class="fs-2 fw-bold text-success"><?= $stats['total_students'] ?></div>
                    <div class="text-muted small">cadastradas</div>
                </div>
                <i class="fas fa-children fa-2x opacity-25"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #17a2b8 !important;">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small text-uppercase fw-bold">Professores</div>
                    <div class="fs-2 fw-bold text-info"><?= $stats['total_professors'] ?></div>
                    <div class="text-muted small">ativos</div>
                </div>
                <i class="fas fa-chalkboard-teacher fa-2x opacity-25"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #ffc107 !important;">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small text-uppercase fw-bold">Observacoes</div>
                    <div class="fs-2 fw-bold text-warning"><?= $stats['total_observations'] ?></div>
                    <div class="text-muted small">
                        <span class="text-success fw-bold">+<?= $stats['observations_this_month'] ?></span> este mes
                    </div>
                </div>
                <i class="fas fa-clipboard-check fa-2x opacity-25"></i>
            </div>
        </div>
    </div>
</div>

<!-- ROW 2: Cards secundarios -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <i class="fas fa-school fa-lg mb-1" style="color:var(--primary-color)"></i>
            <div class="fs-5 fw-bold"><?= $stats['total_schools'] ?></div>
            <div class="text-muted small">Escolas Ativas</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <i class="fas fa-book fa-lg mb-1 text-info"></i>
            <div class="fs-5 fw-bold"><?= count($courseReport) ?></div>
            <div class="text-muted small">Cursos Ativos</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <i class="fas fa-user-check fa-lg mb-1 text-success"></i>
<?php
$totalEnrolled = 0;
foreach ($courseReport as $cr) { $totalEnrolled += (int)$cr['enrolled_count']; }
?>
            <div class="fs-5 fw-bold"><?= $totalEnrolled ?></div>
            <div class="text-muted small">Total Matriculas</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <i class="fas fa-percentage fa-lg mb-1 text-warning"></i>
<?php
$avgAll = 0;
if (!empty($courseReport)) {
    $sum = 0;
    foreach ($courseReport as $cr) { $sum += (float)$cr['avg_progress']; }
    $avgAll = round($sum / count($courseReport), 1);
}
?>
            <div class="fs-5 fw-bold"><?= $avgAll ?>%</div>
            <div class="text-muted small">Progresso Medio</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- COLUNA ESQUERDA -->
    <div class="col-lg-8">

        <!-- Relatorio de Cursos -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold" style="color:var(--primary-color)">
                    <i class="fas fa-chart-bar me-2"></i>Relatorio de Cursos
                </h6>
                <a href="/admin/courses" class="btn btn-sm btn-outline-primary">Ver cursos</a>
            </div>
            <div class="card-body p-0">
<?php if (empty($courseReport)): ?>
                <p class="text-muted text-center py-4">Nenhum curso ativo.</p>
<?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Curso</th>
                                <th class="text-center">Matriculados</th>
                                <th>Progresso Medio</th>
                            </tr>
                        </thead>
                        <tbody>
<?php foreach ($courseReport as $cr): ?>
                            <tr>
                                <td class="fw-bold small"><?= htmlspecialchars($cr['title']) ?></td>
                                <td class="text-center">
                                    <span class="badge bg-primary rounded-pill"><?= $cr['enrolled_count'] ?></span>
                                </td>
                                <td style="min-width:140px">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1" style="height:8px">
                                            <div class="progress-bar" role="progressbar"
                                                 style="width:<?= $cr['avg_progress'] ?>%; background-color:var(--primary-color)"
                                                 aria-valuenow="<?= $cr['avg_progress'] ?>"
                                                 aria-valuemin="0" aria-valuemax="100"></div>
                                        </div>
                                        <small class="text-muted fw-bold" style="min-width:40px"><?= $cr['avg_progress'] ?>%</small>
                                    </div>
                                </td>
                            </tr>
<?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
<?php endif; ?>
            </div>
        </div>

        <!-- Observacoes Recentes -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold" style="color:var(--primary-color)">
                    <i class="fas fa-clipboard-list me-2"></i>Observacoes Recentes
                </h6>
                <a href="/admin/observations" class="btn btn-sm btn-outline-primary">Ver todas</a>
            </div>
            <div class="card-body p-0">
<?php if (empty($recentObservations)): ?>
                <p class="text-muted text-center py-4">Nenhuma observacao registrada.</p>
<?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Crianca</th>
                                <th>Titulo</th>
                                <th>Tipo</th>
                                <th>Professor</th>
                                <th>Data</th>
                            </tr>
                        </thead>
                        <tbody>
<?php foreach ($recentObservations as $obs): ?>
                            <tr>
                                <td class="small fw-bold"><?= htmlspecialchars($obs['student_name']) ?></td>
                                <td class="small"><?= htmlspecialchars($obs['title'] ?? '-') ?></td>
                                <td><span class="badge bg-secondary"><?= htmlspecialchars($obs['type'] ?? '-') ?></span></td>
                                <td class="small text-muted"><?= htmlspecialchars($obs['teacher_name']) ?></td>
                                <td class="small text-muted"><?= date('d/m/Y', strtotime($obs['created_at'])) ?></td>
                            </tr>
<?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
<?php endif; ?>
            </div>
        </div>

    </div>

    <!-- COLUNA DIREITA -->
    <div class="col-lg-4">

        <!-- Acoes Rapidas -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold" style="color:var(--primary-color)">
                    <i class="fas fa-bolt me-2"></i>Acoes Rapidas
                </h6>
            </div>
            <div class="card-body d-grid gap-2">
                <a href="/admin/classrooms" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-chalkboard me-2"></i>Gerenciar Turmas
                </a>
                <a href="/admin/students" class="btn btn-outline-success btn-sm">
                    <i class="fas fa-children me-2"></i>Criancas
                </a>
                <a href="/admin/observations" class="btn btn-outline-info btn-sm">
                    <i class="fas fa-clipboard-list me-2"></i>Observacoes
                </a>
                <a href="/admin/users" class="btn btn-outline-warning btn-sm">
                    <i class="fas fa-users me-2"></i>Professores
                </a>
                <a href="/admin/courses" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-book me-2"></i>Cursos
                </a>
                <a href="/admin/reports" class="btn btn-outline-dark btn-sm">
                    <i class="fas fa-chart-bar me-2"></i>Relatorios
                </a>
            </div>
        </div>

        <!-- Distribuicao por Faixa Etaria -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold" style="color:var(--primary-color)">
                    <i class="fas fa-info-circle me-2"></i>Visao Geral
                </h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted small">Turmas ativas</span>
                        <span class="fw-bold"><?= $stats['total_classrooms'] ?></span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted small">Criancas cadastradas</span>
                        <span class="fw-bold"><?= $stats['total_students'] ?></span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted small">Professores</span>
                        <span class="fw-bold"><?= $stats['total_professors'] ?></span>
                    </li>
                    <li class="d-flex justify-content-between py-2 border-bottom">
                        <span class="text-muted small">Escolas ativas</span>
                        <span class="fw-bold"><?= $stats['total_schools'] ?></span>
                    </li>
                    <li class="d-flex justify-content-between py-2">
                        <span class="text-muted small">Observacoes este mes</span>
                        <span class="fw-bold text-success"><?= $stats['observations_this_month'] ?></span>
                    </li>
                </ul>
            </div>
        </div>

    </div>
</div>
