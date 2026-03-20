<?php

namespace App\Controllers\Admin;

use App\Models\Student;
use App\Models\School;
use App\Core\Security\Csrf;

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
        Csrf::verify();

        $errors = [];

        // BUG-021: validate name
        $name = trim($_POST['name'] ?? '');
        if (empty($name)) {
            $errors[] = 'O nome do aluno é obrigatório.';
        }

        // BUG-022: validate birth_date
        $birthDate = $_POST['birth_date'] ?? '';
        if (!empty($birthDate)) {
            $d = \DateTime::createFromFormat('Y-m-d', $birthDate);
            if (!$d || $d->format('Y-m-d') !== $birthDate || $d > new \DateTime()) {
                $errors[] = 'Data de nascimento inválida.';
            }
        }

        if (!empty($errors)) {
            $_SESSION['error_message'] = implode(' ', $errors);
            return $this->create();
        }

        $schoolId = (int)($_POST['school_id'] ?? 0);
        if ($schoolId > 0) {
            $db = \App\Core\Database\Connection::getInstance();
            $stmt = $db->prepare("SELECT id FROM schools WHERE id = ? LIMIT 1");
            $stmt->execute([$schoolId]);
            if (!$stmt->fetch()) {
                $_SESSION['error_message'] = 'Escola inválida.';
                header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/admin/students'));
                exit;
            }
        }

        $data = [
            'name' => $name,
            'birth_date' => $birthDate,
            'school_id' => $schoolId ?: null,
            'photo_url' => null
        ];

        // Lógica simples de upload se houver foto
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

            $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $filename = time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
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

        // Buscar turma atual do aluno (via classroom_students pivot)
        $classroom = null;
        try {
            $db = \App\Core\Database\Connection::getInstance();
            $stmt = $db->prepare("
                SELECT c.*, u.name as teacher_name, s.name as school_name
                FROM classroom_students cs
                JOIN classrooms c ON cs.classroom_id = c.id
                LEFT JOIN users u ON c.teacher_id = u.id
                LEFT JOIN schools s ON c.school_id = s.id
                WHERE cs.student_id = ? AND c.status = 'active'
                ORDER BY c.school_year DESC
                LIMIT 1
            ");
            $stmt->execute([$id]);
            $classroom = $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\Exception $e) {
            // Tabela pode não existir ainda
        }

        return $this->render('students/show', [
            'student' => $student,
            'observations' => $observations,
            'age' => $age,
            'classroom' => $classroom
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
        Csrf::verify();

        $studentModel = new Student();
        $student = $studentModel->find($id);

        if (!$student) {
            $_SESSION['error_message'] = 'Aluno não encontrado.';
            header('Location: /admin/students');
            exit;
        }

        $errors = [];

        // BUG-021: validate name
        $name = trim($_POST['name'] ?? '');
        if (empty($name)) {
            $errors[] = 'O nome do aluno é obrigatório.';
        }

        // BUG-022: validate birth_date
        $birthDate = $_POST['birth_date'] ?? '';
        if (!empty($birthDate)) {
            $d = \DateTime::createFromFormat('Y-m-d', $birthDate);
            if (!$d || $d->format('Y-m-d') !== $birthDate || $d > new \DateTime()) {
                $errors[] = 'Data de nascimento inválida.';
            }
        }

        if (!empty($errors)) {
            $_SESSION['error_message'] = implode(' ', $errors);
            return $this->edit($id);
        }

        $schoolId = (int)($_POST['school_id'] ?? 0);
        if ($schoolId > 0) {
            $db = \App\Core\Database\Connection::getInstance();
            $stmt = $db->prepare("SELECT id FROM schools WHERE id = ? LIMIT 1");
            $stmt->execute([$schoolId]);
            if (!$stmt->fetch()) {
                $_SESSION['error_message'] = 'Escola inválida.';
                header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/admin/students'));
                exit;
            }
        }

        $data = [
            'name' => $name,
            'birth_date' => $birthDate,
            'school_id' => $schoolId ?: null,
            'photo_url' => $student['photo_url'] // Mantém foto atual
        ];

        // Upload de nova foto (opcional)
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

            $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $filename = time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
            $uploadPath = __DIR__ . '/../../../public/uploads/students/' . $filename;

            if (!is_dir(dirname($uploadPath))) {
                mkdir(dirname($uploadPath), 0775, true);
            }

            if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadPath)) {
                // Deletar foto antiga se existir
                if ($student['photo_url']) {
                    $oldPhotoPath = __DIR__ . '/../../../public' . $student['photo_url'];
                    if (file_exists($oldPhotoPath) && !unlink($oldPhotoPath)) {
                        error_log('Falha ao remover arquivo: ' . $oldPhotoPath);
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
            if (file_exists($photoPath) && !unlink($photoPath)) {
                error_log('Falha ao remover arquivo: ' . $photoPath);
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