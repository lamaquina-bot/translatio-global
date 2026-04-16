# 🛡️ Security Agent Run — Translatio Global

**VERSIÓN:** 1.0 | **Fecha:** 16 Abril 2026 | **Agente:** Security Agent de MOLINO

---

## 1. AMENAZAS IDENTIFICADAS

```
CRÍTICAS:
  - Datos personales de leads (nombre, email, país, situación personal)
  - GDPR/Data protection (clientes europeos)
  - Ley 1581 de 2012 (datos personales Colombia)

ALTAS:
  - Formulario de contacto → spam, injection, XSS
  - Chatbot → prompt injection, información médica/legal inapropiada
  - WordPress → known vulnerabilities en plugins/tema
  - Admin panel → brute force, credential stuffing

MEDIAS:
  - DDoS (bajo riesgo, sitio informativo)
  - Scraping de contenido (bajo impacto)
```

---

## 2. MEDIDAS DE SEGURIDAD

### Transport Layer
```
✅ HTTPS obligatorio (HSTS, Traefik + Let's Encrypt)
✅ TLS 1.2+ mínimo
✅ Security headers (CSP, X-Frame-Options, X-Content-Type-Options)
✅ Redirect HTTP → HTTPS
```

### Application Layer
```
WordPress:
  ✅ Desactivar XML-RPC
  ✅ Desactivar REST API público (solo endpoints propios)
  ✅ Desactivar file editor en admin
  ✅ Limitar login attempts (5 intentos, 15 min lockout)
  ✅ 2FA para admin (plugin Wordfence)
  ✅ Auto-update major versions
  ✅ Remove WordPress version header
  ✅ Change default admin username
  ✅ Disable pingbacks/trackbacks

Forms:
  ✅ Honeypot field
  ✅ reCAPTCHA v3 (invisible, score threshold 0.5)
  ✅ Rate limiting (5/hora/IP formulario, 3/hora/IP chatbot lead)
  ✅ Server-side validation (sanitize_text_field, is_email)
  ✅ Nonce verification en todos los formularios
  ✅ CSP header previene XSS

Chatbot:
  ✅ No almacenar datos médicos o legales
  ✅ Sanitizar input del usuario
  ✅ No ejecutar código basado en input
  ✅ Logging de conversaciones (auditoría)
  ✅ Disclaimers: "Esto es información general, no consejo médico/legal"
```

### Data Layer
```
Database:
  ✅ Credenciales en variables de entorno (no en código)
  ✅ $wpdb->prepare() en todas las queries
  ✅ Separate DB user con permisos mínimos
  ✅ No exponer table names en errores

Personal Data (GDPR/Ley 1581):
  ✅ Consent explícito antes de capturar datos (checkbox GDPR)
  ✅ Cookie consent banner
  ✅ Política de privacidad completa en cada idioma
  ✅ Data retention: 2 años, luego auto-delete
  ✅ Right to deletion: endpoint para borrar datos de un lead
  ✅ Right to export: endpoint para descargar datos de un lead
  ✅ Data stored en Colombia (VPS) → complying with Ley 1581
  ✅ Encrypt PII at rest (DB encryption via Coolify volume)
```

### Infrastructure
```
  ✅ Docker isolation (no host access)
  ✅ Coolify manages secrets
  ✅ Traefik rate limiting middleware
  ✅ Regular container image updates
  ✅ No SSH exposed (Coolify terminal only)
  ✅ Backup encryption
```

---

## 3. CHATBOT SAFETY RULES

```
REGLAS OBLIGATORIAS:

1. NUNCA dar consejos médicos específicos
   Si preguntan → "Para información médica específica, consulta con un 
   profesional de la salud. ¿Quieres que te pongamos en contacto con uno?"

2. NUNCA dar consejos legales específicos
   Si preguntan → "Cada caso es diferente. Nuestro equipo legal puede 
   asesorarte. ¿Quieres agendar una consulta?"

3. NUNCA citar precios exactos
   Si preguntan → "Los costos dependen de tu caso específico. 
   ¿Quieres que un especializar te contacte con un presupuesto personalizado?"

4. NUNCA garantizar resultados
   No usar palabras como "garantizamos", "aseguramos", "100% exitoso"

5. SIEMPRE ofrecer derivación a humano
   Después de 2 preguntas sin respuesta clara
   Cuando detecte urgencia o emoción fuerte
   Cuando pregunte por caso específico

6. SIEMPRE incluir disclaimer
   "Esta información es de carácter general y no constituye asesoría 
   médica ni legal."
```

---

## 4. VULNERABILITY MANAGEMENT

```
Weekly:
  - Wordfence scan automático
  - Review plugin updates

Monthly:
  - WPScan del sitio completo
  - Review access logs
  - Check SSL certificate expiry

Quarterly:
  - Penetration test básico (OWASP ZAP)
  - Review data retention compliance
  - Update security policies
```

---

## 5. INCIDENT RESPONSE

```
IF data breach:
  1. Contener: desactivar formulario/chatbot
  2. Evaluar: qué datos se expusieron, cuántos usuarios
  3. Notificar: equipo Translatio + afectados (GDPR: 72h)
  4. Remediar: parchar vulnerabilidad
  5. Documentar: incidente + lecciones + mejoras

IF site compromised:
  1. Restaurar desde backup más reciente
  2. Cambiar todas las credenciales
  3. Analizar vector de ataque
  4. Parchar
  5. Re-deploy
```

---

**FIN DE SECURITY AGENT RUN**
