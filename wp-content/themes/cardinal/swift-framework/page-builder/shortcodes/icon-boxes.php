<?php

    /*
    *
    *	Swift Page Builder - Icon Boxes Shortcode
    *	------------------------------------------------
    *	Swift Framework
    * 	Copyright Swift Ideas 2014 - http://www.swiftideas.net
    *
    */

    class SwiftPageBuilderShortcode_spb_icon_box extends SwiftPageBuilderShortcode {

        public function content( $atts, $content = null ) {

            $title = $el_class = $text_color = $image_url = $image_object = $width = $el_position = '';

            extract( shortcode_atts( array(
                'title'           => '',
                'icon'            => '',
                'character'       => '',
                'image'           => '',
                'box_type'        => '',
                'icon_color'      => '',
                'text_color'      => '',
                'bg_color'        => '',
                'flip_text_color' => '',
                'flip_bg_color'   => '',
                'animation'       => '',
                'animation_delay' => '',
                'link'            => '',
                'target'          => '',
                'el_class'        => '',
                'el_position'     => '',
                'width'           => '1/1'
            ), $atts ) );

            $output = '';

            if ( $image != "" ) {
                $img_url      = wp_get_attachment_url( $image, 'full' );
                $image_object = sf_aq_resize( $img_url, 70, 70, true, false );
                $image_url    = $image_object[0];
            }

            $icon_box_output = do_shortcode( '[sf_iconbox icon="' . $icon . '" character="' . $character . '" image="' . $image_url . '" type="' . $box_type . '" title="' . $title . '" animation="' . $animation . '" animation_delay="' . $animation_delay . '" bg_color="' . $bg_color . '" text_color="' . $text_color . '" icon_color="' . $icon_color . '" flip_text_color="' . $flip_text_color . '" flip_bg_color="' . $flip_bg_color . '" link="' . $link . '" target="' . $target . '"]' . $content . '[/sf_iconbox]' );

            $el_class = $this->getExtraClass( $el_class );
            $width    = spb_translateColumnWidthToSpan( $width );

            $output .= "\n\t" . '<div class="spb_icon_box ' . $width . $el_class . '">';
            $output .= "\n\t\t" . '<div class="spb-asset-content">';
            $output .= "\n\t\t" . $icon_box_output;
            $output .= "\n\t\t" . '</div>';
            $output .= "\n\t" . '</div> ' . $this->endBlockComment( $width );

            $output = $this->startRow( $el_position ) . $output . $this->endRow( $el_position );

            global $sf_include_carousel;
            $sf_include_carousel = true;

            return $output;
        }
    }

    $target_arr = array(
        __( "Same window", "swift-framework-admin" ) => "_self",
        __( "New window", "swift-framework-admin" )  => "_blank"
    );

    SPBMap::map( 'spb_icon_box', array(
        "name"          => __( "Icon Box", "swift-framework-admin" ),
        "base"          => "spb_icon_box",
        "class"         => "",
        "icon"          => "spb-icon-icon-box",
        "wrapper_class" => "clearfix",
        "params"        => array(
            array(
                "type"        => "textfield",
                "heading"     => __( "Icon Box title", "swift-framework-admin" ),
                "param_name"  => "title",
                "holder"      => 'div',
                "value"       => "",
                "description" => __( "Icon Box title text.", "swift-framework-admin" )
            ),
            array(
                "type"        => "dropdown",
                "heading"     => __( "Icon Box Type", "swift-framework-admin" ),
                "param_name"  => "box_type",
                "value"       => array(
                    __( 'Standard', "swift-framework-admin" )                 => "standard",
                    __( 'Standard with Title Icon', "swift-framework-admin" ) => "standard-title",
                    __( 'Bold', "swift-framework-admin" )                     => "bold",
                    __( 'Left Icon', "swift-framework-admin" )                => "left-icon",
                    __( 'Left Icon Alt', "swift-framework-admin" )            => "left-icon-alt",
                    __( 'Boxed One', "swift-framework-admin" )                => "boxed-one",
                    __( 'Boxed Two', "swift-framework-admin" )                => "boxed-two",
                    __( 'Boxed Three', "swift-framework-admin" )              => "boxed-three",
                    __( 'Boxed Four', "swift-framework-admin" )               => "boxed-four",
                    __( 'Animated', "swift-framework-admin" )                 => "animated",
                ),
                "description" => __( "Choose the type of icon box.", "swift-framework-admin" )
            ),
            array(
                "type"        => "icon-picker",
                "heading"     => __( "Icon Box Icon", "swift-framework-admin" ),
                "param_name"  => "icon",
                "value"       => "",
                "description" => ''
            ),
            array(
                "type"        => "textfield",
                "heading"     => __( "Icon Box Character", "swift-framework-admin" ),
                "param_name"  => "character",
                "value"       => "",
                "description" => __( "Instead of an icon, you can optionally provide a single letter/digit here. NOTE: This will override the icon selection.", "swift-framework-admin" )
            ),
            array(
                "type"        => "attach_image",
                "heading"     => __( "Icon Box Image", "swift-framework-admin" ),
                "param_name"  => "image",
                "value"       => "",
                "description" => __( "Instead of an icon, you can optionally upload an image here. This will be resized to 70px x 70px. NOTE: This will override the icon selection.", "swift-framework-admin" )
            ),
            array(
                "type"        => "textarea_html",
                "holder"      => "div",
                "class"       => "",
                "heading"     => __( "Text", "swift-framework-admin" ),
                "param_name"  => "content",
                "value"       => __( "click the edit button to change this text.", "swift-framework-admin" ),
                "description" => __( "Enter your content.", "swift-framework-admin" )
            ),
            array(
                "type"        => "textfield",
                "heading"     => __( "Icon Box Link", "swift-framework-admin" ),
                "param_name"  => "link",
                "value"       => "",
                "description" => __( "If you would like, you can set a link here for the icon and title to link through to.", "swift-framework-admin" )
            ),
            array(
                "type"       => "dropdown",
                "heading"    => __( "Link Target", "swift-framework-admin" ),
                "param_name" => "target",
                "value"      => $target_arr
            ),
            array(
                "type"       => "section",
                "param_name" => "ib_animation_options",
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
                "description" => __( "Select an intro animation for the icon box that will show it when it appears within the viewport.", "swift-framework-admin" )
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
                "param_name" => "ib_colour_options",
                "heading"    => __( "Colour Options", "swift-framework-admin" ),
            ),
            array(
                "type"        => "colorpicker",
                "heading"     => __( "Icon color", "swift-framework-admin" ),
                "param_name"  => "icon_color",
                "value"       => "",
                "description" => __( "Set the icon colour for the icon box, or leave blank to use the default colours.", "swift-framework-admin" )
            ),
            array(
                "type"        => "colorpicker",
                "heading"     => __( "Text color", "swift-framework-admin" ),
                "param_name"  => "text_color",
                "value"       => "",
                "description" => __( "Set the text colour for the icon box, or leave blank to use the default colours.", "swift-framework-admin" )
            ),
            array(
                "type"        => "colorpicker",
                "heading"     => __( "Background color", "swift-framework-admin" ),
                "param_name"  => "bg_color",
                "value"       => "",
                "description" => __( "Set the background colour for the icon box, or leave blank to use the default colours.", "swift-framework-admin" )
            ),
            array(
                "type"        => "colorpicker",
                "heading"     => __( "Flip Background color", "swift-framework-admin" ),
                "param_name"  => "flip_bg_color",
                "value"       => "",
                "description" => __( "Set the background colour for the back of the animated icon box, or leave blank to use the default colours.", "swift-framework-admin" )
            ),
            array(
                "type"        => "colorpicker",
                "heading"     => __( "Flip Text color", "swift-framework-admin" ),
                "param_name"  => "flip_text_color",
                "value"       => "",
                "description" => __( "Set the text colour for the back of the animated icon box, or leave blank to use the default colours.", "swift-framework-admin" )
            ),
            array(
                "type"       => "section",
                "param_name" => "ib_misc_options",
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
    ) );