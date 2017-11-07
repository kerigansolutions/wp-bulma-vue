<?php
/**
 * @package KMA
 * @subpackage kmaslim
 * @since 1.0
 * @version 1.3
 */

use Includes\Modules\Leads\Leads;
use Includes\Modules\Helpers\CleanWP;
use Includes\Modules\Layouts\Layouts;
use Includes\Modules\Slider\BulmaSlider;
use Includes\Modules\Testimonials\Testimonials;
use Includes\Modules\Social\SocialSettingsPage;

require('vendor/autoload.php');

new CleanWP();

$socialLinks = new SocialSettingsPage();
if(is_admin()) {
    $socialLinks->createPage();
}

$testimonials = new Testimonials();
$testimonials->createPostType();
$testimonials->createAdminColumns();
//$testimonials->createShortcode();

$layouts = new Layouts();
$layouts->createPostType();
$layouts->createDefaultFormats();
$layouts->createLayout('two-column','two column page layout','twocol');

$slider = new BulmaSlider();
$slider->createPostType();
$slider->createAdminColumns();

if ( ! function_exists( 'kmaslim_setup' ) ) :

function kmaslim_setup() {

	load_theme_textdomain( 'kmaslim', get_template_directory() . '/languages' );
	add_theme_support( 'automatic-feed-links' );
	add_theme_support( 'title-tag' );
	add_theme_support( 'post-thumbnails' );

	register_nav_menus( array(
		'mobile-menu'    => esc_html__( 'Mobile Menu', 'kmaslim' ),
		'footer-menu'    => esc_html__( 'Footer Menu', 'kmaslim' ),
		'main-menu'      => esc_html__( 'Main Navigation', 'kmaslim' )
	) );

	add_theme_support( 'html5', array(
		'search-form',
		'comment-form',
		'comment-list',
		'gallery',
		'caption'
	) );

	function kmaslim_inline() {?>
		<style type="text/css">
			<?php echo file_get_contents(get_template_directory() . '/style.css'); ?>
		</style>
	<?php }
	add_action( 'wp_head', 'kmaslim_inline' );
	wp_register_script( 'scripts', get_template_directory_uri() . '/app.js', array(), '0.0.1', true );

}
endif;
add_action( 'after_setup_theme', 'kmaslim_setup' );

function kmaslim_scripts() {
	wp_enqueue_script( 'scripts' );
}
add_action( 'wp_enqueue_scripts', 'kmaslim_scripts' );

//Remove WordPress's content filtering so we can make our own tags AND use them.
remove_filter( 'the_content', 'wpautop' );