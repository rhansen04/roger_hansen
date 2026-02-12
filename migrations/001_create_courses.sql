-- migrations/001_create_courses.sql

CREATE TABLE IF NOT EXISTS courses (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL COMMENT 'Título do curso',
    slug VARCHAR(255) NOT NULL UNIQUE COMMENT 'URL amigável',
    description TEXT COMMENT 'Descrição completa',
    short_description VARCHAR(500) COMMENT 'Descrição resumida',
    cover_image VARCHAR(255) COMMENT 'URL da imagem de capa',
    price DECIMAL(10,2) DEFAULT 0.00 COMMENT 'Preço do curso',
    is_free TINYINT(1) DEFAULT 0 COMMENT 'Curso gratuito?',
    is_active TINYINT(1) DEFAULT 1 COMMENT 'Curso ativo?',
    category VARCHAR(100) COMMENT 'Categoria do curso',
    level ENUM('beginner', 'intermediate', 'advanced') DEFAULT 'beginner' COMMENT 'Nível de dificuldade',
    duration_hours INT DEFAULT 0 COMMENT 'Duração em horas',
    instructor_id INT COMMENT 'ID do instrutor',
    max_students INT COMMENT 'Máximo de alunos',
    enrollment_start_date DATE COMMENT 'Data de início de matrículas',
    enrollment_end_date DATE COMMENT 'Data final de matrículas',
    course_start_date DATE COMMENT 'Data de início do curso',
    course_end_date DATE COMMENT 'Data de término do curso',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_courses_active (is_active),
    INDEX idx_courses_category (category),
    INDEX idx_courses_slug (slug),
    FOREIGN KEY (instructor_id) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Tabela de cursos';
