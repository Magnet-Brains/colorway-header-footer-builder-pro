<?php

// This is the setup wizard init file.

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! function_exists( 'colorway_hf_theme_setup_wizard' ) ) :
	function Colorway_Hf_theme_setup_wizard() {

		if(class_exists('Colorway_Hf_Setup_Wizard')) {
			class cwhf_Colorway_Hf_Setup_Wizard extends Colorway_Hf_Setup_Wizard {

				/* Holds the current instance of the theme manager */
				private static $instance = null;

				/* return Colorway_Hf_Setup_Wizard */
				public static function get_instance() {
					if ( ! self::$instance ) {
						self::$instance = new self;
					}

					return self::$instance;
				}

				public function init_actions(){
					if ( apply_filters( $this->theme_name . '_enable_setup_wizard', true ) && current_user_can( 'manage_options' )  ) {
						add_filter( $this->theme_name . '_theme_setup_wizard_content', array(
							$this,
							'theme_setup_wizard_content'
						) );
						add_filter( $this->theme_name . '_theme_setup_wizard_steps', array(
							$this,
							'theme_setup_wizard_steps'
						) );
					}
					parent::init_actions();
				}

				public function theme_setup_wizard_steps($steps){
					return $steps;
				}
				public function theme_setup_wizard_content($content){
					if($this->is_possible_upgrade()){
						array_unshift_assoc($content,'upgrade',array(
							'title' => __( 'Upgrade', 'colorway-hf' ),
							'description' => __( 'Upgrade Content and Settings', 'colorway-hf' ),
							'pending' => __( 'Pending.', 'colorway-hf' ),
							'installing' => __( 'Installing Updates.', 'colorway-hf' ),
							'success' => __( 'Success.', 'colorway-hf' ),
							//'install_callback' => array( $this,'_content_install_updates' ),
							'checked' => 1
						));
					}
					return $content;
				}

				public function is_possible_upgrade(){
					$widget = get_option('widget_text');
					if(is_array($widget)) {
						foreach($widget as $item){
							if(isset($item['cwhf_widget_bg'])){
								return true;
							}
						}
					}
					// check if shop page is already installed?
					$shoppage = get_page_by_title( 'Shop' );
					if ( $shoppage || get_option( 'page_on_front', false ) ) {
						return true;
					}

					return false;
				}

			}

			cwhf_Colorway_Hf_Setup_Wizard::get_instance();
		}
	}
endif;