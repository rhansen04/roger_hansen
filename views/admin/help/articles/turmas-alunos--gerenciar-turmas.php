<p class="lead">Turmas organizam os alunos por faixa etária, período e professor. Aqui você aprende a criar, editar, ativar/desativar turmas e visualizar os alunos matriculados em cada uma.</p>

<div class="article-steps">
    <div class="article-step">
        <div class="step-number">1</div>
        <div class="step-content">
            <h6>Criar uma nova turma</h6>
            <p>Acesse <strong>Cadastros → Turmas</strong> e clique no botão <strong>"Nova Turma"</strong>. Preencha todos os campos obrigatórios do formulário:</p>
            <ul>
                <li><strong>Nome da Turma</strong> — identificação da turma (ex: "Turma Girassol", "Maternal II A")</li>
                <li><strong>Escola</strong> — selecione a escola no dropdown. Somente escolas cadastradas e ativas aparecerão na lista</li>
                <li><strong>Professor(a)</strong> — selecione o professor responsável no dropdown. A lista é composta por usuários com perfil de professor cadastrados no sistema</li>
                <li><strong>Faixa Etária</strong> — escolha entre <strong>0-3 anos</strong> ou <strong>3-6 anos</strong>. Essa classificação segue as diretrizes da educação infantil</li>
                <li><strong>Período</strong> — selecione <strong>Manhã</strong>, <strong>Tarde</strong> ou <strong>Integral</strong>, conforme o funcionamento da turma</li>
                <li><strong>Ano Letivo</strong> — informe o ano letivo correspondente (ex: 2026)</li>
                <li><strong>Status</strong> — a turma é criada como <strong>Ativa</strong> por padrão</li>
            </ul>
            <p>Após preencher, clique em <strong>"Salvar"</strong>. A turma aparecerá na listagem principal.</p>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">2</div>
        <div class="step-content">
            <h6>Editar informações da turma</h6>
            <p>Na listagem de turmas, clique no botão <strong>"Editar"</strong> (ícone de lápis) ao lado da turma desejada. Você pode alterar:</p>
            <ul>
                <li><strong>Nome da turma</strong> — renomear conforme necessidade (ex: trocar de "Maternal I" para "Maternal I - Tarde")</li>
                <li><strong>Professor responsável</strong> — trocar o professor entre períodos ou anos letivos</li>
                <li><strong>Período</strong> — ajustar o turno caso haja mudança na organização escolar</li>
                <li><strong>Faixa etária e Escola</strong> — podem ser ajustados se houve erro no cadastro inicial</li>
            </ul>
            <p>As alterações são salvas imediatamente ao clicar em <strong>"Salvar"</strong>. O histórico de observações e pareceres dos alunos vinculados não é afetado.</p>

            <h6 class="fw-bold mt-4 mb-3">Acesso rápido aos alunos na edição</h6>

            <p>Ao editar uma turma, o formulário agora exibe um card informativo com o botão <strong>"Gerenciar Alunos"</strong>. Este botão direciona para a página de detalhes da turma, onde você pode:</p>
            <ul>
                <li>Ver a lista completa de alunos vinculados (com foto, nome, idade)</li>
                <li>Adicionar novos alunos à turma</li>
                <li>Remover alunos da turma</li>
                <li>Acessar o perfil individual de cada aluno</li>
            </ul>

            <div class="help-tip">
                <i class="fas fa-lightbulb me-2"></i>
                <strong>Dica:</strong> Na listagem de turmas, clicar no <strong>nome da turma</strong> também leva à página de detalhes com a lista de alunos.
            </div>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">3</div>
        <div class="step-content">
            <h6>Ativar e desativar turmas</h6>
            <p>Turmas <strong>nunca são excluídas</strong> do sistema — apenas desativadas. Isso garante a preservação completa do histórico pedagógico (observações, pareceres descritivos e portfólios).</p>
            <ul>
                <li>Na listagem de turmas, localize a turma desejada</li>
                <li>Clique no botão de <strong>alternância (toggle)</strong> na coluna de status</li>
                <li>Turma <strong>ativa</strong>: aparece normalmente nos dropdowns e pode receber alunos</li>
                <li>Turma <strong>inativa</strong>: fica oculta nos dropdowns de seleção, mas permanece acessível na listagem com filtro. Todos os dados vinculados são preservados integralmente</li>
            </ul>
            <p>Ao final de um ano letivo, desative as turmas antigas e crie novas para o próximo ano. Os registros históricos continuarão disponíveis para consulta.</p>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">4</div>
        <div class="step-content">
            <h6>Visualizar detalhes da turma e alunos matriculados</h6>
            <p>Clique no <strong>nome da turma</strong> na listagem para acessar a página de detalhes. Nesta página você verá:</p>
            <ul>
                <li><strong>Informações gerais</strong> — nome, escola, professor, faixa etária, período e ano letivo</li>
                <li><strong>Lista de alunos matriculados</strong> — exibida em formato de tabela contendo:
                    <ul>
                        <li><strong>#</strong> — coluna numerada com a posição do aluno na lista, facilitando a contagem e referência rápida</li>
                        <li><strong>Foto</strong> do aluno (ou ícone placeholder caso não tenha foto cadastrada)</li>
                        <li><strong>Nome completo</strong> do aluno</li>
                        <li><strong>Data de nascimento</strong></li>
                        <li><strong>Idade</strong> — calculada automaticamente pelo sistema com base na data de nascimento</li>
                        <li><strong>Ações</strong> — botões para acessar o perfil do aluno ou removê-lo da turma</li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</div>

<div class="help-tip">
    <i class="fas fa-lightbulb me-2"></i>
    <strong>Dica:</strong> Na listagem de turmas, cada turma exibe um <strong>badge com o número de alunos</strong> matriculados. Isso permite visualizar rapidamente quais turmas estão cheias ou com vagas disponíveis sem precisar abrir cada uma individualmente.
</div>

<div class="help-tip help-tip-success">
    <i class="fas fa-check-circle me-2"></i>
    <strong>Rota de acesso:</strong> A listagem completa de turmas está disponível em <code>/admin/classrooms</code>. Use o menu lateral <strong>Cadastros → Turmas</strong> para navegar diretamente.
</div>
