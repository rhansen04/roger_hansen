<?php

namespace App\Controllers;

use App\Services\CertificateService;

class CertificateController
{
    /**
     * GET /certificado/gerar/{enrollmentId}
     * Gerar e baixar certificado
     */
    public function generate($enrollmentId)
    {
        if (!isset($_SESSION['user_id'])) {
            header('Location: /login');
            exit;
        }

        $db = \App\Core\Database\Connection::getInstance();

        // Verificar que o enrollment pertence ao usuario logado
        $stmt = $db->prepare("SELECT * FROM enrollments WHERE id = ? AND user_id = ? AND is_course_completed = 1");
        $stmt->execute([$enrollmentId, $_SESSION['user_id']]);
        $enrollment = $stmt->fetch();

        if (!$enrollment) {
            $_SESSION['error_message'] = 'Certificado indisponivel. Conclua o curso primeiro.';
            header('Location: /minha-conta');
            exit;
        }

        $service = new CertificateService();
        $url = $service->generate($enrollmentId);

        if ($url) {
            header('Location: ' . $url);
        } else {
            $_SESSION['error_message'] = 'Erro ao gerar certificado.';
            header('Location: /minha-conta');
        }
        exit;
    }

    /**
     * GET /certificado/{code}
     * Verificar autenticidade do certificado
     */
    public function verify($code)
    {
        $service = new CertificateService();
        $certificate = $service->verify($code);

        extract(['certificate' => $certificate, 'code' => $code]);
        ob_start();
        include __DIR__ . "/../../views/pages/certificado-verificar.php";
        $content = ob_get_clean();
        include __DIR__ . "/../../views/layouts/public.php";
    }
}
