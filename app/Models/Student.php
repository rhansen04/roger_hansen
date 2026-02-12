<?php

namespace App\Models;

use App\Core\Database\Connection;
use PDO;
use PDOException;

class Student
{
    protected $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    /**
     * Listar todos os alunos
     */
    public function all()
    {
        try {
            $sql = "SELECT s.*, sch.name as school_name
                    FROM students s
                    LEFT JOIN schools sch ON s.school_id = sch.id
                    ORDER BY s.name ASC";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao listar alunos: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Buscar aluno por ID
     */
    public function find($id)
    {
        try {
            $sql = "SELECT s.*, sch.name as school_name
                    FROM students s
                    LEFT JOIN schools sch ON s.school_id = sch.id
                    WHERE s.id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar aluno: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Criar novo aluno
     */
    public function create($data)
    {
        try {
            $sql = "INSERT INTO students (name, birth_date, school_id, photo_url, created_at, updated_at)
                    VALUES (:name, :birth_date, :school_id, :photo_url, :created_at, :updated_at)";

            $stmt = $this->db->prepare($sql);
            $now = date('Y-m-d H:i:s');

            return $stmt->execute([
                ':name' => $data['name'],
                ':birth_date' => $data['birth_date'],
                ':school_id' => $data['school_id'],
                ':photo_url' => $data['photo_url'] ?? null,
                ':created_at' => $now,
                ':updated_at' => $now
            ]);
        } catch (PDOException $e) {
            error_log("Erro ao criar aluno: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Atualizar aluno
     */
    public function update($id, $data)
    {
        try {
            $sql = "UPDATE students
                    SET name = :name,
                        birth_date = :birth_date,
                        school_id = :school_id,
                        photo_url = :photo_url,
                        updated_at = :updated_at
                    WHERE id = :id";

            $stmt = $this->db->prepare($sql);

            return $stmt->execute([
                ':id' => $id,
                ':name' => $data['name'],
                ':birth_date' => $data['birth_date'],
                ':school_id' => $data['school_id'],
                ':photo_url' => $data['photo_url'] ?? null,
                ':updated_at' => date('Y-m-d H:i:s')
            ]);
        } catch (PDOException $e) {
            error_log("Erro ao atualizar aluno: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Deletar aluno
     */
    public function delete($id)
    {
        try {
            $sql = "DELETE FROM students WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Erro ao deletar aluno: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Buscar alunos por escola
     */
    public function findBySchool($school_id)
    {
        try {
            $sql = "SELECT s.*, sch.name as school_name
                    FROM students s
                    LEFT JOIN schools sch ON s.school_id = sch.id
                    WHERE s.school_id = ?
                    ORDER BY s.name ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$school_id]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar alunos por escola: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Contar total de alunos
     */
    public function countTotal()
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM students";
            $stmt = $this->db->query($sql);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (PDOException $e) {
            error_log("Erro ao contar alunos: " . $e->getMessage());
            return 0;
        }
    }
}