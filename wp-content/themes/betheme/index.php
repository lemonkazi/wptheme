<?php
/**
 * The main template file.
 *
 * @package Betheme
 * @author Muffin group
 * @link http://muffingroup.com
 */

get_header();
$blog_classes = array();

// layout
if( $_GET && key_exists('mfn-b', $_GET) ){
	$blog_layout = $_GET['mfn-b']; // demo
} else {
	$blog_layout = mfn_opts_get( 'blog-layout', 'classic' );
}
$blog_classes[] = $blog_layout;

// isotope
if( $blog_layout=='masonry' ) $blog_classes[] = 'isotope';

// ajax load more
$load_more = mfn_opts_get('blog-load-more');

$translate['filter'] 		= mfn_opts_get('translate') ? mfn_opts_get('translate-filter','Filter by') : __('Filter by','betheme');
$translate['tags'] 			= mfn_opts_get('translate') ? mfn_opts_get('translate-tags','Tags') : __('Tags','betheme');
$translate['authors'] 		= mfn_opts_get('translate') ? mfn_opts_get('translate-authors','Authors') : __('Authors','betheme');
$translate['all'] 			= mfn_opts_get('translate') ? mfn_opts_get('translate-all','Show all') : __('Show all','betheme');
$translate['categories'] 	= mfn_opts_get('translate') ? mfn_opts_get('translate-categories','Categories') : __('Categories','betheme');
?>

<!-- #Content -->
<div id="Content">
	<div class="content_wrapper clearfix">

		<!-- .sections_group -->
		<div class="sections_group">
			
			<div class="extra_content">
				<?php 
					if( category_description() ){
						echo '<div class="section the_content category_description">';
							echo '<div class="section_wrapper">';
								echo '<div class="the_content_wrapper">';
									echo category_description();
								echo '</div>';
							echo '</div>';
						echo '</div>';
					} else {
						mfn_builder_print(mfn_ID());
					}
				?>
			</div>
			
			<div class="section">
				<div class="section_wrapper clearfix">
				
					<!-- #Filters -->
					<?php if( ! is_singular() && get_post_type()=='post' && get_option( 'page_for_posts' ) ): ?>
						<div id="Filters" class="column one <?php if( $blog_layout=='masonry' ) echo 'isotope-filters'; ?>">
						
							<ul class="filters_buttons">
								<li class="label"><?php echo $translate['filter']; ?></li>
								<li class="categories"><a class="open" href="#"><i class="icon-docs"></i><?php echo $translate['categories']; ?><i class="icon-down-dir"></i></a></li>
								<li class="tags"><a class="open" href="#"><i class="icon-tag"></i><?php echo $translate['tags']; ?><i class="icon-down-dir"></i></a></li>
								<li class="authors"><a class="open" href="#"><i class="icon-user"></i><?php echo $translate['authors']; ?><i class="icon-down-dir"></i></a></li>
								<li class="reset"><a class="close" data-rel="*" href="<?php echo get_permalink(mfn_ID()); ?>"><i class="icon-cancel"></i><?php echo $translate['all']; ?></a></li>
							</ul>
							
							<div class="filters_wrapper">
								<ul class="categories">
									<?php 
										if( $categories = get_categories() ){
											foreach( $categories as $category ){
												echo '<li><a data-rel=".category-'. $category->slug .'" href="'. get_term_link($category) .'">'. $category->name .'</a></li>';
											}
										}
									?>
									<li class="close"><a href="#"><i class="icon-cancel"></i></a></li>
								</ul>
								<ul class="tags">
									<?php 
										if( $tags = get_tags() ){
											foreach( $tags as $tag ){
												echo '<li><a data-rel=".tag-'. $tag->slug .'" href="'. get_tag_link($tag) .'">'. $tag->name .'</a></li>';
											}
										}
									?>
									<li class="close"><a href="#"><i class="icon-cancel"></i></a></li>
								</ul>
								<ul class="authors">
									<?php 
										$authors = get_users();
										if( is_array($authors) ){
											
											// remove subscribers
											foreach( $authors as $ka => $author ){
												$remove = true;
												if( in_array( 'author', $author->roles ) ) $remove = false;
												if( in_array( 'editor', $author->roles ) ) $remove = false;
												if( in_array( 'administrator', $author->roles ) ) $remove = false;
												if( $remove ) unset( $authors[$ka] );
											}
										
											foreach( $authors as $auth ){
												echo '<li><a data-rel=".author-'. $auth->data->user_login .'" href="'. get_author_posts_url($auth->ID) .'">'. $auth->data->display_name .'</a></li>';
											}
										
										}
									?>
									<li class="close"><a href="#"><i class="icon-cancel"></i></a></li>
								</ul>
							</div>
									
						</div>
					<?php endif; ?>
					
					<div class="column one column_blog">	
						<div class="blog_wrapper isotope_wrapper">
						
							<div class="posts_group lm_wrapper <?php echo implode(' ', $blog_classes); ?>">
								<?php echo mfn_content_post( false, false, $load_more ); ?>
							</div>
						
							<?php	
								// pagination
								if( function_exists( 'mfn_pagination' ) ):

									echo mfn_pagination( false, $load_more );
								
								else:
									?>
										<div class="nav-next"><?php next_posts_link(__('&larr; Older Entries', 'betheme')) ?></div>
										<div class="nav-previous"><?php previous_posts_link(__('Newer Entries &rarr;', 'betheme')) ?></div>
									<?php
								endif;
							?>
						
						</div>
					</div>

				</div>	
			</div>
			
		</div>	
		
		<!-- .four-columns - sidebar -->
		<?php get_sidebar( 'blog' ); ?>

	</div>
</div>

<?php get_footer(); ?>