<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/admin/observations" class="text-decoration-none">Observações</a></li>
        <li class="breadcrumb-item"><a href="/admin/observations/<?php echo $observation['id']; ?>" class="text-decoration-none">Detalhes</a></li>
        <li class="breadcrumb-item active" aria-current="page">Editar</li>
    </ol>
</nav>

<div class="mb-4">
    <a href="/admin/observations/<?php echo $observation['id']; ?>" class="text-decoration-none text-muted">
        <i class="fas fa-arrow-left me-2"></i> Voltar para detalhes
    </a>
    <h2 class="text-primary fw-bold mt-3">EDITAR OBSERVAÇÃO #<?php echo $observation['id']; ?></h2>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="/admin/observations/<?php echo $observation['id']; ?>/update" method="POST" id="observationForm">
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Aluno <span class="text-danger">*</span></label>
                        <select name="student_id" id="student_id" class="form-select" required>
                            <option value="">Selecione um aluno</option>
                            <?php foreach ($students as $student): ?>
                                <option value="<?php echo $student['id']; ?>"
                                    <?php echo ($observation['student_id'] == $student['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($student['name']); ?>
                                    <?php if ($student['school_name']): ?>
                                        - <?php echo htmlspecialchars($student['school_name']); ?>
                                    <?php endif; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="form-text text-muted">Selecione o aluno relacionado à observação</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Data da Observação <span class="text-danger">*</span></label>
                            <input type="date" name="observation_date" class="form-control"
                                value="<?php echo date('Y-m-d', strtotime($observation['observation_date'])); ?>" required>
                            <small class="form-text text-muted">Quando ocorreu o fato observado</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Categoria <span class="text-danger">*</span></label>
                            <select name="category" class="form-select" required>
                                <option value="">Selecione uma categoria</option>
                                <option value="Comportamento" <?php echo ($observation['category'] == 'Comportamento') ? 'selected' : ''; ?>>Comportamento</option>
                                <option value="Aprendizado" <?php echo ($observation['category'] == 'Aprendizado') ? 'selected' : ''; ?>>Aprendizado</option>
                                <option value="Saúde" <?php echo ($observation['category'] == 'Saúde') ? 'selected' : ''; ?>>Saúde</option>
                                <option value="Comunicação com Pais" <?php echo ($observation['category'] == 'Comunicação com Pais') ? 'selected' : ''; ?>>Comunicação com Pais</option>
                                <option value="Geral" <?php echo ($observation['category'] == 'Geral') ? 'selected' : ''; ?>>Geral</option>
                            </select>
                            <small class="form-text text-muted">Tipo de observação</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Título da Observação <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control"
                            value="<?php echo htmlspecialchars($observation['title']); ?>"
                            placeholder="Ex: Adaptação excelente, Desenvolvimento da linguagem, etc."
                            required maxlength="200">
                        <small class="form-text text-muted">Título resumido da observação</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Descrição Detalhada</label>
                        <textarea name="description" id="description" class="form-control" rows="8"
                            placeholder="Descreva detalhadamente a observação..."><?php echo htmlspecialchars($observation['description'] ?? ''); ?></textarea>
                        <div class="d-flex justify-content-between mt-1">
                            <small class="form-text text-muted">Seja claro e objetivo na descrição</small>
                            <small class="text-muted" id="charCount"><?php echo strlen($observation['description'] ?? ''); ?> caracteres</small>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 border-start">
                    <div class="mb-3">
                        <h5 class="text-secondary fw-bold mb-3">
                            <i class="fas fa-info-circle me-2"></i> Informações
                        </h5>
                        <div class="alert alert-light border">
                            <p class="small mb-2">
                                <strong>Criado por:</strong><br>
                                <i class="fas fa-user-tie me-1"></i>
                                <?php echo htmlspecialchars($observation['teacher_name']); ?>
                            </p>
                            <p class="small mb-2">
                                <strong>Data de criação:</strong><br>
                                <i class="fas fa-clock me-1"></i>
                                <?php echo date('d/m/Y \à\s H:i', strtotime($observation['created_at'])); ?>
                            </p>
                            <?php if ($observation['updated_at'] != $observation['created_at']): ?>
                                <p class="small mb-0">
                                    <strong>Última atualização:</strong><br>
                                    <i class="fas fa-sync-alt me-1"></i>
                                    <?php echo date('d/m/Y \à\s H:i', strtotime($observation['updated_at'])); ?>
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Atenção:</strong>
                        <p class="small mb-0">As alterações serão registradas. Certifique-se de que as informações estão corretas antes de salvar.</p>
                    </div>

                    <div class="mb-3">
                        <h5 class="text-secondary fw-bold mb-3">
                            <i class="fas fa-lightbulb me-2"></i> Dicas
                        </h5>
                        <ul class="small mb-0">
                            <li class="mb-2">Use linguagem objetiva e profissional</li>
                            <li class="mb-2">Documente fatos, não opiniões</li>
                            <li class="mb-2">Seja específico sobre data e contexto</li>
                            <li class="mb-0">Revise antes de salvar</li>
                        </ul>
                    </div>
                </div>
            </div>

            <hr class="my-4">

            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-danger">*</span>
                    <small class="text-muted">Campos obrigatórios</small>
                </div>
                <div>
                    <a href="/admin/observations/<?php echo $observation['id']; ?>" class="btn btn-light me-2">Cancelar</a>
                    <button type="submit" class="btn btn-hansen px-5">
                        <i class="fas fa-save me-2"></i> SALVAR ALTERAÇÕES
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
// Contador de caracteres
const descField = document.getElementById('description');
if (descField) {
    descField.addEventListener('input', function() {
        const count = this.value.length;
        document.getElementById('charCount').textContent = count + ' caracteres';
    });
}

// Validação do formulário
document.getElementById('observationForm').addEventListener('submit', function(e) {
    const studentId = document.getElementById('student_id').value;

    if (!studentId) {
        e.preventDefault();
        alert('Por favor, selecione um aluno.');
        document.getElementById('student_id').focus();
        return false;
    }
});

// Select2 para busca de alunos (se disponível)
if (typeof jQuery !== 'undefined' && jQuery.fn.select2) {
    jQuery('#student_id').select2({
        placeholder: 'Digite para buscar um aluno...',
        allowClear: true
    });
}

// Alerta de confirmação antes de sair com alterações não salvas
let formChanged = false;
const form = document.getElementById('observationForm');
const originalFormData = new FormData(form);

form.addEventListener('change', function() {
    formChanged = true;
});

form.addEventListener('submit', function() {
    formChanged = false;
});

window.addEventListener('beforeunload', function(e) {
    if (formChanged) {
        e.preventDefault();
        e.returnValue = '';
        return '';
    }
});
</script>
