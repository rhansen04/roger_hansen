# Quick Start - CRUD de Escolas

Guia rápido para configurar e usar o sistema de gerenciamento de escolas.

## Instalação Rápida

### 1. Atualizar Banco de Dados

Execute os scripts SQL na ordem:

```bash
# Windows (MySQL instalado)
mysql -u root -p < migrations/add_school_fields.sql
```

Ou copie e cole o conteúdo de `migrations/add_school_fields.sql` no phpMyAdmin.

### 2. (Opcional) Inserir Dados de Teste

```bash
mysql -u root -p < migrations/seed_schools_demo.sql
```

Isso irá criar:
- 10 escolas de exemplo
- 18 alunos vinculados
- Mix de escolas ativas e inativas

### 3. Verificar Permissões de Upload

Certifique-se que o diretório existe e tem permissão de escrita:
```
public/uploads/schools/
```

### 4. Acessar o Sistema

1. Faça login no sistema admin
2. Acesse: `http://seu-dominio/admin/schools`

## Uso Rápido

### Criar Nova Escola

1. Clique em "Nova Escola"
2. Preencha pelo menos o **nome** (obrigatório)
3. Opcionalmente:
   - Preencha cidade, estado, endereço
   - Adicione contato, telefone, email
   - Defina datas de contrato
   - Faça upload do logo
   - Escolha status (ativo/inativo)
4. Clique em "Cadastrar Escola"

### Ver Detalhes de uma Escola

1. Na lista, clique no botão azul (olho)
2. Visualize:
   - Todas as informações da escola
   - Logo
   - Lista de alunos vinculados
   - Datas de cadastro/atualização

### Editar Escola

1. Na lista, clique no botão amarelo (lápis)
2. Altere os campos desejados
3. Faça upload de novo logo (opcional)
4. Clique em "Atualizar Escola"

### Deletar Escola

1. Na lista, clique no botão vermelho (lixeira)
2. Confirme a ação
3. **Atenção:** Não é possível deletar escola com alunos vinculados

## Campos da Escola

| Campo | Obrigatório | Descrição |
|-------|-------------|-----------|
| Nome | Sim | Nome da instituição |
| Cidade | Não | Cidade onde está localizada |
| Estado | Não | UF (2 letras) |
| Endereço | Não | Endereço completo |
| Pessoa de Contato | Não | Nome do responsável |
| Telefone | Não | Telefone de contato |
| Email | Não | Email de contato |
| Data Início Contrato | Não | Início da parceria |
| Data Fim Contrato | Não | Término da parceria |
| Logo | Não | Imagem JPG/PNG/GIF (máx 2MB) |
| Status | Não | Ativo ou Inativo (padrão: Ativo) |

## Dicas de Uso

### Logo da Escola
- Formatos aceitos: JPG, PNG, GIF
- Tamanho recomendado: 400x400px
- Tamanho máximo do arquivo: 2MB
- Logo é exibido em 40x40px na lista
- Logo é exibido em tamanho maior nos detalhes

### Status
- **Ativo**: Escola com contrato vigente
- **Inativo**: Escola com contrato encerrado

### Alunos
- Visualize alunos vinculados nos detalhes da escola
- Clique em "Adicionar Aluno" para cadastrar novo aluno
- Aluno será automaticamente vinculado à escola

### Segurança
- Escolas com alunos vinculados NÃO podem ser deletadas
- Primeiro desvincule ou delete os alunos
- Logo é automaticamente removido ao deletar escola

## Atalhos e Links

- **Lista de Escolas**: `/admin/schools`
- **Nova Escola**: `/admin/schools/create`
- **Ver Escola**: `/admin/schools/{id}`
- **Editar Escola**: `/admin/schools/{id}/edit`

## Troubleshooting

### Erro ao fazer upload de logo
- Verifique se o diretório `public/uploads/schools/` existe
- Verifique permissões de escrita no diretório
- Verifique tamanho do arquivo (máx 2MB)
- Verifique formato (apenas JPG, PNG, GIF)

### Não consigo deletar escola
- Verifique se há alunos vinculados
- Acesse os detalhes da escola e veja a lista de alunos
- Primeiro remova os alunos, depois delete a escola

### Logo não aparece
- Verifique se o upload foi bem-sucedido
- Verifique se o arquivo está em `public/uploads/schools/`
- Verifique se o caminho no banco está correto
- Limpe o cache do navegador (Ctrl+F5)

### Campos não estão salvando
- Verifique se executou a migration `add_school_fields.sql`
- Verifique estrutura da tabela: `DESCRIBE schools;`
- Verifique logs de erro do PHP

## Próximos Passos

Após dominar o CRUD de escolas, explore:

1. **Dashboard**: Veja estatísticas gerais
2. **Alunos**: Gerencie alunos por escola
3. **Observações**: Registre avaliações dos alunos
4. **Relatórios**: Exporte dados para análise

## Suporte

Documentação completa: `docs/CRUD_ESCOLAS.md`
Testes: `tests/test_schools.md`

---

**Desenvolvido para Hansen Educacional**
**Versão 1.0 - Fevereiro 2025**
