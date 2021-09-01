import hooks from './hooks';
import types from './types';
import * as tools from 'utils/tools';
import denyBlocks from './deny-blocks';

/**
 * @function init
 * @description Initialize module
 */

const init = () => {
	hooks();
	types();
	denyBlocks();

	if ( tools.getNodes( '#editor.block-editor__container', false, document, true )[ 0 ] ) {
		import( './preview' /* webpackChunkName:"editor-preview" */ ).then( ( module ) => {
			module.default();
		} );
	}
};

export default init;
