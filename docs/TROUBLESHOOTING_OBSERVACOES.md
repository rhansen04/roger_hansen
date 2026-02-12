# Troubleshooting - Sistema de Observações Pedagógicas

## Problemas Comuns e Soluções

---

### 1. Erro 404 ao acessar /admin/observations

**Sintoma:**
```
404 Not Found
```

**Possíveis Causas:**
1. Router não atualizado
2. Rotas não configuradas
3. .htaccess não configurado corretamente

**Solução:**
```php
// Verificar se as rotas estão em public/index.php:
$router->get('/admin/observations', [AdminObservationController::class, 'index']);

// Verificar se o Router.php suporta parâmetros {id}
// Ver app/Core/Router/Router.php - deve ter regex pattern matching
```

---

### 2. Página em Branco ao criar observação

**Sintoma:**
Tela branca após clicar em "Nova Observação"

**Possíveis Causas:**
1. View não encontrada
2. Erro de PHP não exibido
3. Problema no método create()

**Solução:**
```php
// 1. Verificar se existe: views/admin/observations/create.php

// 2. Habilitar exibição de erros em public/index.php:
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 3. Verificar logs de erro do PHP
```

---

### 3. Erro ao salvar observação

**Sintoma:**
```
Erro ao criar observação. Tente novamente.
```

**Possíveis Causas:**
1. Campos obrigatórios vazios
2. Erro de conexão com banco
3. Problema na query SQL

**Solução:**
```php
// Verificar no ObservationController.php se:
- $_POST['student_id'] não está vazio
- $_POST['type'] não está vazio
- $_POST['content'] não está vazio
- $_SESSION['user_id'] está definido

// Verificar logs:
// Observation.php registra erros com error_log()
// Verificar em: /var/log/php/error.log ou similar
```

---

### 4. Dropdown de alunos vazio

**Sintoma:**
Select de alunos não mostra nenhum aluno

**Possíveis Causas:**
1. Nenhum aluno cadastrado no banco
2. Erro na query do Model Student
3. Problema ao passar dados para a view

**Solução:**
```php
// 1. Verificar se há alunos cadastrados:
SELECT * FROM students;

// 2. Verificar se StudentController passa $students para view:
return $this->render('observations/create', [
    'students' => $students
]);

// 3. Verificar se view está iterando corretamente:
<?php foreach ($students as $student): ?>
```

---

### 5. Filtros não funcionam

**Sintoma:**
Ao filtrar por categoria ou data, nada acontece

**Possíveis Causas:**
1. Parâmetros GET não sendo lidos
2. Lógica de filtro no Controller incorreta
3. Form não está com method="GET"

**Solução:**
```php
// Verificar no ObservationController.php:
$filters = [
    'student_id' => $_GET['student_id'] ?? null,
    'type' => $_GET['type'] ?? null,
    'date_from' => $_GET['date_from'] ?? null,
    'date_to' => $_GET['date_to'] ?? null
];

// Verificar se o form tem method="GET":
<form method="GET" action="/admin/observations">
```

---

### 6. Erro ao editar observação

**Sintoma:**
```
Observação não encontrada.
```

**Possíveis Causas:**
1. ID inválido na URL
2. Observação foi deletada
3. Router não capturando {id} corretamente

**Solução:**
```php
// 1. Verificar URL: /admin/observations/1/edit

// 2. Verificar se Router captura {id}:
// app/Core/Router/Router.php deve ter:
$pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $route['path']);

// 3. Verificar se método edit($id) recebe parâmetro:
public function edit($id) {
    $observation = $obsModel->find($id);
    if (!$observation) {
        // Mensagem de erro
    }
}
```

---

### 7. Confirmação de delete não aparece

**Sintoma:**
Ao clicar em deletar, nada acontece

**Possíveis Causas:**
1. JavaScript não carregado
2. Erro no console do navegador
3. Função confirmDelete() não definida

**Solução:**
```javascript
// Verificar se existe no final da view:
<script>
function confirmDelete(id, studentName) {
    if (confirm(`Tem certeza que deseja excluir...`)) {
        const form = document.getElementById('deleteForm');
        form.action = `/admin/observations/${id}/delete`;
        form.submit();
    }
}
</script>

// Verificar console do navegador (F12) para erros
```

---

### 8. Badges de categoria não aparecem coloridos

**Sintoma:**
Badges aparecem todos com a mesma cor

**Possíveis Causas:**
1. Array de cores não definido
2. Valor de $obs['type'] não corresponde ao esperado
3. CSS do Bootstrap não carregado

**Solução:**
```php
// Verificar no código da view:
$badgeColors = [
    'Comportamento' => 'primary',
    'Aprendizado' => 'success',
    'Saúde' => 'danger',
    'Comunicação com Pais' => 'warning',
    'Geral' => 'secondary'
];
$color = $badgeColors[$obs['type']] ?? 'secondary';

// Verificar se Bootstrap está carregado:
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
```

---

### 9. Sessão expira rapidamente

**Sintoma:**
Usuário é deslogado ao acessar observações

**Possíveis Causas:**
1. Middleware de autenticação muito restritivo
2. Sessão não iniciada
3. Timeout de sessão muito curto

**Solução:**
```php
// Verificar em public/index.php:
session_start();

// Verificar se middleware verifica sessão:
if (strpos($_SERVER['REQUEST_URI'], '/admin/') === 0) {
    \App\Middleware\AuthMiddleware::handle();
}

// Aumentar tempo de sessão (se necessário):
ini_set('session.gc_maxlifetime', 3600); // 1 hora
```

---

### 10. Layout quebrado / CSS não carrega

**Sintoma:**
Página aparece sem estilização

**Possíveis Causas:**
1. Bootstrap não carregado
2. Font Awesome não carregado
3. CSS customizado com erro

**Solução:**
```html
<!-- Verificar no layout admin.php: -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<!-- Verificar se há erro 404 nas requests (F12 -> Network) -->
```

---

## Verificações Gerais

### Checklist de Diagnóstico

```bash
# 1. Verificar se arquivos existem:
ls views/admin/observations/index.php
ls views/admin/observations/create.php
ls views/admin/observations/show.php
ls views/admin/observations/edit.php

# 2. Verificar permissões de escrita no banco:
ls -l storage/hansen_educacional.db

# 3. Verificar estrutura da tabela:
sqlite3 storage/hansen_educacional.db ".schema observations"

# 4. Verificar se há observações no banco:
sqlite3 storage/hansen_educacional.db "SELECT COUNT(*) FROM observations;"
```

### Logs Importantes

```bash
# Logs de erro do PHP:
tail -f /var/log/php/error.log

# Logs do Apache:
tail -f /var/log/apache2/error.log

# Logs do servidor web:
tail -f /var/log/nginx/error.log
```

---

## Testes de Funcionalidade

### Teste 1: Criar Observação
```
1. Login no sistema
2. Acessar /admin/observations/create
3. Selecionar aluno
4. Escolher categoria "Comportamento"
5. Preencher descrição com mínimo 10 caracteres
6. Clicar em "SALVAR"
7. Verificar mensagem de sucesso
8. Verificar se aparece na listagem
```

### Teste 2: Filtrar Observações
```
1. Acessar /admin/observations
2. Selecionar um aluno no filtro
3. Clicar em "Filtrar"
4. Verificar se mostra apenas observações do aluno
5. Clicar em "Limpar"
6. Verificar se mostra todas novamente
```

### Teste 3: Editar Observação
```
1. Na listagem, clicar em editar uma observação
2. Modificar a descrição
3. Clicar em "SALVAR ALTERAÇÕES"
4. Verificar mensagem de sucesso
5. Ver detalhes e confirmar mudança
```

### Teste 4: Deletar Observação
```
1. Na listagem, clicar em deletar
2. Confirmar no alerta
3. Verificar mensagem de sucesso
4. Confirmar que não aparece mais na listagem
```

---

## Contatos de Suporte

**Documentação:**
- `docs/CRUD_OBSERVACOES.md` - Documentação técnica completa
- `README_OBSERVACOES.md` - Guia de uso rápido

**Código Fonte:**
- `app/Controllers/Admin/ObservationController.php`
- `app/Models/Observation.php`
- `views/admin/observations/`

---

## Versão

**Implementado em:** 10/02/2026
**Versão do Sistema:** 1.0
**Status:** Produção
