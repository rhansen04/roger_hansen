# CRUD Completo de Escolas

Sistema completo de gerenciamento de escolas implementado para o Hansen Educacional.

## Estrutura Implementada

### 1. Model (app/Models/School.php)
**Métodos disponíveis:**
- `all()` - Listar todas as escolas
- `allWithStudentsCount()` - Listar escolas com contagem de alunos
- `find($id)` - Buscar escola por ID
- `findWithStudentsCount($id)` - Buscar escola com contagem de alunos
- `getStudents($schoolId)` - Listar alunos de uma escola
- `create($data)` - Criar nova escola
- `update($id, $data)` - Atualizar escola
- `delete($id)` - Deletar escola
- `countActive()` - Contar escolas ativas

### 2. Controller (app/Controllers/Admin/SchoolController.php)
**Rotas implementadas:**
- `GET /admin/schools` - Lista de escolas com contagem de alunos
- `GET /admin/schools/create` - Formulário de criação
- `POST /admin/schools` - Salvar nova escola
- `GET /admin/schools/{id}` - Ver detalhes + lista de alunos vinculados
- `GET /admin/schools/{id}/edit` - Formulário de edição
- `POST /admin/schools/{id}/update` - Atualizar escola
- `POST /admin/schools/{id}/delete` - Deletar escola

### 3. Views (views/admin/schools/)
- **index.php** - Tabela com lista de escolas
  - Logo, nome, localização, contato, telefone, email
  - Badge de status (ativo/inativo)
  - Badge com contagem de alunos
  - Botões: Ver, Editar, Deletar

- **show.php** - Detalhes completos da escola
  - Informações gerais (nome, cidade, estado, endereço)
  - Informações de contato (pessoa, telefone, email)
  - Informações do contrato (datas início/fim, status)
  - Logo da escola
  - Lista de alunos vinculados

- **create.php** - Formulário de criação
- **edit.php** - Formulário de edição
- **_form.php** - Partial com campos reutilizáveis

## Campos da Tabela Schools

| Campo | Tipo | Obrigatório | Descrição |
|-------|------|-------------|-----------|
| id | INT | Sim (auto) | Chave primária |
| name | VARCHAR(255) | Sim | Nome da escola |
| city | VARCHAR(100) | Não | Cidade |
| state | CHAR(2) | Não | Estado (UF) |
| address | TEXT | Não | Endereço completo |
| contact_person | VARCHAR(255) | Não | Pessoa de contato |
| phone | VARCHAR(20) | Não | Telefone |
| email | VARCHAR(255) | Não | Email |
| contract_start_date | DATE | Não | Data início contrato |
| contract_end_date | DATE | Não | Data fim contrato |
| logo_url | VARCHAR(255) | Não | URL do logo |
| status | ENUM | Não | active/inactive (padrão: active) |
| created_at | TIMESTAMP | Sim (auto) | Data de criação |
| updated_at | TIMESTAMP | Sim (auto) | Data última atualização |

## Funcionalidades Implementadas

### Validações
- Nome da escola é obrigatório
- Não permite deletar escola com alunos vinculados
- Upload de logo apenas em formatos: JPG, PNG, GIF
- Limite de tamanho para logos: 2MB

### Upload de Logos
- Diretório: `public/uploads/schools/`
- Formato do arquivo: `school_{timestamp}.{ext}`
- Remove logo antigo ao fazer upload de novo
- Remove logo ao deletar escola

### Mensagens de Feedback
- Mensagens de sucesso (verde)
- Mensagens de erro (vermelho)
- Confirmação JavaScript antes de deletar

### Design
- Layout admin responsivo
- Bootstrap 5
- Cores oficiais Hansen (#007e66)
- Badges para status e contagem de alunos
- Ícones Font Awesome
- Tabelas com hover effect

## Migrações de Banco de Dados

### Setup inicial:
```bash
mysql -u root -p < setup_banco.sql
```

### Atualizar tabela existente:
```bash
mysql -u root -p < migrations/add_school_fields.sql
```

## Rotas Registradas

Todas as rotas estão protegidas por autenticação (middleware).

```php
// Lista e criação
GET  /admin/schools
GET  /admin/schools/create
POST /admin/schools

// Ver, editar, deletar
GET  /admin/schools/{id}
GET  /admin/schools/{id}/edit
POST /admin/schools/{id}/update
POST /admin/schools/{id}/delete
```

## Integração com Alunos

- Na página de detalhes da escola, lista todos os alunos vinculados
- Botão para adicionar novo aluno diretamente na escola
- Previne deleção de escola com alunos vinculados
- Exibe contagem de alunos em badges na lista

## Exemplos de Uso

### Criar nova escola:
1. Acessar `/admin/schools`
2. Clicar em "Nova Escola"
3. Preencher campos obrigatórios (nome)
4. Opcionalmente fazer upload de logo
5. Salvar

### Editar escola:
1. Na lista, clicar no botão "Editar" (ícone lápis)
2. Alterar campos desejados
3. Fazer upload de novo logo (opcional)
4. Salvar

### Ver detalhes:
1. Na lista, clicar no botão "Ver Detalhes" (ícone olho)
2. Ver informações completas + alunos vinculados

### Deletar escola:
1. Na lista, clicar no botão "Deletar" (ícone lixeira)
2. Confirmar na janela JavaScript
3. Escola é removida (se não tiver alunos vinculados)

## Próximos Passos Sugeridos

- [ ] Adicionar paginação na lista de escolas
- [ ] Adicionar busca/filtro de escolas
- [ ] Exportar lista de escolas para Excel/PDF
- [ ] Implementar soft delete (deleção lógica)
- [ ] Adicionar campos personalizados por escola
- [ ] Dashboard com estatísticas por escola
- [ ] Relatórios de contratos vencendo

## Desenvolvedor

Implementado em 10/02/2025 para o projeto Hansen Educacional.
