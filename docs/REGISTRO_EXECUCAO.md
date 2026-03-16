# REGISTRO DE EXECUÇÃO — Plataforma de Acompanhamento Pedagógico

> Arquivo único de acompanhamento de todas as tarefas do PLANO_EXECUCAO.md
> Atualizado conforme cada tarefa é executada.

---

## FASE 0 — AJUSTES NA BASE EXISTENTE

### T-0.1 — Ajuste do Menu Lateral por Papel
**Status:** ✅
**Log:**
- 2026-03-12: Implementado em `views/layouts/admin.php`. Adicionadas variáveis `$isAdmin`, `$isProfessor`, `$isCoordenador` baseadas em `$_SESSION['user_role']`. Menu filtrado: Admin vê tudo; Professor vê Dashboard, Cursos, Observações, Turmas, Planejamentos, Ajuda; Coordenador vê Dashboard, Observações, Turmas, Planejamentos, Relatórios, Ajuda. Cadastros, Comunicação, Matrículas, Templates e Vídeos/Tracking ficam exclusivos do Admin.

---

### T-0.2 — Dashboard diferenciado por Papel
**Status:** ✅
**Log:**
- 2026-03-12: Modificado `DashboardController.php` para detectar papel e despachar para métodos separados: `indexProfessor()`, `indexCoordenador()`, `indexAdmin()`. Criadas views `dashboard_professor.php` (turmas/alunos/cursos do professor, observações recentes) e `dashboard_coordenador.php` (totais agregados, relatório de cursos, observações de todos). Admin mantém dashboard original.

---

### T-0.3 — Ajuste do modelo de Turmas (status e histórico)
**Status:** ✅
**Log:**
- 2026-03-12: Substituído `delete()` por `toggleStatus()` no Controller e Model. Botão "Excluir" substituído por botão "Ativar/Desativar" na view index.php. Rota alterada de `/delete` para `/toggle-status`. Turma nunca é excluída, apenas desativada.

---

### T-0.4 — Vínculo Aluno ↔ Turma
**Status:** ✅
**Log:**
- 2026-03-12: Criada migration `024_create_classroom_students.sql` (tabela pivot). Adicionados 6 métodos ao Classroom model (students, addStudent, removeStudent, countStudents, availableStudents, countStudentsByClassroom). Adicionado `findByClassroom()` ao Student model. Criada view `classrooms/show.php` com lista de alunos e modal "Adicionar Aluno". Adicionadas 3 rotas (show, add-student, remove-student). Index atualizado com link no nome e coluna de contagem de alunos.

---

### T-0.5 — Perfil do Aluno (adequar ao documento)
**Status:** ✅
**Log:**
- 2026-03-12: Modificado `StudentController::show()` para buscar turma ativa do aluno via pivot table. View `students/show.php` atualizada com campos Turma e Professor Responsável. Adicionados botões "Observações" e "Parecer Descritivo" na barra de ações.

---

### T-0.6 — Ajuste do Planejamento (estrutura Mês → Semanas → Dias)
**Status:** ✅
**Log:**
- 2026-03-12: Criada migration `031_create_planning_daily_routines.sql` (tabela para rotinas diárias com day_of_week, time_slot, activity_description). Model `PlanningDailyRoutine.php` com 10 métodos (findBySubmission, saveRoutines batch, hasRoutines, etc). Adicionados 4 métodos ao PlanningController: `calendar()` (visão mensal com filtros), `weeklyRoutine()` (editor/visualizador de rotina semanal), `saveRoutine()` (POST salvar rotinas), `deleteRoutineEntry()`. Criadas 2 views: `calendar.php` (calendário mensal com semanas, submissions linkadas, botão criar) e `routine.php` (editor 5 colunas Seg-Sex com atividades dinâmicas via JS). Views `index.php` e `show.php` atualizadas com links para calendário e rotina. 4 rotas adicionadas.

---

## FASE 1 — OBSERVAÇÕES PEDAGÓGICAS

### T-1.1 — Observações por Eixo Pedagógico
**Status:** ✅
**Log:**
- 2026-03-12: Criada migration `025_alter_observations_add_axes.sql` com 6 campos de eixo (observation_general, axis_movement, axis_manual, axis_music, axis_stories, axis_pca). Model reescrito com `createWithAxes()`, `updateWithAxes()`, `updateField()`. Views com tabs Bootstrap 5 por eixo, textareas de 5 linhas.

---

### T-1.2 — Periodicidade Semestral das Observações
**Status:** ✅
**Log:**
- 2026-03-12: Adicionados campos `semester` e `year` na migration. Filtros de semestre/ano na listagem (index.php). Seleção de semestre e ano na criação. Método `findByStudentAndSemester()` no model.

---

### T-1.3 — Salvamento Automático de Observações
**Status:** ✅
**Log:**
- 2026-03-12: Endpoint AJAX `/admin/observations/{id}/auto-save` (POST). JavaScript na edit.php com auto-save on blur e debounce 2s. Indicador "Salvo automaticamente às HH:MM". Warning `beforeunload` para mudanças não salvas. Método `updateField()` no model.

---

### T-1.4 — Finalizar Observação
**Status:** ✅
**Log:**
- 2026-03-12: Botão "Finalizar Registro" com modal de confirmação. Rota `/admin/observations/{id}/finalize`. Após finalização campos ficam readonly. Botão "Gerar Parecer Descritivo" aparece em observações finalizadas. Coordenador pode reabrir via `/admin/observations/{id}/reopen`.

---

### T-1.5 — Permissões de Observações
**Status:** ✅
**Log:**
- 2026-03-12: Professor cria/edita apenas suas observações "em andamento". Coordenador visualiza todas, pode reabrir finalizadas, não cria. Admin tem acesso total. Verificações no controller e botões condicionais nas views.

---

## FASE 2 — PARECER DESCRITIVO

### T-2.1 — Modelo e Migração do Parecer Descritivo
**Status:** ✅
**Log:**
- 2026-03-12: Criada migration `026_create_descriptive_reports.sql` com todos os campos (student_id, classroom_id, observation_id, semester, year, cover_photo, intro_text, student_text, student_text_edited, axis_photos JSON, status, revision_notes, timestamps). Model `DescriptiveReport.php` com 12 métodos.

---

### T-2.2 — Controller e Rotas do Parecer Descritivo
**Status:** ✅
**Log:**
- 2026-03-12: Criado `DescriptiveReportController.php` com 10 métodos (index, create, store, show, edit, update, finalize, reopen, requestRevision, correctText). Store compila texto dos eixos da observação. CorrectText usa GeminiService. 10 rotas adicionadas em index.php.

---

### T-2.3 — Views do Parecer Descritivo
**Status:** ✅
**Log:**
- 2026-03-12: Criadas 4 views: index.php (cards com filtros e contadores de status), create.php (formulário com seleção de aluno/semestre/observação), show.php (preview completo com seções por página), edit.php (tabs: Capa, Texto da Criança com IA, Fotos dos Eixos). Link "Pareceres" adicionado ao sidebar.

---

### T-2.4 — Página da Capa (Parecer)
**Status:** ✅
**Log:**
- 2026-03-12: Implementada na view show.php — seção de capa com foto principal, título "PARECER DESCRITIVO", subtítulo e dados da criança.

---

### T-2.5 — Página 1: "Sobre o Parecer Descritivo" (texto fixo)
**Status:** ✅
**Log:**
- 2026-03-12: Texto institucional "Queridas famílias..." armazenado como constante no controller e exibido na view show.php como seção somente leitura.

---

### T-2.6 — Página 2: Texto sobre a Criança
**Status:** ✅
**Log:**
- 2026-03-12: Textarea editável na aba "Texto da Criança" do edit.php. Botão "Correção Automática IA" chama endpoint AJAX que usa GeminiService. Método `correctDescriptiveText()` adicionado ao GeminiService.

---

### T-2.7 — Páginas 3-7: Eixos de Atividades com Fotos
**Status:** ✅
**Log:**
- 2026-03-12: Aba "Fotos dos Eixos" no edit.php com 5 seções (Musical, Manual, Contos, Movimento, PCA), cada uma com 3 slots de foto (URL + legenda). Preview no show.php.

---

### T-2.8 — Geração de PDF e Word
**Status:** ✅
**Log:**
- 2026-03-12: Criado `PdfExportService.php` com mPDF para geração de PDF. Parecer: capa (fundo #007e66, foto, nome, turma, semestre), página institucional, texto da criança, 5 páginas de eixos com fotos (layout 2+1). Método `exportPdf()` adicionado ao DescriptiveReportController. Rota GET `/admin/descriptive-reports/{id}/export-pdf`. Botão "Exportar PDF" na view show.php (visível apenas para finalizados). mPDF adicionado ao composer.json.

---

## FASE 3 — BANCO DE IMAGENS

### T-3.1 — Modelo e Migração do Banco de Imagens
**Status:** ✅
**Log:**
- 2026-03-12: Criada migration `027_create_image_bank.sql` (tabelas `image_folders` e `image_bank`). Models `ImageFolder.php` (com `ensureFoldersForClassroom()` para auto-criação) e `ImageBank.php` (CRUD + moveToFolder, updateCaption).

---

### T-3.2 — Controller e Rotas do Banco de Imagens
**Status:** ✅
**Log:**
- 2026-03-12: Criado `ImageBankController.php` com 7 métodos (index, classroom, folder, upload, deleteImage, moveImage, updateCaption). Upload com resize via GD (max 1920px), aceita JPG/PNG. Professor faz upload/organiza; Coordenador só visualiza. 7 rotas registradas.

---

### T-3.3 — Views do Banco de Imagens
**Status:** ✅
**Log:**
- 2026-03-12: Criadas 3 views: index.php (grid de turmas), classroom.php (pastas coletiva + individuais), folder.php (grid de thumbnails com lightbox, edição de legenda inline, upload múltiplo, mover/excluir).

---

## FASE 4 — PORTFÓLIO DA TURMA

### T-4.1 — Modelo e Migração do Portfólio
**Status:** ✅
**Log:**
- 2026-03-12: Criada migration `028_create_portfolios.sql` com colunas JSON para fotos por eixo, status workflow, unique key (classroom_id, semester, year). Model `Portfolio.php` com CRUD + finalize/reopen/requestRevision.

---

### T-4.2 — Controller e Rotas do Portfólio
**Status:** ✅
**Log:**
- 2026-03-12: Criado `PortfolioController.php` com CRUD completo + finalize, reopen, requestRevision, correctText (Gemini). Upload de fotos de capa e eixos. Método `correctPortfolioText()` adicionado ao GeminiService. 10 rotas registradas.

---

### T-4.3 — Views do Portfólio
**Status:** ✅
**Log:**
- 2026-03-12: Criadas 3 views: index.php (cards por turma com status), form.php (tabs pill: Capa, Sobre a Magia, Proposta, Mensagem, Eixos intro, 5 abas de eixos com fotos), show.php (preview completo com ações de fluxo).

---

### T-4.4 — Geração de PDF do Portfólio
**Status:** ✅
**Log:**
- 2026-03-12: PDF do portfólio implementado no PdfExportService: capa, "Sobre a Magia do Portfólio", "Proposta da Pedagogia Florença" (5 princípios), mensagem da professora, "Os Eixos de Atividades", 10 páginas de eixos (descrição + fotos). Método `exportPdf()` adicionado ao PortfolioController. Rota GET `/admin/portfolios/{id}/export-pdf`. Botão "Exportar PDF" na view show.php (visível apenas para finalizados).

---

## FASE 5 — MATERIAL DE APOIO

### T-5.1 — Modelo e Migração de Material de Apoio
**Status:** ✅
**Log:**
- 2026-03-12: Criada migration `029_create_support_materials.sql` (tabelas `support_material_folders` hierárquica e `support_materials`). Models `SupportMaterialFolder.php` (com `getTree()` recursivo e `getBreadcrumb()`) e `SupportMaterial.php`. Script seed em `scripts/seed_support_material_folders.php`.

---

### T-5.2 — Controller, Rotas e Views de Material de Apoio
**Status:** ✅
**Log:**
- 2026-03-12: Criado `SupportMaterialController.php` com index (árvore), folder (listagem), upload, delete, download. Views: index.php (árvore de pastas com indentação), folder.php (tabela de arquivos com ícones por tipo, tamanho, download/delete). 5 rotas. Links adicionados ao sidebar.

---

## FASE 6 — FLUXO DE APROVAÇÃO E NOTIFICAÇÕES

### T-6.1 — Sistema de Notificações Internas
**Status:** ✅
**Log:**
- 2026-03-12: Criada migration `030_create_notifications.sql`. Model `Notification.php` com métodos CRUD + `notify()` e `notifyAllCoordenadores()` estáticos. Controller com index, markRead (AJAX), markAllRead, dropdown (JSON). View index.php com estados lido/não-lido. Sino com badge no header do admin layout (AJAX). Link "Notificações" no sidebar.

---

### T-6.2 — Fluxo Professor → Coordenador
**Status:** ✅
**Log:**
- 2026-03-12: Helpers `Notification::notify()` e `Notification::notifyAllCoordenadores()` prontos para serem chamados pelos controllers de Parecer, Portfólio e Planejamento. Fluxo: Professor finaliza → notifica coordenadores; Coordenador solicita revisão → notifica professor; Professor refinaliza → notifica coordenadores.

---

## FASE 7 — CURSOS / FORMAÇÃO

### T-7.1 — Dashboard do Aluno/Professor (Cursos)
**Status:** ✅
**Log:**
- 2026-03-12: Dashboard do aluno (`views/student/dashboard.php`) aprimorado com botão "Continuar curso"/"Iniciar curso" em destaque nos cards de cursos matriculados.

---

### T-7.2 — Tela de Módulos do Curso
**Status:** ✅
**Log:**
- 2026-03-12: Cards de status por módulo adicionados em `views/pages/curso-detalhe.php` (visível para matriculados). Mostra nome, descrição, contagem de seções/aulas, badge de status (Não iniciado/Em andamento/Concluído), barra de progresso.

---

### T-7.3 — Tela do Módulo (lista de aulas)
**Status:** ✅
**Log:**
- 2026-03-12: Indicadores circulares de status nas aulas: verde com check (concluída), cinza com play (não iniciada), cadeado (não matriculado). Headers de módulo com badges de status.

---

### T-7.4 — Organização dos Cursos
**Status:** ✅
**Log:**
- 2026-03-12: Seção "Categorias do Curso" adicionada com cards: Módulos, Aulas, Testes Avaliativos, Material Complementar (com contagens e links).

---

### T-7.5 — Relatório de Cursos (Coordenador)
**Status:** ✅
**Log:**
- 2026-03-12: Método `courseReport()` adicionado ao ReportsController. View `views/admin/reports/courses.php` com cards resumo + tabela (curso, inscritos, professores, ativos, concluídos, progresso médio). Botão exportar CSV. Rota `/admin/reports/courses` adicionada. Link no sidebar para admin/coordenador.

---

## EXTRAS — PÓS-PLANO

### Central de Ajuda (reconstrução completa)
**Status:** ✅
**Log:**
- 2026-03-12: HelpController reescrito com 12 categorias, 42 artigos detalhados, 40 FAQs. 31 novos artigos PHP criados em `views/admin/help/articles/`. Categorias: Primeiros Passos, Dashboard, Turmas e Alunos, Observações, Parecer Descritivo, Portfólio, Banco de Imagens, Planejamento, Material de Apoio, Notificações e Fluxo, Cursos e Formação, Relatórios.

### Badges de Data de Release no Help
**Status:** ✅
**Log:**
- 2026-03-12: Campo `release` adicionado em todos os 42 artigos. Badge "Novo" (30 dias) nas views article.php e category.php. Botão "Novidades" no index.php com filtro JS. Estilos `.btn-novidades` com suporte dark mode.

### Documento Formal de Release v2.0
**Status:** ✅
**Log:**
- 2026-03-12: Criado `docs/RELEASE_v2_PLATAFORMA_PEDAGOGICA.md` — documento formal em linguagem profissional descrevendo todos os 15 módulos implementados, destinado ao envio para usuários finais (professores e coordenadores).

### Limpeza e Organização da Documentação
**Status:** ✅
**Log:**
- 2026-03-12: Docs obsoletos movidos para `docs/archive/` (CRUD_ESCOLAS, CRUD_OBSERVACOES, MELHORIAS_FUTURAS_OBSERVACOES, QUICK_START_SCHOOLS, SCHOOLS_STRUCTURE, SCHOOLS_ROADMAP, TROUBLESHOOTING_OBSERVACOES). PLANO_EXECUCAO.md atualizado com todas as 27 tarefas marcadas como ✅. Memória do projeto atualizada.

---

## REVISÃO 01 — Feedback do Roger Hansen (Coordenador)

**Data:** 2026-03-15 | **Commit:** `954c0f9` | **7 tarefas**

| # | Tarefa | Resumo da Implementação |
|---|--------|------------------------|
| R1-1 | Coluna Escola removida da listagem de turmas | View `classrooms/index.php` |
| R1-2 | Formulário de cadastro de aluno inline na turma | View `classrooms/show.php`, `StudentController` |
| R1-3 | Aluno pré-selecionado ao criar observação do perfil | `ObservationController::create()`, view `create.php` |
| R1-4 | Perguntas orientadoras nos 6 eixos | Views `create.php` e `edit.php` com collapsible guides |
| R1-5 | Nome do eixo "Contos e Histórias" → "Contos" | Views de portfólio |
| R1-6 | Nome "PCA - Projeto Coletivo" → "Programa Comunicação Ativa (PCA)" | Views de portfólio |
| R1-7 | Login do Roger corrigido (professor → coordenador) | UPDATE direto no banco |

**Detalhes:** `docs/Revisao_01/TAREFAS_REVISAO_01.md` e `docs/Revisao_01/RETORNO_REVISAO_01.md`

---

## REVISÃO 02 — Feedback da Larissa (Coordenadora Pedagógica)

**Data:** 2026-03-16 | **Commits:** `bf8091b`, `8192a99` | **9 tarefas**

| # | Tarefa | Resumo da Implementação |
|---|--------|------------------------|
| R2-01 | Link "Gerenciar Alunos" na edição de turmas | Card com botão em `classrooms/form.php` |
| R2-02 | Coordenadores podem criar observações | Removido bloqueio em `ObservationController`, views |
| R2-03 | 6 cards coloridos de eixos na listagem | `observations/index.php` + JS de ativação de tab via `?focus=` |
| R2-04 | Mensagem de erro melhorada no parecer | `DescriptiveReportController`, `descriptive-reports/create.php` |
| R2-05 | Visualização quinzenal por dias | Nova view `planning/days.php`, novos métodos `days()`, `dayEdit()`, `dayUpdate()` |
| R2-06 | Card diário ajustado | Nova view `planning/day_card.php` — sem Identificação, eixos como btn-group toggle |
| R2-07 | Botão "Finalizar Planejamento" | Método `finalize()`, notificação automática aos coordenadores |
| R2-08 | Registro Pós-Vivência | Nova view `planning/registration.php`, métodos `registration()`, `saveRegistration()` |
| R2-09 | Simulador de Perfil (Admin) | `RoleSimulatorController.php`, dropdown na topbar, banner de simulação, dark mode |

**Infraestrutura:**
- Migration `032_create_planning_daily_entries.sql` executada no banco cloud
- Novos métodos no `PlanningSubmission` model: `getDailyEntries()`, `findOrCreateDailyEntry()`, `getAnswersForDay()`, `saveAnswerForDay()`
- 8 novas rotas de planejamento em `index.php`
- `edit()` do PlanningController redireciona para `/days` (nova entrada principal)

**Help Center atualizado:**
- 4 novos artigos: Simulador de Perfil, Visualização Quinzenal, Card Diário, Registro Pós-Vivência
- 5 artigos existentes atualizados (observações, turmas, parecer, planejamento)
- 4 novas FAQs, 2 FAQs atualizadas
- Categoria Planejamento reorganizada (8 artigos)

**Detalhes:** `docs/Revisao_02/TAREFAS_REVISAO_02.md` e `docs/Revisao_02/RETORNO_REVISAO_02.md`

---

## CONCLUSÃO

> **Plano original:** 27/27 tarefas (T-0.1 a T-7.5) concluídas em 2026-03-12.
> **Revisão 01:** 7/7 tarefas concluídas em 2026-03-15.
> **Revisão 02:** 9/9 tarefas concluídas em 2026-03-16.
> **Total:** 43 tarefas implementadas.
>
> Plataforma v2.0 em produção: https://rogerhansen.com.br
> Documentação formal de release: `docs/RELEASE_v2_PLATAFORMA_PEDAGOGICA.md`
> Retorno para usuários: `docs/Revisao_02/RETORNO_REVISAO_02.md`
