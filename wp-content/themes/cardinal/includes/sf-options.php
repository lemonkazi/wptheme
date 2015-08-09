<?php

    /**
     * ReduxFramework Sample Config File
     * For full documentation, please visit: https://github.com/ReduxFramework/ReduxFramework/wiki
     **/

    if ( ! class_exists( "ReduxFramework" ) ) {
        return;
    }

    if ( ! class_exists( "Redux_Framework_options_config" ) ) {
        class Redux_Framework_options_config {

            public $args = array();
            public $sections = array();
            public $theme;
            public $ReduxFramework;

            public function __construct() {

                if ( ! class_exists( 'ReduxFramework' ) ) {
                    return;
                }

                // This is needed. Bah WordPress bugs.  ;)
//            if (strpos(Redux_Helpers::cleanFilePath(__FILE__), Redux_Helpers::cleanFilePath(get_stylesheet_directory())) !== false) {
//                $this->initSettings();
//            } else {
//                add_action('plugins_loaded', array($this, 'initSettings'), 10);
//            }

                // Used in theme, so we can bypass the above
                $this->initSettings();

            }

            public function initSettings() {

                // Just for demo purposes. Not needed per say.
                $this->theme = wp_get_theme();

                // Set the default arguments
                $this->setArguments();

                // Set a few help tabs so you can see how it's done
                //$this->setHelpTabs();

                // Create the sections and fields
                $this->setSections();

                if ( ! isset( $this->args['opt_name'] ) ) { // No errors please
                    return;
                }


                // If Redux is running as a plugin, this will remove the demo notice and links
                //add_action( 'redux/loaded', array( $this, 'remove_demo' ) );

                // Function to test the compiler hook and demo CSS output.
                // Above 10 is a priority, but 2 in necessary to include the dynamically generated CSS to be sent to the function.
                //add_filter('redux/options/'.$this->args['opt_name'].'/compiler', array( $this, 'compiler_action' ), 10, 2);

                // Change the arguments after they've been declared, but before the panel is created
                //add_filter('redux/options/'.$this->args['opt_name'].'/args', array( $this, 'change_arguments' ) );

                // Change the default value of a field after it's been set, but before it's been useds
                //add_filter('redux/options/'.$this->args['opt_name'].'/defaults', array( $this,'change_defaults' ) );

                // Dynamically add a section. Can be also used to modify sections/fields
                //add_filter('redux/options/' . $this->args['opt_name'] . '/sections', array($this, 'dynamic_section'));

                $this->ReduxFramework = new ReduxFramework( $this->sections, $this->args );
            }


            /**
             * Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.
             **/

            function change_arguments( $args ) {
                //$args['dev_mode'] = true;

                return $args;
            }


            /**
             * Filter hook for filtering the default value of any given field. Very useful in development mode.
             **/

            function change_defaults( $defaults ) {
                $defaults['str_replace'] = "Testing filter hook!";

                return $defaults;
            }


            // Remove the demo link and the notice of integrated demo from the redux-framework plugin
            function remove_demo() {

                // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
                if ( class_exists( 'ReduxFrameworkPlugin' ) ) {
                    remove_filter( 'plugin_row_meta', array(
                            ReduxFrameworkPlugin::get_instance(),
                            'plugin_meta_demo_mode_link'
                        ), null, 2 );
                }

                // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
                remove_action( 'admin_notices', array( ReduxFrameworkPlugin::get_instance(), 'admin_notices' ) );

            }


            public function setSections() {

                /**
                 * Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
                 **/

                // Background Patterns Reader
                $sample_patterns_path = ReduxFramework::$_dir . '../sample/patterns/';
                $sample_patterns_url  = ReduxFramework::$_url . '../sample/patterns/';
                $template_directory   = get_template_directory_uri();
                $preset_bgs           = $template_directory . '/images/preset-backgrounds/';
                $sample_patterns      = array();

                if ( is_dir( $sample_patterns_path ) ) :

                    if ( $sample_patterns_dir = opendir( $sample_patterns_path ) ) :
                        $sample_patterns = array();

                        while ( ( $sample_patterns_file = readdir( $sample_patterns_dir ) ) !== false ) {

                            if ( stristr( $sample_patterns_file, '.png' ) !== false || stristr( $sample_patterns_file, '.jpg' ) !== false ) {
                                $name              = explode( ".", $sample_patterns_file );
                                $name              = str_replace( '.' . end( $name ), '', $sample_patterns_file );
                                $sample_patterns[] = array(
                                    'alt' => $name,
                                    'img' => $sample_patterns_url . $sample_patterns_file
                                );
                            }
                        }
                    endif;
                endif;

                ob_start();

                $ct          = wp_get_theme();
                $this->theme = $ct;
                $item_name   = $this->theme->get( 'Name' );
                $tags        = $this->theme->Tags;
                $screenshot  = $this->theme->get_screenshot();
                $class       = $screenshot ? 'has-screenshot' : '';

                $customize_title = sprintf( __( 'Customize &#8220;%s&#8221;', 'swift-framework-admin' ), $this->theme->display( 'Name' ) );

                ?>
                <div id="current-theme" class="<?php echo esc_attr( $class ); ?>">
                    <?php if ( $screenshot ) : ?>
                        <?php if ( current_user_can( 'edit_theme_options' ) ) : ?>
                            <a href="<?php echo wp_customize_url(); ?>" class="load-customize hide-if-no-customize"
                               title="<?php echo esc_attr( $customize_title ); ?>">
                                <img src="<?php echo esc_url( $screenshot ); ?>"
                                     alt="<?php esc_attr_e( 'Current theme preview', 'swift-framework-admin' ); ?>"/>
                            </a>
                        <?php endif; ?>
                        <img class="hide-if-customize" src="<?php echo esc_url( $screenshot ); ?>"
                             alt="<?php esc_attr_e( 'Current theme preview', 'swift-framework-admin' ); ?>"/>
                    <?php endif; ?>

                    <h4>
                        <?php echo $this->theme->display( 'Name' ); ?>
                    </h4>

                    <div>
                        <ul class="theme-info">
                            <li><?php printf( __( 'By %s', 'swift-framework-admin' ), $this->theme->display( 'Author' ) ); ?></li>
                            <li><?php printf( __( 'Version %s', 'swift-framework-admin' ), $this->theme->display( 'Version' ) ); ?></li>
                            <li><?php echo '<strong>' . __( 'Tags', 'swift-framework-admin' ) . ':</strong> '; ?><?php printf( $this->theme->display( 'Tags' ) ); ?></li>
                        </ul>
                        <p class="theme-description"><?php echo $this->theme->display( 'Description' ); ?></p>
                        <?php if ( $this->theme->parent() ) {
                            printf( ' <p class="howto">' . __( 'This <a href="%1$s">child theme</a> requires its parent theme, %2$s.', 'swift-framework-admin' ) . '</p>',
                                __( 'http://codex.wordpress.org/Child_Themes', 'swift-framework-admin' ),
                                $this->theme->parent()->display( 'Name' ) );
                        } ?>

                    </div>

                </div>

                <?php
                $item_info = ob_get_contents();

                ob_end_clean();

                $sampleHTML = '';
                if ( file_exists( dirname( __FILE__ ) . '/info-html.html' ) ) {
                    /** @global WP_Filesystem_Direct $wp_filesystem */
                    global $wp_filesystem;
                    if ( empty( $wp_filesystem ) ) {
                        require_once( ABSPATH . '/wp-admin/includes/file.php' );
                        WP_Filesystem();
                    }
                    $sampleHTML = $wp_filesystem->get_contents( dirname( __FILE__ ) . '/info-html.html' );
                }

                // ACTUAL DECLARATION OF SECTIONS

                if ( isset( $_GET['sf_welcome'] ) ) {
                    if ( $_GET['sf_welcome'] == "true" ) {
                        $this->sections[] = array(
                            'title'  => __( 'Welcome', 'swift-framework-admin' ),
                            'desc'   => 'Welcome to Cardinal.',
                            'icon'   => 'el-icon-star',
                            // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                            'fields' => array(
                                array(
                                    'id'   => 'co_divide_1',
                                    'type' => 'divide'
                                ),
                            ),
                        );
                    }
                }

                $this->sections[] = array(
                    'title'  => __( 'General Options', 'swift-framework-admin' ),
                    'desc'   => '',
                    'icon'   => 'el-icon-wrench',
                    // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                    'fields' => array(
                        array(
                            'id'       => 'enable_responsive',
                            'type'     => 'button_set',
                            'title'    => __( 'Enable Responsive', 'swift-framework-admin' ),
                            'subtitle' => __( 'Enable/Disable the responsive behaviour of the theme', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'On', '0' => 'Off' ),
                            'default'  => '1'
                        ),
                        array(
                            'id'       => 'site_maxwidth',
                            'type'     => 'slider',
                            'title'    => __( 'Site Max-Width', 'swift-framework-admin' ),
                            'subtitle' => __( "Set the maximum width for the site, at it's largest. By default this is 1170px.", 'swift-framework-admin' ),
                            "default"  => "1170",
                            "min"      => "940",
                            "step"     => "10",
                            "max"      => "2000",
                        ),
                        array(
                            'id'       => 'enable_rtl',
                            'type'     => 'button_set',
                            'title'    => __( 'Enable RTL mode', 'swift-framework-admin' ),
                            'subtitle' => __( 'Enable this mode for right-to-left language mode', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'On', '0' => 'Off' ),
                            'default'  => '0'
                        ),
                        array(
                            'id'       => 'page_layout',
                            'type'     => 'image_select',
                            'title'    => __( 'Page Layout', 'swift-framework-admin' ),
                            'subtitle' => __( 'Select the page layout type', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array(
                                'boxed'     => array(
                                    'title' => 'Boxed',
                                    'img'   => $template_directory . '/images/page-bordered.png'
                                ),
                                'fullwidth' => array(
                                    'title' => 'Full Width',
                                    'img'   => $template_directory . '/images/page-fullwidth.png'
                                )
                            ),
                            'default'  => 'fullwidth'
                        ),
                        array(
                            'id'       => 'enable_page_shadow',
                            'type'     => 'button_set',
                            'title'    => __( 'Page shadow', 'swift-framework-admin' ),
                            'subtitle' => __( 'Enable the shadow for the boxed layout / vertical header setups.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'On', '0' => 'Off' ),
                            'default'  => '1'
                        ),
                        array(
                            'id'       => 'enable_page_transitions',
                            'type'     => 'button_set',
                            'title'    => __( 'Page Transitions', 'swift-framework-admin' ),
                            'subtitle' => __( 'Enable the transition animation that occurs upon changing pages.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'On', '0' => 'Off' ),
                            'default'  => '0'
                        ),
                        array(
                            'id'       => 'page_transition',
                            'type'     => 'select',
                            'title'    => __( 'Page Transition', 'swift-framework-admin' ),
                            'subtitle' => __( 'Select which style of transition to show across the site for page transitions, and other loading indicators.', 'swift-framework-admin' ),
                            'options'  => array(
                                'rotating-square' => 'Rotating Square',
                                'wave'            => 'Bar Wave',
                                'three-bounce'    => 'Three Dot Bounce',
                                'circle'          => 'Circle Dots',
                                'loading-bar'     => 'Loading Bar (YouTube style)',
                            ),
                            'desc'     => '',
                            'default'  => 'wave'
                        ),
                        array(
                            'id'       => 'enable_articleswipe',
                            'type'     => 'button_set',
                            'title'    => __( 'Enable Article Swipe Navigation', 'swift-framework-admin' ),
                            'subtitle' => __( 'Enable/Disable the ability to swipe left/right to navigate posts', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'On', '0' => 'Off' ),
                            'default'  => '0'
                        ),
                        array(
                            'id'       => 'enable_backtotop',
                            'type'     => 'button_set',
                            'title'    => __( 'Enable Back To Top', 'swift-framework-admin' ),
                            'subtitle' => __( 'Enable the back to top button that appears in the bottom right corner of the screen.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'On', '0' => 'Off' ),
                            'default'  => '1'
                        ),
                        array(
                            'id'       => 'sidebar_width',
                            'type'     => 'button_set',
                            'title'    => __( 'Sidebar Width', 'swift-framework-admin' ),
                            'subtitle' => __( 'Enable/Disable the responsive behaviour of the theme', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( 'standard' => 'Standard (1/3)', 'reduced' => 'Reduced (1/4)' ),
                            'default'  => 'standard'
                        ),
                        //					array(
                        ////						'id' => 'enable_stickysidebars',
                        //						'type' => 'button_set',
                        //						'title' => __('Enable Sticky Sidebars', 'swift-framework-admin'), 
                        //						'subtitle' => __('Enable the sidebars to be sticky on desktop when the sidebar is small enough to display completely while scrolling.', 'swift-framework-admin'),
                        //						'desc' => '',
                        //						'options' => array('1' => 'On','0' => 'Off'),
                        //						'default' => '0'
                        //						),
                        array(
                            'id'       => 'onepagenav_type',
                            'type'     => 'button_set',
                            'title'    => __( 'One Page Nav Type', 'swift-framework-admin' ),
                            'subtitle' => __( 'Enable the display type to show when using the one page navigation (Page Meta Options).', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( 'standard' => 'Standard', 'arrows' => 'Count + Arrows' ),
                            'default'  => 'arrows'
                        ),
                        array(
                            'id'       => 'disable_pagecomments',
                            'type'     => 'button_set',
                            'title'    => __( 'Disable Page Comments', 'swift-framework-admin' ),
                            'subtitle' => __( 'If you enable this option, then page comments will be disabled globally.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'On', '0' => 'Off' ),
                            'default'  => '1'
                        ),
                        array(
                            'id'       => 'enable_twitter_rts',
                            'type'     => 'button_set',
                            'title'    => __( 'Enable Retweets in Twitter Assets', 'swift-framework-admin' ),
                            'subtitle' => __( 'If you enable this option, then Retweets will be included in your twitter assets.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'On', '0' => 'Off' ),
                            'default'  => '0'
                        ),
                        array(
                            'id'       => 'breadcrumb_in_heading',
                            'type'     => 'button_set',
                            'title'    => __( 'Show Breadcrumbs in Page Heading', 'swift-framework-admin' ),
                            'subtitle' => __( 'If you enable this option, then breadcrumbs will show in the page heading, rather than on their own bar.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'On', '0' => 'Off' ),
                            'default'  => '0'
                        ),
                        array(
                            'id'   => 'general_divide_0',
                            'type' => 'divide'
                        ),
                        array(
                            'id'       => 'custom_favicon',
                            'type'     => 'media',
                            'url'      => true,
                            'title'    => __( 'Custom favicon', 'swift-framework-admin' ),
                            'subtitle' => __( 'Upload a 16px x 16px Png/Gif image that will represent your website favicon', 'swift-framework-admin' ),
                            'desc'     => ''
                        ),
                        array(
                            'id'       => 'custom_ios_title',
                            'type'     => 'text',
                            'title'    => __( 'Custom iOS Bookmark Title', 'swift-framework-admin' ),
                            'subtitle' => __( 'Enter a custom title for your site for when it is added as an iOS bookmark.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'default'  => ''
                        ),
                        array(
                            'id'       => 'custom_ios_icon57',
                            'type'     => 'media',
                            'url'      => true,
                            'title'    => __( 'Custom iOS 57x57', 'swift-framework-admin' ),
                            'subtitle' => __( 'Upload a 57px x 57px Png image that will be your website bookmark on non-retina iOS devices.', 'swift-framework-admin' ),
                            'desc'     => ''
                        ),
                        array(
                            'id'       => 'custom_ios_icon72',
                            'type'     => 'media',
                            'url'      => true,
                            'title'    => __( 'Custom iOS 72x72', 'swift-framework-admin' ),
                            'subtitle' => __( 'Upload a 72px x 72px Png image that will be your website bookmark on non-retina iOS devices.', 'swift-framework-admin' ),
                            'desc'     => ''
                        ),
                        array(
                            'id'       => 'custom_ios_icon114',
                            'type'     => 'media',
                            'url'      => true,
                            'title'    => __( 'Custom iOS 114x114', 'swift-framework-admin' ),
                            'subtitle' => __( 'Upload a 114px x 114px Png image that will be your website bookmark on retina iOS devices.', 'swift-framework-admin' ),
                            'desc'     => ''
                        ),
                        array(
                            'id'       => 'custom_ios_icon144',
                            'type'     => 'media',
                            'url'      => true,
                            'title'    => __( 'Custom iOS 144x144', 'swift-framework-admin' ),
                            'subtitle' => __( 'Upload a 144px x 144px Png image that will be your website bookmark on retina iOS devices.', 'swift-framework-admin' ),
                            'desc'     => ''
                        ),
                        array(
                            'id'   => 'general_divide_1',
                            'type' => 'divide'
                        ),
                        array(
                            'id'       => 'rss_feed_url',
                            'type'     => 'text',
                            'title'    => __( 'RSS Feed URL', 'swift-framework-admin' ),
                            'subtitle' => __( 'The rss feed URL for your blog.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'default'  => '?feed=rss2'
                        ),
                        array(
                            'id'       => 'google_analytics',
                            'type'     => 'textarea',
                            'title'    => __( 'Tracking code', 'swift-framework-admin' ),
                            'subtitle' => __( 'Paste your Google Analytics (or other) tracking code here. This will be added into the footer template of your theme. NOTE: Please include the script tag.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'default'  => ''
                        ),
                        array(
                            'id'       => 'custom_admin_login_logo',
                            'type'     => 'media',
                            'url'      => true,
                            'title'    => __( 'Custom admin login logo', 'swift-framework-admin' ),
                            'subtitle' => __( 'Upload a 254 x 95px image here to replace the admin login logo.', 'swift-framework-admin' ),
                            'desc'     => ''
                        ),
                        array(
                            'id'       => 'overlay_opacity',
                            'type'     => 'slider',
                            'title'    => __( 'Hover Overlay Opacity', 'swift-framework-admin' ),
                            'subtitle' => __( 'Select the percentage opacity of the hover overlay.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'min'      => '0',
                            'max'      => '100',
                            'step'     => '5',
                            'unit'     => '',
                            'default'  => '90'
                        ),
                        array(
                            'id'       => 'disable_mobile_animations',
                            'type'     => 'button_set',
                            'title'    => __( 'Disable Mobile Intro Animations', 'swift-framework-admin' ),
                            'subtitle' => __( 'Disables the intro animations for assets on mobile browsers.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'On', '0' => 'Off' ),
                            'default'  => '1'
                        ),
                        array(
                            'id'       => 'enable_styleswitcher',
                            'type'     => 'button_set',
                            'title'    => __( 'Enable Front End Style Switcher', 'swift-framework-admin' ),
                            'subtitle' => __( 'Enable/Disable the front end styleswitcher.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'On', '0' => 'Off' ),
                            'default'  => '0'
                        ),
                    ),
                );

                $this->sections[] = array(
                    'title'      => __( 'Maintenance Mode', 'swift-framework-admin' ),
                    'desc'       => '',
                    'subsection' => true,
                    'icon'       => 'el-icon-eye-close',
                    // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                    'fields'     => array(
                        array(
                            'id'       => 'enable_maintenance',
                            'type'     => 'button_set',
                            'title'    => __( 'Enable Maintenance', 'swift-framework-admin' ),
                            'subtitle' => __( 'Enable the themes maintenance mode.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '2' => 'On (Custom Page)', '1' => 'On (Standard)', '0' => 'Off', ),
                            'default'  => '0'
                        ),
                        array(
                            'id'       => 'maintenance_mode_page',
                            'type'     => 'select',
                            'data'     => 'pages',
                            'required' => array( 'enable_maintenance', '=', '2' ),
                            'title'    => __( 'Custom Maintenance Mode Page', 'swift-framework-admin' ),
                            'subtitle' => __( 'Select the page that is your maintenace page, if you would like to show a custom page instead of the standard WordPress message. You should use the Holding Page template for this page.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'default'  => '',
                            'args'     => array()
                        ),
                    ),
                );

                $this->sections[] = array(
                    'title'      => __( 'Performance Options', 'swift-framework-admin' ),
                    'desc'       => '',
                    'subsection' => true,
                    'icon'       => 'el-icon-fire',
                    // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                    'fields'     => array(
                        array(
                            'id'       => 'enable_min_scripts',
                            'type'     => 'button_set',
                            'title'    => __( 'Load pre-minified scripts', 'swift-framework-admin' ),
                            'subtitle' => __( 'Enable this option to load pre-minified scripts, without the need for any plugins.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'On', '0' => 'Off' ),
                            'default'  => '0'
                        ),
                    ),
                );

                $this->sections[] = array(
                    'title'      => __( '404 Page', 'swift-framework-admin' ),
                    'desc'       => '',
                    'subsection' => true,
                    'icon'       => 'el-icon-error',
                    // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                    'fields'     => array(
                        array(
                            'id'       => '404_page_content',
                            'type'     => 'editor',
                            'title'    => __( '404 Page Content', 'swift-framework-admin' ),
                            'subtitle' => 'The content that appears on the 404 page, you can use text/shortcodes/html.',
                            'desc'     => '',
                            'default'  => "Sorry but we couldn't find the page you are looking for. Please check to make sure you've typed the URL correctly. You may also want to search for what you are looking for."
                        ),
                        array(
                            'id'       => '404_sidebar_config',
                            'type'     => 'select',
                            'title'    => __( '404 Sidebar Config', 'swift-framework-admin' ),
                            'subtitle' => "Choose the sidebar config for 404 page.",
                            'options'  => array(
                                'no-sidebars'   => 'No Sidebars',
                                'left-sidebar'  => 'Left Sidebar',
                                'right-sidebar' => 'Right Sidebar',
                                'both-sidebars' => 'Both Sidebars'
                            ),
                            'desc'     => '',
                            'default'  => 'right-sidebar'
                        ),
                        array(
                            'id'       => '404_left_sidebar',
                            'type'     => 'select',
                            'title'    => __( '404 Left Sidebar', 'swift-framework-admin' ),
                            'subtitle' => "Choose the left sidebar for the 404 page.",
                            'data'     => 'sidebars',
                            'desc'     => '',
                            'default'  => 'sidebar-1'
                        ),
                        array(
                            'id'       => '404_right_sidebar',
                            'type'     => 'select',
                            'title'    => __( '404 Right Sidebar', 'swift-framework-admin' ),
                            'subtitle' => "Choose the right sidebar for the 404 page.",
                            'data'     => 'sidebars',
                            'desc'     => '',
                            'default'  => 'sidebar-1'
                        ),
                    ),
                );

                $this->sections[] = array(
                    'title'      => __( 'Meta Options', 'swift-framework-admin' ),
                    'desc'       => '',
                    'subsection' => true,
                    'icon'       => 'el-icon-puzzle',
                    // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                    'fields'     => array(
                        array(
                            'id'       => 'disable_social_meta',
                            'type'     => 'button_set',
                            'title'    => __( 'Disable Social Meta Tags', 'swift-framework-admin' ),
                            'subtitle' => __( 'Disable the social meta head tag output.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'On', '0' => 'Off' ),
                            'default'  => '0'
                        ),
                        array(
                            'id'       => 'twitter_author_username',
                            'type'     => 'text',
                            'title'    => __( 'Twitter Publisher Username', 'swift-framework-admin' ),
                            'subtitle' => "Enter your twitter username here, to be used for the Twitter Card date. Ensure that you do not include the @ symbol.",
                            'desc'     => '',
                            'default'  => ""
                        ),
                        array(
                            'id'       => 'googleplus_author',
                            'type'     => 'text',
                            'title'    => __( 'Google+ Username', 'swift-framework-admin' ),
                            'subtitle' => "Enter your Google+ username here, to be used for the authorship meta.",
                            'desc'     => '',
                            'default'  => ""
                        ),
                    ),
                );

                $this->sections[] = array(
                    'icon'       => 'el-icon-adjust-alt',
                    'title'      => __( 'Page Builder Options', 'swift-framework-admin' ),
                    'desc'       => 'These options configure the page builder.',
                    'subsection' => true,
                    'fields'     => array(
                        array(
                            'id'       => 'disable_spb',
                            'type'     => 'button_set',
                            'title'    => __( 'Disable Swift Page Builder', 'swift-framework-admin' ),
                            'subtitle' => __( 'Enable this option to disable the Swift Page Builder', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'Page Builder Disabled', '0' => 'Page Builder Enabled' ),
                            'default'  => '0'
                        ),
                        array(
                            'id'      => 'spb-post-types',
                            'type'    => 'select',
                            'data'    => 'post_types',
                            'multi'   => true,
                            'default' => array( 'page', 'post', 'portfolio', 'team', 'spb-section' ),
                            'title'   => __( 'Page Builder Post Types', 'swift-framework-admin' ),
                            'desc'    => __( 'Select here which post types you would like to enable the page builder for.', 'swift-framework-admin' ),
                        ),
                    ),
                );


                $this->sections[] = array(
                    'title'      => __( 'Plugin Options', 'swift-framework-admin' ),
                    'desc'       => '',
                    'subsection' => true,
                    'icon'       => 'el-icon-globe',
                    // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                    'fields'     => array(
                        array(
                            'id'       => 'disable_ss',
                            'type'     => 'button_set',
                            'title'    => __( 'Disable Swift Slider', 'swift-framework-admin' ),
                            'subtitle' => __( 'Enable this option to disable the Swift Slider Plugin', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'Swift Slider Disabled', '0' => 'Swift Slider Enabled' ),
                            'default'  => '0'
                        ),
                        array(
                            'id'   => 'plugin_divide_0',
                            'type' => 'divide'
                        ),
                        array(
                            'id'       => 'disable_loveit',
                            'type'     => 'button_set',
                            'title'    => __( 'Disable Love It', 'swift-framework-admin' ),
                            'subtitle' => __( 'Enable this option to disable the love it functionality within the theme.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'On', '0' => 'Off' ),
                            'default'  => '0'
                        ),
                        array(
                            'id'       => 'loveit_icon',
                            'type'     => 'text',
                            'title'    => __( 'LoveIt Icon', 'swift-framework-admin' ),
                            'subtitle' => __( 'Here you can set the icon that shows next to the loveit count of posts/portfolio items. This can be any icon from the FontAwesome or SS Gizmo icon sets.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'default'  => "ss-heart"
                        ),
                        array(
                            'id'       => 'loveit_text',
                            'type'     => 'text',
                            'title'    => __( 'LoveIt Text', 'swift-framework-admin' ),
                            'subtitle' => __( 'Here you can set the text to appear after the love it count. This will only appear on detail pages.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'default'  => "Likes"
                        ),
                        array(
                            'id'   => 'plugin_divide_1',
                            'type' => 'divide'
                        ),
                        array(
                            'id'       => 'disable_sfgallery',
                            'type'     => 'button_set',
                            'title'    => __( 'Disable Gallery Shortcode Override', 'swift-framework-admin' ),
                            'subtitle' => __( 'If you enable this option, then our WordPress gallery shortcode override will be disabled.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'On', '0' => 'Off' ),
                            'default'  => '0'
                        ),
                        array(
                            'id'   => 'plugin_divide_2',
                            'type' => 'divide'
                        ),
                        array(
                            'id'       => 'lightbox_nav',
                            'type'     => 'button_set',
                            'title'    => __( 'Lightbox Navigation', 'swift-framework-admin' ),
                            'subtitle' => __( 'Select the type of navigation you would like to use in the lightbox. The default option shows a section of the previous/next image to the left/right of the screen.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( 'default' => 'Default', 'arrows' => 'Arrows' ),
                            'default'  => 'default'
                        ),
                        array(
                            'id'       => 'lightbox_thumbs',
                            'type'     => 'button_set',
                            'title'    => __( 'Lightbox Thumbnails', 'swift-framework-admin' ),
                            'subtitle' => __( 'Select if you would like to display the gallery thumbnails in the lightbox or not.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'Enabled', '0' => 'Disabled' ),
                            'default'  => '1'
                        ),
                        array(
                            'id'       => 'lightbox_skin',
                            'type'     => 'button_set',
                            'title'    => __( 'Lightbox Skin', 'swift-framework-admin' ),
                            'subtitle' => __( 'Select the skin that you wish to use for the lightbox styling.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( 'light' => 'Light', 'dark' => 'Dark' ),
                            'default'  => 'light'
                        ),
                        array(
                            'id'       => 'lightbox_sharing',
                            'type'     => 'button_set',
                            'title'    => __( 'Lightbox Sharing', 'swift-framework-admin' ),
                            'subtitle' => __( 'Enable social sharing buttons on each lightbox image.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'On', '0' => 'Off' ),
                            'default'  => '1'
                        ),
                    )
                );

                $this->sections[] = array(
                    'title'      => __( 'Carousel Options', 'swift-framework-admin' ),
                    'desc'       => '',
                    'subsection' => true,
                    'icon'       => 'el-icon-resize-horizontal',
                    // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                    'fields'     => array(
                        array(
                            'id'      => 'carousel_paginationSpeed',
                            'type'    => 'slider',
                            'title'   => __( 'Pagination Speed (ms)', 'swift-framework-admin' ),
                            'desc'    => __( 'The speed in which the pagination transitions the carousel items. Default value: 800', 'swift-framework-admin' ),
                            "default" => "800",
                            "min"     => "0",
                            "step"    => "50",
                            "max"     => "5000",
                        ),
                        array(
                            'id'      => 'carousel_slideSpeed',
                            'type'    => 'slider',
                            'title'   => __( 'Slide Speed (ms)', 'swift-framework-admin' ),
                            'desc'    => __( 'The speed in which the carousel rotates. Default value: 200', 'swift-framework-admin' ),
                            "default" => "200",
                            "min"     => "0",
                            "step"    => "50",
                            "max"     => "3000",
                        ),
                        array(
                            'id'       => 'carousel_autoplay',
                            'type'     => 'button_set',
                            'title'    => __( 'Auto play', 'swift-framework-admin' ),
                            'subtitle' => __( "If you enable this option, then the carousels will auto rotate after 5 seconds.", 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'On', '0' => 'Off' ),
                            'default'  => '0'
                        ),
                        array(
                            'id'       => 'carousel_pagination',
                            'type'     => 'button_set',
                            'title'    => __( 'Show pagination', 'swift-framework-admin' ),
                            'subtitle' => __( "If you enable this option, then the carousels will display pagination dots below the carousel.", 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'On', '0' => 'Off' ),
                            'default'  => '1'
                        ),
                    )
                );

                $this->sections[] = array(
                    'title'      => __( 'Flexslider Options', 'swift-framework-admin' ),
                    'desc'       => '',
                    'subsection' => true,
                    'icon'       => 'el-icon-screen',
                    // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                    'fields'     => array(
                        array(
                            'id'      => 'slider_slideshowSpeed',
                            'type'    => 'slider',
                            'title'   => __( 'Slideshow Speed (ms)', 'swift-framework-admin' ),
                            'desc'    => __( 'The speed at which the slider rotates. Default value: 7000', 'swift-framework-admin' ),
                            "default" => "7000",
                            "min"     => "0",
                            "step"    => "50",
                            "max"     => "12000",
                        ),
                        array(
                            'id'      => 'slider_animationSpeed',
                            'type'    => 'slider',
                            'title'   => __( 'Slider Animation Speed (ms)', 'swift-framework-admin' ),
                            'desc'    => __( 'The speed in which the transition animation takes. Default value: 600', 'swift-framework-admin' ),
                            "default" => "600",
                            "min"     => "0",
                            "step"    => "50",
                            "max"     => "2000",
                        ),
                        array(
                            'id'       => 'slider_autoplay',
                            'type'     => 'button_set',
                            'title'    => __( 'Auto play', 'swift-framework-admin' ),
                            'subtitle' => __( "If you enable this option, then the sliders will auto rotate.", 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'On', '0' => 'Off' ),
                            'default'  => '0'
                        ),
                    )
                );


                $this->sections[] = array(
                    'type' => 'divide',
                );

                $this->sections[] = array(
                    'title'  => __( 'Custom CSS/JS', 'swift-framework-admin' ),
                    'desc'   => '',
                    'icon'   => 'el-icon-brush',
                    // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                    'fields' => array(
                        array(
                            'id'       => 'custom_css',
                            'type'     => 'ace_editor',
                            'mode'     => 'css',
                            'theme'    => 'monokai',
                            'title'    => __( 'Custom CSS', 'swift-framework-admin' ),
                            'subtitle' => __( 'Add some CSS to your theme by adding it to this textarea. Please do not include any style tags.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'default'  => ''
                        ),
                        array(
                            'id'       => 'custom_js',
                            'type'     => 'ace_editor',
                            'mode'     => 'javascript',
                            'theme'    => 'chrome',
                            'title'    => __( 'Custom JS', 'swift-framework-admin' ),
                            'subtitle' => __( 'Add some custom JavaScript to your theme by adding it to this textarea. Please do not include any script tags.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'default'  => ''
                        )
                    )
                );

                $this->sections[] = array(
                    'title'  => __( 'Colour Options', 'swift-framework-admin' ),
                    'desc'   => sprintf( __( 'To edit the colour options, please use the <a href="%s">Live Color Customizer</a>.', 'swift-framework-admin' ), admin_url( '/customize.php' ) ),
                    'icon'   => 'el-icon-adjust',
                    'fields' => array(
                        array(
                            'id'   => 'co_divide_1',
                            'type' => 'divide'
                        ),
                    )
                );

                if ( sf_is_current_color_settings_empty() ) {

                    $this->sections[] = array(
                        'icon'     => 'el-icon-eye-open',
                        'title'    => __( 'Colour Scheme Options', 'swift-framework-admin' ),
                        'subtitle' => __( '<p class="description">Create, import, and export color schemas.</p>', 'swift-framework-admin' ),
                        'fields'   => array(
                            array(
                                'id'       => 'colour_scheme_select_scheme',
                                'type'     => 'select',
                                'title'    => __( 'Select an existing color scheme to preview', 'swift-framework-admin' ),
                                'subtitle' => "",
                                'options'  => sf_get_color_scheme_list(),
                                'desc'     => '',
                                'default'  => sf_get_current_color_scheme_id()
                            ),
                            array(
                                'id'       => 'colour_scheme_import',
                                'type'     => 'upload_scheme',
                                'title'    => __( 'Import a Color Scheme', 'swift-framework-admin' ),
                                'subtitle' => __( 'File must be in csv format.', 'swift-framework-admin' )
                            ),
                            array(
                                'id'       => 'colour_scheme_export',
                                'type'     => 'raw',
                                'align'    => true,
                                'title'    => __( 'Export Current Settings As Schema', 'swift-framework-admin' ),
                                'subtitle' => __( 'Export the current live color scheme.', 'swift-framework-admin' ),
                                'content'  => sf_export_color_scheme_html()
                            ),
                            array(
                                'id'       => 'colour_scheme_preview',
                                'type'     => 'raw',
                                'align'    => true,
                                'title'    => __( 'Color Scheme Preview', 'swift-framework-admin' ),
                                'subtitle' => __( '<span id="scheme-preview-text">These colors are what currently exist in the WordPress theme customizer.</span>'
                                                  . '<div class="scheme-buttons" id="scheme-buttons">'
                                                  . '<input class="save-this-scheme-name" name="save-this-scheme-name" placeholder="Name This Scheme"   style="display:none;" />'
                                                  . '<a class="save-this-scheme button-secondary"   style="display:none;">Save This Scheme</a>'
                                                  . '<a class="delete-this-scheme button-secondary"  style="display:none;">Delete This Scheme</a>'
                                                  . '<a class="use-this-scheme button-secondary"  style="display:none;">Use This Scheme</a>'
                                                  . '</div>', 'swift-framework-admin' ),
                                'content'  => sf_get_current_color_scheme_html_preview()
                            )
                        )

                    );

                } else {

                    $this->sections[] = array(
                        'icon'     => 'el-icon-eye-open',
                        'title'    => __( 'Colour Scheme Options', 'swift-framework-admin' ),
                        'subtitle' => __( '<p class="description">Create, import, and export color schemas.</p>', 'swift-framework-admin' ),
                        'fields'   => array(
                            array(
                                'id'       => 'colour_scheme_select_scheme',
                                'type'     => 'select',
                                'title'    => __( 'Select an existing colour scheme to preview', 'swift-framework-admin' ),
                                'subtitle' => "",
                                'options'  => sf_get_color_scheme_list(),
                                'desc'     => '',
                                'default'  => sf_get_current_color_scheme_id()
                            ),
                            array(
                                'id'       => 'colour_scheme_import',
                                'type'     => 'upload_scheme',
                                'title'    => __( 'Import a Color Scheme', 'swift-framework-admin' ),
                                'subtitle' => __( 'File must be csv format.', 'swift-framework-admin' )
                            ),
                            array(
                                'id'       => 'colour_scheme_export',
                                'type'     => 'raw',
                                'align'    => true,
                                'title'    => __( 'Export Current Settings As Schema', 'swift-framework-admin' ),
                                'subtitle' => __( 'Export the current live color scheme.', 'swift-framework-admin' ),
                                'desc'     => sf_export_color_scheme_html()
                            ),
                            array(
                                'id'       => 'colour_scheme_preview',
                                'type'     => 'raw',
                                'align'    => true,
                                'title'    => __( 'Color Scheme Preview', 'swift-framework-admin' ),
                                'subtitle' => __( '<span id="scheme-preview-text">These colors are what currently exist in the WordPress theme customizer.</span>'
                                                  . '<div class="scheme-buttons" id="scheme-buttons">'
                                                  . '<input class="save-this-scheme-name" name="save-this-scheme-name" placeholder="Name This Scheme" />'
                                                  . '<a class="save-this-scheme button-secondary">Save This Scheme</a>'
                                                  . '<a class="delete-this-scheme button-secondary"  style="display:none;">Delete This Scheme</a>'
                                                  . '<a class="use-this-scheme button-secondary"  style="display:none;">Use This Scheme</a>'
                                                  . '</div>', 'swift-framework-admin' ),
                                'content'  => sf_get_current_color_scheme_html_preview()
                            )
                        )

                    );

                }

                $this->sections[] = array(
                    'title'  => __( 'Background Options', 'swift-framework-admin' ),
                    'desc'   => '',
                    'icon'   => 'el-icon-picture',
                    // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                    'fields' => array(
                        array(
                            'id'       => 'use_bg_image',
                            'type'     => 'button_set',
                            'title'    => __( 'Use Background Image', 'swift-framework-admin' ),
                            'subtitle' => __( 'Check this to use an image for the body background (boxed layout only).', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'On', '0' => 'Off' ),
                            'default'  => '0'
                        ),
                        array(
                            'id'       => 'custom_bg_image',
                            'type'     => 'media',
                            'url'      => true,
                            'required' => array( 'use_bg_image', '=', '1' ),
                            'title'    => __( 'Upload Background Image', 'swift-framework-admin' ),
                            'subtitle' => __( 'Either upload or provide a link to your own background here, or choose from the presets below.', 'swift-framework-admin' ),
                            'desc'     => ''
                        ),
                        array(
                            'id'       => 'bg_size',
                            'type'     => 'button_set',
                            'required' => array( 'use_bg_image', '=', '1' ),
                            'title'    => __( 'Background Size', 'swift-framework-admin' ),
                            'subtitle' => __( 'If you are using an image rather than a pattern, select cover to make the image cover the background.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( 'cover' => 'Cover', 'auto' => 'Auto' ),
                            'default'  => 'auto'
                        ),
                        array(
                            'id'       => 'preset_bg_image',
                            'type'     => 'image_select',
                            'required' => array( 'use_bg_image', '=', '1' ),
                            'title'    => __( 'Preset body background image', 'swift-framework-admin' ),
                            'subtitle' => __( 'Select a preset background image for the body background.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array(
                                $preset_bgs . '45degree_fabric.png'               => $preset_bgs . '45degree_fabric.png',
                                $preset_bgs . 'argyle.png'                        => $preset_bgs . 'argyle.png',
                                $preset_bgs . 'beige_paper.png'                   => $preset_bgs . 'beige_paper.png',
                                $preset_bgs . 'bgnoise_lg.png'                    => $preset_bgs . 'bgnoise_lg.png',
                                $preset_bgs . 'black_denim.png'                   => $preset_bgs . 'black_denim.png',
                                $preset_bgs . 'black_linen_v2.png'                => $preset_bgs . 'black_linen_v2.png',
                                $preset_bgs . 'black_paper.png'                   => $preset_bgs . 'black_paper.png',
                                $preset_bgs . 'black-Linen.png'                   => $preset_bgs . 'black-Linen.png',
                                $preset_bgs . 'blackmamba.png'                    => $preset_bgs . 'blackmamba.png',
                                $preset_bgs . 'blu_stripes.png'                   => $preset_bgs . 'blu_stripes.png',
                                $preset_bgs . 'bright_squares.png'                => $preset_bgs . 'bright_squares.png',
                                $preset_bgs . 'brushed_alu_dark.png'              => $preset_bgs . 'brushed_alu_dark.png',
                                $preset_bgs . 'brushed_alu.png'                   => $preset_bgs . 'brushed_alu.png',
                                $preset_bgs . 'candyhole.png'                     => $preset_bgs . 'candyhole.png',
                                $preset_bgs . 'checkered_pattern.png'             => $preset_bgs . 'checkered_pattern.png',
                                $preset_bgs . 'classy_fabric.png'                 => $preset_bgs . 'classy_fabric.png',
                                $preset_bgs . 'concrete_wall_3.png'               => $preset_bgs . 'concrete_wall_3.png',
                                $preset_bgs . 'connect.png'                       => $preset_bgs . 'connect.png',
                                $preset_bgs . 'cork_1.png'                        => $preset_bgs . 'cork_1.png',
                                $preset_bgs . 'crissXcross.png'                   => $preset_bgs . 'crissXcross.png',
                                $preset_bgs . 'dark_brick_wall.png'               => $preset_bgs . 'dark_brick_wall.png',
                                $preset_bgs . 'dark_dotted.png'                   => $preset_bgs . 'dark_dotted.png',
                                $preset_bgs . 'dark_geometric.png'                => $preset_bgs . 'dark_geometric.png',
                                $preset_bgs . 'dark_leather.png'                  => $preset_bgs . 'dark_leather.png',
                                $preset_bgs . 'dark_mosaic.png'                   => $preset_bgs . 'dark_mosaic.png',
                                $preset_bgs . 'dark_wood.png'                     => $preset_bgs . 'dark_wood.png',
                                $preset_bgs . 'detailed.png'                      => $preset_bgs . 'detailed.png',
                                $preset_bgs . 'diagonal-noise.png'                => $preset_bgs . 'diagonal-noise.png',
                                $preset_bgs . 'fabric_1.png'                      => $preset_bgs . 'fabric_1.png',
                                $preset_bgs . 'fake_luxury.png'                   => $preset_bgs . 'fake_luxury.png',
                                $preset_bgs . 'felt.png'                          => $preset_bgs . 'felt.png',
                                $preset_bgs . 'flowers.png'                       => $preset_bgs . 'flowers.png',
                                $preset_bgs . 'foggy_birds.png'                   => $preset_bgs . 'foggy_birds.png',
                                $preset_bgs . 'graphy.png'                        => $preset_bgs . 'graphy.png',
                                $preset_bgs . 'gray_sand.png'                     => $preset_bgs . 'gray_sand.png',
                                $preset_bgs . 'green_gobbler.png'                 => $preset_bgs . 'green_gobbler.png',
                                $preset_bgs . 'green-fibers.png'                  => $preset_bgs . 'green-fibers.png',
                                $preset_bgs . 'grid_noise.png'                    => $preset_bgs . 'grid_noise.png',
                                $preset_bgs . 'gridme.png'                        => $preset_bgs . 'gridme.png',
                                $preset_bgs . 'grilled.png'                       => $preset_bgs . 'grilled.png',
                                $preset_bgs . 'grunge_wall.png'                   => $preset_bgs . 'grunge_wall.png',
                                $preset_bgs . 'handmadepaper.png'                 => $preset_bgs . 'handmadepaper.png',
                                $preset_bgs . 'inflicted.png'                     => $preset_bgs . 'inflicted.png',
                                $preset_bgs . 'irongrip.png'                      => $preset_bgs . 'irongrip.png',
                                $preset_bgs . 'knitted-netting.png'               => $preset_bgs . 'knitted-netting.png',
                                $preset_bgs . 'leather_1.png'                     => $preset_bgs . 'leather_1.png',
                                $preset_bgs . 'light_alu.png'                     => $preset_bgs . 'light_alu.png',
                                $preset_bgs . 'light_checkered_tiles.png'         => $preset_bgs . 'light_checkered_tiles.png',
                                $preset_bgs . 'light_honeycomb.png'               => $preset_bgs . 'light_honeycomb.png',
                                $preset_bgs . 'lined_paper.png'                   => $preset_bgs . 'lined_paper.png',
                                $preset_bgs . 'little_pluses.png'                 => $preset_bgs . 'little_pluses.png',
                                $preset_bgs . 'mirrored_squares.png'              => $preset_bgs . 'mirrored_squares.png',
                                $preset_bgs . 'noise_pattern_with_crosslines.png' => $preset_bgs . 'noise_pattern_with_crosslines.png',
                                $preset_bgs . 'noisy.png'                         => $preset_bgs . 'noisy.png',
                                $preset_bgs . 'old_mathematics.png'               => $preset_bgs . 'old_mathematics.png',
                                $preset_bgs . 'padded.png'                        => $preset_bgs . 'padded.png',
                                $preset_bgs . 'paper_1.png'                       => $preset_bgs . 'paper_1.png',
                                $preset_bgs . 'paper_2.png'                       => $preset_bgs . 'paper_2.png',
                                $preset_bgs . 'paper_3.png'                       => $preset_bgs . 'paper_3.png',
                                $preset_bgs . 'pineapplecut.png'                  => $preset_bgs . 'pineapplecut.png',
                                $preset_bgs . 'pinstriped_suit.png'               => $preset_bgs . 'pinstriped_suit.png',
                                $preset_bgs . 'plaid.png'                         => $preset_bgs . 'plaid.png',
                                $preset_bgs . 'project_papper.png'                => $preset_bgs . 'project_papper.png',
                                $preset_bgs . 'px_by_Gre3g.png'                   => $preset_bgs . 'px_by_Gre3g.png',
                                $preset_bgs . 'quilt.png'                         => $preset_bgs . 'quilt.png',
                                $preset_bgs . 'random_grey_variations.png'        => $preset_bgs . 'random_grey_variations.png',
                                $preset_bgs . 'ravenna.png'                       => $preset_bgs . 'ravenna.png',
                                $preset_bgs . 'real_cf.png'                       => $preset_bgs . 'real_cf.png',
                                $preset_bgs . 'robots.png'                        => $preset_bgs . 'robots.png',
                                $preset_bgs . 'rockywall.png'                     => $preset_bgs . 'rockywall.png',
                                $preset_bgs . 'roughcloth.png'                    => $preset_bgs . 'roughcloth.png',
                                $preset_bgs . 'small-crackle-bright.png'          => $preset_bgs . 'small-crackle-bright.png',
                                $preset_bgs . 'smooth_wall.png'                   => $preset_bgs . 'smooth_wall.png',
                                $preset_bgs . 'snow.png'                          => $preset_bgs . 'snow.png',
                                $preset_bgs . 'soft_kill.png'                     => $preset_bgs . 'soft_kill.png',
                                $preset_bgs . 'square_bg.png'                     => $preset_bgs . 'square_bg.png',
                                $preset_bgs . 'starring.png'                      => $preset_bgs . 'starring.png',
                                $preset_bgs . 'stucco.png'                        => $preset_bgs . 'stucco.png',
                                $preset_bgs . 'subtle_freckles.png'               => $preset_bgs . 'subtle_freckles.png',
                                $preset_bgs . 'subtle_orange_emboss.png'          => $preset_bgs . 'subtle_orange_emboss.png',
                                $preset_bgs . 'subtle_zebra_3d.png'               => $preset_bgs . 'subtle_zebra_3d.png',
                                $preset_bgs . 'tileable_wood_texture.png'         => $preset_bgs . 'tileable_wood_texture.png',
                                $preset_bgs . 'type.png'                          => $preset_bgs . 'type.png',
                                $preset_bgs . 'vichy.png'                         => $preset_bgs . 'vichy.png',
                                $preset_bgs . 'washi.png'                         => $preset_bgs . 'washi.png',
                                $preset_bgs . 'white_sand.png'                    => $preset_bgs . 'white_sand.png',
                                $preset_bgs . 'white_texture.png'                 => $preset_bgs . 'white_texture.png',
                                $preset_bgs . 'whitediamond.png'                  => $preset_bgs . 'whitediamond.png',
                                $preset_bgs . 'whitey.png'                        => $preset_bgs . 'whitey.png',
                                $preset_bgs . 'woven.png'                         => $preset_bgs . 'woven.png',
                                $preset_bgs . 'xv.png'                            => $preset_bgs . 'xv.png'
                            ),
                            'default'  => ''
                        )
                    )
                );

                $this->sections[] = array(
                    'type' => 'divide',
                );

                $this->sections[] = array(
                    'title'  => __( 'Header Options', 'swift-framework-admin' ),
                    'desc'   => '',
                    'icon'   => 'el-icon-compass',
                    // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                    'fields' => array(
                        array(
                            'id'       => 'enable_tb',
                            'type'     => 'button_set',
                            'title'    => __( 'Enable Top Bar', 'swift-framework-admin' ),
                            'subtitle' => __( 'Enable top bar to show above header. This is only possible with headers 1-9 (not the vertical headers).', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'On', '0' => 'Off' ),
                            'default'  => '0'
                        ),
                        array(
                            'id'       => 'tb_left_config',
                            'type'     => 'select',
                            'required' => array( 'enable_tb', '=', '1' ),
                            'title'    => __( 'Top Bar Left Config', 'swift-framework-admin' ),
                            'subtitle' => "Choose the config for the left header area if you are using Header 1.",
                            'options'  => array(
                                'text'      => 'Text/Shortcode',
                                'aux-links' => 'Aux Links',
                                'social'    => 'Social Icons',
                                'menu'      => 'Top Bar Menu',
                            ),
                            'desc'     => '',
                            'default'  => 'text'
                        ),
                        array(
                            'id'       => 'tb_left_text',
                            'type'     => 'text',
                            'required' => array(
                                array( 'enable_tb', 'equals', 1 ),
                                array( 'tb_left_config', 'equals', "text" ),
                            ),
                            'title'    => __( 'Top Bar left text config', 'swift-framework-admin' ),
                            'subtitle' => "The text that is shown on the left of header on header type 1 when you have the left config above set to text. You can use any shortcodes here, or widgets (using the Widget Shortcode plugin).",
                            'desc'     => '',
                            'default'  => "Contact us on 0800 123 4567 or info@cardinal.com"
                        ),
                        array(
                            'id'       => 'tb_right_config',
                            'type'     => 'select',
                            'required' => array( 'enable_tb', '=', '1' ),
                            'title'    => __( 'Top Bar Right Config', 'swift-framework-admin' ),
                            'subtitle' => "Choose the config for the right header area if you are using Header 1 or 3.",
                            'options'  => array(
                                'text'      => 'Text/Shortcode',
                                'aux-links' => 'Aux Links',
                                'social'    => 'Social Icons',
                                'menu'      => 'Top Bar Menu',
                            ),
                            'desc'     => '',
                            'default'  => 'text'
                        ),
                        array(
                            'id'       => 'tb_right_text',
                            'type'     => 'text',
                            'required' => array(
                                array( 'enable_tb', 'equals', 1 ),
                                array( 'tb_right_config', 'equals', "text" ),
                            ),
                            'title'    => __( 'Top Bar right text config', 'swift-framework-admin' ),
                            'subtitle' => "The text that is shown on the left of header on header type 2 and type 3 when you have the right config above set to text. You can use any shortcodes here, or widgets (using the Widget Shortcode plugin).",
                            'desc'     => '',
                            'default'  => "Contact us on 0800 123 4567 or info@cardinal.com"
                        ),
                        array(
                            'id'   => 'header-divide',
                            'type' => 'divide'
                        ),
                        array(
                            'id'       => 'header_layout',
                            'type'     => 'image_select',
                            'title'    => __( 'Header Layout', 'swift-framework-admin' ),
                            'subtitle' => __( 'Select a header layout option from the examples.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array(
                                'header-1'          => array(
                                    'title' => '',
                                    'img'   => $template_directory . '/images/cardinal_header_1.png'
                                ),
                                'header-2'          => array(
                                    'title' => '',
                                    'img'   => $template_directory . '/images/cardinal_header_2.png'
                                ),
                                'header-3'          => array(
                                    'title' => '',
                                    'img'   => $template_directory . '/images/cardinal_header_3.png'
                                ),
                                'header-4'          => array(
                                    'title' => '',
                                    'img'   => $template_directory . '/images/cardinal_header_4.png'
                                ),
                                'header-5'          => array(
                                    'title' => '',
                                    'img'   => $template_directory . '/images/cardinal_header_5.png'
                                ),
                                'header-6'          => array(
                                    'title' => '',
                                    'img'   => $template_directory . '/images/cardinal_header_6.png'
                                ),
                                'header-7'          => array(
                                    'title' => '',
                                    'img'   => $template_directory . '/images/cardinal_header_7.png'
                                ),
                                'header-8'          => array(
                                    'title' => '',
                                    'img'   => $template_directory . '/images/cardinal_header_8.png'
                                ),
                                'header-9'          => array(
                                    'title' => '',
                                    'img'   => $template_directory . '/images/cardinal_header_9.png'
                                ),
                                'header-vert'       => array(
                                    'title' => '',
                                    'img'   => $template_directory . '/images/cardinal_header_vert.png'
                                ),
                                'header-vert-right' => array(
                                    'title' => '',
                                    'img'   => $template_directory . '/images/cardinal_header_vert_right.png'
                                )
                            ),
                            'default'  => 'header-7'
                        ),
                        array(
                            'id'       => 'fullwidth_header',
                            'type'     => 'button_set',
                            'title'    => __( 'Full width header', 'swift-framework-admin' ),
                            'subtitle' => __( 'If you are using Header 3, 4, 7 or 8 then you can optionally set the header to be edge to edge rather than contained.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'On', '0' => 'Off' ),
                            'default'  => '0'
                        ),
                        array(
                            'id'       => 'header_left_config',
                            'type'     => 'select',
                            'title'    => __( 'Header Left Config', 'swift-framework-admin' ),
                            'subtitle' => "Choose the config for the left header area if you are using Header 1.",
                            'options'  => array(
                                'text'         => 'Text/Shortcode',
                                'aux-links'    => 'Aux Links',
                                'social'       => 'Social Icons',
                                'overlay-menu' => 'Overlay Menu',
                                'contact'      => 'Contact',
                                'search'       => 'Search'
                            ),
                            'desc'     => '',
                            'default'  => 'text'
                        ),
                        array(
                            'id'       => 'header_left_text',
                            'type'     => 'text',
                            'required' => array( 'header_left_config', '=', 'text' ),
                            'title'    => __( 'Header left text config', 'swift-framework-admin' ),
                            'subtitle' => "The text that is shown on the left of header on header type 1 when you have the left config above set to text. You can use any shortcodes here, or widgets (using the Widget Shortcode plugin).",
                            'desc'     => '',
                            'default'  => "Contact us on 0800 123 4567 or info@cardinal.com"
                        ),
                        array(
                            'id'       => 'header_right_config',
                            'type'     => 'select',
                            'title'    => __( 'Header Right Config', 'swift-framework-admin' ),
                            'subtitle' => "Choose the config for the right header area if you are using Header 1 or 3.",
                            'options'  => array(
                                'text'         => 'Text/Shortcode',
                                'aux-links'    => 'Aux Links',
                                'social'       => 'Social Icons',
                                'overlay-menu' => 'Overlay Menu',
                                'contact'      => 'Contact',
                                'search'       => 'Search'
                            ),
                            'desc'     => '',
                            'default'  => 'text'
                        ),
                        array(
                            'id'       => 'header_right_text',
                            'type'     => 'text',
                            'required' => array( 'header_right_config', '=', 'text' ),
                            'title'    => __( 'Header right text config', 'swift-framework-admin' ),
                            'subtitle' => "The text that is shown on the left of header on header type 2 and type 3 when you have the right config above set to text. You can use any shortcodes here, or widgets (using the Widget Shortcode plugin).",
                            'desc'     => '',
                            'default'  => "Contact us on 0800 123 4567 or info@cardinal.com"
                        ),
                        array(
                            'id'       => 'contact_slideout_page',
                            'type'     => 'select',
                            'data'     => 'pages',
                            'title'    => __( 'Contact Slideout Page', 'swift-framework-admin' ),
                            'subtitle' => __( 'Select the page for which you would like to show the content of in the contact slideout. You can create a page using standard text, or the page builder - allowing for any kind of content in this slideout.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'default'  => '',
                            'args'     => array()
                        ),
                        array(
                            'id'   => 'header_divide_0',
                            'type' => 'divide'
                        ),
                        array(
                            'id'       => 'show_sub',
                            'type'     => 'button_set',
                            'title'    => __( 'Show subscribe aux option', 'swift-framework-admin' ),
                            'subtitle' => __( 'Check this to show the suscribe dropdown in the links output, allowing users to subscribe via inputting their email address. If you use this, be sure to enter a Mailchimp form action URL in the box below.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'On', '0' => 'Off' ),
                            'default'  => '0'
                        ),
                        array(
                            'id'       => 'show_translation',
                            'type'     => 'button_set',
                            'title'    => __( 'Show translation aux option', 'swift-framework-admin' ),
                            'subtitle' => __( 'Check this to show the translation dropdown in the links output.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'On', '0' => 'Off' ),
                            'default'  => '0'
                        ),
                        array(
                            'id'       => 'show_account',
                            'type'     => 'button_set',
                            'title'    => __( 'Show account aux option', 'swift-framework-admin' ),
                            'subtitle' => __( 'Check this to show the account sign in / my account in the links output.', 'swift-framework-admin' ),
                            'options'  => array( '1' => 'On', '0' => 'Off' ),
                            'default'  => '1'
                        ),
                        array(
                            'id'       => 'show_cart',
                            'type'     => 'button_set',
                            'title'    => __( 'Show cart aux option', 'swift-framework-admin' ),
                            'subtitle' => __( 'Check this to show the WooCommerce cart dropdown in the header.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'On', '0' => 'Off' ),
                            'default'  => '0'
                        ),
                        array(
                            'id'       => 'show_cart_count',
                            'type'     => 'button_set',
                            'title'    => __( 'Cart aux item count', 'swift-framework-admin' ),
                            'subtitle' => __( 'Enable this to include the item count within the cart.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'On', '0' => 'Off' ),
                            'default'  => '0'
                        ),
                        array(
                            'id'       => 'show_wishlist',
                            'type'     => 'button_set',
                            'title'    => __( 'Show wishlist aux option', 'swift-framework-admin' ),
                            'subtitle' => __( 'Check this to show the WooCommerce wishlist dropdown in the header. NOTE: You will need the YITH Wishlist plugin to be enabled.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'On', '0' => 'Off' ),
                            'default'  => '0'
                        ),
                        array(
                            'id'       => 'sub_code',
                            'type'     => 'textarea',
                            'title'    => __( 'Subscribe form code', 'swift-framework-admin' ),
                            'subtitle' => "Enter the form code (e.g. Mailchimp) that will be used for the subscribe dropdown. You can enter HTML/Shortcodes/Text here.",
                            'desc'     => '',
                            'default'  => ""
                        ),
                        array(
                            'id'       => 'enable_mini_header',
                            'type'     => 'button_set',
                            'title'    => __( 'Sticky header', 'swift-framework-admin' ),
                            'subtitle' => __( 'Enable the sticky header when scrolling down the page.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'On', '0' => 'Off' ),
                            'default'  => '1'
                        ),
                        array(
                            'id'       => 'enable_mini_header_resize',
                            'type'     => 'button_set',
                            'title'    => __( 'Sticky header resizing', 'swift-framework-admin' ),
                            'subtitle' => __( 'Enable the sticky header to resize when scrolling down the page.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'On', '0' => 'Off' ),
                            'default'  => '0'
                        ),
                        array(
                            'id'       => 'header_search_type',
                            'type'     => 'button_set',
                            'title'    => __( 'Header Search', 'swift-framework-admin' ),
                            'subtitle' => __( 'Enable the search icon in the header menu.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( 'search-on' => 'Search enabled', 'search-off' => 'Search disabled' ),
                            'default'  => 'search-on'
                        ),
                        array(
                            'id'       => 'header_search_pt',
                            'type'     => 'button_set',
                            'required' => array( 'header_search_type', '=', 'search-on' ),
                            'title'    => __( 'Header Search Post Type', 'swift-framework-admin' ),
                            'subtitle' => __( 'Set whether you would like the site search limited to products, or all content.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( 'any' => 'All', 'product' => 'Products' ),
                            'default'  => 'any'
                        ),
                        array(
                            'id'   => 'header_divide_1',
                            'type' => 'divide'
                        ),
                        array(
                            'id'       => 'vertical_header_text',
                            'type'     => 'editor',
                            'title'    => __( 'Vertical Header Copyright Text', 'swift-framework-admin' ),
                            'subtitle' => 'The copyright text that appears at the bottom of the vertical header. NOTE: this can include shortcodes.',
                            'desc'     => '',
                            'default'  => "&copy;[the-year] Cardinal &middot; Built with love by <a href='http://www.swiftideas.net'>Swift Ideas</a> using [wp-link]."
                        ),
                    ),
                );

                $this->sections[] = array(
                    'title'  => __( 'Logo Options', 'swift-framework-admin' ),
                    'desc'   => '',
                    'icon'   => 'el-icon-network',
                    // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                    'fields' => array(
                        array(
                            'id'       => 'logo_upload',
                            'type'     => 'media',
                            'url'      => false,
                            'title'    => __( 'Logo', 'swift-framework-admin' ),
                            'subtitle' => __( 'Upload your logo here (any size).', 'swift-framework-admin' ),
                            'desc'     => ''
                        ),
                        array(
                            'id'       => 'retina_logo_upload',
                            'type'     => 'media',
                            'url'      => false,
                            'title'    => __( 'Retina Logo', 'swift-framework-admin' ),
                            'subtitle' => __( 'Upload the retina version of your logo here.', 'swift-framework-admin' ),
                            'desc'     => ''
                        ),
                        array(
                            'id'       => 'alt_logo_upload',
                            'type'     => 'media',
                            'url'      => false,
                            'title'    => __( 'Alt Logo', 'swift-framework-admin' ),
                            'subtitle' => __( 'Upload an alternative version of your logo here, which can be optionally displayed instead of the standard logo on selected pages.', 'swift-framework-admin' ),
                            'desc'     => ''
                        ),
                        array(
                            'id'       => 'logo_maxheight',
                            'type'     => 'text',
                            'title'    => __( 'Logo Max Height', 'swift-framework-admin' ),
                            'subtitle' => __( 'This must be numeric (no px).', 'swift-framework-admin' ),
                            'desc'     => __( 'You can set a max height for the logo here, and this will resize it on the front end if your logo image is bigger.', 'swift-framework-admin' ),
                            'validate' => 'numeric',
                            'default'  => '100',
                        ),
                        array(
                            'id'       => 'logo_padding',
                            'type'     => 'text',
                            'title'    => __( 'Logo Top/Bottom Padding', 'swift-framework-admin' ),
                            'subtitle' => __( 'This must be numeric (no px). Leave balnk for default.', 'swift-framework-admin' ),
                            'desc'     => __( 'If you would like to override the default logo top/bottom padding, then you can do so here. The default is 30 if the logo height is less than 80, else it is 20.', 'swift-framework-admin' ),
                            'validate' => 'numeric',
                            'default'  => '',
                        ),
                        array(
                            'id'       => 'enable_logo_tagline',
                            'type'     => 'button_set',
                            'title'    => __( 'Enable Logo Tagline', 'swift-framework-admin' ),
                            'subtitle' => __( 'Enable the site tagline to appear under the logo.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'Yes', '0' => 'No' ),
                            'default'  => '0'
                        ),
                        array(
                            'id'          => 'logo_font',
                            'type'        => 'typography',
                            'title'       => __( 'Logo Font', 'swift-framework-admin' ),
                            'subtitle'    => __( 'Specify the logo font properties.', 'swift-framework-admin' ),
                            'google'      => true,
                            'font-backup' => true,
                            'line-height' => false,
                            'all_styles'  => true,
                            // Enable all Google Font style/weight variations to be added to the page
                            'output'      => array( '#logo h1, #logo h2, #mobile-logo h1' ),
                            // An array of CSS selectors to apply this font style to dynamically
                            'compiler'    => array( '#logo h1, #logo h2, #mobile-logo h1' ),
                            // An array of CSS selectors to apply this font style to dynamically
                            'units'       => 'px',
                            // Defaults to px
                            'default'     => array(
                                'color'       => '#222',
                                'font-size'   => '24px',
                                'font-family' => 'Lato',
                                'font-weight' => '400',
                            ),
                        ),
                    )
                );

                $this->sections[] = array(
                    'title'  => __( 'Mobile Header Options', 'swift-framework-admin' ),
                    'desc'   => '',
                    'icon'   => 'el-icon-iphone-home',
                    // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                    'fields' => array(
                        array(
                            'id'       => 'mobile_header_shown',
                            'type'     => 'select',
                            'title'    => __( 'Mobile Header Visiblity', 'swift-framework-admin' ),
                            'subtitle' => __( 'Select at what screen size the main header is replaced by the mobile header.', 'swift-framework-admin' ),
                            'options'  => array(
                                'tablet-land' => 'Tablet (Landscape)',
                                'tablet-port' => 'Tablet (Portrait)',
                                'mobile'      => 'Mobile',
                            ),
                            'desc'     => '',
                            'default'  => 'tablet-land'
                        ),
                        array(
                            'id'       => 'mobile_header_sticky',
                            'type'     => 'button_set',
                            'title'    => __( 'Sticky Mobile Header', 'swift-framework-admin' ),
                            'subtitle' => __( 'Check this to enable sticky functionality on the mobile header.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'On', '0' => 'Off' ),
                            'default'  => '0'
                        ),
                        array(
                            'id'       => 'mobile_header_layout',
                            'type'     => 'select',
                            'title'    => __( 'Mobile Header Layout', 'swift-framework-admin' ),
                            'subtitle' => __( 'Choose the config for the layout of the mobile header.', 'swift-framework-admin' ),
                            'options'  => array(
                                'left-logo'       => 'Left Logo',
                                'right-logo'      => 'Right Logo',
                                'center-logo'     => 'Center Logo (Menu Left, Cart Right)',
                                'center-logo-alt' => 'Center Logo (Cart Left, Menu Right)',
                            ),
                            'desc'     => '',
                            'default'  => 'left-logo'
                        ),
                        array(
                            'id'       => 'mobile_menu_type',
                            'type'     => 'select',
                            'title'    => __( 'Mobile Menu Display Type', 'swift-framework-admin' ),
                            'subtitle' => __( 'Choose the display type for the mobile menu/cart.', 'swift-framework-admin' ),
                            'options'  => array(
                                'slideout' => 'Slideout',
                                'overlay'  => 'Overlay',
                            ),
                            'desc'     => '',
                            'default'  => 'slideout'
                        ),
                        array(
                            'id'       => 'mobile_top_text',
                            'type'     => 'text',
                            'title'    => __( 'Mobile Top Bar Text', 'swift-framework-admin' ),
                            'subtitle' => "The text that is shown above the mobile header, ideal for phone number, email, or social icons placement. You can use shortcodes or text here.",
                            'desc'     => 'This is optional, leave it blank to hide it on the frontend.',
                            'default'  => ""
                        ),
                        array(
                            'id'       => 'mobile_show_search',
                            'type'     => 'button_set',
                            'title'    => __( 'Show search box', 'swift-framework-admin' ),
                            'subtitle' => __( 'Check this to show the search box in the mobile menu panel.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'On', '0' => 'Off' ),
                            'default'  => '0'
                        ),
                        array(
                            'id'       => 'mobile_show_translation',
                            'type'     => 'button_set',
                            'title'    => __( 'Show translation options', 'swift-framework-admin' ),
                            'subtitle' => __( 'Check this to show the translation options in the mobile menu panel. NOTE: the WPML plugin is required for this.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'On', '0' => 'Off' ),
                            'default'  => '0'
                        ),
                        array(
                            'id'       => 'mobile_show_cart',
                            'type'     => 'button_set',
                            'title'    => __( 'Show cart', 'swift-framework-admin' ),
                            'subtitle' => __( 'Check this to show the cart icon and cart panel in the mobile header.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'On', '0' => 'Off' ),
                            'default'  => '0'
                        ),
                        array(
                            'id'       => 'mobile_show_account',
                            'type'     => 'button_set',
                            'title'    => __( 'Show account options', 'swift-framework-admin' ),
                            'subtitle' => __( 'Check this to show the account sign in / my account in the mobile cart panel.', 'swift-framework-admin' ),
                            'options'  => array( '1' => 'On', '0' => 'Off' ),
                            'default'  => '1'
                        ),
                    )
                );

                $this->sections[] = array(
                    'type' => 'divide',
                );

                $this->sections[] = array(
                    'icon'   => 'el-icon-website',
                    'title'  => __( 'Footer Options', 'swift-framework-admin' ),
                    'fields' => array(
                        array(
                            'id'       => 'enable_footer',
                            'type'     => 'button_set',
                            'title'    => __( 'Enable Footer', 'swift-framework-admin' ),
                            'subtitle' => __( 'Enable the footer widgets section.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'On', '0' => 'Off' ),
                            'default'  => '1'
                        ),
                        array(
                            'id'       => 'enable_footer_divider',
                            'type'     => 'button_set',
                            'required' => array( 'enable_footer', '=', '1' ),
                            'title'    => __( 'Footer Divider', 'swift-framework-admin' ),
                            'subtitle' => __( 'Enable the footer divider above the footer.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'On', '0' => 'Off' ),
                            'default'  => '1'
                        ),
                        array(
                            'id'       => 'footer_layout',
                            'type'     => 'image_select',
                            'required' => array( 'enable_footer', '=', '1' ),
                            'title'    => __( 'Footer Layout', 'swift-framework-admin' ),
                            'subtitle' => __( 'Select the footer column layout.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array(
                                'footer-1' => array(
                                    'title' => '',
                                    'img'   => $template_directory . '/images/footer-1.png'
                                ),
                                'footer-2' => array(
                                    'title' => '',
                                    'img'   => $template_directory . '/images/footer-2.png'
                                ),
                                'footer-3' => array(
                                    'title' => '',
                                    'img'   => $template_directory . '/images/footer-3.png'
                                ),
                                'footer-4' => array(
                                    'title' => '',
                                    'img'   => $template_directory . '/images/footer-4.png'
                                ),
                                'footer-5' => array(
                                    'title' => '',
                                    'img'   => $template_directory . '/images/footer-5.png'
                                ),
                                'footer-6' => array(
                                    'title' => '',
                                    'img'   => $template_directory . '/images/footer-6.png'
                                ),
                                'footer-7' => array(
                                    'title' => '',
                                    'img'   => $template_directory . '/images/footer-7.png'
                                ),
                                'footer-8' => array(
                                    'title' => '',
                                    'img'   => $template_directory . '/images/footer-8.png'
                                ),
                                'footer-9' => array(
                                    'title' => '',
                                    'img'   => $template_directory . '/images/footer-9.png'
                                ),
                            ),
                            'default'  => 'footer-1'
                        ),
                        array(
                            'id'   => 'footer_divide_0',
                            'type' => 'divide'
                        ),
                        array(
                            'id'       => 'enable_copyright',
                            'type'     => 'button_set',
                            'title'    => __( 'Enable Copyright', 'swift-framework-admin' ),
                            'subtitle' => __( 'Enable the footer copyright section.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'On', '0' => 'Off' ),
                            'default'  => '1'
                        ),
                        array(
                            'id'       => 'enable_copyright_divider',
                            'type'     => 'button_set',
                            'required' => array( 'enable_copyright', '=', '1' ),
                            'title'    => __( 'Copyright Divider', 'swift-framework-admin' ),
                            'subtitle' => __( 'Enable the copyright divider above the copyright.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'On', '0' => 'Off' ),
                            'default'  => '1'
                        ),
                        array(
                            'id'       => 'footer_copyright_text',
                            'type'     => 'editor',
                            'required' => array( 'enable_copyright', '=', '1' ),
                            'title'    => __( 'Footer Copyright Text', 'swift-framework-admin' ),
                            'subtitle' => 'The copyright text that appears in the footer.',
                            'desc'     => '',
                            'default'  => "&copy;[the-year] Cardinal &middot; Built with love by <a href='http://www.swiftideas.net'>Swift Ideas</a> using [wp-link]."
                        ),
                        array(
                            'id'       => 'copyright_right',
                            'type'     => 'button_set',
                            'required' => array( 'enable_copyright', '=', '1' ),
                            'title'    => __( 'Copyright Right Setup', 'swift-framework-admin' ),
                            'subtitle' => __( 'Choose if you would like to show a menu or text on the right of the copyright area.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( 'text' => 'Text', 'menu' => 'Menu' ),
                            'default'  => 'menu'
                        ),
                        array(
                            'id'       => 'footer_copyright_text_right',
                            'type'     => 'editor',
                            'required' => array(
                                array( 'enable_copyright', 'equals', '1' ),
                                array( 'copyright_right', 'equals', 'text' ),
                            ),
                            'title'    => __( 'Footer Copyright Right Text', 'swift-framework-admin' ),
                            'subtitle' => 'The copyright text that appears in the footer.',
                            'desc'     => '',
                            'default'  => "&copy;[the-year] Cardinal &middot; Built with love by <a href='http://www.swiftideas.net'>Swift Ideas</a> using [wp-link]."
                        ),
                        array(
                            'id'       => 'show_backlink',
                            'type'     => 'button_set',
                            'required' => array( 'enable_copyright', '=', '1' ),
                            'title'    => __( 'Show Swift Ideas Backlink', 'swift-framework-admin' ),
                            'subtitle' => __( 'If checked, a backlink to our site will be shown in the footer. This is not compulsory, but always appreciated!', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'On', '0' => 'Off' ),
                            'default'  => '1'
                        ),
                    ),
                );

                $this->sections[] = array(
                    'type' => 'divide',
                );

                $this->sections[] = array(
                    'icon'   => 'el-icon-search',
                    'title'  => __( 'Super Search Options', 'swift-framework-admin' ),
                    'fields' => array(
                        array(
                            'id'       => 'ss_enable',
                            'type'     => 'button_set',
                            'title'    => __( 'Enable Super Search', 'swift-framework-admin' ),
                            'subtitle' => __( 'If enabled, the super search option will be included on the page. You will also need to choose the option below.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'On', '0' => 'Off' ),
                            'default'  => '1'
                        ),
                        array(
                            'id'       => 'ss_mobile',
                            'type'     => 'button_set',
                            'title'    => __( 'Enable Super Search on Mobile', 'swift-framework-admin' ),
                            'subtitle' => __( 'If enabled, the super search option will show at the top of the page on mobile devices.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'On', '0' => 'Off' ),
                            'default'  => '1'
                        ),
                        array(
                            'id'       => 'field1_text',
                            'type'     => 'text',
                            'title'    => __( 'Field 1 Text', 'swift-framework-admin' ),
                            'subtitle' => 'The text that precedes the first dropdown select.',
                            'desc'     => '',
                            'default'  => "I'm looking for"
                        ),
                        array(
                            'id'       => 'field1_filter',
                            'type'     => 'select',
                            'title'    => __( 'Field 1 Filter', 'swift-framework-admin' ),
                            'subtitle' => "The first filter in the search text, shows after field 1 text.",
                            'options'  => sf_get_woo_product_filters_array(),
                            'desc'     => '',
                            'default'  => 'product_cat'
                        ),
                        array(
                            'id'       => 'field1_default_text',
                            'type'     => 'text',
                            'title'    => __( 'Field 1 Label', 'swift-framework-admin' ),
                            'subtitle' => 'The default label text for the field 1 filter.',
                            'desc'     => '',
                            'default'  => "product"
                        ),
                        array(
                            'id'   => 'ss_divide_0',
                            'type' => 'divide'
                        ),
                        array(
                            'id'       => 'field2_text',
                            'type'     => 'text',
                            'title'    => __( 'Field 2 Text', 'swift-framework-admin' ),
                            'subtitle' => 'The text that precedes the second dropdown select.',
                            'desc'     => '',
                            'default'  => "in a size"
                        ),
                        array(
                            'id'       => 'field2_filter',
                            'type'     => 'select',
                            'title'    => __( 'Field 2 Filter', 'swift-framework-admin' ),
                            'subtitle' => "The second filter in the search text, shows after field 2 text.",
                            'options'  => sf_get_woo_product_filters_array(),
                            'desc'     => '',
                            'default'  => 'product_cat'
                        ),
                        array(
                            'id'       => 'field2_default_text',
                            'type'     => 'text',
                            'title'    => __( 'Field 2 Label', 'swift-framework-admin' ),
                            'subtitle' => 'The default label text for the field 2 filter.',
                            'desc'     => '',
                            'default'  => "size"
                        ),
                        array(
                            'id'   => 'ss_divide_1',
                            'type' => 'divide'
                        ),
                        array(
                            'id'       => 'field3_text',
                            'type'     => 'text',
                            'title'    => __( 'Field 3 Text', 'swift-framework-admin' ),
                            'subtitle' => 'The text that precedes the third dropdown select.',
                            'desc'     => '',
                            'default'  => ". Show me the"
                        ),
                        array(
                            'id'       => 'field3_filter',
                            'type'     => 'select',
                            'title'    => __( 'Field 3 Filter', 'swift-framework-admin' ),
                            'subtitle' => "The third filter in the search text, shows after field 3 text.",
                            'options'  => sf_get_woo_product_filters_array(),
                            'desc'     => '',
                            'default'  => 'product_cat'
                        ),
                        array(
                            'id'       => 'field3_default_text',
                            'type'     => 'text',
                            'title'    => __( 'Field 3 Label', 'swift-framework-admin' ),
                            'subtitle' => 'The default label text for the field 3 filter.',
                            'desc'     => '',
                            'default'  => "colour"
                        ),
                        array(
                            'id'   => 'ss_divide_2',
                            'type' => 'divide'
                        ),
                        array(
                            'id'       => 'field4_text',
                            'type'     => 'text',
                            'title'    => __( 'Field 4 Text', 'swift-framework-admin' ),
                            'subtitle' => 'The text that precedes the fourth dropdown select.',
                            'desc'     => '',
                            'default'  => ""
                        ),
                        array(
                            'id'       => 'field4_filter',
                            'type'     => 'select',
                            'title'    => __( 'Field 4 Filter', 'swift-framework-admin' ),
                            'subtitle' => "The fourth filter in the search text, shows after field 4 text.",
                            'options'  => sf_get_woo_product_filters_array(),
                            'desc'     => '',
                            'default'  => 'product_cat'
                        ),
                        array(
                            'id'       => 'field4_default_text',
                            'type'     => 'text',
                            'title'    => __( 'Field 4 Label', 'swift-framework-admin' ),
                            'subtitle' => 'The default label text for the field 4 filter.',
                            'desc'     => '',
                            'default'  => ""
                        ),
                        array(
                            'id'   => 'ss_divide_3',
                            'type' => 'divide'
                        ),
                        array(
                            'id'       => 'field5_text',
                            'type'     => 'text',
                            'title'    => __( 'Field 5 Text', 'swift-framework-admin' ),
                            'subtitle' => 'The text that precedes the fifth dropdown select.',
                            'desc'     => '',
                            'default'  => ""
                        ),
                        array(
                            'id'       => 'field5_filter',
                            'type'     => 'select',
                            'title'    => __( 'Field 5 Filter', 'swift-framework-admin' ),
                            'subtitle' => "The fifth filter in the search text, shows after field 5 text.",
                            'options'  => sf_get_woo_product_filters_array(),
                            'desc'     => '',
                            'default'  => 'product_cat'
                        ),
                        array(
                            'id'       => 'field5_default_text',
                            'type'     => 'text',
                            'title'    => __( 'Field 5 Label', 'swift-framework-admin' ),
                            'subtitle' => 'The default label text for the field 5 filter.',
                            'desc'     => '',
                            'default'  => ""
                        ),
                        array(
                            'id'   => 'ss_divide_4',
                            'type' => 'divide'
                        ),
                        array(
                            'id'       => 'field6_text',
                            'type'     => 'text',
                            'title'    => __( 'Field 6 Text', 'swift-framework-admin' ),
                            'subtitle' => 'The text that precedes the sixth dropdown select.',
                            'desc'     => '',
                            'default'  => ""
                        ),
                        array(
                            'id'       => 'field6_filter',
                            'type'     => 'select',
                            'title'    => __( 'Field 6 Filter', 'swift-framework-admin' ),
                            'subtitle' => "The sixth filter in the search text, shows after field 6 text.",
                            'options'  => sf_get_woo_product_filters_array(),
                            'desc'     => '',
                            'default'  => 'product_cat'
                        ),
                        array(
                            'id'       => 'field6_default_text',
                            'type'     => 'text',
                            'title'    => __( 'Field 6 Label', 'swift-framework-admin' ),
                            'subtitle' => 'The default label text for the field 6 filter.',
                            'desc'     => '',
                            'default'  => ""
                        ),
                        array(
                            'id'   => 'ss_divide_5',
                            'type' => 'divide'
                        ),
                        array(
                            'id'       => 'ss_final_text',
                            'type'     => 'text',
                            'title'    => __( 'Final Text', 'swift-framework-admin' ),
                            'subtitle' => 'The text that appears after the last filter.',
                            'desc'     => '',
                            'default'  => "items."
                        ),
                        array(
                            'id'       => 'ss_button_text',
                            'type'     => 'text',
                            'title'    => __( 'Super Search Button Text', 'swift-framework-admin' ),
                            'subtitle' => 'The text for the super search button.',
                            'desc'     => '',
                            'default'  => "Super Search"
                        ),
                    ),
                );

                $this->sections[] = array(
                    'icon'   => 'el-icon-bullhorn',
                    'title'  => __( 'Promo Bar Options', 'swift-framework-admin' ),
                    'fields' => array(
                        array(
                            'id'       => 'enable_footer_promo_bar',
                            'type'     => 'button_set',
                            'title'    => __( 'Enable Promo Bar', 'swift-framework-admin' ),
                            'subtitle' => __( 'Enable the sitewide promo bar at the bottom of the page.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'On', '0' => 'Off' ),
                            'default'  => '0'
                        ),
                        array(
                            'id'       => 'footer_promo_bar_type',
                            'type'     => 'button_set',
                            'required' => array( 'enable_footer_promo_bar', '=', '1' ),
                            'title'    => __( 'Promo Bar Type', 'swift-framework-admin' ),
                            'subtitle' => __( 'Select the type for the promo bar.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array(
                                'button' => 'Text + Button',
                                'arrow'  => 'Text + Arrow (Full Bar Link)',
                                'text'   => 'Text Only (Full Bar Link)'
                            ),
                            'default'  => 'button'
                        ),
                        array(
                            'id'       => 'footer_promo_bar_text',
                            'type'     => 'text',
                            'required' => array( 'enable_footer_promo_bar', '=', '1' ),
                            'title'    => __( 'Promo Bar Text', 'swift-framework-admin' ),
                            'subtitle' => 'Enter the text for the promo bar here.',
                            'desc'     => '',
                            'default'  => 'Enter your promo bar text here.'
                        ),
                        array(
                            'id'       => 'footer_promo_bar_button_color',
                            'type'     => 'select',
                            'required' => array( 'enable_footer_promo_bar', '=', '1' ),
                            'title'    => __( 'Promo Bar Button Color', 'swift-framework-admin' ),
                            'subtitle' => "Choose the color for the promo bar button.",
                            'options'  => array(
                                'accent'            => 'Accent',
                                'black'             => 'Black',
                                'white'             => 'White',
                                'grey'              => 'Grey',
                                'lightgrey'         => 'Light Grey',
                                'gold'              => 'Gold',
                                'lightblue'         => 'Light Blue',
                                'green'             => 'Green',
                                'gold'              => 'Gold',
                                'turquoise'         => 'Turquoise',
                                'pink'              => 'Pink',
                                'orange'            => 'Orange',
                                'turquoise'         => 'Turquoise',
                                'transparent-light' => 'Transparent - Light',
                                'transparent-dark'  => 'Transparent - Dark',
                            ),
                            'desc'     => '',
                            'default'  => 'accent'
                        ),
                        array(
                            'id'       => 'footer_promo_bar_button_text',
                            'type'     => 'text',
                            'required' => array( 'enable_footer_promo_bar', '=', '1' ),
                            'title'    => __( 'Promo Bar Button Text', 'swift-framework-admin' ),
                            'subtitle' => 'Enter the text for the promo bar button here, if you have the Text + Button type selected.',
                            'desc'     => '',
                            'default'  => 'Button Text.'
                        ),
                        array(
                            'id'       => 'footer_promo_bar_button_link',
                            'type'     => 'text',
                            'required' => array( 'enable_footer_promo_bar', '=', '1' ),
                            'title'    => __( 'Promo Bar Button Link', 'swift-framework-admin' ),
                            'subtitle' => 'Enter the link for the promo bar button here, if you have the Text + Button or Text + Arrow Button type selected.',
                            'desc'     => '',
                            'default'  => 'http://'
                        ),
                        array(
                            'id'       => 'footer_promo_bar_button_target',
                            'type'     => 'button_set',
                            'required' => array( 'enable_footer_promo_bar', '=', '1' ),
                            'title'    => __( 'Promo Bar Button Target', 'swift-framework-admin' ),
                            'subtitle' => __( 'Select the target for the promo bar link.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '_self' => 'Same Window', '_blank' => 'New Window' ),
                            'default'  => '_self'
                        ),
                    ),
                );

                $this->sections[] = array(
                    'icon'   => 'el-icon-star',
                    'title'  => __( 'Review Options', 'swift-framework-admin' ),
                    'fields' => array(
                        array(
                            'id'       => 'review_format',
                            'type'     => 'select',
                            'title'    => __( 'Review Point Format', 'swift-framework-admin' ),
                            'sub_desc' => "Choose the review point format.",
                            'options'  => array(
                                'percentage' => 'Percentage (0-100%)',
                                'points'     => 'Points (0-10)'
                            ),
                            'desc'     => '',
                            'std'      => 'percentage'
                        ),
                        array(
                            'id'       => 'review_cat_1',
                            'type'     => 'text',
                            'title'    => __( 'Default Review Category 1', 'swift-framework-admin' ),
                            'sub_desc' => "Set the default name for review category 1",
                            'desc'     => '',
                            'std'      => ''
                        ),
                        array(
                            'id'       => 'review_cat_2',
                            'type'     => 'text',
                            'title'    => __( 'Default Review Category 2', 'swift-framework-admin' ),
                            'sub_desc' => "Set the default name for review category 2",
                            'desc'     => '',
                            'std'      => ''
                        ),
                        array(
                            'id'       => 'review_cat_3',
                            'type'     => 'text',
                            'title'    => __( 'Default Review Category 3', 'swift-framework-admin' ),
                            'sub_desc' => "Set the default name for review category 3",
                            'desc'     => '',
                            'std'      => ''
                        ),
                        array(
                            'id'       => 'review_cat_4',
                            'type'     => 'text',
                            'title'    => __( 'Default Review Category 4', 'swift-framework-admin' ),
                            'sub_desc' => "Set the default name for review category 4",
                            'desc'     => '',
                            'std'      => ''
                        )
                    ),
                );

                $this->sections[] = array(
                    'type' => 'divide',
                );

                $this->sections[] = array(
                    'icon'   => 'el-icon-fontsize',
                    'title'  => __( 'Font Options', 'swift-framework-admin' ),
                    'fields' => array(
                        array(
                            'id'             => 'body_font',
                            'type'           => 'typography',
                            'title'          => __( 'Body Font', 'swift-framework-admin' ),
                            'subtitle'       => __( 'Specify the body font properties.', 'swift-framework-admin' ),
                            'google'         => true,
                            'font-backup'    => true,
                            'text-align'     => false,
                            'color'          => false,
                            'letter-spacing' => true,
                            'all_styles'     => true,
                            // Enable all Google Font style/weight variations to be added to the page
                            'output'         => array( 'body,p' ),
                            // An array of CSS selectors to apply this font style to dynamically
                            'compiler'       => array( 'body,p' ),
                            // An array of CSS selectors to apply this font style to dynamically
                            'units'          => 'px',
                            // Defaults to px
                            'default'        => array(
                                'font-size'   => '14px',
                                'line-height' => '20px',
                                'font-family' => 'Source Sans Pro',
                                'font-weight' => '400',
                            ),
                        ),
                        array(
                            'id'             => 'h1_font',
                            'type'           => 'typography',
                            'title'          => __( 'H1 Font', 'swift-framework-admin' ),
                            'subtitle'       => __( 'Specify the H1 font properties.', 'swift-framework-admin' ),
                            'google'         => true,
                            'font-backup'    => true,
                            'text-align'     => false,
                            'color'          => false,
                            'letter-spacing' => true,
                            'all_styles'     => true,
                            // Enable all Google Font style/weight variations to be added to the page
                            'output'         => array( 'h1,.impact-text,.impact-text-large' ),
                            // An array of CSS selectors to apply this font style to dynamically
                            'compiler'       => array( 'h1,.impact-text,.impact-text-large' ),
                            // An array of CSS selectors to apply this font style to dynamically
                            'units'          => 'px',
                            // Defaults to px
                            'default'        => array(
                                'font-size'   => '24px',
                                'line-height' => '34px',
                                'font-family' => 'Lato',
                                'font-weight' => '400',
                            ),
                        ),
                        array(
                            'id'             => 'h2_font',
                            'type'           => 'typography',
                            'title'          => __( 'H2 Font', 'swift-framework-admin' ),
                            'subtitle'       => __( 'Specify the H2 font properties.', 'swift-framework-admin' ),
                            'google'         => true,
                            'font-backup'    => true,
                            'text-align'     => false,
                            'color'          => false,
                            'letter-spacing' => true,
                            'all_styles'     => true,
                            // Enable all Google Font style/weight variations to be added to the page
                            'output'         => array( 'h2,.blog-item .quote-excerpt' ),
                            // An array of CSS selectors to apply this font style to dynamically
                            'compiler'       => array( 'h2,.blog-item .quote-excerpt' ),
                            // An array of CSS selectors to apply this font style to dynamically
                            'units'          => 'px',
                            // Defaults to px
                            'default'        => array(
                                'font-size'   => '20px',
                                'line-height' => '30px',
                                'font-family' => 'Lato',
                                'font-weight' => '400',
                            ),
                        ),
                        array(
                            'id'             => 'h3_font',
                            'type'           => 'typography',
                            'title'          => __( 'H3 Font', 'swift-framework-admin' ),
                            'subtitle'       => __( 'Specify the H3 font properties.', 'swift-framework-admin' ),
                            'google'         => true,
                            'font-backup'    => true,
                            'text-align'     => false,
                            'color'          => false,
                            'letter-spacing' => true,
                            'all_styles'     => true,
                            // Enable all Google Font style/weight variations to be added to the page
                            'output'         => array( 'h3' ),
                            // An array of CSS selectors to apply this font style to dynamically
                            'compiler'       => array( 'h3' ),
                            // An array of CSS selectors to apply this font style to dynamically
                            'units'          => 'px',
                            // Defaults to px
                            'default'        => array(
                                'font-size'   => '18px',
                                'line-height' => '24px',
                                'font-family' => 'Lato',
                                'font-weight' => '400',
                            ),
                        ),
                        array(
                            'id'             => 'h4_font',
                            'type'           => 'typography',
                            'title'          => __( 'H4 Font', 'swift-framework-admin' ),
                            'subtitle'       => __( 'Specify the H4 font properties.', 'swift-framework-admin' ),
                            'google'         => true,
                            'font-backup'    => true,
                            'text-align'     => false,
                            'color'          => false,
                            'letter-spacing' => true,
                            'all_styles'     => true,
                            // Enable all Google Font style/weight variations to be added to the page
                            'output'         => array( 'h4' ),
                            // An array of CSS selectors to apply this font style to dynamically
                            'compiler'       => array( 'h4' ),
                            // An array of CSS selectors to apply this font style to dynamically
                            'units'          => 'px',
                            // Defaults to px
                            'default'        => array(
                                'font-size'   => '16px',
                                'line-height' => '20px',
                                'font-family' => 'Lato',
                                'font-weight' => '400',
                            ),
                        ),
                        array(
                            'id'             => 'h5_font',
                            'type'           => 'typography',
                            'title'          => __( 'H5 Font', 'swift-framework-admin' ),
                            'subtitle'       => __( 'Specify the H5 font properties.', 'swift-framework-admin' ),
                            'google'         => true,
                            'font-backup'    => true,
                            'text-align'     => false,
                            'color'          => false,
                            'letter-spacing' => true,
                            'all_styles'     => true,
                            // Enable all Google Font style/weight variations to be added to the page
                            'output'         => array( 'h5' ),
                            // An array of CSS selectors to apply this font style to dynamically
                            'compiler'       => array( 'h5' ),
                            // An array of CSS selectors to apply this font style to dynamically
                            'units'          => 'px',
                            // Defaults to px
                            'default'        => array(
                                'font-size'   => '14px',
                                'line-height' => '18px',
                                'font-family' => 'Lato',
                                'font-weight' => '400',
                            ),
                        ),
                        array(
                            'id'             => 'h6_font',
                            'type'           => 'typography',
                            'title'          => __( 'H6 Font', 'swift-framework-admin' ),
                            'subtitle'       => __( 'Specify the H6 font properties.', 'swift-framework-admin' ),
                            'google'         => true,
                            'font-backup'    => true,
                            'text-align'     => false,
                            'color'          => false,
                            'letter-spacing' => true,
                            'all_styles'     => true,
                            // Enable all Google Font style/weight variations to be added to the page
                            'output'         => array( 'h6' ),
                            // An array of CSS selectors to apply this font style to dynamically
                            'compiler'       => array( 'h6' ),
                            // An array of CSS selectors to apply this font style to dynamically
                            'units'          => 'px',
                            // Defaults to px
                            'default'        => array(
                                'font-size'   => '12px',
                                'line-height' => '16px',
                                'font-family' => 'Lato',
                                'font-weight' => '400',
                            ),
                        ),
                        array(
                            'id'             => 'menu_font',
                            'type'           => 'typography',
                            'title'          => __( 'Menu Font', 'swift-framework-admin' ),
                            'subtitle'       => __( 'Specify the Menu font properties.', 'swift-framework-admin' ),
                            'google'         => true,
                            'font-backup'    => true,
                            'all_styles'     => true,
                            // Enable all Google Font style/weight variations to be added to the page
                            'line-height'    => false,
                            'color'          => false,
                            'letter-spacing' => true,
                            'output'         => array( '#main-nav, #header nav, #vertical-nav nav, .header-9#header-section #main-nav, #overlay-menu nav, #mobile-menu, #one-page-nav li .hover-caption, .mobile-search-form input[type="text"]' ),
                            // An array of CSS selectors to apply this font style to dynamically
                            'compiler'       => array( '#main-nav, #header nav,#vertical-nav nav, .header-9#header-section #main-nav, #overlay-menu nav, #mobile-menu, #one-page-nav li .hover-caption, .mobile-search-form input[type="text"]' ),
                            // An array of CSS selectors to apply this font style to dynamically
                            'units'          => 'px',
                            // Defaults to px
                            'default'        => array(
                                'font-size'   => '18px',
                                'font-family' => 'Source Sans Pro',
                                'font-weight' => '400',
                            ),
                        ),
                    ),
                );

                $this->sections[] = array(
                    'type' => 'divide',
                );

                $this->sections[] = array(
                    'icon'   => 'el-icon-th-list',
                    'title'  => __( 'Default Meta Options', 'swift-framework-admin' ),
                    'fields' => array(
                        array(
                            'id'       => 'default_show_page_heading',
                            'type'     => 'button_set',
                            'title'    => __( 'Default Show Page Heading', 'swift-framework-admin' ),
                            'subtitle' => __( 'Choose the default state for the page heading, shown/hidden.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'On', '0' => 'Off' ),
                            'default'  => '1'
                        ),
                        array(
                            'id'       => 'default_sidebar_config',
                            'type'     => 'select',
                            'title'    => __( 'Default Page Sidebar Config', 'swift-framework-admin' ),
                            'subtitle' => "Choose the default sidebar config for pages",
                            'options'  => array(
                                'no-sidebars'   => 'No Sidebars',
                                'left-sidebar'  => 'Left Sidebar',
                                'right-sidebar' => 'Right Sidebar',
                                'both-sidebars' => 'Both Sidebars'
                            ),
                            'desc'     => '',
                            'default'  => 'no-sidebars'
                        ),
                        array(
                            'id'       => 'default_left_sidebar',
                            'type'     => 'select',
                            'title'    => __( 'Default Page Left Sidebar', 'swift-framework-admin' ),
                            'subtitle' => "Choose the default left sidebar for pages",
                            'data'     => 'sidebars',
                            'desc'     => '',
                            'default'  => 'sidebar-1'
                        ),
                        array(
                            'id'       => 'default_right_sidebar',
                            'type'     => 'select',
                            'title'    => __( 'Default Page Right Sidebar', 'swift-framework-admin' ),
                            'subtitle' => "Choose the default right sidebar for pages",
                            'data'     => 'sidebars',
                            'desc'     => '',
                            'default'  => 'sidebar-1'
                        ),
                        array(
                            'id'   => 'dm_divide_1',
                            'type' => 'divide'
                        ),
                        array(
                            'id'       => 'default_post_sidebar_config',
                            'type'     => 'select',
                            'title'    => __( 'Default Post Sidebar Config', 'swift-framework-admin' ),
                            'subtitle' => "Choose the default sidebar config for posts",
                            'options'  => array(
                                'no-sidebars'   => 'No Sidebars',
                                'left-sidebar'  => 'Left Sidebar',
                                'right-sidebar' => 'Right Sidebar',
                                'both-sidebars' => 'Both Sidebars'
                            ),
                            'desc'     => '',
                            'default'  => 'no-sidebars'
                        ),
                        array(
                            'id'       => 'default_post_left_sidebar',
                            'type'     => 'select',
                            'title'    => __( 'Default Post Left Sidebar', 'swift-framework-admin' ),
                            'subtitle' => "Choose the default left sidebar for posts",
                            'data'     => 'sidebars',
                            'desc'     => '',
                            'default'  => 'sidebar-1'
                        ),
                        array(
                            'id'       => 'default_post_right_sidebar',
                            'type'     => 'select',
                            'title'    => __( 'Default Post Right Sidebar', 'swift-framework-admin' ),
                            'subtitle' => "Choose the default right sidebar for posts",
                            'data'     => 'sidebars',
                            'desc'     => '',
                            'default'  => 'sidebar-1'
                        ),
                        array(
                            'id'       => 'default_include_author',
                            'type'     => 'button_set',
                            'title'    => __( 'Default Include Author', 'swift-framework-admin' ),
                            'subtitle' => __( 'Choose the default state for the post author box, shown/hidden.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'On', '0' => 'Off' ),
                            'default'  => '1'
                        ),
                        array(
                            'id'       => 'default_include_social',
                            'type'     => 'button_set',
                            'title'    => __( 'Default Include Social Sharing', 'swift-framework-admin' ),
                            'subtitle' => __( 'Choose the default state for the post social sharing, shown/hidden.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'On', '0' => 'Off' ),
                            'default'  => '1'
                        ),
                        array(
                            'id'       => 'default_include_related',
                            'type'     => 'button_set',
                            'title'    => __( 'Default Include Related Articles', 'swift-framework-admin' ),
                            'subtitle' => __( 'Choose the default state for the post related articles, shown/hidden.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'On', '0' => 'Off' ),
                            'default'  => '1'
                        ),
                        array(
                            'id'       => 'default_thumb_media',
                            'type'     => 'select',
                            'title'    => __( 'Default Thumbnail Media', 'swift-framework-admin' ),
                            'subtitle' => "Choose the default thumbnail media for posts",
                            'options'  => array(
                                'none'  => 'None',
                                'image' => 'Image',
                            ),
                            'desc'     => '',
                            'default'  => 'image'
                        ),
                        array(
                            'id'       => 'default_detail_media',
                            'type'     => 'select',
                            'title'    => __( 'Default Detail Media', 'swift-framework-admin' ),
                            'subtitle' => "Choose the default detail media for posts",
                            'options'  => array(
                                'none'  => 'None',
                                'image' => 'Image',
                            ),
                            'desc'     => '',
                            'default'  => 'image'
                        ),
                        array(
                            'id'       => 'default_media_display',
                            'type'     => 'select',
                            'title'    => __( 'Default Post Media Display', 'swift-framework-admin' ),
                            'subtitle' => "Choose the default media display for posts",
                            'options'  => array(
                                'fw-media-title' => 'Full Width with Title Overlay',
                                'fw-media'       => 'Full Width',
                                'standard-above' => 'Standard (Above content)',
                                'standard'       => 'Standard',
                            ),
                            'desc'     => '',
                            'default'  => 'standard'
                        ),
                    ),
                );

                $this->sections[] = array(
                    'icon'   => 'el-icon-th',
                    'title'  => __( 'Archive/Category Options', 'swift-framework-admin' ),
                    'fields' => array(
                        array(
                            'id'       => 'archive_sidebar_config',
                            'type'     => 'select',
                            'title'    => __( 'Sidebar Config', 'swift-framework-admin' ),
                            'subtitle' => "Choose the sidebar configuration for the archive/category pages.",
                            'options'  => array(
                                'no-sidebars'   => 'No Sidebars',
                                'left-sidebar'  => 'Left Sidebar',
                                'right-sidebar' => 'Right Sidebar',
                                'both-sidebars' => 'Both Sidebars'
                            ),
                            'desc'     => '',
                            'default'  => 'right-sidebar'
                        ),
                        array(
                            'id'       => 'archive_sidebar_left',
                            'type'     => 'select',
                            'title'    => __( 'Left Sidebar', 'swift-framework-admin' ),
                            'subtitle' => "Choose the left sidebar for Left/Both sidebar configs.",
                            'data'     => 'sidebars',
                            'desc'     => '',
                            'default'  => 'sidebar-1'
                        ),
                        array(
                            'id'       => 'archive_sidebar_right',
                            'type'     => 'select',
                            'title'    => __( 'Right Sidebar', 'swift-framework-admin' ),
                            'subtitle' => "Choose the left sidebar for Right/Both sidebar configs.",
                            'data'     => 'sidebars',
                            'desc'     => '',
                            'default'  => 'sidebar-1'
                        ),
                        array(
                            'id'       => 'archive_display_type',
                            'type'     => 'select',
                            'title'    => __( 'Display Type', 'swift-framework-admin' ),
                            'subtitle' => "Select the display type. Note: Masonry (Full Width) is only available when the sidebar config is set to no sidebars.",
                            'options'  => array(
                                'standard' => 'Standard',
                                'timeline' => 'Timeline',
                                'bold'     => 'Bold',
                                'mini'     => 'Mini',
                                'masonry'  => 'Masonry',
                            ),
                            'desc'     => '',
                            'default'  => 'masonry'
                        ),
                        array(
                            'id'       => 'archive_content_output',
                            'type'     => 'select',
                            'title'    => __( 'Archive Content Output', 'swift-framework-admin' ),
                            'subtitle' => "Select if you'd like to output the content or excerpt on archive pages.",
                            'options'  => array(
                                'excerpt' => 'Excerpt',
                                'content' => 'Content',
                            ),
                            'desc'     => '',
                            'default'  => 'excerpt'
                        ),
                        array(
                            'id'   => 'archive_divide_a',
                            'type' => 'divide'
                        ),
                        array(
                            'id'       => 'portfolio_archive_display_type',
                            'type'     => 'select',
                            'title'    => __( 'Portfolio Archive Display Type', 'swift-framework-admin' ),
                            'subtitle' => "Select the display type.",
                            'options'  => array(
                                'standard'        => 'Standard',
                                'gallery'         => 'Gallery',
                                'masonry'         => 'Masonry',
                                'masonry-gallery' => 'Masonry Gallery'
                            ),
                            'desc'     => '',
                            'default'  => 'standard'
                        ),
                        array(
                            'id'       => 'portfolio_archive_columns',
                            'type'     => 'select',
                            'title'    => __( 'Portfolio Archive Columns', 'swift-framework-admin' ),
                            'subtitle' => "Select the number of columns for the portfolio archive.",
                            'options'  => array(
                                '1' => '1',
                                '2' => '2',
                                '3' => '3',
                                '4' => '4'
                            ),
                            'desc'     => '',
                            'default'  => '4'
                        )
                    ),
                );

                $this->sections[] = array(
                    'icon'   => 'el-icon-group',
                    'title'  => __( 'BuddyPress & bbPress Options', 'swift-framework-admin' ),
                    'fields' => array(
                        array(
                            'id'       => 'bp_sidebar_config',
                            'type'     => 'select',
                            'title'    => __( 'BuddyPress Sidebar Config', 'swift-framework-admin' ),
                            'subtitle' => "Choose the sidebar configuration for the BuddyPress pages.",
                            'options'  => array(
                                'no-sidebars'   => 'No Sidebars',
                                'left-sidebar'  => 'Left Sidebar',
                                'right-sidebar' => 'Right Sidebar',
                                'both-sidebars' => 'Both Sidebars'
                            ),
                            'desc'     => '',
                            'default'  => 'right-sidebar'
                        ),
                        array(
                            'id'       => 'bp_sidebar_left',
                            'type'     => 'select',
                            'title'    => __( 'BuddyPress Left Sidebar', 'swift-framework-admin' ),
                            'subtitle' => "Choose the left sidebar for Left/Both sidebar configs.",
                            'data'     => 'sidebars',
                            'desc'     => '',
                            'default'  => 'sidebar-1'
                        ),
                        array(
                            'id'       => 'bp_sidebar_right',
                            'type'     => 'select',
                            'title'    => __( 'BuddyPress Right Sidebar', 'swift-framework-admin' ),
                            'subtitle' => "Choose the left sidebar for Right/Both sidebar configs.",
                            'data'     => 'sidebars',
                            'desc'     => '',
                            'default'  => 'sidebar-1'
                        ),
                        array(
                            'id'   => 'bb_divide_a',
                            'type' => 'divide'
                        ),
                        array(
                            'id'       => 'bb_sidebar_config',
                            'type'     => 'select',
                            'title'    => __( 'bbPress Sidebar Config', 'swift-framework-admin' ),
                            'subtitle' => "Choose the sidebar configuration for the bbPress pages.",
                            'options'  => array(
                                'no-sidebars'   => 'No Sidebars',
                                'left-sidebar'  => 'Left Sidebar',
                                'right-sidebar' => 'Right Sidebar',
                                'both-sidebars' => 'Both Sidebars'
                            ),
                            'desc'     => '',
                            'default'  => 'right-sidebar'
                        ),
                        array(
                            'id'       => 'bb_sidebar_left',
                            'type'     => 'select',
                            'title'    => __( 'bbPress Left Sidebar', 'swift-framework-admin' ),
                            'subtitle' => "Choose the left sidebar for Left/Both sidebar configs.",
                            'data'     => 'sidebars',
                            'desc'     => '',
                            'default'  => 'sidebar-1'
                        ),
                        array(
                            'id'       => 'bb_sidebar_right',
                            'type'     => 'select',
                            'title'    => __( 'bbPress Right Sidebar', 'swift-framework-admin' ),
                            'subtitle' => "Choose the left sidebar for Right/Both sidebar configs.",
                            'data'     => 'sidebars',
                            'desc'     => '',
                            'default'  => 'sidebar-1'
                        ),
                    ),
                );

                $this->sections[] = array(
                    'icon'   => 'el-icon-view-mode',
                    'title'  => __( 'Custom Post Type Options', 'swift-framework-admin' ),
                    'fields' => array(
                        array(
                            'id'       => 'cpt-disable',
                            'type'     => 'checkbox',
                            'title'    => __( 'Disable Custom Post Types', 'swift-framework-admin' ),
                            'subtitle' => __( 'You can disable the custom post types used within the theme here, by checking the corresponding box. NOTE: If you do not want to disable any, then make sure none of the boxes are checked.', 'swift-framework-admin' ),
                            'options'  => array(
                                'portfolio'    => 'Portfolio',
                                'galleries'    => 'Galleries',
                                'team'         => 'Team',
                                'clients'      => 'Clients',
                                'testimonials' => 'Testimonials',
                                'directory'    => 'Directory',
                                'spb-section'  => 'SPB Section',
                            ),
                            'default'  => array(
                                'portfolio'    => '0',
                                'galleries'    => '0',
                                'team'         => '0',
                                'clients'      => '0',
                                'testimonials' => '0',
                                'directory'    => '0',
                                'swift-slider' => '0'
                            )
                        ),
                        array(
                            'id'   => 'cpt-divide-0',
                            'type' => 'divide'
                        ),
                        array(
                            'id'       => 'blog_page',
                            'type'     => 'select',
                            'data'     => 'pages',
                            'title'    => __( 'Blog Page', 'swift-framework-admin' ),
                            'subtitle' => __( 'Select the page that is your main blog index page. This is used to link to the page from the blog post detail page, and the page builder recent post asset.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'default'  => '',
                            'args'     => array()
                        ),
                        array(
                            'id'       => 'enable_post_category_navigation',
                            'type'     => 'button_set',
                            'title'    => __( 'Same Category Post Navigation', 'swift-framework-admin' ),
                            'subtitle' => __( 'Enable this if you would like to set it so that the single post pagination only includes items within the same category.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'On', '0' => 'Off' ),
                            'default'  => '0'
                        ),
                        array(
                            'id'       => 'single_author',
                            'type'     => 'button_set',
                            'title'    => __( 'Single Author Blog', 'swift-framework-admin' ),
                            'subtitle' => __( 'If enabled, the author name will be hidden from the blog/post details in the page builder assets and single details line.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'On', '0' => 'Off' ),
                            'default'  => '0'
                        ),
                        array(
                            'id'       => 'remove_dates',
                            'type'     => 'button_set',
                            'title'    => __( 'Remove Post Dates', 'swift-framework-admin' ),
                            'subtitle' => __( 'If enabled, the date will not be included with the post details.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'On', '0' => 'Off' ),
                            'default'  => '0'
                        ),
                        array(
                            'id'   => 'cpt-divide-1',
                            'type' => 'divide'
                        ),
                        array(
                            'id'       => 'portfolio_page',
                            'type'     => 'select',
                            'data'     => 'pages',
                            'title'    => __( 'Portfolio Page', 'swift-framework-admin' ),
                            'subtitle' => __( 'Select the page that is your portfolio index page. This is used to link to the page from the portfolio detail page.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'default'  => '',
                            'args'     => array()
                        ),
                        array(
                            'id'       => 'enable_category_navigation',
                            'type'     => 'button_set',
                            'title'    => __( 'Same Category Portfolio Navigation', 'swift-framework-admin' ),
                            'subtitle' => __( 'Enable this if you would like to set it so that the single portfolio pagination only includes items within the same category.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'On', '0' => 'Off' ),
                            'default'  => '0'
                        ),
                        array(
                            'id'       => 'related_projects_fullwidth',
                            'type'     => 'button_set',
                            'title'    => __( 'Full Width Related Projects Display', 'swift-framework-admin' ),
                            'subtitle' => __( 'Enable this to make the related projects show full width on the portfolio detail page.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'On', '0' => 'Off' ),
                            'default'  => '1'
                        ),
                        array(
                            'id'       => 'related_projects_columns',
                            'type'     => 'button_set',
                            'title'    => __( 'Related Projects Columns', 'swift-framework-admin' ),
                            'subtitle' => __( 'Choose between 3 and 4 columns for the related projects the portfolio detail page.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '3' => '3', '4' => '4' ),
                            'default'  => '3'
                        ),
                        array(
                            'id'   => 'cpt-divide-2',
                            'type' => 'divide'
                        ),
                        array(
                            'id'       => 'testimonial_page',
                            'type'     => 'select',
                            'data'     => 'pages',
                            'title'    => __( 'Testimonial Page', 'swift-framework-admin' ),
                            'subtitle' => __( 'Select the page that is your testimonial index page. This is used to link to the page from various places.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'default'  => '',
                            'args'     => array()
                        ),
                    ),
                );

                $this->sections[] = array(
                    'type' => 'divide',
                );

                $this->sections[] = array(
                    'icon'   => 'el-icon-shopping-cart',
                    'title'  => __( 'WooCommerce Options', 'swift-framework-admin' ),
                    'fields' => array(
                        array(
                            'id'       => 'enable_catalog_mode',
                            'type'     => 'button_set',
                            'title'    => __( 'Catalog Mode', 'swift-framework-admin' ),
                            'subtitle' => __( 'Enable this setting to set the products into catalog mode, with no cart or checkout process.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'On', '0' => 'Off' ),
                            'default'  => '0'
                        ),
                        array(
                            'id'       => 'products_per_page',
                            'type'     => 'text',
                            'title'    => __( 'Products Per Page', 'swift-framework-admin' ),
                            'subtitle' => __( 'Number value.', 'swift-framework-admin' ),
                            'desc'     => __( 'The amount of products you would like to show per page on shop/category pages.', 'swift-framework-admin' ),
                            'validate' => 'numeric',
                            'default'  => '24',
                        ),
                        array(
                            'id'       => 'new_badge',
                            'type'     => 'text',
                            'title'    => __( 'New Badge', 'swift-framework-admin' ),
                            'subtitle' => __( 'Number value.', 'swift-framework-admin' ),
                            'desc'     => __( 'The amount of time in days that the "New" badge will display on products. Set this to 0 to disable the badge.', 'swift-framework-admin' ),
                            'validate' => 'numeric',
                            'default'  => '7',
                        ),
                        array(
                            'id'   => 'woo_general_divide_1',
                            'type' => 'divide'
                        ),
                        array(
                            'id'       => 'checkout_new_account_text',
                            'type'     => 'textarea',
                            'title'    => __( 'New account text', 'swift-framework-admin' ),
                            'subtitle' => __( 'This text appears in the sign in / sign up area of the checkout process.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'default'  => 'Creating an account with Cardinal is quick and easy, and will allow you to move through our checkout quicker. You can also store multiple shipping addresses, gain access to your order history, and much more.'
                        ),
                        array(
                            'id'       => 'help_bar_text',
                            'type'     => 'text',
                            'title'    => __( 'Help Bar Text', 'swift-framework-admin' ),
                            'subtitle' => __( 'This text appears in the help bar on account / checkout pages.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'default'  => 'Need help? Call customer services on 0800 123 4567.'
                        ),
                        array(
                            'id'       => 'email_modal',
                            'type'     => 'textarea',
                            'title'    => __( 'Email customer care modal', 'swift-framework-admin' ),
                            'subtitle' => __( 'The content that appears in the modal box for the email customer care help link.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'default'  => 'Enter your contact details or email form shortcode here. (Text/HTML/Shortcodes accepted).'
                        ),
                        array(
                            'id'       => 'shipping_modal',
                            'type'     => 'textarea',
                            'title'    => __( 'Shipping information modal', 'swift-framework-admin' ),
                            'subtitle' => __( 'The content that appears in the modal box for the shipping information help link.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'default'  => 'Enter your shipping information here. (Text/HTML/Shortcodes accepted).'
                        ),
                        array(
                            'id'       => 'returns_modal',
                            'type'     => 'textarea',
                            'title'    => __( 'Returns & exchange modal', 'swift-framework-admin' ),
                            'subtitle' => __( 'The content that appears in the modal box for the returns & exchange help link.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'default'  => 'Enter your returns and exchange information here. (Text/HTML/Shortcodes accepted).'
                        ),
                        array(
                            'id'       => 'faqs_modal',
                            'type'     => 'textarea',
                            'title'    => __( 'FAQs modal', 'swift-framework-admin' ),
                            'subtitle' => __( 'The content that appears in the modal box for the faqs help link.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'default'  => 'Enter your faqs here. (Text/HTML/Shortcodes accepted).'
                        ),
                        array(
                            'id'       => 'feedback_modal',
                            'type'     => 'textarea',
                            'title'    => __( 'Feedback modal', 'swift-framework-admin' ),
                            'subtitle' => __( 'The content that appears in the modal box for the leave feedback link.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'default'  => 'Enter your feedback modal content here. (Text/HTML/Shortcodes accepted).'
                        ),
                    ),
                );

                $this->sections[] = array(
                    'icon'       => 'el-icon-shopping-cart',
                    'title'      => __( 'Shop Options', 'swift-framework-admin' ),
                    'subsection' => true,
                    'fields'     => array(
                        array(
                            'id'       => 'product_display_type',
                            'type'     => 'select',
                            'title'    => __( 'Product Display Type', 'swift-framework-admin' ),
                            'subtitle' => "Choose the product display type for WooCommerce shop/category pages.",
                            'options'  => array(
                                'standard'         => 'Standard',
                                'gallery'          => 'Gallery',
                                'gallery-bordered' => 'Gallery Bordered',
                            ),
                            'desc'     => '',
                            'default'  => 'standard'
                        ),
                        array(
                            'id'       => 'product_display_columns',
                            'type'     => 'select',
                            'title'    => __( 'Product Display Columns', 'swift-framework-admin' ),
                            'subtitle' => "Choose the number of columns to display on shop/category pages.",
                            'options'  => array(
                                '2' => '2',
                                '3' => '3',
                                '4' => '4',
                                '5' => '5',
                                '6' => '6',
                            ),
                            'desc'     => '',
                            'default'  => '4'
                        ),
                        array(
                            'id'       => 'product_display_gutters',
                            'type'     => 'button_set',
                            'title'    => __( 'Product Display Gutters', 'swift-framework-admin' ),
                            'subtitle' => __( 'Choose if you would like spacing in between the products - Gallery modes only.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'Gutters', '0' => 'No Gutters' ),
                            'default'  => '1'
                        ),
                        array(
                            'id'       => 'product_display_fullwidth',
                            'type'     => 'button_set',
                            'title'    => __( 'Full Width Product Display', 'swift-framework-admin' ),
                            'subtitle' => __( 'Choose if you would like the shop page to show full width, with a sidebar integrated into the masonry (Only Left/Right Sidebar Option is supported).', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'Yes', '0' => 'No' ),
                            'default'  => '0'
                        ),
                        array(
                            'id'       => 'product_qv_hover',
                            'type'     => 'button_set',
                            'title'    => __( 'Quickview only on hover', 'swift-framework-admin' ),
                            'subtitle' => __( 'Enable this if you would like the quickview to only show on hover. Note: You will need the quickview plugin installed and activated.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'Yes', '0' => 'No' ),
                            'default'  => '1'
                        ),
                        array(
                            'id'       => 'product_rating',
                            'type'     => 'button_set',
                            'title'    => __( 'Standard - Show rating', 'swift-framework-admin' ),
                            'subtitle' => __( 'Enable this if you would like to show the product rating below the product image/details (standard display type only).', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'Yes', '0' => 'No' ),
                            'default'  => '1'
                        ),
                        array(
                            'id'       => 'product_buybtn',
                            'type'     => 'button_set',
                            'title'    => __( 'Standard - Show buy button', 'swift-framework-admin' ),
                            'subtitle' => __( 'Enable this if you would like to show the buy button below the product image/details (standard display type only).', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'Yes', '0' => 'No' ),
                            'default'  => '1'
                        ),
                        array(
                            'id'       => 'woo_sidebar_config',
                            'type'     => 'select',
                            'title'    => __( 'WooCommerce Sidebar Config', 'swift-framework-admin' ),
                            'subtitle' => "Choose the sidebar config for WooCommerce shop/category pages.",
                            'options'  => array(
                                'no-sidebars'   => 'No Sidebars',
                                'left-sidebar'  => 'Left Sidebar',
                                'right-sidebar' => 'Right Sidebar',
                                'both-sidebars' => 'Both Sidebars'
                            ),
                            'desc'     => '',
                            'default'  => 'no-sidebars'
                        ),
                        array(
                            'id'       => 'woo_left_sidebar',
                            'type'     => 'select',
                            'title'    => __( 'WooCommerce Left Sidebar', 'swift-framework-admin' ),
                            'subtitle' => "Choose the left sidebar for WooCommerce shop/category pages.",
                            'data'     => 'sidebars',
                            'desc'     => '',
                            'default'  => 'woocommerce-sidebar'
                        ),
                        array(
                            'id'       => 'woo_right_sidebar',
                            'type'     => 'select',
                            'title'    => __( 'WooCommerce Right Sidebar', 'swift-framework-admin' ),
                            'subtitle' => "Choose the right sidebar for WooCommerce shop/category pages.",
                            'data'     => 'sidebars',
                            'desc'     => '',
                            'default'  => 'woocommerce-sidebar'
                        ),
                        array(
                            'id'   => 'woo_shop_divide_0',
                            'type' => 'divide'
                        ),
                        array(
                            'id'       => 'woo_show_page_heading',
                            'type'     => 'button_set',
                            'title'    => __( 'Shop Category / Page Heading', 'swift-framework-admin' ),
                            'subtitle' => __( 'Show page title on shop/category WooCommerce page.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'On', '2' => 'Sort Only', '0' => 'Off' ),
                            'default'  => '1'
                        ),
                        array(
                            'id'       => 'woo_page_heading_style',
                            'type'     => 'select',
                            'title'    => __( 'WooCommerce Page Heading Style', 'swift-framework-admin' ),
                            'subtitle' => "Choose the page heading style for the shop/category WooCommerce pages.",
                            'options'  => array(
                                'standard' => 'Standard',
                                'fancy'    => 'Fancy'
                            ),
                            'desc'     => '',
                            'default'  => 'standard'
                        ),
                        array(
                            'id'       => 'woo_page_heading_image',
                            'type'     => 'media',
                            'url'      => true,
                            'title'    => __( 'WooCommerce Fancy Heading Background Image', 'swift-framework-admin' ),
                            'subtitle' => __( 'Upload the fancy heading background image for WooCommerce page heading (Fancy Heading Only).', 'swift-framework-admin' ),
                            'desc'     => ''
                        ),
                        array(
                            'id'       => 'woo_page_heading_text_style',
                            'type'     => 'select',
                            'title'    => __( 'WooCommerce Fancy Heading Text Style', 'swift-framework-admin' ),
                            'subtitle' => "Choose the text style for the WooCommerce page heading (Fancy Heading Only).",
                            'options'  => array(
                                'light' => 'Light',
                                'dark'  => 'Dark'
                            ),
                            'desc'     => '',
                            'default'  => 'light'
                        ),
                    ),
                );

                $this->sections[] = array(
                    'icon'       => 'el-icon-shopping-cart',
                    'title'      => __( 'Product Options', 'swift-framework-admin' ),
                    'subsection' => true,
                    'fields'     => array(
                        array(
                            'id'       => 'enable_default_tabs',
                            'type'     => 'button_set',
                            'title'    => __( 'Product Description Tabs Mode', 'swift-framework-admin' ),
                            'subtitle' => __( 'Enable this setting to revert to the default product description styling tabs - this will allow you to use extensions that add extra tabs to the product tabs.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'On', '0' => 'Off' ),
                            'default'  => '0'
                        ),
                        array(
                            'id'       => 'enable_product_zoom',
                            'type'     => 'button_set',
                            'title'    => __( 'Enable image zoom on product images', 'swift-framework-admin' ),
                            'subtitle' => __( 'Choose whether you would like to enable product image zoom functionality on the product detail page images.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( '1' => 'On', '0' => 'Off' ),
                            'default'  => '0'
                        ),
                        array(
                            'id'       => 'product_zoom_type',
                            'type'     => 'button_set',
                            'required' => array( 'enable_product_zoom', '=', '1' ),
                            'title'    => __( 'Image zoom type', 'swift-framework-admin' ),
                            'subtitle' => __( 'Choose whether you would like to enable product image zoom functionality on the product detail page images.', 'swift-framework-admin' ),
                            'desc'     => '',
                            'options'  => array( 'inner' => 'Default (inner)', 'lens' => 'Lens' ),
                            'default'  => 'inner'
                        ),
                        array(
                            'id'   => 'woo_product_divide_0',
                            'type' => 'divide'
                        ),
                        array(
                            'id'       => 'default_product_sidebar_config',
                            'type'     => 'select',
                            'title'    => __( 'Default Product Sidebar Config', 'swift-framework-admin' ),
                            'subtitle' => "Choose the sidebar config for WooCommerce shop/category pages.",
                            'options'  => array(
                                'no-sidebars'   => 'No Sidebars',
                                'left-sidebar'  => 'Left Sidebar',
                                'right-sidebar' => 'Right Sidebar',
                                'both-sidebars' => 'Both Sidebars'
                            ),
                            'desc'     => '',
                            'default'  => 'no-sidebars'
                        ),
                        array(
                            'id'       => 'default_product_left_sidebar',
                            'type'     => 'select',
                            'title'    => __( 'Default Product Left Sidebar', 'swift-framework-admin' ),
                            'subtitle' => "Choose the default left sidebar for WooCommerce product pages.",
                            'data'     => 'sidebars',
                            'desc'     => '',
                            'default'  => 'woocommerce-sidebar'
                        ),
                        array(
                            'id'       => 'default_product_right_sidebar',
                            'type'     => 'select',
                            'title'    => __( 'Default Product Right Sidebar', 'swift-framework-admin' ),
                            'subtitle' => "Choose the default right sidebar for WooCommerce product pages.",
                            'data'     => 'sidebars',
                            'desc'     => '',
                            'default'  => 'woocommerce-sidebar'
                        ),
                    ),
                );

                $this->sections[] = array(
                    'type' => 'divide',
                );

                $this->sections[] = array(
                    'icon'   => 'el-icon-twitter',
                    'title'  => __( 'Social Profiles', 'swift-framework-admin' ),
                    'desc'   => 'These fields populate the [social] shortcode, which you can then use anywhere in your site.',
                    'fields' => array(
                        array(
                            'id'       => 'twitter_username',
                            'type'     => 'text',
                            'title'    => __( 'Twitter', 'swift-framework-admin' ),
                            'subtitle' => "Your Twitter username (no @).",
                            'desc'     => '',
                            'default'  => ''
                        ),
                        array(
                            'id'       => 'facebook_page_url',
                            'type'     => 'text',
                            'title'    => __( 'Facebook', 'swift-framework-admin' ),
                            'subtitle' => "Your facebook page/profile url",
                            'desc'     => '',
                            'default'  => ''
                        ),
                        array(
                            'id'       => 'dribbble_username',
                            'type'     => 'text',
                            'title'    => __( 'Dribbble', 'swift-framework-admin' ),
                            'subtitle' => "Your Dribbble username",
                            'desc'     => '',
                            'default'  => ''
                        ),
                        array(
                            'id'       => 'vimeo_username',
                            'type'     => 'text',
                            'title'    => __( 'Vimeo', 'swift-framework-admin' ),
                            'subtitle' => "Your Vimeo username",
                            'desc'     => '',
                            'default'  => ''
                        ),
                        array(
                            'id'       => 'tumblr_username',
                            'type'     => 'text',
                            'title'    => __( 'Tumblr', 'swift-framework-admin' ),
                            'subtitle' => "Your Tumblr username",
                            'desc'     => '',
                            'default'  => ''
                        ),
                        array(
                            'id'       => 'skype_username',
                            'type'     => 'text',
                            'title'    => __( 'Skype', 'swift-framework-admin' ),
                            'subtitle' => "Your Skype username",
                            'desc'     => '',
                            'default'  => ''
                        ),
                        array(
                            'id'       => 'linkedin_page_url',
                            'type'     => 'text',
                            'title'    => __( 'LinkedIn', 'swift-framework-admin' ),
                            'subtitle' => "Your LinkedIn page/profile url",
                            'desc'     => '',
                            'default'  => ''
                        ),
                        array(
                            'id'       => 'googleplus_page_url',
                            'type'     => 'text',
                            'title'    => __( 'Google+', 'swift-framework-admin' ),
                            'subtitle' => "Your Google+ page/profile URL",
                            'desc'     => '',
                            'default'  => ''
                        ),
                        array(
                            'id'       => 'flickr_page_url',
                            'type'     => 'text',
                            'title'    => __( 'Flickr', 'swift-framework-admin' ),
                            'subtitle' => "Your Flickr page url",
                            'desc'     => '',
                            'default'  => ''
                        ),
                        array(
                            'id'       => 'youtube_url',
                            'type'     => 'text',
                            'title'    => __( 'YouTube', 'swift-framework-admin' ),
                            'subtitle' => "Your YouTube URL",
                            'desc'     => '',
                            'default'  => ''
                        ),
                        array(
                            'id'       => 'pinterest_username',
                            'type'     => 'text',
                            'title'    => __( 'Pinterest', 'swift-framework-admin' ),
                            'subtitle' => "Your Pinterest username",
                            'desc'     => '',
                            'default'  => ''
                        ),
                        array(
                            'id'       => 'foursquare_url',
                            'type'     => 'text',
                            'title'    => __( 'Foursquare', 'swift-framework-admin' ),
                            'subtitle' => "Your Foursqaure URL",
                            'desc'     => '',
                            'default'  => ''
                        ),
                        array(
                            'id'       => 'instagram_username',
                            'type'     => 'text',
                            'title'    => __( 'Instagram', 'swift-framework-admin' ),
                            'subtitle' => "Your Instagram username",
                            'desc'     => '',
                            'default'  => ''
                        ),
                        array(
                            'id'       => 'github_url',
                            'type'     => 'text',
                            'title'    => __( 'GitHub', 'swift-framework-admin' ),
                            'subtitle' => "Your GitHub URL",
                            'desc'     => '',
                            'default'  => ''
                        ),
                        array(
                            'id'       => 'xing_url',
                            'type'     => 'text',
                            'title'    => __( 'Xing', 'swift-framework-admin' ),
                            'subtitle' => "Your Xing URL",
                            'desc'     => '',
                            'default'  => ''
                        ),
                        array(
                            'id'       => 'behance_url',
                            'type'     => 'text',
                            'title'    => __( 'Behance', 'swift-framework-admin' ),
                            'subtitle' => "Your Behance URL",
                            'desc'     => '',
                            'default'  => ''
                        ),
                        array(
                            'id'       => 'deviantart_url',
                            'type'     => 'text',
                            'title'    => __( 'Deviantart', 'swift-framework-admin' ),
                            'subtitle' => "Your Deviantart URL",
                            'desc'     => '',
                            'default'  => ''
                        ),
                        array(
                            'id'       => 'soundcloud_url',
                            'type'     => 'text',
                            'title'    => __( 'SoundCloud', 'swift-framework-admin' ),
                            'subtitle' => "Your SoundCloud URL",
                            'desc'     => '',
                            'default'  => ''
                        ),
                        array(
                            'id'       => 'yelp_url',
                            'type'     => 'text',
                            'title'    => __( 'Yelp', 'swift-framework-admin' ),
                            'subtitle' => "Your Yelp URL",
                            'desc'     => '',
                            'default'  => ''
                        ),
                        array(
                            'id'       => 'rss_url',
                            'type'     => 'text',
                            'title'    => __( 'RSS Feed', 'swift-framework-admin' ),
                            'subtitle' => "Your RSS Feed URL",
                            'desc'     => '',
                            'default'  => ''
                        )
                    ),
                );

                $this->sections[] = array(
                    'type' => 'divide',
                );

                $this->sections[] = array(
                    'title'  => __( 'Import / Export', 'swift-framework-admin' ),
                    'desc'   => __( 'Import and Export your Redux Framework settings from file, text or URL.', 'swift-framework-admin' ),
                    'icon'   => 'el-icon-refresh',
                    'fields' => array(
                        array(
                            'id'         => 'opt-import-export',
                            'type'       => 'import_export',
                            'title'      => 'Import Export',
                            'subtitle'   => 'Save and restore your Redux options',
                            'full_width' => false,
                        ),
                    ),
                );

                $theme_info = '<div class="redux-framework-section-desc">';
                $theme_info .= '<p class="redux-framework-theme-data description theme-uri">' . __( '<strong>Theme URL:</strong> ', 'swift-framework-admin' ) . '<a href="' . $this->theme->get( 'ThemeURI' ) . '" target="_blank">' . $this->theme->get( 'ThemeURI' ) . '</a></p>';
                $theme_info .= '<p class="redux-framework-theme-data description theme-author">' . __( '<strong>Author:</strong> ', 'swift-framework-admin' ) . $this->theme->get( 'Author' ) . '</p>';
                $theme_info .= '<p class="redux-framework-theme-data description theme-version">' . __( '<strong>Version:</strong> ', 'swift-framework-admin' ) . $this->theme->get( 'Version' ) . '</p>';
                $theme_info .= '<p class="redux-framework-theme-data description theme-description">' . $this->theme->get( 'Description' ) . '</p>';
                $tabs = $this->theme->get( 'Tags' );
                if ( ! empty( $tabs ) ) {
                    $theme_info .= '<p class="redux-framework-theme-data description theme-tags">' . __( '<strong>Tags:</strong> ', 'swift-framework-admin' ) . implode( ', ', $tabs ) . '</p>';
                }
                $theme_info .= '</div>';

//			if(file_exists(dirname(__FILE__).'/README.md')){
//			$this->sections['theme_docs'] = array(
//						'icon' => ReduxFramework::$_url.'assets/img/glyphicons/glyphicons_071_book.png',
//						'title' => __('Documentation', 'swift-framework-admin'),
//						'fields' => array(
//							array(
//								'id'=>'17',
//								'type' => 'raw',
//								'content' => file_get_contents(dirname(__FILE__).'/README.md')
//								),				
//						),
//						
//						);
//			}//if

//			$this->sections[] = array(
//				'type' => 'divide',
//			);
//
//			$this->sections[] = array(
//				'icon' => 'el-icon-info-sign',
//				'title' => __('Theme Information', 'swift-framework-admin'),
//				'desc' => __('<p class="description">This is the Description. Again HTML is allowed</p>', 'swift-framework-admin'),
//				'fields' => array(
//					array(
//						'id'=>'raw_new_info',
//						'type' => 'raw',
//						'content' => $item_info,
//						)
//					),   
//				);
//
//			if(file_exists(trailingslashit(dirname(__FILE__)) . 'README.html')) {
//			    $tabs['docs'] = array(
//					'icon' => 'el-icon-book',
//					    'title' => __('Documentation', 'swift-framework-admin'),
//			        'content' => nl2br(file_get_contents(trailingslashit(dirname(__FILE__)) . 'README.html'))
//			    );
//			}

            }


            /**
             * All the possible arguments for Redux.
             * For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments
             **/
            public function setArguments() {

                $theme = wp_get_theme(); // For use with some settings. Not necessary.

                $this->args = array(

                    // TYPICAL -> Change these values as you need/desire
                    'opt_name'           => 'sf_cardinal_options',
                    // This is where your data is stored in the database and also becomes your global variable name.
                    //'display_name'			=> $theme->get('Name'), // Name that appears at the top of your panel
                    'display_name'       => __( 'Theme Options', 'swift-framework-admin' ),
                    // Name that appears at the top of your panel
                    //'display_version'		=> $theme->get('Version'), // Version that appears at the top of your panel
                    'menu_type'          => 'menu',
                    //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
                    'allow_sub_menu'     => true,
                    // Show the sections below the admin menu item or not
                    'menu_title'         => __( 'Theme Options', 'swift-framework-admin' ),
                    'page'               => __( 'Theme Options', 'swift-framework-admin' ),
                    'google_api_key'     => 'AIzaSyC2wsPjq6DE7aShaWCJlOhWwY3FPw5-ikc',
                    // Must be defined to add google fonts to the typography module
                    'global_variable'    => '',
                    // Set a different name for your global variable other than the opt_name
                    'dev_mode'           => false,
                    // Show the time the page took to load, etc
                    'customizer'         => false,
                    // Enable basic customizer support

                    // OPTIONAL -> Give you extra features
                    'admin_bar'          => true,
                    // Show the panel pages on the admin bar
                    'page_priority'      => null,
                    // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
                    'page_parent'        => 'themes.php',
                    // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
                    'page_permissions'   => 'manage_options',
                    // Permissions needed to access the options panel.
                    'menu_icon'          => '',
                    // Specify a custom URL to an icon
                    'last_tab'           => '',
                    // Force your panel to always open to a specific tab (by id)
                    'page_icon'          => 'icon-themes',
                    // Icon displayed in the admin panel next to your menu_title
                    'page_slug'          => '_sf_options',
                    // Page slug used to denote the panel
                    'save_defaults'      => true,
                    // On load save the defaults to DB before user clicks save or not
                    'default_show'       => false,
                    // If true, shows the default value next to each field that is not the default value.
                    'default_mark'       => '',
                    // What to print by the field's title if the value shown is default. Suggested: *


                    // CAREFUL -> These options are for advanced use only
                    'transient_time'     => 60 * MINUTE_IN_SECONDS,
                    'output'             => true,
                    // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
                    'output_tag'         => true,
                    // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
                    //'domain'             	=> 'swift-framework-admin', // Translation domain key. Don't change this unless you want to retranslate all of Redux.
                    //'footer_credit'      	=> '', // Disable the footer credit of Redux. Please leave if you can help it.


                    // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
                    'database'           => '',
                    // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!


                    'show_import_export' => true,
                    // REMOVE
                    'system_info'        => false,
                    // REMOVE

                    'help_tabs'          => array(),
                    'help_sidebar'       => '',
                    // __( '', $this->args['domain'] );            
                );

            }
        }

        global $reduxConfig;
        $reduxConfig = new Redux_Framework_options_config();
    }

    /**
     * Custom function for the callback referenced above
     */
    if ( ! function_exists( 'redux_my_custom_field' ) ):
        function redux_my_custom_field( $field, $value ) {
            print_r( $field );
            echo '<br/>';
            print_r( $value );
        }
    endif;

    /**
     * Custom function for the callback validation referenced above
     * */
    if ( ! function_exists( 'redux_validate_callback_function' ) ):
        function redux_validate_callback_function( $field, $value, $existing_value ) {
            $error = false;
            $value = 'just testing';

            /*
          do your validation

          if(something) {
            $value = $value;
          } elseif(something else) {
            $error = true;
            $value = $existing_value;
            $field['msg'] = 'your custom error message';
          }
         */

            $return['value'] = $value;
            if ( $error == true ) {
                $return['error'] = $field;
            }

            return $return;
        }
    endif;
