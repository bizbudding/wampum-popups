/*!
 * Slim Popups
 */
( function ( document, $, undefined ) {
	'use strict';

	// Add class to the popup wrap
	$( '#slim-popups' ).addClass( 'slim-' + slim_popups_vars.style );

	var aggressive = false;
	if ( slim_popups_vars.type == 'timed' ) {
		aggressive = true;
	}

	var _ouibounce = ouibounce($('#slim-popups')[0], {
		aggressive: aggressive,
		callback: slim_popups_vars.callback,
		cookiedomain: slim_popups_vars.cookiedomain,
		cookieexpire: slim_popups_vars.cookieexpire,
		cookiename: slim_popups_vars.cookiename,
		delay: slim_popups_vars.delay,
		sensitivity: slim_popups_vars.sensitivity,
		sitewide: slim_popups_vars.sitewide,
		timer: slim_popups_vars.timer,
	});

	if ( slim_popups_vars.type == 'timed' ) {
		setTimeout(function() {
			_ouibounce.fire();
			_ouibounce.disable();
		}, slim_popups_vars.time );
	}

	$('body').on('click', function() {
		$('#slim-popups').hide();
	});

	$('.slim-popups-close').on( 'click', 'a', function() {
		$('#slim-popups').hide();
	});

})( this, jQuery );