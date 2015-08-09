<?php
/**
 * 404 page.
 *
 * @package Betheme
 * @author Muffin group
 * @link http://muffingroup.com
 */

$translate['404-title'] = mfn_opts_get('translate') ? mfn_opts_get('translate-404-title','Ooops... Error 404') : __('Ooops... Error 404','betheme');
$translate['404-subtitle'] = mfn_opts_get('translate') ? mfn_opts_get('translate-404-subtitle','We`re sorry, but the page you are looking for doesn`t exist.') : __('We are sorry, but the page you are looking for does not exist.','betheme');
$translate['404-text'] = mfn_opts_get('translate') ? mfn_opts_get('translate-404-text','Please check entered address and try again <em>or</em>') : __('Please check entered address and try again or ','betheme');
$translate['404-btn'] = mfn_opts_get('translate') ? mfn_opts_get('translate-404-btn','go to homepage') : __('go to homepage','betheme');
?><!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" lang="en"> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" lang="en"> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" lang="en"> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!--><html <?php language_attributes(); ?>> <!--<![endif]-->

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
<?php wp_head();?>
</head>

<!-- body -->
<body <?php body_class('mfn-404'); ?>>
	
	<div id="Error_404">
		<div class="container">
			<div class="column one">
				<div class="error_pic">
					<i class="<?php mfn_opts_show('error404-icon','icon-traffic-cone'); ?>"></i>
				</div>
				<div class="error_desk">
					<h2><?php echo $translate['404-title']; ?></h2>
					<h4><?php echo $translate['404-subtitle']; ?></h4>
					<p><span class="check"><?php echo $translate['404-text']; ?></span> <a class="button button_filled" href="<?php echo site_url(); ?>"><?php echo $translate['404-btn']; ?></a></p>
				</div>				
			</div>
		</div>
	</div>

	<!-- wp_footer() -->
	<?php wp_footer(); ?>

</body>
</html>