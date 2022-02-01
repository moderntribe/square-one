/* -----------------------------------------------------------------------------
 *
 * Component: Slider
 *
 * This file is just a clearing-house, see the js directory
 * and edit the source files found there.
 *
 * ----------------------------------------------------------------------------- */

const init = () => {
	if ( document.querySelector( '[data-js="c-slider"]' ) ) {
		import( './js/slider' /* webpackChunkName:"slider" */ ).then( ( module ) => {
			module.default();
		} );
	}
};

export default init;
