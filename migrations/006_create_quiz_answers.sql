-- migrations/006_create_quiz_answers.sql

CREATE TABLE IF NOT EXISTS quiz_answers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question_id INT NOT NULL COMMENT 'ID da questão',
    answer_text TEXT NOT NULL COMMENT 'Texto da resposta',
    is_correct TINYINT(1) DEFAULT 0 COMMENT 'É a resposta correta?',
    sort_order INT DEFAULT 0 COMMENT 'Ordem de exibição',
    FOREIGN KEY (question_id) REFERENCES quiz_questions(id) ON DELETE CASCADE,
    INDEX idx_answers_question (question_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Tabela de respostas das questões';
