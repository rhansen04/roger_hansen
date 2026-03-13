<p class="lead">A plataforma Hansen Educacional possui 5 papéis de usuário, cada um com permissões específicas. Entenda exatamente o que cada papel pode fazer em cada módulo do sistema.</p>

<div class="article-steps">
    <div class="article-step">
        <div class="step-number">1</div>
        <div class="step-content">
            <h6>Admin — Controle Total</h6>
            <p>O administrador tem acesso irrestrito a todos os módulos e funcionalidades da plataforma:</p>
            <ul>
                <li>Gerencia escolas, usuários, alunos e todas as configurações do sistema</li>
                <li>Cria e edita cursos, módulos, lições e quizzes</li>
                <li>Gerencia matrículas e acompanha o progresso de todos os alunos</li>
                <li>Acessa todos os relatórios e dashboards analíticos</li>
                <li>Visualiza e gerencia observações, pareceres e portfólios de todas as turmas</li>
                <li>Gerencia notificações e contatos recebidos</li>
            </ul>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">2</div>
        <div class="step-content">
            <h6>Professor — Trabalho Pedagógico</h6>
            <p>O professor é responsável pelo registro pedagógico das suas turmas:</p>
            <ul>
                <li><strong>Turmas:</strong> cria e edita suas próprias turmas, vincula e desvincula alunos</li>
                <li><strong>Observações:</strong> cria e edita observações pedagógicas das crianças das suas turmas</li>
                <li><strong>Pareceres:</strong> elabora pareceres descritivos, usa correção por IA e exporta PDF</li>
                <li><strong>Portfólios:</strong> cria portfólios coletivos das suas turmas com fotos e textos</li>
                <li><strong>Planejamento:</strong> cria planejamentos pedagógicos usando os templates disponíveis</li>
                <li><strong>Banco de Imagens:</strong> faz upload de fotos, adiciona legendas e organiza por turma</li>
                <li><strong>Cursos:</strong> acessa cursos atribuídos e acompanha seu próprio progresso</li>
                <li><strong>Limitações:</strong> não pode aprovar documentos, não acessa relatórios gerais nem gerencia outros usuários</li>
            </ul>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">3</div>
        <div class="step-content">
            <h6>Coordenador — Supervisão Pedagógica</h6>
            <p>O coordenador supervisiona o trabalho dos professores sem criar documentos diretamente:</p>
            <ul>
                <li><strong>Visualização completa:</strong> vê todas as observações, pareceres e portfólios de todas as turmas e professores</li>
                <li><strong>Reabrir documentos:</strong> pode reabrir observações e pareceres já finalizados para correção</li>
                <li><strong>Solicitar revisão:</strong> envia o documento de volta ao professor com anotações específicas sobre o que precisa ser ajustado</li>
                <li><strong>Banco de Imagens:</strong> acesso somente para visualização, não pode fazer upload nem editar</li>
                <li><strong>Limitações:</strong> não cria observações, pareceres ou portfólios. Seu papel é revisar e orientar.</li>
            </ul>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">4</div>
        <div class="step-content">
            <h6>Student (Aluno) — Aprendizagem Online</h6>
            <p>O aluno acessa a plataforma pelo painel do estudante:</p>
            <ul>
                <li>Dashboard próprio com cursos matriculados e progresso individual</li>
                <li>Assiste aulas em vídeo, lê materiais e realiza quizzes</li>
                <li>Acompanha seu percentual de conclusão por curso e módulo</li>
                <li>Recebe certificados ao concluir cursos (quando configurado)</li>
            </ul>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">5</div>
        <div class="step-content">
            <h6>Parent (Responsável) — Acompanhamento</h6>
            <p>O responsável acompanha o desempenho do filho:</p>
            <ul>
                <li>Dashboard com visão do progresso da criança nos cursos</li>
                <li>Acesso aos relatórios de desempenho do filho</li>
                <li>Visualização de pareceres quando compartilhados</li>
            </ul>
        </div>
    </div>
</div>

<h6 class="mt-4 mb-3">Matriz de Permissões por Módulo</h6>
<div class="table-responsive">
    <table class="table table-bordered table-sm">
        <thead class="table-dark">
            <tr>
                <th>Módulo</th>
                <th class="text-center">Admin</th>
                <th class="text-center">Professor</th>
                <th class="text-center">Coordenador</th>
                <th class="text-center">Student</th>
                <th class="text-center">Parent</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Dashboard</td>
                <td class="text-center text-success"><i class="fas fa-check"></i> Completo</td>
                <td class="text-center text-success"><i class="fas fa-check"></i> Próprio</td>
                <td class="text-center text-success"><i class="fas fa-check"></i> Supervisão</td>
                <td class="text-center text-success"><i class="fas fa-check"></i> Cursos</td>
                <td class="text-center text-success"><i class="fas fa-check"></i> Filho</td>
            </tr>
            <tr>
                <td>Escolas</td>
                <td class="text-center text-success"><i class="fas fa-check"></i> CRUD</td>
                <td class="text-center text-danger"><i class="fas fa-times"></i></td>
                <td class="text-center text-muted"><i class="fas fa-eye"></i> Ver</td>
                <td class="text-center text-danger"><i class="fas fa-times"></i></td>
                <td class="text-center text-danger"><i class="fas fa-times"></i></td>
            </tr>
            <tr>
                <td>Alunos</td>
                <td class="text-center text-success"><i class="fas fa-check"></i> CRUD</td>
                <td class="text-center text-muted"><i class="fas fa-eye"></i> Suas turmas</td>
                <td class="text-center text-muted"><i class="fas fa-eye"></i> Ver</td>
                <td class="text-center text-danger"><i class="fas fa-times"></i></td>
                <td class="text-center text-danger"><i class="fas fa-times"></i></td>
            </tr>
            <tr>
                <td>Turmas</td>
                <td class="text-center text-success"><i class="fas fa-check"></i> CRUD</td>
                <td class="text-center text-success"><i class="fas fa-check"></i> Próprias</td>
                <td class="text-center text-muted"><i class="fas fa-eye"></i> Ver todas</td>
                <td class="text-center text-danger"><i class="fas fa-times"></i></td>
                <td class="text-center text-danger"><i class="fas fa-times"></i></td>
            </tr>
            <tr>
                <td>Observações</td>
                <td class="text-center text-success"><i class="fas fa-check"></i> CRUD</td>
                <td class="text-center text-success"><i class="fas fa-check"></i> Criar/Editar</td>
                <td class="text-center text-warning"><i class="fas fa-eye"></i> Revisar</td>
                <td class="text-center text-danger"><i class="fas fa-times"></i></td>
                <td class="text-center text-danger"><i class="fas fa-times"></i></td>
            </tr>
            <tr>
                <td>Pareceres</td>
                <td class="text-center text-success"><i class="fas fa-check"></i> CRUD</td>
                <td class="text-center text-success"><i class="fas fa-check"></i> Criar/Editar</td>
                <td class="text-center text-warning"><i class="fas fa-eye"></i> Revisar</td>
                <td class="text-center text-danger"><i class="fas fa-times"></i></td>
                <td class="text-center text-muted"><i class="fas fa-eye"></i> Ver</td>
            </tr>
            <tr>
                <td>Portfólios</td>
                <td class="text-center text-success"><i class="fas fa-check"></i> CRUD</td>
                <td class="text-center text-success"><i class="fas fa-check"></i> Criar/Editar</td>
                <td class="text-center text-warning"><i class="fas fa-eye"></i> Revisar</td>
                <td class="text-center text-danger"><i class="fas fa-times"></i></td>
                <td class="text-center text-danger"><i class="fas fa-times"></i></td>
            </tr>
            <tr>
                <td>Banco de Imagens</td>
                <td class="text-center text-success"><i class="fas fa-check"></i> CRUD</td>
                <td class="text-center text-success"><i class="fas fa-check"></i> Upload</td>
                <td class="text-center text-muted"><i class="fas fa-eye"></i> Ver</td>
                <td class="text-center text-danger"><i class="fas fa-times"></i></td>
                <td class="text-center text-danger"><i class="fas fa-times"></i></td>
            </tr>
            <tr>
                <td>Planejamento</td>
                <td class="text-center text-success"><i class="fas fa-check"></i> CRUD</td>
                <td class="text-center text-success"><i class="fas fa-check"></i> Criar/Editar</td>
                <td class="text-center text-muted"><i class="fas fa-eye"></i> Ver</td>
                <td class="text-center text-danger"><i class="fas fa-times"></i></td>
                <td class="text-center text-danger"><i class="fas fa-times"></i></td>
            </tr>
            <tr>
                <td>Material de Apoio</td>
                <td class="text-center text-success"><i class="fas fa-check"></i> CRUD</td>
                <td class="text-center text-success"><i class="fas fa-check"></i> Upload/Download</td>
                <td class="text-center text-success"><i class="fas fa-check"></i> Upload/Download</td>
                <td class="text-center text-danger"><i class="fas fa-times"></i></td>
                <td class="text-center text-danger"><i class="fas fa-times"></i></td>
            </tr>
            <tr>
                <td>Cursos</td>
                <td class="text-center text-success"><i class="fas fa-check"></i> CRUD</td>
                <td class="text-center text-muted"><i class="fas fa-eye"></i> Assistir</td>
                <td class="text-center text-muted"><i class="fas fa-eye"></i> Ver relatórios</td>
                <td class="text-center text-success"><i class="fas fa-check"></i> Assistir</td>
                <td class="text-center text-danger"><i class="fas fa-times"></i></td>
            </tr>
            <tr>
                <td>Relatórios</td>
                <td class="text-center text-success"><i class="fas fa-check"></i> Todos</td>
                <td class="text-center text-danger"><i class="fas fa-times"></i></td>
                <td class="text-center text-success"><i class="fas fa-check"></i> Pedagógicos</td>
                <td class="text-center text-danger"><i class="fas fa-times"></i></td>
                <td class="text-center text-muted"><i class="fas fa-eye"></i> Filho</td>
            </tr>
            <tr>
                <td>Notificações</td>
                <td class="text-center text-success"><i class="fas fa-check"></i> Todas</td>
                <td class="text-center text-success"><i class="fas fa-check"></i> Próprias</td>
                <td class="text-center text-success"><i class="fas fa-check"></i> Próprias</td>
                <td class="text-center text-success"><i class="fas fa-check"></i> Próprias</td>
                <td class="text-center text-success"><i class="fas fa-check"></i> Próprias</td>
            </tr>
            <tr>
                <td>Usuários</td>
                <td class="text-center text-success"><i class="fas fa-check"></i> CRUD</td>
                <td class="text-center text-danger"><i class="fas fa-times"></i></td>
                <td class="text-center text-danger"><i class="fas fa-times"></i></td>
                <td class="text-center text-danger"><i class="fas fa-times"></i></td>
                <td class="text-center text-danger"><i class="fas fa-times"></i></td>
            </tr>
        </tbody>
    </table>
</div>

<div class="help-tip">
    <i class="fas fa-lightbulb me-2"></i>
    <strong>Dica:</strong> O papel do usuário é definido pelo administrador no momento do cadastro em <strong>Cadastros &rarr; Usuários</strong>. Um usuário não pode alterar seu próprio papel.
</div>

<div class="help-tip help-tip-success">
    <i class="fas fa-check-circle me-2"></i>
    <strong>Importante:</strong> Se você precisa de acesso a um módulo que não aparece no seu menu, entre em contato com o administrador da plataforma para verificar suas permissões.
</div>
