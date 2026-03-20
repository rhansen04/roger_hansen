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
        'stories' => ['name' => 'Contos', 'icon' => 'book', 'color' => '#2ecc71',
            'desc' => 'Contacao de historias, teatro de fantoches, dramatizacao e literatura infantil.'],
        'music' => ['name' => 'Musical', 'icon' => 'music', 'color' => '#9b59b6',
            'desc' => 'Atividades musicais: cantar, tocar instrumentos, ritmos corporais e apreciacao musical.'],
        'pca' => ['name' => 'Programa Comunicacao Ativa (PCA)', 'icon' => 'comments', 'color' => '#f39c12',
            'desc' => 'Desenvolvimento da comunicacao e expressao verbal da crianca por meio de atividades dirigidas.']
    ];
?>

<form method="POST" action="<?= $action ?>">

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
                        <div id="cover-preview" class="mb-2 <?= empty($portfolio['cover_photo_url']) ? 'd-none' : '' ?>">
                            <img src="<?= htmlspecialchars($portfolio['cover_photo_url'] ?? '') ?>" class="img-thumbnail" id="cover-preview-img" style="max-height:200px;">
                            <button type="button" class="btn btn-sm btn-outline-danger ms-2" onclick="clearCover()">
                                <i class="fas fa-times"></i> Remover
                            </button>
                        </div>
                        <input type="hidden" name="cover_photo_bank_url" id="cover_photo_bank_url" value="<?= htmlspecialchars($portfolio['cover_photo_url'] ?? '') ?>">
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="openImagePicker('cover', null)">
                            <i class="fas fa-images me-1"></i> Selecionar do Banco de Imagens
                        </button>
                        <?php if (empty($bankImages)): ?>
                        <div class="form-text text-warning"><i class="fas fa-info-circle me-1"></i>Nenhuma imagem encontrada no banco desta turma. Adicione imagens pelo Banco de Imagens primeiro.</div>
                        <?php else: ?>
                        <div class="form-text"><?= count($bankImages) ?> imagem(ns) disponíveis no banco da turma.</div>
                        <?php endif; ?>
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

                    <!-- Fotos do eixo (max 3) via Banco de Imagens -->
                    <div class="mb-3">
                        <label class="form-label fw-bold">Fotos (max 3 por eixo) — Banco de Imagens</label>

                        <?php
                            $existingPhotos = $portfolio["axis_{$key}_photos"] ?? [];
                            if (!is_array($existingPhotos)) $existingPhotos = [];
                        ?>

                        <!-- Fotos existentes -->
                        <?php if (!empty($existingPhotos)): ?>
                        <div class="row g-2 mb-3" id="existing-<?= $key ?>">
                            <?php foreach ($existingPhotos as $idx => $photo): ?>
                            <div class="col-md-4">
                                <div class="card border">
                                    <img src="<?= htmlspecialchars($photo['url'] ?? '') ?>" class="card-img-top" style="height:120px;object-fit:cover;">
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

                        <!-- Novas fotos via banco -->
                        <div id="new-photos-<?= $key ?>" class="row g-2 mb-2"></div>
                        <input type="hidden" name="axis_<?= $key ?>_photo_count" id="count-<?= $key ?>" value="0">

                        <?php $remaining = 3 - count($existingPhotos); ?>
                        <?php if ($remaining > 0): ?>
                        <button type="button" class="btn btn-outline-primary btn-sm" onclick="openImagePicker('axis', '<?= $key ?>')">
                            <i class="fas fa-images me-1"></i> Adicionar foto do Banco de Imagens
                        </button>
                        <div class="form-text">Você pode adicionar até <?= $remaining ?> foto(s) neste eixo. Selecione do banco de imagens da turma.</div>
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

<!-- Modal Banco de Imagens -->
<div class="modal fade" id="imageBankModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-images me-2"></i>Selecionar do Banco de Imagens</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <?php if (!$isEdit): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>Selecione a turma primeiro para ver as imagens disponíveis.
                    <select id="classroomPickerSelect" class="form-select form-select-sm mt-2" onchange="loadBankImages(this.value)">
                        <option value="">Escolha a turma...</option>
                        <?php foreach ($classrooms as $c): ?>
                        <option value="<?= $c['id'] ?>"><?= htmlspecialchars($c['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php endif; ?>
                <div id="bankImageGrid" class="row g-2">
                    <?php if (!empty($bankImages)): ?>
                        <?php foreach ($bankImages as $img): ?>
                        <div class="col-6 col-md-3 col-lg-2">
                            <div class="card border-2 border-light h-100 image-bank-item" style="cursor:pointer"
                                 onclick="selectBankImage('<?= htmlspecialchars($img['url']) ?>', '<?= htmlspecialchars(addslashes($img['caption'] ?? $img['original_name'])) ?>')"
                                 data-url="<?= htmlspecialchars($img['url']) ?>"
                                 data-caption="<?= htmlspecialchars($img['caption'] ?? $img['original_name']) ?>">
                                <img src="<?= htmlspecialchars($img['url']) ?>" class="card-img-top" style="height:100px;object-fit:cover;">
                                <div class="card-body p-1">
                                    <small class="text-muted" style="font-size:0.7em"><?= htmlspecialchars($img['caption'] ?? $img['original_name']) ?></small>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                    <div class="col-12">
                        <div id="bankEmptyMsg" class="text-center text-muted py-4">
                            <i class="fas fa-images fa-3x mb-3"></i><br>
                            Nenhuma imagem disponível. Adicione imagens pelo Banco de Imagens primeiro.
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
            </div>
        </div>
    </div>
</div>

<script>
var _pickerTarget = null;  // 'cover' or axis key
var _pickerType = null;    // 'cover' or 'axis'

function openImagePicker(type, axisKey) {
    _pickerType = type;
    _pickerTarget = axisKey;
    var modal = new bootstrap.Modal(document.getElementById('imageBankModal'));
    modal.show();
}

function selectBankImage(url, caption) {
    if (_pickerType === 'cover') {
        document.getElementById('cover_photo_bank_url').value = url;
        var img = document.getElementById('cover-preview-img');
        img.src = url;
        document.getElementById('cover-preview').classList.remove('d-none');
    } else if (_pickerType === 'axis') {
        var key = _pickerTarget;
        var countEl = document.getElementById('count-' + key);
        var count = parseInt(countEl.value || '0');
        var grid = document.getElementById('new-photos-' + key);
        var col = document.createElement('div');
        col.className = 'col-md-4';
        col.innerHTML = '<div class="card border">' +
            '<img src="' + url + '" class="card-img-top" style="height:120px;object-fit:cover;">' +
            '<div class="card-body p-1"><small class="text-muted">' + caption + '</small></div>' +
            '</div>' +
            '<input type="hidden" name="axis_' + key + '_bank_urls[]" value="' + url + '">' +
            '<input type="hidden" name="axis_' + key + '_bank_captions[]" value="' + caption + '">';
        grid.appendChild(col);
        countEl.value = count + 1;
    }
    bootstrap.Modal.getInstance(document.getElementById('imageBankModal')).hide();
}

function clearCover() {
    document.getElementById('cover_photo_bank_url').value = '';
    document.getElementById('cover-preview').classList.add('d-none');
}

function loadBankImages(classroomId) {
    if (!classroomId) return;
    var grid = document.getElementById('bankImageGrid');
    grid.innerHTML = '<div class="col-12 text-center py-4"><i class="fas fa-spinner fa-spin fa-2x"></i></div>';
    fetch('/admin/api/image-bank/classroom/' + classroomId)
        .then(r => r.json())
        .then(images => {
            if (!images.length) {
                grid.innerHTML = '<div class="col-12"><div class="text-center text-muted py-4"><i class="fas fa-images fa-3x mb-3"></i><br>Nenhuma imagem disponível para esta turma.</div></div>';
                return;
            }
            grid.innerHTML = images.map(img =>
                '<div class="col-6 col-md-3 col-lg-2">' +
                '<div class="card border-2 border-light h-100 image-bank-item" style="cursor:pointer" ' +
                'onclick="selectBankImage(\'' + img.url + '\', \'' + (img.caption || img.original_name || '').replace(/'/g, "\\'") + '\')">' +
                '<img src="' + img.url + '" class="card-img-top" style="height:100px;object-fit:cover;">' +
                '<div class="card-body p-1"><small class="text-muted" style="font-size:0.7em">' + (img.caption || img.original_name || '') + '</small></div>' +
                '</div></div>'
            ).join('');
        })
        .catch(() => { grid.innerHTML = '<div class="col-12 text-center text-danger">Erro ao carregar imagens.</div>'; });
}
</script>

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
