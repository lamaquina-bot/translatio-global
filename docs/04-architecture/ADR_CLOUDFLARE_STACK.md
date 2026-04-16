# 🏗️ Architecture Decision Record — Translatio Global

**FECHA:** 16 Abril 2026
**DECISIÓN:** Migrar de WordPress + Coolify a Cloudflare-native stack
**RAZÓN:** Cliente solicita tecnología homogénea con Cloudflare

---

## Stack Final

```
FRAMEWORK:       Astro 5.x (SSG — Static Site Generation)
HOSTING:         Cloudflare Pages (free tier)
CHATBOT:         Cloudflare Workers + Workers AI
BASE DE DATOS:   Cloudflare D1 (SQLite serverless)
FORMULARIOS:     Cloudflare Workers (API endpoint)
DNS/CDN/SSL:     Cloudflare (ya activo)
ANALYTICS:       Cloudflare Web Analytics (free)
I18N:            Astro i18n built-in (5 idiomas, static)
EMAIL:           Cloudflare Email Workers → Resend
DOMINIO:         translatio.thefuckinggoat.cloud (Cloudflare DNS)
REPO:            github.com/lamaquina-bot/translatio-global
DEPLOY:          Git push → Cloudflare Pages auto-deploy
```

## Por qué NO WordPress

| Aspecto | WordPress + Coolify | Cloudflare Pages + Astro |
|---------|-------------------|-------------------------|
| Hosting | VPS ($5-10/mes) | Free |
| Velocidad global | Depende del VPS (1 location) | CDN 300+ locations |
| Mantenimiento | Updates, plugins, security | Zero maintenance |
| SSL | Configurar | Automático |
| Chatbot | Plugin externo | Workers AI nativo |
| DB | MySQL separado | D1 integrado |
| Deploy | Manual o CI/CD | Git push = deploy |
| Escalabilidad | Limitada al VPS | Ilimitada (serverless) |
| i18n | WPML ($79/año) o Polylang | Built-in (gratis) |

## Arquitectura

```
[Usuario]
    ↓
[Cloudflare CDN/SSL] → cache estático
    ↓
[Astro SSG pages] → HTML/CSS/JS pre-generado (5 idiomas)
    
[Chatbot widget] → JS en browser
    ↓
[Cloudflare Worker /api/chat]
    ↓
[Workers AI] → respuestas FAQ
    ↓
[D1 Database] → leads + chatbot logs
    ↓
[Resend/Email] → notificación equipo + confirmación usuario
```

## Estructura del Proyecto

```
translatio-global/
├── astro.config.mjs
├── package.json
├── wrangler.toml              (Cloudflare config)
├── src/
│   ├── components/
│   │   ├── Header.astro
│   │   ├── Footer.astro
│   │   ├── Hero.astro
│   │   ├── ServiceCards.astro
│   │   ├── ProcessTimeline.astro
│   │   ├── Testimonials.astro
│   │   ├── ContactForm.astro
│   │   ├── LanguageSelector.astro
│   │   ├── ChatbotWidget.astro
│   │   └── CTASection.astro
│   ├── layouts/
│   │   └── BaseLayout.astro
│   ├── pages/
│   │   ├── index.astro              (ES default)
│   │   ├── en/index.astro
│   │   ├── pt/index.astro
│   │   ├── zh/index.astro
│   │   ├── fr/index.astro
│   │   ├── servicios.astro / services / servicos / ...
│   │   ├── proceso.astro / process / ...
│   │   ├── quienes-somos.astro / about / ...
│   │   ├── contacto.astro / contact / ...
│   │   └── api/
│   │       ├── chat.ts              (Worker: chatbot)
│   │       ├── contact.ts           (Worker: form)
│   │       └── leads.ts             (Worker: admin)
│   ├── i18n/
│   │   ├── es.ts
│   │   ├── en.ts
│   │   ├── pt.ts
│   │   ├── zh.ts
│   │   ├── fr.ts
│   │   └── utils.ts
│   ├── content/
│   │   ├── faq/
│   │   │   ├── es.json
│   │   │   ├── en.json
│   │   │   ├── pt.json
│   │   │   ├── zh.json
│   │   │   └── fr.json
│   │   └── testimonials/
│   │       └── es.json (+ 4 idiomas)
│   └── styles/
│       ├── global.css
│       ├── variables.css
│       └── chatbot.css
├── public/
│   ├── images/
│   └── favicon.svg
├── functions/                       (Cloudflare Functions)
│   └── api/
│       ├── chat.ts
│       └── contact.ts
├── schema.d1.sql                    (D1 database schema)
└── docs/                            (documentación existente)
```

## Cloudflare Services Used

| Servicio | Uso | Free Tier |
|----------|-----|-----------|
| Pages | Hosting sitio estático | 500 builds/mes, unlimited bandwidth |
| Workers | API endpoints (chatbot, forms) | 100K requests/día |
| Workers AI | Chatbot inteligencia | 10K neurons/day (gratis) |
| D1 | Base de datos (leads, logs) | 5GB, 5M reads/día |
| DNS | Resolución de dominio | Incluido |
| SSL | Certificados | Automático |
| Web Analytics | Métricas del sitio | Gratis |
| Email Routing | Email @translatio | Gratis |

**Costo total: $0/mes**
