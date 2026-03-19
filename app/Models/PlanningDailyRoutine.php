<?php

namespace App\Models;

use App\Core\Database\Connection;
use PDO;
use PDOException;

class PlanningDailyRoutine
{
    protected $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    /**
     * All routines for a submission, grouped by day_of_week
     */
    public function findBySubmission($submissionId)
    {
        try {
            $sql = "SELECT * FROM planning_daily_routines
                    WHERE submission_id = ?
                    ORDER BY day_of_week, sort_order, time_slot";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$submissionId]);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $grouped = [];
            foreach ($rows as $row) {
                $grouped[$row['day_of_week']][] = $row;
            }
            return $grouped;
        } catch (PDOException $e) {
            error_log("Erro ao buscar rotinas: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Routines for a specific day of a submission
     */
    public function findBySubmissionAndDay($submissionId, $dayOfWeek)
    {
        try {
            $sql = "SELECT * FROM planning_daily_routines
                    WHERE submission_id = ? AND day_of_week = ?
                    ORDER BY sort_order, time_slot";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$submissionId, $dayOfWeek]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar rotinas do dia: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Create a single routine entry
     */
    public function create($data)
    {
        try {
            $sql = "INSERT INTO planning_daily_routines (submission_id, day_of_week, time_slot, activity_description, sort_order)
                    VALUES (:submission_id, :day_of_week, :time_slot, :activity_description, :sort_order)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':submission_id' => $data['submission_id'],
                ':day_of_week' => $data['day_of_week'],
                ':time_slot' => $data['time_slot'],
                ':activity_description' => $data['activity_description'],
                ':sort_order' => $data['sort_order'] ?? 0,
            ]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erro ao criar rotina: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update a single routine entry
     */
    public function update($id, $data)
    {
        try {
            $sql = "UPDATE planning_daily_routines
                    SET time_slot = :time_slot, activity_description = :activity_description,
                        sort_order = :sort_order, updated_at = NOW()
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':time_slot' => $data['time_slot'],
                ':activity_description' => $data['activity_description'],
                ':sort_order' => $data['sort_order'] ?? 0,
                ':id' => $id,
            ]);
        } catch (PDOException $e) {
            error_log("Erro ao atualizar rotina: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete a single routine entry
     */
    public function delete($id)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM planning_daily_routines WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Erro ao deletar rotina: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Find a single routine entry by ID
     */
    public function find($id)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM planning_daily_routines WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar rotina: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Delete all routines for a submission
     */
    public function deleteBySubmission($submissionId)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM planning_daily_routines WHERE submission_id = ?");
            return $stmt->execute([$submissionId]);
        } catch (PDOException $e) {
            error_log("Erro ao deletar rotinas: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Batch save: delete all existing + insert new ones
     * $routines is array of arrays with keys: day_of_week, time_slot, activity_description, sort_order
     */
    public function saveRoutines($submissionId, $routines)
    {
        try {
            $this->db->beginTransaction();

            // Delete inline (not via deleteBySubmission) so exceptions
            // propagate to this try/catch instead of being swallowed.
            $delStmt = $this->db->prepare("DELETE FROM planning_daily_routines WHERE submission_id = ?");
            $delStmt->execute([$submissionId]);

            $sql = "INSERT INTO planning_daily_routines (submission_id, day_of_week, time_slot, activity_description, sort_order)
                    VALUES (:submission_id, :day_of_week, :time_slot, :activity_description, :sort_order)";
            $stmt = $this->db->prepare($sql);

            foreach ($routines as $r) {
                if (empty($r['time_slot']) && empty($r['activity_description'])) continue;
                $stmt->execute([
                    ':submission_id' => $submissionId,
                    ':day_of_week' => $r['day_of_week'],
                    ':time_slot' => $r['time_slot'] ?? '',
                    ':activity_description' => $r['activity_description'] ?? '',
                    ':sort_order' => $r['sort_order'] ?? 0,
                ]);
            }

            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log("Erro ao salvar rotinas em lote: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Check if a submission has any routines
     */
    public function hasRoutines($submissionId)
    {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM planning_daily_routines WHERE submission_id = ?");
            $stmt->execute([$submissionId]);
            return $stmt->fetchColumn() > 0;
        } catch (PDOException $e) {
            return false;
        }
    }

    /**
     * Count routines for a submission
     */
    public function countBySubmission($submissionId)
    {
        try {
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM planning_daily_routines WHERE submission_id = ?");
            $stmt->execute([$submissionId]);
            return (int) $stmt->fetchColumn();
        } catch (PDOException $e) {
            return 0;
        }
    }
}
