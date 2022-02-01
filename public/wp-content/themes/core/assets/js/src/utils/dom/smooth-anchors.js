/**
 * Enable this module in your ready function to cause all hash links to smooth scroll sitewide!
 */

import * as tools from '../tools';
import { trigger } from '../events';
import scrollTo from '../dom/scroll-to';

const handleAnchorClick = ( e ) => {
	const anchor = e.currentTarget;
	const hash = anchor.hash.substring( 1 );
	const target = document.getElementById( hash );
	if ( ! target ) {
		return;
	}

	e.preventDefault();

	history.pushState( null, null, e.target.hash );

	scrollTo( {
		offset: -150,
		duration: 300,
		$target: $( target ),
		afterScroll: () => {
			trigger( { event: 'modern_tribe/trigger_smooth_anchor', native: false } );
		},
	} );
};

const bindEvents = () => {
	const anchorLinks = tools.convertElements( document.querySelectorAll( 'a[href^="#"]:not([href="#"])' ) );
	if ( ! anchorLinks.length ) {
		return;
	}

	anchorLinks.forEach( link => link.addEventListener( 'click', handleAnchorClick ) );
};

const init = () => {
	bindEvents();
};

export default init;
