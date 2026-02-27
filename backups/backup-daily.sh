#!/bin/bash
# ====================================
# backup-daily.sh - Backup diario de WordPress
# Translatio Global
# ====================================

# Configuración
DATE=$(date +%Y%m%d_%H%M%S)
SITE_NAME="translatioglobal"
SITE_DIR="/var/www/translatioglobal.com"
BACKUP_DIR="/backups/translatioglobal"
DB_NAME="translatio_db"
DB_USER="translatio_user"
DB_PASS="tu_contraseña_segura"
KEEP_DAYS=30

# Crear directorios
mkdir -p $BACKUP_DIR/daily
mkdir -p $BACKUP_DIR/logs

# Log file
LOG_FILE="$BACKUP_DIR/logs/backup-$(date +%Y%m).log"

log() {
    echo "[$(date '+%Y-%m-%d %H:%M:%S')] $1" >> $LOG_FILE
}

log "=========================================="
log "Iniciando backup diario de $SITE_NAME"
log "=========================================="

# 1. Backup de base de datos
log "Backing up database..."
mysqldump -u$DB_USER -p$DB_PASS --single-transaction --routines --triggers $DB_NAME | gzip > "$BACKUP_DIR/daily/${SITE_NAME}_db_${DATE}.sql.gz"

if [ $? -eq 0 ]; then
    log "✅ Database backup completed: ${SITE_NAME}_db_${DATE}.sql.gz"
else
    log "❌ Database backup FAILED!"
    exit 1
fi

# 2. Backup de wp-content (archivos importantes)
log "Backing up wp-content..."
tar -czf "$BACKUP_DIR/daily/${SITE_NAME}_wp-content_${DATE}.tar.gz" \
    --exclude="$SITE_DIR/wp-content/cache" \
    --exclude="$SITE_DIR/wp-content/uploads/backups" \
    --exclude="$SITE_DIR/wp-content/upgrade" \
    "$SITE_DIR/wp-content" 2>/dev/null

if [ $? -eq 0 ]; then
    log "✅ wp-content backup completed: ${SITE_NAME}_wp-content_${DATE}.tar.gz"
else
    log "⚠️ wp-content backup had warnings"
fi

# 3. Backup completo de archivos (opcional, más pesado)
# log "Backing up full site..."
# tar -czf "$BACKUP_DIR/daily/${SITE_NAME}_full_${DATE}.tar.gz" \
#     --exclude="$SITE_DIR/wp-content/cache" \
#     "$SITE_DIR" 2>/dev/null

# 4. Limpiar backups antiguos
log "Cleaning old backups (older than $KEEP_DAYS days)..."
find $BACKUP_DIR/daily -name "*.gz" -type f -mtime +$KEEP_DAYS -delete
log "✅ Old backups cleaned"

# 5. Estadísticas
TOTAL_SIZE=$(du -sh $BACKUP_DIR/daily | cut -f1)
TODAY_FILES=$(ls -1 $BACKUP_DIR/daily/*${DATE}* 2>/dev/null | wc -l)
DB_SIZE=$(ls -lh $BACKUP_DIR/daily/${SITE_NAME}_db_${DATE}.sql.gz | awk '{print $5}')
CONTENT_SIZE=$(ls -lh $BACKUP_DIR/daily/${SITE_NAME}_wp-content_${DATE}.tar.gz | awk '{print $5}')

log "------------------------------------------"
log "Backup Statistics:"
log "  - Total backup size: $TOTAL_SIZE"
log "  - Files created today: $TODAY_FILES"
log "  - Database size: $DB_SIZE"
log "  - wp-content size: $CONTENT_SIZE"
log "------------------------------------------"

# 6. Enviar notificación (descomentar si se desea)
# NOTIFICATION_WEBHOOK="https://api.telegram.org/botTOKEN/sendMessage"
# MESSAGE="✅ Backup Translatio completado: $(date '+%Y-%m-%d %H:%M')
# DB: $DB_SIZE
# Files: $CONTENT_SIZE"
# curl -s -X POST $NOTIFICATION_WEBHOOK -d "chat_id=CHAT_ID&text=$MESSAGE" > /dev/null

log "✅ Backup completed successfully!"
log ""

exit 0
