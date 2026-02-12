-- migrations/012_alter_users_add_student_role.sql

-- Adicionar colunas se não existirem
ALTER TABLE users
ADD COLUMN IF NOT EXISTS last_login TIMESTAMP NULL AFTER created_at,
ADD COLUMN IF NOT EXISTS phone VARCHAR(20) AFTER email,
ADD COLUMN IF NOT EXISTS avatar VARCHAR(255) AFTER phone,
ADD COLUMN IF NOT EXISTS bio TEXT AFTER avatar,
ADD COLUMN IF NOT EXISTS linkedin_url VARCHAR(255) AFTER bio;

-- Atualizar ENUM de role para incluir 'student'
-- Em MySQL, precisamos remover a restrição existente primeiro
ALTER TABLE users MODIFY COLUMN role ENUM('admin', 'professor', 'coordenador', 'student') DEFAULT 'student';

-- Criar índices
CREATE INDEX IF NOT EXISTS idx_users_role ON users(role);
CREATE INDEX IF NOT EXISTS idx_users_email ON users(email);

-- Adicionar comentários
ALTER TABLE users
MODIFY COLUMN name VARCHAR(255) NOT NULL COMMENT 'Nome do usuário',
MODIFY COLUMN email VARCHAR(255) NOT NULL COMMENT 'Email do usuário',
MODIFY COLUMN password VARCHAR(255) NOT NULL COMMENT 'Senha (hash)',
MODIFY COLUMN role ENUM('admin', 'professor', 'coordenador', 'student') DEFAULT 'student' COMMENT 'Perfil do usuário',
MODIFY COLUMN created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP COMMENT 'Data de cadastro',
MODIFY COLUMN last_login TIMESTAMP NULL COMMENT 'Ultimo acesso',
MODIFY COLUMN phone VARCHAR(20) COMMENT 'Telefone do usuario',
MODIFY COLUMN avatar VARCHAR(255) COMMENT 'URL da foto de perfil',
MODIFY COLUMN bio TEXT COMMENT 'Biografia do usuario',
MODIFY COLUMN linkedin_url VARCHAR(255) COMMENT 'Perfil do LinkedIn';
