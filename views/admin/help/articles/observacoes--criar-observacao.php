<p class="lead">As observações pedagógicas são registros detalhados do desenvolvimento de cada aluno, organizados em 6 eixos temáticos e separados por semestre. Aqui você aprende a criar, preencher e gerenciar suas observações.</p>

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
            <h6>Preencher os 6 eixos pedagógicos</h6>
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
            <p>Cada eixo contém um campo <strong>textarea</strong> com <strong>5 linhas visíveis</strong>, mas sem limite de texto. Clique na aba correspondente para navegar entre os eixos. Não é necessário preencher todos de uma vez — o auto-save permite preencher gradualmente.</p>
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

<div class="help-tip help-tip-success">
    <i class="fas fa-check-circle me-2"></i>
    <strong>Dica:</strong> Para uma explicação detalhada de cada eixo pedagógico e exemplos do que escrever, consulte o artigo <strong>"Eixos Pedagógicos — O que observar em cada eixo"</strong> na seção de ajuda.
</div>
