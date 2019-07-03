const { resolve } = require( 'path' );
const webpack = require( 'webpack' );
const merge = require( 'webpack-merge' );
const common = require( './common.js' );
const rules = require( './rules.js' );
const vendor = require( './vendors' );
const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' );
const BundleAnalyzerPlugin = require( 'webpack-bundle-analyzer' ).BundleAnalyzerPlugin;
const pkg = require( '../package.json' );

module.exports = merge( common, {
	cache: true,
	mode: 'development',
	entry: {
		scripts: `./${ pkg._core_theme_js_src_path }index.js`,
		vendor: vendor.theme,
	},
	output: {
		filename: '[name].js',
		chunkFilename: '[name].[chunkhash].js',
		path: resolve( `${ __dirname }/../`, pkg._core_theme_js_dist_path ),
		publicPath: `/${ pkg._core_theme_js_dist_path }`,
	},
	devtool: 'eval-source-map',
	module: {
		rules: [
			rules.miniExtractPlugin,
		],
	},
	plugins: [
		new webpack.HashedModuleIdsPlugin(),
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
		namedModules: true, // NamedModulesPlugin()
		splitChunks: { // CommonsChunkPlugin()
			name: 'vendor',
			minChunks: 2,
		},
		noEmitOnErrors: true, // NoEmitOnErrorsPlugin
		concatenateModules: true, //ModuleConcatenationPlugin
	},
} );
