<?php 
namespace ColorwayHF\Modules\Controls;

defined( 'ABSPATH' ) || exit;

class Ajax_Select2 extends \Elementor\Base_Data_Control {

	public function get_api_url(){
		return get_rest_url() . 'colorwayhf/v1';
	}

	/* Get select2 control type. */
	public function get_type() {
		return 'ajaxselect2';
	}

	/*  Enqueue ontrol scripts and styles. */
	public function enqueue() {
		// script
		wp_register_script( 'colorwayhf-js-ajaxchoose-control',  Init::get_url() . 'assets/js/ajaxchoose.js' );
		wp_enqueue_script( 'colorwayhf-js-ajaxchoose-control' );
	}

	/* Get select2 control default settings. */
	protected function get_default_settings() {
		return [
			'options' => [],
			'multiple' => false,
			'select2options' => [],
		];
	}


	/* Render select2 control output in the editor. */
	public function content_template() {
		$control_uid = $this->get_control_uid();
		?>
		<div class="elementor-control-field">
			<label for="<?php echo esc_attr($control_uid); ?>" class="elementor-control-title">{{{ data.label }}}</label>
			<div class="elementor-control-input-wrapper">
				<# var multiple = ( data.multiple ) ? 'multiple' : ''; #>
				<select 
					id="<?php echo esc_attr($control_uid); ?>" 
					class="elementor-megamenuajaxselect2" 
					type="megamenuajaxselect2" {{ multiple }} 
					data-setting="{{ data.name }}"
					data-ajax-url="<?php echo esc_attr($this->get_api_url() . '/{{data.options}}/'); ?>"
				>
				</select>
			</div>
		</div>
		<# if ( data.description ) { #>
			<div class="elementor-control-field-description">{{{ data.description }}}</div>
		<# } #>
		<?php
	}
}
