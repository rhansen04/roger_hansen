<?php

namespace App\Controllers\Admin;

use App\Core\Database\Connection;

class ReportsController
{
    protected $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    /**
     * GET /admin/reports
     */
    public function index()
    {
        // Totais gerais
        $stats = [];
        $stats['total_users'] = (int)$this->db->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $stats['total_students'] = (int)$this->db->query("SELECT COUNT(*) FROM users WHERE role = 'student'")->fetchColumn();
        $stats['total_courses'] = (int)$this->db->query("SELECT COUNT(*) FROM courses WHERE is_active = 1")->fetchColumn();
        $stats['total_enrollments'] = (int)$this->db->query("SELECT COUNT(*) FROM enrollments")->fetchColumn();
        $stats['active_enrollments'] = (int)$this->db->query("SELECT COUNT(*) FROM enrollments WHERE status = 'active'")->fetchColumn();
        $stats['completed_courses'] = (int)$this->db->query("SELECT COUNT(*) FROM enrollments WHERE is_course_completed = 1")->fetchColumn();

        // Progresso médio
        $avgProgress = $this->db->query("SELECT AVG(overall_progress_percentage) FROM enrollments WHERE status = 'active'")->fetchColumn();
        $stats['avg_progress'] = round($avgProgress ?? 0, 1);

        // Matrículas por curso
        $stmt = $this->db->query("
            SELECT c.title, c.id,
                   COUNT(e.id) as total_enrollments,
                   SUM(CASE WHEN e.status = 'active' THEN 1 ELSE 0 END) as active_count,
                   SUM(CASE WHEN e.is_course_completed = 1 THEN 1 ELSE 0 END) as completed_count,
                   ROUND(AVG(e.overall_progress_percentage), 1) as avg_progress
            FROM courses c
            LEFT JOIN enrollments e ON c.id = e.course_id
            WHERE c.is_active = 1
            GROUP BY c.id
            ORDER BY total_enrollments DESC
        ");
        $courseStats = $stmt->fetchAll();

        // Quiz stats
        $stmt = $this->db->query("
            SELECT q.title as quiz_title, c.title as course_title,
                   COUNT(qa.id) as total_attempts,
                   ROUND(AVG(qa.score), 1) as avg_score,
                   SUM(CASE WHEN qa.passed = 1 THEN 1 ELSE 0 END) as passed_count,
                   ROUND(SUM(CASE WHEN qa.passed = 1 THEN 1 ELSE 0 END) * 100.0 / NULLIF(COUNT(qa.id), 0), 1) as pass_rate
            FROM quizzes q
            JOIN sections s ON q.section_id = s.id
            JOIN courses c ON s.course_id = c.id
            LEFT JOIN quiz_attempts qa ON q.id = qa.quiz_id
            GROUP BY q.id
            HAVING total_attempts > 0
            ORDER BY total_attempts DESC
        ");
        $quizStats = $stmt->fetchAll();

        // Matrículas recentes (últimos 30 dias)
        $stmt = $this->db->query("
            SELECT DATE(e.enrollment_date) as dt, COUNT(*) as cnt
            FROM enrollments e
            WHERE e.enrollment_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
            GROUP BY DATE(e.enrollment_date)
            ORDER BY dt
        ");
        $recentEnrollments = $stmt->fetchAll();

        return $this->render('reports/index', [
            'stats' => $stats,
            'courseStats' => $courseStats,
            'quizStats' => $quizStats,
            'recentEnrollments' => $recentEnrollments,
        ]);
    }

    public function lowScores()
    {
        $stmt = $this->db->query("
            SELECT
                u.id AS user_id,
                u.name AS student_name,
                c.id AS course_id,
                c.title AS course_title,
                c.slug AS course_slug,
                q.id AS quiz_id,
                q.title AS quiz_title,
                q.passing_score,
                q.attempts_allowed,
                e.id AS enrollment_id,
                MAX(qa.score) AS best_score,
                COUNT(qa.id) AS attempt_count
            FROM quiz_attempts qa
            JOIN users u ON qa.user_id = u.id
            JOIN quizzes q ON qa.quiz_id = q.id
            JOIN sections s ON q.section_id = s.id
            JOIN courses c ON s.course_id = c.id
            JOIN enrollments e ON e.user_id = u.id AND e.course_id = c.id
            WHERE qa.passed = 0
            GROUP BY u.id, q.id, e.id
            HAVING MAX(qa.score) < q.passing_score
            ORDER BY best_score ASC, u.name ASC
        ");
        $lowScores = $stmt->fetchAll();

        return $this->render('reports/low-scores', ['lowScores' => $lowScores]);
    }

    public function resetAttempts($quizId)
    {
        $userId       = (int)($_POST['user_id'] ?? 0);
        $enrollmentId = (int)($_POST['enrollment_id'] ?? 0);

        if (!$userId || !$quizId) {
            header('Location: /admin/reports/low-scores');
            exit;
        }

        // Delete all attempts for this user+quiz so they can retry
        $stmt = $this->db->prepare("DELETE FROM quiz_attempts WHERE quiz_id = ? AND user_id = ?");
        $stmt->execute([$quizId, $userId]);

        $_SESSION['success_message'] = 'Tentativas resetadas. O aluno pode refazer o quiz agora.';
        header('Location: /admin/reports/low-scores');
        exit;
    }

    protected function render($view, $data = [])
    {
        extract($data);
        $viewFile = __DIR__ . "/../../../views/admin/{$view}.php";
        ob_start();
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            echo "<h2>Página {$view} em construção</h2>";
        }
        $content = ob_get_clean();
        include __DIR__ . "/../../../views/layouts/admin.php";
    }
}
