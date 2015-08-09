<?php if( mfn_opts_get('sliding-top')): ?>

	<div id="Sliding-top">
		
		<div class="widgets_wrapper">
			<div class="container">
				<?php
					$sidebars_count = 0;	
					for( $i = 1; $i <= 4; $i++ ){
						if ( is_active_sidebar( 'top-area-'. $i ) ) $sidebars_count++;
					}
				
					$sidebar_class = '';
					if( $sidebars_count > 0 ){
						switch( $sidebars_count ){
							case 2: $sidebar_class = 'one-second'; break; 
							case 3: $sidebar_class = 'one-third'; break; 
							case 4: $sidebar_class = 'one-fourth'; break;
							default: $sidebar_class = 'one';
						}
					}
				?>
				
				<?php 
					for( $i = 1; $i <= 4; $i++ ){
						if ( is_active_sidebar( 'top-area-'. $i ) ){
							echo '<div class="'. $sidebar_class .' column">';
								dynamic_sidebar( 'top-area-'. $i );
							echo '</div>';
						}
					}
				?>
		
			</div>
		</div>
		
		<a href="#" class="sliding-top-control"><span><i class="plus icon-down-open-mini"></i><i class="minus icon-up-open-mini"></i></span></a>
	</div>

<?php endif; ?>