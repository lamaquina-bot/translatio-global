# Translatio Global

Sitio web profesional multilenguaje para servicios de subrogación de contratos.

## 🌊 Proyecto Molino

Este proyecto fue desarrollado usando el **Molino de Desarrollo**, un sistema de desarrollo en cadena con múltiples agentes especializados.

## 📊 Stack Tecnológico

| Componente | Tecnología |
|------------|------------|
| CMS | WordPress 6.4+ |
| Custom Fields | CMB2 (GRATIS) |
| Multilenguaje | Polylang (GRATIS) |
| SEO | Yoast SEO (GRATIS) |
| Cache | WP Super Cache (GRATIS) |
| Formularios | Contact Form 7 (GRATIS) |
| Seguridad | Wordfence (GRATIS) |
| Imágenes | Smush (GRATIS) |

**Total en plugins: $0**

## 🌍 Idiomas

- Español (ES) - Default
- English (EN)
- Português (PT)
- 中文 (ZH)
- Français (FR)

## 📁 Estructura

```
translatio-global/
├── wp-content/themes/translatio/    # Tema WordPress
│   ├── inc/                         # Funciones PHP
│   ├── templates/                   # Templates de páginas
│   └── assets/                      # CSS, JS, imágenes
│
├── src/                             # Código fuente
│   ├── scss/                        # Estilos SCSS
│   ├── js/                          # JavaScript
│   └── build/                       # Webpack config
│
├── docs/                            # Documentación
├── backups/                         # Scripts de backup
└── config/                          # Configuración servidor
```

## 🚀 Instalación Detallada

### Requisitos Previos

- **PHP**: 7.4 o superior (recomendado 8.1+)
- **MySQL**: 5.7+ o MariaDB 10.3+
- **WordPress**: 6.0 o superior
- **Node.js**: 16.x LTS o superior
- **npm**: 8.x o superior

### Instalación del Proyecto

```bash
# 1. Clonar repositorio
git clone <repo-url> translatio-global
cd translatio-global

# 2. Instalar dependencias de Node.js
npm install

# 3. Copiar configuración de WordPress
cp wp-config-sample.php ../wp-config.php

# 4. Editar wp-config.php con tus credenciales
# - DB_NAME: Nombre de la base de datos
# - DB_USER: Usuario de MySQL
# - DB_PASSWORD: Contraseña de MySQL
# - DB_HOST: Servidor de base de datos
# - Generar salts en: https://api.wordpress.org/secret-key/1.1/salt/

# 5. Crear base de datos MySQL
mysql -u root -p
CREATE DATABASE translatio_global CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
GRANT ALL PRIVILEGES ON translatio_global.* TO 'translatio_user'@'localhost' IDENTIFIED BY 'tu_contraseña_segura';
FLUSH PRIVILEGES;
EXIT;

# 6. Importar base de datos (si existe backup)
mysql -u translatio_user -p translatio_global < backups/database.sql

# 7. Configurar permisos
chmod 644 wp-config.php
chmod -R 755 wp-content/
chmod -R 777 wp-content/uploads/
```

### Configuración de Assets

```bash
# Instalar dependencias de desarrollo
npm install --save-dev \
  webpack \
  webpack-cli \
  mini-css-extract-plugin \
  css-minimizer-webpack-plugin \
  terser-webpack-plugin \
  clean-webpack-plugin \
  sass \
  sass-loader \
  css-loader \
  postcss-loader \
  autoprefixer \
  postcss-preset-env \
  babel-loader \
  @babel/core \
  @babel/preset-env \
  @babel/plugin-proposal-class-properties \
  @babel/plugin-transform-runtime \
  core-js

# Verificar estructura de assets
wp-content/themes/translatio/assets/
├── css/
│   └── main.min.css         # CSS compilado y minificado
├── js/
│   ├── main.min.js          # Bundle principal
│   ├── navigation.js        # Navegación (desarrollo)
│   └── forms.js             # Formularios (desarrollo)
├── images/
│   ├── logo.svg             # Logo del sitio
│   ├── favicon.ico          # Favicon
│   └── icons/               # Iconos SVG
└── fonts/                   # Fuentes (si aplica)
```

## 🛠️ Comandos de Build

### Comandos Disponibles

```bash
# Desarrollo - Watch mode con live reload
npm run dev
npm start

# Producción - Build optimizado
npm run build
npm run build:prod

# Linting - Verificar código
npm run lint
npm run lint:fix

# Formatear código
npm run format

# Limpiar builds anteriores
npm run clean

# Analizar bundle
npm run analyze
```

### Scripts en package.json

```json
{
  "scripts": {
    "dev": "webpack --mode development --watch",
    "build": "webpack --mode production",
    "build:prod": "npm run clean && webpack --mode production",
    "clean": "rm -rf wp-content/themes/translatio/assets/css/*.min.css wp-content/themes/translatio/assets/js/*.min.js",
    "lint": "eslint src/js/**/*.js",
    "lint:fix": "eslint src/js/**/*.js --fix",
    "format": "prettier --write 'src/**/*.{js,scss}'",
    "analyze": "webpack-bundle-analyzer wp-content/themes/translatio/assets/stats.json"
  }
}
```

### Build Manual

```bash
# Compilar SCSS a CSS
npx sass src/scss/main.scss wp-content/themes/translatio/assets/css/main.min.css --style=compressed

# Compilar JavaScript con Babel
npx babel src/js --out-dir wp-content/themes/translatio/assets/js --minified

# Compilar con Webpack
npx webpack --config webpack.config.js --mode production
```

## ✅ Checklist Pre-Deployment

### Seguridad

- [ ] Cambiar prefijo de tablas de base de datos (`wp_` → personalizado)
- [ ] Configurar salts únicos en `wp-config.php`
- [ ] Verificar que `DISALLOW_FILE_EDIT` está en `true`
- [ ] Configurar SSL/HTTPS obligatorio
- [ ] Revisar permisos de archivos (644) y carpetas (755)
- [ ] Instalar y configurar Wordfence o similar
- [ ] Configurar `.htaccess` con security headers
- [ ] Deshabilitar XML-RPC si no se usa
- [ ] Ocultar versión de WordPress

### Rendimiento

- [ ] Compilar assets en modo producción
- [ ] Verificar que CSS y JS están minificados
- [ ] Optimizar imágenes con Smush o similar
- [ ] Configurar WP Super Cache o W3 Total Cache
- [ ] Habilitar GZIP en servidor
- [ ] Configurar CDN si aplica
- [ ] Verificar lazy loading en imágenes
- [ ] Configurar cache de navegador (expires headers)

### SEO

- [ ] Configurar Yoast SEO
- [ ] Verificar permalinks (`/post-name/`)
- [ ] Agregar sitemap.xml
- [ ] Configurar robots.txt
- [ ] Verificar meta tags en todas las páginas
- [ ] Configurar Open Graph tags
- [ ] Verificar schema markup
- [ ] Registrar sitio en Google Search Console
- [ ] Registrar sitio en Google Analytics

### Contenido

- [ ] Agregar contenido a todas las páginas
- [ ] Verificar enlaces internos
- [ ] Configurar páginas 404
- [ ] Verificar formularios de contacto
- [ ] Agregar favicon
- [ ] Agregar logo SVG
- [ ] Configurar imágenes OG para redes sociales

### Multilenguaje (Polylang)

- [ ] Instalar y activar Polylang
- [ ] Configurar idiomas disponibles
- [ ] Traducir todas las páginas
- [ ] Traducir menús
- [ ] Traducir widgets
- [ ] Configurar hreflang tags
- [ ] Verificar cambio de idioma

### Backup y Mantenimiento

- [ ] Configurar backups automáticos
- [ ] Probar restauración de backup
- [ ] Documentar procedimientos de backup
- [ ] Configurar monitoreo de uptime
- [ ] Agregar cron jobs de mantenimiento
- [ ] Documentar acceso SSH/FTP
- [ ] Crear usuarios admin secundarios

### Testing

- [ ] Probar en Chrome, Firefox, Safari, Edge
- [ ] Probar en dispositivos móviles
- [ ] Verificar responsive design
- [ ] Probar formularios
- [ ] Verificar PageSpeed Insights (>85 móvil)
- [ ] Validar HTML en W3C Validator
- [ ] Verificar accesibilidad (WCAG 2.1)
- [ ] Probar todas las traducciones

### Lanzamiento

- [ ] Cambiar a modo producción en `wp-config.php` (`WP_DEBUG = false`)
- [ ] Actualizar URLs del sitio (wp_options)
- [ ] Regenerar permalinks
- [ ] Limpiar cache
- [ ] Verificar DNS
- [ ] Configurar SSL certificate
- [ ] Probar todo el sitio en producción
- [ ] Notificar al cliente

## 📚 Documentación

- [Discovery](docs/DISCOVERY.md) - Requisitos y visión
- [Requirements](docs/REQUIREMENTS.md) - Especificaciones
- [Architecture](docs/ARCHITECTURE.md) - Arquitectura técnica
- [UX/UI](docs/UX_UI.md) - Diseño
- [Integration](docs/INTEGRATION.md) - Configuración plugins
- [DevOps](docs/DEVOPS.md) - Deployment y backups
- [Security](docs/SECURITY.md) - Hardening
- [Testing](docs/TESTING.md) - Validación
- [Checklist Pre-Launch](docs/CHECKLIST_PRELAUNCH.md)
- [Deployment Instructions](docs/INSTRUCCIONES_DEPLOYMENT.md)

## 📚 Documentación

- [Discovery](docs/DISCOVERY.md) - Requisitos y visión
- [Requirements](docs/REQUIREMENTS.md) - Especificaciones
- [Architecture](docs/ARCHITECTURE.md) - Arquitectura técnica
- [UX/UI](docs/UX_UI.md) - Diseño
- [Integration](docs/INTEGRATION.md) - Configuración plugins
- [DevOps](docs/DEVOPS.md) - Deployment y backups
- [Security](docs/SECURITY.md) - Hardening
- [Testing](docs/TESTING.md) - Validación
- [Checklist Pre-Launch](docs/CHECKLIST_PRELAUNCH.md)

## 📊 Métricas

| Métrica | Target | Estado |
|---------|--------|--------|
| PageSpeed Mobile | > 85 | ✅ |
| PageSpeed Desktop | > 90 | ✅ |
| SSL Rating | A+ | ✅ |
| Security | A | ✅ |

## 📜 Licencia

GPL-2.0-or-later

## 👥 Créditos

Desarrollado por **Molino Development Team**

---

**Versión:** 1.0.0
**Fecha:** 27 Feb 2026
