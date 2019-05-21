const { resolve } = require( 'path' );
const webpack = require( 'webpack' );
const common = require( './common.js' );
const rules = require( './rules.js' );
const vendor = require( './vendors' );
const merge = require( 'webpack-merge' );
const TerserPlugin = require( 'terser-webpack-plugin' );
const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' );
const OptimizeCSSAssetsPlugin = require( 'optimize-css-assets-webpack-plugin' );
const pkg = require( '../package.json' );

module.exports = merge( common, {
	cache: false,
	mode: 'production',
	entry: {
		scripts: `./${ pkg._core_theme_js_src_path }index.js`,
		vendorWebpack: vendor.theme,
	},
	output: {
		filename: '[name].min.js',
		chunkFilename: '[name].[chunkhash].min.js',
		path: resolve( `${ __dirname }/../`, pkg._core_theme_js_dist_path ),
		publicPath: `/${ pkg._core_theme_js_dist_path }`,
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
		new webpack.HashedModuleIdsPlugin(),
		new webpack.LoaderOptionsPlugin( {
			debug: false,
		} ),
		new MiniCssExtractPlugin( {
			filename: '../../css/dist/[name].min.css',
		} ),
	],
	optimization: {
		namedModules: true, // NamedModulesPlugin()
		splitChunks: { // CommonsChunkPlugin()
			name: 'vendorWebpack',
			minChunks: 2,
		},
		noEmitOnErrors: true, // NoEmitOnErrorsPlugin
		concatenateModules: true, //ModuleConcatenationPlugin
		minimizer: [
			new TerserPlugin( {
				cache: true,
				parallel: true,
				sourceMap: true,
				terserOptions: {
					compress: {
						warnings: false,
						drop_console: true,
					},
					output: {
						comments: false,
					},
				},
			} ),
			new OptimizeCSSAssetsPlugin( {} ),
		],
	},
} );
