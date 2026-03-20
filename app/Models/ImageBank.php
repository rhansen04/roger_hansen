<?php

namespace App\Models;

use App\Core\Database\Connection;
use PDO;
use PDOException;

class ImageBank
{
    protected $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    /**
     * Buscar imagens de uma pasta
     */
    public function findByFolder($folderId)
    {
        try {
            $sql = "SELECT ib.*, u.name as uploaded_by_name
                    FROM image_bank ib
                    LEFT JOIN users u ON ib.uploaded_by = u.id
                    WHERE ib.folder_id = :folder_id
                    ORDER BY ib.created_at DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':folder_id' => $folderId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar imagens da pasta: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Buscar imagem por ID
     */
    public function find($id)
    {
        try {
            $sql = "SELECT ib.*, u.name as uploaded_by_name, f.classroom_id, f.folder_type
                    FROM image_bank ib
                    LEFT JOIN users u ON ib.uploaded_by = u.id
                    LEFT JOIN image_folders f ON ib.folder_id = f.id
                    WHERE ib.id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar imagem: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Salvar registro de imagem
     */
    public function create($data)
    {
        try {
            $sql = "INSERT INTO image_bank (folder_id, filename, original_name, file_size, mime_type, uploaded_by, caption)
                    VALUES (:folder_id, :filename, :original_name, :file_size, :mime_type, :uploaded_by, :caption)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':folder_id' => $data['folder_id'],
                ':filename' => $data['filename'],
                ':original_name' => $data['original_name'],
                ':file_size' => $data['file_size'] ?? null,
                ':mime_type' => $data['mime_type'] ?? null,
                ':uploaded_by' => $data['uploaded_by'],
                ':caption' => $data['caption'] ?? null
            ]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erro ao criar registro de imagem: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Deletar imagem (registro + arquivo)
     */
    public function delete($id)
    {
        try {
            $image = $this->find($id);
            if (!$image) return false;

            // Deletar arquivo do disco
            $filePath = __DIR__ . '/../../public/uploads/image-bank/' . $image['filename'];
            if (file_exists($filePath) && !unlink($filePath)) {
                error_log('Falha ao remover arquivo: ' . $filePath);
            }

            $sql = "DELETE FROM image_bank WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("Erro ao deletar imagem: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Atualizar legenda
     */
    public function updateCaption($id, $caption)
    {
        try {
            $sql = "UPDATE image_bank SET caption = :caption WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':caption' => $caption, ':id' => $id]);
        } catch (PDOException $e) {
            error_log("Erro ao atualizar legenda: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Buscar todas as imagens de uma turma (todas as pastas)
     */
    public function getByClassroom($classroomId)
    {
        try {
            $sql = "SELECT ib.id, ib.filename, ib.caption, ib.original_name, f.folder_type, f.name as folder_name
                    FROM image_bank ib
                    INNER JOIN image_folders f ON ib.folder_id = f.id
                    WHERE f.classroom_id = :classroom_id
                    ORDER BY ib.created_at DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':classroom_id' => $classroomId]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($rows as &$row) {
                $row['url'] = '/uploads/image-bank/' . $row['filename'];
            }
            return $rows;
        } catch (PDOException $e) {
            error_log("Erro ao buscar imagens da turma: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Mover imagem para outra pasta
     */
    public function moveToFolder($id, $newFolderId)
    {
        try {
            $sql = "UPDATE image_bank SET folder_id = :folder_id WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':folder_id' => $newFolderId, ':id' => $id]);
        } catch (PDOException $e) {
            error_log("Erro ao mover imagem: " . $e->getMessage());
            return false;
        }
    }
}
