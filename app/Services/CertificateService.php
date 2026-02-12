<?php

namespace App\Services;

use App\Core\Database\Connection;

class CertificateService
{
    /**
     * Gerar certificado HTML e retornar como string
     */
    public function generateHtml($enrollment, $user, $course)
    {
        $completionDate = $enrollment['course_completed_at']
            ? date('d/m/Y', strtotime($enrollment['course_completed_at']))
            : date('d/m/Y');

        $certificateCode = 'HC-' . str_pad($enrollment['id'], 6, '0', STR_PAD_LEFT);

        $html = '<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Certificado - ' . htmlspecialchars($course['title']) . '</title>
<style>
    @page { size: landscape; margin: 0; }
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: Georgia, "Times New Roman", serif; background: white; }
    .certificate {
        width: 297mm; height: 210mm;
        padding: 15mm;
        position: relative;
        background: white;
    }
    .border-outer {
        border: 3px solid #007e66;
        padding: 10mm;
        height: 100%;
        position: relative;
    }
    .border-inner {
        border: 1px solid #04574A;
        padding: 15mm 20mm;
        height: 100%;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
    }
    .logo { color: #007e66; font-size: 28pt; font-weight: bold; letter-spacing: 3px; margin-bottom: 5mm; }
    .logo span { color: #ffb606; font-size: 14pt; display: block; letter-spacing: 5px; }
    .title { color: #007e66; font-size: 20pt; margin: 8mm 0 3mm; letter-spacing: 2px; }
    .subtitle { color: #666; font-size: 11pt; margin-bottom: 8mm; }
    .student-name { font-size: 24pt; color: #04574A; font-weight: bold; border-bottom: 2px solid #ffb606; padding-bottom: 3mm; margin-bottom: 5mm; }
    .course-name { font-size: 16pt; color: #007e66; font-weight: bold; margin: 5mm 0; }
    .details { color: #666; font-size: 10pt; margin: 3mm 0; }
    .footer-cert { display: flex; justify-content: space-between; width: 100%; margin-top: 10mm; padding: 0 15mm; }
    .footer-cert .col { text-align: center; }
    .footer-cert .line { border-top: 1px solid #333; width: 60mm; margin: 0 auto 2mm; }
    .footer-cert .label { font-size: 9pt; color: #666; }
    .code { position: absolute; bottom: 5mm; right: 10mm; font-size: 8pt; color: #999; }
    @media print {
        body { -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    }
</style>
</head>
<body>
<div class="certificate">
    <div class="border-outer">
        <div class="border-inner">
            <div class="logo">HANSEN<span>EDUCACIONAL</span></div>
            <div class="title">CERTIFICADO DE CONCLUSÃO</div>
            <div class="subtitle">Certificamos que</div>
            <div class="student-name">' . htmlspecialchars($user['name']) . '</div>
            <div class="subtitle">concluiu com êxito o curso</div>
            <div class="course-name">' . htmlspecialchars($course['title']) . '</div>
            <div class="details">Carga horária: ' . ($course['duration_hours'] ?? 0) . ' horas</div>
            <div class="details">Data de conclusão: ' . $completionDate . '</div>

            <div class="footer-cert">
                <div class="col">
                    <div class="line"></div>
                    <div class="label">Roger Hansen<br>Diretor Educacional</div>
                </div>
                <div class="col">
                    <div class="line"></div>
                    <div class="label">' . htmlspecialchars($user['name']) . '<br>Aluno(a)</div>
                </div>
            </div>
        </div>
        <div class="code">Código: ' . $certificateCode . ' | Verifique em hanseneducacional.com.br/certificado/' . $certificateCode . '</div>
    </div>
</div>
</body>
</html>';

        return $html;
    }

    /**
     * Salvar certificado como arquivo HTML e atualizar enrollment
     */
    public function generate($enrollmentId)
    {
        $db = Connection::getInstance();

        // Buscar enrollment com dados
        $stmt = $db->prepare("
            SELECT e.*, u.name as student_name, u.email as student_email,
                   c.title as course_title, c.duration_hours, c.slug as course_slug
            FROM enrollments e
            JOIN users u ON e.user_id = u.id
            JOIN courses c ON e.course_id = c.id
            WHERE e.id = ? AND e.is_course_completed = 1
        ");
        $stmt->execute([$enrollmentId]);
        $enrollment = $stmt->fetch();

        if (!$enrollment) {
            return false;
        }

        $user = ['name' => $enrollment['student_name'], 'email' => $enrollment['student_email']];
        $course = [
            'title' => $enrollment['course_title'],
            'duration_hours' => $enrollment['duration_hours'],
            'slug' => $enrollment['course_slug'],
        ];

        $html = $this->generateHtml($enrollment, $user, $course);

        // Salvar arquivo
        $filename = 'certificado_' . $enrollmentId . '.html';
        $dir = __DIR__ . '/../../public/uploads/certificates/';
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }
        file_put_contents($dir . $filename, $html);

        $certificateUrl = '/uploads/certificates/' . $filename;

        // Atualizar enrollment
        $stmt = $db->prepare("UPDATE enrollments SET certificate_issued = 1, certificate_url = ? WHERE id = ?");
        $stmt->execute([$certificateUrl, $enrollmentId]);

        return $certificateUrl;
    }

    /**
     * Verificar certificado por codigo
     */
    public function verify($code)
    {
        // Extrair enrollment ID do codigo HC-000123
        if (!preg_match('/^HC-(\d+)$/', $code, $m)) {
            return null;
        }

        $enrollmentId = (int)$m[1];
        $db = Connection::getInstance();

        $stmt = $db->prepare("
            SELECT e.*, u.name as student_name, c.title as course_title, c.duration_hours
            FROM enrollments e
            JOIN users u ON e.user_id = u.id
            JOIN courses c ON e.course_id = c.id
            WHERE e.id = ? AND e.certificate_issued = 1
        ");
        $stmt->execute([$enrollmentId]);
        return $stmt->fetch() ?: null;
    }
}
