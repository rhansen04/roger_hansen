<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/admin/portfolios" class="text-decoration-none">Portfolios</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= $isEdit ? 'Editar' : 'Novo' ?> Portfolio</li>
    </ol>
</nav>

<?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i><?= $_SESSION['success_message'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['success_message']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error_message'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i><?= $_SESSION['error_message'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['error_message']); ?>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-primary fw-bold mb-0">
        <i class="fas fa-book-open me-2"></i><?= $isEdit ? 'Editar' : 'Novo' ?> Portfolio
    </h2>
    <a href="/admin/portfolios" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Voltar</a>
</div>

<?php
    $action = $isEdit ? "/admin/portfolios/{$portfolio['id']}/update" : '/admin/portfolios';

    $axes = [
        'movement' => ['name' => 'Movimento', 'icon' => 'running', 'color' => '#e74c3c',
            'desc' => 'Atividades que envolvem o corpo em movimento: correr, pular, dancar, equilibrar-se e explorar espacos.'],
        'manual' => ['name' => 'Atividades Manuais', 'icon' => 'paint-brush', 'color' => '#3498db',
            'desc' => 'Atividades de criacao e expressao: pintura, desenho, modelagem, recorte e colagem.'],
        'stories' => ['name' => 'Contos e Historias', 'icon' => 'book', 'color' => '#2ecc71',
            'desc' => 'Contacao de historias, teatro de fantoches, dramatizacao e literatura infantil.'],
        'music' => ['name' => 'Musical', 'icon' => 'music', 'color' => '#9b59b6',
            'desc' => 'Atividades musicais: cantar, tocar instrumentos, ritmos corporais e apreciacao musical.'],
        'pca' => ['name' => 'PCA - Projeto Coletivo', 'icon' => 'project-diagram', 'color' => '#f39c12',
            'desc' => 'Projetos coletivos de aprendizagem que integram diferentes areas do conhecimento.']
    ];
?>

<form method="POST" action="<?= $action ?>" enctype="multipart/form-data">

    <!-- Navigation Tabs -->
    <ul class="nav nav-pills mb-4 flex-wrap" id="portfolioTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="tab-cover" data-bs-toggle="pill" data-bs-target="#pane-cover" type="button">
                <i class="fas fa-image me-1"></i>Capa
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-page1" data-bs-toggle="pill" data-bs-target="#pane-page1" type="button">
                <i class="fas fa-star me-1"></i>Sobre a Magia
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-page2" data-bs-toggle="pill" data-bs-target="#pane-page2" type="button">
                <i class="fas fa-graduation-cap me-1"></i>Proposta
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-message" data-bs-toggle="pill" data-bs-target="#pane-message" type="button">
                <i class="fas fa-envelope me-1"></i>Mensagem
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-axes-intro" data-bs-toggle="pill" data-bs-target="#pane-axes-intro" type="button">
                <i class="fas fa-compass me-1"></i>Eixos
            </button>
        </li>
        <?php foreach ($axes as $key => $axis): ?>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="tab-<?= $key ?>" data-bs-toggle="pill" data-bs-target="#pane-<?= $key ?>" type="button">
                <i class="fas fa-<?= $axis['icon'] ?> me-1" style="color:<?= $axis['color'] ?>"></i><?= $axis['name'] ?>
            </button>
        </li>
        <?php endforeach; ?>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="portfolioTabContent">

        <!-- CAPA -->
        <div class="tab-pane fade show active" id="pane-cover" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-4">Capa do Portfolio</h4>

                    <?php if (!$isEdit): ?>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Turma <span class="text-danger">*</span></label>
                            <select name="classroom_id" class="form-select" required>
                                <option value="">Selecione a turma...</option>
                                <?php foreach ($classrooms as $c): ?>
                                <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?> (<?= $c['school_year'] ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Semestre <span class="text-danger">*</span></label>
                            <select name="semester" class="form-select" required>
                                <option value="1">1o Semestre</option>
                                <option value="2">2o Semestre</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold">Ano <span class="text-danger">*</span></label>
                            <input type="number" name="year" class="form-control" value="<?= date('Y') ?>" required>
                        </div>
                    </div>
                    <?php else: ?>
                    <div class="alert alert-info mb-4">
                        <strong>Turma:</strong> <?= htmlspecialchars($portfolio['classroom_name']) ?> |
                        <strong>Semestre:</strong> <?= $portfolio['semester'] ?>o |
                        <strong>Ano:</strong> <?= $portfolio['year'] ?>
                    </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Foto de Capa</label>
                        <?php if ($isEdit && !empty($portfolio['cover_photo_url'])): ?>
                        <div class="mb-2">
                            <img src="<?= htmlspecialchars($portfolio['cover_photo_url']) ?>" class="img-thumbnail" style="max-height:200px;">
                        </div>
                        <?php endif; ?>
                        <input type="file" name="cover_photo" class="form-control" accept="image/jpeg,image/png">
                        <div class="form-text">Imagem JPG/PNG para a capa do portfolio.</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- PAGINA 1: Sobre a Magia -->
        <div class="tab-pane fade" id="pane-page1" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-3"><i class="fas fa-star text-warning me-2"></i>Sobre a Magia do Portfolio</h4>
                    <div class="bg-light rounded p-4">
                        <p class="mb-3">O portfolio e uma ferramenta pedagogica que registra e celebra o percurso de aprendizagem das criancas ao longo do semestre.</p>
                        <p class="mb-3">Atraves de fotos, relatos e producoes, podemos acompanhar o desenvolvimento de cada crianca, valorizando suas conquistas, descobertas e interacoes com o mundo.</p>
                        <p class="mb-0">Este documento foi construido com carinho pela equipe pedagogica, refletindo os momentos significativos vivenciados em cada eixo de atividade.</p>
                    </div>
                    <div class="form-text mt-2"><i class="fas fa-info-circle me-1"></i>Esta pagina e de texto fixo e sera exibida automaticamente no portfolio.</div>
                </div>
            </div>
        </div>

        <!-- PAGINA 2: Proposta -->
        <div class="tab-pane fade" id="pane-page2" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-3"><i class="fas fa-graduation-cap text-primary me-2"></i>Proposta da Pedagogia Florenca</h4>
                    <div class="bg-light rounded p-4">
                        <p class="mb-3">A Pedagogia Florenca baseia-se na crenca de que cada crianca e unica e capaz de construir seu proprio conhecimento por meio da exploracao, da curiosidade e das interacoes sociais.</p>
                        <p class="mb-3">Nossa proposta pedagogica valoriza:</p>
                        <ul class="mb-3">
                            <li>O brincar como principal forma de aprendizagem</li>
                            <li>A expressao artistica em todas as suas formas</li>
                            <li>O respeito ao ritmo individual de cada crianca</li>
                            <li>A construcao coletiva do conhecimento</li>
                            <li>A parceria entre escola e familia</li>
                        </ul>
                        <p class="mb-0">Os eixos de atividade sao organizados para proporcionar experiencias ricas e diversificadas, estimulando o desenvolvimento integral.</p>
                    </div>
                    <div class="form-text mt-2"><i class="fas fa-info-circle me-1"></i>Esta pagina e de texto fixo e sera exibida automaticamente no portfolio.</div>
                </div>
            </div>
        </div>

        <!-- PAGINA 3: Mensagem -->
        <div class="tab-pane fade" id="pane-message" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-3"><i class="fas fa-envelope text-info me-2"></i>Mensagem para a Turma</h4>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Escreva uma mensagem carinhosa para a turma:</label>
                        <textarea name="teacher_message" class="form-control" rows="6" placeholder="Queridas criancas e familias..."><?= htmlspecialchars($portfolio['teacher_message'] ?? '') ?></textarea>
                    </div>

                    <?php if ($isEdit && !empty($portfolio['id'])): ?>
                    <div class="d-flex gap-2 mb-3">
                        <button type="button" class="btn btn-outline-primary" id="btnCorrectText" onclick="correctText(<?= $portfolio['id'] ?>)">
                            <i class="fas fa-magic me-2"></i>Corrigir Texto com IA
                        </button>
                    </div>

                    <?php if (!empty($portfolio['teacher_message_corrected'])): ?>
                    <div class="card bg-light border-0 mt-3">
                        <div class="card-body">
                            <h6 class="fw-bold text-success"><i class="fas fa-check-circle me-2"></i>Texto Corrigido pela IA:</h6>
                            <p class="mb-0" id="correctedText"><?= nl2br(htmlspecialchars($portfolio['teacher_message_corrected'])) ?></p>
                            <button type="button" class="btn btn-sm btn-outline-success mt-2" onclick="useCorrectedText()">
                                <i class="fas fa-check me-1"></i>Usar este texto
                            </button>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div id="correctionResult" class="mt-3" style="display:none;"></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- PAGINA 4: Eixos Intro -->
        <div class="tab-pane fade" id="pane-axes-intro" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-3"><i class="fas fa-compass text-success me-2"></i>Os Eixos de Atividades</h4>
                    <div class="bg-light rounded p-4">
                        <p class="mb-3">As atividades sao organizadas em cinco eixos principais, cada um oferecendo experiencias unicas para o desenvolvimento integral das criancas:</p>
                        <div class="row g-3">
                            <?php foreach ($axes as $key => $axis): ?>
                            <div class="col-md-6">
                                <div class="d-flex align-items-start">
                                    <i class="fas fa-<?= $axis['icon'] ?> fa-lg me-3 mt-1" style="color:<?= $axis['color'] ?>"></i>
                                    <div>
                                        <strong><?= $axis['name'] ?></strong>
                                        <p class="text-muted small mb-0"><?= $axis['desc'] ?></p>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <div class="form-text mt-2"><i class="fas fa-info-circle me-1"></i>Esta pagina e de texto fixo e sera exibida automaticamente no portfolio.</div>
                </div>
            </div>
        </div>

        <!-- EIXOS (2 paginas cada: texto fixo + fotos) -->
        <?php foreach ($axes as $key => $axis): ?>
        <div class="tab-pane fade" id="pane-<?= $key ?>" role="tabpanel">
            <div class="card border-0 shadow-sm mb-3">
                <div class="card-body p-4">
                    <h4 class="fw-bold mb-3">
                        <i class="fas fa-<?= $axis['icon'] ?> me-2" style="color:<?= $axis['color'] ?>"></i>
                        <?= $axis['name'] ?>
                    </h4>

                    <!-- Texto fixo do eixo -->
                    <div class="bg-light rounded p-3 mb-4">
                        <p class="mb-0 text-muted"><?= $axis['desc'] ?></p>
                    </div>

                    <!-- Descricao editavel -->
                    <div class="mb-4">
                        <label class="form-label fw-bold">Descricao das atividades realizadas:</label>
                        <textarea name="axis_<?= $key ?>_description" class="form-control" rows="4"
                            placeholder="Descreva as atividades realizadas neste eixo..."><?= htmlspecialchars($portfolio["axis_{$key}_description"] ?? '') ?></textarea>
                    </div>

                    <!-- Fotos do eixo (max 3) -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Fotos (max 3 por eixo)</label>

                        <?php
                            $existingPhotos = $portfolio["axis_{$key}_photos"] ?? [];
                            if (!is_array($existingPhotos)) $existingPhotos = [];
                        ?>

                        <!-- Fotos existentes -->
                        <?php if (!empty($existingPhotos)): ?>
                        <div class="row g-2 mb-3">
                            <?php foreach ($existingPhotos as $idx => $photo): ?>
                            <div class="col-md-4">
                                <div class="card border">
                                    <img src="<?= htmlspecialchars($photo['url'] ?? '') ?>" class="card-img-top" style="height:150px;object-fit:cover;">
                                    <div class="card-body p-2">
                                        <small class="text-muted"><?= htmlspecialchars($photo['caption'] ?? '') ?></small>
                                        <div class="form-check mt-1">
                                            <input type="checkbox" class="form-check-input" name="remove_<?= $key ?>_photos[]" value="<?= $idx ?>" id="rm_<?= $key ?>_<?= $idx ?>">
                                            <label class="form-check-label text-danger small" for="rm_<?= $key ?>_<?= $idx ?>">Remover</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>

                        <?php $remaining = 3 - count($existingPhotos); ?>
                        <?php if ($remaining > 0): ?>
                        <input type="file" name="axis_<?= $key ?>_photos[]" class="form-control mb-2" accept="image/jpeg,image/png" multiple>
                        <div class="form-text">Voce pode adicionar ate <?= $remaining ?> foto(s) neste eixo. JPG/PNG.</div>
                        <?php else: ?>
                        <div class="form-text text-warning">Limite de 3 fotos atingido. Remova alguma para adicionar novas.</div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>

    </div>

    <!-- Submit buttons -->
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body p-3 d-flex justify-content-between">
            <a href="/admin/portfolios" class="btn btn-outline-secondary">
                <i class="fas fa-times me-2"></i>Cancelar
            </a>
            <button type="submit" class="btn btn-hansen text-white">
                <i class="fas fa-save me-2"></i><?= $isEdit ? 'Salvar Alteracoes' : 'Criar Portfolio' ?>
            </button>
        </div>
    </div>
</form>

<script>
function correctText(portfolioId) {
    const btn = document.getElementById('btnCorrectText');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Corrigindo...';

    fetch('/admin/portfolios/' + portfolioId + '/correct-text', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'}
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-magic me-2"></i>Corrigir Texto com IA';

        const resultDiv = document.getElementById('correctionResult');
        if (data.success) {
            resultDiv.style.display = 'block';
            resultDiv.innerHTML = '<div class="card bg-light border-0"><div class="card-body">' +
                '<h6 class="fw-bold text-success"><i class="fas fa-check-circle me-2"></i>Texto Corrigido pela IA:</h6>' +
                '<p class="mb-2">' + data.corrected_text.replace(/\n/g, '<br>') + '</p>' +
                '<button type="button" class="btn btn-sm btn-outline-success" onclick="useCorrectedText()">' +
                '<i class="fas fa-check me-1"></i>Usar este texto</button></div></div>';

            // Store corrected text for later use
            resultDiv.dataset.corrected = data.corrected_text;
        } else {
            resultDiv.style.display = 'block';
            resultDiv.innerHTML = '<div class="alert alert-danger">' + (data.error || 'Erro ao corrigir texto.') + '</div>';
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-magic me-2"></i>Corrigir Texto com IA';
    });
}

function useCorrectedText() {
    const resultDiv = document.getElementById('correctionResult');
    const correctedEl = document.getElementById('correctedText');
    let corrected = '';

    if (resultDiv && resultDiv.dataset.corrected) {
        corrected = resultDiv.dataset.corrected;
    } else if (correctedEl) {
        corrected = correctedEl.innerText;
    }

    if (corrected) {
        document.querySelector('textarea[name="teacher_message"]').value = corrected;
    }
}
</script>
