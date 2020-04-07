/**
 * External Dependencies
 */
const { sync } = require( 'glob' );

/**
 * Internal Dependencies
 */
const { square1: { paths } } = require( '../../package.json' );

const REGEX = /integrations\/(.*)\/index\.(js|pcss)/;

/**
 *  Build out a list of usable integration asset files
 */
const assets = [
	...sync( `./${ paths.core_theme_integrations }**/index.pcss` ),
	...sync( `./${ paths.core_theme_integrations }**/index.js` ),
];

/**
 * Move assets under directory name for the entry
 *
 *  @example
 *  {
 *    'gravity-forms': [
 *        './wp-content/themes/core/integrations/gravity-forms/index.pcss',
 *        './wp-content/themes/core/integrations/gravity-forms/index.js'
 *    ]
 *   }
 */
const entries = assets.reduce( ( acc, path ) => {
	// eslint-disable-next-line no-unused-vars
	const [ _, dirname ] = path.match( REGEX );

	if ( acc[ dirname ] ) {
		acc[ dirname ].push( path );
	} else {
		acc[ dirname ] = [ path ];
	}

	return acc;
}, {} );

module.exports = entries;
