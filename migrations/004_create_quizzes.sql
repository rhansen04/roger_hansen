-- migrations/004_create_quizzes.sql

CREATE TABLE IF NOT EXISTS quizzes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    section_id INT NOT NULL COMMENT 'ID da seção',
    lesson_id INT COMMENT 'ID da lição associada (opcional)',
    title VARCHAR(255) NOT NULL COMMENT 'Título da avaliação',
    description TEXT COMMENT 'Descrição da avaliação',
    passing_score INT DEFAULT 70 COMMENT 'Nota mínima para passar (%)',
    time_limit_minutes INT COMMENT 'Tempo limite em minutos',
    attempts_allowed INT DEFAULT 3 COMMENT 'Número de tentativas permitidas',
    sort_order INT DEFAULT 0 COMMENT 'Ordem de exibição',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (section_id) REFERENCES sections(id) ON DELETE CASCADE,
    INDEX idx_quizzes_section (section_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Tabela de quizzes/avaliações';
