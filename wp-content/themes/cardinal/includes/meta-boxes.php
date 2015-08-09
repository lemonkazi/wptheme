<?php

    /*
	*
	*	Meta Box Functions
	*	------------------------------------------------
	*	Swift Framework
	* 	Copyright Swift Ideas 2014 - http://www.swiftideas.net
	*
	*/

    $prefix = 'sf_';

    global $meta_boxes;
    $sf_options = get_option( 'sf_cardinal_options' );

    $meta_boxes = array();

    $default_show_page_heading = $sf_options['default_show_page_heading'];
    $default_sidebar_config    = $sf_options['default_sidebar_config'];
    $default_left_sidebar      = $sf_options['default_left_sidebar'];
    $default_right_sidebar     = $sf_options['default_right_sidebar'];

    if ( $default_show_page_heading == "" ) {
        $default_show_page_heading = 1;
    }
    if ( $default_sidebar_config == "" ) {
        $default_sidebar_config = "no-sidebars";
    }
    if ( $default_left_sidebar == "" ) {
        $default_left_sidebar = "Sidebar-1";
    }
    if ( $default_right_sidebar == "" ) {
        $default_right_sidebar = "Sidebar-1";
    }

    /* PRODUCT SIDEBARS */
    $default_product_sidebar_config = $sf_options['default_product_sidebar_config'];
    $default_product_left_sidebar   = $sf_options['default_product_left_sidebar'];
    $default_product_right_sidebar  = $sf_options['default_product_right_sidebar'];

    if ( $default_product_sidebar_config == "" ) {
        $default_product_sidebar_config = "no-sidebars";
    }
    if ( $default_product_left_sidebar == "" ) {
        $default_product_left_sidebar = "Sidebar-1";
    }
    if ( $default_product_right_sidebar == "" ) {
        $default_product_right_sidebar = "Sidebar-1";
    }

    /* POST META */
    $default_post_sidebar_config = $sf_options['default_post_sidebar_config'];
    $default_post_left_sidebar   = $sf_options['default_post_left_sidebar'];
    $default_post_right_sidebar  = $sf_options['default_post_right_sidebar'];
    $default_include_author      = $sf_options['default_include_author'];
    $default_include_social      = $sf_options['default_include_social'];
    $default_include_related     = $sf_options['default_include_related'];
    $default_thumb_media         = $sf_options['default_thumb_media'];
    $default_detail_media        = $sf_options['default_detail_media'];
	$default_media_display		 = "standard";
	
	if ( isset( $sf_options['default_media_display'] ) ) {
		$default_media_display = $sf_options['default_media_display'];
	}
    if ( $default_post_sidebar_config == "" ) {
        $default_post_sidebar_config = "right-sidebar";
    }
    if ( $default_post_left_sidebar == "" ) {
        $default_post_left_sidebar = "Sidebar-1";
    }
    if ( $default_post_right_sidebar == "" ) {
        $default_post_right_sidebar = "Sidebar-1";
    }
    if ( $default_include_author == "" ) {
        $default_include_author = 1;
    }
    if ( $default_include_social == "" ) {
        $default_include_social = 1;
    }
    if ( $default_include_related == "" ) {
        $default_include_related = 1;
    }

    /* Thumbnail Meta Box
	================================================== */
    $meta_boxes[] = array(
        'id'      => 'thumbnail_meta_box',
        'title'   => __( 'Thumbnail', 'swift-framework-admin' ),
        'pages'   => array( 'post' ),
        'context' => 'normal',
        'fields'  => array(

            // THUMBNAIL TYPE
            array(
                'name'     => __( 'Thumbnail type', 'swift-framework-admin' ),
                'id'       => "{$prefix}thumbnail_type",
                'type'     => 'select',
                'options'  => array(
                    'none'     => 'None',
                    'image'    => 'Image',
                    'video'    => 'Video',
                    'slider'   => 'Slider',
                    'audio'    => 'Audio',
                    'sh-video' => 'Self Hosted Video'
                ),
                'multiple' => false,
                'std'      => $default_thumb_media,
                'desc'     => __( 'Choose what will be used for the item thumbnail.', 'swift-framework-admin' )
            ),
            // THUMBNAIL IMAGE
            array(
                'name'             => __( 'Thumbnail image', 'swift-framework-admin' ),
                'desc'             => __( 'The image that will be used as the thumbnail image.', 'swift-framework-admin' ),
                'id'               => "{$prefix}thumbnail_image",
                'type'             => 'image_advanced',
                'max_file_uploads' => 1
            ),
            // THUMBNAIL VIDEO
            array(
                'name'  => __( 'Thumbnail video URL', 'swift-framework-admin' ),
                'id'    => $prefix . 'thumbnail_video_url',
                'desc'  => __( 'Enter the video url for the thumbnail. Only links from Vimeo & YouTube are supported.', 'swift-framework-admin' ),
                'clone' => false,
                'type'  => 'text',
                'std'   => '',
            ),
            // THUMBNAIL AUDIO
            array(
                'name'  => __( 'Thumbnail audio URL', 'swift-framework-admin' ),
                'id'    => $prefix . 'thumbnail_audio_url',
                'desc'  => __( 'Enter the audio url for the thumbnail.', 'swift-framework-admin' ),
                'clone' => false,
                'type'  => 'text',
                'std'   => '',
            ),
            // THUMBNAIL SELF HOSTED VIDEO
            array(
                'name'  => __( 'Thumbnail Self Hosted Video MP4 URL', 'swift-framework-admin' ),
                'id'    => $prefix . 'thumbnail_video_mp4',
                'desc'  => __( 'Enter the video mp4 url for the thumbnail.', 'swift-framework-admin' ),
                'clone' => false,
                'type'  => 'text',
                'std'   => '',
            ),
            array(
                'name'  => __( 'Thumbnail Self Hosted Video WEBM URL', 'swift-framework-admin' ),
                'id'    => $prefix . 'thumbnail_video_webm',
                'desc'  => __( 'Enter the video webm url for the thumbnail.', 'swift-framework-admin' ),
                'clone' => false,
                'type'  => 'text',
                'std'   => '',
            ),
            array(
                'name'  => __( 'Thumbnail Self Hosted Video OGG URL', 'swift-framework-admin' ),
                'id'    => $prefix . 'thumbnail_video_ogg',
                'desc'  => __( 'Enter the video ogg url for the thumbnail.', 'swift-framework-admin' ),
                'clone' => false,
                'type'  => 'text',
                'std'   => '',
            ),
            // THUMBNAIL GALLERY
            array(
                'name'             => __( 'Thumbnail gallery', 'swift-framework-admin' ),
                'desc'             => __( 'The images that will be used in the thumbnail gallery.', 'swift-framework-admin' ),
                'id'               => "{$prefix}thumbnail_gallery",
                'type'             => 'image_advanced',
                'max_file_uploads' => 50,
            ),
            // THUMBNAIL LINK TYPE
            array(
                'name'     => __( 'Thumbnail link type', 'swift-framework-admin' ),
                'id'       => "{$prefix}thumbnail_link_type",
                'type'     => 'select',
                'options'  => array(
                    'link_to_post'   => __( 'Link to item', 'swift-framework-admin' ),
                    'link_to_url'    => __( 'Link to URL', 'swift-framework-admin' ),
                    'link_to_url_nw' => __( 'Link to URL (New Window)', 'swift-framework-admin' ),
                    'lightbox_thumb' => __( 'Lightbox to the thumbnail image', 'swift-framework-admin' ),
                    'lightbox_image' => __( 'Lightbox to image (select below)', 'swift-framework-admin' ),
                    'lightbox_video' => __( 'Fullscreen Video Overlay (input below)', 'swift-framework-admin' )
                ),
                'multiple' => false,
                'std'      => 'link-to-post',
                'desc'     => __( 'Choose what link will be used for the image(s) and title of the item.', 'swift-framework-admin' )
            ),
            // THUMBNAIL LINK URL
            array(
                'name'  => __( 'Thumbnail link URL', 'swift-framework-admin' ),
                'id'    => $prefix . 'thumbnail_link_url',
                'desc'  => __( 'Enter the url for the thumbnail link.', 'swift-framework-admin' ),
                'clone' => false,
                'type'  => 'text',
                'std'   => '',
            ),
            // THUMBNAIL LINK LIGHTBOX IMAGE
            array(
                'name' => __( 'Thumbnail link lightbox image', 'swift-framework-admin' ),
                'desc' => __( 'The image that will be used as the lightbox image.', 'swift-framework-admin' ),
                'id'   => "{$prefix}thumbnail_link_image",
                'type' => 'thickbox_image'
            ),
            // THUMBNAIL LINK LIGHTBOX VIDEO
            array(
                'name'  => __( 'Thumbnail link lightbox video URL', 'swift-framework-admin' ),
                'id'    => $prefix . 'thumbnail_link_video_url',
                'desc'  => __( 'Enter the video url for the thumbnail lightbox. Only links from Vimeo & YouTube are supported.', 'swift-framework-admin' ),
                'clone' => false,
                'type'  => 'text',
                'std'   => '',
            )
        )
    );

    /* Thumbnail Meta Box
	================================================== */
    $meta_boxes[] = array(
        'id'      => 'alt_thumbnail_meta_box',
        'title'   => __( 'Thumbnail', 'swift-framework-admin' ),
        'pages'   => array( 'download' ),
        'context' => 'normal',
        'fields'  => array(

            // THUMBNAIL TYPE
            array(
                'name'     => __( 'Thumbnail type', 'swift-framework-admin' ),
                'id'       => "{$prefix}thumbnail_type",
                'type'     => 'select',
                'options'  => array(
                    'none'     => 'None',
                    'image'    => 'Image',
                    'video'    => 'Video',
                    'slider'   => 'Slider',
                    'sh-video' => 'Self Hosted Video'
                ),
                'multiple' => false,
                'std'      => 'image',
                'desc'     => __( 'Choose what will be used for the item thumbnail.', 'swift-framework-admin' )
            ),
            // THUMBNAIL IMAGE
            array(
                'name'             => __( 'Thumbnail image', 'swift-framework-admin' ),
                'desc'             => __( 'The image that will be used as the thumbnail image.', 'swift-framework-admin' ),
                'id'               => "{$prefix}thumbnail_image",
                'type'             => 'image_advanced',
                'max_file_uploads' => 1
            ),
            // THUMBNAIL VIDEO
            array(
                'name'  => __( 'Thumbnail video URL', 'swift-framework-admin' ),
                'id'    => $prefix . 'thumbnail_video_url',
                'desc'  => __( 'Enter the video url for the thumbnail. Only links from Vimeo & YouTube are supported.', 'swift-framework-admin' ),
                'clone' => false,
                'type'  => 'text',
                'std'   => '',
            ),
            // THUMBNAIL SELF HOSTED VIDEO
            array(
                'name'  => __( 'Thumbnail Self Hosted Video MP4 URL', 'swift-framework-admin' ),
                'id'    => $prefix . 'thumbnail_video_mp4',
                'desc'  => __( 'Enter the video mp4 url for the thumbnail.', 'swift-framework-admin' ),
                'clone' => false,
                'type'  => 'text',
                'std'   => '',
            ),
            array(
                'name'  => __( 'Thumbnail Self Hosted Video WEBM URL', 'swift-framework-admin' ),
                'id'    => $prefix . 'thumbnail_video_webm',
                'desc'  => __( 'Enter the video webm url for the thumbnail.', 'swift-framework-admin' ),
                'clone' => false,
                'type'  => 'text',
                'std'   => '',
            ),
            array(
                'name'  => __( 'Thumbnail Self Hosted Video OGG URL', 'swift-framework-admin' ),
                'id'    => $prefix . 'thumbnail_video_ogg',
                'desc'  => __( 'Enter the video ogg url for the thumbnail.', 'swift-framework-admin' ),
                'clone' => false,
                'type'  => 'text',
                'std'   => '',
            ),
            // THUMBNAIL GALLERY
            array(
                'name'             => __( 'Thumbnail gallery', 'swift-framework-admin' ),
                'desc'             => __( 'The images that will be used in the thumbnail gallery.', 'swift-framework-admin' ),
                'id'               => "{$prefix}thumbnail_gallery",
                'type'             => 'image_advanced',
                'max_file_uploads' => 50,
            ),
            // THUMBNAIL LINK TYPE
            array(
                'name'     => __( 'Thumbnail link type', 'swift-framework-admin' ),
                'id'       => "{$prefix}thumbnail_link_type",
                'type'     => 'select',
                'options'  => array(
                    'link_to_post'   => __( 'Link to item', 'swift-framework-admin' ),
                    'link_to_url'    => __( 'Link to URL', 'swift-framework-admin' ),
                    'link_to_url_nw' => __( 'Link to URL (New Window)', 'swift-framework-admin' ),
                    'lightbox_thumb' => __( 'Lightbox to the thumbnail image', 'swift-framework-admin' ),
                    'lightbox_image' => __( 'Lightbox to image (select below)', 'swift-framework-admin' ),
                    'lightbox_video' => __( 'Fullscreen Video Overlay (input below)', 'swift-framework-admin' )
                ),
                'multiple' => false,
                'std'      => 'link-to-post',
                'desc'     => __( 'Choose what link will be used for the image(s) and title of the item.', 'swift-framework-admin' )
            ),
            // THUMBNAIL LINK URL
            array(
                'name'  => __( 'Thumbnail link URL', 'swift-framework-admin' ),
                'id'    => $prefix . 'thumbnail_link_url',
                'desc'  => __( 'Enter the url for the thumbnail link.', 'swift-framework-admin' ),
                'clone' => false,
                'type'  => 'text',
                'std'   => '',
            ),
            // THUMBNAIL LINK LIGHTBOX IMAGE
            array(
                'name' => __( 'Thumbnail link lightbox image', 'swift-framework-admin' ),
                'desc' => __( 'The image that will be used as the lightbox image.', 'swift-framework-admin' ),
                'id'   => "{$prefix}thumbnail_link_image",
                'type' => 'thickbox_image'
            ),
            // THUMBNAIL LINK LIGHTBOX VIDEO
            array(
                'name'  => __( 'Thumbnail link lightbox video URL', 'swift-framework-admin' ),
                'id'    => $prefix . 'thumbnail_link_video_url',
                'desc'  => __( 'Enter the video url for the thumbnail lightbox. Only links from Vimeo & YouTube are supported.', 'swift-framework-admin' ),
                'clone' => false,
                'type'  => 'text',
                'std'   => '',
            )
        )
    );


    /* Thumbnail Meta Box
	================================================== */
    $meta_boxes[] = array(
        'id'      => 'alt_thumbnail_meta_box',
        'title'   => __( 'Thumbnail', 'swift-framework-admin' ),
        'pages'   => array( 'portfolio' ),
        'context' => 'normal',
        'fields'  => array(

            // THUMBNAIL TYPE
            array(
                'name'     => __( 'Thumbnail type', 'swift-framework-admin' ),
                'id'       => "{$prefix}thumbnail_type",
                'type'     => 'select',
                'options'  => array(
                    'none'   => 'None',
                    'image'  => 'Image',
                    'video'  => 'Video',
                    'slider' => 'Slider'
                ),
                'multiple' => false,
                'std'      => 'image',
                'desc'     => __( 'Choose what will be used for the item thumbnail.', 'swift-framework-admin' )
            ),
            // THUMBNAIL IMAGE
            array(
                'name'             => __( 'Thumbnail image', 'swift-framework-admin' ),
                'desc'             => __( 'The image that will be used as the thumbnail image.', 'swift-framework-admin' ),
                'id'               => "{$prefix}thumbnail_image",
                'type'             => 'image_advanced',
                'max_file_uploads' => 1
            ),
            // THUMBNAIL VIDEO
            array(
                'name'  => __( 'Thumbnail video URL', 'swift-framework-admin' ),
                'id'    => $prefix . 'thumbnail_video_url',
                'desc'  => __( 'Enter the video url for the thumbnail. Only links from Vimeo & YouTube are supported.', 'swift-framework-admin' ),
                'clone' => false,
                'type'  => 'text',
                'std'   => '',
            ),
            // THUMBNAIL GALLERY
            array(
                'name'             => __( 'Thumbnail gallery', 'swift-framework-admin' ),
                'desc'             => __( 'The images that will be used in the thumbnail gallery.', 'swift-framework-admin' ),
                'id'               => "{$prefix}thumbnail_gallery",
                'type'             => 'image_advanced',
                'max_file_uploads' => 50,
            ),
            // THUMBNAIL LINK TYPE
            array(
                'name'     => __( 'Thumbnail link type', 'swift-framework-admin' ),
                'id'       => "{$prefix}thumbnail_link_type",
                'type'     => 'select',
                'options'  => array(
                    'link_to_post'   => __( 'Link to item', 'swift-framework-admin' ),
                    'link_to_url'    => __( 'Link to URL', 'swift-framework-admin' ),
                    'link_to_url_nw' => __( 'Link to URL (New Window)', 'swift-framework-admin' ),
                    'lightbox_thumb' => __( 'Lightbox to the thumbnail image', 'swift-framework-admin' ),
                    'lightbox_image' => __( 'Lightbox to image (select below)', 'swift-framework-admin' ),
                    'lightbox_video' => __( 'Fullscreen Video Overlay (input below)', 'swift-framework-admin' )
                ),
                'multiple' => false,
                'std'      => 'link-to-post',
                'desc'     => __( 'Choose what link will be used for the image(s) and title of the item.', 'swift-framework-admin' )
            ),
            // THUMBNAIL LINK URL
            array(
                'name'  => __( 'Thumbnail link URL', 'swift-framework-admin' ),
                'id'    => $prefix . 'thumbnail_link_url',
                'desc'  => __( 'Enter the url for the thumbnail link.', 'swift-framework-admin' ),
                'clone' => false,
                'type'  => 'text',
                'std'   => '',
            ),
            // THUMBNAIL LINK LIGHTBOX IMAGE
            array(
                'name' => __( 'Thumbnail link lightbox image', 'swift-framework-admin' ),
                'desc' => __( 'The image that will be used as the lightbox image.', 'swift-framework-admin' ),
                'id'   => "{$prefix}thumbnail_link_image",
                'type' => 'thickbox_image'
            ),
            // THUMBNAIL LINK LIGHTBOX VIDEO
            array(
                'name'  => __( 'Thumbnail link lightbox video URL', 'swift-framework-admin' ),
                'id'    => $prefix . 'thumbnail_link_video_url',
                'desc'  => __( 'Enter the video url for the thumbnail lightbox. Only links from Vimeo & YouTube are supported.', 'swift-framework-admin' ),
                'clone' => false,
                'type'  => 'text',
                'std'   => '',
            ),
            // PAGE TITLE BACKGROUND COLOR
            array(
                'name' => __( 'Thumbnail Background Hover Color', 'swift-framework-admin' ),
                'id'   => $prefix . 'port_hover_bg_color',
                'desc' => __( "Optionally set an alternate background colour for the thumbnail hover.", 'swift-framework-admin' ),
                'type' => 'color',
                'std'  => '',
            ),
        )
    );


    /* Portfolio Masonry Thumbnail Meta Box
	================================================== */
    $meta_boxes[] = array(
        'id'      => 'masonry_thumbnail_meta_box',
        'title'   => __( 'Masonry Thumbnail', 'swift-framework-admin' ),
        'pages'   => array( 'portfolio' ),
        'context' => 'normal',
        'fields'  => array(

            // THUMBNAIL TYPE
            array(
                'name'     => __( 'Masonry Thumbnail Size', 'swift-framework-admin' ),
                'id'       => "{$prefix}masonry_thumb_size",
                'type'     => 'select',
                'options'  => array(
                    'standard'  => 'Standard',
                    'wide'      => 'Wide',
                    'tall'      => 'Tall',
                    'wide-tall' => 'Wide & Tall'
                ),
                'multiple' => false,
                'std'      => 'standard',
                'desc'     => __( 'Choose the size that you would like the item to show as with the Multi-Size Masonry Portfolio setup. This will only affect that asset.', 'swift-framework-admin' )
            ),
        )
    );


    /* Detail Media Meta Box
	================================================== */
    $meta_boxes[] = array(
        'id'      => 'detail_media_meta_box',
        'title'   => __( 'Detail Media', 'swift-framework-admin' ),
        'pages'   => array( 'post', 'portfolio', 'download' ),
        'context' => 'normal',
        'fields'  => array(

            // USE THUMBNAIL CONTENT FOR THE MAIN DETAIL DISPLAY
            array(
                'name' => __( 'Use the thumbnail content', 'swift-framework-admin' ),    // File type: checkbox
                'id'   => "{$prefix}thumbnail_content_main_detail",
                'type' => 'checkbox',
                'desc' => __( 'Uncheck this box if you wish to select different media for the main detail display.', 'swift-framework-admin' ),
                'std'  => 0,
            ),
            // DETAIL TYPE
            array(
                'name'     => __( 'Detail type', 'swift-framework-admin' ),
                'id'       => "{$prefix}detail_type",
                'type'     => 'select',
                'options'  => array(
                    'none'            => __( 'None', 'swift-framework-admin' ),
                    'image'           => __( 'Image', 'swift-framework-admin' ),
                    'video'           => __( 'Video', 'swift-framework-admin' ),
                    'slider'          => __( 'Standard Slider', 'swift-framework-admin' ),
                    'gallery-stacked' => __( 'Stacked Gallery', 'swift-framework-admin' ),
                    'layer-slider'    => __( 'Revolution/Layer Slider', 'swift-framework-admin' ),
                    'audio'           => __( 'Audio', 'swift-framework-admin' ),
                    'sh-video'        => __( 'Self Hosted Video', 'swift-framework-admin' ),
                    'custom'          => __( 'Custom', 'swift-framework-admin' )
                ),
                'multiple' => false,
                'std'      => $default_detail_media,
                'desc'     => __( 'Choose what will be used for the item detail media.', 'swift-framework-admin' )
            ),
            // DETAIL IMAGE
            array(
                'name'             => __( 'Detail image', 'swift-framework-admin' ),
                'desc'             => __( 'The image that will be used as the detail image.', 'swift-framework-admin' ),
                'id'               => "{$prefix}detail_image",
                'type'             => 'image_advanced',
                'max_file_uploads' => 1
            ),
            // DETAIL VIDEO
            array(
                'name'  => __( 'Detail video URL', 'swift-framework-admin' ),
                'id'    => $prefix . 'detail_video_url',
                'desc'  => __( 'Enter the video url for the detail display. Only links from Vimeo & YouTube are supported.', 'swift-framework-admin' ),
                'clone' => false,
                'type'  => 'text',
                'std'   => '',
            ),
            // DETAIL AUDIO
            array(
                'name'  => __( 'Detail audio URL', 'swift-framework-admin' ),
                'id'    => $prefix . 'detail_audio_url',
                'desc'  => __( 'Enter the audio url for the detail display.', 'swift-framework-admin' ),
                'clone' => false,
                'type'  => 'text',
                'std'   => '',
            ),
            // DETAIL SELF HOSTED VIDEO
            array(
                'name'  => __( 'Detail Self Hosted Video MP4 URL', 'swift-framework-admin' ),
                'id'    => $prefix . 'detail_video_mp4',
                'desc'  => __( 'Enter the video mp4 url for the detail display.', 'swift-framework-admin' ),
                'clone' => false,
                'type'  => 'text',
                'std'   => '',
            ),
            array(
                'name'  => __( 'Detail Self Hosted Video WEBM URL', 'swift-framework-admin' ),
                'id'    => $prefix . 'detail_video_webm',
                'desc'  => __( 'Enter the video webm url for the detail display.', 'swift-framework-admin' ),
                'clone' => false,
                'type'  => 'text',
                'std'   => '',
            ),
            array(
                'name'  => __( 'Detail Self Hosted Video OGG URL', 'swift-framework-admin' ),
                'id'    => $prefix . 'detail_video_ogg',
                'desc'  => __( 'Enter the video ogg url for the detail display.', 'swift-framework-admin' ),
                'clone' => false,
                'type'  => 'text',
                'std'   => '',
            ),
            // DETAIL GALLERY
            array(
                'name'             => __( 'Post detail gallery', 'swift-framework-admin' ),
                'desc'             => __( 'The images that will be used in the detail gallery.', 'swift-framework-admin' ),
                'id'               => "{$prefix}detail_gallery",
                'type'             => 'image_advanced',
                'max_file_uploads' => 50,
            ),
            // DETAIL REV SLIDER
            array(
                'name'  => __( 'Revolution slider alias', 'swift-framework-admin' ),
                'id'    => $prefix . 'detail_rev_slider_alias',
                'desc'  => __( "Enter the revolution slider alias for the slider that you want to show.", 'swift-framework-admin' ),
                'clone' => false,
                'type'  => 'text',
                'std'   => '',
            ),
            // DETAIL LAYER SLIDER
            array(
                'name'  => __( 'Layer Slider alias', 'swift-framework-admin' ),
                'id'    => $prefix . 'detail_layer_slider_alias',
                'desc'  => __( "Enter the Layer Slider ID for the slider that you want to show.", 'swift-framework-admin' ),
                'clone' => false,
                'type'  => 'text',
                'std'   => '',
            ),
            // DETAIL CUSTOM
            array(
                'name' => __( 'Custom detail display', 'swift-framework-admin' ),
                'desc' => __( "If you'd like to provide your own detail media, please add it here", 'swift-framework-admin' ),
                'id'   => "{$prefix}custom_media",
                'type' => 'textarea',
                'std'  => "",
                'cols' => '40',
                'rows' => '8',
            ),
        )
    );

    /* Page Title Meta Box
	================================================== */
    $meta_boxes[] = array(
        'id'      => 'page_heading_meta_box',
        'title'   => __( 'Page Title', 'swift-framework-admin' ),
        'pages'   => array( 'post', 'page', 'portfolio', 'product', 'team', 'galleries' ),
        'context' => 'normal',
        'fields'  => array(

            // SHOW PAGE TITLE
            array(
                'name' => __( 'Show page title', 'swift-framework-admin' ),    // File type: checkbox
                'id'   => "{$prefix}page_title",
                'type' => 'checkbox',
                'desc' => __( 'Show the page title at the top of the page.', 'swift-framework-admin' ),
                'std'  => $default_show_page_heading,
            ),
            // PAGE TITLE BACKGROUND COLOR
            array(
                'name' => __( 'Page Title Background Color', 'swift-framework-admin' ),
                'id'   => $prefix . 'page_title_bg_color',
                'desc' => __( "Optionally set a background color for the page title.", 'swift-framework-admin' ),
                'type' => 'color',
                'std'  => '',
            ),
            // PAGE TITLE TEXT COLOR
            array(
                'name' => __( 'Page Title Text Color', 'swift-framework-admin' ),
                'id'   => $prefix . 'page_title_text_color',
                'desc' => __( "Optionally set a text color for the page title.", 'swift-framework-admin' ),
                'type' => 'color',
                'std'  => '',
            ),
            // PAGE TITLE STYLE
            array(
                'name'     => __( 'Page Title Style', 'swift-framework-admin' ),
                'id'       => "{$prefix}page_title_style",
                'type'     => 'select',
                'options'  => array(
                    'standard' => __( 'Standard', 'swift-framework-admin' ),
                    'fancy'    => __( 'Fancy', 'swift-framework-admin' )
                ),
                'multiple' => false,
                'std'      => 'standard',
                'desc'     => __( 'Choose the heading style.', 'swift-framework-admin' )
            ),
            // PAGE TITLE LINE 1
            array(
                'name' => __( 'Page Title', 'swift-framework-admin' ),
                'id'   => $prefix . 'page_title_one',
                'desc' => __( "Enter a custom page title if you'd like.", 'swift-framework-admin' ),
                'type' => 'text',
                'std'  => '',
            ),
            // PAGE TITLE LINE 2
            array(
                'name' => __( 'Page Subtitle', 'swift-framework-admin' ),
                'id'   => $prefix . 'page_subtitle',
                'desc' => __( "Enter a custom page title if you'd like (Fancy Page Title Style Only).", 'swift-framework-admin' ),
                'type' => 'text',
                'std'  => '',
            ),
            // FANCY HEADING IMAGE UPLOAD
            array(
                'name'             => __( 'Fancy Heading Background Image', 'swift-framework-admin' ),
                'desc'             => __( 'The image that will be used as the background for the fancy header.', 'swift-framework-admin' ),
                'id'               => "{$prefix}page_title_image",
                'type'             => 'image_advanced',
                'max_file_uploads' => 1
            ),
            // FANCY HEADING TEXT STYLE
            array(
                'name'     => __( 'Fancy Heading Text Style', 'swift-framework-admin' ),
                'id'       => "{$prefix}page_title_text_style",
                'type'     => 'select',
                'options'  => array(
                    'light' => __( 'Light', 'swift-framework-admin' ),
                    'dark'  => __( 'Dark', 'swift-framework-admin' )
                ),
                'multiple' => false,
                'std'      => 'light',
                'desc'     => __( 'If you uploaded an image in the option above, choose light/dark styling for the text heading text here.', 'swift-framework-admin' )
            ),
            // FANCY HEADING TEXT ALIGN
            array(
                'name'     => __( 'Fancy Heading Text Align', 'swift-framework-admin' ),
                'id'       => "{$prefix}page_title_text_align",
                'type'     => 'select',
                'options'  => array(
                    'left'   => __( 'Left', 'swift-framework-admin' ),
                    'center' => __( 'Center', 'swift-framework-admin' ),
                    'right'  => __( 'Right', 'swift-framework-admin' )
                ),
                'multiple' => false,
                'std'      => 'left',
                'desc'     => __( 'Choose the text alignment for the fancy header.', 'swift-framework-admin' )
            ),
            // FANCY HEADING HEIGHT
            array(
                'name'  => __( 'Fancy Heading Height', 'swift-framework-admin' ),
                'id'    => "{$prefix}page_title_height",
                'desc'  => __( "Set the height for the Fancy Heading (no px).", 'swift-framework-admin' ),
                'clone' => false,
                'type'  => 'text',
                'std'   => '400',
            ),
            // REMOVE BREADCRUMBS
            array(
                'name' => __( 'Remove breadcrumbs', 'swift-framework-admin' ),
                'id'   => "{$prefix}no_breadcrumbs",
                'type' => 'checkbox',
                'desc' => __( 'Remove the breadcrumbs from under the page title on this page.', 'swift-framework-admin' ),
                'std'  => 0,
            ),
        )
    );

    /* Portfolio Meta Box
	================================================== */
    $meta_boxes[] = array(
        'id'      => 'portfolio_meta_box',
        'title'   => __( 'Portfolio Meta', 'swift-framework-admin' ),
        'pages'   => array( 'portfolio' ),
        'context' => 'normal',
        'fields'  => array(

            // MISC OPTIONS SECTION
            array(
                'name'  => '',
                'title' => __( 'One Page Options', 'swift-framework-admin' ),
                'id'    => "{$prefix}heading_onepage",
                'type'  => 'section'
            ),
            // REMOVE PROMO BAR
            array(
                'name' => __( 'Enable One Page Navigation', 'swift-framework-admin' ),    // File type: checkbox
                'id'   => "{$prefix}enable_one_page_nav",
                'type' => 'checkbox',
                'desc' => __( 'Enable the one page nav which appears on the right of the page.', 'swift-framework-admin' ),
                'std'  => 0,
            ),
            // ITEM DETAILS OPTIONS SECTION
            array(
                'name'  => '',
                'title' => __( 'Portfolio Item Details', 'swift-framework-admin' ),
                'id'    => "{$prefix}heading_item_details",
                'type'  => 'section'
            ),
            // Client Text
            array(
                'name'  => __( 'Client', 'swift-framework-admin' ),
                'id'    => $prefix . 'portfolio_client',
                'desc'  => __( "Enter a client for use within the portfolio item index (optional).", 'swift-framework-admin' ),
                'clone' => false,
                'type'  => 'text',
                'std'   => '',
            ),
            // Sub Text
            array(
                'name'  => __( 'Subtitle', 'swift-framework-admin' ),
                'id'    => $prefix . 'portfolio_subtitle',
                'desc'  => __( "Enter a subtitle for use within the portfolio item index (optional).", 'swift-framework-admin' ),
                'clone' => false,
                'type'  => 'text',
                'std'   => '',
            ),
            // External Link
            array(
                'name'  => __( 'External Link', 'swift-framework-admin' ),
                'id'    => $prefix . 'portfolio_external_link',
                'desc'  => __( "Enter an external link for the item  (optional) (NOTE: INCLUDE HTTP://).", 'swift-framework-admin' ),
                'clone' => false,
                'type'  => 'text',
                'std'   => '',
            ),
            // CUSTOM EXCERPT SECTION
            array(
                'name'  => '',
                'title' => __( 'Custom Excerpt', 'swift-framework-admin' ),
                'id'    => "{$prefix}heading_custom_excerpt",
                'type'  => 'section'
            ),
            // CUSTOM EXCERPT
            array(
                'name' => __( 'Custom excerpt', 'swift-framework-admin' ),
                'desc' => __( "You can optionally write a custom excerpt here to display instead of the excerpt that is automatically generated. If you use the page builder, then you'll want to add content to this box.", 'swift-framework-admin' ),
                'id'   => "{$prefix}custom_excerpt",
                'type' => 'textarea',
                'std'  => "",
                'cols' => '40',
                'rows' => '8',
            ),
            // MAIN DETAIL SECTION
            array(
                'name'  => '',
                'title' => __( 'Main Detail Options', 'swift-framework-admin' ),
                'id'    => "{$prefix}heading_detail",
                'type'  => 'section'
            ),
            // FULL WIDTH MEDIA DISPLAY
            array(
                'name'     => __( 'Media Display', 'swift-framework-admin' ),
                'id'       => "{$prefix}fw_media_display",
                'type'     => 'select',
                'options'  => array(
                    'fw-media' => __( 'Full Width Media', 'swift-framework-admin' ),
                    'split'    => __( 'Split Media / Description', 'swift-framework-admin' ),
                    'standard' => __( 'Standard', 'swift-framework-admin' ),
                ),
                'multiple' => false,
                'std'      => 'standard',
                'desc'     => __( 'Choose how you would like to display your selected media - full width (edge to edge), split, or standard (media with content below).', 'swift-framework-admin' )
            ),
            // MEDIA IMAGE HEIGHT
            array(
                'name'  => __( 'Media Image Height', 'swift-framework-admin' ),
                'id'    => $prefix . 'media_height',
                'desc'  => __( "If you are using the image detail type, and would like to set a height for the image - then please do so here (no px).", 'swift-framework-admin' ),
                'clone' => false,
                'type'  => 'text',
                'std'   => '',
            ),
            array(
                'name' => __( 'Item Sidebar Content', 'swift-framework-admin' ),
                'desc' => __( "You can optionally add some content here to display in the details column, including shortcodes etc. Only visible on Standard and Full Width Media display types.", 'swift-framework-admin' ),
                'id'   => "{$prefix}item_sidebar_content",
                'type' => 'wysiwyg',
                'std'  => "",
                'cols' => '40',
                'rows' => '8',
            ),
            // HIDE DETAILS BAR
            array(
                'name' => __( 'Hide item details bar', 'swift-framework-admin' ),
                'id'   => "{$prefix}hide_details",
                'type' => 'checkbox',
                'desc' => __( 'Check this box to hide the item details on the detail page.', 'swift-framework-admin' ),
                'std'  => 0,
            ),
            // INCLUDE SOCIAL SHARING
            array(
                'name' => __( 'Include social sharing', 'swift-framework-admin' ),
                'id'   => "{$prefix}social_sharing",
                'type' => 'checkbox',
                'desc' => __( 'Check this box to show social sharing icons on the detail page.', 'swift-framework-admin' ),
                'std'  => 1,
            ),
            // MISC
            array(
                'name'  => '',
                'title' => __( 'Misc. Options', 'swift-framework-admin' ),
                'id'    => "{$prefix}heading_detail",
                'type'  => 'section'
            ),
            // Extra Page Class
            array(
                'name'  => __( 'Extra page class', 'swift-framework-admin' ),
                'id'    => $prefix . 'extra_page_class',
                'desc'  => __( "If you wish to add extra classes to the body class of the page (for custom css use), then please add the class(es) here.", 'swift-framework-admin' ),
                'clone' => false,
                'type'  => 'text',
                'std'   => '',
            ),
            // REMOVE PROMO BAR
            array(
                'name' => __( 'Remove promo bar', 'swift-framework-admin' ),   // File type: checkbox
                'id'   => "{$prefix}remove_promo_bar",
                'type' => 'checkbox',
                'desc' => __( 'Remove the promo bar at the bottom of the page.', 'swift-framework-admin' ),
                'std'  => 0,
            )
        )
    );


    /* Page Background Meta Box
	================================================== */
    $meta_boxes[] = array(
        'id'      => 'page_background_meta_box',
        'title'   => __( 'Page Background', 'swift-framework-admin' ),
        'pages'   => array( 'post', 'portfolio', 'product', 'page' ),
        'context' => 'normal',
        'fields'  => array(

            // BACKGROUND IMAGE
            array(
                'name'             => __( 'Background Image', 'swift-framework-admin' ),
                'desc'             => __( 'The image that will be used as the OUTER page background image.', 'swift-framework-admin' ),
                'id'               => "{$prefix}background_image",
                'type'             => 'image_advanced',
                'max_file_uploads' => 1
            ),
            // BACKGROUND SIZE
            array(
                'name'     => __( 'Background Image Size', 'swift-framework-admin' ),
                'desc'     => __( 'For fullscreen images, choose Cover. For repeating patterns, choose Auto.', 'swift-framework-admin' ),
                'id'       => "{$prefix}background_image_size",
                'type'     => 'select',
                'options'  => array(
                    'cover' => 'Cover',
                    'auto'  => 'Auto'
                ),
                'multiple' => false,
                'std'      => 'cover',
            ),
            // INNER BACKGROUND IMAGE
            array(
                'name'             => __( 'Inner Background Image', 'swift-framework-admin' ),
                'desc'             => __( 'The image that will be used as the INNER page background image.', 'swift-framework-admin' ),
                'id'               => "{$prefix}inner_background_image",
                'type'             => 'image_advanced',
                'max_file_uploads' => 1
            ),
            // BACKGROUND SIZE
            array(
                'name'     => __( 'Inner Background Image Size', 'swift-framework-admin' ),
                'desc'     => __( 'For fullscreen images, choose Cover. For repeating patterns, choose Auto.', 'swift-framework-admin' ),
                'id'       => "{$prefix}inner_background_image_size",
                'type'     => 'select',
                'options'  => array(
                    'cover' => 'Cover',
                    'auto'  => 'Auto'
                ),
                'multiple' => false,
                'std'      => 'auto',
            ),
            // INNER BACKGROUND COLOR
            array(
                'name' => __( 'Inner Background Color', 'swift-framework-admin' ),
                'id'   => $prefix . 'inner_background_color',
                'desc' => __( "Optionally set a background color for the inner page background.", 'swift-framework-admin' ),
                'type' => 'color',
                'std'  => '',
            ),

        )
    );

    /* Download Options Meta Box
	================================================== */
    $meta_boxes[] = array(
        'id'       => 'download_meta_box',
        'title'    => __( 'Download Options', 'swift-framework-admin' ),
        'pages'    => array( 'post' ),
        'context'  => 'normal',
        'priority' => 'low',
        'fields'   => array(
            // DOWNLOAD BUTTON
            array(
                'name' => __( 'Show Download Button', 'swift-framework-admin' ),   // File type: checkbox
                'id'   => "{$prefix}download_button",
                'type' => 'checkbox',
                'desc' => __( 'Enable a download button on the detail and index for the post.', 'swift-framework-admin' ),
                'std'  => 0,
            ),
            // DOWNLOAD FILE
            array(
                'name'             => __( 'Download File', 'swift-framework-admin' ),
                'desc'             => __( 'The file that the download button will link to.', 'swift-framework-admin' ),
                'id'               => "{$prefix}download_file",
                'type'             => 'file_advanced',
                'max_file_uploads' => 1
            ),
            // DOWNLOAD SHORTCODE
            array(
                'name' => __( 'Download shortcode', 'swift-framework-admin' ),
                'desc' => __( "Alternatively, you can provide a shortcode here for your download, for example from the Easy Digital Downloads plugin.", 'swift-framework-admin' ),
                'id'   => "{$prefix}download_shortcode",
                'type' => 'textarea',
                'std'  => "",
                'cols' => '40',
                'rows' => '8',
            ),
        )
    );


    /* Post Meta Box
	================================================== */
    $meta_boxes[] = array(
        'id'      => 'post_meta_box',
        'title'   => __( 'Post Meta', 'swift-framework-admin' ),
        'pages'   => array( 'post' ),
        'context' => 'normal',
        'fields'  => array(

            // CUSTOM EXCERPT SECTION
            array(
                'name'  => '',
                'title' => __( 'Custom Excerpt', 'swift-framework-admin' ),
                'id'    => "{$prefix}heading_custom_excerpt",
                'type'  => 'section'
            ),
            // CUSTOM EXCERPT
            array(
                'name' => __( 'Custom excerpt', 'swift-framework-admin' ),
                'desc' => __( "You can optionally write a custom excerpt here to display instead of the excerpt that is automatically generated. If you use the page builder, then you'll want to add content to this box.", 'swift-framework-admin' ),
                'id'   => "{$prefix}custom_excerpt",
                'type' => 'textarea',
                'std'  => "",
                'cols' => '40',
                'rows' => '8',
            ),
            // MAIN DETAIL SECTION
            array(
                'name'  => '',
                'title' => __( 'Main Detail Options', 'swift-framework-admin' ),
                'id'    => "{$prefix}heading_detail",
                'type'  => 'section'
            ),
            // PAGE HEADER TYPE
            array(
                'name'     => __( 'Post Header Type', 'swift-framework-admin' ),
                'id'       => "{$prefix}page_header_type",
                'type'     => 'select',
                'options'  => array(
                    'standard'    => __( 'Standard', 'swift-framework-admin' ),
                    'naked-light' => __( 'Naked (Light)', 'swift-framework-admin' ),
                    'naked-dark'  => __( 'Naked (Dark)', 'swift-framework-admin' ),
                ),
                'multiple' => false,
                'std'      => 'standard',
                'desc'     => __( 'Choose the type of header that is shown on this post. NOTE: The naked options are only possible when you have the fancy heading enabled, or the media display below set to "Full Width with Title Overlay".', 'swift-framework-admin' ),
            ),
            // FULL WIDTH MEDIA
            array(
                'name'     => __( 'Media Display', 'swift-framework-admin' ),
                'id'       => "{$prefix}fw_media_display",
                'type'     => 'select',
                'options'  => array(
                    'fw-media-title' => __( 'Full Width with Title Overlay', 'swift-framework-admin' ),
                    'fw-media'       => __( 'Full Width', 'swift-framework-admin' ),
                    'standard-above' => __( 'Standard (Above content)', 'swift-framework-admin' ),
                    'standard'       => __( 'Standard', 'swift-framework-admin' ),
                ),
                'multiple' => false,
                'std'      => $default_media_display,
                'desc'     => __( 'Choose how you would like to display your selected media - full width (edge to edge) with or without the title overlay, or standard. If you choose the title overlay option, it is recommended that you hide the page title in the page title meta options.', 'swift-framework-admin' )
            ),
            // MEDIA IMAGE HEIGHT
            array(
                'name'  => __( 'Title Overlay Min Height', 'swift-framework-admin' ),
                'id'    => $prefix . 'media_height',
                'desc'  => __( "If you are using the 'Full Width with Title Overlay' media display type, you can set a min-height for it here (no px).", 'swift-framework-admin' ),
                'clone' => false,
                'type'  => 'text',
                'std'   => '500',
            ),
            // TITLE OVERLAY TEXT COLOR
            array(
                'name' => __( 'Title Overlay Text Color', 'swift-framework-admin' ),
                'id'   => $prefix . 'title_overlay_text_color',
                'desc' => __( "Optionally set a text color for the title overlay text.", 'swift-framework-admin' ),
                'type' => 'color',
                'std'  => '',
            ),
            // CONTENT FORMATTING
            array(
                'name' => __( 'Extra Paragraph Spacing', 'swift-framework-admin' ),
                'id'   => "{$prefix}extra_paragraph_spacing",
                'type' => 'checkbox',
                'desc' => __( 'Check this box to enable extra spacing around paragraph elements within the post content.', 'swift-framework-admin' ),
                'std'  => 0,
            ),
            // INCLUDE AUTHOR INFO
            array(
                'name' => __( 'Include author info', 'swift-framework-admin' ),
                'id'   => "{$prefix}author_info",
                'type' => 'checkbox',
                'desc' => __( 'Check this box to show the author info box on the detail page.', 'swift-framework-admin' ),
                'std'  => $default_include_author,
            ),
            // INCLUDE SOCIAL SHARING
            array(
                'name' => __( 'Include social sharing', 'swift-framework-admin' ),
                'id'   => "{$prefix}social_sharing",
                'type' => 'checkbox',
                'desc' => __( 'Check this box to show social sharing icons on the detail page.', 'swift-framework-admin' ),
                'std'  => $default_include_social,
            ),
            // INCLUDE AUTHOR INFO
            array(
                'name' => __( 'Remove article pagination', 'swift-framework-admin' ),
                'id'   => "{$prefix}remove_next_prev",
                'type' => 'checkbox',
                'desc' => __( 'Check this box to remove the next/previous article pagination on the detail page.', 'swift-framework-admin' ),
                'std'  => $default_include_author,
            ),
            // INCLUDE RELATED ARTICLES
            array(
                'name' => __( 'Include related articles', 'swift-framework-admin' ),
                'id'   => "{$prefix}related_articles",
                'type' => 'checkbox',
                'desc' => __( 'Check this box to show related articles on the detail page.', 'swift-framework-admin' ),
                'std'  => $default_include_related,
            ),
            // MISC
            array(
                'name'  => '',
                'title' => __( 'Misc. Options', 'swift-framework-admin' ),
                'id'    => "{$prefix}heading_detail",
                'type'  => 'section'
            ),
            // Extra Page Class
            array(
                'name'  => __( 'Extra page class', 'swift-framework-admin' ),
                'id'    => $prefix . 'extra_page_class',
                'desc'  => __( "If you wish to add extra classes to the body class of the page (for custom css use), then please add the class(es) here.", 'swift-framework-admin' ),
                'clone' => false,
                'type'  => 'text',
                'std'   => '',
            ),
            // REMOVE PROMO BAR
            array(
                'name' => __( 'Remove promo bar', 'swift-framework-admin' ),   // File type: checkbox
                'id'   => "{$prefix}remove_promo_bar",
                'type' => 'checkbox',
                'desc' => __( 'Remove the promo bar at the bottom of the page.', 'swift-framework-admin' ),
                'std'  => 0,
            ),
            // REMOVE TOP SPACING
            array(
                'name' => __( 'Remove top spacing', 'swift-framework-admin' ),    // File type: checkbox
                'id'   => "{$prefix}no_top_spacing",
                'type' => 'checkbox',
                'desc' => __( 'Remove the spacing at the top of the page.', 'swift-framework-admin' ),
                'std'  => 0,
            ),
            // REMOVE BOTTOM SPACING
            array(
                'name' => __( 'Remove bottom spacing', 'swift-framework-admin' ),    // File type: checkbox
                'id'   => "{$prefix}no_bottom_spacing",
                'type' => 'checkbox',
                'desc' => __( 'Remove the spacing at the bottom of the page.', 'swift-framework-admin' ),
                'std'  => 0,
            )

        )
    );


    /* Product Meta Box
	================================================== */
    $meta_boxes[] = array(
        'id'      => 'product_meta_box',
        'title'   => __( 'Product Meta', 'swift-framework-admin' ),
        'pages'   => array( 'product' ),
        'context' => 'normal',
        'fields'  => array(

            // PRODUCT DESCRIPTION SECTION
            array(
                'name'  => '',
                'title' => __( 'Product Description', 'swift-framework-admin' ),
                'id'    => "{$prefix}heading_custom_excerpt",
                'type'  => 'section'
            ),
            // PRODUCT DESCRIPTION
            array(
                'name' => __( 'Product Short Description', 'swift-framework-admin' ),
                'desc' => __( "You can optionally write a short description here, which shows above the variations/cart options.", 'swift-framework-admin' ),
                'id'   => "{$prefix}product_short_description",
                'type' => 'textarea',
                'std'  => "",
                'cols' => '40',
                'rows' => '8',
            ),
            // PRODUCT DESCRIPTION
            array(
                'name' => __( 'Product Description', 'swift-framework-admin' ),
                'desc' => __( "You can optionally write a product description here, which shows under the description accordion heading if you have the page builder enabled for product pages.", 'swift-framework-admin' ),
                'id'   => "{$prefix}product_description",
                'type' => 'textarea',
                'std'  => "",
                'cols' => '40',
                'rows' => '8',
            ),
            // MISC
            array(
                'name'  => '',
                'title' => __( 'Misc. Options', 'swift-framework-admin' ),
                'id'    => "{$prefix}heading_detail",
                'type'  => 'section'
            ),
            // Extra Page Class
            array(
                'name'  => __( 'Extra page class', 'swift-framework-admin' ),
                'id'    => $prefix . 'extra_page_class',
                'desc'  => __( "If you wish to add extra classes to the body class of the page (for custom css use), then please add the class(es) here.", 'swift-framework-admin' ),
                'clone' => false,
                'type'  => 'text',
                'std'   => '',
            ),
            // REMOVE PROMO BAR
            array(
                'name' => __( 'Remove promo bar', 'swift-framework-admin' ),    // File type: checkbox
                'id'   => "{$prefix}remove_promo_bar",
                'type' => 'checkbox',
                'desc' => __( 'Remove the promo bar at the bottom of the page.', 'swift-framework-admin' ),
                'std'  => 0,
            )

        )
    );


    /* Team Meta Box
	================================================== */
    $meta_boxes[] = array(
        'id'     => 'team_meta_box',
        'title'  => __( 'Team Member Meta', 'swift-framework-admin' ),
        'pages'  => array( 'team' ),
        'fields' => array(

            // CUSTOM EXCERPT SECTION
            array(
                'name'  => '',
                'title' => __( 'Custom Excerpt', 'swift-framework-admin' ),
                'id'    => "{$prefix}heading_custom_excerpt",
                'type'  => 'section'
            ),
            // CUSTOM EXCERPT
            array(
                'name' => __( 'Custom excerpt', 'swift-framework-admin' ),
                'desc' => __( "You can optionally write a custom excerpt here to display instead of the excerpt that is automatically generated (this is needed if you use the page builder above).", 'swift-framework-admin' ),
                'id'   => "{$prefix}custom_excerpt",
                'type' => 'textarea',
                'std'  => "",
                'cols' => '40',
                'rows' => '8',
            ),
            // TEAM MEMBER DETAILS SECTION
            array(
                'name'  => '',
                'title' => __( 'Team Member Details', 'swift-framework-admin' ),
                'id'    => "{$prefix}heading_team_member_details",
                'type'  => 'section'
            ),
            // TEAM MEMBER POSITION
            array(
                'name'  => __( 'Position', 'swift-framework-admin' ),
                'id'    => $prefix . 'team_member_position',
                'desc'  => __( "Enter the team member's position within the team.", 'swift-framework-admin' ),
                'clone' => false,
                'type'  => 'text',
                'std'   => '',
            ),
            // TEAM MEMBER EMAIL
            array(
                'name'  => __( 'Email Address', 'swift-framework-admin' ),
                'id'    => $prefix . 'team_member_email',
                'desc'  => __( "Enter the team member's email address.", 'swift-framework-admin' ),
                'clone' => false,
                'type'  => 'text',
                'std'   => '',
            ),
            // TEAM MEMBER PHONE NUMBER
            array(
                'name'  => __( 'Phone Number', 'swift-framework-admin' ),
                'id'    => $prefix . 'team_member_phone_number',
                'desc'  => __( "Enter the team member's phone number.", 'swift-framework-admin' ),
                'clone' => false,
                'type'  => 'text',
                'std'   => '',
            ),
            // TEAM MEMBER TWITTER
            array(
                'name'  => __( 'Twitter', 'swift-framework-admin' ),
                'id'    => $prefix . 'team_member_twitter',
                'desc'  => __( "Enter the team member's Twitter username.", 'swift-framework-admin' ),
                'clone' => false,
                'type'  => 'text',
                'std'   => '',
            ),
            // TEAM MEMBER FACEBOOK
            array(
                'name'  => __( 'Facebook', 'swift-framework-admin' ),
                'id'    => $prefix . 'team_member_facebook',
                'desc'  => __( "Enter the team member's Facebook URL.", 'swift-framework-admin' ),
                'clone' => false,
                'type'  => 'text',
                'std'   => '',
            ),
            // TEAM MEMBER LINKEDIN
            array(
                'name'  => __( 'LinkedIn', 'swift-framework-admin' ),
                'id'    => $prefix . 'team_member_linkedin',
                'desc'  => __( "Enter the team member's LinkedIn URL.", 'swift-framework-admin' ),
                'clone' => false,
                'type'  => 'text',
                'std'   => '',
            ),
            // TEAM MEMBER GOOGLE+
            array(
                'name'  => __( 'Google+', 'swift-framework-admin' ),
                'id'    => $prefix . 'team_member_google_plus',
                'desc'  => __( "Enter the team member's Google+ URL.", 'swift-framework-admin' ),
                'clone' => false,
                'type'  => 'text',
                'std'   => '',
            ),
            // TEAM MEMBER SKYPE
            array(
                'name'  => __( 'Skype', 'swift-framework-admin' ),
                'id'    => $prefix . 'team_member_skype',
                'desc'  => __( "Enter the team member's Skype username.", 'swift-framework-admin' ),
                'clone' => false,
                'type'  => 'text',
                'std'   => '',
            ),
            // TEAM MEMBER INSTAGRAM
            array(
                'name'  => __( 'Instagram', 'swift-framework-admin' ),
                'id'    => $prefix . 'team_member_instagram',
                'desc'  => __( "Enter the team member's Instragram URL (e.g. http://hashgr.am/).", 'swift-framework-admin' ),
                'clone' => false,
                'type'  => 'text',
                'std'   => '',
            ),
            // TEAM MEMBER DRIBBBLE
            array(
                'name'  => __( 'Dribbble', 'swift-framework-admin' ),
                'id'    => $prefix . 'team_member_dribbble',
                'desc'  => __( "Enter the team member's Dribbble username.", 'swift-framework-admin' ),
                'clone' => false,
                'type'  => 'text',
                'std'   => '',
            )
        )
    );


    /* Clients Meta Box
	================================================== */
    $meta_boxes[] = array(
        'id'     => 'client_meta_box',
        'title'  => __( 'Client Meta', 'swift-framework-admin' ),
        'pages'  => array( 'clients' ),
        'fields' => array(

            // CLIENT IMAGE LINK
            array(
                'name'  => __( 'Client Link', 'swift-framework-admin' ),
                'id'    => $prefix . 'client_link',
                'desc'  => __( "Enter the link for the client if you want the image to be clickable.", 'swift-framework-admin' ),
                'clone' => false,
                'type'  => 'text',
                'std'   => ''
            )
        )
    );


    /* Testimonials Meta Box
	================================================== */
    $meta_boxes[] = array(
        'id'     => 'testimonials_meta_box',
        'title'  => __( 'Testimonial Meta', 'swift-framework-admin' ),
        'pages'  => array( 'testimonials' ),
        'fields' => array(

            // TESTIMONAIL CITE
            array(
                'name'  => __( 'Testimonial Cite', 'swift-framework-admin' ),
                'id'    => $prefix . 'testimonial_cite',
                'desc'  => __( "Enter the cite name for the testimonial.", 'swift-framework-admin' ),
                'clone' => false,
                'type'  => 'text',
                'std'   => ''
            ),
            // TESTIMONAIL CITE
            array(
                'name'  => __( 'Testimonial Cite Subtext', 'swift-framework-admin' ),
                'id'    => $prefix . 'testimonial_cite_subtext',
                'desc'  => __( "Enter the cite subtext for the testimonial (optional).", 'swift-framework-admin' ),
                'clone' => false,
                'type'  => 'text',
                'std'   => ''
            ),
            // TESTIMONAIL IMAGE
            array(
                'name'             => __( 'Testimonial Cite Image', 'swift-framework-admin' ),
                'desc'             => __( 'Enter the cite image for the testimonial (optional).', 'swift-framework-admin' ),
                'id'               => "{$prefix}testimonial_cite_image",
                'type'             => 'image_advanced',
                'max_file_uploads' => 1
            ),
        )
    );


    /* Slider Meta Box
	================================================== */
    $meta_boxes[] = array(
        'id'     => 'page_header_meta_box',
        'title'  => __( 'Page Header / Slider', 'swift-framework-admin' ),
        'pages'  => array( 'page' ),
        'fields' => array(

            // PAGE HEADER TYPE
            array(
                'name'     => __( 'Page Header Type', 'swift-framework-admin' ),
                'id'       => "{$prefix}page_header_type",
                'type'     => 'select',
                'options'  => array(
                    'standard'         => __( 'Standard', 'swift-framework-admin' ),
                    'standard-overlay' => __( 'Standard (Overlay)', 'swift-framework-admin' ),
                    'naked-light'      => __( 'Naked (Light)', 'swift-framework-admin' ),
                    'naked-dark'       => __( 'Naked (Dark)', 'swift-framework-admin' ),
                    'below-slider'     => __( 'Below Slider', 'swift-framework-admin' )
                ),
                'multiple' => false,
                'std'      => 'standard',
                'desc'     => __( 'Choose the type of header that is shown on this page. If you choose one of the Naked header options, then the header will be overlaid over the slider/area below it. NOTE: These options are only applicable for non-vertical headers.', 'swift-framework-admin' ),
            ),
            // PAGE HEADER ALT LOGO
            array(
                'name' => __( 'Use Alt Logo', 'swift-framework-admin' ),
                'id'   => "{$prefix}page_header_alt_logo",
                'type' => 'checkbox',
                'std'  => 0,
                'desc' => __( 'Choose if you would like to use the ALT logo on this page (the logo will revert to the standard logo for the sticky header if you are using it).', 'swift-framework-admin' ),
            ),
            // PAGE MENU
            array(
                'name'     => __( 'Page Menu', 'swift-framework-admin' ),
                'id'       => "{$prefix}page_menu",
                'type'     => 'select',
                'options'  => sf_get_menu_list(),
                'multiple' => false,
                'std'      => '',
                'desc'     => __( 'Optionally you can choose to override the menu that is used on the page. This is ideal if you want to create a page with a anchor link scroll menu.', 'swift-framework-admin' ),
            ),
            // PAGE SLIDER
            array(
                'name'     => __( 'Page Slider', 'swift-framework-admin' ),
                'id'       => "{$prefix}page_slider",
                'type'     => 'select',
                'options'  => array(
                    'none'         => __( 'None', 'swift-framework-admin' ),
                    'swift-slider' => __( 'Swift Slider', 'swift-framework-admin' ),
                    'revslider'    => __( 'Revolution Slider', 'swift-framework-admin' ),
                    'layerslider'  => __( 'LayerSlider', 'swift-framework-admin' ),
                    'masterslider' => __( 'Master Slider', 'swift-framework-admin' ),
                ),
                'multiple' => false,
                'std'      => 'none',
                'desc'     => __( 'Choose the type of slider you would like to display at the top of the page, if any. You can then set the slider settings below.', 'swift-framework-admin' ),
            ),
            // SWIFT SLIDER OPTIONS SECTION
            array(
                'name'       => '',
                'title'      => __( 'Swift Slider Options', 'swift-framework-admin' ),
                'id'         => "{$prefix}ss_options",
                'wrap-class' => 'pageslider-swift-slider',
                'type'       => 'section'
            ),
            // SWIFT SLIDER TYPE
            array(
                'name'       => __( 'Swift Slider Type', 'swift-framework-admin' ),
                'id'         => "{$prefix}ss_type",
                'type'       => 'select',
                'options'    => array(
                    'slider'  => __( 'Standard Slider', 'swift-framework-admin' ),
                    'curtain' => __( 'Curtain Slider', 'swift-framework-admin' ),
                ),
                'multiple'   => false,
                'std'        => 'none',
                'wrap-class' => 'pageslider-swift-slider',
                'desc'       => __( 'Choose if you would like to display the Swift Slider in horizontal slider mode, or vertical curtain slider format.', 'swift-framework-admin' ),
            ),
            // SWIFT SLIDER CATEGORY
            array(
                'name'       => __( 'Swift Slider Slide Category', 'swift-framework-admin' ),
                'id'         => "{$prefix}ss_category",
                'type'       => 'select',
                'options'    => sf_get_category_list_key_array( 'swift-slider-category' ),
                'multiple'   => false,
                'std'        => 'none',
                'wrap-class' => 'pageslider-swift-slider',
                'desc'       => __( 'Choose the category of slide that you would like to show, or all.', 'swift-framework-admin' ),
            ),
            // SWIFT SLIDER SLIDE COUNT
            array(
                'name'       => __( 'Swift Slider Slides', 'swift-framework-admin' ),
                'id'         => "{$prefix}ss_slides",
                'desc'       => __( "Set the number of slides to show. If blank then all will show.", 'swift-framework-admin' ),
                'clone'      => false,
                'type'       => 'text',
                'wrap-class' => 'pageslider-swift-slider',
                'std'        => '5',
            ),
            // SWIFT SLIDER FULLSCREEN
            array(
                'name'       => __( 'Swift Slider Fullscreen', 'swift-framework-admin' ),
                'id'         => "{$prefix}ss_fs",
                'type'       => 'checkbox',
                'std'        => 0,
                'wrap-class' => 'pageslider-swift-slider',
                'desc'       => __( 'Choose if you would like the slider to be window height.', 'swift-framework-admin' ),
            ),
            // SWIFT SLIDER MAX HEIGHT
            array(
                'name'       => __( 'Swift Slider Max Height', 'swift-framework-admin' ),
                'id'         => "{$prefix}ss_maxheight",
                'desc'       => __( "Set the maximum height that the Swift Slider should display at (optional) (no px).", 'swift-framework-admin' ),
                'clone'      => false,
                'type'       => 'text',
                'wrap-class' => 'pageslider-swift-slider',
                'std'        => '600',
            ),
            // SWIFT SLIDER AUTOPLAY
            array(
                'name'       => __( 'Swift Slider Autoplay', 'swift-framework-admin' ),
                'id'         => "{$prefix}ss_autoplay",
                'desc'       => __( "If you would like the slider to auto-rotate, then set the autoplay rotate time in ms here. I.e. you would enter '5000' for the slider to rotate every 5 seconds.", 'swift-framework-admin' ),
                'clone'      => false,
                'type'       => 'text',
                'wrap-class' => 'pageslider-swift-slider',
                'std'        => '',
            ),
            // SWIFT SLIDER LOOP
            array(
                'name'       => __( 'Swift Slider Loop', 'swift-framework-admin' ),
                'id'         => "{$prefix}ss_loop",
                'type'       => 'checkbox',
                'std'        => 1,
                'wrap-class' => 'pageslider-swift-slider',
                'desc'       => __( 'Choose if you would like the slider to loop (not possible on curtain slider).', 'swift-framework-admin' ),
            ),
            // SWIFT SLIDER NAVIGATION
            array(
                'name'       => __( 'Swift Slider Navigation', 'swift-framework-admin' ),
                'id'         => "{$prefix}ss_nav",
                'type'       => 'checkbox',
                'std'        => 1,
                'wrap-class' => 'pageslider-swift-slider',
                'desc'       => __( 'Choose if you would like to display the left/right arrows on the slider (only if slider type is set to "Slider").', 'swift-framework-admin' ),
            ),
            // SWIFT SLIDER PAGINATION
            array(
                'name'       => __( 'Swift Slider Pagination', 'swift-framework-admin' ),
                'id'         => "{$prefix}ss_pagination",
                'type'       => 'checkbox',
                'std'        => 1,
                'wrap-class' => 'pageslider-swift-slider',
                'desc'       => __( 'Choose if you would like to display the slider pagination.', 'swift-framework-admin' ),
            ),
            // SWIFT SLIDER CONTINUE
            array(
                'name'       => __( 'Swift Slider Continue', 'swift-framework-admin' ),
                'id'         => "{$prefix}ss_continue",
                'type'       => 'checkbox',
                'std'        => 1,
                'wrap-class' => 'pageslider-swift-slider',
                'desc'       => __( 'Choose if you would like to display the continue button on Curtain slider type to progress to the content. If you want to only display the slider on the page, and no content, then make sure you set this to NO.', 'swift-framework-admin' ),
            ),
            // REVSLIDER OPTIONS SECTION
            array(
                'name'       => '',
                'title'      => __( 'Revolution Slider Options', 'swift-framework-admin' ),
                'id'         => "{$prefix}rs_options",
                'wrap-class' => 'pageslider-revslider',
                'type'       => 'section'
            ),
            // REV SLIDER
            array(
                'name'       => __( 'Revolution slider alias', 'swift-framework-admin' ),
                'id'         => $prefix . 'rev_slider_alias',
                'desc'       => __( "Enter the revolution slider alias for the slider that you want to show.", 'swift-framework-admin' ),
                'type'       => 'text',
                'wrap-class' => 'pageslider-revslider',
                'std'        => '',
            ),
            // LAYERSLIDER OPTIONS SECTION
            array(
                'name'       => '',
                'title'      => __( 'LayerSlider Options', 'swift-framework-admin' ),
                'id'         => "{$prefix}ls_options",
                'wrap-class' => 'pageslider-layerslider',
                'type'       => 'section'
            ),
            // LAYERSLIDER
            array(
                'name'       => __( 'LayerSlider ID', 'swift-framework-admin' ),
                'id'         => $prefix . 'layerslider_id',
                'desc'       => __( "Enter the LayerSlider ID for the slider that you want to show.", 'swift-framework-admin' ),
                'type'       => 'text',
                'wrap-class' => 'pageslider-layerslider',
                'std'        => '',
            ),
            // MASTER SLIDER OPTIONS SECTION
            array(
                'name'       => '',
                'title'      => __( 'Master Slider Options', 'swift-framework-admin' ),
                'id'         => "{$prefix}ms_options",
                'wrap-class' => 'pageslider-masterslider',
                'type'       => 'section'
            ),
            // MASTER SLIDER
            array(
                'name'       => __( 'Master Slider ID', 'swift-framework-admin' ),
                'id'         => $prefix . 'masterslider_id',
                'desc'       => __( "Enter the Master Slider ID for the slider that you want to show.", 'swift-framework-admin' ),
                'type'       => 'text',
                'wrap-class' => 'pageslider-masterslider',
                'std'        => '',
            )
        )
    );


    /* Page Meta Box
	================================================== */
    $meta_boxes[] = array(
        'id'     => 'page_meta_box',
        'title'  => __( 'Page Meta', 'swift-framework-admin' ),
        'pages'  => array( 'page' ),
        'fields' => array(

            // MISC OPTIONS SECTION
            array(
                'name'  => '',
                'title' => __( 'One Page Options', 'swift-framework-admin' ),
                'id'    => "{$prefix}heading_onepage",
                'type'  => 'section'
            ),
            // REMOVE PROMO BAR
            array(
                'name' => __( 'Enable One Page Navigation', 'swift-framework-admin' ),    // File type: checkbox
                'id'   => "{$prefix}enable_one_page_nav",
                'type' => 'checkbox',
                'desc' => __( 'Enable the one page nav which appears on the right of the page.', 'swift-framework-admin' ),
                'std'  => 0,
            ),
            // MISC OPTIONS SECTION
            array(
                'name'  => '',
                'title' => __( 'Misc. Options', 'swift-framework-admin' ),
                'id'    => "{$prefix}heading_misc",
                'type'  => 'section'
            ),
            // Extra Page Class
            array(
                'name'  => __( 'Extra page class', 'swift-framework-admin' ),
                'id'    => $prefix . 'extra_page_class',
                'desc'  => __( "If you wish to add extra classes to the body class of the page (for custom css use), then please add the class(es) here.", 'swift-framework-admin' ),
                'clone' => false,
                'type'  => 'text',
                'std'   => '',
            ),
            // REMOVE PROMO BAR
            array(
                'name' => __( 'Remove promo bar', 'swift-framework-admin' ),    // File type: checkbox
                'id'   => "{$prefix}remove_promo_bar",
                'type' => 'checkbox',
                'desc' => __( 'Remove the promo bar at the bottom of the page.', 'swift-framework-admin' ),
                'std'  => 0,
            ),
            // REMOVE TOP SPACING
            array(
                'name' => __( 'Remove top spacing', 'swift-framework-admin' ),    // File type: checkbox
                'id'   => "{$prefix}no_top_spacing",
                'type' => 'checkbox',
                'desc' => __( 'Remove the spacing at the top of the page.', 'swift-framework-admin' ),
                'std'  => 0,
            ),
            // REMOVE BOTTOM SPACING
            array(
                'name' => __( 'Remove bottom spacing', 'swift-framework-admin' ),    // File type: checkbox
                'id'   => "{$prefix}no_bottom_spacing",
                'type' => 'checkbox',
                'desc' => __( 'Remove the spacing at the bottom of the page.', 'swift-framework-admin' ),
                'std'  => 0,
            )
        )
    );

    /* Sidebar Meta Box Page
	================================================== */
    $meta_boxes[] = array(
        'id'       => 'sidebar_meta_box_page',
        'title'    => __( 'Sidebar Options', 'swift-framework-admin' ),
        'pages'    => array( 'page' ),
        'priority' => 'low',
        'fields'   => array(

            // SIDEBAR CONFIG
            array(
                'name'     => __( 'Sidebar configuration', 'swift-framework-admin' ),
                'id'       => "{$prefix}sidebar_config",
                'type'     => 'select',
                'options'  => array(
                    'no-sidebars'   => __( 'No Sidebars', 'swift-framework-admin' ),
                    'left-sidebar'  => __( 'Left Sidebar', 'swift-framework-admin' ),
                    'right-sidebar' => __( 'Right Sidebar', 'swift-framework-admin' ),
                    'both-sidebars' => __( 'Both Sidebars', 'swift-framework-admin' )
                ),
                'multiple' => false,
                'std'      => $default_sidebar_config,
                'desc'     => __( 'Choose the sidebar configuration for the detail page of this page.', 'swift-framework-admin' ),
            ),
            // LEFT SIDEBAR
            array(
                'name' => __( 'Left Sidebar', 'swift-framework-admin' ),
                'id'   => "{$prefix}left_sidebar",
                'type' => 'sidebars',
                'std'  => $default_left_sidebar
            ),
            // RIGHT SIDEBAR
            array(
                'name' => __( 'Right Sidebar', 'swift-framework-admin' ),
                'id'   => "{$prefix}right_sidebar",
                'type' => 'sidebars',
                'std'  => $default_right_sidebar
            ),
        )
    );

    /* Sidebar Meta Box Post
	================================================== */
    $meta_boxes[] = array(
        'id'       => 'sidebar_meta_box_post',
        'title'    => __( 'Sidebar Options', 'swift-framework-admin' ),
        'pages'    => array( 'post' ),
        'priority' => 'low',
        'fields'   => array(

            // SIDEBAR CONFIG
            array(
                'name'     => __( 'Sidebar configuration', 'swift-framework-admin' ),
                'id'       => "{$prefix}sidebar_config",
                'type'     => 'select',
                'options'  => array(
                    'no-sidebars'   => __( 'No Sidebars', 'swift-framework-admin' ),
                    'left-sidebar'  => __( 'Left Sidebar', 'swift-framework-admin' ),
                    'right-sidebar' => __( 'Right Sidebar', 'swift-framework-admin' ),
                    'both-sidebars' => __( 'Both Sidebars', 'swift-framework-admin' )
                ),
                'multiple' => false,
                'std'      => $default_post_sidebar_config,
                'desc'     => __( 'Choose the sidebar configuration for the detail page of this post.', 'swift-framework-admin' ),
            ),
            // LEFT SIDEBAR
            array(
                'name' => __( 'Left Sidebar', 'swift-framework-admin' ),
                'id'   => "{$prefix}left_sidebar",
                'type' => 'sidebars',
                'std'  => $default_post_left_sidebar
            ),
            // RIGHT SIDEBAR
            array(
                'name' => __( 'Right Sidebar', 'swift-framework-admin' ),
                'id'   => "{$prefix}right_sidebar",
                'type' => 'sidebars',
                'std'  => $default_post_right_sidebar
            ),
        )
    );

    /* Sidebar Meta Box Product
	================================================== */
    $meta_boxes[] = array(
        'id'       => 'sidebar_meta_box_product',
        'title'    => __( 'Sidebar Options', 'swift-framework-admin' ),
        'pages'    => array( 'product' ),
        'priority' => 'low',
        'fields'   => array(

            // SIDEBAR CONFIG
            array(
                'name'     => __( 'Sidebar configuration', 'swift-framework-admin' ),
                'id'       => "{$prefix}sidebar_config",
                'type'     => 'select',
                'options'  => array(
                    'no-sidebars'   => __( 'No Sidebars', 'swift-framework-admin' ),
                    'left-sidebar'  => __( 'Left Sidebar', 'swift-framework-admin' ),
                    'right-sidebar' => __( 'Right Sidebar', 'swift-framework-admin' ),
                    'both-sidebars' => __( 'Both Sidebars', 'swift-framework-admin' )
                ),
                'multiple' => false,
                'std'      => $default_product_sidebar_config,
                'desc'     => __( 'Choose the sidebar configuration for the detail page of this product.', 'swift-framework-admin' ),
            ),
            // LEFT SIDEBAR
            array(
                'name' => __( 'Left Sidebar', 'swift-framework-admin' ),
                'id'   => "{$prefix}left_sidebar",
                'type' => 'sidebars',
                'std'  => $default_product_left_sidebar
            ),
            // RIGHT SIDEBAR
            array(
                'name' => __( 'Right Sidebar', 'swift-framework-admin' ),
                'id'   => "{$prefix}right_sidebar",
                'type' => 'sidebars',
                'std'  => $default_product_right_sidebar
            ),
        )
    );


    /* ==================================================
		
	Reviews Meta Box
	
	================================================== */

    $review_format = $review_cat_1 = $review_cat_2 = $review_cat_3 = $review_cat_4 = $review_suffix = $review_max = $review_step = "";

    if ( isset( $sf_options['review_format'] ) ) {
        $review_format = $sf_options['review_format'];
    }
    if ( isset( $sf_options['review_cat_1'] ) ) {
        $review_cat_1 = __( $sf_options['review_cat_1'], 'swiftframework' );
    }
    if ( isset( $sf_options['review_cat_2'] ) ) {
        $review_cat_2 = __( $sf_options['review_cat_2'], 'swiftframework' );
    }
    if ( isset( $sf_options['review_cat_3'] ) ) {
        $review_cat_3 = __( $sf_options['review_cat_3'], 'swiftframework' );
    }
    if ( isset( $sf_options['review_cat_4'] ) ) {
        $review_cat_4 = __( $sf_options['review_cat_4'], 'swiftframework' );
    }

    if ( $review_format == "" || $review_format == "percentage" ) {
        $review_suffix = " %";
        $review_max    = 100;
        $review_step   = 1;
    } else {
        $review_suffix = "";
        $review_max    = 10;
        $review_step   = .1;
    }

    $meta_boxes[] = array(
        'id'       => 'reviews_meta_box',
        'title'    => 'Review Meta',
        'priority' => 'low',
        'pages'    => array( 'post' ),
        'fields'   => array(

            // REVIEW POST ON/OFF
            array(
                'name' => 'Review Post',
                'id'   => "{$prefix}review_post",
                'type' => 'checkbox',
                'std'  => 0,
                'desc' => 'Select this checkbox if this is a review post.',
            ),
            // Review Category 1 - Name
            array(
                'name'  => 'Review Category 1 - Name',
                'id'    => $prefix . 'review_cat_1',
                'desc'  => 'Enter the name for review category 1.',
                'clone' => false,
                'type'  => 'text',
                'std'   => $review_cat_1,
            ),
            // Review Category 1 Value
            array(
                'name'       => 'Review Category 1 - Value',
                'id'         => $prefix . 'review_cat_1_value',
                'desc'       => 'Select the value for review category 1.',
                'clone'      => false,
                'type'       => 'slider',
                'prefix'     => '',
                'suffix'     => $review_suffix,
                'js_options' => array(
                    'min'  => 0,
                    'max'  => $review_max,
                    'step' => $review_step,
                ),
            ),
            // Review Category 2 - Name
            array(
                'name'  => 'Review Category 2 - Name',
                'id'    => $prefix . 'review_cat_2',
                'desc'  => 'Enter the name for review category 2.',
                'clone' => false,
                'type'  => 'text',
                'std'   => $review_cat_2,
            ),
            // Review Category 2 Value
            array(
                'name'       => 'Review Category 2 - Value',
                'id'         => $prefix . 'review_cat_2_value',
                'desc'       => 'Select the value for review category 2.',
                'clone'      => false,
                'type'       => 'slider',
                'prefix'     => '',
                'suffix'     => $review_suffix,
                'js_options' => array(
                    'min'  => 0,
                    'max'  => $review_max,
                    'step' => $review_step,
                ),
            ),
            // Review Category 3 - Name
            array(
                'name'  => 'Review Category 3 - Name',
                'id'    => $prefix . 'review_cat_3',
                'desc'  => 'Enter the name for review category 3.',
                'clone' => false,
                'type'  => 'text',
                'std'   => $review_cat_3,
            ),
            // Review Category 3 Value
            array(
                'name'       => 'Review Category 3 - Value',
                'id'         => $prefix . 'review_cat_3_value',
                'desc'       => 'Select the value for review category 3.',
                'clone'      => false,
                'type'       => 'slider',
                'prefix'     => '',
                'suffix'     => $review_suffix,
                'js_options' => array(
                    'min'  => 0,
                    'max'  => $review_max,
                    'step' => $review_step,
                ),
            ),
            // Review Category 4 - Name
            array(
                'name'  => 'Review Category 4 - Name',
                'id'    => $prefix . 'review_cat_4',
                'desc'  => 'Enter the name for review category 4.',
                'clone' => false,
                'type'  => 'text',
                'std'   => $review_cat_4,
            ),
            // Review Category 4 Value
            array(
                'name'       => 'Review Category 4 - Value',
                'id'         => $prefix . 'review_cat_4_value',
                'desc'       => 'Select the value for review category 4.',
                'clone'      => false,
                'type'       => 'slider',
                'prefix'     => '',
                'suffix'     => $review_suffix,
                'js_options' => array(
                    'min'  => 0,
                    'max'  => $review_max,
                    'step' => $review_step,
                ),
            ),
            // Review Summary Text
            array(
                'name' => 'Summary Text',
                'desc' => "You can write the summary text here to display next to the overall score.",
                'id'   => "{$prefix}review_summary",
                'type' => 'textarea',
                'std'  => "",
                'cols' => '40',
                'rows' => '8',
            ),
        )
    );


    /* Gallery Meta Box
	================================================== */
    $meta_boxes[] = array(
        'id'      => 'gallery_meta_box',
        'title'   => __( 'Gallery Options', 'swift-framework-admin' ),
        'pages'   => array( 'galleries' ),
        'context' => 'normal',
        'fields'  => array(

            // GALLERY IMAGES
            array(
                'name'             => __( 'Gallery Images', 'swift-framework-admin' ),
                'desc'             => __( 'The images that will be used in the gallery.', 'swift-framework-admin' ),
                'id'               => "{$prefix}gallery_images",
                'type'             => 'image_advanced',
                'max_file_uploads' => 200,
            ),
            // Sub Text
            array(
                'name'  => __( 'Gallery Subtitle', 'swift-framework-admin' ),
                'id'    => $prefix . 'gallery_subtitle',
                'desc'  => __( "Enter a subtitle for use within the galleries list (optional).", 'swift-framework-admin' ),
                'clone' => false,
                'type'  => 'text',
                'std'   => '',
            ),
            // CUSTOM EXCERPT
            array(
                'name' => __( 'Gallery Excerpt', 'swift-framework-admin' ),
                'desc' => __( "You can write an excerpt here which will display on the galleries list if you have it set to show.", 'swift-framework-admin' ),
                'id'   => "{$prefix}custom_excerpt",
                'type' => 'textarea',
                'std'  => "",
                'cols' => '40',
                'rows' => '8',
            ),
            // GALLERY SHARE
            array(
                'name' => __( 'Include social sharing', 'swift-framework-admin' ),
                'id'   => "{$prefix}gallery_share",
                'type' => 'checkbox',
                'desc' => __( 'Check this box to show social sharing on the detail page.', 'swift-framework-admin' ),
                'std'  => 1,
            ),
        )
    );


    /* Directory Meta Box
	================================================== */
    $meta_boxes[] = array(
        'id'      => 'directory_meta_box',
        'title'   => __( 'Directory Options', 'swift-framework-admin' ),
        'pages'   => array( 'directory' ),
        'context' => 'normal',
        'fields'  => array(


            // Address
            array(
                'name'  => __( 'Address', 'swift-framework-admin' ),
                'id'    => $prefix . 'directory_address',
                'desc'  => __( 'Enter the address that you would like to show on the map here, i.e. "Cupertino".', 'swift-framework-admin' ),
                'clone' => false,
                'type'  => 'text',
                'std'   => '',
            ),
            // Pin Button Text
            array(
                'name'  => __( 'Generate Coordinates', 'swift-framework-admin' ),
                'id'    => $prefix . 'directory_calculate_coordinates',
                'desc'  => __( 'Will automatically generate the latitude/longitude coordinates witht the given address.', 'swift-framework-admin' ),
                'clone' => false,
                'type'  => 'button',
                'std'   => 'Generate Coordinates',
            ),
            // Latitude Coordinate
            array(
                'name'  => __( 'Latitude Coordinate', 'swift-framework-admin' ),
                'id'    => $prefix . 'directory_lat_coord',
                'desc'  => __( 'Enter the Latitude coordinate of the Directory Item.', 'swift-framework-admin' ),
                'clone' => false,
                'type'  => 'text',
                'std'   => '',
            ),
            // Longitude Coordinate
            array(
                'name'  => __( 'Longitude Coordinate', 'swift-framework-admin' ),
                'id'    => $prefix . 'directory_lng_coord',
                'desc'  => __( 'Enter the Longitude coordinate of the Directory Item.', 'swift-framework-admin' ),
                'clone' => false,
                'type'  => 'text',
                'std'   => '',
            ),
            // Custom Map Pin
            array(
                'name'             => __( 'Custom Map Pin', 'swift-framework-admin' ),
                'desc'             => __( 'Choose an image to use as the custom pin for the address on the map. Upload your custom map pin, the image size must be 150px x 75px.', 'swift-framework-admin' ),
                'id'               => "{$prefix}directory_map_pin",
                'type'             => 'image_advanced',
                'max_file_uploads' => 1
            ),
            // Pin Link
            array(
                'name'  => __( 'Pin Link', 'swift-framework-admin' ),
                'id'    => $prefix . 'directory_pin_link',
                'desc'  => __( 'Enter the Link url of the location marker.', 'swift-framework-admin' ),
                'clone' => false,
                'type'  => 'text',
                'std'   => '',
            ),
            // Pin Button Text
            array(
                'name'  => __( 'Pin Button Text', 'swift-framework-admin' ),
                'id'    => $prefix . 'directory_pin_button_text',
                'desc'  => __( 'Enter the text of the Pin Button.', 'swift-framework-admin' ),
                'clone' => false,
                'type'  => 'text',
                'std'   => '',
            ),
        )
    );


    /********************* META BOX REGISTERING ***********************/

    /**
     * Register meta boxes
     *
     * @return void
     */
    function sf_register_meta_boxes() {
        global $meta_boxes;

        // Make sure there's no errors when the plugin is deactivated or during upgrade
        if ( class_exists( 'RW_Meta_Box' ) ) {
            foreach ( $meta_boxes as $meta_box ) {
                new RW_Meta_Box( $meta_box );
            }
        }
    }

    // Hook to 'admin_init' to make sure the meta box class is loaded before
    // (in case using the meta box class in another plugin)
    // This is also helpful for some conditionals like checking page template, categories, etc.
    add_action( 'admin_init', 'sf_register_meta_boxes' );

?>
