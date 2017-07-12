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
	    <div class="navbar-start">
	      <div class="navbar-item has-dropdown is-hoverable">
	        <a class="navbar-link  is-active" href="/documentation/overview/start/">
	          Docs
	        </a>
	        <div class="navbar-dropdown is-boxed">
	          <a class="navbar-item " href="/documentation/overview/start/">
	            Overview
	          </a>
	          <a class="navbar-item " href="http://bulma.io/documentation/modifiers/syntax/">
	            Modifiers
	          </a>
	          <a class="navbar-item " href="http://bulma.io/documentation/grid/columns/">
	            Grid
	          </a>
	          <a class="navbar-item " href="http://bulma.io/documentation/elements/box/">
	            Elements
	          </a>

	            <a class="navbar-item is-active" href="http://bulma.io/documentation/components/breadcrumb/">
	              Components
	            </a>

	          <a class="navbar-item " href="http://bulma.io/documentation/layout/container/">
	            Layout
	          </a>
	          <hr class="navbar-divider">
	          <div class="navbar-item">
	            <div>version <p class="has-text-info">0.4.3</p></div>
	          </div>
	        </div>
	      </div>
	      <div class="navbar-item has-dropdown is-hoverable">
	        <a class="navbar-link " href="http://bulma.io/blog/">
	          Blog
	        </a>
	        <div id="blogDropdown" class="navbar-dropdown is-boxed" data-style="width: 18rem;">

	            <a class="navbar-item" href="/2017/03/10/new-field-element/">
	              <div class="navbar-content">
	                <p>
	                  <small class="has-text-info">10 Mar 2017</small>
	                </p>
	                <p>New field element (for better controls)</p>
	              </div>
	            </a>

	            <a class="navbar-item" href="/2016/04/11/metro-ui-css-grid-with-bulma-tiles/">
	              <div class="navbar-content">
	                <p>
	                  <small class="has-text-info">11 Apr 2016</small>
	                </p>
	                <p>Metro UI CSS grid with Bulma tiles</p>
	              </div>
	            </a>

	            <a class="navbar-item" href="/2016/02/09/blog-launched-new-responsive-columns-new-helpers/">
	              <div class="navbar-content">
	                <p>
	                  <small class="has-text-info">09 Feb 2016</small>
	                </p>
	                <p>Blog launched, new responsive columns, new helpers</p>
	              </div>
	            </a>

	          <a class="navbar-item" href="http://bulma.io/blog/">
	            More posts
	          </a>
	          <hr class="navbar-divider">
	          <div class="navbar-item">
	            <div class="navbar-content">
	              <div class="level is-mobile">
	                <div class="level-left">
	                  <div class="level-item">
	                    <strong>Stay up to date!</strong>
	                  </div>
	                </div>
	                <div class="level-right">
	                  <div class="level-item">
	                    <a class="button is-rss is-small" href="http://bulma.io/atom.xml">
	                      <span class="icon is-small">
	                        <i class="fa fa-rss"></i>
	                      </span>
	                      <span>Subscribe</span>
	                    </a>
	                  </div>
	                </div>
	              </div>
	            </div>
	          </div>
	        </div>
	      </div>
	    </div>

			<?php wp_nav_menu(
	        array(
	            'theme_location'  => 'main-menu',
	            'container' 			=> false,
	            'menu_class'      => 'navbar-end',
	            'fallback_cb'     => '',
	            'menu_id'         => 'main-menu',
				'link_before'	  => '',
				'link_after' 	  => '',
				'items_wrap' 	  => '<div id="%1$s" class="%2$s">%3$s</div>',
				'walker'		  => new bulma_navwalker()
	        )
	    ); ?>
	  </div>
	</nav>


</header>
