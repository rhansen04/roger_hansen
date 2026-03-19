<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/admin/image-bank" class="text-decoration-none">Banco de Imagens</a></li>
        <li class="breadcrumb-item"><a href="/admin/image-bank/<?= $folder['classroom_id'] ?>" class="text-decoration-none"><?= htmlspecialchars($folder['classroom_name'] ?? 'Turma') ?></a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($folder['name']) ?></li>
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
        <i class="fas fa-<?= $folder['folder_type'] === 'classroom' ? 'users' : 'user' ?> me-2"></i>
        <?= htmlspecialchars($folder['name']) ?>
    </h2>
    <div class="d-flex gap-2">
        <?php if ($canEdit): ?>
        <button class="btn btn-hansen text-white" data-bs-toggle="modal" data-bs-target="#uploadModal">
            <i class="fas fa-upload me-2"></i>Enviar Imagens
        </button>
        <?php endif; ?>
        <a href="/admin/image-bank/<?= $folder['classroom_id'] ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Voltar
        </a>
    </div>
</div>

<?php if (empty($images)): ?>
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="fas fa-image fa-3x text-muted mb-3"></i>
            <p class="text-muted mb-0">Nenhuma imagem nesta pasta.</p>
            <?php if ($canEdit): ?>
            <button class="btn btn-hansen text-white mt-3" data-bs-toggle="modal" data-bs-target="#uploadModal">
                <i class="fas fa-upload me-2"></i>Enviar a primeira imagem
            </button>
            <?php endif; ?>
        </div>
    </div>
<?php else: ?>
    <div class="row g-3">
        <?php foreach ($images as $image): ?>
        <div class="col-6 col-md-4 col-lg-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="position-relative" style="cursor:pointer" data-bs-toggle="modal" data-bs-target="#lightbox-<?= $image['id'] ?>">
                    <img src="/uploads/image-bank/<?= htmlspecialchars($image['filename']) ?>"
                         class="card-img-top" alt="<?= htmlspecialchars($image['original_name']) ?>"
                         style="height:180px;object-fit:cover;">
                </div>
                <div class="card-body p-2">
                    <?php if ($canEdit): ?>
                    <div class="mb-1">
                        <input type="text" class="form-control form-control-sm caption-input"
                               data-id="<?= $image['id'] ?>"
                               value="<?= htmlspecialchars($image['caption'] ?? '') ?>"
                               placeholder="Legenda...">
                    </div>
                    <?php else: ?>
                        <?php if ($image['caption']): ?>
                        <small class="text-muted"><?= htmlspecialchars($image['caption']) ?></small>
                        <?php endif; ?>
                    <?php endif; ?>
                    <div class="d-flex justify-content-between align-items-center mt-1">
                        <small class="text-muted" title="<?= $image['original_name'] ?>">
                            <?= date('d/m/Y', strtotime($image['created_at'])) ?>
                        </small>
                        <?php if ($canEdit): ?>
                        <div class="btn-group btn-group-sm">
                            <?php if (!empty($otherFolders)): ?>
                            <button class="btn btn-outline-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#moveModal-<?= $image['id'] ?>" title="Mover">
                                <i class="fas fa-exchange-alt"></i>
                            </button>
                            <?php endif; ?>
                            <button class="btn btn-outline-danger btn-sm" onclick="confirmDeleteImage(<?= $image['id'] ?>)" title="Excluir">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Lightbox Modal -->
        <div class="modal fade" id="lightbox-<?= $image['id'] ?>" tabindex="-1">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content bg-dark border-0">
                    <div class="modal-header border-0">
                        <h6 class="modal-title text-white"><?= htmlspecialchars($image['caption'] ?: $image['original_name']) ?></h6>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body text-center p-0">
                        <img src="/uploads/image-bank/<?= htmlspecialchars($image['filename']) ?>"
                             class="img-fluid" alt="<?= htmlspecialchars($image['original_name']) ?>">
                    </div>
                    <div class="modal-footer border-0 justify-content-between">
                        <small class="text-white-50">Enviado por <?= htmlspecialchars($image['uploaded_by_name'] ?? 'Desconhecido') ?> em <?= date('d/m/Y H:i', strtotime($image['created_at'])) ?></small>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($canEdit && !empty($otherFolders)): ?>
        <!-- Move Modal -->
        <div class="modal fade" id="moveModal-<?= $image['id'] ?>" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form method="POST" action="/admin/image-bank/image/<?= $image['id'] ?>/move">
                        <div class="modal-header">
                            <h5 class="modal-title">Mover Imagem</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <label class="form-label fw-bold">Mover para:</label>
                            <select name="folder_id" class="form-select" required>
                                <option value="">Selecione a pasta destino...</option>
                                <?php foreach ($otherFolders as $of): ?>
                                <option value="<?= $of['id'] ?>"><?= htmlspecialchars($of['name']) ?> (<?= $of['folder_type'] === 'classroom' ? 'Coletivo' : 'Individual' ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-hansen text-white">Mover</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?php if ($canEdit): ?>
<!-- Drag-and-Drop Zone -->
<div id="dropZone" class="card border-2 border-dashed border-primary bg-light mb-4" style="display:none;">
    <div class="card-body text-center py-5">
        <i class="fas fa-cloud-upload-alt fa-3x text-primary mb-3"></i>
        <h5 class="text-primary fw-bold">Solte as imagens aqui</h5>
        <p class="text-muted mb-0">JPG ou PNG</p>
    </div>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/admin/image-bank/folder/<?= $folder['id'] ?>/upload" enctype="multipart/form-data" id="uploadForm">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-upload me-2"></i>Enviar Imagens</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div id="dropZoneModal" class="border-2 border-dashed rounded p-4 text-center mb-3" style="border:2px dashed #007e66;cursor:pointer;transition:background .2s;">
                        <i class="fas fa-cloud-upload-alt fa-2x text-muted mb-2"></i>
                        <p class="mb-1 fw-bold">Arraste imagens aqui ou clique para selecionar</p>
                        <small class="text-muted">JPG/PNG - Maximo 1920px de largura</small>
                        <input type="file" name="images[]" class="form-control mt-2" accept="image/jpeg,image/png" multiple required id="fileInput">
                    </div>
                    <div id="filePreview" class="row g-2"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-hansen text-white">
                        <i class="fas fa-upload me-2"></i>Enviar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Delete Form -->
<form id="deleteImageForm" method="POST" style="display:none;"></form>

<script>
// Confirmar exclusao
function confirmDeleteImage(id) {
    if (confirm('Tem certeza que deseja excluir esta imagem?\n\nEsta acao nao pode ser desfeita.')) {
        const form = document.getElementById('deleteImageForm');
        form.action = '/admin/image-bank/image/' + id + '/delete';
        form.submit();
    }
}

// Drag-and-drop support
(function() {
    var dropZone = document.getElementById('dropZone');
    var dropZoneModal = document.getElementById('dropZoneModal');
    var fileInput = document.getElementById('fileInput');
    var filePreview = document.getElementById('filePreview');
    var uploadForm = document.getElementById('uploadForm');

    if (!dropZone) return;

    // Page-level drag-and-drop: show drop zone overlay
    var dragCounter = 0;
    document.addEventListener('dragenter', function(e) {
        e.preventDefault();
        dragCounter++;
        if (dragCounter === 1) dropZone.style.display = 'block';
    });
    document.addEventListener('dragleave', function(e) {
        e.preventDefault();
        dragCounter--;
        if (dragCounter === 0) dropZone.style.display = 'none';
    });
    document.addEventListener('dragover', function(e) { e.preventDefault(); });
    document.addEventListener('drop', function(e) {
        e.preventDefault();
        dragCounter = 0;
        dropZone.style.display = 'none';
        if (e.dataTransfer.files.length > 0) {
            fileInput.files = e.dataTransfer.files;
            showPreview(e.dataTransfer.files);
            var modal = new bootstrap.Modal(document.getElementById('uploadModal'));
            modal.show();
        }
    });

    // Modal drop zone
    if (dropZoneModal) {
        dropZoneModal.addEventListener('dragover', function(e) {
            e.preventDefault();
            this.style.background = '#e8f5e9';
        });
        dropZoneModal.addEventListener('dragleave', function(e) {
            e.preventDefault();
            this.style.background = '';
        });
        dropZoneModal.addEventListener('drop', function(e) {
            e.preventDefault();
            this.style.background = '';
            if (e.dataTransfer.files.length > 0) {
                fileInput.files = e.dataTransfer.files;
                showPreview(e.dataTransfer.files);
            }
        });
    }

    // Preview on file select
    if (fileInput) {
        fileInput.addEventListener('change', function() { showPreview(this.files); });
    }

    function showPreview(files) {
        if (!filePreview) return;
        filePreview.innerHTML = '';
        Array.from(files).forEach(function(file) {
            if (!file.type.match('image.*')) return;
            var reader = new FileReader();
            reader.onload = function(e) {
                var col = document.createElement('div');
                col.className = 'col-4 col-md-3';
                col.innerHTML = '<img src="' + e.target.result + '" class="img-fluid rounded" style="height:80px;object-fit:cover;width:100%;" alt="">' +
                    '<small class="text-muted d-block text-truncate">' + file.name + '</small>';
                filePreview.appendChild(col);
            };
            reader.readAsDataURL(file);
        });
    }
})();

// Auto-save caption
document.querySelectorAll('.caption-input').forEach(input => {
    let timeout;
    input.addEventListener('input', function() {
        clearTimeout(timeout);
        const id = this.dataset.id;
        const caption = this.value;
        const el = this;

        timeout = setTimeout(() => {
            const formData = new FormData();
            formData.append('caption', caption);

            fetch('/admin/image-bank/image/' + id + '/caption', {
                method: 'POST',
                body: formData
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    el.classList.add('border-success');
                    setTimeout(() => el.classList.remove('border-success'), 1500);
                }
            });
        }, 800);
    });
});
</script>
