<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-primary fw-bold mb-0">Quizzes - <?php echo htmlspecialchars($course['title']); ?></h2>
    <a href="/admin/courses/<?php echo $course['id']; ?>" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Voltar</a>
</div>

<?php if (!empty($_SESSION['success_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
<?php endif; ?>
<?php if (!empty($_SESSION['error_message'])): ?>
    <div class="alert alert-danger alert-dismissible fade show"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
<?php endif; ?>

<!-- Criar Quiz -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white"><h5 class="mb-0"><i class="fas fa-plus me-2"></i>Novo Quiz</h5></div>
    <div class="card-body">
        <form method="POST" action="/admin/courses/<?php echo $course['id']; ?>/quizzes">
            <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Seção *</label>
                    <select name="section_id" class="form-select" required>
                        <option value="">Selecione...</option>
                        <?php foreach ($sections as $s): ?>
                            <option value="<?php echo $s['id']; ?>"><?php echo htmlspecialchars($s['title']); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Título *</label>
                    <input type="text" name="title" class="form-control" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Descrição</label>
                    <input type="text" name="description" class="form-control">
                </div>
            </div>
            <div class="row">
                <div class="col-md-3 mb-3">
                    <label class="form-label">Nota mínima (%)</label>
                    <input type="number" name="passing_score" class="form-control" value="70" min="0" max="100">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Tempo limite (min)</label>
                    <input type="number" name="time_limit_minutes" class="form-control" placeholder="Sem limite">
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Tentativas</label>
                    <input type="number" name="attempts_allowed" class="form-control" value="3" min="0">
                    <small class="text-muted">0 = ilimitado</small>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label">Ordem</label>
                    <input type="number" name="sort_order" class="form-control" value="0">
                </div>
            </div>
            <button type="submit" class="btn btn-success"><i class="fas fa-save me-1"></i> Criar Quiz</button>
        </form>
    </div>
</div>

<!-- Lista de Quizzes -->
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <?php if (empty($quizzes)): ?>
            <p class="text-muted text-center py-3">Nenhum quiz cadastrado.</p>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Título</th>
                            <th>Seção</th>
                            <th>Questões</th>
                            <th>Nota Mínima</th>
                            <th>Tentativas</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($quizzes as $q): ?>
                        <tr>
                            <td><?php echo $q['id']; ?></td>
                            <td><?php echo htmlspecialchars($q['title']); ?></td>
                            <td><span class="badge bg-info"><?php echo htmlspecialchars($q['section_title']); ?></span></td>
                            <td><?php echo $q['questions_count']; ?></td>
                            <td><?php echo $q['passing_score']; ?>%</td>
                            <td><?php echo $q['attempts_allowed'] == 0 ? 'Ilimitado' : $q['attempts_allowed']; ?></td>
                            <td>
                                <a href="/admin/quizzes/<?php echo $q['id']; ?>/edit" class="btn btn-sm btn-outline-primary"><i class="fas fa-edit"></i></a>
                                <form method="POST" action="/admin/quizzes/<?php echo $q['id']; ?>/delete" class="d-inline" onsubmit="return confirm('Deletar este quiz?')">
                                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token'] ?? ''; ?>">
                                    <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>
