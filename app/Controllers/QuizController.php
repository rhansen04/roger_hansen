<?php

namespace App\Controllers;

use App\Models\Quiz;
use App\Models\Course;
use App\Core\Database\Connection;

class QuizController
{
    protected $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    /**
     * GET /curso/{slug}/quiz/{quizId}
     * Exibir quiz para o aluno
     */
    public function show($slug, $quizId)
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

        // Verificar enrollment
        $stmt = $this->db->prepare("SELECT * FROM enrollments WHERE user_id = ? AND course_id = ? AND status = 'active'");
        $stmt->execute([$_SESSION['user_id'], $course['id']]);
        $enrollment = $stmt->fetch();
        if (!$enrollment) {
            header('Location: /curso/' . $slug);
            exit;
        }

        $quizModel = new Quiz();
        $quiz = $quizModel->find($quizId);
        if (!$quiz) {
            http_response_code(404);
            echo "Quiz nao encontrado";
            return;
        }

        // Verificar tentativas
        $stmt = $this->db->prepare("SELECT COUNT(*) as cnt FROM quiz_attempts WHERE quiz_id = ? AND user_id = ?");
        $stmt->execute([$quizId, $_SESSION['user_id']]);
        $attemptCount = (int)$stmt->fetch()['cnt'];

        if ($quiz['attempts_allowed'] > 0 && $attemptCount >= $quiz['attempts_allowed']) {
            // Buscar melhor resultado
            $stmt = $this->db->prepare("SELECT * FROM quiz_attempts WHERE quiz_id = ? AND user_id = ? ORDER BY score DESC LIMIT 1");
            $stmt->execute([$quizId, $_SESSION['user_id']]);
            $bestAttempt = $stmt->fetch();

            return $this->render('quiz-resultado', [
                'title' => $quiz['title'],
                'course' => $course,
                'quiz' => $quiz,
                'attempt' => $bestAttempt,
                'attemptsUsed' => $attemptCount,
                'noMoreAttempts' => true,
            ]);
        }

        // Buscar questoes com respostas
        $questions = $quizModel->getQuestions($quizId);
        foreach ($questions as &$q) {
            $stmt = $this->db->prepare("SELECT id, answer_text, sort_order FROM quiz_answers WHERE question_id = ? ORDER BY sort_order");
            $stmt->execute([$q['id']]);
            $q['answers'] = $stmt->fetchAll();
        }

        $this->render('quiz-responder', [
            'title' => $quiz['title'],
            'course' => $course,
            'quiz' => $quiz,
            'questions' => $questions,
            'enrollment' => $enrollment,
            'attemptNumber' => $attemptCount + 1,
        ]);
    }

    /**
     * POST /curso/{slug}/quiz/{quizId}/submit
     * Submeter respostas do quiz
     */
    public function submit($slug, $quizId)
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $courseModel = new Course();
        $course = $courseModel->findBySlug($slug);
        if (!$course) {
            header('Location: /cursos');
            exit;
        }

        $stmt = $this->db->prepare("SELECT * FROM enrollments WHERE user_id = ? AND course_id = ? AND status = 'active'");
        $stmt->execute([$_SESSION['user_id'], $course['id']]);
        $enrollment = $stmt->fetch();
        if (!$enrollment) {
            header('Location: /curso/' . $slug);
            exit;
        }

        $quizModel = new Quiz();
        $quiz = $quizModel->find($quizId);
        if (!$quiz) {
            header('Location: /curso/' . $slug);
            exit;
        }

        // Buscar questoes com respostas corretas
        $questions = $quizModel->getQuestions($quizId);
        $totalPoints = 0;
        $earnedPoints = 0;
        $userAnswers = [];

        foreach ($questions as $q) {
            $totalPoints += $q['points'];
            $userAnswer = $_POST['question_' . $q['id']] ?? null;
            $userAnswers[$q['id']] = $userAnswer;

            if ($userAnswer !== null) {
                // Verificar se a resposta selecionada e correta
                $stmt = $this->db->prepare("SELECT is_correct FROM quiz_answers WHERE id = ? AND question_id = ?");
                $stmt->execute([$userAnswer, $q['id']]);
                $answer = $stmt->fetch();
                if ($answer && $answer['is_correct']) {
                    $earnedPoints += $q['points'];
                }
            }
        }

        $score = $totalPoints > 0 ? ($earnedPoints / $totalPoints) * 100 : 0;
        $passed = $score >= $quiz['passing_score'];

        // Salvar tentativa
        $stmt = $this->db->prepare("
            INSERT INTO quiz_attempts (quiz_id, user_id, enrollment_id, score, passed, answers_json, completed_at)
            VALUES (?, ?, ?, ?, ?, ?, NOW())
        ");
        $stmt->execute([
            $quizId,
            $_SESSION['user_id'],
            $enrollment['id'],
            round($score, 2),
            $passed ? 1 : 0,
            json_encode($userAnswers),
        ]);

        $attemptId = $this->db->lastInsertId();

        // Buscar tentativa salva
        $stmt = $this->db->prepare("SELECT * FROM quiz_attempts WHERE id = ?");
        $stmt->execute([$attemptId]);
        $attempt = $stmt->fetch();

        $stmt = $this->db->prepare("SELECT COUNT(*) as cnt FROM quiz_attempts WHERE quiz_id = ? AND user_id = ?");
        $stmt->execute([$quizId, $_SESSION['user_id']]);
        $attemptCount = (int)$stmt->fetch()['cnt'];

        $this->render('quiz-resultado', [
            'title' => 'Resultado - ' . $quiz['title'],
            'course' => $course,
            'quiz' => $quiz,
            'attempt' => $attempt,
            'attemptsUsed' => $attemptCount,
            'noMoreAttempts' => $quiz['attempts_allowed'] > 0 && $attemptCount >= $quiz['attempts_allowed'],
        ]);
    }

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
