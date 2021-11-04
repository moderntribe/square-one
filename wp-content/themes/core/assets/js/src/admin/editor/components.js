/**
 * @module
 * @exports init
 * @description Initializes theme component JS in the block editor context.
 */

import * as tests from 'utils/tests';
import * as tools from 'utils/tools';

const init = () => {
	// Note that initializing sliders in the block editor is incompatible with
	// service workers because it requires data that's not available in the
	// editor.
	if ( !tests.supportsWorkers && tools.getNodes( '[data-js="c-slider"]', false, document, true )[ 0 ] ) {
		import( 'components/slider' /* webpackChunkName:"editor-slider" */ ).then( ( module ) => {
			module.default();
		} );
	}

	console.info( 'SquareOne Admin: Initialized all components.' );
};

export default init;
