<?php
/**
 * Menu functions.
 *
 * @package Betheme
 * @author Muffin group
 * @link http://muffingroup.com
 */


/* ---------------------------------------------------------------------------
 * Registers a menu location to use with navigation menus.
 * --------------------------------------------------------------------------- */
register_nav_menu( 'main-menu',				__( 'Main Menu', 'mfn-opts' ) );
register_nav_menu( 'secondary-menu',		__( 'Secondary Menu', 'mfn-opts' ) );
register_nav_menu( 'lang-menu',				__( 'Languages Menu', 'mfn-opts' ) );
register_nav_menu( 'social-menu',			__( 'Social Menu Top', 'mfn-opts' ) );
register_nav_menu( 'social-menu-bottom',	__( 'Social Menu Bottom', 'mfn-opts' ) );


/* ---------------------------------------------------------------------------
 * Main Menu
 * --------------------------------------------------------------------------- */
function mfn_wp_nav_menu() 
{
	$args = array( 
		'container' 		=> 'nav',
		'container_id'		=> 'menu', 
		'menu_class'		=> 'menu', 
		'fallback_cb'		=> 'mfn_wp_page_menu', 
// 		'theme_location'	=> $location,
		'depth' 			=> 5,
		'link_before'     	=> '<span>',
		'link_after'      	=> '</span>',
		'walker' 			=> new Walker_Nav_Menu_Mfn,
	);
	
	// custom menu for pages
	if( $custom_menu = get_post_meta( mfn_ID(), 'mfn-post-menu', true ) ){
		$args['menu']			= $custom_menu;
	} else {
		$args['theme_location'] = 'main-menu';
	}
	
	wp_nav_menu( $args ); 
}

function mfn_wp_page_menu() 
{
	$args = array(
		'title_li' 		=> '0',
		'sort_column' 	=> 'menu_order',
		'depth' 		=> 5,
	);

	echo '<nav id="menu">'."\n";
		echo '<ul class="menu page-menu">'."\n";
			wp_list_pages($args); 
		echo '</ul>'."\n";
	echo '</nav>'."\n";
}


/* ---------------------------------------------------------------------------
 * Secondary menu
 * --------------------------------------------------------------------------- */
function mfn_wp_secondary_menu()
{
	$args = array(
		'container' 		=> 'nav',
		'container_id'		=> 'secondary-menu', 
		'menu_class'		=> 'secondary-menu', 
		'fallback_cb'		=> false,
		'theme_location' 	=> 'secondary-menu',
		'depth'				=> 2,
	);
	wp_nav_menu( $args );
}


/* ---------------------------------------------------------------------------
 * Languages menu
 * --------------------------------------------------------------------------- */
function mfn_wp_lang_menu()
{
	$args = array(
		'container' 		=> false,
		'fallback_cb'		=> false,
		'menu_class'		=> 'wpml-lang-dropdown',
		'theme_location' 	=> 'lang-menu',
		'depth'				=> 1,
	);
	wp_nav_menu( $args );
}


/* ---------------------------------------------------------------------------
 * Social menu
 * --------------------------------------------------------------------------- */
function mfn_wp_social_menu()
{
	$args = array(
		'container' 		=> 'nav',
		'container_id'		=> 'social-menu', 
		'menu_class'		=> 'social-menu', 
		'fallback_cb'		=> false,
		'theme_location' 	=> 'social-menu',
		'depth'				=> 1,
	);
	wp_nav_menu( $args );
}

function mfn_wp_social_menu_bottom()
{
	$args = array(
		'container' 		=> 'nav',
		'container_id'		=> 'social-menu', 
		'menu_class'		=> 'social-menu', 
		'fallback_cb'		=> false,
		'theme_location' 	=> 'social-menu-bottom',
		'depth'				=> 1,
	);
	wp_nav_menu( $args );
}

?>