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
		filename: '[name].[chunkhash:10].js',
		chunkFilename: '[name].[chunkhash:10].js',
	},
	devtool: 'source-map',
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
