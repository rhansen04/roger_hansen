# 🚀 Hansen Educacional - Site PHP Local

> Sistema educacional desenvolvido em PHP 8.2+ com MVC e Bootstrap 5.3

---

## ⚡ Quick Start

### 1️⃣ Execute o script de setup (recomendado)

```powershell
# Abra PowerShell como ADMINISTRADOR
cd "F:\Projetos\Roger.Hansen\SITE_NOVO"
Set-ExecutionPolicy -ExecutionPolicy Bypass -Scope Process -Force
.\SETUP_XAMPP.ps1
```

### 2️⃣ Crie o banco de dados

Acesse http://localhost/phpmyadmin e execute:

```sql
CREATE DATABASE hansen_educacional CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE hansen_educacional;

CREATE TABLE schools (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    city VARCHAR(100),
    state CHAR(2),
    contact_person VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    birth_date DATE,
    school_id INT,
    photo_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (school_id) REFERENCES schools(id)
);
```

### 3️⃣ Acesse o site

```
http://hansen.local
http://hansen.local/admin/dashboard
```

---

## 📋 Checklist de Configuração

```
[ ] XAMPP instalado em C:\xampp
[ ] PowerShell aberto como ADMINISTRADOR
[ ] Script SETUP_XAMPP.ps1 executado
[ ] Apache reiniciado no XAMPP
[ ] Banco de dados criado no phpMyAdmin
[ ] Tabelas criadas (schools, students)
[ ] hansen.local acessível no navegador
[ ] Admin dashboard em hansen.local/admin/dashboard
```

---

## 🛠️ Configuração Manual (se necessário)

### 1. Virtual Host no Apache

Adicione em `C:\xampp\apache\conf\extra\httpd-vhosts.conf`:

```apache
<VirtualHost *:80>
    DocumentRoot "F:/Projetos/Roger.Hansen/SITE_NOVO/public"
    ServerName hansen.local
    <Directory "F:/Projetos/Roger.Hansen/SITE_NOVO/public">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### 2. Habilitar mod_rewrite

Edite `C:\xampp\apache\conf\httpd.conf`:

```apache
LoadModule rewrite_module modules/mod_rewrite.so
```

(remova o `#` se estiver comentado)

### 3. Arquivo hosts do Windows

Adicione em `C:\Windows\System32\drivers\etc\hosts`:

```
127.0.0.1       hansen.local
127.0.0.1       www.hansen.local
```

### 4. Reiniciar Apache

- XAMPP Control Panel → Stop → Start (Apache)

---

## 📁 Estrutura do Projeto

```
SITE_NOVO/
├── 📄 SETUP_XAMPP.ps1              ← Execute este arquivo primeiro!
├── 📄 VERIFICACAO_RAPIDA.ps1       ← Verifica se tudo está ok
├── 📄 CONFIGURACAO_LOCAL.md        ← Guia detalhado
├── 📄 README.md                    ← Este arquivo
│
├── 📁 public/                      ← Raiz web (DocumentRoot)
│   ├── index.php                   ← Entry point
│   ├── .htaccess                   ← Rewrite rules
│   └── assets/                     ← CSS, JS, imagens
│
├── 📁 app/                         ← Núcleo da aplicação
│   ├── Controllers/                ← Controllers
│   │   ├── PageController.php
│   │   └── Admin/DashboardController.php
│   ├── Models/                     ← Models (Student, School)
│   ├── Core/
│   │   ├── Router/Router.php       ← Sistema de rotas
│   │   └── Database/Connection.php ← Conexão DB
│   └── Config/database.php         ← Configuração banco
│
├── 📁 views/                       ← Templates
│   ├── layouts/                    ← Layouts base
│   │   ├── public.php
│   │   └── admin.php
│   ├── pages/                      ← Páginas públicas
│   │   ├── home.php
│   │   ├── programas.php
│   │   ├── palestras.php
│   │   ├── contato.php
│   │   ├── cursos.php
│   │   └── livros.php
│   └── admin/                      ← Páginas admin
│       └── dashboard.php
│
└── 📁 storage/                     ← Logs e cache
```

---

## 🌐 URLs do Site

### Páginas Públicas

| Página | URL |
|--------|-----|
| Home | `http://hansen.local` |
| Programas | `http://hansen.local/programas` |
| Palestras | `http://hansen.local/palestras` |
| Cursos | `http://hansen.local/cursos` |
| Livros | `http://hansen.local/livros` |
| Contato | `http://hansen.local/contato` |

### Área Administrativa

| Página | URL |
|--------|-----|
| Dashboard | `http://hansen.local/admin/dashboard` |

---

## 🔍 Verificação Rápida

Execute o script de verificação para confirmar se tudo está funcionando:

```powershell
.\VERIFICACAO_RAPIDA.ps1
```

Ele vai verificar:
- ✓ XAMPP instalado
- ✓ Apache rodando
- ✓ mod_rewrite habilitado
- ✓ Virtual Host configurado
- ✓ Arquivo hosts atualizado
- ✓ Projeto íntegro
- ✓ MySQL conectável
- ✓ Banco de dados criado

---

## 🚨 Troubleshooting

### ❌ "ERR_NAME_NOT_RESOLVED"

```powershell
# Limpar cache DNS
ipconfig /flushdns
```

### ❌ "404 Not Found"

1. Verifique se `mod_rewrite` está habilitado
2. Reinicie o Apache
3. Verifique se `.htaccess` existe

### ❌ Erro 500

Veja logs em:
```
C:\xampp\apache\logs\hansen.local-error.log
C:\xampp\apache\logs\error.log
```

### ❌ Banco de dados não conecta

1. Verifique se MySQL está rodando
2. Verifique se banco `hansen_educacional` foi criado
3. Verifique se credenciais são corretas (`root` / sem senha)

---

## 💾 Banco de Dados

### Configuração Padrão

```php
// app/Config/database.php
[
    'host' => 'localhost',
    'port' => '3306',
    'database' => 'hansen_educacional',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
]
```

### Tabelas

- **schools**: Informações de escolas
- **students**: Informações de alunos

---

## 🛠️ Tecnologias

| Camada | Tecnologia |
|--------|-----------|
| Backend | PHP 8.2+ |
| Frontend | Bootstrap 5.3, Font Awesome 6 |
| Banco | MariaDB/MySQL |
| Servidor | Apache 2.4 |
| Roteamento | Sistema customizado MVC |

---

## 📝 Próximas Implementações

- [ ] CRUD completo de alunos e escolas
- [ ] Sistema de autenticação (login)
- [ ] Email de contato automático
- [ ] Upload de imagens
- [ ] Relatórios PDF
- [ ] Dashboard com gráficos

---

## 👤 Desenvolvedor

**Hansen Educacional** - Sistema educacional customizado
Desenvolvido com PHP 8.2+ MVC
Data: 10 de Fevereiro de 2026

---

## 📞 Suporte

Se encontrar problemas:

1. Execute `VERIFICACAO_RAPIDA.ps1` para diagnóstico
2. Leia `CONFIGURACAO_LOCAL.md` para guia detalhado
3. Verifique logs em `C:\xampp\apache\logs\`

---

**Pronto para começar? Execute:**

```powershell
.\SETUP_XAMPP.ps1
```

🎉
