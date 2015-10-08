'use strict';

import Utils from './base';

export default class Events extends Utils {

	constructor( options ) {
		super( options );
	}

	on( el, name, handler ) {
		if ( el.addEventListener ) {
			el.addEventListener( name, handler );
		}
		else {
			el.attachEvent( 'on' + name, function() {
				handler.call( el );
			} );
		}
	}

	ready( fn ) {
		if ( document.readyState !== 'loading' ) {
			fn();
		}
		else if ( document.addEventListener ) {
			document.addEventListener( 'DOMContentLoaded', fn );
		}
		else {
			document.attachEvent( 'onreadystatechange', function() {
				if ( document.readyState !== 'loading' ) {
					fn();
				}
			} );
		}
	}

	static trigger( opts ){

		let event,
			options = _.assign( {
				data  : {},
				el    : document,
				event : '',
				native: true
			}, opts );

		if ( options.native ) {
			event = document.createEvent( 'HTMLEvents' );
			event.initEvent( options.event, true, false );
		}
		else {
			if ( window.CustomEvent ) {
				event = new CustomEvent( options.event, {detail: options.data} );
			}
			else {
				event = document.createEvent( 'CustomEvent' );
				event.initCustomEvent( options.event, true, true, options.data );
			}
		}

		options.el.dispatchEvent( event );

	}

}