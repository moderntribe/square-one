/**
 * @module
 * @exports init
 * @description Initializes theme component JS in the block editor context.
 */

import * as tools from 'utils/tools';

import sectionNav from 'components/section_nav';

const init = () => {
	sectionNav();

	if ( tools.getNodes( '[data-js="c-slider"]', false, document, true )[ 0 ] ) {
		import( 'components/slider' /* webpackChunkName:"editor-slider" */ ).then( ( module ) => {
			module.default();
		} );
	}

	console.info( 'SquareOne Admin: Initialized all components.' );
};

export default init;
