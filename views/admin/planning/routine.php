<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/admin/planning" class="text-decoration-none">Planejamentos</a></li>
        <li class="breadcrumb-item"><a href="/admin/planning/<?= $submission['id'] ?>" class="text-decoration-none">#<?= $submission['id'] ?></a></li>
        <li class="breadcrumb-item active">Rotina Semanal</li>
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

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="/admin/planning/<?= $submission['id'] ?>" class="text-decoration-none text-muted mb-2 d-block">
            <i class="fas fa-arrow-left me-2"></i> Voltar ao planejamento
        </a>
        <h2 class="text-primary fw-bold mb-0">
            <i class="fas fa-clock me-2"></i>ROTINA SEMANAL
        </h2>
        <p class="text-muted mb-0">
            <?= htmlspecialchars($submission['classroom_name'] ?? '-') ?>
            &mdash;
            <?= date('d/m', strtotime($submission['period_start'])) ?> a <?= date('d/m/Y', strtotime($submission['period_end'])) ?>
        </p>
    </div>
    <?php if ($canEdit): ?>
    <button type="submit" form="routineForm" class="btn btn-hansen text-white">
        <i class="fas fa-save me-2"></i> Salvar Rotina
    </button>
    <?php endif; ?>
</div>

<?php if ($canEdit): ?>
<form id="routineForm" method="POST" action="/admin/planning/<?= $submission['id'] ?>/routine">
<?php endif; ?>

<!-- Weekly Comparison Table -->
<div class="row">
    <?php foreach ($dayNames as $dayNum => $dayLabel):
        $dayRoutines = $routines[$dayNum] ?? [];
    ?>
    <div class="col-12 col-lg mb-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-primary text-white fw-bold text-center py-2">
                <i class="fas fa-calendar-day me-1"></i> <?= $dayLabel ?>
            </div>
            <div class="card-body p-2" id="day-<?= $dayNum ?>">
                <?php if (!empty($dayRoutines)):
                    foreach ($dayRoutines as $idx => $activity):
                ?>
                    <div class="routine-entry mb-2 p-2 bg-light rounded border" data-day="<?= $dayNum ?>">
                        <?php if ($canEdit): ?>
                            <div class="d-flex gap-2 mb-1">
                                <input type="text"
                                       name="routines[<?= $dayNum ?>][<?= $idx ?>][time_slot]"
                                       class="form-control form-control-sm"
                                       placeholder="08:00-08:30"
                                       value="<?= htmlspecialchars($activity['time_slot']) ?>"
                                       style="max-width: 130px;">
                                <button type="button" class="btn btn-sm btn-outline-danger btn-remove-activity" title="Remover">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                            <textarea name="routines[<?= $dayNum ?>][<?= $idx ?>][activity_description]"
                                      class="form-control form-control-sm"
                                      rows="2"
                                      placeholder="Descricao da atividade"><?= htmlspecialchars($activity['activity_description']) ?></textarea>
                        <?php else: ?>
                            <div class="d-flex align-items-center mb-1">
                                <span class="badge bg-primary me-2"><?= htmlspecialchars($activity['time_slot']) ?></span>
                            </div>
                            <small><?= nl2br(htmlspecialchars($activity['activity_description'])) ?></small>
                        <?php endif; ?>
                    </div>
                <?php
                    endforeach;
                else:
                    if (!$canEdit): ?>
                        <p class="text-muted small text-center py-3 mb-0 fst-italic">Nenhuma atividade</p>
                    <?php endif;
                endif; ?>

                <?php if ($canEdit): ?>
                <div class="text-center mt-2">
                    <button type="button" class="btn btn-sm btn-outline-primary btn-add-activity" data-day="<?= $dayNum ?>">
                        <i class="fas fa-plus me-1"></i> Adicionar atividade
                    </button>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<?php if ($canEdit): ?>
    <div class="text-center mb-4">
        <button type="submit" class="btn btn-hansen btn-lg text-white">
            <i class="fas fa-save me-2"></i> Salvar Rotina
        </button>
    </div>
</form>
<?php endif; ?>

<?php if ($canEdit): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Track counters per day for unique name indices
    const counters = {};
    document.querySelectorAll('.routine-entry').forEach(function(entry) {
        const day = entry.getAttribute('data-day');
        counters[day] = (counters[day] || 0) + 1;
    });

    // Add activity
    document.querySelectorAll('.btn-add-activity').forEach(function(btn) {
        btn.addEventListener('click', function() {
            const day = this.getAttribute('data-day');
            const container = document.getElementById('day-' + day);
            const idx = counters[day] || 0;
            counters[day] = idx + 1;

            const div = document.createElement('div');
            div.className = 'routine-entry mb-2 p-2 bg-light rounded border';
            div.setAttribute('data-day', day);
            div.innerHTML = `
                <div class="d-flex gap-2 mb-1">
                    <input type="text"
                           name="routines[${day}][${idx}][time_slot]"
                           class="form-control form-control-sm"
                           placeholder="08:00-08:30"
                           style="max-width: 130px;">
                    <button type="button" class="btn btn-sm btn-outline-danger btn-remove-activity" title="Remover">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <textarea name="routines[${day}][${idx}][activity_description]"
                          class="form-control form-control-sm"
                          rows="2"
                          placeholder="Descricao da atividade"></textarea>
            `;

            // Insert before the "add" button wrapper
            const addBtnWrapper = container.querySelector('.text-center.mt-2');
            container.insertBefore(div, addBtnWrapper);

            // Focus the time input
            div.querySelector('input').focus();

            // Attach remove handler
            div.querySelector('.btn-remove-activity').addEventListener('click', function() {
                div.remove();
            });
        });
    });

    // Remove activity (for existing entries)
    document.querySelectorAll('.btn-remove-activity').forEach(function(btn) {
        btn.addEventListener('click', function() {
            this.closest('.routine-entry').remove();
        });
    });
});
</script>
<?php endif; ?>

<style>
.routine-entry textarea {
    resize: vertical;
    min-height: 50px;
}
@media (max-width: 991.98px) {
    .routine-entry {
        font-size: 0.9rem;
    }
}
@media print {
    #sidebar, .navbar, .breadcrumb, .btn, .btn-add-activity, .btn-remove-activity { display: none !important; }
    #content { margin-left: 0 !important; }
    .card { break-inside: avoid; }
}
</style>
