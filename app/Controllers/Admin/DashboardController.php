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
        $role = $_SESSION['user_role'] ?? 'admin';

        if ($role === 'professor') {
            return $this->indexProfessor();
        }

        if ($role === 'coordenador') {
            return $this->indexCoordenador();
        }

        return $this->indexAdmin();
    }

    // ─── PROFESSOR DASHBOARD ────────────────────────────────────────
    protected function indexProfessor()
    {
        $userId = (int)($_SESSION['user_id'] ?? 0);
        $stats = [];

        // Courses the professor is enrolled in with % completion
        $myCourses = [];
        try {
            $stmt = $this->db->prepare("
                SELECT c.id, c.title, c.slug,
                       e.overall_progress_percentage,
                       e.enrollment_date, e.status,
                       e.is_course_completed
                FROM enrollments e
                JOIN courses c ON e.course_id = c.id
                WHERE e.user_id = :uid AND e.status = 'active'
                ORDER BY e.enrollment_date DESC
            ");
            $stmt->execute(['uid' => $userId]);
            $myCourses = $stmt->fetchAll();
        } catch (\Exception $e) {
            $myCourses = [];
        }
        $stats['total_courses'] = count($myCourses);

        // Active classrooms (only theirs)
        $myClassrooms = [];
        try {
            $stmt = $this->db->prepare("
                SELECT cl.id, cl.name, cl.age_group, cl.period, cl.school_year,
                       s.name AS school_name
                FROM classrooms cl
                JOIN schools s ON cl.school_id = s.id
                WHERE cl.teacher_id = :uid AND cl.status = 'active'
                ORDER BY cl.school_year DESC, cl.name
            ");
            $stmt->execute(['uid' => $userId]);
            $myClassrooms = $stmt->fetchAll();
        } catch (\Exception $e) {
            $myClassrooms = [];
        }
        $stats['total_classrooms'] = count($myClassrooms);

        // Number of students in their classrooms (by school_id, since no pivot table yet)
        $stats['total_students'] = 0;
        try {
            $stmt = $this->db->prepare("
                SELECT COUNT(DISTINCT st.id)
                FROM students st
                INNER JOIN classrooms cl ON cl.school_id = st.school_id
                WHERE cl.teacher_id = :uid AND cl.status = 'active'
            ");
            $stmt->execute(['uid' => $userId]);
            $stats['total_students'] = (int)$stmt->fetchColumn();
        } catch (\Exception $e) {
            $stats['total_students'] = 0;
        }

        // Pending descriptive reports (placeholder - module not yet built)
        $stats['pending_reports'] = 0;

        // Recent observations they created
        $recentObservations = [];
        try {
            $stmt = $this->db->prepare("
                SELECT o.title, o.type, o.observed_at, o.created_at,
                       s.name AS student_name
                FROM observations o
                JOIN students s ON o.student_id = s.id
                WHERE o.user_id = :uid
                ORDER BY o.created_at DESC
                LIMIT 5
            ");
            $stmt->execute(['uid' => $userId]);
            $recentObservations = $stmt->fetchAll();
        } catch (\Exception $e) {
            $recentObservations = [];
        }
        $stats['total_observations'] = 0;
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM observations WHERE user_id = :uid");
            $stmt->execute(['uid' => $userId]);
            $stats['total_observations'] = (int)$stmt->fetchColumn();
        } catch (\Exception $e) {
            // keep 0
        }

        return $this->render('dashboard_professor', [
            'stats'              => $stats,
            'myCourses'          => $myCourses,
            'myClassrooms'       => $myClassrooms,
            'recentObservations' => $recentObservations,
        ]);
    }

    // ─── COORDENADOR DASHBOARD ──────────────────────────────────────
    protected function indexCoordenador()
    {
        $stats = [];

        // Total classrooms
        $stats['total_classrooms'] = (int)$this->db->query("SELECT COUNT(*) FROM classrooms WHERE status = 'active'")->fetchColumn();

        // Total children (students)
        $stats['total_students'] = (int)$this->db->query("SELECT COUNT(*) FROM students")->fetchColumn();

        // Total professors
        $stats['total_professors'] = (int)$this->db->query("SELECT COUNT(*) FROM users WHERE role = 'professor'")->fetchColumn();

        // Observations stats
        $stats['total_observations'] = (int)$this->db->query("SELECT COUNT(*) FROM observations")->fetchColumn();
        $stats['observations_this_month'] = 0;
        try {
            $stats['observations_this_month'] = (int)$this->db->query(
                "SELECT COUNT(*) FROM observations WHERE MONTH(created_at) = MONTH(NOW()) AND YEAR(created_at) = YEAR(NOW())"
            )->fetchColumn();
        } catch (\Exception $e) {
            // keep 0
        }

        // Total schools
        $stats['total_schools'] = (int)$this->db->query("SELECT COUNT(*) FROM schools WHERE status = 'active'")->fetchColumn();

        // Course report table (name, enrolled count, avg completion %)
        $courseReport = [];
        try {
            $stmt = $this->db->query("
                SELECT c.id, c.title,
                       COUNT(e.id) AS enrolled_count,
                       ROUND(COALESCE(AVG(e.overall_progress_percentage), 0), 1) AS avg_progress
                FROM courses c
                LEFT JOIN enrollments e ON c.id = e.course_id
                WHERE c.is_active = 1
                GROUP BY c.id
                ORDER BY enrolled_count DESC
            ");
            $courseReport = $stmt->fetchAll();
        } catch (\Exception $e) {
            $courseReport = [];
        }

        // Recent observations (all)
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
                LIMIT 8
            ");
            $recentObservations = $stmt->fetchAll();
        } catch (\Exception $e) {
            $recentObservations = [];
        }

        return $this->render('dashboard_coordenador', [
            'stats'              => $stats,
            'courseReport'       => $courseReport,
            'recentObservations' => $recentObservations,
        ]);
    }

    // ─── ADMIN DASHBOARD (original) ─────────────────────────────────
    protected function indexAdmin()
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
