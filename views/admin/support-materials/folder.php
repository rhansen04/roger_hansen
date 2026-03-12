<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/admin/support-materials" class="text-decoration-none">Material de Apoio</a></li>
        <?php foreach ($breadcrumb as $i => $bc): ?>
            <?php if ($i < count($breadcrumb) - 1): ?>
            <li class="breadcrumb-item"><a href="/admin/support-materials/folder/<?= $bc['id'] ?>" class="text-decoration-none"><?= htmlspecialchars($bc['name']) ?></a></li>
            <?php else: ?>
            <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($bc['name']) ?></li>
            <?php endif; ?>
        <?php endforeach; ?>
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

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <h2 class="text-primary fw-bold mb-0">
        <i class="fas fa-folder-open me-2"></i><?= htmlspecialchars($folder['name']) ?>
    </h2>
    <div class="d-flex gap-2">
        <?php if ($canUpload): ?>
        <button class="btn btn-hansen text-white" data-bs-toggle="modal" data-bs-target="#uploadModal">
            <i class="fas fa-upload me-2"></i>Enviar Arquivo
        </button>
        <?php endif; ?>
        <?php
            $backUrl = $folder['parent_id']
                ? '/admin/support-materials/folder/' . $folder['parent_id']
                : '/admin/support-materials';
        ?>
        <a href="<?= $backUrl ?>" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Voltar</a>
    </div>
</div>

<!-- Subpastas -->
<?php if (!empty($subfolders)): ?>
<div class="row g-3 mb-4">
    <?php foreach ($subfolders as $sub): ?>
    <div class="col-md-4 col-lg-3">
        <a href="/admin/support-materials/folder/<?= $sub['id'] ?>" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100 hover-shadow text-center p-3">
                <i class="fas fa-folder fa-3x mb-2" style="color:#ffb606"></i>
                <h6 class="fw-bold text-dark mb-0"><?= htmlspecialchars($sub['name']) ?></h6>
            </div>
        </a>
    </div>
    <?php endforeach; ?>
</div>
<?php endif; ?>

<!-- Materiais -->
<?php if (empty($materials) && empty($subfolders)): ?>
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="fas fa-file fa-3x text-muted mb-3"></i>
            <p class="text-muted mb-0">Nenhum material nesta pasta.</p>
            <?php if ($canUpload): ?>
            <button class="btn btn-hansen text-white mt-3" data-bs-toggle="modal" data-bs-target="#uploadModal">
                <i class="fas fa-upload me-2"></i>Enviar primeiro arquivo
            </button>
            <?php endif; ?>
        </div>
    </div>
<?php elseif (!empty($materials)): ?>
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3">Arquivo</th>
                            <th class="py-3">Tamanho</th>
                            <th class="py-3">Enviado por</th>
                            <th class="py-3">Data</th>
                            <th class="py-3 text-center">Acoes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($materials as $material): ?>
                        <?php
                            $ext = strtolower(pathinfo($material['original_name'], PATHINFO_EXTENSION));
                            $iconMap = [
                                'pdf' => 'fa-file-pdf text-danger',
                                'doc' => 'fa-file-word text-primary',
                                'docx' => 'fa-file-word text-primary',
                                'xls' => 'fa-file-excel text-success',
                                'xlsx' => 'fa-file-excel text-success',
                                'ppt' => 'fa-file-powerpoint text-warning',
                                'pptx' => 'fa-file-powerpoint text-warning',
                                'jpg' => 'fa-file-image text-info',
                                'jpeg' => 'fa-file-image text-info',
                                'png' => 'fa-file-image text-info',
                                'zip' => 'fa-file-archive text-secondary',
                                'rar' => 'fa-file-archive text-secondary',
                                'mp4' => 'fa-file-video text-purple',
                                'mp3' => 'fa-file-audio text-pink'
                            ];
                            $iconClass = $iconMap[$ext] ?? 'fa-file text-muted';
                            $fileSize = $material['file_size'] ?? 0;
                            if ($fileSize > 1048576) {
                                $sizeStr = round($fileSize / 1048576, 1) . ' MB';
                            } elseif ($fileSize > 1024) {
                                $sizeStr = round($fileSize / 1024) . ' KB';
                            } else {
                                $sizeStr = $fileSize . ' B';
                            }
                        ?>
                        <tr>
                            <td class="ps-4">
                                <i class="fas <?= $iconClass ?> me-2 fa-lg"></i>
                                <span class="fw-semibold"><?= htmlspecialchars($material['title']) ?></span>
                                <br><small class="text-muted"><?= htmlspecialchars($material['original_name']) ?></small>
                            </td>
                            <td><?= $sizeStr ?></td>
                            <td><?= htmlspecialchars($material['uploaded_by_name'] ?? '-') ?></td>
                            <td><?= date('d/m/Y', strtotime($material['created_at'])) ?></td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="/admin/support-materials/<?= $material['id'] ?>/download" class="btn btn-sm btn-outline-primary" title="Download">
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <?php if ($canUpload): ?>
                                    <button onclick="confirmDeleteMaterial(<?= $material['id'] ?>, '<?= htmlspecialchars(addslashes($material['title'])) ?>')"
                                            class="btn btn-sm btn-outline-danger" title="Excluir">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php if ($canUpload): ?>
<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/admin/support-materials/folder/<?= $folder['id'] ?>/upload" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-upload me-2"></i>Enviar Arquivo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Titulo do Material</label>
                        <input type="text" name="title" class="form-control" placeholder="Deixe vazio para usar o nome do arquivo">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Arquivo <span class="text-danger">*</span></label>
                        <input type="file" name="file" class="form-control" required>
                        <div class="form-text">PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, JPG, PNG, ZIP, MP4, MP3</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-hansen text-white"><i class="fas fa-upload me-2"></i>Enviar</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Delete Form -->
<form id="deleteMaterialForm" method="POST" style="display:none;"></form>

<script>
function confirmDeleteMaterial(id, title) {
    if (confirm('Tem certeza que deseja excluir o material "' + title + '"?\n\nEsta acao nao pode ser desfeita.')) {
        const form = document.getElementById('deleteMaterialForm');
        form.action = '/admin/support-materials/' + id + '/delete';
        form.submit();
    }
}
</script>

<style>
.hover-shadow:hover {
    transform: translateY(-2px);
    box-shadow: 0 .5rem 1rem rgba(0,0,0,.12)!important;
    transition: transform .2s, box-shadow .2s;
}
</style>
