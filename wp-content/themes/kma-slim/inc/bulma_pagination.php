<?php
/**
 * Pagination Functions
 *
 * @package KMA
 * @subpackage kmaslim
 */
/**
 * Add custom pagination class
 *
 * @link https://css-tricks.com/snippets/wordpress/add-class-to-links-generated-by-next_posts_link-and-previous_posts_link/
 */
function bulma_pagination($args = [], $class = 'pagination') {
	if ($GLOBALS['wp_query']->max_num_pages <= 1) return;
	$args = wp_parse_args( $args, [
		'mid_size'           => 2,
		'prev_next'          => false,
		'prev_text'          => __('Older posts', 'bulmapress'),
		'next_text'          => __('Newer posts', 'bulmapress'),
		'screen_reader_text' => __('Posts navigation', 'bulmapress'),
		]);
	$links     = paginate_links($args);
	$next_link = get_previous_posts_link($args['next_text']);
	$prev_link = get_next_posts_link($args['prev_text']);
	$template  = apply_filters( 'navigation_markup_template', '
		<nav class="navigation %1$s" role="navigation">
			<h2 class="screen-reader-text">%2$s</h2>
			<div class="nav-links level">%3$s<div class="page-numbers-container level">%4$s</div>%5$s</div>
		</nav>', $args, $class);
	echo sprintf($template, $class, $args['screen_reader_text'], $next_link, $links, $prev_link);
}
