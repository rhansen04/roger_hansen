<h2 class="text-primary fw-bold mb-4">MEUS CURSOS</h2>

<?php if (empty($enrollments)): ?>
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="fas fa-book-open fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">Você ainda não está matriculado em nenhum curso</h4>
            <p class="text-muted">Explore nossos cursos disponíveis e comece a aprender!</p>
            <a href="/cursos" class="btn btn-hansen text-white">Ver Cursos Disponíveis</a>
        </div>
    </div>
<?php else: ?>
    <div class="row">
        <?php foreach ($enrollments as $enrollment): ?>
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <?php if (!empty($enrollment['course']['cover_image'])): ?>
                    <img src="<?php echo $enrollment['course']['cover_image']; ?>" class="card-img-top" alt="<?php echo htmlspecialchars($enrollment['course_title']); ?>" style="height: 180px; object-fit: cover;">
                <?php else: ?>
                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 180px;">
                        <i class="fas fa-book fa-4x text-muted"></i>
                    </div>
                <?php endif; ?>
                <div class="card-body">
                    <h5 class="card-title fw-bold"><?php echo htmlspecialchars($enrollment['course_title']); ?></h5>
                    <p class="text-muted small mb-2">
                        Matriculado em: <?php echo date('d/m/Y', strtotime($enrollment['enrollment_date'] ?? $enrollment['created_at'] ?? 'now')); ?>
                    </p>

                    <!-- Progress Bar -->
                    <?php $progress = $enrollment['overall_progress_percentage'] ?? $enrollment['completion_percentage'] ?? 0; ?>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <small class="fw-bold">Progresso</small>
                            <small class="fw-bold"><?php echo number_format($progress, 0); ?>%</small>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $progress; ?>%"></div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <span class="badge <?php echo $enrollment['status'] === 'active' ? 'bg-success' : 'bg-secondary'; ?>">
                            <?php echo $enrollment['status'] === 'active' ? 'Ativo' : ucfirst($enrollment['status']); ?>
                        </span>
                        <div>
                            <?php if (!empty($enrollment['is_course_completed'])): ?>
                                <a href="/certificado/gerar/<?php echo $enrollment['id']; ?>" class="btn btn-outline-success btn-sm me-1" title="Certificado"><i class="fas fa-certificate"></i></a>
                            <?php endif; ?>
                            <a href="/curso/<?php echo $enrollment['course_slug']; ?>" class="btn btn-hansen btn-sm text-white">Continuar</a>
                            <a href="/curso/<?php echo $enrollment['course_slug']; ?>/perguntas" class="btn btn-outline-secondary btn-sm ms-1" title="Perguntas e Respostas"><i class="fas fa-comments"></i></a>
                            <?php if (!empty($enrollment['material_count'])): ?>
                                <a href="/curso/<?php echo $enrollment['course_slug']; ?>/materiais" class="btn btn-outline-secondary btn-sm ms-1" title="Materiais de Apoio">
                                    <i class="fas fa-paperclip"></i>
                                    <span class="badge bg-primary rounded-pill ms-1"><?php echo $enrollment['material_count']; ?></span>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if (!empty($failedQuizEnrollments)): ?>
<div class="mt-5">
    <h4 class="fw-bold text-danger mb-3"><i class="fas fa-exclamation-triangle me-2"></i>Atenção: Abaixo da Nota Mínima</h4>
    <div class="row">
        <?php foreach ($failedQuizEnrollments as $fq): ?>
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card border-danger border-0 shadow-sm h-100" style="border-left: 4px solid #dc3545 !important;">
                <div class="card-body">
                    <div class="d-flex align-items-start mb-2">
                        <i class="fas fa-times-circle text-danger fa-2x me-3 mt-1"></i>
                        <div>
                            <h6 class="fw-bold mb-1"><?= htmlspecialchars($fq['course_title']) ?></h6>
                            <small class="text-muted">Quiz: <?= htmlspecialchars($fq['quiz_title']) ?></small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <small>Sua nota</small>
                            <small class="fw-bold text-danger"><?= round($fq['best_score']) ?>%</small>
                        </div>
                        <div class="progress" style="height:8px">
                            <div class="progress-bar bg-danger" style="width:<?= $fq['best_score'] ?>%"></div>
                        </div>
                        <div class="d-flex justify-content-between mt-1">
                            <small class="text-muted"><?= $fq['attempt_count'] ?> tentativa(s)</small>
                            <small class="text-muted">Mínimo: <?= $fq['passing_score'] ?>%</small>
                        </div>
                    </div>
                    <?php $canRetry = $fq['attempts_allowed'] == 0 || $fq['attempt_count'] < $fq['attempts_allowed']; ?>
                    <?php if ($canRetry): ?>
                        <a href="/curso/<?= htmlspecialchars($fq['course_slug']) ?>" class="btn btn-danger btn-sm w-100">
                            <i class="fas fa-redo me-2"></i>Refazer Curso / Quiz
                        </a>
                    <?php else: ?>
                        <div class="alert alert-warning py-2 mb-0 text-center small">
                            <i class="fas fa-lock me-1"></i>Limite de tentativas atingido.<br>
                            <strong>Solicite ao professor para liberar nova tentativa.</strong>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php endif; ?>
