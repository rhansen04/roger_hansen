CREATE TABLE IF NOT EXISTS course_materials (
    id INT AUTO_INCREMENT PRIMARY KEY,
    course_id INT NOT NULL COMMENT 'Curso ao qual pertence',
    section_id INT NULL COMMENT 'Seção específica (opcional)',
    title VARCHAR(255) NOT NULL COMMENT 'Título do material',
    description TEXT COMMENT 'Descrição do material',
    file_type ENUM('pdf','excel','image','video','other') NOT NULL DEFAULT 'other',
    file_name VARCHAR(255) NOT NULL COMMENT 'Nome original do arquivo',
    file_path VARCHAR(500) NOT NULL COMMENT 'Caminho relativo em storage/',
    file_size INT DEFAULT 0 COMMENT 'Tamanho em bytes',
    sort_order INT DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1,
    created_by INT NOT NULL COMMENT 'Admin que fez upload',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (course_id) REFERENCES courses(id) ON DELETE CASCADE,
    FOREIGN KEY (section_id) REFERENCES sections(id) ON DELETE SET NULL,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_materials_course (course_id),
    INDEX idx_materials_section (section_id),
    INDEX idx_materials_active (is_active)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Materiais de apoio dos cursos (PDFs, Excel, imagens, vídeos)';
