<p class="lead">O painel do Administrador foi reestruturado em dois níveis de navegação: módulos globais sempre disponíveis e um contexto de escola que filtra todos os dados de uma escola específica quando ativado.</p>

<div class="help-tip">
    <i class="fas fa-lightbulb me-2"></i>
    <strong>Por que isso importa?</strong> Ao administrar uma plataforma com várias escolas, o contexto de escola permite trabalhar focado nos dados de uma única instituição, sem misturar turmas, alunos e documentos de escolas diferentes.
</div>

<h5 class="mt-4 mb-3"><i class="fas fa-th-large me-2 text-primary"></i>Módulos Globais do Admin</h5>

<p>Independente do contexto de escola ativo, três módulos globais estão sempre acessíveis no menu do administrador:</p>

<div class="table-responsive">
    <table class="table table-bordered table-sm">
        <thead class="table-light">
            <tr>
                <th>Módulo Global</th>
                <th>O que gerencia</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Escolas</strong></td>
                <td>Cadastro, ativação/desativação e configurações de todas as escolas da plataforma</td>
            </tr>
            <tr>
                <td><strong>Usuários</strong></td>
                <td>Criação, edição e gerenciamento de todas as contas (Admins, Professores, Coordenadores, Alunos)</td>
            </tr>
            <tr>
                <td><strong>Cursos + Material de Apoio</strong></td>
                <td>Gestão de cursos de formação, matrículas e biblioteca digital de materiais pedagógicos</td>
            </tr>
        </tbody>
    </table>
</div>

<h5 class="mt-4 mb-3"><i class="fas fa-school me-2 text-success"></i>Navegação Contextual por Escola</h5>

<div class="article-steps">
    <div class="article-step">
        <div class="step-number">1</div>
        <div class="step-content">
            <h6>Acesse a listagem de Escolas</h6>
            <p>No menu lateral do admin, clique em <strong>Escolas</strong>. A listagem exibe todas as escolas cadastradas com suas informações básicas (nome, status, contrato).</p>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">2</div>
        <div class="step-content">
            <h6>Clique em "Entrar no Ambiente"</h6>
            <p>Em cada linha da listagem de escolas, há o botão <strong>"Entrar no Ambiente"</strong>. Ao clicar:</p>
            <ul>
                <li>A escola é definida como o <strong>contexto ativo</strong> para a sessão atual</li>
                <li>O menu lateral do admin é expandido com um <strong>submenu filtrado</strong> por aquela escola</li>
                <li>Um indicador no topo do menu mostra o nome da escola ativa no contexto</li>
            </ul>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">3</div>
        <div class="step-content">
            <h6>Submenu filtrado pela escola</h6>
            <p>Com o contexto de escola ativo, o submenu exibe os seguintes módulos — todos mostrando <strong>apenas os dados daquela escola</strong>:</p>
            <ul>
                <li><strong>Turmas</strong> — apenas as turmas dessa escola</li>
                <li><strong>Alunos</strong> — apenas os alunos dessa escola</li>
                <li><strong>Observações</strong> — observações das turmas dessa escola</li>
                <li><strong>Pareceres</strong> — pareceres das crianças dessa escola</li>
                <li><strong>Planejamentos</strong> — planejamentos dos professores dessa escola</li>
                <li><strong>Portfólios</strong> — portfólios das turmas dessa escola</li>
                <li><strong>Banco de Imagens</strong> — acervo fotográfico das turmas dessa escola</li>
            </ul>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">4</div>
        <div class="step-content">
            <h6>Sair do contexto da escola</h6>
            <p>Para voltar à visão global (sem filtro por escola), clique no botão <strong>"Sair do Ambiente"</strong> no indicador de contexto no menu lateral.</p>
            <ul>
                <li>O submenu filtrado é removido</li>
                <li>Os módulos globais continuam disponíveis normalmente</li>
                <li>Você pode então entrar no ambiente de uma escola diferente</li>
            </ul>
        </div>
    </div>
</div>

<div class="help-tip">
    <i class="fas fa-lightbulb me-2"></i>
    <strong>Dica:</strong> Use o contexto de escola ao fazer um acompanhamento específico de uma instituição — por exemplo, ao preparar relatórios, revisar documentos ou apoiar a equipe pedagógica de uma escola específica.
</div>

<h6 class="fw-bold mt-4 mb-3">Visão geral da estrutura do menu Admin</h6>

<div class="d-flex flex-column gap-2 p-3" style="background: #f8f9fa; border-radius: 12px;">
    <div class="d-flex align-items-center gap-2">
        <i class="fas fa-globe text-primary"></i>
        <strong>Módulos Globais (sempre visíveis)</strong>
    </div>
    <ul class="mb-0 ms-4">
        <li>Escolas &nbsp;<span class="badge bg-secondary">Global</span></li>
        <li>Usuários &nbsp;<span class="badge bg-secondary">Global</span></li>
        <li>Cursos + Material de Apoio &nbsp;<span class="badge bg-secondary">Global</span></li>
    </ul>

    <div class="d-flex align-items-center gap-2 mt-2">
        <i class="fas fa-school text-success"></i>
        <strong>Contexto de Escola (ao clicar em "Entrar no Ambiente")</strong>
    </div>
    <ul class="mb-0 ms-4">
        <li>Turmas da escola &nbsp;<span class="badge bg-success">Filtrado</span></li>
        <li>Alunos da escola &nbsp;<span class="badge bg-success">Filtrado</span></li>
        <li>Observações da escola &nbsp;<span class="badge bg-success">Filtrado</span></li>
        <li>Pareceres da escola &nbsp;<span class="badge bg-success">Filtrado</span></li>
        <li>Planejamentos da escola &nbsp;<span class="badge bg-success">Filtrado</span></li>
        <li>Portfólios da escola &nbsp;<span class="badge bg-success">Filtrado</span></li>
        <li>Banco de Imagens da escola &nbsp;<span class="badge bg-success">Filtrado</span></li>
    </ul>
</div>

<div class="help-tip help-tip-success">
    <i class="fas fa-check-circle me-2"></i>
    <strong>Dica:</strong> Os módulos globais (Escolas, Usuários, Cursos) continuam acessíveis mesmo com um contexto de escola ativo — você nunca perde acesso às funções globais.
</div>
