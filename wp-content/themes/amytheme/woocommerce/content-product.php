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

global $product, $woocommerce_loop, $ab_amy_settings, $as_hfx;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) )
	$woocommerce_loop['loop'] = 0;

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) )
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 3 );

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
	$classes[] = 'numrow'.$ab_amy_settings['woo-def-rows'];
	$classes[] = $ab_amy_settings['woo-def-color'];
	

?>


<?php
//HOVER SVG 
//=====================================================
if($ab_amy_settings['woo-hoverfx'] == "squares"){
	$as_hfx  = "M180,0v117.9V147v29.1h-60V157H60v-19.5H0V0H180z";
}else if($ab_amy_settings['woo-hoverfx'] == 'waves'){
	$as_hfx  = "M0-2h180v186.8c0,0-44,21-90-12.1c-48.8-35.1-90,12.1-90,12.1V-2z";
}else{
	$as_hfx  = "M 0 0 L 0 182 L 90 156.5 L 180 182 L 180 0 L 0 0 z ";
}
		
		//$id = get_the_ID();
	//$id = get_the_ID();
	
	$shop= get_page($id);
                $post_meta_data = get_post_custom($shop->ID);
				if(isset( $post_meta_data['custom_select_color_style'][0]))
			$ab_tf_post_colorw = $post_meta_data['custom_select_color_style'][0];
		else $ab_tf_post_colorw = "peterriver";
				//echo $post_meta_data['custom_select_color_style'][0];	
		//$post->ID = get_option('woocommerce_shop_page_id');
?>
<li <?php post_class( $classes ); ?>>
 <section class="grid clearfix  <?php echo $ab_tf_post_colorw; ?>  " >
                    <div class="layer tt-cn-style" data-depth="<?php echo $ab_amy_settings['amy-slider-parallax-depth']?>">	
	
	
	<?php
	
    
   if($ab_amy_settings['woo-thumb-style'] == 'style1'){
	  
                            get_template_part( 'inc/woo-style', '1' );	
                        }else if( $ab_amy_settings['woo-thumb-style'] == 'style2'){
                            get_template_part( 'inc/woo-style', '2' );	
                            
                        }?>

</div>
</section>


<?php  /*?><?php do_action( 'woocommerce_before_shop_loop_item' ); ?>

	<a href="<?php the_permalink(); ?>">

		<?php
			do_action( 'woocommerce_before_shop_loop_item_title' );
		?>
		<h3><?php the_title(); ?></h3>
		<?php
			do_action( 'woocommerce_after_shop_loop_item_title' );
		?>
	</a>
		<?php do_action( 'woocommerce_after_shop_loop_item' ); ?><?php */?>

</li>