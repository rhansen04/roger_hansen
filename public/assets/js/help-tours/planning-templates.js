/**
 * Tour: Templates de Planejamento
 */
window.helpTours = window.helpTours || {};
window.helpTours['planning-templates'] = {
    steps: [
        {
            element: 'h2',
            popover: {
                title: 'Templates de Planejamento',
                description: 'Templates são modelos reutilizáveis para planejamentos pedagógicos. Defina as seções e campos que os professores deverão preencher.',
                position: 'bottom'
            }
        },
        {
            element: 'a[href="/admin/planning-templates/create"]',
            popover: {
                title: 'Criar Novo Template',
                description: 'Crie um template definindo nome, faixa etária e as seções com seus campos (texto, data, seleção, etc.).',
                position: 'left'
            }
        },
        {
            element: '.col-md-4',
            popover: {
                title: 'Card do Template',
                description: 'Cada card mostra o nome, status (ativo/inativo), descrição e faixa etária do template. Use os botões para editar ou excluir.',
                position: 'right'
            }
        }
    ]
};

window.helpTours['planning-templates-form'] = {
    steps: [
        {
            element: 'input[name="name"]',
            popover: {
                title: 'Nome do Template',
                description: 'Dê um nome descritivo, como "Planejamento PFI - Berçário" ou "PFII - Maternal".',
                position: 'bottom'
            }
        },
        {
            element: 'select[name="age_group"]',
            popover: {
                title: 'Faixa Etária',
                description: 'Selecione a faixa etária para a qual este template se destina.',
                position: 'bottom'
            }
        },
        {
            element: '#sections-container',
            popover: {
                title: 'Seções do Template',
                description: 'Adicione seções (ex: "Campos de Experiência", "Objetivos") e dentro de cada seção, defina os campos que o professor deverá preencher.',
                position: 'top'
            }
        }
    ]
};
