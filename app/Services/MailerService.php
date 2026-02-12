<?php

namespace App\Services;

class MailerService
{
    private $fromEmail;
    private $fromName;

    public function __construct()
    {
        $this->fromEmail = getenv('MAIL_FROM_ADDRESS') ?: 'noreply@hanseneducacional.com.br';
        $this->fromName = getenv('MAIL_FROM_NAME') ?: 'Hansen Educacional';
    }

    public function send($to, $subject, $htmlBody)
    {
        $headers = [
            'MIME-Version: 1.0',
            'Content-type: text/html; charset=UTF-8',
            'From: ' . $this->fromName . ' <' . $this->fromEmail . '>',
            'Reply-To: ' . $this->fromEmail,
            'X-Mailer: PHP/' . phpversion(),
        ];

        return mail($to, $subject, $htmlBody, implode("\r\n", $headers));
    }

    public function sendPasswordReset($email, $token)
    {
        $baseUrl = getenv('APP_URL') ?: 'http://hansen.local';
        $resetUrl = $baseUrl . '/redefinir-senha/' . $token;

        $html = '
        <div style="font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto;">
            <div style="background-color: #007e66; padding: 20px; text-align: center;">
                <h1 style="color: white; margin: 0;">HANSEN <span style="color: #ffb606;">EDUCACIONAL</span></h1>
            </div>
            <div style="padding: 30px; background-color: #f8f9fa;">
                <h2 style="color: #333;">Recuperação de Senha</h2>
                <p>Você solicitou a redefinição de senha. Clique no botão abaixo para criar uma nova senha:</p>
                <p style="text-align: center; margin: 30px 0;">
                    <a href="' . htmlspecialchars($resetUrl) . '" style="background-color: #007e66; color: white; padding: 12px 30px; text-decoration: none; border-radius: 5px; font-weight: bold;">Redefinir Senha</a>
                </p>
                <p style="color: #666; font-size: 14px;">Se você não solicitou esta alteração, ignore este e-mail.</p>
                <p style="color: #666; font-size: 14px;">Este link expira em 1 hora.</p>
            </div>
        </div>';

        return $this->send($email, 'Recuperação de Senha - Hansen Educacional', $html);
    }
}
