<?php
/**
 * Post custom meta fields.
 *
 * @package Betheme
 * @author Muffin group
 * @link http://muffingroup.com
 */

 
/*-----------------------------------------------------------------------------------*/
/*	Define Metabox Fields
/*-----------------------------------------------------------------------------------*/

$mfn_post_meta_box = array(
	'id' => 'mfn-meta-post',
	'title' => __('Post Options','mfn-opts'),
	'page' => 'post',
	'context' => 'normal',
	'priority' => 'high',
	'fields' => array(
	
		array(
			'id' 		=> 'mfn-post-hide-content',
			'type' 		=> 'switch',
			'title'		=> __('Hide The Content', 'mfn-opts'),
			'sub_desc' 	=> __('Hide the content from the WordPress editor.', 'mfn-opts'),
			'desc' 		=> __('<strong>Turn it ON if you build content using Content Builder</strong>. Use the Content item if you want to display the Content from editor within the Content Builder.', 'mfn-opts'),
			'options' 	=> array('1' => 'On', '0' => 'Off'),
			'std' 		=> '0'
		),
		
		array(
			'id' 		=> 'mfn-post-layout',
			'type' 		=> 'radio_img',
			'title' 	=> __('Layout', 'mfn-opts'), 
			'sub_desc' 	=> __('Select layout for this post', 'mfn-opts'),
			'options' 	=> array(
				'no-sidebar' 	=> array('title' => 'Full width. No sidebar', 'img' => MFN_OPTIONS_URI.'img/1col.png'),
				'left-sidebar' 	=> array('title' => 'Left Sidebar', 'img' => MFN_OPTIONS_URI.'img/2cl.png'),
				'right-sidebar' => array('title' => 'Right Sidebar', 'img' => MFN_OPTIONS_URI.'img/2cr.png')
			),
			'std' 		=> mfn_opts_get( 'sidebar-layout' ),																		
		),
		
		array(
			'id' 		=> 'mfn-post-sidebar',
			'type' 		=> 'select',
			'title' 	=> __('Sidebar', 'mfn-opts'), 
			'sub_desc' 	=> __('Select sidebar for this post', 'mfn-opts'),
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
			'id' 		=> 'mfn-post-header-bg',
			'type' 		=> 'upload',
			'title' 	=> __('Header | Image', 'mfn-opts'),
		),
		
		array(
			'id'		=> 'mfn-post-hide-title',
			'type'		=> 'switch',
			'title'		=> __('Title Area | Hide', 'mfn-opts'),
			'options'	=> array('1' => 'On', '0' => 'Off'),
			'std'		=> '0'
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
			'id' 		=> 'mfn-post-link',
			'type' 		=> 'text',
			'title' 	=> __('External Link', 'mfn-opts'),
			'desc' 		=> __('For Post Format: Link', 'mfn-opts'),
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
function mfn_post_meta_add() {
	global $mfn_post_meta_box;
	add_meta_box($mfn_post_meta_box['id'], $mfn_post_meta_box['title'], 'mfn_post_show_box', $mfn_post_meta_box['page'], $mfn_post_meta_box['context'], $mfn_post_meta_box['priority']);
}
add_action('admin_menu', 'mfn_post_meta_add');


/*-----------------------------------------------------------------------------------*/
/*	Callback function to show fields in meta box
/*-----------------------------------------------------------------------------------*/
function mfn_post_show_box() {
	global $MFN_Options, $mfn_post_meta_box, $post;
	$MFN_Options->_enqueue();
 	
	// Use nonce for verification
	echo '<div id="mfn-wrapper">';
		echo '<input type="hidden" name="mfn_post_meta_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';
		
		mfn_builder_show();
		
		echo '<table class="form-table">';
			echo '<tbody>';
	 
				foreach ($mfn_post_meta_box['fields'] as $field) {
					$meta = get_post_meta($post->ID, $field['id'], true);
					if( ! key_exists( 'std' , $field) ) $field['std'] = false;
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
function mfn_post_save_data($post_id) {
	global $mfn_post_meta_box;
 
	// verify nonce
	if( key_exists( 'mfn_post_meta_nonce',$_POST ) ) {
		if ( ! wp_verify_nonce( $_POST['mfn_post_meta_nonce'], basename(__FILE__) ) ) {
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
 
	// check and save fields ( $mfn_page_meta_box['fields'] )
	foreach ($mfn_post_meta_box['fields'] as $field) {
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
add_action('save_post', 'mfn_post_save_data');


/*-----------------------------------------------------------------------------------*/
/*	Styles & scripts
 /*-----------------------------------------------------------------------------------*/
function mfn_post_admin_styles() {
	wp_enqueue_style( 'mfn.builder', LIBS_URI. '/css/mfn.builder.css', false, time(), 'all');
}
add_action('admin_print_styles', 'mfn_post_admin_styles');

function mfn_post_admin_scripts() {
	wp_enqueue_script( 'jquery.mfn.builder', LIBS_URI. '/js/mfn.builder.js', false, time(), true );
}
add_action('admin_print_scripts', 'mfn_post_admin_scripts');

?>