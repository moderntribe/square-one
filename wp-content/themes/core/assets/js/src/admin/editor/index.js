import hooks from './hooks';
import types from './types';
import * as tools from 'utils/tools';
import denyBlocks from './deny-blocks';
import pageTemplateBlockFilter from './page-template-block-filter';

/**
 * @function init
 * @description Initialize module
 */

const init = () => {
	hooks();
	types();
	denyBlocks();
	pageTemplateBlockFilter();

	if ( tools.getNodes( '#editor.block-editor__container', false, document, true )[ 0 ] ) {
		import( './preview' /* webpackChunkName:"editor-preview" */ ).then( ( module ) => {
			module.default();
		} );
	}
};

export default init;
