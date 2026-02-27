<?php

namespace App\Models;

use App\Core\Database\Connection;
use PDO;
use PDOException;

class PlanningSubmission
{
    protected $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    public function all($filters = [])
    {
        try {
            $sql = "SELECT ps.*, pt.title as template_title, u.name as teacher_name,
                           c.name as classroom_name, s.name as school_name
                    FROM planning_submissions ps
                    LEFT JOIN planning_templates pt ON ps.template_id = pt.id
                    LEFT JOIN users u ON ps.teacher_id = u.id
                    LEFT JOIN classrooms c ON ps.classroom_id = c.id
                    LEFT JOIN schools s ON c.school_id = s.id
                    WHERE 1=1";
            $params = [];

            if (!empty($filters['teacher_id'])) {
                $sql .= " AND ps.teacher_id = ?";
                $params[] = $filters['teacher_id'];
            }
            if (!empty($filters['classroom_id'])) {
                $sql .= " AND ps.classroom_id = ?";
                $params[] = $filters['classroom_id'];
            }
            if (!empty($filters['status'])) {
                $sql .= " AND ps.status = ?";
                $params[] = $filters['status'];
            }
            if (!empty($filters['template_id'])) {
                $sql .= " AND ps.template_id = ?";
                $params[] = $filters['template_id'];
            }

            $sql .= " ORDER BY ps.created_at DESC";

            $stmt = $this->db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao listar planejamentos: " . $e->getMessage());
            return [];
        }
    }

    public function find($id)
    {
        try {
            $sql = "SELECT ps.*, pt.title as template_title, u.name as teacher_name,
                           c.name as classroom_name, s.name as school_name
                    FROM planning_submissions ps
                    LEFT JOIN planning_templates pt ON ps.template_id = pt.id
                    LEFT JOIN users u ON ps.teacher_id = u.id
                    LEFT JOIN classrooms c ON ps.classroom_id = c.id
                    LEFT JOIN schools s ON c.school_id = s.id
                    WHERE ps.id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar planejamento: " . $e->getMessage());
            return null;
        }
    }

    public function create($data)
    {
        try {
            $sql = "INSERT INTO planning_submissions (template_id, teacher_id, classroom_id, period_start, period_end, status, created_at, updated_at)
                    VALUES (:template_id, :teacher_id, :classroom_id, :period_start, :period_end, :status, :created_at, :updated_at)";
            $stmt = $this->db->prepare($sql);
            $now = date('Y-m-d H:i:s');
            $stmt->execute([
                ':template_id' => $data['template_id'],
                ':teacher_id' => $data['teacher_id'],
                ':classroom_id' => $data['classroom_id'],
                ':period_start' => $data['period_start'],
                ':period_end' => $data['period_end'],
                ':status' => 'draft',
                ':created_at' => $now,
                ':updated_at' => $now
            ]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erro ao criar planejamento: " . $e->getMessage());
            return false;
        }
    }

    public function updateStatus($id, $status)
    {
        try {
            $extra = '';
            if ($status === 'submitted') $extra = ', submitted_at = NOW()';
            if ($status === 'registered') $extra = ', registered_at = NOW()';

            $sql = "UPDATE planning_submissions SET status = ?, updated_at = NOW() $extra WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$status, $id]);
        } catch (PDOException $e) {
            error_log("Erro ao atualizar status: " . $e->getMessage());
            return false;
        }
    }

    public function delete($id)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM planning_submissions WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Erro ao deletar planejamento: " . $e->getMessage());
            return false;
        }
    }

    // --- Answers ---

    public function getAnswers($submissionId)
    {
        try {
            $sql = "SELECT * FROM planning_submission_answers WHERE submission_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$submissionId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar respostas: " . $e->getMessage());
            return [];
        }
    }

    public function getAnswersIndexed($submissionId)
    {
        $answers = $this->getAnswers($submissionId);
        $indexed = [];
        foreach ($answers as $a) {
            $indexed[$a['field_id']] = $a;
        }
        return $indexed;
    }

    public function saveAnswer($submissionId, $fieldId, $sectionId, $answerText, $answerJson = null)
    {
        try {
            $sql = "INSERT INTO planning_submission_answers (submission_id, field_id, section_id, answer_text, answer_json)
                    VALUES (:submission_id, :field_id, :section_id, :answer_text, :answer_json)
                    ON DUPLICATE KEY UPDATE answer_text = VALUES(answer_text), answer_json = VALUES(answer_json)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':submission_id' => $submissionId,
                ':field_id' => $fieldId,
                ':section_id' => $sectionId,
                ':answer_text' => $answerText,
                ':answer_json' => $answerJson
            ]);
        } catch (PDOException $e) {
            error_log("Erro ao salvar resposta: " . $e->getMessage());
            return false;
        }
    }

    public function getByTeacher($teacherId)
    {
        return $this->all(['teacher_id' => $teacherId]);
    }

    public function getByClassroom($classroomId)
    {
        return $this->all(['classroom_id' => $classroomId]);
    }
}
