-- migrations/016_create_contacts.sql

CREATE TABLE IF NOT EXISTS contacts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    school_name VARCHAR(255) NOT NULL,
    contact_name VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    phone VARCHAR(50) NOT NULL,
    city_state VARCHAR(255) NOT NULL,
    message TEXT,
    attachment VARCHAR(255) DEFAULT NULL,
    is_read TINYINT(1) DEFAULT 0,
    notes TEXT DEFAULT NULL COMMENT 'Notas internas do admin',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_contacts_email (email),
    INDEX idx_contacts_read (is_read),
    INDEX idx_contacts_date (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Contatos recebidos pelo formulário do site';
