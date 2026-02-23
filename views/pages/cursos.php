<header class="bg-primary-hansen text-white py-5 text-center">
    <div class="container py-4">
        <h6 class="text-secondary fw-bold text-uppercase">Formação Profissional</h6>
        <h1 class="display-4 fw-bold">INOVAÇÃO, CIÊNCIA E HUMANISMO</h1>
        <p class="lead">Descubra novos horizontes com nossos cursos transformadores</p>
    </div>
</header>

<section class="py-5">
    <div class="container py-5">
        <?php if (empty($courses)): ?>
            <div class="text-center py-5">
                <i class="fas fa-book-open fa-4x text-muted mb-3"></i>
                <h3 class="text-muted">Em breve, novos cursos disponíveis!</h3>
                <p class="text-muted">Estamos preparando conteúdos incríveis para você.</p>
                <a href="/contato" class="btn btn-hansen mt-3">QUERO SER AVISADO</a>
            </div>
        <?php else: ?>
            <div class="row g-5">
                <?php foreach ($courses as $course): ?>
                <div class="col-md-6 col-lg-4">
                    <div class="card h-100 border-0 shadow-lg overflow-hidden">
                        <?php if (!empty($course['cover_image'])): ?>
                            <img src="<?php echo htmlspecialchars($course['cover_image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($course['title']); ?>" style="height: 250px; object-fit: cover;">
                        <?php else: ?>
                            <div class="d-flex align-items-center justify-content-center" style="height: 250px; background: linear-gradient(135deg, var(--primary-color), var(--dark-teal));">
                                <i class="fas fa-graduation-cap fa-4x text-white opacity-50"></i>
                            </div>
                        <?php endif; ?>
                        <div class="card-body text-center p-4">
                            <h4 class="card-title text-primary fw-bold mb-3"><?php echo htmlspecialchars($course['title']); ?></h4>
                            <?php if (!empty($course['short_description'])): ?>
                                <p class="card-text text-muted mb-3"><?php echo htmlspecialchars($course['short_description']); ?></p>
                            <?php elseif (!empty($course['description'])): ?>
                                <p class="card-text text-muted mb-3"><?php echo htmlspecialchars(mb_substr($course['description'], 0, 150)); ?>...</p>
                            <?php endif; ?>

                            <div class="mb-3">
                                <?php if (!empty($course['level'])): ?>
                                    <span class="badge bg-light text-dark border me-1"><i class="fas fa-signal me-1"></i><?php echo ucfirst($course['level']); ?></span>
                                <?php endif; ?>
                                <?php if (!empty($course['duration_hours'])): ?>
                                    <span class="badge bg-light text-dark border"><i class="fas fa-clock me-1"></i><?php echo $course['duration_hours']; ?>h</span>
                                <?php endif; ?>
                            </div>

                            <?php if (!isset($_SESSION['user_id'])): ?>
                                <?php if ($course['is_free']): ?>
                                    <span class="badge bg-success fs-6 mb-3">GRATUITO</span>
                                <?php elseif (!empty($course['price']) && $course['price'] > 0): ?>
                                    <p class="text-green fw-bold fs-5 mb-3">R$ <?php echo number_format($course['price'], 2, ',', '.'); ?></p>
                                <?php endif; ?>
                            <?php endif; ?>

                            <a href="/curso/<?php echo htmlspecialchars($course['slug']); ?>" class="btn btn-hansen mt-2 px-4">VER CURSO</a>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- Depoimento -->
<section class="py-5 bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <div class="p-5 bg-white rounded shadow-sm">
                    <i class="fas fa-quote-left fa-2x text-secondary mb-4"></i>
                    <p class="lead text-muted fst-italic mb-4">"A metodologia transformou completamente nossa compreensão sobre o desenvolvimento infantil. Conteúdos claros e práticos que realmente fazem diferença no dia a dia escolar."</p>
                    <h5 class="fw-bold text-primary mb-1">Beatriz Oliveira Fonseca</h5>
                    <p class="text-muted small">Coordenadora Pedagógica</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Diferenciais -->
<section class="py-5">
    <div class="container py-5">
        <div class="row align-items-center">
            <div class="col-md-6 mb-4 mb-md-0">
                <h2 class="display-5 fw-bold text-primary mb-4">METODOLOGIA COMPLETA</h2>
                <p class="lead">Nossos cursos combinam conteúdos pedagógicos fundamentados com técnicas práticas para a vida.</p>
                <ul class="list-unstyled mt-4">
                    <li class="mb-3"><i class="fas fa-check-circle text-primary me-2"></i> Baseados em Neurociência e Pedagogia Humanista</li>
                    <li class="mb-3"><i class="fas fa-check-circle text-primary me-2"></i> Certificação reconhecida nacionalmente</li>
                    <li class="mb-3"><i class="fas fa-check-circle text-primary me-2"></i> Material didático completo incluído</li>
                    <li class="mb-3"><i class="fas fa-check-circle text-primary me-2"></i> Livros complementares disponíveis</li>
                </ul>
                <a href="/livros" class="btn btn-outline-primary mt-3 px-4">CONHEÇA NOSSOS LIVROS</a>
            </div>
            <div class="col-md-6 text-center">
                <img src="/assets/images/downloaded/h1.webp" alt="Cursos Hansen Educacional" class="img-fluid rounded-3 shadow-lg">
            </div>
        </div>
    </div>
</section>

<!-- CTA Final -->
<section class="py-5 bg-primary-hansen text-white">
    <div class="container text-center py-4">
        <h2 class="fw-bold mb-4">TRANSFORME SUA PRÁTICA EDUCACIONAL!</h2>
        <p class="lead mb-5">Entre em contato e conheça mais sobre nossos cursos presenciais e online.</p>
        <a href="/contato" class="btn btn-hansen btn-lg px-5">QUERO MAIS INFORMAÇÕES</a>
    </div>
</section>

<style>
    .card { transition: transform 0.3s, box-shadow 0.3s; }
    .card:hover { transform: translateY(-8px); box-shadow: 0 15px 40px rgba(0, 126, 102, 0.25) !important; }
</style>
