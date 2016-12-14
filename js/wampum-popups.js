/*!
 * jQuery Cookie Plugin v1.4.1
 * https://github.com/carhartl/jquery-cookie
 *
 * Copyright 2006, 2014 Klaus Hartl
 * Released under the MIT license
 */
!function(a){"function"==typeof define&&define.amd?define(["jquery"],a):"object"==typeof exports?module.exports=a(require("jquery")):a(jQuery)}(function(a){function c(a){return h.raw?a:encodeURIComponent(a)}function d(a){return h.raw?a:decodeURIComponent(a)}function e(a){return c(h.json?JSON.stringify(a):String(a))}function f(a){0===a.indexOf('"')&&(a=a.slice(1,-1).replace(/\\"/g,'"').replace(/\\\\/g,"\\"));try{return a=decodeURIComponent(a.replace(b," ")),h.json?JSON.parse(a):a}catch(a){}}function g(b,c){var d=h.raw?b:f(b);return a.isFunction(c)?c(d):d}var b=/\+/g,h=a.cookie=function(b,f,i){if(arguments.length>1&&!a.isFunction(f)){if(i=a.extend({},h.defaults,i),"number"==typeof i.expires){var j=i.expires,k=i.expires=new Date;k.setMilliseconds(k.getMilliseconds()+864e5*j)}return document.cookie=[c(b),"=",e(f),i.expires?"; expires="+i.expires.toUTCString():"",i.path?"; path="+i.path:"",i.domain?"; domain="+i.domain:"",i.secure?"; secure":""].join("")}for(var l=b?void 0:{},m=document.cookie?document.cookie.split("; "):[],n=0,o=m.length;n<o;n++){var p=m[n].split("="),q=d(p.shift()),r=p.join("=");if(b===q){l=g(r,f);break}b||void 0===(r=g(r))||(l[q]=r)}return l};h.defaults={},a.removeCookie=function(b,c){return a.cookie(b,"",a.extend({},c,{expires:-1})),!a.cookie(b)}});

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

			if ( oui['aggressive'] == 'true' ) {
				/**
				 * Force cookie name if aggressive mode is used
				 * This prevents a cookie being set that may be used elsewhere for timed/exit popup
				 * Otherwise it should still check for the cookie
				 * https://github.com/carlsednaoui/ouibounce/issues/116
				 */
				oui['cookieName'] = 'wampumPopupAggressive';
				// Set the ouibounce object as a variable
				var _ouibounce = ouibounce( popup[0], oui );
				// Force fire after given time
				setTimeout(function() {
					_ouibounce.fire();
				}, popup_vars.time );
			} else if ( ! $.cookie(oui['cookieName']) ) {
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
			popup.fadeOut('fast');
			// Disable ouibounce object if it was set
			if ( typeof _ouibounce != 'undefined' ) {
				_ouibounce.disable();
			}
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
		        if ( ! ( $(e.target).hasClass('wampum-popup-content') || $(e.target).parents().hasClass('wampum-popup-content') ) ) {
		            popup.fadeOut('fast');
		            // Disable ouibounce object if it was set
					if ( typeof _ouibounce != 'undefined' ) {
						_ouibounce.disable();
					}
		        }
		    });

		}

	}); // end each

})( this, jQuery );
