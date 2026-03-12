<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/admin/classrooms" class="text-decoration-none">Turmas</a></li>
        <li class="breadcrumb-item active"><?= htmlspecialchars($classroom['name']) ?></li>
    </ol>
</nav>

<?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show">
        <i class="fas fa-check-circle me-2"></i><?= $_SESSION['success_message'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['success_message']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error_message'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-triangle me-2"></i><?= $_SESSION['error_message'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['error_message']); ?>
<?php endif; ?>

<?php
$periodLabels = ['morning' => 'Manhã', 'afternoon' => 'Tarde', 'full' => 'Integral'];
$statusLabels = ['active' => 'Ativa', 'inactive' => 'Inativa'];
?>

<!-- Classroom Info Header -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <h2 class="text-primary fw-bold mb-1"><?= htmlspecialchars($classroom['name']) ?></h2>
                <div class="d-flex flex-wrap gap-3 text-muted">
                    <span><i class="fas fa-school me-1"></i> <?= htmlspecialchars($classroom['school_name'] ?? '-') ?></span>
                    <span><i class="fas fa-chalkboard-teacher me-1"></i> <?= htmlspecialchars($classroom['teacher_name'] ?? '-') ?></span>
                    <span><i class="fas fa-child me-1"></i> <?= $classroom['age_group'] ?> anos</span>
                    <span><i class="fas fa-clock me-1"></i> <?= $periodLabels[$classroom['period']] ?? $classroom['period'] ?></span>
                    <span><i class="fas fa-calendar me-1"></i> <?= $classroom['school_year'] ?></span>
                    <span>
                        <?php if ($classroom['status'] === 'active'): ?>
                            <span class="badge bg-success">Ativa</span>
                        <?php else: ?>
                            <span class="badge bg-secondary">Inativa</span>
                        <?php endif; ?>
                    </span>
                </div>
            </div>
            <div class="d-flex gap-2">
                <a href="/admin/classrooms/<?= $classroom['id'] ?>/edit" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-edit me-1"></i> Editar
                </a>
                <a href="/admin/classrooms" class="btn btn-outline-secondary btn-sm">
                    <i class="fas fa-arrow-left me-1"></i> Voltar
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Students Section -->
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="fw-bold mb-0">
        <i class="fas fa-users me-2 text-primary"></i>Alunos
        <span class="badge bg-primary rounded-pill ms-2"><?= count($students) ?></span>
    </h4>
    <?php if (!empty($availableStudents)): ?>
        <button type="button" class="btn btn-hansen text-white btn-sm" data-bs-toggle="modal" data-bs-target="#addStudentModal">
            <i class="fas fa-plus me-1"></i> Adicionar Aluno
        </button>
    <?php endif; ?>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0 align-middle">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4 py-3" style="width: 60px;">Foto</th>
                        <th class="py-3">Nome</th>
                        <th class="py-3">Data de Nascimento</th>
                        <th class="py-3">Idade</th>
                        <th class="py-3 text-center">Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($students)): ?>
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i class="fas fa-user-friends fa-3x mb-3"></i><br>
                                Nenhum aluno vinculado a esta turma.
                                <?php if (!empty($availableStudents)): ?>
                                    <br><small>Clique em "Adicionar Aluno" para vincular.</small>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($students as $student): ?>
                        <tr>
                            <td class="ps-4">
                                <?php if (!empty($student['photo_url'])): ?>
                                    <img src="<?= htmlspecialchars($student['photo_url']) ?>"
                                         alt="<?= htmlspecialchars($student['name']) ?>"
                                         class="rounded-circle"
                                         style="width: 40px; height: 40px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                         style="width: 40px; height: 40px;">
                                        <i class="fas fa-user text-muted"></i>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="fw-bold"><?= htmlspecialchars($student['name']) ?></td>
                            <td>
                                <?php if (!empty($student['birth_date'])): ?>
                                    <?= date('d/m/Y', strtotime($student['birth_date'])) ?>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if (!empty($student['birth_date'])): ?>
                                    <span class="badge bg-info">
                                        <?= $student['age_years'] ?> ano<?= $student['age_years'] != 1 ? 's' : '' ?>
                                        <?php if ($student['age_months'] > 0): ?>
                                            e <?= $student['age_months'] ?> m<?= $student['age_months'] != 1 ? 'eses' : 'ês' ?>
                                        <?php endif; ?>
                                    </span>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="btn-group">
                                    <a href="/admin/students/<?= $student['id'] ?>" class="btn btn-sm btn-outline-primary" title="Ver aluno">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <button onclick="removeStudent(<?= $student['id'] ?>, '<?= htmlspecialchars(addslashes($student['name'])) ?>')"
                                            class="btn btn-sm btn-outline-danger" title="Remover da turma">
                                        <i class="fas fa-user-minus"></i>
                                    </button>
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

<!-- Modal Adicionar Aluno -->
<div class="modal fade" id="addStudentModal" tabindex="-1" aria-labelledby="addStudentModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="/admin/classrooms/<?= $classroom['id'] ?>/add-student">
                <div class="modal-header">
                    <h5 class="modal-title" id="addStudentModalLabel">
                        <i class="fas fa-user-plus me-2"></i>Adicionar Aluno
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="student_id" class="form-label">Selecione o aluno</label>
                        <select name="student_id" id="student_id" class="form-select" required>
                            <option value="">-- Selecione --</option>
                            <?php foreach ($availableStudents as $as): ?>
                                <option value="<?= $as['id'] ?>">
                                    <?= htmlspecialchars($as['name']) ?>
                                    <?php if (!empty($as['school_name'])): ?>
                                        (<?= htmlspecialchars($as['school_name']) ?>)
                                    <?php endif; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-hansen text-white">
                        <i class="fas fa-plus me-1"></i> Adicionar
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Form oculto para remover aluno -->
<form id="removeStudentForm" method="POST" action="/admin/classrooms/<?= $classroom['id'] ?>/remove-student" style="display: none;">
    <input type="hidden" name="student_id" id="removeStudentId">
</form>

<script>
function removeStudent(studentId, studentName) {
    if (confirm(`Remover "${studentName}" desta turma?\n\nO aluno não será excluído, apenas desvinculado da turma.`)) {
        document.getElementById('removeStudentId').value = studentId;
        document.getElementById('removeStudentForm').submit();
    }
}
</script>
