/**
 * External Dependencies
 */
const { resolve } = require( 'path' );
const merge = require( 'webpack-merge' );
const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' );
const BundleAnalyzerPlugin = require( 'webpack-bundle-analyzer' ).BundleAnalyzerPlugin;

/**
 * Internal Dependencies
 */
const prodBase = require( './configs/prod-base.js' );
const pkg = require( '../package.json' );
const entry = require( './entry/admin' );
const afterCompile = require( './after-emit' );

module.exports = merge.strategy( {
	plugins: 'append',
} )( prodBase, {
	entry,
	output: {
		path: resolve( `${ __dirname }/../`, pkg.square1.paths.core_admin_js_dist ),
		publicPath: `/${ pkg.square1.paths.core_admin_js_dist }`,
	},
	plugins: [
		new MiniCssExtractPlugin( {
			filename: '../../../css/dist/admin/[name].min.css',
		} ),
		new BundleAnalyzerPlugin( {
			analyzerMode: 'static',
			reportFilename: resolve( `${ __dirname }/../`, 'reports/webpack-admin-bundle-prod.html' ),
			openAnalyzer: false,
		} ),
		afterCompile.scripts.admin,
	],
} );
