<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/admin/students" class="text-decoration-none">Alunos</a></li>
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
        <a href="/admin/students" class="text-decoration-none text-muted mb-2 d-block">
            <i class="fas fa-arrow-left me-2"></i> Voltar para listagem
        </a>
        <h2 class="text-primary fw-bold mb-0">DETALHES DO ALUNO</h2>
    </div>
    <div class="btn-group">
        <button id="btnResumoIA" class="btn btn-gradient-purple text-white">
            <i class="fas fa-magic me-2"></i> Resumo IA
        </button>
        <a href="/admin/students/<?php echo $student['id']; ?>/edit" class="btn btn-hansen text-white">
            <i class="fas fa-edit me-2"></i> Editar
        </a>
        <button onclick="confirmDelete(<?php echo $student['id']; ?>, '<?php echo htmlspecialchars($student['name']); ?>')" class="btn btn-danger">
            <i class="fas fa-trash me-2"></i> Excluir
        </button>
    </div>
</div>

<!-- Card de Informações do Aluno -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-4">
        <div class="row">
            <!-- Coluna de Dados Pessoais -->
            <div class="col-md-8">
                <h5 class="text-primary fw-bold mb-4">
                    <i class="fas fa-user me-2"></i> Dados Pessoais
                </h5>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="text-muted small fw-bold d-block mb-1">Nome Completo</label>
                        <p class="fs-5 fw-bold text-primary mb-0"><?php echo htmlspecialchars($student['name']); ?></p>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small fw-bold d-block mb-1">Data de Nascimento</label>
                        <p class="mb-0"><?php echo date('d/m/Y', strtotime($student['birth_date'])); ?></p>
                    </div>
                    <div class="col-md-3">
                        <label class="text-muted small fw-bold d-block mb-1">Idade</label>
                        <p class="mb-0">
                            <span class="badge bg-primary fs-6"><?php echo $age; ?> anos</span>
                        </p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <label class="text-muted small fw-bold d-block mb-1">Escola Vinculada</label>
                        <p class="mb-0">
                            <?php if ($student['school_name']): ?>
                                <i class="fas fa-school text-primary me-2"></i>
                                <a href="/admin/schools/<?php echo $student['school_id']; ?>" class="text-decoration-none fw-bold">
                                    <?php echo htmlspecialchars($student['school_name']); ?>
                                </a>
                            <?php else: ?>
                                <span class="text-muted">Não vinculada</span>
                            <?php endif; ?>
                        </p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <label class="text-muted small fw-bold d-block mb-1">Cadastrado em</label>
                        <p class="small mb-0">
                            <i class="fas fa-calendar-plus me-1"></i>
                            <?php echo date('d/m/Y \à\s H:i', strtotime($student['created_at'])); ?>
                        </p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small fw-bold d-block mb-1">Última Atualização</label>
                        <p class="small mb-0">
                            <i class="fas fa-sync-alt me-1"></i>
                            <?php echo date('d/m/Y \à\s H:i', strtotime($student['updated_at'])); ?>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Coluna da Foto -->
            <div class="col-md-4 border-start text-center">
                <label class="text-muted small fw-bold d-block mb-3">Foto do Aluno</label>
                <?php if ($student['photo_url']): ?>
                    <img src="<?php echo htmlspecialchars($student['photo_url']); ?>"
                         alt="<?php echo htmlspecialchars($student['name']); ?>"
                         class="img-fluid rounded shadow-sm"
                         style="max-height: 250px; object-fit: cover;">
                <?php else: ?>
                    <div class="text-center py-4">
                        <i class="fas fa-user-circle fa-8x text-light"></i>
                        <p class="text-muted mt-3 mb-0">Sem foto cadastrada</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Card de Observações -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="text-primary fw-bold mb-0">
                <i class="fas fa-clipboard-list me-2"></i> Observações do Aluno
                <span class="badge bg-primary ms-2"><?php echo count($observations); ?></span>
            </h5>
            <a href="/admin/observations?student_id=<?php echo $student['id']; ?>" class="btn btn-sm btn-outline-primary">
                <i class="fas fa-plus me-1"></i> Nova Observação
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <?php if (empty($observations)): ?>
            <div class="text-center py-5 text-muted">
                <i class="fas fa-clipboard fa-3x mb-3"></i><br>
                Nenhuma observação registrada para este aluno.
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0 align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3" style="width: 15%;">Data</th>
                            <th class="py-3" style="width: 12%;">Tipo</th>
                            <th class="py-3" style="width: 48%;">Conteúdo</th>
                            <th class="py-3" style="width: 20%;">Professor</th>
                            <th class="py-3 text-center" style="width: 5%;">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($observations as $obs): ?>
                        <tr>
                            <td class="ps-4 small"><?php echo date('d/m/Y', strtotime($obs['observation_date'])); ?></td>
                            <td>
                                <?php
                                $badgeColors = [
                                    'Comportamento' => 'primary',
                                    'Aprendizado' => 'success',
                                    'Saúde' => 'danger',
                                    'Comunicação com Pais' => 'warning',
                                    'Geral' => 'secondary'
                                ];
                                $color = $badgeColors[$obs['category']] ?? 'secondary';
                                ?>
                                <span class="badge bg-<?php echo $color; ?>">
                                    <?php echo htmlspecialchars($obs['category']); ?>
                                </span>
                            </td>
                            <td class="small">
                                <strong><?php echo htmlspecialchars($obs['title']); ?></strong>
                                <?php if (!empty($obs['description'])): ?>
                                    <br><span class="text-muted"><?php echo nl2br(htmlspecialchars(substr($obs['description'], 0, 80))); ?><?php echo strlen($obs['description']) > 80 ? '...' : ''; ?></span>
                                <?php endif; ?>
                            </td>
                            <td class="small">
                                <i class="fas fa-user-tie me-1"></i>
                                <?php echo htmlspecialchars($obs['teacher_name']); ?>
                            </td>
                            <td class="text-center">
                                <a href="/admin/observations/<?php echo $obs['id']; ?>" class="btn btn-sm btn-outline-primary" title="Ver Detalhes">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Form oculto para delete -->
<form id="deleteForm" method="POST" style="display: none;">
    <input type="hidden" name="_method" value="DELETE">
</form>

<!-- Modal Resumo IA -->
<div class="modal fade" id="modalResumoIA" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-gradient-purple text-white">
                <h5 class="modal-title">
                    <i class="fas fa-magic me-2"></i>
                    Resumo Pedagógico IA - <?= htmlspecialchars($student['name']) ?>
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="resumoLoading" class="text-center py-5">
                    <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                        <span class="visually-hidden">Gerando resumo...</span>
                    </div>
                    <p class="mt-3 text-muted">
                        <i class="fas fa-robot me-2"></i>
                        Analisando observações e gerando narrativa pedagógica...
                    </p>
                </div>

                <div id="resumoContent" style="display: none;">
                    <div class="alert alert-info mb-3">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Total de observações analisadas:</strong> <span id="totalObs">0</span>
                    </div>

                    <div id="resumoTexto" class="card-body bg-light rounded p-4" style="white-space: pre-wrap; line-height: 1.8;">
                        <!-- Texto do resumo aqui -->
                    </div>

                    <div class="mt-3 text-center">
                        <button id="btnCopiar" class="btn btn-primary">
                            <i class="fas fa-copy me-2"></i>
                            Copiar Texto
                        </button>
                    </div>
                </div>

                <div id="resumoError" style="display: none;" class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Erro:</strong> <span id="errorMessage"></span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.btn-gradient-purple {
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
    color: white;
    border: none;
    transition: transform 0.2s, box-shadow 0.2s;
}
.btn-gradient-purple:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(139, 92, 246, 0.4);
    color: white;
}

.bg-gradient-purple {
    background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
}

.modal-lg {
    max-width: 720px;
}

.modal-dialog-scrollable .modal-body {
    max-height: 85vh;
}

#resumoTexto {
    font-size: 1.05rem;
    color: #333;
}
</style>

<script>
function confirmDelete(id, name) {
    if (confirm(`Tem certeza que deseja excluir o aluno "${name}"?\n\nAtenção: Esta ação não pode ser desfeita e todas as observações relacionadas serão perdidas.`)) {
        const form = document.getElementById('deleteForm');
        form.action = `/admin/students/${id}/delete`;
        form.submit();
    }
}

// Resumo IA com Gemini
document.addEventListener('DOMContentLoaded', function() {
    const btnResumoIA = document.getElementById('btnResumoIA');
    const modalElement = document.getElementById('modalResumoIA');
    const modal = new bootstrap.Modal(modalElement);
    const studentId = <?= $student['id'] ?>;

    btnResumoIA?.addEventListener('click', async function() {
        // Abrir modal
        modal.show();

        // Mostrar loading
        document.getElementById('resumoLoading').style.display = 'block';
        document.getElementById('resumoContent').style.display = 'none';
        document.getElementById('resumoError').style.display = 'none';

        try {
            const response = await fetch(`/admin/students/${studentId}/ai-summary`);
            const data = await response.json();

            if (data.success) {
                // Esconder loading
                document.getElementById('resumoLoading').style.display = 'none';

                // Mostrar conteúdo
                document.getElementById('resumoContent').style.display = 'block';
                document.getElementById('totalObs').textContent = data.data.total_observations;
                document.getElementById('resumoTexto').textContent = data.data.summary;
            } else {
                throw new Error(data.error || 'Erro desconhecido');
            }
        } catch (error) {
            document.getElementById('resumoLoading').style.display = 'none';
            document.getElementById('resumoError').style.display = 'block';
            document.getElementById('errorMessage').textContent = error.message;
        }
    });

    // Botão copiar
    document.getElementById('btnCopiar')?.addEventListener('click', function() {
        const texto = document.getElementById('resumoTexto').textContent;
        navigator.clipboard.writeText(texto).then(() => {
            this.innerHTML = '<i class="fas fa-check me-2"></i>Copiado!';
            setTimeout(() => {
                this.innerHTML = '<i class="fas fa-copy me-2"></i>Copiar Texto';
            }, 2000);
        }).catch(() => {
            alert('Erro ao copiar texto. Use Ctrl+C manualmente.');
        });
    });
});
</script>
