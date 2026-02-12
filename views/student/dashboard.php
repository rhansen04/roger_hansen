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
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
