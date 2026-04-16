# 🚀 DevOps Agent Run — Translatio Global

**VERSIÓN:** 1.0 | **Fecha:** 16 Abril 2026 | **Agente:** DevOps Agent de MOLINO

---

## 1. INFRAESTRUCTURA

```
VPS:        89.117.33.22 (existente)
Orquestador: Coolify v4.x
Proxy:       Traefik v3.6.8 (ya corriendo)
SSL:         Let's Encrypt (auto-renew via Traefik)
DNS:         thefuckinggoat.cloud (ya configurado)
Dominio:     translatio.thefuckinggoat.cloud (o dominio propio del cliente)
```

---

## 2. DEPLOYMENT EN COOLIFY

### Paso 1: Crear Proyecto
```
Coolify Dashboard → Projects → "translatio-global"
O usar proyecto existente "sandbox" para staging
```

### Paso 2: Crear Aplicación
```
Tipo: Docker Compose
Source: GitHub repo (lamaquina-bot/translatio-global)
Branch: main
Path: /docker-compose.yml
```

### Paso 3: docker-compose.yml
```yaml
version: '3.8'

services:
  wordpress:
    image: wordpress:6.8-php8.2-apache
    restart: unless-stopped
    environment:
      WORDPRESS_DB_HOST: mysql
      WORDPRESS_DB_NAME: ${DB_NAME:-translatio}
      WORDPRESS_DB_USER: ${DB_USER:-translatio}
      WORDPRESS_DB_PASSWORD: ${DB_PASSWORD}
      WORDPRESS_DEBUG: "false"
      WP_MEMORY_LIMIT: "256M"
      WP_MAX_MEMORY_LIMIT: "512M"
    volumes:
      - wp_html:/var/www/html
      - ./wp-content/themes/translatio:/var/www/html/wp-content/themes/translatio
      - ./wp-content/plugins/translatio-custom:/var/www/html/wp-content/plugins/translatio-custom
      - ./uploads.ini:/usr/local/etc/php/conf.d/uploads.ini
    depends_on:
      mysql:
        condition: service_healthy
    labels:
      - "traefik.enable=true"
      - "traefik.http.routers.translatio.rule=Host(`translatio.thefuckinggoat.cloud`)"
      - "traefik.http.routers.translatio.entrypoints=websecure"
      - "traefik.http.routers.translatio.tls.certresolver=letsencrypt"
      - "traefik.http.services.translatio.loadbalancer.server.port=80"
      # Security headers
      - "traefik.http.middlewares.translatio-sec.headers.browserxssfilter=true"
      - "traefik.http.middlewares.translatio-sec.headers.contenttypenosniff=true"
      - "traefik.http.middlewares.translatio-sec.headers.forcestsheader=true"
      - "traefik.http.middlewares.translatio-sec.headers.sslredirect=true"
      - "traefik.http.middlewares.translatio-sec.headers.stsseconds=31536000"
      - "traefik.http.routers.translatio.middlewares=translatio-sec"
    networks:
      - coolify

  mysql:
    image: mysql:8.0
    restart: unless-stopped
    environment:
      MYSQL_DATABASE: ${DB_NAME:-translatio}
      MYSQL_USER: ${DB_USER:-translatio}
      MYSQL_PASSWORD: ${DB_PASSWORD}
      MYSQL_ROOT_PASSWORD: ${DB_ROOT_PASSWORD}
    volumes:
      - db_data:/var/lib/mysql
    healthcheck:
      test: ["CMD", "mysqladmin", "ping", "-h", "localhost"]
      interval: 10s
      timeout: 5s
      retries: 5
    networks:
      - coolify

volumes:
  wp_html:
  db_data:

networks:
  coolify:
    external: true
```

### uploads.ini
```ini
file_uploads = On
memory_limit = 256M
upload_max_filesize = 10M
post_max_size = 12M
max_execution_time = 120
```

---

## 3. CI/CD

### GitHub Actions (deploy automático)
```yaml
name: Deploy Translatio
on:
  push:
    branches: [main]
    paths:
      - 'wp-content/**'
      - 'docker-compose.yml'

jobs:
  deploy:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v4
      
      - name: Deploy via Coolify API
        run: |
          curl -X GET \
            "${{ secrets.COOLIFY_URL }}/api/v1/applications/${{ secrets.COOLIFY_APP_UUID }}/deploy" \
            -H "Authorization: Bearer ${{ secrets.COOLIFY_TOKEN }}"
```

---

## 4. MONITORING

```
Uptime:        Coolify built-in health checks
Logs:          docker logs wordpress → stdout
Error tracking: WordPress debug.log (rotado semanal)
Performance:   Query monitor plugin (solo en staging)
Alertas:       Email si container reinicia o health check falla
```

---

## 5. BACKUP STRATEGY

```
Daily (2am UTC):
  - mysqldump → /backups/translatio/db/YYYY-MM-DD.sql.gz
  - wp-content tar → /backups/translatio/files/YYYY-MM-DD.tar.gz
  
Weekly (Sunday):
  - Full volume snapshot via Coolify

Retention:
  - Daily: 30 días
  - Weekly: 4 semanas
  - Offsite: Backblaze B2 (90 días)

Restore test: Primer lunes de cada mes
```

---

## 6. DOMINIO DNS

```
Si cliente compra dominio propio (translatioglobal.com):
  DNS A record → 89.117.33.22
  Traefik rule: Host(`translatioglobal.com`) + Host(`www.translatioglobal.com`)
  Let's Encrypt auto-certificate

Mientras tanto:
  Subdominio: translatio.thefuckinggoat.cloud (ya funcional)
```

---

## 7. CHECKLIST DEPLOY

```
Pre-deploy:
  ☐ Variables de entorno configuradas en Coolify
  ☐ DNS apuntando al VPS
  ☐ Traefik labels correctos
  ☐ SSL certificate generado
  ☐ WordPress instalado y configurado
  ☐ WPML/Polylang instalado y configurado
  ☐ Tema Translatio activado
  ☐ Plugins instalados (security, SEO, cache, forms)
  ☐ Chatbot integrado (Tidio/custom)
  ☐ Email SMTP configurado y testeado
  ☐ GA4 tracking code instalado

Post-deploy:
  ☐ Smoke test: cada página carga en cada idioma
  ☐ Formulario envía correctamente
  ☐ Chatbot responde en cada idioma
  ☐ SSL verificado (HTTPS, headers)
  ☐ Performance check (LCP < 2.5s)
  ☐ Mobile responsive check
  ☐ Backup automático confirmado
```

---

**FIN DE DEVOPS AGENT RUN**
