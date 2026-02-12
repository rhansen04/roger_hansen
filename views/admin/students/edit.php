<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/admin/students" class="text-decoration-none">Alunos</a></li>
        <li class="breadcrumb-item"><a href="/admin/students/<?php echo $student['id']; ?>" class="text-decoration-none">Detalhes</a></li>
        <li class="breadcrumb-item active" aria-current="page">Editar</li>
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

<div class="mb-4">
    <a href="/admin/students/<?php echo $student['id']; ?>" class="text-decoration-none text-muted">
        <i class="fas fa-arrow-left me-2"></i> Voltar para detalhes
    </a>
    <h2 class="text-primary fw-bold mt-3">EDITAR ALUNO</h2>
    <p class="text-muted mb-0">Atualize as informações do aluno: <strong><?php echo htmlspecialchars($student['name']); ?></strong></p>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="/admin/students/<?php echo $student['id']; ?>/update" method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nome Completo do Aluno <span class="text-danger">*</span></label>
                        <input type="text"
                               name="name"
                               class="form-control"
                               placeholder="Digite o nome do aluno"
                               value="<?php echo htmlspecialchars($student['name']); ?>"
                               required>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Data de Nascimento <span class="text-danger">*</span></label>
                            <input type="date"
                                   name="birth_date"
                                   class="form-control"
                                   value="<?php echo $student['birth_date']; ?>"
                                   required>
                            <?php
                            $birthDate = new DateTime($student['birth_date']);
                            $today = new DateTime();
                            $age = $today->diff($birthDate)->y;
                            ?>
                            <small class="text-muted">Idade atual: <?php echo $age; ?> anos</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Escola / Unidade <span class="text-danger">*</span></label>
                            <select name="school_id" class="form-select" required>
                                <option value="">Selecione uma escola</option>
                                <?php foreach ($schools as $school): ?>
                                    <option value="<?php echo $school['id']; ?>"
                                            <?php echo ($school['id'] == $student['school_id']) ? 'selected' : ''; ?>>
                                        <?php echo htmlspecialchars($school['name']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="alert alert-info d-flex align-items-start">
                            <i class="fas fa-info-circle mt-1 me-2"></i>
                            <div>
                                <strong>Dica:</strong> Deixe o campo de foto em branco para manter a foto atual.
                                Envie uma nova foto apenas se desejar substituir a existente.
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 border-start">
                    <div class="mb-3 text-center">
                        <label class="form-label fw-bold d-block text-start ps-3">Foto do Aluno</label>

                        <!-- Foto Atual -->
                        <div class="mt-2 mb-3">
                            <?php if ($student['photo_url']): ?>
                                <img src="<?php echo htmlspecialchars($student['photo_url']); ?>"
                                     alt="<?php echo htmlspecialchars($student['name']); ?>"
                                     class="img-fluid rounded shadow-sm mb-2"
                                     style="max-height: 200px; object-fit: cover;"
                                     id="currentPhoto">
                                <p class="small text-muted mb-0">Foto atual</p>
                            <?php else: ?>
                                <i class="fas fa-user-circle fa-8x text-light mb-3"></i>
                                <p class="small text-muted mb-0">Sem foto cadastrada</p>
                            <?php endif; ?>
                        </div>

                        <!-- Upload Nova Foto -->
                        <div class="mt-3">
                            <input type="file"
                                   name="photo"
                                   class="form-control form-control-sm"
                                   accept="image/jpeg,image/jpg,image/png"
                                   id="photoInput"
                                   onchange="previewPhoto(event)">
                            <p class="small text-muted mt-2 mb-0">
                                <i class="fas fa-upload me-1"></i>
                                Formatos: JPG, PNG. Máx 2MB.
                            </p>
                        </div>

                        <!-- Preview da Nova Foto -->
                        <div id="photoPreview" class="mt-3" style="display: none;">
                            <p class="small fw-bold text-success mb-2">
                                <i class="fas fa-check me-1"></i> Nova foto selecionada
                            </p>
                            <img id="previewImage" src="" alt="Preview" class="img-fluid rounded shadow-sm" style="max-height: 150px;">
                        </div>
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <div class="d-flex justify-content-between align-items-center">
                <a href="/admin/students/<?php echo $student['id']; ?>" class="btn btn-light">
                    <i class="fas fa-times me-2"></i> Cancelar
                </a>
                <button type="submit" class="btn btn-hansen px-5">
                    <i class="fas fa-save me-2"></i> SALVAR ALTERAÇÕES
                </button>
            </div>
        </form>
    </div>
</div>

<script>
function previewPhoto(event) {
    const file = event.target.files[0];
    if (file) {
        // Validar tamanho (2MB)
        if (file.size > 2 * 1024 * 1024) {
            alert('A foto deve ter no máximo 2MB!');
            event.target.value = '';
            return;
        }

        // Validar tipo
        if (!['image/jpeg', 'image/jpg', 'image/png'].includes(file.type)) {
            alert('Apenas imagens JPG ou PNG são aceitas!');
            event.target.value = '';
            return;
        }

        // Mostrar preview
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('previewImage').src = e.target.result;
            document.getElementById('photoPreview').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
}
</script>
