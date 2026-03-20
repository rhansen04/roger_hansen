<p class="lead">As observações pedagógicas são registros detalhados do desenvolvimento de cada aluno, organizados em 6 eixos temáticos e separados por semestre. Aqui você aprende a criar, preencher e gerenciar suas observações.</p>

<div class="help-tip help-tip-success">
    <i class="fas fa-check-circle me-2"></i>
    <strong>Novidade v4:</strong> As observações agora exibem as perguntas de cada eixo de forma <strong>numerada</strong>, e cada pergunta possui seu <strong>próprio campo de resposta obrigatório</strong>. Isso garante um registro estruturado e completo por eixo.
</div>

<h6 class="fw-bold mt-4 mb-3">Atalhos rápidos: Cards dos 6 Eixos</h6>

<p>Na listagem de observações (<strong>Observações</strong> no menu), você encontra <strong>6 cards coloridos</strong> representando os eixos pedagógicos. Ao clicar em um card, o formulário de nova observação abre com a aba daquele eixo já ativa:</p>

<div class="table-responsive">
    <table class="table table-sm table-bordered">
        <thead class="table-light">
            <tr><th>Card</th><th>Cor</th><th>Eixo Ativado</th></tr>
        </thead>
        <tbody>
            <tr><td><i class="fas fa-file-alt me-1"></i> Obs. Geral</td><td>Cinza</td><td>Aba "Observação Geral"</td></tr>
            <tr><td><i class="fas fa-running me-1"></i> Movimento</td><td>Vermelho</td><td>Aba "Movimento"</td></tr>
            <tr><td><i class="fas fa-hands me-1"></i> Manual</td><td>Laranja</td><td>Aba "Manual"</td></tr>
            <tr><td><i class="fas fa-music me-1"></i> Musical</td><td>Roxo</td><td>Aba "Musical"</td></tr>
            <tr><td><i class="fas fa-book-open me-1"></i> Contos</td><td>Azul</td><td>Aba "Contos"</td></tr>
            <tr><td><i class="fas fa-comments me-1"></i> PCA</td><td>Verde</td><td>Aba "Comunicação Ativa"</td></tr>
        </tbody>
    </table>
</div>

<div class="help-tip">
    <i class="fas fa-lightbulb me-2"></i>
    <strong>Dica:</strong> Os cards servem como atalho visual. Você também pode usar o botão <strong>"Nova Observação"</strong> normalmente — a diferença é que os cards já abrem na aba correta.
</div>

<div class="help-tip help-tip-success">
    <i class="fas fa-check-circle me-2"></i>
    <strong>Novidade v2.1:</strong> Os textos dos eixos preenchidos na criação agora são <strong>salvos automaticamente</strong> junto com o registro. Você não precisa mais salvar primeiro e depois editar — tudo é gravado de uma vez.
</div>

<div class="article-steps">
    <div class="article-step">
        <div class="step-number">1</div>
        <div class="step-content">
            <h6>Acessar o formulário de nova observação</h6>
            <p>Existem duas formas de criar uma nova observação:</p>
            <ul>
                <li><strong>Via menu:</strong> Acesse <strong>Pedagógico → Observações</strong> e clique no botão <strong>"Nova Observação"</strong></li>
                <li><strong>Via perfil do aluno:</strong> No perfil do aluno, clique no botão <strong>"Observações"</strong> e depois em <strong>"Nova Observação"</strong></li>
            </ul>
            <p>Ambos os caminhos levam ao mesmo formulário de criação.</p>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">2</div>
        <div class="step-content">
            <h6>Selecionar aluno, semestre e ano</h6>
            <p>O formulário inicial solicita três informações essenciais:</p>
            <ul>
                <li><strong>Aluno</strong> — selecione o aluno no dropdown. Se você acessou via perfil do aluno, este campo já estará preenchido automaticamente</li>
                <li><strong>Semestre</strong> — escolha entre <strong>1º Semestre</strong> ou <strong>2º Semestre</strong>. Cada semestre representa um período distinto de avaliação pedagógica</li>
                <li><strong>Ano</strong> — informe o ano letivo correspondente (ex: 2026)</li>
            </ul>
            <p>Esses três campos juntos identificam unicamente a observação no sistema.</p>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">3</div>
        <div class="step-content">
            <h6>Verificação de duplicidade</h6>
            <p>O sistema verifica automaticamente se já existe uma observação registrada para a combinação <strong>aluno + semestre + ano</strong> selecionada.</p>
            <ul>
                <li>Se <strong>já existir</strong> uma observação: o sistema exibirá um alerta e impedirá a criação duplicada, direcionando você para editar a observação existente</li>
                <li>Se <strong>não existir</strong>: o formulário completo com os eixos pedagógicos será exibido para preenchimento</li>
            </ul>
            <p>Essa regra garante que exista no máximo <strong>uma observação por aluno por semestre por ano</strong>, evitando duplicidade de registros.</p>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">4</div>
        <div class="step-content">
            <h6>Preencher os 6 eixos pedagógicos — Perguntas Numeradas</h6>
            <p>O formulário de observação é organizado em <strong>6 abas</strong> (Bootstrap tabs), cada uma representando um eixo pedagógico:</p>
            <ol>
                <li>
                    <strong>Observação Geral</strong>
                    <p class="mb-1">Notas gerais sobre o comportamento, socialização, estado emocional e desenvolvimento global da criança no período.</p>
                </li>
                <li>
                    <strong>Eixo de Movimento</strong>
                    <p class="mb-1">Observações sobre atividades de movimento: corrida, salto, equilíbrio, consciência espacial e desenvolvimento motor amplo.</p>
                </li>
                <li>
                    <strong>Eixo Manual (Atividade Manual)</strong>
                    <p class="mb-1">Registro sobre habilidades motoras finas: desenho, pintura, recorte, modelagem, colagem e atividades de construção.</p>
                </li>
                <li>
                    <strong>Eixo Musical (Atividade Musical)</strong>
                    <p class="mb-1">Observações sobre expressão musical: ritmo, canto, exploração de instrumentos, escuta ativa e movimento com música.</p>
                </li>
                <li>
                    <strong>Eixo de Contos (Atividade de Contos)</strong>
                    <p class="mb-1">Notas sobre engajamento com histórias: compreensão narrativa, imaginação, dramatização e reconto de histórias.</p>
                </li>
                <li>
                    <strong>PCA (Programa Comunicação Ativa)</strong>
                    <p class="mb-1">Registros sobre comunicação ativa: expressão verbal, desenvolvimento de vocabulário, habilidades de conversa e comunicação não-verbal.</p>
                </li>
            </ol>

            <p class="mt-3"><strong>Como as perguntas funcionam (novidade v4):</strong></p>
            <p>Cada eixo exibe suas perguntas orientadoras de forma <strong>numerada e expandida</strong>, diferente da versão anterior onde as perguntas eram colapsáveis. Agora:</p>
            <ul>
                <li>Cada pergunta possui seu <strong>próprio campo de resposta</strong> (textarea dedicado)</li>
                <li>Os campos são <strong>obrigatórios</strong> — é necessário preencher ao menos as perguntas do eixo para salvar</li>
                <li>A numeração (1, 2, 3...) facilita a referência e o acompanhamento de cada aspecto pedagógico</li>
                <li>O <strong>auto-save</strong> continua funcionando: cada campo é salvo automaticamente ao sair do campo ou após pausa na digitação</li>
            </ul>
        </div>
    </div>
</div>

<div class="help-tip">
    <i class="fas fa-lightbulb me-2"></i>
    <strong>Dica:</strong> Você não precisa preencher todos os 6 eixos em uma única sessão. Graças ao <strong>auto-save</strong>, seus textos são salvos automaticamente conforme você digita. Volte ao formulário quantas vezes precisar para completar os eixos ao longo do semestre.
</div>

<h6 class="fw-bold mt-4 mb-3">Status da observação</h6>

<p>Toda observação começa com o status <strong>"Em andamento"</strong>, indicando que ainda está em preenchimento. Enquanto estiver nesse status, o professor pode editar livremente os textos de todos os eixos.</p>

<ul>
    <li><span class="badge bg-warning text-dark">Em andamento</span> — observação em edição, auto-save ativo, textos podem ser alterados</li>
    <li><span class="badge bg-success">Finalizada</span> — observação concluída, campos somente leitura (veja o artigo sobre finalização)</li>
</ul>

<h6 class="fw-bold mt-4 mb-3">Filtros na listagem de observações</h6>

<p>Na listagem de observações (<strong>Pedagógico → Observações</strong>), utilize os filtros disponíveis para localizar registros rapidamente:</p>

<div class="table-responsive">
    <table class="table table-sm table-bordered">
        <thead class="table-light">
            <tr>
                <th>Filtro</th>
                <th>Descrição</th>
                <th>Exemplo</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Aluno</strong></td>
                <td>Filtra observações de um aluno específico</td>
                <td>Selecionar "Maria Silva"</td>
            </tr>
            <tr>
                <td><strong>Semestre</strong></td>
                <td>Exibe apenas observações do semestre selecionado</td>
                <td>1º Semestre ou 2º Semestre</td>
            </tr>
            <tr>
                <td><strong>Ano</strong></td>
                <td>Filtra por ano letivo</td>
                <td>2026</td>
            </tr>
            <tr>
                <td><strong>Status</strong></td>
                <td>Filtra por status da observação</td>
                <td>Em andamento ou Finalizada</td>
            </tr>
        </tbody>
    </table>
</div>

<h6 class="fw-bold mt-4 mb-3">Feedbacks da Coordenação</h6>

<p>Ao visualizar uma observação, o coordenador pode registrar feedbacks diretamente na página da observação. Para o professor, essa seção aparece logo abaixo do conteúdo da observação:</p>

<ul>
    <li><strong>Quem pode registrar:</strong> Coordenadores e Administradores</li>
    <li><strong>Visibilidade para o professor:</strong> os feedbacks ficam visíveis ao professor que criou a observação</li>
    <li><strong>Notificação automática:</strong> quando um coordenador registra um feedback, o professor recebe uma notificação interna com o aviso</li>
    <li><strong>Histórico:</strong> os feedbacks são exibidos em ordem cronológica com data, hora e nome do coordenador</li>
</ul>

<div class="help-tip">
    <i class="fas fa-lightbulb me-2"></i>
    <strong>Dica para coordenadores:</strong> Use os feedbacks para orientar o professor de forma construtiva sem precisar solicitar uma revisão formal do documento. É uma comunicação direta e rápida sobre pontos específicos da observação.
</div>

<div class="help-tip help-tip-success">
    <i class="fas fa-check-circle me-2"></i>
    <strong>Dica:</strong> Para uma explicação detalhada de cada eixo pedagógico e exemplos do que escrever, consulte o artigo <strong>"Eixos Pedagógicos — O que observar em cada eixo"</strong> na seção de ajuda.
</div>
