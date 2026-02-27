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
        'name'          => _x('Servicios', 'Post Type General Name', 'translatio'),
        'singular_name' => _x('Servicio', 'Post Type Singular Name', 'translatio'),
        'menu_name'     => __('Servicios', 'translatio'),
        'all_items'     => __('Todos los Servicios', 'translatio'),
    ];

    $args = [
        'label'               => __('Servicio', 'translatio'),
        'labels'              => $labels,
        'supports'            => ['title', 'editor', 'thumbnail'],
        'public'              => true,
        'show_ui'             => true,
        'menu_position'       => 27,
        'menu_icon'           => 'dashicons-portfolio',
        'has_archive'         => false,
        'show_in_rest'        => true,
    ];

    register_post_type('servicios', $args);
}
add_action('init', 'translatio_register_servicios_cpt', 0);

/**
 * Metaboxes CMB2 para Servicios
 */
function translatio_servicios_metaboxes() {
    $cmb = new_cmb2_box([
        'id'           => 'servicio_metabox',
        'title'        => __('Detalles del Servicio', 'translatio'),
        'object_types' => ['servicios'],
    ]);

    $cmb->add_field([
        'name'       => __('Descripción Corta', 'translatio'),
        'id'         => 'servicio_descripcion_corta',
        'type'       => 'textarea_small',
        'attributes' => ['maxlength' => 140],
    ]);

    $cmb->add_field([
        'name'       => __('Icono SVG', 'translatio'),
        'id'         => 'servicio_icono',
        'type'       => 'file',
        'options'    => ['url' => false],
    ]);

    $cmb->add_field([
        'name'    => __('Orden', 'translatio'),
        'id'      => 'servicio_orden',
        'type'    => 'text_small',
        'default' => '1',
    ]);

    $pasos_id = $cmb->add_field([
        'id'      => 'servicio_pasos',
        'type'    => 'group',
        'options' => [
            'group_title'   => __('Paso {#}', 'translatio'),
            'add_button'    => __('Agregar Paso', 'translatio'),
            'remove_button' => __('Eliminar', 'translatio'),
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
