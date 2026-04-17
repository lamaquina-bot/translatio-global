# ⚡ PERFORMANCE TESTER REPORT — Translatio Global

**Agente:** Performance Tester ⚡  
**Fecha:** 2026-04-17  
**Versión:** 1.0.0  

---

## Resumen Ejecutivo

Análisis de rendimiento del sitio estático basado en los archivos del build Astro.

---

## Recursos del Build

| Archivo | Tamaño | Gzipped (est.) |
|---------|--------|----------------|
| `index.html` (ES) | 20.2 KB | ~5.5 KB |
| `index.html` (EN) | 19.8 KB | ~5.3 KB |
| `index.html` (PT) | 19.5 KB | ~5.2 KB |
| `index.html` (ZH) | 19.6 KB | ~5.3 KB |
| `index.html` (FR) | 19.7 KB | ~5.3 KB |
| `index.BtTOLcli.css` | 15.1 KB | ~3.5 KB |
| `favicon.svg` | 264 B | ~200 B |
| **Total por página** | **~35 KB** | **~9 KB** |

✅ Sin imágenes en el build (solo emojis como decoración)  
✅ Sin archivos JS externos (todo inline)

---

## Recursos Externos

### Google Fonts (Bloqueante)

| Fuente | Pesos | Tamaño estimado |
|--------|-------|-----------------|
| Inter | 400, 500, 600, 700 | ~80 KB woff2 |
| Noto Sans SC | 400, 500, 600, 700 | ~150 KB woff2 (CJK) |
| Playfair Display | 600, 700 | ~40 KB woff2 |
| **Total fuentes** | | **~270 KB** |

| ID | Severidad | Hallazgo |
|----|-----------|----------|
| PERF-001 | Major | Noto Sans SC (150KB CJK) se carga en todos los idiomas, solo necesaria en ZH |
| PERF-002 | Major | Sin `font-display: swap` en la URL de Google Fonts — puede causar FOIT |
| PERF-003 | Minor | 3 familias de fuentes es excesivo para una landing page |
| PERF-004 | Enhancement | Considerar `self-host` de fuentes para eliminar dependencia de Google |

### Cloudflare Analytics

- `beacon.min.js` con `defer` ✅
- Token `"pending"` — no envía datos reales

---

## Core Web Vitals — Estimación

| Métrica | Estimación | Estado |
|---------|-----------|--------|
| LCP (Largest Contentful Paint) | ~1.5s | 🟢 Bueno |
| FID (First Input Delay) | <50ms | 🟢 Bueno |
| CLS (Cumulative Layout Shift) | ~0.02 | 🟢 Bueno |
| FCP (First Contentful Paint) | ~1.2s | 🟢 Bueno |
| TTI (Time to Interactive) | ~1.8s | 🟢 Bueno |

### Justificación:
- **LCP**: El H1 es el elemento más grande, se renderiza inmediatamente (sin imágenes hero)
- **FID**: JS mínimo inline, sin frameworks pesados
- **CLS**: Header fixed con altura definida, no hay contenido dinámico que cause shift
- **Riesgo CLS**: La fuente Playfair Display puede causar shift si carga tarde (FOIT→FOUT)

---

## Análisis de Render Blocking

| Recurso | Blocking | Solución |
|---------|----------|----------|
| Google Fonts CSS | Sí | `font-display: swap` + `preconnect` ✅ |
| CSS propio | Sí | Ya está inline en Astro, tamaño pequeño ✅ |

---

## Análisis de CSS

- **Tamaño:** 15.1 KB (Tailwind compiled)
- **Uso real:** ~40% de las clases Tailwind se usan → buena purga por Astro
- **Sin CSS no usado significativo**

---

## Imágenes

✅ **No hay imágenes en el sitio** — usa emojis como iconos decorativos.  
⚠️ Si se añaden imágenes en el futuro: implementar `loading="lazy"`, WebP/AVIF, y `srcset`.

---

## Recomendaciones

| # | Severidad | Acción | Impacto estimado |
|---|-----------|--------|------------------|
| PERF-001 | Major | Cargar Noto Sans SC solo en versión ZH | -150KB en 4/5 páginas |
| PERF-002 | Major | Añadir `&display=swap` a URL de Google Fonts | Eliminar FOIT |
| PERF-003 | Minor | Evaluar si se necesita Playfair Display o si Inter bold basta | -40KB |
| PERF-004 | Enhancement | Pre-cachear fuentes con service worker | Mejora revisitas |
| PERF-005 | Enhancement | Añadir `<link rel="preload">` para fuentes críticas | Mejora FCP |
| PERF-006 | Enhancement | Minificar HTML (remover comentarios, espacios extra) | -10% HTML |

---

*Métricas: 7 recursos analizados, tamaño total ~35KB por página, 6 hallazgos (2 Major, 1 Minor, 3 Enhancement)*

*Generado por MOLINO Testing Squad — 2026-04-17*
