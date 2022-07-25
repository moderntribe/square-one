/**
 * External Dependencies
 */
const { resolve } = require( 'path' );

/**
 * Internal Dependencies
 */
const pkg = require( '../package.json' );
const appBase = require( './configs/app-base.js' );
const localConfig = require( './configs/local' );

module.exports = {
	mode: 'development',
	entry: {
		scripts: `./${ pkg.square1.paths.core_apps_js_src }Example/index.js`,
	},
	output: {
		filename: 'app.js',
		path: resolve( `${ __dirname }/../`, 'public/js/' ),
		publicPath: `${ localConfig.protocol }://${ localConfig.proxy }:9000/`,
	},
	...appBase,
};
