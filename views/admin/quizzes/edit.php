<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-primary fw-bold mb-0">Editar Quiz: <?php echo htmlspecialchars($quiz['title']); ?></h2>
    <a href="/admin/courses/<?php echo $course['id']; ?>/quizzes" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Voltar</a>
</div>

<?php if (!empty($_SESSION['success_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
<?php endif; ?>
<?php if (!empty($_SESSION['error_message'])): ?>
    <div class="alert alert-danger alert-dismissible fade show"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
<?php endif; ?>

<!-- Dados do Quiz -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white"><h5 class="mb-0"><i class="fas fa-cog me-2"></i>Configurações do Quiz</h5></div>
    <div class="card-body">
        <form method="POST" action="/admin/quizzes/<?php echo $quiz['id']; ?>/update">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Título *</label>
                    <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($quiz['title']); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Descrição</label>
                    <input type="text" name="description" class="form-control" value="<?php echo htmlspecialchars($quiz['description'] ?? ''); ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Nota mínima (%)</label>
                    <input type="number" name="passing_score" class="form-control" value="<?php echo $quiz['passing_score']; ?>" min="0" max="100">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Tempo limite (min)</label>
                    <input type="number" name="time_limit_minutes" class="form-control" value="<?php echo $quiz['time_limit_minutes'] ?? ''; ?>" placeholder="Sem limite">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Tentativas</label>
                    <input type="number" name="attempts_allowed" class="form-control" value="<?php echo $quiz['attempts_allowed']; ?>" min="0">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Ordem</label>
                    <input type="number" name="sort_order" class="form-control" value="<?php echo $quiz['sort_order']; ?>">
                </div>
            </div>
            <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i> Salvar</button>
        </form>
    </div>
</div>

<!-- Questões -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0"><i class="fas fa-question-circle me-2"></i>Questões (<?php echo count($questions); ?>)</h5>
        <button class="btn btn-sm btn-success" data-bs-toggle="collapse" data-bs-target="#addQuestionForm"><i class="fas fa-plus me-1"></i> Adicionar Questão</button>
    </div>
    <div class="card-body">
        <!-- Form Adicionar Questão -->
        <div class="collapse mb-4" id="addQuestionForm">
            <div class="card card-body bg-light">
                <form method="POST" action="/admin/quizzes/<?php echo $quiz['id']; ?>/questions">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Pergunta *</label>
                            <textarea name="question_text" class="form-control" rows="2" required></textarea>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Tipo</label>
                            <select name="question_type" class="form-select">
                                <option value="multiple_choice">Múltipla Escolha</option>
                                <option value="true_false">V ou F</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Pontos</label>
                            <input type="number" name="points" class="form-control" value="1" min="1">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Ordem</label>
                            <input type="number" name="sort_order" class="form-control" value="<?php echo count($questions); ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Respostas</label>
                        <?php for ($i = 0; $i < 4; $i++): ?>
                        <div class="input-group mb-2">
                            <div class="input-group-text">
                                <input type="radio" name="correct_answer" value="<?php echo $i; ?>" <?php echo $i === 0 ? 'checked' : ''; ?>>
                            </div>
                            <input type="text" name="answers[]" class="form-control" placeholder="Resposta <?php echo $i + 1; ?>" <?php echo $i < 2 ? 'required' : ''; ?>>
                        </div>
                        <?php endfor; ?>
                        <small class="text-muted">Selecione o radio da resposta correta.</small>
                    </div>
                    <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i> Adicionar Questão</button>
                </form>
            </div>
        </div>

        <!-- Lista de Questões -->
        <?php if (empty($questions)): ?>
            <p class="text-muted text-center">Nenhuma questão cadastrada.</p>
        <?php else: ?>
            <?php foreach ($questions as $i => $q): ?>
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <h6 class="fw-bold"><?php echo ($i + 1); ?>. <?php echo htmlspecialchars($q['question_text']); ?></h6>
                        <div>
                            <span class="badge bg-secondary me-2"><?php echo $q['points']; ?> pt(s)</span>
                            <form method="POST" action="/admin/questions/<?php echo $q['id']; ?>/delete" class="d-inline" onsubmit="return confirm('Remover esta questão?')">
                                <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </div>
                    <?php if (!empty($q['answers'])): ?>
                    <ul class="list-unstyled mt-2 mb-0">
                        <?php foreach ($q['answers'] as $a): ?>
                        <li class="<?php echo !empty($a['is_correct']) ? 'text-success fw-bold' : ''; ?>">
                            <?php echo !empty($a['is_correct']) ? '<i class="fas fa-check-circle me-1"></i>' : '<i class="far fa-circle me-1"></i>'; ?>
                            <?php echo htmlspecialchars($a['answer_text']); ?>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
