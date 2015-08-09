<?php
    /*
    *
    *	Styleswitcher
    *	------------------------------------------------
    *	Swift Framework
    * 	Copyright Swift Ideas 2014 - http://www.swiftideas.net
    *
    */

    if ( ! function_exists( 'sf_styleswitcher' ) ) {
        function sf_styleswitcher() {

            global $sf_options;
            $enable_styleswitcher = $sf_options['enable_styleswitcher'];

            if ( $enable_styleswitcher ) {
                $styleswitcher_path = get_template_directory_uri() . '/includes/sf-styleswitcher/';

                ?>
                <div class="style-switcher">
                    <h4>Design Style Switcher<a class="switch-button" href="#"><i class="ss-write"></i></a></h4>

                    <div class="switch-cont">
                        <div class="options">
                            <select class="style-select">
                                <option value="minimal-design">Minimal</option>
                                <option value="bold-design">Bold</option>
                                <option value="bright-design">Bright</option>
                            </select>
                        </div>
                    </div>
                </div>

                <script>
                    var onLoad = {
                        init: function() {

                            var body = jQuery( 'body' );

                            jQuery( '.style-switcher' ).on(
                                'click', 'a.switch-button', function( e ) {
                                    e.preventDefault();
                                    var $style_switcher = jQuery( '.style-switcher' );
                                    if ( $style_switcher.css( 'left' ) === '0px' ) {
                                        $style_switcher.animate(
                                            {
                                                left: '-240'
                                            }
                                        );
                                    } else {
                                        $style_switcher.animate(
                                            {
                                                left: '0'
                                            }
                                        );
                                    }
                                }
                            );

                            if ( body.hasClass( 'minimal-design' ) ) {
                                jQuery( '.style-select option[value="minimal-design"]' ).prop( "selected", "selected" )
                            } else if ( body.hasClass( 'bold-design' ) ) {
                                jQuery( '.style-select option[value="bold-design"]' ).prop( "selected", "selected" )
                            } else if ( body.hasClass( 'bright-design' ) ) {
                                jQuery( '.style-select option[value="bright-design"]' ).prop( "selected", "selected" )
                            }

                            jQuery( '.style-select' ).change(
                                function() {
                                    body.removeClass( 'minimal-design' ).removeClass( 'bright-design' ).removeClass( 'bold-design' );
                                    body.addClass( jQuery( '.style-select' ).val() );
                                }
                            );

                        }
                    };

                    jQuery( document ).ready( onLoad.init );
                </script>

            <?php
            }
        }

        add_action( 'wp_footer', 'sf_styleswitcher' );
    }
?>