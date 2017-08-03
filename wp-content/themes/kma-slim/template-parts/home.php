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
                                <h1 class="title is-1">Tabs</h1>
                                <tabs>

                                    <tab name="About Us" :selected="true">
                                        <h1>About Us</h1>
                                        <p>Andouille porchetta shank ham hock. Ham hock doner pastrami shankle tri-tip kielbasa chuck corned beef venison cow tongue capicola pancetta short ribs landjaeger. Prosciutto leberkas picanha drumstick hamburger pastrami pancetta shankle pork chop beef. T-bone alcatra cupim andouille bresaola pig porchetta swine.</p>
                                    </tab>

                                    <tab name="Our Culture">
                                        <h1>Our Culture</h1>
                                        <p>Bacon tail boudin brisket, leberkas flank shankle spare ribs. Shoulder corned beef andouille frankfurter. Bresaola porchetta sirloin cupim cow, ham tail beef. Pork chop short ribs pig prosciutto ground round leberkas cupim rump drumstick capicola salami burgdoggen hamburger sirloin. Spare ribs leberkas meatloaf biltong alcatra prosciutto bresaola andouille strip steak beef ribs ham hock bacon. Pork chop pig cow, alcatra biltong meatloaf pancetta tri-tip chicken t-bone sausage ribeye porchetta sirloin. Pork loin meatball pig, rump shankle chuck flank beef ribs venison capicola bresaola pancetta tenderloin.</p>
                                    </tab>

                                    <tab name="Our Vision">
                                        <h1>Our Vision</h1>
                                        <p>Turkey burgdoggen ball tip prosciutto leberkas. Tenderloin salami andouille frankfurter pork doner capicola brisket leberkas ground round chuck spare ribs bacon. Pork chop ground round jerky, boudin meatball biltong kevin frankfurter pork turducken cupim pancetta. Pancetta cupim brisket andouille salami capicola sirloin, landjaeger filet mignon fatback shankle porchetta picanha. Ball tip andouille picanha tail venison swine meatloaf capicola filet mignon salami pig pancetta pork belly ribeye kielbasa.</p>
                                    </tab>

                                </tabs>
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