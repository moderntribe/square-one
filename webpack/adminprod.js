const { resolve } = require( 'path' );
const webpack = require( 'webpack' );
const common = require( './common.js' );
const rules = require( './rules.js' );
const splitChunks = require( './split-chunks.js' );
const minimizer = require( './minimizer' );
const merge = require( 'webpack-merge' );
const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' );
const BundleAnalyzerPlugin = require( 'webpack-bundle-analyzer' ).BundleAnalyzerPlugin;
const pkg = require( '../package.json' );

module.exports = merge( common, {
	cache: false,
	mode: 'production',
	devtool: false,
	entry: {
		scripts: `./${ pkg.square1.paths.core_admin_js_src }index.js`,
	},
	output: {
		filename: '[name].min.js',
		chunkFilename: '[name].min.js',
		path: resolve( `${ __dirname }/../`, pkg.square1.paths.core_admin_js_dist ),
		publicPath: `/${ pkg.square1.paths.core_admin_js_dist }`,
	},
	module: {
		rules: [
			rules.miniExtractPlugin,
		],
	},
	plugins: [
		new webpack.DefinePlugin( {
			'process.env': { NODE_ENV: JSON.stringify( 'production' ) },
		} ),
		new webpack.LoaderOptionsPlugin( {
			debug: false,
		} ),
		new MiniCssExtractPlugin( {
			filename: '../../css/admin/dist/[name].min.css',
		} ),
		new BundleAnalyzerPlugin( {
			analyzerMode: 'static',
			reportFilename: resolve( `${ __dirname }/../`, 'reports/webpack-admin-bundle-prod.html' ),
			openAnalyzer: false,
		} ),
	],
	optimization: {
		splitChunks,
		noEmitOnErrors: true, // NoEmitOnErrorsPlugin
		concatenateModules: true, //ModuleConcatenationPlugin
		minimizer,
	},
} );
