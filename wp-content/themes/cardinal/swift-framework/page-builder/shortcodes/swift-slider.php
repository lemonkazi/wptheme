<?php

    /*
    *
    *	Swift Page Builder - Swift Slider Shortcode
    *	------------------------------------------------
    *	Swift Framework
    * 	Copyright Swift Ideas 2014 - http://www.swiftideas.net
    *
    */

    class SwiftPageBuilderShortcode_spb_swift_slider extends SwiftPageBuilderShortcode {

        protected function content( $atts, $content = null ) {

            $width = $el_class = $output = $items = $el_position = '';

            extract( shortcode_atts( array(
                'category'    => '',
                'fullscreen'  => 'false',
                'maxheight'   => '',
                'slidecount'  => '',
                'autoplay'    => '',
                'loop'        => '',
                'nav'         => 'true',
                'pagination'  => 'true',
                'continue'    => 'true',
                'el_position' => '',
                'fullwidth'   => '',
                'width'       => '1/1',
                'el_class'    => ''
            ), $atts ) );

            $el_class = $this->getExtraClass( $el_class );
            $width    = spb_translateColumnWidthToSpan( $width );

            $output .= "\n\t" . '<div class="spb_swift-slider spb_content_element ' . $width . $el_class . '">';
            $output .= "\n\t\t" . '<div class="spb-asset-content">';
            $output .= "\n\t\t\t" . do_shortcode( '[swift_slider type="slider" category="' . $category . '" fullscreen="' . $fullscreen . '" max_height="' . $maxheight . '" slide_count="' . $slidecount . '" loop="' . $loop . '" nav="' . $nav . '" pagination="' . $pagination . '" autoplay="' . $autoplay . '" continue="' . $continue . '"]' );
            $output .= "\n\t\t" . '</div>';
            $output .= "\n\t" . '</div> ' . $this->endBlockComment( $width );

            if ( $fullwidth == "yes" && $width == "col-sm-12" ) {
                $output = $this->startRow( $el_position, '', true ) . $output . $this->endRow( $el_position, '', true );
            } else {
                $output = $this->startRow( $el_position ) . $output . $this->endRow( $el_position );
            }

            return $output;

        }
    }

    SPBMap::map( 'spb_swift_slider', array(
        "name"   => __( "Swift Slider", "swift-framework-admin" ),
        "base"   => "spb_swift_slider",
        "class"  => "spb_swiftslider",
        "icon"   => "spb-icon-swiftslider",
        "params" => array(
            array(
                "type"        => "dropdown",
                "heading"     => __( "Fullscreen", "swift-framework-admin" ),
                "param_name"  => "fullscreen",
                "value"       => array(
                    __( 'Yes', "swift-framework-admin" ) => "true",
                    __( 'No', "swift-framework-admin" )  => "false"
                ),
                "description" => __( "Choose if you would like the slider to be window height.", "swift-framework-admin" )
            ),
            array(
                "type"        => "textfield",
                "heading"     => __( "Slider Max Height", "swift-framework-admin" ),
                "param_name"  => "maxheight",
                "value"       => "600",
                "description" => __( "Set the maximum height that the Swift Slider should display at (optional) (no px).", "swift-framework-admin" )
            ),
            array(
                "type"        => "textfield",
                "heading"     => __( "Slide Count", "swift-framework-admin" ),
                "param_name"  => "slidecount",
                "value"       => "5",
                "description" => __( "Set the number of slides to show. If blank then all will show.", "swift-framework-admin" )
            ),
            array(
                "type"        => "select-multiple",
                "heading"     => __( "Slide category", "swift-framework-admin" ),
                "param_name"  => "category",
                "value"       => sf_get_category_list( 'swift-slider-category' ),
                "description" => __( "Choose the category of slide that you would like to show, or all.", "swift-framework-admin" )
            ),
            array(
                "type"        => "textfield",
                "heading"     => __( "Autoplay", "swift-framework-admin" ),
                "param_name"  => "autoplay",
                "value"       => "",
                "description" => __( "If you would like the slider to auto-rotate, then set the autoplay rotate time in ms here. I.e. you would enter '5000' for the slider to rotate every 5 seconds.", "swift-framework-admin" )
            ),
            array(
                "type"        => "dropdown",
                "heading"     => __( "Loop", "swift-framework-admin" ),
                "param_name"  => "loop",
                "value"       => array(
                    __( 'Yes', "swift-framework-admin" ) => "true",
                    __( 'No', "swift-framework-admin" )  => "false"
                ),
                "description" => __( "Select if you'd like the slider to loop (slider type only).", "swift-framework-admin" )
            ),
            array(
                "type"        => "dropdown",
                "heading"     => __( "Navigation", "swift-framework-admin" ),
                "param_name"  => "nav",
                "value"       => array(
                    __( 'Yes', "swift-framework-admin" ) => "true",
                    __( 'No', "swift-framework-admin" )  => "false"
                ),
                "description" => __( 'Choose if you would like to display the left/right arrows on the slider (only if slider type is set to "Slider").', "swift-framework-admin" )
            ),
            array(
                "type"        => "dropdown",
                "heading"     => __( "Pagination", "swift-framework-admin" ),
                "param_name"  => "pagination",
                "value"       => array(
                    __( 'Yes', "swift-framework-admin" ) => "true",
                    __( 'No', "swift-framework-admin" )  => "false"
                ),
                "description" => __( "Choose if you would like to display the slider pagination.", "swift-framework-admin" )
            ),
            array(
                "type"        => "dropdown",
                "heading"     => __( "Continue", "swift-framework-admin" ),
                "param_name"  => "continue",
                "value"       => array(
                    __( 'Yes', "swift-framework-admin" ) => "true",
                    __( 'No', "swift-framework-admin" )  => "false"
                ),
                "description" => __( "Choose if you would like to display the continue button on Curtain slider type to progress to the content. If you want to only display the slider on teh page, and no content, then make sure you set this to NO.", "swift-framework-admin" )
            ),
            array(
                "type"        => "dropdown",
                "heading"     => __( "Full Width", "swift-framework-admin" ),
                "param_name"  => "fullwidth",
                "value"       => array(
                    __( 'Yes', "swift-framework-admin" ) => "yes",
                    __( 'No', "swift-framework-admin" )  => "no"
                ),
                "description" => __( "Select if you'd like the slider to be full width (edge to edge). NOTE: only possible on pages without sidebars.", "swift-framework-admin" )
            ),
            array(
                "type"        => "textfield",
                "heading"     => __( "Extra class name", "swift-framework-admin" ),
                "param_name"  => "el_class",
                "value"       => "",
                "description" => __( "If you wish to style this particular content element differently, then use this field to add a class name and then refer to it in your css file.", "swift-framework-admin" )
            )
        )
    ) );