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
        <div class="section-wrapper full-bg" >

            <slider>
                <slide image="/wp-content/themes/kma-slim/img/placeholder-4.jpg" :active="true" >
                    <section class="hero is-fullheight is-transparent white-80">
                        <div class="hero-body">
                            <div class="container">
                                <h1 class="title is-1">Slide 1</h1>
                            </div>
                        </div>
                    </section>
                </slide>
                <slide image="/wp-content/themes/kma-slim/img/placeholder-2.jpg">
                    <section class="hero is-fullheight is-transparent white-80">
                        <div class="hero-body">
                            <div class="container">
                                <h1 class="title is-1">Slide 2</h1>
                            </div>
                        </div>
                    </section>
                </slide>
                <slide image="/wp-content/themes/kma-slim/img/placeholder-3.jpg">
                    <section class="hero is-fullheight is-transparent white-80">
                        <div class="hero-body">
                            <div class="container">
                                <h1 class="title is-1">Slide 3</h1>
                            </div>
                        </div>
                    </section>
                </slide>
                <slide image="/wp-content/themes/kma-slim/img/placeholder-1.jpg">
                    <section class="hero is-fullheight is-transparent white-80">
                        <div class="hero-body">
                            <div class="container">
                                <h1 class="title is-1">Slide 4</h1>
                            </div>
                        </div>
                    </section>
                </slide>
            </slider>

            <a class="clickdown icon center" href="#bot"><i class="fa fa-angle-down" aria-hidden="true"></i></a>

        </div>
    </article><!-- #post-## -->
</div>
<!--<modal v-if="isVisible" @close="isVisible = false"><div class="box" >Welcome!</div></modal>-->