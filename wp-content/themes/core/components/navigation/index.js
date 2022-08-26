/* -----------------------------------------------------------------------------
 *
 * Component: Navigation Menu
 *
 * This file is just a clearing-house, see the css directory
 * and edit the source files found there.
 *
 * ----------------------------------------------------------------------------- */

import siteHeader from './js/site-header';

const el = {
	container: document.querySelector( '[data-js="c-site-header"]' ),
};

const init = () => {
	if ( ! el.container ) {
		return;
	}

	siteHeader( el.container );
};

export default init;
