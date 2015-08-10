<?php
/**
  ReduxFramework Sample Config File
  For full documentation, please visit: https://docs.reduxframework.com
 * */
if (!class_exists('Redux_Framework_sample_config')) {

    class Redux_Framework_sample_config {

        public $args        = array();
        public $sections    = array();
        public $theme;
        public $ReduxFramework;

        public function __construct() {

            if (!class_exists('ReduxFramework')) {
                return;
            }

            // This is needed. Bah WordPress bugs.  ;)
            if (  true == Redux_Helpers::isTheme(__FILE__) ) {
                $this->initSettings();
            } else {
                add_action('plugins_loaded', array($this, 'initSettings'), 10);
            }

        }

        public function initSettings() {

            // Just for demo purposes. Not needed per say.
            $this->theme = wp_get_theme();

            // Set the default arguments
            $this->setArguments();

            // Set a few help tabs so you can see how it's done
          //  $this->setHelpTabs();

            // Create the sections and fields
            $this->setSections();

            if (!isset($this->args['opt_name'])) { // No errors please
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

            $this->ReduxFramework = new ReduxFramework($this->sections, $this->args);
        }

        /**

          This is a test function that will let you see when the compiler hook occurs.
          It only runs if a field	set with compiler=>true is changed.

         * */
        function compiler_action($options, $css) {
            //echo '<h1>The compiler hook has run!';
            //print_r($options); //Option values
            //print_r($css); // Compiler selector CSS values  compiler => array( CSS SELECTORS )

            /*
              // Demo of how to use the dynamic CSS and write your own static CSS file
              $filename = dirname(__FILE__) . '/style' . '.css';
              global $wp_filesystem;
              if( empty( $wp_filesystem ) ) {
                require_once( ABSPATH .'/wp-admin/includes/file.php' );
              WP_Filesystem();
              }

              if( $wp_filesystem ) {
                $wp_filesystem->put_contents(
                    $filename,
                    $css,
                    FS_CHMOD_FILE // predefined mode settings for WP files
                );
              }
             */
        }

        /**

          Custom function for filtering the sections array. Good for child themes to override or add to the sections.
          Simply include this function in the child themes functions.php file.

          NOTE: the defined constants for URLs, and directories will NOT be available at this point in a child theme,
          so you must use get_template_directory_uri() if you want to use any of the built in icons

         * */
        function dynamic_section($sections) {
            //$sections = array();
            $sections[] = array(
                'title' => __('Section via hook', 'sopernal-theme'),
                'desc' => __('<p class="description">This is a section created by adding a filter to the sections array. Can be used by child themes to add/remove sections from the options.</p>', 'sopernal-theme'),
                'icon' => 'el-icon-paper-clip',
                // Leave this as a blank section, no options just some intro text set above.
                'fields' => array()
            );

            return $sections;
        }

        /**

          Filter hook for filtering the args. Good for child themes to override or add to the args array. Can also be used in other functions.

         * */
        function change_arguments($args) {
            //$args['dev_mode'] = true;

            return $args;
        }

        /**

          Filter hook for filtering the default value of any given field. Very useful in development mode.

         * */
        function change_defaults($defaults) {
            $defaults['str_replace'] = 'Testing filter hook!';

            return $defaults;
        }

        // Remove the demo link and the notice of integrated demo from the redux-framework plugin
        function remove_demo() {

            // Used to hide the demo mode link from the plugin page. Only used when Redux is a plugin.
            if (class_exists('ReduxFrameworkPlugin')) {
                remove_filter('plugin_row_meta', array(ReduxFrameworkPlugin::instance(), 'plugin_metalinks'), null, 2);

                // Used to hide the activation notice informing users of the demo panel. Only used when Redux is a plugin.
                remove_action('admin_notices', array(ReduxFrameworkPlugin::instance(), 'admin_notices'));
            }
        }

        public function setSections() {

            /**
              Used within different fields. Simply examples. Search for ACTUAL DECLARATION for field examples
             * */
            // Background Patterns Reader
            $sample_patterns_path   = ReduxFramework::$_dir . '../sample/patterns/';
            $sample_patterns_url    = ReduxFramework::$_url . '../sample/patterns/';
            $sample_patterns        = array();

            if (is_dir($sample_patterns_path)) :

                if ($sample_patterns_dir = opendir($sample_patterns_path)) :
                    $sample_patterns = array();

                    while (( $sample_patterns_file = readdir($sample_patterns_dir) ) !== false) {

                        if (stristr($sample_patterns_file, '.png') !== false || stristr($sample_patterns_file, '.jpg') !== false) {
                            $name = explode('.', $sample_patterns_file);
                            $name = str_replace('.' . end($name), '', $sample_patterns_file);
                            $sample_patterns[]  = array('alt' => $name, 'img' => $sample_patterns_url . $sample_patterns_file);
                        }
                    }
                endif;
            endif;

            ob_start();

            $ct             = wp_get_theme();
            $this->theme    = $ct;
            $item_name      = $this->theme->get('Name');
            $tags           = $this->theme->Tags;
            $screenshot     = $this->theme->get_screenshot();
            $class          = $screenshot ? 'has-screenshot' : '';

            $customize_title = sprintf(__('Customize &#8220;%s&#8221;', 'sopernal-theme'), $this->theme->display('Name'));
            
            ?>
            <div id="current-theme" class="<?php echo esc_attr($class); ?>">
            <?php if ($screenshot) : ?>
                <?php if (current_user_can('edit_theme_options')) : ?>
                        <a href="<?php echo wp_customize_url(); ?>" class="load-customize hide-if-no-customize" title="<?php echo esc_attr($customize_title); ?>">
                            <img src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview'); ?>" />
                        </a>
                <?php endif; ?>
                    <img class="hide-if-customize" src="<?php echo esc_url($screenshot); ?>" alt="<?php esc_attr_e('Current theme preview'); ?>" />
                <?php endif; ?>

                <h4><?php echo $this->theme->display('Name'); ?></h4>

                <div>
                    <ul class="theme-info">
                        <li><?php printf(__('By %s', 'sopernal-theme'), $this->theme->display('Author')); ?></li>
                        <li><?php printf(__('Version %s', 'sopernal-theme'), $this->theme->display('Version')); ?></li>
                        <li><?php echo '<strong>' . __('Tags', 'sopernal-theme') . ':</strong> '; ?><?php printf($this->theme->display('Tags')); ?></li>
                    </ul>
                    <p class="theme-description"><?php echo $this->theme->display('Description'); ?></p>
            <?php
            if ($this->theme->parent()) {
                printf(' <p class="howto">' . __('This <a href="%1$s">child theme</a> requires its parent theme, %2$s.') . '</p>', __('http://codex.wordpress.org/Child_Themes', 'sopernal-theme'), $this->theme->parent()->display('Name'));
            }
            ?>

                </div>
            </div>

            <?php
            $item_info = ob_get_contents();

            ob_end_clean();

            $sampleHTML = '';
            if (file_exists(dirname(__FILE__) . '/info-html.html')) {
                /** @global WP_Filesystem_Direct $wp_filesystem  */
                global $wp_filesystem;
                if (empty($wp_filesystem)) {
                    require_once(ABSPATH . '/wp-admin/includes/file.php');
                    WP_Filesystem();
                }
                $sampleHTML = $wp_filesystem->get_contents(dirname(__FILE__) . '/info-html.html');
            }

            // ACTUAL DECLARATION OF SECTIONS
            $this->sections[] = array(
                'title'     => __('General Settings', 'sopernal-theme'),
                'desc'      => __('In this section you can find general options', 'sopernal-theme'),
               // 'icon'      => 'el-icon-home',
				'icon'      => 'el-icon-cogs',
				
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(
					array(
							'id'        => 'amy-divide-g1',
							'type'      => 'info',
							'raw_html'  => true,
							'desc'      => __('<div class="rdinfotitle">Site width</div>', 'sopernal-theme'),
					),
					array(
                        'id'        => 'site-width',
                        'type'      => 'button_set',
                        'title'     => __('Site width', 'sopernal-theme'),
                        'options'   => array(
                            'boxed' => 'Boxed', 
                            'fullwidth' => 'Full width'
                        ),
						'hint'      => array(
                            'title'     => 'Video Help',
                            'content'   => "This is a <b>hint</b> tool-tip for the webFonts field.<br/><br/>Add any HTML based text you like here. <iframe src='//www.youtube.com/embed/c1GC0cJU9Mc?list=UUGRCwQF9REq7yE1S78b8kBQ' width='480px' height='400px' frameborder='0' allowfullscreen></iframe>"),
                        'default'   => 'fullwidth'
                    ),
					array(
							'id'        => 'amy-divide-g2',
							'type'      => 'info',
							'raw_html'  => true,
							'desc'      => __('<div class="rdinfotitle">Header and Footer styles</div>', 'sopernal-theme'),
					),
					array(
                        'id'        => 'menu-position',
                        'type'      => 'button_set',
                        'title'     => __('Menu position', 'sopernal-theme'),
                        'options'   => array(
                            'fixed' => 'Fixed', 
                            'absolute' => 'Absolute'
                        ),
                        'default'   => 'fixed'
                    ),
					array(
                        'id'        => 'menu-width',
                        'type'      => 'button_set',
                        'title'     => __('Menu width', 'sopernal-theme'),
                        'options'   => array(
                            'boxed' => 'Boxed', 
                            'fullwidth' => 'Full width'
                        ),
                        'default'   => 'boxed'
                    ),
					array(
                        'id'    => 'amy-divide-2',
                        'type'  => 'divide'
                    ),
					
					
					array(
                        'id'        => 'footer-position',
                        'type'      => 'button_set',
                        'title'     => __('Footer position', 'sopernal-theme'),
                        'options'   => array(
                            'fixed' => 'Fixed', 
                            'absolute' => 'Absolute'
                        ),
                        'default'   => 'fixed'
                    ),
					array(
                        'id'        => 'footer-width',
                        'type'      => 'button_set',
                        'title'     => __('Footer width', 'sopernal-theme'),
                        'options'   => array(
                            'boxed' => 'Boxed', 
                            'fullwidth' => 'Full width'
                        ),
                        'default'   => 'fullwidth'
                    ),
					array(
							'id'        => 'amy-divide-g7',
							'type'      => 'info',
							'raw_html'  => true,
							'desc'      => __('<div class="rdinfotitle">Other</div>', 'sopernal-theme'),
					),
					array(
                        'id'        => 'future-posts',
                        'type'      => 'switch',
                        'title'     => __('Posts in future', 'sopernal-theme'),
                        'subtitle'  => __('Enable this option if you want to publish posts with future date', 'sopernal-theme'),
                        'default'   => false,
                        'on'        => 'On',
                        'off'       => 'Off',
                    ),
					
					array(
                        'id'                => 'disqus-id',
                        'type'              => 'text',
                        'title'             => __('Disqus ID', 'sopernal-theme'),
                    ),
					array(
                        'id'        => 'menu-search',
                        'type'      => 'switch',
                        'title'     => __('Menu search field', 'sopernal-theme'),
                        'default'   => true,
                        'on'        => 'On',
                        'off'       => 'Off',
                    ),
					array(
							'id'        => 'amy-divide-g3',
							'type'      => 'info',
							'raw_html'  => true,
							'desc'      => __('<div class="rdinfotitle">Logo and fav ico</div>', 'sopernal-theme'),
					),
					array(
						'id' => 'logo-img',
						'type' => 'media',
						'title' => __('Section Logo Image', 'resumi-admin'),
						'subtitle'  => __('200x70px', 'sopernal-theme'),
						'default' => array("url" => get_template_directory_uri() . "/images/logo.png"),
						'preview' => true,
						"url" => true
					),
					array(
						'id' => 'fav-ico',
						'type' => 'media',
						'title' => __('Section favicon', 'resumi-admin'),
						'desc'      => __('You can visit <a href="http://www.favicon.cc">www.favicon.cc</a> to generate your own favicon', 'sopernal-theme'),
						'default' => array("url" => get_template_directory_uri() . "/images/favicon.ico"),
						'preview' => false,
						"url" => true
					),
					array(
							'id'        => 'amy-divide-g4',
							'type'      => 'info',
							'raw_html'  => true,
							'desc'      => __('<div class="rdinfotitle">Welcome message</div>', 'sopernal-theme'),
					),
					array(
                        'id'        => 'welcome-msg',
                        'type'      => 'switch',
                        'title'     => __('Welcome message', 'sopernal-theme'),
                        
                        'default'   => true,
                        'on'        => 'On',
                        'off'       => 'Off',
                    ),
					array(
                        'id'        => 'welcome-msg-text',
                        'type'      => 'editor',
						'required'  => array('welcome-msg', '=', true),
                        'title'     => __('Welcome message text', 'sopernal-theme'),
                        'default'   => '<span class="content-title">Welcome</span>
<span id="input-method">You can add your content here</span>',
                    ),
					array(
							'id'        => 'amy-divide-g5',
							'type'      => 'info',
							'raw_html'  => true,
							'desc'      => __('<div class="rdinfotitle">404 page</div>', 'sopernal-theme'),
					),
					 array(
                        'id'        => '404-page',
                        'type'      => 'select',
                        'data'      => 'pages',
                        'title'     => __('Select 404', 'sopernal-theme'),
                        'subtitle'  => __('First you need to create and then select your 404 page', 'sopernal-theme'),
                    ),
					
					
					array(
							'id'        => 'amy-divide-g6',
							'type'      => 'info',
							'raw_html'  => true,
							'desc'      => __('<div class="rdinfotitle">Custom code</div>', 'sopernal-theme'),
					),
					array(
                        'id'        => 'add-custom-css',
                        'type'      => 'ace_editor',
                        'title'     => __('Custom CSS Code', 'sopernal-theme'),
                        'subtitle'  => __('Paste your CSS code here.', 'sopernal-theme'),
                        'mode'      => 'css',
                        'theme'     => 'monokai',
                        'default'   => ".yourclass{\nmargin: 0 auto;\n}"
                    ),
                    array(
                        'id'        => 'add-custom-js',
                        'type'      => 'ace_editor',
                        'title'     => __('JS Code', 'sopernal-theme'),
                        'subtitle'  => __('Paste your JS code here.', 'sopernal-theme'),
                        'mode'      => 'javascript',
                        'theme'     => 'chrome',
                        'default'   => "jQuery(document).ready(function(){\n\n});"
                    )
                ),
            );
            $this->sections[] = array(
                'type' => 'divide',
            );
			
			 // ACTUAL DECLARATION OF SECTIONS
            $this->sections[] = array(
                'title'     => __('Homepage Slider', 'sopernal-theme'),
                'desc'      => __('Homepage slider options', 'sopernal-theme'),
               // 'icon'      => 'el-icon-home',
				'icon'      => 'el-icon-home',
				
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
				
                'fields'    => array(
					array(
							'id'        => 'amy-divide-h1',
							'type'      => 'info',
							'raw_html'  => true,
							'desc'      => __('<div class="rdinfotitle">Homepage slider styles and content</div>', 'sopernal-theme'),
					),
					 array(
                        'id'        => 'amy-slider-style',
                        'type'      => 'select',
                        'title'     => __('Slider style', 'sopernal-theme'),
                        
                        //Must provide key => value pairs for select options
                        'options'   => array(
                            'classictilt' => 'Circle', 
                            'cube' => 'Cube', 
                            'carousel' => 'Carousel',
							'concave' => 'Concave',
							'coverflow' => 'Coverflow',
							'spiraltop' => 'Spiral top',
							'spiralbottom' => 'Spiral bottom',
							'classic' => 'Classic',
                        ),
                        'default'   => 'classictilt'
                    ),
					
					array(
                        'id'        => 'amy-slider-post-type',
                        'type'      => 'select',
                        'data'      => 'post_type',
                        'title'     => __('Slider post type', 'sopernal-theme'),
						'default'   => 'post'
                    ),
					 array(
                        'id'        => 'amy-slider-cat',
                        'type'      => 'select',
                        'data'      => 'categories',
                        'multi'     => true,
                        'title'     => __('Slider categories', 'sopernal-theme'),
                        'subtitle'      => __('Display only selected categories', 'sopernal-theme'),
                    ),
					 array(
                        'id'        => 'amy-slider-color',
                        'type'      => 'button_set',
                        'title'     => __('Slider color cheme', 'sopernal-theme'),
                        'options'   => array(
                            '' => 'White', 
                            'as_black_style' => 'Dark'
                        ),
                        'default'   => ''
						
                       
                    ),
					array(
                        'id'        => 'amy-slider-hoverfx',
                        'type'      => 'button_set',
                        'title'     => __('Slider hover style', 'sopernal-theme'),
                        'options'   => array(
							'' => 'Triangle',
                            'squares' => 'Squares', 
                            'waves' => 'Waves'
                        ),
                        'default'   => ''
						
                       
                    ),
					array(
                        'id'        => 'amy-slider-parallax',
                        'type'      => 'switch',
                        'title'     => __('Slider parallax effect', 'sopernal-theme'),
                        'subtitle'  => __('It will increase slightly cpu usage', 'sopernal-theme'),
                        'default'   => true,
                        'on'        => 'On',
                        'off'       => 'Off',
                    ),
					array(
                        'id'            => 'amy-slider-parallax-depth',
                        'type'          => 'slider',
						'required'  => array('amy-slider-parallax', '=', true),
                        'title'     => __('Slider parallax depth', 'sopernal-theme'),
                        'default'       => 1.5,
                        'min'           => 0,
                        'step'          => .1,
                        'max'           => 8,
                        'resolution'    => 0.1,
                        'display_value' => 'text'
                    ),
					 array(
                        'id'        => 'amy-slider-excerpt',
                        'type'      => 'spinner',
                        'title'     => __('Slider tile excerpt', 'sopernal-theme'),
                        'subtitle'      => __('Maximum excerpt symbols per post tile', 'sopernal-theme'),
                        'default'   => '150',
                        'min'       => '0',
                        'step'      => '1',
						'max'       => '20000',
                    ),
					
					array(
                        'id'        => 'order-posts',
                        'type'      => 'button_set',
                        'title'     => __('Order posts', 'sopernal-theme'),
                         'options'   => array(
                            '1' => 'Last added first', 
                            '2' => 'Last added last'
                        ),
                        'default'   => '1'
                        
                    ),
						array(
                        'id'        => 'def-pagination-display',
                        'type'      => 'button_set',
						/*'required'  => array('footer-date', '=', true),*/
                        'title'     => __('Pagination style', 'sopernal-theme'),
                        'options'   => array(
                            '1' => 'Pagination', 
                            '2' => 'Infinite Scroll'
                        ),
                        'default'   => '1'
                    ),
					
					
					
					 array(
                        'id'        => 'amy-slider-first-post',
                        'type'      => 'spinner',
                        'title'     => __('Slider first tile', 'sopernal-theme'),
                        'subtitle'      => __('Which tile to be on focus.', 'sopernal-theme'),
                        'default'   => '0',
                        'min'       => '0',
                        'step'      => '1',
						'max'       => '20000',
                    ),
					array(
                        'id'        => 'amy-slider-autorotate',
                        'type'      => 'switch',
                        'title'     => __('Slider slideshow', 'sopernal-theme'),
                        'default'   => false,
                        'on'        => 'On',
                        'off'       => 'Off',
                    ),
					 array(
                        'id'        => 'amy-slider-autorotate-delay',
                        'type'      => 'spinner',
						'required'  => array('amy-slider-autorotate', '=', true),
                        'title'     => __('Slideshow delay in miliseconds', 'sopernal-theme'),
                        'default'   => '3000',
                        'min'       => '1000',
                        'step'      => '1000',
                        'max'       => '20000',
                    ),
				
				),
			);
			
			 // ACTUAL DECLARATION OF SECTIONS
            $this->sections[] = array(
                'title'     => __('Footer Settings', 'sopernal-theme'),
                'desc'      => __('Footer settings', 'sopernal-theme'),
               // 'icon'      => 'el-icon-home',
				'icon'      => 'el-icon-lines',
				
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(
					array(
							'id'        => 'amy-divide-f1',
							'type'      => 'info',
							'raw_html'  => true,
							'desc'      => __('<div class="rdinfotitle">Footer widgets</div>', 'sopernal-theme'),
					),
					array(
                        'id'        => 'footer-date',
                        'type'      => 'switch',
                        'title'     => __('Date widget', 'sopernal-theme'),
                        'default'   => true,
                        'on'        => 'On',
                        'off'       => 'Off',
                    ),
					array(
							'id'        => 'amy-divide-f2',
							'type'      => 'info',
							'raw_html'  => true,
							'desc'      => __('<div class="rdinfotitle">Footer layout</div>', 'sopernal-theme'),
					),
					 array(
                        'id'        => 'footer-layout',
                        'type'      => 'image_select',
                        'title'     => __('Select layout', 'sopernal-theme'),
                        'subtitle'      => __('Each column responds to footer widgets (Footer widget 1, Footer widget 2 etc.)', 'sopernal-theme'),
                        //Must provide key => value(array:title|img) pairs for radio options
                        'options'   => array(
                            '1' => array('alt' => '1 Column',        'img' => ReduxFramework::$_url . '../amy/images/1c.jpg'),
                            '2' => array('alt' => '2 Column Left',   'img' => ReduxFramework::$_url . '../amy/images/12c.jpg'),
                            '3' => array('alt' => '2 Column',  'img' => ReduxFramework::$_url . '../amy/images/22c.jpg'),
                            '4' => array('alt' => '2 Column Right', 'img' => ReduxFramework::$_url . '../amy/images/21c.jpg'),
                            '5' => array('alt' => '3 Column',  'img' => ReduxFramework::$_url . '../amy/images/3c.jpg'),
                        ), 
                        'default' => '5'
                    ),
					array(
							'id'        => 'amy-divide-f3',
							'type'      => 'info',
							'raw_html'  => true,
							'desc'      => __('<div class="rdinfotitle">Footer credits</div>', 'sopernal-theme'),
					),
					array(
                        'id'        => 'footer-text',
                        'type'      => 'editor',
                        'title'     => __('Credits', 'sopernal-theme'),
                        'default'   => '<p><strong>Copyright 2014 </strong><br> Designed by <a href="http://yoursite.com">Your Name</a></p>',
                    ),
					array(
							'id'        => 'amy-divide-f4',
							'type'      => 'info',
							'raw_html'  => true,
							'desc'      => __('<div class="rdinfotitle">Footer social icons</div>', 'sopernal-theme'),
					),
					array(
                        'id'        => 'fb-link-url',
                        'type'      => 'text',
                        'title'     => __('Facebook icon url', 'sopernal-theme'),
						 'desc'      => __('Enter url to show the icon', 'sopernal-theme'),
                        'validate'  => 'url',
                        'default'   => 'https://facebook.com',
                        /*'text_hint' => array(
                            'title'     => '',
                            'content'   => 'Please enter a valid <strong>URL</strong> in this field.'
                        )*/
                    ),
					
					array(
                        'id'        => 'tw-link-url',
                        'type'      => 'text',
                        'title'     => __('Twitter icon url', 'sopernal-theme'),
						 'desc'      => __('Enter url to show the icon', 'sopernal-theme'),
                        'validate'  => 'url',
                        'default'   => 'https://twitter.com',
                    ),
					array(
                        'id'        => 'gp-link-url',
                        'type'      => 'text',
                        'title'     => __('Google+ icon url', 'sopernal-theme'),
						 'desc'      => __('Enter url to show the icon', 'sopernal-theme'),
                        'validate'  => 'url',
                        'default'   => 'https://plus.google.com/',
                    ),
					array(
                        'id'        => 'pi-link-url',
                        'type'      => 'text',
                        'title'     => __('Pinterest icon url', 'sopernal-theme'),
						 'desc'      => __('Enter url to show the icon', 'sopernal-theme'),
                        'validate'  => 'url',
                        'default'   => 'https://www.pinterest.com/',
                    ),
					array(
                        'id'        => 'yt-link-url',
                        'type'      => 'text',
                        'title'     => __('Youtube icon url', 'sopernal-theme'),
						 'desc'      => __('Enter url to show the icon', 'sopernal-theme'),
                        'validate'  => 'url',
                        'default'   => '',
                    )
                )
            );
			
			 // ACTUAL DECLARATION OF SECTIONS
            $this->sections[] = array(
                'title'     => __('Background Options', 'sopernal-theme'),
                'desc'      => __('Add background, slideshow or videoto to your site ', 'sopernal-theme'),
               // 'icon'      => 'el-icon-home',
				'icon'      => 'el-icon-picture',
				
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(
				array(
							'id'        => 'amy-divide-b1',
							'type'      => 'info',
							'raw_html'  => true,
							'desc'      => __('<div class="rdinfotitle">Full width site background</div>', 'sopernal-theme'),
				),
				  array(
                        'id'        => 'def-background',
                        'type'      => 'background',
                        'output'    => array('body'),
                        'title'     => __('Background', 'sopernal-theme'),
                        'subtitle'  => __('This settings will apply for all pages', 'sopernal-theme'),
                        'default'   => array("background-color" => '#F1F1F1'),
						
						//'default' => array("background-image" => get_template_directory_uri() . "/images/logo.png"),
                    ),
					array(
							'id'        => 'amy-divide-b2',
							'type'      => 'info',
							'raw_html'  => true,
							'desc'      => __('<div class="rdinfotitle">Boxed site background</div>', 'sopernal-theme'),
					),
					array(
                        'id'        => 'def-background-boxed-opa',
                        'type'      => 'color_rgba',
                        'title'     => __('Boxed style background color', 'sopernal-theme'),
						'subtitle'      => __('You can add opacity background color', 'sopernal-theme'),
                        'default'   => array('color' => '#F1F1F1', 'alpha' => '1'),
                        'output'    => array('.boxedstyle .header-top-bg'),
                        'mode'      => 'background-color',
                        'validate'  => 'colorrgba',
                    ),
					array(
                        'id'        => 'def-background-boxed',
                        'type'      => 'background',
                        'output'    => array('.ss-container'),
						//'output'    => array('.header-top-p.boxedstyle'),
						'background-color'         => false,
                        'title'     => __('Boxed style background', 'sopernal-theme'),
                        'subtitle'  => __('This settings will apply for all pages', 'sopernal-theme'),
                        'default'   => array("background-color" => '#F1F1F1'),
						
						//'default' => array("background-image" => get_template_directory_uri() . "/images/logo.png"),
                    ),
					
					array(
							'id'        => 'amy-divide-b3',
							'type'      => 'info',
							'raw_html'  => true,
							'desc'      => __('<div class="rdinfotitle">LayerSlider background</div>', 'sopernal-theme'),
					),
					array(
                        'id'		=> 'layerslider-bg',
                        'type'		=> 'text',
                        'title'		=> __('LayerSlider background', 'sopernal-theme'),
                        'desc'		=> __('Add layerslider shortcode. Example: <span style="color:#777;">[layerslider id="5"]', 'sopernal-theme')
                    ),
					array(
							'id'        => 'amy-divide-b4',
							'type'      => 'info',
							'raw_html'  => true,
							'desc'      => __('<div class="rdinfotitle">Video background</div>', 'sopernal-theme'),
					),
					
					array(
                        'id'        => 'yt-active',
                        'type'      => 'switch',
                        'title'     => __('Activate Youtube background video', 'sopernal-theme'),
						'subtitle'      => __('For default homepage and boxed style site', 'sopernal-theme'),
                        'default'   => false,
                        'on'        => 'On',
                        'off'       => 'Off',
                    ),
					array(
                        'id'		=> 'yt-bg-id',
                        'type'		=> 'text',
						'required'  => array('yt-active', '=', true),
                        'title'		=> __('Youtube ID', 'sopernal-theme'),
                        'desc'		=> __('Add only the YT video ID <span style="color:#ddd;">http://www.youtube.com/watch?v=</span><strong>WhBoR_tgXCI</strong>', 'sopernal-theme')
                    ),
					array(
                        'id'        => 'yt-bg-cotrols',
                        'type'      => 'switch',
						'required'  => array('yt-active', '=', true),
                        'title'     => __('Video controls', 'sopernal-theme'),
                        'default'   => true,
                        'on'        => 'On',
                        'off'       => 'Off',
                    ),
					array(
                        'id'        => 'yt-bg-mute',
                        'type'      => 'button_set',
						'required'  => array('yt-active', '=', true),
                        'title'     => __('Mute video', 'sopernal-theme'),
                        'options'   => array(
							'true' => 'Muted',
                            'false' => 'Unmuted'
                        ),
                        'default'   => 'false'
                    ),
					array(
                        'id'        => 'yt-bg-repeat',
                        'type'      => 'button_set',
						'required'  => array('yt-active', '=', true),
                        'title'     => __('Rpeat video', 'sopernal-theme'),
                        'options'   => array(
							'true' => 'Rpeat',
                            'false' => 'Play once'
                        ),
                        'default'   => 'true'
                    ),
					array(
                        'id'        => 'yt-bg-start',
                        'type'      => 'spinner',
						'required'  => array('yt-active', '=', true),
                        'title'     => __('Start video at second', 'sopernal-theme'),
                        'default'   => '0',
                        'min'       => '0',
                        'step'      => '1',
						'max'		=> '99999999'
                    ),
					array(
							'id'        => 'amy-divide-b4',
							'type'      => 'info',
							'raw_html'  => true,
							'desc'      => __('<div class="rdinfotitle">Slideshow background</div>', 'sopernal-theme'),
					),
					array(
                        'id'        => 'bg-active',
                        'type'      => 'switch',
                        'title'     => __('Activate bacgkround slideshow', 'sopernal-theme'),
                        'default'   => false,
                        'on'        => 'On',
                        'off'       => 'Off',
                    ),
					array(
                        'id'        => 'bg-allpages',
                        'type'      => 'button_set',
						'required'  => array('bg-active', '=', true),
                        'title'     => __('Vertical alignment', 'sopernal-theme'),
                        'options'   => array(
							'1' => 'All pages',
							'0' => 'Homepage',
                        ),
                        'default'   => '0'
                    ),
					array(
                        'id'        => 'bg-overlays',
                        'type'      => 'image_select',
                     	'required'  => array('bg-active', '=', true),
                        'title'     => __('Bacgkroudn overlay', 'sopernal-theme'),
                        
                        'default'   => 16,
                        'options'   => array(
                            '01' => get_template_directory_uri() . "/images/adminico/p-01.jpg",
                          	'02' => get_template_directory_uri() . "/images/adminico/p-02.jpg",
							'03' => get_template_directory_uri() . "/images/adminico/p-03.jpg",
							'04' => get_template_directory_uri() . "/images/adminico/p-04.jpg",
							'05' => get_template_directory_uri() . "/images/adminico/p-05.jpg",
							'06' => get_template_directory_uri() . "/images/adminico/p-06.jpg",
							'07' => get_template_directory_uri() . "/images/adminico/p-07.jpg",
							'08' => get_template_directory_uri() . "/images/adminico/p-08.jpg",
							'09' => get_template_directory_uri() . "/images/adminico/p-09.jpg",
							'10' => get_template_directory_uri() . "/images/adminico/p-10.jpg",
							'11' => get_template_directory_uri() . "/images/adminico/p-11.jpg",
							'12' => get_template_directory_uri() . "/images/adminico/p-12.jpg",
							'13' => get_template_directory_uri() . "/images/adminico/p-13.jpg",
							'14' => get_template_directory_uri() . "/images/adminico/p-14.jpg",
							'15' => get_template_directory_uri() . "/images/adminico/p-15.jpg",
							'16' => get_template_directory_uri() . "/images/adminico/p-16.png",
                        ),
                    ),
					array(
                        'id'        => 'bg-delay',
                        'type'      => 'spinner',
						'required'  => array('bg-active', '=', true),
                        'title'     => __('Slideshow delay in miliseconds', 'sopernal-theme'),
                        'default'   => '6000',
                        'min'       => '1000',
                        'step'      => '1000',
                        'max'       => '20000',
                    ),
					array(
                        'id'        => 'bg-fade',
                        'type'      => 'spinner',
						'required'  => array('bg-active', '=', true),
                        'title'     => __('Fade out speed in miliseconds', 'sopernal-theme'),
                        'default'   => '1000',
                        'min'       => '1000',
                        'step'      => '1000',
                        'max'       => '10000',
                    ),
					array(
                        'id'        => 'bg-vposition',
                        'type'      => 'button_set',
						'required'  => array('bg-active', '=', true),
                        'title'     => __('Vertical alignment', 'sopernal-theme'),
                        'options'   => array(
							'top' => 'Top',
							'center' => 'Center',
							'bottom' => 'Bottom'
                        ),
                        'default'   => 'center'
                    ),
					array(
                        'id'        => 'bg-hposition',
                        'type'      => 'button_set',
						'required'  => array('bg-active', '=', true),
                        'title'     => __('Horizontal alignment', 'sopernal-theme'),
                        'options'   => array(
							'left' => 'Left',
							'center' => 'Center',
							'right' => 'Right'
                        ),
                        'default'   => 'center'
                    ),
					
					  array(
                        'id'        => 'bg-slides',
                        'type'      => 'slides',
						'required'  => array('bg-active', '=', true),
                        'title'     => __('Add backgroudn images', 'sopernal-theme'),
						'default' => array("image" => get_template_directory_uri() . "/images/logo.png"),
                      
                    ),

					
                )
            );
			
			 $this->sections[] = array(
                'title'     => __('Styling Options', 'sopernal-theme'),
                'desc'      => __('Change fonts and colors', 'sopernal-theme'),
               // 'icon'      => 'el-icon-home',
				'icon'      => 'el-icon-adjust-alt',
				
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(
				   array(
							'id'        => 'amy-divide-s30',
							'type'      => 'info',
							'raw_html'  => true,
							'desc'      => __('<div class="rdinfotitle">Main color scheme</div>', 'sopernal-theme'),
					),
					array(
                        'id'        => 'body-color-scheme',
                        'type'      => 'image_select',
                        'title'     => __('Header and footer color scheme', 'sopernal-theme'),
                        
                        'default'   => 'amethyst',
                       
                        'options'   => array(
                            'turquoise' => get_template_directory_uri() . "/images/options/01.jpg",
                          	'greensea' => get_template_directory_uri() . "/images/options/02.jpg",
							'emerald' => get_template_directory_uri() . "/images/options/03.jpg",
							'nephritis' => get_template_directory_uri() . "/images/options/04.jpg",
							'peterriver' => get_template_directory_uri() . "/images/options/05.jpg",
							'belizehole' => get_template_directory_uri() . "/images/options/06.jpg",
							'amethyst' => get_template_directory_uri() . "/images/options/07.jpg",
							'wisteria' => get_template_directory_uri() . "/images/options/08.jpg",
							'wetasphalt' => get_template_directory_uri() . "/images/options/09.jpg",
							'midnightblue' => get_template_directory_uri() . "/images/options/10.jpg",
							'sunflower' => get_template_directory_uri() . "/images/options/11.jpg",
							'orange' => get_template_directory_uri() . "/images/options/12.jpg",
							'carrot' => get_template_directory_uri() . "/images/options/13.jpg",
							'pumpkin' => get_template_directory_uri() . "/images/options/14.jpg",
							'alizarin' => get_template_directory_uri() . "/images/options/15.jpg",
							'pomegranate' => get_template_directory_uri() . "/images/options/16.jpg",
							'concrete' => get_template_directory_uri() . "/images/options/17.jpg",
							'asbestos' => get_template_directory_uri() . "/images/options/18.jpg",
                        ),
                    ),
					array(
							'id'        => 'amy-divide-s31',
							'type'      => 'info',
							'raw_html'  => true,
							'desc'      => __('<div class="rdinfotitle">Main fonts</div>', 'sopernal-theme'),
					),
					
					array(
                        'id'        => 'body-typography',
                        'type'      => 'typography',
                        'title'     => __('Body Font', 'sopernal-theme'),
                        'google'    => true,
						'font-backup'   => true,
                        'line-height'   => false,
						'text-align'	=> false,
						'output'        => array('body', 'input', 'textarea', 'select'),
                        'default'   => array(
							'color' => '#777777',
							'font-style'    => '400',
                            'font-family'   => 'Open Sans',
                            'google'        => true,
                            'font-size'     => '12px',
							 
                        ),
                    ),
					array(
                        'id'        => 'headings-typography',
                        'type'      => 'typography',
                        'title'     => __('Headings', 'sopernal-theme'),
                        'google'    => true,
						'font-backup'   => true,
                        'line-height'   => false,
						'text-align'	=> false,
						'color'         => false,
						'font-size'     => false,
						'output'        => array('h1','h2','h3','h4','h5','h6'),
                        'default'   => array(
							'font-style'    => '300',
                            'font-family'   => 'Open Sans',
                            'google'        => true,
                          
                        ),
                    ),
					array(
                        'id'        => 'titles-typography',
                        'type'      => 'typography',
                        'title'     => __('Titles', 'sopernal-theme'),
                        'google'    => true,
						'font-backup'   => true,
                        'line-height'   => false,
						'text-align'	=> false,
						'color'         => false,
						'font-size'     => false,
						'output'        => array('.content-title', '.content-title a', '.comment-reply-title', '.h-style h2',  'h2.wpb_heading'),
                        'default'   => array(
							'font-style'    => '300',
                            'font-family'   => 'Open Sans',
                            'google'        => true,
                          
                        ),
                    ),
					array(
                        'id'        => 'custom-typography',
                        'type'      => 'typography',
                        'title'     => __('Coustom font', 'sopernal-theme'),
                        'google'    => true,
						'font-backup'   => true,
                        'line-height'   => false,
						'text-align'	=> false,
						'color'         => false,
						'font-size'     => false,
                        'default'   => array(
							'font-style'    => '300',
                            'font-family'   => 'Open Sans',
                            'google'        => true,
                          
                        ),
                    ),
					array(
							'id'        => 'amy-divide-s30',
							'type'      => 'info',
							'raw_html'  => true,
							'desc'      => __('<div class="rdinfotitle">Menu</div>', 'sopernal-theme'),
					),
				array(
                        'id'        => 'menu-background',
                        'type'      => 'color_rgba',
                        'title'     => __('Menu background color', 'sopernal-theme'),
                        'default'   => array('color' => '#ffffff', 'alpha' => '0.96'),
                        'output'    => array('.header-white'),
                        'mode'      => 'background',						
                        'validate'  => 'colorrgba',
                    ),
					array(
                        'id'        => 'menu-background-gradient',
                        'type'      => 'switch',
                        'title'     => __('Menu background gradient', 'sopernal-theme'),
                        'default'   => false,
                        'on'        => 'On',
                        'off'       => 'Off',
                    ),
					array(
                        'id'        => 'menu-background-gradient-height',
                        'type'      => 'spinner',
						'required'  => array('menu-background-gradient', '=', true),
                        'title'     => __('Gradient height', 'sopernal-theme'),
                        'default'   => '100',
                        'min'       => '0',
                        'step'      => '1',
						'max'		=> '500'
                    ),
					array(
                        'id'        => 'menu-background-shadow',
                        'type'      => 'switch',
                        'title'     => __('Menu background shadow', 'sopernal-theme'),
                        'default'   => true,
                        'on'        => 'On',
                        'off'       => 'Off',
                    ),
					array(
                        'id'            => 'menu-typography',
                        'type'          => 'typography',
                        'title'         => __('Menu typography', 'sopernal-theme'),
                        //'compiler'      => true,  // Use if you want to hook in your own CSS compiler
                        'google'        => true,    // Disable google fonts. Won't work if you haven't defined your google api key
                        'font-backup'   => true,    // Select a backup non-google font in addition to a google font
                        //'font-style'    => false, // Includes font-style and weight. Can use font-style or font-weight to declare
                        //'subsets'       => false, // Only appears if google is true and subsets not set to false
                        //'font-size'     => false,
                        'line-height'   => true,
						'text-align'=>false,
                        //'word-spacing'  => true,  // Defaults to false
                        //'letter-spacing'=> true,  // Defaults to false
                        //'color'         => false,
                        //'preview'       => false, // Disable the previewer
                        'all_styles'    => true,    // Enable all Google Font style/weight variations to be added to the page
                        'output'        => array('.nav li a', '.responsivemenu  a', "#navs",'.searchmenu:after'), // An array of CSS selectors to apply this font style to dynamically
                        //'compiler'      => array('h2.site-description-compiler'), // An array of CSS selectors to apply this font style to dynamically
                        'units'         => 'px', 
                        'default'       => array(
                            'font-style'    => '300',
                            'font-family'   => 'Open Sans',
                            'google'        => true,
							'color'        => '#777777',
                            'font-size'     => '13px',
							'line-height'   => '18px'
							),
                    ),
					array(
                        'id'        => 'amy-divide-s70',
                        'type'      => 'info',
                        'raw_html'  => true,
                        'desc'      => __('<div class="rdinfotitle">Sub-Menu</div>', 'sopernal-theme'),
                    ),
					array(
                        'id'        => 'submenu-background',
                        'type'      => 'color_rgba',
                        'title'     => __('Sub-menu background color', 'sopernal-theme'),
                        'default'   => array('color' => '#282828', 'alpha' => '0.90'),
                        'output'    => array('.nav li ul'),
                        'mode'      => 'background',
                        'validate'  => 'colorrgba',
                    ),
					array(
                        'id'        => 'submenu-border',
                        'type'      => 'border',
						'all'      => false,
						'right'      => false,
						'top'      => false,
						'left'      => false,
						'color'		=> false,
                        'title'     => __('Sub-menu borders', 'sopernal-theme'),
                        'output'    => array('.nav ul li'),
                        'default'   => array(
                            'border-color'  => '#333333', 
                            'border-style'  => 'solid', 
                            'border-bottom' => '1px', 
                        )
                    ),
					array(
                        'id'        => 'submenu-border-color',
                        'type'      => 'color_rgba',
                        'title'     => __('Sub-menu borders color', 'sopernal-theme'),
                        'default'   => array('color' => '#333333', 'alpha' => '1.0'),
                        'output'    => array('.nav ul li'),
                        'mode'      => 'border-color',
                        'validate'  => 'colorrgba',
                    ),
					
					
					
					array(
                        'id'            => 'submenu-typography',
                        'type'          => 'typography',
                        'title'         => __('Sub-menu typography', 'sopernal-theme'),
                        'google'        => true,
                        'font-backup'   => true, 
                        'line-height'   => false,
						'text-align'	=> false,
                        //'color'         => false,
                        'all_styles'    => true,    // Enable all Google Font style/weight variations to be added to the page
                        'output'        => array('.nav ul li a:link','.nav ul li a:visited'), // An array of CSS selectors to apply this font style to dynamically
                        'units'         => 'px',
                        'default'       => array(
                            'font-style'    => '300',
                            'font-family'   => 'Open Sans',
                            'google'        => true,
							'color'        => '#c9c9c9',
                            'font-size'     => '11px'
							),
                    ),
					array(
                        'id'        => 'amy-divide-s69',
                        'type'      => 'info',
                        'raw_html'  => true,
                        'desc'      => __('<div class="rdinfotitle">Widgets</div>', 'sopernal-theme'),
                    ),
					array(
                        'id'        => 'widget-typography',
                        'type'      => 'typography',
                        'title'     => __('Widget titles', 'sopernal-theme'),
                        'subtitle'  => __('Specify the body font properties.', 'sopernal-theme'),
                        'google'    => true,
						'font-backup'   => true,
                        'line-height'   => false,
						'text-align'	=> false,
						'color'         => false,
						'output'        => array('.widgettitle'),
                        'default'   => array(
							'font-style'    => '400',
                            'font-family'   => 'Open Sans',
                            'google'        => true,
                            'font-size'     => '18px'
                        ),
                    ),
					
					array(
                        'id'        => 'widget-border',
                        'type'      => 'border',
						'all'      => false,
						'right'      => false,
						'top'      => false,
						'left'      => false,
						'color'      => false,
                        'title'     => __('Widgets bottom borders', 'sopernal-theme'),
                        'output'    => array('.widget li'),
                        'default'   => array(
                            'border-color'  => '#e4e4e4', 
                            'border-style'  => 'solid', 
                            'border-bottom' => '1px', 
                        )
                    ),
					array(
                        'id'        => 'widget-border-color',
                        'type'      => 'color_rgba',
                        'title'     => __('Widgets bottom borders color', 'sopernal-theme'),
                        'default'   => array('color' => '#e4e4e4', 'alpha' => '1.0'),
                        'output'    => array('.widget li'),
                        'mode'      => 'border-color',
                        'validate'  => 'colorrgba',
                    ),
					
					
					
					array(
                        'id'        => 'amy-divide-s59',
                        'type'      => 'info',
                        'raw_html'  => true,
                        'desc'      => __('<div class="rdinfotitle">Default page padding</div>', 'sopernal-theme'),
                    ),
					 array(
                        'id'            => 'page-spacing',
                        'type'          => 'spacing',
                        'output'        => array('#tt-h-one','#tt-h-two'), // An array of CSS selectors to apply this font style to
                        'mode'          => 'margin',    // absolute, padding, margin, defaults to padding
                        'all'           => false,        // Have one field that applies to all
                        //'top'           => false,     // Disable the top
                        'right'         => false,     // Disable the right
                        //'bottom'        => false,     // Disable the bottom
                        'left'          => false,     // Disable the left
                        'units'         => 'px',      // You can specify a unit value. Possible: px, em, %
                        //'units_extended'=> 'true',    // Allow users to select any type of unit
                        //'display_units' => 'false',   // Set to false to hide the units if the units are specified
                        'title'         => __('Page padding', 'sopernal-theme'),
                        'subtitle'      => __('Add top and bottom padding to all pages.', 'sopernal-theme'),
                       
                        'default'       => array(
                            'margin-top'    => '60px', 
                            'margin-bottom' => '60px'
                        )
                    ),
					array(
                        'id'        => 'amy-divide-s60',
                        'type'      => 'info',
                        'raw_html'  => true,
                        'desc'      => __('<div class="rdinfotitle">Comments background</div>', 'sopernal-theme'),
                    ),
					 array(
                        'id'        => 'commnets-background',
                        'type'      => 'color_rgba',
                        'title'     => __('Background color', 'sopernal-theme'),
                        'default'   => array('color' => '#f9f9f9', 'alpha' => '1.0'),
                        'output'    => array('.fb-holder .container-border','.comment-body','body .woocommerce #payment'),
                        'mode'      => 'background',
                        'validate'  => 'colorrgba',
                    ),
					array(
                        'id'        => 'amy-divide-s61',
                        'type'      => 'info',
                        'raw_html'  => true,
                        'desc'      => __('<div class="rdinfotitle">Input fields</div>', 'sopernal-theme'),
                    ),
					 array(
                        'id'        => 'input-filed-background',
                        'type'      => 'color_rgba',
                        'title'     => __('Input fields background color', 'sopernal-theme'),
                        'default'   => array('color' => '#f9f9f9', 'alpha' => '1.0'),
                        'output'    => array('#commentform textarea','#commentform input', '.wpcf7-form input', '.wpcf7-form textarea', 'body .woocommerce #payment'),
                        'mode'      => 'background',
                        'validate'  => 'colorrgba',
                    ),
					
					array(
                        'id'        => 'input-filed-border',
                        'type'      => 'border',
						'all'      => true,
						'color'		=>false,
                        'title'     => __('Input fields border', 'sopernal-theme'),
                        'output'    => array('#commentform textarea','#commentform input','.wpcf7-form input', '.wpcf7-form textarea','#s'), // An array of CSS selectors to apply this font style to
                       
                        'default'   => array(
                            'border-color'  => '#ececec', 
                            'border-style'  => 'solid', 
							'border-top'    => '1px', 
                            'border-right'  => '1px', 
                            'border-bottom' => '1px', 
                            'border-left'   => '1px'
                        )
                    ),
					array(
                        'id'        => 'input-text-color',
                        'type'      => 'color_rgba',
                        'title'     => __('Input fields color', 'sopernal-theme'),
                        'default'   => array('color' => '#777777', 'alpha' => '1.0'),
                        'output'    => array('#commentform textarea','#commentform input','.wpcf7-form input', '.wpcf7-form textarea','#s'),
                        'mode'      => 'color',
                        'validate'  => 'colorrgba',
                    ),
					array(
                        'id'        => 'input-border-color',
                        'type'      => 'color_rgba',
                        'title'     => __('Input fields border color', 'sopernal-theme'),
                        'default'   => array('color' => '#ececec', 'alpha' => '0.5'),
                        'output'    => array('#commentform textarea','#commentform input','.wpcf7-form input', '.wpcf7-form textarea','#s'),
                        'mode'      => 'border-color',
                        'validate'  => 'colorrgba',
                    ),
					array(
                        'id'        => 'amy-divide-s62',
                        'type'      => 'info',
                        'raw_html'  => true,
                        'desc'      => __('<div class="rdinfotitle">Footer</div>', 'sopernal-theme'),
                    ),
					array(
                        'id'        => 'path-background',
                        'type'      => 'color_rgba',
                        'title'     => __('Footer sitepath background color', 'sopernal-theme'),
                        
                        'default'   => array('color' => '#ffffff', 'alpha' => '0.85'),
                        'output'    => array('.breadcrumbs'),
                        'mode'      => 'background',
                        'validate'  => 'colorrgba',
                    ),
					
					array(
                        'id'        => 'footer-background',
                        'type'      => 'color_rgba',
                        'title'     => __('Footer background color', 'sopernal-theme'),
                        
                        'default'   => array('color' => '#ffffff', 'alpha' => '0.90'),
                        'output'    => array('#footer'),
                        'mode'      => 'background',
                        'validate'  => 'colorrgba',
                    ),
					
					
					array(
                        'id'        => 'footer-background-text',
                        'type'      => 'typography',
                        'title'     => __('Footer text color', 'sopernal-theme'),
                        'google'    => false,
						'font-backup'   => false,
                        'line-height'   => false,
						'text-align'	=> false,
						'font-weight'	=> false,
						'font-family'	=> false,
						'line-height'   => false,
						'all_styles'    => false,  
						'font-size'	=> false,
						'output'        => array('.navkey','.date-time','.copyrholder', '.numpostcontent'),
                        'default'   => array(
							'color' => '#777777',
                        ),
                    ),
					
					
					array(
                        'id'        => 'footer-background-widget',
                        'type'      => 'color_rgba',
                        'title'     => __('Footer widget area background color', 'sopernal-theme'),
                        
                        'default'   => array('color' => '#282828', 'alpha' => '0.95'),
                        'output'    => array('.footerwidget'),
                        'mode'      => 'background',
                        'validate'  => 'colorrgba',
                    ),
					array(
                        'id'        => 'footer-widget-border',
                        'type'      => 'border',
						'all'      => false,
						'right'      => false,
						'top'      => false,
						'left'      => false,
                        'title'     => __('Footer widgets bottom borders', 'sopernal-theme'),
                        'output'    => array('#footer .widget li'),
                        'default'   => array(
                            'border-color'  => '#4f4f4f', 
                            'border-style'  => 'solid', 
                            'border-bottom' => '1px', 
                        )
                    ),
					array(
                        'id'        => 'footer-typography',
                        'type'      => 'typography',
                        'title'     => __('Footer typography', 'sopernal-theme'),
                        'google'    => true,
						'font-backup'   => true,
                        'line-height'   => false,
						'text-align'	=> false,
						'output'        => array('#footer'),
                        'default'   => array(
							'color' => '#a9a9a9',
							'font-style'    => '400',
                            'font-family'   => 'Open Sans',
                            'google'        => true,
                            'font-size'     => '12px'
                        ),
                    ),
					
					array(
                        'id'        => 'amy-divide-s67',
                        'type'      => 'info',
                        'raw_html'  => true,
                        'desc'      => __('<div class="rdinfotitle">Loading bacground color</div>', 'sopernal-theme'),
                    ),
					array(
                        'id'        => 'loading-bacground',
                        'type'      => 'color_rgba',
                        'title'     => __('Color', 'sopernal-theme'),
                        
                        'default'   => array('color' => '#fefefe', 'alpha' => '1'),
                        'output'    => array('.loadbg'),
                        'mode'      => 'background',
                        'validate'  => 'colorrgba',
                    ),
					
					array(
                        'id'        => 'amy-divide-s47',
                        'type'      => 'info',
                        'raw_html'  => true,
                        'desc'      => __('<div class="rdinfotitle">Big images bacground color</div>', 'sopernal-theme'),
                    ),
					array(
                        'id'        => 'img-bacground',
                        'type'      => 'color_rgba',
                        'title'     => __('Color', 'sopernal-theme'),
                        
                        'default'   => array('color' => '#ffffff', 'alpha' => '0.9'),
                        'output'    => array('div.pp_overlay'),
                        'mode'      => 'background',
                        'validate'  => 'colorrgba',
                    ),
					
					
					
					
					array(
                        'id'        => 'amy-divide-w1',
                        'type'      => 'info',
                        'raw_html'  => true,
                        'desc'      => __('<div class="rdinfotitle">WooCommerce messages</div>', 'sopernal-theme'),
                    ),
					array(
                        'id'        => 'woo-errmsg-bg',
                        'type'      => 'color_rgba',
                        'title'     => __('Messages backround color', 'sopernal-theme'),
                        'default'   => array('color' => '#f9f9f9', 'alpha' => '1.0'),
                        'output'    => array('.woocommerce .woocommerce-error','.woocommerce .woocommerce-message', '.woocommerce .woocommerce-info'),
                        'mode'      => 'background',
                        'validate'  => 'colorrgba',
                    ),
					
					array(
                        'id'        => 'woo-errmsg-text',
                        'type'      => 'color_rgba',
                        'title'     => __('Messages text color', 'sopernal-theme'),
                        'default'   => array('color' => '#777777', 'alpha' => '1.0'),
                        'output'    => array('.woocommerce .woocommerce-error','.woocommerce .woocommerce-message', '.woocommerce .woocommerce-info'),
                        'mode'      => 'color',
                        'validate'  => 'colorrgba',
                    ),
					
					
					array(
                        'id'        => 'amy-divide-w2',
                        'type'      => 'info',
                        'raw_html'  => true,
                        'desc'      => __('<div class="rdinfotitle">WooCommerce input fields</div>', 'sopernal-theme'),
                    ),
					 array(
                        'id'        => 'woo-input-bg',
                        'type'      => 'color_rgba',
                        'title'     => __('Input fields background color', 'sopernal-theme'),
                        'default'   => array('color' => '#f9f9f9', 'alpha' => '1.0'),
                        'output'    => array('#customer_details input','#customer_details textarea','.chosen-container-single .chosen-single','.chosen-container .chosen-drop','.chosen-container-active.chosen-with-drop .chosen-single','.woocommerce input.input-text','.woocommerce #content .quantity input.qty'),
                        'mode'      => 'background',
                        'validate'  => 'colorrgba',
                    ),
					array(
                        'id'        => 'woo-input-border',
                        'type'      => 'border',
						'all'      => true,
						'color' => false,
                        'title'     => __('Input fields border', 'sopernal-theme'),
                        'output'    => array('#customer_details input','#customer_details textarea','.chosen-container-single .chosen-single','.chosen-container .chosen-drop','.chosen-container-active.chosen-with-drop .chosen-single','.woocommerce input.input-text','.woocommerce #content .quantity input.qty'), // An array of CSS selectors to apply this font style to
                       
                        'default'   => array(
                            'border-color'  => '#ececec', 
                            'border-style'  => 'solid', 
							'border-top'    => '1px', 
                            'border-right'  => '1px', 
                            'border-bottom' => '1px', 
                            'border-left'   => '1px'
                        )
                    ),
					array(
                        'id'        => 'woo-input-border-color',
                        'type'      => 'color_rgba',
                        'title'     => __('Input fields border color', 'sopernal-theme'),
                        'default'   => array('color' => '#ececec', 'alpha' => '1.0'),
                        'output'    => array('#customer_details input','#customer_details textarea','.chosen-container-single .chosen-single','.chosen-container .chosen-drop','.chosen-container-active.chosen-with-drop .chosen-single','.woocommerce input.input-text','.woocommerce #content .quantity input.qty'),
                        'mode'      => 'border-color',
                        'validate'  => 'colorrgba',
                    ),
					array(
                        'id'        => 'woo-input-color',
                        'type'      => 'color_rgba',
                        'title'     => __('Input fields color', 'sopernal-theme'),
                        'default'   => array('color' => '#777777', 'alpha' => '1.0'),
                        'output'    => array('#customer_details input','#customer_details textarea','.chosen-container-single .chosen-single','.chosen-container .chosen-drop','.chosen-container-active.chosen-with-drop .chosen-single','.woocommerce input.input-text', '.woocommerce #content .quantity input.qty'),
                        'mode'      => 'color',
                        'validate'  => 'colorrgba',
                    ),
					
					array(
                        'id'        => 'woo-element-border',
                        'type'      => 'color_rgba',
                        'title'     => __('Forms border color', 'sopernal-theme'),
                        'default'   => array('color' => '#ececec', 'alpha' => '1.0'),
                        'output'    => array('tr th', 'tr td','tr','.woocommerce table.shop_table','.woocommerce form.checkout_coupon', '.woocommerce form.login', '.woocommerce form.register', '.woocommerce-page form.checkout_coupon', '.woocommerce-page form.login', '.woocommerce-page form.register','.woocommerce table.shop_attributes','.woocommerce  table.shop_attributes td','.woocommerce table.shop_table td', '.woocommerce-page table.shop_table td'),
                        'mode'      => 'border-color',
                        'validate'  => 'colorrgba',
                    ),
				
					
					
					
				
					
					
				
					
                )
            );
			 $this->sections[] = array(
                'title'     => __('Translation', 'sopernal-theme'),
               // 'icon'      => 'el-icon-home',
				'icon'      => 'el-icon-globe',
				
                // 'submenu' => false, // Setting submenu to false on a given section will hide it from the WordPress sidebar menu!
                'fields'    => array(
				
					array(
                        'id'        => 'amy-divide-t1',
                        'type'      => 'info',
                        'raw_html'  => true,
                        'desc'      => __('<div class="rdinfotitle">Menu</div>', 'sopernal-theme'),
                    ),
					array(
                        'id'                => 'tr-resmenu-button',
                        'type'              => 'text',
                        'title'             => __('Responsive menu button label', 'sopernal-theme'),
						'default'   => 'Menu'
                    ),
					array(
                        'id'                => 'tr-resmenu-title',
                        'type'              => 'text',
                        'title'             => __('Responsive menu header', 'sopernal-theme'),
						'default'   => 'Menu'
                    ),
					array(
                        'id'                => 'tr-menu-search',
                        'type'              => 'text',
                        'title'             => __('Search placeholder', 'sopernal-theme'),
						'default'   => 'Search...'
                    ),
					array(
                        'id'        => 'amy-divide-t4',
                        'type'      => 'info',
                        'raw_html'  => true,
                        'desc'      => __('<div class="rdinfotitle">Footer post counter</div>', 'sopernal-theme'),
                    ),
					array(
                        'id'                => 'tr-footer-home-info',
                        'type'              => 'text',
                        'title'             => __('For homepage', 'sopernal-theme'),
						'default'   => 'posts<br>at home page'
                    ),
					array(
                        'id'                => 'tr-footer-search-info',
                        'type'              => 'text',
                        'title'             => __('For search', 'sopernal-theme'),
						'default'   => 'results<br>found'
                    ),
					array(
                        'id'                => 'tr-footer-archive-info',
                        'type'              => 'text',
                        'title'             => __('For archive', 'sopernal-theme'),
						'default'   => 'posts<br>in archive'
                    ),
					array(
                        'id'                => 'tr-footer-category-info',
                        'type'              => 'text',
                        'title'             => __('For category', 'sopernal-theme'),
						'default'   => 'posts<br>in '
                    ),
					array(
                        'id'        => 'amy-divide-t5',
                        'type'      => 'info',
                        'raw_html'  => true,
                        'desc'      => __('<div class="rdinfotitle">WordPress comments</div>', 'sopernal-theme'),
                    ),
					array(
                        'id'                => 'tr-comm-name',
                        'type'              => 'text',
                        'title'             => __('Name label', 'sopernal-theme'),
						'default'   => 'Name'
                    ),
					array(
                        'id'                => 'tr-comm-email',
                        'type'              => 'text',
                        'title'             => __('E-mail label', 'sopernal-theme'),
						'default'   => 'E-mail'
                    ),
					array(
                        'id'                => 'tr-comm-comment',
                        'type'              => 'text',
                        'title'             => __('Comment label', 'sopernal-theme'),
						'default'   => 'Comment'
                    ),

					array(
                        'id'                => 'tr-comm-title',
                        'type'              => 'text',
                        'title'             => __('Leave a reply title', 'sopernal-theme'),
						'default'   => 'Leave a Reply'
                    ),
					array(
                        'id'                => 'tr-comm-subtitle',
                        'type'              => 'text',
                        'title'             => __('Leave a reply subtitle', 'sopernal-theme'),
						'default'   => 'Your email address will not be published.'
                    ),
					array(
                        'id'                => 'tr-comm-submit',
                        'type'              => 'text',
                        'title'             => __('Submit button label', 'sopernal-theme'),
						'default'   => 'Post Comment'
                    ),
					
					
					array(
                        'id'                => 'tr-comm-newcomm',
                        'type'              => 'text',
                        'title'             => __('Newer comments button label', 'sopernal-theme'),
						'default'   => 'Newer Comments &rarr;'
                    ),
					array(
                        'id'                => 'tr-comm-oldcomm',
                        'type'              => 'text',
                        'title'             => __('Older comments button label', 'sopernal-theme'),
						'default'   => '&larr; Older Comments'
                    ),
					
					array(
                        'id'                => 'tr-comm-commreply',
                        'type'              => 'text',
                        'title'             => __('Reply comment button label', 'sopernal-theme'),
						'default'   => 'Reply'
                    ),
					array(
                        'id'                => 'tr-comm-mustlogin',
                        'type'              => 'text',
                        'title'             => __('You must be logged in label', 'sopernal-theme'),
						'default'   => 'You must be logged to post a comment.'
                    ),
					
					array(
                        'id'                => 'tr-comm-login',
                        'type'              => 'text',
                        'title'             => __('Log in label', 'sopernal-theme'),
						'default'   => 'Log in?'
                    ),
					array(
                        'id'                => 'tr-comm-logout',
                        'type'              => 'text',
                        'title'             => __('Log out label', 'sopernal-theme'),
						'default'   => 'Log out?'
                    ),
					array(
                        'id'                => 'tr-comm-loggedin',
                        'type'              => 'text',
                        'title'             => __('Logged in label', 'sopernal-theme'),
						'default'   => 'Logged in as'
                    ),
					
					array(
                        'id'                => 'tr-comm-error',
                        'type'              => 'text',
                        'title'             => __('Errorr comment message', 'sopernal-theme'),
						'default'   => 'You might have left one of the fields blank, or be posting too quickly'
                    ),
					array(
                        'id'                => 'tr-comm-thanks',
                        'type'              => 'text',
                        'title'             => __('Successful comment send', 'sopernal-theme'),
						'default'   => 'Thanks for your comment. We appreciate your response.'
                    ),
					array(
                        'id'                => 'tr-comm-process',
                        'type'              => 'text',
                        'title'             => __('Processing comment', 'sopernal-theme'),
						'default'   => 'Sending...'
                    ),
					
					array(
                        'id'                => 'tr-comm-1comm',
                        'type'              => 'text',
                        'title'             => __('Single comment', 'sopernal-theme'),
						'default'   => 'thought on'
                    ),
					array(
                        'id'                => 'tr-comm-2comm',
                        'type'              => 'text',
                        'title'             => __('More then 1 comment', 'sopernal-theme'),
						'default'   => 'thoughts on'
                    ),
					array(
                        'id'        => 'amy-divide-t6',
                        'type'      => 'info',
                        'raw_html'  => true,
                        'desc'      => __('<div class="rdinfotitle">Other comments</div>', 'sopernal-theme'),
                    ),
					array(
                        'id'                => 'tr-disqus-title',
                        'type'              => 'text',
                        'title'             => __('Disqus comments title', 'sopernal-theme'),
						'default'   => 'Disqus comments'
                    ),
					array(
                        'id'                => 'tr-facebook-title',
                        'type'              => 'text',
                        'title'             => __('Facebook comments title', 'sopernal-theme'),
						'default'   => 'Facebook comments'
                    ),
					
					
					array(
                        'id'        => 'amy-divide-t7',
                        'type'      => 'info',
                        'raw_html'  => true,
                        'desc'      => __('<div class="rdinfotitle">Woocommerce elements</div>', 'sopernal-theme'),
                    ),
					
					array(
                        'id'                => 'tr-woo-cart-title',
                        'type'              => 'text',
                        'title'             => __('Footer cart title', 'sopernal-theme'),
						'default'   => 'Cart'
                    ),
					array(
                        'id'                => 'tr-woo-1cart',
                        'type'              => 'text',
                        'title'             => __('Footer cart 1 item', 'sopernal-theme'),
						'default'   => 'item'
                    ),
					array(
                        'id'                => 'tr-woo-2cart',
                        'type'              => 'text',
                        'title'             => __('Footer cart more then 1 item', 'sopernal-theme'),
						'default'   => 'items'
                    ),
					
					array(
                        'id'        => 'amy-divide-t7',
                        'type'      => 'info',
                        'raw_html'  => true,
                        'desc'      => __('<div class="rdinfotitle">Footer clock months</div>', 'sopernal-theme'),
                    ),
					array(
                        'id'                => 'tr-months-jan',
                        'type'              => 'text',
                        'title'             => __('January', 'sopernal-theme'),
						'default'   => 'January'
                    ),
					array(
                        'id'                => 'tr-months-feb',
                        'type'              => 'text',
                        'title'             => __('February', 'sopernal-theme'),
						'default'   => 'February'
                    ),
					array(
                        'id'                => 'tr-months-mar',
                        'type'              => 'text',
                        'title'             => __('March', 'sopernal-theme'),
						'default'   => 'March'
                    ),
					array(
                        'id'                => 'tr-months-apr',
                        'type'              => 'text',
                        'title'             => __('April', 'sopernal-theme'),
						'default'   => 'April'
                    ),
					array(
                        'id'                => 'tr-months-may',
                        'type'              => 'text',
                        'title'             => __('May', 'sopernal-theme'),
						'default'   => 'May'
                    ),
					array(
                        'id'                => 'tr-months-jun',
                        'type'              => 'text',
                        'title'             => __('June', 'sopernal-theme'),
						'default'   => 'June'
                    ),
					array(
                        'id'                => 'tr-months-jul',
                        'type'              => 'text',
                        'title'             => __('July', 'sopernal-theme'),
						'default'   => 'July'
                    ),
					array(
                        'id'                => 'tr-months-aug',
                        'type'              => 'text',
                        'title'             => __('August', 'sopernal-theme'),
						'default'   => 'August'
                    ),
					array(
                        'id'                => 'tr-months-sep',
                        'type'              => 'text',
                        'title'             => __('September', 'sopernal-theme'),
						'default'   => 'September'
                    ),
					array(
                        'id'                => 'tr-months-oct',
                        'type'              => 'text',
                        'title'             => __('October', 'sopernal-theme'),
						'default'   => 'October'
                    ),
					array(
                        'id'                => 'tr-months-nov',
                        'type'              => 'text',
                        'title'             => __('November', 'sopernal-theme'),
						'default'   => 'November'
                    ),
					array(
                        'id'                => 'tr-months-dec',
                        'type'              => 'text',
                        'title'             => __('December', 'sopernal-theme'),
						'default'   => 'December'
                    ),
					array(
                        'id'        => 'amy-divide-t1',
                        'type'      => 'info',
                        'raw_html'  => true,
                        'desc'      => __('<div class="rdinfotitle">Other</div>', 'sopernal-theme'),
                    ),
					
					array(
                        'id'                => 'tr-nav-tooltip',
                        'type'              => 'text',
                        'title'             => __('Footer navigation arrows', 'sopernal-theme'),
						'default'   => 'Slider navigation'
                    ),
					
					
					
					
					
                )
            );
			
			 $this->sections[] = array(
                'title'     => __('WooCommerce', 'sopernal-theme'),
               // 'icon'      => 'el-icon-home',
				'icon'      => 'el-icon-shopping-cart',
                'fields'    => array(
					array(
                        'id'        => 'woo-def-color',
                        'type'      => 'button_set',
                        'title'     => __('Woocommerce color cheme', 'sopernal-theme'),
                        'options'   => array(
                            '' => 'White', 
                            'as_black_style' => 'Dark'
                        ),
                        'default'   => ''
						
                       
                    ),
					array(
                        'id'        => 'woo-def-rows',
                        'type'      => 'spinner',
                        'title'     => __('Default woocommerce grid rows', 'sopernal-theme'),
                        'default'   => '3',
                        'min'       => '2',
                        'step'      => '1',
						'max'       => '3',
                    ),
					array(
                        'id'        => 'woo-hoverfx',
                        'type'      => 'button_set',
                        'title'     => __('Slider hover style', 'sopernal-theme'),
                        'options'   => array(
							'' => 'Triangle',
                            'squares' => 'Squares', 
                            'waves' => 'Waves'
                        ),
                        'default'   => ''
						
                       
                    ),
				
					 array(
                        'id'        => 'woo-thumb-style',
                        'type'      => 'button_set',
                        'title'     => __('Woo thumb style', 'sopernal-theme'),
						 //Must provide key => value pairs for select options
                        'options'   => array(
                            'style1' => 'Style 1', 
                            'style2' => 'Style 2'
                        ),
                        'default'   => 'style1'
					),
					
					array(
                        'id'        => 'woo-footer-cart',
                        'type'      => 'switch',
                        'title'     => __('Show footer cart', 'sopernal-theme'),
                        'default'   => true,
                        'on'        => 'On',
                        'off'       => 'Off',
                    ),
					
                )
            );
			
			
			$this->sections[] = array(
                'title'     => __('One click styles', 'sopernal-theme'),
               // 'icon'      => 'el-icon-home',
				'icon'      => 'el-icon-magic',
                'fields'    => array(
					
					   array(
                        'id'        => 'amy-menu-styles',
                        'type'      => 'image_select',
                        'presets'   => true,
                        'title'     => __('Select theme style', 'sopernal-theme'),
                        'subtitle'  => __('You can customize each settings after selecting your theme style', 'sopernal-theme'),
                        'default'   => 0,
                        'options'   => array(
                            '1'         => array('alt' => 'Preset 1', 'img' => ReduxFramework::$_url . '../amy/images/themestyle1.jpg', 'presets' =>'{"last_tab":"","site-width":"fullwidth","menu-position":"fixed","menu-width":"boxed","footer-position":"fixed","footer-width":"fullwidth","future-posts":"","disqus-id":"","menu-search":"1","logo-img":{"url":"'.get_template_directory_uri().'/images/logo.png","id":"","height":"","width":"","thumbnail":""},"fav-ico":{"url":"'.get_template_directory_uri().'/images/favicon.ico","id":"","height":"","width":"","thumbnail":""},"welcome-msg":"1","welcome-msg-text":"<span class=\"content-title\">Welcome<\/span>\r\n<span id=\"input-method\">You can add your content here<\/span>","404-page":"","add-custom-css":".yourclass{\r\nmargin: 0 auto;\r\n}","add-custom-js":"                jQuery(document).ready(function(){\r\n\r\n});            ","amy-slider-style":"classictilt","amy-slider-post-type":"post","amy-slider-color":"","amy-slider-hoverfx":"","amy-slider-parallax":"0","amy-slider-parallax-depth":"1.5","amy-slider-excerpt":"150","order-posts":"1","def-pagination-display":"1","amy-slider-first-post":"0","amy-slider-autorotate":"","amy-slider-autorotate-delay":"3000","footer-date":"1","footer-layout":"5","footer-text":"<strong>Copyright 2014 <\/strong>\r\nDesigned by <a href=\"http:\/\/yoursite.com\">Your Name<\/a>","fb-link-url":"https:\/\/facebook.com","tw-link-url":"https:\/\/twitter.com","gp-link-url":"https:\/\/plus.google.com\/","pi-link-url":"https:\/\/www.pinterest.com\/","yt-link-url":"","def-background":{"background-color":"#F1F1F1","background-repeat":"","background-size":"","background-attachment":"","background-position":"","background-image":"","media":{"id":"","height":"","width":"","thumbnail":""}},"def-background-boxed-opa":{"color":"#f1f1f1","alpha":"1"},"def-background-boxed":{"background-repeat":"","background-size":"","background-attachment":"","background-position":"","background-image":"","media":{"id":"","height":"","width":"","thumbnail":""}},"layerslider-bg":"","yt-active":"","yt-bg-id":"","yt-bg-cotrols":"1","yt-bg-mute":"false","yt-bg-repeat":"true","yt-bg-start":"0","bg-active":"1","bg-allpages":"0","bg-overlays":"16","bg-delay":"6000","bg-fade":"1000","bg-vposition":"center","bg-hposition":"center","bg-slides":[{"title":"","description":"","url":"","sort":"","attachment_id":"","thumb":"'.get_template_directory_uri().'/images/defbg-150.jpg","image":"'.get_template_directory_uri().'/images/defbg.jpg","height":"","width":""}],"body-color-scheme":"amethyst","body-typography":{"font-family":"Open Sans","font-options":"{\"variants\":[{\"id\":\"300\",\"name\":\"Book+300\"},{\"id\":\"400\",\"name\":\"Normal+400\"},{\"id\":\"600\",\"name\":\"Semi-Bold+600\"},{\"id\":\"700\",\"name\":\"Bold+700\"},{\"id\":\"800\",\"name\":\"Extra-Bold+800\"},{\"id\":\"300italic\",\"name\":\"Book+300+Italic\"},{\"id\":\"400italic\",\"name\":\"Normal+400+Italic\"},{\"id\":\"600italic\",\"name\":\"Semi-Bold+600+Italic\"},{\"id\":\"700italic\",\"name\":\"Bold+700+Italic\"},{\"id\":\"800italic\",\"name\":\"Extra-Bold+800+Italic\"}],\"subsets\":[{\"id\":\"devanagari\",\"name\":\"Devanagari\"},{\"id\":\"cyrillic\",\"name\":\"Cyrillic\"},{\"id\":\"latin\",\"name\":\"Latin\"},{\"id\":\"latin-ext\",\"name\":\"Latin+Extended\"},{\"id\":\"cyrillic-ext\",\"name\":\"Cyrillic+Extended\"},{\"id\":\"greek\",\"name\":\"Greek\"},{\"id\":\"vietnamese\",\"name\":\"Vietnamese\"},{\"id\":\"greek-ext\",\"name\":\"Greek+Extended\"}]}","google":"true","font-backup":"","font-weight":"400","font-style":"","subsets":"","font-size":"12px","color":"#777777"},"headings-typography":{"font-family":"Open Sans","font-options":"{\"variants\":[{\"id\":\"300\",\"name\":\"Book+300\"},{\"id\":\"400\",\"name\":\"Normal+400\"},{\"id\":\"600\",\"name\":\"Semi-Bold+600\"},{\"id\":\"700\",\"name\":\"Bold+700\"},{\"id\":\"800\",\"name\":\"Extra-Bold+800\"},{\"id\":\"300italic\",\"name\":\"Book+300+Italic\"},{\"id\":\"400italic\",\"name\":\"Normal+400+Italic\"},{\"id\":\"600italic\",\"name\":\"Semi-Bold+600+Italic\"},{\"id\":\"700italic\",\"name\":\"Bold+700+Italic\"},{\"id\":\"800italic\",\"name\":\"Extra-Bold+800+Italic\"}],\"subsets\":[{\"id\":\"devanagari\",\"name\":\"Devanagari\"},{\"id\":\"cyrillic\",\"name\":\"Cyrillic\"},{\"id\":\"latin\",\"name\":\"Latin\"},{\"id\":\"latin-ext\",\"name\":\"Latin+Extended\"},{\"id\":\"cyrillic-ext\",\"name\":\"Cyrillic+Extended\"},{\"id\":\"greek\",\"name\":\"Greek\"},{\"id\":\"vietnamese\",\"name\":\"Vietnamese\"},{\"id\":\"greek-ext\",\"name\":\"Greek+Extended\"}]}","google":"true","font-backup":"","font-weight":"300","font-style":"","subsets":""},"titles-typography":{"font-family":"Open Sans","font-options":"{\"variants\":[{\"id\":\"300\",\"name\":\"Book+300\"},{\"id\":\"400\",\"name\":\"Normal+400\"},{\"id\":\"600\",\"name\":\"Semi-Bold+600\"},{\"id\":\"700\",\"name\":\"Bold+700\"},{\"id\":\"800\",\"name\":\"Extra-Bold+800\"},{\"id\":\"300italic\",\"name\":\"Book+300+Italic\"},{\"id\":\"400italic\",\"name\":\"Normal+400+Italic\"},{\"id\":\"600italic\",\"name\":\"Semi-Bold+600+Italic\"},{\"id\":\"700italic\",\"name\":\"Bold+700+Italic\"},{\"id\":\"800italic\",\"name\":\"Extra-Bold+800+Italic\"}],\"subsets\":[{\"id\":\"devanagari\",\"name\":\"Devanagari\"},{\"id\":\"cyrillic\",\"name\":\"Cyrillic\"},{\"id\":\"latin\",\"name\":\"Latin\"},{\"id\":\"latin-ext\",\"name\":\"Latin+Extended\"},{\"id\":\"cyrillic-ext\",\"name\":\"Cyrillic+Extended\"},{\"id\":\"greek\",\"name\":\"Greek\"},{\"id\":\"vietnamese\",\"name\":\"Vietnamese\"},{\"id\":\"greek-ext\",\"name\":\"Greek+Extended\"}]}","google":"true","font-backup":"","font-weight":"300","font-style":"","subsets":""},"menu-background":{"color":"#ffffff","alpha":"0.94"},"menu-background-shadow":"1","menu-typography":{"font-family":"Open Sans","font-options":"{\"variants\":[{\"id\":\"300\",\"name\":\"Book+300\"},{\"id\":\"400\",\"name\":\"Normal+400\"},{\"id\":\"600\",\"name\":\"Semi-Bold+600\"},{\"id\":\"700\",\"name\":\"Bold+700\"},{\"id\":\"800\",\"name\":\"Extra-Bold+800\"},{\"id\":\"300italic\",\"name\":\"Book+300+Italic\"},{\"id\":\"400italic\",\"name\":\"Normal+400+Italic\"},{\"id\":\"600italic\",\"name\":\"Semi-Bold+600+Italic\"},{\"id\":\"700italic\",\"name\":\"Bold+700+Italic\"},{\"id\":\"800italic\",\"name\":\"Extra-Bold+800+Italic\"}],\"subsets\":[{\"id\":\"devanagari\",\"name\":\"Devanagari\"},{\"id\":\"cyrillic\",\"name\":\"Cyrillic\"},{\"id\":\"latin\",\"name\":\"Latin\"},{\"id\":\"latin-ext\",\"name\":\"Latin+Extended\"},{\"id\":\"cyrillic-ext\",\"name\":\"Cyrillic+Extended\"},{\"id\":\"greek\",\"name\":\"Greek\"},{\"id\":\"vietnamese\",\"name\":\"Vietnamese\"},{\"id\":\"greek-ext\",\"name\":\"Greek+Extended\"}]}","google":"true","font-backup":"","font-weight":"300","font-style":"","subsets":"","font-size":"13px","line-height":"18px","color":"#777777"},"submenu-background":{"color":"#ffffff","alpha":"0.94"},"submenu-border":{"border-top":"0","border-right":"0","border-bottom":"1px","border-left":"0","border-style":"solid"},"submenu-border-color":{"color":"#333333","alpha":"0.06"},"submenu-typography":{"font-family":"Open Sans","font-options":"{\"variants\":[{\"id\":\"300\",\"name\":\"Book+300\"},{\"id\":\"400\",\"name\":\"Normal+400\"},{\"id\":\"600\",\"name\":\"Semi-Bold+600\"},{\"id\":\"700\",\"name\":\"Bold+700\"},{\"id\":\"800\",\"name\":\"Extra-Bold+800\"},{\"id\":\"300italic\",\"name\":\"Book+300+Italic\"},{\"id\":\"400italic\",\"name\":\"Normal+400+Italic\"},{\"id\":\"600italic\",\"name\":\"Semi-Bold+600+Italic\"},{\"id\":\"700italic\",\"name\":\"Bold+700+Italic\"},{\"id\":\"800italic\",\"name\":\"Extra-Bold+800+Italic\"}],\"subsets\":[{\"id\":\"devanagari\",\"name\":\"Devanagari\"},{\"id\":\"cyrillic\",\"name\":\"Cyrillic\"},{\"id\":\"latin\",\"name\":\"Latin\"},{\"id\":\"latin-ext\",\"name\":\"Latin+Extended\"},{\"id\":\"cyrillic-ext\",\"name\":\"Cyrillic+Extended\"},{\"id\":\"greek\",\"name\":\"Greek\"},{\"id\":\"vietnamese\",\"name\":\"Vietnamese\"},{\"id\":\"greek-ext\",\"name\":\"Greek+Extended\"}]}","google":"true","font-backup":"","font-weight":"300","font-style":"","subsets":"","font-size":"11px","color":"#777777"},"widget-typography":{"font-family":"Open Sans","font-options":"{\"variants\":[{\"id\":\"300\",\"name\":\"Book+300\"},{\"id\":\"400\",\"name\":\"Normal+400\"},{\"id\":\"600\",\"name\":\"Semi-Bold+600\"},{\"id\":\"700\",\"name\":\"Bold+700\"},{\"id\":\"800\",\"name\":\"Extra-Bold+800\"},{\"id\":\"300italic\",\"name\":\"Book+300+Italic\"},{\"id\":\"400italic\",\"name\":\"Normal+400+Italic\"},{\"id\":\"600italic\",\"name\":\"Semi-Bold+600+Italic\"},{\"id\":\"700italic\",\"name\":\"Bold+700+Italic\"},{\"id\":\"800italic\",\"name\":\"Extra-Bold+800+Italic\"}],\"subsets\":[{\"id\":\"devanagari\",\"name\":\"Devanagari\"},{\"id\":\"cyrillic\",\"name\":\"Cyrillic\"},{\"id\":\"latin\",\"name\":\"Latin\"},{\"id\":\"latin-ext\",\"name\":\"Latin+Extended\"},{\"id\":\"cyrillic-ext\",\"name\":\"Cyrillic+Extended\"},{\"id\":\"greek\",\"name\":\"Greek\"},{\"id\":\"vietnamese\",\"name\":\"Vietnamese\"},{\"id\":\"greek-ext\",\"name\":\"Greek+Extended\"}]}","google":"true","font-backup":"","font-weight":"400","font-style":"","subsets":"","font-size":"18px"},"widget-border":{"border-top":"0","border-right":"0","border-bottom":"1px","border-left":"0","border-style":"solid","border-color":"#e4e4e4"},"widget-border-color":{"color":"#e4e4e4","alpha":"1"},"page-spacing":{"margin-top":"60px","margin-bottom":"60px"},"commnets-background":{"color":"#f9f9f9","alpha":"1.0"},"input-filed-background":{"color":"#f9f9f9","alpha":"1.0"},"input-filed-border":{"border-top":"1px","border-right":"1px","border-bottom":"1px","border-left":"1px","border-style":"solid"},"input-border-color":{"color":"#ececec","alpha":"0.5"},"path-background":{"color":"#ffffff","alpha":"0.85"},"footer-background":{"color":"#ffffff","alpha":"0.90"},"footer-background-text":{"font-weight":"","font-style":"","color":"#777777"},"footer-background-widget":{"color":"#282828","alpha":"0.95"},"footer-widget-border":{"border-top":"0","border-right":"0","border-bottom":"1px","border-left":"0","border-style":"solid","border-color":"#4f4f4f"},"footer-typography":{"font-family":"Open Sans","font-options":"{\"variants\":[{\"id\":\"300\",\"name\":\"Book+300\"},{\"id\":\"400\",\"name\":\"Normal+400\"},{\"id\":\"600\",\"name\":\"Semi-Bold+600\"},{\"id\":\"700\",\"name\":\"Bold+700\"},{\"id\":\"800\",\"name\":\"Extra-Bold+800\"},{\"id\":\"300italic\",\"name\":\"Book+300+Italic\"},{\"id\":\"400italic\",\"name\":\"Normal+400+Italic\"},{\"id\":\"600italic\",\"name\":\"Semi-Bold+600+Italic\"},{\"id\":\"700italic\",\"name\":\"Bold+700+Italic\"},{\"id\":\"800italic\",\"name\":\"Extra-Bold+800+Italic\"}],\"subsets\":[{\"id\":\"devanagari\",\"name\":\"Devanagari\"},{\"id\":\"cyrillic\",\"name\":\"Cyrillic\"},{\"id\":\"latin\",\"name\":\"Latin\"},{\"id\":\"latin-ext\",\"name\":\"Latin+Extended\"},{\"id\":\"cyrillic-ext\",\"name\":\"Cyrillic+Extended\"},{\"id\":\"greek\",\"name\":\"Greek\"},{\"id\":\"vietnamese\",\"name\":\"Vietnamese\"},{\"id\":\"greek-ext\",\"name\":\"Greek+Extended\"}]}","google":"true","font-backup":"","font-weight":"400","font-style":"","subsets":"","font-size":"12px","color":"#a9a9a9"},"loading-bacground":{"color":"#fefefe","alpha":"1"},"woo-errmsg-bg":{"color":"#f9f9f9","alpha":"1.0"},"woo-errmsg-text":{"color":"#777777","alpha":"1.0"},"woo-input-bg":{"color":"#f9f9f9","alpha":"1.0"},"woo-input-border":{"border-top":"1px","border-right":"1px","border-bottom":"1px","border-left":"1px","border-style":"solid"},"woo-input-border-color":{"color":"#ececec","alpha":"1.0"},"woo-input-color":{"color":"#777777","alpha":"1.0"},"woo-element-border":{"color":"#ececec","alpha":"1.0"},"tr-resmenu-button":"Menu","tr-resmenu-title":"Menu","tr-menu-search":"Search...","tr-footer-home-info":"posts<br>at home page","tr-footer-search-info":"results<br>found","tr-footer-archive-info":"posts<br>in archive","tr-footer-category-info":"posts<br>in ","tr-comm-name":"Name","tr-comm-email":"E-mail","tr-comm-comment":"Comment","tr-comm-title":"Leave a Reply","tr-comm-subtitle":"Your email address will not be published.","tr-comm-submit":"Post Comment","tr-comm-newcomm":"Newer Comments \u2192","tr-comm-oldcomm":"\u2190 Older Comments","tr-comm-commreply":"Reply","tr-comm-mustlogin":"You must be logged to post a comment.","tr-comm-login":"Log in?","tr-comm-logout":"Log out?","tr-comm-loggedin":"Logged in as","tr-comm-error":"You might have left one of the fields blank, or be posting too quickly","tr-comm-thanks":"Thanks for your comment. We appreciate your response.","tr-comm-process":"Sending...","tr-comm-1comm":"thought on","tr-comm-2comm":"thoughts on","tr-disqus-title":"Disqus comments","tr-facebook-title":"Facebook comments","tr-woo-cart-title":"Cart","tr-woo-1cart":"item","tr-woo-2cart":"items","tr-months-jan":"January","tr-months-feb":"February","tr-months-mar":"March","tr-months-apr":"April","tr-months-may":"May","tr-months-jun":"June","tr-months-jul":"July","tr-months-aug":"August","tr-months-sep":"September","tr-months-oct":"October","tr-months-nov":"November","tr-months-dec":"December","tr-nav-tooltip":"Slider navigation","woo-def-color":"","woo-def-rows":"3","woo-hoverfx":"","woo-thumb-style":"style1","woo-footer-cart":"1","amy-menu-styles":"2","REDUX_last_saved":1403284152,"amy-menu-styles2":0,"redux-backup":"1"}'),
                       
						 '2'         => array('alt' => 'Preset 2', 'img' => ReduxFramework::$_url . '../amy/images/themestyle2.jpg', 'presets' =>'
{"last_tab":"","site-width":"boxed","menu-position":"fixed","menu-width":"boxed","footer-position":"absolute","footer-width":"boxed","future-posts":"","disqus-id":"","menu-search":"1","logo-img":{"url":"'.get_template_directory_uri().'/images/logo.png","id":"","height":"","width":"","thumbnail":""},"fav-ico":{"url":"'.get_template_directory_uri().'/images/favicon.ico","id":"","height":"","width":"","thumbnail":""},"welcome-msg":"1","welcome-msg-text":"<span class=\"content-title\">Welcome<\/span>\r\n<span id=\"input-method\">You can add your content here<\/span>","404-page":"","add-custom-css":"                .yourclass{\r\nmargin: 0 auto;\r\n}            ","add-custom-js":"                jQuery(document).ready(function(){\r\n\r\n});            ","amy-slider-style":"coverflow","amy-slider-post-type":"post","amy-slider-color":"","amy-slider-hoverfx":"","amy-slider-parallax":"0","amy-slider-parallax-depth":"1.5","amy-slider-excerpt":"150","order-posts":"1","def-pagination-display":"1","amy-slider-first-post":"0","amy-slider-autorotate":"","amy-slider-autorotate-delay":"3000","footer-date":"1","footer-layout":"5","footer-text":"<strong>Copyright 2014 <\/strong>\r\nDesigned by <a href=\"http:\/\/yoursite.com\">Your Name<\/a>","fb-link-url":"https:\/\/facebook.com","tw-link-url":"https:\/\/twitter.com","gp-link-url":"https:\/\/plus.google.com\/","pi-link-url":"https:\/\/www.pinterest.com\/","yt-link-url":"","def-background":{"background-color":"#F1F1F1","background-repeat":"","background-size":"","background-attachment":"","background-position":"","background-image":"","media":{"id":"","height":"","width":"","thumbnail":""}},"def-background-boxed-opa":{"color":"#f1f1f1","alpha":"1"},"def-background-boxed":{"background-repeat":"","background-size":"","background-attachment":"","background-position":"","background-image":"","media":{"id":"","height":"","width":"","thumbnail":""}},"layerslider-bg":"","yt-active":"","yt-bg-id":"","yt-bg-cotrols":"1","yt-bg-mute":"false","yt-bg-repeat":"true","yt-bg-start":"0","bg-active":"1","bg-allpages":"1","bg-overlays":"16","bg-delay":"6000","bg-fade":"1000","bg-vposition":"center","bg-hposition":"center","bg-slides":[{"title":"","description":"","url":"","sort":"0","attachment_id":"","thumb":"'.get_template_directory_uri().'/images/defbg-150.jpg","image":"'.get_template_directory_uri().'/images/defbg.jpg","height":"","width":""}],"body-color-scheme":"amethyst","body-typography":{"font-family":"Open Sans","font-options":"{\"variants\":[{\"id\":\"300\",\"name\":\"Book+300\"},{\"id\":\"400\",\"name\":\"Normal+400\"},{\"id\":\"600\",\"name\":\"Semi-Bold+600\"},{\"id\":\"700\",\"name\":\"Bold+700\"},{\"id\":\"800\",\"name\":\"Extra-Bold+800\"},{\"id\":\"300italic\",\"name\":\"Book+300+Italic\"},{\"id\":\"400italic\",\"name\":\"Normal+400+Italic\"},{\"id\":\"600italic\",\"name\":\"Semi-Bold+600+Italic\"},{\"id\":\"700italic\",\"name\":\"Bold+700+Italic\"},{\"id\":\"800italic\",\"name\":\"Extra-Bold+800+Italic\"}],\"subsets\":[{\"id\":\"devanagari\",\"name\":\"Devanagari\"},{\"id\":\"cyrillic\",\"name\":\"Cyrillic\"},{\"id\":\"latin\",\"name\":\"Latin\"},{\"id\":\"latin-ext\",\"name\":\"Latin+Extended\"},{\"id\":\"cyrillic-ext\",\"name\":\"Cyrillic+Extended\"},{\"id\":\"greek\",\"name\":\"Greek\"},{\"id\":\"vietnamese\",\"name\":\"Vietnamese\"},{\"id\":\"greek-ext\",\"name\":\"Greek+Extended\"}]}","google":"true","font-backup":"","font-weight":"400","font-style":"","subsets":"","font-size":"12px","color":"#777777"},"headings-typography":{"font-family":"Open Sans","font-options":"{\"variants\":[{\"id\":\"300\",\"name\":\"Book+300\"},{\"id\":\"400\",\"name\":\"Normal+400\"},{\"id\":\"600\",\"name\":\"Semi-Bold+600\"},{\"id\":\"700\",\"name\":\"Bold+700\"},{\"id\":\"800\",\"name\":\"Extra-Bold+800\"},{\"id\":\"300italic\",\"name\":\"Book+300+Italic\"},{\"id\":\"400italic\",\"name\":\"Normal+400+Italic\"},{\"id\":\"600italic\",\"name\":\"Semi-Bold+600+Italic\"},{\"id\":\"700italic\",\"name\":\"Bold+700+Italic\"},{\"id\":\"800italic\",\"name\":\"Extra-Bold+800+Italic\"}],\"subsets\":[{\"id\":\"devanagari\",\"name\":\"Devanagari\"},{\"id\":\"cyrillic\",\"name\":\"Cyrillic\"},{\"id\":\"latin\",\"name\":\"Latin\"},{\"id\":\"latin-ext\",\"name\":\"Latin+Extended\"},{\"id\":\"cyrillic-ext\",\"name\":\"Cyrillic+Extended\"},{\"id\":\"greek\",\"name\":\"Greek\"},{\"id\":\"vietnamese\",\"name\":\"Vietnamese\"},{\"id\":\"greek-ext\",\"name\":\"Greek+Extended\"}]}","google":"true","font-backup":"","font-weight":"300","font-style":"","subsets":""},"titles-typography":{"font-family":"Open Sans","font-options":"{\"variants\":[{\"id\":\"300\",\"name\":\"Book+300\"},{\"id\":\"400\",\"name\":\"Normal+400\"},{\"id\":\"600\",\"name\":\"Semi-Bold+600\"},{\"id\":\"700\",\"name\":\"Bold+700\"},{\"id\":\"800\",\"name\":\"Extra-Bold+800\"},{\"id\":\"300italic\",\"name\":\"Book+300+Italic\"},{\"id\":\"400italic\",\"name\":\"Normal+400+Italic\"},{\"id\":\"600italic\",\"name\":\"Semi-Bold+600+Italic\"},{\"id\":\"700italic\",\"name\":\"Bold+700+Italic\"},{\"id\":\"800italic\",\"name\":\"Extra-Bold+800+Italic\"}],\"subsets\":[{\"id\":\"devanagari\",\"name\":\"Devanagari\"},{\"id\":\"cyrillic\",\"name\":\"Cyrillic\"},{\"id\":\"latin\",\"name\":\"Latin\"},{\"id\":\"latin-ext\",\"name\":\"Latin+Extended\"},{\"id\":\"cyrillic-ext\",\"name\":\"Cyrillic+Extended\"},{\"id\":\"greek\",\"name\":\"Greek\"},{\"id\":\"vietnamese\",\"name\":\"Vietnamese\"},{\"id\":\"greek-ext\",\"name\":\"Greek+Extended\"}]}","google":"true","font-backup":"","font-weight":"300","font-style":"","subsets":""},"menu-background":{"color":"#ffffff","alpha":"0.95"},"menu-background-shadow":"1","menu-typography":{"font-family":"Open Sans","font-options":"{\"variants\":[{\"id\":\"300\",\"name\":\"Book+300\"},{\"id\":\"400\",\"name\":\"Normal+400\"},{\"id\":\"600\",\"name\":\"Semi-Bold+600\"},{\"id\":\"700\",\"name\":\"Bold+700\"},{\"id\":\"800\",\"name\":\"Extra-Bold+800\"},{\"id\":\"300italic\",\"name\":\"Book+300+Italic\"},{\"id\":\"400italic\",\"name\":\"Normal+400+Italic\"},{\"id\":\"600italic\",\"name\":\"Semi-Bold+600+Italic\"},{\"id\":\"700italic\",\"name\":\"Bold+700+Italic\"},{\"id\":\"800italic\",\"name\":\"Extra-Bold+800+Italic\"}],\"subsets\":[{\"id\":\"devanagari\",\"name\":\"Devanagari\"},{\"id\":\"cyrillic\",\"name\":\"Cyrillic\"},{\"id\":\"latin\",\"name\":\"Latin\"},{\"id\":\"latin-ext\",\"name\":\"Latin+Extended\"},{\"id\":\"cyrillic-ext\",\"name\":\"Cyrillic+Extended\"},{\"id\":\"greek\",\"name\":\"Greek\"},{\"id\":\"vietnamese\",\"name\":\"Vietnamese\"},{\"id\":\"greek-ext\",\"name\":\"Greek+Extended\"}]}","google":"true","font-backup":"","font-weight":"300","font-style":"","subsets":"","font-size":"13px","line-height":"18px","color":"#777777"},"submenu-background":{"color":"#ffffff","alpha":"0.94"},"submenu-border":{"border-top":"0","border-right":"0","border-bottom":"1px","border-left":"0","border-style":"solid"},"submenu-border-color":{"color":"#333333","alpha":"0.06"},"submenu-typography":{"font-family":"Open Sans","font-options":"{\"variants\":[{\"id\":\"300\",\"name\":\"Book+300\"},{\"id\":\"400\",\"name\":\"Normal+400\"},{\"id\":\"600\",\"name\":\"Semi-Bold+600\"},{\"id\":\"700\",\"name\":\"Bold+700\"},{\"id\":\"800\",\"name\":\"Extra-Bold+800\"},{\"id\":\"300italic\",\"name\":\"Book+300+Italic\"},{\"id\":\"400italic\",\"name\":\"Normal+400+Italic\"},{\"id\":\"600italic\",\"name\":\"Semi-Bold+600+Italic\"},{\"id\":\"700italic\",\"name\":\"Bold+700+Italic\"},{\"id\":\"800italic\",\"name\":\"Extra-Bold+800+Italic\"}],\"subsets\":[{\"id\":\"devanagari\",\"name\":\"Devanagari\"},{\"id\":\"cyrillic\",\"name\":\"Cyrillic\"},{\"id\":\"latin\",\"name\":\"Latin\"},{\"id\":\"latin-ext\",\"name\":\"Latin+Extended\"},{\"id\":\"cyrillic-ext\",\"name\":\"Cyrillic+Extended\"},{\"id\":\"greek\",\"name\":\"Greek\"},{\"id\":\"vietnamese\",\"name\":\"Vietnamese\"},{\"id\":\"greek-ext\",\"name\":\"Greek+Extended\"}]}","google":"true","font-backup":"","font-weight":"300","font-style":"","subsets":"","font-size":"11px","color":"#777777"},"widget-typography":{"font-family":"Open Sans","font-options":"{\"variants\":[{\"id\":\"300\",\"name\":\"Book+300\"},{\"id\":\"400\",\"name\":\"Normal+400\"},{\"id\":\"600\",\"name\":\"Semi-Bold+600\"},{\"id\":\"700\",\"name\":\"Bold+700\"},{\"id\":\"800\",\"name\":\"Extra-Bold+800\"},{\"id\":\"300italic\",\"name\":\"Book+300+Italic\"},{\"id\":\"400italic\",\"name\":\"Normal+400+Italic\"},{\"id\":\"600italic\",\"name\":\"Semi-Bold+600+Italic\"},{\"id\":\"700italic\",\"name\":\"Bold+700+Italic\"},{\"id\":\"800italic\",\"name\":\"Extra-Bold+800+Italic\"}],\"subsets\":[{\"id\":\"devanagari\",\"name\":\"Devanagari\"},{\"id\":\"cyrillic\",\"name\":\"Cyrillic\"},{\"id\":\"latin\",\"name\":\"Latin\"},{\"id\":\"latin-ext\",\"name\":\"Latin+Extended\"},{\"id\":\"cyrillic-ext\",\"name\":\"Cyrillic+Extended\"},{\"id\":\"greek\",\"name\":\"Greek\"},{\"id\":\"vietnamese\",\"name\":\"Vietnamese\"},{\"id\":\"greek-ext\",\"name\":\"Greek+Extended\"}]}","google":"true","font-backup":"","font-weight":"400","font-style":"","subsets":"","font-size":"18px"},"widget-border":{"border-top":"0","border-right":"0","border-bottom":"1px","border-left":"0","border-style":"solid","border-color":"#e4e4e4"},"widget-border-color":{"color":"#e4e4e4","alpha":"1"},"page-spacing":{"margin-top":"60px","margin-bottom":"60px"},"commnets-background":{"color":"#f9f9f9","alpha":"1.0"},"input-filed-background":{"color":"#f9f9f9","alpha":"1.0"},"input-filed-border":{"border-top":"1px","border-right":"1px","border-bottom":"1px","border-left":"1px","border-style":"solid"},"input-border-color":{"color":"#ececec","alpha":"0.5"},"path-background":{"color":"#ffffff","alpha":"0.85"},"footer-background":{"color":"#ffffff","alpha":"0.90"},"footer-background-text":{"font-weight":"","font-style":"","color":"#777777"},"footer-background-widget":{"color":"#282828","alpha":"0.95"},"footer-widget-border":{"border-top":"0","border-right":"0","border-bottom":"1px","border-left":"0","border-style":"solid","border-color":"#4f4f4f"},"footer-typography":{"font-family":"Open Sans","font-options":"{\"variants\":[{\"id\":\"300\",\"name\":\"Book+300\"},{\"id\":\"400\",\"name\":\"Normal+400\"},{\"id\":\"600\",\"name\":\"Semi-Bold+600\"},{\"id\":\"700\",\"name\":\"Bold+700\"},{\"id\":\"800\",\"name\":\"Extra-Bold+800\"},{\"id\":\"300italic\",\"name\":\"Book+300+Italic\"},{\"id\":\"400italic\",\"name\":\"Normal+400+Italic\"},{\"id\":\"600italic\",\"name\":\"Semi-Bold+600+Italic\"},{\"id\":\"700italic\",\"name\":\"Bold+700+Italic\"},{\"id\":\"800italic\",\"name\":\"Extra-Bold+800+Italic\"}],\"subsets\":[{\"id\":\"devanagari\",\"name\":\"Devanagari\"},{\"id\":\"cyrillic\",\"name\":\"Cyrillic\"},{\"id\":\"latin\",\"name\":\"Latin\"},{\"id\":\"latin-ext\",\"name\":\"Latin+Extended\"},{\"id\":\"cyrillic-ext\",\"name\":\"Cyrillic+Extended\"},{\"id\":\"greek\",\"name\":\"Greek\"},{\"id\":\"vietnamese\",\"name\":\"Vietnamese\"},{\"id\":\"greek-ext\",\"name\":\"Greek+Extended\"}]}","google":"true","font-backup":"","font-weight":"400","font-style":"","subsets":"","font-size":"12px","color":"#a9a9a9"},"loading-bacground":{"color":"#fefefe","alpha":"1"},"woo-errmsg-bg":{"color":"#f9f9f9","alpha":"1.0"},"woo-errmsg-text":{"color":"#777777","alpha":"1.0"},"woo-input-bg":{"color":"#f9f9f9","alpha":"1.0"},"woo-input-border":{"border-top":"1px","border-right":"1px","border-bottom":"1px","border-left":"1px","border-style":"solid"},"woo-input-border-color":{"color":"#ececec","alpha":"1.0"},"woo-input-color":{"color":"#777777","alpha":"1.0"},"woo-element-border":{"color":"#ececec","alpha":"1.0"},"tr-resmenu-button":"Menu","tr-resmenu-title":"Menu","tr-menu-search":"Search...","tr-footer-home-info":"posts<br>at home page","tr-footer-search-info":"results<br>found","tr-footer-archive-info":"posts<br>in archive","tr-footer-category-info":"posts<br>in ","tr-comm-name":"Name","tr-comm-email":"E-mail","tr-comm-comment":"Comment","tr-comm-title":"Leave a Reply","tr-comm-subtitle":"Your email address will not be published.","tr-comm-submit":"Post Comment","tr-comm-newcomm":"Newer Comments \u2192","tr-comm-oldcomm":"\u2190 Older Comments","tr-comm-commreply":"Reply","tr-comm-mustlogin":"You must be logged to post a comment.","tr-comm-login":"Log in?","tr-comm-logout":"Log out?","tr-comm-loggedin":"Logged in as","tr-comm-error":"You might have left one of the fields blank, or be posting too quickly","tr-comm-thanks":"Thanks for your comment. We appreciate your response.","tr-comm-process":"Sending...","tr-comm-1comm":"thought on","tr-comm-2comm":"thoughts on","tr-disqus-title":"Disqus comments","tr-facebook-title":"Facebook comments","tr-woo-cart-title":"Cart","tr-woo-1cart":"item","tr-woo-2cart":"items","tr-months-jan":"January","tr-months-feb":"February","tr-months-mar":"March","tr-months-apr":"April","tr-months-may":"May","tr-months-jun":"June","tr-months-jul":"July","tr-months-aug":"August","tr-months-sep":"September","tr-months-oct":"October","tr-months-nov":"November","tr-months-dec":"December","tr-nav-tooltip":"Slider navigation","woo-def-color":"","woo-def-rows":"3","woo-hoverfx":"","woo-thumb-style":"style1","woo-footer-cart":"1","REDUX_last_saved":1403288226,"amy-menu-styles":0,"amy-menu-styles2":0,"redux-backup":"1"}'),



'3'         => array('alt' => 'Preset 3', 'img' => ReduxFramework::$_url . '../amy/images/themestyle5.jpg', 'presets' =>'


{"last_tab":"","site-width":"fullwidth","menu-position":"absolute","menu-width":"boxed","footer-position":"absolute","footer-width":"fullwidth","future-posts":"","disqus-id":"","menu-search":"1","logo-img":{"url":"'.get_template_directory_uri().'/images/logo.png","id":"","height":"70","width":"200","thumbnail":"'.get_template_directory_uri().'/images/logo.png"},"fav-ico":{"url":"'.get_template_directory_uri().'/images/favicon.ico","id":"","height":"","width":"","thumbnail":""},"welcome-msg":"1","welcome-msg-text":"<span class=\"content-title\">Welcome<\/span>\r\n<span id=\"input-method\">You can add your content here<\/span>","404-page":"","add-custom-css":"                .yourclass{\r\nmargin: 0 auto;\r\n}            ","add-custom-js":"                jQuery(document).ready(function(){\r\n\r\n});            ","amy-slider-style":"carousel","amy-slider-post-type":"post","amy-slider-color":"","amy-slider-hoverfx":"","amy-slider-parallax":"0","amy-slider-parallax-depth":"1.5","amy-slider-excerpt":"150","order-posts":"1","def-pagination-display":"1","amy-slider-first-post":"0","amy-slider-autorotate":"","amy-slider-autorotate-delay":"3000","footer-date":"1","footer-layout":"5","footer-text":"<strong>Copyright 2014 <\/strong>\r\nDesigned by <a href=\"http:\/\/yoursite.com\">Your Name<\/a>","fb-link-url":"https:\/\/facebook.com","tw-link-url":"https:\/\/twitter.com","gp-link-url":"https:\/\/plus.google.com\/","pi-link-url":"https:\/\/www.pinterest.com\/","yt-link-url":"","def-background":{"background-color":"#F1F1F1","background-repeat":"","background-size":"","background-attachment":"","background-position":"","background-image":"","media":{"id":"","height":"","width":"","thumbnail":""}},"def-background-boxed-opa":{"color":"#f1f1f1","alpha":"1"},"def-background-boxed":{"background-repeat":"","background-size":"","background-attachment":"","background-position":"","background-image":"","media":{"id":"","height":"","width":"","thumbnail":""}},"layerslider-bg":"","yt-active":"","yt-bg-id":"","yt-bg-cotrols":"1","yt-bg-mute":"false","yt-bg-repeat":"true","yt-bg-start":"0","bg-active":"1","bg-allpages":"0","bg-overlays":"16","bg-delay":"6000","bg-fade":"1000","bg-vposition":"center","bg-hposition":"center","bg-slides":[{"title":"","description":"","url":"","sort":"0","attachment_id":"","thumb":"'.get_template_directory_uri().'/images/defbg-150.jpg","image":"'.get_template_directory_uri().'/images/defbg.jpg","height":"1280","width":"1920"}],"body-color-scheme":"turquoise","body-typography":{"font-family":"Open Sans","font-options":"{\"variants\":[{\"id\":\"300\",\"name\":\"Book+300\"},{\"id\":\"400\",\"name\":\"Normal+400\"},{\"id\":\"600\",\"name\":\"Semi-Bold+600\"},{\"id\":\"700\",\"name\":\"Bold+700\"},{\"id\":\"800\",\"name\":\"Extra-Bold+800\"},{\"id\":\"300italic\",\"name\":\"Book+300+Italic\"},{\"id\":\"400italic\",\"name\":\"Normal+400+Italic\"},{\"id\":\"600italic\",\"name\":\"Semi-Bold+600+Italic\"},{\"id\":\"700italic\",\"name\":\"Bold+700+Italic\"},{\"id\":\"800italic\",\"name\":\"Extra-Bold+800+Italic\"}],\"subsets\":[{\"id\":\"devanagari\",\"name\":\"Devanagari\"},{\"id\":\"cyrillic\",\"name\":\"Cyrillic\"},{\"id\":\"latin\",\"name\":\"Latin\"},{\"id\":\"latin-ext\",\"name\":\"Latin+Extended\"},{\"id\":\"cyrillic-ext\",\"name\":\"Cyrillic+Extended\"},{\"id\":\"greek\",\"name\":\"Greek\"},{\"id\":\"vietnamese\",\"name\":\"Vietnamese\"},{\"id\":\"greek-ext\",\"name\":\"Greek+Extended\"}]}","google":"true","font-backup":"","font-weight":"400","font-style":"","subsets":"","font-size":"12px","color":"#777777"},"headings-typography":{"font-family":"Open Sans","font-options":"{\"variants\":[{\"id\":\"300\",\"name\":\"Book+300\"},{\"id\":\"400\",\"name\":\"Normal+400\"},{\"id\":\"600\",\"name\":\"Semi-Bold+600\"},{\"id\":\"700\",\"name\":\"Bold+700\"},{\"id\":\"800\",\"name\":\"Extra-Bold+800\"},{\"id\":\"300italic\",\"name\":\"Book+300+Italic\"},{\"id\":\"400italic\",\"name\":\"Normal+400+Italic\"},{\"id\":\"600italic\",\"name\":\"Semi-Bold+600+Italic\"},{\"id\":\"700italic\",\"name\":\"Bold+700+Italic\"},{\"id\":\"800italic\",\"name\":\"Extra-Bold+800+Italic\"}],\"subsets\":[{\"id\":\"devanagari\",\"name\":\"Devanagari\"},{\"id\":\"cyrillic\",\"name\":\"Cyrillic\"},{\"id\":\"latin\",\"name\":\"Latin\"},{\"id\":\"latin-ext\",\"name\":\"Latin+Extended\"},{\"id\":\"cyrillic-ext\",\"name\":\"Cyrillic+Extended\"},{\"id\":\"greek\",\"name\":\"Greek\"},{\"id\":\"vietnamese\",\"name\":\"Vietnamese\"},{\"id\":\"greek-ext\",\"name\":\"Greek+Extended\"}]}","google":"true","font-backup":"","font-weight":"300","font-style":"","subsets":""},"titles-typography":{"font-family":"Open Sans","font-options":"{\"variants\":[{\"id\":\"300\",\"name\":\"Book+300\"},{\"id\":\"400\",\"name\":\"Normal+400\"},{\"id\":\"600\",\"name\":\"Semi-Bold+600\"},{\"id\":\"700\",\"name\":\"Bold+700\"},{\"id\":\"800\",\"name\":\"Extra-Bold+800\"},{\"id\":\"300italic\",\"name\":\"Book+300+Italic\"},{\"id\":\"400italic\",\"name\":\"Normal+400+Italic\"},{\"id\":\"600italic\",\"name\":\"Semi-Bold+600+Italic\"},{\"id\":\"700italic\",\"name\":\"Bold+700+Italic\"},{\"id\":\"800italic\",\"name\":\"Extra-Bold+800+Italic\"}],\"subsets\":[{\"id\":\"devanagari\",\"name\":\"Devanagari\"},{\"id\":\"cyrillic\",\"name\":\"Cyrillic\"},{\"id\":\"latin\",\"name\":\"Latin\"},{\"id\":\"latin-ext\",\"name\":\"Latin+Extended\"},{\"id\":\"cyrillic-ext\",\"name\":\"Cyrillic+Extended\"},{\"id\":\"greek\",\"name\":\"Greek\"},{\"id\":\"vietnamese\",\"name\":\"Vietnamese\"},{\"id\":\"greek-ext\",\"name\":\"Greek+Extended\"}]}","google":"true","font-backup":"","font-weight":"300","font-style":"","subsets":""},"menu-background":{"color":"#ffffff","alpha":"0.94"},"menu-background-shadow":"1","menu-typography":{"font-family":"Open Sans","font-options":"{\"variants\":[{\"id\":\"300\",\"name\":\"Book+300\"},{\"id\":\"400\",\"name\":\"Normal+400\"},{\"id\":\"600\",\"name\":\"Semi-Bold+600\"},{\"id\":\"700\",\"name\":\"Bold+700\"},{\"id\":\"800\",\"name\":\"Extra-Bold+800\"},{\"id\":\"300italic\",\"name\":\"Book+300+Italic\"},{\"id\":\"400italic\",\"name\":\"Normal+400+Italic\"},{\"id\":\"600italic\",\"name\":\"Semi-Bold+600+Italic\"},{\"id\":\"700italic\",\"name\":\"Bold+700+Italic\"},{\"id\":\"800italic\",\"name\":\"Extra-Bold+800+Italic\"}],\"subsets\":[{\"id\":\"devanagari\",\"name\":\"Devanagari\"},{\"id\":\"cyrillic\",\"name\":\"Cyrillic\"},{\"id\":\"latin\",\"name\":\"Latin\"},{\"id\":\"latin-ext\",\"name\":\"Latin+Extended\"},{\"id\":\"cyrillic-ext\",\"name\":\"Cyrillic+Extended\"},{\"id\":\"greek\",\"name\":\"Greek\"},{\"id\":\"vietnamese\",\"name\":\"Vietnamese\"},{\"id\":\"greek-ext\",\"name\":\"Greek+Extended\"}]}","google":"true","font-backup":"","font-weight":"300","font-style":"","subsets":"","font-size":"13px","line-height":"18px","color":"#777777"},"submenu-background":{"color":"#ffffff","alpha":"0.94"},"submenu-border":{"border-top":"0","border-right":"0","border-bottom":"1px","border-left":"0","border-style":"solid"},"submenu-border-color":{"color":"#333333","alpha":"0.06"},"submenu-typography":{"font-family":"Open Sans","font-options":"{\"variants\":[{\"id\":\"300\",\"name\":\"Book+300\"},{\"id\":\"400\",\"name\":\"Normal+400\"},{\"id\":\"600\",\"name\":\"Semi-Bold+600\"},{\"id\":\"700\",\"name\":\"Bold+700\"},{\"id\":\"800\",\"name\":\"Extra-Bold+800\"},{\"id\":\"300italic\",\"name\":\"Book+300+Italic\"},{\"id\":\"400italic\",\"name\":\"Normal+400+Italic\"},{\"id\":\"600italic\",\"name\":\"Semi-Bold+600+Italic\"},{\"id\":\"700italic\",\"name\":\"Bold+700+Italic\"},{\"id\":\"800italic\",\"name\":\"Extra-Bold+800+Italic\"}],\"subsets\":[{\"id\":\"devanagari\",\"name\":\"Devanagari\"},{\"id\":\"cyrillic\",\"name\":\"Cyrillic\"},{\"id\":\"latin\",\"name\":\"Latin\"},{\"id\":\"latin-ext\",\"name\":\"Latin+Extended\"},{\"id\":\"cyrillic-ext\",\"name\":\"Cyrillic+Extended\"},{\"id\":\"greek\",\"name\":\"Greek\"},{\"id\":\"vietnamese\",\"name\":\"Vietnamese\"},{\"id\":\"greek-ext\",\"name\":\"Greek+Extended\"}]}","google":"true","font-backup":"","font-weight":"300","font-style":"","subsets":"","font-size":"11px","color":"#777777"},"widget-typography":{"font-family":"Open Sans","font-options":"{\"variants\":[{\"id\":\"300\",\"name\":\"Book+300\"},{\"id\":\"400\",\"name\":\"Normal+400\"},{\"id\":\"600\",\"name\":\"Semi-Bold+600\"},{\"id\":\"700\",\"name\":\"Bold+700\"},{\"id\":\"800\",\"name\":\"Extra-Bold+800\"},{\"id\":\"300italic\",\"name\":\"Book+300+Italic\"},{\"id\":\"400italic\",\"name\":\"Normal+400+Italic\"},{\"id\":\"600italic\",\"name\":\"Semi-Bold+600+Italic\"},{\"id\":\"700italic\",\"name\":\"Bold+700+Italic\"},{\"id\":\"800italic\",\"name\":\"Extra-Bold+800+Italic\"}],\"subsets\":[{\"id\":\"devanagari\",\"name\":\"Devanagari\"},{\"id\":\"cyrillic\",\"name\":\"Cyrillic\"},{\"id\":\"latin\",\"name\":\"Latin\"},{\"id\":\"latin-ext\",\"name\":\"Latin+Extended\"},{\"id\":\"cyrillic-ext\",\"name\":\"Cyrillic+Extended\"},{\"id\":\"greek\",\"name\":\"Greek\"},{\"id\":\"vietnamese\",\"name\":\"Vietnamese\"},{\"id\":\"greek-ext\",\"name\":\"Greek+Extended\"}]}","google":"true","font-backup":"","font-weight":"400","font-style":"","subsets":"","font-size":"18px"},"widget-border":{"border-top":"0","border-right":"0","border-bottom":"1px","border-left":"0","border-style":"solid"},"widget-border-color":{"color":"#e4e4e4","alpha":"1"},"page-spacing":{"margin-top":"60px","margin-bottom":"60px"},"commnets-background":{"color":"#f9f9f9","alpha":"1.0"},"input-filed-background":{"color":"#f9f9f9","alpha":"1.0"},"input-filed-border":{"border-top":"1px","border-right":"1px","border-bottom":"1px","border-left":"1px","border-style":"solid"},"input-border-color":{"color":"#ececec","alpha":"0.5"},"path-background":{"color":"#ffffff","alpha":"0.85"},"footer-background":{"color":"#52ccb3","alpha":"0.87"},"footer-background-text":{"font-weight":"","font-style":"","color":"#ffffff"},"footer-background-widget":{"color":"#fafafa","alpha":"0.91"},"footer-widget-border":{"border-top":"0","border-right":"0","border-bottom":"1px","border-left":"0","border-style":"solid","border-color":"#dbdbdb"},"footer-typography":{"font-family":"Open Sans","font-options":"{\"variants\":[{\"id\":\"300\",\"name\":\"Book+300\"},{\"id\":\"400\",\"name\":\"Normal+400\"},{\"id\":\"600\",\"name\":\"Semi-Bold+600\"},{\"id\":\"700\",\"name\":\"Bold+700\"},{\"id\":\"800\",\"name\":\"Extra-Bold+800\"},{\"id\":\"300italic\",\"name\":\"Book+300+Italic\"},{\"id\":\"400italic\",\"name\":\"Normal+400+Italic\"},{\"id\":\"600italic\",\"name\":\"Semi-Bold+600+Italic\"},{\"id\":\"700italic\",\"name\":\"Bold+700+Italic\"},{\"id\":\"800italic\",\"name\":\"Extra-Bold+800+Italic\"}],\"subsets\":[{\"id\":\"devanagari\",\"name\":\"Devanagari\"},{\"id\":\"cyrillic\",\"name\":\"Cyrillic\"},{\"id\":\"latin\",\"name\":\"Latin\"},{\"id\":\"latin-ext\",\"name\":\"Latin+Extended\"},{\"id\":\"cyrillic-ext\",\"name\":\"Cyrillic+Extended\"},{\"id\":\"greek\",\"name\":\"Greek\"},{\"id\":\"vietnamese\",\"name\":\"Vietnamese\"},{\"id\":\"greek-ext\",\"name\":\"Greek+Extended\"}]}","google":"true","font-backup":"","font-weight":"400","font-style":"","subsets":"","font-size":"12px","color":"#777777"},"loading-bacground":{"color":"#fefefe","alpha":"1"},"woo-errmsg-bg":{"color":"#f9f9f9","alpha":"1.0"},"woo-errmsg-text":{"color":"#777777","alpha":"1.0"},"woo-input-bg":{"color":"#f9f9f9","alpha":"1.0"},"woo-input-border":{"border-top":"1px","border-right":"1px","border-bottom":"1px","border-left":"1px","border-style":"solid"},"woo-input-border-color":{"color":"#ececec","alpha":"1.0"},"woo-input-color":{"color":"#777777","alpha":"1.0"},"woo-element-border":{"color":"#ececec","alpha":"1.0"},"tr-resmenu-button":"Menu","tr-resmenu-title":"Menu","tr-menu-search":"Search...","tr-footer-home-info":"posts<br>at home page","tr-footer-search-info":"results<br>found","tr-footer-archive-info":"posts<br>in archive","tr-footer-category-info":"posts<br>in ","tr-comm-name":"Name","tr-comm-email":"E-mail","tr-comm-comment":"Comment","tr-comm-title":"Leave a Reply","tr-comm-subtitle":"Your email address will not be published.","tr-comm-submit":"Post Comment","tr-comm-newcomm":"Newer Comments \u2192","tr-comm-oldcomm":"\u2190 Older Comments","tr-comm-commreply":"Reply","tr-comm-mustlogin":"You must be logged to post a comment.","tr-comm-login":"Log in?","tr-comm-logout":"Log out?","tr-comm-loggedin":"Logged in as","tr-comm-error":"You might have left one of the fields blank, or be posting too quickly","tr-comm-thanks":"Thanks for your comment. We appreciate your response.","tr-comm-process":"Sending...","tr-comm-1comm":"thought on","tr-comm-2comm":"thoughts on","tr-disqus-title":"Disqus comments","tr-facebook-title":"Facebook comments","tr-woo-cart-title":"Cart","tr-woo-1cart":"item","tr-woo-2cart":"items","tr-months-jan":"January","tr-months-feb":"February","tr-months-mar":"March","tr-months-apr":"April","tr-months-may":"May","tr-months-jun":"June","tr-months-jul":"July","tr-months-aug":"August","tr-months-sep":"September","tr-months-oct":"October","tr-months-nov":"November","tr-months-dec":"December","tr-nav-tooltip":"Slider navigation","woo-def-color":"","woo-def-rows":"3","woo-hoverfx":"","woo-thumb-style":"style1","woo-footer-cart":"1","REDUX_last_saved":1403329819,"amy-menu-styles":0,"amy-menu-styles2":0,"redux-backup":"1"}'),


'4'         => array('alt' => 'Preset 4', 'img' => ReduxFramework::$_url . '../amy/images/themestyle6.jpg', 'presets' =>'



{"last_tab":"","site-width":"fullwidth","menu-position":"fixed","menu-width":"boxed","footer-position":"fixed","footer-width":"fullwidth","future-posts":"","disqus-id":"andreyboyadzhiev","menu-search":"1","logo-img":{"url":"'.get_template_directory_uri().'/images/logo.png","id":"","height":"70","width":"200","thumbnail":"'.get_template_directory_uri().'/images/logo.png"},"fav-ico":{"url":"'.get_template_directory_uri().'/images/favicon.ico","id":"","height":"","width":"","thumbnail":""},"welcome-msg":"1","welcome-msg-text":"<span class=\"content-title\">Welcome<\/span>\r\n<span id=\"input-method\">You can add your content here<\/span>","404-page":"","add-custom-css":"                .yourclass{\r\nmargin: 0 auto;\r\n}            ","add-custom-js":"                jQuery(document).ready(function(){\r\n\r\n});            ","amy-slider-style":"spiraltop","amy-slider-post-type":"post","amy-slider-color":"as_black_style","amy-slider-hoverfx":"","amy-slider-parallax":"1","amy-slider-parallax-depth":"1.5","amy-slider-excerpt":"150","order-posts":"1","def-pagination-display":"1","amy-slider-first-post":"4","amy-slider-autorotate":"","amy-slider-autorotate-delay":"3000","footer-date":"1","footer-layout":"5","footer-text":"<strong>Copyright 2014 <\/strong>\r\nDesigned by <a href=\"http:\/\/yoursite.com\">Your Name<\/a>","fb-link-url":"https:\/\/facebook.com","tw-link-url":"https:\/\/twitter.com","gp-link-url":"https:\/\/plus.google.com\/","pi-link-url":"https:\/\/www.pinterest.com\/","yt-link-url":"","def-background":{"background-color":"#F1F1F1","background-repeat":"","background-size":"","background-attachment":"","background-position":"","background-image":"","media":{"id":"","height":"","width":"","thumbnail":""}},"def-background-boxed-opa":{"color":"#000000","alpha":"0.43"},"def-background-boxed":{"background-repeat":"","background-size":"","background-attachment":"","background-position":"","background-image":"","media":{"id":"","height":"","width":"","thumbnail":""}},"layerslider-bg":"","yt-active":"","yt-bg-id":"","yt-bg-cotrols":"1","yt-bg-mute":"false","yt-bg-repeat":"true","yt-bg-start":"0","bg-active":"1","bg-allpages":"1","bg-overlays":"16","bg-delay":"6000","bg-fade":"1000","bg-vposition":"center","bg-hposition":"center","bg-slides":[{"title":"","description":"","url":"","sort":"0","attachment_id":"","thumb":"'.get_template_directory_uri().'/images/defbg-black-150.jpg","image":"'.get_template_directory_uri().'/images/defbg-black.jpg","height":"1280","width":"1920"}],"body-color-scheme":"alizarin","body-typography":{"font-family":"Open Sans","font-options":"{\"variants\":[{\"id\":\"300\",\"name\":\"Book+300\"},{\"id\":\"400\",\"name\":\"Normal+400\"},{\"id\":\"600\",\"name\":\"Semi-Bold+600\"},{\"id\":\"700\",\"name\":\"Bold+700\"},{\"id\":\"800\",\"name\":\"Extra-Bold+800\"},{\"id\":\"300italic\",\"name\":\"Book+300+Italic\"},{\"id\":\"400italic\",\"name\":\"Normal+400+Italic\"},{\"id\":\"600italic\",\"name\":\"Semi-Bold+600+Italic\"},{\"id\":\"700italic\",\"name\":\"Bold+700+Italic\"},{\"id\":\"800italic\",\"name\":\"Extra-Bold+800+Italic\"}],\"subsets\":[{\"id\":\"devanagari\",\"name\":\"Devanagari\"},{\"id\":\"cyrillic\",\"name\":\"Cyrillic\"},{\"id\":\"latin\",\"name\":\"Latin\"},{\"id\":\"latin-ext\",\"name\":\"Latin+Extended\"},{\"id\":\"cyrillic-ext\",\"name\":\"Cyrillic+Extended\"},{\"id\":\"greek\",\"name\":\"Greek\"},{\"id\":\"vietnamese\",\"name\":\"Vietnamese\"},{\"id\":\"greek-ext\",\"name\":\"Greek+Extended\"}]}","google":"true","font-backup":"","font-weight":"400","font-style":"","subsets":"","font-size":"12px","color":"#dddddd"},"headings-typography":{"font-family":"Open Sans","font-options":"{\"variants\":[{\"id\":\"300\",\"name\":\"Book+300\"},{\"id\":\"400\",\"name\":\"Normal+400\"},{\"id\":\"600\",\"name\":\"Semi-Bold+600\"},{\"id\":\"700\",\"name\":\"Bold+700\"},{\"id\":\"800\",\"name\":\"Extra-Bold+800\"},{\"id\":\"300italic\",\"name\":\"Book+300+Italic\"},{\"id\":\"400italic\",\"name\":\"Normal+400+Italic\"},{\"id\":\"600italic\",\"name\":\"Semi-Bold+600+Italic\"},{\"id\":\"700italic\",\"name\":\"Bold+700+Italic\"},{\"id\":\"800italic\",\"name\":\"Extra-Bold+800+Italic\"}],\"subsets\":[{\"id\":\"devanagari\",\"name\":\"Devanagari\"},{\"id\":\"cyrillic\",\"name\":\"Cyrillic\"},{\"id\":\"latin\",\"name\":\"Latin\"},{\"id\":\"latin-ext\",\"name\":\"Latin+Extended\"},{\"id\":\"cyrillic-ext\",\"name\":\"Cyrillic+Extended\"},{\"id\":\"greek\",\"name\":\"Greek\"},{\"id\":\"vietnamese\",\"name\":\"Vietnamese\"},{\"id\":\"greek-ext\",\"name\":\"Greek+Extended\"}]}","google":"true","font-backup":"","font-weight":"300","font-style":"","subsets":""},"titles-typography":{"font-family":"Open Sans","font-options":"{\"variants\":[{\"id\":\"300\",\"name\":\"Book+300\"},{\"id\":\"400\",\"name\":\"Normal+400\"},{\"id\":\"600\",\"name\":\"Semi-Bold+600\"},{\"id\":\"700\",\"name\":\"Bold+700\"},{\"id\":\"800\",\"name\":\"Extra-Bold+800\"},{\"id\":\"300italic\",\"name\":\"Book+300+Italic\"},{\"id\":\"400italic\",\"name\":\"Normal+400+Italic\"},{\"id\":\"600italic\",\"name\":\"Semi-Bold+600+Italic\"},{\"id\":\"700italic\",\"name\":\"Bold+700+Italic\"},{\"id\":\"800italic\",\"name\":\"Extra-Bold+800+Italic\"}],\"subsets\":[{\"id\":\"devanagari\",\"name\":\"Devanagari\"},{\"id\":\"cyrillic\",\"name\":\"Cyrillic\"},{\"id\":\"latin\",\"name\":\"Latin\"},{\"id\":\"latin-ext\",\"name\":\"Latin+Extended\"},{\"id\":\"cyrillic-ext\",\"name\":\"Cyrillic+Extended\"},{\"id\":\"greek\",\"name\":\"Greek\"},{\"id\":\"vietnamese\",\"name\":\"Vietnamese\"},{\"id\":\"greek-ext\",\"name\":\"Greek+Extended\"}]}","google":"true","font-backup":"","font-weight":"300","font-style":"","subsets":""},"menu-background":{"color":"#000000","alpha":"0.46"},"menu-background-shadow":"1","menu-typography":{"font-family":"Open Sans","font-options":"{\"variants\":[{\"id\":\"300\",\"name\":\"Book+300\"},{\"id\":\"400\",\"name\":\"Normal+400\"},{\"id\":\"600\",\"name\":\"Semi-Bold+600\"},{\"id\":\"700\",\"name\":\"Bold+700\"},{\"id\":\"800\",\"name\":\"Extra-Bold+800\"},{\"id\":\"300italic\",\"name\":\"Book+300+Italic\"},{\"id\":\"400italic\",\"name\":\"Normal+400+Italic\"},{\"id\":\"600italic\",\"name\":\"Semi-Bold+600+Italic\"},{\"id\":\"700italic\",\"name\":\"Bold+700+Italic\"},{\"id\":\"800italic\",\"name\":\"Extra-Bold+800+Italic\"}],\"subsets\":[{\"id\":\"devanagari\",\"name\":\"Devanagari\"},{\"id\":\"cyrillic\",\"name\":\"Cyrillic\"},{\"id\":\"latin\",\"name\":\"Latin\"},{\"id\":\"latin-ext\",\"name\":\"Latin+Extended\"},{\"id\":\"cyrillic-ext\",\"name\":\"Cyrillic+Extended\"},{\"id\":\"greek\",\"name\":\"Greek\"},{\"id\":\"vietnamese\",\"name\":\"Vietnamese\"},{\"id\":\"greek-ext\",\"name\":\"Greek+Extended\"}]}","google":"true","font-backup":"","font-weight":"300","font-style":"","subsets":"","font-size":"13px","line-height":"18px","color":"#dddddd"},"submenu-background":{"color":"#050505","alpha":"0.80"},"submenu-border":{"border-top":"0","border-right":"0","border-bottom":"1px","border-left":"0","border-style":"solid"},"submenu-border-color":{"color":"#333333","alpha":"0.06"},"submenu-typography":{"font-family":"Open Sans","font-options":"{\"variants\":[{\"id\":\"300\",\"name\":\"Book+300\"},{\"id\":\"400\",\"name\":\"Normal+400\"},{\"id\":\"600\",\"name\":\"Semi-Bold+600\"},{\"id\":\"700\",\"name\":\"Bold+700\"},{\"id\":\"800\",\"name\":\"Extra-Bold+800\"},{\"id\":\"300italic\",\"name\":\"Book+300+Italic\"},{\"id\":\"400italic\",\"name\":\"Normal+400+Italic\"},{\"id\":\"600italic\",\"name\":\"Semi-Bold+600+Italic\"},{\"id\":\"700italic\",\"name\":\"Bold+700+Italic\"},{\"id\":\"800italic\",\"name\":\"Extra-Bold+800+Italic\"}],\"subsets\":[{\"id\":\"devanagari\",\"name\":\"Devanagari\"},{\"id\":\"cyrillic\",\"name\":\"Cyrillic\"},{\"id\":\"latin\",\"name\":\"Latin\"},{\"id\":\"latin-ext\",\"name\":\"Latin+Extended\"},{\"id\":\"cyrillic-ext\",\"name\":\"Cyrillic+Extended\"},{\"id\":\"greek\",\"name\":\"Greek\"},{\"id\":\"vietnamese\",\"name\":\"Vietnamese\"},{\"id\":\"greek-ext\",\"name\":\"Greek+Extended\"}]}","google":"true","font-backup":"","font-weight":"300","font-style":"","subsets":"","font-size":"11px","color":"#dddddd"},"widget-typography":{"font-family":"Open Sans","font-options":"{\"variants\":[{\"id\":\"300\",\"name\":\"Book+300\"},{\"id\":\"400\",\"name\":\"Normal+400\"},{\"id\":\"600\",\"name\":\"Semi-Bold+600\"},{\"id\":\"700\",\"name\":\"Bold+700\"},{\"id\":\"800\",\"name\":\"Extra-Bold+800\"},{\"id\":\"300italic\",\"name\":\"Book+300+Italic\"},{\"id\":\"400italic\",\"name\":\"Normal+400+Italic\"},{\"id\":\"600italic\",\"name\":\"Semi-Bold+600+Italic\"},{\"id\":\"700italic\",\"name\":\"Bold+700+Italic\"},{\"id\":\"800italic\",\"name\":\"Extra-Bold+800+Italic\"}],\"subsets\":[{\"id\":\"devanagari\",\"name\":\"Devanagari\"},{\"id\":\"cyrillic\",\"name\":\"Cyrillic\"},{\"id\":\"latin\",\"name\":\"Latin\"},{\"id\":\"latin-ext\",\"name\":\"Latin+Extended\"},{\"id\":\"cyrillic-ext\",\"name\":\"Cyrillic+Extended\"},{\"id\":\"greek\",\"name\":\"Greek\"},{\"id\":\"vietnamese\",\"name\":\"Vietnamese\"},{\"id\":\"greek-ext\",\"name\":\"Greek+Extended\"}]}","google":"true","font-backup":"","font-weight":"400","font-style":"","subsets":"","font-size":"18px"},"widget-border":{"border-top":"0","border-right":"0","border-bottom":"1px","border-left":"0","border-style":"solid"},"widget-border-color":{"color":"#8f8f8f","alpha":"0.36"},"page-spacing":{"margin-top":"60px","margin-bottom":"60px"},"commnets-background":{"color":"#0a0a0a","alpha":"0.33"},"input-filed-background":{"color":"#0a0a0a","alpha":"0.25"},"input-filed-border":{"border-top":"1px","border-right":"1px","border-bottom":"1px","border-left":"1px","border-style":"solid"},"input-border-color":{"color":"#8f8f8f","alpha":"0.50"},"path-background":{"color":"#000000","alpha":"0.33"},"footer-background":{"color":"#1a1a1a","alpha":"0.81"},"footer-background-text":{"font-weight":"","font-style":"","color":"#a3a3a3"},"footer-background-widget":{"color":"#282828","alpha":"0.73"},"footer-widget-border":{"border-top":"0","border-right":"0","border-bottom":"1px","border-left":"0","border-style":"solid","border-color":"#4f4f4f"},"footer-typography":{"font-family":"Open Sans","font-options":"{\"variants\":[{\"id\":\"300\",\"name\":\"Book+300\"},{\"id\":\"400\",\"name\":\"Normal+400\"},{\"id\":\"600\",\"name\":\"Semi-Bold+600\"},{\"id\":\"700\",\"name\":\"Bold+700\"},{\"id\":\"800\",\"name\":\"Extra-Bold+800\"},{\"id\":\"300italic\",\"name\":\"Book+300+Italic\"},{\"id\":\"400italic\",\"name\":\"Normal+400+Italic\"},{\"id\":\"600italic\",\"name\":\"Semi-Bold+600+Italic\"},{\"id\":\"700italic\",\"name\":\"Bold+700+Italic\"},{\"id\":\"800italic\",\"name\":\"Extra-Bold+800+Italic\"}],\"subsets\":[{\"id\":\"devanagari\",\"name\":\"Devanagari\"},{\"id\":\"cyrillic\",\"name\":\"Cyrillic\"},{\"id\":\"latin\",\"name\":\"Latin\"},{\"id\":\"latin-ext\",\"name\":\"Latin+Extended\"},{\"id\":\"cyrillic-ext\",\"name\":\"Cyrillic+Extended\"},{\"id\":\"greek\",\"name\":\"Greek\"},{\"id\":\"vietnamese\",\"name\":\"Vietnamese\"},{\"id\":\"greek-ext\",\"name\":\"Greek+Extended\"}]}","google":"true","font-backup":"","font-weight":"400","font-style":"","subsets":"","font-size":"12px","color":"#a9a9a9"},"loading-bacground":{"color":"#3d3d3d","alpha":"1"},"woo-errmsg-bg":{"color":"#0a0a0a","alpha":"0.34"},"woo-errmsg-text":{"color":"#dddddd","alpha":"1.00"},"woo-input-bg":{"color":"#0a0a0a","alpha":"0.29"},"woo-input-border":{"border-top":"1px","border-right":"1px","border-bottom":"1px","border-left":"1px","border-style":"solid"},"woo-input-border-color":{"color":"#8f8f8f","alpha":"0.34"},"woo-input-color":{"color":"#dddddd","alpha":"1.00"},"woo-element-border":{"color":"#8f8f8f","alpha":"0.37"},"tr-resmenu-button":"Menu","tr-resmenu-title":"Menu","tr-menu-search":"Search...","tr-footer-home-info":"posts<br>at home page","tr-footer-search-info":"results<br>found","tr-footer-archive-info":"posts<br>in archive","tr-footer-category-info":"posts<br>in ","tr-comm-name":"Name","tr-comm-email":"E-mail","tr-comm-comment":"Comment","tr-comm-title":"Leave a Reply","tr-comm-subtitle":"Your email address will not be published.","tr-comm-submit":"Post Comment","tr-comm-newcomm":"Newer Comments \u2192","tr-comm-oldcomm":"\u2190 Older Comments","tr-comm-commreply":"Reply","tr-comm-mustlogin":"You must be logged to post a comment.","tr-comm-login":"Log in?","tr-comm-logout":"Log out?","tr-comm-loggedin":"Logged in as","tr-comm-error":"You might have left one of the fields blank, or be posting too quickly","tr-comm-thanks":"Thanks for your comment. We appreciate your response.","tr-comm-process":"Sending...","tr-comm-1comm":"thought on","tr-comm-2comm":"thoughts on","tr-disqus-title":"Disqus comments","tr-facebook-title":"Facebook comments","tr-woo-cart-title":"Cart","tr-woo-1cart":"item","tr-woo-2cart":"items","tr-months-jan":"January","tr-months-feb":"February","tr-months-mar":"March","tr-months-apr":"April","tr-months-may":"May","tr-months-jun":"June","tr-months-jul":"July","tr-months-aug":"August","tr-months-sep":"September","tr-months-oct":"October","tr-months-nov":"November","tr-months-dec":"December","tr-nav-tooltip":"Slider navigation","woo-def-color":"as_black_style","woo-def-rows":"3","woo-hoverfx":"","woo-thumb-style":"style2","woo-footer-cart":"1","REDUX_last_saved":1403340021,"amy-menu-styles":0,"amy-menu-styles2":0,"redux-backup":"1"}'),

						'5'         => array('alt' => 'Preset 5', 'img' => ReduxFramework::$_url . '../amy/images/themestyle3.jpg', 'presets' =>'

{"last_tab":"","site-width":"boxed","menu-position":"fixed","menu-width":"boxed","footer-position":"absolute","footer-width":"boxed","future-posts":"","disqus-id":"andreyboyadzhiev","menu-search":"1","logo-img":{"url":"'.get_template_directory_uri().'/images/logo.png","id":"","height":"70","width":"200","thumbnail":"'.get_template_directory_uri().'/images/logo.png"},"fav-ico":{"url":"'.get_template_directory_uri().'/images/favicon.ico","id":"","height":"","width":"","thumbnail":""},"welcome-msg":"1","welcome-msg-text":"<span class=\"content-title\">Welcome<\/span>\r\n<span id=\"input-method\">You can add your content here<\/span>","404-page":"","add-custom-css":"                .yourclass{\r\nmargin: 0 auto;\r\n}            ","add-custom-js":"                jQuery(document).ready(function(){\r\n\r\n});            ","amy-slider-style":"coverflow","amy-slider-post-type":"post","amy-slider-color":"as_black_style","amy-slider-hoverfx":"","amy-slider-parallax":"1","amy-slider-parallax-depth":"1.5","amy-slider-excerpt":"150","order-posts":"1","def-pagination-display":"1","amy-slider-first-post":"4","amy-slider-autorotate":"","amy-slider-autorotate-delay":"3000","footer-date":"1","footer-layout":"5","footer-text":"<strong>Copyright 2014 <\/strong>\r\nDesigned by <a href=\"http:\/\/yoursite.com\">Your Name<\/a>","fb-link-url":"https:\/\/facebook.com","tw-link-url":"https:\/\/twitter.com","gp-link-url":"https:\/\/plus.google.com\/","pi-link-url":"https:\/\/www.pinterest.com\/","yt-link-url":"","def-background":{"background-color":"#F1F1F1","background-repeat":"","background-size":"","background-attachment":"","background-position":"","background-image":"","media":{"id":"","height":"","width":"","thumbnail":""}},"def-background-boxed-opa":{"color":"#000000","alpha":"0.43"},"def-background-boxed":{"background-repeat":"","background-size":"","background-attachment":"","background-position":"","background-image":"","media":{"id":"","height":"","width":"","thumbnail":""}},"layerslider-bg":"","yt-active":"","yt-bg-id":"","yt-bg-cotrols":"1","yt-bg-mute":"false","yt-bg-repeat":"true","yt-bg-start":"0","bg-active":"1","bg-allpages":"1","bg-overlays":"16","bg-delay":"6000","bg-fade":"1000","bg-vposition":"center","bg-hposition":"center","bg-slides":[{"title":"","description":"","url":"","sort":"0","attachment_id":"","thumb":"'.get_template_directory_uri().'/images/defbg-black-150.jpg","image":"'.get_template_directory_uri().'/images/defbg-black.jpg","height":"1280","width":"1920"}],"body-color-scheme":"alizarin","body-typography":{"font-family":"Open Sans","font-options":"{\"variants\":[{\"id\":\"300\",\"name\":\"Book+300\"},{\"id\":\"400\",\"name\":\"Normal+400\"},{\"id\":\"600\",\"name\":\"Semi-Bold+600\"},{\"id\":\"700\",\"name\":\"Bold+700\"},{\"id\":\"800\",\"name\":\"Extra-Bold+800\"},{\"id\":\"300italic\",\"name\":\"Book+300+Italic\"},{\"id\":\"400italic\",\"name\":\"Normal+400+Italic\"},{\"id\":\"600italic\",\"name\":\"Semi-Bold+600+Italic\"},{\"id\":\"700italic\",\"name\":\"Bold+700+Italic\"},{\"id\":\"800italic\",\"name\":\"Extra-Bold+800+Italic\"}],\"subsets\":[{\"id\":\"devanagari\",\"name\":\"Devanagari\"},{\"id\":\"cyrillic\",\"name\":\"Cyrillic\"},{\"id\":\"latin\",\"name\":\"Latin\"},{\"id\":\"latin-ext\",\"name\":\"Latin+Extended\"},{\"id\":\"cyrillic-ext\",\"name\":\"Cyrillic+Extended\"},{\"id\":\"greek\",\"name\":\"Greek\"},{\"id\":\"vietnamese\",\"name\":\"Vietnamese\"},{\"id\":\"greek-ext\",\"name\":\"Greek+Extended\"}]}","google":"true","font-backup":"","font-weight":"400","font-style":"","subsets":"","font-size":"12px","color":"#dddddd"},"headings-typography":{"font-family":"Open Sans","font-options":"{\"variants\":[{\"id\":\"300\",\"name\":\"Book+300\"},{\"id\":\"400\",\"name\":\"Normal+400\"},{\"id\":\"600\",\"name\":\"Semi-Bold+600\"},{\"id\":\"700\",\"name\":\"Bold+700\"},{\"id\":\"800\",\"name\":\"Extra-Bold+800\"},{\"id\":\"300italic\",\"name\":\"Book+300+Italic\"},{\"id\":\"400italic\",\"name\":\"Normal+400+Italic\"},{\"id\":\"600italic\",\"name\":\"Semi-Bold+600+Italic\"},{\"id\":\"700italic\",\"name\":\"Bold+700+Italic\"},{\"id\":\"800italic\",\"name\":\"Extra-Bold+800+Italic\"}],\"subsets\":[{\"id\":\"devanagari\",\"name\":\"Devanagari\"},{\"id\":\"cyrillic\",\"name\":\"Cyrillic\"},{\"id\":\"latin\",\"name\":\"Latin\"},{\"id\":\"latin-ext\",\"name\":\"Latin+Extended\"},{\"id\":\"cyrillic-ext\",\"name\":\"Cyrillic+Extended\"},{\"id\":\"greek\",\"name\":\"Greek\"},{\"id\":\"vietnamese\",\"name\":\"Vietnamese\"},{\"id\":\"greek-ext\",\"name\":\"Greek+Extended\"}]}","google":"true","font-backup":"","font-weight":"300","font-style":"","subsets":""},"titles-typography":{"font-family":"Open Sans","font-options":"{\"variants\":[{\"id\":\"300\",\"name\":\"Book+300\"},{\"id\":\"400\",\"name\":\"Normal+400\"},{\"id\":\"600\",\"name\":\"Semi-Bold+600\"},{\"id\":\"700\",\"name\":\"Bold+700\"},{\"id\":\"800\",\"name\":\"Extra-Bold+800\"},{\"id\":\"300italic\",\"name\":\"Book+300+Italic\"},{\"id\":\"400italic\",\"name\":\"Normal+400+Italic\"},{\"id\":\"600italic\",\"name\":\"Semi-Bold+600+Italic\"},{\"id\":\"700italic\",\"name\":\"Bold+700+Italic\"},{\"id\":\"800italic\",\"name\":\"Extra-Bold+800+Italic\"}],\"subsets\":[{\"id\":\"devanagari\",\"name\":\"Devanagari\"},{\"id\":\"cyrillic\",\"name\":\"Cyrillic\"},{\"id\":\"latin\",\"name\":\"Latin\"},{\"id\":\"latin-ext\",\"name\":\"Latin+Extended\"},{\"id\":\"cyrillic-ext\",\"name\":\"Cyrillic+Extended\"},{\"id\":\"greek\",\"name\":\"Greek\"},{\"id\":\"vietnamese\",\"name\":\"Vietnamese\"},{\"id\":\"greek-ext\",\"name\":\"Greek+Extended\"}]}","google":"true","font-backup":"","font-weight":"300","font-style":"","subsets":""},"menu-background":{"color":"#000000","alpha":"0.46"},"menu-background-shadow":"1","menu-typography":{"font-family":"Open Sans","font-options":"{\"variants\":[{\"id\":\"300\",\"name\":\"Book+300\"},{\"id\":\"400\",\"name\":\"Normal+400\"},{\"id\":\"600\",\"name\":\"Semi-Bold+600\"},{\"id\":\"700\",\"name\":\"Bold+700\"},{\"id\":\"800\",\"name\":\"Extra-Bold+800\"},{\"id\":\"300italic\",\"name\":\"Book+300+Italic\"},{\"id\":\"400italic\",\"name\":\"Normal+400+Italic\"},{\"id\":\"600italic\",\"name\":\"Semi-Bold+600+Italic\"},{\"id\":\"700italic\",\"name\":\"Bold+700+Italic\"},{\"id\":\"800italic\",\"name\":\"Extra-Bold+800+Italic\"}],\"subsets\":[{\"id\":\"devanagari\",\"name\":\"Devanagari\"},{\"id\":\"cyrillic\",\"name\":\"Cyrillic\"},{\"id\":\"latin\",\"name\":\"Latin\"},{\"id\":\"latin-ext\",\"name\":\"Latin+Extended\"},{\"id\":\"cyrillic-ext\",\"name\":\"Cyrillic+Extended\"},{\"id\":\"greek\",\"name\":\"Greek\"},{\"id\":\"vietnamese\",\"name\":\"Vietnamese\"},{\"id\":\"greek-ext\",\"name\":\"Greek+Extended\"}]}","google":"true","font-backup":"","font-weight":"300","font-style":"","subsets":"","font-size":"13px","line-height":"18px","color":"#dddddd"},"submenu-background":{"color":"#050505","alpha":"0.80"},"submenu-border":{"border-top":"0","border-right":"0","border-bottom":"1px","border-left":"0","border-style":"solid"},"submenu-border-color":{"color":"#333333","alpha":"0.06"},"submenu-typography":{"font-family":"Open Sans","font-options":"{\"variants\":[{\"id\":\"300\",\"name\":\"Book+300\"},{\"id\":\"400\",\"name\":\"Normal+400\"},{\"id\":\"600\",\"name\":\"Semi-Bold+600\"},{\"id\":\"700\",\"name\":\"Bold+700\"},{\"id\":\"800\",\"name\":\"Extra-Bold+800\"},{\"id\":\"300italic\",\"name\":\"Book+300+Italic\"},{\"id\":\"400italic\",\"name\":\"Normal+400+Italic\"},{\"id\":\"600italic\",\"name\":\"Semi-Bold+600+Italic\"},{\"id\":\"700italic\",\"name\":\"Bold+700+Italic\"},{\"id\":\"800italic\",\"name\":\"Extra-Bold+800+Italic\"}],\"subsets\":[{\"id\":\"devanagari\",\"name\":\"Devanagari\"},{\"id\":\"cyrillic\",\"name\":\"Cyrillic\"},{\"id\":\"latin\",\"name\":\"Latin\"},{\"id\":\"latin-ext\",\"name\":\"Latin+Extended\"},{\"id\":\"cyrillic-ext\",\"name\":\"Cyrillic+Extended\"},{\"id\":\"greek\",\"name\":\"Greek\"},{\"id\":\"vietnamese\",\"name\":\"Vietnamese\"},{\"id\":\"greek-ext\",\"name\":\"Greek+Extended\"}]}","google":"true","font-backup":"","font-weight":"300","font-style":"","subsets":"","font-size":"11px","color":"#dddddd"},"widget-typography":{"font-family":"Open Sans","font-options":"{\"variants\":[{\"id\":\"300\",\"name\":\"Book+300\"},{\"id\":\"400\",\"name\":\"Normal+400\"},{\"id\":\"600\",\"name\":\"Semi-Bold+600\"},{\"id\":\"700\",\"name\":\"Bold+700\"},{\"id\":\"800\",\"name\":\"Extra-Bold+800\"},{\"id\":\"300italic\",\"name\":\"Book+300+Italic\"},{\"id\":\"400italic\",\"name\":\"Normal+400+Italic\"},{\"id\":\"600italic\",\"name\":\"Semi-Bold+600+Italic\"},{\"id\":\"700italic\",\"name\":\"Bold+700+Italic\"},{\"id\":\"800italic\",\"name\":\"Extra-Bold+800+Italic\"}],\"subsets\":[{\"id\":\"devanagari\",\"name\":\"Devanagari\"},{\"id\":\"cyrillic\",\"name\":\"Cyrillic\"},{\"id\":\"latin\",\"name\":\"Latin\"},{\"id\":\"latin-ext\",\"name\":\"Latin+Extended\"},{\"id\":\"cyrillic-ext\",\"name\":\"Cyrillic+Extended\"},{\"id\":\"greek\",\"name\":\"Greek\"},{\"id\":\"vietnamese\",\"name\":\"Vietnamese\"},{\"id\":\"greek-ext\",\"name\":\"Greek+Extended\"}]}","google":"true","font-backup":"","font-weight":"400","font-style":"","subsets":"","font-size":"18px"},"widget-border":{"border-top":"0","border-right":"0","border-bottom":"1px","border-left":"0","border-style":"solid"},"widget-border-color":{"color":"#8f8f8f","alpha":"0.36"},"page-spacing":{"margin-top":"60px","margin-bottom":"60px"},"commnets-background":{"color":"#0a0a0a","alpha":"0.33"},"input-filed-background":{"color":"#0a0a0a","alpha":"0.25"},"input-filed-border":{"border-top":"1px","border-right":"1px","border-bottom":"1px","border-left":"1px","border-style":"solid"},"input-border-color":{"color":"#8f8f8f","alpha":"0.50"},"path-background":{"color":"#000000","alpha":"0.33"},"footer-background":{"color":"#1a1a1a","alpha":"0.81"},"footer-background-text":{"font-weight":"","font-style":"","color":"#a3a3a3"},"footer-background-widget":{"color":"#282828","alpha":"0.73"},"footer-widget-border":{"border-top":"0","border-right":"0","border-bottom":"1px","border-left":"0","border-style":"solid","border-color":"#4f4f4f"},"footer-typography":{"font-family":"Open Sans","font-options":"{\"variants\":[{\"id\":\"300\",\"name\":\"Book+300\"},{\"id\":\"400\",\"name\":\"Normal+400\"},{\"id\":\"600\",\"name\":\"Semi-Bold+600\"},{\"id\":\"700\",\"name\":\"Bold+700\"},{\"id\":\"800\",\"name\":\"Extra-Bold+800\"},{\"id\":\"300italic\",\"name\":\"Book+300+Italic\"},{\"id\":\"400italic\",\"name\":\"Normal+400+Italic\"},{\"id\":\"600italic\",\"name\":\"Semi-Bold+600+Italic\"},{\"id\":\"700italic\",\"name\":\"Bold+700+Italic\"},{\"id\":\"800italic\",\"name\":\"Extra-Bold+800+Italic\"}],\"subsets\":[{\"id\":\"devanagari\",\"name\":\"Devanagari\"},{\"id\":\"cyrillic\",\"name\":\"Cyrillic\"},{\"id\":\"latin\",\"name\":\"Latin\"},{\"id\":\"latin-ext\",\"name\":\"Latin+Extended\"},{\"id\":\"cyrillic-ext\",\"name\":\"Cyrillic+Extended\"},{\"id\":\"greek\",\"name\":\"Greek\"},{\"id\":\"vietnamese\",\"name\":\"Vietnamese\"},{\"id\":\"greek-ext\",\"name\":\"Greek+Extended\"}]}","google":"true","font-backup":"","font-weight":"400","font-style":"","subsets":"","font-size":"12px","color":"#a9a9a9"},"loading-bacground":{"color":"#3d3d3d","alpha":"1"},"woo-errmsg-bg":{"color":"#0a0a0a","alpha":"0.34"},"woo-errmsg-text":{"color":"#dddddd","alpha":"1.00"},"woo-input-bg":{"color":"#0a0a0a","alpha":"0.29"},"woo-input-border":{"border-top":"1px","border-right":"1px","border-bottom":"1px","border-left":"1px","border-style":"solid"},"woo-input-border-color":{"color":"#8f8f8f","alpha":"0.34"},"woo-input-color":{"color":"#dddddd","alpha":"1.00"},"woo-element-border":{"color":"#8f8f8f","alpha":"0.37"},"tr-resmenu-button":"Menu","tr-resmenu-title":"Menu","tr-menu-search":"Search...","tr-footer-home-info":"posts<br>at home page","tr-footer-search-info":"results<br>found","tr-footer-archive-info":"posts<br>in archive","tr-footer-category-info":"posts<br>in ","tr-comm-name":"Name","tr-comm-email":"E-mail","tr-comm-comment":"Comment","tr-comm-title":"Leave a Reply","tr-comm-subtitle":"Your email address will not be published.","tr-comm-submit":"Post Comment","tr-comm-newcomm":"Newer Comments \u2192","tr-comm-oldcomm":"\u2190 Older Comments","tr-comm-commreply":"Reply","tr-comm-mustlogin":"You must be logged to post a comment.","tr-comm-login":"Log in?","tr-comm-logout":"Log out?","tr-comm-loggedin":"Logged in as","tr-comm-error":"You might have left one of the fields blank, or be posting too quickly","tr-comm-thanks":"Thanks for your comment. We appreciate your response.","tr-comm-process":"Sending...","tr-comm-1comm":"thought on","tr-comm-2comm":"thoughts on","tr-disqus-title":"Disqus comments","tr-facebook-title":"Facebook comments","tr-woo-cart-title":"Cart","tr-woo-1cart":"item","tr-woo-2cart":"items","tr-months-jan":"January","tr-months-feb":"February","tr-months-mar":"March","tr-months-apr":"April","tr-months-may":"May","tr-months-jun":"June","tr-months-jul":"July","tr-months-aug":"August","tr-months-sep":"September","tr-months-oct":"October","tr-months-nov":"November","tr-months-dec":"December","tr-nav-tooltip":"Slider navigation","woo-def-color":"as_black_style","woo-def-rows":"3","woo-hoverfx":"","woo-thumb-style":"style2","woo-footer-cart":"1","REDUX_last_saved":1403294162,"amy-menu-styles":0,"amy-menu-styles2":0,"redux-backup":"1"}'),

						'6'         => array('alt' => 'Preset 6', 'img' => ReduxFramework::$_url . '../amy/images/themestyle4.jpg', 'presets' =>'


{"last_tab":"","site-width":"fullwidth","menu-position":"fixed","menu-width":"fullwidth","footer-position":"absolute","footer-width":"fullwidth","future-posts":"","disqus-id":"andreyboyadzhiev","menu-search":"1","logo-img":{"url":"'.get_template_directory_uri().'/images/logo.png","id":"","height":"70","width":"200","thumbnail":"'.get_template_directory_uri().'/images/logo.png"},"fav-ico":{"url":"'.get_template_directory_uri().'/images/favicon.ico","id":"","height":"","width":"","thumbnail":""},"welcome-msg":"1","welcome-msg-text":"<span class=\"content-title\">Welcome<\/span>\r\n<span id=\"input-method\">You can add your content here<\/span>","404-page":"","add-custom-css":"                .yourclass{\r\nmargin: 0 auto;\r\n}            ","add-custom-js":"                jQuery(document).ready(function(){\r\n\r\n});            ","amy-slider-style":"classic","amy-slider-post-type":"post","amy-slider-color":"as_black_style","amy-slider-hoverfx":"","amy-slider-parallax":"0","amy-slider-parallax-depth":"1.5","amy-slider-excerpt":"150","order-posts":"1","def-pagination-display":"2","amy-slider-first-post":"4","amy-slider-autorotate":"","amy-slider-autorotate-delay":"3000","footer-date":"1","footer-layout":"5","footer-text":"<strong>Copyright 2014 <\/strong>\r\nDesigned by <a href=\"http:\/\/yoursite.com\">Your Name<\/a>","fb-link-url":"https:\/\/facebook.com","tw-link-url":"https:\/\/twitter.com","gp-link-url":"https:\/\/plus.google.com\/","pi-link-url":"https:\/\/www.pinterest.com\/","yt-link-url":"","def-background":{"background-color":"#3f3f3f","background-repeat":"","background-size":"","background-attachment":"","background-position":"","background-image":"","media":{"id":"","height":"","width":"","thumbnail":""}},"def-background-boxed-opa":{"color":"#000000","alpha":"0.43"},"def-background-boxed":{"background-repeat":"","background-size":"","background-attachment":"","background-position":"","background-image":"","media":{"id":"","height":"","width":"","thumbnail":""}},"layerslider-bg":"","yt-active":"0","yt-bg-id":"piH5_aP0fY8","yt-bg-cotrols":"1","yt-bg-mute":"false","yt-bg-repeat":"true","yt-bg-start":"0","bg-active":"1","bg-allpages":"0","bg-overlays":"16","bg-delay":"6000","bg-fade":"1000","bg-vposition":"center","bg-hposition":"center","bg-slides":[{"title":"","description":"","url":"","sort":"0","attachment_id":"","thumb":"'.get_template_directory_uri().'/images/defbg-black-150.jpg","image":"'.get_template_directory_uri().'/images/defbg-black.jpg","height":"1280","width":"1920"}],"body-color-scheme":"peterriver","body-typography":{"font-family":"Open Sans","font-options":"{\"variants\":[{\"id\":\"300\",\"name\":\"Book+300\"},{\"id\":\"400\",\"name\":\"Normal+400\"},{\"id\":\"600\",\"name\":\"Semi-Bold+600\"},{\"id\":\"700\",\"name\":\"Bold+700\"},{\"id\":\"800\",\"name\":\"Extra-Bold+800\"},{\"id\":\"300italic\",\"name\":\"Book+300+Italic\"},{\"id\":\"400italic\",\"name\":\"Normal+400+Italic\"},{\"id\":\"600italic\",\"name\":\"Semi-Bold+600+Italic\"},{\"id\":\"700italic\",\"name\":\"Bold+700+Italic\"},{\"id\":\"800italic\",\"name\":\"Extra-Bold+800+Italic\"}],\"subsets\":[{\"id\":\"devanagari\",\"name\":\"Devanagari\"},{\"id\":\"cyrillic\",\"name\":\"Cyrillic\"},{\"id\":\"latin\",\"name\":\"Latin\"},{\"id\":\"latin-ext\",\"name\":\"Latin+Extended\"},{\"id\":\"cyrillic-ext\",\"name\":\"Cyrillic+Extended\"},{\"id\":\"greek\",\"name\":\"Greek\"},{\"id\":\"vietnamese\",\"name\":\"Vietnamese\"},{\"id\":\"greek-ext\",\"name\":\"Greek+Extended\"}]}","google":"true","font-backup":"","font-weight":"400","font-style":"","subsets":"","font-size":"12px","color":"#dddddd"},"headings-typography":{"font-family":"Open Sans","font-options":"{\"variants\":[{\"id\":\"300\",\"name\":\"Book+300\"},{\"id\":\"400\",\"name\":\"Normal+400\"},{\"id\":\"600\",\"name\":\"Semi-Bold+600\"},{\"id\":\"700\",\"name\":\"Bold+700\"},{\"id\":\"800\",\"name\":\"Extra-Bold+800\"},{\"id\":\"300italic\",\"name\":\"Book+300+Italic\"},{\"id\":\"400italic\",\"name\":\"Normal+400+Italic\"},{\"id\":\"600italic\",\"name\":\"Semi-Bold+600+Italic\"},{\"id\":\"700italic\",\"name\":\"Bold+700+Italic\"},{\"id\":\"800italic\",\"name\":\"Extra-Bold+800+Italic\"}],\"subsets\":[{\"id\":\"devanagari\",\"name\":\"Devanagari\"},{\"id\":\"cyrillic\",\"name\":\"Cyrillic\"},{\"id\":\"latin\",\"name\":\"Latin\"},{\"id\":\"latin-ext\",\"name\":\"Latin+Extended\"},{\"id\":\"cyrillic-ext\",\"name\":\"Cyrillic+Extended\"},{\"id\":\"greek\",\"name\":\"Greek\"},{\"id\":\"vietnamese\",\"name\":\"Vietnamese\"},{\"id\":\"greek-ext\",\"name\":\"Greek+Extended\"}]}","google":"true","font-backup":"","font-weight":"300","font-style":"","subsets":""},"titles-typography":{"font-family":"Open Sans","font-options":"{\"variants\":[{\"id\":\"300\",\"name\":\"Book+300\"},{\"id\":\"400\",\"name\":\"Normal+400\"},{\"id\":\"600\",\"name\":\"Semi-Bold+600\"},{\"id\":\"700\",\"name\":\"Bold+700\"},{\"id\":\"800\",\"name\":\"Extra-Bold+800\"},{\"id\":\"300italic\",\"name\":\"Book+300+Italic\"},{\"id\":\"400italic\",\"name\":\"Normal+400+Italic\"},{\"id\":\"600italic\",\"name\":\"Semi-Bold+600+Italic\"},{\"id\":\"700italic\",\"name\":\"Bold+700+Italic\"},{\"id\":\"800italic\",\"name\":\"Extra-Bold+800+Italic\"}],\"subsets\":[{\"id\":\"devanagari\",\"name\":\"Devanagari\"},{\"id\":\"cyrillic\",\"name\":\"Cyrillic\"},{\"id\":\"latin\",\"name\":\"Latin\"},{\"id\":\"latin-ext\",\"name\":\"Latin+Extended\"},{\"id\":\"cyrillic-ext\",\"name\":\"Cyrillic+Extended\"},{\"id\":\"greek\",\"name\":\"Greek\"},{\"id\":\"vietnamese\",\"name\":\"Vietnamese\"},{\"id\":\"greek-ext\",\"name\":\"Greek+Extended\"}]}","google":"true","font-backup":"","font-weight":"300","font-style":"","subsets":""},"menu-background":{"color":"#000000","alpha":"0.46"},"menu-background-shadow":"1","menu-typography":{"font-family":"Open Sans","font-options":"{\"variants\":[{\"id\":\"300\",\"name\":\"Book+300\"},{\"id\":\"400\",\"name\":\"Normal+400\"},{\"id\":\"600\",\"name\":\"Semi-Bold+600\"},{\"id\":\"700\",\"name\":\"Bold+700\"},{\"id\":\"800\",\"name\":\"Extra-Bold+800\"},{\"id\":\"300italic\",\"name\":\"Book+300+Italic\"},{\"id\":\"400italic\",\"name\":\"Normal+400+Italic\"},{\"id\":\"600italic\",\"name\":\"Semi-Bold+600+Italic\"},{\"id\":\"700italic\",\"name\":\"Bold+700+Italic\"},{\"id\":\"800italic\",\"name\":\"Extra-Bold+800+Italic\"}],\"subsets\":[{\"id\":\"devanagari\",\"name\":\"Devanagari\"},{\"id\":\"cyrillic\",\"name\":\"Cyrillic\"},{\"id\":\"latin\",\"name\":\"Latin\"},{\"id\":\"latin-ext\",\"name\":\"Latin+Extended\"},{\"id\":\"cyrillic-ext\",\"name\":\"Cyrillic+Extended\"},{\"id\":\"greek\",\"name\":\"Greek\"},{\"id\":\"vietnamese\",\"name\":\"Vietnamese\"},{\"id\":\"greek-ext\",\"name\":\"Greek+Extended\"}]}","google":"true","font-backup":"","font-weight":"300","font-style":"","subsets":"","font-size":"13px","line-height":"18px","color":"#dddddd"},"submenu-background":{"color":"#050505","alpha":"0.80"},"submenu-border":{"border-top":"0","border-right":"0","border-bottom":"1px","border-left":"0","border-style":"solid"},"submenu-border-color":{"color":"#333333","alpha":"0.06"},"submenu-typography":{"font-family":"Open Sans","font-options":"{\"variants\":[{\"id\":\"300\",\"name\":\"Book+300\"},{\"id\":\"400\",\"name\":\"Normal+400\"},{\"id\":\"600\",\"name\":\"Semi-Bold+600\"},{\"id\":\"700\",\"name\":\"Bold+700\"},{\"id\":\"800\",\"name\":\"Extra-Bold+800\"},{\"id\":\"300italic\",\"name\":\"Book+300+Italic\"},{\"id\":\"400italic\",\"name\":\"Normal+400+Italic\"},{\"id\":\"600italic\",\"name\":\"Semi-Bold+600+Italic\"},{\"id\":\"700italic\",\"name\":\"Bold+700+Italic\"},{\"id\":\"800italic\",\"name\":\"Extra-Bold+800+Italic\"}],\"subsets\":[{\"id\":\"devanagari\",\"name\":\"Devanagari\"},{\"id\":\"cyrillic\",\"name\":\"Cyrillic\"},{\"id\":\"latin\",\"name\":\"Latin\"},{\"id\":\"latin-ext\",\"name\":\"Latin+Extended\"},{\"id\":\"cyrillic-ext\",\"name\":\"Cyrillic+Extended\"},{\"id\":\"greek\",\"name\":\"Greek\"},{\"id\":\"vietnamese\",\"name\":\"Vietnamese\"},{\"id\":\"greek-ext\",\"name\":\"Greek+Extended\"}]}","google":"true","font-backup":"","font-weight":"300","font-style":"","subsets":"","font-size":"11px","color":"#dddddd"},"widget-typography":{"font-family":"Open Sans","font-options":"{\"variants\":[{\"id\":\"300\",\"name\":\"Book+300\"},{\"id\":\"400\",\"name\":\"Normal+400\"},{\"id\":\"600\",\"name\":\"Semi-Bold+600\"},{\"id\":\"700\",\"name\":\"Bold+700\"},{\"id\":\"800\",\"name\":\"Extra-Bold+800\"},{\"id\":\"300italic\",\"name\":\"Book+300+Italic\"},{\"id\":\"400italic\",\"name\":\"Normal+400+Italic\"},{\"id\":\"600italic\",\"name\":\"Semi-Bold+600+Italic\"},{\"id\":\"700italic\",\"name\":\"Bold+700+Italic\"},{\"id\":\"800italic\",\"name\":\"Extra-Bold+800+Italic\"}],\"subsets\":[{\"id\":\"devanagari\",\"name\":\"Devanagari\"},{\"id\":\"cyrillic\",\"name\":\"Cyrillic\"},{\"id\":\"latin\",\"name\":\"Latin\"},{\"id\":\"latin-ext\",\"name\":\"Latin+Extended\"},{\"id\":\"cyrillic-ext\",\"name\":\"Cyrillic+Extended\"},{\"id\":\"greek\",\"name\":\"Greek\"},{\"id\":\"vietnamese\",\"name\":\"Vietnamese\"},{\"id\":\"greek-ext\",\"name\":\"Greek+Extended\"}]}","google":"true","font-backup":"","font-weight":"400","font-style":"","subsets":"","font-size":"18px"},"widget-border":{"border-top":"0","border-right":"0","border-bottom":"1px","border-left":"0","border-style":"solid"},"widget-border-color":{"color":"#8f8f8f","alpha":"0.36"},"page-spacing":{"margin-top":"60px","margin-bottom":"60px"},"commnets-background":{"color":"#0a0a0a","alpha":"0.33"},"input-filed-background":{"color":"#0a0a0a","alpha":"0.25"},"input-filed-border":{"border-top":"1px","border-right":"1px","border-bottom":"1px","border-left":"1px","border-style":"solid"},"input-border-color":{"color":"#8f8f8f","alpha":"0.50"},"path-background":{"color":"#000000","alpha":"0.33"},"footer-background":{"color":"#1a1a1a","alpha":"0.81"},"footer-background-text":{"font-weight":"","font-style":"","color":"#a3a3a3"},"footer-background-widget":{"color":"#282828","alpha":"0.73"},"footer-widget-border":{"border-top":"0","border-right":"0","border-bottom":"1px","border-left":"0","border-style":"solid","border-color":"#4f4f4f"},"footer-typography":{"font-family":"Open Sans","font-options":"{\"variants\":[{\"id\":\"300\",\"name\":\"Book+300\"},{\"id\":\"400\",\"name\":\"Normal+400\"},{\"id\":\"600\",\"name\":\"Semi-Bold+600\"},{\"id\":\"700\",\"name\":\"Bold+700\"},{\"id\":\"800\",\"name\":\"Extra-Bold+800\"},{\"id\":\"300italic\",\"name\":\"Book+300+Italic\"},{\"id\":\"400italic\",\"name\":\"Normal+400+Italic\"},{\"id\":\"600italic\",\"name\":\"Semi-Bold+600+Italic\"},{\"id\":\"700italic\",\"name\":\"Bold+700+Italic\"},{\"id\":\"800italic\",\"name\":\"Extra-Bold+800+Italic\"}],\"subsets\":[{\"id\":\"devanagari\",\"name\":\"Devanagari\"},{\"id\":\"cyrillic\",\"name\":\"Cyrillic\"},{\"id\":\"latin\",\"name\":\"Latin\"},{\"id\":\"latin-ext\",\"name\":\"Latin+Extended\"},{\"id\":\"cyrillic-ext\",\"name\":\"Cyrillic+Extended\"},{\"id\":\"greek\",\"name\":\"Greek\"},{\"id\":\"vietnamese\",\"name\":\"Vietnamese\"},{\"id\":\"greek-ext\",\"name\":\"Greek+Extended\"}]}","google":"true","font-backup":"","font-weight":"400","font-style":"","subsets":"","font-size":"12px","color":"#a9a9a9"},"loading-bacground":{"color":"#3d3d3d","alpha":"1"},"woo-errmsg-bg":{"color":"#0a0a0a","alpha":"0.34"},"woo-errmsg-text":{"color":"#dddddd","alpha":"1.00"},"woo-input-bg":{"color":"#0a0a0a","alpha":"0.29"},"woo-input-border":{"border-top":"1px","border-right":"1px","border-bottom":"1px","border-left":"1px","border-style":"solid"},"woo-input-border-color":{"color":"#8f8f8f","alpha":"0.34"},"woo-input-color":{"color":"#dddddd","alpha":"1.00"},"woo-element-border":{"color":"#8f8f8f","alpha":"0.37"},"tr-resmenu-button":"Menu","tr-resmenu-title":"Menu","tr-menu-search":"Search...","tr-footer-home-info":"posts<br>at home page","tr-footer-search-info":"results<br>found","tr-footer-archive-info":"posts<br>in archive","tr-footer-category-info":"posts<br>in ","tr-comm-name":"Name","tr-comm-email":"E-mail","tr-comm-comment":"Comment","tr-comm-title":"Leave a Reply","tr-comm-subtitle":"Your email address will not be published.","tr-comm-submit":"Post Comment","tr-comm-newcomm":"Newer Comments \u2192","tr-comm-oldcomm":"\u2190 Older Comments","tr-comm-commreply":"Reply","tr-comm-mustlogin":"You must be logged to post a comment.","tr-comm-login":"Log in?","tr-comm-logout":"Log out?","tr-comm-loggedin":"Logged in as","tr-comm-error":"You might have left one of the fields blank, or be posting too quickly","tr-comm-thanks":"Thanks for your comment. We appreciate your response.","tr-comm-process":"Sending...","tr-comm-1comm":"thought on","tr-comm-2comm":"thoughts on","tr-disqus-title":"Disqus comments","tr-facebook-title":"Facebook comments","tr-woo-cart-title":"Cart","tr-woo-1cart":"item","tr-woo-2cart":"items","tr-months-jan":"January","tr-months-feb":"February","tr-months-mar":"March","tr-months-apr":"April","tr-months-may":"May","tr-months-jun":"June","tr-months-jul":"July","tr-months-aug":"August","tr-months-sep":"September","tr-months-oct":"October","tr-months-nov":"November","tr-months-dec":"December","tr-nav-tooltip":"Slider navigation","woo-def-color":"as_black_style","woo-def-rows":"3","woo-hoverfx":"","woo-thumb-style":"style1","woo-footer-cart":"1","REDUX_last_saved":1403298104,"amy-menu-styles":0,"amy-menu-styles2":0,"redux-backup":"1"}'),


                        ),
						
                   	 ),
				
					
					
					
                )
            );
			
			
			
			
			
			
			
			
			
			
			
			
					
					
					
					
					
					
					
					

            $theme_info  = '<div class="redux-framework-section-desc">';
            $theme_info .= '<p class="redux-framework-theme-data description theme-uri">' . __('<strong>Theme URL:</strong> ', 'sopernal-theme') . '<a href="' . $this->theme->get('ThemeURI') . '" target="_blank">' . $this->theme->get('ThemeURI') . '</a></p>';
            $theme_info .= '<p class="redux-framework-theme-data description theme-author">' . __('<strong>Author:</strong> ', 'sopernal-theme') . $this->theme->get('Author') . '</p>';
            $theme_info .= '<p class="redux-framework-theme-data description theme-version">' . __('<strong>Version:</strong> ', 'sopernal-theme') . $this->theme->get('Version') . '</p>';
            $theme_info .= '<p class="redux-framework-theme-data description theme-description">' . $this->theme->get('Description') . '</p>';
            $tabs = $this->theme->get('Tags');
            if (!empty($tabs)) {
                $theme_info .= '<p class="redux-framework-theme-data description theme-tags">' . __('<strong>Tags:</strong> ', 'sopernal-theme') . implode(', ', $tabs) . '</p>';
            }
            $theme_info .= '</div>';

            if (file_exists(dirname(__FILE__) . '/../README.md')) {
                $this->sections['theme_docs'] = array(
                    'icon'      => 'el-icon-list-alt',
                    'title'     => __('Documentation', 'sopernal-theme'),
                    'fields'    => array(
                        array(
                            'id'        => '17',
                            'type'      => 'raw',
                            'markdown'  => true,
                            'content'   => file_get_contents(dirname(__FILE__) . '/../README.md')
                        ),
                    ),
                );
            }
            
           
            $this->sections[] = array(
                'title'     => __('Import / Export', 'sopernal-theme'),
                'desc'      => __('Import and Export your Redux Framework settings from file, text or URL.', 'sopernal-theme'),
                'icon'      => 'el-icon-refresh',
                'fields'    => array(
                    array(
                        'id'            => 'opt-import-export',
                        'type'          => 'import_export',
                        'title'         => 'Import Export',
                        'subtitle'      => 'Save and restore your Redux options',
                        'full_width'    => false,
                    ),
                ),
            );                     
                    
            $this->sections[] = array(
                'type' => 'divide',
            );

            $this->sections[] = array(
                'icon'      => 'el-icon-info-sign',
                'title'     => __('Theme Information', 'sopernal-theme'),
                'desc'      => __('<p class="description">This is the Description. Again HTML is allowed</p>', 'sopernal-theme'),
                'fields'    => array(
                    array(
                        'id'        => 'opt-raw-info',
                        'type'      => 'raw',
                        'content'   => $item_info,
                    )
                ),
            );

            if (file_exists(trailingslashit(dirname(__FILE__)) . 'README.html')) {
                $tabs['docs'] = array(
                    'icon'      => 'el-icon-book',
                    'title'     => __('Documentation', 'sopernal-theme'),
                    'content'   => nl2br(file_get_contents(trailingslashit(dirname(__FILE__)) . 'README.html'))
                );
            }
        }

        public function setHelpTabs() {

            // Custom page help tabs, displayed using the help API. Tabs are shown in order of definition.
            $this->args['help_tabs'][] = array(
                'id'        => 'redux-help-tab-1',
                'title'     => __('Theme Information 1', 'sopernal-theme'),
                'content'   => __('<p>This is the tab content, HTML is allowed.</p>', 'sopernal-theme')
            );

            $this->args['help_tabs'][] = array(
                'id'        => 'redux-help-tab-2',
                'title'     => __('Theme Information 2', 'sopernal-theme'),
                'content'   => __('<p>This is the tab content, HTML is allowed.</p>', 'sopernal-theme')
            );

            // Set the help sidebar
            $this->args['help_sidebar'] = __('<p>This is the sidebar content, HTML is allowed.</p>', 'sopernal-theme');
        }

        /**

          All the possible arguments for Redux.
          For full documentation on arguments, please refer to: https://github.com/ReduxFramework/ReduxFramework/wiki/Arguments

         * */
        public function setArguments() {

            $theme = wp_get_theme(); // For use with some settings. Not necessary.

            $this->args = array(
                // TYPICAL -> Change these values as you need/desire
                'opt_name'          => 'sopernal_settings',            // This is where your data is stored in the database and also becomes your global variable name.
                'display_name'      => $theme->get('Name').' - Settings Panel',     // Name that appears at the top of your panel
                'display_version'   => $theme->get('Version'),  // Version that appears at the top of your panel
                'menu_type'         => 'menu',                  //Specify if the admin menu should appear or not. Options: menu or submenu (Under appearance only)
                'allow_sub_menu'    => true,                    // Show the sections below the admin menu item or not
                'menu_title'        => __('Sopernal Settings', 'sopernal-theme'),
                'page_title'        => __('Sopernal Settings', 'sopernal-theme'),
                
                // You will need to generate a Google API key to use this feature.
                // Please visit: https://developers.google.com/fonts/docs/developer_api#Auth
                'google_api_key' => '-', // Must be defined to add google fonts to the typography module
                
                'async_typography'  => false,                    // Use a asynchronous font on the front end or font string
                'admin_bar'         => true,                    // Show the panel pages on the admin bar
                'global_variable'   => '',                      // Set a different name for your global variable other than the opt_name
                'dev_mode'          => false,                    // Show the time the page took to load, etc
                'customizer'        => true,                    // Enable basic customizer support
                
                // OPTIONAL -> Give you extra features
                'page_priority'     => null,                    // Order where the menu appears in the admin area. If there is any conflict, something will not show. Warning.
                'page_parent'       => 'themes.php',            // For a full list of options, visit: http://codex.wordpress.org/Function_Reference/add_submenu_page#Parameters
                'page_permissions'  => 'manage_options',        // Permissions needed to access the options panel.
                'menu_icon'         => '',                      // Specify a custom URL to an icon
                'last_tab'          => '',                      // Force your panel to always open to a specific tab (by id)
                'page_icon'         => 'icon-themes',           // Icon displayed in the admin panel next to your menu_title
                'page_slug'         => '_options',              // Page slug used to denote the panel
                'save_defaults'     => true,                    // On load save the defaults to DB before user clicks save or not
                'default_show'      => false,                   // If true, shows the default value next to each field that is not the default value.
                'default_mark'      => '',                      // What to print by the field's title if the value shown is default. Suggested: *
                'show_import_export' => true,                   // Shows the Import/Export panel when not used as a field.
                
                // CAREFUL -> These options are for advanced use only
                'transient_time'    => 60 * MINUTE_IN_SECONDS,
                'output'            => true,                    // Global shut-off for dynamic CSS output by the framework. Will also disable google fonts output
                'output_tag'        => true,                    // Allows dynamic CSS to be generated for customizer and google fonts, but stops the dynamic CSS from going to the head
                'footer_credit'     => 'Settings panel by <a href="http://themes.cray.bg">themes.cray.bg</a>',                   // Disable the footer credit of Redux. Please leave if you can help it.
                
                // FUTURE -> Not in use yet, but reserved or partially implemented. Use at your own risk.
                'database'              => '', // possible: options, theme_mods, theme_mods_expanded, transient. Not fully functional, warning!
                'system_info'           => false, // REMOVE

                // HINTS
                'hints' => array(
                    'icon'          => 'icon-question-sign',
                    'icon_position' => 'right',
                    'icon_color'    => 'lightgray',
                    'icon_size'     => 'normal',
                    'tip_style'     => array(
                        'color'         => 'light',
                        'shadow'        => true,
                        'rounded'       => false,
                        'style'         => '',
                    ),
                    'tip_position'  => array(
                        'my' => 'top left',
                        'at' => 'bottom right',
                    ),
                    'tip_effect'    => array(
                        'show'          => array(
                            'effect'        => 'fade',
                            'duration'      => '500',
                            'event'         => 'mouseover',
                        ),
                        'hide'      => array(
                            'effect'    => 'fade',
                            'duration'  => '500',
                            'event'     => 'click',
                        ),
                    ),
                )
            );


            // SOCIAL ICONS -> Setup custom links in the footer for quick links in your panel footer icons.
            $this->args['share_icons'][] = array(
                'url'   => 'https://github.com/ReduxFramework/ReduxFramework',
                'title' => 'Visit us on GitHub',
                'icon'  => 'el-icon-github'
                //'img'   => '', // You can use icon OR img. IMG needs to be a full URL.
            );
            $this->args['share_icons'][] = array(
                'url'   => 'https://www.facebook.com/flasherland.fanpage',
                'title' => 'Like us on Facebook',
                'icon'  => 'el-icon-facebook'
            );
            $this->args['share_icons'][] = array(
                'url'   => 'http://twitter.com/cray.bg',
                'title' => 'Follow us on Twitter',
                'icon'  => 'el-icon-twitter'
            );
            

            // Panel Intro text -> before the form
            /*if (!isset($this->args['global_variable']) || $this->args['global_variable'] !== false) {
                if (!empty($this->args['global_variable'])) {
                    $v = $this->args['global_variable'];
                } else {
                    $v = str_replace('-', '_', $this->args['opt_name']);
                }
                $this->args['intro_text'] = sprintf(__('<p>Did you know that Redux sets a global variable for you? To access any of your saved options from within your code you can use your global variable: <strong>$%1$s</strong></p>', 'sopernal-theme'), $v);
            } else {
                $this->args['intro_text'] = __('<p>This text is displayed above the options panel. It isn\'t required, but more info is always better! The intro_text field accepts all HTML.</p>', 'sopernal-theme');
            }*/

            // Add content after the form.
     //       $this->args['footer_text'] = __('<p>This text is displayed below the options panel. It isn\'t required, but more info is always better! The footer_text field accepts all HTML.</p>', 'sopernal-theme');
        }

    }
    
    global $reduxConfig;
    $reduxConfig = new Redux_Framework_sample_config();
}

/**
  Custom function for the callback referenced above
 */
if (!function_exists('redux_my_custom_field')):
    function redux_my_custom_field($field, $value) {
        print_r($field);
        echo '<br/>';
        print_r($value);
    }
endif;

/**
  Custom function for the callback validation referenced above
 * */
if (!function_exists('redux_validate_callback_function')):
    function redux_validate_callback_function($field, $value, $existing_value) {
        $error = false;
        $value = 'just function';
        $return['value'] = $value;
        if ($error == true) {
            $return['error'] = $field;
        }
        return $return;
    }
endif;