<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <h2 class="fw-bold text-dark">
                <i class="fas fa-edit text-primary me-2"></i> Editar Escola
            </h2>
            <p class="text-muted">Atualize as informações da escola <strong><?php echo htmlspecialchars($school['name']); ?></strong></p>
        </div>
    </div>

    <?php if (isset($_SESSION['error_message'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle me-2"></i>
            <?php echo $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <form method="POST" action="/admin/schools/<?php echo $school['id']; ?>/update" enctype="multipart/form-data">
        <?php include __DIR__ . '/_form.php'; ?>
    </form>
</div>
