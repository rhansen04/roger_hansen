-- migrations/032_create_planning_daily_entries.sql
-- Entradas diarias para planejamento quinzenal

CREATE TABLE IF NOT EXISTS planning_daily_entries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    submission_id INT NOT NULL,
    entry_date DATE NOT NULL,
    status ENUM('empty','draft','filled') NOT NULL DEFAULT 'empty',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (submission_id) REFERENCES planning_submissions(id) ON DELETE CASCADE,
    UNIQUE KEY uk_submission_date (submission_id, entry_date),
    INDEX idx_daily_submission (submission_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Entradas diarias do planejamento quinzenal';

-- Adicionar coluna daily_entry_id na tabela de respostas
ALTER TABLE planning_submission_answers
    ADD COLUMN daily_entry_id INT NULL AFTER section_id,
    ADD FOREIGN KEY fk_answer_daily_entry (daily_entry_id) REFERENCES planning_daily_entries(id) ON DELETE CASCADE;

-- Atualizar unique key para permitir respostas por dia
ALTER TABLE planning_submission_answers
    DROP INDEX uk_submission_field,
    ADD UNIQUE KEY uk_submission_field_daily (submission_id, field_id, daily_entry_id);
