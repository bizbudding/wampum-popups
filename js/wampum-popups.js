/*!
 * Wampum Popups
 */
( function ( document, $, undefined ) {
	'use strict';

	// Launch 'click' type popups manually
	$('body').on( 'click', '.wampum-popup-link', function(e){
		e.preventDefault();
		$( '#wampum-popup-' + $(this).attr('data-popup') ).show();
	});

	// Find all the popups on the page
	$.each( $( '.wampum-popup' ), function( key, value ) {

		var index		= $(this).attr( 'data-popup' );
		var popup		= $( '#wampum-popup-' + index );
		var popup_vars	= wampum_popups_vars[index].wampumpopups;
		var oui_vars	= wampum_popups_vars[index].ouibounce;

		// Set the empty oui array
		var oui = [];

		// Loop through the properties
		for ( var prop in oui_vars ) {
		    if ( oui_vars.hasOwnProperty( prop ) ) {
		 		// Set each custom property as an option in ouibounce
			 	oui[prop] = oui_vars[prop];
		    }
		}

		// Add class to the popup wrap
		popup.addClass( 'wampum-' + popup_vars.style + ' wampum-' + popup_vars.type );

		// Gallery popup (should only be used internally, when [gallery] shortcode is present)
		if ( popup_vars.type == 'gallery' ) {

			var content = popup.find('.wampum-popup-content');

			// If more than one gallery image, add prev/next buttons
			if ( $('.gallery-item').length > 1 ) {
				content.after('<span style="display:none;" class="wampum-popup-button wampum-popup-next"><span class="screen-reader-text">Next</span></span>');
				content.after('<span style="display:none;" class="wampum-popup-button wampum-popup-prev"><span class="screen-reader-text">Previous</span></span>');
			}

			$( '.gallery-item' ).on( 'click', 'a', function(e){

				e.preventDefault();

				var src	= $(this).prop('href');
				var alt = $(this).find('img').attr('alt');

				// Remove any existing images
				content.find('img').remove();

				// Add image to content
				content.append('<img style="width:auto;height:auto;" src="' + src + '" alt="' + alt + '"/>');

				// Show it, this has nothing to do with ouibounce
				popup.show();

				// Get the image variable
				var img	= popup.find('img');

				var prev_button	= popup.find('.wampum-popup-prev').first();
				var next_button	= popup.find('.wampum-popup-next').first();
				var current		= $(this).parents('.gallery-item');
				var prev_item	= current.prevAll('.gallery-item').first();
				var next_item	= current.nextAll('.gallery-item').first();

				if ( prev_item.length > 0 ) {
					setTimeout(function() {
						prev_button.fadeIn('fast');
					}, 500 );
				} else {
					prev_button.hide();
				}

				if ( next_item.length > 0 ) {
					setTimeout(function() {
						next_button.fadeIn('fast');
					}, 500 );
				} else {
					next_button.hide();
				}

				// Resize after image is loaded (so we make sure to get the right size!)
				img.on( 'load', function() {
					// Resize
					resizeContent(img);
				});

				// Resize image when window is resized
				$(window).resize(function() {
					resizeContent(img);
				});

				// Change image on arrow click
				prev_button.on( 'click', function() {
					doPreviousItem();
				});

				// Change image on arrow click
				next_button.on( 'click', function() {
					doNextItem();
				});

				// Change change with left/right arrow keys
				$(document).keydown(function(e) {
				    switch(e.which) {
				        case 37: // left
				        doPreviousItem();
				        break;

				        case 38: // up
				        break;

				        case 39: // right
				        doNextItem();
				        break;

				        case 40: // down
				        break;

				        default: return; // exit this handler for other keys
				    }
				    e.preventDefault(); // prevent the default action (scroll / move caret)
				});

				function doPreviousItem() {

					if ( ! prev_item.is(':visible') ) {
						return;
					}

					// Set the new current item
					current = prev_item;

					// Update image
					img.attr( 'src', current.find('a').prop('href') ).attr( 'alt', current.find('img').attr('alt') );

					// Update the item variables
					prev_item = current.prevAll('.gallery-item') ? current.prevAll('.gallery-item').first() : '';
					next_item = current.nextAll('.gallery-item') ? current.nextAll('.gallery-item').first() : '';

					// If there is an item, show it, otherwise hide it
					if ( prev_item.length > 0 ) {
						prev_button.fadeIn('fast');
					} else {
						prev_button.hide();
					}

					if ( next_item.length > 0 ) {
						next_button.fadeIn('fast');
					} else {
						next_button.hide();
					}
				}

				function doNextItem() {

					if ( ! next_item.is(':visible') ) {
						return;
					}

					// Set the new current item
					current = next_item;

					// Update image
					img.attr( 'src', current.find('a').prop('href') ).attr( 'alt', current.find('img').attr('alt') );

					// Update the item variables
					prev_item = current.prevAll('.gallery-item') ? current.prevAll('.gallery-item').first() : '';
					next_item = current.nextAll('.gallery-item') ? current.nextAll('.gallery-item').first() : '';

					// If there is an item, show it, otherwise hide it
					if ( prev_item.length > 0 ) {
						prev_button.fadeIn('fast');
					} else {
						prev_button.hide();
					}

					if ( next_item.length > 0 ) {
						next_button.fadeIn('fast');
					} else {
						next_button.hide();
					}
				}

			});

		}

		// This is default ouibounce, so easy!
		if ( popup_vars.type == 'exit' ) {
			// Set the popup object
			ouibounce( popup[0], oui );
		}

		// If timed, force firing of popup
		if ( popup_vars.type == 'timed' ) {

			if ( oui['aggressive'] == 'true' ) {

				// Show it, this has nothing to do with ouibounce
				popup.show();

			} else {

				/**
				 * Fire away!
				 *
				 * Note: if popup is not aggressive and cookie has been viewed, it would still fire
				 * This was handled by using PHP to check if the cookie has been viewed
				 * Popup will not even load if cookie has been viewed, therefore we can force fire all of them
				 * @see  get_wampum_popup() in wampum-popups.php
				 */
				// Set the ouibounce object as a variable
				var _ouibounce = ouibounce( popup[0], oui );
				// Force fire after given time
				setTimeout(function() {
					_ouibounce.fire();
				}, popup_vars.time );

			}

		}

		// Close if clicking the close button
		$( popup ).on( 'click', '.wampum-popup-close', function() {
			closePopup( popup );
		});

		// If close_outside is true
		if ( popup_vars.close_outside ) {

		    // Close popup listener
		    $('body').mouseup(function(e){
		    	// Bail if clicking a prev/next button
		    	if ( $(e.target).hasClass('wampum-popup-prev') || $(e.target).hasClass('wampum-popup-next') ) {
		    		return false;
		    	}
		        // Set our popup as a variable
		        var content = popup.find('.wampum-popup-content');
		        /**
		         * If click is not on our popup
		         * If click is not on a child of our popup
		         */
		        if ( ! ( $(e.target).hasClass('wampum-popup-content') || $(e.target).parents().hasClass('wampum-popup-content') ) ) {
		        	closePopup( popup );
		        }
		    });

		}

		// Close popup with esc key
		$(document).keydown(function(e) {
		    switch(e.which) {
		        case 27: // esc
		        closePopup(popup);
		        break;

		        default: return; // exit this handler for other keys
		    }
		    e.preventDefault(); // prevent the default action (scroll / move caret)
		});

	}); // end each

	// Helper function to resize the child element inside .wampum-popup-content
	function resizeContent(element){
		// Clear it, so it can grow
		element.css('width', 'auto').css('height', 'auto');
		// Resize baby
		element.css(
			'height', element.parents('.wampum-popup-inner').height(),
			'max-height', '100%',
			'max-width', '100%'
		);
	}

	// Helper function to do the closing of a modal
	function closePopup( popup ) {
        popup.fadeOut('fast');
        // Disable ouibounce object if it was set
		if ( typeof _ouibounce != 'undefined' ) {
			_ouibounce.disable();
		}
		// Hide pagination, so it can fade in next time
		popup.find('.wampum-popup-prev').hide();
		popup.find('.wampum-popup-next').hide();
	}

})( this, jQuery );
