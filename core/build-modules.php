<?php

namespace ColorwayHF\Core;

use ColorwayHF\Libs\Framework\Attr;

defined('ABSPATH') || exit;

/* Call assosiated classes of every modules. */

class Build_Modules {

    private $module_dir;
    public static $instance = null;
    private $system_modules = [
        'dynamic-content',
        'library',
        'controls',
    ];
    private $core_modules;
    private $active_modules;

    public function __construct() {
        $this->core_modules = \ColorwayHF::default_modules();
        $this->active_modules = Attr::instance()->utils->get_option('module_list', $this->core_modules);
        $this->active_modules = array_merge($this->active_modules, $this->system_modules);

        foreach ($this->active_modules as $module) {

            // make the class name and call it.
            $class_name = '\ColorwayHF\Modules\\' . \ColorwayHF\Utils::make_classname($module) . '\Init';
            new $class_name();
        }
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
