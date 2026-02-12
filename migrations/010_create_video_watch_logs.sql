-- migrations/010_create_video_watch_logs.sql

CREATE TABLE IF NOT EXISTS video_watch_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    video_progress_id INT NOT NULL COMMENT 'ID do progresso de vídeo',
    session_start TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Início da sessão',
    session_end TIMESTAMP NULL COMMENT 'Fim da sessão',
    session_duration INT DEFAULT 0 COMMENT 'Duração da sessão em segundos',
    percentage_before DECIMAL(5,2) DEFAULT 0.00 COMMENT '% antes da sessão',
    percentage_after DECIMAL(5,2) DEFAULT 0.00 COMMENT '% após a sessão',
    device_info VARCHAR(255) COMMENT 'Dispositivo/Usuário agente',
    ip_address VARCHAR(45) COMMENT 'Endereço IP',
    completed_during_session TINYINT(1) DEFAULT 0 COMMENT 'Vídeo concluído nesta sessão?',
    FOREIGN KEY (video_progress_id) REFERENCES video_progress(id) ON DELETE CASCADE,
    INDEX idx_watch_logs_video (video_progress_id),
    INDEX idx_watch_logs_date (session_start)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Histórico de sessões de visualização de vídeos (PERMANENTE - NÃO DELETAR)';
