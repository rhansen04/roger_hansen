# TAREFAS DA PLATAFORMA - Acompanhamento Pedagógico

> Documento gerado a partir dos pedidos dos usuários (Reunião 02/03/2026 - Roger, Regis, Laryssa)
> Acesso temporário: http://154.38.189.82:8080/
>
> **Legenda:**
> - 🔴 **[QUESTÃO]** = Informação incompleta ou inconclusiva — requer esclarecimento
> - ✅ = Tarefa clara e pronta para execução

---

## MÓDULO 1 — DASHBOARD DO PROFESSOR

**Ref:** http://154.38.189.82:8080/admin/dashboard

### Tarefa 1.1 — Menu Lateral do Professor
Implementar menu lateral com os seguintes itens:
1. Cursos
2. Turmas
3. Planejamento
4. Parecer Descritivo
5. Portfólio
6. Material de Apoio
7. Banco de Imagens

### Tarefa 1.2 — Cards do Dashboard
Exibir no dashboard os seguintes indicadores:
- **Cursos** — Porcentagem de realização do curso do professor
- **Turmas ativas** — Turmas em que o professor é responsável
- **Nº de alunos** — Total de alunos sob responsabilidade do professor
- **Pareceres Descritivos** — Nº de pareceres pendentes
- **Portfólio** — Status: Finalizado ou Pendente

> 🔴 **[QUESTÃO 1]:** O card "Cursos" exibe a porcentagem de qual curso? Se o professor tiver múltiplos cursos, exibe uma média ou lista cada um separadamente?

> 🔴 **[QUESTÃO 2]:** O card "Portfólio" mostra apenas Finalizado/Pendente. Deve mostrar um contador (ex: "2 pendentes, 3 finalizados") ou apenas o status do portfólio mais recente?

---

## MÓDULO 2 — TURMAS

**Ref:** http://154.38.189.82:8080/admin/classrooms

### Tarefa 2.1 — Listagem de Turmas
- Exibir lista de turmas cadastradas
- Botão destacado no topo: **"+ Nova Turma"**

### Tarefa 2.2 — Formulário de Criação de Turma
Ao clicar em "+ Nova Turma", abrir formulário com:
- Nome da Turma (campo texto)
- Ano letivo
- Nome do Professor
- Botão Salvar

### Tarefa 2.3 — Exibição de Turma Criada
Após salvar, exibir em formato de cards ou lista:
- Nome da turma + Professora responsável
- Nº de alunos
- Ano letivo
- Status

> 🔴 **[QUESTÃO 3]:** Quais são os status possíveis de uma turma? (Ativa, Inativa, Arquivada?)

### Tarefa 2.4 — Acesso à Lista de Alunos
Ao clicar na turma → abrir a lista de alunos pertencentes àquela turma.

---

## MÓDULO 3 — ALUNOS

**Ref:** http://154.38.189.82:8080/admin/students

### Tarefa 3.1 — Botão Adicionar Aluno
Dentro da turma, exibir botão: **"Adicionar Aluno"**

### Tarefa 3.2 — Formulário de Cadastro de Aluno
Campos obrigatórios:
- Nome da criança (texto)
- Data de Nascimento (seletor de data)
- Turma (preenchido automaticamente conforme turma aberta)
- Foto (upload de imagem — JPG/PNG)
- Botão Salvar

### Tarefa 3.3 — Lista de Alunos na Turma
Após cadastro, a tela da turma deve exibir tabela:

| Foto | Nome da Criança | Data de Nascimento | Idade | Ação |
|------|-----------------|-------------------|-------|------|
| 📷  | Nome            | DD/MM/AAAA        | Xcalc | Acessar |

- A idade deve ser calculada automaticamente com base na data de nascimento.

### Tarefa 3.4 — Perfil do Aluno
**Ref:** http://154.38.189.82:8080/admin/students/16/edit

Ao clicar no aluno, abrir página de perfil com:
- Foto da criança
- Nome completo
- Data de nascimento
- Idade
- Turma
- Professor responsável
- Botão **"Observação"** (clicável → direciona para seção de Observações)
- Botão **"Parecer Descritivo"** (clicável → direciona para seção de Parecer)

---

## MÓDULO 4 — OBSERVAÇÕES

### Tarefa 4.1 — Tela de Observações do Aluno
Ao clicar em "Observação" no perfil do aluno, exibir como abas ou menu lateral:
- Observações (listagem)
- ➕ Adicionar Nova Observação
- Observação Geral
- Eixo de Atividade de Movimento
- Eixo de Atividade Manual
- Eixo de Atividade Musical
- Eixo de Atividade de Contos
- Eixo Programa Comunicação Ativa

### Tarefa 4.2 — Listagem de Observações
Listar todas as observações já realizadas para o aluno:
- 📅 1º Semestre 2026 – Status: Finalizado
- 📅 2º Semestre 2026 – Status: Em andamento
- Botão: **Visualizar**
- Botão: **Editar** (somente se status = Em andamento)

### Tarefa 4.3 — Criar Nova Observação
Ao clicar em "Adicionar Nova Observação":
- Seleção de Período (trimestre)
- Data automática
- Status inicial: Em andamento

> 🔴 **[QUESTÃO 4]:** A listagem usa "Semestre" (1º e 2º Semestre), mas a criação usa "Trimestre". Qual é a periodicidade correta? Semestral ou trimestral?

### Tarefa 4.4 — Campos de Preenchimento da Observação
Todos os campos (Observação Geral + 5 Eixos):
- Tipo: Texto
- Limite visual de 5 linhas
- Salvamento automático

### Tarefa 4.5 — Finalizar Observação
No final da página, botão: **"Finalizar Registro"**

Ao clicar:
- Modal de confirmação: "Deseja finalizar esta observação? Após finalizar, não será possível editar."
  - Cancelar / Confirmar
- Após confirmação:
  - Campos ficam somente leitura
  - Status muda para **Finalizado**
  - Libera exportação para o Parecer Descritivo

### Tarefa 4.6 — Exportação para Parecer Descritivo
Na observação finalizada, botão: **"📄 Gerar Parecer Descritivo"**
- Compilar automaticamente: Observação Geral + Todos os Eixos
- Gerar documento estruturado
- Exportar em: PDF e Word (.docx)

### Tarefa 4.7 — Regras de Permissão das Observações
**Professor:**
- Pode cadastrar turma e aluno
- Pode criar e editar observações enquanto "Em andamento"

**Coordenação:**
- Pode editar qualquer turma
- Pode reabrir observações finalizadas
- Pode visualizar histórico

### Tarefa 4.8 — Salvamento Automático
- Salvar ao sair do campo
- Exibir aviso: "Salvo automaticamente às HH:MM"
- Não permitir perda de dados ao atualizar página
- Registro de data e usuário que editou

---

## MÓDULO 5 — PARECER DESCRITIVO

**Ref:** http://154.38.189.82:8080/admin/students

### Tarefa 5.1 — Acesso ao Parecer Descritivo
- Acessível pelo menu lateral → Professor vê lista de turmas → seleciona turma → vê cards dos pareceres por criança
- Também acessível diretamente dentro do perfil do aluno

### Tarefa 5.2 — Capa do Parecer (1 página inteira)
- Título centralizado: **"PARECER DESCRITIVO"**
- Subtítulo: "Acompanhamento do desenvolvimento da criança no ambiente escolar"
- Foto principal da criança (importada do banco de imagens)
- Regras:
  - Foto em destaque (proporção fixa)
  - Se não houver foto cadastrada → exibir aviso ao professor antes de gerar o PDF
  - Capa sempre ocupar 1 página inteira

### Tarefa 5.3 — Página 1: "Sobre o Parecer Descritivo"
- Texto introdutório fixo (padrão institucional)
- Não editável pelo professor

> 🔴 **[QUESTÃO 5]:** Qual é o texto institucional fixo da Página 1 do Parecer? Precisa ser fornecido para implementação.

### Tarefa 5.4 — Página 2: Texto Sobre a Criança
- Título: Nome da Criança
- Conteúdo: Importação automática da Observação Geral + Observação dos Eixos de Atividades (última observação finalizada do período selecionado)
- Regras:
  - Unificar todos os campos preenchidos
  - Eliminar repetições automáticas
  - Organizar em texto corrido estruturado
  - Preservar integralmente o conteúdo do professor

> 🔴 **[QUESTÃO 6]:** "Eliminar repetições automáticas" e "Organizar em texto corrido" — isso será feito manualmente pelo professor ou via IA/algoritmo? Se via IA, qual serviço usar? (Gemini já está integrado no projeto?)

### Tarefa 5.5 — Botão Editar (Página 2)
- Permite ajustar o texto final antes da geração do PDF
- Edição ocorre apenas no parecer (não altera observação original)
- Após salvar edição → gerar nova versão do parecer

### Tarefa 5.6 — Botão Correção Automática (Página 2)
- Aplicar revisão ortográfica e gramatical
- Ajustar pontuação
- Melhorar fluidez textual
- **Não alterar sentido pedagógico do conteúdo**
- Após geração da versão final em PDF → bloquear edição

> 🔴 **[QUESTÃO 7]:** A "Correção Automática" será feita por qual serviço? API do Gemini? LanguageTool? Precisa definir.

### Tarefa 5.7 — Páginas 3 a 7: Eixos de Atividades com Fotos
Criar 5 páginas, uma para cada eixo:

| Página | Título |
|--------|--------|
| 3      | Eixo de Atividades Musicais |
| 4      | Eixo de Atividades Manuais |
| 5      | Eixo de Atividades de Contos |
| 6      | Eixo de Atividades de Movimento |
| 7      | Eixo Programa Comunicação Ativa |

Cada página contém:
- Espaço para 3 fotos (layout: 3 em linha)
- Botão: ➕ Adicionar Foto
- Cada foto com campo de legenda (texto curto, ~1 linha)

---

## MÓDULO 6 — PORTFÓLIO

**Ref:** http://154.38.189.82:8080/admin/classrooms

### Tarefa 6.1 — Acesso ao Portfólio
- Menu lateral → Professor vê lista de turmas → seleciona turma → vê portfólios produzidos com status (Pendente/Finalizado)
- Também considerar acesso pelo perfil do aluno

> 🔴 **[QUESTÃO 8]:** O portfólio é por TURMA (coletivo) ou por ALUNO (individual)? O documento menciona ambos. Na seção principal diz "Portfólio da Turma", mas depois menciona "portfólios referentes às crianças da turma". Esclarecer.

### Tarefa 6.2 — Listagem de Portfólios
Exibir em cards/lista:
- Nome da criança (ou turma)
- Turma
- Data de criação ou última atualização
- Status: Pendente / Finalizado
- Botão: **"+ Novo Portfólio"**

### Tarefa 6.3 — Criação de Novo Portfólio
Estrutura pré-definida com:
- Campos com textos fixos (não editáveis)
- Campos editáveis para a professora
- Pode ser salvo como Pendente ou Finalizado

### Tarefa 6.4 — Capa do Portfólio (1 página inteira)
- Foto da turma
- Título centralizado: **"Memórias especiais da Primeira Infância"**
- Subtítulo: "Portfólio da turma [campo editável para nome da turma, Mês/Ano]"
- Regras: Foto em destaque, aviso se não houver foto, capa = 1 página inteira

### Tarefa 6.5 — Página 1: "Sobre a Magia do Portfólio"
Texto fixo institucional (não editável). Texto fornecido:

> *"A magia do Portfólio — O portfólio é um poderoso instrumento de acompanhamento pedagógico..."* (texto completo fornecido no documento original)

### Tarefa 6.6 — Página 2: "Proposta da Pedagogia Florença"
- Texto fixo institucional (não editável)

> 🔴 **[QUESTÃO 9]:** Qual é o texto fixo da "Proposta da Pedagogia Florença"? Precisa ser fornecido.

### Tarefa 6.7 — Página 3: "Mensagem para a turma"
- Campo de texto livre para a professora (1 a 2 parágrafos)
- Botão: **"Corrigir texto"** → sistema apresenta versão revisada, professora aceita ou mantém original

> 🔴 **[QUESTÃO 10]:** A correção de texto será via qual serviço de IA? Mesmo serviço da Tarefa 5.6?

### Tarefa 6.8 — Página 4: "Os Eixos de Atividades"
- Texto fixo institucional (não editável)

> 🔴 **[QUESTÃO 11]:** Qual é o texto fixo de "Os Eixos de Atividades"? Precisa ser fornecido.

### Tarefa 6.9 — Páginas 5-14: Eixos de Atividades (Texto + Fotos)
Estrutura em pares de páginas (texto fixo + fotos):

| Páginas | Eixo | Pág. Texto | Pág. Fotos |
|---------|------|-----------|-----------|
| 5-6     | Movimento | Texto fixo (não editável) | 3 fotos + campo descrição |
| 7-8     | Manuais | Texto fixo (não editável) | 3 fotos + campo descrição |
| 9-10    | Contos | Texto fixo (não editável) | 3 fotos + campo descrição |
| 11-12   | Musicais | Texto fixo (não editável) | 3 fotos + campo descrição |
| 13-14   | Comunicação Ativa | Texto fixo (não editável) | 3 fotos + campo descrição |

**Layout das páginas de fotos:**
- 3 imagens: 2 acima + 1 abaixo (ou reorganização automática)
- Imagens são da TURMA (coletivas), extraídas do Banco de Imagens
- Evitar quebra de imagem entre páginas no PDF
- Campo de texto abaixo das fotos (1 parágrafo) para descrição

> 🔴 **[QUESTÃO 12]:** Os textos fixos das páginas 5, 7, 9, 11 e 13 precisam ser fornecidos para cada eixo.

> 🔴 **[QUESTÃO 13]:** Na Página 14 (Comunicação Ativa), o documento diz "Eixo Musicais" nas imagens — parece erro de cópia. Confirmar que devem ser imagens do Eixo Comunicação Ativa.

---

## MÓDULO 7 — FLUXO DE APROVAÇÃO (Parecer + Portfólio)

### Tarefa 7.1 — Botão Finalizar
Ao concluir Parecer ou Portfólio, professor clica: **"✔ Finalizar"**
- Status muda para "Finalizado"
- Documento fica visível no painel da coordenadora

### Tarefa 7.2 — Visualização pela Coordenadora
- Coordenadora vê documentos com status "Finalizado"
- Pode visualizar completo, mas **não pode editar** diretamente

### Tarefa 7.3 — Solicitar Ajuste (Coordenadora)
Botão: **"Enviar orientação / Solicitar ajuste"**
- Abre campo de mensagem para observações/sugestões
- Após envio:
  - Professor recebe notificação na plataforma
  - Mensagem vinculada ao documento
  - Status retorna automaticamente para **Pendente**

### Tarefa 7.4 — Correção pelo Professor
- Professor acessa documento indicado
- Realiza ajustes
- Clica novamente em **"✔ Finalizar"**
- Documento volta para revisão da coordenadora

### Tarefa 7.5 — Sistema de Notificações
Implementar sistema de notificações internas na plataforma para:
- Coordenadora → Professor (solicitação de ajuste)
- Vinculação de mensagem ao documento específico

> 🔴 **[QUESTÃO 14]:** As notificações são apenas internas (dentro da plataforma) ou também por e-mail? O professor vê um ícone de sino/badge no menu?

---

## MÓDULO 8 — PLANEJAMENTO

**Ref:** http://154.38.189.82:8080/admin/planning

### Tarefa 8.1 — Listagem de Planejamentos
Exibir lista/cards com todos os planejamentos produzidos:
- Mês de referência (em destaque)
- Turma
- Data de criação ou última atualização
- Status: Pendente / Finalizado
- Botão: **"+ Novo Planejamento"**

### Tarefa 8.2 — Correção da Estrutura Existente
- **Problema atual:** Campo identificado como "Planejamento Quinzenal" mas com estrutura de um único dia
- **Solução:** Reorganizar para modelo de **planejamento mensal**

### Tarefa 8.3 — Nova Estrutura: Planejamento Mensal
Hierarquia: **Mês → Semanas → Dias**

**Nível 1: Mês**
- Botão: ➕ Adicionar Planejamento
- Seleção do mês (Janeiro a Dezembro)

**Nível 2: Semanas**
- Botão: ➕ Adicionar Semana
- Semana 1 a 5 (opcional)

**Nível 3: Dias**
- Botão: ➕ Adicionar Dia
- Opções: Segunda a Sexta-feira

**Nível 4: Conteúdo do Dia**
- Ao clicar no dia → abrir a estrutura de planejamento pedagógico já existente

### Tarefa 8.4 — Rotina do Dia
Adicionar ao final do planejamento diário a seção **"Rotina do Dia"**:

**Campo de horário:**
- Seleção via botões pré-definidos:
  - 07:30–08:00, 08:00–08:30, 08:30–09:00, 09:00–09:30, 09:30–10:00, etc.

**Campo de descrição da atividade:**
- Texto curto

Botão: **➕ Adicionar atividade** (para incluir novas linhas)

Exemplo:
| Horário | Atividade |
|---------|-----------|
| 07:30–08:00 | Acolhida e recepção das crianças |
| 08:00–08:30 | Abertura do dia / Palavra do dia |

> 🔴 **[QUESTÃO 15]:** Os horários pré-definidos vão de 07:30 até que hora? Os intervalos são sempre de 30 minutos ou podem variar (ex: 09:00–09:20 para lanche)? O professor pode criar horários personalizados?

### Tarefa 8.5 — Visualizar Rotina da Semana
Botão para visualizar todas as rotinas da semana em uma única tela (tabela):

| Segunda | Terça | Quarta | Quinta | Sexta |
|---------|-------|--------|--------|-------|
| Acolhida | Acolhida | Acolhida | Acolhida | Acolhida |
| Eixo Contos | Eixo Movimento | Eixo Musical | Eixo Manuais | Centros Aprendizagem |

### Tarefa 8.6 — Botão no Menu Lateral
O botão "Planejamento" deve permanecer no menu lateral da página inicial.

---

## MÓDULO 9 — MATERIAL DE APOIO

### Tarefa 9.1 — Seção Material de Apoio no Menu Lateral
Criar espaço "Material de Apoio" acessível pelo menu lateral (para professor E coordenador).

### Tarefa 9.2 — Estrutura de Pastas
Organizar em pastas por categorias:

```
Material de Apoio/
├── Eixos de Atividades/
│   ├── Manuais/
│   ├── Musicais/
│   ├── Contos/
│   └── Movimento/
├── Centros de Aprendizagem/
└── Famílias de Brinquedos/
```

> 🔴 **[QUESTÃO 16]:** Quem pode fazer upload de materiais? Apenas o coordenador ou também o professor? Quais formatos de arquivo são aceitos (PDF, vídeo, imagens, links)?

> 🔴 **[QUESTÃO 17]:** O Eixo "Comunicação Ativa" não tem subpasta no Material de Apoio. Isso é intencional ou deveria ter?

---

## MÓDULO 10 — BANCO DE IMAGENS

### Tarefa 10.1 — Criar Área "Banco de Imagens"
Área para armazenamento de fotos para uso em Pareceres Descritivos e Portfólio.

### Tarefa 10.2 — Estrutura de Pastas

```
Banco de Imagens/
├── Turma Maternal A/
│   ├── Turma (Registros Coletivos)/
│   └── Crianças (Registros Individuais)/
│       ├── Nome da criança 1/
│       ├── Nome da criança 2/
│       └── Nome da criança 3/
├── Turma Maternal B/
│   └── ...
└── Turma Jardim I/
    └── ...
```

- Pastas de turma criadas automaticamente a partir das turmas cadastradas
- Subpastas de crianças criadas automaticamente a partir dos alunos cadastrados

### Tarefa 10.3 — Funcionalidades do Banco de Imagens
- **Upload:** Botão ➕ Adicionar Imagem (múltiplas imagens)
- **Organização:** Mover imagens entre pastas; Excluir imagens
- **Visualização:** Formato de miniaturas (thumbnails)

> 🔴 **[QUESTÃO 18]:** Existe limite de tamanho por imagem ou de armazenamento total? Quais formatos aceitos (JPG, PNG, HEIC)?

---

## MÓDULO 11 — DASHBOARD DO COORDENADOR

**Ref:** http://154.38.189.82:8080/admin/dashboard

### Tarefa 11.1 — Menu Lateral do Coordenador
Itens do menu:
1. Turmas
2. Pareceres Descritivos
3. Portfólios
4. Planejamentos
5. Materiais de Apoio
6. Relatório

> 🔴 **[QUESTÃO 19]:** O coordenador não tem "Banco de Imagens" no menu. Ele deve ter acesso ao banco ou isso é exclusivo do professor?

### Tarefa 11.2 — Cards do Dashboard do Coordenador
- **Nº total de turmas** (ex: 8)
- **Nº total de crianças** (ex: 120)
- **Nº total de professores** (ex: 8)
- **Pareceres Descritivos:** Pendentes (ex: 35) / Finalizados (ex: 85)
- **Portfólios das Turmas:** Pendentes (ex: 2) / Finalizados (ex: 6)

### Tarefa 11.3 — Relatório de Cursos (Formação)
Tabela com:
- Nome do curso
- Nº de professores inscritos
- Porcentagem média de conclusão

Exemplo:
| Curso | Inscritos | Progresso médio |
|-------|-----------|-----------------|
| Comunicação Ativa | 8 | 75% |
| Pedagogia Florença I | 8 | 60% |

### Tarefa 11.4 — Exportação de Relatórios
Botão: **"⬇ Exportar relatório"**
- Formatos: PDF e Excel
- Incluir todos os indicadores do painel

---

## MÓDULO 12 — TURMAS (VISÃO COORDENADOR)

**Ref:** http://154.38.189.82:8080/admin/classrooms

### Tarefa 12.1 — Listagem de Turmas (Coordenador)
Coordenador visualiza TODAS as turmas da escola:
- Nome da turma
- Professora responsável
- Nº total de crianças
- Cada turma clicável

### Tarefa 12.2 — Área Interna da Turma (Coordenador)
**Ref:** http://154.38.189.82:8080/admin/classrooms/1/edit

Ao clicar na turma, 3 módulos:
1. Pareceres Descritivos das Crianças
2. Portfólio da Turma
3. Planejamentos

### Tarefa 12.3 — Pareceres Descritivos (Coordenador)
Lista de crianças + parecer:
- Nome da criança
- Status (Pendente / Finalizado)
- Ações:
  - Se Pendente: Visualizar
  - Se Finalizado: Visualizar / Enviar / Baixar

### Tarefa 12.4 — Portfólios (Coordenador)
- Nome da turma
- Status: Pendente / Finalizado
- Ações: Visualizar / Baixar / Enviar para famílias (futuro)

### Tarefa 12.5 — Restrição de Envio
- Envio para famílias = **exclusivo do Coordenador**
- Professores NÃO têm permissão de envio
- Botões disponíveis (somente se Finalizado):
  - Enviar para famílias (futuro — quando houver acesso para pais)
  - Baixar arquivo (PDF)

### Tarefa 12.6 — Solicitar Correção (Coordenador)
Botão: **"Solicitar correção"**
- Campo de texto para observações/orientações
- Após envio: Professor notificado + Status → Pendente

### Tarefa 12.7 — Controle de Status dos Documentos
Estados possíveis:
1. **Pendente** — em elaboração ou aguardando revisão
2. **Finalizado** — concluído e disponível
3. **Enviado para as famílias** (futuro)

---

## MÓDULO 13 — PLANEJAMENTO (VISÃO COORDENADOR)

### Tarefa 13.1 — Listagem de Planejamentos (Coordenador)
Visualizar planejamentos organizados cronologicamente:
- Data/identificação do planejamento
- Professora responsável
- Status: Pendente / Finalizado

### Tarefa 13.2 — Visualização do Planejamento
Ao clicar, coordenador vê todo o conteúdo:
- Objetivos, descrição das atividades, eixos, materiais, rotina do dia
- **Coordenador NÃO edita**, apenas visualiza

### Tarefa 13.3 — Solicitar Ajustes (Planejamento)
Botão: **"Solicitar ajuste / Enviar orientação"**
- Campo de mensagem
- Após envio: Professor notificado + Status → Pendente
- Fluxo idêntico ao de Parecer/Portfólio (Tarefa 7.3)

---

## MÓDULO 14 — CURSOS / FORMAÇÃO (REFERÊNCIAS DE DESIGN)

> 🔴 **[QUESTÃO 20]:** O documento apresenta referências visuais (imagens de dashboards, filtros, critérios de avaliação, questionários, cadastro em massa) mas não detalha os requisitos funcionais dos Cursos/Formação. Isso será detalhado em um documento separado? Ou as imagens de referência são suficientes para implementação?

### Tarefa 14.1 — Dashboard do Gestor (Cursos)
Implementar tela de gestão de cursos conforme modelos visuais de referência.

### Tarefa 14.2 — Filtros de Cursos
Implementar funcionalidade de filtro conforme modelo visual.

### Tarefa 14.3 — Critérios de Avaliação dos Cursos
Implementar tela de critérios de avaliação conforme modelo visual.

### Tarefa 14.4 — Formatos de Conteúdo Diversos
Permitir diferentes formatos de conteúdo para o aluno, com marcação de pré-requisito e opcional.

### Tarefa 14.5 — Questionários
Implementar opções de questionários conforme modelos visuais.

### Tarefa 14.6 — Cadastro em Massa de Participantes
Implementar funcionalidade de cadastro em massa conforme modelo visual.

> 🔴 **[QUESTÃO 21]:** Sem acesso às imagens de referência do Google Docs, não é possível replicar os modelos visuais. As imagens precisam ser exportadas e disponibilizadas separadamente.

---

## RESUMO DE QUESTÕES PENDENTES (🔴)

| # | Questão | Módulo |
|---|---------|--------|
| 1 | Dashboard Professor: como exibir % de cursos (média ou individual)? | 1 |
| 2 | Dashboard Professor: Portfólio mostra contador ou status único? | 1 |
| 3 | Quais são os status possíveis de uma Turma? | 2 |
| 4 | Observações: periodicidade é Semestral ou Trimestral? | 4 |
| 5 | Qual é o texto fixo da Pág. 1 do Parecer ("Sobre o Parecer Descritivo")? | 5 |
| 6 | Unificação de texto: manual pelo professor ou via IA? | 5 |
| 7 | Correção Automática: qual serviço/API usar? | 5 |
| 8 | Portfólio é por TURMA ou por ALUNO? | 6 |
| 9 | Qual é o texto fixo de "Proposta da Pedagogia Florença"? | 6 |
| 10 | Correção de texto do Portfólio: qual serviço de IA? | 6 |
| 11 | Qual é o texto fixo de "Os Eixos de Atividades"? | 6 |
| 12 | Textos fixos das páginas de cada eixo no Portfólio? | 6 |
| 13 | Pág. 14: "Eixo Musicais" parece erro — confirmar Comunicação Ativa | 6 |
| 14 | Notificações: apenas internas ou também por e-mail? | 7 |
| 15 | Horários da rotina: faixa, intervalos e personalização? | 8 |
| 16 | Material de Apoio: quem faz upload e quais formatos? | 9 |
| 17 | Material de Apoio: Eixo Comunicação Ativa sem subpasta — intencional? | 9 |
| 18 | Banco de Imagens: limite de tamanho/armazenamento? | 10 |
| 19 | Coordenador tem acesso ao Banco de Imagens? | 11 |
| 20 | Módulo Cursos/Formação: requisitos funcionais detalhados? | 14 |
| 21 | Imagens de referência do documento precisam ser exportadas | 14 |
