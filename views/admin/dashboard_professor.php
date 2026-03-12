<h2 class="fw-bold mb-4" style="color:var(--primary-color)">
    <i class="fas fa-chalkboard-teacher me-2"></i>Meu Painel
</h2>

<!-- ROW 1: Cards principais -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid var(--primary-color) !important;">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small text-uppercase fw-bold">Minhas Turmas</div>
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
                    <div class="text-muted small text-uppercase fw-bold">Meus Alunos</div>
                    <div class="fs-2 fw-bold text-success"><?= $stats['total_students'] ?></div>
                    <div class="text-muted small">nas minhas turmas</div>
                </div>
                <i class="fas fa-children fa-2x opacity-25"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #17a2b8 !important;">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small text-uppercase fw-bold">Meus Cursos</div>
                    <div class="fs-2 fw-bold text-info"><?= $stats['total_courses'] ?></div>
                    <div class="text-muted small">em andamento</div>
                </div>
                <i class="fas fa-book-open fa-2x opacity-25"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #ffc107 !important;">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small text-uppercase fw-bold">Relat. Pendentes</div>
                    <div class="fs-2 fw-bold text-warning"><?= $stats['pending_reports'] ?></div>
                    <div class="text-muted small">descritivos</div>
                </div>
                <i class="fas fa-file-alt fa-2x opacity-25"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- COLUNA ESQUERDA -->
    <div class="col-lg-8">

        <!-- Meus Cursos -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold" style="color:var(--primary-color)">
                    <i class="fas fa-graduation-cap me-2"></i>Meus Cursos
                </h6>
                <a href="/admin/courses" class="btn btn-sm btn-outline-primary">Ver todos</a>
            </div>
            <div class="card-body p-0">
<?php if (empty($myCourses)): ?>
                <p class="text-muted text-center py-4">Nenhum curso matriculado ainda.</p>
<?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Curso</th>
                                <th>Data Matricula</th>
                                <th>Progresso</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
<?php foreach ($myCourses as $course): ?>
                            <tr>
                                <td class="fw-bold small"><?= htmlspecialchars($course['title']) ?></td>
                                <td class="small text-muted"><?= date('d/m/Y', strtotime($course['enrollment_date'])) ?></td>
                                <td style="min-width:120px">
                                    <div class="progress" style="height:8px">
                                        <div class="progress-bar" role="progressbar"
                                             style="width:<?= $course['overall_progress_percentage'] ?>%; background-color:var(--primary-color)"
                                             aria-valuenow="<?= $course['overall_progress_percentage'] ?>"
                                             aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small class="text-muted"><?= round($course['overall_progress_percentage']) ?>%</small>
                                </td>
                                <td>
<?php if ($course['is_course_completed']): ?>
                                    <span class="badge bg-success"><i class="fas fa-check me-1"></i>Concluido</span>
<?php else: ?>
                                    <span class="badge bg-info">Em andamento</span>
<?php endif; ?>
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
                    <i class="fas fa-clipboard-list me-2"></i>Minhas Observacoes Recentes
                </h6>
                <a href="/admin/observations" class="btn btn-sm btn-outline-primary">Ver todas</a>
            </div>
            <div class="card-body p-0">
<?php if (empty($recentObservations)): ?>
                <p class="text-muted text-center py-4">Nenhuma observacao registrada ainda.</p>
<?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Crianca</th>
                                <th>Titulo</th>
                                <th>Tipo</th>
                                <th>Data</th>
                            </tr>
                        </thead>
                        <tbody>
<?php foreach ($recentObservations as $obs): ?>
                            <tr>
                                <td class="small fw-bold"><?= htmlspecialchars($obs['student_name']) ?></td>
                                <td class="small"><?= htmlspecialchars($obs['title'] ?? '-') ?></td>
                                <td><span class="badge bg-secondary"><?= htmlspecialchars($obs['type'] ?? '-') ?></span></td>
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

        <!-- Minhas Turmas -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold" style="color:var(--primary-color)">
                    <i class="fas fa-chalkboard me-2"></i>Minhas Turmas
                </h6>
                <a href="/admin/classrooms" class="btn btn-sm btn-outline-primary">Ver todas</a>
            </div>
            <div class="card-body">
<?php if (empty($myClassrooms)): ?>
                <p class="text-muted text-center">Nenhuma turma atribuida.</p>
<?php else: ?>
                <div class="list-group list-group-flush">
<?php foreach ($myClassrooms as $cl): ?>
                    <div class="list-group-item px-0">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <div class="fw-bold small"><?= htmlspecialchars($cl['name']) ?></div>
                                <div class="text-muted" style="font-size:0.78rem">
                                    <i class="fas fa-school me-1"></i><?= htmlspecialchars($cl['school_name']) ?>
                                </div>
                            </div>
                            <div class="text-end">
                                <span class="badge" style="background-color:var(--primary-color)">
                                    <?= $cl['age_group'] === '0-3' ? '0-3 anos' : '3-6 anos' ?>
                                </span>
                                <div class="text-muted" style="font-size:0.72rem; margin-top:2px">
<?php
$periods = ['morning' => 'Manha', 'afternoon' => 'Tarde', 'full' => 'Integral'];
echo $periods[$cl['period']] ?? $cl['period'];
?>
                                    &middot; <?= $cl['school_year'] ?>
                                </div>
                            </div>
                        </div>
                    </div>
<?php endforeach; ?>
                </div>
<?php endif; ?>
            </div>
        </div>

        <!-- Acoes Rapidas -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold" style="color:var(--primary-color)">
                    <i class="fas fa-bolt me-2"></i>Acoes Rapidas
                </h6>
            </div>
            <div class="card-body d-grid gap-2">
                <a href="/admin/observations/create" class="btn btn-outline-primary btn-sm">
                    <i class="fas fa-clipboard-list me-2"></i>Nova Observacao
                </a>
                <a href="/admin/classrooms" class="btn btn-outline-success btn-sm">
                    <i class="fas fa-chalkboard me-2"></i>Minhas Turmas
                </a>
                <a href="/admin/courses" class="btn btn-outline-info btn-sm">
                    <i class="fas fa-book-open me-2"></i>Meus Cursos
                </a>
                <a href="/admin/students" class="btn btn-outline-warning btn-sm">
                    <i class="fas fa-children me-2"></i>Criancas
                </a>
            </div>
        </div>

        <!-- Resumo Observacoes -->
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold" style="color:var(--primary-color)">
                    <i class="fas fa-chart-pie me-2"></i>Minhas Observacoes
                </h6>
            </div>
            <div class="card-body text-center">
                <div class="fs-3 fw-bold" style="color:var(--primary-color)"><?= $stats['total_observations'] ?></div>
                <div class="text-muted small">observacoes registradas</div>
            </div>
        </div>

    </div>
</div>
