<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h2 class="fw-bold mb-0" style="color:var(--primary-color)"><i class="fas fa-paperclip me-2"></i>Materiais de Apoio</h2>
        <small class="text-muted">Curso: <strong><?= htmlspecialchars($course['title']) ?></strong></small>
    </div>
    <div>
        <a href="/admin/courses/<?= $course['id'] ?>" class="btn btn-outline-secondary btn-sm me-2"><i class="fas fa-arrow-left me-1"></i>Voltar ao Curso</a>
        <a href="/admin/courses/<?= $course['id'] ?>/materials/create" class="btn btn-sm" style="background:var(--primary-color);color:white"><i class="fas fa-plus me-1"></i>Novo Material</a>
    </div>
</div>

<?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show"><i class="fas fa-check-circle me-2"></i><?= $_SESSION['success_message'] ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php unset($_SESSION['success_message']); ?>
<?php endif; ?>
<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show"><i class="fas fa-exclamation-circle me-2"></i><?= $_SESSION['error'] ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<?php if (empty($materials)): ?>
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="fas fa-folder-open fa-4x text-muted mb-3"></i>
            <h5 class="text-muted">Nenhum material cadastrado ainda.</h5>
            <a href="/admin/courses/<?= $course['id'] ?>/materials/create" class="btn btn-sm mt-2" style="background:var(--primary-color);color:white">Adicionar primeiro material</a>
        </div>
    </div>
<?php else: ?>
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr><th>Tipo</th><th>Título</th><th>Seção</th><th>Tamanho</th><th>Enviado por</th><th>Data</th><th>Ações</th></tr>
            </thead>
            <tbody>
            <?php foreach ($materials as $m): ?>
                <?php
                $iconMap = ['pdf'=>'fas fa-file-pdf text-danger','excel'=>'fas fa-file-excel text-success','image'=>'fas fa-file-image text-info','video'=>'fas fa-file-video text-warning','other'=>'fas fa-file text-muted'];
                $icon = $iconMap[$m['file_type']] ?? 'fas fa-file text-muted';
                $sizeKb = $m['file_size'] > 0 ? round($m['file_size']/1024) . ' KB' : '-';
                ?>
                <tr>
                    <td><i class="<?= $icon ?> fa-lg"></i></td>
                    <td>
                        <strong><?= htmlspecialchars($m['title']) ?></strong>
                        <?php if ($m['description']): ?><br><small class="text-muted"><?= htmlspecialchars($m['description']) ?></small><?php endif; ?>
                        <br><small class="text-muted fst-italic"><?= htmlspecialchars($m['file_name']) ?></small>
                    </td>
                    <td class="small text-muted"><?= $m['section_title'] ? htmlspecialchars($m['section_title']) : '<span class="text-muted">Geral</span>' ?></td>
                    <td class="small text-muted"><?= $sizeKb ?></td>
                    <td class="small text-muted"><?= htmlspecialchars($m['created_by_name']) ?></td>
                    <td class="small text-muted"><?= date('d/m/Y', strtotime($m['created_at'])) ?></td>
                    <td>
                        <a href="/admin/materials/<?= $m['id'] ?>/download" class="btn btn-outline-primary btn-sm" title="Download"><i class="fas fa-download"></i></a>
                        <form method="POST" action="/admin/materials/<?= $m['id'] ?>/delete" class="d-inline" onsubmit="return confirm('Remover este material?')">
                            <button type="submit" class="btn btn-outline-danger btn-sm" title="Excluir"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        </div>
    </div>
</div>
<?php endif; ?>
