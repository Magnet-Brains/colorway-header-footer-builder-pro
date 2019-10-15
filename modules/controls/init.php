<?php 
namespace ColorwayHF\Modules\Controls;

defined( 'ABSPATH' ) || exit;

class Init{

    // instance of all control's base class
    // ##readhere
    public static function get_url(){
        return \ColorwayHF::module_url() . 'controls/';
    }
    public static function get_dir(){
        return \ColorwayHF::module_dir() . 'controls/';
    }

    public function __construct() {

        // Includes necessary files
        $this->include_files();

        // Initilizating control hooks
       add_action('elementor/controls/controls_registered', array( $this, 'ajax_select2' ), 11 );

    }

    private function include_files(){
        // Controls_Manager
        include_once self::get_dir() . 'control-manager.php';

        // ajax select2
        include_once self::get_dir() . 'ajax-select2.php';
        include_once self::get_dir() . 'ajax-select2-api.php';

    }

    public function ajax_select2( $controls_manager ) {
        $controls_manager->register_control('ajaxselect2', new \ColorwayHF\Modules\Controls\Ajax_Select2());
    }


}