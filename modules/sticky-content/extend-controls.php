<?php

namespace Elementor;

class ColorwayHF_Extend_Sticky{

    public function __construct() {
		add_action( 'elementor/element/section/section_advanced/after_section_end', [ $this, 'register_controls' ] );
		add_action( 'elementor/element/common/_section_style/after_section_end', [ $this, 'register_controls' ] );
	}

	public function register_controls( Controls_Stack $element ) {
		$element->start_controls_section(
			'wc_section_scroll_effect',
			[
				'label' => __( 'ColorwayHF Sticky Effect', 'colorway-hf' ),
				'tab' => Controls_Manager::TAB_ADVANCED,
			]
		);

		$element->add_control(
			'cw_sticky',
			[
				'label' => __( 'Sticky', 'colorway-hf' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'None', 'colorway-hf' ),
					'top' => __( 'Top', 'colorway-hf' ),
					'bottom' => __( 'Bottom', 'colorway-hf' ),
				],
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'cw_sticky_on',
			[
				'label' => __( 'Sticky On', 'colorway-hf' ),
				'type' => Controls_Manager::SELECT2,
				'multiple' => true,
				'label_block' => 'true',
				'default' => [ 'desktop', 'tablet', 'mobile' ],
				'options' => [
					'desktop' => __( 'Desktop', 'colorway-hf' ),
					'tablet' => __( 'Tablet', 'colorway-hf' ),
					'mobile' => __( 'Mobile', 'colorway-hf' ),
				],
				'condition' => [
					'cw_sticky!' => '',
				],
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'cw_sticky_offset',
			[
				'label' => __( 'Sticky Offset', 'colorway-hf' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'required' => true,
				'condition' => [
					'cw_sticky!' => '',
				],
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'cw_sticky_effect_offset',
			[
				'label' => __( 'Effect Offset', 'colorway-hf' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'required' => true,
				'condition' => [
					'cw_sticky!' => '',
				],
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);

		$element->end_controls_section();
	}
}