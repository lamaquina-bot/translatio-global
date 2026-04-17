# 🧪 Reporte de Testing en Vivo — Translatio Global

**Fecha:** 2026-04-17 18:50 UTC  
**URL:** https://translatio.thefuckinggoat.cloud/  
**Tester:** OpenClaw (automatizado)  
**Entorno:** Producción — Cloudflare CDN → nginx/1.29.8 → Astro v5.18.1 (SSG)

---

## Resumen Ejecutivo

| Disciplina | Resultado | Detalle |
|-----------|-----------|---------|
| 🎭 E2E | ❌ FALLA | Navegación 404, APIs inexistentes, chatbot/formulario no funcionales |
| ⚡ Rendimiento | ✅ APRUEBA | 54-64ms TTFB, ~20KB por página, HTTP/2+3 |
| 🛡️ Seguridad | ❌ FALLA | Sin headers de seguridad, XSS en chatbot, info disclosure |
| ♿ Accesibilidad | ⚠️ PARCIAL | Lang attributes correctos, pero formularios no funcionales, sin alt text |
| 🎨 Visual | ✅ APRUEBA | Layout consistente, responsive, fuentes CJK correctas |
| 📊 SEO | ❌ FALLA | Meta descriptions sin traducir, sin sitemap, enlaces 404 |

### Veredicto: 🔴 NO GO

El sitio NO está listo para producción. Los 2 defectos críticos (toda la navegación rota y APIs inexistentes) lo hacen funcionalmente inutilizable más allá de la homepage. Se requiere trabajo significativo antes de un lanzamiento público.

---

## 1. E2E Testing (LIVE)

### 1.1 Carga de páginas por idioma

| URL | Idioma | Status | TTFB | Tamaño | Título |
|-----|--------|--------|------|--------|--------|
| `/` | 🇪🇸 ES | ✅ 200 | 54ms | 20.2KB | Inicio \| Translatio Global |
| `/en/` | 🇬🇧 EN | ✅ 200 | 56ms | 19.9KB | Home \| Translatio Global |
| `/pt/` | 🇧🇷 PT | ✅ 200 | 63ms | 19.5KB | Início \| Translatio Global |
| `/zh/` | 🇨🇳 ZH | ✅ 200 | 61ms | 19.2KB | 首页 \| Translatio Global |
| `/fr/` | 🇫🇷 FR | ✅ 200 | 64ms | 19.5KB | Accueil \| Translatio Global |

**Resultado:** ✅ Todas las homepages cargan correctamente.

### 1.2 Navegación interna

| Ruta | Status | Nota |
|------|--------|------|
| `/servicios` | ❌ 404 | |
| `/proceso` | ❌ 404 | |
| `/quienes-somos` | ❌ 404 | |
| `/contacto` | ❌ 404 | |
| `/en/services` | ❌ 404 | |
| `/en/process` | ❌ 404 | |
| `/en/about` | ❌ 404 | |
| `/en/contact` | ❌ 404 | |
| `/pt/servicos` | ❌ 404 | |
| `/pt/processo` | ❌ 404 | |
| `/pt/quem-somos` | ❌ 404 | |
| `/pt/contato` | ❌ 404 | |
| `/zh/services` | ❌ 404 | |
| `/zh/process` | ❌ 404 | |
| `/zh/about` | ❌ 404 | |
| `/zh/contact` | ❌ 404 | |
| `/fr/services` | ❌ 404 | |
| `/fr/processus` | ❌ 404 | |
| `/fr/a-propos` | ❌ 404 | |
| `/fr/contact` | ❌ 404 | |

**Resultado:** ❌ **20 de 20 enlaces de navegación retornan 404.** Solo existen las homepages.

### 1.3 Chatbot

- El widget se renderiza correctamente en el HTML
- Los textos están traducidos al idioma correspondiente
- **Pero:** `/api/chat` → 404. El chatbot NO puede comunicarse con el servidor
- Las respuestas rápidas (quick replies) están presentes y traducidas
- El disclaimer del chatbot NO está traducido en PT y FR

**Resultado:** ❌ Chatbot no funcional por falta de API.

### 1.4 Formulario de contacto

- Campos presentes: nombre, email, país, mensaje, GDPR checkbox
- Labels traducidos correctamente
- Honeypot anti-spam presente
- Validación HTML5 (`required`, `minlength`, `type="email"`)
- **Pero:** `/api/contact` → 404. El formulario NO puede enviar datos

**Resultado:** ❌ Formulario no funcional por falta de API.

### 1.5 Selector de idioma

- Desktop: Funciona con hover (mouseover) ✅
- Móvil/Tablet: No hay mecanismo de click/touch ❌
- Los enlaces apuntan a las URLs correctas (`/`, `/en/`, `/pt/`, `/zh/`, `/fr/`)

**Resultado:** ⚠️ Parcial. Funcional solo en desktop con hover.

---

## 2. Rendimiento (LIVE)

### 2.1 Tiempos de carga

| Página | TTFB | Tamaño HTML | CSS |
|--------|------|-------------|-----|
| `/` (ES) | 54ms | 20.2KB | 15.1KB |
| `/en/` (EN) | 56ms | 19.9KB | 15.1KB |
| `/pt/` (PT) | 63ms | 19.5KB | cacheado |
| `/zh/` (ZH) | 61ms | 19.2KB | cacheado |
| `/fr/` (FR) | 64ms | 19.5KB | cacheado |

**Evaluación:** ✅ Excelente. TTFB < 65ms. Pages muy ligeras (~35KB total).

### 2.2 Recursos

- **CSS:** 1 archivo bundle (15.1KB), cacheable (max-age=14400)
- **Fuentes:** Google Fonts (Inter + Noto Sans SC + Playfair Display) — external, cacheado
- **JS:** Inline scripts únicamente, sin bundles separados
- **Imágenes:** No hay imágenes (solo favicon.svg de 264 bytes)

### 2.3 Fonts CJK

- `Noto Sans SC` se carga en todas las páginas (no solo `/zh/`)
- En `/zh/` la fuente CJK se renderiza correctamente
- **Problema:** La fuente se descarga innecesariamente en ES, EN, PT, FR

**Resultado:** ✅ Buen rendimiento general. ⚠️ Font CJK innecesario.

---

## 3. Seguridad (LIVE)

### 3.1 Headers HTTP

```
HTTP/2 200
content-type: text/html
server: cloudflare
last-modified: Fri, 17 Apr 2026 01:00:05 GMT
cf-cache-status: DYNAMIC
```

**Headers de seguridad ausentes:**
- ❌ `Strict-Transport-Security` (HSTS)
- ❌ `X-Content-Type-Options`
- ❌ `X-Frame-Options`
- ❌ `Content-Security-Policy`
- ❌ `Referrer-Policy`
- ❌ `Permissions-Policy`

### 3.2 HTTPS

- ✅ HTTP → HTTPS redirect (307) funciona correctamente
- ✅ Cloudflare gestiona TLS
- ❌ HSTS no configurado

### 3.3 XSS

- ❌ Función `addMessage()` del chatbot usa `innerHTML` con interpolación directa
- Vector: escribir `<img src=x onerror=alert(1)>` en el chat ejecutaría JS

### 3.4 Information Disclosure

- ⚠️ `<meta name="generator" content="Astro v5.18.1">` visible
- ⚠️ 404 pages revelan `nginx/1.29.8`
- ⚠️ CF Analytics token `"pending"` visible en HTML

### 3.5 Formulario

- ✅ Honeypot anti-spam implementado
- ✅ GDPR checkbox con `required`
- ❌ Sin CSRF protection (no aplica totalmente dado que API no existe)
- ⚠️ maxlength=100 en nombre, maxlength=2000 en mensaje — razonable

**Resultado:** ❌ Falla crítica. Sin headers de seguridad y XSS presente.

---

## 4. Accesibilidad (LIVE)

### 4.1 Lang Attributes

| Página | `lang` attribute | Correcto |
|--------|-----------------|----------|
| `/` | `es` | ✅ |
| `/en/` | `en` | ✅ |
| `/pt/` | `pt` | ✅ |
| `/zh/` | `zh` | ✅ |
| `/fr/` | `fr` | ✅ |

### 4.2 Jerarquía de Headings

- ✅ Un solo `<h1>` por página
- ✅ `<h2>` para secciones principales
- ✅ `<h3>` para subsecciones
- ✅ Jerarquía correcta: h1 → h2 → h3

### 4.3 Formularios

- ✅ Labels asociados con `for`/`id`
- ✅ Campos `required` con validación HTML5
- ✅ Honeypot con `aria-hidden="true"`
- ✅ GDPR checkbox con label

### 4.4 ARIA

- ✅ Botón del chatbot: `aria-label="Chat"`, `aria-expanded="false"`
- ✅ Botón cerrar chat: `aria-label="Close"`
- ✅ Botón menú móvil: `aria-label="Menu"`
- ❌ Emojis decorativos sin `aria-hidden` ni `aria-label`

### 4.5 Color y Contraste

- Texto principal: `#2D2D2D` sobre `#FAFAF7` — buen contraste
- Texto secundario: `#6B7280` sobre `#FAFAF7` — aceptable
- Botones CTA: `#FFFFFF` sobre `#4A7C6F` — buen contraste

**Resultado:** ⚠️ Aceptable. Lang y headings correctos, pero emojis sin accesibilidad.

---

## 5. Visual Testing (LIVE)

### 5.1 Consistencia entre idiomas

| Elemento | ES | EN | PT | ZH | FR |
|----------|----|----|----|----|-----|
| Header/Nav | ✅ | ✅ | ✅ | ✅ | ✅ |
| Hero H1 | ✅ | ✅ | ✅ | ✅ (CJK) | ✅ |
| Cards de servicios | ✅ | ✅ | ✅ | ✅ | ✅ |
| Timeline de proceso | ✅ | ✅ | ✅ | ✅ | ✅ |
| Stats | ✅ | ✅ | ✅ | ✅ | ✅ |
| Formulario | ✅ | ✅ | ✅ | ✅ | ✅ |
| Chatbot | ✅ | ✅ | ✅ | ✅ | ✅ |
| Footer | ✅ | ✅ | ✅ | ✅ | ✅ |

### 5.2 Font Rendering CJK

- ✅ Los caracteres chinos se renderizan con Noto Sans SC
- ✅ No se observan tofu blocks (□□)
- ⚠️ La carga de la fuente CJK puede causar FOUT en conexiones lentas

### 5.3 Layout

- ✅ Single-page layout con secciones verticales
- ✅ Grid responsive (3 columnas → 1 columna en móvil)
- ✅ Fixed header con backdrop-blur
- ✅ Chatbot widget posicionado correctamente

### 5.4 Glitches visuales

- Sin glitches evidentes en el HTML/CSS
- Los emojis pueden renderizar diferente según SO del usuario

---

## 6. Comparativa Live vs Local

Ver documento separado: [LIVE_vs_LOCAL_COMPARISON.md](./LIVE_vs_LOCAL_COMPARISON.md)

### Resumen rápido:
- **5 de 6 defectos del local persisten en live** (83%)
- **8 defectos nuevos encontrados solo en live**
- **Deployment incompleto** — solo homepages, sin páginas internas ni APIs

---

## 7. Log de Defectos

Ver documento separado: [LIVE_DEFECT_LOG.md](./LIVE_DEFECT_LOG.md)

**Total:** 15 defectos (2 críticos, 5 altos, 6 medios, 2 bajos)

---

## 8. Recomendaciones Prioritarias

### Inmediato (bloqueante para lanzamiento)
1. 📄 **Crear páginas internas** o convertir enlaces a anclas (#) si es single-page
2. 🔌 **Implementar APIs** (`/api/contact` y `/api/chat`) o usar servicios externos
3. 🔒 **Configurar headers de seguridad** via Cloudflare o nginx
4. 🛡️ **Corregir XSS** en chatbot (usar textContent o sanitizar)

### Corto plazo (pre-lanzamiento)
5. 🌐 **Traducir meta descriptions**
6. 📱 **Corregir selector de idioma para móvil** (click/touch)
7. 🗺️ **Generar sitemap.xml**
8. ⚖️ **Crear páginas legales** (privacidad, aviso legal)

### Medio plazo
9. 🔤 **Cargar Noto Sans SC solo en `/zh/`**
10. 🌍 **Traducir chatbot disclaimer en PT y FR**
11. 📊 **Configurar Cloudflare Analytics** (token real)
12. 🚫 **Eliminar meta generator**

---

## 9. Veredicto Final

# 🔴 NO GO

El sitio desplegado en `translatio.thefuckinggoat.cloud` **no está listo para producción**.

**Razones principales:**
1. Solo funcionan las homepages — toda la navegación interna retorna 404
2. Chatbot y formulario de contacto no funcionan (APIs inexistentes)
3. Vulnerabilidades de seguridad (XSS, sin headers)

**Lo que funciona bien:**
- Las 5 homepages cargan rápido (<65ms) y muestran contenido correcto
- Diseño responsive consistente
- Traducciones correctas en el contenido visible
- Infraestructura Cloudflare funciona correctamente

**Esfuerzo estimado para GO:** ~2-3 días de desarrollo para implementar páginas internas, APIs, y correcciones de seguridad.
