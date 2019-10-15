<?php 
namespace ColorwayHF\Libs\Framework\Classes;

use ColorwayHF\Libs\Framework\Classes\License;

defined( 'ABSPATH' ) || exit;

class Ajax{
    private $utils;

    public function __construct() {
        add_action( 'wp_ajax_cw_admin_action', [$this, 'colorwayhf_admin_action'] );
        add_action( 'wp_ajax_cw_admin_license', [$this, 'colorwayhf_admin_license'] );
        $this->utils = Utils::instance();
    }
    
    public function colorwayhf_admin_action() {        
        $this->utils->save_option('widget_list', $_POST['widget_list']);
        $this->utils->save_option('module_list', $_POST['module_list']);
        $this->utils->save_option('user_data', $_POST['user_data']);

        wp_die(); // this is required to terminate immediately and return a proper response
    }

    public function colorwayhf_admin_license() {      
        $key = !isset($_POST['elementkit_pro_license_key']) ? '' : sanitize_text_field($_POST['elementkit_pro_license_key']);
        $type = !isset($_POST['type']) ? '' : sanitize_text_field($_POST['type']);
        $result = [
            'status' => 'danger',
            'message' => esc_html__('Something went wrong', 'colorway-hf')
        ];

        switch($type){
            case 'activate':
                if($key == ''){
                    $result['message'] = esc_html__('Your key is empty', 'colorway-hf');
                    $this->return_json($result);
                }
        
                $o = License::instance()->activate( $key );
                if ( is_object($o) ) {
                    $result = $o;
                }
            
            break;
            case 'revoke':
                $o = License::instance()->revoke();
                if ( is_object($o) ) {
                    $result = $o;
                }
            break;
        }


        $this->return_json($result);
    }

    public function return_json($data){
        if(is_array($data) || is_object($data)){
            echo  json_encode($data);
        }else{
            echo $data;
        }

        wp_die();
    }

}