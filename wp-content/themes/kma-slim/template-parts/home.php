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
        <div class="section-wrapper full-bg" v-bind:style="{ 'background-image': 'url(' + currentImage + ')' }" >
            <section class="hero is-fullheight is-transparent white-80">

                <div class="hero-body">
                    <div class="slider-left icon is-large" @click="clickPrev">
                        <i class="fa fa-angle-left is-large" aria-hidden="true"></i>
                    </div>
                    <div class="container">
                        <h1 class="title is-1"><?php echo $headline; ?></h1>
                        <?php echo ($subhead!='' ? '<p class="subtitle is-3">'.$subhead.'</p>' : null); ?>
                        <?php the_content(); ?>
                    </div>
                    <div class="slider-right icon is-large" @click="clickNext">
                        <i class="fa fa-angle-right is-large" aria-hidden="true"></i>
                    </div>
                </div>
                <a class="clickdown icon center" href="#bot"><i class="fa fa-angle-down" aria-hidden="true"></i></a>

            </section>
        </div>
    </article><!-- #post-## -->
</div>
<modal v-if="isVisible" @close="isVisible = false"><div class="box" >Welcome!</div></modal>