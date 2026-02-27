# 🔒 FASE 9: SECURITY - Translatio Global

**Fecha:** 27 Feb 2026
**Agente:** Security Agent
**Estado:** ✅ COMPLETADO

---

## 🛡️ WORDFENCE CONFIGURACIÓN AVANZADA

### Reglas de Firewall Personalizadas

#### Rate Limiting
```
All crawlers: 600 req/min
Humans: 1200 req/min
Google Crawlers: Exempt
```

#### Blocked Patterns
```
# SQL Injection patterns
/administrator/
/admin.php
/phpmyadmin/
/pma/
/mysql/
/myadmin/

# Common exploits
/wp-config.php.bak
/.env
/.git/
/.svn/
/wp-content/debug.log
```

### 2FA Configuration
1. Ir a Wordfence → Login Security
2. **Enable 2FA for all administrators** (OBLIGATORIO)
3. Authentication method: TOTP (Google Authenticator, Authy)
4. Grace period: 7 días

### Whitelist IPs (si es necesario)
```php
// Agregar a functions.php o mu-plugin
define('WORDFENCE_DISABLE_BLOCKING', false);

// IPs confiables
$whitelisted_ips = [
    '192.168.1.100', // Office
    '10.0.0.50',     // VPN
];

if (in_array($_SERVER['REMOTE_ADDR'], $whitelisted_ips)) {
    define('WORDFENCE_DISABLE_BLOCKING', true);
}
```

---

## 🔐 WP-CONFIG HARDENING

### wp-config-optimizado.php
```php
<?php
/**
 * Configuración segura de WordPress
 * NO subir a Git
 */

// ==================== DATABASE ====================
define('DB_NAME', 'translatio_db');
define('DB_USER', 'translatio_user');
define('DB_PASSWORD', 'contraseña_segura_aqui');
define('DB_HOST', 'localhost');
define('DB_CHARSET', 'utf8mb4');
define('DB_COLLATE', 'utf8mb4_unicode_ci');

// ==================== SECURITY KEYS ====================
// Generar en: https://api.wordpress.org/secret-key/1.1/salt/
define('AUTH_KEY',         'poner_key_unica_aqui');
define('SECURE_AUTH_KEY',  'poner_key_unica_aqui');
define('LOGGED_IN_KEY',    'poner_key_unica_aqui');
define('NONCE_KEY',        'poner_key_unica_aqui');
define('AUTH_SALT',        'poner_key_unica_aqui');
define('SECURE_AUTH_SALT', 'poner_key_unica_aqui');
define('LOGGED_IN_SALT',   'poner_key_unica_aqui');
define('NONCE_SALT',       'poner_key_unica_aqui');

// ==================== TABLE PREFIX ====================
$table_prefix = 'wp_tr4ns1t10_'; // Prefijo único

// ==================== HARDENING ====================

// Deshabilitar editor de archivos
define('DISALLOW_FILE_EDIT', true);
define('DISALLOW_FILE_MODS', true);

// Deshabilitar XML-RPC
define('XMLRPC_REQUEST', false);

// Limitar revisiones
define('WP_POST_REVISIONS', 3);
define('EMPTY_TRASH_DAYS', 7);

// Actualizaciones automáticas
define('WP_AUTO_UPDATE_CORE', 'minor');
define('WP_PLUGIN_AUTO_UPDATE_DISABLED', false);

// Memory limits
define('WP_MEMORY_LIMIT', '256M');
define('WP_MAX_MEMORY_LIMIT', '512M');

// SSL forzado
define('FORCE_SSL_ADMIN', true);
define('FORCE_SSL_LOGIN', true);

// Deshabilitar WP Cron (usar system cron)
define('DISABLE_WP_CRON', true);

// Modo debug (desactivar en producción)
define('WP_DEBUG', false);
define('WP_DEBUG_LOG', false);
define('WP_DEBUG_DISPLAY', false);
@ini_set('display_errors', 0);

// ==================== HTTPS DETECTION ====================
if (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') {
    $_SERVER['HTTPS'] = 'on';
}

// ==================== ABSPATH ====================
if (!defined('ABSPATH')) {
    define('ABSPATH', __DIR__ . '/');
}

require_once ABSPATH . 'wp-settings.php';
```

---

## 📁 .HTACCESS SEGURO

### .htaccess-seguro
```apache
# ====================
# TRANSLATIO GLOBAL - Secure .htaccess
# ====================

# Protect wp-config.php
<Files wp-config.php>
    Order Allow,Deny
    Deny from all
</Files>

# Protect .htaccess
<Files .htaccess>
    Order Allow,Deny
    Deny from all
</Files>

# Protect htaccess backup files
<Files ~ "^\.ht">
    Order Allow,Deny
    Deny from all
</Files>

# Block XML-RPC
<Files xmlrpc.php>
    Order Deny,Allow
    Deny from all
</Files>

# Disable directory browsing
Options All -Indexes

# Block access to sensitive directories
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /
    
    # Block access to .git, .svn, .env
    RewriteRule ^\.git - [F,L]
    RewriteRule ^\.svn - [F,L]
    RewriteRule ^\.env - [F,L]
    
    # Block access to backup files
    RewriteRule \.(bak|config|sql|fla|psd|ini|log|sh|inc|swp|dist)$ - [F,L]
    
    # Block PHP files in uploads
    RewriteRule ^wp-content/uploads/.*\.php$ - [F,L]
    
    # Block PHP files in plugins/themes
    RewriteRule ^wp-content/plugins/.*\.php$ - [F,L]
    RewriteRule ^wp-content/themes/.*\.php$ - [F,L]
</IfModule>

# Security Headers
<IfModule mod_headers.c>
    # Prevent clickjacking
    Header always set X-Frame-Options "SAMEORIGIN"
    
    # Prevent MIME-type sniffing
    Header always set X-Content-Type-Options "nosniff"
    
    # Enable XSS protection
    Header always set X-XSS-Protection "1; mode=block"
    
    # Referrer policy
    Header always set Referrer-Policy "strict-origin-when-cross-origin"
    
    # Content Security Policy (ajustar según necesidades)
    # Header always set Content-Security-Policy "default-src 'self';"
    
    # Remove X-Powered-By header
    Header unset X-Powered-By
</IfModule>

# Disable server signature
ServerSignature Off

# Limit file upload size (10MB)
LimitRequestBody 10485760

# Protect against script injections
<IfModule mod_rewrite.c>
    RewriteCond %{QUERY_STRING} (\<|%3C).*script.*(\>|%3E) [NC,OR]
    RewriteCond %{QUERY_STRING} GLOBALS(=|\[|\%[0-9A-Z]{0,2}) [OR]
    RewriteCond %{QUERY_STRING} _REQUEST(=|\[|\%[0-9A-Z]{0,2})
    RewriteRule .* index.php [F,L]
</IfModule>

# 6G Firewall (Lite version)
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteBase /
    
    # Block empty user agents
    RewriteCond %{HTTP_USER_AGENT} ^$
    RewriteRule .* - [F,L]
    
    # Block suspicious request methods
    RewriteCond %{REQUEST_METHOD} !^(GET|HEAD|POST|OPTIONS)
    RewriteRule .* - [F,L]
    
    # Block suspicious strings
    RewriteCond %{QUERY_STRING} ../ [NC,OR]
    RewriteCond %{QUERY_STRING} boot\.ini [NC,OR]
    RewriteCond %{QUERY_STRING} etc/passwd [NC,OR]
    RewriteCond %{QUERY_STRING} php:// [NC,OR]
    RewriteCond %{QUERY_STRING} proc/self/environ [NC]
    RewriteRule .* - [F,L]
</IfModule>

# WP Super Cache rules (agregar aquí)
```

---

## 🚫 NGINX SECURITY CONFIG

### nginx-security.conf
```nginx
# ====================
# NGINX SECURITY CONFIG
# ====================

# Hide Nginx version
server_tokens off;

# Security headers
add_header X-Frame-Options "SAMEORIGIN" always;
add_header X-Content-Type-Options "nosniff" always;
add_header X-XSS-Protection "1; mode=block" always;
add_header Referrer-Policy "strict-origin-when-cross-origin" always;
add_header Permissions-Policy "accelerometer=(), camera=(), geolocation=(), gyroscope=(), magnetometer=(), microphone=(), payment=(), usb=()" always;

# Block common attack patterns
location ~ /\. {
    deny all;
    access_log off;
    log_not_found off;
}

location ~ /wp-config.php {
    deny all;
    access_log off;
    log_not_found off;
}

location ~ /xmlrpc.php {
    deny all;
    access_log off;
    log_not_found off;
}

# Block PHP files in uploads
location ~* /wp-content/uploads/.*\.php$ {
    deny all;
}

# Block access to sensitive files
location ~* \.(bak|config|sql|fla|psd|ini|log|sh|inc|swp|dist|git|svn|env)$ {
    deny all;
}

# Limit request body size
client_max_body_size 10M;

# Rate limiting for login
location = /wp-login.php {
    limit_req zone=login burst=5 nodelay;
    include fastcgi_params;
    fastcgi_pass php;
}

# Rate limiting for xmlrpc
location = /xmlrpc.php {
    deny all;
}

# Block specific user agents
if ($http_user_agent ~* (wget|curl|python-requests|libwww-perl|MJ12bot|AhrefsBot)) {
    return 403;
}

# Block countries si es necesario (requiere GeoIP)
# if ($geoip_country_code !~ ^(CO|US|ES|MX|AR|PE|CL)) {
#     return 403;
# }
```

---

## 👤 HARDENING DE USUARIOS

### Crear Admin Secreto
```bash
# Crear usuario admin con nombre no obvio
wp user create admin-secret secreto@translatioglobal.com --role=administrator --user_pass="contraseña_segura"

# Eliminar usuario admin default (id=1)
wp user delete 1 --reassign=<new_admin_id>
```

### Deshabilitar display_name "admin"
```php
// functions.php o mu-plugin
add_filter('pre_user_login', function($user_login) {
    if (strtolower($user_login) === 'admin') {
        wp_die('El nombre de usuario "admin" no está permitido.');
    }
    return $user_login;
});
```

### Bloquear creación de usuarios admin
```php
add_filter('user_has_cap', function($allcaps, $caps, $args, $user) {
    if (!is_super_admin($user->ID) && isset($args[0]) && $args[0] === 'create_users') {
        $allcaps['create_users'] = false;
    }
    return $allcaps;
}, 10, 4);
```

---

## 🔍 SECURITY AUDIT CHECKLIST

### Pre-Launch Audit

#### File System
- [ ] Permisos correctos: 755 directorios, 644 archivos
- [ ] wp-config.php con permisos 600
- [ ] .htaccess y wp-config.php no accesibles desde web
- [ ] Sin archivos backup accesibles (.bak, .sql, .zip)
- [ ] Sin directorios .git, .svn, .env accesibles

#### Database
- [ ] Prefijo de tabla no default (wp_)
- [ ] Usuario DB con privilegios mínimos
- [ ] Sin datos sensibles en wp_options (API keys, etc.)

#### Users
- [ ] No existe usuario "admin"
- [ ] Todos los admins con 2FA activado
- [ ] Contraseñas fuertes (min 12 caracteres)
- [ ] Usuarios innecesarios eliminados

#### Plugins & Themes
- [ ] Todos actualizados
- [ ] Plugins innecesarios eliminados
- [ ] Temas default eliminados
- [ ] Sin plugins de desarrollo activos

#### Configuration
- [ ] DISALLOW_FILE_EDIT = true
- [ ] DISALLOW_FILE_MODS = true
- [ ] FORCE_SSL_ADMIN = true
- [ ] WP_DEBUG = false
- [ ] XML-RPC deshabilitado

#### Server
- [ ] SSL activo y forzado
- [ ] Security headers configurados
- [ ] Rate limiting activo
- [ ] Firewall activo (Wordfence)
- [ ] Logs de acceso y errores configurados

#### Monitoring
- [ ] Alertas de Wordfence configuradas
- [ ] Monitoreo de uptime activo
- [ | Backups automáticos funcionando
- [ ] Rotación de logs configurada

---

## 🔐 SECURITY HEADERS FINALES

### Headers recomendados para producción

```nginx
# Content Security Policy
add_header Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-inline' 'unsafe-eval' https://fonts.googleapis.com https://www.googletagmanager.com; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data: https:; frame-src 'self' https://www.google.com; connect-src 'self' https://www.google-analytics.com;" always;

# Strict Transport Security
add_header Strict-Transport-Security "max-age=31536000; includeSubDomains; preload" always;

# X-Frame-Options
add_header X-Frame-Options "SAMEORIGIN" always;

# X-Content-Type-Options
add_header X-Content-Type-Options "nosniff" always;

# X-XSS-Protection
add_header X-XSS-Protection "1; mode=block" always;

# Referrer-Policy
add_header Referrer-Policy "strict-origin-when-cross-origin" always;

# Permissions-Policy
add_header Permissions-Policy "accelerometer=(), camera=(), geolocation=(), gyroscope=(), magnetometer=(), microphone=(), payment=(), usb=()" always;
```

---

## ✅ CHECKLIST SECURITY

- [ ] Wordfence configurado con reglas personalizadas
- [ ] 2FA activado para todos los admins
- [ ] wp-config.php hardening aplicado
- [ ] .htaccess seguro configurado
- [ ] Nginx security headers activos
- [ ] Usuario "admin" eliminado
- [ ] Prefijo de DB cambiado
- [ ] SSL forzado en admin
- [ ] XML-RPC deshabilitado
- [ ] File editor deshabilitado
- [ ] Security audit completado
- [ ] Sin vulnerabilidades detectadas

---

**Estado:** ✅ FASE 9 COMPLETADA
**Siguiente:** FASE 10 - SUPER TESTING

---
*Security Agent - Molino Translatio Global*
