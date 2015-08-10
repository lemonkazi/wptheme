<?php

$output = $el_class = $image = $img_size = $img_link = $img_color = $img_link_target = $img_link_large = $title = $alignment = $css_animation = '';

extract(shortcode_atts(array(
    'title' => '',
    'image' => $image,
    'img_size'  => 'thumbnail',
    'img_link_large' => false,
    'img_link' => '',
	'img_color' => '',
    'img_link_target' => '_self',
    'alignment' => 'left',
    'el_class' => '',
    'css_animation' => ''
), $atts));

$img_id = preg_replace('/[^\d]/', '', $image);
$img = wpb_getImageBySize(array( 'attach_id' => $img_id, 'thumb_size' => $img_size ));
if ( $img == NULL ) $img['thumbnail'] = '<img src="http://placekitten.com/g/400/300" /> <small>'.__('This is image placeholder, edit your page to replace it.', 'js_composer').'</small>';

$el_class = $this->getExtraClass($el_class);

$a_class = ' class="imghoverfx"';
if ( $el_class != '' ) {
    $tmp_class = explode(" ", strtolower($el_class));
    $tmp_class = str_replace(".", "", $tmp_class);
    if ( in_array("prettyphoto", $tmp_class) ) {
        wp_enqueue_script( 'prettyphoto' );
        wp_enqueue_style( 'prettyphoto' );
        $a_class = ' class="prettyphoto"';
        $el_class = str_ireplace(" imghoverfx prettyphoto", "", $el_class);
    }
}

$link_to = '';
if ($img_link_large==true) {
    $link_to = wp_get_attachment_image_src( $img_id, 'large');
    $link_to = $link_to[0];
	
	
}
else if (!empty($img_link)) {
    $link_to = $img_link;
}


if ($img_link_large==true) {
$image_string ='
	<div class="slelementno">
		<figure>'.$img['thumbnail'].'
			<figcaption>
				<a href="'.$link_to.'" rel="prettyPhotoImages[1]">
					<div class="clickimg">
						<i class="icon-search"></i>
					</div>
				</a>
			</figcaption>
		</figure>
	</div>';
}else if (!empty($img_link)) {
    $image_string ='
	<div class="slelementno">
		<figure>'.$img['thumbnail'].'
			<figcaption>
				<a href="'.$link_to.'" '.($img_link_target!='_self' ? ' target="'.$img_link_target.'"' : '').' >
					<div class="clickimg">
						<i class="icon-link"></i>
					</div>
				</a>
			</figcaption>
		</figure>
	</div>';
}else{
$image_string ='
	<div class="slelementno">
		<figure>'.$img['thumbnail'].'</figure>
	</div>';
}


$css_class =  apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_single_image wpb_content_element'.$el_class, $this->settings['base']);
$css_class .= $this->getCSSAnimation($css_animation);

$css_class .= ' vc_align_'.$alignment;

$output .= "\n\t".'<div class="'.$css_class.'" >';
$output .= "\n\t\t".'<div class="wpb_wrapper"><div class="imghoverfxbg grid '.$img_color.'">';
$output .= "\n\t\t\t".wpb_widget_title(array('title' => $title, 'extraclass' => 'wpb_singleimage_heading'));
$output .= "\n\t\t\t".$image_string;
$output .= "\n\t\t".'</div></div> '.$this->endBlockComment('.wpb_wrapper');
$output .= "\n\t".'</div> '.$this->endBlockComment('.wpb_single_image');

echo $output;
?>