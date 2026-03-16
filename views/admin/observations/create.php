<!-- Breadcrumb -->
<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/admin/observations" class="text-decoration-none">Observacoes</a></li>
        <li class="breadcrumb-item active" aria-current="page">Nova Observacao</li>
    </ol>
</nav>

<div class="mb-4">
    <a href="/admin/observations" class="text-decoration-none text-muted"><i class="fas fa-arrow-left me-2"></i> Voltar para listagem</a>
    <h2 class="fw-bold mt-3" style="color: var(--primary-color, #007e66);">
        <i class="fas fa-plus-circle me-2"></i>NOVA OBSERVACAO PEDAGOGICA
    </h2>
</div>

<form action="/admin/observations" method="POST" id="observationForm">
    <!-- Dados basicos -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom">
            <h5 class="mb-0 fw-bold" style="color: var(--primary-color, #007e66);">
                <i class="fas fa-info-circle me-2"></i>Dados Basicos
            </h5>
        </div>
        <div class="card-body p-4">
            <div class="row">
                <div class="col-md-5 mb-3">
                    <label class="form-label fw-bold">Aluno <span class="text-danger">*</span></label>
                    <?php if (!empty($selectedStudentId)):
                        // Encontrar nome do aluno selecionado
                        $selectedName = '';
                        foreach ($students as $s) {
                            if ($s['id'] == $selectedStudentId) { $selectedName = $s['name']; break; }
                        }
                    ?>
                        <input type="hidden" name="student_id" id="student_id" value="<?php echo $selectedStudentId; ?>">
                        <input type="text" class="form-control" value="<?php echo htmlspecialchars($selectedName); ?>" readonly>
                    <?php else: ?>
                        <select name="student_id" id="student_id" class="form-select" required>
                            <option value="">Selecione um aluno</option>
                            <?php foreach ($students as $student): ?>
                                <option value="<?php echo $student['id']; ?>">
                                    <?php echo htmlspecialchars($student['name']); ?>
                                    <?php if (!empty($student['school_name'])): ?>
                                        - <?php echo htmlspecialchars($student['school_name']); ?>
                                    <?php endif; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>
                </div>
                <div class="col-md-3 mb-3">
                    <label class="form-label fw-bold">Semestre <span class="text-danger">*</span></label>
                    <select name="semester" class="form-select" required>
                        <option value="1" <?php echo ($defaultSemester == 1) ? 'selected' : ''; ?>>1o Semestre</option>
                        <option value="2" <?php echo ($defaultSemester == 2) ? 'selected' : ''; ?>>2o Semestre</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label fw-bold">Ano Letivo <span class="text-danger">*</span></label>
                    <select name="year" class="form-select" required>
                        <?php foreach ($years as $y): ?>
                            <option value="<?php echo $y; ?>" <?php echo ($y == $currentYear) ? 'selected' : ''; ?>>
                                <?php echo $y; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
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
                <div class="tab-pane fade show active" id="panel-general" role="tabpanel">
                    <label class="form-label fw-bold">Observacao Geral</label>
                    <div class="mb-2">
                        <a class="text-muted small text-decoration-none" data-bs-toggle="collapse" href="#guide-general" role="button">
                            <i class="fas fa-lightbulb me-1 text-warning"></i>Perguntas orientadoras <i class="fas fa-chevron-down ms-1" style="font-size:0.7em"></i>
                        </a>
                        <div class="collapse" id="guide-general">
                            <div class="card card-body bg-light border-0 mt-1 small">
                                <ul class="mb-0 ps-3">
                                    <li>Que mudancas voce observou nesse campo desde a ultima observacao?</li>
                                    <li>Quais atividades, objetos ou brinquedos a crianca demonstra maior interesse em explorar?</li>
                                    <li>Quais sao suas facilidades e dificuldades?</li>
                                    <li>Como a crianca interage com os colegas e professores?</li>
                                    <li>Em que atividades a crianca demonstra autonomia? O que faz por conta propria?</li>
                                    <li>Como a crianca lida com situacoes desafiadoras?</li>
                                    <li>Como a crianca expressa suas emocoes?</li>
                                    <li>Quais sao as caracteristicas mais marcantes no comportamento da crianca?</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <textarea name="observation_general" class="form-control" rows="5"
                        placeholder="Registre aqui observacoes gerais sobre o desenvolvimento do aluno neste periodo..."></textarea>
                </div>
                <div class="tab-pane fade" id="panel-movement" role="tabpanel">
                    <label class="form-label fw-bold">Eixo Atividade de Movimento</label>
                    <div class="mb-2">
                        <a class="text-muted small text-decoration-none" data-bs-toggle="collapse" href="#guide-movement" role="button">
                            <i class="fas fa-lightbulb me-1 text-warning"></i>Perguntas orientadoras <i class="fas fa-chevron-down ms-1" style="font-size:0.7em"></i>
                        </a>
                        <div class="collapse" id="guide-movement">
                            <div class="card card-body bg-light border-0 mt-1 small">
                                <ul class="mb-0 ps-3">
                                    <li>Que mudancas voce observou nesse campo desde a ultima observacao?</li>
                                    <li>Prudencia: Como a crianca se movimenta? E cuidadosa?</li>
                                    <li>Persistencia: Insiste quando enfrenta dificuldades?</li>
                                    <li>Medo e Coragem: Apresenta medos excessivos ou enfrenta desafios?</li>
                                    <li>Qualidade do Movimento: Movimentos equilibrados, precisos, tensos ou relaxados?</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <textarea name="axis_movement" class="form-control" rows="5"
                        placeholder="Descreva o desenvolvimento do aluno nas atividades de movimento..."></textarea>
                </div>
                <div class="tab-pane fade" id="panel-manual" role="tabpanel">
                    <label class="form-label fw-bold">Eixo Atividade Manual</label>
                    <div class="mb-2">
                        <a class="text-muted small text-decoration-none" data-bs-toggle="collapse" href="#guide-manual" role="button">
                            <i class="fas fa-lightbulb me-1 text-warning"></i>Perguntas orientadoras <i class="fas fa-chevron-down ms-1" style="font-size:0.7em"></i>
                        </a>
                        <div class="collapse" id="guide-manual">
                            <div class="card card-body bg-light border-0 mt-1 small">
                                <ul class="mb-0 ps-3">
                                    <li>Que mudancas voce observou nesse campo desde a ultima observacao?</li>
                                    <li>Capacidade de Brincar: A crianca brinca e se diverte? Brinca sozinha?</li>
                                    <li>Concentracao: Concentra-se nos brinquedos e atividades manuais?</li>
                                    <li>Variedade: Explora diferentes tipos de brinquedos e atividades?</li>
                                    <li>Profundidade: Brinca mais tempo com um mesmo brinquedo?</li>
                                    <li>Interatividade: Como a crianca interage com os brinquedos e com outras criancas durante as atividades manuais?</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <textarea name="axis_manual" class="form-control" rows="5"
                        placeholder="Descreva o desenvolvimento do aluno nas atividades manuais..."></textarea>
                </div>
                <div class="tab-pane fade" id="panel-music" role="tabpanel">
                    <label class="form-label fw-bold">Eixo Atividade Musical</label>
                    <div class="mb-2">
                        <a class="text-muted small text-decoration-none" data-bs-toggle="collapse" href="#guide-music" role="button">
                            <i class="fas fa-lightbulb me-1 text-warning"></i>Perguntas orientadoras <i class="fas fa-chevron-down ms-1" style="font-size:0.7em"></i>
                        </a>
                        <div class="collapse" id="guide-music">
                            <div class="card card-body bg-light border-0 mt-1 small">
                                <ul class="mb-0 ps-3">
                                    <li>Que mudancas voce observou nesse campo desde a ultima observacao?</li>
                                    <li>Preferencias Musicais: Quais sao as preferencias da crianca em relacao a tipos sonoros, musicas e instrumentos?</li>
                                    <li>Sincronia: A crianca acompanha os movimentos e sons de forma sincronizada?</li>
                                    <li>Canto: A crianca canta ou cantarola sozinha?</li>
                                    <li>Concentracao: Como e a concentracao da crianca durante atividades musicais?</li>
                                    <li>Reacoes: Quais sao as reacoes da crianca a diferentes sons e musicas?</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <textarea name="axis_music" class="form-control" rows="5"
                        placeholder="Descreva o desenvolvimento do aluno nas atividades musicais..."></textarea>
                </div>
                <div class="tab-pane fade" id="panel-stories" role="tabpanel">
                    <label class="form-label fw-bold">Eixo Atividade de Contos</label>
                    <div class="mb-2">
                        <a class="text-muted small text-decoration-none" data-bs-toggle="collapse" href="#guide-stories" role="button">
                            <i class="fas fa-lightbulb me-1 text-warning"></i>Perguntas orientadoras <i class="fas fa-chevron-down ms-1" style="font-size:0.7em"></i>
                        </a>
                        <div class="collapse" id="guide-stories">
                            <div class="card card-body bg-light border-0 mt-1 small">
                                <ul class="mb-0 ps-3">
                                    <li>Que mudancas voce observou nesse campo desde a ultima observacao?</li>
                                    <li>Reacoes Corporais e Faciais: Como a crianca reage aos contos?</li>
                                    <li>Expressoes de Emocoes: Como a crianca expressa emocoes durante os contos?</li>
                                    <li>Preferencias: Quais sao as preferencias da crianca em relacao a sons, rimas, momentos dos contos e historias?</li>
                                    <li>Imitacao: A crianca imita gestos e palavras dos contos?</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <textarea name="axis_stories" class="form-control" rows="5"
                        placeholder="Descreva o desenvolvimento do aluno nas atividades de contos..."></textarea>
                </div>
                <div class="tab-pane fade" id="panel-pca" role="tabpanel">
                    <label class="form-label fw-bold">Eixo Programa Comunicacao Ativa</label>
                    <div class="mb-2">
                        <a class="text-muted small text-decoration-none" data-bs-toggle="collapse" href="#guide-pca" role="button">
                            <i class="fas fa-lightbulb me-1 text-warning"></i>Perguntas orientadoras <i class="fas fa-chevron-down ms-1" style="font-size:0.7em"></i>
                        </a>
                        <div class="collapse" id="guide-pca">
                            <div class="card card-body bg-light border-0 mt-1 small">
                                <ul class="mb-0 ps-3">
                                    <li>Que mudancas voce observou nesse campo desde a ultima observacao?</li>
                                    <li>Capacidade de compreender palavras: Entende os significados das palavras?</li>
                                    <li>Capacidade de expressar palavras: Expressa palavras com sentido correto?</li>
                                    <li>Usa palavras trabalhadas no seu dia a dia?</li>
                                    <li>Consegue expressar em palavras o que esta sentindo ou pensando?</li>
                                    <li>Entende o sentido das historias de conversar?</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <textarea name="axis_pca" class="form-control" rows="5"
                        placeholder="Descreva o desenvolvimento do aluno no Programa Comunicacao Ativa..."></textarea>
                </div>
            </div>
        </div>
    </div>

    <!-- Botoes -->
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <span class="text-danger">*</span>
            <small class="text-muted">Campos obrigatorios</small>
        </div>
        <div>
            <a href="/admin/observations" class="btn btn-light me-2">Cancelar</a>
            <button type="submit" class="btn btn-hansen px-5">
                <i class="fas fa-save me-2"></i> CRIAR OBSERVACAO
            </button>
        </div>
    </div>
</form>

<script>
// Validacao do formulario
document.getElementById('observationForm').addEventListener('submit', function(e) {
    const studentId = document.getElementById('student_id').value;
    if (!studentId) {
        e.preventDefault();
        alert('Por favor, selecione um aluno.');
        var el = document.getElementById('student_id');
        if (el.tagName === 'SELECT') el.focus();
        return false;
    }
});

// Select2 para busca de alunos (se disponivel e nao for hidden)
var studentSelect = document.querySelector('select#student_id');
if (studentSelect && typeof jQuery !== 'undefined' && jQuery.fn.select2) {
    jQuery(studentSelect).select2({
        placeholder: 'Digite para buscar um aluno...',
        allowClear: true
    });
}

// Ativar tab via parametro ?focus= da URL
(function() {
    var params = new URLSearchParams(window.location.search);
    var focus = params.get('focus');
    if (focus) {
        var tabMap = {
            'general': 'tab-general',
            'movement': 'tab-movement',
            'manual': 'tab-manual',
            'music': 'tab-music',
            'stories': 'tab-stories',
            'pca': 'tab-pca'
        };
        var tabId = tabMap[focus];
        if (tabId) {
            var tabEl = document.getElementById(tabId);
            if (tabEl) {
                var tab = new bootstrap.Tab(tabEl);
                tab.show();
            }
        }
    }
})();
</script>
