<p class="lead">O fluxo de aprovação é o processo pelo qual documentos pedagógicos passam do professor ao coordenador para revisão, podendo ser finalizados, revisados e refinalizados até a aprovação final.</p>

<div class="help-tip">
    <i class="fas fa-lightbulb me-2"></i>
    <strong>Aplicável a:</strong> O fluxo de aprovação se aplica a três tipos de documentos: <strong>Pareceres Descritivos</strong>, <strong>Portfólios</strong> e <strong>Planejamentos</strong>. O ciclo é o mesmo para todos.
</div>

<h5 class="mt-4 mb-3"><i class="fas fa-project-diagram me-2 text-primary"></i>Visão geral do fluxo</h5>

<p>O diagrama abaixo ilustra o ciclo completo do fluxo de aprovação:</p>

<div class="d-flex flex-wrap align-items-center justify-content-center gap-2 my-4 p-3" style="background: #f8f9fa; border-radius: 12px;">
    <span class="badge bg-secondary px-3 py-2"><i class="fas fa-edit me-1"></i>Rascunho</span>
    <i class="fas fa-arrow-right text-muted"></i>
    <span class="badge bg-primary px-3 py-2"><i class="fas fa-check me-1"></i>Finalizado</span>
    <i class="fas fa-arrow-right text-muted"></i>
    <span class="badge bg-info px-3 py-2"><i class="fas fa-eye me-1"></i>Em revisão</span>
    <i class="fas fa-arrow-right text-muted"></i>
    <span class="badge bg-warning text-dark px-3 py-2"><i class="fas fa-undo me-1"></i>Revisão Solicitada</span>
    <i class="fas fa-arrow-right text-muted"></i>
    <span class="badge bg-primary px-3 py-2"><i class="fas fa-check me-1"></i>Refinalizado</span>
    <i class="fas fa-arrow-right text-muted"></i>
    <span class="badge bg-success px-3 py-2"><i class="fas fa-check-double me-1"></i>Aprovado</span>
</div>

<p class="text-muted text-center small">O ciclo <strong>Finalizado → Revisão Solicitada → Refinalizado</strong> pode se repetir quantas vezes forem necessárias até a aprovação.</p>

<h5 class="mt-4 mb-3"><i class="fas fa-chalkboard-teacher me-2 text-primary"></i>Passo a passo detalhado</h5>

<div class="article-steps">
    <div class="article-step">
        <div class="step-number">1</div>
        <div class="step-content">
            <h6>Professor cria o documento</h6>
            <p>O professor acessa a seção correspondente (Pareceres, Portfólios ou Planejamentos) e cria um novo documento. O documento é criado com status <strong>"Rascunho"</strong>.</p>
            <ul>
                <li>No modo rascunho, o professor pode editar livremente todo o conteúdo do documento</li>
                <li>O documento pode ser salvo parcialmente e retomado posteriormente</li>
                <li>Nenhuma notificação é gerada enquanto o documento está em rascunho</li>
                <li>O coordenador pode ver o documento na listagem, mas não é necessária nenhuma ação de sua parte nesta etapa</li>
            </ul>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">2</div>
        <div class="step-content">
            <h6>Professor finaliza o documento</h6>
            <p>Quando o professor considera o documento pronto para revisão, clica no botão <strong>"Finalizar"</strong>. O status muda para <strong>"Finalizado"</strong>.</p>
            <ul>
                <li>Ao finalizar, o sistema envia automaticamente uma <strong>notificação</strong> para <strong>todos os coordenadores</strong> cadastrados na plataforma</li>
                <li>A notificação é do tipo <code>finalized</code> (ícone verde de conclusão)</li>
                <li>O documento passa a ser <strong>somente leitura</strong> para o professor — ele não pode mais editar até que o coordenador solicite revisão</li>
                <li>A finalização indica que o professor considera o trabalho concluído e pronto para avaliação</li>
            </ul>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">3</div>
        <div class="step-content">
            <h6>Coordenador revisa o documento</h6>
            <p>O coordenador recebe a notificação e acessa o documento finalizado para revisão. O documento é exibido em <strong>modo somente leitura</strong> para o coordenador (ele não pode editar o conteúdo do professor).</p>
            <ul>
                <li>O coordenador lê todo o conteúdo do documento com atenção</li>
                <li>Avalia a qualidade pedagógica, a coerência do texto e o cumprimento dos requisitos</li>
                <li>Nesta etapa, o coordenador tem duas opções: aprovar ou solicitar revisão</li>
            </ul>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">4</div>
        <div class="step-content">
            <h6>Coordenador solicita revisão (se necessário)</h6>
            <p>Se o coordenador identifica pontos que precisam de ajuste, clica no botão <strong>"Solicitar Revisão"</strong>. Um formulário é exibido para que o coordenador registre suas observações:</p>
            <ul>
                <li><strong>Notas de revisão:</strong> campo de texto onde o coordenador descreve exatamente o que precisa ser corrigido ou melhorado</li>
                <li>O status do documento muda para <strong>"Revisão Solicitada"</strong></li>
                <li>O sistema envia automaticamente uma notificação do tipo <code>revision_request</code> (ícone amarelo de atenção) para o <strong>professor autor</strong> do documento</li>
                <li>O documento volta a ser <strong>editável</strong> para o professor</li>
            </ul>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">5</div>
        <div class="step-content">
            <h6>Professor corrige o documento</h6>
            <p>O professor recebe a notificação de revisão solicitada. Ao abrir o documento, ele encontra:</p>
            <ul>
                <li>As <strong>notas de revisão</strong> do coordenador, explicando o que precisa ser ajustado</li>
                <li>O documento agora está novamente em <strong>modo de edição</strong></li>
                <li>O professor realiza as correções solicitadas no conteúdo do documento</li>
                <li>Pode salvar parcialmente durante o processo de correção</li>
            </ul>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">6</div>
        <div class="step-content">
            <h6>Professor refinaliza o documento</h6>
            <p>Após realizar todas as correções, o professor clica novamente em <strong>"Finalizar"</strong> para enviar o documento revisado. O status volta para <strong>"Finalizado"</strong>.</p>
            <ul>
                <li>Todos os coordenadores são <strong>notificados novamente</strong> de que o documento foi refinalizado</li>
                <li>O documento volta a ficar em modo somente leitura para o professor</li>
                <li>O coordenador pode então avaliar as correções realizadas</li>
            </ul>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">7</div>
        <div class="step-content">
            <h6>Aprovação final</h6>
            <p>Quando o coordenador está satisfeito com o documento, a aprovação é concretizada. O status muda para <strong>"Aprovado"</strong> ou equivalente.</p>
            <ul>
                <li>O coordenador pode então fazer o <strong>download em PDF</strong> do documento aprovado</li>
                <li>O professor recebe notificação de aprovação (tipo <code>approved</code>, ícone verde)</li>
                <li>O documento aprovado fica em modo somente leitura para todos</li>
            </ul>
        </div>
    </div>
</div>

<h5 class="mt-4 mb-3"><i class="fas fa-sync me-2 text-primary"></i>Ciclo de revisões</h5>

<p>O fluxo de revisão pode se <strong>repetir múltiplas vezes</strong> conforme necessário:</p>

<div class="article-steps">
    <div class="article-step">
        <div class="step-number"><i class="fas fa-redo"></i></div>
        <div class="step-content">
            <h6>Ciclo repetível</h6>
            <p>O ciclo <strong>Finalizar → Revisão Solicitada → Correção → Refinalizar</strong> pode acontecer quantas vezes forem necessárias até que o coordenador esteja satisfeito com o resultado.</p>
            <ul>
                <li>Cada rodada de revisão gera novas notificações para ambas as partes</li>
                <li>O histórico de notas de revisão é preservado para referência</li>
                <li>Não há limite de rodadas de revisão</li>
                <li>O objetivo é garantir a qualidade pedagógica do documento por meio de um diálogo construtivo entre professor e coordenador</li>
            </ul>
        </div>
    </div>
</div>

<h5 class="mt-4 mb-3"><i class="fas fa-file-alt me-2 text-primary"></i>Documentos que utilizam o fluxo</h5>

<div class="table-responsive">
    <table class="table table-sm table-bordered">
        <thead class="table-light">
            <tr>
                <th>Documento</th>
                <th>Descrição</th>
                <th>Quem cria</th>
                <th>Quem revisa</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Parecer Descritivo</strong></td>
                <td>Documento individual por aluno, com narrativa de desenvolvimento semestral</td>
                <td>Professor</td>
                <td>Coordenador</td>
            </tr>
            <tr>
                <td><strong>Portfólio</strong></td>
                <td>Documento coletivo da turma com registros de atividades e projetos</td>
                <td>Professor</td>
                <td>Coordenador</td>
            </tr>
            <tr>
                <td><strong>Planejamento</strong></td>
                <td>Planejamento semanal das atividades pedagógicas da turma</td>
                <td>Professor</td>
                <td>Coordenador</td>
            </tr>
        </tbody>
    </table>
</div>

<h5 class="mt-4 mb-3"><i class="fas fa-comment-alt me-2 text-success"></i>Feedbacks da Coordenação</h5>

<p>Além do fluxo formal de aprovação, o coordenador pode usar os <strong>Feedbacks da Coordenação</strong> como canal de comunicação direta com o professor, sem precisar reabrir ou bloquear o documento:</p>

<div class="table-responsive">
    <table class="table table-bordered table-sm">
        <thead class="table-light">
            <tr>
                <th>Recurso</th>
                <th>Feedbacks da Coordenação</th>
                <th>Solicitação de Revisão</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Disponível em</strong></td>
                <td>Observações, Pareceres, Portfólios</td>
                <td>Pareceres, Portfólios, Planejamentos</td>
            </tr>
            <tr>
                <td><strong>Bloqueia edição do professor?</strong></td>
                <td class="text-center text-danger"><i class="fas fa-times"></i> Não</td>
                <td class="text-center text-success"><i class="fas fa-check"></i> Sim (requer refinalização)</td>
            </tr>
            <tr>
                <td><strong>Notifica o professor?</strong></td>
                <td class="text-center text-success"><i class="fas fa-check"></i> Sim, automaticamente</td>
                <td class="text-center text-success"><i class="fas fa-check"></i> Sim, automaticamente</td>
            </tr>
            <tr>
                <td><strong>Histórico</strong></td>
                <td>Sim, cronológico com data e autor</td>
                <td>Sim, notas de revisão</td>
            </tr>
            <tr>
                <td><strong>Ideal para</strong></td>
                <td>Orientações complementares, elogios, sugestões leves</td>
                <td>Correções obrigatórias antes da aprovação</td>
            </tr>
        </tbody>
    </table>
</div>

<p>Os <strong>Feedbacks da Coordenação</strong> estão disponíveis na seção inferior da página de visualização de cada documento. O professor pode ler os feedbacks diretamente no documento sem precisar acessar as notificações.</p>

<div class="help-tip help-tip-success">
    <i class="fas fa-check-circle me-2"></i>
    <strong>Dica para coordenadores:</strong> Ao solicitar revisão, seja específico nas notas — indique exatamente quais partes precisam de ajuste e o que espera como melhoria. Para orientações gerais e positivas, use os Feedbacks da Coordenação para não interromper o fluxo de aprovação.
</div>

<div class="help-tip">
    <i class="fas fa-lightbulb me-2"></i>
    <strong>Dica para professores:</strong> Antes de finalizar, releia o documento completo e verifique se todos os campos obrigatórios estão preenchidos. Uma finalização bem preparada reduz a chance de solicitação de revisão.
</div>
