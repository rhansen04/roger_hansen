<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Alunos</li>
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
    <h2 class="text-primary fw-bold mb-0">GESTÃO DE ALUNOS</h2>
    <a href="/admin/students/create" class="btn btn-hansen text-white text-decoration-none"><i class="fas fa-plus me-2"></i> Novo Aluno</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">ID</th>
                        <th class="py-3">Nome do Aluno</th>
                        <th class="py-3">Data de Nasc.</th>
                        <th class="py-3">Escola</th>
                        <th class="py-3 text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($students)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fas fa-user-slash fa-3x mb-3"></i><br>
                                Nenhum aluno cadastrado no momento.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($students as $student): ?>
                        <tr>
                            <td class="ps-4"><?php echo $student['id']; ?></td>
                            <td class="fw-bold text-primary"><?php echo $student['name']; ?></td>
                            <td><?php echo date('d/m/Y', strtotime($student['birth_date'])); ?></td>
                            <td><?php echo $student['school_name'] ?? 'Não vinculada'; ?></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="/admin/students/<?php echo $student['id']; ?>" class="btn btn-sm btn-outline-primary" title="Ver Detalhes"><i class="fas fa-eye"></i></a>
                                    <a href="/admin/students/<?php echo $student['id']; ?>/edit" class="btn btn-sm btn-outline-secondary" title="Editar"><i class="fas fa-edit"></i></a>
                                    <button onclick="confirmDelete(<?php echo $student['id']; ?>, '<?php echo htmlspecialchars($student['name']); ?>')" class="btn btn-sm btn-outline-danger" title="Excluir"><i class="fas fa-trash"></i></button>
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
function confirmDelete(id, name) {
    if (confirm(`Tem certeza que deseja excluir o aluno "${name}"?\n\nAtenção: Esta ação não pode ser desfeita.`)) {
        const form = document.getElementById('deleteForm');
        form.action = `/admin/students/${id}/delete`;
        form.submit();
    }
}
</script>
