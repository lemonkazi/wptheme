<?php

    /*
    *
    *	Swift Page Builder - Row Shortcode
    *	------------------------------------------------
    *	Swift Framework
    * 	Copyright Swift Ideas 2014 - http://www.swiftideas.net
    *
    */

    class SwiftPageBuilderShortcode_spb_row extends SwiftPageBuilderShortcode {

        public function content( $atts, $content = null ) {

            $row_el_class = $width = $row_bg_color = $row_padding_vertical = $row_margin_vertical = $remove_element_spacing = $el_position = $animation_output = '';

            extract( shortcode_atts( array(
                'wrap_type'               => '',
                'row_bg_color'            => '',
                'row_id'                  => '',
                'row_name'                => '',
                'row_padding_vertical'    => '',
                'row_margin_vertical'     => '30',
                'row_overlay_opacity'     => '0',
                'remove_element_spacing'  => '',
                'vertical_center'         => 'true',
                'row_bg_type'             => '',
                'bg_image'                => '',
                'bg_video_mp4'            => '',
                'bg_video_webm'           => '',
                'bg_video_ogg'            => '',
                'parallax_video_height'   => 'window-height',
                'parallax_image_height'   => 'content-height',
                'parallax_video_overlay'  => 'none',
                'parallax_image_movement' => 'fixed',
                'parallax_image_speed'    => '0.5',
                'bg_type'                 => '',
                'row_expanding'			  => '',
                'row_expading_text_closed' => '',
                'row_expading_text_open'  => '',
                'row_animation'        	  => '',
                'row_animation_delay'  	  => '',
                'responsive_vis'          => '',
                'row_el_class'            => '',
                'el_position'             => '',
                'width'                   => '1/1'
            ), $atts ) );

            $output = $inline_style = $rowId = '';

            if ( $row_id != "" ) {
                $rowId = 'id="' . $row_id . '" data-rowname="' . $row_name . '"';
            }

            $responsive_vis = str_replace( "_", " ", $responsive_vis );
            $row_el_class   = $this->getExtraClass( $row_el_class ) . ' ' . $responsive_vis;
            $orig_width     = $width;
            $width          = spb_translateColumnWidthToSpan( $width );
            $img_url        = wp_get_attachment_image_src( $bg_image, 'full' );

            if ( $remove_element_spacing == "yes" ) {
                $row_el_class .= ' remove-element-spacing';
            }

            if ( $row_bg_color != "" ) {
                $inline_style .= 'background-color:' . $row_bg_color . ';';
            }
            if ( $row_padding_vertical != "" ) {
                $inline_style .= 'padding-top:' . $row_padding_vertical . 'px;padding-bottom:' . $row_padding_vertical . 'px;';
            }
            if ( $row_margin_vertical != "" ) {
                $inline_style .= 'margin-top:' . $row_margin_vertical . 'px;margin-bottom:' . $row_margin_vertical . 'px;';
            }

            if ( $row_bg_type != "color" && isset( $img_url ) && $img_url[0] != "" ) {
                $inline_style .= 'background-image: url(' . $img_url[0] . ');';
            }
            
            if ( $row_animation != "" && $row_animation != "none" ) {
            	$row_el_class .= ' sf-animation';
                $animation_output = 'data-animation="' . $row_animation . '" data-delay="' . $row_animation_delay . '"';
            }
            
            if ( $row_expanding == "yes" ) {
            	$row_el_class .= ' spb-row-expanding';
            	$output .= "\n\t\t" . '<a href="#" class="spb-row-expand-text" data-closed-text="'.$row_expading_text_closed.'" data-open-text="'.$row_expading_text_open.'"><span>'.$row_expading_text_closed.'</span></a>';
            }


            if ( $row_bg_type == "video" ) {
                if ( $img_url[0] != "" ) {
                    $output .= "\n\t" . '<div class="spb-row-container spb-row-' . $wrap_type . ' spb_parallax_asset sf-parallax sf-parallax-video parallax-' . $parallax_video_height . ' spb_content_element bg-type-' . $bg_type . ' ' . $width . $row_el_class . '" data-v-center="' . $vertical_center . '" '.$animation_output.' style="' . $inline_style . '">';
                } else {
                    $output .= "\n\t" . '<div class="spb-row-container spb-row-' . $wrap_type . ' spb_parallax_asset sf-parallax sf-parallax-video parallax-' . $parallax_video_height . ' spb_content_element bg-type-' . $bg_type . ' ' . $width . $row_el_class . '" data-v-center="' . $vertical_center . '" '.$animation_output.' style="' . $inline_style . '">';
                }
            } else if ( $row_bg_type == "image" ) {
                if ( $img_url[0] != "" ) {
                    if ( $parallax_image_movement == "stellar" ) {
                        $output .= "\n\t" . '<div class="spb-row-container spb-row-' . $wrap_type . ' spb_parallax_asset sf-parallax parallax-' . $parallax_image_height . ' parallax-' . $parallax_image_movement . ' spb_content_element bg-type-' . $bg_type . ' ' . $width . $row_el_class . '" data-v-center="' . $vertical_center . '" data-stellar-background-ratio="' . $parallax_image_speed . '" '.$animation_output.' style="' . $inline_style . '">';
                    } else {
                        $output .= "\n\t" . '<div class="spb-row-container spb-row-' . $wrap_type . ' spb_parallax_asset sf-parallax parallax-' . $parallax_image_height . ' parallax-' . $parallax_image_movement . ' spb_content_element bg-type-' . $bg_type . ' ' . $width . $row_el_class . '" data-v-center="' . $vertical_center . '" '.$animation_output.' style="' . $inline_style . '">';
                    }
                } else {
                    $output .= "\n\t" . '<div class="spb-row-container spb-row-' . $wrap_type . ' spb_parallax_asset sf-parallax parallax-' . $parallax_image_height . ' spb_content_element bg-type-' . $bg_type . ' ' . $width . $row_el_class . '" data-v-center="' . $vertical_center . '" '.$animation_output.' style="' . $inline_style . '">';
                }
            } else {
                $output .= "\n\t" . '<div class="spb-row-container spb-row-' . $wrap_type . ' ' . $width . $row_el_class . '" data-v-center="' . $vertical_center . '" '.$animation_output.' style="' . $inline_style . '">';
            }
			
            $output .= "\n\t\t" . '<div class="spb_content_element">';
            $output .= "\n\t\t\t" . spb_format_content( $content );
            $output .= "\n\t\t" . '</div> ' . $this->endBlockComment( $width );

            if ( $row_bg_type == "video" ) {
                if ( $img_url ) {
                    $output .= '<video class="parallax-video" poster="' . $img_url[0] . '" preload="auto" autoplay loop="loop" muted="muted">';
                } else {
                    $output .= '<video class="parallax-video" preload="auto" autoplay loop="loop" muted="muted">';
                }
                if ( $bg_video_mp4 != "" ) {
                    $output .= '<source src="' . $bg_video_mp4 . '" type="video/mp4">';
                }
                if ( $bg_video_webm != "" ) {
                    $output .= '<source src="' . $bg_video_webm . '" type="video/webm">';
                }
                if ( $bg_video_ogg != "" ) {
                    $output .= '<source src="' . $bg_video_ogg . '" type="video/ogg">';
                }
                $output .= '</video>';
                if ( $parallax_video_overlay != "color" ) {
                    $output .= '<div class="video-overlay overlay-' . $parallax_video_overlay . '"></div>';
                }
            }

            if ( $row_overlay_opacity != "0" && $parallax_video_overlay == "color" ) {
                $opacity = intval( $row_overlay_opacity, 10 ) / 100;
                $output .= '<div class="row-overlay" style="background-color:' . $row_bg_color . ';opacity:' . $opacity . ';"></div>';
            }

            $output .= "\n\t" . '</div>';

            $output = $this->startRow( $el_position, '', true, $rowId ) . $output . $this->endRow( $el_position, '', true );

            if ( $row_bg_type == "image" || $row_bg_type == "video" ) {
                global $sf_include_parallax;
                $sf_include_parallax = true;
            }

            return $output;
        }

        public function contentAdmin( $atts, $content = null ) {
            $width = $row_el_class = $bg_color = $padding_vertical = '';
            extract( shortcode_atts( array(
                'wrap_type'               => '',
                'row_el_class'            => '',
                'row_bg_color'            => '',
                'row_padding_vertical'    => '',
                'row_margin_vertical'     => '',
                'row_overlay_opacity'     => '0',
                'remove_element_spacing'  => '',
                'vertical_center'         => 'true',
                'row_id'                  => '',
                'row_name'                => '',
                'row_bg_type'             => '',
                'bg_image'                => '',
                'bg_video_mp4'            => '',
                'bg_video_webm'           => '',
                'bg_video_ogg'            => '',
                'parallax_video_height'   => 'window-height',
                'parallax_image_height'   => 'content-height',
                'parallax_video_overlay'  => 'none',
                'parallax_image_movement' => 'fixed',
                'parallax_image_speed'    => '0.5',
                'bg_type'                 => '',
                'row_expanding'			  => '',
                'row_expading_text_closed' => '',
                'row_expading_text_open'  => '',
                'row_animation'        	  => '',
                'row_animation_delay'  	  => '',
                'responsive_vis'          => '',
                'el_position'             => '',
                'width'                   => 'span12'
            ), $atts ) );

            $output = '';

            $output .= '<div data-element_type="spb_row" class="spb_row spb_sortable span12 spb_droppable not-column-inherit">';
            $output .= '<input type="hidden" class="spb_sc_base" name="element_name-spb_row" value="spb_row">';
            $output .= '<div class="controls sidebar-name"><span class="asset-name">' . __( "Row", "swift-framework-admin" ) . '</span><div class="controls_right"><a class="element-save" href="#" title="Save"></a><a class="column_edit" href="#" title="Edit"></a> <a class="column_clone" href="#" title="Clone"></a> <a class="column_delete" href="#" title="Delete"></a></div></div>';
            $output .= '<div class="spb_element_wrapper">';
            $output .= '<div class="row-fluid spb_column_container spb_sortable_container not-column-inherit">';
            $output .= do_shortcode( shortcode_unautop( $content ) );
            $output .= SwiftPageBuilder::getInstance()->getLayout()->getContainerHelper();
            $output .= '</div>';
            if ( isset( $this->settings['params'] ) ) {
                $inner = '';
                foreach ( $this->settings['params'] as $param ) {
                    $param_value = isset( $$param['param_name'] ) ? $$param['param_name'] : '';
                    //var_dump($param_value);
                    if ( is_array( $param_value ) ) {
                        // Get first element from the array
                        reset( $param_value );
                        $first_key   = key( $param_value );
                        $param_value = $param_value[ $first_key ];
                    }
                    $inner .= $this->singleParamHtmlHolder( $param, $param_value );
                }
                $output .= $inner;
            }
            $output .= '</div>';
            $output .= '</div>';

            return $output;
        }
    }

    SPBMap::map( 'spb_row', array(
        "name"            => __( "Row", "swift-framework-admin" ),
        "base"            => "spb_row",
        "controls"        => "edit_delete",
        "content_element" => false,
        "params"          => array(
            array(
                "type"       => "section",
                "param_name" => "section_row_options",
                "heading"    => __( "Row Type Options", "swift-framework-admin" ),
            ),
            array(
                "type"        => "dropdown",
                "heading"     => __( "Wrap type", "swift-framework-admin" ),
                "param_name"  => "wrap_type",
                "value"       => array(
                    __( 'Standard Width Content', "swift-framework-admin" ) => "content-width",
                    __( 'Full Width Content', "swift-framework-admin" )     => "full-width"
                ),
                "description" => __( "Select if you want to row to wrap the content to the grid, or if you want the content to be edge to edge.", "swift-framework-admin" )
            ),
            array(
                "type"        => "dropdown",
                "heading"     => __( "Row Background Type", "swift-framework-admin" ),
                "param_name"  => "row_bg_type",
                "value"       => array(
                    __( "Color", "swift-framework-admin" ) => "color",
                    __( "Image", "swift-framework-admin" ) => "image",
                    __( "Video", "swift-framework-admin" ) => "video"
                ),
                "description" => __( "Choose whether you want to use an image or video for the background of the parallax. This will decide what is used from the options below.", "swift-framework-admin" )
            ),
            array(
                "type"        => "colorpicker",
                "heading"     => __( "Background color", "swift-framework-admin" ),
                "param_name"  => "row_bg_color",
                "value"       => "",
                "description" => __( "Select a background colour for the row here.", "swift-framework-admin" )
            ),
            array(
                "type"       => "section",
                "param_name" => "section_bg_image_options",
                "heading"    => __( "Row Background Image Options", "swift-framework-admin" ),
            ),
            array(
                "type"        => "attach_image",
                "heading"     => __( "Background Image", "swift-framework-admin" ),
                "param_name"  => "bg_image",
                "value"       => "",
                "description" => "Choose an image to use as the background for the parallax area."
            ),
            array(
                "type"        => "dropdown",
                "heading"     => __( "Background Image Type", "swift-framework-admin" ),
                "param_name"  => "bg_type",
                "value"       => array(
                    __( "Cover", "swift-framework-admin" )   => "cover",
                    __( "Pattern", "swift-framework-admin" ) => "pattern"
                ),
                "description" => __( "If you're uploading an image that you want to spread across the whole asset, then choose cover. Else choose pattern for an image you want to repeat.", "swift-framework-admin" )
            ),
            array(
                "type"        => "dropdown",
                "heading"     => __( "Parallax Image Height", "swift-framework-admin" ),
                "param_name"  => "parallax_image_height",
                "value"       => array(
                    __( "Content Height", "swift-framework-admin" ) => "content-height",
                    __( "Window Height", "swift-framework-admin" )  => "window-height"
                ),
                "description" => __( "If you are using this as an image parallax asset, then please choose whether you'd like asset to sized based on the content height or the height of the viewport window.", "swift-framework-admin" )
            ),
            array(
                "type"        => "dropdown",
                "heading"     => __( "Background Image Movement", "swift-framework-admin" ),
                "param_name"  => "parallax_image_movement",
                "value"       => array(
                    __( "Fixed", "swift-framework-admin" )             => "fixed",
                    __( "Scroll", "swift-framework-admin" )            => "scroll",
                    __( "Stellar (dynamic)", "swift-framework-admin" ) => "stellar",
                ),
                "description" => __( "Choose the type of movement you would like the parallax image to have. Fixed means the background image is fixed on the page, Scroll means the image will scroll will the page, and stellar makes the image move at a seperate speed to the page, providing a layered effect.", "swift-framework-admin" )
            ),
            array(
                "type"        => "textfield",
                "heading"     => __( "Parallax Image Speed (Stellar Only)", "swift-framework-admin" ),
                "param_name"  => "parallax_image_speed",
                "value"       => "0.5",
                "description" => "The speed at which the parallax image moves in relation to the page scrolling. For example, 0.5 would mean the image scrolls at half the speed of the standard page scroll. (Default 0.5)."
            ),
            array(
                "type"       => "section",
                "param_name" => "section_bg_video_options",
                "heading"    => __( "Row Background Video Options", "swift-framework-admin" ),
            ),
            array(
                "type"        => "textfield",
                "heading"     => __( "Background Video (MP4)", "swift-framework-admin" ),
                "param_name"  => "bg_video_mp4",
                "value"       => "",
                "description" => "Provide a video URL in MP4 format to use as the background for the parallax area. You can upload these videos through the WordPress media manager."
            ),
            array(
                "type"        => "textfield",
                "heading"     => __( "Background Video (WebM)", "swift-framework-admin" ),
                "param_name"  => "bg_video_webm",
                "value"       => "",
                "description" => "Provide a video URL in WebM format to use as the background for the parallax area. You can upload these videos through the WordPress media manager."
            ),
            array(
                "type"        => "textfield",
                "heading"     => __( "Background Video (Ogg)", "swift-framework-admin" ),
                "param_name"  => "bg_video_ogg",
                "value"       => "",
                "description" => "Provide a video URL in OGG format to use as the background for the parallax area. You can upload these videos through the WordPress media manager."
            ),
            array(
                "type"        => "dropdown",
                "heading"     => __( "Parallax Video Height", "swift-framework-admin" ),
                "param_name"  => "parallax_video_height",
                "value"       => array(
                    __( "Window Height", "swift-framework-admin" )  => "window-height",
                    __( "Content Height", "swift-framework-admin" ) => "content-height"
                ),
                "description" => __( "If you are using this as a video parallax asset, then please choose whether you'd like asset to sized based on the content height or the video height.", "swift-framework-admin" )
            ),
            array(
                "type"       => "section",
                "param_name" => "section_display_options",
                "heading"    => __( "Row Display Options", "swift-framework-admin" ),
            ),
            array(
                "type"        => "dropdown",
                "heading"     => __( "Overlay style", "swift-framework-admin" ),
                "param_name"  => "parallax_video_overlay",
                "value"       => array(
                    __( "None", "swift-framework-admin" )             => "none",
                    __( "Color", "swift-framework-admin" )            => "color",
                    __( "Light Grid", "swift-framework-admin" )       => "lightgrid",
                    __( "Dark Grid", "swift-framework-admin" )        => "darkgrid",
                    __( "Light Grid (Fat)", "swift-framework-admin" ) => "lightgridfat",
                    __( "Dark Grid (Fat)", "swift-framework-admin" )  => "darkgridfat",
                    __( "Light Diagonal", "swift-framework-admin" )   => "diaglight",
                    __( "Dark Diagonal", "swift-framework-admin" )    => "diagdark",
                    __( "Light Vertical", "swift-framework-admin" )   => "vertlight",
                    __( "Dark Vertical", "swift-framework-admin" )    => "vertdark",
                    __( "Light Horizontal", "swift-framework-admin" ) => "horizlight",
                    __( "Dark Horizontal", "swift-framework-admin" )  => "horizdark",
                ),
                "description" => __( "If you would like an overlay to appear on top of the video, then you can select it here.", "swift-framework-admin" )
            ),
            array(
                "type"        => "uislider",
                "heading"     => __( "Overlay Opacity", "swift-framework-admin" ),
                "param_name"  => "row_overlay_opacity",
                "value"       => "0",
                "step"        => "5",
                "min"         => "0",
                "max"         => "100",
                "description" => __( "Adjust the overlay capacity if using an image or video option. This only has effect for the color overlay style option, and shows an overlay over the image/video at the desired opacity. Percentage.", "swift-framework-admin" )
            ),
            array(
                "type"        => "uislider",
                "heading"     => __( "Padding - Vertical", "swift-framework-admin" ),
                "param_name"  => "row_padding_vertical",
                "value"       => "0",
                "description" => __( "Adjust the vertical padding for the row. (px)", "swift-framework-admin" )
            ),
            array(
                "type"        => "uislider",
                "heading"     => __( "Margin - Vertical", "swift-framework-admin" ),
                "param_name"  => "row_margin_vertical",
                "value"       => "0",
                "description" => __( "Adjust the margin above and below the row. (px)", "swift-framework-admin" )
            ),
            array(
                "type"        => "dropdown",
                "heading"     => __( "Remove Element Spacing", "swift-framework-admin" ),
                "param_name"  => "remove_element_spacing",
                "value"       => array(
                    __( 'No', "swift-framework-admin" )  => "no",
                    __( 'Yes', "swift-framework-admin" ) => "yes"
                ),
                "description" => __( "Enable this option if you wish to remove all spacing from the elements within the row.", "swift-framework-admin" )
            ),
            array(
                "type"        => "dropdown",
                "heading"     => __( "Vertically Center Elements Within", "swift-framework-admin" ),
                "param_name"  => "vertical_center",
                "value"       => array(
                    __( 'No', "swift-framework-admin" )  => "false",
                    __( 'Yes', "swift-framework-admin" ) => "true"
                ),
                "description" => __( "Enable this option if you wish to center the elements within the row.", "swift-framework-admin" )
            ),
            array(
                "type"       => "section",
                "param_name" => "section_reveal_options",
                "heading"    => __( "Row Content Expand Options", "swift-framework-admin" ),
            ),
            array(
                "type"        => "dropdown",
                "heading"     => __( "Expanding Row", "swift-framework-admin" ),
                "param_name"  => "row_expanding",
                "value"       => array(
                    __( "No", "swift-framework-admin" )             => "no",
                    __( "Yes", "swift-framework-admin" )             => "yes",
                ),
                "description" => __( "If you would like the content to be hidden on load, and have a text link to expand the content, then select Yes.", "swift-framework-admin" )
            ),
            array(
                "type"        => "textfield",
                "heading"     => __( "Expanding Link Text (Content Closed)", "swift-framework-admin" ),
                "param_name"  => "row_expading_text_closed",
                "value"       => "",
                "description" => __( "This is the text that is shown when the expanding row is closed.", "swift-framework-admin" )
            ),
            array(
                "type"        => "textfield",
                "heading"     => __( "Expanding Link Text (Content Open)", "swift-framework-admin" ),
                "param_name"  => "row_expading_text_open",
                "value"       => "",
                "description" => __( "This is the text that is shown when the expanding row is open.", "swift-framework-admin" )
            ),
            array(
                "type"       => "section",
                "param_name" => "row_animation_options",
                "heading"    => __( "Animation Options", "swift-framework-admin" ),
            ),
            array(
                "type"        => "dropdown",
                "heading"     => __( "Intro Animation", "swift-framework-admin" ),
                "param_name"  => "row_animation",
                "value"       => array(
                    __( "None", "swift-framework-admin" )             => "none",
                    __( "Fade In", "swift-framework-admin" )          => "fade-in",
                    __( "Fade From Left", "swift-framework-admin" )   => "fade-from-left",
                    __( "Fade From Right", "swift-framework-admin" )  => "fade-from-right",
                    __( "Fade From Bottom", "swift-framework-admin" ) => "fade-from-bottom",
                    __( "Move Up", "swift-framework-admin" )          => "move-up",
                    __( "Grow", "swift-framework-admin" )             => "grow",
                    __( "Fly", "swift-framework-admin" )              => "fly",
                    __( "Helix", "swift-framework-admin" )            => "helix",
                    __( "Flip", "swift-framework-admin" )             => "flip",
                    __( "Pop Up", "swift-framework-admin" )           => "pop-up",
                    __( "Spin", "swift-framework-admin" )             => "spin",
                    __( "Flip X", "swift-framework-admin" )           => "flip-x",
                    __( "Flip Y", "swift-framework-admin" )           => "flip-y"
                ),
                "description" => __( "Select an intro animation for the row which will show it when it appears within the viewport.", "swift-framework-admin" )
            ),
            array(
                "type"        => "textfield",
                "heading"     => __( "Animation Delay", "swift-framework-admin" ),
                "param_name"  => "row_animation_delay",
                "value"       => "0",
                "description" => __( "If you wish to add a delay to the animation, then you can set it here (ms).", "swift-framework-admin" )
            ),
            array(
                "type"       => "section",
                "param_name" => "section_misc_options",
                "heading"    => __( "Row Misc/ID Options", "swift-framework-admin" ),
            ),
            array(
                "type"        => "dropdown",
                "heading"     => __( "Responsive Visiblity", "swift-framework-admin" ),
                "param_name"  => "responsive_vis",
                "holder"      => 'indicator',
                "value"       => array(
                    ''                                                          => '',
                    __( 'Hidden on Desktop', "swift-framework-admin" )          => "hidden-lg_hidden-md",
                    __( 'Hidden on Tablet', "swift-framework-admin" )           => "hidden-sm",
                    __( 'Hidden on Desktop + Tablet', "swift-framework-admin" ) => "hidden-lg_hidden-md_hidden-sm",
                    __( 'Hidden on Tablet + Phone', "swift-framework-admin" )   => "hidden-xs_hidden-sm",
                    __( 'Hidden on Phone', "swift-framework-admin" )            => "hidden-xs",
                ),
                "description" => __( "Set the responsive visiblity for the row, if you would only like it to display on certain display sizes.", "swift-framework-admin" )
            ),
            array(
                "type"        => "textfield",
                "heading"     => __( "Row ID", "swift-framework-admin" ),
                "param_name"  => "row_id",
                "value"       => "",
                "description" => __( "If you wish to add an ID to the row, then add it here. You can then use the id to deep link to this section of the page. This is also used for one page navigation. NOTE: Make sure this is unique to the page!!", "swift-framework-admin" )
            ),
            array(
                "type"        => "textfield",
                "heading"     => __( "Row Section Name", "swift-framework-admin" ),
                "param_name"  => "row_name",
                "value"       => "",
                "description" => __( "This is used for the one page navigation, to identify the row. If this is left blank, then the row will be left off of the one page navigation.", "swift-framework-admin" )
            ),
            array(
                "type"        => "textfield",
                "heading"     => __( "Extra class name", "swift-framework-admin" ),
                "param_name"  => "row_el_class",
                "value"       => "",
                "description" => __( "If you wish to style this particular content element differently, then use this field to add a class name and then refer to it in your css file.", "swift-framework-admin" )
            )
        )
    ) );