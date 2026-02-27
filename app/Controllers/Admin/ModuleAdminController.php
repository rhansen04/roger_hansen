<?php

namespace App\Controllers\Admin;

use App\Models\Module;
use App\Models\Course;

class ModuleAdminController
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

        $data = [
            ':course_id' => $courseId,
            ':title' => $_POST['title'] ?? '',
            ':description' => $_POST['description'] ?? '',
            ':sort_order' => $_POST['sort_order'] ?? 0,
        ];

        $moduleModel = new Module();
        if ($moduleModel->create($data)) {
            $_SESSION['success_message'] = 'Módulo criado com sucesso!';
        } else {
            $_SESSION['error_message'] = 'Erro ao criar módulo.';
        }

        header('Location: /admin/courses/' . $courseId);
        exit;
    }

    public function update($id)
    {
        $moduleModel = new Module();
        $module = $moduleModel->find($id);

        if (!$module) {
            $_SESSION['error_message'] = 'Módulo não encontrado.';
            header('Location: /admin/courses');
            exit;
        }

        $data = [
            ':title' => $_POST['title'] ?? '',
            ':description' => $_POST['description'] ?? '',
            ':sort_order' => $_POST['sort_order'] ?? 0,
        ];

        if ($moduleModel->update($id, $data)) {
            $_SESSION['success_message'] = 'Módulo atualizado com sucesso!';
        } else {
            $_SESSION['error_message'] = 'Erro ao atualizar módulo.';
        }

        header('Location: /admin/courses/' . $module['course_id']);
        exit;
    }

    public function reorder($id)
    {
        $moduleModel = new Module();
        $module = $moduleModel->find($id);

        if (!$module) {
            http_response_code(404);
            echo json_encode(['error' => 'Módulo não encontrado']);
            return;
        }

        $direction = $_POST['direction'] ?? '';
        $modules = $moduleModel->getByCourse($module['course_id']);

        $currentIndex = null;
        foreach ($modules as $i => $m) {
            if ($m['id'] == $id) { $currentIndex = $i; break; }
        }

        $targetIndex = $direction === 'up' ? $currentIndex - 1 : $currentIndex + 1;

        if ($targetIndex < 0 || $targetIndex >= count($modules)) {
            http_response_code(400);
            echo json_encode(['error' => 'Limite atingido']);
            return;
        }

        $currentOrder = $modules[$currentIndex]['sort_order'];
        $targetOrder = $modules[$targetIndex]['sort_order'];

        $moduleModel->updateSortOrder($modules[$currentIndex]['id'], $targetOrder);
        $moduleModel->updateSortOrder($modules[$targetIndex]['id'], $currentOrder);

        echo json_encode(['success' => true]);
    }

    public function delete($id)
    {
        $moduleModel = new Module();
        $module = $moduleModel->find($id);

        if (!$module) {
            $_SESSION['error_message'] = 'Módulo não encontrado.';
            header('Location: /admin/courses');
            exit;
        }

        $courseId = $module['course_id'];

        if ($moduleModel->delete($id)) {
            $_SESSION['success_message'] = 'Módulo deletado com sucesso! As seções foram desvinculadas.';
        } else {
            $_SESSION['error_message'] = 'Erro ao deletar módulo.';
        }

        header('Location: /admin/courses/' . $courseId);
        exit;
    }
}
