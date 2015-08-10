<?php
/**
 * @package Betheme
 * @author Muffin group
 * @link http://muffingroup.com
 */

header( 'Content-type: text/css;' );
	
$url 	= dirname( __FILE__ );
$strpos = strpos( $url, 'wp-content' );
$base 	= substr( $url, 0, $strpos );

require_once( $base .'wp-load.php' );
?>

/******************* Background ********************/
	
	html { 
		background-color: <?php mfn_opts_show( 'background-body', '#FCFCFC' ) ?>;
	}
	
	#Wrapper, #Content { 
		background-color: <?php mfn_opts_show( 'background-body', '#FCFCFC' ) ?>;
	}
	
	<?php if( mfn_opts_get( 'img-subheader-bg' ) ): ?>
		body:not(.template-slider) #Header_wrapper { background-image: url("<?php mfn_opts_show( 'img-subheader-bg' ) ?>"); }
	<?php endif; ?>
	
	<?php if( mfn_opts_get( 'subheader-image' ) ): ?>
		#Subheader { background-image: url("<?php mfn_opts_show( 'subheader-image' ) ?>"); }
	<?php endif; ?>
	
	<?php if( mfn_opts_get( 'footer-bg-img' ) ): ?>
		#Footer { background-image: url("<?php mfn_opts_show( 'footer-bg-img' ) ?>"); }
	<?php endif; ?>

/********************** Fonts **********************/

 	body, #Subheader .title, button, span.date_label, .timeline_items li h3 span, input[type="submit"], input[type="reset"], input[type="button"],
	input[type="text"], input[type="password"], input[type="tel"], input[type="email"], textarea, select, .offer_li .title h3 {
		<?php $font = str_replace( '#', '', mfn_opts_get( 'font-content', 'Roboto' ) ); ?>
		font-family: <?php echo $font; ?>, Arial, Tahoma, sans-serif;
		font-weight: 400;
	}
	
	#menu > ul > li > a, #header_action_button, #header_cart {
		<?php $font = str_replace( '#', '', mfn_opts_get( 'font-menu', 'Roboto' ) ); ?>
		font-family: <?php echo $font; ?>, Arial, Tahoma, sans-serif;
		font-weight: 400;
	}
	
	h1, .text-logo #logo {
		<?php $font = str_replace( '#', '', mfn_opts_get( 'font-headings', 'Patua One' ) ); ?>
		font-family: <?php echo $font; ?>, Arial, Tahoma, sans-serif;
		font-weight: 300;
	}
	
	h2 {
		<?php $font = str_replace( '#', '', mfn_opts_get( 'font-headings', 'Patua One' ) ); ?>
		font-family: <?php echo $font; ?>, Arial, Tahoma, sans-serif;
		font-weight: 300;
	}
	
	h3 {
		<?php $font = str_replace( '#', '', mfn_opts_get( 'font-headings', 'Patua One' ) ); ?>
		font-family: <?php echo $font; ?>, Arial, Tahoma, sans-serif;
		font-weight: 300;
	}
	
	h4 {
		<?php $font = str_replace( '#', '', mfn_opts_get( 'font-headings', 'Patua One' ) ); ?>
		font-family: <?php echo $font; ?>, Arial, Tahoma, sans-serif;
		font-weight: 300;
	}
	
	h5 {
		<?php $font = str_replace( '#', '', mfn_opts_get( 'font-headings-small', 'Roboto' ) ); ?>
		font-family: <?php echo $font; ?>, Arial, Tahoma, sans-serif;
		font-weight: 700;
	}
	
	blockquote {
		<?php $font = str_replace( '#', '', mfn_opts_get( 'font-blockquote', 'Patua One' ) ); ?>
		font-family: <?php echo $font; ?>, Arial, Tahoma, sans-serif;
	}


/********************** Font sizes **********************/

/* Body */

	body {
		font-size: <?php mfn_opts_show( 'font-size-content', '13' ) ?>px;
		<?php $line_height = mfn_opts_get( 'font-size-content', '13' ) + 8; ?>
		line-height: <?php echo $line_height; ?>px;		
	}
	
	#menu > ul > li > a {	
		font-size: <?php mfn_opts_show( 'font-size-menu', '14' ) ?>px;
	}
	
/* Headings */

	h1, #Subheader .title, .text-logo #logo { 
		font-size: <?php mfn_opts_show( 'font-size-h1', '25' ) ?>px;
		<?php $line_height = mfn_opts_get( 'font-size-h1', '25' ) + 0; ?>
		line-height: <?php echo $line_height; ?>px;
	}
	
	h2 { 
		font-size: <?php mfn_opts_show( 'font-size-h2', '30' ) ?>px;
		<?php $line_height = mfn_opts_get( 'font-size-h2', '30' ) + 0; ?>
		line-height: <?php echo $line_height; ?>px;
	}
	
	h3 {
		font-size: <?php mfn_opts_show( 'font-size-h3', '25' ) ?>px;
		<?php $line_height = mfn_opts_get( 'font-size-h3', '25' ) + 2; ?>
		line-height: <?php echo $line_height; ?>px;
	}
	
	h4 {
		font-size: <?php mfn_opts_show( 'font-size-h4', '21' ) ?>px;
		<?php $line_height = mfn_opts_get( 'font-size-h4', '21' ) + 4; ?>
		line-height: <?php echo $line_height; ?>px;
	}
	
	h5 {
		font-size: <?php mfn_opts_show( 'font-size-h5', '15' ) ?>px;
		<?php $line_height = mfn_opts_get( 'font-size-h5', '15' ) + 5; ?>
		line-height: <?php echo $line_height; ?>px;
	}
	
	h6 {
		font-size: <?php mfn_opts_show( 'font-size-h6', '13' ) ?>px;
		<?php $line_height = mfn_opts_get( 'font-size-h6', '13' ) + 7; ?>
		line-height: <?php echo $line_height; ?>px;
	}
