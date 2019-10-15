<?php 
namespace ColorwayHF\Modules\Dynamic_Content;

defined( 'ABSPATH' ) || exit;

class Cpt{

    public function __construct() {
        $this->post_type();
        register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
        register_activation_hook( __FILE__, [$this, 'flush_rewrites'] );   
    }

    public function post_type() {
        
        $labels = array(
            'name'                  => _x( 'Colorway Header Footer items', 'Post Type General Name', 'colorway-hf' ),
            'singular_name'         => _x( 'Colorway Header Footer item', 'Post Type Singular Name', 'colorway-hf' ),
            'menu_name'             => esc_html__( 'Colorway Header Footer item', 'colorway-hf' ),
            'name_admin_bar'        => esc_html__( 'Colorway Header Footer item', 'colorway-hf' ),
            'archives'              => esc_html__( 'Item Archives', 'colorway-hf' ),
            'attributes'            => esc_html__( 'Item Attributes', 'colorway-hf' ),
            'parent_item_colon'     => esc_html__( 'Parent Item:', 'colorway-hf' ),
            'all_items'             => esc_html__( 'All Items', 'colorway-hf' ),
            'add_new_item'          => esc_html__( 'Add New Item', 'colorway-hf' ),
            'add_new'               => esc_html__( 'Add New', 'colorway-hf' ),
            'new_item'              => esc_html__( 'New Item', 'colorway-hf' ),
            'edit_item'             => esc_html__( 'Edit Item', 'colorway-hf' ),
            'update_item'           => esc_html__( 'Update Item', 'colorway-hf' ),
            'view_item'             => esc_html__( 'View Item', 'colorway-hf' ),
            'view_items'            => esc_html__( 'View Items', 'colorway-hf' ),
            'search_items'          => esc_html__( 'Search Item', 'colorway-hf' ),
            'not_found'             => esc_html__( 'Not found', 'colorway-hf' ),
            'not_found_in_trash'    => esc_html__( 'Not found in Trash', 'colorway-hf' ),
            'featured_image'        => esc_html__( 'Featured Image', 'colorway-hf' ),
            'set_featured_image'    => esc_html__( 'Set featured image', 'colorway-hf' ),
            'remove_featured_image' => esc_html__( 'Remove featured image', 'colorway-hf' ),
            'use_featured_image'    => esc_html__( 'Use as featured image', 'colorway-hf' ),
            'insert_into_item'      => esc_html__( 'Insert into item', 'colorway-hf' ),
            'uploaded_to_this_item' => esc_html__( 'Uploaded to this item', 'colorway-hf' ),
            'items_list'            => esc_html__( 'Items list', 'colorway-hf' ),
            'items_list_navigation' => esc_html__( 'Items list navigation', 'colorway-hf' ),
            'filter_items_list'     => esc_html__( 'Filter items list', 'colorway-hf' ),
        );
        $rewrite = array(
            'slug'                  => 'colorwayhf-content',
            'with_front'            => true,
            'pages'                 => false,
            'feeds'                 => false,
        );
        $args = array(
            'label'                 => esc_html__( 'Colorway Header Footer item', 'colorway-hf' ),
            'description'           => esc_html__( 'colorwayhf_content', 'colorway-hf' ),
            'labels'                => $labels,
            'supports'              => array( 'title', 'editor', 'elementor', 'permalink' ),
            'hierarchical'          => true,
            'public'                => true,
            'show_ui'               => false,
            'show_in_menu'          => false,
            'menu_position'         => 5,
            'show_in_admin_bar'     => false,
            'show_in_nav_menus'     => false,
            'can_export'            => true,
            'has_archive'           => false,
            'publicly_queryable' => true,
            'rewrite'               => $rewrite,
            'query_var' => true,
            'exclude_from_search'   => true,
            'publicly_queryable'    => true,
            'capability_type'       => 'page',
            'show_in_rest'          => true,
            'rest_base'             => 'colorwayhf-content',
        );
        register_post_type( 'colorwayhf_content', $args );
    }

    public function flush_rewrites() {
        $this->post_type();
        flush_rewrite_rules();
    }
}

new Cpt();