<?php
/*
* Theme Name: AMY Theme - Creative Multi-Purpose WordPress Theme
* Theme Author: Andrey Boyadzhiev - http://themes.cray.bg
*
* Version: 1.0 
*/
?>
<?php
$st = "0";
$goinfinite  = 0;
global $slectloop, $ab_amy_settings, $wp_query;
if(is_date() ||  is_search()){
	$st = "1";	
}else{
	$st = "0";
}
if ($ab_amy_settings['def-pagination-display'] == "2"){ 
	$slectloop = 'loop';
	$goinfinite  = 1;
}
if($goinfinite == 1){?>
	<script>
		'strict mode';
		var slectloop = <?php echo json_encode($slectloop); ?>;
		var whait = 0;
		var count = 2;
		var total = <?php echo $wp_query->max_num_pages; ?>;
		var is_state = <?php echo $st; ?>;
		window.initajax = function(){
			if (count > total){
				return false;
			}else{
				if(whait !=1){  
					loadArticle(count, is_state);
					whait = 1
				}else{
				   return false;
				}
			}
			count++;
		}
		function loadArticle(pageNumber, is_state){ 
			jQuery('.inifiniteLoader').removeClass('fadeOutDown').addClass("fadeInUp");
			jQuery('.numpostinfi').removeClass('fadeInUp').addClass("fadeOutDown");
				jQuery.ajax({
					url: "<?php echo site_url() ?>/wp-admin/admin-ajax.php",
					type:'POST',
					data:"action=infinite_scroll&page_no="+ pageNumber + '&loop_file='+slectloop+'&is_state='+is_state,
					success: function(html){
						jQuery('.inifiniteLoader').removeClass('fadeInUp').addClass("fadeOutDown");	
						jQuery('.numpostinfi').removeClass('fadeOutDown').addClass("fadeInUp");
						jQuery("#articlehold").append(html);
						whait = 0;
					}
				});
			return false;
		}
	</script><?php 
};?>

<script>
jQuery(document).ready(function($){
	'use strict';
	var themes,
		selectedThemeIndex,
		instructionsTimeout,
		deck;
	window.scrollinit = function(){
		deck = bespoke.from('article');
		initThemeSwitching();
	};
	setTimeout(scrollinit,1000);
	function initThemeSwitching() {
		themes = [
			'classictilt'
		];
		selectedThemeIndex = 0;
		if(window.lastslide !==''){
			deck.slide(window.lastslide-1);
		}else{
			deck.slide(0);
		}
		if(window.openfirst !== 1){
			window.gokb='';
			var nmax = $("section").length -1;
			window.openfirst = 1
			window.gomouse();		
			deck.slide(<?php echo $ab_amy_settings['amy-slider-first-post'] ?>);
		}
		
		initInstructions();
		initKeys();
		initButtons();
		initSlideGestures();
		initClickInactive();
		loopreset();
		<?php if($ab_amy_settings['amy-slider-parallax'] == true){?>
		//	var scene = document.getElementById('articlehold');
		//	var parallax = new Parallax(scene);
			var $parallax = $('#articlehold').parallax();
			$parallax.parallax('updateLayers');
		<?php }?>
  		var siteString='';
		var hash = window.location.hash;
		var findme = "!slide-";
		var n = $("section").length;
		siteString = hash.replace ( /[^\d.]/g, '' );
		if(siteString && hash.indexOf(findme) > -1){
			
			if(n <= siteString){
				document.removeEventListener('keydown', gokb);
				setTimeout( function(){
				window.initajax()},10)
				
			}
			deck.slide(siteString);
		}
	}
	//Display wellcome buble (use cookie to show only once)
	//==================================================
	function initInstructions() {
		if (isTouch()) {
			$('body').addClass('forios');
		}
		/*function setCookie(c_name,value,exdays){
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
			window.bopen = 2;
			var bubleopen = Number(getCookie("welcomemsg"));
			if(bubleopen !== 1){
			
				$( document ).ready( 
				function() {
					window.bopen = 1;
					$("#ss-container").unbind("mousewheel DOMMouseScroll");
					
					showInstructions()
					instructionsTimeout=setTimeout(showInstructions, 2000);
				}
				 )

			}
		}	
		*/
	}
	//Small bottom navigation
	//==================================================
	function initButtons() {
		document.getElementById('next-arrow').addEventListener('click',goprev );
		document.getElementById('prev-arrow').addEventListener('click',gonext );
	}
	function gonext(){
		deck.next();
		var n = $("section").length;
		$('section').each(function(){
			if( $(this).hasClass('bespoke-active') && Number($(this).attr('rel'))+1 ===n){
				<?php if($ab_amy_settings['def-pagination-display'] == "2"){?>
				if(window.initajax() !== false){
					document.removeEventListener('keydown', gokb);
					document.getElementById('next-arrow').removeEventListener('click', gonext);
				}
				<?php };?>
			}
		});
	}
	function goprev(){
		deck.prev();
		var n = $("section").length;
		$('section').each(function(){
			if( $(this).hasClass('bespoke-active') && Number($(this).attr('rel'))+1 ===n){
				<?php if($ab_amy_settings['def-pagination-display'] == "2"){?>
				if(window.initajax() !== false){
					document.removeEventListener('keydown', gokb);
					document.getElementById('next-arrow').removeEventListener('click', gonext);
				}
				<?php };?>
			}
		});
	}
	//Keyboard navigation
	//==================================================
	function initKeys(e) {
		document.getElementById('next-arrow').removeEventListener('click', gonext);
		if (/Firefox/.test(navigator.userAgent)) {
			document.addEventListener('keydown', function(e) {
				if (e.which >= 37 && e.which <= 40) {
					e.preventDefault();
				}
			});
		}
		window.gokb = function(e) {
			if(window.bopen === 1){
				hideInstructions();	
				window.bopen = 2;
			}
			var key = e.which;
			
			if(key === 37){
				window.clearInterval(autorotateposts);
				deck.prev();
			}
			if(key === 39 ){
				window.clearInterval(autorotateposts);
				deck.next();
			}
			var n = $("section").length;
			$('section').each(function(){
				if( $(this).hasClass('bespoke-active') && Number($(this).attr('rel'))+1 ===n){
					<?php if($ab_amy_settings['def-pagination-display'] == "2"){?>
						if(window.initajax() !== false){
							document.removeEventListener('keydown', gokb);
						}
					<?php }; ?>
					}
				});
			};
		document.addEventListener('keydown', gokb);
	}
	
	function extractDelta(e) {
		if (e.wheelDelta) {
			return e.wheelDelta;
		}
		if (e.originalEvent.detail) {
			return e.originalEvent.detail* -40;
		}
		if (e.originalEvent && e.originalEvent.wheelDelta) {
			return e.originalEvent.wheelDelta;
		}
	}
	//Mouse wheel navigation
	//==================================================
    window.gomouse = function gomousewheel(){
		var n = $("section").length;
		$('section').each(function(){
			if( $(this).hasClass('bespoke-active') && Number($(this).attr('rel'))+1 ===n && jQuery(document).width() > 530){
				<?php if($ab_amy_settings['def-pagination-display'] == "2"){?>
				if(window.initajax() === false){
					document.addEventListener('keydown', gokb);
				}else{
					$("#ss-container").unbind("mousewheel DOMMouseScroll");
					document.removeEventListener('keydown', gokb);
				}
				<?php }; ?>
			}
		});
		if(jQuery(document).width() < 530){
			if(jQuery(window).scrollTop() > jQuery(document).height() - jQuery(window).height()-150){
				<?php if($ab_amy_settings['def-pagination-display'] == "2"){?>
				if(window.initajax() === false){
					document.addEventListener('keydown', gokb);
				}else{
					$("#ss-container").unbind("mousewheel DOMMouseScroll");
					document.removeEventListener('keydown', gokb);
				}
				<?php };?>
			}
		}
		$('#ss-container').bind('mousewheel DOMMouseScroll', function(e){
			if(extractDelta(e) > 0) {
			$("#ss-container").unbind("mousewheel DOMMouseScroll");
				setTimeout(prevp, 200); 
			}
			if(extractDelta(e) < 0) {
			$("#ss-container").unbind("mousewheel DOMMouseScroll");
				setTimeout(nextp, 200);
			}
		});
		function prevp(){
			window.clearInterval(autorotateposts);
			deck.prev();
			setTimeout( gomousewheel, 200);  
		}
		function nextp(){
			window.clearInterval(autorotateposts);
			deck.next();
			setTimeout( gomousewheel, 200);  
		}
	};
	if(window.openfirst === 1){
		window.gomouse();
	}
	//Navigation for touch devices
	//==================================================
	function initSlideGestures() {
		var start = 0;
		var main = document.getElementById('main'),
			startPosition,
			delta,
			
			singleTouch = function(fn, preventDefault) {
				return function(e) {
					if(e.touches.length === 1){
						fn(e.touches[0].pageX);
					}
				};
			},
			touchstart = singleTouch(function(position) {
				startPosition = position;
				delta = 0;
					start = 0;
					main.addEventListener('touchend', touchend); 
			}),

			touchmove = singleTouch(function(position) {
				delta = position - startPosition;
			}, true),
			
			touchend = function() {	
				
			if(jQuery(document).width() < 530){
					if(jQuery(window).scrollTop() > jQuery(document).height() - jQuery(window).height()-130){
						<?php if($ab_amy_settings['def-pagination-display'] == "2"){?>
						if(window.initajax() === false){
							main.addEventListener('touchstart', touchstart);
							main.addEventListener('touchmove', touchmove);
							main.addEventListener('touchend', touchend);
						}else{
							main.removeEventListener('touchstart', touchstart);
							main.removeEventListener('touchmove', touchmove);
							main.removeEventListener('touchend', touchend);
						}
						<?php }; ?>
					}
				}	
				if (Math.abs(delta) < 50) {
					return;
				}
				if(delta > 0){
					setTimeout(deck.prev, 400);
				}else{
					setTimeout(deck.next, 400);
				}
				var n = $("section").length;
				$('section').each(function(){
					if( $(this).hasClass('bespoke-active') && Number($(this).attr('rel'))+1 ===n && jQuery(document).width() > 530){
						<?php if($ab_amy_settings['def-pagination-display'] == "2"){?>
						if(window.initajax() === false){
							main.addEventListener('touchstart', touchstart);
							main.addEventListener('touchmove', touchmove);
							main.addEventListener('touchend', touchend);
						}else{
							main.removeEventListener('touchstart', touchstart);
							main.removeEventListener('touchmove', touchmove);
							main.removeEventListener('touchend', touchend);
						}
						<?php }; ?>
					}
				});
				
			};
		window.remvoetuch = function(){
			main.removeEventListener('touchstart', touchstart);
			main.removeEventListener('touchmove', touchmove);
			main.removeEventListener('touchend', touchend);
		};
		window.addtuch = function(){
			main.addEventListener('touchstart', touchstart);
			main.addEventListener('touchmove', touchmove);
			main.addEventListener('touchend', touchend);
		};
		window.addtuch();
	}
	//Show hide wellcome bubble
	//==================================================
	function showInstructions() {
		$('#ss-container').addClass('addblur');
		$('#footer').addClass('addblur');
		$('#dl-menu').addClass('addblur');
		$('.right-bottom-nav').addClass('addblur');
		$('.opentip-container').addClass('addblur');
		$('.addbg').addClass('addbgv');
		$('.addbg').click(function() {
			if(window.bopen === 1){
				hideInstructions();	
				window.bopen = 2;
			}
			$(this).unbind("click");
		});
		document.querySelectorAll('header .welcome-b')[0].className = 'welcome-b visible animated fadeInUp';
	}
	function hideInstructions() {
		window.gomouse();
		$('#ss-container').removeClass('addblur');
		$('#footer').removeClass('addblur');
		$('#dl-menu').removeClass('addblur');
		$('.right-bottom-nav').removeClass('addblur');
		$('.opentip-container').removeClass('addblur');
		$('.addbg').removeClass('addbgv');
		clearTimeout(instructionsTimeout);
		document.querySelectorAll('header .welcome-b')[0].className = 'welcome-b';
	}

	function isTouch() {
		return !!('ontouchstart' in window) || navigator.msMaxTouchPoints;
	}

	function modulo(num, n) {
		return ((num % n) + n) % n;
	}
	//Mouse click navigation
	//==================================================
	function initClickInactive(){
		$(".tt-cn-style").unbind("click");
		var main = document.getElementById('main');
		var n = $("section").length;
		window.lastslide = n;
		$('.tt-cn-style').click(function() {
			window.clearInterval(autorotateposts);
			var page = $(this).parent().attr('rel');
			var count = Number(page)+1;
			if( $(this).parent().hasClass('bespoke-inactive') ){
			
				if(count === n){
					<?php if($ab_amy_settings['def-pagination-display'] == "2"){?>
					if(window.initajax() === false){
						document.addEventListener('keydown', gokb);
						window.remvoetuch();
						initSlideGestures();
					}else{
						document.removeEventListener('keydown', gokb);
						window.remvoetuch();
					}
					<?php }; ?>
				}
			deck.slide(page);
			}
		});
	}
	function loopreset(){
		$('a').live('touchend', function(e) {
			var el = $(this);
			var link = el.attr('href');
		});
		$("a[rel^='prettyPhotoImages']").prettyPhoto({theme: 'dark_square',allow_resize: true});	 
	}
	 var autorotateposts, stopnextslide;
	 <?php if($ab_amy_settings['amy-slider-autorotate'] == true){ ?> 
			function autoslide(){
				stopnextslide = 0;
				var n = $('section').length;
				
				$('section').each(function () {
					if ($(this).hasClass('bespoke-active') && Number($(this).attr('rel'))+1 == n) {
						deck.slide(0);
						stopnextslide = 1;
					}
				});
				if(stopnextslide != 1){
					deck.next();
				}
			}
			$('section').hover(function() {
				window.clearInterval(autorotateposts);
			}, function(){
					window.clearInterval(autorotateposts);
					
					 autorotateposts = setInterval(autoslide, <?php echo $ab_amy_settings['amy-slider-autorotate-delay']?>);
			})
			var autorotateposts = setInterval(autoslide , <?php echo $ab_amy_settings['amy-slider-autorotate-delay']?>);
				
		<?php }; ?>
});
</script>

<script>
(function(moduleName, window, document) {
	var from = function(selector, selectedPlugins) {
		
			var parent = document.querySelector(selector),
				slides = [].slice.call(parent.children, 0),
				activeSlide = slides[0],
				deckListeners = {},
				relnum = 0,
				isfirst = '0',

				activate = function(index, customData) {
					if (!slides[index]) {
						return;
					}
					//var isfirst = '0';
					fire(deckListeners, 'deactivate', createEventData(activeSlide, customData));

					activeSlide = slides[index];

					slides.map(deactivate);

					fire(deckListeners, 'activate', createEventData(activeSlide, customData));				
					addClass(activeSlide, 'active');
					removeClass(activeSlide, 'inactive');
					if ( jQuery.browser.msie && jQuery.browser.version == '9.0' ) { 
					jQuery( " section.bespoke-before-3" ).animate({ "margin-left": "-1400px" },300, "linear" );
					jQuery( " section.bespoke-before-2" ).animate({ "margin-left": "-966px" },300, "linear" );
					jQuery( " section.bespoke-before-1" ).animate({ "margin-left": "-570px" }, 300, "linear" );
					jQuery( " section.bespoke-active" ).animate({ "margin-left": "-175px" }, 300, "linear" );
					jQuery( " section" ).css("opacity","1");
					if(isfirst !=1){
					jQuery( " section.bespoke-after" ).css("margin-left","2000px");
					 isfirst = 1
					}
					jQuery( " section.bespoke-after-1" ).animate({ "margin-left": "220px" },300, "linear");
					jQuery( " section.bespoke-after-2" ).animate({ "margin-left": "616px" },300, "linear" );
					jQuery( " section.bespoke-after-3" ).animate({ "margin-left": "1200px" }, 300, "linear" );
					}
				},

				deactivate = function(slide, index) {
					
					var offset = index - slides.indexOf(activeSlide),
						offsetClass = offset > 0 ? 'after' : 'before';

					['before(-\\d+)?', 'after(-\\d+)?', 'active', 'inactive'].map(removeClass.bind(null, slide));
					slide !== activeSlide &&
						['inactive', offsetClass, offsetClass + '-' + Math.abs(offset)].map(addClass.bind(null, slide));
			
				
						
				},

				slide = function(index, customData) {
					fire(deckListeners, 'slide', createEventData(slides[index], customData)) && activate(index, customData);
					
				},

				next = function(customData) {
					var nextSlideIndex = slides.indexOf(activeSlide) + 1;
					
			
					fire(deckListeners, 'next', createEventData(activeSlide, customData)) && activate(nextSlideIndex, customData);
					if ( jQuery.browser.msie && jQuery.browser.version == '9.0' ) {
				//	jQuery( " section.bespoke-before-3" ).animate({ "margin-left": "-1900px" },300, "linear" );
					jQuery( " section.bespoke-before-3" ).animate({ "margin-left": "-1400px" },300, "linear" );
					jQuery( " section.bespoke-before-2" ).animate({ "margin-left": "-966px" },300, "linear" );
					jQuery( " section.bespoke-before-1" ).animate({ "margin-left": "-570px" }, 300, "linear" );
					
					jQuery( " section.bespoke-after-1" ).animate({ "margin-left": "220px" },300, "linear");
					jQuery( " section.bespoke-after-2" ).animate({ "margin-left": "616px" },300, "linear" );
					jQuery( " section.bespoke-after-3" ).animate({ "margin-left": "1200px" }, 300, "linear" );
					jQuery( " section.bespoke-after-4" ).animate({ "margin-left": "1700px" }, 300, "linear" );
					}
					
					 window.location.hash = '!slide-'+jQuery( "section.bespoke-active" ).attr("rel");
					 
					// window.location.hash = 'post-'+jQuery( "section.bespoke-active" ).find(".content-title").text();
				},

				prev = function(customData) {
					var prevSlideIndex = slides.indexOf(activeSlide) - 1;

					fire(deckListeners, 'prev', createEventData(activeSlide, customData)) && activate(prevSlideIndex, customData);
					if ( jQuery.browser.msie && jQuery.browser.version == '9.0' ) { 
					jQuery( " section.bespoke-before-3" ).animate({ "margin-left": "-1400px" },300, "linear" );
					jQuery( " section.bespoke-before-2" ).animate({ "margin-left": "-966px" },300, "linear" );
					jQuery( " section.bespoke-before-1" ).animate({ "margin-left": "-570px" }, 300, "linear" );
					
					jQuery( " section.bespoke-after-1" ).animate({ "margin-left": "220px" },300, "linear");
					jQuery( " section.bespoke-after-2" ).animate({ "margin-left": "616px" },300, "linear" );
					jQuery( " section.bespoke-after-3" ).animate({ "margin-left": "1200px" }, 300, "linear" );
					jQuery( " section.bespoke-after-4" ).animate({ "margin-left": "1700px" }, 300, "linear" );
					}
					
					 window.location.hash = '!slide-'+jQuery( "section.bespoke-active" ).attr("rel");
					
					//window.location.hash = jQuery( "section.bespoke-active" ).attr("rel");

				},

				createEventData = function(slide, eventData) {
					eventData = eventData || {};
					eventData.index = slides.indexOf(slide);
					eventData.slide = slide;
					return eventData;
				},

				deck = {
					on: on.bind(null, deckListeners),
					off: off.bind(null, deckListeners),
					fire: fire.bind(null, deckListeners),
					slide: slide,
					next: next,
					prev: prev,
					parent: parent,
					slides: slides
				};
			
			addClass(parent, 'parent');
			
			slides.map(function(slide) {
				
				addClass(slide, 'slide');
				jQuery(slide).attr('rel', relnum);
				relnum++;
			});

			Object.keys(selectedPlugins || {}).map(function(pluginName) {
				var config = selectedPlugins[pluginName];
				config && plugins[pluginName](deck, config === true ? {} : config);
				
			});

			activate(0);

			decks.push(deck);

			return deck;
		},

		decks = [],

		bespokeListeners = {},

		on = function(listeners, eventName, callback) {
			(listeners[eventName] || (listeners[eventName] = [])).push(callback);
		},

		off = function(listeners, eventName, callback) {
			listeners[eventName] = (listeners[eventName] || []).filter(function(listener) {
				return listener !== callback;
			});
		},

		fire = function(listeners, eventName, eventData) {
			return (listeners[eventName] || [])
				.concat((listeners !== bespokeListeners && bespokeListeners[eventName]) || [])
				.reduce(function(notCancelled, callback) {
					return notCancelled && callback(eventData) !== false;
				}, true);
		},

		addClass = function(el, cls) {
			el.classList.add(moduleName + '-' + cls);
		},

		removeClass = function(el, cls) {
			el.className = el.className
				.replace(new RegExp(moduleName + '-' + cls +'(\\s|$)', 'g'), ' ')
				.replace(/^\s+|\s+$/g, '');
		},

		callOnAllInstances = function(method) {
			return function(arg) {
				decks.map(function(deck) {
					deck[method].call(null, arg);
				});
			};
		},

		bindPlugin = function(pluginName) {
			return {
				from: function(selector, selectedPlugins) {
					(selectedPlugins = selectedPlugins || {})[pluginName] = true;
					return from(selector, selectedPlugins);
				}
			};
		},

		makePluginForAxis = function(axis) {
			return function(deck) {
				var startPosition,
					delta;

				document.addEventListener('keydown', function(e) {
					var key = e.which;

					if (axis === 'X') {
						key === 37 && deck.prev();
						(key === 32 || key === 39) && deck.next();
					} else {
						key === 38 && deck.prev();
						(key === 32 || key === 40) && deck.next();
					}
				});

				deck.parent.addEventListener('touchstart', function(e) {
					if (e.touches.length) {
						startPosition = e.touches[0]['page' + axis];
						delta = 0;
					}
				});

				deck.parent.addEventListener('touchmove', function(e) {
					if (e.touches.length) {
						e.preventDefault();
						delta = e.touches[0]['page' + axis] - startPosition;
					}
				});

				deck.parent.addEventListener('touchend', function() {
					Math.abs(delta) > 50 && (delta > 0 ? deck.prev() : deck.next());
				});
			};
		},

		plugins = {
			horizontal: makePluginForAxis('X'),
			vertical: makePluginForAxis('Y')
		};

	window[moduleName] = {
		from: from,
		slide: callOnAllInstances('slide'),
		next: callOnAllInstances('next'),
		prev: callOnAllInstances('prev'),
		horizontal: bindPlugin('horizontal'),
		vertical: bindPlugin('vertical'),
		on: on.bind(null, bespokeListeners),
		off: off.bind(null, bespokeListeners),
		plugins: plugins
	};

}('bespoke', this, document));
</script>




