/**
 * External Dependencies
 */
const webpack = require( 'webpack' );
const merge = require( 'webpack-merge' );

/**
 * Internal Dependencies
 */
const base = require( './base.js' );
const splitChunks = require( '../optimization/split-chunks' );

module.exports = merge.strategy( {
	plugins: 'append',
} )( base, {
	cache: true,
	mode: 'development',
	output: {
		filename: '[name].[chunkhash].js',
		chunkFilename: '[name].[chunkhash].js',
	},
	devtool: 'eval-source-map',
	plugins: [
		new webpack.LoaderOptionsPlugin( {
			debug: true,
		} ),
	],
	optimization: {
		splitChunks,
		noEmitOnErrors: true, // NoEmitOnErrorsPlugin
		concatenateModules: true, //ModuleConcatenationPlugin
	},
} );
