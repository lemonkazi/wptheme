<?php
    return;

    /*
    *
    *	Swift Page Builder - Blog Grid OldShortcode
    *	------------------------------------------------
    *	Swift Framework
    * 	Copyright Swift Ideas 2014 - http://www.swiftideas.net
    *
    */

    class SwiftPageBuilderShortcode_spb_blog_grid extends SwiftPageBuilderShortcode {

        protected function content( $atts, $content = null ) {

            $title = $width = $el_class = $output = $show_blog_aux = $exclude_categories = $blog_aux = $show_read_more = $offset = $posts_order = $content_output = $items = $item_figure = $el_position = '';

            extract( shortcode_atts( array(
                'title'            => '',
                'fullwidth'        => 'yes',
                "item_count"       => '5',
                "instagram_id"     => '',
                "instagram_token"  => '',
                "twitter_username" => '',
                "category"         => '',
                'el_position'      => '',
                'width'            => '1/1',
                'el_class'         => ''
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

            $blog_items_output = "";

            global $sf_options, $sf_sidebar_config;


            /* CATEGORY SLUG MODIFICATION
            ================================================== */
            if ( $category == "All" ) {
                $category = "all";
            }
            if ( $category == "all" ) {
                $category = '';
            }
            $category_slug = str_replace( '_', '-', $category );


            /* BLOG QUERY SETUP
            ================================================== */
            global $post, $wp_query;

            if ( get_query_var( 'paged' ) ) {
                $paged = get_query_var( 'paged' );
            } elseif ( get_query_var( 'page' ) ) {
                $paged = get_query_var( 'page' );
            } else {
                $paged = 1;
            }

            $tweet_count = $instagram_count = floor( $item_count / 4 );

            if ( $twitter_username == "" ) {
                $tweet_count = 0;
            }
            if ( $instagram_id == "" || $instagram_token == "" ) {
                $instagram_count = 0;
            }

            $item_count = $item_count - $tweet_count - $instagram_count;

            $blog_args = array(
                'post_type'      => 'post',
                'post_status'    => 'publish',
                'paged'          => $paged,
                'category_name'  => $category_slug,
                'posts_per_page' => $item_count,
                'cat'            => '"' . $exclude_categories . '"',
            );

            $blog_grid_items = new WP_Query( $blog_args );
            $count           = 0;

            /* BLOG ITEMS OUTPUT
            ================================================== */
            $blog_items_output .= '<div class="blog-items blog-grid-items">';
            $blog_items_output .= '<ul class="grid-items row clearfix">';

            while ( $blog_grid_items->have_posts() ) : $blog_grid_items->the_post();

                $grid_item_content = "";
                $post_format       = get_post_format( $post->ID );
                if ( $post_format == "" ) {
                    $post_format = 'standard';
                }
                $post_title     = get_the_title();
                $post_date      = get_the_date();
                $post_date_str  = strtotime( $post_date );
                $post_author    = get_the_author();
                $post_permalink = get_permalink();
                $custom_excerpt = sf_get_post_meta( $post->ID, 'sf_custom_excerpt', true );
                $post_excerpt   = strip_shortcodes( get_the_excerpt() );

                $media_image = rwmb_meta( 'sf_thumbnail_image', 'type=image&size=full' );

                $image_id = $media_image_url = "";
                $is_last  = false;

                foreach ( $media_image as $detail_image ) {
                    $image_id        = $detail_image['ID'];
                    $media_image_url = $detail_image['url'];
                    break;
                }
                if ( ! $media_image ) {
                    $media_image     = get_post_thumbnail_id();
                    $image_id        = $media_image;
                    $media_image_url = wp_get_attachment_url( $media_image, 'full' );
                }
                $detail_image = sf_aq_resize( $media_image_url, 500, 500, true, false );
                $image_alt    = sf_get_post_meta( $image_id, '_wp_attachment_image_alt', true );


                if ( $count > $item_count - 1 ) {
                    break;
                }

                $post_format = get_post_format( $post->ID );
                if ( $post_format == "" ) {
                    $post_format = 'standard';
                }

                if ( $count % 5 ) {
                    $is_last = true;
                }

                if ( $detail_image && ! $is_last ) {
                    $item_class = "col-sm-sf-25";
                    $count      = $count + 2;
                } else {
                    $item_class = "col-sm-sf-5";
                    $count ++;
                }


                /* BLOG ITEM OUTPUT
                ================================================== */
                $blog_items_output .= '<li itemscope itemtype="http://schema.org/BlogPosting" class="blog-item ' . $item_class . ' format-' . $post_format . ' ' . implode( ' ', get_post_class() ) . '" data-date="' . $post_date_str . '">';

                $grid_item_content .= '<h6>' . __( "Article", "swiftframework" ) . '</h6>';
                if ( $post_format == "quote" ) {
                    $grid_item_content .= '<div class="quote-excerpt" itemprop="name headline">' . $post_excerpt . '</div>';
                } else {
                    $grid_item_content .= '<h3>' . $post_title . '</h3>';
                }
                $grid_item_content .= '<data class="date" data-date="' . $post_date_str . '">' . $post_date . '</data>';
                $grid_item_content .= '<div class="author">' . sprintf( __( '<span>By</span> <span rel="author" itemprop="author">%1$s</span>', 'swiftframework' ), $post_author ) . '</div>';
                $grid_item_content .= '<div class="post-icon" href="' . $post_permalink . '"><i class="ss-file"></i></div>' . "\n";

                $blog_items_output .= '<a class="grid-link" href="' . $post_permalink . '"></a>';

                if ( $detail_image && ( $count % 2 == 0 ) && ! $is_last ) {
                    // GRID LEFT
                    $blog_items_output .= '<div class="grid-left">';
                    $blog_items_output .= $grid_item_content;
                    $blog_items_output .= '</div>';

                    // GRID RIGHT
                    $blog_items_output .= '<div class="grid-image"><img itemprop="image" src="' . $detail_image[0] . '" width="' . $detail_image[1] . '" height="' . $detail_image[2] . '" alt="' . $image_alt . '" /></div>';

                } else if ( $detail_image && ! $is_last ) {

                    // GRID RIGHT
                    $blog_items_output .= '<div class="grid-right">';
                    $blog_items_output .= $grid_item_content;
                    $blog_items_output .= '</div>';

                    // GRID IMAGE
                    $blog_items_output .= '<div class="grid-image"><img itemprop="image" src="' . $detail_image[0] . '" width="' . $detail_image[1] . '" height="' . $detail_image[2] . '" alt="' . $image_alt . '" /></div>';

                } else {
                    $blog_items_output .= '<div class="grid-no-image">';
                    $blog_items_output .= '<h6>' . __( "Article", "swiftframework" ) . '</h6>';
                    if ( $post_format == "quote" ) {
                        $blog_items_output .= '<div class="quote-excerpt" itemprop="name headline">' . $post_excerpt . '</div>';
                    } else {
                        $blog_items_output .= '<h3>' . $post_title . '</h3>';
                    }
                    $blog_items_output .= '<data class="date" data-date="' . $post_date_str . '">' . $post_date . '</data>';
                    $blog_items_output .= '<div class="author">' . sprintf( __( '<span>By</span> <span rel="author" itemprop="author">%1$s</span>', 'swiftframework' ), $post_author ) . '</div>';
                    $blog_items_output .= '<div class="post-icon"><i class="ss-file"></i></div>' . "\n";
                    $blog_items_output .= '</div>';
                }

                $blog_items_output .= '</li>';


            endwhile;

            wp_reset_query();
            wp_reset_postdata();

            $blog_items_output .= '</ul>';
            $blog_items_output .= '</div>';

            /* TWEETS
            ================================================== */
            if ( $twitter_username != "" ) {
                $blog_items_output .= '<ul class="blog-tweets">' . sf_get_tweets( $twitter_username, $tweet_count, 'blog-grid' ) . '</ul>';
            }


            /* INSTAGRAMS
            ================================================== */
            if ( $instagram_id != "" && $instagram_token != "" ) {
                $blog_items_output .= '<ul class="blog-instagrams" data-title="' . __( "Instagram", "swiftframework" ) . '" data-count="' . $instagram_count . '" data-userid="' . $instagram_id . '" data-token="' . $instagram_token . '" data-itemclass="col-sm-sf-5"></ul>';
            }


            /* FINAL OUTPUT
            ================================================== */
            if ( $fullwidth == "yes" ) {
                $fullwidth = true;
            } else {
                $fullwidth = false;
            }
            $el_class = $this->getExtraClass( $el_class );

            $output .= "\n\t" . '<div class="spb_blog_grid_widget blog-wrap spb_content_element ' . $width . $el_class . '">';
            $output .= "\n\t\t" . '<div class="spb-asset-content">';
            $output .= ( $title != '' ) ? "\n\t\t\t" . $this->$this->spb_title( $widget_title, '', $fullwidth ) : '';
            $output .= "\n\t\t" . $blog_items_output;
            $output .= "\n\t\t" . '</div>';
            $output .= "\n\t" . '</div> ' . $this->endBlockComment( $width );

            if ( $fullwidth ) {
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

    SPBMap::map( 'spb_blog_grid', array(
        "name"   => __( "Blog Social Grid", "swift-framework-admin" ),
        "base"   => "spb_blog_grid",
        "class"  => "spb_blog_grid",
        "icon"   => "spb-icon-blog-grid",
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
                "class"       => "",
                "heading"     => __( "Number of items", "swift-framework-admin" ),
                "param_name"  => "item_count",
                "value"       => array( "5", "10", "15", "20" ),
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
                "heading"     => __( "Full Width", "swift-framework-admin" ),
                "param_name"  => "fullwidth",
                "value"       => array(
                    __( 'Yes', "swift-framework-admin" ) => "yes",
                    __( 'No', "swift-framework-admin" )  => "no"
                ),
                "description" => __( "Select if you'd like the asset to be full width (edge to edge). NOTE: only possible on pages without sidebars.", "swift-framework-admin" )
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

?>