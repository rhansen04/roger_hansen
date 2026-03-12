-- Migration 026: Criar tabela de pareceres descritivos
-- Fase 2 do overhaul pedagogico (T-2.1 a T-2.8)

CREATE TABLE IF NOT EXISTS descriptive_reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL,
    classroom_id INT NULL,
    observation_id INT NULL,
    semester TINYINT NOT NULL COMMENT '1 ou 2',
    year INT NOT NULL,
    cover_photo_url VARCHAR(500) NULL,
    intro_text TEXT NULL COMMENT 'Texto fixo institucional',
    student_text TEXT NULL COMMENT 'Texto compilado das observacoes',
    student_text_edited TEXT NULL COMMENT 'Versao editada pelo professor',
    axis_photos JSON NULL COMMENT 'Fotos por eixo: {axis: [{url, caption}]}',
    status ENUM('draft', 'finalized', 'revision_requested') DEFAULT 'draft',
    revision_notes TEXT NULL,
    finalized_at DATETIME NULL,
    finalized_by INT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (classroom_id) REFERENCES classrooms(id) ON DELETE SET NULL,
    FOREIGN KEY (observation_id) REFERENCES observations(id) ON DELETE SET NULL,
    INDEX idx_dr_student (student_id),
    INDEX idx_dr_semester (semester, year),
    INDEX idx_dr_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Pareceres descritivos';
