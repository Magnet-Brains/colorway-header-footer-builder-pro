<?php

defined('ABSPATH') || exit;

/**
 * Plugin Name: Colorway Header Footer Builder Pro
 * Description:The advanced addons that allows you to create different layouts for header and footer with the help of Elementor.
 * Plugin URI: https://www.inkthemes.com/colorway/
 * Author: InkThemes
 * Version: 1.0.2
 * Author URI: https://inkthemes.com
 * Text Domain: colorway-hf
 * @package ColorwayHF
 * @category Pro
 * Colorway Header Footer Builder is a powerful addon for Elementor page builder.
 * 
 */
final class ColorwayHF {

    const VERSION = '1.0.1';

    static function plugin_file() {
        return __FILE__;
    }

    static function plugin_url() {
        return trailingslashit(plugin_dir_url(__FILE__));
    }

    static function plugin_dir() {
        return trailingslashit(plugin_dir_path(__FILE__));
    }

    static function module_dir() {
        return self::plugin_dir() . 'modules/';
    }

    static function module_url() {
        return self::plugin_url() . 'modules/';
    }

    static function lib_dir() {
        return self::plugin_dir() . 'libs/';
    }

    static function lib_url() {
        return self::plugin_url() . 'libs/';
    }

    public function __construct() {
        // Init Plugin
        add_action('plugins_loaded', array($this, 'init'), 100);
    }

    /* Initialize the plugin */

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
        $default_list = [
            'header-footer',
            'sticky-content',
        ];
        return $default_list;
    }

    public function admin_notice_missing_main_plugin() {
        if (isset($_GET['activate'])) {
            unset($_GET['activate']);
        }

        $message = sprintf(
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
        printf('<div class="notice notice-warning is-dismissible colorwayhf-setup-notice"><p>%1$s</p></div>', wp_kses_post($message));
    }

}

new ColorwayHF();



register_activation_hook(__FILE__, function () {
    add_option('colorwayhf_do_activation_redirect', true);
});
add_action('admin_init', function () {
    if (get_option('colorwayhf_do_activation_redirect', false)) {
        delete_option('colorwayhf_do_activation_redirect');
        exit(esc_url(wp_redirect("plugins.php?page=cwhf-setup")));
    }
});


/*
 * Git Hosted Plugin Upadte 
 */

require ( dirname(__FILE__) . '/colorway-hf-update-checker/plugin-update-checker.php' );
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
                'https://github.com/MagnetBrains/colorway-header-footer-builder-pro', __FILE__, 'colorway-hf'
);

$myUpdateChecker->getVcsApi()->enableReleaseAssets();
