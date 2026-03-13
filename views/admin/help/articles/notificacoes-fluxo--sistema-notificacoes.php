<p class="lead">O sistema de notificações mantém todos os usuários informados sobre eventos importantes em tempo real, com um ícone de sino no cabeçalho, contagem de não lidas e navegação direta para o documento relacionado.</p>

<h5 class="mt-4 mb-3"><i class="fas fa-bell me-2 text-primary"></i>Notificações no cabeçalho</h5>

<div class="article-steps">
    <div class="article-step">
        <div class="step-number">1</div>
        <div class="step-content">
            <h6>Ícone do sino com badge de contagem</h6>
            <p>No canto superior direito do cabeçalho da plataforma, há um ícone de <strong>sino</strong> que funciona como ponto de acesso rápido às notificações.</p>
            <ul>
                <li>Quando existem notificações <strong>não lidas</strong>, um <strong>badge numérico</strong> (bolinha vermelha) aparece sobre o sino indicando a quantidade</li>
                <li>O badge é atualizado automaticamente via <strong>AJAX</strong>, sem necessidade de recarregar a página</li>
                <li>Quando todas as notificações estão lidas, o badge desaparece</li>
                <li>O sino está sempre visível em qualquer página da área administrativa</li>
            </ul>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">2</div>
        <div class="step-content">
            <h6>Dropdown de notificações recentes</h6>
            <p>Ao clicar no ícone do sino, um <strong>dropdown</strong> é exibido com as <strong>10 notificações mais recentes</strong>. Cada item do dropdown mostra:</p>
            <ul>
                <li><strong>Ícone colorido:</strong> um ícone à esquerda com cor diferente conforme o tipo de notificação (detalhado abaixo)</li>
                <li><strong>Título:</strong> descrição resumida do evento (ex: "Revisão solicitada", "Parecer finalizado")</li>
                <li><strong>Mensagem:</strong> texto complementar com detalhes sobre o documento ou ação</li>
                <li><strong>Tempo relativo:</strong> indicação em português de quando a notificação foi gerada (ex: "há 2 horas", "há 3 dias", "há 1 semana")</li>
                <li>Notificações <strong>não lidas</strong> possuem fundo destacado para diferenciá-las das já lidas</li>
            </ul>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">3</div>
        <div class="step-content">
            <h6>Clique para navegar ao documento</h6>
            <p>Ao clicar em uma notificação no dropdown, duas coisas acontecem simultaneamente:</p>
            <ul>
                <li>A notificação é marcada como <strong>lida</strong> automaticamente</li>
                <li>Você é redirecionado para o <strong>documento relacionado</strong> — por exemplo, o parecer descritivo, portfólio ou planejamento que gerou aquela notificação</li>
                <li>Isso permite acessar rapidamente o item que requer sua atenção sem precisar navegar manualmente</li>
            </ul>
        </div>
    </div>
</div>

<h5 class="mt-4 mb-3"><i class="fas fa-list me-2 text-primary"></i>Página completa de notificações</h5>

<div class="article-steps">
    <div class="article-step">
        <div class="step-number">4</div>
        <div class="step-content">
            <h6>Acesse todas as notificações</h6>
            <p>Para ver o histórico completo, acesse <strong>Notificações</strong> no menu lateral (sidebar). A página exibe as <strong>100 notificações mais recentes</strong> em formato de lista.</p>
            <ul>
                <li>Cada notificação mostra as mesmas informações do dropdown: ícone, título, mensagem e tempo relativo</li>
                <li>Notificações não lidas são visualmente destacadas com fundo diferenciado</li>
                <li>A lista é ordenada da mais recente para a mais antiga</li>
            </ul>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">5</div>
        <div class="step-content">
            <h6>Marcar como lida</h6>
            <p>Existem duas formas de marcar notificações como lidas:</p>
            <ul>
                <li><strong>Individual:</strong> clique em uma notificação específica para marcá-la como lida e ser redirecionado ao documento</li>
                <li><strong>Em lote:</strong> clique no botão <strong>"Marcar todas como lidas"</strong> no topo da página para marcar todas as notificações pendentes de uma vez</li>
            </ul>
        </div>
    </div>
</div>

<h5 class="mt-4 mb-3"><i class="fas fa-tags me-2 text-primary"></i>Tipos de notificação</h5>

<p>Cada tipo de notificação possui um ícone e uma cor específica para identificação rápida:</p>

<div class="table-responsive">
    <table class="table table-sm table-bordered">
        <thead class="table-light">
            <tr>
                <th>Tipo</th>
                <th>Ícone</th>
                <th>Cor</th>
                <th>Quando é gerada</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><code>revision_request</code></td>
                <td><i class="fas fa-exclamation-triangle text-warning"></i> Atenção</td>
                <td><span class="badge bg-warning text-dark">Amarelo</span></td>
                <td>Coordenador solicitou revisão em um documento do professor</td>
            </tr>
            <tr>
                <td><code>finalized</code></td>
                <td><i class="fas fa-check-circle text-success"></i> Concluído</td>
                <td><span class="badge bg-success">Verde</span></td>
                <td>Professor finalizou um documento para avaliação</td>
            </tr>
            <tr>
                <td><code>reopened</code></td>
                <td><i class="fas fa-redo text-info"></i> Reaberto</td>
                <td><span class="badge bg-info">Azul</span></td>
                <td>Documento foi reaberto para edição</td>
            </tr>
            <tr>
                <td><code>approved</code></td>
                <td><i class="fas fa-thumbs-up text-success"></i> Aprovado</td>
                <td><span class="badge bg-success">Verde</span></td>
                <td>Documento foi aprovado pelo coordenador</td>
            </tr>
            <tr>
                <td><code>rejected</code></td>
                <td><i class="fas fa-thumbs-down text-danger"></i> Rejeitado</td>
                <td><span class="badge bg-danger">Vermelho</span></td>
                <td>Documento foi rejeitado pelo coordenador</td>
            </tr>
            <tr>
                <td><code>comment</code></td>
                <td><i class="fas fa-comment text-primary"></i> Comentário</td>
                <td><span class="badge bg-primary">Azul</span></td>
                <td>Novo comentário adicionado a um documento</td>
            </tr>
            <tr>
                <td><code>reminder</code></td>
                <td><i class="fas fa-bell text-warning"></i> Lembrete</td>
                <td><span class="badge bg-warning text-dark">Amarelo</span></td>
                <td>Lembrete automático sobre prazo ou tarefa pendente</td>
            </tr>
        </tbody>
    </table>
</div>

<h5 class="mt-4 mb-3"><i class="fas fa-sync-alt me-2 text-primary"></i>Atualização em tempo real</h5>

<p>O sistema utiliza <strong>endpoints AJAX</strong> para manter as notificações atualizadas sem recarregar a página:</p>
<ul>
    <li>A contagem de não lidas no badge do sino é consultada periodicamente via requisição assíncrona</li>
    <li>O dropdown é carregado sob demanda ao clicar no sino</li>
    <li>Ações como marcar como lida e marcar todas como lidas são executadas via AJAX com feedback visual instantâneo</li>
</ul>

<div class="help-tip help-tip-success">
    <i class="fas fa-check-circle me-2"></i>
    <strong>Dica:</strong> Verifique suas notificações regularmente, especialmente se você é professor. Notificações de <strong>revisão solicitada</strong> indicam que o coordenador precisa que você faça ajustes em um documento antes da aprovação final.
</div>
