CREATE TABLE IF NOT EXISTS course_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL,
    lesson_id INT NULL COMMENT 'Pergunta específica de uma lição (opcional)',
    parent_id INT NULL COMMENT 'NULL = pergunta raiz; ID = resposta a outra mensagem',
    user_id INT NOT NULL COMMENT 'Quem enviou',
    message TEXT NOT NULL,
    is_read TINYINT(1) DEFAULT 0 COMMENT 'Lido pelo professor?',
    is_answered TINYINT(1) DEFAULT 0 COMMENT 'Pergunta respondida?',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE SET NULL,
    FOREIGN KEY (parent_id) REFERENCES course_messages(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_msg_course (course_id),
    INDEX idx_msg_lesson (lesson_id),
    INDEX idx_msg_parent (parent_id),
    INDEX idx_msg_user (user_id),
    INDEX idx_msg_read (is_read)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Perguntas e respostas dos cursos (aluno -> professor)';
