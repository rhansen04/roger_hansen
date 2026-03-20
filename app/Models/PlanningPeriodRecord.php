<?php

namespace App\Models;

use App\Core\Database\Connection;
use PDO;
use PDOException;

class PlanningPeriodRecord
{
    protected $db;

    private static bool $tableChecked = false;

    public function __construct()
    {
        $this->db = Connection::getInstance();
        if (!self::$tableChecked) {
            $this->createTable();
            self::$tableChecked = true;
        }
    }

    public function findBySubmission($submissionId)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM planning_period_records WHERE submission_id = ? LIMIT 1");
            $stmt->execute([$submissionId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar registro do período: " . $e->getMessage());
            return null;
        }
    }

    public function find($id)
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM planning_period_records WHERE id = ?");
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar registro: " . $e->getMessage());
            return null;
        }
    }

    public function create($data)
    {
        try {
            $sql = "INSERT INTO planning_period_records
                    (submission_id, activity_synthesis, planning_execution, planning_execution_justification,
                     child_engagement, child_engagement_comment,
                     adjustments_time, adjustments_space, adjustments_materials, adjustments_mediation, adjustments_interest, adjustments_description,
                     children_novelty, advances_challenges,
                     support_pedagogical, support_organizational, support_formative, support_structural, support_description,
                     created_at, updated_at)
                    VALUES
                    (:submission_id, :activity_synthesis, :planning_execution, :planning_execution_justification,
                     :child_engagement, :child_engagement_comment,
                     :adjustments_time, :adjustments_space, :adjustments_materials, :adjustments_mediation, :adjustments_interest, :adjustments_description,
                     :children_novelty, :advances_challenges,
                     :support_pedagogical, :support_organizational, :support_formative, :support_structural, :support_description,
                     NOW(), NOW())";
            $stmt = $this->db->prepare($sql);
            $stmt->execute($this->buildParams($data));
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erro ao criar registro do período: " . $e->getMessage());
            return false;
        }
    }

    public function update($id, $data)
    {
        try {
            $sql = "UPDATE planning_period_records SET
                    activity_synthesis = :activity_synthesis,
                    planning_execution = :planning_execution,
                    planning_execution_justification = :planning_execution_justification,
                    child_engagement = :child_engagement,
                    child_engagement_comment = :child_engagement_comment,
                    adjustments_time = :adjustments_time,
                    adjustments_space = :adjustments_space,
                    adjustments_materials = :adjustments_materials,
                    adjustments_mediation = :adjustments_mediation,
                    adjustments_interest = :adjustments_interest,
                    adjustments_description = :adjustments_description,
                    children_novelty = :children_novelty,
                    advances_challenges = :advances_challenges,
                    support_pedagogical = :support_pedagogical,
                    support_organizational = :support_organizational,
                    support_formative = :support_formative,
                    support_structural = :support_structural,
                    support_description = :support_description,
                    updated_at = NOW()
                    WHERE id = :id";
            $params = $this->buildParams($data);
            $params[':id'] = $id;
            unset($params[':submission_id']);
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            error_log("Erro ao atualizar registro do período: " . $e->getMessage());
            return false;
        }
    }

    private function buildParams($data)
    {
        $adj = $data['adjustments'] ?? [];
        $sup = $data['support'] ?? [];
        return [
            ':submission_id' => $data['submission_id'],
            ':activity_synthesis' => $data['activity_synthesis'] ?? null,
            ':planning_execution' => $data['planning_execution'] ?? 'sim',
            ':planning_execution_justification' => $data['planning_execution_justification'] ?? null,
            ':child_engagement' => $data['child_engagement'] ?? 'alto',
            ':child_engagement_comment' => $data['child_engagement_comment'] ?? null,
            ':adjustments_time' => in_array('time', $adj) ? 1 : 0,
            ':adjustments_space' => in_array('space', $adj) ? 1 : 0,
            ':adjustments_materials' => in_array('materials', $adj) ? 1 : 0,
            ':adjustments_mediation' => in_array('mediation', $adj) ? 1 : 0,
            ':adjustments_interest' => in_array('interest', $adj) ? 1 : 0,
            ':adjustments_description' => $data['adjustments_description'] ?? null,
            ':children_novelty' => $data['children_novelty'] ?? null,
            ':advances_challenges' => $data['advances_challenges'] ?? null,
            ':support_pedagogical' => in_array('pedagogical', $sup) ? 1 : 0,
            ':support_organizational' => in_array('organizational', $sup) ? 1 : 0,
            ':support_formative' => in_array('formative', $sup) ? 1 : 0,
            ':support_structural' => in_array('structural', $sup) ? 1 : 0,
            ':support_description' => $data['support_description'] ?? null,
        ];
    }

    public function createTable()
    {
        try {
            $this->db->exec("CREATE TABLE IF NOT EXISTS planning_period_records (
                id INT AUTO_INCREMENT PRIMARY KEY,
                submission_id INT NOT NULL,
                activity_synthesis TEXT,
                planning_execution ENUM('sim','parcialmente','nao') NOT NULL DEFAULT 'sim',
                planning_execution_justification TEXT,
                child_engagement ENUM('alto','medio','baixo') NOT NULL DEFAULT 'alto',
                child_engagement_comment TEXT,
                adjustments_time TINYINT(1) DEFAULT 0,
                adjustments_space TINYINT(1) DEFAULT 0,
                adjustments_materials TINYINT(1) DEFAULT 0,
                adjustments_mediation TINYINT(1) DEFAULT 0,
                adjustments_interest TINYINT(1) DEFAULT 0,
                adjustments_description TEXT,
                children_novelty TEXT,
                advances_challenges TEXT,
                support_pedagogical TINYINT(1) DEFAULT 0,
                support_organizational TINYINT(1) DEFAULT 0,
                support_formative TINYINT(1) DEFAULT 0,
                support_structural TINYINT(1) DEFAULT 0,
                support_description TEXT,
                created_at DATETIME,
                updated_at DATETIME,
                UNIQUE KEY uq_submission (submission_id),
                CONSTRAINT fk_ppr_submission FOREIGN KEY (submission_id) REFERENCES planning_submissions(id) ON DELETE CASCADE
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
            return true;
        } catch (PDOException $e) {
            error_log("Erro ao criar tabela planning_period_records: " . $e->getMessage());
            return false;
        }
    }
}
