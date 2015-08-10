<?php
/**
 * Theme widgets and sidebars.
 *
 * @package Betheme
 * @author Muffin group
 * @link http://muffingroup.com
 */


/* ---------------------------------------------------------------------------
 * New widgets
 * --------------------------------------------------------------------------- */
function mfn_register_widget()
{
	register_widget('Mfn_Flickr_Widget');			// Flickr
	register_widget('Mfn_Login_Widget');			// Login
	register_widget('Mfn_Menu_Widget');				// Menu
	register_widget('Mfn_Recent_Comments_Widget');	// Comments
	register_widget('Mfn_Recent_Posts_Widget');		// Posts
	register_widget('Mfn_Tag_Cloud_Widget');		// Tags
}
add_action('widgets_init','mfn_register_widget');


/* ---------------------------------------------------------------------------
 * Add custom sidebars
 * --------------------------------------------------------------------------- */
function mfn_register_sidebars() {

	// custom sidebars -------------------------------------------------------
	$sidebars = mfn_opts_get( 'sidebars' );
	if(is_array($sidebars))
	{
		foreach ($sidebars as $sidebar)
		{	
			register_sidebar( array (
				'name' 			=> $sidebar,
				'id' 			=> 'sidebar-'. str_replace("+", "-", urlencode(strtolower($sidebar))),
				'description'	=> __('Custom sidebar created in Theme Options.','betheme'),
				'before_widget' => '<aside id="%1$s" class="widget %2$s">',
				'after_widget' 	=> '</aside>',
				'before_title' 	=> '<h3>',
				'after_title' 	=> '</h3>',
			));
		}	
	}
	
	// footer areas ----------------------------------------------------------
	for ($i = 1; $i <= 4; $i++)
	{
		register_sidebar(array(
			'name' 			=> __('Footer area','mfn-opts') .' #'.$i,
			'id' 			=> 'footer-area-'.$i,
			'description'	=> __('Appears in the Footer section of the site.','betheme'),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' 	=> '</aside>',
			'before_title' 	=> '<h4>',
			'after_title' 	=> '</h4>',
		));
	}
	
	// sliding top ----------------------------------------------------------
	for ($i = 1; $i <= 4; $i++)
	{
		register_sidebar(array(
			'name' 			=> __('Sliding Top area','mfn-opts') .' #'.$i,
			'id' 			=> 'top-area-'.$i,
			'description'	=> __('Appears in the Sliding Top section of the site.','betheme'),
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget' 	=> '</aside>',
			'before_title' 	=> '<h4>',
			'after_title' 	=> '</h4>',
		));
	}
	
	// Forum | bbPress -----------------------------------------------------------
	register_sidebar(array(
		'name'          => __('Plugin | bbPress', 'mfn-opts'),
		'id'            => 'forum',
		'description'	=> __('Main sidebar for bbPress pages that appears on the right. Leave it empty to use Full Width layout.','betheme'),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' 	=> '</aside>',
		'before_title'  => '<h3>',
		'after_title'   => '</h3>',
	));
	
	// Events | Events Callendar -----------------------------------------------------------
	register_sidebar(array(
		'name'          => __('Plugin | Events Calendar', 'mfn-opts'),
		'id'            => 'events',
		'description'	=> __('Main sidebar for The Events Calendar pages that appears on the right. Leave it empty to use Full Width layout.','betheme'),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' 	=> '</aside>',
		'before_title'  => '<h3>',
		'after_title'   => '</h3>',
	));
	
	// Shop | WooCommerce ----------------------------------------------------------
	register_sidebar(array(
		'name'          => __('Plugin | WooCommerce', 'mfn-opts'),
		'id'            => 'shop',
		'description'	=> __('Main sidebar for WooCommerce pages that appears on the right. Leave it empty to use Full Width layout.','betheme'),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget' 	=> '</aside>',
		'before_title'  => '<h3>',
		'after_title'   => '</h3>',
	));

}
add_action( 'widgets_init', 'mfn_register_sidebars' );

?>