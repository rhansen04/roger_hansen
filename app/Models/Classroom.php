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

    public function toggleStatus($id)
    {
        try {
            $sql = "UPDATE classrooms
                    SET status = CASE WHEN status = 'active' THEN 'inactive' ELSE 'active' END,
                        updated_at = :updated_at
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':id' => $id,
                ':updated_at' => date('Y-m-d H:i:s')
            ]);
        } catch (PDOException $e) {
            error_log("Erro ao alterar status da turma: " . $e->getMessage());
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

    /**
     * Retorna os alunos de uma turma com idade calculada
     */
    public function students($classroomId)
    {
        try {
            $sql = "SELECT s.*, sch.name as school_name, cs.enrolled_at,
                           TIMESTAMPDIFF(YEAR, s.birth_date, CURDATE()) as age_years,
                           TIMESTAMPDIFF(MONTH, s.birth_date, CURDATE()) % 12 as age_months
                    FROM classroom_students cs
                    INNER JOIN students s ON cs.student_id = s.id
                    LEFT JOIN schools sch ON s.school_id = sch.id
                    WHERE cs.classroom_id = :classroom_id
                    ORDER BY s.name ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':classroom_id' => $classroomId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar alunos da turma: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Adiciona um aluno a uma turma
     */
    public function addStudent($classroomId, $studentId)
    {
        try {
            $sql = "INSERT INTO classroom_students (classroom_id, student_id, enrolled_at)
                    VALUES (:classroom_id, :student_id, CURDATE())";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':classroom_id' => $classroomId,
                ':student_id' => $studentId
            ]);
        } catch (PDOException $e) {
            error_log("Erro ao adicionar aluno na turma: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Remove um aluno de uma turma
     */
    public function removeStudent($classroomId, $studentId)
    {
        try {
            $sql = "DELETE FROM classroom_students
                    WHERE classroom_id = :classroom_id AND student_id = :student_id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([
                ':classroom_id' => $classroomId,
                ':student_id' => $studentId
            ]);
        } catch (PDOException $e) {
            error_log("Erro ao remover aluno da turma: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Conta alunos em uma turma
     */
    public function countStudents($classroomId)
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM classroom_students WHERE classroom_id = :classroom_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':classroom_id' => $classroomId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (PDOException $e) {
            error_log("Erro ao contar alunos da turma: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Retorna alunos que NAO estao nesta turma (para dropdown de adicionar)
     */
    public function availableStudents($classroomId)
    {
        try {
            $sql = "SELECT s.*, sch.name as school_name
                    FROM students s
                    LEFT JOIN schools sch ON s.school_id = sch.id
                    WHERE s.id NOT IN (
                        SELECT student_id FROM classroom_students WHERE classroom_id = :classroom_id
                    )
                    ORDER BY s.name ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':classroom_id' => $classroomId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar alunos disponiveis: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Conta alunos por turma (para listagem)
     */
    public function countStudentsByClassroom()
    {
        try {
            $sql = "SELECT classroom_id, COUNT(*) as total
                    FROM classroom_students
                    GROUP BY classroom_id";
            $stmt = $this->db->query($sql);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $counts = [];
            foreach ($results as $row) {
                $counts[$row['classroom_id']] = $row['total'];
            }
            return $counts;
        } catch (PDOException $e) {
            error_log("Erro ao contar alunos por turma: " . $e->getMessage());
            return [];
        }
    }
}
