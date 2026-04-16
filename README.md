# Translatio Global

> Agencia de acompañamiento integral en subrogación gestacional en Colombia.

## Stack

| Tech | Uso |
|-----------|
| **Astro 5** | Framework SSG (Static Site Generation) |
| **Cloudflare Pages** | Hosting + CDN global + SSL |
| **Cloudflare Workers** | API endpoints (chatbot, forms) |
| **Cloudflare D1** | Base de datos SQLite (leads, logs) |
| **Workers AI** | Chatbot inteligencia |
| **Tailwind CSS** | Estilos |

**Costo: $0/mes** (todo dentro del free tier de Cloudflare)

## Quick Start

```bash
# Instalar dependencias
npm install

# Desarrollo local
npm run dev

# Build
npm run build

# Preview
npm run preview

# Deploy (automático via git push a main)
git push origin main
```

## Idiomas

ES (Español) · EN (English) · PT (Português) · ZH (中文) · FR (Français)

## Estructura

```
src/
  components/    → Componentes Astro reutilizables
  layouts/       → Layout base
  pages/         → Páginas por idioma
  pages/api/     → Workers API (chatbot, forms)
  i18n/          → Traducciones
  content/       → FAQ, testimonios
  styles/        → CSS global + variables
functions/       → Cloudflare Functions
public/          → Assets estáticos
docs/            → Documentación del proyecto
```

## Deploy

Git push a `main` → Cloudflare Pages auto-deploy.

## Docs

Ver `docs/` para brief, arquitectura, testing strategy y chatbot spec.
