# 🐛 Log de Defectos — Translatio Global (LIVE)

**Fecha:** 2026-04-17  
**URL:** https://translatio.thefuckinggoat.cloud/  
**Entorno:** Producción (Cloudflare + nginx/1.29.8)

---

## DEFECTOS CRÍTICOS 🔴

### LIVE-001 — Todas las páginas internas retornan 404
- **Severidad:** CRÍTICO  
- **Categoría:** E2E / Funcional  
- **Descripción:** Todos los enlaces de navegación (servicios, proceso, quiénes somos, contacto) retornan HTTP 404 en los 5 idiomas. Solo las homepages funcionan.  
- **Evidencia:**  
  - `/servicios` → 404, `/en/services` → 404, `/pt/servicos` → 404, `/zh/services` → 404, `/fr/services` → 404  
  - `/proceso` → 404, `/en/process` → 404, `/pt/processo` → 404  
  - `/quienes-somos` → 404, `/en/about` → 404  
  - `/contacto` → 404, `/en/contact` → 404, `/pt/contato` → 404  
  - Lo mismo para `/fr/processus`, `/fr/a-propos`, `/fr/contact`  
- **Impacto:** El sitio es funcionalmente una sola página con enlaces rotos. Navegación completamente rota.  
- **Recomendación:** Crear las páginas faltantes o cambiar enlaces a anclas internas (#) si es single-page.

### LIVE-002 — API de chatbot y formulario de contacto retornan 404
- **Severidad:** CRÍTICO  
- **Categoría:** E2E / Funcional  
- **Descripción:** Los endpoints `/api/chat` y `/api/contact` no existen en el servidor. El chatbot no puede enviar mensajes y el formulario de contacto no puede procesar envíos.  
- **Evidencia:**  
  - `GET /api/contact` → 404  
  - `GET /api/chat` → 404  
  - El JS del formulario envía POST a `/api/contact` → fallará  
  - El JS del chatbot envía POST a `/api/chat` → fallará  
- **Impacto:** Chatbot y formulario completamente no funcionales. Leads se pierden.  
- **Recomendación:** Implementar endpoints API o usar servicio externo (Formspree, etc.).

---

## DEFECTOS ALTOS 🟠

### LIVE-003 — Meta description no traducida (misma en los 5 idiomas)
- **Severidad:** ALTO  
- **Categoría:** SEO / Contenido  
- **Descripción:** El atributo `meta name="description"` contiene texto en español en TODAS las versiones de idioma.  
- **Evidencia:**  
  - `/` (ES): `Translatio Global - Acompañamiento integral en subrogación gestacional en Colombia`  
  - `/en/` (EN): `Translatio Global - Acompañamiento integral en subrogación gestacional en Colombia` (igual)  
  - `/pt/` (PT): `Translatio Global - Acompañamiento integral en subrogación gestacional en Colombia` (igual)  
  - `/zh/` (ZH): `Translatio Global - Acompañamiento integral en subrogación gestacional en Colombia` (igual)  
  - `/fr/` (FR): `Translatio Global - Acompañamiento integral en subrogación gestacional en Colombia` (igual)  
- **Impacto:** SEO penalizado en Google para idiomas no españoles. Descripción irrelevante para usuarios.  
- **Recomendación:** Traducir meta description a cada idioma.

### LIVE-004 — XSS en chatbot mediante `addMessage()` con innerHTML
- **Severidad:** ALTO  
- **Categoría:** Seguridad  
- **Descripción:** La función `addMessage()` usa `div.innerHTML = ...` con interpolación directa de texto del usuario (`${text}`) sin sanitización.  
- **Evidencia:** En todas las páginas, el JS del chatbot contiene:  
  ```javascript
  div.innerHTML = isUser
    ? `<div ...><p class="text-sm">${text}</p></div>`
    : `<div ...><p class="text-sm">${text}</p></div>`;
  ```
- **Impacto:** Un usuario puede inyectar HTML/JS arbitrario en el chat. Vector de XSS almacenado si los mensajes se comparten.  
- **Recomendación:** Usar `textContent` en lugar de `innerHTML`, o sanitizar con DOMPurify.

### LIVE-005 — Selector de idioma no funcional en móvil (solo hover)
- **Severidad:** ALTO  
- **Categoría:** UX / Responsive  
- **Descripción:** El dropdown de idiomas usa `group-hover:block` que solo funciona con hover de ratón. En dispositivos táctiles (móvil/tablet), no hay forma de abrir el selector.  
- **Evidencia:** HTML: `class="... hidden group-hover:block ..."` en el dropdown. No hay evento click/touch.  
- **Impacto:** Usuarios móviles no pueden cambiar de idioma. ~60% del tráfico web es móvil.  
- **Recomendación:** Agregar toggle por click/touch con JavaScript.

### LIVE-006 — Ausencia de headers de seguridad
- **Severidad:** ALTO  
- **Categoría:** Seguridad  
- **Descripción:** No se detectan headers de seguridad estándar en las respuestas HTTP.  
- **Evidencia:** Headers faltantes:  
  - `X-Content-Type-Options` — ausente  
  - `X-Frame-Options` — ausente  
  - `Content-Security-Policy` — ausente  
  - `Strict-Transport-Security` — ausente (Cloudflare lo debería inyectar)  
  - `Referrer-Policy` — ausente  
  - `Permissions-Policy` — ausente  
- **Impacto:** Vulnerable a clickjacking, MIME sniffing, y otros ataques.  
- **Recomendación:** Configurar headers en nginx o via Cloudflare Page Rules.

### LIVE-007 — No existe sitemap.xml
- **Severidad:** ALTO  
- **Categoría:** SEO  
- **Descripción:** `https://translatio.thefuckinggoat.cloud/sitemap.xml` retorna 404.  
- **Evidencia:** Respuesta: `<center><h1>404 Not Found</h1></center>`  
- **Impacto:** Motores de búsqueda no pueden descubrir eficientemente las páginas.  
- **Recomendación:** Generar sitemap.xml con Astro.

---

## DEFECTOS MEDIOS 🟡

### LIVE-008 — Noto Sans SC cargado innecesariamente en todos los idiomas
- **Severidad:** MEDIO  
- **Categoría:** Rendimiento  
- **Descripción:** La fuente `Noto Sans SC` (CJK/China) se carga en las 5 versiones de idioma, incluyendo español, inglés, portugués y francés donde no se necesita.  
- **Evidencia:** En todas las páginas: `<link href="https://fonts.googleapis.com/css2?family=Inter:...&family=Noto+Sans+SC:...&family=Playfair+Display:..." rel="stylesheet">`  
- **Impacto:** Descarga innecesaria de ~1-2MB de fuentes CJK en páginas que no usan caracteres chinos.  
- **Recomendación:** Cargar Noto Sans SC solo en `/zh/`.

### LIVE-009 — Privacy policy y legal links apuntan a `#`
- **Severidad:** MEDIO  
- **Categoría:** Legal / Contenido  
- **Descripción:** Los enlaces de "Política de Privacidad" y "Aviso Legal" en el footer apuntan a `#` (navegación a sí mismo).  
- **Evidencia:** `<a href="#" class="hover:text-white transition-colors">Política de Privacidad</a>`  
- **Impacto:** Incumplimiento GDPR/RGPD potencial. No hay política de privacidad accesible.  
- **Recomendación:** Crear páginas de política de privacidad y aviso legal.

### LIVE-010 — Token de Cloudflare Analytics en estado "pending"
- **Severidad:** MEDIO  
- **Categoría:** Configuración  
- **Descripción:** El beacon de Cloudflare Web Analytics tiene token `"pending"`, indicando que nunca se configuró correctamente.  
- **Evidencia:** `data-cf-beacon="{&quot;token&quot;: &quot;pending&quot;}"`  
- **Impacto:** Analytics no funciona. No hay datos de visitantes.  
- **Recomendación:** Configurar token real de Cloudflare Web Analytics o eliminar el script.

### LIVE-011 — Generator tag expone versión de Astro
- **Severidad:** MEDIO  
- **Categoría:** Seguridad (Information Disclosure)  
- **Descripción:** `<meta name="generator" content="Astro v5.18.1">` revela la versión exacta del framework.  
- **Evidencia:** Presente en todas las páginas.  
- **Impacto:** Facilita ataques dirigidos a vulnerabilidades conocidas de Astro v5.18.1.  
- **Recomendación:** Eliminar o ocultar el meta generator.

### LIVE-012 — Disclaimer del chatbot sin traducir en PT y FR
- **Severidad:** MEDIO  
- **Categoría:** Contenido / i18n  
- **Descripción:** El disclaimer legal del chatbot está en inglés en las versiones PT y FR (y posiblemente otras).  
- **Evidencia:**  
  - `/pt/`: `<p class="text-[10px] text-gray-400">This information is general and does not constitute medical or legal advice.</p>`  
  - `/fr/`: Mismo texto en inglés  
  - `/zh/`: Correctamente traducido a chino  
  - `/en/`: Correcto (es inglés)  
  - `/es`: Correctamente traducido a español  
- **Impacto:** Inconsistencia de idioma. Disclaimer legal potencialmente no válido para usuarios PT/FR.  
- **Recomendación:** Traducir disclaimer en PT y FR.

### LIVE-013 — Footer "Colombia 🇨🇴" no traducido
- **Severidad:** MEDIO  
- **Categoría:** Contenido / i18n  
- **Descripción:** El texto del footer `Colombia 🇨🇴` aparece igual en todos los idiomas, incluyendo chino y francés.  
- **Evidencia:** Presente en todas las páginas: `<p class="text-sm mt-1 text-white/60">Colombia 🇨🇴</p>`  
- **Impacto:** Menor, pero inconsistente con la localización.  
- **Recomendación:** Considerar traducción o dejar como nombre propio.

---

## DEFECTOS BAJOS 🟢

### LIVE-014 — Sin alt text en imágenes/emojis decorativos
- **Severidad:** BAJO  
- **Categoría:** Accesibilidad  
- **Descripción:** Los emojis usados como iconos (👨‍👩‍👧, 🤰, ⚖️) no tienen alt text descriptivo.  
- **Evidencia:** `<div class="text-4xl mb-4">👨👩👧</div>` — sin atributo alt ni aria-label.  
- **Impacto:** Lectores de pantalla no pueden interpretar los iconos.  
- **Recomendación:** Agregar `aria-hidden="true"` si decorativos, o `aria-label` si informativos.

### LIVE-015 — Honeypot visible en DOM (aunque hidden)
- **Severidad:** BAJO  
- **Categoría:** Seguridad  
- **Descripción:** El campo honeypot usa `class="hidden"` que es detectable por bots avanzados.  
- **Evidencia:** `<div class="hidden" aria-hidden="true"><input type="text" name="website"...></div>`  
- **Impacto:** Protección mínima contra spam.  
- **Recomendación:** Usar técnicas más robustas (reCAPTCHA, Cloudflare Turnstile).

---

## RESUMEN

| Severidad | Cantidad |
|-----------|----------|
| 🔴 CRÍTICO | 2 |
| 🟠 ALTO | 5 |
| 🟡 MEDIO | 6 |
| 🟢 BAJO | 2 |
| **TOTAL** | **15** |
