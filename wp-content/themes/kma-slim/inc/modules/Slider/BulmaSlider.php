<?php

namespace Includes\Modules\Slider;

use Includes\Modules\CPT\CustomPostType;

class BulmaSlider
{

    /**
     * Slider constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return null
     */
    public function createPostType()
    {
        $slider = new CustomPostType('Slide Image', [
            'supports'           => [ 'title', 'revisions', 'page-attributes' ],
            'menu_icon'          => 'dashicons-images-alt2',
            'rewrite'            => [ 'with_front' => false ],
            'hierarchical'       => false,
            'has_archive'        => false,
            'menu_position'      => null,
            'public'             => false,
            'publicly_queryable' => false,
        ]);

        $slider->addTaxonomy('Slider');

        $slider->addMetaBox('Slide Details', [
            'Photo File'         => 'image',
            'Headline'           => 'text',
            'Caption'            => 'text',
            'Alt Tag'            => 'text',
            'Link'               => 'text',
            'Open in New Window' => 'boolean',
        ]);

        $slider->addMetaBox(
            'Photo Description',
            [
                'HTML' => 'wysiwyg',
            ]
        );
    }

    /**
     * @return null
     */
    public function createAdminColumns()
    {

        //TODO: make this work...
    }

    /**
     * @param slider ( post type category )
     * @return array
     */
    public function getSlides($category = '')
    {
        $request = [
            'posts_per_page' => - 1,
            'offset'         => 0,
            'order'          => 'ASC',
            'orderby'        => 'menu_order',
            'post_type'      => 'slide_image',
            'post_status'    => 'publish',
        ];

        if ($category != '') {
            $categoryarray        = [
                [
                    'taxonomy'         => 'slider',
                    'field'            => 'slug',
                    'terms'            => $category,
                    'include_children' => false,
                ],
            ];
            $request['tax_query'] = $categoryarray;
        }

        $slidelist = get_posts($request);

        $slideArray = [];
        foreach ($slidelist as $slide) {
            array_push($slideArray, [
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

            ]);
        }

        return $slideArray;
    }

    /**
     * @param slider ( post type category )
     * @return HTML
     */
    public function getSlider($category = '')
    {
        $slides = $this->getSlides($category);
        $slider = '';

        $i = 0;
        foreach ($slides as $slide) {
            $slider .= '<bulma-slide :id="'.$i.'" image="'.$slide['photo'].'" '.($i==0 ? ':active="true"' : '').'>
                <div class="container">
                    <section class="slide-content">'
                            . ($slide['headline'] != '' ? '<p class="title slider-title">'.$slide['headline'].'</p>' : '')
                            . ($slide['caption'] != '' ? '<p class="subtitle slider-subtitle">'.$slide['caption'].'</p>' : '')
                            . ($slide['description'] != '' ? '<div class="slider-description">'.$slide['description'].'</div>' : '') .
                        '</section>
                    </div>
                </bulma-slide>';
            $i++;
        }

        return $slider;
    }
}
