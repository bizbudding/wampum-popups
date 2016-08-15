/*!
 * Slim Popups init
 */
( function ( document, $, undefined ) {
	'use strict';

	var _ouibounce = ouibounce($('#slim-popups')[0], {
	    aggressive: true,
	});

	// Add text to the button
	// $( '.slim-popups-close' ).find('button').append( slim_popups_timed_vars.closetext );
	$( '#slim-popups' ).addClass( 'slim-' + slim_popups_timed_vars.style );

	setTimeout(function() {

		_ouibounce.fire();

		$('body').on('click', function() {
			$('#slim-popups').hide();
		});

		$('.slim-popups-close').on( 'click', 'a', function() {
			$('#slim-popups').hide();
		});

	}, slim_popups_timed_vars.time );

	_ouibounce.disable();

})( this, jQuery );