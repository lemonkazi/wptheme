<?php

    /* ==================================================

    Clients Post Type Functions

    ================================================== */


    /* CLIENTS CATEGORY
    ================================================== */
    function sf_clients_category_register() {
        $args = array(
            "label"             => __( 'Client Categories', "swift-framework-admin" ),
            "singular_label"    => __( 'Client Category', "swift-framework-admin" ),
            'public'            => true,
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_in_nav_menus' => false,
            'args'              => array( 'orderby' => 'term_order' ),
            'rewrite'           => false,
            'query_var'         => true
        );

        register_taxonomy( 'clients-category', 'clients', $args );
    }

    add_action( 'init', 'sf_clients_category_register' );


    /* CLIENTS POST TYPE
    ================================================== */
    function sf_clients_register() {

        $labels = array(
            'name'               => __( 'Clients', "swift-framework-admin" ),
            'singular_name'      => __( 'Client', "swift-framework-admin" ),
            'add_new'            => __( 'Add New', "swift-framework-admin" ),
            'add_new_item'       => __( 'Add New Client', "swift-framework-admin" ),
            'edit_item'          => __( 'Edit Client', "swift-framework-admin" ),
            'new_item'           => __( 'New Client', "swift-framework-admin" ),
            'view_item'          => __( 'View Client', "swift-framework-admin" ),
            'search_items'       => __( 'Search Clients', "swift-framework-admin" ),
            'not_found'          => __( 'No clients have been added yet', "swift-framework-admin" ),
            'not_found_in_trash' => __( 'Nothing found in Trash', "swift-framework-admin" ),
            'parent_item_colon'  => ''
        );

        $args = array(
            'labels'            => $labels,
            'public'            => true,
            'show_ui'           => true,
            'show_in_menu'      => true,
            'show_in_nav_menus' => false,
            'menu_icon'         => 'dashicons-businessman',
            'rewrite'           => false,
            'supports'          => array( 'title', 'thumbnail', 'excerpt', 'custom-fields', 'excerpt' ),
            'has_archive'       => true,
            'taxonomies'        => array( 'clients-category', 'post_tag' )
        );

        register_post_type( 'clients', $args );
    }

    add_action( 'init', 'sf_clients_register' );


    /* CLIENTS POST TYPE COLUMNS
    ================================================== */
    function sf_clients_edit_columns( $columns ) {
        $columns = array(
            "cb"               => "<input type=\"checkbox\" />",
            "thumbnail"        => "",
            "title"            => __( "Client", "swift-framework-admin" ),
            "clients-category" => __( "Categories", "swift-framework-admin" )
        );

        return $columns;
    }

    add_filter( "manage_edit-clients_columns", "sf_clients_edit_columns" );

?>