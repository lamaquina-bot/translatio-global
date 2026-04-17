# LECCIONES APRENDIDAS — DevOps Translatio Global

**Fecha:** 2026-04-17  
**Proyecto:** Translatio Global  
**Agente:** DevOps Agent (MOLINO)

---

## Resumen Ejecutivo

El despliegue de Translatio Global presenta **4 categorías de fallas críticas** que no fueron detectadas antes de poner el sitio en producción. Ninguna de estas fallas es de infraestructura — son fallas de **validación, diseño y pipeline**.

---

## 1. Análisis de Causa Raíz por Issue

### 1.1 Páginas internas → 404

**Qué pasó:** La navegación del header apunta a `/servicios`, `/proceso`, `/quienes-somos`, `/contacto` como rutas independientes. Pero el sitio es **single-page por idioma** — no existen esas páginas.

**Por qué pasó:**
- El componente `Header.astro` fue diseñado con rutas absolutas como si el sitio tuviera páginas separadas
- Nadie verificó que esas rutas tuvieran correspondencia con archivos en `src/pages/`
- El build generó exitosamente (0 errores) porque Astro no valida que los href existan
- No se ejecutó ningún smoke test post-build

**Lección:** **El éxito del build ≠ éxito del sitio.** Un build sin errores no significa que las rutas sean correctas.

### 1.2 APIs inexistentes → 404

**Qué pasó:** El chatbot y formulario de contacto llaman a `/api/chat` y `/api/contact`. No hay backend.

**Por qué pasó:**
- El proyecto se configuró como `output: 'static'` desde el inicio
- Se diseñaron componentes que requieren servidor SIN cambiar la configuración
- No hubo validación de que los endpoints referenciados existieran
- No se verificó la arquitectura (¿es static? ¿es SSR? ¿necesita server?)

**Lección:** **Los componentes frontend que llaman APIs deben validarse contra la arquitectura real del proyecto.** Si el proyecto es estático, las APIs deben ser externas o el componente debe ser client-side.

### 1.3 Headers de seguridad ausentes

**Qué pasó:** CSP, HSTS, X-Frame-Options, etc. no están configurados.

**Por qué pasó:**
- No existe un checklist de seguridad para despliegues
- No hay configuración de nginx/Traefik con headers por defecto
- No se auditó la configuración de Cloudflare
- La seguridad se consideró "después" en lugar de "por defecto"

**Lección:** **Los headers de seguridad deben ser parte del template de despliegue, no una configuración posterior.** Cada nuevo sitio debe incluirlos por defecto.

### 1.4 Sitemap ausente

**Qué pasó:** No se generó `sitemap.xml`.

**Por qué pasó:**
- No se instaló `@astrojs/sitemap`
- No se consideró SEO como parte del pipeline de build
- No hay verificación post-deployment de archivos SEO (robots.txt, sitemap.xml, manifest.json)

**Lección:** **SEO básico (sitemap, robots.txt, meta tags) debe ser un quality gate del pipeline.**

---

## 2. ¿Por qué no se detectaron estas fallas?

### 2.1 No hubo Smoke Test post-deployment

El sitio se desplegó sin verificar NINGUNA de estas condiciones:
- [ ] ¿Todas las rutas del menú responden 200?
- [ ] ¿Las APIs referenciadas existen?
- [ ] ¿Los headers de seguridad están presentes?
- [ ] ¿El sitemap es accesible?
- [ ] ¿Los formularios funcionan?

### 2.2 No hubo validación de integridad código→build

Nadie verificó que:
- Los `href` del código tuvieran archivos correspondientes en dist/
- Los `fetch()` tuvieran endpoints existentes
- La arquitectura (static) fuera compatible con las funcionalidades (APIs)

### 2.3 Falta de quality gates en el pipeline

No existían criterios de aceptación para el deployment:
- Test de rutas (cada link del nav → 200)
- Test de APIs (cada endpoint referenciado → no 404)
- Test de headers de seguridad
- Test de archivos SEO

---

## 3. Gaps de Conocimiento del DevOps Agent

### 3.1 ¿Qué no sabía el Agente?

1. **No validó rutas vs archivos:** Debió comparar todos los href del HTML con los archivos generados en dist/
2. **No validó arquitectura vs funcionalidad:** Debió verificar que `output: 'static'` era compatible con las APIs referenciadas
3. **No aplicó security baseline:** Debió incluir headers de seguridad como parte del despliegue estándar
4. **No ejecutó smoke test:** Debió verificar el sitio post-deployment con un checklist mínimo

### 3.2 ¿Qué debe cambiar en el Pipeline?

| Fase | Cambio | Prioridad |
|------|--------|-----------|
| **Pre-build** | Validar que todos los href en el código tengan correspondencia con pages/ | Alta |
| **Post-build** | Comparar rutas del nav con archivos en dist/ | Alta |
| **Post-build** | Verificar que no haya fetch() a endpoints inexistentes | Alta |
| **Pre-deploy** | Incluir nginx.conf con headers de seguridad | Media |
| **Post-deploy** | Ejecutar smoke test automático (ver template) | Alta |
| **Post-deploy** | Verificar sitemap.xml accesible | Media |

### 3.3 Actualizaciones a KNOWLEDGE del DevOps Agent

**Nueva regla:** Antes de cualquier despliegue de sitio estático:
1. Extraer todos los enlaces internos del HTML generado
2. Verificar que cada enlace corresponde a un archivo en dist/
3. Verificar que no hay `fetch()` a rutas locales sin API correspondiente
4. Incluir headers de seguridad por defecto
5. Generar sitemap.xml (o verificar que existe)
6. Ejecutar smoke test post-deployment

**Nueva regla para Astro:**
- Si `output: 'static'`, NO puede haber API routes en `/api/`
- Si componentes necesitan APIs, deben ser: (a) externas, (b) serverless, o (c) client-side

---

## 4. Prevención de Recurrencia

### 4.1 Template de Validación Pre-Deploy

Para CADA despliegue de sitio web:

```markdown
## Pre-Deploy Checklist
- [ ] Todas las rutas del nav tienen archivos correspondientes en dist/
- [ ] No hay fetch() a APIs locales sin backend
- [ ] Headers de seguridad configurados (CSP, HSTS, X-Frame-Options)
- [ ] sitemap.xml generado y accesible
- [ ] robots.txt presente
- [ ] SSL/TLS configurado
- [ ] Redirecciones configuradas (www → non-www, http → https)
```

### 4.2 Smoke Test Automatizado

Ver template en `POST_DEPLOYMENT_SMOKE_TEST_TEMPLATE.md`.

### 4.3 Quality Gates

```
BUILD EXITOSO (0 errores)
  → VALIDACIÓN DE RUTAS (todos los href → archivo existe)
    → VALIDACIÓN DE APIs (todos los fetch → endpoint existe)
      → VALIDACIÓN DE SEGURIDAD (headers presentes)
        → SMOKE TEST POST-DEPLOY (HTTP 200 en todas las rutas)
          → ✅ DEPLOY APROBADO
```

---

## 5. Métricas del Incidente

| Métrica | Valor |
|---------|-------|
| Páginas funcionando | 5 de 25+ esperadas |
| Rutas rotas | 20+ (4 páginas × 5 idiomas) |
| APIs rotas | 2 |
| Headers faltantes | 6 |
| Archivos SEO faltantes | 2 (sitemap.xml, robots.txt) |
| Tiempo con sitio roto en producción | Desconocido |
| Detección por | Investigación manual, no automatizada |

---

## 6. Conclusión

**El problema fundamental fue la ausencia de validación post-build y post-deploy.** El build funcionó sin errores, lo que dio una falsa sensación de éxito. Pero el build nunca verificó que el OUTPUT fuera correcto — solo que no hubo errores de compilación.

**Analogía:** Es como compilar un programa sin errores pero nunca ejecutarlo para verificar que funcione.

**Acción más importante:** Implementar smoke test post-deployment como quality gate obligatorio.
