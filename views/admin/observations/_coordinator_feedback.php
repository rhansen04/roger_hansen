<?php
/**
 * Partial: coordinator feedback section
 * Requires: $contentType (string), $contentId (int), $comments (array from CoordinatorComment::findByContent)
 * Only shown if user is coordinator or admin, or if there are existing comments
 */
$userRole = $_SESSION['user_role'] ?? '';
$canComment = in_array($userRole, ['coordenador', 'admin']);
if (!$canComment && empty($comments)) return;
?>
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-bottom d-flex align-items-center gap-2">
        <i class="fas fa-comment-dots text-primary"></i>
        <h5 class="mb-0 fw-bold" style="color: var(--primary-color, #007e66);">Feedbacks da Coordenacao</h5>
        <span class="badge bg-secondary ms-auto"><?= count($comments) ?></span>
    </div>
    <div class="card-body p-4">
        <?php if (empty($comments)): ?>
            <p class="text-muted small mb-3"><i class="fas fa-comment-slash me-1"></i>Nenhum feedback registrado ainda.</p>
        <?php else: ?>
            <div class="mb-4">
                <?php foreach ($comments as $c): ?>
                <div class="d-flex gap-3 mb-3 pb-3 border-bottom">
                    <div class="flex-shrink-0">
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                             style="width:36px;height:36px;background:var(--primary-color,#007e66);color:#fff;font-weight:700;font-size:.85rem;">
                            <?= strtoupper(substr($c['coordinator_name'], 0, 1)) ?>
                        </div>
                    </div>
                    <div class="flex-grow-1">
                        <div class="fw-bold small"><?= htmlspecialchars($c['coordinator_name']) ?></div>
                        <div class="text-muted" style="font-size:.78rem;"><?= date('d/m/Y \a\s H:i', strtotime($c['created_at'])) ?></div>
                        <div class="mt-1"><?= nl2br(htmlspecialchars($c['comment'])) ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if ($canComment): ?>
        <form action="/admin/coordinator-feedback" method="POST">
            <input type="hidden" name="content_type" value="<?= htmlspecialchars($contentType) ?>">
            <input type="hidden" name="content_id" value="<?= (int)$contentId ?>">
            <input type="hidden" name="return_url" value="<?= htmlspecialchars($_SERVER['REQUEST_URI']) ?>">
            <div class="mb-2">
                <label class="form-label fw-bold small">Novo feedback / orientacao pedagogica</label>
                <textarea name="comment" class="form-control" rows="3" required
                          placeholder="Escreva seu feedback ou orientacao para o professor..."></textarea>
            </div>
            <button type="submit" class="btn btn-hansen btn-sm">
                <i class="fas fa-paper-plane me-1"></i> Enviar Feedback
            </button>
        </form>
        <?php endif; ?>
    </div>
</div>
