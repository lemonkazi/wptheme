<?php
    /*
    *
    *	Head Tag Functions
    *	------------------------------------------------
    *	Swift Framework
    * 	Copyright Swift Ideas 2014 - http://www.swiftideas.net
    *
    *	sf_head_meta()
    *	sf_page_classes()
    *
    */


    /* HEAD META
    ================================================== */
    if ( ! function_exists( 'sf_head_meta' ) ) {
        function sf_head_meta() {

            global $sf_options;

            $enable_responsive = $sf_options['enable_responsive'];
            global $post, $remove_promo_bar, $enable_one_page_nav;
            if ( $post ) {
                $remove_promo_bar    = sf_get_post_meta( $post->ID, 'sf_remove_promo_bar', true );
                $enable_one_page_nav = sf_get_post_meta( $post->ID, 'sf_enable_one_page_nav', true );
            }
            ?>

            <!--// SITE TITLE //-->
            <title><?php wp_title( '|', true, 'right' ); ?></title>

            <!--// SITE META //-->
            <meta charset="<?php bloginfo( 'charset' ); ?>"/>
            <?php if ( $enable_responsive ) { ?>
                <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
            <?php } ?>
            <?php if ( isset( $sf_options['custom_ios_title'] ) && $sf_options['custom_ios_title'] != "" ) { ?>
                <meta name="apple-mobile-web-app-title"
                      content="<?php echo __( $sf_options['custom_ios_title'], 'swiftframework' ); ?>">
            <?php } ?>

            <!--// PINGBACK & FAVICON //-->
            <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>"/>
            <?php if ( isset( $sf_options['custom_favicon']['url'] ) && $sf_options['custom_favicon']['url'] != "" ) { ?>
                <link rel="shortcut icon" href="<?php echo $sf_options['custom_favicon']['url']; ?>" /><?php } ?>

            <?php if ( isset( $sf_options['custom_ios_icon144']['url'] ) && $sf_options['custom_ios_icon144']['url'] != "" ) { ?>
                <link rel="apple-touch-icon-precomposed" sizes="144x144"
                      href="<?php echo $sf_options['custom_ios_icon144']['url']; ?>"/>
            <?php } ?>
            <?php if ( isset( $sf_options['custom_ios_icon114']['url'] ) && $sf_options['custom_ios_icon114']['url'] != "" ) { ?>
                <link rel="apple-touch-icon-precomposed" sizes="114x114"
                      href="<?php echo $sf_options['custom_ios_icon114']['url']; ?>"/>
            <?php } ?>
            <?php if ( isset( $sf_options['custom_ios_icon72']['url'] ) && $sf_options['custom_ios_icon72']['url'] != "" ) { ?>
                <link rel="apple-touch-icon-precomposed" sizes="72x72"
                      href="<?php echo $sf_options['custom_ios_icon72']['url']; ?>"/>
            <?php } ?>
            <?php if ( isset( $sf_options['custom_ios_icon57']['url'] ) && $sf_options['custom_ios_icon57']['url'] != "" ) { ?>
                <link rel="apple-touch-icon-precomposed" sizes="57x57"
                      href="<?php echo $sf_options['custom_ios_icon57']['url']; ?>"/>
            <?php } ?>

        <?php
        }

        add_action( 'wp_head', 'sf_head_meta', 0 );
    }


    /* SOCIAL META
    ================================================== */

    //Adding the Open Graph in the Language Attributes
    function sf_add_opengraph_doctype( $output ) {
        global $sf_options;
        $disable_social_meta = false;
        if ( isset( $sf_options['disable_social_meta'] ) ) {
            $disable_social_meta = $sf_options['disable_social_meta'];
        }
        if ( $disable_social_meta ) {
            return $output;
        }

        return $output . ' xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://www.facebook.com/2008/fbml"';
    }

    add_filter( 'language_attributes', 'sf_add_opengraph_doctype' );

    if ( ! function_exists( 'sf_social_meta' ) ) {
        function sf_social_meta() {
            global $post, $sf_options;

            $logo = array();
            if ( isset( $sf_options['logo_upload'] ) ) {
                $logo = $sf_options['logo_upload'];
            }
            $disable_social_meta = false;
            if ( isset( $sf_options['disable_social_meta'] ) ) {
                $disable_social_meta = $sf_options['disable_social_meta'];
            }

            if ( ! $post || ! is_singular() || class_exists( 'WPSEO_Admin' ) || $disable_social_meta ) {
                return;
            }

            $title             = strip_tags( get_the_title() );
            $permalink         = get_permalink();
            $site_name         = get_bloginfo( 'name' );
            $excerpt           = get_the_excerpt();
            $content           = get_the_content();
            $twitter_author    = $sf_options['twitter_author_username'];
            $googleplus_author = $sf_options['googleplus_author'];
            if ( $excerpt != "" ) {
                $excerpt = strip_tags( trim( preg_replace( '|\[(.+?)\](.+?\[/\\1\])?|s', '', $content ) ) );
                if ( function_exists( 'mb_strimwidth' ) ) {
                    $excerpt = mb_strimwidth( $excerpt, 0, 100, '...' );
                }
            }
            if ( function_exists( 'is_product' ) && is_product() ) {
                $product_description       = sf_get_post_meta( $post->ID, 'sf_product_description', true );
                $product_short_description = sf_get_post_meta( $post->ID, 'sf_product_short_description', true );
                if ( $product_description != "" ) {
                    $excerpt = strip_tags( $product_description );
                } else if ( $product_short_description != "" ) {
                    $excerpt = strip_tags( $product_short_description );
                }
            }

            $image_url = "";
            if ( has_post_thumbnail( $post->ID ) ) {
                $thumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'medium' );
                $image_url = esc_attr( $thumbnail[0] );
            } else if ( isset( $logo['url'] ) && $logo['url'] != "" ) {
                $image_url = $logo['url'];
            }

            echo "" . "\n";
            echo '<!-- Facebook Meta -->' . "\n";
            echo '<meta property="og:title" content="' . $title . ' - ' . $site_name . '"/>' . "\n";
            echo '<meta property="og:type" content="article"/>' . "\n";
            echo '<meta property="og:url" content="' . $permalink . '"/>' . "\n";
            echo '<meta property="og:site_name" content="' . $site_name . '"/>' . "\n";
            echo '<meta property="og:description" content="' . $excerpt . '">' . "\n";
            if ( $image_url != "" ) {
                echo '<meta property="og:image" content="' . $image_url . '"/>' . "\n";
            }
            if ( function_exists( 'is_product' ) && is_product() ) {
                $product = new WC_Product( $post->ID );
                echo '<meta property="og:price:amount" content="' . $product->price . '" />' . "\n";
                echo '<meta property="og:price:currency" content="' . get_woocommerce_currency() . '" />' . "\n";
            }

            echo "" . "\n";
            echo '<!-- Twitter Card data -->' . "\n";
            echo '<meta name="twitter:card" content="summary_large_image">' . "\n";
            echo '<meta name="twitter:title" content="' . $title . '">' . "\n";
            echo '<meta name="twitter:description" content="' . $excerpt . '">' . "\n";
            if ( $twitter_author != "" ) {
                echo '<meta name="twitter:site" content="@' . $twitter_author . '">' . "\n";
                echo '<meta name="twitter:creator" content="@' . $twitter_author . '">' . "\n";
            }
            if ( $image_url != "" ) {
                echo '<meta property="twitter:image:src" content="' . $image_url . '"/>' . "\n";
            }
            if ( function_exists( 'is_product' ) && is_product() ) {
                $product = new WC_Product( $post->ID );
                echo '<meta name="twitter:data1" content="' . $product->price . '">' . "\n";
                echo '<meta name="twitter:label1" content="Price">' . "\n";
            }
            echo "" . "\n";

            echo "" . "\n";
            if ( $googleplus_author != "" ) {
                echo '<!-- Google Authorship and Publisher Markup -->' . "\n";
                echo '<link rel="author" href="https://plus.google.com/' . $googleplus_author . '/posts"/>' . "\n";
                echo '<link rel="publisher" href="https://plus.google.com/' . $googleplus_author . '"/>' . "\n";
            }
        }

        add_action( 'wp_head', 'sf_social_meta', 5 );
    }


    /* PAGE CLASS
    ================================================== */
    if ( ! function_exists( 'sf_page_classes' ) ) {
        function sf_page_classes() {

            // Get options
            global $sf_options, $post, $sf_catalog_mode;

            $enable_responsive = $sf_options['enable_responsive'];
            $is_responsive     = "responsive-fluid";
            if ( ! $enable_responsive ) {
                $is_responsive = "responsive-fixed";
            }
            $enable_rtl   = $sf_options['enable_rtl'];
            $design_style = sf_get_option( 'design_style_type', 'minimal' );

            $page_header_type = $page_slider = $page_header_alt_logo = "";
            $header_layout    = $sf_options['header_layout'];
            if ( isset( $_GET['header'] ) ) {
                $header_layout = $_GET['header'];
            }
            $page_layout             = $sf_options['page_layout'];
            $enable_page_shadow      = $sf_options['enable_page_shadow'];
            $enable_page_transitions = $sf_options['enable_page_transitions'];
            $page_transition         = $sf_options['page_transition'];
            $enable_header_shadow    = false;
            if ( isset( $sf_options['enable_header_shadow'] ) ) {
                $enable_header_shadow = $sf_options['enable_header_shadow'];
            }
            $enable_mini_header        = $sf_options['enable_mini_header'];
            $enable_mini_header_resize = $sf_options['enable_mini_header_resize'];
            $header_search_type        = $sf_options['header_search_type'];
            $mobile_header_layout      = $sf_options['mobile_header_layout'];
            $mobile_header_shown       = $sf_options['mobile_header_shown'];
            $mobile_header_sticky      = $sf_options['mobile_header_sticky'];
            $mobile_menu_type          = "slideout";
            if ( isset( $sf_options['mobile_menu_type'] ) ) {
                $mobile_menu_type = $sf_options['mobile_menu_type'];
            }
            $enable_articleswipe = 0;
            if ( isset( $sf_options['enable_articleswipe'] ) ) {
                $enable_articleswipe = $sf_options['enable_articleswipe'];
            }
            $home_preloader = false;
            if ( isset( $sf_options['home_preloader'] ) ) {
                $home_preloader = $sf_options['home_preloader'];
            }

            if ( is_page() && $post ) {
                $page_header_type     = sf_get_post_meta( $post->ID, 'sf_page_header_type', true );
                $page_header_alt_logo = sf_get_post_meta( $post->ID, 'sf_page_header_alt_logo', true );
                $page_slider          = sf_get_post_meta( $post->ID, 'sf_page_slider', true );
            }

            if ( ( $page_header_type == "naked-light" || $page_header_type == "naked-dark" ) && ( $header_layout == "header-vert" || $header_layout == "header-vert-right" ) ) {
                $header_layout = "header-1";
            }

            // Create variables
            $page_class = $header_wrap_class = $logo_class = "";

            // Design Style
            if ( isset( $_GET['design_style'] ) ) {
                $design_style = $_GET['design_style'];
            }
            $page_class .= $design_style . "-design ";

            // Header Layout
            if ( $page_header_type == "standard-overlay" ) {
                $page_class .= "header-overlay ";
            } else if ( $header_layout == "header-5" ) {
                $page_class .= "header-5-overlay ";
            }
            if ( $header_layout == "header-vert" ) {
                $page_class .= "vertical-header ";
            }
            if ( $header_layout == "header-vert-right" ) {
                $page_class .= "vertical-header vertical-header-right ";
            }
            if ( $header_layout == "header-3" || $header_layout == "header-4" || $header_layout == "header-5" ) {
                $header_wrap_class = " full-center";
            }
            if ( $header_layout == "header-3" || $header_layout == "header-4" || $header_layout == "header-5" || $header_layout == "header-7" || $header_layout == "header-8" ) {
                $header_wrap_class .= " full-header-stick";
            }

            // Mobile Header Layout
            $page_class .= "mobile-header-" . $mobile_header_layout . " ";
            $page_class .= "mhs-" . $mobile_header_shown . " ";
            if ( $mobile_header_sticky ) {
                $page_class .= "mh-sticky ";
            }
            $page_class .= 'mh-' . $mobile_menu_type . ' ';

            // Catalog Mode
            if ( isset( $sf_options['enable_catalog_mode'] ) ) {
                $enable_catalog_mode = $sf_options['enable_catalog_mode'];
                if ( $enable_catalog_mode ) {
                    $sf_catalog_mode = true;
                    $page_class .= "catalog-mode ";
                }
            }

            // Responsive classes
            $page_class .= $is_responsive . ' ';

            if ( is_rtl() || $enable_rtl || isset( $_GET['RTL'] ) ) {
                $page_class .= 'rtl ';
            }

            // Mini header
            if ( $enable_mini_header && ! ( $header_layout == "header-vert" || $header_layout == "header-vert-right" ) ) {
                $page_class .= "sticky-header-enabled ";
                if ( $enable_mini_header_resize ) {
                    $page_class .= "sh-dynamic ";
                }
            }

            // Page Shadow
            if ( $enable_page_shadow ) {
                $page_class .= "page-shadow ";
            }

            // Page Transtions
            if ( $enable_page_transitions ) {
                $page_class .= "page-transitions ";
                if ( $page_transition == "loading-bar" ) {
                    $page_class .= "loading-bar-transition ";
                }
            }

            // Header Shadow
            if ( $enable_header_shadow ) {
                $page_class .= "header-shadow ";
            }

            // Page Header Type
            if ( $page_header_type != "" ) {
                $page_class .= 'header-' . $page_header_type . ' ';
            }

            // Page Header Logo
            if ( $page_header_alt_logo ) {
                $page_class .= 'logo-alt-version ';
            }

            if ( is_singular( 'post' ) && $post ) {
                $post_header_type = sf_get_post_meta( $post->ID, 'sf_page_header_type', true );
                $fw_media_display = sf_get_post_meta( $post->ID, 'sf_fw_media_display', true );
                $page_title_style = sf_get_post_meta( $post->ID, 'sf_page_title_style', true );

                if ( $page_title_style == "fancy" || $fw_media_display == "fw-media-title" || $fw_media_display == "fw-media" ) {
                    $page_class .= 'header-' . $post_header_type . ' ';
                }
            }

            if ( function_exists( 'is_product' ) && is_product() ) {
                $product_layout = sf_get_post_meta( $post->ID, 'sf_product_layout', true );
                $page_class .= 'product-' . $product_layout . ' ';
            }

            // Layout
            if ( isset( $_GET['layout'] ) ) {
                $page_layout = $_GET['layout'];
            }
            $page_class .= "layout-" . $page_layout . " ";


            // Article Swipe
            if ( $enable_articleswipe ) {
                $page_class .= 'article-swipe ';
            }

            if ( $home_preloader && ( is_home() || is_front_page() ) && ! is_paged() ) {
                $page_class .= 'sf-preloader ';
            }

            // Return array of classes
            $class_array = array(
                "page-layout"   => $page_layout,
                "page"          => $page_class,
                "header-layout" => $header_layout,
                "header-wrap"   => $header_wrap_class,
                "logo"          => $logo_class,
                "search-type"   => $header_search_type
            );

            return $class_array;
        }
    }
?>
