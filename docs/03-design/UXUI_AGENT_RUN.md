# 🎨 UX/UI Agent Run — Translatio Global

**VERSIÓN:** 1.0
**Fecha:** 16 Abril 2026
**Agente:** UX/UI Agent de MOLINO
**Confianza:** 85%

---

## 1. PRINCIPIOS DE DISEÑO

```
EMOCIÓN OBJETIVO: Confianza + Calidez + Humanidad
EMOCIÓN EVITAR: Frío, médico, corporativo, transaccional

3 PALABRAS CLAVE:
  - Cercanía (no distancia)
  - Transparencia (no oscuridad)
  - Esperanza (no desesperanza)
```

---

## 2. USER PERSONAS

### Persona 1: Padre/Madre Intencional (Internacional)
```
Perfil: 35-50 años, profesional, de Europa/LatAm/China
Estado emocional: Vulnerable, esperanzado, ansioso
Necesidad: Información clara, proceso transparente, sentirse acompañado
Tecnología: Navega en su idioma, usa mobile (60%) y desktop (40%)
Objeción principal: "¿Es seguro? ¿Es legal? ¿Es ético?"
```

### Persona 2: Posible Gestante (Colombiana)
```
Perfil: 25-38 años, colombiana, situación económica modesta
Estado emocional: Curiosa, cautelosa, necesita seguridad
Necesidad: Entender qué implica, sentirse protegida, no explotada
Tecnología: Mobile-first (80%)
Objeción principal: "¿Me van a cuidar? ¿Es seguro para mí?"
```

### Persona 3: Referidor/Profesional (Abogado, Médico)
```
Perfil: 30-55 años, profesional de salud o derecho
Necesidad: Credenciales, rigor profesional, referencia confiable
Tecnología: Desktop (70%)
Objeción principal: "¿Es serio? ¿Tienen respaldo?"
```

---

## 3. USER FLOWS

### Flow 1: Padre Internacional — Primer Contacto
```
Landing → "¿Qué es la subrogación gestacional?" → 
  "¿Por qué Colombia?" → Servicios → Proceso → 
  Chatbot "¿Tienes preguntas?" → 
  Captura de lead (nombre, email, país, idioma) → 
  Confirmación "Te contactamos en 24h" →
  Email de seguimiento
```

### Flow 2: Gestante — Información
```
Landing (ES) → "Para Gestantes" → 
  "Qué implica" → "Cómo te protegemos" → 
  "Preguntas frecuentes" → 
  Formulario de contacto / WhatsApp →
  Confirmación + llamada telefónica
```

### Flow 3: Chatbot — FAQ + Lead
```
Widget abierto (esquina inferior derecha) →
  Saludo en idioma del usuario →
  "¿En qué puedo ayudarte?" →
  FAQ rápida (3 opciones) O pregunta libre →
  Si necesita más info: "¿Quieres que te contactemos?" →
  Captura nombre + email + país →
  "Un especialista te contactará en 24h"
  
DERIVACIÓN A HUMANO:
  - Pregunta sobre costos específicos
  - Pregunta sobre caso particular
  - Pregunta médica o legal
  - 2 preguntas sin respuesta del bot
```

---

## 4. PALETA DE COLORES

```
PRIMARIO:    #4A7C6F (Verde salvia — calma, naturaleza, esperanza)
SECUNDARIO:  #D4A574 (Arena cálida — humanidad, calidez, acogida)
ACENTO:      #2C5F8A (Azul profundo — confianza, profesionalismo)
FONDO:       #FAFAF7 (Blanco cálido — no clínico, no frío)
TEXTO:       #2D2D2D (Gris oscuro — legible, no agresivo)
SUCCESS:     #6B9E7D (Verde suave)
WARNING:     #E8A54B (Ámbar)
ERROR:       #C75C5C (Rojo suave — no alarmante)
```

**Justificación cultural:**
- **Europa:** Verde salvia + azul = confianza profesional, no médico
- **China:** Arena cálida + verde = armonía, naturaleza, familia
- **LatAm:** Tonos cálidos = cercanía, humanidad
- **Evitar:** Blanco puro (demasiado clínico), rojo fuerte (alarma), negro (frío)

---

## 5. TIPOGRAFÍA

```
HEADINGS: 'Playfair Display' (serif — elegancia, confianza)
BODY:      'Inter' (sans-serif — legibilidad universal, soporta 5 idiomas)
CHINESE:   'Noto Sans SC' (Google Fonts — optimizado para chino simplificado)

ESCALA:
  H1: 48px / 3rem
  H2: 36px / 2.25rem
  H3: 28px / 1.75rem
  H4: 22px / 1.375rem
  Body: 18px / 1.125rem (más grande que estándar — accesibilidad)
  Small: 14px / 0.875rem

LINE HEIGHT: 1.7 (body), 1.2 (headings)
```

---

## 6. WIREFRAMES (TEXTO)

### HOME
```
┌─────────────────────────────────────────────┐
│  [LOGO]    Inicio  Servicios  Proceso       │
│            Nosotros  Contacto  [🌐 ES ▼]    │
├─────────────────────────────────────────────┤
│                                             │
│  Subrogación Gestacional                    │
│  con Confianza Global                       │
│                                             │
│  Acompañamos a padres y gestantes en        │
│  cada paso del camino.                      │
│                                             │
│  [Solicitar Consulta]  [Conocer Más]        │
│                                             │
│  💬 Chatbot widget (esquina inferior)       │
├─────────────────────────────────────────────┤
│  ¿Por qué Colombia?                        │
│  ┌──────┐ ┌──────┐ ┌──────┐               │
│  │Legal │ │Cuida-│ │Accesi│               │
│  │Marco │ │dos   │ │ble   │               │
│  └──────┘ └──────┘ └──────┘               │
├─────────────────────────────────────────────┤
│  Nuestros Servicios                         │
│  ┌─────────────────────────────────┐       │
│  │ Acompañamiento a Padres         │       │
│  │ Acompañamiento a Gestantes      │       │
│  │ Proceso Legal Completo          │       │
│  └─────────────────────────────────┘       │
├─────────────────────────────────────────────┤
│  Testimonios (carrusel)                     │
│  "Gracias a Translatio..." — María C.      │
├─────────────────────────────────────────────┤
│  [Contactar Ahora]                          │
│  +500 familias | 15+ años | 5 idiomas      │
├─────────────────────────────────────────────┤
│  Footer: Legal | Privacidad | Contacto      │
└─────────────────────────────────────────────┘
```

### SERVICIOS
```
┌─────────────────────────────────────────────┐
│  Nav                                        │
├─────────────────────────────────────────────┤
│  Nuestro Acompañamiento                     │
│                                             │
│  ┌─────────────────┐ ┌─────────────────┐   │
│  │ PARA PADRES      │ │ PARA GESTANTES   │   │
│  │ • Consulta inicial│ │ • Información    │   │
│  │ • Match proceso   │ │   completa      │   │
│  │ • Acompañamiento  │ │ • Protección     │   │
│  │   legal           │ │   legal y médica │   │
│  │ • Seguimiento     │ │ • Seguimiento    │   │
│  │   post-proceso    │ │   psicológico    │   │
│  │ [Más info]        │ │ [Más info]       │   │
│  └─────────────────┘ └─────────────────┘   │
├─────────────────────────────────────────────┤
│  Proceso paso a paso (timeline visual)      │
│  1. Consulta → 2. Evaluación → 3. Match     │
│  → 4. Proceso legal → 5. Acompañamiento     │
│  → 6. Nacimiento                            │
├─────────────────────────────────────────────┤
│  [Solicitar Consulta Gratuita]              │
└─────────────────────────────────────────────┘
```

### CONTACTO
```
┌─────────────────────────────────────────────┐
│  Nav                                        │
├─────────────────────────────────────────────┤
│  Contáctanos                                │
│                                             │
│  ┌──────────────┐  ┌──────────────────┐    │
│  │ FORMULARIO    │  │ INFORMACIÓN      │    │
│  │ Nombre *      │  │ 📧 email@...     │    │
│  │ Email *       │  │ 📱 WhatsApp      │    │
│  │ País *        │  │ 📍 Colombia      │    │
│  │ Mensaje       │  │                   │    │
│  │ [Enviar]      │  │ Horario: L-V 8-18│    │
│  └──────────────┘  └──────────────────┘    │
│                                             │
│  💬 O chatea con nosotros ahora             │
└─────────────────────────────────────────────┘
```

---

## 7. CHATBOT UX

```
WIDGET:
  - Posición: esquina inferior derecha
  - Color: acento (#2C5F8A)
  - Icono: 💬 con pulso suave
  - Label: "¿Preguntas? Chatea con nosotros"

CONVERSACIÓN:
  1. Saludo automático (en idioma del navegador):
     "¡Hola! 👋 Soy el asistente de Translatio.
      ¿En qué puedo ayudarte?"
  
  2. Quick replies (3 botones):
     [¿Qué es la subrogación?] [¿Es legal?] [Quiero información]
  
  3. Si elige FAQ → respuesta + "¿Tienes otra pregunta?"
  
  4. Si pregunta específica → "Para darte la mejor información,
     me gustaría que un especialista te contacte.
     ¿Me compartes tu nombre y email?"
  
  5. Captura: nombre, email, país, idioma preferido
  
  6. Cierre: "¡Gracias {nombre}! Un especialista te contactará
     en las próximas 24 horas en {idioma}. 🤝"

REGLAS UX CHATBOT:
  - No invasivo (no salta automáticamente, solo pulsa)
  - Se puede minimizar y recordar posición
  - GDPR notice antes de capturar datos
  - En mobile: fullscreen cuando está abierto
```

---

## 8. RESPONSIVE BREAKPOINTS

```
Mobile:  < 768px   (prioridad — 60% tráfico esperado)
Tablet:  768-1024px
Desktop: > 1024px

PRIORIDAD MOBILE:
  - Hero text más corto
  - CTAs sticky en bottom
  - Chatbot fullscreen
  - Menú hamburguesa
  - Imágenes lazy load
```

---

## 9. ACCESIBILIDAD (WCAG 2.2 AA)

```
✅ Contraste: > 4.5:1 texto normal, > 3:1 texto grande
✅ Focus visible en todos los interactivos
✅ Alt text en todas las imágenes
✅ Formularios con labels asociados
✅ Navegación por teclado completa
✅ No depender solo de color para información
✅ ARIA labels en chatbot y menú
✅ Reduce motion para usuarios que lo prefieren
✅ Idioma declarado en <html lang="">
```

---

## 10. COMPONENTES REUTILIZABLES

```
1. Card (servicio, testimonio, feature)
2. Button (primary, secondary, ghost)
3. Language Selector (dropdown con bandera + código)
4. Chatbot Widget (floating)
5. Form (contacto + chatbot lead)
6. Timeline (proceso paso a paso)
7. Testimonial Carousel
8. Hero Section (home + landing)
9. FAQ Accordion
10. Footer (legal links, idioma, contacto)
```

---

**FIN DE UX/UI AGENT RUN**

*Diseño para la emoción correcta. Cada pixel debe comunicar: "Estás en buenas manos."*
