<?php
    /**
     * The template for displaying product content in the single-product.php template
     * Override this template by copying it to yourtheme/woocommerce/content-single-product.php
     *
     * @author        WooThemes
     * @package       WooCommerce/Templates
     * @version       2.1.2
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    } // Exit if accessed directly
?>

<?php
    global $post, $product, $sf_catalog_mode, $sidebar_config, $sf_options;

    $product_layout = sf_get_post_meta( $post->ID, 'sf_product_layout', true );

    $pb_active = sf_get_post_meta( $post->ID, '_spb_js_status', true );
    if ( $pb_active != "true" ) {
        $pb_active = false;
    }
?>

    <div class="container">
        <?php
            /**
             * woocommerce_before_single_product hook
             *
             * @hooked wc_print_notices - 10
             */
            do_action( 'woocommerce_before_single_product' );

            if ( post_password_required() ) {
                echo get_the_password_form();

                return;
            }
        ?>
    </div>

    <div itemscope itemtype="<?php echo woocommerce_get_product_schema(); ?>"
         id="product-<?php the_ID(); ?>" <?php post_class(); ?>>

        <div class="entry-title" itemprop="name"><?php the_title(); ?></div>

        <?php if ($sidebar_config == "no-sidebars" && $product_layout != "fw-split") { ?>
        <div class="container">
            <?php } ?>

            <?php
                /**
                 * woocommerce_show_product_images hook
                 *
                 * @hooked woocommerce_show_product_sale_flash - 10
                 * @hooked woocommerce_show_product_images - 20
                 */
                do_action( 'woocommerce_before_single_product_summary' );
            ?>

            <div class="summary entry-summary">

                <div class="summary-top clearfix">

                    <?php if ( $product_layout == "fw-split" ) { ?>

                        <h1><?php the_title(); ?></h1>

                    <?php } ?>

                    <div itemprop="offers" itemscope itemtype="http://schema.org/Offer">

                        <p itemprop="price" class="price"><?php echo $product->get_price_html(); ?></p>

                        <meta itemprop="priceCurrency" content="<?php echo get_woocommerce_currency(); ?>"/>

                        <?php if ( ! $sf_catalog_mode ) { ?>
                            <link itemprop="availability"
                                  href="http://schema.org/<?php echo $product->is_in_stock() ? 'InStock' : 'OutOfStock'; ?>" /><?php } ?>

                    </div>

                    <?php
                        if ( comments_open() ) {

                            $count = $wpdb->get_var( "
						    SELECT COUNT(meta_value) FROM $wpdb->commentmeta
						    LEFT JOIN $wpdb->comments ON $wpdb->commentmeta.comment_id = $wpdb->comments.comment_ID
						    WHERE meta_key = 'rating'
						    AND comment_post_ID = $post->ID
						    AND comment_approved = '1'
						    AND meta_value > 0
						" );

                            $rating = $wpdb->get_var( "
					        SELECT SUM(meta_value) FROM $wpdb->commentmeta
					        LEFT JOIN $wpdb->comments ON $wpdb->commentmeta.comment_id = $wpdb->comments.comment_ID
					        WHERE meta_key = 'rating'
					        AND comment_post_ID = $post->ID
					        AND comment_approved = '1'
					    " );

                            if ( $count > 0 ) {

                                $average = number_format( $rating / $count, 2 );

                                $reviews_text = sprintf( _n( '%d Review', '%d Reviews', $count, 'Swift Framework' ), $count );

                                echo '<div class="review-summary"><div class="star-rating" title="' . sprintf( __( 'Rated %s out of 5', 'swiftframework' ), $average ) . '"><span style="width:' . ( $average * 16 ) . 'px"><span class="rating">' . $average . '</span> ' . __( 'out of 5', 'swiftframework' ) . '</span></div><div class="reviews-text">' . $reviews_text . '</div></div>';

                            }
                        }
                    ?>
                    <?php
                        $has_cat          = get_the_terms( $post->ID, 'product_cat' );
                        $pagination_style = "standard";
                        if ( isset( $sf_options['pagination_style'] ) ) {
                            $pagination_style = $sf_options['pagination_style'];
                        }
                    ?>
                    <?php if ( $has_cat != 0 && $pagination_style == "standard" ) { ?>
                        <div class="product-navigation">
                            <div
                                class="nav-previous"><?php previous_post_link( '%link', '<i class="ss-navigateright"></i>', true, '', 'product_cat' ); ?></div>
                            <div
                                class="nav-next"><?php next_post_link( '%link', '<i class="ss-navigateleft"></i>', true, '', 'product_cat' ); ?></div>
                        </div>
                    <?php } ?>

                </div>

                <?php
                    /**
                     * woocommerce_single_product_summary hook
                     *
                     * @hooked woocommerce_template_single_title - 5
                     * @hooked woocommerce_template_single_price - 10
                     * @hooked woocommerce_template_single_excerpt - 20
                     * @hooked woocommerce_template_single_add_to_cart - 30
                     * @hooked woocommerce_template_single_meta - 40
                     * @hooked woocommerce_template_single_sharing - 50
                     */

                    do_action( 'woocommerce_single_product_summary' );
                ?>


            </div>
            <!-- .summary -->

            <?php if ($sidebar_config == "no-sidebars" && $product_layout != "fw-split") { ?>
        </div>
    <?php } ?>

        <?php if ( $pb_active ) { ?>

            <div id="product-display-area" class="clearfix">

                <?php the_content(); ?>

            </div>

        <?php } ?>

        <?php if ($sidebar_config == "no-sidebars") { ?>
        <div class="container">
            <?php } ?>

            <?php
                /**
                 * woocommerce_after_single_product_summary hook
                 *
                 * @hooked woocommerce_output_product_data_tabs - 10
                 * @hooked woocommerce_output_related_products - 20
                 */
                do_action( 'woocommerce_after_single_product_summary' );
            ?>

            <?php if ($sidebar_config == "no-sidebars") { ?>
        </div>
    <?php } ?>

        <meta itemprop="url" content="<?php the_permalink(); ?>"/>

    </div><!-- #product-<?php the_ID(); ?> -->

<?php do_action( 'woocommerce_after_single_product' ); ?>