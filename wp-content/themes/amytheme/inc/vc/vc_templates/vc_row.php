<?php
$output = $el_class = $bg_image = $bg_color = $inner_row = $bg_image_repeat = $font_color = $padding = $full_width = $margin_bottom = $css = '';
extract(shortcode_atts(array(
    'el_class'        => '',
    'bg_image'        => '',
    'bg_color'        => '',
    'bg_image_repeat' => '',
    'font_color'      => '',
    'padding'         => '',
    'margin_bottom'   => '',
	'full_width'   => 'fullwidthrow',
	'inner_row' => '',
	'css' => '',
), $atts));

wp_enqueue_style( 'js_composer_front' );
wp_enqueue_script( 'wpb_composer_front_js' );
wp_enqueue_style('js_composer_custom_css');

$el_class = $this->getExtraClass($el_class);

$css_class =  apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_row '.get_row_css_class().$el_class, $this->settings['base']);

$style = $this->buildStyle($bg_image, $bg_color, $bg_image_repeat, $font_color, $padding, $margin_bottom);

$output .= '<div class="'.$inner_row.' '.$css_class.'"'.$style.'><div class='.$full_width.' >';
$output .= wpb_js_remove_wpautop($content);
$output .= '</div></div>'.$this->endBlockComment('row');

echo $output;