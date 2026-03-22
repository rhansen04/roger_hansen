<?php

namespace App\Controllers\Admin;

use App\Models\PlanningSubmission;
use App\Models\PlanningTemplate;
use App\Models\PlanningDailyRoutine;
use App\Models\PlanningPeriodRecord;
use App\Models\Classroom;
use App\Models\Notification;
use App\Core\Security\Csrf;

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

        // Professores veem apenas seus proprios planejamentos
        $role = $_SESSION['user_role'] ?? 'admin';
        if ($role === 'professor') {
            $filters['teacher_id'] = $_SESSION['user_id'];
        }

        $submissions = $subModel->all($filters);
        $classrooms = ($role === 'professor')
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

        $role = $_SESSION['user_role'] ?? 'admin';
        $classrooms = ($role === 'professor')
            ? $classModel->getByTeacher($_SESSION['user_id'])
            : $classModel->all();

        return $this->render('planning/form', [
            'submission' => null,
            'template' => null,
            'answers' => [],
            'classrooms' => $classrooms,
            'mode' => 'create'
        ]);
    }

    public function store()
    {
        Csrf::verify();

        if (empty($_POST['classroom_id']) || empty($_POST['period_start']) || empty($_POST['period_end'])) {
            $_SESSION['error_message'] = 'Preencha todos os campos obrigatórios.';
            header('Location: /admin/planning/create');
            exit;
        }

        $periodStartRaw = trim((string) ($_POST['period_start'] ?? ''));
        $periodEndRaw   = trim((string) ($_POST['period_end'] ?? ''));
        $startDate = $this->parsePlanningDate($periodStartRaw);
        $endDate   = $this->parsePlanningDate($periodEndRaw);
        if (!$startDate || !$endDate || $startDate > $endDate) {
            $_SESSION['error_message'] = 'Período inválido: data de início deve ser anterior ao término.';
            header('Location: /admin/planning/create');
            exit;
        }

        $periodStart = $startDate->format('Y-m-d');
        $periodEnd = $endDate->format('Y-m-d');

        $templateId = $this->getDefaultTemplateId();
        if ($templateId === null) {
            $_SESSION['error_message'] = 'Nenhum template de planejamento ativo foi encontrado.';
            header('Location: /admin/planning/create');
            exit;
        }

        $subModel = new PlanningSubmission();
        $data = [
            'template_id' => $templateId,
            'teacher_id' => $_SESSION['user_id'],
            'classroom_id' => $_POST['classroom_id'],
            'period_start' => $periodStart,
            'period_end' => $periodEnd,
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

        header('Location: /admin/planning/' . $submissionId . '/days');
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

        // Professores podem ver apenas seus proprios planejamentos
        $role = $_SESSION['user_role'] ?? 'admin';
        if ($role === 'professor' && (int)$submission['teacher_id'] !== (int)$_SESSION['user_id']) {
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
        // Redirecionar para a visualizacao de dias (quinzenal)
        header("Location: /admin/planning/{$id}/days");
        exit;
    }

    public function editLegacy($id)
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
        if ($role === 'professor' && (int)$submission['teacher_id'] !== (int)$_SESSION['user_id']) {
            $_SESSION['error_message'] = 'Acesso negado.';
            header('Location: /admin/planning');
            exit;
        }

        $template = $tplModel->getWithSectionsAndFields($submission['template_id']);
        $answers = $subModel->getAnswersIndexed($id);

        $classrooms = ($role === 'professor')
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
        Csrf::verify();

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

    public function calendar()
    {
        $subModel = new PlanningSubmission();
        $classModel = new Classroom();

        $role = $_SESSION['user_role'] ?? 'admin';

        $month = (int)($_GET['month'] ?? date('n'));
        $year  = (int)($_GET['year'] ?? date('Y'));
        if ($month < 1 || $month > 12) $month = (int)date('n');
        if ($year < 2020 || $year > 2030) $year = (int)date('Y');

        // Get classrooms for filter
        $classrooms = ($role === 'professor')
            ? $classModel->getByTeacher($_SESSION['user_id'])
            : $classModel->all();

        $classroomId = $_GET['classroom_id'] ?? null;

        // Get all submissions that overlap with this month
        $monthStart = sprintf('%04d-%02d-01', $year, $month);
        $monthEnd = date('Y-m-t', strtotime($monthStart));

        $filters = [];
        if ($role === 'professor') {
            $filters['teacher_id'] = $_SESSION['user_id'];
        }
        if (!empty($classroomId)) {
            $filters['classroom_id'] = $classroomId;
        }

        $allSubmissions = $subModel->all($filters);

        // Filter submissions that overlap with the selected month
        $monthSubmissions = [];
        foreach ($allSubmissions as $sub) {
            if ($sub['period_end'] >= $monthStart && $sub['period_start'] <= $monthEnd) {
                $monthSubmissions[] = $sub;
            }
        }

        // Build weeks of the month
        $weeks = [];
        $firstDay = strtotime($monthStart);
        $lastDay = strtotime($monthEnd);

        // Find Monday of the first week
        $dayOfWeek = date('N', $firstDay); // 1=Mon, 7=Sun
        $weekStart = strtotime('-' . ($dayOfWeek - 1) . ' days', $firstDay);

        while ($weekStart <= $lastDay) {
            $weekEnd = strtotime('+4 days', $weekStart); // Friday
            $weekSunday = strtotime('+6 days', $weekStart); // Sunday (end of week)

            $weekSubs = [];
            foreach ($monthSubmissions as $sub) {
                $pStart = strtotime($sub['period_start']);
                $pEnd = strtotime($sub['period_end']);
                // Check overlap between submission period and this week (Mon-Fri)
                if ($pEnd >= $weekStart && $pStart <= $weekEnd) {
                    $weekSubs[] = $sub;
                }
            }

            $weeks[] = [
                'start' => date('Y-m-d', $weekStart),
                'end' => date('Y-m-d', $weekEnd),
                'start_fmt' => date('d/m', $weekStart),
                'end_fmt' => date('d/m', $weekEnd),
                'submissions' => $weekSubs,
                'in_month' => (date('n', $weekStart) == $month || date('n', $weekEnd) == $month),
            ];

            $weekStart = strtotime('+7 days', $weekStart);
        }

        $monthNames = [
            1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Marco', 4 => 'Abril',
            5 => 'Maio', 6 => 'Junho', 7 => 'Julho', 8 => 'Agosto',
            9 => 'Setembro', 10 => 'Outubro', 11 => 'Novembro', 12 => 'Dezembro'
        ];

        return $this->render('planning/calendar', [
            'weeks' => $weeks,
            'year' => $year,
            'month' => $month,
            'monthName' => $monthNames[$month],
            'classrooms' => $classrooms,
            'classroomId' => $classroomId,
            'userRole' => $role,
        ]);
    }

    public function weeklyRoutine($id)
    {
        $subModel = new PlanningSubmission();
        $routineModel = new PlanningDailyRoutine();

        $submission = $subModel->find($id);
        if (!$submission) {
            $_SESSION['error_message'] = 'Planejamento nao encontrado.';
            header('Location: /admin/planning');
            exit;
        }

        $role = $_SESSION['user_role'] ?? 'admin';
        if ($role === 'professor' && (int)$submission['teacher_id'] !== (int)$_SESSION['user_id']) {
            $_SESSION['error_message'] = 'Acesso negado.';
            header('Location: /admin/planning');
            exit;
        }

        $routines = $routineModel->findBySubmission($id);

        $dayNames = [
            1 => 'Segunda-feira',
            2 => 'Terca-feira',
            3 => 'Quarta-feira',
            4 => 'Quinta-feira',
            5 => 'Sexta-feira',
        ];

        $canEdit = ($submission['status'] !== 'registered')
            && ($role !== 'professor' || $submission['teacher_id'] == $_SESSION['user_id']);

        return $this->render('planning/routine', [
            'submission' => $submission,
            'routines' => $routines,
            'dayNames' => $dayNames,
            'canEdit' => $canEdit,
        ]);
    }

    public function saveRoutine($id)
    {
        Csrf::verify();

        $subModel = new PlanningSubmission();
        $routineModel = new PlanningDailyRoutine();

        $submission = $subModel->find($id);
        if (!$submission) {
            $_SESSION['error_message'] = 'Planejamento nao encontrado.';
            header('Location: /admin/planning');
            exit;
        }

        $role = $_SESSION['user_role'] ?? 'admin';
        if ($role === 'professor' && (int)$submission['teacher_id'] !== (int)$_SESSION['user_id']) {
            $_SESSION['error_message'] = 'Acesso negado.';
            header('Location: /admin/planning');
            exit;
        }

        // Parse POST data: routines[day_of_week][index][time_slot|activity_description]
        $routines = [];
        if (!empty($_POST['routines']) && is_array($_POST['routines'])) {
            foreach ($_POST['routines'] as $day => $activities) {
                $sortOrder = 0;
                foreach ($activities as $activity) {
                    $timeSlot = trim($activity['time_slot'] ?? '');
                    $description = trim($activity['activity_description'] ?? '');
                    if ($timeSlot === '' && $description === '') continue;

                    $routines[] = [
                        'day_of_week' => (int) $day,
                        'time_slot' => $timeSlot,
                        'activity_description' => $description,
                        'sort_order' => $sortOrder++,
                    ];
                }
            }
        }

        if ($routineModel->saveRoutines($id, $routines)) {
            $_SESSION['success_message'] = 'Rotina semanal salva com sucesso!';
        } else {
            $_SESSION['error_message'] = 'Erro ao salvar rotina.';
        }

        header("Location: /admin/planning/{$id}/routine");
        exit;
    }

    /**
     * Grid de dias uteis do periodo (visualizacao quinzenal)
     */
    public function days($id)
    {
        $subModel = new PlanningSubmission();
        $tplModel = new PlanningTemplate();

        $submission = $subModel->find($id);
        if (!$submission) {
            $_SESSION['error_message'] = 'Planejamento nao encontrado.';
            header('Location: /admin/planning');
            exit;
        }

        $role = $_SESSION['user_role'] ?? 'admin';
        if ($role === 'professor' && (int)$submission['teacher_id'] !== (int)$_SESSION['user_id']) {
            $_SESSION['error_message'] = 'Acesso negado.';
            header('Location: /admin/planning');
            exit;
        }

        // Calcular dias uteis (seg-sex) entre period_start e period_end
        $start = new \DateTime($submission['period_start']);
        $end = new \DateTime($submission['period_end']);
        $end->modify('+1 day'); // inclusive

        $weekdays = [];
        $interval = new \DateInterval('P1D');
        $period = new \DatePeriod($start, $interval, $end);
        foreach ($period as $day) {
            $dow = (int)$day->format('N'); // 1=Mon ... 7=Sun
            if ($dow <= 5) {
                $weekdays[] = $day->format('Y-m-d');
            }
        }

        // Carregar daily entries existentes
        $entries = $subModel->getDailyEntries($id);
        $entriesByDate = [];
        foreach ($entries as $e) {
            $entriesByDate[$e['entry_date']] = $e;
        }

        $template = $tplModel->getWithSectionsAndFields($submission['template_id']);

        $dayNames = [1 => 'Seg', 2 => 'Ter', 3 => 'Qua', 4 => 'Qui', 5 => 'Sex'];

        return $this->render('planning/days', [
            'submission' => $submission,
            'template' => $template,
            'weekdays' => $weekdays,
            'entriesByDate' => $entriesByDate,
            'dayNames' => $dayNames,
            'userRole' => $role,
        ]);
    }

    /**
     * Editar um dia especifico do planejamento
     */
    public function dayEdit($id, $date)
    {
        $subModel = new PlanningSubmission();
        $tplModel = new PlanningTemplate();

        $submission = $subModel->find($id);
        if (!$submission) {
            $_SESSION['error_message'] = 'Planejamento nao encontrado.';
            header('Location: /admin/planning');
            exit;
        }

        $role = $_SESSION['user_role'] ?? 'admin';
        if ($role === 'professor' && (int)$submission['teacher_id'] !== (int)$_SESSION['user_id']) {
            $_SESSION['error_message'] = 'Acesso negado.';
            header('Location: /admin/planning');
            exit;
        }

        $d = \DateTime::createFromFormat('Y-m-d', $date);
        $start = new \DateTime($submission['period_start']);
        $end   = new \DateTime($submission['period_end']);
        if (!$d || $d < $start || $d > $end) {
            $_SESSION['error_message'] = 'Data fora do período do planejamento.';
            header('Location: /admin/planning/' . $id . '/days');
            exit;
        }

        $template = $tplModel->getWithSectionsAndFields($submission['template_id']);

        // Encontrar ou criar daily entry
        $dailyEntry = $subModel->findOrCreateDailyEntry($id, $date);
        $answers = $subModel->getAnswersForDay($id, $dailyEntry['id']);

        // Filtrar secoes: excluir Identificacao e secoes de registro
        $daySections = [];
        if ($template && !empty($template['sections'])) {
            foreach ($template['sections'] as $section) {
                if (!empty($section['is_registration'])) continue;
                if (stripos($section['title'], 'Identifica') === 0) continue;
                $daySections[] = $section;
            }
        }

        $dayNames = [1 => 'Segunda-feira', 2 => 'Terca-feira', 3 => 'Quarta-feira', 4 => 'Quinta-feira', 5 => 'Sexta-feira'];
        $dt = new \DateTime($date);
        $dow = (int)$dt->format('N');

        return $this->render('planning/day_card', [
            'submission' => $submission,
            'template' => $template,
            'sections' => $daySections,
            'answers' => $answers,
            'dailyEntry' => $dailyEntry,
            'date' => $date,
            'dateFmt' => $dt->format('d/m/Y'),
            'dayName' => $dayNames[$dow] ?? '',
        ]);
    }

    /**
     * Salvar respostas de um dia especifico
     */
    public function dayUpdate($id, $date)
    {
        Csrf::verify();

        $subModel = new PlanningSubmission();

        $submission = $subModel->find($id);
        if (!$submission) {
            $_SESSION['error_message'] = 'Planejamento nao encontrado.';
            header('Location: /admin/planning');
            exit;
        }

        $role = $_SESSION['user_role'] ?? 'admin';
        if ($role === 'professor' && (int)$submission['teacher_id'] !== (int)$_SESSION['user_id']) {
            $_SESSION['error_message'] = 'Acesso negado.';
            header('Location: /admin/planning');
            exit;
        }

        $dailyEntry = $subModel->findOrCreateDailyEntry($id, $date);
        if (!$dailyEntry) {
            $_SESSION['error_message'] = 'Não foi possível carregar o dia do planejamento.';
            header('Location: /admin/planning/' . $id . '/days');
            exit;
        }

        if (!empty($_POST['answers']) && is_array($_POST['answers'])) {
            foreach ($_POST['answers'] as $fieldId => $value) {
                $sectionId = $_POST['answer_sections'][$fieldId] ?? 0;

                if (is_array($value)) {
                    $answerJson = json_encode(['selected' => array_map('intval', $value)], JSON_UNESCAPED_UNICODE);
                    $subModel->saveAnswerForDay($id, $fieldId, $sectionId, $dailyEntry['id'], null, $answerJson);
                } else {
                    $subModel->saveAnswerForDay($id, $fieldId, $sectionId, $dailyEntry['id'], trim($value), null);
                }
            }
        }

        // Atualizar status do daily entry para draft (tem conteudo)
        $subModel->updateDailyEntryStatus($dailyEntry['id'], 'filled');

        $_SESSION['success_message'] = 'Dia ' . (new \DateTime($date))->format('d/m') . ' salvo com sucesso!';
        header("Location: /admin/planning/{$id}/days");
        exit;
    }

    /**
     * Finalizar planejamento (mudar para submitted)
     */
    public function finalize($id)
    {
        Csrf::verify();

        $subModel = new PlanningSubmission();

        $submission = $subModel->find($id);
        if (!$submission) {
            $_SESSION['error_message'] = 'Planejamento nao encontrado.';
            header('Location: /admin/planning');
            exit;
        }

        $role = $_SESSION['user_role'] ?? 'admin';
        if ($role === 'professor' && (int)$submission['teacher_id'] !== (int)$_SESSION['user_id']) {
            $_SESSION['error_message'] = 'Acesso negado.';
            header('Location: /admin/planning');
            exit;
        }

        if ($submission['status'] !== 'draft') {
            $_SESSION['error_message'] = 'Apenas planejamentos em rascunho podem ser finalizados.';
            header("Location: /admin/planning/{$id}/days");
            exit;
        }

        $updated = $subModel->updateStatus($id, 'submitted');

        if ($updated) {
            // Notificar coordenadores apenas se o status foi alterado com sucesso
            try {
                Notification::notifyAllCoordenadores(
                    'planning',
                    'Planejamento enviado para revisao',
                    'O professor ' . ($_SESSION['user_name'] ?? '') . ' enviou o planejamento da turma ' . ($submission['classroom_name'] ?? '') . '.',
                    'planning',
                    $id
                );
            } catch (\Throwable $e) {
                error_log('finalize: falha ao notificar coordenadores para planejamento ' . $id . ': ' . $e->getMessage());
            }
        }

        $_SESSION['success_message'] = 'Planejamento finalizado e enviado para revisao!';
        header("Location: /admin/planning/{$id}/days");
        exit;
    }

    /**
     * Tela de registro pos-execucao (secoes com is_registration = 1)
     */
    public function registration($id)
    {
        $subModel = new PlanningSubmission();
        $tplModel = new PlanningTemplate();

        $submission = $subModel->find($id);
        if (!$submission) {
            $_SESSION['error_message'] = 'Planejamento nao encontrado.';
            header('Location: /admin/planning');
            exit;
        }

        $role = $_SESSION['user_role'] ?? 'admin';
        if ($role === 'professor' && (int)$submission['teacher_id'] !== (int)$_SESSION['user_id']) {
            $_SESSION['error_message'] = 'Acesso negado.';
            header('Location: /admin/planning');
            exit;
        }

        $template = $tplModel->getWithSectionsAndFields($submission['template_id']);

        // Filtrar apenas secoes de registro
        $regSections = [];
        if ($template && !empty($template['sections'])) {
            foreach ($template['sections'] as $section) {
                if (!empty($section['is_registration'])) {
                    $regSections[] = $section;
                }
            }
        }

        // Respostas de registro (daily_entry_id IS NULL)
        $answers = $subModel->getRegistrationAnswers($id);

        return $this->render('planning/registration', [
            'submission' => $submission,
            'template' => $template,
            'sections' => $regSections,
            'answers' => $answers,
        ]);
    }

    /**
     * Salvar registro pos-execucao
     */
    public function saveRegistration($id)
    {
        Csrf::verify();

        $subModel = new PlanningSubmission();

        $submission = $subModel->find($id);
        if (!$submission) {
            $_SESSION['error_message'] = 'Planejamento nao encontrado.';
            header('Location: /admin/planning');
            exit;
        }

        $role = $_SESSION['user_role'] ?? 'admin';
        if ($role === 'professor' && (int)$submission['teacher_id'] !== (int)$_SESSION['user_id']) {
            $_SESSION['error_message'] = 'Acesso negado.';
            header('Location: /admin/planning');
            exit;
        }

        // Salvar respostas sem daily_entry_id (registro geral)
        if (!empty($_POST['answers']) && is_array($_POST['answers'])) {
            foreach ($_POST['answers'] as $fieldId => $value) {
                $sectionId = $_POST['answer_sections'][$fieldId] ?? 0;

                if (is_array($value)) {
                    $answerJson = json_encode(['selected' => array_map('intval', $value)], JSON_UNESCAPED_UNICODE);
                    $subModel->saveAnswer($id, $fieldId, $sectionId, null, $answerJson);
                } else {
                    $subModel->saveAnswer($id, $fieldId, $sectionId, trim($value), null);
                }
            }
        }

        $action = $_POST['_action'] ?? 'save';
        if ($action === 'register' && $submission['status'] === 'submitted') {
            $subModel->updateStatus($id, 'registered');
            $_SESSION['success_message'] = 'Registro pos-vivencia finalizado com sucesso!';
        } else {
            $_SESSION['success_message'] = 'Registro salvo com sucesso!';
        }

        header("Location: /admin/planning/{$id}/days");
        exit;
    }

    public function deleteRoutineEntry($id)
    {
        Csrf::verify();

        $routineModel = new PlanningDailyRoutine();
        $entry = $routineModel->find($id);

        if (!$entry) {
            $_SESSION['error_message'] = 'Atividade nao encontrada.';
            header('Location: /admin/planning');
            exit;
        }

        $subModel = new PlanningSubmission();
        $submission = $subModel->find($entry['submission_id']);

        $role = $_SESSION['user_role'] ?? 'admin';
        if ($role === 'professor' && $submission && (int)$submission['teacher_id'] !== (int)$_SESSION['user_id']) {
            $_SESSION['error_message'] = 'Acesso negado.';
            header('Location: /admin/planning');
            exit;
        }

        $submissionId = $entry['submission_id'];
        if ($routineModel->delete($id)) {
            $_SESSION['success_message'] = 'Atividade removida!';
        } else {
            $_SESSION['error_message'] = 'Erro ao remover atividade.';
        }

        header("Location: /admin/planning/{$submissionId}/routine");
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

    // --- Registros do Período ---

    public function recordCreate($id)
    {
        $subModel = new PlanningSubmission();
        $submission = $subModel->find($id);
        if (!$submission) {
            header('Location: /admin/planning');
            exit;
        }

        $recordModel = new PlanningPeriodRecord();
        $recordModel->createTable();

        $existing = $recordModel->findBySubmission($id);
        if ($existing) {
            header("Location: /admin/planning/{$id}/record/edit");
            exit;
        }

        return $this->render('planning/record_form', [
            'submission' => $submission,
            'record' => [],
            'isEdit' => false,
            'pageTitle' => 'Registro do Período'
        ]);
    }

    public function recordStore($id)
    {
        Csrf::verify();

        $subModel = new PlanningSubmission();
        $submission = $subModel->find($id);
        if (!$submission) {
            header('Location: /admin/planning');
            exit;
        }

        $recordModel = new PlanningPeriodRecord();
        $recordModel->createTable();

        $data = [
            'submission_id'                    => $id,
            'activity_synthesis'               => trim($_POST['activity_synthesis'] ?? ''),
            'planning_execution'               => $_POST['planning_execution'] ?? '',
            'planning_execution_justification' => trim($_POST['planning_execution_justification'] ?? ''),
            'child_engagement'                 => $_POST['child_engagement'] ?? '',
            'child_engagement_comment'         => trim($_POST['child_engagement_comment'] ?? ''),
            'adjustments_time'                 => isset($_POST['adjustments_time']) ? 1 : 0,
            'adjustments_space'                => isset($_POST['adjustments_space']) ? 1 : 0,
            'adjustments_materials'            => isset($_POST['adjustments_materials']) ? 1 : 0,
            'adjustments_mediation'            => isset($_POST['adjustments_mediation']) ? 1 : 0,
            'adjustments_interest'             => isset($_POST['adjustments_interest']) ? 1 : 0,
            'adjustments_description'          => trim($_POST['adjustments_description'] ?? ''),
            'children_novelty'                 => trim($_POST['children_novelty'] ?? ''),
            'advances_challenges'              => trim($_POST['advances_challenges'] ?? ''),
            'support_pedagogical'              => isset($_POST['support_pedagogical']) ? 1 : 0,
            'support_organizational'           => isset($_POST['support_organizational']) ? 1 : 0,
            'support_formative'                => isset($_POST['support_formative']) ? 1 : 0,
            'support_structural'               => isset($_POST['support_structural']) ? 1 : 0,
            'support_description'              => trim($_POST['support_description'] ?? ''),
        ];
        $result = $recordModel->create($data);

        if ($result) {
            $_SESSION['success_message'] = 'Registro do período salvo com sucesso!';
        } else {
            $_SESSION['error_message'] = 'Erro ao salvar o registro.';
        }
        header('Location: /admin/planning');
        exit;
    }

    public function recordEdit($id)
    {
        $subModel = new PlanningSubmission();
        $submission = $subModel->find($id);
        if (!$submission) {
            header('Location: /admin/planning');
            exit;
        }

        $recordModel = new PlanningPeriodRecord();
        $record = $recordModel->findBySubmission($id);

        if (!$record) {
            header("Location: /admin/planning/{$id}/record/create");
            exit;
        }

        return $this->render('planning/record_form', [
            'submission' => $submission,
            'record' => $record,
            'isEdit' => true,
            'pageTitle' => 'Editar Registro do Período'
        ]);
    }

    public function recordUpdate($id)
    {
        Csrf::verify();

        $recordModel = new PlanningPeriodRecord();
        $record = $recordModel->findBySubmission($id);

        if (!$record) {
            header('Location: /admin/planning');
            exit;
        }

        $data = [
            'activity_synthesis'               => trim($_POST['activity_synthesis'] ?? ''),
            'planning_execution'               => $_POST['planning_execution'] ?? '',
            'planning_execution_justification' => trim($_POST['planning_execution_justification'] ?? ''),
            'child_engagement'                 => $_POST['child_engagement'] ?? '',
            'child_engagement_comment'         => trim($_POST['child_engagement_comment'] ?? ''),
            'adjustments_time'                 => isset($_POST['adjustments_time']) ? 1 : 0,
            'adjustments_space'                => isset($_POST['adjustments_space']) ? 1 : 0,
            'adjustments_materials'            => isset($_POST['adjustments_materials']) ? 1 : 0,
            'adjustments_mediation'            => isset($_POST['adjustments_mediation']) ? 1 : 0,
            'adjustments_interest'             => isset($_POST['adjustments_interest']) ? 1 : 0,
            'adjustments_description'          => trim($_POST['adjustments_description'] ?? ''),
            'children_novelty'                 => trim($_POST['children_novelty'] ?? ''),
            'advances_challenges'              => trim($_POST['advances_challenges'] ?? ''),
            'support_pedagogical'              => isset($_POST['support_pedagogical']) ? 1 : 0,
            'support_organizational'           => isset($_POST['support_organizational']) ? 1 : 0,
            'support_formative'                => isset($_POST['support_formative']) ? 1 : 0,
            'support_structural'               => isset($_POST['support_structural']) ? 1 : 0,
            'support_description'              => trim($_POST['support_description'] ?? ''),
        ];
        $result = $recordModel->update($record['id'], $data);

        if ($result) {
            $_SESSION['success_message'] = 'Registro atualizado com sucesso!';
        } else {
            $_SESSION['error_message'] = 'Erro ao atualizar o registro.';
        }
        header('Location: /admin/planning');
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

    private function getDefaultTemplateId(): ?int
    {
        $tplModel = new PlanningTemplate();
        $templates = $tplModel->allActive();

        if (empty($templates)) {
            return null;
        }

        return (int) $templates[0]['id'];
    }

    private function parsePlanningDate(string $value): ?\DateTime
    {
        if ($value === '') {
            return null;
        }

        $formats = ['Y-m-d', 'd/m/Y'];
        foreach ($formats as $format) {
            $date = \DateTime::createFromFormat($format, $value);
            $errors = \DateTime::getLastErrors();
            if ($date && ($errors === false || ($errors['warning_count'] === 0 && $errors['error_count'] === 0))) {
                return $date;
            }
        }

        return null;
    }
}
