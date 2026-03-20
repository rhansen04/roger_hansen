<?php

namespace App\Core\Security;

class Csrf
{
    /**
     * Valida o token CSRF do POST.
     * Redireciona com erro 403 se inválido.
     */
    public static function verify(): void
    {
        $submitted = $_POST['csrf_token'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '');
        $expected  = $_SESSION['csrf_token'] ?? '';

        if (empty($expected) || !hash_equals($expected, $submitted)) {
            http_response_code(403);
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH'])) {
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Token CSRF inválido.']);
            } else {
                $_SESSION['error_message'] = 'Requisição inválida. Recarregue a página e tente novamente.';
                $referer = $_SERVER['HTTP_REFERER'] ?? '/admin/dashboard';
                header('Location: ' . $referer);
            }
            exit;
        }
    }

    /**
     * Valida sem redirecionar — retorna bool.
     * Útil para endpoints AJAX que gerenciam sua própria resposta.
     */
    public static function check(): bool
    {
        $submitted = $_POST['csrf_token'] ?? ($_SERVER['HTTP_X_CSRF_TOKEN'] ?? '');
        $expected  = $_SESSION['csrf_token'] ?? '';
        return !empty($expected) && hash_equals($expected, $submitted);
    }

    /**
     * Retorna o token atual (para injetar nas views).
     */
    public static function token(): string
    {
        return $_SESSION['csrf_token'] ?? '';
    }

    /**
     * Valida e sanitiza uma URL de retorno para evitar open redirect.
     * Aceita apenas paths internos (começam com /).
     */
    public static function safeRedirectUrl(string $url, string $fallback = '/admin/dashboard'): string
    {
        $url = trim($url);
        // Só aceita paths relativos internos
        if (empty($url) || !preg_match('#^/[a-zA-Z0-9/_\-?=&#%\.]*$#', $url)) {
            return $fallback;
        }
        return $url;
    }
}
