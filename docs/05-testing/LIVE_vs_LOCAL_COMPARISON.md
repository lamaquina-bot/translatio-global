# Comparativa LIVE vs LOCAL — Translatio Global

**Fecha:** 2026-04-17  
**LIVE:** https://translatio.thefuckinggoat.cloud/  
**LOCAL:** Build de referencia auditado previamente

---

## Defectos del Build Local — ¿Presentes en LIVE?

| # | Defecto Local | Presente en LIVE | Notas |
|---|--------------|-----------------|-------|
| 1 | XSS en chatbot `addMessage()` usando innerHTML | ✅ **SÍ** | Código idéntico en producción. `div.innerHTML = ...${text}...` sin sanitización. |
| 2 | GDPR consent hardcoded as true | ❌ **NO** | En LIVE el checkbox tiene `required` y se envía `gdpr_consent: checked`. No está hardcodeado. |
| 3 | Language selector broken en móvil (hover-only) | ✅ **SÍ** | `group-hover:block` sin toggle por click/touch. Idéntico al local. |
| 4 | Meta descriptions not translated | ✅ **SÍ** | Todas las páginas tienen la misma meta description en español. |
| 5 | Noto Sans SC loaded unnecessarily on all pages | ✅ **SÍ** | Font import idéntico en las 5 páginas. |
| 6 | Privacy policy links go to `#` | ✅ **SÍ** | `<a href="#">Política de Privacidad</a>` en todos los idiomas. |

### Resumen
- **5 de 6 defectos del local están presentes en LIVE** (83%)
- **1 defecto corregido**: GDPR consent ya no está hardcodeado

---

## Nuevos Defectos Encontrados Solo en LIVE

| # | Defecto | Severidad | Descripción |
|---|---------|-----------|-------------|
| N1 | Todas las páginas internas 404 | 🔴 CRÍTICO | 20+ enlaces de navegación retornan 404 |
| N2 | API endpoints no existen (404) | 🔴 CRÍTICO | `/api/chat` y `/api/contact` retornan 404. Chatbot y formulario inútiles |
| N3 | Sin headers de seguridad | 🟠 ALTO | No CSP, no X-Frame-Options, no HSTS, etc. |
| N4 | No existe sitemap.xml | 🟠 ALTO | Retorna 404 |
| N5 | CF Analytics token "pending" | 🟡 MEDIO | Analytics no funciona |
| N6 | Generator tag expone Astro version | 🟡 MEDIO | `Astro v5.18.1` visible |
| N7 | Chatbot disclaimer sin traducir (PT, FR) | 🟡 MEDIO | Texto en inglés en páginas PT y FR |
| N8 | Footer "Colombia 🇨🇴" sin traducir | 🟡 MEDIO | Aparece igual en todos los idiomas |

---

## Evaluación de Calidad del Deployment

### Infraestructura ✅
- **Cloudflare CDN:** Funcional, con HTTP/2 y HTTP/3 (alt-svc h3)
- **HTTPS:** Forzado correctamente (HTTP → HTTPS redirect 307)
- **Tiempo de respuesta:** 54-64ms — excelente
- **Tamaño de página:** ~19-20KB — ligero
- **robots.txt:** Configurado por Cloudflare con protección contra AI crawlers

### Funcionalidad ❌
- **Navegación:** Completamente rota (todos los enlaces internos 404)
- **Chatbot:** No funcional (API no existe)
- **Formulario:** No funcional (API no existe)
- **Analytics:** No configurado (token pending)

### Contenido ✅/⚠️
- **Traducción de homepage:** Correcta en los 5 idiomas
- **SEO:** Meta description sin traducir, sin sitemap
- **Legal:** Sin políticas de privacidad

### Seguridad ⚠️
- **HTTPS:** ✅ Forzado
- **Headers:** ❌ Ninguno configurado
- **XSS:** ❌ Presente en chatbot
- **Info disclosure:** ⚠️ Versión Astro expuesta, server header nginx visible

---

## Veredicto

El deployment es **igual o peor** que el build local. Los 5 defectos principales del local persisten en producción, y se suman 2 defectos críticos nuevos (páginas 404 y APIs inexistentes) que hacen que el sitio sea esencialmente una sola página con chatbot y formulario no funcionales.

**El sitio parece ser un deploy incompleto** — solo se desplegaron las homepages sin las páginas secundarias ni el backend API.
