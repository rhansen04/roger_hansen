# Tarefas — Revisão V4 da Plataforma Hansen Educacional

> Origem: `Plataforma_Correções_V4.docx.pdf` · Data: 2026-03-19
> Status geral: 🔲 Não iniciado | 🔄 Em andamento | ✅ Concluído

---

## Grupo 1 — Menu / Navegação

### T4-01 · Reordenar menu do Professor/Coordenador
**Prioridade:** Alta
**Status:** 🔲

Nova ordem solicitada (seção ENSINO):
1. Turmas
2. Observações
3. Planejamentos
4. Material de Apoio
5. Pareceres
6. Portfólios
7. Banco de Imagens

**Arquivos afetados:** view de layout/sidebar (menu lateral)

---

### T4-02 · Adicionar botão "Rotina Semanal" abaixo do item Planejamento no menu
**Prioridade:** Média
**Status:** 🔲

Um sub-item ou botão de acesso rápido "Rotina Semanal" deve aparecer no menu logo abaixo do link de Planejamentos.

---

## Grupo 2 — Turmas

### T4-03 · Coluna numerada na lista de alunos da Turma
**Prioridade:** Baixa
**Status:** 🔲

Na tela de detalhe da turma (`/admin/classrooms/{id}`), inserir uma coluna numerada (1, 2, 3…) no lado esquerdo da tabela de alunos para identificar a quantidade/posição de cada aluno.

---

## Grupo 3 — Planejamentos

### T4-04 · Remover coluna "Template" da listagem de Planejamentos
**Prioridade:** Alta
**Status:** 🔲

Na tela `/admin/planning` (listagem), remover completamente a coluna "Template" da tabela.

---

### T4-05 · Adicionar coluna "Registros do Período" na listagem de Planejamentos
**Prioridade:** Alta
**Status:** 🔲

Substituir/adicionar uma coluna interativa chamada **"Registros do Período"** na listagem de planejamentos.

**Comportamento:**
- **Pendente** (professor não acessou ou não finalizou): badge/botão visual amarelo/laranja indicando "Pendente"; ao clicar abre o formulário de preenchimento.
- **Concluído** (professor finalizou): badge/botão verde com ícone de confirmação indicando "Concluído"; ao clicar permite visualizar e/ou editar o registro.

---

### T4-06 · Criar formulário "Registro — Final da Semana"
**Prioridade:** Alta
**Status:** 🔲

Novo formulário vinculado a cada planejamento. Campos obrigatórios:

| Campo | Tipo |
|---|---|
| Síntese do Desenvolvimento das Atividades | Textarea (como as propostas aconteceram ao longo da semana) |
| Execução do Planejamento | Radio: Sim / Parcialmente / Não + textarea de justificativa (condicional) |
| Engajamento das Crianças | Radio: Alto / Médio / Baixo + textarea de comentário breve |
| Ajustes Realizados | Checkboxes: Tempo / Espaço / Materiais / Mediação docente / Interesse das crianças + textarea de descrição |
| O que as crianças trouxeram de novo para a proposta? | Textarea (interesses espontâneos, falas marcantes, descobertas) |
| Avanços ou Desafios Observados | Textarea (interação, autonomia, concentração, cooperação) |
| Necessidade de Apoio | Checkboxes: Pedagógico / Organizacional / Formativo / Estrutural + textarea de descrição |

Requer: novo Model `PlanningRecord`, Controller action, migration de tabela, rotas, view do formulário.

---

## Grupo 4 — Dias do Planejamento (Cards)

### T4-07 · Reduzir tamanho dos cards de dias (layout compacto)
**Prioridade:** Média
**Status:** 🔲

Na tela `/admin/planning/{id}/days`, reduzir visualmente os cards de dias para um layout mais compacto, organizado e leve, sem comprometer legibilidade.

---

### T4-08 · Diferenciação visual de estados nos cards de dias por cor
**Prioridade:** Média
**Status:** 🔲

- **Dia vazio** (sem planejamento/registro): cor de destaque (ex: amarelo/laranja) + indicativo "Pendente"
- **Dia preenchido**: cor de confirmação (ex: verde) + texto "Concluído" ou "Preenchido"

---

## Grupo 5 — Portfólio

### T4-09 · Remover upload local de imagens no Portfólio
**Prioridade:** Alta
**Status:** 🔲

Na tela de criação/edição de portfólio, remover o campo de upload de arquivo (input file) para imagens de capa e demais seções. Imagens devem ser selecionadas **exclusivamente** a partir do banco de imagens interno da plataforma.

---

### T4-10 · Integrar seletor do Banco de Imagens no Portfólio
**Prioridade:** Alta
**Status:** 🔲

Substituir o campo de upload por um seletor/modal que acessa o banco de imagens já cadastrado pelo professor, filtrando por turma/aluno conforme contexto.

---

## Grupo 6 — Observações Pedagógicas

### T4-11 · Numerar todas as perguntas dos eixos pedagógicos
**Prioridade:** Média
**Status:** 🔲

No formulário de observações (`/admin/observations/create`), todas as perguntas guia de cada eixo devem ser exibidas com numeração sequencial (1., 2., 3.…).

---

### T4-12 · Campo de resposta individual e obrigatório por pergunta
**Prioridade:** Alta
**Status:** ✅

Cada pergunta dos eixos deve ter seu próprio campo de resposta (textarea individual), de preenchimento obrigatório, em vez de um campo único por eixo. Garante que o professor responda de forma organizada e independente a cada questão.

---

## Grupo 7 — Perfil Administrador (Reestruturação de Menu)

### T4-13 · Reestruturar menu do Administrador (3 módulos globais)
**Prioridade:** Alta
**Status:** ✅

O menu principal do Administrador deve conter apenas **3 módulos de nível global**:
1. **Escolas** — CRUD completo + acesso ao ambiente interno da escola
2. **Usuários** — gestão global (todas as escolas): criar, editar, vincular escola, definir papel, ativar/inativar
3. **Cursos** — criar/editar cursos, módulos/aulas, vincular a escolas/usuários, testes, material complementar

Módulo adicional: **Material de Apoio** (global).

---

### T4-14 · Navegação contextual por Escola (Admin)
**Prioridade:** Alta
**Status:** ✅

Ao acessar "Escolas" e selecionar uma escola específica, o sistema deve abrir um **ambiente contextual da escola**:

- Exibir claramente o nome da escola ativa (breadcrumb ou header)
- Filtrar automaticamente todos os dados por aquela escola
- Menu secundário interno com: **Turmas · Alunos · Pareceres · Planejamentos · Portfólios · Observações**
- Botão/link para voltar à lista global de escolas

---

## Grupo 8 — Perfil Coordenador (Novo Papel)

### T4-15 · Implementar papel "Coordenador" no sistema
**Prioridade:** Alta
**Status:** ✅

Criar novo tipo de usuário: **Coordenador**.

**Função:** acompanhar, orientar e intervir nas produções dos professores da escola vinculada.

**Permissões:**
- Visualizar todos os conteúdos (observações, pareceres, planejamentos, portfólios) dos professores da escola
- Inserir observações/orientações pedagógicas
- Enviar notificações e sinalizações para professores

**Menu do Coordenador:**
1. Turmas — visualizar turmas da escola e conteúdos vinculados
2. Observações — registrar orientações pedagógicas, visualizar, vincular a professor/turma/data
3. Pareceres — acompanhar pareceres dos professores, inserir comentários/feedbacks
4. Portfólios — visualizar portfólios criados, inserir observações/orientações
5. Acompanhamento de Cursos — ver progresso dos professores, status (em andamento/concluído)

**Gestão de usuários restrita:**
- O coordenador pode adicionar novos professores, mas **somente dentro do contexto da sua escola**

---

### T4-16 · Ferramentas de interação do Coordenador com Professores
**Prioridade:** Média
**Status:** ✅

O coordenador deve conseguir:
- Enviar notificações internas (via sistema de notificações já existente)
- Sinalizar pendências diretamente nos registros dos professores
- Inserir feedbacks/comentários diretamente nos conteúdos (pareceres, portfólios, planejamentos, observações)

---

## Resumo Executivo das Tarefas

| ID | Descrição | Grupo | Prioridade | Status |
|---|---|---|---|---|
| T4-01 | Reordenar menu Professor/Coordenador | Menu | Alta | ✅ |
| T4-02 | Botão "Rotina Semanal" no menu | Menu | Média | ✅ |
| T4-03 | Coluna numerada na lista de alunos da turma | Turmas | Baixa | ✅ |
| T4-04 | Remover coluna "Template" dos Planejamentos | Planejamentos | Alta | ✅ |
| T4-05 | Coluna "Registros do Período" (interativa) | Planejamentos | Alta | ✅ |
| T4-06 | Formulário "Registro — Final da Semana" | Planejamentos | Alta | ✅ |
| T4-07 | Cards de dias compactos | Dias Planejamento | Média | ✅ |
| T4-08 | Diferenciação visual por cor nos cards de dias | Dias Planejamento | Média | ✅ |
| T4-09 | Remover upload local no Portfólio | Portfólio | Alta | ✅ |
| T4-10 | Seletor do Banco de Imagens no Portfólio | Portfólio | Alta | ✅ |
| T4-11 | Numerar perguntas dos eixos (Observações) | Observações | Média | ✅ |
| T4-12 | Campo individual por pergunta (Observações) | Observações | Alta | ✅ |
| T4-13 | Reestruturar menu Admin (3 módulos globais) | Admin | Alta | ✅ |
| T4-14 | Navegação contextual por Escola (Admin) | Admin | Alta | ✅ |
| T4-15 | Novo papel: Coordenador | Coordenador | Alta | ✅ |
| T4-16 | Ferramentas de interação Coordenador↔Professor | Coordenador | Média | ✅ |

**Total: 16 tarefas** · Alta: 10 · Média: 5 · Baixa: 1
