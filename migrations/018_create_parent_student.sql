-- 018_create_parent_student.sql
-- Adiciona role 'parent' e tabela de vinculo pai/aluno

-- Add 'parent' to users role enum
ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'professor', 'coordenador', 'student', 'parent') DEFAULT 'student';

-- Link parents to their children (students)
CREATE TABLE IF NOT EXISTS parent_student (
    id INT AUTO_INCREMENT PRIMARY KEY,
    parent_id INT NOT NULL COMMENT 'ID do usuario pai/mae',
    student_user_id INT NOT NULL COMMENT 'ID do usuario aluno (role=student)',
    relationship VARCHAR(50) DEFAULT 'parent' COMMENT 'pai, mae, responsavel',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_link (parent_id, student_user_id),
    FOREIGN KEY (parent_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (student_user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_parent (parent_id),
    INDEX idx_student (student_user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Vinculo entre pais/responsaveis e alunos';
