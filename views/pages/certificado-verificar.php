<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-body p-5 text-center">
                        <h3 class="text-primary fw-bold mb-4">VERIFICAÇÃO DE CERTIFICADO</h3>

                        <?php if ($certificate): ?>
                            <div class="mb-4">
                                <i class="fas fa-check-circle fa-4x text-success"></i>
                            </div>
                            <h5 class="text-success fw-bold mb-3">Certificado Válido</h5>
                            <hr>
                            <p class="mb-1"><strong>Aluno:</strong> <?php echo htmlspecialchars($certificate['student_name']); ?></p>
                            <p class="mb-1"><strong>Curso:</strong> <?php echo htmlspecialchars($certificate['course_title']); ?></p>
                            <p class="mb-1"><strong>Carga Horária:</strong> <?php echo $certificate['duration_hours'] ?? 0; ?> horas</p>
                            <p class="mb-1"><strong>Conclusão:</strong> <?php echo $certificate['course_completed_at'] ? date('d/m/Y', strtotime($certificate['course_completed_at'])) : 'N/A'; ?></p>
                            <p class="mb-0"><strong>Código:</strong> <?php echo htmlspecialchars($code); ?></p>
                        <?php else: ?>
                            <div class="mb-4">
                                <i class="fas fa-times-circle fa-4x text-danger"></i>
                            </div>
                            <h5 class="text-danger fw-bold mb-3">Certificado Não Encontrado</h5>
                            <p class="text-muted">O código <strong><?php echo htmlspecialchars($code); ?></strong> não corresponde a nenhum certificado válido.</p>
                        <?php endif; ?>

                        <hr>
                        <a href="/" class="btn btn-outline-primary">Voltar ao Site</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
