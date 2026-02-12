<?php

namespace App\Models;

use App\Core\Database\Connection;
use PDO;
use PDOException;

class Observation
{
    protected $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    /**
     * Listar todas as observações
     */
    public function all()
    {
        try {
            $sql = "SELECT o.*,
                           COALESCE(u.name, 'Usuário desconhecido') as teacher_name,
                           COALESCE(s.name, 'Aluno não encontrado') as student_name
                    FROM observations o
                    LEFT JOIN users u ON o.user_id = u.id
                    LEFT JOIN students s ON o.student_id = s.id
                    ORDER BY o.observation_date DESC, o.created_at DESC";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao listar observações: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Buscar observação por ID
     */
    public function find($id)
    {
        try {
            $sql = "SELECT o.*,
                           COALESCE(u.name, 'Usuário desconhecido') as teacher_name,
                           COALESCE(s.name, 'Aluno não encontrado') as student_name
                    FROM observations o
                    LEFT JOIN users u ON o.user_id = u.id
                    LEFT JOIN students s ON o.student_id = s.id
                    WHERE o.id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar observação: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Criar nova observação
     */
    public function create($data)
    {
        try {
            $sql = "INSERT INTO observations (student_id, user_id, category, title, description, observation_date, created_at, updated_at)
                    VALUES (:student_id, :user_id, :category, :title, :description, :observation_date, :created_at, :updated_at)";

            $stmt = $this->db->prepare($sql);
            $now = date('Y-m-d H:i:s');

            return $stmt->execute([
                ':student_id' => $data['student_id'],
                ':user_id' => $data['user_id'],
                ':category' => $data['category'] ?? 'Geral',
                ':title' => $data['title'],
                ':description' => $data['description'] ?? '',
                ':observation_date' => $data['observation_date'] ?? date('Y-m-d'),
                ':created_at' => $now,
                ':updated_at' => $now
            ]);
        } catch (PDOException $e) {
            error_log("Erro ao criar observação: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Atualizar observação
     */
    public function update($id, $data)
    {
        try {
            $sql = "UPDATE observations
                    SET student_id = :student_id,
                        user_id = :user_id,
                        category = :category,
                        title = :title,
                        description = :description,
                        observation_date = :observation_date,
                        updated_at = :updated_at
                    WHERE id = :id";

            $stmt = $this->db->prepare($sql);

            return $stmt->execute([
                ':id' => $id,
                ':student_id' => $data['student_id'],
                ':user_id' => $data['user_id'],
                ':category' => $data['category'] ?? 'Geral',
                ':title' => $data['title'],
                ':description' => $data['description'] ?? '',
                ':observation_date' => $data['observation_date'] ?? date('Y-m-d'),
                ':updated_at' => date('Y-m-d H:i:s')
            ]);
        } catch (PDOException $e) {
            error_log("Erro ao atualizar observação: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Deletar observação
     */
    public function delete($id)
    {
        try {
            $sql = "DELETE FROM observations WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Erro ao deletar observação: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Buscar observações por aluno
     */
    public function findByStudent($student_id)
    {
        try {
            $sql = "SELECT o.*,
                           COALESCE(s.name, 'Aluno não encontrado') as student_name,
                           COALESCE(u.name, 'Usuário desconhecido') as teacher_name
                    FROM observations o
                    LEFT JOIN students s ON o.student_id = s.id
                    LEFT JOIN users u ON o.user_id = u.id
                    WHERE o.student_id = ?
                    ORDER BY o.observation_date DESC, o.created_at DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$student_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar observações por aluno: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Alias para compatibilidade com código existente
     */
    public function allByStudent($studentId)
    {
        return $this->findByStudent($studentId);
    }

    /**
     * Contar total de observações
     */
    public function countTotal()
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM observations";
            $stmt = $this->db->query($sql);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (PDOException $e) {
            error_log("Erro ao contar observações: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Buscar observações recentes
     */
    public function recentObservations($limit = 10)
    {
        try {
            $sql = "SELECT o.*,
                           COALESCE(u.name, 'Usuário desconhecido') as teacher_name,
                           COALESCE(s.name, 'Aluno não encontrado') as student_name
                    FROM observations o
                    LEFT JOIN users u ON o.user_id = u.id
                    LEFT JOIN students s ON o.student_id = s.id
                    ORDER BY o.observation_date DESC, o.created_at DESC
                    LIMIT ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$limit]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar observações recentes: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Buscar observações por tipo/categoria
     */
    public function findByType($category)
    {
        try {
            $sql = "SELECT o.*,
                           COALESCE(u.name, 'Usuário desconhecido') as teacher_name,
                           COALESCE(s.name, 'Aluno não encontrado') as student_name
                    FROM observations o
                    LEFT JOIN users u ON o.user_id = u.id
                    LEFT JOIN students s ON o.student_id = s.id
                    WHERE o.category = ?
                    ORDER BY o.observation_date DESC, o.created_at DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$category]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar observações por categoria: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Buscar observações por período
     */
    public function findByDateRange($dateFrom, $dateTo)
    {
        try {
            $sql = "SELECT o.*,
                           COALESCE(u.name, 'Usuário desconhecido') as teacher_name,
                           COALESCE(s.name, 'Aluno não encontrado') as student_name
                    FROM observations o
                    LEFT JOIN users u ON o.user_id = u.id
                    LEFT JOIN students s ON o.student_id = s.id
                    WHERE o.observation_date BETWEEN ? AND ?
                    ORDER BY o.observation_date DESC, o.created_at DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$dateFrom, $dateTo]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar observações por período: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Contar observações por categoria
     */
    public function countByCategory()
    {
        try {
            $sql = "SELECT category, COUNT(*) as total
                    FROM observations
                    GROUP BY category
                    ORDER BY total DESC";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao contar observações por categoria: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Alias para compatibilidade (deprecated - use countByCategory)
     */
    public function countByType()
    {
        return $this->countByCategory();
    }
}
