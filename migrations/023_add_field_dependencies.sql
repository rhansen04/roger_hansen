-- Migration 023: Add field dependency support
-- Allows fields to depend on another field's value (e.g., show/hide based on radio selection)

ALTER TABLE planning_template_fields
  ADD COLUMN depends_on_field_id INT NULL,
  ADD COLUMN depends_on_value VARCHAR(255) NULL;

-- Seed: Link existing Section 4 (Objetivos) fields to the Section 2 radio (Eixo da Vivência)
-- MySQL doesn't allow subquery on same table being updated, so use a temp table

CREATE TEMPORARY TABLE tmp_ctrl AS
SELECT f.id AS ctrl_id, s.template_id
FROM planning_template_fields f
JOIN planning_template_sections s ON f.section_id = s.id
WHERE f.field_type = 'radio' AND f.label LIKE '%Eixo%Viv%';

UPDATE planning_template_fields f
JOIN planning_template_sections s ON f.section_id = s.id
JOIN tmp_ctrl t ON t.template_id = s.template_id
SET f.depends_on_field_id = t.ctrl_id,
    f.depends_on_value = CASE
        WHEN f.label LIKE '%Manuais%' THEN 'Manual'
        WHEN f.label LIKE '%Musicais%' THEN 'Musical'
        WHEN f.label LIKE '%Contos%' THEN 'Contos'
        WHEN f.label LIKE '%Movimento%' THEN 'Movimento'
        WHEN f.label LIKE '%PCA%' OR f.label LIKE '%Comunica%Ativa%' THEN 'PCA'
    END
WHERE s.title LIKE '%Objetivos%Aprendizagem%'
  AND (f.label LIKE '%Manuais%' OR f.label LIKE '%Musicais%' OR f.label LIKE '%Contos%'
       OR f.label LIKE '%Movimento%' OR f.label LIKE '%PCA%' OR f.label LIKE '%Comunica%Ativa%');

DROP TEMPORARY TABLE tmp_ctrl;
