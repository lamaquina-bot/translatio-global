# 🎨 VISUAL TESTER REPORT — Translatio Global

**Agente:** Visual Regression Tester 🎨  
**Fecha:** 2026-04-17  
**Versión:** 1.0.0  

---

## Resumen Ejecutivo

Documentación de la baseline visual para las 5 variantes de idioma. No hay screenshots (análisis de código), pero se documenta el layout esperado como referencia para futuras comparaciones.

---

## Layout Global (Común a todas las páginas)

### Estructura de Secciones (top to bottom):
1. **Header** — Fixed, `bg-[#FAFAF7]/95 backdrop-blur-sm`, z-50
2. **Hero** — pt-28, título H1 centrado, subtítulo, 2 CTAs
3. **Servicios** — Grid 3 columnas, cards con shadow
4. **Proceso** — Timeline vertical con 4 pasos
5. **Stats** — Background verde `#4A7C6F`, 3 columnas
6. **CTA** (solo ES, EN) — Fondo blanco, botón dorado
7. **Contacto** — Formulario centrado
8. **Footer** — Fondo oscuro `#2D2D2D`
9. **Chatbot** — Fixed bottom-right, botón azul `#2C5F8A`

### Breakpoints:

| Breakpoint | Ancho | Comportamiento |
|-----------|-------|----------------|
| Mobile | < 768px | Nav oculta, hamburger menu, 1 columna |
| Tablet | 768px+ | Nav visible, grid 3 cols |
| Desktop | 1024px+ | H1 más grande (6xl) |

---

## Paleta de Colores

| Color | Hex | Uso |
|-------|-----|-----|
| Primary | `#4A7C6F` | CTAs, acentos, nav active |
| Secondary | `#D4A574` | CTA dorado, logo footer |
| Accent | `#2C5F8A` | Chatbot, botones envío |
| Background | `#FAFAF7` | Fondo principal |
| Text | `#2D2D2D` | Texto body |
| Footer BG | `#2D2D2D` | Footer |

---

## Tipografía

| Elemento | Fuente | Tamaño |
|----------|--------|--------|
| Body | Inter 400 | base (16px) |
| H1 | Playfair Display 700 | 4xl→5xl→6xl responsive |
| H2 | Playfair Display 700 | 3xl→4xl |
| H3 | Playfair Display 600 | xl |
| Nav | Inter 500 | sm (14px) |

### Variante ZH (Chino):
- Body: `Noto Sans SC` (declarada en CSS como override)
- H1-H3: `Noto Sans SC` (sin serif para CJK)
- ⚠️ **VIS-001 (Major):** La regla CSS para ZH es `[lang=zh] { font-family: Noto Sans SC !important }` pero el body ya tiene `font-['Inter',sans-serif]` inline — el CSS global debería sobreescribir correctamente, pero hay potencial conflicto de especificidad.

---

## Diferencias entre Idiomas

| Aspecto | ES | EN | PT | ZH | FR |
|---------|----|----|----|----|-----|
| Sección CTA intermedia | ✅ | ✅ | ❌ | ❌ | ❌ |
| Título H1 | Subrogación Gestacional | Gestational Surrogacy | Substituição Gestacional | 代孕服务 | GPA |
| Stats "+500" | +500 | +500 | +500 | 500+ | +500 |
| Chatbot disclaimer | ES traducido | EN | EN ⚠️ | ZH | EN ⚠️ |
| `meta description` | ES ✅ | ES ❌ | ES ❌ | ES ❌ | ES ❌ |

### ⚠️ VIS-002 (Minor): Chatbot disclaimer no traducido en PT y FR

En `pt/index.html` y `fr/index.html`, el disclaimer del chatbot está en inglés:
```html
<p class="text-[10px] text-gray-400">This information is general and does not constitute medical or legal advice.</p>
```

### ⚠️ VIS-003 (Minor): Sección CTA intermedia ausente en PT, ZH, FR

Las versiones ES y EN tienen una sección extra "¿Listo para Dar el Primer Paso?" con CTA dorado entre Stats y Contacto. PT, ZH y FR saltan directamente de Stats a Contacto.

---

## Responsive Baseline

### Mobile (< 768px):
- Header: Logo + selector idioma + hamburger ☰
- Hero: H1 tamaño base (4xl), CTAs en columna
- Servicios: 1 columna (cards stacked)
- Proceso: Timeline vertical (igual que desktop)
- Stats: 3 columnas (texto más pequeño)
- Contacto: Full width
- Chatbot: max-width calc(100vw - 2rem)

### Desktop (≥ 768px):
- Header: Logo + nav completa + selector idioma
- Hero: H1 5xl
- Servicios: 3 columnas grid

### Desktop LG (≥ 1024px):
- Hero: H1 6xl

---

## Recomendaciones

| # | Severidad | Acción |
|---|-----------|--------|
| VIS-001 | Major | Verificar renderizado CJK en ZH — confirmar que Noto Sans SC aplica correctamente sobre clases inline |
| VIS-002 | Minor | Traducir chatbot disclaimer en PT y FR |
| VIS-003 | Minor | Añadir sección CTA intermedia en PT, ZH, FR para consistencia |
| VIS-004 | Enhancement | Crear screenshots baseline con Percy/Chromatic |
| VIS-005 | Enhancement | Testear en dispositivos CJK reales para verificar line-height y spacing |

---

*Generado por MOLINO Testing Squad — 2026-04-17*
