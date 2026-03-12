-- Migration 025: Adicionar eixos pedagogicos e campos de semestre as observacoes
-- Fase 1 do overhaul de Observacoes (T-1.1 a T-1.5)

ALTER TABLE observations
    ADD COLUMN semester TINYINT NULL COMMENT '1 ou 2',
    ADD COLUMN year INT NULL COMMENT 'Ano letivo',
    ADD COLUMN status ENUM('in_progress', 'finalized') DEFAULT 'in_progress',
    ADD COLUMN observation_general TEXT NULL COMMENT 'Observacao Geral',
    ADD COLUMN axis_movement TEXT NULL COMMENT 'Eixo Atividade de Movimento',
    ADD COLUMN axis_manual TEXT NULL COMMENT 'Eixo Atividade Manual',
    ADD COLUMN axis_music TEXT NULL COMMENT 'Eixo Atividade Musical',
    ADD COLUMN axis_stories TEXT NULL COMMENT 'Eixo Atividade de Contos',
    ADD COLUMN axis_pca TEXT NULL COMMENT 'Eixo Programa Comunicacao Ativa',
    ADD COLUMN finalized_at DATETIME NULL,
    ADD COLUMN finalized_by INT NULL,
    ADD INDEX idx_observations_semester (semester, year),
    ADD INDEX idx_observations_status (status);
