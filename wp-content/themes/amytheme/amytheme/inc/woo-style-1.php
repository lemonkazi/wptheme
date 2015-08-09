<?php
/*
* Theme Name: AMY Theme - Creative Multi-Purpose WordPress Theme
* Theme Author: Andrey Boyadzhiev - http://themes.cray.bg
*
* Version: 1.0 
*/
?>
<?php
global $ab_amy_settings, $product, $as_hfx;
$src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), array( 340,450, ), true );
$srcf = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full', true );?>
<div class="slelement woocommerce">
	<figure>
		<img src="<?php echo $src[0]; ?>" class="clean-img"/> 
		<svg viewBox="0 0 180 250" preserveAspectRatio="none"><path d="<?php echo $as_hfx ?>"/></svg>
        <figcaption class="layer" rel="<?php the_permalink(); ?>">
            <h2 class="content-title" ><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
            <div class="hideifneed">
                <div class="ratingholder content-title woopriceh">
                    <?php echo $product->get_price_html();?>
                </div>
                <p><?php echo $product->get_categories( ', ', '<span class="posted_in">' . _n( 'Category:', 'Categories:', sizeof( get_the_terms( $post->ID, 'product_cat' ) ), 'woocommerce' ) . ' ', '.</span>' ); ?></p>
               <?php 
                echo apply_filters( 'woocommerce_loop_add_to_cart_link',
                sprintf( '<a href="%s" rel="nofollow" data-product_id="%s" data-product_sku="%s" class="%s product_type_%s"><div class="clickimg clickaddtocart"><i class="icon-shopping-cart"></i></div></a>',
                    esc_url( $product->add_to_cart_url() ),
                    esc_attr( $product->id ),
                    esc_attr( $product->get_sku() ),
                    $product->is_purchasable() ? 'add_to_cart_button' : '',
                    esc_attr( $product->product_type ),
                    esc_html( $product->add_to_cart_text() )
                    ),
                $product );?>
                <a href="<?php the_permalink(); ?>"> 
                    <div class="clicklink">
                        <div><?php the_title(); ?></div>
                    </div>
                </a>
                <a href="<?php the_permalink(); ?>">
                    <div class="clickimg"><?php 
                        if($rating_html = $product->get_rating_html()){ 
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
</div>
