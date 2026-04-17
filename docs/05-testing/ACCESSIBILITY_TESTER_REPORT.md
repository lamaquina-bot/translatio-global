# ♿ ACCESSIBILITY TESTER REPORT — Translatio Global

**Agente:** Accessibility Tester ♿  
**Fecha:** 2026-04-17  
**Versión:** 1.0.0  

---

## Resumen Ejecutivo

Auditoría de accesibilidad WCAG 2.2 AA sobre las 5 variantes del sitio. El sitio tiene una base semántica razonable pero presenta deficiencias en varios criterios.

---

## Resultado Global

| Criterio | Estado | Nivel WCAG |
|----------|--------|------------|
| Lenguaje de la página | ✅ | 3.1.1 A |
| Título de página | ✅ | 2.4.2 A |
| Estructura de encabezados | ✅ | 1.3.1 A |
| Etiquetas de formulario | ✅ | 1.3.1 A |
| Contraste de color | ✅ | 1.4.3 AA |
| Navegación por teclado | ⚠️ | 2.1.1 A |
| Nombre accesible | ⚠️ | 4.1.2 A |
| Live regions | ❌ | 4.1.3 AA |

---

## 1. Atributos de Lenguaje

| Página | `lang` attribute | Correcto |
|--------|------------------|----------|
| `index.html` | `es` | ✅ |
| `en/index.html` | `en` | ✅ |
| `pt/index.html` | `pt` | ✅ |
| `zh/index.html` | `zh` | ✅ |
| `fr/index.html` | `fr` | ✅ |

⚠️ **ACC-001 (Major):** El selector de idioma no tiene `lang` en los textos de opciones. Los nombres de idioma deberían tener `lang` attributes (ej: `<span lang="zh">中文</span>`).

---

## 2. Estructura de Encabezados

Todas las páginas siguen la jerarquía correcta:
```
h1 — Título principal (hero)
  h2 — Secciones (Servicios, Proceso, Contacto, CTA)
    h3 — Sub-elementos (cards, pasos)
```
✅ Correcto — un solo `h1` por página, jerarquía lógica.

---

## 3. Formularios

### Formulario de Contacto

| Campo | `<label>` | `for`/`id` | `required` | Estado |
|-------|-----------|------------|------------|--------|
| Nombre | ✅ "Nombre *" | ✅ `for="name"` | ✅ | OK |
| Email | ✅ "Email *" | ✅ `for="email"` | ✅ | OK |
| País | ✅ "País *" | ✅ `for="country"` | ✅ | OK |
| Mensaje | ✅ "Mensaje" | ✅ `for="message"` | — | OK |
| GDPR | ✅ | ✅ `for="gdpr"` | ✅ | OK |

✅ Todos los campos tienen labels asociados correctamente.

### ⚠️ ACC-002 (Major): Chatbot Lead Form — Sin Labels

```html
<input id="lead-name" type="text" placeholder="Tu nombre" ...>
<input id="lead-email" type="email" placeholder="Tu email" ...>
```

**Problema:** Los campos del lead form del chatbot NO tienen `<label>`. Solo usan `placeholder` que no es accesible.

**Fix:** Añadir `<label for="lead-name" class="sr-only">Nombre</label>` o usar `aria-label`.

---

## 4. Navegación por Teclado

| Elemento | Tab-accesible | Enter/Space funcional | Focus visible |
|----------|---------------|----------------------|---------------|
| Links nav | ✅ | ✅ | ✅ |
| Botón menú mobile | ✅ | ⚠️ Solo click | ⚠️ outline-none |
| Botón chat toggle | ✅ | ✅ | ⚠️ outline-none |
| Chatbot close | ✅ | ✅ | ⚠️ outline-none |
| Quick replies | ✅ | ✅ | ⚠️ outline-none |
| Form inputs | ✅ | ✅ | ✅ focus:border + focus:ring |

### ⚠️ ACC-003 (Major): outline-none sin focus ring alternativo

El CSS tiene `.outline-none{outline:2px solid transparent;outline-offset:2px}` aplicado a todos los inputs. Aunque los inputs tienen `focus:border` y `focus:ring`, otros elementos interactivos (botones) no tienen indicador de focus visible.

**Fix:** Añadir `focus:ring` o `focus-visible:ring` a todos los botones.

---

## 5. ARIA

| Elemento | ARIA | Correcto |
|----------|------|----------|
| Mobile menu button | `aria-label="Menu"` | ✅ |
| Language selector | `aria-label="Select language"` | ✅ (pero debería estar en el idioma de la página) |
| Chatbot toggle | `aria-label="Chat"`, `aria-expanded="false"` | ✅ |
| Chatbot close | `aria-label="Close"` | ✅ |
| Honeypot | `aria-hidden="true"` | ✅ |

### ❌ ACC-004 (Major): Chatbot messages sin aria-live

El área de mensajes del chatbot no tiene `aria-live="polite"`. Los lectores de pantalla no serán notificados cuando aparezcan nuevos mensajes.

**Fix:** Añadir `aria-live="polite"` a `#chatbot-messages`.

---

## 6. Contraste de Color

| Combinación | Ratio (est.) | Mínimo AA | Estado |
|-------------|-------------|-----------|--------|
| `#2D2D2D` sobre `#FAFAF7` | ~12:1 | 4.5:1 | ✅ |
| `#4A7C6F` sobre blanco | ~4.6:1 | 4.5:1 | ✅ (ajustado) |
| `#4A7C6F` sobre `#FAFAF7` | ~4.5:1 | 4.5:1 | ✅ Barely |
| Gris `#9CA3AF` sobre blanco | ~3:1 | 4.5:1 | ❌ Placeholder text |
| `text-white/40` sobre `#2D2D2D` | ~2.5:1 | 4.5:1 | ❌ Footer copyright |
| `text-white/80` sobre `#2D2D2D` | ~5:1 | 4.5:1 | ✅ |
| `text-gray-400` (`#9CA3AF`) sobre gris-50 | ~2.8:1 | 4.5:1 | ❌ Disclaimer chatbot |

### ⚠️ ACC-005 (Minor): Texto con contraste insuficiente

- Footer copyright (`text-white/40` sobre `#2D2D2D`): ~2.5:1 ❌
- Chatbot disclaimer (`text-gray-400` sobre `bg-gray-50`): ~2.8:1 ❌
- Placeholders de inputs: `#9CA3AF` sobre blanco: ~3:1 ❌ (placeholders están exentos de 1.4.3 pero no de 1.4.11)

---

## 7. Otros Hallazgos

| ID | Severidad | Descripción |
|----|-----------|-------------|
| ACC-006 | Minor | Selector de idioma usa `group-hover:block` — no es accesible por teclado (no se puede abrir con Tab) |
| ACC-007 | Minor | Botón menú mobile usa ☰ como texto — debería usar SVG con `aria-hidden` + texto en `sr-only` |
| ACC-008 | Enhancement | Añadir `role="dialog"` y `aria-modal="true"` a la ventana del chatbot |
| ACC-009 | Enhancement | Añadir skip-to-content link al inicio |
| ACC-010 | Enhancement | Footer links "Política de Privacidad" y "Aviso Legal" apuntan a `#` |

---

## Recomendaciones Prioritarias

1. **Major:** Añadir `aria-live="polite"` a `#chatbot-messages`
2. **Major:** Añadir labels accesibles al chatbot lead form
3. **Major:** Añadir `focus-visible` styles a todos los botones
4. **Minor:** Añadir `lang` attributes a textos en otros idiomas en el selector
5. **Minor:** Reemplazar ☰ con SVG accesible
6. **Enhancement:** Añadir skip-to-content link

---

## Checklist WCAG 2.2 AA

| Criterio | Estado | Notas |
|----------|--------|-------|
| 1.1.1 Non-text Content | ✅ | Solo emojis decorativos |
| 1.3.1 Info and Relationships | ⚠️ | Lead form sin labels |
| 1.4.3 Contrast (Minimum) | ⚠️ | Algunos textos tenues |
| 2.1.1 Keyboard | ⚠️ | Selector idioma no navegable por teclado |
| 2.4.2 Page Titled | ✅ | Todos tienen `<title>` |
| 2.4.6 Headings and Labels | ✅ | Jerarquía correcta |
| 3.1.1 Language of Page | ✅ | `lang` correcto en todas |
| 4.1.2 Name, Role, Value | ⚠️ | Botones sin focus visible |
| 4.1.3 Status Messages | ❌ | Sin aria-live en chat |

**Conformidad estimada: ~75% WCAG 2.2 AA**

---

*Generado por MOLINO Testing Squad — 2026-04-17*
