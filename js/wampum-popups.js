/*!
 * Wampum Popups
 */
( function ( document, $, undefined ) {
	'use strict';

	// Find all the popups on the page
	$.each( $( '.wampum-popup' ), function( key, value ) {

		var index		= $(this).attr( 'data-popup' );
		var popup		= $( '#wampum-popup-' + index );
		var popup_vars	= wampum_popups_vars[index].wampumpopups;
		var oui_vars	= wampum_popups_vars[index].ouibounce;

		// Add class to the popup wrap
		popup.addClass( 'wampum-' + popup_vars.style );

		// Set the empty oui array
		var oui = [];

		// Loop through the properties
		for ( var prop in oui_vars ) {
		    if ( oui_vars.hasOwnProperty( prop ) ) {
		 		// Set each custom property as an option in ouibounce
			 	oui[prop] = oui_vars[prop];
		    }
		}

		// This is default ouibounce, so easy!
		if ( popup_vars.type == 'exit' ) {
			// Set the popup object
			ouibounce( popup[0], oui );
		}

		// If timed, force firing of popup
		if ( popup_vars.type == 'timed' ) {

			if ( oui['aggressive'] ) {
				/**
				 * Force cookie name if aggressive mode is used
				 * This prevents a cookie being set that may be used elsewhere for timed/exit popup
				 */
				oui['cookieName'] = 'wampumPopupAggressive';
				// Set the ouibounce object as a variable
				var _ouibounce = ouibounce( popup[0], oui );
				// Force fire after given time
				setTimeout(function() {
					_ouibounce.fire();
				}, popup_vars.time );
			} else {
				// If this popup hasn't been viewed yet
				if ( typeof $.cookie(oui['cookieName']) === 'undefined' ) {
					// Set the ouibounce object as a variable
					var _ouibounce = ouibounce( popup[0], oui );
					// Force fire after given time
					setTimeout(function() {
						_ouibounce.fire();
					}, popup_vars.time );
				}
			}

		}

		// Close if clicking the close button
		$( popup ).on( 'click', '.wampum-popup-close', function() {
			popup.fadeOut('fast');
			_ouibounce.disable();
		});

		if ( popup_vars.close_outside ) {

		    // Close popup listener
		    $('body').mouseup(function(e){
		        // Set our popup as a variable
		        var content = popup.find('.wampum-popup-content');
		        /**
		         * If click is not on our popup
		         * If click is not on a child of our popup
		         */
		        if ( ! $(this).parents().hasClass('wampum-popup-content') && ! content.has(e.target).length ) {
		            popup.fadeOut('fast');
		            _ouibounce.disable();
		        }
		    });

		}

	}); // end each

})( this, jQuery );
