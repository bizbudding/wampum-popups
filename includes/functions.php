<?php

/**
 * Helper function to get template part
 *
 * slim_popup( 'popup', 'category' );
 *
 * This will try to load in the following order:
 * 1: wp-content/themes/theme-name/slim-popups/popups-category.php
 * 2: wp-content/themes/theme-name/slim-popups/popups.php
 * 3: wp-content/plugins/slim-popups/templates/popups-category.php
 * 4: wp-content/plugins/slim-popups/templates/popups.php.
 *
 * @since  1.0.0
 *
 * @param  string  $filename  file name (one word, no hyphens or underscores)
 * @param  array   $args   	  whether or not to load the CSS file
 *
 * @return mixed
 */
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
	slim_popups_localize_script($args);

	if ( $args['css'] ) {
		wp_enqueue_style('slim-popups');
	}

	echo '<div id="slim-popups" style="display:none;">';
		echo '<div class="slim-popups-underlay"></div>';
		echo '<div class="slim-popups-overlay">';
			echo '<div class="slim-popup">';
			echo '<div class="slim-popups-close"><button>×<span class="screen-reader-text">Close Popup</span></button></div>';
				echo '<div class="slim-popups-content">';
				    Slim_Popups()->templates->get_template_part( $filename, null, true );
				echo '</div>';
			echo '</div>';
	echo '</div>';
}

function slim_popups_localize_script( $args ) {
	wp_localize_script( 'slim-popups', 'slim_popups_vars', $args );
}
