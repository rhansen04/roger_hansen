-- migrations/005_create_quiz_questions.sql

CREATE TABLE IF NOT EXISTS quiz_questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_id INT NOT NULL COMMENT 'ID do quiz',
    question_text TEXT NOT NULL COMMENT 'Texto da questão',
    question_type ENUM('multiple_choice', 'true_false', 'short_answer') DEFAULT 'multiple_choice' COMMENT 'Tipo de questão',
    sort_order INT DEFAULT 0 COMMENT 'Ordem de exibição',
    points INT DEFAULT 1 COMMENT 'Pontuação da questão',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE,
    INDEX idx_questions_quiz (quiz_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Tabela de questões dos quizzes';
