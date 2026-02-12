<?php

namespace App\Controllers\Api;

use App\Models\VideoProgress;
use App\Models\CourseProgress;
use App\Models\Enrollment;
use App\Middleware\ApiAuthMiddleware;

class VideoTrackingController
{
    protected $videoProgress;
    protected $courseProgress;

    public function __construct()
    {
        $this->videoProgress = new VideoProgress();
        $this->courseProgress = new CourseProgress();
    }

    /**
     * GET /api/video-progress/{enrollmentId}/{lessonId}
     * Buscar progresso atual de um video
     */
    public function getProgress($enrollmentId, $lessonId)
    {
        ApiAuthMiddleware::verifyEnrollmentOwner($enrollmentId);

        $progress = $this->videoProgress->getOrCreate($enrollmentId, $lessonId);

        if ($progress) {
            $this->json(true, $progress);
        } else {
            $this->json(true, [
                'enrollment_id' => (int) $enrollmentId,
                'lesson_id' => (int) $lessonId,
                'current_time' => 0,
                'total_duration' => 0,
                'percentage_watched' => 0,
                'is_completed' => false,
            ]);
        }
    }

    /**
     * POST /api/video-progress
     * Salvar progresso do video (chamado a cada 5s)
     */
    public function saveProgress()
    {
        $data = ApiAuthMiddleware::getJsonBody();

        // Validar campos obrigatorios
        $required = ['enrollment_id', 'lesson_id', 'current_time', 'total_duration', 'percentage_watched'];
        foreach ($required as $field) {
            if (!isset($data[$field])) {
                http_response_code(400);
                $this->json(false, null, "Campo obrigatorio ausente: {$field}");
                return;
            }
        }

        $enrollmentId = (int) $data['enrollment_id'];
        $lessonId = (int) $data['lesson_id'];
        $currentTime = (float) $data['current_time'];
        $totalDuration = (float) $data['total_duration'];
        $percentage = (float) $data['percentage_watched'];

        // Validar ownership
        ApiAuthMiddleware::verifyEnrollmentOwner($enrollmentId);

        // Validar valores
        if ($totalDuration <= 0) {
            http_response_code(400);
            $this->json(false, null, 'Duracao total deve ser maior que zero');
            return;
        }
        $percentage = max(0, min(100, $percentage));

        // Buscar ou criar progresso
        $progress = $this->videoProgress->getOrCreate($enrollmentId, $lessonId, $totalDuration);
        if (!$progress) {
            http_response_code(500);
            $this->json(false, null, 'Erro ao criar progresso');
            return;
        }

        // Atualizar progresso
        $updated = $this->videoProgress->updateProgress(
            $progress['id'],
            $currentTime,
            $totalDuration,
            $percentage
        );

        if ($updated) {
            // Sincronizar com course_progress
            $this->syncCourseProgress($enrollmentId, $lessonId, $updated);

            // Atualizar enrollment
            $this->videoProgress->updateEnrollmentProgress($enrollmentId);

            $this->json(true, [
                'id' => $updated['id'],
                'current_time' => $updated['current_time'],
                'percentage_watched' => $updated['percentage_watched'],
                'is_completed' => (bool) $updated['is_completed'],
            ]);
        } else {
            http_response_code(500);
            $this->json(false, null, 'Erro ao atualizar progresso');
        }
    }

    /**
     * POST /api/video-start-session
     * Iniciar sessao de visualizacao
     */
    public function startSession()
    {
        $data = ApiAuthMiddleware::getJsonBody();

        if (empty($data['video_progress_id'])) {
            http_response_code(400);
            $this->json(false, null, 'video_progress_id obrigatorio');
            return;
        }

        $videoProgressId = (int) $data['video_progress_id'];
        $deviceInfo = $data['device_info'] ?? $_SERVER['HTTP_USER_AGENT'] ?? '';
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';

        // Verificar que o progress pertence ao usuario
        $progress = $this->videoProgress->find($videoProgressId);
        if (!$progress) {
            http_response_code(404);
            $this->json(false, null, 'Progresso nao encontrado');
            return;
        }
        ApiAuthMiddleware::verifyEnrollmentOwner($progress['enrollment_id']);

        $watchLogId = $this->videoProgress->startWatchSession($videoProgressId, $deviceInfo, $ipAddress);

        if ($watchLogId) {
            $this->json(true, [
                'watch_log_id' => (int) $watchLogId,
                'session_start' => date('Y-m-d H:i:s'),
            ]);
        } else {
            http_response_code(500);
            $this->json(false, null, 'Erro ao iniciar sessao');
        }
    }

    /**
     * POST /api/video-end-session
     * Finalizar sessao de visualizacao
     */
    public function endSession()
    {
        $data = ApiAuthMiddleware::getJsonBody();

        if (empty($data['watch_log_id'])) {
            http_response_code(400);
            $this->json(false, null, 'watch_log_id obrigatorio');
            return;
        }

        $watchLogId = (int) $data['watch_log_id'];
        $sessionDuration = (int) ($data['session_duration'] ?? 0);
        $percentageAfter = (float) ($data['percentage_after'] ?? 0);
        $completed = (bool) ($data['completed'] ?? false);

        $result = $this->videoProgress->endWatchSession($watchLogId, $sessionDuration, $percentageAfter, $completed);

        if ($result) {
            $this->json(true, null, 'Sessao finalizada com sucesso');
        } else {
            http_response_code(500);
            $this->json(false, null, 'Erro ao finalizar sessao');
        }
    }

    /**
     * POST /api/course-progress/{enrollmentId}
     * Calcular e retornar progresso geral do curso
     */
    public function calculateCourseProgress($enrollmentId)
    {
        ApiAuthMiddleware::verifyEnrollmentOwner($enrollmentId);

        $progress = $this->videoProgress->updateEnrollmentProgress($enrollmentId);

        $this->json(true, $progress);
    }

    /**
     * Sincronizar video_progress com course_progress
     */
    private function syncCourseProgress($enrollmentId, $lessonId, $videoProgress)
    {
        $existing = $this->courseProgress->findByEnrollmentAndLesson($enrollmentId, $lessonId);

        $data = [
            'enrollment_id' => $enrollmentId,
            'lesson_id' => $lessonId,
            'completed' => $videoProgress['is_completed'] ? 1 : 0,
            'completed_at' => $videoProgress['completed_at'] ?? null,
            'video_progress_id' => $videoProgress['id'],
            'video_percentage_watched' => $videoProgress['percentage_watched'],
            'video_current_time' => $videoProgress['current_time'],
            'video_total_duration' => $videoProgress['total_duration'],
            'first_played_at' => $videoProgress['first_watch_start'] ?? date('Y-m-d H:i:s'),
            'last_played_at' => date('Y-m-d H:i:s'),
            'total_play_time' => $videoProgress['total_watch_time'] ?? 0,
            'time_spent_minutes' => 0,
            'quiz_score' => null,
            'quiz_passed' => null,
            'attempts' => 0,
        ];

        $this->courseProgress->createOrUpdate($enrollmentId, $lessonId, $data);
    }

    /**
     * Resposta JSON padronizada
     */
    private function json($success, $data = null, $message = null)
    {
        header('Content-Type: application/json');
        $response = ['success' => $success];
        if ($data !== null) $response['data'] = $data;
        if ($message !== null) $response['message'] = $message;
        echo json_encode($response);
    }
}
