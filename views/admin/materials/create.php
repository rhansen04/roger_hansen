<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0" style="color:var(--primary-color)"><i class="fas fa-upload me-2"></i>Novo Material de Apoio</h2>
        <small class="text-muted">Curso: <strong><?= htmlspecialchars($course['title']) ?></strong></small>
    </div>
    <a href="/admin/courses/<?= $course['id'] ?>/materials" class="btn btn-outline-secondary btn-sm"><i class="fas fa-arrow-left me-1"></i>Voltar</a>
</div>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger"><i class="fas fa-exclamation-circle me-2"></i><?= $_SESSION['error'] ?></div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form method="POST" action="/admin/courses/<?= $course['id'] ?>/materials/create" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label fw-bold">Título <span class="text-danger">*</span></label>
                <input type="text" name="title" class="form-control" required placeholder="Ex: Apostila Módulo 1">
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Descrição</label>
                <textarea name="description" class="form-control" rows="2" placeholder="Descrição opcional do material"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Seção (opcional)</label>
                <select name="section_id" class="form-select">
                    <option value="">— Geral (sem seção específica) —</option>
                    <?php foreach ($sections as $s): ?>
                        <option value="<?= $s['id'] ?>"><?= htmlspecialchars($s['title']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Arquivo <span class="text-danger">*</span></label>
                <input type="file" name="material_file" class="form-control" required
                       accept=".pdf,.xlsx,.xls,.csv,.jpg,.jpeg,.png,.gif,.webp,.mp4,.avi,.mov,.webm,.doc,.docx,.ppt,.pptx,.zip">
                <div class="form-text">Permitido: PDF, Excel, CSV, imagens (JPG/PNG), vídeos (MP4), Word, PowerPoint, ZIP. Máx: 50MB.</div>
            </div>
            <div class="mb-3">
                <label class="form-label fw-bold">Ordem de exibição</label>
                <input type="number" name="sort_order" class="form-control" value="0" min="0" style="max-width:120px">
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn" style="background:var(--primary-color);color:white"><i class="fas fa-upload me-2"></i>Enviar Material</button>
                <a href="/admin/courses/<?= $course['id'] ?>/materials" class="btn btn-outline-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>
