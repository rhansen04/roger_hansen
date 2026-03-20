<?php

namespace App\Controllers\Admin;

use App\Models\Observation;
use App\Models\Student;
use App\Models\CoordinatorComment;
use App\Core\Security\Csrf;

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
        Csrf::verify();

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
            'observation_general' => $this->encodeAxisField('observation_general'),
            'axis_movement' => $this->encodeAxisField('axis_movement'),
            'axis_manual' => $this->encodeAxisField('axis_manual'),
            'axis_music' => $this->encodeAxisField('axis_music'),
            'axis_stories' => $this->encodeAxisField('axis_stories'),
            'axis_pca' => $this->encodeAxisField('axis_pca'),
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
        if ($userRole === 'professor' && (int)$observation['user_id'] !== (int)$userId) {
            $_SESSION['error_message'] = 'Voce nao tem permissao para ver esta observacao.';
            header('Location: /admin/observations');
            exit;
        }

        $commentModel = new CoordinatorComment();
        $comments = $commentModel->findByContent('observation', (int) $id);

        return $this->render('observations/show', [
            'observation' => $observation,
            'userRole' => $userRole,
            'comments' => $comments,
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
            if ((int)$observation['user_id'] !== (int)$userId) {
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
        Csrf::verify();

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
        if ($userRole === 'professor' && (int)$observation['user_id'] !== (int)$userId) {
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
            'observation_general' => $this->encodeAxisField('observation_general'),
            'axis_movement' => $this->encodeAxisField('axis_movement'),
            'axis_manual' => $this->encodeAxisField('axis_manual'),
            'axis_music' => $this->encodeAxisField('axis_music'),
            'axis_stories' => $this->encodeAxisField('axis_stories'),
            'axis_pca' => $this->encodeAxisField('axis_pca'),
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

        // Ler JSON do body
        $input = json_decode(file_get_contents('php://input'), true) ?? [];

        // Verificar CSRF via header ou campo no body JSON
        $csrfToken = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? ($input['csrf_token'] ?? '');
        $expected = $_SESSION['csrf_token'] ?? '';
        if (empty($expected) || !hash_equals($expected, $csrfToken)) {
            http_response_code(403);
            echo json_encode(['error' => 'Token invalido.']);
            exit;
        }

        // Validar campo contra whitelist antes de qualquer acesso ao modelo
        $allowedFields = ['observation_general', 'axis_movement', 'axis_manual', 'axis_music', 'axis_stories', 'axis_pca'];
        $field = $input['field'] ?? '';
        if (!in_array($field, $allowedFields)) {
            http_response_code(400);
            echo json_encode(['error' => 'Campo invalido.']);
            exit;
        }

        $obsModel = new Observation();
        $observation = $obsModel->find($id);

        if (!$observation) {
            echo json_encode(['success' => false, 'message' => 'Observacao nao encontrada.']);
            exit;
        }

        $userRole = $_SESSION['user_role'] ?? 'professor';
        $userId = $_SESSION['user_id'] ?? 0;

        // Verificar permissoes
        if ($userRole === 'professor' && (int)$observation['user_id'] !== (int)$userId) {
            echo json_encode(['success' => false, 'message' => 'Sem permissao.']);
            exit;
        }

        if (($observation['status'] ?? 'in_progress') === 'finalized') {
            echo json_encode(['success' => false, 'message' => 'Observacao finalizada.']);
            exit;
        }

        $value = $input['value'] ?? '';

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
        Csrf::verify();

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
        if ($userRole === 'professor' && (int)$observation['user_id'] !== (int)$userId) {
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
        Csrf::verify();

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
        Csrf::verify();

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
        if ($userRole === 'professor' && (int)$observation['user_id'] !== (int)$userId) {
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

        // Verificar se existe parecer descritivo vinculado a esta observacao
        $db = \App\Core\Database\Connection::getInstance();
        $stmt = $db->prepare("SELECT id FROM descriptive_reports WHERE observation_id = ? LIMIT 1");
        $stmt->execute([$id]);
        if ($stmt->fetch()) {
            $_SESSION['error_message'] = 'Nao e possivel excluir: esta observacao esta vinculada a um parecer descritivo.';
            header('Location: /admin/observations/' . $id);
            exit;
        }

        $studentId = $observation['student_id'];

        // Limpar comentários de coordenacao vinculados
        $commentModel = new \App\Models\CoordinatorComment();
        $commentModel->deleteByContent('observation', (int)$id);

        if ($obsModel->delete($id)) {
            $_SESSION['success_message'] = 'Observacao excluida com sucesso!';
        } else {
            $_SESSION['error_message'] = 'Erro ao excluir observacao.';
        }

        header('Location: /admin/observations');
        exit;
    }

    private function encodeAxisField(string $fieldName): string
    {
        $raw = $_POST[$fieldName] ?? [];
        if (is_array($raw)) {
            return json_encode(array_values(array_map('trim', $raw)));
        }
        return trim((string) $raw);
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
