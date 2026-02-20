<?php

namespace App\Controllers;

use App\Models\User;
use App\Core\Database\Connection;
use App\Services\MailerService;

class AuthController
{
    public function showLogin()
    {
        if (isset($_SESSION['user_id'])) {
            return $this->redirectByRole($_SESSION['user_role'] ?? 'student');
        }
        return $this->render('login');
    }

    public function login()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];

            $userModel->updateLastLogin($user['id']);

            $this->redirectByRole($user['role']);
            exit;
        }

        return $this->render('login', ['error' => 'E-mail ou senha inválidos.']);
    }

    public function logout()
    {
        session_destroy();
        header('Location: /login');
        exit;
    }

    // --- Registro ---

    public function showRegister()
    {
        if (isset($_SESSION['user_id'])) {
            return $this->redirectByRole($_SESSION['user_role'] ?? 'student');
        }
        return $this->render('register');
    }

    public function register()
    {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        // Validations
        $errors = [];
        if (empty($name)) $errors[] = 'Nome é obrigatório.';
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'E-mail inválido.';
        if (strlen($password) < 6) $errors[] = 'Senha deve ter pelo menos 6 caracteres.';
        if ($password !== $passwordConfirm) $errors[] = 'Senhas não conferem.';

        // Check unique email
        $userModel = new User();
        if ($userModel->findByEmail($email)) {
            $errors[] = 'Este e-mail já está cadastrado.';
        }

        if (!empty($errors)) {
            return $this->render('register', [
                'errors' => $errors,
                'old' => ['name' => $name, 'email' => $email],
            ]);
        }

        $result = $userModel->create([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'role' => 'student',
        ]);

        if ($result) {
            $_SESSION['success_message'] = 'Conta criada com sucesso! Faça login para continuar.';
            header('Location: /login');
            exit;
        }

        return $this->render('register', [
            'errors' => ['Erro ao criar conta. Tente novamente.'],
            'old' => ['name' => $name, 'email' => $email],
        ]);
    }

    // --- Recuperação de Senha ---

    public function showForgotPassword()
    {
        return $this->render('forgot-password');
    }

    public function sendResetLink()
    {
        $email = trim($_POST['email'] ?? '');

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->render('forgot-password', ['error' => 'Informe um e-mail válido.']);
        }

        $userModel = new User();
        $user = $userModel->findByEmail($email);

        // Always show success (prevent email enumeration)
        if ($user) {
            $token = bin2hex(random_bytes(32));
            $db = Connection::getInstance();

            // Delete old tokens for this email
            $stmt = $db->prepare("DELETE FROM password_resets WHERE email = ?");
            $stmt->execute([$email]);

            // Insert new token
            $stmt = $db->prepare("INSERT INTO password_resets (email, token) VALUES (?, ?)");
            $stmt->execute([$email, $token]);

            // Send email
            $mailer = new MailerService();
            $mailer->sendPasswordReset($email, $token);
        }

        return $this->render('forgot-password', [
            'success' => 'Se o e-mail estiver cadastrado, você receberá um link para redefinir sua senha.',
        ]);
    }

    public function showResetForm($token)
    {
        $db = Connection::getInstance();
        $stmt = $db->prepare("SELECT * FROM password_resets WHERE token = ? AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR) LIMIT 1");
        $stmt->execute([$token]);
        $reset = $stmt->fetch();

        if (!$reset) {
            $_SESSION['error_message'] = 'Link inválido ou expirado. Solicite um novo.';
            header('Location: /esqueci-senha');
            exit;
        }

        return $this->render('reset-password', ['token' => $token]);
    }

    public function resetPassword()
    {
        $token = $_POST['token'] ?? '';
        $password = $_POST['password'] ?? '';
        $passwordConfirm = $_POST['password_confirm'] ?? '';

        if (strlen($password) < 6) {
            return $this->render('reset-password', [
                'token' => $token,
                'error' => 'Senha deve ter pelo menos 6 caracteres.',
            ]);
        }

        if ($password !== $passwordConfirm) {
            return $this->render('reset-password', [
                'token' => $token,
                'error' => 'Senhas não conferem.',
            ]);
        }

        $db = Connection::getInstance();
        $stmt = $db->prepare("SELECT * FROM password_resets WHERE token = ? AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR) LIMIT 1");
        $stmt->execute([$token]);
        $reset = $stmt->fetch();

        if (!$reset) {
            $_SESSION['error_message'] = 'Link inválido ou expirado.';
            header('Location: /esqueci-senha');
            exit;
        }

        // Update password
        $userModel = new User();
        $user = $userModel->findByEmail($reset['email']);
        if ($user) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $db->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->execute([$hashedPassword, $user['id']]);
        }

        // Delete used token
        $stmt = $db->prepare("DELETE FROM password_resets WHERE email = ?");
        $stmt->execute([$reset['email']]);

        $_SESSION['success_message'] = 'Senha redefinida com sucesso! Faça login com a nova senha.';
        header('Location: /login');
        exit;
    }

    private function redirectByRole($role)
    {
        switch ($role) {
            case 'student':
                header('Location: /minha-conta');
                break;
            case 'parent':
                header('Location: /minha-area');
                break;
            default:
                header('Location: /admin/dashboard');
                break;
        }
        exit;
    }

    protected function render($view, $data = [])
    {
        extract($data);
        ob_start();
        include __DIR__ . "/../../views/pages/{$view}.php";
        $content = ob_get_clean();
        include __DIR__ . "/../../views/layouts/public.php";
    }
}
