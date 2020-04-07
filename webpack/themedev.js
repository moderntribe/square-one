/**
 * External Dependencies
 */
const { resolve } = require( 'path' );
const webpack = require( 'webpack' );
const merge = require( 'webpack-merge' );
const glob = require( 'glob' );
const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' );
const BundleAnalyzerPlugin = require( 'webpack-bundle-analyzer' ).BundleAnalyzerPlugin;

/**
 * Internal Dependencies
 */
const common = require( './configs/base.js' );
const splitChunks = require( './optimization/split-chunks.js' );
const pkg = require( '../package.json' );

module.exports = merge( common, {
	cache: true,
	mode: 'development',
	entry: {
		scripts: [
			`./${ pkg.square1.paths.core_theme_js_src }index.js`,
			...glob.sync( `./${ pkg.square1.paths.core_theme_components }**/index.js` ),
		],
		integrations: [
			...glob.sync( `./${ pkg.square1.paths.core_theme_integrations }**/index.js` ),
		],
	},
	output: {
		filename: '[name].js',
		chunkFilename: '[name].js',
		path: resolve( `${ __dirname }/../`, pkg.square1.paths.core_theme_js_dist ),
		publicPath: `/${ pkg.square1.paths.core_theme_js_dist }`,
	},
	devtool: 'eval-source-map',
	plugins: [
		new MiniCssExtractPlugin( {
			filename: '../../css/[name].css',
		} ),
		new webpack.LoaderOptionsPlugin( {
			debug: true,
		} ),
		new BundleAnalyzerPlugin( {
			analyzerMode: 'static',
			reportFilename: resolve( `${ __dirname }/../`, 'reports/webpack-theme-bundle.html' ),
			openAnalyzer: false,
		} ),
	],
	optimization: {
		splitChunks,
		noEmitOnErrors: true, // NoEmitOnErrorsPlugin
		concatenateModules: true, //ModuleConcatenationPlugin
	},
} );
