<h2 class="mb-4"><i class="fas fa-chart-line me-2" style="color: var(--primary-color);"></i> Dashboard de Videos</h2>

<!-- Cards de Metricas -->
<div class="row g-3 mb-4">
    <div class="col-md-4 col-lg-2">
        <div class="card card-stat text-center p-3">
            <div class="fs-2 fw-bold" style="color: var(--primary-color);"><?= $stats['total_enrollments'] ?></div>
            <small class="text-muted">Matriculas Ativas</small>
        </div>
    </div>
    <div class="col-md-4 col-lg-2">
        <div class="card card-stat text-center p-3">
            <div class="fs-2 fw-bold text-success"><?= $stats['total_completed'] ?></div>
            <small class="text-muted">Videos Completados</small>
        </div>
    </div>
    <div class="col-md-4 col-lg-2">
        <div class="card card-stat text-center p-3">
            <div class="fs-2 fw-bold text-info"><?= $stats['total_sessions'] ?></div>
            <small class="text-muted">Sessoes de Video</small>
        </div>
    </div>
    <div class="col-md-4 col-lg-2">
        <div class="card card-stat text-center p-3">
            <div class="fs-2 fw-bold text-warning"><?= $stats['total_watch_hours'] ?>h</div>
            <small class="text-muted">Horas Assistidas</small>
        </div>
    </div>
    <div class="col-md-4 col-lg-2">
        <div class="card card-stat text-center p-3">
            <div class="fs-2 fw-bold text-danger"><?= $stats['courses_completed'] ?></div>
            <small class="text-muted">Cursos 100%</small>
        </div>
    </div>
    <div class="col-md-4 col-lg-2">
        <div class="card card-stat text-center p-3">
            <div class="fs-2 fw-bold" style="color: var(--dark-teal);"><?= $stats['avg_progress'] ?>%</div>
            <small class="text-muted">Progresso Medio</small>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Progresso por Curso -->
    <div class="col-lg-6">
        <div class="card card-stat">
            <div class="card-header bg-white fw-bold">
                <i class="fas fa-graduation-cap me-2" style="color: var(--primary-color);"></i> Progresso por Curso
            </div>
            <div class="card-body p-0">
                <?php if (empty($courseStats)): ?>
                    <p class="text-muted text-center py-4">Nenhum curso cadastrado</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Curso</th>
                                    <th class="text-center">Alunos</th>
                                    <th>Progresso</th>
                                    <th class="text-center">Concluidos</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($courseStats as $course): ?>
                                    <tr>
                                        <td>
                                            <strong><?= htmlspecialchars($course['title']) ?></strong>
                                            <br><small class="text-muted"><?= $course['total_lessons'] ?> licoes</small>
                                        </td>
                                        <td class="text-center"><?= $course['total_students'] ?></td>
                                        <td>
                                            <div class="progress" style="height: 18px;">
                                                <div class="progress-bar" style="width: <?= $course['avg_progress'] ?>%; background: var(--primary-color); font-size: 0.7rem;">
                                                    <?= $course['avg_progress'] ?>%
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <span class="badge bg-success"><?= $course['completed_count'] ?></span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Top Alunos -->
    <div class="col-lg-6">
        <div class="card card-stat">
            <div class="card-header bg-white fw-bold">
                <i class="fas fa-trophy me-2 text-warning"></i> Ranking de Alunos
            </div>
            <div class="card-body p-0">
                <?php if (empty($topStudents)): ?>
                    <p class="text-muted text-center py-4">Nenhum aluno matriculado</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Aluno</th>
                                    <th>Curso</th>
                                    <th>Progresso</th>
                                    <th>Ultima Atividade</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($topStudents as $student): ?>
                                    <tr>
                                        <td>
                                            <a href="/admin/video-dashboard/aluno/<?= $student['enrollment_id'] ?>" class="text-decoration-none">
                                                <strong><?= htmlspecialchars($student['name']) ?></strong>
                                            </a>
                                            <br><small class="text-muted"><?= htmlspecialchars($student['email']) ?></small>
                                        </td>
                                        <td><small><?= htmlspecialchars($student['course_title']) ?></small></td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="progress flex-grow-1" style="height: 12px;">
                                                    <div class="progress-bar <?= $student['is_course_completed'] ? 'bg-success' : '' ?>"
                                                         style="width: <?= $student['overall_progress_percentage'] ?>%; <?= !$student['is_course_completed'] ? 'background: var(--primary-color);' : '' ?>">
                                                    </div>
                                                </div>
                                                <small class="fw-bold" style="min-width: 40px;"><?= round($student['overall_progress_percentage']) ?>%</small>
                                            </div>
                                            <small class="text-muted"><?= $student['videos_completed_count'] ?>/<?= $student['total_videos_count'] ?> videos</small>
                                        </td>
                                        <td>
                                            <?php if ($student['last_activity_at']): ?>
                                                <small><?= date('d/m H:i', strtotime($student['last_activity_at'])) ?></small>
                                            <?php else: ?>
                                                <small class="text-muted">-</small>
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
    </div>
</div>

<div class="row g-4 mt-2">
    <!-- Atividade dos ultimos 7 dias -->
    <div class="col-lg-6">
        <div class="card card-stat">
            <div class="card-header bg-white fw-bold">
                <i class="fas fa-calendar-week me-2 text-info"></i> Atividade - Ultimos 7 Dias
            </div>
            <div class="card-body p-0">
                <?php if (empty($recentActivity)): ?>
                    <p class="text-muted text-center py-4">Nenhuma atividade recente</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Data</th>
                                    <th class="text-center">Sessoes</th>
                                    <th class="text-center">Alunos</th>
                                    <th class="text-center">Minutos</th>
                                    <th class="text-center">Completados</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($recentActivity as $day): ?>
                                    <tr>
                                        <td><?= date('d/m/Y', strtotime($day['dia'])) ?></td>
                                        <td class="text-center"><?= $day['sessoes'] ?></td>
                                        <td class="text-center"><?= $day['alunos_ativos'] ?></td>
                                        <td class="text-center"><?= $day['minutos_assistidos'] ?> min</td>
                                        <td class="text-center"><span class="badge bg-success"><?= $day['completados'] ?></span></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Alunos Inativos -->
    <div class="col-lg-6">
        <div class="card card-stat">
            <div class="card-header bg-white fw-bold">
                <i class="fas fa-user-clock me-2 text-danger"></i> Alunos Inativos (+7 dias)
            </div>
            <div class="card-body p-0">
                <?php if (empty($inactiveStudents)): ?>
                    <p class="text-success text-center py-4"><i class="fas fa-check-circle me-1"></i> Todos os alunos estao ativos!</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Aluno</th>
                                    <th>Curso</th>
                                    <th class="text-center">Progresso</th>
                                    <th class="text-center">Dias Inativo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($inactiveStudents as $student): ?>
                                    <tr>
                                        <td>
                                            <a href="/admin/video-dashboard/aluno/<?= $student['enrollment_id'] ?>" class="text-decoration-none">
                                                <?= htmlspecialchars($student['name']) ?>
                                            </a>
                                        </td>
                                        <td><small><?= htmlspecialchars($student['course_title']) ?></small></td>
                                        <td class="text-center"><?= round($student['overall_progress_percentage']) ?>%</td>
                                        <td class="text-center">
                                            <span class="badge bg-danger"><?= $student['days_inactive'] ?> dias</span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Logs de Sessoes Recentes -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card card-stat">
            <div class="card-header bg-white fw-bold d-flex justify-content-between align-items-center">
                <span><i class="fas fa-history me-2" style="color: var(--primary-color);"></i> Sessoes Recentes</span>
                <small class="text-muted">Ultimas 20 sessoes</small>
            </div>
            <div class="card-body p-0">
                <?php if (empty($watchLogs)): ?>
                    <p class="text-muted text-center py-4">Nenhuma sessao registrada</p>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover table-sm mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Aluno</th>
                                    <th>Licao</th>
                                    <th>Curso</th>
                                    <th>Inicio</th>
                                    <th class="text-center">Duracao</th>
                                    <th class="text-center">Antes</th>
                                    <th class="text-center">Depois</th>
                                    <th class="text-center">Completou</th>
                                    <th>IP</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($watchLogs as $log): ?>
                                    <tr>
                                        <td><small><?= htmlspecialchars($log['student_name']) ?></small></td>
                                        <td><small><?= htmlspecialchars($log['lesson_title']) ?></small></td>
                                        <td><small class="text-muted"><?= htmlspecialchars($log['course_title']) ?></small></td>
                                        <td><small><?= $log['session_start'] ? date('d/m H:i', strtotime($log['session_start'])) : '-' ?></small></td>
                                        <td class="text-center">
                                            <small>
                                                <?php if ($log['session_duration'] > 0): ?>
                                                    <?= floor($log['session_duration'] / 60) ?>:<?= str_pad($log['session_duration'] % 60, 2, '0', STR_PAD_LEFT) ?>
                                                <?php else: ?>
                                                    <span class="text-warning">em andamento</span>
                                                <?php endif; ?>
                                            </small>
                                        </td>
                                        <td class="text-center"><small><?= round($log['percentage_before'] ?? 0) ?>%</small></td>
                                        <td class="text-center"><small><?= round($log['percentage_after'] ?? 0) ?>%</small></td>
                                        <td class="text-center">
                                            <?php if ($log['completed_during_session']): ?>
                                                <i class="fas fa-check-circle text-success"></i>
                                            <?php else: ?>
                                                <small class="text-muted">-</small>
                                            <?php endif; ?>
                                        </td>
                                        <td><small class="text-muted"><?= htmlspecialchars($log['ip_address'] ?? '') ?></small></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
