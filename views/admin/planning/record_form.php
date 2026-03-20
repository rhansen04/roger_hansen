<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/admin/planning" class="text-decoration-none">Planejamentos</a></li>
        <li class="breadcrumb-item active">Registro do Período</li>
    </ol>
</nav>

<?php if (isset($_SESSION['error_message'])): ?>
    <div class="alert alert-danger alert-dismissible fade show">
        <i class="fas fa-exclamation-triangle me-2"></i><?= $_SESSION['error_message'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['error_message']); ?>
<?php endif; ?>

<!-- Info bar -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body py-3">
        <div class="row text-center align-items-center">
            <div class="col-md-3">
                <small class="text-muted">Professor:</small><br>
                <strong><?= htmlspecialchars($submission['teacher_name'] ?? '-') ?></strong>
            </div>
            <div class="col-md-3">
                <small class="text-muted">Turma:</small><br>
                <strong><?= htmlspecialchars($submission['classroom_name'] ?? '-') ?></strong>
            </div>
            <div class="col-md-3">
                <small class="text-muted">Período:</small><br>
                <strong><?= date('d/m', strtotime($submission['period_start'])) ?> - <?= date('d/m/Y', strtotime($submission['period_end'])) ?></strong>
            </div>
            <div class="col-md-3">
                <small class="text-muted">Template:</small><br>
                <strong><?= htmlspecialchars($submission['template_title'] ?? '-') ?></strong>
            </div>
        </div>
    </div>
</div>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="/admin/planning" class="text-decoration-none text-muted"><i class="fas fa-arrow-left me-2"></i> Voltar</a>
        <h2 class="text-primary fw-bold mt-2 mb-0">
            <i class="fas fa-clipboard-list me-2"></i>REGISTRO — FINAL DA SEMANA
        </h2>
    </div>
</div>

<form method="POST" action="<?= $isEdit ? "/admin/planning/{$submission['id']}/record/update" : "/admin/planning/{$submission['id']}/record" ?>">

    <!-- 1. Síntese -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom">
            <h6 class="mb-0 fw-bold text-primary"><i class="fas fa-pen me-2"></i>Síntese do Desenvolvimento das Atividades</h6>
            <small class="text-muted">Como as propostas aconteceram ao longo da semana.</small>
        </div>
        <div class="card-body">
            <textarea name="activity_synthesis" class="form-control" rows="4"
                placeholder="Descreva como as atividades propostas se desenvolveram durante o período..."><?= htmlspecialchars($record['activity_synthesis'] ?? '') ?></textarea>
        </div>
    </div>

    <!-- 2. Execução do Planejamento -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom">
            <h6 class="mb-0 fw-bold text-primary"><i class="fas fa-tasks me-2"></i>Execução do Planejamento</h6>
        </div>
        <div class="card-body">
            <div class="d-flex gap-4 mb-3">
                <?php foreach (['sim' => 'Sim', 'parcialmente' => 'Parcialmente', 'nao' => 'Não'] as $val => $label): ?>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="planning_execution" id="exec_<?= $val ?>" value="<?= $val ?>"
                        <?= (($record['planning_execution'] ?? 'sim') === $val) ? 'checked' : '' ?>
                        onchange="toggleJustification(this.value)">
                    <label class="form-check-label fw-bold" for="exec_<?= $val ?>"><?= $label ?></label>
                </div>
                <?php endforeach; ?>
            </div>
            <div id="justificationBlock" style="<?= in_array($record['planning_execution'] ?? '', ['parcialmente','nao']) ? '' : 'display:none' ?>">
                <label class="form-label text-muted small">Se necessário, justifique:</label>
                <textarea name="planning_execution_justification" class="form-control" rows="2"
                    placeholder="Justifique o que não foi executado conforme planejado..."><?= htmlspecialchars($record['planning_execution_justification'] ?? '') ?></textarea>
            </div>
        </div>
    </div>

    <!-- 3. Engajamento das Crianças -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom">
            <h6 class="mb-0 fw-bold text-primary"><i class="fas fa-child me-2"></i>Engajamento das Crianças</h6>
        </div>
        <div class="card-body">
            <div class="d-flex gap-4 mb-3">
                <?php foreach (['alto' => 'Alto', 'medio' => 'Médio', 'baixo' => 'Baixo'] as $val => $label): ?>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="child_engagement" id="eng_<?= $val ?>" value="<?= $val ?>"
                        <?= (($record['child_engagement'] ?? 'alto') === $val) ? 'checked' : '' ?>>
                    <label class="form-check-label fw-bold" for="eng_<?= $val ?>"><?= $label ?></label>
                </div>
                <?php endforeach; ?>
            </div>
            <label class="form-label text-muted small">Comentário breve:</label>
            <textarea name="child_engagement_comment" class="form-control" rows="2"
                placeholder="Descreva brevemente o nível de engajamento observado..."><?= htmlspecialchars($record['child_engagement_comment'] ?? '') ?></textarea>
        </div>
    </div>

    <!-- 4. Ajustes Realizados -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom">
            <h6 class="mb-0 fw-bold text-primary"><i class="fas fa-sliders-h me-2"></i>Ajustes Realizados</h6>
        </div>
        <div class="card-body">
            <div class="row g-2 mb-3">
                <?php
                $adjOptions = [
                    'time' => 'Tempo', 'space' => 'Espaço', 'materials' => 'Materiais',
                    'mediation' => 'Mediação docente', 'interest' => 'Interesse das crianças'
                ];
                foreach ($adjOptions as $key => $label):
                    $checked = !empty($record["adjustments_{$key}"]) ? 'checked' : '';
                ?>
                <div class="col-md-4">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="adjustments[]" value="<?= $key ?>" id="adj_<?= $key ?>" <?= $checked ?>>
                        <label class="form-check-label" for="adj_<?= $key ?>"><?= $label ?></label>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <label class="form-label text-muted small">Descrição (se necessário):</label>
            <textarea name="adjustments_description" class="form-control" rows="2"
                placeholder="Descreva os ajustes realizados..."><?= htmlspecialchars($record['adjustments_description'] ?? '') ?></textarea>
        </div>
    </div>

    <!-- 5. O que as crianças trouxeram de novo -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom">
            <h6 class="mb-0 fw-bold text-primary"><i class="fas fa-star text-warning me-2"></i>O que as crianças trouxeram de novo para a proposta?</h6>
            <small class="text-muted">Interesses espontâneos, falas marcantes, descobertas ou caminhos inesperados.</small>
        </div>
        <div class="card-body">
            <textarea name="children_novelty" class="form-control" rows="4"
                placeholder="Registre aqui o que surgiu de novo, inesperado ou significativo durante as atividades..."><?= htmlspecialchars($record['children_novelty'] ?? '') ?></textarea>
        </div>
    </div>

    <!-- 6. Avanços ou Desafios Observados -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom">
            <h6 class="mb-0 fw-bold text-primary"><i class="fas fa-chart-line me-2"></i>Avanços ou Desafios Observados</h6>
            <small class="text-muted">Interação, autonomia, concentração, cooperação ou dificuldades percebidas.</small>
        </div>
        <div class="card-body">
            <textarea name="advances_challenges" class="form-control" rows="4"
                placeholder="Registre avanços no desenvolvimento das crianças ou desafios identificados..."><?= htmlspecialchars($record['advances_challenges'] ?? '') ?></textarea>
        </div>
    </div>

    <!-- 7. Necessidade de Apoio -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom">
            <h6 class="mb-0 fw-bold text-primary"><i class="fas fa-hands-helping me-2"></i>Necessidade de Apoio</h6>
        </div>
        <div class="card-body">
            <div class="row g-2 mb-3">
                <?php
                $supOptions = [
                    'pedagogical' => 'Pedagógico', 'organizational' => 'Organizacional',
                    'formative' => 'Formativo', 'structural' => 'Estrutural'
                ];
                foreach ($supOptions as $key => $label):
                    $checked = !empty($record["support_{$key}"]) ? 'checked' : '';
                ?>
                <div class="col-md-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="support[]" value="<?= $key ?>" id="sup_<?= $key ?>" <?= $checked ?>>
                        <label class="form-check-label" for="sup_<?= $key ?>"><?= $label ?></label>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <label class="form-label text-muted small">Descreva:</label>
            <textarea name="support_description" class="form-control" rows="2"
                placeholder="Descreva a necessidade de apoio identificada..."><?= htmlspecialchars($record['support_description'] ?? '') ?></textarea>
        </div>
    </div>

    <!-- Botões -->
    <div class="card border-0 shadow-sm">
        <div class="card-body d-flex justify-content-between">
            <a href="/admin/planning" class="btn btn-light"><i class="fas fa-times me-2"></i>Cancelar</a>
            <button type="submit" class="btn btn-hansen text-white px-5">
                <i class="fas fa-save me-2"></i><?= $isEdit ? 'Salvar Alterações' : 'Salvar Registro' ?>
            </button>
        </div>
    </div>
</form>

<script>
function toggleJustification(val) {
    const block = document.getElementById('justificationBlock');
    block.style.display = (val === 'parcialmente' || val === 'nao') ? '' : 'none';
}
</script>
