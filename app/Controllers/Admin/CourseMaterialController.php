<?php

namespace App\Controllers\Admin;

use App\Core\Database\Connection;

class CourseMaterialController
{
    protected $db;
    protected $uploadDir;

    public function __construct()
    {
        $this->db = Connection::getInstance();
        $this->uploadDir = __DIR__ . '/../../../storage/materials/';
        if (!is_dir($this->uploadDir)) {
            mkdir($this->uploadDir, 0755, true);
        }
    }

    // GET /admin/courses/{courseId}/materials
    public function index($courseId)
    {
        $course = $this->getCourse($courseId);
        if (!$course) { http_response_code(404); echo "Curso não encontrado"; return; }

        $stmt = $this->db->prepare("
            SELECT m.*, u.name AS created_by_name,
                   s.title AS section_title
            FROM course_materials m
            JOIN users u ON m.created_by = u.id
            LEFT JOIN sections s ON m.section_id = s.id
            WHERE m.course_id = ?
            ORDER BY m.sort_order ASC, m.created_at DESC
        ");
        $stmt->execute([$courseId]);
        $materials = $stmt->fetchAll();

        // Sections for filter dropdown
        $stmt2 = $this->db->prepare("SELECT id, title FROM sections WHERE course_id = ? ORDER BY sort_order");
        $stmt2->execute([$courseId]);
        $sections = $stmt2->fetchAll();

        $this->render('materials/index', [
            'course'    => $course,
            'materials' => $materials,
            'sections'  => $sections,
        ]);
    }

    // GET /admin/courses/{courseId}/materials/create
    public function create($courseId)
    {
        $course = $this->getCourse($courseId);
        if (!$course) { http_response_code(404); echo "Curso não encontrado"; return; }

        $stmt = $this->db->prepare("SELECT id, title FROM sections WHERE course_id = ? ORDER BY sort_order");
        $stmt->execute([$courseId]);
        $sections = $stmt->fetchAll();

        $this->render('materials/create', ['course' => $course, 'sections' => $sections]);
    }

    // POST /admin/courses/{courseId}/materials/create
    public function store($courseId)
    {
        $course = $this->getCourse($courseId);
        if (!$course) { http_response_code(404); return; }

        $title      = trim($_POST['title'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $sectionId  = !empty($_POST['section_id']) ? (int)$_POST['section_id'] : null;
        $sortOrder  = (int)($_POST['sort_order'] ?? 0);

        if (!$title) {
            $_SESSION['error'] = 'Título é obrigatório.';
            header("Location: /admin/courses/{$courseId}/materials/create");
            exit;
        }

        if (empty($_FILES['material_file']['name'])) {
            $_SESSION['error'] = 'Arquivo é obrigatório.';
            header("Location: /admin/courses/{$courseId}/materials/create");
            exit;
        }

        $file     = $_FILES['material_file'];
        $ext      = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $allowed  = ['pdf','xlsx','xls','csv','jpg','jpeg','png','gif','webp','mp4','avi','mov','webm','doc','docx','ppt','pptx','zip'];

        if (!in_array($ext, $allowed)) {
            $_SESSION['error'] = 'Tipo de arquivo não permitido.';
            header("Location: /admin/courses/{$courseId}/materials/create");
            exit;
        }

        $fileType = match(true) {
            in_array($ext, ['pdf'])                          => 'pdf',
            in_array($ext, ['xlsx','xls','csv'])             => 'excel',
            in_array($ext, ['jpg','jpeg','png','gif','webp'])=> 'image',
            in_array($ext, ['mp4','avi','mov','webm'])       => 'video',
            default                                           => 'other',
        };

        $uniqueName = uniqid('mat_') . '_' . time() . '.' . $ext;
        $destPath   = $this->uploadDir . $uniqueName;

        if (!move_uploaded_file($file['tmp_name'], $destPath)) {
            $_SESSION['error'] = 'Falha ao salvar o arquivo.';
            header("Location: /admin/courses/{$courseId}/materials/create");
            exit;
        }

        $stmt = $this->db->prepare("
            INSERT INTO course_materials
                (course_id, section_id, title, description, file_type, file_name, file_path, file_size, sort_order, created_by)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $courseId, $sectionId, $title, $description,
            $fileType, $file['name'], 'materials/' . $uniqueName,
            $file['size'], $sortOrder, $_SESSION['user_id']
        ]);

        $_SESSION['success_message'] = 'Material adicionado com sucesso!';
        header("Location: /admin/courses/{$courseId}/materials");
        exit;
    }

    // POST /admin/materials/{id}/delete
    public function delete($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM course_materials WHERE id = ?");
        $stmt->execute([$id]);
        $material = $stmt->fetch();

        if ($material) {
            $fullPath = __DIR__ . '/../../../storage/' . $material['file_path'];
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }
            $this->db->prepare("DELETE FROM course_materials WHERE id = ?")->execute([$id]);
        }

        $_SESSION['success_message'] = 'Material removido.';
        header("Location: /admin/courses/{$material['course_id']}/materials");
        exit;
    }

    // GET /admin/materials/{id}/download
    public function download($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM course_materials WHERE id = ? AND is_active = 1");
        $stmt->execute([$id]);
        $material = $stmt->fetch();

        if (!$material) { http_response_code(404); echo "Material não encontrado"; return; }

        $fullPath = __DIR__ . '/../../../storage/' . $material['file_path'];
        if (!file_exists($fullPath)) { http_response_code(404); echo "Arquivo não encontrado"; return; }

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $material['file_name'] . '"');
        header('Content-Length: ' . filesize($fullPath));
        readfile($fullPath);
        exit;
    }

    private function getCourse($courseId)
    {
        $stmt = $this->db->prepare("SELECT id, title, slug FROM courses WHERE id = ?");
        $stmt->execute([$courseId]);
        return $stmt->fetch() ?: null;
    }

    protected function render($view, $data = [])
    {
        extract($data);
        $viewFile = __DIR__ . "/../../../views/admin/{$view}.php";
        ob_start();
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            echo "<p>View não encontrada: {$view}</p>";
        }
        $content = ob_get_clean();
        include __DIR__ . "/../../../views/layouts/admin.php";
    }
}
