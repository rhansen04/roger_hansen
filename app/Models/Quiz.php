<?php

namespace App\Models;

use PDO;
use PDOException;
use App\Core\Database\Connection;

class Quiz
{
    protected $db;
    
    public function __construct()
    {
        $this->db = Connection::getInstance();
    }
    
    /**
     * Buscar quiz por ID
     */
    public function find($id)
    {
        try {
            $sql = "SELECT * FROM quizzes WHERE id = ? LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Erro ao buscar quiz: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Buscar quizzes de uma seção
     */
    public function getBySection($sectionId)
    {
        try {
            $sql = "SELECT * FROM quizzes WHERE section_id = ? ORDER BY sort_order ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$sectionId]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Erro ao buscar quizzes: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Criar novo quiz
     */
    public function create($data)
    {
        try {
            $sql = "INSERT INTO quizzes (section_id, lesson_id, title, description, passing_score, time_limit_minutes, attempts_allowed, sort_order)
                    VALUES (:section_id, :lesson_id, :title, :description, :passing_score, :time_limit_minutes, :attempts_allowed, :sort_order)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($data);
        } catch (PDOException $e) {
            error_log("Erro ao criar quiz: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Atualizar quiz
     */
    public function update($id, $data)
    {
        try {
            $data['id'] = $id;
            $sql = "UPDATE quizzes
                    SET section_id = :section_id,
                        lesson_id = :lesson_id,
                        title = :title,
                        description = :description,
                        passing_score = :passing_score,
                        time_limit_minutes = :time_limit_minutes,
                        attempts_allowed = :attempts_allowed,
                        sort_order = :sort_order
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($data);
        } catch (PDOException $e) {
            error_log("Erro ao atualizar quiz: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Deletar quiz
     */
    public function delete($id)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM quizzes WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Erro ao deletar quiz: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Buscar questões de um quiz
     */
    public function getQuestions($quizId)
    {
        try {
            $sql = "SELECT * FROM quiz_questions WHERE quiz_id = ? ORDER BY sort_order ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$quizId]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Erro ao buscar questões: " . $e->getMessage());
            return [];
        }
    }
}
