-- migrations/021_create_classrooms.sql
-- Tabela de turmas vinculadas a escola e professor

CREATE TABLE IF NOT EXISTS classrooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    school_id INT NOT NULL COMMENT 'Escola da turma',
    teacher_id INT NOT NULL COMMENT 'Professor responsável',
    name VARCHAR(255) NOT NULL COMMENT 'Nome da turma (ex: Maternal II - Manhã)',
    age_group ENUM('0-3','3-6') NOT NULL COMMENT 'Faixa etária',
    period ENUM('morning','afternoon','full') NOT NULL DEFAULT 'morning' COMMENT 'Período',
    school_year YEAR NOT NULL COMMENT 'Ano letivo',
    status ENUM('active','inactive') NOT NULL DEFAULT 'active',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (school_id) REFERENCES schools(id) ON DELETE CASCADE,
    FOREIGN KEY (teacher_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_classrooms_school (school_id),
    INDEX idx_classrooms_teacher (teacher_id),
    INDEX idx_classrooms_year (school_year)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Turmas pedagógicas';
