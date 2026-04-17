# 📋 DEFECT LOG — Translatio Global

**Proyecto:** Translatio Global  
**Fecha:** 2026-04-17  
**Fuente:** MOLINO Testing Squad — 8 disciplinas de testing

---

## Resumen

| Severidad | Cantidad |
|-----------|----------|
| 🔴 Critical | 2 |
| 🟠 Major | 14 |
| 🟡 Minor | 17 |
| 🔵 Enhancement | 19 |
| **Total** | **52** |

---

## Defectos Críticos

| ID | Severidad | Agente | Descripción | Ubicación | Recomendación | Estado |
|----|-----------|--------|-------------|-----------|---------------|--------|
| SEG-001 | 🔴 Critical | Security | XSS en `addMessage()` — usa innerHTML con input de usuario sin sanitizar | Chatbot script, todas las páginas | Reemplazar innerHTML con textContent o DOMPurify | 🔴 Open |
| SEG-002 | 🔴 Critical | Security/Integration | GDPR consent falsificado — chatbot envía `gdpr_consent: true` sin checkbox real | Chatbot lead-submit, todas las páginas | Añadir checkbox GDPR al lead form del chatbot | 🔴 Open |

---

## Defectos Mayores

| ID | Severidad | Agente | Descripción | Ubicación | Recomendación | Estado |
|----|-----------|--------|-------------|-----------|---------------|--------|
| UT-001 | 🟠 Major | Unit | `addMessage()` usa innerHTML → riesgo XSS | Chatbot, todas las páginas | Usar textContent | 🔴 Open |
| INT-002 | 🟠 Major | Integration | No hay manejo de error HTTP no-200 en formulario | Form handler, todas las páginas | Mostrar mensaje de error al usuario | 🔴 Open |
| INT-003 | 🟠 Major | Integration | Sin rate limiting client-side en formulario | Form handler | Añadir debounce/throttle | 🔴 Open |
| INT-008 | 🟠 Major | Integration | Selector de idioma no funciona en mobile (hover-only) | Header, todas las páginas | Cambiar a click-based dropdown | 🔴 Open |
| SEG-003 | 🟠 Major | Security | Enlaces de Política de Privacidad apuntan a `#` | Footer, todas las páginas | Crear páginas legales | 🔴 Open |
| SEG-004 | 🟠 Major | Security | Sin CSRF token en formulario | Form handler | Implementar CSRF token | 🔴 Open |
| SEG-005 | 🟠 Major | Security | Sin Content-Security-Policy header | Servidor | Configurar CSP | 🔴 Open |
| PERF-001 | 🟠 Major | Performance | Noto Sans SC (150KB) se carga en todos los idiomas | `<head>`, todas las páginas | Cargar solo en ZH | 🔴 Open |
| PERF-002 | 🟠 Major | Performance | Sin `font-display: swap` en Google Fonts URL | `<head>`, todas las páginas | Añadir `&display=swap` | 🔴 Open |
| ACC-001 | 🟠 Major | Accessibility | Selector de idioma sin `lang` en textos de opciones | Header dropdown | Añadir `lang` attributes | 🔴 Open |
| ACC-002 | 🟠 Major | Accessibility | Chatbot lead form sin labels accesibles | Chatbot lead form | Añadir aria-label o labels | 🔴 Open |
| ACC-003 | 🟠 Major | Accessibility | Botones sin focus-visible style | Botones chatbot, mobile menu | Añadir focus-visible ring | 🔴 Open |
| ACC-004 | 🟠 Major | Accessibility | Chatbot messages sin aria-live | `#chatbot-messages` | Añadir `aria-live="polite"` | 🔴 Open |
| VIS-001 | 🟠 Major | Visual | Posible conflicto de especificidad en fuentes CJK (ZH) | CSS inline vs global | Verificar renderizado real | 🔴 Open |
| CHAOS-001 | 🟠 Major | Chaos | Google Fonts es SPOF — bloqueado en China | `<head>`, todas las páginas | Self-host o CDN alternativo | 🔴 Open |
| CHAOS-002 | 🟠 Major | Chaos | APIs sin feedback de error visible | Form handler + chatbot | Añadir mensajes de error | 🔴 Open |

---

## Defectos Menores

| ID | Severidad | Agente | Descripción | Estado |
|----|-----------|--------|-------------|--------|
| UT-002 | 🟡 Minor | Unit | catch vacío en form handler — no muestra error al usuario | 🔴 Open |
| UT-003 | 🟡 Minor | Unit | Sin validación de email en chatbot lead form | 🔴 Open |
| INT-004 | 🟡 Minor | Integration | Contrato API /api/contact no documentado | 🔴 Open |
| INT-007 | 🟡 Minor | Integration | Session ID del chatbot nunca se renueva | 🔴 Open |
| E2E-001 | 🟡 Minor | E2E | Selector idioma no funcional en mobile | 🔴 Open |
| E2E-002 | 🟡 Minor | E2E | Sección CTA ausente en PT, ZH, FR | 🔴 Open |
| E2E-003 | 🟡 Minor | E2E | Nav links apuntan a páginas inexistentes en dist | 🔴 Open |
| PERF-003 | 🟡 Minor | Performance | 3 familias de fuentes es excesivo para landing | 🔴 Open |
| ACC-005 | 🟡 Minor | Accessibility | Textos con contraste insuficiente (footer, disclaimer) | 🔴 Open |
| ACC-006 | 🟡 Minor | Accessibility | Selector idioma no accesible por teclado | 🔴 Open |
| ACC-007 | 🟡 Minor | Accessibility | Botón menú usa ☰ como texto (debería ser SVG) | 🔴 Open |
| VIS-002 | 🟡 Minor | Visual | Chatbot disclaimer no traducido en PT y FR | 🔴 Open |
| VIS-003 | 🟡 Minor | Visual | Sección CTA intermedia ausente en PT, ZH, FR | 🔴 Open |
| CHAOS-003 | 🟡 Minor | Chaos | Formulario sin action/method como fallback sin JS | 🔴 Open |
| INT-009 | 🟡 Minor | Integration | Meta description no traducida en EN, PT, ZH, FR | 🔴 Open |

---

## Mejoras Sugeridas

| ID | Agente | Descripción |
|----|--------|-------------|
| UT-004 | Unit | Eliminar variable `escalateCount` no usada |
| UT-005 | Unit | Eliminar `const q = btn.dataset.q` no usada |
| INT-005 | Integration | Añadir retry logic y timeout a fetch |
| INT-006 | Integration | Añadir timeout a requests del chat |
| INT-009 | Integration | Añadir `hreflang` tags para SEO multilingüe |
| E2E-004 | E2E | Implementar tests automáticos con Playwright |
| E2E-005 | E2E | Verificar scroll suave a secciones |
| E2E-006 | E2E | Test de FCP < 2s |
| E2E-007 | E2E | Test de TTI < 3s |
| E2E-008 | E2E | Test visual con Percy/Chromatic |
| PERF-004 | Performance | Pre-cachear fuentes con service worker |
| PERF-005 | Performance | Preload de fuentes críticas |
| PERF-006 | Performance | Minificar HTML |
| SEG-006 | Security | Sin validación de email en chatbot lead |
| SEG-007 | Security | Cloudflare token = "pending" |
| ACC-008 | Accessibility | Añadir role="dialog" al chatbot |
| ACC-009 | Accessibility | Añadir skip-to-content link |
| ACC-010 | Accessibility | Footer links apuntan a # |
| CHAOS-004 | Chaos | Implementar service worker para offline |

---

*Generado por MOLINO Testing Squad — 2026-04-17*
