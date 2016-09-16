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

	// Close if clicking the close button
	$('.slim-popups-close').on( 'click', 'button', function() {
		$('#slim-popups').hide();
	});

    // Close popup listener
    $('body').mouseup(function(e){
        // Set our popup as a variable
        var popup = $('.slim-popup');
        /**
         * If click is not on our popup
         * If click is not on a child of our popup
         */
        if ( ! $(this).parents().hasClass('slim-popup') && ! popup.has(e.target).length ) {
            $('#slim-popups').hide();
        }
    });

})( this, jQuery );