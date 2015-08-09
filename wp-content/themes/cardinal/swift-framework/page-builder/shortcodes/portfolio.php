<?php

    /*
    *
    *	Swift Page Builder - Portfolio Shortcode
    *	------------------------------------------------
    *	Swift Framework
    * 	Copyright Swift Ideas 2014 - http://www.swiftideas.net
    *
    */

    class SwiftPageBuilderShortcode_spb_portfolio extends SwiftPageBuilderShortcode {

        protected function content( $atts, $content = null ) {

            $title = $width = $el_class = $exclude_categories = $multi_size_ratio = $hover_style = $output = $tax_terms = $items = $el_position = '';

            extract( shortcode_atts( array(
                'title'              => '',
                'display_type'       => 'standard',
                'multi_size_ratio'   => '1/1',
                'fullwidth'          => 'no',
                'gutters'            => 'yes',
                'columns'            => '4',
                'show_title'         => 'yes',
                'show_subtitle'      => 'yes',
                'show_excerpt'       => 'no',
                'hover_show_excerpt' => 'no',
                "excerpt_length"     => '20',
                'item_count'         => '-1',
                'category'           => '',
                'order'              => 'standard',
                'portfolio_filter'   => 'yes',
                'pagination'         => 'no',
                'button_enabled'     => 'no',
                'hover_style'        => 'default',
                'el_position'        => '',
                'width'              => '1/1',
                'el_class'           => ''
            ), $atts ) );


            /* SIDEBAR CONFIG
            ================================================== */
            global $sf_sidebar_config, $sf_options;

            $sidebars = '';
            if ( ( $sf_sidebar_config == "left-sidebar" ) || ( $sf_sidebar_config == "right-sidebar" ) ) {
                $sidebars = 'one-sidebar';
            } else if ( $sf_sidebar_config == "both-sidebars" ) {
                $sidebars = 'both-sidebars';
            } else {
                $sidebars = 'no-sidebars';
            }


            /* PORTFOLIO ITEMS
            ================================================== */
            $items = sf_portfolio_items( $display_type, $multi_size_ratio, $fullwidth, $gutters, $columns, $show_title, $show_subtitle, $show_excerpt, $hover_show_excerpt, $excerpt_length, $item_count, $category, $exclude_categories, $pagination, $sidebars, $order, $hover_style );


            /* PAGE BUILDER OUTPUT
            ================================================== */
            $width    = spb_translateColumnWidthToSpan( $width );
            $el_class = $this->getExtraClass( $el_class );

            $has_button     = false;
            $page_button    = $title_wrap_class = "";
            $portfolio_page = __( $sf_options['portfolio_page'], 'swiftframework' );
            if ( $portfolio_page != "" ) {
                $has_button  = true;
                $page_button = '<a class="sf-button medium white sf-icon-stroke " href="' . get_permalink( $portfolio_page ) . '"><i class="ss-layergroup"></i><span class="text">' . __( "VIEW ALL PROJECTS", "swiftframework" ) . '</span></a>';
            }

            if ( $portfolio_filter == "yes" ) {
                $title_wrap_class .= 'has-filter ';
            } else if ( $has_button && $button_enabled == "yes" ) {
                $title_wrap_class .= 'has-button ';
            }
            if ( $fullwidth == "yes" && $sidebars == "no-sidebars" ) {
                $title_wrap_class .= 'container ';
            }

            $output .= "\n\t" . '<div class="spb_portfolio_widget portfolio-wrap spb_content_element ' . $width . $el_class . '">';
            $output .= "\n\t\t" . '<div class="spb-asset-content">';
            if ( $title != '' || $portfolio_filter == "yes" ) {
                $output .= "\n\t\t" . '<div class="title-wrap clearfix ' . $title_wrap_class . '">';
                if ( $title != '' ) {
                    $output .= '<h2 class="spb-heading"><span>' . $title . '</span></h2>';
                }
                if ( $portfolio_filter == "yes" ) {
                    $output .= sf_portfolio_filter( '', $category );
                } else if ( $has_button && $button_enabled == "yes" ) {
                    $output .= $page_button;
                }
                $output .= '</div>';
            }
            $output .= "\n\t\t" . $items;
            $output .= "\n\t\t" . '</div>';
            $output .= "\n\t" . '</div> ' . $this->endBlockComment( $width );

            if ( $fullwidth == "yes" && $sidebars == "no-sidebars" ) {
                $output = $this->startRow( $el_position, '', true ) . $output . $this->endRow( $el_position, '', true );
            } else {
                $output = $this->startRow( $el_position ) . $output . $this->endRow( $el_position );
            }

            global $sf_include_isotope, $sf_has_portfolio;
            $sf_include_isotope = true;
            $sf_has_portfolio   = true;

            return $output;

        }
    }

    /* PARAMS
    ================================================== */
    $params = array(
        array(
            "type"        => "textfield",
            "heading"     => __( "Widget title", "swift-framework-admin" ),
            "param_name"  => "title",
            "value"       => "",
            "description" => __( "Heading text. Leave it empty if not needed.", "swift-framework-admin" )
        ),
        array(
            "type"        => "dropdown",
            "heading"     => __( "Display type", "swift-framework-admin" ),
            "param_name"  => "display_type",
            "value"       => array(
                __( 'Standard', "swift-framework-admin" )           => "standard",
                __( 'Gallery', "swift-framework-admin" )            => "gallery",
                __( 'Masonry', "swift-framework-admin" )            => "masonry",
                __( 'Masonry Gallery', "swift-framework-admin" )    => "masonry-gallery",
                __( 'Multi Size Masonry', "swift-framework-admin" ) => "multi-size-masonry"
            ),
            "description" => __( "Select the type of portfolio you'd like to show.", "swift-framework-admin" )
        ),
        array(
            "type"        => "dropdown",
            "heading"     => __( "Multi Size Masonry Ratio", "swift-framework-admin" ),
            "param_name"  => "multi_size_ratio",
            "value"       => array( "1/1", "4/3" ),
            "description" => __( "If you selected Multi Size Masonry above, you can then choose whether to display 4/3, or 1/1 ratio thumbnails.", "swift-framework-admin" )
        ),
        array(
            "type"        => "dropdown",
            "heading"     => __( "Full Width", "swift-framework-admin" ),
            "param_name"  => "fullwidth",
            "value"       => array(
                __( 'Yes', "swift-framework-admin" ) => "yes",
                __( 'No', "swift-framework-admin" )  => "no"
            ),
            "description" => __( "Select if you'd like the asset to be full width (edge to edge). NOTE: only possible on pages without sidebars.", "swift-framework-admin" )
        ),
        array(
            "type"        => "dropdown",
            "heading"     => __( "Gutters", "swift-framework-admin" ),
            "param_name"  => "gutters",
            "value"       => array(
                __( 'Yes', "swift-framework-admin" ) => "yes",
                __( 'No', "swift-framework-admin" )  => "no"
            ),
            "description" => __( "Select if you'd like spacing between the items, or not.", "swift-framework-admin" )
        ),
        array(
            "type"        => "dropdown",
            "heading"     => __( "Column count", "swift-framework-admin" ),
            "param_name"  => "columns",
            "value"       => array( "5", "4", "3", "2", "1" ),
            "description" => __( "How many portfolio columns to display. NOTE: Only for display types other than 'Multi Size Masonry'", "swift-framework-admin" )
        ),
        array(
            "type"        => "dropdown",
            "heading"     => __( "Show title text", "swift-framework-admin" ),
            "param_name"  => "show_title",
            "value"       => array(
                __( 'Yes', "swift-framework-admin" ) => "yes",
                __( 'No', "swift-framework-admin" )  => "no"
            ),
            "description" => __( "Show the item title text. (Standard/Masonry only)", "swift-framework-admin" )
        ),
        array(
            "type"        => "dropdown",
            "heading"     => __( "Show subtitle text", "swift-framework-admin" ),
            "param_name"  => "show_subtitle",
            "value"       => array(
                __( 'Yes', "swift-framework-admin" ) => "yes",
                __( 'No', "swift-framework-admin" )  => "no"
            ),
            "description" => __( "Show the item subtitle text. (Standard/Masonry only)", "swift-framework-admin" )
        ),
        array(
            "type"        => "dropdown",
            "heading"     => __( "Show item excerpt", "swift-framework-admin" ),
            "param_name"  => "show_excerpt",
            "value"       => array(
                __( 'No', "swift-framework-admin" )  => "no",
                __( 'Yes', "swift-framework-admin" ) => "yes"
            ),
            "description" => __( "Show the item excerpt text. (Standard/Masonry only)", "swift-framework-admin" )
        ),
        array(
            "type"        => "dropdown",
            "heading"     => __( "Excerpt Hover", "swift-framework-admin" ),
            "param_name"  => "hover_show_excerpt",
            "value"       => array(
                __( 'No', "swift-framework-admin" )  => "no",
                __( 'Yes', "swift-framework-admin" ) => "yes"
            ),
            "description" => __( "Show the item excerpt on hover, instead of the arrow button. (Gallery/Masonry Gallery only)", "swift-framework-admin" )
        ),
        array(
            "type"        => "textfield",
            "heading"     => __( "Excerpt Length", "swift-framework-admin" ),
            "param_name"  => "excerpt_length",
            "value"       => "20",
            "description" => __( "The length of the excerpt for the posts.", "swift-framework-admin" )
        ),
        array(
            "type"        => "textfield",
            "class"       => "",
            "heading"     => __( "Number of items", "swift-framework-admin" ),
            "param_name"  => "item_count",
            "value"       => "12",
            "description" => __( "The number of portfolio items to show per page. Leave blank to show ALL portfolio items.", "swift-framework-admin" )
        ),
        array(
            "type"        => "select-multiple",
            "heading"     => __( "Portfolio category", "swift-framework-admin" ),
            "param_name"  => "category",
            "value"       => sf_get_category_list( 'portfolio-category' ),
            "description" => __( "Choose the category from which you'd like to show the portfolio items.", "swift-framework-admin" )
        ),
        array(
            "type"        => "dropdown",
            "heading"     => __( "Portfolio Order", "swift-framework-admin" ),
            "param_name"  => "order",
            "value"       => array(
                __( 'Date (Descending)', "swift-framework-admin" )  => "standard",
                __( 'Date (Ascending)', "swift-framework-admin" )   => "date-asc",
                __( 'Title (Descending)', "swift-framework-admin" ) => "title-desc",
                __( 'Title (Ascending)', "swift-framework-admin" )  => "title-asc"
            ),
            "description" => __( "Set the order in which the items are shown.", "swift-framework-admin" )
        ),
        array(
            "type"        => "dropdown",
            "heading"     => __( "Filter", "swift-framework-admin" ),
            "param_name"  => "portfolio_filter",
            "value"       => array(
                __( 'Yes', "swift-framework-admin" ) => "yes",
                __( 'No', "swift-framework-admin" )  => "no"
            ),
            "description" => __( "Show the portfolio category filter above the items.", "swift-framework-admin" )
        ),
        array(
            "type"        => "dropdown",
            "heading"     => __( "Pagination", "swift-framework-admin" ),
            "param_name"  => "pagination",
            "value"       => array(
                __( 'Yes', "swift-framework-admin" ) => "yes",
                __( 'No', "swift-framework-admin" )  => "no"
            ),
            "description" => __( "Show portfolio pagination.", "swift-framework-admin" )
        ),
        array(
            "type"        => "dropdown",
            "heading"     => __( "Portfolio Page Button", "swift-framework-admin" ),
            "param_name"  => "button_enabled",
            "value"       => array(
                __( 'No', "swift-framework-admin" )  => "no",
                __( 'Yes', "swift-framework-admin" ) => "yes"
            ),
            "description" => __( "Select if you'd like to include a button in the title bar to link through to all portfolio items. The page is set in Theme Options > Custom Post Type Options. NOTE: This will not show if you have the Filter set to 'yes', or if you are using the asset as a carousel.", "swift-framework-admin" )
        )
    );

    if ( sf_theme_opts_name() == "sf_joyn_options" ) {
        $params[] = array(
            "type"        => "dropdown",
            "heading"     => __( "Thumbnail Hover Style", "swift-framework-admin" ),
            "param_name"  => "hover_style",
            "value"       => array(
                __( 'Default', "swift-framework-admin" )     => "default",
                __( 'Standard', "swift-framework-admin" )    => "gallery-standard",
                __( 'Gallery Alt', "swift-framework-admin" ) => "gallery-alt-one",
                //__('Gallery Alt Two', "swift-framework-admin") => "gallery-alt-two",
            ),
            "description" => __( "Choose the thumbnail hover style for the asset. If set to 'Default', then this uses the thumbnail type set in the theme options.", "swift-framework-admin" )
        );
    }

    $params[] = array(
        "type"        => "textfield",
        "heading"     => __( "Extra class name", "swift-framework-admin" ),
        "param_name"  => "el_class",
        "value"       => "",
        "description" => __( "If you wish to style this particular content element differently, then use this field to add a class name and then refer to it in your css file.", "swift-framework-admin" )
    );


    /* SHORTCODE MAP
    ================================================== */
    SPBMap::map( 'spb_portfolio', array(
        "name"   => __( "Portfolio", "swift-framework-admin" ),
        "base"   => "spb_portfolio",
        "class"  => "spb_portfolio",
        "icon"   => "spb-icon-portfolio",
        "params" => $params
    ) );
