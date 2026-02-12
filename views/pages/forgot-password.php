<section class="py-5 bg-light" style="min-height: 80vh; display: flex; align-items: center;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h3 class="text-primary fw-bold">ESQUECI MINHA SENHA</h3>
                            <p class="text-muted">Informe seu e-mail para receber o link de recuperação</p>
                        </div>

                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
                        <?php endif; ?>

                        <?php if (isset($success)): ?>
                            <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
                        <?php endif; ?>

                        <?php if (isset($_SESSION['error_message'])): ?>
                            <div class="alert alert-danger"><?php echo $_SESSION['error_message']; ?></div>
                            <?php unset($_SESSION['error_message']); ?>
                        <?php endif; ?>

                        <form action="/esqueci-senha" method="POST">
                            <div class="mb-4">
                                <label class="form-label fw-bold">E-mail</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-envelope text-muted"></i></span>
                                    <input type="email" name="email" class="form-control border-start-0" placeholder="exemplo@email.com" required>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-hansen w-100 py-3">Enviar Link de Recuperação</button>

                            <div class="text-center mt-4">
                                <a href="/login" class="text-decoration-none text-muted"><i class="fas fa-arrow-left me-1"></i> Voltar ao Login</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
