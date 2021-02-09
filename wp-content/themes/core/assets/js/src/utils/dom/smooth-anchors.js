/**
 * Enable this module in your ready function to cause all hash links to smooth scroll sitewide!
 */

import * as tools from '../tools';
import scrollTo from '../dom/scroll-to';

const handleAnchorClick = ( e, options ) => {
	const target = document.getElementById( e.target.hash.substring( 1 ) );
	if ( ! target ) {
		return;
	}

	e.preventDefault();

	history.pushState( null, null, e.target.hash );

	scrollTo( {
		offset: -150,
		duration: 300,
		$target: $( target ),
		afterScroll: function() {
			if ( ! options.auto_focus ) {
				return;
			}

			if ( ! target.getAttribute( 'tabindex' ) ) {
				target.setAttribute( 'tabindex', '-1' );
			}

			target.focus();
		}
	} );
};

const bindEvents = ( options ) => {
	const anchorLinks = tools.convertElements( document.querySelectorAll( 'a[href^="#"]:not([href="#"])' ) );
	if ( ! anchorLinks.length ) {
		return;
	}

	anchorLinks.forEach( link => link.addEventListener( 'click', ( e ) => {
		handleAnchorClick( e, options );
	} ) );
};

const init = ( opts = {} ) => {
	const options = {
		auto_focus: false, // Focus the element after scolling
	};

	// merge options
	Object.assign( options, opts );

	bindEvents( options );
};

export default init;
