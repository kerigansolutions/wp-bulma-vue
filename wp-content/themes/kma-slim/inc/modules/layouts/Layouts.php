<?php

/**
 * Layouts class
 */
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

        $page->addTaxonomy('Layout', array(
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
            array(
                'description' => $description,
                'slug'        => $slug
            )
        );

    }

}