<?php
namespace ColorwayHF\Core;

defined( 'ABSPATH' ) || exit;

/**
 * Inline script registrar.
 * 
 * Returns all necessary inline js & css.
 * 
 * @since 1.0.0
 * @access public
 */
class Build_Inline_Scripts{

    /**
	 * The class instance.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @var Build_Modules
	 */
    public static $instance = null;

    function __construct(){
        add_action('wp_enqueue_scripts', [$this, 'frontend_js']);
        add_action('admin_print_scripts', [$this, 'admin_js']);
    }


    // scripts for common end, admin & frontend
    public function common_js(){
		ob_start(); ?>

<!--        console.log(window.colorwayhf);-->

		var colorwayhf = {
            resturl: '<?php echo get_rest_url() . 'colorwayhf/v1/'; ?>',
        }

<!--        console.log(window.colorwayhf);-->
		<?php
        $output = ob_get_contents();
        ob_end_clean();
        return $output;
    }



    // scripts for frontend
    public function frontend_js(){
        $js = $this->common_js();
        wp_add_inline_script('colorwayhf-framework-js-frontend', $js);
    }


    // scripts for admin
    public function admin_js(){
        echo "<script type='text/javascript'>\n";
        echo \ColorwayHF\Utils::render($this->common_js());
        echo "\n</script>";
    }



    /**
     * Instance.
     * 
     * Ensures only one instance of the class is loaded or can be loaded.
     *
     * @since 1.0.0
     * @access public
     * @static
     *
     * @return Build_Widgets An instance of the class.
     */
    public static function instance() {
        if ( is_null( self::$instance ) ) {

            // Fire the class instance
            self::$instance = new self();
        }

        return self::$instance;
    }
}