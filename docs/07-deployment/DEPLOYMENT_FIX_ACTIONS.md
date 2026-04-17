# DEPLOYMENT FIX ACTIONS — Translatio Global

**Fecha:** 2026-04-17  
**Agente:** DevOps Agent (MOLINO)

---

## Acciones Ejecutadas

### Acción 1: Verificar sitio en vivo
```bash
curl -sI https://translatio.thefuckinggoat.cloud/
```
**Resultado:** ✅ Homepage responde HTTP 200, servidor Cloudflare

### Acción 2: Verificar páginas internas
```bash
curl -s -o /dev/null -w "%{http_code}" https://translatio.thefuckinggoat.cloud/servicios
curl -s -o /dev/null -w "%{http_code}" https://translatio.thefuckinggoat.cloud/api/chat
curl -s -o /dev/null -w "%{http_code}" https://translatio.thefuckinggoat.cloud/api/contact
curl -s -o /dev/null -w "%{http_code}" https://translatio.thefuckinggoat.cloud/sitemap.xml
```
**Resultado:** ❌ Todo devuelve 404

### Acción 3: Inspeccionar archivos de build
```bash
find dist/ -type f
```
**Resultado:** Solo 7 archivos: 5 index.html (uno por idioma), 1 CSS, 1 favicon
No hay páginas separadas para servicios/proceso/contacto

### Acción 4: Inspeccionar código fuente
```bash
find src/pages/ -type f
```
**Resultado:** Solo existen `index.astro` por idioma. No hay páginas para las rutas del nav.

### Acción 5: Verificar APIs referenciadas
```bash
grep -r "api/chat\|api/contact" src/
```
**Resultado:** 
- `ChatbotWidget.astro` → `fetch('/api/chat')` y `fetch('/api/contact')`
- `ContactForm.astro` → `fetch('/api/contact')`

No existe `src/pages/api/` ni ningún backend.

### Acción 6: Consultar Coolify API
```bash
GET /v1/projects → translatio-global (ifpl4qogpk6ub2cg7o3tcxsi)
GET /v1/projects/ifpl4qogpk6ub2cg7o3tcxsi → Sin aplicaciones
```
**Resultado:** Proyecto existe pero está VACÍO. No hay app desplegada en Coolify.

### Acción 7: Verificar headers de seguridad
```bash
curl -sI https://translatio.thefuckinggoat.cloud/
```
**Headers ausentes:**
- Content-Security-Policy
- Strict-Transport-Security
- X-Frame-Options
- X-Content-Type-Options
- Referrer-Policy
- Permissions-Policy

### Acción 8: Generar sitemap.xml
**Resultado:** ✅ Creado `dist/sitemap.xml` con 5 URLs (homepages por idioma)

---

## Acciones PENDIENTES (requieren acceso/decisión humana)

### Fix 1: Navegación (Header.astro)
**Archivo:** `src/components/Header.astro`
**Cambio:** Reemplazar rutas absolutas por anchor links

```diff
- es: { home: '/', services: '/servicios', process: '/proceso', about: '/quienes-somos', contact: '/contacto' },
+ es: { home: '/', services: '#services', process: '#process', about: '#about', contact: '#contact' },
```

Repetir para los 5 idiomas. Agregar `id` correspondientes en las secciones de `index.astro`.

### Fix 2: Chatbot sin API
**Archivo:** `src/components/ChatbotWidget.astro`
**Cambio:** Eliminar `fetch('/api/chat')` y usar respuestas predefinidas mapeadas por `data-q`:

```javascript
const faqResponses = {
  what_is: 'La subrogación gestacional es un proceso...',
  legal: 'Sí, la subrogación es legal en Colombia...',
  info: 'Puedes contactarnos por...'
};
```

### Fix 3: Formulario de contacto
**Opciones:**
1. **Formspree** (recomendado): `https://formspree.io/f/{form_id}` — gratuito hasta 50/mes
2. **Getform.io**: Similar, plan gratuito
3. **WhatsApp**: Link directo `https://wa.me/57XXXXXXXXX`
4. **API propia en Coolify**: Crear servicio Node.js/Python

### Fix 4: Despliegue en Coolify
Crear aplicación nginx:alpine en proyecto `translatio-global`:
- Volúmen: ruta al dist/
- Dominio: translatio.thefuckinggoat.cloud
- Headers de seguridad en nginx.conf

### Fix 5: Headers de seguridad (nginx.conf)
```nginx
add_header Content-Security-Policy "default-src 'self'; script-src 'self' 'unsafe-inline'; style-src 'self' 'unsafe-inline' https://fonts.googleapis.com; font-src https://fonts.gstatic.com; img-src 'self' data:;" always;
add_header Strict-Transport-Security "max-age=31536000; includeSubDomains" always;
add_header X-Frame-Options "SAMEORIGIN" always;
add_header X-Content-Type-Options "nosniff" always;
add_header Referrer-Policy "strict-origin-when-cross-origin" always;
```

### Fix 6: Sitemap automático
```bash
npm install @astrojs/sitemap
```
Agregar a `astro.config.mjs`:
```javascript
import sitemap from '@astrojs/sitemap';
export default defineConfig({
  integrations: [tailwind(), sitemap()],
});
```
