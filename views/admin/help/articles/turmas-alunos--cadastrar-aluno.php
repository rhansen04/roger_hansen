<p class="lead">O cadastro de alunos é o primeiro passo para começar a utilizar o sistema pedagógico. Aqui você registra os dados básicos do aluno, envia sua foto e o deixa pronto para ser vinculado a uma turma.</p>

<div class="article-steps">
    <div class="article-step">
        <div class="step-number">1</div>
        <div class="step-content">
            <h6>Acessar o formulário de novo aluno</h6>
            <p>Navegue pelo menu lateral até <strong>Cadastros → Alunos</strong>. Na listagem de alunos, clique no botão <strong>"Novo Aluno"</strong> no canto superior direito da página.</p>
            <p>O formulário de cadastro será exibido com todos os campos necessários para o registro.</p>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">2</div>
        <div class="step-content">
            <h6>Preencher os dados do aluno</h6>
            <p>O formulário contém os seguintes campos:</p>
            <ul>
                <li><strong>Nome Completo</strong> <span class="text-danger">*</span> — nome completo do aluno, sem abreviações. Este nome será exibido em todas as telas, relatórios e pareceres descritivos</li>
                <li><strong>Data de Nascimento</strong> <span class="text-danger">*</span> — selecione a data no formato dd/mm/aaaa. Assim que preenchida, o sistema <strong>calcula automaticamente a idade</strong> do aluno em anos e meses, exibindo ao lado do campo</li>
                <li><strong>Escola</strong> <span class="text-danger">*</span> — selecione a escola no dropdown. Somente escolas ativas cadastradas no sistema aparecerão na lista. Este campo é importante porque, ao vincular o aluno a uma turma, apenas turmas da mesma escola serão oferecidas</li>
            </ul>
            <p>Os campos marcados com <span class="text-danger">*</span> são obrigatórios e o formulário não poderá ser salvo sem preenchê-los.</p>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">3</div>
        <div class="step-content">
            <h6>Enviar foto do aluno</h6>
            <p>O campo de foto permite upload de uma imagem do aluno para facilitar a identificação visual em turmas e perfis:</p>
            <ul>
                <li><strong>Formatos aceitos:</strong> JPG e PNG</li>
                <li><strong>Tamanho máximo:</strong> 2 MB por arquivo</li>
                <li>Ao selecionar a imagem, um <strong>preview</strong> (pré-visualização) é exibido imediatamente no formulário para que você confirme que a foto está correta</li>
                <li>A foto é armazenada no diretório <code>/uploads/students/</code> do servidor</li>
                <li>Se nenhuma foto for enviada, o sistema utilizará um <strong>ícone placeholder</strong> (silhueta genérica) em todas as telas</li>
            </ul>
            <p>A foto pode ser substituída a qualquer momento editando o cadastro do aluno posteriormente.</p>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">4</div>
        <div class="step-content">
            <h6>Salvar o cadastro</h6>
            <p>Após preencher todos os campos obrigatórios e (opcionalmente) anexar a foto, clique em <strong>"Salvar"</strong>.</p>
            <ul>
                <li>O aluno será registrado no sistema e aparecerá na <strong>listagem de alunos</strong></li>
                <li>A partir deste momento, o aluno estará <strong>disponível para ser vinculado a turmas</strong> da mesma escola</li>
                <li>O aluno também poderá receber observações pedagógicas e pareceres descritivos</li>
            </ul>
        </div>
    </div>
</div>

<div class="help-tip">
    <i class="fas fa-exclamation-circle me-2"></i>
    <strong>Proteção de dados:</strong> Um aluno <strong>não pode ser excluído</strong> do sistema se possuir observações registradas. Essa regra de integridade garante que nenhum registro pedagógico seja perdido acidentalmente. Para alunos que deixaram a escola, basta removê-los da turma — seus dados permanecem no sistema para consulta futura.
</div>

<h6 class="fw-bold mt-4 mb-3">Editar um aluno existente</h6>

<p>Na listagem de alunos (<strong>Cadastros → Alunos</strong>), clique no botão <strong>"Editar"</strong> (ícone de lápis) ao lado do aluno. Você pode alterar qualquer campo, incluindo substituir a foto. As alterações refletem automaticamente em todas as turmas e observações vinculadas.</p>

<div class="help-tip help-tip-success">
    <i class="fas fa-check-circle me-2"></i>
    <strong>Dica:</strong> Ao cadastrar muitos alunos, organize-se preenchendo todos os alunos de uma mesma escola antes de passar para a próxima. Assim, o campo "Escola" permanecerá o mesmo e o processo será mais ágil.
</div>

<h6 class="fw-bold mt-4 mb-3">Resumo dos campos do cadastro</h6>

<div class="table-responsive">
    <table class="table table-sm table-bordered">
        <thead class="table-light">
            <tr>
                <th>Campo</th>
                <th>Tipo</th>
                <th>Obrigatório</th>
                <th>Observação</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Nome Completo</strong></td>
                <td>Texto</td>
                <td>Sim</td>
                <td>Sem abreviações, nome completo do aluno</td>
            </tr>
            <tr>
                <td><strong>Data de Nascimento</strong></td>
                <td>Data</td>
                <td>Sim</td>
                <td>Idade calculada automaticamente pelo sistema</td>
            </tr>
            <tr>
                <td><strong>Escola</strong></td>
                <td>Dropdown</td>
                <td>Sim</td>
                <td>Filtra turmas disponíveis para vinculação</td>
            </tr>
            <tr>
                <td><strong>Foto</strong></td>
                <td>Upload (JPG/PNG)</td>
                <td>Não</td>
                <td>Máx. 2 MB, preview exibido antes de salvar</td>
            </tr>
        </tbody>
    </table>
</div>
