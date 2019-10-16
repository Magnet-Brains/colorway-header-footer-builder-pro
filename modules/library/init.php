<?php
namespace ColorwayHF\Modules\Library;

defined( 'ABSPATH' ) || exit;

class Init{
    private $dir;
    private $url;

    public function __construct(){

        // get current directory path.
        $this->dir = dirname(__FILE__) . '/';

        // get current module's url.
        $this->url = \ColorwayHF::plugin_url() . 'modules/library/';

        // enqueue editor js for elementor.
        add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'editor_scripts' ), 10);

        // print views and tab variables on footer.
        add_action( 'elementor/editor/footer', array( $this, 'print_views' ) );

        // enqueue editor css.
        add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'editor_styles' ) );

        // enqueue modal's preview css.
        add_action( 'elementor/preview/enqueue_styles', array( $this, 'preview_styles' ) );
    }

    public function editor_scripts(){
		wp_enqueue_script( 
			'colorwayhf-library-editor-script', 
			$this->url . 'assets/js/editor.js', 
			array('jquery', 'underscore', 'backbone-marionette'), 
			\ColorwayHF::VERSION,
			true
		);
	}

	public function editor_styles(){
		wp_enqueue_style( 'colorwayhf-library-editor-style', $this->url . 'assets/css/editor.css', array(), \ColorwayHF::VERSION);
	}

	public function preview_styles(){
		wp_enqueue_style( 'colorwayhf-library-preview-style', $this->url . 'assets/css/preview.css', array(), \ColorwayHF::VERSION );
	}

	public function print_views(){
		foreach ( glob( $this->dir . 'views/editor/*.php' ) as $file ) {
			$name = basename( $file, '.php' );
			ob_start();
			include $file;
			printf( '<script type="text/html" id="view-colorwayhf-%1$s">%2$s</script>', wp_kses_post($name), ob_get_clean() );
		}
	}
}