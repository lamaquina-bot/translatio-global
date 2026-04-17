# DEVOPS FIX REPORT — Translatio Global

**Fecha:** 2025-04-17  
**Investigador:** DevOps Agent (MOLINO)  
**Sitio:** https://translatio.thefuckinggoat.cloud/

---

## 1. Estado Actual del Despliegue

### ✅ Funcionando
- Homepage en 5 idiomas: `/`, `/en/`, `/pt/`, `/zh/`, `/fr/` → HTTP 200
- CSS cargando correctamente (`/_astro/index.BtTOLcli.css`)
- Favicon accesible (`/favicon.svg`)
- Sitio detrás de Cloudflare (CDN activo)

### ❌ Roto
| Problema | URLs afectadas | HTTP Status |
|----------|---------------|-------------|
| Páginas internas 404 | `/servicios`, `/proceso`, `/quienes-somos`, `/contacto` y equivalentes en 4 idiomas | 404 |
| API Chat | `/api/chat` | 404 |
| API Contact | `/api/contact` | 404 |
| Sitemap | `/sitemap.xml` | 404 |
| Headers seguridad | Todos | Ausentes |

### 🔍 Headers de Respuesta Actuales
```
server: cloudflare
content-type: text/html
```
**Ausentes:** CSP, HSTS, X-Frame-Options, X-Content-Type-Options, Referrer-Policy, Permissions-Policy

---

## 2. Análisis de Causa Raíz

### 2.1 Navegación Interna → 404

**CAUSA RAÍZ:** El componente `Header.astro` genera enlaces a rutas que NO EXISTEN como páginas:

```typescript
// src/components/Header.astro (línea 16)
const nav = {
  es: { home: '/', services: '/servicios', process: '/proceso', about: '/quienes-somos', contact: '/contacto' },
  // ... 4 idiomas más
};
```

Pero `src/pages/` solo contiene:
```
index.astro          → genera dist/index.html
en/index.astro       → genera dist/en/index.html
pt/index.astro       → genera dist/pt/index.html
zh/index.astro       → genera dist/zh/index.html
fr/index.astro       → genera dist/fr/index.html
```

**No existen páginas separadas** para servicios, proceso, quienes-somos, ni contacto. Todo el contenido está en UNA sola página por idioma. La navegación debería usar anchor links (`#services`, `#process`, `#contact`) en lugar de rutas.

**Clasificación:** BUG DE DESARROLLO (no de deployment)

### 2.2 APIs inexistentes → 404

**CAUSA RAÍZ:** El proyecto está configurado como `output: 'static'` en `astro.config.mjs`:

```javascript
export default defineConfig({
  output: 'static',  // ← No server-side rendering, no API routes
});
```

Sin embargo, dos componentes hacen `fetch()` a APIs que no existen:
- `ChatbotWidget.astro` → `fetch('/api/chat', {...})` y `fetch('/api/contact', {...})`
- `ContactForm.astro` → `fetch('/api/contact', {...})`

**No hay código de backend** en todo el repositorio. No hay carpeta `src/pages/api/`, no hay `server.js`, no hay funciones serverless.

**Clasificación:** BUG DE DISEÑO (arquitectura incompleta)

### 2.3 Headers de Seguridad Ausentes

**CAUSA RAÍZ:** El sitio está detrás de Cloudflare pero no se configuraron:
- Reglas de Cloudflare para headers de seguridad
- Headers en el origen (nginx o similar)
- Middleware de Traefik para headers

El servidor responde únicamente con headers básicos de Cloudflare.

**Clasificación:** GAP DE CONFIGURACIÓN DE INFRAESTRUCTURA

### 2.4 Sitemap Ausente

**CAUSA RAÍZ:** No se generó `sitemap.xml` como parte del build. Astro tiene un integration `@astrojs/sitemap` que no está instalada ni configurada.

**Clasificación:** GAP DE BUILD/SEO

---

## 3. Infraestructura Detectada

### Coolify
- **Proyecto:** `translatio-global` (UUID: `ifpl4qogpk6ub2cg7o3tcxsi`)
- **Environment:** `production` (UUID: `ocnq632x1h7xu5zmm8asrb8f`)
- **Aplicaciones:** NINGUNA — el proyecto está vacío en Coolify

### Cloudflare
- El dominio resuelve a través de Cloudflare
- No se puede determinar si es Cloudflare Pages o un origen externo

### Conclusión Infraestructura
El sitio NO está desplegado a través de Coolify. Probablemente está servido directamente desde Cloudflare (Pages/Workers) o desde otro origen. El proyecto en Coolify fue creado pero nunca se desplegó nada en él.

---

## 4. Acciones Necesarias

### 🔴 Crítico (Sitio roto)

| # | Acción | Tipo | Detalle |
|---|--------|------|---------|
| 1 | Cambiar enlaces de navegación a anchors | Código | `Header.astro`: `/servicios` → `#services`, `/proceso` → `#process`, `/contacto` → `#contact` |
| 2 | Agregar IDs a las secciones | Código | Las secciones en `index.astro` necesitan `id="services"`, `id="process"`, `id="contact"` |
| 3 | Hacer chatbot 100% client-side | Código | Eliminar `fetch('/api/chat')`, usar respuestas predefinidas (FAQ) con lógica JS |
| 4 | Manejar formulario de contacto | Código | Opciones: (a) mailto:, (b) Formspree/Getform, (c) WhatsApp link, (d) crear API |

### 🟡 Importante (Seguridad/SEO)

| # | Acción | Tipo | Detalle |
|---|--------|------|---------|
| 5 | Instalar `@astrojs/sitemap` | Build | `npm install @astrojs/sitemap` y agregar a config |
| 6 | Configurar headers seguridad | Infra | Agregar en Cloudflare Rules o nginx/Traefik |
| 7 | Desplegar en Coolify | Infra | Crear app nginx:alpine con volúmenes apuntando a dist/ |

### 🟢 Mejora

| # | Acción | Tipo |
|---|--------|------|
| 8 | Agregar robots.txt | SEO |
| 9 | Agregar meta tags Open Graph | SEO |
| 10 | Implementar analytics | Marketing |

---

## 5. Sitemap Generado

Se creó `/dist/sitemap.xml` con las 5 páginas disponibles (homepages por idioma).

---

## 6. Límites de Esta Investigación

- **No hay acceso a Cloudflare** — No se pueden configurar headers de seguridad directamente
- **No hay app en Coolify** — No se puede modificar la configuración del servidor
- **Los fixes de código requieren rebuild** — Cambiar Header.astro y los componentes requiere re-ejecutar el build de Astro

---

## 7. Próximos Pasos Recomendados

1. **Inmediato:** Cambiar nav links a anchors en `Header.astro` y rebuild
2. **Inmediato:** Hacer chatbot client-side (sin API) o conectar a servicio externo
3. **Corto plazo:** Configurar formulario de contacto con Formspree/Getform
4. **Corto plazo:** Desplegar en Coolify con nginx y headers de seguridad
5. **Medio plazo:** Agregar `@astrojs/sitemap` al pipeline de build
