<?php

namespace App\Controllers;

use App\Core\Database\Connection;

class StudentMaterialController
{
    protected $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    // GET /curso/{slug}/materiais
    public function index($slug)
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $stmt = $this->db->prepare("SELECT id, title, slug FROM courses WHERE slug = ?");
        $stmt->execute([$slug]);
        $course = $stmt->fetch();
        if (!$course) { http_response_code(404); echo "Curso não encontrado"; return; }

        // Verificar matrícula ativa
        $stmt = $this->db->prepare("SELECT id FROM enrollments WHERE user_id = ? AND course_id = ? AND status = 'active'");
        $stmt->execute([$_SESSION['user_id'], $course['id']]);
        if (!$stmt->fetch()) {
            header('Location: /curso/' . $slug);
            exit;
        }

        $stmt = $this->db->prepare("
            SELECT m.*, s.title AS section_title
            FROM course_materials m
            LEFT JOIN sections s ON m.section_id = s.id
            WHERE m.course_id = ? AND m.is_active = 1
            ORDER BY m.sort_order ASC, m.created_at ASC
        ");
        $stmt->execute([$course['id']]);
        $materials = $stmt->fetchAll();

        $this->render('curso-materiais', [
            'title'     => 'Materiais | ' . $course['title'],
            'course'    => $course,
            'materials' => $materials,
        ]);
    }

    // GET /material/{id}/download
    public function download($id)
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $stmt = $this->db->prepare("SELECT * FROM course_materials WHERE id = ? AND is_active = 1");
        $stmt->execute([$id]);
        $material = $stmt->fetch();
        if (!$material) { http_response_code(404); echo "Material não encontrado"; return; }

        // Verificar matrícula ativa no curso
        $stmt = $this->db->prepare("SELECT id FROM enrollments WHERE user_id = ? AND course_id = ? AND status = 'active'");
        $stmt->execute([$_SESSION['user_id'], $material['course_id']]);
        if (!$stmt->fetch()) {
            http_response_code(403);
            echo "Acesso negado. Matricule-se no curso para acessar os materiais.";
            return;
        }

        $fullPath = __DIR__ . '/../../storage/' . $material['file_path'];
        if (!file_exists($fullPath)) {
            http_response_code(404);
            echo "Arquivo não encontrado no servidor.";
            return;
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $material['file_name'] . '"');
        header('Content-Length: ' . filesize($fullPath));
        readfile($fullPath);
        exit;
    }

    protected function render($view, $data = [])
    {
        extract($data);
        $viewFile = __DIR__ . "/../../views/pages/{$view}.php";
        ob_start();
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            echo "<p>View não encontrada.</p>";
        }
        $content = ob_get_clean();
        include __DIR__ . "/../../views/layouts/public.php";
    }
}
