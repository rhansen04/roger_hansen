# Revisao 06 - Ajustes Plataforma Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Corrigir 5 ajustes reportados na Revisao 06 da plataforma Hansen Educacional: listagem de observacoes por aluno, toggle PCA, compilacao automatica do parecer descritivo, salvamento parcial de eixos, e limpeza do modulo planejamento.

**Architecture:** PHP 8.2 MVC customizado sem framework. Cada ajuste modifica controller + model + view. Todas as mudancas sao backward-compatible e nao requerem migracao de banco (campos ja existem).

**Tech Stack:** PHP 8.2, MySQL 8.0, Bootstrap 5.3, JavaScript vanilla

---

## File Structure

| File | Responsibility | Tasks |
|------|---------------|-------|
| `app/Controllers/Admin/ObservationController.php` | Controller de observacoes | T1, T4 |
| `app/Models/Observation.php` | Model de observacoes | T1 |
| `views/admin/observations/index.php` | Listagem de observacoes | T1 |
| `views/admin/observations/create.php` | Formulario de criacao | T2, T4 |
| `views/admin/observations/edit.php` | Formulario de edicao | T2 |
| `views/admin/observations/_questions.php` | Perguntas dos eixos | T4 |
| `views/admin/students/show.php` | Detalhes do aluno | T3B |
| `app/Controllers/Admin/DescriptiveReportController.php` | Controller parecer | T3A |
| `views/admin/descriptive-reports/create.php` | Form criar parecer | T3A |
| `views/admin/planning/form.php` | Form planejamento | T5 |
| `views/admin/planning/index.php` | Listagem planejamento | T5 |

---

### Task 1: Listagem de Observacoes por Aluno (sem duplicacao)

**Files:**
- Modify: `app/Models/Observation.php` — novo metodo `allGroupedByStudent()`
- Modify: `app/Controllers/Admin/ObservationController.php:17-53` — refatorar `index()`
- Modify: `views/admin/observations/index.php:150-240` — nova estrutura de tabela

- [ ] **Step 1: Criar metodo `allGroupedByStudent()` no Model**

No arquivo `app/Models/Observation.php`, adicionar metodo que agrupa por aluno:

```php
public function allGroupedByStudent($filters = [], $userId = null, $roleRestrict = false)
{
    try {
        $where = [];
        $params = [];

        if ($roleRestrict && $userId) {
            $where[] = "o.user_id = ?";
            $params[] = $userId;
        }
        if (!empty($filters['student_id'])) {
            $where[] = "o.student_id = ?";
            $params[] = $filters['student_id'];
        }
        if (!empty($filters['semester'])) {
            $where[] = "o.semester = ?";
            $params[] = $filters['semester'];
        }
        if (!empty($filters['year'])) {
            $where[] = "o.year = ?";
            $params[] = $filters['year'];
        }
        if (!empty($filters['status'])) {
            $where[] = "o.status = ?";
            $params[] = $filters['status'];
        }

        $whereClause = !empty($where) ? " WHERE " . implode(" AND ", $where) : "";

        $sql = "SELECT
                    s.id as student_id,
                    s.name as student_name,
                    MAX(o.semester) as semester,
                    MAX(o.year) as year,
                    COUNT(o.id) as observation_count,
                    MAX(o.updated_at) as last_updated,
                    CASE
                        WHEN SUM(CASE WHEN o.status = 'in_progress' THEN 1 ELSE 0 END) > 0
                        THEN 'in_progress'
                        ELSE 'finalized'
                    END as aggregated_status
                FROM observations o
                LEFT JOIN users u ON o.user_id = u.id
                LEFT JOIN students s ON o.student_id = s.id
                {$whereClause}
                GROUP BY s.id, s.name
                ORDER BY s.name ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    } catch (\PDOException $e) {
        error_log("Erro ao listar observacoes agrupadas: " . $e->getMessage());
        return [];
    }
}
```

- [ ] **Step 2: Atualizar `index()` no Controller**

No `ObservationController.php`, alterar o metodo `index()` para usar o novo metodo:

Substituir a linha:
```php
$observations = $obsModel->allFiltered($filters, $userId, $roleRestrict);
```
Por:
```php
$studentRows = $obsModel->allGroupedByStudent($filters, $userId, $roleRestrict);
```

E alterar o `render()` para passar `studentRows` em vez de `observations`:
```php
return $this->render('observations/index', [
    'studentRows' => $studentRows,
    'students' => $students,
    'filters' => $filters,
    'years' => $years,
    'currentYear' => $currentYear,
    'userRole' => $userRole,
]);
```

- [ ] **Step 3: Reescrever tabela na view `index.php`**

Substituir o bloco da tabela (linhas 150-240) pela nova estrutura:

**Cabecalho:**
```html
<tr>
    <th class="ps-4 py-3" style="width:5%">No</th>
    <th class="py-3">Aluno</th>
    <th class="py-3">Semestre / Ano</th>
    <th class="py-3 text-center">No Observacoes</th>
    <th class="py-3 text-center">Status</th>
    <th class="py-3">Atualizado em</th>
    <th class="py-3 text-center">Acoes</th>
</tr>
```

**Corpo (usar `$studentRows` e numeracao sequencial `$idx + 1`):**
- Coluna No: `$idx + 1`
- Coluna Aluno: link para `/admin/students/{student_id}`
- Coluna Semestre/Ano: badge com `Xo Sem / YYYY`
- Coluna No Observacoes: badge com `observation_count`
- Coluna Status: badge verde "Finalizado" ou amarelo "Em andamento"
- Coluna Atualizado em: data formatada
- Coluna Acoes: botoes Visualizar (`/admin/observations?student_id=X`) e Editar (`/admin/observations/create?student_id=X`)

**Remover:** coluna Professor (nao deve aparecer)

- [ ] **Step 4: Testar manualmente e fazer commit**

Verificar:
- Aluno com multiplas observacoes aparece apenas 1x
- Contagem correta
- Status agregado correto
- Filtros funcionando

```bash
git add app/Models/Observation.php app/Controllers/Admin/ObservationController.php views/admin/observations/index.php
git commit -m "fix: listagem observacoes agrupa por aluno sem duplicacao (Revisao 06 - Ajuste 1)"
```

---

### Task 2: Toggle PCA por Escola (Ajuste 2)

**Files:**
- Modify: `views/admin/observations/create.php:174-184` — remover `required` dos campos PCA
- Modify: `views/admin/observations/edit.php` — mesma alteracao

O toggle PCA ja esta implementado:
- `School.pca_enabled` ja existe no banco
- `ObservationController.buildPcaEnabledByStudent()` ja consulta
- `create.php` ja oculta a aba PCA quando desabilitado (linhas 252-287)
- `store()` ja ignora PCA quando desabilitado (linha 140)

**Verificar:** O comportamento ja esta correto. A aba PCA ja e ocultada quando `pca_enabled = 0` na escola. O campo ja nao e required para PCA. Nao ha validacao server-side exigindo PCA.

- [ ] **Step 1: Verificar que o toggle PCA funciona corretamente**

Ler `views/admin/schools/edit.php` para confirmar que o toggle existe na UI.
Ler `app/Models/School.php` para confirmar `isPcaEnabled()`.

Se tudo estiver funcionando, marcar como completo sem alteracoes.

- [ ] **Step 2: Commit (apenas se houver alteracoes)**

```bash
git add -A && git commit -m "fix: verificar toggle PCA por escola (Revisao 06 - Ajuste 2)"
```

---

### Task 3A: Parecer Descritivo - Compilacao Automatica (Ajuste 3)

**Files:**
- Modify: `app/Controllers/Admin/DescriptiveReportController.php:100-167` — metodo `store()`
- Modify: `views/admin/descriptive-reports/create.php` — exibir compilacao

O `store()` ja faz compilacao automatica:
- Linha 116: `$studentText = $obsModel->compileSemesterText($studentId, $semester, $year);`
- O `syncDescriptiveReportsForObservationContext()` no ObservationController ja atualiza pareceres quando observacoes mudam.

**Problema provavel:** O campo `student_text` no parecer pode nao estar sendo exibido na tela de criacao. Verificar a view `create.php`.

- [ ] **Step 1: Verificar e corrigir a view `create.php` do parecer**

Ler `views/admin/descriptive-reports/create.php` e verificar se a compilacao das observacoes e exibida antes de clicar "Gerar Parecer".

Se nao estiver exibindo, adicionar preview das observacoes compiladas via AJAX quando o aluno/semestre/ano forem selecionados.

- [ ] **Step 2: Garantir `recompile()` funciona**

Verificar que o metodo `recompile()` no controller recompila o texto a partir das observacoes atualizadas.

- [ ] **Step 3: Commit**

```bash
git add app/Controllers/Admin/DescriptiveReportController.php views/admin/descriptive-reports/create.php
git commit -m "fix: compilacao automatica do parecer descritivo (Revisao 06 - Ajuste 3A)"
```

---

### Task 3B: Botao Editar na Tela de Detalhes do Aluno (Ajuste 3)

**Files:**
- Modify: `views/admin/students/show.php:242-246` — adicionar botao Editar

- [ ] **Step 1: Adicionar botao Editar na coluna Acoes**

No arquivo `views/admin/students/show.php`, localizar a coluna de acoes (linha 242-246):

```php
<td class="text-center">
    <a href="/admin/observations/<?php echo $obs['id']; ?>" class="btn btn-sm btn-outline-primary" title="Ver Detalhes">
        <i class="fas fa-eye"></i>
    </a>
</td>
```

Substituir por:

```php
<td class="text-center">
    <div class="btn-group">
        <a href="/admin/observations/<?php echo $obs['id']; ?>" class="btn btn-sm btn-outline-primary" title="Ver Detalhes">
            <i class="fas fa-eye"></i>
        </a>
        <?php if (($obs['status'] ?? 'in_progress') !== 'finalized'): ?>
            <a href="/admin/observations/<?php echo $obs['id']; ?>/edit" class="btn btn-sm btn-outline-secondary" title="Editar">
                <i class="fas fa-edit"></i>
            </a>
        <?php endif; ?>
    </div>
</td>
```

- [ ] **Step 2: Commit**

```bash
git add views/admin/students/show.php
git commit -m "fix: adicionar botao editar observacao na tela de detalhes do aluno (Revisao 06 - Ajuste 3B)"
```

---

### Task 4: Salvamento Parcial de Observacoes (Ajuste 4)

**Files:**
- Modify: `views/admin/observations/create.php:174-184` — remover `required` dos textareas
- Modify: `views/admin/observations/create.php:201` — renomear botao
- Modify: `views/admin/observations/create.php:240-249` — remover validacao JS obrigatoria
- Modify: `views/admin/observations/edit.php` — mesmas alteracoes

- [ ] **Step 1: Remover `required` dos textareas na `create.php`**

Na view `create.php`, linha 184, alterar:
```php
<?php echo ($axisData['field'] !== 'axis_pca') ? 'required' : ''; ?>
```
Para: (vazio — nenhum campo required)
```php

```

Na linha 175, remover o asterisco obrigatorio:
```php
<?php if ($axisData['field'] !== 'axis_pca'): ?><span class="text-danger">*</span><?php endif; ?>
```
Substituir por: (nada — remover a linha inteira do asterisco)

- [ ] **Step 2: Renomear botao de "CRIAR OBSERVACAO" para "SALVAR OBSERVACAO"**

Na `create.php`, linha 201-203:
```php
<button type="submit" class="btn btn-hansen px-5">
    <i class="fas fa-save me-2"></i> CRIAR OBSERVACAO
</button>
```
Alterar para:
```php
<button type="submit" class="btn btn-hansen px-5">
    <i class="fas fa-save me-2"></i> SALVAR OBSERVACAO
</button>
```

- [ ] **Step 3: Remover validacao JS obrigatoria**

Na `create.php`, na funcao `isFieldRequired()` (linhas 240-249), alterar para:
```javascript
function isFieldRequired(fieldName) {
    return false;
}
```

Remover tambem a indicacao `* Campos obrigatorios` (linhas 194-198):
```html
<div>
    <span class="text-danger">*</span>
    <small class="text-muted">Campos obrigatorios</small>
</div>
```
Substituir por:
```html
<div></div>
```

- [ ] **Step 4: Aplicar mesmas alteracoes no `edit.php`**

Verificar `views/admin/observations/edit.php` e aplicar as mesmas mudancas:
- Remover `required` dos textareas
- Renomear botao para "SALVAR OBSERVACAO"
- Remover validacao obrigatoria JS

- [ ] **Step 5: Remover validacao server-side de eixos (se houver)**

No `ObservationController.php`, metodo `store()` — verificar se ha validacao que exige preenchimento dos eixos. Atualmente o controller apenas verifica `student_id`, `semester` e `year` (linha 110), entao nao ha bloqueio server-side para eixos vazios. OK.

- [ ] **Step 6: Commit**

```bash
git add views/admin/observations/create.php views/admin/observations/edit.php
git commit -m "fix: permitir salvamento parcial de eixos nas observacoes (Revisao 06 - Ajuste 4)"
```

---

### Task 5: Menu Planejamento - Remover Template e Corrigir Datas (Ajuste 5)

**Files:**
- Modify: `views/admin/planning/form.php:20-69` — remover campo Template, reorganizar colunas
- Modify: `app/Controllers/Admin/PlanningController.php:70-78` — tornar template_id opcional
- Modify: `views/admin/planning/index.php` — reorganizar colunas da listagem

- [ ] **Step 1: Remover campo Template da view `form.php`**

No arquivo `views/admin/planning/form.php`, remover o bloco do Template (linhas 27-35):
```php
<div class="col-md-4 mb-3">
    <label class="form-label fw-bold">Template <span class="text-danger">*</span></label>
    <select name="template_id" class="form-select" required id="templateSelect">
        <option value="">Selecione...</option>
        <?php foreach ($templates as $t): ?>
            <option value="<?= $t['id'] ?>"><?= htmlspecialchars($t['title']) ?></option>
        <?php endforeach; ?>
    </select>
</div>
```

E reorganizar as colunas restantes (Turma, Inicio, Fim) no layout col-md-4, col-md-4, col-md-4.

Tambem garantir que os campos de data usam `type="date"` (ja usam na linha 47-51). Formato DD/MM/AAAA sera exibido pelo browser baseado no locale.

- [ ] **Step 2: Tornar template_id opcional no Controller**

No `PlanningController.php`, metodo `store()`, linha 74:
```php
if (empty($_POST['template_id']) || empty($_POST['classroom_id']) || ...
```
Remover `empty($_POST['template_id'])` da validacao. Usar um template padrao ou null:
```php
if (empty($_POST['classroom_id']) || empty($_POST['period_start']) || empty($_POST['period_end'])) {
```

No `$data` (linha 91-97), usar template_id opcional:
```php
'template_id' => $_POST['template_id'] ?? null,
```

- [ ] **Step 3: Reorganizar colunas da listagem `index.php`**

Verificar `views/admin/planning/index.php` e reorganizar as colunas na ordem:
1. No (numeracao sequencial)
2. Turma
3. Inicio (DD/MM/AAAA)
4. Fim (DD/MM/AAAA)
5. Status
6. Acoes

Remover coluna Template se existir.

- [ ] **Step 4: Commit**

```bash
git add views/admin/planning/form.php app/Controllers/Admin/PlanningController.php views/admin/planning/index.php
git commit -m "fix: remover template e corrigir datas no modulo planejamento (Revisao 06 - Ajuste 5)"
```

---

## Resumo de Commits

| Commit | Ajuste | Descricao |
|--------|--------|-----------|
| 1 | Ajuste 1 | Listagem observacoes agrupa por aluno |
| 2 | Ajuste 2 | Verificar toggle PCA (ja implementado) |
| 3 | Ajuste 3A | Compilacao automatica parecer descritivo |
| 4 | Ajuste 3B | Botao editar na tela detalhes do aluno |
| 5 | Ajuste 4 | Salvamento parcial de eixos |
| 6 | Ajuste 5 | Remover template e corrigir datas planejamento |
