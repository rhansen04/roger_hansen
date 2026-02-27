/**
 * Tour: Gestão de Cursos (Enriched)
 */
window.helpTours = window.helpTours || {};
window.helpTours['courses'] = {
    steps: [
        {
            element: 'h2',
            popover: {
                title: '📚 Gestão de Cursos',
                description: '<p>Nesta página você gerencia todos os cursos da plataforma.</p><ul><li>Criar e editar cursos</li><li>Organizar seções, módulos e lições</li><li>Ativar/desativar cursos</li><li>Acompanhar matrículas</li></ul>',
                position: 'bottom'
            }
        },
        {
            element: 'a[href="/admin/courses/create"]',
            popover: {
                title: '➕ Criar Novo Curso',
                description: '<p>Inicie a criação de um novo curso definindo:</p><ul><li><strong>Título e descrição</strong></li><li><strong>Imagem de capa</strong></li><li><strong>Instrutor responsável</strong></li><li><strong>Gratuito ou pago</strong></li></ul><p>💡 <em>Após criar, adicione seções, módulos e lições na página do curso.</em></p>',
                position: 'left'
            }
        },
        {
            element: '#courseSearch',
            popover: {
                title: '🔍 Busca Rápida',
                description: '<p>Digite o nome do curso ou instrutor para filtrar a lista instantaneamente.</p><p>💡 <em>A busca filtra enquanto você digita — sem precisar apertar Enter.</em></p>',
                position: 'bottom'
            }
        },
        {
            element: '.table-hover',
            popover: {
                title: '📋 Lista de Cursos',
                description: '<p>Visão geral de todos os cursos com:</p><ul><li><strong>Seções/Lições</strong> — quantidade de conteúdo</li><li><strong>Alunos</strong> — total matriculados</li><li><strong>Status</strong> — ativo ou inativo</li><li><strong>Ações</strong> — ver, editar, excluir</li></ul>',
                position: 'top'
            }
        },
        {
            element: '.btn-group',
            popover: {
                title: '⚙️ Ações do Curso',
                description: '<p>Cada curso tem botões de ação:</p><ul><li>👁️ <strong>Visualizar</strong> — ver detalhes, módulos e lições</li><li>✏️ <strong>Editar</strong> — alterar informações</li><li>🗑️ <strong>Excluir</strong> — remover (com confirmação)</li></ul>',
                position: 'left'
            }
        },
        {
            popover: {
                title: '✅ Tour Concluído!',
                description: '<p>Para um guia completo sobre criação de cursos, acesse a <a href="/admin/help/cursos/criar-curso" style="color:#007e66;font-weight:bold">Central de Ajuda</a>.</p>'
            }
        }
    ]
};
