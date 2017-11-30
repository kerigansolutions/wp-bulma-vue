<?php

namespace Includes\Modules\Team;

use Includes\Modules\CPT\CustomPostType;

class Physicians
{

    public function __construct()
    {
    }

    public function createPostType()
    {

        $team = new CustomPostType('Physician',
            [
                'supports'           => ['title', 'editor', 'author'],
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
                    'slug'       => 'physicians',
                    'with_front' => true,
                    'feeds'      => true,
                    'pages'      => false
                ]
            ]
        );

        $team->addTaxonomy('Type');

        $team->addMetaBox(
            'Contact Info',
            [
                'Photo'        => 'image',
                'Specialties'  => 'textarea',
                'YouTube Code' => 'text',
            ]
        );

    }

    /**
     * @return null
     */
    public function createAdminColumns()
    {

        add_filter('manage_physician_posts_columns',
            function ($defaults) {
                $defaults = [
                    'title'       => 'Name',
                    'specialties' => 'Specialties',
                    'ytcode'      => 'YouTube Code',
                    'photo'       => 'Photo'
                ];

                return $defaults;
            }, 0);

        add_action('manage_physician_posts_custom_column', function ($column_name, $post_ID) {
            switch ($column_name) {
                case 'photo':
                    $photo = get_post_meta($post_ID, 'contact_info_photo', true);
                    echo(isset($photo) ? '<img src ="' . $photo . '" class="img-fluid" style="width:200px; max-width:100%;" >' : null);
                    break;

                case 'specialties':
                    $object = get_post_meta($post_ID, 'contact_info_specialties', true);
                    echo(isset($object) ? $object : null);
                    break;

                case 'ytcode':
                    $object = get_post_meta($post_ID, 'contact_info_youtube_code', true);
                    echo(isset($object) ? $object : null);
                    break;

            }
        }, 0, 2);

    }

    public function getPhysicians($args = [], $taxonomy = '')
    {

        $request = [
            'post_type'      => 'physician',
            'posts_per_page' => -1,
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
            'offset'         => 0,
            'post_status'    => 'publish',
        ];

        if ( $taxonomy != '' ) {
            $categoryarray = [
                'relation' => 'AND',
                [
                    'taxonomy'         => 'type',
                    'field'            => 'slug',
                    'terms'            => $taxonomy,
                    'include_children' => false,
                ],
            ];
            $request['tax_query'] = $categoryarray;
        }

        $request = get_posts(array_merge($request, $args));

        $output = [];
        foreach ($request as $item) {

            array_push($output, [
                'id'           => (isset($item->ID) ? $item->ID : null),
                'name'         => $item->post_title,
                'slug'         => (isset($item->post_name) ? $item->post_name : null),
                'specialties'  => (isset($item->contact_info_specialties) ? $item->contact_info_specialties : null),
                'youtube_code' => (isset($item->contact_info_youtube_code) ? $item->contact_info_youtube_code : null),
                'photo'        => (isset($item->contact_info_photo) ? $item->contact_info_photo : null),
                'link'         => get_permalink($item->ID),
            ]);

        }

        return $output;
    }

    public function getSingle($name)
    {

        $output = $this->getPhysicians([
            'title'          => $name,
            'posts_per_page' => 1,
        ]);

        return $output[0];
    }

    public function getPhysicianNames()
    {

        $request = $this->getPhysicians([]);

        $output = [];
        foreach ($request as $item) {
            array_push($output, (isset($item->post_title) ? $item->post_title : null));
        }

        return $output;
    }

}