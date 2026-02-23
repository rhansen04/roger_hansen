# Como Conectar na VPS e Fazer Deploy

## Dados da VPS

| Item | Valor |
|------|-------|
| **IP** | 154.38.189.82 |
| **Usuario** | root |
| **SO** | Ubuntu 24.04 LTS |
| **Provedor** | Contabo |
| **Porta SSH** | 22 |
| **Autenticacao** | Somente chave SSH (senha desabilitada) |

## Chave SSH

| Item | Valor |
|------|-------|
| **Chave privada (com passphrase)** | `F:\_KEYS_\ssh_key_auto` |
| **Chave sem passphrase** | `F:\_KEYS_\ssh_key_auto_nopass` |
| **Passphrase** | `idsh$A0sp.` |

## Como Conectar via Claude Code

O SSH nativo do Windows exige que a chave tenha permissoes restritas. Procedimento:

```bash
# 1. Copiar chave sem passphrase para local temporario
powershell -NoProfile -Command "Copy-Item 'F:\_KEYS_\ssh_key_auto_nopass' 'C:\tmp\deploy_key_roger'; icacls 'C:\tmp\deploy_key_roger' /inheritance:r /grant:r 'Regis Hansen:F'"

# 2. Executar comando na VPS
"C:/Windows/System32/OpenSSH/ssh.exe" -i "C:/tmp/deploy_key_roger" -o StrictHostKeyChecking=no root@154.38.189.82 'COMANDO_AQUI' 2>&1

# 3. Limpar apos uso
powershell -NoProfile -Command "Remove-Item 'C:\tmp\deploy_key_roger' -Force"
```

### Importante

- Usar `"C:/Windows/System32/OpenSSH/ssh.exe"` (nao o SSH do Git Bash)
- Para comandos com `$()`, usar aspas simples no comando SSH (evita expansao local)
- Sempre adicionar `2>&1` no final para capturar stderr
- Timeout recomendado: 30000ms para comandos simples, 300000ms para build

## Localizacao do Projeto na VPS

| Item | Caminho |
|------|---------|
| **Codigo fonte** | `/opt/site_roger` |
| **Docker Compose** | `/opt/site_roger/docker-compose.yml` |
| **Env vars** | `/opt/site_roger/.env` |

## Containers Docker

| Container | Servico | Porta |
|-----------|---------|-------|
| site_roger-app-1 | PHP (Apache) | 8080→80 |
| site_roger-db-1 | MySQL 8.0 | 3306 (interno) |
| site_roger-redis-1 | Redis 7 | 6379 (interno) |

## Credenciais do Banco

| Item | Valor |
|------|-------|
| **Database** | hansen_educacional |
| **User** | hansen |
| **Password** | Hansen2026Edu! |
| **Root Password** | duqK^3v)J^FW@?Xi |

## Deploy Automatico (GitHub Actions)

O deploy e automatico a cada `git push` na branch `main`.

**Pipeline:** `git push` → GitHub Actions → SSH na VPS → `git pull + docker compose build + up`

**Tempo medio:** ~2 minutos

**Workflow:** `.github/workflows/deploy.yml`

**GitHub Secrets configurados:**
- `SERVER_HOST` = 154.38.189.82
- `SERVER_USER` = root
- `SERVER_SSH_KEY` = chave ed25519 dedicada (`/root/.ssh/id_deploy_roger`)

### Verificar status do deploy

```bash
gh run list -R rhansen04/roger_hansen --limit 3
gh run view <RUN_ID> -R rhansen04/roger_hansen
```

## Deploy Manual (via script local)

```bash
# Na raiz do projeto
bash deploy.sh
```

Ou manualmente:

```bash
ssh -i "F:/_KEYS_/ssh_key_auto" root@154.38.189.82 "cd /opt/site_roger && git pull && docker compose build --no-cache && docker compose up -d && docker image prune -f"
```

## Deploy Manual via Claude Code

```bash
# 1. Preparar chave
powershell -NoProfile -Command "Copy-Item 'F:\_KEYS_\ssh_key_auto_nopass' 'C:\tmp\deploy_key_roger'; icacls 'C:\tmp\deploy_key_roger' /inheritance:r /grant:r 'Regis Hansen:F'"

# 2. Deploy
"C:/Windows/System32/OpenSSH/ssh.exe" -i "C:/tmp/deploy_key_roger" -o StrictHostKeyChecking=no root@154.38.189.82 'cd /opt/site_roger && git pull origin main && docker compose build --no-cache && docker compose up -d && docker image prune -f' 2>&1

# 3. Verificar
"C:/Windows/System32/OpenSSH/ssh.exe" -i "C:/tmp/deploy_key_roger" -o StrictHostKeyChecking=no root@154.38.189.82 'cd /opt/site_roger && docker compose ps && curl -s -o /dev/null -w "%{http_code}" http://localhost:8080' 2>&1

# 4. Limpar chave
powershell -NoProfile -Command "Remove-Item 'C:\tmp\deploy_key_roger' -Force"
```

## Comandos Uteis na VPS

```bash
# Ver logs do app
docker compose -f /opt/site_roger/docker-compose.yml logs -f app

# Ver logs do banco
docker compose -f /opt/site_roger/docker-compose.yml logs -f db

# Restart dos containers
docker compose -f /opt/site_roger/docker-compose.yml restart

# Acessar shell do container app
docker exec -it site_roger-app-1 bash

# Acessar MySQL
docker exec -it site_roger-db-1 mysql -u hansen -p'Hansen2026Edu!' hansen_educacional

# Ver uso de disco dos volumes
docker system df -v | grep site_roger
```

## Rede e Acesso Externo

| Item | Valor |
|------|-------|
| **Acesso atual** | http://154.38.189.82:8080 |
| **Reverse proxy** | Traefik (labels preparatorios no docker-compose.yml) |
| **Firewall** | UFW — apenas portas 22, 80, 443 abertas |
| **Fail2ban** | Ativo — 3 tentativas = ban 1 hora |

Quando tiver dominio, basta apontar o DNS para 154.38.189.82 e o Traefik vai rotear automaticamente (labels ja configuradas para `rogerhansen.com.br`).
