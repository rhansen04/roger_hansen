<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>
        <i class="fas fa-user-graduate me-2" style="color: var(--primary-color);"></i>
        <?= htmlspecialchars($enrollment['student_name']) ?>
    </h2>
    <a href="/admin/video-dashboard" class="btn btn-outline-secondary">
        <i class="fas fa-arrow-left me-1"></i> Voltar ao Dashboard
    </a>
</div>

<!-- Info do Aluno -->
<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card card-stat p-3">
            <small class="text-muted">Email</small>
            <div class="fw-bold"><?= htmlspecialchars($enrollment['student_email']) ?></div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stat p-3">
            <small class="text-muted">Curso</small>
            <div class="fw-bold"><?= htmlspecialchars($enrollment['course_title']) ?></div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card card-stat p-3 text-center">
            <small class="text-muted">Progresso</small>
            <div class="fs-3 fw-bold" style="color: var(--primary-color);"><?= round($enrollment['overall_progress_percentage'] ?? 0) ?>%</div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card card-stat p-3 text-center">
            <small class="text-muted">Videos</small>
            <div class="fs-3 fw-bold text-success"><?= $enrollment['videos_completed_count'] ?? 0 ?>/<?= $enrollment['total_videos_count'] ?? 0 ?></div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="card card-stat p-3 text-center">
            <small class="text-muted">Status</small>
            <div class="mt-1">
                <?php if ($enrollment['is_course_completed']): ?>
                    <span class="badge bg-success fs-6">Concluido</span>
                <?php else: ?>
                    <span class="badge bg-warning fs-6">Em andamento</span>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Progresso por Video -->
<div class="card card-stat mb-4">
    <div class="card-header bg-white fw-bold">
        <i class="fas fa-play-circle me-2" style="color: var(--primary-color);"></i> Progresso por Video
    </div>
    <div class="card-body p-0">
        <?php if (empty($videoProgress)): ?>
            <p class="text-muted text-center py-4">Nenhum video assistido ainda</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Licao</th>
                            <th>Modulo</th>
                            <th>Progresso</th>
                            <th class="text-center">Sessoes</th>
                            <th class="text-center">Status</th>
                            <th>Ultima Vez</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($videoProgress as $vp): ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($vp['lesson_title']) ?></strong></td>
                                <td><small class="text-muted"><?= htmlspecialchars($vp['section_title'] ?? '-') ?></small></td>
                                <td style="min-width: 200px;">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1" style="height: 14px;">
                                            <div class="progress-bar <?= $vp['is_completed'] ? 'bg-success' : '' ?>"
                                                 style="width: <?= $vp['percentage_watched'] ?>%; <?= !$vp['is_completed'] ? 'background: var(--primary-color);' : '' ?> font-size: 0.65rem;">
                                                <?= round($vp['percentage_watched']) ?>%
                                            </div>
                                        </div>
                                    </div>
                                    <small class="text-muted">
                                        <?= gmdate('i:s', $vp['current_time'] ?? 0) ?> / <?= gmdate('i:s', $vp['total_duration'] ?? 0) ?>
                                    </small>
                                </td>
                                <td class="text-center"><?= $vp['watch_sessions'] ?? 0 ?></td>
                                <td class="text-center">
                                    <?php if ($vp['is_completed']): ?>
                                        <span class="badge bg-success"><i class="fas fa-check me-1"></i>Concluido</span>
                                    <?php elseif ($vp['percentage_watched'] > 0): ?>
                                        <span class="badge bg-warning">Em andamento</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Nao iniciado</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <small><?= $vp['last_watched_at'] ? date('d/m/Y H:i', strtotime($vp['last_watched_at'])) : '-' ?></small>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Historico de Sessoes -->
<div class="card card-stat">
    <div class="card-header bg-white fw-bold">
        <i class="fas fa-history me-2 text-info"></i> Historico de Sessoes
    </div>
    <div class="card-body p-0">
        <?php if (empty($watchLogs)): ?>
            <p class="text-muted text-center py-4">Nenhuma sessao registrada</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Licao</th>
                            <th>Inicio</th>
                            <th>Fim</th>
                            <th class="text-center">Duracao</th>
                            <th class="text-center">% Antes</th>
                            <th class="text-center">% Depois</th>
                            <th class="text-center">Completou</th>
                            <th>Dispositivo</th>
                            <th>IP</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($watchLogs as $log): ?>
                            <tr>
                                <td><small><?= htmlspecialchars($log['lesson_title']) ?></small></td>
                                <td><small><?= $log['session_start'] ? date('d/m H:i:s', strtotime($log['session_start'])) : '-' ?></small></td>
                                <td><small><?= $log['session_end'] ? date('d/m H:i:s', strtotime($log['session_end'])) : '<span class="text-warning">aberta</span>' ?></small></td>
                                <td class="text-center">
                                    <small>
                                        <?php if ($log['session_duration'] > 0): ?>
                                            <?= floor($log['session_duration'] / 60) ?>:<?= str_pad($log['session_duration'] % 60, 2, '0', STR_PAD_LEFT) ?>
                                        <?php else: ?>
                                            -
                                        <?php endif; ?>
                                    </small>
                                </td>
                                <td class="text-center"><small><?= round($log['percentage_before'] ?? 0) ?>%</small></td>
                                <td class="text-center"><small><?= round($log['percentage_after'] ?? 0) ?>%</small></td>
                                <td class="text-center">
                                    <?= $log['completed_during_session'] ? '<i class="fas fa-check-circle text-success"></i>' : '-' ?>
                                </td>
                                <td><small class="text-muted" title="<?= htmlspecialchars($log['device_info'] ?? '') ?>"><?= substr($log['device_info'] ?? '', 0, 30) ?>...</small></td>
                                <td><small class="text-muted"><?= htmlspecialchars($log['ip_address'] ?? '') ?></small></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
