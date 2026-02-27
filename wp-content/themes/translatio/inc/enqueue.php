<?php
/**
 * Scripts y Estilos
 * 
 * @package Translatio
 * @since 1.0.0
 */

if (!defined('ABSPATH')) {
    exit;
}

/**
 * Enqueue scripts y styles
 */
function translatio_scripts() {
    // Google Fonts
    wp_enqueue_style(
        'translatio-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Noto+Sans+SC:wght@400;500;700&display=swap',
        [],
        null
    );
    
    // Estilos principales
    wp_enqueue_style(
        'translatio-main',
        TRANSLATIO_URI . '/assets/css/main.min.css',
        ['translatio-fonts'],
        TRANSLATIO_VERSION
    );
    
    // JavaScript principal
    wp_enqueue_script(
        'translatio-main',
        TRANSLATIO_URI . '/assets/js/main.min.js',
        [],
        TRANSLATIO_VERSION,
        true
    );
    
    // Localizar script con variables PHP
    wp_localize_script('translatio-main', 'translatioData', [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('translatio_nonce'),
        'lang'    => translatio_get_current_lang(),
    ]);
    
    // Comment reply script
    if (is_singular() && comments_open() && get_option('thread_comments')) {
        wp_enqueue_script('comment-reply');
    }
}
add_action('wp_enqueue_scripts', 'translatio_scripts');

/**
 * Preconnect para Google Fonts
 */
function translatio_resource_hints($urls, $relation_type) {
    if ('preconnect' === $relation_type) {
        $urls[] = ['href' => 'https://fonts.googleapis.com'];
        $urls[] = ['href' => 'https://fonts.gstatic.com', 'crossorigin' => true];
    }
    return $urls;
}
add_filter('wp_resource_hints', 'translatio_resource_hints', 10, 2);

/**
 * Defer/Async scripts
 */
function translatio_script_loader_tag($tag, $handle, $src) {
    $async_scripts = ['translatio-main'];
    $defer_scripts = [];
    
    if (in_array($handle, $async_scripts)) {
        return str_replace(' src', ' async src', $tag);
    }
    
    if (in_array($handle, $defer_scripts)) {
        return str_replace(' src', ' defer src', $tag);
    }
    
    return $tag;
}
add_filter('script_loader_tag', 'translatio_script_loader_tag', 10, 3);

/**
 * Remover query strings de assets
 */
function translatio_remove_query_strings($src) {
    if (strpos($src, '?ver=')) {
        $src = remove_query_arg('ver', $src);
    }
    return $src;
}
add_filter('style_loader_src', 'translatio_remove_query_strings', 10, 2);
add_filter('script_loader_src', 'translatio_remove_query_strings', 10, 2);

/**
 * Deshabilitar emojis
 */
function translatio_disable_emojis() {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    remove_filter('the_content_feed', 'wp_staticize_emoji');
    remove_filter('comment_text_rss', 'wp_staticize_emoji');
    remove_filter('wp_mail', 'wp_staticize_emoji_for_email');
}
add_action('init', 'translatio_disable_emojis');

/**
 * Remover jQuery Migrate
 */
function translatio_remove_jquery_migrate($scripts) {
    if (!is_admin() && isset($scripts->registered['jquery'])) {
        $script = $scripts->registered['jquery'];
        if ($script->deps) {
            $script->deps = array_diff($script->deps, ['jquery-migrate']);
        }
    }
}
add_action('wp_default_scripts', 'translatio_remove_jquery_migrate');
