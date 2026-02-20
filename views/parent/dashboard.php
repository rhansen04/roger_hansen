<h2 class="fw-bold mb-4" style="color:var(--dark-teal)"><i class="fas fa-users me-2"></i>Acompanhe seus Filhos</h2>

<?php if (empty($children)): ?>
<div class="card border-0 shadow-sm">
    <div class="card-body text-center py-5">
        <i class="fas fa-user-plus fa-4x text-muted mb-3"></i>
        <h5 class="text-muted">Nenhum aluno vinculado a sua conta ainda.</h5>
        <p class="text-muted small">Entre em contato com a escola para vincular seu filho a sua conta.</p>
    </div>
</div>
<?php else: ?>
<div class="row g-4">
    <?php foreach ($children as $child): ?>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <?php if ($child['avatar']): ?>
                        <img src="<?= htmlspecialchars($child['avatar']) ?>" class="rounded-circle me-3" width="56" height="56" style="object-fit:cover">
                    <?php else: ?>
                        <div class="rounded-circle me-3 d-flex align-items-center justify-content-center" style="width:56px;height:56px;background:var(--primary-color);color:white;font-size:1.4rem;font-weight:bold;">
                            <?= strtoupper(substr($child['name'], 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                    <div>
                        <h5 class="mb-0 fw-bold"><?= htmlspecialchars($child['name']) ?></h5>
                        <small class="text-muted"><?= htmlspecialchars($child['email']) ?></small><br>
                        <small class="text-muted">Ultimo acesso: <?= $child['last_login'] ? date('d/m/Y H:i', strtotime($child['last_login'])) : 'Nunca' ?></small>
                    </div>
                </div>

                <!-- Resumo de cursos -->
                <h6 class="fw-bold text-muted text-uppercase small mb-2">Cursos</h6>
                <?php if (empty($child['enrollments'])): ?>
                    <p class="text-muted small">Sem matriculas.</p>
                <?php else: ?>
                    <?php foreach (array_slice($child['enrollments'], 0, 3) as $en): ?>
                    <div class="mb-2">
                        <div class="d-flex justify-content-between">
                            <small class="fw-bold"><?= htmlspecialchars($en['course_title']) ?></small>
                            <small><?= round($en['overall_progress_percentage']) ?>%</small>
                        </div>
                        <div class="progress" style="height:5px">
                            <div class="progress-bar <?= $en['is_course_completed'] ? 'bg-success' : '' ?>"
                                 style="width:<?= $en['overall_progress_percentage'] ?>%;<?= !$en['is_course_completed'] ? 'background-color:var(--primary-color)' : '' ?>"></div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                <?php endif; ?>

                <!-- Quizzes resumo -->
                <?php
                $passed = count(array_filter($child['quiz_attempts'], fn($q) => $q['passed']));
                $total  = count($child['quiz_attempts']);
                ?>
                <?php if ($total > 0): ?>
                <div class="mt-2 pt-2 border-top">
                    <small class="text-muted">Quizzes: <span class="text-success fw-bold"><?= $passed ?> aprovado(s)</span> / <?= $total ?> realizados</small>
                </div>
                <?php endif; ?>
            </div>
            <div class="card-footer bg-transparent border-0">
                <a href="/minha-area/filho/<?= $child['id'] ?>" class="btn btn-sm w-100" style="background:var(--dark-teal);color:white">
                    <i class="fas fa-eye me-1"></i>Ver Detalhes Completos
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>
