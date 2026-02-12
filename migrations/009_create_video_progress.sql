-- migrations/009_create_video_progress.sql

CREATE TABLE IF NOT EXISTS video_progress (
    id INT AUTO_INCREMENT PRIMARY KEY,
    enrollment_id INT NOT NULL COMMENT 'ID da matrícula',
    lesson_id INT NOT NULL COMMENT 'ID da lição',
    `current_time` INT NOT NULL DEFAULT 0 COMMENT 'Segundos assistidos atualmente',
    total_duration INT NOT NULL DEFAULT 0 COMMENT 'Duração total do vídeo em segundos',
    percentage_watched DECIMAL(5,2) NOT NULL DEFAULT 0.00 COMMENT '% do vídeo assistido (0-100)',
    is_completed TINYINT(1) DEFAULT 0 COMMENT 'Vídeo completado (100% assistido)?',
    completed_at TIMESTAMP NULL COMMENT 'Data/hora da conclusão do vídeo',
    last_position_timestamp INT COMMENT 'Timestamp da última posição assistida (para retomada)',
    first_watch_start TIMESTAMP NULL COMMENT 'Quando o aluno começou a assistir pela primeira vez',
    total_watch_time INT DEFAULT 0 COMMENT 'Tempo total assistido em segundos (acumulado)',
    watch_sessions INT DEFAULT 1 COMMENT 'Número de sessões de visualização',
    last_watched_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Última vez que assistiu',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_video_progress (enrollment_id, lesson_id),
    FOREIGN KEY (enrollment_id) REFERENCES enrollments(id) ON DELETE CASCADE,
    FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE,
    INDEX idx_video_progress_enrollment (enrollment_id),
    INDEX idx_video_progress_lesson (lesson_id),
    INDEX idx_video_progress_completed (is_completed)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Tabela de progresso de vídeos assistidos pelo aluno (tracking em tempo real)';
