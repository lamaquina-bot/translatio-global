<?php
/**
 * Translatio Global - Tema WordPress
 * 
 * @package Translatio
 * @version 1.0.0
 * @author Molino Development Team
 * @license GPL-2.0+
 */

if (!defined('ABSPATH')) {
    exit;
}

// Definir constantes del tema
define('TRANSLATIO_VERSION', '1.0.0');
define('TRANSLATIO_DIR', get_template_directory());
define('TRANSLATIO_URI', get_template_directory_uri());

/**
 * Setup del tema
 */
function translatio_theme_setup() {
    // Soporte para título
    add_theme_support('title-tag');
    
    // Soporte para imágenes destacadas
    add_theme_support('post-thumbnails');
    
    // Soporte para HTML5
    add_theme_support('html5', [
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script'
    ]);
    
    // Soporte para logo personalizado
    add_theme_support('custom-logo', [
        'height'      => 100,
        'width'       => 300,
        'flex-height' => true,
        'flex-width'  => true,
    ]);
    
    // Soporte para feed RSS
    add_theme_support('automatic-feed-links');
    
    // Tamaños de imagen personalizados
    add_image_size('hero', 1920, 1080, true);
    add_image_size('card-large', 600, 400, true);
    add_image_size('card-medium', 400, 300, true);
    add_image_size('card-small', 300, 200, true);
    add_image_size('testimonial', 150, 150, true);
    
    // Registrar menús
    register_nav_menus([
        'primary' => __('Menú Principal', 'translatio'),
        'footer'  => __('Menú Footer', 'translatio'),
        'legal'   => __('Menú Legal', 'translatio'),
    ]);
    
    // Cargar textdomain
    load_theme_textdomain('translatio', TRANSLATIO_DIR . '/languages');
}
add_action('after_setup_theme', 'translatio_theme_setup');

/**
 * Cargar archivos del tema
 */
$translatio_includes = [
    '/inc/setup.php',
    '/inc/enqueue.php',
    '/inc/customizer.php',
    '/inc/cpt-testimonios.php',
    '/inc/cpt-casos.php',
    '/inc/cpt-servicios.php',
    '/inc/metaboxes.php',
    '/inc/polylang.php',
    '/inc/helpers.php',
    '/inc/ajax.php',
];

foreach ($translatio_includes as $file) {
    $filepath = TRANSLATIO_DIR . $file;
    if (file_exists($filepath)) {
        require_once $filepath;
    }
}

/**
 * Cargar CMB2 (si no está como plugin)
 */
if (!class_exists('CMB2') && file_exists(TRANSLATIO_DIR . '/inc/cmb2/init.php')) {
    require_once TRANSLATIO_DIR . '/inc/cmb2/init.php';
}

/**
 * Funciones auxiliares globales
 */

// Obtener opción del tema
function translatio_get_option($key, $default = '') {
    return get_theme_mod($key, $default);
}

// Verificar si es página de inicio
function translatio_is_front_page() {
    return (is_front_page() && !is_home());
}

// Obtener idioma actual (Polylang)
function translatio_get_current_lang() {
    if (function_exists('pll_current_language')) {
        return pll_current_language('slug');
    }
    return 'es';
}

// Obtener campo traducido
function translatio_get_field($field_id, $post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $value = get_post_meta($post_id, $field_id, true);
    
    // Si es string y Polylang está activo, intentar traducir
    if (is_string($value) && function_exists('pll__')) {
        return pll__($value);
    }
    
    return $value;
}

// Debug helper
function translatio_debug($data) {
    if (WP_DEBUG) {
        echo '<pre>';
        print_r($data);
        echo '</pre>';
    }
}
