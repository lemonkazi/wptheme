<?php
/**
 * The template for displaying all pages.
 *
 * @package Betheme
 * @author Muffin group
 * @link http://muffingroup.com
 */

get_header();
?>
	
<!-- #Content -->
<div id="Content">
	<div class="content_wrapper clearfix">

		<!-- .sections_group -->
		<div class="sections_group">
			<?php 
				while ( have_posts() ){
					the_post();							// Post Loop	
					mfn_builder_print( get_the_ID() );	// Content Builder & WordPress Editor Content
				}
			?>
		</div>
		
		<!-- .four-columns - sidebar -->
		<?php if( is_active_sidebar( 'forum' ) ):  ?>
			<div class="four columns">
				<div class="widget-area clearfix <?php mfn_opts_show('sidebar-lines'); ?>">
					<?php dynamic_sidebar( 'forum' ); ?>
				</div>
			</div>
		<?php endif; ?>

	</div>
</div>

<?php get_footer(); ?>