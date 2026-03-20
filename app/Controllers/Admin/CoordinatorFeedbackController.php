<?php
namespace App\Controllers\Admin;

use App\Models\CoordinatorComment;
use App\Models\Notification;
use App\Core\Security\Csrf;

class CoordinatorFeedbackController
{
    public function store()
    {
        Csrf::verify();
        $userRole = $_SESSION['user_role'] ?? '';
        $userId   = (int) ($_SESSION['user_id'] ?? 0);

        // Only coordinators and admins can post feedback
        if (!in_array($userRole, ['coordenador', 'admin'])) {
            $_SESSION['error_message'] = 'Sem permissao para adicionar feedback.';
            header('Location: /admin/dashboard');
            exit;
        }

        $contentType = $_POST['content_type'] ?? '';
        $contentId   = (int) ($_POST['content_id'] ?? 0);
        $comment     = trim($_POST['comment'] ?? '');
        $rawUrl      = $_POST['return_url'] ?? '/admin/dashboard';
        $returnUrl   = Csrf::safeRedirectUrl($rawUrl, '/admin/dashboard');

        $allowedTypes = ['observation', 'descriptive_report', 'portfolio', 'planning'];
        if (!in_array($contentType, $allowedTypes) || $contentId <= 0 || empty($comment)) {
            $_SESSION['error_message'] = 'Dados invalidos para o feedback.';
            header('Location: ' . $returnUrl);
            exit;
        }

        $commentModel = new CoordinatorComment();
        $newId = $commentModel->create([
            'coordinator_id' => $userId,
            'content_type'   => $contentType,
            'content_id'     => $contentId,
            'comment'        => $comment,
        ]);

        if ($newId) {
            // Send notification to teacher
            $teacherId = $this->getContentOwnerId($contentType, $contentId);
            if ($teacherId && $teacherId !== $userId) {
                $this->notifyTeacher($teacherId, $contentType, $contentId, $userId);
            }
            $_SESSION['success_message'] = 'Feedback registrado com sucesso!';
        } else {
            $_SESSION['error_message'] = 'Erro ao registrar feedback.';
        }

        header('Location: ' . $returnUrl);
        exit;
    }

    private function getContentOwnerId(string $type, int $id): ?int
    {
        try {
            $db = \App\Core\Database\Connection::getInstance();

            switch ($type) {
                case 'observation':
                    $stmt = $db->prepare("SELECT user_id FROM observations WHERE id = ? LIMIT 1");
                    $stmt->execute([$id]);
                    $row = $stmt->fetch(\PDO::FETCH_ASSOC);
                    return $row ? (int) $row['user_id'] : null;

                case 'descriptive_report':
                    // descriptive_reports has no direct user_id; get teacher via linked observation
                    $stmt = $db->prepare(
                        "SELECT o.user_id
                         FROM descriptive_reports dr
                         LEFT JOIN observations o ON dr.observation_id = o.id
                         WHERE dr.id = ? LIMIT 1"
                    );
                    $stmt->execute([$id]);
                    $row = $stmt->fetch(\PDO::FETCH_ASSOC);
                    return ($row && $row['user_id']) ? (int) $row['user_id'] : null;

                case 'portfolio':
                    // portfolios are linked to classrooms which have a teacher_id
                    $stmt = $db->prepare(
                        "SELECT c.teacher_id
                         FROM portfolios p
                         JOIN classrooms c ON p.classroom_id = c.id
                         WHERE p.id = ? LIMIT 1"
                    );
                    $stmt->execute([$id]);
                    $row = $stmt->fetch(\PDO::FETCH_ASSOC);
                    return ($row && $row['teacher_id']) ? (int) $row['teacher_id'] : null;

                case 'planning':
                    $stmt = $db->prepare("SELECT user_id FROM planning_submissions WHERE id = ? LIMIT 1");
                    $stmt->execute([$id]);
                    $row = $stmt->fetch(\PDO::FETCH_ASSOC);
                    return $row ? (int) $row['user_id'] : null;

                default:
                    return null;
            }
        } catch (\PDOException $e) {
            error_log("getContentOwnerId: " . $e->getMessage());
            return null;
        }
    }

    private function notifyTeacher(int $teacherId, string $type, int $contentId, int $coordinatorId): void
    {
        $typeLabels = [
            'observation'        => 'observacao',
            'descriptive_report' => 'parecer descritivo',
            'portfolio'          => 'portfolio',
            'planning'           => 'planejamento',
        ];
        $label = $typeLabels[$type] ?? $type;

        $coordinatorName = $_SESSION['user_name'] ?? 'Coordenador';

        $notif = new Notification();
        $notif->create([
            'user_id'        => $teacherId,
            'type'           => 'coordinator_feedback',
            'title'          => "Novo feedback da coordenacao na sua {$label}",
            'message'        => "{$coordinatorName} adicionou um comentario na sua {$label}.",
            'reference_type' => $type,
            'reference_id'   => $contentId,
        ]);
    }
}
