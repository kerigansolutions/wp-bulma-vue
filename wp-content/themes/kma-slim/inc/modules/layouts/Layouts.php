<?php
/**
 * Layouts class
 */
class Layouts {
	/**
	 * Layouts constructor.
	 */
	function __construct() {

	}

	/**
	 * @return null
	 */
	public function createPostType() {
		$page = new Custom_Post_Type('Page');
		$page->add_meta_box(
			'Page Information',
			array(
				'Headline' 			=> 'text',
				'Subhead'           => 'text'
			)
		);

		$page->add_taxonomy( 'Layout', array(
			'hierarchical'      => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'format' ),
			'capabilities' => array(
				'manage_terms' => '',
				'edit_terms' => '',
				'delete_terms' => '',
				'assign_terms' => 'edit_posts'
			),
			'public' => true,
			'show_in_nav_menus' => false,
			'show_tagcloud' => false,
		) );

	}

	/**
	 * @return null
	 */
	public function createDefaultFormats() {

		// programmatically create a few format terms
		function layout_insert_default_format() { // later we'll define this as our default, so all posts have to have at least one format
			wp_insert_term(
				'Default',
				'layout',
				array(
					'description'	=> '',
					'slug' 		    => 'default'
				)
			);
		}
		add_action( 'init', 'layout_insert_default_format' );

		// make sure there's a default Format type and that it's chosen if they didn't choose one
		function layout_default_format_term( $post_id, $post ) {
			if ( 'publish' === $post->post_status ) {
				$defaults = array(
					'format' => 'default' // change 'default' to whatever term slug you created above that you want to be the default
				);
				$taxonomies = get_object_taxonomies( $post->post_type );
				foreach ( (array) $taxonomies as $taxonomy ) {
					$terms = wp_get_post_terms( $post_id, $taxonomy );
					if ( empty( $terms ) && array_key_exists( $taxonomy, $defaults ) ) {
						wp_set_object_terms( $post_id, $defaults[$taxonomy], $taxonomy );
					}
				}
			}
		}
		add_action( 'save_post', 'layout_default_format_term', 100, 2 );

		// replace checkboxes for the format taxonomy with radio buttons and a custom meta box
		function layout_term_radio_checklist( $args ) {
			if ( ! empty( $args['taxonomy'] ) && $args['taxonomy'] === 'format' ) {
				if ( empty( $args['walker'] ) || is_a( $args['walker'], 'Walker' ) ) { // Don't override 3rd party walkers.
					if ( ! class_exists( 'Layout_Walker_Category_Radio_Checklist' ) ) {
						include('Layout_Walker.php');
					}
					$args['walker'] = new Layout_Walker_Category_Radio_Checklist;
				}
			}
			return $args;
		}
		add_filter( 'wp_terms_checklist_args', 'layout_term_radio_checklist' );

	}

	/**
	 * @param term
	 * @param slug
	 * @param description
	 */
	public function createLayout( $term = '', $description = '', $slug = '' ){

		wp_insert_term(
			$term,
			'layout',
			array(
				'description'	=> $description,
				'slug' 		    => $slug
			)
		);

	}

}