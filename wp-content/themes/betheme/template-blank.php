<?php
/**
 * Template Name: Blank Page
 *
 * @package Betheme
 * @author Muffin Group
 */
?><!DOCTYPE html>
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html class="no-js" <?php language_attributes(); ?>> <!--<![endif]-->

<!-- head -->
<head>

<!-- meta -->
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<?php if( mfn_opts_get('responsive') ) echo '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">'; ?>

<title><?php
if( mfn_title() ){
	echo mfn_title();
} else {
	global $page, $paged;
	wp_title( '|', true, 'right' );
	bloginfo( 'name' );
	if ( $paged >= 2 || $page >= 2 ) echo ' | ' . sprintf( __( 'Page %s', 'betheme' ), max( $paged, $page ) );
}
?></title>

<?php do_action('wp_seo'); ?>

<link rel="shortcut icon" href="<?php mfn_opts_show('favicon-img',THEME_URI .'/images/favicon.ico'); ?>" type="image/x-icon" />	

<!-- wp_head() -->
<?php wp_head(); ?>
</head>

<!-- body -->
<body <?php body_class( 'template-blank' ); ?>>

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
			<?php get_sidebar(); ?>
	
		</div>
	</div>

<!-- wp_footer() -->
<?php wp_footer(); ?>

</body>
</html>