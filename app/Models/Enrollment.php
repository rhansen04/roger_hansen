<?php

namespace App\Models;

use PDO;
use PDOException;
use App\Core\Database\Connection;

class Enrollment
{
    protected $db;
    
    public function __construct()
    {
        $this->db = Connection::getInstance();
    }
    
    /**
     * Buscar matrícula por ID
     */
    public function find($id)
    {
        try {
            $sql = "SELECT e.*,
                           u.name as student_name,
                           u.email as student_email,
                           c.title as course_title,
                           c.slug as course_slug
                    FROM enrollments e
                    JOIN users u ON e.user_id = u.id
                    JOIN courses c ON e.course_id = c.id
                    WHERE e.id = ? LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Erro ao buscar matrícula: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Buscar matrículas de um usuário com progresso real calculado
     */
    public function getByUser($userId)
    {
        try {
            $sql = "SELECT e.*,
                           c.title as course_title,
                           c.slug as course_slug,
                           (SELECT COUNT(l.id)
                            FROM lessons l
                            JOIN sections s ON l.section_id = s.id
                            WHERE s.course_id = e.course_id) AS real_total_lessons,
                           (SELECT COUNT(*)
                            FROM course_progress cp
                            WHERE cp.enrollment_id = e.id AND cp.completed = 1) AS real_completed_lessons
                    FROM enrollments e
                    JOIN courses c ON e.course_id = c.id
                    WHERE e.user_id = ?
                    ORDER BY e.enrollment_date DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId]);
            $rows = $stmt->fetchAll();
            // Calcular percentual real e sobrescrever o campo cacheado
            foreach ($rows as &$row) {
                $total = (int) $row['real_total_lessons'];
                $done  = (int) $row['real_completed_lessons'];
                $row['overall_progress_percentage'] = $total > 0
                    ? round($done / $total * 100, 0)
                    : 0;
            }
            return $rows;
        } catch (PDOException $e) {
            error_log("Erro ao buscar matrículas: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Buscar matrículas de um curso
     */
    public function getByCourse($courseId)
    {
        try {
            $sql = "SELECT e.*,
                           u.name as student_name,
                           u.email as student_email
                    FROM enrollments e
                    JOIN users u ON e.user_id = u.id
                    WHERE e.course_id = ?
                    ORDER BY e.enrollment_date DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$courseId]);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            error_log("Erro ao buscar matrículas: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Criar nova matrícula
     */
    public function create($data)
    {
        try {
            $sql = "INSERT INTO enrollments (user_id, course_id, status, payment_status)
                    VALUES (:user_id, :course_id, :status, :payment_status)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($data);
        } catch (PDOException $e) {
            error_log("Erro ao criar matrícula: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Atualizar matrícula
     */
    public function update($id, $data)
    {
        try {
            $data['id'] = $id;
            $sql = "UPDATE enrollments
                    SET user_id = :user_id,
                        course_id = :course_id,
                        status = :status,
                        payment_status = :payment_status,
                        certificate_issued = :certificate_issued,
                        certificate_url = :certificate_url,
                        overall_progress_percentage = :overall_progress_percentage,
                        lessons_completed_count = :lessons_completed_count,
                        total_lessons_count = :total_lessons_count,
                        videos_completed_count = :videos_completed_count,
                        total_videos_count = :total_videos_count,
                        total_watch_time = :total_watch_time,
                        first_activity_at = :first_activity_at,
                        last_activity_at = :last_activity_at,
                        completion_percentage = :completion_percentage,
                        is_course_completed = :is_course_completed,
                        course_completed_at = :course_completed_at,
                        certificate_eligible = :certificate_eligible
                    WHERE id = :id";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute($data);
        } catch (PDOException $e) {
            error_log("Erro ao atualizar matrícula: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Deletar matrícula
     */
    public function delete($id)
    {
        try {
            $stmt = $this->db->prepare("DELETE FROM enrollments WHERE id = ?");
            return $stmt->execute([$id]);
        } catch (PDOException $e) {
            error_log("Erro ao deletar matrícula: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Verificar se usuário está matriculado no curso
     */
    public function isEnrolled($userId, $courseId)
    {
        try {
            $sql = "SELECT COUNT(*) as enrolled FROM enrollments
                    WHERE user_id = ? AND course_id = ? AND status = 'active'";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userId, $courseId]);
            $result = $stmt->fetch();
            return ($result['enrolled'] ?? 0) > 0;
        } catch (PDOException $e) {
            error_log("Erro ao verificar matrícula: " . $e->getMessage());
            return false;
        }
    }
}
