<?php
/**
 * Definição das perguntas orientadoras por eixo
 * Incluído por create.php e edit.php das observações
 */
$axisQuestions = [
    'general' => [
        'name' => 'Observação Geral',
        'field' => 'observation_general',
        'icon' => 'fas fa-file-alt',
        'tab_id' => 'panel-general',
        'tab_btn' => 'tab-general',
        'questions' => [
            'Que mudanças você observou nesse campo desde a última observação?',
            'Quais atividades, objetos ou brinquedos a criança demonstra maior interesse em explorar?',
            'Quais são suas facilidades e dificuldades?',
            'Como a criança interage com os colegas e professores?',
            'Em que atividades a criança demonstra autonomia? O que faz por conta própria?',
            'Como a criança lida com situações desafiadoras?',
            'Como a criança expressa suas emoções?',
            'Quais são as características mais marcantes no comportamento da criança?',
        ]
    ],
    'movement' => [
        'name' => 'Atividade de Movimento',
        'field' => 'axis_movement',
        'icon' => 'fas fa-running',
        'tab_id' => 'panel-movement',
        'tab_btn' => 'tab-movement',
        'questions' => [
            'Que mudanças você observou nesse campo desde a última observação?',
            'Prudência: Como a criança se movimenta? É cuidadosa?',
            'Persistência: Insiste quando enfrenta dificuldades?',
            'Medo e Coragem: Apresenta medos excessivos ou enfrenta desafios?',
            'Qualidade do Movimento: Os movimentos são equilibrados, precisos, tensos ou relaxados?',
        ]
    ],
    'manual' => [
        'name' => 'Atividade Manual',
        'field' => 'axis_manual',
        'icon' => 'fas fa-hands',
        'tab_id' => 'panel-manual',
        'tab_btn' => 'tab-manual',
        'questions' => [
            'Que mudanças você observou nesse campo desde a última observação?',
            'Capacidade de Brincar: A criança brinca e se diverte? Brinca sozinha?',
            'Concentração: Concentra-se nos brinquedos e atividades manuais?',
            'Variedade: Explora diferentes tipos de brinquedos e atividades?',
            'Profundidade: Brinca mais tempo com um mesmo brinquedo?',
            'Interatividade: Como a criança interage com os brinquedos e com outras crianças durante as atividades manuais?',
        ]
    ],
    'music' => [
        'name' => 'Atividade Musical',
        'field' => 'axis_music',
        'icon' => 'fas fa-music',
        'tab_id' => 'panel-music',
        'tab_btn' => 'tab-music',
        'questions' => [
            'Que mudanças você observou nesse campo desde a última observação?',
            'Preferências Musicais: Quais são as preferências da criança em relação a tipos sonoros, músicas e instrumentos?',
            'Sincronia: A criança acompanha os movimentos e sons de forma sincronizada?',
            'Canto: A criança canta ou cantarola sozinha?',
            'Concentração: Como é a concentração da criança durante atividades musicais?',
            'Reações: Quais são as reações da criança a diferentes sons e músicas?',
        ]
    ],
    'stories' => [
        'name' => 'Atividade de Contos',
        'field' => 'axis_stories',
        'icon' => 'fas fa-book-open',
        'tab_id' => 'panel-stories',
        'tab_btn' => 'tab-stories',
        'questions' => [
            'Que mudanças você observou nesse campo desde a última observação?',
            'Reações Corporais e Faciais: Como a criança reage aos contos?',
            'Expressões de Emoções: Como a criança expressa emoções durante os contos?',
            'Preferências: Quais são as preferências da criança em relação a sons, rimas e momentos dos contos?',
            'Imitação: A criança imita gestos e palavras dos contos?',
        ]
    ],
    'pca' => [
        'name' => 'Programa Comunicação Ativa (PCA)',
        'field' => 'axis_pca',
        'icon' => 'fas fa-comments',
        'tab_id' => 'panel-pca',
        'tab_btn' => 'tab-pca',
        'questions' => [
            'Que mudanças você observou nesse campo desde a última observação?',
            'Capacidade de compreender palavras: Entende os significados das palavras?',
            'Capacidade de expressar palavras: Expressa palavras com sentido correto?',
            'Usa palavras trabalhadas no seu dia a dia?',
            'Consegue expressar em palavras o que está sentindo ou pensando?',
            'Entende o sentido das histórias conversadas?',
        ]
    ],
];

/**
 * Parsear respostas: suporta JSON novo ou texto legado
 */
function parseAxisAnswers($value, $questionCount) {
    if (empty($value)) return array_fill(0, $questionCount, '');
    $decoded = json_decode($value, true);
    if (is_array($decoded)) {
        // Garantir que temos todos os índices
        $answers = [];
        for ($i = 0; $i < $questionCount; $i++) {
            $answers[$i] = $decoded[$i] ?? '';
        }
        return $answers;
    }
    // Legado: plain text vai na primeira resposta
    $answers = array_fill(0, $questionCount, '');
    $answers[0] = $value;
    return $answers;
}
