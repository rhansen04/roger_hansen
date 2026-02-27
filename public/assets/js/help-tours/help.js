/**
 * Tour: Central de Ajuda
 */
window.helpTours = window.helpTours || {};
window.helpTours['help'] = {
    steps: [
        {
            element: '.help-hero',
            popover: {
                title: '🔍 Busca Rápida',
                description: '<p>Digite qualquer palavra-chave para filtrar artigos, categorias e perguntas frequentes em tempo real.</p><p>💡 <em>Experimente digitar "curso", "matrícula" ou "planejamento".</em></p>',
                position: 'bottom'
            }
        },
        {
            element: '#categoryGrid',
            popover: {
                title: '📂 Categorias',
                description: '<p>Todo o conteúdo de ajuda organizado por tema:</p><ul><li>Cada card mostra a quantidade de artigos disponíveis</li><li>Clique para ver os artigos da categoria</li></ul>',
                position: 'top'
            }
        },
        {
            element: '#faqAccordion',
            popover: {
                title: '❓ Perguntas Frequentes',
                description: '<p>Respostas rápidas para as dúvidas mais comuns. Clique em uma pergunta para expandir a resposta.</p>',
                position: 'top'
            }
        },
        {
            popover: {
                title: '✅ Pronto!',
                description: '<p>Use a Central de Ajuda sempre que tiver dúvidas. Os artigos incluem passo a passo detalhados com dicas práticas.</p>'
            }
        }
    ]
};
