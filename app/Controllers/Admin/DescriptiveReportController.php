<?php

namespace App\Controllers\Admin;

use App\Models\DescriptiveReport;
use App\Models\Student;
use App\Models\Classroom;
use App\Models\Observation;
use App\Services\GeminiService;
use Exception;

class DescriptiveReportController
{
    const INTRO_TEXT = 'Queridas familias,

Este Parecer Descritivo tem como objetivo compartilhar com voces o acompanhamento do desenvolvimento de seu(sua) filho(a) no ambiente escolar, considerando aspectos pedagogicos fundamentais vivenciados ao longo do semestre.

Aqui, voces encontrarao registros sobre as experiencias, conquistas e desafios observados em cada eixo de atividades que compoem a proposta pedagogica da nossa escola. Cada relato foi construido com cuidado e atencao, buscando refletir o olhar sensivel dos educadores que acompanham o dia a dia da crianca.

Acreditamos que a parceria entre familia e escola e essencial para o desenvolvimento integral da crianca. Por isso, convidamos voces a lerem este documento com carinho e, sempre que desejarem, procurem a equipe pedagogica para conversarmos sobre o progresso e as necessidades de cada crianca.

Com carinho,
Equipe Pedagogica';

    /**
     * Listagem de pareceres com filtros
     */
    public function index()
    {
        $reportModel = new DescriptiveReport();
        $classroomModel = new Classroom();

        $filters = [
            'classroom_id' => $_GET['classroom_id'] ?? null,
            'semester' => $_GET['semester'] ?? null,
            'year' => $_GET['year'] ?? null,
            'status' => $_GET['status'] ?? null
        ];

        $hasFilters = !empty(array_filter($filters));

        if ($hasFilters) {
            $reports = $reportModel->findFiltered($filters);
        } else {
            $reports = $reportModel->all();
        }

        $classrooms = $classroomModel->all();
        $counts = $reportModel->countByStatus();

        return $this->render('descriptive-reports/index', [
            'reports' => $reports,
            'classrooms' => $classrooms,
            'filters' => $filters,
            'counts' => $counts
        ]);
    }

    /**
     * Formulario de criacao de parecer
     */
    public function create()
    {
        $studentModel = new Student();
        $classroomModel = new Classroom();
        $obsModel = new Observation();

        $students = $studentModel->all();
        $classrooms = $classroomModel->all();

        $selectedStudentId = $_GET['student_id'] ?? null;
        $selectedObservationId = $_GET['observation_id'] ?? null;

        // Buscar observacoes disponiveis para o aluno selecionado
        $observations = [];
        if ($selectedStudentId) {
            $observations = $obsModel->findByStudent($selectedStudentId);
        }

        return $this->render('descriptive-reports/create', [
            'students' => $students,
            'classrooms' => $classrooms,
            'observations' => $observations,
            'selectedStudentId' => $selectedStudentId,
            'selectedObservationId' => $selectedObservationId
        ]);
    }

    /**
     * Salvar novo parecer - compilar texto das observacoes
     */
    public function store()
    {
        if (empty($_POST['student_id']) || empty($_POST['semester']) || empty($_POST['year'])) {
            $_SESSION['error_message'] = 'Preencha todos os campos obrigatorios.';
            header('Location: /admin/descriptive-reports/create');
            exit;
        }

        $studentId = (int)$_POST['student_id'];
        $semester = (int)$_POST['semester'];
        $year = (int)$_POST['year'];
        $classroomId = !empty($_POST['classroom_id']) ? (int)$_POST['classroom_id'] : null;
        $observationId = !empty($_POST['observation_id']) ? (int)$_POST['observation_id'] : null;

        // Compilar texto a partir da observacao
        $studentText = '';
        if ($observationId) {
            $obsModel = new Observation();
            $observation = $obsModel->find($observationId);

            if ($observation) {
                $parts = [];

                if (!empty($observation['observation_general'])) {
                    $parts[] = $observation['observation_general'];
                }
                if (!empty($observation['axis_movement'])) {
                    $parts[] = "Atividade de Movimento:\n" . $observation['axis_movement'];
                }
                if (!empty($observation['axis_manual'])) {
                    $parts[] = "Atividade Manual:\n" . $observation['axis_manual'];
                }
                if (!empty($observation['axis_music'])) {
                    $parts[] = "Atividade Musical:\n" . $observation['axis_music'];
                }
                if (!empty($observation['axis_stories'])) {
                    $parts[] = "Atividade de Contos:\n" . $observation['axis_stories'];
                }
                if (!empty($observation['axis_pca'])) {
                    $parts[] = "Programa Comunicacao Ativa (PCA):\n" . $observation['axis_pca'];
                }

                $studentText = implode("\n\n", $parts);
            }
        } else {
            // Se nao foi selecionada uma observacao especifica, buscar a mais recente do semestre
            $obsModel = new Observation();
            $observations = $obsModel->findByStudent($studentId);

            foreach ($observations as $obs) {
                if (isset($obs['semester']) && $obs['semester'] == $semester
                    && isset($obs['year']) && $obs['year'] == $year) {
                    $parts = [];

                    if (!empty($obs['observation_general'])) {
                        $parts[] = $obs['observation_general'];
                    }
                    if (!empty($obs['axis_movement'])) {
                        $parts[] = "Atividade de Movimento:\n" . $obs['axis_movement'];
                    }
                    if (!empty($obs['axis_manual'])) {
                        $parts[] = "Atividade Manual:\n" . $obs['axis_manual'];
                    }
                    if (!empty($obs['axis_music'])) {
                        $parts[] = "Atividade Musical:\n" . $obs['axis_music'];
                    }
                    if (!empty($obs['axis_stories'])) {
                        $parts[] = "Atividade de Contos:\n" . $obs['axis_stories'];
                    }
                    if (!empty($obs['axis_pca'])) {
                        $parts[] = "Programa Comunicacao Ativa (PCA):\n" . $obs['axis_pca'];
                    }

                    $studentText = implode("\n\n", $parts);
                    $observationId = $obs['id'];
                    break;
                }
            }
        }

        // Detectar turma do aluno se nao informada
        if (!$classroomId) {
            $classroomModel = new Classroom();
            $allClassrooms = $classroomModel->all();
            foreach ($allClassrooms as $cr) {
                $students = $classroomModel->students($cr['id']);
                foreach ($students as $st) {
                    if ($st['id'] == $studentId) {
                        $classroomId = $cr['id'];
                        break 2;
                    }
                }
            }
        }

        $reportModel = new DescriptiveReport();
        $newId = $reportModel->create([
            'student_id' => $studentId,
            'classroom_id' => $classroomId,
            'observation_id' => $observationId,
            'semester' => $semester,
            'year' => $year,
            'intro_text' => self::INTRO_TEXT,
            'student_text' => $studentText,
            'student_text_edited' => $studentText,
            'status' => 'draft'
        ]);

        if ($newId) {
            $_SESSION['success_message'] = 'Parecer descritivo gerado com sucesso!';
            header('Location: /admin/descriptive-reports/' . $newId . '/edit');
            exit;
        }

        $_SESSION['error_message'] = 'Erro ao gerar parecer descritivo. Tente novamente.';
        header('Location: /admin/descriptive-reports/create');
        exit;
    }

    /**
     * Visualizar parecer (preview)
     */
    public function show($id)
    {
        $reportModel = new DescriptiveReport();
        $report = $reportModel->find($id);

        if (!$report) {
            $_SESSION['error_message'] = 'Parecer nao encontrado.';
            header('Location: /admin/descriptive-reports');
            exit;
        }

        return $this->render('descriptive-reports/show', [
            'report' => $report
        ]);
    }

    /**
     * Formulario de edicao
     */
    public function edit($id)
    {
        $reportModel = new DescriptiveReport();
        $report = $reportModel->find($id);

        if (!$report) {
            $_SESSION['error_message'] = 'Parecer nao encontrado.';
            header('Location: /admin/descriptive-reports');
            exit;
        }

        if ($report['status'] === 'finalized') {
            $_SESSION['error_message'] = 'Parecer finalizado nao pode ser editado. Solicite revisao.';
            header('Location: /admin/descriptive-reports/' . $id);
            exit;
        }

        return $this->render('descriptive-reports/edit', [
            'report' => $report
        ]);
    }

    /**
     * Salvar edicoes do parecer
     */
    public function update($id)
    {
        $reportModel = new DescriptiveReport();
        $report = $reportModel->find($id);

        if (!$report) {
            $_SESSION['error_message'] = 'Parecer nao encontrado.';
            header('Location: /admin/descriptive-reports');
            exit;
        }

        $data = [];

        // Tab Capa
        if (isset($_POST['cover_photo_url'])) {
            $data['cover_photo_url'] = trim($_POST['cover_photo_url']);
        }

        // Tab Texto
        if (isset($_POST['student_text_edited'])) {
            $data['student_text_edited'] = trim($_POST['student_text_edited']);
        }

        // Tab Fotos dos Eixos
        if (isset($_POST['axis_photos'])) {
            $axisPhotos = [];
            $axes = ['movement', 'manual', 'music', 'stories', 'pca'];

            foreach ($axes as $axis) {
                $axisPhotos[$axis] = [];
                for ($i = 0; $i < 3; $i++) {
                    $url = trim($_POST['axis_photos'][$axis][$i]['url'] ?? '');
                    $caption = trim($_POST['axis_photos'][$axis][$i]['caption'] ?? '');
                    if (!empty($url)) {
                        $axisPhotos[$axis][] = ['url' => $url, 'caption' => $caption];
                    }
                }
            }

            $data['axis_photos'] = json_encode($axisPhotos);
        }

        if ($reportModel->update($id, $data)) {
            $_SESSION['success_message'] = 'Parecer atualizado com sucesso!';
            header('Location: /admin/descriptive-reports/' . $id);
            exit;
        }

        $_SESSION['error_message'] = 'Erro ao atualizar parecer. Tente novamente.';
        header('Location: /admin/descriptive-reports/' . $id . '/edit');
        exit;
    }

    /**
     * Finalizar parecer
     */
    public function finalize($id)
    {
        $reportModel = new DescriptiveReport();
        $report = $reportModel->find($id);

        if (!$report) {
            $_SESSION['error_message'] = 'Parecer nao encontrado.';
            header('Location: /admin/descriptive-reports');
            exit;
        }

        $userId = $_SESSION['user_id'];

        if ($reportModel->finalize($id, $userId)) {
            $_SESSION['success_message'] = 'Parecer finalizado com sucesso!';
        } else {
            $_SESSION['error_message'] = 'Erro ao finalizar parecer.';
        }

        header('Location: /admin/descriptive-reports/' . $id);
        exit;
    }

    /**
     * Reabrir parecer (apenas coordenador/admin)
     */
    public function reopen($id)
    {
        $role = $_SESSION['user_role'] ?? '';
        if (!in_array($role, ['admin', 'coordenador'])) {
            $_SESSION['error_message'] = 'Sem permissao para reabrir pareceres.';
            header('Location: /admin/descriptive-reports/' . $id);
            exit;
        }

        $reportModel = new DescriptiveReport();
        $report = $reportModel->find($id);

        if (!$report) {
            $_SESSION['error_message'] = 'Parecer nao encontrado.';
            header('Location: /admin/descriptive-reports');
            exit;
        }

        if ($reportModel->reopen($id)) {
            $_SESSION['success_message'] = 'Parecer reaberto para edicao.';
        } else {
            $_SESSION['error_message'] = 'Erro ao reabrir parecer.';
        }

        header('Location: /admin/descriptive-reports/' . $id);
        exit;
    }

    /**
     * Solicitar revisao (apenas coordenador/admin)
     */
    public function requestRevision($id)
    {
        $role = $_SESSION['user_role'] ?? '';
        if (!in_array($role, ['admin', 'coordenador'])) {
            $_SESSION['error_message'] = 'Sem permissao para solicitar revisao.';
            header('Location: /admin/descriptive-reports/' . $id);
            exit;
        }

        $notes = trim($_POST['revision_notes'] ?? '');
        if (empty($notes)) {
            $_SESSION['error_message'] = 'Informe o motivo da revisao.';
            header('Location: /admin/descriptive-reports/' . $id);
            exit;
        }

        $reportModel = new DescriptiveReport();
        $report = $reportModel->find($id);

        if (!$report) {
            $_SESSION['error_message'] = 'Parecer nao encontrado.';
            header('Location: /admin/descriptive-reports');
            exit;
        }

        if ($reportModel->requestRevision($id, $notes)) {
            $_SESSION['success_message'] = 'Revisao solicitada com sucesso.';
        } else {
            $_SESSION['error_message'] = 'Erro ao solicitar revisao.';
        }

        header('Location: /admin/descriptive-reports/' . $id);
        exit;
    }

    /**
     * Exportar parecer como PDF
     */
    public function exportPdf($id)
    {
        $model = new DescriptiveReport();
        $report = $model->find($id);

        if (!$report) {
            $_SESSION['error_message'] = 'Parecer nao encontrado.';
            header('Location: /admin/descriptive-reports');
            exit;
        }

        $studentModel = new Student();
        $student = $studentModel->find($report['student_id']);

        $classroomModel = new Classroom();
        $classroom = !empty($report['classroom_id']) ? $classroomModel->find($report['classroom_id']) : null;

        $pdfService = new \App\Services\PdfExportService();
        $pdfContent = $pdfService->generateDescriptiveReportPdf($report, $student, $classroom);

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="parecer_' . $report['id'] . '.pdf"');
        echo $pdfContent;
        exit;
    }

    /**
     * AJAX: Correcao automatica de texto via IA
     */
    public function correctText($id)
    {
        header('Content-Type: application/json');

        $reportModel = new DescriptiveReport();
        $report = $reportModel->find($id);

        if (!$report) {
            echo json_encode(['success' => false, 'error' => 'Parecer nao encontrado.']);
            exit;
        }

        $text = $report['student_text_edited'] ?: $report['student_text'];

        if (empty(trim($text))) {
            echo json_encode(['success' => false, 'error' => 'Nao ha texto para corrigir.']);
            exit;
        }

        try {
            $gemini = new GeminiService();
            $corrected = $gemini->correctDescriptiveText($text, $report['student_name']);

            // Salvar o texto corrigido
            $reportModel->updateText($id, $corrected);

            echo json_encode(['success' => true, 'text' => $corrected]);
        } catch (Exception $e) {
            error_log("Erro na correcao IA do parecer: " . $e->getMessage());
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
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
