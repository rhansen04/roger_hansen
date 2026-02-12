<?php

namespace App\Controllers\Admin;

use App\Core\Database\Connection;

class VideoAdminController
{
    protected $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    /**
     * GET /admin/video-dashboard
     * Dashboard principal de metricas de video
     */
    public function index()
    {
        $stats = $this->getOverviewStats();
        $courseStats = $this->getCourseStats();
        $recentActivity = $this->getRecentActivity();
        $topStudents = $this->getTopStudents();
        $inactiveStudents = $this->getInactiveStudents(7);
        $watchLogs = $this->getRecentWatchLogs(20);

        return $this->render('video-dashboard', [
            'stats' => $stats,
            'courseStats' => $courseStats,
            'recentActivity' => $recentActivity,
            'topStudents' => $topStudents,
            'inactiveStudents' => $inactiveStudents,
            'watchLogs' => $watchLogs,
        ]);
    }

    /**
     * GET /admin/video-dashboard/aluno/{enrollmentId}
     * Detalhe do progresso de um aluno
     */
    public function studentDetail($enrollmentId)
    {
        $enrollment = $this->db->prepare("
            SELECT e.*, u.name as student_name, u.email as student_email, c.title as course_title, c.slug as course_slug
            FROM enrollments e
            JOIN users u ON e.user_id = u.id
            JOIN courses c ON e.course_id = c.id
            WHERE e.id = ?
        ")->execute([$enrollmentId]);

        $stmt = $this->db->prepare("
            SELECT e.*, u.name as student_name, u.email as student_email, c.title as course_title
            FROM enrollments e
            JOIN users u ON e.user_id = u.id
            JOIN courses c ON e.course_id = c.id
            WHERE e.id = ?
        ");
        $stmt->execute([$enrollmentId]);
        $enrollment = $stmt->fetch();

        if (!$enrollment) {
            header('Location: /admin/video-dashboard');
            exit;
        }

        // Progresso por video
        $stmt = $this->db->prepare("
            SELECT vp.*, l.title as lesson_title, s.title as section_title
            FROM video_progress vp
            JOIN lessons l ON vp.lesson_id = l.id
            LEFT JOIN sections s ON l.section_id = s.id
            WHERE vp.enrollment_id = ?
            ORDER BY s.sort_order, l.sort_order
        ");
        $stmt->execute([$enrollmentId]);
        $videoProgress = $stmt->fetchAll();

        // Watch logs
        $stmt = $this->db->prepare("
            SELECT wl.*, l.title as lesson_title
            FROM video_watch_logs wl
            JOIN video_progress vp ON wl.video_progress_id = vp.id
            JOIN lessons l ON vp.lesson_id = l.id
            WHERE vp.enrollment_id = ?
            ORDER BY wl.session_start DESC
            LIMIT 50
        ");
        $stmt->execute([$enrollmentId]);
        $watchLogs = $stmt->fetchAll();

        return $this->render('video-student-detail', [
            'enrollment' => $enrollment,
            'videoProgress' => $videoProgress,
            'watchLogs' => $watchLogs,
        ]);
    }

    /**
     * Estatisticas gerais
     */
    private function getOverviewStats()
    {
        $stats = [];

        // Total de matriculas ativas
        $stats['total_enrollments'] = $this->db->query(
            "SELECT COUNT(*) FROM enrollments WHERE status = 'active'"
        )->fetchColumn() ?: 0;

        // Videos completados
        $stats['total_completed'] = $this->db->query(
            "SELECT COUNT(*) FROM video_progress WHERE is_completed = 1"
        )->fetchColumn() ?: 0;

        // Total de sessoes
        $stats['total_sessions'] = $this->db->query(
            "SELECT COUNT(*) FROM video_watch_logs"
        )->fetchColumn() ?: 0;

        // Tempo total assistido (segundos)
        $totalSeconds = $this->db->query(
            "SELECT COALESCE(SUM(session_duration), 0) FROM video_watch_logs"
        )->fetchColumn() ?: 0;
        $stats['total_watch_hours'] = round($totalSeconds / 3600, 1);

        // Cursos com 100% conclusao
        $stats['courses_completed'] = $this->db->query(
            "SELECT COUNT(*) FROM enrollments WHERE is_course_completed = 1"
        )->fetchColumn() ?: 0;

        // Progresso medio
        $stats['avg_progress'] = round($this->db->query(
            "SELECT COALESCE(AVG(overall_progress_percentage), 0) FROM enrollments WHERE status = 'active'"
        )->fetchColumn() ?: 0, 1);

        return $stats;
    }

    /**
     * Estatisticas por curso
     */
    private function getCourseStats()
    {
        $stmt = $this->db->query("
            SELECT
                c.id, c.title, c.slug,
                COUNT(DISTINCT e.id) as total_students,
                ROUND(COALESCE(AVG(e.overall_progress_percentage), 0), 1) as avg_progress,
                SUM(CASE WHEN e.is_course_completed = 1 THEN 1 ELSE 0 END) as completed_count,
                (SELECT COUNT(*) FROM lessons l JOIN sections s ON l.section_id = s.id WHERE s.course_id = c.id) as total_lessons
            FROM courses c
            LEFT JOIN enrollments e ON c.id = e.course_id AND e.status = 'active'
            WHERE c.is_active = 1
            GROUP BY c.id, c.title, c.slug
            ORDER BY total_students DESC
        ");
        return $stmt->fetchAll();
    }

    /**
     * Atividade recente (ultimos 7 dias)
     */
    private function getRecentActivity()
    {
        $stmt = $this->db->query("
            SELECT
                DATE(wl.session_start) as dia,
                COUNT(*) as sessoes,
                COUNT(DISTINCT vp.enrollment_id) as alunos_ativos,
                ROUND(COALESCE(SUM(wl.session_duration), 0) / 60, 0) as minutos_assistidos,
                SUM(CASE WHEN wl.completed_during_session = 1 THEN 1 ELSE 0 END) as completados
            FROM video_watch_logs wl
            JOIN video_progress vp ON wl.video_progress_id = vp.id
            WHERE wl.session_start >= DATE_SUB(NOW(), INTERVAL 7 DAY)
            GROUP BY DATE(wl.session_start)
            ORDER BY dia DESC
        ");
        return $stmt->fetchAll();
    }

    /**
     * Top alunos por progresso
     */
    private function getTopStudents()
    {
        $stmt = $this->db->query("
            SELECT
                u.id as user_id, u.name, u.email,
                e.id as enrollment_id,
                c.title as course_title,
                e.overall_progress_percentage,
                e.videos_completed_count,
                e.total_videos_count,
                e.is_course_completed,
                e.last_activity_at
            FROM enrollments e
            JOIN users u ON e.user_id = u.id
            JOIN courses c ON e.course_id = c.id
            WHERE e.status = 'active'
            ORDER BY e.overall_progress_percentage DESC, e.last_activity_at DESC
            LIMIT 15
        ");
        return $stmt->fetchAll();
    }

    /**
     * Alunos inativos ha mais de X dias
     */
    private function getInactiveStudents($days = 7)
    {
        $stmt = $this->db->prepare("
            SELECT
                u.name, u.email,
                c.title as course_title,
                e.id as enrollment_id,
                e.overall_progress_percentage,
                e.last_activity_at,
                DATEDIFF(NOW(), COALESCE(e.last_activity_at, e.enrollment_date)) as days_inactive
            FROM enrollments e
            JOIN users u ON e.user_id = u.id
            JOIN courses c ON e.course_id = c.id
            WHERE e.status = 'active'
              AND e.is_course_completed = 0
              AND (e.last_activity_at IS NULL OR e.last_activity_at < DATE_SUB(NOW(), INTERVAL ? DAY))
            ORDER BY days_inactive DESC
            LIMIT 10
        ");
        $stmt->execute([$days]);
        return $stmt->fetchAll();
    }

    /**
     * Logs recentes de sessoes
     */
    private function getRecentWatchLogs($limit = 20)
    {
        $stmt = $this->db->prepare("
            SELECT
                wl.*,
                l.title as lesson_title,
                u.name as student_name,
                c.title as course_title
            FROM video_watch_logs wl
            JOIN video_progress vp ON wl.video_progress_id = vp.id
            JOIN lessons l ON vp.lesson_id = l.id
            JOIN enrollments e ON vp.enrollment_id = e.id
            JOIN users u ON e.user_id = u.id
            JOIN courses c ON e.course_id = c.id
            ORDER BY wl.session_start DESC
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    protected function render($view, $data = [])
    {
        extract($data);
        $viewFile = __DIR__ . "/../../../views/admin/{$view}.php";

        ob_start();
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            echo "<h2>Pagina em desenvolvimento</h2>";
        }
        $content = ob_get_clean();

        include __DIR__ . "/../../../views/layouts/admin.php";
    }
}
