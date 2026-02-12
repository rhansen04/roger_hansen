<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Enrollment;
use App\Models\Course;
use App\Core\Database\Connection;

class StudentPanelController
{
    public function dashboard()
    {
        $this->requireStudent();

        $userId = $_SESSION['user_id'];
        $enrollmentModel = new Enrollment();
        $enrollments = $enrollmentModel->getByUser($userId);

        // Enrich with course data
        $courseModel = new Course();
        foreach ($enrollments as &$enrollment) {
            $course = $courseModel->find($enrollment['course_id']);
            $enrollment['course'] = $course;
        }

        return $this->render('dashboard', ['enrollments' => $enrollments]);
    }

    public function profile()
    {
        $this->requireStudent();

        $userModel = new User();
        $user = $userModel->find($_SESSION['user_id']);

        return $this->render('profile', ['user' => $user]);
    }

    public function updateProfile()
    {
        $this->requireStudent();

        $userId = $_SESSION['user_id'];
        $userModel = new User();
        $user = $userModel->find($userId);

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');

        $errors = [];
        if (empty($name)) $errors[] = 'Nome é obrigatório.';
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'E-mail inválido.';

        // Check email unique (exclude self)
        $existing = $userModel->findByEmail($email);
        if ($existing && $existing['id'] != $userId) {
            $errors[] = 'Este e-mail já está em uso.';
        }

        if (!empty($errors)) {
            return $this->render('profile', ['user' => $user, 'errors' => $errors]);
        }

        $db = Connection::getInstance();
        $stmt = $db->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
        $stmt->execute([$name, $email, $userId]);

        $_SESSION['user_name'] = $name;
        $_SESSION['success_message'] = 'Perfil atualizado com sucesso!';
        header('Location: /minha-conta/perfil');
        exit;
    }

    public function changePassword()
    {
        $this->requireStudent();

        $userId = $_SESSION['user_id'];
        $userModel = new User();
        $user = $userModel->find($userId);

        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (!password_verify($currentPassword, $user['password'])) {
            return $this->render('profile', ['user' => $user, 'errors' => ['Senha atual incorreta.']]);
        }

        if (strlen($newPassword) < 6) {
            return $this->render('profile', ['user' => $user, 'errors' => ['Nova senha deve ter pelo menos 6 caracteres.']]);
        }

        if ($newPassword !== $confirmPassword) {
            return $this->render('profile', ['user' => $user, 'errors' => ['Senhas não conferem.']]);
        }

        $db = Connection::getInstance();
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([$hashedPassword, $userId]);

        $_SESSION['success_message'] = 'Senha alterada com sucesso!';
        header('Location: /minha-conta/perfil');
        exit;
    }

    private function requireStudent()
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }
    }

    protected function render($view, $data = [])
    {
        extract($data);
        $viewFile = __DIR__ . "/../../views/student/{$view}.php";

        ob_start();
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            echo "<h2>Página {$view} em construção</h2>";
        }
        $content = ob_get_clean();

        include __DIR__ . "/../../views/layouts/student.php";
    }
}
