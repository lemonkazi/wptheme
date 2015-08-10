<?php
/**
 * The template for displaying content in the index.php template
 *
 * @package Betheme
 * @author Muffin group
 * @link http://muffingroup.com
 */

if( ! function_exists('mfn_content_post') ){
	function mfn_content_post( $query = false, $layout = false, $load_more = false ){
		global $wp_query;
		$output = '';
	
		$translate['published'] 	= mfn_opts_get('translate') ? mfn_opts_get('translate-published','Published by') : __('Published by','betheme');
		$translate['at'] 			= mfn_opts_get('translate') ? mfn_opts_get('translate-at','at') : __('at','betheme');
		$translate['categories'] 	= mfn_opts_get('translate') ? mfn_opts_get('translate-categories','Categories') : __('Categories','betheme');
		$translate['like'] 			= mfn_opts_get('translate') ? mfn_opts_get('translate-like','Do you like it?') : __('Do you like it?','betheme');
		$translate['readmore'] 		= mfn_opts_get('translate') ? mfn_opts_get('translate-readmore','Read more') : __('Read more','betheme');
	
		if( ! $query ) $query = $wp_query;
	
		if ( $query->have_posts() ){
			while ( $query->have_posts() ){
				$query->the_post();
				
				$post_class =  array('post-item','isotope-item','clearfix');
				if( ! mfn_post_thumbnail( get_the_ID() ) ) $post_class[] = 'no-img';
				if( post_password_required() ) $post_class[] = 'no-img';
				$post_class[] = 'author-'. get_the_author_meta( 'user_login' );
				$post_class = implode(' ', get_post_class( $post_class ));
				
				$output .= '<div class="'. $post_class .'">';
					
					$output .= '<div class="date_label">'. get_the_date() .'</div>'; // style: timeline
					
					// photo --------------------------------------------------------------------------
					if( ! post_password_required() ){
						$output .= '<div class="image_frame post-photo-wrapper scale-with-grid">';
							$output .= '<div class="image_wrapper">';
								$output .= mfn_post_thumbnail( get_the_ID(), false, false, $load_more );
							$output .= '</div>';
						$output .= '</div>';
					}
				
					// desc ---------------------------------------------------------------------------
					$output .= '<div class="post-desc-wrapper">';
						$output .= '<div class="post-desc">';
						
							// meta -------------------------------------
							if( mfn_opts_get( 'blog-meta' ) ){
								$output .= '<div class="post-meta clearfix">';
								
									$output .= '<div class="author-date">';
										$output .= '<span class="author"><span>'. $translate['published'] .' </span><i class="icon-user"></i> <a href="'. get_author_posts_url( get_the_author_meta( 'ID' ) ) .'">'. get_the_author_meta( 'display_name' ) .'</a></span> ';
										$output .= '<span class="date"><span>'. $translate['at'] .' </span><i class="icon-clock"></i> '. get_the_date() .'</span>';	
									$output .= '</div>';
									
									$output .= '<div class="category">';
										$output .= '<span class="cat-btn">'. $translate['categories'] .' <i class="icon-down-dir"></i></span>';
										$output .= '<div class="cat-wrapper">'. get_the_category_list() .'</div>';
									$output .= '</div>';
										
								$output .= '</div>';
							}
						
							// title -------------------------------------
							$output .= '<div class="post-title">';		
									
								if( get_post_format() == 'quote' ){
									// quote ----------------------------
									$output .= '<blockquote><a href="'. get_permalink() .'">'. get_the_title() .'</a></blockquote>';
								
								} elseif( get_post_format() == 'link' ){
									// link ----------------------------
									$output .= '<i class="icon-link"></i>';
									$output .= '<div class="link-wrapper">';
										$output .= '<h4>'. get_the_title() .'</h4>';
										$link = get_post_meta(get_the_ID(), 'mfn-post-link', true);
										$output .= '<a target="_blank" href="'. $link .'">'. $link .'</a>';
									$output .= '</div>';
									
								} else {
									// default ----------------------------
									$output .= '<h2 class="entry-title" itemprop="headline"><a href="'. get_permalink() .'">'. get_the_title() .'</a></h2>';
								}
								
							$output .= '</div>';
		
							// content -------------------------------------
							$output .= '<div class="post-excerpt">'. get_the_excerpt() .'</div>';
							
							// footer -------------------------------------
							$output .= '<div class="post-footer">';
								
								$output .= '<div class="button-love"><span class="love-text">'. $translate['like'] .'</span>'. mfn_love() .'</div>';
								
								$output .= '<div class="post-links">';
									if( comments_open() ) $output .= '<i class="icon-comment-empty-fa"></i> <a href="'. get_comments_link() .'" class="post-comments">'. get_comments_number() .'</a>';
									$output .= '<i class="icon-doc-text"></i> <a href="'. get_permalink() .'" class="post-more">'. $translate['readmore'] .'</a>';
								$output .= '</div>';
								
							$output .= '</div>';
							
						$output .= '</div>';
					$output .= '</div>';
				
				$output .= '</div>';
				
			}
			
// 			$output .= '<div class="TILIM">'. $wp_query->max_num_pages .'</div>';
		}
		
		return $output;
	}
}

?>