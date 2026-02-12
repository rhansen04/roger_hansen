<?php

namespace App\Controllers\Admin;

use App\Models\Observation;
use App\Models\Student;

class ObservationController
{
    /**
     * Listagem de observações com filtros
     */
    public function index()
    {
        $obsModel = new Observation();
        $studentModel = new Student();

        // Filtros
        $filters = [
            'student_id' => $_GET['student_id'] ?? null,
            'category' => $_GET['category'] ?? null,
            'date_from' => $_GET['date_from'] ?? null,
            'date_to' => $_GET['date_to'] ?? null
        ];

        // Se tiver filtro de aluno, buscar observações do aluno
        if ($filters['student_id']) {
            $observations = $obsModel->findByStudent($filters['student_id']);
            $student = $studentModel->find($filters['student_id']);
        } else {
            $observations = $obsModel->all();
            $student = null;
        }

        // Aplicar filtros adicionais
        if ($filters['category']) {
            $observations = array_filter($observations, function($obs) use ($filters) {
                return $obs['category'] === $filters['category'];
            });
        }

        if ($filters['date_from']) {
            $observations = array_filter($observations, function($obs) use ($filters) {
                return $obs['observation_date'] >= $filters['date_from'];
            });
        }

        if ($filters['date_to']) {
            $observations = array_filter($observations, function($obs) use ($filters) {
                return $obs['observation_date'] <= $filters['date_to'];
            });
        }

        // Buscar todos os alunos para o dropdown de filtro
        $students = $studentModel->all();

        return $this->render('observations/index', [
            'observations' => $observations,
            'students' => $students,
            'student' => $student,
            'filters' => $filters
        ]);
    }

    /**
     * Formulário de criação
     */
    public function create()
    {
        $studentModel = new Student();
        $students = $studentModel->all();

        // Se veio com student_id na URL, pré-selecionar
        $selectedStudentId = $_GET['student_id'] ?? null;

        return $this->render('observations/create', [
            'students' => $students,
            'selectedStudentId' => $selectedStudentId
        ]);
    }

    /**
     * Salvar nova observação
     */
    public function store()
    {
        // Validações básicas
        if (empty($_POST['student_id']) || empty($_POST['title'])) {
            $_SESSION['error_message'] = 'Preencha todos os campos obrigatórios.';
            header('Location: /admin/observations/create');
            exit;
        }

        $data = [
            'student_id' => $_POST['student_id'],
            'user_id' => $_SESSION['user_id'],
            'category' => $_POST['category'] ?? 'Geral',
            'title' => trim($_POST['title']),
            'description' => trim($_POST['description'] ?? ''),
            'observation_date' => !empty($_POST['observation_date'])
                ? $_POST['observation_date']
                : date('Y-m-d')
        ];

        $obsModel = new Observation();
        if ($obsModel->create($data)) {
            $_SESSION['success_message'] = 'Observação criada com sucesso!';
            header('Location: /admin/observations?student_id=' . $data['student_id']);
            exit;
        }

        $_SESSION['error_message'] = 'Erro ao criar observação. Tente novamente.';
        header('Location: /admin/observations/create');
        exit;
    }

    /**
     * Ver detalhes de uma observação
     */
    public function show($id)
    {
        $obsModel = new Observation();
        $observation = $obsModel->find($id);

        if (!$observation) {
            $_SESSION['error_message'] = 'Observação não encontrada.';
            header('Location: /admin/observations');
            exit;
        }

        return $this->render('observations/show', [
            'observation' => $observation
        ]);
    }

    /**
     * Formulário de edição
     */
    public function edit($id)
    {
        $obsModel = new Observation();
        $observation = $obsModel->find($id);

        if (!$observation) {
            $_SESSION['error_message'] = 'Observação não encontrada.';
            header('Location: /admin/observations');
            exit;
        }

        $studentModel = new Student();
        $students = $studentModel->all();

        return $this->render('observations/edit', [
            'observation' => $observation,
            'students' => $students
        ]);
    }

    /**
     * Atualizar observação
     */
    public function update($id)
    {
        $obsModel = new Observation();
        $observation = $obsModel->find($id);

        if (!$observation) {
            $_SESSION['error_message'] = 'Observação não encontrada.';
            header('Location: /admin/observations');
            exit;
        }

        // Validações básicas
        if (empty($_POST['student_id']) || empty($_POST['title'])) {
            $_SESSION['error_message'] = 'Preencha todos os campos obrigatórios.';
            header('Location: /admin/observations/' . $id . '/edit');
            exit;
        }

        $data = [
            'student_id' => $_POST['student_id'],
            'user_id' => $observation['user_id'], // Mantém o criador original
            'category' => $_POST['category'] ?? 'Geral',
            'title' => trim($_POST['title']),
            'description' => trim($_POST['description'] ?? ''),
            'observation_date' => !empty($_POST['observation_date'])
                ? $_POST['observation_date']
                : $observation['observation_date']
        ];

        if ($obsModel->update($id, $data)) {
            $_SESSION['success_message'] = 'Observação atualizada com sucesso!';
            header('Location: /admin/observations/' . $id);
            exit;
        }

        $_SESSION['error_message'] = 'Erro ao atualizar observação. Tente novamente.';
        header('Location: /admin/observations/' . $id . '/edit');
        exit;
    }

    /**
     * Deletar observação
     */
    public function delete($id)
    {
        $obsModel = new Observation();
        $observation = $obsModel->find($id);

        if (!$observation) {
            $_SESSION['error_message'] = 'Observação não encontrada.';
            header('Location: /admin/observations');
            exit;
        }

        $studentId = $observation['student_id'];

        if ($obsModel->delete($id)) {
            $_SESSION['success_message'] = 'Observação deletada com sucesso!';
        } else {
            $_SESSION['error_message'] = 'Erro ao deletar observação. Tente novamente.';
        }

        header('Location: /admin/observations?student_id=' . $studentId);
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
