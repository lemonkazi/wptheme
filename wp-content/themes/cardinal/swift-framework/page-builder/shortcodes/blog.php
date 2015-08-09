<?php

    /*
    *
    *	Swift Page Builder - Blog Shortcode
    *	------------------------------------------------
    *	Swift Framework
    * 	Copyright Swift Ideas 2014 - http://www.swiftideas.net
    *
    */

    class SwiftPageBuilderShortcode_spb_blog extends SwiftPageBuilderShortcode {

        protected function content( $atts, $content = null ) {

            $title = $width = $el_class = $output = $gutters = $fullwidth = $columns = $hover_style = $show_read_more = $offset = $posts_order = $content_output = $items = $item_figure = $el_position = '';

            extract( shortcode_atts( array(
                'title'              => '',
                "blog_type"          => "standard",
                "gutters"            => 'yes',
                "columns"            => '4',
                "fullwidth"          => "no",
                'show_title'         => 'yes',
                'show_excerpt'       => 'yes',
                "show_details"       => 'yes',
                "offset"             => '0',
                "posts_order"        => 'DESC',
                "excerpt_length"     => '20',
                'show_read_more'     => 'yes',
                "item_count"         => '5',
                "category"           => '',
                "exclude_categories" => '',
                "pagination"         => "no",
                "social_integration" => 'no',
                "twitter_username"   => '',
                "instagram_id"       => '',
                "instagram_token"    => '',
                "blog_filter"        => '',
                'hover_style'        => 'default',
                "content_output"     => 'excerpt',
                'el_position'        => '',
                'width'              => '1/1',
                'el_class'           => ''
            ), $atts ) );


            $width = spb_translateColumnWidthToSpan( $width );


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


            /* BLOG AUX
            ================================================== */
//	        if ($show_blog_aux == "yes" && $sidebars == "no-sidebars") {
//	        	$blog_aux = sf_blog_aux($width);
//	        }


            /* BLOG ITEMS
            ================================================== */
            $items = sf_blog_items( $blog_type, $gutters, $columns, $fullwidth, $show_title, $show_excerpt, $show_details, $excerpt_length, $content_output, $show_read_more, $item_count, $category, $exclude_categories, $pagination, $sidebars, $width, $offset, $posts_order, $hover_style, $social_integration, $twitter_username, $instagram_id, $instagram_token );


            /* FINAL OUTPUT
            ================================================== */
            $title_wrap_class = "";
            if ( $blog_filter == "yes" ) {
                $title_wrap_class .= 'has-filter ';
            }
            if ( $fullwidth == "yes" && $sidebars == "no-sidebars" ) {
                $title_wrap_class .= 'container ';
            }
            $el_class = $this->getExtraClass( $el_class );

            $output .= "\n\t" . '<div class="spb_blog_widget blog-wrap spb_content_element ' . $width . $el_class . '">';
            $output .= "\n\t\t" . '<div class="spb-asset-content">';
            if ( $title != '' || $blog_filter == "yes" ) {
                $output .= "\n\t\t" . '<div class="title-wrap clearfix ' . $title_wrap_class . '">';
                if ( $title != '' ) {
                    $output .= '<h2 class="spb-heading"><span>' . $title . '</span></h2>';
                }
                if ( $blog_filter == "yes" ) {
                    $output .= sf_blog_filter( '', $category );
                }
                $output .= '</div>';
            }
            $output .= "\n\t\t" . $items;
            $output .= "\n\t\t" . '</div>';
            $output .= "\n\t" . '</div> ' . $this->endBlockComment( $width );

            if ( $fullwidth == "yes" ) {
                $output = $this->startRow( $el_position, '', true ) . $output . $this->endRow( $el_position, '', true );
            } else {
                $output = $this->startRow( $el_position ) . $output . $this->endRow( $el_position );
            }

            global $sf_has_blog, $sf_include_imagesLoaded;
            $sf_include_imagesLoaded = true;
            $sf_has_blog             = true;

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
            "heading"     => __( "Blog type", "swift-framework-admin" ),
            "param_name"  => "blog_type",
            "value"       => array(
                __( 'Standard', "swift-framework-admin" ) => "standard",
                __( 'Timeline', "swift-framework-admin" ) => "timeline",
                __( 'Bold', "swift-framework-admin" )     => "bold",
                __( 'Mini', "swift-framework-admin" )     => "mini",
                __( 'Masonry', "swift-framework-admin" )  => "masonry",
            ),
            "description" => __( "Select the display type for the blog.", "swift-framework-admin" )
        ),
        array(
            "type"        => "dropdown",
            "heading"     => __( "Masonry gutters", "swift-framework-admin" ),
            "param_name"  => "gutters",
            "value"       => array(
                __( 'Yes', "swift-framework-admin" ) => "yes",
                __( 'No', "swift-framework-admin" )  => "no"
            ),
            "description" => __( "Select if you'd like spacing between the items, or not (Masonry type only).", "swift-framework-admin" )
        ),
        array(
            "type"        => "dropdown",
            "heading"     => __( "Masonry Columns", "swift-framework-admin" ),
            "param_name"  => "columns",
            "value"       => array( "5", "4", "3", "2", "1" ),
            "description" => __( "How many blog masonry columns to display. NOTE: Only for the masonry blog type, and not when fullwidth mode is selected, as this is adaptive.", "swift-framework-admin" )
        ),
        array(
            "type"        => "dropdown",
            "heading"     => __( "Full Width", "swift-framework-admin" ),
            "param_name"  => "fullwidth",
            "value"       => array(
                __( 'No', "swift-framework-admin" )  => "no",
                __( 'Yes', "swift-framework-admin" ) => "yes"
            ),
            "description" => __( "Select if you'd like the asset to be full width (edge to edge). NOTE: only possible on pages without sidebars.", "swift-framework-admin" )
        ),
        array(
            "type"        => "textfield",
            "class"       => "",
            "heading"     => __( "Number of items", "swift-framework-admin" ),
            "param_name"  => "item_count",
            "value"       => "5",
            "description" => __( "The number of blog items to show per page.", "swift-framework-admin" )
        ),
        array(
            "type"        => "select-multiple",
            "heading"     => __( "Blog category", "swift-framework-admin" ),
            "param_name"  => "category",
            "value"       => sf_get_category_list( 'category' ),
            "description" => __( "Choose the category for the blog items.", "swift-framework-admin" )
        ),
        array(
            "type"        => "textfield",
            "heading"     => __( "Posts offset", "swift-framework-admin" ),
            "param_name"  => "offset",
            "value"       => "0",
            "description" => __( "The offset for the start of the posts that are displayed, e.g. enter 5 here to start from the 5th post.", "swift-framework-admin" )
        ),
        array(
            "type"        => "dropdown",
            "heading"     => __( "Posts order", "swift-framework-admin" ),
            "param_name"  => "posts_order",
            "value"       => array(
                __( "Descending", "swift-framework-admin" ) => "DESC",
                __( "Ascending", "swift-framework-admin" )  => "ASC"
            ),
            "description" => __( "The order of the posts.", "swift-framework-admin" )
        ),
        array(
            "type"        => "dropdown",
            "heading"     => __( "Show title text", "swift-framework-admin" ),
            "param_name"  => "show_title",
            "value"       => array(
                __( "Yes", "swift-framework-admin" ) => "yes",
                __( "No", "swift-framework-admin" )  => "no"
            ),
            "description" => __( "Show the item title text.", "swift-framework-admin" )
        ),
        array(
            "type"        => "dropdown",
            "heading"     => __( "Show item excerpt", "swift-framework-admin" ),
            "param_name"  => "show_excerpt",
            "value"       => array(
                __( "No", "swift-framework-admin" )  => "no",
                __( "Yes", "swift-framework-admin" ) => "yes"
            ),
            "description" => __( "Show the item excerpt text. NOTE: Not used in the Bold blog type.", "swift-framework-admin" )
        ),
        array(
            "type"        => "dropdown",
            "heading"     => __( "Show item details", "swift-framework-admin" ),
            "param_name"  => "show_details",
            "value"       => array(
                __( "No", "swift-framework-admin" )  => "no",
                __( "Yes", "swift-framework-admin" ) => "yes"
            ),
            "description" => __( "Show the item details.", "swift-framework-admin" )
        ),
        array(
            "type"        => "textfield",
            "heading"     => __( "Excerpt Length", "swift-framework-admin" ),
            "param_name"  => "excerpt_length",
            "value"       => "20",
            "description" => __( "The length of the excerpt for the posts. NOTE: Not used in the Bold blog type.", "swift-framework-admin" )
        ),
        array(
            "type"        => "dropdown",
            "heading"     => __( "Content Output", "swift-framework-admin" ),
            "param_name"  => "content_output",
            "value"       => array(
                __( "Excerpt", "swift-framework-admin" )      => "excerpt",
                __( "Full Content", "swift-framework-admin" ) => "full_content"
            ),
            "description" => __( "Choose whether to display the excerpt or the full content for the post. Full content is not available for the masonry or bold view types.", "swift-framework-admin" )
        ),
        array(
            "type"        => "dropdown",
            "heading"     => __( "Show read more link", "swift-framework-admin" ),
            "param_name"  => "show_read_more",
            "value"       => array(
                __( "Yes", "swift-framework-admin" ) => "yes",
                __( "No", "swift-framework-admin" )  => "no"
            ),
            "description" => __( "Show a read more link below the excerpt. NOTE: Not used in Bold or Masonry types.", "swift-framework-admin" )
        ),
        array(
            "type"        => "dropdown",
            "heading"     => __( "Social Integration", "swift-framework-admin" ),
            "param_name"  => "social_integration",
            "value"       => array(
                __( "No", "swift-framework-admin" )  => "no",
                __( "Yes", "swift-framework-admin" ) => "yes"
            ),
            "description" => __( "Enable social integration within the blog posts (only on Masonry blog types). NOTE: This will only integrate Twitter/Instagram posts to the first page of your blog, and won't be included in any further pages, or content loaded via infinite scroll or AJAX. It therefore works best when you show a high number of blog posts on a single page, and pagination MUST be set to none.", "swift-framework-admin" )
        ),
        array(
            "type"        => "textfield",
            "heading"     => __( "Twitter Username", "swift-framework-admin" ),
            "param_name"  => "twitter_username",
            "value"       => "",
            "description" => __( "Enter your twitter username here to include tweets in the blog grid. Ensure you have the Twitter oAuth plugin installed and your details added.", "swift-framework-admin" )
        ),
        array(
            "type"        => "textfield",
            "heading"     => __( "Instagram ID", "swift-framework-admin" ),
            "param_name"  => "instagram_id",
            "value"       => "",
            "description" => __( "Enter your Instagram ID here to include your instagrams in the blog grid. You can find your instagram ID here - <a href='http://jelled.com/instagram/lookup-user-id' target='_blank'>http://jelled.com/instagram/lookup-user-id</a> You will also need to enter your token below.", "swift-framework-admin" )
        ),
        array(
            "type"        => "textfield",
            "heading"     => __( "Instagram Token", "swift-framework-admin" ),
            "param_name"  => "instagram_token",
            "value"       => "",
            "description" => __( "Enter your Instagram Token here to include your instagrams in the blog grid. You can generate your instagram access token here - <a href='http://www.pinceladasdaweb.com.br/instagram/access-token/' target='_blank'>http://www.pinceladasdaweb.com.br/instagram/access-token/</a>. NOTE: This is REQUIRED.", "swift-framework-admin" )
        ),
        array(
            "type"        => "dropdown",
            "heading"     => __( "Filter", "swift-framework-admin" ),
            "param_name"  => "blog_filter",
            "value"       => array(
                __( 'Yes', "swift-framework-admin" ) => "yes",
                __( 'No', "swift-framework-admin" )  => "no"
            ),
            "description" => __( "Show the blog category filter above the items.", "swift-framework-admin" )
        ),
        array(
            "type"        => "dropdown",
            "heading"     => __( "Pagination", "swift-framework-admin" ),
            "param_name"  => "pagination",
            "value"       => array(
                __( "Infinite scroll", "swift-framework-admin" )  => "infinite-scroll",
                __( "Load more (AJAX)", "swift-framework-admin" ) => "load-more",
                __( "Standard", "swift-framework-admin" )         => "standard",
                __( "None", "swift-framework-admin" )             => "none"
            ),
            "description" => __( "Show pagination.", "swift-framework-admin" )
        ),
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
    SPBMap::map( 'spb_blog', array(
        "name"   => __( "Blog", "swift-framework-admin" ),
        "base"   => "spb_blog",
        "class"  => "spb_blog",
        "icon"   => "spb-icon-blog",
        "params" => $params
    ) );
