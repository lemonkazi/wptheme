<?php

    /*
    *
    *	Swift Page Builder - Media Shortcodes
    *	------------------------------------------------
    *	Swift Framework
    * 	Copyright Swift Ideas 2014 - http://www.swiftideas.net
    *
    */


    /* VIDEO ASSET
    ================================================== */

    class SwiftPageBuilderShortcode_spb_video extends SwiftPageBuilderShortcode {

        protected function content( $atts, $content = null ) {
            $title = $link = $size = $el_position = $full_width = $width = $el_class = '';
            extract( shortcode_atts( array(
                'title'       => '',
                'link'        => '',
                'size'        => '1280x720',
                'el_position' => '',
                'width'       => '1/1',
                'full_width'  => 'no',
                'el_class'    => ''
            ), $atts ) );
            $output = '';

            if ( $link == '' ) {
                return null;
            }
            $video_h  = '';
            $el_class = $this->getExtraClass( $el_class );
            $width    = spb_translateColumnWidthToSpan( $width );
            $size     = str_replace( array( 'px', ' ' ), array( '', '' ), $size );
            $size     = explode( "x", $size );
            $video_w  = $size[0];
            if ( count( $size ) > 1 ) {
                $video_h = $size[1];
            }

            $embed = sf_video_embed( $link, $video_w, $video_h );

            if ( $full_width == "yes" ) {
                $output .= "\n\t" . '<div class="spb_video_widget full-width spb_content_element ' . $width . $el_class . '">';
            } else {
                $output .= "\n\t" . '<div class="spb_video_widget spb_content_element ' . $width . $el_class . '">';
            }
            $output .= "\n\t\t" . '<div class="spb-asset-content">';
            $output .= ( $title != '' ) ? "\n\t\t\t" . $this->spb_title( $title, '' ) : '';
            $output .= $embed;
            $output .= "\n\t\t" . '</div>';
            $output .= "\n\t" . '</div> ' . $this->endBlockComment( $width );

            $output = $this->startRow( $el_position ) . $output . $this->endRow( $el_position );

            return $output;
        }
    }

    SPBMap::map( 'spb_video', array(
        "name"   => __( "Video Player", "swift-framework-admin" ),
        "base"   => "spb_video",
        "class"  => "",
        "icon"   => "spb-icon-film-youtube",
        "params" => array(
            array(
                "type"        => "textfield",
                "heading"     => __( "Widget title", "swift-framework-admin" ),
                "param_name"  => "title",
                "value"       => "",
                "description" => __( "Heading text. Leave it empty if not needed.", "swift-framework-admin" )
            ),
            array(
                "type"        => "textfield",
                "heading"     => __( "Video link", "swift-framework-admin" ),
                "param_name"  => "link",
                "value"       => "",
                "description" => __( 'Link to the video. More about supported formats at <a href="http://codex.wordpress.org/Embeds#Okay.2C_So_What_Sites_Can_I_Embed_From.3F" target="_blank">WordPress codex page</a>.', "swift-framework-admin" )
            ),
            array(
                "type"        => "textfield",
                "heading"     => __( "Video size", "swift-framework-admin" ),
                "param_name"  => "size",
                "value"       => "",
                "description" => __( 'Enter video size in pixels. Example: 200x100 (Width x Height).', "swift-framework-admin" )
            ),
            array(
                "type"        => "dropdown",
                "heading"     => __( "Full width", "swift-framework-admin" ),
                "param_name"  => "full_width",
                "value"       => array(
                    __( 'No', "swift-framework-admin" )  => "no",
                    __( 'Yes', "swift-framework-admin" ) => "yes"
                ),
                "description" => __( "Select this if you want the video to be the full width of the page container (leave the above size blank).", "swift-framework-admin" )
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


    /* SINGLE IMAGE ASSET
    ================================================== */

    class SwiftPageBuilderShortcode_spb_image extends SwiftPageBuilderShortcode {

        public function content( $atts, $content = null ) {

            $el_class = $width = $image_size = $animation = $frame = $lightbox = $hover_style = $image_link = $link_target = $caption = $fullwidth = $el_position = $el_class = $image = '';

            extract( shortcode_atts( array(
                'title'           => '',
                'width'           => '1/1',
                'image'           => $image,
                'image_size'      => '',
                'image_width'     => '',
                'intro_animation' => 'none',
                'animation_delay' => '200',
                'frame'           => '',
                'lightbox'        => '',
                'image_link'      => '',
                'link_target'     => '',
                'hover_style'     => 'default',
                'caption'         => '',
                'caption_pos'     => 'hover',
                'fullwidth'       => 'no',
                'el_position'     => '',
                'el_class'        => ''
            ), $atts ) );

            if ( $image_size == "" ) {
                $image_size = "large";
            }

            $output = '';
            if ( $fullwidth == "yes" && $width == "col-sm-12" ) {
                $fullwidth = true;
            } else {
                $fullwidth = false;
            }
            $img      = spb_getImageBySize( array(
                'attach_id'  => preg_replace( '/[^\d]/', '', $image ),
                'thumb_size' => $image_size
            ) );
            $img_url  = wp_get_attachment_image_src( $image, 'large' );
            $el_class = $this->getExtraClass( $el_class );
            $width    = spb_translateColumnWidthToSpan( $width );
            // $content =  !empty($image) ? '<img src="'..'" alt="">' : '';
            $content = '';
            if ( $image_width != "" ) {
                $image_width = 'style="width:' . $image_width . 'px;margin:0 auto;"';
            }

            // Thumb Type
            if ( $hover_style == "default" && function_exists( 'sf_get_thumb_type' ) ) {
                $el_class .= ' ' . sf_get_thumb_type();
            } else {
                $el_class .= ' thumbnail-' . $hover_style;
            }

            if ( $intro_animation != "none" ) {
                $output .= "\n\t" . '<div class="spb_content_element spb_image sf-animation ' . $frame . ' ' . $width . $el_class . '" data-animation="' . $intro_animation . '" data-delay="' . $animation_delay . '">';
            } else {
                $output .= "\n\t" . '<div class="spb_content_element spb_image ' . $frame . ' ' . $width . $el_class . '">';
            }
            $output .= "\n\t\t" . '<div class="spb-asset-content">';
            $output .= ( $title != '' ) ? "\n\t\t\t" . $this->spb_title( $title, '', $fullwidth ) : '';
            if ( $caption_pos == "hover" && $caption != "" ) {
                $output .= '<figure class="animated-overlay overlay-style caption-hover clearfix" ' . $image_width . '>';
            } else if ( $lightbox == "yes" || $image_link != "" ) {
                $output .= '<figure class="animated-overlay overlay-alt clearfix" ' . $image_width . '>';
            } else {
                $output .= '<figure class="clearfix" ' . $image_width . '>';
            }
            if ( $image_link != "" ) {
                $output .= "\n\t\t" . '<a class="img-link" href="' . $image_link . '" target="' . $link_target . '"></a>';
                $output .= $img['thumbnail'];
                $output .= '<div class="figcaption-wrap"></div>';
                $output .= '<figcaption>';
                if ( $caption_pos == "hover" ) {
                    $output .= '<div class="thumb-info">';
                    $output .= '<h4>' . $caption . '</h4>';
                } else {
                    $output .= '<div class="thumb-info thumb-info-alt">';
                    $output .= '<i class="ss-link"></i>';
                }
                $output .= '</div></figcaption>';
            } else if ( $lightbox == "yes" ) {
                $output .= $img['thumbnail'];
                if ( $img_url[0] != "" ) {
                    $output .= '<a class="lightbox" href="' . $img_url[0] . '" data-rel="ilightbox[' . $image . '-' . rand( 0, 1000 ) . ']" data-caption="' . $caption . '"></a>';
                }
                $output .= '<div class="figcaption-wrap"></div>';
                $output .= '<figcaption>';
                if ( $caption_pos == "hover" ) {
                    if ( $caption != "" ) {
                        $output .= '<div class="thumb-info">';
                        $output .= '<h4>' . $caption . '</h4>';
                    } else {
                        $output .= '<div class="thumb-info thumb-info-alt">';
                        $output .= '<i class="ss-view"></i>';
                    }
                } else {
                    $output .= '<div class="thumb-info thumb-info-alt">';
                    $output .= '<i class="ss-view"></i>';
                }
                $output .= '</div></figcaption>';
            } else {
                $output .= "\n\t\t" . $img['thumbnail'];
                $output .= '<div class="figcaption-wrap"></div>';
                if ( $caption_pos == "hover" && $caption != "" ) {
                    $output .= '<figcaption>';
                    $output .= '<div class="thumb-info">';
                    $output .= '<h4>' . $caption . '</h4>';
                    $output .= '</div></figcaption>';
                }
            }
            $output .= '</figure>';
            if ( $caption_pos == "below" && $caption != "" ) {
                $output .= '<div class="image-caption">';
                $output .= '<h4>' . $caption . '</h4>';
                $output .= '</div>';
            }
            $output .= "\n\t\t" . '</div>';
            $output .= "\n\t" . '</div> ' . $this->endBlockComment( $width );

            if ( $fullwidth ) {
                $output = $this->startRow( $el_position, '', true ) . $output . $this->endRow( $el_position, '', true );
            } else {
                $output = $this->startRow( $el_position ) . $output . $this->endRow( $el_position );
            }

            return $output;
        }

        public function singleParamHtmlHolder( $param, $value ) {
            $output = '';
            // Compatibility fixes
            $old_names = array(
                'yellow_message',
                'blue_message',
                'green_message',
                'button_green',
                'button_grey',
                'button_yellow',
                'button_blue',
                'button_red',
                'button_orange'
            );
            $new_names = array(
                'alert-block',
                'alert-info',
                'alert-success',
                'btn-success',
                'btn',
                'btn-info',
                'btn-primary',
                'btn-danger',
                'btn-warning'
            );
            $value     = str_ireplace( $old_names, $new_names, $value );

            $param_name = isset( $param['param_name'] ) ? $param['param_name'] : '';
            $type       = isset( $param['type'] ) ? $param['type'] : '';
            $class      = isset( $param['class'] ) ? $param['class'] : '';

            if ( isset( $param['holder'] ) == false || $param['holder'] == 'hidden' ) {
                $output .= '<input type="hidden" class="spb_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '" value="' . $value . '" />';
                if ( ( $param['type'] ) == 'attach_image' ) {
                    $img = spb_getImageBySize( array(
                        'attach_id'  => (int) preg_replace( '/[^\d]/', '', $value ),
                        'thumb_size' => 'thumbnail'
                    ) );
                    $output .= ( $img ? $img['thumbnail'] : '<img width="150" height="150" src="' . SwiftPageBuilder::getInstance()->assetURL( 'img/blank_f7.gif' ) . '" class="attachment-thumbnail" alt="" title="" />' ) . '<a href="#" class="column_edit_trigger' . ( $img && ! empty( $img['p_img_large'][0] ) ? ' image-exists' : '' ) . '"><i class="spb-icon-single-image"></i>' . __( 'No image yet! Click here to select it now.', 'swift-framework-admin' ) . '</a>';
                }
            } else {
                $output .= '<' . $param['holder'] . ' class="spb_param_value ' . $param_name . ' ' . $type . ' ' . $class . '" name="' . $param_name . '">' . $value . '</' . $param['holder'] . '>';
            }

            return $output;
        }
    }

    SPBMap::map( 'spb_image', array(
        "name"   => __( "Image", "swift-framework-admin" ),
        "base"   => "spb_image",
        "class"  => "spb_image_widget",
        "icon"   => "spb-icon-image",
        "params" => array(
            array(
                "type"        => "textfield",
                "heading"     => __( "Widget title", "swift-framework-admin" ),
                "param_name"  => "title",
                "value"       => "",
                "description" => __( "Heading text. Leave it empty if not needed.", "swift-framework-admin" )
            ),
            array(
                "type"        => "attach_image",
                "heading"     => __( "Image", "swift-framework-admin" ),
                "param_name"  => "image",
                "value"       => "",
                "description" => ""
            ),
            array(
                "type"        => "dropdown",
                "heading"     => __( "Image Size", "swift-framework-admin" ),
                "param_name"  => "image_size",
                "value"       => array(
                    __( "Full", "swift-framework-admin" )               => "full",
                    __( "Large", "swift-framework-admin" )              => "large",
                    __( "Medium", "swift-framework-admin" )             => "medium",
                    __( "Thumbnail", "swift-framework-admin" )          => "thumbnail",
                    __( "Small 4/3 Cropped", "swift-framework-admin" )  => "thumb-image",
                    __( "Medium 4/3 Cropped", "swift-framework-admin" ) => "thumb-image-twocol",
                    __( "Large 4/3 Cropped", "swift-framework-admin" )  => "thumb-image-onecol",
                    __( "Large 1/1 Cropped", "swift-framework-admin" )  => "large-square",
                ),
                "description" => __( "Select the source size for the image (NOTE: this doesn't affect it's size on the front-end, only the quality).", "swift-framework-admin" )
            ),
            array(
                "type"        => "textfield",
                "heading"     => __( "Image width", "swift-framework-admin" ),
                "param_name"  => "image_width",
                "value"       => "",
                "description" => __( "If you would like to override the width that the image is displayed at, then please provide the value here (no px). NOTE: The image can only be max 100% of it's container, this is generally for use if you would like to make the image smaller, and it will be centralised.", "swift-framework-admin" )
            ),
            array(
                "type"        => "dropdown",
                "heading"     => __( "Image Frame", "swift-framework-admin" ),
                "param_name"  => "frame",
                "value"       => array(
                    __( "No Frame", "swift-framework-admin" )     => "noframe",
                    __( "Border Frame", "swift-framework-admin" ) => "borderframe",
                    __( "Glow Frame", "swift-framework-admin" )   => "glowframe",
                    __( "Shadow Frame", "swift-framework-admin" ) => "shadowframe"
                ),
                "description" => __( "Select a frame for the image.", "swift-framework-admin" )
            ),
            array(
                "type"        => "dropdown",
                "heading"     => __( "Intro Animation", "swift-framework-admin" ),
                "param_name"  => "intro_animation",
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
                "description" => __( "Select an intro animation for the image that will show it when it appears within the viewport.", "swift-framework-admin" )
            ),
            array(
                "type"        => "textfield",
                "heading"     => __( "Animation Delay", "swift-framework-admin" ),
                "param_name"  => "animation_delay",
                "value"       => "200",
                "description" => __( "If you wish to add a delay to the animation, then you can set it here (default 200) (ms).", "swift-framework-admin" )
            ),
            array(
                "type"        => "dropdown",
                "heading"     => __( "Full width", "swift-framework-admin" ),
                "param_name"  => "fullwidth",
                "value"       => array(
                    __( "No", "swift-framework-admin" )  => "no",
                    __( "Yes", "swift-framework-admin" ) => "yes"
                ),
                "description" => __( "Select if you want the image to be the full width of the page. (Make sure the element width is 1/1 too).", "swift-framework-admin" )
            ),
            array(
                "type"        => "dropdown",
                "heading"     => __( "Enable lightbox link", "swift-framework-admin" ),
                "param_name"  => "lightbox",
                "value"       => array(
                    __( "Yes", "swift-framework-admin" ) => "yes",
                    __( "No", "swift-framework-admin" )  => "no"
                ),
                "description" => __( "Select if you want the image to open in a lightbox on click", "swift-framework-admin" )
            ),
            array(
                "type"        => "textfield",
                "heading"     => __( "Add link to image", "swift-framework-admin" ),
                "param_name"  => "image_link",
                "value"       => "",
                "description" => __( "If you would like the image to link to a URL, then enter it here. NOTE: this will override the lightbox functionality if you have enabled it.", "swift-framework-admin" )
            ),
            array(
                "type"        => "dropdown",
                "heading"     => __( "Link opens in new window?", "swift-framework-admin" ),
                "param_name"  => "link_target",
                "value"       => array(
                    __( "Self", "swift-framework-admin" )       => "_self",
                    __( "New Window", "swift-framework-admin" ) => "_blank"
                ),
                "description" => __( "Select if you want the link to open in a new window", "swift-framework-admin" )
            ),
            array(
                "type"        => "textfield",
                "heading"     => __( "Image Caption", "swift-framework-admin" ),
                "param_name"  => "caption",
                "value"       => "",
                "description" => __( "If you would like a caption to be shown below the image, add it here.", "swift-framework-admin" )
            ),
            array(
                "type"        => "dropdown",
                "heading"     => __( "Caption Position", "swift-framework-admin" ),
                "param_name"  => "caption_pos",
                "value"       => array(
                    __( "Hover", "swift-framework-admin" ) => "hover",
                    __( "Below", "swift-framework-admin" ) => "below"
                ),
                "description" => __( "Choose if you would like the caption to appear on the hover, or below the image. If you leave the caption field above blank then no caption will be shown.", "swift-framework-admin" )
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


    /* GOOGLE MAPS ASSET
    ================================================== */

    class SwiftPageBuilderShortcode_spb_gmaps extends SwiftPageBuilderShortcode {

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

            $title = $address = $size = $zoom = $color = $saturation = $map_center_latitude = $map_center_longitude = $pin_image = $type = $el_position = $width = $el_class = '';
            extract( shortcode_atts( array(
                'title'                => '',
                'size'                 => 200,
                'zoom'                 => 14,
                'map_center_latitude'  => '',
                'map_center_longitude' => '',
                'color'                => '',
                'saturation'           => '',
                'type'                 => 'm',
                'fullscreen'           => 'no',
                'el_position'          => '',
                'width'                => '1/1',
                'el_class'             => ''
            ), $atts ) );
            $output = '';

            $el_class = $this->getExtraClass( $el_class );
            $width    = spb_translateColumnWidthToSpan( $width );
            $size     = str_replace( array( 'px', ' ' ), array( '', '' ), $size );
            if ( $fullscreen == "yes" && $width == "col-sm-12" ) {
                $fullscreen = true;
            } else {
                $fullscreen = false;
            }


            if ( $fullscreen ) {
                $output .= "\n\t" . '<div class="spb_gmaps_widget fullscreen-map spb_content_element ' . $width . $el_class . '">';
            } else {
                $output .= "\n\t" . '<div class="spb_gmaps_widget spb_content_element ' . $width . $el_class . '">';
            }
            $output .= "\n\t\t" . '<div class="spb-asset-content">';
            $output .= ( $title != '' ) ? "\n\t\t\t" . $this->spb_title( $title, '', $fullscreen ) : '';
            $output .= '<div class="spb_map_wrapper"><div class="map-canvas" style="width:100%;height:' . $size . 'px;" data-center-lat="' . $map_center_latitude . '" data-center-lng="' . $map_center_longitude . '" data-zoom="' . $zoom . '" data-maptype="' . $type . '" data-mapcolor="' . $color . '" data-mapsaturation="' . $saturation . '"></div>';

            $output .= "\n\t\t\t" . do_shortcode( $content );
            $output .= "\n\t\t" . '</div>';
            $output .= "\n\t\t" . '</div>';
            $output .= "\n\t" . '</div> ' . $this->endBlockComment( $width );

            if ( $fullscreen ) {
                $output = $this->startRow( $el_position, '', true ) . $output . $this->endRow( $el_position, '', true );
            } else {
                $output = $this->startRow( $el_position ) . $output . $this->endRow( $el_position );
            }
            global $sf_include_maps;
            $sf_include_maps = true;

            return $output;
        }

    }

    SPBMap::map( 'spb_gmaps', array(
        "name"            => __( "Google Map", "swift-framework-admin" ),
        "base"            => "spb_gmaps",
        "controls"        => "full",
        "class"           => "spb_gmaps",
        "icon"            => "spb-icon-map-pin",
        //"wrapper_class" => "clearfix",
        "params"          => array(
            array(
                "type"        => "textfield",
                "heading"     => __( "Widget title", "swift-framework-admin" ),
                "param_name"  => "title",
                "value"       => "",
                "description" => __( "Heading text. Leave it empty if not needed.", "swift-framework-admin" )
            ),
            array(
                "type"        => "textfield",
                "heading"     => __( "Map Height", "swift-framework-admin" ),
                "param_name"  => "size",
                "value"       => "300",
                "description" => __( 'Enter map height in pixels. Example: 300.', "swift-framework-admin" )
            ),
            array(
                "type"        => "dropdown",
                "heading"     => __( "Map Type", "swift-framework-admin" ),
                "param_name"  => "type",
                "value"       => array(
                    __( "Map", "swift-framework-admin" )       => "ROADMAP",
                    __( "Satellite", "swift-framework-admin" ) => "SATELLITE",
                    __( "Hybrid", "swift-framework-admin" )    => "HYBRID",
                    __( "Terrain", "swift-framework-admin" )   => "TERRAIN"
                ),
                "description" => __( "Select map display type. NOTE, if you set a color below, then only the standard Map type will show.", "swift-framework-admin" )
            ),
            array(
                "type"        => "textfield",
                "heading"     => __( "Map Center Latitude Coordinate", "swift-framework-admin" ),
                "param_name"  => "map_center_latitude",
                "value"       => "",
                "description" => __( "Enter the Latitude coordinate of the center of the map.", "swift-framework-admin" )
            ),
            array(
                "type"        => "textfield",
                "heading"     => __( "Map Center Longitude Coordinate", "swift-framework-admin" ),
                "param_name"  => "map_center_longitude",
                "value"       => "",
                "description" => __( "Enter the Longitude coordinate of the center of the map.", "swift-framework-admin" )
            ),
            array(
                "type"       => "dropdown",
                "heading"    => __( "Map Zoom", "swift-framework-admin" ),
                "param_name" => "zoom",
                "value"      => array(
                    __( "14 - Default", "swift-framework-admin" ) => 14,
                    1,
                    2,
                    3,
                    4,
                    5,
                    6,
                    7,
                    8,
                    9,
                    10,
                    11,
                    12,
                    13,
                    15,
                    16,
                    17,
                    18,
                    19,
                    20
                )
            ),
            array(
                "type"        => "colorpicker",
                "heading"     => __( "Map Color", "swift-framework-admin" ),
                "param_name"  => "color",
                "value"       => "",
                "description" => __( 'If you would like, you can enter a hex color here to style the map by changing the hue.', "swift-framework-admin" )
            ),
            array(
                "type"        => "dropdown",
                "heading"     => __( "Map Saturation", "swift-framework-admin" ),
                "param_name"  => "saturation",
                "value"       => array(
                    __( "Color", "swift-framework-admin" )        => "color",
                    __( "Mono (Light)", "swift-framework-admin" ) => "mono-light",
                    __( "Mono (Dark)", "swift-framework-admin" )  => "mono-dark"
                ),
                "description" => __( "Set whether you would like the map to be in color or mono (black/white).", "swift-framework-admin" )
            ),
            array(
                "type"        => "dropdown",
                "heading"     => __( "Fullscreen Display", "swift-framework-admin" ),
                "param_name"  => "fullscreen",
                "value"       => array(
                    __( "No", "swift-framework-admin" )  => "no",
                    __( "Yes", "swift-framework-admin" ) => "yes"
                ),
                "description" => __( "If yes, the map will be displayed from screen edge to edge.", "swift-framework-admin" )
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
			<button class="add_tab">' . __( "Add New Pin", "swift-framework-admin" ) . '</button>
		</div>
	
		<div class="spb_tabs_holder">
			%content%
		</div>',
        'default_content' => '
					
				[spb_map_pin pin_title="' . __( "First Pin", "swift-framework-admin" ) . '" width="1/1"]' . __( 'This is a map pin. Click the edit button to change it.', 'swift-framework-admin' ) . '[/spb_map_pin]',
        "js_callback"     => array( "init" => "spbTabsInitCallBack" )

    ) );


    /* MAP PIN ASSET
    ================================================== */

    class SwiftPageBuilderShortcode_spb_map_pin extends SwiftPageBuilderShortcode {


        public function contentAdmin( $atts, $content ) {
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
                $iner .= $this->singleParamHtmlHolder( $param, $param_value );
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
            $elem   = str_ireplace( '%spb_element_content%', $iner, $elem );
            $output = $elem;
            $output = '<div class="row-fluid spb_column_container map_pin_wrapper not-column-inherit not-sortable">' . $output . '</div>';

            return $output;
        }


        protected function content( $atts, $content = null ) {

            $pin_title = $el_class = $address = $width = $el_position = $pin_image = $pin_link = $pin_button_text = $pin_latitude = $pin_longitude = $inline_style = '';

            extract( shortcode_atts( array(

                'pin_title'       => '',
                'icon'            => '',
                'el_class'        => '',
                'address'         => '',
                'pin_image'       => '',
                'pin_link'        => '',
                'pin_button_text' => '',
                'pin_latitude'    => '',
                'pin_longitude'   => '',
                'el_position'     => '',
                'width'           => '1/1',
                'pin_id'          => ''
            ), $atts ) );

            $output = '';


            $el_class = $this->getExtraClass( $el_class );
            $width    = spb_translateColumnWidthToSpan( $width );
            $el_class .= ' spb_map_pin';
            $img_url = wp_get_attachment_image_src( $pin_image, 'full' );

            $output = '<div class="pin_location" data-title="' . $pin_title . '" data-pinlink="' . $pin_link . '" data-pinimage="' . $img_url[0] . '"  data-address="' . $address . '"  data-content="' . strip_tags( $content ) . '" data-lat="' . $pin_latitude . '" data-lng="' . $pin_longitude . '" data-button-text="' . $pin_button_text . '" ></div>';

            return $output;
        }
    }

    SPBMap::map( 'spb_map_pin', array(
            "name"     => __( "Map Pin", "swift-framework-admin" ),
            "base"     => "spb_map_pin",
            "class"    => "",
            "icon"     => "spb-icon-map-pin",
            "controls" => "delete_edit",
            "params"   => array(
                array(
                    "type"        => "textfield",
                    "holder"      => "div",
                    "heading"     => __( "Title", "swift-framework-admin" ),
                    "param_name"  => "pin_title",
                    "value"       => "",
                    "description" => __( "Heading text. Leave it empty if not needed.", "swift-framework-admin" )
                ),
                array(
                    "type"        => "textfield",
                    "holder"      => "div",
                    "heading"     => __( "Address", "swift-framework-admin" ),
                    "param_name"  => "address",
                    "value"       => __( 'Click the edit button to change the map pin details.', "swift-framework-admin" ),
                    "description" => __( 'Enter the address that you would like to show on the map here, i.e. "Cupertino".', "swift-framework-admin" )
                ),
                array(
                    "type"        => "textfield",
                    "heading"     => __( "Latitude Coordinate", "swift-framework-admin" ),
                    "param_name"  => "pin_latitude",
                    "value"       => "",
                    "description" => __( "Enter the Latitude coordinate of the location marker.", "swift-framework-admin" )
                ),
                array(
                    "type"        => "textfield",
                    "heading"     => __( "Longitude Coordinate", "swift-framework-admin" ),
                    "param_name"  => "pin_longitude",
                    "value"       => "",
                    "description" => __( "Enter the Longitude coordinate of the location marker.", "swift-framework-admin" )
                ),
                array(
                    "type"        => "attach_image",
                    "heading"     => __( "Custom Map Pin", "swift-framework-admin" ),
                    "param_name"  => "pin_image",
                    "value"       => "",
                    "description" => "Choose an image to use as the custom pin for the address on the map. Upload your custom map pin, the image size must be 150px x 75px."
                ),
                array(
                    "type"        => "textfield",
                    "heading"     => __( "Pin Link", "swift-framework-admin" ),
                    "param_name"  => "pin_link",
                    "value"       => "",
                    "description" => __( "Enter the Link url of the location marker.", "swift-framework-admin" )
                ),
                array(
                    "type"        => "textfield",
                    "heading"     => __( "Pin Button Text", "swift-framework-admin" ),
                    "param_name"  => "pin_button_text",
                    "value"       => "",
                    "description" => __( "Enter the text of the Pin Button.", "swift-framework-admin" )
                ),
                array(
                    "type"        => "textarea_html",
                    "holder"      => "div",
                    "class"       => "hide-shortcode",
                    "heading"     => __( "Text", "swift-framework-admin" ),
                    "param_name"  => "content",
                    "value"       => __( "Click the edit button to change the map pin detail text.", "swift-framework-admin" ),
                    "description" => __( "Enter your content.", "swift-framework-admin" )
                )
            )
        )
    );

    /* DIRECTORY ITENS MAPS ASSET
    ================================================== */

    class SwiftPageBuilderShortcode_spb_directory extends SwiftPageBuilderShortcode {

        protected function content( $atts, $content = null ) {

            $title = $address = $img_file_url = $pin_image = $size = $zoom = $directory_category = $directory_map_filter = $directory_map_results = $directory_map_filter_pos = $color = $saturation = $type = $el_position = $width = $el_class = '';

            extract( shortcode_atts( array(
                'title'                    => '',
                'address'                  => '',
                'directory_category'       => '',
                'directory_map_filter'     => '',
                'directory_map_filter_pos' => '',
                'directory_map_results'    => '',
                'size'                     => 200,
                'zoom'                     => 14,
                'color'                    => '',
                'saturation'               => '',
                'type'                     => 'm',
                'pin_image'                => '',
                'fullscreen'               => 'no',
                'el_position'              => '',
                'width'                    => '1/1',
                'el_class'                 => ''
            ), $atts ) );
            $output = '';


            $el_class = $this->getExtraClass( $el_class );
            $width    = spb_translateColumnWidthToSpan( $width );
            $ajax_url = admin_url( 'admin-ajax.php' );
            $size     = str_replace( array( 'px', ' ' ), array( '', '' ), $size );
            if ( $fullscreen == "yes" && $width == "col-sm-12" ) {
                $fullscreen = true;
            } else {
                $fullscreen = false;
            }

            if ( $pin_image != "" ) {
                $img_url = wp_get_attachment_image_src( $pin_image, 'full' );
                if ( is_array( $img_url ) ) {
                    $img_file_url = $img_url[0];
                }
            }

            if ( $fullscreen ) {
                $output .= "\n\t" . '<div class="spb_gmaps_widget fullscreen-map spb_content_element ' . $width . $el_class . '">';
            } else {
                $output .= "\n\t" . '<div class="spb_gmaps_widget spb_content_element ' . $width . $el_class . '">';
            }

            if ( $directory_map_filter_pos == "above" ) {
                if ( $fullscreen == "yes" && $width == "col-sm-12" ) {
                    $output .= "\n\t" . '<div class="container"><div class="spb_directory_filter_above clearfix"></div></div>' . $this->endBlockComment( $width );
                } else {
                    $output .= "\n\t" . '<div class="spb_directory_filter_above clearfix"></div>' . $this->endBlockComment( $width );
                }
            }

            $output .= "\n\t\t" . '<div class="spb-asset-content">';
            $output .= ( $title != '' ) ? "\n\t\t\t" . $this->spb_title( $title, '', $fullscreen ) : '';
            $output .= '<div class="spb_map_wrapper"><div class="map-directory-canvas" style="width:100%;height:' . $size . 'px;" data-address="' . $address . '" data-zoom="' . $zoom . '" data-maptype="' . $type . '" data-ajaxurl="' . $ajax_url . '" data-mapcolor="' . $color . '" data-directory-category="' . $directory_category . '" data-directory-map-filter="' . $directory_map_filter . '" data-directory-map-filter-pos="' . $directory_map_filter_pos . '" data-directory-map-results="' . $directory_map_results . '" data-mapsaturation="' . $saturation . '" data-pinimage="' . $img_file_url . '"></div></div>';
            $output .= "\n\t\t" . '</div>';
            if ( $directory_map_filter_pos == "below" ) {
                if ( $fullscreen == "yes" && $width == "col-sm-12" ) {
                    $output .= "\n\t" . '<div class="container"><div class="spb_directory_filter_below clearfix"></div></div>' . $this->endBlockComment( $width );
                } else {
                    $output .= "\n\t" . '<div class="spb_directory_filter_below clearfix"></div>' . $this->endBlockComment( $width );
                }
            }
            $output .= "\n\t\t" . '<div class="directory-results container"></div>';
            $output .= "\n\t" . '</div>';

            if ( $fullscreen == "yes" && $width == "col-sm-12" ) {
                $output = $this->startRow( $el_position, '', true ) . $output . $this->endRow( $el_position, '', true );
            } else {
                $output = $this->startRow( $el_position ) . $output . $this->endRow( $el_position );
            }
            global $sf_include_maps;
            $sf_include_maps = true;

            return $output;
        }
    }


    SPBMap::map( 'spb_directory', array(
        "name"     => __( "Directory Map", "swift-framework-admin" ),
        "base"     => "spb_directory",
        "controls" => "full",
        "class"    => "spb_directory",
        "icon"     => "spb-icon-map-pin",
        "params"   => array(
            array(
                "type"        => "textfield",
                "heading"     => __( "Widget title", "swift-framework-admin" ),
                "param_name"  => "title",
                "value"       => "",
                "description" => __( "Heading text. Leave it empty if not needed.", "swift-framework-admin" )
            ),
            array(
                "type"        => "textfield",
                "heading"     => __( "Map Height", "swift-framework-admin" ),
                "param_name"  => "size",
                "value"       => "300",
                "description" => __( 'Enter map height in pixels. Example: 300.', "swift-framework-admin" )
            ),
            array(
                "type"        => "select-multiple",
                "heading"     => __( "Directory category", "swift-framework-admin" ),
                "param_name"  => "directory_category",
                "value"       => sf_get_category_list( 'directory-category' ),
                "description" => __( "Choose the category from which you'd like to show the directory items.", "swift-framework-admin" )
            ),
            array(
                "type"        => "dropdown",
                "heading"     => __( "Map Filter", "swift-framework-admin" ),
                "param_name"  => "directory_map_filter",
                "value"       => array(
                    __( "No", "swift-framework-admin" )  => "no",
                    __( "Yes", "swift-framework-admin" ) => "yes"
                ),
                "description" => __( "If yes, will be added a filter to refine the results.", "swift-framework-admin" )
            ),
            array(
                "type"        => "dropdown",
                "heading"     => __( "Map Filter Position", "swift-framework-admin" ),
                "param_name"  => "directory_map_filter_pos",
                "value"       => array(
                    __( "Above", "swift-framework-admin" ) => "above",
                    __( "Below", "swift-framework-admin" ) => "below"
                ),
                "description" => __( "Choose the position of the Map Filter(above or below).", "swift-framework-admin" )
            ),
            array(
                "type"        => "dropdown",
                "heading"     => __( "Display Results", "swift-framework-admin" ),
                "param_name"  => "directory_map_results",
                "value"       => array(
                    __( "Map", "swift-framework-admin" )        => "map",
                    __( "List", "swift-framework-admin" )       => "list",
                    __( "Map & List", "swift-framework-admin" ) => "maplist"
                ),
                "description" => __( "Choose how the results will be displayed.", "swift-framework-admin" )
            ),
            array(
                "type"        => "dropdown",
                "heading"     => __( "Map Type", "swift-framework-admin" ),
                "param_name"  => "type",
                "value"       => array(
                    __( "Map", "swift-framework-admin" )       => "ROADMAP",
                    __( "Satellite", "swift-framework-admin" ) => "SATELLITE",
                    __( "Hybrid", "swift-framework-admin" )    => "HYBRID",
                    __( "Terrain", "swift-framework-admin" )   => "TERRAIN"
                ),
                "description" => __( "Select map display type. NOTE, if you set a color below, then only the standard Map type will show.", "swift-framework-admin" )
            ),
            array(
                "type"       => "dropdown",
                "heading"    => __( "Map Zoom", "swift-framework-admin" ),
                "param_name" => "zoom",
                "value"      => array(
                    __( "14 - Default", "swift-framework-admin" ) => 14,
                    1,
                    2,
                    3,
                    4,
                    5,
                    6,
                    7,
                    8,
                    9,
                    10,
                    11,
                    12,
                    13,
                    15,
                    16,
                    17,
                    18,
                    19,
                    20
                )
            ),
            array(
                "type"        => "colorpicker",
                "heading"     => __( "Map Color", "swift-framework-admin" ),
                "param_name"  => "color",
                "value"       => "",
                "description" => __( 'If you would like, you can enter a hex color here to style the map by changing the hue.', "swift-framework-admin" )
            ),
            array(
                "type"        => "dropdown",
                "heading"     => __( "Map Saturation", "swift-framework-admin" ),
                "param_name"  => "saturation",
                "value"       => array(
                    __( "Color", "swift-framework-admin" )        => "color",
                    __( "Mono (Light)", "swift-framework-admin" ) => "mono-light",
                    __( "Mono (Dark)", "swift-framework-admin" )  => "mono-dark"
                ),
                "description" => __( "Set whether you would like the map to be in color or mono (black/white).", "swift-framework-admin" )
            ),
            array(
                "type"        => "dropdown",
                "heading"     => __( "Fullscreen Display", "swift-framework-admin" ),
                "param_name"  => "fullscreen",
                "value"       => array(
                    __( "No", "swift-framework-admin" )  => "no",
                    __( "Yes", "swift-framework-admin" ) => "yes"
                ),
                "description" => __( "If yes, the map will be displayed from screen edge to edge.", "swift-framework-admin" )
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