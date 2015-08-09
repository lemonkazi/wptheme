<?php

    /*
    *
    *	Swift Framework Main Class
    *	------------------------------------------------
    *	Swift Framework v3.0
    * 	Copyright Swift Ideas 2014 - http://www.swiftideas.net
    *
    */

    /* CUSTOM POST TYPES
    ================================================== */
    $sf_options  = get_option( sf_theme_opts_name() );
    $disable_spb = false;
    if ( isset( $sf_options['disable_spb'] ) ) {
        $disable_spb = $sf_options['disable_spb'];
    }
    $disable_ss = false;
    if ( isset( $sf_options['disable_ss'] ) ) {
        $disable_ss = $sf_options['disable_ss'];
    }

    /* CUSTOM POST TYPES
    ================================================== */
    require_once( SF_FRAMEWORK_PATH . '/custom-post-types/portfolio-type.php' );
    require_once( SF_FRAMEWORK_PATH . '/custom-post-types/galleries-type.php' );
    require_once( SF_FRAMEWORK_PATH . '/custom-post-types/team-type.php' );
    require_once( SF_FRAMEWORK_PATH . '/custom-post-types/clients-type.php' );
    require_once( SF_FRAMEWORK_PATH . '/custom-post-types/testimonials-type.php' );
    require_once( SF_FRAMEWORK_PATH . '/custom-post-types/directory-type.php' );
    require_once( SF_FRAMEWORK_PATH . '/custom-post-types/spb-section-type.php' );
    require_once( SF_FRAMEWORK_PATH . '/custom-post-types/sf-post-type-permalinks.php' );


    /* SWIFT PAGE BUILDER
    ================================================== */
    if ( ! $disable_spb ) {
        include_once( SF_FRAMEWORK_PATH . '/page-builder/sf-page-builder.php' );
    }

    /* SWIFT SLIDER
    ================================================== */
    if ( ! $disable_ss ) {
        include_once( SF_FRAMEWORK_PATH . '/swift-slider/swift-slider.php' );
    }

    /* CORE FILES
    ================================================== */
    include_once( SF_FRAMEWORK_PATH . '/core/sf-base.php' );
    include_once( SF_FRAMEWORK_PATH . '/core/sf-breadcrumbs.php' );
    include_once( SF_FRAMEWORK_PATH . '/core/sf-comments.php' );
    include_once( SF_FRAMEWORK_PATH . '/core/sf-footer.php' );
    include_once( SF_FRAMEWORK_PATH . '/core/sf-formatting.php' );
    include_once( SF_FRAMEWORK_PATH . '/core/sf-functions.php' );
    include_once( SF_FRAMEWORK_PATH . '/core/sf-get-template.php' );
    include_once( SF_FRAMEWORK_PATH . '/core/sf-head.php' );
    include_once( SF_FRAMEWORK_PATH . '/core/sf-header.php' );
    include_once( SF_FRAMEWORK_PATH . '/core/sf-media.php' );
    include_once( SF_FRAMEWORK_PATH . '/core/sf-menus.php' );
    include_once( SF_FRAMEWORK_PATH . '/core/sf-page-heading.php' );
    include_once( SF_FRAMEWORK_PATH . '/core/sf-pagination.php' );
    include_once( SF_FRAMEWORK_PATH . '/core/sf-shortcodes.php' );
    include_once( SF_FRAMEWORK_PATH . '/core/sf-sidebars.php' );
    include_once( SF_FRAMEWORK_PATH . '/core/sf-directory.php' );
    if ( sf_woocommerce_activated() ) {
        include_once( SF_FRAMEWORK_PATH . '/core/sf-supersearch.php' );
    }
    include_once( SF_FRAMEWORK_PATH . '/core/sf-woocommerce.php' );


    /* CONTENT FILES
    ================================================== */
    include_once( SF_FRAMEWORK_PATH . '/content/sf-blog.php' );
    include_once( SF_FRAMEWORK_PATH . '/content/sf-campaign-detail.php' );
    include_once( SF_FRAMEWORK_PATH . '/content/sf-galleries.php' );
    include_once( SF_FRAMEWORK_PATH . '/content/sf-pageslider.php' );
    include_once( SF_FRAMEWORK_PATH . '/content/sf-portfolio.php' );
    include_once( SF_FRAMEWORK_PATH . '/content/sf-portfolio-detail.php' );
    include_once( SF_FRAMEWORK_PATH . '/content/sf-post-detail.php' );
    include_once( SF_FRAMEWORK_PATH . '/content/sf-post-formats.php' );
    include_once( SF_FRAMEWORK_PATH . '/content/sf-products.php' );


    /* MEGA MENU
    ================================================== */
    include_once( SF_FRAMEWORK_PATH . '/sf-megamenu/sf-megamenu.php' );


    /* WIDGETS
    ================================================== */
    include_once( SF_FRAMEWORK_PATH . '/widgets/widget-twitter.php' );
    include_once( SF_FRAMEWORK_PATH . '/widgets/widget-flickr.php' );
    include_once( SF_FRAMEWORK_PATH . '/widgets/widget-video.php' );
    include_once( SF_FRAMEWORK_PATH . '/widgets/widget-posts.php' );
    include_once( SF_FRAMEWORK_PATH . '/widgets/widget-portfolio.php' );
    include_once( SF_FRAMEWORK_PATH . '/widgets/widget-portfolio-grid.php' );
    include_once( SF_FRAMEWORK_PATH . '/widgets/widget-advertgrid.php' );
    include_once( SF_FRAMEWORK_PATH . '/widgets/widget-infocus.php' );
    include_once( SF_FRAMEWORK_PATH . '/widgets/widget-comments.php' );
    include_once( SF_FRAMEWORK_PATH . '/widgets/widget-mostloved.php' );


    /* TEXT DOMAIN
    ================================================== */
    load_theme_textdomain( 'swift-framework-admin', get_template_directory() . '/swift-framework/language' );


    /* DISABLE MASTER SLIDER AUTO UPDATE
    ================================================== */
    add_filter( 'masterslider_disable_auto_update', '__return_true' );

?>
