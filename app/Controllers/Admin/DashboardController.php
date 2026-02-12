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
        $stats['total_students'] = (int)$this->db->query("SELECT COUNT(*) FROM users WHERE role = 'student'")->fetchColumn();
        $stats['total_schools'] = (int)$this->db->query("SELECT COUNT(*) FROM schools")->fetchColumn();
        $stats['total_courses'] = (int)$this->db->query("SELECT COUNT(*) FROM courses WHERE is_active = 1")->fetchColumn();
        $stats['total_enrollments'] = (int)$this->db->query("SELECT COUNT(*) FROM enrollments WHERE status = 'active'")->fetchColumn();
        $stats['total_observations'] = (int)$this->db->query("SELECT COUNT(*) FROM observations")->fetchColumn();
        $stats['total_users'] = (int)$this->db->query("SELECT COUNT(*) FROM users")->fetchColumn();

        // Contatos recentes (tabela contacts, se existir)
        $contacts = [];
        try {
            $stmt = $this->db->query("SELECT * FROM contacts ORDER BY created_at DESC LIMIT 5");
            $contacts = $stmt->fetchAll();
            $stats['total_contacts'] = (int)$this->db->query("SELECT COUNT(*) FROM contacts")->fetchColumn();
        } catch (\Exception $e) {
            $stats['total_contacts'] = 0;
        }

        // Matrículas recentes
        $stmt = $this->db->query("
            SELECT e.id, u.name as student_name, c.title as course_title, e.enrollment_date, e.status
            FROM enrollments e
            JOIN users u ON e.user_id = u.id
            JOIN courses c ON e.course_id = c.id
            ORDER BY e.enrollment_date DESC
            LIMIT 5
        ");
        $recentEnrollments = $stmt->fetchAll();

        // Cursos completados
        $stats['completed_courses'] = (int)$this->db->query("SELECT COUNT(*) FROM enrollments WHERE is_course_completed = 1")->fetchColumn();

        return $this->render('dashboard', [
            'stats' => $stats,
            'contacts' => $contacts,
            'recentEnrollments' => $recentEnrollments,
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
