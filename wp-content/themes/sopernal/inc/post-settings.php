<?php
/*
* Theme Name: AMY Theme - Creative Multi-Purpose WordPress Theme
* Theme Author: Andrey Boyadzhiev - http://themes.cray.bg
*
* Version: 1.0 
*/
?>
<?php 
global $sopernal_settings, $ab_tf_post_showtitle, $ab_tf_post_showfbcomments, $ab_tf_post_parallax_image ,$ab_tf_post_bgimage, $ab_tf_post_img_slideshow, $ab_tf_post_embed_video_yt, $ab_tf_post_embed_video_vm, $ab_tf_show_sidebar, $ab_tf_post_img_effect, $ab_tf_post_img_sdirection, $ab_tf_post_color, $ab_tf_post_showdscomments, $ab_tf_post_content_position,  $ab_tf_post_header, $ab_tf_post_prallax_fx, $ab_tf_post_prallax_speed, $ab_tf_post_prallax_coverratio, $ab_tf_post_prallax_minheight, $ab_tf_post_prallax_extraheight, $ab_tf_post_custom_url, $ab_tf_post_prallax_zoom, $ab_tf_post_padding_top, $ab_tf_post_padding_bottom, $ab_tf_post_info;
if(isset($post_meta_data['custom_repeatable'][0]) ){
			$custom_repeatable = unserialize($post_meta_data['custom_repeatable'][0]); 
		}else{
			$custom_repeatable[0] = '';
		};
		
		if(isset( $post_meta_data['custom_select_color_style'][0]))
			$ab_tf_post_color = $post_meta_data['custom_select_color_style'][0];
		else $ab_tf_post_color = "peterriver";
	
	
	
		if(isset($post_meta_data['custom_select_show_sidebar'][0]))
			$ab_tf_show_sidebar = $post_meta_data['custom_select_show_sidebar'][0];
		else $ab_tf_show_sidebar ='hide';
		if(isset($post_meta_data['custom_select_show_title'][0]))
			$ab_tf_post_showtitle = $post_meta_data['custom_select_show_title'][0];
		else $ab_tf_post_showtitle = 'show';
		if(isset($post_meta_data['custom_select_fb_comments'][0]))
			$ab_tf_post_showfbcomments = $post_meta_data['custom_select_fb_comments'][0];
		else $ab_tf_post_showfbcomments = 'off';
		if(isset($post_meta_data['custom_image'][0]))
			$ab_tf_post_bgimage = $post_meta_data['custom_image'][0];
		else $ab_tf_post_bgimage = '';
		if(isset($post_meta_data['custom_select_img_effect'][0]))
			$ab_tf_post_img_effect = $post_meta_data['custom_select_img_effect'][0];
		else $ab_tf_post_img_effect = 'fade';
		if(isset($post_meta_data['custom_select_img_sdirection'][0]))
			$ab_tf_post_img_sdirection = $post_meta_data['custom_select_img_sdirection'][0];
		else $ab_tf_post_img_sdirection = 'horizontal';
		if(isset($post_meta_data['custom_select_img_slideshow'][0]))
			$ab_tf_post_img_slideshow = $post_meta_data['custom_select_img_slideshow'][0];
		else $ab_tf_post_img_slideshow = 'false';
		if(isset($post_meta_data['custom_embed_video_yt'][0]))
			$ab_tf_post_embed_video_yt = $post_meta_data['custom_embed_video_yt'][0];
		else $ab_tf_post_embed_video_yt = '';
		if(isset($post_meta_data['custom_embed_video_vm'][0]))
			$ab_tf_post_embed_video_vm = $post_meta_data['custom_embed_video_vm'][0];
		else $ab_tf_post_embed_video_vm = '';
		if(isset($post_meta_data['custom_select_ds_comments'][0]))
			$ab_tf_post_showdscomments = $post_meta_data['custom_select_ds_comments'][0];
		else $post_ribbon_display = 'off';
		if(isset($post_meta_data['custom_select_content_position'][0]))
			$ab_tf_post_content_position = 'center'; //$post_meta_data['custom_select_content_position'][0];
		else $ab_tf_post_content_position = 'center';
		
		if(isset($post_meta_data['custom_select_info'][0]))
			$ab_tf_post_info = $post_meta_data['custom_select_info'][0];
		else $ab_tf_post_info = 'hide';
		
		
		
		
		if(isset($post_meta_data['custom_post_padding_top'][0]))
			$ab_tf_post_padding_top = $post_meta_data['custom_post_padding_top'][0];
		else $ab_tf_post_padding_top = '';
		if(isset($post_meta_data['custom_post_padding_bottom'][0]))
			$ab_tf_post_padding_bottom = $post_meta_data['custom_post_padding_bottom'][0];
		else $ab_tf_post_padding_bottom = '';
		
		
		if(isset($post_meta_data['custom_select_header'][0]))
			$ab_tf_post_header = $post_meta_data['custom_select_header'][0];
		else $ab_tf_post_header = 'headeroff';
		if(isset($post_meta_data['custom_select_prallax_fx'][0]))
			$ab_tf_post_prallax_fx = $post_meta_data['custom_select_prallax_fx'][0];
		else $ab_tf_post_prallax_fx = 'true';
		if(isset($post_meta_data['custom_select_prallax_speed'][0]))
			$ab_tf_post_prallax_speed = $post_meta_data['custom_select_prallax_speed'][0];
		else $ab_tf_post_prallax_speed = '0.3';
		if(isset($post_meta_data['custom_select_prallax_coverratio'][0]))
			$ab_tf_post_prallax_coverratio = $post_meta_data['custom_select_prallax_coverratio'][0];
		else $ab_tf_post_prallax_coverratio = '0.75';
		if(isset($post_meta_data['custom_select_prallax_minheight'][0]))
			$ab_tf_post_prallax_minheight = $post_meta_data['custom_select_prallax_minheight'][0];
		else $ab_tf_post_prallax_minheight = '300';
		if(isset($post_meta_data['custom_select_prallax_extraheight'][0]))
			$ab_tf_post_prallax_extraheight = $post_meta_data['custom_select_prallax_extraheight'][0];
		else $ab_tf_post_prallax_extraheight = '200';
		if(isset($post_meta_data['custom_select_prallax_zoom'][0]))
			$ab_tf_post_prallax_zoom = $post_meta_data['custom_select_prallax_zoom'][0];
		else $ab_tf_post_prallax_zoom = 'true';
		if(isset($post_meta_data['custom_custom_parallax_image'][0]))
			$ab_tf_post_parallax_image = $post_meta_data['custom_custom_parallax_image'][0];
		else $ab_tf_post_parallax_image = '';
		
		if(isset($post_meta_data['custom_select_prallax_layerslider'][0]))
			$ab_tf_post_layerslider = $post_meta_data['custom_select_prallax_layerslider'][0];
		else $ab_tf_post_layerslider = '';
		
		if(isset($post_meta_data['custom_select_post_bgcolor'][0]))
			$ab_tf_post_bgcolor = $post_meta_data['custom_select_post_bgcolor'][0];
		else $ab_tf_post_bgcolor = 'slect color';
		
		if(isset($post_meta_data['custom_post_custom_url'][0]))
			$ab_tf_post_custom_url = $post_meta_data['custom_post_custom_url'][0];
		else $ab_tf_post_custom_url = '';
		
		
		
		
		?>