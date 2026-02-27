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
        'archives'              => __('Archivo de Casos', 'translatio'),
        'all_items'             => __('Todos los Casos', 'translatio'),
        'add_new_item'          => __('Agregar Nuevo Caso', 'translatio'),
        'edit_item'             => __('Editar Caso', 'translatio'),
        'featured_image'        => __('Imagen del Caso', 'translatio'),
    ];

    $args = [
        'label'               => __('Caso de Éxito', 'translatio'),
        'labels'              => $labels,
        'supports'            => ['title', 'editor', 'thumbnail', 'excerpt'],
        'public'              => true,
        'show_ui'             => true,
        'menu_position'       => 26,
        'menu_icon'           => 'dashicons-awards',
        'has_archive'         => true,
        'show_in_rest'        => true,
        'rewrite'             => ['slug' => 'casos-exito'],
    ];

    register_post_type('casos_exito', $args);
}
add_action('init', 'translatio_register_casos_cpt', 0);

/**
 * Metaboxes CMB2 para Casos
 */
function translatio_casos_metaboxes() {
    $cmb = new_cmb2_box([
        'id'           => 'caso_metabox',
        'title'        => __('Detalles del Caso', 'translatio'),
        'object_types' => ['casos_exito'],
    ]);

    $cmb->add_field([
        'name' => __('Cliente', 'translatio'),
        'id'   => 'caso_cliente',
        'type' => 'text',
    ]);

    $cmb->add_field([
        'name'    => __('Tipo de Caso', 'translatio'),
        'id'      => 'caso_tipo',
        'type'    => 'select',
        'options' => [
            'arrendamiento' => __('Arrendamiento', 'translatio'),
            'comercial'     => __('Comercial', 'translatio'),
            'inmobiliario'  => __('Inmobiliario', 'translatio'),
        ],
    ]);

    $cmb->add_field([
        'name' => __('Duración (semanas)', 'translatio'),
        'id'   => 'caso_duracion',
        'type' => 'text_small',
    ]);

    $group_id = $cmb->add_field([
        'id'      => 'caso_resultados',
        'type'    => 'group',
        'options' => [
            'group_title'   => __('Resultado {#}', 'translatio'),
            'add_button'    => __('Agregar Resultado', 'translatio'),
            'remove_button' => __('Eliminar', 'translatio'),
        ],
    ]);

    $cmb->add_group_field($group_id, [
        'name' => __('Resultado', 'translatio'),
        'id'   => 'resultado_texto',
        'type' => 'text',
    ]);

    $cmb->add_field([
        'name' => __('Destacado en Home', 'translatio'),
        'id'   => 'caso_destacado',
        'type' => 'checkbox',
    ]);
}
add_action('cmb2_admin_init', 'translatio_casos_metaboxes');
