<?php
/*  Colorway Header Footer Builder Setup Wizard Class */
if (!defined('ABSPATH')) {
    exit;
}

if (!class_exists('colorwayhf_admin_styles')) {

    add_action('admin_init', 'colorwayhf_admin_styles');

    function colorwayhf_admin_styles() {
        wp_enqueue_style('colorwayhf-admin-style', plugin_dir_url(__FILE__) . '/css/colorwayhf-admin.css', '', '1.0.0', 'all');
    }

    class Colorway_Hf_Setup_Wizard {

        protected $version = '1.0.0';
        protected $theme_name = '';
        protected $colorway_hf_username = '';
        protected $oauth_script = '';
        protected $step = '';
        protected $steps = array();
        protected $plugin_path = '';
        protected $plugin_url = '';
        protected $page_slug;
        protected $tgmpa_instance;
        protected $tgmpa_menu_slug = 'tgmpa-install-plugins';
        protected $tgmpa_url = 'themes.php?page=tgmpa-install-plugins';
        protected $page_parent;
        protected $page_url;
        public $site_styles = array();
        private static $instance = null;

        public static function get_instance() {
            if (!self::$instance) {
                self::$instance = new self;
            }
            return self::$instance;
        }

        public function __construct() {
            $this->init_globals();
            $this->init_actions();
            $this->plugin_url = plugin_dir_url(__FILE__);
        }

        public function init_globals() {
            $this->page_slug = 'cwhf-setup';
            $this->parent_slug = '';
            //If we have parent slug - set correct url
            if ($this->parent_slug !== '') {
                $this->page_url = 'admin.php?page=' . $this->page_slug;
            } else {
                $this->page_url = 'themes.php?page=' . $this->page_slug;
            }
            //set relative plugin path url
            $this->plugin_path = trailingslashit($this->cleanFilePath(dirname(__FILE__)));
            $relative_url = str_replace($this->cleanFilePath(get_template_directory()), '', $this->plugin_path);
            $this->plugin_url = trailingslashit(get_template_directory_uri() . $relative_url);
        }

        public function init_actions() {
            if (apply_filters($this->theme_name . '_enable_setup_wizard', true) && current_user_can('manage_options')) {
                add_action('admin_menu', array($this, 'admin_menus'));
                add_action('admin_init', array($this, 'init_wizard_steps'), 30);
                add_action('admin_init', array($this, 'setup_wizard'), 30);
                add_action('wp_ajax_setup_content', array($this, 'setup_content'));
                add_action('wp_ajax_colorway_theme_options', array($this, 'colorway_theme_options'));
            }

            add_action('upgrader_post_install', array($this, 'upgrader_post_install'), 10, 2);
        }

        /* After a theme update we clear the setup_complete option. This prompts the user to visit the update page again. */

        public function upgrader_post_install($return, $theme) {
            if (is_wp_error($return)) {
                return $return;
            }
            if ($theme != get_stylesheet()) {
                return $return;
            }
            update_option('colorway_hf_setup_complete', false);
            return $return;
        }

        /*  We determine if the user already has theme content installed. This can happen if swapping from a previous theme or updated the current theme. We change the UI a bit when updating / swapping to a new theme. */

        public function is_possible_upgrade() {
            return false;
        }

        /*  Add admin menus/screens. */

        public function admin_menus() {

            if ($this->is_submenu_page()) {
                //prevent Theme Check warning about "themes should use add_theme_page for adding admin pages"
                $add_subpage_function = 'add_submenu' . '_page';
                $add_subpage_function($this->parent_slug, esc_html__('ColorwayHf Setup', 'colorway-hf'), esc_html__('ColorwayHf Setup', 'colorway-hf'), 'manage_options', $this->page_slug, array(
                    $this,
                    'setup_wizard',
                ));
            } else {
                add_theme_page(esc_html__('ColorwayHf Setup', 'colorway-hf'), esc_html__('ColorwayHf Setup', 'colorway-hf'), 'manage_options', $this->page_slug, array(
                    $this,
                    'setup_wizard',
                ));
            }
        }

        /*  Setup steps. */

        public function init_wizard_steps() {

            $this->steps = array(
                'introduction' => array(
                    'name' => esc_html__('Introduction', 'colorway-hf'),
                    'view' => array($this, 'colorway_hf_setup_introduction'),
                    'handler' => array($this, ''),
                ),
            );
            $this->steps['default_content'] = array(
                'name' => esc_html__('Demo Templates', 'colorway-hf'),
                'view' => array($this, 'colorway_hf_setup_default_content'),
                'handler' => '',
            );
            $this->steps['next_steps'] = array(
                'name' => esc_html__('Ready!', 'colorway-hf'),
                'view' => array($this, 'colorway_hf_setup_ready'),
                'handler' => '',
            );

            $this->steps = apply_filters($this->theme_name . '_theme_setup_wizard_steps', $this->steps);
        }

        /*  Show the setup wizard */

        public function setup_wizard() {
            if (empty($_GET['page']) || $this->page_slug !== $_GET['page']) {
                return;
            }

            $this->step = isset($_GET['step']) ? sanitize_key($_GET['step']) : current(array_keys($this->steps));

            wp_register_script('jquery-blockui', $this->plugin_url . 'js/jquery.blockUI.js', array('jquery'), '2.70', true);
            wp_register_script('colorway-hf-setup', $this->plugin_url . 'js/colorwayhf-setup.js', array(
                'jquery',
                'jquery-blockui',
                    ), $this->version);
            wp_localize_script('colorway-hf-setup', 'colorway_hf_setup_params', array(
                'tgm_plugin_nonce' => array(
                    'update' => wp_create_nonce('tgmpa-update'),
                    'install' => wp_create_nonce('tgmpa-install'),
                ),
                'tgm_bulk_url' => admin_url($this->tgmpa_url),
                'ajaxurl' => admin_url('admin-ajax.php'),
                'wpnonce' => wp_create_nonce('colorway_hf_setup_nonce'),
                'verify_text' => esc_html__('...verifying', 'colorway-hf'),
            ));

            wp_enqueue_style('colorway-hf-setup', $this->plugin_url . 'css/colorwayhf-setup.css', array(
                'wp-admin',
                'dashicons',
                'install',
                    ), $this->version);

            //enqueue style for admin notices
            wp_enqueue_style('wp-admin');

            wp_enqueue_media();
            wp_enqueue_script('media');

            ob_start();
            $this->setup_wizard_header();
            $this->setup_wizard_steps();
            $show_content = true;
            echo '<div class="colorway-hf-setup-content">';
            if (!empty($_REQUEST['save_step']) && isset($this->steps[$this->step]['handler'])) {
                $show_content = call_user_func($this->steps[$this->step]['handler']);
            }
            if ($show_content) {
                $this->setup_wizard_content();
            }
            echo '</div>';
            $this->setup_wizard_footer();
            exit;
        }

        public function get_step_link($step) {
            return add_query_arg('step', $step, admin_url('admin.php?page=' . $this->page_slug));
        }

        public function get_next_step_link() {
            $keys = array_keys($this->steps);

            return add_query_arg('step', $keys[array_search($this->step, array_keys($this->steps)) + 1], remove_query_arg('translation_updated'));
        }

        /*  Setup Wizard Header */

        public function setup_wizard_header() {
            ?>
            <!DOCTYPE html>
            <html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes(); ?>>
                <head>
                    <meta name="viewport" content="width=device-width"/>
                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
                    <?php
                    // avoid theme check issues.
                    echo '<title>' . esc_html__('Colorway &rsaquo; ColorwayHf Setup Wizard', 'colorway-hf') . '</title>';
                    ?>
                    <?php wp_print_scripts('colorway-hf-setup'); ?>
                    <?php do_action('admin_print_styles'); ?>
                    <?php do_action('admin_print_scripts'); ?>
                    <?php do_action('admin_head'); ?>
                </head>
                <body class="colorway-hf-setup wp-core-ui">
                    <h1 id="wc-logo">
                        <a href="https://www.inkthemes.com/" target="_blank">				
                            <img src="<?php echo esc_url($this->plugin_url . '/images/cwhf-logo.png') ?>" alt="Colorwayhf logo" />
                        </a>
                    </h1>
                    <?php
                }

                /* Setup Wizard Footer */

                public function setup_wizard_footer() {
                    ?>
                    <?php if ('next_steps' === $this->step) : ?>
                        <a class="wc-return-to-dashboard"
                           href="<?php echo esc_url(admin_url()); ?>"><?php esc_html_e('Return to the WordPress Dashboard', 'colorway-hf'); ?></a>
                       <?php endif; ?>
                </body>
                <?php
                @do_action('admin_footer'); // this was spitting out some errors in some admin templates. quick @ fix until I have time to find out what's causing errors.
                do_action('admin_print_footer_scripts');
                ?>
            </html>
            <?php
        }

        /* Output the steps */

        public function setup_wizard_steps() {
            $ouput_steps = $this->steps;
            array_shift($ouput_steps);
            ?>
            <ol class="colorway-hf-setup-steps">
                <?php foreach ($ouput_steps as $step_key => $step) : ?>
                    <li class="<?php
                    $show_link = false;
                    if ($step_key === $this->step) {
                        echo 'active';
                    } elseif (array_search($this->step, array_keys($this->steps)) > array_search($step_key, array_keys($this->steps))) {
                        echo 'done';
                        $show_link = true;
                    }
                    ?>"><?php
                            if ($show_link) {
                                ?>
                            <a href="<?php echo esc_url($this->get_step_link($step_key)); ?>"><?php echo esc_html($step['name']); ?></a>
                            <?php
                        } else {
                            echo esc_html($step['name']);
                        }
                        ?></li>
                <?php endforeach; ?>
            </ol>
            <?php
        }

        /* Output the content for the current step */

        public function setup_wizard_content() {
            isset($this->steps[$this->step]) ? call_user_func($this->steps[$this->step]['view']) : false;
        }

        /*  Introduction step */

        public function colorway_hf_setup_introduction() {

            if ($this->is_possible_upgrade()) {
                ?>
                <h1><?php printf(esc_html__('Welcome to the Easy Setup Assistant! for %s.', 'colorway-hf')); ?></h1>
                <p><?php esc_html_e('It looks like you may have recently upgraded to this theme. Great! This setup wizard will help ensure all the default settings are correct. It will also show some information about your new website and support options.', 'colorway-hf'); ?></p>
                <div class="colorway-hf-setup-actions step">
                    <div class="buttons-ink">
                        <a href="<?php echo esc_url($this->get_next_step_link()); ?>"
                           class="button-primary button button-large button-next"><?php esc_html_e('Let\'s Go!', 'colorway-hf'); ?></a>
                        <a href="<?php echo esc_url(wp_get_referer() && !strpos(wp_get_referer(), 'update.php') ? wp_get_referer() : admin_url('') ); ?>"
                           class="button button-large"><?php esc_html_e('Not right now', 'colorway-hf'); ?></a>
                    </div>
                </div>
                <?php
            } else if (get_option('colorway_hf_setup_complete', false)) {
                ?>
                <h1><?php printf(esc_html__('Welcome To The Quick Setup Wizard', 'colorway-hf')); ?></h1>
                <p><?php esc_html_e('It looks like you already ran the quick setup wizard. ', 'colorway-hf'); ?></p>
                <ul class="reimport-btn">
                    <li>
                        <a href="<?php echo esc_url($this->get_next_step_link()); ?>"
                           class="button-pri button button-next button-large"><?php esc_html_e('Run ColorwayHf Setup Wizard Again', 'colorway-hf'); ?></a>
                    </li>

                </ul>
                <div class="colorway-hf-setup-actions step">
                    <div class="buttons-ink">
                        <a href="<?php echo esc_url(wp_get_referer() && !strpos(wp_get_referer(), 'update.php') ? wp_get_referer() : admin_url('') ); ?>"
                           class="button button-large"><?php esc_html_e('Cancel', 'colorway-hf'); ?></a>
                    </div>
                </div>
                <?php
            } else {
                ?>
                <h1><?php printf(esc_html__('Welcome To The Quick Setup Wizard', 'colorway-hf')); ?></h1>
                <p><?php printf(esc_html__('Thank you for choosing Colorway Header-Footer Addon. The quick setup wizard will help you configure the addon and install all the required default header-footer templates.', 'colorway-hf')); ?><br/><strong>It should only take a few seconds!</strong></p>
                <p><?php esc_html_e('Or, skip and return to the WordPress Dashboard. Come back anytime to start creating headers & footers.', 'colorway-hf'); ?></p>
                <div class="colorway-hf-setup-actions step">
                    <div class="buttons-ink">
                        <a href="<?php echo esc_url(wp_get_referer() && !strpos(wp_get_referer(), 'update.php') ? wp_get_referer() : admin_url('') ); ?>"
                           class="button button-large"><?php esc_html_e('Skip & Return', 'colorway-hf'); ?></a>
                        <a href="<?php echo esc_url($this->get_next_step_link()); ?>"
                           class="button-pri button button-large button-next"><?php esc_html_e('Start Now', 'colorway-hf'); ?></a>
                    </div>
                </div>
                <?php
            }
        }

        public function filter_options($options) {
            return $options;
        }

        public function colorway_hf_setup_default_content() {
            ?>
            <h1><?php esc_html_e("Let’s Import Demo Templates", "colorway-hf"); ?></h1>
            <form method="post">
                <?php if ($this->is_possible_upgrade()) { ?>
                    <p><?php esc_html_e('It looks like you already have content installed on this website. If you would like to install the default demo content as well you can select it below. Otherwise just choose the upgrade option to ensure everything is up to date.', 'colorway-hf'); ?></p>
                <?php } else { ?>
                    <p><?php printf(esc_html__("The next step is to import demo header & footer templates for your site.", 'colorway-hf')); ?></p>
                    <p><?php printf(esc_html__('Click the “Import Templates” button. Once the templates are imported, you can manage/edit them through the WordPress admin dashboard.', 'colorway-hf')); ?></p>
                    <p><?php printf(esc_html__("Or, you can skip this step to create headers & footers from scratch. New templates will be created under the “My Templates” section of the Colorway Header-Footer addons.", 'colorway-hf')); ?></p>
                <?php } ?>

                <div class="content-importer-response">
                    <div id="importer-response" class="clear pos-relative">
                        <span class="res-text"></span>
                        <img class="loadinerSearch" width="30px" src="<?php echo esc_url($this->plugin_url . '/images/ajax-load.gif') ?>">
                            <img class="checkImg" width="30px" src="<?php echo esc_url($this->plugin_url . '/images/check-img.png') ?>">
                                </div>
                                <div id="importer-response-menu" class="clear pos-relative">
                                    <span class="res-text"></span>
                                    <img class="loadinerSearch" width="30px" src="<?php echo esc_url($this->plugin_url . '/images/ajax-load.gif') ?>">
                                        <img class="checkImg" width="30px" src="<?php echo esc_url($this->plugin_url . '/images/check-img.png') ?>">
                                            </div>
                                            <div id="importer-response-homepage" class="clear pos-relative">
                                                <span class="res-text"></span>
                                                <img class="loadinerSearch" width="30px" src="<?php echo esc_url($this->plugin_url . '/images/ajax-load.gif') ?>">
                                                    <img class="checkImg" width="30px" src="<?php echo esc_url($this->plugin_url . '/images/check-img.png') ?>">
                                                        </div>
                                                        <div id="importer-response-themeoptions" class="clear pos-relative">
                                                            <span class="res-text"></span>
                                                            <img class="loadinerSearch" width="30px" src="<?php echo esc_url($this->plugin_url . '/images/ajax-load.gif') ?>">
                                                                <img class="checkImg" width="30px" src="<?php echo esc_url($this->plugin_url . '/images/check-img.png') ?>">
                                                                    </div>
                                                                    <div class="clear"></div>
                                                                    </div>
                                                                    <div class="colorway-hf-setup-actions step">
                                                                        <div class="buttons-ink">
                                                                            <a href="<?php echo esc_url($this->get_next_step_link()); ?>"
                                                                               class="button button-large button-next button-next-skip"><?php esc_html_e('Skip This Step', 'colorway-hf'); ?></a>
                                                                            <a href="#" class="colorway-import-content button button-large"><?php esc_html_e('Import Templates', 'colorway-hf'); ?></a>
                                                                            <?php wp_nonce_field('colorway-hf-setup'); ?>
                                                                        </div>
                                                                    </div>
                                                                    </form>
                                                                    <?php
                                                                }

                                                                public function setup_content() {
                                                                    // Here we will right our code to import xml file in wordpress.
                                                                    $file = plugin_dir_path(__FILE__) . 'content/content.xml';
                                                                    if (!defined('WP_LOAD_IMPORTERS'))
                                                                        define('WP_LOAD_IMPORTERS', true);

                                                                    require_once ABSPATH . 'wp-admin/includes/import.php';

                                                                    $importer_error = false;
                                                                    $class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
                                                                    if (file_exists($class_wp_importer)) {
                                                                        require_once($class_wp_importer);
                                                                    } else {
                                                                        $importer_error = true;
                                                                    }

                                                                    if (!class_exists('WP_Import')) {
                                                                        $class_wp_import = plugin_dir_path(__FILE__) . 'importer/importer/cw-wordpress-importer.php';
                                                                        if (file_exists($class_wp_importer)) {
                                                                            // require_once($class_wp_import);
                                                                            require_once( $class_wp_import);
                                                                        } else {
                                                                            $importer_error = true;
                                                                        }
                                                                    }
                                                                    if ($importer_error) {
                                                                        ob_start();
                                                                        $msg = "Error on import";
//                                                                        $msg = ob_get_contents();
                                                                        ob_end_clean();

                                                                        die(wp_kses_post($msg));
                                                                    } else {

                                                                        if (!is_file($file)) {

                                                                            ob_start();
                                                                            $msg = "Something went wrong";
                                                                            $msg = ob_get_contents();
                                                                            ob_end_clean();
                                                                            die(wp_kses_post($msg));
                                                                        } else {

                                                                            $wp_import = new WP_Import();
                                                                            $wp_import->fetch_attachments = true;
                                                                            ob_start();
                                                                            $res = $wp_import->import($file);
                                                                            $res = ob_get_contents();
                                                                            ob_end_clean();
                                                                            $msg = 'Content imported success';
                                                                            die(wp_kses_post($msg));
                                                                        }
                                                                    }
                                                                }

                                                                public function colorway_theme_options() {
                                                                    $file = plugin_dir_path(__FILE__) . 'content/themeOptions1.json';
                                                                    $data = file_get_contents($file);
                                                                    $data = json_decode($data, true);

                                                                    if (is_array($data) && !empty($data)) {
                                                                        $menuname = 'Main Menu';
                                                                        $menulocation = 'custom_menu';
                                                                        // Does the menu exist already?
                                                                        $menu_exists = wp_get_nav_menu_object($menuname);
                                                                        if (!$menu_exists) {
                                                                            $menu_id = wp_create_nav_menu($menuname);
                                                                            foreach ($data as $key => $val) {
                                                                                wp_update_nav_menu_item($menu_id, 0, array(
                                                                                    'menu-item-title' => $val['name'],
                                                                                    'menu-item-classes' => $key,
                                                                                    'menu-item-url' => $val['url'],
                                                                                    'menu-item-status' => 'publish'));
                                                                            }
                                                                            if (!has_nav_menu($menulocation)) {
                                                                                $locations = get_theme_mod('nav_menu_locations');
                                                                                $locations[$menulocation] = $menu_id;
                                                                                set_theme_mod('nav_menu_locations', $locations);
                                                                                die('Templates imported');
                                                                            }
                                                                        }
                                                                    } else {
                                                                        die('Error in theme option');
                                                                    }
                                                                    die();
                                                                }

                                                                public $logs = array();

                                                                public function log($message) {
                                                                    $this->logs[] = $message;
                                                                }

                                                                public $errors = array();

                                                                public function error($message) {
                                                                    $this->logs[] = 'ERROR!!!! ' . $message;
                                                                }

                                                                public function colorway_hf_setup_ready() {

                                                                    update_option('colorway_hf_setup_complete', time());
                                                                    update_option('cwhf_update_notice', strtotime('-4 days'));
                                                                    $welcomePargeURL = admin_url() . 'edit.php?post_type=colorwayhf_template';
                                                                    ?>

                                                                    <h1><?php esc_html_e('Your Website Is Ready With New Header & Footer!', 'colorway-hf'); ?></h1>

                                                                    <p class="lp-redirect-page-pragrahi"><?php esc_html_e('Go to my templates for viewing variety of predefined header footer templates or visit site
…', 'colorway-hf'); ?>
                                                                    </p>
                                                                    <div class="colorway-hf-setup-actions step">
                                                                        <div class="buttons-ink">
                                                                            <a href="<?php echo esc_url(home_url()); ?>" class="button button-next button-large">Visit Site</a> 
                                                                            <a href="<?php echo esc_url($welcomePargeURL); ?>" class="colorway-temp button button-large">Go To My Templates!</a>
                                                                        </div>
                                                                    </div>
                                                                    <?php
                                                                }

                                                                private static $_current_manage_token = false;

                                                                public function ajax_notice_handler() {
                                                                    check_ajax_referer('cwhfwp-ajax-nonce', 'security');
                                                                    // Store it in the options table
                                                                    update_option('cwhf_update_notice', time());
                                                                }

                                                                private function _array_merge_recursive_distinct($array1, $array2) {
                                                                    $merged = $array1;
                                                                    foreach ($array2 as $key => &$value) {
                                                                        if (is_array($value) && isset($merged [$key]) && is_array($merged [$key])) {
                                                                            $merged [$key] = $this->_array_merge_recursive_distinct($merged [$key], $value);
                                                                        } else {
                                                                            $merged [$key] = $value;
                                                                        }
                                                                    }
                                                                    return $merged;
                                                                }

                                                                public static function cleanFilePath($path) {
                                                                    $path = str_replace('', '', str_replace(array('\\', '\\\\', '//'), '/', $path));
                                                                    if ($path[strlen($path) - 1] === '/') {
                                                                        $path = rtrim($path, '/');
                                                                    }

                                                                    return $path;
                                                                }

                                                                public function is_submenu_page() {
                                                                    return ( $this->parent_slug == '' ) ? false : true;
                                                                }

                                                            }

                                                        }

                                                        /*  ability extend class functionality */
                                                        add_action('after_setup_theme', 'colorway_hf_theme_setup_wizard', 10);
                                                        if (!function_exists('colorway_hf_theme_setup_wizard')) :

                                                            function colorway_hf_theme_setup_wizard() {
                                                                Colorway_Hf_Setup_Wizard::get_instance();
                                                            }







endif;
