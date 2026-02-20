<?php

namespace App\Controllers;

use App\Core\Database\Connection;

class ParentPanelController
{
    protected $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    private function requireParent()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
        if (($_SESSION['user_role'] ?? '') !== 'parent') {
            header('Location: /login');
            exit;
        }
    }

    // GET /minha-area
    public function dashboard()
    {
        $this->requireParent();
        $parentId = $_SESSION['user_id'];

        // Get linked children
        $stmt = $this->db->prepare("
            SELECT u.id, u.name, u.email, u.avatar, u.last_login,
                   ps.relationship
            FROM parent_student ps
            JOIN users u ON ps.student_user_id = u.id
            WHERE ps.parent_id = ?
            ORDER BY u.name
        ");
        $stmt->execute([$parentId]);
        $children = $stmt->fetchAll();

        // For each child, get their enrollments and progress
        foreach ($children as &$child) {
            $stmt2 = $this->db->prepare("
                SELECT e.id, e.status, e.overall_progress_percentage, e.is_course_completed,
                       e.enrollment_date, e.last_activity_at, e.total_watch_time,
                       c.title AS course_title, c.slug AS course_slug
                FROM enrollments e
                JOIN courses c ON e.course_id = c.id
                WHERE e.user_id = ?
                ORDER BY e.enrollment_date DESC
            ");
            $stmt2->execute([$child['id']]);
            $child['enrollments'] = $stmt2->fetchAll();

            // Quiz performance
            $stmt3 = $this->db->prepare("
                SELECT qa.score, qa.passed, qa.started_at,
                       q.title AS quiz_title, q.passing_score,
                       c.title AS course_title
                FROM quiz_attempts qa
                JOIN quizzes q ON qa.quiz_id = q.id
                JOIN sections s ON q.section_id = s.id
                JOIN courses c ON s.course_id = c.id
                WHERE qa.user_id = ?
                ORDER BY qa.started_at DESC
                LIMIT 10
            ");
            $stmt3->execute([$child['id']]);
            $child['quiz_attempts'] = $stmt3->fetchAll();
        }

        $this->render('parent/dashboard', [
            'children' => $children,
        ]);
    }

    // GET /minha-area/filho/{studentId}
    public function childDetail($studentId)
    {
        $this->requireParent();
        $parentId = $_SESSION['user_id'];

        // Verify this student is linked to parent
        $stmt = $this->db->prepare("SELECT * FROM parent_student WHERE parent_id = ? AND student_user_id = ?");
        $stmt->execute([$parentId, $studentId]);
        if (!$stmt->fetch()) {
            http_response_code(403);
            echo "Acesso negado.";
            return;
        }

        $stmt = $this->db->prepare("SELECT id, name, email, avatar, last_login, created_at FROM users WHERE id = ?");
        $stmt->execute([$studentId]);
        $child = $stmt->fetch();

        // Enrollments with progress
        $stmt = $this->db->prepare("
            SELECT e.*, c.title AS course_title, c.slug, c.duration_hours
            FROM enrollments e
            JOIN courses c ON e.course_id = c.id
            WHERE e.user_id = ?
            ORDER BY e.enrollment_date DESC
        ");
        $stmt->execute([$studentId]);
        $enrollments = $stmt->fetchAll();

        // All quiz attempts
        $stmt = $this->db->prepare("
            SELECT qa.*, q.title AS quiz_title, q.passing_score, c.title AS course_title
            FROM quiz_attempts qa
            JOIN quizzes q ON qa.quiz_id = q.id
            JOIN sections s ON q.section_id = s.id
            JOIN courses c ON s.course_id = c.id
            WHERE qa.user_id = ?
            ORDER BY qa.started_at DESC
        ");
        $stmt->execute([$studentId]);
        $quizAttempts = $stmt->fetchAll();

        // Observations (pedagogical notes)
        $observations = [];
        try {
            $stmt = $this->db->prepare("
                SELECT o.title, o.type, o.content, o.observed_at, o.created_at,
                       u.name AS teacher_name
                FROM observations o
                JOIN users u ON o.user_id = u.id
                JOIN students st ON o.student_id = st.id
                WHERE st.user_id = ? OR st.id = ?
                ORDER BY o.created_at DESC
                LIMIT 20
            ");
            $stmt->execute([$studentId, $studentId]);
            $observations = $stmt->fetchAll();
        } catch (\Exception $e) {}

        $this->render('parent/child-detail', [
            'child'        => $child,
            'enrollments'  => $enrollments,
            'quizAttempts' => $quizAttempts,
            'observations' => $observations,
        ]);
    }

    // GET /minha-area/perfil
    public function profile()
    {
        $this->requireParent();
        $stmt = $this->db->prepare("SELECT id, name, email, phone FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
        $this->render('parent/profile', ['user' => $user]);
    }

    // POST /minha-area/perfil
    public function updateProfile()
    {
        $this->requireParent();
        $name  = trim($_POST['name'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        if ($name) {
            $this->db->prepare("UPDATE users SET name = ?, phone = ?, updated_at = NOW() WHERE id = ?")
                ->execute([$name, $phone, $_SESSION['user_id']]);
            $_SESSION['user_name'] = $name;
            $_SESSION['success_message'] = 'Perfil atualizado!';
        }
        header('Location: /minha-area/perfil');
        exit;
    }

    protected function render($view, $data = [])
    {
        extract($data);
        $viewFile = __DIR__ . "/../../views/{$view}.php";
        ob_start();
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            echo "<p>View nao encontrada: {$view}</p>";
        }
        $content = ob_get_clean();
        include __DIR__ . "/../../views/layouts/parent.php";
    }
}
