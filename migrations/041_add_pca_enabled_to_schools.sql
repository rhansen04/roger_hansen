-- Migration 041: configuracao do PCA por escola

ALTER TABLE schools
    ADD COLUMN IF NOT EXISTS pca_enabled TINYINT(1) NOT NULL DEFAULT 0
    COMMENT '1 = PCA habilitado para a escola; 0 = desabilitado';

CREATE INDEX IF NOT EXISTS idx_schools_pca_enabled ON schools(pca_enabled);
