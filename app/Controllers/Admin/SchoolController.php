<?php

namespace App\Controllers\Admin;

use App\Models\School;
use App\Core\Security\Csrf;

class SchoolController
{
    /**
     * Listar todas as escolas
     */
    public function index()
    {
        $schoolModel = new School();
        $schools = $schoolModel->allWithStudentsCount();

        return $this->render('schools/index', ['schools' => $schools]);
    }

    /**
     * Ver detalhes de uma escola
     */
    public function show($id)
    {
        $schoolModel = new School();
        $school = $schoolModel->findWithStudentsCount($id);

        if (!$school) {
            $_SESSION['error_message'] = 'Escola não encontrada.';
            header('Location: /admin/schools');
            exit;
        }

        $students = $schoolModel->getStudents($id);

        return $this->render('schools/show', [
            'school' => $school,
            'students' => $students
        ]);
    }

    /**
     * Formulário para criar nova escola
     */
    public function create()
    {
        return $this->render('schools/create');
    }

    /**
     * Salvar nova escola
     */
    public function store()
    {
        Csrf::verify();

        $errors = [];

        // Validação básica
        if (empty($_POST['name'])) {
            $errors[] = 'O nome da escola é obrigatório.';
        }

        // BUG-026: validate email
        $email = $_POST['email'] ?? null;
        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'E-mail inválido.';
        }

        // BUG-027: validate contract dates
        $contractStart = $_POST['contract_start_date'] ?? null;
        $contractEnd   = $_POST['contract_end_date'] ?? null;
        if (!empty($contractStart) && !empty($contractEnd)) {
            $dStart = \DateTime::createFromFormat('Y-m-d', $contractStart);
            $dEnd   = \DateTime::createFromFormat('Y-m-d', $contractEnd);
            if (!$dStart || $dStart->format('Y-m-d') !== $contractStart) {
                $errors[] = 'Data de início do contrato inválida.';
            } elseif (!$dEnd || $dEnd->format('Y-m-d') !== $contractEnd) {
                $errors[] = 'Data de término do contrato inválida.';
            } elseif ($dStart > $dEnd) {
                $errors[] = 'A data de início do contrato deve ser anterior à data de término.';
            }
        }

        if (!empty($errors)) {
            $_SESSION['error_message'] = implode(' ', $errors);
            header('Location: /admin/schools/create');
            exit;
        }

        $data = [
            'name' => $_POST['name'] ?? '',
            'city' => $_POST['city'] ?? null,
            'state' => $_POST['state'] ?? null,
            'address' => $_POST['address'] ?? null,
            'contact_person' => $_POST['contact_person'] ?? null,
            'phone' => $_POST['phone'] ?? null,
            'email' => $email,
            'contract_start_date' => $contractStart ?: null,
            'contract_end_date' => $contractEnd ?: null,
            'status' => $_POST['status'] ?? 'active',
            'pca_enabled' => !empty($_POST['pca_enabled']) ? 1 : 0,
            'logo_url' => null
        ];

        // Upload de logo (opcional)
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === 0) {
            $maxSize = 5 * 1024 * 1024; // 5 MB
            if ($_FILES['logo']['size'] > $maxSize) {
                $_SESSION['error_message'] = 'Arquivo muito grande. Máximo 5MB.';
                header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/admin'));
                exit;
            }

            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($_FILES['logo']['tmp_name']);
            $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($mime, $allowedMimes)) {
                $_SESSION['error_message'] = 'Tipo de arquivo não permitido.';
                header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/admin'));
                exit;
            }

            $ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
            $filename = 'school_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
            $uploadPath = __DIR__ . '/../../../public/uploads/schools/' . $filename;

            if (!is_dir(dirname($uploadPath))) {
                mkdir(dirname($uploadPath), 0775, true);
            }

            if (move_uploaded_file($_FILES['logo']['tmp_name'], $uploadPath)) {
                $data['logo_url'] = '/uploads/schools/' . $filename;
            }
        }

        $schoolModel = new School();
        if ($schoolModel->create($data)) {
            $_SESSION['success_message'] = 'Escola cadastrada com sucesso!';
            header('Location: /admin/schools');
            exit;
        }

        $_SESSION['error_message'] = 'Erro ao cadastrar escola. Tente novamente.';
        header('Location: /admin/schools/create');
        exit;
    }

    /**
     * Formulário para editar escola
     */
    public function edit($id)
    {
        $schoolModel = new School();
        $school = $schoolModel->find($id);

        if (!$school) {
            $_SESSION['error_message'] = 'Escola não encontrada.';
            header('Location: /admin/schools');
            exit;
        }

        return $this->render('schools/edit', ['school' => $school]);
    }

    /**
     * Atualizar escola
     */
    public function update($id)
    {
        Csrf::verify();

        $errors = [];

        // Validação básica
        if (empty($_POST['name'])) {
            $errors[] = 'O nome da escola é obrigatório.';
        }

        // BUG-026: validate email
        $email = $_POST['email'] ?? null;
        if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'E-mail inválido.';
        }

        // BUG-027: validate contract dates
        $contractStart = $_POST['contract_start_date'] ?? null;
        $contractEnd   = $_POST['contract_end_date'] ?? null;
        if (!empty($contractStart) && !empty($contractEnd)) {
            $dStart = \DateTime::createFromFormat('Y-m-d', $contractStart);
            $dEnd   = \DateTime::createFromFormat('Y-m-d', $contractEnd);
            if (!$dStart || $dStart->format('Y-m-d') !== $contractStart) {
                $errors[] = 'Data de início do contrato inválida.';
            } elseif (!$dEnd || $dEnd->format('Y-m-d') !== $contractEnd) {
                $errors[] = 'Data de término do contrato inválida.';
            } elseif ($dStart > $dEnd) {
                $errors[] = 'A data de início do contrato deve ser anterior à data de término.';
            }
        }

        if (!empty($errors)) {
            $_SESSION['error_message'] = implode(' ', $errors);
            header('Location: /admin/schools/' . $id . '/edit');
            exit;
        }

        $schoolModel = new School();
        $school = $schoolModel->find($id);

        if (!$school) {
            $_SESSION['error_message'] = 'Escola não encontrada.';
            header('Location: /admin/schools');
            exit;
        }

        $data = [
            'name' => $_POST['name'] ?? '',
            'city' => $_POST['city'] ?? null,
            'state' => $_POST['state'] ?? null,
            'address' => $_POST['address'] ?? null,
            'contact_person' => $_POST['contact_person'] ?? null,
            'phone' => $_POST['phone'] ?? null,
            'email' => $email,
            'contract_start_date' => $contractStart ?: null,
            'contract_end_date' => $contractEnd ?: null,
            'status' => $_POST['status'] ?? 'active',
            'pca_enabled' => !empty($_POST['pca_enabled']) ? 1 : 0,
            'logo_url' => $school['logo_url'] ?? null
        ];

        // Upload de novo logo (opcional)
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === 0) {
            $maxSize = 5 * 1024 * 1024; // 5 MB
            if ($_FILES['logo']['size'] > $maxSize) {
                $_SESSION['error_message'] = 'Arquivo muito grande. Máximo 5MB.';
                header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/admin'));
                exit;
            }

            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mime = $finfo->file($_FILES['logo']['tmp_name']);
            $allowedMimes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            if (!in_array($mime, $allowedMimes)) {
                $_SESSION['error_message'] = 'Tipo de arquivo não permitido.';
                header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/admin'));
                exit;
            }

            // Remove logo antigo se existir
            if (!empty($school['logo_url'])) {
                $oldLogoPath = __DIR__ . '/../../../public' . $school['logo_url'];
                if (file_exists($oldLogoPath) && !unlink($oldLogoPath)) {
                    error_log('Falha ao remover arquivo: ' . $oldLogoPath);
                }
            }

            $ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
            $filename = 'school_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
            $uploadPath = __DIR__ . '/../../../public/uploads/schools/' . $filename;

            if (!is_dir(dirname($uploadPath))) {
                mkdir(dirname($uploadPath), 0775, true);
            }

            if (move_uploaded_file($_FILES['logo']['tmp_name'], $uploadPath)) {
                $data['logo_url'] = '/uploads/schools/' . $filename;
            }
        }

        if ($schoolModel->update($id, $data)) {
            $_SESSION['success_message'] = 'Escola atualizada com sucesso!';
            header('Location: /admin/schools');
            exit;
        }

        $_SESSION['error_message'] = 'Erro ao atualizar escola. Tente novamente.';
        header('Location: /admin/schools/' . $id . '/edit');
        exit;
    }

    /**
     * Deletar escola
     */
    public function delete($id)
    {
        $schoolModel = new School();
        $school = $schoolModel->find($id);

        if (!$school) {
            $_SESSION['error_message'] = 'Escola não encontrada.';
            header('Location: /admin/schools');
            exit;
        }

        // Verificar se há alunos vinculados
        $students = $schoolModel->getStudents($id);
        if (!empty($students)) {
            $_SESSION['error_message'] = 'Não é possível deletar esta escola pois existem alunos vinculados a ela.';
            header('Location: /admin/schools');
            exit;
        }

        // Remove logo se existir
        if (!empty($school['logo_url'])) {
            $logoPath = __DIR__ . '/../../../public' . $school['logo_url'];
            if (file_exists($logoPath) && !unlink($logoPath)) {
                error_log('Falha ao remover arquivo: ' . $logoPath);
            }
        }

        if ($schoolModel->delete($id)) {
            $_SESSION['success_message'] = 'Escola deletada com sucesso!';
        } else {
            $_SESSION['error_message'] = 'Erro ao deletar escola. Tente novamente.';
        }

        header('Location: /admin/schools');
        exit;
    }

    /**
     * Entrar no contexto de uma escola (admin)
     */
    public function enterContext($id)
    {
        if (($_SESSION['user_role'] ?? '') !== 'admin') {
            header('Location: /admin/schools');
            exit;
        }

        $schoolModel = new School();
        $school = $schoolModel->find($id);

        if (!$school) {
            $_SESSION['error_message'] = 'Escola não encontrada.';
            header('Location: /admin/schools');
            exit;
        }

        $_SESSION['admin_school_context'] = [
            'id'   => (int) $school['id'],
            'name' => $school['name'],
        ];

        header('Location: /admin/schools/' . $id);
        exit;
    }

    /**
     * Sair do contexto de escola (admin)
     */
    public function exitContext()
    {
        unset($_SESSION['admin_school_context']);
        header('Location: /admin/schools');
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
