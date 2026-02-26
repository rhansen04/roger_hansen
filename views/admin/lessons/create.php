<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/admin/courses" class="text-decoration-none">Cursos</a></li>
        <li class="breadcrumb-item"><a href="/admin/courses/<?php echo $course['id']; ?>" class="text-decoration-none"><?php echo htmlspecialchars($course['title']); ?></a></li>
        <li class="breadcrumb-item"><span class="text-muted"><?php echo htmlspecialchars($section['title']); ?></span></li>
        <li class="breadcrumb-item active">Nova Lição</li>
    </ol>
</nav>

<div class="d-flex align-items-center mb-3">
    <a href="/admin/courses/<?php echo $course['id']; ?>" class="btn btn-outline-secondary btn-sm me-3">
        <i class="fas fa-arrow-left me-1"></i> Voltar ao Curso
    </a>
    <h2 class="text-primary fw-bold mb-0">NOVA LIÇÃO</h2>
</div>

<form action="/admin/sections/<?php echo $section['id']; ?>/lessons" method="POST" enctype="multipart/form-data">
    <?php $lesson = []; include __DIR__ . '/_form.php'; ?>
    <div class="d-flex justify-content-between">
        <a href="/admin/courses/<?php echo $course['id']; ?>" class="btn btn-outline-secondary">Cancelar</a>
        <button type="submit" class="btn btn-hansen text-white"><i class="fas fa-save me-2"></i> Salvar Lição</button>
    </div>
</form>
