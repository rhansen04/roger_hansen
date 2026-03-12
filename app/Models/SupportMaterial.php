<?php

namespace App\Models;

use App\Core\Database\Connection;
use PDO;
use PDOException;

class SupportMaterial
{
    protected $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    /**
     * Buscar materiais de uma pasta
     */
    public function findByFolder($folderId)
    {
        try {
            $sql = "SELECT sm.*, u.name as uploaded_by_name
                    FROM support_materials sm
                    LEFT JOIN users u ON sm.uploaded_by = u.id
                    WHERE sm.folder_id = :folder_id
                    ORDER BY sm.title ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':folder_id' => $folderId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar materiais da pasta: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Buscar material por ID
     */
    public function find($id)
    {
        try {
            $sql = "SELECT sm.*, u.name as uploaded_by_name, f.name as folder_name
                    FROM support_materials sm
                    LEFT JOIN users u ON sm.uploaded_by = u.id
                    LEFT JOIN support_material_folders f ON sm.folder_id = f.id
                    WHERE sm.id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar material: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Criar registro de material
     */
    public function create($data)
    {
        try {
            $sql = "INSERT INTO support_materials (folder_id, title, filename, original_name, file_size, mime_type, uploaded_by)
                    VALUES (:folder_id, :title, :filename, :original_name, :file_size, :mime_type, :uploaded_by)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':folder_id' => $data['folder_id'],
                ':title' => $data['title'],
                ':filename' => $data['filename'],
                ':original_name' => $data['original_name'],
                ':file_size' => $data['file_size'] ?? null,
                ':mime_type' => $data['mime_type'] ?? null,
                ':uploaded_by' => $data['uploaded_by']
            ]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erro ao criar material: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Deletar material (registro + arquivo)
     */
    public function delete($id)
    {
        try {
            $material = $this->find($id);
            if (!$material) return false;

            // Deletar arquivo do disco
            $filePath = __DIR__ . '/../../public/uploads/support-materials/' . $material['filename'];
            if (file_exists($filePath)) {
                unlink($filePath);
            }

            $sql = "DELETE FROM support_materials WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("Erro ao deletar material: " . $e->getMessage());
            return false;
        }
    }
}
