<?php
/**
 * Funciones auxiliares
 * 
 * @package Translatio
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Obtener opción del tema
 */
function translatio_get_option($key, $default = '') {
    return get_theme_mod($key, $default);
}

/**
 * Verificar si es front page
 */
function translatio_is_front_page() {
    return (is_front_page() && !is_home());
}

/**
 * Obtener idioma actual (Polylang)
 */
function translatio_get_current_lang() {
    if (function_exists('pll_current_language')) {
        return pll_current_language('slug');
    }
    return 'es';
}

/**
 * Obtener campo CMB2 traducido
 */
function translatio_get_field($field_id, $post_id = null) {
    if (!$post_id) {
        $post_id = get_the_ID();
    }
    
    $value = get_post_meta($post_id, $field_id, true);
    
    if (is_string($value) && function_exists('pll__')) {
        return pll__($value);
    }
    
    return $value;
}

/**
 * Traducir string (helper)
 */
function translatio__($string) {
    if (function_exists('pll__')) {
        return pll__($string);
    }
    return __($string, 'translatio');
}

/**
 * Echo traducido
 */
function translatio_e($string) {
    echo translatio__($string);
}

/**
 * Obtener página por template
 */
function translatio_get_page_by_template($template) {
    $pages = get_posts([
        'post_type'      => 'page',
        'meta_key'       => '_wp_page_template',
        'meta_value'     => $template,
        'posts_per_page' => 1,
    ]);
    
    return $pages ? $pages[0] : null;
}

/**
 * Obtener URL de página por template
 */
function translatio_get_page_url($template) {
    $page = translatio_get_page_by_template($template);
    return $page ? get_permalink($page->ID) : home_url('/');
}

/**
 * Truncar texto
 */
function translatio_truncate($text, $length = 100, $append = '...') {
    if (strlen($text) <= $length) {
        return $text;
    }
    return rtrim(substr($text, 0, $length)) . $append;
}

/**
 * Formatear teléfono para link
 */
function translatio_format_phone_link($phone) {
    return 'tel:' . preg_replace('/[^0-9+]/', '', $phone);
}

/**
 * Formatear WhatsApp link
 */
function translatio_format_whatsapp_link($number, $message = '') {
    $number = preg_replace('/[^0-9]/', '', $number);
    $url = 'https://wa.me/' . $number;
    
    if ($message) {
        $url .= '?text=' . urlencode($message);
    }
    
    return $url;
}

/**
 * Obtener imagen con lazy loading
 */
function translatio_get_image($attachment_id, $size = 'full', $attr = []) {
    $default_attr = [
        'loading' => 'lazy',
        'class'   => 'img-fluid',
    ];
    
    $attr = wp_parse_args($attr, $default_attr);
    
    return wp_get_attachment_image($attachment_id, $size, false, $attr);
}

/**
 * Breadcrumb simple
 */
function translatio_breadcrumb() {
    if (is_front_page()) {
        return;
    }
    
    echo '<nav class="breadcrumb" aria-label="Breadcrumb">';
    echo '<a href="' . home_url('/') . '">' . translatio__('Inicio') . '</a>';
    
    if (is_page()) {
        $parents = get_post_ancestors(get_the_ID());
        foreach (array_reverse($parents) as $parent) {
            echo ' <span class="separator">/</span> ';
            echo '<a href="' . get_permalink($parent) . '">' . get_the_title($parent) . '</a>';
        }
        echo ' <span class="separator">/</span> ';
        echo '<span class="current">' . get_the_title() . '</span>';
    } elseif (is_single()) {
        echo ' <span class="separator">/</span> ';
        echo '<span class="current">' . get_the_title() . '</span>';
    } elseif (is_archive()) {
        echo ' <span class="separator">/</span> ';
        echo '<span class="current">' . get_the_archive_title() . '</span>';
    }
    
    echo '</nav>';
}

/**
 * Verificar si página tiene hero
 */
function translatio_has_hero() {
    return has_post_thumbnail() || translatio_get_field('hero_imagen');
}

/**
 * Obtener colores del tema
 */
function translatio_get_colors() {
    $lang = translatio_get_current_lang();
    
    if ($lang === 'zh') {
        return [
            'primary'   => '#8b0000',
            'secondary' => '#ffd700',
            'accent'    => '#1e3a5f',
        ];
    }
    
    return [
        'primary'   => '#1e3a5f',
        'secondary' => '#c9a227',
        'accent'    => '#2e7d32',
    ];
}

/**
 * Debug helper
 */
function translatio_debug($data, $die = false) {
    if (WP_DEBUG) {
        echo '<pre style="background:#f0f0f0;padding:20px;overflow:auto;">';
        print_r($data);
        echo '</pre>';
        if ($die) {
            die();
        }
    }
}
