<p class="lead">Aprenda a enviar fotos para o Banco de Imagens, editar legendas, mover fotos entre pastas e excluir registros. Todas as operações são realizadas dentro de uma pasta específica da turma.</p>

<div class="help-tip">
    <i class="fas fa-lightbulb me-2"></i>
    <strong>Lembrete:</strong> Apenas <strong>Professores</strong> (em suas próprias turmas) e <strong>Administradores</strong> podem realizar upload, edição, movimentação e exclusão de fotos. Coordenadores possuem acesso somente leitura.
</div>

<h5 class="mt-4 mb-3"><i class="fas fa-cloud-upload-alt me-2 text-primary"></i>Upload de fotos</h5>

<div class="article-steps">
    <div class="article-step">
        <div class="step-number">1</div>
        <div class="step-content">
            <h6>Navegue até a pasta desejada</h6>
            <p>Acesse <strong>Pedagógico → Banco de Imagens</strong>, selecione a turma e clique na pasta onde deseja adicionar fotos (pasta coletiva "Registros Coletivos" ou pasta individual do aluno).</p>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">2</div>
        <div class="step-content">
            <h6>Clique no botão "Upload"</h6>
            <p>No topo da visualização da pasta, clique no botão <strong>"Upload"</strong>. Uma janela de seleção de arquivos será aberta pelo navegador.</p>
            <ul>
                <li><strong>Seleção múltipla:</strong> você pode selecionar várias fotos de uma vez segurando a tecla <code>Ctrl</code> (ou <code>Cmd</code> no Mac) ao clicar nos arquivos</li>
                <li><strong>Formatos aceitos:</strong> JPG, JPEG e PNG</li>
                <li>Outros formatos de imagem não são aceitos e serão rejeitados pelo sistema</li>
            </ul>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">3</div>
        <div class="step-content">
            <h6>Redimensionamento automático</h6>
            <p>Ao enviar, o sistema processa cada imagem automaticamente utilizando a biblioteca <strong>GD</strong> do PHP:</p>
            <ul>
                <li>Imagens com largura superior a <strong>1920 pixels</strong> são redimensionadas proporcionalmente para no máximo 1920px de largura</li>
                <li>A altura é ajustada proporcionalmente para manter o aspecto original da foto</li>
                <li>Imagens menores que 1920px são mantidas no tamanho original</li>
                <li>O redimensionamento reduz o tamanho do arquivo sem perda perceptível de qualidade, economizando espaço em disco</li>
            </ul>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">4</div>
        <div class="step-content">
            <h6>Armazenamento no servidor</h6>
            <p>Cada foto é salva no disco seguindo a estrutura de diretórios:</p>
            <ul>
                <li><strong>Caminho:</strong> <code>/uploads/image-bank/{classroom_id}/{folder_type}/</code></li>
                <li>O nome do arquivo é gerado automaticamente para evitar conflitos</li>
                <li>No banco de dados são registrados: <strong>nome do arquivo</strong>, <strong>nome original</strong>, <strong>tamanho do arquivo</strong>, <strong>tipo MIME</strong>, <strong>usuário que fez o upload</strong> e <strong>data/hora</strong></li>
            </ul>
        </div>
    </div>
</div>

<h5 class="mt-4 mb-3"><i class="fas fa-pen me-2 text-primary"></i>Editar legendas</h5>

<div class="article-steps">
    <div class="article-step">
        <div class="step-number">1</div>
        <div class="step-content">
            <h6>Clique na área de legenda da foto</h6>
            <p>Abaixo de cada miniatura, há uma área de legenda. Clique diretamente nessa área para ativar o modo de edição inline. Um campo de texto aparecerá para que você possa digitar ou alterar a legenda.</p>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">2</div>
        <div class="step-content">
            <h6>Digite a legenda e salve automaticamente</h6>
            <p>A legenda é salva automaticamente via <strong>AJAX</strong> ao sair do campo (evento blur) ou pressionar Enter. Não é necessário clicar em nenhum botão de salvar.</p>
            <ul>
                <li>A legenda pode descrever a atividade, o momento ou o contexto da foto</li>
                <li>Legendas bem escritas facilitam a busca e organização posterior</li>
                <li>Uma confirmação visual (feedback) aparece brevemente indicando que a legenda foi salva com sucesso</li>
            </ul>
        </div>
    </div>
</div>

<h5 class="mt-4 mb-3"><i class="fas fa-exchange-alt me-2 text-primary"></i>Mover fotos entre pastas</h5>

<div class="article-steps">
    <div class="article-step">
        <div class="step-number">1</div>
        <div class="step-content">
            <h6>Selecione a opção "Mover"</h6>
            <p>Na visualização da foto ou no menu de ações da miniatura, clique na opção <strong>"Mover"</strong>. Um dropdown será exibido com todas as pastas disponíveis dentro da mesma turma.</p>
            <ul>
                <li>A movimentação é restrita a pastas <strong>dentro da mesma turma</strong> — não é possível mover fotos entre turmas diferentes</li>
                <li>O dropdown lista a pasta "Registros Coletivos" e todas as pastas individuais dos alunos</li>
                <li>A pasta atual da foto não aparece na lista de destinos</li>
            </ul>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">2</div>
        <div class="step-content">
            <h6>Selecione a pasta de destino</h6>
            <p>Clique na pasta de destino desejada. A foto será movida instantaneamente e desaparecerá da pasta atual, aparecendo na pasta selecionada.</p>
            <ul>
                <li>O arquivo físico no disco é realocado para o diretório correspondente à nova pasta</li>
                <li>A legenda e demais metadados da foto são preservados após a movimentação</li>
            </ul>
        </div>
    </div>
</div>

<h5 class="mt-4 mb-3"><i class="fas fa-trash-alt me-2 text-danger"></i>Excluir fotos</h5>

<div class="article-steps">
    <div class="article-step">
        <div class="step-number">1</div>
        <div class="step-content">
            <h6>Clique no botão de exclusão</h6>
            <p>No menu de ações da miniatura ou na visualização ampliada, clique no botão de <strong>excluir</strong> (ícone de lixeira). Uma caixa de confirmação será exibida para evitar exclusões acidentais.</p>
            <ul>
                <li><strong>Ação irreversível:</strong> ao confirmar, a foto é removida permanentemente tanto do banco de dados quanto do disco do servidor</li>
                <li>Não há lixeira ou possibilidade de recuperação — certifique-se antes de confirmar</li>
                <li>Apenas o professor da turma e o administrador podem excluir fotos</li>
            </ul>
        </div>
    </div>
</div>

<h5 class="mt-4 mb-3"><i class="fas fa-database me-2 text-primary"></i>Dados registrados por foto</h5>

<div class="table-responsive">
    <table class="table table-sm table-bordered">
        <thead class="table-light">
            <tr>
                <th>Campo</th>
                <th>Descrição</th>
            </tr>
        </thead>
        <tbody>
            <tr><td><strong>filename</strong></td><td>Nome do arquivo no servidor (gerado automaticamente)</td></tr>
            <tr><td><strong>original_name</strong></td><td>Nome original do arquivo enviado pelo usuário</td></tr>
            <tr><td><strong>file_size</strong></td><td>Tamanho do arquivo em bytes</td></tr>
            <tr><td><strong>mime_type</strong></td><td>Tipo MIME do arquivo (image/jpeg, image/png)</td></tr>
            <tr><td><strong>caption</strong></td><td>Legenda editável da foto</td></tr>
            <tr><td><strong>uploaded_by</strong></td><td>Usuário que realizou o upload</td></tr>
            <tr><td><strong>created_at</strong></td><td>Data e hora do upload</td></tr>
        </tbody>
    </table>
</div>

<div class="help-tip help-tip-success">
    <i class="fas fa-check-circle me-2"></i>
    <strong>Dica:</strong> Envie as fotos logo após a atividade pedagógica, enquanto o contexto está fresco. Assim, as legendas serão mais precisas e o registro visual mais completo.
</div>
