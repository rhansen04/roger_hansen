/**
 * Tour: Planejamentos Pedagógicos
 */
window.helpTours = window.helpTours || {};
window.helpTours['planning'] = {
    steps: [
        {
            element: 'h2',
            popover: {
                title: 'Planejamentos Pedagógicos',
                description: 'Aqui você gerencia todos os planejamentos. Cada planejamento é baseado em um template e vinculado a uma turma e quinzena.',
                position: 'bottom'
            }
        },
        {
            element: 'a[href="/admin/planning/create"]',
            popover: {
                title: 'Novo Planejamento',
                description: 'Crie um novo planejamento escolhendo o template, turma e período da quinzena.',
                position: 'left'
            }
        },
        {
            element: 'form[action*="planning"]',
            popover: {
                title: 'Filtros',
                description: 'Filtre por professor, turma ou status (rascunho, enviado, registrado) para encontrar planejamentos específicos.',
                position: 'bottom'
            }
        },
        {
            element: '.table-hover',
            popover: {
                title: 'Lista de Planejamentos',
                description: 'Veja o template usado, professor, turma, quinzena e status atual. Os status indicam o fluxo: Rascunho → Enviado → Registrado.',
                position: 'top'
            }
        }
    ]
};

window.helpTours['planning-form'] = {
    steps: [
        {
            element: '#templateSelect',
            popover: {
                title: 'Escolha o Template',
                description: 'Selecione qual modelo de planejamento usar (PFI, PFII, etc.). O formulário será gerado automaticamente com as seções do template.',
                position: 'bottom'
            }
        },
        {
            element: 'select[name="classroom_id"]',
            popover: {
                title: 'Turma',
                description: 'Vincule este planejamento a uma turma específica.',
                position: 'bottom'
            }
        },
        {
            element: 'input[name="period_start"]',
            popover: {
                title: 'Período da Quinzena',
                description: 'Defina as datas de início e fim do período que este planejamento abrange.',
                position: 'bottom'
            }
        },
        {
            element: '.card-header.bg-white.fw-bold',
            popover: {
                title: 'Seções do Template',
                description: 'Cada card representa uma seção do template. Preencha os campos conforme orientação pedagógica.',
                position: 'bottom'
            }
        },
        {
            element: 'button[value="save"]',
            popover: {
                title: 'Salvar como Rascunho',
                description: 'Salve o planejamento como rascunho para continuar editando depois.',
                position: 'top'
            }
        },
        {
            element: 'button[value="submit"]',
            popover: {
                title: 'Enviar para Aprovação',
                description: 'Quando o planejamento estiver completo, envie para aprovação da coordenação.',
                position: 'top'
            }
        }
    ]
};
