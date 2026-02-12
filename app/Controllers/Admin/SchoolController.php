<?php

namespace App\Controllers\Admin;

use App\Models\School;

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
        // Validação básica
        if (empty($_POST['name'])) {
            $_SESSION['error_message'] = 'O nome da escola é obrigatório.';
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
            'email' => $_POST['email'] ?? null,
            'contract_start_date' => $_POST['contract_start_date'] ?? null,
            'contract_end_date' => $_POST['contract_end_date'] ?? null,
            'status' => $_POST['status'] ?? 'active',
            'logo_url' => null
        ];

        // Upload de logo (opcional)
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === 0) {
            $ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
            $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array(strtolower($ext), $allowedExts)) {
                $filename = 'school_' . time() . '.' . $ext;
                $uploadPath = __DIR__ . '/../../../public/uploads/schools/' . $filename;

                if (!is_dir(dirname($uploadPath))) {
                    mkdir(dirname($uploadPath), 0775, true);
                }

                if (move_uploaded_file($_FILES['logo']['tmp_name'], $uploadPath)) {
                    $data['logo_url'] = '/uploads/schools/' . $filename;
                }
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
        // Validação básica
        if (empty($_POST['name'])) {
            $_SESSION['error_message'] = 'O nome da escola é obrigatório.';
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
            'email' => $_POST['email'] ?? null,
            'contract_start_date' => $_POST['contract_start_date'] ?? null,
            'contract_end_date' => $_POST['contract_end_date'] ?? null,
            'status' => $_POST['status'] ?? 'active',
            'logo_url' => $school['logo_url'] ?? null
        ];

        // Upload de novo logo (opcional)
        if (isset($_FILES['logo']) && $_FILES['logo']['error'] === 0) {
            $ext = pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
            $allowedExts = ['jpg', 'jpeg', 'png', 'gif'];

            if (in_array(strtolower($ext), $allowedExts)) {
                // Remove logo antigo se existir
                if (!empty($school['logo_url'])) {
                    $oldLogoPath = __DIR__ . '/../../../public' . $school['logo_url'];
                    if (file_exists($oldLogoPath)) {
                        unlink($oldLogoPath);
                    }
                }

                $filename = 'school_' . time() . '.' . $ext;
                $uploadPath = __DIR__ . '/../../../public/uploads/schools/' . $filename;

                if (!is_dir(dirname($uploadPath))) {
                    mkdir(dirname($uploadPath), 0775, true);
                }

                if (move_uploaded_file($_FILES['logo']['tmp_name'], $uploadPath)) {
                    $data['logo_url'] = '/uploads/schools/' . $filename;
                }
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
            if (file_exists($logoPath)) {
                unlink($logoPath);
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
