/**
 * External Dependencies
 */
const { resolve } = require( 'path' );

/**
 * Internal Dependencies
 */
const appBase = require( './configs/app-base.js' );
const pkg = require( '../package.json' );

module.exports = {
	mode: 'development',
	entry: {
		scripts: `./${ pkg.square1.paths.core_apps_js_src }Example/index.js`,
	},
	output: {
		filename: 'app.js',
		path: resolve( `${ __dirname }/../`, 'public/js/' ),
		publicPath: 'http://localhost:3000/',
	},
	...appBase,
};
