<?php

namespace App\Controllers\Admin;

use App\Models\Enrollment;
use App\Models\Course;
use App\Models\User;
use App\Core\Database\Connection;

class EnrollmentAdminController
{
    protected $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    /**
     * GET /admin/enrollments
     */
    public function index()
    {
        $filterCourse    = $_GET['course_id'] ?? '';
        $filterStatus    = $_GET['status'] ?? '';
        $filterCompleted = $_GET['completed'] ?? '';

        $sql = "
            SELECT e.*, u.name as student_name, u.email as student_email,
                   c.title as course_title, c.slug as course_slug
            FROM enrollments e
            JOIN users u ON e.user_id = u.id
            JOIN courses c ON e.course_id = c.id
            WHERE 1=1
        ";
        $params = [];

        if ($filterCourse) {
            $sql .= " AND e.course_id = ?";
            $params[] = $filterCourse;
        }
        if ($filterStatus) {
            $sql .= " AND e.status = ?";
            $params[] = $filterStatus;
        }
        if ($filterCompleted !== '') {
            $sql .= " AND e.is_course_completed = ?";
            $params[] = (int) $filterCompleted;
        }

        $sql .= " ORDER BY e.enrollment_date DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $enrollments = $stmt->fetchAll();

        // Cursos para filtro
        $courses = $this->db->query("SELECT id, title FROM courses ORDER BY title")->fetchAll();

        return $this->render('enrollments/index', [
            'enrollments'     => $enrollments,
            'courses'         => $courses,
            'filterCourse'    => $filterCourse,
            'filterStatus'    => $filterStatus,
            'filterCompleted' => $filterCompleted,
        ]);
    }

    /**
     * POST /admin/enrollments/store
     * Criar matrícula manual
     */
    public function store()
    {
        $userId = $_POST['user_id'] ?? 0;
        $courseId = $_POST['course_id'] ?? 0;
        $status = $_POST['status'] ?? 'active';

        // Verificar se já existe
        $stmt = $this->db->prepare("SELECT id FROM enrollments WHERE user_id = ? AND course_id = ?");
        $stmt->execute([$userId, $courseId]);
        if ($stmt->fetch()) {
            $_SESSION['error_message'] = 'Aluno já está matriculado neste curso.';
            header('Location: /admin/enrollments');
            exit;
        }

        $stmt = $this->db->prepare("INSERT INTO enrollments (user_id, course_id, status, payment_status) VALUES (?, ?, ?, ?)");
        $stmt->execute([$userId, $courseId, $status, $status === 'active' ? 'paid' : 'pending']);

        $_SESSION['success_message'] = 'Matrícula criada com sucesso!';
        header('Location: /admin/enrollments');
        exit;
    }

    /**
     * POST /admin/enrollments/{id}/activate
     */
    public function activate($id)
    {
        $stmt = $this->db->prepare("UPDATE enrollments SET status = 'active', payment_status = 'paid' WHERE id = ?");
        $stmt->execute([$id]);

        $_SESSION['success_message'] = 'Matrícula ativada!';
        header('Location: /admin/enrollments');
        exit;
    }

    /**
     * POST /admin/enrollments/{id}/deactivate
     */
    public function deactivate($id)
    {
        $stmt = $this->db->prepare("UPDATE enrollments SET status = 'inactive' WHERE id = ?");
        $stmt->execute([$id]);

        $_SESSION['success_message'] = 'Matrícula desativada.';
        header('Location: /admin/enrollments');
        exit;
    }

    /**
     * POST /admin/enrollments/{id}/delete
     */
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM enrollments WHERE id = ?");
        $stmt->execute([$id]);

        $_SESSION['success_message'] = 'Matrícula removida.';
        header('Location: /admin/enrollments');
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
