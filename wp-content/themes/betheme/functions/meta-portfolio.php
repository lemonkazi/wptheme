<?php
/**
 * Portfolio custom meta fields.
 *
 * @package Betheme
 * @author Muffin group
 * @link http://muffingroup.com
 */

/* ---------------------------------------------------------------------------
 * Create new post type
 * --------------------------------------------------------------------------- */
function mfn_portfolio_post_type() 
{
	$portfolio_item_slug = mfn_opts_get( 'portfolio-slug', 'portfolio-item' );
	
	$labels = array(
		'name' 					=> __('Portfolio','mfn-opts'),
		'singular_name' 		=> __('Portfolio item','mfn-opts'),
		'add_new' 				=> __('Add New','mfn-opts'),
		'add_new_item' 			=> __('Add New Portfolio item','mfn-opts'),
		'edit_item' 			=> __('Edit Portfolio item','mfn-opts'),
		'new_item' 				=> __('New Portfolio item','mfn-opts'),
		'view_item' 			=> __('View Portfolio item','mfn-opts'),
		'search_items' 			=> __('Search Portfolio items','mfn-opts'),
		'not_found' 			=> __('No portfolio items found','mfn-opts'),
		'not_found_in_trash' 	=> __('No portfolio items found in Trash','mfn-opts'), 
		'parent_item_colon' 	=> ''
	  );
		
	$args = array(
		'labels' 				=> $labels,
		'menu_icon'				=> 'dashicons-portfolio',
		'public' 				=> true,
		'publicly_queryable' 	=> true,
		'show_ui' 				=> true, 
		'query_var' 			=> true,
		'capability_type' 		=> 'post',
		'hierarchical' 			=> false,
		'menu_position' 		=> null,
		'rewrite' 				=> array( 'slug' => $portfolio_item_slug, 'with_front' => true ),
		'supports' 				=> array( 'title', 'editor', 'author', 'excerpt', 'thumbnail', 'page-attributes' ),
	); 
	  
	register_post_type( 'portfolio', $args );
	  
	register_taxonomy( 'portfolio-types', 'portfolio', array(
		'hierarchical' 			=> true, 
		'label' 				=>  __( 'Portfolio categories', 'mfn-opts' ), 
		'singular_label' 		=>  __( 'Portfolio category', 'mfn-opts' ), 
		'rewrite'				=> true,
		'query_var' 			=> true
	));
}
add_action( 'init', 'mfn_portfolio_post_type' );


/* ---------------------------------------------------------------------------
 * Edit columns
 * --------------------------------------------------------------------------- */
function mfn_portfolio_edit_columns($columns)
{
	$newcolumns = array(
		"cb" 					=> "<input type=\"checkbox\" />",
		"portfolio_thumbnail" 	=> __('Thumbnail','mfn-opts'),
		"title" 				=> 'Title',
		"portfolio_types" 		=> __('Categories','mfn-opts'),
		"portfolio_order" 		=> __('Order','mfn-opts'),
	);
	$columns = array_merge($newcolumns, $columns);	
	
	return $columns;
}
add_filter("manage_edit-portfolio_columns", "mfn_portfolio_edit_columns");  


/* ---------------------------------------------------------------------------
 * Custom columns
 * --------------------------------------------------------------------------- */
function mfn_portfolio_custom_columns($column)
{
	global $post;
	switch ($column)
	{
		case "portfolio_thumbnail":
			if ( has_post_thumbnail() ) { the_post_thumbnail('50x50'); }
			break;
		case "portfolio_types":
			echo get_the_term_list($post->ID, 'portfolio-types', '', ', ','');
			break;
		case "portfolio_order":
			echo $post->menu_order;
			break;		
	}
}
add_action("manage_posts_custom_column",  "mfn_portfolio_custom_columns"); 


/*-----------------------------------------------------------------------------------*/
/*	Define Metabox Fields
/*-----------------------------------------------------------------------------------*/

$mfn_portfolio_meta_box = array(
	'id' => 'mfn-meta-portfolio',
	'title' => __('Portfolio Item Options','mfn-opts'),
	'page' => 'portfolio',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(

		array(
			'id' 		=> 'mfn-post-hide-content',
			'type' 		=> 'switch',
			'title' 	=> __('Hide the content', 'mfn-opts'),
			'sub_desc' 	=> __('Hide the content from the WordPress editor', 'mfn-opts'),
			'desc' 		=> __('<strong>Turn it ON if you build content using Content Builder</strong>. Use the Content item if you want to display the Content from editor within the Content Builder.', 'mfn-opts'),
			'options' 	=> array('1' => 'On', '0' => 'Off'),
			'std' 		=> '0'
		),
			
		array(
			'id' 		=> 'mfn-post-layout',
			'type' 		=> 'radio_img',
			'title' 	=> __('Layout', 'mfn-opts'), 
			'sub_desc' 	=> __('Select layout for this portfolio item', 'mfn-opts'),
			'options' 	=> array(
				'no-sidebar' 	=> array('title' => 'Full width. No sidebar', 'img' => MFN_OPTIONS_URI.'img/1col.png'),
				'left-sidebar' 	=> array('title' => 'Left Sidebar', 'img' => MFN_OPTIONS_URI.'img/2cl.png'),
				'right-sidebar'	=> array('title' => 'Right Sidebar', 'img' => MFN_OPTIONS_URI.'img/2cr.png')
			),
			'std' 		=> mfn_opts_get( 'sidebar-layout' ),																		
		),
		
		array(
			'id' 		=> 'mfn-post-sidebar',
			'type' 		=> 'select',
			'title' 	=> __('Sidebar', 'mfn-opts'), 
			'sub_desc' 	=> __('Select sidebar for this portfolio item', 'mfn-opts'),
			'desc' 		=> __('Shows only if layout with sidebar is selected.', 'mfn-opts'),
			'options' 	=> mfn_opts_get( 'sidebars' ),
		),
		
		array(
			'id' 		=> 'mfn-post-slider',
			'type' 		=> 'select',
			'title' 	=> __('Slider | Revolution Slider', 'mfn-opts'), 
			'sub_desc' 	=> __('Select slider for this page.', 'mfn-opts'),
			'desc' 		=> __('Select one from the list of available <a target="_blank" href="admin.php?page=revslider">Revolution Sliders</a>', 'mfn-opts'),
			'options' 	=> mfn_get_sliders(),
		),
		
		array(
			'id' 		=> 'mfn-post-slider-layer',
			'type' 		=> 'select',
			'title' 	=> __('Slider | Layer Slider', 'mfn-opts'), 
			'sub_desc' 	=> __('Select slider for this page.', 'mfn-opts'),
			'desc' 		=> __('Select one from the list of available <a target="_blank" href="admin.php?page=layerslider">Layer Sliders</a>', 'mfn-opts'),
			'options' 	=> mfn_get_sliders_layer(),
		),
			
		array(
			'id' 		=> 'mfn-post-slider-header',
			'type' 		=> 'switch',
			'title' 	=> __('Slider | Show in Header', 'mfn-opts'),
			'sub_desc' 	=> __('Show slider in Header instead of the Content', 'mfn-opts'),
			'options' 	=> array( '1' => 'On', '0' => 'Off' ),
			'std' 		=> '0'
		),
		
		array(
			'id' 		=> 'mfn-post-header-bg',
			'type' 		=> 'upload',
			'title' 	=> __('Header | Image', 'mfn-opts'),
		),
		
		array(
			'id' 		=> 'mfn-post-video',
			'type' 		=> 'text',
			'title' 	=> __('Video ID', 'mfn-opts'),
			'sub_desc' 	=> __('YouTube or Vimeo', 'mfn-opts'),
			'desc' 		=> __('It`s placed in every YouTube & Vimeo video, for example:<br /><br /><b>YouTube:</b> http://www.youtube.com/watch?v=<u>WoJhnRczeNg</u><br /><b>Vimeo:</b> http://vimeo.com/<u>62954028</u>', 'mfn-opts'),
			'class' 	=> 'small-text mfn-post-format video'
		),
		
		array(
			'id'		=> 'mfn-post-video-mp4',
			'type'		=> 'upload',
			'title'		=> __('HTML5 mp4 video', 'mfn-opts'),
			'sub_desc'	=> __('m4v [.mp4]', 'mfn-opts'),
			'desc'		=> __('<strong>Notice:</strong> HTML5 video works only in moden browsers.', 'mfn-opts'),
			'class'		=> __('video', 'mfn-opts'),
		),
			
		array(
			'id' 		=> 'mfn-post-bg',
			'type' 		=> 'upload',
			'title' 	=> __('Background Image', 'mfn-opts'),
			'sub_desc' 	=> __('Background Image for List Style Portfolio', 'mfn-opts'),
		),
			
		array(
			'id' 		=> 'mfn-post-size',
			'type' 		=> 'select',
			'title' 	=> __('Item Size', 'mfn-opts'),
			'sub_desc' 	=> __('Size for Masonry Flat Style Portfolio', 'mfn-opts'),
			'options' 	=> array(
				''			=> __('Default','mfn-opts'),
				'wide'		=> __('Wide','mfn-opts'),
				'tall'		=> __('Tall','mfn-opts'),
				'wide tall'	=> __('Big','mfn-opts'),
			),
		),
			
		array(
			'id' 		=> 'mfn-post-client',
			'type' 		=> 'text',
			'title' 	=> __('Client', 'mfn-opts'),
			'sub_desc' 	=> __('Project description: Client', 'mfn-opts'),
		),
			
		array(
			'id' 		=> 'mfn-post-link',
			'type' 		=> 'text',
			'title' 	=> __('Website', 'mfn-opts'),
			'sub_desc' 	=> __('Project description: Website', 'mfn-opts'),
		),
			
		array(
			'id' 		=> 'mfn-post-task',
			'type' 		=> 'text',
			'title' 	=> __('Task', 'mfn-opts'),
			'sub_desc' 	=> __('Project description: Task', 'mfn-opts'),
		),
			
		array(
			'id' 		=> 'mfn-meta-seo-title',
			'type' 		=> 'text',
			'title' 	=> __('SEO Title', 'mfn-opts'),
			'desc' 		=> __('These settings overriddes theme options settings.', 'mfn-opts'),
		),
		
		array(
			'id' 		=> 'mfn-meta-seo-description',
			'type' 		=> 'text',
			'title' 	=> __('SEO Description', 'mfn-opts'),
			'desc' 		=> __('These settings overriddes theme options settings.', 'mfn-opts'),
		),
		
		array(
			'id' 		=> 'mfn-meta-seo-keywords',
			'type' 		=> 'text',
			'title' 	=> __('SEO Keywords', 'mfn-opts'),
			'desc' 		=> __('These settings overriddes theme options settings.', 'mfn-opts'),
		),

	),
);


/*-----------------------------------------------------------------------------------*/
/*	Add metabox to edit page
/*-----------------------------------------------------------------------------------*/ 
function mfn_portfolio_meta_add() {
	global $mfn_portfolio_meta_box;
	add_meta_box($mfn_portfolio_meta_box['id'], $mfn_portfolio_meta_box['title'], 'mfn_portfolio_show_box', $mfn_portfolio_meta_box['page'], $mfn_portfolio_meta_box['context'], $mfn_portfolio_meta_box['priority']);
}
add_action('admin_menu', 'mfn_portfolio_meta_add');


/*-----------------------------------------------------------------------------------*/
/*	Callback function to show fields in meta box
/*-----------------------------------------------------------------------------------*/
function mfn_portfolio_show_box() {
	global $MFN_Options, $mfn_portfolio_meta_box, $post;
	$MFN_Options->_enqueue();
 	
	// Use nonce for verification
	echo '<div id="mfn-wrapper">';
		echo '<input type="hidden" name="mfn_portfolio_meta_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
		
		mfn_builder_show();
		
		echo '<table class="form-table">';
			echo '<tbody>';
	 
				foreach ($mfn_portfolio_meta_box['fields'] as $field) {
					$meta = get_post_meta($post->ID, $field['id'], true);
					if( ! key_exists('std', $field) ) $field['std'] = false;
					$meta = ( $meta || $meta==='0' ) ? $meta : stripslashes(htmlspecialchars(( $field['std']), ENT_QUOTES ));
					mfn_meta_field_input( $field, $meta );
				}
	 
			echo '</tbody>';
		echo '</table>';
	echo '</div>';
}


/*-----------------------------------------------------------------------------------*/
/*	Save data when post is edited
/*-----------------------------------------------------------------------------------*/
function mfn_portfolio_save_data($post_id) {
	global $mfn_portfolio_meta_box;
 
	// verify nonce
	if( key_exists( 'mfn_portfolio_meta_nonce',$_POST ) ) {
		if ( ! wp_verify_nonce( $_POST['mfn_portfolio_meta_nonce'], basename(__FILE__) ) ) {
			return $post_id;
		}
	}
 
	// check autosave
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return $post_id;
	}
 
	// check permissions
	if ( (key_exists('post_type', $_POST)) && ('page' == $_POST['post_type']) ) {
		if (!current_user_can('edit_page', $post_id)) {
			return $post_id;
		}
	} elseif (!current_user_can('edit_post', $post_id)) {
		return $post_id;
	}
	
	mfn_builder_save($post_id);
 
	foreach ($mfn_portfolio_meta_box['fields'] as $field) {
		$old = get_post_meta($post_id, $field['id'], true);
		if( key_exists($field['id'], $_POST) ) {
			$new = $_POST[$field['id']];
		} else {
//			$new = ""; // problem with "quick edit"
			continue;
		}
 
		if ( isset($new) && $new != $old) {
			update_post_meta($post_id, $field['id'], $new);
		} elseif ('' == $new && $old) {
			delete_post_meta($post_id, $field['id'], $old);
		}
	}
}
add_action('save_post', 'mfn_portfolio_save_data');


/*-----------------------------------------------------------------------------------*/
/*	Styles & scripts
 /*-----------------------------------------------------------------------------------*/
function mfn_portfolio_admin_styles() {
	wp_enqueue_style( 'mfn.builder', LIBS_URI. '/css/mfn.builder.css', false, time(), 'all');
}
add_action('admin_print_styles', 'mfn_portfolio_admin_styles');

function mfn_portfolio_admin_scripts() {
	wp_enqueue_script( 'jquery.mfn.builder', LIBS_URI. '/js/mfn.builder.js', false, time(), true );
}
add_action('admin_print_scripts', 'mfn_portfolio_admin_scripts');

?>