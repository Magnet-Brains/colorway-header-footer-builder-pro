<?php

namespace ColorwayHF;

defined('ABSPATH') || exit;

/* ColorwayHF class - Initiate all necessary classes, hooks, configs. */

class Handler {
    /* The plugin instance. */

    public static $instance = null;

    /* Construct the plugin object. */

    public function __construct() {

        // Call the autoloader method.
        $this->registrar_autoloader();

        // Enqueue admin scripts.
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin']);

        // Enqueue inline scripts
        Core\Build_Inline_Scripts::instance();

        // Register default modules
        Core\Build_Modules::instance();
    }

    public function enqueue_frontend_inline() {
        $script_builder = new Core\Build_Inline_scripts();
        $script_builder->script_for_frontend();
    }

    /* Enqueue js and css to admin. */

    public function enqueue_admin() {
        $screen = get_current_screen();
        if (!in_array($screen->id, ['nav-menus', 'toplevel_page_colorwayhf', 'edit-colorwayhf_template', 'colorwayhf_page_colorwayhf-header-footer'])) {
            return;
        }

        wp_register_style('fontawesome', \ColorwayHF::module_url() . 'init/assets/css/font-awesome.min.css', \ColorwayHF::VERSION);
        wp_register_style('colorwayhf-font-css-admin', \ColorwayHF::module_url() . 'init/assets/css/cwicons.css', \ColorwayHF::VERSION);
        wp_register_style('colorwayhf-lib-css-admin', \ColorwayHF::lib_url() . 'framework/assets/css/framework.css', \ColorwayHF::VERSION);
        wp_register_style('colorwayhf-init-css-admin', \ColorwayHF::lib_url() . 'framework/assets/css/admin-style.css', \ColorwayHF::VERSION);
        wp_register_style('colorwayhf-init-css-admin-ems', \ColorwayHF::lib_url() . 'framework/assets/css/admin-style-ems-dev.css', \ColorwayHF::VERSION);

        wp_enqueue_style('fontawesome');
        wp_enqueue_style('colorwayhf-font-css-admin');
        wp_enqueue_style('colorwayhf-lib-css-admin');
        wp_enqueue_style('colorwayhf-init-css-admin');
        wp_enqueue_style('colorwayhf-init-css-admin-ems');

        wp_enqueue_script('bootstrap', \ColorwayHF::lib_url() . 'framework/assets/js/bootstrap.min.js', \ColorwayHF::VERSION, true);
        wp_enqueue_script('popper', \ColorwayHF::lib_url() . 'framework/assets/js/popper.min.js', \ColorwayHF::VERSION, true);
        wp_enqueue_script('colorwayhf-init-js-admin', \ColorwayHF::lib_url() . 'framework/assets/js/admin-script.js', \ColorwayHF::VERSION, true);
    }

    public function enqueue_admin_inline() {
        $script_builder = new Core\Build_Inline_scripts();
        $script_builder->script_for_admin();
    }

    /* Construct the plugin object. */

    private function registrar_version_manager() {
        // run the migration class if current version is greater than old installed version.
        if (Helper::current_version() > Helper::old_version()) {
            // load the update and related migration classes
        }
    }

    /* ColorwayHF autoloader loads all the classes needed to run the plugin. */

    private function registrar_autoloader() {
        require_once \ColorwayHF::plugin_dir() . '/autoloader.php';
        Autoloader::run();
    }

    /* return Handler An instance of the class. */
    public static function instance() {
        if (is_null(self::$instance)) {

            // Fire when ColorwayHF instance.
            self::$instance = new self();

            // Fire when ColorwayHF was fully loaded and instantiated.
            do_action('colorwayhf/loaded');
        }
        return self::$instance;
    }
}

// Run the instance.
Handler::instance();
