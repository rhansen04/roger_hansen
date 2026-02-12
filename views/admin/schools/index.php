<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="fw-bold text-dark">
                <i class="fas fa-school text-primary me-2"></i> Gerenciar Escolas
            </h2>
            <p class="text-muted">Lista completa de escolas cadastradas no sistema</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="/admin/schools/create" class="btn btn-hansen">
                <i class="fas fa-plus me-2"></i> Nova Escola
            </a>
        </div>
    </div>

    <?php if (isset($_SESSION['success_message'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i>
            <?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card card-stat">
        <div class="card-body">
            <?php if (empty($schools)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-school fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">Nenhuma escola cadastrada</h5>
                    <p class="text-muted">Clique no botão "Nova Escola" para adicionar a primeira escola</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 60px;">Logo</th>
                                <th>Nome</th>
                                <th>Cidade/Estado</th>
                                <th>Contato</th>
                                <th>Telefone</th>
                                <th>Email</th>
                                <th class="text-center">Alunos</th>
                                <th class="text-center">Status</th>
                                <th class="text-center" style="width: 200px;">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($schools as $school): ?>
                                <tr>
                                    <td>
                                        <?php if (!empty($school['logo_url'])): ?>
                                            <img src="<?php echo htmlspecialchars($school['logo_url']); ?>"
                                                 alt="Logo"
                                                 class="rounded"
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="bg-secondary rounded d-flex align-items-center justify-content-center"
                                                 style="width: 40px; height: 40px;">
                                                <i class="fas fa-school text-white"></i>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <strong><?php echo htmlspecialchars($school['name']); ?></strong>
                                    </td>
                                    <td>
                                        <?php
                                        $location = [];
                                        if (!empty($school['city'])) $location[] = $school['city'];
                                        if (!empty($school['state'])) $location[] = $school['state'];
                                        echo !empty($location) ? htmlspecialchars(implode('/', $location)) : '<span class="text-muted">-</span>';
                                        ?>
                                    </td>
                                    <td>
                                        <?php echo !empty($school['contact_person']) ? htmlspecialchars($school['contact_person']) : '<span class="text-muted">-</span>'; ?>
                                    </td>
                                    <td>
                                        <?php echo !empty($school['phone']) ? htmlspecialchars($school['phone']) : '<span class="text-muted">-</span>'; ?>
                                    </td>
                                    <td>
                                        <?php if (!empty($school['email'])): ?>
                                            <a href="mailto:<?php echo htmlspecialchars($school['email']); ?>" class="text-decoration-none">
                                                <?php echo htmlspecialchars($school['email']); ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="text-muted">-</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-info">
                                            <?php echo $school['students_count']; ?> aluno<?php echo $school['students_count'] != 1 ? 's' : ''; ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <?php if ($school['status'] === 'active'): ?>
                                            <span class="badge bg-success">Ativo</span>
                                        <?php else: ?>
                                            <span class="badge bg-secondary">Inativo</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="/admin/schools/<?php echo $school['id']; ?>"
                                           class="btn btn-sm btn-info text-white me-1"
                                           title="Ver Detalhes">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="/admin/schools/<?php echo $school['id']; ?>/edit"
                                           class="btn btn-sm btn-warning text-white me-1"
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button onclick="confirmDelete(<?php echo $school['id']; ?>, '<?php echo htmlspecialchars(addslashes($school['name'])); ?>')"
                                                class="btn btn-sm btn-danger"
                                                title="Deletar">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
function confirmDelete(schoolId, schoolName) {
    if (confirm('Tem certeza que deseja deletar a escola "' + schoolName + '"?\n\nAtenção: Esta ação não pode ser desfeita!')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/admin/schools/' + schoolId + '/delete';
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
