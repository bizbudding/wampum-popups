<?php
/**
 * @package   Slim_Popups_Setup
 * @author    BizBudding, INC <mike@bizbudding.com>
 * @license   GPL-2.0+
 * @link      http://bizbudding.com.com
 * @copyright 2016 BizBudding, INC
 *
 * @wordpress-plugin
 * Plugin Name:        Slim Popups
 * Description: 	   A lightweight popups plugin utilizing on oiubounce and/or magnific popups scripts
 * Plugin URI:         TBD
 * Author:             Mike Hemberger
 * Author URI:         http://bizbudding.com
 * Text Domain:        wampum
 * License:            GPL-2.0+
 * License URI:        http://www.gnu.org/licenses/gpl-2.0.txt
 * Version:            1.0.0
 * GitHub Plugin URI:  TBD
 * GitHub Branch:	   master
 */


// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Slim_Popups_Setup' ) ) :
/**
 * Main Slim_Popups_Setup Class.
 *
 * @since 1.0.0
 */
final class Slim_Popups_Setup {
	/**
	 * Singleton
	 * @var   Slim_Popups_Setup The one true Slim_Popups_Setup
	 * @since 1.0.0
	 */
	private static $instance;

	public $templates;
	/**
	 * Main Slim_Popups_Setup Instance.
	 *
	 * Insures that only one instance of Slim_Popups_Setup exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
	 *
	 * @since   1.0.0
	 * @static  var array $instance
	 * @return  object | Slim_Popups_Setup The one true Slim_Popups_Setup
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			// Setup the setup
			self::$instance = new Slim_Popups_Setup;
			// Methods
			self::$instance->setup_constants();
			self::$instance->includes();
			self::$instance->setup();
			// Instantiate Classes
			self::$instance->templates = Slim_Popups_Template_Loader::instance();
		}
		return self::$instance;
	}
	/**
	 * Throw error on object clone.
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @since   1.0.0
	 * @access  protected
	 * @return  void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wampum' ), '1.0' );
	}
	/**
	 * Disable unserializing of the class.
	 *
	 * @since   1.0.0
	 * @access  protected
	 * @return  void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'wampum' ), '1.0' );
	}
	/**
	 * Setup plugin constants.
	 *
	 * @access private
	 * @since 1.0.0
	 * @return void
	 */
	private function setup_constants() {
		// Plugin version.
		if ( ! defined( 'SLIM_POPUPS_VERSION' ) ) {
			define( 'SLIM_POPUPS_VERSION', '1.0.0' );
		}
		// Plugin Folder Path.
		if ( ! defined( 'SLIM_POPUPS_PLUGIN_DIR' ) ) {
			define( 'SLIM_POPUPS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
		}
		// Plugin Includes Path
		if ( ! defined( 'SLIM_POPUPS_INCLUDES_DIR' ) ) {
			define( 'SLIM_POPUPS_INCLUDES_DIR', SLIM_POPUPS_PLUGIN_DIR . 'includes/' );
		}
		// Plugin Folder URL.
		if ( ! defined( 'SLIM_POPUPS_PLUGIN_URL' ) ) {
			define( 'SLIM_POPUPS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		}
		// Plugin Root File.
		if ( ! defined( 'SLIM_POPUPS_PLUGIN_FILE' ) ) {
			define( 'SLIM_POPUPS_PLUGIN_FILE', __FILE__ );
		}
		// Plugin Base Name
		if ( ! defined( 'SLIM_POPUPS_BASENAME' ) ) {
			define( 'SLIM_POPUPS_BASENAME', dirname( plugin_basename( __FILE__ ) ) );
		}
	}
	/**
	 * Include required files.
	 *
	 * @access private
	 * @since 1.0.0
	 * @return void
	 */
	private function includes() {
		// Vendor
		require_once SLIM_POPUPS_INCLUDES_DIR . 'lib/class-gamajo-template-loader.php';
		// Classes
		require_once SLIM_POPUPS_INCLUDES_DIR . 'class-template-loader.php';
		// Functions
		require_once SLIM_POPUPS_INCLUDES_DIR . 'functions.php';
	}

	function setup() {
		register_activation_hook( __FILE__,   array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
		// Register styles and scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'register_stylesheets' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );
	}

	function activate() {
	}

	function deactivate() {
	}

	/**
	 * Register stylesheets for later use
	 *
	 * Use via wp_enqueue_style('wampum'); in a template
	 *
	 * @since  1.0.0
	 *
	 * @return null
	 */
	function register_stylesheets() {
	    wp_register_style( 'slim-popups', SLIM_POPUPS_PLUGIN_URL . 'css/slim-popups.css', array(), SLIM_POPUPS_VERSION );
	}

	/**
	 * Register scripts for later use
	 *
	 * Use via wp_enqueue_script('magnific-popups'); in a template
	 *
	 * @since  1.0.0
	 *
	 * @return null
	 */
	function register_scripts() {
		wp_register_script( 'ouibounce',  		 SLIM_POPUPS_PLUGIN_URL . 'js/ouibounce.min.js', 			 array(), 			'0.0.12', 			  true );
		// wp_register_script( 'magnific',   		 SLIM_POPUPS_PLUGIN_URL . 'js/jquery.magnific-popup.min.js', array(), 			'1.1.0',  			  true );
		// wp_register_script( 'slim-popups-exit',  SLIM_POPUPS_PLUGIN_URL . 'js/slim-popups-exit.js',	 	  	 array('ouibounce'), SLIM_POPUPS_VERSION, true );
		// wp_register_script( 'slim-popups-timed', SLIM_POPUPS_PLUGIN_URL . 'js/slim-popups-timed.js',	 	 array('magnific'),  SLIM_POPUPS_VERSION, true );
		wp_register_script( 'slim-popups', 		 SLIM_POPUPS_PLUGIN_URL . 'js/slim-popups.js',	 	 		 array('ouibounce'),  SLIM_POPUPS_VERSION, true );
		// wp_enqueue_script( 'ouibounce',  SLIM_POPUPS_PLUGIN_URL . 'js/ouibounce.min.js', array('jquery'), 	 '0.0.12', 			 true );
		// wp_enqueue_script( 'slim-popups', SLIM_POPUPS_PLUGIN_URL . 'js/slim-popups.js',	 array('ouibounce'), SLIM_POPUPS_VERSION, true );
	}

	function slim_popup( $filename, $args = array(), $script_options = array() ) {
		// Script defaults
		$defaults = array(
			'aggressive'	=> false,   // true
			'callback'		=> false,   // function() { console.log('slim popups fired!'); }
			'cookieexpire'	=> false,   // 7
			'cookiedomain'	=> false,   // .example.com
			'cookiename'	=> false,   // 'customCookieName'
			'css'  			=> true, 	// whether or not to load the stylesheet
			'delay'			=> false,   // 100
			'sensitivity'	=> false,   // 40
			'sitewide'		=> false,   // true
			'style'			=> 'modal', // 'modal' or 'slideup'
			'time'			=> 4000,    // time in milliseconds
			'timer'			=> false,   // 10
			'type' 			=> 'exit',  // 'exit' or 'timed'
		);
		$args = wp_parse_args( $args, $defaults );

		wp_enqueue_script('ouibounce');
		wp_enqueue_script('slim-popups');

		$this->localize_script($args);

		if ( $args['css'] ) {
			wp_enqueue_style('slim-popups');
		}

		echo '<div id="slim-popups" style="display:none;">';
			echo '<div class="slim-popups-underlay"></div>';
			echo '<div class="slim-popups-overlay">';
				echo '<div class="slim-popup">';
				echo '<div class="slim-popups-close"><button>×<span class="screen-reader-text">Close Popup</span></button></div>';
					echo '<div class="slim-popups-content">';
					    $this->templates->get_template_part( $filename, null, true );
					echo '</div>';
				echo '</div>';
		echo '</div>';
	}

	function localize_script( $args ) {
		wp_localize_script( 'slim-popups', 'slim_popups_vars', $args );
	}

}
endif; // End if class_exists check.

/**
 * The main function for that returns Slim_Popups_Setup
 *
 * The main function responsible for returning the one true Slim_Popups_Setup
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $slim_popups = Slim_Popups_Setup_Init(); ?>
 *
 * @since 1.0.0
 *
 * @return object|Slim_Popups_Setup The one true Slim_Popups_Setup Instance.
 */
function Slim_Popups() {
	return Slim_Popups_Setup::instance();
}
// Get Slim_Popups_Setup Running.
Slim_Popups();
