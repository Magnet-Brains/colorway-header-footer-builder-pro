<?php

namespace ColorwayHF\Libs\Framework;

use ColorwayHF\Libs\Framework\Classes\Utils;

defined('ABSPATH') || exit;

class Attr {
    /* The class instance.  */

    public static $instance = null;
    public $utils;

    public static function get_dir() {
        return \ColorwayHF::lib_dir() . 'framework/';
    }

    public static function get_url() {
        return \ColorwayHF::lib_url() . 'framework/';
    }

    public static function key() {
        return 'colorwayhf';
    }

    public function __construct() {
        $this->utils = Classes\Utils::instance();
        new Classes\Ajax;

        // register admin menus
        add_action('admin_menu', [$this, 'register_settings_menus']);
        add_action('admin_menu', [$this, 'register_support_menu'], 999);

        // register js/ css
        add_action('admin_enqueue_scripts', [$this, 'enqueue_scripts']);
    }

    public function include_files() {
        
    }

    public function enqueue_scripts() {
        wp_register_style('colorwayhf-admin-global', \ColorwayHF::lib_url() . 'framework/assets/css/admin-global.css', \ColorwayHF::VERSION);
        wp_enqueue_style('colorwayhf-admin-global');
    }

    public function register_settings_menus() {

        // dashboard, main menu
        add_menu_page(
                esc_html__('ColorwayHF Settings', 'colorway-hf'), esc_html__('ColorwayHF', 'colorway-hf'), 'manage_options', self::key(), [$this, 'register_settings_contents__settings'], self::get_url() . 'assets/images/logo-hf.png', 59
        );
    }

    public function register_support_menu() {
        add_submenu_page(self::key(), esc_html__('Settings', 'colorway-hf'), esc_html__('Settings', 'colorway-hf'), 'manage_options', self::key() . '-header-footer', [$this, 'register_settings_contents__colorway'], 10);
    }

    public function register_settings_contents__settings() {
        include self::get_dir() . 'pages/settings-init.php';
    }

    public function register_settings_contents__colorway() {
        include self::get_dir() . 'pages/settings-header-footer.php';
    }

    /* return Build_Widgets An instance of the class. */

    public static function instance() {
        if (is_null(self::$instance)) {
            // Fire the class instance
            self::$instance = new self();
        }
        return self::$instance;
    }

}
