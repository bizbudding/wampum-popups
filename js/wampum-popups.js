/*!
 * Wampum Popups
 */
( function ( document, $, undefined ) {
	'use strict';

	// Add class to the popup wrap
	$( '.wampum-popup' ).addClass( 'wampum-' + wampum_popups_vars.wampumpopups.style );

	// Set the empty options array
	var options = [];

	// Loop through the properties
	for ( var prop in wampum_popups_vars.ouibounce ) {
	    if ( wampum_popups_vars.ouibounce.hasOwnProperty( prop ) ) {
	 		// Set each custom property as an option in ouibounce
		 	options[prop] = wampum_popups_vars.ouibounce[prop];
	    }
	}

	if ( wampum_popups_vars.wampumpopups.type == 'exit' ) {
		// Set the popup object
		ouibounce( $('.wampum-popup')[0], options );
	}

	// If timed, force firing of popup
	if ( wampum_popups_vars.wampumpopups.type == 'timed' ) {

		var _ouibounce = ouibounce( $('.wampum-popup')[0], options );

		_ouibounce.aggressive = true,

		setTimeout(function() {
			_ouibounce.fire();
			_ouibounce.disable();
		}, wampum_popups_vars.wampumpopups.time );
	}

	// Close if clicking the close button
	$('.wampum-popup-close').on( 'click', 'button', function() {
		$('.wampum-popup').hide();
	});


	if ( wampum_popups_vars.wampumpopups.close_outside ) {
	console.log(wampum_popups_vars.wampumpopups.close_outside);

	    // Close popup listener
	    $('body').mouseup(function(e){
	        // Set our popup as a variable
	        var popup = $('.wampum-popup-content');
	        /**
	         * If click is not on our popup
	         * If click is not on a child of our popup
	         */
	        if ( ! $(this).parents().hasClass('wampum-popup') && ! popup.has(e.target).length ) {
	            $('.wampum-popup').hide();
	        }
	    });

	}

})( this, jQuery );