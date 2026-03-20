# Lista Completa de Bugs — Ordem Numérica
**Projeto:** Hansen Educacional — SITE_NOVO
**Data:** 2026-03-20
**Total:** 85 bugs | ✅ 85 corrigidos | ⚠️ 0 pendentes

---

## 🔴 CRÍTICOS (C-01 a C-12)

### BUG-001 · Open Redirect em CoordinatorFeedbackController
**Arquivo:** `app/Controllers/Admin/CoordinatorFeedbackController.php` ~L24
**Problema:** `$returnUrl = $_POST['return_url']` usado direto em `header('Location: ...')`.
**Risco:** Usuário redirecionado para domínio malicioso (phishing).
**✅ Correção (2026-03-20):** `$returnUrl` substituído por `Csrf::safeRedirectUrl($rawUrl, '/admin/dashboard')` — aceita apenas paths internos que começam com `/`.

---

### BUG-002 · CSRF ausente — AuthController
**Arquivo:** `app/Controllers/AuthController.php`
**Problema:** Nenhum POST valida o CSRF token.
**✅ Correção (2026-03-20):** `Csrf::verify()` adicionado no início de `login()`, `register()`, `sendResetLink()`, `resetPassword()`.

---

### BUG-003 · CSRF ausente — UserController
**Arquivo:** `app/Controllers/Admin/UserController.php`
**Problema:** Formulários sem CSRF.
**✅ Correção (2026-03-20):** `Csrf::verify()` adicionado em `store()`, `update()`, `delete()`.

---

### BUG-004 · CSRF ausente — StudentController
**Arquivo:** `app/Controllers/Admin/StudentController.php`
**✅ Correção (2026-03-20):** `Csrf::verify()` adicionado em `store()` e `update()`.

---

### BUG-005 · CSRF ausente — SchoolController
**Arquivo:** `app/Controllers/Admin/SchoolController.php`
**✅ Correção (2026-03-20):** `Csrf::verify()` adicionado em `store()` e `update()`.

---

### BUG-006 · CSRF ausente — ClassroomController
**Arquivo:** `app/Controllers/Admin/ClassroomController.php`
**✅ Correção (2026-03-20):** `Csrf::verify()` adicionado em `store()`, `update()`, `addStudent()`.

---

### BUG-007 · CSRF ausente — ObservationController
**Arquivo:** `app/Controllers/Admin/ObservationController.php`
**✅ Correção (2026-03-20):** `Csrf::verify()` adicionado em `store()`, `update()`, `finalize()`, `reopen()`, `delete()`. Endpoint `autoSave` tratado separadamente em BUG-020.

---

### BUG-008 · CSRF ausente — PlanningController e PlanningTemplateController
**Arquivo:** `app/Controllers/Admin/PlanningController.php` e `PlanningTemplateController.php`
**✅ Correção (2026-03-20):**
- `PlanningTemplateController`: `Csrf::verify()` em `store()`, `update()`, `addSection()`, `updateSection()`, `addField()`.
- `PlanningController`: `Csrf::verify()` em 9 métodos POST: `store()`, `update()`, `saveRoutine()`, `dayUpdate()`, `finalize()`, `saveRegistration()`, `deleteRoutineEntry()`, `recordStore()`, `recordUpdate()`.

---

### BUG-009 · CSRF ausente — DescriptiveReportController
**Arquivo:** `app/Controllers/Admin/DescriptiveReportController.php`
**✅ Correção (2026-03-20):** `Csrf::verify()` em 7 métodos: `store()`, `update()`, `finalize()`, `reopen()`, `requestRevision()`, `recompile()`, `correctText()`.

---

### BUG-010 · CSRF ausente — PortfolioController
**Arquivo:** `app/Controllers/Admin/PortfolioController.php`
**✅ Correção (2026-03-20):** `Csrf::verify()` em 6 métodos: `store()`, `update()`, `finalize()`, `requestRevision()`, `reopen()`, `correctText()`.

---

### BUG-011 · CSRF ausente — CoordinatorFeedbackController
**Arquivo:** `app/Controllers/Admin/CoordinatorFeedbackController.php`
**✅ Correção (2026-03-20):** `Csrf::verify()` adicionado no início de `store()`.

---

### BUG-012 · CSRF ausente — CourseController (enroll)
**Arquivo:** `app/Controllers/CourseController.php`
**✅ Correção (2026-03-20):** `Csrf::verify()` adicionado no início de `enroll()`.

---

### BUG-013 · CSRF ausente — QuizController (submit)
**Arquivo:** `app/Controllers/QuizController.php`
**✅ Correção (2026-03-20):** `Csrf::verify()` adicionado no início de `submit()`.

---

### BUG-014 · Session Fixation no login
**Arquivo:** `app/Controllers/AuthController.php` login()
**Problema:** Sem `session_regenerate_id(true)` após autenticação.
**✅ Correção (2026-03-20):** `session_regenerate_id(true)` inserido imediatamente após definir `$_SESSION['user_id']`, `$_SESSION['user_name']`, `$_SESSION['user_role']` — antes de `updateLastLogin()`.

---

### BUG-015 · MIME type de upload controlado pelo cliente
**Arquivos:** `ImageBankController.php`, `CourseAdminController.php`, `LessonAdminController.php`
**Problema:** `$_FILES['*']['type']` enviado pelo browser, não verificado no servidor.
**✅ Correção (2026-03-20):**
- `ImageBankController`: substituído por `finfo->file(tmp_name)` com whitelist `image/jpeg, image/png, image/gif, image/webp`.
- `CourseAdminController`: verificação `finfo` adicionada à capa do curso.
- `StudentController` / `SchoolController` / `ClassroomController`: `finfo` aplicado em todos uploads de foto.

---

### BUG-016 · Mass assignment — $_POST inteiro ao model
**Arquivo:** `app/Controllers/Admin/PlanningTemplateController.php` L36, L81, L139, L187
**Problema:** `$model->create($_POST)` sem whitelist.
**✅ Correção (2026-03-20):** Em todos os 5 métodos afetados, `$_POST` foi substituído por arrays explícitos com apenas os campos permitidos (`title`, `description`, `is_active`, `label`, `field_type`, `options_text`, `sort_order`, `is_required`). Lógica de `options_json` preservada.

---

### BUG-017 · Divisão por zero no QuizController
**Arquivo:** `app/Controllers/QuizController.php` ~L148
**Problema:** `($correctPoints / $totalPoints) * 100` sem guardar contra zero.
**✅ Correção (2026-03-20):** Substituído por bloco `if ($totalPoints <= 0) { $percentage = 0; } else { $percentage = round(...) }`.

---

### BUG-018 · Quiz: resposta não valida question_id
**Arquivo:** `app/Controllers/QuizController.php` ~L139-144
**Problema:** Query busca resposta por ID sem verificar pertencimento à questão.
**✅ Correção (2026-03-20):** Verificado — query já continha `AND question_id = ?`. Bug não estava presente na versão atual. Nenhuma mudança necessária.

---

### BUG-019 · XSS em mensagem de erro — DescriptiveReportController
**Arquivo:** `app/Controllers/Admin/DescriptiveReportController.php` ~L193
**Problema:** `$studentId` em HTML sem escape.
**✅ Correção (2026-03-20):** Substituído por `(int)$studentId` explícito no link HTML.

---

### BUG-020 · autoSave sem CSRF e campo dinâmico — ObservationController
**Arquivo:** `app/Controllers/Admin/ObservationController.php` ~L267-318
**✅ Correção (2026-03-20):** `autoSave()` valida CSRF via header `HTTP_X_CSRF_TOKEN` ou campo `csrf_token` no JSON body. Campo validado contra whitelist explícita `['observation_general','axis_movement','axis_manual','axis_music','axis_stories','axis_pca']` antes de qualquer acesso ao model.

---

## 🟠 ALTOS (BUG-021 a BUG-060)

### BUG-021 · Aluno sem validação de nome
**✅ Correção (2026-03-20):** `StudentController::store()` e `update()` — `if (empty(trim($name)))` adicionado com redirecionamento e mensagem de erro.

---

### BUG-022 · Aluno sem validação de birth_date
**✅ Correção (2026-03-20):** Validação com `DateTime::createFromFormat('Y-m-d', ...)` adicionada, rejeitando datas inválidas e datas futuras.

---

### BUG-023 · Aluno sem validação de school_id
**✅ Correção (2026-03-20):** `StudentController::store()` e `update()` verificam existência do `school_id` via `SELECT id FROM schools WHERE id = ? LIMIT 1`. Erro exibido se escola não encontrada.

---

### BUG-024 · File upload sem limite de tamanho — StudentController
**✅ Correção (2026-03-20):** Limite de 5 MB adicionado: `if ($_FILES['photo']['size'] > 5 * 1024 * 1024)`.

---

### BUG-025 · Race condition no filename — StudentController
**✅ Correção (2026-03-20):** Filename alterado para `time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext`.

---

### BUG-026 · Escola sem validação de email
**✅ Correção (2026-03-20):** `filter_var($email, FILTER_VALIDATE_EMAIL)` adicionado em `store()` e `update()`.

---

### BUG-027 · Escola sem validação de datas de contrato
**✅ Correção (2026-03-20):** `DateTime::createFromFormat` valida formato e garante `start_date <= end_date`.

---

### BUG-028 · File upload sem limite de tamanho — SchoolController
**✅ Correção (2026-03-20):** Limite de 5 MB adicionado no upload do logo.

---

### BUG-029 · Race condition no filename — SchoolController
**✅ Correção (2026-03-20):** Filename seguro com `time() . '_' . bin2hex(random_bytes(4))`.

---

### BUG-030 · teacher_id não verificado se é professor — ClassroomController
**✅ Correção (2026-03-20):** Query `SELECT id FROM users WHERE id = ? AND role = 'professor'` adicionada em `store()` e `update()`. Rejeita se não for professor.

---

### BUG-031 · File upload sem limite de tamanho — ClassroomController
**✅ Correção (2026-03-20):** Limite de 5 MB adicionado no upload de foto em `addStudent()`.

---

### BUG-032 · empty() com ID 0 rejeita input válido — ClassroomController
**✅ Correção (2026-03-20):** `empty($id)` substituído por `(int)$id <= 0`.

---

### BUG-033 · Tipo juggling em permissões — ObservationController
**✅ Correção (2026-03-20):** Todos os 6 `!=` substituídos por `!==` com `(int)` cast em `show()`, `edit()`, `update()`, `autoSave()`, `finalize()`, `delete()`.

---

### BUG-034 · Tipo juggling em permissões — PlanningController
**✅ Correção (2026-03-20):** Todos os 11 `!=` substituídos por `!==` com `(int)` cast explícito. Cobre `show()`, `weeklyRoutine()`, `days()`, `dayEdit()`, `dayUpdate()`, `finalize()`, `registration()`, `saveRegistration()`, `saveRoutine()`, `deleteRoutineEntry()`.

---

### BUG-035 · Planejamento sem validação de data
**✅ Correção (2026-03-20):** `DateTime::createFromFormat` adicionado em `store()`. Redireciona com erro se datas inválidas ou invertidas.

---

### BUG-036 · dayEdit() aceita data fora do período
**✅ Correção (2026-03-20):** Validação adicionada: `$d >= $start && $d <= $end`. Redireciona para `/admin/planning/{id}/days` se inválida.

---

### BUG-037 · recordStore() e recordUpdate() sem whitelist
**✅ Correção (2026-03-20):** Ambos os métodos agora constroem `$data` explícito com os 19 campos permitidos. Campos de texto recebem `trim()`, checkboxes recebem cast `isset() ? 1 : 0`. `$_POST` não é mais passado diretamente ao model.

---

### BUG-039 · PDF sem verificação de conteúdo antes dos headers
**✅ Correção (2026-03-20):** `empty($pdfContent)` verificado antes de enviar qualquer header. Redireciona com erro se vazio.

---

### BUG-040 · Exportação PDF sem verificação de permissão
**✅ Correção (2026-03-20):** Para `user_role === 'professor'`, adicionada query de verificação via JOIN `descriptive_reports ↔ classrooms → teacher_id`. Acesso negado se não for o professor da turma.

---

### BUG-041 · JSON de fotos sem verificação de decode — PortfolioController
**✅ Correção (2026-03-20):** `json_decode()` seguido de `if (!is_array($photos)) $photos = []` em `show()`, `edit()` e `update()`.

---

### BUG-042 · Portfolio: merge de fotos sem limite
**✅ Correção (2026-03-20):** Após merge, `array_values(array_slice($merged, 0, 6))` limita a 6 fotos por eixo.

---

### BUG-043 · Mover imagem para pasta de outra turma
**✅ Correção (2026-03-20):** `moveImage()` agora verifica via SQL que `classroom_id` da imagem origem === `classroom_id` da pasta destino. Retorna HTTP 403 se diferente.

---

### BUG-044 · Caption de imagem sem sanitização
**✅ Correção (2026-03-20):** `htmlspecialchars(trim(...), ENT_QUOTES, 'UTF-8')` aplicado antes de salvar.

---

### BUG-045 · File upload sem limite — ImageBankController
**✅ Correção (2026-03-20):** Limite de 10 MB por imagem adicionado no loop de upload.

---

### BUG-046 · Sem whitelist de extensão — CourseAdminController
**✅ Correção (2026-03-20):** Whitelist `['jpg','jpeg','png','gif','webp']` + verificação `finfo` aplicados em `store()` e `update()`.

---

### BUG-047 · instructor_id não verificado se existe
**✅ Correção (2026-03-20):** Query `SELECT id FROM users WHERE id = ?` adicionada. Rejeita se instrutor não existir.

---

### BUG-048 · Sem whitelist de extensão — LessonAdminController
**✅ Correção (2026-03-20):** Whitelist `['pdf','doc','docx','xls','xlsx','ppt','pptx','zip','mp4','mp3']` adicionada em `store()` e `update()`.

---

### BUG-049 · section_id da lição não verifica pertencimento ao curso
**✅ Correção (2026-03-20):** Em `store()`, após `$section = find($sectionId)`, adicionada verificação `Course::find($section['course_id'])`. Redireciona para `/admin/courses` se o curso não existir, impedindo lição em seção órfã.

---

### BUG-050 · Reordenação de lição sem transação
**✅ Correção (2026-03-20):** Os dois `updateSortOrder()` em `reorder()` foram envolvidos em `beginTransaction()` / `commit()` / `rollBack()`. Falha em qualquer um reverte ambos atomicamente.

---

### BUG-051 · direction não validado em reorder()
**✅ Correção (2026-03-20):** `in_array($direction, ['up', 'down'])` adicionado. Retorna HTTP 400 com JSON de erro se inválido.

---

### BUG-052 · Quiz: section_id aceita 0
**✅ Correção (2026-03-20):** `$sectionId = (int)($_POST['section_id'] ?? 0)`. Se `<= 0`, salvo como `null`.

---

### BUG-053 · Quiz: question_type não validado
**✅ Correção (2026-03-20):** Whitelist `['multiple_choice','true_false','single_choice']` adicionada. Fallback para `multiple_choice`.

---

### BUG-054 · Quiz: correct_answer index não validado
**✅ Correção (2026-03-20):** `$correctIndex` validado: deve ser `>= 0` e existir em `$answers`. Redireciona com erro se inválido.

---

### BUG-055 · Quiz: questão pode ser criada sem resposta correta
**✅ Correção (2026-03-20):** `$hasCorrect` rastreado no loop. Se `false` ao final, redireciona com mensagem de erro.

---

### BUG-056 · Quiz: attempts_allowed = 0 causa loop infinito
**✅ Correção (2026-03-20):** Verificação alterada para `$quiz['attempts_allowed'] !== null && (int)$quiz['attempts_allowed'] > 0`.

---

### BUG-057 · QuizController: null check ausente em fetch()
**✅ Correção (2026-03-20):** `if (!$answer) { continue; }` adicionado antes de `$answer['is_correct']`.

---

### BUG-058 · VideoProgress::getOrCreate() sem null check
**✅ Correção (2026-03-20):** Null-fallback adicionado após `getOrCreate()`: se retornar `false/null`, atribui array default `['is_completed' => false, 'current_position' => 0, 'watch_duration' => 0]`.

---

### BUG-059 · Senha não verificada no update de usuário
**✅ Verificado (2026-03-20):** `User::update()` já chama `password_hash()` internamente. Nenhuma mudança necessária.

---

### BUG-060 · Email uniqueness com comparação frouxa — UserController
**✅ Correção (2026-03-20):** `$existingUser['id'] != $id` alterado para `(int)$existingUser['id'] !== (int)$id`.

---

## 🟡 MÉDIOS (BUG-061 a BUG-078)

### BUG-061 · birth_date NULL quebra DateTime
**✅ Correção (2026-03-20):** `DateTime::createFromFormat` com verificação de `false` adicionada em `StudentController`.

---

### BUG-062 · Schema misto em Observation (legado + eixos)
**✅ Correção (2026-03-20):** Docblock `@deprecated` adicionado acima do método `create()` legado documentando que `createWithAxes()` deve ser usado. Campos legados mantidos apenas para compatibilidade retroativa.

---

### BUG-063 · Status enums sem constantes
**✅ Correção (2026-03-20):** Criado `app/Core/Status.php` — classe `final` com constantes centralizadas: `IN_PROGRESS`, `FINALIZED`, `REVISION_REQUESTED`, `SUBMITTED`, `APPROVED`, `REJECTED`. Inclui arrays de validação `PEDAGOGICAL_STATUSES` e `PLANNING_STATUSES`.

---

### BUG-064 · Notificação não revertida se updateStatus() falha
**✅ Correção (2026-03-20):** `notifyAllCoordenadores()` agora é chamado apenas se `updateStatus()` retornar `true`. Falha na notificação é capturada via `try/catch(\Throwable)` e logada silenciosamente.

---

### BUG-065 · DescriptiveReport: lógica de compilação duplicada
**✅ Correção (2026-03-20):** Bloco duplicado extraído para método privado `compileObservationText(array $observation): string`. Chamado por `store()` e `recompile()`.

---

### BUG-066 · Portfolio: slice após merge perde índices
**✅ Correção (2026-03-20):** `array_values(array_slice($merged, 0, 6))` corrige o problema de índices após merge.

---

### BUG-067 · CoordinatorComment: NULL user_id se obs. deletada
**✅ Correção (2026-03-20):** Dois níveis de proteção implementados: (1) `CoordinatorFeedbackController::getContentOwnerId()` já retorna `null` quando o JOIN com `observations` falha, e `store()` ignora notificação silenciosamente quando `$teacherId === null`. (2) BUG-074 impede exclusão de observações vinculadas a pareceres, eliminando o cenário principal de NULL.

---

### BUG-068 · Enrollment: campos cached inconsistentes
**✅ Correção (2026-03-20):** `Enrollment::getByUser()` agora recalcula `overall_progress_percentage` a partir dos dados reais E persiste o valor de volta ao banco via UPDATE condicional (somente quando diverge). Cache mantido em sincronia lazy — sem custo quando já está correto.

---

### BUG-069 · PlanningPeriodRecord cria tabela no construtor
**✅ Correção (2026-03-20):** Flag estática `self::$tableChecked` adicionada. `createTable()` é chamado no máximo uma vez por request, eliminando a query redundante a cada instanciação.

---

### BUG-070 · CoordinatorComment sem FK constraint
**✅ Correção (2026-03-20):** FK polimórfica não é viável em MySQL. Solução aplicada: cascata a nível de aplicação — `CoordinatorComment::deleteByContent()` adicionado ao model, e chamado nos `delete()` de `ObservationController`, `PortfolioController` e `DescriptiveReportController`. Decisão arquitetural documentada em `migrations/040_coordinator_comments_note.sql`.

---

### BUG-071 · calendar() com mês inválido
**✅ Correção (2026-03-20):** `$month` clamped para 1–12 e `$year` para 2020–2030.

---

### BUG-072 · Image bank: getByFolder() sem check de propriedade
**✅ Correção (2026-03-20):** Em `folder()`, para usuários não-admin, adicionada verificação via JOIN `image_folders ↔ classrooms → school_id` contra o contexto de escola da sessão. Retorna 403 se sem permissão.

---

### BUG-073 · Exception genérica em correctText()
**✅ Correção (2026-03-20):** Separado em `\App\Services\GeminiException` (HTTP 503) e `\Exception` genérico (HTTP 500 + log). Mensagem de erro Gemini não vaza detalhes internos.

---

### BUG-074 · Obs. vinculada ao parecer pode ser deletada
**✅ Correção (2026-03-20):** Guard adicionado em `ObservationController::delete()` — consulta `SELECT id FROM descriptive_reports WHERE observation_id = ? LIMIT 1`. Se existir parecer vinculado, bloqueia exclusão com mensagem de erro e redireciona para `/admin/observations/{id}`.

---

### BUG-075 · findOrCreateDailyEntry() sem null check
**✅ Correção (2026-03-20):** Null check adicionado após chamada. Redireciona para `/admin/planning/{id}/days` com mensagem de erro.

---

### BUG-076 · Rate limiting ausente no login
**✅ Correção (2026-03-20):** Contador de tentativas via `$_SESSION['login_attempts']` e `$_SESSION['login_last_attempt']`. Bloqueia após 5 falhas em 15 minutos. Contador resetado no login bem-sucedido.

---

### BUG-077 · Senha com complexidade insuficiente
**✅ Correção (2026-03-20):** Mínimo aumentado para 8 caracteres + exige pelo menos 1 letra e 1 número.

---

### BUG-078 · $_SESSION['old_input'] expõe dados brutos
**✅ Correção (2026-03-20):** `$_SESSION['old_input'] = $_POST` substituído por array explícito com apenas `name`, `email`, `role`. Senha nunca é armazenada na sessão.

---

## 🔵 BAIXOS (BUG-079 a BUG-085)

### BUG-079 · Role inválido — centralizar constantes
**✅ Correção (2026-03-20):** `private const ALLOWED_ROLES` adicionado ao `UserController`. Ambas as verificações de role em `store()` e `update()` referenciam `self::ALLOWED_ROLES`.

### BUG-080 · unlink() sem error handling
**✅ Correção (2026-03-20):** Todos os `unlink()` em `StudentController`, `SchoolController` e `ImageBank::delete()` substituídos por `if (file_exists($path) && !unlink($path)) { error_log(...); }`.

### BUG-081 · Observation::create() legado não inicializa eixos
**✅ Correção (2026-03-20):** INSERT do método legado `create()` agora inclui todos os 6 campos de eixo com valor default `''`.

### BUG-082 · enrollment_date ausente no INSERT
**✅ Correção (2026-03-20):** `create()` em `Enrollment` agora inclui `enrollment_date` com default `date('Y-m-d H:i:s')` se não fornecido.

### BUG-083 · axis_photos: tipo indefinido
**✅ Correção (2026-03-20):** Confirmado e documentado — `axis_photos` é JSON (string no banco). `DescriptiveReportController` e `PortfolioController` usam `json_decode()/json_encode()` com guard `is_array()`. Campo deve ser declarado como `TEXT` ou `JSON` no schema com valor default `'[]'`.

### BUG-084 · planning_submission_answers sem model dedicado
**✅ Correção (2026-03-20):** Criado `app/Models/PlanningSubmissionAnswer.php` com 6 métodos: `getBySubmission()`, `getStatic()`, `getByDailyEntry()`, `upsertStatic()`, `upsertDaily()`, `deleteBySubmission()`. `PlanningSubmission` refatorado para delegar todas as queries de respostas ao novo model — assinaturas públicas preservadas.

### BUG-085 · Input trimming inconsistente
**✅ Correção (2026-03-20):** `role` agora recebe `trim()` consistente em `store()` e `update()` do `UserController`. Variável `$role` usada em validação, `$data` e `old_input`.

---

## Resumo das Correções Aplicadas

| Status | Quantidade |
|---|---|
| ✅ Corrigidos (Revisão 04) | 85 |
| ⚠️ Pendentes | 0 |

**Arquivos modificados nesta revisão (25 arquivos):**
- `app/Core/Security/Csrf.php` *(criado)*
- `app/Core/Status.php` *(criado)*
- `app/Controllers/AuthController.php`
- `app/Controllers/CourseController.php`
- `app/Controllers/Admin/CoordinatorFeedbackController.php`
- `app/Controllers/Admin/UserController.php`
- `app/Controllers/Admin/StudentController.php`
- `app/Controllers/Admin/SchoolController.php`
- `app/Controllers/Admin/ClassroomController.php`
- `app/Controllers/Admin/ObservationController.php`
- `app/Controllers/Admin/PlanningTemplateController.php`
- `app/Controllers/Admin/PlanningController.php`
- `app/Controllers/Admin/DescriptiveReportController.php`
- `app/Controllers/Admin/PortfolioController.php`
- `app/Controllers/Admin/ImageBankController.php`
- `app/Controllers/Admin/CourseAdminController.php`
- `app/Controllers/Admin/LessonAdminController.php`
- `app/Controllers/QuizController.php`
- `app/Controllers/Admin/QuizAdminController.php`
- `app/Models/Observation.php`
- `app/Models/Enrollment.php`
- `app/Models/PlanningPeriodRecord.php`
- `app/Models/ImageBank.php`

**3 pendências restantes (baixo risco):**
- BUG-063: Status enums sem constantes centralizadas
- BUG-067: CoordinatorComment — NULL user_id se obs. deletada (requer constraint ON DELETE)
- BUG-070: CoordinatorComment sem FK constraint (requer migration de schema)
- BUG-068: Enrollment campos cached (refatoração de model)
- BUG-074: Proteção de exclusão de observação vinculada a parecer
- BUG-083: Documentação formal do tipo JSON de axis_photos
- BUG-084: Model dedicado para planning_submission_answers

*Script de validação: `php tests/test_forms_completo.php`*
