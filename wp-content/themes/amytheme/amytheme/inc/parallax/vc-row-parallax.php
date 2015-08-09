<?php
/*
Plugin Name: Parallax Backgrounds for VC
Description: Adds new options to Visual Composer rows to enable parallax scrolling to row background images.
Author: Benjamin Intal, Gambit
Version: 2.0
Author URI: http://gambit.ph
*/

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

defined( 'GAMBIT_VC_PARALLAX_BG' ) or define( 'GAMBIT_VC_PARALLAX_BG', 'gambit-vc-parallax-bg' );


if ( ! class_exists( 'GambitVCParallaxBackgrounds' ) ) {

	/**
	 * Parallax Background Class
	 *
	 * @since	1.0
	 */
	class GambitVCParallaxBackgrounds {


		/**
		 * Constructor, checks for Visual Composer and defines hooks
		 *
		 * @return	void
		 * @since	1.0
		 */
		function __construct() {
			// Check if Visual Composer is installed
			if ( ! defined( 'WPB_VC_VERSION' ) || ! function_exists( 'vc_add_param' ) ) {
				return;
			}

			add_action( 'wp_head', array( $this, 'initParallax' ) );
			add_action( 'plugins_loaded', array( $this, 'loadTextDomain' ) );
			add_action( 'init', array( $this, 'addParallaxParams' ), 100 );
			add_filter( 'gambit_add_parallax_div', array( __CLASS__, 'createParallaxDiv' ), 10, 3 );
            add_action( 'admin_head', array( $this, 'printAdminScripts' ) );
		}

        public function printAdminScripts() {
            echo "<script>
                jQuery(document).ready(function(\$) {
                \$('body').on('click', '[role=tab]', function() { \$('[name=gmbt_prlx_bg_type]').trigger('change') });
                });
                </script>";
        }


		/**
		 * Initializes the parallax background in the front end
		 *
		 * @return	void
		 * @since	1.0
		 */
		public function initParallax() {
			?>
			<script>
			jQuery(document).ready(function($) {
				"use strict";

				// Initialize Rowspans
				$('div.bg-parallax').each(function() {
					if ( typeof $(this).attr('data-row-span') == 'undefined' ) {
						return;
					}
					var rowSpan = parseInt( $(this).attr('data-row-span') );
					if ( isNaN( rowSpan ) ) {
						return;
					}
					if ( rowSpan == 0 ) {
						return;
					}

					var $nextRows = $(this).nextAll('.wpb_row');
					$nextRows.splice(0,1);
					$nextRows.splice(rowSpan);

					// Clear stylings for the next rows that our parallax will occupy
					$nextRows.each(function() {
						if ( $(this).prev().is('.bg-parallax') ) {
							$(this).prev().remove();
						}
						// we need to apply this class to make the row children visible
						$(this).addClass('bg-parallax-parent')
						// we need to clear the row background to make the parallax visible
						.css( {
							'backgroundImage': '',
							'backgroundColor': 'transparent'
						} );
					});
				})

				// Initialize parallax
				$('div.bg-parallax').each(function() {
					var $row = $(this).next();
					if ( $row.css( 'backgroundSize' ) == 'contain' ) {
						$row.css( 'backgroundSize', 'cover' );
					}
					$(this).css( {
						'backgroundImage': $row.css( 'backgroundImage' ),
						'backgroundRepeat': $row.css( 'backgroundRepeat' ),
						'backgroundSize': $row.css( 'backgroundSize' )
					} )
					.prependTo( $row.addClass('bg-parallax-parent') )
					.scrolly2().trigger('scroll');
					$row.css( {
						'backgroundImage': '',
						'backgroundRepeat': '',
						'backgroundSize': ''
					});

					if ( $(this).attr('data-direction') == 'up' || $(this).attr('data-direction') == 'down' ) {
						if($(window).width() > 810){
							if ( jQuery.browser.msie && jQuery.browser.version == '9.0' ) { 
							}else{
								$(this).css( 'backgroundAttachment', 'fixed' );
							}
						}else{
							$(this).css( 'backgroundAttachment', 'scroll' );
						}
							
					}
				});


				$(window).resize( function() {
					setTimeout( function() {
						var $ = jQuery;
					// Break container
					$('div.bg-parallax').each(function() {
						if ( typeof $(this).attr('data-break-parents') == 'undefined' ) {
							return;
						}
						var breakNum = parseInt( $(this).attr('data-break-parents') );
						if ( isNaN( breakNum ) ) {
							return;
						}

						var $immediateParent = $(this).parent();

						// Find the parent we're breaking away to
						var $parent = $immediateParent;
						for ( var i = 0; i < breakNum; i++ ) {
							if ( $parent.is('html') ) {
								break;
							}
							$parent = $parent.parent();
						}

						// Compute dimensions & location
						var parentWidth = $parent.width()
							+ parseInt( $parent.css('paddingLeft') )
							+ parseInt( $parent.css('paddingRight') );
						var left = - ( $immediateParent.offset().left - $parent.offset().left );
						if ( left > 0 ) {
							left = 0;
						}

						$(this).addClass('broke-out')
						.css({
							'width': parentWidth,
							'left': left
						})
						.parent().addClass('broke-out');
					});

					// span multiple rows
					$('div.bg-parallax').each(function() {
						if ( typeof $(this).attr('data-row-span') == 'undefined' ) {
							return;
						}
						var rowSpan = parseInt( $(this).attr('data-row-span') );
						if ( isNaN( rowSpan ) ) {
							return;
						}
						if ( rowSpan == 0 ) {
							return;
						}

                        var $current = $(this).parent('.wpb_row');

						var $nextRows = $(this).parent('.wpb_row').nextAll('.wpb_row');
						$nextRows.splice(rowSpan);

						// Clear stylings for the next rows that our parallax will occupy
						var heightToAdd = 0;
                        heightToAdd += parseInt($current.css('marginBottom'));
						$nextRows.each(function() {
							heightToAdd += $(this).height()
								+ parseInt($(this).css('paddingTop'))
								+ parseInt($(this).css('paddingBottom'))
								+ parseInt($(this).css('marginTop'));
						});
						$(this).css( 'height', 'calc(100% + ' + heightToAdd + 'px)' );
					});

					$(document).trigger('scroll');
					}, 1 );
				});

				// Perform parallax adjustment after a short delay to wait for themes to do their adjustments
				// setTimeout( function() {
					jQuery(window).trigger('resize');
				// }, 1 );
			});
			</script>
			<style>
			/* Parallax mandatory styles */
			.bg-parallax {
				width: 100%;
				height: 100%;
				position: absolute;
				display: block;
				top: 0;
				left: 0;
				z-index:-5;
				
				
				
				
			}
			.bg-parallax-parent {
				position: relative;
				overflow:hidden;
			}
			.lsparallax{
				
			}
			.bg-parallax-parent > *:not(.bg-parallax) {
				position: relative;
				z-index: 1;
			}
			.bg-parallax-parent.broke-out {
				overflow: visible;
				
			}
			.bg-parallax[data-mobile-enabled=""] {
			
				
			}
			@media (max-width: 980px) {
				/* Disable parallax for mobile devices */
				.bg-parallax[data-mobile-enabled=""] {
					background-position: 50% 50% !important;
					background-attachment: scroll!important;
					z-index:-1!important;
					
				}
			}
			</style>
			<?php
		}


		/**
		 * Loads the translations
		 *
		 * @return	void
		 * @since	1.0
		 */
		public function loadTextDomain() {
			load_plugin_textdomain( GAMBIT_VC_PARALLAX_BG, false, basename( dirname( __FILE__ ) ) . '/languages/' );
		}


		/**
		 * Creates the placeholder for the row with the parallax bg
		 *
		 * @param	string $output An empty string
		 * @param	array $atts The attributes of the vc_row shortcode
		 * @param	string $content The contents of vc_row
		 * @return	string The placeholder div
		 * @since	1.0
		 */
		public static function createParallaxDiv( $output, $atts, $content ) {
			extract( shortcode_atts( array(
				// Old parameter names, keep these for backward rendering compatibility
				'parallax'                => '',
				'layerslider_id'		  => '',
				'parallax_lvl'            => '',
				'speed'                   => '',
				'enable_prlxls'			  => '',
				'enable_mobile'           => '',
				'break_parents'           => '',
				'row_span'                => '',
                // BG type
                'gmbt_prlx_bg_type'       => '',
				// Our new parameter names
				'gmbt_prlx_parallax'      => '',
				'gmbt_prlx_layerslider'   => '',
				'gmbt_prlx_parallax_lvl'      => '',
				'gmbt_prlx_speed'         => '',
				'gmbt_prlx_layerslider_prlx' => '',
				'gmbt_prlx_enable_mobile' => '',
				'gmbt_prlx_break_parents' => '',
				'gmbt_prlx_row_span'      => '',
                // Video options
                'gmbt_prlx_video_youtube' => '',
                'gmbt_prlx_video_youtube_mute' => '',
                'gmbt_prlx_video_vimeo'   => '',

			), $atts ) );

			/*
			 * We're using new param names now, support the old ones
			 */

			if ( empty( $gmbt_prlx_parallax ) ) {
				$gmbt_prlx_parallax = $parallax;
			}
			
			if ( empty( $gmbt_prlx_layerslider ) ) {
				$gmbt_prlx_layerslider = $layerslider_id;
			}
			if ( empty( $gmbt_prlx_parallax_lvl ) ) {
				$gmbt_prlx_parallax_lvl = $parallax_lvl;
			}
			
			if ( empty( $gmbt_prlx_speed ) ) {
				$gmbt_prlx_speed = $speed;
			}
			if ( empty( $gmbt_prlx_layerslider_prlx ) ) {
				$gmbt_prlx_layerslider_prlx = $enable_prlxls;
			}
			if ( empty( $gmbt_prlx_enable_mobile ) ) {
				$gmbt_prlx_enable_mobile = $enable_mobile;
			}
			if ( empty( $gmbt_prlx_break_parents ) ) {
				$gmbt_prlx_break_parents = $break_parents;
			}
			if ( empty( $gmbt_prlx_row_span ) ) {
				$gmbt_prlx_row_span = $row_span;
			}

			/*
			 * Main parallax method
			 */

            $type = 'video';
            if ( empty( $gmbt_prlx_bg_type ) || $gmbt_prlx_bg_type == 'parallax' ) {
                $type = 'parallax';
            }

			if ( empty( $gmbt_prlx_parallax ) ) {
				return "";
			}

			//wp_enqueue_script( 'jquery-scrolly', plugins_url( 'jquery.scrolly.js', __FILE__ ), array( 'jquery' ), null, true );
		//		wp_register_script('ab_tf_jquery-scrolly', get_template_directory_uri().'/functions/parallax/jquery.scrolly.js', false,'1.0',true);
			//wp_enqueue_script('ab_tf_jquery-scrolly');
			
			
            if ( ! empty( $gmbt_prlx_video_youtube ) || ! empty( $gmbt_prlx_video_vimeo ) ) {
				
					wp_register_script('ab_tf_bgvideo', get_template_directory_uri().'/inc/parallax/bg-video.js', false,'1.0',true);
			wp_enqueue_script('ab_tf_bgvideo');
			
    			
            }

			$parallaxClass = ( $gmbt_prlx_parallax == "none" ) ? "" : "bg-parallax";
			$parallaxClass = in_array( $gmbt_prlx_parallax, array( "none", "up", "down", "left", "right", "bg-parallax" ) ) ? $parallaxClass : "";

            if ( $type == 'video' ) {
                $parallaxClass = "bg-parallax";
            }

            if ( ! $parallaxClass ) {
                return '';
            }

            $videoDiv = "";

            if ( ! empty( $gmbt_prlx_video_youtube ) ) {
                $videoDiv = "<div id='video-" . rand( 10000,99999 ) . "' data-youtube-video-id='" . $gmbt_prlx_video_youtube . "' data-mute='" . ( $gmbt_prlx_video_youtube_mute == 'mute' ? 'true' : 'false' ) . "'><div id='video-" . rand( 10000,99999 ) . "-inner'></div></div>";
				
            } else if ( ! empty( $gmbt_prlx_video_vimeo ) ) {
                $videoDiv = '<div id="video-' . rand( 10000,99999 ) . '" data-vimeo-video-id="' . $gmbt_prlx_video_vimeo . '"><iframe src="//player.vimeo.com/video/' . $gmbt_prlx_video_vimeo . '?html5=1&autopause=0&autoplay=1&badge=0&byline=0&loop=1&title=0" frameborder="0"></iframe></div>';
            }
			
			$fixforwebkit ='';
			$ls_id = ''; 
			global $ab_amy_settings;
			if ($gmbt_prlx_parallax_lvl == '2'){ 
				//$fixforwebkit =  "-webkit-backface-visibility: hidden!important;";
			}
			if (! empty($gmbt_prlx_layerslider)){ 
				
				$ls_id =  $gmbt_prlx_layerslider;
				$videoDiv = '<div class="lsparallax">'.do_shortcode('[layerslider id="'.$ls_id.'"]')."</div>";
			}
			//echo $ls_id;
			return   "<div style='pointer-events : none; ".$fixforwebkit." z-index:" . esc_attr($gmbt_prlx_parallax_lvl) . "' class='" . esc_attr( $parallaxClass ) . "' data-direction='" . esc_attr( $gmbt_prlx_parallax ) . "' data-velocity='" . esc_attr( (float)$gmbt_prlx_speed * -1 ) . "' data-mobile-enabled='" . esc_attr( $gmbt_prlx_enable_mobile ) . "' data-break-parents='" . esc_attr( $gmbt_prlx_break_parents ) . "' data-row-span='" . esc_attr( $gmbt_prlx_row_span ) . "' data-isboxed='" . $ab_amy_settings['site-width'] . "' data-layerslider='" . $ls_id. "' data-layersliderprlx='" .  esc_attr( $gmbt_prlx_layerslider_prlx ) . "'>" . $videoDiv . "</div>	";
		}
//echo do_shortcode('[layerslider id="'.$ab_tf_post_layerslider.'"]');

		/**
		 * Adds the parameter fields to the VC row
		 *
		 * @return	void
		 * @since	1.0
		 */
		public function addParallaxParams() {
			$setting = array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __( "Background Type", GAMBIT_VC_PARALLAX_BG ),
				"param_name" => "gmbt_prlx_bg_type",
				"value" => array(
					"Image Parallax" => "parallax",
					"Video" => "video",
					"LayerSlider" => "layerslider",
				),
				"description" => __( "", GAMBIT_VC_PARALLAX_BG ),
				"group" => __( "Image Parallax / Video", GAMBIT_VC_PARALLAX_BG ),
			);
			vc_add_param( 'vc_row', $setting );

			$setting = array(
				"type" => "textfield",
				"class" => "",
				"heading" => __( "YouTube Video ID", GAMBIT_VC_PARALLAX_BG ),
				"param_name" => "gmbt_prlx_video_youtube",
				"value" => "",
				"description" => __( "Enter the video ID of the YouTube video you want to use as your background. You can see the video ID from your video's URL: https://www.youtube.com/watch?v=XXXXXXXXX (The X's is the video ID). <em>Ads will show up in the video if it has them.</em> No video will be shown if left blank. <strong>Tip: newly uploaded videos may not display right away and might show an error message</strong>", GAMBIT_VC_PARALLAX_BG ),
                "dependency" => array(
                    "element" => "gmbt_prlx_bg_type",
                    "value" => array( "video" ),
                ),
				"group" => __( "Image Parallax / Video", GAMBIT_VC_PARALLAX_BG ),
			);
			vc_add_param( 'vc_row', $setting );

			$setting = array(
				"type" => "checkbox",
				"class" => "",
				"heading" => __( "Mute YouTube Video", GAMBIT_VC_PARALLAX_BG ),
				"param_name" => "gmbt_prlx_video_youtube_mute",
				"value" => array( __( "Check this to mute your video", GAMBIT_VC_PARALLAX_BG ) => "mute" ),
				"description" => __( "", GAMBIT_VC_PARALLAX_BG ),
                "dependency" => array(
                    "element" => "gmbt_prlx_video_youtube",
                    "not_empty" => true,
                ),
				"group" => __( "Image Parallax / Video", GAMBIT_VC_PARALLAX_BG ),
			);
			vc_add_param( 'vc_row', $setting );

			$setting = array(
				"type" => "textfield",
				"class" => "",
				"heading" => __( "Vimeo Video ID", GAMBIT_VC_PARALLAX_BG ),
				"param_name" => "gmbt_prlx_video_vimeo",
				"value" => "",
				"description" => __( "Enter the video ID of the Vimeo video you want to use as your background. You can see the video ID from your video's URL: https://vimeo.com/XXXXXXX (The X's is the video ID). No video will be shown if left blank. <strong>Tip: Vimeo sometimes has problems with Firefox</strong>", GAMBIT_VC_PARALLAX_BG ),
                "dependency" => array(
                    "element" => "gmbt_prlx_bg_type",
                    "value" => array( "video" ),
                ),
				"group" => __( "Image Parallax / Video", GAMBIT_VC_PARALLAX_BG ),
			);
			vc_add_param( 'vc_row', $setting );
			
			$setting = array(
				"type" => "textfield",
				"class" => "",
				"heading" => __( "LayerSliedr ID", GAMBIT_VC_PARALLAX_BG ),
				"param_name" => "gmbt_prlx_layerslider",
				"value" => "",
				"description" => __( "Enter LayerSlider ID ", GAMBIT_VC_PARALLAX_BG ),
                "dependency" => array(
                    "element" => "gmbt_prlx_bg_type",
                    "value" => array( "layerslider" ),
                ),
				"group" => __( "Image Parallax / Video", GAMBIT_VC_PARALLAX_BG ),
			);
			vc_add_param( 'vc_row', $setting );
			
			$setting = array(
				"type" => "checkbox",
				"class" => "",
				"param_name" => "gmbt_prlx_layerslider_prlx",
				"value" => array( __( "Check this to enable parallax effect", GAMBIT_VC_PARALLAX_BG ) => "parallax-enable-layerslider" ),
				
                "dependency" => array(
                    "element" => "gmbt_prlx_bg_type",
                    "value" => array( "layerslider" ),
                ),
				"group" => __( "Image Parallax / Video", GAMBIT_VC_PARALLAX_BG ),
			);
			vc_add_param( 'vc_row', $setting );

			$setting = array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __( "Background Image Parallax", GAMBIT_VC_PARALLAX_BG ),
				"param_name" => "gmbt_prlx_parallax",
				"value" => array(
					"No Parallax" => "none",
					"Up" => "up",
					"Down" => "down",
					"Left" => "left",
					"Right" => "right",
				),
				"description" => __( "Select the parallax effect for your background image. You must have a background image to use this. Be mindful of the <strong>background size</strong> and the <strong>dimensions</strong> of your background image when setting this value. For example, if you're performing a vertical parallax (up or down), make sure that your background image has a large height that can provide sufficient scrolling space for the parallax effect.", GAMBIT_VC_PARALLAX_BG ),
                "dependency" => array(
                    "element" => "gmbt_prlx_bg_type",
                    "value" => array( "parallax" ),
                ),
				"group" => __( "Image Parallax / Video", GAMBIT_VC_PARALLAX_BG ),
			);
			vc_add_param( 'vc_row', $setting );
			
			$setting = array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __( "Z-index level", GAMBIT_VC_PARALLAX_BG ),
				"param_name" => "gmbt_prlx_parallax_lvl",
				"value" => array(
					"Under BG color" => "0",
					"Over BG color" => "1",
					"Over content" => "2",				),
				"description" => __( "Select the parallax effect for your background image. You must have a background image to use this. Be mindful of the <strong>background size</strong> and the <strong>dimensions</strong> of your background image when setting this value. For example, if you're performing a vertical parallax (up or down), make sure that your background image has a large height that can provide sufficient scrolling space for the parallax effect.", GAMBIT_VC_PARALLAX_BG ),
                "dependency" => array(
                    "element" => "gmbt_prlx_bg_type",
                    "value" => array( "parallax" ),
                ),
				"group" => __( "Image Parallax / Video", GAMBIT_VC_PARALLAX_BG ),
			);
			vc_add_param( 'vc_row', $setting );

			$setting = array(
				"type" => "textfield",
				"class" => "",
				"heading" => __( "Parallax Speed", GAMBIT_VC_PARALLAX_BG ),
				"param_name" => "gmbt_prlx_speed",
				"value" => "0.3",
				"description" => __( "The movement speed, value should be between 0.1 and 1.0. A lower number means slower scrolling speed. Be mindful of the <strong>background size</strong> and the <strong>dimensions</strong> of your background image when setting this value. Faster scrolling means that the image will move faster, make sure that your background image has enough width or height for the offset.", GAMBIT_VC_PARALLAX_BG ),
                "dependency" => array(
                    "element" => "gmbt_prlx_bg_type",
                    "value" => array( "parallax" ),
                ),
				"group" => __( "Image Parallax / Video", GAMBIT_VC_PARALLAX_BG ),
			);
			vc_add_param( 'vc_row', $setting );

			$setting = array(
				"type" => "checkbox",
				"class" => "",
				"param_name" => "gmbt_prlx_enable_mobile",
				"value" => array( __( "Check this to enable the parallax effect in mobile devices", GAMBIT_VC_PARALLAX_BG ) => "parallax-enable-mobile" ),
				"description" => __( "Parallax effects would most probably cause slowdowns when your site is viewed in mobile devices. If the device width is less than 980 pixels, then it is assumed that the site is being viewed in a mobile device.", GAMBIT_VC_PARALLAX_BG ),
                "dependency" => array(
                    "element" => "gmbt_prlx_bg_type",
                    "value" => array( "parallax" ),
                ),
				"group" => __( "Image Parallax / Video", GAMBIT_VC_PARALLAX_BG ),
			);
			vc_add_param( 'vc_row', $setting );

			$setting = array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __( "Breakout Parallax & Video Background", GAMBIT_VC_PARALLAX_BG ),
				"param_name" => "gmbt_prlx_break_parents",
				"value" => array(
					"Don't break out the row container" => "0",
					sprintf( _n( "Break out of 1 container", "Break out of %d containers", 1, GAMBIT_VC_PARALLAX_BG ), 1 ) => "1",
					sprintf( _n( "Break out of 1 container", "Break out of %d containers", 2, GAMBIT_VC_PARALLAX_BG ), 2 ) => "2",
					sprintf( _n( "Break out of 1 container", "Break out of %d containers", 3, GAMBIT_VC_PARALLAX_BG ), 3 ) => "3",
					sprintf( _n( "Break out of 1 container", "Break out of %d containers", 4, GAMBIT_VC_PARALLAX_BG ), 4 ) => "4",
					sprintf( _n( "Break out of 1 container", "Break out of %d containers", 5, GAMBIT_VC_PARALLAX_BG ), 5 ) => "5",
					sprintf( _n( "Break out of 1 container", "Break out of %d containers", 6, GAMBIT_VC_PARALLAX_BG ), 6 ) => "6",
					sprintf( _n( "Break out of 1 container", "Break out of %d containers", 7, GAMBIT_VC_PARALLAX_BG ), 7 ) => "7",
					sprintf( _n( "Break out of 1 container", "Break out of %d containers", 8, GAMBIT_VC_PARALLAX_BG ), 8 ) => "8",
					sprintf( _n( "Break out of 1 container", "Break out of %d containers", 9, GAMBIT_VC_PARALLAX_BG ), 9 ) => "9",
					sprintf( _n( "Break out of 1 container", "Break out of %d containers", 10, GAMBIT_VC_PARALLAX_BG ), 10 ) => "10",
					__( "Break out of all containers (full page width)", GAMBIT_VC_PARALLAX_BG ) => "99",
				),
				"description" => __( "The parallax or video effect is contained inside a Visual Composer row, depending on your theme, this container may be too small for your parallax effect. Adjust this option to let the parallax effect stretch outside it's current container and occupy the parent container's width.", GAMBIT_VC_PARALLAX_BG ),
				"group" => __( "Image Parallax / Video", GAMBIT_VC_PARALLAX_BG ),
			);
			vc_add_param( 'vc_row', $setting );

			$setting = array(
				"type" => "dropdown",
				"class" => "",
				"heading" => __( "Breakout Parallax & Video Row Span", GAMBIT_VC_PARALLAX_BG ),
				"param_name" => "gmbt_prlx_row_span",
				"value" => array(
					"Occupy this row only" => "0",
					sprintf( _n( "Occupy also the next row", "Occupy also the next %d rows", 1, GAMBIT_VC_PARALLAX_BG ), 1 ) => "1",
					sprintf( _n( "Occupy also the next row", "Occupy also the next %d rows", 2, GAMBIT_VC_PARALLAX_BG ), 2 ) => "2",
					sprintf( _n( "Occupy also the next row", "Occupy also the next %d rows", 3, GAMBIT_VC_PARALLAX_BG ), 3 ) => "3",
					sprintf( _n( "Occupy also the next row", "Occupy also the next %d rows", 4, GAMBIT_VC_PARALLAX_BG ), 4 ) => "4",
					sprintf( _n( "Occupy also the next row", "Occupy also the next %d rows", 5, GAMBIT_VC_PARALLAX_BG ), 5 ) => "5",
					sprintf( _n( "Occupy also the next row", "Occupy also the next %d rows", 6, GAMBIT_VC_PARALLAX_BG ), 6 ) => "6",
					sprintf( _n( "Occupy also the next row", "Occupy also the next %d rows", 7, GAMBIT_VC_PARALLAX_BG ), 7 ) => "7",
					sprintf( _n( "Occupy also the next row", "Occupy also the next %d rows", 8, GAMBIT_VC_PARALLAX_BG ), 8 ) => "8",
					sprintf( _n( "Occupy also the next row", "Occupy also the next %d rows", 9, GAMBIT_VC_PARALLAX_BG ), 9 ) => "9",
					sprintf( _n( "Occupy also the next row", "Occupy also the next %d rows", 10, GAMBIT_VC_PARALLAX_BG ), 10 ) => "10",
				),
				"description" => __( "The parallax or video effect is normall only applied for this Visual Composer row. You can choose here if you want this parallax background to also span across the next Visual Composer row. Remember to clear the background of the next row so as not to cover up the parallax.", GAMBIT_VC_PARALLAX_BG ),
				"group" => __( "Image Parallax / Video", GAMBIT_VC_PARALLAX_BG ),
			);
			vc_add_param( 'vc_row', $setting );
		}
	}


	new GambitVCParallaxBackgrounds();
}



if ( ! function_exists( 'vc_theme_before_vc_row' ) ) {


	/**
	 * Adds the placeholder div right before the vc_row is printed
	 *
	 * @param	array $atts The attributes of the vc_row shortcode
	 * @param	string $content The contents of vc_row
	 * @return	string The placeholder div
	 * @since	1.0
	 */
	function vc_theme_before_vc_row($atts, $content = null) {
		return apply_filters( 'gambit_add_parallax_div', '', $atts, $content );
	}
}
