<?php

    /*
    *
    *	Page Heading
    *	------------------------------------------------
    *	Swift Framework v3.0
    * 	Copyright Swift Ideas 2014 - http://www.swiftideas.net
    *
    *	sf_page_heading()
    *
    */


    /* PAGE HEADING
    ================================================== */
    if ( ! function_exists( 'sf_page_heading' ) ) {
        function sf_page_heading() {

            global $post, $sf_options;

            $shop_page  = false;
            $page_title = $page_subtitle = $page_title_style = $fancy_title_image_url = $article_heading_bg = $article_heading_text = $page_heading_el_class = "";

            $show_page_title    = apply_filters( 'sf_page_heading_ns_pagetitle', 1 );
            $remove_breadcrumbs = apply_filters( 'sf_page_heading_ns_removebreadcrumbs', 0 );
            $breadcrumb_in_heading = 0;
            if ( isset( $sf_options['breadcrumb_in_heading'] ) ) {
            	$breadcrumb_in_heading = $sf_options['breadcrumb_in_heading'];
            }
            $page_title_height  = 300;
            $page_title_style   = "standard";

            if ( ( function_exists( 'is_shop' ) && is_shop() ) || ( function_exists( 'is_product_category' ) && is_product_category() ) ) {
                $shop_page = true;
            }

            // Defaults
            $default_show_page_heading = $sf_options['default_show_page_heading'];
            $pagination_style          = "standard";
            if ( isset( $sf_options['pagination_style'] ) ) {
                $pagination_style = $sf_options['pagination_style'];
            }

            // Post meta
            if ( $post && is_singular() ) {
                $show_page_title       = sf_get_post_meta( $post->ID, 'sf_page_title', true );
                $remove_breadcrumbs    = sf_get_post_meta( $post->ID, 'sf_no_breadcrumbs', true );
                $page_title_style      = sf_get_post_meta( $post->ID, 'sf_page_title_style', true );
                $page_title            = sf_get_post_meta( $post->ID, 'sf_page_title_one', true );
                $page_subtitle         = sf_get_post_meta( $post->ID, 'sf_page_subtitle', true );
                $fancy_title_image     = rwmb_meta( 'sf_page_title_image', 'type=image&size=full' );
                $page_title_text_style = sf_get_post_meta( $post->ID, 'sf_page_title_text_style', true );
                $page_title_text_align = sf_get_post_meta( $post->ID, 'sf_page_title_text_align', true );
                $page_title_height     = sf_get_post_meta( $post->ID, 'sf_page_title_height', true );
                $page_heading_bg       = sf_get_post_meta( $post->ID, 'sf_page_title_bg_color', true );
                $page_heading_text     = sf_get_post_meta( $post->ID, 'sf_page_title_text_color', true );

                if ( $page_heading_bg != "" ) {
                    $article_heading_bg = 'style="background-color:' . $page_heading_bg . ';border-color:' . $page_heading_bg . ';"';
                }
                if ( $page_heading_text != "" ) {
                    $article_heading_text = 'style="color:' . $page_heading_text . ';"';
                }
            }

            if ( is_singular( 'post' ) ) {
                $fw_media_display = sf_get_post_meta( $post->ID, 'sf_fw_media_display', true );
                if ( $fw_media_display == "fw-media-title" ) {
                    return;
                }
            }

            // Portfolio category navigation
            $enable_category_navigation = $sf_options['enable_category_navigation'];

            // Woo setup
            if ( $shop_page ) {
                $show_page_title       = $sf_options['woo_show_page_heading'];
                $page_title_style      = $sf_options['woo_page_heading_style'];
                $fancy_title_image     = $sf_options['woo_page_heading_image'];
                $page_title_text_style = $sf_options['woo_page_heading_text_style'];
                $page_title_text_align = "left";

                if ( isset( $fancy_title_image ) ) {
                    $fancy_title_image_url = $fancy_title_image['url'];
                }
            }
            if ( function_exists( 'is_product' ) && is_product() ) {
                $product_layout = sf_get_post_meta( $post->ID, 'sf_product_layout', true );
                if ( $product_layout == "fw-split" ) {
                    return;
                }
            }

            // Page Title
            if ( $show_page_title == "" ) {
                $show_page_title = $default_show_page_heading;
            }
            if ( $page_title == "" ) {
                $page_title = get_the_title();
            }
            if ( $page_title_height == "" ) {
                $page_title_height = apply_filters( 'sf_shop_fancy_page_height', 300 );
            }

            // Fancy heading image
            if ( $page_title_style == "fancy" && $fancy_title_image_url == "" ) {
                foreach ( $fancy_title_image as $detail_image ) {
                    if ( isset( $detail_image['url'] ) ) {
                        $fancy_title_image_url = $detail_image['url'];
                        break;
                    }
                }
                if ( ! $fancy_title_image ) {
                    $fancy_title_image     = get_post_thumbnail_id();
                    $fancy_title_image_url = wp_get_attachment_url( $fancy_title_image, 'full' );
                }
            }

            // Page Title Hidden
            if ( ! $show_page_title ) {
                $page_heading_el_class = "page-heading-hidden";
            }
            
            // Breadcrumb in heading
            if ( $breadcrumb_in_heading ) {
            	$page_heading_el_class .= " page-heading-breadcrumbs";
            }


            if ( ! is_home() ) {
                ?>
                <?php if ( $page_title_style == "fancy" ) { ?>

                    <div class="fancy-heading-wrap">

                    <?php if ( $fancy_title_image_url != "" ) { ?>
                        <div class="page-heading fancy-heading clearfix <?php echo $page_title_text_style; ?>-style fancy-image <?php echo $page_heading_el_class; ?>" style="background-image: url(<?php echo $fancy_title_image_url; ?>);" data-height="<?php echo $page_title_height; ?>">
                    <?php } else { ?>
                        <div class="page-heading fancy-heading <?php echo $page_heading_el_class; ?> clearfix" data-height="<?php echo $page_title_height; ?>" <?php echo $article_heading_bg; ?>>
                    <?php } ?>
                    <div class="heading-text container" data-textalign="<?php echo $page_title_text_align; ?>">
                        <?php if ( sf_woocommerce_activated() && is_woocommerce() ) { ?>

                            <?php if ( is_product() ) { ?>

                                <h1 class="entry-title" <?php echo $article_heading_text; ?>><?php echo $page_title; ?></h1>

                            <?php } else { ?>

                                <h1 class="entry-title" <?php echo $article_heading_text; ?>><?php woocommerce_page_title(); ?></h1>

                            <?php } ?>

                        <?php } else { ?>

                            <h1 class="entry-title"><?php echo $page_title; ?></h1>

                        <?php } ?>

                        <?php if ( $page_subtitle ) { ?>
                            <h3><?php echo $page_subtitle; ?></h3>
                        <?php } ?>
						
						<?php if ( !$remove_breadcrumbs && $breadcrumb_in_heading ) {
							echo sf_breadcrumbs( true );
						} ?>
						
                        <?php if ( is_singular( 'portfolio' ) && ! ( sf_theme_opts_name() == "sf_joyn_options" && $pagination_style == "fs-arrow" ) ) { ?>
                            <div
                                class="prev-item" <?php echo $article_heading_text; ?>><?php next_post_link( '%link', '<i class="ss-navigateleft"></i>', $enable_category_navigation, '', 'portfolio-category' ); ?></div>
                            <div
                                class="next-item" <?php echo $article_heading_text; ?>><?php previous_post_link( '%link', '<i class="ss-navigateright"></i>', $enable_category_navigation, '', 'portfolio-category' ); ?></div>
                        <?php } ?>

                        <?php if ( is_singular( 'galleries' ) && ! ( sf_theme_opts_name() == "sf_joyn_options" && $pagination_style == "fs-arrow" ) ) { ?>
                            <div
                                class="prev-item" <?php echo $article_heading_text; ?>><?php next_post_link( '%link', '<i class="ss-navigateleft"></i>', false, '', 'gallery-category' ); ?></div>
                            <div
                                class="next-item" <?php echo $article_heading_text; ?>><?php previous_post_link( '%link', '<i class="ss-navigateright"></i>', false, '', 'gallery-category' ); ?></div>
                        <?php } ?>

                    </div>

                    </div>

                    </div>

                <?php } else { ?>

                    <?php if ( $show_page_title == 2 ) { ?>
                        <div class="page-heading ph-sort clearfix" <?php echo $article_heading_bg; ?>>
                    <?php } else { ?>
                        <div class="page-heading <?php echo $page_heading_el_class; ?> clearfix" <?php echo $article_heading_bg; ?>>
                    <?php } ?>
                    <div class="container">
                        <div class="heading-text">

                            <?php if ( sf_woocommerce_activated() && is_woocommerce() ) { ?>

                                <?php if ( is_product() ) { ?>

                                    <h1 class="entry-title" <?php echo $article_heading_text; ?>><?php echo $page_title; ?></h1>

                                <?php } else { ?>

                                    <h1 class="entry-title" <?php echo $article_heading_text; ?>><?php woocommerce_page_title(); ?></h1>

                                <?php } ?>

                            <?php } else if ( is_search() ) { ?>

                                <?php
                                $s         = get_search_query();
                                $allsearch = new WP_Query( "s=$s&showposts=-1" );
                                $key       = esc_html( $s, 1 );
                                $count     = $allsearch->post_count;
                                wp_reset_query(); ?>
                                <?php if ( $count == 1 ) : ?>
                                    <?php printf( __( '<h1>%1$s result for <span>%2$s</span></h1>', 'swiftframework' ), $count, get_search_query() ); ?>
                                <?php else : ?>
                                    <?php printf( __( '<h1>%1$s results for <span>%2$s</span></h1>', 'swiftframework' ), $count, get_search_query() ); ?>
                                <?php endif; ?>

                            <?php } else if ( is_category() ) { ?>

                                <h1 <?php echo $article_heading_text; ?>><?php single_cat_title(); ?></h1>

                            <?php } else if ( is_archive() ) { ?>

                                <?php /* If this is a tag archive */
                                if ( is_tag() ) { ?>
                                    <h1 <?php echo $article_heading_text; ?>><?php _e( "Posts tagged with", "swiftframework" ); ?>
                                        &#8216;<?php single_tag_title(); ?>&#8217;</h1>
                                    <?php /* If this is a daily archive */
                                } elseif ( is_day() ) { ?>
                                    <h1 <?php echo $article_heading_text; ?>><?php _e( "Archive for", "swiftframework" ); ?> <?php the_time( 'F jS, Y' ); ?></h1>
                                    <?php /* If this is a monthly archive */
                                } elseif ( is_month() ) { ?>
                                    <h1 <?php echo $article_heading_text; ?>><?php _e( "Archive for", "swiftframework" ); ?> <?php the_time( 'F, Y' ); ?></h1>
                                    <?php /* If this is a yearly archive */
                                } elseif ( is_year() ) { ?>
                                    <h1 <?php echo $article_heading_text; ?>><?php _e( "Archive for", "swiftframework" ); ?> <?php the_time( 'Y' ); ?></h1>
                                    <?php /* If this is an author archive */
                                } elseif ( is_author() ) { ?>
                                    <?php $author = get_userdata( get_query_var( 'author' ) ); ?>
                                    <?php if ( class_exists( 'ATCF_Campaigns' ) ) { ?>
                                        <h1 <?php echo $article_heading_text; ?>><?php _e( "Projects by", "swiftframework" ); ?> <?php echo $author->display_name; ?></h1>
                                    <?php } else { ?>
                                        <h1 <?php echo $article_heading_text; ?>><?php _e( "Author archive for", "swiftframework" ); ?> <?php echo $author->display_name; ?></h1>
                                    <?php } ?>
                                    <?php /* If this is a paged archive */
                                } elseif ( isset( $_GET['paged'] ) && ! empty( $_GET['paged'] ) ) { ?>
                                    <h1 <?php echo $article_heading_text; ?>><?php _e( "Blog Archives", "swiftframework" ); ?></h1>
                                <?php } else { ?>
                                    <h1 <?php echo $article_heading_text; ?>><?php post_type_archive_title(); ?></h1>
                                <?php } ?>

                            <?php } else if ( is_404() ) { ?>

                                <h1 class="entry-title" <?php echo $article_heading_text; ?>><?php _e( "404", "swiftframework" ); ?></h1>

                            <?php } else { ?>

                                <h1 class="entry-title" <?php echo $article_heading_text; ?>><?php echo $page_title; ?></h1>

                            <?php } ?>

                        </div>

                        <?php if ( is_singular( 'portfolio' ) && ! ( sf_theme_opts_name() == "sf_joyn_options" && $pagination_style == "fs-arrow" ) ) { ?>
                            <div
                                class="prev-item" <?php echo $article_heading_text; ?>><?php next_post_link( '%link', '<i class="ss-navigateleft"></i>', $enable_category_navigation, '', 'portfolio-category' ); ?></div>
                            <div
                                class="next-item" <?php echo $article_heading_text; ?>><?php previous_post_link( '%link', '<i class="ss-navigateright"></i>', $enable_category_navigation, '', 'portfolio-category' ); ?></div>
                        <?php } ?>

                        <?php if ( is_singular( 'galleries' ) && ! ( sf_theme_opts_name() == "sf_joyn_options" && $pagination_style == "fs-arrow" ) ) { ?>
                            <div
                                class="prev-item" <?php echo $article_heading_text; ?>><?php next_post_link( '%link', '<i class="ss-navigateleft"></i>', false, '', 'gallery-category' ); ?></div>
                            <div
                                class="next-item" <?php echo $article_heading_text; ?>><?php previous_post_link( '%link', '<i class="ss-navigateright"></i>', false, '', 'gallery-category' ); ?></div>
                        <?php } ?>
							
						<?php if ( !$remove_breadcrumbs && $breadcrumb_in_heading ) {
							echo sf_breadcrumbs( true );
						} ?>

                        <?php if ( $shop_page ) {
                            woocommerce_catalog_ordering();
                            woocommerce_result_count();
                        } ?>
                     	
                    </div>
                </div>
                <?php
                }
            }
        }

        add_action( 'sf_main_container_start', 'sf_page_heading', 20 );
    }
?>