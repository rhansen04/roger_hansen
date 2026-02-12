# Estrutura do CRUD de Escolas

Mapa completo da arquitetura implementada.

## Estrutura de Arquivos

```
SITE_NOVO/
│
├── app/
│   ├── Models/
│   │   └── School.php                    ← Model com métodos CRUD
│   │
│   └── Controllers/
│       └── Admin/
│           └── SchoolController.php      ← Controller com 7 métodos
│
├── views/
│   ├── layouts/
│   │   └── admin.php                     ← Layout base (sidebar + menu)
│   │
│   └── admin/
│       └── schools/
│           ├── index.php                 ← Lista de escolas
│           ├── show.php                  ← Detalhes da escola
│           ├── create.php                ← Formulário criar
│           ├── edit.php                  ← Formulário editar
│           └── _form.php                 ← Partial do formulário
│
├── public/
│   ├── index.php                         ← Rotas registradas
│   │
│   └── uploads/
│       └── schools/                      ← Logos das escolas
│           └── .gitkeep
│
├── migrations/
│   ├── add_school_fields.sql             ← Migration de atualização
│   └── seed_schools_demo.sql             ← Dados de exemplo
│
├── docs/
│   ├── CRUD_ESCOLAS.md                   ← Documentação completa
│   ├── QUICK_START_SCHOOLS.md            ← Guia rápido
│   ├── SCHOOLS_ROADMAP.md                ← Melhorias futuras
│   └── SCHOOLS_STRUCTURE.md              ← Este arquivo
│
└── tests/
    └── test_schools.md                   ← Checklist de testes
```

## Fluxo de Dados

```
┌──────────────┐
│   Browser    │
│ (Usuário)    │
└──────┬───────┘
       │
       │ HTTP Request
       ▼
┌─────────────────────────────────────────────────────┐
│                   public/index.php                   │
│  ┌────────────────────────────────────────────────┐ │
│  │           Router (Roteamento)                  │ │
│  │  - Identifica rota                             │ │
│  │  - Aplica middleware (autenticação)            │ │
│  │  - Direciona para controller                   │ │
│  └────────────┬───────────────────────────────────┘ │
└───────────────┼─────────────────────────────────────┘
                │
                ▼
┌─────────────────────────────────────────────────────┐
│        app/Controllers/Admin/SchoolController.php    │
│  ┌────────────────────────────────────────────────┐ │
│  │  Métodos:                                      │ │
│  │  • index()     → Lista escolas                 │ │
│  │  • show($id)   → Detalhes escola               │ │
│  │  • create()    → Form criar                    │ │
│  │  • store()     → Salvar nova                   │ │
│  │  • edit($id)   → Form editar                   │ │
│  │  • update($id) → Atualizar                     │ │
│  │  • delete($id) → Deletar                       │ │
│  └────────────┬───────────────────────────────────┘ │
└───────────────┼─────────────────────────────────────┘
                │
                ▼
┌─────────────────────────────────────────────────────┐
│            app/Models/School.php                     │
│  ┌────────────────────────────────────────────────┐ │
│  │  Métodos:                                      │ │
│  │  • all()                                       │ │
│  │  • allWithStudentsCount()                      │ │
│  │  • find($id)                                   │ │
│  │  • findWithStudentsCount($id)                  │ │
│  │  • getStudents($schoolId)                      │ │
│  │  • create($data)                               │ │
│  │  • update($id, $data)                          │ │
│  │  • delete($id)                                 │ │
│  │  • countActive()                               │ │
│  └────────────┬───────────────────────────────────┘ │
└───────────────┼─────────────────────────────────────┘
                │
                ▼
┌─────────────────────────────────────────────────────┐
│           MySQL Database (hansen_educacional)        │
│  ┌────────────────────────────────────────────────┐ │
│  │  Tabela: schools                               │ │
│  │  - id (PK)                                     │ │
│  │  - name                                        │ │
│  │  - city, state, address                        │ │
│  │  - contact_person, phone, email                │ │
│  │  - contract_start_date, contract_end_date      │ │
│  │  - logo_url, status                            │ │
│  │  - created_at, updated_at                      │ │
│  └────────────┬───────────────────────────────────┘ │
└───────────────┼─────────────────────────────────────┘
                │
                │ JOIN com students
                │
┌───────────────┼─────────────────────────────────────┐
│  ┌────────────▼───────────────────────────────────┐ │
│  │  Tabela: students                              │ │
│  │  - id (PK)                                     │ │
│  │  - name                                        │ │
│  │  - birth_date                                  │ │
│  │  - school_id (FK) → schools.id                 │ │
│  │  - photo_url                                   │ │
│  │  - created_at                                  │ │
│  └────────────┬───────────────────────────────────┘ │
└───────────────┼─────────────────────────────────────┘
                │
                │ Resposta
                ▼
┌─────────────────────────────────────────────────────┐
│              views/admin/schools/*.php               │
│  ┌────────────────────────────────────────────────┐ │
│  │  • index.php   → Renderiza lista               │ │
│  │  • show.php    → Renderiza detalhes            │ │
│  │  • create.php  → Renderiza form criar          │ │
│  │  • edit.php    → Renderiza form editar         │ │
│  │  • _form.php   → Partial reutilizável          │ │
│  └────────────┬───────────────────────────────────┘ │
└───────────────┼─────────────────────────────────────┘
                │
                ▼
┌─────────────────────────────────────────────────────┐
│             views/layouts/admin.php                  │
│  ┌────────────────────────────────────────────────┐ │
│  │  • Sidebar com menu                            │ │
│  │  • Topbar com usuário logado                   │ │
│  │  • Área de conteúdo ($content)                 │ │
│  │  • Bootstrap 5 + Font Awesome                  │ │
│  └────────────┬───────────────────────────────────┘ │
└───────────────┼─────────────────────────────────────┘
                │
                │ HTML Response
                ▼
        ┌──────────────┐
        │   Browser    │
        │ (Renderizado)│
        └──────────────┘
```

## Rotas e Endpoints

| Método | Rota | Controller@Método | Descrição |
|--------|------|-------------------|-----------|
| GET | `/admin/schools` | SchoolController@index | Lista todas as escolas |
| GET | `/admin/schools/create` | SchoolController@create | Formulário de criação |
| POST | `/admin/schools` | SchoolController@store | Salva nova escola |
| GET | `/admin/schools/{id}` | SchoolController@show | Detalhes da escola |
| GET | `/admin/schools/{id}/edit` | SchoolController@edit | Formulário de edição |
| POST | `/admin/schools/{id}/update` | SchoolController@update | Atualiza escola |
| POST | `/admin/schools/{id}/delete` | SchoolController@delete | Deleta escola |

## Relacionamentos no Banco

```
┌────────────────────┐
│     schools        │
│────────────────────│
│ • id (PK)          │─┐
│ • name             │ │
│ • city             │ │
│ • state            │ │
│ • address          │ │
│ • contact_person   │ │
│ • phone            │ │
│ • email            │ │
│ • contract_*       │ │
│ • logo_url         │ │
│ • status           │ │
│ • timestamps       │ │
└────────────────────┘ │
                       │ 1:N
                       │
                       ▼
┌────────────────────┐
│     students       │
│────────────────────│
│ • id (PK)          │
│ • name             │
│ • birth_date       │
│ • school_id (FK)   │◄─┘
│ • photo_url        │
│ • created_at       │
└────────────────────┘
```

## Diagrama de Classes (Simplificado)

```
┌─────────────────────────────────┐
│         SchoolController         │
├─────────────────────────────────┤
│ - render($view, $data)          │
├─────────────────────────────────┤
│ + index()                       │
│ + show($id)                     │
│ + create()                      │
│ + store()                       │
│ + edit($id)                     │
│ + update($id)                   │
│ + delete($id)                   │
└───────────┬─────────────────────┘
            │ usa
            ▼
┌─────────────────────────────────┐
│            School                │
├─────────────────────────────────┤
│ - $db                           │
├─────────────────────────────────┤
│ + all()                         │
│ + allWithStudentsCount()        │
│ + find($id)                     │
│ + findWithStudentsCount($id)    │
│ + getStudents($schoolId)        │
│ + create($data)                 │
│ + update($id, $data)            │
│ + delete($id)                   │
│ + countActive()                 │
└───────────┬─────────────────────┘
            │ usa
            ▼
┌─────────────────────────────────┐
│         Connection               │
├─────────────────────────────────┤
│ - static $instance              │
├─────────────────────────────────┤
│ + static getInstance() : PDO    │
└─────────────────────────────────┘
```

## Ciclo de Vida de uma Requisição

### Exemplo: Listar Escolas

```
1. Usuário acessa: /admin/schools

2. public/index.php
   ├─ session_start()
   ├─ autoload classes
   └─ Router::dispatch()

3. Middleware (AuthMiddleware)
   ├─ Verifica se usuário está logado
   └─ Se não, redireciona para /login

4. Router encontra rota
   └─ SchoolController@index

5. SchoolController::index()
   ├─ $schoolModel = new School()
   ├─ $schools = $schoolModel->allWithStudentsCount()
   └─ return $this->render('schools/index', ['schools' => $schools])

6. School::allWithStudentsCount()
   ├─ $db = Connection::getInstance()
   ├─ SQL: SELECT schools.*, COUNT(students.id) ...
   │       FROM schools
   │       LEFT JOIN students ON ...
   │       GROUP BY schools.id
   └─ return $stmt->fetchAll()

7. SchoolController::render()
   ├─ extract(['schools' => $schools])
   ├─ ob_start()
   ├─ include views/admin/schools/index.php
   ├─ $content = ob_get_clean()
   └─ include views/layouts/admin.php

8. views/layouts/admin.php
   ├─ HTML head (Bootstrap, CSS)
   ├─ Sidebar com menu
   ├─ Topbar com usuário
   ├─ <?php echo $content; ?>  ← schools/index.php
   └─ Scripts (Bootstrap JS)

9. Browser recebe HTML completo
   └─ Renderiza página
```

## Padrões de Design Utilizados

### MVC (Model-View-Controller)
- **Model**: `School.php` - Lógica de negócio e acesso ao banco
- **View**: `views/admin/schools/*.php` - Apresentação
- **Controller**: `SchoolController.php` - Lógica de aplicação

### Repository Pattern
- Model abstrai acesso ao banco de dados
- Controller não conhece SQL
- Facilita testes e manutenção

### Singleton
- `Connection::getInstance()` - Uma única instância PDO

### Front Controller
- `public/index.php` - Ponto único de entrada
- Router direciona todas as requisições

### Template Method
- `_form.php` - Partial reutilizado em create e edit

## Segurança Implementada

### Autenticação
```php
// Middleware aplicado em todas as rotas /admin/*
if (strpos($_SERVER['REQUEST_URI'], '/admin/') === 0) {
    \App\Middleware\AuthMiddleware::handle();
}
```

### Validação de Input
```php
// Sanitização de HTML
htmlspecialchars($school['name'])

// Validação de campos obrigatórios
if (empty($_POST['name'])) { ... }

// Validação de upload
$allowedExts = ['jpg', 'jpeg', 'png', 'gif'];
if (in_array(strtolower($ext), $allowedExts)) { ... }
```

### SQL Injection Prevention
```php
// Prepared Statements em todos os métodos
$stmt = $this->db->prepare("SELECT * FROM schools WHERE id = ?");
$stmt->execute([$id]);
```

### CSRF Protection
- Sessões PHP para armazenar estado do usuário
- POST methods para operações destrutivas

## Performance

### Otimizações
- Índices no banco: `status`, `city`, `state`
- JOIN otimizado para contagem de alunos
- Consultas preparadas (prepared statements)
- Cache de sessão PHP

### Query Otimizada
```sql
-- Ao invés de N+1 queries, uma única query com JOIN
SELECT s.*, COUNT(st.id) as students_count
FROM schools s
LEFT JOIN students st ON s.id = st.school_id
GROUP BY s.id
ORDER BY s.name ASC
```

## Convenções de Código

### Nomenclatura
- **Classes**: PascalCase (`SchoolController`)
- **Métodos**: camelCase (`allWithStudentsCount()`)
- **Variáveis**: snake_case (`$students_count`)
- **Arquivos**: lowercase (`index.php`)
- **Partials**: underscore prefix (`_form.php`)

### Estrutura de Métodos
1. Validação de entrada
2. Busca de dados no Model
3. Verificações de negócio
4. Operação principal
5. Feedback (mensagem de sessão)
6. Redirecionamento

### Padrão de Retorno
```php
// Métodos de listagem
return $this->render('view', $data);

// Métodos de ação (store, update, delete)
$_SESSION['success_message'] = 'Mensagem';
header('Location: /admin/schools');
exit;
```

---

**Este documento deve ser atualizado sempre que houver mudanças significativas na estrutura.**

**Última atualização:** 10/02/2025
