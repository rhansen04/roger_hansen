<p class="lead">O sistema de observações possui um controle de permissões por perfil de usuário. Cada papel (Professor, Coordenador e Admin) tem acessos específicos para garantir a integridade e a organização dos registros pedagógicos.</p>

<h6 class="fw-bold mt-4 mb-3">Matriz de permissões</h6>

<p>A tabela abaixo detalha exatamente quais ações cada perfil pode executar no módulo de observações:</p>

<div class="table-responsive">
    <table class="table table-sm table-bordered text-center">
        <thead class="table-light">
            <tr>
                <th class="text-start">Ação</th>
                <th><i class="fas fa-chalkboard-teacher me-1"></i>Professor</th>
                <th><i class="fas fa-user-tie me-1"></i>Coordenador</th>
                <th><i class="fas fa-user-shield me-1"></i>Admin</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="text-start"><strong>Criar observação</strong></td>
                <td><span class="text-success"><i class="fas fa-check-circle"></i></span> Seus alunos</td>
                <td><span class="text-success"><i class="fas fa-check-circle"></i></span> Qualquer aluno</td>
                <td><span class="text-success"><i class="fas fa-check-circle"></i></span> Qualquer aluno</td>
            </tr>
            <tr>
                <td class="text-start"><strong>Visualizar observações</strong></td>
                <td><span class="text-success"><i class="fas fa-check-circle"></i></span> Apenas próprias</td>
                <td><span class="text-success"><i class="fas fa-check-circle"></i></span> Todas</td>
                <td><span class="text-success"><i class="fas fa-check-circle"></i></span> Todas</td>
            </tr>
            <tr>
                <td class="text-start"><strong>Editar observação "Em andamento"</strong></td>
                <td><span class="text-success"><i class="fas fa-check-circle"></i></span> Apenas próprias</td>
                <td><span class="text-danger"><i class="fas fa-times-circle"></i></span></td>
                <td><span class="text-success"><i class="fas fa-check-circle"></i></span> Qualquer uma</td>
            </tr>
            <tr>
                <td class="text-start"><strong>Auto-save</strong></td>
                <td><span class="text-success"><i class="fas fa-check-circle"></i></span> Próprias</td>
                <td><span class="text-danger"><i class="fas fa-times-circle"></i></span></td>
                <td><span class="text-success"><i class="fas fa-check-circle"></i></span> Qualquer uma</td>
            </tr>
            <tr>
                <td class="text-start"><strong>Finalizar observação</strong></td>
                <td><span class="text-success"><i class="fas fa-check-circle"></i></span> Apenas próprias</td>
                <td><span class="text-danger"><i class="fas fa-times-circle"></i></span></td>
                <td><span class="text-success"><i class="fas fa-check-circle"></i></span> Qualquer uma</td>
            </tr>
            <tr>
                <td class="text-start"><strong>Reabrir observação finalizada</strong></td>
                <td><span class="text-danger"><i class="fas fa-times-circle"></i></span></td>
                <td><span class="text-success"><i class="fas fa-check-circle"></i></span> Qualquer uma</td>
                <td><span class="text-success"><i class="fas fa-check-circle"></i></span> Qualquer uma</td>
            </tr>
            <tr>
                <td class="text-start"><strong>Excluir observação (rascunho)</strong></td>
                <td><span class="text-success"><i class="fas fa-check-circle"></i></span> Apenas próprias</td>
                <td><span class="text-danger"><i class="fas fa-times-circle"></i></span></td>
                <td><span class="text-success"><i class="fas fa-check-circle"></i></span> Qualquer uma</td>
            </tr>
            <tr>
                <td class="text-start"><strong>Filtrar por professor</strong></td>
                <td><span class="text-danger"><i class="fas fa-times-circle"></i></span></td>
                <td><span class="text-success"><i class="fas fa-check-circle"></i></span></td>
                <td><span class="text-success"><i class="fas fa-check-circle"></i></span></td>
            </tr>
        </tbody>
    </table>
</div>

<hr class="my-4">

<h6 class="fw-bold mt-4 mb-3">Detalhes por perfil</h6>

<div class="article-steps">
    <div class="article-step">
        <div class="step-number"><i class="fas fa-chalkboard-teacher"></i></div>
        <div class="step-content">
            <h6>Professor</h6>
            <p>O professor é o principal autor das observações pedagógicas. Suas permissões são focadas nos alunos de suas próprias turmas:</p>
            <ul>
                <li><strong>Criar observações</strong> — pode registrar observações para os alunos vinculados às suas turmas</li>
                <li><strong>Editar observações em andamento</strong> — pode alterar textos dos eixos pedagógicos apenas em observações que ele mesmo criou e que ainda estejam com status "Em andamento"</li>
                <li><strong>Auto-save</strong> — funciona normalmente durante a edição de suas próprias observações</li>
                <li><strong>Finalizar</strong> — pode concluir suas próprias observações, tornando-as somente leitura</li>
                <li><strong>Excluir rascunhos</strong> — pode excluir observações em andamento que ele criou, desde que ainda não tenham sido finalizadas</li>
                <li><strong>Restrição de visibilidade</strong> — <strong>não pode ver</strong> observações criadas por outros professores. Cada professor visualiza apenas seus próprios registros</li>
            </ul>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number"><i class="fas fa-user-tie"></i></div>
        <div class="step-content">
            <h6>Coordenador</h6>
            <p>O coordenador tem papel de supervisão pedagógica e também pode criar observações para qualquer aluno:</p>
            <ul>
                <li><strong>Criar observações</strong> — pode registrar observações para qualquer aluno de qualquer turma</li>
                <li><strong>Visualizar todas as observações</strong> — acesso completo de leitura a todas as observações de todos os professores</li>
                <li><strong>Filtrar por professor</strong> — pode selecionar um professor específico no filtro para ver apenas as observações daquele professor</li>
                <li><strong>Reabrir observações finalizadas</strong> — sua ação exclusiva mais importante (veja detalhes abaixo)</li>
                <li><strong>Não pode editar</strong> — o coordenador não modifica textos dos eixos, garantindo que o registro reflita a percepção original do professor</li>
            </ul>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number"><i class="fas fa-user-shield"></i></div>
        <div class="step-content">
            <h6>Admin</h6>
            <p>O administrador tem acesso total ao sistema de observações:</p>
            <ul>
                <li><strong>Criar</strong> — pode criar observações para qualquer aluno de qualquer turma</li>
                <li><strong>Editar</strong> — pode alterar textos de qualquer observação, independente de quem a criou</li>
                <li><strong>Finalizar</strong> — pode finalizar qualquer observação em andamento</li>
                <li><strong>Reabrir</strong> — pode reabrir qualquer observação finalizada</li>
                <li><strong>Excluir</strong> — pode excluir qualquer observação, em qualquer status</li>
                <li><strong>Filtrar</strong> — acesso a todos os filtros, incluindo filtro por professor</li>
            </ul>
        </div>
    </div>
</div>

<hr class="my-4">

<h6 class="fw-bold mt-4 mb-3">Fluxo de reabertura de observação</h6>

<p>A reabertura é um recurso exclusivo do <strong>Coordenador</strong> e do <strong>Admin</strong>, utilizado quando uma observação finalizada precisa de correções ou complementos.</p>

<div class="article-steps">
    <div class="article-step">
        <div class="step-number">1</div>
        <div class="step-content">
            <h6>Coordenador identifica a necessidade</h6>
            <p>Ao revisar as observações finalizadas, o coordenador identifica que determinada observação precisa de ajustes — seja por informação incompleta, erro de digitação ou necessidade de complemento pedagógico.</p>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">2</div>
        <div class="step-content">
            <h6>Clicar em "Reabrir"</h6>
            <p>Na observação finalizada, o coordenador clica no botão <strong>"Reabrir"</strong>. Um diálogo de confirmação é exibido para evitar reaberturas acidentais.</p>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">3</div>
        <div class="step-content">
            <h6>Observação retorna a "Em andamento"</h6>
            <p>Após a confirmação, o sistema:</p>
            <ul>
                <li>Altera o status de volta para <span class="badge bg-warning text-dark">Em andamento</span></li>
                <li>Reabilita a edição de todos os campos de texto</li>
                <li>Reativa o auto-save</li>
                <li>Remove o botão "Gerar Parecer Descritivo" (até nova finalização)</li>
            </ul>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">4</div>
        <div class="step-content">
            <h6>Professor edita e finaliza novamente</h6>
            <p>O professor responsável pela observação pode agora acessá-la, fazer as correções necessárias e <strong>finalizar novamente</strong> quando estiver satisfeito com o resultado.</p>
        </div>
    </div>
</div>

<div class="help-tip">
    <i class="fas fa-lightbulb me-2"></i>
    <strong>Dica:</strong> A comunicação entre coordenador e professor é essencial nesse processo. Ao reabrir uma observação, informe o professor sobre quais pontos precisam de atenção para que a correção seja feita de forma objetiva.
</div>

<div class="help-tip help-tip-success">
    <i class="fas fa-check-circle me-2"></i>
    <strong>Segurança:</strong> Todas as ações de finalização e reabertura são registradas com data e hora no sistema, permitindo rastreabilidade completa do histórico de alterações de status.
</div>
