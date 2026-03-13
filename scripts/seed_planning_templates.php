<?php
/**
 * Seed: Criar 2 Templates de Planejamento Pedagógico (PFI e PFII)
 *
 * Uso: php scripts/seed_planning_templates.php
 */

require_once __DIR__ . '/../vendor/autoload.php';

use App\Models\PlanningTemplate;

$model = new PlanningTemplate();

// ============================================================
// Helper
// ============================================================
function addFields($model, $sectionId, $fields) {
    $order = 0;
    foreach ($fields as $f) {
        $order++;
        $model->createField([
            'section_id'   => $sectionId,
            'field_type'   => $f['type'] ?? 'text',
            'label'        => $f['label'],
            'description'  => $f['description'] ?? null,
            'options_json' => isset($f['options']) ? json_encode($f['options'], JSON_UNESCAPED_UNICODE) : null,
            'is_required'  => $f['required'] ?? 0,
            'sort_order'   => $order,
        ]);
    }
}

// ============================================================
// Shared section builders
// ============================================================

function buildIdentificacao($model, $templateId, $order) {
    $sid = $model->createSection([
        'template_id' => $templateId, 'title' => 'Identificação',
        'sort_order' => $order, 'section_type' => 'default',
    ]);
    addFields($model, $sid, [
        ['label' => 'Turma', 'type' => 'text', 'required' => 1],
        ['label' => 'Professor(a)', 'type' => 'text', 'required' => 1],
        ['label' => 'Faixa Etária', 'type' => 'text', 'required' => 1],
        ['label' => 'Semana/Data', 'type' => 'text', 'required' => 1],
    ]);
}

function buildEixoVivencia($model, $templateId, $order) {
    $sid = $model->createSection([
        'template_id' => $templateId, 'title' => 'Eixo da Vivência',
        'sort_order' => $order, 'section_type' => 'default',
    ]);
    addFields($model, $sid, [
        [
            'label' => 'Eixo da Vivência',
            'type' => 'radio',
            'required' => 1,
            'options' => ['Manual', 'Musical', 'Movimento', 'Contos', 'PCA'],
        ],
        ['label' => 'Palavra do Dia (PCA)', 'type' => 'text'],
    ]);
}

function buildPropostaVivencia($model, $templateId, $order) {
    $sid = $model->createSection([
        'template_id' => $templateId, 'title' => 'Proposta da Vivência — O que será feito',
        'description' => 'Descrever objetivamente a experiência planejada.',
        'sort_order' => $order, 'section_type' => 'default',
    ]);
    addFields($model, $sid, [
        ['label' => 'Proposta da Vivência', 'type' => 'textarea', 'required' => 1],
    ]);
}

function buildOrganizacaoAmbiente($model, $templateId, $order) {
    $sid = $model->createSection([
        'template_id' => $templateId, 'title' => 'Organização do Ambiente',
        'description' => 'Como o espaço será preparado para favorecer autonomia, exploração e interação.',
        'sort_order' => $order, 'section_type' => 'default',
    ]);
    addFields($model, $sid, [
        ['label' => 'Organização do Ambiente', 'type' => 'textarea'],
    ]);
}

function buildMateriaisNecessarios($model, $templateId, $order) {
    $sid = $model->createSection([
        'template_id' => $templateId, 'title' => 'Materiais Necessários',
        'sort_order' => $order, 'section_type' => 'default',
    ]);
    addFields($model, $sid, [
        ['label' => 'Materiais Necessários', 'type' => 'textarea'],
    ]);
}

function buildSequenciaVivencia($model, $templateId, $order) {
    $sid = $model->createSection([
        'template_id' => $templateId, 'title' => 'Sequência da Vivência',
        'description' => 'Descrever objetivamente cada momento da proposta.',
        'sort_order' => $order, 'section_type' => 'default',
    ]);
    addFields($model, $sid, [
        ['label' => 'Acolhimento / Início', 'type' => 'textarea'],
        ['label' => 'Desenvolvimento (Apresentação da proposta)', 'type' => 'textarea'],
        ['label' => 'Exploração das Crianças', 'type' => 'textarea', 'description' => 'Experiência ativa e participação das crianças.'],
        ['label' => 'Fechamento', 'type' => 'textarea', 'description' => 'Síntese, partilha ou transição.'],
    ]);
}

function buildObservacoesAntecipadas($model, $templateId, $order) {
    $sid = $model->createSection([
        'template_id' => $templateId, 'title' => 'Observações Antecipadas',
        'sort_order' => $order, 'section_type' => 'default',
    ]);
    addFields($model, $sid, [
        ['label' => 'Tempo previsto', 'type' => 'text'],
        ['label' => 'Adaptações possíveis ou necessárias', 'type' => 'textarea'],
    ]);
}

function buildRegistro($model, $templateId, $order, $title) {
    $sid = $model->createSection([
        'template_id' => $templateId, 'title' => $title,
        'sort_order' => $order, 'section_type' => 'default',
        'is_registration' => 1,
    ]);
    addFields($model, $sid, [
        [
            'label' => 'Síntese do Desenvolvimento das Atividades',
            'type' => 'textarea',
            'description' => 'Como as propostas aconteceram ao longo da semana.',
        ],
        [
            'label' => 'Execução do Planejamento',
            'type' => 'radio',
            'options' => ['Sim', 'Parcialmente', 'Não'],
        ],
        ['label' => 'Se necessário, justificar', 'type' => 'textarea'],
        [
            'label' => 'Engajamento das Crianças',
            'type' => 'radio',
            'options' => ['Alto', 'Médio', 'Baixo'],
        ],
        ['label' => 'Comentário breve', 'type' => 'textarea'],
        [
            'label' => 'Ajustes Realizados',
            'type' => 'checklist_group',
            'options' => ['Tempo', 'Espaço', 'Materiais', 'Mediação docente', 'Interesse das crianças'],
        ],
        ['label' => 'Descrição dos ajustes (se necessário)', 'type' => 'textarea'],
        [
            'label' => 'O que as crianças trouxeram de novo para a proposta?',
            'type' => 'textarea',
            'description' => 'Interesses espontâneos, falas marcantes, descobertas ou caminhos inesperados.',
        ],
        [
            'label' => 'Avanços ou Desafios Observados',
            'type' => 'textarea',
            'description' => 'Interação, autonomia, concentração, cooperação ou dificuldades percebidas.',
        ],
        [
            'label' => 'Necessidade de Apoio',
            'type' => 'checklist_group',
            'options' => ['Pedagógico', 'Organizacional', 'Formativo', 'Estrutural'],
        ],
        ['label' => 'Descreva a necessidade de apoio', 'type' => 'textarea'],
    ]);
}

// ============================================================
// Objetivos de Aprendizagem — common groups
// ============================================================

$objManuais = [
    'Manipulação concreta de materiais diversos',
    'Coordenação motora fina',
    'Integração sensorial tátil e perceptiva',
    'Criatividade e expressão pessoal',
    'Exploração de diferentes texturas e materiais',
    'Produção manual com autonomia progressiva',
    'Percepção estética e senso artístico',
    'Planejamento e execução de tarefas práticas',
    'Exploração funcional de objetos e ferramentas simples',
];

$objMusicais = [
    'Reconhecimento e expressão de emoções por meio da música',
    'Percepção auditiva e escuta atenta',
    'Reconhecimento de padrões sonoros',
    'Percepção rítmica corporal e sonora',
    'Percepção de melodia e harmonia',
    'Expressão vocal e corporal musical',
    'Memória auditiva e musical',
    'Apreciação musical e sensibilidade sonora',
];

$objContosPFI = [
    'Escuta atenta de histórias',
    'Ampliação do repertório cultural inicial',
    'Interação afetiva mediada pela narrativa',
    'Desenvolvimento da linguagem receptiva (compreensão oral)',
    'Imaginação simbólica',
    'Reconhecimento e nomeação de emoções básicas',
    'Atenção e memória narrativa',
    'Estímulo cognitivo por meio da narrativa',
];

$objContosPFII = [
    'Linguagem oral e comunicação expressiva',
    'Interpretação de narrativas e símbolos',
    'Imaginação e elaboração simbólica',
    'Reflexão sobre atitudes e consequências',
    'Desenvolvimento de valores e virtudes',
    'Expressão de ideias, sentimentos e opiniões',
    'Ampliação do repertório cultural e literário',
    'Compreensão simbólica de conflitos e resolução de problemas',
];

$objMovimento = [
    'Coordenação motora global',
    'Equilíbrio e organização postural',
    'Consciência corporal',
    'Orientação espacial',
    'Lateralidade e organização motora',
    'Expressão corporal intencional',
    'Regulação emocional por meio do movimento',
    'Atenção e concentração em atividades motoras',
    'Integração entre movimento, atenção e cognição',
];

$objPCA = [
    'Linguagem oral e escuta',
    'Ampliação de vocabulário',
    'Fluência verbal',
    'Organização do pensamento verbal',
    'Coordenação motora',
    'Expressão emocional',
    'Imaginação simbólica',
    'Cooperação e convivência',
    'Autonomia comunicativa',
    'Exploração sensorial',
    'Movimento corporal',
];

// Mediação PFI
$mediacaoPFI = [
    'Nomeação e ampliação da linguagem. Ex.: nomear ações, objetos, emoções e sensações durante a experiência ("macio", "frio", "subiu", "caiu", "feliz"), favorecendo a linguagem oral emergente e a compreensão.',
    'Interação afetiva e comunicação responsiva. Ex.: contato visual, gestos, imitação, acolhimento das iniciativas da criança, responder balbucios, expressões corporais e tentativas comunicativas.',
    'Intervenção no espaço e materiais. Ex.: demonstrar uso de material ou gesto, adaptar o espaço e materiais para incentivar a manipulação de materiais, o engatinhar, o alcançar algo, e as descobertas no próprio ritmo.',
    'Apoios previstos. Ex.: auxílio para descer de implemento ou liberar-se de brinquedo quando necessário, adaptação de materiais, mediação emocional e de conflitos entre crianças, reorganização do espaço para segurança e participação ativa.',
    'Observação ativa e sensível. Ex.: observar interesses, expressões corporais, formas de comunicação, interações e modos de exploração para registrar avanços e necessidades.',
];

// Mediação PFII
$mediacaoPFII = [
    'Ampliação da linguagem e do pensamento. Ex.: propor perguntas abertas, incentivar a narrativa, nomear emoções, ações e conceitos ("como você fez?", "o que aconteceu depois?", "por que você acha isso?"), favorecendo organização do pensamento e expressão verbal.',
    'Interação afetiva e comunicação responsiva. Ex.: escuta ativa das falas das crianças, validação de ideias e sentimentos, incentivo à participação em rodas de conversa e acolhimento das iniciativas individuais e coletivas.',
    'Intervenção no espaço e nos materiais. Ex.: demonstrar possibilidades de uso dos materiais, reorganizar o ambiente para ampliar desafios e descobertas, incentivar experimentação, construção, dramatização e exploração autônoma.',
    'Apoios previstos. Ex.: mediação de conflitos entre pares, apoio à organização emocional, auxílio na resolução de problemas, adaptação de materiais ou estratégias para favorecer participação e inclusão.',
    'Observação ativa e intencional. Ex.: observar hipóteses, estratégias utilizadas, interações sociais, formas de participação e interesses emergentes para registrar avanços, desafios e necessidades pedagógicas.',
];

function buildObjetivos($model, $templateId, $order, $isPFII, $groups) {
    $sid = $model->createSection([
        'template_id' => $templateId, 'title' => 'Objetivos de Aprendizagem',
        'description' => 'O que se espera favorecer a partir da vivência.',
        'sort_order' => $order, 'section_type' => 'default',
    ]);

    $fields = [];

    // Manuais
    $fields[] = ['label' => 'Eixo de Atividades Manuais', 'type' => 'checklist_group', 'options' => $groups['manuais']];
    $fields[] = ['label' => 'Outros — Manuais', 'type' => 'textarea'];

    // Musicais
    $fields[] = ['label' => 'Eixo de Atividades Musicais', 'type' => 'checklist_group', 'options' => $groups['musicais']];
    $fields[] = ['label' => 'Outros — Musicais', 'type' => 'textarea'];

    if ($isPFII) {
        // PFII: 2 Contos groups
        $fields[] = ['label' => 'Eixo de Atividades de Contos — PFI', 'type' => 'checklist_group', 'options' => $groups['contos_pfi']];
        $fields[] = ['label' => 'Outros — Contos PFI', 'type' => 'textarea'];
        $fields[] = ['label' => 'Eixo de Atividades de Contos — PFII', 'type' => 'checklist_group', 'options' => $groups['contos_pfii']];
        $fields[] = ['label' => 'Outros — Contos PFII', 'type' => 'textarea'];
    } else {
        // PFI: 1 Contos group
        $fields[] = ['label' => 'Eixo de Atividades de Contos', 'type' => 'checklist_group', 'options' => $groups['contos_pfi']];
        $fields[] = ['label' => 'Outros — Contos', 'type' => 'textarea'];
    }

    // Movimento
    $fields[] = ['label' => 'Eixo de Atividades de Movimento', 'type' => 'checklist_group', 'options' => $groups['movimento']];
    $fields[] = ['label' => 'Outros — Movimento', 'type' => 'textarea'];

    // PCA
    $fields[] = ['label' => 'Programa Comunicação Ativa', 'type' => 'checklist_group', 'options' => $groups['pca']];
    $fields[] = ['label' => 'Outros — PCA', 'type' => 'textarea'];

    addFields($model, $sid, $fields);
}

function buildMediacao($model, $templateId, $order, $title, $description, $options) {
    $sid = $model->createSection([
        'template_id' => $templateId, 'title' => $title,
        'description' => $description,
        'sort_order' => $order, 'section_type' => 'default',
    ]);
    addFields($model, $sid, [
        ['label' => 'Ações de Mediação', 'type' => 'checklist_group', 'options' => $options],
        ['label' => 'Descrição breve (se necessário)', 'type' => 'textarea'],
    ]);
}

// ============================================================
// CREATE TEMPLATES
// ============================================================

$groups = [
    'manuais'    => $objManuais,
    'musicais'   => $objMusicais,
    'contos_pfi' => $objContosPFI,
    'contos_pfii'=> $objContosPFII,
    'movimento'  => $objMovimento,
    'pca'        => $objPCA,
];

echo "=== Seed: Planning Templates ===\n\n";

// --- Template PFI ---
$tplPFI = $model->create([
    'title'       => 'Planejamento Pedagógico 2026 — Modelo PFI',
    'description' => 'Modelo de planejamento pedagógico para crianças de 0 a 3 anos — Pedagogia Florença.',
    'age_group'   => '0-3',
    'is_active'   => 1,
    'sort_order'  => 1,
]);
echo "Template PFI criado (ID: $tplPFI)\n";

buildIdentificacao($model, $tplPFI, 1);
buildEixoVivencia($model, $tplPFI, 2);
buildPropostaVivencia($model, $tplPFI, 3);
buildObjetivos($model, $tplPFI, 4, false, $groups);
buildOrganizacaoAmbiente($model, $tplPFI, 5);
buildMateriaisNecessarios($model, $tplPFI, 6);
buildSequenciaVivencia($model, $tplPFI, 7);
buildMediacao($model, $tplPFI, 8,
    'Mediação do Professor',
    'Descreva COMO você irá intervir durante a vivência considerando a comunicação não verbal, o vínculo afetivo, a exploração sensorial e o ritmo individual das crianças. Marque pelo menos duas ações previstas e complemente se necessário.',
    $mediacaoPFI
);
buildObservacoesAntecipadas($model, $tplPFI, 9);
buildRegistro($model, $tplPFI, 10, 'Registro — Final da Semana');
echo "  -> 10 seções + campos inseridos.\n\n";

// --- Template PFII ---
$tplPFII = $model->create([
    'title'       => 'Planejamento Pedagógico 2026 — Modelo PFII',
    'description' => 'Modelo de planejamento pedagógico para crianças de 3 a 6 anos — Pedagogia Florença.',
    'age_group'   => '3-6',
    'is_active'   => 1,
    'sort_order'  => 2,
]);
echo "Template PFII criado (ID: $tplPFII)\n";

buildIdentificacao($model, $tplPFII, 1);
buildEixoVivencia($model, $tplPFII, 2);
buildPropostaVivencia($model, $tplPFII, 3);
buildObjetivos($model, $tplPFII, 4, true, $groups);
buildOrganizacaoAmbiente($model, $tplPFII, 5);
buildMateriaisNecessarios($model, $tplPFII, 6);
buildSequenciaVivencia($model, $tplPFII, 7);
buildMediacao($model, $tplPFII, 8,
    'Mediação do Professor — Crianças de 3 a 6 anos',
    'Descreva COMO você irá intervir durante a vivência considerando a ampliação da linguagem, a autonomia progressiva, a interação social, o pensamento simbólico e o desenvolvimento cognitivo das crianças. Marque pelo menos duas ações previstas e complemente se necessário.',
    $mediacaoPFII
);
buildObservacoesAntecipadas($model, $tplPFII, 9);
buildRegistro($model, $tplPFII, 10, 'Registro — Período: _/_/__ a _/_/__');
echo "  -> 10 seções + campos inseridos.\n\n";

echo "=== Seed concluído com sucesso! ===\n";
