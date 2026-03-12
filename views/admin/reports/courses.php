<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/admin/reports">Relatorios</a></li>
        <li class="breadcrumb-item active">Cursos</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="fw-bold mb-0">
        <i class="fas fa-graduation-cap me-2 text-primary"></i>Relatorio de Cursos
    </h4>
    <button class="btn btn-outline-secondary btn-sm" onclick="exportTable()" title="Exportar">
        <i class="fas fa-download me-1"></i>Exportar CSV
    </button>
</div>

<?php if (empty($courseStats)): ?>
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="fas fa-chart-bar fa-4x text-muted mb-3"></i>
            <h5 class="text-muted">Nenhum curso encontrado</h5>
            <p class="text-muted">Cadastre cursos para ver os relatorios.</p>
        </div>
    </div>
<?php else: ?>
    <!-- Summary cards -->
    <div class="row g-3 mb-4">
        <?php
            $totalCourses = count($courseStats);
            $totalEnrolled = array_sum(array_column($courseStats, 'total_enrolled'));
            $avgOverall = $totalCourses > 0 ? round(array_sum(array_column($courseStats, 'avg_progress')) / $totalCourses, 1) : 0;
            $totalCompleted = array_sum(array_column($courseStats, 'completed_count'));
        ?>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="fw-bold fs-3 text-primary"><?= $totalCourses ?></div>
                    <small class="text-muted">Cursos Ativos</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="fw-bold fs-3 text-success"><?= $totalEnrolled ?></div>
                    <small class="text-muted">Total Matriculados</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="fw-bold fs-3 text-warning"><?= $avgOverall ?>%</div>
                    <small class="text-muted">Progresso Medio</small>
                </div>
            </div>
        </div>
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body text-center">
                    <div class="fw-bold fs-3 text-info"><?= $totalCompleted ?></div>
                    <small class="text-muted">Concluidos</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Course table -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="courseReportTable">
                    <thead class="table-light">
                        <tr>
                            <th>Curso</th>
                            <th class="text-center">Matriculados</th>
                            <th class="text-center">Professores</th>
                            <th class="text-center">Ativos</th>
                            <th class="text-center">Concluidos</th>
                            <th class="text-center">Progresso Medio</th>
                            <th class="text-center">Acoes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($courseStats as $cs): ?>
                        <tr>
                            <td>
                                <div class="fw-bold"><?= htmlspecialchars($cs['title']) ?></div>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-primary"><?= $cs['total_enrolled'] ?></span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-info"><?= $cs['professor_enrolled'] ?></span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-success"><?= $cs['active_count'] ?></span>
                            </td>
                            <td class="text-center">
                                <span class="badge bg-secondary"><?= $cs['completed_count'] ?></span>
                            </td>
                            <td class="text-center">
                                <div class="d-flex align-items-center justify-content-center gap-2">
                                    <div class="progress flex-grow-1" style="height: 8px; max-width: 100px;">
                                        <div class="progress-bar bg-success" style="width: <?= $cs['avg_progress'] ?? 0 ?>%"></div>
                                    </div>
                                    <small class="fw-bold"><?= $cs['avg_progress'] ?? 0 ?>%</small>
                                </div>
                            </td>
                            <td class="text-center">
                                <a href="/admin/courses/<?= $cs['id'] ?>" class="btn btn-sm btn-outline-primary" title="Ver curso">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>

<script>
function exportTable() {
    const table = document.getElementById('courseReportTable');
    if (!table) return;
    let csv = [];
    const rows = table.querySelectorAll('tr');
    rows.forEach(function(row) {
        const cols = row.querySelectorAll('td, th');
        const rowData = [];
        cols.forEach(function(col, idx) {
            if (idx < cols.length - 1) { // Skip actions column
                rowData.push('"' + col.textContent.trim().replace(/"/g, '""') + '"');
            }
        });
        csv.push(rowData.join(','));
    });
    const csvContent = '\uFEFF' + csv.join('\n');
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement('a');
    link.href = URL.createObjectURL(blob);
    link.download = 'relatorio_cursos_' + new Date().toISOString().slice(0,10) + '.csv';
    link.click();
}
</script>
