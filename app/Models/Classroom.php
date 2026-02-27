<?php

namespace App\Models;

use App\Core\Database\Connection;
use PDO;
use PDOException;

class Classroom
{
    protected $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    public function all()
    {
        try {
            $sql = "SELECT c.*, s.name as school_name, u.name as teacher_name
                    FROM classrooms c
                    LEFT JOIN schools s ON c.school_id = s.id
                    LEFT JOIN users u ON c.teacher_id = u.id
                    ORDER BY c.school_year DESC, c.name ASC";
            $stmt = $this->db->query($sql);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao listar turmas: " . $e->getMessage());
            return [];
        }
    }

    public function find($id)
    {
        try {
            $sql = "SELECT c.*, s.name as school_name, u.name as teacher_name
                    FROM classrooms c
                    LEFT JOIN schools s ON c.school_id = s.id
                    LEFT JOIN users u ON c.teacher_id = u.id
                    WHERE c.id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar turma: " . $e->getMessage());
            return null;
        }
    }

    public function create($data)
    {
        try {
            $sql = "INSERT INTO classrooms (school_id, teacher_id, name, age_group, period, school_year, status, created_at, updated_at)
                    VALUES (:school_id, :teacher_id, :name, :age_group, :period, :school_year, :status, :created_at, :updated_at)";
            $stmt = $this->db->prepare($sql);
            $now = date('Y-m-d H:i:s');
            return $stmt->execute([
                ':school_id' => $data['school_id'],
                ':teacher_id' => $data['teacher_id'],
                ':name' => $data['name'],
                ':age_group' => $data['age_group'],
                ':period' => $data['period'] ?? 'morning',
                ':school_year' => $data['school_year'] ?? date('Y'),
                ':status' => $data['status'] ?? 'active',
                ':created_at' => $now,
                ':updated_at' => $now
            ]);
        } catch (PDOException $e) {
            error_log("Erro ao criar turma: " . $e->getMessage());
            return false;
        }
    }

    public function update($id, $data)
    {
        try {
            $sql = "UPDATE classrooms
                    SET school_id = :school_id,
                        teacher_id = :teacher_id,
                        name = :name,
                        age_group = :age_group,
                        period = :period,
                        school_year = :school_year,
                        status = :status,
                        updated_at = :updated_at
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':id' => $id,
                ':school_id' => $data['school_id'],
                ':teacher_id' => $data['teacher_id'],
                ':name' => $data['name'],
                ':age_group' => $data['age_group'],
                ':period' => $data['period'] ?? 'morning',
                ':school_year' => $data['school_year'] ?? date('Y'),
                ':status' => $data['status'] ?? 'active',
                ':updated_at' => date('Y-m-d H:i:s')
            ]);
        } catch (PDOException $e) {
            error_log("Erro ao atualizar turma: " . $e->getMessage());
            return false;
        }
    }

    public function delete($id)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM classrooms WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Erro ao deletar turma: " . $e->getMessage());
            return false;
        }
    }

    public function getByTeacher($teacherId)
    {
        try {
            $sql = "SELECT c.*, s.name as school_name
                    FROM classrooms c
                    LEFT JOIN schools s ON c.school_id = s.id
                    WHERE c.teacher_id = ? AND c.status = 'active'
                    ORDER BY c.school_year DESC, c.name ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$teacherId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar turmas por professor: " . $e->getMessage());
            return [];
        }
    }

    public function getBySchool($schoolId)
    {
        try {
            $sql = "SELECT c.*, u.name as teacher_name
                    FROM classrooms c
                    LEFT JOIN users u ON c.teacher_id = u.id
                    WHERE c.school_id = ? AND c.status = 'active'
                    ORDER BY c.name ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$schoolId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar turmas por escola: " . $e->getMessage());
            return [];
        }
    }
}
