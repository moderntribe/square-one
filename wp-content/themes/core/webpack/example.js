/**
 * External Dependencies
 */
const { resolve } = require( 'path' );
const merge = require( 'webpack-merge' );

/**
 * Internal Dependencies
 */
const base = require( './configs/base.js' );
const appBase = require( './configs/app-base.js' );
const pkg = require( '../package.json' );

module.exports = merge( base, {
	mode: 'development',
	entry: {
		scripts: `./${ pkg.square1.paths.core_apps_js_src }Example/index.js`,
	},
	output: {
		filename: 'app.js',
		path: resolve( `${ __dirname }/../`, 'public/js/' ),
		publicPath: 'https://localhost:3000/',
	},
	...appBase,
} );
