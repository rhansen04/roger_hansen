-- migrations/015_create_quiz_attempts.sql

CREATE TABLE IF NOT EXISTS quiz_attempts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quiz_id INT NOT NULL COMMENT 'ID do quiz',
    user_id INT NOT NULL COMMENT 'ID do usuario',
    enrollment_id INT NOT NULL COMMENT 'ID da matricula',
    score DECIMAL(5,2) DEFAULT 0.00 COMMENT 'Nota obtida (%)',
    passed TINYINT(1) DEFAULT 0 COMMENT 'Passou?',
    answers_json TEXT COMMENT 'Respostas do aluno (JSON)',
    started_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    completed_at TIMESTAMP NULL,
    time_spent_seconds INT DEFAULT 0 COMMENT 'Tempo gasto em segundos',
    FOREIGN KEY (quiz_id) REFERENCES quizzes(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (enrollment_id) REFERENCES enrollments(id) ON DELETE CASCADE,
    INDEX idx_quiz_attempts_quiz (quiz_id),
    INDEX idx_quiz_attempts_user (user_id),
    INDEX idx_quiz_attempts_enrollment (enrollment_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Tabela de tentativas de quiz pelos alunos';
