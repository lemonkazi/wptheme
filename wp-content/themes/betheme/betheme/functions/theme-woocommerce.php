<?php
/**
 * WooCommerce functions.
 *
 * @package Betheme
 * @author Muffin group
 * @link http://muffingroup.com
 */


/* ---------------------------------------------------------------------------
 * WooCommerce - Theme support & actions
 * --------------------------------------------------------------------------- */
add_theme_support( 'woocommerce' );

remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20, 0);					// breadcrumbs
remove_action( 'woocommerce_sidebar', 'woocommerce_get_sidebar', 10);								// sidebar

remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);		// content wrapper begin
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);		// content wrapper end

if( mfn_opts_get('shop-catalogue') ){
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30);	// add to cart button
}

add_action( 'woocommerce_before_main_content', 'mfn_woocommerce_output_content_wrapper', 10);		// content wrapper begin
add_action( 'woocommerce_after_main_content', 'mfn_woocommerce_output_content_wrapper_end', 10);	// content wrapper end


/* ---------------------------------------------------------------------------
 * WooCommerce - Styles
 * --------------------------------------------------------------------------- */
function mfn_woo_styles()
{
	wp_enqueue_style( 'mfn-woo', THEME_URI .'/css/woocommerce.css', 'woocommerce_frontend_styles-css', THEME_VERSION, 'all' );
}
add_action( 'wp_enqueue_scripts', 'mfn_woo_styles', 100 );


/* ---------------------------------------------------------------------------
 * WooCommerce - Define image sizes
* --------------------------------------------------------------------------- */
global $pagenow;
if ( is_admin() && isset( $_GET['activated'] ) && $pagenow == 'themes.php' ) add_action( 'init', 'mfn_woocommerce_image_dimensions', 1 );

function mfn_woocommerce_image_dimensions() {
	$catalog = array(
		'width' 	=> '500',	// px
		'height'	=> '500',	// px
		'crop'		=> 1 		// true
	);

	$single = array(
		'width' 	=> '500',	// px
		'height'	=> '500',	// px
		'crop'		=> 1 		// true
	);

	$thumbnail = array(
		'width' 	=> '200',	// px
		'height'	=> '200',	// px
		'crop'		=> 1 		// true
	);

	// Image sizes
	update_option( 'shop_catalog_image_size', $catalog );		// Product category thumbs
	update_option( 'shop_single_image_size', $single ); 		// Single product image
	update_option( 'shop_thumbnail_image_size', $thumbnail ); 	// Image gallery thumbs
}


/* ---------------------------------------------------------------------------
 * WooCommerce - Before Main Content
 * --------------------------------------------------------------------------- */
function mfn_woocommerce_output_content_wrapper()
{
	?>
	<!-- #Content -->
	<div id="Content">
		<div class="content_wrapper clearfix">
	
			<!-- .sections_group -->
			<div class="sections_group">
				<div class="section">
					<div class="section_wrapper clearfix">
						<div class="items_group clearfix">
							<div class="column one woocommerce-content">
	<?php 
}


/* ---------------------------------------------------------------------------
 * WooCommerce - After Main Content
 * --------------------------------------------------------------------------- */
function mfn_woocommerce_output_content_wrapper_end()
{
	?>
							</div>
						</div>
					</div>
				</div>
			</div>
			
			<!-- .four-columns - sidebar -->
			<?php if( is_active_sidebar( 'shop' ) ):  ?>
				<div class="four columns">
					<div class="widget-area clearfix <?php mfn_opts_show('sidebar-lines'); ?>">
						<?php dynamic_sidebar( 'shop' ); ?>
					</div>
				</div>
			<?php endif; ?>
	
		</div>
	</div>
	<?php
}


/* ---------------------------------------------------------------------------
 *	WooCommerce - Products per line/page
 * --------------------------------------------------------------------------- */
add_filter( 'loop_shop_columns', create_function( false, 'return 3;' ) );

function mfn_woo_per_page( $cols ){
	return mfn_opts_get( 'shop-products', 12 );
}
add_filter( 'loop_shop_per_page', 'mfn_woo_per_page', 20 );


/* ---------------------------------------------------------------------------
 *	WooCommerce - Ensure cart contents update when products are added to the cart via AJAX
 * --------------------------------------------------------------------------- */
add_filter('add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment');
function woocommerce_header_add_to_cart_fragment( $fragments ) {
	global $woocommerce;
	
	$cart_icon = mfn_opts_get('shop-cart');
	if( $cart_icon == 1 ) $cart_icon = 'icon-basket'; // Be < 4.9 compatibility

	ob_start();
	echo '<a id="header_cart" href="'. $woocommerce->cart->get_cart_url() .'"><i class="'. $cart_icon .'"></i><span>'. $woocommerce->cart->cart_contents_count .'</span></a>';
	$fragments['a#header_cart'] = ob_get_clean();
	
	return $fragments;
}


?>