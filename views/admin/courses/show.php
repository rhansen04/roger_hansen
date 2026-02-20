<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/admin/courses" class="text-decoration-none">Cursos</a></li>
        <li class="breadcrumb-item active"><?php echo htmlspecialchars($course['title']); ?></li>
    </ol>
</nav>

<?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i><?php echo $_SESSION['success_message']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['success_message']); ?>
<?php endif; ?>
<?php if (isset($_SESSION['error_message'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i><?php echo $_SESSION['error_message']; ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['error_message']); ?>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-primary fw-bold mb-0"><?php echo htmlspecialchars($course['title']); ?></h2>
    <div>
        <a href="/admin/courses/<?php echo $course['id']; ?>/materials" class="btn btn-outline-info"><i class="fas fa-paperclip me-1"></i> Materiais</a>
        <a href="/admin/courses/<?php echo $course['id']; ?>/quizzes" class="btn btn-outline-success"><i class="fas fa-question-circle me-1"></i> Quizzes</a>
        <a href="/admin/courses/<?php echo $course['id']; ?>/edit" class="btn btn-outline-primary"><i class="fas fa-edit me-1"></i> Editar</a>
        <a href="/admin/courses" class="btn btn-outline-secondary ms-2">Voltar</a>
    </div>
</div>

<!-- Course Info -->
<div class="row mb-4">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-3">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 text-center">
                        <?php if ($course['cover_image']): ?>
                            <img src="<?php echo $course['cover_image']; ?>" class="img-fluid rounded" alt="Capa">
                        <?php else: ?>
                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 120px;">
                                <i class="fas fa-image fa-3x text-muted"></i>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-9">
                        <p class="mb-1"><strong>Slug:</strong> <?php echo htmlspecialchars($course['slug']); ?></p>
                        <p class="mb-1"><strong>Categoria:</strong> <?php echo htmlspecialchars($course['category'] ?? 'N/A'); ?></p>
                        <p class="mb-1"><strong>Nível:</strong> <?php echo ucfirst($course['level'] ?? 'N/A'); ?></p>
                        <p class="mb-1"><strong>Duração:</strong> <?php echo $course['duration_hours'] ?? 0; ?>h</p>
                        <p class="mb-0">
                            <?php if ($course['is_active']): ?><span class="badge bg-success">Ativo</span><?php else: ?><span class="badge bg-danger">Inativo</span><?php endif; ?>
                            <?php if ($course['is_free']): ?><span class="badge bg-info ms-1">Gratuito</span><?php else: ?><span class="badge bg-warning text-dark ms-1">R$ <?php echo number_format($course['price'], 2, ',', '.'); ?></span><?php endif; ?>
                        </p>
                    </div>
                </div>
                <?php if ($course['description']): ?>
                    <hr>
                    <p class="mb-0"><?php echo nl2br(htmlspecialchars($course['description'])); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <h5 class="fw-bold">Estatísticas</h5>
                <hr>
                <div class="row">
                    <div class="col-4">
                        <h3 class="fw-bold text-primary"><?php echo count($sections); ?></h3>
                        <small class="text-muted">Seções</small>
                    </div>
                    <div class="col-4">
                        <?php $totalLessons = 0; foreach ($sectionLessons as $ls) $totalLessons += count($ls); ?>
                        <h3 class="fw-bold text-info"><?php echo $totalLessons; ?></h3>
                        <small class="text-muted">Lições</small>
                    </div>
                    <div class="col-4">
                        <h3 class="fw-bold text-success"><?php echo count($enrollments); ?></h3>
                        <small class="text-muted">Alunos</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sections & Lessons -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0">Seções e Lições</h4>
    <button class="btn btn-hansen btn-sm text-white" data-bs-toggle="modal" data-bs-target="#addSectionModal"><i class="fas fa-plus me-1"></i> Nova Seção</button>
</div>

<?php if (empty($sections)): ?>
    <div class="alert alert-info">Nenhuma seção cadastrada. Clique em "Nova Seção" para começar.</div>
<?php else: ?>
    <div class="accordion" id="sectionsAccordion">
        <?php foreach ($sections as $i => $section): ?>
        <div class="accordion-item border-0 shadow-sm mb-2">
            <h2 class="accordion-header">
                <button class="accordion-button <?php echo $i > 0 ? 'collapsed' : ''; ?>" type="button" data-bs-toggle="collapse" data-bs-target="#section<?php echo $section['id']; ?>">
                    <span class="badge bg-secondary me-2"><?php echo $section['sort_order']; ?></span>
                    <strong><?php echo htmlspecialchars($section['title']); ?></strong>
                    <span class="badge bg-info ms-2"><?php echo count($sectionLessons[$section['id']] ?? []); ?> lições</span>
                </button>
            </h2>
            <div id="section<?php echo $section['id']; ?>" class="accordion-collapse collapse <?php echo $i === 0 ? 'show' : ''; ?>" data-bs-parent="#sectionsAccordion">
                <div class="accordion-body">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <small class="text-muted"><?php echo htmlspecialchars($section['description'] ?? ''); ?></small>
                        <div>
                            <button class="btn btn-outline-primary btn-sm" onclick="editSection(<?php echo $section['id']; ?>, '<?php echo htmlspecialchars($section['title'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($section['description'] ?? '', ENT_QUOTES); ?>', <?php echo $section['sort_order']; ?>)"><i class="fas fa-edit"></i></button>
                            <button class="btn btn-outline-danger btn-sm" onclick="deleteSection(<?php echo $section['id']; ?>, '<?php echo htmlspecialchars($section['title'], ENT_QUOTES); ?>')"><i class="fas fa-trash"></i></button>
                            <a href="/admin/sections/<?php echo $section['id']; ?>/lessons/create" class="btn btn-outline-success btn-sm"><i class="fas fa-plus"></i> Lição</a>
                        </div>
                    </div>
                    <?php if (!empty($sectionLessons[$section['id']])): ?>
                    <table class="table table-sm table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th>#</th>
                                <th>Título</th>
                                <th>Vídeo</th>
                                <th>Material</th>
                                <th>Preview</th>
                                <th class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($sectionLessons[$section['id']] as $lesson): ?>
                            <tr>
                                <td><?php echo $lesson['sort_order']; ?></td>
                                <td><?php echo htmlspecialchars($lesson['title']); ?></td>
                                <td><?php echo $lesson['video_url'] ? '<i class="fas fa-video text-success"></i>' : '<i class="fas fa-minus text-muted"></i>'; ?></td>
                                <td><?php echo $lesson['material_file'] ? '<i class="fas fa-file text-primary"></i>' : '<i class="fas fa-minus text-muted"></i>'; ?></td>
                                <td><?php echo $lesson['is_preview'] ? '<span class="badge bg-success">Sim</span>' : '<span class="badge bg-light text-dark">Não</span>'; ?></td>
                                <td class="text-center">
                                    <a href="/admin/lessons/<?php echo $lesson['id']; ?>/edit" class="btn btn-sm btn-outline-secondary"><i class="fas fa-edit"></i></a>
                                    <button onclick="deleteLesson(<?php echo $lesson['id']; ?>, '<?php echo htmlspecialchars($lesson['title'], ENT_QUOTES); ?>')" class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php else: ?>
                        <p class="text-muted mb-0"><i class="fas fa-info-circle me-1"></i> Nenhuma lição nesta seção.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<!-- Add Section Modal -->
<div class="modal fade" id="addSectionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/admin/courses/<?php echo $course['id']; ?>/sections" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Nova Seção</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Título *</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Descrição</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Ordem</label>
                        <input type="number" name="sort_order" class="form-control" value="<?php echo count($sections) + 1; ?>" min="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-hansen text-white">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Section Modal -->
<div class="modal fade" id="editSectionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editSectionForm" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title">Editar Seção</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Título *</label>
                        <input type="text" name="title" id="editSectionTitle" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Descrição</label>
                        <textarea name="description" id="editSectionDesc" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Ordem</label>
                        <input type="number" name="sort_order" id="editSectionOrder" class="form-control" min="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-hansen text-white">Atualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Forms -->
<form id="deleteSectionForm" method="POST" style="display:none;"></form>
<form id="deleteLessonForm" method="POST" style="display:none;"></form>

<script>
function editSection(id, title, desc, order) {
    document.getElementById('editSectionForm').action = '/admin/sections/' + id + '/update';
    document.getElementById('editSectionTitle').value = title;
    document.getElementById('editSectionDesc').value = desc;
    document.getElementById('editSectionOrder').value = order;
    new bootstrap.Modal(document.getElementById('editSectionModal')).show();
}

function deleteSection(id, name) {
    if (confirm('Excluir seção "' + name + '"? Todas as lições desta seção serão excluídas!')) {
        const form = document.getElementById('deleteSectionForm');
        form.action = '/admin/sections/' + id + '/delete';
        form.submit();
    }
}

function deleteLesson(id, name) {
    if (confirm('Excluir lição "' + name + '"?')) {
        const form = document.getElementById('deleteLessonForm');
        form.action = '/admin/lessons/' + id + '/delete';
        form.submit();
    }
}
</script>
