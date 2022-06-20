/**
 * @module
 * @description Javascript for the image component.
 */

import lazySizes from 'lazysizes';

/**
 * Load unloaded lazy images to make sure they're present for printing.
 */
const loadBeforePrint = () => {
	const lazyImages = document.querySelectorAll( 'img.lazyload' );
	lazyImages.forEach( image => lazySizes.loader.unveil( image ) );
};

/**
 * @function bindEvents
 * @description Bind the events for this module here.
 */
const bindEvents = () => {
	window.addEventListener( 'beforeprint', loadBeforePrint );
};

/**
 * @function init
 */
const init = () => {
	bindEvents();

	console.info( 'SquareOne Theme: Initialized Image component.' );
};

export default init;
