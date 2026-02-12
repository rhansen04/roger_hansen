<?php

namespace App\Controllers;

class PageController
{
    public function home()
    {
        return $this->render('home', ['title' => 'Programas Pedagógicos para educação | Hansen Educacional']);
    }

    public function programas()
    {
        return $this->render('programas', ['title' => 'Programas Pedagógicos | Hansen Educacional']);
    }

    public function palestras()
    {
        return $this->render('palestras', ['title' => 'Palestras | Hansen Educacional']);
    }

    public function cursos()
    {
        $db = \App\Core\Database\Connection::getInstance();
        $stmt = $db->query("SELECT * FROM courses WHERE is_active = 1 ORDER BY created_at DESC");
        $courses = $stmt->fetchAll();

        return $this->render('cursos', [
            'title' => 'Cursos | Hansen Educacional',
            'courses' => $courses,
        ]);
    }

    public function livros()
    {
        return $this->render('livros', ['title' => 'Livros | Hansen Educacional']);
    }

    public function contato()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            return $this->processContactForm();
        }
        return $this->render('contato', ['title' => 'Contato | Hansen Educacional']);
    }

    public function termosDeUso()
    {
        return $this->render('termos-de-uso', ['title' => 'Termos de Uso | Hansen Educacional']);
    }

    public function politicaPrivacidade()
    {
        return $this->render('politica-privacidade', ['title' => 'Política de Privacidade | Hansen Educacional']);
    }

    private function processContactForm()
    {
        // Validação básica
        $required = ['school_name', 'contact_name', 'email', 'phone', 'city_state'];
        $errors = [];

        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                $errors[] = "Campo obrigatório: " . str_replace('_', ' ', $field);
            }
        }

        if (!empty($errors)) {
            return $this->render('contato', [
                'title' => 'Contato | Hansen Educacional',
                'errors' => $errors
            ]);
        }

        // Processar dados do formulário
        $data = [
            'school_name' => filter_var($_POST['school_name'], FILTER_SANITIZE_STRING),
            'contact_name' => filter_var($_POST['contact_name'], FILTER_SANITIZE_STRING),
            'email' => filter_var($_POST['email'], FILTER_SANITIZE_EMAIL),
            'phone' => filter_var($_POST['phone'], FILTER_SANITIZE_STRING),
            'city_state' => filter_var($_POST['city_state'], FILTER_SANITIZE_STRING),
            'message' => filter_var($_POST['message'] ?? '', FILTER_SANITIZE_STRING),
            'date' => date('Y-m-d H:i:s')
        ];

        // Processar anexo se houver
        if (isset($_FILES['attachment']) && $_FILES['attachment']['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/../../storage/attachments/';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $fileName = time() . '_' . basename($_FILES['attachment']['name']);
            $uploadFile = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['attachment']['tmp_name'], $uploadFile)) {
                $data['attachment'] = $fileName;
            }
        }

        // Salvar no banco de dados
        $db = \App\Core\Database\Connection::getInstance();
        $stmt = $db->prepare("
            INSERT INTO contacts (school_name, contact_name, email, phone, city_state, message, attachment)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['school_name'],
            $data['contact_name'],
            $data['email'],
            $data['phone'],
            $data['city_state'],
            $data['message'],
            $data['attachment'] ?? null,
        ]);

        // Redirecionar com mensagem de sucesso
        return $this->render('contato', [
            'title' => 'Contato | Hansen Educacional',
            'success' => 'Mensagem enviada com sucesso! Entraremos em contato em breve.'
        ]);
    }

    protected function render($view, $data = [])
    {
        extract($data);
        $viewFile = __DIR__ . "/../../views/pages/{$view}.php";
        
        if (file_exists($viewFile)) {
            ob_start();
            include $viewFile;
            $content = ob_get_clean();
            include __DIR__ . "/../../views/layouts/public.php";
        } else {
            ob_start();
            echo "<div class='container py-5'><h1>Página " . ucfirst($view) . " em construção</h1><p>Estamos trabalhando para trazer o conteúdo original do site de produção.</p></div>";
            $content = ob_get_clean();
            include __DIR__ . "/../../views/layouts/public.php";
        }
    }
}