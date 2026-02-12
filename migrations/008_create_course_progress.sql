-- migrations/008_create_course_progress.sql

CREATE TABLE IF NOT EXISTS course_progress (
    id INT AUTO_INCREMENT PRIMARY KEY,
    enrollment_id INT NOT NULL COMMENT 'ID da matrícula',
    lesson_id INT NOT NULL COMMENT 'ID da lição',
    completed TINYINT(1) DEFAULT 0 COMMENT 'Lição completada?',
    completed_at TIMESTAMP NULL COMMENT 'Data de conclusão',
    time_spent_minutes INT DEFAULT 0 COMMENT 'Tempo gasto na lição',
    quiz_score INT COMMENT 'Nota do quiz (se houver)',
    quiz_passed TINYINT(1) COMMENT 'Quiz aprovado?',
    attempts INT DEFAULT 0 COMMENT 'Número de tentativas',
    video_progress_id INT COMMENT 'ID do progresso de vídeo associado',
    video_percentage_watched DECIMAL(5,2) DEFAULT 0.00 COMMENT '% do vídeo assistido',
    video_current_time INT DEFAULT 0 COMMENT 'Segundo atual do vídeo',
    video_total_duration INT DEFAULT 0 COMMENT 'Duração total do vídeo em segundos',
    first_played_at TIMESTAMP NULL COMMENT 'Quando foi reproduzido pela primeira vez',
    last_played_at TIMESTAMP NULL COMMENT 'Última vez que foi reproduzido',
    total_play_time INT DEFAULT 0 COMMENT 'Tempo total de reprodução em segundos',
    play_count INT DEFAULT 0 COMMENT 'Número de vezes que foi reproduzido',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_progress (enrollment_id, lesson_id),
    FOREIGN KEY (enrollment_id) REFERENCES enrollments(id) ON DELETE CASCADE,
    FOREIGN KEY (lesson_id) REFERENCES lessons(id) ON DELETE CASCADE,
    FOREIGN KEY (video_progress_id) REFERENCES video_progress(id) ON DELETE SET NULL,
    INDEX idx_progress_enrollment (enrollment_id),
    INDEX idx_progress_lesson (lesson_id),
    INDEX idx_progress_video (video_progress_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Tabela de progresso do aluno nos cursos e lições';
