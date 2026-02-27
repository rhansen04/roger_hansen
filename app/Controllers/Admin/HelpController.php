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
                'description' => 'Aprenda o básico do painel administrativo: Dashboard, menu lateral, modo escuro, tours interativos e configurações iniciais.',
                'icon' => 'fas fa-rocket',
                'color' => '#007e66',
                'articles' => [
                    'visao-geral' => [
                        'title' => 'Visão Geral do Painel',
                        'summary' => 'Conheça o Dashboard, menu lateral com 5 seções, barra superior e todas as funcionalidades do painel administrativo.',
                        'time' => 5,
                    ],
                    'primeiro-acesso' => [
                        'title' => 'Primeiro Acesso e Configuração',
                        'summary' => 'Checklist completo do primeiro login: papéis de usuário, cadastro de escola, alunos e primeira matrícula.',
                        'time' => 6,
                    ],
                    'navegacao' => [
                        'title' => 'Navegação, Modo Escuro e Tours',
                        'summary' => 'Menu lateral responsivo, modo escuro persistente, tours interativos página a página e Central de Ajuda.',
                        'time' => 4,
                    ],
                ],
            ],
            'planejamento' => [
                'title' => 'Planejamento Pedagógico',
                'description' => 'Crie planejamentos pedagógicos com templates personalizáveis, seções dinâmicas e fluxo de aprovação (Rascunho → Enviado → Registrado).',
                'icon' => 'fas fa-calendar-alt',
                'color' => '#6f42c1',
                'articles' => [
                    'criar-planejamento' => [
                        'title' => 'Criar um Planejamento',
                        'summary' => 'Passo a passo: escolher template, vincular turma, definir período e preencher as seções geradas automaticamente.',
                        'time' => 6,
                    ],
                    'usar-templates' => [
                        'title' => 'Templates de Planejamento',
                        'summary' => 'Como criar e gerenciar templates com seções e campos personalizados (texto, seleção, checkbox, radio).',
                        'time' => 7,
                    ],
                    'fluxo-status' => [
                        'title' => 'Fluxo de Status',
                        'summary' => 'Entenda os 3 estados: Rascunho (professor edita), Enviado (coordenação avalia) e Registrado (pós-vivência).',
                        'time' => 3,
                    ],
                ],
            ],
            'cursos' => [
                'title' => 'Cursos',
                'description' => 'Crie cursos completos com hierarquia Módulos → Seções → Lições, vídeos com preview, quizzes avaliativos e materiais de apoio.',
                'icon' => 'fas fa-book',
                'color' => '#0d6efd',
                'articles' => [
                    'criar-curso' => [
                        'title' => 'Criar um Curso',
                        'summary' => 'Cadastre com título, slug automático, imagem de capa, instrutor, preço, nível e categoria.',
                        'time' => 5,
                    ],
                    'modulos-licoes' => [
                        'title' => 'Módulos, Seções e Lições',
                        'summary' => 'Organize conteúdo em hierarquia, mova seções entre módulos, adicione vídeos com preview e reordene com drag.',
                        'time' => 8,
                    ],
                    'publicar-curso' => [
                        'title' => 'Quizzes, Materiais e Publicação',
                        'summary' => 'Configure quizzes com nota mínima e tentativas, envie materiais (PDF, Excel, vídeo) e ative o curso.',
                        'time' => 6,
                    ],
                ],
            ],
            'turmas' => [
                'title' => 'Turmas',
                'description' => 'Organize alunos em turmas vinculadas a escolas e professores, com faixa etária, período e ano letivo.',
                'icon' => 'fas fa-chalkboard',
                'color' => '#fd7e14',
                'articles' => [
                    'criar-turma' => [
                        'title' => 'Criar uma Turma',
                        'summary' => 'Configure nome, escola, professor, faixa etária, período (manhã/tarde/integral) e ano letivo.',
                        'time' => 4,
                    ],
                    'gerenciar-alunos' => [
                        'title' => 'Gerenciar Alunos na Turma',
                        'summary' => 'Edite composição da turma, veja vinculações com planejamentos e mantenha o histórico organizado.',
                        'time' => 4,
                    ],
                ],
            ],
            'alunos' => [
                'title' => 'Alunos e Matrículas',
                'description' => 'Cadastre alunos com foto, realize matrículas com controle de status/pagamento, acompanhe progresso e gere resumos com IA.',
                'icon' => 'fas fa-user-graduate',
                'color' => '#20c997',
                'articles' => [
                    'cadastrar-aluno' => [
                        'title' => 'Cadastrar Aluno',
                        'summary' => 'Registro com nome, data de nascimento (idade automática), escola, foto com preview e resumo IA via Gemini.',
                        'time' => 5,
                    ],
                    'matricular' => [
                        'title' => 'Realizar Matrícula',
                        'summary' => 'Matricule alunos em cursos com status ativo/pendente, controle de pagamento e prevenção de duplicatas.',
                        'time' => 5,
                    ],
                    'acompanhar-progresso' => [
                        'title' => 'Acompanhar Progresso',
                        'summary' => 'Dashboard de Vídeos, progresso por lição, sessões individuais, alunos inativos e certificados digitais.',
                        'time' => 7,
                    ],
                ],
            ],
            'relatorios' => [
                'title' => 'Relatórios e Análises',
                'description' => 'Métricas consolidadas, performance por curso e quiz, alunos com notas baixas, tracking detalhado de vídeo e ranking de engajamento.',
                'icon' => 'fas fa-chart-bar',
                'color' => '#dc3545',
                'articles' => [
                    'relatorio-geral' => [
                        'title' => 'Relatório Geral',
                        'summary' => 'Cards de resumo, performance por curso (taxa de conclusão), desempenho de quizzes e tendências de matrícula.',
                        'time' => 5,
                    ],
                    'notas-baixas' => [
                        'title' => 'Alunos com Notas Baixas',
                        'summary' => 'Identifique quem não atingiu a nota mínima, veja tentativas restantes e libere retry com um clique.',
                        'time' => 4,
                    ],
                    'tracking-video' => [
                        'title' => 'Dashboard de Vídeos',
                        'summary' => 'Horas assistidas, sessões por dia, ranking de alunos, inativos há +7 dias e histórico completo por aluno.',
                        'time' => 7,
                    ],
                ],
            ],
        ];

        $this->faq = [
            // --- Interface e Navegação ---
            [
                'question' => 'Como alterno entre modo claro e escuro?',
                'answer' => 'Clique no ícone de lua (<i class="fas fa-moon"></i>) na barra superior do painel. A preferência é salva automaticamente no seu navegador e persiste entre sessões.',
            ],
            [
                'question' => 'Posso usar o sistema no celular?',
                'answer' => 'Sim! O painel é totalmente responsivo. No celular, toque no botão ☰ no canto superior esquerdo para abrir o menu lateral. Toque fora do menu ou no overlay escuro para fechar.',
            ],
            [
                'question' => 'Como funciona o tour interativo?',
                'answer' => 'Clique no botão <strong>? Ajuda</strong> na barra superior ou em <strong>"Tour desta Página"</strong> no menu lateral. Um guia visual destaca cada elemento da página com explicações detalhadas. Use "Próximo" e "Anterior" para navegar pelo tour.',
            ],
            // --- Usuários e Acesso ---
            [
                'question' => 'Quem pode acessar o painel administrativo?',
                'answer' => 'Usuários com papel de <strong>admin</strong> ou <strong>professor</strong>. O sistema tem 5 papéis: admin (acesso total), professor (gestão de alunos), coordenador (relatórios), student (painel do aluno em /minha-conta) e parent (painel de responsável em /minha-area).',
            ],
            [
                'question' => 'Posso excluir minha própria conta de admin?',
                'answer' => 'Não. O sistema impede que você exclua sua própria conta e também bloqueia a exclusão se você for o último administrador. Isso garante que sempre haverá acesso administrativo ao sistema.',
            ],
            [
                'question' => 'Como redefinir a senha de um usuário?',
                'answer' => 'Acesse <strong>Cadastros → Usuários</strong>, clique em editar o usuário e digite uma nova senha (mínimo 6 caracteres). Se deixar o campo de senha em branco, a senha atual é mantida.',
            ],
            // --- Alunos e Matrículas ---
            [
                'question' => 'Como faço para matricular vários alunos de uma vez?',
                'answer' => 'Atualmente as matrículas são feitas individualmente em <strong>Ensino → Matrículas</strong>. Clique em "Nova Matrícula", informe o ID do aluno (usuário com papel student), selecione o curso e defina o status.',
            ],
            [
                'question' => 'O que acontece quando um aluno completa um curso?',
                'answer' => 'O sistema marca automaticamente a matrícula como concluída (100% de progresso). O aluno pode então gerar um <strong>certificado digital</strong> com código de verificação único, que pode ser validado publicamente pela URL <code>/certificado/{código}</code>.',
            ],
            [
                'question' => 'Posso reativar uma matrícula desativada?',
                'answer' => 'Sim! Em <strong>Ensino → Matrículas</strong>, clique no botão <strong>✅ Ativar</strong> ao lado da matrícula inativa. O aluno recupera o acesso ao curso com todo o progresso preservado.',
            ],
            [
                'question' => 'Por que não consigo excluir um aluno?',
                'answer' => 'Alunos com <strong>observações pedagógicas</strong> vinculadas não podem ser excluídos para proteger o histórico. Remova as observações primeiro ou mantenha o aluno no sistema como registro.',
            ],
            [
                'question' => 'O que é o "Resumo IA" no perfil do aluno?',
                'answer' => 'É um resumo pedagógico gerado automaticamente pela <strong>API Gemini (IA)</strong> analisando todas as observações do aluno. Clique no botão roxo <strong>🪄 Resumo IA</strong> na página de detalhes do aluno. Requer que observações estejam cadastradas.',
            ],
            // --- Cursos e Conteúdo ---
            [
                'question' => 'Por que não consigo excluir um curso?',
                'answer' => 'Cursos com <strong>matrículas vinculadas</strong> não podem ser excluídos. Para retirá-lo do catálogo, edite o curso e desmarque "Ativo". As matrículas existentes continuam funcionando normalmente.',
            ],
            [
                'question' => 'O que acontece ao excluir um módulo?',
                'answer' => 'As seções dentro do módulo <strong>não são removidas</strong> — apenas desvinculadas e movidas para "Seções Gerais". Já ao excluir uma seção, todas as lições dentro dela são removidas permanentemente.',
            ],
            [
                'question' => 'Posso mover seções entre módulos?',
                'answer' => 'Sim! Na página do curso, clique no botão <strong>"Mover"</strong> no cabeçalho de um módulo. Um modal permite selecionar seções e transferi-las para outro módulo ou para "Seções Gerais".',
            ],
            [
                'question' => 'Posso adicionar materiais de apoio aos cursos?',
                'answer' => 'Sim! Na página de detalhes do curso, clique em <strong>"Materiais"</strong>. Envie PDFs, Excel, Word, PowerPoint, imagens, vídeos ou ZIP (máx. 50MB). Os alunos podem baixar pelo painel do curso.',
            ],
            // --- Vídeo e Tracking ---
            [
                'question' => 'Como os vídeos são rastreados?',
                'answer' => 'O sistema registra automaticamente cada sessão de vídeo: horário de início/fim, tempo assistido, progresso antes e depois, dispositivo e IP. Veja tudo em <strong>Análises → Vídeos / Tracking</strong>.',
            ],
            [
                'question' => 'O que significa "aluno inativo"?',
                'answer' => 'No Dashboard de Vídeos, alunos que não acessaram nenhuma lição há mais de <strong>7 dias</strong> aparecem na seção "Alunos Inativos" com um badge vermelho mostrando os dias de inatividade.',
            ],
            // --- Quizzes ---
            [
                'question' => 'Um aluno esgotou as tentativas do quiz. O que fazer?',
                'answer' => 'Acesse <strong>Análises → Notas Baixas</strong> e clique no botão <strong>🔄 Liberar Retry</strong> ao lado do aluno. Isso remove todas as tentativas anteriores, permitindo refazer o quiz.',
            ],
            // --- Planejamento ---
            [
                'question' => 'Posso restaurar um planejamento excluído?',
                'answer' => 'Não, a exclusão é permanente. Use os status (Rascunho → Enviado → Registrado) para controlar o fluxo. Evite excluir planejamentos que podem servir como referência futura.',
            ],
            [
                'question' => 'Professores podem ver planejamentos de outros professores?',
                'answer' => 'Não. Professores veem apenas seus próprios planejamentos. <strong>Administradores</strong> e <strong>coordenadores</strong> têm acesso a todos os planejamentos e podem filtrar por professor, turma ou status.',
            ],
            // --- Responsáveis ---
            [
                'question' => 'Como vincular responsáveis a alunos?',
                'answer' => 'Acesse <strong>Cadastros → Responsáveis</strong>, clique em <strong>"Vincular Filhos"</strong> ao lado do responsável. Selecione o aluno (usuário student) e o tipo de parentesco (Pai, Mãe ou Responsável). O responsável terá acesso ao painel em <code>/minha-area</code>.',
            ],
            // --- Contatos e Comunicação ---
            [
                'question' => 'Como sei que recebi um novo contato?',
                'answer' => 'Na seção <strong>Comunicação → Contatos</strong>, mensagens não lidas aparecem com badge <strong>"Novo"</strong> em vermelho e texto em negrito. Um contador de não lidos aparece no topo da página. O contato é marcado como lido automaticamente ao visualizar.',
            ],
            [
                'question' => 'Como responder perguntas dos alunos?',
                'answer' => 'Em <strong>Comunicação → Perguntas</strong>, clique no botão <strong>"Responder"</strong> ao lado da pergunta. Você será redirecionado para a thread do curso onde pode digitar sua resposta.',
            ],
            // --- Escolas ---
            [
                'question' => 'Como funciona o contrato de uma escola?',
                'answer' => 'Ao cadastrar uma escola em <strong>Cadastros → Escolas</strong>, defina as datas de início e fim do contrato e o status (ativa/inativa). Isso é informativo — o sistema não bloqueia acesso com base nas datas, mas ajuda no controle administrativo.',
            ],
            // --- Observações ---
            [
                'question' => 'Quais tipos de observação pedagógica existem?',
                'answer' => 'O sistema oferece 5 categorias: <span class="badge bg-primary">Comportamento</span> (atitudes e interações), <span class="badge bg-success">Aprendizado</span> (progressos e dificuldades), <span class="badge bg-danger">Saúde</span> (sintomas e acidentes), <span class="badge bg-warning text-dark">Comunicação com Pais</span> (conversas e alinhamentos) e <span class="badge bg-secondary">Geral</span>.',
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
