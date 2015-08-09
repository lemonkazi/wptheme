<?php

    /*
    *
    *	Swift Framework Sidebar Functions
    *	------------------------------------------------
    *	Swift Framework v3.0
    * 	Copyright Swift Ideas 2014 - http://www.swiftideas.net
    *
    *	sf_setup_sidebars()
    *	sf_sidebars_array()
    *	sf_set_sidebar_global()
    *
    */

    /* REGISTER SIDEBARS
    ================================================== */
    if ( ! function_exists( 'sf_register_sidebars' ) ) {
        function sf_register_sidebars() {
            if ( function_exists( 'register_sidebar' ) ) {

                $sf_options    = get_option( sf_theme_opts_name() );
                $footer_config = $sf_options['footer_layout'];

                register_sidebar( array(
                    'name'          => 'Sidebar One',
                    'description'   => 'This widget area can be selected in the page/post/product meta options, and also in the theme options panel - so that you can display it on pages of your choice.',
                    'before_widget' => '<section id="%1$s" class="widget %2$s clearfix">',
                    'after_widget'  => '</section>',
                    'before_title'  => '<div class="widget-heading clearfix"><h4 class="spb-heading"><span>',
                    'after_title'   => '</span></h4></div>',
                ) );
                register_sidebar( array(
                    'name'          => 'Sidebar Two',
                    'description'   => 'This widget area can be selected in the page/post/product meta options, and also in the theme options panel - so that you can display it on pages of your choice.',
                    'before_widget' => '<section id="%1$s" class="widget %2$s clearfix">',
                    'after_widget'  => '</section>',
                    'before_title'  => '<div class="widget-heading clearfix"><h4 class="spb-heading"><span>',
                    'after_title'   => '</span></h4></div>',
                ) );
                register_sidebar( array(
                    'name'          => 'Sidebar Three',
                    'description'   => 'This widget area can be selected in the page/post/product meta options, and also in the theme options panel - so that you can display it on pages of your choice.',
                    'before_widget' => '<section id="%1$s" class="widget %2$s clearfix">',
                    'after_widget'  => '</section>',
                    'before_title'  => '<div class="widget-heading clearfix"><h4 class="spb-heading"><span>',
                    'after_title'   => '</span></h4></div>',
                ) );
                register_sidebar( array(
                    'name'          => 'Sidebar Four',
                    'description'   => 'This widget area can be selected in the page/post/product meta options, and also in the theme options panel - so that you can display it on pages of your choice.',
                    'before_widget' => '<section id="%1$s" class="widget %2$s clearfix">',
                    'after_widget'  => '</section>',
                    'before_title'  => '<div class="widget-heading clearfix"><h4 class="spb-heading"><span>',
                    'after_title'   => '</span></h4></div>',
                ) );
                register_sidebar( array(
                    'name'          => 'Sidebar Five',
                    'description'   => 'This widget area can be selected in the page/post/product meta options, and also in the theme options panel - so that you can display it on pages of your choice.',
                    'before_widget' => '<section id="%1$s" class="widget %2$s clearfix">',
                    'after_widget'  => '</section>',
                    'before_title'  => '<div class="widget-heading clearfix"><h4 class="spb-heading"><span>',
                    'after_title'   => '</span></h4></div>',
                ) );
                register_sidebar( array(
                    'name'          => 'Sidebar Six',
                    'description'   => 'This widget area can be selected in the page/post/product meta options, and also in the theme options panel - so that you can display it on pages of your choice.',
                    'before_widget' => '<section id="%1$s" class="widget %2$s clearfix">',
                    'after_widget'  => '</section>',
                    'before_title'  => '<div class="widget-heading clearfix"><h4 class="spb-heading"><span>',
                    'after_title'   => '</span></h4></div>',
                ) );
                register_sidebar( array(
                    'name'          => 'Sidebar Seven',
                    'description'   => 'This widget area can be selected in the page/post/product meta options, and also in the theme options panel - so that you can display it on pages of your choice.',
                    'before_widget' => '<section id="%1$s" class="widget %2$s clearfix">',
                    'after_widget'  => '</section>',
                    'before_title'  => '<div class="widget-heading clearfix"><h4 class="spb-heading"><span>',
                    'after_title'   => '</span></h4></div>',
                ) );
                register_sidebar( array(
                    'name'          => 'Sidebar Eight',
                    'description'   => 'This widget area can be selected in the page/post/product meta options, and also in the theme options panel - so that you can display it on pages of your choice.',
                    'before_widget' => '<section id="%1$s" class="widget %2$s clearfix">',
                    'after_widget'  => '</section>',
                    'before_title'  => '<div class="widget-heading clearfix"><h4 class="spb-heading"><span>',
                    'after_title'   => '</span></h4></div>',
                ) );
                register_sidebar( array(
                    'id'            => 'footer-column-1',
                    'name'          => 'Footer Column 1',
                    'description'   => 'This widget area is used to display widgets in the footer. You can select the amount of footer columns in the theme options panel.',
                    'before_widget' => '<section id="%1$s" class="widget %2$s clearfix">',
                    'after_widget'  => '</section>',
                    'before_title'  => '<div class="widget-heading clearfix"><h6>',
                    'after_title'   => '</h6></div>',
                ) );
                if ( $footer_config != "footer-9" ) {
                    register_sidebar( array(
                        'id'            => 'footer-column-2',
                        'name'          => 'Footer Column 2',
                        'description'   => 'This widget area is used to display widgets in the footer. You can select the amount of footer columns in the theme options panel.',
                        'before_widget' => '<section id="%1$s" class="widget %2$s clearfix">',
                        'after_widget'  => '</section>',
                        'before_title'  => '<div class="widget-heading clearfix"><h6>',
                        'after_title'   => '</h6></div>',
                    ) );
                    register_sidebar( array(
                        'id'            => 'footer-column-3',
                        'name'          => 'Footer Column 3',
                        'description'   => 'This widget area is used to display widgets in the footer. You can select the amount of footer columns in the theme options panel.',
                        'before_widget' => '<section id="%1$s" class="widget %2$s clearfix">',
                        'after_widget'  => '</section>',
                        'before_title'  => '<div class="widget-heading clearfix"><h6>',
                        'after_title'   => '</h6></div>',
                    ) );
                }
                if ( $footer_config == "footer-1" ) {
                    register_sidebar( array(
                        'id'            => 'footer-column-4',
                        'name'          => 'Footer Column 4',
                        'description'   => 'This widget area is used to display widgets in the footer. You can select the amount of footer columns in the theme options panel.',
                        'before_widget' => '<section id="%1$s" class="widget %2$s clearfix">',
                        'after_widget'  => '</section>',
                        'before_title'  => '<div class="widget-heading clearfix"><h6>',
                        'after_title'   => '</h6></div>',
                    ) );
                }
                register_sidebar( array(
                    'id'            => 'woocommerce-sidebar',
                    'name'          => 'WooCommerce Sidebar',
                    'description'   => 'This widget area is for you to use as a specialised WooCommerce widget area. You can select this widget area to be used in the Theme Options > WooCommerce Options panels, and also in the product meta options.',
                    'before_widget' => '<section id="%1$s" class="widget %2$s clearfix">',
                    'after_widget'  => '</section>',
                    'before_title'  => '<div class="widget-heading clearfix"><h4 class="spb-heading"><span>',
                    'after_title'   => '</span></h4></div>',
                ) );
                register_sidebar( array(
                    'id'            => 'crowdfunding-sidebar',
                    'name'          => 'Crowdfunding Sidebar',
                    'description'   => 'This widget area is for you to use as a specialised Crowdfunding widget area. The widgets added here will be shown on Crowdfunding pages.',
                    'before_widget' => '<section id="%1$s" class="widget %2$s clearfix">',
                    'after_widget'  => '</section>',
                    'before_title'  => '<div class="widget-heading clearfix"><h4 class="spb-heading"><span>',
                    'after_title'   => '</span></h4></div>',
                ) );
            }
        }

        add_action( 'widgets_init', 'sf_register_sidebars' );
    }


    /* GET SIDEBARS ARRAY
    ================================================== */
    function sf_sidebars_array() {
        $sidebars = array();

        foreach ( $GLOBALS['wp_registered_sidebars'] as $sidebar ) {
            $sidebars[ ucwords( $sidebar['id'] ) ] = $sidebar['name'];
        }

        return $sidebars;
    }


    /* SET SIDEBAR GLOBAL
    ================================================== */
    function sf_set_sidebar_global( $sidebar_config ) {
        global $sf_sidebar_config;
        if ( ( $sidebar_config == "left-sidebar" ) || ( $sidebar_config == "right-sidebar" ) ) {
            $sf_sidebar_config = 'one-sidebar';
        } else if ( $sidebar_config == "both-sidebars" ) {
            $sf_sidebar_config = 'both-sidebars';
        } else {
            $sf_sidebar_config = 'no-sidebars';
        }
    }

?>