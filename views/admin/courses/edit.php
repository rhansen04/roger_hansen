<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/admin/courses" class="text-decoration-none">Cursos</a></li>
        <li class="breadcrumb-item active">Editar</li>
    </ol>
</nav>

<h2 class="text-primary fw-bold mb-4">EDITAR CURSO</h2>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="/admin/courses/<?php echo $course['id']; ?>/update" method="POST" enctype="multipart/form-data">
            <?php include __DIR__ . '/_form.php'; ?>
            <hr>
            <div class="d-flex justify-content-between">
                <a href="/admin/courses/<?php echo $course['id']; ?>" class="btn btn-outline-secondary">Cancelar</a>
                <button type="submit" class="btn btn-hansen text-white"><i class="fas fa-save me-2"></i> Atualizar Curso</button>
            </div>
        </form>
    </div>
</div>
