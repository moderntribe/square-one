

'use strict';

export default function dom_ready( fn ) {
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