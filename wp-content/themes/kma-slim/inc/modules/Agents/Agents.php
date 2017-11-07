<?php

namespace Includes\Modules\Agents;

use GuzzleHttp\Client;
use Includes\Modules\CPT\CustomPostType;

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Agents {

	public $queryvar;
	public $agentArray;
    public $officeId;
    protected $agentData;

	public function __construct() {
	}

	public function createPostType() {

		$team = new CustomPostType( 'agent',
			[
				'supports'           => [ 'title', 'editor', 'thumbnail', 'author' ],
				'menu_icon'          => 'dashicons-businessman',
				'has_archive'        => false,
				'menu_position'      => null,
				'public'             => true,
				'publicly_queryable' => true,
				'hierarchical'       => true,
				'show_ui'            => true,
				'show_in_nav_menus'  => true,
				'_builtin'           => false,
				'rewrite'            => [
					'slug'       => 'team',
					//string Customize the permalink structure slug. Defaults to the $post_type value. Should be translatable.
					'with_front' => true,
					//bool Should the permalink structure be prepended with the front base. <br>
					//(example: if your permalink structure is /blog/, then your links will be: false-> /news/, true->/blog/news/). Defaults to true
					'feeds'      => true,
					//bool Should a feed permalink structure be built for this post type. Defaults to has_archive value
					'pages'      => false
					//bool Should the permalink structure provide for pagination. Defaults to true
				],
				/*'capability_type'    => array('agent','agents'),
				'capabilities' => array(
					'edit_post'          => 'edit_agents',
					'read_post'          => 'read_agents',
					'publish_posts'      => 'publish_agents',
					'edit_others_posts'  => 'edit_others_agents'
				),*/
			]
		);
		$team->addTaxonomy( 'office' );
		$team->createTaxonomyMeta( 'office', [ 'label' => 'Address', 'type' => 'textarea' ] );
		$team->createTaxonomyMeta( 'office', [ 'label' => 'Phone Number', 'type' => 'text' ] );
		$team->createTaxonomyMeta( 'office', [ 'label' => 'Fax Number', 'type' => 'text' ] );
		$team->createTaxonomyMeta( 'office', [ 'label' => 'GPS Coordinates', 'type' => 'text' ] );

		$team->addMetaBox(
			'Contact Info',
			[
				'Display Name' => 'text',
				'MLS IDs'      => 'text',
				'AKA'          => 'text',
				'Title'        => 'text',
				'Photo'        => 'image',
				'Email'        => 'text',
				'Website'      => 'text',
				'Office Phone' => 'text',
                'Cell Phone'   => 'text',
			]
		);

		$team->addMetaBox(
			'Social Media Info',
			[
				'Facebook'    => 'text',
				'Twitter'     => 'text',
				'LinkedIn'    => 'text',
				'Instagram'   => 'text',
				'YouTube'     => 'text',
				'Google Plus' => 'text'
			]
		);

	}

	public function getAgentNames() {
		$request = get_posts( [
			'post_type'      => 'agent',
			'posts_per_page' => -1,
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
			'offset'         => 0,
			'post_status'    => 'publish',
		] );

		$output = [];
		foreach ( $request as $item ) {
			array_push( $output, ( isset( $item->contact_info_display_name ) ? $item->contact_info_display_name : null ) );
		}

		return $output;
	}

	public function getTeam( $args = [] ) {

		$request = [
			'post_type'      => 'agent',
			'posts_per_page' => -1,
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
			'offset'         => 0,
			'post_status'    => 'publish',
		];

		$request = get_posts( array_merge( $request, $args ) );

		$output = [];
		foreach ( $request as $item ) {

			$terms      = wp_get_object_terms( $item->ID, 'office' );
			$categories = [];
			foreach ( $terms as $term ) {
				array_push( $categories, [
						'category-id'   => ( isset( $term->term_id ) ? $term->term_id : null ),
						'category-name' => ( isset( $term->name ) ? $term->name : null ),
						'category-slug' => ( isset( $term->slug ) ? $term->slug : null ),
					]
				);
			}

			array_push( $output, [
                'post_id'      => (isset($item->ID) ? $item->ID : null),
                'mls_name'     => (isset($item->post_title) ? $item->post_title : null),
                'name'         => (isset($item->contact_info_display_name) ? $item->contact_info_display_name : null),
                'aka'          => (isset($item->contact_info_aka) ? $item->contact_info_aka : null),
                'title'        => (isset($item->contact_info_title) ? $item->contact_info_title : null),
                'email_address'=> (isset($item->contact_info_email) ? $item->contact_info_email : null),
                'website'      => (isset($item->contact_info_website) ? $item->contact_info_website : null),
                'office_phone' => (isset($item->contact_info_office_phone) ? $item->contact_info_office_phone : null),
                'cell_phone'   => (isset($item->contact_info_cell_phone) ? $item->contact_info_cell_phone : null),
                'slug'         => (isset($item->post_name) ? $item->post_name : null),
                'thumbnail'    => (isset($item->contact_info_photo) ? $item->contact_info_photo : get_template_directory_uri() . '/img/agent-placeholder.jpg'),
                'short_ids'    => (isset($item->contact_info_mls_ids) ? $item->contact_info_mls_ids : null),
                'link'         => get_permalink($item->ID),
                'bio'          => (isset($item->post_content) ? $item->post_content : null),
                'social'       => [
                    'facebook'    => (isset($item->social_media_info_facebook) ? $item->social_media_info_facebook : null),
                    'twitter'     => (isset($item->social_media_info_twitter) ? $item->social_media_info_twitter : null),
                    'linkedin'    => (isset($item->social_media_info_linkedin) ? $item->social_media_info_linkedin : null),
                    'instagram'   => (isset($item->social_media_info_instagram) ? $item->social_media_info_instagram : null),
                    'youtube'     => (isset($item->social_media_info_youtube) ? $item->social_media_info_youtube : null),
                    'google_plus' => (isset($item->social_media_info_google) ? $item->social_media_info_google : null),
                ],
                'categories'   => $categories
            ]);

		}

		return $output;
	}

	public function getSingleAgent( $name ) {

		$output = $this->getTeam( [
			'posts_per_page' => 1,
            'meta_query' => [
                [
                    'key' => 'contact_info_display_name',
                    'value' => $name,
                ]
            ]
		] );

        if(isset($output[0])){
            return $output[0];
        }else{
            return false;
        }

	}

	public function getOffices( $args = [], $limit = 0 ) {

		$request = [
			'taxonomy'   => 'office',
			'hide_empty' => false,
		];

		$request = get_terms( array_merge( $request, $args ) );

		//chop to limit manually since SCP Order is ganked.
		if ( $limit != 0 ) {
			$request = array_slice( $request, 0, $limit );
		}

		$output = [];
		foreach ( $request as $item ) {
			$coordinates = explode( ',', get_term_meta( $item->term_id, 'office_gps_coordinates', true ) );
			$output[]    = [
				'id'        => $item->term_id,
				'name'      => $item->name,
                'bio'       => $item->post_content,
				'slug'      => $item->slug,
				'address'   => get_term_meta( $item->term_id, 'office_address', true ),
				'phone'     => get_term_meta( $item->term_id, 'office_phone_number', true ),
				'fax'       => get_term_meta( $item->term_id, 'office_fax_number', true ),
				'latitude'  => $coordinates[0],
				'longitude' => $coordinates[1]
			];
		}

		return $output;

	}

	public function getAgentListings( $agentIds ){

        $client   = new Client(['base_uri' => 'http://mothership.kerigan.com/api/v1/']);

        // make the API call
        $apiCall = $client->request(
            'GET',
            'agentlistings?agentId=' . $agentIds
        );

        return json_decode($apiCall->getBody());

    }

    public function setAgentSeo( $agentData ){

        $this->agentData = $agentData;

        add_filter('wpseo_title', function () {
            return $this->agentData['name'] . ' | ' . $this->agentData['title'] . ' | ' . get_bloginfo('name');
        });

        add_filter('wpseo_metadesc', function () {
            return strip_tags($this->agentData['bio']);
        });

        add_filter('wpseo_opengraph_image', function () {
            return $this->agentData['thumbnail'];
        });

        add_filter('wpseo_canonical',  function () {
            return get_the_permalink();
        });

        add_filter('wpseo_opengraph_url', function () {
            return get_the_permalink();
        });

    }

    protected function getFromMothership( $agentName )
    {

        $client = new Client(['base_uri' => 'https://mothership.kerigan.com/api/v1/']);

        // make the API call
        $apiCall = $client->request(
            'GET',
            'agents?'
            .'fullName='. $agentName
        );

        $results = json_decode($apiCall->getBody());

        $agentMothershipData = [
            'email_address' => $this->assembleMothershipData('email', $results->data),
            'website'       => $this->assembleMothershipData('url', $results->data),
            'office_phone'  => $this->assembleMothershipData('office_phone', $results->data),
            'cell_phone'    => $this->assembleMothershipData('cell_phone', $results->data),
            'short_ids'     => $this->getShortIds($results->data)
        ];

        //echo '<pre>',print_r($agentMothershipData),'</pre>';

        return $agentMothershipData;
    }

    protected function assembleMothershipData($parameter, $data){
        foreach($data as $entry){
            if($entry->$parameter != ''){
                return $entry->$parameter;
            }
        }
    }

    protected function getShortIds( $agentMothershipData )
    {
        $shortIds = [];
        foreach ($agentMothershipData as $entry){
            array_push($shortIds, $entry->short_id);
        }
        $shortIds = implode(',',$shortIds);
        return $shortIds;
    }

    protected function updateAgentsByMotherShip($agentData, $postID)
    {

        $updates = [
            'contact_info_display_name'  => $agentData['name'],
            'contact_info_email'         => $agentData['email_address'],
            'contact_info_office_phone'  => $agentData['office_phone'],
            'contact_info_cell_phone'    => $agentData['cell_phone'],
            'contact_info_website'       => $agentData['website'],
            'contact_info_mls_ids'       => $agentData['short_ids']
        ];

        foreach($updates as $key => $var){
            if(get_post_meta($postID, $key, true) == '') {
                update_post_meta($postID, $key, $var);
            }
        }

    }

    public function assembleAgentData( $agentName )
    {

        $agentData = $this->getSingleAgent($agentName);
        $agentData['short_ids']  = trim(implode('|', explode(',', $agentData['short_ids'])));
        return $agentData;

    }

    public function updateAgent($agentData)
    {
        $agentMothershipData = $this->getFromMothership($agentData['mls_name']);
        $agentMothershipData['name'] = $agentData['mls_name'];
        $this->updateAgentsByMotherShip( $agentMothershipData, $agentData['post_id']);
        //echo '<pre>',print_r($agentMothershipData),'</pre>';
    }

    public function getAgentById($shortId)
    {
        $client = new Client(['base_uri' => 'https://mothership.kerigan.com/api/v1/']);

        // make the API call
        $apiCall = $client->request(
            'GET',
            'agents?'
            .'shortId='. $shortId
        );

        $agentData = json_decode($apiCall->getBody());
        return $agentData;


    }

}