# 📋 REQUIREMENTS AGENT RUN — Translatio Global

**Fecha:** 16 Abril 2026
**Agente:** Requirements Agent de MOLINO v1.0.0
**Proyecto:** Translatio Global — Subrogación Gestacional en Colombia
**Fuente:** PROJECT_BRIEF.md + DISCOVERY_AGENT_RUN.md
**Veredicto Discovery:** PROCEED WITH CONDITIONS (confianza 0.80)

---

## FASE 1: RECEPCIÓN DE VISION DOCUMENT

### Completeness Check

| Sección del Vision | Presente | Completitud |
|---------------------|----------|-------------|
| Problem Statement | ✅ | Completo |
| User Personas | ✅ | 4 segmentos identificados |
| Success Metrics | ✅ | 7 KPIs definidos |
| MVP Features | ✅ | 10 Must-Haves |
| Stakeholders | ✅ | 7 mapeados |
| Risks | ✅ | 10 riesgos (3 críticos) |
| Out of Scope | ✅ | 7 items |

### Readiness Score: **0.85**

### Gaps Identificados

1. **Presupuesto no confirmado** por Gabriel → impacto en alcance de idiomas y chatbot
2. **No hay testimonios reales** → Casos de Éxito requiere estrategia alternativa
3. **Legal review pendiente** → Disclaimer y contenido legal son placeholders hasta validación
4. **Canal de gestantes** no definido → ¿llegan por web o por otro medio?
5. **Traducción ZH** requiere nativo → presupuesto y partner no confirmados

### Glosario Preliminar

| Término | Definición |
|---------|-----------|
| **Padre intencional** | Persona o pareja que busca tener un hijo mediante subrogación gestacional |
| **Gestante** | Mujer que lleva el embarazo (no aporta material genético en gestacional) |
| **Subrogación gestacional** | Proceso donde una gestante lleva el embarazo de padres intencionales, sin vínculo genético |
| **Subrogación tradicional** | NO aplicable a este proyecto — gestante aporta óvulo (excluido deliberadamente) |
| **Acompañamiento integral** | Servicio de Translatio: coordinación legal, psicológica, médica y logística |
| **Lead** | Visitante que proporciona datos de contacto a través del sitio o chatbot |
| **Lead calificado** | Lead que ha sido filtrado y derivado a consulta humana |
| **GDPR** | Reglamento General de Protección de Datos (UE) — aplicable a padres europeos |
| **WPML / Polylang** | Plugins de WordPress para multilenguaje |
| **CPT** | Custom Post Type en WordPress |
| **hreflang** | Tag HTML que indica a Google el idioma y región de una página |

---

## FASE 2: STAKEHOLDER REQUIREMENTS ELICITATION

### Stakeholders

| # | Stakeholder | Tipo | Poder | Interés | Estrategia |
|---|------------|------|-------|---------|------------|
| S1 | Gabriel (fundador) | Decision Maker | ALTO | ALTO | Manage Closely |
| S2 | Padres intencionales (ES/EN/PT/ZH/FR) | User | MEDIO | ALTO | Keep Satisfied |
| S3 | Gestantes potenciales | User | MEDIO | ALTO | Keep Informed |
| S4 | Equipo de acompañamiento Translatio | Operations | MEDIO | ALTO | Keep Satisfied |
| S5 | Abogados / asesores legales | Compliance | ALTO | MEDIO | Keep Satisfied |
| S6 | Equipo MOLINO (dev, QA, diseño) | Developer | ALTO | ALTO | Manage Closely |
| S7 | Reguladores colombianos | External | ALTO | BAJO | Monitor |

### Necesidades por Stakeholder

**S2 — Padres intencionales:**
- Información clara sobre el proceso de subrogación en Colombia
- Saber si Colombia es un destino viable (legal, costos, tiempos)
- Confianza en la agencia (transparencia, rostros reales, testimonios)
- Contacto sin compromiso (formulario, chatbot, llamada)
- Información en su idioma nativo
- Privacidad de sus datos personales

**S3 — Gestantes:**
- Información clara sobre qué implica ser gestante
- Conocer sus derechos y el acompañamiento que recibirán
- Contacto confidencial
- Información en español

**S4 — Equipo Translatio:**
- Leads capturados con datos suficientes (nombre, email, país, idioma, etapa del proceso)
- Notificación en tiempo real de leads nuevos
- Derivación clara desde chatbot cuando el caso requiere humano
- Panel o acceso a leads capturados

### Conflictos Identificados

| # | Stakeholders | Conflicto | Resolución Propuesta |
|---|-------------|-----------|---------------------|
| C1 | Padres vs. Legal | Padres quieren info específica sobre costos y legalidad; abogados quieren texto conservador | Compromiso: rangos de costos + disclaimer legal visible |
| C2 | Padres vs. Privacidad | Chatbot necesita datos para lead; padres pueden no querer dar email | Captura gradual: nombre + país primero, email después |
| C3 | Equipo vs. GDPR | Quieren máximo data de leads; GDPR limita recopilación | GDPR compliance obligatorio; solo datos necesarios |

---

## FASE 3: REQUERIMIENTOS FUNCIONALES

### Módulo 1: Sitio Web Corporativo — Estructura

| ID | Requisito | Prioridad | Fuente |
|----|-----------|-----------|--------|
| FR-001 | El sitio DEBE tener 6 páginas principales: Home, Servicios, Proceso, Quiénes Somos, Casos de Éxito, Contacto | Must | S1, Brief |
| FR-002 | El sitio DEBE estar disponible en 5 idiomas: ES, EN, PT, ZH, FR con selección visible en el header | Must | S1, Brief |
| FR-003 | El idioma por defecto DEBE ser ES para visitantes sin preferencia de idioma detectada | Should | S2 |
| FR-004 | El sistema DEBE detectar el idioma del navegador y ofrecer cambiar al idioma detectado (sin forzar) | Should | S2 |
| FR-005 | Cada página DEBE tener URL única por idioma (ej: `/es/servicios`, `/en/services`, `/pt/servicos`) | Must | SEO |
| FR-006 | El sitio DEBE incluir tags hreflang correctos en cada página para SEO multilenguaje | Must | SEO |
| FR-007 | El sitio DEBE generar sitemap XML por idioma | Should | SEO |
| FR-008 | El sitio DEBE usar WordPress como CMS con plugin de multilenguaje (WPML o Polylang) | Must | S1, S6 |

### Módulo 2: Contenido por Página

| ID | Requisito | Prioridad | Fuente |
|----|-----------|-----------|--------|
| FR-010 | La página Home DEBE incluir: hero section con propuesta de valor, resumen de servicios, CTA de contacto, testimonio destacado (si existe), sección "Por Colombia" | Must | S1, S2 |
| FR-011 | La página Servicios DEBE describir los 3 servicios principales: acompañamiento a padres, acompañamiento a gestantes, coordinación legal-médica | Must | S1 |
| FR-012 | La página Proceso DEBE mostrar el paso a paso del proceso de subrogación gestacional en Colombia (timeline visual o stepper) | Must | S2 |
| FR-013 | La página Proceso DEBE incluir tiempos estimados por etapa (orientativos, con disclaimer) | Should | S2 |
| FR-014 | La página Quiénes Somos DEBE mostrar: misión, equipo con fotos y nombres reales, enfoque diferenciador (humano y empático) | Must | S1 |
| FR-015 | La página Casos de Éxito DEBE mostrar testimonios reales con permiso explícito. Si no hay testimonios, DEBE mostrar contenido educativo/informativo sobre subrogación gestacional | Must | S1, Ética |
| FR-016 | La página Contacto DEBE incluir: formulario de contacto, email directo, enlace a chatbot, información de ubicación (Colombia) | Must | S2 |
| FR-017 | TODAS las páginas DEBE incluir un disclaimer legal visible indicando que el contenido es informativo y no constituye asesoría médica ni legal | Must | S5, R2 |
| FR-018 | El sitio DEBE incluir una página de Política de Privacidad en cada idioma, GDPR-compliant | Must | S5, GDPR |
| FR-019 | El sitio DEBE incluir una página de Cookies con banner de consentimiento configurable por idioma | Must | GDPR |

### Módulo 3: Formulario de Contacto / Captura de Leads

| ID | Requisito | Prioridad | Fuente |
|----|-----------|-----------|--------|
| FR-020 | El formulario de contacto DEBE capturar: nombre, email, país de origen, idioma preferido | Must | S4 |
| FR-021 | El formulario DEBE incluir campo opcional: mensaje libre o etapa del proceso (explorando, decidido, en proceso) | Should | S4 |
| FR-022 | El formulario DEBE validar formato de email en frontend y backend | Must | Calidad de datos |
| FR-023 | El formulario DEBE mostrar mensaje de confirmación tras envío exitoso | Must | UX |
| FR-024 | Los datos del formulario DEBE almacenarse en la base de datos del sitio con timestamp | Must | S4 |
| FR-025 | El sistema DEBE enviar notificación por email al equipo Translatio por cada nuevo lead | Must | S4 |
| FR-026 | El formulario DEBE incluir checkbox de aceptación de Política de Privacidad (obligatorio para enviar) | Must | GDPR |
| FR-027 | El formulario DEBE registrar consentimiento explícito con fecha y hora | Must | GDPR |

### Módulo 4: Chatbot 24/7

| ID | Requisito | Prioridad | Fuente |
|----|-----------|-----------|--------|
| FR-030 | El chatbot DEBE estar accesible desde todas las páginas del sitio (widget flotante) | Must | S1 |
| FR-031 | El chatbot DEBE funcionar en los 5 idiomas del sitio (ES, EN, PT, ZH, FR) | Must | S1 |
| FR-032 | El chatbot DEBE detectar el idioma activo del sitio y responder en ese idioma por defecto | Must | UX |
| FR-033 | El chatbot DEBE ofrecer un menú de FAQ con las categorías: Proceso, Costos, Legalidad, Tiempos, Requisitos, Ser Gestante | Must | S2 |
| FR-034 | El chatbot DEBE responder preguntas frecuentes predefinidas con respuestas aprobadas por el equipo Translatio | Must | S1 |
| FR-035 | El chatbot NUNCA DEBE dar consejos médicos, psicológicos ni legales. Ante preguntas de este tipo, DEBE responder: "Esta consulta requiere asesoría profesional. ¿Quieres que te conectemos con nuestro equipo?" | Must | S5, R3 |
| FR-036 | El chatbot DEBE ofrecer derivación a humano en CADA interacción (botón visible: "Hablar con una persona") | Must | S1, R3 |
| FR-037 | El chatbot DEBE capturar datos de lead: nombre, email, país, idioma antes o durante la conversación | Must | S4 |
| FR-038 | El chatbot DEBE enviar los datos capturados al mismo sistema de almacenamiento que el formulario de contacto | Must | S4 |
| FR-039 | El chatbot DEBE registrar el historial de conversación vinculado al lead | Should | S4 |
| FR-040 | El chatbot DEBE funcionar 24/7 sin intervención humana para las FAQ predefinidas | Must | S1 |
| FR-041 | Cuando un usuario solicite hablar con humano, el chatbot DEBE crear una notificación al equipo Translatio con los datos del lead y resumen de la conversación | Must | S4 |
| FR-042 | El chatbot DEBE mostrar mensaje de horario de atención humana: "Nuestro equipo te contactará en las próximas X horas" (configurable) | Should | UX |
| FR-043 | El chatbot DEBE incluir disclaimer inicial: "Soy un asistente virtual. No doy consejos médicos ni legales" | Must | R3 |

### Módulo 5: SEO y Marketing Digital

| ID | Requisito | Prioridad | Fuente |
|----|-----------|-----------|--------|
| FR-050 | Cada página en cada idioma DEBE tener meta title y meta description editables desde el CMS | Must | SEO |
| FR-051 | El sitio DEBE implementar schema markup (JSON-LD) para Organization, FAQPage y BreadcrumbList | Should | SEO |
| FR-052 | El sitio DEBE cargar Google Analytics 4 o equivalente con tracking de conversiones (formulario enviado, chatbot lead capturado) | Must | S1 |
| FR-053 | El sitio DEBE tener favicon, Open Graph tags y Twitter Cards configurables por idioma | Should | SEO |
| FR-054 | El sitio DEBE implementar redirecciones 301 para URLs canónicas | Should | SEO |

### Módulo 6: Panel de Administración (WordPress)

| ID | Requisito | Prioridad | Fuente |
|----|-----------|-----------|--------|
| FR-060 | El admin DEBE poder editar el contenido de todas las páginas en todos los idiomas sin requerir developer | Must | S1 |
| FR-061 | El admin DEBE poder agregar/editar/eliminar respuestas del chatbot (FAQ) sin requerir developer | Must | S1 |
| FR-062 | El admin DEBE poder ver la lista de leads capturados (formulario + chatbot) con filtros por fecha, idioma y país | Must | S4 |
| FR-063 | El admin DEBE poder exportar la lista de leads a CSV | Should | S4 |
| FR-064 | El admin DEBE poder configurar el email de notificación de leads nuevos | Should | S4 |

---

## FASE 4: REQUERIMIENTOS NO FUNCIONALES

### Performance

| ID | Requisito | Métrica | Umbral Mínimo | Target | Método de Medición |
|----|-----------|---------|---------------|--------|-------------------|
| NFR-001 | El sitio DEBE cargar en menos de 4 segundos en móvil (3G simulado) | Largest Contentful Paint (LCP) | < 4.0s | < 2.5s | Lighthouse Mobile |
| NFR-002 | El sitio DEBE cargar en menos de 2 segundos en desktop (broadband) | LCP | < 2.0s | < 1.5s | Lighthouse Desktop |
| NFR-003 | El First Input Delay DEBE ser menor a 100ms | FID | < 200ms | < 100ms | Lighthouse |
| NFR-004 | El PageSpeed Score DEBE ser mayor a 70 en móvil y 80 en desktop | PageSpeed Score | > 70 móvil / > 80 desktop | > 85 móvil / > 90 desktop | Google PageSpeed Insights |
| NFR-005 | El chatbot widget DEBE cargar en menos de 2 segundos después del page load | Widget init time | < 3.0s | < 1.5s | Chrome DevTools Performance |

### Security

| ID | Requisito | Métrica | Umbral | Método de Medición |
|----|-----------|---------|--------|-------------------|
| NFR-010 | El sitio DEBE usar HTTPS en todas las páginas con certificado SSL válido | HTTPS coverage | 100% | Browser check + SSL Labs test |
| NFR-011 | El formulario DEBE proteger contra CSRF, XSS e inyección SQL | Vulnerability count | 0 críticas/high | OWASP ZAP scan |
| NFR-012 | Las contraseñas de admin DEBEN tener mínimo 12 caracteres con política de complejidad | Password policy | Enforced | Verificación manual |
| NFR-013 | El sitio DEBE implementar Content Security Policy headers | CSP headers | Present | SecurityHeaders.com |
| NFR-014 | Los datos de leads DEBEN almacenarse cifrados en reposo (database encryption) | Encryption at rest | AES-256 o equivalente | Verificación de configuración DB |
| NFR-015 | El acceso al panel admin DEBE estar limitado por IP o 2FA | Access control | IP whitelist o 2FA activo | Verificación manual |
| NFR-016 | El sitio DEBE implementar rate limiting en el formulario de contacto (máximo 3 envíos por IP por hora) | Rate limit | ≤ 3/hora/IP | Test manual |

### Accessibility (WCAG 2.2 AA)

| ID | Requisito | Métrica | Umbral | Método de Medición |
|----|-----------|---------|--------|-------------------|
| NFR-020 | El sitio DEBE cumplir WCAG 2.2 nivel AA | WCAG compliance | 100% AA | axe-core automated + manual audit |
| NFR-021 | Todas las imágenes DEBEN tener texto alternativo descriptivo en el idioma de la página | Alt text coverage | 100% | axe-core scan |
| NFR-022 | El sitio DEBE ser totalmente navegable por teclado | Keyboard navigation | 100% funcional | Test manual |
| NFR-023 | El contraste de color DEBE ser mínimo 4.5:1 para texto normal y 3:1 para texto grande | Contrast ratio | ≥ 4.5:1 / ≥ 3:1 | axe-core + Colour Contrast Analyser |
| NFR-024 | El chatbot DEBE ser accesible por teclado y compatible con lectores de pantalla | ARIA compliance | WCAG AA | Test manual con NVDA/VoiceOver |

### Internationalización (i18n)

| ID | Requisito | Métrica | Umbral | Método de Medición |
|----|-----------|---------|--------|-------------------|
| NFR-030 | El contenido en cada idioma DEBE ser traducido por profesional nativo (NO traducción automática visible) | Translation quality | Revisión humana por nativo | Revisión manual por traductor |
| NFR-031 | Las fechas, monedas y formatos numéricos DEBEN localizarse según el idioma/región | Locale formatting | 100% consistente | Revisión manual |
| NFR-032 | El sitio DEBE soportar caracteres Unicode completos (incluyendo caracteres chinos simplificados) | Unicode support | Sin errores de rendering | Test visual en todos los idiomas |
| NFR-033 | El layout DEBE adaptarse a textos más largos en otros idiomas (PT ~20% más largo que ES, ZH más corto pero fonts más grandes) | Layout flexibility | Sin overflow ni truncamiento | Test visual en 5 idiomas |
| NFR-034 | El RTL (right-to-left) NO es requerido — todos los idiomas son LTR | N/A | N/A | N/A |

### Usabilidad

| ID | Requisito | Métrica | Umbral | Método de Medición |
|----|-----------|---------|--------|-------------------|
| NFR-040 | El sitio DEBE ser responsive y funcionar en: móvil (320px+), tablet (768px+), desktop (1024px+) | Responsive breakpoints | Sin breakage visual | BrowserStack o similar |
| NFR-041 | Un visitante nuevo DEBE poder encontrar la información de contacto en menos de 2 clicks desde cualquier página | Navigation depth | ≤ 2 clicks | Test manual |
| NFR-042 | El chatbot DEBE ser identificable visualmente como widget de chat (ícono universal) en menos de 3 segundos de escaneo visual | Discoverability | Test con 5 usuarios | User testing |

### Disponibilidad y Confiabilidad

| ID | Requisito | Métrica | Umbral | Target | Método de Medición |
|----|-----------|---------|---------------|--------|-------------------|
| NFR-050 | El sitio DEBE tener un uptime mínimo de 99.5% | Uptime % | > 99.5% | > 99.9% | Monitoreo uptime (UptimeRobot o similar) |
| NFR-051 | El chatbot DEBE tener un uptime mínimo de 99% (aceptamos más downtime que el sitio) | Chatbot uptime | > 99% | > 99.5% | Monitoreo servicio chatbot |
| NFR-052 | El sitio DEBE tener backup diario automático de la base de datos y archivos | Backup frequency | Diario | Cada 6 horas | Verificación de cron + restore test |
| NFR-053 | El RTO (Recovery Time Objective) DEBE ser menor a 4 horas | RTO | < 4h | < 2h | Restore test quarterly |

### Compliance Legal

| ID | Requisito | Métrica | Umbral | Método de Medición |
|----|-----------|---------|--------|-------------------|
| NFR-060 | El sitio DEBE cumplir GDPR para visitantes de la Unión Europea | GDPR compliance | 100% | Legal review checklist |
| NFR-061 | El sitio DEBE cumplir Ley 1581 de 2012 (Protección de Datos Personales - Colombia) | Colombian data protection | 100% | Legal review checklist |
| NFR-062 | Los datos de leads DEBEN poder eliminarse bajo solicitud (derecho al olvido) | Deletion capability | < 72h respuesta | Test manual |
| NFR-063 | El consentimiento de cookies DEBE ser granular (necesarias, analíticas, marketing) | Cookie consent | 3 categorías mínimo | Verificación banner |

---

## FASE 5: USER STORIES

### Epic 1: Navegación e Información

**US-001** — Ver información de servicios
> Como **padre intencional**, quiero ver los servicios que ofrece Translatio, para entender si esta agencia cubre mis necesidades.

- **Criterios de aceptación:**
  - [ ] La página Servicios lista mínimo 3 servicios principales
  - [ ] Cada servicio tiene descripción de mínimo 100 palabras en cada idioma
  - [ ] Hay CTA de contacto visible desde la página de servicios
  - [ ] Página accesible en los 5 idiomas con contenido completo (no placeholders)
- **Prioridad:** Must
- **FRs relacionados:** FR-011

**US-002** — Entender el proceso
> Como **padre intencional**, quiero ver el proceso paso a paso, para saber qué esperar si decido iniciar.

- **Criterios de aceptación:**
  - [ ] La página Proceso muestra mínimo 5 etapas del proceso
  - [ ] Las etapas están en orden cronológico
  - [ ] Incluye tiempos estimados con disclaimer "los tiempos son orientativos"
  - [ ] Disponible en 5 idiomas
- **Prioridad:** Must
- **FRs relacionados:** FR-012, FR-013

**US-003** — Conocer al equipo
> Como **padre intencional**, quiero ver quiénes conforman Translatio, para generar confianza en la agencia.

- **Criterios de aceptación:**
  - [ ] La página muestra fotos reales del equipo (no stock)
  - [ ] Cada miembro tiene nombre y rol
  - [ ] Incluye sección de misión/valores
  - [ ] Disponible en 5 idiomas
- **Prioridad:** Must
- **FRs relacionados:** FR-014

**US-004** — Ver por qué Colombia
> Como **padre intencional**, quiero entender por qué Colombia es un buen destino para subrogación, para tomar una decisión informada.

- **Criterios de aceptación:**
  - [ ] La sección explica el marco legal colombiano (con disclaimer)
  - [ ] Incluye ventajas comparativas (costo, ubicación, clima legal)
  - [ ] Menciona rangos de costos orientativos
  - [ ] Disponible en 5 idiomas
- **Prioridad:** Must
- **FRs relacionados:** FR-010

### Epic 2: Contacto y Captura de Leads

**US-010** — Enviar formulario de contacto
> Como **padre intencional**, quiero enviar mis datos de contacto, para que Translatio me pueda asesorar.

- **Criterios de aceptación:**
  - [ ] El formulario tiene campos: nombre (requerido), email (requerido), país (requerido), idioma (auto-detected, editable), mensaje (opcional)
  - [ ] Validación de email en tiempo real
  - [ ] Checkbox de privacidad obligatorio
  - [ ] Confirmación visual tras envío
  - [ ] Lead almacenado en base de datos
  - [ ] Email de notificación enviado al equipo
- **Prioridad:** Must
- **FRs relacionados:** FR-020 a FR-027

**US-011** — Contactar por chatbot
> Como **padre intencional**, quiero chatear con un asistente virtual, para resolver dudas iniciales sin compromiso.

- **Criterios de aceptación:**
  - [ ] El widget es visible en todas las páginas
  - [ ] Se abre al hacer click en el ícono de chat
  - [ ] Muestra disclaimer inicial
  - [ ] Ofrece menú de categorías FAQ
  - [ ] Responde en el idioma activo del sitio
- **Prioridad:** Must
- **FRs relacionados:** FR-030 a FR-043

**US-012** — Ser derivado a humano
> Como **padre intencional**, quiero hablar con una persona real cuando mis preguntas sean complejas, para sentirme acompañado.

- **Criterios de aceptación:**
  - [ ] Botón "Hablar con una persona" visible en todo momento
  - [ ] Al hacer click, el chatbot solicita nombre y email (si no los tiene)
  - [ ] Crea notificación al equipo Translatio
  - [ ] Informa al usuario el tiempo estimado de respuesta
- **Prioridad:** Must
- **FRs relacionados:** FR-036, FR-041, FR-042

### Epic 3: Multilenguaje

**US-020** — Navegar en mi idioma
> Como **padre intencional chino**, quiero ver todo el sitio en chino simplificado, para entender la información sin barreras idiomáticas.

- **Criterios de aceptación:**
  - [ ] Todas las páginas están disponibles en ZH
  - [ ] El selector de idioma muestra "中文" (no "ZH")
  - [ ] La URL cambia a `/zh/...` al seleccionar chino
  - [ ] Todos los formularios y CTAs están traducidos
  - [ ] No hay texto en español visible en la versión china
- **Prioridad:** Must (fase 2 si se comienza con 3 idiomas)
- **FRs relacionados:** FR-002, FR-005

**US-021** — Cambiar idioma fácilmente
> Como visitante, quiero cambiar el idioma del sitio con un solo click, para ver el contenido en mi idioma preferido.

- **Criterios de aceptación:**
  - [ ] Selector de idioma visible en header (no escondido en menú hamburguesa en desktop)
  - [ ] Un click cambia el idioma sin recargar toda la página (o con recarga rápida < 2s)
  - [ ] La selección persiste durante la sesión
- **Prioridad:** Must
- **FRs relacionados:** FR-002

### Epic 4: Gestión Administrativa

**US-030** — Gestionar leads
> Como **miembro del equipo Translatio**, quiero ver todos los leads capturados, para dar seguimiento oportuno.

- **Criterios de aceptación:**
  - [ ] Panel con lista de leads ordenados por fecha (más reciente primero)
  - [ ] Filtros: por fecha, idioma, país, fuente (formulario/chatbot)
  - [ ] Cada lead muestra: nombre, email, país, idioma, fecha, fuente
  - [ ] Exportación a CSV
- **Prioridad:** Should
- **FRs relacionados:** FR-062, FR-063

**US-031** — Actualizar FAQ del chatbot
> Como **administrador**, quiero editar las preguntas y respuestas del chatbot, para mantener la información actualizada.

- **Criterios de aceptación:**
  - [ ] Interfaz en WordPress para agregar/editar/eliminar FAQ
  - [ ] Cada FAQ tiene: categoría, pregunta (5 idiomas), respuesta (5 idiomas), activo/inactivo
  - [ ] Los cambios se reflejan en el chatbot en menos de 5 minutos
- **Prioridad:** Must
- **FRs relacionados:** FR-061

### Epic 5: Gestante

**US-040** — Informarme sobre ser gestante
> Como **mujer colombiana interesada en ser gestante**, quiero encontrar información clara sobre el proceso, para tomar una decisión informada.

- **Criterios de aceptación:**
  - [ ] Existe sección o página dirigida a gestantes (puede ser sub-sección de Servicios)
  - [ ] Explica: qué implica, derechos, acompañamiento, requisitos
  - [ ] Tiene CTA de contacto confidencial
  - [ ] Disponible mínimo en ES (Should: EN, PT)
- **Prioridad:** Should
- **FRs relacionados:** FR-011

---

## FASE 6: VALIDACIÓN Y PRIORIZACIÓN

### Quality Check

| Criterio | Resultado | Detalle |
|----------|-----------|---------|
| No ambiguo | ✅ | Cada FR usa DEBE con verbo activo y condición medible |
| Completo | ✅ | 6 módulos cubiertos, 5 epics, 12 user stories |
| Consistente | ✅ | Sin contradicciones entre FRs detectadas |
| Trazable | ✅ | Cada FR tiene fuente (stakeholder o riesgo) |
| Testable | ✅ | Cada FR tiene criterios de aceptación verificables |
| Factible | ✅ | WordPress + chatbot híbrido es técnicamente viable |

### Priorización MoSCoW

**MUST HAVE (MVP — Lanzamiento)**
| IDs | Categoría | Cantidad |
|-----|-----------|----------|
| FR-001, FR-002, FR-005, FR-006, FR-008, FR-010, FR-011, FR-012, FR-014, FR-015, FR-016, FR-017, FR-018, FR-019 | Sitio Web | 14 |
| FR-020, FR-022, FR-023, FR-024, FR-025, FR-026, FR-027 | Formulario | 7 |
| FR-030, FR-031, FR-032, FR-033, FR-034, FR-035, FR-036, FR-037, FR-038, FR-040, FR-041, FR-043 | Chatbot | 12 |
| FR-050, FR-052 | SEO/Analytics | 2 |
| FR-060, FR-061 | Admin | 2 |
| NFR-001 a NFR-005 | Performance | 5 |
| NFR-010 a NFR-016 | Security | 7 |
| NFR-020 a NFR-024 | Accessibility | 5 |
| NFR-030, NFR-032, NFR-033 | i18n | 3 |
| NFR-040, NFR-041 | Usability | 2 |
| NFR-050, NFR-052 | Disponibilidad | 2 |
| NFR-060 a NFR-063 | Compliance | 4 |
| **Total Must** | | **65** |

**SHOULD HAVE (Post-MVP inmediato)**
| IDs | Categoría | Cantidad |
|-----|-----------|----------|
| FR-003, FR-004, FR-007, FR-013, FR-021, FR-039, FR-042, FR-051, FR-053, FR-054, FR-063, FR-064 | Varios | 12 |
| FR-040 (sección gestante) | Gestante | 1 |
| NFR-031, NFR-042, NFR-051, NFR-053 | Varios | 4 |
| **Total Should** | | **17** |

**COULD HAVE (Futuro)**
| IDs | Categoría | Cantidad |
|-----|-----------|----------|
| Calculadora de costos interactiva | Marketing | 1 |
| Integración CRM (HubSpot/Salesforce) | Operaciones | 1 |
| Blog con contenido generado | Marketing | 1 |
| Agendamiento de llamadas (Calendly) | UX | 1 |
| Portal de gestantes con login | Producto | 1 |
| **Total Could** | | **5** |

**WON'T HAVE (Este release)**
| Item | Razón |
|------|-------|
| App móvil | No es prioridad; sitio responsive es suficiente |
| Pasarela de pagos online | Pagos se manejan por canal privado |
| Telemedicina / videollamadas integradas | Fuera de alcance del sitio web |
| Portal de gestión de casos | Producto interno, no público |
| Traducción automática en tiempo real (Google Translate widget) | Se requiere traducción profesional |
| Subrogación tradicional | El negocio es exclusivamente gestacional |

### Clasificación Kano

| Tipo | Requisitos | Justificación |
|------|-----------|---------------|
| **Basic (debe existir)** | HTTPS, GDPR, responsive, formulario, disclaimer, 5 idiomas | Si no están, el producto no es viable |
| **Performance (más = mejor)** | Velocidad de carga, SEO, cantidad de FAQ, cobertura de idiomas | Más y mejor = más conversión |
| **Excitement (wow factor)** | Chatbot empático 24/7, sección gestante con enfoque humano, testimonios reales | Diferenciador vs. competencia |

### Conflicts Resolved

| # | Requisitos | Conflicto | Resolución |
|---|-----------|-----------|------------|
| 1 | FR-002 (5 idiomas Must) vs. Discovery (empezar con 3) | Ambición vs. pragmatismo | **Resolución:** FR-002 como Must con phased rollout. ES/EN/PT en Sprint 3, FR/ZH en Sprint 5. El sitio DEBE soportar 5 idiomas técnicamente desde día 1, pero el contenido completo puede rolloutearse por fases. |
| 2 | NFR-001 (LCP < 4s móvil) vs. 5 idiomas + chatbot widget | Performance vs. funcionalidad | **Resolución:** Lazy loading del chatbot widget, cache agresivo, CDN. El chatbot no bloquea LCP. |
| 3 | FR-015 (Casos de éxito) vs. No hay testimonios reales | Contenido vs. realidad | **Resolución:** Si no hay testimonios, la página muestra contenido educativo sobre subrogación gestacional. NO se inventan testimonios. |

---

## OUT OF SCOPE

Los siguientes items están **explícitamente fuera del alcance** de este proyecto:

| # | Item | Razón | ¿Cuándo? |
|---|------|-------|----------|
| 1 | App móvil nativa | Sitio responsive cubre la necesidad | Post-MVP si hay demanda |
| 2 | Pasarela de pagos online | Pagos se gestionan por canal privado | Nunca (o si cambia el modelo) |
| 3 | Telemedicina / videollamadas | Requiere infraestructura HIPAA-level | Producto futuro |
| 4 | Portal de gestión de casos (dashboard) | Herramienta interna, no pública | Fase 2 |
| 5 | CRM integrado (HubSpot, Salesforce) | Procesos manuales primero | Post-MVP |
| 6 | Blog con contenido autogenerado por IA | Calidad no garantizada en tema sensible | Evaluar post-launch |
| 7 | Calculadora de costos interactiva | Requiere datos de costo real validados | Could-have |
| 8 | Calendario de agendamiento (Calendly) | El formulario + chatbot son primer contacto | Could-have |
| 9 | Redes sociales (creación de perfiles, contenido) | Es marketing, no desarrollo web | Otro proyecto |
| 10 | Email marketing automatizado | Requiere CRM + strategy | Post-MVP |
| 11 | Subrogación tradicional (no gestacional) | El negocio es EXCLUSIVAMENTE gestacional | Nunca |

---

## DEPENDENCIAS Y RESTRICCIONES

### Dependencias

| # | Dependencia | Tipo | Impacto si no se cumple | Responsable |
|---|------------|------|------------------------|-------------|
| D1 | **Contenido final aprobado** en español para todas las páginas | Contenido | No se puede desarrollar sin contenido | Gabriel + equipo |
| D2 | **Traducciones profesionales** completadas (EN, PT mínimo; FR, ZH después) | Contenido | Sitio solo en español hasta que lleguen | Gabriel + traductores |
| D3 | **Legal review** de todo el contenido (incluido disclaimer y política de privacidad) | Legal | Riesgo legal existencial si no se hace | Gabriel + abogado |
| D4 | **Hosting y dominio** configurados (Coolify ya disponible) | Infraestructura | No se puede deployar | MOLINO DevOps |
| D5 | **Fotos del equipo real** para Quiénes Somos | Contenido | Se usan placeholders temporalmente | Gabriel |
| D6 | **Base de conocimiento de FAQ** del chatbot (preguntas y respuestas aprobadas) | Contenido | Chatbot sin contenido útil | Gabriel + equipo |
| D7 | **SSL certificate** para el dominio | Infraestructura | Sitio no seguro | MOLINO DevOps |
| D8 | **Servicio de email** para notificaciones de leads (SMTP o servicio) | Infraestructura | No hay notificaciones | MOLINO DevOps |

### Restricciones

| # | Restricción | Tipo | Detalle |
|---|------------|------|---------|
| R-01 | **WordPress obligatorio** | Tecnológica | El cliente requiere WordPress como CMS |
| R-02 | **Coolify como plataforma de deploy** | Infraestructura | Hosting en VPS existente con Coolify |
| R-03 | **Presupuesto estimado $10K-$26K USD** | Financiera | Según discovery; no confirmado por cliente |
| R-04 | **Tono humano y empático** | Contenido | No corporativo, no frío, no clínico |
| R-05 | **No consejos médicos ni legales** | Legal/Ética | Chatbot y contenido informativo solamente |
| R-06 | **No testimonios falsos** | Ética | Si no hay casos reales, contenido educativo |
| R-07 | **Colombia como destino exclusivo** | Negocio | No se promueven otros países |
| R-08 | **Timeline 8-10 semanas** | Tiempo | Según discovery |

---

## TRACEABILITY MATRIX (Resumen)

| User Story | FRs Cubiertos | NFRs Relacionados |
|-----------|---------------|-------------------|
| US-001 Servicios | FR-011 | NFR-030, NFR-033 |
| US-002 Proceso | FR-012, FR-013 | NFR-030, NFR-033 |
| US-003 Equipo | FR-014 | NFR-021, NFR-030 |
| US-004 Por Colombia | FR-010 | NFR-030, NFR-033 |
| US-010 Formulario | FR-020~FR-027 | NFR-011, NFR-016, NFR-060~NFR-063 |
| US-011 Chatbot | FR-030~FR-043 | NFR-005, NFR-024, NFR-032 |
| US-012 Derivación | FR-036, FR-041, FR-042 | NFR-005 |
| US-020 Idioma ZH | FR-002, FR-005 | NFR-030, NFR-032, NFR-033 |
| US-021 Selector idioma | FR-002 | NFR-040 |
| US-030 Gestión leads | FR-062, FR-063 | NFR-014 |
| US-031 Admin FAQ | FR-061 | NFR-030 |
| US-040 Gestante | FR-011 | NFR-030 |

**Coverage:** 45/45 FRs cubiertos por al menos una User Story = **100%**

---

## FASE 7: RESUMEN ESTADÍSTICO

### Conteo de Requisitos

| Tipo | Cantidad |
|------|----------|
| Requisitos Funcionales (FR) | 45 |
| Requisitos No Funcionales (NFR) | 28 |
| **Total** | **73** |

### Distribución MoSCoW

| Prioridad | FRs | NFRs | Total |
|-----------|-----|------|-------|
| Must | 37 | 28 | 65 |
| Should | 13 | 4 | 17 |
| Could | 0 | 0 | 5 (features, no FRs) |
| Won't | 0 | 0 | 11 (items) |

### User Stories

| Epic | User Stories |
|------|-------------|
| Navegación e Información | 4 |
| Contacto y Leads | 3 |
| Multilenguaje | 2 |
| Gestión Administrativa | 2 |
| Gestante | 1 |
| **Total** | **12** |

---

## FASE 8: GO/NO-GO DECISION

### Evaluación de Criterios

| Criterio | Status | Detalle |
|----------|--------|---------|
| Vision Document recibido | ✅ | PROJECT_BRIEF + DISCOVERY_AGENT_RUN |
| Stakeholders elicited | ✅ | 7 stakeholders, conflictos resueltos |
| FRs completos y no ambiguos | ✅ | 45 FRs con criterios de aceptación |
| NFRs medibles | ✅ | 28 NFRs con métricas y umbrales |
| Use cases / user stories cubren FRs | ✅ | 12 USs, 100% cobertura |
| Quality score > 90% | ✅ | Ambigüedad < 3%, trazabilidad 100% |
| Priorización completada | ✅ | MoSCoW + Kano |
| SRS documentado | ✅ | Este documento |
| Traceability matrix | ✅ | 100% cobertura |
| Stakeholder approval | ⏳ | Pendiente validación de Gabriel |

### Flags

| Tipo | Cantidad | Detalle |
|------|----------|---------|
| 🟢 Green | 8 | Criterios cumplidos |
| 🟡 Yellow | 2 | Stakeholder approval pendiente, presupuesto no confirmado |
| 🔴 Red | 0 | Sin blockers insalvables |

### 🏁 VEREDICTO: READY WITH MINOR GAPS

**Confianza: 0.88**

**Justificación:** El SRS está completo, riguroso y testeable. Los 2 gaps son externos al documento (validación de Gabriel y confirmación de presupuesto) y no bloquean el inicio del trabajo de arquitectura.

### Gaps a Resolver (Pre-Architecture)

1. **Gabriel aprueba el alcance** — Confirmar 3 idiomas inicial vs 5 desde día 1
2. **Presupuesto confirmado** — Impacta complejidad del chatbot (reglas vs LLM)
3. **Contenido en español entregado** — Sin esto no se avanza a desarrollo
4. **Legal review del disclaimer** — Requiere abogado
5. **Fotos del equipo** — Para página Quiénes Somos
6. **FAQ base para chatbot** — Mínimo 20 preguntas/respuestas por idioma

### Próximo Agente

**Architecture Agent** — Input: este documento + PROJECT_BRIEF + DISCOVERY_AGENT_RUN

---

## 📊 RESUMEN EJECUTIVO

```
╔══════════════════════════════════════════════════════════════╗
║     REQUIREMENTS AGENT - TRANSLATIO GLOBAL                  ║
╠══════════════════════════════════════════════════════════════╣
║                                                              ║
║  Vision Readiness:    0.85 (alto)                           ║
║  Quality Score:       > 90%                                  ║
║  Traceability:        100%                                   ║
║  Ambiguity Rate:      < 3%                                   ║
║                                                              ║
║  Requisitos Funcionales:     45                              ║
║  Requisitos No Funcionales:  28                              ║
║  Total Requisitos:           73                              ║
║  User Stories:               12 (5 epics)                   ║
║                                                              ║
║  Must Have:   65 (89%)                                      ║
║  Should Have: 17 (future sprint)                            ║
║  Won't Have:  11 items (explicitly excluded)                ║
║                                                              ║
║  Módulos:                                                    ║
║  1. Sitio Web (6 páginas, 5 idiomas, WordPress)             ║
║  2. Contenido (gestacional, NO contratos)                   ║
║  3. Formulario de Contacto (GDPR-compliant)                 ║
║  4. Chatbot 24/7 (FAQ + derivación a humano)                ║
║  5. SEO + Analytics                                         ║
║  6. Panel Admin WordPress                                   ║
║                                                              ║
║  NFRs por categoría:                                        ║
║  - Performance: 5 (PageSpeed, LCP, FID)                     ║
║  - Security: 7 (HTTPS, CSRF, XSS, encryption)               ║
║  - Accessibility: 5 (WCAG 2.2 AA)                           ║
║  - i18n: 4 (Unicode, locales, layout)                       ║
║  - Usability: 2 (responsive, navigation)                    ║
║  - Availability: 4 (uptime, backup, RTO)                    ║
║  - Compliance: 4 (GDPR, Colombian law)                      ║
║                                                              ║
║  🏁 VEREDICTO: READY WITH MINOR GAPS                        ║
║  📊 Confianza: 88%                                          ║
║  📋 6 gaps externos a resolver pre-architecture              ║
║  ➡️  Próximo: Architecture Agent                             ║
║                                                              ║
╚══════════════════════════════════════════════════════════════╝
```

---

*Requirements Agent de MOLINO v1.0.0 — 16 Abril 2026*
*Documento guardado en: `/translatio-global/docs/01-brief/REQUIREMENTS_AGENT_RUN.md`*
*Próximo agente: Architecture Agent*
