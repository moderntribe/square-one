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
const entry = require( './entry/theme' );
const pkg = require( '../package.json' );
const afterCompile = require( './after-emit' );
const externals = require( './externals/theme' );

module.exports = merge.strategy( {
	plugins: 'append',
} )(
	prodBase,
	{
		entry,
		externals,
		output: {
			path: resolve( `${ __dirname }/../`, pkg.square1.paths.core_theme_js_dist ),
			publicPath: `/${ pkg.square1.paths.core_theme_js_dist }`,
		},
		plugins: [
			new MiniCssExtractPlugin( {
				filename: '../../../css/dist/theme/[name].min.css',
			} ),
			new BundleAnalyzerPlugin( {
				analyzerMode: 'static',
				reportFilename: resolve( `${ __dirname }/../`, 'reports/webpack-theme-bundle-prod.html' ),
				openAnalyzer: false,
			} ),
			afterCompile.styles,
			afterCompile.scripts.theme,
		],
	}
);
