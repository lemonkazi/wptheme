<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $woocommerce_loop;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) )
	$woocommerce_loop['loop'] = 0;

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) )
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );

// Ensure visibility
if ( ! $product || ! $product->is_visible() )
	return;

// Increase loop count
$woocommerce_loop['loop']++;

// Extra post classes
$classes = array();
if ( 0 == ( $woocommerce_loop['loop'] - 1 ) % $woocommerce_loop['columns'] || 1 == $woocommerce_loop['columns'] )
	$classes[] = 'first';
if ( 0 == $woocommerce_loop['loop'] % $woocommerce_loop['columns'] )
	$classes[] = 'last';

// Product type - Buttons ---------------------------------
if( ! $product->is_in_stock() || mfn_opts_get('shop-catalogue') || in_array( $product->product_type, array('external','grouped','variable') ) ){
	$add_to_cart = false;
	$image_frame = false;
} else {
	$add_to_cart = '<a href="'. apply_filters( 'add_to_cart_url', esc_url( $product->add_to_cart_url() ) ) .'" rel="nofollow" data-product_id="'.$product->id.'" class="add_to_cart_button product_type_'.$product->product_type.'"><i class="icon-basket"></i></a>';
	$image_frame = 'double';
}

?>
<li <?php post_class( $classes ); ?>>
	
	<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>

	<div class="image_frame scale-with-grid product-loop-thumb" ontouchstart="this.classList.toggle('hover');">
		<div class="image_wrapper">
		
			<a href="<?php the_permalink(); ?>">
				<div class="mask"></div>
				<?php the_post_thumbnail( 'shop_catalog', array( 'class' => 'scale-with-grid' ) ) ?>
			</a>
			
			<div class="image_links <?php echo $image_frame; ?>">
				<?php echo $add_to_cart; ?>
				<a class="link" href="<?php the_permalink(); ?>"><i class="icon-link"></i></a>
			</div>

		</div>
		
		<?php if ($product->is_on_sale()) echo '<span class="onsale"><i class="icon-star"></i></span>'; ?>
		<a href="<?php the_permalink(); ?>"><span class="product-loading-icon added-cart"></span></a>
			
	</div>

	<div class="desc">
	
		<?php
			/**
			 * woocommerce_before_shop_loop_item_title hook
			 *
			 * @hooked woocommerce_show_product_loop_sale_flash - 10
			 * @hooked woocommerce_template_loop_product_thumbnail - 10
			 */
// 			do_action( 'woocommerce_before_shop_loop_item_title' );
		?>
		
		<h4><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h4>
		
		<?php
			/**
			 * woocommerce_after_shop_loop_item_title hook
			 *
			 * @hooked woocommerce_template_loop_rating - 5
			 * @hooked woocommerce_template_loop_price - 10
			 */
			do_action( 'woocommerce_after_shop_loop_item_title' );
		?>
		
	</div>
	
	<?php // do_action( 'woocommerce_after_shop_loop_item' ); // add to cart button ?>

</li>