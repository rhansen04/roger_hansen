/**
 * Tour: Templates de Planejamento (Enriched)
 */
window.helpTours = window.helpTours || {};
window.helpTours['planning-templates'] = {
    steps: [
        {
            element: 'h2',
            popover: {
                title: '📄 Templates de Planejamento',
                description: '<p>Templates são modelos reutilizáveis para planejamentos pedagógicos.</p><ul><li>Defina <strong>seções</strong> (ex: Campos de Experiência, Objetivos)</li><li>Dentro de cada seção, crie <strong>campos</strong> personalizados</li><li>Os professores preenchem os campos ao criar um planejamento</li></ul>',
                position: 'bottom'
            }
        },
        {
            element: 'a[href="/admin/planning-templates/create"]',
            popover: {
                title: '➕ Criar Novo Template',
                description: '<p>Defina um template com:</p><ul><li><strong>Nome</strong> — ex: "PFI Berçário", "PFII Maternal"</li><li><strong>Faixa etária</strong> — público-alvo</li><li><strong>Seções e campos</strong> — estrutura do formulário</li></ul><p>💡 <em>Templates bem estruturados economizam tempo dos professores.</em></p>',
                position: 'left'
            }
        },
        {
            element: '.col-md-4',
            popover: {
                title: '🗂️ Card do Template',
                description: '<p>Cada card exibe:</p><ul><li><strong>Nome</strong> e <strong>status</strong> (ativo/inativo)</li><li><strong>Descrição</strong> e <strong>faixa etária</strong></li><li>Botões para <strong>editar</strong> ou <strong>excluir</strong></li></ul><p>💡 <em>Templates inativos não aparecem na seleção ao criar planejamentos.</em></p>',
                position: 'right'
            }
        },
        {
            popover: {
                title: '✅ Tour Concluído!',
                description: '<p>Para um guia completo sobre templates, acesse a <a href="/admin/help/planejamento/usar-templates" style="color:#007e66;font-weight:bold">Central de Ajuda</a>.</p>'
            }
        }
    ]
};

window.helpTours['planning-templates-form'] = {
    steps: [
        {
            element: 'input[name="name"]',
            popover: {
                title: '✏️ Nome do Template',
                description: '<p>Dê um nome descritivo e claro:</p><ul><li>"Planejamento PFI - Berçário"</li><li>"PFII - Maternal II"</li><li>"Plano Semanal - Pré-Escola"</li></ul>',
                position: 'bottom'
            }
        },
        {
            element: 'select[name="age_group"]',
            popover: {
                title: '👶 Faixa Etária',
                description: '<p>Selecione a faixa etária para a qual este template se destina. Isso ajuda os professores a encontrarem o template correto.</p>',
                position: 'bottom'
            }
        },
        {
            element: '#sections-container',
            popover: {
                title: '📝 Seções do Template',
                description: '<p>Construa a estrutura do planejamento:</p><ul><li><strong>Adicione seções</strong> — cada uma agrupa campos relacionados</li><li><strong>Dentro das seções</strong>, defina campos (texto, data, seleção, checkbox)</li><li>Arraste para <strong>reordenar</strong></li></ul><p>💡 <em>Pense na estrutura como o "esqueleto" que os professores irão preencher.</em></p>',
                position: 'top'
            }
        }
    ]
};
