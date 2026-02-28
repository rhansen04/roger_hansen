-- Migration 023: Add field dependency support
-- Allows fields to depend on another field's value (e.g., show/hide based on radio selection)

ALTER TABLE planning_template_fields
  ADD COLUMN depends_on_field_id INT NULL,
  ADD COLUMN depends_on_value VARCHAR(255) NULL;

-- Seed: Link existing Section 4 (Objetivos) fields to the Section 2 radio (Eixo da Vivência)
-- This UPDATE uses subqueries to find the correct field IDs dynamically

-- Set depends_on_field_id for all fields in "Objetivos de Aprendizagem" sections
-- that have eixo-related labels, pointing to the "Eixo da Vivência" radio field
UPDATE planning_template_fields f
JOIN planning_template_sections s ON f.section_id = s.id
SET f.depends_on_field_id = (
    SELECT ctrl.id
    FROM planning_template_fields ctrl
    JOIN planning_template_sections cs ON ctrl.section_id = cs.id
    WHERE cs.template_id = s.template_id
      AND ctrl.field_type = 'radio'
      AND ctrl.label LIKE '%Eixo%Viv%'
    LIMIT 1
),
f.depends_on_value = CASE
    WHEN f.label LIKE '%Manuais%' THEN 'Manual'
    WHEN f.label LIKE '%Musicais%' THEN 'Musical'
    WHEN f.label LIKE '%Contos%' THEN 'Contos'
    WHEN f.label LIKE '%Movimento%' THEN 'Movimento'
    WHEN f.label LIKE '%PCA%' OR f.label LIKE '%Comunicação Ativa%' THEN 'PCA'
    ELSE NULL
END
WHERE s.title LIKE '%Objetivos%Aprendizagem%'
  AND (
    f.label LIKE '%Manuais%'
    OR f.label LIKE '%Musicais%'
    OR f.label LIKE '%Contos%'
    OR f.label LIKE '%Movimento%'
    OR f.label LIKE '%PCA%'
    OR f.label LIKE '%Comunicação Ativa%'
  );
