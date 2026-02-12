<?php

namespace App\Models;

use PDO;
use PDOException;
use App\Core\Database\Connection;
use App\Core\Cache\RedisCache;

class CourseProgress
{
    protected $db;
    protected $cache;
    
    public function __construct()
    {
        $this->db = Connection::getInstance();
        $this->cache = new RedisCache();
    }
    
    /**
     * Buscar progresso de uma lição
     */
    public function findByEnrollmentAndLesson($enrollmentId, $lessonId)
    {
        try {
            $cacheKey = "course_progress:{$enrollmentId}:{$lessonId}";
            
            $cached = $this->cache->get($cacheKey);
            if ($cached !== null) {
                return $cached;
            }
            
            $sql = "SELECT * FROM course_progress
                    WHERE enrollment_id = ? AND lesson_id = ?
                    LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$enrollmentId, $lessonId]);
            $result = $stmt->fetch();
            
            if ($result) {
                $this->cache->set($cacheKey, $result);
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log("Erro ao buscar progresso: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Criar ou atualizar progresso
     */
    public function createOrUpdate($enrollmentId, $lessonId, $data = [])
    {
        try {
            // Verificar se já existe
            $existing = $this->findByEnrollmentAndLesson($enrollmentId, $lessonId);
            
            if ($existing) {
                // Atualizar
                $data['id'] = $existing['id'];
                $sql = "UPDATE course_progress
                        SET completed = :completed,
                            completed_at = ?,
                            time_spent_minutes = :time_spent_minutes,
                            quiz_score = :quiz_score,
                            quiz_passed = :quiz_passed,
                            attempts = :attempts,
                            video_progress_id = :video_progress_id,
                            video_percentage_watched = :video_percentage_watched,
                            video_current_time = :video_current_time,
                            video_total_duration = :video_total_duration,
                            first_played_at = :first_played_at,
                            last_played_at = :last_played_at,
                            total_play_time = :total_play_time,
                            play_count = play_count + 1,
                            updated_at = NOW()
                        WHERE id = :id";
                $data['completed_at'] = $data['completed'] && !$existing['completed'] ? date('Y-m-d H:i:s') : $data['completed_at'];
                
                $stmt = $this->db->prepare($sql);
                $result = $stmt->execute($data);
                
                // Limpar cache
                $this->cache->delete("course_progress:{$enrollmentId}:{$lessonId}");
                
                return $result;
            } else {
                // Criar novo
                $sql = "INSERT INTO course_progress
                        (enrollment_id, lesson_id, completed, completed_at, time_spent_minutes,
                         quiz_score, quiz_passed, attempts, video_progress_id,
                         video_percentage_watched, video_current_time, video_total_duration,
                         first_played_at, last_played_at, total_play_time, play_count,
                         created_at, updated_at)
                        VALUES (:enrollment_id, :lesson_id, :completed, :completed_at, :time_spent_minutes,
                                :quiz_score, :quiz_passed, :attempts, :video_progress_id,
                                :video_percentage_watched, :video_current_time, :video_total_duration,
                                :first_played_at, :last_played_at, :total_play_time, 1,
                                NOW(), NOW())";
                $stmt = $this->db->prepare($sql);
                $result = $stmt->execute($data);
                
                return $result ? $this->db->lastInsertId() : false;
            }
        } catch (PDOException $e) {
            error_log("Erro ao criar/atualizar progresso: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Buscar todos os progressos de uma matrícula
     */
    public function getByEnrollment($enrollmentId)
    {
        try {
            $cacheKey = "course_progress:enrollment:{$enrollmentId}";
            
            $cached = $this->cache->get($cacheKey);
            if ($cached !== null) {
                return $cached;
            }
            
            $sql = "SELECT cp.*,
                           l.title as lesson_title,
                           s.title as section_title
                    FROM course_progress cp
                    JOIN lessons l ON cp.lesson_id = l.id
                    LEFT JOIN sections s ON l.section_id = s.id
                    WHERE cp.enrollment_id = ?
                    ORDER BY s.sort_order ASC, l.sort_order ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$enrollmentId]);
            $result = $stmt->fetchAll();
            
            $this->cache->set($cacheKey, $result);
            
            return $result;
        } catch (PDOException $e) {
            error_log("Erro ao buscar progressos: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Marcar lição como concluída
     */
    public function markAsCompleted($courseProgressId)
    {
        try {
            $sql = "UPDATE course_progress
                    SET completed = 1,
                        completed_at = NOW(),
                        quiz_passed = 1,
                        video_percentage_watched = 100.00,
                        video_current_time = video_total_duration
                    WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([$courseProgressId]);
            
            // Limpar cache
            $progress = $this->find($courseProgressId);
            if ($progress) {
                $this->cache->delete("course_progress:{$progress['enrollment_id']}:{$progress['lesson_id']}");
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log("Erro ao marcar como concluído: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Buscar por ID
     */
    public function find($id)
    {
        try {
            $sql = "SELECT * FROM course_progress WHERE id = ? LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Erro ao buscar progresso por ID: " . $e->getMessage());
            return null;
        }
    }
}
