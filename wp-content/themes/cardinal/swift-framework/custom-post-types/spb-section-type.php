<?php

    /* ==================================================

    SPB Section Post Type Functions

    ================================================== */


    /* SPB SECTION CATEGORY
    ================================================== */
    function sf_spb_section_category_register() {
        $args = array(
            "label"             => __( 'Categories', "swift-framework-admin" ),
            "singular_label"    => __( 'Category', "swift-framework-admin" ),
            'public'            => true,
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_in_nav_menus' => false,
            'args'              => array( 'orderby' => 'term_order' ),
            'rewrite'           => array(
                'slug'         => __( 'spb-section-category', 'swift-framework-admin' ),
                'with_front'   => false,
                'hierarchical' => true,
            ),
            'query_var'         => true
        );

        register_taxonomy( 'spb-section-category', 'spb-section', $args );
    }

    add_action( 'init', 'sf_spb_section_category_register' );


    /* SPB SECTION POST TYPE
    ================================================== */
    function sf_spb_section_register() {

        $labels = array(
            'name'               => _x( 'SPB Sections', 'post type general name', "swift-framework-admin" ),
            'singular_name'      => _x( 'SPB Section', 'post type singular name', "swift-framework-admin" ),
            'add_new'            => _x( 'Add New', 'SPB Section', "swift-framework-admin" ),
            'add_new_item'       => __( 'Add New SPB Section', "swift-framework-admin" ),
            'edit_item'          => __( 'Edit SPB Section', "swift-framework-admin" ),
            'new_item'           => __( 'New SPB Section', "swift-framework-admin" ),
            'view_item'          => __( 'View SPB Section', "swift-framework-admin" ),
            'search_items'       => __( 'Search SPB Sections', "swift-framework-admin" ),
            'not_found'          => __( 'No SPB Sections have been added yet', "swift-framework-admin" ),
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
            'menu_icon'           => 'dashicons-schedule',
            'hierarchical'        => false,
            'rewrite'             => false,
            'supports'            => array( 'title', 'editor' ),
            'has_archive'         => true,
            'taxonomies'          => array( 'spb-section-category' )

        );

        register_post_type( 'spb-section', $args );
    }

    add_action( 'init', 'sf_spb_section_register' );


    /* SPB POST TYPE COLUMNS
    ================================================== */
    function sf_spb_section_edit_columns( $columns ) {
        $columns = array(
            "cb"                   => "<input type=\"checkbox\" />",
            "title"                => __( "SPB Section", "swift-framework-admin" ),
            "spb-section-category" => __( "Categories", "swift-framework-admin" )
        );

        return $columns;
    }

    add_filter( "manage_edit-spb-section_columns", "sf_spb_section_edit_columns" );


?>