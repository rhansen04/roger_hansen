<?php

namespace App\Controllers\Admin;

use App\Models\Lesson;
use App\Models\Section;
use App\Models\Course;

class LessonAdminController
{
    public function create($sectionId)
    {
        $sectionModel = new Section();
        $section = $sectionModel->find($sectionId);

        if (!$section) {
            $_SESSION['error_message'] = 'Seção não encontrada.';
            header('Location: /admin/courses');
            exit;
        }

        $courseModel = new Course();
        $course = $courseModel->find($section['course_id']);

        return $this->render('lessons/create', [
            'section' => $section,
            'course' => $course,
        ]);
    }

    public function store($sectionId)
    {
        $sectionModel = new Section();
        $section = $sectionModel->find($sectionId);

        if (!$section) {
            $_SESSION['error_message'] = 'Seção não encontrada.';
            header('Location: /admin/courses');
            exit;
        }

        $data = [
            ':section_id' => $sectionId,
            ':title' => $_POST['title'] ?? '',
            ':description' => $_POST['description'] ?? '',
            ':content' => $_POST['content'] ?? '',
            ':video_url' => $_POST['video_url'] ?? '',
            ':video_duration' => $_POST['video_duration'] ?? 0,
            ':material_file' => null,
            ':sort_order' => $_POST['sort_order'] ?? 0,
            ':duration_minutes' => $_POST['duration_minutes'] ?? 0,
            ':is_preview' => isset($_POST['is_preview']) ? 1 : 0,
        ];

        // Upload material file
        if (isset($_FILES['material_file']) && $_FILES['material_file']['error'] === 0) {
            $ext = pathinfo($_FILES['material_file']['name'], PATHINFO_EXTENSION);
            $filename = 'material_' . time() . '.' . $ext;
            $uploadPath = __DIR__ . '/../../../public/uploads/materials/' . $filename;

            if (!is_dir(dirname($uploadPath))) {
                mkdir(dirname($uploadPath), 0775, true);
            }

            if (move_uploaded_file($_FILES['material_file']['tmp_name'], $uploadPath)) {
                $data[':material_file'] = '/uploads/materials/' . $filename;
            }
        }

        $lessonModel = new Lesson();
        if ($lessonModel->create($data)) {
            $_SESSION['success_message'] = 'Lição criada com sucesso!';
            header('Location: /admin/courses/' . $section['course_id']);
            exit;
        }

        $_SESSION['error_message'] = 'Erro ao criar lição.';
        return $this->create($sectionId);
    }

    public function edit($id)
    {
        $lessonModel = new Lesson();
        $lesson = $lessonModel->find($id);

        if (!$lesson) {
            $_SESSION['error_message'] = 'Lição não encontrada.';
            header('Location: /admin/courses');
            exit;
        }

        return $this->render('lessons/edit', [
            'lesson' => $lesson,
        ]);
    }

    public function update($id)
    {
        $lessonModel = new Lesson();
        $lesson = $lessonModel->find($id);

        if (!$lesson) {
            $_SESSION['error_message'] = 'Lição não encontrada.';
            header('Location: /admin/courses');
            exit;
        }

        $data = [
            ':section_id' => $lesson['section_id'],
            ':title' => $_POST['title'] ?? '',
            ':description' => $_POST['description'] ?? '',
            ':content' => $_POST['content'] ?? '',
            ':video_url' => $_POST['video_url'] ?? '',
            ':video_duration' => $_POST['video_duration'] ?? 0,
            ':material_file' => $lesson['material_file'],
            ':sort_order' => $_POST['sort_order'] ?? 0,
            ':duration_minutes' => $_POST['duration_minutes'] ?? 0,
            ':is_preview' => isset($_POST['is_preview']) ? 1 : 0,
        ];

        // Upload new material file
        if (isset($_FILES['material_file']) && $_FILES['material_file']['error'] === 0) {
            $ext = pathinfo($_FILES['material_file']['name'], PATHINFO_EXTENSION);
            $filename = 'material_' . time() . '.' . $ext;
            $uploadPath = __DIR__ . '/../../../public/uploads/materials/' . $filename;

            if (!is_dir(dirname($uploadPath))) {
                mkdir(dirname($uploadPath), 0775, true);
            }

            if (move_uploaded_file($_FILES['material_file']['tmp_name'], $uploadPath)) {
                if ($lesson['material_file']) {
                    $oldPath = __DIR__ . '/../../../public' . $lesson['material_file'];
                    if (file_exists($oldPath)) unlink($oldPath);
                }
                $data[':material_file'] = '/uploads/materials/' . $filename;
            }
        }

        if ($lessonModel->update($id, $data)) {
            $_SESSION['success_message'] = 'Lição atualizada com sucesso!';
            header('Location: /admin/courses/' . $lesson['course_id']);
            exit;
        }

        $_SESSION['error_message'] = 'Erro ao atualizar lição.';
        return $this->edit($id);
    }

    public function delete($id)
    {
        $lessonModel = new Lesson();
        $lesson = $lessonModel->find($id);

        if (!$lesson) {
            $_SESSION['error_message'] = 'Lição não encontrada.';
            header('Location: /admin/courses');
            exit;
        }

        $courseId = $lesson['course_id'];

        // Delete material file
        if ($lesson['material_file']) {
            $filePath = __DIR__ . '/../../../public' . $lesson['material_file'];
            if (file_exists($filePath)) unlink($filePath);
        }

        if ($lessonModel->delete($id)) {
            $_SESSION['success_message'] = 'Lição deletada com sucesso!';
        } else {
            $_SESSION['error_message'] = 'Erro ao deletar lição.';
        }

        header('Location: /admin/courses/' . $courseId);
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
