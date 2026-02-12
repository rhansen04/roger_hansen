<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item active">Cursos</li>
    </ol>
</nav>

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
    <h2 class="text-primary fw-bold mb-0">GESTÃO DE CURSOS</h2>
    <a href="/admin/courses/create" class="btn btn-hansen text-white text-decoration-none"><i class="fas fa-plus me-2"></i> Novo Curso</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3">ID</th>
                        <th class="py-3">Título</th>
                        <th class="py-3">Instrutor</th>
                        <th class="py-3 text-center">Seções</th>
                        <th class="py-3 text-center">Lições</th>
                        <th class="py-3 text-center">Alunos</th>
                        <th class="py-3 text-center">Status</th>
                        <th class="py-3 text-center">Preço</th>
                        <th class="py-3 text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($courses)): ?>
                        <tr>
                            <td colspan="9" class="text-center py-5 text-muted">
                                <i class="fas fa-book fa-3x mb-3"></i><br>
                                Nenhum curso cadastrado.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($courses as $course): ?>
                        <tr>
                            <td class="ps-4"><?php echo $course['id']; ?></td>
                            <td class="fw-bold text-primary"><?php echo htmlspecialchars($course['title']); ?></td>
                            <td><?php echo htmlspecialchars($course['instructor_name'] ?? 'N/A'); ?></td>
                            <td class="text-center"><span class="badge bg-info"><?php echo $course['sections_count']; ?></span></td>
                            <td class="text-center"><span class="badge bg-secondary"><?php echo $course['lessons_count']; ?></span></td>
                            <td class="text-center"><span class="badge bg-primary"><?php echo $course['enrollments_count']; ?></span></td>
                            <td class="text-center">
                                <?php if ($course['is_active']): ?>
                                    <span class="badge bg-success">Ativo</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Inativo</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <?php if ($course['is_free']): ?>
                                    <span class="badge bg-success">Gratuito</span>
                                <?php else: ?>
                                    R$ <?php echo number_format($course['price'], 2, ',', '.'); ?>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="/admin/courses/<?php echo $course['id']; ?>" class="btn btn-sm btn-outline-primary" title="Ver Detalhes"><i class="fas fa-eye"></i></a>
                                    <a href="/admin/courses/<?php echo $course['id']; ?>/edit" class="btn btn-sm btn-outline-secondary" title="Editar"><i class="fas fa-edit"></i></a>
                                    <button onclick="confirmDelete(<?php echo $course['id']; ?>, '<?php echo htmlspecialchars($course['title']); ?>')" class="btn btn-sm btn-outline-danger" title="Excluir"><i class="fas fa-trash"></i></button>
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

<form id="deleteForm" method="POST" style="display: none;"></form>

<script>
function confirmDelete(id, name) {
    if (confirm(`Tem certeza que deseja excluir o curso "${name}"?\n\nAtenção: Esta ação não pode ser desfeita.`)) {
        const form = document.getElementById('deleteForm');
        form.action = `/admin/courses/${id}/delete`;
        form.submit();
    }
}
</script>
