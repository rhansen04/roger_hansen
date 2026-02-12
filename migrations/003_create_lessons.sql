-- migrations/003_create_lessons.sql

CREATE TABLE IF NOT EXISTS lessons (
    id INT AUTO_INCREMENT PRIMARY KEY,
    section_id INT NOT NULL COMMENT 'ID da seção',
    title VARCHAR(255) NOT NULL COMMENT 'Título da lição',
    description TEXT COMMENT 'Descrição da lição',
    content TEXT COMMENT 'Conteúdo da lição (HTML)',
    video_url VARCHAR(500) COMMENT 'URL do vídeo (YouTube, Vimeo)',
    video_duration INT DEFAULT 0 COMMENT 'Duração do vídeo em segundos',
    material_file VARCHAR(255) COMMENT 'Arquivo de material (PDF, etc.)',
    sort_order INT DEFAULT 0 COMMENT 'Ordem de exibição',
    duration_minutes INT DEFAULT 0 COMMENT 'Duração em minutos',
    is_preview TINYINT(1) DEFAULT 0 COMMENT 'Disponível como preview?',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (section_id) REFERENCES sections(id) ON DELETE CASCADE,
    INDEX idx_lessons_section (section_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Tabela de lições dos cursos';
