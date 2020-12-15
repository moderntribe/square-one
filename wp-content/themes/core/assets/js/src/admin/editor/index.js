import hooks from './hooks';
import types from './types';
import * as tools from 'utils/tools';

/**
 * @function init
 * @description Initialize module
 */

const init = () => {
	hooks();
	types();

	if ( tools.getNodes( '#editor.block-editor__container', false, document, true )[ 0 ] ) {
		import( './preview' /* webpackChunkName:"editor-preview" */ ).then( ( module ) => {
			module.default();
		} );
	}
};

export default init;
