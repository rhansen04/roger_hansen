<p class="lead">A matrícula vincula um aluno a um curso, liberando o acesso ao conteúdo. Veja como realizar matrículas e gerenciar seus status.</p>

<div class="article-steps">
    <div class="article-step">
        <div class="step-number">1</div>
        <div class="step-content">
            <h6>Acesse a gestão de Matrículas</h6>
            <p>No menu lateral, clique em <strong>Ensino → Matrículas</strong>. A página mostra todas as matrículas com filtros por:</p>
            <ul>
                <li><strong>Curso:</strong> Selecione um curso específico</li>
                <li><strong>Status:</strong> Ativo, Pendente ou Inativo</li>
                <li><strong>Conclusão:</strong> Concluído ou Não Concluído</li>
            </ul>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">2</div>
        <div class="step-content">
            <h6>Realizar nova matrícula</h6>
            <p>Clique em <strong>"Nova Matrícula"</strong>. No modal que abre, informe:</p>
            <ul>
                <li><strong>ID do Aluno:</strong> O número de ID do usuário (role "student")</li>
                <li><strong>Curso:</strong> Selecione na lista de cursos disponíveis</li>
                <li><strong>Status:</strong> Ativo (acesso imediato) ou Pendente (aguardando confirmação)</li>
            </ul>
            <p>Matrículas ativas definem automaticamente o pagamento como "pago". Matrículas pendentes ficam como "pagamento pendente".</p>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">3</div>
        <div class="step-content">
            <h6>Entender os status</h6>
            <p>Cada matrícula tem dois indicadores de status:</p>
            <table class="table table-sm mt-2">
                <thead>
                    <tr><th>Status</th><th>Badge</th><th>Significado</th></tr>
                </thead>
                <tbody>
                    <tr><td><strong>Ativo</strong></td><td><span class="badge bg-success">active</span></td><td>Aluno tem acesso ao curso</td></tr>
                    <tr><td><strong>Pendente</strong></td><td><span class="badge bg-warning text-dark">pending</span></td><td>Aguardando ativação</td></tr>
                    <tr><td><strong>Inativo</strong></td><td><span class="badge bg-secondary">inactive</span></td><td>Acesso suspenso</td></tr>
                </tbody>
            </table>
            <table class="table table-sm mt-2">
                <thead>
                    <tr><th>Pagamento</th><th>Badge</th><th>Significado</th></tr>
                </thead>
                <tbody>
                    <tr><td><strong>Pago</strong></td><td><span class="badge bg-success">paid</span></td><td>Pagamento confirmado</td></tr>
                    <tr><td><strong>Gratuito</strong></td><td><span class="badge bg-info">free</span></td><td>Curso gratuito</td></tr>
                    <tr><td><strong>Pendente</strong></td><td><span class="badge bg-warning text-dark">pending</span></td><td>Aguardando pagamento</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">4</div>
        <div class="step-content">
            <h6>Ações de gerenciamento</h6>
            <p>Na tabela de matrículas, cada linha tem botões de ação:</p>
            <ul>
                <li><strong>✅ Ativar:</strong> Transforma matrícula pendente/inativa em ativa</li>
                <li><strong>⏸️ Desativar:</strong> Suspende o acesso do aluno (temporário)</li>
                <li><strong>🗑️ Remover:</strong> Exclui a matrícula permanentemente</li>
            </ul>
        </div>
    </div>
</div>

<div class="help-tip">
    <i class="fas fa-lightbulb me-2"></i>
    <strong>Atenção:</strong> O sistema impede matrículas duplicadas — um aluno não pode ser matriculado duas vezes no mesmo curso.
</div>

<div class="help-tip help-tip-success">
    <i class="fas fa-check-circle me-2"></i>
    <strong>Próximo passo:</strong> Após matricular, <a href="/admin/help/alunos/acompanhar-progresso">acompanhe o progresso</a> do aluno.
</div>
