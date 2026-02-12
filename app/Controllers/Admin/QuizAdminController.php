<?php

namespace App\Controllers\Admin;

use App\Models\Quiz;
use App\Models\Section;
use App\Models\Course;
use App\Core\Database\Connection;

class QuizAdminController
{
    protected $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    /**
     * GET /admin/courses/{courseId}/quizzes
     * Listar quizzes de um curso
     */
    public function index($courseId)
    {
        $courseModel = new Course();
        $course = $courseModel->find($courseId);
        if (!$course) {
            $_SESSION['error_message'] = 'Curso nao encontrado.';
            header('Location: /admin/courses');
            exit;
        }

        $stmt = $this->db->prepare("
            SELECT q.*, s.title as section_title,
                   COUNT(qq.id) as questions_count
            FROM quizzes q
            JOIN sections s ON q.section_id = s.id
            LEFT JOIN quiz_questions qq ON q.id = qq.quiz_id
            WHERE s.course_id = ?
            GROUP BY q.id
            ORDER BY s.sort_order, q.sort_order
        ");
        $stmt->execute([$courseId]);
        $quizzes = $stmt->fetchAll();

        $sectionModel = new Section();
        $sections = $sectionModel->getByCourse($courseId);

        return $this->render('quizzes/index', [
            'course' => $course,
            'quizzes' => $quizzes,
            'sections' => $sections,
        ]);
    }

    /**
     * POST /admin/courses/{courseId}/quizzes
     * Criar quiz
     */
    public function store($courseId)
    {
        $quizModel = new Quiz();
        $data = [
            ':section_id' => $_POST['section_id'] ?? 0,
            ':lesson_id' => !empty($_POST['lesson_id']) ? $_POST['lesson_id'] : null,
            ':title' => $_POST['title'] ?? '',
            ':description' => $_POST['description'] ?? '',
            ':passing_score' => $_POST['passing_score'] ?? 70,
            ':time_limit_minutes' => !empty($_POST['time_limit_minutes']) ? $_POST['time_limit_minutes'] : null,
            ':attempts_allowed' => $_POST['attempts_allowed'] ?? 3,
            ':sort_order' => $_POST['sort_order'] ?? 0,
        ];

        if ($quizModel->create($data)) {
            $_SESSION['success_message'] = 'Quiz criado com sucesso!';
        } else {
            $_SESSION['error_message'] = 'Erro ao criar quiz.';
        }

        header('Location: /admin/courses/' . $courseId . '/quizzes');
        exit;
    }

    /**
     * GET /admin/quizzes/{id}/edit
     * Editar quiz com questoes e respostas
     */
    public function edit($id)
    {
        $quizModel = new Quiz();
        $quiz = $quizModel->find($id);
        if (!$quiz) {
            $_SESSION['error_message'] = 'Quiz nao encontrado.';
            header('Location: /admin/courses');
            exit;
        }

        // Buscar secao e curso
        $sectionModel = new Section();
        $section = $sectionModel->find($quiz['section_id']);
        $courseModel = new Course();
        $course = $courseModel->find($section['course_id']);

        // Buscar questoes com respostas
        $questions = $quizModel->getQuestions($id);
        foreach ($questions as &$q) {
            $stmt = $this->db->prepare("SELECT * FROM quiz_answers WHERE question_id = ? ORDER BY sort_order");
            $stmt->execute([$q['id']]);
            $q['answers'] = $stmt->fetchAll();
        }

        return $this->render('quizzes/edit', [
            'quiz' => $quiz,
            'course' => $course,
            'section' => $section,
            'questions' => $questions,
        ]);
    }

    /**
     * POST /admin/quizzes/{id}/update
     */
    public function update($id)
    {
        $quizModel = new Quiz();
        $quiz = $quizModel->find($id);
        if (!$quiz) {
            $_SESSION['error_message'] = 'Quiz nao encontrado.';
            header('Location: /admin/courses');
            exit;
        }

        $data = [
            ':section_id' => $quiz['section_id'],
            ':lesson_id' => !empty($_POST['lesson_id']) ? $_POST['lesson_id'] : null,
            ':title' => $_POST['title'] ?? '',
            ':description' => $_POST['description'] ?? '',
            ':passing_score' => $_POST['passing_score'] ?? 70,
            ':time_limit_minutes' => !empty($_POST['time_limit_minutes']) ? $_POST['time_limit_minutes'] : null,
            ':attempts_allowed' => $_POST['attempts_allowed'] ?? 3,
            ':sort_order' => $_POST['sort_order'] ?? 0,
        ];

        if ($quizModel->update($id, $data)) {
            $_SESSION['success_message'] = 'Quiz atualizado!';
        } else {
            $_SESSION['error_message'] = 'Erro ao atualizar quiz.';
        }

        header('Location: /admin/quizzes/' . $id . '/edit');
        exit;
    }

    /**
     * POST /admin/quizzes/{id}/delete
     */
    public function delete($id)
    {
        $quizModel = new Quiz();
        $quiz = $quizModel->find($id);
        if (!$quiz) {
            $_SESSION['error_message'] = 'Quiz nao encontrado.';
            header('Location: /admin/courses');
            exit;
        }

        $sectionModel = new Section();
        $section = $sectionModel->find($quiz['section_id']);
        $courseId = $section['course_id'];

        if ($quizModel->delete($id)) {
            $_SESSION['success_message'] = 'Quiz deletado!';
        } else {
            $_SESSION['error_message'] = 'Erro ao deletar quiz.';
        }

        header('Location: /admin/courses/' . $courseId . '/quizzes');
        exit;
    }

    /**
     * POST /admin/quizzes/{quizId}/questions
     * Adicionar questao
     */
    public function addQuestion($quizId)
    {
        $stmt = $this->db->prepare("
            INSERT INTO quiz_questions (quiz_id, question_text, question_type, sort_order, points)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $quizId,
            $_POST['question_text'] ?? '',
            $_POST['question_type'] ?? 'multiple_choice',
            $_POST['sort_order'] ?? 0,
            $_POST['points'] ?? 1,
        ]);

        $questionId = $this->db->lastInsertId();

        // Adicionar respostas se enviadas
        if (!empty($_POST['answers']) && is_array($_POST['answers'])) {
            $correctAnswer = $_POST['correct_answer'] ?? 0;
            foreach ($_POST['answers'] as $i => $answerText) {
                if (empty(trim($answerText))) continue;
                $stmt = $this->db->prepare("
                    INSERT INTO quiz_answers (question_id, answer_text, is_correct, sort_order)
                    VALUES (?, ?, ?, ?)
                ");
                $stmt->execute([$questionId, $answerText, ($i == $correctAnswer) ? 1 : 0, $i]);
            }
        }

        $_SESSION['success_message'] = 'Questao adicionada!';
        header('Location: /admin/quizzes/' . $quizId . '/edit');
        exit;
    }

    /**
     * POST /admin/questions/{id}/delete
     */
    public function deleteQuestion($id)
    {
        $stmt = $this->db->prepare("SELECT quiz_id FROM quiz_questions WHERE id = ?");
        $stmt->execute([$id]);
        $question = $stmt->fetch();

        if ($question) {
            $this->db->prepare("DELETE FROM quiz_questions WHERE id = ?")->execute([$id]);
            $_SESSION['success_message'] = 'Questao removida!';
            header('Location: /admin/quizzes/' . $question['quiz_id'] . '/edit');
        } else {
            header('Location: /admin/courses');
        }
        exit;
    }

    protected function render($view, $data = [])
    {
        extract($data);
        $viewFile = __DIR__ . "/../../../views/admin/{$view}.php";
        ob_start();
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            echo "<h2>Pagina {$view} em construcao</h2>";
        }
        $content = ob_get_clean();
        include __DIR__ . "/../../../views/layouts/admin.php";
    }
}
