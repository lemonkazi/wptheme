<?php
/*
* Theme Name: AMY Theme - Creative Multi-Purpose WordPress Theme
* Theme Author: Andrey Boyadzhiev - http://themes.cray.bg
*
* Version: 1.0 
*/
?>
<?php get_header();
global $ab_amy_settings,$ab_amy_bgslideshow, $as_hfx;
$ab_amy_bgslideshow = 1;

//HOVER SVG 
//=====================================================
if($ab_amy_settings['amy-slider-hoverfx'] == "squares"){
	$as_hfx  = "M180,0v117.9V147v29.1h-60V157H60v-19.5H0V0H180z";
}else if($ab_amy_settings['amy-slider-hoverfx'] == 'waves'){
	$as_hfx  = "M0-2h180v186.8c0,0-44,21-90-12.1c-48.8-35.1-90,12.1-90,12.1V-2z";
}else{
	$as_hfx  = "M 0 0 L 0 182 L 90 156.5 L 180 182 L 180 0 L 0 0 z ";
}

//CUSTOM HOME QUERY
//=====================================================
if(!is_archive() && !is_search()){
	global $ab_amy_settings, $query_string, $paged;
	if($ab_amy_settings['order-posts'] == "2" ){
		$order_posts = 'ASC';
	}else{
		$order_posts = 'DESC';
	}
	if($ab_amy_settings['amy-slider-post-type'] !=''){
		$post_type = $ab_amy_settings['amy-slider-post-type'];
	}else{
		$post_type = 'post';
	}
	if(isset($ab_amy_settings['amy-slider-cat']) && $ab_amy_settings['amy-slider-cat']!=''){
		$catarray = $ab_amy_settings['amy-slider-cat'];
		$arrlength=count($catarray);
		$cat = '';
		for($i=0; $i<$arrlength; $i++) {
			$cat .= $catarray[$i].", " ;
	}
}else{
	$cat = '';
}
if ( is_home()  ){
	query_posts( $query_string . '&post_type='.$post_type.'&order='.$order_posts.'&cat='.$cat.'&paged='.$paged );

	 $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
	}
};
	
//CUSTOM HOME QUERY
//=====================================================
if(have_posts()){?> 
    <div id="main" <?php post_class(); ?>><?php
	
		//AMY SLIDER 
		//=====================================================?>
        <article id="articlehold" class="scene <?php  echo $ab_amy_settings['amy-slider-style'] ?> <?php echo $ab_amy_settings['amy-slider-color'] ?> <?php if($ab_amy_settings['footer-position'] == "absolute"){ echo "absolutefooter";}?>"><?php 
            if(have_posts()) : while ( have_posts() ) : the_post();
                $id = get_the_ID();
                $post_meta_data = get_post_custom($post->ID);
                include('inc/post-settings.php');?>
                <section class="grid clearfix  <?php echo $ab_tf_post_color; ?> " >
                    <div class="layer tt-cn-style" data-depth="<?php echo $ab_amy_settings['amy-slider-parallax-depth']?>"><?php 
						//LOAD ONE OF THE SLIDER TILE TEMPLATES (you can find them @ inc folder)
						//=====================================================
                        if(function_exists( 'is_woocommerce' ) && $post_type=='product' && $ab_amy_settings['woo-thumb-style'] == 'style1'){
                            get_template_part( 'inc/woo-style', '1' );	
                        }else if(function_exists( 'is_woocommerce' ) && $post_type=='product' && $ab_amy_settings['woo-thumb-style'] == 'style2'){
                            get_template_part( 'inc/woo-style', '2' );	
                            
                        }else{
                            get_template_part( 'inc/def-style', '1' );
                        }?>
                    </div>
                </section><?php 
            endwhile;
            endif;
            add_editor_style();?>	
        </article><?php 
		ab_tf_t_pagination($pages = '', $range = 2); ?>
    </div>
    <?php 
	
	//AMY SLIDER JS CORE FILE
	//=====================================================
    include('inc/amy-slider.php');
}else{
	
	//404 ERROR PAGE
	//=====================================================
	get_template_part( '404' );
}
get_footer(); ?>