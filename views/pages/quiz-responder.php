<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-primary mb-1"><?php echo htmlspecialchars($quiz['title']); ?></h2>
                    <p class="text-muted mb-0"><?php echo htmlspecialchars($course['title']); ?> &bull; Tentativa <?php echo $attemptNumber; ?></p>
                </div>
                <?php if (!empty($quiz['time_limit_minutes'])): ?>
                <div class="text-end">
                    <span class="badge bg-warning text-dark fs-6"><i class="fas fa-clock me-1"></i> <?php echo $quiz['time_limit_minutes']; ?> min</span>
                </div>
                <?php endif; ?>
            </div>

            <?php if (!empty($quiz['description'])): ?>
            <div class="alert alert-info"><?php echo htmlspecialchars($quiz['description']); ?></div>
            <?php endif; ?>

            <form method="POST" action="/curso/<?php echo htmlspecialchars($course['slug']); ?>/quiz/<?php echo $quiz['id']; ?>/submit">
                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">

                <?php foreach ($questions as $i => $q): ?>
                <div class="card border-0 shadow-sm mb-3">
                    <div class="card-body">
                        <h6 class="fw-bold mb-3">
                            <span class="badge bg-primary me-2"><?php echo ($i + 1); ?></span>
                            <?php echo htmlspecialchars($q['question_text']); ?>
                            <small class="text-muted fw-normal">(<?php echo $q['points']; ?> pt<?php echo $q['points'] > 1 ? 's' : ''; ?>)</small>
                        </h6>
                        <?php foreach ($q['answers'] as $a): ?>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="question_<?php echo $q['id']; ?>" value="<?php echo $a['id']; ?>" id="answer_<?php echo $a['id']; ?>">
                            <label class="form-check-label" for="answer_<?php echo $a['id']; ?>"><?php echo htmlspecialchars($a['answer_text']); ?></label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endforeach; ?>

                <div class="d-flex justify-content-between mt-4">
                    <a href="/curso/<?php echo htmlspecialchars($course['slug']); ?>" class="btn btn-outline-secondary">Cancelar</a>
                    <button type="submit" class="btn btn-success btn-lg" onclick="return confirm('Enviar respostas? Esta ação não pode ser desfeita.')">
                        <i class="fas fa-paper-plane me-1"></i> Enviar Respostas
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
