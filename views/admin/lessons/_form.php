<?php
$videoId = '';
$videoUrl = $lesson['video_url'] ?? '';
if ($videoUrl) {
    if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $videoUrl, $m)) {
        $videoId = $m[1];
    }
}
?>

<!-- Card: Informações Básicas -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-teal text-white">
        <i class="fas fa-info-circle me-2"></i> Informações Básicas
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-9 mb-3">
                <label class="form-label fw-bold">Título da Lição *</label>
                <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($lesson['title'] ?? ''); ?>" required>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label fw-bold">Ordem</label>
                <input type="number" name="sort_order" class="form-control" min="0" value="<?php echo $lesson['sort_order'] ?? 0; ?>">
            </div>
        </div>
        <div class="mb-0">
            <label class="form-label fw-bold">Descrição</label>
            <textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($lesson['description'] ?? ''); ?></textarea>
        </div>
    </div>
</div>

<!-- Card: Conteúdo da Aula -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header">
        <i class="fas fa-file-alt me-2"></i> Conteúdo da Aula
    </div>
    <div class="card-body">
        <div class="mb-0">
            <label class="form-label fw-bold">Conteúdo (HTML)</label>
            <textarea name="content" class="form-control" rows="5"><?php echo htmlspecialchars($lesson['content'] ?? ''); ?></textarea>
        </div>
    </div>
</div>

<!-- Card: Vídeo -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header">
        <i class="fas fa-video me-2"></i> Vídeo
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-6 mb-3 mb-md-0">
                <label class="form-label fw-bold">URL do Vídeo (YouTube)</label>
                <input type="url" name="video_url" id="videoUrl" class="form-control" placeholder="https://www.youtube.com/watch?v=..." value="<?php echo htmlspecialchars($lesson['video_url'] ?? ''); ?>" oninput="updatePreview()">
            </div>
            <div class="col-md-6">
                <label class="form-label fw-bold">Preview</label>
                <div id="videoPreview">
                    <?php if ($videoId): ?>
                        <div class="ratio ratio-16x9">
                            <iframe id="previewIframe" src="https://www.youtube.com/embed/<?php echo $videoId; ?>" allowfullscreen></iframe>
                        </div>
                    <?php else: ?>
                        <div id="videoPlaceholder" class="border rounded d-flex align-items-center justify-content-center text-muted" style="aspect-ratio: 16/9;">
                            <div class="text-center">
                                <i class="fas fa-play-circle fa-3x mb-2 d-block"></i>
                                Cole a URL para ver o preview
                            </div>
                        </div>
                        <div id="videoIframeWrap" class="ratio ratio-16x9" style="display:none;">
                            <iframe id="previewIframe" src="" allowfullscreen></iframe>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Card: Configurações -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header">
        <i class="fas fa-cog me-2"></i> Configurações
    </div>
    <div class="card-body">
        <div class="row align-items-end">
            <div class="col-md-3 mb-3">
                <label class="form-label fw-bold">Duração</label>
                <div class="input-group">
                    <input type="number" name="duration_minutes" class="form-control" min="0" value="<?php echo $lesson['duration_minutes'] ?? 0; ?>">
                    <span class="input-group-text">min</span>
                </div>
            </div>
            <div class="col-md-3 mb-3">
                <label class="form-label fw-bold">Duração Vídeo</label>
                <div class="input-group">
                    <input type="number" name="video_duration" class="form-control" min="0" value="<?php echo $lesson['video_duration'] ?? 0; ?>">
                    <span class="input-group-text">seg</span>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <label class="form-label fw-bold">Material (PDF, etc.)</label>
                <?php if (!empty($lesson['material_file'])): ?>
                    <div class="mb-2">
                        <a href="<?php echo $lesson['material_file']; ?>" target="_blank" class="badge bg-primary text-decoration-none p-2">
                            <i class="fas fa-download me-1"></i> Material atual
                        </a>
                    </div>
                <?php endif; ?>
                <input type="file" name="material_file" class="form-control">
            </div>
            <div class="col-md-2 mb-3">
                <div class="form-check form-switch">
                    <input type="checkbox" name="is_preview" class="form-check-input" id="isPreview" role="switch" <?php echo !empty($lesson['is_preview']) ? 'checked' : ''; ?>>
                    <label class="form-check-label fw-bold" for="isPreview">Preview</label>
                </div>
                <small class="text-muted">Acesso gratuito</small>
            </div>
        </div>
    </div>
</div>

<script>
function updatePreview() {
    const url = document.getElementById('videoUrl').value;
    const match = url.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/);
    const placeholder = document.getElementById('videoPlaceholder');
    const iframeWrap = document.getElementById('videoIframeWrap');
    const iframe = document.getElementById('previewIframe');

    if (match) {
        iframe.src = 'https://www.youtube.com/embed/' + match[1];
        if (placeholder) placeholder.style.display = 'none';
        if (iframeWrap) iframeWrap.style.display = 'block';
        // For edit mode where iframe is already visible
        iframe.closest('.ratio').style.display = '';
    } else {
        iframe.src = '';
        if (placeholder) placeholder.style.display = '';
        if (iframeWrap) iframeWrap.style.display = 'none';
    }
}
</script>
