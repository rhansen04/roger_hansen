<?php

namespace App\Models;

use PDO;
use PDOException;
use App\Core\Database\Connection;
use App\Core\Cache\RedisCache;

class VideoProgress
{
    protected $db;
    protected $cache;
    
    public function __construct()
    {
        $this->db = Connection::getInstance();
        $this->cache = new RedisCache();
    }
    
    /**
     * Buscar ou criar progresso de vídeo
     */
    public function getOrCreate($enrollmentId, $lessonId, $videoDuration = 0)
    {
        $cacheKey = "video_progress:{$enrollmentId}:{$lessonId}";
        
        $cached = $this->cache->get($cacheKey);
        if ($cached !== null) {
            return $cached;
        }
        
        try {
            // Tenta buscar progresso existente
            $stmt = $this->db->prepare("
                SELECT * FROM video_progress
                WHERE enrollment_id = ? AND lesson_id = ?
                LIMIT 1
            ");
            $stmt->execute([$enrollmentId, $lessonId]);
            $progress = $stmt->fetch();
            
            if ($progress) {
                $this->cache->set($cacheKey, $progress);
                return $progress;
            }
            
            // Se não existe, cria novo
            $sql = "INSERT INTO video_progress
                        (enrollment_id, lesson_id, total_duration, created_at)
                        VALUES (?, ?, ?, NOW())";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$enrollmentId, $lessonId, $videoDuration]);
            
            // Busca novamente para retornar o novo registro
            $stmt = $this->db->prepare("
                SELECT * FROM video_progress
                WHERE enrollment_id = ? AND lesson_id = ?
                LIMIT 1
            ");
            $stmt->execute([$enrollmentId, $lessonId]);
            $progress = $stmt->fetch();
            
            if ($progress) {
                $this->cache->set($cacheKey, $progress);
            }
            
            return $progress;
        } catch (PDOException $e) {
            error_log("Erro ao buscar/criar progresso: " . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Atualizar progresso de vídeo
     */
    public function updateProgress($videoProgressId, $currentTime, $totalDuration, $percentage)
    {
        try {
            $sql = "UPDATE video_progress
                    SET `current_time` = ?,
                        total_duration = ?,
                        percentage_watched = ?,
                        last_watched_at = NOW()
                    WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$currentTime, $totalDuration, $percentage, $videoProgressId]);
            
            // Se atingir 97%, marca como completado
            if ($percentage >= 97) {
                $this->markAsCompleted($videoProgressId);
            }
            
            // Buscar progresso atualizado
            $progress = $this->find($videoProgressId);
            if ($progress) {
                // Limpar cache
                $this->cache->delete("video_progress:{$progress['enrollment_id']}:{$progress['lesson_id']}");
            }
            
            return $progress;
        } catch (PDOException $e) {
            error_log("Erro ao atualizar progresso: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Marcar vídeo como completado (97% ou mais)
     */
    public function markAsCompleted($videoProgressId)
    {
        try {
            $sql = "UPDATE video_progress
                    SET is_completed = 1,
                        completed_at = NOW(),
                        percentage_watched = 100.00,
                        `current_time` = total_duration,
                        last_watched_at = NOW()
                    WHERE id = ? AND is_completed = 0";
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([$videoProgressId]);
            
            // Limpar cache
            $progress = $this->find($videoProgressId);
            if ($progress) {
                // Sincronizar com course_progress
                $this->syncWithCourseProgress($videoProgressId, $progress['enrollment_id'], $progress['lesson_id'], true);
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log("Erro ao marcar como completado: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Sincronizar com tabela course_progress
     */
    private function syncWithCourseProgress($videoProgressId, $enrollmentId, $lessonId, $isCompleted)
    {
        try {
            $sql = "UPDATE course_progress
                    SET completed = ?,
                        completed_at = ?,
                        video_progress_id = ?,
                        video_percentage_watched = 100.00
                    WHERE enrollment_id = ? AND lesson_id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$isCompleted, $isCompleted ? date('Y-m-d H:i:s') : null, $videoProgressId, $enrollmentId, $lessonId]);
            
            // Limpar cache
            $this->cache->delete("course_progress:{$enrollmentId}:{$lessonId}");
        } catch (PDOException $e) {
            error_log("Erro ao sincronizar progresso: " . $e->getMessage());
        }
    }
    
    /**
     * Buscar todos os vídeos de uma matrícula
     */
    public function getVideosByEnrollment($enrollmentId)
    {
        try {
            $cacheKey = "videos:enrollment:{$enrollmentId}";
            
            $cached = $this->cache->get($cacheKey);
            if ($cached !== null) {
                return $cached;
            }
            
            $sql = "SELECT vp.*,
                           l.title as lesson_title,
                           s.title as section_title
                    FROM video_progress vp
                    LEFT JOIN lessons l ON vp.lesson_id = l.id
                    LEFT JOIN sections s ON l.section_id = s.id
                    WHERE vp.enrollment_id = ?
                    ORDER BY s.sort_order ASC, l.sort_order ASC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$enrollmentId]);
            $result = $stmt->fetchAll();
            
            $this->cache->set($cacheKey, $result);
            
            return $result;
        } catch (PDOException $e) {
            error_log("Erro ao buscar vídeos: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Calcular progresso do curso de uma matrícula
     */
    public function calculateCourseProgress($enrollmentId)
    {
        try {
            // Buscar todos os vídeos com progresso
            $videos = $this->getVideosByEnrollment($enrollmentId);
            
            if (empty($videos)) {
                return [
                    'total_videos' => 0,
                    'completed_videos' => 0,
                    'progress_percentage' => 0,
                    'is_completed' => false
                ];
            }
            
            $totalVideos = count($videos);
            $completedVideos = 0;
            $totalPercentage = 0;
            
            foreach ($videos as $video) {
                if ($video['is_completed']) {
                    $completedVideos++;
                }
                $totalPercentage += $video['percentage_watched'];
            }
            
            $progressPercentage = $totalVideos > 0 ? round($totalPercentage / $totalVideos, 2) : 0;
            $isCompleted = ($completedVideos === $totalVideos) && ($totalVideos > 0);
            
            return [
                'total_videos' => $totalVideos,
                'completed_videos' => $completedVideos,
                'progress_percentage' => $progressPercentage,
                'is_completed' => $isCompleted
            ];
        } catch (PDOException $e) {
            error_log("Erro ao calcular progresso: " . $e->getMessage());
            return [
                'total_videos' => 0,
                'completed_videos' => 0,
                'progress_percentage' => 0,
                'is_completed' => false
            ];
        }
    }
    
    /**
     * Atualizar progresso da matrícula
     * Calcula a partir de course_progress (fonte de verdade)
     */
    public function updateEnrollmentProgress($enrollmentId)
    {
        try {
            // Total de lições do curso desta matrícula
            $stmtTotal = $this->db->prepare("
                SELECT COUNT(l.id)
                FROM lessons l
                JOIN sections s ON l.section_id = s.id
                JOIN enrollments e ON s.course_id = e.course_id
                WHERE e.id = ?
            ");
            $stmtTotal->execute([$enrollmentId]);
            $totalLessons = (int) $stmtTotal->fetchColumn();

            // Lições concluídas
            $stmtDone = $this->db->prepare("
                SELECT COUNT(*) FROM course_progress
                WHERE enrollment_id = ? AND completed = 1
            ");
            $stmtDone->execute([$enrollmentId]);
            $completedLessons = (int) $stmtDone->fetchColumn();

            $progressPercentage = $totalLessons > 0
                ? round($completedLessons / $totalLessons * 100, 2)
                : 0;
            $isCompleted = ($totalLessons > 0 && $completedLessons >= $totalLessons);

            $sql = "UPDATE enrollments
                    SET overall_progress_percentage = ?,
                        lessons_completed_count = ?,
                        videos_completed_count = ?,
                        total_videos_count = ?,
                        completion_percentage = ?,
                        is_course_completed = ?,
                        last_activity_at = ?,
                        certificate_eligible = ?,
                        course_completed_at = ?
                    WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                $progressPercentage,
                $completedLessons,
                $completedLessons,
                $totalLessons,
                $progressPercentage,
                $isCompleted ? 1 : 0,
                date('Y-m-d H:i:s'),
                $isCompleted ? 1 : 0,
                $isCompleted ? date('Y-m-d H:i:s') : null,
                $enrollmentId
            ]);

            // Limpar caches
            $this->cache->delete("videos:enrollment:{$enrollmentId}");
            $this->cache->delete("course_progress:enrollment:{$enrollmentId}");

            return [
                'total_videos'       => $totalLessons,
                'completed_videos'   => $completedLessons,
                'progress_percentage' => $progressPercentage,
                'is_completed'       => $isCompleted,
            ];
        } catch (PDOException $e) {
            error_log("Erro ao atualizar progresso da matrícula: " . $e->getMessage());
            return [
                'total_videos' => 0,
                'completed_videos' => 0,
                'progress_percentage' => 0,
                'is_completed' => false
            ];
        }
    }
    
    /**
     * Iniciar nova sessão de visualização
     */
    public function startWatchSession($videoProgressId, $deviceInfo = null, $ipAddress = null)
    {
        try {
            $progress = $this->find($videoProgressId);
            
            // Atualizar contagem de sessões
            $this->db->prepare("UPDATE video_progress SET watch_sessions = watch_sessions + 1 WHERE id = ?")->execute([$videoProgressId]);
            
            // Se for a primeira visualização, salvar data
            if (!$progress['first_watch_start']) {
                $this->db->prepare("UPDATE video_progress SET first_watch_start = ? WHERE id = ?")->execute([date('Y-m-d H:i:s'), $videoProgressId]);
            }
            
            // Criar log de sessão
            $sql = "INSERT INTO video_watch_logs
                        (video_progress_id, session_start, percentage_before, device_info, ip_address)
                        VALUES (?, NOW(), ?, ?, ?)";
            $stmt = $this->db->prepare($sql);

            $percentageBefore = $progress['percentage_watched'] ?? 0;
            $stmt->execute([$videoProgressId, $percentageBefore, $deviceInfo, $ipAddress]);
            return $this->db->lastInsertId() ?: false;
        } catch (PDOException $e) {
            error_log("Erro ao iniciar sessão: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Finalizar sessão de visualização
     */
    public function endWatchSession($watchLogId, $sessionDuration, $percentageAfter, $completed = false)
    {
        try {
            $sql = "UPDATE video_watch_logs
                        SET session_end = ?,
                            session_duration = ?,
                            percentage_after = ?,
                            completed_during_session = ?
                        WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([date('Y-m-d H:i:s'), $sessionDuration, $percentageAfter, $completed ? 1 : 0, $watchLogId]);

            return $result;
        } catch (PDOException $e) {
            error_log("Erro ao finalizar sessão: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Buscar histórico de sessões de um vídeo
     */
    public function getWatchLogs($videoProgressId)
    {
        try {
            $cacheKey = "watch_logs:{$videoProgressId}";
            
            $cached = $this->cache->get($cacheKey);
            if ($cached !== null) {
                return $cached;
            }
            
            $sql = "SELECT * FROM video_watch_logs
                    WHERE video_progress_id = ?
                    ORDER BY session_start DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$videoProgressId]);
            $result = $stmt->fetchAll();
            
            $this->cache->set($cacheKey, $result);
            
            return $result;
        } catch (PDOException $e) {
            error_log("Erro ao buscar logs de sessões: " . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Buscar por ID
     */
    public function find($id)
    {
        try {
            $sql = "SELECT * FROM video_progress WHERE id = ? LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$id]);
            return $stmt->fetch();
        } catch (PDOException $e) {
            error_log("Erro ao buscar progresso por ID: " . $e->getMessage());
            return null;
        }
    }
}
