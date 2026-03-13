# HANSEN EDUCACIONAL — Plataforma de Acompanhamento Pedagógico

## Release de Atualização — Março 2026

**Data de publicação:** 12 de março de 2026
**Versão:** 2.0

---

## Introdução

A Plataforma de Acompanhamento Pedagógico da Hansen Educacional passou por uma ampla atualização, resultado do levantamento de requisitos realizado na reunião de 02 de março de 2026, com a participação de Roger, Regis e Laryssa. Todas as funcionalidades solicitadas naquela ocasião foram integralmente implementadas e encontram-se disponíveis para uso imediato na plataforma.

Esta versão representa um marco significativo na digitalização do acompanhamento pedagógico baseado na Pedagogia Florença. A plataforma agora contempla o ciclo completo do trabalho docente e coordenativo — desde o planejamento de atividades até a geração de pareceres descritivos e portfólios, passando pela gestão de turmas, observações pedagógicas, banco de imagens e relatórios consolidados. Recursos de Inteligência Artificial foram incorporados para auxiliar na correção textual e na elaboração de resumos pedagógicos.

O presente documento descreve cada módulo implementado, suas funcionalidades, o caminho de acesso no sistema e os perfis de usuário que possuem permissão para utilizá-lo. Recomendamos a leitura integral deste material para pleno aproveitamento dos recursos disponíveis.

---

## 1. Dashboard Personalizado por Papel

O Dashboard é a tela inicial da plataforma e foi projetado para exibir informações relevantes de acordo com o perfil de cada usuário. Ao efetuar login, cada profissional visualiza um painel com as métricas e os atalhos mais pertinentes às suas atribuições diárias.

**Como acessar:** O Dashboard é exibido automaticamente ao realizar o login na plataforma. Também pode ser acessado a qualquer momento clicando no logotipo ou no item "Dashboard" no menu lateral.

**Funcionalidades por perfil:**

- **Professor:**
  - Listagem das turmas sob sua responsabilidade
  - Quantidade de alunos vinculados
  - Cursos em andamento com indicador de progresso
  - Observações pedagógicas recentes

- **Coordenador:**
  - Métricas globais: total de turmas, crianças matriculadas e professores ativos
  - Relatório consolidado de cursos e formações
  - Visão geral do andamento pedagógico da instituição

- **Administrador:**
  - Visão completa com todas as métricas da plataforma
  - Dados de matrículas e contatos
  - Estatísticas de quizzes e avaliações
  - Acesso irrestrito a todas as funcionalidades

**Quem pode usar:** Professor, Coordenador e Administrador (cada um com visão adaptada ao seu perfil).

---

## 2. Menu Lateral Diferenciado

O menu lateral da plataforma foi redesenhado para apresentar apenas as opções relevantes ao perfil do usuário autenticado. Essa abordagem simplifica a navegação e garante que cada profissional tenha acesso direto às ferramentas necessárias para o desempenho de suas funções.

**Como acessar:** O menu lateral está permanentemente visível no lado esquerdo da tela em todas as páginas da plataforma.

**Itens exibidos por perfil:**

- **Professor:**
  - Cursos
  - Turmas
  - Planejamento
  - Pareceres
  - Portfólios
  - Banco de Imagens
  - Material de Apoio

- **Coordenador:**
  - Turmas
  - Observações
  - Planejamentos
  - Pareceres
  - Portfólios
  - Relatórios
  - Material de Apoio

- **Administrador:**
  - Acesso completo a todos os itens do menu, incluindo configurações do sistema e gestão de usuários

**Quem pode usar:** Professor, Coordenador e Administrador (cada um visualiza o menu correspondente ao seu perfil).

---

## 3. Gestão de Turmas

O módulo de Gestão de Turmas permite o cadastro, a edição e o controle das turmas da instituição. As turmas podem ser ativadas ou desativadas conforme a necessidade, sem que sejam excluídas do sistema, preservando assim o histórico pedagógico completo.

**Como acessar:** Menu lateral > **Turmas**.

**Funcionalidades principais:**

- Criar novas turmas com nome, ano letivo e turno
- Editar informações de turmas existentes
- Ativar e desativar turmas (o sistema não permite exclusão, garantindo a integridade do histórico)
- Vincular alunos à turma com exibição de foto, nome completo e idade calculada automaticamente
- Visualizar a lista completa de alunos dentro de cada turma
- Atribuir professor responsável à turma

**Quem pode usar:** Coordenador e Administrador (Professor visualiza apenas as turmas às quais está vinculado).

---

## 4. Perfil do Aluno Aprimorado

O perfil de cada aluno foi aprimorado para reunir em uma única tela todas as informações relevantes ao acompanhamento pedagógico individual. Além dos dados cadastrais, o perfil oferece acesso direto às ferramentas pedagógicas e conta com um recurso de Inteligência Artificial para geração de resumos.

**Como acessar:** Menu lateral > **Turmas** > selecionar a turma > clicar no nome do aluno.

**Funcionalidades principais:**

- Foto do aluno, dados pessoais completos, turma e professor responsável
- Botão de acesso direto às Observações Pedagógicas do aluno
- Botão de acesso direto ao Parecer Descritivo
- Resumo Pedagógico gerado via Inteligência Artificial (Google Gemini), que sintetiza as observações registradas em um texto objetivo e conciso
- Histórico do aluno com registros de semestres anteriores

**Quem pode usar:** Professor (alunos de suas turmas), Coordenador (todos os alunos) e Administrador.

---

## 5. Observações Pedagógicas por Eixo

Este módulo é o coração do registro pedagógico na Pedagogia Florença. As observações são organizadas em seis eixos temáticos e estruturadas por semestre, permitindo ao professor documentar o desenvolvimento de cada criança de forma detalhada e sistematizada. O sistema possui salvamento automático para evitar perda de dados.

**Como acessar:** Menu lateral > **Turmas** > selecionar turma > selecionar aluno > **Observações**, ou diretamente pelo perfil do aluno.

**Funcionalidades principais:**

- Seis eixos de observação: **Geral**, **Movimento**, **Manual**, **Musical**, **Contos** e **PCA**
- Organização semestral com separação entre 1.º e 2.º semestre
- Salvamento automático em tempo real durante a digitação, sem necessidade de clicar em botão de salvar
- Finalização da observação com bloqueio automático de edição, garantindo a integridade do registro
- Coordenador possui permissão para reabrir observações finalizadas quando necessária a revisão
- Histórico completo de alterações preservado no sistema

**Quem pode usar:** Professor (registro e edição), Coordenador (visualização, reabertura para revisão) e Administrador.

---

## 6. Parecer Descritivo

O Parecer Descritivo é o documento formal que compila as observações pedagógicas de cada aluno em um texto narrativo e institucional. O sistema gera o parecer automaticamente a partir das observações finalizadas e oferece recursos de correção textual via Inteligência Artificial, além de exportação em formato PDF com layout profissional.

**Como acessar:** Menu lateral > **Pareceres**, ou pelo perfil do aluno > botão **Parecer Descritivo**.

**Funcionalidades principais:**

- Geração automática a partir da observação pedagógica finalizada
- Compilação inteligente dos textos dos seis eixos em um documento unificado
- Correção ortográfica e gramatical assistida por Inteligência Artificial
- Inclusão de até 3 fotos por eixo, com campos para legendas descritivas
- Exportação em PDF com layout institucional de 7 páginas:
  - Capa com dados do aluno e da instituição
  - Texto institucional da Pedagogia Florença
  - Narrativa pedagógica individual
  - Páginas dedicadas a cada eixo com fotos e descrições
- Fluxo de aprovação integrado (Professor → Coordenador)

**Quem pode usar:** Professor (elaboração), Coordenador (revisão e aprovação) e Administrador.

---

## 7. Portfólio da Turma

O Portfólio é um documento coletivo que registra as experiências e realizações de uma turma ao longo do semestre. Combina textos institucionais fixos da Pedagogia Florença com contribuições personalizadas da professora, organizados por eixos de atividade e acompanhados de registros fotográficos. O resultado é um documento extenso e visualmente rico, exportável em PDF.

**Como acessar:** Menu lateral > **Portfólios**.

**Funcionalidades principais:**

- Documento coletivo organizado por turma e semestre
- Textos fixos institucionais da Pedagogia Florença inseridos automaticamente
- Campo para mensagem personalizada da professora, com correção textual via Inteligência Artificial
- Cinco eixos de atividade, cada um com campo de descrição e espaço para fotos representativas
- Exportação em PDF com layout institucional de 14 ou mais páginas
- Fluxo de aprovação integrado (Professor → Coordenador)

**Quem pode usar:** Professor (elaboração), Coordenador (revisão e aprovação) e Administrador.

---

## 8. Banco de Imagens

O Banco de Imagens oferece um repositório organizado para armazenamento e gestão dos registros fotográficos das atividades pedagógicas. As imagens são organizadas por turma, com pastas individuais para cada aluno e uma pasta coletiva para registros da turma.

**Como acessar:** Menu lateral > **Banco de Imagens**.

**Funcionalidades principais:**

- Organização hierárquica: turma → pasta coletiva da turma + pastas individuais por aluno
- Upload múltiplo de imagens com redimensionamento automático para otimização de armazenamento
- Legendas editáveis para cada imagem, facilitando a identificação e a contextualização dos registros
- Possibilidade de mover imagens entre pastas (da pasta coletiva para a individual do aluno, por exemplo)
- Integração com o Parecer Descritivo e o Portfólio para seleção de fotos

**Quem pode usar:** Professor (upload e gestão das imagens de suas turmas), Coordenador (visualização) e Administrador.

---

## 9. Planejamento Pedagógico

O módulo de Planejamento Pedagógico permite ao professor estruturar e registrar suas atividades de forma organizada, com templates personalizáveis, calendário mensal e rotina diária. O sistema conta com campos condicionais inteligentes e um fluxo de envio e registro para acompanhamento pela coordenação.

**Como acessar:** Menu lateral > **Planejamento**.

**Funcionalidades principais:**

- Templates personalizáveis com seções e campos configuráveis
- Campos condicionais: a seleção de um eixo de atividade exibe automaticamente os objetivos específicos correspondentes
- Calendário mensal com visualização por semana, permitindo planejar atividades ao longo do mês
- Rotina diária detalhada: definição de horários e atividades para cada dia da semana (segunda a sexta-feira)
- Fluxo de estados: **Rascunho** → **Enviado** → **Registrado**
- Histórico de planejamentos anteriores para consulta e referência

**Quem pode usar:** Professor (elaboração), Coordenador (acompanhamento e registro) e Administrador.

---

## 10. Material de Apoio

O Material de Apoio funciona como um repositório digital de documentos pedagógicos, organizado em pastas hierárquicas que seguem a estrutura da Pedagogia Florença. Professores e coordenadores podem consultar e baixar materiais de referência para o planejamento e a execução das atividades.

**Como acessar:** Menu lateral > **Material de Apoio**.

**Funcionalidades principais:**

- Repositório organizado em pastas hierárquicas
- Estrutura de categorias:
  - **Eixos de Atividades:** Manuais, Musicais, Contos e Movimento
  - **Centros de Aprendizagem**
  - **Famílias de Brinquedos**
- Upload de PDFs e documentos diversos
- Download de materiais para uso offline
- Navegação intuitiva entre pastas e subpastas

**Quem pode usar:** Professor e Coordenador (consulta e download), Administrador (gestão completa do repositório).

---

## 11. Sistema de Notificações

O sistema de notificações mantém todos os usuários informados sobre eventos relevantes em tempo real. Um ícone de sino no cabeçalho da plataforma exibe um indicador numérico (badge) com a quantidade de notificações não lidas, e um painel dropdown permite a leitura rápida sem sair da página atual.

**Como acessar:** Clicar no ícone de **sino** localizado no cabeçalho superior direito da plataforma.

**Funcionalidades principais:**

- Ícone de sino com badge indicando o número de notificações pendentes
- Painel dropdown com listagem das notificações mais recentes
- Tipos de notificação:
  - Finalização de documentos (observações, pareceres, portfólios, planejamentos)
  - Solicitação de revisão pela coordenação
  - Reabertura de documentos para edição
- Marcação individual ou em lote como lida
- Redirecionamento direto ao item relacionado ao clicar na notificação

**Quem pode usar:** Professor, Coordenador e Administrador.

---

## 12. Fluxo de Aprovação Professor → Coordenador

O fluxo de aprovação estabelece um processo formal de revisão e validação dos documentos pedagógicos produzidos pelos professores. Este ciclo garante a qualidade e a conformidade dos registros antes de sua conclusão definitiva.

**Como acessar:** O fluxo é acionado automaticamente ao finalizar documentos nos módulos de Pareceres, Portfólios e Planejamentos.

**Funcionalidades principais:**

- Professor finaliza o documento → Coordenadores são notificados automaticamente
- Coordenador revisa o documento e pode:
  - **Aprovar:** o documento é registrado como concluído
  - **Solicitar revisão:** o documento é reaberto com notas de orientação → Professor é notificado
- O ciclo de revisão pode se repetir quantas vezes forem necessárias até a aprovação final
- Aplica-se aos seguintes módulos:
  - Pareceres Descritivos
  - Portfólios da Turma
  - Planejamentos Pedagógicos
- Registro completo do histórico de interações entre professor e coordenador

**Quem pode usar:** Professor (envio e correção) e Coordenador (revisão e aprovação).

---

## 13. Cursos e Formação (Visão do Aluno)

O módulo de Cursos e Formação oferece uma experiência completa de aprendizado online para os profissionais da instituição. Cada usuário possui um dashboard individual com indicadores de progresso, e o sistema acompanha a conclusão de módulos e aulas até a emissão de certificado digital.

**Como acessar:** Menu lateral > **Cursos**.

**Funcionalidades principais:**

- Dashboard do aluno com visão consolidada do progresso em todos os cursos matriculados
- Módulos organizados com indicadores de status: **Não iniciado**, **Em andamento** e **Concluído**
- Indicadores visuais nas aulas (ícones e cores) para fácil identificação do andamento
- Acompanhamento de progresso em tempo real (percentual de conclusão)
- Certificado digital emitido automaticamente ao completar todas as aulas e avaliações do curso

**Quem pode usar:** Professor (como aluno dos cursos), Coordenador e Administrador.

---

## 14. Relatórios

O módulo de Relatórios oferece à coordenação e à administração uma visão analítica do desempenho pedagógico e operacional da plataforma. Quatro tipos de relatórios estão disponíveis, cada um com foco em uma dimensão específica do acompanhamento.

**Como acessar:** Menu lateral > **Relatórios**.

**Funcionalidades principais:**

- **Relatório Geral:** métricas consolidadas da instituição, incluindo totais de turmas, alunos, professores e indicadores de produtividade pedagógica
- **Notas Baixas:** identificação de alunos com desempenho abaixo do esperado nas avaliações dos cursos, permitindo intervenção pedagógica direcionada
- **Video Tracking:** acompanhamento detalhado do consumo de videoaulas — horas assistidas, número de sessões, ranking de engajamento por usuário
- **Relatório de Cursos:** progresso detalhado por curso com percentuais de conclusão, com opção de exportação em formato CSV para análise externa

**Quem pode usar:** Coordenador e Administrador.

---

## 15. Central de Ajuda

A Central de Ajuda é um recurso completo de suporte e orientação integrado à plataforma. Com artigos detalhados, perguntas frequentes e busca inteligente, a Central permite que os usuários encontrem respostas e aprendam a utilizar cada funcionalidade de forma autônoma.

**Como acessar:** Menu lateral > **Ajuda**, ou pelo ícone de interrogação presente no cabeçalho da plataforma.

**Funcionalidades principais:**

- 12 categorias temáticas cobrindo todos os módulos da plataforma
- 42 artigos detalhados com instruções passo a passo, dicas e tabelas de referência
- 40 perguntas frequentes com respostas objetivas
- Busca em tempo real com resultados instantâneos à medida que o usuário digita
- Tours interativos por página, que guiam o usuário visualmente pelos elementos da interface

**Quem pode usar:** Professor, Coordenador e Administrador.

---

## Questões Resolvidas

Durante o processo de desenvolvimento, todas as **21 questões em aberto** identificadas no documento original de requisitos foram analisadas, discutidas e resolvidas. As decisões tomadas foram incorporadas diretamente na implementação dos módulos correspondentes, garantindo que a plataforma reflita integralmente as necessidades levantadas pela equipe pedagógica.

---

## Acesso e Suporte

A plataforma está disponível no endereço:

> **http://154.38.189.82:8080/**

Para dúvidas sobre o uso das funcionalidades, recomendamos:

1. **Central de Ajuda** — disponível diretamente na plataforma, com artigos detalhados e perguntas frequentes sobre cada módulo.
2. **Tours Interativos** — presentes em todas as páginas principais, os tours guiam o usuário pelos elementos da interface de forma visual e prática. Basta clicar no botão de tour disponível na página.

---

*Equipe de Desenvolvimento Hansen Educacional*
*Março de 2026*
