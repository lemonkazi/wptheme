<?php
/*
* Theme Name: AMY Theme - Creative Multi-Purpose WordPress Theme
* Theme Author: Andrey Boyadzhiev - http://themes.cray.bg
*
* Version: 1.0 
*/
?>

<?php
global $sopernal_settings ,$ab_amy_bgslideshow ,$is_404;
if($is_404 == true){
	$id = $sopernal_settings['404-page'];
	$post_meta_data = get_post_custom($sopernal_settings['404-page']);
}else{
	$id = get_the_ID();
	$post_meta_data = get_post_custom($post->ID);
}

include('post-settings.php');

$ab_tf_body_color_scheme = $sopernal_settings['body-color-scheme'];


if($ab_tf_body_color_scheme == 'turquoise'){
	$color = '#52ccb3';
	$bacgroundcolor = '#247967';
	$bacgroundcolora = '82,204,179';
}elseif($ab_tf_body_color_scheme == 'greensea'){
	$color = '#4eb7a3';
	$bacgroundcolor = '#157260';
	$bacgroundcolora = '78,183,163';
}elseif($ab_tf_body_color_scheme == 'emerald'){
	$color = '#5fd892';
	$bacgroundcolor = '#28a45c';
	$bacgroundcolora = '95,216,146';
}elseif($ab_tf_body_color_scheme == 'nephritis'){
	$color = '#4fb77c';
	$bacgroundcolor = '#25804c';
	$bacgroundcolora = '79, 183, 124';
}elseif($ab_tf_body_color_scheme == 'peterriver'){
	$color = '#65b1e4';
	$bacgroundcolor = '#367aa6';
	$bacgroundcolora = '101,177,299';
	
}elseif($ab_tf_body_color_scheme == 'belizehole'){
	$color = '#5d9ec9';
	$bacgroundcolor = '#256690';
	$bacgroundcolora = '93,158,201';
}elseif($ab_tf_body_color_scheme == 'amethyst'){
	$color = '#b281c7';
	$bacgroundcolor = '#814999';
	$bacgroundcolora = '178,129,199';
}elseif($ab_tf_body_color_scheme == 'wisteria'){
	$color = '#a871c1';
	$bacgroundcolor = '#814999';
	$bacgroundcolora = '168,113,193';
}elseif($ab_tf_body_color_scheme == 'wetasphalt'){
	$color = '#657585';
	$bacgroundcolor = '#394b5d';
	$bacgroundcolora = '101,117,133';
}elseif($ab_tf_body_color_scheme == 'midnightblue'){
	$color = '#566576';
	$bacgroundcolor = '#1b3046';
	$bacgroundcolora = '86,101,108';
}elseif($ab_tf_body_color_scheme == 'sunflower'){
	$color = '#f4d248';
	$bacgroundcolor = '#a3860f';
	$bacgroundcolora = '244,210,72';
}elseif($ab_tf_body_color_scheme == 'orange'){
	$color = '#f6b44a';
	$bacgroundcolor = '#cd6f00';
	$bacgroundcolora = '246,180,74';
}elseif($ab_tf_body_color_scheme == 'carrot'){
	$color = '#ec9e57';
	$bacgroundcolor = '#d56f12';
	$bacgroundcolora = '236,158,87';
}elseif($ab_tf_body_color_scheme == 'pumpkin'){
	$color = '#dd7d3d';
	$bacgroundcolor = '#a84908';
	$bacgroundcolora = '221,125,60';
}elseif($ab_tf_body_color_scheme == 'alizarin'){
	$color = '#ee776b';
	$bacgroundcolor = '#b83f33';
	$bacgroundcolora = '238,119,107';
}elseif($ab_tf_body_color_scheme == 'pomegranate'){
	$color = '#d0685e';
	$bacgroundcolor = '#8b2a21';
	$bacgroundcolora = '208,104,94';
}elseif($ab_tf_body_color_scheme == 'concrete'){
	$color = '#aebbbb';
	$bacgroundcolor = '#697878';
	$bacgroundcolora = '174,187,187';
}elseif($ab_tf_body_color_scheme == 'asbestos'){
	$color = '#96a2a2';
	$bacgroundcolor = '#717d7d';
	$bacgroundcolora = '150,162,162';
}

?>

<style>
<?php echo $sopernal_settings['add-custom-css'];?>

#footer a, .breadcrumbs a, .opentip-container .opentip a {
	color:<?php echo $color;?>;
}
#footer a:hover, .breadcrumbs a:hover, .opentip a:hover{
	color:<?php echo $bacgroundcolor;?>;
}
.pagination a:hover, .pagination .current, .dl-menuwrapper button, .navkey:hover, .date-time:hover{
	background-color:rgba(<?php echo $bacgroundcolora;?>, 0.8)!important;
}
.dl-menuwrapper button, .gglass .timedate, .footerwidget .wpcf7-submit{
	background-color:rgba(<?php echo $bacgroundcolora;?>, 1)!important;
}
.dl-menuwrapper button:hover,.footerwidget .wpcf7-submit:hover{
	background-color:<?php echo $bacgroundcolor;?>!important;
}
.nav li:hover a, .selected-nav a{
	color:<?php echo $color;?>;
}

ul ul .current_page_item, ul li .current-menu-item, .nav ul li:hover , .responsivemenu  a:hover, .searchmenu:hover #navs{
	background-color:rgba(<?php echo $bacgroundcolora;?>, 1)!important;
	color:#fff!important;
}
ul ul .current-menu-item a, ul ul .current-menu-item  li a:hover{
	color:#fff!important;
}
ul ul .current-menu-item  li a{
	color:#fff!important;
}

.nav li:hover, .selected-nav, .current-menu-item, .current-menu-parent, .navcal li ul, .nav li ul{
	border-bottom-color:rgba(<?php echo $bacgroundcolora;?>, 1)!important;
}
/**/

.nav ul li  a:hover, .nav ul ul:active a{
	color:#fff!important;
	
}
 .nav ul li:hover > a {
	color:#fff!important;
	
}
figcaption a:hover .clicklink, figcaption a:hover .clickimg {
	color:#fff!important;
}

#tt-h-two, #tt-h-one{
	<?php if($ab_tf_post_padding_top !=''){echo 'margin-top:'.$ab_tf_post_padding_top.';';};?>
	<?php if($ab_tf_post_padding_bottom !=''){echo 'margin-bottom:'.$ab_tf_post_padding_bottom.';';};?>
	/*padding-top:0px;
	padding-bottom:0px;*/
}

<?php if ($sopernal_settings['menu-background-shadow'] == false){?>
.header-white {
-webkit-box-shadow:none;
-moz-box-shadow:none;
-ms-box-shadow:none;
box-shadow:none;
}
<?php }?>
<?php if ($sopernal_settings['menu-background-gradient'] == true){?>
.header-white {
 	background: -webkit-linear-gradient(<?php echo $sopernal_settings['menu-background']['color'];?>, transparent);
    background: -o-linear-gradient(<?php echo $sopernal_settings['menu-background']['color'];?>, transparent); 
    background: -moz-linear-gradient(<?php echo $sopernal_settings['menu-background']['color'];?>, transparent); 
    background: linear-gradient(<?php echo $sopernal_settings['menu-background']['color'];?>, rgba(255,255,255,0)); 
	height:<?php echo $sopernal_settings['menu-background-gradient-height'];?>px;
	
	opacity:<?php echo $sopernal_settings['menu-background']['alpha'];?>
}
<?php }?>

	

.header-top-p{
	<?php if($ab_tf_post_bgcolor !='select color'){echo 'background-color:'.$ab_tf_post_bgcolor.'!important;';};?>
}
#customer_details ::-webkit-input-placeholder {
  color:<?php echo $sopernal_settings['woo-input-color']['color']?>!important;
}

</style>
<?php 

global $ab_tf_post_showfbcomments, $ab_tf_post_showdscomments,  $ab_tf_post_header, $ab_tf_post_prallax_fx, $ab_tf_post_prallax_speed, $ab_tf_post_prallax_coverratio, $ab_tf_post_prallax_minheight, $ab_tf_post_prallax_extraheight, $ab_tf_post_prallax_zoom;
?>
<script>
<?php echo $sopernal_settings['add-custom-js'];?>
jQuery(document).ready(function($){
	
	$( ".searchmenu" )
  .mouseover(function() {
	  $("#nav").css('opacity','0.1');
	  // $("#nav").fadeTo( "opacity", "0.13" );
  })
  .mouseout(function() {
	     $("#nav").css('opacity','1');
  	// $("#nav").fadeTo( 300, 1 );
  });
  
	
	$(window).bind("load", function() {	
	for(var i = 0; i < Opentip.tips.length; i ++) { Opentip.tips[i].show(); }	
for(var i = 0; i < Opentip.tips.length; i ++) { Opentip.tips[i].hide(); }
	jQuery('.loadbg').removeClass('fadeInUp').addClass("fadeOutDown");
 })
	
	if(!$("#mainpage div").hasClass('wpb_row')){
		//$("#ss-container").addClass("fullwidthrow");
		$(".ss-stand-alone").addClass("fullwidthrow");
		
	}else if( $("#mainpage") ){
		
	}
	//$("#content").addClass("fullwidthrow");
		
	
	
	var nowayp = 0
	if($("#mainpage div").hasClass('wpb_animate_when_almost_visible')){
		nowayp = 1;
	}
  <?php if($sopernal_settings['footer-position'] != "absolute"){?>
		if($(window).width() > 810){
		$(".ccscroll").mCustomScrollbar({
					autoHideScrollbar:true,
					theme:"light-thin",
					advanced:{
						autoScrollOnFocus: false,
        				updateOnContentResize: true
   					 }
				});
			
		}
		<?php }?>
	
				
	var waitForFinalEvent = (function () {
  var timers = {};
  return function (callback, ms, uniqueId) {
    if (!uniqueId) {
      uniqueId = "Don't call this twice without a uniqueId";
    }
    if (timers[uniqueId]) {
      clearTimeout (timers[uniqueId]);
    }
    timers[uniqueId] = setTimeout(callback, ms);
  };
})();
$(window).resize(function () {
	
	
    waitForFinalEvent(function(){
		if($("#mainpage div").hasClass('fb-comments')){
     		var thewidth = $(".fb-comments").width();
			$(".fb-comments").attr("data-width", thewidth );
					FB.XFBML.parse();
						}
    }, 700, "fb resize");
	
});
var thewidth = $(".fb-comments").width();
$(".fb-comments").attr("data-width", thewidth );
					//FB.XFBML.parse();
				$(window).resize(function() {
					
					
			
						
	 waitForFinalEvent(function(){
		 <?php if($sopernal_settings['footer-position'] != "absolute"){?>
		if($(window).width() < 810){
			$(".ccscroll").mCustomScrollbar("destroy");
			
		}else{
			$(".ccscroll").mCustomScrollbar("destroy");
			$(".ccscroll").mCustomScrollbar({
					autoHideScrollbar:true,
					theme:"light-thin",
					advanced:{
						autoScrollOnFocus: false,
        				updateOnContentResize: true
   					 }
				});
		}
		<?php }?>
	 })
	})
	$("#mainpage").bind("touchmove", function (event) {
		$.waypoints('refresh');
		if($('#mainpage > *').hasClass('wpb_animate_when_almost_visible')){
							$.waypoints('refresh');
							
							}
		
	})
	
				
				//MNOGO VAJNO IZKLIUCHVA SROLA 
				/*$('#mainpage').mCustomScrollbar("destroy");
				$('#mainpage').css("position","static");*/
			
	
	
	var notclicked='0'
	
	$("#widgetfooter").click(function(){
		for(var i = 0; i < Opentip.tips.length; i ++) { Opentip.tips[i].hide(); }
		if($(window).width() > 810){	
			if(notclicked !=1 ){
				$(".p-position").css('bottom','460px');
				$("#footer").css('bottom','380px');
				$("#widgetfooter").css('bottom','380px');
				notclicked =1
			}else{
				 $(".p-position").css('bottom','80px');
				 $("#footer").css('bottom','0px');
				 $("#widgetfooter").css('bottom','0px');
			 notclicked =0
			}
		}
		
		
    }); 
	$("#main").click(function(){
		for(var i = 0; i < Opentip.tips.length; i ++) { Opentip.tips[i].hide();}
		if($(window).width() > 810){	
			if(notclicked ==1 ){
				$("#footer").css('bottom','0px');
				$("#widgetfooter").css('bottom','0px');
				$(".p-position").css('bottom','80px');
				$(".responsivemenuwarp").css('left','-400px');
				$(".responsivemenubg").css('left','-400px');
				notclicked =0
			}
		}else{
			if(notclicked ==1 ){
				$(".responsivemenuwarp").css('left','-400px');
				$(".responsivemenubg").css('left','-400px');
				notclicked =0
			}
			
		}
	})
	$("#mainpage").click(function(){
		for(var i = 0; i < Opentip.tips.length; i ++) { Opentip.tips[i].hide();}
		if($(window).width() > 810){	
			if(notclicked ==1 ){
				$("#footer").css('bottom','0px');
				$("#widgetfooter").css('bottom','0px');
				$(".p-position").css('bottom','80px');
				$(".responsivemenuwarp").css('left','-400px');
				$(".responsivemenubg").css('left','-400px');
				notclicked =0
			}
		}else{
			if(notclicked ==1 ){
				$(".responsivemenuwarp").css('left','-400px');
				$(".responsivemenubg").css('left','-400px');
				notclicked =0
			}
		}
	})

	
	
	
	
	$("#openrespmenu").click(function(){
		for(var i = 0; i < Opentip.tips.length; i ++) { Opentip.tips[i].hide(); }
		if(notclicked !=1 ){
			$(".responsivemenuwarp").css('left','0px');
			$(".responsivemenubg").css('left','0px');
			$(".ss-container").css('left','300px');	
			notclicked =1
		}else{
			$(".responsivemenuwarp").css('left','-400px');
			$(".responsivemenubg").css('left','-400px');
			notclicked =0
		}
		
		
    }); 
	
	
$( '.menu-item a' ).each ( function () {
	
	
		
		
	$(this).click(function(event) {
		$( '.menu-item a' ).parent().removeClass( "current-menu-item");
		var selectmenu = $(this);
		var elClasses = $(this).attr('href').replace(/^.*?#/,'');
				if($('.header-top-p div').hasClass(elClasses)){
					event.preventDefault();
					$('html,body').animate({
					<?php if($sopernal_settings['menu-position'] == "absolute"){?>
						
						scrollTop: $('.'+elClasses).offset().top
					<?php }else{?>
						scrollTop: $('.'+elClasses).offset().top - 74
					<?php } ?>
				}, 1200, function(){window.location.hash = elClasses; selectmenu.parent().addClass( "current-menu-item"); });
				}	
	});
});

	
	
	//For touch devices
	//==================================================
	$('a').live('touchend', function(e) {
		var el = $(this);
		var link = el.attr('href');
	});
	//pretty Photo settings( ! Don't change )
	//==================================================
//	$("a[rel^='prettyPhoto']").prettyPhoto({theme: 'dark_square', allow_resize: false,});
	$("a[rel^='prettyPhotoImages']").prettyPhoto({theme: 'dark_square',allow_resize: true});	
	//Clock settings
	//==================================================
	function update(widget_id, time_format, date_format) {
		var months = new Array("<?php echo $sopernal_settings['tr-months-jan'];?>", "<?php echo $sopernal_settings['tr-months-feb'];?>", "<?php echo $sopernal_settings['tr-months-mar'];?>", "<?php echo $sopernal_settings['tr-months-apr'];?>", "<?php echo $sopernal_settings['tr-months-may'];?>", "<?php echo $sopernal_settings['tr-months-jun'];?>", "<?php echo $sopernal_settings['tr-months-jul'];?>", "<?php echo $sopernal_settings['tr-months-aug'];?>", "<?php echo $sopernal_settings['tr-months-sep'];?>", "<?php echo $sopernal_settings['tr-months-oct'];?>", "<?php echo $sopernal_settings['tr-months-nov'];?>", "<?php echo $sopernal_settings['tr-months-dec'];?>"),
		ampm = " AM",
		now = new Date(),
		hours = now.getHours(),
		minutes = now.getMinutes(),
		seconds = now.getSeconds(),
		$date = jQuery("#" + widget_id + " .tt-b-date"),
		$day = jQuery("#" + widget_id + " .tt-b-day"),
		$month = jQuery("#" + widget_id + " .tt-b-month");
	
	
		if (date_format != "none") {
		var currentTime = new Date(),
			year = currentTime.getFullYear(),
			month = currentTime.getMonth(),
			day = currentTime.getDate();
			
		if (date_format == "long") {
			$date.text(months[month] + " " + day + ", " + year);
		}
		else if (date_format == "medium") {
			$day.text(day);
			$month.text(months[month].substring(0, 3));
			$date.text(year);
		}
		else if (date_format == "short") {
			$date.text((month + 1) + "/" + day + "/" + year);
		}
		else if (date_format == "european") {
			$date.text(day + "/" + (month + 1) + "/" + year);
		}
		}	
		
		if ((date_format != "none") || (time_format != "none")) {
		setTimeout(function() {
			update(widget_id, time_format, date_format);
		}, 60000);
		}
	}
	update('footer-time', '12-hour', 'medium');
});
		
jQuery(window).load(function($) {
		var siteString='';
		var hash = window.location.hash;
		
		siteString = hash.replace(/^.*?#/,'');
		if(siteString !='' && jQuery('.header-top-p div').hasClass(siteString)){
			
			setTimeout( function(){
			jQuery('html,body').animate({	
					scrollTop: jQuery('.'+siteString).offset().top - 74
				}, 1200)}, 600)
		
		}
	//Show / hide loading and elements
	//==================================================
	jQuery('.inifiniteLoaderP').removeClass('fadeOutDownL').addClass("fadeInUp");
	jQuery('.inifiniteLoaderBg').removeClass('fadeOutDownL').addClass("fadeInUp");

 
 	jQuery('.inifiniteLoaderP').removeClass('fadeInUp').addClass("fadeOutDownL");
	jQuery('.inifiniteLoaderBg').removeClass('fadeInUp').addClass("fadeOutDownL");
	
	
	
	setTimeout(function(){
		jQuery('.socicons').removeClass('fadeOutDown').addClass("fadeInUp");
		jQuery('.tt-bottom-nav').removeClass('fadeOutDown').addClass("fadeInUp");
		jQuery('.copyrholder').removeClass('fadeOutDown').addClass("fadeInUp");
		jQuery('.date-time').removeClass('fadeOutDown').addClass("fadeInUp");
		jQuery('.numpostinfi').removeClass('fadeOutDown').addClass("fadeInUp");
	},600);
	
	setTimeout(function(){
		jQuery('.breadcrumbs').removeClass('fadeOutDown').addClass("fadeInUpt");
	},800);
	setTimeout(function(){
		jQuery('.dl-menuwrapper').removeClass('fadeOutDown').addClass("fadeInUp");
	},1000);
	setTimeout(function(){
		jQuery('.p-position').removeClass('fadeOutDown').addClass("fadeInUp");
	},1200);
});
//alert("");
</script>
<?php
if($sopernal_settings['yt-active'] == true && $ab_amy_bgslideshow == 1 || $sopernal_settings['yt-active'] == true && $sopernal_settings['site-width'] == "boxed"){?>
		<script>
		//Youtube API
		//==================================================
        jQuery( document ).ready( function($){
             $('#ytbgvideo').tubular({videoId: '<?php echo $sopernal_settings['yt-bg-id']; ?>', mute: <?php echo $sopernal_settings['yt-bg-mute'];?> , start: '<?php echo $sopernal_settings['yt-bg-start'];?>', repeat:<?php echo $sopernal_settings['yt-bg-repeat'];?> }); 
        });
        </script><?php 
	
}?>

<?php if($sopernal_settings['yt-bg-cotrols'] == true  && $sopernal_settings['yt-active'] == true ){?>
<script>
//Youtube controls
//==================================================
jQuery(document).ready(function($){
	document.getElementById('hide-content').addEventListener('click', hidecontent);
	var iscontenthiden = 0;
	function hidecontent(){
		if(iscontenthiden == 0 ){
			$('.header-top-p').delay(300).addClass('addopahide').delay(300).removeClass('remopa');
			$('.header-white').delay(300).addClass('addopahide').delay(300).removeClass('remopa');
			$('.ss-nav').delay(300).addClass('addopahide').delay(300).removeClass('remopa');
			$('#widgetfooter').delay(300).addClass('addopahide').delay(300).removeClass('remopa');
			
			 
			iscontenthiden = 1;
		}else{
			$('.header-top-p').delay(300).addClass('remopa').delay(300).removeClass('addopahide');
			$('.header-white').delay(300).addClass('remopa').delay(300).removeClass('addopahide');
			$('.ss-nav').delay(300).addClass('remopa').delay(300).removeClass('addopahide');
			$('#widgetfooter').delay(300).addClass('remopa').delay(300).removeClass('addopahide');
			iscontenthiden = 0;
		}
	}
});
</script>       
<div class="bottom-video-nav">
	<div>
    	<i id="hide-content" class="icon-resize-full navkey"></i> 
    	<i class="icon-stop navkey tubular-pause"></i> 
        <i class="icon-play navkey tubular-play"></i> 
        <i class="icon-volume-up navkey tubular-mute"></i></div>
</div><?php 
}?>

<?php	if( $sopernal_settings['bg-active'] == true && $ab_amy_bgslideshow == 1){ ?>   
<script>	

		jQuery(document).ready(function($){
			'strict mode';
			if(window.hasownbg != 1){
				<?php if(isset($sopernal_settings['bg-slides'][1]['image'])){?>
				jQuery.vegas('slideshow', {
				delay:<?php echo $sopernal_settings['bg-delay']; ?>,
				backgrounds:[
				<?php $i = 0; 
					foreach( $sopernal_settings['bg-slides'] as $slide ){?>
					
					{ src:'<?php echo $sopernal_settings['bg-slides'][$i]['image'];?>', fade:<?php echo $sopernal_settings['bg-fade']; ?>, valign:'<?php echo $sopernal_settings['bg-vposition']; ?>', align:'<?php echo $sopernal_settings['bg-hposition']; ?>' },
					 
				 <?php $i++; 
				  }; ?>
					
				  ]
				})('overlay', {
				  src:'<?php echo get_template_directory_uri(); ?>/images/overlays/<?php echo $sopernal_settings['bg-overlays'];?>.png'
				});
			
			 <?php }else{?>
			 
				jQuery.vegas({
						src:'<?php echo $sopernal_settings['bg-slides'][0]['image'];?>', 
						fade:<?php echo $sopernal_settings['bg-fade']; ?>, 
						valign:'<?php echo $sopernal_settings['bg-vposition']; ?>', 
						align:'<?php echo $sopernal_settings['bg-hposition']; ?>' 
				
				})('overlay', {
				  src:'<?php echo get_template_directory_uri(); ?>/images/overlays/<?php echo $sopernal_settings['bg-overlays'];?>.png',
				});
			 
			 <?php }?>
			}
		});
	</script>
<?php };?>



<script>
jQuery(document).ready(function($){
	
	


//Display wellcome buble (use cookie to show only once)
	//==================================================
	function initInstructions() {
		function setCookie(c_name,value,exdays){
			var exdate=new Date();
			exdate.setDate(exdate.getDate() + exdays);
			var c_value=escape(value) + ((exdays===null) ? "" : "; expires="+exdate.toUTCString());
			document.cookie=c_name + "=" + c_value;
		}	
		function getCookie(c_name){
			var c_value = document.cookie;
			var c_start = c_value.indexOf(" " + c_name + "=");
			if (c_start === -1){
				c_start = c_value.indexOf(c_name + "=");
			}
			if (c_start === -1){
				c_value = null;
			}else{
				c_start = c_value.indexOf("=", c_start) + 1;
				var c_end = c_value.indexOf(";", c_start);
				if (c_end === -1){
					c_end = c_value.length;
				}
				c_value = unescape(c_value.substring(c_start,c_end));
			}
			return c_value;
		}
		function checkCookie(){
			var bubleopen = Number(getCookie("welcomemsg"));
			if(bubleopen !== 1){
					$("#ss-container").unbind("mousewheel DOMMouseScroll");
					
					//showInstructions()
					instructionsTimeout=setTimeout(showInstructions, 2000);
			
				setCookie('welcomemsg','1', 1);	
			}
		}	
		<?php if($sopernal_settings['welcome-msg'] == '1'){ ?>
		if(getCookie("welcomemsg") == null){
			checkCookie();
		}
		
		<?php };?>
	}
	
	//Show hide wellcome bubble
	//==================================================
	function showInstructions() {
		$('.addbg').addClass('addbgv');
		$('.addbg').click(function() {
			
				hideInstructions();	
				
			//$(this).unbind("click");
		});
		document.querySelectorAll('header .welcome-b')[0].className = 'welcome-b visible animated fadeInUp';
	}
	function hideInstructions() {
		$('.addbg').removeClass('addbgv');
		clearTimeout(instructionsTimeout);
		document.querySelectorAll('header .welcome-b')[0].className = 'welcome-b';
	}
	
	initInstructionsgo=setTimeout(initInstructions, 1000);
	//initInstructions()
	
});
	</script>





