-- =====================================================
-- Seed de Dados de Demonstração - Observações Pedagógicas
-- Hansen Educacional
-- Data: 10/02/2026
-- =====================================================

-- IMPORTANTE: Execute este script apenas se quiser adicionar dados de teste
-- Este script pressupõe que você já tem:
-- - Usuários cadastrados (tabela users)
-- - Escolas cadastradas (tabela schools)
-- - Alunos cadastrados (tabela students)

-- =====================================================
-- Observações de Exemplo
-- =====================================================

-- Observação 1 - Comportamento
INSERT INTO observations (student_id, user_id, type, content, observed_at, created_at, updated_at)
VALUES (
    1,
    1,
    'Comportamento',
    'O aluno demonstrou excelente comportamento durante toda a semana. Participou ativamente das atividades em grupo, ajudou os colegas com dificuldades e mostrou respeito às regras da sala de aula. Destaque para a iniciativa de organizar os materiais ao final da aula.',
    '2026-02-05 10:30:00',
    NOW(),
    NOW()
);

-- Observação 2 - Aprendizado
INSERT INTO observations (student_id, user_id, type, content, observed_at, created_at, updated_at)
VALUES (
    1,
    1,
    'Aprendizado',
    'Progresso significativo em matemática. O aluno dominou o conceito de frações e consegue resolver problemas básicos de adição e subtração de frações com denominadores iguais. Recomenda-se continuar praticando para consolidar o aprendizado.',
    '2026-02-06 14:15:00',
    NOW(),
    NOW()
);

-- Observação 3 - Saúde
INSERT INTO observations (student_id, user_id, type, content, observed_at, created_at, updated_at)
VALUES (
    2,
    1,
    'Saúde',
    'A aluna apresentou dor de cabeça durante a aula de educação física. Foi encaminhada à enfermaria e a responsável foi contatada. Após repouso de 20 minutos, a aluna se sentiu melhor e retornou às atividades. Responsável orientou que a aluna não dormiu bem na noite anterior.',
    '2026-02-07 09:45:00',
    NOW(),
    NOW()
);

-- Observação 4 - Comunicação com Pais
INSERT INTO observations (student_id, user_id, type, content, observed_at, created_at, updated_at)
VALUES (
    1,
    1,
    'Comunicação com Pais',
    'Reunião com a mãe do aluno para discutir o progresso acadêmico. A mãe demonstrou interesse e compromisso em acompanhar as atividades de casa. Foi acordado que o aluno dedicará 30 minutos diários à leitura em casa. A mãe solicitou dicas de livros adequados para a idade.',
    '2026-02-08 16:00:00',
    NOW(),
    NOW()
);

-- Observação 5 - Geral
INSERT INTO observations (student_id, user_id, type, content, observed_at, created_at, updated_at)
VALUES (
    2,
    1,
    'Geral',
    'A aluna ganhou o prêmio de "Aluna Destaque do Mês" por sua dedicação, pontualidade e excelente desempenho acadêmico. Durante a cerimônia, demonstrou timidez mas ficou muito feliz ao receber o certificado. Os pais compareceram e ficaram emocionados.',
    '2026-02-09 15:30:00',
    NOW(),
    NOW()
);

-- Observação 6 - Comportamento (exemplo de intervenção)
INSERT INTO observations (student_id, user_id, type, content, observed_at, created_at, updated_at)
VALUES (
    2,
    1,
    'Comportamento',
    'Ocorreu um conflito durante o recreio entre a aluna e uma colega por conta de um brinquedo. Foi realizada uma conversa mediada entre as duas alunas, onde cada uma pode expressar seus sentimentos. Ambas se desculparam e voltaram a brincar juntas. Os pais de ambas foram informados da situação e da resolução.',
    '2026-02-10 11:00:00',
    NOW(),
    NOW()
);

-- Observação 7 - Aprendizado (dificuldade identificada)
INSERT INTO observations (student_id, user_id, type, content, observed_at, created_at, updated_at)
VALUES (
    1,
    1,
    'Aprendizado',
    'Identificada dificuldade específica em interpretação de texto. O aluno consegue ler com fluência, mas apresenta dificuldade em compreender mensagens implícitas e fazer inferências. Será implementado um plano de apoio com atividades direcionadas. Recomenda-se acompanhamento psicopedagógico.',
    '2026-02-10 13:45:00',
    NOW(),
    NOW()
);

-- =====================================================
-- Verificação dos dados inseridos
-- =====================================================

-- Contar total de observações por tipo
-- SELECT type, COUNT(*) as total FROM observations GROUP BY type;

-- Listar todas as observações com nomes
-- SELECT
--     o.id,
--     s.name as aluno,
--     u.name as professor,
--     o.type as categoria,
--     DATE(o.observed_at) as data,
--     substr(o.content, 1, 50) || '...' as resumo
-- FROM observations o
-- JOIN students s ON o.student_id = s.id
-- JOIN users u ON o.user_id = u.id
-- ORDER BY o.observed_at DESC;

-- =====================================================
-- Notas:
-- - Ajuste os IDs de student_id e user_id conforme seus dados
-- - As datas estão configuradas para a semana de 05-10/02/2026
-- - Use estes exemplos como referência para criar novas observações
-- =====================================================
