# Revisao 05 - Tarefas Extraidas do PDF

Fonte: `docs/Revisao_05/AJUSTES PLATAFORMA.pdf`
Data de leitura: 2026-03-20

## Objetivo

Consolidar os ajustes solicitados pelo usuario no modulo pedagogico da plataforma, com foco em Observacoes, configuracao do PCA por escola, compilacao automatica do Parecer Descritivo e reorganizacao do menu lateral.

## Tarefas

### 1. Observacoes cumulativas por aluno no semestre

- Remover a regra atual que bloqueia uma segunda observacao para o mesmo aluno no mesmo semestre/ano.
- Permitir varios registros por aluno no mesmo semestre/ano.
- Aplicar limite operacional de ate 5 observacoes por aluno em cada semestre/ano.
- Exibir historico das observacoes ja registradas para o aluno no semestre acima da area de preenchimento.
- Garantir que a listagem/historico respeite a ordem cronologica dos registros.

### 2. PCA configuravel por escola

- Criar configuracao por escola para habilitar/desabilitar o eixo `Programa Comunicacao Ativa (PCA)`.
- Definir o comportamento padrao como desabilitado.
- Quando desabilitado:
- Nao exigir validacao do PCA.
- Nao permitir preenchimento do eixo PCA no formulario de observacoes.
- Quando habilitado:
- Exibir normalmente o eixo PCA.
- Permitir preenchimento sem torna-lo obrigatorio por padrao.
- Preservar registros antigos quando a escola mudar a configuracao no futuro.

### 3. Parecer Descritivo compilado a partir de todas as observacoes do semestre

- Alterar a geracao do parecer para considerar todas as observacoes do aluno no semestre/ano.
- Compilar os textos em ordem cronologica.
- Atualizar automaticamente o parecer quando observacoes do periodo forem criadas, editadas ou removidas.
- Ajustar a acao de recompilacao para usar o conjunto completo de observacoes, e nao apenas uma observacao vinculada.
- Ajustar a tela de criacao do parecer para deixar claro que a base e o conjunto de observacoes do periodo.

### 4. Correcao do menu lateral

- Restaurar os itens `Planejamentos`, `Material de Apoio` e `Banco de Imagens`.
- Inserir `Rotina Semanal` logo abaixo de `Material de Apoio`.
- Remover o item `Professores` do menu lateral solicitado no PDF.
- Garantir a ordem correta para a navegacao pedagogica:
- `Turmas`
- `Observacoes`
- `Planejamentos`
- `Material de Apoio`
- `Rotina Semanal`
- `Parecer`
- `Portfolio`
- `Banco de Imagem`

## Status inicial

- [x] PDF lido
- [x] Tarefas consolidadas em Markdown
- [x] Observacoes cumulativas implementadas
- [x] PCA configuravel por escola implementado
- [x] Parecer compilado por semestre implementado
- [x] Menu lateral corrigido
- [x] Validacoes finais executadas
