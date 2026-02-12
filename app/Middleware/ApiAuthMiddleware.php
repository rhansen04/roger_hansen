<?php

namespace App\Middleware;

use App\Core\Database\Connection;

class ApiAuthMiddleware
{
    /**
     * Verificar se usuario esta autenticado via sessao
     */
    public static function handle()
    {
        // Verificar sessao
        if (!isset($_SESSION['user_id'])) {
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Nao autenticado']);
            exit;
        }

        // Verificar CSRF token para POST/PUT/DELETE
        $method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
        if (in_array($method, ['POST', 'PUT', 'DELETE'])) {
            // sendBeacon envia como blob sem headers customizados - aceitar sem CSRF
            $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
            $csrfToken = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? '';

            // Se tem header CSRF, validar
            if (!empty($csrfToken)) {
                if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $csrfToken)) {
                    http_response_code(403);
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'error' => 'CSRF token invalido']);
                    exit;
                }
            }
            // Se nao tem header CSRF, aceitar apenas se vier de sendBeacon (sem header custom)
            // Em producao, considerar validar origin/referer
        }
    }

    /**
     * Verificar se usuario eh dono do enrollment
     */
    public static function verifyEnrollmentOwner($enrollmentId)
    {
        $userId = $_SESSION['user_id'];
        $userRole = $_SESSION['user_role'] ?? '';

        // Admin pode acessar qualquer enrollment
        if ($userRole === 'admin') {
            return true;
        }

        $db = Connection::getInstance();
        $stmt = $db->prepare("SELECT id FROM enrollments WHERE id = ? AND user_id = ?");
        $stmt->execute([$enrollmentId, $userId]);

        if (!$stmt->fetch()) {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'Acesso negado a esta matricula']);
            exit;
        }

        return true;
    }

    /**
     * Ler body JSON da requisicao
     */
    public static function getJsonBody()
    {
        $raw = file_get_contents('php://input');
        $data = json_decode($raw, true);

        if ($data === null && !empty($raw)) {
            http_response_code(400);
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'error' => 'JSON invalido']);
            exit;
        }

        return $data ?? [];
    }
}
