<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0" style="color:var(--primary-color)"><i class="fas fa-chart-line me-2"></i>Alunos Abaixo da Nota Mínima</h2>
    <a href="/admin/reports" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i>Relatórios</a>
</div>

<?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show"><i class="fas fa-check-circle me-2"></i><?= $_SESSION['success_message'] ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php unset($_SESSION['success_message']); ?>
<?php endif; ?>

<?php if (empty($lowScores)): ?>
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
            <h5>Nenhum aluno abaixo da nota mínima.</h5>
        </div>
    </div>
<?php else: ?>
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white">
        <span class="badge bg-danger me-2"><?= count($lowScores) ?></span> aluno(s) precisam de atenção
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Aluno</th>
                    <th>Curso</th>
                    <th>Quiz</th>
                    <th>Melhor Nota</th>
                    <th>Nota Mínima</th>
                    <th>Tentativas</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($lowScores as $ls): ?>
                <tr>
                    <td class="fw-bold small"><?= htmlspecialchars($ls['student_name']) ?></td>
                    <td class="small"><?= htmlspecialchars($ls['course_title']) ?></td>
                    <td class="small text-muted"><?= htmlspecialchars($ls['quiz_title']) ?></td>
                    <td>
                        <span class="badge bg-danger"><?= round($ls['best_score']) ?>%</span>
                    </td>
                    <td class="small text-muted"><?= $ls['passing_score'] ?>%</td>
                    <td class="small">
                        <?= $ls['attempt_count'] ?>
                        <?php if ($ls['attempts_allowed'] > 0): ?>
                            / <?= $ls['attempts_allowed'] ?>
                        <?php else: ?>
                            <small class="text-muted">(ilimitado)</small>
                        <?php endif; ?>
                    </td>
                    <td>
                        <form method="POST" action="/admin/quiz/<?= $ls['quiz_id'] ?>/reset-attempts" class="d-inline">
                            <input type="hidden" name="user_id" value="<?= $ls['user_id'] ?>">
                            <input type="hidden" name="enrollment_id" value="<?= $ls['enrollment_id'] ?>">
                            <button type="submit" class="btn btn-sm btn-outline-warning" title="Liberar nova tentativa" onclick="return confirm('Liberar nova tentativa para este aluno?')">
                                <i class="fas fa-redo me-1"></i>Liberar Retry
                            </button>
                        </form>
                        <a href="/admin/students/<?= $ls['user_id'] ?>" class="btn btn-sm btn-outline-info ms-1" title="Ver aluno">
                            <i class="fas fa-user"></i>
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>
</div>
<?php endif; ?>
