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
    <div class="d-flex align-items-center gap-2">
        <a href="/admin/courses" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-1"></i> Voltar</a>
        <div class="vr"></div>
        <h2 class="text-primary fw-bold mb-0"><?php echo htmlspecialchars($course['title']); ?></h2>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="/curso/<?php echo htmlspecialchars($course['slug']); ?>" class="btn btn-outline-secondary btn-sm" target="_blank"><i class="fas fa-external-link-alt me-1"></i> Ver como Aluno</a>
        <div class="vr"></div>
        <a href="/admin/courses/<?php echo $course['id']; ?>/materials" class="btn btn-outline-info"><i class="fas fa-paperclip me-1"></i> Materiais</a>
        <a href="/admin/courses/<?php echo $course['id']; ?>/quizzes" class="btn btn-outline-info"><i class="fas fa-question-circle me-1"></i> Quizzes</a>
        <div class="vr"></div>
        <a href="/admin/courses/<?php echo $course['id']; ?>/edit" class="btn btn-primary"><i class="fas fa-edit me-1"></i> Editar Curso</a>
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
                            <?php if (($_SESSION['user_role'] ?? '') !== 'professor'): ?>
                                <?php if ($course['is_free']): ?><span class="badge bg-info ms-1">Gratuito</span><?php else: ?><span class="badge bg-warning text-dark ms-1">R$ <?php echo number_format($course['price'], 2, ',', '.'); ?></span><?php endif; ?>
                            <?php endif; ?>
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
                    <div class="col-3">
                        <h3 class="fw-bold text-warning"><?php echo count($modules ?? []); ?></h3>
                        <small class="text-muted">Módulos</small>
                    </div>
                    <div class="col-3">
                        <h3 class="fw-bold text-primary"><?php echo count($sections); ?></h3>
                        <small class="text-muted">Seções</small>
                    </div>
                    <div class="col-3">
                        <?php $totalLessons = 0; foreach ($sectionLessons as $ls) $totalLessons += count($ls); ?>
                        <h3 class="fw-bold text-info"><?php echo $totalLessons; ?></h3>
                        <small class="text-muted">Lições</small>
                    </div>
                    <div class="col-3">
                        <h3 class="fw-bold text-success"><?php echo count($enrollments); ?></h3>
                        <small class="text-muted">Alunos</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Organizar seções por módulo
$orphanSections = [];
$moduleSections = [];
foreach ($sections as $section) {
    if (empty($section['module_id'])) {
        $orphanSections[] = $section;
    } else {
        $moduleSections[$section['module_id']][] = $section;
    }
}
$hasModules = !empty($modules);
?>

<!-- Sections & Lessons -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0"><?php echo $hasModules ? 'Módulos, Seções e Lições' : 'Seções e Lições'; ?></h4>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-secondary btn-sm" onclick="document.querySelectorAll('.accordion-collapse').forEach(el => new bootstrap.Collapse(el, {toggle:false}).show())"><i class="fas fa-expand-alt me-1"></i> Expandir Tudo</button>
        <button class="btn btn-outline-secondary btn-sm" onclick="document.querySelectorAll('.accordion-collapse.show').forEach(el => new bootstrap.Collapse(el, {toggle:false}).hide())"><i class="fas fa-compress-alt me-1"></i> Recolher Tudo</button>
        <button class="btn btn-outline-warning btn-sm text-dark" data-bs-toggle="modal" data-bs-target="#addModuleModal"><i class="fas fa-layer-group me-1"></i> Novo Módulo</button>
        <button class="btn btn-hansen btn-sm text-white" data-bs-toggle="modal" data-bs-target="#addSectionModal"><i class="fas fa-plus me-1"></i> Nova Seção</button>
    </div>
</div>

<?php
// Helper para renderizar uma seção com suas lições
function renderSection($section, $sectionLessons, $sectionIndex, $totalSections, $accordionParent) {
    $lessons = $sectionLessons[$section['id']] ?? [];
    $lessonCount = count($lessons);
?>
<div class="accordion-item border-0 shadow-sm mb-2">
    <h2 class="accordion-header">
        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#section<?php echo $section['id']; ?>">
            <span class="badge bg-secondary me-2"><?php echo $section['sort_order']; ?></span>
            <strong><?php echo htmlspecialchars($section['title']); ?></strong>
            <span class="badge bg-info ms-2"><?php echo $lessonCount; ?> lições</span>
        </button>
    </h2>
    <div id="section<?php echo $section['id']; ?>" class="accordion-collapse collapse">
        <div class="accordion-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <small class="text-muted"><?php echo htmlspecialchars($section['description'] ?? ''); ?></small>
                <div class="d-flex gap-1">
                    <button class="btn btn-outline-secondary btn-sm" onclick="reorderItem('sections', <?php echo $section['id']; ?>, 'up')" <?php echo $sectionIndex === 0 ? 'disabled' : ''; ?> title="Mover para cima"><i class="fas fa-arrow-up"></i></button>
                    <button class="btn btn-outline-secondary btn-sm" onclick="reorderItem('sections', <?php echo $section['id']; ?>, 'down')" <?php echo $sectionIndex === $totalSections - 1 ? 'disabled' : ''; ?> title="Mover para baixo"><i class="fas fa-arrow-down"></i></button>
                    <button class="btn btn-outline-primary btn-sm" onclick="editSection(<?php echo $section['id']; ?>, '<?php echo htmlspecialchars($section['title'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($section['description'] ?? '', ENT_QUOTES); ?>', <?php echo $section['sort_order']; ?>, <?php echo $section['module_id'] ?? 'null'; ?>)"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-outline-danger btn-sm" onclick="deleteSection(<?php echo $section['id']; ?>, '<?php echo htmlspecialchars($section['title'], ENT_QUOTES); ?>')"><i class="fas fa-trash"></i></button>
                    <a href="/admin/sections/<?php echo $section['id']; ?>/lessons/create" class="btn btn-outline-success btn-sm"><i class="fas fa-plus"></i> Lição</a>
                </div>
            </div>
            <?php if (!empty($lessons)): ?>
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
                    <?php foreach ($lessons as $li => $lesson): ?>
                    <tr>
                        <td><?php echo $lesson['sort_order']; ?></td>
                        <td><?php echo htmlspecialchars($lesson['title']); ?></td>
                        <td><?php echo $lesson['video_url'] ? '<i class="fas fa-video text-success"></i>' : '<i class="fas fa-minus text-muted"></i>'; ?></td>
                        <td><?php echo $lesson['material_file'] ? '<i class="fas fa-file text-primary"></i>' : '<i class="fas fa-minus text-muted"></i>'; ?></td>
                        <td><?php echo $lesson['is_preview'] ? '<span class="badge bg-success">Sim</span>' : '<span class="badge bg-light text-dark">Não</span>'; ?></td>
                        <td class="text-center">
                            <button class="btn btn-sm btn-outline-secondary" onclick="reorderItem('lessons', <?php echo $lesson['id']; ?>, 'up')" <?php echo $li === 0 ? 'disabled' : ''; ?>><i class="fas fa-arrow-up"></i></button>
                            <button class="btn btn-sm btn-outline-secondary" onclick="reorderItem('lessons', <?php echo $lesson['id']; ?>, 'down')" <?php echo $li === $lessonCount - 1 ? 'disabled' : ''; ?>><i class="fas fa-arrow-down"></i></button>
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
<?php } ?>

<?php if (empty($sections) && empty($modules)): ?>
    <div class="alert alert-info">Nenhuma seção cadastrada. Clique em "Nova Seção" para começar, ou crie um "Novo Módulo" para organizar as seções.</div>
<?php else: ?>

    <?php // Seções sem módulo (Seções Gerais) ?>
    <?php if (!empty($orphanSections)): ?>
        <?php if ($hasModules): ?>
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-header bg-light d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-folder me-2 text-secondary"></i>Seções Gerais <span class="badge bg-secondary"><?php echo count($orphanSections); ?></span></h5>
                    <button class="btn btn-outline-info btn-sm" onclick="openMoveModal(0, 'Seções Gerais')" title="Mover seções para um módulo"><i class="fas fa-exchange-alt me-1"></i> Mover</button>
                </div>
                <div class="card-body p-2">
        <?php endif; ?>
        <div class="accordion" id="orphanSectionsAccordion">
            <?php foreach ($orphanSections as $i => $section): ?>
                <?php renderSection($section, $sectionLessons, $i, count($orphanSections), 'orphanSectionsAccordion'); ?>
            <?php endforeach; ?>
        </div>
        <?php if ($hasModules): ?>
                </div>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <?php // Módulos com suas seções ?>
    <?php if ($hasModules): ?>
        <div class="accordion" id="modulesAccordion">
            <?php foreach ($modules as $mi => $module):
                $modSections = $moduleSections[$module['id']] ?? [];
            ?>
            <div class="card border-0 shadow-sm mb-3" style="border-left: 4px solid var(--bs-warning) !important;">
                <div class="card-header bg-warning bg-opacity-10 d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <button class="btn btn-sm p-0 border-0" data-bs-toggle="collapse" data-bs-target="#module<?php echo $module['id']; ?>">
                            <i class="fas fa-layer-group text-warning me-1"></i>
                        </button>
                        <span class="badge bg-warning text-dark me-1"><?php echo $module['sort_order']; ?></span>
                        <h5 class="mb-0 fw-bold"><?php echo htmlspecialchars($module['title']); ?></h5>
                        <span class="badge bg-primary"><?php echo count($modSections); ?> seções</span>
                        <?php if (!empty($module['description'])): ?>
                            <small class="text-muted ms-2"><?php echo htmlspecialchars($module['description']); ?></small>
                        <?php endif; ?>
                    </div>
                    <div class="d-flex gap-1">
                        <button class="btn btn-outline-secondary btn-sm" onclick="reorderItem('modules', <?php echo $module['id']; ?>, 'up')" <?php echo $mi === 0 ? 'disabled' : ''; ?>><i class="fas fa-arrow-up"></i></button>
                        <button class="btn btn-outline-secondary btn-sm" onclick="reorderItem('modules', <?php echo $module['id']; ?>, 'down')" <?php echo $mi === count($modules) - 1 ? 'disabled' : ''; ?>><i class="fas fa-arrow-down"></i></button>
                        <button class="btn btn-outline-primary btn-sm" onclick="editModule(<?php echo $module['id']; ?>, '<?php echo htmlspecialchars($module['title'], ENT_QUOTES); ?>', '<?php echo htmlspecialchars($module['description'] ?? '', ENT_QUOTES); ?>', <?php echo $module['sort_order']; ?>)"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-outline-info btn-sm" onclick="openMoveModal(<?php echo $module['id']; ?>, '<?php echo htmlspecialchars($module['title'], ENT_QUOTES); ?>')" title="Mover seções para outro módulo"><i class="fas fa-exchange-alt"></i></button>
                        <button class="btn btn-outline-danger btn-sm" onclick="deleteModule(<?php echo $module['id']; ?>, '<?php echo htmlspecialchars($module['title'], ENT_QUOTES); ?>')"><i class="fas fa-trash"></i></button>
                    </div>
                </div>
                <div id="module<?php echo $module['id']; ?>" class="collapse show">
                    <div class="card-body p-2">
                        <?php if (!empty($modSections)): ?>
                        <div class="accordion" id="moduleSections<?php echo $module['id']; ?>">
                            <?php foreach ($modSections as $si => $section): ?>
                                <?php renderSection($section, $sectionLessons, $si, count($modSections), 'moduleSections' . $module['id']); ?>
                            <?php endforeach; ?>
                        </div>
                        <?php else: ?>
                            <p class="text-muted mb-0 p-2"><i class="fas fa-info-circle me-1"></i> Nenhuma seção neste módulo.</p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

<?php endif; ?>

<!-- Add Module Modal -->
<div class="modal fade" id="addModuleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/admin/courses/<?php echo $course['id']; ?>/modules" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-layer-group me-2"></i>Novo Módulo</h5>
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
                        <input type="number" name="sort_order" class="form-control" value="<?php echo count($modules ?? []) + 1; ?>" min="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning text-dark">Salvar Módulo</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Module Modal -->
<div class="modal fade" id="editModuleModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="editModuleForm" method="POST">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-layer-group me-2"></i>Editar Módulo</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Título *</label>
                        <input type="text" name="title" id="editModuleTitle" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Descrição</label>
                        <textarea name="description" id="editModuleDesc" class="form-control" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Ordem</label>
                        <input type="number" name="sort_order" id="editModuleOrder" class="form-control" min="0">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning text-dark">Atualizar</button>
                </div>
            </form>
        </div>
    </div>
</div>

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
                    <?php if (!empty($modules)): ?>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Módulo <small class="text-muted">(opcional)</small></label>
                        <select name="module_id" class="form-select">
                            <option value="">-- Sem módulo (seção geral) --</option>
                            <?php foreach ($modules as $mod): ?>
                                <option value="<?php echo $mod['id']; ?>"><?php echo htmlspecialchars($mod['title']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php endif; ?>
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
                    <?php if (!empty($modules)): ?>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Módulo <small class="text-muted">(opcional)</small></label>
                        <select name="module_id" id="editSectionModule" class="form-select">
                            <option value="">-- Sem módulo (seção geral) --</option>
                            <?php foreach ($modules as $mod): ?>
                                <option value="<?php echo $mod['id']; ?>"><?php echo htmlspecialchars($mod['title']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <?php endif; ?>
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

<!-- Move Sections Modal -->
<div class="modal fade" id="moveSectionsModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="/admin/sections/move-module" method="POST">
                <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title"><i class="fas fa-exchange-alt me-2"></i> Mover Seções</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-2">Origem: <strong id="moveSourceName"></strong></p>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Selecione as seções para mover:</label>
                        <div id="moveSectionsList" class="border rounded p-2" style="max-height:200px; overflow-y:auto;">
                            <!-- populated by JS -->
                        </div>
                        <div class="mt-1">
                            <small class="text-muted">
                                <a href="#" onclick="toggleAllMoveCheckboxes(true); return false;">Selecionar todas</a> |
                                <a href="#" onclick="toggleAllMoveCheckboxes(false); return false;">Desmarcar todas</a>
                            </small>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Mover para: <span class="text-danger">*</span></label>
                        <select name="target_module_id" class="form-select" required>
                            <option value="">-- Seções Gerais (sem módulo) --</option>
                            <?php foreach ($modules as $mod): ?>
                                <option value="<?php echo $mod['id']; ?>"><?php echo htmlspecialchars($mod['title']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-info text-white"><i class="fas fa-exchange-alt me-1"></i> Mover</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Delete Forms -->
<form id="deleteModuleForm" method="POST" style="display:none;"></form>
<form id="deleteSectionForm" method="POST" style="display:none;"></form>
<form id="deleteLessonForm" method="POST" style="display:none;"></form>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteConfirmModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-exclamation-triangle me-2"></i> Confirmar Exclusão</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p id="deleteConfirmMessage" class="mb-0"></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="deleteConfirmBtn"><i class="fas fa-trash me-1"></i> Excluir</button>
            </div>
        </div>
    </div>
</div>

<script>
function editModule(id, title, desc, order) {
    document.getElementById('editModuleForm').action = '/admin/modules/' + id + '/update';
    document.getElementById('editModuleTitle').value = title;
    document.getElementById('editModuleDesc').value = desc;
    document.getElementById('editModuleOrder').value = order;
    new bootstrap.Modal(document.getElementById('editModuleModal')).show();
}

function editSection(id, title, desc, order, moduleId) {
    document.getElementById('editSectionForm').action = '/admin/sections/' + id + '/update';
    document.getElementById('editSectionTitle').value = title;
    document.getElementById('editSectionDesc').value = desc;
    document.getElementById('editSectionOrder').value = order;
    var moduleSelect = document.getElementById('editSectionModule');
    if (moduleSelect) {
        moduleSelect.value = moduleId || '';
    }
    new bootstrap.Modal(document.getElementById('editSectionModal')).show();
}

function showDeleteModal(message, formId, action) {
    document.getElementById('deleteConfirmMessage').innerHTML = message;
    document.getElementById('deleteConfirmBtn').onclick = function() {
        var form = document.getElementById(formId);
        form.action = action;
        form.submit();
    };
    new bootstrap.Modal(document.getElementById('deleteConfirmModal')).show();
}

function deleteModule(id, name) {
    showDeleteModal(
        'Excluir módulo "<strong>' + name + '</strong>"?<br><br><span class="text-warning"><i class="fas fa-info-circle me-1"></i>As seções deste módulo serão desvinculadas e aparecerão como seções gerais.</span>',
        'deleteModuleForm',
        '/admin/modules/' + id + '/delete'
    );
}

function deleteSection(id, name) {
    showDeleteModal(
        'Excluir seção "<strong>' + name + '</strong>"?<br><br><span class="text-danger"><i class="fas fa-exclamation-circle me-1"></i>Todas as lições desta seção serão excluídas permanentemente.</span>',
        'deleteSectionForm',
        '/admin/sections/' + id + '/delete'
    );
}

function deleteLesson(id, name) {
    showDeleteModal(
        'Excluir lição "<strong>' + name + '</strong>"?<br><br><span class="text-danger"><i class="fas fa-exclamation-circle me-1"></i>Esta ação é irreversível.</span>',
        'deleteLessonForm',
        '/admin/lessons/' + id + '/delete'
    );
}

// Data for move modal - sections grouped by module
var moduleSectionsData = <?php
    $jsData = [];
    foreach ($modules as $mod) {
        $modSecs = $moduleSections[$mod['id']] ?? [];
        $jsData[$mod['id']] = array_map(function($s) {
            return ['id' => $s['id'], 'title' => $s['title']];
        }, $modSecs);
    }
    // orphan sections (module_id = null) -> key "0"
    $jsData[0] = array_map(function($s) {
        return ['id' => $s['id'], 'title' => $s['title']];
    }, $orphanSections);
    echo json_encode($jsData);
?>;

function openMoveModal(moduleId, moduleName) {
    document.getElementById('moveSourceName').textContent = moduleName;
    var list = document.getElementById('moveSectionsList');
    var sections = moduleSectionsData[moduleId] || [];
    list.innerHTML = '';
    if (sections.length === 0) {
        list.innerHTML = '<p class="text-muted small mb-0">Nenhuma seção neste módulo.</p>';
        return;
    }
    sections.forEach(function(sec) {
        var div = document.createElement('div');
        div.className = 'form-check';
        div.innerHTML = '<input type="checkbox" name="section_ids[]" value="' + sec.id + '" class="form-check-input move-check" id="moveSec' + sec.id + '" checked>' +
            '<label class="form-check-label" for="moveSec' + sec.id + '">' + sec.title + '</label>';
        list.appendChild(div);
    });
    // Remove source module from target dropdown
    var select = document.querySelector('#moveSectionsModal select[name="target_module_id"]');
    Array.from(select.options).forEach(function(opt) {
        opt.hidden = (opt.value == moduleId);
    });
    select.value = '';
    new bootstrap.Modal(document.getElementById('moveSectionsModal')).show();
}

function toggleAllMoveCheckboxes(checked) {
    document.querySelectorAll('.move-check').forEach(function(cb) { cb.checked = checked; });
}

function reorderItem(type, id, direction) {
    fetch('/admin/' + type + '/' + id + '/reorder', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'direction=' + direction
    }).then(r => r.json()).then(data => {
        if (data.success) location.reload();
    });
}
</script>
