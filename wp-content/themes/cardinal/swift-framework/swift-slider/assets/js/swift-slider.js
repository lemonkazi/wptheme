/*
 *
 *	Swift Slider Frontend JS
 *	------------------------------------------------
 *	Swift Slider
 *	Copyright Swift Ideas 2014 - http://www.swiftideas.net
 *
 */

var SWIFTSLIDER = SWIFTSLIDER || {};

/*global jQuery */
(function() {

    // USE STRICT
    "use strict";

    var swiftSliderElements = [],
        $window = jQuery( window ),
        body = jQuery( 'body' ),
        deviceAgent = navigator.userAgent.toLowerCase(),
        isMobile = deviceAgent.match( /(iphone|ipod|android|iemobile)/ ),
        isMobileAlt = deviceAgent.match( /(iphone|ipod|ipad|android|iemobile)/ ),
        isAppleDevice = deviceAgent.match( /(iphone|ipod|ipad)/ ),
        parallaxScroll = navigator.userAgent.indexOf( 'Safari' ) != -1 || navigator.userAgent.indexOf( 'Chrome' ) == -1,
        scrollLimited = false;

    SWIFTSLIDER.curtainAnimating = false;

    SWIFTSLIDER = {
        init: function() {

            // Set up each slider
            jQuery( '.swift-slider' ).each(
                function( i ) {

                    var ssInstance = jQuery( this ),
                        sliderID = ssInstance.attr( 'id' ),
                        ssFullscreen = ssInstance.data( 'fullscreen' ),
                        windowHeight = parseInt( $window.height(), 10 ),
                        ssMaxHeight = parseInt( ssInstance.data( 'max-height' ), 10 );

                    // Skip if no slides found
                    if ( ssInstance.hasClass( 'no-slides' ) ) {
                        return false;
                    }

                    // Set the slider height
                    if ( jQuery( '#wpadminbar' ).length > 0 ) {
                        windowHeight = windowHeight - jQuery( '#wpadminbar' ).height();
                    }

                    var sliderHeight = windowHeight > ssMaxHeight || !ssFullscreen && ssMaxHeight ? ssMaxHeight : windowHeight;

                    if ( jQuery( '#top-bar' ).length > 0 ) {
                        sliderHeight = sliderHeight - jQuery( '#top-bar' ).height();
                    }

                    ssInstance.css( 'height', sliderHeight );

                    // Setup slider
                    SWIFTSLIDER.setupSlider( i, sliderID );

                    // Prepare the first slide
                    SWIFTSLIDER.prepareFirstSlide( i, jQuery( '#' + sliderID ) );
                }
            );

            $window.smartresize(
                function() {
                    SWIFTSLIDER.resizeSliders();
                }
            );

        },
        setupSlider: function( i, sliderID ) {

            // Slider variables
            var sliderInstance = '#' + sliderID,
                sliderElement = jQuery( sliderInstance ),
                sliderType = sliderElement.data( 'type' ),
                sliderAuto = parseInt( sliderElement.data( 'autoplay' ), 10 ),
                sliderLoop = sliderElement.data( 'loop' ),
                slideCount = sliderElement.data( 'slide-count' ),
                mobileNoSwipe = false,
                desktopSwipe = true,
                grabAbility = true;

            if ( slideCount === 1 ) {
                desktopSwipe = false;
                grabAbility = false;
                mobileNoSwipe = true;
            }

            // Set up the Swift Slider instance
            if ( sliderType == "curtain" ) {
                swiftSliderElements[i] = new Swiper(
                    sliderInstance, {
                        loop: false,
                        progress: true,
                        mode: 'vertical',
                        speed: 1000,
                        autoplay: sliderAuto,
                        keyboardControl: true,
                        simulateTouch: false,
                        mousewheelControl: true,
                        onFirstInit: SWIFTSLIDER.afterSliderCurtainInit,
                        onSlideChangeStart: SWIFTSLIDER.slideTransitionStart,
                        onSlideChangeEnd: SWIFTSLIDER.slideTransitionEnd,
                        onProgressChange: function( swiper ) {
                            for ( var i = 0; i < swiper.slides.length; i++ ) {
                                var slide = swiper.slides[i];
                                var progress = slide.progress;
                                var translate, boxShadow, boxShadowOpacity;
                                if ( progress > 0 ) {
                                    //translate = progress*jQuery(swiper.container).height(); 
                                    translate = progress * swiper.height;
                                    //translate = progress*swiper.width;  
                                    boxShadowOpacity = 0;
                                } else {
                                    translate = 0;
                                    boxShadowOpacity = 1 - Math.min( Math.abs( progress ), 1 );
                                }
                                //slide.style.boxShadow='0px 0px 10px rgba(0,0,0,'+boxShadowOpacity+')';
                                //swiper.setTransform(slide,'translate3d('+(translate)+'px,0,0)')
                                swiper.setTransform( slide, 'translate3d(0,' + (translate) + 'px,0)' );
                            }
                        },
                        onTouchStart: function( swiper ) {
                            for ( var i = 0; i < swiper.slides.length; i++ ) {
                                swiper.setTransition( swiper.slides[i], 0 );
                            }
                        },
                        onSetWrapperTransition: function( swiper, speed ) {
                            for ( var i = 0; i < swiper.slides.length; i++ ) {
                                swiper.setTransition( swiper.slides[i], speed );
                            }
                        }
                    }
                );
            } else {
                swiftSliderElements[i] = new Swiper(
                    sliderInstance, {
                        loop: sliderLoop,
                        touchRatio: 0.7,
                        mode: 'horizontal',
                        speed: 600,
                        autoplay: sliderAuto,
                        grabCursor: grabAbility,
                        paginationClickable: true,
                        keyboardControl: true,
                        noSwiping: mobileNoSwipe,
                        simulateTouch: desktopSwipe,
                        onFirstInit: SWIFTSLIDER.afterSliderInit,
                        onSlideChangeStart: SWIFTSLIDER.slideTransitionStart,
                        onSlideChangeEnd: SWIFTSLIDER.slideTransitionEnd
                    }
                );

                if ( mobileNoSwipe ) {
                    sliderElement.addClass( 'no-swiping' );
                }
            }

            // Resize the sliders
            swiftSliderElements[i].resizeFix();

        },
        prepareFirstSlide: function( i, sliderInstance ) {

            var delay = 600;

            // Set controls style based on the slide
            sliderInstance.find( '.swift-slider-pagination, .swift-slider-prev, .swift-slider-next, .swift-slider-continue' ).removeClass( 'dark' ).removeClass( 'light' ).addClass( sliderInstance.find( '.swiper-slide-active' ).data( 'style' ) );

            // Check if first slide has a video
            if ( sliderInstance.find( '.swiper-slide-active video' ).length > 0 ) {
                // Check if browser supports video
                if ( !jQuery( 'html' ).hasClass( 'no-video' ) && !isMobileAlt ) {

                    // Get video instance
                    var videoInstance = sliderInstance.find( '.swiper-slide-active video' ).get( 0 );

                    // Load video
                    videoInstance.load();

                    // Add event listener for when video has loaded
                    videoInstance.addEventListener(
                        'loadeddata', function() {

                            // Slide video loaded, now display the slider
                            SWIFTSLIDER.sliderLoaded( i, sliderInstance );
                            SWIFTSLIDER.setSliderContent( sliderInstance );
                            SWIFTSLIDER.showControls( sliderInstance );
                            SWIFTSLIDER.slideTransitionEnd( swiftSliderElements[i], delay );

                        }
                    );

                } else {

                    // Video not supported, remove video and continue loading slider
                    sliderInstance.find( '.swiper-slide-active video' ).remove();
                    SWIFTSLIDER.sliderLoaded( i, sliderInstance );
                    SWIFTSLIDER.setSliderContent( sliderInstance );
                    SWIFTSLIDER.showControls( sliderInstance );
                    SWIFTSLIDER.slideTransitionEnd( swiftSliderElements[i], delay );
                }

            } else {
                var slideImageURL = sliderInstance.find( '.swiper-slide-active' ).data( 'slide-img' );
                if ( slideImageURL && slideImageURL.length > 0 ) {

                    var slideImage = new Image();

                    // Callback for when slideImage loads					
                    jQuery( slideImage ).load(
                        function() {

                            // Slide image loaded, now display the slider
                            SWIFTSLIDER.sliderLoaded( i, sliderInstance );
                            SWIFTSLIDER.setSliderContent( sliderInstance );
                            SWIFTSLIDER.showControls( sliderInstance );
                            SWIFTSLIDER.slideTransitionEnd( swiftSliderElements[i], delay );
                        }
                    );

                    // Set the source
                    slideImage.src = slideImageURL;

                } else {

                    // If all else fails, now display the slider
                    SWIFTSLIDER.sliderLoaded( i, sliderInstance );
                    SWIFTSLIDER.setSliderContent( sliderInstance );
                    SWIFTSLIDER.showControls( sliderInstance );
                    SWIFTSLIDER.slideTransitionEnd( swiftSliderElements[i], delay );
                }

            }

        },
        afterSliderInit: function() {

            // Resize the sliders
            SWIFTSLIDER.resizeSliders();

        },
        afterSliderCurtainInit: function( e ) {

            var sliderObject = e,
                sliderInstance = jQuery( sliderObject.container ),
                scrollIndicator = sliderInstance.find( '.swift-scroll-indicator' );

            // Resize the sliders
            SWIFTSLIDER.resizeSliders();

            // Scroll Indicator Animate
            setTimeout(
                function() {
                    SWIFTSLIDER.scrollIndicatorAnimate( scrollIndicator );
                }, 1000
            );

            // Bind scroll event on load
            if ( $window.scrollTop() < 10 ) {
                SWIFTSLIDER.curtainEnter( sliderObject, sliderInstance );
            } else {
                jQuery( 'body,html' ).animate(
                    {
                        scrollTop: sliderInstance.scrollTop()
                    }, 200, 'easeOutCubic', function() {
                        SWIFTSLIDER.curtainEnter( sliderObject, sliderInstance );
                    }
                );

            }

            // Check when slider is hovered & bind mousewheel
            sliderInstance.on(
                'mouseenter', function() {
                    body.css( 'overflow', 'hidden' );
                    SWIFTSLIDER.curtainEnter( sliderObject, sliderInstance );
                }
            );

            // Remove
            sliderInstance.on(
                'mouseleave', function() {
                    body.css( 'overflow', '' );
                }
            );

        },
        scrollIndicatorAnimate: function( scrollIndicator ) {

            var timer = false,
                indicatorDots = scrollIndicator.find( 'span' );

            if ( scrollIndicator.hasClass( 'indicator-hidden' ) ) {
                return false;
            }

            indicatorDots.each(
                function( n, i ) {
                    i = jQuery( i );

                    i.delay( n * 100 ).animate(
                        {
                            opacity: 1
                        }, 100, function() {
                            i.animate(
                                {
                                    opacity: 0
                                }, 500, function() {
                                    if ( n === indicatorDots.length - 1 ) {
                                        timer = setTimeout(
                                            function() {
                                                SWIFTSLIDER.scrollIndicatorAnimate( scrollIndicator );
                                            }, 2000
                                        );
                                    }
                                }
                            );
                        }
                    );
                }
            );
        },
        curtainEnter: function( sliderObject, sliderInstance ) {
            if ( $window.scrollTop() > 0 ) {
                jQuery( 'body,html' ).animate(
                    {
                        scrollTop: sliderInstance.scrollTop()
                    }, 800, 'easeOutCubic', function() {
                        SWIFTSLIDER.curtainAnimating = false;
                    }
                );
            }
            if ( !sliderInstance.data( 'continue' ) ) {
                SWIFTSLIDER.curtainAnimating = false;
            }
        },
        sliderLoaded: function( i, sliderInstance ) {

            // Fade out the loader
            sliderInstance.find( '#swift-slider-loader' ).fadeOut( 400 );

            // Fade in the slider
            sliderInstance.find( '.swiper-wrapper' ).animate(
                {
                    'opacity': 1
                }, 400
            );

            // Fade in the video overlays
            sliderInstance.find( '.video-overlay' ).animate(
                {
                    'opacity': 1
                }, 400
            );

            // Initiate parallax scrolling on the slider if it's using the hero location
            if ( (sliderInstance.parent( '#main-container' ).length > 0 || sliderInstance.parent( 'body' ).length > 0) && sliderInstance.data( 'type' ) != "curtain" && !isMobileAlt ) {
                SWIFTSLIDER.parallax( sliderInstance );
            }

            // Slider prev button
            sliderInstance.find( '.swift-slider-prev' ).on(
                'click', function( e ) {
                    e.preventDefault();
                    if ( body.css( "direction" ).toLowerCase() == "rtl" ) {
                        swiftSliderElements[i].swipeNext();
                    } else {
                        swiftSliderElements[i].swipePrev();
                    }
                }
            );

            // Slider next button
            sliderInstance.find( '.swift-slider-next' ).on(
                'click', function( e ) {
                    e.preventDefault();
                    if ( body.css( "direction" ).toLowerCase() == "rtl" ) {
                        swiftSliderElements[i].swipePrev();
                    } else {
                        swiftSliderElements[i].swipeNext();
                    }
                }
            );

            // Slieder pagination
            sliderInstance.find( '.swift-slider-pagination' ).on(
                'click', '> div', function( e ) {
                    swiftSliderElements[i].swipeTo( jQuery( this ).index() );
                    sliderInstance.find( '.swift-slider-pagination .dot' ).removeClass( 'active' );
                    jQuery( this ).addClass( 'active' );
                }
            );

            // Curtain slider continue
            sliderInstance.find( '.swift-slider-continue' ).on(
                'click', function( e ) {
                    e.preventDefault();
                    SWIFTSLIDER.curtainAdvance( sliderInstance );
                }
            );

        },
        curtainAdvance: function( sliderInstance ) {

            if ( !sliderInstance.data( 'continue' ) ) {
                return false;
            }

            var stickyHeaderHeight = body.hasClass( 'sticky-header-enabled' ) && !body.hasClass( 'header-below-slider' ) && jQuery( '.header-wrap' ).is( ':visible' ) ? jQuery( '.sticky-header' ).height() : 0;

            if ( body.hasClass( 'sh-dynamic' ) ) {

                stickyHeaderHeight = stickyHeaderHeight - 20;
            }

            var sliderScrollTop = sliderInstance.offset().top + sliderInstance.height() - stickyHeaderHeight;


            if ( jQuery( '#wpadminbar' ).length > 0 ) {
                sliderScrollTop = sliderScrollTop - jQuery( '#wpadminbar' ).height();
            }

            jQuery( 'body,html' ).stop( true, false ).animate(
                {
                    scrollTop: sliderScrollTop
                }, 800
            );

            body.css( 'overflow', '' );
            SWIFTSLIDER.curtainAnimating = true;
        },
        showControls: function( sliderInstance ) {

            // Animate in controls
            if ( sliderInstance.data( 'type' ) != "curtain" ) {
                if ( sliderInstance.data( 'loop' ) ) {
                    sliderInstance.find( '.swift-slider-prev' ).fadeIn( 400 );
                }
                sliderInstance.find( '.swift-slider-next' ).fadeIn( 400 );
                sliderInstance.find( '.swift-slider-pagination .dot' ).first().addClass( 'active' );
                sliderInstance.find( '.swift-slider-pagination' ).fadeIn( 600 );
            } else if ( sliderInstance.data( 'type' ) === "curtain" ) {
                sliderInstance.find( '.swift-slider-pagination .dot' ).first().addClass( 'active' );
                sliderInstance.find( '.swift-slider-pagination' ).css(
                    'margin-top', -sliderInstance.find( '.swift-slider-pagination' ).height() / 2
                );
                sliderInstance.find( '.swift-slider-pagination' ).fadeIn( 600 );
                if ( sliderInstance.find( '.swiper-slide' ).length < 2 ) {
                    sliderInstance.find( '.swift-slider-continue' ).removeClass( 'continue-hidden' );
                }
            }

        },
        parallax: function( sliderInstance ) {

            var sliderHeight = sliderInstance.height(),
                docHeight = jQuery( document ).height();

            // Check if parallax scroll is possible
            if ( parallaxScroll ) {
                $window.scroll(
                    function() {

                        var scrollTop = $window.scrollTop();

                        // Check if window width is greater than mobile sized
                        if ( $window.width() > 768 ) {

                            // Only scroll if the slider is makes sense to do so
                            if ( sliderHeight * 2.2 < docHeight ) {

                                if ( scrollTop < 0 ) {
                                    scrollTop = 0;
                                }

                                // Move the swiper wrapper down, simulating a height change
                                sliderInstance.find( '.swiper-wrapper' ).stop( true, true ).transition(
                                    {
                                        top: scrollTop / 1.8
                                    }, 0
                                );

                                // Move & fade the slider content
                                sliderInstance.find( '.caption-wrap' ).stop( true, true ).transition(
                                    {
                                        y: scrollTop / 480,
                                        opacity: 1 - scrollTop / 450
                                    }, 0
                                );

                                // Fade the pagination
                                sliderInstance.find( '.swift-slider-pagination' ).stop( true, true ).transition(
                                    {
                                        opacity: 1 - scrollTop / 420
                                    }, 0
                                );
                            }
                        }
                    }
                );
            }

        },
        resizeSliders: function( sliderInstance ) {

            // Cache the windowHeight
            var windowHeight = parseInt( $window.height(), 10 );

            if ( jQuery( '#wpadminbar' ).length > 0 ) {
                windowHeight = windowHeight - jQuery( '#wpadminbar' ).height();
            }

            // Resize each slider
            if ( sliderInstance ) {

                var ssInstance = sliderInstance,
                    ssFullscreen = ssInstance.data( 'fullscreen' ),
                    ssMaxHeight = parseInt( ssInstance.data( 'max-height' ), 10 ),
                    ssVideoWrap = ssInstance.find( '.video-wrap' ),
                    sliderHeight = windowHeight > ssMaxHeight && !ssFullscreen ? ssMaxHeight : windowHeight,
                    sliderWidth = ssInstance.width();

                // Set slider and slide width & height	
                SWIFTSLIDER.setSliderSize( ssInstance, ssFullscreen, sliderWidth, sliderHeight );

                // Check if a video is present
                if ( ssVideoWrap.length > 0 ) {
                    SWIFTSLIDER.setSlideVideoSize( ssVideoWrap, sliderWidth, sliderHeight );
                }

            } else {

                jQuery( '.swift-slider' ).each(
                    function() {
                        var ssInstance = jQuery( this ),
                            ssFullscreen = ssInstance.data( 'fullscreen' ),
                            ssMaxHeight = parseInt( ssInstance.data( 'max-height' ), 10 ),
                            ssVideoWrap = ssInstance.find( '.video-wrap' ),
                            sliderHeight = windowHeight > ssMaxHeight && !ssFullscreen ? ssMaxHeight : windowHeight,
                            sliderWidth = ssInstance.width();

                        // Set slider and slide width & height	
                        SWIFTSLIDER.setSliderSize( ssInstance, ssFullscreen, sliderWidth, sliderHeight );

                        // Check if a video is present
                        if ( ssVideoWrap.length > 0 ) {
                            SWIFTSLIDER.setSlideVideoSize( ssVideoWrap, sliderWidth, sliderHeight );
                        }
                    }
                );

            }

        },
        setSliderSize: function( ssInstance, ssFullscreen, sliderWidth, sliderHeight ) {
            // Modify height based on other elements
            if ( !body.hasClass( 'vertical-header' ) && !body.hasClass( 'header-naked-light' ) && !body.hasClass( 'header-naked-dark' ) && !body.hasClass( 'header-below-slider' ) && !body.hasClass( 'header-standard-overlay' ) && ssFullscreen && jQuery( '.header-wrap' ).is( ':visible' ) ) {
                sliderHeight = sliderHeight - jQuery( '.header-wrap' ).height();
            }


            if ( body.hasClass( 'vertical-header' ) && jQuery( '#wpadminbar' ).length > 0 && ssFullscreen ) {
                //sliderHeight = sliderHeight + jQuery('#wpadminbar').height();
            }

            if ( jQuery( '#mobile-top-text' ).is( ':visible' ) ) {
                sliderHeight = sliderHeight - jQuery( '#mobile-top-text' ).height();
            }

            if ( jQuery( '#mobile-header' ).is( ':visible' ) && !body.hasClass( 'header-below-slider' ) ) {
                sliderHeight = sliderHeight - jQuery( '#mobile-header' ).height() - 20;
            }

            if ( jQuery( '#top-bar' ).length > 0 ) {
                sliderHeight = sliderHeight - jQuery( '#top-bar' ).height();
            }

            // Set slider height + width
            ssInstance.css( 'height', sliderHeight );
            ssInstance.find( '.swiper-container, .swiper-slide' ).css( 'height', sliderHeight );
            ssInstance.find( '.swiper-container' ).css( 'width', sliderWidth );

            // Vertically center caption & fade in
            var contentHeight = 0;
            ssInstance.find( '.caption-content' ).each(
                function() {
                    var caption = jQuery( this ),
                        captionHeight = jQuery( this ).height();

                    if ( captionHeight > contentHeight ) {
                        contentHeight = captionHeight;
                    }

                    caption.css( 'margin-top', -captionHeight / 2 );
                }
            );


            // Increase slider height if content height is larger
            if ( contentHeight > sliderHeight ) {
                ssInstance.css( 'height', contentHeight + 60 );
                ssInstance.find( '.swiper-container, .swiper-slide' ).css( 'height', contentHeight + 60 );
            }
        },
        setSlideVideoSize: function( ssVideoWrap, sliderWidth, sliderHeight ) {
            var ssVideo = ssVideoWrap.find( '.video' ),
                videoWidth = parseInt( ssVideo.data( 'width' ), 10 ),
                videoHeight = parseInt( ssVideo.data( 'height' ), 10 );

            // Set video width/height if needed
            if ( videoWidth === 0 ) {
                videoWidth = ssVideo[0].videoWidth;
            }
            if ( videoHeight === 0 ) {
                videoHeight = ssVideo[0].videoHeight;
            }
            // Last ditch fallbacks
            if ( videoWidth === 0 ) {
                videoWidth = 1920;
            }
            if ( videoHeight === 0 ) {
                videoHeight = 1080;
            }

            // Set slide video width + height
            ssVideo.css( 'height', sliderHeight ).css( 'width', sliderWidth );
            ssVideoWrap.width( sliderWidth ).height( sliderHeight );

            // Use the largest scale factor of horizontal/vertical
            var scale_h = sliderWidth / videoWidth;
            var scale_v = sliderHeight / videoHeight;
            var scale = scale_h > scale_v ? scale_h : scale_v;

            // Update minium width to never allow excess space
            var min_w = videoWidth / videoHeight * (sliderHeight + 20);

            // Don't allow scaled width < minimum video width
            if ( scale * videoWidth < min_w ) {
                scale = min_w / videoWidth;
            }

            // Scale the video
            ssVideo.width( Math.ceil( scale * videoWidth + 2 ) );
            ssVideo.height( Math.ceil( scale * videoHeight + 2 ) );

            // Center the video wrap
            ssVideo.css( 'margin-top', -(ssVideo.height() - ssVideoWrap.height()) / 2 );
            ssVideo.css( 'margin-left', -(ssVideo.width() - ssVideoWrap.width()) / 2 );
        },
        slideTransitionStart: function( e ) {
            var sliderObject = e,
                sliderInstance = jQuery( sliderObject.container ),
                currentSlide = jQuery( e.activeSlide() ),
                currentSlideIndex = e.activeIndex,
                currentSlideID = currentSlide.data( 'slide-id' ) ? parseInt( currentSlide.data( 'slide-id' ), 10 ) : 1,
                pagination = sliderInstance.find( '.swift-slider-pagination' ),
                numberOfSlides = pagination.find( '.dot' ).length,
                ssContinue = sliderInstance.data( 'continue' );

            // Resize current slider
            SWIFTSLIDER.resizeSliders( sliderInstance );

            // Update pagination				
            if ( sliderInstance.data( 'type' ) === "curtain" ) {
                currentSlideIndex = currentSlideIndex + 1;
            } else {
                if ( sliderInstance.data( 'loop' ) ) {
                    if ( currentSlideIndex === 0 ) {
                        currentSlideIndex = numberOfSlides;
                    } else if ( currentSlideIndex > numberOfSlides ) {
                        currentSlideIndex = currentSlideIndex - numberOfSlides;
                    }
                } else {
                    currentSlideIndex = currentSlideIndex + 1;
                }
            }
            sliderInstance.find( '.swift-slider-pagination .dot' ).removeClass( 'active' );
            sliderInstance.find( '.swift-slider-pagination .dot:nth-child(' + currentSlideIndex + ')' ).addClass( 'active' );

            // Set curtain controls
            if ( currentSlideID > 1 ) {
                sliderInstance.find( '.swift-scroll-indicator' ).fadeOut( 300 );
            } else {
                sliderInstance.find( '.swift-scroll-indicator' ).fadeIn( 300 );
            }

            if ( currentSlideID === numberOfSlides && ssContinue ) {
                setTimeout(
                    function() {
                        sliderInstance.find( '.swift-slider-continue' ).removeClass( 'continue-hidden' );
                    }, 600
                );
            } else {
                setTimeout(
                    function() {
                        sliderInstance.find( '.swift-slider-continue' ).addClass( 'continue-hidden' );
                    }, 300
                );
            }

            // Set controls style based on the slide
            sliderInstance.find( '.swift-slider-pagination, .swift-slider-prev, .swift-slider-next, .swift-slider-continue' ).removeClass( 'dark' ).removeClass( 'light' ).addClass( currentSlide.data( 'style' ) );

            if ( currentSlideIndex === 1 && !sliderInstance.data( 'loop' ) ) {
                sliderInstance.find( '.swift-slider-prev' ).css( 'display', 'none' );
                sliderInstance.find( '.swift-slider-next' ).css( 'display', 'block' );
            } else if ( currentSlideIndex === sliderInstance.find( '.swiper-slide' ).length && !sliderInstance.data( 'loop' ) ) {
                sliderInstance.find( '.swift-slider-prev' ).css( 'display', 'block' );
                sliderInstance.find( '.swift-slider-next' ).css( 'display', 'none' );
            } else {
                sliderInstance.find( '.swift-slider-prev' ).css( 'display', 'block' );
                sliderInstance.find( '.swift-slider-next' ).css( 'display', 'block' );
            }
        },
        slideTransitionEnd: function( e, delay ) {
            var sliderObject = e,
                sliderInstance = jQuery( sliderObject.container ),
                currentSlide = jQuery( e.activeSlide() ),
                currentIndex = e.activeIndex >= 0 ? e.activeIndex : 1,
                currentSlideID = currentSlide.data( 'slide-id' ) ? parseInt( currentSlide.data( 'slide-id' ), 10 ) : 1,
                timeoutDelay = delay || 0,
                pagination = sliderInstance.find( '.swift-slider-pagination' ),
                numberOfSlides = pagination.find( '.dot' ).length,
                sliderPrev = jQuery( '.swift-slider-prev' ).find( 'h4' ),
                sliderNext = jQuery( '.swift-slider-next' ).find( 'h4' ),
                prevSlideTitle = currentSlideID === 1 ? sliderInstance.find( '.swiper-slide[data-slide-id="' + numberOfSlides + '"]' ).data( 'slide-title' ) : sliderInstance.find( '.swiper-slide[data-slide-id="' + (currentSlideID - 1) + '"]' ).data( 'slide-title' ),
                nextSlideTitle = currentSlideID === numberOfSlides ? sliderInstance.find( '.swiper-slide[data-slide-id="1"]' ).data( 'slide-title' ) : sliderInstance.find( '.swiper-slide[data-slide-id="' + (currentSlideID + 1) + '"]' ).data( 'slide-title' );

            // Resize the sliders
            SWIFTSLIDER.resizeSliders( sliderInstance );

            // Set next/previous navigation titles
            sliderPrev.text( prevSlideTitle );
            sliderNext.text( nextSlideTitle );

            // Set content for each slide
            sliderInstance.find( '.swiper-slide' ).each(
                function( i ) {
                    var slide = jQuery( this ),
                        slideID = slide.data( 'slide-id' ),
                        slideCaption = slide.find( '.caption-content' ),
                        slideVideo = slide.find( '.video-wrap > video' );

                    // Check if there is a video, and if so then pause it & set to start point
                    if ( slideVideo.length > 0 ) {
                        if ( !jQuery( 'html' ).hasClass( 'no-video' ) && !isMobileAlt ) {
                            slideVideo.get( 0 ).pause();
                            if ( slideVideo.get( 0 ).currentTime !== 0 ) {
                                slideVideo.get( 0 ).currentTime = 0;
                            }
                        } else {
                            slideVideo.remove();
                        }
                    }

                    // Reset caption position & opacity of other slides
                    if ( slideID !== currentSlideID && slideCaption.length > 0 ) {
                        slideCaption.css(
                            {
                                'margin-top': '',
                                'padding-top': '',
                                'padding-bottom': '',
                                'opacity': '0'
                            }
                        );
                    }

                    // animate current slide content
                    if ( slideID === currentSlideID ) {
                        // Play the active slide video, if there is one
                        setTimeout(
                            function() {
                                if ( !jQuery( 'html' ).hasClass( 'no-video' ) ) {
                                    if ( slideVideo.length > 0 ) {
                                        slideVideo.get( 0 ).pause();
                                        slideVideo.get( 0 ).play();
                                    }
                                }

                                // Fade in the current slide content
                                if ( slideCaption.length > 0 ) {
                                    var captionHeight = slideCaption.height();

                                    slideCaption.css( 'margin-top', -captionHeight / 2 ).stop().animate(
                                        {
                                            'opacity': 1,
                                            'padding-top': 0,
                                            'padding-bottom': 0
                                        }, 800, 'easeOutQuart'
                                    );
                                }
                            }, timeoutDelay
                        );
                    }
                }
            );
        },
        setSliderContent: function( sliderInstance ) {

            sliderInstance.find( '.swiper-slide' ).each(
                function() {

                    var content = jQuery( this ).find( '.caption-content' ),
                        contentHeight = content.height();

                    // Set content vertically center
                    content.css( 'margin-top', -contentHeight / 2 );

                }
            );
        }
    };

    var onReady = {
        init: function() {
            if ( jQuery( '.swift-slider' ).length > 0 ) {
                SWIFTSLIDER.init();
            }
        }
    };

    jQuery( document ).ready( onReady.init );

})( jQuery );

/*
 * Swiper 2.6.1
 * Mobile touch slider and framework with hardware accelerated transitions
 *
 * http://www.idangero.us/sliders/swiper/
 *
 * Copyright 2010-2014, Vladimir Kharlampidi
 * The iDangero.us
 * http://www.idangero.us/
 *
 * Licensed under GPL & MIT
 *
 * Released on: May 6, 2014
 */
var Swiper = function( l, a ) {
    if ( document.body.__defineGetter__ ) {
        if ( HTMLElement ) {
            var t = HTMLElement.prototype;
            if ( t.__defineGetter__ ) {
                t.__defineGetter__(
                    "outerHTML", function() {
                        return new XMLSerializer().serializeToString( this )
                    }
                )
            }
        }
    }
    if ( !window.getComputedStyle ) {
        window.getComputedStyle = function( i, j ) {
            this.el = i;
            this.getPropertyValue = function( ag ) {
                var p = /(\-([a-z]){1})/g;
                if ( ag === "float" ) {
                    ag = "styleFloat"
                }
                if ( p.test( ag ) ) {
                    ag = ag.replace(
                        p, function() {
                            return arguments[2].toUpperCase()
                        }
                    )
                }
                return i.currentStyle[ag] ? i.currentStyle[ag] : null
            };
            return this
        }
    }
    if ( !Array.prototype.indexOf ) {
        Array.prototype.indexOf = function( ah, ai ) {
            for ( var ag = (ai || 0), p = this.length; ag < p; ag++ ) {
                if ( this[ag] === ah ) {
                    return ag
                }
            }
            return -1
        }
    }
    if ( !document.querySelectorAll ) {
        if ( !window.jQuery ) {
            return
        }
    }
    function R( i, j ) {
        if ( document.querySelectorAll ) {
            return (j || document).querySelectorAll( i )
        } else {
            return jQuery( i, j )
        }
    }

    if ( typeof l === "undefined" ) {
        return
    }
    if ( !(l.nodeType) ) {
        if ( R( l ).length === 0 ) {
            return
        }
    }
    var f = this;
    f.touches = {start: 0, startX: 0, startY: 0, current: 0, currentX: 0, currentY: 0, diff: 0, abs: 0};
    f.positions = {start: 0, abs: 0, diff: 0, current: 0};
    f.times = {start: 0, end: 0};
    f.id = (new Date()).getTime();
    f.container = (l.nodeType) ? l : R( l )[0];
    f.isTouched = false;
    f.isMoved = false;
    f.activeIndex = 0;
    f.centerIndex = 0;
    f.activeLoaderIndex = 0;
    f.activeLoopIndex = 0;
    f.previousIndex = null;
    f.velocity = 0;
    f.snapGrid = [];
    f.slidesGrid = [];
    f.imagesToLoad = [];
    f.imagesLoaded = 0;
    f.wrapperLeft = 0;
    f.wrapperRight = 0;
    f.wrapperTop = 0;
    f.wrapperBottom = 0;
    f.isAndroid = navigator.userAgent.toLowerCase().indexOf( "android" ) >= 0;
    var z, J, ae, r, b, d;
    var G = {
        eventTarget: "wrapper",
        mode: "horizontal",
        touchRatio: 1,
        speed: 300,
        freeMode: false,
        freeModeFluid: false,
        momentumRatio: 1,
        momentumBounce: true,
        momentumBounceRatio: 1,
        slidesPerView: 1,
        slidesPerGroup: 1,
        slidesPerViewFit: true,
        simulateTouch: true,
        followFinger: true,
        shortSwipes: true,
        longSwipesRatio: 0.5,
        moveStartThreshold: false,
        onlyExternal: false,
        createPagination: true,
        pagination: false,
        paginationElement: "span",
        paginationClickable: false,
        paginationAsRange: true,
        resistance: true,
        scrollContainer: false,
        preventLinks: true,
        preventLinksPropagation: false,
        noSwiping: false,
        noSwipingClass: "swiper-no-swiping",
        initialSlide: 0,
        keyboardControl: false,
        mousewheelControl: false,
        mousewheelControlForceToAxis: false,
        useCSS3Transforms: true,
        autoplay: false,
        autoplayDisableOnInteraction: true,
        autoplayStopOnLast: false,
        loop: false,
        loopAdditionalSlides: 0,
        roundLengths: false,
        calculateHeight: false,
        cssWidthAndHeight: false,
        updateOnImagesReady: true,
        releaseFormElements: true,
        watchActiveIndex: false,
        visibilityFullFit: false,
        offsetPxBefore: 0,
        offsetPxAfter: 0,
        offsetSlidesBefore: 0,
        offsetSlidesAfter: 0,
        centeredSlides: false,
        queueStartCallbacks: false,
        queueEndCallbacks: false,
        autoResize: true,
        resizeReInit: false,
        DOMAnimation: true,
        loader: {slides: [], slidesHTMLType: "inner", surroundGroups: 1, logic: "reload", loadAllSlides: false},
        slideElement: "div",
        slideClass: "swiper-slide",
        slideActiveClass: "swiper-slide-active",
        slideVisibleClass: "swiper-slide-visible",
        slideDuplicateClass: "swiper-slide-duplicate",
        wrapperClass: "swiper-wrapper",
        paginationElementClass: "swiper-pagination-switch",
        paginationActiveClass: "swiper-active-switch",
        paginationVisibleClass: "swiper-visible-switch"
    };
    a = a || {};
    for ( var x in G ) {
        if ( x in a && typeof a[x] === "object" ) {
            for ( var e in G[x] ) {
                if ( !(e in a[x]) ) {
                    a[x][e] = G[x][e]
                }
            }
        } else {
            if ( !(x in a) ) {
                a[x] = G[x]
            }
        }
    }
    f.params = a;
    if ( a.scrollContainer ) {
        a.freeMode = true;
        a.freeModeFluid = true
    }
    if ( a.loop ) {
        a.resistance = "100%"
    }
    var D = a.mode === "horizontal";
    var v = ["mousedown", "mousemove", "mouseup"];
    if ( f.browser.ie10 ) {
        v = ["MSPointerDown", "MSPointerMove", "MSPointerUp"]
    }
    if ( f.browser.ie11 ) {
        v = ["pointerdown", "pointermove", "pointerup"]
    }
    f.touchEvents = {
        touchStart: f.support.touch || !a.simulateTouch ? "touchstart" : v[0],
        touchMove: f.support.touch || !a.simulateTouch ? "touchmove" : v[1],
        touchEnd: f.support.touch || !a.simulateTouch ? "touchend" : v[2]
    };
    for ( var V = f.container.childNodes.length - 1; V >= 0; V-- ) {
        if ( f.container.childNodes[V].className ) {
            var s = f.container.childNodes[V].className.split( /\s+/ );
            for ( var S = 0; S < s.length; S++ ) {
                if ( s[S] === a.wrapperClass ) {
                    z = f.container.childNodes[V]
                }
            }
        }
    }
    f.wrapper = z;
    f._extendSwiperSlide = function( i ) {
        i.append = function() {
            if ( a.loop ) {
                i.insertAfter( f.slides.length - f.loopedSlides )
            } else {
                f.wrapper.appendChild( i );
                f.reInit()
            }
            return i
        };
        i.prepend = function() {
            if ( a.loop ) {
                f.wrapper.insertBefore( i, f.slides[f.loopedSlides] );
                f.removeLoopedSlides();
                f.calcSlides();
                f.createLoop()
            } else {
                f.wrapper.insertBefore( i, f.wrapper.firstChild )
            }
            f.reInit();
            return i
        };
        i.insertAfter = function( j ) {
            if ( typeof j === "undefined" ) {
                return false
            }
            var p;
            if ( a.loop ) {
                p = f.slides[j + 1 + f.loopedSlides];
                if ( p ) {
                    f.wrapper.insertBefore( i, p )
                } else {
                    f.wrapper.appendChild( i )
                }
                f.removeLoopedSlides();
                f.calcSlides();
                f.createLoop()
            } else {
                p = f.slides[j + 1];
                f.wrapper.insertBefore( i, p )
            }
            f.reInit();
            return i
        };
        i.clone = function() {
            return f._extendSwiperSlide( i.cloneNode( true ) )
        };
        i.remove = function() {
            f.wrapper.removeChild( i );
            f.reInit()
        };
        i.html = function( j ) {
            if ( typeof j === "undefined" ) {
                return i.innerHTML
            } else {
                i.innerHTML = j;
                return i
            }
        };
        i.index = function() {
            var j;
            for ( var p = f.slides.length - 1; p >= 0; p-- ) {
                if ( i === f.slides[p] ) {
                    j = p
                }
            }
            return j
        };
        i.isActive = function() {
            if ( i.index() === f.activeIndex ) {
                return true
            } else {
                return false
            }
        };
        if ( !i.swiperSlideDataStorage ) {
            i.swiperSlideDataStorage = {}
        }
        i.getData = function( j ) {
            return i.swiperSlideDataStorage[j]
        };
        i.setData = function( j, p ) {
            i.swiperSlideDataStorage[j] = p;
            return i
        };
        i.data = function( j, p ) {
            if ( typeof p === "undefined" ) {
                return i.getAttribute( "data-" + j )
            } else {
                i.setAttribute( "data-" + j, p );
                return i
            }
        };
        i.getWidth = function( p, j ) {
            return f.h.getWidth( i, p, j )
        };
        i.getHeight = function( p, j ) {
            return f.h.getHeight( i, p, j )
        };
        i.getOffset = function() {
            return f.h.getOffset( i )
        };
        return i
    };
    f.calcSlides = function( ah ) {
        var aj = f.slides ? f.slides.length : false;
        f.slides = [];
        f.displaySlides = [];
        for ( var ai = 0; ai < f.wrapper.childNodes.length; ai++ ) {
            if ( f.wrapper.childNodes[ai].className ) {
                var ak = f.wrapper.childNodes[ai].className;
                var p = ak.split( /\s+/ );
                for ( var ag = 0; ag < p.length; ag++ ) {
                    if ( p[ag] === a.slideClass ) {
                        f.slides.push( f.wrapper.childNodes[ai] )
                    }
                }
            }
        }
        for ( ai = f.slides.length - 1; ai >= 0; ai-- ) {
            f._extendSwiperSlide( f.slides[ai] )
        }
        if ( aj === false ) {
            return
        }
        if ( aj !== f.slides.length || ah ) {
            o();
            q();
            f.updateActiveSlide();
            if ( f.params.pagination ) {
                f.createPagination()
            }
            f.callPlugins( "numberOfSlidesChanged" )
        }
    };
    f.createSlide = function( p, j, ag ) {
        j = j || f.params.slideClass;
        ag = ag || a.slideElement;
        var i = document.createElement( ag );
        i.innerHTML = p || "";
        i.className = j;
        return f._extendSwiperSlide( i )
    };
    f.appendSlide = function( j, i, p ) {
        if ( !j ) {
            return
        }
        if ( j.nodeType ) {
            return f._extendSwiperSlide( j ).append()
        } else {
            return f.createSlide( j, i, p ).append()
        }
    };
    f.prependSlide = function( j, i, p ) {
        if ( !j ) {
            return
        }
        if ( j.nodeType ) {
            return f._extendSwiperSlide( j ).prepend()
        } else {
            return f.createSlide( j, i, p ).prepend()
        }
    };
    f.insertSlideAfter = function( j, p, i, ag ) {
        if ( typeof j === "undefined" ) {
            return false
        }
        if ( p.nodeType ) {
            return f._extendSwiperSlide( p ).insertAfter( j )
        } else {
            return f.createSlide( p, i, ag ).insertAfter( j )
        }
    };
    f.removeSlide = function( i ) {
        if ( f.slides[i] ) {
            if ( a.loop ) {
                if ( !f.slides[i + f.loopedSlides] ) {
                    return false
                }
                f.slides[i + f.loopedSlides].remove();
                f.removeLoopedSlides();
                f.calcSlides();
                f.createLoop()
            } else {
                f.slides[i].remove()
            }
            return true
        } else {
            return false
        }
    };
    f.removeLastSlide = function() {
        if ( f.slides.length > 0 ) {
            if ( a.loop ) {
                f.slides[f.slides.length - 1 - f.loopedSlides].remove();
                f.removeLoopedSlides();
                f.calcSlides();
                f.createLoop()
            } else {
                f.slides[f.slides.length - 1].remove()
            }
            return true
        } else {
            return false
        }
    };
    f.removeAllSlides = function() {
        for ( var j = f.slides.length - 1; j >= 0; j-- ) {
            f.slides[j].remove()
        }
    };
    f.getSlide = function( i ) {
        return f.slides[i]
    };
    f.getLastSlide = function() {
        return f.slides[f.slides.length - 1]
    };
    f.getFirstSlide = function() {
        return f.slides[0]
    };
    f.activeSlide = function() {
        return f.slides[f.activeIndex]
    };
    f.fireCallback = function() {
        var p = arguments[0];
        if ( Object.prototype.toString.call( p ) === "[object Array]" ) {
            for ( var j = 0; j < p.length; j++ ) {
                if ( typeof p[j] === "function" ) {
                    p[j]( arguments[1], arguments[2], arguments[3], arguments[4], arguments[5] )
                }
            }
        } else {
            if ( Object.prototype.toString.call( p ) === "[object String]" ) {
                if ( a["on" + p] ) {
                    f.fireCallback( a["on" + p], arguments[1], arguments[2], arguments[3], arguments[4], arguments[5] )
                }
            } else {
                p( arguments[1], arguments[2], arguments[3], arguments[4], arguments[5] )
            }
        }
    };
    function B( i ) {
        if ( Object.prototype.toString.apply( i ) === "[object Array]" ) {
            return true
        }
        return false
    }

    f.addCallback = function( ag, i ) {
        var p = this, j;
        if ( p.params["on" + ag] ) {
            if ( B( this.params["on" + ag] ) ) {
                return this.params["on" + ag].push( i )
            } else {
                if ( typeof this.params["on" + ag] === "function" ) {
                    j = this.params["on" + ag];
                    this.params["on" + ag] = [];
                    this.params["on" + ag].push( j );
                    return this.params["on" + ag].push( i )
                }
            }
        } else {
            this.params["on" + ag] = [];
            return this.params["on" + ag].push( i )
        }
    };
    f.removeCallbacks = function( i ) {
        if ( f.params["on" + i] ) {
            f.params["on" + i] = null
        }
    };
    var A = [];
    for ( var ac in f.plugins ) {
        if ( a[ac] ) {
            var P = f.plugins[ac]( f, a[ac] );
            if ( P ) {
                A.push( P )
            }
        }
    }
    f.callPlugins = function( ag, j ) {
        if ( !j ) {
            j = {}
        }
        for ( var p = 0; p < A.length; p++ ) {
            if ( ag in A[p] ) {
                A[p][ag]( j )
            }
        }
    };
    if ( (f.browser.ie10 || f.browser.ie11) && !a.onlyExternal ) {
        f.wrapper.classList.add( "swiper-wp8-" + (D ? "horizontal" : "vertical") )
    }
    if ( a.freeMode ) {
        f.container.className += " swiper-free-mode"
    }
    f.initialized = false;
    f.init = function( aj, ai ) {
        var an = f.h.getWidth( f.container, false, a.roundLengths );
        var aA = f.h.getHeight( f.container, false, a.roundLengths );
        if ( an === f.width && aA === f.height && !aj ) {
            return
        }
        f.width = an;
        f.height = aA;
        var ao, aq, ax, ak, au, az;
        var aw;
        d = D ? an : aA;
        var ap = f.wrapper;
        if ( aj ) {
            f.calcSlides( ai )
        }
        if ( a.slidesPerView === "auto" ) {
            var al = 0;
            var ag = 0;
            if ( a.slidesOffset > 0 ) {
                ap.style.paddingLeft = "";
                ap.style.paddingRight = "";
                ap.style.paddingTop = "";
                ap.style.paddingBottom = ""
            }
            ap.style.width = "";
            ap.style.height = "";
            if ( a.offsetPxBefore > 0 ) {
                if ( D ) {
                    f.wrapperLeft = a.offsetPxBefore
                } else {
                    f.wrapperTop = a.offsetPxBefore
                }
            }
            if ( a.offsetPxAfter > 0 ) {
                if ( D ) {
                    f.wrapperRight = a.offsetPxAfter
                } else {
                    f.wrapperBottom = a.offsetPxAfter
                }
            }
            if ( a.centeredSlides ) {
                if ( D ) {
                    f.wrapperLeft = (d - this.slides[0].getWidth( true, a.roundLengths )) / 2;
                    f.wrapperRight = (d - f.slides[f.slides.length - 1].getWidth( true, a.roundLengths )) / 2
                } else {
                    f.wrapperTop = (d - f.slides[0].getHeight( true, a.roundLengths )) / 2;
                    f.wrapperBottom = (d - f.slides[f.slides.length - 1].getHeight( true, a.roundLengths )) / 2
                }
            }
            if ( D ) {
                if ( f.wrapperLeft >= 0 ) {
                    ap.style.paddingLeft = f.wrapperLeft + "px"
                }
                if ( f.wrapperRight >= 0 ) {
                    ap.style.paddingRight = f.wrapperRight + "px"
                }
            } else {
                if ( f.wrapperTop >= 0 ) {
                    ap.style.paddingTop = f.wrapperTop + "px"
                }
                if ( f.wrapperBottom >= 0 ) {
                    ap.style.paddingBottom = f.wrapperBottom + "px"
                }
            }
            az = 0;
            var ay = 0;
            f.snapGrid = [];
            f.slidesGrid = [];
            ax = 0;
            for ( aw = 0; aw < f.slides.length; aw++ ) {
                ao = f.slides[aw].getWidth( true, a.roundLengths );
                aq = f.slides[aw].getHeight( true, a.roundLengths );
                if ( a.calculateHeight ) {
                    ax = Math.max( ax, aq )
                }
                var at = D ? ao : aq;
                if ( a.centeredSlides ) {
                    var p = aw === f.slides.length - 1 ? 0 : f.slides[aw + 1].getWidth( true, a.roundLengths );
                    var ah = aw === f.slides.length - 1 ? 0 : f.slides[aw + 1].getHeight( true, a.roundLengths );
                    var am = D ? p : ah;
                    if ( at > d ) {
                        if ( a.slidesPerViewFit ) {
                            f.snapGrid.push( az + f.wrapperLeft );
                            f.snapGrid.push( az + at - d + f.wrapperLeft )
                        } else {
                            for ( var av = 0; av <= Math.floor( at / (d + f.wrapperLeft) ); av++ ) {
                                if ( av === 0 ) {
                                    f.snapGrid.push( az + f.wrapperLeft )
                                } else {
                                    f.snapGrid.push( az + f.wrapperLeft + d * av )
                                }
                            }
                        }
                        f.slidesGrid.push( az + f.wrapperLeft )
                    } else {
                        f.snapGrid.push( ay );
                        f.slidesGrid.push( ay )
                    }
                    ay += at / 2 + am / 2
                } else {
                    if ( at > d ) {
                        if ( a.slidesPerViewFit ) {
                            f.snapGrid.push( az );
                            f.snapGrid.push( az + at - d )
                        } else {
                            if ( d !== 0 ) {
                                for ( var ar = 0; ar <= Math.floor( at / d ); ar++ ) {
                                    f.snapGrid.push( az + d * ar )
                                }
                            } else {
                                f.snapGrid.push( az )
                            }
                        }
                    } else {
                        f.snapGrid.push( az )
                    }
                    f.slidesGrid.push( az )
                }
                az += at;
                al += ao;
                ag += aq
            }
            if ( a.calculateHeight ) {
                f.height = ax
            }
            if ( D ) {
                ae = al + f.wrapperRight + f.wrapperLeft;
                ap.style.width = (al) + "px";
                ap.style.height = (f.height) + "px"
            } else {
                ae = ag + f.wrapperTop + f.wrapperBottom;
                ap.style.width = (f.width) + "px";
                ap.style.height = (ag) + "px"
            }
        } else {
            if ( a.scrollContainer ) {
                ap.style.width = "";
                ap.style.height = "";
                ak = f.slides[0].getWidth( true, a.roundLengths );
                au = f.slides[0].getHeight( true, a.roundLengths );
                ae = D ? ak : au;
                ap.style.width = ak + "px";
                ap.style.height = au + "px";
                J = D ? ak : au
            } else {
                if ( a.calculateHeight ) {
                    ax = 0;
                    au = 0;
                    if ( !D ) {
                        f.container.style.height = ""
                    }
                    ap.style.height = "";
                    for ( aw = 0; aw < f.slides.length; aw++ ) {
                        f.slides[aw].style.height = "";
                        ax = Math.max( f.slides[aw].getHeight( true ), ax );
                        if ( !D ) {
                            au += f.slides[aw].getHeight( true )
                        }
                    }
                    aq = ax;
                    f.height = aq;
                    if ( D ) {
                        au = aq
                    } else {
                        d = aq;
                        f.container.style.height = d + "px"
                    }
                } else {
                    aq = D ? f.height : f.height / a.slidesPerView;
                    if ( a.roundLengths ) {
                        aq = Math.round( aq )
                    }
                    au = D ? f.height : f.slides.length * aq
                }
                ao = D ? f.width / a.slidesPerView : f.width;
                if ( a.roundLengths ) {
                    ao = Math.round( ao )
                }
                ak = D ? f.slides.length * ao : f.width;
                J = D ? ao : aq;
                if ( a.offsetSlidesBefore > 0 ) {
                    if ( D ) {
                        f.wrapperLeft = J * a.offsetSlidesBefore
                    } else {
                        f.wrapperTop = J * a.offsetSlidesBefore
                    }
                }
                if ( a.offsetSlidesAfter > 0 ) {
                    if ( D ) {
                        f.wrapperRight = J * a.offsetSlidesAfter
                    } else {
                        f.wrapperBottom = J * a.offsetSlidesAfter
                    }
                }
                if ( a.offsetPxBefore > 0 ) {
                    if ( D ) {
                        f.wrapperLeft = a.offsetPxBefore
                    } else {
                        f.wrapperTop = a.offsetPxBefore
                    }
                }
                if ( a.offsetPxAfter > 0 ) {
                    if ( D ) {
                        f.wrapperRight = a.offsetPxAfter
                    } else {
                        f.wrapperBottom = a.offsetPxAfter
                    }
                }
                if ( a.centeredSlides ) {
                    if ( D ) {
                        f.wrapperLeft = (d - J) / 2;
                        f.wrapperRight = (d - J) / 2
                    } else {
                        f.wrapperTop = (d - J) / 2;
                        f.wrapperBottom = (d - J) / 2
                    }
                }
                if ( D ) {
                    if ( f.wrapperLeft > 0 ) {
                        ap.style.paddingLeft = f.wrapperLeft + "px"
                    }
                    if ( f.wrapperRight > 0 ) {
                        ap.style.paddingRight = f.wrapperRight + "px"
                    }
                } else {
                    if ( f.wrapperTop > 0 ) {
                        ap.style.paddingTop = f.wrapperTop + "px"
                    }
                    if ( f.wrapperBottom > 0 ) {
                        ap.style.paddingBottom = f.wrapperBottom + "px"
                    }
                }
                ae = D ? ak + f.wrapperRight + f.wrapperLeft : au + f.wrapperTop + f.wrapperBottom;
                if ( !a.cssWidthAndHeight ) {
                    if ( parseFloat( ak ) > 0 ) {
                        ap.style.width = ak + "px"
                    }
                    if ( parseFloat( au ) > 0 ) {
                        ap.style.height = au + "px"
                    }
                }
                az = 0;
                f.snapGrid = [];
                f.slidesGrid = [];
                for ( aw = 0; aw < f.slides.length; aw++ ) {
                    f.snapGrid.push( az );
                    f.slidesGrid.push( az );
                    az += J;
                    if ( !a.cssWidthAndHeight ) {
                        if ( parseFloat( ao ) > 0 ) {
                            f.slides[aw].style.width = ao + "px"
                        }
                        if ( parseFloat( aq ) > 0 ) {
                            f.slides[aw].style.height = aq + "px"
                        }
                    }
                }
            }
        }
        if ( !f.initialized ) {
            f.callPlugins( "onFirstInit" );
            if ( a.onFirstInit ) {
                f.fireCallback( a.onFirstInit, f )
            }
        } else {
            f.callPlugins( "onInit" );
            if ( a.onInit ) {
                f.fireCallback( a.onInit, f )
            }
        }
        f.initialized = true
    };
    f.reInit = function( i ) {
        f.init( true, i )
    };
    f.resizeFix = function( i ) {
        f.callPlugins( "beforeResizeFix" );
        f.init( a.resizeReInit || i );
        if ( !a.freeMode ) {
            f.swipeTo( (a.loop ? f.activeLoopIndex : f.activeIndex), 0, false );
            if ( a.autoplay ) {
                if ( f.support.transitions && typeof K !== "undefined" ) {
                    if ( typeof K !== "undefined" ) {
                        clearTimeout( K );
                        K = undefined;
                        f.startAutoplay()
                    }
                } else {
                    if ( typeof X !== "undefined" ) {
                        clearInterval( X );
                        X = undefined;
                        f.startAutoplay()
                    }
                }
            }
        } else {
            if ( f.getWrapperTranslate() < -ab() ) {
                f.setWrapperTransition( 0 );
                f.setWrapperTranslate( -ab() )
            }
        }
        f.callPlugins( "afterResizeFix" )
    };
    function ab() {
        var i = (ae - d);
        if ( a.freeMode ) {
            i = ae - d
        }
        if ( a.slidesPerView > f.slides.length && !a.centeredSlides ) {
            i = 0
        }
        if ( i < 0 ) {
            i = 0
        }
        return i
    }

    function m() {
        var ai = f.h.addEventListener;
        var ah = a.eventTarget === "wrapper" ? f.wrapper : f.container;
        if ( !(f.browser.ie10 || f.browser.ie11) ) {
            if ( f.support.touch ) {
                ai( ah, "touchstart", U );
                ai( ah, "touchmove", aa );
                ai( ah, "touchend", N )
            }
            if ( a.simulateTouch ) {
                ai( ah, "mousedown", U );
                ai( document, "mousemove", aa );
                ai( document, "mouseup", N )
            }
        } else {
            ai( ah, f.touchEvents.touchStart, U );
            ai( document, f.touchEvents.touchMove, aa );
            ai( document, f.touchEvents.touchEnd, N )
        }
        if ( a.autoResize ) {
            ai( window, "resize", f.resizeFix )
        }
        q();
        f._wheelEvent = false;
        if ( a.mousewheelControl ) {
            if ( document.onmousewheel !== undefined ) {
                f._wheelEvent = "mousewheel"
            }
            if ( !f._wheelEvent ) {
                try {
                    new WheelEvent( "wheel" );
                    f._wheelEvent = "wheel"
                } catch ( ag ) {
                }
            }
            if ( !f._wheelEvent ) {
                f._wheelEvent = "DOMMouseScroll"
            }
            if ( f._wheelEvent ) {
                ai( f.container, f._wheelEvent, c )
            }
        }
        function p( aj ) {
            var i = new Image();
            i.onload = function() {
                if ( f && f.imagesLoaded !== undefined ) {
                    f.imagesLoaded++
                }
                if ( f.imagesLoaded === f.imagesToLoad.length ) {
                    f.reInit();
                    if ( a.onImagesReady ) {
                        f.fireCallback( a.onImagesReady, f )
                    }
                }
            };
            i.src = aj
        }

        if ( a.keyboardControl ) {
            ai( document, "keydown", F )
        }
        if ( a.updateOnImagesReady ) {
            f.imagesToLoad = R( "img", f.container );
            for ( var j = 0; j < f.imagesToLoad.length; j++ ) {
                p( f.imagesToLoad[j].getAttribute( "src" ) )
            }
        }
    }

    f.destroy = function() {
        var i = f.h.removeEventListener;
        var j = a.eventTarget === "wrapper" ? f.wrapper : f.container;
        if ( !(f.browser.ie10 || f.browser.ie11) ) {
            if ( f.support.touch ) {
                i( j, "touchstart", U );
                i( j, "touchmove", aa );
                i( j, "touchend", N )
            }
            if ( a.simulateTouch ) {
                i( j, "mousedown", U );
                i( document, "mousemove", aa );
                i( document, "mouseup", N )
            }
        } else {
            i( j, f.touchEvents.touchStart, U );
            i( document, f.touchEvents.touchMove, aa );
            i( document, f.touchEvents.touchEnd, N )
        }
        if ( a.autoResize ) {
            i( window, "resize", f.resizeFix )
        }
        o();
        if ( a.paginationClickable ) {
            C()
        }
        if ( a.mousewheelControl && f._wheelEvent ) {
            i( f.container, f._wheelEvent, c )
        }
        if ( a.keyboardControl ) {
            i( document, "keydown", F )
        }
        if ( a.autoplay ) {
            f.stopAutoplay()
        }
        f.callPlugins( "onDestroy" );
        f = null
    };
    function q() {
        var ah = f.h.addEventListener, ag;
        if ( a.preventLinks ) {
            var j = R( "a", f.container );
            for ( ag = 0; ag < j.length; ag++ ) {
                ah( j[ag], "click", O )
            }
        }
        if ( a.releaseFormElements ) {
            var p = R( "input, textarea, select", f.container );
            for ( ag = 0; ag < p.length; ag++ ) {
                ah( p[ag], f.touchEvents.touchStart, y, true )
            }
        }
        if ( a.onSlideClick ) {
            for ( ag = 0; ag < f.slides.length; ag++ ) {
                ah( f.slides[ag], "click", W )
            }
        }
        if ( a.onSlideTouch ) {
            for ( ag = 0; ag < f.slides.length; ag++ ) {
                ah( f.slides[ag], f.touchEvents.touchStart, k )
            }
        }
    }

    function o() {
        var ah = f.h.removeEventListener, ag;
        if ( a.onSlideClick ) {
            for ( ag = 0; ag < f.slides.length; ag++ ) {
                ah( f.slides[ag], "click", W )
            }
        }
        if ( a.onSlideTouch ) {
            for ( ag = 0; ag < f.slides.length; ag++ ) {
                ah( f.slides[ag], f.touchEvents.touchStart, k )
            }
        }
        if ( a.releaseFormElements ) {
            var p = R( "input, textarea, select", f.container );
            for ( ag = 0; ag < p.length; ag++ ) {
                ah( p[ag], f.touchEvents.touchStart, y, true )
            }
        }
        if ( a.preventLinks ) {
            var j = R( "a", f.container );
            for ( ag = 0; ag < j.length; ag++ ) {
                ah( j[ag], "click", O )
            }
        }
    }

    function F( am ) {
        var ak = am.keyCode || am.charCode;
        if ( am.shiftKey || am.altKey || am.ctrlKey || am.metaKey ) {
            return
        }
        if ( ak === 37 || ak === 39 || ak === 38 || ak === 40 ) {
            var an = false;
            var al = f.h.getOffset( f.container );
            var ai = f.h.windowScroll().left;
            var ag = f.h.windowScroll().top;
            var ah = f.h.windowWidth();
            var j = f.h.windowHeight();
            var p = [[al.left, al.top], [al.left + f.width, al.top], [al.left, al.top + f.height], [al.left + f.width, al.top + f.height]];
            for ( var aj = 0; aj < p.length; aj++ ) {
                var ao = p[aj];
                if ( ao[0] >= ai && ao[0] <= ai + ah && ao[1] >= ag && ao[1] <= ag + j ) {
                    an = true
                }
            }
            if ( !an ) {
                return
            }
        }
        if ( D ) {
            if ( ak === 37 || ak === 39 ) {
                if ( am.preventDefault ) {
                    am.preventDefault()
                } else {
                    am.returnValue = false
                }
            }
            if ( ak === 39 ) {
                f.swipeNext()
            }
            if ( ak === 37 ) {
                f.swipePrev()
            }
        } else {
            if ( ak === 38 || ak === 40 ) {
                if ( am.preventDefault ) {
                    am.preventDefault()
                } else {
                    am.returnValue = false
                }
            }
            if ( ak === 40 ) {
                f.swipeNext()
            }
            if ( ak === 38 ) {
                f.swipePrev()
            }
        }
    }

    f.disableKeyboardControl = function() {
        a.keyboardControl = false;
        f.h.removeEventListener( document, "keydown", F )
    };
    f.enableKeyboardControl = function() {
        a.keyboardControl = true;
        f.h.addEventListener( document, "keydown", F )
    };
    var T = (new Date()).getTime();

    function c( p ) {
        var j = f._wheelEvent;
        var ag = 0;
        if ( p.detail ) {
            ag = -p.detail
        } else {
            if ( j === "mousewheel" ) {
                if ( a.mousewheelControlForceToAxis ) {
                    if ( D ) {
                        if ( Math.abs( p.wheelDeltaX ) > Math.abs( p.wheelDeltaY ) ) {
                            ag = p.wheelDeltaX
                        } else {
                            return
                        }
                    } else {
                        if ( Math.abs( p.wheelDeltaY ) > Math.abs( p.wheelDeltaX ) ) {
                            ag = p.wheelDeltaY
                        } else {
                            return
                        }
                    }
                } else {
                    ag = p.wheelDelta
                }
            } else {
                if ( j === "DOMMouseScroll" ) {
                    ag = -p.detail
                } else {
                    if ( j === "wheel" ) {
                        if ( a.mousewheelControlForceToAxis ) {
                            if ( D ) {
                                if ( Math.abs( p.deltaX ) > Math.abs( p.deltaY ) ) {
                                    ag = -p.deltaX
                                } else {
                                    return
                                }
                            } else {
                                if ( Math.abs( p.deltaY ) > Math.abs( p.deltaX ) ) {
                                    ag = -p.deltaY
                                } else {
                                    return
                                }
                            }
                        } else {
                            ag = Math.abs( p.deltaX ) > Math.abs( p.deltaY ) ? -p.deltaX : -p.deltaY
                        }
                    }
                }
            }
        }
        if ( !a.freeMode ) {
            if ( (new Date()).getTime() - T > 60 ) {
                if ( ag < 0 ) {
                    f.swipeNext()
                } else {
                    f.swipePrev()
                }
            }
            T = (new Date()).getTime()
        } else {
            var i = f.getWrapperTranslate() + ag;
            if ( i > 0 ) {
                i = 0
            }
            if ( i < -ab() ) {
                i = -ab()
            }
            f.setWrapperTransition( 0 );
            f.setWrapperTranslate( i );
            f.updateActiveSlide( i );
            if ( i === 0 || i === -ab() ) {
                return
            }
        }
        if ( a.autoplay ) {
            f.stopAutoplay( true )
        }
        if ( p.preventDefault ) {
            p.preventDefault()
        } else {
            p.returnValue = false
        }
        return false
    }

    f.disableMousewheelControl = function() {
        if ( !f._wheelEvent ) {
            return false
        }
        a.mousewheelControl = false;
        f.h.removeEventListener( f.container, f._wheelEvent, c );
        return true
    };
    f.enableMousewheelControl = function() {
        if ( !f._wheelEvent ) {
            return false
        }
        a.mousewheelControl = true;
        f.h.addEventListener( f.container, f._wheelEvent, c );
        return true
    };
    if ( a.grabCursor ) {
        var g = f.container.style;
        g.cursor = "move";
        g.cursor = "grab";
        g.cursor = "-moz-grab";
        g.cursor = "-webkit-grab"
    }
    f.allowSlideClick = true;
    function W( i ) {
        if ( f.allowSlideClick ) {
            E( i );
            f.fireCallback( a.onSlideClick, f, i )
        }
    }

    function k( i ) {
        E( i );
        f.fireCallback( a.onSlideTouch, f, i )
    }

    function E( j ) {
        if ( !j.currentTarget ) {
            var i = j.srcElement;
            do {
                if ( i.className.indexOf( a.slideClass ) > -1 ) {
                    break
                }
                i = i.parentNode
            } while ( i );
            f.clickedSlide = i
        } else {
            f.clickedSlide = j.currentTarget
        }
        f.clickedSlideIndex = f.slides.indexOf( f.clickedSlide );
        f.clickedSlideLoopIndex = f.clickedSlideIndex - (f.loopedSlides || 0)
    }

    f.allowLinks = true;
    function O( i ) {
        if ( !f.allowLinks ) {
            if ( i.preventDefault ) {
                i.preventDefault()
            } else {
                i.returnValue = false
            }
            if ( a.preventLinksPropagation && "stopPropagation" in i ) {
                i.stopPropagation()
            }
            return false
        }
    }

    function y( i ) {
        if ( i.stopPropagation ) {
            i.stopPropagation()
        } else {
            i.returnValue = false
        }
        return false
    }

    var w = false;
    var L;
    var ad = true;

    function U( p ) {
        if ( a.preventLinks ) {
            f.allowLinks = true
        }
        if ( f.isTouched || a.onlyExternal ) {
            return false
        }
        if ( a.noSwiping && (p.target || p.srcElement) && I( p.target || p.srcElement ) ) {
            return false
        }
        ad = false;
        f.isTouched = true;
        w = p.type === "touchstart";
        if ( !w || p.targetTouches.length === 1 ) {
            f.callPlugins( "onTouchStartBegin" );
            if ( !w && !f.isAndroid ) {
                if ( p.preventDefault ) {
                    p.preventDefault()
                } else {
                    p.returnValue = false
                }
            }
            var j = w ? p.targetTouches[0].pageX : (p.pageX || p.clientX);
            var i = w ? p.targetTouches[0].pageY : (p.pageY || p.clientY);
            f.touches.startX = f.touches.currentX = j;
            f.touches.startY = f.touches.currentY = i;
            f.touches.start = f.touches.current = D ? j : i;
            f.setWrapperTransition( 0 );
            f.positions.start = f.positions.current = f.getWrapperTranslate();
            f.setWrapperTranslate( f.positions.start );
            f.times.start = (new Date()).getTime();
            b = undefined;
            if ( a.moveStartThreshold > 0 ) {
                L = false
            }
            if ( a.onTouchStart ) {
                f.fireCallback( a.onTouchStart, f, p )
            }
            f.callPlugins( "onTouchStartEnd" )
        }
    }

    var h, H;

    function aa( ah ) {
        if ( !f.isTouched || a.onlyExternal ) {
            return
        }
        if ( w && ah.type === "mousemove" ) {
            return
        }
        var ag = w ? ah.targetTouches[0].pageX : (ah.pageX || ah.clientX);
        var j = w ? ah.targetTouches[0].pageY : (ah.pageY || ah.clientY);
        if ( typeof b === "undefined" && D ) {
            b = !!(b || Math.abs( j - f.touches.startY ) > Math.abs( ag - f.touches.startX ))
        }
        if ( typeof b === "undefined" && !D ) {
            b = !!(b || Math.abs( j - f.touches.startY ) < Math.abs( ag - f.touches.startX ))
        }
        if ( b ) {
            f.isTouched = false;
            return
        }
        if ( ah.assignedToSwiper ) {
            f.isTouched = false;
            return
        }
        ah.assignedToSwiper = true;
        if ( a.preventLinks ) {
            f.allowLinks = false
        }
        if ( a.onSlideClick ) {
            f.allowSlideClick = false
        }
        if ( a.autoplay ) {
            f.stopAutoplay( true )
        }
        if ( !w || ah.touches.length === 1 ) {
            if ( !f.isMoved ) {
                f.callPlugins( "onTouchMoveStart" );
                if ( a.loop ) {
                    f.fixLoop();
                    f.positions.start = f.getWrapperTranslate()
                }
                if ( a.onTouchMoveStart ) {
                    f.fireCallback( a.onTouchMoveStart, f )
                }
            }
            f.isMoved = true;
            if ( ah.preventDefault ) {
                ah.preventDefault()
            } else {
                ah.returnValue = false
            }
            f.touches.current = D ? ag : j;
            f.positions.current = (f.touches.current - f.touches.start) * a.touchRatio + f.positions.start;
            if ( f.positions.current > 0 && a.onResistanceBefore ) {
                f.fireCallback( a.onResistanceBefore, f, f.positions.current )
            }
            if ( f.positions.current < -ab() && a.onResistanceAfter ) {
                f.fireCallback( a.onResistanceAfter, f, Math.abs( f.positions.current + ab() ) )
            }
            if ( a.resistance && a.resistance !== "100%" ) {
                var p;
                if ( f.positions.current > 0 ) {
                    p = 1 - f.positions.current / d / 2;
                    if ( p < 0.5 ) {
                        f.positions.current = (d / 2)
                    } else {
                        f.positions.current = f.positions.current * p
                    }
                }
                if ( f.positions.current < -ab() ) {
                    var ai = (f.touches.current - f.touches.start) * a.touchRatio + (ab() + f.positions.start);
                    p = (d + ai) / (d);
                    var i = f.positions.current - ai * (1 - p) / 2;
                    var aj = -ab() - d / 2;
                    if ( i < aj || p <= 0 ) {
                        f.positions.current = aj
                    } else {
                        f.positions.current = i
                    }
                }
            }
            if ( a.resistance && a.resistance === "100%" ) {
                if ( f.positions.current > 0 && !(a.freeMode && !a.freeModeFluid) ) {
                    f.positions.current = 0
                }
                if ( f.positions.current < -ab() && !(a.freeMode && !a.freeModeFluid) ) {
                    f.positions.current = -ab()
                }
            }
            if ( !a.followFinger ) {
                return
            }
            if ( !a.moveStartThreshold ) {
                f.setWrapperTranslate( f.positions.current )
            } else {
                if ( Math.abs( f.touches.current - f.touches.start ) > a.moveStartThreshold || L ) {
                    if ( !L ) {
                        L = true;
                        f.touches.start = f.touches.current;
                        return
                    }
                    f.setWrapperTranslate( f.positions.current )
                } else {
                    f.positions.current = f.positions.start
                }
            }
            if ( a.freeMode || a.watchActiveIndex ) {
                f.updateActiveSlide( f.positions.current )
            }
            if ( a.grabCursor ) {
                f.container.style.cursor = "move";
                f.container.style.cursor = "grabbing";
                f.container.style.cursor = "-moz-grabbin";
                f.container.style.cursor = "-webkit-grabbing"
            }
            if ( !h ) {
                h = f.touches.current
            }
            if ( !H ) {
                H = (new Date()).getTime()
            }
            f.velocity = (f.touches.current - h) / ((new Date()).getTime() - H) / 2;
            if ( Math.abs( f.touches.current - h ) < 2 ) {
                f.velocity = 0
            }
            h = f.touches.current;
            H = (new Date()).getTime();
            f.callPlugins( "onTouchMoveEnd" );
            if ( a.onTouchMove ) {
                f.fireCallback( a.onTouchMove, f, ah )
            }
            return false
        }
    }

    function N( p ) {
        if ( b ) {
            f.swipeReset()
        }
        if ( a.onlyExternal || !f.isTouched ) {
            return
        }
        f.isTouched = false;
        if ( a.grabCursor ) {
            f.container.style.cursor = "move";
            f.container.style.cursor = "grab";
            f.container.style.cursor = "-moz-grab";
            f.container.style.cursor = "-webkit-grab"
        }
        if ( !f.positions.current && f.positions.current !== 0 ) {
            f.positions.current = f.positions.start
        }
        if ( a.followFinger ) {
            f.setWrapperTranslate( f.positions.current )
        }
        f.times.end = (new Date()).getTime();
        f.touches.diff = f.touches.current - f.touches.start;
        f.touches.abs = Math.abs( f.touches.diff );
        f.positions.diff = f.positions.current - f.positions.start;
        f.positions.abs = Math.abs( f.positions.diff );
        var aq = f.positions.diff;
        var au = f.positions.abs;
        var j = f.times.end - f.times.start;
        if ( au < 5 && (j) < 300 && f.allowLinks === false ) {
            if ( !a.freeMode && au !== 0 ) {
                f.swipeReset()
            }
            if ( a.preventLinks ) {
                f.allowLinks = true
            }
            if ( a.onSlideClick ) {
                f.allowSlideClick = true
            }
        }
        setTimeout(
            function() {
                if ( a.preventLinks ) {
                    f.allowLinks = true
                }
                if ( a.onSlideClick ) {
                    f.allowSlideClick = true
                }
            }, 100
        );
        var am = ab();
        if ( !f.isMoved && a.freeMode ) {
            f.isMoved = false;
            if ( a.onTouchEnd ) {
                f.fireCallback( a.onTouchEnd, f, p )
            }
            f.callPlugins( "onTouchEnd" );
            return
        }
        if ( !f.isMoved || f.positions.current > 0 || f.positions.current < -am ) {
            f.swipeReset();
            if ( a.onTouchEnd ) {
                f.fireCallback( a.onTouchEnd, f, p )
            }
            f.callPlugins( "onTouchEnd" );
            return
        }
        f.isMoved = false;
        if ( a.freeMode ) {
            if ( a.freeModeFluid ) {
                var an = 1000 * a.momentumRatio;
                var aj = f.velocity * an;
                var ai = f.positions.current + aj;
                var ah = false;
                var ao;
                var at = Math.abs( f.velocity ) * 20 * a.momentumBounceRatio;
                if ( ai < -am ) {
                    if ( a.momentumBounce && f.support.transitions ) {
                        if ( ai + am < -at ) {
                            ai = -am - at
                        }
                        ao = -am;
                        ah = true;
                        ad = true
                    } else {
                        ai = -am
                    }
                }
                if ( ai > 0 ) {
                    if ( a.momentumBounce && f.support.transitions ) {
                        if ( ai > at ) {
                            ai = at
                        }
                        ao = 0;
                        ah = true;
                        ad = true
                    } else {
                        ai = 0
                    }
                }
                if ( f.velocity !== 0 ) {
                    an = Math.abs( (ai - f.positions.current) / f.velocity )
                }
                f.setWrapperTranslate( ai );
                f.setWrapperTransition( an );
                if ( a.momentumBounce && ah ) {
                    f.wrapperTransitionEnd(
                        function() {
                            if ( !ad ) {
                                return
                            }
                            if ( a.onMomentumBounce ) {
                                f.fireCallback( a.onMomentumBounce, f )
                            }
                            f.callPlugins( "onMomentumBounce" );
                            f.setWrapperTranslate( ao );
                            f.setWrapperTransition( 300 )
                        }
                    )
                }
                f.updateActiveSlide( ai )
            }
            if ( !a.freeModeFluid || j >= 300 ) {
                f.updateActiveSlide( f.positions.current )
            }
            if ( a.onTouchEnd ) {
                f.fireCallback( a.onTouchEnd, f, p )
            }
            f.callPlugins( "onTouchEnd" );
            return
        }
        r = aq < 0 ? "toNext" : "toPrev";
        if ( r === "toNext" && (j <= 300) ) {
            if ( au < 30 || !a.shortSwipes ) {
                f.swipeReset()
            } else {
                f.swipeNext( true )
            }
        }
        if ( r === "toPrev" && (j <= 300) ) {
            if ( au < 30 || !a.shortSwipes ) {
                f.swipeReset()
            } else {
                f.swipePrev( true )
            }
        }
        var ar = 0;
        if ( a.slidesPerView === "auto" ) {
            var ag = Math.abs( f.getWrapperTranslate() );
            var ap = 0;
            var al;
            for ( var ak = 0; ak < f.slides.length; ak++ ) {
                al = D ? f.slides[ak].getWidth( true, a.roundLengths ) : f.slides[ak].getHeight( true, a.roundLengths );
                ap += al;
                if ( ap > ag ) {
                    ar = al;
                    break
                }
            }
            if ( ar > d ) {
                ar = d
            }
        } else {
            ar = J * a.slidesPerView
        }
        if ( r === "toNext" && (j > 300) ) {
            if ( au >= ar * a.longSwipesRatio ) {
                f.swipeNext( true )
            } else {
                f.swipeReset()
            }
        }
        if ( r === "toPrev" && (j > 300) ) {
            if ( au >= ar * a.longSwipesRatio ) {
                f.swipePrev( true )
            } else {
                f.swipeReset()
            }
        }
        if ( a.onTouchEnd ) {
            f.fireCallback( a.onTouchEnd, f, p )
        }
        f.callPlugins( "onTouchEnd" )
    }

    function I( j ) {
        var i = false;
        do {
            if ( j.className.indexOf( a.noSwipingClass ) > -1 ) {
                i = true
            }
            j = j.parentElement
        } while ( !i && j.parentElement && j.className.indexOf( a.wrapperClass ) === -1 );
        if ( !i && j.className.indexOf( a.wrapperClass ) > -1 && j.className.indexOf( a.noSwipingClass ) > -1 ) {
            i = true
        }
        return i
    }

    function M( i, j ) {
        var p = document.createElement( "div" );
        var ag;
        p.innerHTML = j;
        ag = p.firstChild;
        ag.className += " " + i;
        return ag.outerHTML
    }

    f.swipeNext = function( p ) {
        if ( !p && a.loop ) {
            f.fixLoop()
        }
        if ( !p && a.autoplay ) {
            f.stopAutoplay( true )
        }
        f.callPlugins( "onSwipeNext" );
        var aj = f.getWrapperTranslate();
        var ah = aj;
        if ( a.slidesPerView === "auto" ) {
            for ( var ag = 0; ag < f.snapGrid.length; ag++ ) {
                if ( -aj >= f.snapGrid[ag] && -aj < f.snapGrid[ag + 1] ) {
                    ah = -f.snapGrid[ag + 1];
                    break
                }
            }
        } else {
            var j = J * a.slidesPerGroup;
            ah = -(Math.floor( Math.abs( aj ) / Math.floor( j ) ) * j + j)
        }
        if ( ah < -ab() ) {
            ah = -ab()
        }
        if ( ah === aj ) {
            var ai = jQuery( f.container );
            if ( ai.data( "type" ) === "curtain" && ai.data( "continue" ) ) {
                SWIFTSLIDER.curtainAdvance( ai )
            } else {
                return false
            }
        }
        n( ah, "next" );
        return true
    };
    f.swipePrev = function( p ) {
        if ( !p && a.loop ) {
            f.fixLoop()
        }
        if ( !p && a.autoplay ) {
            f.stopAutoplay( true )
        }
        f.callPlugins( "onSwipePrev" );
        var ai = jQuery( f.container );
        if ( ai.data( "type" ) === "curtain" && SWIFTSLIDER.curtainAnimating ) {
            return false
        }
        var aj = Math.ceil( f.getWrapperTranslate() );
        var ah;
        if ( a.slidesPerView === "auto" ) {
            ah = 0;
            for ( var ag = 1; ag < f.snapGrid.length; ag++ ) {
                if ( -aj === f.snapGrid[ag] ) {
                    ah = -f.snapGrid[ag - 1];
                    break
                }
                if ( -aj > f.snapGrid[ag] && -aj < f.snapGrid[ag + 1] ) {
                    ah = -f.snapGrid[ag];
                    break
                }
            }
        } else {
            var j = J * a.slidesPerGroup;
            ah = -(Math.ceil( -aj / j ) - 1) * j
        }
        if ( ah > 0 ) {
            ah = 0
        }
        if ( ah === aj ) {
            return false
        }
        n( ah, "prev" );
        return true
    };
    f.swipeReset = function() {
        f.callPlugins( "onSwipeReset" );
        var ah = f.getWrapperTranslate();
        var j = J * a.slidesPerGroup;
        var ag;
        var ai = -ab();
        if ( a.slidesPerView === "auto" ) {
            ag = 0;
            for ( var p = 0; p < f.snapGrid.length; p++ ) {
                if ( -ah === f.snapGrid[p] ) {
                    return
                }
                if ( -ah >= f.snapGrid[p] && -ah < f.snapGrid[p + 1] ) {
                    if ( f.positions.diff > 0 ) {
                        ag = -f.snapGrid[p + 1]
                    } else {
                        ag = -f.snapGrid[p]
                    }
                    break
                }
            }
            if ( -ah >= f.snapGrid[f.snapGrid.length - 1] ) {
                ag = -f.snapGrid[f.snapGrid.length - 1]
            }
            if ( ah <= -ab() ) {
                ag = -ab()
            }
        } else {
            ag = ah < 0 ? Math.round( ah / j ) * j : 0
        }
        if ( a.scrollContainer ) {
            ag = ah < 0 ? ah : 0
        }
        if ( ag < -ab() ) {
            ag = -ab()
        }
        if ( a.scrollContainer && (d > J) ) {
            ag = 0
        }
        if ( ag === ah ) {
            return false
        }
        n( ag, "reset" );
        return true
    };
    f.swipeTo = function( i, ag, ah ) {
        i = parseInt( i, 10 );
        f.callPlugins( "onSwipeTo", {index: i, speed: ag} );
        if ( a.loop ) {
            i = i + f.loopedSlides
        }
        var p = f.getWrapperTranslate();
        if ( i > (f.slides.length - 1) || i < 0 ) {
            return
        }
        var j;
        if ( a.slidesPerView === "auto" ) {
            j = -f.slidesGrid[i]
        } else {
            j = -i * J
        }
        if ( j < -ab() ) {
            j = -ab()
        }
        if ( j === p ) {
            return false
        }
        ah = ah === false ? false : true;
        n( j, "to", {index: i, speed: ag, runCallbacks: ah} );
        return true
    };
    function n( ag, ah, am ) {
        var j = (ah === "to" && am.speed >= 0) ? am.speed : a.speed;
        var aj = +new Date();

        function ai() {
            var an = +new Date();
            var ao = an - aj;
            p += ak * ao / (1000 / 60);
            i = al === "toNext" ? p > ag : p < ag;
            if ( i ) {
                f.setWrapperTranslate( Math.round( p ) );
                f._DOMAnimating = true;
                window.setTimeout(
                    function() {
                        ai()
                    }, 1000 / 60
                )
            } else {
                if ( a.onSlideChangeEnd ) {
                    if ( ah === "to" ) {
                        if ( am.runCallbacks === true ) {
                            f.fireCallback( a.onSlideChangeEnd, f )
                        }
                    } else {
                        f.fireCallback( a.onSlideChangeEnd, f )
                    }
                }
                f.setWrapperTranslate( ag );
                f._DOMAnimating = false
            }
        }

        if ( f.support.transitions || !a.DOMAnimation ) {
            f.setWrapperTranslate( ag );
            f.setWrapperTransition( j )
        } else {
            var p = f.getWrapperTranslate();
            var ak = Math.ceil( (ag - p) / j * (1000 / 60) );
            var al = p > ag ? "toNext" : "toPrev";
            var i = al === "toNext" ? p > ag : p < ag;
            if ( f._DOMAnimating ) {
                return
            }
            ai()
        }
        f.updateActiveSlide( ag );
        if ( a.onSlideNext && ah === "next" ) {
            f.fireCallback( a.onSlideNext, f, ag )
        }
        if ( a.onSlidePrev && ah === "prev" ) {
            f.fireCallback( a.onSlidePrev, f, ag )
        }
        if ( a.onSlideReset && ah === "reset" ) {
            f.fireCallback( a.onSlideReset, f, ag )
        }
        if ( ah === "next" || ah === "prev" || (ah === "to" && am.runCallbacks === true) ) {
            af( ah )
        }
    }

    f._queueStartCallbacks = false;
    f._queueEndCallbacks = false;
    function af( i ) {
        f.callPlugins( "onSlideChangeStart" );
        if ( a.onSlideChangeStart ) {
            if ( a.queueStartCallbacks && f.support.transitions ) {
                if ( f._queueStartCallbacks ) {
                    return
                }
                f._queueStartCallbacks = true;
                f.fireCallback( a.onSlideChangeStart, f, i );
                f.wrapperTransitionEnd(
                    function() {
                        f._queueStartCallbacks = false
                    }
                )
            } else {
                f.fireCallback( a.onSlideChangeStart, f, i )
            }
        }
        if ( a.onSlideChangeEnd ) {
            if ( f.support.transitions ) {
                if ( a.queueEndCallbacks ) {
                    if ( f._queueEndCallbacks ) {
                        return
                    }
                    f._queueEndCallbacks = true;
                    f.wrapperTransitionEnd(
                        function( j ) {
                            f.fireCallback( a.onSlideChangeEnd, j, i )
                        }
                    )
                } else {
                    f.wrapperTransitionEnd(
                        function( j ) {
                            f.fireCallback( a.onSlideChangeEnd, j, i )
                        }
                    )
                }
            } else {
                if ( !a.DOMAnimation ) {
                    setTimeout(
                        function() {
                            f.fireCallback( a.onSlideChangeEnd, f, i )
                        }, 10
                    )
                }
            }
        }
    }

    f.updateActiveSlide = function( aj ) {
        if ( !f.initialized ) {
            return
        }
        if ( f.slides.length === 0 ) {
            return
        }
        f.previousIndex = f.activeIndex;
        if ( typeof aj === "undefined" ) {
            aj = f.getWrapperTranslate()
        }
        if ( aj > 0 ) {
            aj = 0
        }
        var ai;
        if ( a.slidesPerView === "auto" ) {
            var am = 0;
            f.activeIndex = f.slidesGrid.indexOf( -aj );
            if ( f.activeIndex < 0 ) {
                for ( ai = 0; ai < f.slidesGrid.length - 1; ai++ ) {
                    if ( -aj > f.slidesGrid[ai] && -aj < f.slidesGrid[ai + 1] ) {
                        break
                    }
                }
                var ag = Math.abs( f.slidesGrid[ai] + aj );
                var p = Math.abs( f.slidesGrid[ai + 1] + aj );
                if ( ag <= p ) {
                    f.activeIndex = ai
                } else {
                    f.activeIndex = ai + 1
                }
            }
        } else {
            f.activeIndex = Math[a.visibilityFullFit ? "ceil" : "round"]( -aj / J )
        }
        if ( f.activeIndex === f.slides.length ) {
            f.activeIndex = f.slides.length - 1
        }
        if ( f.activeIndex < 0 ) {
            f.activeIndex = 0
        }
        if ( !f.slides[f.activeIndex] ) {
            return
        }
        f.calcVisibleSlides( aj );
        if ( f.support.classList ) {
            var ak;
            for ( ai = 0; ai < f.slides.length; ai++ ) {
                ak = f.slides[ai];
                ak.classList.remove( a.slideActiveClass );
                if ( f.visibleSlides.indexOf( ak ) >= 0 ) {
                    ak.classList.add( a.slideVisibleClass )
                } else {
                    ak.classList.remove( a.slideVisibleClass )
                }
            }
            f.slides[f.activeIndex].classList.add( a.slideActiveClass )
        } else {
            var al = new RegExp( "\\s*" + a.slideActiveClass );
            var j = new RegExp( "\\s*" + a.slideVisibleClass );
            for ( ai = 0; ai < f.slides.length; ai++ ) {
                f.slides[ai].className = f.slides[ai].className.replace( al, "" ).replace( j, "" );
                if ( f.visibleSlides.indexOf( f.slides[ai] ) >= 0 ) {
                    f.slides[ai].className += " " + a.slideVisibleClass
                }
            }
            f.slides[f.activeIndex].className += " " + a.slideActiveClass
        }
        if ( a.loop ) {
            var ah = f.loopedSlides;
            f.activeLoopIndex = f.activeIndex - ah;
            if ( f.activeLoopIndex >= f.slides.length - ah * 2 ) {
                f.activeLoopIndex = f.slides.length - ah * 2 - f.activeLoopIndex
            }
            if ( f.activeLoopIndex < 0 ) {
                f.activeLoopIndex = f.slides.length - ah * 2 + f.activeLoopIndex
            }
            if ( f.activeLoopIndex < 0 ) {
                f.activeLoopIndex = 0
            }
        } else {
            f.activeLoopIndex = f.activeIndex
        }
        if ( a.pagination ) {
            f.updatePagination( aj )
        }
    };
    f.createPagination = function( p ) {
        if ( a.paginationClickable && f.paginationButtons ) {
            C()
        }
        f.paginationContainer = a.pagination.nodeType ? a.pagination : R( a.pagination )[0];
        if ( a.createPagination ) {
            var j = "";
            var ai = f.slides.length;
            var ah = ai;
            if ( a.loop ) {
                ah -= f.loopedSlides * 2
            }
            for ( var ag = 0; ag < ah; ag++ ) {
                j += "<" + a.paginationElement + ' class="' + a.paginationElementClass + '"></' + a.paginationElement + ">"
            }
            f.paginationContainer.innerHTML = j
        }
        f.paginationButtons = R( "." + a.paginationElementClass, f.paginationContainer );
        if ( !p ) {
            f.updatePagination()
        }
        f.callPlugins( "onCreatePagination" );
        if ( a.paginationClickable ) {
            Y()
        }
    };
    function C() {
        var j = f.paginationButtons;
        if ( j ) {
            for ( var p = 0; p < j.length; p++ ) {
                f.h.removeEventListener( j[p], "click", u )
            }
        }
    }

    function Y() {
        var j = f.paginationButtons;
        if ( j ) {
            for ( var p = 0; p < j.length; p++ ) {
                f.h.addEventListener( j[p], "click", u )
            }
        }
    }

    function u( ai ) {
        var p;
        var ah = ai.target || ai.srcElement;
        var j = f.paginationButtons;
        for ( var ag = 0; ag < j.length; ag++ ) {
            if ( ah === j[ag] ) {
                p = ag
            }
        }
        f.swipeTo( p )
    }

    f.updatePagination = function( p ) {
        if ( !a.pagination ) {
            return
        }
        if ( f.slides.length < 1 ) {
            return
        }
        var ak = R( "." + a.paginationActiveClass, f.paginationContainer );
        if ( !ak ) {
            return
        }
        var ai = f.paginationButtons;
        if ( ai.length === 0 ) {
            return
        }
        for ( var aj = 0; aj < ai.length; aj++ ) {
            ai[aj].className = a.paginationElementClass
        }
        var am = a.loop ? f.loopedSlides : 0;
        if ( a.paginationAsRange ) {
            if ( !f.visibleSlides ) {
                f.calcVisibleSlides( p )
            }
            var ag = [];
            var ah;
            for ( ah = 0; ah < f.visibleSlides.length; ah++ ) {
                var al = f.slides.indexOf( f.visibleSlides[ah] ) - am;
                if ( a.loop && al < 0 ) {
                    al = f.slides.length - f.loopedSlides * 2 + al
                }
                if ( a.loop && al >= f.slides.length - f.loopedSlides * 2 ) {
                    al = f.slides.length - f.loopedSlides * 2 - al;
                    al = Math.abs( al )
                }
                ag.push( al )
            }
            for ( ah = 0; ah < ag.length; ah++ ) {
                if ( ai[ag[ah]] ) {
                    ai[ag[ah]].className += " " + a.paginationVisibleClass
                }
            }
            if ( a.loop ) {
                if ( ai[f.activeLoopIndex] !== undefined ) {
                    ai[f.activeLoopIndex].className += " " + a.paginationActiveClass
                }
            } else {
                ai[f.activeIndex].className += " " + a.paginationActiveClass
            }
        } else {
            if ( a.loop ) {
                if ( ai[f.activeLoopIndex] ) {
                    ai[f.activeLoopIndex].className += " " + a.paginationActiveClass + " " + a.paginationVisibleClass
                }
            } else {
                ai[f.activeIndex].className += " " + a.paginationActiveClass + " " + a.paginationVisibleClass
            }
        }
    };
    f.calcVisibleSlides = function( j ) {
        var ai = [];
        var ak = 0, ah = 0, aj = 0;
        if ( D && f.wrapperLeft > 0 ) {
            j = j + f.wrapperLeft
        }
        if ( !D && f.wrapperTop > 0 ) {
            j = j + f.wrapperTop
        }
        for ( var ag = 0; ag < f.slides.length; ag++ ) {
            ak += ah;
            if ( a.slidesPerView === "auto" ) {
                ah = D ? f.h.getWidth( f.slides[ag], true, a.roundLengths ) : f.h.getHeight(
                    f.slides[ag], true, a.roundLengths
                )
            } else {
                ah = J
            }
            aj = ak + ah;
            var p = false;
            if ( a.visibilityFullFit ) {
                if ( ak >= -j && aj <= -j + d ) {
                    p = true
                }
                if ( ak <= -j && aj >= -j + d ) {
                    p = true
                }
            } else {
                if ( aj > -j && aj <= ((-j + d)) ) {
                    p = true
                }
                if ( ak >= -j && ak < ((-j + d)) ) {
                    p = true
                }
                if ( ak < -j && aj > ((-j + d)) ) {
                    p = true
                }
            }
            if ( p ) {
                ai.push( f.slides[ag] )
            }
        }
        if ( ai.length === 0 ) {
            ai = [f.slides[f.activeIndex]]
        }
        f.visibleSlides = ai
    };
    var K, X;
    f.startAutoplay = function() {
        if ( f.support.transitions ) {
            if ( typeof K !== "undefined" ) {
                return false
            }
            if ( !a.autoplay ) {
                return
            }
            f.callPlugins( "onAutoplayStart" );
            if ( a.onAutoplayStart ) {
                f.fireCallback( a.onAutoplayStart, f )
            }
            Z()
        } else {
            if ( typeof X !== "undefined" ) {
                return false
            }
            if ( !a.autoplay ) {
                return
            }
            f.callPlugins( "onAutoplayStart" );
            if ( a.onAutoplayStart ) {
                f.fireCallback( a.onAutoplayStart, f )
            }
            X = setInterval(
                function() {
                    if ( a.loop ) {
                        f.fixLoop();
                        f.swipeNext( true )
                    } else {
                        if ( !f.swipeNext( true ) ) {
                            if ( !a.autoplayStopOnLast ) {
                                f.swipeTo( 0 )
                            } else {
                                clearInterval( X );
                                X = undefined
                            }
                        }
                    }
                }, a.autoplay
            )
        }
    };
    f.stopAutoplay = function( i ) {
        if ( f.support.transitions ) {
            if ( !K ) {
                return
            }
            if ( K ) {
                clearTimeout( K )
            }
            K = undefined;
            if ( i && !a.autoplayDisableOnInteraction ) {
                f.wrapperTransitionEnd(
                    function() {
                        Z()
                    }
                )
            }
            f.callPlugins( "onAutoplayStop" );
            if ( a.onAutoplayStop ) {
                f.fireCallback( a.onAutoplayStop, f )
            }
        } else {
            if ( X ) {
                clearInterval( X )
            }
            X = undefined;
            f.callPlugins( "onAutoplayStop" );
            if ( a.onAutoplayStop ) {
                f.fireCallback( a.onAutoplayStop, f )
            }
        }
    };
    function Z() {
        K = setTimeout(
            function() {
                if ( a.loop ) {
                    f.fixLoop();
                    f.swipeNext( true )
                } else {
                    if ( !f.swipeNext( true ) ) {
                        if ( !a.autoplayStopOnLast ) {
                            f.swipeTo( 0 )
                        } else {
                            clearTimeout( K );
                            K = undefined
                        }
                    }
                }
                f.wrapperTransitionEnd(
                    function() {
                        if ( typeof K !== "undefined" ) {
                            Z()
                        }
                    }
                )
            }, a.autoplay
        )
    }

    f.loopCreated = false;
    f.removeLoopedSlides = function() {
        if ( f.loopCreated ) {
            for ( var j = 0; j < f.slides.length; j++ ) {
                if ( f.slides[j].getData( "looped" ) === true ) {
                    f.wrapper.removeChild( f.slides[j] )
                }
            }
        }
    };
    f.createLoop = function() {
        if ( f.slides.length === 0 ) {
            return
        }
        if ( a.slidesPerView === "auto" ) {
            f.loopedSlides = a.loopedSlides || 1
        } else {
            f.loopedSlides = a.slidesPerView + a.loopAdditionalSlides
        }
        if ( f.loopedSlides > f.slides.length ) {
            f.loopedSlides = f.slides.length
        }
        var an = "", ak = "", aj;
        var ai = "";
        var ao = f.slides.length;
        var ag = Math.floor( f.loopedSlides / ao );
        var am = f.loopedSlides % ao;
        for ( aj = 0; aj < (ag * ao); aj++ ) {
            var ah = aj;
            if ( aj >= ao ) {
                var al = Math.floor( aj / ao );
                ah = aj - (ao * al)
            }
            ai += f.slides[ah].outerHTML
        }
        for ( aj = 0; aj < am; aj++ ) {
            ak += M( a.slideDuplicateClass, f.slides[aj].outerHTML )
        }
        for ( aj = ao - am; aj < ao; aj++ ) {
            an += M( a.slideDuplicateClass, f.slides[aj].outerHTML )
        }
        var p = an + ai + z.innerHTML + ai + ak;
        z.innerHTML = p;
        f.loopCreated = true;
        f.calcSlides();
        for ( aj = 0; aj < f.slides.length; aj++ ) {
            if ( aj < f.loopedSlides || aj >= f.slides.length - f.loopedSlides ) {
                f.slides[aj].setData( "looped", true )
            }
        }
        f.callPlugins( "onCreateLoop" )
    };
    f.fixLoop = function() {
        var i;
        if ( f.activeIndex < f.loopedSlides ) {
            i = f.slides.length - f.loopedSlides * 3 + f.activeIndex;
            f.swipeTo( i, 0, false )
        } else {
            if ( (a.slidesPerView === "auto" && f.activeIndex >= f.loopedSlides * 2) || (f.activeIndex > f.slides.length - a.slidesPerView * 2) ) {
                i = -f.slides.length + f.activeIndex + f.loopedSlides;
                f.swipeTo( i, 0, false )
            }
        }
    };
    f.loadSlides = function() {
        var ag = "";
        f.activeLoaderIndex = 0;
        var p = a.loader.slides;
        var ah = a.loader.loadAllSlides ? p.length : a.slidesPerView * (1 + a.loader.surroundGroups);
        for ( var j = 0; j < ah; j++ ) {
            if ( a.loader.slidesHTMLType === "outer" ) {
                ag += p[j]
            } else {
                ag += "<" + a.slideElement + ' class="' + a.slideClass + '" data-swiperindex="' + j + '">' + p[j] + "</" + a.slideElement + ">"
            }
        }
        f.wrapper.innerHTML = ag;
        f.calcSlides( true );
        if ( !a.loader.loadAllSlides ) {
            f.wrapperTransitionEnd( f.reloadSlides, true )
        }
    };
    f.reloadSlides = function() {
        var j = a.loader.slides;
        var am = parseInt( f.activeSlide().data( "swiperindex" ), 10 );
        if ( am < 0 || am > j.length - 1 ) {
            return
        }
        f.activeLoaderIndex = am;
        var p = Math.max( 0, am - a.slidesPerView * a.loader.surroundGroups );
        var ak = Math.min( am + a.slidesPerView * (1 + a.loader.surroundGroups) - 1, j.length - 1 );
        if ( am > 0 ) {
            var ag = -J * (am - p);
            f.setWrapperTranslate( ag );
            f.setWrapperTransition( 0 )
        }
        var aj;
        if ( a.loader.logic === "reload" ) {
            f.wrapper.innerHTML = "";
            var an = "";
            for ( aj = p; aj <= ak; aj++ ) {
                an += a.loader.slidesHTMLType === "outer" ? j[aj] : "<" + a.slideElement + ' class="' + a.slideClass + '" data-swiperindex="' + aj + '">' + j[aj] + "</" + a.slideElement + ">"
            }
            f.wrapper.innerHTML = an
        } else {
            var ah = 1000;
            var ai = 0;
            for ( aj = 0; aj < f.slides.length; aj++ ) {
                var al = f.slides[aj].data( "swiperindex" );
                if ( al < p || al > ak ) {
                    f.wrapper.removeChild( f.slides[aj] )
                } else {
                    ah = Math.min( al, ah );
                    ai = Math.max( al, ai )
                }
            }
            for ( aj = p; aj <= ak; aj++ ) {
                var ao;
                if ( aj < ah ) {
                    ao = document.createElement( a.slideElement );
                    ao.className = a.slideClass;
                    ao.setAttribute( "data-swiperindex", aj );
                    ao.innerHTML = j[aj];
                    f.wrapper.insertBefore( ao, f.wrapper.firstChild )
                }
                if ( aj > ai ) {
                    ao = document.createElement( a.slideElement );
                    ao.className = a.slideClass;
                    ao.setAttribute( "data-swiperindex", aj );
                    ao.innerHTML = j[aj];
                    f.wrapper.appendChild( ao )
                }
            }
        }
        f.reInit( true )
    };
    function Q() {
        f.calcSlides();
        if ( a.loader.slides.length > 0 && f.slides.length === 0 ) {
            f.loadSlides()
        }
        if ( a.loop ) {
            f.createLoop()
        }
        f.init();
        m();
        if ( a.pagination ) {
            f.createPagination( true )
        }
        if ( a.loop || a.initialSlide > 0 ) {
            f.swipeTo( a.initialSlide, 0, false )
        } else {
            f.updateActiveSlide( 0 )
        }
        if ( a.autoplay ) {
            f.startAutoplay()
        }
        f.centerIndex = f.activeIndex;
        if ( a.onSwiperCreated ) {
            f.fireCallback( a.onSwiperCreated, f )
        }
        f.callPlugins( "onSwiperCreated" )
    }

    Q()
};
Swiper.prototype = {
    plugins: {}, wrapperTransitionEnd: function( h, f ) {
        var b = this, e = b.wrapper, d = ["webkitTransitionEnd", "transitionend", "oTransitionEnd", "MSTransitionEnd", "msTransitionEnd"], c;

        function g() {
            h( b );
            if ( b.params.queueEndCallbacks ) {
                b._queueEndCallbacks = false
            }
            if ( !f ) {
                for ( c = 0; c < d.length; c++ ) {
                    b.h.removeEventListener( e, d[c], g )
                }
            }
        }

        if ( h ) {
            for ( c = 0; c < d.length; c++ ) {
                b.h.addEventListener( e, d[c], g )
            }
        }
    }, getWrapperTranslate: function( e ) {
        var d = this.wrapper, a, c, f, b;
        if ( typeof e === "undefined" ) {
            e = this.params.mode === "horizontal" ? "x" : "y"
        }
        if ( this.support.transforms && this.params.useCSS3Transforms ) {
            f = window.getComputedStyle( d, null );
            if ( window.WebKitCSSMatrix ) {
                b = new WebKitCSSMatrix( f.webkitTransform === "none" ? "" : f.webkitTransform )
            } else {
                b = f.MozTransform || f.OTransform || f.MsTransform || f.msTransform || f.transform || f.getPropertyValue( "transform" ).replace(
                    "translate(", "matrix(1, 0, 0, 1,"
                );
                a = b.toString().split( "," )
            }
            if ( e === "x" ) {
                if ( window.WebKitCSSMatrix ) {
                    c = b.m41
                } else {
                    if ( a.length === 16 ) {
                        c = parseFloat( a[12] )
                    } else {
                        c = parseFloat( a[4] )
                    }
                }
            }
            if ( e === "y" ) {
                if ( window.WebKitCSSMatrix ) {
                    c = b.m42
                } else {
                    if ( a.length === 16 ) {
                        c = parseFloat( a[13] )
                    } else {
                        c = parseFloat( a[5] )
                    }
                }
            }
        } else {
            if ( e === "x" ) {
                c = parseFloat( d.style.left, 10 ) || 0
            }
            if ( e === "y" ) {
                c = parseFloat( d.style.top, 10 ) || 0
            }
        }
        return c || 0
    }, setWrapperTranslate: function( a, f, e ) {
        var d = this.wrapper.style, b = {x: 0, y: 0, z: 0}, c;
        if ( arguments.length === 3 ) {
            b.x = a;
            b.y = f;
            b.z = e
        } else {
            if ( typeof f === "undefined" ) {
                f = this.params.mode === "horizontal" ? "x" : "y"
            }
            b[f] = a
        }
        if ( this.support.transforms && this.params.useCSS3Transforms ) {
            c = this.support.transforms3d ? "translate3d(" + b.x + "px, " + b.y + "px, " + b.z + "px)" : "translate(" + b.x + "px, " + b.y + "px)";
            d.webkitTransform = d.MsTransform = d.msTransform = d.MozTransform = d.OTransform = d.transform = c
        } else {
            d.left = b.x + "px";
            d.top = b.y + "px"
        }
        this.callPlugins( "onSetWrapperTransform", b );
        if ( this.params.onSetWrapperTransform ) {
            this.fireCallback( this.params.onSetWrapperTransform, this, b )
        }
    }, setWrapperTransition: function( a ) {
        var b = this.wrapper.style;
        b.webkitTransitionDuration = b.MsTransitionDuration = b.msTransitionDuration = b.MozTransitionDuration = b.OTransitionDuration = b.transitionDuration = (a / 1000) + "s";
        this.callPlugins( "onSetWrapperTransition", {duration: a} );
        if ( this.params.onSetWrapperTransition ) {
            this.fireCallback( this.params.onSetWrapperTransition, this, a )
        }
    }, h: {
        getWidth: function( e, c, a ) {
            var d = window.getComputedStyle( e, null ).getPropertyValue( "width" );
            var b = parseFloat( d );
            if ( isNaN( b ) || d.indexOf( "%" ) > 0 ) {
                b = e.offsetWidth - parseFloat(
                    window.getComputedStyle(
                        e, null
                    ).getPropertyValue( "padding-left" )
                ) - parseFloat( window.getComputedStyle( e, null ).getPropertyValue( "padding-right" ) )
            }
            if ( c ) {
                b += parseFloat(
                    window.getComputedStyle(
                        e, null
                    ).getPropertyValue( "padding-left" )
                ) + parseFloat( window.getComputedStyle( e, null ).getPropertyValue( "padding-right" ) )
            }
            if ( a ) {
                return Math.round( b )
            } else {
                return b
            }
        }, getHeight: function( d, c, b ) {
            if ( c ) {
                return d.offsetHeight
            }
            var a = window.getComputedStyle( d, null ).getPropertyValue( "height" );
            var e = parseFloat( a );
            if ( isNaN( e ) || a.indexOf( "%" ) > 0 ) {
                e = d.offsetHeight - parseFloat(
                    window.getComputedStyle(
                        d, null
                    ).getPropertyValue( "padding-top" )
                ) - parseFloat( window.getComputedStyle( d, null ).getPropertyValue( "padding-bottom" ) )
            }
            if ( c ) {
                e += parseFloat(
                    window.getComputedStyle(
                        d, null
                    ).getPropertyValue( "padding-top" )
                ) + parseFloat( window.getComputedStyle( d, null ).getPropertyValue( "padding-bottom" ) )
            }
            if ( b ) {
                return Math.round( e )
            } else {
                return e
            }
        }, getOffset: function( b ) {
            var c = b.getBoundingClientRect();
            var a = document.body;
            var g = b.clientTop || a.clientTop || 0;
            var f = b.clientLeft || a.clientLeft || 0;
            var d = window.pageYOffset || b.scrollTop;
            var e = window.pageXOffset || b.scrollLeft;
            if ( document.documentElement && !window.pageYOffset ) {
                d = document.documentElement.scrollTop;
                e = document.documentElement.scrollLeft
            }
            return {top: c.top + d - g, left: c.left + e - f}
        }, windowWidth: function() {
            if ( window.innerWidth ) {
                return window.innerWidth
            } else {
                if ( document.documentElement && document.documentElement.clientWidth ) {
                    return document.documentElement.clientWidth
                }
            }
        }, windowHeight: function() {
            if ( window.innerHeight ) {
                return window.innerHeight
            } else {
                if ( document.documentElement && document.documentElement.clientHeight ) {
                    return document.documentElement.clientHeight
                }
            }
        }, windowScroll: function() {
            if ( typeof pageYOffset !== "undefined" ) {
                return {left: window.pageXOffset, top: window.pageYOffset}
            } else {
                if ( document.documentElement ) {
                    return {left: document.documentElement.scrollLeft, top: document.documentElement.scrollTop}
                }
            }
        }, addEventListener: function( b, c, d, a ) {
            if ( typeof a === "undefined" ) {
                a = false
            }
            if ( b.addEventListener ) {
                b.addEventListener( c, d, a )
            } else {
                if ( b.attachEvent ) {
                    b.attachEvent( "on" + c, d )
                }
            }
        }, removeEventListener: function( b, c, d, a ) {
            if ( typeof a === "undefined" ) {
                a = false
            }
            if ( b.removeEventListener ) {
                b.removeEventListener( c, d, a )
            } else {
                if ( b.detachEvent ) {
                    b.detachEvent( "on" + c, d )
                }
            }
        }
    }, setTransform: function( b, a ) {
        var c = b.style;
        c.webkitTransform = c.MsTransform = c.msTransform = c.MozTransform = c.OTransform = c.transform = a
    }, setTranslate: function( b, d ) {
        var c = b.style;
        var e = {x: d.x || 0, y: d.y || 0, z: d.z || 0};
        var a = this.support.transforms3d ? "translate3d(" + (e.x) + "px," + (e.y) + "px," + (e.z) + "px)" : "translate(" + (e.x) + "px," + (e.y) + "px)";
        c.webkitTransform = c.MsTransform = c.msTransform = c.MozTransform = c.OTransform = c.transform = a;
        if ( !this.support.transforms ) {
            c.left = e.x + "px";
            c.top = e.y + "px"
        }
    }, setTransition: function( a, b ) {
        var c = a.style;
        c.webkitTransitionDuration = c.MsTransitionDuration = c.msTransitionDuration = c.MozTransitionDuration = c.OTransitionDuration = c.transitionDuration = b + "ms"
    }, support: {
        touch: (window.Modernizr && Modernizr.touch === true) || (function() {
            return !!(("ontouchstart" in window) || window.DocumentTouch && document instanceof DocumentTouch)
        })(), transforms3d: (window.Modernizr && Modernizr.csstransforms3d === true) || (function() {
            var a = document.createElement( "div" ).style;
            return ("webkitPerspective" in a || "MozPerspective" in a || "OPerspective" in a || "MsPerspective" in a || "perspective" in a)
        })(), transforms: (window.Modernizr && Modernizr.csstransforms === true) || (function() {
            var a = document.createElement( "div" ).style;
            return ("transform" in a || "WebkitTransform" in a || "MozTransform" in a || "msTransform" in a || "MsTransform" in a || "OTransform" in a)
        })(), transitions: (window.Modernizr && Modernizr.csstransitions === true) || (function() {
            var a = document.createElement( "div" ).style;
            return ("transition" in a || "WebkitTransition" in a || "MozTransition" in a || "msTransition" in a || "MsTransition" in a || "OTransition" in a)
        })(), classList: (function() {
            var a = document.createElement( "div" ).style;
            return "classList" in a
        })()
    }, browser: {
        ie8: (function() {
            var c = -1;
            if ( navigator.appName === "Microsoft Internet Explorer" ) {
                var a = navigator.userAgent;
                var b = new RegExp( /MSIE ([0-9]{1,}[\.0-9]{0,})/ );
                if ( b.exec( a ) !== null ) {
                    c = parseFloat( RegExp.$1 )
                }
            }
            return c !== -1 && c < 9
        })(), ie10: window.navigator.msPointerEnabled, ie11: window.navigator.pointerEnabled
    }
};
if ( window.jQuery || window.Zepto ) {
    (function( a ) {
        a.fn.swiper = function( c ) {
            var b = new Swiper( a( this )[0], c );
            a( this ).data( "swiper", b );
            return b
        }
    })( window.jQuery || window.Zepto )
}
if ( typeof(module) !== "undefined" ) {
    module.exports = Swiper
}
if ( typeof define === "function" && define.amd ) {
    define(
        [], function() {
            return Swiper
        }
    )
}
;

/*
 * Swiper Smooth Progress 1.1.0
 * Smooth progress plugin for Swiper
 *
 * http://www.idangero.us/sliders/swiper/plugins/progress.php
 *
 * Copyright 2010-2014, Vladimir Kharlampidi
 * The iDangero.us
 * http://www.idangero.us/
 *
 * Licensed under GPL & MIT
 *
 * Released on: January 29, 2014
 */
Swiper.prototype.plugins.progress = function( a ) {
    function b() {
        for ( var b = 0; b < a.slides.length; b++ ) {
            var c = a.slides[b];
            c.progressSlideSize = e ? a.h.getWidth( c ) : a.h.getHeight( c ), c.progressSlideOffset = "offsetLeft"in c ? e ? c.offsetLeft : c.offsetTop : e ? c.getOffset().left - a.h.getOffset( a.container ).left : c.getOffset().top - a.h.getOffset( a.container ).top
        }
        d = e ? a.h.getWidth( a.wrapper ) + a.wrapperLeft + a.wrapperRight - a.width : a.h.getHeight( a.wrapper ) + a.wrapperTop + a.wrapperBottom - a.height
    }

    function c( b ) {
        var c, b = b || {x: 0, y: 0, z: 0};
        c = 1 == a.params.centeredSlides ? e ? -b.x + a.width / 2 : -b.y + a.height / 2 : e ? -b.x : -b.y;
        for ( var f = 0; f < a.slides.length; f++ ) {
            var g = a.slides[f], h = 1 == a.params.centeredSlides ? g.progressSlideSize / 2 : 0, i = (c - g.progressSlideOffset - h) / g.progressSlideSize;
            g.progress = i
        }
        a.progress = e ? -b.x / d : -b.y / d, a.params.onProgressChange && a.fireCallback(
            a.params.onProgressChange, a
        )
    }

    var d, e = "horizontal" == a.params.mode, f = {
        onFirstInit: function() {
            b(), c( {x: a.getWrapperTranslate( "x" ), y: a.getWrapperTranslate( "y" )} )
        }, onInit: function() {
            b()
        }, onSetWrapperTransform: function( a ) {
            c( a )
        }
    };
    return f
};