<?php

    /*
    *
    *	Directory Functions
    *	------------------------------------------------
    *	Swift Framework
    * 	Copyright Swift Ideas 2014 - http://www.swiftideas.net
    *
    *	sf_directory()
    *
    */

    if ( ! function_exists( 'sf_directory' ) ) {
        function sf_directory() {

            $search_term     = "";
            $category_term   = "";
            $location_term   = "";
            $directory_itens = array();
            $count           = 0;

            if ( ! empty( $_REQUEST['search_term'] ) ) {
                $search_term = $_REQUEST['search_term'];
            }

            $tax_query          = array();
            $tax_query_category = array();
            $tax_query_location = array();

            if ( isset( $_REQUEST['location_term'] ) && $_REQUEST['location_term'] != '' ) {

                $location_term      = $_REQUEST['location_term'];
                $tax_query_location = array(
                    'taxonomy' => 'directory-location',
                    'field'    => 'slug',
                    'terms'    => array( $location_term )
                );

                array_push( $tax_query, $tax_query_location );

            }

            if ( isset( $_REQUEST['category_term'] ) && $_REQUEST['category_term'] != '' && $_REQUEST['category_term'] != 'All' ) {

                $category_term      = $_REQUEST['category_term'];
                $tax_query_category = array(
                    'taxonomy' => 'directory-category',
                    'field'    => 'slug',
                    'terms'    => array( $category_term )
                );

                array_push( $tax_query, $tax_query_category );

            }

            $search_query_args = array(
                's'                => $search_term,
                'post_type'        => 'directory',
                'post_status'      => 'publish',
                'suppress_filters' => false,
                'numberposts'      => - 1,
                'tax_query'        => $tax_query
            );


            $search_query_args = http_build_query( $search_query_args );
            $search_results    = get_posts( $search_query_args );

            foreach ( $search_results as $result ) {

                $directory_item                = array();
                $directory_item["pin_title"]   = $result->post_title;
                $directory_item["pin_content"] = $result->post_content;

                //Get the excerpt
                $content        = $result->post_content;
                $excerpt_length = 20;
                $words          = explode( ' ', $content, $excerpt_length + 1 );
                $categories     = wp_get_post_terms( $result->ID, "directory-category" );
                $locations      = wp_get_post_terms( $result->ID, "directory-location" );
                $category_list  = $location_list = "";
                $c              = $l = 0;
                if ( $categories ) {
                    foreach ( $categories as $category ) {
                        if ( $c == 0 ) {
                            $category_list .= $category->name;
                        } else {
                            $category_list .= ', ' . $category->name;
                        }
                    }
                }
                if ( $locations ) {
                    foreach ( $locations as $location ) {
                        if ( $l == 0 ) {
                            $location_list .= $location->name;
                        } else {
                            $location_list .= ', ' . $location->name;
                        }
                    }
                }

                if ( count( $words ) > $excerpt_length ) :
                    array_pop( $words );
                    array_push( $words, '...' );
                    $content = implode( ' ', $words );
                endif;

                $directory_item["pin_short_content"] = $content;

                $pin_img_url                        = wp_get_attachment_image_src( sf_get_post_meta( $result->ID, 'sf_directory_map_pin', true ), 'full' );
                $img_src                            = wp_get_attachment_image_src( get_post_thumbnail_id( $result->ID ), 'thumb-image' );
                $directory_item["pin_logo_url"]     = $pin_img_url[0];
                $directory_item["pin_thumbnail"]    = $img_src[0];
                $directory_item["pin_address"]      = sf_get_post_meta( $result->ID, 'sf_directory_address', true );
                $directory_item["pin_link"]         = esc_url( sf_get_post_meta( $result->ID, 'sf_directory_pin_link', true ) );
                $directory_item["pin_button_text"]  = sf_get_post_meta( $result->ID, 'sf_directory_pin_button_text', true );
                $directory_item["pin_lat"]          = sf_get_post_meta( $result->ID, 'sf_directory_lat_coord', true );
                $directory_item["pin_lng"]          = sf_get_post_meta( $result->ID, 'sf_directory_lng_coord', true );
                $directory_item["categories"]       = $category_list;
                $directory_item["location"]         = $location_list;
                $directory_itens['items'][ $count ] = $directory_item;

                $count ++;
            }
            $directory_itens['map_1st_text']   = __( "What are you looking for?", "swiftframework" );
            $directory_itens['results_text_1'] = __( "Found", "swiftframework" );
            $directory_itens['results_text_2'] = __( "results", "swiftframework" );
            $directory_itens['search_text']    = __( "Search", "swiftframework" );

            //If we get no results, then return error message
            if ( $count == 0 ) {
                $directory_itens['errormsg'] = __( "No results found, please try again.", "swiftframework" );
            }

            $directory_itens['results']    = $count;
            $directory_itens['locations']  = sf_directory_location_filter();
            $directory_itens['categories'] = sf_directory_category_filter( $category_term );

            echo json_encode( $directory_itens );
            die();

        }

        add_action( 'wp_ajax_sf_directory', 'sf_directory' );
        add_action( 'wp_ajax_nopriv_sf_directory', 'sf_directory' );
    }


    /* DIRECTORY CATEGORY FILTER
    ================================================== */
    if ( ! function_exists( 'sf_directory_category_filter' ) ) {
        function sf_directory_category_filter( $selected_category = "" ) {

            $filter_output = $tax_terms = "";
            $tax_terms     = get_terms( 'directory-category' );
            $filter_output .= '<select class="filter-wrap  directory-category-option clearfix">' . "\n";
            $filter_output .= '<option class="all" value="All">' . __( "All Categories", "swiftframework" ) . '</option>' . "\n";

            foreach ( $tax_terms as $tax_term ) {
                if ( $selected_category == $tax_term->slug ) {
                    $filter_output .= '<option value=' . $tax_term->slug . ' selected>' . $tax_term->name . '</option>' . "\n";
                } else {
                    $filter_output .= '<option value=' . $tax_term->slug . '>' . $tax_term->name . '</option>' . "\n";
                }
            }

            $filter_output .= '</select>' . "\n";

            return $filter_output;
        }
    }


    /* DIRECTORY LOCATION FILTER
    ================================================== */
    if ( ! function_exists( 'sf_directory_location_filter' ) ) {
        function sf_directory_location_filter() {

            $filter_output = $tax_terms = "";
            $tax_terms     = get_terms( 'directory-location', 'hide_empty=0' );
            $filter_output .= '<select class="filter-wrap directory-location-option clearfix ">' . "\n";
            $filter_output .= '<option class="all selected" value="">' . __( "All Locations", "swiftframework" ) . '</option>' . "\n";

            foreach ( $tax_terms as $tax_term ) {
                $filter_output .= '<option value=' . $tax_term->slug . '>' . $tax_term->name . '</option>' . "\n";
            }
            $filter_output .= '</select>' . "\n";

            return $filter_output;
        }
    }
?>
