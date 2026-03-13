<p class="lead">Aprenda a enviar novos materiais de apoio para as pastas, baixar documentos disponíveis e gerenciar os arquivos armazenados na plataforma.</p>

<div class="help-tip">
    <i class="fas fa-lightbulb me-2"></i>
    <strong>Permissões:</strong> O <strong>upload</strong> e a <strong>exclusão</strong> de materiais são restritos ao <strong>Administrador</strong>. Professores e Coordenadores podem <strong>navegar</strong> pelas pastas e <strong>baixar</strong> os arquivos disponíveis.
</div>

<h5 class="mt-4 mb-3"><i class="fas fa-cloud-upload-alt me-2 text-primary"></i>Upload de materiais (Administrador)</h5>

<div class="article-steps">
    <div class="article-step">
        <div class="step-number">1</div>
        <div class="step-content">
            <h6>Navegue até a pasta de destino</h6>
            <p>Acesse <strong>Pedagógico → Material de Apoio</strong> e navegue pela estrutura de pastas até encontrar a pasta onde deseja armazenar o novo material. O arquivo será vinculado à pasta que estiver aberta no momento do upload.</p>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">2</div>
        <div class="step-content">
            <h6>Clique no botão "Upload"</h6>
            <p>Dentro da pasta desejada, clique no botão <strong>"Upload"</strong> disponível no topo da listagem de arquivos. O formulário de envio será exibido com os seguintes campos:</p>
            <ul>
                <li><strong>Arquivo:</strong> clique para selecionar o arquivo do seu computador</li>
                <li><strong>Título:</strong> nome descritivo do material. Se deixado em branco, o sistema preenche automaticamente com o nome original do arquivo (sem a extensão)</li>
            </ul>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">3</div>
        <div class="step-content">
            <h6>Selecione o arquivo e confirme</h6>
            <p>O sistema aceita diversos formatos de arquivo para atender às diferentes necessidades pedagógicas:</p>
            <ul>
                <li><strong>PDF</strong> — formato principal e mais recomendado para documentos pedagógicos</li>
                <li><strong>Word</strong> (.doc, .docx) — documentos editáveis de texto</li>
                <li><strong>Excel</strong> (.xls, .xlsx) — planilhas e tabelas</li>
                <li><strong>Imagens</strong> (.jpg, .png, .gif) — fotos, ilustrações e diagramas</li>
                <li><strong>Outros formatos:</strong> qualquer tipo de arquivo pode ser enviado conforme a necessidade</li>
            </ul>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">4</div>
        <div class="step-content">
            <h6>Arquivo armazenado com metadados</h6>
            <p>Ao enviar, o sistema armazena o arquivo no servidor e registra os seguintes metadados no banco de dados:</p>
            <ul>
                <li><strong>Nome original:</strong> nome do arquivo como foi enviado pelo usuário</li>
                <li><strong>Tamanho:</strong> tamanho do arquivo em bytes</li>
                <li><strong>Tipo MIME:</strong> tipo do conteúdo do arquivo (application/pdf, image/jpeg, etc.)</li>
                <li><strong>Usuário:</strong> identificação de quem realizou o upload</li>
                <li><strong>Data/hora:</strong> registro do momento exato do upload</li>
                <li><strong>Título:</strong> nome descritivo definido pelo administrador</li>
            </ul>
        </div>
    </div>
</div>

<h5 class="mt-4 mb-3"><i class="fas fa-download me-2 text-primary"></i>Download de materiais</h5>

<div class="article-steps">
    <div class="article-step">
        <div class="step-number">1</div>
        <div class="step-content">
            <h6>Localize o material desejado</h6>
            <p>Navegue pela estrutura de pastas até encontrar o arquivo que deseja baixar. Os arquivos são listados dentro de cada pasta com ícone, título, nome original, tamanho e data de upload para facilitar a identificação.</p>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">2</div>
        <div class="step-content">
            <h6>Clique no botão de download</h6>
            <p>Ao lado de cada arquivo, clique no botão de <strong>download</strong> (ícone de seta para baixo). O navegador iniciará o download automaticamente.</p>
            <ul>
                <li>O arquivo é baixado com o <strong>nome original</strong> preservado (o mesmo nome que foi usado no momento do upload)</li>
                <li>O cabeçalho <strong>Content-Type</strong> é enviado corretamente conforme o tipo MIME do arquivo, garantindo que o navegador reconheça o tipo de conteúdo</li>
                <li>PDFs podem ser abertos diretamente no navegador, dependendo das configurações do usuário</li>
                <li>O download está disponível para <strong>todos os perfis</strong>: Professor, Coordenador e Administrador</li>
            </ul>
        </div>
    </div>
</div>

<h5 class="mt-4 mb-3"><i class="fas fa-trash-alt me-2 text-danger"></i>Excluir materiais (Administrador)</h5>

<div class="article-steps">
    <div class="article-step">
        <div class="step-number">1</div>
        <div class="step-content">
            <h6>Clique no botão de exclusão</h6>
            <p>Na lista de arquivos da pasta, clique no botão de <strong>excluir</strong> (ícone de lixeira) ao lado do arquivo que deseja remover. Este botão é visível apenas para Administradores.</p>
            <ul>
                <li>Uma caixa de confirmação será exibida para evitar exclusões acidentais</li>
                <li>Ao confirmar, o arquivo é removido permanentemente tanto da <strong>pasta no disco</strong> quanto do <strong>banco de dados</strong></li>
                <li><strong>Ação irreversível:</strong> não há lixeira ou mecanismo de recuperação. Certifique-se de que o material não é mais necessário antes de excluir</li>
            </ul>
        </div>
    </div>
</div>

<h5 class="mt-4 mb-3"><i class="fas fa-user-shield me-2 text-primary"></i>Resumo de permissões</h5>

<div class="table-responsive">
    <table class="table table-sm table-bordered">
        <thead class="table-light">
            <tr>
                <th>Ação</th>
                <th>Professor</th>
                <th>Coordenador</th>
                <th>Administrador</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Navegar pastas</td>
                <td class="text-success"><i class="fas fa-check"></i></td>
                <td class="text-success"><i class="fas fa-check"></i></td>
                <td class="text-success"><i class="fas fa-check"></i></td>
            </tr>
            <tr>
                <td>Download de arquivos</td>
                <td class="text-success"><i class="fas fa-check"></i></td>
                <td class="text-success"><i class="fas fa-check"></i></td>
                <td class="text-success"><i class="fas fa-check"></i></td>
            </tr>
            <tr>
                <td>Upload de arquivos</td>
                <td class="text-danger"><i class="fas fa-times"></i></td>
                <td class="text-danger"><i class="fas fa-times"></i></td>
                <td class="text-success"><i class="fas fa-check"></i></td>
            </tr>
            <tr>
                <td>Excluir arquivos</td>
                <td class="text-danger"><i class="fas fa-times"></i></td>
                <td class="text-danger"><i class="fas fa-times"></i></td>
                <td class="text-success"><i class="fas fa-check"></i></td>
            </tr>
        </tbody>
    </table>
</div>

<div class="help-tip help-tip-success">
    <i class="fas fa-check-circle me-2"></i>
    <strong>Dica:</strong> Ao fazer upload de materiais, use títulos descritivos e claros. Um bom título como "Manual de Atividades Musicais - Faixa 3 a 4 anos" ajuda os professores a encontrar rapidamente o material que precisam, sem necessidade de abrir o arquivo.
</div>
