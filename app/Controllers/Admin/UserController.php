<?php

namespace App\Controllers\Admin;

use App\Models\User;

class UserController
{
    /**
     * Listar todos os usuários
     */
    public function index()
    {
        $userModel = new User();
        $users = $userModel->all();

        return $this->render('users/index', ['users' => $users]);
    }

    /**
     * Formulário para criar novo usuário
     */
    public function create()
    {
        return $this->render('users/create');
    }

    /**
     * Salvar novo usuário
     */
    public function store()
    {
        // Validação básica
        $errors = [];

        if (empty($_POST['name'])) {
            $errors[] = 'O nome é obrigatório.';
        }

        if (empty($_POST['email'])) {
            $errors[] = 'O email é obrigatório.';
        } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email inválido.';
        }

        if (empty($_POST['password'])) {
            $errors[] = 'A senha é obrigatória.';
        } elseif (strlen($_POST['password']) < 6) {
            $errors[] = 'A senha deve ter no mínimo 6 caracteres.';
        }

        if (empty($_POST['role'])) {
            $errors[] = 'O perfil é obrigatório.';
        } elseif (!in_array($_POST['role'], ['admin', 'professor', 'coordenador', 'student', 'parent'])) {
            $errors[] = 'Perfil inválido.';
        }

        // Verificar se email já existe
        if (empty($errors)) {
            $userModel = new User();
            $existingUser = $userModel->findByEmail($_POST['email']);

            if ($existingUser) {
                $errors[] = 'Este email já está cadastrado no sistema.';
            }
        }

        if (!empty($errors)) {
            $_SESSION['error_message'] = implode('<br>', $errors);
            $_SESSION['old_input'] = $_POST;
            header('Location: /admin/users/create');
            exit;
        }

        $data = [
            'name' => trim($_POST['name']),
            'email' => trim($_POST['email']),
            'password' => $_POST['password'],
            'role' => $_POST['role']
        ];

        $userModel = new User();
        if ($userModel->create($data)) {
            $_SESSION['success_message'] = 'Usuário cadastrado com sucesso!';
            header('Location: /admin/users');
            exit;
        }

        $_SESSION['error_message'] = 'Erro ao cadastrar usuário. Tente novamente.';
        header('Location: /admin/users/create');
        exit;
    }

    /**
     * Formulário para editar usuário
     */
    public function edit($id)
    {
        $userModel = new User();
        $user = $userModel->find($id);

        if (!$user) {
            $_SESSION['error_message'] = 'Usuário não encontrado.';
            header('Location: /admin/users');
            exit;
        }

        return $this->render('users/edit', ['user' => $user]);
    }

    /**
     * Atualizar usuário
     */
    public function update($id)
    {
        $userModel = new User();
        $user = $userModel->find($id);

        if (!$user) {
            $_SESSION['error_message'] = 'Usuário não encontrado.';
            header('Location: /admin/users');
            exit;
        }

        // Validação básica
        $errors = [];

        if (empty($_POST['name'])) {
            $errors[] = 'O nome é obrigatório.';
        }

        if (empty($_POST['email'])) {
            $errors[] = 'O email é obrigatório.';
        } elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email inválido.';
        }

        if (empty($_POST['role'])) {
            $errors[] = 'O perfil é obrigatório.';
        } elseif (!in_array($_POST['role'], ['admin', 'professor', 'coordenador', 'student', 'parent'])) {
            $errors[] = 'Perfil inválido.';
        }

        // Validar senha se fornecida
        if (!empty($_POST['password']) && strlen($_POST['password']) < 6) {
            $errors[] = 'A senha deve ter no mínimo 6 caracteres.';
        }

        // Verificar se email já existe (exceto para o próprio usuário)
        if (empty($errors) && $_POST['email'] !== $user['email']) {
            $existingUser = $userModel->findByEmail($_POST['email']);

            if ($existingUser && $existingUser['id'] != $id) {
                $errors[] = 'Este email já está cadastrado no sistema.';
            }
        }

        if (!empty($errors)) {
            $_SESSION['error_message'] = implode('<br>', $errors);
            $_SESSION['old_input'] = $_POST;
            header('Location: /admin/users/' . $id . '/edit');
            exit;
        }

        $data = [
            'name' => trim($_POST['name']),
            'email' => trim($_POST['email']),
            'role' => $_POST['role']
        ];

        // Incluir senha apenas se foi fornecida
        if (!empty($_POST['password'])) {
            $data['password'] = $_POST['password'];
        }

        if ($userModel->update($id, $data)) {
            $_SESSION['success_message'] = 'Usuário atualizado com sucesso!';
            header('Location: /admin/users');
            exit;
        }

        $_SESSION['error_message'] = 'Erro ao atualizar usuário. Tente novamente.';
        header('Location: /admin/users/' . $id . '/edit');
        exit;
    }

    /**
     * Deletar usuário
     */
    public function delete($id)
    {
        $userModel = new User();
        $user = $userModel->find($id);

        if (!$user) {
            $_SESSION['error_message'] = 'Usuário não encontrado.';
            header('Location: /admin/users');
            exit;
        }

        // Não permitir deletar próprio usuário
        if ($id == $_SESSION['user_id']) {
            $_SESSION['error_message'] = 'Você não pode deletar seu próprio usuário.';
            header('Location: /admin/users');
            exit;
        }

        // Não permitir deletar o último admin
        if ($user['role'] === 'admin') {
            $adminCount = $userModel->countByRole('admin');
            if ($adminCount <= 1) {
                $_SESSION['error_message'] = 'Não é possível deletar o último administrador do sistema.';
                header('Location: /admin/users');
                exit;
            }
        }

        if ($userModel->delete($id)) {
            $_SESSION['success_message'] = 'Usuário deletado com sucesso!';
        } else {
            $_SESSION['error_message'] = 'Erro ao deletar usuário. Tente novamente.';
        }

        header('Location: /admin/users');
        exit;
    }

    /**
     * Renderizar view
     */
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
