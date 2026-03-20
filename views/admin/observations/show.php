<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/admin/observations" class="text-decoration-none">Observacoes</a></li>
        <li class="breadcrumb-item active" aria-current="page">Detalhes</li>
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
$status = $observation['status'] ?? 'in_progress';
$isFinalized = ($status === 'finalized');
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="/admin/observations" class="text-decoration-none text-muted mb-2 d-block">
            <i class="fas fa-arrow-left me-2"></i> Voltar para listagem
        </a>
        <h2 class="fw-bold mb-0" style="color: var(--primary-color, #007e66);">
            <i class="fas fa-clipboard-list me-2"></i>OBSERVACAO PEDAGOGICA
        </h2>
    </div>
    <div class="d-flex align-items-center gap-2">
        <?php if ($isFinalized): ?>
            <span class="badge bg-success fs-6 px-3 py-2">
                <i class="fas fa-check-circle me-1"></i> Finalizado
            </span>
        <?php else: ?>
            <span class="badge bg-warning text-dark fs-6 px-3 py-2">
                <i class="fas fa-edit me-1"></i> Em andamento
            </span>
        <?php endif; ?>

        <?php if (!$isFinalized && $userRole !== 'coordenador'): ?>
            <a href="/admin/observations/<?php echo $observation['id']; ?>/edit" class="btn btn-hansen text-white">
                <i class="fas fa-edit me-2"></i> Editar
            </a>
        <?php endif; ?>

        <?php if ($isFinalized && in_array($userRole, ['coordenador', 'admin'])): ?>
            <button type="button" class="btn btn-outline-warning" data-bs-toggle="modal" data-bs-target="#reopenModal">
                <i class="fas fa-lock-open me-2"></i> Reabrir
            </button>
        <?php endif; ?>

        <?php if ($isFinalized): ?>
            <a href="/admin/descriptive-reports?student_id=<?php echo $observation['student_id']; ?>&observation_id=<?php echo $observation['id']; ?>"
               class="btn btn-outline-primary">
                <i class="fas fa-file-alt me-2"></i> Gerar Parecer Descritivo
            </a>
        <?php endif; ?>
    </div>
</div>

<!-- Card de Informacoes Basicas -->
<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0 fw-bold" style="color: var(--primary-color, #007e66);">
                    <i class="fas fa-info-circle me-2"></i>Informacoes
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <small class="text-muted d-block mb-1">Aluno</small>
                        <a href="/admin/students/<?php echo $observation['student_id']; ?>" class="text-decoration-none fw-bold fs-5" style="color: var(--primary-color, #007e66);">
                            <i class="fas fa-user-graduate me-1"></i>
                            <?php echo htmlspecialchars($observation['student_name']); ?>
                        </a>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block mb-1">Periodo</small>
                        <p class="fw-bold mb-0">
                            <?php if ($observation['semester'] && $observation['year']): ?>
                                <?php echo $observation['semester']; ?>o Semestre / <?php echo $observation['year']; ?>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </p>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block mb-1">Professor</small>
                        <p class="fw-bold mb-0">
                            <i class="fas fa-user-tie me-1"></i>
                            <?php echo htmlspecialchars($observation['teacher_name']); ?>
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <small class="text-muted d-block mb-1">Criado em</small>
                        <p class="small mb-0">
                            <i class="fas fa-calendar me-1"></i>
                            <?php echo date('d/m/Y H:i', strtotime($observation['created_at'])); ?>
                        </p>
                    </div>
                    <div class="col-md-4">
                        <small class="text-muted d-block mb-1">Atualizado em</small>
                        <p class="small mb-0">
                            <i class="fas fa-sync-alt me-1"></i>
                            <?php echo date('d/m/Y H:i', strtotime($observation['updated_at'])); ?>
                        </p>
                    </div>
                    <?php if ($isFinalized && !empty($observation['finalized_at'])): ?>
                        <div class="col-md-4">
                            <small class="text-muted d-block mb-1">Finalizado em</small>
                            <p class="small mb-0">
                                <i class="fas fa-check me-1"></i>
                                <?php echo date('d/m/Y H:i', strtotime($observation['finalized_at'])); ?>
                                <?php if (!empty($observation['finalized_by_name'])): ?>
                                    por <?php echo htmlspecialchars($observation['finalized_by_name']); ?>
                                <?php endif; ?>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Eixos Pedagogicos com Tabs -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0 fw-bold" style="color: var(--primary-color, #007e66);">
                    <i class="fas fa-layer-group me-2"></i>Eixos Pedagogicos
                </h5>
            </div>
            <div class="card-body p-4">
                <ul class="nav nav-tabs" id="axesTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="tab-general" data-bs-toggle="tab" data-bs-target="#panel-general" type="button" role="tab">
                            <i class="fas fa-file-alt me-1"></i> Observacao Geral
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-movement" data-bs-toggle="tab" data-bs-target="#panel-movement" type="button" role="tab">
                            <i class="fas fa-running me-1"></i> Movimento
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-manual" data-bs-toggle="tab" data-bs-target="#panel-manual" type="button" role="tab">
                            <i class="fas fa-hands me-1"></i> Manual
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-music" data-bs-toggle="tab" data-bs-target="#panel-music" type="button" role="tab">
                            <i class="fas fa-music me-1"></i> Musical
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-stories" data-bs-toggle="tab" data-bs-target="#panel-stories" type="button" role="tab">
                            <i class="fas fa-book-open me-1"></i> Contos
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-pca" data-bs-toggle="tab" data-bs-target="#panel-pca" type="button" role="tab">
                            <i class="fas fa-comments me-1"></i> Comunicacao Ativa
                        </button>
                    </li>
                </ul>

                <div class="tab-content pt-4" id="axesTabContent">
                    <?php
                    $axes = [
                        'panel-general' => ['label' => 'Observacao Geral', 'field' => 'observation_general', 'active' => true],
                        'panel-movement' => ['label' => 'Eixo Atividade de Movimento', 'field' => 'axis_movement', 'active' => false],
                        'panel-manual' => ['label' => 'Eixo Atividade Manual', 'field' => 'axis_manual', 'active' => false],
                        'panel-music' => ['label' => 'Eixo Atividade Musical', 'field' => 'axis_music', 'active' => false],
                        'panel-stories' => ['label' => 'Eixo Atividade de Contos', 'field' => 'axis_stories', 'active' => false],
                        'panel-pca' => ['label' => 'Eixo Programa Comunicacao Ativa', 'field' => 'axis_pca', 'active' => false],
                    ];
                    foreach ($axes as $panelId => $axis):
                        $value = $observation[$axis['field']] ?? '';
                        $activeClass = $axis['active'] ? 'show active' : '';
                    ?>
                        <div class="tab-pane fade <?php echo $activeClass; ?>" id="<?php echo $panelId; ?>" role="tabpanel">
                            <?php if (!empty(trim($value))): ?>
                                <div class="bg-light p-4 rounded">
                                    <p class="mb-0" style="white-space: pre-wrap; line-height: 1.8;"><?php echo htmlspecialchars($value); ?></p>
                                </div>
                            <?php else: ?>
                                <div class="bg-light p-4 rounded text-muted text-center">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Nenhum registro para este eixo.
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Card de Acoes Rapidas -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <h5 class="text-secondary fw-bold mb-3">
                    <i class="fas fa-bolt me-2"></i> Acoes Rapidas
                </h5>
                <div class="d-grid gap-2">
                    <a href="/admin/students/<?php echo $observation['student_id']; ?>" class="btn btn-outline-primary">
                        <i class="fas fa-user-graduate me-2"></i> Ver Perfil do Aluno
                    </a>
                    <a href="/admin/observations?student_id=<?php echo $observation['student_id']; ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-clipboard-list me-2"></i> Observacoes do Aluno
                    </a>
                    <?php if ($userRole !== 'coordenador'): ?>
                        <a href="/admin/observations/create?student_id=<?php echo $observation['student_id']; ?>" class="btn btn-outline-success">
                            <i class="fas fa-plus me-2"></i> Nova Observacao
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Card de Status -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <h5 class="text-secondary fw-bold mb-3">
                    <i class="fas fa-info-circle me-2"></i> Status do Registro
                </h5>
                <div class="mb-3">
                    <small class="text-muted d-block mb-1">ID</small>
                    <p class="mb-0 fw-bold">#<?php echo $observation['id']; ?></p>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block mb-1">Status</small>
                    <?php if ($isFinalized): ?>
                        <span class="badge bg-success">
                            <i class="fas fa-check-circle me-1"></i> Finalizado
                        </span>
                    <?php else: ?>
                        <span class="badge bg-warning text-dark">
                            <i class="fas fa-edit me-1"></i> Em andamento
                        </span>
                    <?php endif; ?>
                </div>
                <!-- Preenchimento dos eixos -->
                <div class="mb-0">
                    <small class="text-muted d-block mb-2">Preenchimento</small>
                    <?php
                    $axisLabels = [
                        'observation_general' => 'Geral',
                        'axis_movement' => 'Movimento',
                        'axis_manual' => 'Manual',
                        'axis_music' => 'Musical',
                        'axis_stories' => 'Contos',
                        'axis_pca' => 'Com. Ativa',
                    ];
                    foreach ($axisLabels as $field => $label):
                        $filled = !empty(trim($observation[$field] ?? ''));
                    ?>
                        <div class="d-flex align-items-center mb-1">
                            <?php if ($filled): ?>
                                <i class="fas fa-check-circle text-success me-2"></i>
                            <?php else: ?>
                                <i class="far fa-circle text-muted me-2"></i>
                            <?php endif; ?>
                            <small><?php echo $label; ?></small>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <?php if (!$isFinalized && $userRole !== 'coordenador'): ?>
            <!-- Card de Exclusao -->
            <div class="card border-0 shadow-sm border-danger">
                <div class="card-body p-4">
                    <h5 class="text-danger fw-bold mb-3">
                        <i class="fas fa-exclamation-triangle me-2"></i> Zona de Perigo
                    </h5>
                    <p class="small text-muted mb-3">Esta acao nao pode ser desfeita.</p>
                    <button onclick="confirmDelete(<?php echo $observation['id']; ?>)" class="btn btn-outline-danger btn-sm w-100">
                        <i class="fas fa-trash me-2"></i> Excluir Observacao
                    </button>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php
// Coordinator feedback partial
$contentType = 'observation';
$contentId   = $observation['id'];
$comments    = $comments ?? [];
include __DIR__ . '/_coordinator_feedback.php';
?>

<?php if ($isFinalized && in_array($userRole, ['coordenador', 'admin'])): ?>
<!-- Modal de Reabertura -->
<div class="modal fade" id="reopenModal" tabindex="-1" aria-labelledby="reopenModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reopenModalLabel">
                    <i class="fas fa-lock-open me-2 text-warning"></i>Reabrir Observacao
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning mb-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Atencao:</strong> Ao reabrir, o professor podera editar novamente os campos da observacao.
                </div>
                <p>Deseja realmente reabrir esta observacao para edicao?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                <form action="/admin/observations/<?php echo $observation['id']; ?>/reopen" method="POST" style="display: inline;">
                    <button type="submit" class="btn btn-warning">
                        <i class="fas fa-lock-open me-2"></i> Sim, Reabrir
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Form oculto para delete -->
<form id="deleteForm" method="POST" style="display: none;"></form>

<script>
function confirmDelete(id) {
    if (confirm('Tem certeza que deseja excluir esta observacao?\n\nAtencao: Esta acao nao pode ser desfeita.')) {
        var form = document.getElementById('deleteForm');
        form.action = '/admin/observations/' + id + '/delete';
        form.submit();
    }
}
</script>

<style>
@media print {
    .btn, .breadcrumb, nav, button, .card-body .d-grid {
        display: none !important;
    }
}
</style>
