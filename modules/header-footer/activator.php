<?php 
namespace ColorwayHF\Modules\Header_Footer;

defined( 'ABSPATH' ) || exit;

class Activator {
    public static $instance = null;

    protected $templates;
    public $header_template;
    public $footer_template;

    protected $current_theme;
    protected $current_template;

    protected $post_type = 'colorwayhf_template';

    public function __construct() {
        add_action( 'wp', array( $this, 'hooks' ) );
    }

    public function hooks(){
        $this->current_template = basename(get_page_template_slug());
        if($this->current_template == 'elementor_canvas'){
            return;
        }

        $this->current_theme = get_template();
        
            new Theme_Hooks\Theme_Support(self::template_ids());
    
    }

    public static function template_ids(){
        $cached = wp_cache_get( 'colorwayhf_template_ids' );
		if ( false !== $cached ) {
			return $cached;
        }
        
        $instance = self::instance();
        $instance->the_filter();

        $ids = [
            $instance->header_template,
            $instance->footer_template,
        ];

        if($instance->header_template != null){
			\ColorwayHF\Utils::render_elementor_content_css($instance->header_template);
		}

		if($instance->footer_template != null){
			\ColorwayHF\Utils::render_elementor_content_css($instance->footer_template);
		}

        wp_cache_set( 'colorwayhf_template_ids', $ids );
        return $ids;
    }


    protected function the_filter(){
        $arg = [
            'posts_per_page'   => -1,
            'orderby'          => 'id',
            'order'            => 'DESC',
            'post_status'      => 'publish',
            'post_type'        => $this->post_type,
            'meta_query' => [
                [
                    'key'     => 'colorwayhf_template_activation',
                    'value'   => 'yes',
                    'compare' => '=',
                ],
            ],
        ];
        $this->templates = get_posts($arg);

        // entire site
        if(!is_admin()){
            $filters = [[
                'key'     => 'condition_a',
                'value'   => 'entire_site',
            ]];
            $this->get_header_footer($filters);
        }

        // all archive
        if(is_archive()){
            $filters = [[
                'key'     => 'condition_a',
                'value'   => 'archive',
            ]];
            $this->get_header_footer($filters);
        }

        // all singular
        if(is_page() || is_single() || is_404()){
            $filters = [
                [
                    'key'     => 'condition_a',
                    'value'   => 'singular',
                ],
                [
                    'key'     => 'condition_singular',
                    'value'   => 'all',
                ]
            ];
            $this->get_header_footer($filters);
        }
        
        // all pages, all posts, 404 page
        if(is_page()){
            $filters = [
                [
                    'key'     => 'condition_a',
                    'value'   => 'singular',
                ],
                [
                    'key'     => 'condition_singular',
                    'value'   => 'all_pages',
                ]
            ];
            $this->get_header_footer($filters);
        }elseif(is_single()){
            $filters = [
                [
                    'key'     => 'condition_a',
                    'value'   => 'singular',
                ],
                [
                    'key'     => 'condition_singular',
                    'value'   => 'all_posts',
                ]
            ];
            $this->get_header_footer($filters);
        }elseif(is_404()){
            $filters = [
                [
                    'key'     => 'condition_a',
                    'value'   => 'singular',
                ],
                [
                    'key'     => 'condition_singular',
                    'value'   => '404page',
                ]
            ];
            $this->get_header_footer($filters);
        }

        // singular selective
        if(is_page() || is_single()){
            $filters = [
                [
                    'key'     => 'condition_a',
                    'value'   => 'singular',
                ],
                [
                    'key'     => 'condition_singular',
                    'value'   => 'selective',
                ],
                [
                    'key'     => 'condition_singular_id',
                    'value'   => get_the_ID(),
                ]
            ];
            $this->get_header_footer($filters);
        }

        // homepage
        if(is_home() || is_front_page()){
            $filters = [
                [
                    'key'     => 'condition_a',
                    'value'   => 'singular',
                ],
                [
                    'key'     => 'condition_singular',
                    'value'   => 'front_page',
                ]
            ];
            $this->get_header_footer($filters);
        }
    }

    protected function get_header_footer($filters){
        if($this->templates != null){
            foreach($this->templates as $template){
                $template = $this->get_full_data($template);
                $match_found = true;
                
                foreach($filters as $filter){
                    if($filter['key'] == 'condition_singular_id'){
                        $ids = explode(',', $template[$filter['key']]);
                        if(!in_array($filter['value'], $ids)){
                            $match_found = false;
                        }
                    }elseif($template[$filter['key']] != $filter['value']){
                        $match_found = false;
                    }
                    if( $filter['key'] == 'condition_a' && $template[$filter['key']] == 'singular' && count($filters) < 2){
                        $match_found = false;
                    }
                }

                if($match_found == true){
                    if($template['type'] == 'header'){
                        $this->header_template = $template['ID'];
                    }
                    if($template['type'] == 'footer'){
                        $this->footer_template = $template['ID'];
                    }
                }
            }
        }
    }

    protected function get_full_data($post){
        if($post != null){
            return array_merge((array)$post, [
                'type' => get_post_meta($post->ID, 'colorwayhf_template_type', true),
                'condition_a' => get_post_meta($post->ID, 'colorwayhf_template_condition_a', true),
                'condition_singular' => get_post_meta($post->ID, 'colorwayhf_template_condition_singular', true),
                'condition_singular_id' => get_post_meta($post->ID, 'colorwayhf_template_condition_singular_id', true),
            ]);
        }
    }

    public static function instance() {
        if ( is_null( self::$instance ) ) {
            self::$instance = new self();
        }

        return self::$instance;
    }
}