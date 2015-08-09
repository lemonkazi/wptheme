<?php
    /*
    *
    *	Swift Slider Post Type
    *	------------------------------------------------
    *	Swift Slider
    * 	Copyright Swift Ideas 2014 - http://www.swiftideas.net
    *
    */

    /* SWIFT SLIDER CATEGORY
    ================================================== */
    $args = array(
        "label"             => __( 'Slide Categories', "swift-framework-admin" ),
        "singular_label"    => __( 'Slide Category', "swift-framework-admin" ),
        'public'            => true,
        'hierarchical'      => true,
        'show_ui'           => true,
        'show_in_nav_menus' => false,
        'args'              => array( 'orderby' => 'term_order' ),
        'rewrite'           => false,
        'query_var'         => true
    );
    register_taxonomy( 'swift-slider-category', 'swift-slider', $args );


    /* SWIFT SLIDER POST TYPE
    ================================================== */
    function swift_slider_register() {

        $labels = array(
            'name'               => __( 'Swift Slider', "swift-framework-admin" ),
            'singular_name'      => __( 'Slide', "swift-framework-admin" ),
            'add_new'            => __( 'Add New Slide', 'slide', "swift-framework-admin" ),
            'add_new_item'       => __( 'Add New Slide', "swift-framework-admin" ),
            'edit_item'          => __( 'Edit Slide', "swift-framework-admin" ),
            'new_item'           => __( 'New Slide', "swift-framework-admin" ),
            'view_item'          => __( 'View Slide', "swift-framework-admin" ),
            'search_items'       => __( 'Search Slides', "swift-framework-admin" ),
            'not_found'          => __( 'No slides have been added yet', "swift-framework-admin" ),
            'not_found_in_trash' => __( 'Nothing found in Trash', "swift-framework-admin" ),
            'parent_item_colon'  => ''
        );

        $args = array(
            'labels'              => $labels,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => false,
            'exclude_from_search' => true,
            'menu_icon'           => 'dashicons-format-image',
            'hierarchical'        => false,
            'rewrite'             => false,
            'supports'            => array( 'title', 'thumbnail' ),
            'has_archive'         => true,
            'taxonomies'          => array( 'swift-slider-category' )
        );

        register_post_type( 'swift-slider', $args );
    }

    add_action( 'init', 'swift_slider_register' );


    /* SWIFT SLIDER POST TYPE COLUMNS
    ================================================== */
    function swift_slider_edit_columns( $columns ) {
        $columns = array(
            "cb"                    => "<input type=\"checkbox\" />",
            "thumbnail"             => "",
            "title"                 => __( "Slide", "swift-framework-admin" ),
            "swift-slider-category" => __( "Slide Categories", "swift-framework-admin" )
        );

        return $columns;
    }

    add_filter( "manage_edit-swift-slider_columns", "swift_slider_edit_columns" );

?>