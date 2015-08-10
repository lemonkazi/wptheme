<?php
/**
 * The Page Sidebar containing the widget area.
 *
 * @package Betheme
 * @author Muffin group
 * @link http://muffingroup.com
 */

$portfolio_page_id = mfn_opts_get( 'portfolio-page' );
$sidebars = mfn_opts_get( 'sidebars' );
$sidebar = get_post_meta($portfolio_page_id, 'mfn-post-sidebar', true);
$sidebar = $sidebars[$sidebar];
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