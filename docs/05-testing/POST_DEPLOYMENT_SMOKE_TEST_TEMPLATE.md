# POST-DEPLOYMENT SMOKE TEST TEMPLATE

**Proyecto:** Translatio Global  
**URL Base:** `https://translatio.thefuckinggoat.cloud/`  
**Última actualización:** 2026-04-17

---

## Instrucciones

Ejecutar este checklist DESPUÉS de cada despliegue. Todas las verificaciones deben pasar antes de considerar el deploy exitoso.

---

## 1. Verificación de URLs (HTTP Status)

### Homepages
| URL | Status Esperado | Status Real | ✅/❌ |
|-----|----------------|-------------|-------|
| `/` | 200 | | |
| `/en/` | 200 | | |
| `/pt/` | 200 | | |
| `/zh/` | 200 | | |
| `/fr/` | 200 | | |

### Páginas Internas (o anchors)
| URL | Status Esperado | Status Real | ✅/❌ |
|-----|----------------|-------------|-------|
| `/servicios` o `/#services` | 200 | | |
| `/proceso` o `/#process` | 200 | | |
| `/quienes-somos` o `/#about` | 200 | | |
| `/contacto` o `/#contact` | 200 | | |
| `/en/services` o `/en/#services` | 200 | | |
| `/en/process` o `/en/#process` | 200 | | |
| `/en/about` o `/en/#about` | 200 | | |
| `/en/contact` o `/en/#contact` | 200 | | |

**Comando rápido:**
```bash
for path in / /en/ /pt/ /zh/ /fr/ /servicios /proceso /quienes-somos /contacto; do
  status=$(curl -s -o /dev/null -w "%{http_code}" "https://translatio.thefuckinggoat.cloud${path}")
  echo "${path} → ${status}"
done
```

### Assets Estáticos
| URL | Status Esperado | ✅/❌ |
|-----|----------------|-------|
| `/_astro/index.BtTOLcli.css` | 200 | |
| `/favicon.svg` | 200 | |
| `/sitemap.xml` | 200 | |
| `/robots.txt` | 200 | |

---

## 2. Verificación de APIs

| Endpoint | Método | Status Esperado | ✅/❌ |
|----------|--------|----------------|-------|
| `/api/chat` | POST | 200 (o alternativa client-side) | |
| `/api/contact` | POST | 200 (o redirect a servicio externo) | |

**Nota:** Si el sitio es estático, estos endpoints NO deben existir. Verificar que:
- El chatbot funciona sin API (FAQ client-side)
- El formulario envía a servicio externo (Formspree, etc.)

---

## 3. Verificación de Headers de Seguridad

```bash
curl -sI https://translatio.thefuckinggoat.cloud/ | grep -iE "content-security-policy|strict-transport|x-frame|x-content-type|referrer-policy|permissions-policy"
```

| Header | Presente | Valor Correcto | ✅/❌ |
|--------|----------|----------------|-------|
| `Content-Security-Policy` | ☐ | `default-src 'self'; ...` | |
| `Strict-Transport-Security` | ☐ | `max-age=31536000; includeSubDomains` | |
| `X-Frame-Options` | ☐ | `SAMEORIGIN` | |
| `X-Content-Type-Options` | ☐ | `nosniff` | |
| `Referrer-Policy` | ☐ | `strict-origin-when-cross-origin` | |
| `Permissions-Policy` | ☐ | `camera=(), microphone=(), ...` | |

---

## 4. Verificación de Navegación

### Menú Desktop
- [ ] Click en cada enlace del menú → navega correctamente
- [ ] Selector de idioma funciona (5 opciones)
- [ ] Menú mobile se abre/cierra

### Enlaces Internos
- [ ] Todos los `<a href>` internos responden 200
- [ ] No hay enlaces rotos (`href="#"` vacíos no cuentan si son botones)

**Comando para extraer y verificar enlaces:**
```bash
curl -s https://translatio.thefuckinggoat.cloud/ | grep -oP 'href="(/[^"]*)"' | sort -u | while read href; do
  path=$(echo "$href" | sed 's/href="//;s/"//')
  status=$(curl -s -o /dev/null -w "%{http_code}" "https://translatio.thefuckinggoat.cloud${path}")
  echo "${path} → ${status}"
done
```

---

## 5. Verificación de Contenido

- [ ] Homepage carga con contenido en español
- [ ] `/en/` carga en inglés
- [ ] `/pt/` carga en portugués
- [ ] `/zh/` carga en chino
- [ ] `/fr/` carga en francés
- [ ] Imágenes cargan (logo, íconos)
- [ ] Fuentes cargan (Inter, Playfair Display, Noto Sans SC)

---

## 6. Verificación SEO

| Elemento | Presente | ✅/❌ |
|----------|----------|-------|
| `<title>` por página | ☐ | |
| `<meta name="description">` | ☐ | |
| `<html lang="...">` correcto | ☐ | |
| `<link rel="canonical">` | ☐ | |
| `<link rel="alternate" hreflang="...">` | ☐ | |
| `sitemap.xml` accesible | ☐ | |
| `robots.txt` accesible | ☐ | |
| Open Graph tags (`og:title`, `og:description`, `og:image`) | ☐ | |

---

## 7. Verificación de Rendimiento (Baseline)

```bash
# Tiempo de respuesta
curl -s -o /dev/null -w "DNS: %{time_namelookup}s\nConnect: %{time_connect}s\nTTFB: %{time_starttransfer}s\nTotal: %{time_total}s\n" https://translatio.thefuckinggoat.cloud/
```

| Métrica | Objetivo | Real | ✅/❌ |
|---------|----------|------|-------|
| TTFB (Time to First Byte) | < 500ms | | |
| Tiempo total de carga | < 2s | | |
| Tamaño de homepage | < 500KB | | |

---

## 8. Verificación SSL/TLS

```bash
curl -sI https://translatio.thefuckinggoat.cloud/ | grep -i "strict-transport"
```

- [ ] HTTPS funciona
- [ ] HTTP redirige a HTTPS
- [ ] Certificado SSL válido (no expirado)
- [ ] HSTS habilitado

---

## 9. Verificación de Formularios/Interactividad

- [ ] Chatbot se abre al click en 💬
- [ ] Chatbot muestra saludo en idioma correcto
- [ ] Botones de FAQ responden
- [ ] Formulario de contacto se muestra
- [ ] Formulario envía correctamente (verificar con test)

---

## 10. Resultado Final

| Categoría | Tests | Pasaron | Fallaron |
|-----------|-------|---------|----------|
| URLs | __ | __ | __ |
| APIs | __ | __ | __ |
| Headers Seguridad | __ | __ | __ |
| Navegación | __ | __ | __ |
| Contenido | __ | __ | __ |
| SEO | __ | __ | __ |
| Rendimiento | __ | __ | __ |
| SSL/TLS | __ | __ | __ |
| Formularios | __ | __ | __ |

**Estado del Despliegue:** ☐ APROBADO / ☐ RECHAZADO

**Firmado:** _____________ **Fecha:** _____________

---

## Script Automatizado (Opcional)

```bash
#!/bin/bash
# smoke-test.sh — Post-deployment smoke test for Translatio Global
BASE="https://translatio.thefuckinggoat.cloud"
FAILED=0

echo "=== SMOKE TEST: Translatio Global ==="
echo ""

# URLs
echo "--- URLs ---"
for path in / /en/ /pt/ /zh/ /fr/ /sitemap.xml /favicon.svg; do
  status=$(curl -s -o /dev/null -w "%{http_code}" "${BASE}${path}")
  if [ "$status" = "200" ]; then
    echo "✅ ${path} → ${status}"
  else
    echo "❌ ${path} → ${status}"
    FAILED=$((FAILED+1))
  fi
done

# APIs
echo ""
echo "--- APIs ---"
for endpoint in /api/chat /api/contact; do
  status=$(curl -s -o /dev/null -w "%{http_code}" -X POST "${BASE}${endpoint}")
  if [ "$status" != "404" ]; then
    echo "✅ ${endpoint} → ${status}"
  else
    echo "❌ ${endpoint} → ${status} (esperado: no 404)"
    FAILED=$((FAILED+1))
  fi
done

# Security headers
echo ""
echo "--- Headers de Seguridad ---"
for header in "content-security-policy" "strict-transport-security" "x-frame-options" "x-content-type-options" "referrer-policy"; do
  if curl -sI "${BASE}/" | grep -qi "$header"; then
    echo "✅ ${header} presente"
  else
    echo "❌ ${header} AUSENTE"
    FAILED=$((FAILED+1))
  fi
done

echo ""
echo "=== RESULTADO: ${FAILED} fallas ==="
exit $FAILED
```
