<section class="py-5">
    <div class="container">
        <div class="mb-4 d-flex justify-content-between align-items-start">
            <div>
                <a href="/curso/<?= htmlspecialchars($course['slug']) ?>" class="text-decoration-none text-muted small"><i class="fas fa-arrow-left me-1"></i>Voltar ao curso</a>
                <h2 class="fw-bold mt-2" style="color:var(--primary-color)"><i class="fas fa-comments me-2"></i>Perguntas e Respostas</h2>
                <p class="text-muted"><?= htmlspecialchars($course['title']) ?></p>
            </div>
            <?php if (!$isStaff): ?>
            <button class="btn btn-sm" style="background:var(--primary-color);color:white" data-bs-toggle="modal" data-bs-target="#askModal">
                <i class="fas fa-question-circle me-1"></i>Fazer Pergunta
            </button>
            <?php endif; ?>
        </div>

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show"><i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($_SESSION['success_message']) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']) ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (empty($questions)): ?>
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-comments fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">Nenhuma pergunta ainda.</h5>
                    <?php if (!$isStaff): ?>
                        <button class="btn btn-sm mt-2" style="background:var(--primary-color);color:white" data-bs-toggle="modal" data-bs-target="#askModal">Faça a primeira pergunta</button>
                    <?php endif; ?>
                </div>
            </div>
        <?php else: ?>
            <div class="d-flex flex-column gap-3">
                <?php foreach ($questions as $q): ?>
                <div class="card border-0 shadow-sm <?= !$q['is_answered'] && $isStaff ? 'border-start border-3 border-warning' : '' ?>">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <div class="d-flex align-items-center gap-2">
                                <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width:36px;height:36px;font-size:0.9rem;background:<?= in_array($q['author_role'], ['admin','professor','coordenador']) ? 'var(--dark-teal)' : 'var(--primary-color)' ?>">
                                    <?= strtoupper(substr($q['author_name'], 0, 1)) ?>
                                </div>
                                <div>
                                    <strong class="small"><?= htmlspecialchars($q['author_name']) ?></strong>
                                    <?php if (in_array($q['author_role'], ['admin','professor','coordenador'])): ?>
                                        <span class="badge ms-1" style="background:var(--secondary-color);color:#000;font-size:0.65rem">Professor</span>
                                    <?php endif; ?>
                                    <br><small class="text-muted"><?= date('d/m/Y H:i', strtotime($q['created_at'])) ?></small>
                                </div>
                            </div>
                            <div class="d-flex gap-1 align-items-center">
                                <?php if ($q['lesson_title']): ?>
                                    <span class="badge bg-light text-dark border small"><i class="fas fa-play me-1"></i><?= htmlspecialchars($q['lesson_title']) ?></span>
                                <?php endif; ?>
                                <?php if ($q['is_answered']): ?>
                                    <span class="badge bg-success small"><i class="fas fa-check me-1"></i>Respondida</span>
                                <?php elseif ($isStaff): ?>
                                    <span class="badge bg-warning text-dark small">Aguardando resposta</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <p class="mb-2"><?= nl2br(htmlspecialchars($q['message'])) ?></p>
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted"><i class="fas fa-reply me-1"></i><?= $q['reply_count'] ?> resposta(s)</small>
                            <a href="/curso/<?= htmlspecialchars($course['slug']) ?>/pergunta/<?= $q['id'] ?>" class="btn btn-sm btn-outline-primary">
                                <?= $isStaff ? 'Responder' : 'Ver Thread' ?>
                            </a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Modal: Nova Pergunta -->
<?php if (!$isStaff): ?>
<div class="modal fade" id="askModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header" style="background:var(--primary-color);color:white">
                <h5 class="modal-title"><i class="fas fa-question-circle me-2"></i>Fazer uma Pergunta</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="/curso/<?= htmlspecialchars($course['slug']) ?>/perguntas/nova">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Sua pergunta <span class="text-danger">*</span></label>
                        <textarea name="message" class="form-control" rows="4" required placeholder="Descreva sua dúvida com detalhes..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn" style="background:var(--primary-color);color:white"><i class="fas fa-paper-plane me-2"></i>Enviar Pergunta</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
