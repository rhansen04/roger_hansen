# Checklist de Testes - CRUD de Escolas

## Preparação
- [ ] Executar migration: `mysql -u root -p < migrations/add_school_fields.sql`
- [ ] Verificar se diretório `public/uploads/schools/` existe
- [ ] Fazer login no sistema admin

## Testes Funcionais

### 1. Listar Escolas (Index)
- [ ] Acessar `/admin/schools`
- [ ] Verificar se a página carrega sem erros
- [ ] Verificar exibição da tabela de escolas
- [ ] Verificar badges de status (ativo/inativo)
- [ ] Verificar badges de contagem de alunos
- [ ] Verificar exibição de logos (ou placeholder)
- [ ] Verificar botões: Ver, Editar, Deletar

### 2. Criar Nova Escola
- [ ] Clicar no botão "Nova Escola"
- [ ] Verificar formulário de criação carrega
- [ ] **Teste de validação:** Tentar salvar sem preencher nome (deve dar erro)
- [ ] Preencher apenas o nome (campo obrigatório)
- [ ] Salvar - deve redirecionar para lista com mensagem de sucesso
- [ ] **Teste completo:** Criar escola com todos os campos:
  - [ ] Nome: "Escola Teste Completa"
  - [ ] Cidade: "São Paulo"
  - [ ] Estado: "SP"
  - [ ] Endereço: "Rua Teste, 123 - Centro - CEP 01000-000"
  - [ ] Pessoa de Contato: "João Silva"
  - [ ] Telefone: "(11) 1234-5678"
  - [ ] Email: "contato@escolateste.com.br"
  - [ ] Data Início: data atual
  - [ ] Data Término: +1 ano
  - [ ] Status: Ativo
  - [ ] Logo: fazer upload de imagem JPG/PNG
- [ ] Verificar se escola aparece na lista
- [ ] Verificar se logo foi salvo corretamente

### 3. Ver Detalhes da Escola
- [ ] Clicar no botão "Ver Detalhes" (ícone olho)
- [ ] Verificar exibição de todas as informações
- [ ] Verificar seção "Informações Gerais"
- [ ] Verificar seção "Informações do Contrato"
- [ ] Verificar exibição do logo
- [ ] Verificar datas de cadastro/atualização
- [ ] Verificar lista de alunos vinculados
- [ ] Verificar botão "Adicionar Aluno"
- [ ] Verificar botões "Voltar" e "Editar"

### 4. Editar Escola
- [ ] Na lista, clicar no botão "Editar" (ícone lápis)
- [ ] Verificar formulário pré-preenchido com dados atuais
- [ ] Alterar nome da escola
- [ ] Alterar cidade
- [ ] Alterar telefone
- [ ] Fazer upload de novo logo
- [ ] Salvar alterações
- [ ] Verificar mensagem de sucesso
- [ ] Verificar se alterações foram salvas
- [ ] Verificar se logo foi atualizado
- [ ] **Teste sem alteração de logo:** Editar sem fazer novo upload
- [ ] Verificar se logo antigo foi mantido

### 5. Deletar Escola
- [ ] **Teste com alunos vinculados:**
  - [ ] Tentar deletar escola com alunos
  - [ ] Deve mostrar mensagem de erro
  - [ ] Escola NÃO deve ser deletada
- [ ] **Teste sem alunos:**
  - [ ] Criar uma escola sem alunos
  - [ ] Clicar no botão "Deletar" (ícone lixeira)
  - [ ] Verificar confirmação JavaScript
  - [ ] Cancelar - escola não deve ser deletada
  - [ ] Tentar novamente e confirmar
  - [ ] Verificar mensagem de sucesso
  - [ ] Verificar se escola foi removida da lista
  - [ ] Verificar se logo foi deletado do servidor

### 6. Upload de Logos
- [ ] **Teste de formatos aceitos:**
  - [ ] Upload de JPG - deve funcionar
  - [ ] Upload de PNG - deve funcionar
  - [ ] Upload de GIF - deve funcionar
  - [ ] Upload de arquivo não-imagem - deve rejeitar
- [ ] **Teste de substituição:**
  - [ ] Upload de logo inicial
  - [ ] Editar e fazer upload de novo logo
  - [ ] Verificar se logo antigo foi removido do servidor
  - [ ] Verificar se novo logo está sendo exibido

### 7. Status e Badges
- [ ] Criar escola com status "Ativo"
- [ ] Verificar badge verde "Ativo"
- [ ] Editar para status "Inativo"
- [ ] Verificar badge cinza "Inativo"
- [ ] Verificar contagem de alunos no badge azul

### 8. Integração com Alunos
- [ ] Acessar detalhes de uma escola
- [ ] Clicar em "Adicionar Aluno"
- [ ] Verificar se redireciona para formulário de criação de aluno
- [ ] Verificar se escola está pré-selecionada (school_id)
- [ ] Criar aluno vinculado à escola
- [ ] Voltar para detalhes da escola
- [ ] Verificar se aluno aparece na lista
- [ ] Verificar contagem de alunos atualizada

### 9. Mensagens de Feedback
- [ ] Criar escola - mensagem verde de sucesso
- [ ] Editar escola - mensagem verde de sucesso
- [ ] Deletar escola - mensagem verde de sucesso
- [ ] Tentar deletar com alunos - mensagem vermelha de erro
- [ ] Criar sem nome - mensagem vermelha de erro
- [ ] Verificar auto-dismiss das mensagens

### 10. Responsividade
- [ ] Abrir em desktop - layout correto
- [ ] Abrir em tablet - layout adaptado
- [ ] Abrir em mobile - layout mobile friendly
- [ ] Verificar tabela responsiva com scroll horizontal
- [ ] Verificar formulários em mobile

## Testes de Segurança

### 11. Autenticação
- [ ] Fazer logout
- [ ] Tentar acessar `/admin/schools` sem login
- [ ] Deve redirecionar para página de login
- [ ] Fazer login novamente
- [ ] Verificar acesso liberado

### 12. Validações de Dados
- [ ] **SQL Injection:** Tentar inserir SQL no campo nome
- [ ] **XSS:** Tentar inserir `<script>alert('XSS')</script>` no nome
- [ ] Verificar se dados são sanitizados (htmlspecialchars)
- [ ] **Upload malicioso:** Tentar fazer upload de arquivo .php renomeado
- [ ] Verificar se apenas imagens são aceitas

## Testes de Performance

### 13. Consultas ao Banco
- [ ] Verificar se lista carrega rápido (< 1 segundo)
- [ ] Criar 50+ escolas e verificar performance
- [ ] Verificar se índices do banco estão otimizados
- [ ] Verificar uso de JOIN para contagem de alunos

## Testes de Usabilidade

### 14. Experiência do Usuário
- [ ] Verificar clareza das labels dos campos
- [ ] Verificar textos de ajuda (small texts)
- [ ] Verificar ícones intuitivos
- [ ] Verificar cores de acordo com identidade Hansen (#007e66)
- [ ] Verificar feedback visual ao hover nos botões
- [ ] Verificar confirmação antes de ações destrutivas

## Bugs Encontrados
*(Listar aqui qualquer bug encontrado durante os testes)*

---

## Resultado Final
- [ ] Todos os testes passaram
- [ ] Sistema está pronto para produção

**Data do Teste:** ___/___/_____
**Testador:** _____________________
**Aprovado:** [ ] Sim [ ] Não
