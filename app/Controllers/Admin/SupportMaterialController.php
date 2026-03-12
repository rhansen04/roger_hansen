<?php

namespace App\Controllers\Admin;

use App\Models\SupportMaterialFolder;
use App\Models\SupportMaterial;

class SupportMaterialController
{
    /**
     * Arvore de pastas
     */
    public function index()
    {
        $folderModel = new SupportMaterialFolder();
        $tree = $folderModel->getTree();

        return $this->render('support-materials/index', [
            'tree' => $tree
        ]);
    }

    /**
     * Conteudo de uma pasta
     */
    public function folder($id)
    {
        $folderModel = new SupportMaterialFolder();
        $folder = $folderModel->find($id);

        if (!$folder) {
            $_SESSION['error_message'] = 'Pasta nao encontrada.';
            header('Location: /admin/support-materials');
            exit;
        }

        $materialModel = new SupportMaterial();
        $materials = $materialModel->findByFolder($id);
        $subfolders = $folderModel->children($id);
        $breadcrumb = $folderModel->getBreadcrumb($id);

        $role = $_SESSION['user_role'] ?? 'admin';
        $canUpload = ($role === 'admin');

        return $this->render('support-materials/folder', [
            'folder' => $folder,
            'materials' => $materials,
            'subfolders' => $subfolders,
            'breadcrumb' => $breadcrumb,
            'canUpload' => $canUpload
        ]);
    }

    /**
     * Upload de arquivo para pasta (POST)
     */
    public function upload($id)
    {
        $role = $_SESSION['user_role'] ?? 'admin';
        if ($role !== 'admin') {
            $_SESSION['error_message'] = 'Apenas administradores podem fazer upload.';
            header("Location: /admin/support-materials/folder/{$id}");
            exit;
        }

        $folderModel = new SupportMaterialFolder();
        $folder = $folderModel->find($id);

        if (!$folder) {
            $_SESSION['error_message'] = 'Pasta nao encontrada.';
            header('Location: /admin/support-materials');
            exit;
        }

        if (empty($_FILES['file']) || $_FILES['file']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error_message'] = 'Nenhum arquivo selecionado ou erro no upload.';
            header("Location: /admin/support-materials/folder/{$id}");
            exit;
        }

        $file = $_FILES['file'];
        $title = trim($_POST['title'] ?? '');
        if (empty($title)) {
            $title = pathinfo($file['name'], PATHINFO_FILENAME);
        }

        $uploadDir = __DIR__ . '/../../../public/uploads/support-materials/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0775, true);
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $filename = uniqid('mat_') . '.' . $ext;
        $destPath = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $destPath)) {
            $materialModel = new SupportMaterial();
            $materialModel->create([
                'folder_id' => $id,
                'title' => $title,
                'filename' => $filename,
                'original_name' => $file['name'],
                'file_size' => $file['size'],
                'mime_type' => $file['type'],
                'uploaded_by' => $_SESSION['user_id']
            ]);
            $_SESSION['success_message'] = 'Arquivo enviado com sucesso!';
        } else {
            $_SESSION['error_message'] = 'Erro ao salvar arquivo.';
        }

        header("Location: /admin/support-materials/folder/{$id}");
        exit;
    }

    /**
     * Deletar material (POST)
     */
    public function deleteMaterial($id)
    {
        $role = $_SESSION['user_role'] ?? 'admin';
        if ($role !== 'admin') {
            $_SESSION['error_message'] = 'Apenas administradores podem deletar materiais.';
            header('Location: /admin/support-materials');
            exit;
        }

        $materialModel = new SupportMaterial();
        $material = $materialModel->find($id);

        if (!$material) {
            $_SESSION['error_message'] = 'Material nao encontrado.';
            header('Location: /admin/support-materials');
            exit;
        }

        $folderId = $material['folder_id'];

        if ($materialModel->delete($id)) {
            $_SESSION['success_message'] = 'Material deletado com sucesso!';
        } else {
            $_SESSION['error_message'] = 'Erro ao deletar material.';
        }

        header("Location: /admin/support-materials/folder/{$folderId}");
        exit;
    }

    /**
     * Download de arquivo
     */
    public function download($id)
    {
        $materialModel = new SupportMaterial();
        $material = $materialModel->find($id);

        if (!$material) {
            $_SESSION['error_message'] = 'Material nao encontrado.';
            header('Location: /admin/support-materials');
            exit;
        }

        $filePath = __DIR__ . '/../../../public/uploads/support-materials/' . $material['filename'];

        if (!file_exists($filePath)) {
            $_SESSION['error_message'] = 'Arquivo nao encontrado no servidor.';
            header("Location: /admin/support-materials/folder/{$material['folder_id']}");
            exit;
        }

        header('Content-Type: ' . ($material['mime_type'] ?? 'application/octet-stream'));
        header('Content-Disposition: attachment; filename="' . $material['original_name'] . '"');
        header('Content-Length: ' . filesize($filePath));
        header('Cache-Control: no-cache');
        readfile($filePath);
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
