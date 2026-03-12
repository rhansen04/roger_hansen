<?php

namespace App\Models;

use App\Core\Database\Connection;
use PDO;
use PDOException;

class DescriptiveReport
{
    protected $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    /**
     * Listar todos os pareceres descritivos com nome do aluno e turma
     */
    public function all()
    {
        try {
            $sql = "SELECT dr.*,
                           COALESCE(s.name, 'Aluno nao encontrado') as student_name,
                           s.photo_url as student_photo,
                           c.name as classroom_name
                    FROM descriptive_reports dr
                    LEFT JOIN students s ON dr.student_id = s.id
                    LEFT JOIN classrooms c ON dr.classroom_id = c.id
                    ORDER BY dr.year DESC, dr.semester DESC, s.name ASC";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao listar pareceres descritivos: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Buscar parecer por ID com joins
     */
    public function find($id)
    {
        try {
            $sql = "SELECT dr.*,
                           COALESCE(s.name, 'Aluno nao encontrado') as student_name,
                           s.photo_url as student_photo,
                           s.birth_date as student_birth_date,
                           c.name as classroom_name,
                           c.age_group as classroom_age_group,
                           uf.name as finalized_by_name
                    FROM descriptive_reports dr
                    LEFT JOIN students s ON dr.student_id = s.id
                    LEFT JOIN classrooms c ON dr.classroom_id = c.id
                    LEFT JOIN users uf ON dr.finalized_by = uf.id
                    WHERE dr.id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar parecer descritivo: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Buscar pareceres por aluno
     */
    public function findByStudent($studentId)
    {
        try {
            $sql = "SELECT dr.*,
                           COALESCE(s.name, 'Aluno nao encontrado') as student_name,
                           s.photo_url as student_photo,
                           c.name as classroom_name
                    FROM descriptive_reports dr
                    LEFT JOIN students s ON dr.student_id = s.id
                    LEFT JOIN classrooms c ON dr.classroom_id = c.id
                    WHERE dr.student_id = ?
                    ORDER BY dr.year DESC, dr.semester DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$studentId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar pareceres por aluno: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Buscar pareceres por turma
     */
    public function findByClassroom($classroomId)
    {
        try {
            $sql = "SELECT dr.*,
                           COALESCE(s.name, 'Aluno nao encontrado') as student_name,
                           s.photo_url as student_photo,
                           c.name as classroom_name
                    FROM descriptive_reports dr
                    LEFT JOIN students s ON dr.student_id = s.id
                    LEFT JOIN classrooms c ON dr.classroom_id = c.id
                    WHERE dr.classroom_id = ?
                    ORDER BY s.name ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$classroomId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar pareceres por turma: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Buscar pareceres com filtros
     */
    public function findFiltered($filters = [])
    {
        try {
            $sql = "SELECT dr.*,
                           COALESCE(s.name, 'Aluno nao encontrado') as student_name,
                           s.photo_url as student_photo,
                           c.name as classroom_name
                    FROM descriptive_reports dr
                    LEFT JOIN students s ON dr.student_id = s.id
                    LEFT JOIN classrooms c ON dr.classroom_id = c.id
                    WHERE 1=1";
            $params = [];

            if (!empty($filters['classroom_id'])) {
                $sql .= " AND dr.classroom_id = ?";
                $params[] = $filters['classroom_id'];
            }
            if (!empty($filters['semester'])) {
                $sql .= " AND dr.semester = ?";
                $params[] = $filters['semester'];
            }
            if (!empty($filters['year'])) {
                $sql .= " AND dr.year = ?";
                $params[] = $filters['year'];
            }
            if (!empty($filters['status'])) {
                $sql .= " AND dr.status = ?";
                $params[] = $filters['status'];
            }

            $sql .= " ORDER BY dr.year DESC, dr.semester DESC, s.name ASC";

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao filtrar pareceres descritivos: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Criar novo parecer
     */
    public function create($data)
    {
        try {
            $sql = "INSERT INTO descriptive_reports
                    (student_id, classroom_id, observation_id, semester, year, cover_photo_url,
                     intro_text, student_text, student_text_edited, axis_photos, status)
                    VALUES
                    (:student_id, :classroom_id, :observation_id, :semester, :year, :cover_photo_url,
                     :intro_text, :student_text, :student_text_edited, :axis_photos, :status)";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':student_id' => $data['student_id'],
                ':classroom_id' => $data['classroom_id'] ?? null,
                ':observation_id' => $data['observation_id'] ?? null,
                ':semester' => $data['semester'],
                ':year' => $data['year'],
                ':cover_photo_url' => $data['cover_photo_url'] ?? null,
                ':intro_text' => $data['intro_text'] ?? null,
                ':student_text' => $data['student_text'] ?? null,
                ':student_text_edited' => $data['student_text_edited'] ?? null,
                ':axis_photos' => $data['axis_photos'] ?? null,
                ':status' => $data['status'] ?? 'draft'
            ]);

            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erro ao criar parecer descritivo: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Atualizar parecer
     */
    public function update($id, $data)
    {
        try {
            $fields = [];
            $params = [':id' => $id];

            $allowedFields = [
                'cover_photo_url', 'intro_text', 'student_text',
                'student_text_edited', 'axis_photos', 'status',
                'revision_notes', 'classroom_id', 'observation_id'
            ];

            foreach ($allowedFields as $field) {
                if (array_key_exists($field, $data)) {
                    $fields[] = "{$field} = :{$field}";
                    $params[":{$field}"] = $data[$field];
                }
            }

            if (empty($fields)) {
                return true;
            }

            $sql = "UPDATE descriptive_reports SET " . implode(', ', $fields) . " WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Erro ao atualizar parecer descritivo: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Atualizar apenas o texto editado
     */
    public function updateText($id, $text)
    {
        try {
            $sql = "UPDATE descriptive_reports SET student_text_edited = :text WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':id' => $id, ':text' => $text]);
        } catch (PDOException $e) {
            error_log("Erro ao atualizar texto do parecer: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Finalizar parecer
     */
    public function finalize($id, $userId)
    {
        try {
            $sql = "UPDATE descriptive_reports
                    SET status = 'finalized',
                        finalized_at = NOW(),
                        finalized_by = :user_id
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':id' => $id, ':user_id' => $userId]);
        } catch (PDOException $e) {
            error_log("Erro ao finalizar parecer: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Reabrir parecer (voltar para rascunho)
     */
    public function reopen($id)
    {
        try {
            $sql = "UPDATE descriptive_reports
                    SET status = 'draft',
                        finalized_at = NULL,
                        finalized_by = NULL
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("Erro ao reabrir parecer: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Solicitar revisao
     */
    public function requestRevision($id, $notes)
    {
        try {
            $sql = "UPDATE descriptive_reports
                    SET status = 'revision_requested',
                        revision_notes = :notes,
                        finalized_at = NULL,
                        finalized_by = NULL
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([':id' => $id, ':notes' => $notes]);
        } catch (PDOException $e) {
            error_log("Erro ao solicitar revisao do parecer: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Contar pareceres por status
     */
    public function countByStatus()
    {
        try {
            $sql = "SELECT status, COUNT(*) as total
                    FROM descriptive_reports
                    GROUP BY status";
            $stmt = $this->db->query($sql);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $counts = ['draft' => 0, 'finalized' => 0, 'revision_requested' => 0];
            foreach ($results as $row) {
                $counts[$row['status']] = $row['total'];
            }
            return $counts;
        } catch (PDOException $e) {
            error_log("Erro ao contar pareceres por status: " . $e->getMessage());
            return ['draft' => 0, 'finalized' => 0, 'revision_requested' => 0];
        }
    }
}
