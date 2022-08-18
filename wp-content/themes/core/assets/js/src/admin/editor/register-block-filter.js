import { addFilter } from '@wordpress/hooks';

// Specify block name and align settings for it to keep.
const blockAlignments = {
	'core/embed': [ 'wide', 'full' ],
	'core/separator': [],
};

/**
 * @function init
 * @description Filters alignment setting for specified core blocks.
 */

const setAlignmentSupports = ( settings, name ) => {
	if ( ! ( name in blockAlignments ) ) {
		return settings;
	}
	return Object.assign( {}, settings, {
		supports: Object.assign( {}, settings.supports, { align: blockAlignments[ name ] } ),
	} );
};

/**
 * @function init
 * @description This filter has a standalone file for calling it separately from another hooks and before domReady.
 */

const init = () => {
	addFilter( 'blocks.registerBlockType', 'tribe/filter-alignment', setAlignmentSupports );
};

export default init;
