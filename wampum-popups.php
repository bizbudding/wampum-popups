<?php
/**
 * @package   Wampum_Popups_Setup
 * @author    BizBudding, INC <mike@bizbudding.com>
 * @license   GPL-2.0+
 * @link      http://bizbudding.com.com
 * @copyright 2016 BizBudding, INC
 *
 * @wordpress-plugin
 * Plugin Name:        Wampum - Popups
 * Description: 	   A lightweight developer-based popups plugin utilizing oiubounce
 * Plugin URI:         https://github.com/JiveDig/wampum-popups
 * Author:             Mike Hemberger
 * Author URI:         https://bizbudding.com
 * Text Domain:        wampum-popups
 * License:            GPL-2.0+
 * License URI:        http://www.gnu.org/licenses/gpl-2.0.txt
 * Version:            1.0.2
 * GitHub Plugin URI:  https://github.com/JiveDig/wampum-popups
 * GitHub Branch:	   master
 */


// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Main function to create a popup
 *
 * Basic usage - Popup content in child theme: /child-theme-name/wampum-popups/main-popup.php
 * wampum_popup( 'main-popup' );
 *
 * Showing default settings
 * $options = array(
 * 		'css'  			=> true, 	// whether or not to load the stylesheet
 *		'style'			=> 'modal', // 'modal' or 'slideup'
 *		'time'			=> '4000',  // time in milliseconds
 *		'type' 			=> 'exit',  // 'exit' or 'timed'
 *		'close_button'	=> true,	// whether or not to show the close button
 *		'close_outside'	=> true,	// whether or not to allow close by clicking outside the modal
 *		'width'	 		=> '400',   // Max popup content width in pixels
 * );
 * wampum_popup( 'main-popup', $options );
 *
 * @since  1.0.0
 *
 * @param  string  $content  File name (one word, no hyphens or underscores)
 * @param  array   $options	  Plugin options (css, style, time, type)
 * @param  array   $args   	  Ouibounce object properties. See (https://github.com/carlsednaoui/ouibounce#options)
 *
 * @return void
 */
function wampum_popup( $content, $options = array(), $args = array() ) {
	Wampum_Popups()->wampum_popup( $content, $options, $args );
}

if ( ! class_exists( 'Wampum_Popups_Setup' ) ) :
/**
 * Main Wampum_Popups_Setup Class.
 *
 * @since 1.0.0
 */
final class Wampum_Popups_Setup {
	/**
	 * Singleton
	 * @var   Wampum_Popups_Setup The one true Wampum_Popups_Setup
	 * @since 1.0.0
	 */
	private static $instance;

	public $templates;
	/**
	 * Main Wampum_Popups_Setup Instance.
	 *
	 * Insures that only one instance of Wampum_Popups_Setup exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
	 *
	 * @since   1.0.0
	 * @static  var array $instance
	 * @return  object | Wampum_Popups_Setup The one true Wampum_Popups_Setup
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			// Setup the setup
			self::$instance = new Wampum_Popups_Setup;
			// Methods
			self::$instance->setup_constants();
			self::$instance->includes();
			self::$instance->setup();
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
		if ( ! defined( 'WAMPUM_POPUPS_VERSION' ) ) {
			define( 'WAMPUM_POPUPS_VERSION', '1.0.2' );
		}
		// Plugin Folder Path.
		if ( ! defined( 'WAMPUM_POPUPS_PLUGIN_DIR' ) ) {
			define( 'WAMPUM_POPUPS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
		}
		// Plugin Includes Path
		if ( ! defined( 'WAMPUM_POPUPS_INCLUDES_DIR' ) ) {
			define( 'WAMPUM_POPUPS_INCLUDES_DIR', WAMPUM_POPUPS_PLUGIN_DIR . 'includes/' );
		}
		// Plugin Folder URL.
		if ( ! defined( 'WAMPUM_POPUPS_PLUGIN_URL' ) ) {
			define( 'WAMPUM_POPUPS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		}
		// Plugin Root File.
		if ( ! defined( 'WAMPUM_POPUPS_PLUGIN_FILE' ) ) {
			define( 'WAMPUM_POPUPS_PLUGIN_FILE', __FILE__ );
		}
		// Plugin Base Name
		if ( ! defined( 'WAMPUM_POPUPS_BASENAME' ) ) {
			define( 'WAMPUM_POPUPS_BASENAME', dirname( plugin_basename( __FILE__ ) ) );
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
	}

	function setup() {

		register_activation_hook( __FILE__,   array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

		// Register styles and scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'register_stylesheets' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );

		// Add our custom popup hook
		add_action( 'wp_footer', array( $this, 'popups_hook' ) );
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
	    wp_register_style( 'wampum-popups', WAMPUM_POPUPS_PLUGIN_URL . 'css/wampum-popups.css', array(), WAMPUM_POPUPS_VERSION );
	}

	/**
	 * Register scripts for later use
	 *
	 * Use via wp_enqueue_script('wampum-popups'); in a template
	 *
	 * @since  1.0.0
	 *
	 * @return null
	 */
	function register_scripts() {
		wp_register_script( 'ouibounce', WAMPUM_POPUPS_PLUGIN_URL . 'js/ouibounce.min.js', array(), '0.0.12', true );
		wp_register_script( 'wampum-popups', WAMPUM_POPUPS_PLUGIN_URL . 'js/wampum-popups.js', 	array('ouibounce'), WAMPUM_POPUPS_VERSION, true );
	}

	/**
	 * Add a new hook so devs can safely add a new popup without things breaking if this plugin gets deactivated
	 *
	 * @since 	1.1.0
	 *
	 * @return 	null
	 */
	function popups_hook() {
		do_action( 'wampum_popups' );
	}

	function wampum_popup( $content = '', $options = array(), $args = array() ) {

		// Bail if popup has no content
		if ( ! $content ) {
			return;
		}

		// Popup options
		$defaults = array(
			'css'  			=> true, 	// whether or not to load the stylesheet
			'style'			=> 'modal', // 'modal' or 'slideup'
			'time'			=> '4000',  // time in milliseconds
			'type' 			=> 'exit',  // 'exit' or 'timed'
			'close_button'	=> true,	// whether or not to show the close button
			'close_outside'	=> true,	// whether or not to allow close by clicking outside the modal
			'width'	 		=> '400',   // Max popup content width in pixels
		);
		$options = wp_parse_args( $options, $defaults );

		wp_enqueue_script('ouibounce');
		wp_enqueue_script('wampum-popups');

		$this->localize_script( $options, $args );

		if ( filter_var( $options['css'], FILTER_VALIDATE_BOOLEAN ) ) {
			wp_enqueue_style('wampum-popups');
		}

		$close_outside = filter_var( $options['close_outside'], FILTER_VALIDATE_BOOLEAN ) ? ' close-outside' : '';

		echo '<div class="wampum-popup" style="display:none;">';
			echo '<div class="wampum-popup-overlay' . $close_outside . '">';
				echo '<div class="wampum-popup-content" style="max-width:' . $options['width'] . 'px;">';
					if ( filter_var( $options['close_button'], FILTER_VALIDATE_BOOLEAN ) ) {
						echo '<button class="wampum-popup-close">×<span class="screen-reader-text">Close Popup</span></button>';
					}
				    echo $content;
				echo '</div>';
			echo '</div>';
		echo '</div>';

	}

	function localize_script( $options, $args ) {
		$array = array(
			'wampumpopups'	=> $options,
			'ouibounce'		=> $this->ouibounce_args( $args ),
		);
		wp_localize_script( 'wampum-popups', 'wampum_popups_vars', $array );
	}

	function ouibounce_args( $args ) {
		// Script defaults
		$defaults = array(
			'aggressive'	=> false,   // true
			'callback'		=> false,   // function() { console.log('slim popups fired!'); }
			'cookieexpire'	=> false,   // 7
			'cookiedomain'	=> false,   // .example.com
			'cookiename'	=> false,   // 'custom_cookie_name'
			'delay'			=> false,   // 100
			'sensitivity'	=> false,   // 40
			'sitewide'		=> true,    // true (don't be annoying)
			'timer'			=> false,   // 10
		);
		$args  = wp_parse_args( $args, $defaults );
		$array = array();
		foreach ( $args as $key => $value ) {
			if ( $value == false ) {
				continue;
			}
			$array[$key] = $value;
		}
		return $array;
	}

}
endif; // End if class_exists check.

/**
 * The main function for that returns Wampum_Popups_Setup
 *
 * The main function responsible for returning the one true Wampum_Popups_Setup
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $wampum_popups = Wampum_Popups_Setup_Init(); ?>
 *
 * @since 1.0.0
 *
 * @return object|Wampum_Popups_Setup The one true Wampum_Popups_Setup Instance.
 */
function Wampum_Popups() {
	return Wampum_Popups_Setup::instance();
}
// Get Wampum_Popups_Setup Running.
Wampum_Popups();
