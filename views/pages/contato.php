<header class="bg-primary-hansen text-white py-5 text-center">
    <div class="container py-4">
        <h6 class="text-secondary fw-bold text-uppercase">Fale Conosco</h6>
        <h1 class="display-4 fw-bold">TRANSFORMAMOS A EXPERIÊNCIA EDUCACIONAL NA PRIMEIRA INFÂNCIA!</h1>
        <p class="lead">Há mais de 18 anos dedicados à excelência na educação infantil</p>
    </div>
</header>

<section class="py-5 bg-light">
    <div class="container">
        <div class="row">
            <div class="col-lg-7">
                <div class="card border-0 shadow p-4 p-md-5">
                    <h2 class="text-primary fw-bold mb-4">SEJA UMA ESCOLA PARCEIRA</h2>
                    <p class="text-muted mb-4">Preencha o formulário abaixo e nossa equipe entrará em contato para apresentar nossos programas pedagógicos.</p>

                    <?php if (isset($success)): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($success) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <?php if (isset($errors) && !empty($errors)): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i>
                            <ul class="mb-0">
                                <?php foreach ($errors as $error): ?>
                                    <li><?= htmlspecialchars($error) ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    <?php endif; ?>

                    <form action="/contato" method="POST" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small">NOME DA ESCOLA</label>
                                <input type="text" class="form-control bg-light border-0 py-3" name="school_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small">NOME DO RESPONSÁVEL</label>
                                <input type="text" class="form-control bg-light border-0 py-3" name="contact_name" required>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small">E-MAIL</label>
                                <input type="email" class="form-control bg-light border-0 py-3" name="email" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold small">TELEFONE</label>
                                <input type="text" class="form-control bg-light border-0 py-3" name="phone" required placeholder="(00) 00000-0000">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8 mb-3">
                                <label class="form-label fw-bold small">CIDADE / ESTADO</label>
                                <input type="text" class="form-control bg-light border-0 py-3" name="city_state" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label fw-bold small">ANEXAR ARQUIVO (OPCIONAL)</label>
                                <input type="file" class="form-control bg-light border-0 py-3" name="attachment">
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label fw-bold small">MENSAGEM</label>
                            <textarea class="form-control bg-light border-0 py-3" name="message" rows="5" placeholder="Como podemos ajudar sua escola?"></textarea>
                        </div>
                        <button type="submit" class="btn btn-hansen btn-lg w-100 py-3 shadow">ENVIAR SOLICITAÇÃO</button>
                    </form>
                </div>
            </div>
            
            <div class="col-lg-5 ps-lg-5 mt-5 mt-lg-0">
                <div class="mb-5">
                    <h3 class="text-primary fw-bold mb-4">INFORMAÇÕES DE CONTATO</h3>
                    <div class="d-flex align-items-center mb-4">
                        <div class="icon-circle bg-white text-primary shadow-sm me-3" style="width: 50px; height: 50px;">
                            <i class="fab fa-whatsapp"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">WhatsApp</h6>
                            <a href="https://wa.me/5548991427836" class="text-decoration-none text-muted">(48) 99142-7836</a>
                        </div>
                    </div>
                    <div class="d-flex align-items-center mb-4">
                        <div class="icon-circle bg-white text-primary shadow-sm me-3" style="width: 50px; height: 50px;">
                            <i class="fas fa-envelope"></i>
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold">E-mail</h6>
                            <a href="mailto:contato@hanseneducacional.com.br" class="text-decoration-none text-muted">contato@hanseneducacional.com.br</a>
                        </div>
                    </div>
                </div>

                <div>
                    <h3 class="text-primary fw-bold mb-4">REDES SOCIAIS</h3>
                    <div class="mb-3">
                        <a href="https://www.instagram.com/hansen.educacional" target="_blank" class="d-flex align-items-center text-decoration-none text-dark mb-3">
                            <div class="icon-circle bg-white text-primary shadow-sm me-3" style="width: 50px; height: 50px;">
                                <i class="fab fa-instagram"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">@hansen.educacional</h6>
                                <p class="text-muted small mb-0">Siga no Instagram</p>
                            </div>
                        </a>
                        <a href="https://www.instagram.com/rogerhansen.educador" target="_blank" class="d-flex align-items-center text-decoration-none text-dark mb-3">
                            <div class="icon-circle bg-white text-primary shadow-sm me-3" style="width: 50px; height: 50px;">
                                <i class="fab fa-instagram"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">@rogerhansen.educador</h6>
                                <p class="text-muted small mb-0">Siga Roger Hansen</p>
                            </div>
                        </a>
                        <a href="https://www.youtube.com/@pedagogiaflorenca-educacao80" target="_blank" class="d-flex align-items-center text-decoration-none text-dark">
                            <div class="icon-circle bg-white text-primary shadow-sm me-3" style="width: 50px; height: 50px;">
                                <i class="fab fa-youtube"></i>
                            </div>
                            <div>
                                <h6 class="mb-0 fw-bold">YouTube</h6>
                                <p class="text-muted small mb-0">Canal Pedagogia Florença</p>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="mt-5 p-4 bg-white rounded-3 shadow-sm border-start border-4 border-secondary">
                    <h5 class="fw-bold mb-3 text-primary">HORÁRIO DE ATENDIMENTO</h5>
                    <p class="mb-0 text-muted"><i class="far fa-clock me-2"></i> Segunda a Sexta: 08:00 às 18:00</p>
                </div>

                <div class="mt-4 p-4 bg-primary-hansen text-white rounded-3 shadow-sm">
                    <h5 class="fw-bold mb-3">PARCERIA COM ESCOLAS</h5>
                    <p class="small mb-0">Oferecemos programas pedagógicos personalizados para instituições públicas e privadas. Entre em contato para conhecer nossas soluções educacionais.</p>
                </div>
            </div>
        </div>
    </div>
</section>