/**
 * Tour: Planejamentos Pedagógicos (Enriched)
 */
window.helpTours = window.helpTours || {};
window.helpTours['planning'] = {
    steps: [
        {
            element: 'h2',
            popover: {
                title: '📅 Planejamentos Pedagógicos',
                description: '<p>Gerencie todos os planejamentos pedagógicos da instituição.</p><ul><li>Cada planejamento é baseado em um <strong>template</strong></li><li>Vinculado a uma <strong>turma</strong> e <strong>período</strong></li><li>Segue um fluxo de <strong>status</strong>: Rascunho → Enviado → Registrado</li></ul>',
                position: 'bottom'
            }
        },
        {
            element: 'a[href="/admin/planning/create"]',
            popover: {
                title: '➕ Novo Planejamento',
                description: '<p>Crie um novo planejamento em 3 passos:</p><ul><li><strong>1.</strong> Escolha o template (PFI, PFII, etc.)</li><li><strong>2.</strong> Selecione turma e período</li><li><strong>3.</strong> Preencha as seções geradas</li></ul><p>💡 <em>O template define automaticamente as seções e campos do formulário.</em></p>',
                position: 'left'
            }
        },
        {
            element: 'form[action*="planning"]',
            popover: {
                title: '🔍 Filtros',
                description: '<p>Encontre planejamentos específicos filtrando por:</p><ul><li><strong>Professor</strong> — quem criou</li><li><strong>Turma</strong> — turma vinculada</li><li><strong>Status</strong> — rascunho, enviado ou registrado</li></ul>',
                position: 'bottom'
            }
        },
        {
            element: '.table-hover',
            popover: {
                title: '📋 Lista de Planejamentos',
                description: '<p>Cada linha mostra:</p><ul><li><strong>Template</strong> — modelo utilizado</li><li><strong>Professor</strong> — autor do planejamento</li><li><strong>Turma</strong> — contexto escolar</li><li><strong>Quinzena</strong> — período de aplicação</li><li><strong>Status</strong> — etapa atual no fluxo</li></ul>',
                position: 'top'
            }
        },
        {
            popover: {
                title: '✅ Tour Concluído!',
                description: '<p>Para um guia detalhado, visite a <a href="/admin/help/planejamento/criar-planejamento" style="color:#007e66;font-weight:bold">Central de Ajuda — Criar Planejamento</a>.</p>'
            }
        }
    ]
};

window.helpTours['planning-form'] = {
    steps: [
        {
            element: '#templateSelect',
            popover: {
                title: '📋 Escolha o Template',
                description: '<p>Selecione qual modelo de planejamento usar (PFI, PFII, etc.).</p><p>💡 <em>O formulário será gerado automaticamente com as seções e campos do template escolhido.</em></p>',
                position: 'bottom'
            }
        },
        {
            element: 'select[name="classroom_id"]',
            popover: {
                title: '🏫 Turma',
                description: '<p>Vincule este planejamento a uma turma específica. Isso ajuda a organizar e filtrar os planejamentos.</p>',
                position: 'bottom'
            }
        },
        {
            element: 'input[name="period_start"]',
            popover: {
                title: '📆 Período da Quinzena',
                description: '<p>Defina as datas de início e fim do período que este planejamento abrange.</p><p>💡 <em>Geralmente corresponde a uma quinzena letiva.</em></p>',
                position: 'bottom'
            }
        },
        {
            element: '.card-header.bg-white.fw-bold',
            popover: {
                title: '📝 Seções do Template',
                description: '<p>Cada card representa uma seção do template:</p><ul><li>Preencha os campos de texto, seleção e checkboxes</li><li>As seções variam conforme o template escolhido</li></ul>',
                position: 'bottom'
            }
        },
        {
            element: 'button[value="save"]',
            popover: {
                title: '💾 Salvar como Rascunho',
                description: '<p>Salve para continuar editando depois. Rascunhos não são visíveis para a coordenação.</p>',
                position: 'top'
            }
        },
        {
            element: 'button[value="submit"]',
            popover: {
                title: '📤 Enviar para Aprovação',
                description: '<p>Quando o planejamento estiver completo, envie para que a coordenação revise e aprove.</p><p>💡 <em>Após envio, o status muda para "Enviado" e pode ser aprovado pela coordenação.</em></p>',
                position: 'top'
            }
        }
    ]
};
