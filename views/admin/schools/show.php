<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-md-8">
            <h2 class="fw-bold text-dark">
                <i class="fas fa-school text-primary me-2"></i> Detalhes da Escola
            </h2>
            <p class="text-muted">Informações completas e alunos vinculados</p>
        </div>
        <div class="col-md-4 text-end">
            <a href="/admin/schools" class="btn btn-outline-secondary me-2">
                <i class="fas fa-arrow-left me-2"></i> Voltar
            </a>
            <a href="/admin/schools/<?php echo $school['id']; ?>/edit" class="btn btn-hansen">
                <i class="fas fa-edit me-2"></i> Editar
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Informações da Escola -->
        <div class="col-md-8">
            <div class="card card-stat mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-info-circle text-primary me-2"></i> Informações Gerais
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Nome da Escola</label>
                            <p class="fw-bold mb-0"><?php echo htmlspecialchars($school['name']); ?></p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="text-muted small">Cidade</label>
                            <p class="mb-0"><?php echo !empty($school['city']) ? htmlspecialchars($school['city']) : '-'; ?></p>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="text-muted small">Estado</label>
                            <p class="mb-0"><?php echo !empty($school['state']) ? htmlspecialchars($school['state']) : '-'; ?></p>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="text-muted small">Endereço</label>
                            <p class="mb-0"><?php echo !empty($school['address']) ? htmlspecialchars($school['address']) : '-'; ?></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Pessoa de Contato</label>
                            <p class="mb-0"><?php echo !empty($school['contact_person']) ? htmlspecialchars($school['contact_person']) : '-'; ?></p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Telefone</label>
                            <p class="mb-0"><?php echo !empty($school['phone']) ? htmlspecialchars($school['phone']) : '-'; ?></p>
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="text-muted small">Email</label>
                            <p class="mb-0">
                                <?php if (!empty($school['email'])): ?>
                                    <a href="mailto:<?php echo htmlspecialchars($school['email']); ?>">
                                        <?php echo htmlspecialchars($school['email']); ?>
                                    </a>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-stat">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-file-contract text-primary me-2"></i> Informações do Contrato
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Data de Início</label>
                            <p class="mb-0">
                                <?php
                                if (!empty($school['contract_start_date'])) {
                                    echo date('d/m/Y', strtotime($school['contract_start_date']));
                                } else {
                                    echo '-';
                                }
                                ?>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Data de Término</label>
                            <p class="mb-0">
                                <?php
                                if (!empty($school['contract_end_date'])) {
                                    echo date('d/m/Y', strtotime($school['contract_end_date']));
                                } else {
                                    echo '-';
                                }
                                ?>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Status</label>
                            <p class="mb-0">
                                <?php if ($school['status'] === 'active'): ?>
                                    <span class="badge bg-success">Ativo</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Inativo</span>
                                <?php endif; ?>
                            </p>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="text-muted small">Total de Alunos</label>
                            <p class="mb-0">
                                <span class="badge bg-info"><?php echo $school['students_count']; ?> aluno<?php echo $school['students_count'] != 1 ? 's' : ''; ?></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Logo e Estatísticas -->
        <div class="col-md-4">
            <div class="card card-stat mb-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-image text-primary me-2"></i> Logo da Escola
                    </h5>
                </div>
                <div class="card-body text-center">
                    <?php if (!empty($school['logo_url'])): ?>
                        <img src="<?php echo htmlspecialchars($school['logo_url']); ?>"
                             alt="Logo da Escola"
                             class="img-fluid rounded"
                             style="max-height: 200px; object-fit: contain;">
                    <?php else: ?>
                        <div class="bg-light rounded p-5">
                            <i class="fas fa-school fa-5x text-muted"></i>
                            <p class="text-muted mt-3 mb-0 small">Nenhuma logo cadastrada</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card card-stat">
                <div class="card-header bg-white">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-calendar text-primary me-2"></i> Datas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="text-muted small">Cadastrado em</label>
                        <p class="mb-0 small">
                            <?php echo date('d/m/Y H:i', strtotime($school['created_at'])); ?>
                        </p>
                    </div>
                    <?php if (!empty($school['updated_at'])): ?>
                        <div>
                            <label class="text-muted small">Última atualização</label>
                            <p class="mb-0 small">
                                <?php echo date('d/m/Y H:i', strtotime($school['updated_at'])); ?>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Alunos -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card card-stat">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0 fw-bold">
                        <i class="fas fa-user-graduate text-primary me-2"></i>
                        Alunos desta Escola (<?php echo count($students); ?>)
                    </h5>
                    <a href="/admin/students/create?school_id=<?php echo $school['id']; ?>" class="btn btn-sm btn-hansen">
                        <i class="fas fa-plus me-2"></i> Adicionar Aluno
                    </a>
                </div>
                <div class="card-body">
                    <?php if (empty($students)): ?>
                        <div class="text-center py-4">
                            <i class="fas fa-user-graduate fa-3x text-muted mb-3"></i>
                            <h6 class="text-muted">Nenhum aluno vinculado a esta escola</h6>
                            <p class="text-muted small">Clique no botão "Adicionar Aluno" para vincular alunos a esta escola</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 60px;">Foto</th>
                                        <th>Nome</th>
                                        <th>Data de Nascimento</th>
                                        <th>Idade</th>
                                        <th class="text-center">Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($students as $student): ?>
                                        <tr>
                                            <td>
                                                <?php if (!empty($student['photo_url'])): ?>
                                                    <img src="<?php echo htmlspecialchars($student['photo_url']); ?>"
                                                         alt="Foto"
                                                         class="rounded-circle"
                                                         style="width: 40px; height: 40px; object-fit: cover;">
                                                <?php else: ?>
                                                    <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center"
                                                         style="width: 40px; height: 40px;">
                                                        <i class="fas fa-user text-white"></i>
                                                    </div>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <strong><?php echo htmlspecialchars($student['name']); ?></strong>
                                            </td>
                                            <td>
                                                <?php
                                                echo !empty($student['birth_date'])
                                                    ? date('d/m/Y', strtotime($student['birth_date']))
                                                    : '-';
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                if (!empty($student['birth_date'])) {
                                                    $birthDate = new DateTime($student['birth_date']);
                                                    $today = new DateTime();
                                                    $age = $today->diff($birthDate)->y;
                                                    echo $age . ' anos';
                                                } else {
                                                    echo '-';
                                                }
                                                ?>
                                            </td>
                                            <td class="text-center">
                                                <a href="/admin/students/<?php echo $student['id']; ?>"
                                                   class="btn btn-sm btn-info text-white"
                                                   title="Ver Detalhes">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
