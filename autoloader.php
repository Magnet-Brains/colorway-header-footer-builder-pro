<?php

namespace ColorwayHF;

defined('ABSPATH') || exit;

/* ColorwayHF autoloader class. */

class Autoloader {

    public static function run() {
        spl_autoload_register([__CLASS__, 'autoload']);
    }

    /* For a given class, check if it exist and load it. */

    private static function autoload($class_name) {

        // If the class being requested does not start with our prefix
        if (0 !== strpos($class_name, __NAMESPACE__)) {
            return;
        }

        $file_name = strtolower(
                preg_replace(
                        ['/\b' . __NAMESPACE__ . '\\\/', '/([a-z])([A-Z])/', '/_/', '/\\\/'], ['', '$1-$2', '-', DIRECTORY_SEPARATOR], $class_name
                )
        );

        // Compile our path from the corosponding location.
        $file = \ColorwayHF::plugin_dir() . $file_name . '.php';

        if (file_exists($file)) {
            require_once $file;
        }
    }

}
