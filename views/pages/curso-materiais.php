<section class="py-5">
    <div class="container">
        <div class="mb-4">
            <a href="/curso/<?= htmlspecialchars($course['slug']) ?>" class="text-decoration-none text-muted small"><i class="fas fa-arrow-left me-1"></i>Voltar ao curso</a>
            <h2 class="fw-bold mt-2" style="color:var(--primary-color)"><i class="fas fa-paperclip me-2"></i>Materiais de Apoio</h2>
            <p class="text-muted"><?= htmlspecialchars($course['title']) ?></p>
        </div>

        <?php if (empty($materials)): ?>
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center py-5">
                    <i class="fas fa-folder-open fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">Nenhum material disponível ainda.</h5>
                </div>
            </div>
        <?php else: ?>
            <?php
            // Agrupar por seção
            $grouped = ['Geral' => []];
            foreach ($materials as $m) {
                $key = $m['section_title'] ?? 'Geral';
                if (!isset($grouped[$key])) {
                    $grouped[$key] = [];
                }
                $grouped[$key][] = $m;
            }
            ?>
            <?php foreach ($grouped as $sectionName => $items): ?>
                <?php if (empty($items)) continue; ?>
                <h5 class="fw-bold mt-4 mb-3 border-bottom pb-2" style="color:var(--primary-color)"><?= htmlspecialchars($sectionName) ?></h5>
                <div class="row g-3 mb-3">
                    <?php foreach ($items as $m):
                        $iconMap = [
                            'pdf'   => ['icon'=>'fas fa-file-pdf',   'color'=>'#dc3545', 'label'=>'PDF'],
                            'excel' => ['icon'=>'fas fa-file-excel', 'color'=>'#28a745', 'label'=>'Excel'],
                            'image' => ['icon'=>'fas fa-file-image', 'color'=>'#17a2b8', 'label'=>'Imagem'],
                            'video' => ['icon'=>'fas fa-file-video', 'color'=>'#ffc107', 'label'=>'Vídeo'],
                            'other' => ['icon'=>'fas fa-file',       'color'=>'#6c757d', 'label'=>'Arquivo'],
                        ];
                        $ft = $iconMap[$m['file_type']] ?? $iconMap['other'];
                        $sizeKb = $m['file_size'] > 0 ? round($m['file_size']/1024) . ' KB' : '';
                    ?>
                    <div class="col-md-6 col-lg-4">
                        <div class="card border-0 shadow-sm h-100">
                            <div class="card-body d-flex align-items-start gap-3">
                                <div style="font-size:2rem; color:<?= $ft['color'] ?>; flex-shrink:0">
                                    <i class="<?= $ft['icon'] ?>"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="fw-bold small"><?= htmlspecialchars($m['title']) ?></div>
                                    <?php if ($m['description']): ?>
                                        <div class="text-muted small"><?= htmlspecialchars($m['description']) ?></div>
                                    <?php endif; ?>
                                    <div class="mt-1">
                                        <span class="badge bg-light text-dark border small"><?= $ft['label'] ?></span>
                                        <?php if ($sizeKb): ?><span class="text-muted small ms-1"><?= $sizeKb ?></span><?php endif; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer bg-transparent border-0 pt-0">
                                <a href="/material/<?= $m['id'] ?>/download" class="btn btn-sm w-100" style="background:var(--primary-color);color:white">
                                    <i class="fas fa-download me-1"></i>Baixar
                                </a>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</section>
