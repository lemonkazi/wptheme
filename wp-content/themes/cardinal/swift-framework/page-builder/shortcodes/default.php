<?php

    /*
    *
    *	Swift Page Builder - Default Shortcodes
    *	------------------------------------------------
    *	Swift Framework
    * 	Copyright Swift Ideas 2014 - http://www.swiftideas.net
    *
    */

    /* TEXT BLOCK ASSET
    ================================================== */

    class SwiftPageBuilderShortcode_spb_text_block extends SwiftPageBuilderShortcode {

        public function content( $atts, $content = null ) {

            $title = $el_class = $width = $el_position = $inline_style = '';

            extract( shortcode_atts( array(
                'title'              => '',
                'icon'               => '',
                'padding_vertical'   => '0',
                'padding_horizontal' => '0',
                'animation'          => '',
                'animation_delay'    => '',
                'el_class'           => '',
                'el_position'        => '',
                'width'              => '1/2'
            ), $atts ) );

            $output = '';

            $el_class = $this->getExtraClass( $el_class );
            $width    = spb_translateColumnWidthToSpan( $width );

            $el_class .= ' spb_text_column';

            if ( $padding_vertical != "" ) {
                $inline_style .= 'padding-top:' . $padding_vertical . '%;padding-bottom:' . $padding_vertical . '%;';
            }
            if ( $padding_horizontal != "" ) {
                $inline_style .= 'padding-left:' . $padding_horizontal . '%;padding-right:' . $padding_horizontal . '%;';
            }

            $icon_output = "";

            if ( $icon ) {
                $icon_output = '<i class="' . $icon . '"></i>';
            }

            if ( $animation != "" && $animation != "none" ) {
                $output .= "\n\t" . '<div class="spb_content_element sf-animation ' . $width . $el_class . '" data-animation="' . $animation . '" data-delay="' . $animation_delay . '">';
            } else {
                $output .= "\n\t" . '<div class="spb_content_element ' . $width . $el_class . '">';
            }

            $output .= "\n\t\t" . '<div class="spb-asset-content" style="' . $inline_style . '">';
            if ( $icon_output != "" ) {
                $output .= ( $title != '' ) ? "\n\t\t\t" . '<div class="title-wrap"><h3 class="spb-heading spb-icon-heading"><span>' . $icon_output . '' . $title . '</span></h3></div>' : '';
            } else {
                $output .= ( $title != '' ) ? "\n\t\t\t" . $this->spb_title( $title, 'spb-text-heading' ) : '';
            }
            $output .= "\n\t\t\t" . do_shortcode( $content );
            $output .= "\n\t\t" . '</div>';
            $output .= "\n\t" . '</div> ' . $this->endBlockComment( $width );

            $output = $this->startRow( $el_position ) . $output . $this->endRow( $el_position );

            return $output;
        }
    }

    SPBMap::map( 'spb_text_block', array(
            "name"          => __( "Text Block", "swift-framework-admin" ),
            "base"          => "spb_text_block",
            "class"         => "",
            "icon"          => "spb-icon-text-block",
            "wrapper_class" => "clearfix",
            "controls"      => "full",
            "params"        => array(
                array(
                    "type"        => "textfield",
                    "holder"      => "div",
                    "heading"     => __( "Widget title", "swift-framework-admin" ),
                    "param_name"  => "title",
                    "value"       => "",
                    "description" => __( "Heading text. Leave it empty if not needed.", "swift-framework-admin" )
                ),
                array(
                    "type"        => "textfield",
                    "heading"     => __( "Title icon", "swift-framework-admin" ),
                    "param_name"  => "icon",
                    "value"       => "",
                    "description" => __( "Icon to the left of the title text. You can get the code from <a href='http://fortawesome.github.com/Font-Awesome/' target='_blank'>here</a>. E.g. fa-cloud", "swift-framework-admin" )
                ),
                array(
                    "type"        => "textarea_html",
                    "holder"      => "div",
                    "class"       => "",
                    "heading"     => __( "Text", "swift-framework-admin" ),
                    "param_name"  => "content",
                    "value"       => '',
                    //"value" => __("<p>This is a text block. Click the edit button to change this text.</p>", "swift-framework-admin"),
                    "description" => __( "Enter your content.", "swift-framework-admin" )
                ),
                array(
                    "type"       => "section",
                    "param_name" => "tb_animation_options",
                    "heading"    => __( "Animation Options", "swift-framework-admin" ),
                ),
                array(
                    "type"        => "dropdown",
                    "heading"     => __( "Intro Animation", "swift-framework-admin" ),
                    "param_name"  => "animation",
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
                    "description" => __( "Select an intro animation for the text block that will show it when it appears within the viewport.", "swift-framework-admin" )
                ),
                array(
                    "type"        => "textfield",
                    "heading"     => __( "Animation Delay", "swift-framework-admin" ),
                    "param_name"  => "animation_delay",
                    "value"       => "0",
                    "description" => __( "If you wish to add a delay to the animation, then you can set it here (ms).", "swift-framework-admin" )
                ),
                array(
                    "type"       => "section",
                    "param_name" => "tb_styling_options",
                    "heading"    => __( "Styling Options", "swift-framework-admin" ),
                ),
                array(
                    "type"        => "uislider",
                    "heading"     => __( "Padding - Vertical", "swift-framework-admin" ),
                    "param_name"  => "padding_vertical",
                    "value"       => "0",
                    "step"        => "1",
                    "min"         => "0",
                    "max"         => "20",
                    "description" => __( "Adjust the vertical padding for the text block (percentage).", "swift-framework-admin" )
                ),
                array(
                    "type"        => "uislider",
                    "heading"     => __( "Padding - Horizontal", "swift-framework-admin" ),
                    "param_name"  => "padding_horizontal",
                    "value"       => "0",
                    "step"        => "1",
                    "min"         => "0",
                    "max"         => "20",
                    "description" => __( "Adjust the horizontal padding for the text block (percentage).", "swift-framework-admin" )
                ),
                array(
                    "type"        => "textfield",
                    "heading"     => __( "Extra class name", "swift-framework-admin" ),
                    "param_name"  => "el_class",
                    "value"       => "",
                    "description" => __( "If you wish to style this particular content element differently, then use this field to add a class name and then refer to it in your css file.", "swift-framework-admin" )
                )
            )
        )
    );


    /* BUTTON ASSET
    ================================================== */

    class SwiftPageBuilderShortcode_spb_button extends SwiftPageBuilderShortcode {

        public function content( $atts, $content = null ) {

            $title = $el_class = $width = $el_position = $inline_style = '';

            extract( shortcode_atts( array(
                'button_size'       => 'standard',
                'button_colour'     => 'accent',
                'button_type'       => 'standard',
                'button_text'       => '',
                'button_icon'       => '',
                'button_link'       => '#',
                'button_target'     => '_self',
                'button_dropshadow' => '',
                'animation'         => '',
                'animation_delay'   => '',
                'el_class'          => '',
                'el_position'       => '',
                'width'             => '1/2'
            ), $atts ) );

            $output = '';

            $el_class = $this->getExtraClass( $el_class );
            $width    = spb_translateColumnWidthToSpan( $width );

            $output .= "\n\t" . '<div class="spb_button_element sf-animation ' . $width . $el_class . '" data-animation="' . $animation . '" data-delay="' . $animation_delay . '">';
            $output .= "\n\t\t\t" . do_shortcode( '[sf_button colour="' . $button_colour . '" type="' . $button_type . '" size="' . $button_size . '" link="' . $button_link . '" target="' . $button_target . '" icon="' . $button_icon . '" dropshadow="' . $button_dropshadow . '"]' . $button_text . '[/sf_button]' );
            $output .= "\n\t" . '</div> ' . $this->endBlockComment( $width );

            $output = $this->startRow( $el_position ) . $output . $this->endRow( $el_position );

            return $output;
        }
    }

    $type_arr = array(
        __( "Standard", "swift-framework-admin" )       => "standard",
        __( "Stroke to Fill", "swift-framework-admin" ) => "stroke-to-fill",
        __( "Icon Reveal", "swift-framework-admin" )    => "sf-icon-reveal",
        __( "Icon Stroke", "swift-framework-admin" )    => "sf-icon-stroke",
    );
    if ( sf_theme_opts_name() == "sf_joyn_options" ) {
        $type_arr = array(
            __( "Standard", "swift-framework-admin" )       => "standard",
            __( "Bordered", "swift-framework-admin" )       => "bordered",
            __( "Rotate 3D", "swift-framework-admin" )      => "rotate-3d",
            __( "Stroke to Fill", "swift-framework-admin" ) => "stroke-to-fill",
            __( "Icon Reveal", "swift-framework-admin" )    => "sf-icon-reveal",
            __( "Icon Stroke", "swift-framework-admin" )    => "sf-icon-stroke",
        );
    }
    $target_arr = array(
        __( "Same window", "swift-framework-admin" ) => "_self",
        __( "New window", "swift-framework-admin" )  => "_blank"
    );


    SPBMap::map( 'spb_button', array(
            "name"          => __( "Button", "swift-framework-admin" ),
            "base"          => "spb_button",
            "class"         => "",
            "icon"          => "spb-icon-button",
            "wrapper_class" => "clearfix",
            "controls"      => "full",
            "params"        => array(
                array(
                    "type"       => "dropdown",
                    "heading"    => __( "Button Size", "swift-framework-admin" ),
                    "param_name" => "button_size",
                    "value"      => array(
                        __( "Standard", "swift-framework-admin" ) => "standard",
                        __( "Large", "swift-framework-admin" )    => "large",
                    )
                ),
                array(
                    "type"       => "dropdown",
                    "heading"    => __( "Button Colour", "swift-framework-admin" ),
                    "param_name" => "button_colour",
                    "value"      => array(
                        __( "Accent", "swift-framework-admin" )              => "accent",
                        __( "Black", "swift-framework-admin" )               => "black",
                        __( "White", "swift-framework-admin" )               => "white",
                        __( "Blue", "swift-framework-admin" )                => "blue",
                        __( "Grey", "swift-framework-admin" )                => "grey",
                        __( "Light Grey", "swift-framework-admin" )          => "lightgrey",
                        __( "Orange", "swift-framework-admin" )              => "orange",
                        __( "Turquoise", "swift-framework-admin" )           => "turquoise",
                        __( "Green", "swift-framework-admin" )               => "green",
                        __( "Pink", "swift-framework-admin" )                => "pink",
                        __( "Gold", "swift-framework-admin" )                => "gold",
                        __( "Transparent - Light", "swift-framework-admin" ) => "transparent-light",
                        __( "Transparent - Dark", "swift-framework-admin" )  => "transparent-dark",
                    )
                ),
                array(
                    "type"       => "dropdown",
                    "heading"    => __( "Button Type", "swift-framework-admin" ),
                    "param_name" => "button_type",
                    "value"      => $type_arr
                ),
                array(
                    "type"        => "textfield",
                    "heading"     => __( "Button Text", "swift-framework-admin" ),
                    "param_name"  => "button_text",
                    "holder"      => "div",
                    "value"       => "Button Text",
                    "description" => __( "Icon to the left of the title text. You can get the code from <a href='http://fortawesome.github.com/Font-Awesome/' target='_blank'>here</a>. E.g. fa-cloud", "swift-framework-admin" )
                ),
                array(
                    "type"        => "icon-picker",
                    "heading"     => __( "Button Icon", "swift-framework-admin" ),
                    "param_name"  => "button_icon",
                    "value"       => "",
                    "description" => ''
                ),
                array(
                    "type"       => "textfield",
                    "heading"    => __( "Button Link", "swift-framework-admin" ),
                    "param_name" => "button_link",
                    "value"      => "",
                ),
                array(
                    "type"       => "dropdown",
                    "heading"    => __( "Button Target", "swift-framework-admin" ),
                    "param_name" => "button_target",
                    "value"      => $target_arr
                ),
                array(
                    "type"       => "dropdown",
                    "heading"    => __( "Button Dropshadow", "swift-framework-admin" ),
                    "param_name" => "button_dropshadow",
                    "value"      => array(
                        __( 'No', "swift-framework-admin" )  => "no",
                        __( 'Yes', "swift-framework-admin" ) => "yes"
                    ),
                ),
                array(
                    "type"       => "section",
                    "param_name" => "tb_animation_options",
                    "heading"    => __( "Animation Options", "swift-framework-admin" ),
                ),
                array(
                    "type"        => "dropdown",
                    "heading"     => __( "Intro Animation", "swift-framework-admin" ),
                    "param_name"  => "animation",
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
                    "description" => __( "Select an intro animation for the text block that will show it when it appears within the viewport.", "swift-framework-admin" )
                ),
                array(
                    "type"        => "textfield",
                    "heading"     => __( "Animation Delay", "swift-framework-admin" ),
                    "param_name"  => "animation_delay",
                    "value"       => "0",
                    "description" => __( "If you wish to add a delay to the animation, then you can set it here (ms).", "swift-framework-admin" )
                ),
                array(
                    "type"       => "section",
                    "param_name" => "btn_misc_options",
                    "heading"    => __( "Misc Options", "swift-framework-admin" ),
                ),
                array(
                    "type"        => "textfield",
                    "heading"     => __( "Extra class name", "swift-framework-admin" ),
                    "param_name"  => "el_class",
                    "value"       => "",
                    "description" => __( "If you wish to style this particular content element differently, then use this field to add a class name and then refer to it in your css file.", "swift-framework-admin" )
                )
            )
        )
    );


    /* BOXED CONTENT ASSET
    ================================================== */

    class SwiftPageBuilderShortcode_spb_boxed_content extends SwiftPageBuilderShortcode {

        public function content( $atts, $content = null ) {

            $title = $type = $bg_style = $inline_style = $custom_bg_colour = $custom_text_colour = $padding_vertical = $padding_horizontal = $el_class = $width = $el_position = '';

            extract( shortcode_atts( array(
                'title'              => '',
                'type'               => '',
                'custom_bg_colour'   => '',
                'custom_text_colour' => '',
                'padding_vertical'   => '0',
                'padding_horizontal' => '0',
                'el_class'           => '',
                'el_position'        => '',
                'width'              => '1/2'
            ), $atts ) );

            $output = '';

            $el_class = $this->getExtraClass( $el_class );
            $width    = spb_translateColumnWidthToSpan( $width );

            if ( $custom_bg_colour != "" ) {
                $bg_style .= 'background: ' . $custom_bg_colour . '!important;';
            }

            if ( $custom_text_colour != "" ) {
                $inline_style .= 'color: ' . $custom_text_colour . '!important;';
            }

            if ( $padding_vertical != "" ) {
                $inline_style .= 'padding-top:' . $padding_vertical . '%;padding-bottom:' . $padding_vertical . '%;';
            }
            if ( $padding_horizontal != "" ) {
                $inline_style .= 'padding-left:' . $padding_horizontal . '%;padding-right:' . $padding_horizontal . '%;';
            }

            $output .= "\n\t" . '<div class="spb_content_element spb_box_content ' . $width . $el_class . '">';
            $output .= "\n\t" . '<div class="spb-bg-color-wrap ' . $type . '" style="' . $bg_style . '">';
            $output .= "\n\t\t" . '<div class="spb-asset-content">';
            $output .= ( $title != '' ) ? "\n\t\t\t" . $this->spb_title( $title, '' ) : '';
            $output .= "\n\t\t";
            if ( $inline_style != "" ) {
                $output .= '<div class="box-content-wrap" style="' . $inline_style . '">' . do_shortcode( $content ) . '</div>';
            } else {
                $output .= '<div class="box-content-wrap">' . do_shortcode( $content ) . '</div>';
            }
            $output .= "\n\t\t" . '</div>';
            $output .= "\n\t\t" . '</div>';
            $output .= "\n\t" . '</div> ' . $this->endBlockComment( $width );

            $output = $this->startRow( $el_position ) . $output . $this->endRow( $el_position );

            return $output;
        }
    }

    SPBMap::map( 'spb_boxed_content', array(
        "name"          => __( "Boxed Content", "swift-framework-admin" ),
        "base"          => "spb_boxed_content",
        "class"         => "",
        "icon"          => "spb-icon-boxed-content",
        "wrapper_class" => "clearfix",
        "controls"      => "full",
        "params"        => array(
            array(
                "type"        => "textfield",
                "holder"      => "div",
                "heading"     => __( "Widget title", "swift-framework-admin" ),
                "param_name"  => "title",
                "value"       => "",
                "description" => __( "Heading text. Leave it empty if not needed.", "swift-framework-admin" )
            ),
            array(
                "type"        => "textarea_html",
                "holder"      => "div",
                "class"       => "",
                "heading"     => __( "Text", "swift-framework-admin" ),
                "param_name"  => "content",
                "value"       => __( "<p>This is a boxed content block. Click the edit button to edit this text.</p>", "swift-framework-admin" ),
                "description" => __( "Enter your content.", "swift-framework-admin" )
            ),
            array(
                "type"        => "dropdown",
                "heading"     => __( "Box type", "swift-framework-admin" ),
                "param_name"  => "type",
                "value"       => array(
                    __( 'Coloured', "swift-framework-admin" )          => "coloured",
                    __( 'White with stroke', "swift-framework-admin" ) => "whitestroke"
                ),
                "description" => __( "Choose the surrounding box type for this content", "swift-framework-admin" )
            ),
            array(
                "type"        => "colorpicker",
                "heading"     => __( "Background color", "swift-framework-admin" ),
                "param_name"  => "custom_bg_colour",
                "value"       => "",
                "description" => __( "Provide a background colour here. If blank, your colour customisaer settings will be used.", "swift-framework-admin" )
            ),
            array(
                "type"        => "colorpicker",
                "heading"     => __( "Text colour", "swift-framework-admin" ),
                "param_name"  => "custom_text_colour",
                "value"       => "",
                "description" => __( "Provide a text colour here. If blank, your colour customisaer settings will be used.", "swift-framework-admin" )
            ),
            array(
                "type"        => "uislider",
                "heading"     => __( "Padding - Vertical", "swift-framework-admin" ),
                "param_name"  => "padding_vertical",
                "value"       => "0",
                "step"        => "1",
                "min"         => "0",
                "max"         => "20",
                "description" => __( "Adjust the vertical padding for the text block (percentage).", "swift-framework-admin" )
            ),
            array(
                "type"        => "uislider",
                "heading"     => __( "Padding - Horizontal", "swift-framework-admin" ),
                "param_name"  => "padding_horizontal",
                "value"       => "0",
                "step"        => "1",
                "min"         => "0",
                "max"         => "20",
                "description" => __( "Adjust the horizontal padding for the text block (percentage).", "swift-framework-admin" )
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


    /* DIVIDER ASSET
    ================================================== */

    class SwiftPageBuilderShortcode_spb_divider extends SwiftPageBuilderShortcode {

        protected function content( $atts, $content = null ) {
            $with_line = $fullwidth = $type = $width = $el_class = $text = '';
            extract( shortcode_atts( array(
                'with_line'     => '',
                'type'          => '',
                'heading_text'  => '',
                'top_margin'    => '0px',
                'bottom_margin' => '30px',
                'fullwidth'     => '',
                'text'          => '',
                'width'         => '1/1',
                'el_class'      => '',
                'el_position'   => ''
            ), $atts ) );

            $width = spb_translateColumnWidthToSpan( $width );

            $style = "margin-top: " . $top_margin . "; margin-bottom: " . $bottom_margin . ";";

            $output = '';
            $output .= '<div class="divider-wrap ' . $width . '">';
            if ( $type == "heading" ) {
                $output .= '<div class="spb_divider ' . $el_class . '" style="' . $style . '">';
                $output .= '<h3 class="divider-heading">' . $heading_text . '</h3>';
                $output .= '</div>' . $this->endBlockComment( 'divider' ) . "\n";
            } else {
                $output .= '<div class="spb_divider ' . $type . ' spb_content_element ' . $el_class . '" style="' . $style . '">';
                if ( $type == "go_to_top" ) {
                    $output .= '<a class="animate-top" href="#">' . $text . '</a>';
                } else if ( $type == "go_to_top_icon1" ) {
                    $output .= '<a class="animate-top" href="#"><i class="ss-up"></i></a>';
                } else if ( $type == "go_to_top_icon2" ) {
                    $output .= '<a class="animate-top" href="#">' . $text . '<i class="ss-up"></i></a>';
                }
                $output .= '</div>' . $this->endBlockComment( 'divider' ) . "\n";
            }

            $output .= '</div>';


            if ( $fullwidth == "yes" && $width == "col-sm-12" ) {
                $output = $this->startRow( $el_position, '', true ) . $output . $this->endRow( $el_position, '', true );
            } else {
                $output = $this->startRow( $el_position ) . $output . $this->endRow( $el_position );
            }

            return $output;
        }
    }

    SPBMap::map( 'spb_divider', array(
        "name"        => __( "Divider", "swift-framework-admin" ),
        "base"        => "spb_divider",
        "class"       => "spb_divider",
        'icon'        => 'spb-icon-divider',
        "controls"    => '',
        "params"      => array(
            array(
                "type"        => "dropdown",
                "heading"     => __( "Divider type", "swift-framework-admin" ),
                "param_name"  => "type",
                "value"       => array(
                    __( 'Standard', "swift-framework-admin" )           => "standard",
                    __( 'Thin', "swift-framework-admin" )               => "thin",
                    __( 'Dotted', "swift-framework-admin" )             => "dotted",
                    __( 'Heading', "swift-framework-admin" )            => "heading",
                    __( 'Go to top (text)', "swift-framework-admin" )   => "go_to_top",
                    __( 'Go to top (Icon 1)', "swift-framework-admin" ) => "go_to_top_icon1",
                    __( 'Go to top (Icon 2)', "swift-framework-admin" ) => "go_to_top_icon2"
                ),
                "description" => __( "Select divider type.", "swift-framework-admin" )
            ),
            array(
                "type"        => "textfield",
                "heading"     => __( "Divider Heading Text", "swift-framework-admin" ),
                "param_name"  => "heading_text",
                "value"       => __( "", "swift-framework-admin" ),
                "description" => __( "The text for the the 'Heading' divider type.", "swift-framework-admin" )
            ),
            array(
                "type"        => "textfield",
                "heading"     => __( "Go to top text", "swift-framework-admin" ),
                "param_name"  => "text",
                "value"       => __( "Go to top", "swift-framework-admin" ),
                "description" => __( "The text for the 'Go to top (text)' divider type.", "swift-framework-admin" )
            ),
            array(
                "type"        => "textfield",
                "heading"     => __( "Top Margin", "swift-framework-admin" ),
                "param_name"  => "top_margin",
                "value"       => __( "0px", "swift-framework-admin" ),
                "description" => __( "Set the margin above the divider (include px).", "swift-framework-admin" )
            ),
            array(
                "type"        => "textfield",
                "heading"     => __( "Bottom Margin", "swift-framework-admin" ),
                "param_name"  => "bottom_margin",
                "value"       => __( "30px", "swift-framework-admin" ),
                "description" => __( "Set the margin below the divider (include px).", "swift-framework-admin" )
            ),
            array(
                "type"        => "dropdown",
                "heading"     => __( "Full width", "swift-framework-admin" ),
                "param_name"  => "fullwidth",
                "value"       => array(
                    __( 'No', "swift-framework-admin" )  => "no",
                    __( 'Yes', "swift-framework-admin" ) => "yes"
                ),
                "description" => __( "Select yes if you'd like the divider to be full width (only to be used with no sidebars, and with Standard/Thin/Dotted divider types).", "swift-framework-admin" )
            ),
            array(
                "type"        => "textfield",
                "heading"     => __( "Extra class name", "swift-framework-admin" ),
                "param_name"  => "el_class",
                "value"       => "",
                "description" => __( "If you wish to style this particular content element differently, then use this field to add a class name and then refer to it in your css file.", "swift-framework-admin" )
            )
        ),
        "js_callback" => array( "init" => "spbTextSeparatorInitCallBack" )
    ) );


    /* BLANK SPACER ASSET
    ================================================== */

    class SwiftPageBuilderShortcode_spb_blank_spacer extends SwiftPageBuilderShortcode {

        protected function content( $atts, $content = null ) {
            $height = $el_class = '';
            extract( shortcode_atts( array(
                'height'         => '',
                'width'          => '',
                'responsive_vis' => '',
                'el_position'    => '',
                'el_class'       => '',
            ), $atts ) );

            $responsive_vis = str_replace( "_", " ", $responsive_vis );
            $width          = spb_translateColumnWidthToSpan( $width );
            $el_class       = $this->getExtraClass( $el_class ) . ' ' . $responsive_vis;

            $output = '';
            $output .= '<div class="blank_spacer ' . $width . ' ' . $el_class . '" style="height:' . $height . ';">';
            $output .= '</div>' . $this->endBlockComment( 'divider' ) . "\n";

            $output = $this->startRow( $el_position ) . $output . $this->endRow( $el_position );

            return $output;
        }
    }

    SPBMap::map( 'spb_blank_spacer', array(
        "name"   => __( "Blank Spacer", "swift-framework-admin" ),
        "base"   => "spb_blank_spacer",
        "class"  => "spb_blank_spacer",
        'icon'   => 'spb-icon-spacer',
        "params" => array(
            array(
                "type"        => "textfield",
                "heading"     => __( "Height", "swift-framework-admin" ),
                "param_name"  => "height",
                "value"       => __( "30px", "swift-framework-admin" ),
                "description" => __( "The height of the spacer, in px (required).", "swift-framework-admin" )
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
                "heading"     => __( "Extra class name", "swift-framework-admin" ),
                "param_name"  => "el_class",
                "value"       => "",
                "description" => __( "If you wish to style this particular content element differently, then use this field to add a class name and then refer to it in your css file.", "swift-framework-admin" )
            )
        ),
    ) );


    /* MESSAGE BOX ASSET
    ================================================== */

    class SwiftPageBuilderShortcode_spb_message extends SwiftPageBuilderShortcode {

        protected function content( $atts, $content = null ) {
            $color = '';
            extract( shortcode_atts( array(
                'color'       => 'alert-info',
                'el_position' => ''
            ), $atts ) );
            $output = '';
            if ( $color == "alert-block" ) {
                $color = "";
            }

            $width = spb_translateColumnWidthToSpan( "1/1" );

            $output .= '<div class="' . $width . '"><div class="alert spb_content_element ' . $color . '"><div class="messagebox_text">' . spb_format_content( $content ) . '</div></div></div>' . $this->endBlockComment( 'alert box' ) . "\n";
            //$output .= '<div class="spb_messagebox message '.$color.'"><div class="messagebox_text">'.spb_format_content($content).'</div></div>';
            $output = $this->startRow( $el_position ) . $output . $this->endRow( $el_position );

            return $output;
        }
    }

    SPBMap::map( 'spb_message', array(
        "name"          => __( "Message Box", "swift-framework-admin" ),
        "base"          => "spb_message",
        "class"         => "spb_messagebox spb_controls_top_right",
        "icon"          => "spb-icon-message-box",
        "wrapper_class" => "alert",
        "controls"      => "edit_popup_delete",
        "params"        => array(
            array(
                "type"        => "dropdown",
                "heading"     => __( "Message box type", "swift-framework-admin" ),
                "param_name"  => "color",
                "value"       => array(
                    __( 'Informational', "swift-framework-admin" ) => "alert-info",
                    __( 'Warning', "swift-framework-admin" )       => "alert-block",
                    __( 'Success', "swift-framework-admin" )       => "alert-success",
                    __( 'Error', "swift-framework-admin" )         => "alert-error"
                ),
                "description" => __( "Select message type.", "swift-framework-admin" )
            ),
            array(
                "type"        => "textarea_html",
                "holder"      => "div",
                "class"       => "messagebox_text",
                "heading"     => __( "Message text", "swift-framework-admin" ),
                "param_name"  => "content",
                "value"       => __( "<p>This is a message box. Click the edit button to edit this text.</p>", "swift-framework-admin" ),
                "description" => __( "Message text.", "swift-framework-admin" )
            ),
            array(
                "type"        => "textfield",
                "heading"     => __( "Extra class name", "swift-framework-admin" ),
                "param_name"  => "el_class",
                "value"       => "",
                "description" => __( "If you wish to style this particular content element differently, then use this field to add a class name and then refer to it in your css file.", "swift-framework-admin" )
            )
        ),
        "js_callback"   => array( "init" => "spbMessageInitCallBack" )
    ) );


    /* TOGGLE ASSET
    ================================================== */

    class SwiftPageBuilderShortcode_spb_toggle extends SwiftPageBuilderShortcode {

        protected function content( $atts, $content = null ) {
            $title = $el_class = $open = null;
            extract( shortcode_atts( array(
                'title'       => __( "Click to toggle", "swift-framework-admin" ),
                'el_class'    => '',
                'open'        => 'false',
                'el_position' => '',
                'width'       => '1/1'
            ), $atts ) );
            $output = '';

            $width = spb_translateColumnWidthToSpan( $width );

            $el_class = $this->getExtraClass( $el_class );
            $open     = ( $open == 'true' ) ? ' spb_toggle_title_active' : '';
            $el_class .= ( $open == ' spb_toggle_title_active' ) ? ' spb_toggle_open' : '';
            $output .= '<div class="toggle-wrap ' . $width . '">';
            $output .= '<h4 class="spb_toggle' . $open . '">' . $title . '</h4><div class="spb_toggle_content' . $el_class . '">' . spb_format_content( $content ) . '</div>' . $this->endBlockComment( 'toggle' ) . "\n";
            $output .= '</div>';
            $output = $this->startRow( $el_position ) . $output . $this->endRow( $el_position );

            return $output;
        }
    }

    SPBMap::map( 'spb_toggle', array(
        "name"   => __( "Toggle", "swift-framework-admin" ),
        "base"   => "spb_toggle",
        "class"  => "spb_faq",
        "icon"   => "spb-icon-toggle",
        "params" => array(
            array(
                "type"        => "textfield",
                "holder"      => "h4",
                "class"       => "toggle_title",
                "heading"     => __( "Toggle title", "swift-framework-admin" ),
                "param_name"  => "title",
                "value"       => __( "Toggle title", "swift-framework-admin" ),
                "description" => __( "Toggle block title.", "swift-framework-admin" )
            ),
            array(
                "type"        => "textarea_html",
                "holder"      => "div",
                "class"       => "toggle_content",
                "heading"     => __( "Toggle content", "swift-framework-admin" ),
                "param_name"  => "content",
                "value"       => __( "<p>The toggle content goes here, click the edit button to change this text.</p>", "swift-framework-admin" ),
                "description" => __( "Toggle block content.", "swift-framework-admin" )
            ),
            array(
                "type"        => "dropdown",
                "heading"     => __( "Default state", "swift-framework-admin" ),
                "param_name"  => "open",
                "value"       => array(
                    __( "Closed", "swift-framework-admin" ) => "false",
                    __( "Open", "swift-framework-admin" )   => "true"
                ),
                "description" => __( "Select this if you want toggle to be open by default.", "swift-framework-admin" )
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

