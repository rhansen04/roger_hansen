<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-decoration-none">Dashboard</a></li>
        <?php if (isset($student)): ?>
            <li class="breadcrumb-item"><a href="/admin/students" class="text-decoration-none">Alunos</a></li>
            <li class="breadcrumb-item"><a href="/admin/students/<?php echo $student['id']; ?>" class="text-decoration-none"><?php echo htmlspecialchars($student['name']); ?></a></li>
            <li class="breadcrumb-item active" aria-current="page">Observações</li>
        <?php else: ?>
            <li class="breadcrumb-item active" aria-current="page">Observações Pedagógicas</li>
        <?php endif; ?>
    </ol>
</nav>

<!-- Mensagens de Sucesso/Erro -->
<?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i><?php echo $_SESSION['success_message']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['success_message']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error_message'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i><?php echo $_SESSION['error_message']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['error_message']); ?>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <?php if (isset($student)): ?>
            <a href="/admin/students/<?php echo $student['id']; ?>" class="text-decoration-none text-muted mb-2 d-block">
                <i class="fas fa-arrow-left me-2"></i> Voltar para perfil do aluno
            </a>
            <h2 class="text-primary fw-bold mb-0">OBSERVAÇÕES: <?php echo strtoupper(htmlspecialchars($student['name'])); ?></h2>
        <?php else: ?>
            <h2 class="text-primary fw-bold mb-0">GESTÃO DE OBSERVAÇÕES PEDAGÓGICAS</h2>
        <?php endif; ?>
    </div>
    <a href="/admin/observations/create<?php echo isset($student) ? '?student_id=' . $student['id'] : ''; ?>" class="btn btn-hansen text-white text-decoration-none">
        <i class="fas fa-plus me-2"></i> Nova Observação
    </a>
</div>

<!-- Filtros -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form method="GET" action="/admin/observations" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-bold mb-1">Filtrar por Aluno</label>
                <select name="student_id" class="form-select form-select-sm">
                    <option value="">Todos os alunos</option>
                    <?php foreach ($students as $s): ?>
                        <option value="<?php echo $s['id']; ?>" <?php echo ($filters['student_id'] == $s['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($s['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold mb-1">Categoria</label>
                <select name="category" class="form-select form-select-sm">
                    <option value="">Todas</option>
                    <option value="Comportamento" <?php echo ($filters['category'] == 'Comportamento') ? 'selected' : ''; ?>>Comportamento</option>
                    <option value="Aprendizado" <?php echo ($filters['category'] == 'Aprendizado') ? 'selected' : ''; ?>>Aprendizado</option>
                    <option value="Saúde" <?php echo ($filters['category'] == 'Saúde') ? 'selected' : ''; ?>>Saúde</option>
                    <option value="Comunicação com Pais" <?php echo ($filters['category'] == 'Comunicação com Pais') ? 'selected' : ''; ?>>Comunicação com Pais</option>
                    <option value="Geral" <?php echo ($filters['category'] == 'Geral') ? 'selected' : ''; ?>>Geral</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold mb-1">Data Inicial</label>
                <input type="date" name="date_from" class="form-control form-control-sm" value="<?php echo $filters['date_from'] ?? ''; ?>">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold mb-1">Data Final</label>
                <input type="date" name="date_to" class="form-control form-control-sm" value="<?php echo $filters['date_to'] ?? ''; ?>">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary btn-sm me-2">
                    <i class="fas fa-filter me-1"></i> Filtrar
                </button>
                <a href="/admin/observations" class="btn btn-light btn-sm">
                    <i class="fas fa-times me-1"></i> Limpar
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Tabela de Observações -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">ID</th>
                        <th class="py-3">Data</th>
                        <th class="py-3">Aluno</th>
                        <th class="py-3">Categoria</th>
                        <th class="py-3">Conteúdo</th>
                        <th class="py-3">Professor</th>
                        <th class="py-3 text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($observations)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fas fa-clipboard-list fa-3x mb-3"></i><br>
                                Nenhuma observação cadastrada<?php echo !empty($filters['student_id']) || !empty($filters['category']) ? ' com os filtros selecionados' : ''; ?>.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($observations as $obs): ?>
                        <tr>
                            <td class="ps-4"><?php echo $obs['id']; ?></td>
                            <td><?php echo date('d/m/Y', strtotime($obs['observation_date'])); ?></td>
                            <td>
                                <a href="/admin/students/<?php echo $obs['student_id']; ?>" class="text-decoration-none fw-bold text-primary">
                                    <?php echo htmlspecialchars($obs['student_name']); ?>
                                </a>
                            </td>
                            <td>
                                <?php
                                $badgeColors = [
                                    'Comportamento' => 'primary',
                                    'Aprendizado' => 'success',
                                    'Saúde' => 'danger',
                                    'Comunicação com Pais' => 'warning',
                                    'Geral' => 'secondary'
                                ];
                                $color = $badgeColors[$obs['category']] ?? 'secondary';
                                ?>
                                <span class="badge bg-<?php echo $color; ?>">
                                    <?php echo htmlspecialchars($obs['category']); ?>
                                </span>
                            </td>
                            <td>
                                <div class="text-truncate" style="max-width: 300px;">
                                    <strong><?php echo htmlspecialchars($obs['title']); ?></strong>
                                    <?php if (!empty($obs['description'])): ?>
                                        <br><small class="text-muted"><?php echo htmlspecialchars(substr($obs['description'], 0, 60)) . (strlen($obs['description']) > 60 ? '...' : ''); ?></small>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="small">
                                <i class="fas fa-user-tie me-1"></i>
                                <?php echo htmlspecialchars($obs['teacher_name']); ?>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="/admin/observations/<?php echo $obs['id']; ?>" class="btn btn-sm btn-outline-primary" title="Ver Detalhes">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="/admin/observations/<?php echo $obs['id']; ?>/edit" class="btn btn-sm btn-outline-secondary" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="confirmDelete(<?php echo $obs['id']; ?>, '<?php echo htmlspecialchars($obs['student_name']); ?>')" class="btn btn-sm btn-outline-danger" title="Excluir">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Form oculto para delete -->
<form id="deleteForm" method="POST" style="display: none;">
    <input type="hidden" name="_method" value="DELETE">
</form>

<script>
function confirmDelete(id, studentName) {
    if (confirm(`Tem certeza que deseja excluir esta observação do aluno "${studentName}"?\n\nAtenção: Esta ação não pode ser desfeita.`)) {
        const form = document.getElementById('deleteForm');
        form.action = `/admin/observations/${id}/delete`;
        form.submit();
    }
}
</script>
