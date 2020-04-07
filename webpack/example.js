const { resolve } = require( 'path' );
const merge = require( 'webpack-merge' );
const common = require( './common.js' );
const appCommon = require( './app-common.js' );
const pkg = require( '../package.json' );

module.exports = merge( common, {
	mode: 'development',
	entry: {
		scripts: `./${ pkg.square1.paths.core_apps_js_src }Example/index.js`,
	},
	output: {
		filename: 'app.js',
		path: resolve( `${ __dirname }/../`, 'public/js/' ),
		publicPath: 'https://localhost:3000/',
	},
	...appCommon,
} );
