<?php

namespace App\Controllers;

use App\Core\Database\Connection;

class CourseMessageController
{
    protected $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    private function requireAuth()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    }

    // GET /curso/{slug}/perguntas
    // List all questions for a course (student view - sees own questions + answers)
    public function index($slug)
    {
        $this->requireAuth();

        $stmt = $this->db->prepare("SELECT id, title, slug FROM courses WHERE slug = ?");
        $stmt->execute([$slug]);
        $course = $stmt->fetch();
        if (!$course) { http_response_code(404); return; }

        // Check enrollment
        $role = $_SESSION['user_role'] ?? 'student';
        $isStaff = in_array($role, ['admin', 'professor', 'coordenador']);

        if (!$isStaff) {
            $stmt = $this->db->prepare("SELECT id FROM enrollments WHERE user_id = ? AND course_id = ? AND status = 'active'");
            $stmt->execute([$_SESSION['user_id'], $course['id']]);
            if (!$stmt->fetch()) {
                header('Location: /curso/' . $slug);
                exit;
            }
        }

        // Fetch root messages (parent_id IS NULL)
        if ($isStaff) {
            // Staff sees all questions
            $stmt = $this->db->prepare("
                SELECT m.*, u.name AS author_name, u.role AS author_role,
                       l.title AS lesson_title,
                       (SELECT COUNT(*) FROM course_messages r WHERE r.parent_id = m.id) AS reply_count
                FROM course_messages m
                JOIN users u ON m.user_id = u.id
                LEFT JOIN lessons l ON m.lesson_id = l.id
                WHERE m.course_id = ? AND m.parent_id IS NULL
                ORDER BY m.is_answered ASC, m.created_at DESC
            ");
            $stmt->execute([$course['id']]);
        } else {
            // Student sees own questions + questions with answers
            $stmt = $this->db->prepare("
                SELECT m.*, u.name AS author_name, u.role AS author_role,
                       l.title AS lesson_title,
                       (SELECT COUNT(*) FROM course_messages r WHERE r.parent_id = m.id) AS reply_count
                FROM course_messages m
                JOIN users u ON m.user_id = u.id
                LEFT JOIN lessons l ON m.lesson_id = l.id
                WHERE m.course_id = ? AND m.parent_id IS NULL
                  AND (m.user_id = ? OR m.is_answered = 1)
                ORDER BY m.created_at DESC
            ");
            $stmt->execute([$course['id'], $_SESSION['user_id']]);
        }
        $questions = $stmt->fetchAll();

        $this->render('curso-perguntas', [
            'title'     => 'Perguntas | ' . $course['title'],
            'course'    => $course,
            'questions' => $questions,
            'isStaff'   => $isStaff,
        ]);
    }

    // GET /curso/{slug}/pergunta/{messageId}
    // Thread view (question + all replies)
    public function thread($slug, $messageId)
    {
        $this->requireAuth();

        $stmt = $this->db->prepare("SELECT id, title, slug FROM courses WHERE slug = ?");
        $stmt->execute([$slug]);
        $course = $stmt->fetch();
        if (!$course) { http_response_code(404); return; }

        $role = $_SESSION['user_role'] ?? 'student';
        $isStaff = in_array($role, ['admin', 'professor', 'coordenador']);

        // Get root message
        $stmt = $this->db->prepare("
            SELECT m.*, u.name AS author_name, u.role AS author_role, l.title AS lesson_title
            FROM course_messages m
            JOIN users u ON m.user_id = u.id
            LEFT JOIN lessons l ON m.lesson_id = l.id
            WHERE m.id = ? AND m.course_id = ? AND m.parent_id IS NULL
        ");
        $stmt->execute([$messageId, $course['id']]);
        $question = $stmt->fetch();
        if (!$question) { http_response_code(404); return; }

        // Get replies
        $stmt = $this->db->prepare("
            SELECT m.*, u.name AS author_name, u.role AS author_role
            FROM course_messages m
            JOIN users u ON m.user_id = u.id
            WHERE m.parent_id = ?
            ORDER BY m.created_at ASC
        ");
        $stmt->execute([$messageId]);
        $replies = $stmt->fetchAll();

        // Mark as read if staff
        if ($isStaff && !$question['is_read']) {
            $this->db->prepare("UPDATE course_messages SET is_read = 1 WHERE id = ?")->execute([$messageId]);
        }

        $this->render('curso-pergunta-thread', [
            'title'    => 'Pergunta | ' . $course['title'],
            'course'   => $course,
            'question' => $question,
            'replies'  => $replies,
            'isStaff'  => $isStaff,
        ]);
    }

    // POST /curso/{slug}/perguntas/nova
    public function ask($slug)
    {
        $this->requireAuth();

        $stmt = $this->db->prepare("SELECT id FROM courses WHERE slug = ?");
        $stmt->execute([$slug]);
        $course = $stmt->fetch();
        if (!$course) { http_response_code(404); return; }

        $message  = trim($_POST['message'] ?? '');
        $lessonId = !empty($_POST['lesson_id']) ? (int)$_POST['lesson_id'] : null;

        if (strlen($message) < 5) {
            $_SESSION['error'] = 'Mensagem muito curta.';
            header('Location: /curso/' . $slug . '/perguntas');
            exit;
        }

        $stmt = $this->db->prepare("
            INSERT INTO course_messages (course_id, lesson_id, user_id, message)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$course['id'], $lessonId, $_SESSION['user_id'], $message]);

        $_SESSION['success_message'] = 'Sua pergunta foi enviada! O professor irá responder em breve.';
        header('Location: /curso/' . $slug . '/perguntas');
        exit;
    }

    // POST /curso/{slug}/pergunta/{messageId}/responder
    public function reply($slug, $messageId)
    {
        $this->requireAuth();

        $stmt = $this->db->prepare("SELECT id FROM courses WHERE slug = ?");
        $stmt->execute([$slug]);
        $course = $stmt->fetch();
        if (!$course) { http_response_code(404); return; }

        $message = trim($_POST['message'] ?? '');
        if (strlen($message) < 2) {
            header('Location: /curso/' . $slug . '/pergunta/' . $messageId);
            exit;
        }

        $stmt = $this->db->prepare("
            INSERT INTO course_messages (course_id, parent_id, user_id, message)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$course['id'], $messageId, $_SESSION['user_id'], $message]);

        // Mark original question as answered if staff replied
        $role = $_SESSION['user_role'] ?? 'student';
        if (in_array($role, ['admin', 'professor', 'coordenador'])) {
            $this->db->prepare("UPDATE course_messages SET is_answered = 1, is_read = 1 WHERE id = ?")
                ->execute([$messageId]);
        }

        header('Location: /curso/' . $slug . '/pergunta/' . $messageId);
        exit;
    }

    protected function render($view, $data = [])
    {
        extract($data);
        $viewFile = __DIR__ . "/../../views/pages/{$view}.php";
        ob_start();
        if (file_exists($viewFile)) { include $viewFile; } else { echo "<p>View não encontrada.</p>"; }
        $content = ob_get_clean();
        include __DIR__ . "/../../views/layouts/public.php";
    }
}
