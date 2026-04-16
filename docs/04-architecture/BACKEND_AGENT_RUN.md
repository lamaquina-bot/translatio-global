# ⚙️ Backend Agent Run — Translatio Global

**VERSIÓN:** 1.0 | **Fecha:** 16 Abril 2026 | **Agente:** Backend Agent de MOLINO

---

## 1. STACK

```
CMS:           WordPress 6.x (Docker oficial)
Multilenguaje: WPML (licenciado) o Polylang (gratuito)
Chatbot:       Custom endpoint + Tidio/Landbot SDK
Reverse Proxy: Traefik v3.6.8
Hosting:       Coolify on VPS 89.117.33.22
SSL:           Let's Encrypt via Traefik
SMTP:          ProtonMail Bridge o SendGrid
DB:            MySQL 8.0 (Coolify managed)
Backups:       Coolify scheduled + S3/Backblaze B2
```

---

## 2. PLUGINS WORDPRESS

### Obligatorios
```
1. WPML o Polylang          — i18n (5 idiomas)
2. Contact Form 7           — formularios de contacto
3. Flamingo                  — almacenar submissions en DB
4. Wordfence Security       — firewall + malware scan
5. WP Rocket o LiteSpeed    — cache + performance
6. Yoast SEO                — SEO multilenguaje
7. GDPR Cookie Consent      — cookie banner + consent
```

### Chatbot
```
Opción A: Tidio (SaaS) — WordPress plugin, multilenguaje, analytics
Opción B: Landbot — Visual builder, WhatsApp integration
Opción C: Custom — React widget + WordPress REST API endpoint
```

**Recomendación:** Tidio para MVP (setup rápido, multilenguaje nativo, lead capture).

---

## 3. API ENDPOINTS

### Contact Form
```
POST /wp-json/translatio/v1/contact
Body: { name, email, country, language, message, source_page }
Response: { success: true, ticket_id: "TK-XXXX" }
Rate limit: 5 submissions / hour / IP
Validation: nombre > 2 chars, email válido, país en lista, mensaje < 2000 chars
```

### Chatbot Webhook
```
POST /wp-json/translatio/v1/chatbot/lead
Body: { name, email, country, language, source: "chatbot", first_question }
Response: { success: true, ticket_id: "TK-XXXX" }
Rate limit: 3 submissions / hour / IP

GET /wp-json/translatio/v1/chatbot/faq?lang=es&q=que-es-subrogacion
Response: { question, answer, related: [...], escalate: false }
```

### Lead Management
```
GET /wp-json/translatio/v1/leads?status=new
Response: { leads: [...], total, page }

PATCH /wp-json/translatio/v1/leads/{id}
Body: { status: "contacted" | "qualified" | "closed" }
```

---

## 4. BASE DE DATOS

### WordPress Default
```
wp_posts          → Páginas en cada idioma (5 × 6 = 30 posts)
wp_postmeta       → Metadata por página
wp_options        → Config del sitio
wp_users          → Admin + editores
wp_terms          → Categorías, tags (traducidos)
```

### Custom Tables
```sql
CREATE TABLE wp_translatio_leads (
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  email VARCHAR(255) NOT NULL,
  country VARCHAR(2),
  language VARCHAR(5) DEFAULT 'es',
  source ENUM('form','chatbot','whatsapp') DEFAULT 'form',
  source_page VARCHAR(100),
  first_question TEXT,
  status ENUM('new','contacted','qualified','closed','spam') DEFAULT 'new',
  gdpr_consent BOOLEAN DEFAULT FALSE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  contacted_at TIMESTAMP NULL,
  notes TEXT,
  INDEX idx_status (status),
  INDEX idx_email (email),
  INDEX idx_created (created_at)
);

CREATE TABLE wp_translatio_chatbot_log (
  id BIGINT AUTO_INCREMENT PRIMARY KEY,
  session_id VARCHAR(50) NOT NULL,
  language VARCHAR(5),
  question TEXT,
  answer TEXT,
  escalated BOOLEAN DEFAULT FALSE,
  lead_captured BOOLEAN DEFAULT FALSE,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_session (session_id),
  INDEX idx_created (created_at)
);
```

---

## 5. INTEGRACIÓN CHATBOT

### Flujo Backend
```
Usuario pregunta → Chatbot widget (frontend)
  → POST /wp-json/translatio/v1/chatbot/faq
    → Buscar en FAQ local (wp_options con transients)
    → Si match → responder
    → Si no match → "¿Quieres que un especialista te contacte?"
      → POST /wp-json/translatio/v1/chatbot/lead
        → Insertar en wp_translatio_leads
        → Enviar email a equipo Translatio
        → Responder confirmación al usuario
```

### FAQ Storage
```
wp_options: translatio_faq_{lang}
Tipo: ARRAY -> serialize
Contenido: [{ id, question, answer, keywords[], related[] }]

Actualizar via admin panel o import CSV.
Cache en transients (1 hora).
```

---

## 6. EMAIL NOTIFICATIONS

```
SMTP: SendGrid (free tier: 100 emails/día)

TEMPLATES:
1. Nuevo lead → equipo@translatio.com
   Subject: "[Translatio] Nuevo lead de {country} ({language})"
   Body: nombre, email, país, idioma, origen, pregunta

2. Confirmación al usuario → user email
   Subject: "Gracias por contactar a Translatio"
   Body: confirmación + próximo paso + horario de contacto

3. Newsletter opt-in (si lo piden en futuro)
```

---

## 7. SEGURIDAD

```
HTTPS:          Traefik + Let's Encrypt (auto-renew)
Headers:        X-Frame-Options, X-Content-Type-Options, CSP, HSTS
Rate Limiting:  Traefik middleware (100 req/min por IP)
Form Spam:      Honeypot + reCAPTCHA v3 (invisible)
SQL Injection:  WordPress $wpdb->prepare() obligatorio
XSS:           wp_kses_post() en output, sanitize_text_field() en input
Auth:          WordPress robust passwords + 2FA para admin
Backups:       Daily DB dump + weekly full backup via Coolify
GDPR:          Cookie consent + data retention 2 años + delete on request
```

---

## 8. PERFORMANCE

```
Cache:          WP Rocket (page cache + browser cache + lazy load)
CDN:            Cloudflare free tier (DNS + cache estáticos)
Images:         WebP conversion + lazy load + srcset responsive
DB:             Query optimization + index en custom tables
PHP:            8.2+ con OPcache
Memory:         WP_MEMORY_LIMIT 256MB
GZIP:           Traefik compression middleware
```

---

## 9. BACKUP & RECOVERY

```
Daily:   mysqldump → /backups/db/YYYY-MM-DD.sql (retenión 30 días)
Weekly:  Full WordPress tar → /backups/full/ (retención 4 semanas)
Offsite: Backblaze B2 o S3 (retención 90 días)

Recovery test: mensual (restaurar a staging)
```

---

## 10. COOLIFY DEPLOYMENT

```yaml
# docker-compose.yml (Coolify managed)
services:
  wordpress:
    image: wordpress:6-php8.2-apache
    environment:
      WORDPRESS_DB_HOST: mysql
      WORDPRESS_DB_NAME: translatio
      WORDPRESS_DB_USER: ${DB_USER}
      WORDPRESS_DB_PASSWORD: ${DB_PASSWORD}
    volumes:
      - wp_data:/var/www/html
      - ./wp-content/themes/translatio:/var/www/html/wp-content/themes/translatio
      - ./wp-content/plugins/translatio-custom:/var/www/html/wp-content/plugins/translatio-custom
    labels:
      - "traefik.enable=true"
      - "traefik.http.routers.translatio.rule=Host(`translatio.thefuckinggoat.cloud`)"
      - "traefik.http.routers.translatio.tls.certresolver=letsencrypt"
  
  mysql:
    image: mysql:8.0
    environment:
      MYSQL_DATABASE: translatio
      MYSQL_USER: ${DB_USER}
      MYSQL_PASSWORD: ${DB_PASSWORD}
      MYSQL_ROOT_PASSWORD: ${DB_ROOT_PASSWORD}
    volumes:
      - db_data:/var/lib/mysql

volumes:
  wp_data:
  db_data:
```

---

**FIN DE BACKEND AGENT RUN**
