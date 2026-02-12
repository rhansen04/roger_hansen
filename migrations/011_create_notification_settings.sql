-- migrations/011_create_notification_settings.sql

CREATE TABLE IF NOT EXISTS notification_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_role ENUM('student', 'gestor', 'admin') NOT NULL COMMENT 'Perfil de usuário',
    notification_type ENUM('video_completed', 'course_completed', 'inactivity_alert', 'low_progress_alert') NOT NULL COMMENT 'Tipo de notificação',
    is_enabled TINYINT(1) DEFAULT 1 COMMENT 'Está habilitado?',
    recipient_role ENUM('self', 'admin', 'both') NOT NULL COMMENT 'Para quem enviar (próprio, admin, ambos)',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    UNIQUE KEY unique_setting (user_role, notification_type, recipient_role)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Configurações de notificações por perfil e tipo';

-- Inserir configurações padrão
INSERT INTO notification_settings (user_role, notification_type, is_enabled, recipient_role) VALUES
('student', 'video_completed', 1, 'self'),
('admin', 'video_completed', 1, 'admin'),
('student', 'course_completed', 1, 'self'),
('admin', 'course_completed', 1, 'admin'),
('admin', 'inactivity_alert', 1, 'admin'),
('student', 'low_progress_alert', 1, 'self'),
('admin', 'low_progress_alert', 1, 'admin');
