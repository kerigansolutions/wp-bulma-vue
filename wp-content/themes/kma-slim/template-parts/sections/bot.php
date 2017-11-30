<?php

use Includes\Modules\Social\SocialSettingsPage;
use Includes\Modules\Navwalker\BulmaNavwalker;

/**
 * @package KMA
 * @subpackage kmaslim
 * @since 1.0
 * @version 1.3
 */
?>
<div class="sticky-footer" >
    <div id="bot">
        <div class="container">
            <div class="bottom-nav has-text-centered">
                <?php wp_nav_menu( array(
                    'theme_location' => 'footer-menu',
                    'container'      => false,
                    'menu_class'     => 'navbar is-transparent',
                    'fallback_cb'    => '',
                    'menu_id'        => 'footer-menu',
                    'link_before'    => '',
                    'link_after'     => '',
                    'items_wrap'     => '<div id="%1$s" class="%2$s">%3$s</div>',
                    'walker'         => new BulmaNavwalker()
                ) ); ?>
            </div>
        </div>
    </div>
    <div id="bot-bot">
        <div class="container">
            <div class="columns">
                <div class="column is-6">
                    <div class="social has-text-left">
                        <?php
                        $socialLinks = new SocialSettingsPage();
                        $socialIcons = $socialLinks->getSocialLinks('svg', 'circle');
                        if (is_array($socialIcons)) {
                            foreach ($socialIcons as $socialId => $socialLink) {
                                echo '<a class="' . $socialId . '" href="' . $socialLink[0] . '" target="_blank" >' . $socialLink[1] . '</a>';
                            }
                        }
                        ?>
                    </div>
                </div>
                <div class="column is-6 has-text-right">
                    <p class="copyright">&copy;<?php echo date('Y'); ?> <?php echo get_bloginfo(); ?>. All Rights Reserved.</p>
                </div>
            </div>
        </div><!-- .container -->
    </div>
</div>
</div>
<?php wp_footer(); ?>