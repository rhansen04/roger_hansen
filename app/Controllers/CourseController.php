<?php

namespace App\Controllers;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Lesson;
use App\Models\Section;
use App\Models\VideoProgress;
use App\Models\CourseProgress;
use App\Core\Database\Connection;

class CourseController
{
    protected $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    /**
     * GET /curso/{slug}
     * Pagina do curso com secoes e licoes
     */
    public function show($slug)
    {
        $courseModel = new Course();
        $course = $courseModel->findBySlug($slug);

        if (!$course) {
            http_response_code(404);
            echo "Curso nao encontrado";
            return;
        }

        // Buscar secoes com licoes
        $sectionModel = new Section();
        $sections = $sectionModel->getByCourse($course['id']);

        $lessonModel = new Lesson();
        foreach ($sections as &$section) {
            $section['lessons'] = $lessonModel->getBySection($section['id']);
        }

        // Verificar enrollment do usuario logado
        $enrollment = null;
        if (isset($_SESSION['user_id'])) {
            $enrollmentModel = new Enrollment();
            $enrollment = $this->getEnrollmentForUser($_SESSION['user_id'], $course['id']);
        }

        // Buscar quizzes do curso
        $quizzes = [];
        $stmt = $this->db->prepare("
            SELECT q.* FROM quizzes q
            JOIN sections s ON q.section_id = s.id
            WHERE s.course_id = ?
            ORDER BY s.sort_order, q.sort_order
        ");
        $stmt->execute([$course['id']]);
        $quizzes = $stmt->fetchAll();

        $this->render('curso-detalhe', [
            'title' => $course['title'] . ' | Hansen Educacional',
            'course' => $course,
            'sections' => $sections,
            'enrollment' => $enrollment,
            'quizzes' => $quizzes,
        ]);
    }

    /**
     * GET /curso/{slug}/licao/{lessonId}
     * Player de video da licao
     */
    public function player($slug, $lessonId)
    {
        // Verificar autenticacao
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $courseModel = new Course();
        $course = $courseModel->findBySlug($slug);

        if (!$course) {
            http_response_code(404);
            echo "Curso nao encontrado";
            return;
        }

        // Buscar enrollment
        $enrollment = $this->getEnrollmentForUser($_SESSION['user_id'], $course['id']);
        if (!$enrollment) {
            // Auto-matricular se o curso for gratuito
            if ($course['is_free']) {
                $enrollment = $this->autoEnroll($_SESSION['user_id'], $course['id']);
            } else {
                header('Location: /curso/' . $slug);
                exit;
            }
        }

        // Buscar licao
        $lessonModel = new Lesson();
        $lesson = $lessonModel->find($lessonId);

        if (!$lesson) {
            http_response_code(404);
            echo "Licao nao encontrada";
            return;
        }

        // Buscar todas as secoes com licoes para a sidebar
        $sectionModel = new Section();
        $sections = $sectionModel->getByCourse($course['id']);

        $videoProgressModel = new VideoProgress();

        foreach ($sections as &$section) {
            $section['lessons'] = $lessonModel->getBySection($section['id']);
            foreach ($section['lessons'] as &$lessonItem) {
                $progress = $videoProgressModel->getOrCreate($enrollment['id'], $lessonItem['id']);
                $lessonItem['is_completed'] = $progress ? (bool) $progress['is_completed'] : false;
                $lessonItem['percentage_watched'] = $progress ? ($progress['percentage_watched'] ?? 0) : 0;
            }
        }

        // Buscar progresso atual da licao
        $currentProgress = $videoProgressModel->getOrCreate($enrollment['id'], $lessonId, $lesson['video_duration'] ?? 0);

        // Contar total de licoes para progresso
        $totalLessons = 0;
        foreach ($sections as $s) {
            $totalLessons += count($s['lessons']);
        }

        // Atualizar total no enrollment se necessario
        if ($enrollment['total_videos_count'] != $totalLessons) {
            $this->db->prepare("UPDATE enrollments SET total_videos_count = ? WHERE id = ?")
                ->execute([$totalLessons, $enrollment['id']]);
            $enrollment['total_videos_count'] = $totalLessons;
        }

        // Encontrar licao anterior e proxima
        $allLessons = [];
        foreach ($sections as $s) {
            foreach ($s['lessons'] as $l) {
                $allLessons[] = $l;
            }
        }
        $prevLesson = null;
        $nextLesson = null;
        foreach ($allLessons as $i => $l) {
            if ($l['id'] == $lessonId) {
                $prevLesson = $i > 0 ? $allLessons[$i - 1] : null;
                $nextLesson = $i < count($allLessons) - 1 ? $allLessons[$i + 1] : null;
                break;
            }
        }

        $this->render('curso-player', [
            'title' => $lesson['title'] . ' | ' . $course['title'],
            'course' => $course,
            'lesson' => $lesson,
            'sections' => $sections,
            'enrollment' => $enrollment,
            'currentProgress' => $currentProgress,
            'prevLesson' => $prevLesson,
            'nextLesson' => $nextLesson,
            'csrfToken' => $_SESSION['csrf_token'] ?? '',
        ]);
    }

    /**
     * POST /curso/{slug}/matricular
     * Matricular aluno no curso
     */
    public function enroll($slug)
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $courseModel = new Course();
        $course = $courseModel->findBySlug($slug);

        if (!$course) {
            http_response_code(404);
            echo "Curso nao encontrado";
            return;
        }

        // Verificar se ja esta matriculado
        $existing = $this->getEnrollmentForUser($_SESSION['user_id'], $course['id']);
        if ($existing) {
            header('Location: /curso/' . $slug);
            exit;
        }

        // Determinar status baseado no tipo do curso
        if ($course['is_free']) {
            $status = 'active';
            $paymentStatus = 'free';
        } else {
            // Curso pago - matricula pendente ate pagamento
            $status = 'pending';
            $paymentStatus = 'pending';
        }

        $stmt = $this->db->prepare("
            INSERT INTO enrollments (user_id, course_id, status, payment_status)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$_SESSION['user_id'], $course['id'], $status, $paymentStatus]);

        if ($course['is_free']) {
            $_SESSION['success_message'] = 'Matricula realizada com sucesso! Comece a estudar agora.';
        } else {
            $_SESSION['success_message'] = 'Matricula registrada! Aguardando confirmacao de pagamento.';
        }

        header('Location: /curso/' . $slug);
        exit;
    }

    /**
     * Buscar enrollment de um usuario para um curso
     */
    private function getEnrollmentForUser($userId, $courseId)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM enrollments
            WHERE user_id = ? AND course_id = ?
            LIMIT 1
        ");
        $stmt->execute([$userId, $courseId]);
        return $stmt->fetch() ?: null;
    }

    /**
     * Auto-matricular usuario em curso gratuito
     */
    private function autoEnroll($userId, $courseId)
    {
        $stmt = $this->db->prepare("
            INSERT INTO enrollments (user_id, course_id, status, payment_status)
            VALUES (?, ?, 'active', 'free')
        ");
        $stmt->execute([$userId, $courseId]);

        return $this->getEnrollmentForUser($userId, $courseId);
    }

    /**
     * Renderizar view com layout publico
     */
    protected function render($view, $data = [])
    {
        extract($data);
        $viewFile = __DIR__ . "/../../views/pages/{$view}.php";

        ob_start();
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            echo "<div class='container py-5'><h1>Pagina em construcao</h1></div>";
        }
        $content = ob_get_clean();

        include __DIR__ . "/../../views/layouts/public.php";
    }
}
