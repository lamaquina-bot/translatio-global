# INSTRUCCIONES DE DEPLOYMENT - Translatio Global

**Versión:** 1.0.0
**Fecha:** 27 Feb 2026

---

## 📋 PRERREQUISITOS

### Servidor
- [ ] Ubuntu 20.04+ o similar
- [ ] PHP 8.1+
- [ ] MySQL 8.0+
- [ ] Nginx o Apache
- [ ] Composer (opcional)
- [ ] Node.js 18+ (para build)
- [ ] WP-CLI instalado

### Accesos
- [ ] SSH al servidor
- [ ] Acceso root o sudo
- [ ] Credenciales de base de datos
- [ ] Acceso FTP/SFTP (alternativo)

---

## 🚀 DEPLOYMENT PASO A PASO

### 1. Preparar el Servidor

```bash
# Actualizar sistema
sudo apt update && sudo apt upgrade -y

# Instalar dependencias
sudo apt install -y nginx mysql-server php8.2-fpm php8.2-mysql php8.2-curl php8.2-gd php8.2-mbstring php8.2-xml php8.2-zip

# Instalar Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt install -y nodejs

# Instalar WP-CLI
curl -O https://raw.githubusercontent.com/wp-cli/builds/gh-pages/phar/wp-cli.phar
chmod +x wp-cli.phar
sudo mv wp-cli.phar /usr/local/bin/wp
```

### 2. Crear Base de Datos

```bash
# Login a MySQL
sudo mysql

# Crear database y usuario
CREATE DATABASE translatio_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'translatio_user'@'localhost' IDENTIFIED BY 'tu_contraseña_segura';
GRANT ALL PRIVILEGES ON translatio_db.* TO 'translatio_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 3. Instalar WordPress

```bash
# Crear directorio
sudo mkdir -p /var/www/translatioglobal.com
sudo chown -R $USER:$USER /var/www/translatioglobal.com

# Descargar WordPress
cd /var/www/translatioglobal.com
wp core download --locale=es_ES

# Crear wp-config.php
wp config create --dbname=translatio_db --dbuser=translatio_user --dbpass='tu_contraseña_segura'

# Instalar WordPress
wp core install --url=translatioglobal.com --title="Translatio Global" --admin_user=admin --admin_email=admin@translatioglobal.com --admin_password='tu_contraseña_admin'
```

### 4. Subir Archivos del Tema

```bash
# Clonar o subir el repositorio
cd /tmp
git clone https://github.com/tu-repo/translatio-global.git

# Copiar tema
cp -r translatio-global/wp-content/themes/translatio /var/www/translatioglobal.com/wp-content/themes/

# Copiar archivos de configuración
cp translatio-global/config/wp-config-optimizado.php /var/www/translatioglobal.com/wp-config.php

# Copiar scripts
mkdir -p /root/scripts
cp translatio-global/backups/*.sh /root/scripts/
chmod +x /root/scripts/*.sh
```

### 5. Instalar Plugins

```bash
cd /var/www/translatioglobal.com

# Instalar plugins gratuitos
wp plugin install polylang --activate
wp plugin install cmb2 --activate
wp plugin install wordpress-seo --activate
wp plugin install wp-super-cache --activate
wp plugin install contact-form-7 --activate
wp plugin install wp-mail-smtp --activate
wp plugin install wordfence --activate
wp plugin install wp-optimize --activate
wp plugin install smush --activate

# Activar tema
wp theme activate translatio
```

### 6. Configurar Permisos

```bash
# Permisos de archivos
sudo chown -R www-data:www-data /var/www/translatioglobal.com
sudo find /var/www/translatioglobal.com -type d -exec chmod 755 {} \;
sudo find /var/www/translatioglobal.com -type f -exec chmod 644 {} \;
sudo chmod 600 /var/www/translatioglobal.com/wp-config.php
```

### 7. Configurar Nginx

```bash
# Copiar configuración
sudo cp /tmp/translatio-global/config/nginx-config /etc/nginx/sites-available/translatioglobal.com

# Habilitar sitio
sudo ln -s /etc/nginx/sites-available/translatioglobal.com /etc/nginx/sites-enabled/

# Test y reload
sudo nginx -t
sudo systemctl reload nginx
```

### 8. Configurar SSL

```bash
# Instalar Certbot
sudo apt install -y certbot python3-certbot-nginx

# Obtener certificado
sudo certbot --nginx -d translatioglobal.com -d www.translatioglobal.com

# Verificar renovación automática
sudo certbot renew --dry-run
```

### 9. Configurar Cron Jobs

```bash
# Editar crontab
crontab -e

# Agregar líneas:
*/15 * * * * curl -s https://translatioglobal.com/wp-cron.php?doing_wp_cron > /dev/null 2>&1
0 3 * * * /root/scripts/backup-daily.sh
0 4 * * 0 /root/scripts/backup-weekly.sh
0 5 * * 1 certbot renew --quiet --post-hook "systemctl reload nginx"
```

### 10. Build de Assets (Producción)

```bash
cd /tmp/translatio-global
npm install
npm run build:prod

# Copiar assets compilados
cp -r wp-content/themes/translatio/assets /var/www/translatioglobal.com/wp-content/themes/translatio/
```

### 11. Configurar Polylang

```bash
cd /var/www/translatioglobal.com

# Crear idiomas
wp pll lang create es "Español" es_ES --order=1
wp pll lang create en "English" en_US --order=2
wp pll lang create pt "Português" pt_BR --order=3
wp pll lang create zh "中文" zh_CN --order=4
wp pll lang create fr "Français" fr_FR --order=5

# Flush permalinks
wp rewrite flush
```

### 12. Verificación Final

```bash
# Verificar estado
wp core verify-checksums
wp plugin list --status=active
wp theme list

# Test de performance
curl -w "@curl-format.txt" -o /dev/null -s https://translatioglobal.com/

# Verificar SSL
curl -I https://translatioglobal.com/
```

---

## 🔄 ACTUALIZACIONES FUTURAS

### Deploy de actualizaciones

```bash
# Usar el script de deployment
/root/scripts/deploy.sh main

# O manualmente:
cd /tmp/translatio-global
git pull origin main
npm run build:prod
rsync -av wp-content/themes/translatio/ /var/www/translatioglobal.com/wp-content/themes/translatio/
wp cache flush --allow-root
```

---

## 🔧 TROUBLESHOOTING

### Error 500
```bash
# Revisar logs
tail -f /var/log/nginx/error.log
tail -f /var/www/translatioglobal.com/wp-content/debug.log

# Verificar permisos
sudo chown -R www-data:www-data /var/www/translatioglobal.com
```

### SSL no funciona
```bash
# Regenerar certificado
sudo certbot --nginx -d translatioglobal.com --force-renewal
```

### Cache no funciona
```bash
# Verificar WP Super Cache
wp super-cache status

# Flush manual
rm -rf /var/www/translatioglobal.com/wp-content/cache/*
```

---

## 📞 SOPORTE

Para problemas críticos:
1. Revisar logs de error
2. Desactivar plugins temporalmente
3. Verificar configuración de servidor
4. Restaurar desde backup si es necesario

---

**Documento creado:** 27 Feb 2026
**Última actualización:** 27 Feb 2026
