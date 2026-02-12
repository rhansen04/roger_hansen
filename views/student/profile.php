<h2 class="text-primary fw-bold mb-4">MEU PERFIL</h2>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $err): ?>
                <li><?php echo htmlspecialchars($err); ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="fw-bold mb-0"><i class="fas fa-user-edit me-2 text-primary"></i> Dados Pessoais</h5>
            </div>
            <div class="card-body p-4">
                <form action="/minha-conta/perfil" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nome Completo</label>
                        <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($user['name'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">E-mail</label>
                        <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($user['email'] ?? ''); ?>" required>
                    </div>
                    <button type="submit" class="btn btn-hansen text-white"><i class="fas fa-save me-2"></i> Salvar Alterações</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 pt-4 px-4">
                <h5 class="fw-bold mb-0"><i class="fas fa-lock me-2 text-primary"></i> Alterar Senha</h5>
            </div>
            <div class="card-body p-4">
                <form action="/minha-conta/senha" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Senha Atual</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nova Senha</label>
                        <input type="password" name="new_password" class="form-control" required minlength="6">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Confirmar Nova Senha</label>
                        <input type="password" name="confirm_password" class="form-control" required minlength="6">
                    </div>
                    <button type="submit" class="btn btn-hansen text-white"><i class="fas fa-key me-2"></i> Alterar Senha</button>
                </form>
            </div>
        </div>
    </div>
</div>
