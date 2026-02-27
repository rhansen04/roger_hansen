<!-- CSRF Token para AJAX -->
<meta name="csrf-token" content="<?= htmlspecialchars($csrfToken, ENT_QUOTES, 'UTF-8') ?>">

<style>
    .player-container { background: #1a1a1a; }
    .player-wrapper { position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; }
    .player-wrapper #youtube-player { position: absolute; top: 0; left: 0; width: 100%; height: 100%; }

    .lesson-sidebar { max-height: calc(100vh - 100px); overflow-y: auto; }
    .lesson-item { padding: 10px 15px; border-bottom: 1px solid #eee; display: flex; align-items: center; gap: 10px; text-decoration: none; color: var(--text-dark); transition: 0.2s; font-size: 0.9rem; }
    .lesson-item:hover { background: var(--pale-mint); color: var(--text-dark); }
    .lesson-item.active { background: var(--primary-color); color: white; }
    .lesson-item.active .text-muted { color: rgba(255,255,255,0.7) !important; }
    .lesson-item .lesson-number { width: 24px; height: 24px; border-radius: 50%; background: #eee; display: flex; align-items: center; justify-content: center; font-size: 0.75rem; font-weight: 700; flex-shrink: 0; }
    .lesson-item.active .lesson-number { background: rgba(255,255,255,0.2); color: white; }
    .lesson-item.completed .lesson-number { background: #198754; color: white; }

    .section-header { background: var(--bg-light); padding: 8px 15px; font-weight: 700; font-size: 0.8rem; text-transform: uppercase; color: var(--dark-teal); letter-spacing: 0.5px; border-bottom: 1px solid #ddd; }
    .section-header + .section-header { border-top: none; }

    .progress { height: 8px; border-radius: 4px; }
    .progress-lg { height: 20px; border-radius: 10px; }
    .progress-lg .progress-bar { font-size: 0.75rem; line-height: 20px; }

    .nav-lesson { display: flex; gap: 10px; }
    .nav-lesson .btn { flex: 1; }

    @media (max-width: 991px) {
        .lesson-sidebar { max-height: 400px; }
    }
</style>

<div class="container-fluid px-0">
    <div class="row g-0">
        <!-- Coluna Principal: Player + Info -->
        <div class="col-lg-8 col-xl-9">
            <!-- Player de Video -->
            <div class="player-container">
                <div class="player-wrapper">
                    <div id="youtube-player"></div>
                </div>
            </div>

            <!-- Informacoes da Licao -->
            <div class="p-4">
                <!-- Navegacao entre licoes -->
                <div class="nav-lesson mb-3">
                    <?php if ($prevLesson): ?>
                        <a href="/curso/<?= htmlspecialchars($course['slug']) ?>/licao/<?= $prevLesson['id'] ?>"
                           class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-chevron-left me-1"></i> Anterior
                        </a>
                    <?php else: ?>
                        <button class="btn btn-outline-secondary btn-sm" disabled>
                            <i class="fas fa-chevron-left me-1"></i> Anterior
                        </button>
                    <?php endif; ?>

                    <?php if ($nextLesson): ?>
                        <a href="/curso/<?= htmlspecialchars($course['slug']) ?>/licao/<?= $nextLesson['id'] ?>"
                           class="btn btn-hansen btn-sm" id="btn-next-lesson">
                            Proxima <i class="fas fa-chevron-right ms-1"></i>
                        </a>
                    <?php else: ?>
                        <button class="btn btn-hansen btn-sm" disabled>
                            Ultima licao <i class="fas fa-flag-checkered ms-1"></i>
                        </button>
                    <?php endif; ?>
                </div>

                <!-- Titulo e descricao -->
                <h4 class="mb-2"><?= htmlspecialchars($lesson['title']) ?></h4>
                <?php if (!empty($lesson['description'])): ?>
                    <p class="text-muted mb-3"><?= htmlspecialchars($lesson['description']) ?></p>
                <?php endif; ?>

                <!-- Progresso da licao -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <small class="text-muted fw-bold">Progresso da Licao</small>
                        <small class="text-muted">
                            <?php if (!empty($lesson['video_duration'])): ?>
                                <i class="fas fa-clock me-1"></i>
                                <?= floor($lesson['video_duration'] / 60) ?>:<?= str_pad($lesson['video_duration'] % 60, 2, '0', STR_PAD_LEFT) ?>
                            <?php endif; ?>
                        </small>
                    </div>
                    <div class="progress progress-lg">
                        <div class="progress-bar bg-primary-hansen"
                             id="video-progress-bar"
                             role="progressbar"
                             style="width: <?= $currentProgress['percentage_watched'] ?? 0 ?>%; background-color: var(--primary-color);"
                             aria-valuenow="<?= $currentProgress['percentage_watched'] ?? 0 ?>"
                             aria-valuemin="0"
                             aria-valuemax="100">
                            <?= round($currentProgress['percentage_watched'] ?? 0) ?>%
                        </div>
                    </div>
                </div>

                <!-- Materiais (se houver) -->
                <?php if (!empty($lesson['material_file'])): ?>
                    <div class="mt-3">
                        <a href="<?= htmlspecialchars($lesson['material_file']) ?>" class="btn btn-outline-secondary btn-sm" target="_blank">
                            <i class="fas fa-download me-1"></i> Material de Apoio
                        </a>
                    </div>
                <?php endif; ?>

                <!-- Conteudo da licao (se houver) -->
                <?php if (!empty($lesson['content'])): ?>
                    <div class="mt-4 p-3 bg-white rounded shadow-sm">
                        <?= $lesson['content'] ?>
                    </div>
                <?php endif; ?>

                <!-- Voltar ao curso, Perguntas e Materiais -->
                <div class="mt-4 d-flex gap-2 flex-wrap">
                    <a href="/curso/<?= htmlspecialchars($course['slug']) ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Voltar ao Curso
                    </a>
                    <a href="/curso/<?= htmlspecialchars($course['slug']) ?>/perguntas" class="btn btn-outline-primary">
                        <i class="fas fa-comments me-1"></i> Perguntas e Respostas
                    </a>
                    <?php if (!empty($materials)): ?>
                        <a href="/curso/<?= htmlspecialchars($course['slug']) ?>/materiais" class="btn btn-outline-info">
                            <i class="fas fa-paperclip me-1"></i> Materiais de Apoio
                            <span class="badge bg-info text-white ms-1"><?= count($materials) ?></span>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Sidebar: Lista de Licoes -->
        <div class="col-lg-4 col-xl-3 border-start">
            <div class="lesson-sidebar">
                <!-- Progresso do Curso -->
                <div class="p-3 border-bottom" style="background: var(--pale-mint);">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <strong style="font-size: 0.85rem;"><?= htmlspecialchars($course['title']) ?></strong>
                    </div>
                    <div class="progress progress-lg mb-1">
                        <div class="progress-bar"
                             id="course-progress-bar"
                             role="progressbar"
                             style="width: <?= $enrollment['overall_progress_percentage'] ?? 0 ?>%; background-color: var(--primary-color);"
                             aria-valuenow="<?= $enrollment['overall_progress_percentage'] ?? 0 ?>">
                            <?= round($enrollment['overall_progress_percentage'] ?? 0) ?>%
                        </div>
                    </div>
                    <small class="text-muted" id="course-videos-count">
                        <?= $enrollment['videos_completed_count'] ?? 0 ?>/<?= $enrollment['total_videos_count'] ?? 0 ?> videos
                    </small>
                </div>

                <!-- Materiais de Apoio -->
                <?php if (!empty($materials)): ?>
                    <div class="section-header">
                        <i class="fas fa-paperclip me-1"></i> Materiais de Apoio
                    </div>
                    <?php foreach ($materials as $mat): ?>
                        <a href="/curso/<?= htmlspecialchars($course['slug']) ?>/materiais/<?= $mat['id'] ?>/download"
                           class="lesson-item" style="font-size: 0.85rem;">
                            <i class="fas fa-download text-muted"></i>
                            <div class="flex-grow-1">
                                <div><?= htmlspecialchars($mat['title']) ?></div>
                                <?php if (!empty($mat['file_size'])): ?>
                                    <small class="text-muted"><?= round($mat['file_size'] / 1024) ?> KB</small>
                                <?php endif; ?>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>

                <!-- Lista de Secoes e Licoes -->
                <?php
                $lessonCounter = 0;
                $hasModules = !empty($modules);

                // Helper para renderizar lições de uma seção na sidebar
                function renderSidebarSection($section, $course, $lesson, &$lessonCounter) {
                ?>
                    <div class="section-header">
                        <i class="fas fa-folder-open me-1"></i>
                        <?= htmlspecialchars($section['title']) ?>
                    </div>
                    <?php foreach ($section['lessons'] as $lessonItem):
                        $lessonCounter++;
                        $isActive = ($lessonItem['id'] == $lesson['id']);
                        $isLessonCompleted = !empty($lessonItem['is_completed']);
                        $classes = 'lesson-item';
                        if ($isActive) $classes .= ' active';
                        if ($isLessonCompleted) $classes .= ' completed';
                    ?>
                        <a href="/curso/<?= htmlspecialchars($course['slug']) ?>/licao/<?= $lessonItem['id'] ?>"
                           class="<?= $classes ?>"
                           data-lesson-id="<?= $lessonItem['id'] ?>">
                            <span class="lesson-number">
                                <?php if ($isLessonCompleted): ?>
                                    <i class="fas fa-check" style="font-size: 0.65rem;"></i>
                                <?php else: ?>
                                    <?= $lessonCounter ?>
                                <?php endif; ?>
                            </span>
                            <div class="flex-grow-1">
                                <div style="font-size: 0.85rem;"><?= htmlspecialchars($lessonItem['title']) ?></div>
                                <?php if (!empty($lessonItem['video_duration'])): ?>
                                    <small class="text-muted">
                                        <i class="fas fa-play-circle me-1"></i>
                                        <?= floor($lessonItem['video_duration'] / 60) ?>:<?= str_pad($lessonItem['video_duration'] % 60, 2, '0', STR_PAD_LEFT) ?>
                                    </small>
                                <?php endif; ?>
                            </div>
                            <span class="lesson-status">
                                <?php if ($isLessonCompleted): ?>
                                    <i class="fas fa-check-circle text-success"></i>
                                <?php elseif ($lessonItem['percentage_watched'] > 0): ?>
                                    <small class="text-muted"><?= round($lessonItem['percentage_watched']) ?>%</small>
                                <?php endif; ?>
                            </span>
                        </a>
                    <?php endforeach; ?>
                <?php }

                if ($hasModules):
                    // Organizar seções por módulo
                    $orphanSections = [];
                    $moduleSectionsMap = [];
                    foreach ($sections as $sec) {
                        if (empty($sec['module_id'])) {
                            $orphanSections[] = $sec;
                        } else {
                            $moduleSectionsMap[$sec['module_id']][] = $sec;
                        }
                    }

                    // Seções sem módulo
                    foreach ($orphanSections as $section):
                        renderSidebarSection($section, $course, $lesson, $lessonCounter);
                    endforeach;

                    // Módulos
                    foreach ($modules as $module):
                        $modSections = $moduleSectionsMap[$module['id']] ?? [];
                ?>
                    <div class="section-header" style="background: #fff3cd; color: #856404;">
                        <i class="fas fa-layer-group me-1"></i>
                        <?= htmlspecialchars($module['title']) ?>
                    </div>
                    <?php foreach ($modSections as $section):
                        renderSidebarSection($section, $course, $lesson, $lessonCounter);
                    endforeach; ?>
                <?php
                    endforeach;
                else:
                    // Sem módulos - layout original
                    foreach ($sections as $section):
                        renderSidebarSection($section, $course, $lesson, $lessonCounter);
                    endforeach;
                endif;
                ?>
            </div>
        </div>
    </div>
</div>

<!-- YouTube Iframe API -->
<script src="https://www.youtube.com/iframe_api"></script>

<!-- Video Player JS -->
<script src="/assets/js/video-player.js"></script>

<!-- Inicializacao -->
<script>
    const lessonData = {
        id: <?= (int) $lesson['id'] ?>,
        title: <?= json_encode($lesson['title']) ?>,
        videoUrl: <?= json_encode($lesson['video_url'] ?? '') ?>,
        videoDuration: <?= (int) ($lesson['video_duration'] ?? 0) ?>,
        enrollmentId: <?= (int) $enrollment['id'] ?>,
        courseId: <?= (int) $course['id'] ?>
    };

    const trackingConfig = {
        trackingInterval: <?= (int) (getenv('VIDEO_TRACKING_INTERVAL') ?: 5) ?> * 1000,
        completionThreshold: <?= (int) (getenv('VIDEO_COMPLETION_THRESHOLD') ?: 97) ?>
    };
</script>
