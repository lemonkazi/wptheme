<?php

    /* ==================================================

    Directory Post Type Functions

    ================================================== */


    /* DIRECTORY CATEGORY
    ================================================== */
    function sf_directory_category_register() {

        $directory_permalinks = get_option( 'sf_directory_permalinks' );

        $args = array(
            "label"             => __( 'Categories', "swift-framework-admin" ),
            "singular_label"    => __( 'Category', "swift-framework-admin" ),
            'public'            => true,
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_in_nav_menus' => false,
            'args'              => array( 'orderby' => 'term_order' ),
            'rewrite'           => array(
                'slug'       => empty( $directory_permalinks['category_base'] ) ? __( 'directory-category', 'swift-framework-admin' ) : $directory_permalinks['category_base'],
                'with_front' => false
            ),
            'query_var'         => true
        );

        register_taxonomy( 'directory-category', 'directory', $args );
    }

    add_action( 'init', 'sf_directory_category_register' );


    /* DIRECTORY LOCATION
    ================================================== */
    function sf_directory_location_register() {

        $directory_permalinks = get_option( 'sf_directory_permalinks' );

        $args = array(
            "label"             => __( 'Locations', "swift-framework-admin" ),
            "singular_label"    => __( 'Location', "swift-framework-admin" ),
            'public'            => true,
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_in_nav_menus' => false,
            'args'              => array( 'orderby' => 'term_order' ),
            'rewrite'           => array(
                'slug'       => empty( $directory_permalinks['location_base'] ) ? __( 'directory-location', 'swift-framework-admin' ) : $directory_permalinks['location_base'],
                'with_front' => false
            ),
            'query_var'         => true
        );

        register_taxonomy( 'directory-location', 'directory', $args );
    }

    add_action( 'init', 'sf_directory_location_register' );


    /* DIRECTORY POST TYPE
    ================================================== */
    function sf_directory_register() {

        $directory_permalinks = get_option( 'sf_directory_permalinks' );
        $directory_permalink  = empty( $directory_permalink['directory_base'] ) ? __( 'directory', 'swift-framework-admin' ) : $directory_permalink['directory_base'];

        $labels = array(
            'name'               => _x( 'Directory', 'post type general name', "swift-framework-admin" ),
            'singular_name'      => _x( 'Directory Item', 'post type singular name', "swift-framework-admin" ),
            'add_new'            => _x( 'Add New', 'directory item', "swift-framework-admin" ),
            'add_new_item'       => __( 'Add New Directory Item', "swift-framework-admin" ),
            'edit_item'          => __( 'Edit Directory Item', "swift-framework-admin" ),
            'new_item'           => __( 'New Directory Item', "swift-framework-admin" ),
            'view_item'          => __( 'View Diretory Item', "swift-framework-admin" ),
            'search_items'       => __( 'Search Directory Items', "swift-framework-admin" ),
            'not_found'          => __( 'No directory items have been added yet', "swift-framework-admin" ),
            'not_found_in_trash' => __( 'Nothing found in Trash', "swift-framework-admin" ),
            'parent_item_colon'  => ''
        );

        $args = array(
            'labels'            => $labels,
            'public'            => true,
            'show_ui'           => true,
            'show_in_menu'      => true,
            'show_in_nav_menus' => false,
            'menu_icon'         => 'dashicons-groups',
            'hierarchical'      => false,
            'rewrite'           => $directory_permalink != "directory" ? array(
                'slug'       => untrailingslashit( $directory_permalink ),
                'with_front' => false,
                'feeds'      => true
            )
                : false,
            'supports'          => array( 'title', 'editor', 'thumbnail', 'custom-fields', 'excerpt' ),
            'has_archive'       => true,
            'taxonomies'        => array( 'directory-category', 'directory-location' )

        );

        register_post_type( 'directory', $args );
    }

    add_action( 'init', 'sf_directory_register' );


    /* DIRECTORY POST TYPE COLUMNS
    ================================================== */
    function sf_directory_edit_columns( $columns ) {
        $columns = array(
            "cb"                 => "<input type=\"checkbox\" />",
            "thumbnail"          => "",
            "title"              => __( "Directory Item", "swift-framework-admin" ),
            "description"        => __( "Description", "swift-framework-admin" ),
            "location"           => __( "Address", "swift-framework-admin" ),
            "directory-category" => __( "Categories", "swift-framework-admin" )
        );

        return $columns;
    }

    add_filter( "manage_edit-directory_columns", "sf_directory_edit_columns" );


?>