<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/admin/descriptive-reports" class="text-decoration-none">Pareceres Descritivos</a></li>
        <li class="breadcrumb-item active" aria-current="page">Editar - <?php echo htmlspecialchars($report['student_name']); ?></li>
    </ol>
</nav>

<!-- Mensagens -->
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

<?php
    $axisPhotos = !empty($report['axis_photos']) ? json_decode($report['axis_photos'], true) : [];
    $axes = [
        'movement' => ['label' => 'Atividade de Movimento', 'icon' => 'fa-running'],
        'manual'   => ['label' => 'Atividade Manual', 'icon' => 'fa-hands'],
        'music'    => ['label' => 'Atividade Musical', 'icon' => 'fa-music'],
        'stories'  => ['label' => 'Atividade de Contos', 'icon' => 'fa-book-reader'],
        'pca'      => ['label' => 'Programa Comunicacao Ativa (PCA)', 'icon' => 'fa-comments']
    ];

    $statusBadge = match($report['status']) {
        'draft' => '<span class="badge bg-secondary">Rascunho</span>',
        'revision_requested' => '<span class="badge bg-warning text-dark">Revisao Solicitada</span>',
        default => '<span class="badge bg-secondary">Rascunho</span>'
    };
?>

<div class="d-flex justify-content-between align-items-start mb-4">
    <div>
        <a href="/admin/descriptive-reports/<?php echo $report['id']; ?>" class="text-decoration-none text-muted mb-2 d-block">
            <i class="fas fa-arrow-left me-2"></i> Voltar para preview
        </a>
        <h2 class="text-primary fw-bold mb-1">EDITAR PARECER DESCRITIVO</h2>
        <div class="d-flex align-items-center gap-2">
            <span class="text-muted"><?php echo htmlspecialchars($report['student_name']); ?> &middot; <?php echo $report['semester']; ?>o Sem / <?php echo $report['year']; ?></span>
            <?php echo $statusBadge; ?>
        </div>
    </div>
</div>

<?php if ($report['status'] === 'revision_requested' && !empty($report['revision_notes'])): ?>
    <div class="alert alert-warning mb-4">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>Revisao Solicitada:</strong> <?php echo nl2br(htmlspecialchars($report['revision_notes'])); ?>
    </div>
<?php endif; ?>

<?php if (!empty($report['observation_id'])): ?>
    <div class="alert alert-info mb-4 d-flex justify-content-between align-items-center flex-wrap gap-2">
        <div>
            <i class="fas fa-info-circle me-2"></i>
            O texto deste parecer foi compilado a partir de <strong>todas as observacoes do semestre</strong>.
            O link ao lado abre a observacao mais recente desse conjunto para facilitar ajustes manuais.
        </div>
        <div class="d-flex gap-2">
            <a href="/admin/observations/<?php echo $report['observation_id']; ?>/edit" class="btn btn-outline-primary btn-sm" target="_blank">
                <i class="fas fa-external-link-alt me-1"></i> Editar Observacao Mais Recente
            </a>
            <button type="button" class="btn btn-outline-success btn-sm" onclick="recompileFromObservation()" id="btnRecompile">
                <i class="fas fa-sync-alt me-1"></i> Recompilar Texto
            </button>
        </div>
    </div>
<?php endif; ?>

<form method="POST" action="/admin/descriptive-reports/<?php echo $report['id']; ?>/update" id="editForm">
    <input type="hidden" name="csrf_token" value="<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>">

    <!-- Tabs -->
    <ul class="nav nav-tabs mb-4" id="reportTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="cover-tab" data-bs-toggle="tab" data-bs-target="#cover-pane" type="button" role="tab">
                <i class="fas fa-image me-1"></i> Capa
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="text-tab" data-bs-toggle="tab" data-bs-target="#text-pane" type="button" role="tab">
                <i class="fas fa-pen me-1"></i> Texto da Crianca
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="photos-tab" data-bs-toggle="tab" data-bs-target="#photos-pane" type="button" role="tab">
                <i class="fas fa-camera me-1"></i> Fotos dos Eixos
            </button>
        </li>
    </ul>

    <div class="tab-content" id="reportTabContent">

        <!-- TAB: Capa -->
        <div class="tab-pane fade show active" id="cover-pane" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <h5 class="fw-bold mb-3"><i class="fas fa-image me-2 text-primary"></i>Foto de Capa</h5>
                    <div class="mb-3">
                        <label for="cover_photo_url" class="form-label">URL da Foto de Capa</label>
                        <input type="url" name="cover_photo_url" id="cover_photo_url" class="form-control"
                               value="<?php echo htmlspecialchars($report['cover_photo_url'] ?? ''); ?>"
                               placeholder="https://exemplo.com/foto.jpg">
                        <div class="form-text">Cole a URL de uma imagem para a capa do parecer.</div>
                    </div>

                    <?php if (!empty($report['cover_photo_url'])): ?>
                        <div class="text-center mt-3">
                            <p class="small text-muted mb-2">Preview atual:</p>
                            <img src="<?php echo htmlspecialchars($report['cover_photo_url']); ?>" class="img-fluid rounded" style="max-height:200px;" alt="Preview capa">
                        </div>
                    <?php endif; ?>

                    <div id="coverPreview" class="text-center mt-3" style="display:none;">
                        <p class="small text-muted mb-2">Preview:</p>
                        <img id="coverPreviewImg" class="img-fluid rounded" style="max-height:200px;" alt="Preview capa">
                    </div>
                </div>
            </div>
        </div>

        <!-- TAB: Texto da Crianca -->
        <div class="tab-pane fade" id="text-pane" role="tabpanel">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="fw-bold mb-0"><i class="fas fa-pen me-2 text-primary"></i>Texto da Crianca</h5>
                        <button type="button" class="btn btn-outline-primary btn-sm" id="btnCorrectText" onclick="correctTextAI()">
                            <i class="fas fa-magic me-1"></i> Correcao Automatica IA
                        </button>
                    </div>

                    <?php if (!empty($report['student_text']) && $report['student_text'] !== ($report['student_text_edited'] ?? '')): ?>
                        <div class="alert alert-info small mb-3">
                            <i class="fas fa-info-circle me-1"></i>
                            O texto original compilado esta preservado. Voce esta editando uma copia.
                        </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <textarea name="student_text_edited" id="student_text_edited" class="form-control" rows="18" style="line-height:1.8; font-size:1rem;"><?php echo htmlspecialchars($report['student_text_edited'] ?? $report['student_text'] ?? ''); ?></textarea>
                        <div class="form-text">
                            <span id="charCount">0</span> caracteres
                        </div>
                    </div>

                    <div id="aiLoading" class="text-center py-3" style="display:none;">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Corrigindo...</span>
                        </div>
                        <p class="text-muted mt-2">Corrigindo texto com IA, aguarde...</p>
                    </div>

                    <div id="aiResult" class="alert alert-success" style="display:none;">
                        <i class="fas fa-check-circle me-1"></i> Texto corrigido com sucesso pela IA!
                    </div>

                    <div id="aiError" class="alert alert-danger" style="display:none;"></div>
                </div>
            </div>
        </div>

        <!-- TAB: Fotos dos Eixos -->
        <div class="tab-pane fade" id="photos-pane" role="tabpanel">
            <?php foreach ($axes as $axisKey => $axisInfo): ?>
                <?php $photos = $axisPhotos[$axisKey] ?? []; ?>
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white">
                        <h6 class="mb-0 fw-bold">
                            <i class="fas <?php echo $axisInfo['icon']; ?> me-2 text-primary"></i>
                            <?php echo $axisInfo['label']; ?>
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <?php for ($i = 0; $i < 3; $i++): ?>
                                <?php
                                    $photoUrl = $photos[$i]['url'] ?? '';
                                    $photoCaption = $photos[$i]['caption'] ?? '';
                                ?>
                                <div class="col-md-4">
                                    <div class="border rounded p-3 h-100">
                                        <label class="form-label small fw-bold">Foto <?php echo $i + 1; ?></label>
                                        <input type="url"
                                               name="axis_photos[<?php echo $axisKey; ?>][<?php echo $i; ?>][url]"
                                               class="form-control form-control-sm mb-2"
                                               value="<?php echo htmlspecialchars($photoUrl); ?>"
                                               placeholder="URL da foto">

                                        <?php if (!empty($photoUrl)): ?>
                                            <div class="text-center mb-2">
                                                <img src="<?php echo htmlspecialchars($photoUrl); ?>" class="img-fluid rounded" style="max-height:120px;object-fit:cover;" alt="Foto">
                                            </div>
                                        <?php endif; ?>

                                        <input type="text"
                                               name="axis_photos[<?php echo $axisKey; ?>][<?php echo $i; ?>][caption]"
                                               class="form-control form-control-sm"
                                               value="<?php echo htmlspecialchars($photoCaption); ?>"
                                               placeholder="Legenda da foto">
                                    </div>
                                </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>

    <!-- Botoes de Acao -->
    <div class="card border-0 shadow-sm mt-4">
        <div class="card-body d-flex justify-content-between align-items-center">
            <a href="/admin/descriptive-reports/<?php echo $report['id']; ?>" class="btn btn-light">
                <i class="fas fa-times me-1"></i> Cancelar
            </a>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-hansen text-white">
                    <i class="fas fa-save me-1"></i> Salvar Alteracoes
                </button>
            </div>
        </div>
    </div>

</form>

<script>
// Contador de caracteres
const textarea = document.getElementById('student_text_edited');
const charCount = document.getElementById('charCount');

function updateCharCount() {
    charCount.textContent = textarea.value.length;
}
textarea.addEventListener('input', updateCharCount);
updateCharCount();

// Preview da foto de capa
document.getElementById('cover_photo_url').addEventListener('input', function() {
    const url = this.value.trim();
    const preview = document.getElementById('coverPreview');
    const img = document.getElementById('coverPreviewImg');

    if (url) {
        img.src = url;
        img.onerror = function() { preview.style.display = 'none'; };
        img.onload = function() { preview.style.display = 'block'; };
    } else {
        preview.style.display = 'none';
    }
});

// Recompilar texto da observacao
function recompileFromObservation() {
    if (!confirm('Isso substituira o texto atual pelo texto atualizado da observacao. Deseja continuar?')) return;
    const btn = document.getElementById('btnRecompile');
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Recompilando...';

    fetch('/admin/descriptive-reports/<?php echo $report['id']; ?>/recompile', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>' },
        body: JSON.stringify({ csrf_token: '<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>' })
    })
    .then(r => r.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-sync-alt me-1"></i> Recompilar Texto';
        if (data.success) {
            textarea.value = data.text;
            updateCharCount();
            alert('Texto recompilado com sucesso!');
        } else {
            alert('Erro: ' + (data.error || 'Nao foi possivel recompilar.'));
        }
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-sync-alt me-1"></i> Recompilar Texto';
        alert('Erro de conexao.');
    });
}

// Correcao automatica via IA
function correctTextAI() {
    const btn = document.getElementById('btnCorrectText');
    const loading = document.getElementById('aiLoading');
    const result = document.getElementById('aiResult');
    const error = document.getElementById('aiError');

    btn.disabled = true;
    loading.style.display = 'block';
    result.style.display = 'none';
    error.style.display = 'none';

    fetch('/admin/descriptive-reports/<?php echo $report['id']; ?>/correct-text', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': '<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>'
        },
        body: JSON.stringify({ csrf_token: '<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>' })
    })
    .then(response => response.json())
    .then(data => {
        loading.style.display = 'none';
        btn.disabled = false;

        if (data.success) {
            textarea.value = data.text;
            updateCharCount();
            result.style.display = 'block';
            setTimeout(() => { result.style.display = 'none'; }, 5000);
        } else {
            error.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i> ' + (data.error || 'Erro desconhecido');
            error.style.display = 'block';
        }
    })
    .catch(err => {
        loading.style.display = 'none';
        btn.disabled = false;
        error.innerHTML = '<i class="fas fa-exclamation-triangle me-1"></i> Erro de conexao. Tente novamente.';
        error.style.display = 'block';
    });
}
</script>
