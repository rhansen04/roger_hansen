<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Observacoes Pedagogicas</li>
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
        <h2 class="fw-bold mb-0" style="color: var(--primary-color, #007e66);">
            <i class="fas fa-clipboard-list me-2"></i>OBSERVACOES PEDAGOGICAS
        </h2>
        <small class="text-muted">
            <?php if ($userRole === 'professor'): ?>
                Minhas observacoes
            <?php else: ?>
                Todas as observacoes
            <?php endif; ?>
        </small>
    </div>
    <?php if ($userRole !== 'coordenador'): ?>
        <a href="/admin/observations/create" class="btn btn-hansen text-white text-decoration-none">
            <i class="fas fa-plus me-2"></i> Nova Observacao
        </a>
    <?php endif; ?>
</div>

<!-- Filtros -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form method="GET" action="/admin/observations" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-bold mb-1">Aluno</label>
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
                <label class="form-label small fw-bold mb-1">Semestre</label>
                <select name="semester" class="form-select form-select-sm">
                    <option value="">Todos</option>
                    <option value="1" <?php echo ($filters['semester'] == '1') ? 'selected' : ''; ?>>1o Semestre</option>
                    <option value="2" <?php echo ($filters['semester'] == '2') ? 'selected' : ''; ?>>2o Semestre</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold mb-1">Ano</label>
                <select name="year" class="form-select form-select-sm">
                    <option value="">Todos</option>
                    <?php foreach ($years as $y): ?>
                        <option value="<?php echo $y; ?>" <?php echo ($filters['year'] == $y) ? 'selected' : ''; ?>>
                            <?php echo $y; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold mb-1">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">Todos</option>
                    <option value="in_progress" <?php echo ($filters['status'] == 'in_progress') ? 'selected' : ''; ?>>Em andamento</option>
                    <option value="finalized" <?php echo ($filters['status'] == 'finalized') ? 'selected' : ''; ?>>Finalizado</option>
                </select>
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

<!-- Tabela de Observacoes -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">Aluno</th>
                        <th class="py-3">Semestre / Ano</th>
                        <th class="py-3">Professor</th>
                        <th class="py-3">Status</th>
                        <th class="py-3">Atualizado em</th>
                        <th class="py-3 text-center">Acoes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($observations)): ?>
                        <tr>
                            <td colspan="6" class="text-center py-5 text-muted">
                                <i class="fas fa-clipboard-list fa-3x mb-3 d-block"></i>
                                Nenhuma observacao encontrada.
                                <?php if ($userRole !== 'coordenador'): ?>
                                    <br><a href="/admin/observations/create" class="btn btn-sm btn-outline-primary mt-3">
                                        <i class="fas fa-plus me-1"></i> Criar primeira observacao
                                    </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($observations as $obs): ?>
                        <tr>
                            <td class="ps-4">
                                <a href="/admin/students/<?php echo $obs['student_id']; ?>" class="text-decoration-none fw-bold" style="color: var(--primary-color, #007e66);">
                                    <i class="fas fa-user-graduate me-1"></i>
                                    <?php echo htmlspecialchars($obs['student_name']); ?>
                                </a>
                            </td>
                            <td>
                                <?php if ($obs['semester'] && $obs['year']): ?>
                                    <span class="badge bg-light text-dark border">
                                        <?php echo $obs['semester']; ?>o Sem / <?php echo $obs['year']; ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted small">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="small">
                                <i class="fas fa-user-tie me-1"></i>
                                <?php echo htmlspecialchars($obs['teacher_name']); ?>
                            </td>
                            <td>
                                <?php
                                $status = $obs['status'] ?? 'in_progress';
                                if ($status === 'finalized'):
                                ?>
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i> Finalizado
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-edit me-1"></i> Em andamento
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="small text-muted">
                                <i class="fas fa-clock me-1"></i>
                                <?php echo date('d/m/Y H:i', strtotime($obs['updated_at'])); ?>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="/admin/observations/<?php echo $obs['id']; ?>" class="btn btn-sm btn-outline-primary" title="Ver Detalhes">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <?php if ($status !== 'finalized' && $userRole !== 'coordenador'): ?>
                                        <a href="/admin/observations/<?php echo $obs['id']; ?>/edit" class="btn btn-sm btn-outline-secondary" title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                    <?php endif; ?>
                                    <?php if ($status !== 'finalized' && $userRole !== 'coordenador'): ?>
                                        <button onclick="confirmDelete(<?php echo $obs['id']; ?>, '<?php echo htmlspecialchars(addslashes($obs['student_name'])); ?>')" class="btn btn-sm btn-outline-danger" title="Excluir">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    <?php endif; ?>
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
    if (confirm('Tem certeza que deseja excluir a observacao do aluno "' + studentName + '"?\n\nAtencao: Esta acao nao pode ser desfeita.')) {
        const form = document.getElementById('deleteForm');
        form.action = '/admin/observations/' + id + '/delete';
        form.submit();
    }
}
</script>
