<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-primary fw-bold mb-0"><i class="fas fa-user-check me-2"></i>Matrículas</h2>
    <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addEnrollmentModal"><i class="fas fa-plus me-1"></i> Nova Matrícula</button>
</div>

<?php if (!empty($_SESSION['success_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
<?php endif; ?>
<?php if (!empty($_SESSION['error_message'])): ?>
    <div class="alert alert-danger alert-dismissible fade show"><?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?></div>
<?php endif; ?>

<!-- Filtros -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" action="/admin/enrollments" class="row g-3 align-items-end">
            <div class="col-md-4">
                <label class="form-label">Curso</label>
                <select name="course_id" class="form-select">
                    <option value="">Todos os cursos</option>
                    <?php foreach ($courses as $c): ?>
                        <option value="<?php echo $c['id']; ?>" <?php echo $filterCourse == $c['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($c['title']); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select">
                    <option value="">Todos</option>
                    <option value="active" <?php echo $filterStatus === 'active' ? 'selected' : ''; ?>>Ativo</option>
                    <option value="pending" <?php echo $filterStatus === 'pending' ? 'selected' : ''; ?>>Pendente</option>
                    <option value="inactive" <?php echo $filterStatus === 'inactive' ? 'selected' : ''; ?>>Inativo</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter me-1"></i> Filtrar</button>
            </div>
            <div class="col-md-2">
                <a href="/admin/enrollments" class="btn btn-outline-secondary w-100">Limpar</a>
            </div>
        </form>
    </div>
</div>

<!-- Tabela -->
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="mb-2 text-muted"><strong><?php echo count($enrollments); ?></strong> matrícula(s) encontrada(s)</div>
        <?php if (empty($enrollments)): ?>
            <p class="text-muted text-center py-3">Nenhuma matrícula encontrada.</p>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Aluno</th>
                        <th>Curso</th>
                        <th>Status</th>
                        <th>Pagamento</th>
                        <th>Progresso</th>
                        <th>Data</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($enrollments as $e): ?>
                    <tr>
                        <td><?php echo $e['id']; ?></td>
                        <td>
                            <strong><?php echo htmlspecialchars($e['student_name']); ?></strong>
                            <br><small class="text-muted"><?php echo htmlspecialchars($e['student_email']); ?></small>
                        </td>
                        <td><?php echo htmlspecialchars($e['course_title']); ?></td>
                        <td>
                            <?php
                            $statusBadge = ['active' => 'bg-success', 'pending' => 'bg-warning text-dark', 'inactive' => 'bg-secondary'];
                            $statusLabel = ['active' => 'Ativo', 'pending' => 'Pendente', 'inactive' => 'Inativo'];
                            $s = $e['status'];
                            ?>
                            <span class="badge <?php echo $statusBadge[$s] ?? 'bg-secondary'; ?>"><?php echo $statusLabel[$s] ?? $s; ?></span>
                            <?php if (!empty($e['is_course_completed'])): ?>
                                <span class="badge bg-primary">Concluído</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php
                            $payBadge = ['paid' => 'bg-success', 'free' => 'bg-info', 'pending' => 'bg-warning text-dark'];
                            $p = $e['payment_status'] ?? 'pending';
                            ?>
                            <span class="badge <?php echo $payBadge[$p] ?? 'bg-secondary'; ?>"><?php echo ucfirst($p); ?></span>
                        </td>
                        <td>
                            <div class="progress" style="height: 18px; min-width: 80px;">
                                <div class="progress-bar bg-info" style="width: <?php echo $e['overall_progress_percentage'] ?? 0; ?>%">
                                    <?php echo round($e['overall_progress_percentage'] ?? 0); ?>%
                                </div>
                            </div>
                        </td>
                        <td><small><?php echo date('d/m/Y', strtotime($e['enrollment_date'] ?? $e['created_at'] ?? 'now')); ?></small></td>
                        <td>
                            <?php if ($e['status'] !== 'active'): ?>
                            <form method="POST" action="/admin/enrollments/<?php echo $e['id']; ?>/activate" class="d-inline">
                                <button class="btn btn-sm btn-outline-success" title="Ativar"><i class="fas fa-check"></i></button>
                            </form>
                            <?php endif; ?>
                            <?php if ($e['status'] === 'active'): ?>
                            <form method="POST" action="/admin/enrollments/<?php echo $e['id']; ?>/deactivate" class="d-inline">
                                <button class="btn btn-sm btn-outline-warning" title="Desativar"><i class="fas fa-pause"></i></button>
                            </form>
                            <?php endif; ?>
                            <form method="POST" action="/admin/enrollments/<?php echo $e['id']; ?>/delete" class="d-inline" onsubmit="return confirm('Remover esta matrícula?')">
                                <button class="btn btn-sm btn-outline-danger" title="Remover"><i class="fas fa-trash"></i></button>
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

<!-- Modal Criar Matrícula -->
<div class="modal fade" id="addEnrollmentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/admin/enrollments/store">
                <div class="modal-header">
                    <h5 class="modal-title">Nova Matrícula</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Aluno (ID do usuário) *</label>
                        <input type="number" name="user_id" class="form-control" required placeholder="ID do aluno">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Curso *</label>
                        <select name="course_id" class="form-select" required>
                            <option value="">Selecione...</option>
                            <?php foreach ($courses as $c): ?>
                                <option value="<?php echo $c['id']; ?>"><?php echo htmlspecialchars($c['title']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Status</label>
                        <select name="status" class="form-select">
                            <option value="active">Ativo</option>
                            <option value="pending">Pendente</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Criar Matrícula</button>
                </div>
            </form>
        </div>
    </div>
</div>
