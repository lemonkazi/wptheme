<?php get_header(); ?>

<?php
    $sidebar_config = sf_get_post_meta( $post->ID, 'sf_sidebar_config', true );
    $pb_fw_mode     = true;
    $pb_active      = sf_get_post_meta( $post->ID, '_spb_js_status', true );
    if ( $sidebar_config != "no-sidebars" || $pb_active != "true" ) {
        $pb_fw_mode = false;
    }

?>

<?php
    // Check if page should be enabled in full width mode
    if ( ! $pb_fw_mode ) {
        ?>
        <div class="container">
    <?php } ?>

<?php sf_base_layout( 'page' ); ?>

<?php
    // Check if page should be enabled in full width mode
    if ( ! $pb_fw_mode ) {
        ?>
        </div>
    <?php } ?>

<?php get_footer(); ?>