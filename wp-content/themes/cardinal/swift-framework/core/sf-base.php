<?php

    /*
    *
    *	Swift Base Layout Functions
    *	------------------------------------------------
    *	Swift Framework v3.0
    * 	Copyright Swift Ideas 2014 - http://www.swiftideas.net
    *
    *	sf_base_layout()
    *	sf_base_sidebar()
    *
    */

    /* BASE LAYOUT OUTPUT
    ================================================== */
    if ( ! function_exists( 'sf_base_layout' ) ) {
        function sf_base_layout( $template, $type = "" ) {

            global $post, $sf_options;
            $sidebar_var           = sf_base_sidebar();
            $sidebar_config        = $sidebar_var['config'];
            $left_sidebar          = $sidebar_var['left'];
            $right_sidebar         = $sidebar_var['right'];
            $page_wrap_class       = $sidebar_var['page_wrap_class'];
            $remove_bottom_spacing = $remove_top_spacing = "";

            if ( $post ) {
                $remove_bottom_spacing = sf_get_post_meta( $post->ID, 'sf_no_bottom_spacing', true );
                $remove_top_spacing    = sf_get_post_meta( $post->ID, 'sf_no_top_spacing', true );
            }

            if ( $remove_bottom_spacing ) {
                $page_wrap_class .= ' no-bottom-spacing';
            }
            if ( $remove_top_spacing ) {
                $page_wrap_class .= ' no-top-spacing';
            }

            $cont_width = $sidebar_width = "";
			if ($sf_options['sidebar_width'] == "reduced") {
				$cont_width = "col-sm-9";
				$sidebar_width = "col-sm-3";
				$cont_width = apply_filters("sf_base_layout_cont_width_reduced", "col-sm-9");
				$sidebar_width = apply_filters("sf_base_layout_cont_width_reduced_sidebar", "col-sm-3");
			} else {
				$cont_width = "col-sm-8";
				$sidebar_width = "col-sm-4";
				$cont_width = apply_filters("sf_base_layout_cont_width", "col-sm-8");
				$sidebar_width = apply_filters("sf_base_layout_cont_width_sidebar", "col-sm-4");
			}

            ?>
        <div class="inner-page-wrap <?php echo $page_wrap_class; ?> clearfix">

            <!-- OPEN page -->
            <?php if ( $sidebar_config == "left-sidebar" ) { ?>
            <div class="<?php echo $cont_width; ?> push-right clearfix">
            <?php } else if ($sidebar_config == "right-sidebar") { ?>
            <div class="<?php echo $cont_width; ?> clearfix">
            <?php } else if ($sidebar_config == "both-sidebars") { ?>
            <div class="col-sm-9 clearfix">
            <?php } else { ?>
            <div class="clearfix">
        <?php } ?>

            <?php if ( $sidebar_config == "both-sidebars" ) { ?>
                <div class="row">

                    <div class="page-content <?php echo $cont_width; ?> hfeed clearfix">

                        <?php sf_get_template( $template, $type ); ?>

                    </div>

                    <aside class="sidebar left-sidebar <?php echo $sidebar_width; ?>">

                        <?php do_action( 'sf_before_sidebar' ); ?>

                        <div class="sidebar-widget-wrap">
                            <?php dynamic_sidebar( $left_sidebar ); ?>
                        </div>

                        <?php do_action( 'sf_after_sidebar' ); ?>

                    </aside>

                </div>
            <?php } else { ?>

                <div class="page-content hfeed clearfix">

                    <?php sf_get_template( $template, $type ); ?>

                </div>

            <?php } ?>

            <!-- CLOSE page -->
            </div>

            <?php if ( $sidebar_config == "left-sidebar" ) { ?>

                <aside class="sidebar left-sidebar <?php echo $sidebar_width; ?>">

                    <?php do_action( 'sf_before_sidebar' ); ?>

                    <div class="sidebar-widget-wrap">
                        <?php dynamic_sidebar( $left_sidebar ); ?>
                    </div>

                    <?php do_action( 'sf_after_sidebar' ); ?>

                </aside>

            <?php } else if ( $sidebar_config == "right-sidebar" ) { ?>

                <aside class="sidebar right-sidebar <?php echo $sidebar_width; ?>">

                    <?php do_action( 'sf_before_sidebar' ); ?>

                    <div class="sidebar-widget-wrap">
                        <?php dynamic_sidebar( $right_sidebar ); ?>
                    </div>

                    <?php do_action( 'sf_after_sidebar' ); ?>

                </aside>

            <?php } else if ( $sidebar_config == "both-sidebars" ) { ?>


                <aside class="sidebar right-sidebar col-sm-3">

                    <?php do_action( 'sf_before_sidebar' ); ?>

                    <div class="sidebar-widget-wrap">
                        <?php dynamic_sidebar( $right_sidebar ); ?>
                    </div>

                    <?php do_action( 'sf_after_sidebar' ); ?>

                </aside>

            <?php } ?>

            </div>

        <?php
        }
    }


    /* SIDEBAR CONFIG OUTPUT
    ================================================== */
    if ( ! function_exists( 'sf_base_sidebar' ) ) {
        function sf_base_sidebar() {

            // VARIABLES
            global $post, $sf_options;

            // DEFAULT SIDEBAR CONFIG
            $default_sidebar_config = $sf_options['default_sidebar_config'];
            $default_left_sidebar   = $sf_options['default_left_sidebar'];
            $default_right_sidebar  = $sf_options['default_right_sidebar'];
            $buddypress             = sf_is_buddypress();
            $bbpress                = sf_is_bbpress();
            $sidebar_config         = $left_sidebar = $right_sidebar = "";

            // ARCHIVE / CATEGORY SIDEBAR CONFIG
            if ( is_archive() || is_author() || is_category() ) {
                $default_sidebar_config = $sf_options['archive_sidebar_config'];
                $default_left_sidebar   = $sf_options['archive_sidebar_left'];
                $default_right_sidebar  = $sf_options['archive_sidebar_right'];
            }

            // PORTFOLIO CATEGORY SIDEBAR CONFIG
            if ( is_tax( 'portfolio-category' ) ) {
                $sidebar_config = "no-sidebars";
            }

            if ( is_tax( 'download_category' ) ) {
                $default_left_sidebar  = 'crowdfunding-sidebar';
                $default_right_sidebar = 'crowdfunding-sidebar';
            }

            // BUDDYPRESS SIDEBAR CONFIG
            if ( $buddypress != "" ) {
                $default_sidebar_config = $sf_options['bp_sidebar_config'];
                $default_left_sidebar   = $sf_options['bp_sidebar_left'];
                $default_right_sidebar  = $sf_options['bp_sidebar_right'];
            }

            // BBPRESS SIDEBAR CONFIG
            if ( $bbpress ) {
                $default_sidebar_config = $sf_options['bb_sidebar_config'];
                $default_left_sidebar   = $sf_options['bb_sidebar_left'];
                $default_right_sidebar  = $sf_options['bb_sidebar_right'];
            }

            // CURRENT POST/PAGE SIDEBAR CONFIG
            if ( $post && is_singular() ) {
                $sidebar_config = sf_get_post_meta( $post->ID, 'sf_sidebar_config', true );
                $left_sidebar   = sf_get_post_meta( $post->ID, 'sf_left_sidebar', true );
                $right_sidebar  = sf_get_post_meta( $post->ID, 'sf_right_sidebar', true );
            }

            if ( is_404() ) {
                $sidebar_config = $sf_options['404_sidebar_config'];
                $left_sidebar   = $sf_options['404_left_sidebar'];
                $right_sidebar  = $sf_options['404_right_sidebar'];
            }

            // DEFAULTS
            if ( $sidebar_config == "" ) {
                $sidebar_config = $default_sidebar_config;
            }
            if ( $left_sidebar == "" ) {
                $left_sidebar = $default_left_sidebar;
            }
            if ( $right_sidebar == "" ) {
                $right_sidebar = $default_right_sidebar;
            }
            if ( is_singular( 'post' ) || is_singular( 'portfolio' ) || is_singular( 'team' ) ) {
                $sidebar_config = "no-sidebar";
            }

            // SET SIDEBAR GLOBAL
            sf_set_sidebar_global( $sidebar_config );

            // PAGE WRAP CLASS
            $page_wrap_class = '';
            if ( $sidebar_config == "left-sidebar" ) {
                $page_wrap_class = 'has-left-sidebar has-one-sidebar row';
            } else if ( $sidebar_config == "right-sidebar" ) {
                $page_wrap_class = 'has-right-sidebar has-one-sidebar row';
            } else if ( $sidebar_config == "both-sidebars" ) {
                $page_wrap_class = 'has-both-sidebars row';
            } else {
                $page_wrap_class = 'has-no-sidebar';
            }

            // RETURN
            $sidebar_var                    = array();
            $sidebar_var['config']          = $sidebar_config;
            $sidebar_var['left']            = $left_sidebar;
            $sidebar_var['right']           = $right_sidebar;
            $sidebar_var['page_wrap_class'] = $page_wrap_class;

            return $sidebar_var;
        }
    }
?>