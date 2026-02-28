<p class="lead">Campos condicionais permitem que partes do formulário de planejamento apareçam ou se ocultem automaticamente dependendo do valor selecionado em outro campo — tornando o formulário mais limpo e focado.</p>

<div class="help-tip">
    <i class="fas fa-lightbulb me-2"></i>
    <strong>Exemplo prático:</strong> Na Seção 2, o professor escolhe o <strong>Eixo da Vivência</strong> (ex: "Musical"). Automaticamente, apenas os objetivos de aprendizagem relacionados ao eixo Musical aparecem na Seção 4, ocultando os demais. O formulário se adapta em tempo real.
</div>

<h6 class="fw-bold mt-4 mb-3">Como funciona para o professor</h6>

<div class="article-steps">
    <div class="article-step">
        <div class="step-number">1</div>
        <div class="step-content">
            <h6>Preencha o campo controlador</h6>
            <p>No formulário de planejamento, preencha o campo que controla os demais — geralmente um campo do tipo <strong>Radio</strong> ou <strong>Seleção</strong>. Por exemplo, selecione o <strong>Eixo da Vivência</strong> na Seção 2.</p>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">2</div>
        <div class="step-content">
            <h6>Os campos dependentes aparecem automaticamente</h6>
            <p>Ao selecionar um valor, todos os campos que dependem desse valor se tornam visíveis instantaneamente — sem recarregar a página. Campos relacionados a outros valores permanecem ocultos.</p>
            <p>Por exemplo, ao selecionar <strong>"Musical"</strong>, somente o bloco de objetivos de aprendizagem musicais aparece na Seção 4 (Objetivos). Os blocos de Manuais, Contos, Movimento e PCA permanecem ocultos.</p>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">3</div>
        <div class="step-content">
            <h6>Os campos ocultos não são enviados</h6>
            <p>Campos condicionais que estão ocultos são automaticamente desabilitados antes do envio. Isso evita que dados em branco de seções não utilizadas sejam gravados, mantendo o planejamento limpo.</p>
        </div>
    </div>
</div>

<div class="help-tip help-tip-success">
    <i class="fas fa-check-circle me-2"></i>
    <strong>Eixos e objetivos disponíveis:</strong> Os eixos configurados no sistema são <strong>Manual, Musical, Contos, Movimento e PCA</strong> (Pedagogia de Comunicação Ativa). Cada eixo habilita seu próprio conjunto de objetivos de aprendizagem.
</div>

<h6 class="fw-bold mt-4 mb-3">Como o administrador configura dependências</h6>

<div class="article-steps">
    <div class="article-step">
        <div class="step-number">1</div>
        <div class="step-content">
            <h6>Abra o editor do template</h6>
            <p>Acesse <strong>Ensino → Templates Planej.</strong> e clique em <strong>Editar</strong> no template desejado. Expanda a seção onde o novo campo ficará.</p>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">2</div>
        <div class="step-content">
            <h6>Adicione o campo com dependência</h6>
            <p>No formulário <strong>"Adicionar Campo"</strong> (no rodapé de cada seção), preencha:</p>
            <ul>
                <li><strong>Rótulo</strong> — nome do campo (ex: "Objetivos Musicais")</li>
                <li><strong>Tipo</strong> — o tipo de campo desejado (texto longo, checkbox, etc.)</li>
                <li><strong>Depende do campo</strong> — selecione no dropdown o campo controlador. A lista mostra todos os campos do template no formato <code>#ID — Nome do campo (tipo)</code>. Escolha o campo Radio ou Seleção que servirá de gatilho.</li>
                <li><strong>Valor que habilita</strong> — digite exatamente o valor que faz este campo aparecer (ex: <code>Musical</code>). O valor deve corresponder a uma das opções do campo controlador.</li>
            </ul>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">3</div>
        <div class="step-content">
            <h6>Salve e verifique o badge de dependência</h6>
            <p>Após adicionar, o campo aparece no preview da seção com um <strong>badge azul</strong> indicando a dependência configurada:</p>
            <div class="mt-2 mb-2">
                <span class="badge bg-info text-dark"><i class="fas fa-link me-1"></i>Depende de #12 = Musical</span>
            </div>
            <p>Isso confirma que o campo só será exibido quando o campo de ID 12 tiver o valor "Musical" selecionado.</p>
        </div>
    </div>
</div>

<div class="help-tip">
    <i class="fas fa-exclamation-circle me-2"></i>
    <strong>Atenção ao valor digitado:</strong> O campo <strong>"Valor que habilita"</strong> deve ser idêntico (incluindo maiúsculas) à opção exata do campo controlador. Se o radio tem a opção "Manual", o valor deve ser <code>Manual</code> — não <code>manual</code> nem <code>MANUAL</code>.
</div>

<h6 class="fw-bold mt-4 mb-3">Resumo: campos controladores e dependentes</h6>

<div class="table-responsive">
    <table class="table table-sm table-bordered">
        <thead class="table-light">
            <tr>
                <th>Conceito</th>
                <th>Descrição</th>
                <th>Exemplo</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Campo controlador</strong></td>
                <td>Campo cujo valor ativa outros campos. Geralmente Radio ou Seleção.</td>
                <td>Eixo da Vivência (Radio)</td>
            </tr>
            <tr>
                <td><strong>Campo dependente</strong></td>
                <td>Campo que só aparece quando o controlador tem um valor específico.</td>
                <td>Objetivos Musicais (Checkbox)</td>
            </tr>
            <tr>
                <td><strong>Valor que habilita</strong></td>
                <td>Valor exato do controlador que faz o campo dependente aparecer.</td>
                <td><code>Musical</code></td>
            </tr>
            <tr>
                <td><strong>Badge de dependência</strong></td>
                <td>Indicador visual azul no editor de templates para campos condicionais.</td>
                <td><span class="badge bg-info text-dark"><i class="fas fa-link me-1"></i>Depende de #12 = Musical</span></td>
            </tr>
        </tbody>
    </table>
</div>

<div class="help-tip help-tip-success">
    <i class="fas fa-check-circle me-2"></i>
    <strong>Dica profissional:</strong> Um campo controlador pode habilitar múltiplos campos dependentes com o mesmo valor. Por exemplo, vários checkboxes de objetivos podem depender do mesmo eixo selecionado. Basta configurar cada campo dependente com o mesmo "Depende do campo" e "Valor que habilita".
</div>

<h6 class="fw-bold mt-4 mb-3"><i class="fas fa-play-circle me-2 text-primary"></i>Exemplo interativo — experimente aqui</h6>

<div class="card border-primary" style="border-radius: 12px; overflow: hidden;">
    <div class="card-header bg-primary text-white py-2">
        <small class="fw-semibold"><i class="fas fa-chalkboard-teacher me-2"></i>Simulação do formulário de planejamento</small>
    </div>
    <div class="card-body p-4" style="background: #f8f9fa; color: #212529;">

        <!-- Campo controlador -->
        <div class="mb-4">
            <label class="form-label fw-bold" style="color: #212529;">
                Eixo da Vivência <span class="text-danger">*</span>
            </label>
            <small class="d-block text-muted mb-2">Selecione um eixo e veja os campos da Seção 4 aparecerem automaticamente:</small>
            <div class="d-flex flex-wrap gap-2" id="demo-eixos">
                <?php foreach (['Manual', 'Musical', 'Contos', 'Movimento', 'PCA'] as $eixo): ?>
                <div class="form-check form-check-inline">
                    <input class="form-check-input demo-ctrl" type="radio" name="demo_eixo"
                        id="demo_<?= strtolower($eixo) ?>" value="<?= $eixo ?>">
                    <label class="form-check-label fw-semibold" for="demo_<?= strtolower($eixo) ?>"
                        style="color: #212529;"><?= $eixo ?></label>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <hr>
        <p class="text-muted small mb-3"><i class="fas fa-layer-group me-1"></i><strong>Seção 4 — Objetivos de Aprendizagem</strong> (campos condicionais):</p>

        <!-- Campos dependentes -->
        <?php
        $eixos = [
            'Manual'    => ['Exploração de materiais', 'Coordenação motora fina', 'Criatividade plástica'],
            'Musical'   => ['Percepção rítmica', 'Expressão vocal', 'Escuta ativa'],
            'Contos'    => ['Compreensão narrativa', 'Vocabulário e linguagem', 'Imaginação e fantasia'],
            'Movimento' => ['Coordenação motora ampla', 'Equilíbrio e lateralidade', 'Consciência corporal'],
            'PCA'       => ['Comunicação funcional', 'Interação social', 'Autonomia e iniciativa'],
        ];
        $colors = [
            'Manual' => '#fd7e14', 'Musical' => '#20c997', 'Contos' => '#6f42c1',
            'Movimento' => '#0d6efd', 'PCA' => '#e83e8c',
        ];
        foreach ($eixos as $eixo => $objetivos):
            $cor = $colors[$eixo];
        ?>
        <div class="demo-dep mb-3 p-3" data-eixo="<?= $eixo ?>"
            style="display:none; border-left: 4px solid <?= $cor ?>; background: #fff; border-radius: 0 8px 8px 0; color: #212529;">
            <div class="fw-semibold mb-2" style="color: <?= $cor ?>;">
                <i class="fas fa-check-square me-1"></i>Objetivos — Eixo <?= $eixo ?>
            </div>
            <?php foreach ($objetivos as $obj): ?>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" disabled>
                <label class="form-check-label" style="color: #212529;"><?= $obj ?></label>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endforeach; ?>

        <div id="demo-placeholder" class="text-center text-muted py-3" style="border: 2px dashed #dee2e6; border-radius: 8px;">
            <i class="fas fa-arrow-up me-2"></i>Selecione um eixo acima para ver os objetivos correspondentes
        </div>

    </div>
</div>

<script>
(function() {
    document.querySelectorAll('.demo-ctrl').forEach(function(radio) {
        radio.addEventListener('change', function() {
            var val = this.value;
            document.getElementById('demo-placeholder').style.display = 'none';
            document.querySelectorAll('.demo-dep').forEach(function(dep) {
                dep.style.display = dep.dataset.eixo === val ? '' : 'none';
            });
        });
    });
})();
</script>
