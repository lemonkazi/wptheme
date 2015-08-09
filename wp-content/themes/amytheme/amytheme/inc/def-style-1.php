<?php
/*
* Theme Name: AMY Theme - Creative Multi-Purpose WordPress Theme
* Theme Author: Andrey Boyadzhiev - http://themes.cray.bg
*
* Version: 1.0 
*/
?>
<?php
global $ab_amy_settings, $product, $as_hfx, $ab_tf_post_custom_url;
$linktofull = '...';
if(has_post_thumbnail()){
$src = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), array( 340,450, ), true );
$srcf = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID), 'full', true );
}else{
	$src[0] = get_template_directory_uri().'/images/empty.png';
	$srcf[0] =get_template_directory_uri().'/images/empty.png';
}?>
<div class="slelement">
	<figure>
		<img src="<?php echo $src[0]; ?>" class="clean-img"/> 
		<svg viewBox="0 0 180 280" preserveAspectRatio="none"><path d="<?php echo $as_hfx ?>"/></svg>
        <figcaption class="layer" rel="<?php the_permalink(); ?>"><?php 
			if(apply_filters ('the_title', get_the_title()) !='') {?>
				<h2 class="content-title" ><a href="<?php if($ab_tf_post_custom_url !=''){echo $ab_tf_post_custom_url;}else{ the_permalink(); }?>"><?php the_title(); ?></a></h2><?php
			};?>
            <div class="hideifneed"><?php
				if(get_the_excerpt() !="" && get_the_excerpt() !=" " ){
					echo '<p>'.substr( get_the_excerpt(),0,$ab_amy_settings['amy-slider-excerpt']).$linktofull.'</p>';
                }?>
			   	<a href="<?php if($ab_tf_post_custom_url !=''){echo $ab_tf_post_custom_url;}else{ the_permalink(); }?>"> 
					<div class="clicklink">
						<i class="icon-link"></i>
					</div>
				</a>
                <a href="<?php echo $srcf[0]; ?>"  alt="<?php if (isset($attachment->post_title)){echo $attachment->post_title;} ?>" rel="prettyPhotoImages[<?php echo $id; ?>]">
					<div class="clickimg">
						<i class="icon-search"></i>
					</div>
				</a>
            </div>
        </figcaption>            
	</figure>
</div>
