<?php

namespace App\Controllers\Admin;

use App\Core\Database\Connection;

class DashboardController
{
    protected $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    public function index()
    {
        $stats = [];

        // Contagens principais
        $stats['total_students']     = (int)$this->db->query("SELECT COUNT(*) FROM users WHERE role = 'student'")->fetchColumn();
        $stats['total_schools']      = (int)$this->db->query("SELECT COUNT(*) FROM schools")->fetchColumn();
        $stats['total_courses']      = (int)$this->db->query("SELECT COUNT(*) FROM courses WHERE is_active = 1")->fetchColumn();
        $stats['total_enrollments']  = (int)$this->db->query("SELECT COUNT(*) FROM enrollments WHERE status = 'active'")->fetchColumn();
        $stats['total_observations'] = (int)$this->db->query("SELECT COUNT(*) FROM observations")->fetchColumn();
        $stats['total_users']        = (int)$this->db->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $stats['completed_courses']  = (int)$this->db->query("SELECT COUNT(*) FROM enrollments WHERE is_course_completed = 1")->fetchColumn();

        // Novos alunos este mes
        $stats['new_students_month'] = (int)$this->db->query(
            "SELECT COUNT(*) FROM users WHERE role = 'student' AND MONTH(created_at) = MONTH(NOW()) AND YEAR(created_at) = YEAR(NOW())"
        )->fetchColumn();

        // Tempo total assistido (em horas)
        $totalSecs = (int)$this->db->query("SELECT COALESCE(SUM(total_watch_time), 0) FROM enrollments")->fetchColumn();
        $stats['total_watch_hours'] = round($totalSecs / 3600, 1);

        // Progresso medio dos alunos (%)
        $stats['avg_progress'] = round(
            (float)$this->db->query("SELECT COALESCE(AVG(overall_progress_percentage), 0) FROM enrollments WHERE status = 'active'")->fetchColumn(),
            1
        );

        // Quiz attempts
        $stats['total_quiz_attempts'] = (int)$this->db->query("SELECT COUNT(*) FROM quiz_attempts")->fetchColumn();
        $stats['quiz_passed']         = (int)$this->db->query("SELECT COUNT(*) FROM quiz_attempts WHERE passed = 1")->fetchColumn();

        // Contatos
        $contacts = [];
        try {
            $stmt = $this->db->query("SELECT * FROM contacts ORDER BY created_at DESC LIMIT 5");
            $contacts = $stmt->fetchAll();
            $stats['total_contacts']  = (int)$this->db->query("SELECT COUNT(*) FROM contacts")->fetchColumn();
            $stats['unread_contacts'] = (int)$this->db->query("SELECT COUNT(*) FROM contacts WHERE is_read = 0")->fetchColumn();
        } catch (\Exception $e) {
            $stats['total_contacts']  = 0;
            $stats['unread_contacts'] = 0;
        }

        // Matriculas recentes
        $stmt = $this->db->query("
            SELECT e.id, u.name AS student_name, c.title AS course_title,
                   e.enrollment_date, e.status, e.overall_progress_percentage
            FROM enrollments e
            JOIN users u ON e.user_id = u.id
            JOIN courses c ON e.course_id = c.id
            ORDER BY e.enrollment_date DESC
            LIMIT 8
        ");
        $recentEnrollments = $stmt->fetchAll();

        // Cursos mais populares (por matriculas)
        $stmt = $this->db->query("
            SELECT c.title, c.slug, c.is_free,
                   COUNT(e.id) AS total_enrollments,
                   SUM(e.is_course_completed) AS completions,
                   ROUND(AVG(e.overall_progress_percentage), 1) AS avg_progress
            FROM courses c
            LEFT JOIN enrollments e ON c.id = e.course_id
            WHERE c.is_active = 1
            GROUP BY c.id
            ORDER BY total_enrollments DESC
            LIMIT 5
        ");
        $popularCourses = $stmt->fetchAll();

        // Alunos com baixo progresso (< 20%, matriculados ha mais de 7 dias)
        $stmt = $this->db->query("
            SELECT u.name AS student_name, c.title AS course_title,
                   e.overall_progress_percentage, e.enrollment_date, e.last_activity_at
            FROM enrollments e
            JOIN users u ON e.user_id = u.id
            JOIN courses c ON e.course_id = c.id
            WHERE e.status = 'active'
              AND e.overall_progress_percentage < 20
              AND e.enrollment_date < DATE_SUB(NOW(), INTERVAL 7 DAY)
            ORDER BY e.overall_progress_percentage ASC
            LIMIT 5
        ");
        $lowProgressStudents = $stmt->fetchAll();

        // Observacoes recentes
        $recentObservations = [];
        try {
            $stmt = $this->db->query("
                SELECT o.title, o.type, o.observed_at, o.created_at,
                       s.name AS student_name,
                       u.name AS teacher_name
                FROM observations o
                JOIN students s ON o.student_id = s.id
                JOIN users u ON o.user_id = u.id
                ORDER BY o.created_at DESC
                LIMIT 5
            ");
            $recentObservations = $stmt->fetchAll();
        } catch (\Exception $e) {
            $recentObservations = [];
        }

        // Escolas ativas vs inativas
        $stats['active_schools']   = (int)$this->db->query("SELECT COUNT(*) FROM schools WHERE status = 'active'")->fetchColumn();
        $stats['inactive_schools'] = (int)$this->db->query("SELECT COUNT(*) FROM schools WHERE status = 'inactive'")->fetchColumn();

        return $this->render('dashboard', [
            'stats'               => $stats,
            'contacts'            => $contacts,
            'recentEnrollments'   => $recentEnrollments,
            'popularCourses'      => $popularCourses,
            'lowProgressStudents' => $lowProgressStudents,
            'recentObservations'  => $recentObservations,
        ]);
    }

    protected function render($view, $data = [])
    {
        extract($data);
        $viewFile = __DIR__ . "/../../../views/admin/{$view}.php";

        ob_start();
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            echo "<h2>Dashboard em desenvolvimento</h2>";
        }
        $content = ob_get_clean();

        include __DIR__ . "/../../../views/layouts/admin.php";
    }
}
