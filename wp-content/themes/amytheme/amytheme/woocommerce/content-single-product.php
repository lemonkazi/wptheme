<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * Override this template by copying it to yourtheme/woocommerce/content-single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
wp_reset_query();?>
<div id="mainpage"><?php 
	global $post, $product, $woocommerce_loop;
	
	$id = get_the_ID();
			$post_meta_data = get_post_custom($post->ID);
			
			include( get_template_directory() .'/inc/post-settings.php');
	//CUSTOM BACKGROUND
	//=====================================================
	if($ab_tf_post_bgimage != ''){
		$srcsliderfa = wp_get_attachment_image_src( $ab_tf_post_bgimage, 'full', true );?>
		<script>
		jQuery(document).ready( function($){
			window.hasownbg = 1;
			jQuery.vegas('stop');
			jQuery.vegas({
				src:'<?php echo  $srcsliderfa[0]; ?>', 
				fade:2000, 
				valign:'<?php echo $ab_amy_settings['bg-vposition']; ?>', 
				align:'<?php echo $ab_amy_settings['bg-hposition']; ?>' 
			
			})('overlay', {
				src:'<?php echo get_template_directory_uri(); ?>/images/overlays/<?php echo $ab_amy_settings['bg-overlays'];?>.png'
			});
		})
		</script><?php 
	};
	//HEADER PARALLAX EFFECTS
	//=====================================================
	if($ab_tf_post_parallax_image != '' && $ab_tf_post_header == 'headerbg'){
		$parallaximg = wp_get_attachment_image_src( $ab_tf_post_parallax_image,  "full-width-content", true );?>
		<div class="img-holder" data-image="<?php echo $parallaximg[0]; ?>"></div><?php 
	}else if($ab_tf_post_layerslider != '' && $ab_tf_post_header == 'headerls'){?>
		<div class="img-holder" data-image="<?php echo get_template_directory_uri(); ?>/images/empty.png" ></div>
		<div class="parallaxnorm"><?php 
			echo do_shortcode('[layerslider id="'.$ab_tf_post_layerslider.'"]'); ?>
		</div><?php 
	}
    //JAVASCRIPT FOR FLEX SLIDER AND BACKGROUND
			//=====================================================
			if($custom_repeatable[0] != ''){?>
				<script>
                    jQuery( document ).ready( function($){
                        $(window).bind("load", function() {	
                            $('#flexslider-<?php echo $id;?>').flexslider({
                                animation: "<?php echo $ab_tf_post_img_effect; ?>",
                                direction: "<?php echo $ab_tf_post_img_sdirection; ?>",
                                slideshow: <?php echo $ab_tf_post_img_slideshow; ?>,
                                smoothHeight: true,
								directionNav: false, 
								controlNav: true,    
								
								controlsContainer: ".fullwidthtitle"
                            });
                        })
                    });
                </script><?php
			};?>
    <div id='firsts' class=" scene center-content <?php echo $ab_tf_post_color; if( $ab_tf_post_header == 'headeroff'){ echo ' paddingtop';}?>"><?php
    if( $ab_tf_post_header == 'headerfi' ) {
            $src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), array( 720,305, ), true );
            $srcf = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID),'full-width-content', true );
            if($custom_repeatable[0] != ''){?>
                <div class="img-holder" data-image="<?php echo get_template_directory_uri(); ?>/images/empty.png" ></div>
                <div id="flexslider-<?php echo $id;?>" class="parallaxnorm flexslider">
                    <ul class="slides">
                        <li><?php 
                            if ($ab_tf_post_embed_video_yt !='') {?>
                                <iframe class="embedvideo"  width="100%" height="100%" src="//www.youtube.com/embed/<?php echo $ab_tf_post_embed_video_yt;?>?html5=1" frameborder="0" allowfullscreen></iframe><?php
                            }else if ($ab_tf_post_embed_video_vm !=''){?>
                                <iframe src="//player.vimeo.com/video/<?php echo $ab_tf_post_embed_video_vm;?>?title=0&amp;byline=0&amp;portrait=0"  width="100%" height="100%" class="embedvideo" frameborder="0" webkitAllowFullScreen allowFullScreen></iframe><?php
                            }else{?> 
                                <img src="<?php echo $srcf[0]; ?>" class="clean-img "/><?php 
                            }?>
                                                
                        </li><?php
                        foreach ($custom_repeatable as $string) {
                            $srcslider = wp_get_attachment_image_src( $string, "full-width-content", true );
                            $srcsliderf = wp_get_attachment_image_src( $string, 'full', true );?>
                            <li> 
                                <img src="<?php echo $srcslider[0]; ?>" class="clean-img "/> 		
                            </li><?php 
                        };?>
                    </ul>
                </div><?php
            }else{?>
                <div class="<?php if ($ab_tf_post_embed_video_yt !='' || $ab_tf_post_embed_video_vm !='') { echo 'embedvideoh';};?>"><?php
                    if ($ab_tf_post_embed_video_yt !='') {?>
                        <div class="img-holder" data-image="<?php echo get_template_directory_uri(); ?>/images/empty.png"></div>
                        <div class="parallaxdiv">
                            <iframe class="embedvideo parallaxdiv" width="100%" height="100%" src="//www.youtube.com/embed/<?php echo $ab_tf_post_embed_video_yt;?>?html5=1" frameborder="0" allowfullscreen></iframe></div><?php
                    }else if ($ab_tf_post_embed_video_vm !=''){?>
                        <div class="img-holder" data-image="<?php echo get_template_directory_uri(); ?>/images/empty.png"></div>
                        <iframe src="//player.vimeo.com/video/<?php echo $ab_tf_post_embed_video_vm;?>?title=0&amp;byline=0&amp;portrait=0" width="100%" height="100%" class="embedvideo parallaxdiv" frameborder="0" webkitAllowFullScreen allowFullScreen></iframe><?php
                    }else{?>
                        <div class="img-holder" data-image="<?php echo $srcf[0]; ?>" data-extra-height="0"></div><?php
                    };?>
                </div><?php 
            };
        };?>
			
			
			<?php if ( apply_filters( 'woocommerce_show_page_title', true ) ) : ?>
			<?php
			
			//TITLE
			//=====================================================
				if($ab_tf_post_showtitle != 'hide'){?>     
					<div class="fullwidthtitle">                    	
						<h1 class="content-title"><?php woocommerce_page_title(); ?></h1>
					</div><?php
				};
			
			?>
			
		<?php endif; ?>
			<?php
			//CONTENT
			//===================================================== ?>
			<div class="ss-stand-alone add-page-padding <?php if($ab_tf_show_sidebar == 'sbleft' || $ab_tf_show_sidebar == 'sbright' ){ echo ' boxedwidthrow ';}; echo $ab_tf_post_color;?>">
				<div class="ss-full">  
					<div id='tt-h-one' class="ss-row  <?php if($ab_tf_show_sidebar == 'sbleft'){ echo ' sblefton empty-right';}else if($ab_tf_show_sidebar == 'sbright'){echo ' sblefton empty-left';} ?>">
							<div class="container-border zindex-up vc_responsive">
								<div class="gray-container wpb_row">
                                <div class="fullwidthrow">
                                <div class="vc_span12 wpb_column column_container">
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
        
      

			

<div itemscope itemtype="<?php echo woocommerce_get_product_schema(); ?>" id="product-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php
		/**
		 * woocommerce_before_single_product_summary hook
		 *
		 * @hooked woocommerce_show_product_sale_flash - 10
		 * @hooked woocommerce_show_product_images - 20
		 */
		do_action( 'woocommerce_before_single_product_summary' );
	?>

	<div class="summary entry-summary">

		<?php
			/**
			 * woocommerce_single_product_summary hook
			 *
			 * @hooked woocommerce_template_single_title - 5
			 * @hooked woocommerce_template_single_rating - 10
			 * @hooked woocommerce_template_single_price - 10
			 * @hooked woocommerce_template_single_excerpt - 20
			 * @hooked woocommerce_template_single_add_to_cart - 30
			 * @hooked woocommerce_template_single_meta - 40
			 * @hooked woocommerce_template_single_sharing - 50
			 */
			do_action( 'woocommerce_single_product_summary' );
		?>

	</div><!-- .summary -->

	<?php
		/**
		 * woocommerce_after_single_product_summary hook
		 *
		 * @hooked woocommerce_output_product_data_tabs - 10
		 * @hooked woocommerce_output_related_products - 20
		 */
		do_action( 'woocommerce_after_single_product_summary' );
	?>

	<meta itemprop="url" content="<?php the_permalink(); ?>" />

</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action( 'woocommerce_after_single_product' ); ?>

		</div>
						</div>	</div></div><?php 
							
							
							
							
							
							
							
							
							
							
							if ( post_password_required() ) {
	return;
}
global $ab_amy_settings, $ab_tf_post_showdscomments,$ab_tf_post_showfbcomments, $tr_disqus_title, $tr_facebook_title, $ab_tf_show_sidebar, $ab_tf_post_color;

//AJAX COMMENTS FUNCTION
//=====================================================
if (comments_open()){?>
	<script>
    //Ajax comments
    //==================================================
    jQuery(document).ready(function($){
        var commentform=$('#commentform');
        commentform.prepend('<div id="comment-status" ></div>');
        var statusdiv=$('#comment-status');
         
        commentform.submit(function(){
            var formdata=commentform.serialize();
            statusdiv.html('<p><?php echo $ab_amy_settings['tr-comm-process'];?></p>');
            var formurl=commentform.attr('action');
            $.ajax({
                type: 'post',
                url: formurl,
                data: formdata,
                error: function(XMLHttpRequest, textStatus, errorThrown){
                statusdiv.html('<p><?php echo $ab_amy_settings['tr-comm-error'];?></p>');
                },
                success: function(data, textStatus){
                    if(data=="success"){
                    statusdiv.html('<p><?php echo $ab_amy_settings['tr-comm-thanks'];?></p>');
                    }else{
                    statusdiv.html('<p><?php echo $ab_amy_settings['tr-comm-thanks'];?></p>');
                    commentform.find('textarea[name=comment]').val('');
                    }
                }
            });
        return false;
        });
    });
    </script><?php 
} 

//DISQUS COMMENTS
//=====================================================
if($ab_tf_post_showdscomments == 'on' ){?>
    <div class="ss-full ss-row fb-holder ss-stand-alone disquis_h fullwidthrow">
        <div class="container-border"<?php if($ab_tf_show_sidebar != 'hide' ){ ?>class="container-border"<?php }?>>
            <div class="<?php global $ab_tf_post_color; echo $ab_tf_post_color;?>">
                <h3 class="content-title comm-title"><?php echo $ab_amy_settings['tr-disqus-title'];?></h3> 
				<div id="disqus_thread"><p></p></div>
            </div> 
        </div> 
    </div>
	<script>
		//Disqus API
		//==================================================
		jQuery(document).ready(function ($) {
			var disqus_shortname = '<?php echo $ab_amy_settings['disqus-id']; ?>'; // required: replace example with your forum shortname
			(function() {
				var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
				dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
				(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
			})();	
		});
	</script><?php
}; 

//FACEBOOK COMMENTS
//=====================================================
if($ab_tf_post_showfbcomments == 'on' ){?> 
    <div class="ss-full ss-row fb-holder ss-stand-alone fullwidthrow">
        <div class="container-border">
            <div class="gray-container <?php global $ab_tf_post_color; echo $ab_tf_post_color;?>">
                <h3 class="content-title comm-title"><?php echo $ab_amy_settings['tr-facebook-title'];?></h3>
				<div class="fb-comments" data-href="<?php the_permalink(); ?>" data-num-posts="2" data-height="250" data-width="555"  data-colorscheme="light" ></div>
            </div> 
        </div> 
    </div><?php 
};


?>				
							
							
							
							
							
							
							
							
							
							
							
							
							</div>
					</div>   
                    <?php
				
						if($ab_tf_show_sidebar == 'sbleft'){?>
							<div id='tt-h-two' class="sbleft">
							  <?php dynamic_sidebar ('woo-sidebar'); ?>
							</div><?php
						}else if($ab_tf_show_sidebar == 'sbright'){?>
							<div id='tt-h-two' class="sbright">
							  <?php dynamic_sidebar ('woo-sidebar'); ?>   
							</div><?php
						};?>
				</div>
			</div>
	</div></div>     
 <script>  
  jQuery(document).ready(function($){  $('.img-holder').imageScroll({
            container: $('#mainpage'),
            speed: <?php echo $ab_tf_post_prallax_speed ?>,
            coverRatio: <?php echo $ab_tf_post_prallax_coverratio ?>,
            holderMinHeight:<?php echo $ab_tf_post_prallax_minheight ?>,
            extraHeight: <?php echo $ab_tf_post_prallax_extraheight ?>,
			imgzoom:<?php echo $ab_tf_post_prallax_zoom ?>,
            parallax: <?php echo $ab_tf_post_prallax_fx ?>,
			<?php if($ab_amy_settings['site-width'] == "boxed"){ echo "isboxed:true";}?>
        });
});
</script>