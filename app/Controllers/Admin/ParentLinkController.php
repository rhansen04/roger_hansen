<?php

namespace App\Controllers\Admin;

use App\Core\Database\Connection;

class ParentLinkController
{
    protected $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    // GET /admin/parents
    public function index()
    {
        $stmt = $this->db->query("
            SELECT u.id, u.name, u.email, u.created_at,
                   GROUP_CONCAT(su.name SEPARATOR ', ') AS children_names,
                   COUNT(ps.id) AS children_count
            FROM users u
            LEFT JOIN parent_student ps ON ps.parent_id = u.id
            LEFT JOIN users su ON ps.student_user_id = su.id
            WHERE u.role = 'parent'
            GROUP BY u.id
            ORDER BY u.name
        ");
        $parents = $stmt->fetchAll();
        $this->render('parents/index', ['parents' => $parents]);
    }

    // GET /admin/parents/{parentId}/link
    public function linkForm($parentId)
    {
        $stmt = $this->db->prepare("SELECT id, name, email FROM users WHERE id = ? AND role = 'parent'");
        $stmt->execute([$parentId]);
        $parent = $stmt->fetch();
        if (!$parent) {
            http_response_code(404);
            echo "Responsavel nao encontrado.";
            return;
        }

        $stmt = $this->db->prepare("SELECT id, name, email FROM users WHERE role = 'student' ORDER BY name");
        $stmt->execute();
        $students = $stmt->fetchAll();

        $stmt = $this->db->prepare("
            SELECT ps.*, u.name AS student_name
            FROM parent_student ps
            JOIN users u ON ps.student_user_id = u.id
            WHERE ps.parent_id = ?
        ");
        $stmt->execute([$parentId]);
        $linked = $stmt->fetchAll();

        $this->render('parents/link', [
            'parent'   => $parent,
            'students' => $students,
            'linked'   => $linked,
        ]);
    }

    // POST /admin/parents/{parentId}/link
    public function link($parentId)
    {
        $studentId    = (int)($_POST['student_id'] ?? 0);
        $relationship = trim($_POST['relationship'] ?? 'pai/mae');

        if ($studentId) {
            try {
                $this->db->prepare("INSERT IGNORE INTO parent_student (parent_id, student_user_id, relationship) VALUES (?, ?, ?)")
                    ->execute([$parentId, $studentId, $relationship]);
                $_SESSION['success_message'] = 'Aluno vinculado com sucesso!';
            } catch (\Exception $e) {
                $_SESSION['error'] = 'Erro ao vincular aluno.';
            }
        }
        header("Location: /admin/parents/{$parentId}/link");
        exit;
    }

    // POST /admin/parents/unlink/{linkId}
    public function unlink($linkId)
    {
        $stmt = $this->db->prepare("SELECT parent_id FROM parent_student WHERE id = ?");
        $stmt->execute([$linkId]);
        $row = $stmt->fetch();
        $this->db->prepare("DELETE FROM parent_student WHERE id = ?")->execute([$linkId]);
        $_SESSION['success_message'] = 'Vinculo removido.';
        header("Location: /admin/parents/" . ($row['parent_id'] ?? '') . "/link");
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
            echo "<p>View nao encontrada: {$view}</p>";
        }
        $content = ob_get_clean();
        include __DIR__ . "/../../../views/layouts/admin.php";
    }
}
