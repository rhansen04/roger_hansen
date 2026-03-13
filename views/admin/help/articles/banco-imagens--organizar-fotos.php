<p class="lead">O Banco de Imagens organiza as fotos pedagógicas por turma e por aluno, criando automaticamente uma estrutura de pastas que facilita o registro visual do dia a dia escolar.</p>

<div class="help-tip">
    <i class="fas fa-lightbulb me-2"></i>
    <strong>Importante:</strong> O sistema cria as pastas automaticamente ao acessar a turma pela primeira vez. Não é necessário criar pastas manualmente — a função <code>ensureFoldersForClassroom()</code> cuida disso.
</div>

<h5 class="mt-4 mb-3"><i class="fas fa-sitemap me-2 text-primary"></i>Estrutura de navegação</h5>
<p>O Banco de Imagens segue uma hierarquia de três níveis: <strong>Turmas → Pastas → Fotos</strong>. Cada nível apresenta uma visualização em grade (grid) para facilitar a navegação.</p>

<div class="article-steps">
    <div class="article-step">
        <div class="step-number">1</div>
        <div class="step-content">
            <h6>Acesse o Banco de Imagens</h6>
            <p>No menu lateral, navegue até <strong>Pedagógico → Banco de Imagens</strong>. A tela inicial exibe um grid com todas as turmas ativas do ano letivo corrente.</p>
            <ul>
                <li>Cada card de turma mostra o <strong>nome da turma</strong> e a quantidade de fotos armazenadas</li>
                <li><strong>Professor:</strong> visualiza apenas as turmas em que está vinculado como professor titular</li>
                <li><strong>Coordenador:</strong> visualiza todas as turmas, porém em modo somente leitura (sem permissão para upload, edição ou exclusão)</li>
                <li><strong>Administrador:</strong> visualiza todas as turmas com acesso completo</li>
            </ul>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">2</div>
        <div class="step-content">
            <h6>Clique em uma turma para ver suas pastas</h6>
            <p>Ao clicar no card de uma turma, o sistema exibe as pastas disponíveis. As pastas são criadas automaticamente pela função <code>ensureFoldersForClassroom()</code> na primeira vez que a turma é acessada.</p>
            <ul>
                <li><strong>Pasta "Registros Coletivos":</strong> pasta do tipo <code>classroom</code>, destinada a fotos coletivas da turma como um todo — atividades em grupo, eventos, decoração de sala, passeios</li>
                <li><strong>Pastas individuais por aluno:</strong> pastas do tipo <code>student</code>, uma para cada aluno matriculado na turma. O nome da pasta é o nome do aluno</li>
                <li>Se novos alunos forem matriculados na turma, as pastas correspondentes são criadas automaticamente no próximo acesso ao banco de imagens da turma</li>
                <li>As pastas são exibidas em ordem alfabética, com a pasta "Registros Coletivos" sempre aparecendo em primeiro lugar</li>
            </ul>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">3</div>
        <div class="step-content">
            <h6>Clique em uma pasta para ver as fotos</h6>
            <p>Dentro de cada pasta, as fotos são exibidas em um grid de miniaturas (thumbnails). Cada miniatura apresenta:</p>
            <ul>
                <li><strong>Imagem reduzida:</strong> preview da foto em tamanho miniatura para visualização rápida</li>
                <li><strong>Nome do arquivo:</strong> exibido abaixo da miniatura</li>
                <li><strong>Data de upload:</strong> data e hora em que a foto foi enviada ao sistema</li>
                <li><strong>Legenda:</strong> texto descritivo da foto (editável pelo professor)</li>
                <li>Clique na miniatura para abrir a foto em tamanho completo em um lightbox</li>
            </ul>
        </div>
    </div>
</div>

<h5 class="mt-4 mb-3"><i class="fas fa-folder-open me-2 text-primary"></i>Tipos de pasta</h5>

<div class="table-responsive">
    <table class="table table-sm table-bordered">
        <thead class="table-light">
            <tr>
                <th>Tipo</th>
                <th>Identificador</th>
                <th>Finalidade</th>
                <th>Criação</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Coletiva</strong></td>
                <td><code>classroom</code></td>
                <td>Fotos da turma como um todo: atividades coletivas, eventos, ambientação</td>
                <td>Automática (1 por turma)</td>
            </tr>
            <tr>
                <td><strong>Individual</strong></td>
                <td><code>student</code></td>
                <td>Fotos individuais de cada aluno matriculado na turma</td>
                <td>Automática (1 por aluno)</td>
            </tr>
        </tbody>
    </table>
</div>

<h5 class="mt-4 mb-3"><i class="fas fa-user-shield me-2 text-primary"></i>Permissões por perfil</h5>

<div class="table-responsive">
    <table class="table table-sm table-bordered">
        <thead class="table-light">
            <tr>
                <th>Perfil</th>
                <th>Visualizar</th>
                <th>Upload</th>
                <th>Editar legenda</th>
                <th>Mover / Excluir</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Professor</strong></td>
                <td class="text-success"><i class="fas fa-check"></i> Suas turmas</td>
                <td class="text-success"><i class="fas fa-check"></i></td>
                <td class="text-success"><i class="fas fa-check"></i></td>
                <td class="text-success"><i class="fas fa-check"></i></td>
            </tr>
            <tr>
                <td><strong>Coordenador</strong></td>
                <td class="text-success"><i class="fas fa-check"></i> Todas</td>
                <td class="text-danger"><i class="fas fa-times"></i></td>
                <td class="text-danger"><i class="fas fa-times"></i></td>
                <td class="text-danger"><i class="fas fa-times"></i></td>
            </tr>
            <tr>
                <td><strong>Administrador</strong></td>
                <td class="text-success"><i class="fas fa-check"></i> Todas</td>
                <td class="text-success"><i class="fas fa-check"></i></td>
                <td class="text-success"><i class="fas fa-check"></i></td>
                <td class="text-success"><i class="fas fa-check"></i></td>
            </tr>
        </tbody>
    </table>
</div>

<div class="help-tip help-tip-success">
    <i class="fas fa-check-circle me-2"></i>
    <strong>Próximo passo:</strong> Após entender a estrutura de pastas, aprenda a <a href="/admin/help/banco-imagens/upload-gerenciar">fazer upload e gerenciar as fotos</a> dentro de cada pasta.
</div>
