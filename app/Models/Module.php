<?php

namespace App\Models;

use PDO;
use PDOException;
use App\Core\Database\Connection;

class Module
{
    protected $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    public function find($id)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM modules WHERE id = ? LIMIT 1");
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Erro ao buscar módulo: " . $e->getMessage());
            return null;
        }
    }

    public function getByCourse($courseId)
    {
        try {
            $sql = "SELECT m.*,
                           COUNT(s.id) as sections_count
                    FROM modules m
                    LEFT JOIN sections s ON m.id = s.module_id
                    WHERE m.course_id = ?
                    GROUP BY m.id
                    ORDER BY m.sort_order ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$courseId]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Erro ao buscar módulos: " . $e->getMessage());
            return [];
        }
    }

    public function create($data)
    {
        try {
            $sql = "INSERT INTO modules (course_id, title, description, sort_order)
                    VALUES (:course_id, :title, :description, :sort_order)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($data);
        } catch (PDOException $e) {
            error_log("Erro ao criar módulo: " . $e->getMessage());
            return false;
        }
    }

    public function update($id, $data)
    {
        try {
            $data['id'] = $id;
            $sql = "UPDATE modules
                    SET title = :title,
                        description = :description,
                        sort_order = :sort_order
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($data);
        } catch (PDOException $e) {
            error_log("Erro ao atualizar módulo: " . $e->getMessage());
            return false;
        }
    }

    public function updateSortOrder($id, $order)
    {
        try {
            $stmt = $this->db->prepare("UPDATE modules SET sort_order = ? WHERE id = ?");
            return $stmt->execute([$order, $id]);
        } catch (PDOException $e) {
            error_log("Erro ao reordenar módulo: " . $e->getMessage());
            return false;
        }
    }

    public function delete($id)
    {
        try {
            // module_id nas sections será setado para NULL (ON DELETE SET NULL)
            $stmt = $this->db->prepare("DELETE FROM modules WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Erro ao deletar módulo: " . $e->getMessage());
            return false;
        }
    }
}
