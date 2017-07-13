<?php
/**
 * @package KMA
 * @subpackage kmaslim
 * @since 1.0
 * @version 1.2
 */
get_header();

    if ( have_posts() ) :

        /* Start the Loop */
        while ( have_posts() ) : the_post();

            if ( is_front_page() ) {
	            get_template_part( 'template-parts/home' );
            } elseif( is_home() ) {
	            get_template_part( 'template-parts/loop', get_post_format() );
            } else {
	            get_template_part( 'template-parts/content', get_post_format() );
            }

        endwhile;

        the_posts_navigation();

    else :

        get_template_part( 'template-parts/content', 'none' );

    endif;

get_footer(); ?>
