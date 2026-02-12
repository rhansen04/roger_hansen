<div class="row">
    <div class="col-md-8">
        <div class="mb-3">
            <label class="form-label fw-bold">Título do Curso *</label>
            <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($course['title'] ?? ''); ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Descrição Curta</label>
            <input type="text" name="short_description" class="form-control" maxlength="500" value="<?php echo htmlspecialchars($course['short_description'] ?? ''); ?>">
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Descrição Completa</label>
            <textarea name="description" class="form-control" rows="6"><?php echo htmlspecialchars($course['description'] ?? ''); ?></textarea>
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label fw-bold">Imagem de Capa</label>
            <?php if (!empty($course['cover_image'])): ?>
                <div class="mb-2">
                    <img src="<?php echo $course['cover_image']; ?>" class="img-fluid rounded" alt="Capa">
                </div>
            <?php endif; ?>
            <input type="file" name="cover_image" class="form-control" accept="image/*">
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Categoria</label>
            <input type="text" name="category" class="form-control" value="<?php echo htmlspecialchars($course['category'] ?? ''); ?>">
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Nível</label>
            <select name="level" class="form-select">
                <option value="beginner" <?php echo ($course['level'] ?? '') === 'beginner' ? 'selected' : ''; ?>>Iniciante</option>
                <option value="intermediate" <?php echo ($course['level'] ?? '') === 'intermediate' ? 'selected' : ''; ?>>Intermediário</option>
                <option value="advanced" <?php echo ($course['level'] ?? '') === 'advanced' ? 'selected' : ''; ?>>Avançado</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label fw-bold">Instrutor</label>
            <select name="instructor_id" class="form-select">
                <option value="">-- Selecione --</option>
                <?php foreach ($instructors as $inst): ?>
                    <option value="<?php echo $inst['id']; ?>" <?php echo ($course['instructor_id'] ?? '') == $inst['id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($inst['name']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="row">
            <div class="col-6 mb-3">
                <label class="form-label fw-bold">Preço (R$)</label>
                <input type="number" name="price" class="form-control" step="0.01" min="0" value="<?php echo $course['price'] ?? '0.00'; ?>">
            </div>
            <div class="col-6 mb-3">
                <label class="form-label fw-bold">Duração (h)</label>
                <input type="number" name="duration_hours" class="form-control" min="0" value="<?php echo $course['duration_hours'] ?? 0; ?>">
            </div>
        </div>
        <div class="mb-3">
            <div class="form-check">
                <input type="checkbox" name="is_free" class="form-check-input" id="isFree" <?php echo !empty($course['is_free']) ? 'checked' : ''; ?>>
                <label class="form-check-label" for="isFree">Curso Gratuito</label>
            </div>
        </div>
        <div class="mb-3">
            <div class="form-check">
                <input type="checkbox" name="is_active" class="form-check-input" id="isActive" <?php echo (!isset($course['is_active']) || $course['is_active']) ? 'checked' : ''; ?>>
                <label class="form-check-label" for="isActive">Curso Ativo</label>
            </div>
        </div>
    </div>
</div>
