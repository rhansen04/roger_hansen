<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-primary fw-bold mb-0">
        <i class="fas fa-envelope me-2"></i>Contatos
        <?php if ($unreadCount > 0): ?>
            <span class="badge bg-danger"><?php echo $unreadCount; ?> novo(s)</span>
        <?php endif; ?>
    </h2>
</div>

<?php if (!empty($_SESSION['success_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show"><?php echo $_SESSION['success_message']; unset($_SESSION['success_message']); ?></div>
<?php endif; ?>

<div class="card border-0 shadow-sm">
    <div class="card-body">
        <?php if (empty($contacts)): ?>
            <p class="text-muted text-center py-3">Nenhum contato recebido.</p>
        <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th></th>
                        <th>Nome</th>
                        <th>Escola</th>
                        <th>Email</th>
                        <th>Telefone</th>
                        <th>Cidade</th>
                        <th>Data</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($contacts as $c): ?>
                    <tr class="<?php echo !$c['is_read'] ? 'fw-bold' : ''; ?>">
                        <td>
                            <?php if (!$c['is_read']): ?>
                                <span class="badge bg-primary rounded-pill">Novo</span>
                            <?php endif; ?>
                        </td>
                        <td><?php echo htmlspecialchars($c['contact_name']); ?></td>
                        <td><?php echo htmlspecialchars($c['school_name']); ?></td>
                        <td><small><?php echo htmlspecialchars($c['email']); ?></small></td>
                        <td><small><?php echo htmlspecialchars($c['phone']); ?></small></td>
                        <td><small><?php echo htmlspecialchars($c['city_state']); ?></small></td>
                        <td><small><?php echo date('d/m/Y H:i', strtotime($c['created_at'])); ?></small></td>
                        <td>
                            <a href="/admin/contacts/<?php echo $c['id']; ?>" class="btn btn-sm btn-outline-primary"><i class="fas fa-eye"></i></a>
                            <form method="POST" action="/admin/contacts/<?php echo $c['id']; ?>/delete" class="d-inline" onsubmit="return confirm('Remover este contato?')">
                                <button class="btn btn-sm btn-outline-danger"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>
