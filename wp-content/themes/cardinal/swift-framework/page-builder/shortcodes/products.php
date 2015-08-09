<?php

    /*
    *
    *	Swift Page Builder - Products Shortcode
    *	------------------------------------------------
    *	Swift Framework
    * 	Copyright Swift Ideas 2014 - http://www.swiftideas.net
    *
    */

    class SwiftPageBuilderShortcode_spb_products_mini extends SwiftPageBuilderShortcode {

        protected function content( $atts, $content = null ) {

            $title = $asset_type = $width = $el_class = $output = $items = $el_position = '';

            extract( shortcode_atts( array(
                'title'       => '',
                'asset_type'  => 'best-sellers',
                'item_count'  => '4',
                'category'    => '',
                'el_position' => '',
                'width'       => '1/4',
                'el_class'    => ''
            ), $atts ) );


            /* SIDEBAR CONFIG
            ================================================== */
            $sidebar_config = sf_get_post_meta( get_the_ID(), 'sf_sidebar_config', true );

            $sidebars = '';
            if ( ( $sidebar_config == "left-sidebar" ) || ( $sidebar_config == "right-sidebar" ) ) {
                $sidebars = 'one-sidebar';
            } else if ( $sidebar_config == "both-sidebars" ) {
                $sidebars = 'both-sidebars';
            } else {
                $sidebars = 'no-sidebars';
            }

            /* PRODUCT ITEMS
            ================================================== */
            if ( sf_woocommerce_activated() ) {
                $items = sf_mini_product_items( $asset_type, $category, $item_count, $sidebars, $width );
            } else {
                $items = __( "Please install/activate WooCommerce.", "swift-framework-admin" );
            }

            $el_class = $this->getExtraClass( $el_class );
            $width    = spb_translateColumnWidthToSpan( $width );

            $output .= "\n\t" . '<div class="product_list_widget woocommerce spb_content_element ' . $width . $el_class . '">';
            $output .= "\n\t\t" . '<div class="spb-asset-content">';
            $output .= ( $title != '' ) ? "\n\t\t\t" . $this->spb_title( $title, '' ) : '';
            $output .= "\n\t\t" . $items;
            $output .= "\n\t\t" . '</div>';
            $output .= "\n\t" . '</div> ' . $this->endBlockComment( $width );

            $output = $this->startRow( $el_position ) . $output . $this->endRow( $el_position );

            global $sf_has_products;
            $sf_has_products = true;

            return $output;

        }
    }

    SPBMap::map( 'spb_products_mini', array(
        "name"   => __( "Products (Mini)", "swift-framework-admin" ),
        "base"   => "spb_products_mini",
        "class"  => "spb-products-mini",
        "icon"   => "spb-icon-products-mini",
        "params" => array(
            array(
                "type"        => "textfield",
                "heading"     => __( "Widget title", "swift-framework-admin" ),
                "param_name"  => "title",
                "value"       => "",
                "description" => __( "Heading text. Leave it empty if not needed.", "swift-framework-admin" )
            ),
            array(
                "type"        => "dropdown",
                "heading"     => __( "Asset type", "swift-framework-admin" ),
                "param_name"  => "asset_type",
                "value"       => array(
                    __( 'Best Sellers', "swift-framework-admin" )      => "best-sellers",
                    __( 'Latest Products', "swift-framework-admin" )   => "latest-products",
                    __( 'Top Rated', "swift-framework-admin" )         => "top-rated",
                    __( 'Sale Products', "swift-framework-admin" )     => "sale-products",
                    __( 'Recently Viewed', "swift-framework-admin" )   => "recently-viewed",
                    __( 'Featured Products', "swift-framework-admin" ) => "featured-products"
                ),
                "description" => __( "Select the order of the products you'd like to show.", "swift-framework-admin" )
            ),
            array(
                "type"        => "textfield",
                "heading"     => __( "Product category", "swift-framework-admin" ),
                "param_name"  => "category",
                "value"       => "",
                "description" => __( "Optionally, provide the category slugs for the products you want to show (comma seperated). i.e. trainer,dress,bag.", "swift-framework-admin" )
            ),
            array(
                "type"        => "textfield",
                "class"       => "",
                "heading"     => __( "Number of items", "swift-framework-admin" ),
                "param_name"  => "item_count",
                "value"       => "4",
                "description" => __( "The number of products to show.", "swift-framework-admin" )
            ),
            array(
                "type"        => "textfield",
                "heading"     => __( "Extra class name", "swift-framework-admin" ),
                "param_name"  => "el_class",
                "value"       => "",
                "description" => __( "If you wish to style this particular content element differently, then use this field to add a class name and then refer to it in your css file.", "swift-framework-admin" )
            )
        )
    ) );


    class SwiftPageBuilderShortcode_spb_products extends SwiftPageBuilderShortcode {

        protected function content( $atts, $content = null ) {

            $title = $asset_type = $carousel = $width = $sidebars = $el_class = $output = $items = $el_position = '';

            extract( shortcode_atts( array(
                'title'          => '',
                'asset_type'     => 'best-sellers',
                'carousel'       => 'no',
                'fullwidth'      => 'no',
                'gutters'        => 'yes',
                'columns'        => '4',
                'item_count'     => '8',
                'category'       => '',
                'button_enabled' => 'no',
                'el_position'    => '',
                'width'          => '1/1',
                'el_class'       => ''
            ), $atts ) );


            /* SIDEBAR CONFIG
            ================================================== */
            global $sf_sidebar_config;

            $sidebars = '';
            if ( ( $sf_sidebar_config == "left-sidebar" ) || ( $sf_sidebar_config == "right-sidebar" ) ) {
                $sidebars = 'one-sidebar';
            } else if ( $sf_sidebar_config == "both-sidebars" ) {
                $sidebars = 'both-sidebars';
            } else {
                $sidebars = 'no-sidebars';
            }


            /* PRODUCT ITEMS
            ================================================== */
            if ( sf_woocommerce_activated() ) {
                $items = sf_product_items( $asset_type, $category, $carousel, $fullwidth, $gutters, $columns, $item_count, $width );
            } else {
                $items = __( "Please install/activate WooCommerce.", "swift-framework-admin" );
            }


            /* OUTPUT
            ================================================== */
            $el_class = $this->getExtraClass( $el_class );
            $width    = spb_translateColumnWidthToSpan( $width );

            $page_button   = $title_wrap_class = "";
            $has_button    = true;
            $shop_page_url = get_permalink( woocommerce_get_page_id( 'shop' ) );
            $page_button   = '<a class="sf-button medium white sf-icon-stroke " href="' . $shop_page_url . '"><i class="ss-layergroup"></i><span class="text">' . __( "VIEW ALL PRODUCTS", "swiftframework" ) . '</span></a>';

            if ( $has_button && $button_enabled == "yes" ) {
                $title_wrap_class .= 'has-button ';
            }
            if ( $fullwidth == "yes" && $sidebars == "no-sidebars" && $width == "col-sm-12" ) {
                $title_wrap_class .= 'container ';
            }

            $output .= "\n\t" . '<div class="product_list_widget woocommerce spb_content_element ' . $width . $el_class . '">';
            $output .= "\n\t\t" . '<div class="spb-asset-content">';
            $output .= "\n\t\t" . '<div class="title-wrap clearfix ' . $title_wrap_class . '">';
            if ( $title != '' ) {
                if ( $fullwidth == "yes" ) {
                    $output .= '<h2 class="spb-heading"><span>' . $title . '</span></h2>';
                } else {
                    $output .= '<h3 class="spb-heading"><span>' . $title . '</span></h3>';
                }
            }
            if ( $carousel == "yes" && !empty($count) && count($items) > $columns ) {
                $output .= '<div class="carousel-arrows"><a href="#" class="carousel-prev"><i class="ss-navigateleft"></i></a><a href="#" class="carousel-next"><i class="ss-navigateright"></i></a></div>';
            }
            if ( $has_button && $button_enabled == "yes" ) {
                $output .= $page_button;
            }
            $output .= '</div>';

            $output .= "\n\t\t" . $items;
            $output .= "\n\t\t" . '</div>';
            $output .= "\n\t" . '</div> ' . $this->endBlockComment( $width );

            if ( $fullwidth == "yes" && $sidebars == "no-sidebars" && $width == "col-sm-12" ) {
                $output = $this->startRow( $el_position, '', true ) . $output . $this->endRow( $el_position, '', true );
            } else {
                $output = $this->startRow( $el_position ) . $output . $this->endRow( $el_position );
            }

            if ( $carousel == "yes" ) {
                global $sf_include_carousel;
                $sf_include_carousel = true;

            }
            global $sf_include_isotope, $sf_has_products;
            $sf_include_isotope = true;
            $sf_has_products    = true;

            return $output;

        }
    }

    SPBMap::map( 'spb_products', array(
        "name"   => __( "Products", "swift-framework-admin" ),
        "base"   => "spb_products",
        "class"  => "spb-products",
        "icon"   => "spb-icon-products",
        "params" => array(
            array(
                "type"        => "textfield",
                "heading"     => __( "Widget title", "swift-framework-admin" ),
                "param_name"  => "title",
                "value"       => "",
                "description" => __( "Heading text. Leave it empty if not needed.", "swift-framework-admin" )
            ),
            array(
                "type"        => "dropdown",
                "heading"     => __( "Asset type", "swift-framework-admin" ),
                "param_name"  => "asset_type",
                "value"       => array(
                    __( 'Best Sellers', "swift-framework-admin" )      => "best-sellers",
                    __( 'Latest Products', "swift-framework-admin" )   => "latest-products",
                    __( 'Top Rated', "swift-framework-admin" )         => "top-rated",
                    __( 'Sale Products', "swift-framework-admin" )     => "sale-products",
                    __( 'Recently Viewed', "swift-framework-admin" )   => "recently-viewed",
                    __( 'Featured Products', "swift-framework-admin" ) => "featured-products"
                ),
                "description" => __( "Select the order of products you'd like to show.", "swift-framework-admin" )
            ),
            array(
                "type"        => "textfield",
                "heading"     => __( "Product category", "swift-framework-admin" ),
                "param_name"  => "category",
                "value"       => "",
                "description" => __( "Optionally, provide the category slugs for the products you want to show (comma seperated). i.e. trainer,dress,bag.", "swift-framework-admin" )
            ),
            array(
                "type"        => "dropdown",
                "heading"     => __( "Carousel", "swift-framework-admin" ),
                "param_name"  => "carousel",
                "value"       => array(
                    __( 'Yes', "swift-framework-admin" ) => "yes",
                    __( 'No', "swift-framework-admin" )  => "no",
                ),
                "description" => __( "Select if you'd like the asset to be a carousel.", "swift-framework-admin" )
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
                "heading"     => __( "Column count", "swift-framework-admin" ),
                "param_name"  => "columns",
                "value"       => array( "2", "3", "4", "5", "6" ),
                "std"         => "4",
                "description" => __( "How many product columns to display.", "swift-framework-admin" )
            ),
            array(
                "type"        => "textfield",
                "class"       => "",
                "heading"     => __( "Number of items", "swift-framework-admin" ),
                "param_name"  => "item_count",
                "value"       => "8",
                "description" => __( "The number of products to show.", "swift-framework-admin" )
            ),
            array(
                "type"        => "dropdown",
                "heading"     => __( "Shop Page Button", "swift-framework-admin" ),
                "param_name"  => "button_enabled",
                "value"       => array(
                    __( 'No', "swift-framework-admin" )  => "no",
                    __( 'Yes', "swift-framework-admin" ) => "yes"
                ),
                "description" => __( "Select if you'd like to include a button in the title bar to link through to all shop items.", "swift-framework-admin" )
            ),
            array(
                "type"        => "textfield",
                "heading"     => __( "Extra class name", "swift-framework-admin" ),
                "param_name"  => "el_class",
                "value"       => "",
                "description" => __( "If you wish to style this particular content element differently, then use this field to add a class name and then refer to it in your css file.", "swift-framework-admin" )
            )
        )
    ) );