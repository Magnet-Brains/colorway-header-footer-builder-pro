<?php

namespace ColorwayHF\Libs\Framework\Classes;

use ColorwayHF\Libs\Framework\Classes\License;

defined('ABSPATH') || exit;

class Ajax {

    private $utils;

    public function __construct() {
        add_action('wp_ajax_cw_admin_action', [$this, 'colorwayhf_admin_action']);
        $this->utils = Utils::instance();
    }

    public function colorwayhf_admin_action() {
        $this->utils->save_option('widget_list', $_POST['widget_list']);
        $this->utils->save_option('module_list', $_POST['module_list']);
        $this->utils->save_option('user_data', $_POST['user_data']);

        wp_die(); // this is required to terminate immediately and return a proper response
    }

    public function return_json($data) {
        if (is_array($data) || is_object($data)) {
            echo json_encode($data);
        } else {
            echo $data;
        }

        wp_die();
    }

}
