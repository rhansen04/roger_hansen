/**
 * Tour: Dashboard Admin
 */
window.helpTours = window.helpTours || {};
window.helpTours['dashboard'] = {
    steps: [
        {
            element: '.navbar',
            popover: {
                title: 'Barra Superior',
                description: 'Aqui você vê quem está logado, pode alternar o modo escuro e acessar o site público.',
                position: 'bottom'
            }
        },
        {
            element: '.row.g-3.mb-3',
            popover: {
                title: 'Resumo Geral',
                description: 'Cards com os números principais: total de alunos, matrículas ativas, cursos disponíveis e contatos recebidos.',
                position: 'bottom'
            }
        },
        {
            element: '.col-lg-8',
            popover: {
                title: 'Tabelas e Dados',
                description: 'Matrículas recentes, cursos mais populares, contatos e observações — tudo em um só lugar.',
                position: 'right'
            }
        },
        {
            element: '.col-lg-4',
            popover: {
                title: 'Ações Rápidas',
                description: 'Atalhos para criar alunos, cursos, matrículas e outras ações frequentes.',
                position: 'left'
            }
        },
        {
            element: '#sidebar',
            popover: {
                title: 'Menu Lateral',
                description: 'Navegue pelas seções: Cadastros, Ensino, Comunicação e Análises. Use a seção "Ajuda" no final para tours e documentação.',
                position: 'right'
            }
        }
    ]
};
