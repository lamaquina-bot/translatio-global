# 🔧 FASE 5: BACKEND - Translatio Global

**Fecha:** 27 Feb 2026
**Agente:** Backend Agent
**Estado:** ✅ COMPLETADO

---

## 📦 CUSTOM POST TYPES CON CMB2

### cpt-testimonios.php
```php
<?php
/**
 * Custom Post Type: Testimonios
 * 
 * @package Translatio
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Registrar CPT Testimonios
 */
function translatio_register_testimonios_cpt() {
    $labels = [
        'name'                  => _x('Testimonios', 'Post Type General Name', 'translatio'),
        'singular_name'         => _x('Testimonio', 'Post Type Singular Name', 'translatio'),
        'menu_name'             => __('Testimonios', 'translatio'),
        'name_admin_bar'        => __('Testimonio', 'translatio'),
        'archives'              => __('Archivo de Testimonios', 'translatio'),
        'attributes'            => __('Atributos del Testimonio', 'translatio'),
        'parent_item_colon'     => __('Testimonio Padre:', 'translatio'),
        'all_items'             => __('Todos los Testimonios', 'translatio'),
        'add_new_item'          => __('Agregar Nuevo Testimonio', 'translatio'),
        'add_new'               => __('Agregar Nuevo', 'translatio'),
        'new_item'              => __('Nuevo Testimonio', 'translatio'),
        'edit_item'             => __('Editar Testimonio', 'translatio'),
        'update_item'           => __('Actualizar Testimonio', 'translatio'),
        'view_item'             => __('Ver Testimonio', 'translatio'),
        'view_items'            => __('Ver Testimonios', 'translatio'),
        'search_items'          => __('Buscar Testimonio', 'translatio'),
        'not_found'             => __('No encontrado', 'translatio'),
        'not_found_in_trash'    => __('No encontrado en Papelera', 'translatio'),
        'featured_image'        => __('Foto del Cliente', 'translatio'),
        'set_featured_image'    => __('Establecer foto', 'translatio'),
        'remove_featured_image' => __('Remover foto', 'translatio'),
        'use_featured_image'    => __('Usar como foto', 'translatio'),
    ];

    $args = [
        'label'                 => __('Testimonio', 'translatio'),
        'description'           => __('Testimonios de clientes', 'translatio'),
        'labels'                => $labels,
        'supports'              => ['title', 'editor', 'thumbnail'],
        'taxonomies'            => [],
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 25,
        'menu_icon'             => 'dashicons-format-quote',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => false,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => true,
        'publicly_queryable'    => false,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
    ];

    register_post_type('testimonios', $args);
}
add_action('init', 'translatio_register_testimonios_cpt', 0);

/**
 * Metaboxes CMB2 para Testimonios
 */
function translatio_testimonios_metaboxes() {
    $cmb = new_cmb2_box([
        'id'            => 'testimonio_metabox',
        'title'         => __('Datos del Cliente', 'translatio'),
        'object_types'  => ['testimonios'],
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true,
    ]);

    // Nombre del Cliente
    $cmb->add_field([
        'name'       => __('Nombre del Cliente', 'translatio'),
        'desc'       => __('Nombre completo del cliente', 'translatio'),
        'id'         => 'cliente_nombre',
        'type'       => 'text',
        'attributes' => [
            'required' => 'required',
        ],
    ]);

    // Ciudad
    $cmb->add_field([
        'name'       => __('Ciudad', 'translatio'),
        'desc'       => __('Ciudad de residencia', 'translatio'),
        'id'         => 'cliente_ciudad',
        'type'       => 'text',
    ]);

    // Cargo/Empresa
    $cmb->add_field([
        'name'       => __('Cargo / Empresa', 'translatio'),
        'desc'       => __('Opcional', 'translatio'),
        'id'         => 'cliente_cargo',
        'type'       => 'text',
    ]);

    // Calificación
    $cmb->add_field([
        'name'             => __('Calificación', 'translatio'),
        'desc'             => __('Calificación del 1 al 5', 'translatio'),
        'id'               => 'calificacion',
        'type'             => 'select',
        'show_option_none' => false,
        'default'          => '5',
        'options'          => [
            '5' => __('⭐⭐⭐⭐⭐ (5) - Excelente', 'translatio'),
            '4' => __('⭐⭐⭐⭐ (4) - Muy Bueno', 'translatio'),
            '3' => __('⭐⭐⭐ (3) - Bueno', 'translatio'),
            '2' => __('⭐⭐ (2) - Regular', 'translatio'),
            '1' => __('⭐ (1) - Malo', 'translatio'),
        ],
    ]);

    // Fecha del Testimonio
    $cmb->add_field([
        'name'        => __('Fecha del Testimonio', 'translatio'),
        'desc'        => __('Fecha aproximada del servicio', 'translatio'),
        'id'          => 'fecha_testimonio',
        'type'        => 'text_date',
        'date_format' => 'Y-m-d',
    ]);

    // Destacado
    $cmb->add_field([
        'name' => __('Mostrar en Home', 'translatio'),
        'desc' => __('Marcar para mostrar en la página de inicio', 'translatio'),
        'id'   => 'testimonio_destacado',
        'type' => 'checkbox',
    ]);
}
add_action('cmb2_admin_init', 'translatio_testimonios_metaboxes');
```

### cpt-casos.php
```php
<?php
/**
 * Custom Post Type: Casos de Éxito
 * 
 * @package Translatio
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Registrar CPT Casos de Éxito
 */
function translatio_register_casos_cpt() {
    $labels = [
        'name'                  => _x('Casos de Éxito', 'Post Type General Name', 'translatio'),
        'singular_name'         => _x('Caso de Éxito', 'Post Type Singular Name', 'translatio'),
        'menu_name'             => __('Casos de Éxito', 'translatio'),
        'name_admin_bar'        => __('Caso de Éxito', 'translatio'),
        'archives'              => __('Archivo de Casos', 'translatio'),
        'attributes'            => __('Atributos del Caso', 'translatio'),
        'parent_item_colon'     => __('Caso Padre:', 'translatio'),
        'all_items'             => __('Todos los Casos', 'translatio'),
        'add_new_item'          => __('Agregar Nuevo Caso', 'translatio'),
        'add_new'               => __('Agregar Nuevo', 'translatio'),
        'new_item'              => __('Nuevo Caso', 'translatio'),
        'edit_item'             => __('Editar Caso', 'translatio'),
        'update_item'           => __('Actualizar Caso', 'translatio'),
        'view_item'             => __('Ver Caso', 'translatio'),
        'view_items'            => __('Ver Casos', 'translatio'),
        'search_items'          => __('Buscar Caso', 'translatio'),
        'not_found'             => __('No encontrado', 'translatio'),
        'not_found_in_trash'    => __('No encontrado en Papelera', 'translatio'),
        'featured_image'        => __('Imagen del Caso', 'translatio'),
        'set_featured_image'    => __('Establecer imagen', 'translatio'),
        'remove_featured_image' => __('Remover imagen', 'translatio'),
        'use_featured_image'    => __('Usar como imagen', 'translatio'),
    ];

    $args = [
        'label'                 => __('Caso de Éxito', 'translatio'),
        'description'           => __('Casos de éxito documentados', 'translatio'),
        'labels'                => $labels,
        'supports'              => ['title', 'editor', 'thumbnail', 'excerpt'],
        'taxonomies'            => [],
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 26,
        'menu_icon'             => 'dashicons-awards',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => true,
        'can_export'            => true,
        'has_archive'           => true,
        'exclude_from_search'   => false,
        'publicly_queryable'    => true,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
        'rewrite'               => ['slug' => 'casos-exito'],
    ];

    register_post_type('casos_exito', $args);
}
add_action('init', 'translatio_register_casos_cpt', 0);

/**
 * Metaboxes CMB2 para Casos de Éxito
 */
function translatio_casos_metaboxes() {
    $cmb = new_cmb2_box([
        'id'            => 'caso_metabox',
        'title'         => __('Detalles del Caso', 'translatio'),
        'object_types'  => ['casos_exito'],
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true,
    ]);

    // Cliente
    $cmb->add_field([
        'name'       => __('Cliente', 'translatio'),
        'desc'       => __('Nombre o descripción del cliente', 'translatio'),
        'id'         => 'caso_cliente',
        'type'       => 'text',
    ]);

    // Tipo de Caso
    $cmb->add_field([
        'name'             => __('Tipo de Caso', 'translatio'),
        'desc'             => __('Categoría del caso', 'translatio'),
        'id'               => 'caso_tipo',
        'type'             => 'select',
        'show_option_none' => true,
        'options'          => [
            'arrendamiento' => __('Arrendamiento', 'translatio'),
            'comercial'     => __('Comercial', 'translatio'),
            'inmobiliario'  => __('Inmobiliario', 'translatio'),
        ],
    ]);

    // Fecha del Caso
    $cmb->add_field([
        'name'        => __('Fecha de Resolución', 'translatio'),
        'desc'        => __('Fecha en que se completó el caso', 'translatio'),
        'id'          => 'caso_fecha',
        'type'        => 'text_date',
        'date_format' => 'Y-m-d',
    ]);

    // Duración
    $cmb->add_field([
        'name'       => __('Duración (semanas)', 'translatio'),
        'desc'       => __('Tiempo que tomó resolver el caso', 'translatio'),
        'id'         => 'caso_duracion',
        'type'       => 'text_small',
        'attributes' => [
            'type'    => 'number',
            'min'     => '1',
        ],
    ]);

    // Resultados (Repeater)
    $group_id = $cmb->add_field([
        'id'          => 'caso_resultados',
        'type'        => 'group',
        'description' => __('Resultados obtenidos', 'translatio'),
        'options'     => [
            'group_title'    => __('Resultado {#}', 'translatio'),
            'add_button'     => __('Agregar Resultado', 'translatio'),
            'remove_button'  => __('Eliminar', 'translatio'),
            'sortable'       => true,
        ],
    ]);

    $cmb->add_group_field($group_id, [
        'name' => __('Resultado', 'translatio'),
        'id'   => 'resultado_texto',
        'type' => 'text',
    ]);

    // Destacado
    $cmb->add_field([
        'name' => __('Caso Destacado', 'translatio'),
        'desc' => __('Mostrar en la página de inicio', 'translatio'),
        'id'   => 'caso_destacado',
        'type' => 'checkbox',
    ]);
}
add_action('cmb2_admin_init', 'translatio_casos_metaboxes');
```

### cpt-servicios.php
```php
<?php
/**
 * Custom Post Type: Servicios
 * 
 * @package Translatio
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Registrar CPT Servicios
 */
function translatio_register_servicios_cpt() {
    $labels = [
        'name'                  => _x('Servicios', 'Post Type General Name', 'translatio'),
        'singular_name'         => _x('Servicio', 'Post Type Singular Name', 'translatio'),
        'menu_name'             => __('Servicios', 'translatio'),
        'name_admin_bar'        => __('Servicio', 'translatio'),
        'archives'              => __('Archivo de Servicios', 'translatio'),
        'all_items'             => __('Todos los Servicios', 'translatio'),
        'add_new_item'          => __('Agregar Nuevo Servicio', 'translatio'),
        'add_new'               => __('Agregar Nuevo', 'translatio'),
        'new_item'              => __('Nuevo Servicio', 'translatio'),
        'edit_item'             => __('Editar Servicio', 'translatio'),
        'update_item'           => __('Actualizar Servicio', 'translatio'),
        'view_item'             => __('Ver Servicio', 'translatio'),
        'search_items'          => __('Buscar Servicio', 'translatio'),
        'not_found'             => __('No encontrado', 'translatio'),
        'not_found_in_trash'    => __('No encontrado en Papelera', 'translatio'),
        'featured_image'        => __('Imagen del Servicio', 'translatio'),
    ];

    $args = [
        'label'                 => __('Servicio', 'translatio'),
        'description'           => __('Servicios de subrogación', 'translatio'),
        'labels'                => $labels,
        'supports'              => ['title', 'editor', 'thumbnail'],
        'taxonomies'            => [],
        'hierarchical'          => false,
        'public'                => true,
        'show_ui'               => true,
        'show_in_menu'          => true,
        'menu_position'         => 27,
        'menu_icon'             => 'dashicons-portfolio',
        'show_in_admin_bar'     => true,
        'show_in_nav_menus'     => false,
        'can_export'            => true,
        'has_archive'           => false,
        'exclude_from_search'   => true,
        'publicly_queryable'    => false,
        'capability_type'       => 'post',
        'show_in_rest'          => true,
    ];

    register_post_type('servicios', $args);
}
add_action('init', 'translatio_register_servicios_cpt', 0);

/**
 * Metaboxes CMB2 para Servicios
 */
function translatio_servicios_metaboxes() {
    $cmb = new_cmb2_box([
        'id'            => 'servicio_metabox',
        'title'         => __('Detalles del Servicio', 'translatio'),
        'object_types'  => ['servicios'],
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true,
    ]);

    // Descripción Corta
    $cmb->add_field([
        'name'       => __('Descripción Corta', 'translatio'),
        'desc'       => __('Breve descripción para cards (máx 140 caracteres)', 'translatio'),
        'id'         => 'servicio_descripcion_corta',
        'type'       => 'textarea_small',
        'attributes' => [
            'maxlength' => 140,
        ],
    ]);

    // Icono SVG
    $cmb->add_field([
        'name'         => __('Icono', 'translatio'),
        'desc'         => __('Icono SVG del servicio', 'translatio'),
        'id'           => 'servicio_icono',
        'type'         => 'file',
        'options'      => [
            'url' => false,
        ],
        'query_args'   => [
            'type' => [
                'image/svg+xml',
            ],
        ],
        'preview_size' => 'thumbnail',
    ]);

    // Orden
    $cmb->add_field([
        'name'       => __('Orden', 'translatio'),
        'desc'       => __('Posición en listados (1 = primero)', 'translatio'),
        'id'         => 'servicio_orden',
        'type'       => 'text_small',
        'default'    => '1',
        'attributes' => [
            'type' => 'number',
            'min'  => '1',
        ],
    ]);

    // Pasos del Servicio (Repeater)
    $pasos_id = $cmb->add_field([
        'id'          => 'servicio_pasos',
        'type'        => 'group',
        'description' => __('Pasos del proceso del servicio', 'translatio'),
        'options'     => [
            'group_title'    => __('Paso {#}', 'translatio'),
            'add_button'     => __('Agregar Paso', 'translatio'),
            'remove_button'  => __('Eliminar', 'translatio'),
            'sortable'       => true,
        ],
    ]);

    $cmb->add_group_field($pasos_id, [
        'name' => __('Título del Paso', 'translatio'),
        'id'   => 'paso_titulo',
        'type' => 'text',
    ]);

    $cmb->add_group_field($pasos_id, [
        'name' => __('Descripción', 'translatio'),
        'id'   => 'paso_descripcion',
        'type' => 'textarea_small',
    ]);
}
add_action('cmb2_admin_init', 'translatio_servicios_metaboxes');
```

---

## 📧 FORMULARIOS CONTACT FORM 7

### contact-forms.php
```php
<?php
/**
 * Contact Form 7 Forms
 * 
 * @package Translatio
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Crear formularios CF7 al activar tema
 */
function translatio_create_contact_forms() {
    // Verificar si CF7 está activo
    if (!class_exists('WPCF7_ContactForm')) {
        return;
    }

    // Formulario de Contacto General
    $form_title = 'Contacto General';
    
    // Verificar si ya existe
    $existing = get_posts([
        'post_type'      => 'wpcf7_contact_form',
        'title'          => $form_title,
        'post_status'    => 'publish',
        'posts_per_page' => 1,
    ]);

    if (!empty($existing)) {
        return;
    }

    // Crear formulario
    $form_content = '
<div class="form-group">
    [text* nombre class:form-control placeholder "Nombre completo *"]
</div>

<div class="form-group">
    [email* email class:form-control placeholder "Email *"]
</div>

<div class="form-group">
    [tel* telefono class:form-control placeholder "Teléfono *"]
</div>

<div class="form-group">
    [text ciudad class:form-control placeholder "Ciudad"]
</div>

<div class="form-group">
    [select* tipo class:form-control first_as_label "Tipo de consulta *" 
        "Subrogación de Arrendamiento" 
        "Subrogación Comercial" 
        "Subrogación Inmobiliaria" 
        "Subrogación de Seguros" 
        "Otro"]
</div>

<div class="form-group">
    [textarea* mensaje class:form-control 40x4 maxlength:500 placeholder "Mensaje (máx 500 caracteres) *"]
</div>

<div class="form-group form-check">
    [acceptance acepto class:form-check-input] <label class="form-check-label">Acepto la <a href="/politica-privacidad" target="_blank">política de tratamiento de datos</a></label> [/acceptance]
</div>

<div class="form-group">
    [submit class:btn class:btn-primary class:w-100 "Enviar Consulta"]
</div>';

    // Email template
    $mail_template = [
        'active'     => true,
        'subject'    => 'Nueva consulta desde Translatio - [tipo]',
        'sender'     => '[nombre] <[email]>',
        'recipient'  => get_option('admin_email'),
        'body'       => '
NUEVA CONSULTA DESDE TRANSLATIO GLOBAL
=====================================

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
Hora: [_time]
IP: [_remote_ip]',
        'additional_headers' => 'Reply-To: [email]',
        'attachments'        => '',
        'use_html'           => false,
    ];

    // Email de confirmación al usuario
    $mail_2_template = [
        'active'     => true,
        'subject'    => 'Hemos recibido tu consulta - Translatio',
        'sender'     => 'Translatio Global <noreply@translatioglobal.com>',
        'recipient'  => '[email]',
        'body'       => 'Hola [nombre],

Gracias por contactar a Translatio Global. Hemos recibido tu consulta sobre [tipo].

Nuestro equipo revisará tu mensaje y se pondrá en contacto contigo en las próximas 24 horas hábiles.

Si tu consulta es urgente, puedes llamarnos al +57 (601) 234-5678 o escribirnos por WhatsApp al +57 320 123 4567.

Saludos cordiales,
Equipo Translatio Global

---
Este es un email automático, por favor no respondas a este mensaje.',
        'additional_headers' => '',
        'attachments'        => '',
        'use_html'           => false,
    ];

    // Crear el post del formulario
    $form_id = wp_insert_post([
        'post_title'   => $form_title,
        'post_type'    => 'wpcf7_contact_form',
        'post_status'  => 'publish',
        'post_content' => $form_content,
    ]);

    if (!is_wp_error($form_id)) {
        // Guardar configuración de mail
        update_post_meta($form_id, '_mail', $mail_template);
        update_post_meta($form_id, '_mail_2', $mail_2_template);
        update_post_meta($form_id, '_messages', [
            'mail_sent_ok'     => 'Gracias por tu mensaje. Ha sido enviado.',
            'mail_sent_ng'     => 'Hubo un error al enviar el mensaje. Por favor intenta más tarde.',
            'validation_error' => 'Uno o más campos tienen errores. Por favor revisa e intenta de nuevo.',
            'spam'             => 'Hubo un error al enviar el mensaje. Por favor intenta más tarde.',
            'accept_terms'     => 'Debes aceptar los términos y condiciones antes de enviar.',
            'invalid_required' => 'Este campo es requerido.',
            'invalid_too_long' => 'Este campo es demasiado largo.',
            'invalid_email'    => 'La dirección de email no es válida.',
        ]);

        // Guardar shortcodes adicionales
        update_post_meta($form_id, '_form', $form_content);
    }
}
add_action('after_switch_theme', 'translatio_create_contact_forms');
```

---

## 🗄️ ESTRUCTURA DE BASE DE DATOS

### Tablas WordPress Core
- `wp_posts` - Páginas, posts, CPTs
- `wp_postmeta` - Metadatos (campos CMB2)
- `wp_options` - Configuraciones
- `wp_terms` - Categorías, tags
- `wp_term_taxonomy` - Taxonomías
- `wp_term_relationships` - Relaciones

### Tablas Polylang
- `wp_pll_languages` - Idiomas configurados
- `wp_pll_terms_translations` - Traducciones de términos
- `wp_pll_posts_translations` - Traducciones de posts

### Tablas Contact Form 7
- `wp_posts` (post_type = 'wpcf7_contact_form')
- `wp_postmeta` (configuración de formularios)

### Tablas Personalizadas (si necesario)
```sql
-- Tabla para guardar envíos de formularios (opcional)
CREATE TABLE IF NOT EXISTS wp_translatio_submissions (
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    form_id BIGINT(20) UNSIGNED NOT NULL,
    post_id BIGINT(20) UNSIGNED,
    data LONGTEXT,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    ip_address VARCHAR(45),
    user_agent VARCHAR(255),
    PRIMARY KEY (id),
    KEY form_id (form_id),
    KEY created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
```

---

## 🔧 CONFIGURACIÓN POLYLANG

### polylang-config.php
```php
<?php
/**
 * Configuración de Polylang
 * 
 * @package Translatio
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Registrar CPTs y taxonomías para traducción
 */
function translatio_polylang_support() {
    if (!function_exists('pll_get_post_types')) {
        return;
    }

    // Agregar CPTs a Polylang
    add_filter('pll_get_post_types', function($post_types) {
        $post_types['casos_exito'] = 'casos_exito';
        return $post_types;
    });

    // Testimonios y Servicios NO se traducen (solo contenido)
    // Pero sus campos de texto sí
}
add_action('init', 'translatio_polylang_support', 20);

/**
 * Registrar strings para traducción
 */
function translatio_register_pll_strings() {
    if (!function_exists('pll_register_string')) {
        return;
    }

    $strings = [
        // CTAs
        'Solicitar Consulta Gratuita',
        'Contactar Ahora',
        'Ver más',
        'Leer más',
        
        // Labels
        'Casos Exitosos',
        'Clientes Satisfechos',
        'Años de Experiencia',
        'Tasa de Éxito',
        
        // Secciones
        '¿Qué Hacemos?',
        '¿Por Qué Elegir Translatio?',
        'Nuestros Números Hablan',
        'Testimonios Destacados',
        
        // Formularios
        'Nombre completo',
        'Email',
        'Teléfono',
        'Ciudad',
        'Tipo de consulta',
        'Mensaje',
        'Enviar Consulta',
    ];

    foreach ($strings as $string) {
        pll_register_string('translatio', $string, 'Translatio Theme');
    }
}
add_action('after_setup_theme', 'translatio_register_pll_strings');

/**
 * Helper para obtener string traducido
 */
function translatio__($string) {
    if (function_exists('pll__')) {
        return pll__($string);
    }
    return __($string, 'translatio');
}

/**
 * Helper para echo de string traducido
 */
function translatio_e($string) {
    echo translatio__($string);
}
```

---

## ✅ ENTREGABLES BACKEND

- [x] CPT Testimonios con CMB2
- [x] CPT Casos de Éxito con CMB2
- [x] CPT Servicios con CMB2
- [x] Formularios Contact Form 7
- [x] Estructura de DB documentada
- [x] Configuración Polylang

---

**Estado:** ✅ FASE 5 COMPLETADA
**Siguiente:** FASE 6 - FRONTEND

---
*Backend Agent - Molino Translatio Global*
