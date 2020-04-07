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
const devBase = require( './configs/dev-base.js' );
const pkg = require( '../package.json' );

module.exports = merge.strategy( {
	plugins: 'append',
} )( devBase, {
	entry: {
		scripts: `./${ pkg.square1.paths.core_admin_js_src }index.js`,
	},
	output: {
		path: resolve( `${ __dirname }/../`, pkg.square1.paths.core_admin_js_dist ),
		publicPath: `/${ pkg.square1.paths.core_admin_js_dist }`,
	},
	plugins: [
		new MiniCssExtractPlugin( {
			filename: '../../css/admin/[name].css',
		} ),
		new BundleAnalyzerPlugin( {
			analyzerMode: 'static',
			reportFilename: resolve( `${ __dirname }/../`, 'reports/webpack-admin-bundle.html' ),
			openAnalyzer: false,
		} ),
	],
} );
