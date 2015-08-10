<?php
//$block = $block_data[0];
//$settings = $block_data[1];
global $ab_amy_settings;
	
if(function_exists( 'is_woocommerce' ) && $post->post_type=='product' && $post->as_woo_style == 'style1'){
	global $ab_amy_settings, $product;?>
	<div class="slelement woocommerce">
		<figure>
			<?php echo $post->thumbnail ?> 
			<svg viewBox="0 0 180 280" preserveAspectRatio="none"><path d="<?php echo $as_hfx ?>"/></svg>
			<figcaption class="layer" rel="<?php the_permalink(); ?>">
				<h2 class="content-title" ><a href="<?php echo $post->the_permalink; ?>"><?php echo $post->title; ?></a></h2> 
				<div class="hideifneed">
					<div class="ratingholder content-title woopriceh">
						<?php echo $post->get_price_html ?>
					</div>
					<p><?php echo $post->get_categories; ?></p>
					<?php 
					echo apply_filters( 'woocommerce_loop_add_to_cart_link',
					sprintf( '<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" class="%s product_type_%s"><div class="clickimg clickaddtocart"><i class="icon-shopping-cart"></i></div></a>',
						esc_url( $post->add_to_cart_url ),
						esc_attr( $post->id ),
						esc_attr( $post->get_sku ),
						$post->is_purchasable ? 'add_to_cart_button' : '',
						esc_attr( $post->product_type ),
						esc_html( $post->add_to_cart_text )
						),
					$product );?>
					<a href="<?php echo $post->the_permalink; ?>"> 
						<div class="clicklink">
							<div><?php echo $post->title ?></div>
						</div>
					</a>
					<a href="<?php echo $post->the_permalink; ?>">
						<div class="clickimg"><?php 
							if($rating_html = $post->get_rating_html){ 
								echo $rating_html;
							}else{?>
								<span class="star-rating" title="Rated 0.00 out of 0">
									<span class="no-rating"></span>
								</span><?php 
							}?>
						</div>
					</a>
				</div>
			</figcaption>            
		</figure>
	</div><?php
}else if(function_exists( 'is_woocommerce' ) && $post->post_type=='product' && $post->as_woo_style == 'style2'){
	global $ab_amy_settings, $product;?>
	<div class="slelement woocommerce">
		<figure class="nobgcolor">
			<?php echo $post->thumbnail ?> 
		
			<figcaption class="layer" rel="<?php the_permalink(); ?>">
				<div class="hideifneed">
					<?php 
					echo apply_filters( 'woocommerce_loop_add_to_cart_link',
					sprintf( '<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" class="%s product_type_%s"><div class="clickimg clickaddtocart"><i class="icon-shopping-cart"></i></div></a>',
						esc_url( $post->add_to_cart_url ),
						esc_attr( $post->id ),
						esc_attr( $post->get_sku ),
						$post->is_purchasable ? 'add_to_cart_button' : '',
						esc_attr( $post->product_type ),
						esc_html( $post->add_to_cart_text )
						),
					$product );?>
					<a href="<?php echo $post->the_permalink; ?>"> 
						<div class="clicklink">
							<div><?php echo $post->title ?></div>
						</div>
					</a>
					
                    <a href="<?php echo $post->the_permalink; ?>">
					<div class="clickimg">
						<?php echo $post->get_price_html; ?>
					</div>
					</a>
				</div>
			</figcaption>            
		</figure>
	</div><?php
	
}else if($post->as_woo_style == 'style3'){
	
}else{
	global $ab_amy_settings, $product, $ab_tf_post_custom_url;
	$linktofull = '...';?>
	<div class="slelement" data-path-hover="<?php echo $as_hfxh ?>">
		<figure>
			<?php echo $post->thumbnail ?>
			<svg viewBox="0 0 180 280" preserveAspectRatio="none"><path d="<?php echo $as_hfx;?>"/></svg>
			<figcaption class="layer" rel="<?php the_permalink(); ?>"> 
					<h2 class="content-title" ><?php echo  $post->title ?></h2>
				<div class="hideifneed"><?php
				echo substr( !empty($settings[0]) && $settings[0]==='text' ?  $post->content : $post->excerpt ,0,$ab_amy_settings['amy-slider-excerpt']);
					?></p>
					<a href="<?php if($post->custom_url !=''){echo $post->custom_url;}else{ echo $post->link; }?>"> 
						<div class="clicklink">
							<i class="icon-link"></i>
						</div>
					</a>
					<a  href="<?php echo $post->image_link; ?>"  alt="<?php if (isset($attachment->post_title)){echo $attachment->post_title;} ?>" rel="prettyPhotoImages[<?php echo $post->id; ?>]">
						<div class="clickimg">
							<i class="icon-search"></i>
						</div>
					</a>
				</div>
			</figcaption>            
		</figure>
	</div><?php

}?>

                    