/**
 * External Dependencies
 */
const { resolve } = require( 'path' );
const webpack = require( 'webpack' );
const merge = require( 'webpack-merge' );
const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' );
const BundleAnalyzerPlugin = require( 'webpack-bundle-analyzer' ).BundleAnalyzerPlugin;

/**
 * Internal Dependencies
 */
const base = require( './configs/base.js' );
const splitChunks = require( './optimization/split-chunks.js' );
const pkg = require( '../package.json' );

module.exports = merge( base, {
	cache: true,
	mode: 'development',
	entry: {
		scripts: `./${ pkg.square1.paths.core_admin_js_src }index.js`,
	},
	output: {
		filename: '[name].js',
		chunkFilename: '[name].js',
		path: resolve( `${ __dirname }/../`, pkg.square1.paths.core_admin_js_dist ),
		publicPath: `/${ pkg.square1.paths.core_admin_js_dist }`,
	},
	devtool: 'eval-source-map',
	plugins: [
		new MiniCssExtractPlugin( {
			filename: '../../css/admin/[name].css',
		} ),
		new webpack.LoaderOptionsPlugin( {
			debug: true,
		} ),
		new BundleAnalyzerPlugin( {
			analyzerMode: 'static',
			reportFilename: resolve( `${ __dirname }/../`, 'reports/webpack-admin-bundle.html' ),
			openAnalyzer: false,
		} ),
	],
	optimization: {
		splitChunks,
		noEmitOnErrors: true, // NoEmitOnErrorsPlugin
		concatenateModules: true, //ModuleConcatenationPlugin
	},
} );
