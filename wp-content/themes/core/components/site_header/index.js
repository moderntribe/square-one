/**
 * Masthead Scripts
 */

import siteHeader from './js/site-header';

const el = {
	container: document.querySelector( '[data-js="c-site-header"]' ),
};

/**
 * @function init
 * @description Kick off this module's functions
 */

const init = () => {
	if ( ! el.container ) {
		return;
	}

	siteHeader( el.container );
};

export default init;
