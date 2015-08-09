<?php $translate['wpml-no'] = mfn_opts_get('translate') ? mfn_opts_get('translate-wpml-no','No translations available for this page') : __('No translations available for this page','betheme'); ?>

<div id="Header_creative">	
	<a href="#" class="creative-menu-toggle"><i class="icon-menu"></i></a>
	
	<ul class="social creative-social">
		<?php if( mfn_opts_get('social-skype') ): ?><li class="skype"><a href="<?php mfn_opts_show('social-skype'); ?>" title="Skype"><i class="icon-skype"></i></a></li><?php endif; ?>
		<?php if( mfn_opts_get('social-facebook') ): ?><li class="facebook"><a target="_blank" href="<?php mfn_opts_show('social-facebook'); ?>" title="Facebook"><i class="icon-facebook"></i></a></li><?php endif; ?>
		<?php if( mfn_opts_get('social-googleplus') ): ?><li class="googleplus"><a target="_blank" href="<?php mfn_opts_show('social-googleplus'); ?>" title="Google+"><i class="icon-gplus"></i></a></li><?php endif; ?>
		<?php if( mfn_opts_get('social-twitter') ): ?><li class="twitter"><a target="_blank" href="<?php mfn_opts_show('social-twitter'); ?>" title="Twitter"><i class="icon-twitter"></i></a></li><?php endif; ?>
		<?php if( mfn_opts_get('social-vimeo') ): ?><li class="vimeo"><a target="_blank" href="<?php mfn_opts_show('social-vimeo'); ?>" title="Vimeo"><i class="icon-vimeo"></i></a></li><?php endif; ?>
		<?php if( mfn_opts_get('social-youtube') ): ?><li class="youtube"><a target="_blank" href="<?php mfn_opts_show('social-youtube'); ?>" title="YouTube"><i class="icon-play"></i></a></li><?php endif; ?>
		<?php if( mfn_opts_get('social-flickr') ): ?><li class="flickr"><a target="_blank" href="<?php mfn_opts_show('social-flickr'); ?>" title="Flickr"><i class="icon-flickr"></i></a></li><?php endif; ?>
		<?php if( mfn_opts_get('social-linkedin') ): ?><li class="linked_in"><a target="_blank" href="<?php mfn_opts_show('social-linkedin'); ?>" title="LinkedIn"><i class="icon-linkedin"></i></a></li><?php endif; ?>
		<?php if( mfn_opts_get('social-pinterest') ): ?><li class="pinterest"><a target="_blank" href="<?php mfn_opts_show('social-pinterest'); ?>" title="Pinterest"><i class="icon-pinterest"></i></a></li><?php endif; ?>
		<?php if( mfn_opts_get('social-dribbble') ): ?><li class="dribbble"><a target="_blank" href="<?php mfn_opts_show('social-dribbble'); ?>" title="Dribbble"><i class="icon-dribbble"></i></a></li><?php endif; ?>
		<?php if( mfn_opts_get('social-instagram') ): ?><li class="instagram"><a target="_blank" href="<?php mfn_opts_show('social-instagram'); ?>" title="Instagram"><i class="icon-instagram"></i></a></li><?php endif; ?>
		<?php if( mfn_opts_get('social-behance') ): ?><li class="behance"><a target="_blank" href="<?php mfn_opts_show('social-behance'); ?>" title="Behance"><i class="icon-behance"></i></a></li><?php endif; ?>
		<?php if( mfn_opts_get('social-vkontakte') ): ?><li class="vkontakte"><a target="_blank" href="<?php mfn_opts_show('social-vkontakte'); ?>" title="VKontakte"><i class="icon-vkontakte"></i></a></li><?php endif; ?>
	</ul>
	
	<div class="creative-wrapper">
	
		<div id="Top_bar">
			<div class="one">
		
				<div class="top_bar_left">
				
					<!-- .logo -->
					<div class="logo">
						<?php 
							if( is_front_page() ) echo '<h1>';
							
							// logo - source
							if( $_GET && key_exists('mfn-l', $_GET) ){
								$logo_src = THEME_URI .'/images/logo/'. $_GET['mfn-l'] .'.png'; // demo
							} elseif( $layoutID = get_post_meta( mfn_ID(), 'mfn-post-custom-layout', true ) ){
								$logo_src = get_post_meta( $layoutID, 'mfn-post-logo-img', true );
							} else {
								$logo_src = mfn_opts_get( 'logo-img', THEME_URI .'/images/logo/logo.png' );
							}
				
							// logo - print
							echo '<a id="logo" href="'. get_home_url() .'" title="'. get_bloginfo( 'name' ) .'">';
								echo '<img class="scale-with-grid" src="'. $logo_src .'" alt="'. get_bloginfo( 'name' ) .'" />';
							echo '</a>';
				
							if( is_front_page() ) echo '</h1>';
						?>
					</div>
			
					<div class="menu_wrapper">
						<!-- #menu -->
						<?php mfn_wp_nav_menu(); ?>
						<a class="responsive-menu-toggle" href="#"><i class='icon-menu'></i></a>					
					</div>			
				
					<div class="search_wrapper">
						<!-- #searchform -->
						<?php $translate['search-placeholder'] = mfn_opts_get('translate') ? mfn_opts_get('translate-search-placeholder','Enter your search') : __('Enter your search','falco'); ?>
						<form method="get" id="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
							<i class="icon_search icon-search"></i>
							<a href="#" class="icon_close"><i class="icon-cancel"></i></a>
							<input type="text" class="field" name="s" id="s" placeholder="<?php echo $translate['search-placeholder']; ?>" />			
							<input type="submit" class="submit" value="" style="display:none;" />
						</form>
					</div>
				
				</div>
			
				<?php
					global $woocommerce;
					$show_cart = mfn_opts_get( 'shop-cart' );
					if( $show_cart == 1 ) $show_cart = 'icon-basket'; // Be < 4.9 compatibility
					
					$has_cart = ( $woocommerce && $show_cart ) ? true : false;
					$header_search			= mfn_opts_get( 'header-search' );
					$header_action_link		= mfn_opts_get( 'header-action-link' );
				
					if( $has_cart || $header_search || function_exists( 'icl_get_languages' ) || $header_action_link ){
						echo '<div class="top_bar_right">';
							echo '<div class="top_bar_right_wrapper">';
							
								// WooCommerce Cart
								if( $has_cart ){
									echo '<a id="header_cart" href="'. $woocommerce->cart->get_cart_url() .'"><i class="'. $show_cart .'"></i><span>'. $woocommerce->cart->cart_contents_count .'</span></a>';
								}
								
								// Search Icon
								if( $header_search ){
									echo '<a id="search_button" href="#"><i class="icon-search"></i></a>';
								}
		
								// WPML - Custom Languages Menu
								if( function_exists( 'icl_get_languages' ) ){
									$languages = icl_get_languages('skip_missing=N&orderby=KEY&order=DIR&link_empty_to=str');
									if( is_array( $languages ) ){
										
										foreach( $languages as $lang_k=>$lang ){
											if( $lang['active'] ){
												$active_lang = $lang;
												unset( $languages[$lang_k] );
											}
										}
			
										// disabled
										if( count( $languages ) ){
											$lang_status = 'enabled';
										} else {
											$lang_status = 'disabled';
										}
										
										echo '<div class="wpml-languages '. $lang_status .'">';
										
											echo '<a class="active tooltip" href="'. $active_lang['url'] .'" ontouchstart="this.classList.toggle(\'hover\');" data-tooltip="'. $translate['wpml-no'] .'">';
												echo '<img src="'. $active_lang['country_flag_url'] .'" alt="'. $active_lang['translated_name'] .'"/>';
												if( count( $languages ) ) echo '<i class="icon-down-open-mini"></i>';
											echo '</a>';
											
											if( count( $languages ) ){
												echo '<ul class="wpml-lang-dropdown">';
													foreach( $languages as $lang ){
														echo '<li><a href="'. $lang['url'] .'"><img src="'. $lang['country_flag_url'] .'" alt="'. $lang['translated_name'] .'"/></a></li>';
													}
												echo '</ul>';
											}
											
										echo '</div>';
										
									}
								}
		
								// Action Button
								if( $header_action_link ){
									echo '<a href="'. $header_action_link .'" class="button button_theme button_js action_button"><span class="button_label">'. mfn_opts_get( 'header-action-title' ) .'</span></a>';
								}
											
							echo '</div>';
						echo '</div>';
					}
				?>
			</div>
		</div>

		<div id="Action_bar">
			<ul class="social">
				<?php if( mfn_opts_get('social-skype') ): ?><li class="skype"><a href="<?php mfn_opts_show('social-skype'); ?>" title="Skype"><i class="icon-skype"></i></a></li><?php endif; ?>
				<?php if( mfn_opts_get('social-facebook') ): ?><li class="facebook"><a target="_blank" href="<?php mfn_opts_show('social-facebook'); ?>" title="Facebook"><i class="icon-facebook"></i></a></li><?php endif; ?>
				<?php if( mfn_opts_get('social-googleplus') ): ?><li class="googleplus"><a target="_blank" href="<?php mfn_opts_show('social-googleplus'); ?>" title="Google+"><i class="icon-gplus"></i></a></li><?php endif; ?>
				<?php if( mfn_opts_get('social-twitter') ): ?><li class="twitter"><a target="_blank" href="<?php mfn_opts_show('social-twitter'); ?>" title="Twitter"><i class="icon-twitter"></i></a></li><?php endif; ?>
				<?php if( mfn_opts_get('social-vimeo') ): ?><li class="vimeo"><a target="_blank" href="<?php mfn_opts_show('social-vimeo'); ?>" title="Vimeo"><i class="icon-vimeo"></i></a></li><?php endif; ?>
				<?php if( mfn_opts_get('social-youtube') ): ?><li class="youtube"><a target="_blank" href="<?php mfn_opts_show('social-youtube'); ?>" title="YouTube"><i class="icon-play"></i></a></li><?php endif; ?>
				<?php if( mfn_opts_get('social-flickr') ): ?><li class="flickr"><a target="_blank" href="<?php mfn_opts_show('social-flickr'); ?>" title="Flickr"><i class="icon-flickr"></i></a></li><?php endif; ?>
				<?php if( mfn_opts_get('social-linkedin') ): ?><li class="linked_in"><a target="_blank" href="<?php mfn_opts_show('social-linkedin'); ?>" title="LinkedIn"><i class="icon-linkedin"></i></a></li><?php endif; ?>
				<?php if( mfn_opts_get('social-pinterest') ): ?><li class="pinterest"><a target="_blank" href="<?php mfn_opts_show('social-pinterest'); ?>" title="Pinterest"><i class="icon-pinterest"></i></a></li><?php endif; ?>
				<?php if( mfn_opts_get('social-dribbble') ): ?><li class="dribbble"><a target="_blank" href="<?php mfn_opts_show('social-dribbble'); ?>" title="Dribbble"><i class="icon-dribbble"></i></a></li><?php endif; ?>
				<?php if( mfn_opts_get('social-instagram') ): ?><li class="instagram"><a target="_blank" href="<?php mfn_opts_show('social-instagram'); ?>" title="Instagram"><i class="icon-instagram"></i></a></li><?php endif; ?>
				<?php if( mfn_opts_get('social-behance') ): ?><li class="behance"><a target="_blank" href="<?php mfn_opts_show('social-behance'); ?>" title="Behance"><i class="icon-behance"></i></a></li><?php endif; ?>
				<?php if( mfn_opts_get('social-vkontakte') ): ?><li class="vkontakte"><a target="_blank" href="<?php mfn_opts_show('social-vkontakte'); ?>" title="VKontakte"><i class="icon-vkontakte"></i></a></li><?php endif; ?>
			</ul>
		</div>
					
	</div>
	
</div>