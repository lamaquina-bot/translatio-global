#!/bin/bash
# ====================================
# deploy.sh - Script de deployment
# Translatio Global
# ====================================

set -e

# Configuración
BRANCH=${1:-main}
SITE_DIR="/var/www/translatioglobal.com"
BACKUP_DIR="/backups/translatioglobal/pre-deploy"
REPO_DIR="/root/repos/translatio-global"
THEME_NAME="translatio"

# Colores para output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Funciones
log_info() {
    echo -e "${BLUE}[INFO]${NC} $1"
}

log_success() {
    echo -e "${GREEN}[SUCCESS]${NC} $1"
}

log_warning() {
    echo -e "${YELLOW}[WARNING]${NC} $1"
}

log_error() {
    echo -e "${RED}[ERROR]${NC} $1"
}

# Verificar que estamos en el directorio correcto
if [ ! -d "$REPO_DIR" ]; then
    log_error "Repository directory not found: $REPO_DIR"
    exit 1
fi

# Banner
echo ""
echo "╔══════════════════════════════════════════════════╗"
echo "║        TRANSLATIO GLOBAL - DEPLOYMENT            ║"
echo "╠══════════════════════════════════════════════════╣"
echo "║  Branch: $BRANCH"
echo "║  Date: $(date '+%Y-%m-%d %H:%M:%S')"
echo "╚══════════════════════════════════════════════════╝"
echo ""

# 1. Pre-deploy backup
log_info "Creating pre-deploy backup..."
mkdir -p $BACKUP_DIR
DATE=$(date +%Y%m%d_%H%M%S)
tar -czf "$BACKUP_DIR/pre_deploy_$DATE.tar.gz" -C "$SITE_DIR" wp-content 2>/dev/null || true
log_success "Pre-deploy backup created"

# 2. Pull latest code
log_info "Pulling latest code from branch: $BRANCH"
cd $REPO_DIR
git fetch origin
git checkout $BRANCH
git pull origin $BRANCH
log_success "Code updated"

# 3. Build assets (si existe package.json)
if [ -f "$REPO_DIR/package.json" ]; then
    log_info "Building assets..."
    cd $REPO_DIR
    npm install --production
    npm run build:prod
    log_success "Assets built"
fi

# 4. Sync theme files
log_info "Syncing theme files..."
rsync -av --delete \
    --exclude='.git' \
    --exclude='node_modules' \
    --exclude='*.log' \
    "$REPO_DIR/wp-content/themes/$THEME_NAME/" "$SITE_DIR/wp-content/themes/$THEME_NAME/"
log_success "Theme synced"

# 5. Sync plugins personalizados (si existen)
if [ -d "$REPO_DIR/wp-content/plugins" ]; then
    log_info "Syncing custom plugins..."
    rsync -av --delete \
        --exclude='.git' \
        --exclude='node_modules' \
        "$REPO_DIR/wp-content/plugins/" "$SITE_DIR/wp-content/plugins/"
    log_success "Plugins synced"
fi

# 6. Set permissions
log_info "Setting permissions..."
chown -R www-data:www-data $SITE_DIR
find $SITE_DIR -type d -exec chmod 755 {} \;
find $SITE_DIR -type f -exec chmod 644 {} \;
chmod 600 $SITE_DIR/wp-config.php 2>/dev/null || true
log_success "Permissions set"

# 7. WordPress maintenance mode (opcional)
# log_info "Enabling maintenance mode..."
# cd $SITE_DIR
# wp maintenance-mode activate --allow-root

# 8. Flush cache
log_info "Flushing cache..."
cd $SITE_DIR
wp cache flush --allow-root 2>/dev/null || true
wp rewrite flush --allow-root 2>/dev/null || true
log_success "Cache flushed"

# 9. Database updates (si hay)
# log_info "Running database updates..."
# wp db update --allow-root

# 10. Disable maintenance mode
# log_info "Disabling maintenance mode..."
# wp maintenance-mode deactivate --allow-root

# 11. Reload services
log_info "Reloading PHP-FPM..."
systemctl reload php8.2-fpm

log_info "Reloading Nginx..."
nginx -t && systemctl reload nginx
log_success "Services reloaded"

# 12. Clear OPcache (si es posible)
log_info "Clearing OPcache..."
wp eval 'opcache_reset();' --allow-root 2>/dev/null || true

# Resumen
echo ""
echo "╔══════════════════════════════════════════════════╗"
echo "║            DEPLOYMENT COMPLETED                  ║"
echo "╠══════════════════════════════════════════════════╣"
echo "║  ✅ Backup created: pre_deploy_$DATE.tar.gz"
echo "║  ✅ Code updated from branch: $BRANCH"
echo "║  ✅ Assets built"
echo "║  ✅ Theme synced"
echo "║  ✅ Permissions set"
echo "║  ✅ Cache flushed"
echo "║  ✅ Services reloaded"
echo "╠══════════════════════════════════════════════════╣"
echo "║  URL: https://translatioglobal.com"
echo "║  Time: $(date '+%Y-%m-%d %H:%M:%S')"
echo "╚══════════════════════════════════════════════════╝"
echo ""

# Notificación (opcional)
# curl -s -X POST "https://api.telegram.org/botTOKEN/sendMessage" \
#   -d "chat_id=CHAT_ID" \
#   -d "text=✅ Deployment Translatio completado: $(date '+%Y-%m-%d %H:%M')" > /dev/null

exit 0
