(function($){

    "use strict";
	
    /* ---------------------------------------------------------------------------
	 * Sticky header
	 * --------------------------------------------------------------------------- */
    var topBarTop = '61px';
    
	function mfn_sticky(){
		if( $('body').hasClass('header-below') ){
	    	// header below slider
	    	var mfn_header_height = $('.mfn-main-slider').innerHeight() + $('#Header').innerHeight();
	    } else {
	    	// default
	    	var mfn_header_height = $('#Top_bar').innerHeight() + $('#Action_bar').innerHeight();
	    }	
		
		if( $('body').hasClass('sticky-header') ){	
			var start_y = mfn_header_height;
			var window_y = $(window).scrollTop();
	
			if( window_y > start_y ){
				if( ! ($('#Top_bar').hasClass('is-sticky'))) {
					
					$('.header-classic .header_placeholder').css('height', $('#Top_bar').innerHeight() - $('#Action_bar').innerHeight());
					$('.header-stack   .header_placeholder').css('height', 71);
					$('.header-below   .header_placeholder').css('height', $('#Top_bar').innerHeight());
					$('.minimalist-header .header_placeholder').css('height', $('#Top_bar').innerHeight());
					
					$('#Top_bar')
						.addClass('is-sticky')
						.css('top',-60)
						.animate({
							'top': $('#wpadminbar').innerHeight()
						},300);
				}
			}
			else {
				if($('#Top_bar').hasClass('is-sticky')) {
					$('.header_placeholder').css('height',0);
					$('#Top_bar')
						.removeClass('is-sticky')
						.css('top', topBarTop);
				}	
			}
		}
	}
	
	
	/* ---------------------------------------------------------------------------
	 * Sidebar height
	 * --------------------------------------------------------------------------- */
	function mfn_sidebar(){
		if( $('.with_aside .four.columns').length ){
			var maxH = $('#Content .sections_group').height() - 20
			$('.with_aside .four.columns .widget-area').each(function(){
				if( $(this).height() > maxH ){
					maxH = $(this).height();
				}
			});
			$('.with_aside .four.columns .widget-area').css( 'min-height', maxH + 'px' );
		}
	}
	
	
	/* ---------------------------------------------------------------------------
	 * Sliding Footer | Height
	 * --------------------------------------------------------------------------- */
	function mfn_footer(){
		if( $('.footer-fixed #Footer, .footer-sliding #Footer').length ){
			var footerH = $('#Footer').height();
			$('#Content').css( 'margin-bottom', footerH + 'px' );
		}
	}
	
	
	/* ---------------------------------------------------------------------------
	 * Header width
	 * --------------------------------------------------------------------------- */
	function mfn_header(){
		var rightW = $('.top_bar_right').innerWidth();
		var parentW = $('#Top_bar .one').innerWidth() - 10;
		var leftW = parentW - rightW;
		$('.top_bar_left, .menu > li > ul.mfn-megamenu ').width( leftW );
	}
	
	
	/* ---------------------------------------------------------------------------
	 * Full Screen Section
	 * --------------------------------------------------------------------------- */
	function mfn_sectionH(){
		var windowH = $(window).height();
		$('.section.full-screen').each(function(){
			var section = $(this);
			var wrapper = $('.section_wrapper',section);
			section
				.css('padding', 0)
				.height( windowH );
			var padding = ( windowH - wrapper.height() ) / 2;
			wrapper.css('padding-top', padding + 20);			// 20 = column margin-bottom / 2
		});
	}
	
	
	/* ---------------------------------------------------------------------------
	 * # Hash smooth navigation
	 * --------------------------------------------------------------------------- */
	function hashNav(){
		
		// # window.location.hash
		var hash = window.location.hash;
		
		if( hash && $(hash).length ){	
			
			var stickyH = $('.sticky-header #Top_bar').innerHeight();
			var tabsHeaderH = $(hash).siblings('.ui-tabs-nav').innerHeight();
			
			$('html, body').animate({ 
				scrollTop: $(hash).offset().top - stickyH - tabsHeaderH
			}, 500);
		}
	}
	
	
	/* ---------------------------------------------------------------------------
	 * One Page | Scroll Active
	 * --------------------------------------------------------------------------- */
	function onePageActive(){
		
		var stickyH = $('.sticky-header #Top_bar').innerHeight();

		var winTop = $(window).scrollTop();
		var offset = stickyH + 1; // offset top

        var visible = $('[data-id]').filter(function(ndx, div){
            return winTop >= $(div).offset().top - offset &&
            winTop < $(div).offset().top - offset + $(div).outerHeight()
        });
        
        var newActive = visible.first().attr('id');        
        var active = '[data-hash="#'+ newActive +'"]';
        
        var menu = $('#menu');
        menu.find('li').removeClass('current-menu-item currenet-menu-parent current-menu-ancestor current_page_item current_page_parent current_page_ancestor');
        $( active, menu ).closest('li').addClass('current-menu-item');
	}
	
	
	/* ---------------------------------------------------------------------------
	 * niceScroll | Padding right fix for short content
	 * --------------------------------------------------------------------------- */
	function niceScrollFix(){
		var el = $('body > .nicescroll-rails');
		if( el.length ){
			if( el.is(":visible") ){
				$('body').addClass('nice-scroll');
			} else {
				$('body').removeClass('nice-scroll');
			}
		}
	}

	
	/* --------------------------------------------------------------------------------------------------------------------------
	 * $(document).ready
	 * ----------------------------------------------------------------------------------------------------------------------- */
	$(document).ready(function(){
	
		topBarTop = parseInt($('#Top_bar').css('top'), 10);
		if( topBarTop < 0 ) topBarTop = 61;
		topBarTop = topBarTop + 'px';


		/* ---------------------------------------------------------------------------
		 * Content sliders
		 * --------------------------------------------------------------------------- */
		mfnSliderContent();
		mfnSliderOffer();
		mfnSliderOfferThumb();
		mfnSliderBlog();
		mfnSliderClients();
		mfnSliderPortfolio();
		mfnSliderShop();
		mfnSliderTestimonials();
		
		
		/* ---------------------------------------------------------------------------
		 * Responsive menu
		 * --------------------------------------------------------------------------- */
		$('.responsive-menu-toggle').click(function(e){
			e.preventDefault();
			var el = $(this)
			var menu = $('#Top_bar #menu');
			var menuWrap = menu.closest('.menu_wrapper');
			el.toggleClass('active');
			
			if( el.hasClass('is-sticky') && el.hasClass('active') ){
				var top = 0;
				if( menuWrap.length ) top = menuWrap.offset().top;
				$('body,html').animate({
					scrollTop: top
				}, 200);
			}

			menu.stop(true,true).slideToggle(200);
		});
		
		
		/* ---------------------------------------------------------------------------
		 * Main menu
		 * --------------------------------------------------------------------------- */
		
		// Muffin Menu -------------------------------
		$("#menu > ul.menu").muffingroup_menu({
			arrows	: true
		});
		
		$("#secondary-menu > ul.secondary-menu").muffingroup_menu();
		
		mfn_sticky();

		
		/* ---------------------------------------------------------------------------
		 * Menu | OnePage - remove active
		 * Works with .scroll class
		 * Since 4.8 replaced with admin option: Page Options / One Page [function: onePageMenu()]
		 * --------------------------------------------------------------------------- */
		function onePageScroll(){
			if( ! $('body').hasClass('one-page') ){
				var menu = $('#menu');
				
				if( menu.find('li.scroll').length > 1 ){
					menu.find('li.current-menu-item:not(:first)').removeClass('current-menu-item currenet-menu-parent current-menu-ancestor current_page_item current_page_parent current_page_ancestor');
					
					// menu item click
					menu.find('a').click(function(){
						$(this).closest('li').siblings('li').removeClass('current-menu-item currenet-menu-parent current-menu-ancestor current_page_item current_page_parent current_page_ancestor');
						$(this).closest('li').addClass('current-menu-item');
					});
				}
			}
		}
		onePageScroll();
		
		
		/* ---------------------------------------------------------------------------
		 * One Page | Menu with Active on Scroll
		 * --------------------------------------------------------------------------- */
		function onePageMenu(){
			if( $('body').hasClass('one-page') ){
				
				var menu = $('#menu');
				
				// remove active
				menu.find('li').removeClass('current-menu-item currenet-menu-parent current-menu-ancestor current_page_item current_page_parent current_page_ancestor');

				// add attr [data-hash]
				$('a[href]', menu).each(function(){	

					// data-hash
					var url = $(this).attr('href');
					var hash = '#' + url.split('#')[1];
					if( hash && $(hash).length ){	// check if element with specified ID exists
						$(this).attr('data-hash',hash);
						$(hash).attr('data-id',hash);
					}
					
				});
				
				// click
				$('#menu a[data-hash]').click(function(e){
					e.preventDefault(); // only with: body.one-page
					
					// active
					menu.find('li').removeClass('current-menu-item');
					$(this).closest('li').addClass('current-menu-item');
	
					var hash = $(this).attr('data-hash');
					var stickyH = $('.sticky-header #Top_bar').innerHeight();
					var tabsHeaderH = $(hash).siblings('.ui-tabs-nav').innerHeight();
					
					$('html, body').animate({ 
						scrollTop: $(hash).offset().top - stickyH - tabsHeaderH
					}, 500);
					
				});
				
			}
		};
		onePageMenu();

		
		/* ---------------------------------------------------------------------------
		 * Creative Header
		 * --------------------------------------------------------------------------- */
		function creativeHeader(){
			var ch = $('body:not(.header-open) #Header_creative');
			var current;
			
			$('.creative-menu-toggle').click(function(e){
				e.preventDefault();
				ch.addClass('active').animate({ 'left':-1 },500);
				ch.find('.creative-wrapper').fadeIn(500);
				ch.find('.creative-menu-toggle, .creative-social').fadeOut(500);
			});
			
			ch.live('mouseenter', function() {    
			    current = 1;
			}).live('mouseleave', function() {
			    current = null;
			    setTimeout(function(){
			    	if ( ! current ){
				    	ch.removeClass('active').animate({ 'left':-200 },500);
						ch.find('.creative-wrapper').fadeOut(500);
						ch.find('.creative-menu-toggle, .creative-social').fadeIn(500);
			    	}
			    }, 1000);
			});
			
		}
		creativeHeader();

		
		/* ---------------------------------------------------------------------------
		 * Maintenance
		 * --------------------------------------------------------------------------- */
        $('.downcount').each(function(){
            var el = $(this);
           	el.downCount({
    			date	: el.attr('data-date'),
    			offset	: el.attr('data-offset')
    		});  
        }); 
        
        
        /* ---------------------------------------------------------------------------
         * Tooltip Image
         * --------------------------------------------------------------------------- */
        $('.tooltip-img').hover(function(){
        	 $('.tooltip-content').stop(true,true).show();
        },function(){
        	$('.tooltip-content').stop(true,true).hide();
        });


        /* ---------------------------------------------------------------------------
		 * niceScroll
		 * --------------------------------------------------------------------------- */
        if( $('body').hasClass('nice-scroll-on') 
        	&& $(window).width() > 767
        	&& ! navigator.userAgent.match(/(Android|iPod|iPhone|iPad|IEMobile|Opera Mini)/))
        {
        	$('html').niceScroll({
        		autohidemode		: false,
        		cursorborder		: 0,
        		cursorborderradius	: 5,
        		cursorcolor			: '#222222',
        		cursorwidth			: 10,
        		horizrailenabled	: false,
        		mousescrollstep		: ( window.mfn_nicescroll ) ? window.mfn_nicescroll : 40,
        		scrollspeed			: 60
        	});
        	
        	$('body').removeClass('nice-scroll-on').addClass('nice-scroll');
        	niceScrollFix();
	    }

        
        /* ---------------------------------------------------------------------------
		 * WP Gallery
		 * --------------------------------------------------------------------------- */
		$('.gallery-icon > a')
			.wrap('<div class="image_frame scale-with-grid"><div class="image_wrapper"></div></div>')
			.prepend('<div class="mask"></div>')
			.attr('rel', 'prettyphoto[gallery]')
			.children('img' )
				.css('height', 'auto')
				.css('width', '100%');
		

		/* ---------------------------------------------------------------------------
		 * PrettyPhoto
		 * --------------------------------------------------------------------------- */
		if( ( ! window.mfn_prettyphoto.disable ) && ( $(window).width() >= 768 ) ){
			$('a[rel^="prettyphoto"], .prettyphoto').prettyPhoto({
				default_width	: window.mfn_prettyphoto.width ? window.mfn_prettyphoto.width : 500,
				default_height	: window.mfn_prettyphoto.height ? window.mfn_prettyphoto.height : 344,
				show_title		: false,
				deeplinking		: false,
				social_tools	: false
			});
		}
		
        
		/* ---------------------------------------------------------------------------
		 * Black & White
		 * --------------------------------------------------------------------------- */
        $('.greyscale .image_wrapper > a, .greyscale .client_wrapper .gs-wrapper, .greyscale.portfolio-photo a').has('img').BlackAndWhite({
    		hoverEffect		: true,
    		intensity		: 1			// opacity: 0, 0.1, ... 1
    	});
		

		/* ---------------------------------------------------------------------------
		 * Sliding Top
		 * --------------------------------------------------------------------------- */
		$(".sliding-top-control").click(function(e){
			e.preventDefault();
			$('#Sliding-top .widgets_wrapper').slideToggle();
			$('#Sliding-top').toggleClass('active');
		});
		
		
		/* ---------------------------------------------------------------------------
		 * Header Search
		 * --------------------------------------------------------------------------- */
		$("#search_button, #Top_bar .icon_close").click(function(e){
			e.preventDefault();
			$('#Top_bar .search_wrapper').fadeToggle();
		});
	
		
		/* ---------------------------------------------------------------------------
		 * Alert
		 * --------------------------------------------------------------------------- */
		$('.alert .close').click(function(e){
			e.preventDefault();
			$(this).closest('.alert').hide(300);
		});
		
		
		/* ---------------------------------------------------------------------------
		 * Buttons - mark Buttons with Icon & Label
		 * --------------------------------------------------------------------------- */
		$('a.button_js').each(function(){
			var btn = $(this);
			if( btn.find('.button_icon').length && btn.find('.button_label').length ){
				btn.addClass('kill_the_icon');
			}
		});
		
		
		/* ---------------------------------------------------------------------------
		 * Posts sticky navigation
		 * --------------------------------------------------------------------------- */
		$('.fixed-nav').appendTo('body');
		
		
		/* ---------------------------------------------------------------------------
		 * Feature List
		 * --------------------------------------------------------------------------- */
		$('.feature_list ul li:nth-child(4n):not(:last-child)').after('<hr />');
		
		
		/* ---------------------------------------------------------------------------
		 * IE fixes
		 * --------------------------------------------------------------------------- */
		function checkIE(){
			// IE 9
			var ua = window.navigator.userAgent;
	        var msie = ua.indexOf("MSIE ");
	        if( msie > 0 && parseInt(ua.substring(msie + 5, ua.indexOf(".", msie))) == 9 ){
	        	$("body").addClass("ie");
			}
		}
		checkIE();
		
		
		/* ---------------------------------------------------------------------------
		 * Paralex Backgrounds
		 * --------------------------------------------------------------------------- */
		var ua = navigator.userAgent,
		isMobileWebkit = /WebKit/.test(ua) && /Mobile/.test(ua);
		if( ! isMobileWebkit && $(window).width() >= 768 ){
			$.stellar({
				horizontalScrolling	: false,
				responsive			: true
			});
		}
		
		
		/* ---------------------------------------------------------------------------
		 * Ajax | Load More
		 * --------------------------------------------------------------------------- */
		$('.pager_load_more').click(function(e){
			e.preventDefault();
			
			var el = $(this);
			var pager = el.closest('.pager_lm');
			var href = el.attr('href');
			
			// index | for many items on the page
			var index = $('.lm_wrapper').index(el.closest('.isotope_wrapper').find('.lm_wrapper'));

			el.fadeOut(50);
			pager.addClass('loading');
			
			$.get( href, function(data){

				// content
				var content = $('.lm_wrapper:eq('+ index +')', data).wrapInner('').html();

				if( $('.lm_wrapper:eq('+ index +')').hasClass('isotope') ){
					// isotope
					$('.lm_wrapper:eq('+ index +')').append( $(content) ).isotope( 'reloadItems' ).isotope({ sortBy: 'original-order' });
				} else {
					// default
					$( content ).hide().appendTo('.lm_wrapper:eq('+ index +')').fadeIn(1000);
				}
				
				// next page link
				href = $( '.pager_load_more:eq('+ index +')', data ).attr('href');		
				pager.removeClass('loading');					
				if( href ){
					el.fadeIn();
					el.attr( 'href', href );
				}

				// refresh some staff -------------------------------
				mfn_jPlayer();
				iframesHeight();	
				mfn_sidebar();
				
				// isotope fix: second resize
				setTimeout(function(){
					$('.lm_wrapper.isotope').isotope( 'reLayout');
				},1000);				
				
			});

		});
	
		
		/* ---------------------------------------------------------------------------
		 * Blog & Portfolio filters
		 * --------------------------------------------------------------------------- */
		$('.filters_buttons .open').click(function(e){
			e.preventDefault();
			var type = $(this).closest('li').attr('class');
			$('.filters_wrapper').show(200);
			$('.filters_wrapper ul.'+ type).show(200);
			$('.filters_wrapper ul:not(.'+ type +')').hide();
		});
		
		$('.filters_wrapper .close a').click(function(e){
			e.preventDefault();
			$('.filters_wrapper').hide(200);
		});
		
		
		/* ---------------------------------------------------------------------------
		 * Portfolio List - next/prev buttons
		 * --------------------------------------------------------------------------- */
		$('.portfolio_next_js').click(function(e){
			e.preventDefault();
			
			var stickyH = $('#Top_bar.is-sticky').innerHeight();
			var item = $(this).closest('.portfolio-item').next();
			
			if( item.length ){
				$('html, body').animate({ 
					scrollTop: item.offset().top - stickyH
				}, 500);
			}
		});
		
		$('.portfolio_prev_js').click(function(e){
			e.preventDefault();
			
			var stickyH = $('#Top_bar.is-sticky').innerHeight();
			var item = $(this).closest('.portfolio-item').prev();
			
			if( item.length ){
				$('html, body').animate({ 
					scrollTop: item.offset().top - stickyH
				}, 500);
			}
		});
		
		
		/* ---------------------------------------------------------------------------
		 * Tabs
		 * --------------------------------------------------------------------------- */
		$(".jq-tabs").tabs();

		
		/* ---------------------------------------------------------------------------
		 * Smooth scroll
		 * --------------------------------------------------------------------------- */
		$('li.scroll > a, a.scroll').click(function(){
			var url = $(this).attr('href');
			var hash = '#' + url.split('#')[1];
			
			var stickyH = $('.sticky-header #Top_bar').innerHeight();
			var tabsHeaderH = $(hash).siblings('.ui-tabs-nav').innerHeight();
			
			if( hash && $(hash).length ){
				$('html, body').animate({ 
					scrollTop: $(hash).offset().top - stickyH - tabsHeaderH
				}, 500);
			}
		});

		
		/* ---------------------------------------------------------------------------
		 * Muffin Accordion & FAQ
		 * --------------------------------------------------------------------------- */
		$('.mfn-acc').each(function(){
			var el = $(this);
			
			if( el.hasClass('openAll') ){
				// show all -----------
				
				el.find('.question')
					.addClass("active")
					.children(".answer")
						.show();
				
			} else {
				// show one -----------
				
				var active_tab = el.attr('data-active-tab');
				if( el.hasClass('open1st') ) active_tab = 1;
				
				if( active_tab ){
					el.find('.question').eq( active_tab - 1 )
						.addClass("active")
						.children(".answer")
							.show();
				}
				
			}	
		});

		$(".mfn-acc .question > .title").click(function(){		
			if($(this).parent().hasClass("active")) {
				$(this).parent().removeClass("active").children(".answer").slideToggle(200);
			}
			else
			{
				if( ! $(this).closest('.mfn-acc').hasClass('toggle') ){
					$(this).parents(".mfn-acc").children().each(function() {
						if($(this).hasClass("active")) {
							$(this).removeClass("active").children(".answer").slideToggle(200);
						}
					});
				}
				$(this).parent().addClass("active");
				$(this).next(".answer").slideToggle(200);
			}
		});

		
		/* ---------------------------------------------------------------------------
		 * jPlayer
		 * --------------------------------------------------------------------------- */
		function mfn_jPlayer(){
			$('.mfn-jplayer').each(function(){
				var m4v = $(this).attr('data-m4v');
				var poster = $(this).attr('data-img');
				var swfPath = $(this).attr('data-swf');
				var cssSelectorAncestor = '#' + $(this).closest('.mfn-jcontainer').attr('id');
				
				$(this).jPlayer({
					ready	: function () {
						$(this).jPlayer('setMedia', {
							m4v		: m4v,
							poster	: poster
						});
					},
					play	: function () { // To avoid both jPlayers playing together.
						$(this).jPlayer('pauseOthers');
					},
					size: {
						cssClass	: 'jp-video-360p',
						width		: '100%',
						height		: '360px'
					},
					swfPath				: swfPath,
					supplied			: 'm4v',
					cssSelectorAncestor	: cssSelectorAncestor,
					wmode				: 'opaque'
				});
			});
		}
		mfn_jPlayer();
		
		
		/* ---------------------------------------------------------------------------
		 * Love
		 * --------------------------------------------------------------------------- */
		$('.mfn-love').click(function() {
			var el = $(this);
			if( el.hasClass('loved') ) return false;
			
			var post = {
				action: 'mfn_love',
				post_id: el.attr('data-id')
			};
			
			$.post(window.mfn_ajax, post, function(data){
				el.find('.label').html(data);
				el.addClass('loved');
			});

			return false;
		});	
		
		
		/* ---------------------------------------------------------------------------
		 * Go to top
		 * --------------------------------------------------------------------------- */	
		$('#back_to_top').click(function(){
			$('body,html').animate({
				scrollTop: 0
			}, 500);
			return false;
		});		
		
		
		/* ---------------------------------------------------------------------------
		 * Section navigation
		 * --------------------------------------------------------------------------- */	
		$('.section .section-nav').click(function(){
			var el = $(this);
			var section = el.closest('.section');

			if( el.hasClass('prev') ){
				// Previous Section -------------
				if( section.prev().length ){	
					jQuery('html, body').animate({
						scrollTop: section.prev().offset().top
					}, 500);
				}
			} else {
				// Next Section -----------------
				if( section.next().length ){	
					jQuery('html, body').animate({
						scrollTop: section.next().offset().top
					}, 500);
				}			
			}
		});
		
		
		/* ---------------------------------------------------------------------------
		 * WooCommerce
		 * --------------------------------------------------------------------------- */	
		function addToCart(){
			$('body').on('click', '.add_to_cart_button', function(){
				$(this)
					.closest('.product')
						.addClass('adding-to-cart')
						.removeClass('added-to-cart');
			});

			$('body').bind('added_to_cart', function() {
				$('.adding-to-cart')
					.removeClass('adding-to-cart')
					.addClass('added-to-cart');
			});
		}
		addToCart();
		
		
		/* ---------------------------------------------------------------------------
		 * Iframe height
		 * --------------------------------------------------------------------------- */		
		function iframeHeight( item, ratio ){
			var itemW = item.width();
			var itemH = itemW * ratio;
			if( itemH < 147 ) itemH = 147;
			item.height(itemH);
		}
		
		function iframesHeight(){
			iframeHeight($(".blog_wrapper .post-photo-wrapper .mfn-jplayer, .blog_wrapper .post-photo-wrapper iframe, .post-related .mfn-jplayer, .post-related iframe, .blog_slider_ul .mfn-jplayer, .blog_slider_ul iframe"), 0.78);	// blog - list			
			iframeHeight($(".single-post .single-photo-wrapper .mfn-jplayer, .single-post .single-photo-wrapper iframe" ), 0.4);	// blog - single
			
			iframeHeight($(".section-portfolio-header .mfn-jplayer, .section-portfolio-header iframe" ), 0.4);	// portfolio - single
		}
		iframesHeight();

		
		/* ---------------------------------------------------------------------------
		 * Debouncedresize
		 * --------------------------------------------------------------------------- */
		$(window).bind("debouncedresize", function() {
			
			iframesHeight();	
			$('.masonry.isotope').isotope();
			
			// carouFredSel wrapper Height set
			mfn_carouFredSel_height();
			
			// Sidebar Height
			mfn_sidebar();
			
			// Sliding Footer | Height
			mfn_footer();
			
			// Header Width
			mfn_header();
			
			// Full Screen Section
			mfn_sectionH();
			
			// niceScroll | Padding right fix for short content
			niceScrollFix();
		});
		
		/* ---------------------------------------------------------------------------
		 * isotope
		 * --------------------------------------------------------------------------- */
		function isotopeFilter( domEl, isoWrapper ){
			var filter = domEl.attr('data-rel');
			isoWrapper.isotope({ filter: filter });
		}
		
		$('.isotope-filters .filters_wrapper').find('li:not(.close) a').click(function(e){
			e.preventDefault();
			isotopeFilter( $(this), $('.isotope') );
		});
		$('.isotope-filters .filters_buttons').find('li.reset a').click(function(e){
			e.preventDefault();
			isotopeFilter( $(this), $('.isotope') );
		});
		
		// carouFredSel wrapper | Height
		mfn_carouFredSel_height();
		
		// Sidebar | Height
		mfn_sidebar();
		
		// Sliding Footer | Height
		mfn_footer();
		
		// Header | Width
		mfn_header();

		// Full Screen Section
		mfn_sectionH();
		
		// Navigation | Hash
		hashNav();
	});
	
	
	/* --------------------------------------------------------------------------------------------------------------------------
	 * $(window).scroll
	 * ----------------------------------------------------------------------------------------------------------------------- */
	$(window).scroll(function(){
		mfn_sticky();
		onePageActive();
	});
	
	
	/* --------------------------------------------------------------------------------------------------------------------------
	 * $(window).load
	 * ----------------------------------------------------------------------------------------------------------------------- */
	$(window).load(function(){

		/* ---------------------------------------------------------------------------
		 * Isotope
		 * --------------------------------------------------------------------------- */
		// Portfolio - Isotope
		$('.portfolio_wrapper  .isotope:not(.masonry-flat)').isotope({
			itemSelector	: '.portfolio-item',
			layoutMode		: 'fitRows',
			resizable		: true
		});
		
		// Portfolio - Masonry Flat
		$('.portfolio_wrapper .masonry-flat').isotope({
			itemSelector	: '.portfolio-item',
			masonry			: {
			      columnWidth: 1
		    },
			resizable		: true
		});

		// Blog & Portfolio - Masonry
		$('.masonry.isotope').isotope({
			itemSelector	: '.isotope-item',
			layoutMode		: 'masonry',
			resizable		: true
		});

		
		/* ---------------------------------------------------------------------------
		 * Chart
		 * --------------------------------------------------------------------------- */
		$('.chart').waypoint({
			offset		: '100%',
			triggerOnce	: true,
			handler		: function(){
				var color = $(this).attr('data-color');
				$(this).easyPieChart({
					animate		: 1000,
					barColor	: color,
					lineCap		: 'circle',
					lineWidth	: 8,
					size		: 140,
					scaleColor	: false,
					trackColor	: '#f8f8f8'
				});
			}
		});
		
		
		/* ---------------------------------------------------------------------------
		 * Skills
		 * --------------------------------------------------------------------------- */
		$('.bars_list').waypoint({
			offset		: '100%',
			triggerOnce	: true,
			handler		: function(){
				$(this).addClass('hover');
			}
		});
		
		
		/* ---------------------------------------------------------------------------
		 * Progress Icons
		 * --------------------------------------------------------------------------- */
		$('.progress_icons').waypoint({
			offset		: '100%',
			triggerOnce	: true,
			handler		: function(){
				
				var el = $(this);
				var active = el.attr('data-active');
				var color = el.attr('data-color');
				var icon = el.find('.progress_icon');
				var timeout = 200;		// timeout in milliseconds
				
				icon.each(function(i){
					if( i < active ){
						var time = (i+1) * timeout; 
						setTimeout(function(){
							$(icon[i])
								.addClass('themebg')
								.css('background-color',color);
						},time );	
						
					}
				});
				
			}
		});
		
		
		/* ---------------------------------------------------------------------------
		 * Animate Math [counter, quick_fact, etc.]
		 * --------------------------------------------------------------------------- */
		$('.animate-math .number').waypoint({
			offset		: '100%',
			triggerOnce	: true,
			handler		: function(){
				var el			= $(this);
				var duration	= Math.floor((Math.random()*1000)+1000);
				var to			= el.attr('data-to');

				$({property:0}).animate({property:to}, {
					duration	: duration,
					easing		:'linear',
					step		: function() {
						el.text(Math.floor(this.property));
					},
					complete	: function() {
						el.text(this.property);
					}
				});
			}
		});
		
		
		// carouFredSel wrapper | Height
		mfn_carouFredSel_height();
		
		// Sidebar | Height
		mfn_sidebar();
		
		// Sliding Footer | Height
		mfn_footer();
		
		// Header | Width
		mfn_header();

		// Full Screen Section
		mfn_sectionH();
		
		// Navigation | Hash
		hashNav();
		
		// niceScroll | Padding right fix for short content
		niceScrollFix();
	});
	

	/* --------------------------------------------------------------------------------------------------------------------------
	 * $(document).mouseup
	 * ----------------------------------------------------------------------------------------------------------------------- */
	$(document).mouseup(function(e){
		
		// search
		if( $("#searchform").has(e.target).length === 0 ){
			if( $("#searchform").hasClass('focus') ){
				$(this).find('.icon_close').click();
			}
		}
		
	});
	
	
	/* ---------------------------------------------------------------------------
	 * Sliders configuration
	 * --------------------------------------------------------------------------- */
	
	// carouFredSel wrapper Height set -------------------------------------------
	function mfn_carouFredSel_height(){
		$('.caroufredsel_wrapper > ul').each(function(){
			var el = $(this);
			var maxH = 0;
			el.children('li').each(function(){				
				if( $(this).innerHeight() > maxH ){
					maxH = $(this).innerHeight();
				}
			});
//			console.log(maxH);
			el.closest('.caroufredsel_wrapper').height( maxH );
		});
		
	}
	
	// --- Slider ----------------------------------------------------------------
	function mfnSliderContent(){	
		$('.content_slider_ul').each(function(){

			// Init carouFredSel
			$('.content_slider_ul').carouFredSel({
				circular	: true,
				responsive	: true,
				items		: {
					visible	: 1,
					width	: 100
				},
				scroll		: {
					duration	: 500,
					easing		: 'swing'
				},
				prev        : {
					button		: function(){
						return $(this).closest('.content_slider').find('.slider_prev');
					}
				},
				next        : {
					button		: function(){
						return $(this).closest('.content_slider').find('.slider_next');
					}
				},
				pagination	: {
					container	: function(){
						return $(this).closest('.content_slider').find('.slider_pagination');
					}
				},
				auto		: {
					play			: window.mfn_sliders.slider ? true : false,
					timeoutDuration	: window.mfn_sliders.slider ? window.mfn_sliders.slider : 2500,
				},
				swipe		: {
					onTouch		: true,
					onMouse		: true,
					onBefore	: function(){
						$(this).find('a').addClass('disable');
						$(this).find('li').trigger('mouseleave');
					},
					onAfter		: function(){
						$(this).find('a').removeClass('disable');
					}
				}
			});
			
			// Disable accidental clicks while swiping
			$(this).on('click', 'a.disable', function() {
				return false; 
			});
		});
	}
	
	
	// --- Testimonials ----------------------------------------------------------------
	function mfnSliderTestimonials(){	
		$('.testimonials_slider_ul').each(function(){
			
			// Init carouFredSel
			$(this).carouFredSel({
				circular	: true,
				responsive	: true,
				items		: {
					visible	: 1,
					width	: 100
				},
				scroll		: {
					duration	: 500,
					easing		: 'swing'
				},
				prev        : {
					button		: function(){
						return $(this).closest('.testimonials_slider').find('.slider_prev');
					}
				},
				next        : {
					button		: function(){
						return $(this).closest('.testimonials_slider').find('.slider_next');
					}
				},
				pagination	: {
					container	: function(){
						return $(this).closest('.testimonials_slider').find('.slider_images');
					},
					anchorBuilder : false
				},
				auto		: {
					play			: window.mfn_sliders.testimonials ? true : false,
					timeoutDuration	: window.mfn_sliders.testimonials ? window.mfn_sliders.testimonials : 2500,
				},
				swipe		: {
					onTouch		: true,
					onMouse		: true,
					onBefore	: function(){
						$(this).find('a').addClass('disable');
						$(this).find('li').trigger('mouseleave');
					},
					onAfter		: function(){
						$(this).find('a').removeClass('disable');
					}
				}
			});
			
			// Disable accidental clicks while swiping
			$(this).on('click', 'a.disable', function() {
				return false; 
			});
		});
	}
	
	
	// --- Offer -----------------------------------------------------------------
	function mfnSliderOffer(){	
		$('.offer_ul').each(function(){
			
			// Init carouFredSel
			$(this).carouFredSel({
				circular	: true,
				responsive	: true,
				items		: {
					visible	: 1,
					width	: 100
				},
				scroll		: {
					duration	: 500,
					easing		: 'swing',
					onAfter		: function(){
						$(this).closest('.offer').find('.current').text($(this).triggerHandler("currentPosition")+1);
					}
				},
				prev        : {
					button		: function(){
						return $(this).closest('.offer').find('.slider_prev');
					}
				},
				next        : {
					button		: function(){
						return $(this).closest('.offer').find('.slider_next');
					}
				},
				auto		: {
					play			: window.mfn_sliders.offer ? true : false,
					timeoutDuration	: window.mfn_sliders.offer ? window.mfn_sliders.offer : 2500,
				},
				swipe		: {
					onTouch		: true,
					onMouse		: true,
					onBefore	: function(){
						$(this).find('a').addClass('disable');
						$(this).find('li').trigger('mouseleave');
					},
					onAfter		: function(){
						$(this).find('a').removeClass('disable');
						$(this).closest('.offer').find('.current').text($(this).triggerHandler("currentPosition")+1);
					}
				}
			});
			
			// Disable accidental clicks while swiping
			$(this).on('click', 'a.disable', function() {
				return false; 
			});
			
			// Items count
			var count = $(this).children('.offer_li').length;
			$(this).closest('.offer').find('.count').text(count);
		});
	}
	
	
	// --- Offer Thumb -----------------------------------------------------------------
	function mfnSliderOfferThumb_Pager(nr) {
		var thumb = $('.offer_thumb').find('.offer_thumb_li:eq('+ (nr-1) +') .thumbnail img').attr('src');			
	    return '<a href="#'+ nr +'"><img src="'+ thumb +'" alt="'+ nr +'" /></a>';
	}
	
	function mfnSliderOfferThumb(){	
		$('.offer_thumb_ul').each(function(){
			
			// Init carouFredSel
			$(this).carouFredSel({
				circular	: true,
				responsive	: true,
				items		: {
					visible	: 1,
					width	: 100
				},
				pagination	: {
				 	container		: $(this).closest('.offer_thumb').find('.slider_pagination'),
				 	anchorBuilder	: mfnSliderOfferThumb_Pager
				},
				scroll		: {
					duration	: 500,
					easing		: 'swing',
					onAfter		: function(){
						$(this).closest('.offer_thumb').find('.current').text($(this).triggerHandler("currentPosition")+1);
					}
				},
				auto		: {
					play			: window.mfn_sliders.offer ? true : false,
					timeoutDuration	: window.mfn_sliders.offer ? window.mfn_sliders.offer : 2500,
				},
				swipe		: {
					onTouch		: true,
					onMouse		: true,
					onBefore	: function(){
						$(this).find('a').addClass('disable');
						$(this).find('li').trigger('mouseleave');
					},
					onAfter		: function(){
						$(this).find('a').removeClass('disable');
						$(this).closest('.offer_thumb').find('.current').text($(this).triggerHandler("currentPosition")+1);
					}
				}
			});
			
			// Disable accidental clicks while swiping
			$(this).on('click', 'a.disable', function() {
				return false; 
			});
		});
	}
	
	
	// --- Blog ------------------------------------------------------------------	
	function mfnSliderBlog(){	
		$('.blog_slider_ul').each(function(){
			
			// Init carouFredSel
			$(this).carouFredSel({
				circular	: true,
				responsive	: true,
				items		: {
					width : 380,
					visible	: {
						min		: 1,
						max		: 4
					}
				},
				scroll		: {
					duration	: 500,
					easing		: 'swing'
				},
				prev        : {
					button		: function(){
						return $(this).closest('.blog_slider').find('.slider_prev');
					}
				},
				next        : {
					button		: function(){
						return $(this).closest('.blog_slider').find('.slider_next');
					}
				},
				pagination	: {
					container	: function(){
						return $(this).closest('.blog_slider').find('.slider_pagination');
					}
				},
				auto		: {
					play			: window.mfn_sliders.blog ? true : false,
					timeoutDuration	: window.mfn_sliders.blog ? window.mfn_sliders.blog : 2500,
				},
				swipe		: {
					onTouch		: true,
					onMouse		: true,
					onBefore	: function(){
						$(this).find('a').addClass('disable');
						$(this).find('li').trigger('mouseleave');
					},
					onAfter		: function(){
						$(this).find('a').removeClass('disable');
					}
				}
			});
			
			// Disable accidental clicks while swiping
			$(this).on('click', 'a.disable', function() {
				return false; 
			});
		});
	}
	
	
	// --- Clients ------------------------------------------------------------------	
	function mfnSliderClients(){	
		$('.clients_slider_ul').each(function(){
			
			// Init carouFredSel
			$(this).carouFredSel({
				circular	: true,
				responsive	: true,
				items		: {
					width : 380,
					visible	: {
						min		: 1,
						max		: 4
					}
				},
				scroll		: {
					duration	: 500,
					easing		: 'swing'
				},
				prev        : {
					button		: function(){
						return $(this).closest('.clients_slider').find('.slider_prev');
					}
				},
				next        : {
					button		: function(){
						return $(this).closest('.clients_slider').find('.slider_next');
					}
				},
				pagination	: {
					container	: function(){
						return $(this).closest('.clients_slider').find('.slider_pagination');
					}
				},
				auto		: {
					play			: window.mfn_sliders.clients ? true : false,
					timeoutDuration	: window.mfn_sliders.clients ? window.mfn_sliders.clients : 2500,
				},
				swipe		: {
					onTouch		: true,
					onMouse		: true,
					onBefore	: function(){
						$(this).find('a').addClass('disable');
						$(this).find('li').trigger('mouseleave');
					},
					onAfter		: function(){
						$(this).find('a').removeClass('disable');
					}
				}
			});
			
			// Disable accidental clicks while swiping
			$(this).on('click', 'a.disable', function() {
				return false; 
			});
		});
	}
	
	
	// --- Shop ------------------------------------------------------------------	
	function mfnSliderShop(){	
		$('.shop_slider_ul').each(function(){
			
			// Init carouFredSel
			$(this).carouFredSel({
				circular	: true,
				responsive	: true,
				items		: {
					width : 380,
					visible	: {
						min		: 1,
						max		: 4
					}
				},
				scroll		: {
					duration	: 500,
					easing		: 'swing'
				},
				prev        : {
					button		: function(){
						return $(this).closest('.shop_slider').find('.slider_prev');
					}
				},
				next        : {
					button		: function(){
						return $(this).closest('.shop_slider').find('.slider_next');
					}
				},
				pagination	: {
					container	: function(){
						return $(this).closest('.shop_slider').find('.slider_pagination');
					}
				},
				auto		: {
					play			: window.mfn_sliders.shop ? true : false,
					timeoutDuration	: window.mfn_sliders.shop ? window.mfn_sliders.shop : 2500,
				},
				swipe		: {
					onTouch		: true,
					onMouse		: true,
					onBefore	: function(){
						$(this).find('a').addClass('disable');
						$(this).find('li').trigger('mouseleave');
					},
					onAfter		: function(){
						$(this).find('a').removeClass('disable');
					}
				}
			});
			
			// Disable accidental clicks while swiping
			$(this).on('click', 'a.disable', function() {
//				return false; 
			});
		});
	}
	
	
	// --- Portfolio -------------------------------------------------------------
	function mfnSliderPortfolio(){	
		$('.portfolio_slider_ul').each(function(){
			
			// Init carouFredSel
			$(this).carouFredSel({
				circular	: true,
				responsive	: true,
				items		: {
					width : 380,
					visible	: {
						min		: 1,
						max		: 5
					}
				},
				scroll		: {
					duration	: 600,
					easing		: 'swing'
				},
				prev        : {
					button		: function(){
						return $(this).closest('.portfolio_slider').find('.slider_prev');
					}
				},
				next        : {
					button		: function(){
						return $(this).closest('.portfolio_slider').find('.slider_next');
					}
				},
				auto		: {
					play			: window.mfn_sliders.portfolio ? true : false,
					timeoutDuration	: window.mfn_sliders.portfolio ? window.mfn_sliders.portfolio : 2500,
				},
				swipe		: {
					onTouch		: true,
					onMouse		: true,
					onBefore	: function(){
						$(this).find('a').addClass('disable');
						$(this).find('li').trigger('mouseleave');
					},
					onAfter		: function(){
						$(this).find('a').removeClass('disable');
					}
				}
			});
			
			// Disable accidental clicks while swiping
			$(this).on('click', 'a.disable', function() {
				return false; 
			});
		});
	}

})(jQuery);