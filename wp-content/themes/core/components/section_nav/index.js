/* -----------------------------------------------------------------------------
 *
 * Component: Section Nav
 *
 * This file is just a clearing-house, see the css directory
 * and edit the source files found there.
 *
 * ----------------------------------------------------------------------------- */

import { getNodes } from 'utils/tools';

const init = () => {
	const sectionNavs = getNodes( 'c-section-nav' );

	if ( sectionNavs.length ) {
		import( './js/section-nav' /* webpackChunkName:"sectionNav" */ ).then( ( module ) => {
			module.default( sectionNavs );
		} );
	}
};

export default init;
