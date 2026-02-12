<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="fw-bold text-dark">
                <i class="fas fa-users text-primary me-2"></i> Gerenciar Usuários
            </h2>
            <p class="text-muted">Lista completa de usuários cadastrados no sistema</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="/admin/users/create" class="btn btn-hansen">
                <i class="fas fa-plus me-2"></i> Novo Usuário
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
            <?php if (empty($users)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-users fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">Nenhum usuário cadastrado</h5>
                    <p class="text-muted">Clique no botão "Novo Usuário" para adicionar o primeiro usuário</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Nome</th>
                                <th>Email</th>
                                <th class="text-center">Perfil</th>
                                <th class="text-center">Último Acesso</th>
                                <th class="text-center" style="width: 150px;">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($users as $user): ?>
                                <tr>
                                    <td>
                                        <strong><?php echo htmlspecialchars($user['name']); ?></strong>
                                        <?php if ($user['id'] == $_SESSION['user_id']): ?>
                                            <span class="badge bg-info ms-2">Você</span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <a href="mailto:<?php echo htmlspecialchars($user['email']); ?>" class="text-decoration-none">
                                            <?php echo htmlspecialchars($user['email']); ?>
                                        </a>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        $roleBadges = [
                                            'admin' => '<span class="badge bg-success"><i class="fas fa-user-shield me-1"></i>Administrador</span>',
                                            'professor' => '<span class="badge bg-primary"><i class="fas fa-chalkboard-teacher me-1"></i>Professor</span>',
                                            'coordenador' => '<span class="badge bg-warning text-dark"><i class="fas fa-user-tie me-1"></i>Coordenador</span>'
                                        ];
                                        echo $roleBadges[$user['role']] ?? '<span class="badge bg-secondary">Indefinido</span>';
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <?php
                                        if (!empty($user['last_login'])) {
                                            $lastLogin = new DateTime($user['last_login']);
                                            $now = new DateTime();
                                            $diff = $now->diff($lastLogin);

                                            if ($diff->days == 0) {
                                                if ($diff->h == 0) {
                                                    echo '<span class="text-success">' . $diff->i . ' min atrás</span>';
                                                } else {
                                                    echo '<span class="text-success">' . $diff->h . 'h atrás</span>';
                                                }
                                            } elseif ($diff->days == 1) {
                                                echo '<span class="text-muted">Ontem</span>';
                                            } elseif ($diff->days <= 7) {
                                                echo '<span class="text-muted">' . $diff->days . ' dias atrás</span>';
                                            } else {
                                                echo '<span class="text-muted">' . $lastLogin->format('d/m/Y') . '</span>';
                                            }
                                        } else {
                                            echo '<span class="text-muted">Nunca</span>';
                                        }
                                        ?>
                                    </td>
                                    <td class="text-center">
                                        <a href="/admin/users/<?php echo $user['id']; ?>/edit"
                                           class="btn btn-sm btn-warning text-white me-1"
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <?php if ($user['id'] != $_SESSION['user_id']): ?>
                                            <button onclick="confirmDelete(<?php echo $user['id']; ?>, '<?php echo htmlspecialchars(addslashes($user['name'])); ?>')"
                                                    class="btn btn-sm btn-danger"
                                                    title="Deletar">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php else: ?>
                                            <button class="btn btn-sm btn-secondary" disabled title="Não é possível deletar seu próprio usuário">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        <?php endif; ?>
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
function confirmDelete(userId, userName) {
    if (confirm('Tem certeza que deseja deletar o usuário "' + userName + '"?\n\nAtenção: Esta ação não pode ser desfeita!')) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '/admin/users/' + userId + '/delete';
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
