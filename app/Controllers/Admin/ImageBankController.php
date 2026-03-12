<?php

namespace App\Controllers\Admin;

use App\Models\ImageFolder;
use App\Models\ImageBank;
use App\Models\Classroom;

class ImageBankController
{
    /**
     * Listar turmas com seus bancos de imagem
     */
    public function index()
    {
        $classroomModel = new Classroom();
        $role = $_SESSION['user_role'] ?? 'admin';

        if ($role === 'professor') {
            $classrooms = $classroomModel->getByTeacher($_SESSION['user_id']);
        } else {
            $classrooms = $classroomModel->all();
        }

        // Filtrar apenas turmas ativas
        $classrooms = array_filter($classrooms, fn($c) => $c['status'] === 'active');

        return $this->render('image-bank/index', [
            'classrooms' => $classrooms
        ]);
    }

    /**
     * Mostrar pastas de uma turma
     */
    public function classroom($classroomId)
    {
        $classroomModel = new Classroom();
        $classroom = $classroomModel->find($classroomId);

        if (!$classroom) {
            $_SESSION['error_message'] = 'Turma nao encontrada.';
            header('Location: /admin/image-bank');
            exit;
        }

        // Garantir pastas
        $folderModel = new ImageFolder();
        $folderModel->ensureFoldersForClassroom($classroomId);

        $folders = $folderModel->findByClassroom($classroomId);
        $imageCounts = $folderModel->countImagesByFolder($classroomId);

        return $this->render('image-bank/classroom', [
            'classroom' => $classroom,
            'folders' => $folders,
            'imageCounts' => $imageCounts
        ]);
    }

    /**
     * Mostrar imagens de uma pasta
     */
    public function folder($folderId)
    {
        $folderModel = new ImageFolder();
        $folder = $folderModel->find($folderId);

        if (!$folder) {
            $_SESSION['error_message'] = 'Pasta nao encontrada.';
            header('Location: /admin/image-bank');
            exit;
        }

        $imageModel = new ImageBank();
        $images = $imageModel->findByFolder($folderId);

        // Outras pastas da mesma turma (para mover imagens)
        $otherFolders = $folderModel->findByClassroom($folder['classroom_id']);
        $otherFolders = array_filter($otherFolders, fn($f) => $f['id'] != $folderId);

        $role = $_SESSION['user_role'] ?? 'admin';
        $canEdit = ($role === 'admin' || $role === 'professor');

        return $this->render('image-bank/folder', [
            'folder' => $folder,
            'images' => $images,
            'otherFolders' => $otherFolders,
            'canEdit' => $canEdit
        ]);
    }

    /**
     * Upload de imagens (POST)
     */
    public function upload($folderId)
    {
        $role = $_SESSION['user_role'] ?? 'admin';
        if ($role === 'coordenador') {
            $_SESSION['error_message'] = 'Voce nao tem permissao para fazer upload.';
            header("Location: /admin/image-bank/folder/{$folderId}");
            exit;
        }

        $folderModel = new ImageFolder();
        $folder = $folderModel->find($folderId);

        if (!$folder) {
            $_SESSION['error_message'] = 'Pasta nao encontrada.';
            header('Location: /admin/image-bank');
            exit;
        }

        if (empty($_FILES['images']) || !is_array($_FILES['images']['name'])) {
            $_SESSION['error_message'] = 'Nenhuma imagem selecionada.';
            header("Location: /admin/image-bank/folder/{$folderId}");
            exit;
        }

        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        $uploadDir = __DIR__ . '/../../../public/uploads/image-bank/' . $folder['classroom_id'] . '/' . $folder['folder_type'] . '/';

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0775, true);
        }

        $imageModel = new ImageBank();
        $uploaded = 0;
        $errors = 0;

        $fileCount = count($_FILES['images']['name']);
        for ($i = 0; $i < $fileCount; $i++) {
            if ($_FILES['images']['error'][$i] !== UPLOAD_ERR_OK) {
                $errors++;
                continue;
            }

            $mimeType = $_FILES['images']['type'][$i];
            if (!in_array($mimeType, $allowedTypes)) {
                $errors++;
                continue;
            }

            $originalName = $_FILES['images']['name'][$i];
            $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
            if (!in_array($ext, ['jpg', 'jpeg', 'png'])) {
                $errors++;
                continue;
            }

            $filename = $folder['classroom_id'] . '/' . $folder['folder_type'] . '/' . uniqid('img_') . '.' . $ext;
            $fullPath = __DIR__ . '/../../../public/uploads/image-bank/' . $filename;

            // Redimensionar com GD
            $tmpFile = $_FILES['images']['tmp_name'][$i];
            if ($this->resizeAndSave($tmpFile, $fullPath, $mimeType, 1920)) {
                $imageModel->create([
                    'folder_id' => $folderId,
                    'filename' => $filename,
                    'original_name' => $originalName,
                    'file_size' => filesize($fullPath),
                    'mime_type' => $mimeType,
                    'uploaded_by' => $_SESSION['user_id']
                ]);
                $uploaded++;
            } else {
                $errors++;
            }
        }

        if ($uploaded > 0) {
            $_SESSION['success_message'] = "{$uploaded} imagem(ns) enviada(s) com sucesso!" . ($errors > 0 ? " ({$errors} com erro)" : '');
        } else {
            $_SESSION['error_message'] = 'Nenhuma imagem foi enviada. Verifique os formatos (JPG/PNG).';
        }

        header("Location: /admin/image-bank/folder/{$folderId}");
        exit;
    }

    /**
     * Deletar imagem (POST)
     */
    public function deleteImage($id)
    {
        $role = $_SESSION['user_role'] ?? 'admin';
        if ($role === 'coordenador') {
            $_SESSION['error_message'] = 'Voce nao tem permissao para deletar imagens.';
            header('Location: /admin/image-bank');
            exit;
        }

        $imageModel = new ImageBank();
        $image = $imageModel->find($id);

        if (!$image) {
            $_SESSION['error_message'] = 'Imagem nao encontrada.';
            header('Location: /admin/image-bank');
            exit;
        }

        $folderId = $image['folder_id'];

        if ($imageModel->delete($id)) {
            $_SESSION['success_message'] = 'Imagem deletada com sucesso!';
        } else {
            $_SESSION['error_message'] = 'Erro ao deletar imagem.';
        }

        header("Location: /admin/image-bank/folder/{$folderId}");
        exit;
    }

    /**
     * Mover imagem para outra pasta (POST)
     */
    public function moveImage($id)
    {
        $role = $_SESSION['user_role'] ?? 'admin';
        if ($role === 'coordenador') {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Sem permissao']);
            exit;
        }

        $imageModel = new ImageBank();
        $image = $imageModel->find($id);

        if (!$image) {
            $_SESSION['error_message'] = 'Imagem nao encontrada.';
            header('Location: /admin/image-bank');
            exit;
        }

        $newFolderId = $_POST['folder_id'] ?? null;
        if (!$newFolderId) {
            $_SESSION['error_message'] = 'Pasta destino nao informada.';
            header("Location: /admin/image-bank/folder/{$image['folder_id']}");
            exit;
        }

        if ($imageModel->moveToFolder($id, $newFolderId)) {
            $_SESSION['success_message'] = 'Imagem movida com sucesso!';
        } else {
            $_SESSION['error_message'] = 'Erro ao mover imagem.';
        }

        header("Location: /admin/image-bank/folder/{$image['folder_id']}");
        exit;
    }

    /**
     * Atualizar legenda (POST, AJAX)
     */
    public function updateCaption($id)
    {
        header('Content-Type: application/json');

        $role = $_SESSION['user_role'] ?? 'admin';
        if ($role === 'coordenador') {
            echo json_encode(['success' => false, 'error' => 'Sem permissao']);
            exit;
        }

        $imageModel = new ImageBank();
        $caption = $_POST['caption'] ?? '';

        if ($imageModel->updateCaption($id, $caption)) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Erro ao atualizar legenda']);
        }
        exit;
    }

    /**
     * Redimensionar imagem e salvar
     */
    private function resizeAndSave($sourcePath, $destPath, $mimeType, $maxWidth)
    {
        try {
            if ($mimeType === 'image/jpeg' || $mimeType === 'image/jpg') {
                $source = imagecreatefromjpeg($sourcePath);
            } elseif ($mimeType === 'image/png') {
                $source = imagecreatefrompng($sourcePath);
            } else {
                return false;
            }

            if (!$source) return false;

            $origWidth = imagesx($source);
            $origHeight = imagesy($source);

            if ($origWidth > $maxWidth) {
                $ratio = $maxWidth / $origWidth;
                $newWidth = $maxWidth;
                $newHeight = (int)($origHeight * $ratio);

                $resized = imagecreatetruecolor($newWidth, $newHeight);

                if ($mimeType === 'image/png') {
                    imagealphablending($resized, false);
                    imagesavealpha($resized, true);
                }

                imagecopyresampled($resized, $source, 0, 0, 0, 0, $newWidth, $newHeight, $origWidth, $origHeight);
                imagedestroy($source);
                $source = $resized;
            }

            if ($mimeType === 'image/jpeg' || $mimeType === 'image/jpg') {
                $result = imagejpeg($source, $destPath, 85);
            } else {
                $result = imagepng($source, $destPath, 8);
            }

            imagedestroy($source);
            return $result;
        } catch (\Exception $e) {
            error_log("Erro ao redimensionar imagem: " . $e->getMessage());
            return false;
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
            echo "<h2>Pagina {$view} em construcao</h2>";
        }
        $content = ob_get_clean();

        include __DIR__ . "/../../../views/layouts/admin.php";
    }
}
