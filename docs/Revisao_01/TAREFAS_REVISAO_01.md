# TAREFAS — Revisão 01 (Feedback de Usuários)

> Fonte: `docs/Revisao_01/Plataforma_Correções_V1.docx.pdf`
> Criado em: 2026-03-15
> Testado por: Roger Hansen (login coordenador: rogerzhansen@yahoo.com.br)

---

## Legenda de Status
- ⬜ Não iniciado
- 🔄 Em andamento
- ✅ Concluído

---

## R1-1 — Turmas: Remover coluna "Escola" da listagem
**Status:** ✅ | **Complexidade:** Baixa | **Módulo:** Turmas

**Problema:** A tabela de turmas exibe a coluna "Escola", que não é necessária (só existe uma escola).

**Solução:**
- Remover coluna "Escola" do `<thead>` e `<tbody>` em `views/admin/classrooms/index.php`
- Opcional: remover o JOIN com `schools` no `Classroom->all()` se não for usado em outro lugar

**Arquivos:**
- `views/admin/classrooms/index.php` (coluna "Escola" no `<th>` e `<td>`)
- `app/Models/Classroom.php` (método `all()` — JOIN com schools)

---

## R1-2 — Adicionar Aluno: Formulário de cadastro inline (em vez de select)
**Status:** ✅ | **Complexidade:** Média | **Módulo:** Turmas / Alunos

**Problema:** Ao clicar "+ Adicionar Aluno" dentro de uma turma, abre um modal com um `<select>` de alunos já cadastrados. O usuário espera um **formulário de cadastro** para criar o aluno diretamente.

**Solução:** Substituir o modal de seleção por um formulário de cadastro com os campos:
- Nome da criança (texto, obrigatório)
- Data de Nascimento (seletor de data, obrigatório)
- Turma (preenchido automaticamente com a turma atual, readonly)
- Foto (upload de imagem — JPG/PNG, opcional)
- Botão Salvar

**Comportamento esperado:**
1. Clicar "+ Adicionar Aluno" abre modal com formulário de cadastro
2. Ao salvar, cria o aluno na tabela `students` E vincula automaticamente à turma (`classroom_students`)
3. Recarrega a página mostrando o aluno na lista

**Arquivos:**
- `views/admin/classrooms/show.php` (modal `addStudentModal` — linhas 156-192)
- `app/Controllers/Admin/ClassroomController.php` (método `addStudent()` — linhas 79-105)
- `app/Models/Classroom.php` (novo método ou ajuste no `addStudent()`)
- `app/Models/Student.php` (método `create()` para referência)

---

## R1-3 — Observações: Auto-vincular aluno quando acessado do perfil
**Status:** ✅ | **Complexidade:** Baixa | **Módulo:** Observações

**Problema:** Ao criar observação a partir do perfil do aluno, o formulário exibe um dropdown "Selecione um aluno" desnecessário — o aluno já deveria estar pré-selecionado automaticamente.

**Solução:**
- Se a URL contém `?student_id=X`, pré-selecionar o aluno e ocultar o dropdown (ou torná-lo readonly)
- O controller já passa `$selectedStudentId` — basta usar no front-end para esconder o select quando preenchido

**Arquivos:**
- `views/admin/observations/create.php` (linhas 28-40 — dropdown de aluno)
- `app/Controllers/Admin/ObservationController.php` (método `create()` — linhas 54-81)

---

## R1-4 — Observações: Adicionar perguntas orientadoras por eixo
**Status:** ✅ | **Complexidade:** Média | **Módulo:** Observações

**Problema:** Os campos de texto dos 6 eixos pedagógicos não possuem perguntas orientadoras para guiar o professor no preenchimento.

**Solução:** Adicionar bloco de perguntas (tipo accordion, tooltip, ou texto auxiliar colapsável) acima ou ao lado de cada textarea.

**Perguntas por eixo:**

### Observação Geral
- Que mudanças você observou nesse campo desde a última observação?
- Quais atividades, objetos, ou brinquedos a criança demonstra maior interesse em explorar?
- Quais são suas facilidades e dificuldades?
- Como a criança interage com os colegas e professores?
- Em que atividades a criança demonstra autonomia? O que faz por conta própria?
- Como a criança lida com situações desafiadoras?
- Como a criança expressa suas emoções?
- Quais são as características mais marcantes no comportamento da criança?

### Movimento
- Que mudanças você observou nesse campo desde a última observação?
- Prudência: Como a criança se movimenta? É cuidadosa?
- Persistência: Insiste quando enfrenta dificuldades?
- Medo e Coragem: Apresenta medos excessivos ou enfrenta desafios?
- Qualidade do Movimento: Movimentos equilibrados, precisos, tensos ou relaxados?

### Manual
- Que mudanças você observou nesse campo desde a última observação?
- Capacidade de Brincar: A criança brinca e se diverte? Brinca sozinha?
- Concentração: Concentra-se nos brinquedos e atividades manuais?
- Variedade: Explora diferentes tipos de brinquedos e atividades?
- Profundidade: Brinca mais tempo com um mesmo brinquedo?
- Interatividade: Como a criança interage com os brinquedos e com outras crianças durante as atividades manuais?

### Musical
- Que mudanças você observou nesse campo desde a última observação?
- Preferências Musicais: Quais são as preferências da criança em relação a tipos sonoros, músicas e instrumentos?
- Sincronia: A criança acompanha os movimentos e sons de forma sincronizada?
- Canto: A criança canta ou cantarola sozinha?
- Concentração: Como é a concentração da criança durante atividades musicais?
- Reações: Quais são as reações da criança a diferentes sons e músicas?

### Contos
- Que mudanças você observou nesse campo desde a última observação?
- Reações Corporais e Faciais: Como a criança reage aos contos?
- Expressões de Emoções: Como a criança expressa emoções durante os contos?
- Preferências: Quais são as preferências da criança em relação a sons, rimas, momentos dos contos e histórias?
- Imitação: A criança imita gestos e palavras dos contos?

### Programa Comunicação Ativa (PCA)
- Que mudanças você observou nesse campo desde a última observação?
- Capacidade de compreender palavras: Entende os significados das palavras.
- Capacidade de expressar palavras: Expressa palavras com sentido correto.
- Usa palavras trabalhadas no seu dia a dia.
- Consegue expressar em palavras o que está sentindo ou pensando.
- Entende o sentido das histórias de conversar.

**Arquivos:**
- `views/admin/observations/create.php` (painéis dos eixos — linhas 105-134)
- `views/admin/observations/edit.php` (mesmo ajuste)

---

## R1-5 — Portfólio: Corrigir nome do eixo "Contos e Histórias" → "Contos"
**Status:** ✅ | **Complexidade:** Baixa | **Módulo:** Portfólio

**Problema:** O eixo aparece como "Contos e Historias" no portfólio. Deve ser apenas **"Contos"**.

**Arquivos:**
- `views/admin/portfolios/form.php` (linha 41 — `'name' => 'Contos e Historias'`)

---

## R1-6 — Portfólio: Corrigir nome "PCA - Projeto Coletivo" → "Programa Comunicação Ativa (PCA)"
**Status:** ✅ | **Complexidade:** Baixa | **Módulo:** Portfólio

**Problema:** O eixo PCA aparece como "PCA - Projeto Coletivo". Deve ser **"Programa Comunicação Ativa (PCA)"**.

**Também atualizar a descrição:** de "Projetos coletivos de aprendizagem que integram diferentes areas do conhecimento" para algo alinhado com comunicação ativa.

**Arquivos:**
- `views/admin/portfolios/form.php` (linha 45 — `'name' => 'PCA - Projeto Coletivo'`)

---

## R1-7 — Coordenador: Login mostra visão de professor
**Status:** ✅ | **Complexidade:** Média-Alta | **Módulo:** Autenticação / Papéis

**Problema:** O login coordenador (rogerzhansen@yahoo.com.br) está exibindo a experiência de professor. O usuário não conseguiu ver como funciona para o coordenador ou administrador.

**Investigação necessária:**
1. Verificar na tabela `users` qual é o `role` do usuário com email `rogerzhansen@yahoo.com.br` — pode estar como `professor` em vez de `coordenador`
2. Se o role estiver correto, verificar se o `DashboardController` está chamando `indexCoordenador()` corretamente
3. Verificar o sidebar — o menu lateral mostrado no screenshot tem items de professor (Cursos, Observações, etc.)

**Causa mais provável:** O usuário foi cadastrado com role `professor` em vez de `coordenador`. Basta atualizar o role no banco.

**Solução:**
```sql
UPDATE users SET role = 'coordenador' WHERE email = 'rogerzhansen@yahoo.com.br';
```

**Se não for o caso**, verificar:
- `app/Controllers/AuthController.php` — sessão `$_SESSION['user_role']`
- `app/Controllers/Admin/DashboardController.php` — método `index()`
- `views/layouts/admin.php` — condicionais do sidebar

---

## Resumo de Prioridades

| # | Tarefa | Complexidade | Status |
|---|--------|-------------|--------|
| R1-7 | Coordenador vê visão de professor | Média-Alta | ✅ role corrigido no banco |
| R1-2 | Formulário de cadastro de aluno inline | Média | ✅ modal com form de cadastro |
| R1-4 | Perguntas orientadoras nos eixos | Média | ✅ create + edit |
| R1-3 | Auto-vincular aluno nas observações | Baixa | ✅ hidden input quando pré-selecionado |
| R1-1 | Remover coluna "Escola" das turmas | Baixa | ✅ coluna removida |
| R1-5 | Corrigir nome "Contos e Histórias" → "Contos" | Baixa | ✅ form.php + show.php |
| R1-6 | Corrigir nome PCA no portfólio | Baixa | ✅ form.php + show.php |
