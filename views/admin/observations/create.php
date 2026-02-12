<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/admin/observations" class="text-decoration-none">Observações</a></li>
        <li class="breadcrumb-item active" aria-current="page">Nova Observação</li>
    </ol>
</nav>

<div class="mb-4">
    <a href="/admin/observations" class="text-decoration-none text-muted"><i class="fas fa-arrow-left me-2"></i> Voltar para listagem</a>
    <h2 class="text-primary fw-bold mt-3">NOVA OBSERVAÇÃO PEDAGÓGICA</h2>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-4">
        <form action="/admin/observations" method="POST" id="observationForm">
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Aluno <span class="text-danger">*</span></label>
                        <select name="student_id" id="student_id" class="form-select" required>
                            <option value="">Selecione um aluno</option>
                            <?php foreach ($students as $student): ?>
                                <option value="<?php echo $student['id']; ?>"
                                    <?php echo ($selectedStudentId == $student['id']) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($student['name']); ?>
                                    <?php if ($student['school_name']): ?>
                                        - <?php echo htmlspecialchars($student['school_name']); ?>
                                    <?php endif; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <small class="form-text text-muted">Selecione o aluno para registrar a observação</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Data da Observação <span class="text-danger">*</span></label>
                            <input type="date" name="observation_date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                            <small class="form-text text-muted">Quando ocorreu o fato observado</small>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Categoria <span class="text-danger">*</span></label>
                            <select name="category" class="form-select" required>
                                <option value="">Selecione uma categoria</option>
                                <option value="Comportamento">Comportamento</option>
                                <option value="Aprendizado">Aprendizado</option>
                                <option value="Saúde">Saúde</option>
                                <option value="Comunicação com Pais">Comunicação com Pais</option>
                                <option value="Geral" selected>Geral</option>
                            </select>
                            <small class="form-text text-muted">Tipo de observação</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Título da Observação <span class="text-danger">*</span></label>
                        <input type="text" name="title" class="form-control"
                            placeholder="Ex: Adaptação excelente, Desenvolvimento da linguagem, etc."
                            required maxlength="200">
                        <small class="form-text text-muted">Título resumido da observação</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Descrição Detalhada</label>
                        <textarea name="description" id="description" class="form-control" rows="8"
                            placeholder="Descreva detalhadamente a observação...&#10;&#10;Exemplo:&#10;- O que foi observado?&#10;- Quando aconteceu?&#10;- Qual foi o contexto?&#10;- Houve alguma intervenção?"></textarea>
                        <div class="d-flex justify-content-between mt-1">
                            <small class="form-text text-muted">Seja claro e objetivo na descrição</small>
                            <small class="text-muted" id="charCount">0 caracteres</small>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 border-start">
                    <div class="mb-3">
                        <h5 class="text-secondary fw-bold mb-3">
                            <i class="fas fa-info-circle me-2"></i> Dicas para Registro
                        </h5>
                        <div class="alert alert-info">
                            <strong>Comportamento</strong>
                            <p class="small mb-2">Registre atitudes, interações sociais, respeito às regras.</p>

                            <strong>Aprendizado</strong>
                            <p class="small mb-2">Documente progressos, dificuldades, interesses demonstrados.</p>

                            <strong>Saúde</strong>
                            <p class="small mb-2">Anote sintomas, acidentes, questões médicas relevantes.</p>

                            <strong>Comunicação com Pais</strong>
                            <p class="small mb-2">Registre conversas importantes, solicitações, alinhamentos.</p>

                            <strong>Geral</strong>
                            <p class="small mb-0">Para observações diversas não enquadradas acima.</p>
                        </div>
                    </div>

                    <div class="alert alert-warning">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <strong>Importante:</strong>
                        <p class="small mb-0">As observações são confidenciais e devem ser objetivas, respeitosas e construtivas.</p>
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
                    <a href="/admin/observations" class="btn btn-light me-2">Cancelar</a>
                    <button type="submit" class="btn btn-hansen px-5">
                        <i class="fas fa-save me-2"></i> SALVAR OBSERVAÇÃO
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
</script>
