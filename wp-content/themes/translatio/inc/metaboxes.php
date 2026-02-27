<?php
/**
 * Metaboxes para páginas con CMB2
 * 
 * @package Translatio
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Metaboxes para Home Page
 */
function translatio_home_metaboxes() {
    $cmb = new_cmb2_box([
        'id'           => 'home_metabox',
        'title'        => __('Contenido de la Página de Inicio', 'translatio'),
        'object_types' => ['page'],
        'show_on'      => ['key' => 'page-template', 'value' => 'templates/front-page.php'],
    ]);

    // Hero Section
    $cmb->add_field([
        'name' => '— HERO SECTION —',
        'id'   => 'hero_title',
        'type' => 'title',
    ]);

    $cmb->add_field([
        'name'    => __('Título Principal', 'translatio'),
        'id'      => 'hero_titulo',
        'type'    => 'text',
        'default' => 'Subrogación Legal con Confianza Global',
    ]);

    $cmb->add_field([
        'name' => __('Subtítulo', 'translatio'),
        'id'   => 'hero_subtitulo',
        'type' => 'textarea_small',
    ]);

    $cmb->add_field([
        'name'    => __('Texto del Botón CTA', 'translatio'),
        'id'      => 'hero_cta_texto',
        'type'    => 'text',
        'default' => 'Solicitar Consulta Gratuita',
    ]);

    $cmb->add_field([
        'name' => __('URL del Botón CTA', 'translatio'),
        'id'   => 'hero_cta_url',
        'type' => 'text_url',
    ]);

    $cmb->add_field([
        'name'    => __('Imagen de Fondo', 'translatio'),
        'id'      => 'hero_imagen',
        'type'    => 'file',
        'options' => ['url' => false],
    ]);

    // Estadísticas
    $cmb->add_field([
        'name' => '— ESTADÍSTICAS —',
        'id'   => 'stats_title',
        'type' => 'title',
    ]);

    $stats_id = $cmb->add_field([
        'id'      => 'estadisticas',
        'type'    => 'group',
        'options' => [
            'group_title'   => __('Estadística {#}', 'translatio'),
            'add_button'    => __('Agregar', 'translatio'),
            'remove_button' => __('Eliminar', 'translatio'),
        ],
    ]);

    $cmb->add_group_field($stats_id, [
        'name' => __('Valor', 'translatio'),
        'id'   => 'stat_valor',
        'type' => 'text',
    ]);

    $cmb->add_group_field($stats_id, [
        'name' => __('Etiqueta', 'translatio'),
        'id'   => 'stat_etiqueta',
        'type' => 'text',
    ]);

    // Testimonios Destacados
    $cmb->add_field([
        'name' => '— TESTIMONIOS DESTACADOS —',
        'id'   => 'testimonios_title',
        'type' => 'title',
    ]);

    $cmb->add_field([
        'name'       => __('Testimonio 1', 'translatio'),
        'id'         => 'testimonio_1',
        'type'       => 'select',
        'options_cb' => 'translatio_get_testimonios_options',
    ]);

    $cmb->add_field([
        'name'       => __('Testimonio 2', 'translatio'),
        'id'         => 'testimonio_2',
        'type'       => 'select',
        'options_cb' => 'translatio_get_testimonios_options',
    ]);

    // CTA Final
    $cmb->add_field([
        'name' => '— CTA FINAL —',
        'id'   => 'cta_title',
        'type' => 'title',
    ]);

    $cmb->add_field([
        'name'    => __('Título CTA', 'translatio'),
        'id'      => 'cta_titulo',
        'type'    => 'text',
        'default' => '¿Listo para Traspasar su Contrato?',
    ]);

    $cmb->add_field([
        'name' => __('Texto CTA', 'translatio'),
        'id'   => 'cta_texto',
        'type' => 'textarea_small',
    ]);

    $cmb->add_field([
        'name'    => __('Texto del Botón', 'translatio'),
        'id'      => 'cta_boton_texto',
        'type'    => 'text',
        'default' => 'Contactar Ahora',
    ]);

    $cmb->add_field([
        'name' => __('URL del Botón', 'translatio'),
        'id'   => 'cta_boton_url',
        'type' => 'text_url',
    ]);
}
add_action('cmb2_admin_init', 'translatio_home_metaboxes');

/**
 * Metaboxes para Quiénes Somos
 */
function translatio_quienes_metaboxes() {
    $cmb = new_cmb2_box([
        'id'           => 'quienes_metabox',
        'title'        => __('Contenido de Quiénes Somos', 'translatio'),
        'object_types' => ['page'],
        'show_on'      => ['key' => 'page-template', 'value' => 'templates/page-quienes.php'],
    ]);

    // Misión
    $cmb->add_field([
        'name' => __('Título Misión', 'translatio'),
        'id'   => 'mision_titulo',
        'type' => 'text',
    ]);

    $cmb->add_field([
        'name' => __('Texto Misión', 'translatio'),
        'id'   => 'mision_texto',
        'type' => 'wysiwyg',
    ]);

    // Visión
    $cmb->add_field([
        'name' => __('Título Visión', 'translatio'),
        'id'   => 'vision_titulo',
        'type' => 'text',
    ]);

    $cmb->add_field([
        'name' => __('Texto Visión', 'translatio'),
        'id'   => 'vision_texto',
        'type' => 'wysiwyg',
    ]);

    // Valores
    $valores_id = $cmb->add_field([
        'id'      => 'valores',
        'type'    => 'group',
        'options' => [
            'group_title'   => __('Valor {#}', 'translatio'),
            'add_button'    => __('Agregar Valor', 'translatio'),
            'remove_button' => __('Eliminar', 'translatio'),
        ],
    ]);

    $cmb->add_group_field($valores_id, [
        'name' => __('Icono (dashicon)', 'translatio'),
        'id'   => 'valor_icono',
        'type' => 'text',
        'desc' => 'Ejemplo: dashicons-shield',
    ]);

    $cmb->add_group_field($valores_id, [
        'name' => __('Título', 'translatio'),
        'id'   => 'valor_titulo',
        'type' => 'text',
    ]);

    $cmb->add_group_field($valores_id, [
        'name' => __('Descripción', 'translatio'),
        'id'   => 'valor_descripcion',
        'type' => 'textarea_small',
    ]);
}
add_action('cmb2_admin_init', 'translatio_quienes_metaboxes');

/**
 * Metaboxes para Proceso
 */
function translatio_proceso_metaboxes() {
    $cmb = new_cmb2_box([
        'id'           => 'proceso_metabox',
        'title'        => __('Pasos del Proceso', 'translatio'),
        'object_types' => ['page'],
        'show_on'      => ['key' => 'page-template', 'value' => 'templates/page-proceso.php'],
    ]);

    $pasos_id = $cmb->add_field([
        'id'      => 'proceso_pasos',
        'type'    => 'group',
        'options' => [
            'group_title'   => __('Paso {#}', 'translatio'),
            'add_button'    => __('Agregar Paso', 'translatio'),
            'remove_button' => __('Eliminar', 'translatio'),
            'sortable'      => true,
        ],
    ]);

    $cmb->add_group_field($pasos_id, [
        'name' => __('Número', 'translatio'),
        'id'   => 'paso_numero',
        'type' => 'text_small',
    ]);

    $cmb->add_group_field($pasos_id, [
        'name' => __('Título', 'translatio'),
        'id'   => 'paso_titulo',
        'type' => 'text',
    ]);

    $cmb->add_group_field($pasos_id, [
        'name' => __('Duración', 'translatio'),
        'id'   => 'paso_duracion',
        'type' => 'text',
    ]);

    $cmb->add_group_field($pasos_id, [
        'name' => __('Descripción', 'translatio'),
        'id'   => 'paso_descripcion',
        'type' => 'textarea',
    ]);

    $cmb->add_group_field($pasos_id, [
        'name' => __('Icono', 'translatio'),
        'id'   => 'paso_icono',
        'type' => 'text',
    ]);

    $cmb->add_group_field($pasos_id, [
        'name' => __('Actividades (una por línea)', 'translatio'),
        'id'   => 'paso_actividades',
        'type' => 'textarea',
    ]);

    $cmb->add_group_field($pasos_id, [
        'name' => __('Entregable', 'translatio'),
        'id'   => 'paso_entregable',
        'type' => 'text',
    ]);
}
add_action('cmb2_admin_init', 'translatio_proceso_metaboxes');

/**
 * Metaboxes para Contacto
 */
function translatio_contacto_metaboxes() {
    $cmb = new_cmb2_box([
        'id'           => 'contacto_metabox',
        'title'        => __('Información de Contacto', 'translatio'),
        'object_types' => ['page'],
        'show_on'      => ['key' => 'page-template', 'value' => 'templates/page-contacto.php'],
    ]);

    $cmb->add_field([
        'name' => __('Teléfono', 'translatio'),
        'id'   => 'contacto_telefono',
        'type' => 'text',
    ]);

    $cmb->add_field([
        'name' => __('WhatsApp', 'translatio'),
        'id'   => 'contacto_whatsapp',
        'type' => 'text',
    ]);

    $cmb->add_field([
        'name' => __('Email', 'translatio'),
        'id'   => 'contacto_email',
        'type' => 'text_email',
    ]);

    $cmb->add_field([
        'name' => __('Dirección', 'translatio'),
        'id'   => 'contacto_direccion',
        'type' => 'textarea_small',
    ]);

    $cmb->add_field([
        'name' => __('Horarios', 'translatio'),
        'id'   => 'contacto_horarios',
        'type' => 'text',
    ]);

    $cmb->add_field([
        'name' => __('Google Maps Embed URL', 'translatio'),
        'id'   => 'contacto_mapa',
        'type' => 'textarea_code',
    ]);

    // Redes Sociales
    $cmb->add_field([
        'name' => '— REDES SOCIALES —',
        'id'   => 'social_title',
        'type' => 'title',
    ]);

    $cmb->add_field([
        'name' => __('LinkedIn', 'translatio'),
        'id'   => 'social_linkedin',
        'type' => 'text_url',
    ]);

    $cmb->add_field([
        'name' => __('Facebook', 'translatio'),
        'id'   => 'social_facebook',
        'type' => 'text_url',
    ]);

    $cmb->add_field([
        'name' => __('Instagram', 'translatio'),
        'id'   => 'social_instagram',
        'type' => 'text_url',
    ]);

    $cmb->add_field([
        'name' => __('YouTube', 'translatio'),
        'id'   => 'social_youtube',
        'type' => 'text_url',
    ]);

    // Formulario
    $cmb->add_field([
        'name'       => __('Formulario CF7', 'translatio'),
        'id'         => 'contacto_form',
        'type'       => 'select',
        'options_cb' => 'translatio_get_cf7_forms',
    ]);
}
add_action('cmb2_admin_init', 'translatio_contacto_metaboxes');

/**
 * Helper: Obtener testimonios para select
 */
function translatio_get_testimonios_options() {
    $args = [
        'post_type'      => 'testimonios',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
    ];
    
    $testimonios = get_posts($args);
    $options = ['' => '-- Seleccionar --'];
    
    foreach ($testimonios as $t) {
        $options[$t->ID] = $t->post_title;
    }
    
    return $options;
}

/**
 * Helper: Obtener formularios CF7
 */
function translatio_get_cf7_forms() {
    if (!class_exists('WPCF7_ContactForm')) {
        return ['' => 'CF7 no está activo'];
    }
    
    $forms = get_posts([
        'post_type'      => 'wpcf7_contact_form',
        'posts_per_page' => -1,
    ]);
    
    $options = ['' => '-- Seleccionar --'];
    
    foreach ($forms as $form) {
        $options[$form->ID] = $form->post_title;
    }
    
    return $options;
}
