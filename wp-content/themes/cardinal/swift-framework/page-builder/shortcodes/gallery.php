<?php

    /*
    *
    *	Swift Page Builder - Gallery Shortcode
    *	------------------------------------------------
    *	Swift Framework
    * 	Copyright Swift Ideas 2014 - http://www.swiftideas.net
    *
    */

    class SwiftPageBuilderShortcode_spb_gallery extends SwiftPageBuilderShortcode {

        protected function content( $atts, $content = null ) {

            $title = $width = $el_class = $gallery_id = $output = $items = $hover_style = $masonry_gallery = $main_slider = $thumb_slider = $el_position = $gallery_images = $thumb_images = '';

            extract( shortcode_atts( array(
                'title'             => '',
                'gallery_id'        => '',
                'display_type'      => '',
                'columns'           => '',
                'fullwidth'         => '',
                'gutters'           => '',
                'show_thumbs'       => '',
                'show_captions'     => '',
                'autoplay'          => 'no',
                'hover_style'       => 'default',
                'enable_lightbox'   => 'yes',
                'slider_transition' => 'slide',
                'el_position'       => '',
                'width'             => '1/1',
                'el_class'          => ''
            ), $atts ) );


            /* SIDEBAR CONFIG
            ================================================== */
            $sidebar_config = sf_get_post_meta( get_the_ID(), 'sf_sidebar_config', true );

            $sidebars = '';
            if ( ( $sidebar_config == "left-sidebar" ) || ( $sidebar_config == "right-sidebar" ) ) {
                $sidebars = 'one-sidebar';
            } else if ( $sidebar_config == "both-sidebars" ) {
                $sidebars = 'both-sidebars';
            } else {
                $sidebars = 'no-sidebars';
            }


            /* GALLERY
            ================================================== */

            $gallery_args = array(
                'post_type'   => 'galleries',
                'post_status' => 'publish',
                'p'           => $gallery_id
            );

            $gallery_query = new WP_Query( $gallery_args );

            while ( $gallery_query->have_posts() ) : $gallery_query->the_post();


                if ( $display_type == "masonry" ) {

                    /* WRAP VARIABLE CONFIG
                    ================================================== */
                    $list_class = "";

                    if ( $fullwidth == "yes" ) {
                        $list_class .= ' portfolio-full-width';
                    }
                    if ( $gutters == "no" ) {
                        $list_class .= ' no-gutters';
                    } else {
                        $list_class .= ' gutters';
                    }

                    // Thumb Type
                    if ( $hover_style == "default" && function_exists( 'sf_get_thumb_type' ) ) {
                        $list_class = ' ' . sf_get_thumb_type();
                    } else {
                        $list_class .= ' thumbnail-' . $hover_style;
                    }

                    /* COLUMN VARIABLE CONFIG
                    ================================================== */
                    $item_class = "";

                    $image_size = "gallery-image";

                    if ( $columns == "1" ) {
                        $item_class = "col-sm-12 ";
                        $image_size = "full";
                    } else if ( $columns == "2" ) {
                        $item_class = "col-sm-6 ";
                    } else if ( $columns == "3" ) {
                        $item_class = "col-sm-4 ";
                    } else if ( $columns == "4" ) {
                        $item_class = "col-sm-3 ";
                    } else if ( $columns == "5" ) {
                        $item_class = "col-sm-sf-5 ";
                    }

                    $gallery_images = rwmb_meta( 'sf_gallery_images', 'type=image&size=' . $image_size );

                    $masonry_gallery .= '<div class="masonry-gallery ' . $list_class . '">' . "\n";

                    foreach ( $gallery_images as $image ) {

                        $masonry_gallery .= '<div class="gallery-image ' . $item_class . '">';
                        $masonry_gallery .= '<figure class="animated-overlay overlay-style">';

                        if ( $enable_lightbox == "yes" ) {
                            $masonry_gallery .= '<a href="' . $image['full_url'] . '" class="lightbox" data-rel="ilightbox[gallery-' . $gallery_id . ']" data-caption="' . $image['caption'] . '"></a>';
                        }

                        $masonry_gallery .= '<img src="' . $image['url'] . '" width="' . $image['width'] . '" height="' . $image['height'] . '" alt="' . $image['alt'] . '" /></a>';

                        if ( $show_captions == "yes" ) {
                            $masonry_gallery .= '<figcaption><div class="thumb-info">';
                            if ( $image['caption'] != "" ) {
                                $masonry_gallery .= '<h3>' . $image['caption'] . '</h3>';
                            } else {
                                $masonry_gallery .= '<h3>' . $image['title'] . '</h3>';
                            }
                        } else {
                            $masonry_gallery .= '<figcaption><div class="thumb-info thumb-info-alt">';
                            $masonry_gallery .= '<i class="ss-search"></i>';
                        }
                        $masonry_gallery .= '</figcaption>' . "\n";
                        $masonry_gallery .= '</figure>' . "\n";
                        $masonry_gallery .= '</div>' . "\n";
                    }

                    $masonry_gallery .= '</div>' . "\n";

                    $items .= $masonry_gallery;

                } else {

                    $gallery_images = rwmb_meta( 'sf_gallery_images', 'type=image&size=full-width-image-gallery' );
                    $thumb_images   = rwmb_meta( 'sf_gallery_images', 'type=image&size=thumb-square' );

                    $main_slider .= '<div class="flexslider gallery-slider" data-transition="' . $slider_transition . '" data-autoplay="' . $autoplay . '"><ul class="slides">' . "\n";

                    foreach ( $gallery_images as $image ) {

                        if ( $enable_lightbox == "yes" ) {
                            $main_slider .= "<li><a href='{$image['full_url']}' class='lightbox' data-rel='ilightbox[galleryid-" . $gallery_id . "]' data-caption='{$image['caption']}'><img src='{$image['url']}' width='{$image['width']}' height='{$image['height']}' alt='{$image['alt']}' /></a>";

                        } else {
                            $main_slider .= "<li><img src='{$image['url']}' width='{$image['width']}' height='{$image['height']}' alt='{$image['alt']}' />";
                        }

                        if ( $show_captions == "yes" && $image['caption'] != "" ) {
                            $main_slider .= '<p class="flex-caption">' . $image['caption'] . '</p>';
                        }
                        $main_slider .= "</li>" . "\n";
                    }

                    $main_slider .= '</ul></div>' . "\n";

                    if ( $show_thumbs == "yes" ) {

                        $thumb_slider .= '<div class="flexslider gallery-nav"><ul class="slides">' . "\n";

                        foreach ( $thumb_images as $image ) {
                            $thumb_slider .= "<li><img src='{$image['url']}' width='{$image['width']}' height='{$image['height']}' alt='{$image['alt']}' /></li>" . "\n";
                        }

                        $thumb_slider .= '</ul></div>' . "\n";

                    }

                    $items .= $main_slider;
                    $items .= $thumb_slider;
                }

            endwhile;

            wp_reset_query();
            wp_reset_postdata();


            /* PAGE BUILDER OUTPUT
            ================================================== */
            if ( $fullwidth == "yes" && $sidebars == "no-sidebars" ) {
                $fullwidth = true;
            } else {
                $fullwidth = false;
            }
            $width    = spb_translateColumnWidthToSpan( $width );
            $el_class = $this->getExtraClass( $el_class );

            $output .= "\n\t" . '<div class="spb_gallery_widget gallery-' . $display_type . ' spb_content_element ' . $width . $el_class . '">';
            $output .= "\n\t\t" . '<div class="spb-asset-content">';
            $output .= ( $title != '' ) ? "\n\t\t\t" . $this->spb_title( $title, '', $fullwidth ) : '';
            $output .= "\n\t\t" . $items;
            $output .= "\n\t\t" . '</div>';
            $output .= "\n\t" . '</div> ' . $this->endBlockComment( $width );

            if ( $fullwidth ) {
                $output = $this->startRow( $el_position, '', true ) . $output . $this->endRow( $el_position, '', true );
            } else {
                $output = $this->startRow( $el_position ) . $output . $this->endRow( $el_position );
            }

            global $sf_has_gallery;
            $sf_has_gallery = true;

            return $output;

        }
    }

    /* PARAMS
    ================================================== */
    $list_galleries = array();

    if ( is_admin() ) {
        $list_galleries = array(
            "type"        => "dropdown",
            "heading"     => __( "Gallery", "swift-framework-admin" ),
            "param_name"  => "gallery_id",
            "value"       => sf_list_galleries(),
            "description" => __( "Choose the gallery which you'd like to display. You can add galleries in the left admin area.", "swift-framework-admin" )
        );
    } else {
        $list_galleries = array(
            "type"        => "dropdown",
            "heading"     => __( "Gallery", "swift-framework-admin" ),
            "param_name"  => "gallery_id",
            "value"       => "",
            "description" => __( "Choose the gallery which you'd like to display. You can add galleries in the left admin area.", "swift-framework-admin" )
        );
    }

    $params = array(
        array(
            "type"        => "textfield",
            "heading"     => __( "Widget title", "swift-framework-admin" ),
            "param_name"  => "title",
            "value"       => "",
            "description" => __( "Heading text. Leave it empty if not needed.", "swift-framework-admin" )
        ),
        $list_galleries,
        array(
            "type"        => "dropdown",
            "heading"     => __( "Display Type", "swift-framework-admin" ),
            "param_name"  => "display_type",
            "value"       => array(
                __( "Slider", "swift-framework-admin" )  => "slider",
                __( "Masonry", "swift-framework-admin" ) => "masonry",
            ),
            "description" => __( "Choose the transition type for the slider.", "swift-framework-admin" )
        ),
        array(
            "type"        => "dropdown",
            "heading"     => __( "Columns (Masonry only)", "swift-framework-admin" ),
            "param_name"  => "columns",
            "value"       => array( "5", "4", "3", "2", "1" ),
            "description" => __( "How many portfolio columns to display. NOTE: Only for display types other than 'Multi Size Masonry'", "swift-framework-admin" )
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
            "type"        => "dropdown",
            "heading"     => __( "Gutters (Masonry only)", "swift-framework-admin" ),
            "param_name"  => "gutters",
            "value"       => array(
                __( 'Yes', "swift-framework-admin" ) => "yes",
                __( 'No', "swift-framework-admin" )  => "no"
            ),
            "description" => __( "Select if you'd like spacing between the gallery items, or not.", "swift-framework-admin" )
        ),
        array(
            "type"        => "dropdown",
            "heading"     => __( "Slider transition", "swift-framework-admin" ),
            "param_name"  => "slider_transition",
            "value"       => array(
                __( "Slide", "swift-framework-admin" ) => "slide",
                __( "Fade", "swift-framework-admin" )  => "fade"
            ),
            "description" => __( "Choose the transition type for the slider.", "swift-framework-admin" )
        ),
        array(
            "type"        => "dropdown",
            "heading"     => __( "Show thumbnail navigation", "swift-framework-admin" ),
            "param_name"  => "show_thumbs",
            "value"       => array(
                __( "Yes", "swift-framework-admin" ) => "yes",
                __( "No", "swift-framework-admin" )  => "no"
            ),
            "description" => __( "Show a thumbnail navigation display below the slider.", "swift-framework-admin" )
        ),
        array(
            "type"        => "dropdown",
            "heading"     => __( "Enable Autoplay", "swift-framework-admin" ),
            "param_name"  => "autoplay",
            "value"       => array(
                __( "Yes", "swift-framework-admin" ) => "yes",
                __( "No", "swift-framework-admin" )  => "no"
            ),
            "description" => __( "Choose whether to autoplay the slider or not.", "swift-framework-admin" )
        ),
        array(
            "type"        => "dropdown",
            "heading"     => __( "Show captions", "swift-framework-admin" ),
            "param_name"  => "show_captions",
            "value"       => array(
                __( "Yes", "swift-framework-admin" ) => "yes",
                __( "No", "swift-framework-admin" )  => "no"
            ),
            "description" => __( "Choose whether to show captions on the slider or not.", "swift-framework-admin" )
        ),
        array(
            "type"        => "dropdown",
            "heading"     => __( "Enable gallery lightbox", "swift-framework-admin" ),
            "param_name"  => "enable_lightbox",
            "value"       => array(
                __( "Yes", "swift-framework-admin" ) => "yes",
                __( "No", "swift-framework-admin" )  => "no"
            ),
            "description" => __( "Enable lightbox functionality from the gallery.", "swift-framework-admin" )
        ),
    );

    if ( sf_theme_opts_name() == "sf_joyn_options" ) {
        $params[] = array(
            "type"        => "dropdown",
            "heading"     => __( "Thumbnail Hover Style", "swift-framework-admin" ),
            "param_name"  => "hover_style",
            "value"       => array(
                __( 'Default', "swift-framework-admin" )     => "default",
                __( 'Standard', "swift-framework-admin" )    => "gallery-standard",
                __( 'Gallery Alt', "swift-framework-admin" ) => "gallery-alt-one",
                //__('Gallery Alt Two', "swift-framework-admin") => "gallery-alt-two",
            ),
            "description" => __( "Choose the thumbnail hover style for the asset. If set to 'Default', then this uses the thumbnail type set in the theme options.", "swift-framework-admin" )
        );
    }

    $params[] = array(
        "type"        => "textfield",
        "heading"     => __( "Extra class name", "swift-framework-admin" ),
        "param_name"  => "el_class",
        "value"       => "",
        "description" => __( "If you wish to style this particular content element differently, then use this field to add a class name and then refer to it in your css file.", "swift-framework-admin" )
    );


    /* SHORTCODE MAP
    ================================================== */
    SPBMap::map( 'spb_gallery', array(
        "name"   => __( "Gallery", "swift-framework-admin" ),
        "base"   => "spb_gallery",
        "class"  => "spb_gallery",
        "icon"   => "spb-icon-gallery",
        "params" => $params
    ) );