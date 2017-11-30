<?php

use Includes\Modules\Navwalker\BulmaNavwalker;

/**
 * @package KMA
 * @subpackage kmaslim
 * @since 1.0
 * @version 1.2
 */
?>
<div id="MobileNavMenu" :class="[{ 'is-active': isOpen }, 'navbar']">
    <?php wp_nav_menu(array(
        'theme_location' => 'mobile-menu',
        'container'      => false,
        'menu_class'     => 'navbar-start',
        'fallback_cb'    => '',
        'menu_id'        => 'mobile-menu',
        'link_before'    => '',
        'link_after'     => '',
        'items_wrap'     => '<div id="%1$s" class="%2$s">%3$s</div>',
        'walker'         => new BulmaNavwalker()
    )); ?>
</div>
<div :class="['site-wrapper', { 'menu-open': isOpen }, {'full-height': footerStuck}]">
<div class="site-mobile-overlay"></div>
<header id="top" class="header">
    <div class="container-fluid">
        <nav class="navbar">

            <div class="navbar-brand">
                <a href="/">
                    <img src="<?php echo get_template_directory_uri() . '/img/slim-logo.svg'; ?>" alt="Bulma: a modern CSS framework based on Flexbox" width="200" height="60">
                </a>

                <div class="navbar-burger burger" id="MobileNavBurger" data-target="MobileNavMenu" @click="toggleMenu">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>

            <?php wp_nav_menu(array(
                'theme_location' => 'main-menu',
                'container'      => false,
                'menu_class'     => 'navbar-end',
                'fallback_cb'    => '',
                'menu_id'        => 'main-menu',
                'link_before'    => '',
                'link_after'     => '',
                'items_wrap'     => '<div id="%1$s" class="%2$s">%3$s</div>',
                'walker'         => new BulmaNavwalker()
            )); ?>

        </nav>
    </div>
</header>

