<?php

namespace App\Models;

use PDO;
use PDOException;
use App\Core\Database\Connection;

class Course
{
    protected $db;
    
    public function __construct()
    {
        $this->db = Connection::getInstance();
    }
    
    /**
     * Listar todos os cursos ativos
     */
    public function allActive()
    {
        try {
            $sql = "SELECT c.*,
                           COUNT(DISTINCT e.id) as enrollments_count
                    FROM courses c
                    LEFT JOIN enrollments e ON c.id = e.course_id
                    WHERE c.is_active = TRUE
                    GROUP BY c.id
                    ORDER BY c.created_at DESC";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Erro ao listar cursos: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Buscar curso por slug
     */
    public function findBySlug($slug)
    {
        try {
            $sql = "SELECT c.*, u.name as instructor_name
                    FROM courses c
                    LEFT JOIN users u ON c.instructor_id = u.id
                    WHERE c.slug = ?
                    LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$slug]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Erro ao buscar curso: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Buscar curso por ID
     */
    public function find($id)
    {
        try {
            $sql = "SELECT * FROM courses WHERE id = ? LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Erro ao buscar curso: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Obter seções de um curso
     */
    public function getSections($courseId)
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
            error_log("Erro ao obter seções: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Criar novo curso
     */
    public function create($data)
    {
        try {
            $sql = "INSERT INTO courses (title, slug, description, short_description, cover_image, price, is_free, is_active, category, level, duration_hours, instructor_id, created_at, updated_at)
                    VALUES (:title, :slug, :description, :short_description, :cover_image, :price, :is_free, :is_active, :category, :level, :duration_hours, :instructor_id, NOW(), NOW())";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($data);
        } catch (PDOException $e) {
            error_log("Erro ao criar curso: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Atualizar curso
     */
    public function update($id, $data)
    {
        try {
            $data['id'] = $id;
            $sql = "UPDATE courses
                    SET title = :title,
                        slug = :slug,
                        description = :description,
                        short_description = :short_description,
                        cover_image = :cover_image,
                        price = :price,
                        is_free = :is_free,
                        is_active = :is_active,
                        category = :category,
                        level = :level,
                        duration_hours = :duration_hours,
                        instructor_id = :instructor_id,
                        updated_at = NOW()
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($data);
        } catch (PDOException $e) {
            error_log("Erro ao atualizar curso: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Deletar curso
     */
    public function delete($id)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM courses WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Erro ao deletar curso: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Contar cursos ativos
     */
    public function countActive()
    {
        try {
            $stmt = $this->db->query("SELECT COUNT(*) as total FROM courses WHERE is_active = TRUE");
            $result = $stmt->fetch();
            return (int)($result['total'] ?? 0);
        } catch (PDOException $e) {
            error_log("Erro ao contar cursos: " . $e->getMessage());
            return 0;
        }
    }
}
