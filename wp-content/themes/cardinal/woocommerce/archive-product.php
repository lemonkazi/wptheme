<?php
    /**
     * The Template for displaying product archives, including the main shop page which is a post type archive.
     * Override this template by copying it to yourtheme/woocommerce/archive-product.php
     *
     * @author        WooThemes
     * @package       WooCommerce/Templates
     * @version       2.0.0
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    } // Exit if accessed directly

    global $sf_options;

    $sidebar_config            = $sf_options['woo_sidebar_config'];
    $left_sidebar              = $sf_options['woo_left_sidebar'];
    $right_sidebar             = $sf_options['woo_right_sidebar'];
    $product_display_fullwidth = $sf_options['product_display_fullwidth'];
    $product_display_columns   = $sf_options['product_display_columns'];
    $product_display_type      = $sf_options['product_display_type'];
    $product_fw_mode           = false;
	
	if (isset($_GET['sidebar'])) {
		$sidebar_config = $_GET['sidebar'];
	}
		
    if ( $product_display_fullwidth ) {
        $product_fw_mode = true;
    }

    $width = "";
    if ( $product_display_columns == "4" ) {
        $width = 'col-sm-3';
    } else if ( $product_display_columns == "5" ) {
        $width = 'col-sm-sf-5';
    } else if ( $product_display_columns == "3" ) {
        $width = 'col-sm-4';
    } else if ( $product_display_columns == "2" ) {
        $width = 'col-sm-6';
    } else if ( $product_display_columns == "6" ) {
        $width = 'col-sm-2';
    }

    if ( $sidebar_config == "" ) {
        $sidebar_config = 'right-sidebar';
    }
    if ( $left_sidebar == "" ) {
        $left_sidebar = 'woocommerce-sidebar';
    }
    if ( $right_sidebar == "" ) {
        $right_sidebar = 'woocommerce-sidebar';
    }

    $page_class      = $content_class = $orig_sidebar_config = $cont_width = $sidebar_width = $cont_push = $sidebar_pull = '';
    $page_wrap_class = "woocommerce-shop-page ";


    if ( $product_fw_mode ) {
        $page_wrap_class .= 'full-width-shop ';
        $orig_sidebar_config = $sidebar_config;
        $sidebar_config      = "no-sidebars";
    }

    if ( $sf_options['sidebar_width'] == "reduced" ) {
        $cont_width    = "col-sm-9";
        $cont_push     = "col-sm-push-3";
        $sidebar_width = "col-sm-3";
        $sidebar_pull  = "col-sm-pull-9";
    } else {
        $cont_width    = "col-sm-8";
        $cont_push     = "col-sm-push-4";
        $sidebar_width = "col-sm-4";
        $sidebar_pull  = "col-sm-pull-8";
    }

    if ( $orig_sidebar_config != "" ) {
        if ( $orig_sidebar_config == "left-sidebar" ) {
            $page_wrap_class .= 'has-left-sidebar has-one-sidebar';
        } else if ( $orig_sidebar_config == "right-sidebar" ) {
            $page_wrap_class .= 'has-right-sidebar has-one-sidebar';
        } else if ( $orig_sidebar_config == "both-sidebars" ) {
            $page_wrap_class .= 'has-both-sidebars';
        } else {
            $page_wrap_class .= 'has-no-sidebar';
        }
        $page_class    = "row clearfix";
        $content_class = "col-sm-12 clearfix";
    } else {
        if ( $sidebar_config == "left-sidebar" ) {
            $page_wrap_class .= 'has-left-sidebar has-one-sidebar row';
            $page_class    = $cont_width . " " . $cont_push . " clearfix";
            $content_class = "clearfix";
        } else if ( $sidebar_config == "right-sidebar" ) {
            $page_wrap_class .= 'has-right-sidebar has-one-sidebar row';
            $page_class    = $cont_width . " clearfix";
            $content_class = "clearfix";
        } else if ( $sidebar_config == "both-sidebars" ) {
            $page_wrap_class .= 'has-both-sidebars row';
            $page_class    = $cont_width . " clearfix";
            $content_class = $cont_width . " clearfix";
        } else {
            $page_wrap_class .= 'has-no-sidebar';
            $page_class    = "row clearfix";
            $content_class = "col-sm-12 clearfix";
        }
    }

    $content_class .= ' product-type-' . $product_display_type;


    global $sf_include_isotope, $sf_has_products;
    $sf_include_isotope = true;
    $sf_has_products    = true;

    get_header( 'shop' );    ?>

<?php if ( ! $product_fw_mode ) { ?>
    <div class="container">
<?php } ?>

<?php
    /**
     * woocommerce_before_main_content hook
     *
     * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
     * @hooked woocommerce_breadcrumb - 20
     */
    do_action( 'woocommerce_before_main_content' );
?>

    <div class="inner-page-wrap <?php echo $page_wrap_class; ?> clearfix"
         data-shopcolumns="<?php echo $product_display_columns; ?>">

        <!-- OPEN section -->
        <section class="<?php echo $page_class; ?>">

            <!-- OPEN .page-content -->
            <div class="page-content <?php echo $content_class; ?>">

                <?php do_action( 'woocommerce_archive_description' ); ?>

                <?php if ( have_posts() ) : ?>

                    <!-- LOOP START -->
                    <?php woocommerce_product_loop_start(); ?>

                    <?php woocommerce_product_subcategories(); ?>

                    <?php if ( $product_fw_mode && ( $orig_sidebar_config == "left-sidebar" || $orig_sidebar_config == "both-sidebars" ) ) { ?>

                        <div class="sidebar left-sidebar <?php echo $width; ?>">
                            <?php dynamic_sidebar( $left_sidebar ); ?>
                        </div>

                    <?php } ?>

                    <?php while ( have_posts() ) : the_post(); ?>

                        <?php wc_get_template_part( 'content', 'product' ); ?>

                    <?php endwhile; // end of the loop. ?>

                    <?php if ( $product_fw_mode && ( $orig_sidebar_config == "right-sidebar" || $orig_sidebar_config == "both-sidebars" ) ) { ?>

                        <div class="sidebar right-sidebar <?php echo $width; ?>">
                            <?php dynamic_sidebar( $right_sidebar ); ?>
                        </div>

                    <?php } ?>

                    <!-- LOOP END -->
                    <?php woocommerce_product_loop_end(); ?>

                    <?php
                    /**
                     * woocommerce_after_shop_loop hook
                     *
                     * @hooked woocommerce_pagination - 10
                     */
                    do_action( 'woocommerce_after_shop_loop' );
                    ?>

                <?php elseif ( ! woocommerce_product_subcategories( array(
                        'before' => woocommerce_product_loop_start( false ),
                        'after'  => woocommerce_product_loop_end( false )
                    ) )
                ) : ?>

                    <div class="no-products-wrap container">
                        <?php woocommerce_get_template( 'loop/no-products-found.php' ); ?>
                    </div>

                <?php endif; ?>

                <!-- CLOSE .page-content -->
            </div>

            <?php if ( $sidebar_config == "both-sidebars" ) { ?>
                <aside class="sidebar left-sidebar col-sm-3">

                    <?php do_action( 'sf_after_sidebar' ); ?>

                    <div class="sidebar-widget-wrap">
                        <?php dynamic_sidebar( $left_sidebar ); ?>
                    </div>

                    <?php do_action( 'sf_before_sidebar' ); ?>

                </aside>
            <?php } ?>

            <!-- CLOSE section -->
        </section>

        <?php if ( $sidebar_config == "left-sidebar" ) { ?>

            <aside class="sidebar left-sidebar <?php echo $sidebar_width; ?> <?php echo $sidebar_pull; ?>">

                <?php do_action( 'sf_after_sidebar' ); ?>

                <div class="sidebar-widget-wrap">
                    <?php dynamic_sidebar( $left_sidebar ); ?>
                </div>

                <?php do_action( 'sf_before_sidebar' ); ?>

            </aside>

        <?php } else if ( $sidebar_config == "right-sidebar" ) { ?>

            <aside class="sidebar right-sidebar <?php echo $sidebar_width; ?>">

                <?php do_action( 'sf_after_sidebar' ); ?>

                <div class="sidebar-widget-wrap">
                    <?php dynamic_sidebar( $right_sidebar ); ?>
                </div>

                <?php do_action( 'sf_before_sidebar' ); ?>

            </aside>

        <?php } else if ( $sidebar_config == "both-sidebars" ) { ?>

            <aside class="sidebar right-sidebar col-sm-3">

                <?php do_action( 'sf_after_sidebar' ); ?>

                <div class="sidebar-widget-wrap">
                    <?php dynamic_sidebar( $right_sidebar ); ?>
                </div>

                <?php do_action( 'sf_before_sidebar' ); ?>

            </aside>

        <?php } ?>

    </div>

<?php
    /**
     * woocommerce_after_main_content hook
     *
     * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
     */
    do_action( 'woocommerce_after_main_content' );
?>

<?php if ( ! $product_fw_mode ) { ?>
    </div>
<?php } ?>

<?php get_footer( 'shop' ); ?>