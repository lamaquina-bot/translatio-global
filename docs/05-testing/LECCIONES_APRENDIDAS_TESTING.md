# 📋 Lecciones Aprendidas — Auditoría Testing Translatio Global

**Fecha:** 2026-04-17  
**Proyecto:** Translatio Global  
**Documento:** Lecciones Aprendidas del proceso de testing  
**Contexto:** 52 hallazgos (2 Críticos, 14 Mayores, 17 Menores, 19 Mejoras)

---

## Resumen Ejecutivo

El MOLINO Testing Squad encontró **2 defectos críticos** que deberían haberse detectado durante el desarrollo: una vulnerabilidad XSS en el chatbot y una violación GDPR por consentimiento simulado. Estos defectos pasaron a través de **9 agentes de desarrollo** y un **Quality Strategy Lead** que dio veredicto "GO WITH RISKS". Nadie auditó el código construido. Nadie ejecutó pruebas visuales. Este documento analiza por qué falló el proceso y cómo prevenirlo.

---

## 1. Análisis de Fallas del Proceso

### 1.1 XSS en Chatbot — ¿Cómo pasó desapercibido?

**Defecto:** `addMessage()` usa `innerHTML` con `${text}` sin sanitizar en las 5 páginas de idioma.

**Cadena de fallas:**

| Agente | Qué hizo | Qué debió hacer |
|--------|----------|-----------------|
| Backend Agent | Escribió JS del chatbot con `innerHTML` | Usar `textContent` o sanitización |
| Frontend Agent | Integró el chatbot sin revisar seguridad de inputs | Validar que todo input de usuario se sanitiza |
| Security Agent | Revisó docs de arquitectura, no el código construido | Auditar el HTML/JS final generado |
| Quality Strategy Lead | Evaluó planes, no código ejecutado | Exigir auditoría de código real |

**Root cause:** **Ningún agente revisó el código construido.** El Security Agent revisó la *arquitectura planificada*, no el *producto construido*. Es como revisar los planos de un edificio sin inspeccionar la construcción.

### 1.2 GDPR Hardcoded Consent — ¿Cómo se aprobó?

**Defecto:** El chatbot envía `gdpr_consent: true` hardcodeado sin que el usuario haya dado consentimiento real.

**Cadena de fallas:**

1. **Backend Agent** — Implementó el flag como constante en lugar de captura dinámica del consentimiento
2. **Frontend Agent** — No implementó UI de consentimiento funcional (banner/checkbox)
3. **Security Agent** — Verificó que el campo existía en el payload, no que el valor fuera legítimo
4. **Quality Strategy Lead** — Validó que el requisito GDPR estaba "documentado", no que funcionara

**Root cause:** **Confusión entre "el requisito existe en la documentación" y "el requisito funciona en el código".** Ningún agente verificó funcionalmente que el consentimiento fuera real y dinámico.

### 1.3 ¿Por qué no se planearon pruebas visuales?

**El Visual Tester existe en el squad pero nunca se activó.** Razones:

- El pipeline actual activa el Testing Squad **después** del desarrollo completo
- No se estableció baseline visual de referencia
- No se incluyeron criterios de aceptación visual en los run documents
- El Super Test Lead no tenía instrucciones para incluir pruebas visuales como obligatorias
- Se asumió que la revisión del UX/UI Agent durante desarrollo era suficiente

**Root cause:** **El Visual Tester se trata como opcional cuando debería ser obligatorio.** No hay gate de calidad visual en el pipeline.

---

## 2. Análisis del Flujo de Desarrollo

### 2.1 Flujo Actual y Puntos de Falla

```
Discovery → Requirements → Architect → UX/UI → Backend → Frontend → Integration → DevOps → Security → [Quality Strategy Lead] → Testing Squad
                                                                                      ↑                                              ↑
                                                                            Código vulnerable escrito              Se audita DESPUÉS, no durante
```

**Problemas estructurales:**

| Problema | Impacto |
|----------|---------|
| Desarrollo secuencial sin checkpoints de testing | Defectos se acumulan y se propagan |
| Security Agent revisa docs, no código | Vulnerabilidades en código no se detectan |
| Quality Strategy Lead evalúa planes | Brecha entre calidad planificada y calidad real |
| Testing Squad se activa al final | Corrección de defectos críticos es costosa |

### 2.2 La Brecha: Calidad de Planificación vs. Calidad de Ejecución

El Quality Strategy Lead dio **"GO WITH RISKS"** basándose en:

- ✅ Documentos de requisitos completos
- ✅ Arquitectura documentada
- ✅ Plan de seguridad descrito
- ✅ Run documents de cada agente

Pero **NO verificó:**

- ❌ Código HTML/JS real generado
- ❌ Funcionamiento real del chatbot
- ❌ Comportamiento en mobile
- ❌ Headers de seguridad implementados
- ❌ Consentimiento GDPR funcional

**Esto es como aprobar un restaurante por el menú sin haber probado la comida.**

---

## 3. Fallas Específicas por Agente

### 3.1 Backend Agent

| Aspecto | Detalle |
|---------|---------|
| **Qué escribió** | JS del chatbot usando `innerHTML` con interpolación directa de texto |
| **Qué falló** | No sanitizó input de usuario; usó patrón inseguro por defecto |
| **Por qué** | Su KNOWLEDGE no incluye patrones obligatorios de XSS prevention |
| **Impacto** | Vulnerabilidad XSS en 5 páginas (todos los idiomas) |
| **Severidad** | 🔴 Crítica |

**Veredicto:** El Backend Agent no debería escribir JS frontend sin patrones de seguridad validados.

### 3.2 Frontend Agent

| Aspecto | Detalle |
|---------|---------|
| **Qué omitió** | Sanitización de inputs, testing mobile del language selector |
| **Qué falló** | Asumió que el código del Backend Agent era seguro; no probó touch events |
| **Por qué** | No hay gate de "input sanitization check" en su flujo |
| **Impacto** | XSS propagado + selector de idioma roto en mobile |
| **Severidad** | 🔴 Crítica (XSS) + 🟠 Mayor (mobile) |

**Veredicto:** El Frontend Agent debe tratar todo input como no confiable por defecto.

### 3.3 Security Agent

| Aspecto | Detalle |
|---------|---------|
| **Qué revisó** | Documentos de arquitectura y diseño |
| **Qué NO revisó** | El código HTML/JS real construido |
| **Por qué** | Sus INSTRUCTIONS lo orientan a revisar arquitectura, no código ejecutado |
| **Impacto** | XSS y GDPR hardcoded pasaron inadvertidos |
| **Severidad** | 🔴 Crítica (falla de oversight) |

**Veredicto:** La mayor falla del pipeline. El Security Agent revisó los planos pero no la obra.

### 3.4 Quality Strategy Lead

| Aspecto | Detalle |
|---------|---------|
| **Qué evaluó** | Run documents, planes de arquitectura, docs de requisitos |
| **Qué NO evaluó** | Código real, funcionamiento real, pruebas ejecutadas |
| **Veredicto dado** | "GO WITH RISKS" |
| **Veredicto correcto** | "NO GO" — defectos críticos presentes |
| **Severidad** | 🔴 Crítica (falla de gate) |

**Veredicto:** El gate de calidad evaluó documentación, no producto. Insuficiente.

### 3.5 Super Test Lead

| Aspecto | Detalle |
|---------|---------|
| **Estado** | No fue activado durante desarrollo |
| **Cuándo se activó** | Después del GO del Quality Strategy Lead |
| **Qué encontró** | 52 hallazgos incluyendo 2 críticos |
| **Severidad** | 🟠 Mayor (activación tardía) |

**Veredicto:** Si se hubiera activado durante el desarrollo, los defectos se habrían encontrado antes.

---

## 4. ¿Por Qué No Se Planearon Pruebas Visuales?

### 4.1 Estado del Visual Tester

| Dimensión | Estado Actual | Estado Deseado |
|-----------|---------------|----------------|
| Agente disponible | ✅ Existe en el squad | ✅ |
| Activación automática | ❌ No se incluye por defecto | ✅ Siempre activo |
| Baseline visual | ❌ No se estableció | ✅ Baseline por idioma |
| Testing responsive | ❌ No se planificó | ✅ Mobile + Desktop obligatorio |
| Consistencia cross-idioma | ❌ No se verificó | ✅ Screenshot comparison |
| Gate de calidad visual | ❌ No existe | ✅ Aprobación visual requerida |

### 4.2 Razones de la Omisión

1. **El pipeline no exige pruebas visuales** — No hay gate que diga "¿se ejecutó Visual Tester?"
2. **Se asumió cobertura del UX/UI Agent** — Pero ese agent diseñó, no verificó visualmente el resultado
3. **Sin baseline, no hay comparación posible** — Nadie estableció "esto es lo correcto"
4. **Mobile se trató como nice-to-have** — Cuando debería ser first-class citizen
5. **No hay INSTRUCTIONS para el Super Test Lead** que incluya pruebas visuales como obligatorias

### 4.3 Impacto de No Tener Pruebas Visuales

- Selector de idioma roto en mobile → **No detectado**
- Noto Sans SC (150KB) cargado en todas las páginas → **No detectado**
- Inconsistencias visuales entre idiomas → **No detectado**
- Meta descriptions sin traducir → **No detectado visualmente**

---

## 5. Lecciones por Categoría

### 5.1 Proceso

| # | Lección | Acción |
|---|---------|--------|
| P1 | Los agentes de desarrollo no deben auto-revisar su código para seguridad | Security Agent debe auditar código construido |
| P2 | El Testing Squad debe activarse EN PARALELO con el desarrollo, no después | Pipeline modificado (ver sección 6) |
| P3 | Quality gates en planificación ≠ quality gates en código ejecutado | Quality Strategy Lead debe auditar código real |
| P4 | Documentos de run no son evidencia de calidad funcional | Exigir pruebas ejecutadas, no solo planes |
| P5 | Un "GO WITH RISKS" sin auditoría de código es un "GO A CIEGAS" | Gate de código obligatorio antes de GO |

### 5.2 Técnico

| # | Lección | Acción |
|---|---------|--------|
| T1 | `innerHTML` debe estar prohibido por defecto en patrones del Frontend Agent | Agregar a KNOWLEDGE como anti-patrón |
| T2 | Consentimiento GDPR debe verificarse funcionalmente, no solo documentarse | Agregar test funcional obligatorio |
| T3 | Mobile-first testing es innegociable | Testing responsive obligatorio en pipeline |
| T4 | Headers de seguridad (CSP, CSRF) deben ser checklist obligatorio | Security Agent debe verificar con curl, no con docs |
| T5 | Fonts y assets pesados deben verificarse por idioma | Performance Tester debe validar carga por página |

### 5.3 Organizacional

| # | Lección | Acción |
|---|---------|--------|
| O1 | El Security Agent debe revisar OUTPUT construido, no solo arquitectura | Cambiar INSTRUCTIONS del Security Agent |
| O2 | Visual testing debe ser parte del pipeline estándar, no opcional | Gate visual obligatorio |
| O3 | El Super Test Lead debe activarse desde fase de desarrollo | Pipeline paralelo |
| O4 | La separación entre "quién construye" y "quién verifica" es fundamental | No self-review |
| O5 | El Quality Strategy Lead necesita poder de veto sobre código | NO GO posible en cualquier fase |

---

## 6. Plan de Acción Preventivo

### 6.1 Pipeline Propuesto (Modificado)

```
Fase 1-4: Discovery → Requirements → Architect → UX/UI
                                                    ↓
                                          [Design Review Gate]
                                                    ↓
Fase 5-7: Backend → Frontend → Integration
              ↓           ↓          ↓
         ┌────┴───────────┴──────────┴────┐
         │   TESTING SQUAD (PARALELO)      │
         │   • Security Tester             │
         │   • Visual Tester               │
         │   • Unit Tester                 │
         └────────────┬───────────────────┘
                      ↓
              [Code Quality Gate] ← Security Agent audita CÓDIGO REAL
                      ↓
Fase 8-9: DevOps → Security Review Final
                      ↓
              [Quality Strategy Lead] ← Evalúa CÓDIGO + DOCS
                      ↓
              GO / NO GO / GO WITH CONDITIONS
```

### 6.2 Nuevos Quality Gates

| Gate | Responsable | Criterio |
|------|-------------|----------|
| **Design Review Gate** | UX/UI Agent + Visual Tester | Baseline visual aprobado |
| **Code Quality Gate** | Security Agent | XSS scan, input sanitization, headers verificados en código real |
| **Functional Gate** | Super Test Lead | Tests unitarios + E2E pasando |
| **Visual Consistency Gate** | Visual Tester | Screenshots coinciden con baseline en 5 idiomas |
| **Final Quality Gate** | Quality Strategy Lead | Todos los gates anteriores + auditoría de código |

### 6.3 Activación del Testing Squad

| Tester | Activación Actual | Activación Propuesta |
|--------|-------------------|----------------------|
| 🧪 Unit Tester | Post-desarrollo | Durante Backend + Frontend |
| 🔗 Integration Tester | Post-desarrollo | Durante Integration |
| 🎭 E2E Tester | Post-desarrollo | Durante Integration |
| ⚡ Performance Tester | Post-desarrollo | Durante Frontend |
| 🛡️ Security Tester | Post-desarrollo | **Durante Backend (paralelo)** |
| ⚡ Chaos Tester | Post-desarrollo | Post-Integration |
| ♿ Accessibility Tester | Post-desarrollo | Durante Frontend |
| 🎨 Visual Tester | **Nunca activado** | **Durante Frontend + Integration** |

---

## 7. Actualizaciones Requeridas a Agentes

### 7.1 Backend Agent — KNOWLEDGE

**Agregar patrones obligatorios:**

```markdown
## SEGURIDAD OBLIGATORIA

### Manipulación del DOM
- ❌ PROHIBIDO: `element.innerHTML = userInput`
- ✅ OBLIGATORIO: `element.textContent = userInput`
- ✅ ALTERNATIVA: Sanitización con librería (DOMPurify) antes de innerHTML

### Consentimiento GDPR
- ❌ PROHIBIDO: `gdpr_consent: true` hardcodeado
- ✅ OBLIGATORIO: Captura dinámica del consentimiento del usuario
- ✅ VERIFICACIÓN: El valor debe provenir de una acción explícita del usuario

### Validación de Input
- Todo input de usuario debe ser sanitizado antes de renderizarse
- Nunca confiar en datos del cliente
```

### 7.2 Frontend Agent — KNOWLEDGE

**Agregar:**

```markdown
## PATRONES OBLIGATORIOS

### Input Sanitization
- Todo texto de usuario → sanitizar ANTES de inyectar en DOM
- Usar textContent por defecto, innerHTML solo con sanitización

### Mobile-First Testing
- Verificar TODOS los interactivos con touch events
- CSS :hover DEBE tener equivalente :active o touch
- El language selector debe funcionar con click/touch, no solo hover

### Cross-Language Consistency
- Verificar que meta descriptions están traducidas en todos los idiomas
- Verificar que assets pesados (fonts) solo se cargan donde son necesarios
```

### 7.3 Security Agent — INSTRUCTIONS

**Cambios críticos:**

```markdown
## FLUJO DE AUDITORÍA (ACTUALIZADO)

### PASO 1: Revisión de Arquitectura (sin cambios)
### PASO 2: AUDITORÍA DE CÓDIGO CONSTRUIDO (NUEVO - OBLIGATORIO)

El Security Agent DEBE:
1. Leer los archivos HTML/JS generados por Backend y Frontend agents
2. Buscar patrones inseguros: innerHTML, eval(), document.write()
3. Verificar headers de seguridad con curl/wget al sitio desplegado
4. Verificar consentimiento GDPR funcional (no hardcodeado)
5. Verificar CSRF tokens en formularios
6. Generar reporte de código real, no solo de docs

### PASO 3: Verificación Funcional
- Ejecutar el sitio y verificar comportamiento real
- Probar inputs maliciosos (XSS payloads básicos)
- Verificar que GDPR consent funciona end-to-end
```

### 7.4 Quality Strategy Lead — INSTRUCTIONS

**Cambios:**

```markdown
## EVALUACIÓN DE CALIDAD (ACTUALIZADO)

### ANTES de dar veredicto GO/NO GO:
1. Revisar run documents ✅ (existente)
2. **Revisar código construido** ← NUEVO
3. **Verificar que Security Agent auditó código real** ← NUEVO
4. **Verificar que pruebas visuales se ejecutaron** ← NUEVO
5. **Verificar resultados de testing funcional** ← NUEVO

### Veredictos:
- "GO": Todo pasa, código auditado, tests verdes
- "GO WITH RISKS": Solo si los riesgos son conocidos Y aceptados explícitamente
- "NO GO": Si hay defectos críticos sin resolver
- "GO A CIEGAS" ← ELIMINAR esta posibilidad
```

### 7.5 Super Test Lead — INSTRUCTIONS

**Cambios:**

```markdown
## ACTIVACIÓN (ACTUALIZADO)

### ANTES: Se activaba después del Quality Strategy Lead
### AHORA: Se activa desde Fase 5 (Backend)

El Super Test Lead debe:
1. Recibir notificación cuando Backend Agent termina
2. Activar Security Tester y Unit Tester en paralelo con Frontend
3. Activar Visual Tester cuando haya UI construida
4. Activar E2E e Integration Tester durante Integration
5. Reportar hallazgos EN TIEMPO REAL, no al final
```

### 7.6 Visual Tester — INSTRUCTIONS

**Cambios:**

```markdown
## PRUEBAS VISUALES OBLIGATORIAS

### Al activarse (ahora automático):
1. Establecer baseline visual para cada idioma
2. Verificar responsive: desktop + tablet + mobile
3. Verificar consistencia cross-idioma
4. Verificar que assets solo se cargan donde son necesarios
5. Reportar desviaciones del baseline

### Baseline incluye:
- Layout principal
- Navegación y selector de idioma
- Chatbot visual
- Formularios
- Footer y links
- Comportamiento en mobile (touch)
```

---

## 8. Conclusión

El proyecto Translatio Global reveló una falla sistémica: **el pipeline MOLINO evalúa planes pero no productos.** Nueve agentes de desarrollo construyeron código con vulnerabilidades críticas, un Quality Strategy Lead aprobó basándose en documentos, y el Testing Squad (cuando finalmente se activó) encontró 52 problemas.

Las dos lecciones fundamentales son:

1. **La seguridad se verifica en código, no en documentación.** El Security Agent debe auditar lo construido, no lo planificado.
2. **El testing es paralelo al desarrollo, no secuencial.** El Testing Squad debe activarse durante el desarrollo, no después.

Implementar los cambios propuestos en este documento reduciría significativamente la probabilidad de que defectos críticos lleguen a producción.

---

*Documento generado por MOLINO Testing Squad — 2026-04-17*
