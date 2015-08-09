<?php
    /**
     * Order details
     *
     * @author        WooThemes
     * @package       WooCommerce/Templates
     * @version       2.2.0
     */

    if ( ! defined( 'ABSPATH' ) ) {
        exit;
    } // Exit if accessed directly

?>

<?php

    global $woocommerce;

    $myaccount_page_id  = get_option( 'woocommerce_myaccount_page_id' );
    $myaccount_page_url = "";
    if ( $myaccount_page_id ) {
        $myaccount_page_url = get_permalink( $myaccount_page_id );
    }

    $order = wc_get_order( $order_id );

?>

<?php sf_woo_help_bar(); ?>

<div class="my-account-left">

    <h4 class="lined-heading"><span><?php _e( "My Account", "swiftframework" ); ?></span></h4>
    <ul class="nav my-account-nav">
        <li><a href="<?php echo $myaccount_page_url; ?>"><?php _e( "Back to my account", "swiftframework" ); ?></a></li>
    </ul>

</div>

<div class="my-account-right">

    <h4><?php _e( 'Order Details', 'swiftframework' ); ?></h4>
    <table class="shop_table order_details">
        <thead>
        <tr>
            <th class="product-img"><?php _e( 'Item', 'swiftframework' ); ?></th>
            <th class="product-name"><?php _e( 'Product', 'swiftframework' ); ?></th>
            <th class="product-quantity"><?php _e( 'Quantity', 'swiftframework' ); ?></th>
            <th class="product-total"><?php _e( 'Total', 'swiftframework' ); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php
            if ( sizeof( $order->get_items() ) > 0 ) {

                foreach ( $order->get_items() as $item ) {

                    $_product  = get_product( $item['variation_id'] ? $item['variation_id'] : $item['product_id'] );
                    $thumbnail = apply_filters( 'woocommerce_in_cart_product_thumbnail', $_product->get_image(), $item );

                    echo '
						<tr class = "' . esc_attr( apply_filters( 'woocommerce_order_table_item_class', 'order_table_item', $item, $order ) ) . '">';
                    echo '<td class="product-img">';

                    if ( ! $_product->is_visible() || ( ! empty( $_product->variation_id ) && ! $_product->parent_is_visible() ) ) {
                        echo $thumbnail;
                    } else {
                        printf( '<a href="%s">%s</a>', esc_url( get_permalink( apply_filters( 'woocommerce_in_cart_product_id', $item['product_id'] ) ) ), $thumbnail );
                    }

                    echo '</td>
							<td class="product-name">' .
                         apply_filters( 'woocommerce_order_table_product_title', '<a href="' . get_permalink( $item['product_id'] ) . '">' . $item['name'] . '</a>', $item );

                    $item_meta = new WC_Order_Item_Meta( $item['item_meta'] );
                    $item_meta->display();

                    if ( $_product && $_product->exists() && $_product->is_downloadable() && $order->is_download_permitted() ) {

                        $download_file_urls = $order->get_downloadable_file_urls( $item['product_id'], $item['variation_id'], $item );

                        $i     = 0;
                        $links = array();

                        foreach ( $download_file_urls as $file_url => $download_file_url ) {

                            $filename = woocommerce_get_filename_from_url( $file_url );

                            $links[] = '<small><a href="' . $download_file_url . '">' . sprintf( __( 'Download file%s', 'swiftframework' ), ( count( $download_file_urls ) > 1 ? ' ' . ( $i + 1 ) . ': ' : ': ' ) ) . $filename . '</a></small>';

                            $i ++;
                        }

                        echo implode( '<br/>', $links );
                    }

                    echo '</td>';
                    echo '<td class="product-quantity">' .
                         apply_filters( 'woocommerce_checkout_item_quantity', $item['qty'], $item ) .
                         '</td>';
                    echo '<td class="product-total">' . $order->get_formatted_line_subtotal( $item ) . '</td></tr>';

                    // Show any purchase notes
                    if ( $order->status == 'completed' || $order->status == 'processing' ) {
                        if ( $purchase_note = sf_get_post_meta( $_product->id, '_purchase_note', true ) ) {
                            echo '<tr class="product-purchase-note"><td colspan="3">' . apply_filters( 'the_content', $purchase_note ) . '</td></tr>';
                        }
                    }

                }
            }

            do_action( 'woocommerce_order_items_table', $order );
        ?>
        </tbody>
    </table>

    <table class="totals_table">
        <tbody>
        <?php
            if ( $totals = $order->get_order_item_totals() ) {
                foreach ( $totals as $total ) :
                    ?>
                    <tr>
                        <th scope="row"><?php echo $total['label']; ?></th>
                        <td><?php echo $total['value']; ?></td>
                    </tr>
                <?php
                endforeach;
            }
        ?>
        </tbody>
    </table>

    <div class="order-hr clearfix"></div>

    <?php if ( get_option( 'woocommerce_allow_customers_to_reorder' ) == 'yes' && $order->status == 'completed' ) : ?>
        <p class="order-again">
            <a href="<?php echo esc_url( $woocommerce->nonce_url( 'order_again', add_query_arg( 'order_again', $order->id, add_query_arg( 'order', $order->id, get_permalink( woocommerce_get_page_id( 'view_order' ) ) ) ) ) ); ?>"
               class="button"><?php _e( 'Order Again', 'swiftframework' ); ?></a>
        </p>
    <?php endif; ?>

    <?php do_action( 'woocommerce_order_details_after_order_table', $order ); ?>

    <header>
        <h4><?php _e( 'Customer details', 'swiftframework' ); ?></h4>
    </header>
    <dl class="customer_details">
        <?php
            if ( $order->billing_email ) {
                echo '<dt>' . __( 'Email:', 'swiftframework' ) . '</dt><dd>' . $order->billing_email . '</dd>';
            }
            if ( $order->billing_phone ) {
                echo '<dt>' . __( 'Telephone:', 'swiftframework' ) . '</dt><dd>' . $order->billing_phone . '</dd>';
            }
        ?>
    </dl>

    <?php if (get_option( 'woocommerce_ship_to_billing_address_only' ) == 'no') : ?>

    <div class="col2-set addresses">

        <div class="col-1">

            <?php endif; ?>

            <header class="title">
                <h4><?php _e( 'Billing Address', 'swiftframework' ); ?></h4>
            </header>
            <address><p>
                    <?php
                        if ( ! $order->get_formatted_billing_address() ) {
                            _e( 'N/A', 'swiftframework' );
                        } else {
                            echo $order->get_formatted_billing_address();
                        }
                    ?>
                </p></address>

            <?php if (get_option( 'woocommerce_ship_to_billing_address_only' ) == 'no') : ?>

        </div>
        <!-- /.col-1 -->

        <div class="col-2">

            <header class="title">
                <h4><?php _e( 'Shipping Address', 'swiftframework' ); ?></h4>
            </header>
            <address><p>
                    <?php
                        if ( ! $order->get_formatted_shipping_address() ) {
                            _e( 'N/A', 'swiftframework' );
                        } else {
                            echo $order->get_formatted_shipping_address();
                        }
                    ?>
                </p></address>

        </div>
        <!-- /.col-2 -->

    </div>
    <!-- /.col2-set -->

<?php endif; ?>

    <div class="clear"></div>

</div>