<?php
/**
 * @package KMA
 * @subpackage kmaslim
 * @since 1.0
 * @version 1.2
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'kmaslim' ); ?></a>
<header id="top" class="header" >
	<nav class="navbar is-transparent">
	  <div class="navbar-brand">
	    <a class="navbar-item" href="/">
	      <img src="<?php echo get_template_directory_uri().'/img/slim-logo.svg'; ?>" alt="Bulma: a modern CSS framework based on Flexbox" width="112" height="28">
	    </a>

	    <div class="navbar-burger burger" id="TopNavBurger" data-target="TopNavMenu">
	      <span></span>
	      <span></span>
	      <span></span>
	    </div>
	  </div>

	  <div id="TopNavMenu" class="navbar-menu">
			<?php wp_nav_menu(
	        array(
	            'theme_location'  => 'main-menu',
	            'container' 			=> false,
	            'menu_class'      => 'navbar-end',
	            'fallback_cb'     => '',
	            'menu_id'         => 'main-menu',
							'link_before'	    => '',
							'link_after' 	    => '',
							'items_wrap' 	    => '<div id="%1$s" class="%2$s">%3$s</div>',
							'walker'		      => new bulma_navwalker()
	        )
	    ); ?>
	  </div>
	</nav>


</header>
