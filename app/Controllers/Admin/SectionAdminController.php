<?php

namespace App\Controllers\Admin;

use App\Models\Section;
use App\Models\Course;

class SectionAdminController
{
    public function store($courseId)
    {
        $courseModel = new Course();
        $course = $courseModel->find($courseId);

        if (!$course) {
            $_SESSION['error_message'] = 'Curso não encontrado.';
            header('Location: /admin/courses');
            exit;
        }

        $moduleId = !empty($_POST['module_id']) ? $_POST['module_id'] : null;
        $data = [
            ':course_id' => $courseId,
            ':module_id' => $moduleId,
            ':title' => $_POST['title'] ?? '',
            ':description' => $_POST['description'] ?? '',
            ':sort_order' => $_POST['sort_order'] ?? 0,
        ];

        $sectionModel = new Section();
        if ($sectionModel->create($data)) {
            $_SESSION['success_message'] = 'Seção criada com sucesso!';
        } else {
            $_SESSION['error_message'] = 'Erro ao criar seção.';
        }

        header('Location: /admin/courses/' . $courseId);
        exit;
    }

    public function update($id)
    {
        $sectionModel = new Section();
        $section = $sectionModel->find($id);

        if (!$section) {
            $_SESSION['error_message'] = 'Seção não encontrada.';
            header('Location: /admin/courses');
            exit;
        }

        $moduleId = !empty($_POST['module_id']) ? $_POST['module_id'] : null;
        $data = [
            ':course_id' => $section['course_id'],
            ':module_id' => $moduleId,
            ':title' => $_POST['title'] ?? '',
            ':description' => $_POST['description'] ?? '',
            ':sort_order' => $_POST['sort_order'] ?? 0,
        ];

        if ($sectionModel->update($id, $data)) {
            $_SESSION['success_message'] = 'Seção atualizada com sucesso!';
        } else {
            $_SESSION['error_message'] = 'Erro ao atualizar seção.';
        }

        header('Location: /admin/courses/' . $section['course_id']);
        exit;
    }

    public function reorder($id)
    {
        $sectionModel = new Section();
        $section = $sectionModel->find($id);

        if (!$section) {
            http_response_code(404);
            echo json_encode(['error' => 'Seção não encontrada']);
            return;
        }

        $direction = $_POST['direction'] ?? '';
        $courseId = $section['course_id'];
        $sections = $sectionModel->getByCourse($courseId);

        $currentIndex = null;
        foreach ($sections as $i => $s) {
            if ($s['id'] == $id) { $currentIndex = $i; break; }
        }

        $targetIndex = $direction === 'up' ? $currentIndex - 1 : $currentIndex + 1;

        if ($targetIndex < 0 || $targetIndex >= count($sections)) {
            http_response_code(400);
            echo json_encode(['error' => 'Limite atingido']);
            return;
        }

        $currentOrder = $sections[$currentIndex]['sort_order'];
        $targetOrder = $sections[$targetIndex]['sort_order'];

        $sectionModel->updateSortOrder($sections[$currentIndex]['id'], $targetOrder);
        $sectionModel->updateSortOrder($sections[$targetIndex]['id'], $currentOrder);

        echo json_encode(['success' => true]);
    }

    public function delete($id)
    {
        $sectionModel = new Section();
        $section = $sectionModel->find($id);

        if (!$section) {
            $_SESSION['error_message'] = 'Seção não encontrada.';
            header('Location: /admin/courses');
            exit;
        }

        $courseId = $section['course_id'];

        if ($sectionModel->delete($id)) {
            $_SESSION['success_message'] = 'Seção deletada com sucesso!';
        } else {
            $_SESSION['error_message'] = 'Erro ao deletar seção.';
        }

        header('Location: /admin/courses/' . $courseId);
        exit;
    }
}
