<?php
/**
 * The template for displaying the footer.
 *
 * @package Betheme
 * @author Muffin group
 * @link http://muffingroup.com
 */
?>

<!-- #Footer -->		
<footer id="Footer" class="clearfix">
	
	<?php if ( $footer_call_to_action = mfn_opts_get('footer-call-to-action') ): ?>
	<div class="footer_action">
		<div class="container">
			<div class="column one column_column">
				<?php echo $footer_call_to_action; ?>
			</div>
		</div>
	</div>
	<?php endif; ?>

	<?php 
		$sidebars_count = 0;
		for( $i = 1; $i <= 4; $i++ ){
			if ( is_active_sidebar( 'footer-area-'. $i ) ) $sidebars_count++;
		}
		
		if( $sidebars_count > 0 ){
			echo '<div class="widgets_wrapper">';
				echo '<div class="container">';
				
					$sidebar_class = '';
					switch( $sidebars_count ){
						case 2: $sidebar_class = 'one-second'; break;
						case 3: $sidebar_class = 'one-third'; break;
						case 4: $sidebar_class = 'one-fourth'; break;
						default: $sidebar_class = 'one';
					}
					
					for( $i = 1; $i <= 4; $i++ ){
						if ( is_active_sidebar( 'footer-area-'. $i ) ){
							echo '<div class="'. $sidebar_class .' column">';
							dynamic_sidebar( 'footer-area-'. $i );
							echo '</div>';
						}
					}
				
				echo '</div>';
			echo '</div>';
		}
	?>
	
	<?php if( ! mfn_opts_get('footer-hide') ): ?>
	<div class="footer_copy">
		<div class="container">
			<div class="column one">
				<a id="back_to_top" href="" class="button button_left button_js"><span class="button_icon"><i class="icon-up-open-big"></i></span></a>
				
				<!-- Copyrights -->
				<div class="copyright">
					<?php 
						if( mfn_opts_get('footer-copy') ){
							mfn_opts_show('footer-copy');
						} else {
							echo '&copy; '. date( 'Y' ) .' '. get_bloginfo( 'name' ) .'. All Rights Reserved. <a target="_blank" rel="nofollow" href="http://muffingroup.com">Muffin group</a>';
						}
					?>
				</div>
				
				<?php 
					if( has_nav_menu( 'social-menu-bottom' ) ){

						// #social-menu
						mfn_wp_social_menu_bottom();

					} else {

						$target = mfn_opts_get('social-target') ? 'target="_blank"' : false;

						echo '<ul class="social">';
							if( mfn_opts_get('social-skype') ) echo '<li class="skype"><a '.$target.' href="'. mfn_opts_get('social-skype') .'" title="Skype"><i class="icon-skype"></i></a></li>';
							if( mfn_opts_get('social-facebook') ) echo '<li class="facebook"><a '.$target.' href="'. mfn_opts_get('social-facebook') .'" title="Facebook"><i class="icon-facebook"></i></a></li>';
							if( mfn_opts_get('social-googleplus') ) echo '<li class="googleplus"><a '.$target.' href="'. mfn_opts_get('social-googleplus') .'" title="Google+"><i class="icon-gplus"></i></a></li>';
							if( mfn_opts_get('social-twitter') ) echo '<li class="twitter"><a '.$target.' href="'. mfn_opts_get('social-twitter') .'" title="Twitter"><i class="icon-twitter"></i></a></li>';
							if( mfn_opts_get('social-vimeo') ) echo '<li class="vimeo"><a '.$target.' href="'. mfn_opts_get('social-vimeo') .'" title="Vimeo"><i class="icon-vimeo"></i></a></li>';
							if( mfn_opts_get('social-youtube') ) echo '<li class="youtube"><a '.$target.' href="'. mfn_opts_get('social-youtube') .'" title="YouTube"><i class="icon-play"></i></a></li>';						
							if( mfn_opts_get('social-flickr') ) echo '<li class="flickr"><a '.$target.' href="'. mfn_opts_get('social-flickr') .'" title="Flickr"><i class="icon-flickr"></i></a></li>';
							if( mfn_opts_get('social-linkedin') ) echo '<li class="linkedin"><a '.$target.' href="'. mfn_opts_get('social-linkedin') .'" title="LinkedIn"><i class="icon-linkedin"></i></a></li>';
							if( mfn_opts_get('social-pinterest') ) echo '<li class="pinterest"><a '.$target.' href="'. mfn_opts_get('social-pinterest') .'" title="Pinterest"><i class="icon-pinterest"></i></a></li>';
							if( mfn_opts_get('social-dribbble') ) echo '<li class="dribbble"><a '.$target.' href="'. mfn_opts_get('social-dribbble') .'" title="Dribbble"><i class="icon-dribbble"></i></a></li>';
							if( mfn_opts_get('social-instagram') ) echo '<li class="instagram"><a '.$target.' href="'. mfn_opts_get('social-instagram') .'" title="Instagram"><i class="icon-instagram"></i></a></li>';
							if( mfn_opts_get('social-behance') ) echo '<li class="behance"><a '.$target.' href="'. mfn_opts_get('social-behance') .'" title="Behance"><i class="icon-behance"></i></a></li>';
							if( mfn_opts_get('social-vkontakte') ) echo '<li class="vkontakte"><a '.$target.' href="'. mfn_opts_get('social-vkontakte') .'" title="VKontakte"><i class="icon-vkontakte"></i></a></li>';
							if( mfn_opts_get('social-rss') ) echo '<li class="rss"><a '.$target.' href="'. get_bloginfo('rss2_url') .'" title="RSS"><i class="icon-rss"></i></a></li>';
						echo '</ul>';
				
					}
				?>
						
			</div>
		</div>
	</div>
	<?php endif; ?>
	
</footer>

</div><!-- #Wrapper -->

	
<!-- wp_footer() -->
<?php wp_footer(); ?>

</body>
</html>