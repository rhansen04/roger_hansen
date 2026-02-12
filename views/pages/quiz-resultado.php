<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="text-center mb-4">
                <?php if ($attempt['passed']): ?>
                    <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                    <h2 class="fw-bold text-success">Parabéns! Você passou!</h2>
                <?php else: ?>
                    <i class="fas fa-times-circle fa-4x text-danger mb-3"></i>
                    <h2 class="fw-bold text-danger">Não atingiu a nota mínima</h2>
                <?php endif; ?>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body text-center">
                    <h3 class="fw-bold mb-1"><?php echo htmlspecialchars($quiz['title']); ?></h3>
                    <p class="text-muted"><?php echo htmlspecialchars($course['title']); ?></p>

                    <div class="row mt-4">
                        <div class="col-4">
                            <h4 class="fw-bold <?php echo $attempt['passed'] ? 'text-success' : 'text-danger'; ?>"><?php echo number_format($attempt['score'], 1); ?>%</h4>
                            <small class="text-muted">Sua Nota</small>
                        </div>
                        <div class="col-4">
                            <h4 class="fw-bold"><?php echo $quiz['passing_score']; ?>%</h4>
                            <small class="text-muted">Nota Mínima</small>
                        </div>
                        <div class="col-4">
                            <h4 class="fw-bold"><?php echo $attemptsUsed; ?><?php echo $quiz['attempts_allowed'] > 0 ? '/' . $quiz['attempts_allowed'] : ''; ?></h4>
                            <small class="text-muted">Tentativas</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-center gap-3">
                <a href="/curso/<?php echo htmlspecialchars($course['slug']); ?>" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Voltar ao Curso</a>
                <?php if (empty($noMoreAttempts)): ?>
                    <a href="/curso/<?php echo htmlspecialchars($course['slug']); ?>/quiz/<?php echo $quiz['id']; ?>" class="btn btn-primary"><i class="fas fa-redo me-1"></i> Tentar Novamente</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
