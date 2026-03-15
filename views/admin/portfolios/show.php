<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/admin/portfolios" class="text-decoration-none">Portfolios</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($portfolio['classroom_name']) ?></li>
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

<?php
    $statusColors = [
        'pending' => ['bg' => 'warning', 'icon' => 'clock', 'label' => 'Pendente'],
        'finalized' => ['bg' => 'success', 'icon' => 'check-circle', 'label' => 'Finalizado'],
        'revision_requested' => ['bg' => 'danger', 'icon' => 'exclamation-circle', 'label' => 'Revisao Solicitada']
    ];
    $status = $statusColors[$portfolio['status']] ?? $statusColors['pending'];
    $role = $_SESSION['user_role'] ?? 'admin';
    $isCoordenador = ($role === 'coordenador');

    $axes = [
        'movement' => ['name' => 'Movimento', 'icon' => 'running', 'color' => '#e74c3c'],
        'manual' => ['name' => 'Atividades Manuais', 'icon' => 'paint-brush', 'color' => '#3498db'],
        'stories' => ['name' => 'Contos', 'icon' => 'book', 'color' => '#2ecc71'],
        'music' => ['name' => 'Musical', 'icon' => 'music', 'color' => '#9b59b6'],
        'pca' => ['name' => 'Programa Comunicacao Ativa (PCA)', 'icon' => 'comments', 'color' => '#f39c12']
    ];
?>

<div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
    <div>
        <h2 class="text-primary fw-bold mb-1">
            <i class="fas fa-book-open me-2"></i><?= htmlspecialchars($portfolio['classroom_name']) ?>
        </h2>
        <p class="text-muted mb-0">
            <?= $portfolio['semester'] ?>o Semestre / <?= $portfolio['year'] ?>
            <span class="badge bg-<?= $status['bg'] ?> ms-2"><i class="fas fa-<?= $status['icon'] ?> me-1"></i><?= $status['label'] ?></span>
        </p>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <?php if ($portfolio['status'] !== 'finalized'): ?>
            <a href="/admin/portfolios/<?= $portfolio['id'] ?>/edit" class="btn btn-outline-primary">
                <i class="fas fa-edit me-2"></i>Editar
            </a>
            <form method="POST" action="/admin/portfolios/<?= $portfolio['id'] ?>/finalize" class="d-inline"
                  onsubmit="return confirm('Finalizar este portfolio? Ele nao podera mais ser editado.')">
                <button type="submit" class="btn btn-success"><i class="fas fa-check me-2"></i>Finalizar</button>
            </form>
        <?php endif; ?>

        <?php if ($portfolio['status'] === 'finalized'): ?>
            <a href="/admin/portfolios/<?= $portfolio['id'] ?>/export-pdf" class="btn btn-outline-danger" target="_blank">
                <i class="fas fa-file-pdf me-2"></i>Exportar PDF
            </a>
        <?php endif; ?>

        <?php if ($portfolio['status'] === 'finalized' && ($role === 'admin' || $isCoordenador)): ?>
            <form method="POST" action="/admin/portfolios/<?= $portfolio['id'] ?>/reopen" class="d-inline"
                  onsubmit="return confirm('Reabrir este portfolio para edicao?')">
                <button type="submit" class="btn btn-outline-warning"><i class="fas fa-undo me-2"></i>Reabrir</button>
            </form>
        <?php endif; ?>

        <?php if ($isCoordenador && $portfolio['status'] !== 'revision_requested'): ?>
            <button class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#revisionModal">
                <i class="fas fa-exclamation-circle me-2"></i>Solicitar Revisao
            </button>
        <?php endif; ?>

        <a href="/admin/portfolios" class="btn btn-outline-secondary"><i class="fas fa-arrow-left me-2"></i>Voltar</a>
    </div>
</div>

<?php if ($portfolio['status'] === 'revision_requested' && !empty($portfolio['revision_notes'])): ?>
<div class="alert alert-danger mb-4">
    <h6 class="fw-bold"><i class="fas fa-exclamation-circle me-2"></i>Revisao Solicitada</h6>
    <p class="mb-0"><?= nl2br(htmlspecialchars($portfolio['revision_notes'])) ?></p>
</div>
<?php endif; ?>

<!-- Capa -->
<div class="card border-0 shadow-sm mb-4">
    <?php if ($portfolio['cover_photo_url']): ?>
    <img src="<?= htmlspecialchars($portfolio['cover_photo_url']) ?>" class="card-img-top" style="max-height:400px;object-fit:cover;" alt="Capa">
    <?php else: ?>
    <div class="card-img-top d-flex align-items-center justify-content-center" style="height:250px;background:linear-gradient(135deg,#007e66,#00a884);">
        <div class="text-center text-white">
            <i class="fas fa-book-open fa-4x mb-3 opacity-50"></i>
            <h3><?= htmlspecialchars($portfolio['classroom_name']) ?></h3>
            <p><?= $portfolio['semester'] ?>o Semestre - <?= $portfolio['year'] ?></p>
        </div>
    </div>
    <?php endif; ?>
    <div class="card-body">
        <div class="row text-center">
            <div class="col-md-4"><strong>Escola:</strong> <?= htmlspecialchars($portfolio['school_name'] ?? '-') ?></div>
            <div class="col-md-4"><strong>Professor(a):</strong> <?= htmlspecialchars($portfolio['teacher_name'] ?? '-') ?></div>
            <div class="col-md-4"><strong>Faixa Etaria:</strong> <?= htmlspecialchars($portfolio['age_group'] ?? '-') ?></div>
        </div>
    </div>
</div>

<!-- Mensagem -->
<?php if (!empty($portfolio['teacher_message'])): ?>
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <h4 class="fw-bold mb-3"><i class="fas fa-envelope text-info me-2"></i>Mensagem para a Turma</h4>
        <div class="bg-light rounded p-4">
            <?= nl2br(htmlspecialchars($portfolio['teacher_message'])) ?>
        </div>
        <?php if (!empty($portfolio['teacher_message_corrected'])): ?>
        <div class="mt-3 p-3 border-start border-3 border-success bg-light">
            <h6 class="text-success fw-bold"><i class="fas fa-check-circle me-2"></i>Versao Corrigida (IA)</h6>
            <?= nl2br(htmlspecialchars($portfolio['teacher_message_corrected'])) ?>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<!-- Eixos -->
<?php foreach ($axes as $key => $axis): ?>
<?php
    $photos = $portfolio["axis_{$key}_photos"] ?? [];
    $description = $portfolio["axis_{$key}_description"] ?? '';
    if (empty($photos) && empty($description)) continue;
?>
<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-white border-0 p-4 pb-2">
        <h4 class="fw-bold mb-0">
            <i class="fas fa-<?= $axis['icon'] ?> me-2" style="color:<?= $axis['color'] ?>"></i>
            <?= $axis['name'] ?>
        </h4>
    </div>
    <div class="card-body p-4 pt-2">
        <?php if (!empty($description)): ?>
        <p class="mb-3"><?= nl2br(htmlspecialchars($description)) ?></p>
        <?php endif; ?>

        <?php if (!empty($photos)): ?>
        <div class="row g-3">
            <?php foreach ($photos as $photo): ?>
            <div class="col-md-4">
                <div class="card border">
                    <img src="<?= htmlspecialchars($photo['url'] ?? '') ?>" class="card-img-top" style="height:200px;object-fit:cover;" alt="">
                    <?php if (!empty($photo['caption'])): ?>
                    <div class="card-body p-2">
                        <small class="text-muted"><?= htmlspecialchars($photo['caption']) ?></small>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php endforeach; ?>

<!-- Revision Modal -->
<?php if ($isCoordenador): ?>
<div class="modal fade" id="revisionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/admin/portfolios/<?= $portfolio['id'] ?>/request-revision">
                <div class="modal-header">
                    <h5 class="modal-title">Solicitar Revisao</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label fw-bold">Observacoes para revisao:</label>
                    <textarea name="revision_notes" class="form-control" rows="4" required
                        placeholder="Descreva o que precisa ser revisado..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger"><i class="fas fa-exclamation-circle me-2"></i>Solicitar Revisao</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
