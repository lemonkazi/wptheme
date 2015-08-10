<?php
/**
 * The Blog Sidebar containing the widget area.
 *
 * @package Betheme
 * @author Muffin group
 * @link http://muffingroup.com
 */

$posts_page_id = false;
if( get_option( 'page_for_posts' ) ){
	$posts_page_id = get_option( 'page_for_posts' );	// Setings / Reading
} elseif( mfn_opts_get( 'blog-page' ) ){
	$posts_page_id = mfn_opts_get( 'blog-page' );		// Theme Options / Getting Started / Blog	
}

$sidebars = mfn_opts_get( 'sidebars' );
$sidebar = get_post_meta($posts_page_id, 'mfn-post-sidebar', true);
$sidebar = $sidebars[$sidebar];

if( $_GET && key_exists('mfn-s', $_GET) ) $sidebar = $_GET['mfn-s']; // demo
?>

<?php if( mfn_sidebar_classes() ): ?>
<div class="four columns">
	<div class="widget-area clearfix <?php mfn_opts_show('sidebar-lines'); ?>">
		<?php 
			if ( ! dynamic_sidebar( $sidebar ) ){ 
				mfn_nosidebar(); 
			}
		?>
	</div>
</div>
<?php endif; ?>