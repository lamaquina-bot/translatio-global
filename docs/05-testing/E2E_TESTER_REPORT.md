# 🎭 E2E TESTER REPORT — Translatio Global

**Agente:** E2E Tester 🎭  
**Fecha:** 2026-04-17  
**Versión:** 1.0.0  

---

## Resumen Ejecutivo

Se diseñan casos de prueba E2E para los journeys críticos del usuario en los 5 idiomas.

---

## Journeys Críticos Identificados

### Journey 1: Visitante → Contacto (Happy Path)
**Prioridad:** Alta | **Idiomas:** 5

| Step | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | Navegar a `/` (o `/en/`, `/pt/`, `/zh/`, `/fr/`) | Página carga con idioma correcto |
| 2 | Scroll hasta sección de contacto | Formulario visible |
| 3 | Completar nombre, email, país | Campos aceptan input |
| 4 | Marcar checkbox GDPR | Checkbox marcado |
| 5 | Click "Enviar" | Formulario se envía, muestra mensaje de éxito |

**Casos de borde:**
- Enviar sin completar campos requeridos → validación HTML5 bloquea
- Email inválido → validación HTML5 bloquea
- Mensaje > 2000 chars → maxlength bloquea

### Journey 2: Visitante → Chatbot → Lead
**Prioridad:** Alta | **Idiomas:** 5

| Step | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | Click botón 💬 | Ventana de chat se abre |
| 2 | Ver greeting en idioma correcto | Mensaje localizado visible |
| 3 | Click quick reply (ej: "¿Qué es la subrogación?") | Mensaje aparece como user, bot responde |
| 4 | Bot escala → muestra lead form | Formulario nombre+email aparece |
| 5 | Completar y enviar | Mensaje de agradecimiento |

### Journey 3: Selector de Idioma
**Prioridad:** Media | **Idiomas:** 5×4 = 20 combinaciones

| Step | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | Hover sobre selector de idioma | Dropdown aparece |
| 2 | Click otro idioma | Navega a la versión correcta |
| 3 | Verificar `lang` attribute | `<html lang="xx">` correcto |
| 4 | Verificar contenido | Texto todo en el idioma seleccionado |

### Journey 4: Navegación Responsive (Mobile)
**Prioridad:** Media | **Breakpoints:** 320px, 375px, 768px

| Step | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | Abrir en viewport < 768px | Menú hamburger visible, nav desktop oculto |
| 2 | Click ☰ | Menú mobile se despliega |
| 3 | Click enlace nav | Navega correctamente |
| 4 | Verificar selector de idioma | Funciona en mobile (⚠️ defecto conocido) |

### Journey 5: Chatbot Toggle/Close
**Prioridad:** Baja

| Step | Acción | Resultado Esperado |
|------|--------|--------------------|
| 1 | Click toggle | Ventana se abre, `aria-expanded=true` |
| 2 | Click ✕ | Ventana se cierra, `aria-expanded=false` |
| 3 | Click toggle again | Ventana se reabre |
| 4 | Pulse animation | Se oculta después de primer click |

---

## Matriz de Pruebas por Idioma

| Journey | ES | EN | PT | ZH | FR |
|---------|----|----|----|----|----|
| Contacto Happy Path | ☐ | ☐ | ☐ | ☐ | ☐ |
| Contacto Validación | ☐ | ☐ | ☐ | ☐ | ☐ |
| Chatbot Greeting | ☐ | ☐ | ☐ | ☐ | ☐ |
| Chatbot Quick Reply | ☐ | ☐ | ☐ | ☐ | ☐ |
| Chatbot Lead Form | ☐ | ☐ | ☐ | ☐ | ☐ |
| Selector Idioma | ☐ | ☐ | ☐ | ☐ | ☐ |
| Nav Responsive | ☐ | ☐ | ☐ | ☐ | ☐ |

**Total de casos:** 7 journeys × 5 idiomas = 35 test cases mínimos

---

## Hallazgos

| ID | Severidad | Descripción |
|----|-----------|-------------|
| E2E-001 | Minor | Selector de idioma no funciona en mobile (hover-only) |
| E2E-002 | Minor | No hay CTA section intermedia en PT (falta sección "Ready to Take First Step") |
| E2E-003 | Minor | Nav links apuntan a páginas que no existen en el dist actual (solo hay index) |
| E2E-004 | Enhancement | Añadir tests automáticos con Playwright/Cypress |
| E2E-005 | Enhancement | Verificar scroll suave a secciones con anchor links |
| E2E-006 | Enhancement | Test de carga inicial (FCP < 2s) |
| E2E-007 | Enhancement | Test de interactividad (TTI < 3s) |
| E2E-008 | Enhancement | Test visual con Percy/Chromatic

---

## Scripts de Prueba Sugeridos (Playwright)

```javascript
// Ejemplo: Test de formulario de contacto EN
test('Contact form - English', async ({ page }) => {
  await page.goto('/en/');
  await page.click('a[href="#contact"]');
  await page.fill('#name', 'John Doe');
  await page.fill('#email', 'john@example.com');
  await page.fill('#country', 'United States');
  await page.check('#gdpr');
  await page.click('button[type="submit"]');
  await expect(page.locator('#form-success')).toBeVisible();
});
```

---

*Generado por MOLINO Testing Squad — 2026-04-17*
