<?php
/**
 * General custom meta fields.
 *
 * @package Betheme
 * @author Muffin group
 * @link http://muffingroup.com
 */


/*-----------------------------------------------------------------------------------*/
/*	LIST of Categories for posts or specified taxonomy
/*-----------------------------------------------------------------------------------*/
function mfn_get_categories( $category ) {
	$categories = get_categories( array( 'taxonomy' => $category ));
	
	$array = array( '' => __( 'All', 'mfn-opts' ) );	
	foreach( $categories as $cat ){
		if( is_object($cat) ) $array[$cat->slug] = $cat->name;
	}
		
	return $array;
}


/*-----------------------------------------------------------------------------------*/
/*	LIST of Sliders (Revolution Slider)
/*-----------------------------------------------------------------------------------*/
function mfn_get_sliders() {
	global $wpdb;

	$sliders = array( 0 => __('-- Select --', 'mfn-opts') );

	// Revolution Slider ----------------------------------
	if( function_exists( 'rev_slider_shortcode' ) ){
		
		$table_name = $wpdb->base_prefix . "revslider_sliders";
		
		$array = $wpdb->get_results("SELECT * FROM $table_name ORDER BY title ASC");
		
		if( is_array( $array ) ){
			foreach( $array as $v ){
				$sliders[$v->alias] = $v->title;
			}
		}
	}
	
	return $sliders;
}


/*-----------------------------------------------------------------------------------*/
/*	LIST of Sliders (Layer Slider)
/*-----------------------------------------------------------------------------------*/
function mfn_get_sliders_layer() {
	global $wpdb;

	$sliders = array( 0 => __('-- Select --', 'mfn-opts') );

	// Layer Slider ----------------------------------
	if( function_exists( 'layerslider' ) ){
		
		$table_name = $wpdb->base_prefix . "layerslider";
		
		$array = $wpdb->get_results("SELECT * FROM $table_name WHERE flag_hidden = '0' AND flag_deleted = '0' ORDER BY name ASC");
		
		if( is_array( $array ) ){
			foreach( $array as $v ){
				$sliders[$v->id] = $v->name;
			}
		}
	}
	
	return $sliders;
}


/*-----------------------------------------------------------------------------------*/
/*	PRINT Single Muffin Options FIELD
/*-----------------------------------------------------------------------------------*/
function mfn_meta_field_input( $field, $meta ){
	global $MFN_Options;

	if( isset( $field['type'] ) ){		
		echo '<tr valign="top">';
		
			// Field Title & SubDescription
			echo '<th scope="row">';
				if( key_exists('title', $field) ) echo $field['title'];
				if( key_exists('sub_desc', $field) ) echo '<span class="description">'. $field['sub_desc'] .'</span>';
			echo '</th>';
			
			// Muffin Options Field & Description 
			echo '<td>';
				$field_class = 'MFN_Options_'.$field['type'];
				require_once( $MFN_Options->dir.'fields/'.$field['type'].'/field_'.$field['type'].'.php' );
				
				if( class_exists( $field_class ) ){
					$field_object = new $field_class( $field, $meta );
					$field_object->render( true );
				}
				
			echo '</td>';
			
		echo '</tr>';
	}
	
}


/*-----------------------------------------------------------------------------------*/
/*	PRINT Single Muffin Builder ITEM
/*-----------------------------------------------------------------------------------*/
function mfn_builder_item( $item_std, $item = false, $section_id = false ) {
	
	// input's 'name' only for existing items, not for items to clone
	$name_type 		= $item ? 'name="mfn-item-type[]"' : '';
	$name_size 		= $item ? 'name="mfn-item-size[]"' : '';
	$name_parent	= $item ? 'name="mfn-item-parent[]"' : '';
	
	$item_std['size'] = $item['size'] ? $item['size'] : $item_std['size'];
	$label = ( $item && key_exists('fields', $item) && key_exists('title', $item['fields']) ) ? $item['fields']['title'] : '';

	$classes = array(
		'1/6' => 'mfn-item-1-6',
		'1/5' => 'mfn-item-1-5',
		'1/4' => 'mfn-item-1-4',
		'1/3' => 'mfn-item-1-3',
		'1/2' => 'mfn-item-1-2',
		'2/3' => 'mfn-item-2-3',
		'3/4' => 'mfn-item-3-4',
		'1/1' => 'mfn-item-1-1'
	);
	
	echo '<div class="mfn-element mfn-item mfn-item-'. $item_std['type'] .' '. $classes[$item_std['size']] .'">';
							
		echo '<div class="mfn-element-content">';
			echo '<input type="hidden" class="mfn-item-type" '. $name_type .' value="'. $item_std['type'] .'">';
			echo '<input type="hidden" class="mfn-item-size" '. $name_size .' value="'. $item_std['size'] .'">';
			echo '<input type="hidden" class="mfn-item-parent" '. $name_parent .' value="'. $section_id .'" />';
			
			echo '<div class="mfn-element-header">';
				echo '<div class="mfn-item-size">';
					echo '<a class="mfn-element-btn mfn-item-size-dec" href="javascript:void(0);">-</a>';
					echo '<a class="mfn-element-btn mfn-item-size-inc" href="javascript:void(0);">+</a>';
					echo '<span class="mfn-item-desc">'. $item_std['size'] .'</span>';
				echo '</div>';
				echo '<div class="mfn-element-tools">';
					echo '<a class="mfn-element-btn mfn-fr mfn-element-edit" title="Edit" href="javascript:void(0);">E</a>';
					echo '<a class="mfn-element-btn mfn-fr mfn-element-clone mfn-item-clone" title="Clone" href="javascript:void(0);">C</a>';
					echo '<a class="mfn-element-btn mfn-fr mfn-element-delete" title="Delete" href="javascript:void(0);">D</a>';
				echo '</div>';
			echo '</div>';
			
			echo '<div class="mfn-item-content">';
				echo '<div class="mfn-item-icon"></div>';
				echo '<span class="mfn-item-title">'. $item_std['title'] .'</span>';
				echo '<span class="mfn-item-label">'. $label .'</span>';
			echo '</div>';
	
		echo '</div>';
		
		echo '<div class="mfn-element-meta">';
			echo '<table class="form-table">';
				echo '<tbody>';		
		 
					// Fields for Item
					foreach( $item_std['fields'] as $field ){
							
						// values for existing items
						if( $item && key_exists( 'fields', $item ) && key_exists( $field['id'], $item['fields'] ) ){
							$meta = $item['fields'][$field['id']];
						} else {
							$meta = false;
						}
						
						if( ! key_exists('std', $field) ) $field['std'] = false;
						$meta = ( $meta || $meta==='0' ) ? $meta : stripslashes(htmlspecialchars(( $field['std']), ENT_QUOTES ));
						
						// field ID
						$field['id'] = 'mfn-items['. $item_std['type'] .']['. $field['id'] .']';	
						
						// field ID except accordion, faq & tabs
						if( $field['type'] != 'tabs' ){
							$field['id'] .= '[]';					
						}
						
						// PRINT Single Muffin Options FIELD
						mfn_meta_field_input( $field, $meta );
						
					}
		 
				echo '</tbody>';
			echo '</table>';
		echo '</div>';
	
	echo '</div>';						
}


/*-----------------------------------------------------------------------------------*/
/*	PRINT Single Muffin Builder SECTION
/*-----------------------------------------------------------------------------------*/
function mfn_builder_section( $item_std, $section_std, $section = false, $section_id = false ) {

	// input's 'name' only for existing sections, not for section to clone
	$name_row_id = $section ? 'name="mfn-row-id[]"' : '';
	$label = ( $section && key_exists('attr', $section) && key_exists('title', $section['attr']) ) ? $section['attr']['title'] : '';
		
	echo '<div class="mfn-element mfn-row">';

		echo '<div class="mfn-element-content">';
		
			// Section ID
			echo '<input type="hidden" class="mfn-row-id" '. $name_row_id .' value="'. $section_id .'" />';

			echo '<div class="mfn-element-header">';
				echo '<div class="mfn-item-add">';
				echo '<a class="mfn-item-add-btn" href="javascript:void(0);">Add Item</a>';
					echo '<ul class="mfn-item-add-list">';
					
						// List of available Items
						foreach( $item_std as $item ){
							echo '<li><a class="'. $item['type'] .'" href="javascript:void(0);">'. $item['title'] .'</a></li>';
						}
					
					echo '</ul>';
				echo '</div>';
				echo '<span class="mfn-item-label">'. $label .'</span>';
				echo '<div class="mfn-element-tools">';			
					echo '<a class="mfn-element-btn mfn-element-edit" title="Edit" href="javascript:void(0);">E</a>';
					echo '<a class="mfn-element-btn mfn-element-clone mfn-row-clone" title="Clone" href="javascript:void(0);">C</a>';
					echo '<a class="mfn-element-btn mfn-element-delete" title="Delete" href="javascript:void(0);">D</a>';
				echo '</div>';
			echo '</div>';
			
			// .mfn-element-droppable
			echo '<div class="mfn-droppable mfn-sortable clearfix">';

				// Existing Items for Section
				if( $section && key_exists('items', $section) && is_array($section['items']) ){
					foreach( $section['items'] as $item )
					{
						mfn_builder_item( $item_std[$item['type']], $item, $section_id );
					}
				}
		
			echo '</div>';

		echo '</div>';
		
		echo '<div class="mfn-element-meta">';
			echo '<table class="form-table" style="display: table;">';
				echo '<tbody>';
					
					// Fields for Section
					foreach( $section_std as $field ){

						// values for existing sections
						if( $section ){
							$meta = $section['attr'][$field['id']];
						} else {
							$meta = false;
						}
					
						if( ! key_exists('std', $field) ) $field['std'] = false;
						$meta = ( $meta || $meta==='0' ) ? $meta : stripslashes(htmlspecialchars(( $field['std']), ENT_QUOTES ));
					
						// field ID
						$field['id'] = 'mfn-rows['. $field['id'] .']';
					
						// field ID except accordion, faq & tabs
						if( $field['type'] != 'tabs' ){
							$field['id'] .= '[]';
						}
					
						// PRINT Single Muffin Options FIELD
						mfn_meta_field_input( $field, $meta );
						
					}
					
				echo '</tbody>';
			echo '</table>';
		echo '</div>';
		
	echo '</div>';

}


/*-----------------------------------------------------------------------------------*/
/*	PRINT Muffin Builder
/*-----------------------------------------------------------------------------------*/
function mfn_builder_show() {
	global $post;


	// Entrance Animations -----------------------------------------------------------------------------
	$mfn_animate = array(
		'' 					=> '- Not Animated -',
		'fadeIn' 			=> 'Fade In',
		'fadeInUp' 			=> 'Fade In Up',
		'fadeInDown' 		=> 'Fade In Down ',
		'fadeInLeft' 		=> 'Fade In Left',
		'fadeInRight' 		=> 'Fade In Right ',
		'fadeInUpLarge' 	=> 'Fade In Up Large',
		'fadeInDownLarge' 	=> 'Fade In Down Large',
		'fadeInLeftLarge' 	=> 'Fade In Left Large',
		'fadeInRightLarge' 	=> 'Fade In Right Large',
		'zoomIn' 			=> 'Zoom In',
		'zoomInUp' 			=> 'Zoom In Up',
		'zoomInDown' 		=> 'Zoom In Down',
		'zoomInLeft' 		=> 'Zoom In Left',
		'zoomInRight' 		=> 'Zoom In Right',
		'zoomInUpLarge' 	=> 'Zoom In Up Large',
		'zoomInDownLarge' 	=> 'Zoom In Down Large',
		'zoomInLeftLarge' 	=> 'Zoom In Left Large',
		'bounceIn' 			=> 'Bounce In',
		'bounceInUp' 		=> 'Bounce In Up',
		'bounceInDown' 		=> 'Bounce In Down',
		'bounceInLeft' 		=> 'Bounce In Left',
		'bounceInRight' 	=> 'Bounce In Right',
	);
	
	// Default Fields for Section -----------------------------------------------------------------------------
	$mfn_std_section = array(
	
		array(
			'id' 		=> 'title',
			'type' 		=> 'text',
			'title' 	=> __('Title', 'mfn-opts'),
			'desc' 		=> __('This field is used as an Section Label in admin panel only and shows after page update.', 'mfn-opts'),
		),
			
		array(
			'id'		=> 'bg_image',
			'type'		=> 'upload',
			'title'		=> __('Background Image', 'mfn-opts'),
		),
			
		array(
			'id' 		=> 'bg_position',
			'type' 		=> 'select',
			'title' 	=> __('Background Image position', 'mfn-opts'),
			'desc' 		=> __('This option can be used only with your custom image selected above.', 'mfn-opts'),
			'options' 	=> mfna_bg_position(),
			'std' 		=> 'center top no-repeat',
		),
			
		array(
			'id' 		=> 'bg_color',
			'type' 		=> 'text',
			'title' 	=> __('Background Color', 'mfn-opts'),
			'desc' 		=> __('Use color name (eg. "gray") or hex (eg. "#808080").<br /><br />Leave this field blank if you want to use transparent background.', 'mfn-opts'),
			'class' 	=> 'small-text',
			'std' 		=> '',
		),
		
		array(
			'id' 		=> 'divider',
			'type' 		=> 'select',
			'title' 	=> __('Separator', 'mfn-opts'),
			'sub_desc'	=> __('Section Separator', 'mfn-opts'),
			'desc' 		=> __('Works only with Background Color selected above.', 'mfn-opts'),
			'options' 	=> array(
				'' 						=> 'None',
				'circle up' 			=> 'Circle Up',
				'circle down' 			=> 'Circle Down',
				'square up' 			=> 'Square Up',
				'square down' 			=> 'Square Down',
				'triangle up' 			=> 'Triangle Up',
				'triangle down' 		=> 'Triangle Down',
				'triple-triangle up' 	=> 'Triple Triangle Up',
				'triple-triangle down' 	=> 'Triple Triangle Down',
			),
		),
		
		array(
			'id'		=> 'bg_video_mp4',
			'type'		=> 'upload',
			'title'		=> __('HTML5 mp4 video', 'mfn-opts'),
			'sub_desc'	=> __('m4v [.mp4]', 'mfn-opts'),
			'desc'		=> __('Please add both mp4 and ogv for cross-browser compatibility. Background Image will be used as video placeholder before video loads and on mobile devices.', 'mfn-opts'),
			'class'		=> __('video', 'mfn-opts'),
		),
		
		array(
			'id'		=> 'bg_video_ogv',
			'type'		=> 'upload',
			'title'		=> __('HTML5 ogv video', 'mfn-opts'),
			'sub_desc'	=> __('ogg [.ogv]', 'mfn-opts'),
			'class'		=> __('video', 'mfn-opts'),
		),
		
		array(
			'id' 		=> 'layout',
			'type' 		=> 'select',
			'title' 	=> __('Layout', 'mfn-opts'),
			'sub_desc'	=> __('Select layout for this section', 'mfn-opts'),
			'desc' 		=> __('<strong>Notice:</strong> Sidebar for section will show <strong>only</strong> if you set Full Width Page Layout in Page Options below Content Builder.', 'mfn-opts'),
			'options' 	=> array(
				'no-sidebar'	=> 'Full width. No sidebar',
				'left-sidebar'	=> 'Left Sidebar',
				'right-sidebar'	=> 'Right Sidebar'
			),
			'std' 		=> 'no-sidebar',
		),
		
		array(
			'id'		=> 'sidebar',
			'type' 		=> 'select',
			'title' 	=> __('Sidebar', 'mfn-opts'),
			'sub_desc' 	=> __('Select sidebar for this section', 'mfn-opts'),
			'options' 	=> mfn_opts_get( 'sidebars' ),
		),
		
		array(
			'id' 		=> 'padding_top',
			'type'		=> 'text',
			'title' 	=> __('Padding Top', 'mfn-opts'),
			'sub_desc'	=> __('Section Padding Top', 'mfn-opts'),
			'desc' 		=> __('px', 'mfn-opts'),
			'class' 	=> 'small-text',
			'std' 		=> '0',
		),
		
		array(
			'id' 		=> 'padding_bottom',
			'type'		=> 'text',
			'title' 	=> __('Padding Bottom', 'mfn-opts'),
			'sub_desc'	=> __('Section Padding Bottom', 'mfn-opts'),
			'desc' 		=> __('px', 'mfn-opts'),
			'class' 	=> 'small-text',
			'std' 		=> '0',
		),
		
		array(
			'id' 		=> 'style',
			'type' 		=> 'select',
			'title' 	=> __('Style', 'mfn-opts'),
			'sub_desc'	=> __('Predefined styles for section', 'mfn-opts'),
			'desc' 		=> __('For more advanced styles please use Custom CSS field below.', 'mfn-opts'),
			'options' 	=> array(
				'' 					=> 'Default',
				'dark' 				=> 'Dark | .dark',
				'highlight-left' 	=> 'Highlight Left | .highlight-left',
				'highlight-right' 	=> 'Highlight Right | .highlight-right',
				'full-width'	 	=> 'Full Width | .full-width',
				'full-screen'	 	=> 'Full Screen | .full-screen',
				'no-margin'	 		=> 'Columns without vertical margin | .no-margin',
			),
		),
		
		array(
			'id' 		=> 'navigation',
			'type' 		=> 'select',
			'title' 	=> __('Navigation', 'mfn-opts'),
			'options' 	=> array(
				'' 					=> 'None',
				'arrows' 			=> 'Arrows',
			),
		),

		array(
			'id' 		=> 'class',
			'type' 		=> 'text',
			'title' 	=> __('Custom CSS classes', 'mfn-opts'),
			'desc'		=> __('Multiple classes should be separated with SPACE.<br />For sections with centered text you can use class: <strong>center</strong>', 'mfn-opts'),
		),
		
		array(
			'id' 		=> 'section_id',
			'type' 		=> 'text',
			'title' 	=> __('Custom ID', 'mfn-opts'),
			'desc'		=> __('Use this option to create One Page sites.<br /><br />For example: Your Custom ID is <strong>offer</strong> and you want to open this section, please use link: <strong>your-url/#offer-2</strong>', 'mfn-opts'),
			'class' 	=> 'small-text',
		),
		
		array(
			'id' 		=> 'visibility',
			'type' 		=> 'select',
			'title' 	=> __('Responsive Visibility', 'mfn-opts'),
			'options' 	=> array(
				'' 							=> 'Default',
				'hide-desktop' 				=> 'Hide on Desktop',		// 960 +
				'hide-tablet' 				=> 'Hide on Tablet',		// 768 - 959
				'hide-mobile' 				=> 'Hide on Mobile',		// - 768
				'hide-desktop hide-tablet' 	=> 'Hide on Desktop & Tablet',
				'hide-desktop hide-mobile' 	=> 'Hide on Desktop & Mobile',
				'hide-tablet hide-mobile'	=> 'Hide on Tablet & Mobile',
			),
		),
				
	);
	
	// Default Items with Fields -----------------------------------------------------------------------------
	$mfn_std_items = array(
	
		// Accordion  --------------------------------------------
		'accordion' => array(
			'type' 		=> 'accordion',
			'title' 	=> __('Accordion', 'mfn-opts'), 
			'size' 		=> '1/4', 
			'fields'	=> array(
		
				array(
					'id' 		=> 'title',
					'type' 		=> 'text',
					'title' 	=> __('Title', 'mfn-opts'),
				),
					
				array(
					'id' 		=> 'tabs',
					'type' 		=> 'tabs',
					'title' 	=> __('Accordion', 'mfn-opts'),
					'sub_desc' 	=> __('Manage accordion tabs.', 'mfn-opts'),
					'desc' 		=> __('You can use Drag & Drop to set the order.', 'mfn-opts'),
				),
					
				array(
					'id' 		=> 'open1st',
					'type' 		=> 'select',
					'title' 	=> __('Open First', 'mfn-opts'),
					'desc' 		=> __('Open first tab at start.', 'mfn-opts'),
					'options'	=> array( 0 => 'No', 1 => 'Yes' ),
				),
					
				array(
					'id' 		=> 'openAll',
					'type' 		=> 'select',
					'options' 	=> array( 0 => 'No', 1 => 'Yes' ),
					'title' 	=> __('Open All', 'mfn-opts'),
					'desc' 		=> __('Open all tabs at start', 'mfn-opts'),
				),
					
				array(
					'id' 		=> 'style',
					'type' 		=> 'select',
					'title' 	=> __('Style', 'mfn-opts'),
					'options'	=> array( 
						'accordion'	=> 'Accordion',
						'toggle'	=> 'Toggle'
					),
				),
				
			),															
		),
			
		// Article box  --------------------------------------------
		'article_box' => array(
			'type'		=> 'article_box',
			'title'		=> __('Article box', 'mfn-opts'),
			'size'		=> '1/3',
			'fields'	=> array(
	
				array(
					'id' 		=> 'image',
					'type' 		=> 'upload',
					'title' 	=> __('Image', 'mfn-opts'),
					'sub_desc' 	=> __('Featured Image', 'mfn-opts'),
				),
			
				array(
					'id' 		=> 'slogan',
					'type' 		=> 'text',
					'title' 	=> __('Slogan', 'mfn-opts'),
				),
			
				array(
					'id' 		=> 'title',
					'type' 		=> 'text',
					'title' 	=> __('Title', 'mfn-opts'),
				),
		
				array(
					'id' 		=> 'link',
					'type' 		=> 'text',
					'title' 	=> __('Link', 'mfn-opts'),
				),
		
				array(
					'id' 		=> 'target',
					'type' 		=> 'select',
					'options' 	=> array( 0 => 'No', 1 => 'Yes' ),
					'title'		=> __('Open in new window', 'mfn-opts'),
					'desc' 		=> __('Adds a target="_blank" attribute to the link.', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'animate',
					'type' 		=> 'select',
					'title' 	=> __('Animation', 'mfn-opts'),
					'sub_desc' 	=> __('Entrance animation', 'mfn-opts'),
					'options' 	=> $mfn_animate,
				),
	
			),
		),
		
		// Blockquote --------------------------------------------
		'blockquote' => array(
			'type' 		=> 'blockquote',
			'title' 	=> __('Blockquote', 'mfn-opts'), 
			'size' 		=> '1/4', 
			'fields'	=> array(
		
				array(
					'id' 		=> 'content',
					'type' 		=> 'textarea',
					'title' 	=> __('Content', 'mfn-opts'),
					'sub_desc' 	=> __('Blockquote content.', 'mfn-opts'),
					'desc' 		=> __('HTML tags allowed.', 'mfn-opts')
				),
				
				array(
					'id' 		=> 'author',
					'type' 		=> 'text',
					'title' 	=> __('Author', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'link',
					'type' 		=> 'text',
					'title' 	=> __('Link', 'mfn-opts'),
					'sub_desc' 	=> __('Link to company page.', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'target',
					'type' 		=> 'select',
					'options' 	=> array( 0 => 'No', 1 => 'Yes' ),
					'title' 	=> __('Open in new window', 'mfn-opts'),
					'sub_desc' 	=> __('Open link in a new window.', 'mfn-opts'),
					'desc' 		=> __('Adds a target="_blank" attribute to the link.', 'mfn-opts'),
				),
				
			),															
		),
		
		// Blog --------------------------------------------
		'blog' => array(
			'type' => 'blog',
			'title' => __('Blog', 'mfn-opts'), 
			'size' => '1/1', 
			'fields' => array(
		
				array(
					'id' 		=> 'count',
					'type' 		=> 'text',
					'title' 	=> __('Count', 'mfn-opts'),
					'sub_desc' 	=> __('Number of posts to show', 'mfn-opts'),
					'std' 		=> '2',
					'class' 	=> 'small-text',
				),

				array(
					'id' 		=> 'category',
					'type' 		=> 'select',
					'title' 	=> __('Category', 'mfn-opts'),
					'options' 	=> mfn_get_categories( 'category' ),
					'sub_desc' 	=> __('Select posts category', 'mfn-opts'),
				),
				
				array(
					'id'		=> 'category_multi',
					'type'		=> 'text',
					'title'		=> __('Multiple Categories', 'mfn-opts'),
					'sub_desc'	=> __('Categories Slugs', 'mfn-opts'),
					'desc'		=> __('Slugs should be separated with <strong>coma</strong> (,).', 'mfn-opts'),
				),
				
				array(
					'id'		=> 'style',
					'type'		=> 'select',
					'title'		=> 'Style',
					'options'	=> array(
						'classic'	=> 'Classic',
						'masonry'	=> 'Masonry',
						'timeline'	=> 'Timeline',
					),
					'std'		=> 'classic',
				),
				
				array(
					'id'		=> 'greyscale',
					'type'		=> 'select',
					'title'		=> 'Greyscale Images',
					'options' 	=> array( 0 => 'No', 1 => 'Yes' ),
				),
				
				array(
					'id' 		=> 'more',
					'type' 		=> 'select',
					'options' 	=> array( 0 => 'No', 1 => 'Yes' ),
					'title' 	=> __('Show Read More link', 'mfn-opts'),
					'std'		=> 1,
				),
				
				array(
					'id' 		=> 'pagination',
					'type' 		=> 'select',
					'options' 	=> array( 0 => 'No', 1 => 'Yes' ),
					'title' 	=> __('Show Pagination', 'mfn-opts'),
					'desc' 		=> __('<strong>Notice:</strong> Pagination will <strong>not</strong> work if you put item on Homepage of WordPress Multilangual Site.', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'load_more',
					'type' 		=> 'select',
					'title' 	=> __('Load More button', 'mfn-opts'),
					'sub_desc' 	=> __('Show Ajax Load More button', 'mfn-opts'),  
					'desc' 		=> __('This will replace all sliders on list with featured images', 'mfn-opts'),
					'options' 	=> array( 0 => 'No', 1 => 'Yes' ),
				),
				
			),															
		),
		
		// Blog Slider --------------------------------------------
		'blog_slider' => array(
			'type' => 'blog_slider',
			'title' => __('Blog Slider', 'mfn-opts'), 
			'size' => '1/4', 
			'fields' => array(
			
				array(
					'id' 		=> 'title',
					'type' 		=> 'text',
					'title' 	=> __('Title', 'mfn-opts'),
				),
		
				array(
					'id' 		=> 'count',
					'type' 		=> 'text',
					'title' 	=> __('Count', 'mfn-opts'),
					'sub_desc' 	=> __('Number of posts to show', 'mfn-opts'),
					'desc'		=> __('We <strong>do not</strong> recommend use more than 10 items, because site will be working slowly.', 'mfn-opts'),
					'std' 		=> '5',
					'class' 	=> 'small-text',
				),

				array(
					'id' 		=> 'category',
					'type' 		=> 'select',
					'title' 	=> __('Category', 'mfn-opts'),
					'options' 	=> mfn_get_categories( 'category' ),
					'sub_desc' 	=> __('Select posts category', 'mfn-opts'),
				),
				
				array(
					'id'		=> 'category_multi',
					'type'		=> 'text',
					'title'		=> __('Multiple Categories', 'mfn-opts'),
					'sub_desc'	=> __('Categories Slugs', 'mfn-opts'),
					'desc'		=> __('Slugs should be separated with <strong>coma</strong> (,).', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'more',
					'type' 		=> 'select',
					'options' 	=> array( 0 => 'No', 1 => 'Yes' ),
					'title' 	=> __('Show Read More button', 'mfn-opts'),
					'std'		=> 1,
				),
				
			),															
		),
		
		// Call to Action --------------------------------------------------
		'call_to_action' => array(
			'type' => 'call_to_action',
			'title' => __('Call to Action', 'mfn-opts'),
			'size' => '1/1',
			'fields' => array(
			
				array(
					'id' 		=> 'title',
					'type' 		=> 'text',
					'title' 	=> __('Title', 'mfn-opts'),
				),
				
				array(
					'id'		=> 'icon',
					'type' 		=> 'icon',
					'title' 	=> __('Icon', 'mfn-opts'),
					'class'		=> 'small-text',
				),
				
				array(
					'id'		=> 'content',
					'type' 		=> 'textarea',
					'title' 	=> __('Content', 'mfn-opts'),
					'desc' 		=> __('HTML tags allowed.', 'mfn-opts'),
				),

				array(
					'id'		=> 'link',
					'type' 		=> 'text',
					'title' 	=> __('Link', 'mfn-opts'),
				),
				
				array(
					'id'		=> 'button_title',
					'type' 		=> 'text',
					'title' 	=> __('Button Title', 'mfn-opts'),
					'desc' 		=> __('Leave this field blank if you want Call to Action with Big Icon', 'mfn-opts'),
					'class'		=> 'small-text',
				),
				
				array(
					'id' 		=> 'class',
					'type' 		=> 'text',
					'title' 	=> __('Class', 'mfn-opts'),
					'desc' 		=> __('This option is useful when you want to use PrettyPhoto (prettyphoto)', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'target',
					'type' 		=> 'select',
					'title' 	=> __('Open in new window', 'mfn-opts'),
					'desc'		=> __('Adds a target="_blank" attribute to the link', 'mfn-opts'),
					'options' 	=> array( 0 => 'No', 1 => 'Yes' ),
				),
				
				array(
					'id' 		=> 'animate',
					'type' 		=> 'select',
					'title' 	=> __('Animation', 'mfn-opts'),
					'sub_desc' 	=> __('Entrance animation', 'mfn-opts'),
					'options' 	=> $mfn_animate,
				),
				
			),
		),
		
		// Chart  --------------------------------------------------
		'chart' => array(
			'type' => 'chart',
			'title' => __('Chart', 'mfn-opts'),
			'size' => '1/4',
			'fields' => array(
			
				array(
					'id' 		=> 'percent',
					'type' 		=> 'text',
					'title' 	=> __('Percent', 'mfn-opts'),
					'desc' 		=> __('Number between 0-100', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'label',
					'type' 		=> 'text',
					'title' 	=> __('Chart Label', 'mfn-opts'),
				),
				
				array(
					'id'		=> 'icon',
					'type' 		=> 'icon',
					'title' 	=> __('Chart Icon', 'mfn-opts'),
					'class'		=> 'small-text',
				),
				
				array(
					'id'		=> 'image',
					'type' 		=> 'upload',
					'title' 	=> __('Chart Image', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'title',
					'type' 		=> 'text',
					'title' 	=> __('Title', 'mfn-opts'),
				),
		
			),
		),
		
		// Clients  --------------------------------------------
		'clients' => array(
			'type' => 'clients',
			'title' => __('Clients', 'mfn-opts'),
			'size' => '1/1',
			'fields' => array(
	
				array(
					'id' 		=> 'in_row',
					'type' 		=> 'text',
					'title' 	=> __('Items in Row', 'mfn-opts'),
					'sub_desc' 	=> __('Number of items in row', 'mfn-opts'),
					'desc' 		=> __('Recommended number: 3-6', 'mfn-opts'),
					'std' 		=> 6,
					'class' 	=> 'small-text',
				),
				
				array(
					'id'		=> 'category',
					'type'		=> 'select',
					'title'		=> __('Category', 'mfn-opts'),
					'options'	=> mfn_get_categories( 'client-types' ),
					'sub_desc'	=> __('Select the client post category.', 'mfn-opts'),
				),
				
				array(
					'id'		=> 'orderby',
					'type'		=> 'select',
					'title'		=> __('Order by', 'mfn-opts'),
					'sub_desc'	=> __('Portfolio items order by column.', 'mfn-opts'),
					'options' 	=> array(
						'date'			=> 'Date',
						'menu_order' 	=> 'Menu order',
						'title'			=> 'Title',
						'rand'			=> 'Random',
					),
					'std'		=> 'menu_order'
				),
				
				array(
					'id'		=> 'order',
					'type'		=> 'select',
					'title'		=> __('Order', 'mfn-opts'),
					'sub_desc'	=> __('Portfolio items order.', 'mfn-opts'),
					'options'	=> array(
						'ASC' 	=> 'Ascending',
						'DESC' 	=> 'Descending',
					),
					'std'		=> 'ASC'
				),
				
				array(
					'id' 		=> 'style',
					'type' 		=> 'select',
					'options' 	=> array(
						''			=> 'Default',
						'tiles' 	=> 'Tiles',
					),
					'title' 	=> __('Style', 'mfn-opts'),
				),
				
				array(
					'id'		=> 'greyscale',
					'type'		=> 'select',
					'title'		=> 'Greyscale Images',
					'options' 	=> array( 0 => 'No', 1 => 'Yes' ),
				),
	
			),
		),
		
		// Clients Slider --------------------------------------------
		'clients_slider' => array(
			'type' => 'clients_slider',
			'title' => __('Clients Slider', 'mfn-opts'),
			'size' => '1/1',
			'fields' => array(

				array(
					'id' 		=> 'title',
					'type' 		=> 'text',
					'title' 	=> __('Title', 'mfn-opts'),
				),
			
				array(
					'id'		=> 'category',
					'type'		=> 'select',
					'title'		=> __('Category', 'mfn-opts'),
					'options'	=> mfn_get_categories( 'client-types' ),
					'sub_desc'	=> __('Select the client post category.', 'mfn-opts'),
				),
				
				array(
					'id'		=> 'orderby',
					'type'		=> 'select',
					'title'		=> __('Order by', 'mfn-opts'),
					'sub_desc'	=> __('Portfolio items order by column.', 'mfn-opts'),
					'options' 	=> array(
						'date'			=> 'Date',
						'menu_order' 	=> 'Menu order',
						'title'			=> 'Title',
						'rand'			=> 'Random',
					),
					'std'		=> 'menu_order'
				),
				
				array(
					'id'		=> 'order',
					'type'		=> 'select',
					'title'		=> __('Order', 'mfn-opts'),
					'sub_desc'	=> __('Portfolio items order.', 'mfn-opts'),
					'options'	=> array(
						'ASC' 	=> 'Ascending',
						'DESC' 	=> 'Descending',
					),
					'std'		=> 'ASC'
				),
	
			),
		),
		
		// Code  --------------------------------------------
		'code' => array(
			'type' 		=> 'code',
			'title' 	=> __('Code', 'mfn-opts'), 
			'size' 		=> '1/4', 
			'fields'	=> array(
		
				array(
					'id' 		=> 'content',
					'type' 		=> 'textarea',
					'title' 	=> __('Content', 'mfn-opts'),
					'class' 	=> 'full-width',
				),
				
			),															
		),
		
		// Column  --------------------------------------------
		'column' => array(
			'type' 		=> 'column',
			'title' 	=> __('Column', 'mfn-opts'), 
			'size' 		=> '1/4', 
			'fields'	=> array(
		
				array(
					'id' 		=> 'title',
					'type' 		=> 'text',
					'title' 	=> __('Title', 'mfn-opts'),
					'desc' 		=> __('This field is used as an Item Label in admin panel only and shows after page update.', 'mfn-opts'),
				),
					
				array(
					'id' 		=> 'content',
					'type' 		=> 'textarea',
					'title' 	=> __('Column content', 'mfn-opts'),
					'desc' 		=> __('Shortcodes and HTML tags allowed.', 'mfn-opts'),
					'class' 	=> 'full-width sc',
					'validate' 	=> 'html',
				),				
				
				array(
					'id' 		=> 'align',
					'type' 		=> 'select',
					'title' 	=> __('Text Align', 'mfn-opts'),
					'options' 	=> array(
						''			=> 'None',
						'left'		=> 'Left',
						'right'		=> 'Right',
						'center'	=> 'Center',
						'justify'	=> 'Justify',
					),
				),
				
				array(
					'id' 		=> 'animate',
					'type' 		=> 'select',
					'title' 	=> __('Animation', 'mfn-opts'),
					'sub_desc' 	=> __('Entrance animation', 'mfn-opts'),
					'options' 	=> $mfn_animate,
				),

			),															
		),
		
		// Contact box --------------------------------------------
		'contact_box' => array(
			'type' 		=> 'contact_box',
			'title' 	=> __('Contact Box', 'mfn-opts'), 
			'size' 		=> '1/4', 
			'fields' 	=> array(
		
				array(
					'id' 		=> 'title',
					'type' 		=> 'text',
					'title' 	=> __('Title', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'address',
					'type' 		=> 'textarea',
					'title' 	=> __('Address', 'mfn-opts'),
					'desc' 		=> __('HTML tags allowed.', 'mfn-opts'),
				),
					
				array(
					'id' 		=> 'telephone',
					'type' 		=> 'text',
					'title' 	=> __('Telephone', 'mfn-opts'),
				),			
				
				array(
					'id' 		=> 'email',
					'type' 		=> 'text',
					'title' 	=> __('Email', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'www',
					'type' 		=> 'text',
					'title' 	=> __('WWW', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'image',
					'type' 		=> 'upload',
					'title' 	=> __('Background Image', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'animate',
					'type' 		=> 'select',
					'title' 	=> __('Animation', 'mfn-opts'),
					'sub_desc' 	=> __('Entrance animation', 'mfn-opts'),
					'options' 	=> $mfn_animate,
				),
				
			),															
		),
		
		// Content  --------------------------------------------
		'content' => array(
			'type' => 'content',
			'title' => __('Content WP', 'mfn-opts'), 
			'size' => '1/4',
			'fields' => array(
		
				array(
					'id' 		=> 'info',
					'type' 		=> 'info',
					'desc' 		=> __('Adding this Item will show Content from WordPress Editor above Page Options. You can use it only once per page. Please also remember to turn on "Hide The Content" option.', 'nhp-opts'),
				),

			),														
		),
		
		// Countdown  --------------------------------------------------
		'countdown' => array(
			'type' => 'countdown',
			'title' => __('Countdown', 'mfn-opts'),
			'size' => '1/1',
			'fields' => array(
		
				array(
					'id' 		=> 'date',
					'type' 		=> 'text',
					'title' 	=> __('Lunch Date', 'mfn-opts'),
					'desc' 		=> __('Format: 12/30/2014 12:00:00 month/day/year hour:minute:second', 'mfn-opts'),
					'std' 		=> '12/30/2014 12:00:00',
					'class' 	=> 'small-text',
				),
				
				array(
					'id' 		=> 'timezone',
					'type' 		=> 'select',
					'title' 	=> __('UTC Timezone', 'mfn-opts'),
					'options' 	=> mfna_utc(),
					'std' 		=> '0',
				),
		
			),
		),
		
		// Counter  --------------------------------------------------
		'counter' => array(
			'type' => 'counter',
			'title' => __('Counter', 'mfn-opts'),
			'size' => '1/4',
			'fields' => array(
		
				array(
					'id' 		=> 'icon',
					'type' 		=> 'icon',
					'title' 	=> __('Icon', 'mfn-opts'),
					'std' 		=> ' icon-lamp',
					'class' 	=> 'small-text',
				),
				
				array(
					'id' 		=> 'color',
					'type' 		=> 'text',
					'title' 	=> __('Icon Color', 'mfn-opts'),
					'desc' 		=> __('Use color name (eg. "blue") or hex (eg. "#2991D6").', 'mfn-opts'),
					'class' 	=> 'small-text',
				),

				array(
					'id' 		=> 'image',
					'type' 		=> 'upload',
					'title' 	=> __('Image', 'mfn-opts'),
					'desc' 		=> __('If you upload an image, icon will not show.', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'number',
					'type' 		=> 'text',
					'title' 	=> __('Number', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'title',
					'type' 		=> 'text',
					'title' 	=> __('Title', 'mfn-opts'),
				),

				array(
					'id' 		=> 'type',
					'type' 		=> 'select',
					'options' 	=> array(
						'horizontal'	=> 'Horizontal',
						'vertical' 		=> 'Vertical',
					),
					'title' 	=> __('Style', 'mfn-opts'),
					'desc' 		=> __('Vertical style works only for column widths: 1/4, 1/3 & 1/2', 'mfn-opts'),
					'std'		=> 'vertical',
				),
				
				array(
					'id' 		=> 'animate',
					'type' 		=> 'select',
					'title' 	=> __('Animation', 'mfn-opts'),
					'sub_desc' 	=> __('Entrance animation', 'mfn-opts'),
					'options' 	=> $mfn_animate,
				),
		
			),
		),
	
		// Divider  --------------------------------------------
		'divider' => array(
			'type' => 'divider',
			'title' => __('Divider', 'mfn-opts'), 
			'size' => '1/1',
			'fields' => array(
		
				array(
					'id' 		=> 'height',
					'type' 		=> 'text',
					'title' 	=> __('Divider height', 'mfn-opts'),
					'desc' 		=> __('px', 'mfn-opts'),
					'class' 	=> 'small-text',
				),
				
				array(
					'id' 		=> 'style',
					'type' 		=> 'select',
					'options' 	=> array( 
						'default'	=> 'Default',
						'dots'		=> 'Dots',
						'zigzag'	=> 'ZigZag',
					),
					'title' 	=> __('Style', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'line',
					'type' 		=> 'select',
					'options' 	=> array( 
						'default'	=> 'Default',
						'narrow'	=> 'Narrow',
						'wide'		=> 'Wide',
						''			=> 'No Line',
					),
					'title' 	=> __('Line', 'mfn-opts'),
					'desc' 		=> __('This option can be used <strong>only</strong> with Style: Default.', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'themecolor',
					'type' 		=> 'select',
					'options' 	=> array( 
						0			=> 'No',
						1			=> 'Yes',
					),
					'title' 	=> __('Theme Color', 'mfn-opts'),
					'desc' 		=> __('This option can be used <strong>only</strong> with Style: Default.', 'mfn-opts'),
				),
				
			),														
		),	
		
		// Fancy Divider  --------------------------------------------
		'fancy_divider' => array(
			'type' => 'fancy_divider',
			'title' => __('Fancy Divider', 'mfn-opts'), 
			'size' => '1/1',
			'fields' => array(

				array(
					'id' 		=> 'info',
					'type' 		=> 'info',
					'desc' 		=> __('This item can only be used on pages <strong>Without Sidebar</strong>. Please also set Section Style to <strong>Full Width</strong>.', 'nhp-opts'),
				),
			
				array(
					'id' 		=> 'style',
					'type' 		=> 'select',
					'options' 	=> array( 
						'circle up'		=> 'Circle Up',
						'circle down'	=> 'Circle Down',
						'curve up'		=> 'Curve Up',
						'curve down'	=> 'Curve Down',
						'stamp'			=> 'Stamp',
						'triangle up'	=> 'Triangle Up',
						'triangle down'	=> 'Triangle Down',
					),
					'title' 	=> __('Style', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'color_top',
					'type' 		=> 'text',
					'title' 	=> __('Color Top', 'mfn-opts'),
					'desc' 		=> __('Use color name (eg. "blue") or hex (eg. "#2991D6").', 'mfn-opts'),
					'class' 	=> 'small-text',
					'std' 		=> '',
				),
				
				array(
					'id' 		=> 'color_bottom',
					'type' 		=> 'text',
					'title' 	=> __('Color Bottom', 'mfn-opts'),
					'desc' 		=> __('Use color name (eg. "blue") or hex (eg. "#2991D6").', 'mfn-opts'),
					'class' 	=> 'small-text',
					'std' 		=> '',
				),
				
			),														
		),	
		
		// Fancy Heading --------------------------------------------
		'fancy_heading' => array(
			'type' 		=> 'fancy_heading',
			'title' 	=> __('Fancy Heading', 'mfn-opts'),
			'size' 		=> '1/1',
			'fields' 	=> array(
			
				array(
					'id' 		=> 'title',
					'type' 		=> 'text',
					'title' 	=> __('Title', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'h1',
					'type' 		=> 'select',
					'options' 	=> array( 0 => 'No', 1 => 'Yes' ),
					'title' 	=> __('Use H1 tag', 'mfn-opts'),
					'desc' 		=> __('Wrap title into H1 instead of H2', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'icon',
					'type' 		=> 'icon',
					'title' 	=> __('Icon', 'mfn-opts'),
					'sub_desc' 	=> __('Icon Style only', 'mfn-opts'),
					'class' 	=> 'small-text',
				),
				
				array(
					'id' 		=> 'slogan',
					'type' 		=> 'text',
					'title' 	=> __('Slogan', 'mfn-opts'),
					'sub_desc' 	=> __('Line Style only', 'mfn-opts'),
				),

				array(
					'id' 		=> 'content',
					'type' 		=> 'textarea',
					'title' 	=> __('Content', 'mfn-opts'),
					'desc' 		=> __('Some Shortcodes and HTML tags allowed', 'mfn-opts'),
					'class' 	=> 'full-width sc',
					'validate' 	=> 'html',
				),

				array(
					'id' 		=> 'style',
					'type' 		=> 'select',
					'options' 	=> array( 
						'icon'		=> 'Icon',
						'line'		=> 'Line',
						'arrows' 	=> 'Arrows',
					),
					'title' 	=> __('Style', 'mfn-opts'),
					'desc' 		=> __('Some fields above work on selected styles.', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'animate',
					'type' 		=> 'select',
					'title' 	=> __('Animation', 'mfn-opts'),
					'sub_desc' 	=> __('Entrance animation', 'mfn-opts'),
					'options' 	=> $mfn_animate,
				),
		
			),
		),

		// FAQ  --------------------------------------------
		'faq' => array(
			'type' 		=> 'faq',
			'title' 	=> __('FAQ', 'mfn-opts'), 
			'size' 		=> '1/4', 
			'fields' 	=> array(
				
				array(
					'id' 		=> 'title',
					'type' 		=> 'text',
					'title' 	=> __('Title', 'mfn-opts'),
				),
					
				array(
					'id' 		=> 'tabs',
					'type' 		=> 'tabs',
					'title' 	=> __('FAQ', 'mfn-opts'),
					'sub_desc' 	=> __('Manage FAQ tabs.', 'mfn-opts'),
					'desc' 		=> __('You can use Drag & Drop to set the order', 'mfn-opts'),
				),
					
				array(
					'id' 		=> 'open1st',
					'type' 		=> 'select',
					'options' 	=> array( 0 => 'No', 1 => 'Yes' ),
					'title' 	=> __('Open First', 'mfn-opts'),
					'desc' 		=> __('Open first tab at start', 'mfn-opts'),
				),
					
				array(
					'id' 		=> 'openAll',
					'type' 		=> 'select',
					'options' 	=> array( 0 => 'No', 1 => 'Yes' ),
					'title' 	=> __('Open All', 'mfn-opts'),
					'desc' 		=> __('Open all tabs at start', 'mfn-opts'),
				),
				
			),															
		),
		
		// Feature List --------------------------------------------
		'feature_list' => array(
			'type' 		=> 'feature_list',
			'title' 	=> __('Feature List', 'mfn-opts'),
			'size' 		=> '1/1',
			'fields' 	=> array(
	
				array(
					'id' 	=> 'title',
					'type' 	=> 'text',
					'title' => __('Title', 'mfn-opts'),
					'desc' 	=> __('This field is used as an Item Label in admin panel only and shows after page update.', 'mfn-opts'),
				),
					
				array(
					'id' 	=> 'content',
					'type' 	=> 'textarea',
					'title' => __('Content', 'mfn-opts'),
					'desc' 	=> __('Please use <strong>[item icon="" title="" link="" target=""]</strong> shortcodes.', 'mfn-opts'),
					'std' 	=> '[item icon="icon-lamp" title="" link="" target="" animate=""]',
				),
	
			),
		),
		
		// Flat Box --------------------------------------------
		'flat_box' => array(
			'type' 		=> 'flat_box',
			'title' 	=> __('Flat Box', 'mfn-opts'),
			'size' 		=> '1/4',
			'fields' 	=> array(
	
				array(
					'id' 		=> 'icon',
					'type' 		=> 'icon',
					'title' 	=> __('Icon', 'mfn-opts'),
					'std' 		=> 'icon-lamp',
					'class' 	=> 'small-text',
				),
			
				array(
					'id' 		=> 'background',
					'type' 		=> 'text',
					'title' 	=> __('Icon background', 'mfn-opts'),
					'desc' 		=> __('Use color name (eg. "blue") or hex (eg. "#2991D6").<br /><br />Leave this field blank to use Theme Background.', 'mfn-opts'),
					'class' 	=> 'small-text',
				),
				
				array(
					'id' 		=> 'image',
					'type' 		=> 'upload',
					'title' 	=> __('Image', 'mfn-opts'),		
				),
				
				array(
					'id' 		=> 'title',
					'type' 		=> 'text',
					'title' 	=> __('Title', 'mfn-opts'),
				),

				array(
					'id' 		=> 'content',
					'type' 		=> 'textarea',
					'title' 	=> __('Content', 'mfn-opts'),
					'desc' 		=> __('Some Shortcodes and HTML tags allowed', 'mfn-opts'),
					'class'		=> 'full-width sc',
					'validate'	=> 'html',
				),
				
				array(
					'id' 		=> 'link',
					'type' 		=> 'text',
					'title' 	=> __('Link', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'target',
					'type' 		=> 'select',
					'options' 	=> array( 0 => 'No', 1 => 'Yes' ),
					'title' 	=> __('Open in new window', 'mfn-opts'),
					'desc' 		=> __('Adds a target="_blank" attribute to the link.', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'animate',
					'type' 		=> 'select',
					'title' 	=> __('Animation', 'mfn-opts'),
					'sub_desc' 	=> __('Entrance animation', 'mfn-opts'),
					'options' 	=> $mfn_animate,
				),
	
			),
		),
		
		// Hover Box --------------------------------------------
		'hover_box' => array(
			'type' 		=> 'hover_box',
			'title' 	=> __('Hover Box', 'mfn-opts'),
			'size' 		=> '1/4',
			'fields'	=> array(
	
				array(
					'id' 		=> 'image',
					'type' 		=> 'upload',
					'title' 	=> __('Image', 'mfn-opts'),
				),
						
				array(
					'id' 		=> 'image_hover',
					'type' 		=> 'upload',
					'title' 	=> __('Hover Image', 'mfn-opts'),
					'desc' 		=> __('Both images must have the same size.', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'link',
					'type' 		=> 'text',
					'title' 	=> __('Link', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'target',
					'type' 		=> 'select',
					'options' 	=> array( 0 => 'No', 1 => 'Yes' ),
					'title' 	=> __('Open in new window', 'mfn-opts'),
					'desc' 		=> __('Adds a target="_blank" attribute to the link.', 'mfn-opts'),
				),
	
			),
		),
		
		// Hover Color --------------------------------------------------
		'hover_color' => array(
			'type' => 'hover_color',
			'title' => __('Hover Color', 'mfn-opts'),
			'size' => '1/4',
			'fields' => array(
		
				array(
					'id' 		=> 'background',
					'type' 		=> 'text',
					'title' 	=> __('Background color', 'mfn-opts'),
					'desc' 		=> __('Use color name ( blue )<br />or hex ( #2991D6 )', 'mfn-opts'),
					'class' 	=> 'small-text',
					'std' 		=> '#2991D6',
				),
		
				array(
					'id' 		=> 'background_hover',
					'type' 		=> 'text',
					'title' 	=> __('Hover Background color', 'mfn-opts'),
					'desc' 		=> __('Use color name ( blue )<br />or hex ( #2991D6 )', 'mfn-opts'),
					'class' 	=> 'small-text',
					'std' 		=> '#2991D6',
				),
			
				array(
					'id'		=> 'content',
					'type' 		=> 'textarea',
					'title' 	=> __('Content', 'mfn-opts'),
					'desc' 		=> __('Some Shortcodes and HTML tags allowed', 'mfn-opts'),
					'class'		=> 'full-width sc',
				),

				array(
					'id'		=> 'link',
					'type' 		=> 'text',
					'title' 	=> __('Link', 'mfn-opts'),
				),

				array(
					'id' 		=> 'target',
					'type' 		=> 'select',
					'title' 	=> __('Open in new window', 'mfn-opts'),
					'desc'		=> __('Adds a target="_blank" attribute to the link', 'mfn-opts'),
					'options' 	=> array( 0 => 'No', 1 => 'Yes' ),
				),
		
			),
		),
		
		// How It Works --------------------------------------------
		'how_it_works' => array(
			'type' 		=> 'how_it_works',
			'title' 	=> __('How It Works', 'mfn-opts'),
			'size' 		=> '1/4',
			'fields' 	=> array(
	
				array(
					'id' 		=> 'image',
					'type' 		=> 'upload',
					'title' 	=> __('Background Image', 'mfn-opts'),
					'desc' 		=> __('Recommended: Square Image with transparent background.', 'mfn-opts'),
				),
						
				array(
					'id' 		=> 'number',
					'type' 		=> 'text',
					'title' 	=> __('Number', 'mfn-opts'),
					'class' 	=> 'small-text',
				),

				array(
					'id' 		=> 'title',
					'type' 		=> 'text',
					'title' 	=> __('Title', 'mfn-opts'),
				),

				array(
					'id' 		=> 'content',
					'type' 		=> 'textarea',
					'title' 	=> __('Content', 'mfn-opts'),
					'desc' 		=> __('Some Shortcodes and HTML tags allowed', 'mfn-opts'),
					'class'		=> 'full-width sc',
					'validate'	=> 'html',
				),
				
				array(
					'id' 		=> 'border',
					'type' 		=> 'select',
					'title' 	=> __('Line', 'mfn-opts'),
					'sub_desc' 	=> __('Show right connecting line', 'mfn-opts'),
					'options' 	=> array( 0 => 'No', 1 => 'Yes' ),
				),
				
				array(
					'id' 		=> 'link',
					'type' 		=> 'text',
					'title' 	=> __('Link', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'target',
					'type' 		=> 'select',
					'options' 	=> array( 0 => 'No', 1 => 'Yes' ),
					'title' 	=> __('Open in new window', 'mfn-opts'),
					'desc' 		=> __('Adds a target="_blank" attribute to the link.', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'animate',
					'type' 		=> 'select',
					'title' 	=> __('Animation', 'mfn-opts'),
					'sub_desc' 	=> __('Entrance animation', 'mfn-opts'),
					'options' 	=> $mfn_animate,
				),
	
			),
		),
		
		// Icon Box  --------------------------------------------------
		'icon_box' => array(
			'type' => 'icon_box',
			'title' => __('Icon Box', 'mfn-opts'), 
			'size' => '1/4',
			'fields' => array(
		
				array(
					'id' 		=> 'title',
					'type' 		=> 'text',
					'title' 	=> __('Title', 'mfn-opts'),
				),
					
				array(
					'id' 		=> 'content',
					'type' 		=> 'textarea',
					'title' 	=> __('Content', 'mfn-opts'),
					'desc' 		=> __('Some Shortcodes and HTML tags allowed', 'mfn-opts'),
					'class'		=> 'full-width sc',
				),
		
				array(
					'id' 		=> 'icon',
					'type' 		=> 'icon',
					'title' 	=> __('Icon', 'mfn-opts'),
					'std' 		=> 'icon-lamp',
					'class' 	=> 'small-text',
				),
				
				array(
					'id' 		=> 'image',
					'type' 		=> 'upload',
					'title' 	=> __('Image', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'icon_position',
					'type' 		=> 'select',
					'options'	=> array(
						'left'	=> 'Left',
						'top'	=> 'Top',
					),
					'title' 	=> __('Icon Position', 'mfn-opts'),
					'desc' 		=> __('Left position works only for column widths: 1/4, 1/3 & 1/2', 'mfn-opts'),
					'std'		=> 'top',
				),
				
				array(
					'id' 		=> 'border',
					'type' 		=> 'select',
					'title' 	=> __('Border', 'mfn-opts'),
					'sub_desc' 	=> __('Show right border', 'mfn-opts'),
					'options' 	=> array(
						0 	=> 'No',
						1 	=> 'Yes'
					),
				),
				
				array(
					'id' 		=> 'link',
					'type' 		=> 'text',
					'title' 	=> __('Link', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'target',
					'type' 		=> 'select',
					'options' 	=> array( 0 => 'No', 1 => 'Yes' ),
					'title' 	=> __('Open in new window', 'mfn-opts'),
					'desc' 		=> __('Adds a target="_blank" attribute to the link.', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'animate',
					'type' 		=> 'select',
					'title' 	=> __('Animation', 'mfn-opts'),
					'sub_desc' 	=> __('Entrance animation', 'mfn-opts'),
					'options' 	=> $mfn_animate,
				),
				
				array(
					'id' 		=> 'class',
					'type' 		=> 'text',
					'title' 	=> __('Custom CSS classes for link', 'mfn-opts'),
					'desc' 		=> __('This option is useful when you want to use PrettyPhoto (prettyphoto) or Scroll (scroll).', 'mfn-opts'),
				),

			),														
		),
			
		// Image  --------------------------------------------------
		'image' => array(
			'type' => 'image',
			'title' => __('Image', 'mfn-opts'), 
			'size' => '1/4',
			'fields' => array(
		
				array(
					'id' 		=> 'src',
					'type' 		=> 'upload',
					'title' 	=> __('Image', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'border',
					'type' 		=> 'select',
					'options' 	=> array( 0 => 'No', 1 => 'Yes' ),
					'title' 	=> __('Border', 'mfn-opts'),
					'sub_desc' 	=> __('Show Image Border', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'align',
					'type' 		=> 'select',
					'title' 	=> __('Align', 'mfn-opts'),
					'desc' 		=> __('If you want image to be resized to column width please use <b>align none</b>', 'mfn-opts'),
					'options' 	=> array( 
						'' 			=> 'None', 
						'left' 		=> 'Left', 
						'right' 	=> 'Right', 
						'center' 	=> 'Center', 
					),
				),
				
				array(
					'id' 		=> 'margin',
					'type' 		=> 'text',
					'title' 	=> __('Margin Top', 'mfn-opts'),
					'desc' 		=> __('px', 'mfn-opts'),
					'class' 	=> 'small-text',
				),
				
				array(
					'id' 		=> 'alt',
					'type' 		=> 'text',
					'title' 	=> __('Alternate Text', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'caption',
					'type' 		=> 'text',
					'title' 	=> __('Caption', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'link_image',
					'type' 		=> 'upload',
					'title' 	=> __('Zoomed image', 'mfn-opts'),
					'desc' 		=> __('This image will be opened in lightbox.', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'link',
					'type' 		=> 'text',
					'title' 	=> __('Link', 'mfn-opts'),
					'desc' 		=> __('This link will work only if you leave the above "Zoomed image" field empty.', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'target',
					'type' 		=> 'select',
					'options' 	=> array( 0 => 'No', 1 => 'Yes' ),
					'title' 	=> __('Open in new window', 'mfn-opts'),
					'desc' 		=> __('Adds a target="_blank" attribute to the link.', 'mfn-opts'),
				),
				
				array(
					'id'		=> 'greyscale',
					'type'		=> 'select',
					'title'		=> 'Greyscale Images',
					'desc'		=> 'Works only for images with link',
					'options' 	=> array( 0 => 'No', 1 => 'Yes' ),
				),
				
				array(
					'id' 		=> 'animate',
					'type' 		=> 'select',
					'title' 	=> __('Animation', 'mfn-opts'),
					'sub_desc' 	=> __('Entrance animation', 'mfn-opts'),
					'options' 	=> $mfn_animate,
				),

			),														
		),
		
		// Info box --------------------------------------------
		'info_box' => array(
			'type' => 'info_box',
			'title' => __('Info Box', 'mfn-opts'),
			'size' => '1/4',
			'fields' => array(
	
				array(
					'id' 		=> 'title',
					'type' 		=> 'text',
					'title'		=> __('Title', 'mfn-opts'),
				),

				array(
					'id' 		=> 'content',
					'type' 		=> 'textarea',
					'title' 	=> __('Content', 'mfn-opts'),
					'desc' 		=> __('HTML tags allowed.', 'mfn-opts'),
					'std' 		=> '<ul><li>list item 1</li><li>list item 2</li></ul>',
				),

				array(
					'id' 		=> 'image',
					'type' 		=> 'upload',
					'title' 	=> __('Background Image', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'animate',
					'type' 		=> 'select',
					'title' 	=> __('Animation', 'mfn-opts'),
					'sub_desc' 	=> __('Entrance animation', 'mfn-opts'),
					'options' 	=> $mfn_animate,
				),
	
			),
		),
		
		// List --------------------------------------------
		'list' => array(
			'type' 		=> 'list',
			'title'		=> __('List', 'mfn-opts'),
			'size'		=> '1/4',
			'fields'	=> array(
	
				array(
					'id' 		=> 'icon',
					'type' 		=> 'icon',
					'title' 	=> __('Icon', 'mfn-opts'),
					'std' 		=> ' icon-lamp',
					'class'		=> 'small-text',
				),
				
				array(
					'id' 		=> 'image',
					'type' 		=> 'upload',
					'title' 	=> __('Image', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'title',
					'type' 		=> 'text',
					'title' 	=> __('Title', 'mfn-opts'),
				),

				array(
					'id' 		=> 'content',
					'type' 		=> 'textarea',
					'title' 	=> __('Content', 'mfn-opts'),
				),

				array(
					'id' 		=> 'link',
					'type' 		=> 'text',
					'title' 	=> __('Link', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'target',
					'type' 		=> 'select',	
					'title' 	=> __('Open in new window', 'mfn-opts'),
					'desc' 		=> __('Adds a target="_blank" attribute to the link.', 'mfn-opts'),
					'options' 	=> array( 0 => 'No', 1 => 'Yes' ),
				),
				
				array(
					'id' 		=> 'style',
					'type' 		=> 'select',	
					'title' 	=> __('Style', 'mfn-opts'),
					'desc' 		=> __('Only <strong>Vertical Style</strong> works for column widths 1/5 & 1/6', 'mfn-opts'),
					'options' 	=> array( 
						1 => 'With background',
						2 => 'Transparent',
						3 => 'Vertical',
						4 => 'Ordered list',
					),
				),
				
				array(
					'id' 		=> 'animate',
					'type' 		=> 'select',
					'title' 	=> __('Animation', 'mfn-opts'),
					'sub_desc' 	=> __('Entrance animation', 'mfn-opts'),
					'options' 	=> $mfn_animate,
				),
				
			),
		),
		
		// Map ---------------------------------------------
		'map' => array(
			'type'		=> 'map',
			'title'		=> __('Map', 'mfn-opts'), 
			'size'		=> '1/4',
			'fields'	=> array(

				array(
					'id' 		=> 'lat',
					'type' 		=> 'text',
					'title' 	=> __('Google Maps Lat', 'mfn-opts'),
					'class' 	=> 'small-text',
					'desc' 		=> __('The map will appear only if this field is filled correctly.', 'mfn-opts'), 
				),
				
				array(
					'id' 		=> 'lng',
					'type' 		=> 'text',
					'title' 	=> __('Google Maps Lng', 'mfn-opts'),
					'class' 	=> 'small-text',
					'desc' 		=> __('The map will appear only if this field is filled correctly.', 'mfn-opts'), 
				),
	
				array(
					'id' 		=> 'zoom',
					'type' 		=> 'text',
					'title' 	=> __('Zoom', 'mfn-opts'),
					'class' 	=> 'small-text',
					'std' 		=> 13,
				),
				
				array(
					'id' 		=> 'height',
					'type' 		=> 'text',
					'title' 	=> __('Height', 'mfn-opts'),
					'class' 	=> 'small-text',
					'std' 		=> 200,
				),
				
				array(
					'id' 		=> 'icon',
					'type' 		=> 'upload',
					'title' 	=> __('Marker Icon', 'mfn-opts'),
					'desc' 		=> __('.png', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'styles',
					'type' 		=> 'textarea',
					'title' 	=> __('Styles', 'mfn-opts'),
					'sub_desc' 	=> __('Google Maps API styles array', 'mfn-opts'),
					'desc' 		=> __('You can get predefined styles from <a target="_blank" href="http://snazzymaps.com/">snazzymaps.com</a> or generate your own <a target="_blank" href="http://gmaps-samples-v3.googlecode.com/svn/trunk/styledmaps/wizard/index.html">here</a>', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'info',
					'type' 		=> 'info',
					'desc' 		=> __('<strong>Notice:</strong> Contact Box works only in Full Width.', 'nhp-opts'),
				),
	
				array(
					'id' 		=> 'title',
					'type' 		=> 'text',
					'title' 	=> __('Box | Title', 'mfn-opts'),
					'class' 	=> 'small-text',
				),
				
				array(
					'id' 		=> 'content',
					'type' 		=> 'textarea',
					'title' 	=> __('Box | Address', 'mfn-opts'),
					'desc' 		=> __('HTML tags allowed.', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'telephone',
					'type' 		=> 'text',
					'title' 	=> __('Box | Telephone', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'email',
					'type' 		=> 'text',
					'title' 	=> __('Box | Email', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'www',
					'type' 		=> 'text',
					'title' 	=> __('Box | WWW', 'mfn-opts'),
				),
				
			),														
		),
		
		// Offer Slider Full --------------------------------------------
		'offer' => array(
			'type' => 'offer',
			'title' => __('Offer Slider Full', 'mfn-opts'),
			'size' => '1/1',
			'fields' => array(
	
				array(
					'id' 		=> 'info',
					'type' 		=> 'info',
					'desc' 		=> __('This item can only be used on pages <strong>Without Sidebar</strong>.<br />Please also set Section Style to <strong>Full Width</strong> and use one Item in one Section.', 'nhp-opts'),
				),
				
				array(
					'id'		=> 'category',
					'type'		=> 'select',
					'title'		=> __('Category', 'mfn-opts'),
					'options'	=> mfn_get_categories( 'offer-types' ),
					'sub_desc'	=> __('Select the offer post category.', 'mfn-opts'),
				),
	
			),
		),
		
		// Offer Slider Thumb --------------------------------------------
		'offer_thumb' => array(
			'type' => 'offer_thumb',
			'title' => __('Offer Slider Thumb', 'mfn-opts'),
			'size' => '1/1',
			'fields' => array(
	
				array(
					'id' 		=> 'info',
					'type' 		=> 'info',
					'desc' 		=> __('This item can only be used <strong>once per page</strong>.', 'nhp-opts'),
				),
				
				array(
					'id'		=> 'category',
					'type'		=> 'select',
					'title'		=> __('Category', 'mfn-opts'),
					'options'	=> mfn_get_categories( 'offer-types' ),
					'sub_desc'	=> __('Select the offer post category.', 'mfn-opts'),
				),
	
			),
		),
		
		// Opening Hours --------------------------------------------
		'opening_hours' => array(
			'type' => 'opening_hours',
			'title' => __('Opening Hours', 'mfn-opts'),
			'size' => '1/4',
			'fields' => array(
	
				array(
					'id' 		=> 'title',
					'type' 		=> 'text',
					'title' 	=> __('Title', 'mfn-opts'),
				),

				array(
					'id' 		=> 'content',
					'type' 		=> 'textarea',
					'title' 	=> __('Content', 'mfn-opts'),
					'desc' 		=> __('HTML tags allowed.', 'mfn-opts'),
					'std' 		=> '<ul><li><label>Monday - Saturday</label><span>8am - 4pm</span></li></ul>',
				),

				array(
					'id' 		=> 'image',
					'type' 		=> 'upload',
					'title' 	=> __('Background Image', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'animate',
					'type' 		=> 'select',
					'title' 	=> __('Animation', 'mfn-opts'),
					'sub_desc' 	=> __('Entrance animation', 'mfn-opts'),
					'options' 	=> $mfn_animate,
				),
	
			),
		),
		
		// Our team --------------------------------------------
		'our_team' => array(
			'type' => 'our_team',
			'title' => __('Our Team', 'mfn-opts'), 
			'size' => '1/4',
			'fields' => array(
				
				array(
					'id' 		=> 'heading',
					'type' 		=> 'text',
					'title' 	=> __('Heading', 'mfn-opts'),
				),
			
				array(
					'id' 		=> 'image',
					'type' 		=> 'upload',
					'title' 	=> __('Photo', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'title',
					'type' 		=> 'text',
					'title' 	=> __('Title', 'mfn-opts'),
					'sub_desc' 	=> __('Will also be used as the image alternative text', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'subtitle',
					'type' 		=> 'text',
					'title' 	=> __('Subtitle', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'phone',
					'type' 		=> 'text',
					'title' 	=> __('Phone', 'mfn-opts'),
				),
				
				array(
					'id'		=> 'content',
					'type'		=> 'textarea',
					'title'		=> __('Content', 'mfn-opts'),
					'desc' 		=> __('Some Shortcodes and HTML tags allowed', 'mfn-opts'),
					'class'		=> 'full-width sc',
				),

				array(
					'id' 		=> 'email',
					'type' 		=> 'text',
					'title' 	=> __('E-mail', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'facebook',
					'type' 		=> 'text',
					'title' 	=> __('Facebook', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'twitter',
					'type' 		=> 'text',
					'title' 	=> __('Twitter', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'linkedin',
					'type' 		=> 'text',
					'title' 	=> __('LinkedIn', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'vcard',
					'type' 		=> 'text',
					'title' 	=> __('vCard', 'mfn-opts'),
				),

				array(
					'id' 		=> 'blockquote',
					'type' 		=> 'textarea',
					'title' 	=> __('Blockquote', 'mfn-opts'),
				),	

				array(
					'id' 		=> 'style',
					'type' 		=> 'select',
					'options'	=> array(
						'circle'		=> 'Circle',
						'vertical'		=> 'Vertical',
						'horizontal'	=> 'Horizontal 	[only: 1/2]',
					),
					'title' 	=> __('Style', 'mfn-opts'),
					'std'		=> 'vertical',
				),
				
				array(
					'id' 		=> 'link',
					'type'		=> 'text',
					'title' 	=> __('Link', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'target',
					'type' 		=> 'select',
					'title' 	=> __('Open in new window', 'mfn-opts'),
					'desc' 		=> __('Adds a target="_blank" attribute to the link.', 'mfn-opts'),
					'options'	=> array( 0 => 'No', 1 => 'Yes' ),
				),
				
				array(
					'id' 		=> 'animate',
					'type' 		=> 'select',
					'title' 	=> __('Animation', 'mfn-opts'),
					'sub_desc' 	=> __('Entrance animation', 'mfn-opts'),
					'options' 	=> $mfn_animate,
				),
				
			),														
		),
		
		// Our team list --------------------------------------------
		'our_team_list' => array(
			'type' => 'our_team_list',
			'title' => __('Our Team List', 'mfn-opts'), 
			'size' => '1/1',
			'fields' => array(
				
				array(
					'id' 		=> 'image',
					'type' 		=> 'upload',
					'title' 	=> __('Photo', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'title',
					'type' 		=> 'text',
					'title' 	=> __('Title', 'mfn-opts'),
					'sub_desc' 	=> __('Will also be used as the image alternative text', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'subtitle',
					'type' 		=> 'text',
					'title' 	=> __('Subtitle', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'phone',
					'type' 		=> 'text',
					'title' 	=> __('Phone', 'mfn-opts'),
				),
				
				array(
					'id'		=> 'content',
					'type'		=> 'textarea',
					'title'		=> __('Content', 'mfn-opts'),
					'desc' 		=> __('Some Shortcodes and HTML tags allowed', 'mfn-opts'),
					'class'		=> 'full-width sc',
				),
				
				array(
					'id'		=> 'blockquote',
					'type'		=> 'textarea',
					'title'		=> __('Blockquote', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'email',
					'type' 		=> 'text',
					'title' 	=> __('E-mail', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'facebook',
					'type' 		=> 'text',
					'title' 	=> __('Facebook', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'twitter',
					'type' 		=> 'text',
					'title' 	=> __('Twitter', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'linkedin',
					'type' 		=> 'text',
					'title' 	=> __('LinkedIn', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'vcard',
					'type' 		=> 'text',
					'title' 	=> __('vCard', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'link',
					'type'		=> 'text',
					'title' 	=> __('Link', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'target',
					'type' 		=> 'select',
					'title' 	=> __('Open in new window', 'mfn-opts'),
					'desc' 		=> __('Adds a target="_blank" attribute to the link.', 'mfn-opts'),
					'options'	=> array( 0 => 'No', 1 => 'Yes' ),
				),
				
			),														
		),
		
		// Photo Box --------------------------------------------
		'photo_box' => array(
			'type' => 'photo_box',
			'title' => __('Photo Box', 'mfn-opts'),
			'size' => '1/4',
			'fields' => array(
	
				array(
					'id'		=> 'title',
					'type'		=> 'text',
					'title'		=> __('Title', 'mfn-opts'),
				),
				
				array(
					'id'		=> 'image',
					'type'		=> 'upload',
					'title'		=> __('Image', 'mfn-opts'),
				),
				
				array(
					'id'		=> 'content',
					'type'		=> 'textarea',
					'title'		=> __('Content', 'mfn-opts'),
					'desc' 		=> __('Some Shortcodes and HTML tags allowed', 'mfn-opts'),
					'class'		=> 'full-width sc',
				),
				
				array(
					'id'		=> 'align',
					'type'		=> 'select',
					'title'		=> 'Text Align',
					'options' 	=> array(
						''		=> 'Center',
						'left'	=> 'Left',
						'right'	=> 'Right',
					),
				),
				
				array(
					'id' 		=> 'link',
					'type'		=> 'text',
					'title' 	=> __('Link', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'target',
					'type' 		=> 'select',
					'title' 	=> __('Open in new window', 'mfn-opts'),
					'desc' 		=> __('Adds a target="_blank" attribute to the link.', 'mfn-opts'),
					'options'	=> array( 0 => 'No', 1 => 'Yes' ),
				),
				
				array(
					'id'		=> 'greyscale',
					'type'		=> 'select',
					'title'		=> 'Greyscale Images',
					'desc'		=> 'Works only for images with link',
					'options' 	=> array( 0 => 'No', 1 => 'Yes' ),
				),
				
				array(
					'id' 		=> 'animate',
					'type' 		=> 'select',
					'title' 	=> __('Animation', 'mfn-opts'),
					'sub_desc' 	=> __('Entrance animation', 'mfn-opts'),
					'options' 	=> $mfn_animate,
				),
	
			),
		),
		
		// Portfolio --------------------------------------------
		'portfolio' => array(
			'type'		=> 'portfolio',
			'title'		=> __('Portfolio', 'mfn-opts'),
			'size'		=> '1/1',
			'fields'	=> array(
	
				array(
					'id'		=> 'count',
					'type'		=> 'text',
					'title'		=> __('Count', 'mfn-opts'),
					'std'		=> '2',
					'class'		=> 'small-text',
				),

				array(
					'id'		=> 'category',
					'type'		=> 'select',
					'title'		=> __('Category', 'mfn-opts'),
					'options'	=> mfn_get_categories( 'portfolio-types' ),
					'sub_desc'	=> __('Select the portfolio post category.', 'mfn-opts'),
				),

				array(
					'id'		=> 'category_multi',
					'type'		=> 'text',
					'title'		=> __('Multiple Categories', 'mfn-opts'),
					'sub_desc'	=> __('Categories Slugs', 'mfn-opts'),
					'desc'		=> __('Slugs should be separated with <strong>coma</strong> (,).', 'mfn-opts'),
				),

				array(
					'id'		=> 'style',
					'type'		=> 'select',
					'title'		=> 'Style',
					'options' 	=> array(
						'list'			=> 'List',
						'flat'			=> 'Flat',
						'grid'			=> 'Grid',
						'masonry'		=> 'Masonry',
						'masonry-flat'	=> 'Masonry Flat',
					),
					'std' 		=> 'grid'	
				),
				
				array(
					'id'		=> 'greyscale',
					'type'		=> 'select',
					'title'		=> 'Greyscale Images',
					'options' 	=> array( 0 => 'No', 1 => 'Yes' ),
				),
				
				array(
					'id'		=> 'orderby',
					'type'		=> 'select',
					'title'		=> __('Order by', 'mfn-opts'),
					'sub_desc'	=> __('Portfolio items order by column.', 'mfn-opts'),
					'options' 	=> array(
						'date'			=> 'Date', 
						'menu_order' 	=> 'Menu order',			
						'title'			=> 'Title',
						'rand'			=> 'Random',
					),
					'std'		=> 'date'
				),
				
				array(
					'id'		=> 'order',
					'type'		=> 'select',
					'title'		=> __('Order', 'mfn-opts'),
					'sub_desc'	=> __('Portfolio items order.', 'mfn-opts'),
					'options'	=> array(
						'ASC' 	=> 'Ascending',
						'DESC' 	=> 'Descending',
					),
					'std'		=> 'DESC'
				),

				array(
					'id' 		=> 'pagination',
					'type' 		=> 'select',
					'options' 	=> array( 0 => 'No', 1 => 'Yes' ),
					'title' 	=> __('Show pagination', 'mfn-opts'),
					'desc'		=> __('<strong>Notice:</strong> Pagination will <strong>not</strong> work if you put item on Homepage of WordPress Multilangual Site.', 'mfn-opts'),
				),
				

				array(
					'id' 		=> 'load_more',
					'type' 		=> 'select',
					'title' 	=> __('Load More button', 'mfn-opts'),
					'sub_desc' 	=> __('Show Ajax Load More button', 'mfn-opts'),
					'desc' 		=> __('This will replace all sliders on list with featured images', 'mfn-opts'),
					'options' 	=> array( 0 => 'No', 1 => 'Yes' ),
				),
	
			),
		),
		
		// Portfolio Grid --------------------------------------------
		'portfolio_grid' => array(
			'type'		=> 'portfolio_grid',
			'title'		=> __('Portfolio Grid', 'mfn-opts'),
			'size'		=> '1/4',
			'fields'	=> array(
			
				array(
					'id'		=> 'count',
					'type'		=> 'text',
					'title'		=> __('Count', 'mfn-opts'),
					'std'		=> '4',
					'class'		=> 'small-text',
				),
		
				array(
					'id'		=> 'category',
					'type'		=> 'select',
					'title'		=> __('Category', 'mfn-opts'),
					'options'	=> mfn_get_categories( 'portfolio-types' ),
					'sub_desc'	=> __('Select the portfolio post category.', 'mfn-opts'),
				),
				
				array(
					'id'		=> 'category_multi',
					'type'		=> 'text',
					'title'		=> __('Multiple Categories', 'mfn-opts'),
					'sub_desc'	=> __('Categories Slugs', 'mfn-opts'),
					'desc'		=> __('Slugs should be separated with <strong>coma</strong> (,).', 'mfn-opts'),
				),
		
				array(
					'id'		=> 'orderby',
					'type'		=> 'select',
					'title'		=> __('Order by', 'mfn-opts'),
					'sub_desc'	=> __('Portfolio items order by column.', 'mfn-opts'),
					'options' 	=> array(
						'date'			=> 'Date', 
						'menu_order' 	=> 'Menu order',			
						'title'			=> 'Title',
						'rand'			=> 'Random',
					),
					'std'		=> 'date'
				),
				
				array(
					'id'		=> 'order',
					'type'		=> 'select',
					'title'		=> __('Order', 'mfn-opts'),
					'sub_desc'	=> __('Portfolio items order.', 'mfn-opts'),
					'options'	=> array(
						'ASC' 	=> 'Ascending',
						'DESC' 	=> 'Descending',
					),
					'std'		=> 'DESC'
				),
				
				array(
					'id'		=> 'greyscale',
					'type'		=> 'select',
					'title'		=> 'Greyscale Images',
					'options' 	=> array( 0 => 'No', 1 => 'Yes' ),
				),

			),
		),
		
		// Portfolio Photo --------------------------------------------
		'portfolio_photo' => array(
			'type'		=> 'portfolio_photo',
			'title'		=> __('Portfolio Photo', 'mfn-opts'),
			'size'		=> '1/1',
			'fields'	=> array(
						
				array(
					'id'		=> 'count',
					'type'		=> 'text',
					'title'		=> __('Count', 'mfn-opts'),
					'std'		=> '5',
					'class'		=> 'small-text',
				),

				array(
					'id'		=> 'category',
					'type'		=> 'select',
					'title'		=> __('Category', 'mfn-opts'),
					'options'	=> mfn_get_categories( 'portfolio-types' ),
					'sub_desc'	=> __('Select the portfolio post category.', 'mfn-opts'),
				),

				array(
					'id'		=> 'category_multi',
					'type'		=> 'text',
					'title'		=> __('Multiple Categories', 'mfn-opts'),
					'sub_desc'	=> __('Categories Slugs', 'mfn-opts'),
					'desc'		=> __('Slugs should be separated with <strong>coma</strong> (,).', 'mfn-opts'),
				),
	
				array(
					'id'		=> 'orderby',
					'type'		=> 'select',
					'title'		=> __('Order by', 'mfn-opts'),
					'sub_desc'	=> __('Portfolio items order by column.', 'mfn-opts'),
					'options'	=> array('date'=>'Date', 'menu_order' => 'Menu order', 'title'=>'Title'),
					'std'		=> 'date'
				),

				array(
					'id'		=> 'order',
					'type'		=> 'select',
					'title'		=> __('Order', 'mfn-opts'),
					'sub_desc'	=> __('Portfolio items order.', 'mfn-opts'),
					'options'	=> array('ASC' => 'Ascending', 'DESC' => 'Descending'),
					'std'		=> 'DESC'
				),
				
				array(
					'id'		=> 'greyscale',
					'type'		=> 'select',
					'title'		=> 'Greyscale Images',
					'options' 	=> array( 0 => 'No', 1 => 'Yes' ),
				),
	
			),
		),
		
		// Portfolio Slider --------------------------------------------
		'portfolio_slider' => array(
			'type'		=> 'portfolio_slider',
			'title'		=> __('Portfolio Slider', 'mfn-opts'),
			'size'		=> '1/1',
			'fields'	=> array(
			
				array(
					'id'		=> 'count',
					'type'		=> 'text',
					'title'		=> __('Count', 'mfn-opts'),
					'desc'		=> __('We <strong>do not</strong> recommend use more than 10 items, because site will be working slowly.', 'mfn-opts'),
					'std'		=> '6',
					'class'		=> 'small-text',
				),
		
				array(
					'id'		=> 'category',
					'type'		=> 'select',
					'title'		=> __('Category', 'mfn-opts'),
					'options'	=> mfn_get_categories( 'portfolio-types' ),
					'sub_desc'	=> __('Select the portfolio post category.', 'mfn-opts'),
				),
				
				array(
					'id'		=> 'category_multi',
					'type'		=> 'text',
					'title'		=> __('Multiple Categories', 'mfn-opts'),
					'sub_desc'	=> __('Categories Slugs', 'mfn-opts'),
					'desc'		=> __('Slugs should be separated with <strong>coma</strong> (,).', 'mfn-opts'),
				),
		
				array(
					'id'		=> 'orderby',
					'type'		=> 'select',
					'title'		=> __('Order by', 'mfn-opts'),
					'sub_desc'	=> __('Portfolio items order by column.', 'mfn-opts'),
					'options'	=> array('date'=>'Date', 'menu_order' => 'Menu order', 'title'=>'Title'),
					'std'		=> 'date'
				),

				array(
					'id'		=> 'order',
					'type'		=> 'select',
					'title'		=> __('Order', 'mfn-opts'),
					'sub_desc'	=> __('Portfolio items order.', 'mfn-opts'),
					'options'	=> array('ASC' => 'Ascending', 'DESC' => 'Descending'),
					'std'		=> 'DESC'
				),

				array(
					'id'		=> 'arrows',
					'type'		=> 'select',
					'title'		=> __('Navigation Arrows', 'mfn-opts'),
					'sub_desc'	=> __('Show Navigation Arrows', 'mfn-opts'),
					'options'	=> array(
						''			=> 'None',
						'hover' 	=> 'Show on hover',
						'always' 	=> 'Always show',
					),
					'std'		=> 'DESC'
				),

			),
		),
		
		// Pricing item --------------------------------------------
		'pricing_item' => array(
			'type' => 'pricing_item',
			'title' => __('Pricing Item', 'mfn-opts'), 
			'size' => '1/4',
			'fields' => array(
		
				array(
					'id' 		=> 'image',
					'type' 		=> 'upload',
					'title' 	=> __('Image', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'title',
					'type' 		=> 'text',
					'title' 	=> __('Title', 'mfn-opts'),
					'sub_desc' 	=> __('Pricing item title', 'mfn-opts'),
				),

				array(
					'id' 		=> 'price',
					'type' 		=> 'text',
					'title' 	=> __('Price', 'mfn-opts'),
					'class' 	=> 'small-text',
				),
				
				array(
					'id' 		=> 'currency',
					'type'		=> 'text',
					'title' 	=> __('Currency', 'mfn-opts'),
					'class' 	=> 'small-text',
				),
					
				array(
					'id' 		=> 'period',
					'type' 		=> 'text',
					'title' 	=> __('Period', 'mfn-opts'),
					'class' 	=> 'small-text',
				),
				
				array(
					'id'		=> 'subtitle',
					'type'		=> 'text',
					'title'		=> __('Subtitle', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'content',
					'type' 		=> 'textarea',
					'title' 	=> __('Content', 'mfn-opts'),
					'desc' 		=> __('HTML tags allowed.', 'mfn-opts'),
					'std' 		=> '<ul><li><strong>List</strong> item</li></ul>',
				),
				
				array(
					'id' 		=> 'link_title',
					'type' 		=> 'text',
					'title' 	=> __('Link title', 'mfn-opts'),
					'desc' 		=> __('Link will appear only if this field will be filled.', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'link',
					'type' 		=> 'text',
					'title' 	=> __('Link', 'mfn-opts'),
					'desc' 		=> __('Link will appear only if this field will be filled.', 'mfn-opts'),
				),

				array(
					'id' 		=> 'featured',
					'type' 		=> 'select',
					'title' 	=> __('Featured', 'mfn-opts'),
					'options' 	=> array( 0 => 'No', 1 => 'Yes' ),
				),
				
				array(
					'id' 		=> 'style',
					'type' 		=> 'select',
					'title' 	=> __('Style', 'mfn-opts'),
					'options' 	=> array( 
						'box'	=> 'Box',
						'label'	=> 'Table Label',
						'table'	=> 'Table',	
					),
				),
				
				array(
					'id' 		=> 'animate',
					'type' 		=> 'select',
					'title' 	=> __('Animation', 'mfn-opts'),
					'sub_desc' 	=> __('Entrance animation', 'mfn-opts'),
					'options' 	=> $mfn_animate,
				),
				
			),														
		),
		
		// Progress Bars  --------------------------------------------
		'progress_bars' => array(
			'type' => 'progress_bars',
			'title' => __('Progress Bars', 'mfn-opts'),
			'size' => '1/4',
			'fields' => array(
	
				array(
					'id' 		=> 'title',
					'type' 		=> 'text',
					'title' 	=> __('Title', 'mfn-opts'),
				),
					
				array(
					'id' 		=> 'content',
					'type' 		=> 'textarea',
					'title' 	=> __('Content', 'mfn-opts'),
					'desc' 		=> __('Please use <strong>[bar title="Title" value="50"]</strong> shortcodes here.', 'mfn-opts'),
					'std' 		=> '[bar title="Bar1" value="50"]'."\n".'[bar title="Bar2" value="60"]',
				),
	
			),
		),
		
		// Promo Box --------------------------------------------
		'promo_box' => array(
			'type'		=> 'promo_box',
			'title'		=> __('Promo Box', 'mfn-opts'),
			'size'		=> '1/2',
			'fields'	=> array(

				array(
					'id'		=> 'image',
					'type'		=> 'upload',
					'title'		=> __('Image', 'mfn-opts'),
				),
				
				array(
					'id'		=> 'title',
					'type'		=> 'text',
					'title'		=> __('Title', 'mfn-opts'),
				),
				
				array(
					'id'		=> 'content',
					'type'		=> 'textarea',
					'title'		=> __('Content', 'mfn-opts'),
					'desc' 		=> __('Some Shortcodes and HTML tags allowed', 'mfn-opts'),
					'class'		=> 'full-width sc',
				),
				
				array(
					'id' 		=> 'btn_text',
					'type' 		=> 'text',
					'title' 	=> __('Button Text', 'mfn-opts'),
					'class' 	=> 'small-text',
				),
				array(
					'id' 		=> 'btn_link',
					'type' 		=> 'text',
					'title' 	=> __('Button Link', 'mfn-opts'),
					'class' 	=> 'small-text',
				),
				
				array(
					'id' 		=> 'target',
					'type' 		=> 'select',
					'title' 	=> __('Open in new window', 'mfn-opts'),
					'desc' 		=> __('Adds a target="_blank" attribute to the link.', 'mfn-opts'),
					'options' 	=> array( 0 => 'No', 1 => 'Yes' ),
				),
				
				array(
					'id' 		=> 'position',
					'type' 		=> 'select',
					'title' 	=> __('Image position', 'mfn-opts'),
					'options' 	=> array(
						'left' 	=> 'Left',
						'right' => 'Right'
					),
					'std'		=> 'left',
				),
				
				array(
					'id' 		=> 'border',
					'type' 		=> 'select',
					'title' 	=> __('Border', 'mfn-opts'),
					'sub_desc' 	=> __('Show right border', 'mfn-opts'),
					'options' 	=> array( 0 => 'No', 1 => 'Yes' ),
					'std'		=> 'no_border',
				),

				array(
					'id' 		=> 'animate',
					'type' 		=> 'select',
					'title' 	=> __('Animation', 'mfn-opts'),
					'sub_desc' 	=> __('Entrance animation', 'mfn-opts'),
					'options' 	=> $mfn_animate,
				),

			),
		),
		
		// Quick Fact --------------------------------------------
		'quick_fact' => array(
			'type' => 'quick_fact',
			'title' => __('Quick Fact', 'mfn-opts'),
			'size' => '1/4',
			'fields' => array(
	
				array(
					'id' 		=> 'heading',
					'type' 		=> 'text',
					'title' 	=> __('Heading', 'mfn-opts'),
				),
			
				array(
					'id' 		=> 'number',
					'type' 		=> 'text',
					'title'		=> __('Number', 'mfn-opts'),
					'class'		=> 'small-text',
				),

				array(
					'id' 		=> 'title',
					'type' 		=> 'text',
					'title' 	=> __('Title', 'mfn-opts'),
				),

				array(
					'id' 		=> 'content',
					'type' 		=> 'textarea',
					'title' 	=> __('Content', 'mfn-opts'),
					'desc' 		=> __('Some Shortcodes and HTML tags allowed', 'mfn-opts'),
					'class'		=> 'full-width sc',
					'validate' 	=> 'html',
				),
				
				array(
					'id' 		=> 'animate',
					'type' 		=> 'select',
					'title' 	=> __('Animation', 'mfn-opts'),
					'sub_desc' 	=> __('Entrance animation', 'mfn-opts'),
					'options' 	=> $mfn_animate,
				),
	
			),
		),
		
		// Shop Slider --------------------------------------------
		'shop_slider' => array(
			'type' 		=> 'shop_slider',
			'title' 	=> __('Shop Slider', 'mfn-opts'),
			'size' 		=> '1/4',
			'fields' 	=> array(
					
				array(
					'id' 		=> 'title',
					'type' 		=> 'text',
					'title' 	=> __('Title', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'count',
					'type' 		=> 'text',
					'title' 	=> __('Count', 'mfn-opts'),
					'sub_desc' 	=> __('Number of posts to show', 'mfn-opts'),
					'desc'		=> __('We <strong>do not</strong> recommend use more than 10 items, because site will be working slowly.', 'mfn-opts'),
					'std' 		=> '5',
					'class' 	=> 'small-text',
				),
			
				array(
					'id' 		=> 'category',
					'type' 		=> 'select',
					'title'		=> __('Category', 'mfn-opts'),
					'options'	=> mfn_get_categories( 'product_cat' ),
					'sub_desc'	=> __('Select the products category', 'mfn-opts'),
				),

				array(
					'id' 		=> 'orderby',
					'type' 		=> 'select',
					'title' 	=> __('Order by', 'mfn-opts'),
					'sub_desc' 	=> __('Slides order by column', 'mfn-opts'),
					'options' 	=> array('date'=>'Date', 'menu_order' => 'Menu order', 'title'=>'Title'),
					'std' 		=> 'date'
				),

				array(
					'id' 		=> 'order',
					'type' 		=> 'select',
					'title' 	=> __('Order', 'mfn-opts'),
					'sub_desc' 	=> __('Slides order', 'mfn-opts'),
					'options' 	=> array('ASC' => 'Ascending', 'DESC' => 'Descending'),
					'std' 		=> 'DESC'
				),
	
			),
		),
		
		// Sidebar Widget --------------------------------------------
		'sidebar_widget' => array(
			'type' 		=> 'sidebar_widget',
			'title' 	=> __('Sidebar Widget', 'mfn-opts'),
			'size' 		=> '1/4',
			'fields' 	=> array(
				
				array(
					'id'		=> 'sidebar',
					'type' 		=> 'select',
					'title' 	=> __('Select Sidebar', 'mfn-opts'),
					'desc' 		=> __('1. Create Sidebar in Theme Options > Getting Started > Sidebars.<br />2. Add Widget.<br />3. Select your sidebar.', 'mfn-opts'),
					'options' 	=> mfn_opts_get( 'sidebars' ),
				),
	
			),
		),
		
		// Slider --------------------------------------------
		'slider' => array(
			'type' 		=> 'slider',
			'title' 	=> __('Slider', 'mfn-opts'),
			'size' 		=> '1/1',
			'fields' 	=> array(
				
				array(
					'id' 		=> 'style',
					'type' 		=> 'select',
					'options' 	=> array(
						''			=> 'Default',
						'flat' 		=> 'Flat',
					),
					'title' 	=> __('Style', 'mfn-opts'),
				),
			
				array(
					'id' 		=> 'category',
					'type' 		=> 'select',
					'title'		=> __('Category', 'mfn-opts'),
					'options'	=> mfn_get_categories( 'slide-types' ),
					'sub_desc'	=> __('Select the slides category', 'mfn-opts'),
				),

				array(
					'id' 		=> 'orderby',
					'type' 		=> 'select',
					'title' 	=> __('Order by', 'mfn-opts'),
					'sub_desc' 	=> __('Slides order by column', 'mfn-opts'),
					'options' 	=> array('date'=>'Date', 'menu_order' => 'Menu order', 'title'=>'Title'),
					'std' 		=> 'date'
				),

				array(
					'id' 		=> 'order',
					'type' 		=> 'select',
					'title' 	=> __('Order', 'mfn-opts'),
					'sub_desc' 	=> __('Slides order', 'mfn-opts'),
					'options' 	=> array('ASC' => 'Ascending', 'DESC' => 'Descending'),
					'std' 		=> 'DESC'
				),
	
			),
		),
		
		// Slider Plugin --------------------------------------------
		'slider_plugin' => array(
			'type' 		=> 'slider_plugin',
			'title' 	=> __('Slider Plugin', 'mfn-opts'),
			'size' 		=> '1/4',
			'fields' 	=> array(
				
				array(
					'id' 		=> 'rev',
					'type' 		=> 'select',
					'title' 	=> __('Slider | Revolution Slider', 'mfn-opts'),
					'desc' 		=> __('Select one from the list of available <a target="_blank" href="admin.php?page=revslider">Revolution Sliders</a>', 'mfn-opts'),
					'options' 	=> mfn_get_sliders(),
				),
				
				array(
					'id' 		=> 'layer',
					'type' 		=> 'select',
					'title' 	=> __('Slider | Layer Slider', 'mfn-opts'), 
					'desc' 		=> __('Select one from the list of available <a target="_blank" href="admin.php?page=layerslider">Layer Sliders</a>', 'mfn-opts'),
					'options' 	=> mfn_get_sliders_layer(),
				),
	
			),
		),
		
		// Sliding Box --------------------------------------------
		'sliding_box' => array(
			'type' => 'sliding_box',
			'title' => __('Sliding Box', 'mfn-opts'),
			'size' => '1/4',
			'fields' => array(
	
				array(
					'id' 		=> 'image',
					'type' 		=> 'upload',
					'title'		=> __('Image', 'mfn-opts'),
				),

				array(
					'id' 		=> 'title',
					'type' 		=> 'text',
					'title' 	=> __('Title', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'link',
					'type' 		=> 'text',
					'title' 	=> __('Link', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'target',
					'type' 		=> 'select',
					'options' 	=> array( 0 => 'No', 1 => 'Yes' ),
					'title'		=> __('Open in new window', 'mfn-opts'),
					'desc' 		=> __('Adds a target="_blank" attribute to the link.', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'animate',
					'type' 		=> 'select',
					'title' 	=> __('Animation', 'mfn-opts'),
					'sub_desc' 	=> __('Entrance animation', 'mfn-opts'),
					'options' 	=> $mfn_animate,
				),
	
			),
		),
		
		// Tabs --------------------------------------------
		'tabs' => array(
			'type' => 'tabs',
			'title' => __('Tabs', 'mfn-opts'), 
			'size' => '1/4', 
			'fields' => array(
		
				array(
					'id' 		=> 'title',
					'type' 		=> 'text',
					'title' 	=> __('Title', 'mfn-opts'),
				),
			
				array(
					'id' 		=> 'tabs',
					'type' 		=> 'tabs',
					'title' 	=> __('Tabs', 'mfn-opts'),
					'sub_desc' 	=> __('To add an <strong>icon</strong> in Title field, please use the following code:<br/><br/>&lt;i class=" icon-lamp"&gt;&lt;/i&gt; Tab Title', 'mfn-opts'),
					'desc' 		=> __('You can use Drag & Drop to set the order.', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'type',
					'type' 		=> 'select',
					'options' 	=> array(
						'horizontal'	=> 'Horizontal',
						'vertical' 		=> 'Vertical', 
					),
					'title' 	=> __('Style', 'mfn-opts'),
					'desc' 		=> __('Vertical tabs works only for column widths: 1/2, 3/4 & 1/1', 'mfn-opts'),
				),
				
				array(
					'id'		=> 'uid',
					'type'		=> 'text',
					'title'		=> __('Unique ID [optional]', 'mfn-opts'),
					'sub_desc'	=> __('Allowed characters: "a-z" "-" "_"', 'mfn-opts'),
					'desc'		=> __('Use this option if you want to open specified tab from link.<br />For example: Your Unique ID is <strong>offer</strong> and you want to open 2nd tab, please use link: <strong>your-url/#offer-2</strong>', 'mfn-opts'),
				),
				
			),															
		),
			
		// Testimonials --------------------------------------------
		'testimonials' => array(
			'type' => 'testimonials',
			'title' => __('Testimonials', 'mfn-opts'),
			'size' => '1/1',
			'fields' => array(
				
				array(
					'id' 		=> 'category',
					'type' 		=> 'select',
					'title'		=> __('Category', 'mfn-opts'),
					'options'	=> mfn_get_categories( 'testimonial-types' ),
					'sub_desc'	=> __('Select the testimonial post category.', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'orderby',
					'type' 		=> 'select',
					'title' 	=> __('Order by', 'mfn-opts'),
					'sub_desc' 	=> __('Testimonials order by column.', 'mfn-opts'),
					'options' 	=> array('date'=>'Date', 'menu_order' => 'Menu order', 'title'=>'Title'),
					'std' 		=> 'date'
				),
				
				array(
					'id' 		=> 'order',
					'type' 		=> 'select',
					'title' 	=> __('Order', 'mfn-opts'),
					'sub_desc' 	=> __('Testimonials order.', 'mfn-opts'),
					'options' 	=> array('ASC' => 'Ascending', 'DESC' => 'Descending'),
					'std' 		=> 'DESC'
				),
				
				array(
					'id' 		=> 'hide_photos',
					'type' 		=> 'select',
					'options' 	=> array( 0 => 'No', 1 => 'Yes' ),
					'title'		=> __('Hide Photos', 'mfn-opts'),
				),
	
			),
		),
		
		// Testimonials List --------------------------------------------
		'testimonials_list' => array(
			'type' => 'testimonials_list',
			'title' => __('Testimonials List', 'mfn-opts'),
			'size' => '1/1',
			'fields' => array(
				
				array(
					'id' 		=> 'category',
					'type' 		=> 'select',
					'title'		=> __('Category', 'mfn-opts'),
					'options'	=> mfn_get_categories( 'testimonial-types' ),
					'sub_desc'	=> __('Select the testimonial post category.', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'orderby',
					'type' 		=> 'select',
					'title' 	=> __('Order by', 'mfn-opts'),
					'sub_desc' 	=> __('Testimonials order by column.', 'mfn-opts'),
					'options' 	=> array('date'=>'Date', 'menu_order' => 'Menu order', 'title'=>'Title'),
					'std' 		=> 'date'
				),
				
				array(
					'id' 		=> 'order',
					'type' 		=> 'select',
					'title' 	=> __('Order', 'mfn-opts'),
					'sub_desc' 	=> __('Testimonials order.', 'mfn-opts'),
					'options' 	=> array('ASC' => 'Ascending', 'DESC' => 'Descending'),
					'std' 		=> 'DESC'
				),
	
			),
		),
		
		// Timeline --------------------------------------------
		'timeline' => array(
			'type' => 'timeline',
			'title' => __('Timeline', 'mfn-opts'),
			'size' => '1/1',
			'fields' => array(
		
				array(
					'id' => 'tabs',
					'type' => 'tabs',
					'title' => __('Timeline', 'mfn-opts'),
					'sub_desc' => __('Please add <strong>date</strong> wrapped into <strong>span</strong> tag in Title field.<br/><br/>&lt;span&gt;2013&lt;/span&gt;Event Title', 'mfn-opts'),
					'desc' => __('You can use Drag & Drop to set the order.', 'mfn-opts'),
				),
		
			),
		),
		
		// Trailer Box --------------------------------------------
		'trailer_box' => array(
			'type' => 'trailer_box',
			'title' => __('Trailer Box', 'mfn-opts'),
			'size' => '1/4',
			'fields' => array(
	
				array(
					'id' 		=> 'image',
					'type' 		=> 'upload',
					'title'		=> __('Image', 'mfn-opts'),
				),

				array(
					'id' 		=> 'slogan',
					'type' 		=> 'text',
					'title' 	=> __('Slogan', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'title',
					'type' 		=> 'text',
					'title' 	=> __('Title', 'mfn-opts'),
				),

				array(
					'id' 		=> 'link',
					'type' 		=> 'text',
					'title' 	=> __('Link', 'mfn-opts'),
				),

				array(
					'id' 		=> 'target',
					'type' 		=> 'select',
					'options' 	=> array( 0 => 'No', 1 => 'Yes' ),
					'title'		=> __('Open in new window', 'mfn-opts'),
					'desc' 		=> __('Adds a target="_blank" attribute to the link.', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'animate',
					'type' 		=> 'select',
					'title' 	=> __('Animation', 'mfn-opts'),
					'sub_desc' 	=> __('Entrance animation', 'mfn-opts'),
					'options' 	=> $mfn_animate,
				),
	
			),
		),
		
		// Video  --------------------------------------------
		'video' => array(
			'type' => 'video',
			'title' => __('Video', 'mfn-opts'), 
			'size' => '1/4', 
			'fields' => array(
		
				array(
					'id' 		=> 'video',
					'type' 		=> 'text',
					'title' 	=> __('Video ID', 'mfn-opts'),
					'sub_desc' 	=> __('YouTube or Vimeo', 'mfn-opts'),
					'desc' 		=> __('It`s placed in every YouTube & Vimeo video, for example:<br /><br /><b>YouTube:</b> http://www.youtube.com/watch?v=<u>WoJhnRczeNg</u><br /><b>Vimeo:</b> http://vimeo.com/<u>62954028</u>', 'mfn-opts'),
					'class' 	=> 'small-text'
				),
				
				array(
					'id'		=> 'mp4',
					'type'		=> 'upload',
					'title'		=> __('HTML5 mp4 video', 'mfn-opts'),
					'sub_desc'	=> __('m4v [.mp4]', 'mfn-opts'),
					'desc'		=> __('Please add both mp4 and ogv for cross-browser compatibility.', 'mfn-opts'),
					'class'		=> __('video', 'mfn-opts'),
				),
				
				array(
					'id'		=> 'ogv',
					'type'		=> 'upload',
					'title'		=> __('HTML5 ogv video', 'mfn-opts'),
					'sub_desc'	=> __('ogg [.ogv]', 'mfn-opts'),
					'class'		=> __('video', 'mfn-opts'),
				),
				
				array(
					'id'		=> 'placeholder',
					'type'		=> 'upload',
					'title'		=> __('HTML5 placeholder image', 'mfn-opts'),
					'desc'		=> __('Placeholder Image will be used as video placeholder before video loads and on mobile devices.', 'mfn-opts'),
				),
				
				array(
					'id' 		=> 'width',
					'type' 		=> 'text',
					'title' 	=> __('Width', 'mfn-opts'),
					'desc' 		=> __('px', 'mfn-opts'),
					'class' 	=> 'small-text',
					'std' 		=> 700,
				),
				
				array(
					'id' 		=> 'height',
					'type' 		=> 'text',
					'title' 	=> __('Height', 'mfn-opts'),
					'desc' 		=> __('px', 'mfn-opts'),
					'class' 	=> 'small-text',
					'std' 		=> 400,
				),
				
			),	
		),
		
		// Visual Editor  --------------------------------------------
		'visual' => array(
			'type' => 'visual',
			'title' => __('Visual Editor', 'mfn-opts'),
			'size' => '1/4',
			'fields' => array(
	
				array(
					'id' 		=> 'title',
					'type' 		=> 'text',
					'title' 	=> __('Title', 'mfn-opts'),
					'desc' 		=> __('This field is used as an Item Label in admin panel only and shows after page update.', 'mfn-opts'),
				),
					
				array(
					'id' 		=> 'content',
					'type' 		=> 'textarea',
					'title' 	=> __('Visual Editor', 'mfn-opts'),
					'param' 	=> 'editor',
					'validate' 	=> 'html',
				),
	
			),
		),
		
	);
	
	// GET Sections & Items
	$mfn_items_serial = get_post_meta($post->ID, 'mfn-page-items', true);
	$mfn_tmp_fn = 'base'.'64_decode';
	$mfn_items = unserialize(call_user_func($mfn_tmp_fn, $mfn_items_serial));

	// OLD Content Builder 1.0 Compatibility
	if( is_array( $mfn_items ) && ! key_exists( 'attr', $mfn_items[0] ) ){
		$mfn_items_builder2 = array(
			'attr'	=> $mfn_std_section,
			'items'	=> $mfn_items
		);
		$mfn_items = array( $mfn_items_builder2 );
	}

// 	print_r($mfn_items);
	$mfn_sections_count = is_array( $mfn_items ) ? count( $mfn_items ) : 0;

	// builder visibility
	if( $visibility = mfn_opts_get('builder-visibility') ){
		if( $visibility == 'hide' || ( ! current_user_can( $visibility ) ) ){
			return false;
		}
	}
	
	?>
	<div id="mfn-builder">
		<input type="hidden" id="mfn-row-id" value="<?php echo $mfn_sections_count; ?>" />
		<a id="mfn-go-to-top" href="javascript:void(0);">Go to top</a>
	
		<div id="mfn-content">

			<!-- .mfn-row-add ================================================ -->			
			<div class="mfn-row-add">
				<table class="form-table">
					<tbody>
						<tr valign="top">
							<td>
								<a class="btn-blue mfn-row-add-btn" href="javascript:void(0);"><em></em>Add Section</a>
								<div class="logo">Muffin Group | Muffin Builder</div>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
			
			
			<!-- #mfn-desk ================================================== -->
			<div id="mfn-desk" class="clearfix">
			
				<?php
					for( $i = 0; $i < $mfn_sections_count; $i++ ) {
						mfn_builder_section( $mfn_std_items, $mfn_std_section, $mfn_items[$i], $i+1 );
					}
				?>
			
			</div>
			
			
			<!-- #mfn-rows ================================================= -->
			<div id="mfn-rows" class="clearfix">
				<?php mfn_builder_section( $mfn_std_items, $mfn_std_section ); ?>
			</div>
						
			
			<!-- #mfn-items =============================================== -->
			<div id="mfn-items" class="clearfix">
				<?php
					foreach( $mfn_std_items as $item ){
						mfn_builder_item( $item );
					}
				?>				
			</div>
			
			<!-- #mfn-migrate -->
			<div id="mfn-migrate">
			
				<div class="btn-wrapper">
					<a href="javascript:void(0);" class="mfn-btn-migrate btn-exp"><?php _e('Export','mfn-opts'); ?></a>
					<a href="javascript:void(0);" class="mfn-btn-migrate btn-imp"><?php _e('Import','mfn-opts'); ?></a>
				</div>
				<div class="export-wrapper hide">
					<textarea id="mfn-items-export" placeholder="Please remember to Publish/Update your post before Export."><?php echo $mfn_items_serial; ?></textarea>
					<span class="description"><?php _e('Copy to clipboard: Ctrl+C (Cmd+C for Mac)','mfn-opts'); ?></span>
				</div>
				
				<div class="import-wrapper hide">
					<textarea id="mfn-items-import" placeholder="Paste import data here."></textarea>
					<a href="javascript:void(0);" class="mfn-btn-migrate btn-primary btn-import"><?php _e('Import','mfn-opts'); ?></a>				
				</div>
				
			</div>
	
		</div>
		
		<!-- #mfn-popup -->
		<div id="mfn-popup">
			<a href="javascript:void(0);" class="mfn-btn-close mfn-popup-close">Close</a>	
			<a href="javascript:void(0);" class="mfn-popup-save">Save changes</a>	
		</div>
		
		<!-- #mfn-items-seo -->
		<div id="mfn-items-seo">
			<?php 
				$mfn_items_seo = get_post_meta($post->ID, 'mfn-page-items-seo', true);
				echo '<textarea id="mfn-items-seo-data">'. $mfn_items_seo .'</textarea>'; 
			?>
		</div>
		
	</div>
	<?php 

}


/*-----------------------------------------------------------------------------------*/
/*	SAVE Muffin Builder
/*-----------------------------------------------------------------------------------*/
function mfn_builder_save($post_id) {

	$mfn_items = array();
// 	print_r($_POST);
	
	// sections loop -------------------------------------------------------------
	if( key_exists('mfn-row-id', $_POST) && is_array($_POST['mfn-row-id']))
	{
		// foreach $_POST['mfn-row-id']
		foreach( $_POST['mfn-row-id'] as $sectionID_k => $sectionID )
		{
			$section = array();
				
			// $section['attr'] - section attributes
			if( key_exists('mfn-rows', $_POST) && is_array($_POST['mfn-rows'])){
				foreach ( $_POST['mfn-rows'] as $section_attr_k => $section_attr ){
					$section['attr'][$section_attr_k] = $section_attr[$sectionID_k];
				}
			}
				
			// $section['items'] - section items will be added in the next foreach
			$section['items'] = '';
				
			$mfn_items[] = $section;
		}
	
		$newParentSectionIDs = array_flip( $_POST['mfn-row-id'] );
	}	
	
	// items loop ----------------------------------------------------------------
	if( key_exists('mfn-item-type', $_POST) && is_array($_POST['mfn-item-type']))
	{
		$count = array();
		$tabs_count = array();
	
		$seo_content = '';
		
		foreach( $_POST['mfn-item-type'] as $type_k => $type )
		{	
			$item = array();
			$item['type'] = $type;
			$item['size'] = $_POST['mfn-item-size'][$type_k];
				
			// init count for specified item type
			if( ! key_exists($type, $count) ){
				$count[$type] = 0;
			}
			
			// init count for specified tab type
			if( ! key_exists($type, $tabs_count) ){
				$tabs_count[$type] = 0;
			}
			
			if( key_exists($type, $_POST['mfn-items']) ){	
				foreach( (array) $_POST['mfn-items'][$type] as $attr_k => $attr ){

					if( $attr_k == 'tabs'){

						// accordion, faq & tabs ----------------------------
						$item['fields']['count'] = $attr['count'][$count[$type]];
						if( $item['fields']['count'] ){
							for ($i = 0; $i < $item['fields']['count']; $i++) {
								$tab = array();
								$tab['title'] = stripslashes($attr['title'][$tabs_count[$type]]);
								$tab['content'] = stripslashes($attr['content'][$tabs_count[$type]]);
								$item['fields']['tabs'][] = $tab;
								$tabs_count[$type]++;
							}
						}
					
					} else {
						$item['fields'][$attr_k] = stripslashes($attr[$count[$type]]);						
						
						// "Yoast SEO" fix
						$seo_val = trim( $attr[$count[$type]] );
						if( $seo_val && $seo_val != 1 ){
							$seo_content .= stripslashes( $seo_val ) ."\n\n";
						}
						
					}
					
				}
			}
				
			// increase count for specified item type
			$count[$type] ++;
				
			// new parent section ID
			$parentSectionID = $_POST['mfn-item-parent'][$type_k];
			$newParentSectionID = $newParentSectionIDs[$parentSectionID];
				
			// $section['items']
			$mfn_items[$newParentSectionID]['items'][] = $item;
		}
	}
// 	print_r($mfn_items);
	

	// save -----------------------------------------------
	if( $mfn_items )
	{
		$mfn_tmp_fn = 'base'.'64_encode';
		$new = call_user_func($mfn_tmp_fn, serialize($mfn_items));		
	}
	
	
	// migrate --------------------------------------------
	if( key_exists('mfn-items-import', $_POST) ){
		$new = htmlspecialchars(stripslashes( $_POST['mfn-items-import'] )) ;
	}

	
	// "quick edit" fix -----------------------------------
	if( key_exists('mfn-items', $_POST) )
	{
		$field['id'] = 'mfn-page-items';
		$old = get_post_meta($post_id, $field['id'], true);

		if( isset($new) && $new != $old ) {

			// update post meta if there is at least one builder section
			update_post_meta($post_id, $field['id'], $new);

		} elseif( '' == $new && $old ) {

			// delete post meta if builder is empty
			delete_post_meta($post_id, $field['id'], $old);
			
		}
		
		// "Yoast SEO" fix
		if( isset($new) ){
			update_post_meta($post_id, 'mfn-page-items-seo', $seo_content);
		}
		
	}

}


/*-----------------------------------------------------------------------------------*/
/*	PRINT Muffin Builder - FRONTEND
/*-----------------------------------------------------------------------------------*/
function mfn_builder_print( $post_id ) {

	// Sizes for Items
	$classes = array(
		'1/6' => 'one-sixth',
		'1/5' => 'one-fifth',
		'1/4' => 'one-fourth',
		'1/3' => 'one-third',
		'1/2' => 'one-second',
		'2/3' => 'two-third',
		'3/4' => 'three-fourth',
		'1/1' => 'one'
	);

	// Sidebars list
	$sidebars = mfn_opts_get( 'sidebars' );
	
	// GET Sections & Items
	$mfn_items = get_post_meta( $post_id, 'mfn-page-items', true );
	$mfn_tmp_fn = 'base'.'64_decode';
	$mfn_items = unserialize(call_user_func($mfn_tmp_fn, $mfn_items));
	
// 	print_r($mfn_items);
	
	// Content Builder
	if( post_password_required() ){
		
		// prevents duplication of the password form
		if( get_post_meta( $post_id, 'mfn-post-hide-content', true ) ){
			echo '<div class="section the_content">';
				echo '<div class="section_wrapper">';
					echo '<div class="the_content_wrapper">';
						echo get_the_password_form();
					echo '</div>';
				echo '</div>';
			echo '</div>';
		}

	} elseif( is_array( $mfn_items ) ){

		// Sections
		foreach( $mfn_items as $section ){
		
// 			print_r($section['attr']);

			// section attributes -----------------------------------

			// Sidebar for section -------------
			if( ( ! mfn_sidebar_classes() ) && // don't show sidebar for section if sidebar for page is set
				( ( $section['attr']['layout'] == 'right-sidebar' ) || ( $section['attr']['layout'] == 'left-sidebar' ) ) )
			{
				$section_sidebar = $section['attr']['layout'];
			} else {
				$section_sidebar = false;
			}

			// classes ------------------------
			$section_class 		= array();
			
			$section_class[]	= $section_sidebar;
			$section_class[]	= $section['attr']['style'];
			$section_class[]	= $section['attr']['class'];
			
			if( key_exists( 'visibility', $section['attr']) ){
				$section_class[] = $section['attr']['visibility'];
			}
			if( key_exists( 'bg_video_mp4', $section['attr'] ) && $section['attr']['bg_video_mp4'] ){
				 $section_class[] = 'has-video';
			}
			if( key_exists( 'navigation', $section['attr'] ) && $section['attr']['navigation'] ){
				 $section_class[] = 'has-navi';
			}
			
			$section_class		= implode(' ', $section_class);
		
			// styles -------------------------
			$section_style 		= '';

			$section_style[] 	= 'padding-top:'. intval( $section['attr']['padding_top'] ) .'px';
			$section_style[] 	= 'padding-bottom:'. intval( $section['attr']['padding_bottom'] ) .'px';
			$section_style[] 	= 'background-color:'. $section['attr']['bg_color'];
			
			// background image attributes
			if( $section['attr']['bg_image'] ){
				$section_style[] 	= 'background-image:url('. $section['attr']['bg_image'] .')';
				$section_bg_attr 	= explode(';', $section['attr']['bg_position']);
				$section_style[] 	= 'background-repeat:'. $section_bg_attr[0];
				$section_style[] 	= 'background-position:'. $section_bg_attr[1];
				$section_style[] 	= 'background-attachment:'. $section_bg_attr[2];
				$section_style[] 	= 'background-size:'. $section_bg_attr[3];
				$section_style[] 	= '-webkit-background-size:'. $section_bg_attr[3];
			}
			
			$section_style 		= implode('; ', $section_style );
			
			// parallax -------------------------
			$parallax = false;
			if( $section['attr']['bg_image'] && ( $section_bg_attr[2] == 'fixed' ) ){
				if( ! key_exists(4, $section_bg_attr) || $section_bg_attr[4] != 'still' ){
					$parallax = 'data-stellar-background-ratio="0.5"';
				}
			}
			
			// IDs ------------------------------
			if( key_exists('section_id', $section['attr']) && $section['attr']['section_id'] ){
				$section_id = 'id="'. $section['attr']['section_id'] .'"';
			} else {
				$section_id = false;
			}
			
			// print ------------------------------------------------
			
			echo '<div class="section '. $section_class .'" '. $section_id .' style="'. $section_style .'" '. $parallax .'>'; // 100%
			
				// Video ------------------------
				if( key_exists( 'bg_video_mp4', $section['attr'] ) && $mp4 = $section['attr']['bg_video_mp4'] ){
					echo '<div class="section_video">';
					
						echo '<div class="mask"></div>';
						
						$poster = $section['attr']['bg_image'];
						
						echo '<video poster="'. $poster .'" controls="controls" muted="muted" preload="auto" loop="true" autoplay="true">';
							
							echo '<source type="video/mp4" src="'. $mp4 .'" />';
							if( key_exists( 'bg_video_ogv', $section['attr'] ) && $ogv = $section['attr']['bg_video_ogv'] ){
								echo '<source type="video/ogg" src="'. $ogv .'" />';
							}
							
							echo '<object width="1900" height="1060" type="application/x-shockwave-flash" data="'. THEME_URI .'/js/flashmediaelement.swf">';
								echo '<param name="movie" value="'. THEME_URI .'/js/flashmediaelement.swf" />';
								echo '<param name="flashvars" value="controls=true&file='. $mp4 .'" />';
								echo '<img src="'. $poster .'" title="No video playback capabilities" />';
							echo '</object>';
							
						echo '</video>';
						
					echo '</div>';
				}
				
				// Separator ------------------------
				if( key_exists( 'divider', $section['attr'] ) && $divider = $section['attr']['divider'] ){
					echo '<div class="section-divider '. $divider .'"></div>';
				}
				
				// Navigation ------------------------
				if( key_exists( 'navigation', $section['attr'] ) && $divider = $section['attr']['navigation'] ){
					echo '<div class="section-nav prev"><i class="icon-up-open"></i></div>';
					echo '<div class="section-nav next"><i class="icon-down-open"></i></div>';
				}
			
				echo '<div class="section_wrapper clearfix">'; // WIDTH + margin: 0 auto
					
					// Items ------------------------
					echo '<div class="items_group clearfix">'; // 100% || WIDTH (sidebar)
						if( is_array( $section['items'] ) ){			
							foreach( $section['items'] as $item ){
							
								if( function_exists( 'mfn_print_'. $item['type'] ) ){
									
									$class  = $classes[$item['size']];		// size of item
									$class .= ' column_'. $item['type'];	// type of item
										
									echo '<div class="column '. $class .'">';
										call_user_func( 'mfn_print_'. $item['type'], $item );
									echo '</div>';
								}
		
							}
						}
					echo '</div>';
					
					// Sidebar for section -----------
					if( $section_sidebar ){
						echo '<div class="four columns section_sidebar">';
							echo '<div class="widget-area clearfix">';
								dynamic_sidebar( $sidebars[$section['attr']['sidebar']] );
							echo '</div>';
						echo '</div>';
					}
					
				echo '</div>';
			echo '</div>';
		}
	}
	
	// WordPress Editor Content -------------------------------------
	echo '<div class="section the_content">';
		if( ! get_post_meta( $post_id, 'mfn-post-hide-content', true )){
			if( $content = apply_filters('the_content', get_post_field('post_content', $post_id)) ){
				echo '<div class="section_wrapper">';
					echo '<div class="the_content_wrapper">';
//						the_content();
						echo $content;
					echo '</div>';
				echo '</div>';
			}
		}
	echo '</div>';
}


/*-----------------------------------------------------------------------------------*/
/*	PRINT Item - FRONTEND
/*-----------------------------------------------------------------------------------*/

// ---------- [accordion] -----------
function mfn_print_accordion( $item ) {
	echo sc_accordion( $item['fields'] );
}

// ---------- [article_box] -----------
function mfn_print_article_box( $item ) {
	echo sc_article_box( $item['fields'] );
}

// ---------- [blockquote] -----------
function mfn_print_blockquote( $item ) {
	if( ! key_exists('content', $item['fields']) ) $item['fields']['content'] = '';
	echo sc_blockquote( $item['fields'], $item['fields']['content'] );
}

// ---------- [blog] -----------
function mfn_print_blog( $item ) {
	echo sc_blog( $item['fields'] );
}

// ---------- [blog_slider] -----------
function mfn_print_blog_slider( $item ) {
	echo sc_blog_slider( $item['fields'] );
}

// ---------- [call_to_action] -----------
function mfn_print_call_to_action( $item ) {
	if( ! key_exists('content', $item['fields']) ) $item['fields']['content'] = '';
	echo sc_call_to_action( $item['fields'], $item['fields']['content'] );
}

// ---------- [chart] -----------
function mfn_print_chart( $item ) {
	echo sc_chart( $item['fields'] );
}

// ---------- [clients] -----------
function mfn_print_clients( $item ) {
	echo sc_clients( $item['fields'] );
}

// ---------- [clients_slider] -----------
function mfn_print_clients_slider( $item ) {
	echo sc_clients_slider( $item['fields'] );
}

// ---------- [code] -----------
function mfn_print_code( $item ) {
	if( ! key_exists('content', $item['fields']) ) $item['fields']['content'] = '';
	echo sc_code( $item['fields'], $item['fields']['content'] );
}

// ---------- [column] -----------
function mfn_print_column( $item ) {
	if( ! key_exists('content', $item['fields']) ) $item['fields']['content'] = '';
	
	$column_class 	= '';
	$column_attr 	= '';
	
	// align
	if( key_exists('align', $item['fields']) && $item['fields']['align'] ){
		$column_class	.= ' align_'. $item['fields']['align'];
	}	
	
	// animate
	if( key_exists('animate', $item['fields']) && $item['fields']['animate'] ){
		$column_class	.= ' animate';
		$column_attr	.= ' data-anim-type="'. $item['fields']['animate'] .'"'; 
	}	
	
	echo '<div class="column_attr '. $column_class .'" '. $column_attr .'>';
		echo do_shortcode( $item['fields']['content'] );
	echo '</div>';
}

// ---------- [contact_box] -----------
function mfn_print_contact_box( $item ) {
	echo sc_contact_box( $item['fields'] );
}

// ---------- [content] -----------
function mfn_print_content( $item ) {
	echo '<div class="the_content">';
		echo '<div class="the_content_wrapper">';
			the_content();
		echo '</div>';
	echo '</div>';
}

// ---------- [countdown] -----------
function mfn_print_countdown( $item ) {
	echo sc_countdown( $item['fields'] );
}

// ---------- [counter] -----------
function mfn_print_counter( $item ) {
	echo sc_counter( $item['fields'] );
}

// ---------- [divider] -----------
function mfn_print_divider( $item ) {
	echo sc_divider( $item['fields'] );
}

// ---------- [fancy_divider] -----------
function mfn_print_fancy_divider( $item ) {
	echo sc_fancy_divider( $item['fields'] );
}

// ---------- [fancy_heading] -----------
function mfn_print_fancy_heading( $item ) {
	if( ! key_exists('content', $item['fields']) ) $item['fields']['content'] = '';
	echo sc_fancy_heading( $item['fields'], $item['fields']['content'] );
}

// ---------- [faq] -----------
function mfn_print_faq( $item ) {
	echo sc_faq( $item['fields'] );
}

// ---------- [feature_list] -----------
function mfn_print_feature_list( $item ) {
	if( ! key_exists('content', $item['fields']) ) $item['fields']['content'] = '';
	echo sc_feature_list( $item['fields'], $item['fields']['content'] );
}

// ---------- [flat_box] -----------
function mfn_print_flat_box( $item ) {
	if( ! key_exists('content', $item['fields']) ) $item['fields']['content'] = '';
	echo sc_flat_box( $item['fields'], $item['fields']['content'] );
}

// ---------- [hover_box] -----------
function mfn_print_hover_box( $item ) {
	echo sc_hover_box( $item['fields'] );
}

// ---------- [hover_color] -----------
function mfn_print_hover_color( $item ) {
	if( ! key_exists('content', $item['fields']) ) $item['fields']['content'] = '';
	echo sc_hover_color( $item['fields'], $item['fields']['content'] );
}

// ---------- [how_it_works] -----------
function mfn_print_how_it_works( $item ) {
	if( ! key_exists('content', $item['fields']) ) $item['fields']['content'] = '';
	echo sc_how_it_works( $item['fields'], $item['fields']['content'] );
}

// ---------- [icon_box] -----------
function mfn_print_icon_box( $item ) {
	if( ! key_exists('content', $item['fields']) ) $item['fields']['content'] = '';
	echo sc_icon_box( $item['fields'], $item['fields']['content'] );
}

// ---------- [image] -----------
function mfn_print_image( $item ) {
	echo sc_image( $item['fields'] );
}

// ---------- [info_box] -----------
function mfn_print_info_box( $item ) {
	if( ! key_exists('content', $item['fields']) ) $item['fields']['content'] = '';
	echo sc_info_box( $item['fields'], $item['fields']['content'] );
}

// ---------- [list] -----------
function mfn_print_list( $item ) {
	if( ! key_exists('content', $item['fields']) ) $item['fields']['content'] = '';
	echo sc_list( $item['fields'], $item['fields']['content'] );
}

// ---------- [map] -----------
function mfn_print_map( $item ) {
	if( ! key_exists('content', $item['fields']) ) $item['fields']['content'] = '';
	echo sc_map( $item['fields'], $item['fields']['content'] );
}

// ---------- [offer] -----------
function mfn_print_offer( $item ) {
	echo sc_offer( $item['fields'] );
}

// ---------- [offer_thumb] -----------
function mfn_print_offer_thumb( $item ) {
	echo sc_offer_thumb( $item['fields'] );
}

// ---------- [opening_hours] -----------
function mfn_print_opening_hours( $item ) {
	if( ! key_exists('content', $item['fields']) ) $item['fields']['content'] = '';
	echo sc_opening_hours( $item['fields'], $item['fields']['content'] );
}

// ---------- [our_team] -----------
function mfn_print_our_team( $item ) {
	if( ! key_exists('content', $item['fields']) ) $item['fields']['content'] = '';
	echo sc_our_team( $item['fields'], $item['fields']['content'] );
}

// ---------- [our_team_list] -----------
function mfn_print_our_team_list( $item ) {
	if( ! key_exists('content', $item['fields']) ) $item['fields']['content'] = '';
	echo sc_our_team_list( $item['fields'], $item['fields']['content'] );
}

// ---------- [photo_box] -----------
function mfn_print_photo_box( $item ) {
	if( ! key_exists('content', $item['fields']) ) $item['fields']['content'] = '';
	echo sc_photo_box( $item['fields'], $item['fields']['content'] );
}

// ---------- [portfolio] -----------
function mfn_print_portfolio( $item ) {
	echo sc_portfolio( $item['fields'] );
}

// ---------- [portfolio_grid] -----------
function mfn_print_portfolio_grid( $item ) {
	echo sc_portfolio_grid( $item['fields'] );
}

// ---------- [portfolio_photo] -----------
function mfn_print_portfolio_photo( $item ) {
	echo sc_portfolio_photo( $item['fields'] );
}

// ---------- [portfolio_slider] -----------
function mfn_print_portfolio_slider( $item ) {
	echo sc_portfolio_slider( $item['fields'] );
}

// ---------- [pricing_item] -----------
function mfn_print_pricing_item( $item ) {
	if( ! key_exists('content', $item['fields']) ) $item['fields']['content'] = '';
	echo sc_pricing_item( $item['fields'], $item['fields']['content'] );
}

// ---------- [progress_bars] -----------
function mfn_print_progress_bars( $item ) {
	if( ! key_exists('content', $item['fields']) ) $item['fields']['content'] = '';
	echo sc_progress_bars( $item['fields'], $item['fields']['content'] );
}

// ---------- [promo_box] -----------
function mfn_print_promo_box( $item ) {
	if( ! key_exists('content', $item['fields']) ) $item['fields']['content'] = '';
	echo sc_promo_box( $item['fields'], $item['fields']['content'] );
}

// ---------- [quick_fact] -----------
function mfn_print_quick_fact( $item ) {
	if( ! key_exists('content', $item['fields']) ) $item['fields']['content'] = '';
	echo sc_quick_fact( $item['fields'], $item['fields']['content'] );
}

// ---------- [shop_slider] -----------
function mfn_print_shop_slider( $item ) {
	echo sc_shop_slider( $item['fields'] );
}

// ---------- [sidebar_widget] -----------
function mfn_print_sidebar_widget( $item ) {
	echo sc_sidebar_widget( $item['fields'] );
}

// ---------- [slider] -----------
function mfn_print_slider( $item ) {
	echo sc_slider( $item['fields'] );
}

// ---------- [slider_plugin] -----------
function mfn_print_slider_plugin( $item ) {
	echo sc_slider_plugin( $item['fields'] );
}

// ---------- [sliding_box] -----------
function mfn_print_sliding_box( $item ) {
	echo sc_sliding_box( $item['fields'] );
}

// ---------- [tabs] -----------
function mfn_print_tabs( $item ) {
	echo sc_tabs( $item['fields'] );
}

// ---------- [testimonials] -----------
function mfn_print_testimonials( $item ) {
	echo sc_testimonials( $item['fields'] );
}

// ---------- [testimonials_list] -----------
function mfn_print_testimonials_list( $item ) {
	echo sc_testimonials_list( $item['fields'] );
}

// ---------- [timeline] -----------
function mfn_print_timeline( $item ) {
	echo sc_timeline( $item['fields'] );
}

// ---------- [trailer_box] -----------
function mfn_print_trailer_box( $item ) {
	echo sc_trailer_box( $item['fields'] );
}

// ---------- [video] -----------
function mfn_print_video( $item ) {
	echo sc_video( $item['fields'] );
}

// ---------- [visual] -----------
function mfn_print_visual( $item ) {
	if( ! key_exists('content', $item['fields']) ) $item['fields']['content'] = '';
	echo do_shortcode( $item['fields']['content'] );
}

?>