<?php

namespace App\Controllers\Admin;

use App\Models\Student;
use App\Models\School;

class StudentController
{
    public function index()
    {
        $studentModel = new Student();
        $students = $studentModel->all();
        
        return $this->render('students/index', ['students' => $students]);
    }

    public function create()
    {
        $schoolModel = new School();
        $schools = $schoolModel->all();
        
        return $this->render('students/create', ['schools' => $schools]);
    }

    public function store()
    {
        $data = [
            'name' => $_POST['name'] ?? '',
            'birth_date' => $_POST['birth_date'] ?? '',
            'school_id' => $_POST['school_id'] ?? null,
            'photo_url' => null
        ];

        // Lógica simples de upload se houver foto
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
            $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $filename = time() . '.' . $ext;
            $uploadPath = __DIR__ . '/../../../public/uploads/students/' . $filename;

            if (!is_dir(dirname($uploadPath))) {
                mkdir(dirname($uploadPath), 0775, true);
            }

            if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath)) {
                $data['photo_url'] = '/uploads/students/' . $filename;
            }
        }

        $studentModel = new Student();
        if ($studentModel->create($data)) {
            $_SESSION['success_message'] = 'Aluno cadastrado com sucesso!';
            header('Location: /admin/students');
            exit;
        }

        $_SESSION['error_message'] = 'Erro ao cadastrar aluno. Tente novamente.';
        return $this->create();
    }

    public function show($id)
    {
        $studentModel = new Student();
        $student = $studentModel->find($id);

        if (!$student) {
            $_SESSION['error_message'] = 'Aluno não encontrado.';
            header('Location: /admin/students');
            exit;
        }

        // Buscar observações do aluno
        $observationModel = new \App\Models\Observation();
        $observations = $observationModel->findByStudent($id);

        // Calcular idade
        $birthDate = new \DateTime($student['birth_date']);
        $today = new \DateTime();
        $age = $today->diff($birthDate)->y;

        return $this->render('students/show', [
            'student' => $student,
            'observations' => $observations,
            'age' => $age
        ]);
    }

    public function edit($id)
    {
        $studentModel = new Student();
        $student = $studentModel->find($id);

        if (!$student) {
            $_SESSION['error_message'] = 'Aluno não encontrado.';
            header('Location: /admin/students');
            exit;
        }

        $schoolModel = new School();
        $schools = $schoolModel->all();

        return $this->render('students/edit', [
            'student' => $student,
            'schools' => $schools
        ]);
    }

    public function update($id)
    {
        $studentModel = new Student();
        $student = $studentModel->find($id);

        if (!$student) {
            $_SESSION['error_message'] = 'Aluno não encontrado.';
            header('Location: /admin/students');
            exit;
        }

        $data = [
            'name' => $_POST['name'] ?? '',
            'birth_date' => $_POST['birth_date'] ?? '',
            'school_id' => $_POST['school_id'] ?? null,
            'photo_url' => $student['photo_url'] // Mantém foto atual
        ];

        // Upload de nova foto (opcional)
        if (isset($_FILES['photo']) && $_FILES['photo']['error'] === 0) {
            $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $filename = time() . '.' . $ext;
            $uploadPath = __DIR__ . '/../../../public/uploads/students/' . $filename;

            if (!is_dir(dirname($uploadPath))) {
                mkdir(dirname($uploadPath), 0775, true);
            }

            if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath)) {
                // Deletar foto antiga se existir
                if ($student['photo_url']) {
                    $oldPhotoPath = __DIR__ . '/../../../public' . $student['photo_url'];
                    if (file_exists($oldPhotoPath)) {
                        unlink($oldPhotoPath);
                    }
                }
                $data['photo_url'] = '/uploads/students/' . $filename;
            }
        }

        if ($studentModel->update($id, $data)) {
            $_SESSION['success_message'] = 'Aluno atualizado com sucesso!';
            header('Location: /admin/students/' . $id);
            exit;
        }

        $_SESSION['error_message'] = 'Erro ao atualizar aluno. Tente novamente.';
        return $this->edit($id);
    }

    public function delete($id)
    {
        $studentModel = new Student();
        $student = $studentModel->find($id);

        if (!$student) {
            $_SESSION['error_message'] = 'Aluno não encontrado.';
            header('Location: /admin/students');
            exit;
        }

        // Verificar se tem observações
        $observationModel = new \App\Models\Observation();
        $observations = $observationModel->findByStudent($id);
        $observationCount = count($observations);

        if ($observationCount > 0) {
            $_SESSION['error_message'] = "Não é possível deletar o aluno {$student['name']}. Existem {$observationCount} observação(ões) vinculada(s). Delete as observações primeiro.";
            header('Location: /admin/students');
            exit;
        }

        // Deletar foto se existir
        if ($student['photo_url']) {
            $photoPath = __DIR__ . '/../../../public' . $student['photo_url'];
            if (file_exists($photoPath)) {
                unlink($photoPath);
            }
        }

        if ($studentModel->delete($id)) {
            $_SESSION['success_message'] = 'Aluno deletado com sucesso!';
        } else {
            $_SESSION['error_message'] = 'Erro ao deletar aluno. Tente novamente.';
        }

        header('Location: /admin/students');
        exit;
    }

    /**
     * Gerar resumo pedagógico com IA (Gemini)
     */
    public function generateAiSummary($id)
    {
        header('Content-Type: application/json');

        try {
            $geminiService = new \App\Services\GeminiService();
            $result = $geminiService->generateStudentSummary($id);

            echo json_encode([
                'success' => true,
                'data' => $result
            ]);
        } catch (\Exception $e) {
            http_response_code(500);
            echo json_encode([
                'success' => false,
                'error' => $e->getMessage()
            ]);
        }
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