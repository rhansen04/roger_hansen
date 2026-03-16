# Retorno — Revisão 02 da Plataforma

> Data: 16/03/2026
> Referência: Feedback da Larissa (coordenadora pedagógica)

---

Todas as 9 solicitações foram implementadas.

## Correções realizadas

### 1. Simulador de Perfil (Admin)
Admins agora podem **simular a visão de Professor ou Coordenador** sem trocar de conta. Na barra superior aparece o botão **"Simular Perfil"** com as opções. Ao ativar, um banner amarelo indica qual perfil está sendo simulado, e o menu lateral e dashboard mudam de acordo. Para voltar, basta clicar em **"Voltar para Admin"**.
**Importante:** a simulação afeta apenas a visualização — todas as operações de escrita continuam usando o perfil real (admin).

### 2. Turmas — Link para gerenciar alunos
Na tela de **edição de turma**, agora aparece um card com o botão **"Gerenciar Alunos"** que leva direto para a página da turma com a lista de alunos e opção de adicionar/remover.

### 3. Observações — Botão visível para Coordenador
O botão **"+ Nova Observação"** agora aparece para todos os perfis, incluindo coordenadores. Coordenadores podem criar e salvar observações normalmente.

### 4. Observações — Atalhos dos 6 eixos
Na listagem de observações, foram adicionados **6 cards coloridos** representando os eixos pedagógicos (Obs. Geral, Movimento, Manual, Musical, Contos, PCA). Ao clicar em um deles, o formulário de nova observação abre já com a aba daquele eixo ativa.

### 5. Parecer Descritivo — Mensagem de erro melhorada
Quando o professor tenta gerar um parecer e não há observações para o aluno/semestre selecionado, agora aparece uma **mensagem clara** explicando o problema e um botão direto para **"Criar Observação"** para aquele aluno.

### 6. Planejamento — Visualização quinzenal por dias
Ao editar um planejamento, a tela agora mostra uma **grade com os dias úteis (seg-sex)** do período. Cada dia aparece como um card com status (vazio, rascunho, preenchido) e é clicável para abrir o formulário daquele dia.

### 7. Planejamento — Card diário ajustado
O formulário de cada dia **não mostra mais a seção "Identificação"** (já definida ao criar o planejamento). O campo "Eixo de Vivências" foi renomeado para **"Eixo de Atividades"** e os eixos aparecem como **botões de seleção horizontal** (toggle) em vez de radio buttons empilhados.

### 8. Planejamento — Botão "Finalizar Planejamento"
Na tela de dias, quando o planejamento está em rascunho, aparece o botão **"Finalizar Planejamento"**. Ao clicar, o status muda para "Enviado" e os coordenadores recebem uma notificação automática.

### 9. Planejamento — Registro Pós-Vivência
Após o planejamento ser finalizado (status "Enviado"), aparece o botão **"Registro Pós-Vivência"**. Ao clicar, abre um formulário separado com os campos de registro do período (Síntese, Execução, Justificativa). Após preencher, o professor pode salvar como rascunho ou finalizar o registro.
