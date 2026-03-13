<p class="lead">O Material de Apoio organiza documentos pedagógicos em uma estrutura hierárquica de pastas com profundidade ilimitada, permitindo que professores e coordenadores acessem rapidamente manuais, atividades e referências organizadas por eixo e categoria.</p>

<div class="help-tip">
    <i class="fas fa-lightbulb me-2"></i>
    <strong>Importante:</strong> A estrutura de pastas padrão já vem pré-configurada (seeded) pelo sistema. O Administrador pode criar novas pastas, subpastas e reorganizar a hierarquia conforme necessário.
</div>

<h5 class="mt-4 mb-3"><i class="fas fa-folder-tree me-2 text-primary"></i>Acessando o Material de Apoio</h5>

<div class="article-steps">
    <div class="article-step">
        <div class="step-number">1</div>
        <div class="step-content">
            <h6>Acesse a seção de Material de Apoio</h6>
            <p>No menu lateral, navegue até <strong>Pedagógico → Material de Apoio</strong>. A página inicial exibe a visualização em árvore (tree view) da estrutura de pastas.</p>
            <ul>
                <li>A árvore mostra todas as pastas raiz no primeiro nível, com ícones de pasta para identificação visual</li>
                <li>Pastas que possuem subpastas exibem um indicador de expansão (seta) que pode ser clicado para revelar o conteúdo</li>
                <li>Todos os perfis (Professor, Coordenador, Administrador) podem navegar pela estrutura de pastas</li>
            </ul>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">2</div>
        <div class="step-content">
            <h6>Explore a estrutura hierárquica</h6>
            <p>A estrutura de pastas suporta <strong>aninhamento ilimitado</strong> — cada pasta pode conter subpastas, que por sua vez podem conter mais subpastas. A estrutura padrão (seeded) inclui:</p>
            <ul>
                <li><strong>Eixos de Atividades</strong>
                    <ul>
                        <li><strong>Manuais</strong> — materiais sobre atividades manuais e artes plásticas</li>
                        <li><strong>Musicais</strong> — partituras, letras, orientações de atividades musicais</li>
                        <li><strong>Contos</strong> — histórias, roteiros de contação, fichas de leitura</li>
                        <li><strong>Movimento</strong> — atividades de expressão corporal e psicomotricidade</li>
                    </ul>
                </li>
                <li><strong>Centros de Aprendizagem</strong> — materiais sobre organização e atividades nos centros</li>
                <li><strong>Famílias de Brinquedos</strong> — catalogação e orientações sobre brinquedos pedagógicos</li>
            </ul>
        </div>
    </div>
</div>

<div class="help-tip">
    <i class="fas fa-info-circle me-2"></i>
    <strong>Observação:</strong> O eixo <strong>PCA</strong> (Pedagogia de Comunicação Ativa) não possui subpasta na estrutura de Material de Apoio porque o material desse eixo é de natureza física (objetos, materiais concretos), não digital.
</div>

<h5 class="mt-4 mb-3"><i class="fas fa-mouse-pointer me-2 text-primary"></i>Navegando pelas pastas</h5>

<div class="article-steps">
    <div class="article-step">
        <div class="step-number">3</div>
        <div class="step-content">
            <h6>Clique em uma pasta para ver seu conteúdo</h6>
            <p>Ao clicar em uma pasta, a página exibe o conteúdo daquela pasta, que pode incluir:</p>
            <ul>
                <li><strong>Subpastas:</strong> exibidas no topo como cards ou links com ícone de pasta, permitindo navegar mais fundo na hierarquia</li>
                <li><strong>Arquivos:</strong> listados abaixo das subpastas, mostrando os documentos armazenados naquela pasta</li>
                <li>A navegação é intuitiva: clique em uma subpasta para entrar, ou clique em um arquivo para interagir com ele</li>
            </ul>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">4</div>
        <div class="step-content">
            <h6>Acompanhe sua localização pelo breadcrumb</h6>
            <p>No topo da página de cada pasta, um <strong>breadcrumb</strong> (trilha de navegação) mostra o caminho completo desde a raiz até a pasta atual:</p>
            <ul>
                <li>Exemplo: <code>Material de Apoio → Eixos de Atividades → Musicais</code></li>
                <li>Cada nível do breadcrumb é clicável, permitindo voltar rapidamente a qualquer pasta ancestral</li>
                <li>O breadcrumb facilita a orientação quando você está em níveis profundos da hierarquia</li>
            </ul>
        </div>
    </div>

    <div class="article-step">
        <div class="step-number">5</div>
        <div class="step-content">
            <h6>Visualize os detalhes dos arquivos</h6>
            <p>A lista de arquivos dentro de cada pasta exibe informações detalhadas para facilitar a identificação:</p>
            <ul>
                <li><strong>Ícone por tipo:</strong> ícone visual diferente para cada tipo de arquivo (PDF, Word, Excel, imagem, etc.)</li>
                <li><strong>Título:</strong> nome descritivo do material (definido no momento do upload)</li>
                <li><strong>Nome original:</strong> nome do arquivo como foi enviado pelo usuário</li>
                <li><strong>Tamanho:</strong> tamanho do arquivo em formato legível (KB, MB)</li>
                <li><strong>Data de upload:</strong> data e hora em que o arquivo foi adicionado ao sistema</li>
                <li><strong>Botão de download:</strong> clique para baixar o arquivo para seu computador</li>
                <li><strong>Botão de exclusão:</strong> visível apenas para Administradores, permite remover o arquivo</li>
            </ul>
        </div>
    </div>
</div>

<h5 class="mt-4 mb-3"><i class="fas fa-project-diagram me-2 text-primary"></i>Relacionamento pai/filho das pastas</h5>

<p>A estrutura de pastas é baseada em um modelo de <strong>relacionamento pai/filho</strong> (parent/child):</p>
<ul>
    <li>Cada pasta possui um campo <strong>parent_id</strong> que referencia a pasta pai. Pastas raiz possuem parent_id nulo</li>
    <li>Uma pasta pode ter <strong>múltiplas subpastas</strong> filhas, sem limite de quantidade</li>
    <li>A profundidade de aninhamento é <strong>ilimitada</strong> — você pode criar subpastas dentro de subpastas conforme a necessidade organizacional</li>
    <li>Ao excluir uma pasta que contém subpastas e arquivos, todo o conteúdo interno é removido em cascata</li>
</ul>

<h5 class="mt-4 mb-3"><i class="fas fa-user-shield me-2 text-primary"></i>Permissões de acesso</h5>

<div class="table-responsive">
    <table class="table table-sm table-bordered">
        <thead class="table-light">
            <tr>
                <th>Perfil</th>
                <th>Navegar pastas</th>
                <th>Ver arquivos</th>
                <th>Criar pastas</th>
                <th>Excluir pastas</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Professor</strong></td>
                <td class="text-success"><i class="fas fa-check"></i></td>
                <td class="text-success"><i class="fas fa-check"></i></td>
                <td class="text-danger"><i class="fas fa-times"></i></td>
                <td class="text-danger"><i class="fas fa-times"></i></td>
            </tr>
            <tr>
                <td><strong>Coordenador</strong></td>
                <td class="text-success"><i class="fas fa-check"></i></td>
                <td class="text-success"><i class="fas fa-check"></i></td>
                <td class="text-danger"><i class="fas fa-times"></i></td>
                <td class="text-danger"><i class="fas fa-times"></i></td>
            </tr>
            <tr>
                <td><strong>Administrador</strong></td>
                <td class="text-success"><i class="fas fa-check"></i></td>
                <td class="text-success"><i class="fas fa-check"></i></td>
                <td class="text-success"><i class="fas fa-check"></i></td>
                <td class="text-success"><i class="fas fa-check"></i></td>
            </tr>
        </tbody>
    </table>
</div>

<div class="help-tip help-tip-success">
    <i class="fas fa-check-circle me-2"></i>
    <strong>Próximo passo:</strong> Após entender a navegação pelas pastas, aprenda a <a href="/admin/help/material-apoio/upload-download">fazer upload e download de materiais</a>.
</div>
