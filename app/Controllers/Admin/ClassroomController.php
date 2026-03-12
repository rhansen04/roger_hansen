<?php

namespace App\Controllers\Admin;

use App\Models\Classroom;
use App\Models\School;
use App\Models\Student;
use App\Models\User;

class ClassroomController
{
    public function index()
    {
        $model = new Classroom();
        $classrooms = $model->all();
        $studentCounts = $model->countStudentsByClassroom();

        return $this->render('classrooms/index', [
            'classrooms' => $classrooms,
            'studentCounts' => $studentCounts
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

    public function show($id)
    {
        $model = new Classroom();
        $classroom = $model->find($id);

        if (!$classroom) {
            $_SESSION['error_message'] = 'Turma não encontrada.';
            header('Location: /admin/classrooms');
            exit;
        }

        $students = $model->students($id);
        $availableStudents = $model->availableStudents($id);

        return $this->render('classrooms/show', [
            'classroom' => $classroom,
            'students' => $students,
            'availableStudents' => $availableStudents
        ]);
    }

    public function addStudent($id)
    {
        $model = new Classroom();
        $classroom = $model->find($id);

        if (!$classroom) {
            $_SESSION['error_message'] = 'Turma não encontrada.';
            header('Location: /admin/classrooms');
            exit;
        }

        $studentId = $_POST['student_id'] ?? null;
        if (empty($studentId)) {
            $_SESSION['error_message'] = 'Selecione um aluno.';
            header("Location: /admin/classrooms/{$id}");
            exit;
        }

        if ($model->addStudent($id, $studentId)) {
            $_SESSION['success_message'] = 'Aluno adicionado à turma com sucesso!';
        } else {
            $_SESSION['error_message'] = 'Erro ao adicionar aluno à turma.';
        }

        header("Location: /admin/classrooms/{$id}");
        exit;
    }

    public function removeStudent($id)
    {
        $model = new Classroom();
        $classroom = $model->find($id);

        if (!$classroom) {
            $_SESSION['error_message'] = 'Turma não encontrada.';
            header('Location: /admin/classrooms');
            exit;
        }

        $studentId = $_POST['student_id'] ?? null;
        if (empty($studentId)) {
            $_SESSION['error_message'] = 'Aluno não informado.';
            header("Location: /admin/classrooms/{$id}");
            exit;
        }

        if ($model->removeStudent($id, $studentId)) {
            $_SESSION['success_message'] = 'Aluno removido da turma com sucesso!';
        } else {
            $_SESSION['error_message'] = 'Erro ao remover aluno da turma.';
        }

        header("Location: /admin/classrooms/{$id}");
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

    public function toggleStatus($id)
    {
        $model = new Classroom();
        $classroom = $model->find($id);

        if (!$classroom) {
            $_SESSION['error_message'] = 'Turma não encontrada.';
            header('Location: /admin/classrooms');
            exit;
        }

        if ($model->toggleStatus($id)) {
            $newStatus = $classroom['status'] === 'active' ? 'desativada' : 'ativada';
            $_SESSION['success_message'] = "Turma {$newStatus} com sucesso!";
        } else {
            $_SESSION['error_message'] = 'Erro ao alterar status da turma.';
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
