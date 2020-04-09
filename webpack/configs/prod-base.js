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
const minimizer = require( '../optimization/minimizer' );

module.exports = merge.strategy( {
	plugins: 'append',
} )( base, {
	cache: false,
	mode: 'production',
	devtool: false,
	output: {
		filename: '[name].[chunkhash:10].min.js',
		chunkFilename: '[name].[chunkhash:10].min.js',
	},
	plugins: [
		new webpack.DefinePlugin( {
			'process.env': { NODE_ENV: JSON.stringify( 'production' ) },
		} ),
		new webpack.LoaderOptionsPlugin( {
			debug: false,
		} ),
	],
	optimization: {
		splitChunks,
		noEmitOnErrors: true, // NoEmitOnErrorsPlugin
		concatenateModules: true, //ModuleConcatenationPlugin
		minimizer,
	},
} );
