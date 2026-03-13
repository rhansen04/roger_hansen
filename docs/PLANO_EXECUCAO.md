# PLANO DE EXECUÇÃO — Plataforma de Acompanhamento Pedagógico

> Baseado no documento "TAREFAS DA PLATAFORMA" (Reunião 02/03/2026 — Roger, Regis, Laryssa)
> Gerado em: 2026-03-12
> Acesso: http://154.38.189.82:8080/

---

## VISÃO GERAL

Este plano organiza as tarefas do documento em **fases de execução** ordenadas por dependência técnica. Cada tarefa recebe um código único para rastreamento.

### Legenda de Status
- ⬜ Não iniciado
- 🔄 Em andamento
- ✅ Concluído
- ⏭️ Já existente (implementado anteriormente)

---

## FASE 0 — AJUSTES NA BASE EXISTENTE

> Adaptar módulos já implementados para atender aos requisitos do documento.

### T-0.1 — Ajuste do Menu Lateral por Papel (Professor vs Coordenador)
**Módulo:** 1 e 11 | **Status:** ✅
**Descrição:** O sistema já possui sidebar admin. Precisa diferenciar o menu lateral conforme o papel do usuário logado:
- **Professor:** Cursos, Turmas, Planejamento, Parecer Descritivo, Portfólio, Material de Apoio, Banco de Imagens
- **Coordenador:** Turmas, Pareceres Descritivos, Portfólios, Planejamentos, Materiais de Apoio, Banco de Imagens (somente visualização), Relatório

**Arquivos afetados:**
- `views/layouts/admin.php` (sidebar)
- Possível criação de helper para verificar papel do usuário

---

### T-0.2 — Dashboard diferenciado por Papel
**Módulo:** 1 e 11 | **Status:** ✅
**Descrição:** Ajustar o dashboard para exibir cards diferentes conforme o papel:

**Professor:**
- Cursos: lista cada curso com % de conclusão (ex: "Pedagogia Florença I — 70%")
- Turmas ativas
- Nº de alunos sob responsabilidade
- Pareceres Descritivos pendentes
- Status do portfólio mais recente

**Coordenador:**
- Nº total de turmas
- Nº total de crianças
- Nº total de professores
- Pareceres: X pendentes / Y finalizados
- Portfólios: X pendentes / Y finalizados
- Tabela de relatório de cursos (nome, inscritos, % média de conclusão)

**Arquivos afetados:**
- `app/Controllers/Admin/DashboardController.php`
- `views/admin/dashboard.php`

---

### T-0.3 — Ajuste do modelo de Turmas (status e histórico)
**Módulo:** 2 | **Status:** ✅
**Descrição:** O modelo Classroom já existe. Ajustes necessários:
- Status deve ser apenas: **Ativo** e **Inativo** (confirmar que não há "Arquivada")
- Turma **nunca deve ser excluída**, apenas desativada
- Permitir alteração de nome da turma e professora entre anos letivos
- Preservar todo histórico pedagógico vinculado (portfólios, pareceres, observações)
- Remover ou esconder botão "Excluir" da interface de turmas

**Arquivos afetados:**
- `app/Controllers/Admin/ClassroomController.php` (remover delete ou substituir por inativar)
- `views/admin/classrooms/index.php`
- `views/admin/classrooms/edit.php`

---

### T-0.4 — Vínculo Aluno ↔ Turma
**Módulo:** 2 e 3 | **Status:** ✅
**Descrição:** Garantir que alunos estejam vinculados a turmas (não apenas a escolas). Necessário:
- Tabela pivot `classroom_students` (se não existir): classroom_id, student_id, enrolled_at
- Ao clicar na turma → exibir lista de alunos daquela turma
- Botão "Adicionar Aluno" dentro da turma
- Na lista: Foto | Nome | Data Nascimento | Idade (calculada) | Ação (Acessar)

**Arquivos afetados:**
- Nova migration para `classroom_students`
- `app/Models/Classroom.php` (método students())
- `app/Models/Student.php` (método classrooms())
- `app/Controllers/Admin/ClassroomController.php` (show com alunos)
- `views/admin/classrooms/show.php` (lista de alunos da turma)

---

### T-0.5 — Perfil do Aluno (adequar ao documento)
**Módulo:** 3 | **Status:** ✅
**Descrição:** O perfil do aluno já existe mas precisa incluir:
- Foto da criança (em destaque)
- Nome completo, Data de nascimento, Idade (calculada), Turma, Professor responsável
- Botão **"Observação"** → leva para observações do aluno
- Botão **"Parecer Descritivo"** → leva para parecer do aluno

**Arquivos afetados:**
- `views/admin/students/edit.php` ou criar `views/admin/students/show.php`
- `app/Controllers/Admin/StudentController.php`

---

### T-0.6 — Ajuste do Planejamento (estrutura Mês → Semanas → Dias)
**Módulo:** 8 | **Status:** ✅
**Descrição:** O sistema de planejamento já existe com templates. Verificar e ajustar:
- Hierarquia: **Mês → Semanas → Dias**
- Botão "+ Adicionar Planejamento" → selecionar mês
- Dentro do mês: "+ Adicionar Semana" (semana 1-5)
- Dentro da semana: "+ Adicionar Dia" (segunda a sexta)
- Dentro do dia: estrutura pedagógica já existente
- **Rotina do Dia:** campo de horário (preenchimento livre pelo professor) + campo descrição da atividade + botão "+ Adicionar atividade"
- **Visualizar Rotina da Semana:** tabela comparativa (Segunda a Sexta lado a lado)

**Arquivos afetados:**
- `app/Controllers/Admin/PlanningController.php`
- `app/Models/PlanningSubmission.php`
- Views de planning
- Possível nova migration para tabela de rotina diária

---

## FASE 1 — OBSERVAÇÕES PEDAGÓGICAS (adequação)

> O módulo de observações já existe. Adequar à estrutura do documento.

### T-1.1 — Observações por Eixo Pedagógico
**Módulo:** 4 | **Status:** ✅
**Descrição:** Reestruturar as observações para ter abas/seções por eixo:
1. Observação Geral
2. Eixo de Atividade de Movimento
3. Eixo de Atividade Manual
4. Eixo de Atividade Musical
5. Eixo de Atividade de Contos
6. Eixo Programa Comunicação Ativa

Cada campo: tipo texto, limite visual de 5 linhas.

**Arquivos afetados:**
- `app/Models/Observation.php` (campos por eixo)
- `app/Controllers/Admin/ObservationController.php`
- `views/admin/observations/` (reestruturar com abas)
- Possível migration para adicionar campos de eixos na tabela observations

---

### T-1.2 — Periodicidade Semestral das Observações
**Módulo:** 4 | **Status:** ✅
**Descrição:** As observações devem ser organizadas por **semestre**:
- 1º Semestre (ex: jan-jun)
- 2º Semestre (ex: jul-dez)
- Ao criar nova observação: selecionar semestre + ano
- Status: "Em andamento" (padrão) ou "Finalizado"

**Arquivos afetados:**
- `app/Models/Observation.php`
- `app/Controllers/Admin/ObservationController.php`
- Views de observações

---

### T-1.3 — Salvamento Automático de Observações
**Módulo:** 4 | **Status:** ✅
**Descrição:**
- Salvar automaticamente ao sair do campo (evento blur/change)
- Exibir indicador: "Salvo automaticamente às HH:MM"
- Registrar data e usuário que editou
- Prevenir perda de dados ao atualizar página (beforeunload)

**Arquivos afetados:**
- JavaScript no frontend (AJAX auto-save)
- Rota API para salvar campo individual
- Views de observações

---

### T-1.4 — Finalizar Observação
**Módulo:** 4 | **Status:** ✅
**Descrição:**
- Botão "Finalizar Registro" no final da página
- Modal de confirmação: "Deseja finalizar? Após finalizar, não será possível editar."
- Após confirmação: campos ficam somente leitura, status → "Finalizado"
- Libera botão "Gerar Parecer Descritivo"
- **Coordenação pode reabrir** observações finalizadas

**Arquivos afetados:**
- Controller de observações (método finalize)
- Views (modal, botão, estado somente leitura)
- Rota para finalizar/reabrir

---

### T-1.5 — Permissões de Observações
**Módulo:** 4 | **Status:** ✅
**Descrição:**
- **Professor:** criar/editar observações "Em andamento"
- **Coordenação:** editar qualquer turma, reabrir observações finalizadas, visualizar histórico completo

**Arquivos afetados:**
- Middleware ou verificação no controller
- Views (esconder/mostrar botões conforme papel)

---

## FASE 2 — PARECER DESCRITIVO

> Módulo novo. Depende de Observações (Fase 1) e Banco de Imagens (Fase 3).

### T-2.1 — Modelo e Migração do Parecer Descritivo
**Módulo:** 5 | **Status:** ✅
**Descrição:** Criar tabela `descriptive_reports`:
```
id, student_id, classroom_id, semester (1 ou 2), year,
observation_id (FK → observations),
cover_photo_url, intro_text (fixo), student_text (compilado),
student_text_edited (versão editada pelo professor),
axis_photos (JSON: {eixo: [{url, caption}]}),
status (draft/finalized/sent),
finalized_at, finalized_by,
created_at, updated_at
```

**Arquivos a criar:**
- `migrations/024_create_descriptive_reports.sql`
- `app/Models/DescriptiveReport.php`

---

### T-2.2 — Controller e Rotas do Parecer Descritivo
**Módulo:** 5 | **Status:** ✅
**Descrição:** Implementar CRUD + fluxo de geração:
- GET /admin/descriptive-reports → lista (por turma)
- GET /admin/descriptive-reports/create?student_id=X&observation_id=Y → gerar
- GET /admin/descriptive-reports/{id} → visualizar
- GET /admin/descriptive-reports/{id}/edit → editar texto (Página 2)
- PUT /admin/descriptive-reports/{id} → salvar edição
- POST /admin/descriptive-reports/{id}/finalize → finalizar
- POST /admin/descriptive-reports/{id}/reopen → reabrir (coordenação)
- GET /admin/descriptive-reports/{id}/pdf → gerar PDF
- GET /admin/descriptive-reports/{id}/docx → gerar Word

**Arquivos a criar:**
- `app/Controllers/Admin/DescriptiveReportController.php`

---

### T-2.3 — Views do Parecer Descritivo
**Módulo:** 5 | **Status:** ✅
**Descrição:** Criar views:
- `index.php` — lista de pareceres por turma (cards com nome da criança, status, ações)
- `show.php` — visualização completa do parecer (preview das páginas)
- `edit.php` — edição do texto da Página 2 (sem alterar observação original)
- `photos.php` — gestão das fotos dos eixos (Páginas 3-7)

---

### T-2.4 — Página da Capa (Parecer)
**Módulo:** 5 | **Status:** ✅
**Descrição:** Capa do PDF:
- Título: "PARECER DESCRITIVO"
- Subtítulo: "Acompanhamento do desenvolvimento da criança no ambiente escolar"
- Foto principal da criança (proporção fixa, destaque)
- Se não houver foto → aviso antes de gerar
- Ocupa 1 página inteira

---

### T-2.5 — Página 1: "Sobre o Parecer Descritivo" (texto fixo)
**Módulo:** 5 | **Status:** ✅
**Descrição:** Texto institucional fixo (não editável) — texto completo fornecido no documento:
- Começa com "Queridas famílias..."
- Termina com "Com carinho, Equipe Pedagógica"
- Armazenar como constante ou configuração do sistema

---

### T-2.6 — Página 2: Texto sobre a Criança
**Módulo:** 5 | **Status:** ✅
**Descrição:**
- Título: Nome da Criança
- Conteúdo: compilação automática de Observação Geral + 5 Eixos
- Botão "Editar" — ajustar texto sem alterar observação original
- Botão "Correção Automática" — IA (Gemini) para revisar ortografia, gramática e fluidez sem alterar sentido pedagógico
- Após gerar versão final em PDF → bloquear edição

**Integração IA (Gemini):**
- Usar `app/Services/GeminiService.php` existente
- Prompt específico para correção ortográfica/gramatical preservando conteúdo pedagógico

---

### T-2.7 — Páginas 3-7: Eixos de Atividades com Fotos
**Módulo:** 5 | **Status:** ✅
**Descrição:** 5 páginas, uma por eixo:
| Página | Eixo |
|--------|------|
| 3 | Atividades Musicais |
| 4 | Atividades Manuais |
| 5 | Atividades de Contos |
| 6 | Atividades de Movimento |
| 7 | Programa Comunicação Ativa |

Cada página: 3 fotos em linha + campo de legenda por foto.
Fotos vêm do Banco de Imagens (Fase 3).

---

### T-2.8 — Geração de PDF e Word
**Módulo:** 5 | **Status:** ✅
**Descrição:** Gerar documento exportável:
- PDF: usando biblioteca PHP (TCPDF ou mPDF via Composer)
- Word (.docx): usando PHPWord via Composer
- Estrutura: Capa → Página 1 (texto fixo) → Página 2 (texto criança) → Páginas 3-7 (eixos com fotos)
- Evitar quebra de imagem entre páginas

**Dependências:**
- `composer require tecnickcom/tcpdf` ou `mpdf/mpdf`
- `composer require phpoffice/phpword`

---

## FASE 3 — BANCO DE IMAGENS

> Módulo novo. Necessário para Parecer Descritivo e Portfólio.

### T-3.1 — Modelo e Migração do Banco de Imagens
**Módulo:** 10 | **Status:** ✅
**Descrição:** Criar tabelas:
```sql
-- Pastas do banco de imagens
CREATE TABLE image_folders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    classroom_id INT,
    student_id INT NULL,
    folder_type ENUM('classroom', 'student') NOT NULL,
    name VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Imagens
CREATE TABLE image_bank (
    id INT AUTO_INCREMENT PRIMARY KEY,
    folder_id INT NOT NULL,
    filename VARCHAR(255) NOT NULL,
    original_name VARCHAR(255) NOT NULL,
    file_size INT,
    mime_type VARCHAR(50),
    uploaded_by INT NOT NULL,
    caption VARCHAR(500),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (folder_id) REFERENCES image_folders(id),
    FOREIGN KEY (uploaded_by) REFERENCES users(id)
);
```

**Regras:**
- Pastas de turma criadas automaticamente ao cadastrar turma
- Subpastas de crianças criadas automaticamente ao vincular aluno à turma
- Formatos aceitos: JPG, PNG (fotos de celular)
- Redimensionar para otimizar armazenamento (max 1920px largura)

**Arquivos a criar:**
- `migrations/025_create_image_bank.sql`
- `app/Models/ImageFolder.php`
- `app/Models/ImageBank.php`

---

### T-3.2 — Controller e Rotas do Banco de Imagens
**Módulo:** 10 | **Status:** ✅
**Descrição:**
- GET /admin/image-bank → lista de turmas com pastas
- GET /admin/image-bank/{classroom_id} → pastas da turma (coletiva + individuais)
- GET /admin/image-bank/folder/{folder_id} → imagens da pasta (thumbnails)
- POST /admin/image-bank/folder/{folder_id}/upload → upload (múltiplas imagens)
- DELETE /admin/image-bank/image/{id} → excluir imagem
- POST /admin/image-bank/image/{id}/move → mover entre pastas
- PUT /admin/image-bank/image/{id}/caption → atualizar legenda

**Permissões:**
- Professor: upload, organizar, excluir
- Coordenador: visualizar (não faz upload)

**Arquivos a criar:**
- `app/Controllers/Admin/ImageBankController.php`

---

### T-3.3 — Views do Banco de Imagens
**Módulo:** 10 | **Status:** ✅
**Descrição:**
- `index.php` — lista de turmas com link para pastas
- `classroom.php` — pastas da turma (Registros Coletivos + pastas por aluno)
- `folder.php` — grid de thumbnails com botão upload, mover, excluir
- Upload via drag-and-drop ou botão (múltiplas imagens)

---

## FASE 4 — PORTFÓLIO DA TURMA

> Módulo novo. Depende do Banco de Imagens (Fase 3).

### T-4.1 — Modelo e Migração do Portfólio
**Módulo:** 6 | **Status:** ✅
**Descrição:** O portfólio é **por turma** (coletivo). Criar tabela:
```sql
CREATE TABLE portfolios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    classroom_id INT NOT NULL,
    semester TINYINT NOT NULL, -- 1 ou 2
    year INT NOT NULL,
    cover_photo_url VARCHAR(500),
    teacher_message TEXT, -- mensagem da professora (Pág. 3)
    teacher_message_corrected TEXT, -- versão corrigida por IA
    axis_movement_photos JSON, -- [{url, caption}]
    axis_manual_photos JSON,
    axis_stories_photos JSON,
    axis_music_photos JSON,
    axis_pca_photos JSON,
    axis_movement_description TEXT,
    axis_manual_description TEXT,
    axis_stories_description TEXT,
    axis_music_description TEXT,
    axis_pca_description TEXT,
    status ENUM('pending', 'finalized', 'revision_requested') DEFAULT 'pending',
    revision_notes TEXT,
    revision_requested_by INT,
    finalized_at DATETIME,
    finalized_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (classroom_id) REFERENCES classrooms(id)
);
```

**Arquivos a criar:**
- `migrations/026_create_portfolios.sql`
- `app/Models/Portfolio.php`

---

### T-4.2 — Controller e Rotas do Portfólio
**Módulo:** 6 | **Status:** ✅
**Descrição:**
- GET /admin/portfolios → lista por turma (cards com status)
- GET /admin/portfolios/create?classroom_id=X → novo portfólio
- POST /admin/portfolios → salvar
- GET /admin/portfolios/{id} → visualizar (preview)
- GET /admin/portfolios/{id}/edit → editar
- PUT /admin/portfolios/{id} → atualizar
- POST /admin/portfolios/{id}/finalize → finalizar
- POST /admin/portfolios/{id}/request-revision → solicitar revisão (coordenação)
- POST /admin/portfolios/{id}/reopen → reabrir para edição
- GET /admin/portfolios/{id}/pdf → gerar PDF
- POST /admin/portfolios/{id}/correct-text → correção IA da mensagem

**Arquivos a criar:**
- `app/Controllers/Admin/PortfolioController.php`

---

### T-4.3 — Views do Portfólio
**Módulo:** 6 | **Status:** ✅
**Descrição:** Criar views para o editor do portfólio:
- `index.php` — listagem por turma com status
- `create.php` / `edit.php` — editor com abas/steps:
  - Capa (foto da turma, título)
  - Página 1: "Sobre a Magia do Portfólio" (texto fixo, somente leitura)
  - Página 2: "Proposta da Pedagogia Florença" (texto fixo fornecido)
  - Página 3: "Mensagem para a turma" (campo editável + botão "Corrigir texto")
  - Página 4: "Os Eixos de Atividades" (texto fixo fornecido)
  - Páginas 5-14: Eixos (texto fixo + 3 fotos com legenda para cada eixo)
- `show.php` — preview completo do portfólio

**Textos fixos a incluir:**
- "Sobre a Magia do Portfólio" → fornecido no documento
- "Proposta da Pedagogia Florença" → fornecido no documento (5 princípios)
- "Os Eixos de Atividades" → fornecido no documento
- Texto de cada eixo (Movimento, Manuais, Contos, Música, PCA) → todos fornecidos

---

### T-4.4 — Geração de PDF do Portfólio
**Módulo:** 6 | **Status:** ✅
**Descrição:** Gerar PDF com 14+ páginas:
- Capa: foto da turma + título
- Pág. 1: "Sobre a Magia do Portfólio"
- Pág. 2: "Proposta da Pedagogia Florença"
- Pág. 3: "Mensagem para a turma" (texto da professora)
- Pág. 4: "Os Eixos de Atividades"
- Págs. 5-14: pares (texto fixo do eixo + página de 3 fotos com descrição)

Layout de fotos: 2 acima + 1 abaixo. Evitar quebra de imagem entre páginas.

---

## FASE 5 — MATERIAL DE APOIO

### T-5.1 — Modelo e Migração de Material de Apoio
**Módulo:** 9 | **Status:** ✅
**Descrição:** Criar sistema de pastas e arquivos de material de apoio:
```sql
CREATE TABLE support_material_folders (
    id INT AUTO_INCREMENT PRIMARY KEY,
    parent_id INT NULL,
    name VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    sort_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE support_materials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    folder_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    filename VARCHAR(255) NOT NULL,
    original_name VARCHAR(255) NOT NULL,
    file_size INT,
    mime_type VARCHAR(100),
    uploaded_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (folder_id) REFERENCES support_material_folders(id)
);
```

**Estrutura inicial de pastas (seed):**
```
Material de Apoio/
├── Eixos de Atividades/
│   ├── Manuais/
│   ├── Musicais/
│   ├── Contos/
│   └── Movimento/
├── Centros de Aprendizagem/
└── Famílias de Brinquedos/
```

Nota: Eixo "Comunicação Ativa" **não tem subpasta** (material é físico).

**Permissões:** Professor e Coordenador podem fazer upload. Formatos: PDF (principal), vídeos no futuro.

**Arquivos a criar:**
- `migrations/027_create_support_materials.sql`
- `app/Models/SupportMaterialFolder.php`
- `app/Models/SupportMaterial.php`

---

### T-5.2 — Controller, Rotas e Views de Material de Apoio
**Módulo:** 9 | **Status:** ✅
**Descrição:**
- GET /admin/support-materials → árvore de pastas
- GET /admin/support-materials/folder/{id} → conteúdo da pasta
- POST /admin/support-materials/folder/{id}/upload → upload de arquivo
- DELETE /admin/support-materials/{id} → excluir material
- GET /admin/support-materials/{id}/download → download

Views: navegação em árvore de pastas, upload, listagem de arquivos.

---

## FASE 6 — FLUXO DE APROVAÇÃO E NOTIFICAÇÕES

> Sistema transversal para Parecer, Portfólio e Planejamento.

### T-6.1 — Sistema de Notificações Internas
**Módulo:** 7 | **Status:** ✅
**Descrição:** Criar sistema de notificações in-app:
```sql
CREATE TABLE notifications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    type VARCHAR(50) NOT NULL, -- 'revision_request', 'finalized', etc.
    title VARCHAR(255) NOT NULL,
    message TEXT,
    reference_type VARCHAR(50), -- 'descriptive_report', 'portfolio', 'planning'
    reference_id INT,
    is_read TINYINT(1) DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);
```

- Ícone de sino no header com badge de contagem
- Dropdown com notificações recentes
- Página de todas as notificações
- Marcar como lida (individual e "marcar todas")
- Apenas internas (sem email)

**Arquivos a criar:**
- `migrations/028_create_notifications.sql`
- `app/Models/Notification.php`
- `app/Controllers/Admin/NotificationController.php`
- `views/admin/notifications/` (index.php, dropdown parcial)
- Atualizar `views/layouts/admin.php` (sino no header)

---

### T-6.2 — Fluxo Professor → Coordenador
**Módulo:** 7 e 12-13 | **Status:** ✅
**Descrição:** Implementar fluxo de aprovação:

1. **Professor** finaliza documento (Parecer / Portfólio / Planejamento)
   - Status → "Finalizado"
   - Notificação enviada para coordenador(es)

2. **Coordenador** visualiza documentos finalizados
   - Pode visualizar completo, mas NÃO edita
   - Botão "Solicitar ajuste" → abre campo de mensagem
   - Ao enviar: Professor notificado + Status → "Pendente"

3. **Professor** recebe notificação, faz ajustes
   - Finaliza novamente → volta para coordenador

4. **Coordenador** pode baixar PDF quando finalizado

**Arquivos afetados:**
- Controllers de Parecer, Portfólio e Planejamento
- Views de cada módulo (botões conforme papel)
- Model Notification (criar notificações automaticamente)

---

## FASE 7 — CURSOS / FORMAÇÃO (Visão do Aluno/Professor)

> Ajustar módulo de cursos existente para atender ao documento.

### T-7.1 — Dashboard do Aluno/Professor (Cursos)
**Módulo:** 14 | **Status:** ✅
**Descrição:** Tela inicial do curso mostrando:
- Nome do curso
- Progresso geral (%)
- Botão "Continuar curso"

---

### T-7.2 — Tela de Módulos do Curso
**Módulo:** 14 | **Status:** ✅
**Descrição:** Ao entrar no curso, exibir módulos em cards:
- Nome do módulo
- Quantidade de aulas
- Status: não iniciado / em andamento / concluído
- Botão "Acessar módulo"

---

### T-7.3 — Tela do Módulo (lista de aulas)
**Módulo:** 14 | **Status:** ✅
**Descrição:** Menu lateral ou lista com aulas:
- ✓ Concluído | ▶ Em andamento | ⬜ Não iniciado
- Permitir: pausar, voltar, rever conteúdo, acompanhar progresso, baixar materiais
- Módulo concluído quando teste é realizado
- Mensagem de conclusão ao finalizar módulo

---

### T-7.4 — Organização dos Cursos
**Módulo:** 14 | **Status:** ✅
**Descrição:** Categorias dentro do curso:
1. Módulos
2. Aulas Complementares
3. Aulas Práticas
4. Testes Avaliativos
5. Material Complementar

---

### T-7.5 — Relatório de Cursos (Coordenador)
**Módulo:** 11 | **Status:** ✅
**Descrição:** Na dashboard do coordenador, tabela com:
- Nome do curso
- Nº de professores inscritos
- % média de conclusão
- Botão "Exportar relatório" (PDF e Excel)

---

## CRONOGRAMA DE EXECUÇÃO

| Fase | Descrição | Tarefas | Prioridade |
|------|-----------|---------|------------|
| **0** | Ajustes na Base Existente | T-0.1 a T-0.6 | 🔴 CRÍTICA |
| **1** | Observações Pedagógicas | T-1.1 a T-1.5 | 🔴 CRÍTICA |
| **2** | Parecer Descritivo | T-2.1 a T-2.8 | 🟡 ALTA |
| **3** | Banco de Imagens | T-3.1 a T-3.3 | 🟡 ALTA |
| **4** | Portfólio da Turma | T-4.1 a T-4.4 | 🟡 ALTA |
| **5** | Material de Apoio | T-5.1 a T-5.2 | 🟢 MÉDIA |
| **6** | Fluxo de Aprovação e Notificações | T-6.1 a T-6.2 | 🟡 ALTA |
| **7** | Cursos / Formação | T-7.1 a T-7.5 | 🟢 MÉDIA |

### Ordem de Execução Recomendada
```
Fase 0 (base) → Fase 1 (observações) → Fase 3 (banco de imagens)
                                              ↓
                          Fase 2 (parecer) + Fase 4 (portfólio)
                                              ↓
                          Fase 6 (aprovação/notificações)
                                              ↓
                     Fase 5 (material de apoio) + Fase 7 (cursos)
```

### Dependências Técnicas
- **Fase 2** depende de: Fase 1 (observações finalizadas) + Fase 3 (fotos)
- **Fase 4** depende de: Fase 3 (fotos do banco de imagens)
- **Fase 6** depende de: Fases 2 e 4 (documentos para aprovar)
- **Fase 7** é independente (módulo de cursos já existe parcialmente)

---

## DECISÕES TÉCNICAS

### IA para Correção de Texto
- **Serviço:** Google Gemini (já integrado via `app/Services/GeminiService.php`)
- **Uso:** Correção ortográfica/gramatical + organização de texto corrido
- **Regra:** Nunca alterar sentido pedagógico do conteúdo

### Geração de PDF
- **Biblioteca recomendada:** mPDF (melhor suporte a HTML/CSS → PDF)
- **Instalação:** `composer require mpdf/mpdf`
- **Alternativa para Word:** PHPWord (`composer require phpoffice/phpword`)

### Imagens
- **Formatos aceitos:** JPG e PNG
- **Otimização:** redimensionar para max 1920px de largura ao fazer upload
- **Storage:** `public/uploads/image-bank/{classroom_id}/{student_id|collective}/`

### Notificações
- **Tipo:** Apenas internas (in-app)
- **UI:** Ícone de sino com badge no header

---

## REGISTRO DE EXECUÇÃO

> Registro centralizado em arquivo único: [`docs/REGISTRO_EXECUCAO.md`](REGISTRO_EXECUCAO.md)
> Cada tarefa tem seu log de execução atualizado conforme é implementada.
