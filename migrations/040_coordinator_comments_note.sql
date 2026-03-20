-- BUG-070: coordinator_comments usa relação polimórfica (content_type + content_id)
-- FOREIGN KEY formal não é viável em MySQL para relações polimórficas.
-- A limpeza em cascata é feita a nível de aplicação:
--   - ObservationController::delete() limpa 'observation'
--   - PortfolioController::delete() limpa 'portfolio'
--   - DescriptiveReportController::delete() limpa 'descriptive_report'
-- Este arquivo documenta a decisão arquitetural.
-- Nenhum ALTER TABLE é necessário.
SELECT 'BUG-070: cascata polimórfica tratada em nível de aplicação' AS status;
