<section class="py-5 bg-light" style="min-height: 80vh; display: flex; align-items: center;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card border-0 shadow-lg">
                    <div class="card-body p-5">
                        <div class="text-center mb-4">
                            <h3 class="text-primary fw-bold">ACESSO AO PORTAL</h3>
                            <p class="text-muted">Entre com suas credenciais para acessar o painel</p>
                        </div>
                        
                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?php echo $error; ?></div>
                        <?php endif; ?>

                        <form action="/login" method="POST">
                            <div class="mb-3">
                                <label class="form-label fw-bold">E-mail</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-envelope text-muted"></i></span>
                                    <input type="email" name="email" class="form-control border-start-0" placeholder="exemplo@email.com" required>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="form-label fw-bold">Senha</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0"><i class="fas fa-lock text-muted"></i></span>
                                    <input type="password" name="password" id="passwordInput" class="form-control border-start-0 border-end-0" placeholder="********" required>
                                    <span class="input-group-text bg-white border-start-0" style="cursor:pointer;" onclick="let p=document.getElementById('passwordInput'),i=this.querySelector('i');if(p.type==='password'){p.type='text';i.classList.replace('fa-eye','fa-eye-slash')}else{p.type='password';i.classList.replace('fa-eye-slash','fa-eye')}"><i class="fas fa-eye text-muted"></i></span>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-hansen w-100 py-3">Entrar no Sistema</button>
                            
                            <div class="text-center mt-4">
                                <a href="/esqueci-senha" class="text-decoration-none text-muted small">Esqueceu sua senha?</a>
                            </div>
                        </form>

                        <?php if (isset($_SESSION['success_message'])): ?>
                            <div class="alert alert-success mt-3"><?php echo $_SESSION['success_message']; ?></div>
                            <?php unset($_SESSION['success_message']); ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="text-center mt-4">
                    <p class="text-muted">Ainda não tem conta? <a href="/registro" class="text-primary fw-bold">Cadastre-se</a></p>
                </div>
            </div>
        </div>
    </div>
</section>
