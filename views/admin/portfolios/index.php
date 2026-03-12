<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Portfolios</li>
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
    <h2 class="text-primary fw-bold mb-0"><i class="fas fa-book-open me-2"></i>PORTFOLIOS DA TURMA</h2>
    <a href="/admin/portfolios/create" class="btn btn-hansen text-white">
        <i class="fas fa-plus me-2"></i>Novo Portfolio
    </a>
</div>

<?php if (empty($portfolios)): ?>
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
            <p class="text-muted mb-0">Nenhum portfolio criado ainda.</p>
            <a href="/admin/portfolios/create" class="btn btn-hansen text-white mt-3">
                <i class="fas fa-plus me-2"></i>Criar primeiro portfolio
            </a>
        </div>
    </div>
<?php else: ?>
    <div class="row g-4">
        <?php foreach ($portfolios as $portfolio): ?>
        <?php
            $statusColors = [
                'pending' => ['bg' => 'warning', 'icon' => 'clock', 'label' => 'Pendente'],
                'finalized' => ['bg' => 'success', 'icon' => 'check-circle', 'label' => 'Finalizado'],
                'revision_requested' => ['bg' => 'danger', 'icon' => 'exclamation-circle', 'label' => 'Revisao Solicitada']
            ];
            $status = $statusColors[$portfolio['status']] ?? $statusColors['pending'];
        ?>
        <div class="col-md-6 col-lg-4">
            <div class="card border-0 shadow-sm h-100">
                <?php if ($portfolio['cover_photo_url']): ?>
                <img src="<?= htmlspecialchars($portfolio['cover_photo_url']) ?>" class="card-img-top" style="height:160px;object-fit:cover;" alt="Capa">
                <?php else: ?>
                <div class="card-img-top d-flex align-items-center justify-content-center" style="height:160px;background:linear-gradient(135deg,#007e66,#00a884);">
                    <i class="fas fa-book-open fa-4x text-white opacity-50"></i>
                </div>
                <?php endif; ?>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <h5 class="fw-bold text-dark mb-0"><?= htmlspecialchars($portfolio['classroom_name']) ?></h5>
                        <span class="badge bg-<?= $status['bg'] ?>">
                            <i class="fas fa-<?= $status['icon'] ?> me-1"></i><?= $status['label'] ?>
                        </span>
                    </div>
                    <p class="text-muted mb-2">
                        <i class="fas fa-calendar me-1"></i><?= $portfolio['semester'] ?>o Semestre / <?= $portfolio['year'] ?>
                    </p>
                    <p class="text-muted small mb-0">
                        <i class="fas fa-school me-1"></i><?= htmlspecialchars($portfolio['school_name'] ?? '') ?>
                        <span class="mx-1">|</span>
                        <i class="fas fa-user me-1"></i><?= htmlspecialchars($portfolio['teacher_name'] ?? '') ?>
                    </p>
                </div>
                <div class="card-footer bg-transparent border-0 p-3 pt-0">
                    <div class="btn-group w-100">
                        <a href="/admin/portfolios/<?= $portfolio['id'] ?>" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-eye me-1"></i>Ver
                        </a>
                        <?php if ($portfolio['status'] !== 'finalized'): ?>
                        <a href="/admin/portfolios/<?= $portfolio['id'] ?>/edit" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-edit me-1"></i>Editar
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>
