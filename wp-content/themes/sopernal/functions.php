<?php
/**
 * Twenty Fifteen functions and definitions
 *
 * Set up the theme and provides some helper functions, which are used in the
 * theme as custom template tags. Others are attached to action and filter
 * hooks in WordPress to change core functionality.
 *
 * When using a child theme you can override certain functions (those wrapped
 * in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before
 * the parent theme's file, so the child theme functions would be used.
 *
 * @link https://codex.wordpress.org/Theme_Development
 * @link https://codex.wordpress.org/Child_Themes
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are
 * instead attached to a filter or action hook.
 *
 * For more information on hooks, actions, and filters,
 * {@link https://codex.wordpress.org/Plugin_API}
 *
 * @package WordPress
 * @subpackage Twenty_Fifteen
 * @since Twenty Fifteen 1.0
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * @since Twenty Fifteen 1.0
 */
// if ( ! isset( $content_width ) ) {
// 	$content_width = 660;
// }

/**
 * Twenty Fifteen only works in WordPress 4.1 or later.
 */
// if ( version_compare( $GLOBALS['wp_version'], '4.1-alpha', '<' ) ) {
// 	require get_template_directory() . '/inc/back-compat.php';
// }

if (!class_exists('ReduxFramework') && file_exists(dirname(__FILE__) . '/ReduxFramework/ReduxCore/framework.php')) {
    require_once(dirname(__FILE__) . '/ReduxFramework/ReduxCore/framework.php');
}
if (!isset($sopernal_settings) && file_exists(dirname(__FILE__) . '/ReduxFramework/admin.php')) {
    require_once(dirname(__FILE__) . '/ReduxFramework/admin.php');
}

    /* VARIABLE DEFINITIONS
    ================================================== */
    define( 'SOPERNAL_TEMPLATE_PATH', get_template_directory() );
   define( 'SOPERNAL_INCLUDES_PATH', SOPERNAL_TEMPLATE_PATH . '/inc' );
   // define( 'SOPERNAL_FRAMEWORK_PATH', SOPERNAL_TEMPLATE_PATH . '/swift-framework' );
  //  define( 'SOPRNAL_WIDGETS_PATH', SOPERNAL_INCLUDES_PATH . '/widgets' );
    define( 'SOPERNAL_LOCAL_PATH', get_template_directory_uri() );

if ( ! function_exists( 'sopernal_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 *
 * @since Twenty Fifteen 1.0
 */
function sopernal_setup() {

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on sopernal, use a find and replace
	 * to change 'sopernal' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'sopernal', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * See: https://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 825, 510, true );

	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus( array(
		'primary' => __( 'Primary Menu',      'sopernal' ),
		'social'  => __( 'Social Links Menu', 'sopernal' ),
	) );
  //Add support for WordPress 3.0's custom menus
  add_action( 'init', 'ab_tf_register_my_menu' );
  //Register area for custom menu


	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption'
	) );

	/*
	 * Enable support for Post Formats.
	 *
	 * See: https://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link', 'gallery', 'status', 'audio', 'chat'
	) );

	$color_scheme  = sopernal_get_color_scheme();
	$default_color = trim( $color_scheme[0], '#' );

	// Setup the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'sopernal_custom_background_args', array(
		'default-color'      => $default_color,
		'default-attachment' => 'fixed',
	) ) );

	/*
	 * This theme styles the visual editor to resemble the theme style,
	 * specifically font, colors, icons, and column width.
	 */
	add_editor_style( array( 'css/editor-style.css', 'genericons/genericons.css', sopernal_fonts_url() ) );
}
endif; // sopernal_setup
add_action( 'after_setup_theme', 'sopernal_setup' );


function ab_tf_register_my_menu() {
  register_nav_menu( 'primary-menu', 'Primary Menu');
}





/**
 * Register widget area.
 *
 * @since Twenty Fifteen 1.0
 *
 * @link https://codex.wordpress.org/Function_Reference/register_sidebar
 */
function sopernal_widgets_init() {
	register_sidebar( array(
		'name'          => __( 'Widget Area', 'sopernal' ),
		'id'            => 'sidebar-1',
		'description'   => __( 'Add widgets here to appear in your sidebar.', 'sopernal' ),
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'sopernal_widgets_init' );

if ( ! function_exists( 'sopernal_fonts_url' ) ) :
/**
 * Register Google fonts for Twenty Fifteen.
 *
 * @since Twenty Fifteen 1.0
 *
 * @return string Google fonts URL for the theme.
 */
function sopernal_fonts_url() {
	$fonts_url = '';
	$fonts     = array();
	$subsets   = 'latin,latin-ext';

	/*
	 * Translators: If there are characters in your language that are not supported
	 * by Noto Sans, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Noto Sans font: on or off', 'sopernal' ) ) {
		$fonts[] = 'Noto Sans:400italic,700italic,400,700';
	}

	/*
	 * Translators: If there are characters in your language that are not supported
	 * by Noto Serif, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Noto Serif font: on or off', 'sopernal' ) ) {
		$fonts[] = 'Noto Serif:400italic,700italic,400,700';
	}

	/*
	 * Translators: If there are characters in your language that are not supported
	 * by Inconsolata, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Inconsolata font: on or off', 'sopernal' ) ) {
		$fonts[] = 'Inconsolata:400,700';
	}

	/*
	 * Translators: To add an additional character subset specific to your language,
	 * translate this to 'greek', 'cyrillic', 'devanagari' or 'vietnamese'. Do not translate into your own language.
	 */
	$subset = _x( 'no-subset', 'Add new subset (greek, cyrillic, devanagari, vietnamese)', 'sopernal' );

	if ( 'cyrillic' == $subset ) {
		$subsets .= ',cyrillic,cyrillic-ext';
	} elseif ( 'greek' == $subset ) {
		$subsets .= ',greek,greek-ext';
	} elseif ( 'devanagari' == $subset ) {
		$subsets .= ',devanagari';
	} elseif ( 'vietnamese' == $subset ) {
		$subsets .= ',vietnamese';
	}

	if ( $fonts ) {
		$fonts_url = add_query_arg( array(
			'family' => urlencode( implode( '|', $fonts ) ),
			'subset' => urlencode( $subsets ),
		), '//fonts.googleapis.com/css' );
	}

	return $fonts_url;
}
endif;

/**
 * JavaScript Detection.
 *
 * Adds a `js` class to the root `<html>` element when JavaScript is detected.
 *
 * @since Twenty Fifteen 1.1
 */
function sopernal_javascript_detection() {
	echo "<script>(function(html){html.className = html.className.replace(/\bno-js\b/,'js')})(document.documentElement);</script>\n";
}
add_action( 'wp_head', 'sopernal_javascript_detection', 0 );




   /* THEME OPTIONS FRAMEWORK
    ================================================== */
    // require_once( SF_INCLUDES_PATH . '/sf-colour-scheme.php' );
    // if ( ! function_exists( 'sf_include_theme_options' ) ) {
    //     function sf_include_theme_options() {
    //         if ( ! class_exists( 'ReduxFramework' ) ) {
    //             require_once( SF_INCLUDES_PATH . '/options/framework.php' );
    //         }
    //         require_once( SF_INCLUDES_PATH . '/option-extensions/loader.php' );
    //         require_once( SF_INCLUDES_PATH . '/sf-options.php' );
    //         global $sf_cardinal_options, $sf_options;
    //         $sf_options = $sf_cardinal_options;
    //     }

    //     add_action( 'init', 'sf_include_theme_options', 10 );
    // }



















/**
 * Enqueue scripts and styles.
 *
 * @since Sopernal 1.0
 */

/* LOAD STYLESHEETS
    ================================================== */
    if ( ! function_exists( 'sopernal_amy_styles' ) ) {
        function sopernal_amy_styles() {

           // global $sf_options;
         //   $enable_responsive = $sf_options['enable_responsive'];
          //  $enable_rtl        = $sf_options['enable_rtl'];
            wp_register_style( 'sopernal-main', get_stylesheet_directory_uri() . '/style.css', array(), null, 'all' );
         

            wp_register_style( 'bootstrap', SOPERNAL_LOCAL_PATH . '/css/bootstrap.min.css', array(), null, 'all' );
            wp_register_style( 'fontawesome', SOPERNAL_LOCAL_PATH . '/css/font-awesome.min.css', array(), null, 'all' );
          //  wp_register_style( 'ssgizmo', SOPERNAL_LOCAL_PATH . '/css/ss-gizmo.css', array(), null, 'all' );
             wp_register_style( 'sopernal-rtl', SOPERNAL_LOCAL_PATH . '/rtl.css', array(), null, 'all' );
           // wp_register_style( 'sopernal-woocommerce', SOPERNAL_LOCAL_PATH . '/css/sf-woocommerce.css', array(), null, 'screen' );
            wp_register_style( 'sopernal-responsive', SOPERNAL_LOCAL_PATH . '/layout/css/style-responsive.css', array(), null, 'screen' );
            wp_register_style( 'sopernal-red', SOPERNAL_LOCAL_PATH . '/layout/themes/red.css', array(), null, 'all' );  
            wp_enqueue_style( 'bootstrap' );
           // wp_enqueue_style( 'ssgizmo' );
            wp_enqueue_style( 'fontawesome' );
            wp_enqueue_style( 'sopernal-main' );
            wp_enqueue_style( 'sopernal-red' );

            // if ( sf_woocommerce_activated() ) {
            //     wp_enqueue_style( 'sf-woocommerce' );
            // }

            if ( is_rtl() || $enable_rtl || isset( $_GET['RTL'] ) ) {
                wp_enqueue_style( 'sopernal-rtl' );
            }

          //  if ( $enable_responsive ) {
                wp_enqueue_style( 'sopernal-responsive' );
          //  }

        }

        add_action( 'wp_enqueue_scripts', 'sopernal_amy_styles', 99 );
    }


 	/* LOAD FRONTEND SCRIPTS
    ================================================== */
    if ( ! function_exists( 'sopernal_enqueue_scripts' ) ) {
        function sopernal_enqueue_scripts() {

            // Variables
           // global $sf_options;
            // $enable_rtl         = $sf_options['enable_rtl'];
            // $enable_min_scripts = $sf_options['enable_min_scripts'];
            // $post_type          = get_query_var( 'post_type' );

            // Register Scripts
          
          wp_register_script( 'layout-js', SOPERNAL_LOCAL_PATH . '/js/layout.js', 'jquery', null, true );
          
            wp_register_script( 'sopernal-bootstrap-js', SOPERNAL_LOCAL_PATH . '/js/bootstrap.min.js', 'jquery', null, true );
            wp_register_script( 'sopernal-flexslider', SOPERNAL_LOCAL_PATH . '/js/jquery.flexslider-min.js', 'jquery', null, true );
            wp_register_script( 'sopernal-flexslider-rtl', SOPERNAL_LOCAL_PATH . '/js/jquery.flexslider-rtl-min.js', 'jquery', null, true );
			wp_register_script( 'sopernal-backtotop-js', SOPERNAL_LOCAL_PATH . '/js/back-to-top.js', 'jquery', null, true );
           // wp_register_script( 'sopernal-isotope', SOPERNAL_LOCAL_PATH . '/js/jquery.isotope.min.js', 'jquery', null, true );
           // wp_register_script( 'sopernal-imagesLoaded', SOPERNAL_LOCAL_PATH . '/js/imagesloaded.js', 'jquery', null, true );
           // wp_register_script( 'sopernal-owlcarousel', SOPERNAL_LOCAL_PATH . '/js/owl.carousel.min.js', 'jquery', null, true );
           // wp_register_script( 'sopernal-jquery-ui', SOPERNAL_LOCAL_PATH . '/js/jquery-ui-1.10.2.custom.min.js', 'jquery', null, true );
         //   wp_register_script( 'sopernal-ilightbox', SOPERNAL_LOCAL_PATH . '/js/ilightbox.min.js', 'jquery', null, true );
            wp_register_script( 'sopernal-maps', '//maps.google.com/maps/api/js?sensor=false', 'jquery', null, true );
           // wp_register_script( 'sopernal-elevatezoom', SOPERNAL_LOCAL_PATH . '/js/jquery.elevateZoom.min.js', 'jquery', null, true );
           // wp_register_script( 'sopernal-infinite-scroll', SOPERNAL_LOCAL_PATH . '/js/jquery.infinitescroll.min.js', 'jquery', null, true );
          //  wp_register_script( 'sopernal-theme-scripts', SOPERNAL_LOCAL_PATH . '/js/theme-scripts.js', 'jquery', null, true );
          //  wp_register_script( 'sopernal-theme-scripts-min', SOPERNAL_LOCAL_PATH . '/js/sf-scripts.min.js', 'jquery', null, true );
          //  wp_register_script( 'sopernal-theme-scripts-rtl-min', SOPERNAL_LOCAL_PATH . '/js/sf-scripts-rtl.min.js', 'jquery', null, true );
               wp_register_script( 'sopernal-functions', SOPERNAL_LOCAL_PATH . '/js/functions.js', 'jquery', null, true );
         //   wp_register_script( 'sopernal-functions-min', SOPERNAL_LOCAL_PATH . '/js/functions.min.js', 'jquery', null, true );

            // jQuery
            
            wp_enqueue_script( 'jquery' );


            if ( ! is_admin() ) {


                // Theme Scripts
                if ( $enable_min_scripts ) {
                    if ( is_rtl() || $enable_rtl || isset( $_GET['RTL'] ) ) {
                       // wp_enqueue_script( 'sf-theme-scripts-rtl-min' );
                    } else {
                        wp_enqueue_script( 'sf-theme-scripts-min' );
                    }
                    if ( ! is_singular( 'tribe_events' ) && $post_type != 'tribe_events' && ! is_singular( 'tribe_venue' ) && $post_type != 'tribe_venue' ) {
                        wp_enqueue_script( 'sopernal-maps' );
                    }
                  //  wp_enqueue_script( 'sopernal-functions' );

                } else {
                    wp_enqueue_script( 'sopernal-bootstrap-js' );
                   // wp_enqueue_script( 'sf-jquery-ui' );
                    wp_enqueue_script('layout-js');

                    if ( is_rtl() || $enable_rtl || isset( $_GET['RTL'] ) ) {
                        wp_enqueue_script( 'sopernal-flexslider-rtl' );
                    } else {
                        wp_enqueue_script( 'sopernal-flexslider' );
                    }
					  wp_enqueue_script( 'sopernal-backtotop-js' );
                  //  wp_enqueue_script( 'sf-owlcarousel' );
                  //  wp_enqueue_script( 'sf-theme-scripts' );
                   // wp_enqueue_script( 'sf-ilightbox' );

                    if ( ! is_singular( 'tribe_events' ) && $post_type != 'tribe_events' && ! is_singular( 'tribe_venue' ) && $post_type != 'tribe_venue' ) {
                        wp_enqueue_script( 'sopernal-maps' );
                    }

                  //  wp_enqueue_script( 'sf-isotope' );
                   // wp_enqueue_script( 'sf-imagesLoaded' );
                   // wp_enqueue_script( 'sf-infinite-scroll' );

                    // if ( $sf_options['enable_product_zoom'] ) {
                    //     wp_enqueue_script( 'sf-elevatezoom' );
                    // }

                   // wp_enqueue_script( 'sopernal-functions' );
                }

                // Comments reply
                if ( is_singular() && comments_open() ) {
                    wp_enqueue_script( 'comment-reply' );
                }
            }
            
        }

        add_action( 'wp_enqueue_scripts', 'sopernal_enqueue_scripts' );
    }

    /* LOAD BACKEND SCRIPTS
    ================================================== */
    function sf_admin_scripts() {
        wp_register_script( 'admin-functions', get_template_directory_uri() . '/js/sf-admin.js', 'jquery', '1.0', true );
        wp_enqueue_script( 'admin-functions' );
    }

    add_action( 'admin_init', 'sf_admin_scripts' );

















    

/**
 * Add featured image as background image to post navigation elements.
 *
 * @since Twenty Fifteen 1.0
 *
 * @see wp_add_inline_style()
 */
function sopernal_post_nav_background() {
	if ( ! is_single() ) {
		return;
	}

	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );
	$css      = '';

	if ( is_attachment() && 'attachment' == $previous->post_type ) {
		return;
	}

	if ( $previous &&  has_post_thumbnail( $previous->ID ) ) {
		$prevthumb = wp_get_attachment_image_src( get_post_thumbnail_id( $previous->ID ), 'post-thumbnail' );
		$css .= '
			.post-navigation .nav-previous { background-image: url(' . esc_url( $prevthumb[0] ) . '); }
			.post-navigation .nav-previous .post-title, .post-navigation .nav-previous a:hover .post-title, .post-navigation .nav-previous .meta-nav { color: #fff; }
			.post-navigation .nav-previous a:before { background-color: rgba(0, 0, 0, 0.4); }
		';
	}

	if ( $next && has_post_thumbnail( $next->ID ) ) {
		$nextthumb = wp_get_attachment_image_src( get_post_thumbnail_id( $next->ID ), 'post-thumbnail' );
		$css .= '
			.post-navigation .nav-next { background-image: url(' . esc_url( $nextthumb[0] ) . '); border-top: 0; }
			.post-navigation .nav-next .post-title, .post-navigation .nav-next a:hover .post-title, .post-navigation .nav-next .meta-nav { color: #fff; }
			.post-navigation .nav-next a:before { background-color: rgba(0, 0, 0, 0.4); }
		';
	}

	wp_add_inline_style( 'sopernal-style', $css );
}
add_action( 'wp_enqueue_scripts', 'sopernal_post_nav_background' );

/**
 * Display descriptions in main navigation.
 *
 * @since Twenty Fifteen 1.0
 *
 * @param string  $item_output The menu item output.
 * @param WP_Post $item        Menu item object.
 * @param int     $depth       Depth of the menu.
 * @param array   $args        wp_nav_menu() arguments.
 * @return string Menu item with possible description.
 */
function sopernal_nav_description( $item_output, $item, $depth, $args ) {
	if ( 'primary' == $args->theme_location && $item->description ) {
		$item_output = str_replace( $args->link_after . '</a>', '<div class="menu-item-description">' . $item->description . '</div>' . $args->link_after . '</a>', $item_output );
	}

	return $item_output;
}
add_filter( 'walker_nav_menu_start_el', 'sopernal_nav_description', 10, 4 );

class My_Walker_Nav_Menu extends Walker_Nav_Menu {

   function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ){
        $GLOBALS['dd_children'] = ( isset($children_elements[$element->ID]) )? 1:0;
        $GLOBALS['dd_depth'] = (int) $depth;
        parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
    }
  function start_lvl(&$output, $depth) {
    $indent = str_repeat("\t", $depth);
    $output .= "\n$indent<ul class=\"dropdown-menu\">\n";
  }
  function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) 
  {
    global $wp_query, $wpdb;
    $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
    $li_attributes = '';
    $class_names = $value = '';
    $classes = empty( $item->classes ) ? array() : (array) $item->classes;
    
    //Add class and attribute to LI element that contains a submenu UL.
    if ($args->has_children){
      $classes[]    = 'dropdown';
      $li_attributes .= 'data-dropdown="dropdown"';
    }
    $classes[] = 'menu-item-' . $item->ID;
    //If we are on the current page, add the active class to that menu item.
    $classes[] = ($item->current) ? 'active' : '';
    //Make sure you still add all of the WordPress classes.
    $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
    $class_names = ' class="' . esc_attr( $class_names ) . '"';
    $id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
    $id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';
    $has_children = $wpdb->get_var(
      $wpdb->prepare("
        SELECT COUNT(*) FROM $wpdb->postmeta
        WHERE meta_key = %s
        AND meta_value = %d
      ", '_menu_item_menu_item_parent', $item->ID)
    );



    $output .= $indent . '<li' . $id . $value . $class_names .'>';
    

    $output .= $indent . '<li' . $id . $value . $class_names . $li_attributes . '>';
    //Add attributes to link element.
    $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
    $attributes .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target     ) .'"' : '';
    $attributes .= ! empty( $item->xfn ) ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
    $attributes .= ! empty( $item->url ) ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
  // Check if menu item is in main menu

    if ( $depth == 0 && $has_children > 0  ) {
        // These lines adds your custom class and attribute
        $attributes .= ' class="dropdown-toggle"';
        $attributes .= ' data-toggle="dropdown"';
    }
    $item_output = $args->before;
    $item_output .= '<a'. $attributes .'>';
    $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
   // Add the caret if menu level is 0
    if ( $depth == 0 && $has_children > 0  ) {
        $item_output .= ' <b class="caret"></b>';
    }
   $item_output .= '</a>';
    $item_output .= $args->after;
    $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
  }
}

add_filter('nav_menu_css_class','add_parent_css',10,2);
function  add_parent_css($classes, $item){
     global  $dd_depth, $dd_children;
     $classes[] = 'depth'.$dd_depth;
     if($dd_children)
         $classes[] = 'dropdown';
       
    return $classes;
}

//Add class to parent pages to show they have subpages (only for automatic wp_nav_menu)

function add_parent_class( $css_class, $page, $depth, $args )
{
   if ( ! empty( $args['has_children'] ) )
       $css_class[] = 'dropdown';
   return $css_class;
}
add_filter( 'page_css_class', 'add_parent_class', 10, 4 );

function add_search_to_wp_menu ( $items, $args ) {
  if( 'primary' === $args -> theme_location ) {
$items .= '<li class="menu-search"><span class="sep"></span><i class="fa fa-search search-btn"></i>
                <div class="search-box">';

                
                
                  

                
           
$items .= '<form method="get" action="' . get_bloginfo('home') . '/">

<div class="input-group">
<input type="text" placeholder="Search" class="form-control">
<span class="input-group-btn">
                        <button class="btn btn-primary" type="submit">Search</button>
                      </span>
</div></form>';
$items .= '</div> </li>';
  }
return $items;
}
add_filter('wp_nav_menu_items','add_search_to_wp_menu',10,2);
/**
 * Add a `screen-reader-text` class to the search form's submit button.
 *
 * @since Twenty Fifteen 1.0
 *
 * @param string $html Search form HTML.
 * @return string Modified search form HTML.
 */
function sopernal_search_form_modify( $html ) {
	return str_replace( 'class="search-submit"', 'class="search-submit screen-reader-text"', $html );
}
add_filter( 'get_search_form', 'sopernal_search_form_modify' );

/**
 * Implement the Custom Header feature.
 *
 * @since Twenty Fifteen 1.0
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 *
 * @since Twenty Fifteen 1.0
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Customizer additions.
 *
 * @since Twenty Fifteen 1.0
 */
require get_template_directory() . '/inc/customizer.php';

// Footer widget 1/3
if (function_exists('register_sidebar')) {
	register_sidebar(array(
		'name' => 'Footer 1/3',
		'id' => 'footer-1',
		'description' => ' footer area',
		
	));
}
// Footer widget 2/3
if (function_exists('register_sidebar')) {
	register_sidebar(array(
		'name' => 'Footer 2/3',
		'id' => 'footer-2',
		'description' => '2/3 footer area'
	));
}
// Footer widget 3/3
if (function_exists('register_sidebar')) {
	register_sidebar(array(
		'name' => 'Footer 3/3',
		'id' => 'footer-3',
		'description' => '3/3 footer area'
	));
}
