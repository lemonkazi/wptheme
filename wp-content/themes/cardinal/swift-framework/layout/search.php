<?php
    global $sf_options;

    $blog_type    = $sf_options['archive_display_type'];
    $blog_classes = sf_blog_classes( $blog_type );
?>

<?php if ( have_posts() ) : ?>

    <div class="blog-wrap">

        <!-- OPEN .blog-items -->
        <ul class="blog-items row search-items clearfix">

            <?php while ( have_posts() ) : the_post(); ?>

                <?php
                $post_format = get_post_format( $post->ID );
                if ( $post_format == "" ) {
                    $post_format = 'standard';
                }
                ?>
                <li <?php post_class( 'blog-item col-sm-12 format-' . $post_format ); ?>>
                    <?php echo sf_get_search_item( $post->ID ); ?>
                </li>

            <?php endwhile; ?>

            <!-- CLOSE .blog-items -->
        </ul>

    </div>

<?php else: ?>


    <h3><?php _e( "Sorry, there are no posts to display.", "swiftframework" ); ?></h3>

    <div class="no-results-text">
        <p><?php _e( "Please use the form below to search again.", "swiftframework" ); ?></p>

        <form method="get" class="search-form" action="<?php echo home_url(); ?>/">
            <input type="text" placeholder="<?php _e( "Search", "swiftframework" ); ?>" name="s"/>
        </form>
        <p><?php _e( "Alternatively, you can browse the sitemap below.", "swiftframework" ); ?></p>
        <?php echo do_shortcode( '[sf_sitemap]' ); ?>
    </div>

<?php endif; ?>

<div class="pagination-wrap">
    <?php echo pagenavi( $wp_query ); ?>
</div>
