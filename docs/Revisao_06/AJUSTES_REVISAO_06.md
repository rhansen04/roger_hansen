# Revisao 06 - Ajustes Plataforma Hansen Educacional

**Data do documento original:** 20/03/2026
**Convertido em:** 21/03/2026

---

## AJUSTE 1 - Tela Inicial do Modulo Observacoes

### Problema
Na tela `/admin/observations`, quando um aluno possui mais de uma observacao cadastrada, seu nome aparece duplicado na listagem (1 linha por observacao em vez de 1 linha por aluno).

### Solucao Requerida

**Reestruturar a listagem** para que seja baseada na relacao de alunos da turma (nao nas observacoes). Cada aluno aparece **uma unica vez**.

**Nova estrutura de colunas (ordem exata):**

| # | Coluna | Descricao |
|---|--------|-----------|
| 1 | No | Numeracao sequencial (1, 2, 3...) - nao e ID do aluno |
| 2 | Aluno | Nome completo - cada aluno aparece apenas 1x |
| 3 | Semestre/Ano | Ex: "1o semestre / 2026" |
| 4 | Numero de Observacoes | Contagem automatica de obs do aluno no semestre |
| 5 | Status | "Em andamento" ou "Finalizado" - definido manualmente pelo professor |
| 6 | Atualizado em | Manter como esta |
| 7 | Acoes | Botoes: Visualizar + Editar |

**Regras adicionais:**
- Remover a coluna "Professor" da tabela
- Nao incluir nenhuma coluna adicional
- Nao exigir numero minimo de observacoes para finalizar
- A decisao de finalizacao e exclusiva do professor

**Arquivos envolvidos:**
- `app/Controllers/Admin/ObservationController.php` (metodo `index()`)
- `app/Models/Observation.php` (query de listagem)
- `views/admin/observations/index.php` (template da tabela)

---

## AJUSTE 2 - Toggle PCA (Programa Comunicacao Ativa) por Escola

### Problema
O eixo "Programa Comunicacao Ativa" (PCA) deve ser configuravel por escola. Nem todas as escolas utilizam este programa.

### Solucao Requerida

**Configuracao no painel admin da escola** (Configuracoes da Escola > Programas Ativos):
- Toggle/checkbox "Programa Comunicacao Ativa" (ja existe `pca_enabled` na tabela schools)

**Quando DESABILITADO:**
- Ocultar completamente a aba PCA no modulo Observacoes (nao apenas desabilitar visualmente)
- Professor pode criar/editar/salvar observacoes normalmente
- Sistema NAO deve validar nem exigir preenchimento do eixo PCA

**Quando HABILITADO:**
- Eixo PCA aparece normalmente, visivel, editavel, com mesmas regras dos demais eixos

**Persistencia:** Se a escola habilitar o PCA no futuro, observacoes antigas nao precisam ser alteradas retroativamente. Apenas novas observacoes exibirao o eixo.

**Arquivos envolvidos:**
- `app/Models/School.php` (campo `pca_enabled` ja existe)
- `app/Controllers/Admin/ObservationController.php` (metodo `buildPcaEnabledByStudent()` ja existe)
- `views/admin/observations/create.php` e `edit.php` (ocultar aba PCA)
- `views/admin/observations/_questions.php` (condicional PCA)
- `views/admin/schools/edit.php` (toggle PCA)

---

## AJUSTE 3A - Parecer Descritivo: Compilacao Automatica

### Problema
O Parecer Descritivo NAO esta compilando automaticamente os textos das observacoes registradas para o aluno no semestre. O campo deveria ser preenchido automaticamente.

### Solucao Requerida

**Ao criar/abrir um Parecer Descritivo:**
1. Buscar todas as observacoes do aluno no semestre vigente
2. Consolidar os textos em um unico conteudo continuo (ordem cronologica)
3. Atualizar dinamicamente quando observacoes forem adicionadas/editadas/removidas

**Arquivos envolvidos:**
- `app/Controllers/Admin/DescriptiveReportController.php` (metodos `create()`, `store()`, `recompile()`)
- `views/admin/descriptive-reports/create.php`

---

## AJUSTE 3B - Botao Editar na Tela de Detalhes do Aluno

### Problema
Na pagina de detalhes do aluno (`/admin/students/{id}`), na secao "Observacoes do Aluno", falta o botao "Editar" nas acoes de cada observacao listada.

### Solucao Requerida
Adicionar botao "Editar" ao lado do botao existente (Visualizar) na coluna Acoes da tabela de observacoes na view de detalhes do aluno.

**Arquivos envolvidos:**
- `views/admin/students/show.php` (secao de observacoes do aluno)

---

## AJUSTE 4 - Salvamento Parcial de Observacoes (Eixos)

### Problema
Ao clicar em "Criar Observacao" preenchendo apenas 1 eixo, o sistema exige o preenchimento obrigatorio de todos os demais eixos, impedindo salvamento parcial.

### Solucao Requerida

**Regras obrigatorias:**
1. **Salvamento parcial permitido** - Nao exigir preenchimento de todos os eixos. Permitir salvar com 1, 2 ou qualquer quantidade de eixos preenchidos.
2. **Continuidade** - Ao retornar ao registro, carregar dados ja preenchidos e manter eixos vazios editaveis. Complementar a mesma observacao (nao criar nova).
3. **Identificacao** - Observacao com eixos incompletos fica "em andamento".
4. **Botao** - Renomear "Criar Observacao" para "Salvar Observacao". Permitir salvar com eixos em branco sem gerar erro.

**Arquivos envolvidos:**
- `app/Controllers/Admin/ObservationController.php` (metodo `store()` - remover validacao obrigatoria dos eixos)
- `views/admin/observations/create.php` (renomear botao)
- `views/admin/observations/_questions.php` (remover required dos campos)

---

## AJUSTE 5 - Menu Planejamento: Tela Novo Planejamento

### Problema 1 - Remocao da funcao Template
A tela `/admin/planning/create` exibe a funcao "Template" que nao faz parte da proposta do modulo.

**Correcao:** Remover completamente o campo/selecao de Template da tela Novo Planejamento.

### Problema 2 - Estrutura de colunas
A tabela da tela Novo Planejamento deve ter colunas em ordem fixa:

| # | Coluna | Descricao |
|---|--------|-----------|
| 1 | No | Numeracao sequencial automatica |
| 2 | Turma | Nome da turma do planejamento |
| 3 | Inicio | Date picker para data de inicio (DD/MM/AAAA) |
| 4 | Fim | Date picker para data de termino (DD/MM/AAAA) |

### Problema 3 - Campos de data nao funcionais
Os campos Inicio e Fim nao estao funcionando corretamente (nao salvam, nao exibem, ou nao permitem selecao).

**Correcao:** Garantir que os campos de data usem date picker, aceitem apenas datas validas no formato DD/MM/AAAA, salvem e exibam corretamente.

**Arquivos envolvidos:**
- `app/Controllers/Admin/PlanningController.php` (metodo `create()`, `store()`)
- `app/Models/PlanningSubmission.php`
- `views/admin/planning/create.php` ou `form.php` (template do formulario)
- `views/admin/planning/index.php` (listagem)
