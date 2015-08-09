<?php

    if ( ! defined( 'ABSPATH' ) ) {
        die( '-1' );
    }


    /* DEFINITIONS
    ================================================== */
    define( 'SPB_VERSION', '3.0' );
    define( 'SPB_PATH', dirname( __FILE__ ) );
    $spb_settings = Array(
        'SPB_ROOT'       => SPB_PATH . '/',
        'SPB_DIR'        => basename( SPB_PATH ) . '/',
        'SPB_ASSETS'     => 'assets/',
        'SPB_INC'        => SPB_PATH . '/inc/',
        'SPB_SHORTCODES' => SPB_PATH . '/shortcodes/'
    );
    $inc_dir      = $spb_settings['SPB_INC'];
    define( 'SPB_SHORTCODES', $spb_settings['SPB_SHORTCODES'] );


    /* INCLUDE INC FILES
    ================================================== */
    require_once( $inc_dir . 'abstract.php' );
    require_once( $inc_dir . 'helpers.php' );
    require_once( $inc_dir . 'mapper.php' );
    require_once( $inc_dir . 'shortcodes.php' );
    require_once( $inc_dir . 'builder.php' );
    require_once( $inc_dir . 'layouts.php' );
    require_once( $inc_dir . 'templates.php' );


    /* INCLUDE SHORTCODE FILES
    ================================================== */
    if ( ! function_exists( 'spb_register_assets' ) ) {
        function spb_register_assets() {
            $pb_assets = array();
            $path      = dirname( __FILE__ ) . '/shortcodes/';
            $folders   = scandir( $path, 1 );
            foreach ( $folders as $file ) {
                if ( $file == '.' || $file == '..' || $file == '.DS_Store' || $file == "blog-grid-old.php" ) {
                    continue;
                }
                $file               = substr( $file, 0, - 4 );
                $pb_assets[ $file ] = $file;
            }

            //
            //// PB Assets Array
            //$pb_assets = array(
            //    'default'              => 'default',
            //    'column'               => 'column',
            //    'row'                  => 'row',
            //    'accordion'            => 'accordion',
            //    'accordion_tab'        => 'accordion_tab',
            //    'tabs'                 => 'tabs',
            //    'tour'                 => 'tour',
            //    'promo-bar'            => 'promo-bar',
            //    'media'                => 'media',
            //    'raw_content'          => 'raw_content',
            //    'portfolio'            => 'portfolio',
            //    'blog'                 => 'blog',
            //    'campaigns'            => 'campaigns',
            //    'blog-grid'            => 'blog-grid',
            //    'icon-boxes'           => 'icon-boxes',
            //    'products'             => 'products',
            //    'gallery'              => 'gallery',
            //    'galleries'            => 'galleries',
            //    'clients'              => 'clients',
            //    'team'                 => 'team',
            //    'testimonial'          => 'testimonial',
            //    'testimonial-carousel' => 'testimonial-carousel',
            //    'testimonial-slider'   => 'testimonial-slider',
            //    'revslider'            => 'revslider',
            //    'recent-posts'         => 'recent-posts',
            //    'parallax'             => 'parallax',
            //    'portfolio-showcase'   => 'portfolio-showcase',
            //    'portfolio-carousel'   => 'portfolio-carousel',
            //    'code-snippet'         => 'code-snippet',
            //    'googlechart'          => 'googlechart',
            //    'sitemap'              => 'sitemap',
            //    'search'               => 'search',
            //    'widget-area'          => 'widget-area',
            //    'supersearch'          => 'supersearch',
            //    'latest-tweets'        => 'latest-tweets',
            //    'tweets-slider'        => 'tweets-slider',
            //    'gravityforms'         => 'gravityforms',
            //    'gopricing'            => 'gopricing',
            //    'swift-slider'         => 'swift-slider',
            //    'counter'              => 'counter',
            //);

            $pb_assets = apply_filters( 'spb_assets_filter', $pb_assets );

            if ( ! sf_gravityforms_activated() ) {
                unset( $pb_assets['gravityforms'] );
            }
            if ( ! sf_woocommerce_activated() ) {
                unset( $pb_assets['products'] );
            }
            if ( ! sf_gopricing_activated() ) {
                unset( $pb_assets['gopricing'] );
            }
            if ( ! sf_ninjaforms_activated() ) {
                unset( $pb_assets['ninjaforms'] );
            }

            // Load Each Asset
            foreach ( $pb_assets as $asset ) {
                require_once( SPB_SHORTCODES . $asset . '.php' );
            }

        }

        if ( is_admin() ) {
            add_action( 'admin_init', 'spb_register_assets', 2 );
        }
        if ( ! is_admin() ) {
            add_action( 'wp', 'spb_register_assets', 2 );
        }
    }


    /* INCLUDE BUILDER SETUP
    ================================================== */
    require_once( $inc_dir . 'build.php' );


    /* LAYOUT & SHORTCODE SETUP
    ================================================== */
    require_once( $inc_dir . 'default-map.php' );


    /* INITIALISE BUILDER
    ================================================== */
    $wpSPB_setup = is_admin() ? new SFPageBuilderSetupAdmin() : new SFPageBuilderSetup();
    $wpSPB_setup->init( $spb_settings );
