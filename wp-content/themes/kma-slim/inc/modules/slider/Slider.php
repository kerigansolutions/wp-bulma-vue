<?php
/**
 * Slider Class
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) { exit; }

class Slider {

    /**
     * Slider constructor.
     */
    function __construct() {

    }

    /**
     * @return null
     */
    public function createPostType() {

        $slider = new Custom_Post_Type( 'Slide Image', array(
            'supports'           => array( 'title', 'revisions' ),
            'menu_icon'          => 'dashicons-images-alt2',
            'rewrite'            => array( 'with_front' => false ),
            'has_archive'        => false,
            'menu_position'      => null,
            'public'             => false,
            'publicly_queryable' => false,
        ) );

        $slider->add_taxonomy( 'Slider' );

        $slider->add_meta_box( 'Slide Details', array(
            'Photo File'         => 'image',
            'Headline'           => 'text',
            'Caption'            => 'text',
            'Alt Tag'            => 'text',
            'Link'               => 'text',
            'Open in New Window' => 'boolean',
        ) );

        $slider->add_meta_box(
            'Photo Description',
            array(
                'HTML' => 'wysiwyg',
            )
        );

    }

    /**
     * @return null
     */
    public function createAdminColumns() {

        //TODO: make this work...

    }

    /**
     * @param slider ( post type category )
     * @return array
     */
    public function getSlides( $category = '' ){

        $request = array(
            'posts_per_page' => - 1,
            'offset'         => 0,
            'order'          => 'ASC',
            'orderby'        => 'menu_order',
            'post_type'      => 'slide_image',
            'post_status'    => 'publish',
        );

        if ( $category != '' ) {
            $categoryarray        = array(
                array(
                    'taxonomy'         => 'slider',
                    'field'            => 'slug',
                    'terms'            => $category,
                    'include_children' => false,
                ),
            );
            $request['tax_query'] = $categoryarray;
        }

        $slidelist = get_posts( $request );

        $slideArray = array();
        foreach ( $slidelist as $slide ){

            array_push($slideArray, array(
                'id'            => (isset($slide->ID)                               ? $slide->ID : null),
                'name'          => (isset($slide->post_title)                       ? $slide->post_title : null),
                'slug'          => (isset($slide->post_name)                        ? $slide->post_name : null),
                'photo'         => (isset($slide->slide_details_photo_file)         ? $slide->slide_details_photo_file : null),
                'headline'      => (isset($slide->slide_details_headline)           ? $slide->slide_details_headline : null),
                'caption'       => (isset($slide->slide_details_caption)            ? $slide->slide_details_caption : null),
                'alt'           => (isset($slide->slide_details_alt_tag)            ? $slide->slide_details_alt_tag : null),
                'url'           => (isset($slide->slide_details_link)               ? $slide->slide_details_link : null),
                'target'        => (isset($slide->slide_details_open_in_new_window) ? $slide->slide_details_open_in_new_window : null),
                'description'   => (isset($slide->photo_description_html)           ? $slide->photo_description_html : null),
                'link'          => get_permalink($slide->ID),

            ));

        }

        return $slideArray;

    }

    /**
     * @param slider ( post type category )
     * @return HTML
     */
    public function getSlider($category = ''){

        $slides = $this->getSlides($category);
        $slider = '';

        $i = 0;
        foreach($slides as $slide){

            $slider .= '<slide image="'.$slide['photo'].'" '.( $i==0 ? ':active="true"' : '' ).'>
                    <section class="hero is-fullheight is-transparent white-80">
                        <div class="hero-body">
                            <div class="container">'
                                . ($slide['headline'] != '' ? '<h2 class="title is-1">'.$slide['headline'].'</h2>' : '')
                                . ($slide['caption'] != '' ? '<p class="subtitle is-3">'.$slide['caption'].'</p>' : '')
                                . ($slide['description'] != '' ? $slide['description'] : '') .
                            '</div>
                        </div>
                    </section>
                </slide>';
            $i++;
        }

        return $slider;

    }

}