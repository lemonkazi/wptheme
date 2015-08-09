<?php
$output = $title = $el_class = $bg_color = $nav_menu = '';
extract( shortcode_atts( array(
    'title' => '',
    'nav_menu' => '',
	'bg_color' => '',
    'el_class' => ''
), $atts ) );
$el_class = $this->getExtraClass($el_class);

$output = '<div class="vc_wp_custommenu wpb_content_element '.$bg_color.' '.$el_class.'">';
$type = 'WP_Nav_Menu_Widget';
$args = array();

ob_start();
the_widget( $type, $atts, $args );
$output .= ob_get_clean();

$output .= '</div>' . $this->endBlockComment('vc_wp_custommenu') . "\n";

echo $output;