<div class="row">
    <div class="col-md-8">
        <div class="mb-3">
            <label class="form-label fw-bold">Título da Lição *</label>
            <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($lesson['title'] ?? ''); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Descrição</label>
            <textarea name="description" class="form-control" rows="3"><?php echo htmlspecialchars($lesson['description'] ?? ''); ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Conteúdo (HTML)</label>
            <textarea name="content" class="form-control" rows="5"><?php echo htmlspecialchars($lesson['content'] ?? ''); ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">URL do Vídeo (YouTube)</label>
            <input type="url" name="video_url" id="videoUrl" class="form-control" placeholder="https://www.youtube.com/watch?v=..." value="<?php echo htmlspecialchars($lesson['video_url'] ?? ''); ?>" onchange="updatePreview()">
        </div>
        <?php
        $videoId = '';
        $videoUrl = $lesson['video_url'] ?? '';
        if ($videoUrl) {
            if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $videoUrl, $m)) {
                $videoId = $m[1];
            }
        }
        ?>
        <div id="videoPreview" class="mb-3" style="<?php echo $videoId ? '' : 'display:none;'; ?>">
            <label class="form-label fw-bold">Preview do Vídeo</label>
            <div class="ratio ratio-16x9">
                <iframe id="previewIframe" src="<?php echo $videoId ? 'https://www.youtube.com/embed/' . $videoId : ''; ?>" allowfullscreen></iframe>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label fw-bold">Ordem</label>
            <input type="number" name="sort_order" class="form-control" min="0" value="<?php echo $lesson['sort_order'] ?? 0; ?>">
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Duração (minutos)</label>
            <input type="number" name="duration_minutes" class="form-control" min="0" value="<?php echo $lesson['duration_minutes'] ?? 0; ?>">
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Duração Vídeo (seg.)</label>
            <input type="number" name="video_duration" class="form-control" min="0" value="<?php echo $lesson['video_duration'] ?? 0; ?>">
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Material (PDF, etc.)</label>
            <?php if (!empty($lesson['material_file'])): ?>
                <div class="mb-2">
                    <a href="<?php echo $lesson['material_file']; ?>" target="_blank" class="btn btn-sm btn-outline-primary"><i class="fas fa-download me-1"></i> Material atual</a>
                </div>
            <?php endif; ?>
            <input type="file" name="material_file" class="form-control">
        </div>
        <div class="mb-3">
            <div class="form-check">
                <input type="checkbox" name="is_preview" class="form-check-input" id="isPreview" <?php echo !empty($lesson['is_preview']) ? 'checked' : ''; ?>>
                <label class="form-check-label" for="isPreview">Disponível como Preview (gratuito)</label>
            </div>
        </div>
    </div>
</div>

<script>
function updatePreview() {
    const url = document.getElementById('videoUrl').value;
    const preview = document.getElementById('videoPreview');
    const iframe = document.getElementById('previewIframe');
    const match = url.match(/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/);
    if (match) {
        iframe.src = 'https://www.youtube.com/embed/' + match[1];
        preview.style.display = 'block';
    } else {
        preview.style.display = 'none';
        iframe.src = '';
    }
}
</script>
