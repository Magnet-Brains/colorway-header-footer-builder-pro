<?php
namespace ColorwayHF\Core;
use ColorwayHF\Libs\Framework\Attr;

defined( 'ABSPATH' ) || exit;

/**
 * Module registrar.
 *
 * Call assosiated classes of every modules.
 *
 * @since 1.0.0
 * @access public
 */
class Build_Modules{

    private $module_dir;

	/**
	 * Hold the module list.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */

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


    private $system_modules = [
        'dynamic-content',
        'library',
        'controls',
    ];

    private $core_modules;

    private $active_modules;

    public function __construct(){
        $this->core_modules = \ColorwayHF::default_modules();
        $this->active_modules = Attr::instance()->utils->get_option('module_list', $this->core_modules);
        $this->active_modules = array_merge($this->active_modules, $this->system_modules);

        foreach($this->active_modules as $module){

            // make the class name and call it.
            $class_name = '\ColorwayHF\Modules\\'. \ColorwayHF\Utils::make_classname($module) .'\Init';
            //var_dump(get_option('module_list', $this->core_modules));
            new $class_name();
        }
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