/*
 *
 * MFN_Options_radio_img function
 * Changes the radio select option, and changes class on images
 *
 */

function mfn_radio_img_select(relid, labelclass){
	jQuery(this).prev('input[type="radio"]').prop('checked');
	jQuery('.mfn-radio-img-'+labelclass).removeClass('mfn-radio-img-selected');	
	jQuery('label[for="'+relid+'"]').addClass('mfn-radio-img-selected');
}