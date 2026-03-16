<p class="lead">Ao clicar em um dia na grade quinzenal, o formulário do card diário é aberto. Aqui você preenche o planejamento específico daquele dia, com os campos definidos pelo template — cada dia tem suas respostas armazenadas de forma independente.</p>

<div class="help-tip">
    <i class="fas fa-lightbulb me-2"></i>
    <strong>Recurso R2-06:</strong> O card diário é o formulário individual de cada dia do planejamento. Ele exibe apenas as seções relevantes para o dia a dia, omitindo automaticamente a seção de Identificação (já definida na criação) e as seções de registro pós-vivência.
</div>

<h6 class="fw-bold mt-4 mb-3">Navegação e cabeçalho</h6>
<p>Ao abrir o formulário diário, o topo da página exibe:</p>
<ul>
    <li><strong>Breadcrumb:</strong> Dashboard &gt; Planejamentos &gt; Dias &gt; DD/MM/YYYY — permite voltar facilmente para qualquer nível</li>
    <li><strong>Cabeçalho do dia:</strong> nome completo do dia da semana (ex.: Segunda-feira, Terça-feira), data formatada, nome do template e turma vinculada</li>
</ul>

<div class="article-steps">
    <div class="article-step">
        <div class="step-number">1</div>
        <div class="step-content">
            <h6>Seções exibidas no formulário diário</h6>
            <p>O formulário diário exibe as seções do template com duas exclusões automáticas:</p>
            <ul>
                <li><strong>Seção "Identificação" é omitida:</strong> os dados de identificação (professor, turma, período, escola) já foram definidos no momento da criação do planejamento e não precisam ser repetidos a cada dia.</li>
                <li><strong>Seções de registro pós-vivência são omitidas:</strong> seções marcadas como <code>is_registration=1</code> no template aparecem apenas na etapa de Registro Pós-Vivência, após a finalização do planejamento.</li>
            </ul>
            <p>Todas as demais seções do template são exibidas na ordem definida, com seus respectivos campos.</p>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">2</div>
        <div class="step-content">
            <h6>Renomeação de rótulos</h6>
            <p>Para melhor adequação ao contexto diário, o sistema aplica a seguinte renomeação automática nos rótulos:</p>
            <ul>
                <li><strong>"Eixo de Vivências"</strong> ou <strong>"Eixo da Vivência"</strong> aparece como <strong>"Eixo de Atividades"</strong> no formulário diário</li>
                <li>Todos os demais rótulos de campos e seções permanecem iguais ao template original</li>
            </ul>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">3</div>
        <div class="step-content">
            <h6>Campos de seleção de eixo (radio como toggle buttons)</h6>
            <p>Campos do tipo <strong>radio</strong> usados para seleção de eixo são renderizados de forma diferenciada no card diário:</p>
            <ul>
                <li>Em vez de botões radio empilhados verticalmente, são exibidos como <strong>btn-group horizontal</strong> (toggle buttons)</li>
                <li>Cada opção aparece como um botão lado a lado, e ao clicar em um, ele fica visualmente selecionado</li>
                <li>Essa apresentação economiza espaço vertical e torna a seleção mais intuitiva e rápida</li>
            </ul>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">4</div>
        <div class="step-content">
            <h6>Campo "Palavra do dia"</h6>
            <p>O campo <strong>"Palavra do dia"</strong>, quando presente no template, é mantido como campo de texto simples (input text) no formulário diário. Não sofre nenhuma alteração de layout ou comportamento em relação ao template original.</p>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">5</div>
        <div class="step-content">
            <h6>Tipos de campo suportados</h6>
            <p>O formulário diário suporta todos os tipos de campo definidos no template:</p>
        </div>
    </div>
</div>

<div class="table-responsive">
    <table class="table table-bordered table-sm">
        <thead class="table-dark">
            <tr>
                <th>Tipo de campo</th>
                <th>Comportamento no card diário</th>
                <th>Exemplo</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>text</strong></td>
                <td>Campo de texto simples (uma linha)</td>
                <td>Palavra do dia, Tema da aula</td>
            </tr>
            <tr>
                <td><strong>textarea</strong></td>
                <td>Área de texto expansível (múltiplas linhas)</td>
                <td>Descrição da atividade, Objetivos</td>
            </tr>
            <tr>
                <td><strong>date</strong></td>
                <td>Seletor de data com calendário</td>
                <td>Data da atividade especial</td>
            </tr>
            <tr>
                <td><strong>select</strong></td>
                <td>Dropdown com opções predefinidas</td>
                <td>Espaço utilizado, Faixa etária</td>
            </tr>
            <tr>
                <td><strong>radio</strong></td>
                <td>Toggle buttons horizontais (btn-group) para eixos; radio padrão para demais</td>
                <td>Eixo de Atividades, Tipo de agrupamento</td>
            </tr>
            <tr>
                <td><strong>checkbox</strong></td>
                <td>Caixas de marcação independentes</td>
                <td>Materiais necessários, Recursos utilizados</td>
            </tr>
            <tr>
                <td><strong>checklist</strong></td>
                <td>Lista de itens com checkboxes agrupados</td>
                <td>Competências BNCC, Habilidades trabalhadas</td>
            </tr>
        </tbody>
    </table>
</div>

<div class="article-steps">
    <div class="article-step">
        <div class="step-number">6</div>
        <div class="step-content">
            <h6>Salvando o formulário diário</h6>
            <p>Ao terminar o preenchimento, clique no botão <strong>"Salvar"</strong> na parte inferior do formulário:</p>
            <ul>
                <li>As respostas são salvas <strong>exclusivamente para aquele dia</strong> — cada dia possui seu próprio registro no banco de dados (identificado por <code>daily_entry_id</code>)</li>
                <li>Alterar as respostas de um dia <strong>não afeta</strong> os demais dias do planejamento</li>
                <li>Após salvar, o sistema redireciona de volta para a <strong>grade de dias</strong> com uma mensagem de sucesso</li>
                <li>O status do card daquele dia é atualizado automaticamente na grade (de Vazio para Rascunho ou Preenchido)</li>
            </ul>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">7</div>
        <div class="step-content">
            <h6>Editando um dia já preenchido</h6>
            <p>Enquanto o planejamento estiver no status <span class="badge bg-warning text-dark">Rascunho</span>, você pode clicar em qualquer card (inclusive os já preenchidos) para editar as respostas. O formulário será carregado com os dados salvos anteriormente.</p>
            <p>Após a finalização (<span class="badge bg-primary">Enviado</span>), os dias ficam em modo somente leitura e não podem mais ser editados.</p>
        </div>
    </div>
</div>

<figure class="article-screenshot">
    <div class="screenshot-placeholder">
        <i class="fas fa-edit fa-3x" style="color:#6f42c1"></i>
        <p class="text-muted mt-2">Formulário do card diário com seções e campos do template</p>
    </div>
    <figcaption>Exemplo do formulário diário mostrando seções do template, toggle buttons para seleção de eixo e campo "Palavra do dia"</figcaption>
</figure>

<div class="help-tip">
    <i class="fas fa-lightbulb me-2"></i>
    <strong>Dica:</strong> Você pode preencher os dias na ordem que preferir. Não é necessário começar pela segunda-feira — comece pelo dia que você já tem mais clareza sobre a atividade planejada.
</div>

<div class="help-tip help-tip-success">
    <i class="fas fa-check-circle me-2"></i>
    <strong>Armazenamento independente:</strong> Cada dia gera um registro separado no banco de dados. Isso significa que mesmo que você preencha apenas 3 dos 10 dias, os dados desses 3 dias estão salvos e seguros. Você pode completar os demais em outro momento.
</div>
