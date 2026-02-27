/**
 * Tour: Gestão de Cursos
 */
window.helpTours = window.helpTours || {};
window.helpTours['courses'] = {
    steps: [
        {
            element: 'h2',
            popover: {
                title: 'Gestão de Cursos',
                description: 'Nesta página você gerencia todos os cursos da plataforma: cria, edita, ativa/desativa e organiza módulos e lições.',
                position: 'bottom'
            }
        },
        {
            element: 'a[href="/admin/courses/create"]',
            popover: {
                title: 'Criar Novo Curso',
                description: 'Clique aqui para criar um novo curso. Você definirá título, descrição, instrutor, preço e imagem de capa.',
                position: 'left'
            }
        },
        {
            element: '#courseSearch',
            popover: {
                title: 'Busca Rápida',
                description: 'Digite o nome do curso ou instrutor para filtrar a lista instantaneamente.',
                position: 'bottom'
            }
        },
        {
            element: '.table-hover',
            popover: {
                title: 'Lista de Cursos',
                description: 'Veja todos os cursos com quantidade de seções, lições, alunos matriculados, status e preço. Use os botões de ação para visualizar, editar ou excluir.',
                position: 'top'
            }
        },
        {
            element: '.btn-group',
            popover: {
                title: 'Ações do Curso',
                description: 'Visualizar detalhes, editar informações ou excluir o curso. Ao visualizar, você acessa os módulos e lições.',
                position: 'left'
            }
        }
    ]
};
