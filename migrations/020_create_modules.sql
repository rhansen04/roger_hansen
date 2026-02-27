-- migrations/020_create_modules.sql
-- Adiciona camada de Módulos: Curso → Módulos → Seções → Lições

CREATE TABLE IF NOT EXISTS modules (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL COMMENT 'ID do curso',
    title VARCHAR(255) NOT NULL COMMENT 'Título do módulo',
    description TEXT COMMENT 'Descrição do módulo',
    sort_order INT DEFAULT 0 COMMENT 'Ordem de exibição',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    INDEX idx_modules_course (course_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Tabela de módulos dos cursos';

-- Adicionar module_id nullable na tabela sections
ALTER TABLE sections ADD COLUMN module_id INT NULL COMMENT 'ID do módulo (opcional)' AFTER course_id;
ALTER TABLE sections ADD CONSTRAINT fk_sections_module FOREIGN KEY (module_id) REFERENCES modules(id) ON DELETE SET NULL;
ALTER TABLE sections ADD INDEX idx_sections_module (module_id);
