/**
 * Tour: Dashboard Admin (Enriched)
 */
window.helpTours = window.helpTours || {};
window.helpTours['dashboard'] = {
    steps: [
        {
            element: '.navbar',
            popover: {
                title: '🧭 Barra Superior',
                description: '<p>Sua central de controle rápido:</p><ul><li><strong>Usuário logado</strong> — veja seu nome e papel</li><li><strong>Botão ?</strong> — inicia este tour interativo</li><li><strong>🌙 Modo escuro</strong> — alterne o tema visual</li><li><strong>Ver Site Público</strong> — abre o site em nova aba</li></ul><p>💡 <em>O modo escuro é salvo automaticamente no navegador.</em></p>',
                position: 'bottom'
            }
        },
        {
            element: '.row.g-3.mb-3',
            popover: {
                title: '📊 Resumo Geral',
                description: '<p>Cards com as métricas mais importantes do sistema:</p><ul><li><strong>Alunos</strong> — total cadastrados</li><li><strong>Matrículas</strong> — ativas no momento</li><li><strong>Cursos</strong> — disponíveis no catálogo</li><li><strong>Contatos</strong> — mensagens recebidas</li></ul><p>💡 <em>Esses números são atualizados em tempo real a cada acesso.</em></p>',
                position: 'bottom'
            }
        },
        {
            element: '.col-lg-8',
            popover: {
                title: '📋 Tabelas e Dados',
                description: '<p>Informações detalhadas organizadas em tabelas:</p><ul><li><strong>Matrículas recentes</strong> — últimas 8 com progresso</li><li><strong>Cursos populares</strong> — ranking por matrículas</li><li><strong>Alunos com baixo progresso</strong> — alertas de atenção</li><li><strong>Observações recentes</strong> — últimos registros</li></ul>',
                position: 'right'
            }
        },
        {
            element: '.col-lg-4',
            popover: {
                title: '⚡ Ações Rápidas',
                description: '<p>Atalhos para as tarefas mais comuns:</p><ul><li>Cadastrar novo aluno</li><li>Criar novo curso</li><li>Realizar matrícula</li><li>Ver relatórios</li></ul><p>💡 <em>Use estes atalhos para economizar tempo no dia a dia.</em></p>',
                position: 'left'
            }
        },
        {
            element: '#sidebar',
            popover: {
                title: '📌 Menu Lateral',
                description: '<p>Navegação principal organizada por seções:</p><ul><li><strong>Cadastros</strong> — Alunos, Escolas, Usuários</li><li><strong>Ensino</strong> — Cursos, Matrículas, Planejamentos</li><li><strong>Comunicação</strong> — Contatos e Perguntas</li><li><strong>Análises</strong> — Vídeos e Relatórios</li><li><strong>Ajuda</strong> — Tours e Central de Ajuda</li></ul><p>💡 <em>A página ativa é destacada com borda amarela.</em></p>',
                position: 'right'
            }
        },
        {
            popover: {
                title: '✅ Tour Concluído!',
                description: '<p>Agora você conhece o Dashboard! Explore as outras seções pelo menu lateral.</p><p>💡 <em>Cada página tem seu próprio tour — clique no botão <strong>?</strong> para descobrir.</em></p><p>📚 Para guias detalhados, acesse a <a href="/admin/help" style="color:#007e66;font-weight:bold">Central de Ajuda</a>.</p>'
            }
        }
    ]
};
