<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="/admin/users">Usuários</a></li>
                    <li class="breadcrumb-item active">Editar Usuário</li>
                </ol>
            </nav>
            <h2 class="fw-bold text-dark">
                <i class="fas fa-user-edit text-primary me-2"></i> Editar Usuário
            </h2>
            <p class="text-muted">Atualize as informações do usuário <strong><?php echo htmlspecialchars($user['name']); ?></strong></p>
        </div>
    </div>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="card card-stat">
        <div class="card-body">
            <form method="POST" action="/admin/users/<?php echo $user['id']; ?>/update">
                <?php include __DIR__ . '/_form.php'; ?>
            </form>
        </div>
    </div>
</div>
