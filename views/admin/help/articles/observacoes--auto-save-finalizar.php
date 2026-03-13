<p class="lead">O sistema de observações conta com salvamento automático para evitar perda de dados e um processo de finalização que bloqueia a edição e habilita a geração do parecer descritivo.</p>

<h6 class="fw-bold mt-4 mb-3">Salvamento Automático (Auto-Save)</h6>

<p>O auto-save funciona de forma transparente enquanto você preenche os eixos pedagógicos, garantindo que nenhum texto seja perdido mesmo em caso de queda de conexão ou fechamento acidental do navegador.</p>

<div class="article-steps">
    <div class="article-step">
        <div class="step-number">1</div>
        <div class="step-content">
            <h6>Como o auto-save é acionado</h6>
            <p>O salvamento automático é disparado em duas situações:</p>
            <ul>
                <li><strong>Ao sair do campo (blur):</strong> quando você clica fora do textarea ou muda de aba, o conteúdo daquele eixo é salvo imediatamente</li>
                <li><strong>Após parar de digitar (debounce de 2 segundos):</strong> se você parar de digitar por 2 segundos, o sistema salva automaticamente o conteúdo atual. Isso garante salvamento mesmo que você continue na mesma aba por longos períodos</li>
            </ul>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">2</div>
        <div class="step-content">
            <h6>Requisição AJAX de salvamento</h6>
            <p>Cada auto-save envia uma requisição <strong>AJAX</strong> (assíncrona) para o endpoint:</p>
            <p><code>/admin/observations/{id}/auto-save</code></p>
            <p>A requisição é enviada em segundo plano, sem recarregar a página e sem interromper a digitação. Apenas o conteúdo do eixo alterado é enviado, otimizando o tráfego de dados.</p>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">3</div>
        <div class="step-content">
            <h6>Indicador visual de salvamento</h6>
            <p>Após cada salvamento bem-sucedido, um indicador visual é exibido no formulário:</p>
            <p><em><i class="fas fa-check-circle text-success me-1"></i> Salvo automaticamente às 14:35</em></p>
            <p>O horário exibido corresponde ao momento exato do último salvamento. Se houver erro na conexão, o indicador mostrará um aviso para que você salve manualmente.</p>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">4</div>
        <div class="step-content">
            <h6>Proteção contra saída acidental</h6>
            <p>Se houver alterações não salvas e você tentar <strong>fechar a aba</strong>, <strong>navegar para outra página</strong> ou <strong>recarregar</strong>, o navegador exibirá um <strong>diálogo de confirmação</strong> (evento <code>beforeunload</code>):</p>
            <p><em>"Você tem alterações não salvas. Tem certeza que deseja sair?"</em></p>
            <p>Essa proteção evita perda acidental de texto que ainda não foi salvo pelo auto-save.</p>
        </div>
    </div>
</div>

<div class="help-tip">
    <i class="fas fa-lightbulb me-2"></i>
    <strong>Dica:</strong> Você pode preencher os eixos ao longo de vários dias. O auto-save garante que todo progresso parcial seja preservado. Basta acessar a observação novamente pelo menu <strong>Pedagógico → Observações</strong> e continuar de onde parou.
</div>

<hr class="my-4">

<h6 class="fw-bold mt-4 mb-3">Finalização da Observação</h6>

<p>A finalização é o processo que encerra a edição da observação e a marca como completa. Após finalizar, o professor não poderá mais alterar os textos.</p>

<div class="article-steps">
    <div class="article-step">
        <div class="step-number">1</div>
        <div class="step-content">
            <h6>Iniciar a finalização</h6>
            <p>Quando todos os eixos estiverem preenchidos e revisados, clique no botão <strong>"Finalizar Registro"</strong> localizado ao final do formulário de observação.</p>
            <p>Um <strong>modal de confirmação</strong> será exibido com a mensagem:</p>
            <p><em>"Ao finalizar, os campos não poderão mais ser editados. Deseja continuar?"</em></p>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">2</div>
        <div class="step-content">
            <h6>Confirmar a finalização</h6>
            <p>Clique em <strong>"Sim, finalizar"</strong> para confirmar. O sistema irá:</p>
            <ul>
                <li>Alterar o status da observação de <span class="badge bg-warning text-dark">Em andamento</span> para <span class="badge bg-success">Finalizada</span></li>
                <li>Registrar o <strong>timestamp</strong> (data e hora exatas) da finalização</li>
                <li>Tornar <strong>todos os campos de texto somente leitura</strong> (readonly) — os textos ficam visíveis mas não editáveis</li>
                <li>Desabilitar o auto-save, pois não há mais alterações possíveis</li>
            </ul>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">3</div>
        <div class="step-content">
            <h6>Gerar Parecer Descritivo</h6>
            <p>Após a finalização, um novo botão é exibido na observação: <strong>"Gerar Parecer Descritivo"</strong>. Este botão permite criar o relatório formal de avaliação pedagógica com base nos textos registrados nos 6 eixos.</p>
            <p>O parecer descritivo só pode ser gerado a partir de observações finalizadas, garantindo que o relatório seja baseado em registros completos e revisados.</p>
        </div>
    </div>
</div>

<div class="help-tip">
    <i class="fas fa-exclamation-circle me-2"></i>
    <strong>Importante:</strong> Após a finalização, o <strong>professor não pode mais editar</strong> a observação. Se for necessário fazer correções, apenas o <strong>Coordenador</strong> pode reabrir a observação (veja o artigo sobre permissões). Revise seus textos cuidadosamente antes de finalizar.
</div>

<h6 class="fw-bold mt-4 mb-3">Resumo do ciclo de vida da observação</h6>

<div class="table-responsive">
    <table class="table table-sm table-bordered">
        <thead class="table-light">
            <tr>
                <th>Etapa</th>
                <th>Status</th>
                <th>Ações possíveis</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Criação</strong></td>
                <td><span class="badge bg-warning text-dark">Em andamento</span></td>
                <td>Editar textos, auto-save ativo, navegação entre abas</td>
            </tr>
            <tr>
                <td><strong>Preenchimento</strong></td>
                <td><span class="badge bg-warning text-dark">Em andamento</span></td>
                <td>Continuar preenchendo eixos, salvar automaticamente</td>
            </tr>
            <tr>
                <td><strong>Finalização</strong></td>
                <td><span class="badge bg-success">Finalizada</span></td>
                <td>Campos readonly, gerar parecer descritivo</td>
            </tr>
            <tr>
                <td><strong>Reabertura</strong> (apenas Coordenador)</td>
                <td><span class="badge bg-warning text-dark">Em andamento</span></td>
                <td>Professor pode editar novamente</td>
            </tr>
        </tbody>
    </table>
</div>

<div class="help-tip help-tip-success">
    <i class="fas fa-check-circle me-2"></i>
    <strong>Dica:</strong> Antes de finalizar, navegue por todas as 6 abas e releia seus textos. Verifique se as informações estão claras e completas, pois elas serão a base para o parecer descritivo do aluno.
</div>
