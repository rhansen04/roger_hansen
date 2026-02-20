<section class="py-5">
    <div class="container" style="max-width:800px">
        <div class="mb-4">
            <a href="/curso/<?= htmlspecialchars($course['slug']) ?>/perguntas" class="text-decoration-none text-muted small"><i class="fas fa-arrow-left me-1"></i>Voltar às perguntas</a>
            <h2 class="fw-bold mt-2" style="color:var(--primary-color)">Thread da Pergunta</h2>
        </div>

        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show"><?= htmlspecialchars($_SESSION['success_message']) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>

        <!-- Pergunta original -->
        <div class="card border-0 shadow-sm mb-4" style="border-left: 4px solid var(--primary-color) !important">
            <div class="card-body">
                <div class="d-flex align-items-center gap-2 mb-3">
                    <div class="rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width:40px;height:40px;background:var(--primary-color)">
                        <?= strtoupper(substr($question['author_name'], 0, 1)) ?>
                    </div>
                    <div>
                        <strong><?= htmlspecialchars($question['author_name']) ?></strong>
                        <span class="badge bg-light text-dark border ms-1 small">Aluno</span>
                        <?php if ($question['lesson_title']): ?>
                            <span class="badge bg-light text-dark border ms-1 small"><i class="fas fa-play me-1"></i><?= htmlspecialchars($question['lesson_title']) ?></span>
                        <?php endif; ?>
                        <br><small class="text-muted"><?= date('d/m/Y H:i', strtotime($question['created_at'])) ?></small>
                    </div>
                </div>
                <p class="mb-0 fs-6"><?= nl2br(htmlspecialchars($question['message'])) ?></p>
            </div>
        </div>

        <!-- Respostas -->
        <?php if (!empty($replies)): ?>
        <div class="d-flex flex-column gap-3 ms-4 mb-4">
            <?php foreach ($replies as $reply):
                $isTeacher = in_array($reply['author_role'], ['admin','professor','coordenador']);
            ?>
            <div class="card border-0 shadow-sm <?= $isTeacher ? 'border-start border-3' : '' ?>" style="<?= $isTeacher ? 'border-color:var(--secondary-color) !important' : '' ?>">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <div class="rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width:32px;height:32px;font-size:0.8rem;background:<?= $isTeacher ? 'var(--secondary-color);color:#000' : 'var(--bg-light);color:var(--primary-color)' ?>">
                            <?= strtoupper(substr($reply['author_name'], 0, 1)) ?>
                        </div>
                        <div>
                            <strong class="small"><?= htmlspecialchars($reply['author_name']) ?></strong>
                            <?php if ($isTeacher): ?>
                                <span class="badge ms-1" style="background:var(--secondary-color);color:#000;font-size:0.65rem"><i class="fas fa-chalkboard-teacher me-1"></i>Professor</span>
                            <?php endif; ?>
                            <br><small class="text-muted"><?= date('d/m/Y H:i', strtotime($reply['created_at'])) ?></small>
                        </div>
                    </div>
                    <p class="mb-0 small"><?= nl2br(htmlspecialchars($reply['message'])) ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <!-- Formulário de resposta -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white">
                <h6 class="mb-0 fw-bold">
                    <?= $isStaff ? '<i class="fas fa-reply me-2"></i>Responder' : '<i class="fas fa-comment me-2"></i>Adicionar comentário' ?>
                </h6>
            </div>
            <div class="card-body">
                <form method="POST" action="/curso/<?= htmlspecialchars($course['slug']) ?>/pergunta/<?= $question['id'] ?>/responder">
                    <textarea name="message" class="form-control mb-3" rows="3" required placeholder="<?= $isStaff ? 'Digite sua resposta...' : 'Adicione um comentário...' ?>"></textarea>
                    <button type="submit" class="btn btn-sm" style="background:<?= $isStaff ? 'var(--secondary-color);color:#000' : 'var(--primary-color);color:white' ?>">
                        <i class="fas fa-paper-plane me-2"></i><?= $isStaff ? 'Publicar Resposta' : 'Enviar' ?>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
