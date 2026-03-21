<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">Observacoes Pedagogicas</li>
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
        <h2 class="fw-bold mb-0" style="color: var(--primary-color, #007e66);">
            <i class="fas fa-clipboard-list me-2"></i>OBSERVACOES PEDAGOGICAS
        </h2>
        <small class="text-muted">
            <?php if ($userRole === 'professor'): ?>
                Minhas observacoes
            <?php else: ?>
                Todas as observacoes
            <?php endif; ?>
        </small>
    </div>
    <a href="/admin/observations/create" class="btn btn-hansen text-white text-decoration-none">
        <i class="fas fa-plus me-2"></i> Nova Observacao
    </a>
</div>

<!-- Eixos Pedagogicos - Acesso Rapido -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-lg-2">
        <a href="/admin/observations/create?focus=general" class="card border-0 shadow-sm text-decoration-none h-100" style="background:linear-gradient(135deg,#6c757d,#495057);">
            <div class="card-body text-center text-white py-3 px-2">
                <i class="fas fa-file-alt fa-2x mb-2"></i>
                <div class="small fw-bold">Obs. Geral</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <a href="/admin/observations/create?focus=movement" class="card border-0 shadow-sm text-decoration-none h-100" style="background:linear-gradient(135deg,#e74c3c,#c0392b);">
            <div class="card-body text-center text-white py-3 px-2">
                <i class="fas fa-running fa-2x mb-2"></i>
                <div class="small fw-bold">Movimento</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <a href="/admin/observations/create?focus=manual" class="card border-0 shadow-sm text-decoration-none h-100" style="background:linear-gradient(135deg,#f39c12,#e67e22);">
            <div class="card-body text-center text-white py-3 px-2">
                <i class="fas fa-hands fa-2x mb-2"></i>
                <div class="small fw-bold">Manual</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <a href="/admin/observations/create?focus=music" class="card border-0 shadow-sm text-decoration-none h-100" style="background:linear-gradient(135deg,#9b59b6,#8e44ad);">
            <div class="card-body text-center text-white py-3 px-2">
                <i class="fas fa-music fa-2x mb-2"></i>
                <div class="small fw-bold">Musical</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <a href="/admin/observations/create?focus=stories" class="card border-0 shadow-sm text-decoration-none h-100" style="background:linear-gradient(135deg,#3498db,#2980b9);">
            <div class="card-body text-center text-white py-3 px-2">
                <i class="fas fa-book-open fa-2x mb-2"></i>
                <div class="small fw-bold">Contos</div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <a href="/admin/observations/create?focus=pca" class="card border-0 shadow-sm text-decoration-none h-100" style="background:linear-gradient(135deg,#1abc9c,#16a085);">
            <div class="card-body text-center text-white py-3 px-2">
                <i class="fas fa-comments fa-2x mb-2"></i>
                <div class="small fw-bold">PCA</div>
            </div>
        </a>
    </div>
</div>

<!-- Filtros -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body p-3">
        <form method="GET" action="/admin/observations" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-bold mb-1">Aluno</label>
                <select name="student_id" class="form-select form-select-sm">
                    <option value="">Todos os alunos</option>
                    <?php foreach ($students as $s): ?>
                        <option value="<?php echo $s['id']; ?>" <?php echo ($filters['student_id'] == $s['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($s['name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold mb-1">Semestre</label>
                <select name="semester" class="form-select form-select-sm">
                    <option value="">Todos</option>
                    <option value="1" <?php echo ($filters['semester'] == '1') ? 'selected' : ''; ?>>1o Semestre</option>
                    <option value="2" <?php echo ($filters['semester'] == '2') ? 'selected' : ''; ?>>2o Semestre</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold mb-1">Ano</label>
                <select name="year" class="form-select form-select-sm">
                    <option value="">Todos</option>
                    <?php foreach ($years as $y): ?>
                        <option value="<?php echo $y; ?>" <?php echo ($filters['year'] == $y) ? 'selected' : ''; ?>>
                            <?php echo $y; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold mb-1">Status</label>
                <select name="status" class="form-select form-select-sm">
                    <option value="">Todos</option>
                    <option value="in_progress" <?php echo ($filters['status'] == 'in_progress') ? 'selected' : ''; ?>>Em andamento</option>
                    <option value="finalized" <?php echo ($filters['status'] == 'finalized') ? 'selected' : ''; ?>>Finalizado</option>
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-primary btn-sm me-2">
                    <i class="fas fa-filter me-1"></i> Filtrar
                </button>
                <a href="/admin/observations" class="btn btn-light btn-sm">
                    <i class="fas fa-times me-1"></i> Limpar
                </a>
            </div>
        </form>
    </div>
</div>

<!-- Tabela de Observacoes -->
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3" style="width:50px;">No</th>
                        <th class="py-3">Aluno</th>
                        <th class="py-3">Semestre / Ano</th>
                        <th class="py-3 text-center">No Observacoes</th>
                        <th class="py-3">Status</th>
                        <th class="py-3">Atualizado em</th>
                        <th class="py-3 text-center">Acoes</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($studentRows)): ?>
                        <tr>
                            <td colspan="7" class="text-center py-5 text-muted">
                                <i class="fas fa-clipboard-list fa-3x mb-3 d-block"></i>
                                Nenhuma observacao encontrada.
                                <br><a href="/admin/observations/create" class="btn btn-sm btn-outline-primary mt-3">
                                    <i class="fas fa-plus me-1"></i> Criar primeira observacao
                                </a>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($studentRows as $idx => $row): ?>
                        <tr>
                            <td class="ps-4 text-muted small"><?php echo $idx + 1; ?></td>
                            <td>
                                <a href="/admin/students/<?php echo $row['student_id']; ?>" class="text-decoration-none fw-bold" style="color: var(--primary-color, #007e66);">
                                    <i class="fas fa-user-graduate me-1"></i>
                                    <?php echo htmlspecialchars($row['student_name']); ?>
                                </a>
                            </td>
                            <td>
                                <?php if ($row['semester'] && $row['year']): ?>
                                    <span class="badge bg-light text-dark border">
                                        <?php echo $row['semester']; ?>o Sem / <?php echo $row['year']; ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted small">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info text-white"><?php echo (int) $row['observation_count']; ?></span>
                            </td>
                            <td>
                                <div class="dropdown">
                                    <?php if ($row['aggregated_status'] === 'finalized'): ?>
                                        <button class="badge bg-success border-0 dropdown-toggle" type="button" data-bs-toggle="dropdown" data-student-id="<?php echo $row['student_id']; ?>" data-semester="<?php echo (int) $row['semester']; ?>" data-year="<?php echo (int) $row['year']; ?>">
                                            <i class="fas fa-check-circle me-1"></i> Finalizado
                                        </button>
                                    <?php else: ?>
                                        <button class="badge bg-warning text-dark border-0 dropdown-toggle" type="button" data-bs-toggle="dropdown" data-student-id="<?php echo $row['student_id']; ?>" data-semester="<?php echo (int) $row['semester']; ?>" data-year="<?php echo (int) $row['year']; ?>">
                                            <i class="fas fa-edit me-1"></i> Em andamento
                                        </button>
                                    <?php endif; ?>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item status-option <?php echo ($row['aggregated_status'] !== 'finalized') ? 'active' : ''; ?>"
                                               href="#" data-student-id="<?php echo $row['student_id']; ?>" data-semester="<?php echo (int) $row['semester']; ?>" data-year="<?php echo (int) $row['year']; ?>" data-status="in_progress">
                                                <i class="fas fa-edit me-2 text-warning"></i> Em andamento
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item status-option <?php echo ($row['aggregated_status'] === 'finalized') ? 'active' : ''; ?>"
                                               href="#" data-student-id="<?php echo $row['student_id']; ?>" data-semester="<?php echo (int) $row['semester']; ?>" data-year="<?php echo (int) $row['year']; ?>" data-status="finalized">
                                                <i class="fas fa-check-circle me-2 text-success"></i> Finalizado
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            </td>
                            <td class="small text-muted">
                                <i class="fas fa-clock me-1"></i>
                                <?php echo date('d/m/Y H:i', strtotime($row['last_updated'])); ?>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="/admin/observations/<?php echo (int) $row['latest_observation_id']; ?>" class="btn btn-sm btn-outline-primary" title="Visualizar">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="<?php echo !empty($row['latest_editable_observation_id']) ? '/admin/observations/' . (int) $row['latest_editable_observation_id'] . '/edit' : '#'; ?>" class="btn btn-sm btn-outline-secondary <?php echo empty($row['latest_editable_observation_id']) ? 'disabled' : ''; ?>" title="<?php echo empty($row['latest_editable_observation_id']) ? 'Nenhuma observacao editavel neste periodo' : 'Editar'; ?>" <?php echo empty($row['latest_editable_observation_id']) ? 'aria-disabled="true" tabindex="-1"' : ''; ?>>
                                        <i class="fas fa-edit"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Form oculto para delete -->
<form id="deleteForm" method="POST" style="display: none;">
    <input type="hidden" name="_method" value="DELETE">
</form>

<script>
function confirmDelete(id, studentName) {
    if (confirm('Tem certeza que deseja excluir a observacao do aluno "' + studentName + '"?\n\nAtencao: Esta acao nao pode ser desfeita.')) {
        const form = document.getElementById('deleteForm');
        form.action = '/admin/observations/' + id + '/delete';
        form.submit();
    }
}

document.querySelectorAll('.status-option').forEach(function(item) {
    item.addEventListener('click', function(e) {
        e.preventDefault();
        var studentId = this.dataset.studentId;
        var semester = this.dataset.semester;
        var year = this.dataset.year;
        var newStatus = this.dataset.status;
        var dropdownBtn = this.closest('.dropdown').querySelector('.dropdown-toggle');
        var csrfToken = '<?php echo htmlspecialchars($_SESSION['csrf_token'] ?? ''); ?>';

        fetch('/admin/observations/toggle-student-status', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({ student_id: studentId, semester: semester, year: year, status: newStatus, csrf_token: csrfToken })
        })
        .then(function(r) { return r.json(); })
        .then(function(data) {
            if (data.success) {
                if (data.status === 'finalized') {
                    dropdownBtn.className = 'badge bg-success border-0 dropdown-toggle';
                    dropdownBtn.innerHTML = '<i class="fas fa-check-circle me-1"></i> Finalizado';
                } else {
                    dropdownBtn.className = 'badge bg-warning text-dark border-0 dropdown-toggle';
                    dropdownBtn.innerHTML = '<i class="fas fa-edit me-1"></i> Em andamento';
                }
                var items = dropdownBtn.closest('.dropdown').querySelectorAll('.dropdown-item');
                items.forEach(function(opt) {
                    opt.classList.toggle('active', opt.dataset.status === data.status);
                });
            } else {
                alert(data.message || 'Erro ao atualizar status.');
            }
        })
        .catch(function() { alert('Erro de conexao. Tente novamente.'); });
    });
});
</script>
