<?php

namespace App\Controllers\Admin;

use App\Models\Portfolio;
use App\Models\Classroom;
use App\Models\ImageBank;
use App\Models\CoordinatorComment;
use App\Core\Security\Csrf;

class PortfolioController
{
    /**
     * Listar portfolios
     */
    public function index()
    {
        $portfolioModel = new Portfolio();
        $classroomModel = new Classroom();
        $role = $_SESSION['user_role'] ?? 'admin';

        $portfolios = $portfolioModel->all();

        // Filtrar por professor se necessario
        if ($role === 'professor') {
            $myClassrooms = $classroomModel->getByTeacher($_SESSION['user_id']);
            $myClassroomIds = array_column($myClassrooms, 'id');
            $portfolios = array_filter($portfolios, fn($p) => in_array($p['classroom_id'], $myClassroomIds));
        }

        // Turmas para o form de criacao
        if ($role === 'professor') {
            $classrooms = $classroomModel->getByTeacher($_SESSION['user_id']);
        } else {
            $classrooms = $classroomModel->all();
        }
        $classrooms = array_filter($classrooms, fn($c) => $c['status'] === 'active');

        return $this->render('portfolios/index', [
            'portfolios' => $portfolios,
            'classrooms' => $classrooms
        ]);
    }

    /**
     * Form de criacao
     */
    public function create()
    {
        $classroomModel = new Classroom();
        $role = $_SESSION['user_role'] ?? 'admin';

        if ($role === 'professor') {
            $classrooms = $classroomModel->getByTeacher($_SESSION['user_id']);
        } else {
            $classrooms = $classroomModel->all();
        }
        $classrooms = array_filter($classrooms, fn($c) => $c['status'] === 'active');

        return $this->render('portfolios/form', [
            'portfolio' => null,
            'classrooms' => $classrooms,
            'bankImages' => [],
            'isEdit' => false
        ]);
    }

    /**
     * Salvar novo portfolio
     */
    public function store()
    {
        Csrf::verify();

        $data = [
            'classroom_id' => $_POST['classroom_id'] ?? null,
            'semester' => $_POST['semester'] ?? 1,
            'year' => $_POST['year'] ?? date('Y'),
            'teacher_message' => trim($_POST['teacher_message'] ?? ''),
            'axis_movement_description' => trim($_POST['axis_movement_description'] ?? ''),
            'axis_manual_description' => trim($_POST['axis_manual_description'] ?? ''),
            'axis_stories_description' => trim($_POST['axis_stories_description'] ?? ''),
            'axis_music_description' => trim($_POST['axis_music_description'] ?? ''),
            'axis_pca_description' => trim($_POST['axis_pca_description'] ?? '')
        ];

        // Foto de capa via banco de imagens
        if (!empty($_POST['cover_photo_bank_url'])) {
            $data['cover_photo_url'] = $_POST['cover_photo_bank_url'];
        }

        // Fotos dos eixos via banco de imagens
        $axes = ['movement', 'manual', 'stories', 'music', 'pca'];
        foreach ($axes as $axis) {
            $photos = $this->collectBankPhotos($axis);
            if (!empty($photos)) {
                $data["axis_{$axis}_photos"] = json_encode($photos);
            }
        }

        if (empty($data['classroom_id'])) {
            $_SESSION['error_message'] = 'Selecione uma turma.';
            header('Location: /admin/portfolios/create');
            exit;
        }

        $portfolioModel = new Portfolio();
        $id = $portfolioModel->create($data);

        if ($id) {
            $_SESSION['success_message'] = 'Portfolio criado com sucesso!';
            header("Location: /admin/portfolios/{$id}");
            exit;
        }

        $_SESSION['error_message'] = 'Erro ao criar portfolio. Verifique se ja existe um portfolio para esta turma/semestre/ano.';
        header('Location: /admin/portfolios/create');
        exit;
    }

    /**
     * Visualizar portfolio
     */
    public function show($id)
    {
        $portfolioModel = new Portfolio();
        $portfolio = $portfolioModel->find($id);

        if (!$portfolio) {
            $_SESSION['error_message'] = 'Portfolio nao encontrado.';
            header('Location: /admin/portfolios');
            exit;
        }

        // Decode JSON photos
        $axes = ['movement', 'manual', 'stories', 'music', 'pca'];
        foreach ($axes as $axis) {
            $key = "axis_{$axis}_photos";
            $photos = json_decode($portfolio[$key] ?? '', true);
            if (!is_array($photos)) $photos = [];
            $portfolio[$key] = $photos;
        }

        $commentModel = new CoordinatorComment();
        $comments = $commentModel->findByContent('portfolio', (int) $id);

        return $this->render('portfolios/show', [
            'portfolio' => $portfolio,
            'comments'  => $comments,
        ]);
    }

    /**
     * Form de edicao
     */
    public function edit($id)
    {
        $portfolioModel = new Portfolio();
        $portfolio = $portfolioModel->find($id);

        if (!$portfolio) {
            $_SESSION['error_message'] = 'Portfolio nao encontrado.';
            header('Location: /admin/portfolios');
            exit;
        }

        if ($portfolio['status'] === 'finalized') {
            $_SESSION['error_message'] = 'Portfolio finalizado nao pode ser editado.';
            header("Location: /admin/portfolios/{$id}");
            exit;
        }

        // Decode JSON photos
        $axes = ['movement', 'manual', 'stories', 'music', 'pca'];
        foreach ($axes as $axis) {
            $key = "axis_{$axis}_photos";
            $photos = json_decode($portfolio[$key] ?? '', true);
            if (!is_array($photos)) $photos = [];
            $portfolio[$key] = $photos;
        }

        $classroomModel = new Classroom();
        $classrooms = $classroomModel->all();

        $imageBankModel = new ImageBank();
        $bankImages = $imageBankModel->getByClassroom($portfolio['classroom_id']);

        return $this->render('portfolios/form', [
            'portfolio' => $portfolio,
            'classrooms' => $classrooms,
            'bankImages' => $bankImages,
            'isEdit' => true
        ]);
    }

    /**
     * Atualizar portfolio
     */
    public function update($id)
    {
        Csrf::verify();

        $portfolioModel = new Portfolio();
        $portfolio = $portfolioModel->find($id);

        if (!$portfolio) {
            $_SESSION['error_message'] = 'Portfolio nao encontrado.';
            header('Location: /admin/portfolios');
            exit;
        }

        $data = [
            'teacher_message' => trim($_POST['teacher_message'] ?? ''),
            'axis_movement_description' => trim($_POST['axis_movement_description'] ?? ''),
            'axis_manual_description' => trim($_POST['axis_manual_description'] ?? ''),
            'axis_stories_description' => trim($_POST['axis_stories_description'] ?? ''),
            'axis_music_description' => trim($_POST['axis_music_description'] ?? ''),
            'axis_pca_description' => trim($_POST['axis_pca_description'] ?? '')
        ];

        // Foto de capa via banco de imagens
        if (!empty($_POST['cover_photo_bank_url'])) {
            $data['cover_photo_url'] = $_POST['cover_photo_bank_url'];
        }

        // Fotos dos eixos via banco de imagens
        $axes = ['movement', 'manual', 'stories', 'music', 'pca'];
        foreach ($axes as $axis) {
            $newPhotos = $this->collectBankPhotos($axis);
            $existingKey = "axis_{$axis}_photos";
            $existing = json_decode($portfolio[$existingKey] ?? '', true);
            if (!is_array($existing)) $existing = [];

            // Remover fotos marcadas
            $removeKey = "remove_{$axis}_photos";
            if (!empty($_POST[$removeKey]) && is_array($_POST[$removeKey])) {
                foreach ($_POST[$removeKey] as $removeIdx) {
                    unset($existing[(int)$removeIdx]);
                }
                $existing = array_values($existing);
            }

            $merged = array_merge($existing, $newPhotos);
            // BUG-042: enforce per-axis photo limit
            $merged = array_values(array_slice($merged, 0, 6));
            $data[$existingKey] = json_encode($merged);
        }

        if ($portfolioModel->update($id, $data)) {
            $_SESSION['success_message'] = 'Portfolio atualizado com sucesso!';
            header("Location: /admin/portfolios/{$id}");
            exit;
        }

        $_SESSION['error_message'] = 'Erro ao atualizar portfolio.';
        header("Location: /admin/portfolios/{$id}/edit");
        exit;
    }

    /**
     * Finalizar portfolio
     */
    public function finalize($id)
    {
        Csrf::verify();

        $portfolioModel = new Portfolio();
        if ($portfolioModel->finalize($id, $_SESSION['user_id'])) {
            $_SESSION['success_message'] = 'Portfolio finalizado com sucesso!';
        } else {
            $_SESSION['error_message'] = 'Erro ao finalizar portfolio.';
        }
        header("Location: /admin/portfolios/{$id}");
        exit;
    }

    /**
     * Solicitar revisao
     */
    public function requestRevision($id)
    {
        Csrf::verify();

        $notes = trim($_POST['revision_notes'] ?? '');
        if (empty($notes)) {
            $_SESSION['error_message'] = 'Informe as observacoes da revisao.';
            header("Location: /admin/portfolios/{$id}");
            exit;
        }

        $portfolioModel = new Portfolio();
        if ($portfolioModel->requestRevision($id, $notes, $_SESSION['user_id'])) {
            $_SESSION['success_message'] = 'Revisao solicitada com sucesso!';
        } else {
            $_SESSION['error_message'] = 'Erro ao solicitar revisao.';
        }
        header("Location: /admin/portfolios/{$id}");
        exit;
    }

    /**
     * Reabrir portfolio
     */
    public function reopen($id)
    {
        Csrf::verify();

        $portfolioModel = new Portfolio();
        if ($portfolioModel->reopen($id)) {
            $_SESSION['success_message'] = 'Portfolio reaberto com sucesso!';
        } else {
            $_SESSION['error_message'] = 'Erro ao reabrir portfolio.';
        }
        header("Location: /admin/portfolios/{$id}");
        exit;
    }

    /**
     * Deletar portfolio
     */
    public function delete($id)
    {
        Csrf::verify();

        $portfolioModel = new Portfolio();
        $portfolio = $portfolioModel->find($id);

        if (!$portfolio) {
            $_SESSION['error_message'] = 'Portfolio nao encontrado.';
            header('Location: /admin/portfolios');
            exit;
        }

        // Limpar comentários de coordenacao vinculados
        $commentModel = new \App\Models\CoordinatorComment();
        $commentModel->deleteByContent('portfolio', (int)$id);

        if ($portfolioModel->delete($id)) {
            $_SESSION['success_message'] = 'Portfolio excluido com sucesso!';
        } else {
            $_SESSION['error_message'] = 'Erro ao excluir portfolio.';
        }

        header('Location: /admin/portfolios');
        exit;
    }

    /**
     * Exportar portfolio como PDF
     */
    public function exportPdf($id)
    {
        $portfolioModel = new Portfolio();
        $portfolio = $portfolioModel->find($id);

        if (!$portfolio) {
            $_SESSION['error_message'] = 'Portfolio nao encontrado.';
            header('Location: /admin/portfolios');
            exit;
        }

        $classroomModel = new Classroom();
        $classroom = $classroomModel->find($portfolio['classroom_id']);

        try {
            $pdfService = new \App\Services\PdfExportService();
            $pdfContent = $pdfService->generatePortfolioPdf($portfolio, $classroom);

            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename="portfolio_' . $portfolio['id'] . '.pdf"');
            echo $pdfContent;
            exit;
        } catch (\Throwable $e) {
            error_log('Portfolio PDF export error (ID ' . $id . '): ' . $e->getMessage());
            $_SESSION['error_message'] = 'Erro ao gerar PDF do portfolio. Verifique se a biblioteca mPDF esta instalada corretamente.';
            header("Location: /admin/portfolios/{$id}");
            exit;
        }
    }

    /**
     * Corrigir texto com IA (AJAX)
     */
    public function correctText($id)
    {
        Csrf::verify();

        header('Content-Type: application/json');

        try {
            $portfolioModel = new Portfolio();
            $portfolio = $portfolioModel->find($id);

            if (!$portfolio || empty($portfolio['teacher_message'])) {
                echo json_encode(['success' => false, 'error' => 'Mensagem nao encontrada']);
                exit;
            }

            $geminiService = new \App\Services\GeminiService();
            $corrected = $geminiService->correctPortfolioText($portfolio['teacher_message']);

            $portfolioModel->update($id, ['teacher_message_corrected' => $corrected]);

            echo json_encode(['success' => true, 'corrected_text' => $corrected]);
        } catch (\Exception $e) {
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
        exit;
    }

    /**
     * Coletar fotos selecionadas do banco de imagens para um eixo
     */
    private function collectBankPhotos($axis)
    {
        $urls = $_POST["axis_{$axis}_bank_urls"] ?? [];
        $captions = $_POST["axis_{$axis}_bank_captions"] ?? [];
        $photos = [];

        if (!is_array($urls)) return $photos;

        foreach ($urls as $i => $url) {
            if (empty($url)) continue;
            $photos[] = [
                'url' => $url,
                'caption' => $captions[$i] ?? ''
            ];
        }
        return $photos;
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
