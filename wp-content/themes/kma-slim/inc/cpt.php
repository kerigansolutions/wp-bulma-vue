<?php //Custom Post Type Class

if ( ! defined( 'ABSPATH' ) ) exit; //To protect from absolute linkage

class Custom_Post_Type {

    public $post_type_name;
    public $post_type_args;
    public $post_type_labels;
	public $columns;
	public $custom_populate_columns;
	public $filters;
    public $taxonomies;

    public function __construct( $name, $args = array(), $labels = array() ){

        // Set some important variables
		$this->post_type_name	= strtolower( str_replace( ' ', '_', $name ) );
		$this->post_type_args 	= $args;
		$this->post_type_labels	= $labels;

		// Add action to register the post type, if the post type does not already exist
		if( ! post_type_exists( $this->post_type_name ) ){
			add_action( 'init', array( &$this, 'register_post_type' ) );

		}

		// Populate the taxonomy columns with the posts terms.
			$this->add_action( 'manage_' . $this->post_type_name . '_posts_custom_column', array( &$this, 'populate_admin_columns' ), 10, 2 );
			// Add filter select option to admin edit.
			$this->add_action( 'restrict_manage_posts', array( &$this, 'add_taxonomy_filters' ) );

		// Listen for the save post hook
		$this->save();

    }

	/**
     * Get
     *
     * Helper function to get an object variable.
     *
     * @param string $var The variable you would like to retrieve.
     * @return mixed Returns the value on success, boolean false whe it fails.
     */
    function get( $var ) {
        // If the variable exists.
        if ( $this->$var ) {
            // On success return the value.
            return $this->$var;
        } else {
            // on fail return false
            return false;
        }
    }

    /**
     * Set
     *
     * Helper function used to set an object variable. Can overwrite existsing
     * variables or create new ones. Cannot overwrite reserved variables.
     *
     * @param mixed $var The variable you would like to create/overwrite.
     * @param mixed $value The value you would like to set to the variable.
     */
    function set( $var, $value ) {
        // An array of reserved variables that cannot be overwritten.
        $reserved = array(
            'config',
            'post_type_name',
            'singular',
            'plural',
            'slug',
            'options',
            'taxonomies'
        );
        // If the variable is not a reserved variable
        if ( ! in_array( $var, $reserved ) ) {
            // Write variable and value
            $this->$var = $value;
        }
    }

    /**
     * Add Action
     *
     * Helper function to add add_action WordPress filters.
     *
     * @param string $action Name of the action.
     * @param string $function Function to hook that will run on action.
     * @param integet $priority Order in which to execute the function, relation to other functions hooked to this action.
     * @param integer $accepted_args The number of arguments the function accepts.
     */
    function add_action( $action, $function, $priority = 10, $accepted_args = 1 ) {
        // Pass variables into WordPress add_action function
        add_action( $action, $function, $priority, $accepted_args );
    }

    /**
     * Add Filter
     *
     * Create add_filter WordPress filter.
     *
     * @see http://codex.wordpress.org/Function_Reference/add_filter
     *
     * @param  string  $action           Name of the action to hook to, e.g 'init'.
     * @param  string  $function         Function to hook that will run on @action.
     * @param  int     $priority         Order in which to execute the function, relation to other function hooked to this action.
     * @param  int     $accepted_args    The number of arguements the function accepts.
     */
    function add_filter( $action, $function, $priority = 10, $accepted_args = 1 ) {
        // Pass variables into Wordpress add_action function
        add_filter( $action, $function, $priority, $accepted_args );
    }

	/* Method which registers the post type */
    public function register_post_type(){

         //Capitilize the words and make it plural
		$name       = ucwords( str_replace( '_', ' ', $this->post_type_name ) );
		$plural     = $name . 's';

		// We set the default labels based on the post type name and plural. We overwrite them with the given labels.
		$labels = array_merge(

			// Default
			array(
				'name'                  => _x( $plural, 'post type general name' ),
				'singular_name'         => _x( $name, 'post type singular name' ),
				'add_new'               => _x( 'Add New', strtolower( $name ) ),
				'add_new_item'          => __( 'Add New ' . $name ),
				'edit_item'             => __( 'Edit ' . $name ),
				'new_item'              => __( 'New ' . $name ),
				'all_items'             => __( 'All ' . $plural ),
				'view_item'             => __( 'View ' . $name ),
				'search_items'          => __( 'Search ' . $plural ),
				'not_found'             => __( 'No ' . strtolower( $plural ) . ' found'),
				'not_found_in_trash'    => __( 'No ' . strtolower( $plural ) . ' found in Trash'),
				'parent_item_colon'     => '',
				'menu_name'             => $plural
			),

			// Given labels
			$this->post_type_labels

		);

		// Same principle as the labels. We set some defaults and overwrite them with the given arguments.
		$args = array_merge(

			// Default
			array(
				'label'                 => $plural,
				'labels'                => $labels,
				'public'                => true,
				'show_ui'               => true,
				'supports'              => array( 'title', 'editor', 'revisions' ),
				'show_in_nav_menus'     => true,
				'_builtin'              => false,
			),

			// Given args
			$this->post_type_args

		);

		// Register the post type
		register_post_type( $this->post_type_name, $args );
    }

    /* Method to attach the taxonomy to the post type */
    public function add_taxonomy( $name, $args = array(), $labels = array() ){
    	if( ! empty( $name ) ){
			// We need to know the post type name, so the new taxonomy can be attached to it.
			$post_type_name = $this->post_type_name;

			// Taxonomy properties
			$taxonomy_name      = strtolower( str_replace( ' ', '_', $name ) );
			$taxonomy_labels    = $labels;
			$taxonomy_args      = $args;

			if( ! taxonomy_exists( $taxonomy_name ) ){
				/* Create taxonomy and attach it to the object type (post type) */

				//Capitilize the words and make it plural
				$name       = ucwords( str_replace( '_', ' ', $name ) );
				$plural     = $name . 's';

				// Default labels, overwrite them with the given labels.
				$labels = array_merge(

					// Default
					array(
						'name'                  => _x( $plural, 'taxonomy general name' ),
						'singular_name'         => _x( $name, 'taxonomy singular name' ),
						'search_items'          => __( 'Search ' . $plural ),
						'all_items'             => __( 'All ' . $plural ),
						'parent_item'           => __( 'Parent ' . $name ),
						'parent_item_colon'     => __( 'Parent ' . $name . ':' ),
						'edit_item'             => __( 'Edit ' . $name ),
						'update_item'           => __( 'Update ' . $name ),
						'add_new_item'          => __( 'Add New ' . $name ),
						'new_item_name'         => __( 'New ' . $name . ' Name' ),
						'menu_name'             => __( $name ),
					),

					// Given labels
					$taxonomy_labels

				);

				// Default arguments, overwritten with the given arguments
				$args = array_merge(

					// Default
					array(
						'hierarchical'      => true,
						'label'                 => $plural,
						'labels'                => $labels,
						'public'                => true,
						'show_ui'               => true,
						'show_in_nav_menus'     => true,
						'_builtin'              => false,
						'rewrite'            => array(
							'slug' 			=> '',   		//string Customize the permalink structure slug. Defaults to the $post_type value. Should be translatable.
							'with_front' 	=> false, 				//bool Should the permalink structure be prepended with the front base. <br>
																	//(example: if your permalink structure is /blog/, then your links will be: false-> /news/, true->/blog/news/). Defaults to true
							'feeds' 		=> true, 				//bool Should a feed permalink structure be built for this post type. Defaults to has_archive value
							'pages' 		=> false				//bool Should the permalink structure provide for pagination. Defaults to true
						),
					),

					// Given
					$taxonomy_args

				);

				// Add the taxonomy to the post type
				add_action( 'init',
					function() use( $taxonomy_name, $post_type_name, $args )
					{
						register_taxonomy( $taxonomy_name, $post_type_name, $args );
					}
				);
			}else{
				/* The taxonomy already exists. We are going to attach the existing taxonomy to the object type (post type) */

				add_action( 'init',
					function() use( $taxonomy_name, $post_type_name ){
						register_taxonomy_for_object_type( $taxonomy_name, $post_type_name );
					}
				);
			}
		}
    }

    /* Attaches meta boxes to the post type */
    public function add_meta_box($title, $fields = array(), $context = 'normal', $priority = 'default' ){
         if( ! empty( $title ) ){
			// We need to know the Post Type name again
			$post_type_name = $this->post_type_name;

			// Meta variables
			$box_id         = strtolower( str_replace( ' ', '_', $title ) );
			$box_title      = ucwords( str_replace( '_', ' ', $title ) );
			$box_context    = $context;
			$box_priority   = $priority;

			// Make the fields global
			global $custom_fields;
			$custom_fields[$title] = $fields;

			add_action( 'admin_init',

				function() use( $box_id, $box_title, $post_type_name, $box_context, $box_priority, $fields ){  //(isset($filters) ? $filters : null)
                    add_meta_box(
						$box_id,
						$box_title,
						function( $post, $data){
                            $data = (isset($data) ? $data : null);
							global $post;

							// Nonce field for some validation
							wp_nonce_field( plugin_basename( __FILE__ ), 'custom_post_type' );

							// Get all inputs from $data
							$custom_fields = $data['args'][0];

							// Get the saved values
							$meta = get_post_custom( $post->ID );

							// Check the array and loop through it
							if( ! empty( $custom_fields ) ){
								/* Loop through $custom_fields */
								echo '<table cellpadding="5" style="width:100%">';
								foreach( $custom_fields as $label => $type ){
									$field_id_name  = strtolower( str_replace( ' ', '_', $data['id'] ) ) . '_' . strtolower( str_replace( ' ', '_', $label ) );
									if($type == 'preview'){
										$value   = (isset($_POST[$field_id_name][0]) ? $_POST[$field_id_name][0] : (isset($meta[$field_id_name][0]) ? $meta[$field_id_name][0] : '') );
										echo '<tr><td colspan="2">' . $value . '</td></tr>';
									}elseif($type == 'text'){
										$value   = (isset($_POST[$field_id_name][0]) ? $_POST[$field_id_name][0] : (isset($meta[$field_id_name][0]) ? $meta[$field_id_name][0] : '') );
										echo '<tr>
									   <td width="20%" align="right" valign="top"><label for="' . $field_id_name . '">' . $label . '</label></td><td width="80%" valign="top"><input style="width:100%;" class="form-control" type="text" name="custom_meta[' . $field_id_name . ']" id="' . $field_id_name . '" value="' . htmlentities( $value ) . '" /></td></tr>';
									}elseif($type == 'image'){

									    //echo '<pre>',print_r($meta[$field_id_name]),'</pre>';

									    $photourl   = (isset($_POST[$field_id_name]) ? $_POST[$field_id_name][0] : (isset($meta[$field_id_name][0]) ? $meta[$field_id_name][0] : '') );
                                        $name       = (isset($field_id_name) ? $field_id_name : 'image');
                                        $label      = (isset($label) ? $label : 'Photo');

										wp_enqueue_script('media-upload');
										wp_enqueue_script('thickbox');
										wp_enqueue_style('thickbox');
										wp_enqueue_media();
										wp_enqueue_script( 'meta-box-image' );

										echo '<tr>
									    <td width="20%" align="right" valign="top"><label for="' . $name . '">' . $label . '</label></td><td>';
										?>
										<input type="text" name="custom_meta[<?php echo $name; ?>]" id="input-<?php echo $name; ?>" value="<?php echo $photourl; ?>" style="width: 70%;" />
										<input type="button" id="button-<?php echo $name; ?>" class="button" value="<?php _e( 'Choose or Upload an Image', 'prfx-textdomain' )?>" />
                                        <?php if($photourl != '') { ?>
										<div id="preview-box" style="padding:5px 0;"><img id="preview-<?php echo $name; ?>" src="<?php echo $photourl; ?>" style="max-width: 100%;"></div>
										<?php } ?>
                                        <script>
                                            jQuery(document).ready(function($){

                                                // Instantiates the variable that holds the media library frame.
                                                var meta_image_frame;
                                                var meta_image;

                                                // Runs when the image button is clicked.
                                                $('#button-<?php echo $name; ?>').click(function(e){

                                                    // Prevents the default action from occuring.
                                                    e.preventDefault();

                                                    // If the frame already exists, re-open it.
                                                    if ( meta_image_frame ) {
                                                        meta_image_frame.open();
                                                        return;
                                                    }

                                                    // Sets up the media library frame
                                                    meta_image_frame = wp.media.frames.meta_image_frame = wp.media({
                                                        //title: meta_image.title,
                                                        //button: { text:  meta_image.button },
                                                        //library: { type: 'image' }
                                                    });

                                                    // Runs when an image is selected.
                                                    meta_image_frame.on('select', function(){

                                                        // Grabs the attachment selection and creates a JSON representation of the model.
                                                        var media_attachment = meta_image_frame.state().get('selection').first().toJSON();

                                                        // Sends the attachment URL to our custom image input field.
                                                        if(media_attachment != '') {
                                                            $('#input-<?php echo $name; ?>').val(media_attachment.url);
                                                            $('#preview-<?php echo $name; ?>').attr("src", media_attachment.url);
                                                        }
                                                    });

                                                    // Opens the media library frame.
                                                    meta_image_frame.open();
                                                });
                                            });
										</script>
										<?php
										echo '</td></tr>';

									}elseif($type == 'file'){
										$value   = (isset($_POST[$field_id_name][0]) ? $_POST[$field_id_name][0] : (isset($meta[$field_id_name][0]) ? $meta[$field_id_name][0] : '') );
										echo '<tr>
									   <td width="20%" align="right" valign="top"><label for="' . $field_id_name . '">' . $label . '</label></td><td width="80%" valign="top"><input class="form-control" type="file" name="custom_meta[' . $field_id_name . ']" id="' . $field_id_name . '" value="' . $value . '" />';
										if($value != ''){ echo '<a href="' . $value . '" target="_blank" class="button" >View/download current file</a>'; } //$meta[$field_id_name][0]
										echo '</td></tr>';
									}elseif($type == 'date'){
										echo '<tr>
									   <td width="20%" align="right" valign="top"><label for="' . $field_id_name . '">' . $label . '</label></td><td width="80%" valign="top"><input class="form-control dateselect" type="date" name="custom_meta[' . $field_id_name . ']" id="' . $field_id_name . '" value="' . $meta[$field_id_name][0] . '" /></td></tr>';
									}elseif($type == 'boolean'){
										$value   = (isset($_POST[$field_id_name][0]) ? $_POST[$field_id_name][0] : (isset($meta[$field_id_name][0]) ? $meta[$field_id_name][0] : '') );
										echo '<tr>
									    <td width="20%" align="right" valign="top"><input type="checkbox" class="form-control" name="custom_meta[' . $field_id_name . ']" id="' . $field_id_name . '"';
										if($value==TRUE){ echo ' checked '; }
										echo '></td><td width="80%" valign="top">' . $label . '</td></tr>';
									}elseif($type == 'longtext'){
										$value   = (isset($_POST[$field_id_name][0]) ? $_POST[$field_id_name][0] : (isset($meta[$field_id_name][0]) ? $meta[$field_id_name][0] : '') );
										echo '<tr>
									    <td width="20%" align="right" valign="top" ><label for="' . $field_id_name . '">' . $label . '</label></td>
									    <td width="80%" valign="top" ><textarea rows="4" class="form-control" name="custom_meta[' . $field_id_name . ']" id="' . $field_id_name . '" style="width:100%;" >' . htmlentities($value) . '</textarea></td></tr>';
									}elseif($type == 'wysiwyg'){
										$value   = (isset($_POST[$field_id_name][0]) ? $_POST[$field_id_name][0] : (isset($meta[$field_id_name][0]) ? $meta[$field_id_name][0] : '') );
										echo '<tr>
									    <td valign="top" style="width:100%;">'.
										     wp_editor( $value, $field_id_name,
											     array(
												     'quicktags'     => array( 'buttons' => 'em,strong,link' ),
												     'textarea_name' => 'custom_meta[' . $field_id_name . ']',
												     'quicktags'     => true,
												     'tinymce'       => true
											     )
										     ).'</td></tr>';
									}elseif($type == 'locked'){
										// No Meta box info since editing will be locked.
										$value   = (isset($_POST[$field_id_name][0]) ? $_POST[$field_id_name][0] : (isset($meta[$field_id_name][0]) ? $meta[$field_id_name][0] : '') );
										echo '<tr><td style="width:20%; border-bottom:1px solid #eee; padding:5px 2px">' . $label . '</td><td style="border-bottom:1px solid #eee; padding:5px 2px">' . $value . '</td></tr>';
									}elseif($type == 'embed'){
										$value   = (isset($_POST[$field_id_name][0]) ? $_POST[$field_id_name][0] : (isset($meta[$field_id_name][0]) ? $meta[$field_id_name][0] : '') );
										echo '<tr>
									   <td width="20%" align="right" valign="top" ><label for="' . $field_id_name . '">' . $label . '</label></td><td width="50%" valign="top"><textarea rows="4" class="form-control" name="custom_meta[' . $field_id_name . ']" id="' . $field_id_name . '" style="width:100%;" ';
										if($value!=''){ echo ' readonly '; }
										echo '>' . htmlentities($meta[$field_id_name][0]) . '</textarea>';
										if($value!=''){
											echo ' <a style="display:inline-block; padding:5px 10px; text-decoration:none; cursor:pointer; background-color:#eaeaea; border-radius:5px; border:1px solid #ddd;" onclick="document.getElementById(\'' . $field_id_name . '\').readOnly=false" >Edit embed code</a> ';
										}
										echo '</td><td width="30%" valign="top">' . htmlentities($value) .'</td></tr>';
									}
								}
								echo '</table>';
							}
						},
						$post_type_name,
						$box_context,
						$box_priority,
						array( $fields )
					);
				}
			);
		}
    }

	/**
     * Add admin columns
     *
     * Adds columns to the admin edit screen. Function is used with add_action
     *
     * @param array $columns Columns to be added to the admin edit screen.
     * @return array
     */
    function add_admin_columns( $columns ) {
        // If no user columns have been specified, add taxonomies
        if ( ! isset( $this->columns ) ) {
            $new_columns = array();
            // determine which column to add custom taxonomies after
            if ( is_array( $this->taxonomies ) && in_array( 'post_tag', $this->taxonomies ) || $this->post_type_name === 'post' ) {
                $after = 'tags';
            } elseif( is_array( $this->taxonomies ) && in_array( 'category', $this->taxonomies ) || $this->post_type_name === 'post' ) {
                $after = 'categories';
            } elseif( post_type_supports( $this->post_type_name, 'author' ) ) {
                $after = 'author';
            } else {
                $after = 'title';
            }
            // foreach exisiting columns
            foreach( $columns as $key => $title ) {
                // add exisiting column to the new column array
                $new_columns[$key] = $title;
                // we want to add taxonomy columns after a specific column
                if( $key === $after ) {
                    // If there are taxonomies registered to the post type.
                    if ( is_array( $this->taxonomies ) ) {
                        // Create a column for each taxonomy.
                        foreach( $this->taxonomies as $tax ) {
                            // WordPress adds Categories and Tags automatically, ignore these
                            if( $tax !== 'category' && $tax !== 'post_tag' ) {
                                // Get the taxonomy object for labels.
                                $taxonomy_object = get_taxonomy( $tax );
                                // Column key is the slug, value is friendly name.
                                $new_columns[ $tax ] = sprintf( __( '%s', $this->textdomain ), $taxonomy_object->labels->name );
                            }
                        }
                    }
                }
            }
            // overide with new columns
            $columns = $new_columns;
        } else {
            // Use user submitted columns, these are defined using the object columns() method.
            $columns = $this->columns;
        }
        return $columns;
    }
    /**
     * Populate admin columns
     *
     * Populate custom columns on the admin edit screen.
     *
     * @param string $column The name of the column.
     * @param integer $post_id The post ID.
     */
    function populate_admin_columns( $column, $post_id ) {
        // Get wordpress $post object.
        global $post;
        // determine the column
        switch( $column ) {
            // If column is a taxonomy associated with the post type.
            case ( taxonomy_exists( $column ) ) :
                // Get the taxonomy for the post
                $terms = get_the_terms( $post_id, $column );
                // If we have terms.
                if ( ! empty( $terms ) ) {
                    $output = array();
                    // Loop through each term, linking to the 'edit posts' page for the specific term.
                    foreach( $terms as $term ) {
                        // Output is an array of terms associated with the post.
                        $output[] = sprintf(
                            // Define link.
                            '<a href="%s">%s</a>',
                            // Create filter url.
                            esc_url( add_query_arg( array( 'post_type' => $post->post_type, $column => $term->slug ), 'edit.php' ) ),
                            // Create friendly term name.
                            esc_html( sanitize_term_field( 'name', $term->name, $term->term_id, $column, 'display' ) )
                        );
                    }
                    // Join the terms, separating them with a comma.
                    echo join( ', ', $output );
                // If no terms found.
                } else {
                    // Get the taxonomy object for labels
                    $taxonomy_object = get_taxonomy( $column );
                    // Echo no terms.
                    printf( __( 'No %s', $this->textdomain ), $taxonomy_object->labels->name );
                }
            break;
            // If column is for the post ID.
            case 'post_id' :
                echo $post->ID;
            break;
            // if the column is prepended with 'meta_', this will automagically retrieve the meta values and display them.
            case ( preg_match( '/^meta_/', $column ) ? true : false ) :
                // meta_book_author (meta key = book_author)
                $x = substr( $column, 5 );
                $meta = get_post_meta( $post->ID, $x );
                echo join( ", ", $meta );
            break;
            // If the column is post thumbnail.
            case 'icon' :
                // Create the edit link.
                $link = esc_url( add_query_arg( array( 'post' => $post->ID, 'action' => 'edit' ), 'post.php' ) );
                // If it post has a featured image.
                if ( has_post_thumbnail() ) {
                    // Display post featured image with edit link.
                    echo '<a href="' . $link . '">';
                        the_post_thumbnail( array(60, 60) );
                    echo '</a>';
                } else {
                    // Display default media image with link.
                    echo '<a href="' . $link . '"><img src="'. site_url( '/wp-includes/images/crystal/default.png' ) .'" alt="' . $post->post_title . '" /></a>';
                }
            break;
            // Default case checks if the column has a user function, this is most commonly used for custom fields.
            default :
                // If there are user custom columns to populate.
                if ( isset( $this->custom_populate_columns ) && is_array( $this->custom_populate_columns ) ) {
                    // If this column has a user submitted function to run.
                    if ( isset( $this->custom_populate_columns[ $column ] ) && is_callable( $this->custom_populate_columns[ $column ] ) ) {
                        // Run the function.
                        call_user_func_array(  $this->custom_populate_columns[ $column ], array( $column, $post ) );
                    }
                }
            break;
        } // end switch( $column )
    }
    /**
     * Filters
     *
     * User function to define which taxonomy filters to display on the admin page.
     *
     * @param array $filters An array of taxonomy filters to display.
     */
    function filters( $filters = array() ) {
        //$this->filters = (isset($filters) ? $filters : null);
        $this->set($filters, (isset($filters) ? $filters : null));
    }
    /**
     *  Add taxtonomy filters
     *
     * Creates select fields for filtering posts by taxonomies on admin edit screen.
    */
    function add_taxonomy_filters() {
        global $typenow;
        global $wp_query;
        // Must set this to the post type you want the filter(s) displayed on.
        if ( $typenow == $this->post_type_name ) {
            // if custom filters are defined use those
            if ( is_array( $this->filters ) ) {
                $filters = $this->filters;
            // else default to use all taxonomies associated with the post
            } else {
                $filters = $this->taxonomies;
            }
            if ( ! empty( $filters ) ) {
                // Foreach of the taxonomies we want to create filters for...
                foreach ( $filters as $tax_slug ) {
                    // ...object for taxonomy, doesn't contain the terms.
                    $tax = get_taxonomy( $tax_slug );
                    // Get taxonomy terms and order by name.
                    $args = array(
                        'orderby' => 'name',
                        'hide_empty' => false
                    );
                    // Get taxonomy terms.
                    $terms = get_terms( $tax_slug, $args );
                    // If we have terms.
                    if ( $terms ) {
                        // Set up select box.
                        printf( ' &nbsp;<select name="%s" class="postform">', $tax_slug );
                        // Default show all.
                        printf( '<option value="0">%s</option>', sprintf( __( 'Show all %s', $this->textdomain ), $tax->label ) );
                        // Foreach term create an option field...
                        foreach ( $terms as $term ) {
                            // ...if filtered by this term make it selected.
                            if ( isset( $_GET[ $tax_slug ] ) && $_GET[ $tax_slug ] === $term->slug ) {
                                printf( '<option value="%s" selected="selected">%s (%s)</option>', $term->slug, $term->name, $term->count );
                            // ...create option for taxonomy.
                            } else {
                                printf( '<option value="%s">%s (%s)</option>', $term->slug, $term->name, $term->count );
                            }
                        }
                        // End the select field.
                        print( '</select>&nbsp;' );
                    }
                }
            }
        }
    }
    /**
     * Columns
     *
     * Choose columns to be displayed on the admin edit screen.
     *
     * @param array $columns An array of columns to be displayed.
     */
    function columns( $columns ) {
        // If columns is set.
        if( isset( $columns ) ) {
            // Assign user submitted columns to object.
            $this->columns = $columns;
        }
    }
    /**
     * Populate columns
     *
     * Define what and how to populate a speicific admin column.
     *
     * @param string $column_name The name of the column to populate.
     * @param mixed $callback An anonyous function or callable array to call when populating the column.
     */
    function populate_column( $column_name, $callback ) {
        $this->custom_populate_columns[ $column_name ] = $callback;
    }
    /**
     * Sortable
     *
     * Define what columns are sortable in the admin edit screen.
     *
     * @param array $columns An array of columns that are sortable.
     */
    function sortable( $columns = array() ) {
        // Assign user defined sortable columns to object variable.
        $this->sortable = $columns;
        // Run filter to make columns sortable.
        $this->add_filter( 'manage_edit-' . $this->post_type_name . '_sortable_columns', array( &$this, 'make_columns_sortable' ) );
        // Run action that sorts columns on request.
        $this->add_action( 'load-edit.php', array( &$this, 'load_edit' ) );
    }
    /**
     * Make columns sortable
     *
     * Internal function that adds user defined sortable columns to WordPress default columns.
     *
     * @param array $columns Columns to be sortable.
     *
     */
    function make_columns_sortable( $columns ) {
        // For each sortable column.
        foreach ( $this->sortable as $column => $values ) {
            // Make an array to merge into wordpress sortable columns.
            $sortable_columns[ $column ] = $values[0];
        }
        // Merge sortable columns array into wordpress sortable columns.
        $columns = array_merge( $sortable_columns, $columns );
        return $columns;
    }

    /* Listens for when the post type being saved */
    public function save(){
         // Need the post type name again
		$post_type_name = $this->post_type_name;

		if(!isset($_POST['custom_post_type'])){
			$_POST['custom_post_type'] = 'post';
        }
		add_action( 'save_post',
			function() use( $post_type_name ){

				// Deny the WordPress autosave function
				if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return;

				if ( ! wp_verify_nonce( $_POST['custom_post_type'], plugin_basename(__FILE__) ) ) return;

				global $post;

				if( isset( $_POST ) && isset( $post->ID ) && get_post_type( $post->ID ) == $post_type_name ){
					global $custom_fields;

					// Loop through each meta box
					foreach( $custom_fields as $title => $fields ){
						// Loop through all fields
						foreach( $fields as $label => $type ){
                            $field_id_name = strtolower( str_replace( ' ', '_', $title ) ) . '_' . strtolower( str_replace( ' ', '_', $label ) );
                            update_post_meta( $post->ID, $field_id_name, (isset($_POST['custom_meta'][ $field_id_name ]) ? $_POST['custom_meta'][ $field_id_name ] : null) );
						}
					}
				}
			}
		);
    }

	public static function beautify( $string ){
		return ucwords( str_replace( '_', ' ', $string ) );
	}

	public static function uglify( $string ){
		return strtolower( str_replace( ' ', '_', $string ) );
	}

	public static function pluralize( $string ){
		$last = $string[strlen( $string ) - 1];

		if( $last == 'y' ){
			$cut = substr( $string, 0, -1 );
			//convert y to ies
			$plural = $cut . 'ies';
		}else{
			// just attach an s
			$plural = $string . 's';
		}

		return $plural;
	}

}


?>