<p class="lead">O perfil do aluno é a página central onde todas as informações pedagógicas se encontram: dados pessoais, turma atual, observações registradas e acesso ao Resumo IA gerado por inteligência artificial.</p>

<h6 class="fw-bold mt-4 mb-3">Informações exibidas no perfil</h6>

<p>Ao acessar o perfil de um aluno, as seguintes informações são exibidas em destaque:</p>

<ul>
    <li><strong>Foto do aluno</strong> — exibida em tamanho destacado no topo do perfil. Caso o aluno não tenha foto cadastrada, um ícone placeholder (silhueta) é exibido no lugar</li>
    <li><strong>Nome completo</strong> — nome do aluno conforme cadastro</li>
    <li><strong>Data de nascimento</strong> — no formato dd/mm/aaaa</li>
    <li><strong>Idade</strong> — calculada automaticamente pelo sistema com base na data atual (ex: "4 anos e 7 meses")</li>
    <li><strong>Escola</strong> — escola à qual o aluno pertence</li>
    <li><strong>Turma atual</strong> — nome da turma ativa na qual o aluno está vinculado (obtida via tabela intermediária de matrículas). Se o aluno não estiver vinculado a nenhuma turma, exibe "Sem turma"</li>
    <li><strong>Professor(a)</strong> — nome do professor responsável pela turma atual do aluno</li>
</ul>

<h6 class="fw-bold mt-4 mb-3">Botões de ação do perfil</h6>

<p>O perfil do aluno oferece acesso rápido às principais funcionalidades pedagógicas através de botões de ação:</p>

<div class="article-steps">
    <div class="article-step">
        <div class="step-number"><i class="fas fa-clipboard-list"></i></div>
        <div class="step-content">
            <h6>Observações</h6>
            <p>Abre a listagem de observações pedagógicas do aluno. Permite visualizar todos os registros por semestre e ano, com acesso para criar novas observações ou editar as existentes.</p>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number"><i class="fas fa-file-alt"></i></div>
        <div class="step-content">
            <h6>Parecer Descritivo</h6>
            <p>Acessa os pareceres descritivos gerados para o aluno. O parecer é um documento formal com a avaliação pedagógica baseada nas observações registradas ao longo do semestre.</p>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number"><i class="fas fa-robot"></i></div>
        <div class="step-content">
            <h6>Resumo IA</h6>
            <p>O botão roxo <strong>"Resumo IA"</strong> aciona a inteligência artificial para gerar um resumo pedagógico narrativo do aluno. Veja detalhes abaixo.</p>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number"><i class="fas fa-edit"></i></div>
        <div class="step-content">
            <h6>Editar</h6>
            <p>Abre o formulário de edição do cadastro do aluno, permitindo alterar nome, data de nascimento, escola e foto.</p>
        </div>
    </div>
</div>

<h6 class="fw-bold mt-4 mb-3">Tabela de observações no perfil</h6>

<p>O perfil exibe uma tabela com todas as observações pedagógicas registradas para o aluno, contendo:</p>

<div class="table-responsive">
    <table class="table table-sm table-bordered">
        <thead class="table-light">
            <tr>
                <th>Coluna</th>
                <th>Descrição</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Data</strong></td>
                <td>Data em que a observação foi registrada ou atualizada pela última vez</td>
            </tr>
            <tr>
                <td><strong>Categoria</strong></td>
                <td>Badge colorido indicando a categoria da observação:
                    <ul class="mb-0 mt-1">
                        <li><span class="badge bg-primary">Comportamento</span> — azul</li>
                        <li><span class="badge bg-success">Aprendizado</span> — verde</li>
                        <li><span class="badge bg-danger">Saúde</span> — vermelho</li>
                        <li><span class="badge bg-warning text-dark">Comunicação</span> — amarelo</li>
                        <li><span class="badge bg-secondary">Geral</span> — cinza</li>
                    </ul>
                </td>
            </tr>
            <tr>
                <td><strong>Conteúdo</strong></td>
                <td>Preview (trecho inicial) do texto da observação, com link para ver o conteúdo completo</td>
            </tr>
            <tr>
                <td><strong>Professor</strong></td>
                <td>Nome do professor que registrou a observação</td>
            </tr>
        </tbody>
    </table>
</div>

<h6 class="fw-bold mt-4 mb-3">Resumo IA — Inteligência Artificial</h6>

<div class="article-steps">
    <div class="article-step">
        <div class="step-number">1</div>
        <div class="step-content">
            <h6>Solicitar o resumo</h6>
            <p>No perfil do aluno, clique no botão roxo <strong>"Resumo IA"</strong>. Um <strong>modal</strong> será aberto exibindo um <strong>spinner de carregamento</strong> enquanto a inteligência artificial processa as informações.</p>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">2</div>
        <div class="step-content">
            <h6>Processamento pela IA</h6>
            <p>O sistema envia <strong>todas as observações pedagógicas</strong> do aluno para a <strong>API do Google Gemini</strong>. A IA analisa o conjunto completo de registros e gera uma <strong>narrativa pedagógica</strong> coesa e detalhada sobre o desenvolvimento do aluno.</p>
            <p>O resumo inclui análises sobre:</p>
            <ul>
                <li>Padrões de comportamento e socialização</li>
                <li>Progressos na aprendizagem ao longo do tempo</li>
                <li>Pontos fortes e áreas que precisam de atenção</li>
                <li>Recomendações pedagógicas baseadas nos registros</li>
            </ul>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">3</div>
        <div class="step-content">
            <h6>Visualizar e copiar o resultado</h6>
            <p>O texto gerado pela IA é exibido no modal em formato legível. O conteúdo é <strong>copiável</strong> — utilize o botão de copiar ou selecione o texto manualmente para colar em documentos, relatórios ou pareceres descritivos.</p>
        </div>
    </div>
</div>

<div class="help-tip">
    <i class="fas fa-lightbulb me-2"></i>
    <strong>Dica:</strong> Quanto mais observações registradas para o aluno, mais rico e detalhado será o resumo gerado pela IA. Incentive os professores a registrarem observações frequentes e diversificadas nos diferentes eixos pedagógicos.
</div>

<div class="help-tip help-tip-success">
    <i class="fas fa-check-circle me-2"></i>
    <strong>Privacidade:</strong> O Resumo IA é gerado sob demanda e não é armazenado permanentemente. Cada vez que o botão é clicado, um novo resumo é gerado com base nas observações mais atuais do aluno.
</div>
