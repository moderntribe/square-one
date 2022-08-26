/**
 * @module
 * @exports init
 * @description Initializes theme component JS in the block editor context.
 */

import * as tools from 'utils/tools';

const setGalleryInputWidth = () => {
	const acfGalleries = tools.getNodes( '.acf-gallery.-open', true, document, true );

	if ( ! acfGalleries ) {
		return;
	}

	acfGalleries.forEach( gallery => {
		gallery.style.setProperty( '--sidebar-width', `${ gallery.clientWidth }px` );
	} );
};

const bindEvents = () => {
	if ( window.acf ) {
		window.acf.addAction( 'append', setGalleryInputWidth );
	}
};

const init = () => {
	bindEvents();
	console.info( 'SquareOne Admin: Update ACF Gallery Input Width.' );
};

export default init;
