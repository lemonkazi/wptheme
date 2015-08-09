<?php

    /*
    *
    *	Swift Page Builder - Code-Snipper Shortcode
    *	------------------------------------------------
    *	Swift Framework
    * 	Copyright Swift Ideas 2014 - http://www.swiftideas.net
    *
    */

    class SwiftPageBuilderShortcode_spb_codesnippet extends SwiftPageBuilderShortcode {

        public function content( $atts, $content = null ) {

            $title = $el_class = $width = $el_position = '';

            extract( shortcode_atts( array(
                'title'       => '',
                'el_class'    => '',
                'el_position' => '',
                'width'       => '1'
            ), $atts ) );

            $output = '';

            $el_class = $this->getExtraClass( $el_class );
            $width    = spb_translateColumnWidthToSpan( $width );

            $output .= "\n\t" . '<div class="spb_codesnippet_element ' . $width . $el_class . '">';
            $output .= "\n\t\t" . '<div class="spb-asset-content">';
            $output .= ( $title != '' ) ? "\n\t\t\t" . $this->spb_title( $widget_title, '' ) : '';
            $output .= "\n\t\t<code class='code-block'>" . spb_format_content( $content ) . "</code>";
            $output .= "\n\t\t" . '</div>';
            $output .= "\n\t" . '</div> ' . $this->endBlockComment( $width );

            //
            $output = $this->startRow( $el_position ) . $output . $this->endRow( $el_position );

            return $output;
        }
    }

    SPBMap::map( 'spb_codesnippet', array(
        "name"   => __( "Code Snippet", "swift-framework-admin" ),
        "base"   => "spb_codesnippet",
        "class"  => "spb_codesnippet",
        "icon"   => "spb-icon-code-snippet",
        "params" => array(
            array(
                "type"        => "textfield",
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
                "value"       => __( "<p>Add your code snippet here.</p>", "swift-framework-admin" ),
                "description" => __( "Enter your code snippet.", "swift-framework-admin" )
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
