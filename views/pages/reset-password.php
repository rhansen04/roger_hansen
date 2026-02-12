<section class="py-5 bg-light" style="min-height: 80vh; display: flex; align-items: center;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h3 class="text-primary fw-bold">NOVA SENHA</h3>
                            <p class="text-muted">Defina sua nova senha de acesso</p>
                        </div>

                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>

                        <form action="/redefinir-senha" method="POST">
                            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nova Senha</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-lock text-muted"></i></span>
                                    <input type="password" name="password" class="form-control border-start-0" placeholder="Mínimo 6 caracteres" required minlength="6">
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold">Confirmar Nova Senha</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-lock text-muted"></i></span>
                                    <input type="password" name="password_confirm" class="form-control border-start-0" placeholder="Repita a nova senha" required minlength="6">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-hansen w-100 py-3">Redefinir Senha</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
