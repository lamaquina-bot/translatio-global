# 🏗️ ARCHITECT AGENT RUN — Translatio Global

**Fecha:** 16 Abril 2026  
**Agente:** Architect Agent de MOLINO v1.0.0  
**Proyecto:** Translatio Global — Subrogación Gestacional en Colombia  
**Input:** PROJECT_BRIEF + DISCOVERY_AGENT_RUN + REQUIREMENTS_AGENT_RUN (73 requisitos, confianza 0.88)

---

## 📊 RESUMEN EJECUTIVO

```
╔══════════════════════════════════════════════════════════════╗
║     ARCHITECT AGENT - TRANSLATIO GLOBAL                     ║
╠══════════════════════════════════════════════════════════════╣
║                                                              ║
║  Architecture Style:   Modular Monolith (WordPress + Custom) ║
║  Complexity Level:     LOW-MEDIUM                           ║
║  Bounded Contexts:     3 (Content, Leads, Chatbot)          ║
║  Databases:            1 (MariaDB) + 1 (SQLite chatbot)     ║
║  ADRs:                 10 documentados                       ║
║  Fitness Functions:    6 definidas                           ║
║  Threats Mitigated:    8 (STRIDE)                           ║
║                                                              ║
║  ASR Coverage:         100%                                  ║
║  QA Scenarios:         8 definidos                           ║
║                                                              ║
║  🏁 VEREDICTO: READY WITH GAPS                              ║
║  📊 Confianza: 0.85                                         ║
║  ➡️  Próximo: UX/UI Agent                                   ║
║                                                              ║
╚══════════════════════════════════════════════════════════════╝
```

---

## FASE 1: SRS RECEPTION & ANALYSIS

### SRS Completeness Check

| Componente | Status | Notas |
|-----------|--------|-------|
| Functional Requirements (45) | ✅ Completo | 6 módulos, bien definidos |
| Non-Functional Requirements (28) | ✅ Completo | Métricas y umbrales claros |
| Use Cases / User Stories (12) | ✅ Completo | 5 epics, criterios de aceptación |
| Traceability Matrix | ✅ 100% coverage | FRs → USs → NFRs |
| Glossary | ✅ | Términos clave definidos |
| Prioritized Backlog | ✅ MoSCoW | 65 Must, 17 Should |

### Readiness Score: **0.88** (alto)

### Architecturally Significant Requirements (ASRs)

| ID | Tipo | Descripción | Prioridad |
|----|------|-------------|-----------|
| ASR-001 | Performance | LCP < 4s móvil, < 2s desktop con 5 idiomas + chatbot widget | HIGH |
| ASR-002 | Security | GDPR compliance, datos de leads cifrados, HTTPS everywhere | HIGH |
| ASR-003 | i18n | 5 idiomas con URLs únicas, hreflang, contenido profesional no automático | HIGH |
| ASR-004 | Availability | Uptime 99.5% sitio, 99% chatbot, backup diario | MEDIUM |
| ASR-005 | Extensibility | Admin puede gestionar contenido, FAQ, leads sin developer | MEDIUM |
| ASR-006 | Accessibility | WCAG 2.2 AA completo | MEDIUM |
| ASR-007 | Integrability | Chatbot ↔ WordPress ↔ Email ↔ Analytics | MEDIUM |

### Quality Attribute Priorities

1. **Security** — Datos personales de padres europeos (GDPR), tema sensible
2. **i18n** — 5 idiomas es core del producto, no feature
3. **Performance** — Usuarios internacionales con conexiones variables
4. **Availability** — Chatbot 24/7 implica servicio Always-On
5. **Extensibility** — Cliente necesita autonomía post-launch
6. **Accessibility** — Requisito legal y ético (WCAG 2.2 AA)

### Gaps for Requirements Agent

Ninguno crítico. El SRS está sólido (confianza 0.88). Los gaps son externos (presupuesto, contenido, legal review).

---

## FASE 2: ARCHITECTURAL REQUIREMENTS ANALYSIS

### Quality Attribute Scenarios

| ID | QA | Estímulo | Entorno | Respuesta | Medida | Fuente |
|----|-----|----------|---------|-----------|--------|--------|
| QA-001 | Performance | Visitante carga Home en móvil 3G | Tráfico normal, 50 concurrentes | Página renderizada completa | LCP < 4.0s | NFR-001 |
| QA-002 | Performance | Chatbot widget se inicializa | Post page-load, cualquier página | Widget interactuable | Init < 2.0s | NFR-005 |
| QA-003 | Security | Visitante EU envía formulario | Producción, GDPR applies | Datos cifrados, consentimiento registrado, email notificación | 0 leaks, consentimiento timestamped | NFR-010, NFR-060 |
| QA-004 | Security | Atacante intenta inyección SQL en formulario | Producción | Input sanitizado, request bloqueada | 0 vulnerabilidades | NFR-011 |
| QA-005 | i18n | Padre chino navega en ZH | Cualquier página | Contenido completo en chino, URL /zh/... | 0 texto en otro idioma | NFR-030-033 |
| QA-006 | Availability | Servidor se reinicia | Producción | Sitio vuelve online automáticamente | RTO < 4h | NFR-053 |
| QA-007 | Extensibility | Admin edita FAQ del chatbot | WordPress admin panel | Cambios reflejados en < 5 min | Sin intervención dev | FR-061 |
| QA-008 | Performance | Lead capturado por chatbot | Producción | Notificación email enviada al equipo | Email entregado < 30s | FR-025, FR-041 |

### Constraints

| Tipo | Descripción | Impacto |
|------|-------------|---------|
| **Infraestructura** | VPS único (89.117.33.22), Coolify + Traefik v3.6.8 | No multi-region, no auto-scale |
| **Plataforma** | WordPress obligatorio como CMS | Limita stack, pero acelera desarrollo |
| **Presupuesto** | Hosting ya pagado (Coolify), $0 infra adicional estimado | Recursos limitados, optimizar |
| **Timeline** | 8-10 semanas | Priorizar MVP (ES/EN/PT primero) |
| **Compliance** | GDPR + Ley 1581 Colombia | Cifrado, consentimiento, derecho al olvido |
| **Equipo** | 1 dev principal (MOLINO) | Arquitectura simple = imprescindible |

### Trade-off Matrix

| Trade-off | Decisión | Justificación |
|-----------|----------|---------------|
| Performance vs Feature completeness | Favorecer performance | Chatbot lazy-loaded, no bloquea LCP |
| Security vs Usability | Security no negociable para datos personales | GDPR = legalmente obligatorio |
| Simplicidad vs Escalabilidad | Simplicidad primero | VPS único, tráfico estimado bajo (< 5K/mes MVP) |
| Costo vs Funcionalidad | Usar OSS y self-hosted | Presupuesto limitado, Coolify ya disponible |
| i18n completeness vs Speed to market | Phased rollout: ES/EN/PT → FR/ZH | Descubrimiento recomienda 3 primero |

---

## FASE 3: ARCHITECTURE STYLE SELECTION

### Candidatos Evaluados

| Estilo | Performance | Security | i18n | Maint. | Deploy | Veredicto |
|--------|------------|----------|------|--------|--------|-----------|
| **Monolito Modular (WordPress)** | 4 | 3 | 4 | 3 | 5 | ✅ RECOMENDADO |
| Microservicios (WP + API + Chatbot separado) | 3 | 3 | 4 | 4 | 2 | ❌ Overkill |
| Serverless (Headless WP + API Gateway) | 4 | 4 | 4 | 3 | 3 | ❌ Complejo para equipo/VPS |
| Jamstack (Static + WP headless) | 5 | 4 | 3 | 3 | 3 | ❌ i18n dinámico difícil |

### Decisión: Monolito Modular con WordPress

**Justificación:**
1. VPS único = no hay infra para distribuir servicios
2. Equipo de 1 dev = overhead de microservicios no justificado
3. Tráfico estimado < 5K/mes (MVP) = WordPress monolito maneja 10x eso
4. El cliente pidió WordPress explícitamente
5. Plugins (Polylang, CF7) resuelven i18n y forms sin desarrollo custom

### Plan de Evolución

```
Fase 1 (MVP): WordPress monolito + chatbot embebido (widget JS)
Fase 2 (Post-MVP): Si chatbot crece → extraer a microservicio independiente
Fase 3 (Escala): Si tráfico > 50K/mes → CDN + cache layer + read replicas
```

---

## FASE 4: DOMAIN-DRIVEN DESIGN

### Bounded Contexts

```
┌─────────────────────────────────────────────────────────────────┐
│                     TRANSLATIO GLOBAL                           │
│                                                                 │
│  ┌──────────────┐  ┌──────────────┐  ┌──────────────────────┐  │
│  │   CONTENT    │  │    LEADS     │  │      CHATBOT         │  │
│  │   CONTEXT    │  │   CONTEXT    │  │      CONTEXT         │  │
│  │              │  │              │  │                      │  │
│  │ • Pages      │  │ • Lead       │  │ • FAQ Entries        │  │
│  │ • Services   │  │ • Form       │  │ • Conversations      │  │
│  │ • Testimonials│ │   Submissions│  │ • Lead Capture       │  │
│  │ • i18n Content│ │ • Email      │  │ • Human Handoff      │  │
│  │ • Media      │  │   Notifs     │  │ • i18n Responses     │  │
│  │              │  │ • Consent    │  │                      │  │
│  │ Type: CORE   │  │ Type: CORE   │  │ Type: SUPPORTING     │  │
│  └──────────────┘  └──────────────┘  └──────────────────────┘  │
│         │                │                      │               │
│         └────────────────┼──────────────────────┘               │
│                          │                                      │
│              ┌──────────────────────┐                           │
│              │   SHARED KERNEL      │                           │
│              │  • i18n (5 langs)    │                           │
│              │  • User Language     │                           │
│              │  • Country codes     │                           │
│              │  • Consent model     │                           │
│              └──────────────────────┘                           │
└─────────────────────────────────────────────────────────────────┘
```

### Context Map

| Upstream | Downstream | Patrón | Descripción |
|----------|-----------|--------|-------------|
| Content | Chatbot | OHS (Open Host Service) | Chatbot lee FAQ entries desde WordPress |
| Leads | Chatbot | Customer-Supplier | Chatbot escribe leads en WordPress |
| Content | Leads | Conformist | Leads usa páginas de contenido como contexto |

### Ubiquitous Language

| Término | Contexto | Definición |
|---------|----------|-----------|
| Lead | Leads, Chatbot | Persona que proporcionó datos de contacto |
| FAQ Entry | Chatbot | Pregunta + respuesta en N idiomas, vinculada a categoría |
| Conversation | Chatbot | Sesión de chat con un visitante, puede generar un Lead |
| Handoff | Chatbot | Transferencia de conversación a equipo humano |
| Submission | Leads | Datos enviados vía formulario de contacto |
| Consent | Leads | Aceptación explícita de política de privacidad |
| Locale | Shared | Combinación idioma + región (es-CO, en-US, pt-BR, zh-CN, fr-FR) |

---

## FASE 5: COMPONENT DESIGN

### Diagrama C4 — Nivel Context

```
                    ┌─────────────┐
                    │   Padre     │
                    │ Intencional │──────────┐
                    │ (ES/EN/PT/ │          │
                    │  ZH/FR)    │          │ HTTPS
                    └─────────────┘          │
                                              ▼
┌──────────┐                         ┌─────────────────┐
│ Google   │◄───── SEO/Analytics ────│                 │
│ (Crawler)│                         │   TRANSLATIO    │
└──────────┘                         │   GLOBAL WEB    │
                                     │                 │
┌──────────┐                         │  WordPress +    │
│ Gabriel/ │──── WP Admin ─────────►│  Chatbot Widget │
│ Team     │                         │                 │
└──────────┘                         └────────┬────────┘
                                              │
                                     ┌────────▼────────┐
                                     │  Email Service   │
                                     │  (SMTP relay)    │
                                     └─────────────────┘
```

### Diagrama C4 — Nivel Container

```
┌──────────────────────────────────────────────────────────────────┐
│                        VPS (89.117.33.22)                        │
│                                                                  │
│  ┌─────────────────────────────────────────────────────────┐    │
│  │                    Traefik v3.6.8                        │    │
│  │         (Reverse Proxy + SSL Termination + Routing)     │    │
│  └────────────────────────┬────────────────────────────────┘    │
│                           │                                      │
│                           ▼                                      │
│  ┌─────────────────────────────────────────────────────────┐    │
│  │                                                         │    │
│  │              WordPress Container (Docker)                │    │
│  │                                                         │    │
│  │  ┌─────────────┐  ┌──────────────┐  ┌───────────────┐  │    │
│  │  │ WordPress   │  │ Polylang     │  │ Contact       │  │    │
│  │  │ Core        │  │ (i18n)       │  │ Form 7        │  │    │
│  │  │             │  │              │  │ + Flamingo    │  │    │
│  │  │             │  │ /es/ /en/    │  │ (lead store)  │  │    │
│  │  │             │  │ /pt/ /zh/    │  │              │  │    │
│  │  │             │  │ /fr/         │  │              │  │    │
│  │  └─────────────┘  └──────────────┘  └───────────────┘  │    │
│  │                                                         │    │
│  │  ┌─────────────┐  ┌──────────────┐  ┌───────────────┐  │    │
│  │  │ Chatbot     │  │ Yoast SEO /  │  │ WP Mail SMTP  │  │    │
│  │  │ Widget      │  │ RankMath     │  │ (email relay) │  │    │
│  │  │ (custom JS) │  │ (SEO)        │  │              │  │    │
│  │  └─────────────┘  └──────────────┘  └───────────────┘  │    │
│  │                                                         │    │
│  │  ┌─────────────┐  ┌──────────────┐                     │    │
│  │  │ GDPR Cookie │  │ WP Rocket /  │                     │    │
│  │  │ Consent     │  │ LiteSpeed    │                     │    │
│  │  │ Banner      │  │ Cache        │                     │    │
│  │  └─────────────┘  └──────────────┘                     │    │
│  │                                                         │    │
│  │  ┌─────────────────────────────────────────────────┐   │    │
│  │  │              MariaDB 10.11+                      │   │    │
│  │  │  • wp_posts (páginas, CPTs en 5 idiomas)        │   │    │
│  │  │  • wp_postmeta                                  │   │    │
│  │  │  • wp_options                                   │   │    │
│  │  │  • Polylang tables (translations map)            │   │    │
│  │  │  • Flamingo tables (form submissions / leads)    │   │    │
│  │  │  • Chatbot FAQ CPT + conversation log            │   │    │
│  │  └─────────────────────────────────────────────────┘   │    │
│  │                                                         │    │
│  └─────────────────────────────────────────────────────────┘    │
│                                                                  │
│  ┌─────────────────────────────────────────────────────────┐    │
│  │  Coolify (Orchestration)                                │    │
│  │  • Deploys via Docker Compose                           │    │
│  │  • SSL certs via Let's Encrypt (Traefik integration)    │    │
│  │  • Backup volumes                                       │    │
│  │  • Environment variables                                │    │
│  └─────────────────────────────────────────────────────────┘    │
│                                                                  │
└──────────────────────────────────────────────────────────────────┘
```

### Componentes Detallados

#### 1. WordPress Core + Theme

| Aspecto | Decisión |
|---------|----------|
| **Theme** | Custom theme (desde scratch o child theme de GeneratePress/OceanWP) — lightweight, sin page builders |
| **CPTs** | 3 Custom Post Types: `servicio`, `testimonio`, `faq_chatbot` |
| **Templates** | Page templates por sección: home, servicios, proceso, equipo, contacto |
| **Editor** | Gutenberg (bloques custom para secciones repetitivas) |

#### 2. Polylang (i18n Engine)

| Aspecto | Decisión |
|---------|----------|
| **Plugin** | Polylang Pro (no gratis — necesitamos URL por idioma + hreflang automático) |
| **URLs** | `/es/`, `/en/`, `/pt/`, `/zh/`, `/fr/` (subdirectorio, no subdominio) |
| **Default** | ES (redirect automático si no hay cookie/lang detectado) |
| **Detección** | Browser language → ofrecer switch, no forzar |
| **Contenido** | Cada página/CPT tiene 5 traducciones vinculadas |
| **Media** | Imágenes compartidas, alt-text traducido |
| **SEO** | hreflang automático, sitemap por idioma, meta traducidos |

**¿Por qué Polylang y no WPML?**
- WPML es más pesado y caro (~$99/año)
- Polylang es más ligero, mejor performance, menos conflictos
- Polylang Pro maneja hreflang y SEO correctamente
- Descubrimiento anterior ya usó Polylang

#### 3. Chatbot Widget (Custom JS)

**Arquitectura del widget:**

```
┌─────────────────────────────────────────────┐
│           Chatbot Widget Architecture        │
│                                              │
│  ┌─────────────┐    ┌──────────────────┐    │
│  │  Widget JS  │    │  WordPress REST  │    │
│  │  (frontend) │◄──►│  API Extension   │    │
│  │             │    │                  │    │
│  │  • UI bubble│    │  GET /wp-json/   │    │
│  │  • Chat panel│   │  chatbot/v1/faq  │    │
│  │  • FAQ menu │    │                  │    │
│  │  • Lead form│    │  POST /wp-json/  │    │
│  │  • i18n     │    │  chatbot/v1/lead │    │
│  │  • Handoff  │    │                  │    │
│  │    button   │    │  POST /wp-json/  │    │
│  │             │    │  chatbot/v1/     │    │
│  │             │    │  conversation    │    │
│  └─────────────┘    └──────────────────┘    │
│                                              │
│  El widget es STATELESS. Todo estado         │
│  se persiste en WordPress via REST API.      │
│  No hay backend separado.                    │
└─────────────────────────────────────────────┘
```

**Implementación del chatbot:**

| Aspecto | Decisión |
|---------|----------|
| **Tipo** | Árbol de decisión (reglas) — NO LLM en MVP |
| **Frontend** | Custom JS widget (no dependencia de terceros) |
| **Backend** | WordPress REST API custom endpoints |
| **FAQ Storage** | CPT `faq_chatbot` con campos: categoría, pregunta (5 langs), respuesta (5 langs), activo |
| **Conversaciones** | Custom table `wp_chatbot_conversations` + `wp_chatbot_messages` |
| **Lead capture** | Datos enviados al mismo sistema que Contact Form 7 (Flamingo) |
| **Handoff** | Email al equipo Translatio con resumen de conversación |
| **Disclaimer** | Mensaje inicial siempre visible en el idioma activo |

**Flujo del chatbot:**

```
Visitante hace click en widget bubble
         │
         ▼
    Disclaimer inicial ("Soy asistente virtual...")
         │
         ▼
    Menú de categorías FAQ:
    ├── Proceso
    ├── Costos
    ├── Legalidad
    ├── Tiempos
    ├── Requisitos
    └── Ser Gestante
         │
         ▼
    Preguntas de la categoría seleccionada
         │
         ├── Respuesta predefinida → "¿Tienes otra pregunta?" / "Hablar con persona"
         │
         ├── "Hablar con persona" → Captura nombre + email → Email al equipo
         │
         └── Pregunta no cubierta → "¿Quieres que te conectemos con nuestro equipo?"
```

#### 4. Formulario de Contacto

| Aspecto | Decisión |
|---------|----------|
| **Plugin** | Contact Form 7 + Flamingo (almacenamiento) |
| **Campos** | Nombre*, Email*, País*, Idioma (auto), Mensaje, Etapa del proceso |
| **GDPR** | Checkbox obligatorio de privacidad + registro de consentimiento |
| **Storage** | Flamingo guarda submissions en DB |
| **Notificación** | Email al equipo Translatio por cada lead |
| **Rate limiting** | Plugin Rate Limit o custom hook (3/hora/IP) |
| **Spam** | reCAPTCHA v3 (invisible) o Cloudflare Turnstile |

#### 5. SEO & Analytics

| Aspecto | Decisión |
|---------|----------|
| **SEO Plugin** | RankMath (más ligero que Yoast, mejor UX) |
| **Analytics** | Google Analytics 4 (via Site Kit o manual) |
| **Sitemap** | RankMath genera sitemap por idioma |
| **Schema** | RankMath genera Organization, FAQPage, BreadcrumbList |
| **hreflang** | Polylang Pro maneja automáticamente |
| **Robots.txt** | Generado por WordPress + RankMath |
| **Open Graph** | RankMath + configuración por idioma |

#### 6. Cache & Performance

| Aspecto | Decisión |
|---------|----------|
| **Cache Plugin** | WP Super Cache o LiteSpeed Cache (si disponible) |
| **Browser Cache** | Headers de cache vía Traefik o plugin |
| **Image Optimization** | ShortPixel o WebP Express |
| **Lazy Loading** | Native WordPress (5.5+) + chatbot widget |
| **CSS/JS** | Minificación vía plugin de cache |
| **CDN** | Cloudflare Free tier (DNS proxy, no CDN full) — opcional |

---

## FASE 6: DATA ARCHITECTURE

### Selección de Base de Datos

| Contexto | Tipo | Tecnología | Justificación |
|----------|------|-----------|---------------|
| Content + Leads | Relacional | MariaDB 10.11+ | WordPress require MySQL-compatible, ACID, data relacional |
| Chatbot conversations | Relacional | MariaDB (misma DB) | Simplifica deploy, baja concurrencia |

**Justificación:** Una sola base de datos MariaDB. El VPS tiene recursos limitados. No hay necesidad de separar bases de datos cuando el tráfico estimado es < 5K visitas/mes.

### Schema Design

#### Tablas WordPress Core (existentes)

```
wp_posts          → Páginas, CPTs (servicio, testimonio, faq_chatbot)
wp_postmeta       → Metadata de posts
wp_options        → Configuración del sitio
wp_users          → Admin users
wp_usermeta       → User metadata
```

#### Tablas Polylang (plugin)

```
pll_languages     → 5 idiomas configurados
pll_term_taxonomy → Traducciones de categorías/taxonomías
pll_translations  → Mapa de traducciones entre posts
```

#### Tablas Flamingo (leads del formulario)

```
flamingo_inbounds  → Submissions del formulario de contacto
```

#### Tablas Custom (chatbot)

```sql
CREATE TABLE wp_chatbot_conversations (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  session_id VARCHAR(64) NOT NULL,
  language VARCHAR(5) NOT NULL DEFAULT 'es',
  lead_id BIGINT UNSIGNED NULL,           -- FK a flamingo_inbounds
  status ENUM('active','completed','handed_off') DEFAULT 'active',
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  INDEX idx_session (session_id),
  INDEX idx_status (status),
  INDEX idx_created (created_at)
);

CREATE TABLE wp_chatbot_messages (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  conversation_id BIGINT UNSIGNED NOT NULL,
  sender ENUM('bot','user') NOT NULL,
  message TEXT NOT NULL,
  faq_id BIGINT UNSIGNED NULL,           -- FK a wp_posts (CPT faq_chatbot)
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_conv (conversation_id),
  FOREIGN KEY (conversation_id) REFERENCES wp_chatbot_conversations(id) ON DELETE CASCADE
);

CREATE TABLE wp_chatbot_leads (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY