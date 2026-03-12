<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Banco de Imagens</li>
    </ol>
</nav>

<?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i><?= $_SESSION['success_message'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['success_message']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error_message'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i><?= $_SESSION['error_message'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['error_message']); ?>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-primary fw-bold mb-0"><i class="fas fa-images me-2"></i>BANCO DE IMAGENS</h2>
</div>

<p class="text-muted mb-4">Selecione uma turma para acessar o banco de imagens com registros coletivos e individuais dos alunos.</p>

<?php if (empty($classrooms)): ?>
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="fas fa-chalkboard fa-3x text-muted mb-3"></i>
            <p class="text-muted mb-0">Nenhuma turma ativa encontrada.</p>
        </div>
    </div>
<?php else: ?>
    <div class="row g-4">
        <?php foreach ($classrooms as $classroom): ?>
        <div class="col-md-6 col-lg-4">
            <a href="/admin/image-bank/<?= $classroom['id'] ?>" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 hover-shadow" style="transition: transform .2s, box-shadow .2s;">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="rounded-circle d-flex align-items-center justify-content-center me-3"
                                 style="width:48px;height:48px;background:linear-gradient(135deg,#007e66,#00a884);">
                                <i class="fas fa-camera text-white fa-lg"></i>
                            </div>
                            <div>
                                <h5 class="fw-bold text-dark mb-0"><?= htmlspecialchars($classroom['name']) ?></h5>
                                <small class="text-muted"><?= $classroom['school_year'] ?></small>
                            </div>
                        </div>
                        <div class="d-flex gap-2 flex-wrap">
                            <span class="badge bg-light text-dark"><i class="fas fa-school me-1"></i><?= htmlspecialchars($classroom['school_name'] ?? 'Sem escola') ?></span>
                            <span class="badge bg-light text-dark"><i class="fas fa-user me-1"></i><?= htmlspecialchars($classroom['teacher_name'] ?? 'Sem professor') ?></span>
                        </div>
                    </div>
                </div>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<style>
.hover-shadow:hover {
    transform: translateY(-2px);
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.12)!important;
}
</style>
