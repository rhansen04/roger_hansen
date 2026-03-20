<?php

namespace App\Controllers\Admin;

use App\Models\Classroom;
use App\Models\School;
use App\Models\Student;
use App\Models\User;
use App\Core\Security\Csrf;

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
        Csrf::verify();

        if (empty($_POST['name']) || empty($_POST['school_id']) || empty($_POST['teacher_id'])) {
            $_SESSION['error_message'] = 'Preencha todos os campos obrigatórios.';
            header('Location: /admin/classrooms/create');
            exit;
        }

        // BUG-030: verify teacher_id belongs to a user with role='professor'
        $teacherId = (int) $_POST['teacher_id'];
        $db = \App\Core\Database\Connection::getInstance();
        $stmt = $db->prepare("SELECT id FROM users WHERE id = ? AND role = 'professor' LIMIT 1");
        $stmt->execute([$teacherId]);
        if (!$stmt->fetch()) {
            $_SESSION['error_message'] = 'Professor inválido.';
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/admin/classrooms'));
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
        Csrf::verify();

        $model = new Classroom();
        $classroom = $model->find($id);

        if (!$classroom) {
            $_SESSION['error_message'] = 'Turma não encontrada.';
            header('Location: /admin/classrooms');
            exit;
        }

        // Novo fluxo: cadastrar aluno e vincular à turma
        if (!empty($_POST['create_new'])) {
            if (empty($_POST['name']) || empty($_POST['birth_date'])) {
                $_SESSION['error_message'] = 'Nome e Data de Nascimento são obrigatórios.';
                header("Location: /admin/classrooms/{$id}");
                exit;
            }

            $studentData = [
                'name' => $_POST['name'],
                'birth_date' => $_POST['birth_date'],
                'school_id' => $classroom['school_id'] ?? null,
                'photo_url' => null
            ];

            // Upload de foto
            if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
                $maxSize = 5 * 1024 * 1024; // 5 MB
                if ($_FILES['photo']['size'] > $maxSize) {
                    $_SESSION['error_message'] = 'Arquivo muito grande. Máximo 5MB.';
                    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/admin'));
                    exit;
                }

                $finfo = new \finfo(FILEINFO_MIME_TYPE);
                $mime = $finfo->file($_FILES['photo']['tmp_name']);
                $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                if (!in_array($mime, $allowedMimes)) {
                    $_SESSION['error_message'] = 'Tipo de arquivo não permitido.';
                    header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/admin'));
                    exit;
                }

                $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
                $filename = time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
                $uploadPath = __DIR__ . '/../../../public/uploads/students/' . $filename;

                if (!is_dir(dirname($uploadPath))) {
                    mkdir(dirname($uploadPath), 0775, true);
                }

                if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath)) {
                    $studentData['photo_url'] = '/uploads/students/' . $filename;
                }
            }

            $studentModel = new Student();
            $studentId = $studentModel->create($studentData);

            if (!$studentId) {
                $_SESSION['error_message'] = 'Erro ao cadastrar aluno.';
                header("Location: /admin/classrooms/{$id}");
                exit;
            }

            if ($model->addStudent($id, $studentId)) {
                $_SESSION['success_message'] = 'Aluno cadastrado e adicionado à turma com sucesso!';
            } else {
                $_SESSION['error_message'] = 'Aluno cadastrado, mas erro ao vincular à turma.';
            }

            header("Location: /admin/classrooms/{$id}");
            exit;
        }

        // Fluxo legado: vincular aluno existente
        $studentId = $_POST['student_id'] ?? null;
        // BUG-032: use integer check instead of empty()
        if ((int)$studentId <= 0) {
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
        Csrf::verify();

        // BUG-032: use integer check instead of empty()
        if ((int)$id <= 0) {
            $_SESSION['error_message'] = 'Turma inválida.';
            header('Location: /admin/classrooms');
            exit;
        }

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

        // BUG-030: verify teacher_id belongs to a user with role='professor'
        $teacherId = (int) $_POST['teacher_id'];
        $db = \App\Core\Database\Connection::getInstance();
        $stmt = $db->prepare("SELECT id FROM users WHERE id = ? AND role = 'professor' LIMIT 1");
        $stmt->execute([$teacherId]);
        if (!$stmt->fetch()) {
            $_SESSION['error_message'] = 'Professor inválido.';
            header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/admin/classrooms'));
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
