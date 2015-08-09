<?php

    /* ==================================================

    Gallery Post Type Functions

    ================================================== */


    /* GALLERY CATEGORY
    ================================================== */
    function sf_gallery_category_register() {

        $gallery_permalinks = get_option( 'sf_galleries_permalinks' );

        $args = array(
            "label"             => __( 'Gallery Categories', "swift-framework-admin" ),
            "singular_label"    => __( 'Gallery Category', "swift-framework-admin" ),
            'public'            => true,
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_in_nav_menus' => false,
            'args'              => array( 'orderby' => 'term_order' ),
            'rewrite'           => array(
                'slug'       => empty( $gallery_permalinks['category_base'] ) ? __( 'gallery-category', 'swift-framework-admin' ) : $gallery_permalinks['category_base'],
                'with_front' => false
            ),
            'query_var'         => true
        );

        register_taxonomy( 'gallery-category', 'gallery', $args );
    }

    add_action( 'init', 'sf_gallery_category_register' );


    /* GALLERIES POST TYPE
    ================================================== */
    function sf_galleries_register() {

        $galleries_permalinks = get_option( 'sf_galleries_permalinks' );
        $galleries_permalink  = empty( $galleries_permalinks['galleries_base'] ) ? __( 'galleries', 'swift-framework-admin' ) : $galleries_permalinks['galleries_base'];

        $labels = array(
            'name'               => __( 'Galleries', "swift-framework-admin" ),
            'singular_name'      => __( 'Gallery', "swift-framework-admin" ),
            'add_new'            => __( 'Add New', "swift-framework-admin" ),
            'add_new_item'       => __( 'Add New Gallery', "swift-framework-admin" ),
            'edit_item'          => __( 'Edit Gallery', "swift-framework-admin" ),
            'new_item'           => __( 'New Gallery', "swift-framework-admin" ),
            'view_item'          => __( 'View Gallery', "swift-framework-admin" ),
            'search_items'       => __( 'Search Galleries', "swift-framework-admin" ),
            'not_found'          => __( 'No galleries have been added yet', "swift-framework-admin" ),
            'not_found_in_trash' => __( 'Nothing found in Trash', "swift-framework-admin" ),
            'parent_item_colon'  => ''
        );

        $args = array(
            'labels'            => $labels,
            'public'            => true,
            'show_ui'           => true,
            'show_in_menu'      => true,
            'show_in_nav_menus' => true,
            'menu_icon'         => 'dashicons-format-gallery',
            'hierarchical'      => false,
            'rewrite'           => $galleries_permalink != "galleries" ? array(
                'slug'       => untrailingslashit( $galleries_permalink ),
                'with_front' => false,
                'feeds'      => true
            )
                : false,
            'supports'          => array( 'title', 'thumbnail', 'editor', 'custom-fields', 'excerpt', 'comments' ),
            'has_archive'       => true,
            'taxonomies'        => array( 'gallery-category' )
        );

        register_post_type( 'galleries', $args );
    }

    add_action( 'init', 'sf_galleries_register' );


    /* GALLERIES POST TYPE COLUMNS
    ================================================== */
    function sf_galleries_edit_columns( $columns ) {
        $columns = array(
            "cb"               => "<input type=\"checkbox\" />",
            "thumbnail"        => "",
            "title"            => __( "Gallery", "swift-framework-admin" ),
            "gallery-category" => __( "Categories", "swift-framework-admin" )
        );

        return $columns;
    }

    add_filter( "manage_edit-galleries_columns", "sf_galleries_edit_columns" );

?>