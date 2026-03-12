<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/admin/dashboard">Dashboard</a></li>
        <li class="breadcrumb-item active">Notificacoes</li>
    </ol>
</nav>

<?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($_SESSION['success_message']) ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['success_message']); ?>
<?php endif; ?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="fw-bold mb-0">
            <i class="fas fa-bell me-2 text-primary"></i>Notificacoes
            <?php if ($unreadCount > 0): ?>
                <span class="badge bg-danger ms-2"><?= $unreadCount ?> nao lida<?= $unreadCount > 1 ? 's' : '' ?></span>
            <?php endif; ?>
        </h4>
    </div>
    <?php if ($unreadCount > 0): ?>
        <form action="/admin/notifications/mark-all-read" method="POST">
            <button type="submit" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-check-double me-1"></i>Marcar todas como lidas
            </button>
        </form>
    <?php endif; ?>
</div>

<?php if (empty($notifications)): ?>
    <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5">
            <i class="fas fa-bell-slash fa-4x text-muted mb-3"></i>
            <h5 class="text-muted">Nenhuma notificacao</h5>
            <p class="text-muted">Voce sera notificado quando houver novas atividades.</p>
        </div>
    </div>
<?php else: ?>
    <div class="card border-0 shadow-sm">
        <div class="list-group list-group-flush">
            <?php foreach ($notifications as $notification): ?>
                <?php
                    $isUnread = !$notification['is_read'];
                    $typeIcons = [
                        'revision_request' => 'fas fa-exclamation-circle text-warning',
                        'finalized' => 'fas fa-check-circle text-success',
                        'reopened' => 'fas fa-redo text-info',
                        'approved' => 'fas fa-thumbs-up text-success',
                        'rejected' => 'fas fa-thumbs-down text-danger',
                        'comment' => 'fas fa-comment text-primary',
                        'reminder' => 'fas fa-bell text-warning',
                    ];
                    $icon = $typeIcons[$notification['type']] ?? 'fas fa-bell text-secondary';

                    // Time ago
                    $now = new DateTime();
                    $past = new DateTime($notification['created_at']);
                    $diff = $now->diff($past);
                    if ($diff->y > 0) $timeAgo = $diff->y . ' ano' . ($diff->y > 1 ? 's' : '') . ' atras';
                    elseif ($diff->m > 0) $timeAgo = $diff->m . ' mes' . ($diff->m > 1 ? 'es' : '') . ' atras';
                    elseif ($diff->d > 0) $timeAgo = $diff->d . ' dia' . ($diff->d > 1 ? 's' : '') . ' atras';
                    elseif ($diff->h > 0) $timeAgo = $diff->h . ' hora' . ($diff->h > 1 ? 's' : '') . ' atras';
                    elseif ($diff->i > 0) $timeAgo = $diff->i . ' minuto' . ($diff->i > 1 ? 's' : '') . ' atras';
                    else $timeAgo = 'agora mesmo';
                ?>
                <div class="list-group-item list-group-item-action d-flex align-items-start gap-3 <?= $isUnread ? 'bg-light' : '' ?>" style="border-left: 3px solid <?= $isUnread ? '#007e66' : 'transparent' ?>;">
                    <div class="flex-shrink-0 mt-1">
                        <i class="<?= $icon ?>" style="font-size: 1.2rem;"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="d-flex justify-content-between align-items-start">
                            <a href="<?= htmlspecialchars($notification['url']) ?>"
                               class="text-decoration-none <?= $isUnread ? 'fw-bold text-dark' : 'text-muted' ?>"
                               onclick="markNotificationRead(<?= $notification['id'] ?>)">
                                <?= htmlspecialchars($notification['title']) ?>
                            </a>
                            <small class="text-muted ms-3 text-nowrap"><?= $timeAgo ?></small>
                        </div>
                        <?php if (!empty($notification['message'])): ?>
                            <p class="mb-0 small text-muted mt-1"><?= htmlspecialchars($notification['message']) ?></p>
                        <?php endif; ?>
                        <?php if (!empty($notification['reference_type'])): ?>
                            <span class="badge bg-light text-dark border mt-1">
                                <?= htmlspecialchars(str_replace('_', ' ', ucfirst($notification['reference_type']))) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    <?php if ($isUnread): ?>
                        <div class="flex-shrink-0">
                            <button class="btn btn-sm btn-outline-secondary" onclick="markNotificationRead(<?= $notification['id'] ?>)" title="Marcar como lida">
                                <i class="fas fa-check"></i>
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>

<script>
function markNotificationRead(id) {
    fetch('/admin/notifications/' + id + '/read', {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Content-Type': 'application/json'
        }
    }).then(r => r.json()).then(data => {
        if (data.success) {
            // Update badge in header
            const badge = document.getElementById('notif-badge');
            if (badge && data.unread > 0) {
                badge.textContent = data.unread;
            } else if (badge) {
                badge.style.display = 'none';
            }
        }
    }).catch(() => {});
}
</script>
