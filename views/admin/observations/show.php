<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/admin/observations" class="text-decoration-none">Observações</a></li>
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

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="/admin/observations?student_id=<?php echo $observation['student_id']; ?>" class="text-decoration-none text-muted mb-2 d-block">
            <i class="fas fa-arrow-left me-2"></i> Voltar para observações
        </a>
        <h2 class="text-primary fw-bold mb-0">DETALHES DA OBSERVAÇÃO #<?php echo $observation['id']; ?></h2>
    </div>
    <div class="btn-group">
        <a href="/admin/observations/<?php echo $observation['id']; ?>/edit" class="btn btn-hansen text-white">
            <i class="fas fa-edit me-2"></i> Editar
        </a>
        <button onclick="confirmDelete(<?php echo $observation['id']; ?>)" class="btn btn-danger">
            <i class="fas fa-trash me-2"></i> Excluir
        </button>
    </div>
</div>

<!-- Card Principal -->
<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <?php
                        $badgeColors = [
                            'Comportamento' => 'primary',
                            'Aprendizado' => 'success',
                            'Saúde' => 'danger',
                            'Comunicação com Pais' => 'warning',
                            'Geral' => 'secondary'
                        ];
                        $color = $badgeColors[$observation['category']] ?? 'secondary';
                        ?>
                        <span class="badge bg-<?php echo $color; ?> fs-6 mb-3">
                            <i class="fas fa-tag me-1"></i>
                            <?php echo htmlspecialchars($observation['category']); ?>
                        </span>
                        <h5 class="text-primary fw-bold mb-2">
                            <i class="fas fa-user-graduate me-2"></i> Aluno
                        </h5>
                        <h4 class="mb-0">
                            <a href="/admin/students/<?php echo $observation['student_id']; ?>" class="text-decoration-none text-dark">
                                <?php echo htmlspecialchars($observation['student_name']); ?>
                            </a>
                        </h4>
                    </div>
                    <div class="text-end">
                        <label class="text-muted small fw-bold d-block mb-1">Data da Observação</label>
                        <p class="fs-5 fw-bold text-primary mb-0">
                            <i class="fas fa-calendar-alt me-2"></i>
                            <?php echo date('d/m/Y', strtotime($observation['observation_date'])); ?>
                        </p>
                    </div>
                </div>

                <hr class="my-4">

                <div class="mb-4">
                    <h5 class="text-secondary fw-bold mb-3">
                        <i class="fas fa-file-alt me-2"></i> <?php echo htmlspecialchars($observation['title']); ?>
                    </h5>
                    <?php if (!empty($observation['description'])): ?>
                        <div class="bg-light p-4 rounded">
                            <p class="mb-0" style="white-space: pre-wrap; line-height: 1.8;"><?php echo htmlspecialchars($observation['description']); ?></p>
                        </div>
                    <?php else: ?>
                        <div class="bg-light p-4 rounded text-muted fst-italic">
                            <p class="mb-0">Nenhuma descrição detalhada fornecida.</p>
                        </div>
                    <?php endif; ?>
                </div>

                <hr class="my-4">

                <div class="row">
                    <div class="col-md-6">
                        <label class="text-muted small fw-bold d-block mb-1">Registrado por</label>
                        <p class="mb-0">
                            <i class="fas fa-user-tie text-primary me-2"></i>
                            <span class="fw-bold"><?php echo htmlspecialchars($observation['teacher_name']); ?></span>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small fw-bold d-block mb-1">Data do Registro</label>
                        <p class="small mb-0">
                            <i class="fas fa-clock me-1"></i>
                            <?php echo date('d/m/Y \à\s H:i', strtotime($observation['created_at'])); ?>
                        </p>
                    </div>
                </div>

                <?php if ($observation['updated_at'] != $observation['created_at']): ?>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <label class="text-muted small fw-bold d-block mb-1">Última Atualização</label>
                            <p class="small mb-0">
                                <i class="fas fa-sync-alt me-1"></i>
                                <?php echo date('d/m/Y \à\s H:i', strtotime($observation['updated_at'])); ?>
                            </p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Card de Ações Rápidas -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <h5 class="text-secondary fw-bold mb-3">
                    <i class="fas fa-bolt me-2"></i> Ações Rápidas
                </h5>
                <div class="d-grid gap-2">
                    <a href="/admin/students/<?php echo $observation['student_id']; ?>" class="btn btn-outline-primary">
                        <i class="fas fa-user-graduate me-2"></i> Ver Perfil do Aluno
                    </a>
                    <a href="/admin/observations?student_id=<?php echo $observation['student_id']; ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-clipboard-list me-2"></i> Todas as Observações
                    </a>
                    <a href="/admin/observations/create?student_id=<?php echo $observation['student_id']; ?>" class="btn btn-outline-success">
                        <i class="fas fa-plus me-2"></i> Nova Observação
                    </a>
                </div>
            </div>
        </div>

        <!-- Card de Informações -->
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h5 class="text-secondary fw-bold mb-3">
                    <i class="fas fa-info-circle me-2"></i> Informações
                </h5>
                <div class="mb-3">
                    <small class="text-muted d-block mb-1">ID da Observação</small>
                    <p class="mb-0">#<?php echo $observation['id']; ?></p>
                </div>
                <div class="mb-3">
                    <small class="text-muted d-block mb-1">Status</small>
                    <span class="badge bg-success">
                        <i class="fas fa-check-circle me-1"></i> Registrado
                    </span>
                </div>
                <div class="mb-0">
                    <small class="text-muted d-block mb-1">Categoria</small>
                    <span class="badge bg-<?php echo $color; ?>">
                        <?php echo htmlspecialchars($observation['type']); ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Form oculto para delete -->
<form id="deleteForm" method="POST" style="display: none;">
    <input type="hidden" name="_method" value="DELETE">
</form>

<script>
function confirmDelete(id) {
    if (confirm('Tem certeza que deseja excluir esta observação?\n\nAtenção: Esta ação não pode ser desfeita.')) {
        const form = document.getElementById('deleteForm');
        form.action = `/admin/observations/${id}/delete`;
        form.submit();
    }
}

// Função para imprimir observação
function printObservation() {
    window.print();
}
</script>

<style>
@media print {
    .btn, .breadcrumb, nav, button {
        display: none !important;
    }
}
</style>
