<?php
    function lip_love_it_link( $post_id = null, $echo = true, $text = "" ) {

        global $user_ID, $post;

        if ( is_null( $post_id ) ) {
            $post_id = $post->ID;
        }

        global $sf_options;
        $loveit_icon = $sf_options['loveit_icon'];
        $loveit_text = $sf_options['loveit_text'];
        $icon        = "";

        if ( isset( $loveit_icon ) && $loveit_icon != "" ) {
            $icon = '<i class="' . $loveit_icon . '"></i>';
        } else {
            $icon = '<i class="ss-heart"></i>';
        }

        // retrieve the total love count for this item
        $love_count = lip_get_love_count( $post_id );

        if ( $text != "" ) {
            $text = ' ' . $loveit_text;
        }

        ob_start();

        // our wrapper DIV
        echo '<div class="love-it-wrapper">';

        if ( ! lip_user_has_loved_post( $user_ID, $post_id ) ) {
            echo '<a href="#" class="love-it" data-post-id="' . $post_id . '" data-user-id="' . $user_ID . '">' . $icon . '<span class="love-count"><data class="count" value="">' . $love_count . '</data>' . $text . '</span></a>';
        } else {
            echo '<a href="#" class="love-it loved" data-post-id="' . $post_id . '" data-user-id="' . $user_ID . '">' . $icon . '<span class="love-count"><data class="count" value="">' . $love_count . '</data>' . $text . '</span></a>';
        }

        // close our wrapper DIV
        echo '</div>';

        if ( $echo ) {
            echo apply_filters( 'lip_links', ob_get_clean() );
        } else {
            return apply_filters( 'lip_links', ob_get_clean() );
        }
    }


    function lip_love_it_nolink( $post_id = null, $link_text, $already_loved, $echo = true ) {

        global $user_ID, $post;

        if ( is_null( $post_id ) ) {
            $post_id = $post->ID;
        }

        // retrieve the total love count for this item
        $love_count = lip_get_love_count( $post_id );

        ob_start();

        // our wrapper DIV
        echo '<div class="love-it-wrapper">';

        // show a message to users who have already loved this item
        echo '<span class="loved">' . $already_loved . ' <span class="love-count">' . $love_count . '</span></span>';

        // close our wrapper DIV
        echo '</div>';

        if ( $echo ) {
            echo apply_filters( 'lip_links', ob_get_clean() );
        } else {
            return apply_filters( 'lip_links', ob_get_clean() );
        }
    }