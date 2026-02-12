<section class="py-5 bg-light" style="min-height: 80vh; display: flex; align-items: center;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h3 class="text-primary fw-bold">CRIAR CONTA</h3>
                            <p class="text-muted">Registre-se para acessar nossos cursos</p>
                        </div>

                        <?php if (!empty($errors)): ?>
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    <?php foreach ($errors as $err): ?>
                                        <li><?php echo htmlspecialchars($err); ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            </div>
                        <?php endif; ?>

                        <form action="/registro" method="POST">
                            <div class="mb-3">
                                <label class="form-label fw-bold">Nome Completo</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-user text-muted"></i></span>
                                    <input type="text" name="name" class="form-control border-start-0" placeholder="Seu nome completo" value="<?php echo htmlspecialchars($old['name'] ?? ''); ?>" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">E-mail</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-envelope text-muted"></i></span>
                                    <input type="email" name="email" class="form-control border-start-0" placeholder="exemplo@email.com" value="<?php echo htmlspecialchars($old['email'] ?? ''); ?>" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-bold">Senha</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-lock text-muted"></i></span>
                                    <input type="password" name="password" class="form-control border-start-0" placeholder="Mínimo 6 caracteres" required minlength="6">
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold">Confirmar Senha</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-lock text-muted"></i></span>
                                    <input type="password" name="password_confirm" class="form-control border-start-0" placeholder="Repita a senha" required minlength="6">
                                </div>
                            </div>
                            <button type="submit" class="btn btn-hansen w-100 py-3">Criar Conta</button>

                            <div class="text-center mt-4">
                                <p class="text-muted mb-0">Já tem conta? <a href="/login" class="text-primary fw-bold text-decoration-none">Faça login</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
