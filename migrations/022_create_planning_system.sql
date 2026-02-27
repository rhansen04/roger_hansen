-- migrations/022_create_planning_system.sql
-- Sistema de planejamento pedagógico: templates + submissions

-- Templates de planejamento
CREATE TABLE IF NOT EXISTS planning_templates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL COMMENT 'Ex: Planejamento Quinzenal PFI',
    description TEXT COMMENT 'Descrição do template',
    age_group ENUM('0-3','3-6','all') NOT NULL DEFAULT 'all' COMMENT 'Faixa etária alvo',
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    sort_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Templates de planejamento pedagógico';

-- Seções do template
CREATE TABLE IF NOT EXISTS planning_template_sections (
    id INT AUTO_INCREMENT PRIMARY KEY,
    template_id INT NOT NULL,
    title VARCHAR(255) NOT NULL COMMENT 'Ex: Identificação, Eixo da Vivência',
    description TEXT COMMENT 'Instrução para o professor',
    section_type VARCHAR(50) NOT NULL DEFAULT 'default' COMMENT 'Tipo visual da seção',
    sort_order INT NOT NULL DEFAULT 0,
    is_registration TINYINT(1) NOT NULL DEFAULT 0 COMMENT '1=seção preenchida pós-vivência',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (template_id) REFERENCES planning_templates(id) ON DELETE CASCADE,
    INDEX idx_sections_template (template_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Seções dos templates de planejamento';

-- Campos de cada seção
CREATE TABLE IF NOT EXISTS planning_template_fields (
    id INT AUTO_INCREMENT PRIMARY KEY,
    section_id INT NOT NULL,
    field_type ENUM('text','textarea','checkbox','radio','select','date','checklist_group') NOT NULL DEFAULT 'text',
    label VARCHAR(255) NOT NULL,
    description TEXT COMMENT 'Texto de ajuda',
    options_json JSON COMMENT 'Opções para checkbox/radio/select/checklist_group',
    is_required TINYINT(1) NOT NULL DEFAULT 0,
    sort_order INT NOT NULL DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (section_id) REFERENCES planning_template_sections(id) ON DELETE CASCADE,
    INDEX idx_fields_section (section_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Campos dos templates de planejamento';

-- Planejamentos preenchidos (submissions)
CREATE TABLE IF NOT EXISTS planning_submissions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    template_id INT NOT NULL,
    teacher_id INT NOT NULL,
    classroom_id INT NOT NULL,
    period_start DATE NOT NULL COMMENT 'Início da quinzena',
    period_end DATE NOT NULL COMMENT 'Fim da quinzena',
    status ENUM('draft','submitted','registered') NOT NULL DEFAULT 'draft',
    submitted_at TIMESTAMP NULL,
    registered_at TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (template_id) REFERENCES planning_templates(id) ON DELETE RESTRICT,
    FOREIGN KEY (teacher_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (classroom_id) REFERENCES classrooms(id) ON DELETE CASCADE,
    INDEX idx_submissions_teacher (teacher_id),
    INDEX idx_submissions_classroom (classroom_id),
    INDEX idx_submissions_status (status),
    INDEX idx_submissions_period (period_start, period_end)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Planejamentos preenchidos pelos professores';

-- Respostas dos campos
CREATE TABLE IF NOT EXISTS planning_submission_answers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    submission_id INT NOT NULL,
    field_id INT NOT NULL,
    section_id INT NOT NULL,
    answer_text TEXT COMMENT 'Resposta texto',
    answer_json JSON COMMENT 'Resposta estruturada (checkboxes, etc)',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (submission_id) REFERENCES planning_submissions(id) ON DELETE CASCADE,
    FOREIGN KEY (field_id) REFERENCES planning_template_fields(id) ON DELETE CASCADE,
    FOREIGN KEY (section_id) REFERENCES planning_template_sections(id) ON DELETE CASCADE,
    INDEX idx_answers_submission (submission_id),
    UNIQUE KEY uk_submission_field (submission_id, field_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Respostas dos planejamentos';
