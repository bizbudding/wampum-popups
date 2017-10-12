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
 * Description:        A lightweight but flexible WordPress popups plugin
 * Plugin URI:         https://github.com/bizbudding/wampum-popups
 * Author:             Mike Hemberger
 * Author URI:         https://bizbudding.com
 * Text Domain:        wampum-popups
 * License:            GPL-2.0+
 * License URI:        http://www.gnu.org/licenses/gpl-2.0.txt
 *
 * Version:            2.1.0
 *
 * GitHub Plugin URI:  https://github.com/bizbudding/wampum-popups
 * GitHub Branch:      master
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Main functions to create a popup
 *
 * @since  1.0.0
 *
 * @param  string  $content  The content to output in the popup
 * @param  array   $args     Plugin options (type, style, time)
 */
function wampum_popup( $content, $args = array() ) {
	echo Wampum_Popups()->get_wampum_popup( $content, $args );
}

function get_wampum_popup( $content, $args = array() ) {
	return Wampum_Popups()->get_wampum_popup( $content, $args );
}

/**
 * Main functions to create a direct link to launch a popup
 * Type can only be 'link' or 'button'
 *
 * @since  2.0.0
 *
 * @param  string  $content  The content to output in the popup
 * @param  array   $args     Plugin options (type & text only)
 */
function wampum_popup_link( $content, $args = array() ) {
	// Params are backwards cause shortcodes have optional $content second
	echo Wampum_Popups()->wampum_popup_shortcode( $args, $content );
}

function get_wampum_popup_link( $content, $args = array() ) {
	// Params are backwards cause shortcodes have optional $content second
	return Wampum_Popups()->wampum_popup_shortcode( $args, $content );
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

	// Set popup counter
	private $popup_counter = 0;

	// Whether to use ouibounce or not
	private $ouibounce = false;

	// Set the wp_localize_script args variable
	private $localize_args = array();

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
			define( 'WAMPUM_POPUPS_VERSION', '2.1.0' );
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
	 * Plugin hooks, filters, and shortcode
	 *
	 * @since  1.0.0
	 *
	 * @return void
	 */
	function setup() {

		// Register styles and scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'stylesheets' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'scripts' ) );

		// Force large image url in all gallery links
		add_filter( 'wp_get_attachment_link', array( $this, 'gallery_image_url' ), 10, 4 );

		// Register our shortcode
		add_shortcode( 'wampum_popup', array( $this, 'wampum_popup_shortcode' ) );

		// Add new hook
		add_action( 'wampum_popups', array( $this, 'gallery_popup' ) );

		// Add our custom popup hook
		add_action( 'wp_footer', array( $this, 'popups_hook' ) );

		/**
		 * Setup the updater.
		 *
		 * @uses    https://github.com/YahnisElsts/plugin-update-checker/
		 *
		 * @return  void
		 */
		if ( ! class_exists( 'Puc_v4_Factory' ) ) {
			require_once WAMPUM_POPUPS_INCLUDES_DIR . 'vendor/plugin-update-checker/plugin-update-checker.php';
		}
		$updater = Puc_v4_Factory::buildUpdateChecker( 'https://github.com/bizbudding/wampum-popups/', __FILE__, 'wampum-popups' );
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
	function stylesheets() {
	    wp_register_style( 'wampum-popups', WAMPUM_POPUPS_PLUGIN_URL . 'css/wampum-popups.min.css', array(), WAMPUM_POPUPS_VERSION );
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
	function scripts() {
		wp_register_script( 'ouibounce', WAMPUM_POPUPS_PLUGIN_URL . 'js/ouibounce.min.js', array(), '0.0.12', true );
		wp_register_script( 'wampum-popups', WAMPUM_POPUPS_PLUGIN_URL . 'js/wampum-popups.min.js', 	array('jquery'), WAMPUM_POPUPS_VERSION, true );
	}

	/**
	 * Force large image url in all gallery links
	 *
	 * @since   2.0.0
	 *
	 * @return  string  Image URL
	 */
	function gallery_image_url( $html, $post_id, $size, $permalink ) {
		$size  = apply_filters( 'wampum_popups_gallery_image_size', 'large' );
		$image = wp_get_attachment_image_src( $post_id, $size );
		return preg_replace( '/href=\'(.*?)\'/', 'href=\'' . $image[0] . '\'', $html );
	}

	/**
	 * See get_wampum_popup()
	 * This is only here to handle the shortcode
	 *
	 * @since  2.0.0
	 */
	function wampum_popup_shortcode( $atts, $content = null ) {

		// Get popup content, while parsing shortcodes
		$popup_content = do_shortcode(trim($content));

		// Bail if no content
		if ( empty( $popup_content ) ) {
			return;
		}

		// Get popup as a variable so it updates popup_counter
		$popup = $this->get_wampum_popup( $popup_content, $atts );

		// Bail if no popup
		if ( ! $popup ) {
			return;
		}

		/**
		 * Echo popup to footer so it's not inline and weird
		 * If inline, it was also too aggressively adopting .entry-content related styles
		 */
		add_action( 'wampum_popups', function() use ( $popup ) {
			echo $popup;
		});

		// If popup is a link or button type, return them linked text
		$output = '';
		$click  = array('link','button');
		if ( in_array( $atts['type'], $click ) ) {
			if ( isset($atts['text']) && ! empty($atts['text']) ) {
				if ( 'link' == $atts['type'] ) {
					$classes = 'wampum-popup-link';
				} elseif ( 'button' == $atts['type'] ) {
					$classes = 'wampum-popup-link button';
				}
				$output = sprintf( '<a class="%s" href="#" data-popup="%s">%s</a>',
					$classes,
					$this->popup_counter,
					sanitize_text_field($atts['text'])
				);
			}
		}
		return $output;
	}

	/**
	 * Get a wampum popup
	 * Enqueues scripts
	 * Returns the popup so we can use it in a shortcode
	 *
	 * @since  1.0.2
	 *
	 * @param  string  $content  HTML to be used in the popup
	 * @param  array   $args     All the popup args
	 *
	 * @return string  popup HTML
	 */
	function get_wampum_popup( $content = null, $args = array() ) {

		// Bail if an exit or timed popup and has no content
		if ( in_array( $args['type'], array('exit','timed') ) && ! trim($content) ) {
			return;
		}

		// Popup args
		$defaults = array(
			'type'          => null,    // 'exit' or 'timed' or 'link' or 'button' or 'gallery (internal only)' (REQUIRED)
			'close_button'  => true,    // whether or not to show the close button
			'close_outside' => true,    // whether or not to allow close by clicking outside the modal
			'logged_in'     => false,   // whether or not to show only to logged in users
			'logged_out'    => false,   // whether or not to show only to logged out users
			'style'         => 'modal', // 'modal' or 'slideup'
			'time'          => '4000',  // time in milliseconds
			'width'         => '400px', // max-width of popup
			'aggressive'    => false,   // ouibounce - true
			'cookieExpire'  => false,   // ouibounce - 7
			'cookieDomain'  => false,   // ouibounce - .example.com
			'cookieName'    => 'wampumPopupViewed',   // ouibounce - 'custom_cookie_name'
			'delay'         => false,   // ouibounce - 100
			'sensitivity'   => false,   // ouibounce - 40
			'sitewide'      => true,    // ouibounce - true (don't be annoying)
			'timer'         => false,   // ouibounce - 10
		);
		$args = shortcode_atts( $defaults, $args, 'wampum_popup' );

		// Bail if we don't have a type, since it's required!
		$types = array('link','button','exit','gallery','timed');
		if ( ! in_array( $args['type'], $types ) ) {
			return;
		}

		// Bail if logged_in is true and user is not logged in
		if ( $args['logged_in'] && ! is_user_logged_in() ) {
			return;
		}

		// Bail if logged_out is true and user is not logged in
		if ( $args['logged_out'] && is_user_logged_in() ) {
			return;
		}

		// Array of types that use ouibounce
		$ouibounce = array('exit','timed');

		// If an ouibounce popup
		if ( in_array( $args['type'], $ouibounce ) ) {

			// Bail if popup is not aggressive and cookie has already been viewed
			$aggressive = filter_var( $args['aggressive'], FILTER_VALIDATE_BOOLEAN );
			$viewed     = isset($_COOKIE[$args['cookieName']]) && filter_var( $_COOKIE[$args['cookieName']], FILTER_VALIDATE_BOOLEAN ) == true ? true : false;
			if ( ! $aggressive && $viewed ) {
				return;
			}

			// If popup uses ouibounce, set as true
			$this->ouibounce = true;

		}

		// Increment the counter so JS can fire the correct popup if multiple on the same page!
		$this->popup_counter++;

		/**
		 * Add these args to a big localization array that saves each popup in its own index
		 * First get the array of this specific popup's args
		 * Then add this as an index to the main localize object
		 */
		$localize_args = $this->get_localize_script_args( $args );
		$this->localize_args[$this->popup_counter] = $localize_args;

		// Send it!
		return $this->get_wampum_popup_html( $content, $args, $this->popup_counter );

	}

	function get_wampum_popup_html( $content, $args, $index = null ) {
		// Maybe add close outside class
		$close_outside = filter_var( $args['close_outside'], FILTER_VALIDATE_BOOLEAN ) ? ' close-outside' : '';
		// The markup
		$output  = '';
		$output .= '<div id="wampum-popup-' . $index . '" class="wampum-popup" style="display:none;" data-popup="' . $index . '">';
			$output .= '<div class="wampum-popup-overlay' . $close_outside . '">';
				$output .= '<div class="wampum-popup-inner" style="max-width:' . $args['width'] . ';">';
					// If close button, add it
					if ( filter_var( $args['close_button'], FILTER_VALIDATE_BOOLEAN ) ) {
						$output .= '<div class="wampum-popup-button wampum-popup-close"><span class="screen-reader-text">Close Popup</span></div>';
					}
					$output .= '<div class="wampum-popup-content">';
					    $output .= $content;
					$output .= '</div>';
				$output .= '</div>';
			$output .= '</div>';
		$output .= '</div>';
		return $output;
	}

	/**
	 * Get the properly organized array of args to localize
	 *
	 * @since  2.0.0
	 *
	 * @param  array  $args  Accepted args to localize
	 *
	 * @return array
	 */
	function get_localize_script_args( $args ) {
		$popup_args = array(
			'close_button'  => $args['close_button'],
			'close_outside' => $args['close_outside'],
			'style'         => $args['style'],
			'time'          => $args['time'],
			'type'          => $args['type'],
			'width'         => $args['width'],
		);
		$ouibounce_args = array(
			'aggressive'   => $args['aggressive'],
			'cookieExpire' => $args['cookieExpire'],
			'cookieDomain' => $args['cookieDomain'],
			'cookieName'   => $args['cookieName'],
			'delay'        => $args['delay'],
			'sensitivity'  => $args['sensitivity'],
			'sitewide'     => $args['sitewide'],
			'timer'        => $args['timer'],
		);
		return array(
			'wampumpopups' => $popup_args,
			'ouibounce'    => $this->ouibounce_args( $ouibounce_args ),
		);
	}

	/**
	 * Strip out the args we don't want to send to the ouibounce() function
	 *
	 * @since   1.0.2
	 *
	 * @param   array  $ouibounce_args  Array of args to check agains
	 *
	 * @return  array  The args to keep
	 */
	function ouibounce_args( $ouibounce_args ) {
		$args = array();
		// Script defaults
		$defaults = array(
			'aggressive'   => false,   // true
			'cookieExpire' => false,   // 7
			'cookieDomain' => false,   // .example.com
			'cookieName'   => false,   // 'custom_cookie_name'
			'delay'        => false,   // 100
			'sensitivity'  => false,   // 40
			'sitewide'     => true,    // true (don't be annoying)
			'timer'        => false,   // 10
		);
		$ouibounce_args	= wp_parse_args( $ouibounce_args, $defaults );
		foreach ( $ouibounce_args as $key => $value ) {
			if ( $value == false ) {
				continue;
			}
			$args[$key] = $value;
		}
		return $args;
	}

	function gallery_popup() {
		// If not a singular view and no gallery
		if ( ! ( is_singular() && get_post_gallery( get_the_ID(), false ) ) ) {
			return;
		}
		// Output popup with empty span so it forces display
		$args = array(
			'aggressive'    => true,
			'close_outside' => false,
			'style'         => 'modal',
			'type'          => 'gallery',
			'width'         => 'auto',
		);
		wampum_popup( '', $args );
	}

	/**
	 * Add a new hook so devs can safely add a new popup without things breaking if this plugin gets deactivated
	 *
	 * @since   1.1.0
	 *
	 * @return  null
	 */
	function popups_hook() {

		do_action( 'wampum_popups' );

		// If we have at least 1 popup
		if ( ( $this->popup_counter > 0 ) ) {
			$css = apply_filters( 'wampum_popups_load_css', '__return_true' );
			if ( $css ) {
				// Styles baby
				wp_enqueue_style( 'wampum-popups' );
			}
			// Scripts baby
			if ( $this->ouibounce ) {
				wp_enqueue_script( 'ouibounce' );
			}
			wp_enqueue_script( 'wampum-popups' );
			wp_localize_script( 'wampum-popups', 'wampum_popups_vars', $this->localize_args );
		}

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
