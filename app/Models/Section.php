<?php

namespace App\Models;

use PDO;
use PDOException;
use App\Core\Database\Connection;

class Section
{
    protected $db;
    
    public function __construct()
    {
        $this->db = Connection::getInstance();
    }
    
    /**
     * Buscar seção por ID
     */
    public function find($id)
    {
        try {
            $sql = "SELECT * FROM sections WHERE id = ? LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Erro ao buscar seção: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Buscar seções de um curso
     */
    public function getByCourse($courseId)
    {
        try {
            $sql = "SELECT s.*,
                           COUNT(l.id) as lessons_count
                    FROM sections s
                    LEFT JOIN lessons l ON s.id = l.section_id
                    WHERE s.course_id = ?
                    GROUP BY s.id
                    ORDER BY s.sort_order ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$courseId]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Erro ao buscar seções: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Criar nova seção
     */
    public function create($data)
    {
        try {
            $sql = "INSERT INTO sections (course_id, module_id, title, description, sort_order)
                    VALUES (:course_id, :module_id, :title, :description, :sort_order)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($data);
        } catch (PDOException $e) {
            error_log("Erro ao criar seção: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Atualizar seção
     */
    public function update($id, $data)
    {
        try {
            $data['id'] = $id;
            $sql = "UPDATE sections
                    SET course_id = :course_id,
                        module_id = :module_id,
                        title = :title,
                        description = :description,
                        sort_order = :sort_order
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($data);
        } catch (PDOException $e) {
            error_log("Erro ao atualizar seção: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Atualizar sort_order de uma seção
     */
    public function updateSortOrder($id, $order)
    {
        try {
            $stmt = $this->db->prepare("UPDATE sections SET sort_order = ? WHERE id = ?");
            return $stmt->execute([$order, $id]);
        } catch (PDOException $e) {
            error_log("Erro ao reordenar seção: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Deletar seção (e suas lições por CASCADE)
     */
    public function delete($id)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM sections WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Erro ao deletar seção: " . $e->getMessage());
            return false;
        }
    }
}
