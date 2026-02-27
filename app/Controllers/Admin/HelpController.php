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
                'description' => 'Aprenda o básico do painel administrativo e comece a usar o sistema com confiança.',
                'icon' => 'fas fa-rocket',
                'color' => '#007e66',
                'articles' => [
                    'visao-geral' => [
                        'title' => 'Visão Geral do Painel',
                        'summary' => 'Conheça todas as áreas do painel administrativo e entenda para que serve cada seção.',
                        'time' => 5,
                    ],
                    'primeiro-acesso' => [
                        'title' => 'Primeiro Acesso',
                        'summary' => 'O que fazer logo após o primeiro login: configurações iniciais e dicas essenciais.',
                        'time' => 3,
                    ],
                    'navegacao' => [
                        'title' => 'Navegação e Atalhos',
                        'summary' => 'Como navegar pelo menu lateral, usar o modo escuro e acessar tours interativos.',
                        'time' => 4,
                    ],
                ],
            ],
            'planejamento' => [
                'title' => 'Planejamento Pedagógico',
                'description' => 'Crie e gerencie planejamentos pedagógicos usando templates flexíveis.',
                'icon' => 'fas fa-calendar-alt',
                'color' => '#6f42c1',
                'articles' => [
                    'criar-planejamento' => [
                        'title' => 'Criar um Planejamento',
                        'summary' => 'Passo a passo para criar um novo planejamento pedagógico do zero.',
                        'time' => 6,
                    ],
                    'usar-templates' => [
                        'title' => 'Usar Templates',
                        'summary' => 'Como escolher e personalizar templates prontos para agilizar seu planejamento.',
                        'time' => 5,
                    ],
                    'fluxo-status' => [
                        'title' => 'Fluxo de Status',
                        'summary' => 'Entenda os estados (rascunho, em revisão, aprovado) e como avançar entre eles.',
                        'time' => 3,
                    ],
                ],
            ],
            'cursos' => [
                'title' => 'Cursos',
                'description' => 'Crie cursos completos com módulos, seções, lições em vídeo e quizzes.',
                'icon' => 'fas fa-book',
                'color' => '#0d6efd',
                'articles' => [
                    'criar-curso' => [
                        'title' => 'Criar um Curso',
                        'summary' => 'Cadastre um novo curso com título, descrição, imagem de capa e configurações.',
                        'time' => 5,
                    ],
                    'modulos-licoes' => [
                        'title' => 'Módulos e Lições',
                        'summary' => 'Organize o conteúdo em seções, módulos e lições com vídeos do Vimeo.',
                        'time' => 7,
                    ],
                    'publicar-curso' => [
                        'title' => 'Publicar e Gerenciar',
                        'summary' => 'Como ativar, desativar e acompanhar o desempenho de um curso.',
                        'time' => 4,
                    ],
                ],
            ],
            'turmas' => [
                'title' => 'Turmas',
                'description' => 'Organize alunos em turmas e gerencie grupos de forma prática.',
                'icon' => 'fas fa-chalkboard',
                'color' => '#fd7e14',
                'articles' => [
                    'criar-turma' => [
                        'title' => 'Criar uma Turma',
                        'summary' => 'Como criar turmas, definir período e associar a escolas.',
                        'time' => 4,
                    ],
                    'gerenciar-alunos' => [
                        'title' => 'Gerenciar Alunos na Turma',
                        'summary' => 'Adicione e remova alunos, visualize listas e acompanhe a turma.',
                        'time' => 5,
                    ],
                ],
            ],
            'alunos' => [
                'title' => 'Alunos e Matrículas',
                'description' => 'Cadastre alunos, realize matrículas e acompanhe o progresso de cada um.',
                'icon' => 'fas fa-user-graduate',
                'color' => '#20c997',
                'articles' => [
                    'cadastrar-aluno' => [
                        'title' => 'Cadastrar Aluno',
                        'summary' => 'Registro de novos alunos no sistema com dados pessoais e escola.',
                        'time' => 3,
                    ],
                    'matricular' => [
                        'title' => 'Realizar Matrícula',
                        'summary' => 'Como matricular um aluno em um curso e ativar seu acesso.',
                        'time' => 4,
                    ],
                    'acompanhar-progresso' => [
                        'title' => 'Acompanhar Progresso',
                        'summary' => 'Veja o progresso geral, tempo assistido, quizzes e certificados.',
                        'time' => 5,
                    ],
                ],
            ],
            'relatorios' => [
                'title' => 'Relatórios e Análises',
                'description' => 'Acompanhe métricas, identifique alunos com dificuldade e analise dados de vídeo.',
                'icon' => 'fas fa-chart-bar',
                'color' => '#dc3545',
                'articles' => [
                    'relatorio-geral' => [
                        'title' => 'Relatório Geral',
                        'summary' => 'Visão consolidada de matrículas, progresso e desempenho nos quizzes.',
                        'time' => 4,
                    ],
                    'notas-baixas' => [
                        'title' => 'Alunos com Notas Baixas',
                        'summary' => 'Identifique alunos com dificuldade e tome ação para ajudá-los.',
                        'time' => 3,
                    ],
                    'tracking-video' => [
                        'title' => 'Tracking de Vídeo',
                        'summary' => 'Analise tempo assistido, sessões e engajamento dos alunos por vídeo.',
                        'time' => 5,
                    ],
                ],
            ],
        ];

        $this->faq = [
            [
                'question' => 'Como alterno entre modo claro e escuro?',
                'answer' => 'Clique no ícone de lua (<i class="fas fa-moon"></i>) na barra superior do painel. A preferência é salva automaticamente no seu navegador.',
            ],
            [
                'question' => 'Posso usar o sistema no celular?',
                'answer' => 'Sim! O painel é totalmente responsivo. No celular, toque no botão de menu (☰) no canto superior esquerdo para acessar a navegação.',
            ],
            [
                'question' => 'Como faço para matricular vários alunos de uma vez?',
                'answer' => 'Atualmente as matrículas são feitas individualmente. Acesse <strong>Ensino → Matrículas</strong>, selecione o aluno e o curso desejado.',
            ],
            [
                'question' => 'O que acontece quando um aluno completa um curso?',
                'answer' => 'O sistema marca o curso como concluído e o aluno pode gerar seu <strong>certificado digital</strong> com código de verificação único.',
            ],
            [
                'question' => 'Como os vídeos são rastreados?',
                'answer' => 'O sistema registra automaticamente o tempo assistido, posição do vídeo e sessões. Você pode ver tudo no <strong>Dashboard de Vídeos</strong>.',
            ],
            [
                'question' => 'Posso restaurar um planejamento excluído?',
                'answer' => 'Não, a exclusão é permanente. Recomendamos usar o status "Arquivado" em vez de excluir planejamentos.',
            ],
            [
                'question' => 'Como funciona o tour interativo?',
                'answer' => 'Clique no botão <strong>?</strong> na barra superior ou em "Tour desta Página" no menu lateral. Um guia visual vai destacar cada seção da página atual.',
            ],
            [
                'question' => 'Quem pode acessar o painel administrativo?',
                'answer' => 'Apenas usuários com papel de <strong>admin</strong> ou <strong>instructor</strong>. Alunos e responsáveis têm seus próprios painéis.',
            ],
            [
                'question' => 'Como vincular responsáveis a alunos?',
                'answer' => 'Acesse <strong>Cadastros → Responsáveis</strong>, selecione o responsável e use o botão "Vincular" para associar aos filhos cadastrados.',
            ],
            [
                'question' => 'Posso adicionar materiais de apoio aos cursos?',
                'answer' => 'Sim! Dentro da página de um curso, acesse a aba <strong>Materiais de Apoio</strong> para enviar PDFs, slides e outros arquivos.',
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
