<?php
/*
* Theme Name: AMY Theme - Creative Multi-Purpose WordPress Theme
* Theme Author: Andrey Boyadzhiev - http://themes.cray.bg
*
* Version: 1.0 
*/
?>
<?php
if ( post_password_required() ) {
	return;
}
global $ab_amy_settings, $ab_tf_post_showdscomments,$ab_tf_post_showfbcomments, $tr_disqus_title, $tr_facebook_title, $ab_tf_show_sidebar, $ab_tf_post_color;

//AJAX COMMENTS FUNCTION
//=====================================================
if (comments_open()){?>
	<script>
    //Ajax comments
    //==================================================
    jQuery(document).ready(function($){
        var commentform=$('#commentform');
        commentform.prepend('<div id="comment-status" ></div>');
        var statusdiv=$('#comment-status');
         
        commentform.submit(function(){
            var formdata=commentform.serialize();
            statusdiv.html('<p><?php echo $ab_amy_settings['tr-comm-process'];?></p>');
            var formurl=commentform.attr('action');
            $.ajax({
                type: 'post',
                url: formurl,
                data: formdata,
                error: function(XMLHttpRequest, textStatus, errorThrown){
                statusdiv.html('<p><?php echo $ab_amy_settings['tr-comm-error'];?></p>');
                },
                success: function(data, textStatus){
                    if(data=="success"){
                    statusdiv.html('<p><?php echo $ab_amy_settings['tr-comm-thanks'];?></p>');
                    }else{
                    statusdiv.html('<p><?php echo $ab_amy_settings['tr-comm-thanks'];?></p>');
                    commentform.find('textarea[name=comment]').val('');
                    }
                }
            });
        return false;
        });
    });
    </script><?php 
} 

//DISQUS COMMENTS
//=====================================================
if($ab_tf_post_showdscomments == 'on' ){?>
    <div class="ss-full ss-row fb-holder ss-stand-alone disquis_h fullwidthrow">
        <div class="container-border"<?php if($ab_tf_show_sidebar != 'hide' ){ ?>class="container-border"<?php }?>>
            <div class="<?php global $ab_tf_post_color; echo $ab_tf_post_color;?>">
                <h3 class="content-title comm-title"><?php echo $ab_amy_settings['tr-disqus-title'];?></h3> 
				<div id="disqus_thread"><p></p></div>
            </div> 
        </div> 
    </div>
	<script>
		//Disqus API
		//==================================================
		jQuery(document).ready(function ($) {
			var disqus_shortname = '<?php echo $ab_amy_settings['disqus-id']; ?>'; // required: replace example with your forum shortname
			(function() {
				var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
				dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
				(document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
			})();	
		});
	</script><?php
}; 


//FACEBOOK COMMENTS
//=====================================================
if($ab_tf_post_showfbcomments == 'on' ){?> 
    <div class="ss-full ss-row fb-holder ss-stand-alone fullwidthrow">
        <div class="container-border">
            <div class="gray-container <?php global $ab_tf_post_color; echo $ab_tf_post_color;?>">
                <h3 class="content-title comm-title"><?php echo $ab_amy_settings['tr-facebook-title'];?></h3>
				<div class="fb-comments" data-href="<?php the_permalink(); ?>" data-num-posts="2" data-height="250" data-width="555"  data-colorscheme="light" ></div>
            </div> 
        </div> 
    </div><?php 
};

//WORDPRESS COMMENTS
//=====================================================
if ( have_comments() && comments_open() ) : ?>
	<div id="comments" class="comments-area ss-full fullwidthrow">
		<h2 class="content-title comm-title"><?php
			printf( _n( '%1 '.$ab_amy_settings['tr-comm-1comm'].' &ldquo;%2$s&rdquo;', '%1$s '.$ab_amy_settings['tr-comm-2comm'].' &ldquo;%2$s&rdquo;', get_comments_number(), 'twentyfourteen' ), number_format_i18n( get_comments_number() ), get_the_title() );?>
		</h2><?php 
		if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) {?>
			<nav id="comment-nav-above" class="navigation comment-navigation" role="navigation">
				<h1 class="screen-reader-text"><?php _e( 'Comment navigation', 'twentyfourteen' ); ?></h1>
				<div class="nav-previous"><?php previous_comments_link( __( $ab_amy_settings['tr-comm-oldcomm'], 'twentyfourteen' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( __( $ab_amy_settings['tr-comm-newcomm'], 'twentyfourteen' ) ); ?></div>
			</nav><?php 
		};?>
		<ol class="comment-list"><?php
			wp_list_comments('type=comment&callback=theme_comment&avatar_size=60');?>
		</ol><?php 
		if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ){?>
            <nav id="comment-nav-below" class="navigation comment-navigation" role="navigation">
                <h1 class="screen-reader-text"><?php _e( 'Comment navigation', 'twentyfourteen' ); ?></h1>
                <div class="nav-previous"><?php previous_comments_link( __( $ab_amy_settings['tr-comm-oldcomm'], 'twentyfourteen' ) ); ?></div>
                <div class="nav-next"><?php next_comments_link( __(  $ab_amy_settings['tr-comm-newcomm'], 'twentyfourteen' ) ); ?></div>
            </nav><?php 
		};
		if ( ! comments_open() ) { ?>
			<p class="no-comments"><?php _e( 'Comments are closed.', 'twentyfourteen' ); ?></p><?php 
		};
endif;

//ADD COMMENT FORM
//=====================================================
if(comments_open() ){?>
	<div class="gray-container <?php  echo $ab_tf_post_color;?> addcolor">
		<div class="comments-add-c fullwidthrow"><?php
			if( ! comments_open() && ! is_page() && post_type_supports( get_post_type(), 'comments' ) ){?>
				<p class="nocomments"><?php echo 'Comments are closed.'; ?></p><?php
			};
			$args = array(
				'id_form'           => 'commentform',
				'id_submit'         => 'submit',
				'title_reply'       => '',
				'title_reply_to'    => $ab_amy_settings['tr-comm-title'].'to %s',
				'label_submit'      => $ab_amy_settings['tr-comm-submit'] ,
				
				'comment_field'		=>  '<div class="comment-form-comment"><label for="comment">' .$ab_amy_settings['tr-comm-comment'] .
				'</label><textarea id="comment" name="comment" cols="45" rows="3" aria-required="true">' .
				'</textarea></div>',
				
				'must_log_in' 		=> '<div class="must-log-in">' .
				sprintf(
				  $ab_amy_settings['tr-comm-mustlogin'].' <a href="%s">'.$ab_amy_settings['tr-comm-login'].'</a>',
				  wp_login_url( apply_filters( 'the_permalink', get_permalink() ) )
				) . '</div>',
				
				'logged_in_as' 		=> '<div class="logged-in-as">' .
				sprintf(
				 $ab_amy_settings['tr-comm-loggedin'].' <a href="%1$s">%2$s</a>. <a href="%3$s" title="Log out of this account">'.$ab_amy_settings['tr-comm-logout'].'</a>' ,
				  admin_url( 'profile.php' ),
				  $user_identity,
				  wp_logout_url( apply_filters( 'the_permalink', get_permalink( ) ) )
				) . '</div>',
				
				'comment_notes_before' => '<div class="comment-notes">' .$ab_amy_settings['tr-comm-subtitle'].
				'</div>',
				
				'comment_notes_after' => '',
				
				'fields' 		=> apply_filters( 'comment_form_default_fields', array(
				
				'author' 		=>
				  '<div class="comment-form-author">' .
				  '<label for="author">'.$ab_amy_settings['tr-comm-name'].'</label><span class="required">*</span>
				  <input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) .
				  '" size="30" /></div>',
				
				'email' 		=>
				  '<div class="comment-form-email"><label for="email">'.$ab_amy_settings['tr-comm-email'].'</label> ' .
				  ( $req ? '<span class="required">*</span>' : '' ) .
				  '<input id="email" name="email" type="text" value="' . esc_attr(  $commenter['comment_author_email'] ) .
				  '" size="30" /></div>',
				
				'url' =>''
					)
				),
			);
			if(post_password_required()){?>
			<h3 class="content-title comm-title"><?php echo  $ab_amy_settings['tr-comm-title'];?></h3> <?php
				echo 'This post is password protected. Enter the password to view any comments.';
			}else{
				?>
			<h3 class="content-title comm-title"><?php echo $ab_amy_settings['tr-comm-title'];?></h3> <?php
			comment_form($args);
			}?>
		</div>
	</div>
</div><?php 
};?>
