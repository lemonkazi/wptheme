<?php
$grid_link = $grid_layout_mode = $title = $filter= '';
$posts = array();
extract(shortcode_atts(array(
    'title' => '',
    'grid_columns_count' => 4,
    'grid_teasers_count' => 8,
    'grid_layout' => 'title,thumbnail,text', // title_thumbnail_text, thumbnail_title_text, thumbnail_text, thumbnail_title, thumbnail, title_text
    'grid_link_target' => '_self',
    'filter' => '', //grid,
    'grid_thumb_size' => '300x400',
    'grid_layout_mode' => 'fitRows',
    'el_class' => '',
    'teaser_width' => '12',
    'orderby' => NULL,
    'order' => 'DESC',
    'loop' => '',
	'grid_style' =>'',
	'as_height' => '700px',
	'as_style' => 'classictilt',
	'as_color' => '',
	'as_thumbsize' => '',
	'as_slideshow' => '',
	'as_slideshow_speed' => '',
	'as_first_slide' => '',
	'as_hoverfx' => '',
	'as_woo_style' => '',
	'as_mouse_parallax' => '',
	'as_mouse_parallax_depth' => '',
), $atts));

global $isrun, $ab_amy_settings;


$isrun = $isrun+1;
if(empty($loop)) return;
$this->getLoop($loop);
$my_query = $this->query;
$args = $this->loop_args;
$teaser_blocks = vc_sorted_list_parse_value($grid_layout);


if($as_hoverfx == 'squares'){
	$as_hfx  = "M180,0v117.9V147v29.1h-60V157H60v-19.5H0V0H180z";
	$as_hfxh  = "M180,0v0.0v0.0v0.9h-60l0,0H60l0,0H0V0H180z";
}else if($as_hoverfx == 'wave'){
	$as_hfx  = "M0-2h180v186.8c0,0-44,21-90-12.1c-48.8-35.1-90,12.1-90,12.1V-2z";
	$as_hfxh  = "M0-2h180v0.4c0,0-33.3,0-90,0c-55.1,0-90,0-90,0V-2z";
}else{
	$as_hfx  = "M 0 0 L 0 182 L 90 156.5 L 180 182 L 180 0 L 0 0 z ";
	$as_hfxh  = "M 0,0 0,0 90,0 180.5,0 180,0 z";
}
if($as_thumbsize == 'yes'){
	$as_thumbsize = 'bigthumpsize';
}
?><?php
				
while ( $my_query->have_posts() ) {
	
    $my_query->the_post(); // Get post from query
    $post = new stdClass(); // Creating post object.
    $post->id = get_the_ID();
	$post->color = get_post_meta( get_the_ID(), 'custom_select_color_style', true );
	$post->custom_url =  get_post_meta( get_the_ID(), 'custom_post_custom_url', true );
    $post->link = get_permalink($post->id);
	
	//$post->get_price = $product->get_price_html(); 
    if($this->getTeaserData('enable', $post->id) === '1') {
        $post->custom_user_teaser = true;
        $data = $this->getTeaserData('data', $post->id);
        if(!empty($data)) $data = json_decode($data);
        $post->bgcolor = $this->getTeaserData('bgcolor');
        $post->custom_teaser_blocks = array();
        $post->title_attribute = the_title_attribute('echo=0');
        if(!empty($data))
            foreach($data as $block) {
                $settings = array();
                if($block->name === 'title') {
                    $post->title = the_title("", "", false);
                } elseif($block->name === 'image') {
                    if($block->image === 'featured') {
                        $post->thumbnail_data = $this->getPostThumbnail($post->id, $grid_thumb_size);
                    } elseif(!empty($block->image)) {
                        $post->thumbnail_data = wpb_getImageBySize(array('attach_id' => (int)$block->image, 'thumb_size' => $grid_thumb_size));
                    } else {
                        $post->thumbnail_data = false;
                    }
                    $post->thumbnail = $post->thumbnail_data && isset($post->thumbnail_data['thumbnail']) ? $post->thumbnail_data['thumbnail'] : '';
                    $post->image_link =  empty($video) && $post->thumbnail && isset($post->thumbnail_data['p_img_large'][0]) ? $post->thumbnail_data['p_img_large'][0] : $video;
                } elseif($block->name === 'text') {
                    if($block->mode === 'custom') {
                        $settings[] = 'text';
                        $post->content = $block->text;
                    } elseif($block->mode === 'excerpt') {
                        $settings[] = $block->mode;
                        $post->excerpt = $this->getPostExcerpt();
                    } else {
                        $settings[] = $block->mode;
                        $post->content = $this->getPostContent();
                    }
                }
                if(isset($block->link)) {
                    if($block->link === 'post') {
                        $settings[] = 'link_post';
                    } elseif($block->link === 'big_image') {
                        $settings[] = 'link_image';
                    } else {
                        $settings[] = 'no_link';
                    }
                    $settings[] = '';
                }
                $post->custom_teaser_blocks[] = array($block->name, $settings);
            }
    } else {
		$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
        $post->custom_user_teaser = false;
        $post->title = the_title("", "", false);
        $post->title_attribute = the_title_attribute('echo=0');
        $post->post_type = get_post_type();
        $post->content = $this->getPostContent();
        $post->excerpt = $this->getPostExcerpt();
        $post->thumbnail_data = $this->getPostThumbnail($post->id, $grid_thumb_size);
        $post->thumbnail = $post->thumbnail_data && isset($post->thumbnail_data['thumbnail']) ? $post->thumbnail_data['thumbnail'] : '';
        $video = get_post_meta($post->id, "_p_video", true);
        $post->image_link =  empty($video) && $post->thumbnail && isset($post->thumbnail_data['p_img_large'][0]) ? $post->thumbnail_data['p_img_large'][0] : $video;
		$post->as_woo_style = "style1";
		$post->the_permalink = get_permalink($post->id);
		global $product;
		if(isset( $product)){
		$post->get_price_html = $product->get_price_html(); 
		$post->get_rating_html = $product->get_rating_html(); 
		$post->the_title = get_the_title($post->id);
		$post->the_permalink = get_permalink($post->id);
		$post->add_to_cart_url = $product->add_to_cart_url(); 
		$post->wooid = $product->get_rating_html(); 
		$post->get_rating = $product->get_rating_html(); 
		$post->get_sku = $product->get_sku(); 
		$post->is_purchasable = $product->is_purchasable() ? 'add_to_cart_button' : ''; 
		$post->product_type = $product->product_type; 
		$post->add_to_cart_text = $product->add_to_cart_text(); 
		$post->get_categories = $product->get_categories( ', ', '<span class="posted_in">' . _n( 'Category:', 'Categories:', sizeof( get_the_terms( $post->id, 'product_cat' ) ), 'woocommerce' ) . ' ', '.</span>' );
		$post->as_woo_style = $as_woo_style;							
											
		}
    }

    $post->categories_css = $this->getCategoriesCss($post->id);

    $posts[] = $post;
}
wp_reset_query();

/**
 * Css classes for grid and teasers.
 * {{
 */
$post_types_teasers = '';
if ( !empty($args['post_type']) && is_array($args['post_type']) ) {
    foreach ( $args['post_type'] as $post_type ) {
        $post_types_teasers .= 'wpb_teaser_grid_'.$post_type . ' ';
    }
}
$el_class = $this->getExtraClass( $el_class );
$li_span_class = $this->spanClass( $grid_columns_count );

$css_class = 'wpb_row wpb_teaser_grid wpb_content_element '.
             $this->getMainCssClass($filter) . // Css class as selector for isotope plugin
             ' columns_count_'.$grid_columns_count . // Custom margin/padding for different count of columns in grid
             ' columns_count_'.$grid_columns_count . // Combination of layout and column count
             // ' post_grid_'.$li_span_class .
             ' ' . $post_types_teasers . // Css classes by selected post types
             $el_class; // Custom css class from shortcode attributes
// }}

$this->setLinktarget($grid_link_target);
if($grid_style == 'gridstyle'){
?>
<div class="<?php echo apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $css_class, $this->settings['base']) ?> woocommerce">
    <div class="wpb_wrapper" id="articlehold<?php echo $isrun; ?>">
        <?php echo wpb_widget_title(array('title' => $title, 'extraclass' => 'wpb_teaser_grid_heading')) ?>
        <div class="teaser_grid_container <?php echo $as_color ?>">
            <?php if( $filter === 'yes' && !empty($this->filter_categories)):
            $categories_array = $this->getFilterCategories();
            ?>
            <ul class="categories_filter vc_clearfix">
                <li class="active"><a href="#" data-filter="*"><?php _e('All', 'js_composer') ?></a></li>
                <?php foreach($this->getFilterCategories() as $cat): ?>
                <?php if( esc_attr($cat->name) != 'variable' && esc_attr($cat->name) != 'simple'){?>
                <li><a href="#" data-filter=".grid-cat-<?php echo $cat->term_id ?>"><?php echo esc_attr($cat->name) ?></a></li>
                <?php }?>
                <?php endforeach; ?>
            </ul><div class="vc_clearfix"></div>
            <?php endif; ?>
            <ul class="wpb_thumbnails wpb_thumbnails-fluid vc_clearfix" data-layout-mode="<?php echo $grid_layout_mode ?>">
                <?php if(count($posts) > 0): ?>
                <?php
                /**
                 * Enqueue js/css
                 * {{
                 */
                wp_enqueue_style('isotope-css');
                wp_enqueue_script( 'isotope' );
                ?>
                <?php foreach($posts as $post): ?>
                <?php
                    $blocks_to_build = $post->custom_user_teaser === true ? $post->custom_teaser_blocks :  $teaser_blocks;
                    $block_style = isset($post->bgcolor) ? ' style="background-color: '.$post->bgcolor.'"' : '';
                    ?>
                <li class="isotope-item <?php echo apply_filters(VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, $li_span_class, 'vc_teaser_grid_li').$post->categories_css ?>"<?php echo $block_style ?>>
                 
                    <div class="grid <?php echo $post->color;?>">
                        <?php include $this->getBlockTemplate() ?>
                       </div>
                </li> <?php echo $this->endBlockComment('single teaser'); ?>
                <?php endforeach; ?>
                <?php else: ?>
                <li class="<?php echo $this->spanClass(1); ?>"><?php _e("Nothing found." , "js_composer") ?></li>
                <?php endif; ?>
            </ul>
        </div>
    </div> <?php echo $this->endBlockComment('.wpb_wrapper') ?>
    <div class="clear"></div>
</div> <?php echo $this->endBlockComment('.wpb_teaser_grid') ?>

<?php }else{?>


<script>
jQuery(document).ready(function($){
	<?php if($as_mouse_parallax == 'yes'){ ?> 
	//var scene = document.getElementById('articlehold<?php echo $isrun; ?>');
	//var parallax = new Parallax(scene);
	$('#articlehold<?php echo $isrun; ?>').parallax();
	<?php } ?>
	'use strict';
	var themes,
		selectedThemeIndex,
		instructionsTimeout,
		deck;
	window.scrollinit = function(){
		deck = bespoke.from('#articlehold<?php echo $isrun; ?>');
		initThemeSwitching();
	};
	setTimeout(scrollinit,1000);
	function initThemeSwitching() {
		themes = [
			'classic',
			'cube',
			'carousel',
			'concave',
			'coverflow',
			'spiraltop',
			'spiralbottom',
			'classictilt'
		];
		selectedThemeIndex = 0;
		if(window.lastslide !==''){
			deck.slide(window.lastslide-1);
		}else{
			deck.slide(0);
		}
		deck.slide(<?php echo $as_first_slide;?>);
		initKeys();
		initButtons();
		initSlideGestures();
		initClickInactive();
		
	}
	//Navigation
	//==================================================
	function initButtons() {
		$('#arrownav<?php echo $isrun;?> .next-arrow').click(function(){ gonext();});
		$('#arrownav<?php echo $isrun;?> .prev-arrow').click(function(){ deck.prev();});
	}
	var stopnextslide = 0;
	function gonext() {
		var stopnextslide = 0;
		var n = $('#articlehold<?php echo $isrun; ?> section').length;
		
		$('#articlehold<?php echo $isrun; ?> section').each(function () {
			if ($(this).hasClass('bespoke-active') && Number($(this).attr('rel'))+1 == n) {
				deck.slide(0);
				stopnextslide = 1;
			}
		});
		if(stopnextslide != 1){
			deck.next();
		}
	};
	//Keyboard navigation
	//==================================================
	function initKeys(e) {
		if (/Firefox/.test(navigator.userAgent)) {
			document.addEventListener('keydown', function(e) {
				if (e.which >= 37 && e.which <= 40) {
					e.preventDefault();
				}
			});
		}
		window.gokb = function(e) {
			var key = e.which;
		
			if(key === 37){
				if ($('#articlehold<?php echo $isrun; ?>').is(':hover')) {
					deck.prev();
				}
			}
			if(key === 39 ){
				if ($('#articlehold<?php echo $isrun; ?>').is(':hover')) {
					deck.next();
				}
			}
			//theme swiching
			if(key === 38){
				if ($('#articlehold<?php echo $isrun; ?>').is(':hover')) {
				if(Modernizr.csstransforms3d !== false){
				prevTheme();
				}
				}
			}
			if(key === 40){
				if ($('#articlehold<?php echo $isrun; ?>').is(':hover')) {
				if(Modernizr.csstransforms3d !== false){
				nextTheme();
				}
				}
			}
			
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
	
	//Navigation for touch devices
	//==================================================
	function initSlideGestures() {
		var start = 0;
		var main = document.getElementById('main<?php echo $isrun; ?>'),
			startPosition,
			delta,
			
			singleTouch = function(fn, preventDefault) {
				return function(e) {
					if(e.touches.length === 1){
						fn(e.touches[0].pageY);
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
				if (Math.abs(delta) < 50) {
					return;
				}
				if(delta > 0){
					//window.clearInterval(autorotateposts);
					deck.prev();
				}else{
					//window.clearInterval(autorotateposts);
					deck.next();
				}
				
				
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
	
	function isTouch() {
		return !!('ontouchstart' in window) || navigator.msMaxTouchPoints;
	}

	function modulo(num, n) {
		return ((num % n) + n) % n;
	}
	//Mouse click navigation
	//==================================================
	function initClickInactive(){
		var n = $("#articlehold<?php echo $isrun; ?> section").length;
		$('#articlehold<?php echo $isrun;?> .tt-cn-style').click(function() {
			//window.clearInterval(autorotateposts);
			var page = $(this).parent().attr('rel');
			if( $(this).parent().hasClass('bespoke-inactive') ){
			deck.slide(page);
			}
		});
	}
	function selectTheme(index) {
		var theme = themes[index];
		var thestyle = theme + '  <?php echo $as_color ?> woocommerce amysliderheight <?php echo $as_thumbsize ?>';
	//	$('#main<?php echo $isrun;?>').className = theme;
		$('#main<?php echo $isrun;?>').removeClass();
		
		$('#main<?php echo $isrun;?>').addClass(thestyle);
	//	alert(theme)
		selectedThemeIndex = index;
	}
	function nextTheme() {
		offsetSelectedTheme(1);
	
	}
	function prevTheme() {
		offsetSelectedTheme(-1);
		
	}
	function offsetSelectedTheme(n) {
		selectTheme(modulo(selectedThemeIndex + n, themes.length));
	}
	<?php if($as_slideshow =='yes'){ ?> 
			function autoslide(){
				stopnextslide = 0;
				var n = $('#articlehold<?php echo $isrun;?> section').length;
				
				$('#articlehold<?php echo $isrun;?> section').each(function () {
					if ($(this).hasClass('bespoke-active') && Number($(this).attr('rel'))+1 == n) {
						deck.slide(0);
					//	nextTheme();
						stopnextslide = 1;
					}
				});
				if(stopnextslide != 1){
					deck.next();
				//	nextTheme();
				}
			}
			$('#articlehold<?php echo $isrun;?>').hover(function() {
				window.clearInterval(autorotateposts);
			}, function(){
					window.clearInterval(autorotateposts);
					 autorotateposts = setInterval(autoslide, <?php echo $as_slideshow_speed;?>);
			})
			var autorotateposts = setInterval(autoslide , <?php echo $as_slideshow_speed;?>);	
		<?php }; ?>
	//	var changetheme = setInterval(nextTheme , 3000);
		
		
});
</script>

<script>

(function(moduleName, window, document) {
	if(window.isrunedonce !=1){
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
					
					// window.location.hash = '!slide-'+jQuery( "section.bespoke-active" ).attr("rel");
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
					
					// window.location.hash = '!slide-'+jQuery( "section.bespoke-active" ).attr("rel");

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
	window.isrunedonce =1
	}
}('bespoke', this, document));
</script>

<?php 

      $style = '';
        if( $as_height != '' ) {
            $style .= 'height: '.(preg_match('/(px|em|\%|pt|cm)$/', $as_height) ? $as_height : $as_height.'px').';';
			
        }
	
  
       ?>
<div  id="main<?php echo $isrun; ?>" <?php echo ' style="'.$style.' "'; ?> class="<?php echo $as_style ?> <?php echo $as_color ?> woocommerce amysliderheight <?php echo $as_thumbsize ?>">
	<article id="articlehold<?php echo $isrun; ?>" class="scene" >
	 
                  
                   <?php foreach($posts as $post):
				   $blocks_to_build = $post->custom_user_teaser === true ? $post->custom_teaser_blocks :  $teaser_blocks;
                   // $block_style = isset($post->bgcolor) ? ' style="background-color: '.$post->bgcolor.'"' : '';
					 ?>
      

			<section class=" grid clearfix  <?php echo $post->color;?>" >
				<div class="layer tt-cn-style  center-content " data-depth="<?php echo $as_mouse_parallax_depth;?>">
                    <?php //foreach($blocks_to_build as $block_data): ?>
                    <?php  include $this->getBlockTemplate();?>
                </div>
			</section>
             <?php endforeach; ?>
           
    </article>
    
       <!-- START NAVIGATION ARROWS -->
       <div id="arrownav<?php echo $isrun;?>">
			<div class="ss-nav-arrows-next">
				<i class="icon-angle-right next-arrow"></i>
			</div>
			<div class="ss-nav-arrows-prev">
				<i class="icon-angle-left prev-arrow"></i>
			</div>
         </div>
			<!-- END NAVIGATION ARROWS -->
</div> <?php echo $this->endBlockComment('.wpb_wrapper') ?>
<?php echo $this->endBlockComment('.wpb_teaser_grid') ?>
<?php }?>