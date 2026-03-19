-- Migration 033: Make observations.content nullable
-- The createWithAxes() method doesn't use content/title columns (legacy).
-- Making them nullable prevents INSERT failures.

ALTER TABLE observations MODIFY COLUMN content TEXT NULL DEFAULT NULL;
ALTER TABLE observations MODIFY COLUMN title VARCHAR(255) NULL DEFAULT NULL;
