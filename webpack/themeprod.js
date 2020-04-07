const { resolve } = require( 'path' );
const webpack = require( 'webpack' );
const common = require( './common.js' );
const dev = require( './themedev' );
const splitChunks = require( './split-chunks.js' );
const merge = require( 'webpack-merge' );
const minimizer = require( './minimizer' );
const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' );
const BundleAnalyzerPlugin = require( 'webpack-bundle-analyzer' ).BundleAnalyzerPlugin;
const pkg = require( '../package.json' );

module.exports = merge.strategy( {
	cache: 'replace',
	mode: 'replace',
	entry: 'replace',
	output: 'replace',
	plugins: 'replace',
	optimization: 'replace',
} )(
	common,
	dev,
	{
		cache: false,
		devtool: false,
		mode: 'production',
		entry: {
			scripts: dev.entry.scripts,
			integrations: dev.entry.integrations,
		},
		output: {
			filename: '[name].min.js',
			chunkFilename: '[name].min.js',
			path: resolve( `${ __dirname }/../`, pkg.square1.paths.core_theme_js_dist ),
			publicPath: `/${ pkg.square1.paths.core_theme_js_dist }`,
		},
		plugins: [
			new webpack.DefinePlugin( {
				'process.env': { NODE_ENV: JSON.stringify( 'production' ) },
			} ),
			new webpack.LoaderOptionsPlugin( {
				debug: false,
			} ),
			new MiniCssExtractPlugin( {
				filename: '../../css/dist/[name].min.css',
			} ),
			new BundleAnalyzerPlugin( {
				analyzerMode: 'static',
				reportFilename: resolve( `${ __dirname }/../`, 'reports/webpack-theme-bundle-prod.html' ),
				openAnalyzer: false,
			} ),
		],
		optimization: {
			splitChunks,
			noEmitOnErrors: true, // NoEmitOnErrorsPlugin
			concatenateModules: true, //ModuleConcatenationPlugin
			minimizer,
		},
	}
);
