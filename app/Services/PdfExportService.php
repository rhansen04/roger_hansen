<?php

namespace App\Services;

use Mpdf\Mpdf;

class PdfExportService
{
    private function createMpdf($config = []): Mpdf
    {
        $defaultConfig = [
            'mode' => 'utf-8',
            'format' => 'A4',
            'margin_left' => 15,
            'margin_right' => 15,
            'margin_top' => 15,
            'margin_bottom' => 15,
            'tempDir' => sys_get_temp_dir() . '/mpdf',
        ];
        return new Mpdf(array_merge($defaultConfig, $config));
    }

    /**
     * Resolve image URL to absolute file path for mPDF
     */
    private function resolveImagePath(string $url): string
    {
        if (empty($url)) {
            return '';
        }
        // If already absolute path or external URL, return as-is
        if (strpos($url, 'http://') === 0 || strpos($url, 'https://') === 0) {
            return $url;
        }
        // Resolve relative URL to filesystem path
        $basePath = __DIR__ . '/../../public';
        $fullPath = $basePath . $url;
        if (file_exists($fullPath)) {
            return realpath($fullPath);
        }
        return '';
    }

    /**
     * Build an image tag, returning empty string if image doesn't exist
     */
    private function buildImageTag(string $url, string $style = '', string $alt = ''): string
    {
        $path = $this->resolveImagePath($url);
        if (empty($path)) {
            return '';
        }
        $escapedPath = htmlspecialchars($path);
        $escapedAlt = htmlspecialchars($alt);
        return "<img src=\"{$escapedPath}\" style=\"{$style}\" alt=\"{$escapedAlt}\" />";
    }

    /**
     * Common CSS styles for PDF documents
     */
    private function getBaseStyles(): string
    {
        return '
        <style>
            body {
                font-family: DejaVu Sans, sans-serif;
                font-size: 12pt;
                line-height: 1.6;
                color: #333;
            }
            .cover-page {
                background-color: #007e66;
                color: #ffffff;
                text-align: center;
                padding: 60px 30px;
                height: 100%;
            }
            .cover-title {
                font-size: 28pt;
                font-weight: bold;
                margin-top: 40px;
                margin-bottom: 10px;
                letter-spacing: 2px;
            }
            .cover-subtitle {
                font-size: 14pt;
                margin-bottom: 30px;
                opacity: 0.9;
            }
            .cover-student {
                font-size: 18pt;
                font-weight: bold;
                margin-top: 20px;
            }
            .cover-info {
                font-size: 12pt;
                margin-top: 10px;
                opacity: 0.9;
            }
            .cover-photo {
                border-radius: 50%;
                border: 5px solid rgba(255,255,255,0.5);
                max-width: 200px;
                max-height: 200px;
            }
            .page-title {
                font-size: 18pt;
                font-weight: bold;
                color: #007e66;
                margin-bottom: 20px;
                padding-bottom: 10px;
                border-bottom: 2px solid #007e66;
            }
            .content-text {
                font-size: 11pt;
                line-height: 1.8;
                text-align: justify;
            }
            .axis-title {
                font-size: 16pt;
                font-weight: bold;
                color: #007e66;
                margin-bottom: 15px;
                padding: 10px 15px;
                background-color: #f0f9f6;
                border-left: 4px solid #007e66;
            }
            .photo-grid {
                text-align: center;
            }
            .photo-item {
                display: inline-block;
                margin: 8px;
                text-align: center;
            }
            .photo-item img {
                max-width: 240px;
                max-height: 180px;
                border-radius: 8px;
                border: 1px solid #ddd;
            }
            .photo-caption {
                font-size: 9pt;
                color: #666;
                font-style: italic;
                margin-top: 5px;
                max-width: 240px;
            }
            .principle-item {
                margin-bottom: 12px;
                padding: 10px 15px;
                background-color: #f8f9fa;
                border-left: 3px solid #007e66;
            }
            .principle-number {
                font-weight: bold;
                color: #007e66;
                margin-right: 8px;
            }
            .page-break {
                page-break-before: always;
            }
        </style>';
    }

    /**
     * Generate Parecer Descritivo PDF
     */
    public function generateDescriptiveReportPdf($report, $student, $classroom): string
    {
        $mpdf = $this->createMpdf([
            'margin_top' => 0,
            'margin_bottom' => 0,
            'margin_left' => 0,
            'margin_right' => 0,
        ]);

        $styles = $this->getBaseStyles();
        $studentName = htmlspecialchars($report['student_name'] ?? $student['name'] ?? 'Aluno');
        $classroomName = htmlspecialchars($report['classroom_name'] ?? ($classroom['name'] ?? ''));
        $semester = $report['semester'] ?? 1;
        $year = $report['year'] ?? date('Y');

        // --- COVER PAGE ---
        $coverPhotoHtml = '';
        if (!empty($report['cover_photo_url'])) {
            $img = $this->buildImageTag($report['cover_photo_url'], 'border-radius:50%;border:5px solid rgba(255,255,255,0.5);max-width:200px;max-height:200px;', 'Foto do aluno');
            if ($img) {
                $coverPhotoHtml = "<div style=\"margin-bottom:20px;\">{$img}</div>";
            }
        }

        $html = $styles . "
        <div class=\"cover-page\">
            <div style=\"padding-top:80px;\">
                <div class=\"cover-title\">PARECER DESCRITIVO</div>
                <div class=\"cover-subtitle\">Registro Pedagogico Individual</div>
                {$coverPhotoHtml}
                <div class=\"cover-student\">{$studentName}</div>
                <div class=\"cover-info\">{$classroomName}</div>
                <div class=\"cover-info\">{$semester}o Semestre - {$year}</div>
            </div>
        </div>";

        $mpdf->WriteHTML($html);

        // Reset margins for content pages
        $mpdf->AddPage('', '', '', '', '', 15, 15, 15, 15);

        // --- PAGE 1: Institutional Text ---
        $introText = nl2br(htmlspecialchars($report['intro_text'] ?? ''));
        $mpdf->WriteHTML("
            <div class=\"page-title\">Sobre o Parecer Descritivo</div>
            <div class=\"content-text\">{$introText}</div>
        ");

        // --- PAGE 2: Child Text ---
        $mpdf->AddPage();
        $displayText = !empty($report['student_text_edited']) ? $report['student_text_edited'] : ($report['student_text'] ?? '');
        $childText = nl2br(htmlspecialchars($displayText));
        $mpdf->WriteHTML("
            <div class=\"page-title\">{$studentName}</div>
            <div class=\"content-text\">{$childText}</div>
        ");

        // --- PAGES 3-7: Axis Photos ---
        $axisPhotos = !empty($report['axis_photos']) ? json_decode($report['axis_photos'], true) : [];
        $axisNames = [
            'movement' => 'Atividades de Movimento',
            'manual' => 'Atividades Manuais',
            'music' => 'Atividades Musicais',
            'stories' => 'Atividades de Contos',
            'pca' => 'Programa Comunicacao Ativa',
        ];

        foreach ($axisNames as $axisKey => $axisLabel) {
            $photos = $axisPhotos[$axisKey] ?? [];
            $mpdf->AddPage();

            $axisHtml = "<div class=\"axis-title\">{$axisLabel}</div>";

            if (!empty($photos)) {
                $axisHtml .= '<div class="photo-grid">';
                $topRow = array_slice($photos, 0, 2);
                $bottomRow = array_slice($photos, 2, 1);

                // Top row: up to 2 photos side by side
                $axisHtml .= '<table style="width:100%;margin-bottom:15px;"><tr>';
                foreach ($topRow as $photo) {
                    $img = $this->buildImageTag(
                        $photo['url'] ?? '',
                        'max-width:240px;max-height:180px;border-radius:8px;border:1px solid #ddd;',
                        $photo['caption'] ?? ''
                    );
                    if ($img) {
                        $caption = !empty($photo['caption']) ? '<div class="photo-caption">' . htmlspecialchars($photo['caption']) . '</div>' : '';
                        $axisHtml .= "<td style=\"width:50%;text-align:center;vertical-align:top;padding:8px;\">{$img}{$caption}</td>";
                    }
                }
                $axisHtml .= '</tr></table>';

                // Bottom row: 1 photo centered
                if (!empty($bottomRow)) {
                    foreach ($bottomRow as $photo) {
                        $img = $this->buildImageTag(
                            $photo['url'] ?? '',
                            'max-width:240px;max-height:180px;border-radius:8px;border:1px solid #ddd;',
                            $photo['caption'] ?? ''
                        );
                        if ($img) {
                            $caption = !empty($photo['caption']) ? '<div class="photo-caption">' . htmlspecialchars($photo['caption']) . '</div>' : '';
                            $axisHtml .= "<div style=\"text-align:center;padding:8px;\">{$img}{$caption}</div>";
                        }
                    }
                }
                $axisHtml .= '</div>';
            } else {
                $axisHtml .= '<div style="text-align:center;color:#999;padding:60px 0;font-size:14pt;">Nenhuma foto registrada para este eixo</div>';
            }

            $mpdf->WriteHTML($axisHtml);
        }

        return $mpdf->Output('', 'S');
    }

    /**
     * Generate Portfolio PDF
     */
    public function generatePortfolioPdf($portfolio, $classroom): string
    {
        $mpdf = $this->createMpdf([
            'margin_top' => 0,
            'margin_bottom' => 0,
            'margin_left' => 0,
            'margin_right' => 0,
        ]);

        $styles = $this->getBaseStyles();
        $classroomName = htmlspecialchars($portfolio['classroom_name'] ?? ($classroom['name'] ?? ''));
        $semester = $portfolio['semester'] ?? 1;
        $year = $portfolio['year'] ?? date('Y');

        // --- COVER PAGE ---
        $coverPhotoHtml = '';
        if (!empty($portfolio['cover_photo_url'])) {
            $img = $this->buildImageTag($portfolio['cover_photo_url'], 'max-width:300px;max-height:220px;border-radius:12px;border:4px solid rgba(255,255,255,0.5);', 'Foto da turma');
            if ($img) {
                $coverPhotoHtml = "<div style=\"margin-bottom:20px;\">{$img}</div>";
            }
        }

        $html = $styles . "
        <div class=\"cover-page\">
            <div style=\"padding-top:80px;\">
                <div class=\"cover-title\">PORTFOLIO DA TURMA</div>
                <div class=\"cover-subtitle\">{$classroomName}</div>
                {$coverPhotoHtml}
                <div class=\"cover-info\">{$semester}o Semestre - {$year}</div>
            </div>
        </div>";

        $mpdf->WriteHTML($html);

        // --- PAGE 1: Sobre a Magia do Portfolio ---
        $mpdf->AddPage('', '', '', '', '', 15, 15, 15, 15);
        $magiaText = 'O portfolio e um instrumento de documentacao pedagogica que registra, atraves de imagens e reflexoes, as vivencias significativas das criancas ao longo do semestre. Cada pagina deste portfolio conta uma historia de descobertas, aprendizagens e crescimento, revelando o percurso de cada turma dentro dos eixos de atividades da Pedagogia Florenca.';
        $mpdf->WriteHTML("
            <div class=\"page-title\">Sobre a Magia do Portfolio</div>
            <div class=\"content-text\">" . nl2br(htmlspecialchars($magiaText)) . "</div>
        ");

        // --- PAGE 2: Proposta da Pedagogia Florenca ---
        $mpdf->AddPage();
        $principles = [
            'Valorizar a infancia como periodo fundamental',
            'Promover aprendizagens significativas atraves do brincar',
            'Desenvolver multiplas linguagens expressivas',
            'Construir relacoes de afeto e respeito',
            'Documentar o percurso de cada crianca',
        ];
        $principlesHtml = '<div class="page-title">Proposta da Pedagogia Florenca</div>';
        foreach ($principles as $i => $principle) {
            $num = $i + 1;
            $principlesHtml .= "
                <div class=\"principle-item\">
                    <span class=\"principle-number\">{$num}.</span> {$principle}
                </div>";
        }
        $mpdf->WriteHTML($principlesHtml);

        // --- PAGE 3: Teacher Message ---
        $mpdf->AddPage();
        $teacherMessage = $portfolio['teacher_message_corrected'] ?? $portfolio['teacher_message'] ?? '';
        $mpdf->WriteHTML("
            <div class=\"page-title\">Mensagem da Professora</div>
            <div class=\"content-text\">" . nl2br(htmlspecialchars($teacherMessage)) . "</div>
        ");

        // --- PAGE 4: Os Eixos de Atividades ---
        $mpdf->AddPage();
        $eixosIntro = 'A Pedagogia Florenca organiza suas atividades em cinco eixos fundamentais que contemplam o desenvolvimento integral da crianca. Cada eixo valoriza diferentes linguagens e formas de expressao, proporcionando experiencias ricas e significativas que respeitam o ritmo e a individualidade de cada crianca.';
        $mpdf->WriteHTML("
            <div class=\"page-title\">Os Eixos de Atividades</div>
            <div class=\"content-text\">" . nl2br(htmlspecialchars($eixosIntro)) . "</div>
        ");

        // --- PAGES 5-14: Axis pairs (description + photos) ---
        $axisConfig = [
            'movement' => 'Atividades de Movimento',
            'manual' => 'Atividades Manuais',
            'music' => 'Atividades Musicais',
            'stories' => 'Atividades de Contos',
            'pca' => 'Programa Comunicacao Ativa',
        ];

        foreach ($axisConfig as $axisKey => $axisLabel) {
            $descField = "axis_{$axisKey}_description";
            $photosField = "axis_{$axisKey}_photos";

            $description = $portfolio[$descField] ?? '';
            $photos = !empty($portfolio[$photosField])
                ? (is_string($portfolio[$photosField]) ? json_decode($portfolio[$photosField], true) : $portfolio[$photosField])
                : [];

            // Text page
            $mpdf->AddPage();
            $descHtml = "<div class=\"axis-title\">{$axisLabel}</div>";
            if (!empty($description)) {
                $descHtml .= '<div class="content-text">' . nl2br(htmlspecialchars($description)) . '</div>';
            } else {
                $descHtml .= '<div style="text-align:center;color:#999;padding:40px 0;">Descricao nao informada.</div>';
            }
            $mpdf->WriteHTML($descHtml);

            // Photo page
            $mpdf->AddPage();
            $photoHtml = "<div class=\"axis-title\">{$axisLabel} - Registros Fotograficos</div>";

            if (!empty($photos) && is_array($photos)) {
                $photoHtml .= '<div class="photo-grid">';

                // Top row: up to 2 photos
                $topRow = array_slice($photos, 0, 2);
                $bottomRow = array_slice($photos, 2, 1);

                $photoHtml .= '<table style="width:100%;margin-bottom:15px;"><tr>';
                foreach ($topRow as $photo) {
                    $img = $this->buildImageTag(
                        $photo['url'] ?? '',
                        'max-width:240px;max-height:180px;border-radius:8px;border:1px solid #ddd;',
                        $photo['caption'] ?? ''
                    );
                    if ($img) {
                        $caption = !empty($photo['caption']) ? '<div class="photo-caption">' . htmlspecialchars($photo['caption']) . '</div>' : '';
                        $photoHtml .= "<td style=\"width:50%;text-align:center;vertical-align:top;padding:8px;\">{$img}{$caption}</td>";
                    }
                }
                $photoHtml .= '</tr></table>';

                // Bottom row: 1 photo centered
                if (!empty($bottomRow)) {
                    foreach ($bottomRow as $photo) {
                        $img = $this->buildImageTag(
                            $photo['url'] ?? '',
                            'max-width:240px;max-height:180px;border-radius:8px;border:1px solid #ddd;',
                            $photo['caption'] ?? ''
                        );
                        if ($img) {
                            $caption = !empty($photo['caption']) ? '<div class="photo-caption">' . htmlspecialchars($photo['caption']) . '</div>' : '';
                            $photoHtml .= "<div style=\"text-align:center;padding:8px;\">{$img}{$caption}</div>";
                        }
                    }
                }
                $photoHtml .= '</div>';
            } else {
                $photoHtml .= '<div style="text-align:center;color:#999;padding:60px 0;font-size:14pt;">Nenhuma foto registrada para este eixo</div>';
            }

            $mpdf->WriteHTML($photoHtml);
        }

        return $mpdf->Output('', 'S');
    }
}
