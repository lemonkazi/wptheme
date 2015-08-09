<?php
/*
* Theme Name: AMY Theme - Creative Multi-Purpose WordPress Theme
* Theme Author: Andrey Boyadzhiev / http://themes.cray.bg
*
* Version: 1.0 
*/
?>

<?php
global $ab_amy_settings, $woocommerce, $wooisactive, $ab_tf_post_info;

//CONVERT SIDEBAR TO STRING
//=====================================================
ob_start();
dynamic_sidebar('archive-time');
$sidebar = ob_get_contents();
ob_end_clean();

//FOOTER
//=====================================================?>
<div id="footer" class="bottomnavanim <?php echo $ab_amy_settings['body-color-scheme'];?> <?php if($ab_amy_settings['footer-position'] == "absolute"){ echo "absolutefooter";}?> <?php if($ab_amy_settings['footer-width'] == "boxed"){ echo "boxedstyle";}?>"><?php 
	if(function_exists('bcn_display')){?>
		<div class="breadcrumbs animated fadeOutH"><?php
			bcn_display();?>
		</div><?php
	}
	
	//FOOTER NAVIGATION (SLIDER / NEX PREV POST)
	//=====================================================?>
	<div class="right-bottom-nav  <?php echo $ab_amy_settings['body-color-scheme'];?>">
		<div class="tt-bottom-nav noborder animated fadeOutH" <?php if(!is_single() && !is_page()) {?> data-ot='<?php echo $ab_amy_settings['tr-nav-tooltip'];?>'  data-ot-fixed="true"  data-ot-background="rgba(255,255,255,0.95)" data-ot-border-color="rgba(255,255,255,1)" data-ot-offset="[ 0, -10 ]" data-ot-auto-offset="true" data-ot-tip-joint="bottom center" data-ot-target="true" data-ot-border-radius="0" data-ot-group="rightnav"<?php } ?>><?php 
			if(is_single()) {
				if($post->post_type=='post' || $post->post_type=='portfolio'){	
					$nextPost = get_next_post();
					$prevPost = get_previous_post();				
					if (!empty( $nextPost )){ 
						$large_image_url_n = wp_get_attachment_image_src( get_post_thumbnail_id($nextPost->ID), array(90,90));
						$nepoid = $nextPost->ID;
						$next_post_url = get_permalink($nepoid);
						$post = get_post( $nepoid );
						$infoteksta = ( $post->post_excerpt ) ? $post->post_excerpt : $post->post_content;
						$next_post_excerpt = substr( $infoteksta,0,50)."...";
						wp_reset_postdata();	
					}
					if (!empty( $prevPost )){ 	
						$large_image_url_p = wp_get_attachment_image_src( get_post_thumbnail_id($prevPost->ID), array(90,90));
						$prpoid = $prevPost->ID;
						$prev_post_url = get_permalink($prpoid);
						$post = get_post( $prpoid );
						$infoteksta = ( $post->post_excerpt ) ? $post->post_excerpt : $post->post_content;
						$prev_post_excerpt = substr( $infoteksta,0,50)."...";
						wp_reset_postdata();
					}
			
					if(!empty( $prevPost )){ ?>
						<a class="navposts <?php if (empty( $large_image_url_p[0] )){ echo 'navpostnoimg';}?>" href=" <?php echo $prev_post_url;?>" ><?php 
							$rmnext ="";
							if($large_image_url_p[0]){
								$rmnext ="<img  class='tipimg' src='".$large_image_url_p[0]."'/>";
							};?>
							<div data-ot='<div class="prevpnav-tip"><?php echo htmlentities($rmnext, ENT_QUOTES, "UTF-8");?><div class="tiptitle"><?php previous_post_link(' %link ');?></div><?php echo "<br>".$prev_post_excerpt;?></div>'   data-ot-fixed="true"  data-ot-background="rgba(255,255,255,0.95)" data-ot-border-color="rgba(255,255,255,1)" data-ot-offset="[ 16, -22 ]" data-ot-auto-offset="false" data-ot-stem-length="12" data-ot-tip-joint="bottom center" data-ot-target="true" data-ot-border-radius="0" data-ot-group="rightnav">
								<i id="prev-arrow" class="icon-chevron-right navkey"></i> 
							</div>
						</a><?php 
					};
					if(!empty( $nextPost )){ ?>
						<a class="navposts <?php if (empty( $large_image_url_n[0] )){ ?> navpostnoimg <?php }?>" href=" <?php echo $next_post_url;?>" ><?php 
							$rmnext ="";
							if($large_image_url_n[0]){
								$rmnext ="<img  class='tipimg'  src='".$large_image_url_n[0]."'/>";
							};?>
							<div data-ot='<div class="prevpnav-tip"><?php echo htmlentities($rmnext, ENT_QUOTES, "UTF-8");?><div class="tiptitle"><?php next_post_link(' %link ');?></div><?php echo "<br>".$next_post_excerpt;?>&nbsp;</div>'  data-ot-fixed="flase"  data-ot-background="rgba(255,255,255,0.9)" data-ot-border-color="rgba(255,255,255,1)" data-ot-auto-offset="true" data-ot-stem-length="12" data-ot-offset="[ 16, -22 ]" data-ot-tip-joint="bottom center" data-ot-target="true" data-ot-border-radius="0" data-ot-group="rightnav">
								<i id="next-arrow" class="icon-chevron-left navkey" ></i> 
							</div>
						</a><?php 
					};
			
				}
			}else{
				if(!is_page()){?>
				<i id="prev-arrow" class="icon-chevron-right navkey "></i>
				<i id="next-arrow" class="icon-chevron-left navkey" ></i><?php 
			}
			}?>
		</div><?php 
		$category = get_the_category();
		if(function_exists( 'is_woocommerce' ) && is_woocommerce() ){
		$terms = get_the_terms( $post->ID, 'product_cat' );
		$category='';
		foreach ($terms as $term) {
			$category.= $term->name.', ';
			//break;
			if($category==""){
				$category="";
			}
		} 
		}
		if(is_single() && $ab_tf_post_info != 'hide' || is_page() && $ab_tf_post_info != 'hide') { 
			
			//FOOTER COMMENTS INFO
			//=====================================================                                                   
			if (comments_open() && $ab_tf_post_info !='hide'){?>
				<div class="tt-bottom-nav animated fadeOutH" data-ot='<?php comments_number( '0 comments', '1 comment', '% comments' ); ?>' data-ot-fixed="true" data-ot-background="rgba(255,255,255,1)" data-ot-border-color="rgba(255,255,255,1)" data-ot-offset="[0, -22 ]" data-ot-auto-offset="true" data-ot-tip-joint="bottom center" data-ot-class-name='<?php echo $ab_tf_post_color; ?>' data-ot-target="true" data-ot-group="rightnav" data-ot-border-radius="0" data-ot-stem-length="12">
					<a  href="<?php comments_link(); ?>"  class="read-more-init voice-readcomments">
						<i class="icon-comments navkey"></i> 
					</a>
				</div><?php
			};
			
			//FOOTER AUTHOR INFO
			//=====================================================?>
			<div class="tt-bottom-nav animated fadeOutH " data-ot=' <?php the_author(); ?>'  data-ot-fixed="true"  data-ot-background="rgba(255,255,255,1)" data-ot-border-color="rgba(255,255,255,1)" data-ot-offset="[ 0, -22]" data-ot-auto-offset="true" data-ot-tip-joint="bottom center" data-ot-group="rightnav" data-ot-target="true" data-ot-border-radius="0" data-ot-stem-length="12">
				<a href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>">
					<i class="icon-user navkey"></i> 
				</a>
			</div><?php 
			
			//FOOTER CATEGORY INFO
			//=====================================================
			if(is_single()) {?>
                <div class="tt-bottom-nav animated fadeOutH " data-ot='<?php if(function_exists( 'is_woocommerce' )&& is_woocommerce()){ echo $category;}else{if(isset($category[0]->cat_name)){ echo $category[0]->cat_name;}else{echo 'no category';}}?>'  data-ot-fixed="true"  data-ot-background="rgba(255,255,255,1)" data-ot-border-color="rgba(255,255,255,1)" data-ot-offset="[0, -22 ]" data-ot-auto-offset="true" data-ot-tip-joint="bottom center"  data-ot-group="rightnav" data-ot-target="true" data-ot-border-radius="0" data-ot-stem-length="12"> 
                    <a class="read-more-init voice-morefromthis"  href="<?php echo get_category_link( $category[0]->term_id );?>">
                        <i class="icon-tag navkey"></i>
                    </a>
                </div><?php 
			}
			
			//FOOTER DATE INFO
			//=====================================================?>                                               
			<div class="tt-bottom-nav animated fadeOutH " data-ot='<?php echo get_the_date('d,F Y'); ?>'  data-ot-fixed="true"  data-ot-background="rgba(255,255,255,1)" data-ot-border-color="rgba(255,255,255,1)" data-ot-offset="[ 0, -22 ]" data-ot-auto-offset="true" data-ot-tip-joint="bottom center" data-ot-group="rightnav" data-ot-target="true" data-ot-border-radius="0" data-ot-stem-length="12"> 
				<a class="read-more-init voice-morefromthis"  href="<?php  if(is_single()) { echo get_category_link( $category[0]->term_id );};?>">
					<i class="icon-time navkey"></i> 
				</a>
			</div><?php
		}
		
		//FOOTER WOOCOMMERCE CART
		//=====================================================
		if(function_exists( 'is_woocommerce' ) && $ab_amy_settings['woo-footer-cart'] == true ) {?>   
			<div id='davidim' class="date-time woocart animated fadeOutH " data-ot='<h4><a href="#"><?php echo $ab_amy_settings['tr-woo-cart-title'];?></a></h4><div class="widget woocommerce widget_shopping_cart"><div class="vc-info woocommerce widget_shopping_cart_content"><?php echo woocommerce_get_template( 'cart/mini-cart.php','widget woocommerce widget_shopping_cart');?></div></div>'  data-ot-fixed="true" data-ot-hide-trigger="closeButton" data-ot-class-name='<?php echo $ab_amy_settings['body-color-scheme'];?>'   data-ot-background="rgba(255,255,255,0.95)" data-ot-border-color="rgba(255,255,255,1)" data-ot-offset="[ 0, -22 ]" data-ot-close-button-radius="8" data-ot-close-button-cross-size="5"  data-ot-close-button-cross-color="#777" data-ot-auto-offset="true" data-ot-tip-joint="bottom center" data-ot-target="true" data-ot-stem-length="12" data-ot-border-radius="0" <?php if($ab_tf_post_info !='hide'){ echo 'data-ot-contain-in-viewport="false"';}else{echo 'data-ot-contain-in-viewport="true"';}?> data-ot-group="rightnav">
			
          
			<?php 
				global $woocommerce;
				$cart_url = $woocommerce->cart->get_cart_url();	
				$shop_page_url = get_permalink( woocommerce_get_page_id( 'shop' ) );
				$cart_contents_count = $woocommerce->cart->cart_contents_count;
				$cart_contents = sprintf(_n('%d', '%d', $cart_contents_count, 'your-theme-slug'), $cart_contents_count);
				$cart_contentssub = sprintf(_n($ab_amy_settings['tr-woo-1cart'], $ab_amy_settings['tr-woo-2cart'], $cart_contents_count, 'your-theme-slug'), $cart_contents_count);
				$cart_total = $woocommerce->cart->get_cart_total();?>
				<div class="cartrebuild">   
					<div class="tt-b-day">             
						<i class="icon-shopping-cart"> </i> 
						<span><?php if (class_exists('Woocommerce')) { echo sprintf(_n('%d', '%d', $woocommerce->cart->cart_contents_count, 'amytheme'), $woocommerce->cart->cart_contents_count);}?></span>
					</div>
					<div class="tt-b-day-r">
						<div class="tt-b-month"><?php echo $cart_contentssub;?></div>
						<div class="tt-b-date"><?php echo $cart_total;?></div>
					</div>
			   </div>
			</div><?php 
		}
		
		//FOOTER CALENDAR INFO
		//=====================================================
		if($ab_amy_settings['footer-date'] == true){?>
			<div id="footer-time" class="date-time animated fadeOutH" data-ot='<?php echo htmlentities($sidebar, ENT_QUOTES, "UTF-8"); ?>' data-ot-fixed="true" data-ot-hide-trigger="closeButton" data-ot-background="rgba(255,255,255,0.95)" data-ot-border-color="rgba(255,255,255,1)" data-ot-offset="[ 0, -22 ]" data-ot-auto-offset="true" data-ot-tip-joint="bottom center" data-ot-target="true" data-ot-border-radius="0" data-ot-close-button-radius="8" data-ot-close-button-cross-size="5"  data-ot-close-button-cross-color="#777" data-ot-group="rightnav" data-ot-contain-in-viewport="false" data-ot-stem-length="12">
				<div class="tt-b-day <?php if(is_single() || is_page()){?>rem-border<?php } ?> "></div>
				<div class="tt-b-day-r">
					<div class="tt-b-month"></div>
					<div class="tt-b-date" ></div>
				</div>
			</div><?php 
		} ?>
		<div class="inifiniteLoader animated">
			<div class="loading"></div>
		</div><?php
		
		//FOOTER NUMBER OF POSTS INFO
		//=====================================================?>
		<div class="animated numpostinfi"><?php 
			if ( is_home()  ){?>
				<div class="numpostcontent"><?php 
					echo "<div class='tt-big-dig'>".$wp_query->found_posts."</div> <div class='tt-dig-txt' >".$ab_amy_settings['tr-footer-home-info']."</div>"; ?>
				</div><?php
			}else if(is_search()){?>
				<div class="numpostcontent"><?php
					echo "<div class='tt-big-dig'>".$wp_query->found_posts."</div> <div class='tt-dig-txt'>".$ab_amy_settings['tr-footer-search-info']."</div>"; ?>
				</div><?php
			}else if(is_archive()){?>
				<div class="numpostcontent"><?php
					$cat_obj = $wp_query->get_queried_object();
					if(is_category()){
						$category = $cat_obj->cat_name; 
						$infoend = $ab_amy_settings['tr-footer-category-info'].' '.$category;
					}else{
						$infoend = $ab_amy_settings['tr-footer-archive-info'];
					}
					echo "<div class='tt-big-dig'>".$wp_query->found_posts."</div> <div class='tt-dig-txt'>".$infoend."</div>"; ?>
				</div><?php 
			}?>
		</div>
	</div><?php 
	
	//FOOTER SOC ICONS
	//=====================================================?>
    <div class="socicons animated fadeOutH">
    <?php //echo do_shortcode('[ssba]');?>
		<?php  if($ab_amy_settings['yt-link-url'] !=''){ ?>
            <a href="<?php echo $ab_amy_settings['yt-link-url']; ?>" target="_blank"><i class="icon-youtube navkey"></i></a>	
        <?php }?>
        <?php  if($ab_amy_settings['pi-link-url'] !=''){ ?>
            <a href="<?php echo $ab_amy_settings['pi-link-url']; ?>" target="_blank"><i class="icon-pinterest navkey"></i></a>	
        <?php }?>
        <?php  if($ab_amy_settings['gp-link-url'] !=''){ ?>
            <a href="<?php echo $ab_amy_settings['gp-link-url']; ?>" target="_blank"><i class="icon-google-plus navkey"></i></a> 
        <?php }?>
        <?php  if($ab_amy_settings['tw-link-url'] !=''){ ?>
            <a href="<?php echo $ab_amy_settings['tw-link-url']; ?>" target="_blank"><i class="icon-twitter navkey"></i></a> 
        <?php }?>
        <?php  if($ab_amy_settings['fb-link-url'] !=''){ ?>
            <a href="<?php echo $ab_amy_settings['fb-link-url']; ?>" target="_blank"><i class="icon-facebook navkey"></i></a>
        <?php }?>
	</div><?php 
	if($ab_amy_settings['footer-text'] !=''){ ?>
        <div class="copyrholder animated fadeOutH">
            <?php echo $ab_amy_settings['footer-text']; ?> 
        </div><?php
	};
	 
	//FOOTER WIDGETS
	//=====================================================?> 
    <div class="footerwidget">
        <div class="wpb_row vc_row-fluid ss-stand-alone"><?php 
			if($ab_amy_settings['footer-layout'] == 5){?>
                <div class="vc_span4 wpb_column column_container ccscroll">
                    <?php dynamic_sidebar('footer-1'); ?>
                </div>
                <div class="vc_span4 wpb_column column_container ccscroll">
                    <?php dynamic_sidebar('footer-2'); ?>
                </div>
                <div class="vc_span4 wpb_column column_container ccscroll">
                    <?php dynamic_sidebar('footer-3'); ?>
                </div><?php 
			}else if($ab_amy_settings['footer-layout'] == 4){?>
                <div class="vc_span8 wpb_column column_container ccscroll">
                    <?php dynamic_sidebar('footer-1'); ?>
                </div>
                <div class="vc_span4 wpb_column column_container ccscroll">
                    <?php dynamic_sidebar('footer-2'); ?>
                </div><?php 
			}else if($ab_amy_settings['footer-layout'] == 3){?>
                <div class="vc_span6 wpb_column column_container ccscroll">
                    <?php dynamic_sidebar('footer-1'); ?>
                </div>
                <div class="vc_span6 wpb_column column_container ccscroll">
                    <?php dynamic_sidebar('footer-2'); ?>
                </div><?php
			}else if($ab_amy_settings['footer-layout'] == 2){?>
                <div class="vc_span4 wpb_column column_container ccscroll">
                    <?php dynamic_sidebar('footer-1'); ?>
                </div>
                <div class="vc_span8 wpb_column column_container ccscroll">
                    <?php dynamic_sidebar('footer-2'); ?>
                </div><?php 
			}else{?>
				<div class="vc_span12 wpb_column column_container ccscroll">
					<?php dynamic_sidebar('footer-1'); ?>
				</div><?php 
			}?>
		</div> 
	</div>   
</div>
</div>
</div>
<div class="cn-overlay"></div><?php 
	if($ab_amy_settings['footer-position'] != "absolute"){?>
		<div id="dl-menu" class="dl-menuwrapper animated fadeOutH <?php if($ab_amy_settings['footer-position'] == "absolute"){ echo "absolutefooter";}?>">
			<button id="widgetfooter">Open Menu</button>
		</div><?php 
	}?>
<?php include('inc/java-fun.php');?>            
<?php wp_footer();?>
</body>
</html>