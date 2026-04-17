# Translatio Global Contact API

## Overview
Lightweight Node.js API for handling contact form submissions and chatbot leads for Translatio Global.

**Base URL:** `https://api.translatio.thefuckinggoat.cloud`

## Endpoints

### `GET /health`
Health check endpoint.

**Response:**
```json
{
  "status": "ok",
  "timestamp": "2026-04-17T20:00:00.000Z",
  "service": "translatio-contact-api"
}
```

### `POST /api/contact`
Submit a contact form.

**Rate Limit:** 10 requests per 15 minutes per IP

**Request Body:**
| Field | Type | Required | Description |
|-------|------|----------|-------------|
| name | string | ✅ | Contact name (2-100 chars) |
| email | string | ✅ | Valid email address |
| country | string | ❌ | Country of origin |
| message | string | ❌ | Message (max 2000 chars) |
| language | string | ❌ | Language code (es, en, pt, zh, fr) |
| gdpr_consent | boolean | ✅ | Must be `true` |

**Success Response (201):**
```json
{
  "success": true,
  "message": "Thank you! We will contact you within 24 hours."
}
```

### `POST /api/chat`
Submit a chatbot lead.

**Rate Limit:** 20 requests per 15 minutes per IP

**Request Body:**
| Field | Type | Required | Description |
|-------|------|----------|-------------|
| name | string | ✅ | Lead name |
| email | string | ✅ | Valid email address |
| language | string | ❌ | Language code |
| source | string | ❌ | Source (default: "chatbot") |
| gdpr_consent | boolean | ✅ | Must be `true` |

**Success Response (201):**
```json
{
  "success": true,
  "message": "Thank you! A specialist will contact you within 24 hours."
}
```

## Error Responses

**400 Bad Request:**
```json
{ "error": "Name and email are required." }
{ "error": "Invalid email format." }
{ "error": "GDPR consent is required." }
```

**429 Too Many Requests:**
```json
{ "error": "Too many requests. Please try again later." }
```

## CORS
Configured for:
- `https://translatio.thefuckinggoat.cloud`
- `https://translatio-global.thefuckinggoat.cloud`
- `https://*.translatio.thefuckinggoat.cloud`
- `http://localhost:3001` (development)

## Data Storage
Submissions are stored in `/app/data/submissions.json` inside the container. Each entry includes:
- `id` — Unique identifier
- `type` — "contact" or "chat_lead"
- `name`, `email`, `language`, `gdpr_consent`
- `timestamp`, `ip` (from X-Forwarded-For)
- Contact-specific: `country`, `message`
- Chat-specific: `source`

## Email Notifications (Future)
Prepared for SMTP integration via environment variables:
- `SMTP_HOST`, `SMTP_PORT`, `SMTP_USER`, `SMTP_PASS`
- `NOTIFY_EMAIL` — Recipient for notifications

## Coolify Deployment

| Setting | Value |
|---------|-------|
| Project | translatio-global (ifpl4qogpk6ub2cg7o3tcxsi) |
| Application UUID | eperll8ld8qzle8jce30tj5e |
| Domain | api.translatio.thefuckinggoat.cloud |
| Build Pack | Dockerfile |
| Base Directory | /api |
| Port | 3000 |
| Git Repo | lamaquina-bot/translatio-global (main branch) |

## ⚠️ DNS Note
The subdomain `api.translatio.thefuckinggoat.cloud` resolves via Cloudflare proxy. If SSL handshake errors occur:
1. Check Cloudflare DNS record for this subdomain
2. Ensure SSL/TLS mode is "Full" or "Full (Strict)"
3. Alternatively, set the DNS record to "DNS Only" (grey cloud) to bypass Cloudflare proxy

Direct server access works: `curl --resolve "api.translatio.thefuckinggoat.cloud:443:89.117.33.22" https://api.translatio.thefuckinggoat.cloud/health`
