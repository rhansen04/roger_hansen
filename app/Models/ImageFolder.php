<?php

namespace App\Models;

use App\Core\Database\Connection;
use PDO;
use PDOException;

class ImageFolder
{
    protected $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    /**
     * Buscar todas as pastas de uma turma (coletivas + individuais de alunos)
     */
    public function findByClassroom($classroomId)
    {
        try {
            $sql = "SELECT f.*, s.name as student_name
                    FROM image_folders f
                    LEFT JOIN students s ON f.student_id = s.id
                    WHERE f.classroom_id = :classroom_id
                    ORDER BY f.folder_type ASC, f.name ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':classroom_id' => $classroomId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar pastas por turma: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Criar pasta
     */
    public function create($data)
    {
        try {
            $sql = "INSERT INTO image_folders (classroom_id, student_id, folder_type, name)
                    VALUES (:classroom_id, :student_id, :folder_type, :name)";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':classroom_id' => $data['classroom_id'],
                ':student_id' => $data['student_id'] ?? null,
                ':folder_type' => $data['folder_type'],
                ':name' => $data['name']
            ]);
            return $this->db->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erro ao criar pasta de imagens: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Buscar pasta por ID com info da turma e aluno
     */
    public function find($id)
    {
        try {
            $sql = "SELECT f.*, c.name as classroom_name, s.name as student_name,
                           c.school_year, sch.name as school_name
                    FROM image_folders f
                    LEFT JOIN classrooms c ON f.classroom_id = c.id
                    LEFT JOIN students s ON f.student_id = s.id
                    LEFT JOIN schools sch ON c.school_id = sch.id
                    WHERE f.id = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erro ao buscar pasta: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Contar imagens por pasta
     */
    public function countImages($folderId)
    {
        try {
            $sql = "SELECT COUNT(*) as total FROM image_bank WHERE folder_id = :folder_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':folder_id' => $folderId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (PDOException $e) {
            error_log("Erro ao contar imagens: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Contar imagens por pasta (batch para listagem)
     */
    public function countImagesByFolder($classroomId)
    {
        try {
            $sql = "SELECT ib.folder_id, COUNT(*) as total
                    FROM image_bank ib
                    INNER JOIN image_folders f ON ib.folder_id = f.id
                    WHERE f.classroom_id = :classroom_id
                    GROUP BY ib.folder_id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':classroom_id' => $classroomId]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $counts = [];
            foreach ($results as $row) {
                $counts[$row['folder_id']] = $row['total'];
            }
            return $counts;
        } catch (PDOException $e) {
            error_log("Erro ao contar imagens por pasta: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Garantir que pastas existam para uma turma (cria coletiva + individual por aluno)
     */
    public function ensureFoldersForClassroom($classroomId)
    {
        try {
            // Verificar se ja tem pasta coletiva
            $sql = "SELECT id FROM image_folders WHERE classroom_id = :cid AND folder_type = 'classroom'";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':cid' => $classroomId]);
            if (!$stmt->fetch()) {
                $this->create([
                    'classroom_id' => $classroomId,
                    'student_id' => null,
                    'folder_type' => 'classroom',
                    'name' => 'Registros Coletivos'
                ]);
            }

            // Buscar alunos da turma
            $sql = "SELECT cs.student_id, s.name
                    FROM classroom_students cs
                    INNER JOIN students s ON cs.student_id = s.id
                    WHERE cs.classroom_id = :cid
                    ORDER BY s.name ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':cid' => $classroomId]);
            $students = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($students as $student) {
                $sql = "SELECT id FROM image_folders
                        WHERE classroom_id = :cid AND student_id = :sid AND folder_type = 'student'";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([':cid' => $classroomId, ':sid' => $student['student_id']]);
                if (!$stmt->fetch()) {
                    $this->create([
                        'classroom_id' => $classroomId,
                        'student_id' => $student['student_id'],
                        'folder_type' => 'student',
                        'name' => $student['name']
                    ]);
                }
            }
        } catch (PDOException $e) {
            error_log("Erro ao garantir pastas: " . $e->getMessage());
        }
    }
}
