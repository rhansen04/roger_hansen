<?php

namespace App\Controllers\Admin;

use App\Models\Observation;
use App\Models\Student;

class ObservationController
{
    /**
     * Listagem de observacoes com filtros e controle de permissao
     */
    public function index()
    {
        $obsModel = new Observation();
        $studentModel = new Student();

        $userRole = $_SESSION['user_role'] ?? 'professor';
        $userId = $_SESSION['user_id'] ?? 0;

        // Filtros
        $filters = [
            'student_id' => $_GET['student_id'] ?? null,
            'semester' => $_GET['semester'] ?? null,
            'year' => $_GET['year'] ?? null,
            'status' => $_GET['status'] ?? null,
        ];

        // Professor ve apenas suas observacoes; coordenador e admin veem todas
        $roleRestrict = ($userRole === 'professor');

        $observations = $obsModel->allFiltered($filters, $userId, $roleRestrict);

        // Buscar todos os alunos para o dropdown de filtro
        $students = $studentModel->all();

        // Ano corrente e lista de anos para filtro
        $currentYear = (int) date('Y');
        $years = range($currentYear, $currentYear - 3);

        return $this->render('observations/index', [
            'observations' => $observations,
            'students' => $students,
            'filters' => $filters,
            'years' => $years,
            'currentYear' => $currentYear,
            'userRole' => $userRole,
        ]);
    }

    /**
     * Formulario de criacao (apenas professor)
     */
    public function create()
    {
        $studentModel = new Student();
        $students = $studentModel->all();

        $selectedStudentId = $_GET['student_id'] ?? null;
        $currentYear = (int) date('Y');
        $currentMonth = (int) date('m');
        $defaultSemester = ($currentMonth <= 6) ? 1 : 2;
        $years = range($currentYear, $currentYear - 3);

        return $this->render('observations/create', [
            'students' => $students,
            'selectedStudentId' => $selectedStudentId,
            'currentYear' => $currentYear,
            'defaultSemester' => $defaultSemester,
            'years' => $years,
        ]);
    }

    /**
     * Salvar nova observacao com eixos
     */
    public function store()
    {
        // Validacoes
        if (empty($_POST['student_id']) || empty($_POST['semester']) || empty($_POST['year'])) {
            $_SESSION['error_message'] = 'Preencha todos os campos obrigatorios (aluno, semestre e ano).';
            header('Location: /admin/observations/create');
            exit;
        }

        $obsModel = new Observation();

        // Verificar duplicidade
        $existing = $obsModel->existsForStudentSemester(
            $_POST['student_id'],
            $_POST['semester'],
            $_POST['year']
        );

        if ($existing) {
            $_SESSION['error_message'] = 'Ja existe uma observacao para este aluno neste semestre/ano. Edite a observacao existente.';
            header('Location: /admin/observations/' . $existing['id'] . '/edit');
            exit;
        }

        $data = [
            'student_id' => $_POST['student_id'],
            'user_id' => $_SESSION['user_id'],
            'semester' => $_POST['semester'],
            'year' => $_POST['year'],
            'observation_general' => trim($_POST['observation_general'] ?? ''),
            'axis_movement' => trim($_POST['axis_movement'] ?? ''),
            'axis_manual' => trim($_POST['axis_manual'] ?? ''),
            'axis_music' => trim($_POST['axis_music'] ?? ''),
            'axis_stories' => trim($_POST['axis_stories'] ?? ''),
            'axis_pca' => trim($_POST['axis_pca'] ?? ''),
        ];

        $newId = $obsModel->createWithAxes($data);
        if ($newId) {
            $_SESSION['success_message'] = 'Observacao criada com sucesso!';
            header('Location: /admin/observations/' . $newId . '/edit');
            exit;
        }

        $_SESSION['error_message'] = 'Erro ao criar observacao. Tente novamente.';
        header('Location: /admin/observations/create');
        exit;
    }

    /**
     * Ver detalhes de uma observacao
     */
    public function show($id)
    {
        $obsModel = new Observation();
        $observation = $obsModel->find($id);

        if (!$observation) {
            $_SESSION['error_message'] = 'Observacao nao encontrada.';
            header('Location: /admin/observations');
            exit;
        }

        $userRole = $_SESSION['user_role'] ?? 'professor';
        $userId = $_SESSION['user_id'] ?? 0;

        // Professor so ve suas proprias
        if ($userRole === 'professor' && $observation['user_id'] != $userId) {
            $_SESSION['error_message'] = 'Voce nao tem permissao para ver esta observacao.';
            header('Location: /admin/observations');
            exit;
        }

        return $this->render('observations/show', [
            'observation' => $observation,
            'userRole' => $userRole,
        ]);
    }

    /**
     * Formulario de edicao
     */
    public function edit($id)
    {
        $obsModel = new Observation();
        $observation = $obsModel->find($id);

        if (!$observation) {
            $_SESSION['error_message'] = 'Observacao nao encontrada.';
            header('Location: /admin/observations');
            exit;
        }

        $userRole = $_SESSION['user_role'] ?? 'professor';
        $userId = $_SESSION['user_id'] ?? 0;

        // Professor so edita suas proprias e em andamento
        if ($userRole === 'professor') {
            if ($observation['user_id'] != $userId) {
                $_SESSION['error_message'] = 'Voce nao tem permissao para editar esta observacao.';
                header('Location: /admin/observations');
                exit;
            }
            if (($observation['status'] ?? 'in_progress') === 'finalized') {
                $_SESSION['error_message'] = 'Esta observacao esta finalizada e nao pode ser editada.';
                header('Location: /admin/observations/' . $id);
                exit;
            }
        }

        // Coordenador nao edita, apenas visualiza e reabre
        if ($userRole === 'coordenador') {
            header('Location: /admin/observations/' . $id);
            exit;
        }

        $studentModel = new Student();
        $students = $studentModel->all();
        $currentYear = (int) date('Y');
        $years = range($currentYear, $currentYear - 3);

        return $this->render('observations/edit', [
            'observation' => $observation,
            'students' => $students,
            'years' => $years,
            'userRole' => $userRole,
        ]);
    }

    /**
     * Atualizar observacao
     */
    public function update($id)
    {
        $obsModel = new Observation();
        $observation = $obsModel->find($id);

        if (!$observation) {
            $_SESSION['error_message'] = 'Observacao nao encontrada.';
            header('Location: /admin/observations');
            exit;
        }

        $userRole = $_SESSION['user_role'] ?? 'professor';
        $userId = $_SESSION['user_id'] ?? 0;

        // Verificar permissoes
        if ($userRole === 'professor' && $observation['user_id'] != $userId) {
            $_SESSION['error_message'] = 'Voce nao tem permissao para editar esta observacao.';
            header('Location: /admin/observations');
            exit;
        }

        if (($observation['status'] ?? 'in_progress') === 'finalized') {
            $_SESSION['error_message'] = 'Esta observacao esta finalizada e nao pode ser editada.';
            header('Location: /admin/observations/' . $id);
            exit;
        }

        $data = [
            'student_id' => $_POST['student_id'] ?? $observation['student_id'],
            'semester' => $_POST['semester'] ?? $observation['semester'],
            'year' => $_POST['year'] ?? $observation['year'],
            'observation_general' => trim($_POST['observation_general'] ?? ''),
            'axis_movement' => trim($_POST['axis_movement'] ?? ''),
            'axis_manual' => trim($_POST['axis_manual'] ?? ''),
            'axis_music' => trim($_POST['axis_music'] ?? ''),
            'axis_stories' => trim($_POST['axis_stories'] ?? ''),
            'axis_pca' => trim($_POST['axis_pca'] ?? ''),
        ];

        if ($obsModel->updateWithAxes($id, $data)) {
            $_SESSION['success_message'] = 'Observacao atualizada com sucesso!';
            header('Location: /admin/observations/' . $id);
            exit;
        }

        $_SESSION['error_message'] = 'Erro ao atualizar observacao. Tente novamente.';
        header('Location: /admin/observations/' . $id . '/edit');
        exit;
    }

    /**
     * Auto-save via AJAX (POST)
     */
    public function autoSave($id)
    {
        header('Content-Type: application/json');

        $obsModel = new Observation();
        $observation = $obsModel->find($id);

        if (!$observation) {
            echo json_encode(['success' => false, 'message' => 'Observacao nao encontrada.']);
            exit;
        }

        $userRole = $_SESSION['user_role'] ?? 'professor';
        $userId = $_SESSION['user_id'] ?? 0;

        // Verificar permissoes
        if ($userRole === 'professor' && $observation['user_id'] != $userId) {
            echo json_encode(['success' => false, 'message' => 'Sem permissao.']);
            exit;
        }

        if (($observation['status'] ?? 'in_progress') === 'finalized') {
            echo json_encode(['success' => false, 'message' => 'Observacao finalizada.']);
            exit;
        }

        // Ler JSON do body
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) {
            $input = $_POST;
        }

        $field = $input['field'] ?? null;
        $value = $input['value'] ?? '';

        if (!$field) {
            echo json_encode(['success' => false, 'message' => 'Campo nao informado.']);
            exit;
        }

        $result = $obsModel->updateField($id, $field, $value);

        if ($result) {
            echo json_encode([
                'success' => true,
                'message' => 'Salvo',
                'saved_at' => date('H:i')
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Erro ao salvar.']);
        }
        exit;
    }

    /**
     * Finalizar observacao
     */
    public function finalize($id)
    {
        $obsModel = new Observation();
        $observation = $obsModel->find($id);

        if (!$observation) {
            $_SESSION['error_message'] = 'Observacao nao encontrada.';
            header('Location: /admin/observations');
            exit;
        }

        $userRole = $_SESSION['user_role'] ?? 'professor';
        $userId = $_SESSION['user_id'] ?? 0;

        // Somente o professor dono pode finalizar
        if ($userRole === 'professor' && $observation['user_id'] != $userId) {
            $_SESSION['error_message'] = 'Voce nao tem permissao para finalizar esta observacao.';
            header('Location: /admin/observations');
            exit;
        }

        if (($observation['status'] ?? 'in_progress') === 'finalized') {
            $_SESSION['error_message'] = 'Esta observacao ja esta finalizada.';
            header('Location: /admin/observations/' . $id);
            exit;
        }

        if ($obsModel->finalize($id, $userId)) {
            $_SESSION['success_message'] = 'Observacao finalizada com sucesso!';
        } else {
            $_SESSION['error_message'] = 'Erro ao finalizar observacao.';
        }

        header('Location: /admin/observations/' . $id);
        exit;
    }

    /**
     * Reabrir observacao (coordenador e admin)
     */
    public function reopen($id)
    {
        $userRole = $_SESSION['user_role'] ?? 'professor';

        // Apenas coordenador e admin podem reabrir
        if (!in_array($userRole, ['coordenador', 'admin'])) {
            $_SESSION['error_message'] = 'Apenas coordenadores podem reabrir observacoes.';
            header('Location: /admin/observations/' . $id);
            exit;
        }

        $obsModel = new Observation();
        $observation = $obsModel->find($id);

        if (!$observation) {
            $_SESSION['error_message'] = 'Observacao nao encontrada.';
            header('Location: /admin/observations');
            exit;
        }

        if (($observation['status'] ?? 'in_progress') !== 'finalized') {
            $_SESSION['error_message'] = 'Esta observacao nao esta finalizada.';
            header('Location: /admin/observations/' . $id);
            exit;
        }

        if ($obsModel->reopen($id)) {
            $_SESSION['success_message'] = 'Observacao reaberta com sucesso!';
        } else {
            $_SESSION['error_message'] = 'Erro ao reabrir observacao.';
        }

        header('Location: /admin/observations/' . $id);
        exit;
    }

    /**
     * Deletar observacao
     */
    public function delete($id)
    {
        $obsModel = new Observation();
        $observation = $obsModel->find($id);

        if (!$observation) {
            $_SESSION['error_message'] = 'Observacao nao encontrada.';
            header('Location: /admin/observations');
            exit;
        }

        $userRole = $_SESSION['user_role'] ?? 'professor';
        $userId = $_SESSION['user_id'] ?? 0;

        // Apenas dono ou admin pode deletar
        if ($userRole === 'professor' && $observation['user_id'] != $userId) {
            $_SESSION['error_message'] = 'Voce nao tem permissao para excluir esta observacao.';
            header('Location: /admin/observations');
            exit;
        }

        // Nao pode deletar finalizada (exceto admin)
        if (($observation['status'] ?? 'in_progress') === 'finalized' && $userRole !== 'admin') {
            $_SESSION['error_message'] = 'Nao e possivel excluir uma observacao finalizada.';
            header('Location: /admin/observations/' . $id);
            exit;
        }

        $studentId = $observation['student_id'];

        if ($obsModel->delete($id)) {
            $_SESSION['success_message'] = 'Observacao excluida com sucesso!';
        } else {
            $_SESSION['error_message'] = 'Erro ao excluir observacao.';
        }

        header('Location: /admin/observations');
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
            echo "<h2>Pagina {$view} em construcao</h2>";
        }
        $content = ob_get_clean();

        include __DIR__ . "/../../../views/layouts/admin.php";
    }
}
