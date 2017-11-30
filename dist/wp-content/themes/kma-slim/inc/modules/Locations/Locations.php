<?php
/**
 * Created by PhpStorm.
 * User: Bryan
 * Date: 10/4/2017
 * Time: 9:37 AM
 */

namespace Includes\Modules\Locations;

use Includes\Modules\CPT\CustomPostType;

class Locations
{

    public function __construct()
    {
    }

    public function createPostType(){

        $locations = new CustomPostType(
            'Location',
            array(
                'supports'           => array('title', 'revisions'),
                'menu_icon'          => 'dashicons-location',
                'has_archive'        => false,
                'menu_position'      => null,
                'public'             => false,
                'publicly_queryable' => false,
            )
        );

        $locations->addMetaBox(
            'Location Info',
            array(
                'Photo'           => 'image',
                'Address'         => 'textarea',
                'Phone Number'    => 'text',
                'Fax Number'      => 'text',
                'GPS Coordinates' => 'text'
            )
        );

        $locations->addMetaBox(
            'Location Description',
            array(
                'html' => 'wysiwyg'
            )
        );

    }

    public function createAdminColumns()
    {

        add_filter('manage_location_posts_columns',
            function ($defaults) {
                $defaults = [
                    'title'   => 'Name',
                    'address' => 'Address',
                    'phone'   => 'Phone Number',
                    'fax'     => 'Fax Number',
                    'gps'     => 'GPS Coordinates',
                    'photo'   => 'Photo'
                ];

                return $defaults;
            }, 0);

        add_action('manage_location_posts_custom_column', function ($column_name, $post_ID) {
            switch ($column_name) {
                case 'photo':
                    $photo = get_post_meta($post_ID, 'location_info_photo', true);
                    echo(isset($photo) ? '<img src ="' . $photo . '" class="img-fluid" style="width:200px; max-width:100%;" >' : null);
                    break;

                case 'address':
                    $object = get_post_meta($post_ID, 'location_info_address', true);
                    echo(isset($object) ? $object : null);
                    break;

                case 'phone':
                    $object = get_post_meta($post_ID, 'location_info_phone_number', true);
                    echo(isset($object) ? $object : null);
                    break;

                case 'fax':
                    $object = get_post_meta($post_ID, 'location_info_fax_number', true);
                    echo(isset($object) ? $object : null);
                    break;

                case 'gps':
                    $object = get_post_meta($post_ID, 'location_info_gps_coordinates', true);
                    echo(isset($object) ? $object : null);
                    break;

            }
        }, 0, 2);

    }

    public function getLocations( $args = [] ) {

        $request = [
            'post_type'      => 'location',
            'posts_per_page' => -1,
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
            'offset'         => 0,
            'post_status'    => 'publish',
        ];

        $request = get_posts( array_merge( $request, $args ) );

        $output = [];
        foreach ( $request as $item ) {

            $gpsCoordinates = isset($item->location_info_gps_coordinates) ? explode(',', $item->location_info_gps_coordinates) : null;

            array_push( $output, [
                'id'        => (isset($itemID) ? $item->ID : null),
                'name'      => $item->post_title,
                'address'   => (isset($item->location_info_address) ? $item->location_info_address : null),
                'latitude'  => (isset($gpsCoordinates) ? $gpsCoordinates[0] : null),
                'longitude' => (isset($gpsCoordinates) ? $gpsCoordinates[1] : null),
                'phone'     => (isset($item->location_info_phone_number) ? $item->location_info_phone_number : null),
                'fax'       => (isset($item->location_info_fax_number) ? $item->location_info_fax_number : null),
                'slug'      => (isset($item->post_name) ? $item->post_name : null),
                'photo'     => (isset($item->location_info_photo) ? $item->location_info_photo : null),
                'link'      => get_permalink($item->ID),
            ]);

        }

        return $output;

    }

}