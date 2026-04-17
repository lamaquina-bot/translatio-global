# 🚀 RELEASE BRIEF — Translatio Global

**Proyecto:** Translatio Global  
**Fecha:** 2026-04-17  
**Versión:** 1.0.0  
**Autor:** MOLINO Testing Squad — Super Test Lead

---

## Veredicto: ⚠️ GO CONDICIONAL

El sitio puede ser publicado **después de resolver 2 defectos críticos y al menos 5 defectos mayores prioritarios**.

---

## Defectos Bloqueantes (Must Fix)

- [ ] **SEG-001** — XSS en chatbot `addMessage()` → reemplazar innerHTML con textContent
- [ ] **SEG-002** — GDPR consent falsificado en chatbot → añadir checkbox GDPR

## Defectos Mayores Prioritarios (Should Fix)

- [ ] **INT-008** — Selector de idioma no funciona en mobile → cambiar a click-based
- [ ] **INT-009** — Meta description no traducida en 4 idiomas → traducir
- [ ] **PERF-001** — Noto Sans SC se carga en todos los idiomas → solo en ZH
- [ ] **ACC-004** — Chatbot sin aria-live → añadir `aria-live="polite"`
- [ ] **ACC-002** — Chatbot lead form sin labels → añadir aria-label

## Defectos Mayores Recomendados (Nice to Fix)

- [ ] SEG-003: Crear páginas legales (Política de Privacidad, Aviso Legal)
- [ ] SEG-004: CSRF token en formulario
- [ ] PERF-002: `font-display: swap` en Google Fonts
- [ ] CHAOS-001: Self-host fuentes o CDN alternativo para China
- [ ] INT-002: Manejo de errores HTTP en formulario
- [ ] ACC-003: Focus-visible en botones
- [ ] VIS-001: Verificar renderizado CJK

---

## Checklist de Sign-Off

| # | Criterio | Responsable | Estado |
|---|----------|-------------|--------|
| 1 | XSS corregido en chatbot | Dev | ☐ |
| 2 | GDPR checkbox en chatbot | Dev | ☐ |
| 3 | Selector idioma mobile funcional | Dev | ☐ |
| 4 | Meta descriptions traducidas | Dev | ☐ |
| 5 | Páginas legales creadas | Legal/Dev | ☐ |
| 6 | API backend operativo | DevOps | ☐ |
| 7 | HTTPS configurado | DevOps | ☐ |
| 8 | Headers de seguridad configurados | DevOps | ☐ |
| 9 | Cloudflare analytics token configurado | DevOps | ☐ |
| 10 | DNS apuntando al servidor correcto | DevOps | ☐ |
| 11 | Formulario testado E2E (envío real) | QA | ☐ |
| 12 | Chatbot testado con API real | QA | ☐ |
| 13 | Verificado en Chrome, Firefox, Safari, Edge | QA | ☐ |
| 14 | Verificado en mobile (iOS + Android) | QA | ☐ |
| 15 | Versión ZH verificada en dispositivo CJK | QA | ☐ |

---

## Riesgos Aceptados

| Riesgo | Impacto | Probabilidad | Mitigación |
|--------|---------|-------------|------------|
| Sin CSRF token | Spam de formulario | Media | Honeypot + server-side validation |
| Sin páginas legales | Incumplimiento GDPR | Alta | Crear antes de tráfico significativo |
| Google Fonts bloqueado en China | ZH sin estilo correcto | Alta | Self-host como siguiente sprint |

---

## Plan de Acción Post-Release

1. **Semana 1:** Monitorear analytics, formularios y chatbot
2. **Semana 2:** Implementar páginas legales, CSRF, headers de seguridad
3. **Semana 3:** Self-host fuentes, service worker, optimizaciones
4. **Semana 4:** Tests automáticos E2E, visual regression setup

---

## Firmas

| Rol | Nombre | Firma | Fecha |
|-----|--------|-------|-------|
| Super Test Lead | MOLINO Testing Squad | ✅ Auditado | 2026-04-17 |
| Dev Lead | — | ☐ | — |
| Product Owner | — | ☐ | — |

---

*Generado por MOLINO Testing Squad — 2026-04-17*
