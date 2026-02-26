<?php

namespace App\Models;

use PDO;
use PDOException;
use App\Core\Database\Connection;

class Lesson
{
    protected $db;
    
    public function __construct()
    {
        $this->db = Connection::getInstance();
    }
    
    /**
     * Buscar lição por ID
     */
    public function find($id)
    {
        try {
            $sql = "SELECT l.*, 
                           s.title as section_title,
                           c.title as course_title,
                           c.id as course_id
                    FROM lessons l
                    LEFT JOIN sections s ON l.section_id = s.id
                    LEFT JOIN courses c ON s.course_id = c.id
                    WHERE l.id = ? LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Erro ao buscar lição: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Buscar lições de uma seção
     */
    public function getBySection($sectionId)
    {
        try {
            $sql = "SELECT * FROM lessons WHERE section_id = ? ORDER BY sort_order ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$sectionId]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Erro ao buscar lições: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Criar nova lição
     */
    public function create($data)
    {
        try {
            $sql = "INSERT INTO lessons (section_id, title, description, content, video_url, video_duration, material_file, sort_order, duration_minutes, is_preview)
                    VALUES (:section_id, :title, :description, :content, :video_url, :video_duration, :material_file, :sort_order, :duration_minutes, :is_preview)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($data);
        } catch (PDOException $e) {
            error_log("Erro ao criar lição: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Atualizar lição
     */
    public function update($id, $data)
    {
        try {
            $data['id'] = $id;
            $sql = "UPDATE lessons
                    SET section_id = :section_id,
                        title = :title,
                        description = :description,
                        content = :content,
                        video_url = :video_url,
                        video_duration = :video_duration,
                        material_file = :material_file,
                        sort_order = :sort_order,
                        duration_minutes = :duration_minutes,
                        is_preview = :is_preview
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($data);
        } catch (PDOException $e) {
            error_log("Erro ao atualizar lição: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Atualizar sort_order de uma lição
     */
    public function updateSortOrder($id, $order)
    {
        try {
            $stmt = $this->db->prepare("UPDATE lessons SET sort_order = ? WHERE id = ?");
            return $stmt->execute([$order, $id]);
        } catch (PDOException $e) {
            error_log("Erro ao reordenar lição: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Deletar lição
     */
    public function delete($id)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM lessons WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Erro ao deletar lição: " . $e->getMessage());
            return false;
        }
    }
}
