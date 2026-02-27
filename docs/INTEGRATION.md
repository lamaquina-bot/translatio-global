# 🔌 FASE 7: INTEGRATION - Translatio Global

**Fecha:** 27 Feb 2026
**Agente:** Integration Agent
**Estado:** ✅ COMPLETADO

---

## 📦 WP SUPER CACHE

### Instalación
```bash
wp plugin install wp-super-cache --activate
```

### Configuración (Settings → WP Super Cache)

#### Easy Tab
- ✅ **Caching On** (Recommended)
- ✅ Use mod_rewrite to serve cache files

#### Advanced Tab
- ✅ **Cache hits to this website for quick access**
- ✅ Use mod_rewrite to serve cache files
- ✅ Compress pages so they're served more quickly
- ✅ 304 Not Modified browser caching
- ✅ Don't cache pages for known users
- ✅ Don't cache pages with GET parameters
- ✅ Mobile device support

#### Preload Tab
- ✅ **Preload cache now**
- Refresh preloaded cache files every 1440 minutes (24 horas)

#### CDN (opcional)
- Si usas CloudFlare u otro CDN, configurar aquí

### .htaccess para WP Super Cache
```apache
# BEGIN WPSuperCache
<IfModule mod_rewrite.c>
RewriteEngine On
RewriteBase /
AddDefaultCharset UTF-8
RewriteCond %{REQUEST_METHOD} !POST
RewriteCond %{QUERY_STRING} !.*=.*
RewriteCond %{HTTP:Cookie} !^.*(comment_author_|wordpress_logged_in|wp-postpass_).*$
RewriteCond %{HTTP:X-Wap-Profile} !^[a-z0-9\"]+ [NC]
RewriteCond %{HTTP:Profile} !^[a-z0-9\"]+ [NC]
RewriteCond %{HTTP_USER_AGENT} !^.*(2.0\ MMP|240x320|400X240|AvantGo|BlackBerry|Blazer|Cellphone|Danger|DoCoMo| Elaine/3.0|EudoraWeb|Googlebot-Mobile|hiptop|IEMobile|KYOCERA/WX310K|LG/U990| MIDP-2.|MMEF20|MOT-V|NetFront|Newt|Nintendo\ Wii|Nitro|Nokia|Opera\ Mini|Palm|PlayStation\ Portable|portalmmm|Proxinet|ProxiNet|SHARP-TQ-GX10|SHARP/iZAR|small|SonyEricsson|Symbian\ OS|SymbianOS|TS21i-10|UP.Browser|UP.Link|webOS|Windows\ CE|WinWAP|YahooSeeker/M1A1-R2D2|iPhone|iPod|Android|BlackBerry9530|LG-TU915\ Obigo|LGE\ VX|webOS|Nokia5800).*
RewriteCond %{HTTP_user_agent} !\ (safari|chrome)
RewriteCond %{DOCUMENT_ROOT}/wp-content/cache/supercache/%{HTTP_HOST}%{REQUEST_URI}/index.html -f
RewriteRule ^(.*) "/wp-content/cache/supercache/%{HTTP_HOST}%{REQUEST_URI}/index.html" [L]
</IfModule>
# END WPSuperCache
```

---

## 🖼️ SMUSH (Optimización de Imágenes)

### Instalación
```bash
wp plugin install smush --activate
```

### Configuración (Smush → Settings)

#### Bulk Smush
1. Ir a Smush → Bulk Smush
2. Click "BULK SMUSH NOW"
3. Esperar a que procese todas las imágenes

#### Configuración Automática
- ✅ **Automatically smush my images on upload**
- ✅ **Strip my image metadata**
- Original Image: ❌ NO guardar (ahorra espacio)
- ✅ **Resize full images**
  - Width: 1920px, Height: 1080px

#### Lazy Load
- ✅ **Lazy Load**
- ✅ **YouTube placeholder** (para videos)

#### CDN (Opcional con plan pago)
- ❌ No usar (versión gratuita)

### Comando WP-CLI para optimizar
```bash
wp smush directory /var/www/html/wp-content/uploads --recursive
```

---

## 🛡️ WORDFENCE (Seguridad)

### Instalación
```bash
wp plugin install wordfence --activate
```

### Configuración (Wordfence → All Options)

#### General
- ✅ **Enable Wordfence Firewall**
- Learning Mode: 7 días (luego Extended Protection)
- ✅ **Rate limit search engine crawlers**

#### Firewall
- Protection Level: **Extended Protection**
- ✅ **Real-time IP blocklist**
- ✅ **Block fake Google crawlers**

#### Login Security
- ✅ **Enable reCAPTCHA**
  - Site Key: [obtener de Google]
  - Secret Key: [obtener de Google]
- ✅ **Two-factor authentication** (2FA) para admins

#### Scanning
- ✅ **Scan for malware**
- ✅ **Check for backdoors**
- ✅ **Check for suspicious code**
- Schedule: Daily at 3:00 AM

#### Blocking
- Block IPs with:
  - Failed login attempts: 5
  - 404 errors: 20
  - Time period: 5 minutes
  - Block duration: 1 hour

#### Email Alerts
- ✅ **Email me when an administrator account is created**
- ✅ **Email me when someone is locked out**
- ✅ **Email me when Wordfence finds a problem**

---

## 📧 WP MAIL SMTP

### Instalación
```bash
wp plugin install wp-mail-smtp --activate
```

### Configuración (Settings → WP Mail SMTP)

#### From Email
- From Email: `noreply@translatioglobal.com`
- From Name: `Translatio Global`
- ✅ Force From Email
- ✅ Force From Name

#### Mailer
- Seleccionar: **Other SMTP** (o tu proveedor preferido)

#### SMTP Settings (Gmail ejemplo)
```
SMTP Host: smtp.gmail.com
Encryption: TLS
SMTP Port: 587
Auto TLS: On
Authentication: On
SMTP Username: tu-email@gmail.com
SMTP Password: [App Password de Google]
```

#### SMTP Settings (Mailgun/SendGrid)
```
# Mailgun
SMTP Host: smtp.mailgun.org
SMTP Port: 587
Encryption: TLS

# SendGrid
SMTP Host: smtp.sendgrid.net
SMTP Port: 587
Encryption: TLS
```

#### Email Test
1. Click "Send Email" para verificar
2. Revisar bandeja de entrada

---

## 🗄️ WP OPTIMIZE

### Instalación
```bash
wp plugin install wp-optimize --activate
```

### Configuración (WP-Optimize → Database)

#### Optimizations (Run Weekly)
- ✅ Clean-up post revisions
- ✅ Clean-up auto drafts
- ✅ Clean-up spam comments
- ✅ Clean-up trash comments
- ✅ Clean-up transient options
- ✅ Clean-up pingbacks
- ✅ Clean-up trackbacks
- ❌ Clean-up orphaned post meta (manual first)

#### Schedule
- ✅ **Run scheduled clean-up**
- Schedule type: Weekly
- Day: Sunday
- Time: 3:00 AM

#### Tables Optimization
- ✅ Optimize database tables

---

## 📊 YOAST SEO

### Instalación
```bash
wp plugin install wordpress-seo --activate
```

### Configuración Inicial (SEO → General)

#### Features
- ✅ SEO analysis
- ✅ Readability analysis
- ✅ Cornerstone content
- ✅ Text link counter
- ✅ XML sitemaps
- ✅ Open Graph
- ✅ Twitter cards

#### Webmaster Tools
- Google Search Console: [verificar]
- Bing Webmaster Tools: [verificar]

### XML Sitemap
```
https://translatioglobal.com/sitemap_index.xml
```

### Robots.txt
```txt
User-agent: *
Disallow: /wp-admin/
Disallow: /wp-includes/
Disallow: /wp-content/plugins/
Disallow: /wp-content/themes/
Allow: /wp-admin/admin-ajax.php

Sitemap: https://translatioglobal.com/sitemap_index.xml
```

---

## 🌍 POLYLANG

### Instalación
```bash
wp plugin install polylang --activate
```

### Configuración (Idiomas → Ajustes)

#### Idiomas
Agregar los 5 idiomas:
1. Español (es_ES) - Default
2. English (en_US)
3. Português (pt_BR)
4. 中文 (zh_CN)
5. Français (fr_FR)

#### URL Modificaciones
- URL del idioma: **El código del idioma se añade a todas las URLs**
- Ejemplo: `/en/`, `/pt/`, `/zh/`, `/fr/`

#### Detectar idioma del navegador
- ❌ NO activar (puede causar problemas de SEO)

#### Medios
- ✅ Los medios son traducibles

#### Sincronización
- ✅ Categorías
- ✅ Imagen destacada
- ✅ Extracto

### Comandos WP-CLI
```bash
# Crear idiomas
wp pll lang create es "Español" es_ES --order=1
wp pll lang create en "English" en_US --order=2
wp pll lang create pt "Português" pt_BR --order=3
wp pll lang create zh "中文" zh_CN --order=4
wp pll lang create fr "Français" fr_FR --order=5

# Copiar contenido entre idiomas
wp pll post duplicate <post_id>
```

---

## 📝 CONTACT FORM 7

### Instalación
```bash
wp plugin install contact-form-7 --activate
```

### Configuración
Los formularios se crean automáticamente al activar el tema. Ver `inc/contact-forms.php`.

### Integración con WP Mail SMTP
El plugin usa automáticamente la configuración de WP Mail SMTP.

### Protección contra spam
- ✅ **reCAPTCHA v3** (configurar en Contact → Integration)
- O usar **hCaptcha** como alternativa gratuita

### Comando para crear formulario
```bash
wp cf7 create "Contacto General" --path=/path/to/theme/inc/contact-forms.php
```

---

## ✅ CHECKLIST INTEGRATION

- [ ] WP Super Cache activado y configurado
- [ ] Smush ejecutado en todas las imágenes
- [ ] Wordfence configurado con 2FA
- [ ] WP Mail SMTP probado con envío
- [ ] WP Optimize programado semanal
- [ ] Yoast SEO conectado a Search Console
- [ ] Polylang con 5 idiomas configurados
- [ ] Contact Form 7 funcionando
- [ ] reCAPTCHA configurado
- [ ] Robots.txt actualizado
- [ ] Sitemap generado

---

**Estado:** ✅ FASE 7 COMPLETADA
**Siguiente:** FASE 8 - DEVOPS

---
*Integration Agent - Molino Translatio Global*
