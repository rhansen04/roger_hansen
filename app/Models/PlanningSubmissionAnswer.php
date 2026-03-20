<?php

namespace App\Models;

use PDO;
use PDOException;
use App\Core\Database\Connection;

/**
 * Model para a tabela planning_submission_answers.
 * Gerencia respostas de campos de planejamento (estáticas e por entrada diária).
 */
class PlanningSubmissionAnswer
{
    protected $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    /**
     * Buscar todas as respostas de um submission.
     */
    public function getBySubmission(int $submissionId): array
    {
        try {
            $stmt = $this->db->prepare("SELECT * FROM planning_submission_answers WHERE submission_id = ?");
            $stmt->execute([$submissionId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("PlanningSubmissionAnswer::getBySubmission: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Buscar respostas estáticas (sem entrada diária vinculada).
     */
    public function getStatic(int $submissionId): array
    {
        try {
            $stmt = $this->db->prepare(
                "SELECT * FROM planning_submission_answers WHERE submission_id = ? AND daily_entry_id IS NULL"
            );
            $stmt->execute([$submissionId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("PlanningSubmissionAnswer::getStatic: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Buscar respostas de uma entrada diária específica.
     */
    public function getByDailyEntry(int $submissionId, int $dailyEntryId): array
    {
        try {
            $stmt = $this->db->prepare(
                "SELECT * FROM planning_submission_answers WHERE submission_id = ? AND daily_entry_id = ?"
            );
            $stmt->execute([$submissionId, $dailyEntryId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("PlanningSubmissionAnswer::getByDailyEntry: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Salvar resposta estática (upsert por submission_id + field_id).
     */
    public function upsertStatic(array $data): bool
    {
        try {
            $sql = "INSERT INTO planning_submission_answers
                        (submission_id, field_id, section_id, answer_text, answer_json)
                    VALUES (:submission_id, :field_id, :section_id, :answer_text, :answer_json)
                    ON DUPLICATE KEY UPDATE
                        answer_text = VALUES(answer_text),
                        answer_json = VALUES(answer_json)";
            return $this->db->prepare($sql)->execute($data);
        } catch (PDOException $e) {
            error_log("PlanningSubmissionAnswer::upsertStatic: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Salvar resposta diária (upsert por submission_id + field_id + daily_entry_id).
     */
    public function upsertDaily(array $data): bool
    {
        try {
            $sql = "INSERT INTO planning_submission_answers
                        (submission_id, field_id, section_id, daily_entry_id, answer_text, answer_json)
                    VALUES (:submission_id, :field_id, :section_id, :daily_entry_id, :answer_text, :answer_json)
                    ON DUPLICATE KEY UPDATE
                        answer_text = VALUES(answer_text),
                        answer_json = VALUES(answer_json)";
            return $this->db->prepare($sql)->execute($data);
        } catch (PDOException $e) {
            error_log("PlanningSubmissionAnswer::upsertDaily: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Deletar todas as respostas de um submission (usado ao excluir submission).
     */
    public function deleteBySubmission(int $submissionId): bool
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM planning_submission_answers WHERE submission_id = ?");
            return $stmt->execute([$submissionId]);
        } catch (PDOException $e) {
            error_log("PlanningSubmissionAnswer::deleteBySubmission: " . $e->getMessage());
            return false;
        }
    }
}
