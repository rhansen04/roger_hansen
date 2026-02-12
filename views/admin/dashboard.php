<h2 class="text-primary fw-bold mb-4"><i class="fas fa-tachometer-alt me-2"></i>Dashboard</h2>

<!-- Cards Estatísticas -->
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card card-stat shadow-sm bg-primary text-white p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0">Alunos</h6>
                    <h3 class="mb-0"><?php echo number_format($stats['total_students']); ?></h3>
                </div>
                <i class="fas fa-user-graduate fa-2x opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stat shadow-sm bg-success text-white p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0">Escolas</h6>
                    <h3 class="mb-0"><?php echo number_format($stats['total_schools']); ?></h3>
                </div>
                <i class="fas fa-school fa-2x opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stat shadow-sm bg-warning text-dark p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0">Contatos</h6>
                    <h3 class="mb-0"><?php echo number_format($stats['total_contacts']); ?></h3>
                </div>
                <i class="fas fa-envelope fa-2x opacity-50"></i>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card card-stat shadow-sm bg-info text-white p-3">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h6 class="mb-0">Observações</h6>
                    <h3 class="mb-0"><?php echo number_format($stats['total_observations']); ?></h3>
                </div>
                <i class="fas fa-clipboard-check fa-2x opacity-50"></i>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <h4 class="fw-bold text-primary mb-0"><?php echo $stats['total_courses']; ?></h4>
            <small class="text-muted">Cursos Ativos</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <h4 class="fw-bold text-success mb-0"><?php echo $stats['total_enrollments']; ?></h4>
            <small class="text-muted">Matrículas Ativas</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <h4 class="fw-bold text-info mb-0"><?php echo $stats['completed_courses']; ?></h4>
            <small class="text-muted">Cursos Concluídos</small>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm text-center p-3">
            <h4 class="fw-bold text-secondary mb-0"><?php echo $stats['total_users']; ?></h4>
            <small class="text-muted">Usuários Total</small>
        </div>
    </div>
</div>

<div class="row">
    <!-- Matrículas Recentes -->
    <div class="col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h5 class="mb-0 text-primary"><i class="fas fa-user-plus me-2"></i>Matrículas Recentes</h5>
            </div>
            <div class="card-body">
                <?php if (empty($recentEnrollments)): ?>
                    <p class="text-muted text-center py-3">Nenhuma matrícula registrada.</p>
                <?php else: ?>
                <table class="table table-hover">
                    <thead>
                        <tr><th>Aluno</th><th>Curso</th><th>Data</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentEnrollments as $e): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($e['student_name']); ?></td>
                            <td><small><?php echo htmlspecialchars($e['course_title']); ?></small></td>
                            <td><small><?php echo date('d/m/Y', strtotime($e['enrollment_date'] ?? 'now')); ?></small></td>
                            <td>
                                <?php $badge = $e['status'] === 'active' ? 'bg-success' : ($e['status'] === 'pending' ? 'bg-warning text-dark' : 'bg-secondary'); ?>
                                <span class="badge <?php echo $badge; ?>"><?php echo ucfirst($e['status']); ?></span>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <a href="/admin/enrollments" class="btn btn-sm btn-outline-primary">Ver todas</a>
                <?php endif; ?>
            </div>
        </div>

        <!-- Contatos Recentes -->
        <?php if (!empty($contacts)): ?>
        <div class="card shadow-sm border-0 mt-4">
            <div class="card-header bg-white">
                <h5 class="mb-0 text-primary"><i class="fas fa-envelope me-2"></i>Contatos Recentes</h5>
            </div>
            <div class="card-body">
                <table class="table table-hover">
                    <thead>
                        <tr><th>Nome</th><th>Email</th><th>Escola</th><th>Data</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($contacts as $c): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($c['contact_name']); ?></td>
                            <td><small><?php echo htmlspecialchars($c['email']); ?></small></td>
                            <td><small><?php echo htmlspecialchars($c['school_name'] ?? '-'); ?></small></td>
                            <td><small><?php echo date('d/m/Y', strtotime($c['created_at'])); ?></small></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <a href="/admin/contacts" class="btn btn-sm btn-outline-primary">Ver todos</a>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Ações Rápidas -->
    <div class="col-md-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white">
                <h5 class="mb-0 text-primary"><i class="fas fa-bolt me-2"></i>Ações Rápidas</h5>
            </div>
            <div class="card-body">
                <a href="/admin/students/create" class="btn btn-outline-primary w-100 mb-2"><i class="fas fa-user-plus me-2"></i> Cadastrar Aluno</a>
                <a href="/admin/schools/create" class="btn btn-outline-success w-100 mb-2"><i class="fas fa-school me-2"></i> Adicionar Escola</a>
                <a href="/admin/courses/create" class="btn btn-outline-info w-100 mb-2"><i class="fas fa-book me-2"></i> Novo Curso</a>
                <a href="/admin/enrollments" class="btn btn-outline-warning w-100 mb-2"><i class="fas fa-user-check me-2"></i> Gerenciar Matrículas</a>
                <a href="/admin/reports" class="btn btn-outline-secondary w-100"><i class="fas fa-chart-bar me-2"></i> Relatórios</a>
            </div>
        </div>
    </div>
</div>
