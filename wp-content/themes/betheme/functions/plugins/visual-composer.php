<?php
/**
 * Visual Composer functions
 *
 * @package Betheme
 * @author Muffin group
 * @link http://muffingroup.com
 */

/* ---------------------------------------------------------------------------
 * Shortcodes | Image compatibility
 * --------------------------------------------------------------------------- */
function mfn_vc_image( $image = false ){
	if( $image && is_numeric( $image ) ){
		$image = wp_get_attachment_image_src( $image, 'full' );
		$image = $image[0];
	}
	return $image;
};


/* ---------------------------------------------------------------------------
 * Shortcodes | Map
 * --------------------------------------------------------------------------- */
add_action ( 'vc_before_init', 'mfn_vc_integrateWithVC' );
function mfn_vc_integrateWithVC() {
	
	/*
	// Alert ----------------------------------------------
	vc_map( array (
		'base' 			=> 'alert',
		'name' 			=> __('Alert', 'mfn-opts'),
		'category' 		=> __('Muffin Builder', 'mfn-opts'),
		'icon' 			=> 'mfn-vc-icon-alert',
		'params' 		=> array (
				
			array (
				'param_name' 	=> 'content',
				'type' 			=> 'textarea',
				'heading' 		=> __('Content', 'mfn-opts'),
				'admin_label'	=> true,
				'value' 		=> __('Insert your content here', 'mfn-opts'),
			),
			
			array (
				'param_name' 	=> 'style',
				'type' 			=> 'dropdown',
				'heading' 		=> __('Style', 'mfn-opts'),
				'admin_label'	=> true,
				'value' 		=> array_flip(array( 
					'error' 		=> 'Error', 
					'info'			=> 'Info', 
					'success' 		=> 'Success', 
					'warning' 		=> 'Warning',
				 )),
			),
			 
		),
	));
	*/

	// Article Box ----------------------------------------
	vc_map( array (
		'base' 			=> 'article_box',
		'name' 			=> __('Article Box', 'mfn-opts'),
		'category' 		=> __('Muffin Builder', 'mfn-opts'),
		'icon' 			=> 'mfn-vc-icon-article_box',
		'params' 		=> array (
				
			array (
				'param_name' 	=> 'image',
				'type' 			=> 'attach_image',
				'heading' 		=> __('Image', 'mfn-opts'),
				'description' 	=> __('Featured Image', 'mfn-opts'),
				'admin_label'	=> false,
			),
			
			array (
				'param_name' 	=> 'slogan',
				'type' 			=> 'textfield',
				'heading' 		=> __('Slogan', 'mfn-opts'),
				'admin_label'	=> true,
			),
			
			array (
				'param_name' 	=> 'title',
				'type' 			=> 'textfield',
				'heading' 		=> __('Title', 'mfn-opts'),
				'admin_label'	=> true,
			),
			
			array (
				'param_name' 	=> 'link',
				'type' 			=> 'textfield',
				'heading' 		=> __('Link', 'mfn-opts'),
				'admin_label'	=> true,
			),
			
			array (
				'param_name' 	=> 'target',
				'type' 			=> 'dropdown',
				'heading' 		=> __('Target', 'mfn-opts'),
				'admin_label'	=> false,
				'value' 		=> array( '', '_blank' ),
			),
			
		)
	));
	
	/*
	// Blockquote -----------------------------------------
	vc_map( array (
		'base' 			=> 'blockquote',
		'name' 			=> __('Blockquote', 'mfn-opts'),	
		'category' 		=> __('Muffin Builder', 'mfn-opts'),
		'icon' 			=> 'mfn-vc-icon-blockquote',
		'params' 		=> array (
				
			array (
				'param_name' 	=> 'content',
				'type' 			=> 'textarea',
				'heading' 		=> __('Content', 'mfn-opts'),
				'admin_label'	=> true,
				'value' 		=> __('Insert your content here', 'mfn-opts'),
			),
			
			array (
				'param_name' 	=> 'author',
				'type' 			=> 'textfield',
				'heading' 		=> __('Author', 'mfn-opts'),
				'admin_label'	=> true,
			),
			
			array (
				'param_name' 	=> 'link',
				'type' 			=> 'textfield',
				'heading' 		=> __('Link', 'mfn-opts'),
				'admin_label'	=> true,
			),
			
			array (
				'param_name' 	=> 'target',
				'type' 			=> 'dropdown',
				'heading' 		=> __('Target', 'mfn-opts'),
				'admin_label'	=> false,
				'value' 		=> array( '', '_blank' ),
			),	
				 
		)
	));
	*/
	
	// Blog -----------------------------------------------
	vc_map( array (
		'base' 			=> 'blog',
		'name' 			=> __('Blog', 'mfn-opts'),
		'description' 	=> __('Recommended column size: 1/1', 'mfn-opts'),
		'category' 		=> __('Muffin Builder', 'mfn-opts'),
		'icon' 			=> 'mfn-vc-icon-blog',
		'params' 		=> array (

			array (
				'param_name' 	=> 'count',
				'type' 			=> 'textfield',
				'heading' 		=> __('Count', 'mfn-opts'),
				'description' 	=> __('Number of posts to show', 'mfn-opts'),
				'admin_label'	=> true,
				'value'			=> 2,
			),

			array (
				'param_name' 	=> 'category',
				'type' 			=> 'dropdown',
				'heading' 		=> __('Category', 'mfn-opts'),
				'description' 	=> __('Select posts category', 'mfn-opts'),
				'admin_label'	=> true,
				'value'			=> array_flip( mfn_get_categories( 'category' ) ),
			),
			
			array (
				'param_name' 	=> 'style',
				'type' 			=> 'dropdown',
				'heading' 		=> __('Style', 'mfn-opts'),
				'admin_label'	=> true,
				'value'			=> array_flip( array(
					'classic'	=> 'Classic',
					'masonry'	=> 'Masonry',
					'timeline'	=> 'Timeline',
				)),
			),
			
			array (
				'param_name' 	=> 'more',
				'type' 			=> 'dropdown',
				'heading' 		=> __('Show Read More link', 'mfn-opts'),
				'admin_label'	=> false,
				'value' 		=> array( 
					__('No','mfn-opts') 	=> 0,
					__('Yes','mfn-opts')	=> 1,
				 ),
			),
			
			array (
				'param_name' 	=> 'pagination',
				'type' 			=> 'dropdown',
				'heading' 		=> __('Show Pagination', 'mfn-opts'),
				'description' 	=> __('<strong>Notice:</strong> Pagination will <strong>not</strong> work if you put item on Homepage of WordPress Multilangual Site.', 'mfn-opts'),
				'admin_label'	=> false,
				'value' 		=> array( 
					__('No','mfn-opts') 	=> 0,
					__('Yes','mfn-opts')	=> 1,
				 ),
			),
				 
		)
	));
	
	// Call to Action ----------------------------------------
	vc_map( array (
		'base' 			=> 'call_to_action',
		'name' 			=> __('Call to Action', 'mfn-opts'),
		'description' 	=> __('Recommended column size: 1/1', 'mfn-opts'),
		'category' 		=> __('Muffin Builder', 'mfn-opts'),
		'icon' 			=> 'mfn-vc-icon-call_to_action',
		'params' 		=> array (

			array (
				'param_name' 	=> 'title',
				'type' 			=> 'textfield',
				'heading' 		=> __('Title', 'mfn-opts'),
				'admin_label'	=> true,
			),

			array (
				'param_name' 	=> 'icon',
				'type' 			=> 'textfield',
				'heading' 		=> __('Icon', 'mfn-opts'),
				'description' 	=> __('Font Icon, eg. <strong>icon-lamp</strong>', 'mfn-opts'),
				'admin_label'	=> true,
				'value'			=> 'icon-lamp',
			),
			
			array (
				'param_name' 	=> 'content',
				'type' 			=> 'textarea',
				'heading' 		=> __('Content', 'mfn-opts'),
				'admin_label'	=> true,
				'value' 		=> __('Insert your content here', 'mfn-opts'),
			),

			array (
				'param_name' 	=> 'link',
				'type' 			=> 'textfield',
				'heading' 		=> __('Link', 'mfn-opts'),
				'admin_label'	=> true,
			),

			array (
				'param_name' 	=> 'button_title',
				'type' 			=> 'textfield',
				'heading' 		=> __('Button Title', 'mfn-opts'),
				'description'	=> __('Leave this field blank if you want Call to Action with Big Icon', 'mfn-opts'),
				'admin_label'	=> false,
			),
			
			array (
				'param_name' 	=> 'class',
				'type' 			=> 'textfield',
				'heading' 		=> __('Class', 'mfn-opts'),
				'description'	=> __('This option is useful when you want to use PrettyPhoto (prettyphoto)', 'mfn-opts'),
				'admin_label'	=> false,
			),
				
			array (
				'param_name' 	=> 'target',
				'type' 			=> 'dropdown',
				'heading' 		=> __('Target', 'mfn-opts'),
				'admin_label'	=> false,
				'value' 		=> array( '', '_blank' ),
			),
	 
		)
	));
	
	// Chart ----------------------------------------------
	vc_map( array (
		'base' 			=> 'chart',
		'name' 			=> __('Chart', 'mfn-opts'),
		'category' 		=> __('Muffin Builder', 'mfn-opts'),
		'icon' 			=> 'mfn-vc-icon-chart',
		'params' 		=> array (

			array (
				'param_name' 	=> 'percent',
				'type' 			=> 'textfield',
				'heading' 		=> __('Percent', 'mfn-opts'),
				'desc' 			=> __('Number between 0-100', 'mfn-opts'),
				'admin_label'	=> true,
			),
			
			array (
				'param_name' 	=> 'label',
				'type' 			=> 'textfield',
				'heading' 		=> __('Chart Label', 'mfn-opts'),
				'admin_label'	=> true,
			),
			
			array (
				'param_name' 	=> 'icon',
				'type' 			=> 'textfield',
				'heading' 		=> __('Icon', 'mfn-opts'),
				'description' 	=> __('Font Icon, eg. <strong>icon-lamp</strong>', 'mfn-opts'),
				'admin_label'	=> true,
				'value'			=> 'icon-lamp',
			),
			
			array (
				'param_name' 	=> 'image',
				'type' 			=> 'attach_image',
				'heading' 		=> __('Chart Image', 'mfn-opts'),
				'admin_label'	=> false,
			),
			
			array (
				'param_name' 	=> 'title',
				'type' 			=> 'textfield',
				'heading' 		=> __('Title', 'mfn-opts'),
				'admin_label'	=> true,
			),
	 
		)
	));
	
	// Clients ----------------------------------------------
	vc_map( array (
		'base' 			=> 'clients',
		'name' 			=> __('Clients', 'mfn-opts'),
		'description' 	=> __('Recommended column size: 1/1', 'mfn-opts'),
		'category' 		=> __('Muffin Builder', 'mfn-opts'),
		'icon' 			=> 'mfn-vc-icon-clients',
		'params' 		=> array (

			array (
				'param_name' 	=> 'in_row',
				'type' 			=> 'textfield',
				'heading' 		=> __('Items in Row', 'mfn-opts'),
				'desc' 			=> __('Number of items in row. Recommended number: 3-6', 'mfn-opts'),
				'admin_label'	=> true,
				'value'			=> 6,
			),

			array (
				'param_name' 	=> 'category',
				'type' 			=> 'textfield',
				'heading' 		=> __('Category', 'mfn-opts'),
				'desc' 			=> __('Client Category slug', 'mfn-opts'),
				'admin_label'	=> true,
			),
			
			array (
				'param_name' 	=> 'style',
				'type' 			=> 'dropdown',
				'heading' 		=> __('Style', 'mfn-opts'),
				'admin_label'	=> false,
				'value' 		=> array_flip(array(
					''			=> 'Default',
					'tiles' 	=> 'Tiles',
				)),
			),
	 
		)
	));
	
	// Contact box ----------------------------------------
	vc_map( array (
		'base' 			=> 'contact_box',
		'name' 			=> __('Contact box', 'mfn-opts'),
		'category' 		=> __('Muffin Builder', 'mfn-opts'),
		'icon' 			=> 'mfn-vc-icon-contact_box',
		'params' 		=> array (

			array (
				'param_name' 	=> 'title',
				'type' 			=> 'textfield',
				'heading' 		=> __('Title', 'mfn-opts'),
				'admin_label'	=> true,
			),
			
			array (
				'param_name' 	=> 'address',
				'type' 			=> 'textarea',
				'heading' 		=> __('Address', 'mfn-opts'),
				'admin_label'	=> true,
				'value' 		=> __('Insert your content here', 'mfn-opts'),
			),
			
			array (
				'param_name' 	=> 'telephone',
				'type' 			=> 'textfield',
				'heading' 		=> __('Telephone', 'mfn-opts'),
				'admin_label'	=> true,
			),
				
			array (
				'param_name' 	=> 'email',
				'type' 			=> 'textfield',
				'heading' 		=> __('Email', 'mfn-opts'),
				'admin_label'	=> true,
			),
				
			array (
				'param_name' 	=> 'www',
				'type' 			=> 'textfield',
				'heading' 		=> __('WWW', 'mfn-opts'),
				'admin_label'	=> true,
			),

			array (
				'param_name' 	=> 'image',
				'type' 			=> 'attach_image',
				'heading' 		=> __('Background Image', 'mfn-opts'),
				'admin_label'	=> false,
			),
	 
		)
	));
	
	// Countdown ----------------------------------------------
	vc_map( array (
		'base' 			=> 'countdown',
		'name' 			=> __('Countdown', 'mfn-opts'),
		'description' 	=> __('Recommended column size: 1/1', 'mfn-opts'),
		'category' 		=> __('Muffin Builder', 'mfn-opts'),
		'icon' 			=> 'mfn-vc-icon-countdown',
		'params' 		=> array (
		
			array (
				'param_name' 	=> 'date',
				'type' 			=> 'textfield',
				'heading' 		=> __('Lunch Date', 'mfn-opts'),
				'desc' 			=> __('Format: 12/30/2014 12:00:00 month/day/year hour:minute:second', 'mfn-opts'),
				'admin_label'	=> true,
				'value'			=> '12/30/2014 12:00:00',
			),
			
			array (
				'param_name' 	=> 'timezone',
				'type' 			=> 'dropdown',
				'heading' 		=> __('UTC Timezone', 'mfn-opts'),
				'admin_label'	=> true,
				'value'			=> array_flip( mfna_utc() ),
			),

		)
	));
	
	// Counter ----------------------------------------------
	vc_map( array (
		'base' 			=> 'counter',
		'name' 			=> __('Counter', 'mfn-opts'),
		'category' 		=> __('Muffin Builder', 'mfn-opts'),
		'icon' 			=> 'mfn-vc-icon-counter',
		'params' 		=> array (
		
			array (
				'param_name' 	=> 'icon',
				'type' 			=> 'textfield',
				'heading' 		=> __('Icon', 'mfn-opts'),
				'description' 	=> __('Font Icon, eg. <strong>icon-lamp</strong>', 'mfn-opts'),
				'admin_label'	=> true,
				'value'			=> 'icon-lamp',
			),
			
			array (
				'param_name' 	=> 'color',
				'type' 			=> 'colorpicker',
				'heading' 		=> __('Icon Color', 'mfn-opts'),
				'admin_label'	=> true,
			),
			
			array (
				'param_name' 	=> 'image',
				'type' 			=> 'attach_image',
				'heading' 		=> __('Chart Image', 'mfn-opts'),
				'admin_label'	=> false,
			),
			
			array (
				'param_name' 	=> 'number',
				'type' 			=> 'textfield',
				'heading' 		=> __('Number', 'mfn-opts'),
				'admin_label'	=> true,
			),
			
			array (
				'param_name' 	=> 'title',
				'type' 			=> 'textfield',
				'heading' 		=> __('Title', 'mfn-opts'),
				'admin_label'	=> true,
			),
			
			array (
				'param_name' 	=> 'type',
				'type' 			=> 'dropdown',
				'heading' 		=> __('Style', 'mfn-opts'),
				'admin_label'	=> false,
				'value'			=> array_flip(array(
					'horizontal'	=> 'Horizontal',
					'vertical' 		=> 'Vertical',
				)),
			),

		)
	));
	
	// Fancy Heading --------------------------------------
	vc_map( array (
		'base' 			=> 'fancy_heading',
		'name' 			=> __('Fancy Heading', 'mfn-opts'),
		'category' 		=> __('Muffin Builder', 'mfn-opts'),
		'icon' 			=> 'mfn-vc-icon-fancy_heading',
		'params' 		=> array (

			array (
				'param_name' 	=> 'title',
				'type' 			=> 'textfield',
				'heading' 		=> __('Title', 'mfn-opts'),
				'admin_label'	=> true,
			),
			
			array (
				'param_name' 	=> 'h1',
				'type' 			=> 'dropdown',
				'heading' 		=> __('Use H1 tag', 'mfn-opts'),
				'description' 	=> __('Wrap title into H1 instead of H2', 'mfn-opts'),
				'admin_label'	=> false,
				'value' 		=> array(
					__('No','mfn-opts') 	=> 0,
					__('Yes','mfn-opts')	=> 1,
				),
			),
			
			array (
				'param_name' 	=> 'icon',
				'type' 			=> 'textfield',
				'heading' 		=> __('Icon', 'mfn-opts'),
				'description' 	=> __('Icon Style only. Font Icon, eg. <strong>icon-lamp</strong>', 'mfn-opts'),
				'admin_label'	=> true,
				'value'			=> 'icon-lamp',
			),
			
			array (
				'param_name' 	=> 'slogan',
				'type' 			=> 'textfield',
				'heading' 		=> __('Slogan', 'mfn-opts'),
				'description' 	=> __('Line Style only', 'mfn-opts'),
				'admin_label'	=> true,
			),
			
			array (
				'param_name' 	=> 'content',
				'type' 			=> 'textarea',
				'heading' 		=> __('Content', 'mfn-opts'),
				'admin_label'	=> true,
				'value' 		=> __('Insert your content here', 'mfn-opts'),
			),

			array (
				'param_name' 	=> 'style',
				'type' 			=> 'dropdown',
				'heading' 		=> __('Style', 'mfn-opts'),
				'description' 	=> __('Some fields above work on selected styles.', 'mfn-opts'),
				'admin_label'	=> false,
				'value'			=> array_flip(array(
					'icon'			=> 'Icon',
					'line'			=> 'Line',
					'arrows' 		=> 'Arrows',
				)),
			),

		)
	));
	
	// Feature List --------------------------------------
	vc_map( array (
		'base' 			=> 'feature_list',
		'name' 			=> __('Feature List', 'mfn-opts'),
		'description' 	=> __('Recommended column size: 1/1', 'mfn-opts'),
		'category' 		=> __('Muffin Builder', 'mfn-opts'),
		'icon' 			=> 'mfn-vc-icon-feature_list',
		'params' 		=> array (

			array (
				'param_name' 	=> 'title',
				'type' 			=> 'textfield',
				'heading' 		=> __('Title', 'mfn-opts'),
				'description' 	=> __('This field is used as an Item Label in admin panel only.', 'mfn-opts'),
				'admin_label'	=> true,
			),
			
			array (
				'param_name' 	=> 'content',
				'type' 			=> 'textarea',
				'heading' 		=> __('Content', 'mfn-opts'),
				'description' 	=>  __('Please use <strong>[item icon="" title="" link="" target=""]</strong> shortcodes.', 'mfn-opts'),
				'admin_label'	=> false,
				'value' 		=> '[item icon="icon-lamp" title="" link="" target="" animate=""]',
			),

		)
	));
	
	// Flat Box -------------------------------------------
	vc_map( array (
		'base' 			=> 'flat_box',
		'name' 			=> __('Flat Box', 'mfn-opts'),
		'category' 		=> __('Muffin Builder', 'mfn-opts'),
		'icon' 			=> 'mfn-vc-icon-flat_box',
		'params' 		=> array (
			
			array (
				'param_name' 	=> 'icon',
				'type' 			=> 'textfield',
				'heading' 		=> __('Icon', 'mfn-opts'),
				'description' 	=> __('Font Icon, eg. <strong>icon-lamp</strong>', 'mfn-opts'),
				'admin_label'	=> true,
				'value'			=> 'icon-lamp',
			),
			
			array (
				'param_name' 	=> 'background',
				'type' 			=> 'colorpicker',
				'heading' 		=> __('Icon background', 'mfn-opts'),
				'description' 	=> __('Leave this field blank to use Theme Background.', 'mfn-opts'),
				'admin_label'	=> true,
			),
			
			array (
				'param_name' 	=> 'image',
				'type' 			=> 'attach_image',
				'heading' 		=> __('Image', 'mfn-opts'),
				'admin_label'	=> false,
			),
				
			array (
				'param_name' 	=> 'title',
				'type' 			=> 'textfield',
				'heading' 		=> __('Title', 'mfn-opts'),
				'admin_label'	=> true,
			),

			array (
				'param_name' 	=> 'content',
				'type' 			=> 'textarea',
				'heading' 		=> __('Content', 'mfn-opts'),
				'admin_label'	=> true,
				'value' 		=> __('Insert your content here', 'mfn-opts'),
			),
			
			array (
				'param_name' 	=> 'link',
				'type' 			=> 'textfield',
				'heading' 		=> __('Link', 'mfn-opts'),
				'admin_label'	=> true,
			),
			
			array (
				'param_name' 	=> 'target',
				'type' 			=> 'dropdown',
				'heading' 		=> __('Target', 'mfn-opts'),
				'admin_label'	=> false,
				'value' 		=> array( '', '_blank' ),
			),

		)
	));
	
	// Hover Box -------------------------------------------
	vc_map( array (
		'base' 			=> 'hover_box',
		'name' 			=> __('Hover Box', 'mfn-opts'),
		'category' 		=> __('Muffin Builder', 'mfn-opts'),
		'icon' 			=> 'mfn-vc-icon-hover_box',
		'params' 		=> array (

			array (
				'param_name' 	=> 'image',
				'type' 			=> 'attach_image',
				'heading' 		=> __('Image', 'mfn-opts'),
				'admin_label'	=> false,
			),
			
			array (
				'param_name' 	=> 'image_hover',
				'type' 			=> 'attach_image',
				'heading' 		=> __('Hover Image', 'mfn-opts'),
				'description' 	=> __('Both images must have the same size.', 'mfn-opts'),
				'admin_label'	=> false,
			),
			
			array (
				'param_name' 	=> 'link',
				'type' 			=> 'textfield',
				'heading' 		=> __('Link', 'mfn-opts'),
				'admin_label'	=> true,
			),
			
			array (
				'param_name' 	=> 'target',
				'type' 			=> 'dropdown',
				'heading' 		=> __('Target', 'mfn-opts'),
				'admin_label'	=> false,
				'value' 		=> array( '', '_blank' ),
			),

		)
	));
	
	// How it Works -------------------------------------------
	vc_map( array (
		'base' 			=> 'how_it_works',
		'name' 			=> __('How it Works', 'mfn-opts'),
		'category' 		=> __('Muffin Builder', 'mfn-opts'),
		'icon' 			=> 'mfn-vc-icon-how_it_works',
		'params' 		=> array (

			array (
				'param_name' 	=> 'image',
				'type' 			=> 'attach_image',
				'heading' 		=> __('Background Image', 'mfn-opts'),
				'description' 	=> __('Recommended: Square Image with transparent background.', 'mfn-opts'),
				'admin_label'	=> false,
			),

			array (
				'param_name' 	=> 'number',
				'type' 			=> 'textfield',
				'heading' 		=> __('Number', 'mfn-opts'),
				'admin_label'	=> true,
			),

			array (
				'param_name' 	=> 'title',
				'type' 			=> 'textfield',
				'heading' 		=> __('Title', 'mfn-opts'),
				'admin_label'	=> true,
			),

			array (
				'param_name' 	=> 'content',
				'type' 			=> 'textfield',
				'heading' 		=> __('Content', 'mfn-opts'),
				'admin_label'	=> true,
			),
			
			array (
				'param_name' 	=> 'border',
				'type' 			=> 'dropdown',
				'heading' 		=> __('Line', 'mfn-opts'),
				'description' 	=> __('Show right connecting line', 'mfn-opts'),
				'admin_label'	=> false,
				'value' 		=> array(
					__('No','mfn-opts') 	=> 0,
					__('Yes','mfn-opts')	=> 1,
				),
			),
			
			array (
				'param_name' 	=> 'link',
				'type' 			=> 'textfield',
				'heading' 		=> __('Link', 'mfn-opts'),
				'admin_label'	=> true,
			),
			
			array (
				'param_name' 	=> 'target',
				'type' 			=> 'dropdown',
				'heading' 		=> __('Target', 'mfn-opts'),
				'admin_label'	=> false,
				'value' 		=> array( '', '_blank' ),
			),

		)
	));
	
	// Icon Box -------------------------------------------
	vc_map( array (
		'base' 			=> 'icon_box',
		'name' 			=> __('Icon Box', 'mfn-opts'),
		'category' 		=> __('Muffin Builder', 'mfn-opts'),
		'icon' 			=> 'mfn-vc-icon-icon_box',
		'params' 		=> array (
			
			array (
				'param_name' 	=> 'title',
				'type' 			=> 'textfield',
				'heading' 		=> __('Title', 'mfn-opts'),
				'admin_label'	=> true,
			),
			
			array (
				'param_name' 	=> 'content',
				'type' 			=> 'textarea',
				'heading' 		=> __('Content', 'mfn-opts'),
				'admin_label'	=> true,
				'value' 		=> __('Insert your content here', 'mfn-opts'),
			),
			
			array (
				'param_name' 	=> 'icon',
				'type' 			=> 'textfield',
				'heading' 		=> __('Icon', 'mfn-opts'),
				'description' 	=> __('Font Icon, eg. <strong>icon-lamp</strong>', 'mfn-opts'),
				'admin_label'	=> true,
				'value'			=> 'icon-lamp',
			),
			
			array (
				'param_name' 	=> 'image',
				'type' 			=> 'attach_image',
				'heading' 		=> __('Image', 'mfn-opts'),
				'admin_label'	=> false,
			),
				
			array (
				'param_name' 	=> 'icon_position',
				'type' 			=> 'dropdown',
				'heading' 		=> __('Icon Position', 'mfn-opts'),
				'admin_label'	=> false,
				'value'			=> array_flip(array(
					'top'	=> 'Top',
					'left'	=> 'Left',
				)),
			),
			
			array (
				'param_name' 	=> 'border',
				'type' 			=> 'dropdown',
				'heading' 		=> __('Border', 'mfn-opts'),
				'description' 	=> __('Show right border', 'mfn-opts'),
				'admin_label'	=> false,
				'value' 		=> array(
						__('No','mfn-opts') 	=> 0,
						__('Yes','mfn-opts')	=> 1,
				),
			),

			array (
				'param_name' 	=> 'link',
				'type' 			=> 'textfield',
				'heading' 		=> __('Link', 'mfn-opts'),
				'admin_label'	=> true,
			),
				
			array (
				'param_name' 	=> 'target',
				'type' 			=> 'dropdown',
				'heading' 		=> __('Target', 'mfn-opts'),
				'admin_label'	=> false,
				'value' 		=> array( '', '_blank' ),
			),
			
			array (
				'param_name' 	=> 'class',
				'type' 			=> 'textfield',
				'heading' 		=> __('Custom CSS classes for link', 'mfn-opts'),
				'description' 	=> __('This option is useful when you want to use PrettyPhoto (prettyphoto) or Scroll (scroll).', 'mfn-opts'),
				'admin_label'	=> true,
			),
			
		)
	));
	
	// Info Box -------------------------------------------
	vc_map( array (
		'base' 			=> 'info_box',
		'name' 			=> __('Info Box', 'mfn-opts'),
		'category' 		=> __('Muffin Builder', 'mfn-opts'),
		'icon' 			=> 'mfn-vc-icon-info_box',
		'params' 		=> array (
			
			array (
				'param_name' 	=> 'title',
				'type' 			=> 'textfield',
				'heading' 		=> __('Title', 'mfn-opts'),
				'admin_label'	=> true,
			),
			
			array (
				'param_name' 	=> 'content',
				'type' 			=> 'textarea',
				'heading' 		=> __('Content', 'mfn-opts'),
				'admin_label'	=> false,
				'value' 		=> '<ul><li>list item 1</li><li>list item 2</li></ul>',
			),

			array (
				'param_name' 	=> 'image',
				'type' 			=> 'attach_image',
				'heading' 		=> __('Background Image', 'mfn-opts'),
				'admin_label'	=> false,
			),

		)
	));
	
	// List -------------------------------------------
	vc_map( array (
		'base' 			=> 'list',
		'name' 			=> __('List', 'mfn-opts'),
		'category' 		=> __('Muffin Builder', 'mfn-opts'),
		'icon' 			=> 'mfn-vc-icon-list',
		'params' 		=> array (
			
			array (
				'param_name' 	=> 'icon',
				'type' 			=> 'textfield',
				'heading' 		=> __('Icon', 'mfn-opts'),
				'description' 	=> __('Font Icon, eg. <strong>icon-lamp</strong>', 'mfn-opts'),
				'admin_label'	=> true,
				'value'			=> 'icon-lamp',
			),
			
			array (
				'param_name' 	=> 'image',
				'type' 			=> 'attach_image',
				'heading' 		=> __('Image', 'mfn-opts'),
				'admin_label'	=> false,
			),
			
			array (
				'param_name' 	=> 'title',
				'type' 			=> 'textfield',
				'heading' 		=> __('Title', 'mfn-opts'),
				'admin_label'	=> true,
			),
			
			array (
				'param_name' 	=> 'content',
				'type' 			=> 'textarea',
				'heading' 		=> __('Content', 'mfn-opts'),
				'admin_label'	=> true,
				'value' 		=> __('Insert your content here', 'mfn-opts'),
			),
			
			array (
				'param_name' 	=> 'link',
				'type' 			=> 'textfield',
				'heading' 		=> __('Link', 'mfn-opts'),
				'admin_label'	=> true,
			),
			
			array (
				'param_name' 	=> 'target',
				'type' 			=> 'dropdown',
				'heading' 		=> __('Target', 'mfn-opts'),
				'admin_label'	=> false,
				'value' 		=> array( '', '_blank' ),
			),
			
			array (
				'param_name' 	=> 'style',
				'type' 			=> 'dropdown',
				'heading' 		=> __('Style', 'mfn-opts'),
				'admin_label'	=> false,
				'value'			=> array_flip(array(
					1 => 'With background',
					2 => 'Transparent',
					3 => 'Vertical',
					4 => 'Ordered list',
				)),
			),

		)
	));
	
	// Map -------------------------------------------
	vc_map( array (
		'base' 			=> 'map',
		'name' 			=> __('Map', 'mfn-opts'),
		'category' 		=> __('Muffin Builder', 'mfn-opts'),
		'icon' 			=> 'mfn-vc-icon-map',
		'params' 		=> array (
			
			array (
				'param_name' 	=> 'lat',
				'type' 			=> 'textfield',
				'heading' 		=> __('Google Maps Lat', 'mfn-opts'),
				'description' 	=> __('The map will appear only if this field is filled correctly.', 'mfn-opts'),
				'admin_label'	=> false,
				'value'			=> '-33.8710',
			),
			
			array (
				'param_name' 	=> 'lng',
				'type' 			=> 'textfield',
				'heading' 		=> __('Google Maps Lng', 'mfn-opts'),
				'description' 	=> __('The map will appear only if this field is filled correctly.', 'mfn-opts'),
				'admin_label'	=> false,
				'value'			=> '151.2039',
			),
			
			array (
				'param_name' 	=> 'zoom',
				'type' 			=> 'textfield',
				'heading' 		=> __('Zoom', 'mfn-opts'),
				'admin_label'	=> false,
				'value'			=> 13,
			),
			
			array (
				'param_name' 	=> 'height',
				'type' 			=> 'textfield',
				'heading' 		=> __('Height', 'mfn-opts'),
				'admin_label'	=> false,
				'value'			=> 200,
			),

		)
	));
	
	// Opening Hours -------------------------------------------
	vc_map( array (
		'base' 			=> 'opening_hours',
		'name' 			=> __('Opening Hours', 'mfn-opts'),
		'category' 		=> __('Muffin Builder', 'mfn-opts'),
		'icon' 			=> 'mfn-vc-icon-opening_hours',
		'params' 		=> array (

			array (
				'param_name' 	=> 'title',
				'type' 			=> 'textfield',
				'heading' 		=> __('Title', 'mfn-opts'),
				'admin_label'	=> true,
			),

			array (
				'param_name' 	=> 'content',
				'type' 			=> 'textarea',
				'heading' 		=> __('Content', 'mfn-opts'),
				'description' 	=> __('HTML tags allowed', 'mfn-opts'),
				'admin_label'	=> false,
				'value' 		=> '<ul><li><label>Monday - Saturday</label><span class="h">8am - 4pm</span></li></ul>',
			),

			array (
				'param_name' 	=> 'image',
				'type' 			=> 'attach_image',
				'heading' 		=> __('Background Image', 'mfn-opts'),
				'admin_label'	=> false,
			),

		)
	));
	
	// Our Team -------------------------------------------
	vc_map( array (
		'base' 			=> 'our_team',
		'name' 			=> __('Our Team', 'mfn-opts'),
		'category' 		=> __('Muffin Builder', 'mfn-opts'),
		'icon' 			=> 'mfn-vc-icon-our_team',
		'params' 		=> array (

			array (
				'param_name' 	=> 'heading',
				'type' 			=> 'textfield',
				'heading' 		=> __('Heading', 'mfn-opts'),
				'admin_label'	=> true,
			),

			array (
				'param_name' 	=> 'image',
				'type' 			=> 'attach_image',
				'heading' 		=> __('Photo', 'mfn-opts'),
				'admin_label'	=> false,
			),
			
			array (
				'param_name' 	=> 'title',
				'type' 			=> 'textfield',
				'heading' 		=> __('Title', 'mfn-opts'),
				'description' 	=> __('Will also be used as the image alternative text', 'mfn-opts'),
				'admin_label'	=> true,
			),
			
			array (
				'param_name' 	=> 'subtitle',
				'type' 			=> 'textfield',
				'heading' 		=> __('Subtitle', 'mfn-opts'),
				'admin_label'	=> true,
			),
			
			array (
				'param_name' 	=> 'phone',
				'type' 			=> 'textfield',
				'heading' 		=> __('Phone', 'mfn-opts'),
				'admin_label'	=> true,
			),
			
			array (
				'param_name' 	=> 'content',
				'type' 			=> 'textarea',
				'heading' 		=> __('Content', 'mfn-opts'),
				'admin_label'	=> false,
			),

			array (
				'param_name' 	=> 'email',
				'type' 			=> 'textfield',
				'heading' 		=> __('E-mail', 'mfn-opts'),
				'admin_label'	=> true,
			),

			array (
				'param_name' 	=> 'facebook',
				'type' 			=> 'textfield',
				'heading' 		=> __('Facebook', 'mfn-opts'),
				'admin_label'	=> true,
			),

			array (
				'param_name' 	=> 'twitter',
				'type' 			=> 'textfield',
				'heading' 		=> __('Twitter', 'mfn-opts'),
				'admin_label'	=> true,
			),
			
			array (
				'param_name' 	=> 'linkedin',
				'type' 			=> 'textfield',
				'heading' 		=> __('LinkedIn', 'mfn-opts'),
				'admin_label'	=> true,
			),
			
			array (
				'param_name' 	=> 'blockquote',
				'type' 			=> 'textarea',
				'heading' 		=> __('Blockquote', 'mfn-opts'),
				'admin_label'	=> false,
			),
			
			array (
				'param_name' 	=> 'link',
				'type' 			=> 'textfield',
				'heading' 		=> __('Link', 'mfn-opts'),
				'admin_label'	=> true,
			),
				
			array (
				'param_name' 	=> 'target',
				'type' 			=> 'dropdown',
				'heading' 		=> __('Target', 'mfn-opts'),
				'admin_label'	=> false,
				'value' 		=> array( '', '_blank' ),
			),
			
			array (
				'param_name' 	=> 'style',
				'type' 			=> 'dropdown',
				'heading' 		=> __('Style', 'mfn-opts'),
				'admin_label'	=> false,
				'value'			=> array_flip(array(
					'circle'		=> 'Circle',
					'vertical'		=> 'Vertical',
					'horizontal'	=> 'Horizontal 	[1/2 and wider]',
				)),
			),

		)
	));
	
	// Our Team List -------------------------------------------
	vc_map( array (
		'base' 			=> 'our_team_list',
		'name' 			=> __('Our Team List', 'mfn-opts'),
		'description' 	=> __('Recommended column size: 1/1', 'mfn-opts'),
		'category' 		=> __('Muffin Builder', 'mfn-opts'),
		'icon' 			=> 'mfn-vc-icon-our_team_list',
		'params' 		=> array (

			array (
				'param_name' 	=> 'image',
				'type' 			=> 'attach_image',
				'heading' 		=> __('Photo', 'mfn-opts'),
				'admin_label'	=> false,
			),
			
			array (
				'param_name' 	=> 'title',
				'type' 			=> 'textfield',
				'heading' 		=> __('Title', 'mfn-opts'),
				'description' 	=> __('Will also be used as the image alternative text', 'mfn-opts'),
				'admin_label'	=> true,
			),
			
			array (
				'param_name' 	=> 'subtitle',
				'type' 			=> 'textfield',
				'heading' 		=> __('Subtitle', 'mfn-opts'),
				'admin_label'	=> true,
			),
			
			array (
				'param_name' 	=> 'phone',
				'type' 			=> 'textfield',
				'heading' 		=> __('Phone', 'mfn-opts'),
				'admin_label'	=> true,
			),
			
			array (
				'param_name' 	=> 'content',
				'type' 			=> 'textarea',
				'heading' 		=> __('Content', 'mfn-opts'),
				'admin_label'	=> false,
			),
			
			array (
				'param_name' 	=> 'blockquote',
				'type' 			=> 'textarea',
				'heading' 		=> __('Blockquote', 'mfn-opts'),
				'admin_label'	=> false,
			),

			array (
				'param_name' 	=> 'email',
				'type' 			=> 'textfield',
				'heading' 		=> __('E-mail', 'mfn-opts'),
				'admin_label'	=> true,
			),

			array (
				'param_name' 	=> 'facebook',
				'type' 			=> 'textfield',
				'heading' 		=> __('Facebook', 'mfn-opts'),
				'admin_label'	=> true,
			),

			array (
				'param_name' 	=> 'twitter',
				'type' 			=> 'textfield',
				'heading' 		=> __('Twitter', 'mfn-opts'),
				'admin_label'	=> true,
			),
			
			array (
				'param_name' 	=> 'linkedin',
				'type' 			=> 'textfield',
				'heading' 		=> __('LinkedIn', 'mfn-opts'),
				'admin_label'	=> true,
			),

			array (
				'param_name' 	=> 'link',
				'type' 			=> 'textfield',
				'heading' 		=> __('Link', 'mfn-opts'),
				'admin_label'	=> true,
			),
				
			array (
				'param_name' 	=> 'target',
				'type' 			=> 'dropdown',
				'heading' 		=> __('Target', 'mfn-opts'),
				'admin_label'	=> false,
				'value' 		=> array( '', '_blank' ),
			),

		)
	));
	
	// Photo Box -------------------------------------------
	vc_map( array (
		'base' 			=> 'photo_box',
		'name' 			=> __('Photo Box', 'mfn-opts'),
		'category' 		=> __('Muffin Builder', 'mfn-opts'),
		'icon' 			=> 'mfn-vc-icon-photo_box',
		'params' 		=> array (

			array (
				'param_name' 	=> 'title',
				'type' 			=> 'textfield',
				'heading' 		=> __('Title', 'mfn-opts'),
				'admin_label'	=> true,
			),
			
			array (
				'param_name' 	=> 'image',
				'type' 			=> 'attach_image',
				'heading' 		=> __('Image', 'mfn-opts'),
				'admin_label'	=> false,
			),
			
			array (
				'param_name' 	=> 'content',
				'type' 			=> 'textarea',
				'heading' 		=> __('Content', 'mfn-opts'),
				'admin_label'	=> false,
			),
			
			array (
				'param_name' 	=> 'align',
				'type' 			=> 'dropdown',
				'heading' 		=> __('Align', 'mfn-opts'),
				'admin_label'	=> false,
				'value' 		=> array_flip(array(
					''		=> 'Center',
					'left'	=> 'Left',
					'right'	=> 'Right',
				)),
			),

			array (
				'param_name' 	=> 'link',
				'type' 			=> 'textfield',
				'heading' 		=> __('Link', 'mfn-opts'),
				'admin_label'	=> true,
			),
				
			array (
				'param_name' 	=> 'target',
				'type' 			=> 'dropdown',
				'heading' 		=> __('Target', 'mfn-opts'),
				'admin_label'	=> false,
				'value' 		=> array( '', '_blank' ),
			),

		)
	));
	
	// Portfolio -------------------------------------------
	vc_map( array (
		'base' 			=> 'portfolio',
		'name' 			=> __('Portfolio', 'mfn-opts'),
		'description' 	=> __('Recommended column size: 1/1', 'mfn-opts'),
		'category' 		=> __('Muffin Builder', 'mfn-opts'),
		'icon' 			=> 'mfn-vc-icon-portfolio',
		'params' 		=> array (

			array (
				'param_name' 	=> 'count',
				'type' 			=> 'textfield',
				'heading' 		=> __('Count', 'mfn-opts'),
				'admin_label'	=> true,
				'value'			=> 2,
			),

			array (
				'param_name' 	=> 'category',
				'type' 			=> 'textfield',
				'heading' 		=> __('Category', 'mfn-opts'),
				'description' 	=> __('Portfolio Category slug', 'mfn-opts'),
				'admin_label'	=> true,
			),
			
			array (
				'param_name' 	=> 'style',
				'type' 			=> 'dropdown',
				'heading' 		=> __('Style', 'mfn-opts'),
				'admin_label'	=> false,
				'value' 		=> array_flip(array(
					'list'			=> 'List',
					'flat'			=> 'Flat',
					'grid'			=> 'Grid',
					'masonry'		=> 'Masonry',
					'masonry-flat'	=> 'Masonry Flat',
				)),
			),
			
			array (
				'param_name' 	=> 'orderby',
				'type' 			=> 'dropdown',
				'heading' 		=> __('Order by', 'mfn-opts'),
				'description' 	=> __('Portfolio items order by column.', 'mfn-opts'),
				'admin_label'	=> false,
				'value' 		=> array_flip(array(
					'date'			=> 'Date',
					'menu_order' 	=> 'Menu order',
					'title'			=> 'Title',
					'rand'			=> 'Random',
				)),
			),
			
			array (
				'param_name' 	=> 'order',
				'type' 			=> 'dropdown',
				'heading' 		=> __('Order', 'mfn-opts'),
				'description' 	=> __('Portfolio items order.', 'mfn-opts'),
				'admin_label'	=> false,
				'value' 		=> array_flip(array(
					'ASC' 	=> 'Ascending',
					'DESC' 	=> 'Descending',
				)),
			),
			
			array (
				'param_name' 	=> 'pagination',
				'type' 			=> 'dropdown',
				'heading' 		=> __('Show pagination', 'mfn-opts'),
				'description' 	=> __('<strong>Notice:</strong> Pagination will <strong>not</strong> work if you put item on Homepage of WordPress Multilangual Site.', 'mfn-opts'),
				'admin_label'	=> false,
				'value' 		=> array_flip(array(
					'' 	=> 'No',
					1 	=> 'Yes',
				)),
			),

		)
	));
	
	// Pricing Item -------------------------------------------
	vc_map( array (
		'base' 			=> 'pricing_item',
		'name' 			=> __('Pricing Item', 'mfn-opts'),
		'category' 		=> __('Muffin Builder', 'mfn-opts'),
		'icon' 			=> 'mfn-vc-icon-pricing_item',
		'params' 		=> array (
			
			array (
				'param_name' 	=> 'image',
				'type' 			=> 'attach_image',
				'heading' 		=> __('Image', 'mfn-opts'),
				'admin_label'	=> false,
			),
			
			array (
				'param_name' 	=> 'title',
				'type' 			=> 'textfield',
				'heading' 		=> __('Title', 'mfn-opts'),
				'admin_label'	=> true,
			),
			
			array (
				'param_name' 	=> 'price',
				'type' 			=> 'textfield',
				'heading' 		=> __('Price', 'mfn-opts'),
				'admin_label'	=> true,
			),
			
			array (
				'param_name' 	=> 'currency',
				'type' 			=> 'textfield',
				'heading' 		=> __('Currency', 'mfn-opts'),
				'admin_label'	=> false,
			),
			
			array (
				'param_name' 	=> 'period',
				'type' 			=> 'textfield',
				'heading' 		=> __('Period', 'mfn-opts'),
				'admin_label'	=> false,
			),
			
			array (
				'param_name' 	=> 'subtitle',
				'type' 			=> 'textfield',
				'heading' 		=> __('Subtitle', 'mfn-opts'),
				'admin_label'	=> true,
			),
			
			array (
				'param_name' 	=> 'content',
				'type' 			=> 'textarea',
				'heading' 		=> __('Content', 'mfn-opts'),
				'description' 	=> __('HTML tags allowed', 'mfn-opts'),
				'admin_label'	=> false,
				'value'			=> '<ul><li><strong>List</strong> item</li></ul>',
			),
			
			array (
				'param_name' 	=> 'link_title',
				'type' 			=> 'textfield',
				'heading' 		=> __('Link title', 'mfn-opts'),
				'description'	=> __('Link will appear only if this field will be filled.', 'mfn-opts'),
				'admin_label'	=> false,
			),
			
			array (
				'param_name' 	=> 'link',
				'type' 			=> 'textfield',
				'heading' 		=> __('Link', 'mfn-opts'),
				'description'	=> __('Link will appear only if this field will be filled.', 'mfn-opts'),
				'admin_label'	=> true,
			),
				
			array (
				'param_name' 	=> 'featured',
				'type' 			=> 'dropdown',
				'heading' 		=> __('Featured', 'mfn-opts'),
				'admin_label'	=> false,
				'value' 		=> array(
					__('No','mfn-opts') 	=> 0,
					__('Yes','mfn-opts')	=> 1,
				),
			),
			
			array (
				'param_name' 	=> 'style',
				'type' 			=> 'dropdown',
				'heading' 		=> __('Style', 'mfn-opts'),
				'admin_label'	=> false,
				'value'			=> array_flip(array(
					'box'	=> 'Box',
					'label'	=> 'Table Label',
					'table'	=> 'Table',
				)),
			),

		)
	));
	
	// Progress Bars -------------------------------------------
	vc_map( array (
		'base' 			=> 'progress_bars',
		'name' 			=> __('Progress Bars', 'mfn-opts'),
		'category' 		=> __('Muffin Builder', 'mfn-opts'),
		'icon' 			=> 'mfn-vc-icon-progress_bars',
		'params' 		=> array (

			array (
				'param_name' 	=> 'title',
				'type' 			=> 'textfield',
				'heading' 		=> __('Title', 'mfn-opts'),
				'admin_label'	=> true,
			),
			
			array (
				'param_name' 	=> 'content',
				'type' 			=> 'textarea',
				'heading' 		=> __('Content', 'mfn-opts'),
				'description'	=> __('Please use <strong>[bar title="Title" value="50"]</strong> shortcodes here.', 'mfn-opts'),
				'admin_label'	=> false,
				'value' 		=> '[bar title="Bar1" value="50"]'."\n".'[bar title="Bar2" value="60"]',
			),

		)
	));
	
	// Promo Box -------------------------------------------
	vc_map( array (
		'base' 			=> 'promo_box',
		'name' 			=> __('Promo Box', 'mfn-opts'),
		'category' 		=> __('Muffin Builder', 'mfn-opts'),
		'icon' 			=> 'mfn-vc-icon-promo_box',
		'params' 		=> array (

			array (
				'param_name' 	=> 'image',
				'type' 			=> 'attach_image',
				'heading' 		=> __('Image', 'mfn-opts'),
				'admin_label'	=> false,
			),
		
			array (
				'param_name' 	=> 'title',
				'type' 			=> 'textfield',
				'heading' 		=> __('Title', 'mfn-opts'),
				'admin_label'	=> true,
			),
			
			array (
				'param_name' 	=> 'content',
				'type' 			=> 'textarea',
				'heading' 		=> __('Content', 'mfn-opts'),
				'admin_label'	=> false,
			),
			
			array (
				'param_name' 	=> 'btn_text',
				'type' 			=> 'textfield',
				'heading' 		=> __('Button Text', 'mfn-opts'),
				'admin_label'	=> false,
			),
			
			array (
				'param_name' 	=> 'btn_link',
				'type' 			=> 'textfield',
				'heading' 		=> __('Button Link', 'mfn-opts'),
				'admin_label'	=> false,
			),
			
			array (
				'param_name' 	=> 'target',
				'type' 			=> 'dropdown',
				'heading' 		=> __('Target', 'mfn-opts'),
				'admin_label'	=> false,
				'value' 		=> array( '', '_blank' ),
			),
				
			array (
				'param_name' 	=> 'position',
				'type' 			=> 'dropdown',
				'heading' 		=> __('Image position', 'mfn-opts'),
				'admin_label'	=> false,
				'value'			=> array_flip(array(
					'left' 	=> 'Left',
					'right' => 'Right',
				)),
			),
			
			array (
				'param_name' 	=> 'border',
				'type' 			=> 'dropdown',
				'heading' 		=> __('Border', 'mfn-opts'),
				'admin_label'	=> false,
				'description'	=> __('Show right border', 'mfn-opts'),
				'value' 		=> array(
					__('No','mfn-opts') 	=> 0,
					__('Yes','mfn-opts')	=> 1,
				),
			),

		)
	));
	
	// Quick Fact -------------------------------------------
	vc_map( array (
		'base' 			=> 'quick_fact',
		'name' 			=> __('Quick Fact', 'mfn-opts'),
		'category' 		=> __('Muffin Builder', 'mfn-opts'),
		'icon' 			=> 'mfn-vc-icon-quick_fact',
		'params' 		=> array (
		
			array (
				'param_name' 	=> 'heading',
				'type' 			=> 'textfield',
				'heading' 		=> __('Heading', 'mfn-opts'),
				'admin_label'	=> true,
			),
		
			array (
				'param_name' 	=> 'number',
				'type' 			=> 'textfield',
				'heading' 		=> __('Number', 'mfn-opts'),
				'admin_label'	=> true,
			),
		
			array (
				'param_name' 	=> 'title',
				'type' 			=> 'textfield',
				'heading' 		=> __('Title', 'mfn-opts'),
				'admin_label'	=> true,
			),
			
			array (
				'param_name' 	=> 'content',
				'type' 			=> 'textarea',
				'heading' 		=> __('Content', 'mfn-opts'),
				'admin_label'	=> false,
			),

		)
	));
	
	// Sliding Box -------------------------------------------
	vc_map( array (
		'base' 			=> 'sliding_box',
		'name' 			=> __('Sliding Box', 'mfn-opts'),
		'category' 		=> __('Muffin Builder', 'mfn-opts'),
		'icon' 			=> 'mfn-vc-icon-sliding_box',
		'params' 		=> array (
		
			array (
				'param_name' 	=> 'image',
				'type' 			=> 'attach_image',
				'heading' 		=> __('Image', 'mfn-opts'),
				'admin_label'	=> false,
			),
		
			array (
				'param_name' 	=> 'title',
				'type' 			=> 'textfield',
				'heading' 		=> __('Title', 'mfn-opts'),
				'admin_label'	=> true,
			),

			array (
				'param_name' 	=> 'link',
				'type' 			=> 'textfield',
				'heading' 		=> __('Link', 'mfn-opts'),
				'admin_label'	=> true,
			),
			
			array (
				'param_name' 	=> 'target',
				'type' 			=> 'dropdown',
				'heading' 		=> __('Target', 'mfn-opts'),
				'admin_label'	=> false,
				'value' 		=> array( '', '_blank' ),
			),
			
		)
	));
	
	// Trailer Box -------------------------------------------
	vc_map( array (
		'base' 			=> 'trailer_box',
		'name' 			=> __('Trailer Box', 'mfn-opts'),
		'category' 		=> __('Muffin Builder', 'mfn-opts'),
		'icon' 			=> 'mfn-vc-icon-trailer_box',
		'params' 		=> array (
		
			array (
				'param_name' 	=> 'image',
				'type' 			=> 'attach_image',
				'heading' 		=> __('Image', 'mfn-opts'),
				'admin_label'	=> false,
			),
		
			array (
				'param_name' 	=> 'slogan',
				'type' 			=> 'textfield',
				'heading' 		=> __('Slogan', 'mfn-opts'),
				'admin_label'	=> true,
			),
			
			array (
				'param_name' 	=> 'title',
				'type' 			=> 'textfield',
				'heading' 		=> __('Title', 'mfn-opts'),
				'admin_label'	=> true,
			),

			array (
				'param_name' 	=> 'link',
				'type' 			=> 'textfield',
				'heading' 		=> __('Link', 'mfn-opts'),
				'admin_label'	=> true,
			),
			
			array (
				'param_name' 	=> 'target',
				'type' 			=> 'dropdown',
				'heading' 		=> __('Target', 'mfn-opts'),
				'admin_label'	=> false,
				'value' 		=> array( '', '_blank' ),
			),
			
		)
	));

	


	

}

?>