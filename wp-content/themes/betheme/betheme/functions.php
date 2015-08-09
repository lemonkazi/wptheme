<?php
/**
 * Theme Functions
 *
 * @package Betheme
 * @author Muffin group
 * @link http://muffingroup.com
 */


define( 'THEME_DIR', get_template_directory() );
define( 'THEME_URI', get_template_directory_uri() );

define( 'THEME_NAME', 'betheme' );
define( 'THEME_VERSION', '5.0' );

define( 'LIBS_DIR', THEME_DIR. '/functions' );
define( 'LIBS_URI', THEME_URI. '/functions' );
define( 'LANG_DIR', THEME_DIR. '/languages' );

add_filter( 'widget_text', 'do_shortcode' );


/* ---------------------------------------------------------------------------
 * Loads Theme Textdomain
 * --------------------------------------------------------------------------- */
load_theme_textdomain( 'betheme', LANG_DIR );
load_theme_textdomain( 'mfn-opts', LANG_DIR );


/* ---------------------------------------------------------------------------
 * Loads the Options Panel
 * --------------------------------------------------------------------------- */
function mfn_admin_scripts() {
// 	wp_enqueue_script( 'jquery-ui-droppable' );
	wp_enqueue_script( 'jquery-ui-sortable' );
}    
add_action( 'wp_enqueue_scripts', 'mfn_admin_scripts' );
add_action( 'admin_enqueue_scripts', 'mfn_admin_scripts' );
	
require( THEME_DIR .'/muffin-options/theme-options.php' );


/* ---------------------------------------------------------------------------
 * Loads Theme Functions
 * --------------------------------------------------------------------------- */

// Functions --------------------------------------------------------------------
require_once( LIBS_DIR .'/theme-functions.php' );

// Header -----------------------------------------------------------------------
require_once( LIBS_DIR .'/theme-head.php' );

// Menu -------------------------------------------------------------------------
require_once( LIBS_DIR .'/theme-menu.php' );
require_once( LIBS_DIR .'/theme-mega-menu.php' );

// Meta box ---------------------------------------------------------------------
require_once( LIBS_DIR .'/meta-functions.php' );

// Custom post types ------------------------------------------------------------
require_once( LIBS_DIR .'/meta-client.php' );
require_once( LIBS_DIR .'/meta-offer.php' );
require_once( LIBS_DIR .'/meta-layout.php' );
require_once( LIBS_DIR .'/meta-portfolio.php' );
require_once( LIBS_DIR .'/meta-slide.php' );
require_once( LIBS_DIR .'/meta-testimonial.php' );

require_once( LIBS_DIR .'/meta-page.php' );
require_once( LIBS_DIR .'/meta-post.php' );

// Content ----------------------------------------------------------------------
require_once( THEME_DIR .'/includes/content-post.php' );
require_once( THEME_DIR .'/includes/content-portfolio.php' );

// Shortcodes -------------------------------------------------------------------
require_once( LIBS_DIR .'/theme-shortcodes.php' );

// Widgets ----------------------------------------------------------------------
require_once( LIBS_DIR .'/widget-functions.php' );

require_once( LIBS_DIR .'/widget-flickr.php' );
require_once( LIBS_DIR .'/widget-login.php' );
require_once( LIBS_DIR .'/widget-menu.php' );
require_once( LIBS_DIR .'/widget-recent-comments.php' );
require_once( LIBS_DIR .'/widget-recent-posts.php' );
require_once( LIBS_DIR .'/widget-tag-cloud.php' );

// TinyMCE ----------------------------------------------------------------------
require_once( LIBS_DIR .'/tinymce/tinymce.php' );

// Plugins ---------------------------------------------------------------------- 
require_once( LIBS_DIR .'/importer/import.php' );

require_once( LIBS_DIR .'/class-love.php' );
require_once( LIBS_DIR .'/class-tgm-plugin-activation.php' );

require_once( LIBS_DIR .'/plugins/visual-composer.php' );

// WooCommerce specified functions
if( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ){
	require_once( LIBS_DIR .'/theme-woocommerce.php' );
}

// Hide activation and update specific parts ------------------------------------

// Slider Revolution
if( function_exists( 'set_revslider_as_theme' ) ){
	set_revslider_as_theme();
}

// LayerSlider
add_action('layerslider_ready', 'mfn_layerslider_overrides');
function mfn_layerslider_overrides() {
	// Disable auto-updates
	$GLOBALS['lsAutoUpdateBox'] = false;
}

// Visual Composer 
add_action( 'vc_before_init', 'mfn_vcSetAsTheme' );
function mfn_vcSetAsTheme() {
	vc_set_as_theme();
}

?>