# Plataforma Hansen Educacional — Revisao 03

**Data:** 18/03/2026
**Versao:** 2.1

---

## Resumo das Melhorias

Atualização focada em correções de usabilidade e estabilidade, com base no feedback dos usuários.

---

## O que mudou

### Observações Pedagógicas

- **Correção ortográfica completa** — Todas as perguntas orientadoras dos 6 eixos foram revisadas e corrigidas (acentuação e ortografia em português).
- **Correção no salvamento** — As observações agora são salvas corretamente ao criar um novo registro. Anteriormente, o preenchimento dos eixos na criação podia não ser gravado.

### Parecer Descritivo

- **Geração restaurada** — Com a correção do salvamento das observações, os pareceres descritivos voltam a funcionar normalmente. A mensagem informativa quando não há observações permanece.

### Planejamento Pedagógico

- **Filtro por eixo nos objetivos** — Ao selecionar um eixo de atividades no planejamento diário, apenas os objetivos de aprendizagem daquele eixo são exibidos. Isso torna o preenchimento mais focado e organizado.
- **Rotina Semanal melhorada** — Os campos de horário e descrição da atividade agora possuem labels claros e separados, facilitando o preenchimento.
- **Correção no salvamento da Rotina Semanal** — O botão "Salvar Rotina" agora funciona corretamente. As atividades da rotina são gravadas sem erro.
- **Registro Pós-Vivência mais acessível** — O botão para acessar a página de registro agora aparece de forma destacada tanto no topo quanto no rodapé da tela de dias do planejamento.

### Banco de Imagens

- **Upload com arrastar e soltar** — Agora é possível arrastar imagens diretamente para a página ou para o modal de upload. Pré-visualização das imagens antes do envio.

### Portfólio da Turma

- **Exportação PDF corrigida** — O erro ao gerar o PDF do portfólio foi corrigido. Em caso de problemas, uma mensagem amigável é exibida.
- **Botão "Visualizar PDF"** — Novo botão disponível antes de finalizar o portfólio, permitindo que o professor revise o conteúdo em PDF antes de solicitar a revisão da coordenação.

### Material de Apoio

- **Upload com arrastar e soltar** — O módulo de Material de Apoio agora suporta arrastar e soltar arquivos para upload, com indicação visual do arquivo selecionado.

---

## Como usar as novidades

| Funcionalidade | Onde encontrar |
|---|---|
| Perguntas orientadoras corrigidas | Observações > Nova Observação ou Editar |
| Filtro por eixo | Planejamento > Dias > Editar Dia > Selecionar eixo |
| Rotina Semanal | Planejamento > Dias > Rotina Semanal (botão no topo) |
| Registro Pós-Vivência | Planejamento > Dias > botão verde "Registro Pós-Vivência" |
| Upload drag-and-drop | Banco de Imagens > pasta do aluno (arraste imagens) |
| Upload drag-and-drop | Material de Apoio > pasta (arraste arquivos) |
| Visualizar PDF | Portfólios > selecionar portfólio > botão "Visualizar PDF" |

---

## Notas Técnicas (Deploy)

1. **Rodar migrations no servidor:**
   ```
   php scripts/run_migrations_024_030.php
   ```
   Isso criará as tabelas `planning_daily_routines` e `planning_daily_entries` (migrations 031 e 032), e tornará o campo `content` da tabela `observations` nullable (migration 033).

2. **Composer:** O `composer install` já foi executado. No servidor, garantir que `vendor/` está atualizado com `composer install --no-dev`.

3. **PHP GD:** A extensão `ext-gd` deve estar habilitada no servidor para geração de PDFs com imagens. Verificar `php.ini`.

---

*Hansen Educacional — Plataforma Pedagógica*
