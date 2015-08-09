<?php

    /*
    *
    *	Swift Page Builder - Blog Items Function Class
    *	------------------------------------------------
    *	Swift Framework
    * 	Copyright Swift Ideas 2014 - http://www.swiftideas.net
    *
    *	sf_blog_items()
    *	sf_blog_aux()
    *
    */

    function sf_blog_classes( $blog_type, $fullwidth = "no" ) {
        global $sf_sidebar_config;

        $list_class = $item_class = '';

        if ( $blog_type == "mini" ) {
            $item_class = "col-sm-12";
        } else if ( $blog_type == "masonry" ) {
            if ( $sf_sidebar_config == "both-sidebars" ) {
                $item_class = "col-sm-3";
            } else {
                $item_class = "col-sm-4";
            }
        } else if ( $blog_type == "masonry" && $fullwith == "yes" ) {
            $item_class = "col-sm-3";
        } else {
            $item_class = "col-sm-12";
        }

        if ( $blog_type == "masonry" ) {
            $list_class .= 'masonry-items';
        } else if ( $blog_type == "bold" ) {
            $list_class .= 'bold-items';
        } else if ( $blog_type == "mini" ) {
            $list_class .= 'mini-items';
        } else if ( $blog_type == "timeline" ) {
            $list_class .= 'timeline-items';
        } else {
            $list_class .= 'standard-items';
        }

        $class_array = array(
            "list" => $list_class,
            "item" => $item_class
        );

        return $class_array;
    }

    /* BLOG ITEMS
    ================================================== */
    if ( ! function_exists( 'sf_blog_items' ) ) {

        function sf_blog_items( $blog_type, $gutters, $columns, $fullwidth, $show_title, $show_excerpt, $show_details, $excerpt_length, $content_output, $show_read_more, $item_count, $category, $exclude_categories, $pagination, $sidebar_config, $width, $offset, $posts_order, $hover_style, $social_integration = "no", $twitter_username = "", $instagram_id = "", $instagram_token = "" ) {

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
                $paged  = get_query_var( 'paged' );
                $offset = $offset + ( $item_count * ( $paged - 1 ) );
            } elseif ( get_query_var( 'page' ) ) {
                $paged  = get_query_var( 'page' );
                $offset = $offset + ( $item_count * ( $paged - 1 ) );
            } else {
                $paged = 1;
            }

            $blog_args      = array();
            $category_array = explode( ",", $category_slug );
            if ( isset( $category_array ) && $category_array[0] != "" ) {
                $blog_args = array(
                    'post_type'      => 'post',
                    'post_status'    => 'publish',
                    'paged'          => $paged,
                    'posts_per_page' => $item_count,
                    'offset'         => $offset,
                    'order'          => $posts_order,
                    'tax_query'      => array(
                        array(
                            'taxonomy' => 'category',
                            'field'    => 'slug',
                            'terms'    => $category_array
                        )
                    )

                );
            } else {
                $blog_args = array(
                    'post_type'      => 'post',
                    'post_status'    => 'publish',
                    'paged'          => $paged,
                    'posts_per_page' => $item_count,
                    'offset'         => $offset,
                    'order'          => $posts_order,
                );
            }
            $blog_items = new WP_Query( $blog_args );


            /* LIST CLASS CONFIG
            ================================================== */
            $list_class = $wrap_class = '';
            if ( $blog_type == "masonry" ) {
                $list_class .= 'masonry-items';
                if ( $gutters == "no" ) {
                    $list_class .= ' no-gutters';
                } else {
                    $list_class .= ' gutters';
                }
                // Thumb Type
                if ( $hover_style == "default" && function_exists( 'sf_get_thumb_type' ) ) {
                    $list_class .= ' ' . sf_get_thumb_type();
                } else {
                    $list_class .= ' thumbnail-' . $hover_style;
                }
            } else if ( $blog_type == "bold" ) {
                $list_class .= 'bold-items';
            } else if ( $blog_type == "mini" ) {
                $list_class .= 'mini-items';
            } else if ( $blog_type == "timeline" ) {
                $list_class .= 'timeline-items';
                if ( $sidebar_config == "no-sidebars" ) {
                    $wrap_class .= 'col-sm-8 col-sm-offset-2';
                }
            } else {
                $list_class .= 'standard-items row';
            }

            if ( $pagination == "infinite-scroll" ) {
                $list_class .= ' blog-inf-scroll';
            }


            /* BLOG ITEMS OUTPUT
            ================================================== */
            $blog_items_output .= '<div class="blog-items-wrap blog-' . $blog_type . ' ' . $wrap_class . '">';
            if ( $blog_type == "timeline" ) {
                $blog_items_output .= '<div class="timeline"></div>';
            }
            if ( ( $social_integration == "yes" && $pagination == "none" ) && ( $twitter_username != "" || $instagram_id != "" ) ) {
                $blog_items_output .= '<ul class="blog-items ' . $list_class . ' social-blog clearfix" data-blog-type="' . $blog_type . '">';
            } else {
                $blog_items_output .= '<ul class="blog-items ' . $list_class . ' clearfix" data-blog-type="' . $blog_type . '">';
            }

            while ( $blog_items->have_posts() ) : $blog_items->the_post();

                $post_format = get_post_format( $post->ID );
                if ( $post_format == "" ) {
                    $post_format = 'standard';
                }

                if ( $blog_type == "mini" || $blog_type == "standard" || $blog_type == "timeline" ) {
                    $item_class = "col-sm-12";
                } else if ( $blog_type == "masonry" && $fullwidth == "yes" ) {
                    $item_class = "col-sm-3";
                } else if ( $blog_type == "masonry" ) {
                    if ( $columns == "5" ) {
                        $item_class = "col-sm-sf-5";
                    } else if ( $columns == "4" ) {
                        $item_class = "col-sm-3";
                    } else if ( $columns == "3" ) {
                        $item_class = "col-sm-4";
                    } else if ( $columns == "2" ) {
                        $item_class = "col-sm-6";
                    } else if ( $columns == "1" ) {
                        $item_class = "col-sm-12";
                    }
                } else {
                    $item_class = $width;
                }

                $post_terms = get_the_terms( $post->ID, 'category' );
                $term_slug  = " ";

                if ( ! empty( $post_terms ) ) {
                    foreach ( $post_terms as $post_term ) {
                        $term_slug = $term_slug . $post_term->slug . ' ';
                    }
                }


                /* BLOG ITEM OUTPUT
                ================================================== */
                $blog_items_output .= '<li itemscope itemtype="http://schema.org/BlogPosting" class="blog-item ' . $item_class . ' ' . $term_slug . ' ' . implode( ' ', get_post_class() ) . '" id="' . get_the_ID() . '" data-date="' . get_the_time( 'U' ) . '">';
                $blog_items_output .= sf_get_post_item( $post->ID, $blog_type, $show_title, $show_excerpt, $show_details, $excerpt_length, $content_output, $show_read_more, $fullwidth );
                $blog_items_output .= '</li>';


            endwhile;

            wp_reset_query();
            wp_reset_postdata();

            $blog_items_output .= '</ul>';


            /* SOCIAL INTEGRATION
            ================================================== */
            if ( $social_integration == "yes" && $pagination == "none" ) {

                $tweet_count = $instagram_count = floor( $item_count / 4 );
                $item_count  = $item_count - $tweet_count - $instagram_count;

                if ( $instagram_id == "" ) {
                    $tweet_count = $tweet_count * 2;
                } else if ( $twitter_username == "" ) {
                    $instagram_count = $instagram_count * 2;
                }

                /* TWEETS
                ================================================== */
                if ( $twitter_username != "" ) {
                    if ( $fullwidth == "yes" ) {
                        $blog_items_output .= '<ul class="blog-tweets">' . sf_get_tweets( $twitter_username, $tweet_count, 'blog-fw', $item_class ) . '</ul>';
                    } else {
                        $blog_items_output .= '<ul class="blog-tweets">' . sf_get_tweets( $twitter_username, $tweet_count, 'blog', $item_class ) . '</ul>';
                    }
                }

                /* INSTAGRAMS
                ================================================== */
                if ( $instagram_id != "" && $instagram_token != "" ) {
                    $blog_items_output .= '<ul class="blog-instagrams" data-title="' . __( "Instagram", "swiftframework" ) . '" data-count="' . $instagram_count . '" data-userid="' . $instagram_id . '" data-token="' . $instagram_token . '" data-itemclass="' . $item_class . '"></ul>';
                }
            }


            /* PAGINATION OUTPUT
            ================================================== */
            if ( $pagination == "infinite-scroll" ) {

                global $sf_include_infscroll;
                $sf_include_infscroll = true;

                $blog_items_output .= '<div class="pagination-wrap hidden">';
                $blog_items_output .= pagenavi( $blog_items );
                $blog_items_output .= '</div>';

            } else if ( $pagination == "load-more" ) {

                global $sf_include_infscroll;
                $sf_include_infscroll = true;

                $blog_items_output .= '<a href="#" class="load-more-btn">' . __( 'Load More', 'swiftframework' ) . '</a>';

                $blog_items_output .= '<div class="pagination-wrap load-more hidden">';
                $blog_items_output .= pagenavi( $blog_items );
                $blog_items_output .= '</div>';

            } else if ( $pagination == "standard" ) {
                if ( $blog_type == "masonry" ) {
                    $blog_items_output .= '<div class="pagination-wrap masonry-pagination">';
                } else {
                    $blog_items_output .= '<div class="pagination-wrap">';
                }
                $blog_items_output .= pagenavi( $blog_items );
                $blog_items_output .= '</div>';
            }


            $blog_items_output .= '</div>';


            /* FUNCTION OUTPUT
            ================================================== */

            return $blog_items_output;

        }
    }


    /* BLOG FILTER
    ================================================== */
    if ( ! function_exists( 'sf_blog_filter' ) ) {
        function sf_blog_filter( $style = "basic", $parent_category = "" ) {

            $filter_output = $tax_terms = "";

            if ( $parent_category == "" || $parent_category == "All" ) {
                $tax_terms = sf_get_category_list( 'category', 1 );
            } else {
                $tax_terms = sf_get_category_list( 'category', 1, $parent_category );
            }

            $filter_output .= '<div class="filter-wrap clearfix">' . "\n";
            $filter_output .= '<ul class="post-filter-tabs filtering clearfix">' . "\n";
            $filter_output .= '<li class="all selected"><a data-filter="*" href="#"><span class="item-name">' . __( "Show all", "swiftframework" ) . '</span></a></li>' . "\n";
            foreach ( $tax_terms as $tax_term ) {
                $term = get_term_by( 'slug', $tax_term, 'category' );
                if ( $term ) {
                    $filter_output .= '<li><a href="#" title="' . $term->name . '" class="' . $term->slug . '" data-filter=".' . $term->slug . '"><span class="item-name">' . $term->name . '</span></a></li>' . "\n";
                } else {
                    $filter_output .= '<li><a href="#" title="' . $tax_term . '" class="' . $tax_term . '" data-filter=".' . $tax_term . '"><span class="item-name">' . $tax_term . '</span></a></li>' . "\n";
                }
            }
            $filter_output .= '</ul></div>' . "\n";

            return $filter_output;
        }
    }


    /* BLOG AUX
    ================================================== */
    if ( ! function_exists( 'sf_blog_aux' ) ) {
        function sf_blog_aux( $width ) {

            $blog_aux_output = "";
            global $sf_options;
            $rss_feed_url = __( $sf_options['rss_feed_url'], 'swiftframework' );


            $category_list = wp_list_categories( 'sort_column=name&title_li=&depth=1&number=10&echo=0&show_count=1' );
            $archive_list  = wp_get_archives( 'type=monthly&limit=12&echo=0' );
            $tags_list     = wp_tag_cloud( 'smallest=12&largest=12&unit=px&format=list&number=50&orderby=name&echo=0' );

            $blog_aux_output .= '<div class="blog-aux-wrap row">'; // open .blog-aux-wrap
            $blog_aux_output .= '<ul class="blog-aux-options bar-styling ' . $width . '">'; // open .blog-aux-options

            // CATEGORIES
            $blog_aux_output .= '<li><a href="#" class="blog-slideout-trigger" data-aux="categories"><i class="ss-index"></i>' . __( "Categories", "swiftframework" ) . '</a>';

            // TAGS
            $blog_aux_output .= '<li><a href="#" class="blog-slideout-trigger" data-aux="tags"><i class="ss-tag"></i>' . __( "Tags", "swiftframework" ) . '</a>';

            // SEARCH FORM
            $blog_aux_output .= '<li class="search"><form method="get" class="search-form" action="' . home_url() . '/">';
            $blog_aux_output .= '<input type="text" placeholder="' . __( "Search", "swiftframework" ) . '" name="s" />';
            $blog_aux_output .= '</form></li>';

            // ARCHIVES
            $blog_aux_output .= '<li><a href="#" class="blog-slideout-trigger" data-aux="archives"><i class="ss-storagebox"></i>' . __( "Archives", "swiftframework" ) . '</a>';

            // RSS LINK
            if ( $rss_feed_url != "" ) {
                $blog_aux_output .= '<li><a href="' . $rss_feed_url . '" class="rss-link" target="_blank"><i class="fa-rss"></i>' . __( "RSS", "swiftframework" ) . '</a>';
            }

            $blog_aux_output .= '</ul>'; // close .blog-aux-options
            $blog_aux_output .= '</div>'; // close .blog-aux-wrap

            $blog_aux_output .= '<div class="container">';
            $blog_aux_output .= '<div class="slideout-filter blog-filter-wrap row clearfix">'; // open .blog-filter-wrap
            $blog_aux_output .= '<div class="filter-slide-wrap col-sm-12">';

            if ( $category_list != '' ) {
                $blog_aux_output .= '<ul class="aux-list aux-categories row clearfix">' . $category_list . '</ul>';
            }
            if ( $tags_list != '' ) {
                $blog_aux_output .= '<ul class="aux-list aux-tags row clearfix">' . $tags_list . '</ul>';
            }
            if ( $archive_list != '' ) {
                $blog_aux_output .= '<ul class="aux-list aux-archives row clearfix">' . $archive_list . '</ul>';
            }
            $blog_aux_output .= '</div>';

            $blog_aux_output .= '</div></div>'; // close .blog-filter-wrap


            /* AUX BUTTONS OUTPUT
            ================================================== */

            return $blog_aux_output;

        }
    }
?>