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
    </div><?php
	
    //MENU BACKGROUND
	//=====================================================?>
    <div class="header-white <?php if($sopernal_settings['menu-position'] == "absolute"){ echo "addpositionab";}?> <?php if($sopernal_settings['site-width'] == "boxed"){ echo "boxedstyle";}?>"></div><?php
		
        //MENU BACKGROUND
		//=====================================================?>
    	<div class="<?php if($sopernal_settings['menu-width'] == "boxed"){ echo "ss-stand-alone";}else{ echo "ss-stand-full";}?>">
            <div class="ss-nav <?php if($sopernal_settings['menu-position'] == "absolute"){ echo "addpositionab";}?>">
                <div id="header-wrapper">
                    <a href="<?php echo get_site_url(); ?>" class="logohover">
                    	<div class="logo" style=" background:url( <?php echo $sopernal_settings['logo-img']['url']; ?>) no-repeat left bottom; ">
               			</div>
                	</a> 
                    <div id="mainmenu" class="wrapper ddsmoothmenu">
                        <div class="dl-menuwrapper">
                            <button id="openrespmenu"><?php echo $sopernal_settings['tr-resmenu-button'];?></button>
                        </div>
                        <?php 
						if($sopernal_settings['menu-search'] == false){
						wp_nav_menu( array( 'sort_column' => 'menu_order','container' => '', 'menu_class' => 'nav nospace', 'menu_id' => 'nav', 'theme_location' => 'primary-menu' ) );
						}else{
							wp_nav_menu( array( 'sort_column' => 'menu_order','container' => '', 'menu_class' => 'nav', 'menu_id' => 'nav', 'theme_location' => 'primary-menu' ) );
						}
						if($sopernal_settings['menu-search'] != false){ ?>
                            <div class="searchwarp">
                                <div class="searchmenu"> 
                                    <form method="get" id="searchform" action="<?php echo site_url() ?>/">
                                        <div>
                                            <input type="text" size="18" placeholder="<?php echo $sopernal_settings['tr-menu-search'];?>"  value="<?php echo esc_html($s, 1); ?>" name="s" id="navs" />
                                            <input type="submit" id="searchsubmit" value="Search" class="btn" />
                                        </div>
                                    </form>
                                </div>
                            </div><?php
						}?>
                    </div>
                </div>
            </div>
		</div><?php
		
        //RESPONSIVE MENU
		//=====================================================?>
        <div class="responsivemenuwarp">
			<div class="responsivemenubg"></div>
			<div class="responsivemenuheader"><?php 
				echo $sopernal_settings['tr-resmenu-title'];
				if($sopernal_settings['menu-search'] != false){ ?>
					<div class="searchwarp">
						<div class="searchmenu"> 
							<form method="get" id="searchform" action="<?php echo site_url(); ?>/">
								<div>
									<input type="text" size="18" placeholder="<?php echo $sopernal_settings['tr-menu-search'];?>"  value="<?php echo esc_html($s, 1); ?>" name="s" id="navs" />
									<input type="submit" id="searchsubmit" value="Search" class="btn" />
								</div>
							</form>
						</div>
					</div><?php
				}?>
			</div><?php
			wp_nav_menu( array( 'sort_column' => 'menu_order','container' => '', 'menu_class' => 'responsivemenu', 'menu_id' => 'other', 'theme_location' => 'primary-menu' ) );?>
        </div><?php
		
        //NONE SUPPORTED BROWSER
		//=====================================================?>
        <header>
        	<div class="support-note"><!-- let's check browser support with modernizr -->
					<!--span class="no-cssanimations">CSS animations are not supported in your browser</span-->
					<!--span class="no-csstransforms">CSS transforms are not supported in your browser</span-->
					<!--span class="no-csstransforms3d">CSS 3D transforms are not supported in your browser</span-->
					<!--span class="no-csstransitions">CSS transitions are not supported in your browser</span-->
                <span class="note-ie"><br>We are apologize for the inconvenience but you need to download <br> more modern browser in order to be able to browse our page<br />
              
                    <p class="support-note-ico ">
                        <a href="http://support.apple.com/kb/DL1531?viewlocale=en_US&amp;locale=en_US"><img src="<?php echo get_template_directory_uri(); ?>/images/support/safari.png" alt="Download Safari" width="50" height="50" /> <br>Download Safari
                        </a>
                        <a href="https://www.google.com/intl/en/chrome/browser/"><img src="<?php echo get_template_directory_uri(); ?>/images/support/chrome.png" alt="Download Chrome" width="50" height="50"  /> <br>Download Chrome
                        </a>
                        <a href="http://www.mozilla.org/en-US/firefox/new/"><img src="<?php echo get_template_directory_uri(); ?>/images/support/firefox.png" alt="Download Firefox" width="50" height="50"/> <br>Download Firefox
                        </a>
                        <a href="http://windows.microsoft.com/en-us/internet-explorer/ie-10-worldwide-languages"><img src="<?php echo get_template_directory_uri(); ?>/images/support/ie.png" alt="Download IE 10+" width="50" height="50"/> <br>Download IE 10+
                        </a>
                    </p>
                  </span>
            </div>
        </header>



	<!-- .sidebar -->

	<!-- <div class="main"> -->
		<div class="container">
			

