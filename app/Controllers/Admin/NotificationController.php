<?php

namespace App\Controllers\Admin;

use App\Models\Notification;
use App\Core\Database\Connection;

class NotificationController
{
    protected $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    /**
     * GET /admin/notifications
     * Full page list of all notifications
     */
    public function index()
    {
        $userId = $_SESSION['user_id'];
        $model = new Notification();
        $notifications = $model->findByUser($userId, 100);
        $unreadCount = $model->countUnread($userId);

        // Enrich notifications with reference URLs
        foreach ($notifications as &$n) {
            $n['url'] = $model->getReferenceUrl($n);
        }

        return $this->render('notifications/index', [
            'pageTitle' => 'Notificacoes',
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ]);
    }

    /**
     * POST /admin/notifications/{id}/read
     * Mark single notification as read (AJAX)
     */
    public function markRead($id)
    {
        $userId = $_SESSION['user_id'];
        $model = new Notification();
        $notification = $model->find($id);

        // Verify ownership
        if (!$notification || $notification['user_id'] != $userId) {
            http_response_code(403);
            echo json_encode(['success' => false, 'error' => 'Acesso negado']);
            return;
        }

        $model->markAsRead($id);

        if ($this->isAjax()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'unread' => $model->countUnread($userId)]);
            return;
        }

        header('Location: /admin/notifications');
        exit;
    }

    /**
     * POST /admin/notifications/mark-all-read
     * Mark all as read
     */
    public function markAllRead()
    {
        $userId = $_SESSION['user_id'];
        $model = new Notification();
        $model->markAllAsRead($userId);

        if ($this->isAjax()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'unread' => 0]);
            return;
        }

        $_SESSION['success_message'] = 'Todas as notificacoes foram marcadas como lidas.';
        header('Location: /admin/notifications');
        exit;
    }

    /**
     * GET /admin/notifications/dropdown
     * AJAX endpoint returning JSON for the bell dropdown
     */
    public function dropdown()
    {
        $userId = $_SESSION['user_id'];
        $model = new Notification();
        $notifications = $model->findByUser($userId, 10);
        $unreadCount = $model->countUnread($userId);

        // Enrich with URLs and time ago
        foreach ($notifications as &$n) {
            $n['url'] = $model->getReferenceUrl($n);
            $n['time_ago'] = $this->timeAgo($n['created_at']);
            $n['icon'] = $this->getTypeIcon($n['type']);
        }

        header('Content-Type: application/json');
        echo json_encode([
            'success' => true,
            'notifications' => $notifications,
            'unread' => $unreadCount,
        ]);
    }

    /**
     * Get icon class for notification type
     */
    private function getTypeIcon($type)
    {
        $icons = [
            'revision_request' => 'fas fa-exclamation-circle text-warning',
            'finalized' => 'fas fa-check-circle text-success',
            'reopened' => 'fas fa-redo text-info',
            'approved' => 'fas fa-thumbs-up text-success',
            'rejected' => 'fas fa-thumbs-down text-danger',
            'comment' => 'fas fa-comment text-primary',
            'reminder' => 'fas fa-bell text-warning',
        ];

        return $icons[$type] ?? 'fas fa-bell text-secondary';
    }

    /**
     * Convert timestamp to "time ago" string
     */
    private function timeAgo($datetime)
    {
        $now = new \DateTime();
        $past = new \DateTime($datetime);
        $diff = $now->diff($past);

        if ($diff->y > 0) return $diff->y . ' ano' . ($diff->y > 1 ? 's' : '') . ' atras';
        if ($diff->m > 0) return $diff->m . ' mes' . ($diff->m > 1 ? 'es' : '') . ' atras';
        if ($diff->d > 0) return $diff->d . ' dia' . ($diff->d > 1 ? 's' : '') . ' atras';
        if ($diff->h > 0) return $diff->h . ' hora' . ($diff->h > 1 ? 's' : '') . ' atras';
        if ($diff->i > 0) return $diff->i . ' minuto' . ($diff->i > 1 ? 's' : '') . ' atras';
        return 'agora mesmo';
    }

    /**
     * Check if request is AJAX
     */
    private function isAjax()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    protected function render($view, $data = [])
    {
        extract($data);
        $viewFile = __DIR__ . "/../../../views/admin/{$view}.php";
        ob_start();
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            echo "<h2>Pagina {$view} em construcao</h2>";
        }
        $content = ob_get_clean();
        include __DIR__ . "/../../../views/layouts/admin.php";
    }
}
