<?php

    /*
    *
    *	Swift Page Builder - Column Shortcode
    *	------------------------------------------------
    *	Swift Framework
    * 	Copyright Swift Ideas 2014 - http://www.swiftideas.net
    *
    */

    class SwiftPageBuilderShortcode_spb_column extends SwiftPageBuilderShortcode {

        public function content( $atts, $content = null ) {

            $col_el_class = $inline_style = $width = $el_position = '';

            extract( shortcode_atts( array(
                'col_bg_color'                => '',
                'col_bg_image'                => '',
                'col_bg_type'                 => 'cover',
                'col_parallax_image_movement' => 'fixed',
                'col_parallax_image_speed'    => '0.5',
                'col_padding'                 => '',
                'col_animation'        	  	  => '',
                'col_animation_delay'  	  	  => '',
                'col_responsive_vis'          => '',
                'col_el_class'                => '',
                'el_position'                 => '',
                'width'                       => '1/2'
            ), $atts ) );

            $output = '';

            $col_responsive_vis = str_replace( "_", " ", $col_responsive_vis );
            $col_el_class       = $this->getExtraClass( $col_el_class ) . ' ' . $col_responsive_vis;
            $orig_width         = $width;
            $width              = spb_translateColumnWidthToSpan( $width );
            $img_url            = wp_get_attachment_image_src( $col_bg_image, 'full' );

            if ( $col_bg_color != "" ) {
                $inline_style .= 'background-color:' . $col_bg_color . ';';
            }

            if ( isset( $img_url ) && $img_url[0] != "" ) {
                $inline_style .= 'background-image: url(' . $img_url[0] . ');';
            }

            if ( $col_padding != "" ) {
                $inline_style .= 'padding:' . $col_padding . '%;';
            }
            
            if ( $col_animation != "" && $col_animation != "none" ) {
            	$col_el_class .= ' sf-animation';
                $animation_output = 'data-animation="' . $col_animation . '" data-delay="' . $col_animation_delay . '"';
            }

            global $column_width;

            if ( $orig_width != "" ) {
                $column_width = $orig_width;
            }

            if ( $col_bg_image != "" && $img_url[0] != "" ) {
                if ( $col_parallax_image_movement == "stellar" ) {
                    $output .= "\n\t" . '<div class="spb-column-container spb_parallax_asset sf-parallax parallax-' . $col_parallax_image_movement . ' spb_content_element bg-type-' . $col_bg_type . ' ' . $width . ' ' . $col_el_class . '" data-stellar-background-ratio="' . $col_parallax_image_speed . '" ' . $animation_output . ' style="' . $inline_style . '">';
                } else {
                    $output .= "\n\t" . '<div class="spb-column-container spb_parallax_asset sf-parallax parallax-' . $col_parallax_image_movement . ' spb_content_element bg-type-' . $col_bg_type . ' ' . $width . ' ' . $col_el_class . '" ' . $animation_output . ' style="' . $inline_style . '">';
                }
            } else {
                $output .= "\n\t" . '<div class="spb-column-container ' . $width . ' ' . $col_el_class . '" ' . $animation_output . ' style="' . $inline_style . '">';
            }
            $output .= "\n\t\t" . '<div class="spb-asset-content">';
            $output .= "\n\t\t" . spb_format_content( $content );
            $output .= "\n\t\t" . '</div>';
            $output .= "\n\t" . '</div> ' . $this->endBlockComment( $width );

            $column_width = "";

            $output = $this->startRow( $el_position, $orig_width ) . $output . $this->endRow( $el_position, 'column' );

            if ( isset( $img_url ) && $img_url[0] != "" && $col_parallax_image_movement == "stellar" ) {
                global $sf_include_parallax;
                $sf_include_parallax = true;
            }

            return $output;
        }

        public function contentAdmin( $atts, $content = null ) {
            $width = '';
            extract( shortcode_atts( array(
                'width'                       => 'column_12',
                'col_bg_color'                => '',
                'col_bg_image'                => '',
                'col_bg_type'                 => '',
                'col_parallax_image_movement' => '',
                'col_parallax_image_speed'    => '',
                'col_padding'                 => '',
                'col_animation'        	  	  => '',
                'col_animation_delay'  	  	  => '',
                'col_responsive_vis'          => '',
                'el_position'                 => '',
                'col_el_class'                => '',
            ), $atts ) );

            $output = '';

            $column_controls = $this->getColumnControls( 'column' );

            if ( $width == 'column_14' || $width == '1/4' ) {
                $width = array( 'span3' );
            } else if ( $width == 'column_14-14-14-14' ) {
                $width = array( 'span3', 'span3', 'span3', 'span3' );
            } else if ( $width == 'column_14-12-14' ) {
                $width = array( 'span3', 'span6', 'span3' );
            } else if ( $width == 'column_12-14-14' ) {
                $width = array( 'span6', 'span3', 'span3' );
            } else if ( $width == 'column_14-14-12' ) {
                $width = array( 'span3', 'span3', 'span6' );
            } else if ( $width == 'column_13' || $width == '1/3' ) {
                $width = array( 'span4' );
            } else if ( $width == 'column_13-23' ) {
                $width = array( 'span4', 'span8' );
            } else if ( $width == 'column_23-13' ) {
                $width = array( 'span8', 'span4' );
            } else if ( $width == 'column_13-13-13' ) {
                $width = array( 'span4', 'span4', 'span4' );
            } else if ( $width == 'column_12' || $width == '1/2' ) {
                $width = array( 'span6' );
            } else if ( $width == 'column_12-12' ) {
                $width = array( 'span6', 'span6' );
            } else if ( $width == 'column_23' || $width == '2/3' ) {
                $width = array( 'span8' );
            } else if ( $width == 'column_34' || $width == '3/4' ) {
                $width = array( 'span9' );
            } else if ( $width == 'column_16' || $width == '1/6' ) {
                $width = array( 'span2' );
            } else {
                $width = array( 'span12' );
            }


            for ( $i = 0; $i < count( $width ); $i ++ ) {
                $output .= '<div data-element_type="spb_column" class="spb_column spb_sortable spb_droppable ' . $width[ $i ] . ' not-column-inherit">';
                $output .= '<input type="hidden" class="spb_sc_base" name="" value="spb_column" />';
                $output .= str_replace( "%column_size%", spb_translateColumnWidthToFractional( $width[ $i ] ), $column_controls );
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
            }

            return $output;
        }
    }

    SPBMap::map( 'spb_column', array(
        "name"            => __( "Column", "swift-framework-admin" ),
        "base"            => "spb_column",
        "controls"        => "full-column",
        "content_element" => false,
        "params"          => array(
            array(
                "type"       => "section",
                "param_name" => "section_bg_video_options",
                "heading"    => __( "Column Background Options", "swift-framework-admin" ),
            ),
            array(
                "type"        => "colorpicker",
                "heading"     => __( "Background color", "swift-framework-admin" ),
                "param_name"  => "col_bg_color",
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
                "param_name"  => "col_bg_image",
                "value"       => "",
                "description" => "Choose an image to use as the background for the parallax area."
            ),
            array(
                "type"        => "dropdown",
                "heading"     => __( "Background Image Type", "swift-framework-admin" ),
                "param_name"  => "col_bg_type",
                "value"       => array(
                    __( "Cover", "swift-framework-admin" )   => "cover",
                    __( "Pattern", "swift-framework-admin" ) => "pattern"
                ),
                "description" => __( "If you're uploading an image that you want to spread across the whole asset, then choose cover. Else choose pattern for an image you want to repeat.", "swift-framework-admin" )
            ),
            array(
                "type"        => "dropdown",
                "heading"     => __( "Background Image Movement", "swift-framework-admin" ),
                "param_name"  => "col_parallax_image_movement",
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
                "param_name"  => "col_parallax_image_speed",
                "value"       => "0.5",
                "description" => "The speed at which the parallax image moves in relation to the page scrolling. For example, 0.5 would mean the image scrolls at half the speed of the standard page scroll. (Default 0.5)."
            ),
            array(
                "type"       => "section",
                "param_name" => "section_display_options",
                "heading"    => __( "Column Display Options", "swift-framework-admin" ),
            ),
            array(
                "type"        => "uislider",
                "heading"     => __( "Padding", "swift-framework-admin" ),
                "param_name"  => "col_padding",
                "value"       => "0",
                "min"         => "0",
                "step"        => "1",
                "max"         => "20",
                "description" => __( "Adjust the padding for the column. (%)", "swift-framework-admin" )
            ),
            array(
                "type"       => "section",
                "param_name" => "col_animation_options",
                "heading"    => __( "Animation Options", "swift-framework-admin" ),
            ),
            array(
                "type"        => "dropdown",
                "heading"     => __( "Intro Animation", "swift-framework-admin" ),
                "param_name"  => "col_animation",
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
                "description" => __( "Select an intro animation for the column which will show it when it appears within the viewport.", "swift-framework-admin" )
            ),
            array(
                "type"        => "textfield",
                "heading"     => __( "Animation Delay", "swift-framework-admin" ),
                "param_name"  => "col_animation_delay",
                "value"       => "0",
                "description" => __( "If you wish to add a delay to the animation, then you can set it here (ms).", "swift-framework-admin" )
            ),
            array(
                "type"       => "section",
                "param_name" => "section_misc_options",
                "heading"    => __( "Column Misc Options", "swift-framework-admin" ),
            ),
            array(
                "type"        => "dropdown",
                "heading"     => __( "Responsive Visiblity", "swift-framework-admin" ),
                "param_name"  => "col_responsive_vis",
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
                "heading"     => __( "Extra class name", "swift-framework-admin" ),
                "param_name"  => "col_el_class",
                "value"       => "",
                "description" => __( "If you wish to style this particular content element differently, then use this field to add a class name and then refer to it in your css file.", "swift-framework-admin" )
            )
        )
    ) );