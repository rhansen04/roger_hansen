<?php

namespace App\Services;

use App\Models\Student;
use App\Models\Observation;
use Exception;

class GeminiService
{
    private string $apiKey = '74e72fc8856748deb91ca39b5fec1aa1.zE5JZuVlIxmqevvi';
    private string $model = 'glm-4.5-air';
    private string $apiUrl = 'https://api.z.ai/api/coding/paas/v4/chat/completions';

    /**
     * Gera resumo pedagógico do aluno baseado em suas observações
     */
    public function generateStudentSummary(int $studentId): array
    {
        $studentModel = new Student();
        $student = $studentModel->find($studentId);

        if (!$student) {
            throw new Exception('Aluno não encontrado.');
        }

        $studentName = $student['name'];

        $observationModel = new Observation();
        $observations = $observationModel->findByStudent($studentId);

        if (empty($observations)) {
            throw new Exception('Nenhuma observação encontrada para este aluno.');
        }

        $compiledData = $this->compileObservations($observations);

        if (empty(trim($compiledData))) {
            throw new Exception('As observações deste aluno não possuem conteúdo suficiente.');
        }

        $prompt = $this->buildPrompt($studentName, count($observations), $compiledData);

        $apiResponse = $this->callAPI($prompt);

        return [
            'summary' => $apiResponse,
            'total_observations' => count($observations),
            'student_name' => $studentName
        ];
    }

    /**
     * Compila observações em formato estruturado para o prompt
     */
    private function compileObservations(array $observations): string
    {
        $compiled = '';
        $obsCount = 0;

        foreach ($observations as $obs) {
            $obsCount++;

            $observedDate = date('d/m/Y', strtotime($obs['observation_date']));

            $compiled .= "\n--- Observação #{$obsCount} ---\n";
            $compiled .= "Professor(a): {$obs['teacher_name']}\n";
            $compiled .= "Data: {$observedDate}\n";

            $category = $obs['category'] ?? 'Geral';
            $compiled .= "Categoria: {$category}\n";

            $title = trim($obs['title']);
            $compiled .= "Título: {$title}\n";

            $description = trim($obs['description'] ?? '');
            if (!empty($description)) {
                $compiled .= "\nDescrição:\n{$description}\n";
            }
        }

        return $compiled;
    }

    /**
     * Monta o prompt completo
     */
    private function buildPrompt(string $studentName, int $obsCount, string $data): string
    {
        return "Você é um redator pedagógico. Com base EXCLUSIVAMENTE nas observações abaixo sobre o(a) aluno(a) {$studentName}, reescreva as informações em um texto corrido e coeso em português brasileiro.

REGRAS IMPORTANTES:
- Use APENAS as informações presentes nas observações. Não invente, não extrapole, não adicione interpretações
- Corrija erros de português, gramática e ortografia do texto original
- Escreva em terceira pessoa, de forma profissional e acolhedora
- O texto deve ter tamanho proporcional à quantidade de observações (seja breve se houver poucas)
- Organize cronologicamente ou por categoria quando fizer sentido
- NÃO adicione recomendações, conclusões ou análises que não estejam nos dados
- Máximo de 1 a 3 parágrafos curtos

Total de observações: {$obsCount}

Observações:
{$data}";
    }

    /**
     * Corrige texto do parecer descritivo via IA
     * Corrige ortografia/gramatica sem alterar conteudo pedagogico
     */
    public function correctDescriptiveText(string $text, string $studentName): string
    {
        $prompt = "Voce e um revisor de textos pedagogicos. Corrija APENAS erros de ortografia, gramatica e concordancia no texto abaixo, que faz parte do parecer descritivo do(a) aluno(a) {$studentName}.

REGRAS IMPORTANTES:
- NAO altere o conteudo, significado ou informacoes pedagogicas
- NAO adicione novas informacoes ou interpretacoes
- NAO remova nenhum trecho do texto original
- Mantenha o tom profissional e acolhedor
- Corrija acentuacao, pontuacao, concordancia verbal e nominal
- Retorne APENAS o texto corrigido, sem explicacoes ou comentarios adicionais

Texto para correcao:
{$text}";

        return $this->callAPI($prompt);
    }

    /**
     * Chama a API GLM (Z.AI)
     */
    private function callAPI(string $prompt): string
    {
        $body = [
            'model' => $this->model,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $prompt
                ]
            ],
            'temperature' => 0.7,
            'max_tokens' => 2048,
            'stream' => false
        ];

        $jsonBody = json_encode($body);

        $ch = curl_init($this->apiUrl);

        if ($ch === false) {
            throw new Exception('Erro ao inicializar conexão com a API.');
        }

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $jsonBody,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/json',
                'Authorization: Bearer ' . $this->apiKey
            ],
            CURLOPT_TIMEOUT => 60,
            CURLOPT_CONNECTTIMEOUT => 10,
            CURLOPT_SSL_VERIFYPEER => true
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);

        curl_close($ch);

        if ($response === false) {
            throw new Exception('Erro ao conectar com a API: ' . $curlError);
        }

        $responseData = json_decode($response, true);

        if ($responseData === null) {
            throw new Exception('Erro ao decodificar resposta da API.');
        }

        if ($httpCode !== 200) {
            $errorMsg = $responseData['error']['message'] ?? ($responseData['message'] ?? 'Erro desconhecido da API');
            throw new Exception("Erro da API GLM (HTTP {$httpCode}): {$errorMsg}");
        }

        $text = $responseData['choices'][0]['message']['content'] ?? '';

        if (empty($text)) {
            throw new Exception('A API não retornou texto. Tente novamente.');
        }

        return trim($text);
    }

    /**
     * Corrige texto do portfolio (ortografia e gramática)
     */
    public function correctPortfolioText(string $text): string
    {
        $prompt = "Você é um revisor de textos em português brasileiro. Corrija APENAS erros de ortografia, gramática, pontuação e concordância no texto abaixo. Mantenha o estilo, tom e conteúdo original. Não adicione nem remova informações. Retorne APENAS o texto corrigido, sem explicações.\n\nTexto:\n{$text}";

        return $this->callAPI($prompt);
    }
}
