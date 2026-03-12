<section class="py-5">
    <div class="container">
        <?php if (isset($_SESSION['success_message'])): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i><?php echo $_SESSION['success_message']; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success_message']); ?>
        <?php endif; ?>
        <div class="row">
            <!-- Info do Curso -->
            <div class="col-lg-8">
                <?php if (!empty($course['cover_image'])): ?>
                    <img src="<?= htmlspecialchars($course['cover_image']) ?>" class="img-fluid rounded mb-4" alt="<?= htmlspecialchars($course['title']) ?>">
                <?php else: ?>
                    <div class="rounded mb-4 d-flex align-items-center justify-content-center" style="height: 300px; background: linear-gradient(135deg, var(--primary-color), var(--dark-teal));">
                        <div class="text-center text-white">
                            <i class="fas fa-graduation-cap fa-4x mb-3"></i>
                            <h2><?= htmlspecialchars($course['title']) ?></h2>
                        </div>
                    </div>
                <?php endif; ?>

                <h1 class="mb-3"><?= htmlspecialchars($course['title']) ?></h1>

                <?php if (!empty($course['description'])): ?>
                    <p class="lead text-muted"><?= htmlspecialchars($course['description']) ?></p>
                <?php endif; ?>

                <!-- Detalhes -->
                <div class="row g-3 my-4">
                    <?php if (!empty($course['level'])): ?>
                        <div class="col-auto">
                            <span class="badge bg-light text-dark border px-3 py-2">
                                <i class="fas fa-signal me-1"></i>
                                <?= ucfirst($course['level']) ?>
                            </span>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($course['duration_hours'])): ?>
                        <div class="col-auto">
                            <span class="badge bg-light text-dark border px-3 py-2">
                                <i class="fas fa-clock me-1"></i>
                                <?= $course['duration_hours'] ?> horas
                            </span>
                        </div>
                    <?php endif; ?>
                    <?php
                    $totalLessons = 0;
                    foreach ($sections as $s) { $totalLessons += count($s['lessons']); }
                    ?>
                    <div class="col-auto">
                        <span class="badge bg-light text-dark border px-3 py-2">
                            <i class="fas fa-play-circle me-1"></i>
                            <?= $totalLessons ?> licoes
                        </span>
                    </div>
                    <div class="col-auto">
                        <span class="badge bg-light text-dark border px-3 py-2">
                            <i class="fas fa-folder me-1"></i>
                            <?= count($sections) ?> modulos
                        </span>
                    </div>
                </div>

                <!-- Conteudo do Curso -->
                <h3 class="mt-5 mb-3">Conteudo do Curso</h3>

                <?php
                // Organizar seções por módulo
                $hasModules = !empty($modules);
                $orphanSections = [];
                $moduleSectionsMap = [];
                if ($hasModules) {
                    foreach ($sections as $sec) {
                        if (empty($sec['module_id'])) {
                            $orphanSections[] = $sec;
                        } else {
                            $moduleSectionsMap[$sec['module_id']][] = $sec;
                        }
                    }
                }

                // Helper: calculate module status
                function getModuleStatus($modSections, $completedLessons) {
                    $totalLessons = 0;
                    $doneLessons = 0;
                    foreach ($modSections as $sec) {
                        foreach ($sec['lessons'] as $l) {
                            $totalLessons++;
                            if (in_array($l['id'], $completedLessons ?? [])) $doneLessons++;
                        }
                    }
                    if ($totalLessons === 0) return ['status' => 'empty', 'done' => 0, 'total' => 0, 'pct' => 0];
                    if ($doneLessons === 0) return ['status' => 'not_started', 'done' => 0, 'total' => $totalLessons, 'pct' => 0];
                    if ($doneLessons >= $totalLessons) return ['status' => 'completed', 'done' => $doneLessons, 'total' => $totalLessons, 'pct' => 100];
                    return ['status' => 'in_progress', 'done' => $doneLessons, 'total' => $totalLessons, 'pct' => round($doneLessons / $totalLessons * 100)];
                }

                // Helper para renderizar lista de licoes de uma secao
                function renderSectionLessons($section, $course, $enrollment, $completedLessons, $sectionId, $collapsed = true, $parentId = 'courseContent') {
                ?>
                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button <?= $collapsed ? 'collapsed' : '' ?>"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#section-<?= $sectionId ?>">
                            <strong><?= htmlspecialchars($section['title']) ?></strong>
                            <span class="badge bg-secondary ms-2"><?= count($section['lessons']) ?> licoes</span>
                            <?php
                            if ($enrollment && !empty($completedLessons)) {
                                $doneSec = count(array_filter($section['lessons'], fn($l) => in_array($l['id'], $completedLessons)));
                                if ($doneSec > 0):
                            ?>
                                <span class="badge bg-success ms-2"><?= $doneSec ?>/<?= count($section['lessons']) ?> concluidas</span>
                            <?php endif; } ?>
                        </button>
                    </h2>
                    <div id="section-<?= $sectionId ?>"
                         class="accordion-collapse collapse <?= !$collapsed ? 'show' : '' ?>">
                        <div class="accordion-body p-0">
                            <ul class="list-group list-group-flush">
                                <?php foreach ($section['lessons'] as $lessonItem): ?>
                                    <?php $isDone = in_array($lessonItem['id'], $completedLessons ?? []); ?>
                                    <li class="list-group-item d-flex align-items-center <?= $enrollment ? 'list-group-item-action' : '' ?> <?= $isDone ? 'bg-light' : '' ?>">
                                        <?php if ($enrollment): ?>
                                            <a href="/curso/<?= htmlspecialchars($course['slug']) ?>/licao/<?= $lessonItem['id'] ?>"
                                               class="d-flex align-items-center w-100 text-decoration-none text-dark">
                                                <?php if ($isDone): ?>
                                                    <span class="me-3 d-inline-flex align-items-center justify-content-center rounded-circle" style="width:28px;height:28px;background:#d4edda;">
                                                        <i class="fas fa-check text-success" style="font-size:.75rem;"></i>
                                                    </span>
                                                <?php else: ?>
                                                    <span class="me-3 d-inline-flex align-items-center justify-content-center rounded-circle" style="width:28px;height:28px;background:#e9ecef;">
                                                        <i class="fas fa-play text-secondary" style="font-size:.65rem;"></i>
                                                    </span>
                                                <?php endif; ?>
                                                <div class="flex-grow-1 <?= $isDone ? 'text-muted text-decoration-line-through' : '' ?>">
                                                    <?= htmlspecialchars($lessonItem['title']) ?>
                                                </div>
                                                <?php if (!empty($lessonItem['video_duration'])): ?>
                                                    <small class="text-muted ms-2">
                                                        <?= floor($lessonItem['video_duration'] / 60) ?>:<?= str_pad($lessonItem['video_duration'] % 60, 2, '0', STR_PAD_LEFT) ?>
                                                    </small>
                                                <?php endif; ?>
                                                <?php if ($isDone): ?>
                                                    <span class="badge bg-success ms-2">Concluida</span>
                                                <?php endif; ?>
                                            </a>
                                        <?php else: ?>
                                            <span class="me-3 d-inline-flex align-items-center justify-content-center rounded-circle" style="width:28px;height:28px;background:#f8f9fa;">
                                                <i class="fas fa-lock text-muted" style="font-size:.65rem;"></i>
                                            </span>
                                            <div class="flex-grow-1 text-muted">
                                                <?= htmlspecialchars($lessonItem['title']) ?>
                                            </div>
                                            <?php if (!empty($lessonItem['video_duration'])): ?>
                                                <small class="text-muted ms-2">
                                                    <?= floor($lessonItem['video_duration'] / 60) ?>:<?= str_pad($lessonItem['video_duration'] % 60, 2, '0', STR_PAD_LEFT) ?>
                                                </small>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <?php } ?>

                <?php if ($hasModules): ?>
                    <!-- T-7.2: Module cards with status -->
                    <?php if ($enrollment): ?>
                    <div class="row g-3 mb-4">
                        <?php foreach ($modules as $module):
                            $modSections = $moduleSectionsMap[$module['id']] ?? [];
                            $modStatus = getModuleStatus($modSections, $completedLessons);
                            $statusColors = [
                                'not_started' => ['bg' => '#f8f9fa', 'border' => '#dee2e6', 'text' => 'Nao iniciado', 'badge' => 'bg-secondary'],
                                'in_progress' => ['bg' => '#fff3cd', 'border' => '#ffb606', 'text' => 'Em andamento', 'badge' => 'bg-warning text-dark'],
                                'completed' => ['bg' => '#d4edda', 'border' => '#28a745', 'text' => 'Concluido', 'badge' => 'bg-success'],
                                'empty' => ['bg' => '#f8f9fa', 'border' => '#dee2e6', 'text' => 'Vazio', 'badge' => 'bg-light text-dark'],
                            ];
                            $sc = $statusColors[$modStatus['status']];
                            $lessonCountMod = 0;
                            foreach ($modSections as $ms) { $lessonCountMod += count($ms['lessons']); }
                        ?>
                        <div class="col-md-6 col-lg-4">
                            <div class="card border-0 shadow-sm h-100" style="border-left: 4px solid <?= $sc['border'] ?> !important;">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="fw-bold mb-0">
                                            <i class="fas fa-layer-group me-1 text-warning"></i>
                                            <?= htmlspecialchars($module['title']) ?>
                                        </h6>
                                        <span class="badge <?= $sc['badge'] ?>"><?= $sc['text'] ?></span>
                                    </div>
                                    <?php if (!empty($module['description'])): ?>
                                        <p class="small text-muted mb-2"><?= htmlspecialchars($module['description']) ?></p>
                                    <?php endif; ?>
                                    <div class="d-flex gap-2 mb-2">
                                        <small class="text-muted"><i class="fas fa-list me-1"></i><?= count($modSections) ?> secoes</small>
                                        <small class="text-muted"><i class="fas fa-play-circle me-1"></i><?= $lessonCountMod ?> licoes</small>
                                    </div>
                                    <?php if ($modStatus['total'] > 0): ?>
                                    <div class="progress" style="height: 6px;">
                                        <div class="progress-bar bg-success" style="width: <?= $modStatus['pct'] ?>%"></div>
                                    </div>
                                    <small class="text-muted"><?= $modStatus['done'] ?>/<?= $modStatus['total'] ?> licoes concluidas</small>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>

                    <?php // Secoes orfas primeiro ?>
                    <?php if (!empty($orphanSections)): ?>
                        <div class="accordion mb-3" id="courseContentOrphan">
                            <?php foreach ($orphanSections as $i => $section): ?>
                                <?php renderSectionLessons($section, $course, $enrollment, $completedLessons, $section['id'], $i > 0, 'courseContentOrphan'); ?>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <?php // Modulos with sections ?>
                    <?php foreach ($modules as $mi => $module):
                        $modSections = $moduleSectionsMap[$module['id']] ?? [];
                    ?>
                        <div class="card border-0 shadow-sm mb-3">
                            <div class="card-header bg-light border-bottom">
                                <h5 class="mb-0">
                                    <i class="fas fa-layer-group me-2 text-warning"></i>
                                    <?= htmlspecialchars($module['title']) ?>
                                    <span class="badge bg-secondary ms-2"><?= count($modSections) ?> secoes</span>
                                    <?php if ($enrollment):
                                        $ms = getModuleStatus($modSections, $completedLessons);
                                        $statusColors2 = [
                                            'not_started' => 'bg-secondary',
                                            'in_progress' => 'bg-warning text-dark',
                                            'completed' => 'bg-success',
                                            'empty' => 'bg-light text-dark',
                                        ];
                                        $statusLabels2 = [
                                            'not_started' => 'Nao iniciado',
                                            'in_progress' => 'Em andamento',
                                            'completed' => 'Concluido',
                                            'empty' => 'Vazio',
                                        ];
                                    ?>
                                        <span class="badge <?= $statusColors2[$ms['status']] ?> ms-2"><?= $statusLabels2[$ms['status']] ?></span>
                                    <?php endif; ?>
                                </h5>
                                <?php if (!empty($module['description'])): ?>
                                    <small class="text-muted"><?= htmlspecialchars($module['description']) ?></small>
                                <?php endif; ?>
                            </div>
                            <div class="card-body p-0">
                                <div class="accordion" id="moduleContent<?= $module['id'] ?>">
                                    <?php foreach ($modSections as $si => $section): ?>
                                        <?php renderSectionLessons($section, $course, $enrollment, $completedLessons, $section['id'], $si > 0 || $mi > 0, 'moduleContent' . $module['id']); ?>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="accordion" id="courseContent">
                        <?php foreach ($sections as $i => $section): ?>
                            <?php renderSectionLessons($section, $course, $enrollment, $completedLessons, $section['id'], $i > 0, 'courseContent'); ?>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <!-- T-7.4: Content categories navigation -->
                <?php if ($enrollment): ?>
                <div class="mt-5">
                    <h3 class="mb-3"><i class="fas fa-th-large me-2 text-primary"></i>Categorias do Curso</h3>
                    <div class="row g-3">
                        <?php if ($hasModules && count($modules) > 0): ?>
                        <div class="col-6 col-md-4 col-lg">
                            <a href="#courseModules" class="card border-0 shadow-sm text-decoration-none h-100" data-bs-toggle="collapse" role="button" aria-expanded="false">
                                <div class="card-body text-center py-3">
                                    <i class="fas fa-layer-group fa-2x text-warning mb-2"></i>
                                    <div class="fw-bold small">Modulos</div>
                                    <small class="text-muted"><?= count($modules) ?></small>
                                </div>
                            </a>
                        </div>
                        <?php endif; ?>
                        <div class="col-6 col-md-4 col-lg">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body text-center py-3">
                                    <i class="fas fa-play-circle fa-2x text-primary mb-2"></i>
                                    <div class="fw-bold small">Aulas</div>
                                    <small class="text-muted"><?= $totalLessons ?> licoes</small>
                                </div>
                            </div>
                        </div>
                        <?php if (!empty($quizzes)): ?>
                        <div class="col-6 col-md-4 col-lg">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body text-center py-3">
                                    <i class="fas fa-clipboard-check fa-2x text-success mb-2"></i>
                                    <div class="fw-bold small">Testes Avaliativos</div>
                                    <small class="text-muted"><?= count($quizzes) ?></small>
                                </div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <?php if (!empty($materialCount) && $materialCount > 0): ?>
                        <div class="col-6 col-md-4 col-lg">
                            <a href="/curso/<?= htmlspecialchars($course['slug']) ?>/materiais" class="card border-0 shadow-sm text-decoration-none h-100">
                                <div class="card-body text-center py-3">
                                    <i class="fas fa-paperclip fa-2x text-info mb-2"></i>
                                    <div class="fw-bold small">Material Complementar</div>
                                    <small class="text-muted"><?= $materialCount ?> arquivo<?= $materialCount > 1 ? 's' : '' ?></small>
                                </div>
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <!-- Quizzes Disponiveis (Testes Avaliativos) -->
                <?php if ($enrollment && !empty($quizzes)): ?>
                <h3 class="mt-5 mb-3"><i class="fas fa-clipboard-check me-2 text-primary"></i>Testes Avaliativos</h3>
                <div class="row g-3">
                    <?php foreach ($quizzes as $quiz): ?>
                    <div class="col-12">
                        <div class="card border-0 shadow-sm" style="border-left: 4px solid var(--primary-color) !important;">
                            <div class="card-body d-flex align-items-center justify-content-between gap-3 py-3 px-4">
                                <div class="d-flex align-items-center gap-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0"
                                         style="width:48px;height:48px;background:#e8f5e9;">
                                        <i class="fas fa-pen-alt" style="color:var(--primary-color);font-size:1.1rem;"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold"><?= htmlspecialchars($quiz['title']) ?></div>
                                        <?php if (!empty($quiz['description'])): ?>
                                            <small class="text-muted"><?= htmlspecialchars($quiz['description']) ?></small>
                                        <?php endif; ?>
                                        <div class="mt-1 d-flex gap-2 flex-wrap">
                                            <span class="badge bg-light text-dark border">
                                                <i class="fas fa-star me-1 text-warning"></i><?= $quiz['passing_score'] ?>% para aprovacao
                                            </span>
                                            <?php if ($quiz['attempts_allowed'] > 0): ?>
                                                <span class="badge bg-light text-dark border">
                                                    <i class="fas fa-redo me-1 text-info"></i><?= $quiz['attempts_allowed'] ?> tentativa(s)
                                                </span>
                                            <?php else: ?>
                                                <span class="badge bg-light text-dark border">
                                                    <i class="fas fa-infinity me-1 text-success"></i>Tentativas ilimitadas
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                                <a href="/curso/<?= htmlspecialchars($course['slug']) ?>/quiz/<?= $quiz['id'] ?>"
                                   class="btn btn-hansen btn-sm px-4 flex-shrink-0">
                                    <i class="fas fa-play me-1"></i> Iniciar
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Sidebar: Card de Acao -->
            <div class="col-lg-4">
                <div class="card shadow-sm sticky-top" style="top: 90px;">
                    <div class="card-body text-center p-4">
                        <?php if (!isset($_SESSION['user_id'])): ?>
                            <?php if ($course['is_free']): ?>
                                <span class="badge bg-success fs-6 mb-3 px-3 py-2">GRATUITO</span>
                            <?php elseif (!empty($course['price']) && $course['price'] > 0): ?>
                                <h3 class="text-green mb-3">R$ <?= number_format($course['price'], 2, ',', '.') ?></h3>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php if ($enrollment): ?>
                            <!-- Ja matriculado -->
                            <div class="mb-3">
                                <div class="progress" style="height: 25px; border-radius: 12px;">
                                    <div class="progress-bar"
                                         style="width: <?= $enrollment['overall_progress_percentage'] ?? 0 ?>%; background-color: var(--primary-color);">
                                        <?= round($enrollment['overall_progress_percentage'] ?? 0) ?>%
                                    </div>
                                </div>
                                <small class="text-muted mt-1 d-block">
                                    <?= $enrollment['videos_completed_count'] ?? 0 ?>/<?= $enrollment['total_videos_count'] ?? $totalLessons ?> licoes concluidas
                                </small>
                            </div>

                            <?php
                            // Encontrar primeira licao nao concluida ou a primeira
                            $firstLesson = null;
                            foreach ($sections as $s) {
                                foreach ($s['lessons'] as $l) {
                                    if (!$firstLesson) $firstLesson = $l;
                                }
                            }
                            ?>
                            <?php if ($firstLesson): ?>
                            <a href="/curso/<?= htmlspecialchars($course['slug']) ?>/licao/<?= $firstLesson['id'] ?>"
                               class="btn btn-hansen btn-lg w-100">
                                <i class="fas fa-play me-2"></i>
                                <?= ($enrollment['overall_progress_percentage'] ?? 0) > 0 ? 'Continuar' : 'Comecar' ?>
                            </a>
                            <?php else: ?>
                            <button class="btn btn-secondary btn-lg w-100" disabled>
                                <i class="fas fa-clock me-2"></i> Em breve
                            </button>
                            <?php endif; ?>
                            <?php if (!empty($materialCount) && $materialCount > 0): ?>
                            <a href="/curso/<?= htmlspecialchars($course['slug']) ?>/materiais" class="btn btn-outline-secondary w-100 mt-2">
                                <i class="fas fa-paperclip me-2"></i>Materiais de Apoio (<?= $materialCount ?>)
                            </a>
                            <?php endif; ?>
                        <?php elseif (isset($_SESSION['user_id'])): ?>
                            <!-- Logado mas nao matriculado -->
                            <form action="/curso/<?= htmlspecialchars($course['slug']) ?>/matricular" method="POST">
                                <button type="submit" class="btn btn-hansen btn-lg w-100">
                                    <i class="fas fa-user-plus me-2"></i>
                                    <?= $course['is_free'] ? 'Matricular-se Gratuitamente' : 'Matricular-se' ?>
                                </button>
                            </form>
                            <?php if (!$course['is_free'] && $course['price'] > 0): ?>
                                <small class="text-muted d-block mt-2">Apos a matricula, voce recebera instrucoes de pagamento.</small>
                            <?php endif; ?>
                        <?php else: ?>
                            <!-- Nao logado -->
                            <a href="/login" class="btn btn-hansen btn-lg w-100 mb-2">
                                <i class="fas fa-sign-in-alt me-2"></i> Entrar para Acessar
                            </a>
                            <small class="text-muted d-block">Nao tem conta? <a href="/registro" class="fw-bold">Cadastre-se</a></small>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
