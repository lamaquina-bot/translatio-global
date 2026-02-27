<?php
/**
 * Template Name: Home
 * 
 * @package Translatio
 */

get_header();
?>

<main id="main-content">
    
    <!-- Hero Section -->
    <?php get_template_part('template-parts/components/hero'); ?>
    
    <!-- Servicios -->
    <section class="section section--alt" id="servicios">
        <div class="container">
            <header class="section-header text-center">
                <h2 class="section-title"><?php translatio_e('Servicios Especializados en Subrogación'); ?></h2>
            </header>
            
            <div class="grid grid--3">
                <?php
                $servicios = get_posts([
                    'post_type'      => 'servicios',
                    'posts_per_page' => 3,
                    'orderby'        => 'meta_value_num',
                    'meta_key'       => 'servicio_orden',
                    'order'          => 'ASC',
                ]);
                
                foreach ($servicios as $servicio) :
                    setup_postdata($servicio);
                    ?>
                    <div class="servicio-card card">
                        <?php
                        $icon_id = get_post_meta($servicio->ID, 'servicio_icono_id', true);
                        if ($icon_id) :
                            $icon_url = wp_get_attachment_url($icon_id);
                            ?>
                            <div class="servicio-icon">
                                <img src="<?php echo esc_url($icon_url); ?>" alt="" width="40" height="40">
                            </div>
                        <?php endif; ?>
                        
                        <h3 class="servicio-title"><?php echo get_the_title($servicio->ID); ?></h3>
                        
                        <p class="servicio-desc">
                            <?php echo get_post_meta($servicio->ID, 'servicio_descripcion_corta', true); ?>
                        </p>
                        
                        <a href="<?php echo get_permalink($servicio->ID); ?>" class="card-link">
                            <?php translatio_e('Más información'); ?>
                            <svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"/></svg>
                        </a>
                    </div>
                    <?php
                endforeach;
                wp_reset_postdata();
                ?>
            </div>
        </div>
    </section>
    
    <!-- Beneficios -->
    <?php get_template_part('template-parts/components/beneficios'); ?>
    
    <!-- Estadísticas -->
    <?php get_template_part('template-parts/components/estadisticas'); ?>
    
    <!-- Testimonios -->
    <?php get_template_part('template-parts/components/testimonios'); ?>
    
    <!-- CTA Final -->
    <?php get_template_part('template-parts/components/cta'); ?>
    
</main>

<?php get_footer(); ?>
