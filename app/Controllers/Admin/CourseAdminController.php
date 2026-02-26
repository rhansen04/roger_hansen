<?php

namespace App\Controllers\Admin;

use App\Models\Course;
use App\Models\Section;
use App\Models\Lesson;
use App\Models\Enrollment;
use App\Models\User;

class CourseAdminController
{
    public function index()
    {
        $courseModel = new Course();
        $db = \App\Core\Database\Connection::getInstance();

        $sql = "SELECT c.*,
                       u.name as instructor_name,
                       COUNT(DISTINCT s.id) as sections_count,
                       COUNT(DISTINCT l.id) as lessons_count,
                       COUNT(DISTINCT e.id) as enrollments_count
                FROM courses c
                LEFT JOIN users u ON c.instructor_id = u.id
                LEFT JOIN sections s ON c.id = s.course_id
                LEFT JOIN lessons l ON s.id = l.section_id
                LEFT JOIN enrollments e ON c.id = e.course_id
                GROUP BY c.id
                ORDER BY c.created_at DESC";
        $stmt = $db->query($sql);
        $courses = $stmt->fetchAll();

        return $this->render('courses/index', ['courses' => $courses, 'pageTitle' => 'Gestão de Cursos']);
    }

    public function create()
    {
        $userModel = new User();
        $instructors = $userModel->all();

        return $this->render('courses/create', ['instructors' => $instructors]);
    }

    public function store()
    {
        $data = [
            ':title' => $_POST['title'] ?? '',
            ':slug' => $this->generateSlug($_POST['title'] ?? ''),
            ':description' => $_POST['description'] ?? '',
            ':short_description' => $_POST['short_description'] ?? '',
            ':cover_image' => null,
            ':price' => $_POST['price'] ?? 0,
            ':is_free' => isset($_POST['is_free']) ? 1 : 0,
            ':is_active' => isset($_POST['is_active']) ? 1 : 0,
            ':category' => $_POST['category'] ?? '',
            ':level' => $_POST['level'] ?? 'beginner',
            ':duration_hours' => $_POST['duration_hours'] ?? 0,
            ':instructor_id' => !empty($_POST['instructor_id']) ? $_POST['instructor_id'] : null,
        ];

        // Upload cover image
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === 0) {
            $ext = pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION);
            $filename = 'course_' . time() . '.' . $ext;
            $uploadPath = __DIR__ . '/../../../public/uploads/courses/' . $filename;

            if (!is_dir(dirname($uploadPath))) {
                mkdir(dirname($uploadPath), 0775, true);
            }

            if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $uploadPath)) {
                $data[':cover_image'] = '/uploads/courses/' . $filename;
            }
        }

        $courseModel = new Course();
        if ($courseModel->create($data)) {
            $_SESSION['success_message'] = 'Curso criado com sucesso!';
            header('Location: /admin/courses');
            exit;
        }

        $_SESSION['error_message'] = 'Erro ao criar curso.';
        return $this->create();
    }

    public function show($id)
    {
        $courseModel = new Course();
        $course = $courseModel->find($id);

        if (!$course) {
            $_SESSION['error_message'] = 'Curso não encontrado.';
            header('Location: /admin/courses');
            exit;
        }

        $sectionModel = new Section();
        $sections = $sectionModel->getByCourse($id);

        $lessonModel = new Lesson();
        $sectionLessons = [];
        foreach ($sections as $section) {
            $sectionLessons[$section['id']] = $lessonModel->getBySection($section['id']);
        }

        $enrollmentModel = new Enrollment();
        $enrollments = $enrollmentModel->getByCourse($id);

        return $this->render('courses/show', [
            'course' => $course,
            'sections' => $sections,
            'sectionLessons' => $sectionLessons,
            'enrollments' => $enrollments,
            'pageTitle' => 'Curso: ' . $course['title'],
        ]);
    }

    public function edit($id)
    {
        $courseModel = new Course();
        $course = $courseModel->find($id);

        if (!$course) {
            $_SESSION['error_message'] = 'Curso não encontrado.';
            header('Location: /admin/courses');
            exit;
        }

        $userModel = new User();
        $instructors = $userModel->all();

        return $this->render('courses/edit', [
            'course' => $course,
            'instructors' => $instructors,
        ]);
    }

    public function update($id)
    {
        $courseModel = new Course();
        $course = $courseModel->find($id);

        if (!$course) {
            $_SESSION['error_message'] = 'Curso não encontrado.';
            header('Location: /admin/courses');
            exit;
        }

        $data = [
            ':title' => $_POST['title'] ?? '',
            ':slug' => $this->generateSlug($_POST['title'] ?? ''),
            ':description' => $_POST['description'] ?? '',
            ':short_description' => $_POST['short_description'] ?? '',
            ':cover_image' => $course['cover_image'],
            ':price' => $_POST['price'] ?? 0,
            ':is_free' => isset($_POST['is_free']) ? 1 : 0,
            ':is_active' => isset($_POST['is_active']) ? 1 : 0,
            ':category' => $_POST['category'] ?? '',
            ':level' => $_POST['level'] ?? 'beginner',
            ':duration_hours' => $_POST['duration_hours'] ?? 0,
            ':instructor_id' => !empty($_POST['instructor_id']) ? $_POST['instructor_id'] : null,
        ];

        // Upload new cover image
        if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === 0) {
            $ext = pathinfo($_FILES['cover_image']['name'], PATHINFO_EXTENSION);
            $filename = 'course_' . time() . '.' . $ext;
            $uploadPath = __DIR__ . '/../../../public/uploads/courses/' . $filename;

            if (!is_dir(dirname($uploadPath))) {
                mkdir(dirname($uploadPath), 0775, true);
            }

            if (move_uploaded_file($_FILES['cover_image']['tmp_name'], $uploadPath)) {
                if ($course['cover_image']) {
                    $oldPath = __DIR__ . '/../../../public' . $course['cover_image'];
                    if (file_exists($oldPath)) unlink($oldPath);
                }
                $data[':cover_image'] = '/uploads/courses/' . $filename;
            }
        }

        if ($courseModel->update($id, $data)) {
            $_SESSION['success_message'] = 'Curso atualizado com sucesso!';
            header('Location: /admin/courses/' . $id);
            exit;
        }

        $_SESSION['error_message'] = 'Erro ao atualizar curso.';
        return $this->edit($id);
    }

    public function delete($id)
    {
        $courseModel = new Course();
        $course = $courseModel->find($id);

        if (!$course) {
            $_SESSION['error_message'] = 'Curso não encontrado.';
            header('Location: /admin/courses');
            exit;
        }

        // Check enrollments
        $enrollmentModel = new Enrollment();
        $enrollments = $enrollmentModel->getByCourse($id);
        if (count($enrollments) > 0) {
            $_SESSION['error_message'] = "Não é possível deletar o curso \"{$course['title']}\". Existem " . count($enrollments) . " matrícula(s) vinculada(s).";
            header('Location: /admin/courses');
            exit;
        }

        // Delete cover image
        if ($course['cover_image']) {
            $imgPath = __DIR__ . '/../../../public' . $course['cover_image'];
            if (file_exists($imgPath)) unlink($imgPath);
        }

        if ($courseModel->delete($id)) {
            $_SESSION['success_message'] = 'Curso deletado com sucesso!';
        } else {
            $_SESSION['error_message'] = 'Erro ao deletar curso.';
        }

        header('Location: /admin/courses');
        exit;
    }

    private function generateSlug($title)
    {
        $slug = mb_strtolower($title);
        $slug = preg_replace('/[áàãâä]/u', 'a', $slug);
        $slug = preg_replace('/[éèêë]/u', 'e', $slug);
        $slug = preg_replace('/[íìîï]/u', 'i', $slug);
        $slug = preg_replace('/[óòõôö]/u', 'o', $slug);
        $slug = preg_replace('/[úùûü]/u', 'u', $slug);
        $slug = preg_replace('/[ç]/u', 'c', $slug);
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        return trim($slug, '-');
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
