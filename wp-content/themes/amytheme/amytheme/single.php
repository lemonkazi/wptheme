<?php
/*
* Theme Name: AMY Theme - Creative Multi-Purpose WordPress Theme
* Theme Author: Andrey Boyadzhiev - http://themes.cray.bg
*
* Version: 1.0 
*/
?>
<?php get_header();
global $ab_amy_settings;
?>
<div id="mainpage" <?php post_class(); ?>><?php 

	//BEGIN LOOP
	//=====================================================
	if(have_posts()) : ?><?php while(have_posts()) : the_post(); 
		$id = get_the_ID();
		$post_meta_data = get_post_custom($post->ID);
		include('inc/post-settings.php');
		
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
		}; ?>
        
		<div id='firsts' class=" scene center-content <?php echo $ab_tf_post_color; if( $ab_tf_post_header == 'headeroff'){ echo ' paddingtop';}?>"><?php
		
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
                                smoothHeight: false,
								directionNav: false, 
								controlNav: true,    
								
								controlsContainer: ".fullwidthtitle"
                            });
                        })
                    });
                </script><?php
			};
			
			//HEADER PARALLAX EFFECTS FEATURED IMAGE / VIDEO
			//=====================================================
			if(has_post_thumbnail()  && $ab_tf_post_header == 'headerfi' ) {
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
			};
			
			//TITLE
			//=====================================================
			if(apply_filters ('the_title', get_the_title()) !=''  ) {
				if($ab_tf_post_showtitle != 'hide'){?>     
					<div class="fullwidthtitle">                    	
						<h1 class="content-title"><?php the_title(); ?></h1>
					</div><?php
				};
			};
            
            //CONTENT
			//===================================================== ?>
			<div class="ss-stand-alone add-page-padding <?php if($ab_tf_show_sidebar == 'sbleft' || $ab_tf_show_sidebar == 'sbright' ){ echo ' boxedwidthrow ';}; echo $ab_tf_post_color;?>">
				<div class="ss-full">  
					<div id='tt-h-one' class="ss-row  <?php if($ab_tf_show_sidebar == 'sbleft'){ echo ' sblefton empty-right';}else if($ab_tf_show_sidebar == 'sbright'){echo ' sblefton empty-left';} ?>"><?php
						if(apply_filters( 'the_content', get_the_content()) !='' || $ab_tf_post_showtitle == 'hide' ){?>
							<div class="container-border zindex-up">
								<div class="gray-container"><?php
									the_content();
									wp_link_pages(); 
									the_tags('<div class="comments-add-c fullwidthrow"><ol class="tags"><li>', '</li><li>', '</li> </ol></div>');?>
								</div>
							</div><?php 
							comments_template();
							?></div><?php
						};
						if($ab_tf_show_sidebar == 'sbleft'){?>
							<div id='tt-h-two' class="sbleft">
							  <?php dynamic_sidebar ('blog-sidebar'); ?>
							</div><?php
						}else if($ab_tf_show_sidebar == 'sbright'){?>
							<div id='tt-h-two' class="sbright">
							  <?php dynamic_sidebar ('blog-sidebar'); ?>   
							</div><?php
						};?>
					</div>   
				</div>
			</div>  
	<?php endwhile; ?>
	<?php endif;  wp_reset_query();?>
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
<?php get_sidebar(); ?>	
<?php get_footer(); ?>

