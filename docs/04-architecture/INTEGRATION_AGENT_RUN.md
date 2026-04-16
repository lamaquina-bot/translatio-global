# 🔗 Integration Agent Run — Translatio Global

**VERSIÓN:** 1.0 | **Fecha:** 16 Abril 2026 | **Agente:** Integration Agent de MOLINO

---

## 1. INTEGRACIONES DEL SISTEMA

### Mapa de Integraciones
```
[Usuario] → [WordPress Frontend] → [WordPress REST API]
                                         ↓
                                    [MySQL DB]
                                         ↓
                              ┌──────────┼──────────┐
                              ↓          ↓          ↓
                         [Chatbot]  [SMTP/Email] [Analytics]
                              ↓
                         [Tidio API]
                              ↓
                         [Lead Storage]
```

---

## 2. INTEGRACIÓN CHATBOT ↔ WORDPRESS

### Contrato API
```
Endpoint: POST /wp-json/translatio/v1/chatbot/message
Request:
  {
    "session_id": "string (UUID)",
    "language": "es|en|pt|zh|fr",
    "message": "string (max 500 chars)",
    "context": {
      "page": "string",
      "previous_questions": ["string"],
      "lead_captured": false
    }
  }

Response (FAQ match):
  {
    "type": "answer",
    "answer": "string",
    "related": ["pregunta1", "pregunta2"],
    "escalate": false
  }

Response (no match):
  {
    "type": "escalate",
    "message": "Para darte la mejor información...",
    "require_lead": true
  }

Response (lead captured):
  {
    "type": "confirmation",
    "message": "¡Gracias {name}! Un especialista te contactará..."
  }
```

### Rate Limiting
```
Chat:     30 mensajes / sesión / hora
Lead:     3 capturas / IP / hora
Contact:  5 envíos / IP / hora
```

---

## 3. INTEGRACIÓN WPML/POLYLANG ↔ TEMA

### Estructura de URLs
```
/es/  → Español (default)
/en/  → English
/pt/  → Português
/zh/  → 中文
/fr/  → Français

Páginas por idioma:
  /es/servicios/     /en/services/     /pt/servicos/     /zh/服务/     /fr/services/
  /es/proceso/       /en/process/      /pt/processo/     /zh/流程/     /fr/processus/
  /es/contacto/      /en/contact/      /pt/contato/      /zh/联系/     /fr/contact/
```

### Sincronización de contenido
```
WPML:
  - Translation Management → traducciones profesionales
  - String Translation → textos del tema
  - Media Translation → imágenes con texto
  
Polylang (alternativa gratuita):
  - Polylang + Lingotek para traducción
  - Strings del tema via pll_register_string()
```

---

## 4. INTEGRACIÓN EMAIL

### Flujo de Notificaciones
```
Lead capturado (formulario o chatbot)
  → wp_mail() → SendGrid SMTP
    → Email 1: Notificación equipo (con todos los datos del lead)
    → Email 2: Auto-confirmación al usuario (en su idioma)

Templates por idioma:
  - subject_{lang}.php
  - body_{lang}.php
  
Fallback: Si SendGrid falla → wp_mail() default → log error
```

---

## 5. INTEGRACIÓN ANALYTICS

```
Google Analytics 4:
  - Eventos: page_view, form_submit, chatbot_open, chatbot_message,
    chatbot_lead, language_switch, cta_click
  - Dimensiones: idioma, país, fuente
  
Tracking structure:
  dataLayer.push({
    event: 'chatbot_lead',
    language: 'es',
    country: 'ES',
    source: 'chatbot'
  });
```

---

## 6. WEBHOOKS

### Outgoing (Translatio → externos)
```
No implementar en MVP. Reservado para futuro:
  - CRM integration (HubSpot/Salesforce)
  - WhatsApp Business API
  - Calendar booking (Calendly)
```

### Incoming (externos → Translatio)
```
Tidio webhook → POST /wp-json/translatio/v1/chatbot/tidio
  - Recibir mensajes de Tidio cuando usuario escribe
  - Buscar en FAQ
  - Responder via Tidio API
```

---

## 7. DATA FLOW: LEAD COMPLETO

```
1. Usuario llega al sitio → idioma detectado → contenido servido
2. Usuario interactúa con chatbot o formulario
3. Datos enviados a REST API
4. Validación server-side (sanitize + rate limit + honeypot)
5. Insert en wp_translatio_leads
6. Email a equipo Translatio (con datos + idioma)
7. Email de confirmación al usuario (en su idioma)
8. Evento en GA4
9. Log en wp_translatio_chatbot_log (si viene de chatbot)
```

---

## 8. API SECURITY

```
Authentication: WordPress nonce (forms) + CORS headers (REST)
Authorization:  REST endpoints solo responden a requests del mismo dominio
Input:          sanitize_text_field(), absint(), wp_kses_post()
Output:         wp_send_json_success/error, never expose internals
CORS:           Access-Control-Allow-Origin: translatio.thefuckinggoat.cloud
Rate Limit:     Traefik middleware + WordPress transient counter
```

---

**FIN DE INTEGRATION AGENT RUN**
