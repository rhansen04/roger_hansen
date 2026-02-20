<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold mb-0" style="color:var(--primary-color)">
        <i class="fas fa-comments me-2"></i>Perguntas dos Alunos
        <?php if ($unreadCount > 0): ?>
            <span class="badge bg-danger fs-6 ms-2"><?= $unreadCount ?> novas</span>
        <?php endif; ?>
    </h2>
</div>

<?php if (empty($questions)): ?>
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="fas fa-comments fa-4x text-muted mb-3"></i>
            <h5 class="text-muted">Nenhuma pergunta ainda.</h5>
        </div>
    </div>
<?php else: ?>
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>Aluno</th><th>Curso</th><th>Pergunta</th><th>Lição</th><th>Status</th><th>Data</th><th>Ação</th></tr>
            </thead>
            <tbody>
            <?php foreach ($questions as $q): ?>
            <tr class="<?= !$q['is_read'] ? 'fw-bold' : '' ?>">
                <td class="small"><?= htmlspecialchars($q['author_name']) ?></td>
                <td class="small text-muted"><?= htmlspecialchars($q['course_title']) ?></td>
                <td class="small"><?= htmlspecialchars(substr($q['message'], 0, 80)) ?><?= strlen($q['message']) > 80 ? '...' : '' ?></td>
                <td class="small text-muted"><?= $q['lesson_title'] ? htmlspecialchars($q['lesson_title']) : '-' ?></td>
                <td>
                    <?php if ($q['is_answered']): ?>
                        <span class="badge bg-success small">Respondida</span>
                    <?php elseif ($q['is_read']): ?>
                        <span class="badge bg-warning text-dark small">Lida</span>
                    <?php else: ?>
                        <span class="badge bg-danger small">Nova</span>
                    <?php endif; ?>
                </td>
                <td class="small text-muted"><?= date('d/m/Y H:i', strtotime($q['created_at'])) ?></td>
                <td>
                    <a href="/curso/<?= htmlspecialchars($q['course_slug']) ?>/pergunta/<?= $q['id'] ?>" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-reply me-1"></i>Responder
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
