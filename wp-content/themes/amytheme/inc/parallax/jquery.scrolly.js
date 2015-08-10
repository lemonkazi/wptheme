// For mobile: http://stackoverflow.com/questions/18753367/jquery-live-scroll-event-on-mobile-work-around

/*
 * Copyright (c) 2011 Brandon Jones
 *
 * This software is provided 'as-is', without any express or implied
 * warranty. In no event will the authors be held liable for any damages
 * arising from the use of this software.
 *
 * Permission is granted to anyone to use this software for any purpose,
 * including commercial applications, and to alter it and redistribute it
 * freely, subject to the following restrictions:
 *
 *    1. The origin of this software must not be misrepresented; you must not
 *    claim that you wrote the original software. If you use this software
 *    in a product, an acknowledgment in the product documentation would be
 *    appreciated but is not required.
 *
 *    2. Altered source versions must be plainly marked as such, and must not
 *    be misrepresented as being the original software.
 *
 *    3. This notice may not be removed or altered from any source
 *    distribution.
 */

(function( $ ){
    var reqAnimFrame = (function(){
      return  window.requestAnimationFrame       ||
              window.webkitRequestAnimationFrame ||
              window.mozRequestAnimationFrame    ||
              window.oRequestAnimationFrame      ||
              window.msRequestAnimationFrame     ||
              function(callback, element){
                window.setTimeout(callback, 1000 / 60);
              };
    })();

    $.fn.requestAnimation = function(callback) {
        var startTime;
        if(window.mozAnimationStartTime) {
            startTime = window.mozAnimationStartTime;
        } else if (window.webkitAnimationStartTime) {
            startTime = window.webkitAnimationStartTime;
        } else {
            startTime = new Date().getTime();
        }

        return this.each(function() {
            var element = this;
            var lastTimestamp = startTime;
            var lastFps = startTime;
            var framesPerSecond = 0;
            var frameCount = 0;

            function onFrame(timestamp){
                if(!timestamp) {
                    timestamp = new Date().getTime();
                }

                // Update FPS if a second or more has passed since last FPS update
                if(timestamp - lastFps >= 1000) {
                    framesPerSecond = frameCount;
                    frameCount = 0;
                    lastFps = timestamp;
                }

                if(callback({
                    timestamp: timestamp,
                    elapsed: timestamp - startTime,
                    frameTime: timestamp - lastTimestamp,
                    framesPerSecond: framesPerSecond,
                }) !== false) {
                    reqAnimFrame(onFrame, element);
                    ++frameCount;
                }
            };

            onFrame(startTime);
        });
    };
})( jQuery );


/*
 * Project: Scrolly2 - Background Image Parallax
 * Originally based on Scrolly by Victor C. / Octave & Octave web agency
 * Rewritten and heavily adjusted by Benjamin Intal / Gambit
 */
(function ( $, window, document, undefined ) {
    var pluginName = 'scrolly2',
        didScroll = false;

    function Plugin( element, options ) {
        this.$element = $(element);
        this.init();
    }

    Plugin.prototype.init = function () {
        var self = this;
        this.startPosition = 0;
        this.offsetTop = this.$element.offset().top;
        this.height = this.$element.outerHeight(true);
        this.velocity = this.$element.attr('data-velocity');
		this.direction = this.$element.attr('data-direction');
		this.isboxed = this.$element.attr('data-isboxed');
		this.layerslider = this.$element.attr('data-layerslider');
		this.layersliderprlx = this.$element.attr('data-layersliderprlx');
		
		//this.divholder = this.$elememt 

		$(this).requestAnimation(function(event) {
			self.scrolly2();
		});
    };

    Plugin.prototype.scrolly2 = function() {
		// Check if the element is inside our viewport, if it's not, don't do anything
		var viewTop = $(window).scrollTop() + 20; // with leeway
		var viewBottom = $(window).scrollTop() + $(window).height() +20; // with leeway
		var elemTop = this.$element.offset().top;
		var elemBottom = this.$element.offset().top + this.$element.height();

		if ( elemTop >= viewBottom || elemBottom <= viewTop ) {
			if(this.layerslider != ''){
				if(this.layersliderprlx != '' && elemBottom +1500 <= viewTop){
				}else{
					return
				}
			}else{
			return;
			}
		}

		// If the element is below the fold, then we need to
		// make sure that when we first see the element,
		// our background image should be in the starting position
		if ( this.$element.offset().top > $(window).height() ) {
			if ( this.direction != 'none' ) {
				this.startPosition = (this.$element.offset().top - $(window).height()) * Math.abs(this.velocity);
				
				//alert(this.startPosition2);
				//alert(this.$element.offset().top);
				//alert(this.velocity);
			}
		}
		this.startPosition2 = (this.$element.offset().top + $(window).height()) * Math.abs(-0.3);

		// Calculate position
        position = this.startPosition + $(window).scrollTop() * this.velocity;
		positiondiv = "-" + (this.startPosition2 + $(window).scrollTop() * -0.4)+ "px";
		//positiondiv = this.startPosition2 + $(window).scrollTop() * -0.4;

		// Adjust position
		if(this.isboxed == 'boxed'){
			var is_chrome = navigator.userAgent.toLowerCase().indexOf('chrome') > -1;
			if(is_chrome){
				var xPos = "0%";
			}else{
				var xPos = "50%";
			}
		}else{
			var xPos = "50%";
		}
		var yPos = "50%";
		//var davai = 0.2 + (this.$element.offset().top - $(window).scrollTop() + position );
		if ( this.direction == 'left' ) {
			xPos = position + 'px';
		} else if ( this.direction == 'right' ) {
			xPos = 'calc(100% + ' + -position + 'px)';
		} else if ( this.direction == 'down' ) {
			// yPos = 'calc(100% + ' + (-position) + 'px)';
			// Use this one for background-attachment: fixed
			var offset = - ( $(window).height()
				- this.$element.offset().top
				- this.$element.height()
				- parseInt( this.$element.css('paddingTop') )
				- parseInt( this.$element.css('paddingBottom') ) );
			var offset2 = - ( $(window).height()
				- this.$element.height());
			yPos = 'calc(100% + ' + ( offset - $(window).scrollTop() - position ) + 'px)';
			yPoss = + positiondiv;
			//alert(yPoss);
		//alert(yPoss);
		} else { // up
			// yPos = position + 'px';
			// Use this one for background-attachment: fixed
			yPos = ( this.$element.offset().top - $(window).scrollTop() + position ) + 'px';
		///	var davai = '1.0' + ( $(window).scrollTop()*0.1 );
		}
		//var davai = 0.2 + (this.$element.offset().top - $(window).scrollTop() + position );
	//	alert(davai)
        
		if(this.layerslider != ''){
			
			if(this.layersliderprlx != ''){
				this.$element.css( { marginTop:positiondiv } );
			}
		}else{
			this.$element.css( { backgroundPosition: xPos + ' ' + yPos } );
		}
		//this.$element.css( { scale:davai} );
		
		
		
    };

    $.fn[pluginName] = function ( options ) {
        return this.each(function () {
            if (!$.data(this, 'plugin_' + pluginName)) {
                $.data(this, 'plugin_' + pluginName, new Plugin( this, options ));
            }
        });
    };

})(jQuery, window, document);
