<h2 class="fw-bold mb-4" style="color:var(--primary-color)"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</h2>

<!-- ROW 1: Cards principais -->
<div class="row g-3 mb-3">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid var(--primary-color) !important;">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small text-uppercase fw-bold">Alunos</div>
                    <div class="fs-2 fw-bold" style="color:var(--primary-color)"><?= number_format($stats['total_students']) ?></div>
                    <div class="text-muted small"><span class="text-success fw-bold">+<?= $stats['new_students_month'] ?></span> este mês</div>
                </div>
                <i class="fas fa-user-graduate fa-2x opacity-25"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #28a745 !important;">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small text-uppercase fw-bold">Matrículas Ativas</div>
                    <div class="fs-2 fw-bold text-success"><?= number_format($stats['total_enrollments']) ?></div>
                    <div class="text-muted small"><?= $stats['completed_courses'] ?> concluídos</div>
                </div>
                <i class="fas fa-user-check fa-2x opacity-25"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #17a2b8 !important;">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small text-uppercase fw-bold">Cursos Ativos</div>
                    <div class="fs-2 fw-bold text-info"><?= number_format($stats['total_courses']) ?></div>
                    <div class="text-muted small">Progresso médio: <?= $stats['avg_progress'] ?>%</div>
                </div>
                <i class="fas fa-book fa-2x opacity-25"></i>
            </div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid #ffc107 !important;">
            <div class="card-body d-flex justify-content-between align-items-center">
                <div>
                    <div class="text-muted small text-uppercase fw-bold">Contatos</div>
                    <div class="fs-2 fw-bold text-warning"><?= number_format($stats['total_contacts']) ?></div>
<?php if ($stats['unread_contacts'] > 0): ?>
                        <div class="small"><span class="badge bg-danger"><?= $stats['unread_contacts'] ?> não lidos</span></div>
<?php else: ?>
                        <div class="text-muted small">Todos lidos</div>
<?php endif; ?>
                </div>
                <i class="fas fa-envelope fa-2x opacity-25"></i>
            </div>
        </div>
    </div>
</div>

<!-- ROW 2: Cards secundários -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <i class="fas fa-school fa-lg mb-1" style="color:var(--primary-color)"></i>
            <div class="fs-5 fw-bold"><?= $stats['active_schools'] ?> <small class="text-muted fs-6">/ <?= $stats['active_schools'] + $stats['inactive_schools'] ?></small></div>
            <div class="text-muted small">Escolas Ativas</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <i class="fas fa-clock fa-lg mb-1 text-info"></i>
            <div class="fs-5 fw-bold"><?= $stats['total_watch_hours'] ?>h</div>
            <div class="text-muted small">Total Assistido</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <i class="fas fa-question-circle fa-lg mb-1 text-warning"></i>
            <div class="fs-5 fw-bold"><?= $stats['total_quiz_attempts'] ?></div>
            <div class="text-muted small">Tentativas de Quiz</div>
        </div>
    </div>
    <div class="col-6 col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <i class="fas fa-clipboard-check fa-lg mb-1 text-success"></i>
            <div class="fs-5 fw-bold"><?= $stats['total_observations'] ?></div>
            <div class="text-muted small">Observações</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- COLUNA ESQUERDA -->
    <div class="col-lg-8">

        <!-- Matrículas Recentes -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold" style="color:var(--primary-color)"><i class="fas fa-user-plus me-2"></i>Matrículas Recentes</h6>
                <a href="/admin/enrollments" class="btn btn-sm btn-outline-primary">Ver todas</a>
            </div>
            <div class="card-body p-0">
<?php if (empty($recentEnrollments)): ?>
                    <p class="text-muted text-center py-4">Nenhuma matrícula registrada.</p>
<?php else: ?>
                <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>Aluno</th><th>Curso</th><th>Data</th><th>Progresso</th><th>Status</th></tr>
                    </thead>
                    <tbody>
<?php foreach ($recentEnrollments as $e): ?>
                        <tr>
                            <td class="fw-bold small"><?= htmlspecialchars($e['student_name']) ?></td>
                            <td class="small text-muted"><?= htmlspecialchars($e['course_title']) ?></td>
                            <td class="small text-muted"><?= date('d/m/Y', strtotime($e['enrollment_date'])) ?></td>
                            <td style="min-width:80px">
                                <div class="progress" style="height:6px">
                                    <div class="progress-bar bg-success" style="width:<?= $e['overall_progress_percentage'] ?>%"></div>
                                </div>
                                <small class="text-muted"><?= round($e['overall_progress_percentage']) ?>%</small>
                            </td>
                            <td>
<?php
$badge = match($e['status']) {
'active'    => 'bg-success',
'pending'   => 'bg-warning text-dark',
'completed' => 'bg-info',
default     => 'bg-secondary'
};
?>
                                <span class="badge <?= $badge ?>"><?= ucfirst($e['status']) ?></span>
                            </td>
                        </tr>
<?php endforeach; ?>
                    </tbody>
                </table>
                </div>
<?php endif; ?>
            </div>
        </div>

        <!-- Cursos Mais Populares -->
<?php if (!empty($popularCourses)): ?>
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold" style="color:var(--primary-color)"><i class="fas fa-fire me-2"></i>Cursos Mais Populares</h6>
                <a href="/admin/courses" class="btn btn-sm btn-outline-primary">Ver todos</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>Curso</th><th>Matrículas</th><th>Concluídos</th><th>Progresso Médio</th></tr>
                    </thead>
                    <tbody>
<?php foreach ($popularCourses as $pc): ?>
                        <tr>
                            <td class="small fw-bold"><?= htmlspecialchars($pc['title']) ?>
<?php if ($pc['is_free']): ?><span class="badge bg-success ms-1">Grátis</span><?php endif; ?>
                            </td>
                            <td class="small"><?= $pc['total_enrollments'] ?></td>
                            <td class="small"><?= $pc['completions'] ?></td>
                            <td style="min-width:100px"><div class="progress" style="height:6px"><div class="progress-bar" style="width:<?= $pc['avg_progress'] ?>%; background-color:var(--primary-color)"></div></div><small class="text-muted"><?= $pc['avg_progress'] ?>%</small></td>
                        </tr>
<?php endforeach; ?>
                    </tbody></table></div></div></div>
<?php endif; ?>
<?php if (!empty($lowProgressStudents)): ?>
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold text-danger"><i class="fas fa-exclamation-triangle me-2"></i>Baixo Progresso</h6>
            </div><div class="card-body p-0"><table class="table table-hover mb-0"><tbody>
<?php foreach ($lowProgressStudents as $lp): ?>
<tr><td><?= htmlspecialchars($lp['student_name']) ?></td>
<td><?= htmlspecialchars($lp['course_title']) ?></td>
<td><span class="badge bg-danger"><?= round($lp['overall_progress_percentage']) ?>%</span></td>
<td><?= date('d/m/Y', strtotime($lp['enrollment_date'])) ?></td></tr>
<?php endforeach; ?>
</tbody></table></div></div>
<?php endif; ?>
<?php if (!empty($contacts)): ?>
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold" style="color:var(--primary-color)"><i class="fas fa-envelope me-2"></i>Contatos Recentes
<?php if ($stats['unread_contacts'] > 0): ?><span class="badge bg-danger ms-1"><?= $stats['unread_contacts'] ?></span><?php endif; ?>
                </h6>
                <a href="/admin/contacts" class="btn btn-sm btn-outline-primary">Ver todos</a>
            </div><div class="card-body p-0"><div class="table-responsive"><table class="table table-hover mb-0"><tbody>
<?php foreach ($contacts as $c): ?>
<tr><td class="small"><?= htmlspecialchars($c['contact_name']) ?></td>
<td class="small text-muted"><?= htmlspecialchars($c['email']) ?></td>
<td class="small text-muted"><?= htmlspecialchars($c['school_name'] ?? '-') ?></td>
<td class="small text-muted"><?= date('d/m/Y', strtotime($c['created_at'])) ?></td></tr>
<?php endforeach; ?>
</tbody></table></div></div></div>
<?php endif; ?>
<?php if (!empty($recentObservations)): ?>
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold" style="color:var(--primary-color)"><i class="fas fa-clipboard-list me-2"></i>Observações Recentes</h6>
                <a href="/admin/observations" class="btn btn-sm btn-outline-primary">Ver todas</a>
            </div><div class="card-body p-0"><table class="table table-hover mb-0"><tbody>
<?php foreach ($recentObservations as $obs): ?>
<tr><td class="small fw-bold"><?= htmlspecialchars($obs['student_name']) ?></td>
<td class="small"><?= htmlspecialchars($obs['title'] ?? '-') ?></td>
<td><span class="badge bg-secondary"><?= htmlspecialchars($obs['type'] ?? '-') ?></span></td>
<td class="small text-muted"><?= htmlspecialchars($obs['teacher_name']) ?></td>
<td class="small text-muted"><?= date('d/m/Y', strtotime($obs['created_at'])) ?></td></tr>
<?php endforeach; ?>
</tbody></table></div></div>
<?php endif; ?>

    </div>
    <!-- COLUNA DIREITA -->
    <div class="col-lg-4">
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold" style="color:var(--primary-color)"><i class="fas fa-bolt me-2"></i>Ações Rápidas</h6>
            </div>
            <div class="card-body d-grid gap-2">
                <a href="/admin/students/create" class="btn btn-outline-primary btn-sm"><i class="fas fa-user-plus me-2"></i>Cadastrar Aluno</a>
                <a href="/admin/schools/create" class="btn btn-outline-success btn-sm"><i class="fas fa-school me-2"></i>Adicionar Escola</a>
                <a href="/admin/courses/create" class="btn btn-outline-info btn-sm"><i class="fas fa-book me-2"></i>Novo Curso</a>
                <a href="/admin/enrollments" class="btn btn-outline-warning btn-sm"><i class="fas fa-user-check me-2"></i>Gerenciar Matrículas</a>
                <a href="/admin/contacts" class="btn btn-outline-danger btn-sm position-relative"><i class="fas fa-envelope me-2"></i>Contatos
<?php if ($stats['unread_contacts'] > 0): ?>
<span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"><?= $stats['unread_contacts'] ?></span>
<?php endif; ?>
                </a>
                <a href="/admin/reports" class="btn btn-outline-secondary btn-sm"><i class="fas fa-chart-bar me-2"></i>Relatórios</a>
                <a href="/admin/video-dashboard" class="btn btn-outline-dark btn-sm"><i class="fas fa-chart-line me-2"></i>Tracking de Vídeos</a>
            </div>
        </div>
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white"><h6 class="mb-0 fw-bold" style="color:var(--primary-color)"><i class="fas fa-question-circle me-2"></i>Quizzes</h6></div>
            <div class="card-body text-center"><div class="row"><div class="col-6 border-end"><div class="fs-4 fw-bold text-primary"><?= $stats['total_quiz_attempts'] ?></div><div class="text-muted small">Tentativas</div></div>
            <div class="col-6"><div class="fs-4 fw-bold text-success"><?= $stats['quiz_passed'] ?></div><div class="text-muted small">Aprovados</div></div></div>
<?php if ($stats['total_quiz_attempts'] > 0): ?>
<?php $passRate = round($stats['quiz_passed'] / $stats['total_quiz_attempts'] * 100); ?>
<div class="mt-3"><div class="progress" style="height:10px"><div class="progress-bar bg-success" style="width:<?= $passRate ?>%"></div></div>
<small class="text-muted"><?= $passRate ?>% aprovacao</small></div>
<?php endif; ?>
            </div></div>
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h6 class="mb-0 fw-bold" style="color:var(--primary-color)"><i class="fas fa-school me-2"></i>Escolas</h6>
                <a href="/admin/schools" class="btn btn-sm btn-outline-primary">Ver todas</a>
            </div><div class="card-body text-center"><div class="row">
                <div class="col-6 border-end"><div class="fs-4 fw-bold text-success"><?= $stats['active_schools'] ?></div><div class="text-muted small">Ativas</div></div>
                <div class="col-6"><div class="fs-4 fw-bold text-secondary"><?= $stats['inactive_schools'] ?></div><div class="text-muted small">Inativas</div></div>
            </div></div></div>
    </div>
</div>
