<?php

namespace App\Controllers\Admin;

class HelpController
{
    private array $categories;
    private array $faq;

    public function __construct()
    {
        $this->categories = [
            'primeiros-passos' => [
                'title' => 'Primeiros Passos',
                'description' => 'Aprenda o básico da plataforma: dashboard por papel, menu lateral, modo escuro, tours interativos e configurações iniciais.',
                'icon' => 'fas fa-rocket',
                'color' => '#007e66',
                'articles' => [
                    'visao-geral' => [
                        'title' => 'Visão Geral da Plataforma',
                        'summary' => 'Conheça todos os módulos da plataforma: Dashboard, Turmas, Alunos, Observações, Pareceres, Portfólios, Banco de Imagens, Planejamento, Material de Apoio, Cursos, Relatórios e Notificações.',
                        'time' => 7,
                        'release' => '2026-03-01',
                    ],
                    'primeiro-acesso' => [
                        'title' => 'Primeiro Acesso e Configuração',
                        'summary' => 'Checklist completo do primeiro login: entenda seu papel (Admin, Professor, Coordenador), configure a escola, cadastre turmas e alunos.',
                        'time' => 6,
                        'release' => '2026-03-01',
                    ],
                    'navegacao' => [
                        'title' => 'Navegação, Modo Escuro e Tours',
                        'summary' => 'Menu lateral por papel, modo escuro persistente, tours interativos por página, sino de notificações e Central de Ajuda.',
                        'time' => 5,
                        'release' => '2026-03-01',
                    ],
                    'papeis-permissoes' => [
                        'title' => 'Papéis e Permissões',
                        'summary' => 'Entenda o que cada papel (Admin, Professor, Coordenador) pode fazer em cada módulo: criação, edição, visualização, finalização e aprovação.',
                        'time' => 6,
                        'release' => '2026-03-01',
                    ],
                    'simulador-perfil' => [
                        'title' => 'Simulador de Perfil (Admin)',
                        'summary' => 'Admins podem simular a visão de Professor ou Coordenador sem trocar de conta. Dropdown na barra superior, banner amarelo durante simulação, sem afetar operações de escrita.',
                        'time' => 4,
                        'release' => '2026-03-16',
                    ],
                ],
            ],
            'dashboard' => [
                'title' => 'Dashboard',
                'description' => 'O Dashboard se adapta ao seu papel: Professor vê suas turmas e alunos, Coordenador vê métricas gerais, Admin tem visão completa.',
                'icon' => 'fas fa-tachometer-alt',
                'color' => '#0d6efd',
                'articles' => [
                    'dashboard-professor' => [
                        'title' => 'Dashboard do Professor',
                        'summary' => 'Cards de turmas ativas, alunos sob responsabilidade, cursos em andamento com progresso, observações recentes e ações rápidas.',
                        'time' => 4,
                        'release' => '2026-03-12',
                    ],
                    'dashboard-coordenador' => [
                        'title' => 'Dashboard do Coordenador',
                        'summary' => 'Métricas globais: total de turmas, crianças, professores, observações. Relatório de cursos com progresso médio. Visão de todas as observações.',
                        'time' => 4,
                        'release' => '2026-03-12',
                    ],
                    'dashboard-admin' => [
                        'title' => 'Dashboard do Administrador',
                        'summary' => 'Visão completa: alunos, matrículas, cursos, contatos, escolas, horas assistidas, quizzes. Matrículas recentes, cursos populares, alunos com baixo progresso.',
                        'time' => 5,
                        'release' => '2026-03-12',
                    ],
                ],
            ],
            'turmas-alunos' => [
                'title' => 'Turmas e Alunos',
                'description' => 'Gerencie turmas (criar, ativar/desativar, vincular alunos) e cadastre alunos com foto, escola e resumo pedagógico via IA.',
                'icon' => 'fas fa-users',
                'color' => '#fd7e14',
                'articles' => [
                    'gerenciar-turmas' => [
                        'title' => 'Gerenciar Turmas',
                        'summary' => 'Crie turmas com nome, escola, professor, faixa etária e período. Ative/desative turmas sem perder histórico. Na edição, acesse "Gerenciar Alunos" para vincular e visualizar alunos.',
                        'time' => 5,
                        'release' => '2026-03-12',
                    ],
                    'vincular-alunos' => [
                        'title' => 'Vincular Alunos à Turma',
                        'summary' => 'Adicione e remova alunos da turma. Veja lista com foto, nome, data de nascimento e idade calculada. Acesse perfil direto da turma.',
                        'time' => 4,
                        'release' => '2026-03-12',
                    ],
                    'cadastrar-aluno' => [
                        'title' => 'Cadastrar Aluno',
                        'summary' => 'Registre com nome, data de nascimento (idade calculada automaticamente), escola e foto. Upload de imagem com preview.',
                        'time' => 4,
                        'release' => '2026-03-12',
                    ],
                    'perfil-aluno' => [
                        'title' => 'Perfil do Aluno e Resumo IA',
                        'summary' => 'Veja dados completos, turma atual, professor responsável, observações vinculadas. Use o botão "Resumo IA" para gerar narrativa pedagógica automática.',
                        'time' => 5,
                        'release' => '2026-03-12',
                    ],
                ],
            ],
            'observacoes' => [
                'title' => 'Observações Pedagógicas',
                'description' => 'Registre observações semestrais organizadas por 6 eixos pedagógicos com atalhos visuais, salvamento automático, finalização e controle de permissões. Todos os perfis podem criar.',
                'icon' => 'fas fa-clipboard-list',
                'color' => '#6f42c1',
                'articles' => [
                    'criar-observacao' => [
                        'title' => 'Criar uma Observação',
                        'summary' => 'Use os 6 cards coloridos de eixos como atalho ou o botão "Nova Observação". Selecione aluno, semestre e ano. Preencha os eixos em abas separadas. Disponível para todos os perfis.',
                        'time' => 6,
                        'release' => '2026-03-12',
                    ],
                    'eixos-pedagogicos' => [
                        'title' => 'Os 6 Eixos Pedagógicos',
                        'summary' => 'Entenda cada eixo: Observação Geral, Movimento, Atividade Manual, Atividade Musical, Contos e Programa Comunicação Ativa (PCA).',
                        'time' => 5,
                        'release' => '2026-03-12',
                    ],
                    'auto-save-finalizar' => [
                        'title' => 'Salvamento Automático e Finalização',
                        'summary' => 'O texto é salvo automaticamente ao sair do campo (blur) ou após 2 segundos sem digitar. Finalize para bloquear edição e liberar geração de Parecer.',
                        'time' => 5,
                        'release' => '2026-03-12',
                    ],
                    'permissoes-observacoes' => [
                        'title' => 'Permissões e Fluxo de Revisão',
                        'summary' => 'Todos os perfis (Professor, Coordenador, Admin) podem criar observações. Professor edita as suas. Coordenador visualiza todas, pode reabrir finalizadas. Admin tem acesso total.',
                        'time' => 4,
                        'release' => '2026-03-12',
                    ],
                ],
            ],
            'parecer-descritivo' => [
                'title' => 'Parecer Descritivo',
                'description' => 'Gere pareceres descritivos a partir das observações: capa, texto institucional, narrativa da criança com correção IA, fotos por eixo e exportação em PDF.',
                'icon' => 'fas fa-file-alt',
                'color' => '#20c997',
                'articles' => [
                    'criar-parecer' => [
                        'title' => 'Criar um Parecer Descritivo',
                        'summary' => 'Selecione aluno e observação. O sistema compila automaticamente os textos dos 6 eixos. Se não houver observações, uma mensagem clara orienta a criar uma primeiro.',
                        'time' => 6,
                        'release' => '2026-03-12',
                    ],
                    'editar-corrigir' => [
                        'title' => 'Editar Texto e Correção com IA',
                        'summary' => 'Edite o texto compilado sem alterar a observação original. Use o botão "Correção Automática IA" para revisar ortografia e gramática.',
                        'time' => 5,
                        'release' => '2026-03-12',
                    ],
                    'fotos-eixos' => [
                        'title' => 'Fotos dos Eixos de Atividades',
                        'summary' => 'Adicione até 3 fotos por eixo (Musical, Manual, Contos, Movimento, PCA) com legendas descritivas. As fotos aparecem no PDF final.',
                        'time' => 4,
                        'release' => '2026-03-12',
                    ],
                    'finalizar-exportar' => [
                        'title' => 'Finalizar e Exportar PDF',
                        'summary' => 'Finalize o parecer para bloquear edição. Coordenador pode solicitar revisão. Exporte PDF com 7 páginas: capa, texto institucional, narrativa e eixos.',
                        'time' => 5,
                        'release' => '2026-03-12',
                    ],
                ],
            ],
            'portfolio' => [
                'title' => 'Portfólio da Turma',
                'description' => 'Monte portfólios semestrais por turma com mensagem da professora, fotos por eixo, correção IA e exportação em PDF de 14+ páginas.',
                'icon' => 'fas fa-images',
                'color' => '#e83e8c',
                'articles' => [
                    'criar-portfolio' => [
                        'title' => 'Criar um Portfólio',
                        'summary' => 'Selecione turma, semestre e ano. Adicione foto de capa e mensagem da professora. Cada combinação turma/semestre/ano é única.',
                        'time' => 5,
                        'release' => '2026-03-12',
                    ],
                    'eixos-fotos' => [
                        'title' => 'Eixos, Descrições e Fotos',
                        'summary' => 'Preencha a descrição de cada eixo (Movimento, Manual, Musical, Contos, PCA) e adicione até 3 fotos com legendas por eixo.',
                        'time' => 6,
                        'release' => '2026-03-12',
                    ],
                    'finalizar-exportar-portfolio' => [
                        'title' => 'Finalizar e Exportar PDF',
                        'summary' => 'Finalize o portfólio, solicite revisão via coordenador e exporte PDF completo: capa, textos institucionais, mensagem, eixos com fotos.',
                        'time' => 5,
                        'release' => '2026-03-12',
                    ],
                ],
            ],
            'banco-imagens' => [
                'title' => 'Banco de Imagens',
                'description' => 'Organize fotos por turma e aluno. Upload múltiplo com redimensionamento automático, legendas editáveis e organização por pastas.',
                'icon' => 'fas fa-camera',
                'color' => '#17a2b8',
                'articles' => [
                    'organizar-fotos' => [
                        'title' => 'Estrutura e Navegação',
                        'summary' => 'Cada turma tem pastas automáticas: uma coletiva e uma por aluno. Navegue por turma → pasta → fotos com thumbnails.',
                        'time' => 4,
                        'release' => '2026-03-12',
                    ],
                    'upload-gerenciar' => [
                        'title' => 'Upload, Legendas e Organização',
                        'summary' => 'Faça upload de múltiplas fotos (JPG/PNG, redimensionadas para max 1920px). Edite legendas inline, mova entre pastas e exclua fotos.',
                        'time' => 5,
                        'release' => '2026-03-12',
                    ],
                ],
            ],
            'planejamento' => [
                'title' => 'Planejamento Pedagógico',
                'description' => 'Crie planejamentos quinzenais com templates, visualize e preencha por dia, finalize para revisão e registre a pós-vivência.',
                'icon' => 'fas fa-calendar-alt',
                'color' => '#6f42c1',
                'articles' => [
                    'criar-planejamento' => [
                        'title' => 'Criar um Planejamento',
                        'summary' => 'Escolha template, vincule turma, defina período quinzenal. Após criar, você será direcionado para a grade de dias úteis do período.',
                        'time' => 5,
                        'release' => '2026-03-16',
                    ],
                    'visualizacao-quinzenal' => [
                        'title' => 'Visualização Quinzenal por Dias',
                        'summary' => 'Grade de cards com os dias úteis (seg-sex) do período. Cada dia mostra status (vazio, rascunho, preenchido) e é clicável para abrir o formulário diário.',
                        'time' => 5,
                        'release' => '2026-03-16',
                    ],
                    'card-diario' => [
                        'title' => 'Preenchimento do Dia (Card Diário)',
                        'summary' => 'Formulário diário sem seção "Identificação". Eixos renomeados para "Eixo de Atividades" com seleção por botões toggle horizontais. Palavra do dia mantida.',
                        'time' => 5,
                        'release' => '2026-03-16',
                    ],
                    'usar-templates' => [
                        'title' => 'Templates de Planejamento',
                        'summary' => 'Crie templates com seções e campos (texto, textarea, select, radio, checkbox, checklist). Defina faixa etária e organize por ordem.',
                        'time' => 7,
                        'release' => '2026-03-01',
                    ],
                    'fluxo-status' => [
                        'title' => 'Fluxo de Status e Finalização',
                        'summary' => 'Rascunho → Enviado (botão "Finalizar Planejamento" notifica coordenadores) → Registrado (pós-vivência concluída). Cada etapa tem ações específicas.',
                        'time' => 4,
                        'release' => '2026-03-16',
                    ],
                    'registro-pos-vivencia' => [
                        'title' => 'Registro Pós-Vivência',
                        'summary' => 'Após finalizar o planejamento, preencha o registro do período: síntese do desenvolvimento, execução e justificativa. Cobre a quinzena inteira.',
                        'time' => 4,
                        'release' => '2026-03-16',
                    ],
                    'calendario-rotina' => [
                        'title' => 'Calendário e Rotina Semanal',
                        'summary' => 'Visualize planejamentos no calendário mensal. Crie rotinas diárias com horários e atividades para cada dia da semana (Seg-Sex).',
                        'time' => 5,
                        'release' => '2026-03-12',
                    ],
                    'dependencia-campos' => [
                        'title' => 'Dependência entre Campos',
                        'summary' => 'Configure campos que aparecem/ocultam conforme valor de outro campo. Ex: Eixo "Musical" → só objetivos musicais visíveis.',
                        'time' => 5,
                        'release' => '2026-03-01',
                    ],
                ],
            ],
            'material-apoio' => [
                'title' => 'Material de Apoio',
                'description' => 'Repositório centralizado de materiais pedagógicos organizados em pastas hierárquicas: Eixos de Atividades, Centros de Aprendizagem e Famílias de Brinquedos.',
                'icon' => 'fas fa-folder-open',
                'color' => '#795548',
                'articles' => [
                    'navegacao-pastas' => [
                        'title' => 'Navegação e Estrutura de Pastas',
                        'summary' => 'Árvore de pastas com subpastas ilimitadas. Estrutura padrão: Eixos de Atividades (Manuais, Musicais, Contos, Movimento), Centros de Aprendizagem, Famílias de Brinquedos.',
                        'time' => 3,
                        'release' => '2026-03-12',
                    ],
                    'upload-download' => [
                        'title' => 'Upload e Download de Materiais',
                        'summary' => 'Envie PDFs, documentos e arquivos. Faça download preservando o nome original. Admin faz upload/exclusão; professores e coordenadores baixam.',
                        'time' => 4,
                        'release' => '2026-03-12',
                    ],
                ],
            ],
            'notificacoes-fluxo' => [
                'title' => 'Notificações e Fluxo de Aprovação',
                'description' => 'Sistema de notificações internas com sino no header. Fluxo Professor → Coordenador para pareceres, portfólios e planejamentos.',
                'icon' => 'fas fa-bell',
                'color' => '#ffc107',
                'articles' => [
                    'sistema-notificacoes' => [
                        'title' => 'Sistema de Notificações',
                        'summary' => 'Sino com badge de contagem no header. Dropdown com 10 notificações recentes. Página completa com 100 últimas. Marcar como lida individual ou em massa.',
                        'time' => 4,
                        'release' => '2026-03-12',
                    ],
                    'fluxo-aprovacao' => [
                        'title' => 'Fluxo de Aprovação Professor → Coordenador',
                        'summary' => 'Professor finaliza → coordenadores notificados. Coordenador solicita revisão com notas → professor notificado. Professor corrige e refinaliza.',
                        'time' => 5,
                        'release' => '2026-03-12',
                    ],
                ],
            ],
            'cursos' => [
                'title' => 'Cursos e Formação',
                'description' => 'Crie cursos completos com hierarquia Módulos → Seções → Lições, vídeos com tracking, quizzes avaliativos e materiais de apoio.',
                'icon' => 'fas fa-book',
                'color' => '#0d6efd',
                'articles' => [
                    'criar-curso' => [
                        'title' => 'Criar um Curso',
                        'summary' => 'Cadastre com título, slug automático, imagem de capa, instrutor, preço, nível e categoria. Ative quando estiver pronto.',
                        'time' => 5,
                        'release' => '2026-03-01',
                    ],
                    'modulos-licoes' => [
                        'title' => 'Módulos, Seções e Lições',
                        'summary' => 'Organize conteúdo em hierarquia, mova seções entre módulos, adicione vídeos com preview e reordene com drag.',
                        'time' => 8,
                        'release' => '2026-03-01',
                    ],
                    'publicar-curso' => [
                        'title' => 'Quizzes, Materiais e Publicação',
                        'summary' => 'Configure quizzes com nota mínima e tentativas, envie materiais de apoio (PDF, Excel, vídeo, máx 50MB) e publique o curso.',
                        'time' => 6,
                        'release' => '2026-03-01',
                    ],
                    'visao-aluno' => [
                        'title' => 'Visão do Aluno no Curso',
                        'summary' => 'Dashboard do aluno com cursos matriculados, botão "Continuar curso", módulos com status (não iniciado/em andamento/concluído), indicadores de progresso.',
                        'time' => 5,
                        'release' => '2026-03-12',
                    ],
                ],
            ],
            'relatorios' => [
                'title' => 'Relatórios e Análises',
                'description' => 'Métricas consolidadas, performance por curso, alunos com notas baixas, tracking de vídeo, ranking de engajamento e relatório de cursos para coordenadores.',
                'icon' => 'fas fa-chart-bar',
                'color' => '#dc3545',
                'articles' => [
                    'relatorio-geral' => [
                        'title' => 'Relatório Geral',
                        'summary' => 'Cards de resumo, performance por curso (taxa de conclusão), desempenho de quizzes e tendências de matrícula nos últimos 30 dias.',
                        'time' => 5,
                        'release' => '2026-03-01',
                    ],
                    'notas-baixas' => [
                        'title' => 'Alunos com Notas Baixas',
                        'summary' => 'Identifique quem não atingiu a nota mínima, veja tentativas restantes e libere retry com um clique.',
                        'time' => 4,
                        'release' => '2026-03-01',
                    ],
                    'tracking-video' => [
                        'title' => 'Dashboard de Vídeos',
                        'summary' => 'Horas assistidas, sessões por dia, ranking de alunos, inativos há +7 dias e histórico completo de sessões.',
                        'time' => 7,
                        'release' => '2026-03-01',
                    ],
                    'relatorio-cursos' => [
                        'title' => 'Relatório de Cursos (Coordenador)',
                        'summary' => 'Tabela com nome do curso, professores inscritos, progresso médio, concluídos e ativos. Exportação CSV.',
                        'time' => 4,
                        'release' => '2026-03-12',
                    ],
                ],
            ],
        ];

        $this->faq = [
            // --- Interface e Navegação ---
            [
                'question' => 'Como alterno entre modo claro e escuro?',
                'answer' => 'Clique no ícone de lua (<i class="fas fa-moon"></i>) na barra superior do painel. A preferência é salva automaticamente no navegador e persiste entre sessões.',
            ],
            [
                'question' => 'Posso usar o sistema no celular?',
                'answer' => 'Sim! O painel é totalmente responsivo. No celular, toque no botão ☰ no canto superior esquerdo para abrir o menu lateral.',
            ],
            [
                'question' => 'Como funciona o tour interativo?',
                'answer' => 'Clique no botão <strong>? Ajuda</strong> na barra superior ou em <strong>"Tour desta Página"</strong> no menu lateral. Um guia visual destaca cada elemento da página com explicações.',
            ],
            // --- Papéis e Acesso ---
            [
                'question' => 'Quais são os papéis de usuário?',
                'answer' => 'O sistema tem 5 papéis: <strong>Admin</strong> (acesso total), <strong>Professor</strong> (turmas, observações, pareceres, portfólios, planejamentos), <strong>Coordenador</strong> (visualização e aprovação), <strong>Student</strong> (painel do aluno) e <strong>Parent</strong> (painel do responsável).',
            ],
            [
                'question' => 'O que o Professor pode fazer que o Coordenador não pode?',
                'answer' => 'O Professor <strong>cria e edita</strong> observações, pareceres, portfólios e planejamentos. O Coordenador <strong>também pode criar observações</strong>, além de <strong>visualizar tudo</strong>, <strong>reabrir</strong> documentos finalizados e <strong>solicitar revisão</strong> com notas explicativas.',
            ],
            [
                'question' => 'Posso excluir minha própria conta de admin?',
                'answer' => 'Não. O sistema impede exclusão da própria conta e bloqueia se você for o último administrador, garantindo sempre acesso administrativo.',
            ],
            [
                'question' => 'Como redefinir a senha de um usuário?',
                'answer' => 'Acesse <strong>Cadastros → Usuários</strong>, edite o usuário e digite nova senha (mínimo 6 caracteres). Deixe em branco para manter a atual.',
            ],
            // --- Turmas e Alunos ---
            [
                'question' => 'Uma turma pode ser excluída?',
                'answer' => 'Não. Turmas são <strong>desativadas</strong>, nunca excluídas. Isso preserva todo o histórico pedagógico (observações, pareceres, portfólios). Use o botão "Desativar" na listagem de turmas.',
            ],
            [
                'question' => 'Como vincular um aluno a uma turma?',
                'answer' => 'Acesse a turma desejada e clique em <strong>"Adicionar Aluno"</strong>. Selecione o aluno no dropdown (mostra apenas alunos da mesma escola) e confirme. O aluno aparecerá na lista da turma.',
            ],
            [
                'question' => 'Por que não consigo excluir um aluno?',
                'answer' => 'Alunos com <strong>observações pedagógicas</strong> vinculadas não podem ser excluídos para proteger o histórico. Remova as observações primeiro ou mantenha o registro.',
            ],
            [
                'question' => 'O que é o "Resumo IA" no perfil do aluno?',
                'answer' => 'É um resumo pedagógico gerado pela <strong>IA (Gemini)</strong> analisando todas as observações do aluno. Clique no botão roxo <strong>🪄 Resumo IA</strong> na página de detalhes. Requer observações cadastradas.',
            ],
            // --- Observações ---
            [
                'question' => 'Quais são os 6 eixos pedagógicos das observações?',
                'answer' => '1) <strong>Observação Geral</strong>, 2) <strong>Eixo de Movimento</strong>, 3) <strong>Eixo Manual</strong>, 4) <strong>Eixo Musical</strong>, 5) <strong>Eixo de Contos</strong>, 6) <strong>Programa Comunicação Ativa (PCA)</strong>. Cada eixo tem um campo de texto dedicado com 5 linhas.',
            ],
            [
                'question' => 'Como funciona o salvamento automático?',
                'answer' => 'O texto é salvo automaticamente ao <strong>sair do campo</strong> (blur) ou após <strong>2 segundos sem digitar</strong> (debounce). Um indicador mostra "Salvo automaticamente às HH:MM". Se houver mudanças não salvas, um alerta aparece ao tentar sair da página.',
            ],
            [
                'question' => 'Posso editar uma observação finalizada?',
                'answer' => 'O professor <strong>não pode</strong> editar após finalizar. O <strong>Coordenador</strong> ou <strong>Admin</strong> pode reabrir a observação usando o botão "Reabrir", devolvendo-a ao estado "Em andamento" para edição.',
            ],
            [
                'question' => 'Pode haver duas observações para o mesmo aluno no mesmo semestre?',
                'answer' => 'Não. O sistema verifica duplicatas e impede a criação de mais de uma observação por aluno/semestre/ano.',
            ],
            // --- Parecer Descritivo ---
            [
                'question' => 'Como o texto do parecer é gerado?',
                'answer' => 'Ao criar um parecer, o sistema <strong>compila automaticamente</strong> os textos dos 6 eixos da observação vinculada em uma narrativa unificada. Você pode editar livremente o texto compilado sem alterar a observação original.',
            ],
            [
                'question' => 'O que faz o botão "Correção Automática IA"?',
                'answer' => 'Envia o texto do parecer para a <strong>IA (Gemini)</strong> que revisa ortografia, gramática e fluidez textual <strong>sem alterar o conteúdo pedagógico</strong>. A versão corrigida é salva automaticamente e pode ser editada novamente.',
            ],
            [
                'question' => 'Quantas fotos posso adicionar por eixo no parecer?',
                'answer' => 'Até <strong>3 fotos por eixo</strong>, cada uma com legenda descritiva. Os 5 eixos são: Musical, Manual, Contos, Movimento e PCA. No PDF, as fotos ficam dispostas em layout 2+1 (duas em cima, uma embaixo).',
            ],
            [
                'question' => 'Quando o botão "Exportar PDF" aparece?',
                'answer' => 'O botão aparece apenas quando o parecer está com status <strong>"Finalizado"</strong>. O PDF tem 7 páginas: capa, texto institucional, narrativa da criança e 5 páginas de eixos com fotos.',
            ],
            // --- Portfólio ---
            [
                'question' => 'Qual a diferença entre Parecer e Portfólio?',
                'answer' => 'O <strong>Parecer Descritivo</strong> é individual (um por aluno), focado em texto narrativo sobre a criança. O <strong>Portfólio</strong> é coletivo (um por turma), focado em fotos das atividades da turma nos 5 eixos com textos institucionais fixos.',
            ],
            [
                'question' => 'Quantas páginas tem o PDF do portfólio?',
                'answer' => '<strong>14+ páginas:</strong> Capa → Sobre a Magia do Portfólio → Proposta da Pedagogia Florença → Mensagem da Professora → Os Eixos de Atividades → 5 pares de páginas (descrição do eixo + fotos).',
            ],
            // --- Banco de Imagens ---
            [
                'question' => 'As pastas do banco de imagens são criadas automaticamente?',
                'answer' => 'Sim! Ao acessar o banco de imagens de uma turma, o sistema cria automaticamente uma <strong>pasta coletiva</strong> e <strong>pastas individuais</strong> para cada aluno vinculado à turma.',
            ],
            [
                'question' => 'Qual o tamanho máximo das fotos?',
                'answer' => 'Formatos aceitos: <strong>JPG e PNG</strong>. As fotos são <strong>redimensionadas automaticamente</strong> para no máximo 1920px de largura, mantendo a proporção. Isso otimiza o armazenamento sem perder qualidade visual.',
            ],
            [
                'question' => 'O Coordenador pode fazer upload de fotos?',
                'answer' => 'Não. O Coordenador tem <strong>acesso somente leitura</strong> ao banco de imagens. Apenas <strong>Professores</strong> e <strong>Admins</strong> podem fazer upload, mover e excluir fotos.',
            ],
            // --- Planejamento ---
            [
                'question' => 'O que são campos condicionais no planejamento?',
                'answer' => 'Campos que só aparecem quando outro campo tem um valor específico. Exemplo: ao selecionar <strong>Eixo "Musical"</strong> na Seção 2, apenas os objetivos musicais ficam visíveis na Seção 4 — os demais ficam ocultos automaticamente.',
            ],
            [
                'question' => 'Como funciona o Calendário de Planejamentos?',
                'answer' => 'O calendário mostra uma visão mensal com todas as semanas. Cada semana mostra os planejamentos vinculados (se existirem) com badges de status. Para editar um planejamento, clique nele para acessar a <strong>grade de dias úteis</strong> do período.',
            ],
            [
                'question' => 'O que é a Rotina Diária?',
                'answer' => 'É um recurso dentro de cada planejamento semanal que permite definir <strong>atividades por dia</strong> (Segunda a Sexta). Cada atividade tem um <strong>horário</strong> (ex: 08:00-08:30) e uma <strong>descrição</strong>. A visualização mostra os 5 dias lado a lado para comparação.',
            ],
            [
                'question' => 'Professores podem ver planejamentos de outros professores?',
                'answer' => 'Não. Professores veem apenas seus próprios planejamentos. <strong>Administradores</strong> e <strong>Coordenadores</strong> têm acesso a todos e podem filtrar por professor, turma ou status.',
            ],
            // --- Material de Apoio ---
            [
                'question' => 'Qual a estrutura padrão das pastas de material de apoio?',
                'answer' => 'A estrutura inicial tem 3 pastas principais: <strong>Eixos de Atividades</strong> (com subpastas Manuais, Musicais, Contos e Movimento), <strong>Centros de Aprendizagem</strong> e <strong>Famílias de Brinquedos</strong>. O eixo PCA não tem subpasta (material é físico).',
            ],
            // --- Notificações ---
            [
                'question' => 'Como funcionam as notificações?',
                'answer' => 'O <strong>sino</strong> no header mostra um badge com a contagem de não lidas. Clique para ver as 10 mais recentes em dropdown. Acesse <strong>Notificações</strong> no menu para ver todas (últimas 100). Marque como lida individualmente ou use "Marcar todas como lidas".',
            ],
            [
                'question' => 'Quando recebo notificações?',
                'answer' => 'Notificações são geradas automaticamente quando: um <strong>documento é finalizado</strong> (pareceres, portfólios), um <strong>coordenador solicita revisão</strong>, ou um documento é <strong>reaberto</strong>. O tipo de notificação é identificado por ícones coloridos.',
            ],
            // --- Cursos ---
            [
                'question' => 'Por que não consigo excluir um curso?',
                'answer' => 'Cursos com <strong>matrículas vinculadas</strong> não podem ser excluídos. Para retirá-lo do catálogo, edite e desmarque "Ativo". As matrículas existentes continuam funcionando.',
            ],
            [
                'question' => 'Posso mover seções entre módulos?',
                'answer' => 'Sim! Na página do curso, clique em <strong>"Mover"</strong> no cabeçalho de um módulo. Selecione seções e transfira para outro módulo ou "Seções Gerais".',
            ],
            [
                'question' => 'O que acontece quando um aluno completa um curso?',
                'answer' => 'O sistema marca a matrícula como concluída (100%). O aluno pode gerar um <strong>certificado digital</strong> com código de verificação único, validável publicamente em <code>/certificado/{código}</code>.',
            ],
            [
                'question' => 'Um aluno esgotou as tentativas do quiz. O que fazer?',
                'answer' => 'Acesse <strong>Análises → Notas Baixas</strong> e clique em <strong>🔄 Liberar Retry</strong>. Isso remove tentativas anteriores, permitindo refazer o quiz do zero.',
            ],
            // --- Relatórios ---
            [
                'question' => 'Onde vejo o relatório de cursos do Coordenador?',
                'answer' => 'Em <strong>Análises → Relatório de Cursos</strong>. Mostra tabela com curso, inscritos, professores, ativos, concluídos e progresso médio. Disponível para Admin e Coordenador. Tem botão de exportação CSV.',
            ],
            [
                'question' => 'Como os vídeos são rastreados?',
                'answer' => 'O sistema registra cada sessão de vídeo automaticamente: horário de início/fim, tempo assistido, progresso antes/depois, dispositivo e IP. Veja tudo em <strong>Análises → Vídeos / Tracking</strong>.',
            ],
            // --- Escolas e Responsáveis ---
            [
                'question' => 'Como funciona o contrato de uma escola?',
                'answer' => 'Ao cadastrar em <strong>Cadastros → Escolas</strong>, defina datas de início/fim do contrato e status. O sistema não bloqueia acesso com base nas datas — é apenas controle informativo.',
            ],
            [
                'question' => 'Como vincular responsáveis a alunos?',
                'answer' => 'Em <strong>Cadastros → Responsáveis</strong>, clique em <strong>"Vincular Filhos"</strong>. Selecione o aluno e o tipo de parentesco (Pai, Mãe ou Responsável). O responsável terá acesso em <code>/minha-area</code>.',
            ],
            // --- Simulador de Perfil ---
            [
                'question' => 'O que é o Simulador de Perfil?',
                'answer' => 'É um recurso exclusivo para <strong>Administradores</strong> que permite visualizar a plataforma como se fosse um Professor ou Coordenador. Acesse pelo dropdown <strong>"Simular Perfil"</strong> na barra superior. Um banner amarelo indica que a simulação está ativa. Importante: a simulação afeta apenas a <strong>visualização</strong> (menus e dashboard) — operações de escrita continuam usando o perfil real.',
            ],
            // --- Planejamento Quinzenal ---
            [
                'question' => 'Como funciona a visualização quinzenal do planejamento?',
                'answer' => 'Ao editar um planejamento, você vê uma <strong>grade com os dias úteis</strong> (segunda a sexta) do período definido. Cada dia é um card clicável que mostra seu status: <strong>vazio</strong> (cinza), <strong>rascunho</strong> (amarelo) ou <strong>preenchido</strong> (verde). O dia atual é destacado com badge "HOJE".',
            ],
            [
                'question' => 'Como finalizo um planejamento?',
                'answer' => 'Na tela de dias, clique no botão <strong>"Finalizar Planejamento"</strong> (visível apenas quando o status é Rascunho). O status muda para "Enviado" e todos os <strong>coordenadores recebem uma notificação</strong> automática. Após o envio, os dias não podem mais ser editados.',
            ],
            [
                'question' => 'O que é o Registro Pós-Vivência?',
                'answer' => 'Após finalizar o planejamento (status "Enviado"), aparece o botão <strong>"Registro Pós-Vivência"</strong>. É um formulário separado com campos de registro do período (síntese, execução, justificativa). Ao finalizar o registro, o status muda para <strong>"Registrado"</strong> — estado final do planejamento.',
            ],
        ];
    }

    public function index()
    {
        return $this->render('help/index', [
            'categories' => $this->categories,
            'faq' => $this->faq,
        ]);
    }

    public function category(string $slug)
    {
        if (!isset($this->categories[$slug])) {
            header('Location: /admin/help');
            exit;
        }

        $category = $this->categories[$slug];
        $category['slug'] = $slug;

        return $this->render('help/category', [
            'category' => $category,
        ]);
    }

    public function article(string $catSlug, string $artSlug)
    {
        if (!isset($this->categories[$catSlug]) || !isset($this->categories[$catSlug]['articles'][$artSlug])) {
            header('Location: /admin/help');
            exit;
        }

        $category = $this->categories[$catSlug];
        $category['slug'] = $catSlug;
        $article = $category['articles'][$artSlug];
        $article['slug'] = $artSlug;

        // Related articles (others in same category)
        $related = [];
        foreach ($category['articles'] as $slug => $art) {
            if ($slug !== $artSlug) {
                $related[$slug] = $art;
            }
        }

        return $this->render('help/article', [
            'category' => $category,
            'article' => $article,
            'related' => $related,
            'catSlug' => $catSlug,
            'artSlug' => $artSlug,
        ]);
    }

    protected function render(string $view, array $data = [])
    {
        extract($data);
        $viewFile = __DIR__ . "/../../../views/admin/{$view}.php";

        ob_start();
        if (file_exists($viewFile)) {
            include $viewFile;
        } else {
            echo "<h2>Página não encontrada</h2>";
        }
        $content = ob_get_clean();

        include __DIR__ . "/../../../views/layouts/admin.php";
    }
}
