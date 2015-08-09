<?php

    /*
    *
    *	Swift Page Builder - Promo Bar Shortcode
    *	------------------------------------------------
    *	Swift Framework
    * 	Copyright Swift Ideas 2014 - http://www.swiftideas.net
    *
    */

    class SwiftPageBuilderShortcode_spb_promo_bar extends SwiftPageBuilderShortcode {

        protected function content( $atts, $content = null ) {
            $btn_color = $btn_text = $display_type = $target = $href = $promo_bar_text = $fullwidth = $inline_style = $inline_style_alt = $width = $position = $el_class = '';
            extract( shortcode_atts( array(
                'btn_color'      => 'accent',
                'btn_text'       => '',
                'target'         => '',
                'display_type'   => '',
                'href'           => '',
                'shadow'         => 'yes',
                'promo_bar_text' => '',
                'width'          => '1/1',
                'bg_color'       => '',
                'text_color'     => '',
                'fullwidth'      => 'no',
                'el_class'       => '',
                'el_position'    => '',
            ), $atts ) );
            $output = '';

            $width    = spb_translateColumnWidthToSpan( $width );
            $el_class = $this->getExtraClass( $el_class );

            $sidebar_config = sf_get_post_meta( get_the_ID(), 'sf_sidebar_config', true );

            $sidebars = '';
            if ( ( $sidebar_config == "left-sidebar" ) || ( $sidebar_config == "right-sidebar" ) ) {
                $sidebars = 'one-sidebar';
            } else if ( $sidebar_config == "both-sidebars" ) {
                $sidebars = 'both-sidebars';
            } else {
                $sidebars = 'no-sidebars';
            }

            if ( $bg_color != "" ) {
                $inline_style .= 'background-color:' . $bg_color . ';';
            }
            if ( $text_color != "" ) {
                $inline_style_alt .= 'color:' . $text_color . ';';
            }

            if ( $target == 'same' || $target == '_self' ) {
                $target = '_self';
            }
            if ( $target != '' ) {
                $target = $target;
            }

            $output .= '<div class="spb-promo-wrap spb_content_element clearfix ' . $width . ' ' . $position . $el_class . '">' . "\n";
            $output .= '<div class="sf-promo-bar ' . $display_type . '" style="' . $inline_style . '">';
            if ( $display_type == "promo-button" ) {
                $output .= '<p style="' . $inline_style_alt . '">' . $promo_bar_text . '</p>';
                $output .= '<a href="' . $href . '" target="' . $target . '" class="sf-button dropshadow ' . $btn_color . '">' . $btn_text . '</a>';
            } else if ( $display_type == "promo-arrow" ) {
                $output .= '<a href="' . $href . '" target="' . $target . '" style="' . $inline_style_alt . '"><p>' . $promo_bar_text . '</p><i class="ss-navigateright"></i></a>';
            } else if ( $display_type == "promo-text" ) {
                $output .= '<a href="' . $href . '" target="' . $target . '" style="' . $inline_style_alt . '"><p>' . do_shortcode( $promo_bar_text ) . '</p></a>';
            } else {
                $output .= '<p>' . do_shortcode( $promo_bar_text ) . '</p>';
            }
            $output .= '</div>';
            $output .= '</div> ' . $this->endBlockComment( '.spb_impact_text' ) . "\n";

            if ( $fullwidth == "yes" && $sidebars == "no-sidebars" ) {
                $output = $this->startRow( $el_position, '', true ) . $output . $this->endRow( $el_position, '', true );
            } else {
                $output = $this->startRow( $el_position ) . $output . $this->endRow( $el_position );
            }

            return $output;
        }
    }

    $colors_arr = array(
        'Accent'              => 'accent',
        'Black'               => 'black',
        'White'               => 'white',
        'Grey'                => 'grey',
        'Light Grey'          => 'lightgrey',
        'Gold'                => 'gold',
        'Light Blue'          => 'lightblue',
        'Green'               => 'green',
        'Gold'                => 'gold',
        'Turquoise'           => 'turquoise',
        'Pink'                => 'pink',
        'Orange'              => 'orange',
        'Turquoise'           => 'turquoise',
        'Transparent - Light' => 'transparent-light',
        'Transparent - Dark'  => 'transparent-dark',
    );

    $target_arr = array(
        __( "Same window", "swift-framework-admin" ) => "_self",
        __( "New window", "swift-framework-admin" )  => "_blank"
    );

    SPBMap::map( 'spb_promo_bar', array(
        "name"     => __( "Promo Bar", "swift-framework-admin" ),
        "base"     => "spb_promo_bar",
        "class"    => "button_grey",
        "icon"     => "spb-icon-impact-text",
        "controls" => "edit_popup_delete",
        "params"   => array(
            array(
                "type"        => "dropdown",
                "heading"     => __( "Display Type", "swift-framework-admin" ),
                "param_name"  => "display_type",
                "value"       => array(
                    __( 'Text + Button', "swift-framework-admin" )                => 'promo-button',
                    __( 'Text + Arrow (Full Bar Link)', "swift-framework-admin" ) => 'promo-arrow',
                    __( 'Text Only (Full Bar Link)', "swift-framework-admin" )    => 'promo-text',
                    __( 'Custom Content', "swift-framework-admin" )               => 'promo-custom'
                ),
                "description" => __( "Choose the display type for the promo bar.", "swift-framework-admin" )
            ),
            array(
                "type"        => "textfield",
                "heading"     => __( "Promo Text", "swift-framework-admin" ),
                "param_name"  => "promo_bar_text",
                "holder"      => 'div',
                "value"       => __( "Enter your text here", "swift-framework-admin" ),
                "description" => __( "Enter the text for the promo bar here.", "swift-framework-admin" )
            ),
            array(
                "type"        => "textfield",
                "heading"     => __( "Button Text", "swift-framework-admin" ),
                "param_name"  => "btn_text",
                "value"       => __( "Button Text", "swift-framework-admin" ),
                "description" => __( "The text that appears on the button (Text + Button display type).", "swift-framework-admin" )
            ),
            array(
                "type"        => "dropdown",
                "heading"     => __( "Button Color Preset", "swift-framework-admin" ),
                "param_name"  => "btn_color",
                "value"       => $colors_arr,
                "description" => __( "Button color preset.", "swift-framework-admin" )
            ),
            array(
                "type"        => "textfield",
                "heading"     => __( "Link URL", "swift-framework-admin" ),
                "param_name"  => "href",
                "value"       => "",
                "description" => __( "The link for the promo bar.", "swift-framework-admin" )
            ),
            array(
                "type"       => "dropdown",
                "heading"    => __( "Link Target", "swift-framework-admin" ),
                "param_name" => "target",
                "value"      => $target_arr
            ),
            array(
                "type"        => "colorpicker",
                "heading"     => __( "Background color", "swift-framework-admin" ),
                "param_name"  => "bg_color",
                "value"       => "",
                "description" => __( "Select a background colour for the asset here.", "swift-framework-admin" )
            ),
            array(
                "type"        => "colorpicker",
                "heading"     => __( "Text color", "swift-framework-admin" ),
                "param_name"  => "text_color",
                "value"       => "",
                "description" => __( "Select a text colour for the asset here.", "swift-framework-admin" )
            ),
            array(
                "type"        => "dropdown",
                "heading"     => __( "Full Width", "swift-framework-admin" ),
                "param_name"  => "fullwidth",
                "value"       => array(
                    __( 'Yes', "swift-framework-admin" ) => "yes",
                    __( 'No', "swift-framework-admin" )  => "no"
                ),
                "description" => __( "Select if you'd like the asset to be full width (edge to edge). NOTE: only possible on pages without sidebars.", "swift-framework-admin" )
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