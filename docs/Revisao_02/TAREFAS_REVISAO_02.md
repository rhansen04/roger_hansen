# Revisão 02 — Tarefas de Correção e Melhorias

**Data:** 16/03/2026
**Origem:** Feedback do usuário (Roger Hansen — Coordenador)
**Documento:** `Plataforma_Correções_V2.docx.pdf`

---

## Resumo Executivo

| # | Tarefa | Módulo | Status |
|---|--------|--------|--------|
| R2-01 | Acesso a alunos e perfis a partir da tela de Turmas | Turmas | ✅ Concluído |
| R2-02 | Botão "+ Adicionar Observação" visível para Coordenador | Observações | ✅ Concluído |
| R2-03 | Botões dos 6 eixos na listagem de Observações | Observações | ✅ Concluído |
| R2-04 | Parecer Descritivo — validar fluxo com observações existentes | Pareceres | ✅ Concluído |
| R2-05 | Planejamento — visualização calendário/lista de 15 dias (quinzenal) | Planejamento | ✅ Concluído |
| R2-06 | Planejamento — ajustes no card de planejamento diário | Planejamento | ✅ Concluído |
| R2-07 | Planejamento — botão "Finalizar Planejamento" + fluxo coordenador | Planejamento | ✅ Concluído |
| R2-08 | Planejamento — página de Registro pós-execução (período completo) | Planejamento | ✅ Concluído |
| R2-09 | Simulador de Perfil para Admin — alternar entre visões Professor/Coordenador/Admin | Acesso | ✅ Concluído |

---

## Detalhamento das Tarefas

### R2-01 — Acesso a alunos e perfis a partir da tela de Turmas

**Problema reportado:** Na tela "Editar Turma" (`/admin/classrooms/{id}/edit`), o usuário não encontrou o botão "+ Adicionar novos alunos" nem onde visualizar os perfis dos alunos.

**Causa raiz:** O botão "+ Adicionar Aluno" e a lista de alunos existem na tela de **detalhes** da turma (`/admin/classrooms/{id}`), não na tela de edição. O fluxo não é intuitivo — o usuário vai em "Editar" esperando gerenciar alunos.

**Solução:**
- Adicionar link/botão na tela de edição (`form.php`) que direcione para a página de detalhes da turma (onde estão os alunos)
- Ou: adicionar seção de gestão de alunos diretamente na página de edição
- Garantir que na listagem de turmas, clicar no nome da turma leve à página de detalhes (com alunos)

**Arquivos:** `views/admin/classrooms/form.php`, `views/admin/classrooms/index.php`

---

### R2-02 — Botão "+ Adicionar Observação" visível para Coordenador

**Problema reportado:** O botão "+ Adicionar Observação" não aparece na listagem de observações.

**Causa raiz:** O botão é ocultado para o papel `coordenador` (condição `if role !== 'coordenador'`). Roger Hansen está logado como Coordenador e não consegue criar observações.

**Solução:**
- Tornar o botão visível para coordenadores (ou ao menos para o perfil admin/coordenador)
- Revisar regra de negócio: se coordenadores devem poder criar observações, remover a restrição

**Arquivos:** `views/admin/observations/index.php` (linha ~39)

---

### R2-03 — Botões dos 6 eixos na listagem de Observações

**Problema reportado:** Os botões dos eixos (Movimento, Manuais, Música, Contos, Programa Comunicação Ativa) não estão mais aparecendo na interface. Deveriam estar visíveis e direcionar o professor para as perguntas de cada eixo.

**Causa raiz:** Os eixos estão implementados como abas **dentro** do formulário de criação/edição, mas não na página de listagem. O usuário espera atalhos visíveis na listagem.

**Solução:**
- Adicionar botões/cards dos 6 eixos na tela de listagem de observações como atalhos visuais
- Ao clicar em um eixo, direcionar para criação/edição focando naquele eixo específico

**Arquivos:** `views/admin/observations/index.php`, `views/admin/observations/create.php`

---

### R2-04 — Parecer Descritivo — validar fluxo com observações existentes

**Problema reportado:** Não consegue gerar parecer descritivo porque não há material das observações.

**Causa raiz:** Consequência da R2-02 — sem poder criar observações, não há dados para os pareceres. Pode também haver problema se observações existem mas não estão finalizadas.

**Solução:**
- Resolver R2-02 primeiro (permitir criação de observações)
- Validar que o fluxo Observação → Parecer funciona end-to-end
- Melhorar mensagem de erro quando não há observações (indicar o caminho para criar)

**Arquivos:** `app/Controllers/Admin/DescriptiveReportController.php`, views relacionadas

---

### R2-05 — Planejamento — visualização calendário/lista de 15 dias

**Problema reportado:** Ao abrir um planejamento para edição, a estrutura atual não reflete o fluxo quinzenal. O sistema deve mostrar 15 dias (úteis, seg-sex) em formato calendário ou lista, e cada dia funciona como entrada para um card de planejamento diário.

**Fluxo esperado:**
1. Professor abre planejamento quinzenal existente
2. Vê lista/calendário de ~15 dias úteis do período
3. Clica em um dia → abre o card de planejamento daquele dia
4. Preenche o planejamento do dia

**Solução:**
- Reestruturar a tela de edição do planejamento para exibir os dias do período como calendário/lista
- Cada dia deve ser clicável e abrir o formulário de planejamento diário
- Manter compatibilidade com os templates existentes (PFI/PFII)

**Arquivos:** `views/admin/planning/form.php`, `app/Controllers/Admin/PlanningController.php`, possível nova tabela `planning_daily_entries`

---

### R2-06 — Planejamento — ajustes no card de planejamento diário

**Alterações solicitadas no card diário:**

| Item | Ação |
|------|------|
| Seção "Identificação" | **Remover** — redundante, pois a turma já é selecionada ao criar o planejamento |
| Campo "Eixo de Vivências" | **Renomear** para "Eixo de Atividades" |
| Seleção de eixos | **Alterar formato** — de títulos fixos para barra/menu de seleção (dropdown ou tabs clicáveis) |
| Campo "Palavra do dia" | **Manter** como está |

**Arquivos:** `views/admin/planning/form.php`, `scripts/seed_planning_templates.php`, banco de dados (templates/sections)

---

### R2-07 — Planejamento — botão "Finalizar Planejamento" + fluxo coordenador

**Problema reportado:** Após completar os 15 dias de planejamento, deve existir um botão "Finalizar Planejamento" que torna o conteúdo disponível para visualização do coordenador.

**Solução:**
- Adicionar botão "Finalizar Planejamento" visível quando todos os dias estiverem preenchidos (ou como opção manual)
- Ao finalizar, mudar status para `submitted` e notificar coordenador
- Coordenador pode visualizar o planejamento completo

**Arquivos:** `views/admin/planning/form.php`, `app/Controllers/Admin/PlanningController.php`

---

### R2-08 — Planejamento — página de Registro pós-execução

**Problema reportado:** Após finalização do planejamento, deve aparecer campo de "Registro" para o professor preencher após a execução. Esse registro se refere ao período completo de 15 dias (não por dia individual).

**Detalhes:**
- Campos: Síntese do Desenvolvimento, Execução do Planejamento (Sim/Parcialmente/Não), justificativa
- Não precisa ficar dentro de cada dia planejado
- Deve cobrir o período quinzenal inteiro

**Solução:**
- A seção `is_registration = 1` do template já existe no banco
- Garantir que ela apareça **separadamente** após a submissão do planejamento (não misturada com os dias)
- Apenas habilitada após status `submitted`

**Arquivos:** `views/admin/planning/form.php`, `app/Controllers/Admin/PlanningController.php`

---

### R2-09 — Simulador de Perfil para Admin (Alternar entre visões)

**Problema reportado (Larissa):** "Estou vendo apenas a visão do professor, e muitas funcionalidades estão atreladas entre si. Assim que eu conseguir acessar também a visão do coordenador e a nossa como administradores, acredito que ficará mais fácil identificar quais ajustes ainda precisam ser feitos."

**Causa raiz:** O sistema não suporta alternar entre papéis. Cada conta tem um role fixo. Para testar as 3 visões, seria necessário 3 contas separadas — pouco prático para quem precisa "provar" cada perfil.

**Solução — "Simular Perfil":**
- Adicionar no header/topbar do admin (visível APENAS para role `admin`) um seletor "Simular visão como:"
- Opções: Admin (padrão) | Professor | Coordenador
- Ao selecionar, a sessão recebe uma variável `simulated_role` que sobrescreve o `user_role` para fins de renderização de menus e dashboards
- Um banner colorido fica visível indicando "Você está simulando a visão de PROFESSOR" com botão "Voltar para Admin"
- **Segurança:** Apenas o `user_role` real (admin) é verificado para permissões de escrita/exclusão. O `simulated_role` afeta apenas a **visualização** (menus, dashboard, filtros)

**Arquivos:** `views/layouts/admin.php` (sidebar + header), `app/Controllers/Admin/DashboardController.php`, novo endpoint `/admin/simulate-role`

---

## Ordem de Execução Sugerida

1. **R2-09** (prioridade alta — simulador de perfil para Larissa testar as visões)
2. **R2-01** (rápido — link na tela de edição de turmas)
3. **R2-02** (rápido — remover restrição de role)
4. **R2-03** (médio — adicionar botões de eixos na listagem)
5. **R2-04** (validação — depende de R2-02)
6. **R2-06** (médio — ajustes nos campos do planejamento)
7. **R2-05** (complexo — reestruturar visualização quinzenal)
8. **R2-07** (médio — botão finalizar + fluxo)
9. **R2-08** (médio — separar registro pós-execução)

---

*Documento gerado automaticamente a partir do feedback da Revisão 02.*
*Atualizado conforme execução das tarefas.*
