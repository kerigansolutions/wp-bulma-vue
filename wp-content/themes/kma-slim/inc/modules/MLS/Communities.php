<?php
namespace Includes\Modules\MLS;

use Includes\Modules\CPT\CustomPostType;

class Communities {

	/**
	 * Community constructor.
	 */
	function __construct() {

	}

	/**
	 * @return null
	 */
	public function createPostType() {

		$communities = new CustomPostType(
			'Community',
			array(
				'supports'           => array('title', 'editor', 'thumbnail', 'revisions'),
				'menu_icon'          => 'dashicons-location',
				'has_archive'        => true,
				'menu_position'      => null,
				'public'             => true,
				'publicly_queryable' => true,
                //'capability_type'    => array('community','communities'),
			)
		);

		$communities->addMetaBox(
			'Community Info',
			array(
			    'Area Image'    => 'image',
				'Database Name' => 'text',
				'Latitude'      => 'text',
				'Longitude'     => 'text'
			)
		);

	}

	/*
	 * @return $communities
	 */
	public function getCommunities(){

		$communitylist = get_posts(array(
			'posts_per_page' => -1,
			'offset'         => 0,
			'order'          => 'ASC',
			'orderby'        => 'menu_order',
			'post_type'      => 'community',
			'post_status'    => 'publish',
		));

		$communities = array();

		foreach ($communitylist as $community) {

			$communities[] = array(
				'id'          => $community->ID,
				'title'       => $community->post_title,
				'name'        => get_post_meta( $community->ID, 'community_info_database_name', true ),
                'photo'       => get_post_meta( $community->ID, 'community_info_area_image', true ),
				'latitude'    => get_post_meta( $community->ID, 'community_info_latitude', true ),
				'longitude'   => get_post_meta( $community->ID, 'community_info_longitude', true ),
                'link'        => get_permalink($community->ID)
			);

		}

		return $communities;

	}

}
