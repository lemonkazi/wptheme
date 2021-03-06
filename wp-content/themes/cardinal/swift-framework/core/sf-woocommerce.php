<?php
    /*
    *
    *	WooCommerce Functions & Hooks
    *	------------------------------------------------
    *	Swift Framework
    * 	Copyright Swift Ideas 2014 - http://www.swiftideas.net
    *
    */

    /* FILTER HOOKS
    ================================================== */
    /* Remove default content wrapper output */
    remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10 );
    remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10 );

    /* Remove default WooCommerce breadcrumbs */
    remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0 );

    /* Move rating output */
    remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
    add_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 15 );

    /* Remove default thumbnail output */
    remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );

    /* Remove default sale flash output */
    remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );


    /* ADJUST DESCRIPTION OUTPUT
    ================================================== */
    if ( ! function_exists( 'woocommerce_taxonomy_archive_description' ) ) {
        function woocommerce_taxonomy_archive_description() {
            if ( is_tax( array( 'product_cat', 'product_tag' ) ) && get_query_var( 'paged' ) == 0 ) {
                $description = apply_filters( 'the_content', term_description() );
                if ( $description ) {
                    echo '<div class="term-description container">' . $description . '</div>';
                }
            }
        }
    }


    /* REMOVE WOOCOMMERCE PRETTYPHOTO STYLES/SCRIPTS
    ================================================== */
    function sf_remove_woo_lightbox_js() {
        wp_dequeue_script( 'prettyPhoto' );
        wp_dequeue_script( 'prettyPhoto-init' );
    }

    add_action( 'wp_enqueue_scripts', 'sf_remove_woo_lightbox_js', 99 );

    function sf_remove_woo_lightbox_css() {
        wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
    }

    add_action( 'wp_enqueue_styles', 'sf_remove_woo_lightbox_css', 99 );


    /* REMOVE META BOX ON WC SHOP PAGE
    ================================================== */
    function sf_check_shop_page() {
        $screen = get_current_screen();
        if ( sf_woocommerce_activated() && $screen->post_type == 'page' ) {
            $wc_shop_id      = wc_get_page_id( 'shop' );
            $current_page_id = 0;

            if ( isset( $_GET['post'] ) ) {
                $current_page_id = $_GET['post'];
            }

            if ( $wc_shop_id == $current_page_id ) {
                echo '<style>.sf-meta-tabs-wrap {display: none!important;}</style>';
            }
        }
    }

    add_action( 'current_screen', 'sf_check_shop_page', 10 );


    /* WOOCOMMERCE CONTENT FUNCTIONS
    ================================================== */
    function sf_get_product_stars() {

        $stars_output = "";

        global $wpdb;
        global $post;
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
            $stars_output .= '<div class="starwrapper" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">';
            $stars_output .= '<span class="star-rating" title="' . sprintf( __( 'Rated %s out of 5', 'swiftframework' ), $average ) . '"><span style="width:' . ( $average * 16 ) . 'px"><span itemprop="ratingValue" class="rating">' . $average . '</span> </span></span>';
            $stars_output .= '</div>';
        }

        return $stars_output;
    }

    function sf_is_out_of_stock() {
        global $post;
        $post_id      = $post->ID;
        $stock_status = sf_get_post_meta( $post_id, '_stock_status', true );
        if ( $stock_status == 'outofstock' ) {
            return true;
        } else {
            return false;
        }
    }

    if ( ! function_exists( 'sf_product_items_text' ) ) {
        function sf_product_items_text( $count, $alt = false ) {
            $product_item_text = "";

            if ( $alt == true ) {
                return number_format_i18n( $count );
            } else {
                if ( $count > 1 ) {
                    $product_item_text = str_replace( '%', number_format_i18n( $count ), __( '% items', 'swiftframework' ) );
                } elseif ( $count == 0 ) {
                    $product_item_text = __( '0 items', 'swiftframework' );
                } else {
                    $product_item_text = __( '1 item', 'swiftframework' );
                }

                return $product_item_text;
            }
        }
    }

    /* ADD TO CART HEADER RELOAD
    ================================================== */
    if ( ! function_exists( 'sf_woo_header_add_to_cart_fragment' ) ) {
        function sf_woo_header_add_to_cart_fragment( $fragments ) {
            global $woocommerce, $sf_options;

            ob_start();

            $show_cart_count = false;
            if ( isset( $sf_options['show_cart_count'] ) ) {
                $show_cart_count = $sf_options['show_cart_count'];
            }

            $cart_count          = $woocommerce->cart->cart_contents_count;
            $cart_count_text     = sf_product_items_text( $cart_count );
            $cart_count_text_alt = sf_product_items_text( $cart_count, true );
            ?>

            <li class="parent shopping-bag-item">

                <?php if ( $show_cart_count ) { ?>

                    <a class="cart-contents" href="<?php echo $woocommerce->cart->get_cart_url(); ?>"
                       title="<?php _e( 'View your shopping cart', 'swiftframework' ); ?>"><i
                            class="ss-cart"></i><?php echo $woocommerce->cart->get_cart_total(); ?>
                        (<?php echo $cart_count; ?>)<span
                            class="num-items"><?php echo $cart_count_text_alt; ?></span></a>

                <?php } else { ?>

                    <a class="cart-contents" href="<?php echo $woocommerce->cart->get_cart_url(); ?>"
                       title="<?php _e( 'View your shopping cart', 'swiftframework' ); ?>"><i
                            class="ss-cart"></i><?php echo $woocommerce->cart->get_cart_total(); ?><span
                            class="num-items"><?php echo $cart_count_text_alt; ?></span></a>

                <?php } ?>

                <ul class="sub-menu">
                    <li>
                        <div class="shopping-bag">

                            <?php if ( $cart_count != "0" ) { ?>

                                <div
                                    class="bag-header"><?php echo $cart_count_text; ?> <?php _e( 'in the cart', 'swiftframework' ); ?></div>

                                <div class="bag-contents">

                                    <?php foreach ( $woocommerce->cart->cart_contents as $cart_item_key => $cart_item ) { ?>

                                        <?php
                                        $bag_product   = $cart_item['data'];
                                        $product_title = $bag_product->get_title();
                                        ?>

                                        <?php if ( $bag_product->exists() && $cart_item['quantity'] > 0 ) { ?>

                                            <div class="bag-product clearfix">

                                                <figure><a class="bag-product-img"
                                                           href="<?php echo get_permalink( $cart_item['product_id'] ); ?>"><?php echo $bag_product->get_image(); ?></a>
                                                </figure>

                                                <div class="bag-product-details">
                                                    <div class="bag-product-title">
                                                        <a href="<?php echo get_permalink( $cart_item['product_id'] ); ?>">
                                                            <?php echo apply_filters( 'woocommerce_cart_widget_product_title', $product_title, $bag_product ); ?></a>
                                                    </div>
                                                    <div
                                                        class="bag-product-price"><?php _e( "Unit Price:", "swiftframework" ); ?> <?php echo woocommerce_price( $bag_product->get_price() ); ?></div>
                                                    <div
                                                        class="bag-product-quantity"><?php _e( 'Quantity:', 'swiftframework' ); ?> <?php echo $cart_item['quantity']; ?></div>
                                                </div>

                                                <?php echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf( '<a href="%s" class="remove" title="%s">&times;</a>', esc_url( $woocommerce->cart->get_remove_url( $cart_item_key ) ), __( 'Remove this item', 'swiftframework' ) ), $cart_item_key ); ?>

                                            </div>

                                        <?php } ?>

                                    <?php } ?>

                                </div>

                                <div class="bag-buttons">

                                    <a class="sf-button standard sf-icon-reveal bag-button"
                                       href="<?php echo esc_url( $woocommerce->cart->get_cart_url() ); ?>"><i
                                            class="ss-view"></i><span
                                            class="text"><?php _e( 'View cart', 'swiftframework' ); ?></span></a>

                                    <a class="sf-button standard sf-icon-reveal checkout-button"
                                       href="<?php echo esc_url( $woocommerce->cart->get_checkout_url() ); ?>"><i
                                            class="ss-creditcard"></i><span
                                            class="text"><?php _e( 'Proceed to checkout', 'swiftframework' ); ?></span></a>

                                </div>

                            <?php } else { ?>

                                <div class="bag-header"><?php _e( "0 items in the cart", "swiftframework" ); ?></div>

                                <div
                                    class="bag-empty"><?php _e( 'Unfortunately, your cart is empty.', 'swiftframework' ); ?></div>

                                <div class="bag-buttons">

                                    <?php $shop_page_url = get_permalink( woocommerce_get_page_id( 'shop' ) ); ?>

                                    <a class="sf-button standard sf-icon-reveal checkout-button"
                                       href="<?php echo esc_url( $shop_page_url ); ?>"><i class="ss-cart"></i><span
                                            class="text"><?php _e( 'Go to the shop', 'swiftframework' ); ?></span></a>

                                </div>

                            <?php } ?>

                        </div>
                    </li>
                </ul>
            </li>

            <?php

            $fragments['.shopping-bag-item'] = ob_get_clean();

            return $fragments;

        }

        add_filter( 'add_to_cart_fragments', 'sf_woo_header_add_to_cart_fragment' );
    }


    /* WISHLIST BUTTON
    ================================================== */
    if ( ! function_exists( 'sf_wishlist_button' ) ) {
        function sf_wishlist_button() {

            global $product, $yith_wcwl;

            if ( class_exists( 'YITH_WCWL_UI' ) ) {
                $url          = $yith_wcwl->get_wishlist_url();
                $product_type = $product->product_type;
                $exists       = $yith_wcwl->is_product_in_wishlist( $product->id );

                $classes = get_option( 'yith_wcwl_use_button' ) == 'yes' ? 'class="add_to_wishlist single_add_to_wishlist button alt"' : 'class="add_to_wishlist"';

                $html = '<div class="yith-wcwl-add-to-wishlist">';
                $html .= '<div class="yith-wcwl-add-button';  // the class attribute is closed in the next row

                $html .= $exists ? ' hide" style="display:none;"' : ' show"';

                $html .= '><a href="' . htmlspecialchars( $yith_wcwl->get_addtowishlist_url() ) . '" data-product-id="' . $product->id . '" data-product-type="' . $product_type . '" ' . $classes . ' ><i class="ss-star"></i></a>';
                $html .= '</div>';

                $html .= '<div class="yith-wcwl-wishlistaddedbrowse hide" style="display:none;"><span class="feedback">' . __( 'Product added to wishlist.', 'swiftframework' ) . '</span> <a href="' . $url . '"><i class="fa-check"></i></a></div>';
                $html .= '<div class="yith-wcwl-wishlistexistsbrowse ' . ( $exists ? 'show' : 'hide' ) . '" style="display:' . ( $exists ? 'block' : 'none' ) . '"><a href="' . $url . '"><i class="fa-check"></i></a></div>';
                $html .= '<div style="clear:both"></div><div class="yith-wcwl-wishlistaddresponse"></div>';

                $html .= '</div>';

                return $html;
            }
        }

        add_action( 'woocommerce_after_add_to_cart_button', 'sf_wishlist_button', 10 );
    }


    /* SHOW PRODUCTS COUNT URL PARAMETER
    ================================================== */
    $options           = get_option( sf_theme_opts_name() );
    $products_per_page = $options['products_per_page'];
    if ( isset( $_GET['layout'] ) ) {
        $page_layout = $_GET['layout'];
    }
    if ( isset( $_GET['show_products'] ) ) {
        if ( $_GET['show_products'] == "all" ) {
            add_filter( 'loop_shop_per_page', create_function( '$cols', 'return -1;' ) );
        } else {
            add_filter( 'loop_shop_per_page', create_function( '$cols', 'return ' . $_GET['show_products'] . ';' ) );
        }
    } else {
        add_filter( 'loop_shop_per_page', create_function( '$cols', 'return  ' . $products_per_page . ';' ) );
    }


    /* SINGLE PRODUCT
    ================================================== */
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
    remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
    remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
    remove_action( 'woocommerce_product_tabs', 'woocommerce_product_description_tab', 10 );
    remove_action( 'woocommerce_product_tab_panels', 'woocommerce_product_description_panel', 10 );

    add_action( 'woocommerce_single_product_summary', 'woocommerce_output_product_data_tabs', 35 );


    /* WOO PRODUCT SHORT DESCRIPTION
    ================================================== */
    if ( ! function_exists( 'sf_product_short' ) ) {
        function sf_product_short() {
            global $post;
            $product_short_description = sf_get_post_meta( $post->ID, 'sf_product_short_description', true );
            if ( $product_short_description == "" ) {
                $product_short_description = $post->post_excerpt;
            }
            if ( substr( $product_short_description, 0, 4 ) === '[spb' ) {
                $product_short_description = "";
            }

            if ( $product_short_description != "" ) {
                ?>
                <div class="product-short" class="entry-summary">
                    <?php echo do_shortcode( sf_add_formatting( $product_short_description ) ); ?>
                </div>
            <?php
            }
        }

        add_action( 'woocommerce_single_product_summary', 'sf_product_short', 0 );
    }


    /* WOO PRODUCT SHARE
    ================================================== */
    if ( ! function_exists( 'sf_product_share' ) ) {
        function sf_product_share() {
            global $post;
            $image = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), false, '' );
            ?>
            <div class="article-share product-share" data-buttontext="<?php _e( "Share this", "swiftframework" ); ?>"
                 data-image="<?php echo $image[0]; ?>"></div>
        <?php
        }

        add_action( 'woocommerce_single_product_summary', 'sf_product_share', 45 );
    }


    /* WOO PRODUCT META
    ================================================== */
    if ( ! function_exists( 'sf_product_meta' ) ) {
        function sf_product_meta() {
            global $sf_options;
            ?>
            <div class="meta-row clearfix">
                <span class="need-help"><?php _e( "Need Help?", "swiftframework" ); ?> <a href="#email-form"
                                                                                          class="inline accent"
                                                                                          data-toggle="modal"><?php _e( "Contact Us", "swiftframework" ); ?></a></span>
                <span class="leave-feedback"><a href="#feedback-form" class="inline accent"
                                                data-toggle="modal"><?php _e( "Leave Feedback", "swiftframework" ); ?></a></span>
            </div>
            <div id="email-form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="email-form-modal"
                 aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                                    class="ss-delete"></i></button>
                            <h3 id="email-form-modal"><?php _e( "Contact Us", "swiftframework" ); ?></h3>
                        </div>
                        <div class="modal-body">
                            <?php echo do_shortcode( $sf_options['email_modal'] ); ?>
                        </div>
                    </div>
                </div>
            </div>

            <div id="feedback-form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="feedback-form-modal"
                 aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                                    class="ss-delete"></i></button>
                            <h3 id="feedback-form-modal"><?php _e( "Leave Feedback", "swiftframework" ); ?></h3>
                        </div>
                        <div class="modal-body">
                            <?php echo do_shortcode( $sf_options['feedback_modal'] ); ?>
                        </div>
                    </div>
                </div>
            </div>

        <?php
        }

        add_action( 'woocommerce_product_meta_start', 'sf_product_meta', 10 );
    }


    /* WOO HELP BAR
    ================================================== */
    if ( ! function_exists( 'sf_woo_help_bar' ) ) {
        function sf_woo_help_bar() {
            global $sf_options;

            $help_bar_text  = __( $sf_options['help_bar_text'], 'swiftframework' );
            $email_modal    = __( $sf_options['email_modal'], 'swiftframework' );
            $shipping_modal = __( $sf_options['shipping_modal'], 'swiftframework' );
            $returns_modal  = __( $sf_options['returns_modal'], 'swiftframework' );
            $faqs_modal     = __( $sf_options['faqs_modal'], 'swiftframework' );
            ?>
            <div class="help-bar clearfix">
                <span><?php echo do_shortcode( $help_bar_text ); ?></span>
                <ul>
                    <?php if ( $email_modal != "" ) { ?>
                        <li><a href="#email-form" class="inline"
                               data-toggle="modal"><?php _e( "Email customer care", "swiftframework" ); ?></a></li>
                    <?php } ?>
                    <?php if ( $shipping_modal != "" ) { ?>
                        <li><a href="#shipping-information" class="inline"
                               data-toggle="modal"><?php _e( "Shipping information", "swiftframework" ); ?></a></li>
                    <?php } ?>
                    <?php if ( $returns_modal != "" ) { ?>
                        <li><a href="#returns-exchange" class="inline"
                               data-toggle="modal"><?php _e( "Returns & exchange", "swiftframework" ); ?></a></li>
                    <?php } ?>
                    <?php if ( $faqs_modal != "" ) { ?>
                        <li><a href="#faqs" class="inline"
                               data-toggle="modal"><?php _e( "F.A.Q.'s", "swiftframework" ); ?></a></li>
                    <?php } ?>
                </ul>
            </div>

            <?php if ( $email_modal != "" ) { ?>
                <div id="email-form" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="email-form-modal"
                     aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                                        class="ss-delete"></i></button>
                                <h3 id="email-form-modal"><?php _e( "Email customer care", "swiftframework" ); ?></h3>
                            </div>
                            <div class="modal-body">
                                <?php echo do_shortcode( $email_modal ); ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <?php if ( $shipping_modal != "" ) { ?>
                <div id="shipping-information" class="modal fade" tabindex="-1" role="dialog"
                     aria-labelledby="shipping-modal" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                                        class="ss-delete"></i></button>
                                <h3 id="shipping-modal"><?php _e( "Shipping information", "swiftframework" ); ?></h3>
                            </div>
                            <div class="modal-body">
                                <?php echo do_shortcode( $shipping_modal ); ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <?php if ( $returns_modal != "" ) { ?>
                <div id="returns-exchange" class="modal fade" tabindex="-1" role="dialog"
                     aria-labelledby="returns-modal" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                                        class="ss-delete"></i></button>
                                <h3 id="returns-modal"><?php _e( "Returns & exchange", "swiftframework" ); ?></h3>
                            </div>
                            <div class="modal-body">
                                <?php echo do_shortcode( $returns_modal ); ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <?php if ( $faqs_modal != "" ) { ?>
                <div id="faqs" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="faqs-modal"
                     aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><i
                                        class="ss-delete"></i></button>
                                <h3 id="faqs-modal"><?php _e( "F.A.Q.'s", "swiftframework" ); ?></h3>
                            </div>
                            <div class="modal-body">
                                <?php echo do_shortcode( $faqs_modal ); ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>

        <?php
        }
    }
?>
