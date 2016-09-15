/*!
 * Slim Popups
 */
( function ( document, $, undefined ) {
	'use strict';

	// Add class to the popup wrap
	$( '#slim-popups' ).addClass( 'slim-' + slim_popups_vars.slimpopups.style );

	// Set the empty options array
	var options = [];

	// Loop through the properties
	for ( var prop in slim_popups_vars.ouibounce ) {
	    if ( slim_popups_vars.ouibounce.hasOwnProperty( prop ) ) {
	 		// Set each custom property as an option in ouibounce
		 	options[prop] = slim_popups_vars.ouibounce[prop];
	    }
	}

	if ( slim_popups_vars.slimpopups.type == 'exit' ) {

		// Set the popup object
		// var _ouibounce = ouibounce($('#slim-popups')[0]);
		ouibounce( $('#slim-popups')[0], options );

	}

	// If timed, force firing of popup
	if ( slim_popups_vars.slimpopups.type == 'timed' ) {

		var _ouibounce = ouibounce( $('#slim-popups')[0], options );

		_ouibounce.aggressive = true,

		setTimeout(function() {
			_ouibounce.fire();
			_ouibounce.disable();
		}, slim_popups_vars.slimpopups.time );
	}

	// Close if clicking outside the popup
	$('body').on('click', function() {
		$('#slim-popups').hide();
	});

	// Close if clicking the close button
	$('.slim-popups-close').on( 'click', 'a', function() {
		$('#slim-popups').hide();
	});

})( this, jQuery );