# 🚀 INSTALAÇÃO - MySQL + Redis Local

## 📋 Pré-Requisitos

1. **XAMPP instalado** em `C:\xampp`
2. **Redis instalado** (ver abaixo como instalar)
3. **PHP 8.2+** já incluído no XAMPP

---

## 📦 INSTALAÇÃO DO REDIS (Windows)

### Opção 1: Via Docker (RECOMENDADO)

1. Instalar Docker Desktop para Windows
2. Abrir PowerShell como ADMINISTRADOR
3. Executar:
   ```bash
   docker run --name redis -p 6379:6379 -v C:\Users\%USERNAME%\redis\data redis:7-alpine
   ```

### Opção 2: Via Executável

1. Baixar Redis para Windows:
   - https://github.com/microsoftarchive/redis/releases
   - Baixar versão mais recente (ex: Redis-x64-7.2.5.msi)

2. Instalar seguindo o instalador

3. Configurar:
   - Marque "Add to PATH"
   - Marque "Register as Service"

4. Iniciar serviço Redis:
   - Win+R → services.msc
   - Encontrar "Redis"
   - Clicar em "Iniciar"

---

## 🗄️ CRIAR BANCO DE DADOS MYSQL

### 1. Abrir phpMyAdmin
Acesse: `http://localhost/phpmyadmin`

### 2. Criar database
Execute no SQL:
```sql
CREATE DATABASE IF NOT EXISTS hansen_educacional 
CHARACTER SET utf8mb4 
COLATE utf8mb4_unicode_ci;
```

### 3. Criar usuário (NÃO usar root em produção)
Execute no SQL:
```sql
CREATE USER IF NOT EXISTS 'hansen_user'@'localhost' IDENTIFIED BY 'senha_forte_aqui_mudar_em_producao';

GRANT ALL PRIVILEGES ON hansen_educacional.* TO 'hansen_user'@'localhost';
FLUSH PRIVILEGES;
```

---

## 🧪 TESTAR CONEXÕES

### Testar MySQL
```bash
php scripts/test_connections.php
```

### Testar Redis (se instalado)
```bash
redis-cli ping
```

---

## 🔧 CONFIGURAÇÃO FINAL

### 1. Criar arquivo .env
Crie o arquivo `.env` no raiz do projeto:
```bash
# Ambiente
APP_ENV=development
APP_DEBUG=true
APP_URL=http://hansen.local

# Database - MySQL (LOCAL)
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=hansen_educacional
DB_USERNAME=hansen_user
DB_PASSWORD=sua_senha_aqui
DB_CHARSET=utf8mb4
DB_COLLATION=utf8mb4_unicode_ci

# Redis (LOCAL)
REDIS_HOST=127.0.0.1
REDIS_PORT=6379
REDIS_PASSWORD=
REDIS_DB=0

# Uploads
UPLOAD_MAX_SIZE=100MB
UPLOAD_PATH=F:/PROJETOS/Roger.Hansen/SITE_NOVO/public/uploads

# Segurança
APP_KEY=hansen_educacional_secret_key_2026_v1
SESSION_DRIVER=redis

# Tracking de Vídeos
VIDEO_TRACKING_INTERVAL=5
VIDEO_COMPLETION_THRESHOLD=97
DASHBOARD_REFRESH_INTERVAL=30

# Notificações
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=
MAIL_PASSWORD=
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@hanseneducacional.com.br
MAIL_FROM_NAME=Hansen Educacional
```

### 2. Instalar dependências Composer
```bash
cd F:\PROJETOS\Roger.Hansen\SITE_NOVO
scripts\install_composer.bat
```

### 3. Executar migrations
```bash
# No phpMyAdmin, execute cada migration na ordem:

# 1. Cursos
source migrations/001_create_courses.sql

# 2. Seções
source migrations/002_create_sections.sql

# 3. Lições
source migrations/003_create_lessons.sql

# 4. Quizzes
source migrations/004_create_quizzes.sql

# 5. Perguntas de Quiz
source migrations/005_create_quiz_questions.sql

# 6. Respostas de Quiz
source migrations/006_create_quiz_answers.sql

# 7. Matrículas
source migrations/007_create_enrollments.sql

# 8. Progresso de Curso
source migrations/008_create_course_progress.sql

# 9. Progresso de Vídeo
source migrations/009_create_video_progress.sql

# 10. Logs de Sessões
source migrations/010_create_video_watch_logs.sql

# 11. Configurações de Notificações
source migrations/011_create_notification_settings.sql

# 12. Atualizar Users (adicionar role 'student')
source migrations/012_alter_users_add_student_role.sql

# 13. Atualizar Schools (compatibilidade MySQL)
source migrations/013_update_schools_mysql_syntax.sql
```

---

## ✅ CHECKLIST FINAL

### Servidor Local
- [ ] XAMPP rodando
- [ ] MySQL 8.0+ ativo
- [ ] phpMyAdmin acessível
- [ ] Database `hansen_educacional` criado
- [ ] Usuário `hansen_user` criado
- [ ] Redis instalado e rodando

### Aplicação
- [ ] Arquivo .env criado
- [ ] Dependências Composer instaladas
- [ ] Todas as migrations executadas
- [ ] Tabelas verificadas

### Testes
- [ ] Conexão MySQL funcionando
- [ ] Conexão Redis funcionando (se aplicável)
- [ ] Sistema acessível em http://hansen.local
- [ ] Página de login acessível

---

## 📋 PRÓXIMOS PASSOS APÓS CONFIGURAÇÃO

1. ✅ Instalar Redis (Docker ou Windows)
2. ✅ Criar banco MySQL e usuário
3. ✅ Configurar arquivo .env
4. ✅ Instalar dependências Composer
5. ✅ Executar migrations
6. ✅ Testar tudo

---

**Depois disso, podemos começar a implementar o sistema de cursos LMS!** 🎓
