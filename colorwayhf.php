<?php
defined('ABSPATH') || exit;
/**
 * Plugin Name: Colorway Header Footer Builder Pro
 * Description:The advanced addons that allows you to create different layouts for header and footer with the help of Elementor.
 * Plugin URI: https://www.inkthemes.com/colorway/
 * Author: InkThemes
 * Version: 1.0.0
 * Author URI: https://inkthemes.com
 * Text Domain: colorway-hf
 * @package ColorwayHF
 * @category Pro
 * Colorway Header Footer Builder is a powerful addon for Elementor page builder.
 * 
 */
final class ColorwayHF {

    /**
     * Plugin Version
     *
     */
    const VERSION = '1.0.0';

    /**
     * Minimum Elementor Version
     *
     */
    const MINIMUM_ELEMENTOR_VERSION = '2.4.0';

    /**
     * Minimum PHP Version
     *
     */
    const MINIMUM_PHP_VERSION = '5.6';

    /**
     * Plugin file
     *
     */
    static function plugin_file() {
        return __FILE__;
    }

    /**
     * Plugin url
     *
     */
    static function plugin_url() {
        return trailingslashit(plugin_dir_url(__FILE__));
    }

    /**
     * Plugin directory.
     *
     */
    static function plugin_dir() {
        return trailingslashit(plugin_dir_path(__FILE__));
    }

    /**
     * Plugin's module directory.
     *
     */
    static function module_dir() {
        return self::plugin_dir() . 'modules/';
    }

    /**
     * Plugin's module url.
     *
     */
    static function module_url() {
        return self::plugin_url() . 'modules/';
    }

    /**
     * Plugin's lib directory.
     *
     */
    static function lib_dir() {
        return self::plugin_dir() . 'libs/';
    }

    /**
     * Plugin's lib url.
     *
     */
    static function lib_url() {
        return self::plugin_url() . 'libs/';
    }

    /**
     * Constructor
     *
     */
    public function __construct() {
        // Init Plugin
        add_action('plugins_loaded', array($this, 'init'), 100);
    }

    /**
     * Initialize the plugin
     *
     * Checks for basic plugin requirements, if one check fail don't continue,
     * if all check have passed include the plugin class.
     *
     * Fired by `plugins_loaded` action hook.
     *
     */
    public function init() {
        // Load the main static helper class.
        require_once self::plugin_dir() . 'helpers/utils.php';
        require_once self::plugin_dir() . 'helpers/notice.php';
        require_once self::plugin_dir() . 'helpers/colorwayhf_setup_wizard/colorwayhf_setup.php';
        // Check if Elementor installed and activated.
        if (!did_action('elementor/loaded')) {
            add_action('admin_notices', array($this, 'install_elementor_notice'));
            return;
        }
        // Check for required Elementor version.
        if (!version_compare(ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=')) {
            add_action('admin_notices', array($this, 'failed_elementor_version'));
            return;
        }
        // Check for required PHP version.
        if (version_compare(PHP_VERSION, self::MINIMUM_PHP_VERSION, '<')) {
            add_action('admin_notices', array($this, 'failed_php_version'));
            return;
        }
        // Once we get here, We have passed all validation checks so we can safely include our plugin.
        // Register ColorwayHF widget category
        add_action('elementor/init', [$this, 'elementor_widget_category']);

        if (get_option('colorway_hf_setup_complete') == '') {
            add_action('admin_notices', array($this, 'admin_notice_missing_main_plugin'));
        }
        add_action('elementor/init', function() {
            // Load the Handler class, it's the core class of ColorwayHF.
            require_once self::plugin_dir() . 'handler.php';
        });
    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have required Elementor.
     *
     */
    public function install_elementor_notice() {

        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

        if (file_exists(WP_PLUGIN_DIR . '/elementor/elementor.php')) {
            $btn['label'] = esc_html__('Activate Elementor Plugin', 'colorway-hf');
            $btn['url'] = wp_nonce_url('plugins.php?action=activate&plugin=elementor/elementor.php&plugin_status=all&paged=1', 'activate-plugin_elementor/elementor.php');
        } else {
            $btn['label'] = esc_html__('Install Elementor Plugin', 'colorway-hf');
            $btn['url'] = wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=elementor'), 'install-plugin_elementor');
        }

        \ColorwayHF\Notice::push(
                [
                    'id' => 'unsupported-elementor-version',
                    'type' => 'error',
                    'dismissible' => true,
                    'btn' => $btn,
                    'message' => sprintf(esc_html__('Oops! Colorway Header Footer Plugin requires Elementor plugin to be activated.', 'colorway-hf'), self::MINIMUM_ELEMENTOR_VERSION),
                ]
        );
    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have a minimum required PHP version.
     *
     */
    public function admin_notice_minimum_php_version() {
        \ColorwayHF\Notice::push(
                [
                    'id' => 'unsupported-php-version',
                    'type' => 'error',
                    'dismissible' => true,
                    'message' => sprintf(esc_html__('ColorwayHF requires PHP version %1$s+, which is currently NOT RUNNING on this server.', 'colorway-hf'), self::MINIMUM_PHP_VERSION),
                ]
        );
    }

    /**
     * Add category.
     *
     * Register custom widget category in Elementor's editor
     *
     */
    public function elementor_widget_category($widgets_manager) {
        \Elementor\Plugin::$instance->elements_manager->add_category(
                'colorwayhf', [
            'title' => esc_html__('ColorwayHF', 'colorway-hf'),
            'icon' => 'fa fa-plug',
                ], 1
        );
        \Elementor\Plugin::$instance->elements_manager->add_category(
                'colorwayhf_headerfooter', [
            'title' => esc_html__('ColorwayHF', 'colorway-hf'),
            'icon' => 'fa fa-plug',
                ], 1
        );
    }

    static function default_modules($package = null) {
        //$package = ($package != null) ? $package : self::PACKAGE_TYPE;
        $default_list = [
            'header-footer',
            'sticky-content',
        ];
        return $default_list;
        //return ($package == 'free') ? $default_list : $default_list;
    }

    public function admin_notice_missing_main_plugin() {
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

        $message = sprintf(
                /* translators: 1: Plugin name 2: Elementor */
                // __('Clicking the button below will install and configure the required default Header-Footer Templates.<br/> <a class="button button-primary" href="' . esc_url(admin_url('plugins.php?page=cwhf-setup')) . '">%3$s</a>', 'colorway-hf'), '<strong>' . esc_html__('Colorway Header-Footer Plugin', 'colorway-hf') . '</strong>', '<strong>' . esc_html__('Colorway Header-Footer Plugin', 'colorway-hf') . '</strong>', '<strong>' . esc_html__('Run Setup', 'colorway-hf') . '</strong>'

                __('
                <div class="notice-container">
                <div class="notice-image">
                    <img src="' . self::plugin_url() . 'helpers/colorwayhf_setup_wizard/images/cwhf-logo.png" class="custom-logo" alt="Colorway"></div> 
                        <div class="notice-content">
                            <h2 class="notice-heading">Thank you for installing Colorway Header-Footer Plugin!</h2>
                            <p>Clicking the button below will install and configure the required default Header-Footer Templates.</p>
                            <div class="colorway-review-notice-container">
                            <a href="' . esc_url(admin_url('plugins.php?page=cwhf-setup')) . '" class="button colorwayhf-templates">Run Setup</a><img src="' . self::plugin_url() . 'helpers/colorwayhf_setup_wizard/images/left-orange-arrow.gif"/></div></div>
                            <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                        </div>')
        );
        printf('<div class="notice notice-warning is-dismissible colorwayhf-setup-notice"><p>%1$s</p></div>', $message);
    }

}

new ColorwayHF();



register_activation_hook(__FILE__, function () {
    add_option('colorwayhf_do_activation_redirect', true);
});
add_action('admin_init', function () {
    if (get_option('colorwayhf_do_activation_redirect', false)) {
        delete_option('colorwayhf_do_activation_redirect');
        exit(wp_redirect("plugins.php?page=cwhf-setup"));
    }
});


/*
 * Git Hosted Plugin Upadte 
 */

require ( dirname(__FILE__) . '/colorway-hf-update-checker/plugin-update-checker.php' );
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/MagnetBrains/colorway-header-footer-builder-pro',
	__FILE__,
	'colorway-hf'
);

$myUpdateChecker->getVcsApi()->enableReleaseAssets();