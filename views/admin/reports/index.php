<h2 class="text-primary fw-bold mb-4"><i class="fas fa-chart-bar me-2"></i>Relatórios</h2>

<!-- Cards de Resumo -->
<div class="row mb-4">
    <div class="col-md-2">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
                <h3 class="fw-bold text-primary"><?php echo $stats['total_students']; ?></h3>
                <small class="text-muted">Alunos</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
                <h3 class="fw-bold text-info"><?php echo $stats['total_courses']; ?></h3>
                <small class="text-muted">Cursos Ativos</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
                <h3 class="fw-bold text-success"><?php echo $stats['total_enrollments']; ?></h3>
                <small class="text-muted">Matrículas</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
                <h3 class="fw-bold text-warning"><?php echo $stats['active_enrollments']; ?></h3>
                <small class="text-muted">Ativas</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
                <h3 class="fw-bold text-success"><?php echo $stats['completed_courses']; ?></h3>
                <small class="text-muted">Concluídos</small>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card border-0 shadow-sm text-center">
            <div class="card-body">
                <h3 class="fw-bold text-primary"><?php echo $stats['avg_progress']; ?>%</h3>
                <small class="text-muted">Progresso Médio</small>
            </div>
        </div>
    </div>
</div>

<!-- Desempenho por Curso -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white"><h5 class="mb-0"><i class="fas fa-book me-2"></i>Desempenho por Curso</h5></div>
    <div class="card-body">
        <?php if (empty($courseStats)): ?>
            <p class="text-muted text-center">Nenhum dado disponível.</p>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Curso</th>
                        <th class="text-center">Matrículas</th>
                        <th class="text-center">Ativas</th>
                        <th class="text-center">Concluídas</th>
                        <th class="text-center">Progresso Médio</th>
                        <th>Taxa Conclusão</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($courseStats as $cs): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($cs['title']); ?></strong></td>
                        <td class="text-center"><?php echo $cs['total_enrollments']; ?></td>
                        <td class="text-center"><span class="badge bg-success"><?php echo $cs['active_count']; ?></span></td>
                        <td class="text-center"><span class="badge bg-primary"><?php echo $cs['completed_count']; ?></span></td>
                        <td class="text-center">
                            <div class="progress" style="height: 20px;">
                                <div class="progress-bar bg-info" style="width: <?php echo $cs['avg_progress'] ?? 0; ?>%">
                                    <?php echo $cs['avg_progress'] ?? 0; ?>%
                                </div>
                            </div>
                        </td>
                        <td>
                            <?php
                            $rate = $cs['total_enrollments'] > 0 ? round($cs['completed_count'] / $cs['total_enrollments'] * 100, 1) : 0;
                            ?>
                            <span class="badge <?php echo $rate >= 50 ? 'bg-success' : ($rate >= 25 ? 'bg-warning text-dark' : 'bg-secondary'); ?>"><?php echo $rate; ?>%</span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Quiz Stats -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white"><h5 class="mb-0"><i class="fas fa-question-circle me-2"></i>Desempenho em Quizzes</h5></div>
    <div class="card-body">
        <?php if (empty($quizStats)): ?>
            <p class="text-muted text-center">Nenhuma tentativa de quiz registrada.</p>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Quiz</th>
                        <th>Curso</th>
                        <th class="text-center">Tentativas</th>
                        <th class="text-center">Nota Média</th>
                        <th class="text-center">Aprovados</th>
                        <th class="text-center">Taxa Aprovação</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($quizStats as $qs): ?>
                    <tr>
                        <td><strong><?php echo htmlspecialchars($qs['quiz_title']); ?></strong></td>
                        <td><small class="text-muted"><?php echo htmlspecialchars($qs['course_title']); ?></small></td>
                        <td class="text-center"><?php echo $qs['total_attempts']; ?></td>
                        <td class="text-center">
                            <span class="badge <?php echo $qs['avg_score'] >= 70 ? 'bg-success' : 'bg-warning text-dark'; ?>"><?php echo $qs['avg_score']; ?>%</span>
                        </td>
                        <td class="text-center"><?php echo $qs['passed_count']; ?></td>
                        <td class="text-center">
                            <span class="badge <?php echo $qs['pass_rate'] >= 70 ? 'bg-success' : ($qs['pass_rate'] >= 40 ? 'bg-warning text-dark' : 'bg-danger'); ?>"><?php echo $qs['pass_rate']; ?>%</span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Matrículas Recentes (30 dias) -->
<?php if (!empty($recentEnrollments)): ?>
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white"><h5 class="mb-0"><i class="fas fa-calendar me-2"></i>Matrículas - Últimos 30 Dias</h5></div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-sm">
                <thead><tr><th>Data</th><th>Novas Matrículas</th><th>Visualização</th></tr></thead>
                <tbody>
                    <?php
                    $maxCnt = max(array_column($recentEnrollments, 'cnt'));
                    foreach ($recentEnrollments as $re):
                    ?>
                    <tr>
                        <td><?php echo date('d/m/Y', strtotime($re['dt'])); ?></td>
                        <td><strong><?php echo $re['cnt']; ?></strong></td>
                        <td>
                            <div class="progress" style="height: 15px;">
                                <div class="progress-bar bg-primary" style="width: <?php echo ($re['cnt'] / $maxCnt) * 100; ?>%"></div>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>
