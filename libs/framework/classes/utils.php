<?php 
namespace ColorwayHF\Libs\Framework\Classes;

defined( 'ABSPATH' ) || exit;

class Utils{

    public static $instance = null;
    private static $key = 'colorwayhf_options';

    public static function get_dir(){
        return \ColorwayHF::lib_dir() . 'framework/';
    }

    public static function get_url(){
        return \ColorwayHF::lib_url() . 'framework/';
    }

    public function get_option($key, $default = ''){
        $data_all = get_option(self::$key);
        return (isset($data_all[$key]) && $data_all[$key] != '') ? $data_all[$key] : $default;
    }

    public function save_option($key, $value = ''){
        $data_all = get_option(self::$key);
        $data_all[$key] = $value;
        update_option('colorwayhf_options', $data_all);
    }

    public function input($input_options){
        $defaults = [
            'type' => null,
            'name' => '',
            'value' => '',
            'class' => '',
            'label' => '',
            'info' => '',
            'options' => [],
        ];
        $input_options = array_merge($defaults, $input_options);
        //d($input_options);
        if(file_exists(self::get_dir() . 'controls/settings/' . $input_options['type'] . '.php')){
            extract($input_options);
            include self::get_dir() . 'controls/settings/' . $input_options['type'] . '.php';
        }
    }

    public static function strify($str){
        return strtolower(preg_replace("/[^A-Za-z0-9]/", "__", $str));
    }

    public static function instance() {
        if ( is_null( self::$instance ) ) {

            // Fire the class instance
            self::$instance = new self();
        }

        return self::$instance;
    }
}