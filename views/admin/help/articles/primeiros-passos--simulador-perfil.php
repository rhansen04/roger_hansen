<p class="lead">O Simulador de Perfil permite que administradores visualizem a plataforma exatamente como um Professor ou Coordenador veria, sem precisar sair da sua conta. Ideal para suporte, treinamento e verificação de permissões.</p>

<div class="help-tip">
    <i class="fas fa-lightbulb me-2"></i>
    <strong>Importante:</strong> A simulação afeta <strong>apenas a visualização</strong>. Todas as operações de escrita (criar, editar, excluir) sempre utilizam o papel real de administrador, garantindo total segurança.
</div>

<h6 class="fw-bold mt-4 mb-3">O que é o Simulador de Perfil?</h6>
<p>O Simulador de Perfil (recurso R2-09) permite que usuários com papel <strong>Admin</strong> alternem temporariamente a interface para exibir a visão de outro papel: <span class="badge bg-primary">Professor</span> ou <span class="badge bg-success">Coordenador</span>. Isso significa que o menu lateral, o dashboard e o badge de papel se adaptam automaticamente, mostrando exatamente o que aquele perfil veria ao acessar a plataforma.</p>

<div class="article-steps">
    <div class="article-step">
        <div class="step-number">1</div>
        <div class="step-content">
            <h6>Como acessar o Simulador</h6>
            <p>Na <strong>barra superior</strong> da plataforma, localize o dropdown do seu perfil (canto superior direito). Apenas usuários com papel <strong>Admin</strong> verão a opção <strong>"Simular Perfil"</strong>.</p>
            <ul>
                <li>Clique no dropdown do seu nome/avatar na barra superior</li>
                <li>Selecione <strong>"Simular Perfil"</strong></li>
                <li>Escolha o papel desejado: <strong>Professor</strong> ou <strong>Coordenador</strong></li>
                <li>A interface se adapta instantaneamente ao papel selecionado</li>
            </ul>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">2</div>
        <div class="step-content">
            <h6>O que muda durante a simulação</h6>
            <p>Ao ativar a simulação, três elementos visuais se alteram imediatamente:</p>
            <ul>
                <li><strong>Menu lateral (sidebar):</strong> exibe apenas os itens de menu que o papel simulado teria acesso. Por exemplo, ao simular Professor, itens como "Usuários" e "Escolas" desaparecem do menu.</li>
                <li><strong>Dashboard:</strong> carrega o dashboard correspondente ao papel simulado, com os cards, contadores e atalhos daquele perfil.</li>
                <li><strong>Badge de papel:</strong> o badge no topo muda para refletir o papel simulado (ex.: de <span class="badge bg-danger">Admin</span> para <span class="badge bg-primary">Professor</span>).</li>
            </ul>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">3</div>
        <div class="step-content">
            <h6>Banner de alerta (sticky)</h6>
            <p>Enquanto a simulação estiver ativa, um <strong>banner amarelo fixo</strong> aparece no topo de todas as páginas para que você nunca esqueça que está em modo de simulação:</p>
            <ul>
                <li>Texto do banner: <strong>"Simulando visão de PROFESSOR"</strong> ou <strong>"Simulando visão de COORDENADOR"</strong></li>
                <li>O banner permanece fixo (sticky) no topo da tela, mesmo ao rolar a página</li>
                <li>Ao lado do texto, um botão <strong>"Voltar para Admin"</strong> permite desativar a simulação instantaneamente</li>
            </ul>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">4</div>
        <div class="step-content">
            <h6>Como desativar a simulação</h6>
            <p>Existem duas formas de voltar ao modo Admin:</p>
            <ul>
                <li><strong>Pelo banner:</strong> clique no botão <strong>"Voltar para Admin"</strong> no banner amarelo fixo no topo da página</li>
                <li><strong>Pelo dropdown:</strong> clique no dropdown do seu perfil na barra superior e selecione <strong>"Voltar para Admin"</strong></li>
            </ul>
            <p>Nos dois casos, a interface retorna instantaneamente ao modo completo de administrador.</p>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">5</div>
        <div class="step-content">
            <h6>Segurança: simulação vs. permissões reais</h6>
            <p>A simulação <strong>nunca</strong> reduz suas permissões reais de administrador. Ela apenas adapta a interface visual. Na prática:</p>
            <ul>
                <li>Se você tentar criar um registro enquanto simula Professor, a operação utiliza seu papel real de Admin</li>
                <li>Chamadas de API e verificações de permissão no servidor sempre consideram o papel original da conta</li>
                <li>Não há risco de perder acesso a funcionalidades durante a simulação</li>
                <li>A simulação é por sessão: se você fizer logout, a simulação é automaticamente desativada</li>
            </ul>
        </div>
    </div>
</div>

<figure class="article-screenshot">
    <div class="screenshot-placeholder">
        <i class="fas fa-user-secret fa-3x" style="color:#6f42c1"></i>
        <p class="text-muted mt-2">Banner amarelo de simulação ativa no topo da tela</p>
    </div>
    <figcaption>Exemplo do banner "Simulando visão de PROFESSOR" com botão para voltar ao Admin</figcaption>
</figure>

<h6 class="fw-bold mt-4 mb-3">O que muda por papel simulado</h6>
<div class="table-responsive">
    <table class="table table-bordered table-sm">
        <thead class="table-dark">
            <tr>
                <th>Elemento</th>
                <th class="text-center">Simulando Professor</th>
                <th class="text-center">Simulando Coordenador</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Menu lateral</strong></td>
                <td>Turmas, Observações, Pareceres, Portfólios, Planejamento, Banco de Imagens, Cursos, Material de Apoio</td>
                <td>Dashboard, Turmas, Observações, Pareceres, Portfólios, Planejamento, Relatórios, Material de Apoio</td>
            </tr>
            <tr>
                <td><strong>Dashboard</strong></td>
                <td>Dashboard do Professor: minhas turmas, planejamentos pendentes, observações recentes</td>
                <td>Dashboard do Coordenador: visão geral de todas as turmas, entregas pendentes, status dos professores</td>
            </tr>
            <tr>
                <td><strong>Badge de papel</strong></td>
                <td class="text-center"><span class="badge bg-primary">Professor</span></td>
                <td class="text-center"><span class="badge bg-success">Coordenador</span></td>
            </tr>
            <tr>
                <td><strong>Itens ocultos</strong></td>
                <td>Usuários, Escolas, Relatórios gerais, Configurações do sistema</td>
                <td>Usuários, Escolas, Configurações do sistema</td>
            </tr>
            <tr>
                <td><strong>Dados exibidos</strong></td>
                <td>Apenas dados vinculados ao admin (simulando "minhas turmas")</td>
                <td>Dados de todos os professores e turmas (visão supervisão)</td>
            </tr>
            <tr>
                <td><strong>Banner sticky</strong></td>
                <td class="text-center"><span class="badge bg-warning text-dark">Simulando visão de PROFESSOR</span></td>
                <td class="text-center"><span class="badge bg-warning text-dark">Simulando visão de COORDENADOR</span></td>
            </tr>
        </tbody>
    </table>
</div>

<h6 class="fw-bold mt-4 mb-3">Caso de uso: testando todas as visões</h6>
<p>A coordenadora Larissa, que possui conta com papel Admin, precisa verificar se o menu e o dashboard estão corretos para os professores antes de uma reunião. Em vez de criar contas de teste ou pedir login emprestado, ela simplesmente:</p>
<ol>
    <li>Clica no dropdown e seleciona <strong>"Simular Perfil" → Professor</strong></li>
    <li>Navega pela plataforma verificando menus, dashboard e listagens</li>
    <li>Clica em <strong>"Voltar para Admin"</strong> no banner amarelo</li>
    <li>Repete o processo selecionando <strong>"Simular Perfil" → Coordenador</strong></li>
    <li>Verifica a visão de supervisão e os filtros disponíveis</li>
    <li>Volta para Admin e segue com seu trabalho normalmente</li>
</ol>

<div class="help-tip help-tip-success">
    <i class="fas fa-check-circle me-2"></i>
    <strong>Dica:</strong> Use o Simulador de Perfil sempre que precisar orientar um professor sobre onde encontrar algo na plataforma. Ao simular a visão dele, você consegue descrever exatamente os passos que ele deve seguir.
</div>
