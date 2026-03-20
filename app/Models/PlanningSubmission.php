<?php

namespace App\Models;

use App\Core\Database\Connection;
use App\Models\PlanningSubmissionAnswer;
use PDO;
use PDOException;

class PlanningSubmission
{
    protected $db;
    protected $answerModel;

    public function __construct()
    {
        $this->db = Connection::getInstance();
        $this->answerModel = new PlanningSubmissionAnswer();
    }

    public function all($filters = [])
    {
        try {
            $sql = "SELECT ps.*, pt.title as template_title, u.name as teacher_name,
                           c.name as classroom_name, s.name as school_name,
                           ppr.id as period_record_id
                    FROM planning_submissions ps
                    LEFT JOIN planning_templates pt ON ps.template_id = pt.id
                    LEFT JOIN users u ON ps.teacher_id = u.id
                    LEFT JOIN classrooms c ON ps.classroom_id = c.id
                    LEFT JOIN schools s ON c.school_id = s.id
                    LEFT JOIN planning_period_records ppr ON ppr.submission_id = ps.id
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
        return $this->answerModel->getBySubmission((int) $submissionId);
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
        return $this->answerModel->upsertStatic([
            ':submission_id' => $submissionId,
            ':field_id'      => $fieldId,
            ':section_id'    => $sectionId,
            ':answer_text'   => $answerText,
            ':answer_json'   => $answerJson,
        ]);
    }

    public function getByTeacher($teacherId)
    {
        return $this->all(['teacher_id' => $teacherId]);
    }

    public function getByClassroom($classroomId)
    {
        return $this->all(['classroom_id' => $classroomId]);
    }

    // --- Daily Entries ---

    public function getDailyEntries($submissionId)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM planning_daily_entries WHERE submission_id = ? ORDER BY entry_date");
            $stmt->execute([$submissionId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar daily entries: " . $e->getMessage());
            return [];
        }
    }

    public function findOrCreateDailyEntry($submissionId, $date)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM planning_daily_entries WHERE submission_id = ? AND entry_date = ?");
            $stmt->execute([$submissionId, $date]);
            $entry = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($entry) return $entry;

            $stmt = $this->db->prepare("INSERT INTO planning_daily_entries (submission_id, entry_date, status) VALUES (?, ?, 'empty')");
            $stmt->execute([$submissionId, $date]);
            $id = $this->db->lastInsertId();

            return ['id' => $id, 'submission_id' => $submissionId, 'entry_date' => $date, 'status' => 'empty'];
        } catch (PDOException $e) {
            error_log("Erro ao criar daily entry: " . $e->getMessage());
            return null;
        }
    }

    public function getAnswersForDay($submissionId, $dailyEntryId)
    {
        $answers = $this->answerModel->getByDailyEntry((int) $submissionId, (int) $dailyEntryId);
        $indexed = [];
        foreach ($answers as $a) {
            $indexed[$a['field_id']] = $a;
        }
        return $indexed;
    }

    public function saveAnswerForDay($submissionId, $fieldId, $sectionId, $dailyEntryId, $answerText, $answerJson = null)
    {
        return $this->answerModel->upsertDaily([
            ':submission_id'  => $submissionId,
            ':field_id'       => $fieldId,
            ':section_id'     => $sectionId,
            ':daily_entry_id' => $dailyEntryId,
            ':answer_text'    => $answerText,
            ':answer_json'    => $answerJson,
        ]);
    }

    public function updateDailyEntryStatus($entryId, $status)
    {
        try {
            $stmt = $this->db->prepare("UPDATE planning_daily_entries SET status = ?, updated_at = NOW() WHERE id = ?");
            return $stmt->execute([$status, $entryId]);
        } catch (PDOException $e) {
            error_log("Erro ao atualizar status daily entry: " . $e->getMessage());
            return false;
        }
    }

    public function getRegistrationAnswers($submissionId)
    {
        $answers = $this->answerModel->getStatic((int) $submissionId);
        $indexed = [];
        foreach ($answers as $a) {
            $indexed[$a['field_id']] = $a;
        }
        return $indexed;
    }
}
