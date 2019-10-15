<?php 
namespace ColorwayHF\Modules\HeaderFooterBuilder;

defined( 'ABSPATH' ) || exit;

class Cpt{

    public function __construct() {
        $this->post_type(); 

        add_action('admin_menu', [$this, 'cpt_menu']);
        add_filter( 'single_template', [ $this, 'load_canvas_template' ] );
    }

    public function post_type() {
        
		$labels = array(
			'name'               => __( 'Templates', 'colorway-hf' ),
			'singular_name'      => __( 'Template', 'colorway-hf' ),
			'menu_name'          => __( 'My Templatesr', 'colorway-hf' ),
			'name_admin_bar'     => __( 'Templates', 'colorway-hf' ),
			'add_new'            => __( 'Add New', 'colorway-hf' ),
			'add_new_item'       => __( 'Add New Template', 'colorway-hf' ),
			'new_item'           => __( 'New Template', 'colorway-hf' ),
			'edit_item'          => __( 'Edit Template', 'colorway-hf' ),
			'view_item'          => __( 'View Template', 'colorway-hf' ),
			'all_items'          => __( 'All Templates', 'colorway-hf' ),
			'search_items'       => __( 'Search Templates', 'colorway-hf' ),
			'parent_item_colon'  => __( 'Parent Templates:', 'colorway-hf' ),
			'not_found'          => __( 'No Templates found.', 'colorway-hf' ),
			'not_found_in_trash' => __( 'No Templates found in Trash.', 'colorway-hf' ),
		);

		$args = array(
			'labels'              => $labels,
			'public'              => true,
			'rewrite'             => false,
			'show_ui'             => true,
			'show_in_menu'        => false,
			'show_in_nav_menus'   => false,
			'exclude_from_search' => true,
			'capability_type'     => 'page',
			'hierarchical'        => false,
			'supports'            => array( 'title', 'thumbnail', 'elementor' ),
		);

		register_post_type( 'colorwayhf_template', $args );
    }

    public function cpt_menu(){
        $link_our_new_cpt = 'edit.php?post_type=colorwayhf_template';
        add_submenu_page('colorwayhf', esc_html__('My Templates', 'colorway-hf'), esc_html__('My Templates', 'colorway-hf'), 'manage_options', $link_our_new_cpt);
    }

    function load_canvas_template( $single_template ) {

		global $post;

		if ( 'colorwayhf_template' == $post->post_type ) {

			$elementor_2_0_canvas = ELEMENTOR_PATH . '/modules/page-templates/templates/canvas.php';

			if ( file_exists( $elementor_2_0_canvas ) ) {
				return $elementor_2_0_canvas;
			} else {
				return ELEMENTOR_PATH . '/includes/page-templates/canvas.php';
			}
		}

		return $single_template;
	}
}

new Cpt();