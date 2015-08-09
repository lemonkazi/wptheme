<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

get_header( 'shop' ); ?>

<div id="mainpage"><?php 
	global $post, $product, $woocommerce_loop, $as_hfx;
	//HOVER SVG 
//=====================================================


	$post_meta_data = get_post_custom(get_option('woocommerce_shop_page_id'));
	include( get_template_directory() .'/inc/post-settings.php');
	//$post->ID = get_option('woocommerce_shop_page_id');
	//wp_reset_query();
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
	}?>
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
							<div class="container-border zindex-up">
								<div class="gray-container">
			
	<?php
	
		/**
		 * woocommerce_before_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
		do_action( 'woocommerce_before_main_content' );
		
	?>

		
	<div class="fullwidthrow">
		<?php do_action( 'woocommerce_archive_description' ); ?>

		<?php if ( have_posts() ) : ?>
		
			<?php
				/**
				 * woocommerce_before_shop_loop hook
				 *
				 * @hooked woocommerce_result_count - 20
				 * @hooked woocommerce_catalog_ordering - 30
				 */
				do_action( 'woocommerce_before_shop_loop' );
			?>

			<?php woocommerce_product_loop_start(); ?>

				<?php woocommerce_product_subcategories(); ?>

				<?php while ( have_posts() ) : the_post(); ?>

					<?php wc_get_template_part( 'content', 'product' ); ?>

				<?php endwhile; // end of the loop. ?>

			<?php woocommerce_product_loop_end(); ?>

			<?php
				/**
				 * woocommerce_after_shop_loop hook
				 *
				 * @hooked woocommerce_pagination - 10
				 */
				do_action( 'woocommerce_after_shop_loop' );
			?>

		<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

			<?php wc_get_template( 'loop/no-products-found.php' ); ?>
		
		<?php endif; ?>

	<?php
		/**
		 * woocommerce_after_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action( 'woocommerce_after_main_content' );
	?>

	<?php
		/**
		 * woocommerce_sidebar hook
		 *
		 * @hooked woocommerce_get_sidebar - 10
		 */
		do_action( 'woocommerce_sidebar' );
	?>
   								</div>
							</div></div><?php 
							
							
							
							
							
							
							
							
							
							
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

//WORDPRESS COMMENTS
//=====================================================
if ( have_comments() && comments_open() ) : ?>
	<div id="comments" class="comments-area ss-full fullwidthrow">
		<h2 class="content-title comm-title"><?php
			printf( _n( '%1 '.$ab_amy_settings['tr-comm-1comm'].' &ldquo;%2$s&rdquo;', '%1$s '.$ab_amy_settings['tr-comm-2comm'].' &ldquo;%2$s&rdquo;', get_comments_number(), 'twentyfourteen' ), number_format_i18n( get_comments_number() ), get_the_title() );?>
		</h2><?php 
		if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) {?>
			<nav id="comment-nav-above" class="navigation comment-navigation" role="navigation">
				<h1 class="screen-reader-text"><?php _e( 'Comment navigation', 'twentyfourteen' ); ?></h1>
				<div class="nav-previous"><?php previous_comments_link( __( $ab_amy_settings['tr-comm-oldcomm'], 'twentyfourteen' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( __( $ab_amy_settings['tr-comm-newcomm'], 'twentyfourteen' ) ); ?></div>
			</nav><?php 
		};?>
		<ol class="comment-list"><?php
			wp_list_comments('type=comment&callback=theme_comment&avatar_size=60');?>
		</ol><?php 
		if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ){?>
            <nav id="comment-nav-below" class="navigation comment-navigation" role="navigation">
                <h1 class="screen-reader-text"><?php _e( 'Comment navigation', 'twentyfourteen' ); ?></h1>
                <div class="nav-previous"><?php previous_comments_link( __( $ab_amy_settings['tr-comm-oldcomm'], 'twentyfourteen' ) ); ?></div>
                <div class="nav-next"><?php next_comments_link( __(  $ab_amy_settings['tr-comm-newcomm'], 'twentyfourteen' ) ); ?></div>
            </nav><?php 
		};
		if ( ! comments_open() ) { ?>
			<p class="no-comments"><?php _e( 'Comments are closed.', 'twentyfourteen' ); ?></p><?php 
		};
endif;
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
	</div>
</div>
    
    
   
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
<?php get_footer( 'shop' ); ?>

