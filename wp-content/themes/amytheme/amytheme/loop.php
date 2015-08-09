<?php
/*
* Theme Name: AMY Theme - Creative Multi-Purpose WordPress Theme
* Theme Author: Andrey Boyadzhiev - http://themes.cray.bg
*
* Version: 1.0 
*/
?>
<?php
global $ab_amy_settings,$ab_amy_bgslideshow, $as_hfx;
$ab_amy_bgslideshow = 1;
if($ab_amy_settings['amy-slider-hoverfx'] == "squares"){
	$as_hfx  = "M180,0v117.9V147v29.1h-60V157H60v-19.5H0V0H180z";
}else if($ab_amy_settings['amy-slider-hoverfx'] == 'waves'){
	$as_hfx  = "M0-2h180v186.8c0,0-44,21-90-12.1c-48.8-35.1-90,12.1-90,12.1V-2z";
}else{
	$as_hfx  = "M 0 0 L 0 182 L 90 156.5 L 180 182 L 180 0 L 0 0 z ";
}
if(have_posts()) :
	$firsttime = 0;		
	while(have_posts()) : the_post();
		$id = get_the_ID();
		$post_meta_data = get_post_custom($post->ID);
		include('inc/post-settings.php');?>
			<section class="grid clearfix  <?php echo $ab_tf_post_color; ?>"><?php
				if($firsttime !=1 ){?>
					<script>
						setTimeout(window.scrollinit,300);
					</script><?php 
					$firsttime = 1; 
				};?>
				<div class="layer tt-cn-style" data-depth="<?php echo $ab_amy_settings['amy-slider-parallax-depth']?>"><?php 
					if(function_exists( 'is_woocommerce' ) && $post_type=='product' && $ab_amy_settings['woo-thumb-style'] == 'style1'){
						get_template_part( 'inc/woo-style', '1' );	
					}else if(function_exists( 'is_woocommerce' ) && $post_type=='product' && $ab_amy_settings['woo-thumb-style'] == 'style2'){
						get_template_part( 'inc/woo-style', '2' );	
						
					}else{
						get_template_part( 'inc/def-style', '1' );
					}?>
					  
				</div>
			</section>
	<?php endwhile; wp_reset_query() ?>
<?php endif;?>
