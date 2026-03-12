<?php

namespace App\Controllers\Admin;

use App\Models\Portfolio;
use App\Models\Classroom;

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
            'isEdit' => false
        ]);
    }

    /**
     * Salvar novo portfolio
     */
    public function store()
    {
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

        // Upload cover photo
        if (isset($_FILES['cover_photo']) && $_FILES['cover_photo']['error'] === UPLOAD_ERR_OK) {
            $data['cover_photo_url'] = $this->uploadPhoto($_FILES['cover_photo'], 'covers');
        }

        // Upload axis photos (JSON arrays)
        $axes = ['movement', 'manual', 'stories', 'music', 'pca'];
        foreach ($axes as $axis) {
            $photos = $this->uploadAxisPhotos($axis);
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
            $portfolio[$key] = !empty($portfolio[$key]) ? json_decode($portfolio[$key], true) : [];
        }

        return $this->render('portfolios/show', [
            'portfolio' => $portfolio
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
            $portfolio[$key] = !empty($portfolio[$key]) ? json_decode($portfolio[$key], true) : [];
        }

        $classroomModel = new Classroom();
        $classrooms = $classroomModel->all();

        return $this->render('portfolios/form', [
            'portfolio' => $portfolio,
            'classrooms' => $classrooms,
            'isEdit' => true
        ]);
    }

    /**
     * Atualizar portfolio
     */
    public function update($id)
    {
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

        // Upload cover photo
        if (isset($_FILES['cover_photo']) && $_FILES['cover_photo']['error'] === UPLOAD_ERR_OK) {
            $data['cover_photo_url'] = $this->uploadPhoto($_FILES['cover_photo'], 'covers');
        }

        // Upload axis photos - merge with existing
        $axes = ['movement', 'manual', 'stories', 'music', 'pca'];
        foreach ($axes as $axis) {
            $existingKey = "axis_{$axis}_photos";
            $existing = !empty($portfolio[$existingKey]) ? json_decode($portfolio[$existingKey], true) : [];
            if (!is_array($existing)) $existing = [];

            // Remover fotos marcadas
            $removeKey = "remove_{$axis}_photos";
            if (!empty($_POST[$removeKey]) && is_array($_POST[$removeKey])) {
                foreach ($_POST[$removeKey] as $removeIdx) {
                    unset($existing[(int)$removeIdx]);
                }
                $existing = array_values($existing);
            }

            // Adicionar novas fotos
            $newPhotos = $this->uploadAxisPhotos($axis);
            $merged = array_merge($existing, $newPhotos);

            $data[$existingKey] = json_encode(array_slice($merged, 0, 3)); // max 3 per axis
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

        $pdfService = new \App\Services\PdfExportService();
        $pdfContent = $pdfService->generatePortfolioPdf($portfolio, $classroom);

        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="portfolio_' . $portfolio['id'] . '.pdf"');
        echo $pdfContent;
        exit;
    }

    /**
     * Corrigir texto com IA (AJAX)
     */
    public function correctText($id)
    {
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
     * Upload de foto individual
     */
    private function uploadPhoto($file, $subdir)
    {
        $uploadDir = __DIR__ . '/../../../public/uploads/portfolios/' . $subdir . '/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0775, true);
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $filename = uniqid('port_') . '.' . $ext;
        $destPath = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $destPath)) {
            return '/uploads/portfolios/' . $subdir . '/' . $filename;
        }
        return null;
    }

    /**
     * Upload de fotos de eixo (multiplas)
     */
    private function uploadAxisPhotos($axis)
    {
        $fieldName = "axis_{$axis}_photos";
        $photos = [];

        if (empty($_FILES[$fieldName]) || !is_array($_FILES[$fieldName]['name'])) {
            return $photos;
        }

        $uploadDir = __DIR__ . '/../../../public/uploads/portfolios/axes/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0775, true);
        }

        $fileCount = count($_FILES[$fieldName]['name']);
        for ($i = 0; $i < $fileCount; $i++) {
            if ($_FILES[$fieldName]['error'][$i] !== UPLOAD_ERR_OK) continue;

            $ext = strtolower(pathinfo($_FILES[$fieldName]['name'][$i], PATHINFO_EXTENSION));
            if (!in_array($ext, ['jpg', 'jpeg', 'png'])) continue;

            $filename = uniqid("axis_{$axis}_") . '.' . $ext;
            $destPath = $uploadDir . $filename;

            if (move_uploaded_file($_FILES[$fieldName]['tmp_name'][$i], $destPath)) {
                $caption = $_POST["axis_{$axis}_captions"][$i] ?? '';
                $photos[] = [
                    'url' => '/uploads/portfolios/axes/' . $filename,
                    'caption' => $caption
                ];
            }
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
