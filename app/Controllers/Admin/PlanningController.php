<?php

namespace App\Controllers\Admin;

use App\Models\PlanningSubmission;
use App\Models\PlanningTemplate;
use App\Models\Classroom;

class PlanningController
{
    public function index()
    {
        $subModel = new PlanningSubmission();
        $classModel = new Classroom();
        $tplModel = new PlanningTemplate();

        $filters = [
            'teacher_id' => $_GET['teacher_id'] ?? null,
            'classroom_id' => $_GET['classroom_id'] ?? null,
            'status' => $_GET['status'] ?? null,
            'template_id' => $_GET['template_id'] ?? null,
        ];

        // Teachers only see their own
        $role = $_SESSION['user_role'] ?? 'admin';
        if ($role === 'teacher') {
            $filters['teacher_id'] = $_SESSION['user_id'];
        }

        $submissions = $subModel->all($filters);
        $classrooms = ($role === 'teacher')
            ? $classModel->getByTeacher($_SESSION['user_id'])
            : $classModel->all();
        $templates = $tplModel->allActive();

        return $this->render('planning/index', [
            'submissions' => $submissions,
            'classrooms' => $classrooms,
            'templates' => $templates,
            'filters' => $filters,
            'userRole' => $role
        ]);
    }

    public function create()
    {
        $classModel = new Classroom();
        $tplModel = new PlanningTemplate();

        $role = $_SESSION['user_role'] ?? 'admin';
        $classrooms = ($role === 'teacher')
            ? $classModel->getByTeacher($_SESSION['user_id'])
            : $classModel->all();
        $templates = $tplModel->allActive();

        return $this->render('planning/form', [
            'submission' => null,
            'template' => null,
            'answers' => [],
            'classrooms' => $classrooms,
            'templates' => $templates,
            'mode' => 'create'
        ]);
    }

    public function store()
    {
        if (empty($_POST['template_id']) || empty($_POST['classroom_id']) || empty($_POST['period_start']) || empty($_POST['period_end'])) {
            $_SESSION['error_message'] = 'Preencha todos os campos obrigatórios.';
            header('Location: /admin/planning/create');
            exit;
        }

        $subModel = new PlanningSubmission();
        $data = [
            'template_id' => $_POST['template_id'],
            'teacher_id' => $_SESSION['user_id'],
            'classroom_id' => $_POST['classroom_id'],
            'period_start' => $_POST['period_start'],
            'period_end' => $_POST['period_end'],
        ];

        $submissionId = $subModel->create($data);
        if (!$submissionId) {
            $_SESSION['error_message'] = 'Erro ao criar planejamento.';
            header('Location: /admin/planning/create');
            exit;
        }

        // Save answers if provided
        $this->saveAnswers($subModel, $submissionId);

        // Check if submit or just save draft
        if (!empty($_POST['_action']) && $_POST['_action'] === 'submit') {
            $subModel->updateStatus($submissionId, 'submitted');
            $_SESSION['success_message'] = 'Planejamento enviado com sucesso!';
        } else {
            $_SESSION['success_message'] = 'Rascunho salvo com sucesso!';
        }

        header('Location: /admin/planning');
        exit;
    }

    public function show($id)
    {
        $subModel = new PlanningSubmission();
        $tplModel = new PlanningTemplate();

        $submission = $subModel->find($id);
        if (!$submission) {
            $_SESSION['error_message'] = 'Planejamento não encontrado.';
            header('Location: /admin/planning');
            exit;
        }

        // Teachers can only see their own
        $role = $_SESSION['user_role'] ?? 'admin';
        if ($role === 'teacher' && $submission['teacher_id'] != $_SESSION['user_id']) {
            $_SESSION['error_message'] = 'Acesso negado.';
            header('Location: /admin/planning');
            exit;
        }

        $template = $tplModel->getWithSectionsAndFields($submission['template_id']);
        $answers = $subModel->getAnswersIndexed($id);

        return $this->render('planning/show', [
            'submission' => $submission,
            'template' => $template,
            'answers' => $answers
        ]);
    }

    public function edit($id)
    {
        $subModel = new PlanningSubmission();
        $tplModel = new PlanningTemplate();
        $classModel = new Classroom();

        $submission = $subModel->find($id);
        if (!$submission) {
            $_SESSION['error_message'] = 'Planejamento não encontrado.';
            header('Location: /admin/planning');
            exit;
        }

        // Only draft/submitted can be edited
        $role = $_SESSION['user_role'] ?? 'admin';
        if ($role === 'teacher' && $submission['teacher_id'] != $_SESSION['user_id']) {
            $_SESSION['error_message'] = 'Acesso negado.';
            header('Location: /admin/planning');
            exit;
        }

        $template = $tplModel->getWithSectionsAndFields($submission['template_id']);
        $answers = $subModel->getAnswersIndexed($id);

        $classrooms = ($role === 'teacher')
            ? $classModel->getByTeacher($_SESSION['user_id'])
            : $classModel->all();

        return $this->render('planning/form', [
            'submission' => $submission,
            'template' => $template,
            'answers' => $answers,
            'classrooms' => $classrooms,
            'templates' => [],
            'mode' => 'edit'
        ]);
    }

    public function update($id)
    {
        $subModel = new PlanningSubmission();
        $submission = $subModel->find($id);

        if (!$submission) {
            $_SESSION['error_message'] = 'Planejamento não encontrado.';
            header('Location: /admin/planning');
            exit;
        }

        // Save answers
        $this->saveAnswers($subModel, $id);

        // Check action
        $action = $_POST['_action'] ?? 'save';
        if ($action === 'submit' && $submission['status'] === 'draft') {
            $subModel->updateStatus($id, 'submitted');
            $_SESSION['success_message'] = 'Planejamento enviado com sucesso!';
        } elseif ($action === 'register' && $submission['status'] === 'submitted') {
            $subModel->updateStatus($id, 'registered');
            $_SESSION['success_message'] = 'Registro pós-vivência salvo com sucesso!';
        } else {
            $_SESSION['success_message'] = 'Planejamento salvo com sucesso!';
        }

        header('Location: /admin/planning/' . $id);
        exit;
    }

    public function delete($id)
    {
        $subModel = new PlanningSubmission();
        $submission = $subModel->find($id);

        if (!$submission) {
            $_SESSION['error_message'] = 'Planejamento não encontrado.';
            header('Location: /admin/planning');
            exit;
        }

        if ($subModel->delete($id)) {
            $_SESSION['success_message'] = 'Planejamento excluído!';
        } else {
            $_SESSION['error_message'] = 'Erro ao excluir planejamento.';
        }
        header('Location: /admin/planning');
        exit;
    }

    private function saveAnswers($subModel, $submissionId)
    {
        if (empty($_POST['answers']) || !is_array($_POST['answers'])) return;

        foreach ($_POST['answers'] as $fieldId => $value) {
            $sectionId = $_POST['answer_sections'][$fieldId] ?? 0;

            if (is_array($value)) {
                // Checklist: value is array of selected indices
                $answerJson = json_encode(['selected' => array_map('intval', $value)], JSON_UNESCAPED_UNICODE);
                $subModel->saveAnswer($submissionId, $fieldId, $sectionId, null, $answerJson);
            } else {
                $subModel->saveAnswer($submissionId, $fieldId, $sectionId, trim($value), null);
            }
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
