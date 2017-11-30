<?php

namespace Includes\Modules\Testimonials;

use Includes\Modules\CPT\CustomPostType;

/**
 * Testimonials
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Testimonials {
	/**
	 * Testimonials constructor.
	 */
	public function __construct() {
	}

	/**
	 * @return null
	 */
	public function createPostType() {
		$quote = new CustomPostType( 'Testimonial', [
			'supports'           => [ 'title', 'editor', 'revisions' ],
			'menu_icon'          => 'dashicons-format-quote',
			'rewrite'            => [ 'slug' => 'testimonials' ],
			'has_archive'        => false,
			'menu_position'      => null,
			'public'             => true,
			'publicly_queryable' => true,
		] );

		$quote->addTaxonomy( 'Testimonial Category' );

		$quote->addMetaBox( 'Author Info', [
			'Name'          => 'text',
			'Company'       => 'text',
			'Short Version' => 'longtext',
			'Featured'      => 'boolean'
		] );
	}

	/**
	 * @return null
	 */
	public function createAdminColumns() {

		//TODO: make this work...
	}

	public function getRandomTestimonial() {

		$request =  $this->getTestimonials( [
			'order'          => 'rand',
			'posts_per_page' => 1
		] );

        return $request[0];

	}

	public function getTestimonials( $args ) {
		$outputArray = [];

		$request = [
			'posts_per_page' => - 1,
			'offset'         => 0,
			'order'          => 'DESC',
			'orderby'        => 'date_posted',
			'post_type'      => 'testimonial',
			'post_status'    => 'publish',
		];

		$request   = array_merge( $request, $args );
		$postArray = get_posts( $request );

		foreach ( $postArray as $post ) {
            $outputArray[] = [
                'content'       => $post->post_content,
                'author'        => get_post_meta($post->ID, 'author_info_name', true),
                'company'       => get_post_meta($post->ID, 'author_info_company', true),
                'featured'      => get_post_meta($post->ID, 'author_info_featured', true),
                'short_version' => get_post_meta($post->ID, 'author_info_short_version', true)
            ];
		}

		return $outputArray;
	}
}
