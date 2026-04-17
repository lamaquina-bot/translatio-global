# 🚨 REQUERIMIENTO DE CORRECCIONES URGENTES — Translatio Global

**ID:** TG-FIX-2026-001
**Fecha:** 17 Abril 2026
**Prioridad:** 🔴 CRÍTICA
**Solicitado por:** Quality Strategy Lead — MOLINO Testing Squad
**Afecta:** https://translatio.thefuckinggoat.cloud/
**Veredicto actual:** 🔴 NO GO — No apto para producción

---

## 📋 RESUMEN EJECUTIVO

El sitio Translatio Global fue auditado por el Testing Squad completo (8 disciplinas) tanto en build local como en producción. Se identificaron **67 hallazgos** (2 críticos, 19 mayores, 17 menores, 19 mejoras + 15 defectos en vivo). El sitio **no puede salir a producción** en su estado actual.

Este documento detalla TODAS las correcciones requeridas, con evidencia, prioridad y criterios de aceptación.

---

## 🔴 DEFECTOS CRÍTICOS (Bloquean release)

### CRIT-001: Navegación interna rota — Todas las rutas → 404

**Severidad:** 🔴 Crítico
**Reportado por:** E2E Tester (live), DevOps Agent
**Afecta:** 5 idiomas × 4 enlaces = 20 enlaces rotos

**Evidencia:**
```
URL probada: https://translatio.thefuckinggoat.cloud/servicios → 404
URL probada: https://translatio.thefuckinggoat.cloud/proceso → 404
URL probada: https://translatio.thefuckinggoat.cloud/quienes-somos → 404
URL probada: https://translatio.thefuckinggoat.cloud/contacto → 404
```

**Causa raíz:** `Header.astro` genera enlaces a rutas separadas (`/servicios`, `/proceso`, `/quienes-somos`, `/contacto`) pero el sitio es single-page — solo existen `index.html` por idioma.

**Archivo afectado:** `src/components/Header.astro` (o equivalente en el framework)

**Corrección requerida:**
- Cambiar todos los enlaces de navegación de rutas absolutas a anchor links:
  - `/servicios` → `#services`
  - `/proceso` → `#process`
  - `/quienes-somos` → `#about`
  - `/contacto` → `#contact`
- Incluir el prefijo de idioma si es necesario: `/en/#services`, `/pt/#services`, etc.
- Verificar que las secciones correspondientes tengan los IDs correctos en el HTML

**Criterio de aceptación:**
- [ ] Todos los enlaces del header funcionan en los 5 idiomas
- [ ] Navegación con scroll suave a cada sección
- [ ] Funciona en desktop y mobile
- [ ] No hay errores 404 en ningún enlace interno

---

### CRIT-002: APIs inexistentes — Chatbot y formulario completamente inoperantes

**Severidad:** 🔴 Crítico
**Reportado por:** Integration Tester, E2E Tester (live), DevOps Agent
**Afecta:** Chatbot + Formulario de contacto en 5 idiomas

**Evidencia:**
```
POST https://translatio.thefuckinggoat.cloud/api/chat → 404 Not Found
POST https://translatio.thefuckinggoat.cloud/api/contact → 404 Not Found
```

**Causa raíz:** El proyecto está configurado como `output: 'static'`. Los componentes `ChatbotWidget.astro` y `ContactForm.astro` llaman a endpoints que no existen ni pueden existir en un sitio estático.

**Archivos afectados:**
- `src/components/ChatbotWidget.astro`
- `src/components/ContactForm.astro`

**Corrección requerida (elegir opción):**

**Opción A — Chatbot client-side + Formulario externo (RECOMENDADO):**
- Chatbot: Implementar lógica 100% client-side con FAQ emparejamiento por keywords. Sin API necesaria. Respuestas predefinidas en cada idioma.
- Formulario: Integrar con servicio externo (Formspree, Getform, o similar). Cambiar el `fetch('/api/contact')` por la URL del servicio externo.
- Ventaja: Mantiene el sitio estático, sin backend, sin costo adicional.

**Opción B — SSR con backend:**
- Cambiar Astro a `output: 'server'` o `output: 'hybrid'`
- Crear endpoints `/api/chat` y `/api/contact` como API routes de Astro
- Requiere servidor Node.js (desplegable en Coolify)
- Ventaja: Control total. Desventaja: Mayor complejidad, costo de servidor.

**Criterio de aceptación:**
- [ ] Chatbot responde preguntas frecuentes en los 5 idiomas
- [ ] Chatbot captura leads (nombre, email) y funciona sin backend
- [ ] Formulario de contacto envía datos correctamente
- [ ] No hay errores 404 ni de red al interactuar
- [ ] Tiempos de respuesta < 2 segundos

---

### CRIT-003: Vulnerabilidad XSS en Chatbot

**Severidad:** 🔴 Crítico (Seguridad)
**Reportado por:** Security Tester
**Afecta:** Chatbot en las 5 páginas

**Evidencia:**
```javascript
// Archivo: dist/index.html (línea 25), repetido en en/ pt/ zh/ fr/
function addMessage(text, isUser = false) {
    const div = document.createElement('div');
    div.innerHTML = isUser
      ? `<div ...><p class="text-sm">${text}</p></div>`
      : `<div ...><p class="text-sm">${text}</p></div>`;
}
```

**Vector de ataque:**
1. Usuario escribe `<img src=x onerror=alert(document.cookie)>`
2. `addMessage(msg, true)` inserta HTML sin sanitizar
3. Script se ejecuta en contexto de la página

**Corrección requerida:**
```javascript
// ANTES (vulnerable):
div.innerHTML = `...<p class="text-sm">${text}</p>...`;

// DESPUÉS (seguro) — Opción 1: textContent
const p = document.createElement('p');
p.className = 'text-sm';
p.textContent = text;
// ... armar DOM con createElement

// DESPUÉS (seguro) — Opción 2: sanitización
import DOMPurify from 'dompurify';
div.innerHTML = DOMPurify.sanitize(`...<p class="text-sm">${text}</p>...`);
```

**Criterio de aceptación:**
- [ ] `innerHTML` no se usa con input de usuario sin sanitizar
- [ ] Prueba XSS con `<img src=x onerror=alert(1)>` no ejecuta script
- [ ] Prueba XSS con `<script>alert(1)</script>` no ejecuta script
- [ ] Funciona correctamente en los 5 idiomas

---

### CRIT-004: Consentimiento GDPR falsificado en Chatbot

**Severidad:** 🔴 Crítico (Legal/Compliance)
**Reportado por:** Security Tester
**Afecta:** Chatbot lead form en 5 idiomas

**Evidencia:**
```javascript
// Archivo: dist/index.html (línea 110), repetido en todas las páginas
body: JSON.stringify({ name, email, language: lang, source: 'chatbot', gdpr_consent: true }),
```

`gdpr_consent: true` está **hardcodeado**. No existe checkbox de consentimiento. El usuario nunca acepta explícitamente el tratamiento de datos.

**Corrección requerida:**
- Añadir checkbox de consentimiento antes del envío del lead form
- Texto del checkbox (ejemplo en español): *"Acepto la Política de Privacidad y consiento el tratamiento de mis datos personales"*
- Solo enviar `gdpr_consent: true` si el checkbox está marcado
- Enlace a Política de Privacidad funcional (no a `#`)
- Implementar en los 5 idiomas

**Criterio de aceptación:**
- [ ] Checkbox visible antes del botón de envío
- [ ] Formulario NO se envía si checkbox no está marcado
- [ ] Texto del checkbox traducido en 5 idiomas
- [ ] Enlace a política de privacidad funcional
- [ ] `gdpr_consent` refleja el estado real del checkbox

---

## 🟠 DEFECTOS MAYORES (Deben corregirse antes de release)

### MAY-001: Selector de idioma no funciona en dispositivos táctiles

**Severidad:** 🟠 Mayor
**Reportado por:** Accessibility Tester, E2E Tester

**Evidencia:** El selector de idioma usa `hover` para desplegar opciones. En móviles/tablets no hay hover → no se puede cambiar de idioma.

**Corrección requerida:**
- Implementar menú toggle con `click/tap` en lugar de (o adicional a) `hover`
- Cerrar menú al seleccionar idioma o al hacer tap fuera
- Funcionar correctamente en iOS Safari, Android Chrome

**Criterio de aceptación:**
- [ ] Selector funciona con tap en mobile
- [ ] Selector funciona con hover en desktop
- [ ] Se cierra al seleccionar un idioma
- [ ] Se cierra al hacer tap fuera del menú

---

### MAY-002: Meta descriptions sin traducir

**Severidad:** 🟠 Mayor
**Reportado por:** E2E Tester, SEO

**Evidencia:**
```html
<!-- dist/en/index.html -->
<meta name="description" content="Soluciones de traducción profesionales para empresas...">
<!-- Mismo texto en español en todas las versiones -->
```

**Corrección requerida:** Traducir `meta description` a los 5 idiomas:
- ES: Ya existe
- EN: "Professional translation solutions for companies..."
- PT: "Soluções de tradução profissional para empresas..."
- ZH: "企业专业翻译解决方案..."
- FR: "Solutions de traduction professionnelles pour les entreprises..."

**Criterio de aceptación:**
- [ ] Cada página tiene meta description en su idioma
- [ ] Longitud entre 120-160 caracteres
- [ ] Incluye keywords relevantes por mercado

---

### MAY-003: Enlaces de Política de Privacidad apuntan a `#`

**Severidad:** 🟠 Mayor (Legal)
**Reportado por:** Security Tester

**Evidencia:**
```html
<a href="#" class="hover:text-white transition-colors">Política de Privacidad</a>
```

**Corrección requerida:**
- Crear página de Política de Privacidad (mínimo en ES y EN)
- Crear página de Términos y Condiciones
- Actualizar todos los enlaces del footer en los 5 idiomas

**Criterio de aceptación:**
- [ ] Enlaces dirigen a páginas reales
- [ ] Contenido legal revisado por profesional
- [ ] Disponible en los 5 idiomas (mínimo ES + EN)

---

### MAY-004: Sin headers de seguridad HTTP

**Severidad:** 🟠 Mayor (Seguridad)
**Reportado por:** Security Tester (live)

**Evidencia:** Headers verificados en producción:
```
Content-Security-Policy: ❌ Ausente
X-Content-Type-Options: ❌ Ausente
X-Frame-Options: ❌ Ausente
Strict-Transport-Security: ❌ Ausente
Referrer-Policy: ❌ Ausente
Permissions-Policy: ❌ Ausente
```

**Corrección requerida:**
Configurar en Cloudflare (Page Rules o Transform Rules) o en el servidor:
```
Content-Security-Policy: default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src 'self' https://fonts.gstatic.com; img-src 'self' data:; connect-src 'self' https://api.formspree.io (o el servicio elegido);
X-Content-Type-Options: nosniff
X-Frame-Options: DENY
Strict-Transport-Security: max-age=31536000; includeSubDomains; preload
Referrer-Policy: strict-origin-when-cross-origin
Permissions-Policy: camera=(), microphone=(), geolocation=()
```

**Criterio de aceptación:**
- [ ] Todos los headers anteriores presentes en respuestas HTTP
- [ ] CSP no bloquea recursos legítimos del sitio
- [ ] Verificado con https://securityheaders.com/ (mínimo B)

---

### MAY-005: Noto Sans SC (150KB) cargado innecesariamente en todas las páginas

**Severidad:** 🟠 Mayor (Performance)
**Reportado por:** Performance Tester

**Evidencia:** La fuente CJK `Noto Sans SC` (~150KB) se carga en las versiones ES, EN, PT y FR donde no se necesita chino.

**Corrección requerida:**
- Solo cargar `Noto Sans SC` en la versión `zh/`
- Usar `Noto Sans` (sin CJK) para las demás versiones
- Considerar `font-display: swap` para evitar FOIT

**Criterio de aceptación:**
- [ ] Noto Sans SC solo se carga en `/zh/`
- [ ] Otras versiones usan fuente sin CJK
- [ ] Lighthouse Performance Score ≥ 90 en las 5 versiones

---

### MAY-006: Sin sitemap.xml

**Severidad:** 🟠 Mayor (SEO)
**Reportado por:** DevOps Agent

**Corrección requerida:** Generar `sitemap.xml` con las URLs disponibles:
```xml
<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
  <url><loc>https://translatio.thefuckinggoat.cloud/</loc></url>
  <url><loc>https://translatio.thefuckinggoat.cloud/en/</loc></url>
  <url><loc>https://translatio.thefuckinggoat.cloud/pt/</loc></url>
  <url><loc>https://translatio.thefuckinggoat.cloud/zh/</loc></url>
  <url><loc>https://translatio.thefuckinggoat.cloud/fr/</loc></url>
</urlset>
```

**Criterio de aceptación:**
- [ ] sitemap.xml accesible en `/sitemap.xml`
- [ ] Incluye todas las URLs en los 5 idiomas
- [ ] Válido según schema de sitemaps.org

---

### MAY-007: Sin CSRF token en formulario de contacto

**Severidad:** 🟠 Mayor (Seguridad)
**Reportado por:** Security Tester

**Corrección requerida:**
- Si se usa servicio externo (Formspree), el CSRF lo maneja el servicio
- Si se implementa API propia, añadir CSRF token
- Documentar la solución elegida

**Criterio de aceptación:**
- [ ] Formulario protegido contra CSRF
- [ ] Verificado con prueba de envío desde origen externo

---

## 🟡 DEFECTOS MENORES (Corregir en siguiente iteración)

| ID | Descripción | Criterio de aceptación |
|----|-------------|----------------------|
| MIN-001 | Cloudflare Analytics token = "pending" | Configurar token real |
| MIN-002 | Generator tag expone versión de Astro | Remover `<meta name="generator">` |
| MIN-003 | Disclaimer legal sin traducir en PT y FR | Traducir en los 5 idiomas |
| MIN-004 | Footer parcialmente sin traducir | Revisar todos los textos del footer |
| MIN-005 | Chatbot sin validación de email | Añadir regex de validación |
| MIN-006 | `console.log` con versión en JS del tema WP | Remover en producción |

---

## 📊 RESUMEN DE CORRECCIONES

| Prioridad | Cantidad | Bloquea release |
|-----------|----------|----------------|
| 🔴 Crítico | 4 | SÍ |
| 🟠 Mayor | 7 | SÍ |
| 🟡 Menor | 6 | No (siguiente iteración) |
| **Total** | **17** | **11 bloquean** |

---

## 🎯 ORDEN SUGERIDO DE EJECUCIÓN

### Sprint 1 — Críticos (debe completarse antes de cualquier release)

1. **CRIT-001** — Fix navegación (anchor links) → 1-2 horas
2. **CRIT-002** — Fix chatbot client-side + formulario externo → 4-6 horas
3. **CRIT-003** — Fix XSS (innerHTML → textContent) → 1 hora
4. **CRIT-004** — Fix GDPR consent → 1-2 horas

### Sprint 2 — Mayores

5. **MAY-001** — Fix selector idioma mobile → 2-3 horas
6. **MAY-002** — Traducir meta descriptions → 30 min
7. **MAY-003** — Crear páginas legales → 4-6 horas
8. **MAY-004** — Configurar security headers → 1-2 horas
9. **MAY-005** — Font loading condicional → 30 min
10. **MAY-006** — Generar sitemap.xml → 15 min
11. **MAY-007** — CSRF protection → 1-2 horas

### Sprint 3 — Menores (post-release)

12-17. Defectos menores

**Estimación total Sprints 1+2:** 16-25 horas de trabajo

---

## ✅ CRITERIOS DE ACEPTACIÓN PARA RELEASE

Antes de considerar el sitio listo para producción, TODOS los siguientes deben cumplirse:

### Funcionalidad
- [ ] Todas las páginas cargan sin errores 404 (5 idiomas)
- [ ] Navegación interna funciona (anchor links)
- [ ] Chatbot responde en los 5 idiomas
- [ ] Formulario de contacto envía datos correctamente
- [ ] Selector de idioma funciona en desktop y mobile

### Seguridad
- [ ] No hay vectores XSS
- [ ] GDPR consent es funcional (no hardcodeado)
- [ ] Security headers configurados
- [ ] No hay exposición de información sensible
- [ ] CSRF protegido

### SEO
- [ ] Meta descriptions traducidas en 5 idiomas
- [ ] sitemap.xml accesible
- [ ] Lang attributes correctos

### Performance
- [ ] Lighthouse Performance ≥ 90
- [ ] Fuentes CJK solo en versión china
- [ ] Tiempo de carga < 3s en Europa, LatAm, China

### Legal
- [ ] Política de privacidad accesible (no `#`)
- [ ] Disclaimer traducido en 5 idiomas
- [ ] Consentimiento GDPR funcional

---

## 📎 REFERENCIAS

### Reportes de auditoría (disponibles en repo):
- `docs/05-testing/SUPER_TEST_LEAD_REPORT.md` — Resumen ejecutivo testing
- `docs/05-testing/SECURITY_TESTER_REPORT.md` — Auditoría seguridad
- `docs/05-testing/E2E_TESTER_REPORT.md` — Pruebas E2E
- `docs/05-testing/ACCESSIBILITY_TESTER_REPORT.md` — Auditoría accesibilidad
- `docs/05-testing/PERFORMANCE_TESTER_REPORT.md` — Análisis performance
- `docs/05-testing/LIVE_TESTING_REPORT.md` — Pruebas en producción
- `docs/05-testing/LIVE_vs_LOCAL_COMPARISON.md` — Comparativa
- `docs/05-testing/DEFECT_LOG.md` — Log completo de defectos
- `docs/05-testing/LECCIONES_APRENDIDAS_TESTING.md` — Lecciones del proceso
- `docs/05-testing/LECCIONES_APRENDIDAS_DEVOPS.md` — Lecciones DevOps
- `docs/07-deployment/DEVOPS_FIX_REPORT.md` — Reporte DevOps
- `docs/07-deployment/DEPLOYMENT_FIX_ACTIONS.md` — Acciones correctivas

---

**Generado por:** MOLINO Testing Squad + DevOps Agent
**Fecha:** 17 Abril 2026
**Próxima revisión:** Post-corrección, antes de release
