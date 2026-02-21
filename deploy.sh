#!/bin/bash
ssh -i "F:/_KEYS_/ssh_key_auto" root@154.38.189.82 "cd /opt/site_roger && git pull && docker compose build --no-cache && docker compose up -d && docker image prune -f"
