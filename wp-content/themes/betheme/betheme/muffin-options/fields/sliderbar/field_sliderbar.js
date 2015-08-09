jQuery(document).ready(function(){
	
	jQuery('.sliderbar').each(function(){
		
		var field_id = jQuery(this).attr('rel');
		var value = jQuery(this).siblings('#' + field_id).attr('value');
		
		jQuery(this).slider({ range: "min", min:1, max:70, value: value,
			slide: function(event, ui){
				jQuery(this).siblings('#' + field_id).attr('value',ui.value);
			}
		});
		
	});

});