<?php
/**
 * @package KMA
 * @subpackage kmaslim
 * @since 1.0
 * @version 1.2
 */
get_header();

    if ( have_posts() ) :

		if (is_home() ) { //multipart template, archive or whatever

			get_template_part( 'template-parts/blog', get_post_format() );

		}else {

			while ( have_posts() ) : the_post();

				if ( is_front_page() ) {
					get_template_part( 'template-parts/home' );
				} else {
					get_template_part( 'template-parts/content', get_post_format() );
				}

			endwhile;

			the_posts_navigation();

		}

    else :

        get_template_part( 'template-parts/content', 'none' );

    endif;

get_footer(); ?>
