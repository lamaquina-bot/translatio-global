# 📋 FASE 2: REQUIREMENTS - Translatio Global

**Fecha:** 27 Feb 2026
**Agente:** Requirements Agent
**Estado:** ✅ COMPLETADO

---

## 🎯 CUSTOM POST TYPES (CPT) CON CMB2

### 1. CPT: Testimonios (`testimonios`)

```php
register_post_type('testimonios', [
    'labels' => [
        'name' => 'Testimonios',
        'singular_name' => 'Testimonio',
        'add_new' => 'Agregar testimonio',
        'edit_item' => 'Editar testimonio'
    ],
    'public' => true,
    'has_archive' => false,
    'menu_icon' => 'dashicons-format-quote',
    'supports' => ['title', 'editor', 'thumbnail'],
    'show_in_rest' => true
]);
```

#### Metaboxes CMB2:
| Campo | ID | Tipo | Descripción |
|-------|----|------|-------------|
| Nombre del Cliente | `cliente_nombre` | text | Nombre completo |
| Ciudad | `cliente_ciudad` | text | Ciudad de residencia |
| Cargo/Empresa | `cliente_cargo` | text | Opcional |
| Calificación | `calificacion` | select | 1-5 estrellas |
| Fecha Testimonio | `fecha_testimonio` | text_date | Fecha del servicio |

#### Polylang Support:
- `post_title` → Traducible
- `post_content` → Traducible
- `cliente_nombre` → NO traducible
- `cliente_ciudad` → NO traducible

---

### 2. CPT: Casos de Éxito (`casos_exito`)

```php
register_post_type('casos_exito', [
    'labels' => [
        'name' => 'Casos de Éxito',
        'singular_name' => 'Caso de Éxito',
        'add_new' => 'Agregar caso',
        'edit_item' => 'Editar caso'
    ],
    'public' => true,
    'has_archive' => true,
    'menu_icon' => 'dashicons-awards',
    'supports' => ['title', 'editor', 'thumbnail', 'excerpt'],
    'show_in_rest' => true,
    'rewrite' => ['slug' => 'casos-exito']
]);
```

#### Metaboxes CMB2:
| Campo | ID | Tipo | Descripción |
|-------|----|------|-------------|
| Cliente | `caso_cliente` | text | Nombre del cliente |
| Tipo de Caso | `caso_tipo` | select | arrendamiento/comercial/inmobiliario |
| Fecha del Caso | `caso_fecha` | text_date | Fecha de resolución |
| Duración (semanas) | `caso_duracion` | text_number | Tiempo de resolución |
| Resultados | `caso_resultados` | group (repeater) | Lista de logros |
| - Resultado | `resultado_texto` | text | Logro específico |
| Destacado | `caso_destacado` | checkbox | Mostrar en home |

#### Polylang Support:
- `post_title` → Traducible
- `post_content` → Traducible
- `caso_resultados` → Traducible (cada item)

---

### 3. CPT: Servicios (`servicios`)

```php
register_post_type('servicios', [
    'labels' => [
        'name' => 'Servicios',
        'singular_name' => 'Servicio',
        'add_new' => 'Agregar servicio',
        'edit_item' => 'Editar servicio'
    ],
    'public' => true,
    'has_archive' => false,
    'menu_icon' => 'dashicons-portfolio',
    'supports' => ['title', 'editor', 'thumbnail'],
    'show_in_rest' => true
]);
```

#### Metaboxes CMB2:
| Campo | ID | Tipo | Descripción |
|-------|----|------|-------------|
| Descripción Corta | `servicio_descripcion_corta` | textarea_small | 140 caracteres max |
| Icono SVG | `servicio_icono` | file | SVG icono |
| Orden | `servicio_orden` | text_number | Posición en lista |
| Pasos del Servicio | `servicio_pasos` | group (repeater) | Steps del servicio |
| - Título del Paso | `paso_titulo` | text | Nombre del paso |
| - Descripción | `paso_descripcion` | textarea_small | Detalle |

#### Polylang Support:
- `post_title` → Traducible
- `post_content` → Traducible
- `servicio_descripcion_corta` → Traducible
- `servicio_pasos` → Traducible

---

## 📄 CAMPOS PERSONALIZADOS POR PÁGINA

### Página: INICIO (front-page.php)

#### Hero Section
| Campo | ID | Tipo | Default |
|-------|----|------|---------|
| Título Principal | `hero_titulo` | text | "Subrogación Legal con Confianza Global" |
| Subtítulo | `hero_subtitulo` | textarea_small | - |
| CTA Texto | `hero_cta_texto` | text | "Solicitar Consulta Gratuita" |
| CTA URL | `hero_cta_url` | text_url | "/contacto" |
| Imagen Fondo | `hero_imagen` | file | - |

#### Estadísticas (Repeater)
| Campo | ID | Tipo |
|-------|----|------|
| Valor | `stat_valor` | text |
| Etiqueta | `stat_etiqueta` | text |

Default values:
```json
[
  {"valor": "+500", "etiqueta": "Casos Exitosos"},
  {"valor": "+400", "etiqueta": "Clientes Satisfechos"},
  {"valor": "15+", "etiqueta": "Años de Experiencia"},
  {"valor": "98%", "etiqueta": "Tasa de Éxito"}
]
```

#### Testimonios Destacados
| Campo | ID | Tipo |
|-------|----|------|
| Testimonio 1 | `testimonio_1` | post_select (testimonios) |
| Testimonio 2 | `testimonio_2` | post_select (testimonios) |

#### CTA Final
| Campo | ID | Tipo |
|-------|----|------|
| Título CTA | `cta_titulo` | text |
| Texto CTA | `cta_texto` | textarea_small |
| Botón Texto | `cta_boton_texto` | text |
| Botón URL | `cta_boton_url` | text_url |

---

### Página: QUIÉNES SOMOS (page-quienes.php)

#### Misión/Visión/Valores
| Campo | ID | Tipo |
|-------|----|------|
| Título Misión | `mision_titulo` | text |
| Texto Misión | `mision_texto` | wysiwyg |
| Título Visión | `vision_titulo` | text |
| Texto Visión | `vision_texto` | wysiwyg |

#### Valores (Repeater)
| Campo | ID | Tipo |
|-------|----|------|
| Icono | `valor_icono` | text (dashicon class) |
| Título | `valor_titulo` | text |
| Descripción | `valor_descripcion` | textarea_small |

Default values:
```json
[
  {"icono": "dashicons-shield", "titulo": "Integridad", "descripcion": "..."},
  {"icono": "dashicons-visibility", "titulo": "Transparencia", "descripcion": "..."},
  {"icono": "dashicons-heart", "titulo": "Compromiso", "descripcion": "..."},
  {"icono": "dashicons-star-filled", "titulo": "Excelencia", "descripcion": "..."}
]
```

#### Equipo (Repeater)
| Campo | ID | Tipo |
|-------|----|------|
| Foto | `equipo_foto` | file |
| Nombre | `equipo_nombre` | text |
| Cargo | `equipo_cargo` | text |
| LinkedIn | `equipo_linkedin` | text_url |

---

### Página: NUESTRO PROCESO (page-proceso.php)

#### Pasos del Proceso (Repeater)
| Campo | ID | Tipo |
|-------|----|------|
| Número | `paso_numero` | text_number |
| Título | `paso_titulo` | text |
| Duración | `paso_duracion` | text |
| Descripción | `paso_descripcion` | textarea |
| Icono | `paso_icono` | text (dashicon) |
| Actividades | `paso_actividades` | textarea (bullets) |
| Entregable | `paso_entregable` | text |

Default: 5 pasos documentados en `contenido/completo.md`

---

### Página: CASOS DE ÉXITO (page-casos.php)

#### Filtros y Configuración
| Campo | ID | Tipo |
|-------|----|------|
| Mostrar Filtros | `mostrar_filtros` | checkbox |
| Título Intro | `casos_intro_titulo` | text |
| Texto Intro | `casos_intro_texto` | textarea_small |
| Cantidad a Mostrar | `casos_cantidad` | text_number (default: 6) |

---

### Página: CONTACTO (page-contacto.php)

#### Información de Contacto
| Campo | ID | Tipo |
|-------|----|------|
| Teléfono | `contacto_telefono` | text |
| WhatsApp | `contacto_whatsapp` | text |
| Email | `contacto_email` | text_email |
| Dirección | `contacto_direccion` | textarea_small |
| Horarios | `contacto_horarios` | text |
| Google Maps Embed | `contacto_mapa` | textarea_code |

#### Redes Sociales
| Campo | ID | Tipo |
|-------|----|------|
| LinkedIn | `social_linkedin` | text_url |
| Facebook | `social_facebook` | text_url |
| Instagram | `social_instagram` | text_url |
| YouTube | `social_youtube` | text_url |

---

## 📝 FORMULARIOS CONTACT FORM 7

### Formulario Principal: "Contacto General"

```html
<div class="form-row">
    <label>Nombre completo *</label>
    [text* nombre placeholder "Su nombre completo"]
</div>

<div class="form-row">
    <label>Email *</label>
    [email* email placeholder "correo@ejemplo.com"]
</div>

<div class="form-row">
    <label>Teléfono *</label>
    [tel* telefono placeholder "+57 300 123 4567"]
</div>

<div class="form-row">
    <label>Ciudad</label>
    [text ciudad placeholder "Bogotá, Medellín, etc."]
</div>

<div class="form-row">
    <label>Tipo de consulta *</label>
    [select* tipo first_as_label "Seleccione una opción"
        "Subrogación de Arrendamiento"
        "Subrogación Comercial"
        "Subrogación Inmobiliaria"
        "Subrogación de Seguros"
        "Otro"]
</div>

<div class="form-row">
    <label>Mensaje *</label>
    [textarea* mensaje 40x4 maxlength:500 placeholder "Describa su situación..."]
</div>

<div class="form-row">
    [acceptance acepto] Acepto la <a href="/politica-privacidad">política de tratamiento de datos</a> [/acceptance]
</div>

<div class="form-row">
    [submit class:btn class:btn-primary "Enviar Consulta"]
</div>
```

#### Email Template:
```
Asunto: Nueva consulta desde Translatio - [tipo]

De: [nombre] <[email]>

---
NUEVA CONSULTA DESDE TRANSLATIO GLOBAL

Cliente: [nombre]
Email: [email]
Teléfono: [telefono]
Ciudad: [ciudad]
Tipo de consulta: [tipo]

Mensaje:
[mensaje]

---
Enviado desde: [url]
Fecha: [_date]
```

---

## 🔌 INTEGRACIÓN POLYLANG

### Strings a Registrar
```php
// Registrar strings para traducción
pll_register_string('translatio', 'Solicitar Consulta Gratuita', 'Translatio');
pll_register_string('translatio', 'Casos Exitosos', 'Translatio');
pll_register_string('translatio', 'Clientes Satisfechos', 'Translatio');
pll_register_string('translatio', 'Años de Experiencia', 'Translatio');
pll_register_string('translatio', 'Tasa de Éxito', 'Translatio');
pll_register_string('translatio', 'Leer más', 'Translatio');
pll_register_string('translatio', 'Ver todos los casos', 'Translatio');
pll_register_string('translatio', 'Contactar Ahora', 'Translatio');
pll_register_string('translatio', '¿Listo para Traspasar su Contrato?', 'Translatio');
```

### Menús por Idioma
```php
// Registrar ubicaciones de menú
register_nav_menus([
    'primary' => 'Menú Principal',
    'footer' => 'Menú Footer',
    'legal' => 'Menú Legal'
]);

// Crear menús para cada idioma
// ES: Inicio, Quiénes Somos, Proceso, Casos, Contacto
// EN: Home, About Us, Our Process, Success Cases, Contact
// PT: Início, Quem Somos, Nosso Processo, Casos de Sucesso, Contato
// ZH: 首页, 关于我们, 流程, 成功案例, 联系我们
// FR: Accueil, Qui Sommes-Nous, Notre Processus, Cas de Succès, Contact
```

---

## 📋 USER STORIES

### US-001: Visitante consulta servicios
**Como** visitante
**Quiero** ver los servicios de subrogación disponibles
**Para** entender qué ofrece Translatio

**Criterios de Aceptación:**
- [ ] Ver 3 servicios principales en home
- [ ] Cada servicio tiene icono, título, descripción
- [ ] Click lleva a detalle del servicio
- [ ] Contenido disponible en 5 idiomas

---

### US-002: Visitante lee casos de éxito
**Como** visitante
**Quiero** leer casos de éxito documentados
**Para** evaluar la experiencia de Translatio

**Criterios de Aceptación:**
- [ ] Ver listado de casos con imagen, título, resumen
- [ ] Poder filtrar por tipo (arrendamiento, comercial, inmobiliario)
- [ ] Click lleva a detalle completo del caso
- [ ] Cada caso muestra: cliente, problema, solución, resultados

---

### US-003: Cliente envía consulta
**Como** cliente potencial
**Quiero** enviar una consulta desde el formulario
**Para** recibir asesoría sobre mi caso

**Criterios de Aceptación:**
- [ ] Formulario con 7 campos (3 requeridos)
- [ ] Validación frontend y backend
- [ ] Email de confirmación al usuario
- [ ] Email de notificación al equipo Translatio
- [ ] Mensaje de éxito visible

---

### US-004: Visitante cambia idioma
**Como** visitante internacional
**Quiero** navegar en mi idioma preferido
**Para** entender los servicios

**Criterios de Aceptación:**
- [ ] Selector de idioma visible en header
- [ ] 5 idiomas disponibles (ES, EN, PT, ZH, FR)
- [ ] Navegación completa traducida
- [ ] URL cambia según idioma (/en/, /pt/, etc.)

---

### US-005: Admin gestiona testimonios
**Como** administrador
**Quiero** agregar/editar testimonios
**Para** mostrar experiencias de clientes

**Criterios de Aceptación:**
- [ ] CPT Testimonios en admin
- [ ] Campos: nombre, ciudad, cargo, calificación
- [ ] Imagen destacada (foto cliente)
- [ ] Polylang: crear traducciones
- [ ] Publicar/despublicar

---

### US-006: Admin gestiona casos de éxito
**Como** administrador
**Quiero** documentar casos de éxito
**Para** mostrar expertise

**Criterios de Aceptación:**
- [ ] CPT Casos de Éxito en admin
- [ ] Campos: cliente, tipo, duración, resultados (repeater)
- [ ] Checkbox "Destacado" para mostrar en home
- [ ] Polylang: traducir a 5 idiomas

---

### US-007: Visitante ve proceso
**Como** visitante
**Quiero** entender el proceso de subrogación
**Para** saber qué esperar

**Criterios de Aceptación:**
- [ ] Ver 5 pasos del proceso
- [ ] Cada paso: título, duración, actividades, entregable
- [ ] Timeline visual
- [ ] Indicador de que primera consulta es GRATUITA

---

### US-008: Visitante en móvil
**Como** visitante móvil
**Quiero** navegar fácilmente en mi celular
**Para** obtener información sobre la marcha

**Criterios de Aceptación:**
- [ ] Menú hamburguesa funcional
- [ ] Botón WhatsApp flotante
- [ ] Formulario usable en móvil
- [ ] Imágenes optimizadas (lazy loading)
- [ ] Tiempo de carga < 3s en 3G

---

## ✅ ENTREGABLES REQUIREMENTS

- [x] 3 CPT documentados con campos CMB2
- [x] Metaboxes por página definidos
- [x] Integración Polylang especificada
- [x] Formularios Contact Form 7 documentados
- [x] 8 User Stories con criterios de aceptación
- [x] Strings de traducción identificados

---

**Estado:** ✅ FASE 2 COMPLETADA
**Siguiente:** FASE 3 - ARCHITECT

---
*Requirements Agent - Molino Translatio Global*
