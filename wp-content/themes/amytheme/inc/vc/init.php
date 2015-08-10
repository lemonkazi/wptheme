<?php

if(function_exists('vc_set_as_theme')) {
	vc_set_as_theme( true);
	vc_set_template_dir( get_template_directory() . '/inc/vc/vc_templates/');
	include( 'map.php' );
	vc_remove_element('vc_cta_button');
	vc_remove_element('vc_button');
	vc_remove_element('vc_images_carousel');
	vc_remove_element('vc_posts_slider');
}

?>