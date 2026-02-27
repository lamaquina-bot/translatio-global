<?php
/**
 * Header Template
 * 
 * @package Translatio
 */

if (!defined('ABSPATH')) {
    exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link screen-reader-text" href="#main-content">
    <?php esc_html_e('Saltar al contenido', 'translatio'); ?>
</a>

<header class="site-header">
    <nav class="navbar" role="navigation" aria-label="<?php esc_attr_e('Navegación principal', 'translatio'); ?>">
        <div class="container">
            <!-- Logo -->
            <a href="<?php echo esc_url(home_url('/')); ?>" class="navbar-brand" aria-label="<?php bloginfo('name'); ?>">
                <?php
                if (has_custom_logo()) {
                    the_custom_logo();
                } else {
                    ?>
                    <span class="logo-text"><?php bloginfo('name'); ?></span>
                    <?php
                }
                ?>
            </a>
            
            <!-- Menú Principal -->
            <?php
            if (has_nav_menu('primary')) {
                wp_nav_menu([
                    'theme_location' => 'primary',
                    'menu_class'     => 'nav-menu',
                    'container'      => false,
                    'depth'          => 1,
                    'fallback_cb'    => false,
                ]);
            }
            ?>
            
            <!-- Language Switcher -->
            <?php if (function_exists('pll_the_languages')) : ?>
            <div class="language-switcher">
                <?php
                $languages = pll_the_languages([
                    'dropdown'   => 1,
                    'show_flags' => 0,
                    'show_names' => 1,
                ]);
                echo $languages;
                ?>
            </div>
            <?php endif; ?>
            
            <!-- Mobile Toggle -->
            <button class="menu-toggle" aria-label="<?php esc_attr_e('Abrir menú', 'translatio'); ?>" aria-expanded="false">
                <span></span>
                <span></span>
                <span></span>
            </button>
        </div>
    </nav>
    
    <!-- Mobile Menu -->
    <div class="mobile-menu" aria-hidden="true">
        <?php
        if (has_nav_menu('primary')) {
            wp_nav_menu([
                'theme_location' => 'primary',
                'menu_class'     => 'mobile-nav-menu',
                'container'      => false,
                'depth'          => 1,
            ]);
        }
        ?>
        
        <!-- Mobile Language Switcher -->
        <?php if (function_exists('pll_the_languages')) : ?>
        <div class="language-switcher-mobile">
            <?php pll_the_languages(['show_flags' => 1, 'show_names' => 1]); ?>
        </div>
        <?php endif; ?>
    </div>
</header>
