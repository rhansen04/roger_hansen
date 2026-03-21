<?php

namespace App\Controllers\Admin;

use App\Models\DescriptiveReport;
use App\Models\Student;
use App\Models\Classroom;
use App\Models\Observation;
use App\Models\CoordinatorComment;
use App\Services\GeminiService;
use App\Core\Security\Csrf;
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
        $selectedSemester = !empty($_GET['semester']) ? (int) $_GET['semester'] : null;
        $selectedYear = !empty($_GET['year']) ? (int) $_GET['year'] : (int) date('Y');

        // Buscar observacoes do periodo para o aluno selecionado
        $observations = [];
        if ($selectedStudentId) {
            if ($selectedSemester) {
                $observations = $obsModel->findByStudentAndSemester((int) $selectedStudentId, $selectedSemester, $selectedYear);
            } else {
                $observations = $obsModel->findByStudent($selectedStudentId);
            }
        }

        return $this->render('descriptive-reports/create', [
            'students' => $students,
            'classrooms' => $classrooms,
            'observations' => $observations,
            'selectedStudentId' => $selectedStudentId,
            'selectedSemester' => $selectedSemester,
            'selectedYear' => $selectedYear
        ]);
    }

    /**
     * Salvar novo parecer - compilar texto das observacoes
     */
    public function store()
    {
        Csrf::verify();

        if (empty($_POST['student_id']) || empty($_POST['semester']) || empty($_POST['year'])) {
            $_SESSION['error_message'] = 'Preencha todos os campos obrigatorios.';
            header('Location: /admin/descriptive-reports/create');
            exit;
        }

        $studentId = (int)$_POST['student_id'];
        $semester = (int)$_POST['semester'];
        $year = (int)$_POST['year'];
        $classroomId = !empty($_POST['classroom_id']) ? (int)$_POST['classroom_id'] : null;
        $obsModel = new Observation();
        $observations = $obsModel->findByStudentAndSemester($studentId, $semester, $year);
        $studentText = $obsModel->compileSemesterText($studentId, $semester, $year);
        $latestObservation = !empty($observations) ? end($observations) : null;
        $observationId = !empty($latestObservation['id']) ? (int) $latestObservation['id'] : null;

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

        // Se nao encontrou observacao, avisar com link para criar
        if (empty($studentText)) {
            $studentModel2 = new Student();
            $studentInfo = $studentModel2->find($studentId);
            $studentName = $studentInfo ? htmlspecialchars($studentInfo['name']) : 'este aluno';
            $_SESSION['error_message'] = 'Nenhuma observacao encontrada para ' . $studentName . ' no ' . $semester . 'o semestre de ' . $year . '. <a href="/admin/observations/create?student_id=' . (int)$studentId . '" class="alert-link">Criar observacao primeiro</a>.';
            header('Location: /admin/descriptive-reports/create');
            exit;
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

        $commentModel = new CoordinatorComment();
        $comments = $commentModel->findByContent('descriptive_report', (int) $id);

        return $this->render('descriptive-reports/show', [
            'report'   => $report,
            'comments' => $comments,
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
        Csrf::verify();

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
            header('Location: /admin/descriptive-reports/' . $id . '/edit');
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
        Csrf::verify();

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
        Csrf::verify();

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
     * Deletar parecer descritivo
     */
    public function delete($id)
    {
        Csrf::verify();

        $reportModel = new DescriptiveReport();
        $report = $reportModel->find($id);

        if (!$report) {
            $_SESSION['error_message'] = 'Parecer nao encontrado.';
            header('Location: /admin/descriptive-reports');
            exit;
        }

        // Limpar comentários de coordenacao vinculados
        $commentModel = new \App\Models\CoordinatorComment();
        $commentModel->deleteByContent('descriptive_report', (int)$id);

        if ($reportModel->delete($id)) {
            $_SESSION['success_message'] = 'Parecer excluido com sucesso!';
        } else {
            $_SESSION['error_message'] = 'Erro ao excluir parecer.';
        }

        header('Location: /admin/descriptive-reports');
        exit;
    }

    /**
     * Solicitar revisao (apenas coordenador/admin)
     */
    public function requestRevision($id)
    {
        Csrf::verify();

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

        // BUG-040: permission check — professor can only export their own classroom's reports
        $userRole = $_SESSION['user_role'] ?? '';
        $userId   = (int)($_SESSION['user_id'] ?? 0);
        if ($userRole === 'professor') {
            $db = \App\Core\Database\Connection::getInstance();
            $stmt = $db->prepare("SELECT c.teacher_id FROM descriptive_reports dr LEFT JOIN classrooms c ON dr.classroom_id = c.id WHERE dr.id = ? LIMIT 1");
            $stmt->execute([$id]);
            $row = $stmt->fetch();
            if (!$row || (int)$row['teacher_id'] !== $userId) {
                $_SESSION['error_message'] = 'Sem permissao para exportar este parecer.';
                header('Location: /admin/descriptive-reports');
                exit;
            }
        }

        $studentModel = new Student();
        $student = $studentModel->find($report['student_id']);

        $classroomModel = new Classroom();
        $classroom = !empty($report['classroom_id']) ? $classroomModel->find($report['classroom_id']) : null;

        try {
            $pdfService = new \App\Services\PdfExportService();
            // BUG-039: verify content before sending headers
            $pdfContent = $pdfService->generateDescriptiveReportPdf($report, $student, $classroom);

            if (empty($pdfContent)) {
                $_SESSION['error_message'] = 'Erro ao gerar PDF. Tente novamente.';
                header('Location: /admin/descriptive-reports/' . $id);
                exit;
            }

            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="parecer_' . $report['id'] . '.pdf"');
            echo $pdfContent;
            exit;
        } catch (\Throwable $e) {
            error_log('Descriptive Report PDF export error (ID ' . $id . '): ' . $e->getMessage());
            $_SESSION['error_message'] = 'Erro ao gerar PDF do parecer. Verifique se a biblioteca mPDF esta instalada corretamente.';
            header("Location: /admin/descriptive-reports/{$id}");
            exit;
        }
    }

    /**
     * AJAX: Recompilar texto a partir da observacao vinculada
     */
    public function recompile($id)
    {
        Csrf::verify();

        header('Content-Type: application/json');

        $reportModel = new DescriptiveReport();
        $report = $reportModel->find($id);

        if (!$report) {
            echo json_encode(['success' => false, 'error' => 'Parecer nao encontrado.']);
            exit;
        }

        $obsModel = new Observation();
        $observations = $obsModel->findByStudentAndSemester((int) $report['student_id'], (int) $report['semester'], (int) $report['year']);
        $studentText = $obsModel->compileSemesterText((int) $report['student_id'], (int) $report['semester'], (int) $report['year']);

        if (empty($studentText)) {
            echo json_encode(['success' => false, 'error' => 'Nao existem observacoes com texto para este aluno no semestre informado.']);
            exit;
        }

        $latestObservation = !empty($observations) ? end($observations) : null;
        $latestObservationId = !empty($latestObservation['id']) ? (int) $latestObservation['id'] : null;

        // Atualizar o parecer
        $reportModel->update($id, [
            'observation_id' => $latestObservationId,
            'student_text' => $studentText,
            'student_text_edited' => $studentText,
        ]);

        echo json_encode(['success' => true, 'text' => $studentText]);
        exit;
    }

    /**
     * AJAX: Correcao automatica de texto via IA
     */
    public function correctText($id)
    {
        Csrf::verify();

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
        } catch (\App\Services\GeminiException $e) {
            http_response_code(503);
            echo json_encode(['error' => 'Servico de IA temporariamente indisponivel.']);
        } catch (Exception $e) {
            error_log('correctText error: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Erro interno ao corrigir texto.']);
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
