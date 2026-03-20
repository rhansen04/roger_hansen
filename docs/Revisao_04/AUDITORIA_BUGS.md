# Auditoria de Bugs — Revisão 04
**Data:** 2026-03-20
**Metodologia:** Análise estática de código (4 agentes paralelos) + testes de model

---

## RESUMO EXECUTIVO

| Severidade | Quantidade |
|---|---|
| **CRÍTICO** | 12 |
| **ALTO** | 28 |
| **MÉDIO** | 31 |
| **BAIXO** | 14 |
| **TOTAL** | 85 defeitos identificados |

---

## 🔴 CRÍTICOS — Devem ser corrigidos imediatamente

### C-01 · Open Redirect em CoordinatorFeedbackController
**Arquivo:** `app/Controllers/Admin/CoordinatorFeedbackController.php` linha ~24
**Problema:** `$returnUrl = $_POST['return_url']` usado diretamente em `header('Location: ' . $returnUrl)`.
**Impacto:** Atacante pode redirecionar usuário para domínio malicioso após login.
**Correção:** Validar que `return_url` começa com `/` (path interno apenas).

### C-02 · CSRF ausente em toda a aplicação
**Arquivos:** Todos os controllers com POST — nenhum valida CSRF token.
**Problema:** O token é gerado em `index.php` mas **nunca verificado** nos controllers.
**Impacto:** Qualquer ação pode ser forjada por um site terceiro.
**Módulos afetados:** Auth, Usuários, Escolas, Turmas, Alunos, Observações, Planejamento, Pareceres, Portfólios, Cursos, Quiz, Matrículas.

### C-03 · MIME type de upload controlado pelo cliente
**Arquivos:** `ImageBankController.php` ~L138, `CourseAdminController.php`, `LessonAdminController.php`
**Problema:** `$_FILES['images']['type'][$i]` é valor enviado pelo browser — não verificado no servidor.
**Impacto:** Upload de arquivo malicioso com extensão `.jpg` mas conteúdo PHP.
**Correção:** Usar `finfo_file()` para verificar MIME type real do arquivo.

### C-04 · `$_POST` inteiro passado ao model em PlanningTemplateController
**Arquivo:** `app/Controllers/Admin/PlanningTemplateController.php` linhas 36, 81, 139, 187
**Problema:** `$model->create($_POST)` e `$model->update($_POST)` sem whitelist.
**Impacto:** Qualquer campo da tabela pode ser sobrescrito via POST (mass assignment).
**Correção:** Extrair explicitamente os campos permitidos antes de passar ao model.

### C-05 · Divisão por zero em QuizController
**Arquivo:** `app/Controllers/QuizController.php` linha ~148
**Problema:** `$score = ($correctPoints / $totalPoints) * 100` — se `$totalPoints = 0`, crash fatal.
**Impacto:** Quiz sem pontuação definida derruba a página.
**Correção:** Verificar `if ($totalPoints <= 0)` antes da divisão.

### C-06 · Validação de resposta de quiz não verifica `question_id`
**Arquivo:** `app/Controllers/QuizController.php` linhas ~139-144
**Problema:** Query busca resposta por `id` mas não verifica se pertence à questão correta.
**Impacto:** Usuário pode enviar `question_5=resposta_correta_da_questao_3` e ganhar pontos indevidamente.
**Correção:** Adicionar `AND question_id = ?` na query de validação de resposta.

### C-07 · Session Fixation em AuthController
**Arquivo:** `app/Controllers/AuthController.php` login() ~L52
**Problema:** Sem `session_regenerate_id(true)` após login bem-sucedido.
**Impacto:** Atacante que obteve session ID antes do login mantém sessão autenticada.
**Correção:** Chamar `session_regenerate_id(true)` imediatamente após validar credenciais.

### C-08 · File upload sem validação de tamanho
**Arquivos:** `StudentController.php`, `SchoolController.php`, `ClassroomController.php`, `ImageBankController.php`
**Problema:** Nenhum controller verifica `$_FILES['*']['size']`.
**Impacto:** Upload de arquivos de múltiplos GB pode esgotar disco do servidor.
**Correção:** Adicionar `if ($_FILES['photo']['size'] > 5 * 1024 * 1024)` antes de processar.

### C-09 · XSS em mensagem de erro de DescriptiveReportController
**Arquivo:** `app/Controllers/Admin/DescriptiveReportController.php` linha ~193
**Problema:** Mensagem de erro inclui HTML com link construído a partir de `$studentId` sem escape.
**Impacto:** Se `$studentId` vier de fonte não confiável, pode injetar JavaScript.
**Correção:** Usar `(int)$studentId` ou `htmlspecialchars()`.

### C-10 · `autoSave` sem CSRF e com campo dinâmico
**Arquivo:** `app/Controllers/Admin/ObservationController.php` ~L267-318
**Problema 1:** Endpoint AJAX sem verificação de CSRF.
**Problema 2:** `$field = $input['field']` passado para `updateField()` — se model não tem whitelist, SQL injection.
**Correção:** Verificar CSRF no header, e confirmar que `Observation::updateField()` valida whitelist de campos.

### C-11 · Course enroll sem CSRF
**Arquivo:** `app/Controllers/CourseController.php` `enroll()` ~L215
**Problema:** POST `POST /curso/{slug}/matricular` sem verificação de token.
**Impacto:** Qualquer site pode matricular um usuário em curso sem seu consentimento.

### C-12 · Quiz submit sem CSRF
**Arquivo:** `app/Controllers/QuizController.php` `submit()` ~L97
**Problema:** POST sem CSRF — resposta ao quiz pode ser forjada.

---

## 🟠 ALTOS — Corrigir no próximo sprint

### A-01 · Aluno sem validação de nome/data
`StudentController.php` — name e birth_date aceitos sem validação. Pode criar aluno sem nome.

### A-02 · Escola sem validação de email e datas
`SchoolController.php` — email aceito sem `FILTER_VALIDATE_EMAIL`, datas sem verificação de ordem.

### A-03 · Quiz: `attempts_allowed = 0` causa loop infinito
`QuizController.php` ~L59 — `if ($quiz['attempts_allowed'] > 0)` trata zero como "ilimitado". Documentar ou bloquear zero.

### A-04 · Lição sem whitelist de extensão de arquivo
`LessonAdminController.php` ~L58-69 — não verifica extensão do material da aula.

### A-05 · Curso sem whitelist de extensão na capa
`CourseAdminController.php` ~L63-75 — `pathinfo(PATHINFO_EXTENSION)` sem lista permitida.

### A-06 · `instructor_id` não verificado se existe
`CourseAdminController.php` ~L167 — aceita qualquer número como instructor_id.

### A-07 · `teacher_id` não verificado se é professor
`ClassroomController.php` ~L40,221 — qualquer user_id pode ser definido como professor da turma.

### A-08 · `section_id` da lição não verifica pertencimento ao curso
`LessonAdminController.php` ~L44 — seção pode pertencer a outro curso.

### A-09 · Tipo juggling em verificações de permissão
**Todos os controllers pedagógicos** — usam `!=` em vez de `!==` para comparar IDs. Com tipos diferentes (string vs int), pode causar bypass.

### A-10 · Race condition em uploads (filename = time())
`StudentController.php` ~L38, `SchoolController.php` ~L82 — dois uploads simultâneos no mesmo segundo sobrescrevem o arquivo.
**Correção:** `time() . '_' . uniqid()` + verificar colisão.

### A-11 · `null` não tratado em VideoProgress
`CourseController.php` ~L152 — `$progress['is_completed']` acessado sem null check após `getOrCreate()`.

### A-12 · `options_text` do template não sanitizado
`PlanningTemplateController.php` ~L183 — `explode("\n", $_POST['options_text'])` sem trim/sanitize.

### A-13 · JSON axis_photos sem verificação de decode
`PortfolioController.php` ~L136, `DescriptiveReportController.php` ~L297 — `json_decode()` sem checar retorno null.

### A-14 · Ausência de verificação de retorno nos models
Múltiplos controllers chamam `$model->updateStatus()`, `$model->finalize()` sem verificar se retornou `false`.

### A-15 · Mover imagem para pasta de outra turma
`ImageBankController.php` ~L235 — `newFolderId` não verifica se pertence à mesma turma.

### A-16 · Caption de imagem sem sanitização
`ImageBankController.php` ~L266 — texto salvo sem escape, potencial XSS.

### A-17 · PDF sem verificação de conteúdo
`DescriptiveReportController.php` ~L447 — headers enviados antes de verificar se `$pdfContent` é válido.

### A-18 · Exportação PDF sem verificação de permissão
`DescriptiveReportController.php` ~L424 — qualquer usuário logado pode exportar qualquer parecer.

### A-19 · Reordenação de lição sem transação
`LessonAdminController.php` ~L182-183 — dois `updateSortOrder()` sem transação, race condition.

### A-20 · Questão sem resposta correta passa pela criação
`QuizAdminController.php` ~L204-211 — resposta vazia é silenciosamente ignorada, quiz pode ficar sem resposta.

### A-21 até A-28 · (ver seção completa na análise dos agentes)

---

## 🟡 MÉDIOS — Planejar para próximas revisões

### M-01 · `birth_date` NULL quebra `new \DateTime()` em StudentController
Linha ~L77 — exceção não tratada se data inválida.

### M-02 · Observação com campos legados (category, description, observation_date)
`Observation.php` — schema misto entre método antigo e novo com eixos. Pode gerar registros incompletos.

### M-03 · Planejamento: data fora do período aceita em dayEdit()
`PlanningController.php` ~L474 — parâmetro `$date` sem verificação de estar dentro de period_start/period_end.

### M-04 · Planejamento: notificação não revertida se updateStatus() falha
`PlanningController.php` ~L596-605 — inconsistência de estado.

### M-05 · DescriptiveReport: lógica de compilação duplicada
Linhas 108-171 e 485-506 — DRY violation. Bug corrigido em um lugar pode não ser corrigido no outro.

### M-06 · Portfolio: merge de fotos sem limite de 3
`PortfolioController.php` ~L234-235 — array pode crescer além do máximo esperado.

### M-07 · CoordinatorComment: NULL user_id se obs. vinculada foi deletada
`CoordinatorFeedbackController.php` ~L71-96 — `getContentOwnerId()` pode retornar NULL.

### M-08 · Enrollment: campos cached vs recalculados inconsistentes
`Enrollment.php` — campos como `overall_progress_percentage` são armazenados e sobrescritos em toda leitura.

### M-09 · PlanningPeriodRecord cria tabela no construtor
`PlanningPeriodRecord.php` — `createTable()` executado a cada instância. Usar migrations.

### M-10 · Status enums sem constantes centralizadas
Observações, Pareceres, Portfólios e Planejamentos usam strings literais diferentes. Sem enum PHP ou constantes.

### M-11 até M-31 · (ver análise detalhada dos agentes)

---

## 🔵 SCHEMA — Inconsistências no Banco

| Issue | Detalhe |
|---|---|
| `axis_photos` tipo indefinido | É JSON? Array serializado? Varchar? Sem documentação. |
| `observation.category` e `observation_date` | Colunas legadas ainda presentes no schema. |
| Falta de UNIQUE em `planning_submissions(submission_id)` em PeriodRecord | Unique declarado em código mas pode não estar no banco. |
| Sem FOREIGN KEY constraints explícitas | `coordinator_comments.content_id` pode referenciar IDs inexistentes. |
| `enrollment.enrollment_date` ausente no INSERT | Campo usado em queries mas não inserido na criação. |
| Tabelas não modeladas explicitamente | `planning_submission_answers`, `planning_daily_entries`, `classroom_students` existem apenas em queries inline. |

---

## 📋 COMO EXECUTAR OS TESTES

```bash
# Todos os módulos
php tests/test_forms_completo.php

# Módulo específico
php tests/test_forms_completo.php --modulo=observations
php tests/test_forms_completo.php --modulo=planning
php tests/test_forms_completo.php --modulo=reports
php tests/test_forms_completo.php --modulo=portfolios
php tests/test_forms_completo.php --modulo=auth
php tests/test_forms_completo.php --modulo=students
php tests/test_forms_completo.php --modulo=quiz
php tests/test_forms_completo.php --modulo=workflows

# Com saída detalhada
php tests/test_forms_completo.php --verbose
```

---

## 📊 PRIORIDADE DE CORREÇÃO

```
Sprint 1 (urgente):  C-02 CSRF, C-01 Open Redirect, C-05 Divisão por zero, C-07 Session fixation
Sprint 2 (crítico):  C-03 MIME, C-04 Mass assignment, C-06 Quiz answer, C-08 File size
Sprint 3 (alto):     A-01..A-10 (validações de campo e permissão)
Sprint 4 (médio):    M-01..M-10 (schema, DRY, estados inconsistentes)
```

---

*Gerado por auditoria automatizada em 2026-03-20*
