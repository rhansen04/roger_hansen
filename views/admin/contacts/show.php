<nav aria-label="breadcrumb" class="mb-3">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/dashboard" class="text-decoration-none">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="/admin/contacts" class="text-decoration-none">Contatos</a></li>
        <li class="breadcrumb-item active">Detalhe</li>
    </ol>
</nav>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="text-primary fw-bold mb-0"><i class="fas fa-envelope-open me-2"></i>Contato #<?php echo $contact['id']; ?></h2>
    <div>
        <form method="POST" action="/admin/contacts/<?php echo $contact['id']; ?>/delete" class="d-inline" onsubmit="return confirm('Remover este contato?')">
            <button class="btn btn-outline-danger"><i class="fas fa-trash me-1"></i> Remover</button>
        </form>
        <a href="/admin/contacts" class="btn btn-outline-secondary ms-2">Voltar</a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="text-muted small">Nome do Contato</label>
                        <p class="fw-bold"><?php echo htmlspecialchars($contact['contact_name']); ?></p>
                    </div>
                    <div class="col-md-6">
                        <label class="text-muted small">Escola / Instituição</label>
                        <p class="fw-bold"><?php echo htmlspecialchars($contact['school_name']); ?></p>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label class="text-muted small">Email</label>
                        <p><a href="mailto:<?php echo htmlspecialchars($contact['email']); ?>"><?php echo htmlspecialchars($contact['email']); ?></a></p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Telefone</label>
                        <p><?php echo htmlspecialchars($contact['phone']); ?></p>
                    </div>
                    <div class="col-md-4">
                        <label class="text-muted small">Cidade / Estado</label>
                        <p><?php echo htmlspecialchars($contact['city_state']); ?></p>
                    </div>
                </div>
                <?php if (!empty($contact['message'])): ?>
                <div class="mb-3">
                    <label class="text-muted small">Mensagem</label>
                    <div class="p-3 bg-light rounded"><?php echo nl2br(htmlspecialchars($contact['message'])); ?></div>
                </div>
                <?php endif; ?>
                <?php if (!empty($contact['attachment'])): ?>
                <div class="mb-3">
                    <label class="text-muted small">Anexo</label>
                    <p><i class="fas fa-paperclip me-1"></i> <?php echo htmlspecialchars($contact['attachment']); ?></p>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <h6 class="fw-bold mb-3">Informações</h6>
                <p class="mb-2"><small class="text-muted">Recebido em:</small><br><?php echo date('d/m/Y H:i', strtotime($contact['created_at'])); ?></p>
                <p class="mb-0"><small class="text-muted">Status:</small><br>
                    <?php echo $contact['is_read'] ? '<span class="badge bg-success">Lido</span>' : '<span class="badge bg-primary">Novo</span>'; ?>
                </p>
            </div>
        </div>
    </div>
</div>
