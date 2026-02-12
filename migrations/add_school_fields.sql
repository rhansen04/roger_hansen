-- Adicionar novos campos à tabela schools
-- Execute este script para adicionar campos de endereço, contato, contrato e logo

USE hansen_educacional;

-- Verificar e adicionar campos se não existirem
ALTER TABLE schools
ADD COLUMN IF NOT EXISTS address TEXT,
ADD COLUMN IF NOT EXISTS phone VARCHAR(20),
ADD COLUMN IF NOT EXISTS email VARCHAR(255),
ADD COLUMN IF NOT EXISTS contract_start_date DATE,
ADD COLUMN IF NOT EXISTS contract_end_date DATE,
ADD COLUMN IF NOT EXISTS logo_url VARCHAR(255),
ADD COLUMN IF NOT EXISTS status ENUM('active', 'inactive') DEFAULT 'active',
ADD COLUMN IF NOT EXISTS updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

-- Atualizar escolas existentes para status ativo
UPDATE schools SET status = 'active' WHERE status IS NULL;

-- Criar índices para melhor performance
CREATE INDEX IF NOT EXISTS idx_schools_status ON schools(status);
CREATE INDEX IF NOT EXISTS idx_schools_city ON schools(city);
CREATE INDEX IF NOT EXISTS idx_schools_state ON schools(state);

-- Adicionar comentários nas colunas
ALTER TABLE schools
MODIFY COLUMN name VARCHAR(255) NOT NULL COMMENT 'Nome da escola',
MODIFY COLUMN city VARCHAR(100) COMMENT 'Cidade onde a escola está localizada',
MODIFY COLUMN state CHAR(2) COMMENT 'Estado (UF)',
MODIFY COLUMN address TEXT COMMENT 'Endereço completo da escola',
MODIFY COLUMN contact_person VARCHAR(255) COMMENT 'Nome da pessoa de contato',
MODIFY COLUMN phone VARCHAR(20) COMMENT 'Telefone de contato',
MODIFY COLUMN email VARCHAR(255) COMMENT 'Email de contato',
MODIFY COLUMN contract_start_date DATE COMMENT 'Data de início do contrato',
MODIFY COLUMN contract_end_date DATE COMMENT 'Data de término do contrato',
MODIFY COLUMN logo_url VARCHAR(255) COMMENT 'URL do logo da escola',
MODIFY COLUMN status ENUM('active', 'inactive') DEFAULT 'active' COMMENT 'Status do contrato';

-- Mostrar estrutura atualizada da tabela
DESCRIBE schools;
