<p class="lead">Vincular alunos a turmas é o processo de matrícula dentro do sistema. Cada aluno pode estar em apenas uma turma ativa por vez, e o vínculo pode ser desfeito sem excluir nenhum dado.</p>

<div class="article-steps">
    <div class="article-step">
        <div class="step-number">1</div>
        <div class="step-content">
            <h6>Acessar a página da turma</h6>
            <p>Navegue até <strong>Cadastros → Turmas</strong> e clique no <strong>nome da turma</strong> na qual deseja adicionar alunos. Você será direcionado para a página de detalhes da turma, acessível pela rota <code>/admin/classrooms/{id}</code>.</p>
            <p>Nesta página você verá as informações da turma (nome, escola, professor, período) e a lista de alunos atualmente vinculados.</p>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">2</div>
        <div class="step-content">
            <h6>Adicionar aluno à turma</h6>
            <p>Clique no botão <strong>"Adicionar Aluno"</strong> localizado acima da lista de alunos. Um <strong>modal</strong> será exibido contendo:</p>
            <ul>
                <li><strong>Dropdown de alunos disponíveis</strong> — a lista mostra apenas alunos que:
                    <ul>
                        <li>Pertencem à <strong>mesma escola</strong> da turma</li>
                        <li><strong>Não estão vinculados</strong> a nenhuma outra turma ativa no momento</li>
                    </ul>
                </li>
                <li>Selecione o aluno desejado no dropdown</li>
                <li>Clique em <strong>"Adicionar"</strong> para confirmar o vínculo</li>
            </ul>
            <p>O modal será fechado e o aluno aparecerá imediatamente na lista de alunos da turma.</p>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">3</div>
        <div class="step-content">
            <h6>Verificar o aluno na lista</h6>
            <p>Após a vinculação, o aluno aparece na tabela de alunos da turma com as seguintes informações:</p>
            <ul>
                <li><strong>Foto</strong> — miniatura da foto do aluno (ou ícone de placeholder se não houver foto cadastrada)</li>
                <li><strong>Nome completo</strong> — nome do aluno conforme cadastro</li>
                <li><strong>Data de nascimento</strong> — exibida no formato dd/mm/aaaa</li>
                <li><strong>Idade</strong> — calculada automaticamente pelo sistema em anos e meses (ex: "4 anos e 3 meses")</li>
                <li><strong>Ações</strong> — botões para acessar o perfil ou remover o vínculo</li>
            </ul>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">4</div>
        <div class="step-content">
            <h6>Remover aluno da turma</h6>
            <p>Para desvincular um aluno da turma:</p>
            <ul>
                <li>Localize o aluno na lista de alunos da turma</li>
                <li>Clique no botão <strong>"Remover"</strong> (ícone de X ou lixeira) na coluna de ações</li>
                <li>Um <strong>diálogo de confirmação</strong> será exibido: "Tem certeza que deseja remover este aluno da turma?"</li>
                <li>Confirme a remoção clicando em <strong>"Sim, remover"</strong></li>
            </ul>
            <p>O aluno será <strong>desvinculado</strong> da turma, mas <strong>não será excluído</strong> do sistema. Seus dados cadastrais, observações e pareceres permanecem intactos. O aluno ficará disponível para ser vinculado a outra turma.</p>
        </div>
    </div>
</div>

<div class="help-tip">
    <i class="fas fa-exclamation-circle me-2"></i>
    <strong>Importante:</strong> Cada aluno pode estar vinculado a <strong>apenas uma turma ativa</strong> por vez. Se precisar transferir um aluno para outra turma, primeiro remova-o da turma atual e depois adicione-o à nova turma.
</div>

<div class="help-tip help-tip-success">
    <i class="fas fa-check-circle me-2"></i>
    <strong>Nota técnica:</strong> O vínculo entre alunos e turmas é registrado na tabela intermediária <code>classroom_students</code>. Essa tabela armazena o histórico de matrículas, garantindo rastreabilidade mesmo após a remoção do vínculo.
</div>

<h6 class="fw-bold mt-4 mb-3">Resumo do fluxo de vinculação</h6>

<div class="table-responsive">
    <table class="table table-sm table-bordered">
        <thead class="table-light">
            <tr>
                <th>Ação</th>
                <th>Onde</th>
                <th>Resultado</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Adicionar aluno</strong></td>
                <td>Página da turma → botão "Adicionar Aluno"</td>
                <td>Aluno vinculado à turma e visível na lista</td>
            </tr>
            <tr>
                <td><strong>Remover aluno</strong></td>
                <td>Página da turma → botão "Remover" na linha do aluno</td>
                <td>Vínculo desfeito, aluno disponível para outra turma</td>
            </tr>
            <tr>
                <td><strong>Transferir aluno</strong></td>
                <td>Remover da turma atual + adicionar à nova turma</td>
                <td>Aluno muda de turma mantendo todos os registros</td>
            </tr>
        </tbody>
    </table>
</div>
