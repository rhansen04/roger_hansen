<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/admin/descriptive-reports" class="text-decoration-none">Pareceres Descritivos</a></li>
        <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($report['student_name']); ?></li>
    </ol>
</nav>

<!-- Mensagens de Sucesso/Erro -->
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
    $role = $_SESSION['user_role'] ?? '';
    $isFinalized = ($report['status'] === 'finalized');
    $isRevision = ($report['status'] === 'revision_requested');
    $isCoordenadorOrAdmin = in_array($role, ['admin', 'coordenador']);

    $statusBadge = match($report['status']) {
        'draft' => '<span class="badge bg-secondary fs-6">Rascunho</span>',
        'finalized' => '<span class="badge bg-success fs-6">Finalizado</span>',
        'revision_requested' => '<span class="badge bg-warning text-dark fs-6">Revisao Solicitada</span>',
        default => '<span class="badge bg-secondary fs-6">Rascunho</span>'
    };

    $axisPhotos = !empty($report['axis_photos']) ? json_decode($report['axis_photos'], true) : [];
    $axisLabels = [
        'movement' => 'Atividade de Movimento',
        'manual' => 'Atividade Manual',
        'music' => 'Atividade Musical',
        'stories' => 'Atividade de Contos',
        'pca' => 'Programa Comunicacao Ativa (PCA)'
    ];
?>

<div class="d-flex justify-content-between align-items-start mb-4">
    <div>
        <a href="/admin/descriptive-reports" class="text-decoration-none text-muted mb-2 d-block">
            <i class="fas fa-arrow-left me-2"></i> Voltar para pareceres
        </a>
        <h2 class="text-primary fw-bold mb-1">PARECER DESCRITIVO</h2>
        <div class="d-flex align-items-center gap-2">
            <span class="text-muted"><?php echo htmlspecialchars($report['student_name']); ?> &middot; <?php echo $report['semester']; ?>o Semestre / <?php echo $report['year']; ?></span>
            <?php echo $statusBadge; ?>
        </div>
    </div>
    <div class="d-flex gap-2 flex-wrap">
        <?php if (!$isFinalized): ?>
            <a href="/admin/descriptive-reports/<?php echo $report['id']; ?>/edit" class="btn btn-outline-secondary">
                <i class="fas fa-edit me-1"></i> Editar
            </a>
            <form method="POST" action="/admin/descriptive-reports/<?php echo $report['id']; ?>/finalize" class="d-inline" onsubmit="return confirm('Deseja finalizar este parecer? Ele ficara bloqueado para edicao.')">
                <button type="submit" class="btn btn-success">
                    <i class="fas fa-check me-1"></i> Finalizar
                </button>
            </form>
        <?php endif; ?>

        <?php if ($isFinalized && $isCoordenadorOrAdmin): ?>
            <form method="POST" action="/admin/descriptive-reports/<?php echo $report['id']; ?>/reopen" class="d-inline" onsubmit="return confirm('Reabrir este parecer para edicao?')">
                <button type="submit" class="btn btn-outline-warning">
                    <i class="fas fa-lock-open me-1"></i> Reabrir
                </button>
            </form>
        <?php endif; ?>

        <?php if ($isCoordenadorOrAdmin && !$isRevision): ?>
            <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#revisionModal">
                <i class="fas fa-undo me-1"></i> Solicitar Revisao
            </button>
        <?php endif; ?>
    </div>
</div>

<?php if ($isRevision && !empty($report['revision_notes'])): ?>
    <div class="alert alert-warning mb-4">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>Revisao Solicitada:</strong> <?php echo nl2br(htmlspecialchars($report['revision_notes'])); ?>
    </div>
<?php endif; ?>

<?php if ($isFinalized && !empty($report['finalized_by_name'])): ?>
    <div class="alert alert-success mb-4 small">
        <i class="fas fa-check-circle me-2"></i>
        Finalizado por <strong><?php echo htmlspecialchars($report['finalized_by_name']); ?></strong>
        em <?php echo date('d/m/Y H:i', strtotime($report['finalized_at'])); ?>
    </div>
<?php endif; ?>

<!-- Preview do Parecer -->
<div class="row justify-content-center">
    <div class="col-lg-10">

        <!-- CAPA -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-image me-2"></i> Capa
            </div>
            <div class="card-body text-center py-5">
                <?php if (!empty($report['cover_photo_url'])): ?>
                    <img src="<?php echo htmlspecialchars($report['cover_photo_url']); ?>" class="img-fluid rounded mb-3" style="max-height:300px;" alt="Foto de Capa">
                <?php else: ?>
                    <div class="text-muted mb-3">
                        <i class="fas fa-camera fa-3x mb-2"></i>
                        <p>Nenhuma foto de capa definida</p>
                    </div>
                <?php endif; ?>
                <h3 class="fw-bold text-primary">Parecer Descritivo</h3>
                <h5 class="text-muted"><?php echo htmlspecialchars($report['student_name']); ?></h5>
                <p class="text-muted">
                    <?php echo $report['semester']; ?>o Semestre - <?php echo $report['year']; ?>
                    <?php if (!empty($report['classroom_name'])): ?>
                        <br><?php echo htmlspecialchars($report['classroom_name']); ?>
                    <?php endif; ?>
                </p>
            </div>
        </div>

        <!-- PAGINA 1: Texto Institucional -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-file-alt me-2"></i> Pagina 1 - Texto Institucional
            </div>
            <div class="card-body p-4">
                <div class="bg-light rounded p-4" style="white-space: pre-wrap; line-height: 1.8; font-size: 1rem;">
<?php echo htmlspecialchars($report['intro_text'] ?? 'Texto institucional nao definido.'); ?>
                </div>
            </div>
        </div>

        <!-- PAGINA 2: Texto da Crianca -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-child me-2"></i> Pagina 2 - Texto da Crianca
            </div>
            <div class="card-body p-4">
                <?php
                    $displayText = !empty($report['student_text_edited']) ? $report['student_text_edited'] : $report['student_text'];
                ?>
                <?php if (!empty($displayText)): ?>
                    <div class="bg-light rounded p-4" style="white-space: pre-wrap; line-height: 1.8; font-size: 1rem;">
<?php echo htmlspecialchars($displayText); ?>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted py-4">
                        <i class="fas fa-pen fa-2x mb-2"></i>
                        <p>Nenhum texto compilado. Edite o parecer para adicionar.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- PAGINAS 3-7: Fotos dos Eixos -->
        <?php
            $pageNum = 3;
            foreach ($axisLabels as $axisKey => $axisLabel):
                $photos = $axisPhotos[$axisKey] ?? [];
        ?>
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-camera me-2"></i> Pagina <?php echo $pageNum; ?> - <?php echo $axisLabel; ?>
            </div>
            <div class="card-body p-4">
                <?php if (!empty($photos)): ?>
                    <div class="row g-3">
                        <?php foreach ($photos as $photo): ?>
                            <div class="col-md-4">
                                <div class="text-center">
                                    <img src="<?php echo htmlspecialchars($photo['url']); ?>" class="img-fluid rounded mb-2" style="max-height:200px;object-fit:cover;" alt="Foto do eixo">
                                    <?php if (!empty($photo['caption'])): ?>
                                        <p class="small text-muted fst-italic"><?php echo htmlspecialchars($photo['caption']); ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="text-center text-muted py-3">
                        <i class="fas fa-images fa-2x mb-2"></i>
                        <p class="mb-0">Nenhuma foto adicionada para este eixo</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php
                $pageNum++;
            endforeach;
        ?>

    </div>
</div>

<!-- Modal de Revisao -->
<?php if ($isCoordenadorOrAdmin): ?>
<div class="modal fade" id="revisionModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/admin/descriptive-reports/<?php echo $report['id']; ?>/request-revision">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-undo me-2"></i>Solicitar Revisao</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="revision_notes" class="form-label fw-bold">Motivo da Revisao <span class="text-danger">*</span></label>
                        <textarea name="revision_notes" id="revision_notes" class="form-control" rows="4" required placeholder="Descreva o que precisa ser revisado..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-paper-plane me-1"></i> Enviar Solicitacao
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>
