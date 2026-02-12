-- Script de dados de exemplo para testes do CRUD de Escolas
-- Execute após a migration add_school_fields.sql

USE hansen_educacional;

-- Limpar dados existentes (CUIDADO: remove todas as escolas)
-- DELETE FROM students WHERE school_id IN (SELECT id FROM schools);
-- DELETE FROM schools;

-- Inserir escolas de exemplo
INSERT INTO schools (name, city, state, address, contact_person, phone, email, contract_start_date, contract_end_date, status) VALUES
('Colégio São Paulo', 'São Paulo', 'SP', 'Av. Paulista, 1000 - Bela Vista - CEP 01310-100', 'Maria Silva', '(11) 3333-4444', 'contato@colegiosp.com.br', '2024-01-01', '2025-12-31', 'active'),
('Escola Municipal Rio de Janeiro', 'Rio de Janeiro', 'RJ', 'Rua das Laranjeiras, 500 - Laranjeiras - CEP 22240-000', 'João Santos', '(21) 2222-3333', 'escola@rj.gov.br', '2023-06-01', '2025-06-30', 'active'),
('Instituto Educacional Minas', 'Belo Horizonte', 'MG', 'Av. Afonso Pena, 1500 - Centro - CEP 30130-000', 'Ana Paula', '(31) 3456-7890', 'contato@institutomg.com.br', '2024-02-01', '2026-02-28', 'active'),
('Colégio Curitiba', 'Curitiba', 'PR', 'Rua XV de Novembro, 800 - Centro - CEP 80020-000', 'Carlos Eduardo', '(41) 3234-5678', 'secretaria@colegioctb.com.br', '2023-08-01', '2024-08-31', 'inactive'),
('Escola Bahia', 'Salvador', 'BA', 'Av. Sete de Setembro, 2000 - Vitória - CEP 40080-000', 'Fernanda Lima', '(71) 3345-6789', 'contato@escolabahia.com.br', '2024-03-01', '2025-03-31', 'active'),
('Centro Educacional Brasília', 'Brasília', 'DF', 'SQS 308 Bloco A - Asa Sul - CEP 70355-010', 'Roberto Costa', '(61) 3456-7890', 'ceb@educacional.com.br', '2024-01-15', '2025-12-31', 'active'),
('Escola Estadual Porto Alegre', 'Porto Alegre', 'RS', 'Av. Ipiranga, 1500 - Centro - CEP 90160-000', 'Patricia Souza', '(51) 3234-5678', 'escola@rs.gov.br', '2023-09-01', '2024-09-30', 'inactive'),
('Colégio Pernambuco', 'Recife', 'PE', 'Rua da Aurora, 300 - Santo Amaro - CEP 50050-000', 'Marcos Paulo', '(81) 3456-7890', 'contato@colegiopernambuco.com.br', '2024-02-15', '2025-12-31', 'active'),
('Instituto Santa Catarina', 'Florianópolis', 'SC', 'Av. Beira Mar Norte, 1000 - Centro - CEP 88015-900', 'Julia Martins', '(48) 3345-6789', 'instituto@santacatarina.com.br', '2024-01-01', '2026-01-01', 'active'),
('Escola Ceará', 'Fortaleza', 'CE', 'Av. Beira Mar, 2500 - Meireles - CEP 60165-121', 'Lucas Ferreira', '(85) 3234-5678', 'contato@escolaceara.com.br', '2024-04-01', '2025-04-30', 'active');

-- Inserir alguns alunos vinculados às escolas
INSERT INTO students (name, birth_date, school_id) VALUES
('Pedro Henrique Silva', '2014-05-15', 1),
('Ana Clara Souza', '2015-08-20', 1),
('João Victor Santos', '2013-12-10', 1),
('Maria Eduarda Lima', '2014-03-25', 2),
('Gabriel Costa', '2015-11-30', 2),
('Sofia Oliveira', '2014-07-18', 3),
('Rafael Alves', '2015-02-14', 3),
('Isabela Pereira', '2014-09-05', 5),
('Lucas Rodrigues', '2015-04-22', 5),
('Beatriz Santos', '2014-06-12', 5),
('Matheus Ferreira', '2015-01-08', 6),
('Larissa Costa', '2014-10-30', 6),
('Guilherme Souza', '2015-07-25', 8),
('Valentina Lima', '2014-11-18', 8),
('Bruno Oliveira', '2015-03-14', 9),
('Alice Pereira', '2014-08-07', 9),
('Enzo Gabriel', '2015-05-20', 10),
('Helena Silva', '2014-12-03', 10);

-- Verificar dados inseridos
SELECT
    s.id,
    s.name,
    s.city,
    s.state,
    s.status,
    COUNT(st.id) as total_alunos
FROM schools s
LEFT JOIN students st ON s.id = st.school_id
GROUP BY s.id
ORDER BY s.name;

-- Estatísticas
SELECT
    'Total de Escolas' as Metrica,
    COUNT(*) as Valor
FROM schools
UNION ALL
SELECT
    'Escolas Ativas',
    COUNT(*)
FROM schools
WHERE status = 'active'
UNION ALL
SELECT
    'Escolas Inativas',
    COUNT(*)
FROM schools
WHERE status = 'inactive'
UNION ALL
SELECT
    'Total de Alunos',
    COUNT(*)
FROM students;
