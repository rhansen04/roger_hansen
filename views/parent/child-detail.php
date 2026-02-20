<div class="mb-4">
    <a href="/minha-area" class="text-decoration-none text-muted small"><i class="fas fa-arrow-left me-1"></i>Voltar</a>
    <h2 class="fw-bold mt-2" style="color:var(--dark-teal)"><i class="fas fa-user-graduate me-2"></i><?= htmlspecialchars($child['name']) ?></h2>
    <small class="text-muted"><?= htmlspecialchars($child['email']) ?> &middot; Membro desde <?= date('d/m/Y', strtotime($child['created_at'])) ?></small>
</div>

<!-- Matriculas e Progresso -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white"><h6 class="mb-0 fw-bold" style="color:var(--primary-color)"><i class="fas fa-book me-2"></i>Progresso nos Cursos</h6></div>
    <div class="card-body p-0">
        <?php if (empty($enrollments)): ?>
            <p class="text-muted text-center py-4">Sem matriculas.</p>
        <?php else: ?>
        <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light"><tr><th>Curso</th><th>Progresso</th><th>Status</th><th>Matricula</th><th>Ultima Atividade</th></tr></thead>
            <tbody>
            <?php foreach ($enrollments as $en): ?>
            <tr>
                <td class="fw-bold small"><?= htmlspecialchars($en['course_title']) ?></td>
                <td style="min-width:120px">
                    <div class="progress" style="height:8px"><div class="progress-bar bg-success" style="width:<?= $en['overall_progress_percentage'] ?>%"></div></div>
                    <small class="text-muted"><?= round($en['overall_progress_percentage']) ?>% &middot; <?= round($en['total_watch_time'] / 60) ?>min assistidos</small>
                </td>
                <td>
                    <?php if ($en['is_course_completed']): ?>
                        <span class="badge bg-success">Concluido</span>
                    <?php else: ?>
                        <span class="badge bg-<?= $en['status'] === 'active' ? 'primary' : 'secondary' ?>"><?= ucfirst($en['status']) ?></span>
                    <?php endif; ?>
                </td>
                <td class="small text-muted"><?= date('d/m/Y', strtotime($en['enrollment_date'])) ?></td>
                <td class="small text-muted"><?= $en['last_activity_at'] ? date('d/m/Y', strtotime($en['last_activity_at'])) : 'Sem atividade' ?></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Quiz Attempts -->
<?php if (!empty($quizAttempts)): ?>
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white"><h6 class="mb-0 fw-bold" style="color:var(--primary-color)"><i class="fas fa-question-circle me-2"></i>Desempenho em Quizzes</h6></div>
    <div class="card-body p-0">
        <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light"><tr><th>Quiz</th><th>Curso</th><th>Nota</th><th>Minimo</th><th>Resultado</th><th>Data</th></tr></thead>
            <tbody>
            <?php foreach ($quizAttempts as $qa): ?>
            <tr>
                <td class="small fw-bold"><?= htmlspecialchars($qa['quiz_title']) ?></td>
                <td class="small text-muted"><?= htmlspecialchars($qa['course_title']) ?></td>
                <td><span class="badge <?= $qa['passed'] ? 'bg-success' : 'bg-danger' ?>"><?= round($qa['score']) ?>%</span></td>
                <td class="small text-muted"><?= $qa['passing_score'] ?>%</td>
                <td><?= $qa['passed'] ? '<span class="text-success fw-bold small">Aprovado</span>' : '<span class="text-danger fw-bold small">Reprovado</span>' ?></td>
                <td class="small text-muted"><?= date('d/m/Y', strtotime($qa['started_at'])) ?></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Observacoes Pedagogicas -->
<?php if (!empty($observations)): ?>
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white"><h6 class="mb-0 fw-bold" style="color:var(--primary-color)"><i class="fas fa-clipboard-list me-2"></i>Observacoes dos Professores</h6></div>
    <div class="card-body">
        <?php foreach ($observations as $obs): ?>
        <div class="border-start border-3 ps-3 mb-3" style="border-color:var(--primary-color) !important">
            <div class="d-flex justify-content-between">
                <strong class="small"><?= htmlspecialchars($obs['title'] ?? 'Observacao') ?></strong>
                <small class="text-muted"><?= date('d/m/Y', strtotime($obs['created_at'])) ?></small>
            </div>
            <small class="text-muted d-block">Por: <?= htmlspecialchars($obs['teacher_name']) ?> &middot; <span class="badge bg-light text-dark border"><?= htmlspecialchars($obs['type'] ?? '-') ?></span></small>
            <p class="mt-1 mb-0 small"><?= nl2br(htmlspecialchars($obs['content'])) ?></p>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>
