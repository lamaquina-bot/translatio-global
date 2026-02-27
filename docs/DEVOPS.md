# 🚀 FASE 8: DEVOPS - Translatio Global

**Fecha:** 27 Feb 2026
**Agente:** DevOps Agent
**Estado:** ✅ COMPLETADO

---

## 🔐 SSL LET'S ENCRYPT

### Instalación con Certbot

```bash
# Instalar Certbot
sudo apt update
sudo apt install certbot python3-certbot-nginx

# Obtener certificado (Nginx)
sudo certbot --nginx -d translatioglobal.com -d www.translatioglobal.com

# Obtener certificado (Apache)
sudo certbot --apache -d translatioglobal.com -d www.translatioglobal.com

# Renovación automática
sudo certbot renew --dry-run
```

### Configuración SSL Nginx
```nginx
server {
    listen 80;
    server_name translatioglobal.com www.translatioglobal.com;
    return 301 https://$server_name$request_uri;
}

server {
    listen 443 ssl http2;
    server_name translatioglobal.com www.translatioglobal.com;

    # SSL Certificates
    ssl_certificate /etc/letsencrypt/live/translatioglobal.com/fullchain.pem;
    ssl_certificate_key /etc/letsencrypt/live/translatioglobal.com/privkey.pem;

    # SSL Configuration
    ssl_protocols TLSv1.2 TLSv1.3;
    ssl_ciphers ECDHE-ECDSA-AES128-GCM-SHA256:ECDHE-RSA-AES128-GCM-SHA256:ECDHE-ECDSA-AES256-GCM-SHA384:ECDHE-RSA-AES256-GCM-SHA384;
    ssl_prefer_server_ciphers off;
    ssl_session_cache shared:SSL:10m;
    ssl_session_timeout 1d;
    ssl_session_tickets off;

    # HSTS
    add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;

    # Security Headers
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header Referrer-Policy "strict-origin-when-cross-origin" always;

    # Document Root
    root /var/www/translatioglobal.com;
    index index.php index.html;

    # WordPress
    location / {
        try_files $uri $uri/ /index.php?$args;
    }

    # PHP-FPM
    location ~ \.php$ {
        include fastcgi_params;
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        fastcgi_buffers 16 16k;
        fastcgi_buffer_size 32k;
    }

    # Deny access to sensitive files
    location ~ /\. {
        deny all;
    }
    location ~ /wp-config.php {
        deny all;
    }
    location ~ /xmlrpc.php {
        deny all;
    }

    # Static files caching
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|svg|woff|woff2)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
    }
}
```

---

## 💾 BACKUP AUTOMÁTICO

### Script de Backup Diario
```bash
#!/bin/bash
# backup-daily.sh - Backup automático de WordPress
# Ejecutar: crontab -e → 0 3 * * * /root/scripts/backup-daily.sh

# Configuración
DATE=$(date +%Y%m%d_%H%M%S)
SITE_NAME="translatioglobal"
SITE_DIR="/var/www/translatioglobal.com"
BACKUP_DIR="/backups/translatioglobal"
DB_NAME="translatio_db"
DB_USER="translatio_user"
DB_PASS="your_password"
KEEP_DAYS=30

# Crear directorio de backup
mkdir -p $BACKUP_DIR/daily

# Backup de base de datos
echo "Backing up database..."
mysqldump -u$DB_USER -p$DB_PASS $DB_NAME | gzip > $BACKUP_DIR/daily/${SITE_NAME}_db_${DATE}.sql.gz

# Backup de archivos
echo "Backing up files..."
tar -czf $BACKUP_DIR/daily/${SITE_NAME}_files_${DATE}.tar.gz \
    --exclude="$SITE_DIR/wp-content/cache" \
    --exclude="$SITE_DIR/wp-content/uploads/backups" \
    $SITE_DIR

# Backup de wp-content (carpeta más importante)
echo "Backing up wp-content..."
tar -czf $BACKUP_DIR/daily/${SITE_NAME}_wp-content_${DATE}.tar.gz \
    $SITE_DIR/wp-content

# Eliminar backups antiguos
echo "Cleaning old backups..."
find $BACKUP_DIR/daily -name "*.gz" -type f -mtime +$KEEP_DAYS -delete

# Log
echo "Backup completed at $(date)" >> $BACKUP_DIR/backup.log

# Enviar notificación (opcional)
# curl -X POST "https://api.telegram.org/botTOKEN/sendMessage" \
#   -d "chat_id=CHAT_ID&text=✅ Backup Translatio completado: $(date)"

echo "Done!"
```

### Script de Backup Semanal (completo)
```bash
#!/bin/bash
# backup-weekly.sh - Backup completo semanal
# Ejecutar: crontab -e → 0 4 * * 0 /root/scripts/backup-weekly.sh

DATE=$(date +%Y%m%d)
SITE_NAME="translatioglobal"
SITE_DIR="/var/www/translatioglobal.com"
BACKUP_DIR="/backups/translatioglobal"
DB_NAME="translatio_db"
DB_USER="translatio_user"
DB_PASS="your_password"
KEEP_WEEKS=12

# Crear directorio
mkdir -p $BACKUP_DIR/weekly

# Backup completo
echo "Creating full backup..."
tar -czf $BACKUP_DIR/weekly/${SITE_NAME}_full_${DATE}.tar.gz $SITE_DIR

# Backup DB
mysqldump -u$DB_USER -p$DB_PASS $DB_NAME | gzip > $BACKUP_DIR/weekly/${SITE_NAME}_db_${DATE}.sql.gz

# Subir a S3/Google Drive (opcional)
# aws s3 sync $BACKUP_DIR/weekly s3://your-bucket/backups/

# Eliminar backups antiguos
find $BACKUP_DIR/weekly -name "*.gz" -type f -mtime +$(($KEEP_WEEKS * 7)) -delete

echo "Weekly backup completed at $(date)" >> $BACKUP_DIR/backup.log
```

### Script de Restauración
```bash
#!/bin/bash
# restore.sh - Restaurar desde backup

BACKUP_FILE=$1
SITE_DIR="/var/www/translatioglobal.com"
DB_NAME="translatio_db"
DB_USER="translatio_user"
DB_PASS="your_password"

if [ -z "$BACKUP_FILE" ]; then
    echo "Usage: ./restore.sh <backup_file.tar.gz>"
    exit 1
fi

# Descomprimir backup
echo "Extracting backup..."
tar -xzf $BACKUP_FILE -C /tmp/

# Restaurar archivos
echo "Restoring files..."
rsync -av --delete /tmp/var/www/translatioglobal.com/ $SITE_DIR/

# Restaurar base de datos
echo "Restoring database..."
gunzip < /tmp/backups/*_db_*.sql.gz | mysql -u$DB_USER -p$DB_PASS $DB_NAME

# Permisos
chown -R www-data:www-data $SITE_DIR
chmod -R 755 $SITE_DIR
chmod 644 $SITE_DIR/wp-config.php

echo "Restore completed!"
```

---

## ⏰ CRON JOBS WORDPRESS

### Configurar en wp-config.php
```php
define('DISABLE_WP_CRON', true);
define('WP_CRON_LOCK_TIMEOUT', 60);
```

### Crontab del sistema
```bash
# Editar crontab
crontab -e

# Agregar líneas:
# WordPress cron cada 15 minutos
*/15 * * * * curl -s https://translatioglobal.com/wp-cron.php?doing_wp_cron > /dev/null 2>&1

# Backup diario a las 3 AM
0 3 * * * /root/scripts/backup-daily.sh

# Backup semanal los domingos a las 4 AM
0 4 * * 0 /root/scripts/backup-weekly.sh

# Renovar SSL cada lunes a las 5 AM
0 5 * * 1 certbot renew --quiet --post-hook "systemctl reload nginx"

# Optimizar DB semanalmente
0 6 * * 0 mysql -u translatio_user -pyour_password -e "OPTIMIZE TABLE translatio_db.wp_posts, translatio_db.wp_postmeta, translatio_db.wp_options;"
```

---

## 🔧 OPTIMIZACIÓN NGINX

### nginx-optimization.conf
```nginx
# Gzip Compression
gzip on;
gzip_vary on;
gzip_proxied any;
gzip_comp_level 6;
gzip_types text/plain text/css text/xml application/json application/javascript application/rss+xml application/atom+xml image/svg+xml;

# Brotli (si está disponible)
# brotli on;
# brotli_comp_level 6;
# brotli_types text/plain text/css application/javascript application/json;

# FastCGI Cache
fastcgi_cache_path /var/cache/nginx levels=1:2 keys_zone=WORDPRESS:100m inactive=60m max_size=512m;
fastcgi_cache_key "$scheme$request_method$host$request_uri";
fastcgi_cache_use_stale error timeout invalid_header http_500 http_503;
fastcgi_ignore_headers Cache-Control Expires Set-Cookie;

# Rate Limiting
limit_req_zone $binary_remote_addr zone=login:10m rate=5r/m;
limit_req_zone $binary_remote_addr zone=general:10m rate=30r/s;

# Upstream PHP
upstream php {
    server unix:/var/run/php/php8.2-fpm.sock;
}
```

### Optimización PHP-FPM
```ini
; /etc/php/8.2/fpm/pool.d/www.conf

[www]
user = www-data
group = www-data
listen = /var/run/php/php8.2-fpm.sock
listen.owner = www-data
listen.group = www-data
listen.mode = 0660

; Process Management
pm = dynamic
pm.max_children = 50
pm.start_servers = 10
pm.min_spare_servers = 5
pm.max_spare_servers = 20
pm.max_requests = 500

; Performance
request_terminate_timeout = 300
pm.status_path = /php-status
slowlog = /var/log/php-fpm-slow.log
request_slowlog_timeout = 10s

; Environment
env[HOSTNAME] = $HOSTNAME
env[PATH] = /usr/local/bin:/usr/bin:/bin
env[TMP] = /tmp
env[TMPDIR] = /tmp
env[TEMP] = /tmp
```

---

## 🚀 SCRIPT DE DEPLOYMENT

### deploy.sh
```bash
#!/bin/bash
# deploy.sh - Deployment a producción
# Uso: ./deploy.sh [branch]

set -e

BRANCH=${1:-main}
SITE_DIR="/var/www/translatioglobal.com"
BACKUP_DIR="/backups/translatioglobal/pre-deploy"
REPO_DIR="/root/repos/translatio-global"

echo "🚀 Starting deployment..."

# 1. Backup pre-deploy
echo "📦 Creating pre-deploy backup..."
mkdir -p $BACKUP_DIR
DATE=$(date +%Y%m%d_%H%M%S)
tar -czf $BACKUP_DIR/pre_deploy_$DATE.tar.gz $SITE_DIR/wp-content

# 2. Pull latest code
echo "📥 Pulling latest code..."
cd $REPO_DIR
git fetch origin
git checkout $BRANCH
git pull origin $BRANCH

# 3. Build assets (si aplica)
echo "🔨 Building assets..."
cd $REPO_DIR
npm install --production
npm run build:prod

# 4. Sync files
echo "📁 Syncing files..."
rsync -av --delete \
    --exclude='wp-config.php' \
    --exclude='wp-content/uploads' \
    --exclude='wp-content/cache' \
    --exclude='.git' \
    --exclude='node_modules' \
    $REPO_DIR/wp-content/ $SITE_DIR/wp-content/

# 5. Update theme
echo "🎨 Updating theme..."
rsync -av $REPO_DIR/wp-content/themes/translatio/ $SITE_DIR/wp-content/themes/translatio/

# 6. Permisos
echo "🔐 Setting permissions..."
chown -R www-data:www-data $SITE_DIR
find $SITE_DIR -type d -exec chmod 755 {} \;
find $SITE_DIR -type f -exec chmod 644 {} \;
chmod 600 $SITE_DIR/wp-config.php

# 7. Flush cache
echo "🧹 Flushing cache..."
cd $SITE_DIR
wp cache flush --allow-root
wp rewrite flush --allow-root

# 8. Database updates (si aplica)
# wp db update --allow-root

# 9. Reload PHP-FPM
echo "🔄 Reloading PHP-FPM..."
systemctl reload php8.2-fpm

# 10. Reload Nginx
echo "🔄 Reloading Nginx..."
nginx -t && systemctl reload nginx

echo "✅ Deployment completed successfully!"
echo "📅 Date: $(date)"
```

---

## 📊 MONITORING

### Script de Health Check
```bash
#!/bin/bash
# health-check.sh - Verificar estado del sitio

SITE_URL="https://translatioglobal.com"
LOG_FILE="/var/log/health-check.log"

# Check HTTP status
HTTP_STATUS=$(curl -s -o /dev/null -w "%{http_code}" $SITE_URL)

if [ $HTTP_STATUS -eq 200 ]; then
    echo "$(date): ✅ Site is UP (HTTP $HTTP_STATUS)" >> $LOG_FILE
else
    echo "$(date): ❌ Site is DOWN (HTTP $HTTP_STATUS)" >> $LOG_FILE
    # Enviar alerta
    # curl -X POST "webhook-url" -d "Site is DOWN"
fi

# Check SSL
SSL_EXPIRY=$(echo | openssl s_client -servername translatioglobal.com -connect translatioglobal.com:443 2>/dev/null | openssl x509 -noout -enddate)
echo "$(date): SSL: $SSL_EXPIRY" >> $LOG_FILE

# Check disk space
DISK_USAGE=$(df -h /var/www | tail -1 | awk '{print $5}' | sed 's/%//')
if [ $DISK_USAGE -gt 80 ]; then
    echo "$(date): ⚠️ High disk usage: $DISK_USAGE%" >> $LOG_FILE
fi
```

### Crontab para monitoring
```bash
# Health check cada 5 minutos
*/5 * * * * /root/scripts/health-check.sh
```

---

## ✅ CHECKLIST DEVOPS

- [ ] SSL Let's Encrypt instalado y configurado
- [ ] Certificados con renovación automática
- [ ] Backup diario programado (3 AM)
- [ ] Backup semanal programado (domingos 4 AM)
- [ ] Scripts de backup probados
- [ ] Script de restauración probado
- [ ] Cron jobs de WordPress configurados
- [ ] Nginx optimizado (gzip, cache)
- [ ] PHP-FPM optimizado
- [ ] Script de deployment funcionando
- [ ] Health check configurado
- [ ] Alertas de monitoreo activas

---

**Estado:** ✅ FASE 8 COMPLETADA
**Siguiente:** FASE 9 - SECURITY

---
*DevOps Agent - Molino Translatio Global*
