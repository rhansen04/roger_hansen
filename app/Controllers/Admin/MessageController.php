<?php

namespace App\Controllers\Admin;

use App\Core\Database\Connection;

class MessageController
{
    protected $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    public function index()
    {
        $stmt = $this->db->query("
            SELECT m.id, m.message, m.is_read, m.is_answered, m.created_at,
                   u.name AS author_name,
                   c.title AS course_title, c.slug AS course_slug,
                   l.title AS lesson_title
            FROM course_messages m
            JOIN users u ON m.user_id = u.id
            JOIN courses c ON m.course_id = c.id
            LEFT JOIN lessons l ON m.lesson_id = l.id
            WHERE m.parent_id IS NULL
            ORDER BY m.is_answered ASC, m.is_read ASC, m.created_at DESC
        ");
        $questions = $stmt->fetchAll();

        $unreadCount = (int)$this->db->query("SELECT COUNT(*) FROM course_messages WHERE parent_id IS NULL AND is_read = 0")->fetchColumn();

        $this->render('messages/index', ['questions' => $questions, 'unreadCount' => $unreadCount]);
    }

    protected function render($view, $data = [])
    {
        extract($data);
        $viewFile = __DIR__ . "/../../../views/admin/{$view}.php";
        ob_start();
        if (file_exists($viewFile)) { include $viewFile; } else { echo "<p>View não encontrada: $view</p>"; }
        $content = ob_get_clean();
        include __DIR__ . "/../../../views/layouts/admin.php";
    }
}
