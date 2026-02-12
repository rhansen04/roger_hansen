-- 000_create_base_tables.sql
-- Tabelas base que nao tinham migrations MySQL (eram SQLite only)
-- DEVE ser executado ANTES de todas as outras migrations

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL COMMENT 'Nome do usuario',
    email VARCHAR(255) NOT NULL UNIQUE COMMENT 'Email do usuario',
    password VARCHAR(255) NOT NULL COMMENT 'Senha (hash)',
    role ENUM('admin', 'professor', 'coordenador', 'student') DEFAULT 'student' COMMENT 'Perfil do usuario',
    phone VARCHAR(20) COMMENT 'Telefone do usuario',
    avatar VARCHAR(255) COMMENT 'URL da foto de perfil',
    bio TEXT COMMENT 'Biografia do usuario',
    linkedin_url VARCHAR(255) COMMENT 'Perfil do LinkedIn',
    last_login TIMESTAMP NULL COMMENT 'Ultimo acesso',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_users_role (role),
    INDEX idx_users_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Tabela de usuarios do sistema';

CREATE TABLE IF NOT EXISTS schools (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL COMMENT 'Nome da escola',
    city VARCHAR(100) COMMENT 'Cidade',
    state CHAR(2) COMMENT 'Estado (UF)',
    address TEXT COMMENT 'Endereco completo',
    contact_person VARCHAR(255) COMMENT 'Pessoa de contato',
    phone VARCHAR(20) COMMENT 'Telefone',
    email VARCHAR(255) COMMENT 'Email de contato',
    logo_url VARCHAR(255) COMMENT 'URL do logo',
    contract_start_date DATE COMMENT 'Inicio do contrato',
    contract_end_date DATE COMMENT 'Fim do contrato',
    status ENUM('active', 'inactive') DEFAULT 'active' COMMENT 'Status do contrato',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_schools_status (status),
    INDEX idx_schools_city (city),
    INDEX idx_schools_state (state)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Tabela de escolas parceiras';

CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL COMMENT 'Nome do aluno',
    birth_date DATE COMMENT 'Data de nascimento',
    school_id INT COMMENT 'Escola do aluno',
    photo_url VARCHAR(255) COMMENT 'Foto do aluno',
    enrollment_number VARCHAR(50) COMMENT 'Numero de matricula',
    parent_name VARCHAR(255) COMMENT 'Nome do responsavel',
    parent_phone VARCHAR(20) COMMENT 'Telefone do responsavel',
    parent_email VARCHAR(255) COMMENT 'Email do responsavel',
    status ENUM('active', 'inactive', 'transferred') DEFAULT 'active' COMMENT 'Status do aluno',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (school_id) REFERENCES schools(id) ON DELETE SET NULL,
    INDEX idx_students_school (school_id),
    INDEX idx_students_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Tabela de alunos';

CREATE TABLE IF NOT EXISTS observations (
    id INT AUTO_INCREMENT PRIMARY KEY,
    student_id INT NOT NULL COMMENT 'Aluno observado',
    user_id INT NOT NULL COMMENT 'Professor que registrou',
    type VARCHAR(100) COMMENT 'Tipo da observacao',
    category VARCHAR(100) COMMENT 'Categoria',
    title VARCHAR(255) COMMENT 'Titulo',
    content TEXT NOT NULL COMMENT 'Conteudo da observacao',
    observed_at DATETIME DEFAULT NULL COMMENT 'Data da observacao',
    attachments TEXT COMMENT 'Anexos (JSON)',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_observations_student (student_id),
    INDEX idx_observations_user (user_id),
    INDEX idx_observations_type (type),
    INDEX idx_observations_date (observed_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Tabela de observacoes pedagogicas';

-- Usuario admin padrao (senha: admin123)
INSERT INTO users (name, email, password, role) VALUES
('Administrador', 'admin@hansen.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin')
ON DUPLICATE KEY UPDATE name = name;
