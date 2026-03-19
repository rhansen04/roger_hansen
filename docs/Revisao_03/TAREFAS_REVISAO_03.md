# Revisão 03 — Tarefas de Correção da Plataforma

**Data:** 2026-03-18
**Origem:** Feedback do usuário (Roger Hansen) — `Plataforma_Correções_V3.docx.pdf`
**Status:** ✅ Todas concluídas

---

## Resumo

| # | Módulo | Tarefa | Prioridade | Status |
|---|--------|--------|------------|--------|
| R3-01 | Observações | Corrigir erros ortográficos nas perguntas orientadoras de todos os eixos | Alta | ✅ Concluído |
| R3-02 | Observações | Revisar fluxo de criação/salvamento — UX confusa, dados não salvam | Crítica | ✅ Concluído |
| R3-03 | Parecer Descritivo | Não é possível criar parecer (dependência de R3-02) | Crítica | ✅ Resolvido via R3-02 |
| R3-04 | Planejamento | Filtrar objetivos por eixo selecionado (mostra todos os eixos, deveria filtrar) | Alta | ✅ Concluído |
| R3-05 | Planejamento | Rotina Semanal — separar campo horário e descrição da atividade | Alta | ✅ Concluído |
| R3-06 | Planejamento | Rotina Semanal — salvar não funciona (erro ao salvar rotina) | Crítica | ✅ Concluído |
| R3-07 | Planejamento | Incluir página de Registro (pós-vivência) ao final do planejamento | Alta | ✅ Concluído |
| R3-08 | Banco de Imagens | Adicionar upload de arquivos e drag-and-drop na pasta do aluno | Alta | ✅ Concluído |
| R3-09 | Portfólio | Corrigir erro fatal ao exportar PDF | Crítica | ✅ Concluído |
| R3-10 | Portfólio | Adicionar botão "Visualizar" antes de solicitar revisão | Média | ✅ Concluído |
| R3-11 | Material de Apoio | Adicionar upload de arquivos e drag-and-drop | Alta | ✅ Concluído |

---

## Detalhamento das Tarefas

### R3-01 — Corrigir erros ortográficos nas perguntas orientadoras
**Módulo:** Observações Pedagógicas
**Arquivos:** `views/admin/observations/create.php`, `views/admin/observations/edit.php`
**Descrição:** As perguntas orientadoras dos 6 eixos contêm erros de acentuação e ortografia (ex: "mudancas" → "mudanças", "voce" → "você", "crianca" → "criança", etc.). Corrigir em ambas as views (create e edit).

---

### R3-02 — Revisar fluxo de criação/salvamento das observações (UI/UX)
**Módulo:** Observações Pedagógicas
**Arquivos:** `app/Controllers/Admin/ObservationController.php`, `app/Models/Observation.php`, `views/admin/observations/create.php`, `views/admin/observations/edit.php`
**Descrição:** O usuário preenche as observações em cada eixo na tela de criação, mas as informações **não são salvas**. O problema é que o fluxo atual exige:
1. Primeiro salvar o registro mestre (student_id + semester + year) — método `store()`
2. Só então editar as observações na tela de edit com auto-save

**Problema de UX:** O formulário de criação exibe todos os campos dos eixos, o usuário preenche tudo, mas ao submeter, o controller `store()` só salva os metadados — as observações dos eixos digitados na criação são descartadas.

**Solução proposta:** Permitir salvar o conteúdo dos eixos já na criação, passando os campos preenchidos para `createWithAxes()`. Rever o fluxo completo de UI para que seja intuitivo.

---

### R3-03 — Parecer Descritivo não pode ser criado
**Módulo:** Parecer Descritivo
**Arquivos:** `app/Controllers/Admin/DescriptiveReportController.php`
**Descrição:** Como as observações não estão sendo salvas (R3-02), o parecer não consegue ser gerado. Mensagem exibida: "Nenhuma observação encontrada para este aluno". **Nota do usuário:** a mensagem ficou muito boa — manter esse feedback visual. A resolução de R3-02 deve resolver este item automaticamente.

---

### R3-04 — Filtrar objetivos de aprendizagem por eixo no planejamento diário
**Módulo:** Planejamento Pedagógico
**Arquivos:** `views/admin/planning/day_card.php`
**Descrição:** Na tela de edição do dia (`/admin/planning/{id}/day/{date}`), a seção "Objetivos de Aprendizagem" exibe os objetivos de **todos os eixos** simultaneamente. O comportamento esperado é que, ao clicar/selecionar um eixo, apenas os objetivos daquele eixo sejam exibidos. Os campos de preenchimento podem permanecer abaixo.

---

### R3-05 — Rotina Semanal — separar horário e descrição
**Módulo:** Planejamento Pedagógico
**Arquivos:** `views/admin/planning/routine.php`, `app/Controllers/Admin/PlanningController.php`, `app/Models/PlanningDailyRoutine.php`
**Descrição:** Atualmente a rotina semanal possui um campo de horário e um campo de descrição lado a lado, mas a UI gera confusão. O campo de "descrição da atividade" precisa estar claramente separado do campo de horário, com labels explícitos.

---

### R3-06 — Rotina Semanal — erro ao salvar
**Módulo:** Planejamento Pedagógico
**Arquivos:** `app/Controllers/Admin/PlanningController.php` (método `saveRoutine()`), `app/Models/PlanningDailyRoutine.php`
**Descrição:** Ao preencher a rotina semanal e clicar em "Salvar Rotina", exibe erro: "Erro ao salvar rotina." As informações não são registradas. Investigar o método `saveRoutines()` no model e o fluxo de salvamento.

---

### R3-07 — Incluir página de Registro pós-vivência
**Módulo:** Planejamento Pedagógico
**Arquivos:** `views/admin/planning/days.php`, `views/admin/planning/registration.php`, `app/Controllers/Admin/PlanningController.php`
**Descrição:** A visualização do planejamento semestral (dias) está boa, mas falta a opção de **registro ao final do processo** (pós-vivência). A funcionalidade de registration já existe no controller (`registration()` / `saveRegistration()`), mas o **botão/link para acessá-la não está visível** na tela de dias ou não está funcional. Verificar se o link está presente e se a página de registro renderiza corretamente.

---

### R3-08 — Banco de Imagens — upload e drag-and-drop
**Módulo:** Banco de Imagens
**Arquivos:** `views/admin/image-bank/folder.php`, `app/Controllers/Admin/ImageBankController.php`
**Descrição:** Na pasta do aluno (`/admin/image-bank/folder/{id}`), **não há opção visível de upload** nem funcionalidade de arrastar e soltar. O upload existe no controller, mas o botão/modal pode não estar renderizando. Adicionar zona de drag-and-drop e garantir que o botão de upload esteja visível.

---

### R3-09 — Portfólio — erro fatal ao exportar PDF
**Módulo:** Portfólio
**Arquivos:** `app/Services/PdfExportService.php`, `app/Controllers/Admin/PortfolioController.php`
**Descrição:** Ao clicar em "Exportar PDF" na tela do portfólio, ocorre erro fatal: `Uncaught Error: Class "Mpdf\Mpdf" not found` ou erro de imagem inválida no mPDF. Investigar:
1. Se a dependência mPDF está instalada via Composer
2. Se os caminhos das imagens no JSON são válidos
3. Adicionar try-catch robusto no método `generatePortfolioPdf()`

---

### R3-10 — Portfólio — botão "Visualizar" antes de solicitar revisão
**Módulo:** Portfólio
**Arquivos:** `views/admin/portfolios/show.php`
**Descrição:** Incluir botão "Visualizar" na tela do portfólio para que o professor possa revisar o conteúdo completo antes de clicar em "Solicitar Revisão". Pode ser uma pré-visualização em modal ou uma página read-only.

---

### R3-11 — Material de Apoio — upload e drag-and-drop
**Módulo:** Material de Apoio
**Arquivos:** `views/admin/support-materials/folder.php`, `app/Controllers/Admin/SupportMaterialController.php`
**Descrição:** A pasta de materiais (`/admin/support-materials/folder/{id}`) **não possui opção de upload** nem drag-and-drop. O upload existe no controller mas a UI não exibe o botão. Adicionar zona de drag-and-drop e garantir que o formulário de upload esteja funcional.

---

## Ordem de Execução Recomendada

1. **R3-01** — Ortografia (rápida, sem dependência)
2. **R3-02** — Fluxo de observações (crítico, desbloqueia R3-03)
3. **R3-03** — Verificar parecer (deve resolver com R3-02)
4. **R3-06** — Rotina Semanal — salvar (bug crítico)
5. **R3-05** — Rotina Semanal — UI dos campos
6. **R3-04** — Filtro de eixos no planejamento
7. **R3-07** — Página de registro pós-vivência
8. **R3-09** — Exportar PDF portfólio (erro fatal)
9. **R3-08** — Upload/drag-drop no Banco de Imagens
10. **R3-10** — Botão Visualizar no Portfólio
11. **R3-11** — Upload/drag-drop no Material de Apoio
