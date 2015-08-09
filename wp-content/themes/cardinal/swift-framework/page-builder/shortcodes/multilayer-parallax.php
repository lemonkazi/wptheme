<?php

    /*
*
*	Swift Page Builder - Multilayer Parallax Shortcode
*	------------------------------------------------
*	Swift Framework
* 	Copyright Swift Ideas 2014 - http://www.swiftideas.net
*
*/

    class SwiftPageBuilderShortcode_spb_multilayer_parallax extends SwiftPageBuilderShortcode {

        public function contentAdmin( $atts, $content = null ) {
            $width                = $custom_markup = '';
            $shortcode_attributes = array( 'width' => '1/1' );
            foreach ( $this->settings['params'] as $param ) {
                if ( $param['param_name'] != 'content' ) {

                    if ( is_string( $param['value'] ) ) {
                        $shortcode_attributes[ $param['param_name'] ] = __( $param['value'], "swift-framework-admin" );
                    } else {
                        $shortcode_attributes[ $param['param_name'] ] = $param['value'];
                    }
                } else if ( $param['param_name'] == 'content' && $content == null ) {
                    $content = __( $param['value'], "swift-framework-admin" );
                }
            }
            extract( shortcode_atts(
                $shortcode_attributes
                , $atts ) );

            $output = '';

            $tmp = '';

            $output .= do_shortcode( $content );
            $elem = $this->getElementHolder( $width );

            $iner = '';
            foreach ( $this->settings['params'] as $param ) {
                $custom_markup = '';
                $param_value   = isset( $$param['param_name'] ) ? $$param['param_name'] : null;

                if ( is_array( $param_value ) ) {
                    // Get first element from the array
                    reset( $param_value );
                    $first_key   = key( $param_value );
                    $param_value = $param_value[ $first_key ];
                }
                $iner .= $this->singleParamHtmlHolder( $param, $param_value );
            }


            if ( isset( $this->settings["custom_markup"] ) && $this->settings["custom_markup"] != '' ) {
                if ( $content != '' ) {
                    $custom_markup = str_ireplace( "%content%", $tmp . $content, $this->settings["custom_markup"] );
                } else if ( $content == '' && isset( $this->settings["default_content"] ) && $this->settings["default_content"] != '' ) {
                    $custom_markup = str_ireplace( "%content%", $this->settings["default_content"], $this->settings["custom_markup"] );
                }

                $iner .= do_shortcode( $custom_markup );
            }
            $elem   = str_ireplace( '%spb_element_content%', $iner, $elem );
            $output = $elem;

            return $output;
        }

        public function content( $atts, $content = null ) {

            $title = $size = $width = $el_class = '';
            extract( shortcode_atts( array(
                'width'       => '1/1',
                'fullscreen'  => 'false',
                'maxheight'   => '600',
                'x_scalar'    => '20',
                'y_scalar'    => '20',
                'el_class'    => '',
                'el_position' => ''
            ), $atts ) );
            $output = '';

            $el_class = $this->getExtraClass( $el_class );
            $width    = spb_translateColumnWidthToSpan( $width );
            $size     = str_replace( array( 'px', ' ' ), array( '', '' ), $size );

            global $sf_ml_parallax_layer;
            $sf_ml_parallax_layer = 1000;

            $output .= "\n\t" . '<div class="spb_multilayer_parallax spb_content_element ' . $width . $el_class . '" data-xscalar="' . $x_scalar . '" data-yscalar="' . $y_scalar . '" data-fullscreen="' . $fullscreen . '" data-max-height="' . $maxheight . '" style="height'.$maxheight.';">';
            $output .= "\n\t\t\t" . do_shortcode( $content );
            $output .= "\n\t" . '</div> ' . $this->endBlockComment( $width );

            $output = $this->startRow( $el_position, '', true ) . $output . $this->endRow( $el_position, '', true );

            global $sf_include_ml_parallax;
            $sf_include_ml_parallax = true;

            return $output;
        }

    }

    SPBMap::map( 'spb_multilayer_parallax', array(
        "name"            => __( "Multilayer Parallax", "swift-framework-admin" ),
        "base"            => "spb_multilayer_parallax",
        "controls"        => "full",
        "class"           => "spb_multilayer_parallax",
        "icon"            => "spb-icon-parallax",
        "params"          => array(
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
                "heading"     => __( "Horizontal scale movement", "swift-framework-admin" ),
                "param_name"  => "x_scalar",
                "value"       => "",
                "description" => __( "Multiplies the X-axis input motion by this value, increasing or decreasing the sensitivity of the layer motion.", "swift-framework-admin" )
            ),
            array(
                "type"        => "textfield",
                "heading"     => __( "Vertical scale movement", "swift-framework-admin" ),
                "param_name"  => "y_scalar",
                "value"       => "",
                "description" => __( "Multiplies the Y-axis input motion by this value, increasing or decreasing the sensitivity of the layer motion.", "swift-framework-admin" )
            ),
            array(
                "type"        => "textfield",
                "heading"     => __( "Extra class name", "swift-framework-admin" ),
                "param_name"  => "el_class",
                "value"       => "",
                "description" => __( "If you wish to style this particular content element differently, then use this field to add a class name and then refer to it in your css file.", "swift-framework-admin" )
            )
        ),
        "custom_markup"   => '
	<div class="tab_controls">
		<button class="add_tab">' . __( "Add New Layer", "swift-framework-admin" ) . '</button>
	</div>

	<div class="spb_tabs_holder">
		%content%
	</div>',
        'default_content' => '
					
				[spb_multilayer_parallax_layer layer_title="' . __( "Layer 1", "swift-framework-admin" ) . '"]' . __( 'This is a Parallax Layer text. Click the edit button to change it.', 'swift-framework-admin' ) . '[/spb_multilayer_parallax_layer]',
        "js_callback"     => array( "init" => "spbTabsInitCallBack" )


    ) );


    /* MULTILAYER PARALLAX LAYER
    ================================================== */

    class SwiftPageBuilderShortcode_spb_multilayer_parallax_layer extends SwiftPageBuilderShortcode {


        public function contentAdmin( $atts, $content ) {
            $custom_markup        = '';
            $shortcode_attributes = array( 'width' => '1/1' );

            foreach ( $this->settings['params'] as $param ) {
                if ( $param['param_name'] != 'content' ) {
                    if ( is_string( $param['value'] ) ) {
                        $shortcode_attributes[ $param['param_name'] ] = __( $param['value'], "swift-framework-admin" );
                    } else {
                        $shortcode_attributes[ $param['param_name'] ] = $param['value'];
                    }
                } else if ( $param['param_name'] == 'content' && $content == null ) {
                    $content = __( $param['value'], "swift-framework-admin" );
                }
            }
            extract( shortcode_atts(
                $shortcode_attributes
                , $atts ) );

            $output = '';

            $elem = $this->getElementHolder( $width );

            $iner = '';

            foreach ( $this->settings['params'] as $param ) {

                $param_value = isset( $$param['param_name'] ) ? $$param['param_name'] : null;

                if ( is_array( $param_value ) ) {
                    // Get first element from the array
                    reset( $param_value );
                    $first_key   = key( $param_value );
                    $param_value = $param_value[ $first_key ];


                }

                if ( $param['param_name'] == 'layer_image' && $content != '' ) {
                    $iner .= $this->singleParamHtmlHolderParallaxImage( $param, $param_value );
                } else {
                    $iner .= $this->singleParamHtmlHolder( $param, $param_value );
                }

            }

            $tmp = '';
            if ( isset( $this->settings["custom_markup"] ) && $this->settings["custom_markup"] != '' ) {
                if ( $content != '' ) {
                    $custom_markup = "";
                } else if ( $content == '' && isset( $this->settings["default_content"] ) && $this->settings["default_content"] != '' ) {

                    $custom_markup = "";
                }

                $iner .= do_shortcode( $custom_markup );
            }
            $elem = str_ireplace( '%spb_element_content%', $iner, $elem );

            $output = $elem;
            $output = '<div class="row-fluid spb_column_container not-column-inherit not-sortable">' . $output . '</div>';

            return $output;
        }

        public function singleParamHtmlHolder( $param, $value ) {
            $output = '';

            $param_name = isset( $param['param_name'] ) ? $param['param_name'] : '';
            $type       = isset( $param['type'] ) ? $param['type'] : '';
            $class      = isset( $param['class'] ) ? $param['class'] : '';

            if ( isset( $param['holder'] ) == false || $param['holder'] == 'hidden' ) {
                $output .= '<input type="hidden" class="spb_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '" value="' . $value . '" />';
                if ( ( $param['type'] ) == 'attach_image' ) {
                    $img = spb_getImageBySize( array(
                        'attach_id'  => (int) preg_replace( '/[^\d]/', '', $value ),
                        'thumb_size' => 'medium'
                    ) );
                    $output .= ( $img ? $img['thumbnail'] : '<img width="150" height="150" src="' . SwiftPageBuilder::getInstance()->assetURL( 'img/blank_f7.gif' ) . '" class="attachment-medium" alt="" title="" />' ) . '<a href="#" class="column_edit_trigger' . ( $img && ! empty( $img['p_img_large'][0] ) ? ' image-exists' : '' ) . '"><i class="spb-icon-single-image"></i>' . __( 'No image yet! Click here to select it now.', 'swift-framework-admin' ) . '</a>';
                }
            } else {
                $output .= '<' . $param['holder'] . ' class="spb_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '">' . $value . '</' . $param['holder'] . '>';
            }

            return $output;
        }

        public function singleParamHtmlHolderParallaxImage( $param, $value ) {

            $output = '';

            $param_name = isset( $param['param_name'] ) ? $param['param_name'] : '';
            $type       = isset( $param['type'] ) ? $param['type'] : '';
            $class      = isset( $param['class'] ) ? $param['class'] : '';

            if ( isset( $param['holder'] ) == false || $param['holder'] == 'hidden' ) {
                $output .= '<input type="hidden" class="spb_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '" value="' . $value . '" />';
                if ( ( $param['type'] ) == 'attach_image' ) {


                    $img = spb_getImageBySize( array(
                        'attach_id'  => (int) preg_replace( '/[^\d]/', '', $value ),
                        'thumb_size' => 'medium'
                    ) );
                    $output .= ( $img ? $img['thumbnail'] : '<img width="150" height="150" src="' . SwiftPageBuilder::getInstance()->assetURL( 'img/blank_f7.gif' ) . '" class="attachment-medium" alt="" title="" />' ) . '<a href="#" class="hide-layer-image column_edit_trigger' . ( $img && ! empty( $img['p_img_large'][0] ) ? ' image-exists' : '' ) . '"><i class="spb-icon-single-image"></i>' . __( 'No image yet! Click here to select it now.', 'swift-framework-admin' ) . '</a>';
                }
            } else {
                $output .= '<' . $param['holder'] . ' class="spb_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '">' . $value . '</' . $param['holder'] . '>';
            }

            return $output;
        }


        protected function content( $atts, $content = null ) {

            $layer_image;
            extract( shortcode_atts( array(
                'layer_image'     => '',
                'layer_depth'     => '0.00',
                'layer_type'      => 'original',
                'layer_bg_pos'    => 'center center',
                'layer_bg_repeat' => 'no-repeat',
                'text_layer'      => 'no'
            ), $atts ) );
            $output = '';

            //$width = spb_translateColumnWidthToSpan($width);
            $img_url      = wp_get_attachment_image_src( $layer_image, 'full' );
            $inline_style = $output = "";

            //$layer_bg_pos = str_replace('_', ' ', $layer_bg_pos);

            global $sf_ml_parallax_layer;
            $sf_ml_parallax_layer --;

            $inline_style .= 'z-index: ' . $sf_ml_parallax_layer . ';';

            $output .= '<div id="spb-mlp-' . $sf_ml_parallax_layer . '" class="layer" data-depth="' . $layer_depth . '" style="' . $inline_style . '">' . "\n";

            if ( $text_layer == "yes" ) {
                $output .= '<div class="layer-bg content-layer" data-layer-bg-pos="' . $layer_bg_pos . '">' . do_shortcode( $content ) . '</div>' . "\n";
            } else {
                if ( $img_url ) {
                    $output .= '<div class="layer-bg" data-layer-type="' . $layer_type . '" data-layer-bg-pos="' . $layer_bg_pos . '" data-layer-repeat="' . $layer_bg_repeat . '" style="background-image:url(' . $img_url[0] . ');"></div>' . "\n";
                }
            }

            $output .= '</div>' . "\n";

            return $output;
        }
    }

    SPBMap::map( 'spb_multilayer_parallax_layer', array(
            "name"     => __( "Multilayer Parallax Layer", "swift-framework-admin" ),
            "base"     => "spb_multilayer_parallax_layer",
            "class"    => "",
            "icon"     => "spb_multilayer_parallax_layer",
            "controls" => "delete_edit",
            "params"   => array(
                array(
                    "type"        => "textfield",
                    "holder"      => "div",
                    "heading"     => __( "Title", "swift-framework-admin" ),
                    "param_name"  => "layer_title",
                    "value"       => "",
                    "description" => __( "It's only showed in Swift Page Builder to identify the Layers. Leave it empty if not needed.", "swift-framework-admin" )
                ),
                array(
                    "type"        => "attach_image",
                    "heading"     => __( "Layer Image", "swift-framework-admin" ),
                    "param_name"  => "layer_image",
                    "value"       => "",
                    "description" => "Choose an image."
                ),
                array(
                    "type"        => "dropdown",
                    "heading"     => __( "Layer Type", "swift-framework-admin" ),
                    "param_name"  => "layer_type",
                    "value"       => array(
                        __( 'Original', "swift-framework-admin" ) => "original",
                        __( 'Cover', "swift-framework-admin" )    => "cover"
                    ),
                    "description" => __( "If you would like the image to cover the height/width of the asset, then please select the 'Cover' option - this is ideal for the background layers of the asset.", "swift-framework-admin" )
                ),
                array(
                    "type"        => "dropdown",
                    "heading"     => __( "Background Position", "swift-framework-admin" ),
                    "param_name"  => "layer_bg_pos",
                    "value"       => array(
                        __( 'Center', "swift-framework-admin" )        => "center center",
                        __( 'Center Left', "swift-framework-admin" )   => "left center",
                        __( 'Center Right', "swift-framework-admin" )  => "right center",
                        __( 'Top Left', "swift-framework-admin" )      => "left top",
                        __( 'Top Right', "swift-framework-admin" )     => "right top",
                        __( 'Top Center', "swift-framework-admin" )    => "center top",
                        __( 'Bottom Left', "swift-framework-admin" )   => "left bottom",
                        __( 'Bottom Right', "swift-framework-admin" )  => "right bottom",
                        __( 'Bottom Center', "swift-framework-admin" ) => "center bottom",
                    ),
                    "description" => __( "Select the alignment for the background image within the asset - this is ideal for placement of images that aren't cover layers.", "swift-framework-admin" )
                ),
                array(
                    "type"        => "dropdown",
                    "heading"     => __( "Background Repeat", "swift-framework-admin" ),
                    "param_name"  => "layer_bg_repeat",
                    "value"       => array(
                        __( 'No Repeat', "swift-framework-admin" )    => "no-repeat",
                        __( 'Repeat X + Y', "swift-framework-admin" ) => "repeat",
                        __( 'Repeat X', "swift-framework-admin" )     => "repeat-x",
                        __( 'Repeat Y', "swift-framework-admin" )     => "repeat-y",
                    ),
                    "description" => __( "Select if you would like the background image to repeat, and if so on which axis.", "swift-framework-admin" )
                ),
                array(
                    "type"        => "dropdown",
                    "heading"     => __( "Layer Depth", "swift-framework-admin" ),
                    "param_name"  => "layer_depth",
                    "value"       => array(
                        "0.00",
                        "0.10",
                        "0.20",
                        "0.30",
                        "0.40",
                        "0.50",
                        "0.60",
                        "0.70",
                        "0.80",
                        "0.90",
                        "1.00"
                    ),
                    "description" => __( "Choose the depth for the layer, where a depth of 0 will cause the layer to remain stationary, and a depth of 1 will cause the layer to move by the total effect of the calculated motion. Values inbetween 0 and 1 will cause the layer to move by an amount relative to the supplied ratio.", "swift-framework-admin" )
                ),
                array(
                    "type"       => "section",
                    "param_name" => "section_misc_options",
                    "heading"    => __( "Optional Text", "swift-framework-admin" ),
                    "value"      => ''
                ),
                array(
                    "type"        => "dropdown",
                    "heading"     => __( "Text Layer Enable", "swift-framework-admin" ),
                    "param_name"  => "text_layer",
                    "value"       => array(
                        __( 'No', "swift-framework-admin" )  => "no",
                        __( 'Yes', "swift-framework-admin" ) => "yes",
                    ),
                    "description" => __( "Select if you would like this layer to be a text layer.", "swift-framework-admin" )
                ),
                array(
                    "type"        => "textarea_html",
                    "holder"      => "div",
                    "class"       => "",
                    "heading"     => __( "Text Layer Content", "swift-framework-admin" ),
                    "param_name"  => "content",
                    "value"       => __( "", "swift-framework-admin" ),
                    "description" => __( "Enter your content if you have set this to be a text layer.", "swift-framework-admin" )
                ),
            )
        )
    );
