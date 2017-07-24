<?php
/**
 * @package KMA
 * @subpackage kmaslim
 * @since 1.0
 * @version 1.2
 */
$headline = ($post->page_information_headline != '' ? $post->page_information_headline : $post->post_title);
$subhead = ($post->page_information_subhead != '' ? $post->page_information_subhead : '');
?>
<div id="mid" >
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <div class="section-wrapper full-bg" style="background-image: url(/wp-content/themes/kma-slim/img/placeholder-4.jpg);">
            <section class="hero is-fullheight is-transparent white-50">

                <div class="hero-body">
                    <div class="container">
                        <h1 class="title is-1"><?php echo $headline; ?></h1>
                        <?php echo ($subhead!='' ? '<p class="subtitle is-3">'.$subhead.'</p>' : null); ?>
                        <?php the_content(); ?>
                    </div>
                </div>
                <a class="clickdown icon center" href="#"><i class="fa fa-angle-down" aria-hidden="true"></i></a>

            </section>
        </div>
    </article><!-- #post-## -->
</div>