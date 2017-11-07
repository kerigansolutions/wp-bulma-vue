<?php

namespace Includes\Modules\Layouts;

use Includes\Modules\CPT\CustomPostType;

/**
 * Layouts class
 */

 // Exit if accessed directly.
if (! defined('ABSPATH')) {
    exit;
}

class Layouts
{
    /**
     * Layouts constructor.
     */
    function __construct()
    {

    }

    /**
     * @return null
     */
    public function createPostType()
    {
        $page = new CustomPostType('Page');
        $page->addMetaBox(
            'Page Information',
            array(
                'Headline' => 'text',
                'Subhead'  => 'text'
            )
        );

        $page->addTaxonomy('layout', array(
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array('slug' => 'format'),
            'capabilities'      => array(
                'manage_terms' => '',
                'edit_terms'   => '',
                'delete_terms' => '',
                'assign_terms' => 'edit_posts'
            ),
            'public'            => true,
            'show_in_nav_menus' => false,
            'show_tagcloud'     => false,
        ));

        $page->convertCheckToRadio('layout');

    }

    /**
     * @return null
     */
    public function createDefaultFormats()
    {

        add_action('init', function () {
            wp_insert_term(
                'Default',
                'layout',
                array(
                    'description' => '',
                    'slug'        => 'default'
                )
            );
        });

    }

    /**
     * @param term
     * @param slug
     * @param description
     */
    public function createLayout($term = '', $description = '', $slug = '')
    {
        wp_insert_term(
            $term,
            'layout',
            [
                'description' => $description,
                'slug'        => $slug
            ]
        );

    }

    public function addContentBox($term = 'default', $title = 'Content'){

        $page = new CustomPostType('Page');
        $page->addMetaBox(
            $title,
            array(
                'HTML' => 'wysiwyg'
            )
        );

    }

}
