/*!
 * Slim Popups init
 */
( function ( document, $, undefined ) {
	'use strict';

	// var modal = ouibounce(document.getElementById('ouibounce-modal'));
	ouibounce($('#slim-popups')[0]);
	// var modal =  ouibounce($('#slim-popups-modal')[0]);

	// setTimeout(launchPopups, 3000);

	// launchPopups() {
		// model
	// }
	// ouibounce(document.getElementById("slim-popups-modal"), {
	// });
	// ouibounce($("#slim-popups-modal")[0], {
		// aggressive: true,
		// timer: 2,
	// });

	$('#slim-popups-modal').on('click', function() {
		$('#slim-popups-modal').hide();
	});

})( this, jQuery );