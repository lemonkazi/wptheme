<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "site-content" div.
 *
 * @package WordPress
 * @subpackage Sopernal
 * @since Sopernal 1.0
 */
?>
<!DOCTYPE html>
<?php global $sopernal_settings; 

?>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width">
	<title>
		<?php 
		if ( !is_front_page() ) { 
			echo wp_title( ' ', true, 'left' ); 
			echo ' | '; 
		} 
			 echo bloginfo('name');  ?> 
	</title>

	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

	<link rel="shortcut icon" href="<?php echo $sopernal_settings['fav-ico']['url']; ?>" />
	
 <meta name="twitter:card" content="summary_large_image">
 <?php 
if(is_single() && has_post_thumbnail() || is_page() && has_post_thumbnail() ) {
	$sopernal_srcf = wp_get_attachment_image_src( get_post_thumbnail_id($post->ID),'full', true );
}?>					
<meta name="twitter:image" content="<?php if(isset($sopernal_srcf[0])){echo $sopernal_srcf[0];} ?>">
<meta property="og:title" content="<?php if ( is_single() || is_page() ) { the_title(); }else{ home_url(); }?>" />
<meta property="og:type" content="article" />
<meta property="og:url" content="<?php if ( is_single() || is_page() ) { the_permalink(); }else{ echo get_site_url(); }?>" /> 
<meta name="twitter:url" content="<?php if ( is_single() || is_page() ) { the_permalink(); }else{ echo get_site_url(); }?>">
<meta name="og:description" content="<?php if ( is_single() || is_page() ) { if(get_the_excerpt()!=''){echo get_the_excerpt();}else{ the_title(); }}else{home_url(); echo " - "; bloginfo('description');} ?>" />

<meta name="twitter:site" content="@it4gen">
	<!--[if lte IE 8]><style>.ss-container, .header-white,.hidden, #nav, .right-bottom-nav, .ss-row-clear{display:none;} .support-note .note-ie{display:block;}</style><![endif]-->
 
<?php  ?>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

	<!--[if lt IE 9]>
	<script src="<?php echo esc_url( get_template_directory_uri() ); ?>/js/html5.js"></script>
	<![endif]-->
	<?php wp_head(); ?>
	<script type="text/javascript">
		function myFunction() {
    

            event.preventDefault();//the default action of the event will not be triggered
            
            jQuery(".header").toggleClass("menuOpened");
            jQuery(".header").find(".header-navigation").toggle(300);
        }

	</script>
</head>

<body <?php body_class(); ?>>
<!-- <div id="page" class="hfeed site"> -->
<!-- <a class="skip-link screen-reader-text" href="#content">
	<?php _e( 'Skip to content', 'sopernal' ); ?>
</a> -->
<?php 



	//WELCOME MESSAGE
	//=====================================================
	if($sopernal_settings['welcome-msg'] == true){ ?>
		<header>
			<div class="hidden welcome-b"><?php echo $sopernal_settings['welcome-msg-text']; ?></div>
		</header>
 		<div class="addbg"></div><?php
	};
	   //LOADING SCREEN
	//=====================================================?>
	<div id="loading" >
		<div class="inifiniteLoaderP animated">
    		<div class="loading"></div>
		</div>
		<div class="animated inifiniteLoaderBg loadbg"><div class="inifiniteLoaderP animated logo" style=" background:url( <?php echo $sopernal_settings['logo-img']['url']; ?>) no-repeat left bottom; "></div></div>
    </div>
<!-- BEGIN TOP BAR -->
    <div class="pre-header">
        <div class="container">
            <div class="row">
                <!-- BEGIN TOP BAR LEFT PART -->
                <div class="col-md-6 col-sm-6 additional-shop-info">
                    <ul class="list-unstyled list-inline">
                        <li><i class="fa fa-phone"></i><span>+1 456 6717</span></li>
                        <li><i class="fa fa-envelope-o"></i><span>info@keenthemes.com</span></li>
                    </ul>
                </div>
                <!-- END TOP BAR LEFT PART -->
                <!-- BEGIN TOP BAR MENU -->
                <div class="col-md-6 col-sm-6 additional-nav">
                    <ul class="list-unstyled list-inline pull-right">
                        <li><a href="page-login.html">Log In</a></li>
                        <li><a href="page-reg-page.html">Registration</a></li>
                    </ul>
                </div>
                <!-- END TOP BAR MENU -->
            </div>
        </div>        
    </div>
    <!-- END TOP BAR -->
    <!-- BEGIN HEADER -->
    <div class="header">
      <div class="container">
        <a class="site-logo" href="index.html"><img src="../../assets/frontend/layout/img/logos/logo-corp-red.png" alt="Metronic FrontEnd"></a>

        <a href="#" onclick="myFunction()" class="mobi-toggler">
        	<i class="fa fa-bars"></i>
        </a>
        

        <!-- BEGIN NAVIGATION -->
        <div class="header-navigation pull-right font-transform-inherit">
          	<ul>
	            <li class="dropdown">
	              <a class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="#">
	                Home 
	                
	              </a>
	                
	              <ul class="dropdown-menu">
	                <li class="active"><a href="index.html">Home Default</a></li>
	                <li><a href="index-header-fix.html">Home with Header Fixed</a></li>
	                <li><a href="index-without-topbar.html">Home without Top Bar</a></li>
	              </ul>
	            </li>
	           


	            <li class="dropdown">
	              <a class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="#">
	                Pages 
	                
	              </a>
	                
	              <ul class="dropdown-menu">
	                <li><a href="page-about.html">About Us</a></li>
	                <li><a href="page-services.html">Services</a></li>
	                <li><a href="page-prices.html">Prices</a></li>
	                <li><a href="page-faq.html">FAQ</a></li>
	                <li><a href="page-gallery.html">Gallery</a></li>
	                <li><a href="page-search-result.html">Search Result</a></li>
	                <li><a href="page-404.html">404</a></li>
	                <li><a href="page-500.html">500</a></li>
	                <li><a href="page-login.html">Login Page</a></li>
	                <li><a href="page-forgotton-password.html">Forget Password</a></li>
	                <li><a href="page-reg-page.html">Signup Page</a></li>
	                <li><a href="page-careers.html">Careers</a></li>
	                <li><a href="page-site-map.html">Site Map</a></li>
	                <li><a href="page-contacts.html">Contact</a></li>                
	              </ul>
	            </li>
	            <li class="dropdown">
	              <a class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="#">
	                Features 
	                
	              </a>
	                
	              <ul class="dropdown-menu">
	                <li><a href="feature-typography.html">Typography</a></li>
	                <li><a href="feature-buttons.html">Buttons</a></li>
	                <li><a href="feature-forms.html">Forms</a></li>
	                
	                <li class="dropdown-submenu">
	                  <a href="index.html">Multi level <i class="fa fa-angle-right"></i></a>
	                  <ul class="dropdown-menu" role="menu">
	                    <li><a href="index.html">Second Level Link</a></li>
	                    <li><a href="index.html">Second Level Link</a></li>
	                    <li class="dropdown-submenu">
	                      <a class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="#">
	                        Second Level Link 
	                        <i class="fa fa-angle-right"></i>
	                      </a>
	                      <ul class="dropdown-menu">
	                        <li><a href="index.html">Third Level Link</a></li>
	                        <li><a href="index.html">Third Level Link</a></li>
	                        <li><a href="index.html">Third Level Link</a></li>
	                      </ul>
	                    </li>
	                  </ul>
	                </li>
	              </ul>
	            </li>
	            <li class="dropdown">
	              <a class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="#">
	                Portfolio 
	                
	              </a>
	                
	              <ul class="dropdown-menu">
	                <li><a href="portfolio-4.html">Portfolio 4</a></li>
	                <li><a href="portfolio-3.html">Portfolio 3</a></li>
	                <li><a href="portfolio-2.html">Portfolio 2</a></li>
	                <li><a href="portfolio-item.html">Portfolio Item</a></li>
	              </ul>
	            </li>
	            <li class="dropdown">
	              <a class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="#">
	                Blog 
	                
	              </a>
	                
	              <ul class="dropdown-menu">
	                <li><a href="blog.html">Blog Page</a></li>
	                <li><a href="blog-item.html">Blog Item</a></li>
	              </ul>
	            </li>

	            <li class="dropdown dropdown-megamenu">
	              <a class="dropdown-toggle" data-toggle="dropdown" data-target="#" href="#">
	                Mega Menu
	                
	              </a>
	              <ul class="dropdown-menu">
	                <li>
	                  <div class="header-navigation-content">
	                    <div class="row">
	                      <div class="col-md-4 header-navigation-col">
	                        <h4>Footwear</h4>
	                        <ul>
	                          <li><a href="index.html">Astro Trainers</a></li>
	                          <li><a href="index.html">Basketball Shoes</a></li>
	                          <li><a href="index.html">Boots</a></li>
	                          <li><a href="index.html">Canvas Shoes</a></li>
	                          <li><a href="index.html">Football Boots</a></li>
	                          <li><a href="index.html">Golf Shoes</a></li>
	                          <li><a href="index.html">Hi Tops</a></li>
	                          <li><a href="index.html">Indoor Trainers</a></li>
	                        </ul>
	                      </div>
	                      <div class="col-md-4 header-navigation-col">
	                        <h4>Clothing</h4>
	                        <ul>
	                          <li><a href="index.html">Base Layer</a></li>
	                          <li><a href="index.html">Character</a></li>
	                          <li><a href="index.html">Chinos</a></li>
	                          <li><a href="index.html">Combats</a></li>
	                          <li><a href="index.html">Cricket Clothing</a></li>
	                          <li><a href="index.html">Fleeces</a></li>
	                          <li><a href="index.html">Gilets</a></li>
	                          <li><a href="index.html">Golf Tops</a></li>
	                        </ul>
	                      </div>
	                      <div class="col-md-4 header-navigation-col">
	                        <h4>Accessories</h4>
	                        <ul>
	                          <li><a href="index.html">Belts</a></li>
	                          <li><a href="index.html">Caps</a></li>
	                          <li><a href="index.html">Gloves</a></li>
	                        </ul>

	                        <h4>Clearance</h4>
	                        <ul>
	                          <li><a href="index.html">Jackets</a></li>
	                          <li><a href="index.html">Bottoms</a></li>
	                        </ul>
	                      </div>
	                    </div>
	                  </div>
	                </li>
	              </ul>
	            </li>   

	            <li><a href="shop-index.html" target="_blank">E-Commerce</a></li>
	            <li><a href="onepage-index.html" target="_blank">One Page</a></li>
	            <li><a href="http://keenthemes.com/preview/metronic/theme/templates/admin" target="_blank">Admin theme</a>
	            </li>

	            <!-- BEGIN TOP SEARCH -->
	            <li class="menu-search">
	              <span class="sep"></span>
	              <i class="fa fa-search search-btn"></i>
	              <div class="search-box">
	                <form action="#">
	                  <div class="input-group">
	                    <input type="text" placeholder="Search" class="form-control">
	                    <span class="input-group-btn">
	                      <button class="btn btn-primary" type="submit">Search</button>
	                    </span>
	                  </div>
	                </form>
	              </div> 
	            </li>
	            <!-- END TOP SEARCH -->
          	</ul>
        </div>
        <!-- END NAVIGATION -->
      </div>
    </div>
    <!-- Header END -->




	<!-- .sidebar -->

	<div class="main">
		<div class="container">
			

