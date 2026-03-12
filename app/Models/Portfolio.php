<?php

namespace App\Models;

use App\Core\Database\Connection;
use PDO;
use PDOException;

class Portfolio
{
    protected $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    /**
     * Listar todos os portfolios
     */
    public function all()
    {
        try {
            $sql = "SELECT p.*, c.name as classroom_name, c.school_year, s.name as school_name,
                           u.name as teacher_name
                    FROM portfolios p
                    INNER JOIN classrooms c ON p.classroom_id = c.id
                    LEFT JOIN schools s ON c.school_id = s.id
                    LEFT JOIN users u ON c.teacher_id = u.id
                    ORDER BY p.year DESC, p.semester DESC, c.name ASC";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao listar portfolios: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Buscar portfolio por ID
     */
    public function find($id)
    {
        try {
            $sql = "SELECT p.*, c.name as classroom_name, c.school_year, c.age_group,
                           s.name as school_name, u.name as teacher_name, c.teacher_id
                    FROM portfolios p
                    INNER JOIN classrooms c ON p.classroom_id = c.id
                    LEFT JOIN schools s ON c.school_id = s.id
                    LEFT JOIN users u ON c.teacher_id = u.id
                    WHERE p.id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar portfolio: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Buscar portfolios de uma turma
     */
    public function findByClassroom($classroomId)
    {
        try {
            $sql = "SELECT p.* FROM portfolios p
                    WHERE p.classroom_id = :classroom_id
                    ORDER BY p.year DESC, p.semester DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':classroom_id' => $classroomId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar portfolios da turma: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Criar portfolio
     */
    public function create($data)
    {
        try {
            $sql = "INSERT INTO portfolios (classroom_id, semester, year, cover_photo_url, teacher_message,
                        axis_movement_photos, axis_manual_photos, axis_stories_photos, axis_music_photos, axis_pca_photos,
                        axis_movement_description, axis_manual_description, axis_stories_description,
                        axis_music_description, axis_pca_description)
                    VALUES (:classroom_id, :semester, :year, :cover_photo_url, :teacher_message,
                        :axis_movement_photos, :axis_manual_photos, :axis_stories_photos, :axis_music_photos, :axis_pca_photos,
                        :axis_movement_description, :axis_manual_description, :axis_stories_description,
                        :axis_music_description, :axis_pca_description)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':classroom_id' => $data['classroom_id'],
                ':semester' => $data['semester'],
                ':year' => $data['year'],
                ':cover_photo_url' => $data['cover_photo_url'] ?? null,
                ':teacher_message' => $data['teacher_message'] ?? null,
                ':axis_movement_photos' => $data['axis_movement_photos'] ?? null,
                ':axis_manual_photos' => $data['axis_manual_photos'] ?? null,
                ':axis_stories_photos' => $data['axis_stories_photos'] ?? null,
                ':axis_music_photos' => $data['axis_music_photos'] ?? null,
                ':axis_pca_photos' => $data['axis_pca_photos'] ?? null,
                ':axis_movement_description' => $data['axis_movement_description'] ?? null,
                ':axis_manual_description' => $data['axis_manual_description'] ?? null,
                ':axis_stories_description' => $data['axis_stories_description'] ?? null,
                ':axis_music_description' => $data['axis_music_description'] ?? null,
                ':axis_pca_description' => $data['axis_pca_description'] ?? null
            ]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erro ao criar portfolio: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Atualizar portfolio
     */
    public function update($id, $data)
    {
        try {
            $fields = [];
            $params = [':id' => $id];

            $allowedFields = [
                'cover_photo_url', 'teacher_message', 'teacher_message_corrected',
                'axis_movement_photos', 'axis_manual_photos', 'axis_stories_photos',
                'axis_music_photos', 'axis_pca_photos',
                'axis_movement_description', 'axis_manual_description', 'axis_stories_description',
                'axis_music_description', 'axis_pca_description',
                'status', 'revision_notes', 'revision_requested_by',
                'finalized_at', 'finalized_by'
            ];

            foreach ($allowedFields as $field) {
                if (array_key_exists($field, $data)) {
                    $fields[] = "{$field} = :{$field}";
                    $params[":{$field}"] = $data[$field];
                }
            }

            if (empty($fields)) return false;

            $sql = "UPDATE portfolios SET " . implode(', ', $fields) . " WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Erro ao atualizar portfolio: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Finalizar portfolio
     */
    public function finalize($id, $userId)
    {
        return $this->update($id, [
            'status' => 'finalized',
            'finalized_at' => date('Y-m-d H:i:s'),
            'finalized_by' => $userId
        ]);
    }

    /**
     * Reabrir portfolio
     */
    public function reopen($id)
    {
        return $this->update($id, [
            'status' => 'pending',
            'finalized_at' => null,
            'finalized_by' => null,
            'revision_notes' => null,
            'revision_requested_by' => null
        ]);
    }

    /**
     * Solicitar revisao
     */
    public function requestRevision($id, $notes, $userId)
    {
        return $this->update($id, [
            'status' => 'revision_requested',
            'revision_notes' => $notes,
            'revision_requested_by' => $userId
        ]);
    }
}
