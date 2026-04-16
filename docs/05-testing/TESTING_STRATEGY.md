# 🛡️ Translatio Global — Testing Strategy

**VERSIÓN:** 1.0
**Fecha:** 16 Abril 2026
**Quality Strategy Lead:** Coordina

---

## PRINCIPIO

Testing es primordial y transversal. Cada entregable se valida antes de avanzar a la siguiente fase.

---

## GATES POR FASE

### Gate 1: Contenido
- ✅ Contenido completo en 5 idiomas
- ✅ Revisión de tono (humano, empático, no corporativo)
- ✅ Legal review: información de subrogación es precisa
- ✅ Accesibilidad del lenguaje

### Gate 2: Diseño
- ✅ Responsive (desktop, tablet, mobile)
- ✅ Consistencia visual en 5 idiomas
- ✅ Contraste y legibilidad (incluido chino)
- ✅ Accesibilidad WCAG 2.2 AA

### Gate 3: Desarrollo
- ✅ Todos los flujos de usuario funcionan en cada idioma
- ✅ Formulario de contacto envía correctamente
- ✅ Chatbot responde en el idioma correcto
- ✅ Navegación entre idiomas sin errores
- ✅ SEO básico en cada idioma

### Gate 4: Chatbot
- ✅ FAQ respuestas correctas en 5 idiomas
- ✅ Captura de lead funcional (nombre, email, idioma, país)
- ✅ Derivación a humano cuando el caso lo requiere
- ✅ No da consejos legales/médicos (solo información general)
- ✅ Funciona en mobile

### Gate 5: Pre-deploy
- ✅ Security: no data leaks, form validation, HTTPS
- ✅ Performance: < 3s carga en Europa, LatAm, China
- ✅ Accessibility: WCAG 2.2 AA completo
- ✅ Visual: sin regresiones entre idiomas

### Gate 6: Post-deploy
- ✅ Smoke test en producción
- ✅ Chatbot funcional en producción
- ✅ Formularios envían correctamente
- ✅ Métricas básicas activas

---

## AGENTES CONVOCADOS

| Agente | Cuándo | Qué valida |
|--------|--------|------------|
| Accessibility Tester | Gate 2, 3, 5 | WCAG, keyboard, screen reader |
| Visual Tester | Gate 2, 3, 5 | Consistencia visual 5 idiomas |
| E2E Tester | Gate 3, 5, 6 | Flujos completos, smoke post-deploy |
| Security Tester | Gate 4, 5 | Forms, chatbot data, HTTPS |
| Performance Tester | Gate 5 | Tiempos carga internacional |
| Quality Strategy Lead | Todos los gates | GO/NO-GO por entregable |

---

## CHECKLIST RÁPIDO POR IDIOMA

Para cada idioma (ES, EN, PT, ZH, FR):

- ☐ Texto visible correcto (no caracteres rotos)
- ☐ Navegación funciona
- ☐ Formulario envía
- ☐ Chatbot responde en el idioma
- ☐ No hay texto sin traducir
- ☐ Responsive se ve bien
