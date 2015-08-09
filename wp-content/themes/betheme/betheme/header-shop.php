<?php
/**
 * The Header for our theme.
 *
 * @package Betheme
 * @author Muffin group
 * @link http://muffingroup.com
 */
?><!DOCTYPE html>
<?php 
	if( $_GET && key_exists('mfn-rtl', $_GET) ):
		echo '<html class="no-js" lang="ar" dir="rtl">';
	else:
?>
<html class="no-js" <?php language_attributes(); ?> <?php mfn_tag_schema(); ?>>
<?php endif; ?>

<!-- head -->
<head>

<!-- meta -->
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<?php if( mfn_opts_get('responsive') ) echo '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">'; ?>

<title itemprop="name"><?php
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

<?php 
	$body_classes = '';
	if( is_active_sidebar( 'shop' ) ) $body_classes .= 'with_aside aside_right';
?>

<!-- body -->
<body <?php body_class( $body_classes ); ?>>
	
	<?php get_template_part( 'includes/header', 'sliding-area' ); ?>
	
	<?php if( mfn_header_style( true ) == 'header-creative' ) get_template_part( 'includes/header', 'creative' ); ?>
	
	<!-- #Wrapper -->
	<div id="Wrapper">
	
		<?php 
			// Header Featured Image -----------
			$header_style = '';
			
			// Image
			if( $shop_id = woocommerce_get_page_id( 'shop' ) ){
				if( has_post_thumbnail( $shop_id ) ){
					$subheader_image = wp_get_attachment_image_src( get_post_thumbnail_id( $shop_id ), 'full' );
					$header_style .= 'style="background-image:url('. $subheader_image[0] .');"';
				}
			}
			
			// Attachment
			if( mfn_opts_get('img-subheader-attachment') == 'fixed' ){
				$header_style .= ' class="bg-fixed"';
			} elseif( mfn_opts_get('img-subheader-attachment') == 'parallax' ){
				$header_style .= ' class="bg-parallax" data-stellar-background-ratio="0.5"';
			}
		?>
		
		<?php 
			if( is_shop() && mfn_header_style() == 'header-below' ) echo mfn_slider( $shop_id );
		?>
		
		<!-- #Header_bg -->
		<div id="Header_wrapper" <?php echo $header_style; ?>>
	
			<!-- #Header -->
			<header id="Header">
				<?php if( mfn_header_style( true ) != 'header-creative' ) get_template_part( 'includes/header', 'top-area' ); ?>	
				<?php if( is_shop() && mfn_header_style() != 'header-below' ) echo mfn_slider( $shop_id ); ?>
			</header>
			
			<?php 
				if( is_product() || ! mfn_slider( $shop_id ) ){
					echo '<div id="Subheader">';
						echo '<div class="container">';
							echo '<div class="column one">';
							
								// Title
								$h = is_product() ? 2 : 1; // h1 - shop, h2 - single product
								echo '<h'. $h .' class="title">';
									woocommerce_page_title();
								echo '</h'. $h .'>';
								add_filter( 'woocommerce_show_page_title', create_function( false, 'return false;' ) );
								
								// Breadcrumbs
								if( ! mfn_opts_get('subheader') ){
									$home = mfn_opts_get('translate') ? mfn_opts_get('translate-home','Home') : __('Home','betheme');
									$woo_crumbs_args = apply_filters( 'woocommerce_breadcrumb_defaults', array(
										'delimiter'   => false,
										'wrap_before' => '<ul class="breadcrumbs woocommerce-breadcrumb">',
										'wrap_after'  => '</ul>',
										'before'      => '<li>',
										'after'       => '<span><i class="icon-right-open"></i></span></li>',
										'home'        => $home,
									) );
									woocommerce_breadcrumb( $woo_crumbs_args );
								}
		
							echo '</div>';
						echo '</div>';
					echo '</div>';
				}
			?>
			
		</div>