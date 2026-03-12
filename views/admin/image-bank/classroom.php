<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/admin/image-bank" class="text-decoration-none">Banco de Imagens</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($classroom['name']) ?></li>
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
    <h2 class="text-primary fw-bold mb-0">
        <i class="fas fa-images me-2"></i><?= htmlspecialchars($classroom['name']) ?> - Pastas
    </h2>
    <a href="/admin/image-bank" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Voltar</a>
</div>

<?php
    $collectiveFolders = array_filter($folders, fn($f) => $f['folder_type'] === 'classroom');
    $studentFolders = array_filter($folders, fn($f) => $f['folder_type'] === 'student');
?>

<!-- Registros Coletivos -->
<h5 class="fw-bold mb-3"><i class="fas fa-users me-2 text-primary"></i>Registros Coletivos</h5>
<div class="row g-3 mb-4">
    <?php if (empty($collectiveFolders)): ?>
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-4 text-muted">
                    Nenhuma pasta coletiva encontrada.
                </div>
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($collectiveFolders as $folder): ?>
        <div class="col-md-4 col-lg-3">
            <a href="/admin/image-bank/folder/<?= $folder['id'] ?>" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 hover-shadow text-center p-3">
                    <i class="fas fa-folder-open fa-3x mb-2" style="color:#ffb606"></i>
                    <h6 class="fw-bold text-dark mb-1"><?= htmlspecialchars($folder['name']) ?></h6>
                    <small class="text-muted"><?= $imageCounts[$folder['id']] ?? 0 ?> imagem(ns)</small>
                </div>
            </a>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Pastas Individuais -->
<h5 class="fw-bold mb-3"><i class="fas fa-user me-2 text-primary"></i>Pastas Individuais (Alunos)</h5>
<div class="row g-3">
    <?php if (empty($studentFolders)): ?>
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-4 text-muted">
                    Nenhum aluno vinculado a esta turma.
                </div>
            </div>
        </div>
    <?php else: ?>
        <?php foreach ($studentFolders as $folder): ?>
        <div class="col-md-4 col-lg-3">
            <a href="/admin/image-bank/folder/<?= $folder['id'] ?>" class="text-decoration-none">
                <div class="card border-0 shadow-sm h-100 hover-shadow text-center p-3">
                    <i class="fas fa-user-circle fa-3x mb-2" style="color:#007e66"></i>
                    <h6 class="fw-bold text-dark mb-1"><?= htmlspecialchars($folder['student_name'] ?? $folder['name']) ?></h6>
                    <small class="text-muted"><?= $imageCounts[$folder['id']] ?? 0 ?> imagem(ns)</small>
                </div>
            </a>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<style>
.hover-shadow:hover {
    transform: translateY(-2px);
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.12)!important;
    transition: transform .2s, box-shadow .2s;
}
</style>
