/**
 * External Dependencies
 */
const { resolve } = require( 'path' );
const merge = require( 'webpack-merge' );
const glob = require( 'glob' );
const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' );
const BundleAnalyzerPlugin = require( 'webpack-bundle-analyzer' ).BundleAnalyzerPlugin;

/**
 * Internal Dependencies
 */
const devBase = require( './configs/dev-base.js' );
const pkg = require( '../package.json' );

module.exports = merge.strategy( {
	plugins: 'append',
} )( devBase, {
	entry: {
		scripts: [
			`./${ pkg.square1.paths.core_theme_js_src }index.js`,
			...glob.sync( `./${ pkg.square1.paths.core_theme_components }**/index.js` ),
		],
		integrations: [
			...glob.sync( `./${ pkg.square1.paths.core_theme_integrations }**/index.js` ),
			...glob.sync( `./${ pkg.square1.paths.core_theme_integrations }**/index.pcss` ),
		],
		master: [
			`./${ pkg.square1.paths.core_theme_pcss }master.pcss`,
		],
		print: [
			`./${ pkg.square1.paths.core_theme_pcss }print.pcss`,
		],
		components: [
			...glob.sync( `./${ pkg.square1.paths.core_theme_components }**/index.pcss` ),
		],
		legacy: [
			`./${ pkg.square1.paths.core_theme_pcss }legacy.pcss`,
		],
	},
	output: {
		path: resolve( `${ __dirname }/../`, pkg.square1.paths.core_theme_js_dist ),
		publicPath: `/${ pkg.square1.paths.core_theme_js_dist }`,
	},
	plugins: [
		new MiniCssExtractPlugin( {
			filename: '../../../css/dist/[name].css',
		} ),
		new BundleAnalyzerPlugin( {
			analyzerMode: 'static',
			reportFilename: resolve( `${ __dirname }/../`, 'reports/webpack-theme-bundle.html' ),
			openAnalyzer: false,
		} ),
	],
} );
