<?php

namespace App\Controllers\Admin;

use App\Models\Classroom;
use App\Models\School;
use App\Models\User;

class ClassroomController
{
    public function index()
    {
        $model = new Classroom();
        $classrooms = $model->all();

        return $this->render('classrooms/index', [
            'classrooms' => $classrooms
        ]);
    }

    public function create()
    {
        $schoolModel = new School();
        $userModel = new User();
        $schools = $schoolModel->all();
        $teachers = $userModel->all();

        return $this->render('classrooms/form', [
            'classroom' => null,
            'schools' => $schools,
            'teachers' => $teachers
        ]);
    }

    public function store()
    {
        if (empty($_POST['name']) || empty($_POST['school_id']) || empty($_POST['teacher_id'])) {
            $_SESSION['error_message'] = 'Preencha todos os campos obrigatórios.';
            header('Location: /admin/classrooms/create');
            exit;
        }

        $model = new Classroom();
        if ($model->create($_POST)) {
            $_SESSION['success_message'] = 'Turma criada com sucesso!';
            header('Location: /admin/classrooms');
            exit;
        }

        $_SESSION['error_message'] = 'Erro ao criar turma.';
        header('Location: /admin/classrooms/create');
        exit;
    }

    public function edit($id)
    {
        $model = new Classroom();
        $classroom = $model->find($id);

        if (!$classroom) {
            $_SESSION['error_message'] = 'Turma não encontrada.';
            header('Location: /admin/classrooms');
            exit;
        }

        $schoolModel = new School();
        $userModel = new User();
        $schools = $schoolModel->all();
        $teachers = $userModel->all();

        return $this->render('classrooms/form', [
            'classroom' => $classroom,
            'schools' => $schools,
            'teachers' => $teachers
        ]);
    }

    public function update($id)
    {
        $model = new Classroom();
        $classroom = $model->find($id);

        if (!$classroom) {
            $_SESSION['error_message'] = 'Turma não encontrada.';
            header('Location: /admin/classrooms');
            exit;
        }

        if (empty($_POST['name']) || empty($_POST['school_id']) || empty($_POST['teacher_id'])) {
            $_SESSION['error_message'] = 'Preencha todos os campos obrigatórios.';
            header("Location: /admin/classrooms/{$id}/edit");
            exit;
        }

        if ($model->update($id, $_POST)) {
            $_SESSION['success_message'] = 'Turma atualizada com sucesso!';
            header('Location: /admin/classrooms');
            exit;
        }

        $_SESSION['error_message'] = 'Erro ao atualizar turma.';
        header("Location: /admin/classrooms/{$id}/edit");
        exit;
    }

    public function delete($id)
    {
        $model = new Classroom();
        if ($model->delete($id)) {
            $_SESSION['success_message'] = 'Turma excluída com sucesso!';
        } else {
            $_SESSION['error_message'] = 'Erro ao excluir turma.';
        }
        header('Location: /admin/classrooms');
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
